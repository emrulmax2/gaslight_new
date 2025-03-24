<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\GasSafetyRecord;
use App\Models\GasSafetyRecordAppliance;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeOwnerGasSafetyController extends Controller
{
    public function show($record, GasSafetyRecord $gsr){
        $user_id = auth()->user()->id;
        $gsr->load(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
        $form = JobForm::where('slug', $record)->get()->first();

        if(empty($gsr->certificate_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastCertificate = GasSafetyRecord::where('job_form_id', $form->id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
            $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

            $cerSerial = $starting_form;
            if(!empty($lastCertificateNo)):
                preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                $cerSerial = (int) $certificateNumbers[1] + 1;
            endif;
            $certificateNumber = $prifix.str_pad($cerSerial, 6, '0', STR_PAD_LEFT);
            GasSafetyRecord::where('id', $gsr->id)->update(['certificate_number' => $certificateNumber]);
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
            'gsra1' => GasSafetyRecordAppliance::where('gas_safety_record_id', $gsr->id)->where('appliance_serial', 1)->get()->first(),
            'gsra2' => GasSafetyRecordAppliance::where('gas_safety_record_id', $gsr->id)->where('appliance_serial', 2)->get()->first(),
            'gsra3' => GasSafetyRecordAppliance::where('gas_safety_record_id', $gsr->id)->where('appliance_serial', 3)->get()->first(),
            'gsra4' => GasSafetyRecordAppliance::where('gas_safety_record_id', $gsr->id)->where('appliance_serial', 4)->get()->first(),
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

        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        $saved = 0;
        if($gasSafetyRecord->id && isset($appliance[$serial]) && !empty($appliance[$serial])):
            $theAppliance = $appliance[$serial];
            $appliance_location_id = (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null);
            if(!empty($appliance_location_id)):
                $gasAppliance = GasSafetyRecordAppliance::updateOrCreate(['gas_safety_record_id' => $gasSafetyRecord->id, 'appliance_serial' => $serial], [
                    'gas_safety_record_id' => $gasSafetyRecord->id,
                    'appliance_serial' => $serial,
                    'appliance_location_id' => (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null),
                    'boiler_brand_id' => (isset($theAppliance['boiler_brand_id']) && !empty($theAppliance['boiler_brand_id']) ? $theAppliance['boiler_brand_id'] : null),
                    'model' => (isset($theAppliance['model']) && !empty($theAppliance['model']) ? $theAppliance['model'] : null),
                    'appliance_type_id' => (isset($theAppliance['appliance_type_id']) && !empty($theAppliance['appliance_type_id']) ? $theAppliance['appliance_type_id'] : null),
                    'serial_no' => (isset($theAppliance['serial_no']) && !empty($theAppliance['serial_no']) ? $theAppliance['serial_no'] : null),
                    'gc_no' => (isset($theAppliance['gc_no']) && !empty($theAppliance['gc_no']) ? $theAppliance['gc_no'] : null),
                    'appliance_flue_type_id' => (isset($theAppliance['appliance_flue_type_id']) && !empty($theAppliance['appliance_flue_type_id']) ? $theAppliance['appliance_flue_type_id'] : null),
                    'opt_pressure' => (isset($theAppliance['opt_pressure']) && !empty($theAppliance['opt_pressure']) ? $theAppliance['opt_pressure'] : null),
                    'safety_devices' => (isset($theAppliance['safety_devices']) && !empty($theAppliance['safety_devices']) ? $theAppliance['safety_devices'] : null),
                    'spillage_test' => (isset($theAppliance['spillage_test']) && !empty($theAppliance['spillage_test']) ? $theAppliance['spillage_test'] : null),
                    'smoke_pellet_test' => (isset($theAppliance['smoke_pellet_test']) && !empty($theAppliance['smoke_pellet_test']) ? $theAppliance['smoke_pellet_test'] : null),
                    'low_analyser_ratio' => (isset($theAppliance['low_analyser_ratio']) && !empty($theAppliance['low_analyser_ratio']) ? $theAppliance['low_analyser_ratio'] : null),
                    'low_co' => (isset($theAppliance['low_co']) && !empty($theAppliance['low_co']) ? $theAppliance['low_co'] : null),
                    'low_co2' => (isset($theAppliance['low_co2']) && !empty($theAppliance['low_co2']) ? $theAppliance['low_co2'] : null),
                    'high_analyser_ratio' => (isset($theAppliance['high_analyser_ratio']) && !empty($theAppliance['high_analyser_ratio']) ? $theAppliance['high_analyser_ratio'] : null),
                    'high_co' => (isset($theAppliance['high_co']) && !empty($theAppliance['high_co']) ? $theAppliance['high_co'] : null),
                    'high_co2' => (isset($theAppliance['high_co2']) && !empty($theAppliance['high_co2']) ? $theAppliance['high_co2'] : null),
                    'satisfactory_termination' => (isset($theAppliance['satisfactory_termination']) && !empty($theAppliance['satisfactory_termination']) ? $theAppliance['satisfactory_termination'] : null),
                    'flue_visual_condition' => (isset($theAppliance['flue_visual_condition']) && !empty($theAppliance['flue_visual_condition']) ? $theAppliance['flue_visual_condition'] : null),
                    'adequate_ventilation' => (isset($theAppliance['adequate_ventilation']) && !empty($theAppliance['adequate_ventilation']) ? $theAppliance['adequate_ventilation'] : null),
                    'landlord_appliance' => (isset($theAppliance['landlord_appliance']) && !empty($theAppliance['landlord_appliance']) ? $theAppliance['landlord_appliance'] : null),
                    'inspected' => (isset($theAppliance['inspected']) && !empty($theAppliance['inspected']) ? $theAppliance['inspected'] : null),
                    'appliance_visual_check' => (isset($theAppliance['appliance_visual_check']) && !empty($theAppliance['appliance_visual_check']) ? $theAppliance['appliance_visual_check'] : null),
                    'appliance_serviced' => (isset($theAppliance['appliance_serviced']) && !empty($theAppliance['appliance_serviced']) ? $theAppliance['appliance_serviced'] : null),
                    'appliance_safe_to_use' => (isset($theAppliance['appliance_safe_to_use']) && !empty($theAppliance['appliance_safe_to_use']) ? $theAppliance['appliance_safe_to_use'] : null),
                    
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]);
                $saved = 1;
            endif;

            return response()->json(['msg' => 'Appliance '.$serial.' Details successfully updated.', 'saved' => $saved], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try later or contact with the Administrator.'], 422);
        endif;
    }

    public function storeSatisfactoryCheck(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'satisfactory_visual_inspaction' => (isset($request->satisfactory_visual_inspaction) && !empty($request->satisfactory_visual_inspaction) ? $request->satisfactory_visual_inspaction : null),
            'emergency_control_accessible' => (isset($request->emergency_control_accessible) && !empty($request->emergency_control_accessible) ? $request->emergency_control_accessible : null),
            'satisfactory_gas_tightness_test' => (isset($request->satisfactory_gas_tightness_test) && !empty($request->satisfactory_gas_tightness_test) ? $request->satisfactory_gas_tightness_test : null),
            'equipotential_bonding_satisfactory' => (isset($request->equipotential_bonding_satisfactory) && !empty($request->equipotential_bonding_satisfactory) ? $request->satisfactory_gas_tightness_test : null),
            'co_alarm_fitted' => (isset($request->co_alarm_fitted) && !empty($request->co_alarm_fitted) ? $request->co_alarm_fitted : null),
            'co_alarm_in_date' => (isset($request->co_alarm_in_date) && !empty($request->co_alarm_in_date) ? $request->co_alarm_in_date : null),
            'co_alarm_test_satisfactory' => (isset($request->co_alarm_test_satisfactory) && !empty($request->co_alarm_test_satisfactory) ? $request->co_alarm_test_satisfactory : null),
            'smoke_alarm_fitted' => (isset($request->smoke_alarm_fitted) && !empty($request->smoke_alarm_fitted) ? $request->smoke_alarm_fitted : null),
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        return response()->json(['msg' => 'Customer Details successfully updated.', 'saved' => 1], 200);
    }

    public function storeComments(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'fault_details' => (isset($request->fault_details) && !empty($request->fault_details) ? $request->fault_details : null),
            'rectification_work_carried_out' => (isset($request->rectification_work_carried_out) && !empty($request->rectification_work_carried_out) ? $request->rectification_work_carried_out : null),
            'details_work_carried_out' => (isset($request->details_work_carried_out) && !empty($request->details_work_carried_out) ? $request->details_work_carried_out : null),
            'flue_cap_put_back' => (isset($request->flue_cap_put_back) && !empty($request->flue_cap_put_back) ? $request->flue_cap_put_back : null),
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        return response()->json(['msg' => 'Customer Details successfully updated.', 'saved' => 1], 200);
    }

    public function storeSignatures(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        
        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
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
                $gasSafetyRecord->deleteSignature();
                
                $imageName = 'signatures/' . Str::uuid() . '.png';
                Storage::disk('public')->put($imageName, $signatureData);
                $signature = new Signature();
                $signature->model_type = GasSafetyRecord::class;
                $signature->model_id = $gasSafetyRecord->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $imageName;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
            endif;
        endif;

        return response()->json(['msg' => 'Customer Details successfully updated.', 'saved' => 1, 'red' => route('records.gsr.view', [$form->slug, $gasSafetyRecord->id])], 200);
    }

    public function store(Request $request){
        $gsr_id = $request->gsr_id;
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $submit_type = $request->submit_type;
        $gsr = GasSafetyRecord::find($gsr_id);

        $red = '';
        $pdf = Storage::disk('public')->url('gsr/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$gsr->certificate_number.'.pdf');
        $message = '';
        $pdf = $this->generatePdf($gsr_id);
        if($submit_type == 2):
            $data = [];
            $data['status'] = 'Approved';

            GasSafetyRecord::where('id', $gsr_id)->update($data);
            
            $email = $this->sendEmail($gsr_id, $job_form_id);
            $message = (!$email ? 'Gas Safety Certificate has been approved. Email cannot be sent due to an invalid or empty email address.' : 'Gas Safety Certificate has been approved and a copy of the certificate mailed to the customer');
        else:
            $data = [];
            $data['status'] = 'Approved';

            GasSafetyRecord::where('id', $gsr_id)->update($data);
            $message = 'Homewoner Gas Safety Certificate successfully approved.';
        endif;

        return response()->json(['msg' => $message, 'red' => route('company.dashboard'), 'pdf' => $pdf]);
    }

    public function generatePdf($gsr_id) {
        $user_id = auth()->user()->id;
        $gsr = GasSafetyRecord::with('customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company')->find($gsr_id);
        $gsra1 = GasSafetyRecordAppliance::where('gas_safety_record_id', $gsr->id)->where('appliance_serial', 1)->get()->first();
        $gsra2 = GasSafetyRecordAppliance::where('gas_safety_record_id', $gsr->id)->where('appliance_serial', 2)->get()->first();
        $gsra3 = GasSafetyRecordAppliance::where('gas_safety_record_id', $gsr->id)->where('appliance_serial', 3)->get()->first();
        $gsra4 = GasSafetyRecordAppliance::where('gas_safety_record_id', $gsr->id)->where('appliance_serial', 4)->get()->first();

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
                                .w-50-percent{width: 50%;}
                                .w-25-percent{width: 25%;}
                                .w-41-percent{width: 41%;}
                                .w-20-percent{width: 20%;}
                                .w-col2{width: 16.666666%;}
                                .w-col4{width: 33.333333%;}
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
                                .p-3{padding: 0.75rem;}
                                .p-5{padding: 1.25rem;}
                                .py-05{padding-top: 0.125rem;padding-bottom: 0.125rem;}
                                .py-1{padding-top: 0.25rem;padding-bottom: 0.25rem;}
                                .py-2{padding-top: 0.5rem;padding-bottom: 0.5rem;}
                                .py-3{padding-top: 0.75rem;padding-bottom: 0.75rem;}
                                .px-5{padding-left: 1.25rem;padding-right: 1.25rem;}
                                .px-2{padding-left: 0.5rem;padding-right: 0.5rem;}
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

                $PDFHTML .= '<div class="header bg-primary p-3">';
                    $PDFHTML .= '<table class="grid grid-cols-12 gap-4 items-center">';
                        $PDFHTML .= '<tbody>';
                            $PDFHTML .= '<tr>';
                                $PDFHTML .= '<td class="w-col2 align-middle">';
                                    $PDFHTML .= '<img class="w-auto h-80px" src="'.$logoBase64.'" alt="Gas Safe Register Logo">';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col8 text-center align-middle px-5">';
                                    $PDFHTML .= '<h1 class="text-white text-xl leading-none mt-0 mb-05">Homeowner / Landlord Gas Safety Record</h1>';
                                    $PDFHTML .= '<div class="text-white text-12px leading-1-3">';
                                        $PDFHTML .= 'This inspection is for gas safety purposes only to comply with the Gas Safety (Installation and Use) Regulations. Flues have been inspected visually and checked for satisfactory evacuation of products of combustion
                                                    A detailed internal inspection of the flue integrity, construction and lining has NOT been carried out.
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
                            $PDFHTML .= '<td class="w-col9 pr-1 pl-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="8" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'APPLIANCE DETAILS';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center w-36px align-middle">';
                                                $PDFHTML .= '#';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Location';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Make';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Model';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Type';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Serial No.';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'Flue Type';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 text-white text-10px uppercase px-2 py-05 text-center w-140px leading-none align-middle">';
                                                $PDFHTML .= 'OPERATING PRESSURE (MBAR) OR HEAT INPUT (KW/H OR BTU/H)';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">1</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->location->name) && !empty($gsra1->location->name) ? $gsra1->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->make->name) && !empty($gsra1->make->name) ? $gsra1->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->model) && !empty($gsra1->model) ? $gsra1->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->type->name) && !empty($gsra1->type->name) ? $gsra1->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->serial_no) && !empty($gsra1->serial_no) ? $gsra1->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->flue->name) && !empty($gsra1->flue->name) ? $gsra1->flue->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-140px border-b">'.(isset($gsra1->opt_pressure) && !empty($gsra1->opt_pressure) ? $gsra1->opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">2</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->location->name) && !empty($gsra2->location->name) ? $gsra2->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->make->name) && !empty($gsra2->make->name) ? $gsra2->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->model) && !empty($gsra2->model) ? $gsra2->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->type->name) && !empty($gsra2->type->name) ? $gsra2->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->serial_no) && !empty($gsra2->serial_no) ? $gsra2->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->flue->name) && !empty($gsra2->flue->name) ? $gsra2->flue->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-140px border-b">'.(isset($gsra2->opt_pressure) && !empty($gsra2->opt_pressure) ? $gsra2->opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">3</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->location->name) && !empty($gsra3->location->name) ? $gsra3->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->make->name) && !empty($gsra3->make->name) ? $gsra3->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->model) && !empty($gsra3->model) ? $gsra3->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->type->name) && !empty($gsra3->type->name) ? $gsra3->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->serial_no) && !empty($gsra3->serial_no) ? $gsra3->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->flue->name) && !empty($gsra3->flue->name) ? $gsra3->flue->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-140px border-b">'.(isset($gsra3->opt_pressure) && !empty($gsra3->opt_pressure) ? $gsra3->opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">4</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->location->name) && !empty($gsra4->location->name) ? $gsra4->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->make->name) && !empty($gsra4->make->name) ? $gsra4->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->model) && !empty($gsra4->model) ? $gsra4->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->type->name) && !empty($gsra4->type->name) ? $gsra4->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->serial_no) && !empty($gsra4->serial_no) ? $gsra4->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->flue->name) && !empty($gsra4->flue->name) ? $gsra4->flue->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-140px border-b">'.(isset($gsra4->opt_pressure) && !empty($gsra4->opt_pressure) ? $gsra4->opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="10" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'FLUE TESTS';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th rowspan="2" class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px uppercase px-2 py-1 text-center w-36px">';
                                                $PDFHTML .= '#';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th rowspan="2" class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px uppercase px-2 py-1 text-center leading-1-5">';
                                                $PDFHTML .= 'SAFETY DEVICE(S) <br/>CORRECT OPERATION';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th rowspan="2" class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px uppercase px-2 py-1 text-center">';
                                                $PDFHTML .= 'SPILLAGE TEST';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th rowspan="2" class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px uppercase px-2 py-1 text-center leading-1-5">';
                                                $PDFHTML .= 'SMOKE PELLET <br/>FLUE FLOW TEST';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th colspan="3" class="whitespace-nowrap border-primary bg-primary border-b-1 border-r border-r-sec border-b-sec text-white text-10px uppercase px-2 py-1 text-center leading-none">';
                                                $PDFHTML .= 'INITIAL (LOW) COMBUSTION ANALYSER READING';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th colspan="3" class="whitespace-nowrap border-primary bg-primary border-b-1 border-r border-r-sec border-b-sec text-white text-11px uppercase px-2 py-1 text-center leading-none">';
                                                $PDFHTML .= 'FINAL (HIGH) COMBUSTION ANALYSER READING';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px uppercase px-2 py-1 text-center leading-none">';
                                                $PDFHTML .= 'RATIO';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px uppercase px-2 py-1 text-center leading-none">';
                                                $PDFHTML .= 'CO PPM';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px uppercase px-2 py-1 text-center leading-none">';
                                                $PDFHTML .= 'CO2 (%)';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px uppercase px-2 py-1 text-center leading-none">';
                                                $PDFHTML .= 'RATIO';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px uppercase px-2 py-1 text-center leading-none">';
                                                $PDFHTML .= 'CO PPM';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-0 text-white text-11px uppercase px-2 py-1 text-center leading-none">';
                                                $PDFHTML .= 'CO2 (%)';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">1</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->safety_devices) && !empty($gsra1->safety_devices) ? $gsra1->safety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->spillage_test) && !empty($gsra1->spillage_test) ? $gsra1->spillage_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->smoke_pellet_test) && !empty($gsra1->smoke_pellet_test) ? $gsra1->smoke_pellet_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra1->low_analyser_ratio) && !empty($gsra1->low_analyser_ratio) ? $gsra1->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra1->low_co) && !empty($gsra1->low_co) ? $gsra1->low_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra1->low_co2) && !empty($gsra1->low_co2) ? $gsra1->low_co2 : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra1->high_analyser_ratio) && !empty($gsra1->high_analyser_ratio) ? $gsra1->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra1->high_co) && !empty($gsra1->high_co) ? $gsra1->high_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b">'.(isset($gsra1->high_co2) && !empty($gsra1->high_co2) ? $gsra1->high_co2 : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">2</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->safety_devices) && !empty($gsra2->safety_devices) ? $gsra2->safety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->spillage_test) && !empty($gsra2->spillage_test) ? $gsra2->spillage_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->smoke_pellet_test) && !empty($gsra2->smoke_pellet_test) ? $gsra2->smoke_pellet_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra2->low_analyser_ratio) && !empty($gsra2->low_analyser_ratio) ? $gsra2->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra2->low_co) && !empty($gsra2->low_co) ? $gsra2->low_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra2->low_co2) && !empty($gsra2->low_co2) ? $gsra2->low_co2 : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra2->high_analyser_ratio) && !empty($gsra2->high_analyser_ratio) ? $gsra2->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra2->high_co) && !empty($gsra2->high_co) ? $gsra2->high_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b">'.(isset($gsra2->high_co2) && !empty($gsra2->high_co2) ? $gsra2->high_co2 : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">3</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->safety_devices) && !empty($gsra3->safety_devices) ? $gsra3->safety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->spillage_test) && !empty($gsra3->spillage_test) ? $gsra3->spillage_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->smoke_pellet_test) && !empty($gsra3->smoke_pellet_test) ? $gsra3->smoke_pellet_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra3->low_analyser_ratio) && !empty($gsra3->low_analyser_ratio) ? $gsra3->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra3->low_co) && !empty($gsra3->low_co) ? $gsra3->low_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra3->low_co2) && !empty($gsra3->low_co2) ? $gsra3->low_co2 : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra3->high_analyser_ratio) && !empty($gsra3->high_analyser_ratio) ? $gsra3->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra3->high_co) && !empty($gsra3->high_co) ? $gsra3->high_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b">'.(isset($gsra3->high_co2) && !empty($gsra3->high_co2) ? $gsra3->high_co2 : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">4</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->safety_devices) && !empty($gsra4->safety_devices) ? $gsra4->safety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->spillage_test) && !empty($gsra4->spillage_test) ? $gsra4->spillage_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->smoke_pellet_test) && !empty($gsra4->smoke_pellet_test) ? $gsra4->smoke_pellet_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra4->low_analyser_ratio) && !empty($gsra4->low_analyser_ratio) ? $gsra4->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra4->low_co) && !empty($gsra4->low_co) ? $gsra4->low_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra4->low_co2) && !empty($gsra4->low_co2) ? $gsra4->low_co2 : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra4->high_analyser_ratio) && !empty($gsra4->high_analyser_ratio) ? $gsra4->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($gsra4->high_co) && !empty($gsra4->high_co) ? $gsra4->high_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b">'.(isset($gsra4->high_co2) && !empty($gsra4->high_co2) ? $gsra4->high_co2 : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';

                                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="8" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">';
                                                $PDFHTML .= 'INSPECTION DETAILS';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px uppercase px-2 py-05 text-center w-36px leading-1-5">';
                                                $PDFHTML .= '#';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                               $PDFHTML .= 'FLUE VISUAL <br/>CONDITION';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'ADEQUATE <br/>VENTILATION';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'LANDLORD\'S <br/>APPLIANCE';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'INSPECTED';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'APPLIANCE <br/>VISUAL CHECK';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'APPLIANCE <br/>SERVICED';
                                            $PDFHTML .= '</th>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center align-middle">';
                                                $PDFHTML .= 'APPLIANCE <br/>SAFE TO USE';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">1</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->flue_visual_condition) && !empty($gsra1->flue_visual_condition) ? $gsra1->flue_visual_condition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->adequate_ventilation) && !empty($gsra1->adequate_ventilation) ? $gsra1->adequate_ventilation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->landlord_appliance) && !empty($gsra1->landlord_appliance) ? $gsra1->landlord_appliance : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->inspected) && !empty($gsra1->inspected) ? $gsra1->inspected : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->appliance_visual_check) && !empty($gsra1->appliance_visual_check) ? $gsra1->appliance_visual_check : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra1->appliance_serviced) && !empty($gsra1->appliance_serviced) ? $gsra1->appliance_serviced : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b w-140px">'.(isset($gsra1->appliance_safe_to_use) && !empty($gsra1->appliance_safe_to_use) ? $gsra1->appliance_safe_to_use : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">2</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->flue_visual_condition) && !empty($gsra2->flue_visual_condition) ? $gsra2->flue_visual_condition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->adequate_ventilation) && !empty($gsra2->adequate_ventilation) ? $gsra2->adequate_ventilation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->landlord_appliance) && !empty($gsra2->landlord_appliance) ? $gsra2->landlord_appliance : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->inspected) && !empty($gsra2->inspected) ? $gsra2->inspected : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->appliance_visual_check) && !empty($gsra2->appliance_visual_check) ? $gsra2->appliance_visual_check : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra2->appliance_serviced) && !empty($gsra2->appliance_serviced) ? $gsra2->appliance_serviced : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b w-140px">'.(isset($gsra2->appliance_safe_to_use) && !empty($gsra2->appliance_safe_to_use) ? $gsra2->appliance_safe_to_use : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">3</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->flue_visual_condition) && !empty($gsra3->flue_visual_condition) ? $gsra3->flue_visual_condition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->adequate_ventilation) && !empty($gsra3->adequate_ventilation) ? $gsra3->adequate_ventilation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->landlord_appliance) && !empty($gsra3->landlord_appliance) ? $gsra3->landlord_appliance : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->inspected) && !empty($gsra3->inspected) ? $gsra3->inspected : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->appliance_visual_check) && !empty($gsra3->appliance_visual_check) ? $gsra3->appliance_visual_check : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra3->appliance_serviced) && !empty($gsra3->appliance_serviced) ? $gsra3->appliance_serviced : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b w-140px">'.(isset($gsra3->appliance_safe_to_use) && !empty($gsra3->appliance_safe_to_use) ? $gsra3->appliance_safe_to_use : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">4</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->flue_visual_condition) && !empty($gsra4->flue_visual_condition) ? $gsra4->flue_visual_condition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->adequate_ventilation) && !empty($gsra4->adequate_ventilation) ? $gsra4->adequate_ventilation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->landlord_appliance) && !empty($gsra4->landlord_appliance) ? $gsra4->landlord_appliance : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->inspected) && !empty($gsra4->inspected) ? $gsra4->inspected : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->appliance_visual_check) && !empty($gsra4->appliance_visual_check) ? $gsra4->appliance_visual_check : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($gsra4->appliance_serviced) && !empty($gsra4->appliance_serviced) ? $gsra4->appliance_serviced : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b w-140px">'.(isset($gsra4->appliance_safe_to_use) && !empty($gsra4->appliance_safe_to_use) ? $gsra4->appliance_safe_to_use : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col3 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">';
                                                $PDFHTML .= 'GAS INSTALLATION PIPEWORK';
                                            $PDFHTML .= '</th>';
                                       $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-1 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">SATISFACTORY VISUAL INSPECTION</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($gsr->satisfactory_visual_inspaction) && !empty($gsr->satisfactory_visual_inspaction) ? $gsr->satisfactory_visual_inspaction : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-1 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">EMERGENCY CONTROL ACCESSIBLE</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($gsr->emergency_control_accessible) && !empty($gsr->emergency_control_accessible) ? $gsr->emergency_control_accessible : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-1 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">SATISFACTORY GAS TIGHTNESS TEST</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($gsr->satisfactory_gas_tightness_test) && !empty($gsr->satisfactory_gas_tightness_test) ? $gsr->satisfactory_gas_tightness_test : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-1 py-05 text-10px tracking-normal text-left leading-1-5 border-b border-r">EQUIPOTENTIAL BONDING SATISFACTION</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($gsr->equipotential_bonding_satisfactory) && !empty($gsr->equipotential_bonding_satisfactory) ? $gsr->equipotential_bonding_satisfactory : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';

                                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th colspan="2" class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">';
                                                $PDFHTML .= 'AUDIBLE CO ALARM';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">APPROVED CO ALARMS FITTED</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($gsr->co_alarm_fitted) && !empty($gsr->co_alarm_fitted) ? $gsr->co_alarm_fitted : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">ARE CO ALARMS IN DATE</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($gsr->co_alarm_in_date) && !empty($gsr->co_alarm_in_date) ? $gsr->co_alarm_in_date : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">TESTING CO ALARMS</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($gsr->co_alarm_test_satisfactory) && !empty($gsr->co_alarm_test_satisfactory) ? $gsr->co_alarm_test_satisfactory : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">SMOKE ALARMS FITTED</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($gsr->smoke_alarm_fitted) && !empty($gsr->smoke_alarm_fitted) ? $gsr->smoke_alarm_fitted : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';

                                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">';
                                                $PDFHTML .= 'GIVE DETAILS OF ANY FAULTS';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 align-top h-83px">'.(isset($gsr->fault_details) && !empty($gsr->fault_details) ? $gsr->fault_details : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">';
                                                $PDFHTML .= 'RECTIFICATION WORK CARRIED OUT';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 align-top  h-83px">'.(isset($gsr->rectification_work_carried_out) && !empty($gsr->rectification_work_carried_out) ? $gsr->rectification_work_carried_out : '').'</td>';
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
                            $PDFHTML .= '<td class="w-col3 pr-1 pl-0 pb-0 pt-0 align-top">';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
                                    $PDFHTML .= '<thead>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<th class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">';
                                                $PDFHTML .= 'DETAILS OF WORKS';
                                            $PDFHTML .= '</th>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</thead>';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 h-60px align-top">'.(isset($gsr->details_work_carried_out) && !empty($gsr->details_work_carried_out) ? $gsr->details_work_carried_out : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-12px uppercase px-2 py-1 leading-none align-middle">HAS FLUE CAP BEEN PUT BACK?</td>';
                                            $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-1 text-12px w-130px leading-none align-middle">'.(isset($gsr->flue_cap_put_back) && !empty($gsr->flue_cap_put_back) ? $gsr->flue_cap_put_back : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col9 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $inspectionDeate = (isset($gsr->inspection_date) && !empty($gsr->inspection_date) ? date('d-m-Y', strtotime($gsr->inspection_date)) : date('d-m-Y'));
                                $nextInspectionDate = (isset($gsr->next_inspection_date) && !empty($gsr->next_inspection_date) ? date('d-m-Y', strtotime($gsr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                                
                                $PDFHTML .= '<table class="table table-sm bordered border-primary">';
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
                                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-50px align-top"></td>';
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
                                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($gsr->job->property->occupant_name) && !empty($gsr->job->property->occupant_name) ? $gsr->job->property->occupant_name : (isset($gsr->customer->full_name) && !empty($gsr->customer->full_name) ? $gsr->customer->full_name : '')).'</td>';
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
                                $PDFHTML .= '</x-base.table>';

                            $PDFHTML .= '</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

            $PDFHTML .= '</body>';
        $PDFHTML .= '</html>';


        $fileName = $gsr->certificate_number.'.pdf';
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('gsr/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('gsr/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName, $content);
    }

    public function sendEmail($gsr_id, $job_form_id){
        $user_id = auth()->user()->id;
        $gsr = GasSafetyRecord::with('job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($gsr_id);
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
            $attachmentFiles[] = [
                "pathinfo" => 'gsr/'.$gsr->customer_job_id.'/'.$gsr->job_form_id.'/'.$fileName,
                "nameinfo" => $fileName,
                "mimeinfo" => 'application/pdf',
                "disk" => 'public'
            ];

            GCEMailerJob::dispatch($configuration, $sendTo, new GCESendMail($subject, $content, $attachmentFiles));
            return true;
        else:
            return false;
        endif;
    }
}
