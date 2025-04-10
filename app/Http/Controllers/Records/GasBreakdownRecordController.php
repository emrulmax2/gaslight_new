<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\GasBreakdownRecord;
use App\Models\GasBreakdownRecordAppliance;
use App\Models\GasServiceRecord;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;
use Barryvdh\DomPDF\Facade\Pdf;

class GasBreakdownRecordController extends Controller
{
    public function show(GasBreakdownRecord $gbr){
        $user_id = auth()->user()->id;
        $gbr->load(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
        $form = JobForm::find($gbr->job_form_id);
        $record = $form->slug;

        if(empty($gbr->certificate_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastCertificate = GasBreakdownRecord::where('job_form_id', $form->id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
            $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

            $cerSerial = $starting_form;
            if(!empty($lastCertificateNo)):
                preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                $cerSerial = (int) $certificateNumbers[1] + 1;
            endif;
            $certificateNumber = $prifix.str_pad($cerSerial, 6, '0', STR_PAD_LEFT);
            GasBreakdownRecord::where('id', $gbr->id)->update(['certificate_number' => $certificateNumber]);
        endif;

        $thePdf = $this->generatePdf($gbr->id);
        return view('app.records.'.$record.'.show', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'gbr' => $gbr,
            'gbra1' => GasBreakdownRecordAppliance::where('gas_breakdown_record_id', $gbr->id)->where('appliance_serial', 1)->get()->first(),
            'signature' => $gbr->signature ? Storage::disk('public')->url($gbr->signature->filename) : '',
            'thePdf' => $thePdf
        ]);
    }

    public function storeAppliance(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $serial = $request->appliance_serial;
        $appliance = (isset($request->app) && !empty($request->app) ? $request->app : []);

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasBreakdownRecord = GasBreakdownRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,
            
            'updated_by' => $user_id,
        ]);
        $saved = 0;
        if($gasBreakdownRecord->id && isset($appliance[$serial]) && !empty($appliance[$serial])):
            $theAppliance = $appliance[$serial];
            $appliance_location_id = (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null);
            if(!empty($appliance_location_id)):
                $gasAppliance = GasBreakdownRecordAppliance::updateOrCreate(['gas_breakdown_record_id' => $gasBreakdownRecord->id, 'appliance_serial' => $serial], [
                    'gas_breakdown_record_id' => $gasBreakdownRecord->id,
                    'appliance_serial' => $serial,
                    'appliance_location_id' => (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null),
                    'boiler_brand_id' => (isset($theAppliance['boiler_brand_id']) && !empty($theAppliance['boiler_brand_id']) ? $theAppliance['boiler_brand_id'] : null),
                    'model' => (isset($theAppliance['model']) && !empty($theAppliance['model']) ? $theAppliance['model'] : null),
                    'appliance_type_id' => (isset($theAppliance['appliance_type_id']) && !empty($theAppliance['appliance_type_id']) ? $theAppliance['appliance_type_id'] : null),
                    'serial_no' => (isset($theAppliance['serial_no']) && !empty($theAppliance['serial_no']) ? $theAppliance['serial_no'] : null),
                    'gc_no' => (isset($theAppliance['gc_no']) && !empty($theAppliance['gc_no']) ? $theAppliance['gc_no'] : null),
                    
                    'performance_analyser_ratio' => (isset($theAppliance['performance_analyser_ratio']) && !empty($theAppliance['performance_analyser_ratio']) ? $theAppliance['performance_analyser_ratio'] : null),
                    'performance_co' => (isset($theAppliance['performance_co']) && !empty($theAppliance['performance_co']) ? $theAppliance['performance_co'] : null),
                    'performance_co2' => (isset($theAppliance['performance_co2']) && !empty($theAppliance['performance_co2']) ? $theAppliance['performance_co2'] : null),
                    'opt_correctly' => (isset($theAppliance['opt_correctly']) && !empty($theAppliance['opt_correctly']) ? $theAppliance['opt_correctly'] : null),
                    'conf_safety_standards' => (isset($theAppliance['conf_safety_standards']) && !empty($theAppliance['conf_safety_standards']) ? $theAppliance['conf_safety_standards'] : null),
                    'notice_exlained' => (isset($theAppliance['notice_exlained']) && !empty($theAppliance['notice_exlained']) ? $theAppliance['notice_exlained'] : null),
                    'flueing_is_safe' => (isset($theAppliance['flueing_is_safe']) && !empty($theAppliance['flueing_is_safe']) ? $theAppliance['flueing_is_safe'] : null),
                    'ventilation_is_safe' => (isset($theAppliance['ventilation_is_safe']) && !empty($theAppliance['ventilation_is_safe']) ? $theAppliance['ventilation_is_safe'] : null),
                    'emition_combustion_test' => (isset($theAppliance['emition_combustion_test']) && !empty($theAppliance['emition_combustion_test']) ? $theAppliance['emition_combustion_test'] : null),
                    'burner_pressure' => (isset($theAppliance['burner_pressure']) && !empty($theAppliance['burner_pressure']) ? $theAppliance['burner_pressure'] : null),
                    'location_of_fault' => (isset($theAppliance['location_of_fault']) && !empty($theAppliance['location_of_fault']) ? $theAppliance['location_of_fault'] : null),
                    'fault_resolved' => (isset($theAppliance['fault_resolved']) && !empty($theAppliance['fault_resolved']) ? $theAppliance['fault_resolved'] : null),
                    'parts_fitted' => (isset($theAppliance['parts_fitted']) && !empty($theAppliance['parts_fitted']) ? $theAppliance['parts_fitted'] : null),
                    'fitted_parts_name' => (isset($theAppliance['fitted_parts_name']) && !empty($theAppliance['fitted_parts_name']) ? $theAppliance['fitted_parts_name'] : null),
                    'parts_required' => (isset($theAppliance['parts_required']) && !empty($theAppliance['parts_required']) ? $theAppliance['parts_required'] : null),
                    'required_parts_name' => (isset($theAppliance['required_parts_name']) && !empty($theAppliance['required_parts_name']) ? $theAppliance['required_parts_name'] : null),
                    'monoxide_alarm_fitted' => (isset($theAppliance['monoxide_alarm_fitted']) && !empty($theAppliance['monoxide_alarm_fitted']) ? $theAppliance['monoxide_alarm_fitted'] : null),
                    'is_safe' => (isset($theAppliance['is_safe']) && !empty($theAppliance['is_safe']) ? $theAppliance['is_safe'] : null),
                    'parts_available' => (isset($theAppliance['parts_available']) && !empty($theAppliance['parts_available']) ? $theAppliance['parts_available'] : null),
                    'recommend_replacement' => (isset($theAppliance['recommend_replacement']) && !empty($theAppliance['recommend_replacement']) ? $theAppliance['recommend_replacement'] : null),
                    'magnetic_filter_fitted' => (isset($theAppliance['magnetic_filter_fitted']) && !empty($theAppliance['magnetic_filter_fitted']) ? $theAppliance['magnetic_filter_fitted'] : null),
                    'improvement_recommended' => (isset($theAppliance['improvement_recommended']) && !empty($theAppliance['improvement_recommended']) ? $theAppliance['improvement_recommended'] : null),
                    'enginner_comments' => (isset($theAppliance['enginner_comments']) && !empty($theAppliance['enginner_comments']) ? $theAppliance['enginner_comments'] : null),
                    
                    'updated_by' => $user_id,
                ]);
                $saved = 1;
            endif;

            return response()->json(['msg' => 'Appliance Details successfully updated.', 'saved' => $saved], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try later or contact with the Administrator.'], 422);
        endif;
    }

    public function storeSignatures(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        
        $gasBreakdownRecord = GasBreakdownRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
            'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
            'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
            'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
            
            'updated_by' => $user_id,
        ]);
        
        if($request->input('sign') !== null):
            $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
            $signatureData = base64_decode($signatureData);
            if(strlen($signatureData) > 2621):
                $gasBreakdownRecord->deleteSignature();
                
                $imageName = 'signatures/' . Str::uuid() . '.png';
                Storage::disk('public')->put($imageName, $signatureData);
                $signature = new Signature();
                $signature->model_type = GasBreakdownRecord::class;
                $signature->model_id = $gasBreakdownRecord->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $imageName;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
            endif;
        endif;

        return response()->json(['msg' => 'Gas Breakdown Record Successfully Saved.', 'saved' => 1, 'red' => route('records.gas.breakdown.record.show', $gasBreakdownRecord->id)], 200);
    }

