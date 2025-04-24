<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasBoilerSystemCommissioningChecklist;
use App\Models\GasBoilerSystemCommissioningChecklistAppliance;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;

class GasBoilerSystemCommissioningChecklistController extends Controller
{
    public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasBoilerSystemCommissioningChecklist::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasBoilerSystemCommissioningChecklist::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasBoilerSystemCommissioningChecklist::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => auth()->user()->id,
        ]);
    }

    public function show(GasBoilerSystemCommissioningChecklist $gbscc){
        $user_id = auth()->user()->id;
        $gbscc->load(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
        $form = JobForm::find($gbscc->job_form_id);
        $record = $form->slug;

        if(empty($gbscc->certificate_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastCertificate = GasBoilerSystemCommissioningChecklist::where('job_form_id', $form->id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
            $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

            $cerSerial = $starting_form;
            if(!empty($lastCertificateNo)):
                preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                $cerSerial = (int) $certificateNumbers[1] + 1;
            endif;
            $certificateNumber = $prifix.str_pad($cerSerial, 6, '0', STR_PAD_LEFT);
            GasBoilerSystemCommissioningChecklist::where('id', $gbscc->id)->update(['certificate_number' => $certificateNumber]);
        endif;

        $thePdf = $this->generatePdf($gbscc->id);
        return view('app.new-records.'.$record.'.show', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'gbscc' => $gbscc,
            'gbscca1' => GasBoilerSystemCommissioningChecklistAppliance::where('gas_boiler_system_commissioning_checklist_id', $gbscc->id)->where('appliance_serial', 1)->get()->first(),
            'signature' => $gbscc->signature ? Storage::disk('public')->url($gbscc->signature->filename) : '',
            'thePdf' => $thePdf
        ]);
    }

    public function store(Request $request){
        $gbscc_id = $request->gbscc_id;
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $submit_type = $request->submit_type;
        $gbscc = GasBoilerSystemCommissioningChecklist::find($gbscc_id);

        $red = '';
        $pdf = Storage::disk('public')->url('gsrvr/'.$gbscc->customer_job_id.'/'.$gbscc->job_form_id.'/'.$gbscc->certificate_number.'.pdf');
        $message = '';
        $pdf = $this->generatePdf($gbscc_id);
        if($submit_type == 2):
            $data = [];
            $data['status'] = 'Approved & Sent';

            GasBoilerSystemCommissioningChecklist::where('id', $gbscc_id)->update($data);
            
            $email = $this->sendEmail($gbscc_id, $job_form_id);
            $message = (!$email ? 'Gas Boiler System Commissioning Checklist Certificate has been approved. Email cannot be sent due to an invalid or empty email address.' : 'Gas Boiler System Commissioning Checklist Certificate has been approved and a copy of the certificate mailed to the customer');
        else:
            $data = [];
            $data['status'] = 'Approved';

            GasBoilerSystemCommissioningChecklist::where('id', $gbscc_id)->update($data);
            $message = 'Gas Boiler System Commissioning Checklist Certificate successfully approved.';
        endif;

        return response()->json(['msg' => $message, 'red' => route('company.dashboard'), 'pdf' => $pdf]);
    }

    public function sendEmail($gbscc_id, $job_form_id){
        $user_id = auth()->user()->id;
        $gbscc = GasBoilerSystemCommissioningChecklist::with('job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($gbscc_id);
        $customerName = (isset($gbscc->customer->full_name) && !empty($gbscc->customer->full_name) ? $gbscc->customer->full_name : '');
        $customerEmail = (isset($gbscc->customer->contact->email) && !empty($gbscc->customer->contact->email) ? $gbscc->customer->contact->email : '');
        if(!empty($customerEmail)):
            $template = JobFormEmailTemplate::where('user_id', $user_id)->where('job_form_id', $job_form_id)->get()->first();
            $subject = (isset($template->subject) && !empty($template->subject) ? $template->subject : 'Gas Safety Record');
            $content = (isset($template->content) && !empty($template->content) ? $template->subjcontentect : '');
            if($content == ''):
                $content .= 'Hi '.$customerName.',<br/><br/>';
                $content .= 'Please check attachment for details.<br/><br/>';
                $content .= 'Thanks & Regards<br/>';
                $content .= 'Gas Safety Engineer';
            endif;
            
            $sendTo = [$customerEmail];
            $configuration = [
                'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'smtp_port' => env('MAIL_PORT', '587'),
                'smtp_username' => env('MAIL_USERNAME', 'no-reply@lcc.ac.uk'),
                'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
                'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
                
                'from_email'    => env('MAIL_FROM_ADDRESS', 'no-reply@lcc.ac.uk'),
                'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'),

            ];

            $fileName = $gbscc->certificate_number.'.pdf';
            $attachmentFiles = [];
            if (Storage::disk('public')->exists('gbscc/'.$gbscc->customer_job_id.'/'.$gbscc->job_form_id.'/'.$fileName)):
                $attachmentFiles[] = [
                    "pathinfo" => 'gbscc/'.$gbscc->customer_job_id.'/'.$gbscc->job_form_id.'/'.$fileName,
                    "nameinfo" => $fileName,
                    "mimeinfo" => 'application/pdf',
                    "disk" => 'public'
                ];
            endif;

            GCEMailerJob::dispatch($configuration, $sendTo, new GCESendMail($subject, $content, $attachmentFiles));
            return true;
        else:
            return false;
        endif;
    }

    public function generatePdf($gbscc_id) {
        $user_id = auth()->user()->id;
        $gbscc = GasBoilerSystemCommissioningChecklist::with('customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company')->find($gbscc_id);
        $gbscca1 = GasBoilerSystemCommissioningChecklistAppliance::where('gas_boiler_system_commissioning_checklist_id', $gbscc->id)->where('appliance_serial', 1)->get()->first();

        $logoPath = resource_path('images/gas_safe_register_dark.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $userSignBase64 = (isset($gbscc->user->signature) && Storage::disk('public')->exists($gbscc->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gbscc->user->signature->filename)) : '');
        $signatureBase64 = ($gbscc->signature && Storage::disk('public')->exists($gbscc->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gbscc->signature->filename)) : '');
        

        $report_title = 'Certificate of '.$gbscc->certificate_number;
        $PDFHTML = '';
        $PDFHTML .= '<html>';
            $PDFHTML .= '<head>';
                $PDFHTML .= '<title>'.$report_title.'</title>';
                $PDFHTML .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
                $PDFHTML .= '<style>
                                *{border-width: 0; border-style: solid;border-color: #e5e7eb;}
                                body{font-family: Tahoma, sans-serif; font-size: 0.875rem; line-height: 1.25rem; color: #475569; padding-top: 0;}
                                table{margin-left: 0px; width: 100%; border-collapse: collapse; text-indent: 0;border-color: inherit;}
                                figure{margin: 0;}
                                @media print{  .no-page-break { page-break-inside: avoid; } .page-break-before { page-break-before: always; } .page-break-after { page-break-after: always; } }
                                @page{margin: .375rem;}

                                .text-center{text-align: center;}
                                .text-left{text-align: left;}
                                .text-right{text-align: right;}
                                .align-top{vertical-align: top;}
                                .align-middle{vertical-align: middle;}

                                .font-medium{font-weight: bold; }
                                .font-bold{font-weight: bold;}
                                .font-sm{font-size: 12px;}
                                .text-2xl{font-size: 1.5rem;}
                                .text-xl{font-size: 1rem;}
                                .text-10px{font-size: 10px;}
                                .text-11px{font-size: 11px;}
                                .text-12px{font-size: 12px;}
                                .text-13px{font-size: 13px;}
                                .text-sm {font-size: 0.875rem;line-height: 1.25rem;}
                                .leading-1{line-height: 1;}
                                .leading-none{line-height: 1;}
                                .leading-20px{line-height: 20px;}
                                .leading-28px{line-height: 28px;}
                                .leading-none {line-height: 1;}
                                .leading-1-3{line-height: 1.3;}
                                .leading-1-2{line-height: 1.2;}
                                .leading-1-1{line-height: 1.1;}
                                .leading-1-5{line-height: 1.5;}
                                .tracking-normal {letter-spacing: 0em;}
                                .text-primary{color: #164e63;}
                                .text-slate-400{color: #94a3b8;}
                                .text-white{color: #FFF;}
                                .uppercase {text-transform: uppercase;}
                                .whitespace-nowrap{white-space: nowrap;}
                                
                                .w-auto{width: auto;}
                                .w-28{width: 7rem;}
                                .w-full{width: 100%;}
                                .w-50-percent, .w-half{width: 50%;}
                                .w-25-percent{width: 25%;}
                                .w-41-percent{width: 41%;}
                                .w-20-percent{width: 20%;}
                                .w-col2{width: 16.666666%;}
                                .w-col4{width: 33.333333%;}
                                .w-col5{width: 41.666666%;}
                                .w-col7{width: 58.333333%;}
                                .w-col8{width: 66.666666%;}
                                .w-col9{width: 75%;}
                                .w-col3{width: 25%;}
                                .w-32 {width: 8rem;}
                                .w-105px{width: 105px;}
                                .w-110px{width: 110px;}
                                .w-115px{width: 115px;}
                                .w-36px{width: 36px;}
                                .w-130px{width: 130px;}
                                .w-140px{width: 140px;}
                                .w-70px{width: 70px;}
                                .w-60px{width: 60px;}
                                .h-auto{height: auto;}
                                .h-29px{height: 29px;}
                                .h-94px{height: 94px;}
                                .h-35px{height: 35px;}
                                .h-60px{height: 60px;}
                                .h-70px{height: 70px;}
                                .h-80px{height: 80px;}
                                .h-100px{height: 100px;}
                                .h-112px{height: 112px;}
                                .h-25px{height: 25px;}
                                .h-45px{height: 45px;}
                                .h-50px{height: 50px;}
                                .h-30px{height: 30px;}
                                .h-83px{height: 83px;}

                                .pt-0{padding-top: 0;}
                                .pr-0{padding-right: 0;}
                                .pb-0{padding-bottom: 0;}
                                .pl-0{padding-left: 0;}
                                .p-0{padding: 0;}
                                .p-25{padding: 0.625rem;}
                                .p-3{padding: 0.75rem;}
                                .p-5{padding: 1.25rem;}
                                .py-05{padding-top: 0.125rem;padding-bottom: 0.125rem;}
                                .py-025{padding-top: 0.0625rem;padding-bottom: 0.0625rem;}
                                .py-1{padding-top: 0.25rem;padding-bottom: 0.25rem;}
                                .py-1-5{padding-top: 0.375rem;padding-bottom: 0.375rem;}
                                .py-2{padding-top: 0.5rem;padding-bottom: 0.5rem;}
                                .py-3{padding-top: 0.75rem;padding-bottom: 0.75rem;}
                                .px-5{padding-left: 1.25rem;padding-right: 1.25rem;}
                                .px-2{padding-left: 0.5rem;padding-right: 0.5rem;}
                                .px-1{padding-left: 0.25rem;padding-right: 0.25rem;}
                                .pt-1{padding-top: 0.25rem;}
                                .pt-1-5{padding-top: 0.375rem;}
                                .pt-2{padding-top: 0.5rem;}
                                .pr-2{padding-right: 0.5rem;}
                                .pr-1{padding-right: 0.25rem;}
                                .pl-1{padding-left: 0.25rem;}
                                .pl-2{padding-left: 0.5rem;}
                                .pb-1{padding-bottom: 0.25rem;}
                                .pb-2{padding-bottom: 0.25rem;}
                                .pt-05{padding-top: 0.125rem;}
                                .pb-05{padding-bottom: 0.125rem;}
                                .mb-05{margin-bottom: 0.25rem;}
                                .mb-1{margin-bottom: 0.5rem;}
                                .mt-1-5{margin-top: 0.375rem;}
                                .mb-2{margin-bottom: 0.5rem;}
                                .mt-2{margin-top: 0.5rem;}
                                .mt-3{margin-top: 0.75rem;}
                                .mt-0{margin-top: 0;}
                                .mb-0{margin-bottom: 0;}
                                .m-2{margin: .5rem;}
                                .mr-1{margin-right: .25rem;}

                                .bg-danger{ background: #b91c1c; }
                                .bg-warning{ background: #f59e0b; }
                                .bg-primary{ background: #164e63; }
                                .bg-white{background: #FFF;}
                                .bg-readonly{ background-color: #f1f5f9;}
                                .bg-light-2{background-color: #D4EFFB;}
                                .bordered{border-width: 1px;}
                                .border-none {border-style: none;}
                                .border-t{border-top-width: 1px;}
                                .border-t-0{border-top-width: 0;}
                                .border-r{border-right-width: 1px;}
                                .border-r-0{border-right-width: 0;}
                                .border-b{border-bottom-width: 1px;}
                                .border-b-0{border-bottom-width: 0;}
                                .border-l{border-left-width: 1px;}
                                .border-0{border-left-width: 0;}
                                .border-b-1{border-bottom-width: 1px;}
                                .border-l-sec{border-left-color: #1d6a87 !important;}
                                .border-r-sec{border-right-color: #1d6a87 !important;}
                                .border-b-sec{border-bottom-color: #1d6a87 !important;}
                                .border-t-sec{border-top-color: #1d6a87 !important;}
                                .border-slate-200 {border-color: #e2e8f0;}
                                .border-primary{border-color: #164e63;}
                                .border-b-white{border-bottom-color: #FFF;}
                                .rounded-none{border-radius: 0px;}
                                
                                .inline-block {display: inline-block;}
                            </style>';
            $PDFHTML .= '</head>';

            $PDFHTML .= '<body>';
                $PDFHTML .= '<div class="header bg-primary p-25">';
                    $PDFHTML .= '<table class="grid grid-cols-12 gap-4 items-center">';
                        $PDFHTML .= '<tbody>';
                            $PDFHTML .= '<tr>';
                                $PDFHTML .= '<td class="w-col2 align-middle">';
                                    $PDFHTML .= '<img class="w-auto h-80px" src="'.$logoBase64.'" alt="Gas Safe Register Logo">';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col8 text-center align-middle px-5">';
                                    $PDFHTML .= '<h1 class="text-white text-2xl leading-none mt-0 mb-0">Gas Boiler System Commissioning Checklist</h1>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col2 align-middle text-center">';
                                    $PDFHTML .= '<label class="text-white uppercase font-medium text-12px leading-none mb-2 inline-block">Certificate Number</label>';
                                    $PDFHTML .= '<div class="inline-block bg-white w-32 text-center rounded-none leading-28px h-35px font-medium text-primary">'.$gbscc->certificate_number.'</div>';
                                $PDFHTML .= '</td>';
                            $PDFHTML .= '</tr>';
                        $PDFHTML .= '</tbody>';
                    $PDFHTML .= '</table>';
                $PDFHTML .= '</div>';

                $PDFHTML .= '<div class="recordInfo mt-1-5">';
                    $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                        $PDFHTML .= '<thead>';
                            $PDFHTML .= '<tr>';
                                $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-t-0 border-r-sec text-white text-12px uppercase leading-none px-2 py-1 text-left">';
                                    $PDFHTML .= 'COMPANY / INSTALLER';
                                $PDFHTML .= '</th>';
                                $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-t-0 border-r-sec text-white text-12px uppercase leading-none px-2 py-1 text-left">';
                                    $PDFHTML .= 'INSPECTION / INSTALLATION ADDRESS';
                                $PDFHTML .= '</th>';
                                $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-t-0 text-white text-12px uppercase leading-none px-2 py-1 text-left">';
                                    $PDFHTML .= 'LANDLORD / AGENT / CUSTOMER';
                                $PDFHTML .= '</th>';
                            $PDFHTML .= '</tr>';
                        $PDFHTML .= '</thead>';
                        $PDFHTML .= '<tbody>';
                            $PDFHTML .= '<tr>';
                                $PDFHTML .= '<td class="w-50-percent p-0 border-primary align-top">';
                                    $PDFHTML .= '<table class="border-none">';
                                        $PDFHTML .= '<tbody>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="w-50-percent p-0 border-l-0 border-t-0 border-r-0 border-b-0 align-top">';
                                                    $PDFHTML .= '<table class="border-none">';
                                                        $PDFHTML .= '<tbody>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Engineer</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gbscc->user->name) && !empty($gbscc->user->name) ? $gbscc->user->name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">GAS SAFE REG.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->user->company->gas_safe_registration_no) && !empty($gbscc->user->company->gas_safe_registration_no) ? $gbscc->user->company->gas_safe_registration_no : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">ID CARD NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->user->gas_safe_id_card) && !empty($gbscc->user->gas_safe_id_card) ? $gbscc->user->gas_safe_id_card : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">&nbsp;</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">&nbsp;</td>';
                                                            $PDFHTML .= '</tr>';
                                                        $PDFHTML .= '</tbody>';
                                                    $PDFHTML .= '</table>';
                                                $PDFHTML .= '</td>';
                                                $PDFHTML .= '<td class="w-50-percent p-0 border-l-0 border-t-0 border-r-0 border-b-0 align-top">';
                                                    $PDFHTML .= '<table class="border-none">';
                                                        $PDFHTML .= '<tbody>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->user->company->company_name) && !empty($gbscc->user->company->company_name) ? $gbscc->user->company->company_name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gbscc->user->company->pdf_address) && !empty($gbscc->user->company->pdf_address) ? $gbscc->user->company->pdf_address : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">TEL NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->user->company->company_phone) && !empty($gbscc->user->company->company_phone) ? $gbscc->user->company->company_phone : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Email</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->user->company->company_email) && !empty($gbscc->user->company->company_email) ? $gbscc->user->company->company_email : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                        $PDFHTML .= '</tbody>';
                                                    $PDFHTML .= '</table>';
                                                $PDFHTML .= '</td>';
                                            $PDFHTML .= '</tr>';
                                        $PDFHTML .= '</tbody>';
                                    $PDFHTML .= '</table>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-25-percent p-0 border-primary align-top">';
                                    $PDFHTML .= '<table class="border-none">';
                                        $PDFHTML .= '<tbody>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->job->property->occupant_name) && !empty($gbscc->job->property->occupant_name) ? $gbscc->job->property->occupant_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gbscc->job->property->pdf_address) && !empty($gbscc->job->property->pdf_address) ? $gbscc->job->property->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->job->property->postal_code) && !empty($gbscc->job->property->postal_code) ? $gbscc->job->property->postal_code : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">&nbsp;</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle"></td>';
                                            $PDFHTML .= '</tr>';
                                        $PDFHTML .= '</tbody>';
                                    $PDFHTML .= '</table>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-25-percent p-0 border-primary align-top">';
                                    $PDFHTML .= '<table class="border-none">';
                                        $PDFHTML .= '<tbody>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->customer->full_name) && !empty($gbscc->customer->full_name) ? $gbscc->customer->full_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->customer->company_name) && !empty($gbscc->customer->company_name) ? $gbscc->customer->company_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gbscc->customer->pdf_address) && !empty($gbscc->customer->pdf_address) ? $gbscc->customer->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbscc->customer->postal_code) && !empty($gbscc->customer->postal_code) ? $gbscc->customer->postal_code : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                        $PDFHTML .= '</tbody>';
                                    $PDFHTML .= '</table>';
                                $PDFHTML .= '</td>';
                            $PDFHTML .= '</tr>';
                        $PDFHTML .= '</tbody>';
                    $PDFHTML .= '</table>';
                $PDFHTML .= '</div>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                $PDFHTML .= 'Appliance Details';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left font-medium leading-1-2 border-b border-r">Make</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->make->name) && !empty($gbscca1->make->name) ? $gbscca1->make->name : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left font-medium leading-1-2 border-b border-r">Model</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->model) && !empty($gbscca1->model) ? $gbscca1->model : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left font-medium leading-1-2 border-b border-r">Serial No</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->serial_no) && !empty($gbscca1->serial_no) ? $gbscca1->serial_no : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';
                $PDFHTML .= '<table class="p-0 border-none mt-1-5">';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="w-col4 pr-1 pl-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Controls';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Time and temp control to heating</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->temperature->name) && !empty($gbscca1->temperature->name) ? $gbscca1->temperature->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Time and temp control to hot water</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->tmp_control_hot_water) && !empty($gbscca1->tmp_control_hot_water) ? $gbscca1->tmp_control_hot_water : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';    
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Heating zone valves</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->heating_zone_vlv) && !empty($gbscca1->heating_zone_vlv) ? $gbscca1->heating_zone_vlv : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';    
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Hot water zone valves</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->hot_water_zone_vlv) && !empty($gbscca1->hot_water_zone_vlv) ? $gbscca1->hot_water_zone_vlv : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';     
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Thermostatic radiator valves</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->therm_radiator_vlv) && !empty($gbscca1->therm_radiator_vlv) ? $gbscca1->therm_radiator_vlv : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>'; 
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Automatic bypass to system</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->bypass_to_system) && !empty($gbscca1->bypass_to_system) ? $gbscca1->bypass_to_system : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';     
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Boiler interlock</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->boiler_interlock) && !empty($gbscca1->boiler_interlock) ? $gbscca1->boiler_interlock : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Combination boilers only';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">is the installation in a hard water area (above 200ppm)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->is_in_hard_water_area) && !empty($gbscca1->is_in_hard_water_area) ? $gbscca1->is_in_hard_water_area : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">If yes and if required by the manufacturer, has the water scale reducer been fitted</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->is_scale_reducer_fitted) && !empty($gbscca1->is_scale_reducer_fitted) ? $gbscca1->is_scale_reducer_fitted : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';    
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">What type of scale reducer has been fitted</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->what_reducer_fitted) && !empty($gbscca1->what_reducer_fitted) ? $gbscca1->what_reducer_fitted : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col4 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'All Systems';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">The system has been flushed and cleaned in accordance with BS7593 and boiler manufacturer\'s instructions</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->flushed_and_cleaned) && !empty($gbscca1->flushed_and_cleaned) ? $gbscca1->flushed_and_cleaned : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">What cleaner was used</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->clearner_name) && !empty($gbscca1->clearner_name) ? $gbscca1->clearner_name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">What inhibitor was used</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->inhibitor_quantity) && !empty($gbscca1->inhibitor_quantity) ? 'Quantity: '.$gbscca1->inhibitor_quantity : '').(isset($gbscca1->inhibitor_amount) && !empty($gbscca1->inhibitor_amount) ? ' Liters: '.$gbscca1->inhibitor_amount : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Has a primary water system filter been installed</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->primary_ws_filter_installed) && !empty($gbscca1->primary_ws_filter_installed) ? $gbscca1->primary_ws_filter_installed : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Domestic hot water mode';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Gas Rate '.(isset($gbscca1->dom_gas_rate_unit) && !empty($gbscca1->dom_gas_rate_unit) ? '('.$gbscca1->dom_gas_rate_unit.')' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->dom_gas_rate) && !empty($gbscca1->dom_gas_rate) ? $gbscca1->dom_gas_rate : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Burner operating pressure (or inlet pressure) at maximum rate '.(isset($gbscca1->dom_burner_opt_pressure_unit) && !empty($gbscca1->dom_burner_opt_pressure_unit) ? '('.$gbscca1->dom_burner_opt_pressure_unit.')' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->dom_burner_opt_pressure) && !empty($gbscca1->dom_burner_opt_pressure) ? $gbscca1->dom_burner_opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Cold water inlet temperature (°C)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->dom_cold_water_temp) && !empty($gbscca1->dom_cold_water_temp) ? $gbscca1->dom_cold_water_temp : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Hot water has been checked at all outlets</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->dom_checked_outlet) && !empty($gbscca1->dom_checked_outlet) ? $gbscca1->dom_checked_outlet : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Water flow rate (l/min)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->dom_water_flow_rate) && !empty($gbscca1->dom_water_flow_rate) ? $gbscca1->dom_water_flow_rate : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">&nbsp;</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4"></td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col4 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Central heating mode';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Gas Rate '.(isset($gbscca1->gas_rate_unit) && !empty($gbscca1->gas_rate_unit) ? $gbscca1->gas_rate_unit : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->gas_rate) && !empty($gbscca1->gas_rate) ? $gbscca1->gas_rate : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Central heating output left at factory setting</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->cho_factory_setting) && !empty($gbscca1->cho_factory_setting) ? $gbscca1->cho_factory_setting : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Burner operating pressure (or inlet pressure) at maximum rate '.(isset($gbscca1->burner_opt_pressure_unit) && !empty($gbscca1->burner_opt_pressure_unit) ? '('.$gbscca1->burner_opt_pressure_unit.')' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->burner_opt_pressure) && !empty($gbscca1->burner_opt_pressure) ? $gbscca1->burner_opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Central heating flow temperature (°C)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->centeral_heat_flow_temp) && !empty($gbscca1->centeral_heat_flow_temp) ? $gbscca1->centeral_heat_flow_temp : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Central heating return temperature (°C)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->centeral_heat_return_temp) && !empty($gbscca1->centeral_heat_return_temp) ? $gbscca1->centeral_heat_return_temp : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Condensing boilers only';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">The condensate drain has been installed in accordance with the manufacturer\'s instructions and/or BS5546/BS6798</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->con_drain_installed) && !empty($gbscca1->con_drain_installed) ? $gbscca1->con_drain_installed : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Point of Termination</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->point_of_termination) && !empty($gbscca1->point_of_termination) ? $gbscca1->point_of_termination : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Method of Disposal</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">'.(isset($gbscca1->dispsal_method) && !empty($gbscca1->dispsal_method) ? $gbscca1->dispsal_method : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">&nbsp;</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4"></td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">&nbsp;</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4"></td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="10" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                $PDFHTML .= 'All installations';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Min. ratio</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">CO at min. rate</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">CO/CO at min. rate</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Max. ratio</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">CO at max. rate</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">CO/CO at max. rate</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">The heating and hot water system complies with the appropriate Building Regulations</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">The boiler and associated products have been installed and commissioned in accordance with the manufacturer\'s instructions</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">The operation of the boiler system controls have been demonstrated to and understood by the customer</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">The manufacturer\'s literature, including Benchmark Checklist and Service Record, has been explained and left with the customer</td>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->min_ratio) && !empty($gbscca1->min_ratio) ? $gbscca1->min_ratio : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->min_co) && !empty($gbscca1->min_co) ? $gbscca1->min_co : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->min_co2) && !empty($gbscca1->min_co2) ? $gbscca1->min_co2 : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->max_ratio) && !empty($gbscca1->max_ratio) ? $gbscca1->max_ratio : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->max_co) && !empty($gbscca1->max_co) ? $gbscca1->max_co : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->max_co2) && !empty($gbscca1->max_co2) ? $gbscca1->max_co2 : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->app_building_regulation) && !empty($gbscca1->app_building_regulation) ? $gbscca1->app_building_regulation : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->commissioned_man_ins) && !empty($gbscca1->commissioned_man_ins) ? $gbscca1->commissioned_man_ins : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->demonstrated_understood) && !empty($gbscca1->demonstrated_understood) ? $gbscca1->demonstrated_understood : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">'.(isset($gbscca1->literature_including) && !empty($gbscca1->literature_including) ? $gbscca1->literature_including : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $inspectionDeate = (isset($gbscc->inspection_date) && !empty($gbscc->inspection_date) ? date('d-m-Y', strtotime($gbscc->inspection_date)) : date('d-m-Y'));
                $nextInspectionDate = (isset($gbscc->next_inspection_date) && !empty($gbscc->next_inspection_date) ? date('d-m-Y', strtotime($gbscc->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                
                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="'.($gbscca1->is_next_inspection == 'Applicable' ? '3' : '2').'" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                $PDFHTML .= 'SIGNATURES';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="w-'.($gbscca1->is_next_inspection == 'Applicable' ? '41-percent' : 'half').' p-0 border-primary align-top border-b-0">';
                                $PDFHTML .= '<table class="table border-none">';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-top">Signature</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-50px align-top">';
                                                if($userSignBase64):
                                                    $PDFHTML .= '<img src="'.$userSignBase64.'" alt="signature" class="h-50px w-auto inline-block"/>';
                                                endif;
                                            $PDFHTML .= '</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Issued By</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gbscc->user->name) && !empty($gbscc->user->name) ? $gbscc->user->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Date of Issue</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.$inspectionDeate.'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-'.($gbscca1->is_next_inspection == 'Applicable' ? '41-percent' : 'half').' p-0 border-primary align-top border-b-0">';
                                $PDFHTML .= '<table class="table border-none">';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-top">Signature</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-50px align-top">';
                                                if($signatureBase64):
                                                    $PDFHTML .= '<img src="'.$signatureBase64.'" alt="signature" class="h-50px w-auto inline-block"/>';
                                                endif;
                                            $PDFHTML .= '</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Received By</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gbscc->relation->name) && !empty($gbscc->relation->name) ? $gbscc->relation->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gbscc->received_by) && !empty($gbscc->received_by) ? $gbscc->received_by : (isset($gbscc->customer->full_name) && !empty($gbscc->customer->full_name) ? $gbscc->customer->full_name : '')).'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            if($gbscca1->is_next_inspection == 'Applicable'):
                                $PDFHTML .= '<td class="w-20-percent p-0 border-primary border-b-0 align-middle bg-light-2 text-primary text-center px-3">';
                                    $PDFHTML .= '<div class="text-primary uppercase font-medium text-12px leading-none mb-1 px-2">Next Inspection Date</div>';
                                    $PDFHTML .= '<div class="inline-block bg-white w-col9 text-center rounded-none h-30px text-12px font-medium">'.$nextInspectionDate.'</div>';
                                $PDFHTML .= '</td>';
                            endif;
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

            $PDFHTML .= '</body>';
        $PDFHTML .= '</html>';


        $fileName = $gbscc->certificate_number.'.pdf';
        if (Storage::disk('public')->exists('gbscc/'.$gbscc->customer_job_id.'/'.$gbscc->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('gbscc/'.$gbscc->customer_job_id.'/'.$gbscc->job_form_id.'/'.$fileName);
        }
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('gbscc/'.$gbscc->customer_job_id.'/'.$gbscc->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('gbscc/'.$gbscc->customer_job_id.'/'.$gbscc->job_form_id.'/'.$fileName);
    }

    
    
    public function storeNew(Request $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;
        $form = JobForm::find($job_form_id);

        $certificate_id = (isset($request->certificate_id) && $request->certificate_id > 0 ? $request->certificate_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $property = CustomerProperty::find($customer_property_id);
        
        $appliances = json_decode($request->appliances);

        if($customer_job_id == 0):
            $customerJob = CustomerJob::create([
                'customer_id' => $customer_id,
                'customer_property_id' => $customer_property_id,
                'description' => $form->name,
                'details' => 'Job created for '.$property->full_address,

                'created_by' => auth()->user()->id
            ]);
            $customer_job_id = ($customerJob->id ? $customerJob->id : $customer_job_id);
        endif;

        if($customer_job_id > 0):
            $gasBoilerSCC = GasBoilerSystemCommissioningChecklist::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            $this->checkAndUpdateRecordHistory($gasBoilerSCC->id);

            if(!empty($appliances) && $gasBoilerSCC->id):
                $appliance_serial = (isset($appliances->appliance_serial) && $appliances->appliance_serial > 0 ? $appliances->appliance_serial : 1);
                $gasAppliance = GasBoilerSystemCommissioningChecklistAppliance::updateOrCreate(['gas_boiler_system_commissioning_checklist_id' => $gasBoilerSCC->id, 'appliance_serial' => $appliance_serial], [
                    'gas_boiler_system_commissioning_checklist_id' => $gasBoilerSCC->id,
                    'appliance_serial' => $appliance_serial,
                    
                    'boiler_brand_id' => (isset($appliances->boiler_brand_id) && !empty($appliances->boiler_brand_id) ? $appliances->boiler_brand_id : null),
                    'model' => (isset($appliances->model) && !empty($appliances->model) ? $appliances->model : null),
                    'serial_no' => (isset($appliances->serial_no) && !empty($appliances->serial_no) ? $appliances->serial_no : null),
                    'appliance_time_temperature_heating_id' => (isset($appliances->appliance_time_temperature_heating_id) && !empty($appliances->appliance_time_temperature_heating_id) ? $appliances->appliance_time_temperature_heating_id : null),
                    
                    'tmp_control_hot_water' => (isset($appliances->tmp_control_hot_water) && !empty($appliances->tmp_control_hot_water) ? $appliances->tmp_control_hot_water : null),
                    'heating_zone_vlv' => (isset($appliances->heating_zone_vlv) && !empty($appliances->heating_zone_vlv) ? $appliances->heating_zone_vlv : null),
                    'hot_water_zone_vlv' => (isset($appliances->hot_water_zone_vlv) && !empty($appliances->hot_water_zone_vlv) ? $appliances->hot_water_zone_vlv : null),
                    'therm_radiator_vlv' => (isset($appliances->therm_radiator_vlv) && !empty($appliances->therm_radiator_vlv) ? $appliances->therm_radiator_vlv : null),
                    'bypass_to_system' => (isset($appliances->bypass_to_system) && !empty($appliances->bypass_to_system) ? $appliances->bypass_to_system : null),
                    'boiler_interlock' => (isset($appliances->boiler_interlock) && !empty($appliances->boiler_interlock) ? $appliances->boiler_interlock : null),
                    'flushed_and_cleaned' => (isset($appliances->flushed_and_cleaned) && !empty($appliances->flushed_and_cleaned) ? $appliances->flushed_and_cleaned : null),
                    'clearner_name' => (isset($appliances->clearner_name) && !empty($appliances->clearner_name) ? $appliances->clearner_name : null),
                    'inhibitor_quantity' => (isset($appliances->inhibitor_quantity) && !empty($appliances->inhibitor_quantity) ? $appliances->inhibitor_quantity : null),
                    'inhibitor_amount' => (isset($appliances->inhibitor_amount) && !empty($appliances->inhibitor_amount) ? $appliances->inhibitor_amount : null),
                    'primary_ws_filter_installed' => (isset($appliances->primary_ws_filter_installed) && !empty($appliances->primary_ws_filter_installed) ? $appliances->primary_ws_filter_installed : null),
                    'gas_rate' => (isset($appliances->gas_rate) && !empty($appliances->gas_rate) ? $appliances->gas_rate : null),
                    'gas_rate_unit' => (isset($appliances->gas_rate_unit) && !empty($appliances->gas_rate_unit) ? $appliances->gas_rate_unit : null),
                    'cho_factory_setting' => (isset($appliances->cho_factory_setting) && !empty($appliances->cho_factory_setting) ? $appliances->cho_factory_setting : null),
                    'burner_opt_pressure' => (isset($appliances->burner_opt_pressure) && !empty($appliances->burner_opt_pressure) ? $appliances->burner_opt_pressure : null),
                    'burner_opt_pressure_unit' => (isset($appliances->burner_opt_pressure_unit) && !empty($appliances->burner_opt_pressure_unit) ? $appliances->burner_opt_pressure_unit : null),
                    'centeral_heat_flow_temp' => (isset($appliances->centeral_heat_flow_temp) && !empty($appliances->centeral_heat_flow_temp) ? $appliances->centeral_heat_flow_temp : null),
                    'centeral_heat_return_temp' => (isset($appliances->centeral_heat_return_temp) && !empty($appliances->centeral_heat_return_temp) ? $appliances->centeral_heat_return_temp : null),
                    'is_in_hard_water_area' => (isset($appliances->is_in_hard_water_area) && !empty($appliances->is_in_hard_water_area) ? $appliances->is_in_hard_water_area : null),
                    'is_scale_reducer_fitted' => (isset($appliances->is_scale_reducer_fitted) && !empty($appliances->is_scale_reducer_fitted) ? $appliances->is_scale_reducer_fitted : null),
                    'what_reducer_fitted' => (isset($appliances->what_reducer_fitted) && !empty($appliances->what_reducer_fitted) ? $appliances->what_reducer_fitted : null),
                    'dom_gas_rate' => (isset($appliances->dom_gas_rate) && !empty($appliances->dom_gas_rate) ? $appliances->dom_gas_rate : null),
                    'dom_gas_rate_unit' => (isset($appliances->dom_gas_rate_unit) && !empty($appliances->dom_gas_rate_unit) ? $appliances->dom_gas_rate_unit : null),
                    'dom_burner_opt_pressure' => (isset($appliances->dom_burner_opt_pressure) && !empty($appliances->dom_burner_opt_pressure) ? $appliances->dom_burner_opt_pressure : null),
                    'dom_burner_opt_pressure_unit' => (isset($appliances->dom_burner_opt_pressure_unit) && !empty($appliances->dom_burner_opt_pressure_unit) ? $appliances->dom_burner_opt_pressure_unit : null),
                    'dom_cold_water_temp' => (isset($appliances->dom_cold_water_temp) && !empty($appliances->dom_cold_water_temp) ? $appliances->dom_cold_water_temp : null),
                    'dom_checked_outlet' => (isset($appliances->dom_checked_outlet) && !empty($appliances->dom_checked_outlet) ? $appliances->dom_checked_outlet : null),
                    'dom_water_flow_rate' => (isset($appliances->dom_water_flow_rate) && !empty($appliances->dom_water_flow_rate) ? $appliances->dom_water_flow_rate : null),
                    'con_drain_installed' => (isset($appliances->con_drain_installed) && !empty($appliances->con_drain_installed) ? $appliances->con_drain_installed : null),
                    'point_of_termination' => (isset($appliances->point_of_termination) && !empty($appliances->point_of_termination) ? $appliances->point_of_termination : null),
                    'dispsal_method' => (isset($appliances->dispsal_method) && !empty($appliances->dispsal_method) ? $appliances->dispsal_method : null),
                    'min_ratio' => (isset($appliances->min_ratio) && !empty($appliances->min_ratio) ? $appliances->min_ratio : null),
                    'min_co' => (isset($appliances->min_co) && !empty($appliances->min_co) ? $appliances->min_co : null),
                    'min_co2' => (isset($appliances->min_co2) && !empty($appliances->min_co2) ? $appliances->min_co2 : null),
                    'max_ratio' => (isset($appliances->max_ratio) && !empty($appliances->max_ratio) ? $appliances->max_ratio : null),
                    'max_co' => (isset($appliances->max_co) && !empty($appliances->max_co) ? $appliances->max_co : null),
                    'max_co2' => (isset($appliances->max_co2) && !empty($appliances->max_co2) ? $appliances->max_co2 : null),
                    'app_building_regulation' => (isset($appliances->app_building_regulation) && !empty($appliances->app_building_regulation) ? $appliances->app_building_regulation : null),
                    'commissioned_man_ins' => (isset($appliances->commissioned_man_ins) && !empty($appliances->commissioned_man_ins) ? $appliances->commissioned_man_ins : null),
                    'demonstrated_understood' => (isset($appliances->demonstrated_understood) && !empty($appliances->demonstrated_understood) ? $appliances->demonstrated_understood : null),
                    'literature_including' => (isset($appliances->literature_including) && !empty($appliances->literature_including) ? $appliances->literature_including : null),
                    'is_next_inspection' => (isset($appliances->is_next_inspection) && !empty($appliances->is_next_inspection) ? $appliances->is_next_inspection : null),
                    
                    'updated_by' => $user_id,
                ]);
            endif;

            if($request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $gasBoilerSCC->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    $signature = new Signature();
                    $signature->model_type = GasBoilerSystemCommissioningChecklist::class;
                    $signature->model_id = $gasBoilerSCC->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([request()->ip()]);
                    $signature->save();
                endif;
            endif;

            return response()->json(['msg' => 'Certificate successfully created.', 'red' => route('new.records.gas.bscc.record.show', $gasBoilerSCC->id)], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function editReady(Request $request){
        $record_id = $request->record_id;

        $record = GasBoilerSystemCommissioningChecklist::with('customer', 'customer.contact', 'job', 'job.property')->find($record_id);
        $appliances = GasBoilerSystemCommissioningChecklistAppliance::where('gas_boiler_system_commissioning_checklist_id', $record_id)->orderBy('id', 'desc')->get()->first();

        $applianceName = (isset($appliances->make->name) && !empty($appliances->make->name) ? $appliances->make->name.' ' : '');
        $data = [
            'certificate_id' => $record->id,
            'certificate' => [
                'inspection_date' => (isset($record->inspection_date) && !empty($record->inspection_date) ? date('d-m-Y', strtotime($record->inspection_date)) : ''),
                'next_inspection_date' => (isset($record->next_inspection_date) && !empty($record->next_inspection_date) ? date('d-m-Y', strtotime($record->next_inspection_date)) : ''),
                'received_by' => (isset($record->received_by) && !empty($record->received_by) ? $record->received_by : ''),
                'relation_id' => (isset($record->relation_id) && !empty($record->relation_id) ? $record->relation_id : ''),
                'relation_name' => (isset($record->relation->name) && !empty($record->relation->name) ? $record->relation->name : ''),
                'signature' => $record->signature && !empty($record->signature->filename) ? Storage::disk('public')->url($record->signature->filename) : ''
            ],
            'job' => $record->job,
            'customer' => $record->customer,
            'job_address' => $record->job->property,
            'occupant' => [
                'customer_property_occupant_id' => $record->job->property->id,
                'occupant_name' => (isset($record->job->property->occupant_name) && !empty($record->job->property->occupant_name) ? $record->job->property->occupant_name : ''),
                'occupant_email' => (isset($record->job->property->occupant_email) && !empty($record->job->property->occupant_email) ? $record->job->property->occupant_email : ''),
                'occupant_phone' => (isset($record->job->property->occupant_phone) && !empty($record->job->property->occupant_phone) ? $record->job->property->occupant_phone : ''),
            ],
            'appliances' => [
                'appliance_label' => 'Appliance '.$appliances->appliance_serial,
                'appliance_title' => ($applianceName != '' ? $applianceName : 'N/A'),
                'gas_boiler_system_commissioning_checklist_id' => $appliances->gas_warning_notice_id,
                'appliance_serial' => $appliances->appliance_serial,
                
                'boiler_brand_id' => (isset($appliances->boiler_brand_id) && !empty($appliances->boiler_brand_id) ? $appliances->boiler_brand_id : ''),
                'model' => (isset($appliances->model) && !empty($appliances->model) ? $appliances->model : ''),
                'serial_no' => (isset($appliances->serial_no) && !empty($appliances->serial_no) ? $appliances->serial_no : ''),
                'appliance_time_temperature_heating_id' => (isset($appliances->appliance_time_temperature_heating_id) && !empty($appliances->appliance_time_temperature_heating_id) ? $appliances->appliance_time_temperature_heating_id : ''),
                
                'tmp_control_hot_water' => (isset($appliances->tmp_control_hot_water) && !empty($appliances->tmp_control_hot_water) ? $appliances->tmp_control_hot_water : ''),
                'heating_zone_vlv' => (isset($appliances->heating_zone_vlv) && !empty($appliances->heating_zone_vlv) ? $appliances->heating_zone_vlv : ''),
                'hot_water_zone_vlv' => (isset($appliances->hot_water_zone_vlv) && !empty($appliances->hot_water_zone_vlv) ? $appliances->hot_water_zone_vlv : ''),
                'therm_radiator_vlv' => (isset($appliances->therm_radiator_vlv) && !empty($appliances->therm_radiator_vlv) ? $appliances->therm_radiator_vlv : ''),
                'bypass_to_system' => (isset($appliances->bypass_to_system) && !empty($appliances->bypass_to_system) ? $appliances->bypass_to_system : ''),
                'boiler_interlock' => (isset($appliances->boiler_interlock) && !empty($appliances->boiler_interlock) ? $appliances->boiler_interlock : ''),
                'flushed_and_cleaned' => (isset($appliances->flushed_and_cleaned) && !empty($appliances->flushed_and_cleaned) ? $appliances->flushed_and_cleaned : ''),
                'clearner_name' => (isset($appliances->clearner_name) && !empty($appliances->clearner_name) ? $appliances->clearner_name : ''),
                'inhibitor_quantity' => (isset($appliances->inhibitor_quantity) && !empty($appliances->inhibitor_quantity) ? $appliances->inhibitor_quantity : ''),
                'inhibitor_amount' => (isset($appliances->inhibitor_amount) && !empty($appliances->inhibitor_amount) ? $appliances->inhibitor_amount : ''),
                'primary_ws_filter_installed' => (isset($appliances->primary_ws_filter_installed) && !empty($appliances->primary_ws_filter_installed) ? $appliances->primary_ws_filter_installed : ''),
                'gas_rate' => (isset($appliances->gas_rate) && !empty($appliances->gas_rate) ? $appliances->gas_rate : ''),
                'gas_rate_unit' => (isset($appliances->gas_rate_unit) && !empty($appliances->gas_rate_unit) ? $appliances->gas_rate_unit : ''),
                'cho_factory_setting' => (isset($appliances->cho_factory_setting) && !empty($appliances->cho_factory_setting) ? $appliances->cho_factory_setting : ''),
                'burner_opt_pressure' => (isset($appliances->burner_opt_pressure) && !empty($appliances->burner_opt_pressure) ? $appliances->burner_opt_pressure : ''),
                'burner_opt_pressure_unit' => (isset($appliances->burner_opt_pressure_unit) && !empty($appliances->burner_opt_pressure_unit) ? $appliances->burner_opt_pressure_unit : ''),
                'centeral_heat_flow_temp' => (isset($appliances->centeral_heat_flow_temp) && !empty($appliances->centeral_heat_flow_temp) ? $appliances->centeral_heat_flow_temp : ''),
                'centeral_heat_return_temp' => (isset($appliances->centeral_heat_return_temp) && !empty($appliances->centeral_heat_return_temp) ? $appliances->centeral_heat_return_temp : ''),
                'is_in_hard_water_area' => (isset($appliances->is_in_hard_water_area) && !empty($appliances->is_in_hard_water_area) ? $appliances->is_in_hard_water_area : ''),
                'is_scale_reducer_fitted' => (isset($appliances->is_scale_reducer_fitted) && !empty($appliances->is_scale_reducer_fitted) ? $appliances->is_scale_reducer_fitted : ''),
                'what_reducer_fitted' => (isset($appliances->what_reducer_fitted) && !empty($appliances->what_reducer_fitted) ? $appliances->what_reducer_fitted : ''),
                'dom_gas_rate' => (isset($appliances->dom_gas_rate) && !empty($appliances->dom_gas_rate) ? $appliances->dom_gas_rate : ''),
                'dom_gas_rate_unit' => (isset($appliances->dom_gas_rate_unit) && !empty($appliances->dom_gas_rate_unit) ? $appliances->dom_gas_rate_unit : ''),
                'dom_burner_opt_pressure' => (isset($appliances->dom_burner_opt_pressure) && !empty($appliances->dom_burner_opt_pressure) ? $appliances->dom_burner_opt_pressure : ''),
                'dom_burner_opt_pressure_unit' => (isset($appliances->dom_burner_opt_pressure_unit) && !empty($appliances->dom_burner_opt_pressure_unit) ? $appliances->dom_burner_opt_pressure_unit : ''),
                'dom_cold_water_temp' => (isset($appliances->dom_cold_water_temp) && !empty($appliances->dom_cold_water_temp) ? $appliances->dom_cold_water_temp : ''),
                'dom_checked_outlet' => (isset($appliances->dom_checked_outlet) && !empty($appliances->dom_checked_outlet) ? $appliances->dom_checked_outlet : ''),
                'dom_water_flow_rate' => (isset($appliances->dom_water_flow_rate) && !empty($appliances->dom_water_flow_rate) ? $appliances->dom_water_flow_rate : ''),
                'con_drain_installed' => (isset($appliances->con_drain_installed) && !empty($appliances->con_drain_installed) ? $appliances->con_drain_installed : ''),
                'point_of_termination' => (isset($appliances->point_of_termination) && !empty($appliances->point_of_termination) ? $appliances->point_of_termination : ''),
                'dispsal_method' => (isset($appliances->dispsal_method) && !empty($appliances->dispsal_method) ? $appliances->dispsal_method : ''),
                'min_ratio' => (isset($appliances->min_ratio) && !empty($appliances->min_ratio) ? $appliances->min_ratio : ''),
                'min_co' => (isset($appliances->min_co) && !empty($appliances->min_co) ? $appliances->min_co : ''),
                'min_co2' => (isset($appliances->min_co2) && !empty($appliances->min_co2) ? $appliances->min_co2 : ''),
                'max_ratio' => (isset($appliances->max_ratio) && !empty($appliances->max_ratio) ? $appliances->max_ratio : ''),
                'max_co' => (isset($appliances->max_co) && !empty($appliances->max_co) ? $appliances->max_co : ''),
                'max_co2' => (isset($appliances->max_co2) && !empty($appliances->max_co2) ? $appliances->max_co2 : ''),
                'app_building_regulation' => (isset($appliances->app_building_regulation) && !empty($appliances->app_building_regulation) ? $appliances->app_building_regulation : ''),
                'commissioned_man_ins' => (isset($appliances->commissioned_man_ins) && !empty($appliances->commissioned_man_ins) ? $appliances->commissioned_man_ins : ''),
                'demonstrated_understood' => (isset($appliances->demonstrated_understood) && !empty($appliances->demonstrated_understood) ? $appliances->demonstrated_understood : ''),
                'literature_including' => (isset($appliances->literature_including) && !empty($appliances->literature_including) ? $appliances->literature_including : ''),
                'is_next_inspection' => (isset($appliances->is_next_inspection) && !empty($appliances->is_next_inspection) ? $appliances->is_next_inspection : ''),
            ]
        ];

        return response()->json(['row' => $data, 'red' => route('new.records.create', $record->job_form_id)], 200);
    }
}
