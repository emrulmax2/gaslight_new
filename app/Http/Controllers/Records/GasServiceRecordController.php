<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
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
        return view('app.records.'.$record.'.show', [
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

    public function storeAppliance(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $serial = $request->appliance_serial;
        $appliance = (isset($request->app) && !empty($request->app) ? $request->app : []);

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasServiceRecord = GasServiceRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        $saved = 0;
        if($gasServiceRecord->id && isset($appliance[$serial]) && !empty($appliance[$serial])):
            $theAppliance = $appliance[$serial];
            $appliance_location_id = (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null);
            if(!empty($appliance_location_id)):
                $gasAppliance = GasServiceRecordAppliance::updateOrCreate(['gas_service_record_id' => $gasServiceRecord->id, 'appliance_serial' => $serial], [
                    'gas_service_record_id' => $gasServiceRecord->id,
                    'appliance_serial' => $serial,
                    'appliance_location_id' => (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null),
                    'boiler_brand_id' => (isset($theAppliance['boiler_brand_id']) && !empty($theAppliance['boiler_brand_id']) ? $theAppliance['boiler_brand_id'] : null),
                    'model' => (isset($theAppliance['model']) && !empty($theAppliance['model']) ? $theAppliance['model'] : null),
                    'appliance_type_id' => (isset($theAppliance['appliance_type_id']) && !empty($theAppliance['appliance_type_id']) ? $theAppliance['appliance_type_id'] : null),
                    'serial_no' => (isset($theAppliance['serial_no']) && !empty($theAppliance['serial_no']) ? $theAppliance['serial_no'] : null),
                    'gc_no' => (isset($theAppliance['gc_no']) && !empty($theAppliance['gc_no']) ? $theAppliance['gc_no'] : null),
                    
                    'opt_pressure' => (isset($theAppliance['opt_pressure']) && !empty($theAppliance['opt_pressure']) ? $theAppliance['opt_pressure'] : null),
                    'rented_accommodation' => (isset($theAppliance['rented_accommodation']) && !empty($theAppliance['rented_accommodation']) ? $theAppliance['rented_accommodation'] : null),
                    'type_of_work_carried_out' => (isset($theAppliance['type_of_work_carried_out']) && !empty($theAppliance['type_of_work_carried_out']) ? $theAppliance['type_of_work_carried_out'] : null),
                    'test_carried_out' => (isset($theAppliance['test_carried_out']) && !empty($theAppliance['test_carried_out']) ? $theAppliance['test_carried_out'] : null),
                    'is_electricial_bonding' => (isset($theAppliance['is_electricial_bonding']) && !empty($theAppliance['is_electricial_bonding']) ? $theAppliance['is_electricial_bonding'] : null),
                    'low_analyser_ratio' => (isset($theAppliance['low_analyser_ratio']) && !empty($theAppliance['low_analyser_ratio']) ? $theAppliance['low_analyser_ratio'] : null),
                    'low_co' => (isset($theAppliance['low_co']) && !empty($theAppliance['low_co']) ? $theAppliance['low_co'] : null),
                    'low_co2' => (isset($theAppliance['low_co2']) && !empty($theAppliance['low_co2']) ? $theAppliance['low_co2'] : null),
                    'high_analyser_ratio' => (isset($theAppliance['high_analyser_ratio']) && !empty($theAppliance['high_analyser_ratio']) ? $theAppliance['high_analyser_ratio'] : null),
                    'high_co' => (isset($theAppliance['high_co']) && !empty($theAppliance['high_co']) ? $theAppliance['high_co'] : null),
                    'high_co2' => (isset($theAppliance['high_co2']) && !empty($theAppliance['high_co2']) ? $theAppliance['high_co2'] : null),

                    'heat_exchanger' => (isset($theAppliance['heat_exchanger']) && !empty($theAppliance['heat_exchanger']) ? $theAppliance['heat_exchanger'] : null),
                    'heat_exchanger_detail' => (isset($theAppliance['heat_exchanger_detail']) && !empty($theAppliance['heat_exchanger_detail']) ? $theAppliance['heat_exchanger_detail'] : null),
                    'burner_injectors' => (isset($theAppliance['burner_injectors']) && !empty($theAppliance['burner_injectors']) ? $theAppliance['burner_injectors'] : null),
                    'burner_injectors_detail' => (isset($theAppliance['burner_injectors_detail']) && !empty($theAppliance['burner_injectors_detail']) ? $theAppliance['burner_injectors_detail'] : null),
                    'flame_picture' => (isset($theAppliance['flame_picture']) && !empty($theAppliance['flame_picture']) ? $theAppliance['flame_picture'] : null),
                    'flame_picture_detail' => (isset($theAppliance['flame_picture_detail']) && !empty($theAppliance['flame_picture_detail']) ? $theAppliance['flame_picture_detail'] : null),
                    'ignition' => (isset($theAppliance['ignition']) && !empty($theAppliance['ignition']) ? $theAppliance['ignition'] : null),
                    'ignition_detail' => (isset($theAppliance['ignition_detail']) && !empty($theAppliance['ignition_detail']) ? $theAppliance['ignition_detail'] : null),
                    'electrics' => (isset($theAppliance['electrics']) && !empty($theAppliance['electrics']) ? $theAppliance['electrics'] : null),
                    'electrics_detail' => (isset($theAppliance['electrics_detail']) && !empty($theAppliance['electrics_detail']) ? $theAppliance['electrics_detail'] : null),
                    'controls' => (isset($theAppliance['controls']) && !empty($theAppliance['controls']) ? $theAppliance['controls'] : null),
                    'controls_detail' => (isset($theAppliance['controls_detail']) && !empty($theAppliance['controls_detail']) ? $theAppliance['controls_detail'] : null),
                    'leak_gas_water' => (isset($theAppliance['leak_gas_water']) && !empty($theAppliance['leak_gas_water']) ? $theAppliance['leak_gas_water'] : null),
                    'leak_gas_water_detail' => (isset($theAppliance['leak_gas_water_detail']) && !empty($theAppliance['leak_gas_water_detail']) ? $theAppliance['leak_gas_water_detail'] : null),
                    'seals' => (isset($theAppliance['seals']) && !empty($theAppliance['seals']) ? $theAppliance['seals'] : null),
                    'seals_detail' => (isset($theAppliance['seals_detail']) && !empty($theAppliance['seals_detail']) ? $theAppliance['seals_detail'] : null),
                    'pipework' => (isset($theAppliance['pipework']) && !empty($theAppliance['pipework']) ? $theAppliance['pipework'] : null),
                    'pipework_detail' => (isset($theAppliance['pipework_detail']) && !empty($theAppliance['pipework_detail']) ? $theAppliance['pipework_detail'] : null),
                    'fans' => (isset($theAppliance['fans']) && !empty($theAppliance['fans']) ? $theAppliance['fans'] : null),
                    'fans_detail' => (isset($theAppliance['fans_detail']) && !empty($theAppliance['fans_detail']) ? $theAppliance['fans_detail'] : null),
                    'fireplace' => (isset($theAppliance['fireplace']) && !empty($theAppliance['fireplace']) ? $theAppliance['fireplace'] : null),
                    'fireplace_detail' => (isset($theAppliance['fireplace_detail']) && !empty($theAppliance['fireplace_detail']) ? $theAppliance['fireplace_detail'] : null),
                    'closure_plate' => (isset($theAppliance['closure_plate']) && !empty($theAppliance['closure_plate']) ? $theAppliance['closure_plate'] : null),
                    'closure_plate_detail' => (isset($theAppliance['closure_plate_detail']) && !empty($theAppliance['closure_plate_detail']) ? $theAppliance['closure_plate_detail'] : null),
                    'allowable_location' => (isset($theAppliance['allowable_location']) && !empty($theAppliance['allowable_location']) ? $theAppliance['allowable_location'] : null),
                    'allowable_location_detail' => (isset($theAppliance['allowable_location_detail']) && !empty($theAppliance['allowable_location_detail']) ? $theAppliance['allowable_location_detail'] : null),
                    'boiler_ratio' => (isset($theAppliance['boiler_ratio']) && !empty($theAppliance['boiler_ratio']) ? $theAppliance['boiler_ratio'] : null),
                    'boiler_ratio_detail' => (isset($theAppliance['boiler_ratio_detail']) && !empty($theAppliance['boiler_ratio_detail']) ? $theAppliance['boiler_ratio_detail'] : null),
                    'stability' => (isset($theAppliance['stability']) && !empty($theAppliance['stability']) ? $theAppliance['stability'] : null),
                    'stability_detail' => (isset($theAppliance['stability_detail']) && !empty($theAppliance['stability_detail']) ? $theAppliance['stability_detail'] : null),
                    'return_air_ple' => (isset($theAppliance['return_air_ple']) && !empty($theAppliance['return_air_ple']) ? $theAppliance['return_air_ple'] : null),
                    'return_air_ple_detail' => (isset($theAppliance['return_air_ple_detail']) && !empty($theAppliance['return_air_ple_detail']) ? $theAppliance['return_air_ple_detail'] : null),
                    'ventillation' => (isset($theAppliance['ventillation']) && !empty($theAppliance['ventillation']) ? $theAppliance['ventillation'] : null),
                    'ventillation_detail' => (isset($theAppliance['ventillation_detail']) && !empty($theAppliance['ventillation_detail']) ? $theAppliance['ventillation_detail'] : null),
                    'flue_termination' => (isset($theAppliance['flue_termination']) && !empty($theAppliance['flue_termination']) ? $theAppliance['flue_termination'] : null),
                    'flue_termination_detail' => (isset($theAppliance['flue_termination_detail']) && !empty($theAppliance['flue_termination_detail']) ? $theAppliance['flue_termination_detail'] : null),
                    'smoke_pellet_flue_flow' => (isset($theAppliance['smoke_pellet_flue_flow']) && !empty($theAppliance['smoke_pellet_flue_flow']) ? $theAppliance['smoke_pellet_flue_flow'] : null),
                    'smoke_pellet_flue_flow_detail' => (isset($theAppliance['smoke_pellet_flue_flow_detail']) && !empty($theAppliance['smoke_pellet_flue_flow_detail']) ? $theAppliance['smoke_pellet_flue_flow_detail'] : null),
                    'smoke_pellet_spillage' => (isset($theAppliance['smoke_pellet_spillage']) && !empty($theAppliance['smoke_pellet_spillage']) ? $theAppliance['smoke_pellet_spillage'] : null),
                    'smoke_pellet_spillage_detail' => (isset($theAppliance['smoke_pellet_spillage_detail']) && !empty($theAppliance['smoke_pellet_spillage_detail']) ? $theAppliance['smoke_pellet_spillage_detail'] : null),
                    'working_pressure' => (isset($theAppliance['working_pressure']) && !empty($theAppliance['working_pressure']) ? $theAppliance['working_pressure'] : null),
                    'working_pressure_detail' => (isset($theAppliance['working_pressure_detail']) && !empty($theAppliance['working_pressure_detail']) ? $theAppliance['working_pressure_detail'] : null),
                    'savety_devices' => (isset($theAppliance['savety_devices']) && !empty($theAppliance['savety_devices']) ? $theAppliance['savety_devices'] : null),
                    'savety_devices_detail' => (isset($theAppliance['savety_devices_detail']) && !empty($theAppliance['savety_devices_detail']) ? $theAppliance['savety_devices_detail'] : null),
                    'gas_tightness' => (isset($theAppliance['gas_tightness']) && !empty($theAppliance['gas_tightness']) ? $theAppliance['gas_tightness'] : null),
                    'gas_tightness_detail' => (isset($theAppliance['gas_tightness_detail']) && !empty($theAppliance['gas_tightness_detail']) ? $theAppliance['gas_tightness_detail'] : null),
                    'expansion_vassel_checked' => (isset($theAppliance['expansion_vassel_checked']) && !empty($theAppliance['expansion_vassel_checked']) ? $theAppliance['expansion_vassel_checked'] : null),
                    'expansion_vassel_checked_detail' => (isset($theAppliance['expansion_vassel_checked_detail']) && !empty($theAppliance['expansion_vassel_checked_detail']) ? $theAppliance['expansion_vassel_checked_detail'] : null),
                    'other_regulations' => (isset($theAppliance['other_regulations']) && !empty($theAppliance['other_regulations']) ? $theAppliance['other_regulations'] : null),
                    'other_regulations_detail' => (isset($theAppliance['other_regulations_detail']) && !empty($theAppliance['other_regulations_detail']) ? $theAppliance['other_regulations_detail'] : null),
                    'is_safe_to_use' => (isset($theAppliance['is_safe_to_use']) && !empty($theAppliance['is_safe_to_use']) ? $theAppliance['is_safe_to_use'] : null),
                    'instruction_followed' => (isset($theAppliance['instruction_followed']) && !empty($theAppliance['instruction_followed']) ? $theAppliance['instruction_followed'] : null),
                    'work_required_note' => (isset($theAppliance['work_required_note']) && !empty($theAppliance['work_required_note']) ? $theAppliance['work_required_note'] : null),
                    
                    'created_by' => $user_id,
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

        
        $gasServiceRecord = GasServiceRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
            'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
            'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
            'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        
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

        return response()->json(['msg' => 'Gas Service Record Successfully Saved.', 'saved' => 1, 'red' => route('records.gas.service.show', $gasServiceRecord->id)], 200);
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
            $data['status'] = 'Approved';

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

        $signaturePath = $gsr->signature ? storage_path('app/public/' . $gsr->signature->filename) : '';
        $signatureBase64 = (!empty($signaturePath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath)) : '');

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
}
