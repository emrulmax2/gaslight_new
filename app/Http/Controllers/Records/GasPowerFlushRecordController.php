<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\ExistingRecordDraft;
use App\Models\GasPowerFlushRecord;
use App\Models\GasPowerFlushRecordChecklist;
use App\Models\GasPowerFlushRecordRediator;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;

class GasPowerFlushRecordController extends Controller
{
    public function checkAndUpdateRecordHistory($record_id){
        $record = GasPowerFlushRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model' => GasPowerFlushRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasPowerFlushRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => auth()->user()->id,
        ]);
    }


    public function show(GasPowerFlushRecord $gpfr){
        $user_id = auth()->user()->id;
        $gpfr->load(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
        $form = JobForm::find($gpfr->job_form_id);
        $record = $form->slug;

        if(empty($gpfr->certificate_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastCertificate = GasPowerFlushRecord::where('job_form_id', $form->id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
            $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

            $cerSerial = $starting_form;
            if(!empty($lastCertificateNo)):
                preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                $cerSerial = (int) $certificateNumbers[1] + 1;
            endif;
            $certificateNumber = $prifix.str_pad($cerSerial, 6, '0', STR_PAD_LEFT);
            GasPowerFlushRecord::where('id', $gpfr->id)->update(['certificate_number' => $certificateNumber]);
        endif;

        $thePdf = $this->generatePdf($gpfr->id);
        return view('app.records.'.$record.'.show', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'gpfr' => $gpfr,
            'gpfrc' => GasPowerFlushRecordChecklist::where('gas_power_flush_record_id', $gpfr->id)->get()->first(),
            'gpfrcr' => GasPowerFlushRecordRediator::where('gas_power_flush_record_id', $gpfr->id)->orderBy('id', 'ASC')->get(),
            'signature' => $gpfr->signature ? Storage::disk('public')->url($gpfr->signature->filename) : '',
            'thePdf' => $thePdf
        ]);
    }

    public function storeChecklist(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasPowerFlush = GasPowerFlushRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,
            
            'updated_by' => $user_id,
        ]);
        $this->checkAndUpdateRecordHistory($gasPowerFlush->id);

        if($gasPowerFlush->id):
            $gasPowerFlushChecklist = GasPowerFlushRecordChecklist::updateOrCreate(['gas_power_flush_record_id' => $gasPowerFlush->id], [
                'gas_power_flush_record_id' => $gasPowerFlush->id,

                'powerflush_system_type_id' => (isset($request->powerflush_system_type_id) && !empty($request->powerflush_system_type_id) ? $request->powerflush_system_type_id : null),
                'boiler_brand_id' => (isset($request->boiler_brand_id) && !empty($request->boiler_brand_id) ? $request->boiler_brand_id : null),
                'radiators' => (isset($request->radiators) && !empty($request->radiators) ? $request->radiators : null),
                'pipework' => (isset($request->pipework) && !empty($request->pipework) ? $request->pipework : null),
                'appliance_type_id' => (isset($request->appliance_type_id) && !empty($request->appliance_type_id) ? $request->appliance_type_id : null),
                'appliance_location_id' => (isset($request->appliance_location_id) && !empty($request->appliance_location_id) ? $request->appliance_location_id : null),
                'serial_no' => (isset($request->serial_no) && !empty($request->serial_no) ? $request->serial_no : null),
                'powerflush_cylinder_type_id' => (isset($request->powerflush_cylinder_type_id) && !empty($request->powerflush_cylinder_type_id) ? $request->powerflush_cylinder_type_id : null),
                'powerflush_pipework_type_id' => (isset($request->powerflush_pipework_type_id) && !empty($request->powerflush_pipework_type_id) ? $request->powerflush_pipework_type_id : null),
                'twin_radiator_vlv_fitted' => (isset($request->twin_radiator_vlv_fitted) && !empty($request->twin_radiator_vlv_fitted) ? $request->twin_radiator_vlv_fitted : null),
                'completely_warm_on_fired' => (isset($request->completely_warm_on_fired) && !empty($request->completely_warm_on_fired) ? $request->completely_warm_on_fired : null),
                'circulation_for_all_readiators' => (isset($request->circulation_for_all_readiators) && !empty($request->circulation_for_all_readiators) ? $request->circulation_for_all_readiators : null),
                'suffifiently_sound' => (isset($request->suffifiently_sound) && !empty($request->suffifiently_sound) ? $request->suffifiently_sound : null),
                'powerflush_circulator_pump_location_id' => (isset($request->powerflush_circulator_pump_location_id) && !empty($request->powerflush_circulator_pump_location_id) ? $request->powerflush_circulator_pump_location_id : null),
                'number_of_radiators' => (isset($request->number_of_radiators) && !empty($request->number_of_radiators) ? $request->number_of_radiators : null),
                'radiator_type_id' => (isset($request->radiator_type_id) && !empty($request->radiator_type_id) ? $request->radiator_type_id : null),
                'getting_warm' => (isset($request->getting_warm) && !empty($request->getting_warm) ? $request->getting_warm : null),
                'are_trvs_fitted' => (isset($request->are_trvs_fitted) && !empty($request->are_trvs_fitted) ? $request->are_trvs_fitted : null),
                'sign_of_neglect' => (isset($request->sign_of_neglect) && !empty($request->sign_of_neglect) ? $request->sign_of_neglect : null),
                'radiator_open_fully' => (isset($request->radiator_open_fully) && !empty($request->radiator_open_fully) ? $request->radiator_open_fully : null),
                'number_of_valves' => (isset($request->number_of_valves) && !empty($request->number_of_valves) ? $request->number_of_valves : null),
                'valves_located' => (isset($request->valves_located) && !empty($request->valves_located) ? $request->valves_located : null),
                'fe_tank_location' => (isset($request->fe_tank_location) && !empty($request->fe_tank_location) ? $request->fe_tank_location : null),
                'fe_tank_checked' => (isset($request->fe_tank_checked) && !empty($request->fe_tank_checked) ? $request->fe_tank_checked : null),
                'fe_tank_condition' => (isset($request->fe_tank_condition) && !empty($request->fe_tank_condition) ? $request->fe_tank_condition : null),
                'color_id' => (isset($request->color_id) && !empty($request->color_id) ? $request->color_id : null),
                'before_color_id' => (isset($request->before_color_id) && !empty($request->before_color_id) ? $request->before_color_id : null),
                'mw_ph' => (isset($request->mw_ph) && !empty($request->mw_ph) ? $request->mw_ph : null),
                'mw_chloride' => (isset($request->mw_chloride) && !empty($request->mw_chloride) ? $request->mw_chloride : null),
                'mw_hardness' => (isset($request->mw_hardness) && !empty($request->mw_hardness) ? $request->mw_hardness : null),
                'mw_inhibitor' => (isset($request->mw_inhibitor) && !empty($request->mw_inhibitor) ? $request->mw_inhibitor : null),
                'bpf_ph' => (isset($request->bpf_ph) && !empty($request->bpf_ph) ? $request->bpf_ph : null),
                'bpf_chloride' => (isset($request->bpf_chloride) && !empty($request->bpf_chloride) ? $request->bpf_chloride : null),
                'bpf_hardness' => (isset($request->bpf_hardness) && !empty($request->bpf_hardness) ? $request->bpf_hardness : null),
                'bpf_inhibitor' => (isset($request->bpf_inhibitor) && !empty($request->bpf_inhibitor) ? $request->bpf_inhibitor : null),
                'apf_ph' => (isset($request->apf_ph) && !empty($request->apf_ph) ? $request->apf_ph : null),
                'apf_chloride' => (isset($request->apf_chloride) && !empty($request->apf_chloride) ? $request->apf_chloride : null),
                'apf_hardness' => (isset($request->apf_hardness) && !empty($request->apf_hardness) ? $request->apf_hardness : null),
                'apf_inhibitor' => (isset($request->apf_inhibitor) && !empty($request->apf_inhibitor) ? $request->apf_inhibitor : null),
                'mw_tds_reading' => (isset($request->mw_tds_reading) && !empty($request->mw_tds_reading) ? $request->mw_tds_reading : null),
                'bf_tds_reading' => (isset($request->bf_tds_reading) && !empty($request->bf_tds_reading) ? $request->bf_tds_reading : null),
                'af_tds_reading' => (isset($request->af_tds_reading) && !empty($request->af_tds_reading) ? $request->af_tds_reading : null),

                'updated_by' => $user_id,
            ]);

            return response()->json(['msg' => 'Power Flush Checklist Details successfully updated.', 'saved' => 1], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try later or contact with the Administrator.'], 422);
        endif;
    }

    public function storeRadiators(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $rediators = (isset($request->red) && !empty($request->red) ? $request->red : []);

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasPowerFlush = GasPowerFlushRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,
            
            'updated_by' => $user_id,
        ]);
        $this->checkAndUpdateRecordHistory($gasPowerFlush->id);

        GasPowerFlushRecordRediator::where('gas_power_flush_record_id',  $gasPowerFlush->id)->forceDelete();
        if($gasPowerFlush->id && !empty($rediators)):
            foreach($rediators as $rediator):
                $gasPowerFlushChecklist = GasPowerFlushRecordRediator::create([
                    'gas_power_flush_record_id' => $gasPowerFlush->id,

                    'rediator_location' => (isset($rediator['rediator_location']) && !empty($rediator['rediator_location']) ? $rediator['rediator_location'] : null),
                    'tmp_b_top' => (isset($rediator['tmp_b_top']) && !empty($rediator['tmp_b_top']) ? $rediator['tmp_b_top'] : null),
                    'tmp_b_bottom' => (isset($rediator['tmp_b_bottom']) && !empty($rediator['tmp_b_bottom']) ? $rediator['tmp_b_bottom'] : null),
                    'tmp_b_left' => (isset($rediator['tmp_b_left']) && !empty($rediator['tmp_b_left']) ? $rediator['tmp_b_left'] : null),
                    'tmp_b_right' => (isset($rediator['tmp_b_right']) && !empty($rediator['tmp_b_right']) ? $rediator['tmp_b_right'] : null),
                    'tmp_a_top' => (isset($rediator['tmp_a_top']) && !empty($rediator['tmp_a_top']) ? $rediator['tmp_a_top'] : null),
                    'tmp_a_bottom' => (isset($rediator['tmp_a_bottom']) && !empty($rediator['tmp_a_bottom']) ? $rediator['tmp_a_bottom'] : null),
                    'tmp_a_left' => (isset($rediator['tmp_a_left']) && !empty($rediator['tmp_a_left']) ? $rediator['tmp_a_left'] : null),
                    'tmp_a_right' => (isset($rediator['tmp_a_right']) && !empty($rediator['tmp_a_right']) ? $rediator['tmp_a_right'] : null)
                ]);
            endforeach;

            return response()->json(['msg' => 'Power Flush Radiators Details successfully updated.', 'saved' => 1], 200);
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

        
        $gasPowerFlush = GasPowerFlushRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
            'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
            'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
            'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
            
            'updated_by' => $user_id,
        ]);
        $this->checkAndUpdateRecordHistory($gasPowerFlush->id);
        
        if($request->input('sign') !== null):
            $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
            $signatureData = base64_decode($signatureData);
            if(strlen($signatureData) > 2621):
                $gasPowerFlush->deleteSignature();
                
                $imageName = 'signatures/' . Str::uuid() . '.png';
                Storage::disk('public')->put($imageName, $signatureData);
                $signature = new Signature();
                $signature->model_type = GasPowerFlushRecord::class;
                $signature->model_id = $gasPowerFlush->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $imageName;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
            endif;
        endif;

        return response()->json(['msg' => 'Gas Power Flush Record Successfully Saved.', 'saved' => 1, 'red' => route('records.gas.power.flush.record.show', $gasPowerFlush->id)], 200);
    }

    public function store(Request $request){
        $gpfr_id = $request->gpfr_id;
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $submit_type = $request->submit_type;
        $gpfr = GasPowerFlushRecord::find($gpfr_id);

        $red = '';
        $pdf = Storage::disk('public')->url('gpfr/'.$gpfr->customer_job_id.'/'.$gpfr->job_form_id.'/'.$gpfr->certificate_number.'.pdf');
        $message = '';
        $pdf = $this->generatePdf($gpfr_id);
        if($submit_type == 2):
            $data = [];
            $data['status'] = 'Approved';

            GasPowerFlushRecord::where('id', $gpfr_id)->update($data);
            
            $email = $this->sendEmail($gpfr_id, $job_form_id);
            $message = (!$email ? 'Gas Power Flush Certificate has been approved. Email cannot be sent due to an invalid or empty email address.' : 'Gas Power Flush Certificate has been approved and a copy of the certificate mailed to the customer');
        else:
            $data = [];
            $data['status'] = 'Approved';

            GasPowerFlushRecord::where('id', $gpfr_id)->update($data);
            $message = 'Gas Power Flush Certificate successfully approved.';
        endif;

        return response()->json(['msg' => $message, 'red' => route('company.dashboard'), 'pdf' => $pdf]);
    }

    public function sendEmail($gpfr_id, $job_form_id){
        $user_id = auth()->user()->id;
        $gpfr = GasPowerFlushRecord::with('job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($gpfr_id);
        $customerName = (isset($gpfr->customer->full_name) && !empty($gpfr->customer->full_name) ? $gpfr->customer->full_name : '');
        $customerEmail = (isset($gpfr->customer->contact->email) && !empty($gpfr->customer->contact->email) ? $gpfr->customer->contact->email : '');
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

            $fileName = $gpfr->certificate_number.'.pdf';
            $attachmentFiles = [];
            if (Storage::disk('public')->exists('gpfr/'.$gpfr->customer_job_id.'/'.$gpfr->job_form_id.'/'.$fileName)):
                $attachmentFiles[] = [
                    "pathinfo" => 'gpfr/'.$gpfr->customer_job_id.'/'.$gpfr->job_form_id.'/'.$fileName,
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

    public function generatePdf($gpfr_id) {
        $user_id = auth()->user()->id;
        $gpfr = GasPowerFlushRecord::with('customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company')->find($gpfr_id);
        $gpfrc = GasPowerFlushRecordChecklist::where('gas_power_flush_record_id', $gpfr->id)->get()->first();
        $gpfrr = GasPowerFlushRecordRediator::where('gas_power_flush_record_id', $gpfr->id)->orderBy('id', 'ASC')->get();

        $logoPath = resource_path('images/gas_safe_register_dark.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $userSignBase64 = (isset($gpfr->user->signature) && Storage::disk('public')->exists($gpfr->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gpfr->user->signature->filename)) : '');
        $signatureBase64 = ($gpfr->signature && Storage::disk('public')->exists($gpfr->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($gpfr->signature->filename)) : '');
        

        $report_title = 'Certificate of '.$gpfr->certificate_number;
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
                                .capitalize{text-transform: capitalize;}
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
                                .w-col10-half{width: 41.666666%}
                                .w-col10-8by-1{width: 10.416666%}
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
                                .ml-1{margin-left: .25rem;}
                                .ml-2{margin-left: .50rem;}

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
                                .border-t-white{border-top-color: #164e63 !important;}
                                .border-t-primary{border-top-color: #FFF !important;}
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
                                    $PDFHTML .= '<h1 class="text-white text-2xl leading-none mt-0 mb-0">Powerflushing Checklist</h1>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col2 align-middle text-center">';
                                    $PDFHTML .= '<label class="text-white uppercase font-medium text-12px leading-none mb-2 inline-block">Certificate Number</label>';
                                    $PDFHTML .= '<div class="inline-block bg-white w-32 text-center rounded-none leading-28px h-35px font-medium text-primary">'.$gpfr->certificate_number.'</div>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gpfr->user->name) && !empty($gpfr->user->name) ? $gpfr->user->name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">GAS SAFE REG.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->user->company->gas_safe_registration_no) && !empty($gpfr->user->company->gas_safe_registration_no) ? $gpfr->user->company->gas_safe_registration_no : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">ID CARD NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->user->gas_safe_id_card) && !empty($gpfr->user->gas_safe_id_card) ? $gpfr->user->gas_safe_id_card : '').'</td>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->user->company->company_name) && !empty($gpfr->user->company->company_name) ? $gpfr->user->company->company_name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gpfr->user->company->pdf_address) && !empty($gpfr->user->company->pdf_address) ? $gpfr->user->company->pdf_address : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">TEL NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->user->company->company_phone) && !empty($gpfr->user->company->company_phone) ? $gpfr->user->company->company_phone : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Email</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->user->company->company_email) && !empty($gpfr->user->company->company_email) ? $gpfr->user->company->company_email : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->job->property->occupant_name) && !empty($gpfr->job->property->occupant_name) ? $gpfr->job->property->occupant_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gpfr->job->property->pdf_address) && !empty($gpfr->job->property->pdf_address) ? $gpfr->job->property->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->job->property->postal_code) && !empty($gpfr->job->property->postal_code) ? $gpfr->job->property->postal_code : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->customer->full_name) && !empty($gpfr->customer->full_name) ? $gpfr->customer->full_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->customer->company_name) && !empty($gpfr->customer->company_name) ? $gpfr->customer->company_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($gpfr->customer->pdf_address) && !empty($gpfr->customer->pdf_address) ? $gpfr->customer->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($gpfr->customer->postal_code) && !empty($gpfr->customer->postal_code) ? $gpfr->customer->postal_code : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                        $PDFHTML .= '</tbody>';
                                    $PDFHTML .= '</table>';
                                $PDFHTML .= '</td>';
                            $PDFHTML .= '</tr>';
                        $PDFHTML .= '</tbody>';
                    $PDFHTML .= '</table>';
                $PDFHTML .= '</div>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Type of System</td>';
                            $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->systemType->name) && !empty($gpfrc->systemType->name) ? $gpfrc->systemType->name : '').'</td>';
                        
                            $PDFHTML .= '<td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Pipework</td>';
                            $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->pipework) && !empty($gpfrc->pipework) ? $gpfrc->pipework : '').'</td>';
                        
                            $PDFHTML .= '<td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Serial Number</td>';
                            $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->serial_no) && !empty($gpfrc->serial_no) ? $gpfrc->serial_no : '').'</td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Boiler</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->make->name) && !empty($gpfrc->make->name) ? $gpfrc->make->name : '').'</td>';
                        
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Type of Boiler</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->type->name) && !empty($gpfrc->type->name) ? $gpfrc->type->name : '').'</td>';
                        
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Type of Water Cylinder</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->cylinder->name) && !empty($gpfrc->cylinder->name) ? $gpfrc->cylinder->name : '').'</td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Radiators</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->radiators) && !empty($gpfrc->radiators) ? $gpfrc->radiators : '').'</td>';
                        
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Location of Boiler</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->location->name) && !empty($gpfrc->location->name) ? $gpfrc->location->name : '').'</td>';
                        
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Type of Pipework</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal w-col2 align-middle">'.(isset($gpfrc->pipeworkType->name) && !empty($gpfrc->pipeworkType->name) ? $gpfrc->pipeworkType->name : '').'</td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">If microbore system</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Are twin entry radiator valves fitted: <span class="ml-1 font-medium">'.(isset($gpfrc->twin_radiator_vlv_fitted) && !empty($gpfrc->twin_radiator_vlv_fitted) ? $gpfrc->twin_radiator_vlv_fitted : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="3">If so, are all radiators completely warm when boiler fired: <span class="ml-1 font-medium">'.(isset($gpfrc->completely_warm_on_fired) && !empty($gpfrc->completely_warm_on_fired) ? $gpfrc->completely_warm_on_fired : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">If single pipe system</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="5">Is there circulation (heat) to all radiators: <span class="ml-1 font-medium">'.(isset($gpfrc->circulation_for_all_readiators) && !empty($gpfrc->circulation_for_all_readiators) ? $gpfrc->circulation_for_all_readiators : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-nowrap font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle" rowspan="3">If elderly steel pipework</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Is system sufficiently sound to power flush: <span class="ml-1 font-medium">'.(isset($gpfrc->suffifiently_sound) && !empty($gpfrc->suffifiently_sound) ? $gpfrc->suffifiently_sound : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Location of system circulator pump: <span class="ml-1 font-medium">'.(isset($gpfrc->pumpLocation->name) && !empty($gpfrc->pumpLocation->name) ? $gpfrc->pumpLocation->name : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle">Number of radiators: <span class="ml-1 font-medium">'.(isset($gpfrc->number_of_radiators) && !empty($gpfrc->number_of_radiators) ? $gpfrc->number_of_radiators : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Radiator Type: <span class="ml-1 font-medium">'.(isset($gpfrc->rediatorType->name) && !empty($gpfrc->rediatorType->name) ? $gpfrc->rediatorType->name : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Are they getting warm: <span class="ml-1 font-medium">'.(isset($gpfrc->getting_warm) && !empty($gpfrc->getting_warm) ? $gpfrc->getting_warm : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle">Are TRV\'s fitted: <span class="ml-1 font-medium">'.(isset($gpfrc->are_trvs_fitted) && !empty($gpfrc->are_trvs_fitted) ? $gpfrc->are_trvs_fitted : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Any obvious signs of neglect/leak: <span class="ml-1 font-medium">'.(isset($gpfrc->sign_of_neglect) && !empty($gpfrc->sign_of_neglect) ? $gpfrc->sign_of_neglect : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="3">Do all thermostic radiator valves (TRV\'s) open fully: <span class="ml-1 font-medium">'.(isset($gpfrc->radiator_open_fully) && !empty($gpfrc->radiator_open_fully) ? $gpfrc->radiator_open_fully : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Are there zone valves / Where are they located</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Number of valves: <span class="ml-1 font-medium">'.(isset($gpfrc->number_of_valves) && !empty($gpfrc->number_of_valves) ? $gpfrc->number_of_valves : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="3">Location: <span class="ml-1 font-medium">'.(isset($gpfrc->valves_located) && !empty($gpfrc->valves_located) ? $gpfrc->valves_located : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle" rowspan="2">F & E Tank</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Location: <span class="ml-1 font-medium">'.(isset($gpfrc->fe_tank_location) && !empty($gpfrc->fe_tank_location) ? $gpfrc->fe_tank_location : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Checked: <span class="ml-1 font-medium">'.(isset($gpfrc->fe_tank_checked) && !empty($gpfrc->fe_tank_checked) ? $gpfrc->fe_tank_checked : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle">Condition: <span class="ml-1 font-medium">'.(isset($gpfrc->fe_tank_condition) && !empty($gpfrc->fe_tank_condition) ? $gpfrc->fe_tank_condition : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">Color of heating system water, as run from bottom of radiator: <span class="ml-1 font-medium">'.(isset($gpfrc->color->name) && !empty($gpfrc->color->name) ? $gpfrc->color->name : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="3">Visual inspection of system water before PowerFlush: <span class="ml-1 font-medium">'.(isset($gpfrc->beforeColor->name) && !empty($gpfrc->beforeColor->name) ? $gpfrc->beforeColor->name : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Test Parameter</td>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-primary border-l border-l-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">pH</td>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-primary border-l border-l-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">chloride (ppm)</td>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-primary border-l border-l-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Hardness</td>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-primary border-l border-l-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle" colspan="2">Inhibitor (ppm molybdate)</td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">Mains water</td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->mw_ph) && !empty($gpfrc->mw_ph) ? $gpfrc->mw_ph : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->mw_chloride) && !empty($gpfrc->mw_chloride) ? $gpfrc->mw_chloride : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->mw_hardness) && !empty($gpfrc->mw_hardness) ? $gpfrc->mw_hardness : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2"><span class="font-medium">'.(isset($gpfrc->mw_inhibitor) && !empty($gpfrc->mw_inhibitor) ? $gpfrc->mw_inhibitor : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">System water before PowerFlush</td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->bpf_ph) && !empty($gpfrc->bpf_ph) ? $gpfrc->bpf_ph : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->bpf_chloride) && !empty($gpfrc->bpf_chloride) ? $gpfrc->bpf_chloride : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->bpf_hardness) && !empty($gpfrc->bpf_hardness) ? $gpfrc->bpf_hardness : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2"><span class="font-medium">'.(isset($gpfrc->bpf_inhibitor) && !empty($gpfrc->bpf_inhibitor) ? $gpfrc->bpf_inhibitor : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">System water after PowerFlush</td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->apf_ph) && !empty($gpfrc->apf_ph) ? $gpfrc->apf_ph : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->apf_chloride) && !empty($gpfrc->apf_chloride) ? $gpfrc->apf_chloride : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle"><span class="font-medium">'.(isset($gpfrc->apf_hardness) && !empty($gpfrc->apf_hardness) ? $gpfrc->apf_hardness : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2"><span class="font-medium">'.(isset($gpfrc->apf_inhibitor) && !empty($gpfrc->apf_inhibitor) ? $gpfrc->apf_inhibitor : '').'</span></td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary border-t border-t-sec whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle">TDS Readings</td>';
                            $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle">Mains Water: <span class="ml-1 font-medium">'.(isset($gpfrc->mw_tds_reading) && !empty($gpfrc->mw_tds_reading) ? $gpfrc->mw_tds_reading.' ppm' : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">System water before flush: <span class="ml-1 font-medium">'.(isset($gpfrc->bf_tds_reading) && !empty($gpfrc->bf_tds_reading) ? $gpfrc->bf_tds_reading.' ppm' : '').'</span></td>';
                            $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle" colspan="2">System water after flush: <span class="ml-1 font-medium">'.(isset($gpfrc->af_tds_reading) && !empty($gpfrc->af_tds_reading) ? $gpfrc->af_tds_reading.' ppm' : '').'</span></td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary whitespace-normal font-medium bg-primary text-white text-11px capitalize px-2 py-05 leading-none tracking-normal w-col2 align-middle"> System water after</td>';
                            $PDFHTML .= '<td class="border-primary border-l border-l-sec whitespace-normal font-medium bg-primary text-white text-11px text-center capitalize px-2 py-05 leading-none tracking-normal align-middle" colspan="4">Temperature before powerflus in °C</td>';
                            $PDFHTML .= '<td class="border-primary border-l border-l-sec whitespace-normal font-medium bg-primary text-white text-11px text-center capitalize px-2 py-05 leading-none tracking-normal align-middle" colspan="4">Temperature After powerflus in °C</td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col2">&nbsp;</td>';
                            $PDFHTML .= '<td class="border-primary border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">Top</td>';
                            $PDFHTML .= '<td class="border-primary border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">Bottom</td>';
                            $PDFHTML .= '<td class="border-primary border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">Left</td>';
                            $PDFHTML .= '<td class="border-primary border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">Right</td>';
                            $PDFHTML .= '<td class="border-primary border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">Top</td>';
                            $PDFHTML .= '<td class="border-primary border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">Bottom</td>';
                            $PDFHTML .= '<td class="border-primary border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">Left</td>';
                            $PDFHTML .= '<td class="border-primary border-l whitespace-nowrap text-primary pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">Right</td>';
                        $PDFHTML .= '</tr>';
                        if($gpfrr->count() > 0):
                            $s = 1;
                            foreach($gpfrr as $rad):
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<td class="border-primary border-t whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col2">#'.$s.'&nbsp;'.(isset($rad->rediator_location) && !empty($rad->rediator_location) ? $rad->rediator_location : 'Radiator').'</td>';
                                    $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">'.(isset($rad->tmp_b_top) && !empty($rad->tmp_b_top) ? $rad->tmp_b_top : '').'</td>';
                                    $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">'.(isset($rad->tmp_b_bottom) && !empty($rad->tmp_b_bottom) ? $rad->tmp_b_bottom : '').'</td>';
                                    $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">'.(isset($rad->tmp_b_left) && !empty($rad->tmp_b_left) ? $rad->tmp_b_left : '').'</td>';
                                    $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">'.(isset($rad->tmp_b_right) && !empty($rad->tmp_b_right) ? $rad->tmp_b_right : '').'</td>';
                                    $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">'.(isset($rad->tmp_a_top) && !empty($rad->tmp_a_top) ? $rad->tmp_a_top : '').'</td>';
                                    $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">'.(isset($rad->tmp_a_bottom) && !empty($rad->tmp_a_bottom) ? $rad->tmp_a_bottom : '').'</td>';
                                    $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">'.(isset($rad->tmp_a_left) && !empty($rad->tmp_a_left) ? $rad->tmp_a_left : '').'</td>';
                                    $PDFHTML .= '<td class="border-primary border-t border-l whitespace-nowrap text-primary font-medium pl-2 pr-2 py-05 text-11px leading-none tracking-normal align-middle w-col10-8by-1">'.(isset($rad->tmp_a_right) && !empty($rad->tmp_a_right) ? $rad->tmp_a_right : '').'</td>';
                                $PDFHTML .= '</tr>';

                                $s++;
                            endforeach;
                        endif;
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';


                $inspectionDeate = (isset($gpfr->inspection_date) && !empty($gpfr->inspection_date) ? date('d-m-Y', strtotime($gpfr->inspection_date)) : date('d-m-Y'));
                $nextInspectionDate = (isset($gpfr->next_inspection_date) && !empty($gpfr->next_inspection_date) ? date('d-m-Y', strtotime($gpfr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gpfr->user->name) && !empty($gpfr->user->name) ? $gpfr->user->name : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gpfr->relation->name) && !empty($gpfr->relation->name) ? $gpfr->relation->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gpfr->received_by) && !empty($gpfr->received_by) ? $gpfr->received_by : (isset($gpfr->customer->full_name) && !empty($gpfr->customer->full_name) ? $gpfr->customer->full_name : '')).'</td>';
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


        $fileName = $gpfr->certificate_number.'.pdf';
        if (Storage::disk('public')->exists('gpfr/'.$gpfr->customer_job_id.'/'.$gpfr->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('gpfr/'.$gpfr->customer_job_id.'/'.$gpfr->job_form_id.'/'.$fileName);
        }
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('gpfr/'.$gpfr->customer_job_id.'/'.$gpfr->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('gpfr/'.$gpfr->customer_job_id.'/'.$gpfr->job_form_id.'/'.$fileName);
    }

    public function destroyRediator($gpfrr){
        $GasPowerFlushRecordRediator = GasPowerFlushRecordRediator::find($gpfrr)->forceDelete();

        return response()->json(['msg' => 'Gas Power Flush Rediator record successfully deleted.', 'red' => ''], 200);
    }
}