    public function store(Request $request){
        $gbr_id = $request->gbr_id;
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $submit_type = $request->submit_type;
        $gbr = GasBreakdownRecord::find($gbr_id);

        $red = '';
        $pdf = Storage::disk('public')->url('gsrvr/'.$gbr->customer_job_id.'/'.$gbr->job_form_id.'/'.$gbr->certificate_number.'.pdf');
        $message = '';
        $pdf = $this->generatePdf($gbr_id);
        if($submit_type == 2):
            $data = [];
            $data['status'] = 'Approved';

            GasBreakdownRecord::where('id', $gbr_id)->update($data);
            
            $email = $this->sendEmail($gbr_id, $job_form_id);
            $message = (!$email ? 'Gas Breakdown Certificate has been approved. Email cannot be sent due to an invalid or empty email address.' : 'Gas Breakdown Certificate has been approved and a copy of the certificate mailed to the customer');
        else:
            $data = [];
            $data['status'] = 'Approved';

            GasBreakdownRecord::where('id', $gbr_id)->update($data);
            $message = 'Gas Breakdown Certificate successfully approved.';
        endif;

        return response()->json(['msg' => $message, 'red' => route('company.dashboard'), 'pdf' => $pdf]);
    }

    public function sendEmail($gsr_id, $job_form_id){
        $user_id = auth()->user()->id;
        $gbr = GasServiceRecord::with('job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($gsr_id);
        $customerName = (isset($gbr->customer->full_name) && !empty($gbr->customer->full_name) ? $gbr->customer->full_name : '');
        $customerEmail = (isset($gbr->customer->contact->email) && !empty($gbr->customer->contact->email) ? $gbr->customer->contact->email : '');
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

            $fileName = $gbr->certificate_number.'.pdf';
            $attachmentFiles = [];
            if (Storage::disk('public')->exists('gbr/'.$gbr->customer_job_id.'/'.$gbr->job_form_id.'/'.$fileName)):
                $attachmentFiles[] = [
                    "pathinfo" => 'gbr/'.$gbr->customer_job_id.'/'.$gbr->job_form_id.'/'.$fileName,
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

    public function generatePdf($gbr_id) {
        $user_id = auth()->user()->id;
        $gbr = GasBreakdownRecord::with('customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company')->find($gbr_id);
        $gbra1 = GasBreakdownRecordAppliance::where('gas_breakdown_record_id', $gbr->id)->where('appliance_serial', 1)->get()->first();

        $logoPath = resource_path('images/gas_safe_register_dark.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $userSignBase64 = (isset($gbr->user->signature) && Storage::disk('public')->exists($gbr->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gbr->user->signature->filename)) : '');
        $signatureBase64 = ($gbr->signature && Storage::disk('public')->exists($gbr->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gbr->signature->filename)) : '');
        

        $report_title = 'Certificate of '.$gbr->certificate_number;
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
                                    $PDFHTML .= '<h1 class="text-white text-2xl leading-none mt-0 mb-0">Breakdown Record</h1>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col2 align-middle text-center">';
                                    $PDFHTML .= '<label class="text-white uppercase font-medium text-12px leading-none mb-2 inline-block">Certificate Number</label>';
                                    $PDFHTML .= '<div class="inline-block bg-white w-32 text-center rounded-none leading-28px h-35px font-medium text-primary">'.$gbr->certificate_number.'</div>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gbr->user->name) && !empty($gbr->user->name) ? $gbr->user->name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">GAS SAFE REG.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->user->company->gas_safe_registration_no) && !empty($gbr->user->company->gas_safe_registration_no) ? $gbr->user->company->gas_safe_registration_no : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">ID CARD NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->user->gas_safe_id_card) && !empty($gbr->user->gas_safe_id_card) ? $gbr->user->gas_safe_id_card : '').'</td>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->user->company->company_name) && !empty($gbr->user->company->company_name) ? $gbr->user->company->company_name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gbr->user->company->pdf_address) && !empty($gbr->user->company->pdf_address) ? $gbr->user->company->pdf_address : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">TEL NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->user->company->company_phone) && !empty($gbr->user->company->company_phone) ? $gbr->user->company->company_phone : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Email</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->user->company->company_email) && !empty($gbr->user->company->company_email) ? $gbr->user->company->company_email : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->job->property->occupant_name) && !empty($gbr->job->property->occupant_name) ? $gbr->job->property->occupant_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gbr->job->property->pdf_address) && !empty($gbr->job->property->pdf_address) ? $gbr->job->property->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->job->property->postal_code) && !empty($gbr->job->property->postal_code) ? $gbr->job->property->postal_code : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->customer->full_name) && !empty($gbr->customer->full_name) ? $gbr->customer->full_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px leading-none align-middle">'.(isset($gbr->customer->company_name) && !empty($gbr->customer->company_name) ? $gbr->customer->company_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gbr->customer->pdf_address) && !empty($gbr->customer->pdf_address) ? $gbr->customer->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gbr->customer->postal_code) && !empty($gbr->customer->postal_code) ? $gbr->customer->postal_code : '').'</td>';
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
                                            $PDFHTML .= '<th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Appliance Details';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'Location';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'Model';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'Make';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'Type';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'Serial No.';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'GC No.';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gbra1->location->name) && !empty($gbra1->location->name) ? $gbra1->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gbra1->model) && !empty($gbra1->model) ? $gbra1->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gbra1->make->name) && !empty($gbra1->make->name) ? $gbra1->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gbra1->type->name) && !empty($gbra1->type->name) ? $gbra1->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gbra1->serial_no) && !empty($gbra1->serial_no) ? $gbra1->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($gbra1->gc_no) && !empty($gbra1->gc_no) ? $gbra1->gc_no : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-half pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="3" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Combustion Performance Readings';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'Ratio';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'CO (PPM)';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">';
                                                $PDFHTML .= 'CO2 (%)';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r w-col4">'.(isset($gbra1->performance_analyser_ratio) && !empty($gbra1->performance_analyser_ratio) ? $gbra1->performance_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r w-col4">'.(isset($gbra1->performance_co) && !empty($gbra1->performance_co) ? $gbra1->performance_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r w-col4">'.(isset($gbra1->performance_co2) && !empty($gbra1->performance_co2) ? $gbra1->performance_co2 : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
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
                                                $PDFHTML .= 'Safety Checks';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Appliance is operating correctly</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->opt_correctly) && !empty($gbra1->opt_correctly) ? $gbra1->opt_correctly : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Appliance conforms to current safety standards</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->conf_safety_standards) && !empty($gbra1->conf_safety_standards) ? $gbra1->conf_safety_standards : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Safety warning/advice notice explained and left</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->notice_exlained) && !empty($gbra1->notice_exlained) ? $gbra1->notice_exlained : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Appliance flueing is safe</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->flueing_is_safe) && !empty($gbra1->flueing_is_safe) ? $gbra1->flueing_is_safe : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Appliance ventilation is safe</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->ventilation_is_safe) && !empty($gbra1->ventilation_is_safe) ? $gbra1->ventilation_is_safe : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Burner pressure/Gas rate correct</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->burner_pressure) && !empty($gbra1->burner_pressure) ? $gbra1->burner_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Emission/combustion test</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->emition_combustion_test) && !empty($gbra1->emition_combustion_test) ? $gbra1->emition_combustion_test : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col4 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Breakdown';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Location of Fault</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col7">'.(isset($gbra1->location_of_fault) && !empty($gbra1->location_of_fault) ? $gbra1->location_of_fault : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Parts fitted</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col7">'.(isset($gbra1->parts_fitted) && !empty($gbra1->parts_fitted) ? $gbra1->parts_fitted : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Name of parts (if fitted)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col7">'.(isset($gbra1->fitted_parts_name) && !empty($gbra1->fitted_parts_name) ? $gbra1->fitted_parts_name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Faults Resolved</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col7">'.(isset($gbra1->fault_resolved) && !empty($gbra1->fault_resolved) ? $gbra1->fault_resolved : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Parts required</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col7">'.(isset($gbra1->parts_required) && !empty($gbra1->parts_required) ? $gbra1->parts_required : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Name of parts (if fitted)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col7">'.(isset($gbra1->required_parts_name) && !empty($gbra1->required_parts_name) ? $gbra1->required_parts_name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">&nbsp;</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col7">&nbsp;</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col4 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'Appliance / system advice and recommendations';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Approved audible Carbon Monoxide Alarm fitted</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->monoxide_alarm_fitted) && !empty($gbra1->monoxide_alarm_fitted) ? $gbra1->monoxide_alarm_fitted : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Appliance is safe</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->is_safe) && !empty($gbra1->is_safe) ? $gbra1->is_safe : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">All functional parts available</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->parts_available) && !empty($gbra1->parts_available) ? $gbra1->parts_available : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Recommended appliance replacement</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->recommend_replacement) && !empty($gbra1->recommend_replacement) ? $gbra1->recommend_replacement : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Magnetic System filter fitted</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->magnetic_filter_fitted) && !empty($gbra1->magnetic_filter_fitted) ? $gbra1->magnetic_filter_fitted : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">System improvement(s)</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">'.(isset($gbra1->improvement_recommended) && !empty($gbra1->improvement_recommended) ? $gbra1->improvement_recommended : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">&nbsp;</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-70px">&nbsp;</td>';
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
                            $PDFHTML .= '<th class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                $PDFHTML .= 'Engineer Comments';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 align-top h-60px">'.(isset($gbra1->enginner_comments) && !empty($gbra1->enginner_comments) ? $gbra1->enginner_comments : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $inspectionDeate = (isset($gbr->inspection_date) && !empty($gbr->inspection_date) ? date('d-m-Y', strtotime($gbr->inspection_date)) : date('d-m-Y'));
                $nextInspectionDate = (isset($gbr->next_inspection_date) && !empty($gbr->next_inspection_date) ? date('d-m-Y', strtotime($gbr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                
                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="3" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gbr->user->name) && !empty($gbr->user->name) ? $gbr->user->name : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gbr->relation->name) && !empty($gbr->relation->name) ? $gbr->relation->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gbr->received_by) && !empty($gbr->received_by) ? $gbr->received_by : (isset($gbr->customer->full_name) && !empty($gbr->customer->full_name) ? $gbr->customer->full_name : '')).'</td>';
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


        $fileName = $gbr->certificate_number.'.pdf';
        if (Storage::disk('public')->exists('gbr/'.$gbr->customer_job_id.'/'.$gbr->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('gbr/'.$gbr->customer_job_id.'/'.$gbr->job_form_id.'/'.$fileName);
        }
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('gbr/'.$gbr->customer_job_id.'/'.$gbr->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('gbr/'.$gbr->customer_job_id.'/'.$gbr->job_form_id.'/'.$fileName);
    }
}
