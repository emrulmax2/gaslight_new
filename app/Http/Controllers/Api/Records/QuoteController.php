<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendQuoteEmailRequest;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerContactInformation;
use App\Models\CustomerProperty;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use App\Models\Quote;
use App\Models\QuoteOption;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
    public function list(Request $request){
        $user_id = $request->user()->id;
        $status = ($request->has('status') && !empty($request->query('status')) ? explode(',', $request->query('status')) : []);
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? strtolower($request->query('order')) : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

    
        $query = Quote::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'property', 'form', 'user', 'user.company', 'property', 'billing']);
        if(!empty($status) && count($status) > 0): $query->whereIn('status', $status); endif;
        if (!empty($queryStr)):
            $query->where('quote_number', 'LIKE', '%' . $queryStr . '%')->orWhereHas('customer', function ($q) use ($queryStr) {
                $q->where('full_name', 'LIKE', '%' . $queryStr . '%');
            })->orWhereHas('property', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            });
        endif;
        $query->where('created_by', $user_id);

        $validSortFields = ['id', 'created_at', 'updated_at', 'issued_date', 'expire_date'];
        $sortField = in_array($sortField, $validSortFields) ? $sortField : 'id';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);

        $limit = max(1, (int)$request->query('limit', 10));
        $page = max(1, (int)$request->query('page', 1));
        $records = $query->paginate($limit, ['*'], 'page', $page);

        $limit = max(1, (int)$request->query('limit', 10));
        $page = (int)$request->query('page', 1);

        if ($page === -1) {
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

        $quote_id = (isset($request->quote_id) && $request->quote_id > 0 ? $request->quote_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : null);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : null);
        $property = CustomerProperty::find($customer_property_id);

        $issued_date = (isset($request->issued_date) && !empty($request->issued_date) ? date('Y-m-d', strtotime($request->issued_date)) : date('Y-m-d'));
        $expire_date = (isset($company->quote_expired_in) && $company->quote_expired_in > 0 ? date('Y-m-d', strtotime('+'.$company->quote_expired_in.' days', strtotime($issued_date))) : date('Y-m-d', strtotime("+7 days")));

        /* Store or Update Record */
        $quote = Quote::updateOrCreate(['id' => $quote_id], [
            'company_id' => auth()->user()->companies->pluck('id')->first(),
            'customer_id' => $customer_id,
            'billing_address_id' => $request->billing_address_id,
            'job_form_id' => $job_form_id,
            'customer_property_id' => $customer_property_id,
            
            'issued_date' => $issued_date,
            'expire_date' => $expire_date,
            
            'updated_by' => $user_id,
        ]);

        if($quote->id):
            $quote_number = $this->generateQuoteNumber($quote->id);
            $options = $request->options;
            QuoteOption::where('quote_id', $quote->id)->forceDelete();
            if(!empty($options)):
                foreach($options as $key => $value):
                    QuoteOption::create([
                        'quote_id' => $quote->id,
                        'name' => $key,
                        'value' => $value
                    ]);
                endforeach;
            endif;

            $existRow = QuoteOption::where('quote_id', $quote->id)->where('name', 'quoteExtra')->get()->first();
            $theData = (isset($existRow->id) && !empty($existRow->id) ? $existRow->value : []);
            $quoteExtra = [
                'non_vat_quote' => (isset($request->non_vat_quote) && $request->non_vat_quote == 1 ? 1 : 0),
                'vat_number' => (isset($request->vat_number) && !empty($request->vat_number) ? $request->vat_number : ""),
            ];
            if(!isset($theData->payment_term) || empty($theData->payment_term)):
                $quoteExtra['payment_term'] = (isset($company->bank->payment_term) && !empty($company->bank->payment_term) ? $company->bank->payment_term : "");
            else:
                $quoteExtra['payment_term'] = $theData->payment_term;
            endif;
            QuoteOption::where('quote_id', $quote->id)->where('name', 'quoteExtra')->forceDelete();
            QuoteOption::create([
                'quote_id' => $quote->id,
                'name' => 'quoteExtra',
                'value' => $quoteExtra
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quote successfully created.',
                'data' =>  Quote::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'property', 'form', 'user', 'user.company', 'options'])->findOrFail($quote->id),
            ], 200);
        else:
            return response()->json([
                'success' => false,
                'message' => 'Jobs not found. Please select a job.'
            ], 400);
        endif;
        /* Store or Update Record */
    }


    public function edit($quote_id, Request $request){
        try {
            $quote = Quote::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'property', 'form', 'user', 'user.company', 'property', 'billing'])
                        ->find($quote_id);
            $data = [
                'quote_id' => $quote->id,
                'quote_number' => $quote->quote_number,
                'quote' => [
                    'status' => $quote->status,
                    'pay_status' => $quote->pay_status,
                    'expire_date' => $quote->expire_date,
                ],
                'issued_date' => (isset($quote->issued_date) && !empty($quote->issued_date) ? date('d-m-Y', strtotime($quote->issued_date)) : ''),
                'customer' => $quote->customer,
                'email_template_data' => $quote->email_template ?? []
            ];
            if($quote->customer_property_id > 0 && isset($quote->property->id)):
                $data['job_address'] = $quote->property;
            endif;
            if($quote->customer_job_id > 0 && isset($quote->job->id)):
                $data['job'] = $quote->job;
            endif;
        
            $billingAddress = null;
            if(isset($quote->billing->id) && $quote->billing->id > 0):
                $billingAddress = $quote->billing;
            elseif(isset($quote->job->billing->id) && $quote->job->billing->id > 0):
                $billingAddress = $quote->job->billing;
            else:
                $billingAddress = $quote->customer->address;
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

            $data['quoteItemsCount'] = 0;
            $data['quoteItems'] = [];
            //$data['quoteNotes'] = (isset($quote->available_options->quoteNotes) && !empty($quote->available_options->quoteNotes) ? $quote->available_options->quoteNotes : '');
            
            if(isset($quote->available_options->quoteItems) && !empty($quote->available_options->quoteItems)):
                if(isset($quote->available_options->quoteItems) && !empty($quote->available_options->quoteItems)):
                    $q = 1;
                    foreach($quote->available_options->quoteItems as $item):
                        $data['quoteItems'][$q] = (array) $item;

                        $data['quoteItemsCount'] += 1;
                        $q++;
                    endforeach;
                endif;
            endif;
            if(isset($quote->available_options->quoteDiscounts) && !empty($quote->available_options->quoteDiscounts)):
                $quoteDiscounts = (array) $quote->available_options->quoteDiscounts;
                $data['quoteDiscounts'] = $quoteDiscounts;
            endif;
            if(isset($quote->available_options->quoteExtra) && !empty($quote->available_options->quoteExtra)):
                $quoteExtra = (array) $quote->available_options->quoteExtra;
                $data['quoteExtra'] = $quoteExtra;
            endif;

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        }catch(ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. The requested (ID: '.$quote_id.') does not exist or may have been deleted.',
            ], 404);
            
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function send(SendQuoteEmailRequest $request){
        $quote_id = $request->quote_id;
        $ccMail = (!empty($request->cc_email_address) ? explode(',', $request->cc_email_address) : []);
        $subject = $request->subject;
        $content = $request->content;
        $customerEmail = $request->customer_email;

        try {
            $quote = Quote::findOrFail($quote_id);
            $updateResult = $quote->update([
                'status' => 'Send'
            ]);

            if (!$updateResult) {
                throw new \Exception("Failed to update record status");
            }

            $contact = CustomerContactInformation::where('customer_id', $quote->customer_id)->first();
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
                $emailSent = $this->sendEmail($quote_id, $customerEmail, $ccMail, $subject, $content);
            } catch (\Exception $e) {
                $emailError = $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'message' => $emailSent 
                    ? 'Quote status has been updated and emailed to the customer'
                    : 'Quote status has been updated but email failed: ' . 
                    ($emailError ?: 'Invalid or empty email address'),
                'quote_id' => $quote_id
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. The requested record (ID: '.$quote_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function sendEmail($quote_id, $customerEmail, $ccMail = [], $subject, $content){
        $quote = Quote::with([
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
            'user.company'])->find($quote_id);
        $companyName = $quote->user->companies->pluck('company_name')->first();
        $customerName = (isset($quote->customer->full_name) && !empty($quote->customer->full_name) ? $quote->customer->full_name : '');
        $customerEmail = (isset($quote->customer->contact->email) && !empty($quote->customer->contact->email) ? $quote->customer->contact->email : '');
        if(!empty($customerEmail)):
            $templateTitle = $subject;
            $ccMail[] = $quote->user->email;

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
            $configuration['from_name'] = !empty($companyName) ? $companyName : $quote->user->name; 
            $configuration['from_email'] = !empty($companyEmail) ? $companyEmail : $quote->user->email; 

            $attachmentFiles = [];
            $fileName = $this->generatePdfFileName($quote->id); 
            if (Storage::disk('public')->exists('quotes/'.$quote->created_by.'/'.$fileName)):
                $attachmentFiles[0] = [
                    "pathinfo" => 'quotes/'.$quote->created_by.'/'.$fileName,
                    "nameinfo" => $fileName,
                    "mimeinfo" => 'application/pdf',
                    "disk" => 'public'
                ];
            endif;
            if(isset($quote->email_template->attachmentFiles) && !empty($quote->email_template->attachmentFiles)):
                $attachmentFiles = array_merge($attachmentFiles, $quote->email_template->attachmentFiles);
            endif;

            GCEMailerJob::dispatch($configuration, $sendTo, new GCESendMail($subject, $content, $attachmentFiles, $templateTitle), $ccMail);
            return true;
        else:
            return false;
        endif;
    }

    public function renderEmailTemplate(Quote $quote, $subject, $content): array {
        // Build shortcode replacements
        $companyName = $quote->user->companies->pluck('company_name')->first();
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
            'attachmentFiles' => $attachmentFiles
        ];
    }

    public function generatePdf($quote_id){
        $quote = Quote::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'property', 'form', 'user', 'user.company', 'options', 'billing'])->find($quote_id);
       
        //dd($record->available_options->quoteItems);
        $companyLogoPath = (isset($quote->user->company->logo_path) && $quote->user->company->logo_path ? $quote->user->company->logo_path : '');
        $companyLogoBase64 = (!empty($companyLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($companyLogoPath)) : '');

        $logoPath = resource_path('images/gas_safe_register.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $report_title = 'Quote of '.$quote->quote_number;

        $userSignBase64 = (isset($record->user->signature) && Storage::disk('public')->exists($record->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($record->user->signature->filename)) : '');
        
        $VIEW = 'app.quote.pdf';
        $fileName = $this->generatePdfFileName($quote->id); 
        if (Storage::disk('public')->exists('quotes/'.$quote->created_by.'/'.$fileName)) {
            Storage::disk('public')->delete('quotes/'.$quote->created_by.'/'.$fileName);
        }
        $pdf = Pdf::loadView($VIEW, compact('quote', 'logoBase64', 'companyLogoBase64', 'report_title', 'userSignBase64'))
            ->setOption(['isRemoteEnabled' => true])
            ->setPaper('a4', 'portrait') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('quotes/'.$quote->created_by.'/'.$fileName, $content );

        return Storage::disk('public')->url('quotes/'.$quote->created_by.'/'.$fileName);
    }

    function generatePdfFileName($quote_id){
        $quote = Quote::with('customer')->find($quote_id);
        $full_name = $quote->customer->full_name;
        $full_address = (isset($quote->customer->full_address) && !empty($quote->customer->full_address) ? $quote->customer->full_address : '');
        
        // Concatenate the fields
        $fileName = "{$full_name}_{$full_address}";
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

    public function generateQuoteNumber($quote_id){
        $quote = Quote::find($quote_id);
        $user_id = $quote->created_by;
        if(empty($quote->quote_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $quote->job_form_id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastQuote = Quote::where('created_by', $user_id)->where('id', '!=', $quote_id)->orderBy('id', 'DESC')->get()->first();
            $lastQuoteNo = (isset($userLastQuote->quote_number) && !empty($userLastQuote->quote_number) ? $userLastQuote->quote_number : '');

             $cerSerial = $starting_form;
            if(!empty($lastQuoteNo)):
                preg_match("/(\d+)/", $lastQuoteNo, $quoteNumbers);
                $cerSerial = isset($quoteNumbers[1]) ? ((int) $quoteNumbers[1]) + 1 : $starting_form;
            endif;
            $quoteNumber = $prifix . $cerSerial;
            Quote::where('id', $quote_id)->update(['quote_number' => $quoteNumber]);

            return $quoteNumber;
        else:
            return false;
        endif;
    }


    public function download($quote_id, Request $request){
        try {
            $record = Quote::findOrFail($quote_id);
            $thePdf = $this->generatePdf($quote_id);

            return response()->json([
                    'success' => true,
                    'download_url' => $thePdf,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. The requested record (ID: '.$quote_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
        
    }
}
