<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakePaymentRequest;
use App\Http\Requests\MakeRefundRequest;
use App\Http\Requests\SendInvoiceEmailRequest;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\Invoice;
use App\Models\InvoiceCancelReason;
use App\Models\InvoiceOption;
use App\Models\InvoicePayment;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use App\Models\PaymentMethod;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function list(Request $request){
        $user_id = $request->user()->id;
        $status = ($request->has('status') && !empty($request->query('status')) ? $request->query('status') : 'All');
        $payStatus = ($request->has('pay_status') && !empty($request->query('pay_status')) ? explode(',', $request->query('pay_status')) : []);
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? strtolower($request->query('order')) : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

    
        $query = Invoice::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.billing', 'job.property', 'form', 'user', 'user.company', 'property', 'billing']);
        if(!empty($status) && $status !== 'All'): $query->where('status', $status); endif;
        if(!empty($payStatus) && count($payStatus) > 0): $query->whereIn('pay_status', $payStatus); endif;
        if (!empty($queryStr)):
            $query->whereHas('customer', function ($q) use ($queryStr) {
                $q->where('full_name', 'LIKE', '%' . $queryStr . '%');
            })->orWhereHas('job.property', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            });
        endif;
        $query->where('created_by', $user_id);

        $validSortFields = ['id', 'created_at', 'updated_at', 'inspection_date', 'next_inspection_date'];
        $sortField = in_array($sortField, $validSortFields) ? $sortField : 'id';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);

        

        $limit = $request->query('limit', -1);

        if ($limit === -1) {
            $records = $query->get();
            return response()->json([
                'success' => true,
                'data' => $records,
                'meta' => [
                    'total' => $records->count(),
                    'per_page' => -1,
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => 1,
                    'to' => $records->count(),
                ]
            ]);
        } else {
            $limit = max(1, (int)$request->query('limit', 10));
            $page = max(1, (int)$request->query('page', 1));
            $records = $query->paginate($limit, ['*'], 'page', $page);
            $records = $query->paginate($limit, ['*'], 'page', max(1, $page));

            return response()->json([
                'success' => true,
                'data' => $records->items(),
                'meta' => [
                    'total' => $records->total(),
                    'per_page' => $records->perPage(),
                    'current_page' => $records->currentPage(),
                    'last_page' => $records->lastPage(),
                    'from' => $records->firstItem(),
                    'to' => $records->lastItem(),
                ]
            ]);
        }
    }

    public function store(Request $request){
        $user_id = $request->user()->id;
        $user = User::find($user_id);
        $company = (isset($user->companies[0]) && !empty($user->companies[0]) ? $user->companies[0] : []);
        $job_form_id = $request->job_form_id;
        $form = JobForm::find($job_form_id);

        $invoice_id = (isset($request->invoice_id) && $request->invoice_id > 0 ? $request->invoice_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $property = CustomerProperty::find($customer_property_id);

        /* Create Job If Empty */
        if($customer_job_id == 0):
            $jobName = $this->createJobName($request->options);
            $jobRefNo = $this->generateReferenceNo($customer_id, $company);
            $customerJob = CustomerJob::create([
                'customer_id' => $customer_id,
                'billing_address_id' => $request->billing_address_id ?? null,
                'customer_property_id' => $customer_property_id,
                'description' => $jobName,
                'details' => null,
                'reference_no' => $jobRefNo,
                'customer_job_status_id' => 1,

                'created_by' => auth()->user()->id
            ]);
            $customer_job_id = ($customerJob->id ? $customerJob->id : $customer_job_id);
        endif;
        /* Create Job If Empty */

        /* Store or Update Record */
        if($customer_job_id > 0):
            $invoice = Invoice::updateOrCreate(['id' => $invoice_id, 'job_form_id' => $job_form_id ], [
                'company_id' => auth()->user()->companies->pluck('id')->first(),
                'customer_id' => $customer_id,
                'billing_address_id' => $request->billing_address_id ?? null,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,
                'customer_property_id' => $customer_property_id,
                
                'issued_date' => (isset($request->issued_date) && !empty($request->issued_date) ? date('Y-m-d', strtotime($request->issued_date)) : date('Y-m-d')),
                'expire_date' => date('Y-m-d', strtotime("+30 days")),
                
                'updated_by' => $user_id,
            ]);
            
            if($invoice->id):
                $invoice_number = $this->generateInvoiceNumber($invoice->id);
                $options = $request->options;
                InvoiceOption::where('invoice_id', $invoice->id)->forceDelete();
                if(!empty($options)):
                    foreach($options as $key => $value):
                        InvoiceOption::create([
                            'invoice_id' => $invoice->id,
                            'name' => $key,
                            'value' => $value
                        ]);
                    endforeach;
                endif;

                
                $existRow = InvoiceOption::where('invoice_id', $invoice->id)->where('name', 'invoiceExtra')->get()->first();
                $theData = (isset($existRow->id) && !empty($existRow->id) ? $existRow->value : []);
                $invoiceExtra = [
                    'non_vat_invoice' => (isset($request->non_vat_invoice) && $request->non_vat_invoice == 1 ? 1 : 0),
                    'vat_number' => (isset($request->vat_number) && !empty($request->vat_number) ? $request->vat_number : null),
                ];
                if(!isset($theData->payment_term) || empty($theData->payment_term)):
                    $invoiceExtra['payment_term'] = (isset($company->bank->payment_term) && !empty($company->bank->payment_term) ? $company->bank->payment_term : null);
                else:
                    $invoiceExtra['payment_term'] = $theData->payment_term;
                endif;
                InvoiceOption::where('invoice_id', $invoice->id)->where('name', 'invoiceExtra')->forceDelete();
                InvoiceOption::create([
                    'invoice_id' => $invoice->id,
                    'name' => 'invoiceExtra',
                    'value' => $invoiceExtra
                ]);

                // Update Customer Job Amount
                CustomerJob::where('id', $customer_job_id)->update(['estimated_amount' => $request->estimated_amount]);

                return response()->json([
                    'success' => true,
                    'message' => 'Invoice successfully created.',
                    'data' =>  Invoice::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'options'])->findOrFail($invoice->id),
                ], 200);
            else:
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later or contact with the administrator.'
                ], 400);
            endif;
        else:
            return response()->json([
                'success' => false,
                'message' => 'Jobs not found. Please select a job.'
            ], 400);
        endif;
        /* Store or Update Record */
    }

    public function createJobName($options){
        $invoiceItems = $options['invoiceItems'];
        $jobName = [];
        if(!empty($invoiceItems)):
            foreach($invoiceItems as $items):
                $jobName[] = $items['description'];
            endforeach;
        endif;

        return (!empty($jobName) ? implode(' ', $jobName) : 'New Job');
    }


    public function edit($invoice_id, Request $request){
        try {
            $invoice = Invoice::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'property', 'billing'])
                        ->find($invoice_id);

            $fileName = $this->generatePdfFileName($invoice->id);
            $pdf_url = Storage::disk('public')->url('invoices/'.$invoice->created_by.'/'.$fileName);
            
            $data = [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'invoice' => [
                    'status' => $invoice->status,
                    'pay_status' => $invoice->pay_status,
                    'expire_date' => $invoice->expire_date,
                ],
                'issued_date' => (isset($invoice->issued_date) && !empty($invoice->issued_date) ? date('d-m-Y', strtotime($invoice->issued_date)) : ''),
                'job' => $invoice->job,
                'customer' => $invoice->customer,
                'job_address' => $invoice->job->property,
                'url' => $pdf_url
            ];

            $billingAddress = null;
            if(isset($invoice->billing->id) && $invoice->billing->id > 0):
                $billingAddress = $invoice->billing;
            elseif(isset($invoice->job->billing->id) && $invoice->job->billing->id > 0):
                $billingAddress = $invoice->job->billing;
            else:
                $billingAddress = $invoice->customer->address;
            endif;
            if($billingAddress):
                $data['billing_address'] = [
                    'id' => $billingAddress->id ?? '0',
                    'address_line_1' => $billingAddress->address_line_1 ?? '',
                    'address_line_2' => $billingAddress->address_line_2 ?? '',
                    'postal_code' => $billingAddress->postal_code ?? '',
                    'state' => $billingAddress->state ?? '',
                    'city' => $billingAddress->city ?? '',
                    'country' => $billingAddress->country ?? '',
                    'latitude' => $billingAddress->latitude ?? '',
                    'longitude' => $billingAddress->longitude ?? '',
                ];
            endif;

            $data['invoiceItemsCount'] = 0;
            $data['invoiceItems'] = $data['invoiceDiscounts'] = $data['invoiceAdvance'] = $data['invoiceExtra'] = [];
            $data['invoiceNotes'] = (isset($invoice->available_options->invoiceNotes) && !empty($invoice->available_options->invoiceNotes) ? $invoice->available_options->invoiceNotes : '');
            
            if(isset($invoice->available_options->invoiceItems) && !empty($invoice->available_options->invoiceItems)):
                if(isset($invoice->available_options->invoiceItems) && !empty($invoice->available_options->invoiceItems)):
                    $q = 1;
                    foreach($invoice->available_options->invoiceItems as $item):
                        $data['invoiceItems'][$q] = (array) $item;

                        $data['invoiceItemsCount'] += 1;
                        $q++;
                    endforeach;
                endif;
            endif;
            if(isset($invoice->available_options->invoiceDiscounts) && !empty($invoice->available_options->invoiceDiscounts)):
                $invoiceDiscounts = (array) $invoice->available_options->invoiceDiscounts;
                $data['invoiceDiscounts'] = $invoiceDiscounts;
            endif;
            if(isset($invoice->available_options->invoiceExtra) && !empty($invoice->available_options->invoiceExtra)):
                $invoiceExtra = (array) $invoice->available_options->invoiceExtra;
                $data['invoiceExtra'] = $invoiceExtra;
            endif;
            if(isset($invoice->available_options->invoiceAdvance) && !empty($invoice->available_options->invoiceAdvance)):
                $invoiceAdvance = (array) $invoice->available_options->invoiceAdvance;
                $data['invoiceAdvance'] = $invoiceAdvance;
            endif;

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        }catch(ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found. The requested (ID: '.$invoice_id.') does not exist or may have been deleted.',
            ], 404);
            
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function send(SendInvoiceEmailRequest $request){
        $invoice_id = $request->invoice_id;
        $ccMail = (!empty($request->cc_email_address) ? explode(',', $request->cc_email_address) : []);
        $subject = $request->subject;
        $content = $request->content;
        $customerEmail = $request->customer_email;
        try {
            $invoice = Invoice::findOrFail($invoice_id);
            $updateResult = $invoice->update([
                'status' => 'Send'
            ]);

            if (!$updateResult) {
                throw new \Exception("Failed to update record status");
            }

            $contact = CustomerContactInformation::where('customer_id', $invoice->customer_id)->first();
            if ($contact->email !== $customerEmail){
                $updated = $contact->update([
                    'email' => $customerEmail
                ]);
                if (!$updated) {
                    throw new \RuntimeException('Failed to update customer email.');
                }
            }

            $emailSent = false;
            $emailError = null;
            
            try {
                $emailSent = $this->sendEmail($invoice_id, $customerEmail, $ccMail, $subject, $content);
            } catch (\Exception $e) {
                $emailError = $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'message' => $emailSent 
                    ? 'Invoice status has been updated and emailed to the customer'
                    : 'Invoice status has been updated but email failed: ' . 
                    ($emailError ?: 'Invalid or empty email address'),
                'invoice_id' => $invoice_id
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found. The requested record (ID: '.$invoice_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function sendEmail($invoice_id, $customerEmail, $ccMail = [], $subject, $content){
        $invoice = Invoice::with([
            'customer', 
            'property', 
            'customer.address', 
            'customer.contact', 
            'job', 
            'job.property', 
            'job.calendar',
            'job.calendar.slot',
            'form', 
            'user', 
            'user.company'])->find($invoice_id);
        $companyName = $invoice->user->companies->pluck('company_name')->first();
        $companyEmail = $invoice->user->companies->pluck('company_email')->first();
        $customerName = (isset($invoice->customer->full_name) && !empty($invoice->customer->full_name) ? $invoice->customer->full_name : '');
        if(!empty($customerEmail)):
            $emailData = $this->renderEmailTemplate($invoice, $subject, $content);

            $subject = (isset($emailData['subject']) && !empty($emailData['subject']) ? $emailData['subject'] : $invoice->form->name);
            $templateTitle = $subject;
            $content = (isset($emailData['content']) && !empty($emailData['content']) ? $emailData['content'] : '');
            $ccMail[] = $invoice->user->email;

            if($content == ''):
                $content .= 'Hi '.$customerName.',<br/><br/>';
                $content .= 'Please check attachment for details.<br/><br/>';
                $content .= 'Thanks & Regards<br/>';
                $content .= env('APP_NAME', 'Gas Safety Engineer');
            endif;
            
            $sendTo = [$customerEmail];
            $configuration = [
                'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'smtp_port' => env('MAIL_PORT', '587'),
                'smtp_username' => env('MAIL_USERNAME', 'info@gascertificate.co.uk'),
                'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
                'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
                
                // 'from_email'    => env('MAIL_FROM_ADDRESS', 'info@gascertificate.co.uk'),
                // 'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'),
            ];
            $configuration['from_name'] = !empty($companyName) ? $companyName : $invoice->user->name; 
            $configuration['from_email'] = !empty($companyEmail) ? $companyEmail : $invoice->user->email; 

            $attachmentFiles = [];
            $fileName = $this->generatePdfFileName($invoice->id); 
            if (Storage::disk('public')->exists('invoices/'.$invoice->created_by.'/'.$fileName)):
                $attachmentFiles[0] = [
                    "pathinfo" => 'invoices/'.$invoice->created_by.'/'.$fileName,
                    "nameinfo" => $fileName,
                    "mimeinfo" => 'application/pdf',
                    "disk" => 'public'
                ];
            endif;
            if(isset($emailData['attachmentFiles']) && !empty($emailData['attachmentFiles'])):
                $attachmentFiles = array_merge($attachmentFiles, $emailData['attachmentFiles']);
            endif;

            GCEMailerJob::dispatch($configuration, $sendTo, new GCESendMail($subject, $content, $attachmentFiles, $templateTitle), $ccMail);
            return true;
        else:
            return false;
        endif;
    }

    public function renderEmailTemplate(Invoice $invoice, $subject, $content): array {
        // Build shortcode replacements
        $companyName = $invoice->user->companies->pluck('company_name')->first();
        $shortcodes = [
            ':customername'         => $invoice->customer->full_name ?? '',
            ':customercompany'      => $invoice->customer->company_name ?? '',
            ':jobref'               => $invoice->job->reference_no ?? '',
            ':jobbuilding'          => isset($invoice->job->property->address_line_1) && !empty($invoice->job->property->address_line_1) ? $invoice->job->property->address_line_1 : '',
            ':jobstreet'            => isset($invoice->job->property->address_line_2) && !empty($invoice->job->property->address_line_1) ? $invoice->job->property->address_line_2 : '',
            ':jobregion'            => isset($invoice->job->property->state) && !empty($invoice->job->property->state) ? $invoice->job->property->state : '',
            ':jobpostcode'          => isset($invoice->job->property->postal_code) && !empty($invoice->job->property->postal_code) ? $invoice->job->property->postal_code : '',
            ':jobtown'              => isset($invoice->job->property->city) && !empty($invoice->job->property->city) ? $invoice->job->property->city : '',
            ':propertyaddress'      => isset($invoice->property->full_address) && !empty($invoice->property->full_address) ? $invoice->property->full_address : '',
            ':contactphone'         => isset($invoice->user->mobile) && !empty($invoice->user->mobile) ? $invoice->user->mobile : '',
            ':companyname'          => $companyName ?? '',
            ':engineername'         => $invoice->user->name ?? '',
            ':eventdate'            => isset($invoice->job->calendar->date) && !empty($invoice->job->calendar->date) ? date('d-m-Y', strtotime($invoice->job->calendar->date)) : '',
            ':eventtime'            => (isset($invoice->job->calendar->slot->start) && !empty($invoice->job->calendar->slot->start) ? date('H:i', strtotime($invoice->job->calendar->slot->start)) : '').(isset($invoice->job->calendar->slot->end) && !empty($invoice->job->calendar->slot->end) ? ' - '.date('H:i', strtotime($invoice->job->calendar->slot->end)) : ''),
            // Add more shortcodes as needed
        ];

        // Replace shortcodes in subject and content
        $subject = str_replace(array_keys($shortcodes), array_values($shortcodes), $subject);
        $content = str_replace(array_keys($shortcodes), array_values($shortcodes), $content);

        $attachmentFiles = [];
        if(isset($template->attachment) && $template->attachment->count() > 0):
            $i = 1;
            foreach($template->attachment as $attachment):
                if(isset($attachment->download_url) && !empty($attachment->download_url)):
                    $attachmentFiles[$i] = [
                        "pathinfo" => 'template_attachments/'.$template->id.'/'.$attachment->current_file_name,
                        "nameinfo" => $attachment->current_file_name,
                        "mimeinfo" => $attachment->doc_type,
                        "disk" => 'public'
                    ];
                    $i++;
                endif;
            endforeach;
        endif;

        return [
            'subject' => $subject,
            'content' => $content,
            'attachmentFiles' => $attachmentFiles,
        ];
    }

    public function generatePdf($invoice_id){
        $invoice = Invoice::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'options', 'billing', 'payments'])->find($invoice_id);
       
        //dd($record->available_options->invoiceItems);
        $logoPath = resource_path('images/gas_safe_register_yellow.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $report_title = 'Invoice of '.$invoice->invoice_number;

        $userSignBase64 = (isset($record->user->signature) && Storage::disk('public')->exists($record->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($record->user->signature->filename)) : '');
        
        $VIEW = 'app.invoice.pdf';
        $fileName = $this->generatePdfFileName($invoice->id); 
        if (Storage::disk('public')->exists('invoices/'.$invoice->created_by.'/'.$fileName)) {
            Storage::disk('public')->delete('invoices/'.$invoice->created_by.'/'.$fileName);
        }
        $pdf = Pdf::loadView($VIEW, compact('invoice', 'logoBase64', 'report_title', 'userSignBase64'))
            ->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'portrait') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('invoices/'.$invoice->created_by.'/'.$fileName, $content );

        return Storage::disk('public')->url('invoices/'.$invoice->created_by.'/'.$fileName);
    }

    function generatePdfFileName($invoice_id){
        $invoice = Invoice::with('job', 'job.property')->find($invoice_id);
        $address_line_1 = $invoice->job->property->address_line_1;
        $address_line_2 = $invoice->job->property->address_line_2;
        $postal_code = $invoice->job->property->postal_code;
        $invoice_number = $invoice->invoice_number;

        // Concatenate the fields
        $fileName = "{$address_line_1}_{$address_line_2}_{$postal_code}_{$invoice_number}";
        // Replace any non-alphanumeric characters with underscores
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '_', $fileName);
        // Replace multiple consecutive underscores with a single underscore
        $fileName = preg_replace('/_+/', '_', $fileName);
        // Trim underscores from start and end
        $fileName = trim($fileName, '_');
        // Optionally lowercase
        $fileName = Str::lower($fileName);
        // Add PDF extension
        return $fileName . '.pdf';
    }

    public function generateInvoiceNumber($invoice_id){
        $invoice = Invoice::find($invoice_id);
        $user_id = $invoice->created_by;
        if(empty($invoice->invoice_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $invoice->job_form_id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastInvoice = Invoice::where('created_by', $user_id)->where('id', '!=', $invoice_id)->orderBy('id', 'DESC')->get()->first();
            $lastInvoiceNo = (isset($userLastInvoice->invoice_number) && !empty($userLastInvoice->invoice_number) ? $userLastInvoice->invoice_number : '');

             $cerSerial = $starting_form;
            if(!empty($lastInvoiceNo)):
                preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
                $cerSerial = isset($invoiceNumbers[1]) ? ((int) $invoiceNumbers[1]) + 1 : $starting_form;
            endif;
            $invoiceNumber = $prifix . $cerSerial;
            Invoice::where('id', $invoice_id)->update(['invoice_number' => $invoiceNumber]);

            return $invoiceNumber;
        else:
            return false;
        endif;
    }

    private function generateReferenceNo($customerId, $company){
        $customer = Customer::find($customerId);
        if (!$customer) return null;
        
        $nameParts = (isset($company->company_name) && !empty($company->company_name) ? explode(' ', $company->company_name) : []);
        //$nameParts = explode(' ', trim($customer->company_name));
        $prefix = '';
        foreach ($nameParts as $part):
            $prefix .= strtoupper(substr($part, 0, 1));
        endforeach;
        $lastJob = CustomerJob::where('customer_id', $customerId)->orderBy('id', 'desc')->first();

        if ($lastJob && preg_match('/\d+$/', $lastJob->reference_no, $matches)):
            $nextNumber = intval($matches[0]) + 1;
        else:
            $nextNumber = 1;
        endif;
        $referenceNo = $prefix . $nextNumber;

        return $referenceNo;
    }


    public function download($invoice_id, Request $request){
        try {
            $record = Invoice::findOrFail($invoice_id);
            $thePdf = $this->generatePdf($invoice_id);

            return response()->json([
                    'success' => true,
                    'download_url' => $thePdf,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found. The requested record (ID: '.$invoice_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
        
    }

    public function cancellReasons(){
        try{
            $reasons = InvoiceCancelReason::orderBy('id', 'ASC')->get();
            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $reasons
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        }
    }

    public function makePayment(Request $request){
        try{
            $invoice_id = $request->invoice_id;

            $data = [
                'invoice_id' => $invoice_id,
                'payment_date' => (isset($request->payment_date) && !empty($request->payment_date) ? date('Y-m-d', strtotime($request->payment_date)) : date('Y-m-d')),
                'payment_method_id' => $request->payment_method_id > 0 ? $request->payment_method_id : null,
                'amount' => $request->amount,
            ];
            $payment = InvoicePayment::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Invoice payment successfully inserted.',
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        }
    }

    public function updateStatus(Request $request){
        try{
            $invoice_id = $request->invoice_id;
            $pay_status = $request->pay_status;

            $invoice = Invoice::find($invoice_id);
            $invoice->pay_status = $pay_status;
            $invoice->invoice_cancel_reason_id = (isset($request->invoice_cancel_reason_id) && $request->invoice_cancel_reason_id > 0 ? $request->invoice_cancel_reason_id : null);
            $invoice->cancel_reason_note = (isset($request->cancel_reason_note) && !empty($request->cancel_reason_note) ? $request->cancel_reason_note : null);
            $invoice->cancelled_by = ($pay_status == 'Canceled' ? $request->user()->id : null);
            $invoice->cancelled_at = ($pay_status == 'Canceled' ? date('Y-m-d H:i:s') : null);
            $invoice->updated_by = $request->user()->id;
            $invoice->updated_at = date('Y-m-d H:i:s');
            $invoice->save();

            return response()->json([
                'success' => true,
                'message' => 'Invoice status successfully updated.',
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        }
    }

    public function getRawInvoice(Request $request){
        try{
            $invoice_id = $request->invoice_id;
            $invoice = Invoice::find($invoice_id);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'row' => $invoice
            ], 200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => 'Data not found for the selected row.',
            ], 304);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function makeRefund(Request $request){
        try{
            $invoice_id = $request->invoice_id;

            $data = [
                'invoice_id' => $invoice_id,
                'payment_date' => (isset($request->payment_date) && !empty($request->payment_date) ? date('Y-m-d', strtotime($request->payment_date)) : date('Y-m-d')),
                'payment_method_id' => $request->payment_method_id > 0 ? $request->payment_method_id : null,
                'amount' => $request->amount * -1,
            ];
            $payment = InvoicePayment::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Refund successfully inserted',
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        }
    }

    public function getPaymentMethods(){
        try{
            $methods = PaymentMethod::orderBy('id', 'ASC')->get();
            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $methods
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        }
    }
}
