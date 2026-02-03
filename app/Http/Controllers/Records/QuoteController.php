<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAddressStoreRequest;
use App\Http\Requests\SendQuoteEmailRequest;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerProperty;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use App\Models\PaymentMethod;
use App\Models\Quote;
use App\Models\QuoteOption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
    public function index(){
       $engineers = User::whereHas('companies', function($query) {
                                $query->where('companies.user_id', Auth::id());
                            })->select('id', 'name')->get();

        $certificate_types = JobForm::where('parent_id', '!=',  0)->where('active', 1)->orderBy('id', 'ASC')->get();
        return view('app.quote.index', [
            'title' => 'Quotes - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Quote', 'href' => 'javascript:void(0);'],
            ],
            'engineers' => $engineers,
            'certificate_types' => $certificate_types
        ]);
    }

    public function list(Request $request){
        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && !empty($request->status) ? explode(',', $request->status) : ['Draft', 'Send']);

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;
    
        $query = Quote::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'property', 'billing'])->orderByRaw(implode(',', $sorts));
        if (!empty($queryStr)):
            $query->where('quote_number', 'LIKE', '%' . $queryStr . '%')->orWhereHas('customer', function ($q) use ($queryStr) {
                $q->where('full_name', 'LIKE', '%' . $queryStr . '%');
            })->orWhereHas('job.property', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            });
        endif;
        if(!empty($status)): $query->whereIn('status', $status); endif;
        $query->where('created_by', $request->user()->id);
        $Query = $query->get();

        $html = '';

        if(!empty($Query) && $Query->count() > 0):
            foreach($Query as $list):
                $url = route('quotes.show', $list->id);

                $html .= '<tr data-url="'.$url.'" class="quoteRow cursor-pointer intro-x box border max-sm:px-3 max-sm:pt-2 max-sm:pb-2 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                    
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Serial No</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.$list->quote_number.'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Landlord Name</label>';
                            $html .= '<div>';
                                $html .= '<div class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.($list->customer->full_name ?? '').'</div>';
                                $html .= '<div class="text-slate-500 whitespace-normal text-xs leading-[1.3]  max-sm:ml-auto">'.($list->customer->full_address ?? '').'</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start flex-wrap">';
                            $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Billing Address</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->billing->full_address ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Assigned To</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.(isset($list->user->name) ? $list->user->name : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Created At</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal text-xs leading-[1.3] max-sm:ml-auto">'.($list->created_at ? $list->created_at->format('Y-m-d h:i A') : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tr-none sm:rounded-tr rounded-br-none sm:rounded-br">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Status</label>';
                            if($list->status == 'Expired'){
                                $html .= '<button class="ml-auto font-medium bg-danger rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$list->status.'</button>';
                            }elseif($list->status == 'Cancelled'){
                                $html .= '<button class="ml-auto font-medium bg-warning rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$list->status.'</button>';
                            }else if($list->status == 'Send'){
                                $html .= '<button class="ml-auto font-medium bg-primary rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$list->status.'</button>';
                            }else if($list->status == 'Accepted'){
                                $html .= '<button class="ml-auto font-medium bg-success rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$list->status.'</button>';
                            }else{
                                $html .= '<button class="ml-auto font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$list->status.'</button>';
                            }
                        $html .= '</div>';
                    $html .= '</td>';
                $html .= '</tr>';
            endforeach;
        else:
            $html .= '<tr data-url="" class="intro-x box bg-pending bg-opacity-10 border border-pending border-opacity-5 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                $html .= '<td colspan="9" class="border-b dark:border-darkmode-300 border-none px-3 py-3 rounded">';
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
            'title' => 'Create New Quote - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Create Quote', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => JobForm::find(3),
        ];
        $user = User::find(auth()->user()->id);
        $data['non_vat_quote'] = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? 0 : 1);
        $data['vat_number'] = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? $user->companies[0]->vat_number : '');
        
        return view('app.quote.create', $data);
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
            'billing_address_id' => $request->customer_address_id,
            'job_form_id' => $job_form_id,
            'customer_property_id' => $customer_property_id,
            
            'issued_date' => $issued_date,
            'expire_date' => $expire_date,
            
            'updated_by' => $user_id,
        ]);

        if($quote->id):
            $quote_number = $this->generateQuoteNumber($quote->id);
            $options = json_decode($request->options);
            QuoteOption::where('quote_id', $quote->id)->forceDelete();
            if(!empty($options)):
                foreach($options as $key => $value):
                    QuoteOption::create([
                        'quote_id' => $quote->id,
                        'name' => $key,
                        'value' => json_decode($value)
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

            return response()->json(['msg' => 'Quote successfully created.', 'red' => route('quotes.show', $quote->id)], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
        /* Store or Update Record */
    }


    public function show(Quote $quote){
        $user_id = auth()->user()->id;
        $quote->load(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'property']);
        $form = JobForm::find($quote->job_form_id);

        $thePdf = $this->generatePdf($quote->id);
        return view('app.quote.show', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Quote', 'href' => 'javascript:void(0);'],
                ['label' => 'Show', 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'quote' => $quote,
            'thePdf' => $thePdf,
        ]);
    }

    public function editReady(Request $request){
        $quote_id = $request->quote_id;

        $quote = Quote::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'property', 'billing'])
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
            'customer' => $quote->customer
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
        $data['quoteNotes'] = (isset($quote->available_options->quoteNotes) && !empty($quote->available_options->quoteNotes) ? $quote->available_options->quoteNotes : '');
        
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
        //$data = array_merge($data, $optionData);
        //dd($optionData);

        return response()->json(['row' => $data, 'form' => $quote->job_form_id, 'red' => route('quotes.create')], 200);
    }


    public function sendEmail(SendQuoteEmailRequest $request){
        $quote_id = $request->quote_id;
        $ccMail = (!empty($request->cc_email_address) ? explode(',', $request->cc_email_address) : []);
        $subject = $request->subject;
        $content = $request->content;
        $customerEmail = $request->customer_email;

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
        CustomerContactInformation::where('customer_id', $quote->customer_id)->update(['email' => $customerEmail]);

        $companyName = $quote->user->companies->pluck('company_name')->first();
        $companyEmail = $quote->user->companies->pluck('company_email')->first();
        $customerName = (isset($quote->customer->full_name) && !empty($quote->customer->full_name) ? $quote->customer->full_name : '');
        if(!empty($customerEmail)):
            $data = [];
            $data['status'] = 'Send';
            Quote::where('id', $quote_id)->update($data);

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
            $pdf = ''; 
            if (Storage::disk('public')->exists('quotes/'.$quote->created_by.'/'.$fileName)):
                $pdf = Storage::disk('public')->url('quotes/'.$quote->created_by.'/'.$fileName);
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
            return response()->json(['msg' => 'Quote successfully send to the customer.', 'red' => route('quotes.show', $quote_id), 'pdf' => $pdf]);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later.'], 304);
        endif;
    }

    public function renderEmailTemplate(Quote $quote, $subject, $content): array {
        // Build shortcode replacements
        $companyName = $quote->user->companies->pluck('company_name')->first();
        $shortcodes = [
            ':customername'         => $quote->customer->full_name ?? '',
            ':customercompany'      => $quote->customer->company_name ?? '',
            ':jobref'               => $quote->job->reference_no ?? '',
            ':jobbuilding'          => isset($quote->job->property->address_line_1) && !empty($quote->job->property->address_line_1) ? $quote->job->property->address_line_1 : '',
            ':jobstreet'            => isset($quote->job->property->address_line_2) && !empty($quote->job->property->address_line_1) ? $quote->job->property->address_line_2 : '',
            ':jobregion'            => isset($quote->job->property->state) && !empty($quote->job->property->state) ? $quote->job->property->state : '',
            ':jobpostcode'          => isset($quote->job->property->postal_code) && !empty($quote->job->property->postal_code) ? $quote->job->property->postal_code : '',
            ':jobtown'              => isset($quote->job->property->city) && !empty($quote->job->property->city) ? $quote->job->property->city : '',
            ':propertyaddress'      => isset($quote->property->full_address) && !empty($quote->property->full_address) ? $quote->property->full_address : '',
            ':contactphone'         => isset($quote->user->mobile) && !empty($quote->user->mobile) ? $quote->user->mobile : '',
            ':companyname'          => $companyName ?? '',
            ':engineername'         => $quote->user->name ?? '',
            ':eventdate'            => isset($quote->job->calendar->date) && !empty($quote->job->calendar->date) ? date('d-m-Y', strtotime($quote->job->calendar->date)) : '',
            ':eventtime'            => (isset($quote->job->calendar->slot->start) && !empty($quote->job->calendar->slot->start) ? date('H:i', strtotime($quote->job->calendar->slot->start)) : '').(isset($quote->job->calendar->slot->end) && !empty($quote->job->calendar->slot->end) ? ' - '.date('H:i', strtotime($quote->job->calendar->slot->end)) : ''),
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

    // private function generateReferenceNo($customerId, $company){
    //     $customer = Customer::find($customerId);
    //     if (!$customer) return null;
        
    //     $nameParts = (isset($company->company_name) && !empty($company->company_name) ? explode(' ', $company->company_name) : []);
    //     //$nameParts = explode(' ', trim($customer->company_name));
    //     $prefix = '';
    //     foreach ($nameParts as $part):
    //         $prefix .= strtoupper(substr($part, 0, 1));
    //     endforeach;
    //     $lastJob = CustomerJob::where('customer_id', $customerId)->orderBy('id', 'desc')->first();

    //     if ($lastJob && preg_match('/\d+$/', $lastJob->reference_no, $matches)):
    //         $nextNumber = intval($matches[0]) + 1;
    //     else:
    //         $nextNumber = 1;
    //     endif;
    //     $referenceNo = $prefix . $nextNumber;

    //     return $referenceNo;
    // }


    

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
}
