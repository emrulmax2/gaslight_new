<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasServiceRecord;
use App\Models\GasServiceRecordAppliance;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;
use Barryvdh\DomPDF\Facade\Pdf;

class GasServiceRecordController extends Controller
{
    public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasServiceRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasServiceRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasServiceRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => auth()->user()->id,
        ]);
    }

    public function show(GasServiceRecord $gsr){
        $user_id = auth()->user()->id;
        $gsr->load(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
        $form = JobForm::find($gsr->job_form_id);
        $record = $form->slug;

        if(empty($gsr->certificate_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastCertificate = GasServiceRecord::where('job_form_id', $form->id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
            $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

            $cerSerial = $starting_form;
            if(!empty($lastCertificateNo)):
                preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                $cerSerial = (int) $certificateNumbers[1] + 1;
            endif;
            $certificateNumber = $prifix.str_pad($cerSerial, 6, '0', STR_PAD_LEFT);
            GasServiceRecord::where('id', $gsr->id)->update(['certificate_number' => $certificateNumber]);
        endif;

        $thePdf = $this->generatePdf($gsr->id);
        return view('app.new-records.'.$record.'.show', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'gsr' => $gsr,
            'gsra1' => GasServiceRecordAppliance::where('gas_service_record_id', $gsr->id)->where('appliance_serial', 1)->get()->first(),
            'signature' => $gsr->signature ? Storage::disk('public')->url($gsr->signature->filename) : '',
            'thePdf' => $thePdf
        ]);
    }

    public function store(Request $request){
        $gsr_id = $request->gsr_id;
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $submit_type = $request->submit_type;
        $gsr = GasServiceRecord::find($gsr_id);

        $red = '';
        $pdf = Storage::disk('public')->url('gsrvr/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$gsr->certificate_number.'.pdf');
        $message = '';
        $pdf = $this->generatePdf($gsr_id);
        if($submit_type == 2):
            $data = [];
            $data['status'] = 'Approved & Sent';

            GasServiceRecord::where('id', $gsr_id)->update($data);
            
            $email = $this->sendEmail($gsr_id, $job_form_id);
            $message = (!$email ? 'Gas Service Certificate has been approved. Email cannot be sent due to an invalid or empty email address.' : 'Gas Service Certificate has been approved and a copy of the certificate mailed to the customer');
        else:
            $data = [];
            $data['status'] = 'Approved';

            GasServiceRecord::where('id', $gsr_id)->update($data);
            $message = 'Homewoner Gas Service Certificate successfully approved.';
        endif;

        return response()->json(['msg' => $message, 'red' => route('company.dashboard'), 'pdf' => $pdf]);
    }

    public function sendEmail($gsr_id, $job_form_id){
        $user_id = auth()->user()->id;
        $gsr = GasServiceRecord::with('job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($gsr_id);
        $customerName = (isset($gsr->customer->full_name) && !empty($gsr->customer->full_name) ? $gsr->customer->full_name : '');
        $customerEmail = (isset($gsr->customer->contact->email) && !empty($gsr->customer->contact->email) ? $gsr->customer->contact->email : '');
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

            $fileName = $gsr->certificate_number.'.pdf';
            $attachmentFiles = [];
            if (Storage::disk('public')->exists('gsrvr/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName)):
                $attachmentFiles[] = [
                    "pathinfo" => 'gsrvr/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName,
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

    public function generatePdf($gsr_id) {
        $user_id = auth()->user()->id;
        $gsr = GasServiceRecord::with('customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company')->find($gsr_id);
        $gsra1 = GasServiceRecordAppliance::where('gas_service_record_id', $gsr->id)->where('appliance_serial', 1)->get()->first();

        $logoPath = resource_path('images/gas_safe_register_dark.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $userSignBase64 = (isset($gsr->user->signature) && Storage::disk('public')->exists($gsr->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gsr->user->signature->filename)) : '');
        $signatureBase64 = ($gsr->signature && Storage::disk('public')->exists($gsr->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gsr->signature->filename)) : '');
        

        $report_title = 'Certificate of '.$gsr->certificate_number;
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
                                    $PDFHTML .= '<h1 class="text-white text-xl leading-none mt-0 mb-05">Service/Maintenance Record</h1>';
                                    $PDFHTML .= '<div class="text-white text-12px leading-1-3">';
                                        $PDFHTML .= 'This record can be used to document the outcomes of the checks and tests required by The Gas Safety (Installation and Use) Regulations. 
                                                    Some of the outcomes are as a result of visual inspection only and are recorded where appropriate. Unless specifically recorded no detailed 
                                                    inspection of the flue lining construction or integrity has been performed.
                                                    Registered Business/engineer details can be checked at www.gassaferegister.co.uk or by calling 0800 408 5500';
                                    $PDFHTML .= '</div>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col2 align-middle text-center">';
                                    $PDFHTML .= '<label class="text-white uppercase font-medium text-12px leading-none mb-2 inline-block">Certificate Number</label>';
                                    $PDFHTML .= '<div class="inline-block bg-white w-32 text-center rounded-none leading-28px h-35px font-medium text-primary">'.$gsr->certificate_number.'</div>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gsr->user->name) && !empty($gsr->user->name) ? $gsr->user->name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">GAS SAFE REG.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->user->company->gas_safe_registration_no) && !empty($gsr->user->company->gas_safe_registration_no) ? $gsr->user->company->gas_safe_registration_no : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">ID CARD NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->user->gas_safe_id_card) && !empty($gsr->user->gas_safe_id_card) ? $gsr->user->gas_safe_id_card : '').'</td>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->user->company->company_name) && !empty($gsr->user->company->company_name) ? $gsr->user->company->company_name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gsr->user->company->pdf_address) && !empty($gsr->user->company->pdf_address) ? $gsr->user->company->pdf_address : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">TEL NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->user->company->company_phone) && !empty($gsr->user->company->company_phone) ? $gsr->user->company->company_phone : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Email</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->user->company->company_email) && !empty($gsr->user->company->company_email) ? $gsr->user->company->company_email : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->job->property->occupant_name) && !empty($gsr->job->property->occupant_name) ? $gsr->job->property->occupant_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gsr->job->property->pdf_address) && !empty($gsr->job->property->pdf_address) ? $gsr->job->property->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->job->property->postal_code) && !empty($gsr->job->property->postal_code) ? $gsr->job->property->postal_code : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->customer->full_name) && !empty($gsr->customer->full_name) ? $gsr->customer->full_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px leading-none align-middle">'.(isset($gsr->customer->company_name) && !empty($gsr->customer->company_name) ? $gsr->customer->company_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gsr->customer->pdf_address) && !empty($gsr->customer->pdf_address) ? $gsr->customer->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gsr->customer->postal_code) && !empty($gsr->customer->postal_code) ? $gsr->customer->postal_code : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                        $PDFHTML .= '</tbody>';
                                    $PDFHTML .= '</table>';
                                $PDFHTML .= '</td>';
                            $PDFHTML .= '</tr>';
                        $PDFHTML .= '</tbody>';
                    $PDFHTML .= '</table>';
                $PDFHTML .= '</div>';

                $PDFHTML .= '<table class="p-0 border-none mt-1-5">';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="w-half pr-1 pl-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">';
                                                $PDFHTML .= 'Appliance Details';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Location';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Model';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Make';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Type';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Serial No.';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'GC No.';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->location->name) && !empty($gsra1->location->name) ? $gsra1->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->model) && !empty($gsra1->model) ? $gsra1->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->make->name) && !empty($gsra1->make->name) ? $gsra1->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->type->name) && !empty($gsra1->type->name) ? $gsra1->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->serial_no) && !empty($gsra1->serial_no) ? $gsra1->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->gc_no) && !empty($gsra1->gc_no) ? $gsra1->gc_no : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-half pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">';
                                                $PDFHTML .= 'Electronic Combustion Gas Analyser Readings';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="3" class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Initial (low) ECGA reading';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th colspan="3" class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Final (high) ECGA reading';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gsra1->low_analyser_ratio) && !empty($gsra1->low_analyser_ratio) ? $gsra1->low_analyser_ratio.' Ratio' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gsra1->low_co) && !empty($gsra1->low_co) ? $gsra1->low_co.' CO (PPM)' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gsra1->low_co2) && !empty($gsra1->low_co2) ? $gsra1->low_co2.' CO<sub>2</sub> (%)' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gsra1->high_analyser_ratio) && !empty($gsra1->high_analyser_ratio) ? $gsra1->high_analyser_ratio.' Ratio' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gsra1->high_co) && !empty($gsra1->high_co) ? $gsra1->high_co.' CO (PPM)' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gsra1->high_co2) && !empty($gsra1->high_co2) ? $gsra1->high_co2.' CO<sub>2</sub> (%)' : '').'</td>';
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
                            $PDFHTML .= '<th colspan="5" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">';
                                $PDFHTML .= 'Installation Details';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Operating Pressure';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Rented Accommodation';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Type of Work Carried Out';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'If a gas test has been carried out, was this a pass or fail';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Is electrical bonding (where required satisfactory)';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->opt_pressure) && !empty($gsra1->opt_pressure) ? $gsra1->opt_pressure : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->rented_accommodation) && !empty($gsra1->rented_accommodation) ? $gsra1->rented_accommodation : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->type_of_work_carried_out) && !empty($gsra1->type_of_work_carried_out) ? $gsra1->type_of_work_carried_out : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->test_carried_out) && !empty($gsra1->test_carried_out) ? $gsra1->test_carried_out : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gsra1->is_electricial_bonding) && !empty($gsra1->is_electricial_bonding) ? $gsra1->is_electricial_bonding : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="p-0 border-none mt-1-5">';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="w-half pr-1 pl-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="3" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">';
                                                $PDFHTML .= 'Appliance Checks';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">';
                                                $PDFHTML .= 'Check';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">';
                                                $PDFHTML .= 'Status';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">';
                                                $PDFHTML .= 'Defects found / remedial action taken';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Heat Exchanger</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->heat_exchanger) && !empty($gsra1->heat_exchanger) ? $gsra1->heat_exchanger : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->heat_exchanger_detail) && !empty($gsra1->heat_exchanger_detail) ? $gsra1->heat_exchanger_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Burner / injectors</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->burner_injectors) && !empty($gsra1->burner_injectors) ? $gsra1->burner_injectors : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->burner_injectors_detail) && !empty($gsra1->burner_injectors_detail) ? $gsra1->burner_injectors_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Flame Picture</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->flame_picture) && !empty($gsra1->flame_picture) ? $gsra1->flame_picture : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->flame_picture_detail) && !empty($gsra1->flame_picture_detail) ? $gsra1->flame_picture_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Ignition</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->ignition) && !empty($gsra1->ignition) ? $gsra1->ignition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->ignition_detail) && !empty($gsra1->ignition_detail) ? $gsra1->ignition_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Electrical Connection</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->electrics) && !empty($gsra1->electrics) ? $gsra1->electrics : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->electrics_detail) && !empty($gsra1->electrics_detail) ? $gsra1->electrics_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Appliance / System Controls</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->controls) && !empty($gsra1->controls) ? $gsra1->controls : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->controls_detail) && !empty($gsra1->controls_detail) ? $gsra1->controls_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Leaks gas / water</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->leak_gas_water) && !empty($gsra1->leak_gas_water) ? $gsra1->leak_gas_water : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->leak_gas_water_detail) && !empty($gsra1->leak_gas_water_detail) ? $gsra1->leak_gas_water_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Seals (appliance case etc.)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->seals) && !empty($gsra1->seals) ? $gsra1->seals : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->seals_detail) && !empty($gsra1->seals_detail) ? $gsra1->seals_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Pipework</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->pipework) && !empty($gsra1->pipework) ? $gsra1->pipework : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->pipework_detail) && !empty($gsra1->pipework_detail) ? $gsra1->pipework_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Fans</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->fans) && !empty($gsra1->fans) ? $gsra1->fans : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->fans_detail) && !empty($gsra1->fans_detail) ? $gsra1->fans_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Fireplace catchment space</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->fireplace) && !empty($gsra1->fireplace) ? $gsra1->fireplace : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->fireplace_detail) && !empty($gsra1->fireplace_detail) ? $gsra1->fireplace_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Closure Plate & PRS10 Tape</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->closure_plate) && !empty($gsra1->closure_plate) ? $gsra1->closure_plate : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->closure_plate_detail) && !empty($gsra1->closure_plate_detail) ? $gsra1->closure_plate_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Allowable Location</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->allowable_location) && !empty($gsra1->allowable_location) ? $gsra1->allowable_location : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->fireplace_allowable_location_detail) && !empty($gsra1->fireplace_allowable_location_detail) ? $gsra1->fireplace_allowable_location_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Boiler Ratio</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->boiler_ratio) && !empty($gsra1->boiler_ratio) ? $gsra1->boiler_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->boiler_ratio_detail) && !empty($gsra1->boiler_ratio_detail) ? $gsra1->boiler_ratio_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Stability</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->stability) && !empty($gsra1->stability) ? $gsra1->stability : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->stability_detail) && !empty($gsra1->stability_detail) ? $gsra1->stability_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Return air / Plenum</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->return_air_ple) && !empty($gsra1->return_air_ple) ? $gsra1->return_air_ple : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->return_air_ple_detail) && !empty($gsra1->return_air_ple_detail) ? $gsra1->return_air_ple_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-half pr-1 pl-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="3" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">';
                                                $PDFHTML .= 'Safety Checks';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">';
                                                $PDFHTML .= 'Check';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">';
                                                $PDFHTML .= 'Status';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">';
                                                $PDFHTML .= 'Defects found / remedial action taken';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Ventilation</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->ventillation) && !empty($gsra1->ventillation) ? $gsra1->ventillation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->ventillation_detail) && !empty($gsra1->ventillation_detail) ? $gsra1->ventillation_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Flue Termination</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->flue_termination) && !empty($gsra1->flue_termination) ? $gsra1->flue_termination : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->flue_termination_detail) && !empty($gsra1->flue_termination_detail) ? $gsra1->flue_termination_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Smoke Pellet Flue Flow Test</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->smoke_pellet_flue_flow) && !empty($gsra1->smoke_pellet_flue_flow) ? $gsra1->smoke_pellet_flue_flow : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->smoke_pellet_flue_flow_detail) && !empty($gsra1->smoke_pellet_flue_flow_detail) ? $gsra1->smoke_pellet_flue_flow_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Smoke Match Spillage Test</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->smoke_pellet_spillage) && !empty($gsra1->smoke_pellet_spillage) ? $gsra1->smoke_pellet_spillage : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->smoke_pellet_spillage_detail) && !empty($gsra1->smoke_pellet_spillage_detail) ? $gsra1->smoke_pellet_spillage_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Working Pressure</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->working_pressure) && !empty($gsra1->working_pressure) ? $gsra1->working_pressure : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->working_pressure_detail) && !empty($gsra1->working_pressure_detail) ? $gsra1->working_pressure_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Safety Device</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->savety_devices) && !empty($gsra1->savety_devices) ? $gsra1->savety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->savety_devices_detail) && !empty($gsra1->savety_devices_detail) ? $gsra1->savety_devices_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Gas Tightness Test</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->gas_tightness) && !empty($gsra1->gas_tightness) ? $gsra1->gas_tightness : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->gas_tightness_detail) && !empty($gsra1->gas_tightness_detail) ? $gsra1->gas_tightness_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Expansion vessel checked / rech rged</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->expansion_vassel_checked) && !empty($gsra1->expansion_vassel_checked) ? $gsra1->expansion_vassel_checked : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->expansion_vassel_checked_detail) && !empty($gsra1->expansion_vassel_checked_detail) ? $gsra1->expansion_vassel_checked_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Other (regulations etc.)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gsra1->other_regulations) && !empty($gsra1->other_regulations) ? $gsra1->other_regulations : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gsra1->other_regulations_detail) && !empty($gsra1->other_regulations_detail) ? $gsra1->other_regulations_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">';
                                                $PDFHTML .= 'Necessary remedial work required';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 align-top h-60px">'.(isset($gsra1->work_required_note) && !empty($gsra1->work_required_note) ? $gsra1->work_required_note : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';

                            $PDFHTML .= '</td>';
                        $PDFHTML .= '<tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="p-0 border-none mt-1-5">';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="w-col5 pr-1 pl-0 pb-0 pt-0 align-top">';
                            $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                $PDFHTML .= '<tbody>';
                                    $PDFHTML .= '<tr>';
                                        $PDFHTML .= '<td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-10px uppercase px-2 py-05 leading-none align-middle">Is the installation and appliance safe to use?</td>';
                                        $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-10px w-130px leading-none align-middle">'.(isset($gsra1->is_safe_to_use) && !empty($gsra1->is_safe_to_use) ? $gsra1->is_safe_to_use : '').'</td>';
                                    $PDFHTML .= '</tr>';
                                $PDFHTML .= '</tbody>';
                            $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col7 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-10px uppercase px-2 py-05 leading-none align-middle">Has the installation been carried out to the relevant standard/manufacturer\'s instructions? </td>';
                                            $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-10px w-130px leading-none align-middle">'.(isset($gsra1->instruction_followed) && !empty($gsra1->instruction_followed) ? $gsra1->instruction_followed : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                        $PDFHTML .= '<tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $inspectionDeate = (isset($gsr->inspection_date) && !empty($gsr->inspection_date) ? date('d-m-Y', strtotime($gsr->inspection_date)) : date('d-m-Y'));
                $nextInspectionDate = (isset($gsr->next_inspection_date) && !empty($gsr->next_inspection_date) ? date('d-m-Y', strtotime($gsr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                
                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="3" class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">';
                                $PDFHTML .= 'SIGNATURES';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="w-41-percent p-0 border-primary align-top border-b-0">';
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gsr->user->name) && !empty($gsr->user->name) ? $gsr->user->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Date of Issue</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.$inspectionDeate.'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-41-percent p-0 border-primary align-top border-b-0">';
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gsr->relation->name) && !empty($gsr->relation->name) ? $gsr->relation->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gsr->received_by) && !empty($gsr->received_by) ? $gsr->received_by : (isset($gsr->customer->full_name) && !empty($gsr->customer->full_name) ? $gsr->customer->full_name : '')).'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-20-percent p-0 border-primary border-b-0 align-middle bg-light-2 text-primary text-center px-3">';
                                $PDFHTML .= '<div class="text-primary uppercase font-medium text-12px leading-none mb-1 px-2">Next Inspection Date</div>';
                                $PDFHTML .= '<div class="inline-block bg-white w-col9 text-center rounded-none h-30px text-12px font-medium">'.$nextInspectionDate.'</div>';
                            $PDFHTML .= '</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

            $PDFHTML .= '</body>';
        $PDFHTML .= '</html>';


        $fileName = $gsr->certificate_number.'.pdf';
        if (Storage::disk('public')->exists('gserv/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('gserv/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName);
        }
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('gserv/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('gserv/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName);
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
            $gasServiceRecord = GasServiceRecord::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            $this->checkAndUpdateRecordHistory($gasServiceRecord->id);

            if(!empty($appliances) && $gasServiceRecord->id):
                $appliance_serial = (isset($appliances->appliance_serial) && $appliances->appliance_serial > 0 ? $appliances->appliance_serial : 1);
                $gasAppliance = GasServiceRecordAppliance::updateOrCreate(['gas_service_record_id' => $gasServiceRecord->id, 'appliance_serial' => $appliance_serial], [
                    'gas_service_record_id' => $gasServiceRecord->id,
                    'appliance_serial' => $appliance_serial,
                    
                    'appliance_location_id' => (isset($appliances->appliance_location_id) && !empty($appliances->appliance_location_id) ? $appliances->appliance_location_id : null),
                    'boiler_brand_id' => (isset($appliances->boiler_brand_id) && !empty($appliances->boiler_brand_id) ? $appliances->boiler_brand_id : null),
                    'model' => (isset($appliances->model) && !empty($appliances->model) ? $appliances->model : null),
                    'appliance_type_id' => (isset($appliances->appliance_type_id) && !empty($appliances->appliance_type_id) ? $appliances->appliance_type_id : null),
                    'serial_no' => (isset($appliances->serial_no) && !empty($appliances->serial_no) ? $appliances->serial_no : null),
                    'gc_no' => (isset($appliances->gc_no) && !empty($appliances->gc_no) ? $appliances->gc_no : null),
                    
                    'opt_pressure' => (isset($appliances->opt_pressure) && !empty($appliances->opt_pressure) ? $appliances->opt_pressure : null),
                    'rented_accommodation' => (isset($appliances->rented_accommodation) && !empty($appliances->rented_accommodation) ? $appliances->rented_accommodation : null),
                    'type_of_work_carried_out' => (isset($appliances->type_of_work_carried_out) && !empty($appliances->type_of_work_carried_out) ? $appliances->type_of_work_carried_out : null),
                    'test_carried_out' => (isset($appliances->test_carried_out) && !empty($appliances->test_carried_out) ? $appliances->test_carried_out : null),
                    'is_electricial_bonding' => (isset($appliances->is_electricial_bonding) && !empty($appliances->is_electricial_bonding) ? $appliances->is_electricial_bonding : null),
                    'low_analyser_ratio' => (isset($appliances->low_analyser_ratio) && !empty($appliances->low_analyser_ratio) ? $appliances->low_analyser_ratio : null),
                    'low_co' => (isset($appliances->low_co) && !empty($appliances->low_co) ? $appliances->low_co : null),
                    'low_co2' => (isset($appliances->low_co2) && !empty($appliances->low_co2) ? $appliances->low_co2 : null),
                    'high_analyser_ratio' => (isset($appliances->high_analyser_ratio) && !empty($appliances->high_analyser_ratio) ? $appliances->high_analyser_ratio : null),
                    'high_co' => (isset($appliances->high_co) && !empty($appliances->high_co) ? $appliances->high_co : null),
                    'high_co2' => (isset($appliances->high_co2) && !empty($appliances->high_co2) ? $appliances->high_co2 : null),

                    'heat_exchanger' => (isset($appliances->heat_exchanger) && !empty($appliances->heat_exchanger) ? $appliances->heat_exchanger : null),
                    'heat_exchanger_detail' => (isset($appliances->heat_exchanger_detail) && !empty($appliances->heat_exchanger_detail) ? $appliances->heat_exchanger_detail : null),
                    'burner_injectors' => (isset($appliances->burner_injectors) && !empty($appliances->burner_injectors) ? $appliances->burner_injectors : null),
                    'burner_injectors_detail' => (isset($appliances->burner_injectors_detail) && !empty($appliances->burner_injectors_detail) ? $appliances->burner_injectors_detail : null),
                    'flame_picture' => (isset($appliances->flame_picture) && !empty($appliances->flame_picture) ? $appliances->flame_picture : null),
                    'flame_picture_detail' => (isset($appliances->flame_picture_detail) && !empty($appliances->flame_picture_detail) ? $appliances->flame_picture_detail : null),
                    'ignition' => (isset($appliances->ignition) && !empty($appliances->ignition) ? $appliances->ignition : null),
                    'ignition_detail' => (isset($appliances->ignition_detail) && !empty($appliances->ignition_detail) ? $appliances->ignition_detail : null),
                    'electrics' => (isset($appliances->electrics) && !empty($appliances->electrics) ? $appliances->electrics : null),
                    'electrics_detail' => (isset($appliances->electrics_detail) && !empty($appliances->electrics_detail) ? $appliances->electrics_detail : null),
                    'controls' => (isset($appliances->controls) && !empty($appliances->controls) ? $appliances->controls : null),
                    'controls_detail' => (isset($appliances->controls_detail) && !empty($appliances->controls_detail) ? $appliances->controls_detail : null),
                    'leak_gas_water' => (isset($appliances->leak_gas_water) && !empty($appliances->leak_gas_water) ? $appliances->leak_gas_water : null),
                    'leak_gas_water_detail' => (isset($appliances->leak_gas_water_detail) && !empty($appliances->leak_gas_water_detail) ? $appliances->leak_gas_water_detail : null),
                    'seals' => (isset($appliances->seals) && !empty($appliances->seals) ? $appliances->seals : null),
                    'seals_detail' => (isset($appliances->seals_detail) && !empty($appliances->seals_detail) ? $appliances->seals_detail : null),
                    'pipework' => (isset($appliances->pipework) && !empty($appliances->pipework) ? $appliances->pipework : null),
                    'pipework_detail' => (isset($appliances->pipework_detail) && !empty($appliances->pipework_detail) ? $appliances->pipework_detail : null),
                    'fans' => (isset($appliances->fans) && !empty($appliances->fans) ? $appliances->fans : null),
                    'fans_detail' => (isset($appliances->fans_detail) && !empty($appliances->fans_detail) ? $appliances->fans_detail : null),
                    'fireplace' => (isset($appliances->fireplace) && !empty($appliances->fireplace) ? $appliances->fireplace : null),
                    'fireplace_detail' => (isset($appliances->fireplace_detail) && !empty($appliances->fireplace_detail) ? $appliances->fireplace_detail : null),
                    'closure_plate' => (isset($appliances->closure_plate) && !empty($appliances->closure_plate) ? $appliances->closure_plate : null),
                    'closure_plate_detail' => (isset($appliances->closure_plate_detail) && !empty($appliances->closure_plate_detail) ? $appliances->closure_plate_detail : null),
                    'allowable_location' => (isset($appliances->allowable_location) && !empty($appliances->allowable_location) ? $appliances->allowable_location : null),
                    'allowable_location_detail' => (isset($appliances->allowable_location_detail) && !empty($appliances->allowable_location_detail) ? $appliances->allowable_location_detail : null),
                    'boiler_ratio' => (isset($appliances->boiler_ratio) && !empty($appliances->boiler_ratio) ? $appliances->boiler_ratio : null),
                    'boiler_ratio_detail' => (isset($appliances->boiler_ratio_detail) && !empty($appliances->boiler_ratio_detail) ? $appliances->boiler_ratio_detail : null),
                    'stability' => (isset($appliances->stability) && !empty($appliances->stability) ? $appliances->stability : null),
                    'stability_detail' => (isset($appliances->stability_detail) && !empty($appliances->stability_detail) ? $appliances->stability_detail : null),
                    'return_air_ple' => (isset($appliances->return_air_ple) && !empty($appliances->return_air_ple) ? $appliances->return_air_ple : null),
                    'return_air_ple_detail' => (isset($appliances->return_air_ple_detail) && !empty($appliances->return_air_ple_detail) ? $appliances->return_air_ple_detail : null),
                    'ventillation' => (isset($appliances->ventillation) && !empty($appliances->ventillation) ? $appliances->ventillation : null),
                    'ventillation_detail' => (isset($appliances->ventillation_detail) && !empty($appliances->ventillation_detail) ? $appliances->ventillation_detail : null),
                    'flue_termination' => (isset($appliances->flue_termination) && !empty($appliances->flue_termination) ? $appliances->flue_termination : null),
                    'flue_termination_detail' => (isset($appliances->flue_termination_detail) && !empty($appliances->flue_termination_detail) ? $appliances->flue_termination_detail : null),
                    'smoke_pellet_flue_flow' => (isset($appliances->smoke_pellet_flue_flow) && !empty($appliances->smoke_pellet_flue_flow) ? $appliances->smoke_pellet_flue_flow : null),
                    'smoke_pellet_flue_flow_detail' => (isset($appliances->smoke_pellet_flue_flow_detail) && !empty($appliances->smoke_pellet_flue_flow_detail) ? $appliances->smoke_pellet_flue_flow_detail : null),
                    'smoke_pellet_spillage' => (isset($appliances->smoke_pellet_spillage) && !empty($appliances->smoke_pellet_spillage) ? $appliances->smoke_pellet_spillage : null),
                    'smoke_pellet_spillage_detail' => (isset($appliances->smoke_pellet_spillage_detail) && !empty($appliances->smoke_pellet_spillage_detail) ? $appliances->smoke_pellet_spillage_detail : null),
                    'working_pressure' => (isset($appliances->working_pressure) && !empty($appliances->working_pressure) ? $appliances->working_pressure : null),
                    'working_pressure_detail' => (isset($appliances->working_pressure_detail) && !empty($appliances->working_pressure_detail) ? $appliances->working_pressure_detail : null),
                    'savety_devices' => (isset($appliances->savety_devices) && !empty($appliances->savety_devices) ? $appliances->savety_devices : null),
                    'savety_devices_detail' => (isset($appliances->savety_devices_detail) && !empty($appliances->savety_devices_detail) ? $appliances->savety_devices_detail : null),
                    'gas_tightness' => (isset($appliances->gas_tightness) && !empty($appliances->gas_tightness) ? $appliances->gas_tightness : null),
                    'gas_tightness_detail' => (isset($appliances->gas_tightness_detail) && !empty($appliances->gas_tightness_detail) ? $appliances->gas_tightness_detail : null),
                    'expansion_vassel_checked' => (isset($appliances->expansion_vassel_checked) && !empty($appliances->expansion_vassel_checked) ? $appliances->expansion_vassel_checked : null),
                    'expansion_vassel_checked_detail' => (isset($appliances->expansion_vassel_checked_detail) && !empty($appliances->expansion_vassel_checked_detail) ? $appliances->expansion_vassel_checked_detail : null),
                    'other_regulations' => (isset($appliances->other_regulations) && !empty($appliances->other_regulations) ? $appliances->other_regulations : null),
                    'other_regulations_detail' => (isset($appliances->other_regulations_detail) && !empty($appliances->other_regulations_detail) ? $appliances->other_regulations_detail : null),
                    'is_safe_to_use' => (isset($appliances->is_safe_to_use) && !empty($appliances->is_safe_to_use) ? $appliances->is_safe_to_use : null),
                    'instruction_followed' => (isset($appliances->instruction_followed) && !empty($appliances->instruction_followed) ? $appliances->instruction_followed : null),
                    'work_required_note' => (isset($appliances->work_required_note) && !empty($appliances->work_required_note) ? $appliances->work_required_note : null),
                    
                    'updated_by' => $user_id,
                ]);
            endif;

            if($request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $gasServiceRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    $signature = new Signature();
                    $signature->model_type = GasServiceRecord::class;
                    $signature->model_id = $gasServiceRecord->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([request()->ip()]);
                    $signature->save();
                endif;
            endif;

            return response()->json(['msg' => 'Certificate successfully created.', 'red' => route('new.records.gas.service.show', $gasServiceRecord->id)], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function editReady(Request $request){
        $record_id = $request->record_id;

        $record = GasServiceRecord::with('customer', 'customer.contact', 'job', 'job.property')->find($record_id);
        $appliances = GasServiceRecordAppliance::where('gas_service_record_id', $record_id)->orderBy('id', 'desc')->get()->first();

        $applianceName = (isset($appliances->make->name) && !empty($appliances->make->name) ? $appliances->make->name.' ' : '');
        $applianceName .= (isset($appliances->type->name) && !empty($appliances->type->name) ? $appliances->type->name.' ' : '');
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
                'gas_service_record_id' => $appliances->gas_wargas_service_record_idning_notice_id,
                'appliance_serial' => $appliances->appliance_serial,
                
                'appliance_location_id' => (isset($appliances->appliance_location_id) && !empty($appliances->appliance_location_id) ? $appliances->appliance_location_id : ''),
                'boiler_brand_id' => (isset($appliances->boiler_brand_id) && !empty($appliances->boiler_brand_id) ? $appliances->boiler_brand_id : ''),
                'model' => (isset($appliances->model) && !empty($appliances->model) ? $appliances->model : ''),
                'appliance_type_id' => (isset($appliances->appliance_type_id) && !empty($appliances->appliance_type_id) ? $appliances->appliance_type_id : ''),
                'serial_no' => (isset($appliances->serial_no) && !empty($appliances->serial_no) ? $appliances->serial_no : ''),
                'gc_no' => (isset($appliances->gc_no) && !empty($appliances->gc_no) ? $appliances->gc_no : ''),
                
                'opt_pressure' => (isset($appliances->opt_pressure) && !empty($appliances->opt_pressure) ? $appliances->opt_pressure : ''),
                'rented_accommodation' => (isset($appliances->rented_accommodation) && !empty($appliances->rented_accommodation) ? $appliances->rented_accommodation : ''),
                'type_of_work_carried_out' => (isset($appliances->type_of_work_carried_out) && !empty($appliances->type_of_work_carried_out) ? $appliances->type_of_work_carried_out : ''),
                'test_carried_out' => (isset($appliances->test_carried_out) && !empty($appliances->test_carried_out) ? $appliances->test_carried_out : ''),
                'is_electricial_bonding' => (isset($appliances->is_electricial_bonding) && !empty($appliances->is_electricial_bonding) ? $appliances->is_electricial_bonding : ''),
                'low_analyser_ratio' => (isset($appliances->low_analyser_ratio) && !empty($appliances->low_analyser_ratio) ? $appliances->low_analyser_ratio : ''),
                'low_co' => (isset($appliances->low_co) && !empty($appliances->low_co) ? $appliances->low_co : ''),
                'low_co2' => (isset($appliances->low_co2) && !empty($appliances->low_co2) ? $appliances->low_co2 : ''),
                'high_analyser_ratio' => (isset($appliances->high_analyser_ratio) && !empty($appliances->high_analyser_ratio) ? $appliances->high_analyser_ratio : ''),
                'high_co' => (isset($appliances->high_co) && !empty($appliances->high_co) ? $appliances->high_co : ''),
                'high_co2' => (isset($appliances->high_co2) && !empty($appliances->high_co2) ? $appliances->high_co2 : ''),

                'heat_exchanger' => (isset($appliances->heat_exchanger) && !empty($appliances->heat_exchanger) ? $appliances->heat_exchanger : ''),
                'heat_exchanger_detail' => (isset($appliances->heat_exchanger_detail) && !empty($appliances->heat_exchanger_detail) ? $appliances->heat_exchanger_detail : ''),
                'burner_injectors' => (isset($appliances->burner_injectors) && !empty($appliances->burner_injectors) ? $appliances->burner_injectors : ''),
                'burner_injectors_detail' => (isset($appliances->burner_injectors_detail) && !empty($appliances->burner_injectors_detail) ? $appliances->burner_injectors_detail : ''),
                'flame_picture' => (isset($appliances->flame_picture) && !empty($appliances->flame_picture) ? $appliances->flame_picture : ''),
                'flame_picture_detail' => (isset($appliances->flame_picture_detail) && !empty($appliances->flame_picture_detail) ? $appliances->flame_picture_detail : ''),
                'ignition' => (isset($appliances->ignition) && !empty($appliances->ignition) ? $appliances->ignition : ''),
                'ignition_detail' => (isset($appliances->ignition_detail) && !empty($appliances->ignition_detail) ? $appliances->ignition_detail : ''),
                'electrics' => (isset($appliances->electrics) && !empty($appliances->electrics) ? $appliances->electrics : ''),
                'electrics_detail' => (isset($appliances->electrics_detail) && !empty($appliances->electrics_detail) ? $appliances->electrics_detail : ''),
                'controls' => (isset($appliances->controls) && !empty($appliances->controls) ? $appliances->controls : ''),
                'controls_detail' => (isset($appliances->controls_detail) && !empty($appliances->controls_detail) ? $appliances->controls_detail : ''),
                'leak_gas_water' => (isset($appliances->leak_gas_water) && !empty($appliances->leak_gas_water) ? $appliances->leak_gas_water : ''),
                'leak_gas_water_detail' => (isset($appliances->leak_gas_water_detail) && !empty($appliances->leak_gas_water_detail) ? $appliances->leak_gas_water_detail : ''),
                'seals' => (isset($appliances->seals) && !empty($appliances->seals) ? $appliances->seals : ''),
                'seals_detail' => (isset($appliances->seals_detail) && !empty($appliances->seals_detail) ? $appliances->seals_detail : ''),
                'pipework' => (isset($appliances->pipework) && !empty($appliances->pipework) ? $appliances->pipework : ''),
                'pipework_detail' => (isset($appliances->pipework_detail) && !empty($appliances->pipework_detail) ? $appliances->pipework_detail : ''),
                'fans' => (isset($appliances->fans) && !empty($appliances->fans) ? $appliances->fans : ''),
                'fans_detail' => (isset($appliances->fans_detail) && !empty($appliances->fans_detail) ? $appliances->fans_detail : ''),
                'fireplace' => (isset($appliances->fireplace) && !empty($appliances->fireplace) ? $appliances->fireplace : ''),
                'fireplace_detail' => (isset($appliances->fireplace_detail) && !empty($appliances->fireplace_detail) ? $appliances->fireplace_detail : ''),
                'closure_plate' => (isset($appliances->closure_plate) && !empty($appliances->closure_plate) ? $appliances->closure_plate : ''),
                'closure_plate_detail' => (isset($appliances->closure_plate_detail) && !empty($appliances->closure_plate_detail) ? $appliances->closure_plate_detail : ''),
                'allowable_location' => (isset($appliances->allowable_location) && !empty($appliances->allowable_location) ? $appliances->allowable_location : ''),
                'allowable_location_detail' => (isset($appliances->allowable_location_detail) && !empty($appliances->allowable_location_detail) ? $appliances->allowable_location_detail : ''),
                'boiler_ratio' => (isset($appliances->boiler_ratio) && !empty($appliances->boiler_ratio) ? $appliances->boiler_ratio : ''),
                'boiler_ratio_detail' => (isset($appliances->boiler_ratio_detail) && !empty($appliances->boiler_ratio_detail) ? $appliances->boiler_ratio_detail : ''),
                'stability' => (isset($appliances->stability) && !empty($appliances->stability) ? $appliances->stability : ''),
                'stability_detail' => (isset($appliances->stability_detail) && !empty($appliances->stability_detail) ? $appliances->stability_detail : ''),
                'return_air_ple' => (isset($appliances->return_air_ple) && !empty($appliances->return_air_ple) ? $appliances->return_air_ple : ''),
                'return_air_ple_detail' => (isset($appliances->return_air_ple_detail) && !empty($appliances->return_air_ple_detail) ? $appliances->return_air_ple_detail : ''),
                'ventillation' => (isset($appliances->ventillation) && !empty($appliances->ventillation) ? $appliances->ventillation : ''),
                'ventillation_detail' => (isset($appliances->ventillation_detail) && !empty($appliances->ventillation_detail) ? $appliances->ventillation_detail : ''),
                'flue_termination' => (isset($appliances->flue_termination) && !empty($appliances->flue_termination) ? $appliances->flue_termination : ''),
                'flue_termination_detail' => (isset($appliances->flue_termination_detail) && !empty($appliances->flue_termination_detail) ? $appliances->flue_termination_detail : ''),
                'smoke_pellet_flue_flow' => (isset($appliances->smoke_pellet_flue_flow) && !empty($appliances->smoke_pellet_flue_flow) ? $appliances->smoke_pellet_flue_flow : ''),
                'smoke_pellet_flue_flow_detail' => (isset($appliances->smoke_pellet_flue_flow_detail) && !empty($appliances->smoke_pellet_flue_flow_detail) ? $appliances->smoke_pellet_flue_flow_detail : ''),
                'smoke_pellet_spillage' => (isset($appliances->smoke_pellet_spillage) && !empty($appliances->smoke_pellet_spillage) ? $appliances->smoke_pellet_spillage : ''),
                'smoke_pellet_spillage_detail' => (isset($appliances->smoke_pellet_spillage_detail) && !empty($appliances->smoke_pellet_spillage_detail) ? $appliances->smoke_pellet_spillage_detail : ''),
                'working_pressure' => (isset($appliances->working_pressure) && !empty($appliances->working_pressure) ? $appliances->working_pressure : ''),
                'working_pressure_detail' => (isset($appliances->working_pressure_detail) && !empty($appliances->working_pressure_detail) ? $appliances->working_pressure_detail : ''),
                'savety_devices' => (isset($appliances->savety_devices) && !empty($appliances->savety_devices) ? $appliances->savety_devices : ''),
                'savety_devices_detail' => (isset($appliances->savety_devices_detail) && !empty($appliances->savety_devices_detail) ? $appliances->savety_devices_detail : ''),
                'gas_tightness' => (isset($appliances->gas_tightness) && !empty($appliances->gas_tightness) ? $appliances->gas_tightness : ''),
                'gas_tightness_detail' => (isset($appliances->gas_tightness_detail) && !empty($appliances->gas_tightness_detail) ? $appliances->gas_tightness_detail : ''),
                'expansion_vassel_checked' => (isset($appliances->expansion_vassel_checked) && !empty($appliances->expansion_vassel_checked) ? $appliances->expansion_vassel_checked : ''),
                'expansion_vassel_checked_detail' => (isset($appliances->expansion_vassel_checked_detail) && !empty($appliances->expansion_vassel_checked_detail) ? $appliances->expansion_vassel_checked_detail : ''),
                'other_regulations' => (isset($appliances->other_regulations) && !empty($appliances->other_regulations) ? $appliances->other_regulations : ''),
                'other_regulations_detail' => (isset($appliances->other_regulations_detail) && !empty($appliances->other_regulations_detail) ? $appliances->other_regulations_detail : ''),
                'is_safe_to_use' => (isset($appliances->is_safe_to_use) && !empty($appliances->is_safe_to_use) ? $appliances->is_safe_to_use : ''),
                'instruction_followed' => (isset($appliances->instruction_followed) && !empty($appliances->instruction_followed) ? $appliances->instruction_followed : ''),
                'work_required_note' => (isset($appliances->work_required_note) && !empty($appliances->work_required_note) ? $appliances->work_required_note : ''),
            ]
        ];

        return response()->json(['row' => $data, 'red' => route('new.records.create', $record->job_form_id)], 200);
    }
}
