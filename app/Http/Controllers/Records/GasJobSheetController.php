<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasJobSheetRecord;
use App\Models\GasJobSheetRecordDetail;
use App\Models\GasJobSheetRecordDocument;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;
use Barryvdh\DomPDF\Facade\Pdf;

class GasJobSheetController extends Controller
{
    public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasJobSheetRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasJobSheetRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasJobSheetRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => auth()->user()->id,
        ]);
    }


    public function show(GasJobSheetRecord $gjsr){
        $user_id = auth()->user()->id;
        $gjsr->load(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
        $form = JobForm::find($gjsr->job_form_id);
        $record = $form->slug;

        if(empty($gjsr->certificate_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastCertificate = GasJobSheetRecord::where('job_form_id', $form->id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
            $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

            $cerSerial = $starting_form;
            if(!empty($lastCertificateNo)):
                preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                $cerSerial = (int) $certificateNumbers[1] + 1;
            endif;
            $certificateNumber = $prifix.str_pad($cerSerial, 6, '0', STR_PAD_LEFT);
            GasJobSheetRecord::where('id', $gjsr->id)->update(['certificate_number' => $certificateNumber]);
        endif;

        $thePdf = $this->generatePdf($gjsr->id);
        return view('app.new-records.'.$record.'.show', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'gjsr' => $gjsr,
            'gjsrd' => GasJobSheetRecordDetail::where('gas_job_sheet_record_id', $gjsr->id)->get()->first(),
            'gjsrdc' => GasJobSheetRecordDocument::where('gas_job_sheet_record_id', $gjsr->id)->get(),
            'signature' => $gjsr->signature ? Storage::disk('public')->url($gjsr->signature->filename) : '',
            'thePdf' => $thePdf
        ]);
    }

    public function store(Request $request){
        $gjsr_id = $request->gjsr_id;
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $submit_type = $request->submit_type;
        $gjsr = GasJobSheetRecord::find($gjsr_id);

        $red = '';
        $pdf = Storage::disk('public')->url('gjsr/'.$gjsr->customer_job_id.'/'.$gjsr->job_form_id.'/'.$gjsr->certificate_number.'.pdf');
        $message = '';
        $pdf = $this->generatePdf($gjsr_id);
        if($submit_type == 2):
            $data = [];
            $data['status'] = 'Approved & Sent';

            GasJobSheetRecord::where('id', $gjsr_id)->update($data);
            
            $email = $this->sendEmail($gjsr_id, $job_form_id);
            $message = (!$email ? 'Gas Job Sheet Certificate has been approved. Email cannot be sent due to an invalid or empty email address.' : 'Gas Job Sheet Certificate has been approved and a copy of the certificate mailed to the customer');
        else:
            $data = [];
            $data['status'] = 'Approved';

            GasJobSheetRecord::where('id', $gjsr_id)->update($data);
            $message = 'Gas Job Sheet Certificate successfully approved.';
        endif;

        return response()->json(['msg' => $message, 'red' => route('company.dashboard'), 'pdf' => $pdf]);
    }

    public function sendEmail($gjsr_id, $job_form_id){
        $user_id = auth()->user()->id;
        $gjsr = GasJobSheetRecord::with('job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($gjsr_id);
        $customerName = (isset($gjsr->customer->full_name) && !empty($gjsr->customer->full_name) ? $gjsr->customer->full_name : '');
        $customerEmail = (isset($gjsr->customer->contact->email) && !empty($gjsr->customer->contact->email) ? $gjsr->customer->contact->email : '');
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

            $fileName = $gjsr->certificate_number.'.pdf';
            $attachmentFiles = [];
            if (Storage::disk('public')->exists('gjsr/'.$gjsr->customer_job_id.'/'.$gjsr->job_form_id.'/'.$fileName)):
                $attachmentFiles[] = [
                    "pathinfo" => 'gjsr/'.$gjsr->customer_job_id.'/'.$gjsr->job_form_id.'/'.$fileName,
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

    public function generatePdf($gjsr_id) {
        $user_id = auth()->user()->id;
        $gjsr = GasJobSheetRecord::with('customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company')->find($gjsr_id);
        $gjsrd = GasJobSheetRecordDetail::where('gas_job_sheet_record_id', $gjsr->id)->get()->first();

        $logoPath = resource_path('images/gas_safe_register_dark.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $userSignBase64 = (isset($gjsr->user->signature) && Storage::disk('public')->exists($gjsr->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gjsr->user->signature->filename)) : '');
        $signatureBase64 = ($gjsr->signature && Storage::disk('public')->exists($gjsr->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gjsr->signature->filename)) : '');
        

        $report_title = 'Certificate of '.$gjsr->certificate_number;
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
                                    $PDFHTML .= '<h1 class="text-white text-2xl leading-none mt-0 mb-05">Job Sheet</h1>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col2 align-middle text-center">';
                                    $PDFHTML .= '<label class="text-white uppercase font-medium text-12px leading-none mb-2 inline-block">Certificate Number</label>';
                                    $PDFHTML .= '<div class="inline-block bg-white w-32 text-center rounded-none leading-28px h-35px font-medium text-primary">'.$gjsr->certificate_number.'</div>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gjsr->user->name) && !empty($gjsr->user->name) ? $gjsr->user->name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">GAS SAFE REG.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->user->company->gas_safe_registration_no) && !empty($gjsr->user->company->gas_safe_registration_no) ? $gjsr->user->company->gas_safe_registration_no : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">ID CARD NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->user->gas_safe_id_card) && !empty($gjsr->user->gas_safe_id_card) ? $gjsr->user->gas_safe_id_card : '').'</td>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->user->company->company_name) && !empty($gjsr->user->company->company_name) ? $gjsr->user->company->company_name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gjsr->user->company->pdf_address) && !empty($gjsr->user->company->pdf_address) ? $gjsr->user->company->pdf_address : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">TEL NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->user->company->company_phone) && !empty($gjsr->user->company->company_phone) ? $gjsr->user->company->company_phone : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Email</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->user->company->company_email) && !empty($gjsr->user->company->company_email) ? $gjsr->user->company->company_email : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->job->property->occupant_name) && !empty($gjsr->job->property->occupant_name) ? $gjsr->job->property->occupant_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gjsr->job->property->pdf_address) && !empty($gjsr->job->property->pdf_address) ? $gjsr->job->property->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->job->property->postal_code) && !empty($gjsr->job->property->postal_code) ? $gjsr->job->property->postal_code : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->customer->full_name) && !empty($gjsr->customer->full_name) ? $gjsr->customer->full_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->customer->company_name) && !empty($gjsr->customer->company_name) ? $gjsr->customer->company_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gjsr->customer->pdf_address) && !empty($gjsr->customer->pdf_address) ? $gjsr->customer->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gjsr->customer->postal_code) && !empty($gjsr->customer->postal_code) ? $gjsr->customer->postal_code : '').'</td>';
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
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">';
                                                $PDFHTML .= 'Job Notes';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top h-80px">'.(isset($gjsrd->job_note) && !empty($gjsrd->job_note) ? $gjsrd->job_note : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-half pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">';
                                                $PDFHTML .= 'Spares Required';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top h-80px">'.(isset($gjsrd->spares_required) && !empty($gjsrd->spares_required) ? $gjsrd->spares_required : '').'</td>';
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
                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">';
                                $PDFHTML .= 'Details';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="whitespace-nowrap border-primary bg-primary border-b border-b-sec text-white text-12px font-medium leading-1-2 px-2 py-05 text-left align-top w-col2">Job Ref</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top">'.(isset($gjsrd->job_ref) && !empty($gjsrd->job_ref) ? $gjsrd->job_ref : '').'</td>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="whitespace-nowrap border-primary bg-primary  border-b border-b-sec text-white text-12px font-medium leading-1-2 px-2 py-05 text-left align-top w-col2">Arrival Time</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top">'.(isset($gjsrd->arrival_time) && !empty($gjsrd->arrival_time) ? $gjsrd->arrival_time : '').'</td>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="whitespace-nowrap border-primary bg-primary  border-b border-b-sec text-white text-12px font-medium leading-1-2 px-2 py-05 text-left align-top w-col2">Departure Time</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top">'.(isset($gjsrd->departure_time) && !empty($gjsrd->departure_time) ? $gjsrd->departure_time : '').'</td>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="whitespace-nowrap border-primary bg-primary  border-b border-b-sec text-white text-12px font-medium leading-1-2 px-2 py-05 text-left align-top w-col2">Hours Used</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top">'.(isset($gjsrd->hours_used) && !empty($gjsrd->hours_used) ? $gjsrd->hours_used : '').'</td>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="whitespace-nowrap border-primary bg-primary  border-b border-b-sec text-white text-12px font-medium leading-1-2 px-2 py-05 text-left align-top w-col2">Awaiting Parts</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top">'.(isset($gjsrd->awaiting_parts) && !empty($gjsrd->awaiting_parts) ? $gjsrd->awaiting_parts : '').'</td>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="whitespace-nowrap border-primary bg-primary  border-b border-b-sec text-white text-12px font-medium leading-1-2 px-2 py-05 text-left align-top w-col2">Job Completed</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top">'.(isset($gjsrd->job_completed) && !empty($gjsrd->job_completed) ? $gjsrd->job_completed : '').'</td>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="whitespace-nowrap border-primary bg-primary  border-b border-b-sec text-white text-12px font-medium leading-1-2 px-2 py-05 text-left align-top w-col2">Date</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r align-top">'.(isset($gjsrd->date) && !empty($gjsrd->date) ? date('d-m-Y', strtotime($gjsrd->date)) : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $inspectionDeate = (isset($gjsr->inspection_date) && !empty($gjsr->inspection_date) ? date('d-m-Y', strtotime($gjsr->inspection_date)) : date('d-m-Y'));
                
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gjsr->user->name) && !empty($gjsr->user->name) ? $gjsr->user->name : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gjsr->relation->name) && !empty($gjsr->relation->name) ? $gjsr->relation->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gjsr->received_by) && !empty($gjsr->received_by) ? $gjsr->received_by : (isset($gjsr->customer->full_name) && !empty($gjsr->customer->full_name) ? $gjsr->customer->full_name : '')).'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-20-percent p-0 border-primary border-b-0 align-middle bg-light-2 text-primary text-center px-3">';
                                $PDFHTML .= '<div class="text-primary uppercase font-medium text-12px leading-none mb-1 px-2">Inspection Date</div>';
                                $PDFHTML .= '<div class="inline-block bg-white w-col9 text-center rounded-none h-30px text-12px font-medium">'.$inspectionDeate.'</div>';
                            $PDFHTML .= '</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                /*$PDFHTML .= '<table class="p-0 border-none mt-1-5">';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->location->name) && !empty($gjsra1->location->name) ? $gjsra1->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->model) && !empty($gjsra1->model) ? $gjsra1->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->make->name) && !empty($gjsra1->make->name) ? $gjsra1->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->type->name) && !empty($gjsra1->type->name) ? $gjsra1->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->serial_no) && !empty($gjsra1->serial_no) ? $gjsra1->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->gc_no) && !empty($gjsra1->gc_no) ? $gjsra1->gc_no : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gjsra1->low_analyser_ratio) && !empty($gjsra1->low_analyser_ratio) ? $gjsra1->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gjsra1->low_co) && !empty($gjsra1->low_co) ? $gjsra1->low_co.' CO (PPM)' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gjsra1->low_co2) && !empty($gjsra1->low_co2) ? $gjsra1->low_co2.' CO<sub>2</sub> (%)' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gjsra1->high_analyser_ratio) && !empty($gjsra1->high_analyser_ratio) ? $gjsra1->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gjsra1->high_co) && !empty($gjsra1->high_co) ? $gjsra1->high_co.' CO (PPM)' : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($gjsra1->high_co2) && !empty($gjsra1->high_co2) ? $gjsra1->high_co2.' CO<sub>2</sub> (%)' : '').'</td>';
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
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->opt_pressure) && !empty($gjsra1->opt_pressure) ? $gjsra1->opt_pressure : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->rented_accommodation) && !empty($gjsra1->rented_accommodation) ? $gjsra1->rented_accommodation : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->type_of_work_carried_out) && !empty($gjsra1->type_of_work_carried_out) ? $gjsra1->type_of_work_carried_out : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->test_carried_out) && !empty($gjsra1->test_carried_out) ? $gjsra1->test_carried_out : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gjsra1->is_electricial_bonding) && !empty($gjsra1->is_electricial_bonding) ? $gjsra1->is_electricial_bonding : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->heat_exchanger) && !empty($gjsra1->heat_exchanger) ? $gjsra1->heat_exchanger : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->heat_exchanger_detail) && !empty($gjsra1->heat_exchanger_detail) ? $gjsra1->heat_exchanger_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Burner / injectors</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->burner_injectors) && !empty($gjsra1->burner_injectors) ? $gjsra1->burner_injectors : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->burner_injectors_detail) && !empty($gjsra1->burner_injectors_detail) ? $gjsra1->burner_injectors_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Flame Picture</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->flame_picture) && !empty($gjsra1->flame_picture) ? $gjsra1->flame_picture : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->flame_picture_detail) && !empty($gjsra1->flame_picture_detail) ? $gjsra1->flame_picture_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Ignition</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->ignition) && !empty($gjsra1->ignition) ? $gjsra1->ignition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->ignition_detail) && !empty($gjsra1->ignition_detail) ? $gjsra1->ignition_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Electrical Connection</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->electrics) && !empty($gjsra1->electrics) ? $gjsra1->electrics : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->electrics_detail) && !empty($gjsra1->electrics_detail) ? $gjsra1->electrics_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Appliance / System Controls</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->controls) && !empty($gjsra1->controls) ? $gjsra1->controls : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->controls_detail) && !empty($gjsra1->controls_detail) ? $gjsra1->controls_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Leaks gas / water</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->leak_gas_water) && !empty($gjsra1->leak_gas_water) ? $gjsra1->leak_gas_water : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->leak_gas_water_detail) && !empty($gjsra1->leak_gas_water_detail) ? $gjsra1->leak_gas_water_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Seals (appliance case etc.)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->seals) && !empty($gjsra1->seals) ? $gjsra1->seals : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->seals_detail) && !empty($gjsra1->seals_detail) ? $gjsra1->seals_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Pipework</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->pipework) && !empty($gjsra1->pipework) ? $gjsra1->pipework : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->pipework_detail) && !empty($gjsra1->pipework_detail) ? $gjsra1->pipework_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Fans</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->fans) && !empty($gjsra1->fans) ? $gjsra1->fans : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->fans_detail) && !empty($gjsra1->fans_detail) ? $gjsra1->fans_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Fireplace catchment space</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->fireplace) && !empty($gjsra1->fireplace) ? $gjsra1->fireplace : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->fireplace_detail) && !empty($gjsra1->fireplace_detail) ? $gjsra1->fireplace_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Closure Plate & PRS10 Tape</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->closure_plate) && !empty($gjsra1->closure_plate) ? $gjsra1->closure_plate : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->closure_plate_detail) && !empty($gjsra1->closure_plate_detail) ? $gjsra1->closure_plate_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Allowable Location</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->allowable_location) && !empty($gjsra1->allowable_location) ? $gjsra1->allowable_location : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->fireplace_allowable_location_detail) && !empty($gjsra1->fireplace_allowable_location_detail) ? $gjsra1->fireplace_allowable_location_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Boiler Ratio</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->boiler_ratio) && !empty($gjsra1->boiler_ratio) ? $gjsra1->boiler_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->boiler_ratio_detail) && !empty($gjsra1->boiler_ratio_detail) ? $gjsra1->boiler_ratio_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Stability</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->stability) && !empty($gjsra1->stability) ? $gjsra1->stability : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->stability_detail) && !empty($gjsra1->stability_detail) ? $gjsra1->stability_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Return air / Plenum</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->return_air_ple) && !empty($gjsra1->return_air_ple) ? $gjsra1->return_air_ple : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->return_air_ple_detail) && !empty($gjsra1->return_air_ple_detail) ? $gjsra1->return_air_ple_detail : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->ventillation) && !empty($gjsra1->ventillation) ? $gjsra1->ventillation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->ventillation_detail) && !empty($gjsra1->ventillation_detail) ? $gjsra1->ventillation_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Flue Termination</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->flue_termination) && !empty($gjsra1->flue_termination) ? $gjsra1->flue_termination : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->flue_termination_detail) && !empty($gjsra1->flue_termination_detail) ? $gjsra1->flue_termination_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Smoke Pellet Flue Flow Test</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->smoke_pellet_flue_flow) && !empty($gjsra1->smoke_pellet_flue_flow) ? $gjsra1->smoke_pellet_flue_flow : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->smoke_pellet_flue_flow_detail) && !empty($gjsra1->smoke_pellet_flue_flow_detail) ? $gjsra1->smoke_pellet_flue_flow_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Smoke Match Spillage Test</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->smoke_pellet_spillage) && !empty($gjsra1->smoke_pellet_spillage) ? $gjsra1->smoke_pellet_spillage : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->smoke_pellet_spillage_detail) && !empty($gjsra1->smoke_pellet_spillage_detail) ? $gjsra1->smoke_pellet_spillage_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Working Pressure</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->working_pressure) && !empty($gjsra1->working_pressure) ? $gjsra1->working_pressure : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->working_pressure_detail) && !empty($gjsra1->working_pressure_detail) ? $gjsra1->working_pressure_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Safety Device</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->savety_devices) && !empty($gjsra1->savety_devices) ? $gjsra1->savety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->savety_devices_detail) && !empty($gjsra1->savety_devices_detail) ? $gjsra1->savety_devices_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Gas Tightness Test</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->gas_tightness) && !empty($gjsra1->gas_tightness) ? $gjsra1->gas_tightness : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->gas_tightness_detail) && !empty($gjsra1->gas_tightness_detail) ? $gjsra1->gas_tightness_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Expansion vessel checked / rech rged</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->expansion_vassel_checked) && !empty($gjsra1->expansion_vassel_checked) ? $gjsra1->expansion_vassel_checked : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->expansion_vassel_checked_detail) && !empty($gjsra1->expansion_vassel_checked_detail) ? $gjsra1->expansion_vassel_checked_detail : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Other (regulations etc.)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">'.(isset($gjsra1->other_regulations) && !empty($gjsra1->other_regulations) ? $gjsra1->other_regulations : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">'.(isset($gjsra1->other_regulations_detail) && !empty($gjsra1->other_regulations_detail) ? $gjsra1->other_regulations_detail : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 align-top h-60px">'.(isset($gjsra1->work_required_note) && !empty($gjsra1->work_required_note) ? $gjsra1->work_required_note : '').'</td>';
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
                                        $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-10px w-130px leading-none align-middle">'.(isset($gjsra1->is_safe_to_use) && !empty($gjsra1->is_safe_to_use) ? $gjsra1->is_safe_to_use : '').'</td>';
                                    $PDFHTML .= '</tr>';
                                $PDFHTML .= '</tbody>';
                            $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col7 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-10px uppercase px-2 py-05 leading-none align-middle">Has the installation been carried out to the relevant standard/manufacturer\'s instructions? </td>';
                                            $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-10px w-130px leading-none align-middle">'.(isset($gjsra1->instruction_followed) && !empty($gjsra1->instruction_followed) ? $gjsra1->instruction_followed : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                        $PDFHTML .= '<tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $inspectionDeate = (isset($gjsr->inspection_date) && !empty($gjsr->inspection_date) ? date('d-m-Y', strtotime($gjsr->inspection_date)) : date('d-m-Y'));
                $nextInspectionDate = (isset($gjsr->next_inspection_date) && !empty($gjsr->next_inspection_date) ? date('d-m-Y', strtotime($gjsr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gjsr->user->name) && !empty($gjsr->user->name) ? $gjsr->user->name : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gjsr->relation->name) && !empty($gjsr->relation->name) ? $gjsr->relation->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gjsr->received_by) && !empty($gjsr->received_by) ? $gjsr->received_by : (isset($gjsr->customer->full_name) && !empty($gjsr->customer->full_name) ? $gjsr->customer->full_name : '')).'</td>';
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
                $PDFHTML .= '</table>';*/

            $PDFHTML .= '</body>';
        $PDFHTML .= '</html>';


        $fileName = $gjsr->certificate_number.'.pdf';
        if (Storage::disk('public')->exists('gjsr/'.$gjsr->customer_job_id.'/'.$gjsr->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('gjsr/'.$gjsr->customer_job_id.'/'.$gjsr->job_form_id.'/'.$fileName);
        }
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('gjsr/'.$gjsr->customer_job_id.'/'.$gjsr->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('gjsr/'.$gjsr->customer_job_id.'/'.$gjsr->job_form_id.'/'.$fileName);
    }

    public function destroyDocument(GasJobSheetRecordDocument $gjsrd){
        if (!empty($gjsrd->name) && Storage::disk('public')->exists('gjsr/'.$gjsrd->gas_job_sheet_record_id.'/'.$gjsrd->name)) {
            Storage::disk('public')->delete('gjsr/'.$gjsrd->gas_job_sheet_record_id.'/'.$gjsrd->name);
        }

        $gjsrd->forceDelete();
        return response()->json(['msg' => 'Gas Job Sheet document Successfully deleted.', 'red' => ''], 200);
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
        
        $jobSheets = json_decode($request->jobSheets);

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
            $gasJobSheetRecord = GasJobSheetRecord::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            $this->checkAndUpdateRecordHistory($gasJobSheetRecord->id);

            if($gasJobSheetRecord->id):
                $gasDetails = GasJobSheetRecordDetail::updateOrCreate(['gas_job_sheet_record_id' => $gasJobSheetRecord->id], [
                    'gas_job_sheet_record_id' => $gasJobSheetRecord->id,
                    
                    'date' => (isset($jobSheets->date) && !empty($jobSheets->date) ? date('Y-m-d', strtotime($jobSheets->date)) : null),
                    'job_note' => (isset($jobSheets->job_note) && !empty($jobSheets->job_note) ? $jobSheets->job_note : null),
                    'spares_required' => (isset($jobSheets->spares_required) && !empty($jobSheets->spares_required) ? $jobSheets->spares_required : null),
                    'job_ref' => (isset($jobSheets->job_ref) && !empty($jobSheets->job_ref) ? $jobSheets->job_ref : null),
                    'arrival_time' => (isset($jobSheets->arrival_time) && !empty($jobSheets->arrival_time) ? $jobSheets->arrival_time : null),
                    'departure_time' => (isset($jobSheets->departure_time) && !empty($jobSheets->departure_time) ? $jobSheets->departure_time : null),
                    'hours_used' => (isset($jobSheets->hours_used) && !empty($jobSheets->hours_used) ? $jobSheets->hours_used : null),
                    'awaiting_parts' => (isset($jobSheets->awaiting_parts) && !empty($jobSheets->awaiting_parts) ? $jobSheets->awaiting_parts : null),
                    'job_completed' => (isset($jobSheets->job_completed) && !empty($jobSheets->job_completed) ? $jobSheets->job_completed : null),
                    
                    'updated_by' => $user_id,
                ]);

                if($request->hasFile('job_sheet_files')):
                    $documents = $request->file('job_sheet_files');
                    foreach($documents as $document):
                        $documentName = $gasJobSheetRecord->id.'_'.$document->getClientOriginalName();
                        $path = $document->storeAs('gjsr/'.$customer_job_id.'/'.$job_form_id, $documentName, 'public');
    
                        $data = [];
                        $data['gas_job_sheet_record_id'] = $gasJobSheetRecord->id;
                        $data['name'] = $documentName;
                        $data['path'] = Storage::disk('public')->url($path);
                        $data['mime_type'] = $document->getClientMimeType();
                        $data['size'] = $document->getSize();
                        GasJobSheetRecordDocument::create($data);
                    endforeach;
                endif;
            endif;


            if($request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $gasJobSheetRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    $signature = new Signature();
                    $signature->model_type = GasJobSheetRecord::class;
                    $signature->model_id = $gasJobSheetRecord->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([request()->ip()]);
                    $signature->save();
                endif;
            endif;

            return response()->json(['msg' => 'Certificate successfully created.', 'red' => route('new.records.gjsr.show', $gasJobSheetRecord->id)], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function editReady(Request $request){
        $record_id = $request->record_id;

        $record = GasJobSheetRecord::with('customer', 'customer.contact', 'job', 'job.property')->find($record_id);
        $jobSheets = GasJobSheetRecordDetail::where('gas_job_sheet_record_id', $record_id)->orderBy('id', 'desc')->get()->first();
        $documents = GasJobSheetRecordDocument::where('gas_job_sheet_record_id', $record_id)->orderBy('id', 'asc')->get();

        $data = [
            'certificate_id' => $record->id,
            'certificate' => [
                'inspection_date' => (isset($record->inspection_date) && !empty($record->inspection_date) ? date('d-m-Y', strtotime($record->inspection_date)) : ''),
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
            'jobSheets' => [
                'gas_job_sheet_record_id' => $jobSheets->gas_job_sheet_record_id,
                
                'date' => (isset($jobSheets->date) && !empty($jobSheets->date) ? date('d-m-Y', strtotime($jobSheets->date)) : ''),
                'job_note' => (isset($jobSheets->job_note) && !empty($jobSheets->job_note) ? $jobSheets->job_note : ''),
                'spares_required' => (isset($jobSheets->spares_required) && !empty($jobSheets->spares_required) ? $jobSheets->spares_required : ''),
                'job_ref' => (isset($jobSheets->job_ref) && !empty($jobSheets->job_ref) ? $jobSheets->job_ref : ''),
                'arrival_time' => (isset($jobSheets->arrival_time) && !empty($jobSheets->arrival_time) ? $jobSheets->arrival_time : ''),
                'departure_time' => (isset($jobSheets->departure_time) && !empty($jobSheets->departure_time) ? $jobSheets->departure_time : ''),
                'hours_used' => (isset($jobSheets->hours_used) && !empty($jobSheets->hours_used) ? $jobSheets->hours_used : ''),
                'awaiting_parts' => (isset($jobSheets->awaiting_parts) && !empty($jobSheets->awaiting_parts) ? $jobSheets->awaiting_parts : ''),
                'job_completed' => (isset($jobSheets->job_completed) && !empty($jobSheets->job_completed) ? $jobSheets->job_completed : ''),
            ]
        ];
        $data['jobSheetAnswered'] = count(array_filter($data['jobSheets'], function($v) { return !empty($v); }));
        $data['jobSheetDocuments'] = '';
        if($documents->count()):
            foreach($documents as $doc):
                if($doc->doc_url):
                    if(in_array($doc->mime_type, ['image/gif', 'image/jpeg', 'image/png', 'image/jpg'])):
                        $data['jobSheetDocuments'] .= '<a id="gjsr_doc_'.$doc->id.'" href="'.$doc->doc_url.'" target="_blank" class="relative inline-flex items-center justify-center image-fit h-[60px] w-[60px] bg-success bg-opacity-10 rounded-[3px] overflow-hidden mr-1 mb-1"><button data-id="'.$doc->id.'" class="delete-doc absolute z-10 right-0 top-0 w-[15px] h-[15px] bg-danger text-white rounded-none rounded-bl-[3px] inline-flex items-center justify-center" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-1.5 h-3 w-3"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg></button><img class="rounded-[3px]" src="'.$doc->doc_url.'"></a>';
                    else:
                        $data['jobSheetDocuments'] .= '<a id="gjsr_doc_'.$doc->id.'" href="'.$doc->doc_url.'" target="_blank" class="relative inline-flex items-center justify-center h-[60px] w-[60px] bg-success bg-opacity-10 rounded-[3px] overflow-hidden mr-1 mb-1"><button data-id="'.$doc->id.'" class="delete-doc absolute z-10 right-0 top-0 w-[15px] h-[15px] bg-danger text-white rounded-none rounded-bl-[3px] inline-flex items-center justify-center" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-1.5 h-3 w-3"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg></button><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="file-text" class="lucide lucide-file-text stroke-1.5 h-8 w-8 text-success"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg></a>';
                    endif;
                endif;
            endforeach;
        endif;

        return response()->json(['row' => $data, 'red' => route('new.records.create', $record->job_form_id)], 200);
    }
}
