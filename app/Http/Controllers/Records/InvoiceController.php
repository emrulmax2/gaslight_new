<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAddressStoreRequest;
use App\Http\Requests\MakePaymentRequest;
use App\Http\Requests\MakeRefundRequest;
use App\Http\Requests\OccupantDetailsStoreRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\SendInvitationSmsRequest;
use App\Http\Requests\SendInvoiceEmailRequest;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\CustomerPropertyOccupant;
use App\Models\Invoice;
use App\Models\InvoiceCancelReason;
use App\Models\InvoiceOption;
use App\Models\InvoicePayment;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use App\Models\PaymentMethod;
use App\Models\Record;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Number;

class InvoiceController extends Controller
{
    public function index(){
       $engineers = User::whereHas('companies', function($query) {
                                $query->where('companies.user_id', Auth::id());
                            })->select('id', 'name')->get();

        $certificate_types = JobForm::where('parent_id', '!=',  0)->where('active', 1)->orderBy('id', 'ASC')->get();
        return view('app.invoice.index', [
            'title' => 'Invoices - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Invoice', 'href' => 'javascript:void(0);'],
            ],
            'engineers' => $engineers,
            'certificate_types' => $certificate_types,
            'payment_methods' => PaymentMethod::where('active', 1)->get(),
            'reasons' => InvoiceCancelReason::where('active', 1)->get()
        ]);
    }

    public function list(Request $request){
        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && !empty($request->status) ? explode(',', $request->status) : ['Unpaid']);

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;
    
        $query = Invoice::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'property', 'billing'])->orderByRaw(implode(',', $sorts));
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
        if(!empty($status) && count($status) > 0): $query->whereIn('pay_status', $status); endif;
        $query->where('created_by', $request->user()->id);
        $Query = $query->get();

        $html = '';

        if(!empty($Query) && $Query->count() > 0):
            foreach($Query as $list):
                $url = route('invoices.show', $list->id);

                $html .= '<tr data-url="'.$url.'" class="invoiceRow cursor-pointer intro-x box border max-sm:px-3 max-sm:pt-2 max-sm:pb-2 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                    
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Serial No</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.$list->invoice_number.'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Inspection Name</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.($list->job->property->occupant_name ?? ($list->customer->full_name ?? '')).'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start flex-wrap">';
                            $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Inspection Address</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->job->property->full_address ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start sm:block">';
                            $html .= '<label class="sm:hidden font-medium m-0">Landlord Name</label>';
                            $html .= '<div>';
                                $html .= '<div class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.($list->customer->full_name ?? '').'</div>';
                                $html .= '<div class="text-slate-500 whitespace-normal text-xs leading-[1.3]  max-sm:ml-auto">'.($list->customer->full_address ?? '').'</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start flex-wrap">';
                            $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Landlord Address</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->billing->full_address ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Assigned To</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.(isset($list->user->name) ? $list->user->name : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Amount</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal text-xs font-medium leading-[1.3] max-sm:ml-auto">';
                                $html .= '<span class="text-primary">'.($list->invoice_total > 0 ? Number::currency($list->invoice_total, 'GBP') : Number::currency(0, 'GBP')).'</span>';
                                $html .= '<span class="text-danger">'.($list->invoice_due > 0 ? ' - '.Number::currency($list->invoice_due, 'GBP') : '').'</span>';
                            $html .= '</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Created At</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal text-xs leading-[1.3] max-sm:ml-auto">';
                                if($list->status == 'Send'){
                                    $html .= '<button class="ml-auto font-medium bg-success mb-1 rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Send</button><br/>';
                                }else {
                                    $html .= '<button class="ml-auto font-medium bg-primary mb-1 rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Draft</button><br/>';
                                }
                                $html .= ($list->created_at ? $list->created_at->format('Y-m-d h:i A') : '');
                            $html .= '</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tr-none sm:rounded-tr rounded-br-none sm:rounded-br">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Status</label>';
                            if($list->pay_status == 'Canceled' || $list->pay_status == 'Refunded'){
                                $html .= '<button class="ml-auto font-medium bg-danger rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$list->pay_status.'</button>';
                            }else if($list->pay_status == 'Paid'){
                                $html .= '<button class="ml-auto font-medium bg-success rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$list->pay_status.'</button>';
                            }else{
                                $html .= '<button class="ml-auto font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$list->pay_status.'</button>';
                            }
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="text-right border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div data-tw-merge data-tw-placement="bottom-start" class="dropdown relative inline-block">';
                            $html .= '<button data-tw-merge data-tw-toggle="dropdown" aria-expanded="false" class="inline-flex items-center justify-center w-[25px] h-[25px] bg-primary text-white cursor-pointer">';
                                $html .= '<i data-tw-merge data-lucide="settings" class="stroke-1.5 h-4 w-4"></i>';
                            $html .= '</button>';
                            $html .= '<div data-transition data-selector=".show" data-enter="transition-all ease-linear duration-150" data-enter-from="absolute !mt-5 invisible opacity-0 translate-y-1" data-enter-to="!mt-1 visible opacity-100 translate-y-0" data-leave="transition-all ease-linear duration-150" data-leave-from="!mt-1 visible opacity-100 translate-y-0" data-leave-to="absolute !mt-5 invisible opacity-0 translate-y-1" class="dropdown-menu absolute z-[9999] hidden w-44">';
                                $html .= '<div data-tw-merge class="dropdown-content rounded-md border-transparent bg-white shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600">';
                                    $html .= '<div class="p-2">';
                                        if($list->pay_status == 'Unpaid'):
                                            $html .= '<a data-hasdue="'.($list->invoice_due > 0 ? 1 : 0).'" data-status="Paid" data-id="'.$list->id.'" class="paidInvoice cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item">';
                                                $html .= '<i data-tw-merge data-lucide="check-circle" class="stroke-1.5 mr-2 h-4 w-4"></i>';
                                                $html .= 'Mar as Paid';
                                            $html .= '</a>';
                                        endif;
                                        if($list->pay_status != 'Paid' && $list->pay_status != 'Canceled'):
                                        $html .= '<a data-id="'.$list->id.'" class="cancelInvoice cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item">';
                                            $html .= '<i data-tw-merge data-lucide="check-circle" class="stroke-1.5 mr-2 h-4 w-4"></i>';
                                            $html .= 'Cancel';
                                        $html .= '</a>';
                                        endif;
                                        if($list->pay_status == 'Canceled' || ($list->pay_status == 'Pad' && $list->invoice_due > 0)):
                                        $html .= '<a data-status="Unpaid" data-id="'.$list->id.'" class="unpaidInvoice cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item">';
                                            $html .= '<i data-tw-merge data-lucide="check-circle" class="stroke-1.5 mr-2 h-4 w-4"></i>';
                                            $html .= 'Move to Unpaid';
                                        $html .= '</a>';
                                        endif;
                                        if($list->pay_status != 'Canceled'):
                                        $html .= '<a data-id="'.$list->id.'" class="refundInvoice cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item">';
                                            $html .= '<i data-tw-merge data-lucide="check-circle" class="stroke-1.5 mr-2 h-4 w-4"></i>';
                                            $html .= 'Refunded';
                                        $html .= '</a>';
                                        endif;
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</td>';
                $html .= '</tr>';
            endforeach;
        else:
            $html .= '<tr data-url="" class="intro-x box bg-pending bg-opacity-10 border border-pending border-opacity-5 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                $html .= '<td colspan="10" class="border-b dark:border-darkmode-300 border-none px-3 py-3 rounded">';
                    $html .= '<div class="flex justify-center items-center text-pending">';
                        $html .= 'No matching records found!';
                    $html .= '</div>';
                $html .= '</td>';
            $html .= '</tr>';
        endif;

        return response()->json(['html' => $html], 200); 
    }

    public function create(JobForm $form){
        $data = [
            'title' => 'Create New Invoice - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Create Invoice', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => JobForm::find(4),
        ];
        $user = User::find(auth()->user()->id);
        $data['non_vat_invoice'] = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? 0 : 1);
        $data['vat_number'] = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? $user->companies[0]->vat_number : '');
        $data['methods'] = PaymentMethod::where('active', 1)->orderBy('name', 'asc')->get();

        return view('app.invoice.create', $data);
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
                'billing_address_id' => $request->customer_address_id ?? null,
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
                'billing_address_id' => $request->customer_address_id ?? null,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,
                'customer_property_id' => $customer_property_id,
                
                'issued_date' => (isset($request->issued_date) && !empty($request->issued_date) ? date('Y-m-d', strtotime($request->issued_date)) : date('Y-m-d')),
                'expire_date' => date('Y-m-d', strtotime("+30 days")),
                
                'updated_by' => $user_id,
            ]);
            
            if($invoice->id):
                $invoice_number = $this->generateInvoiceNumber($invoice->id);
                $options = json_decode($request->options);
                InvoiceOption::where('invoice_id', $invoice->id)->forceDelete();
                if(!empty($options)):
                    foreach($options as $key => $value):
                        InvoiceOption::create([
                            'invoice_id' => $invoice->id,
                            'name' => $key,
                            'value' => json_decode($value)
                        ]);
                    endforeach;
                endif;

                
                $existRow = InvoiceOption::where('invoice_id', $invoice->id)->where('name', 'invoiceExtra')->get()->first();
                $theData = (isset($existRow->id) && !empty($existRow->id) ? $existRow->value : []);
                $invoiceExtra = [
                    'non_vat_invoice' => (isset($request->non_vat_invoice) && $request->non_vat_invoice == 1 ? 1 : 0),
                    'vat_number' => (isset($request->vat_number) && !empty($request->vat_number) ? $request->vat_number : ""),
                ];
                if(!isset($theData->payment_term) || empty($theData->payment_term)):
                    $invoiceExtra['payment_term'] = (isset($company->bank->payment_term) && !empty($company->bank->payment_term) ? $company->bank->payment_term : "");
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
                CustomerJob::where('id', $customer_job_id)->update(['estimated_amount' => $request->invoiceTotal]);
                if(isset($request->from_job) && $request->from_job == 1):
                    CustomerJob::where('id', $customer_job_id)->update([
                        'customer_job_status_id' => 2,
                        'cancel_reason_id' => null,
                        'cancel_reason_note' => null,
                        'updated_by' => $request->user()->id
                    ]);
                endif;

                return response()->json(['msg' => 'Invoice successfully created.', 'red' => route('invoices.show', $invoice->id)], 200);
            else:
                return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
            endif;
        else:
            return response()->json(['msg' => 'Jobs not found. Please select a job.'], 304);
        endif;
        /* Store or Update Record */
    }

    public function createJobName($options){
        $options = json_decode($options);
        $invoiceItems = json_decode($options->invoiceItems);
        $jobName = [];
        if(!empty($invoiceItems)):
            foreach($invoiceItems as $items):
                $jobName[] = $items->description;
            endforeach;
        endif;

        return (!empty($jobName) ? implode(' ', $jobName) : 'New Job');
    }

    public function show(Invoice $invoice){
        $user_id = auth()->user()->id;
        $invoice->load(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'property']);
        $form = JobForm::find($invoice->job_form_id);

        $thePdf = $this->generatePdf($invoice->id);
        return view('app.invoice.show', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Invoice', 'href' => 'javascript:void(0);'],
                ['label' => 'Show', 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'invoice' => $invoice,
            'thePdf' => $thePdf,
            'payment_methods' => PaymentMethod::where('active', 1)->get(),
        ]);
    }

    public function editReady(Request $request){
        $invoice_id = $request->invoice_id;

        $invoice = Invoice::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'property', 'billing'])
                    ->find($invoice_id);
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
            'job_address' => $invoice->job->property
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
        $data['invoiceItems'] = [];
        //$data['invoiceNotes'] = (isset($invoice->available_options->invoiceNotes) && !empty($invoice->available_options->invoiceNotes) ? $invoice->available_options->invoiceNotes : '');
        
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
        //$data = array_merge($data, $optionData);
        //dd($optionData);

        return response()->json(['row' => $data, 'form' => $invoice->job_form_id, 'red' => route('invoices.create')], 200);
    }

    public function sendEmail(SendInvoiceEmailRequest $request){
        $invoice_id = $request->invoice_id;
        $ccMail = (!empty($request->cc_email_address) ? explode(',', $request->cc_email_address) : []);
        $subject = $request->subject;
        $content = $request->content;
        $customerEmail = $request->customer_email;

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
        CustomerContactInformation::where('customer_id', $invoice->customer_id)->update(['email' => $customerEmail]);

        $companyName = $invoice->user->companies->pluck('company_name')->first();
        $companyEmail = $invoice->user->companies->pluck('company_email')->first();
        $customerName = (isset($invoice->customer->full_name) && !empty($invoice->customer->full_name) ? $invoice->customer->full_name : '');
        if(!empty($customerEmail)):
            $data = [];
            $data['status'] = 'Send';
            Invoice::where('id', $invoice_id)->update($data);

            $templateTitle = $subject;
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
            $pdf = ''; 
            if (Storage::disk('public')->exists('invoices/'.$invoice->created_by.'/'.$fileName)):
                $pdf = Storage::disk('public')->url('invoices/'.$invoice->created_by.'/'.$fileName);
                $attachmentFiles[0] = [
                    "pathinfo" => 'invoices/'.$invoice->created_by.'/'.$fileName,
                    "nameinfo" => $fileName,
                    "mimeinfo" => 'application/pdf',
                    "disk" => 'public'
                ];
            endif;
            if(isset($invoice->email_template->attachmentFiles) && !empty($invoice->email_template->attachmentFiles)):
                $attachmentFiles = array_merge($attachmentFiles, $invoice->email_template->attachmentFiles);
            endif;

            GCEMailerJob::dispatch($configuration, $sendTo, new GCESendMail($subject, $content, $attachmentFiles, $templateTitle), $ccMail);
            return response()->json(['msg' => 'Invoice successfully send to the customer.', 'red' => route('invoices.show', $invoice_id), 'pdf' => $pdf]);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later.'], 304);
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
        $companyLogoPath = (isset($invoice->user->company->logo_path) && $invoice->user->company->logo_path ? $invoice->user->company->logo_path : '');
        $companyLogoBase64 = (!empty($companyLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($companyLogoPath)) : '');

        $logoPath = resource_path('images/gas_safe_register.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $report_title = 'Invoice of '.$invoice->invoice_number;

        $userSignBase64 = (isset($record->user->signature) && Storage::disk('public')->exists($record->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($record->user->signature->filename)) : '');
        
        $VIEW = 'app.invoice.pdf';
        $fileName = $this->generatePdfFileName($invoice->id); 
        if (Storage::disk('public')->exists('invoices/'.$invoice->created_by.'/'.$fileName)) {
            Storage::disk('public')->delete('invoices/'.$invoice->created_by.'/'.$fileName);
        }
        $pdf = Pdf::loadView($VIEW, compact('invoice', 'logoBase64', 'companyLogoBase64', 'report_title', 'userSignBase64'))
            ->setOption(['isRemoteEnabled' => true])
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


    public function getJobs(Request $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;

        $html = '';
        $query = CustomerJob::with('customer', 'customer.address', 'property', 'priority', 'thestatus')->where('created_by', $user_id)->orderBy('id', 'DESC')->get();
        if($query->count() > 0):
            $html .= '<div class="results existingAddress">';
                $i = 1;
                foreach($query as $job):
                    $html .= '<div data-id="'.$job->id.'" data-description="'.(!empty($job->description) ? $job->description : '').(isset($job->customer->full_name) && !empty($job->customer->full_name) ? $job->customer->full_name : '').(isset($job->customer->address->postal_code) && !empty($job->customer->address->postal_code) ? ' ('.$job->customer->address->postal_code.')' : '').'" class="customerJobItem flex items-center cursor-pointer '.($i != $query->count() ? ' mb-2' : '').' bg-white px-3 py-3">';
                        $html .= '<div>';
                            $html .= '<div class="group relative flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="briefcase" class="theIcon lucide lucide-briefcase stroke-1.5 h-4 w-4 text-primary"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path><rect width="20" height="14" x="2" y="6" rx="2"></rect></svg>';
                                $html .= '<span style="display: none;" class="h-4 w-4 theLoader absolute left-0 top-0 bottom-0 right-0 m-auto"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#0d9488"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span>';
                            $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                            $html .= '<div>';
                                $html .= '<div class="whitespace-normal font-medium">';
                                    $html .= $job->description;
                                $html .= '</div>';
                                $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                    $html .= (isset($job->customer->full_name) && !empty($job->customer->full_name) ? $job->customer->full_name : '');
                                    $html .= (isset($job->customer->address->postal_code) && !empty($job->customer->address->postal_code) ? ' ('.$job->customer->address->postal_code.')' : '');
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';

                    $i++;
                endforeach;
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-warning border-warning bg-opacity-20 border-opacity-5 text-warning dark:border-warning dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-circle" class="lucide lucide-alert-circle stroke-1.5 mr-2 h-6 w-6"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg><span><strong>Oops!</strong> No jobs found.</span></div>';
            return response()->json(['suc' => 2, 'html' => $html], 200);
        endif;
    }

    public function linkedJob(Request $request){
        $job_id = $request->job_id;
        $job = CustomerJob::with('customer', 'customer.address', 'property', 'billing')->find($job_id);

        return response()->json(['row' => $job], 200);
    }

    public function getCustomers(Request $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;

        $html = '';
        $query = Customer::with('address', 'contact')->where('created_by', $user_id)->get();
        $groupedCustomer = $query->groupBy(function ($item) {
            $full_names = explode(' ', $item->full_name);
            return strtoupper(substr($full_names[0], 0, 1));
        })->sortKeys();
        
        if($query->count() > 0):
            foreach($groupedCustomer as $letter => $customers):
                $html .= '<div class="box mb-0 shadow-none rounded-none border-none customersContainer">';
                    $html .= '<div class="flex flex-col items-center bg-slate-100 px-3 py-3 dark:border-darkmode-400 sm:flex-row">';
                        $html .= '<h2 class="mr-auto font-medium uppercase text-dark">';
                            $html .= $letter;
                        $html .= '</h2>';
                    $html .= '</div>';
                    $html .= '<div class="results existingAddress">';
                        $i = 1;
                        foreach($customers as $customer):
                            $allWords = explode(' ', $customer->full_name);
                            $label = (isset($allWords[0]) && !empty($allWords[0]) ? mb_substr($allWords[0], 0, 1) : '').(count($allWords) > 1 ? mb_substr(end($allWords), 0, 1) : '');
                            
                            $html .= '<div data-id="'.$customer->id.'" data-description="'.$customer->full_name.' '.$customer->postal_code.'" class="customerItem flex items-center cursor-pointer '.($i != $customers->count() ? ' border-b border-slate-100 ' : '').' bg-white px-3 py-3">';
                                $html .= '<div>';
                                    $html .= '<div class="group relative flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                        $html .= '<span class="text-primary text-xs uppercase font-medium">'.$label.'</span>';
                                        //$html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="users" class="lucide lucide-users stroke-1.5 h-4 w-4 text-primary"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
                                        $html .= '<span style="display: none;" class="h-4 w-4 theLoader absolute left-0 top-0 bottom-0 right-0 m-auto"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#0d9488"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span>';
                                    $html .= '</div>';
                                $html .= '</div>';
                                $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                                    $html .= '<div>';
                                        $html .= '<div class="whitespace-normal font-medium">';
                                            $html .= $customer->full_name;
                                        $html .= '</div>';
                                        $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                            $html .= (isset($customer->full_address) && !empty($customer->full_address) ? $customer->full_address : 'N/A');
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
    
                            $i++;
                        endforeach;
                    $html .= '</div>';
                $html .= '</div>';
            endforeach;

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-warning border-warning bg-opacity-20 border-opacity-5 text-warning dark:border-warning dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-circle" class="lucide lucide-alert-circle stroke-1.5 mr-2 h-6 w-6"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg><span><strong>Oops!</strong> No jobs found.</span></div>';
            return response()->json(['suc' => 2, 'html' => $html], 200);
        endif;
    }

    public function getLInkedCustomer(Request $request){
        $customer = Customer::with('address')->find($request->customer_id);

        return response()->json(['customer' => $customer], 200);
    }

    public function getJobAddressrs(Request $request){
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $html = '';
        $query = CustomerProperty::with('customer')->where('customer_id', $customer_id)->orderBy('address_line_1', 'ASC')->get();
        if($query->count() > 0):
            $html .= '<div class="results existingAddress">';
                $i = 1;
                foreach($query as $property):
                    $address = [
                        'id' => $property->id ?? '0',
                        'address_line_1' => $property->address_line_1 ?? '',
                        'address_line_2' => $property->address_line_2 ?? '',
                        'postal_code' => $property->postal_code ?? '',
                        'state' => $property->state ?? '',
                        'city' => $property->city ?? '',
                        'country' => $property->country ?? '',
                        'latitude' => $property->latitude ?? '',
                        'longitude' => $property->longitude ?? '',
                    ];
                    $html .= '<div data-address-obj=\''.e(json_encode($address)).'\' data-id="'.$property->id.'" data-occupant="'.(!empty($property->occupant_name) ? $property->occupant_name : $property->customer->full_name).'" data-address="'.$property->full_address.'" class="customerJobAddressItem flex items-center cursor-pointer '.($i != $query->count() ? ' mb-2' : '').' bg-white px-3 py-3">';
                        $html .= '<div>';
                            $html .= '<div class="group flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin h-4 w-4 stroke-[1.3] text-primary"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                            $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                            $html .= '<div>';
                                $html .= '<div class="whitespace-nowrap font-medium">';
                                    $html .= $property->address_line_1.' '.$property->address_line_2;
                                $html .= '</div>';
                                $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                    $html .= $property->city.', '.$property->postal_code.', '.$property->country;
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';

                    $i++;
                endforeach;
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-warning border-warning bg-opacity-20 border-opacity-5 text-warning dark:border-warning dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-circle" class="lucide lucide-alert-circle stroke-1.5 mr-2 h-6 w-6"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg><span><strong>Oops!</strong> The customer does not have any job addresses.</span></div>';
            return response()->json(['suc' => 2, 'html' => $html], 200);
        endif;
    }

    public function storeJobAddress(JobAddressStoreRequest $request){
        $customer_id = $request->customer_id;
        $customer = Customer::find($customer_id);

        $data = [
            'customer_id' => $request->customer_id,
            'address_line_1' => (!empty($request->address_line_1) ? $request->address_line_1 : null),
            'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
            'postal_code' => $request->postal_code,
            'state' => (!empty($request->state) ? $request->state : null),
            'city' => $request->city,
            'country' => (!empty($request->country) ? $request->country : null),
            'latitude' => (!empty($request->latitude) ? $request->latitude : null),
            'longitude' => (!empty($request->longitude) ? $request->longitude : null),
            'created_by' => $request->user()->id,
        ];
        $address = CustomerProperty::create($data);

        if($address->id):
            return response()->json(['msg' => 'Customer Job Addresses successfully created.', 'red' => '', 'address' => $address, 'id' => $address->id], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function makePayment(MakePaymentRequest $request){
        try{
            $invoice_id = $request->invoice_id;

            $data = [
                'invoice_id' => $invoice_id,
                'payment_date' => (isset($request->payment_date) && !empty($request->payment_date) ? date('Y-m-d', strtotime($request->payment_date)) : date('Y-m-d')),
                'payment_method_id' => $request->payment_method_id > 0 ? $request->payment_method_id : null,
                'amount' => $request->amount,
            ];
            $payment = InvoicePayment::create($data);
            $invoice = Invoice::find($invoice_id);
            $message = 'Invoice payment successfully inserted.';
            $title = 'Congratulations!';
            if($invoice->invoice_due == 0 || (isset($request->paid) && $request->paid == 1)):
                $message = 'Invoice payment successfully inserted and status updated to PAID.';
                $title = (isset($request->paid) && $request->paid == 1 ? 'Paid' : 'Fully Paid!');

                $invoice->update([
                    'pay_status' => 'Paid',
                    'invoice_cancel_reason_id' => null,
                    'cancel_reason_note' => null,
                    'cancelled_by' => null,
                    'cancelled_at' => null,
                ]);
            endif;
            return response()->json([
                'success' => true,
                'title' => $title,
                'message' => $message,
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        }
    }

    public function cancelInvoice(Request $request){
        try{
            $invoice_id = $request->invoice_id;

            $invoice = Invoice::find($invoice_id);
            $invoice->pay_status = 'Canceled';
            $invoice->invoice_cancel_reason_id = (!empty($request->invoice_cancel_reason_id) ? $request->invoice_cancel_reason_id : null);
            $invoice->cancel_reason_note = (!empty($request->cancel_reason_note) ? $request->cancel_reason_note : null);
            $invoice->cancelled_by = auth()->user()->id;
            $invoice->cancelled_at = date('Y-m-d H:i:s');
            $invoice->save();

            return response()->json([
                'success' => true,
                'message' => 'Invoice successfully cancelled.',
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
            $invoice->updated_by = auth()->user()->id;
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

    public function makeRefund(MakeRefundRequest $request){
        try{
            $invoice_id = $request->invoice_id;

            $data = [
                'invoice_id' => $invoice_id,
                'payment_date' => (isset($request->payment_date) && !empty($request->payment_date) ? date('Y-m-d', strtotime($request->payment_date)) : date('Y-m-d')),
                'payment_method_id' => $request->payment_method_id > 0 ? $request->payment_method_id : null,
                'amount' => $request->amount * -1,
            ];
            $payment = InvoicePayment::create($data);
            $invoice = Invoice::find($invoice_id);
            $invoice->update([
                'pay_status' => 'Refunded',
                'invoice_cancel_reason_id' => null,
                'cancel_reason_note' => null,
                'cancelled_by' => null,
                'cancelled_at' => null,
            ]);

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
}
