<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasUnventedHotWaterCylinderRecord;
use App\Models\GasUnventedHotWaterCylinderRecordInspection;
use App\Models\GasUnventedHotWaterCylinderRecordSystem;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class GasUnventedHotWaterCylinderRecordController extends Controller
{
    public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasUnventedHotWaterCylinderRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasUnventedHotWaterCylinderRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasUnventedHotWaterCylinderRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request){
        $user_id = $request->user()->id;
        $job_form_id = 17;
        $form = JobForm::find($job_form_id);

        $certificate_id = (isset($request->certificate_id) && $request->certificate_id > 0 ? $request->certificate_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $property = CustomerProperty::find($customer_property_id);
        
        $unventedSystems = $request->unventedSystems;
        $inspectionRecords = $request->inspectionRecords;

        if($customer_job_id == 0):
            $customerJob = CustomerJob::create([
                'customer_id' => $customer_id,
                'customer_property_id' => $customer_property_id,
                'description' => $form->name,
                'details' => 'Job created for '.$property->full_address,

                'created_by' => $request->user()->id
            ]);
            $customer_job_id = ($customerJob->id ? $customerJob->id : $customer_job_id);
        endif;

        if($customer_job_id > 0):
            $gasUnvenHWCRecord = GasUnventedHotWaterCylinderRecord::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            $this->checkAndUpdateRecordHistory($gasUnvenHWCRecord->id);

            if($gasUnvenHWCRecord->id):
                $gasUnvenHWCRecordSystem = GasUnventedHotWaterCylinderRecordSystem::updateOrCreate(['gas_unvented_hot_water_cylinder_record_id' => $gasUnvenHWCRecord->id], [
                    'gas_unvented_hot_water_cylinder_record_id' => $gasUnvenHWCRecord->id,
                    
                    'type' => (isset($unventedSystems->type) && !empty($unventedSystems->type) ? $unventedSystems->type : null),
                    'make' => (isset($unventedSystems->make) && !empty($unventedSystems->make) ? $unventedSystems->make : null),
                    'model' => (isset($unventedSystems->model) && !empty($unventedSystems->model) ? $unventedSystems->model : null),
                    'location' => (isset($unventedSystems->location) && !empty($unventedSystems->location) ? $unventedSystems->location : null),
                    'serial_no' => (isset($unventedSystems->serial_no) && !empty($unventedSystems->serial_no) ? $unventedSystems->serial_no : null),
                    'gc_number' => (isset($unventedSystems->gc_number) && !empty($unventedSystems->gc_number) ? $unventedSystems->gc_number : null),
                    'direct_or_indirect' => (isset($unventedSystems->direct_or_indirect) && !empty($unventedSystems->direct_or_indirect) ? $unventedSystems->direct_or_indirect : null),
                    'boiler_solar_immersion' => (isset($unventedSystems->boiler_solar_immersion) && !empty($unventedSystems->boiler_solar_immersion) ? $unventedSystems->boiler_solar_immersion : null),
                    'capacity' => (isset($unventedSystems->capacity) && !empty($unventedSystems->capacity) ? $unventedSystems->capacity : null),
                    'warning_label_attached' => (isset($unventedSystems->warning_label_attached) && !empty($unventedSystems->warning_label_attached) ? $unventedSystems->warning_label_attached : null),
                    'water_pressure' => (isset($unventedSystems->water_pressure) && !empty($unventedSystems->water_pressure) ? $unventedSystems->water_pressure : null),
                    'flow_rate' => (isset($unventedSystems->flow_rate) && !empty($unventedSystems->flow_rate) ? $unventedSystems->flow_rate : null),
                    'fully_commissioned' => (isset($unventedSystems->fully_commissioned) && !empty($unventedSystems->fully_commissioned) ? $unventedSystems->fully_commissioned : null),
                    
                    'updated_by' => $user_id,
                ]);
                $gasUnvenHWCRecordInspection = GasUnventedHotWaterCylinderRecordInspection::updateOrCreate(['gas_unvented_hot_water_cylinder_record_id' => $gasUnvenHWCRecord->id], [
                    'gas_unvented_hot_water_cylinder_record_id' => $gasUnvenHWCRecord->id,
                    
                    'system_opt_pressure' => (isset($inspectionRecords->system_opt_pressure) && !empty($inspectionRecords->system_opt_pressure) ? $inspectionRecords->system_opt_pressure : null),
                    'opt_presure_exp_vsl' => (isset($inspectionRecords->opt_presure_exp_vsl) && !empty($inspectionRecords->opt_presure_exp_vsl) ? $inspectionRecords->opt_presure_exp_vsl : null),
                    'opt_presure_exp_vlv' => (isset($inspectionRecords->opt_presure_exp_vlv) && !empty($inspectionRecords->opt_presure_exp_vlv) ? $inspectionRecords->opt_presure_exp_vlv : null),
                    'tem_relief_vlv' => (isset($inspectionRecords->tem_relief_vlv) && !empty($inspectionRecords->tem_relief_vlv) ? $inspectionRecords->tem_relief_vlv : null),
                    'opt_temperature' => (isset($inspectionRecords->opt_temperature) && !empty($inspectionRecords->opt_temperature) ? $inspectionRecords->opt_temperature : null),
                    'combined_temp_presr' => (isset($inspectionRecords->combined_temp_presr) && !empty($inspectionRecords->combined_temp_presr) ? $inspectionRecords->combined_temp_presr : null),
                    'max_circuit_presr' => (isset($inspectionRecords->max_circuit_presr) && !empty($inspectionRecords->max_circuit_presr) ? $inspectionRecords->max_circuit_presr : null),
                    'flow_temp' => (isset($inspectionRecords->flow_temp) && !empty($inspectionRecords->flow_temp) ? $inspectionRecords->flow_temp : null),
                    'd1_mormal_size' => (isset($inspectionRecords->d1_mormal_size) && !empty($inspectionRecords->d1_mormal_size) ? $inspectionRecords->d1_mormal_size : null),
                    'd1_length' => (isset($inspectionRecords->d1_length) && !empty($inspectionRecords->d1_length) ? $inspectionRecords->d1_length : null),
                    'd1_discharges_no' => (isset($inspectionRecords->d1_discharges_no) && !empty($inspectionRecords->d1_discharges_no) ? $inspectionRecords->d1_discharges_no : null),
                    'd1_manifold_size' => (isset($inspectionRecords->d1_manifold_size) && !empty($inspectionRecords->d1_manifold_size) ? $inspectionRecords->d1_manifold_size : null),
                    'd1_is_tundish_install_same_location' => (isset($inspectionRecords->d1_is_tundish_install_same_location) && !empty($inspectionRecords->d1_is_tundish_install_same_location) ? $inspectionRecords->d1_is_tundish_install_same_location : null),
                    'd1_is_tundish_visible' => (isset($inspectionRecords->d1_is_tundish_visible) && !empty($inspectionRecords->d1_is_tundish_visible) ? $inspectionRecords->d1_is_tundish_visible : null),
                    'd1_is_auto_dis_intall' => (isset($inspectionRecords->d1_is_auto_dis_intall) && !empty($inspectionRecords->d1_is_auto_dis_intall) ? $inspectionRecords->d1_is_auto_dis_intall : null),
                    'd2_mormal_size' => (isset($inspectionRecords->d2_mormal_size) && !empty($inspectionRecords->d2_mormal_size) ? $inspectionRecords->d2_mormal_size : null),
                    'd2_pipework_material' => (isset($inspectionRecords->d2_pipework_material) && !empty($inspectionRecords->d2_pipework_material) ? $inspectionRecords->d2_pipework_material : null),
                    'd2_minimum_v_length' => (isset($inspectionRecords->d2_minimum_v_length) && !empty($inspectionRecords->d2_minimum_v_length) ? $inspectionRecords->d2_minimum_v_length : null),
                    'd2_fall_continuously' => (isset($inspectionRecords->d2_fall_continuously) && !empty($inspectionRecords->d2_fall_continuously) ? $inspectionRecords->d2_fall_continuously : null),
                    'd2_termination_method' => (isset($inspectionRecords->d2_termination_method) && !empty($inspectionRecords->d2_termination_method) ? $inspectionRecords->d2_termination_method : null),
                    'd2_termination_satisfactory' => (isset($inspectionRecords->d2_termination_satisfactory) && !empty($inspectionRecords->d2_termination_satisfactory) ? $inspectionRecords->d2_termination_satisfactory : null),
                    'comments' => (isset($inspectionRecords->comments) && !empty($inspectionRecords->comments) ? $inspectionRecords->comments : null),
                    
                    'updated_by' => $user_id,
                ]);
            endif;

            if($request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $gasUnvenHWCRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    $signature = new Signature();
                    $signature->model_type = GasUnventedHotWaterCylinderRecord::class;
                    $signature->model_id = $gasUnvenHWCRecord->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([request()->ip()]);
                    $signature->save();
                endif;
            endif;

            return response()->json([
                'success' => true,
                'message' => $gasUnvenHWCRecord->wasRecentlyCreated ? 'Certificate successfully created' : 'Certificate successfully updated',
                'data' => GasUnventedHotWaterCylinderRecord::with(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'signature'])->findOrFail($gasUnvenHWCRecord->id)
                ], 200);
        else:
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator.'
            ], 304);
        endif;
    }

    public function getDetails(Request $request, $record_id){
        try {
            $guhwc_record = GasUnventedHotWaterCylinderRecord::with(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company'])->findOrFail($record_id);
            $user_id = $request->user_id;
            $guhwc_record->load(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
            $form = JobForm::find($guhwc_record->job_form_id);
            $record = $form->slug;

            if(empty($guhwc_record->certificate_number)):
                $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
                $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
                $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
                $userLastCertificate = GasUnventedHotWaterCylinderRecord::where('job_form_id', $form->id)->where('created_by', $user_id)->where('id', '!=', $guhwc_record->id)->orderBy('id', 'DESC')->get()->first();
                $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

                $cerSerial = $starting_form;
                if(!empty($lastCertificateNo)):
                    preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                    $cerSerial = isset($certificateNumbers[1]) ? ((int) $certificateNumbers[1]) + 1 : $starting_form;
                endif;
                $certificateNumber = $prifix . $cerSerial;
                GasUnventedHotWaterCylinderRecord::where('id', $guhwc_record->id)->update(['certificate_number' => $certificateNumber]);
            endif;

            $thePdf = $this->generatePdf($guhwc_record->id);

          return response()->json([
                'success' => true,
                'data' => [
                    'form' => $form,
                    'guhwcr' => $guhwc_record,
                    'guhwcrs' => GasUnventedHotWaterCylinderRecordSystem::where('gas_unvented_hot_water_cylinder_record_id', $guhwc_record->id)->orderBy('id', 'DESC')->get()->first(),
                    'guhwcri' => GasUnventedHotWaterCylinderRecordInspection::where('gas_unvented_hot_water_cylinder_record_id', $guhwc_record->id)->orderBy('id', 'DESC')->get()->first(),
                    'signature' => $guhwc_record->signature ? Storage::disk('public')->url($guhwc_record->signature->filename) : '',
                    'pdf_url' => $thePdf
                ]
            ], 200);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gas unvented hot water cylinder record not found. . The requested gas unvented hot water cylinder record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

     public function sendEmail($guhwcr_id, $job_form_id, $user_id){
        $guhwcr = GasUnventedHotWaterCylinderRecord::with('job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($guhwcr_id);
        $customerName = (isset($guhwcr->customer->full_name) && !empty($guhwcr->customer->full_name) ? $guhwcr->customer->full_name : '');
        $customerEmail = (isset($guhwcr->customer->contact->email) && !empty($guhwcr->customer->contact->email) ? $guhwcr->customer->contact->email : '');
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

            $fileName = $guhwcr->certificate_number.'.pdf';
            $attachmentFiles = [];
            if (Storage::disk('public')->exists('guhwcr/'.$guhwcr->customer_job_id.'/'.$guhwcr->job_form_id.'/'.$fileName)):
                $attachmentFiles[] = [
                    "pathinfo" => 'guhwcr/'.$guhwcr->customer_job_id.'/'.$guhwcr->job_form_id.'/'.$fileName,
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

    public function generatePdf($guhwcr_id) {
        $guhwcr = GasUnventedHotWaterCylinderRecord::with('customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company')->find($guhwcr_id);
        $guhwcrs = GasUnventedHotWaterCylinderRecordSystem::where('gas_unvented_hot_water_cylinder_record_id', $guhwcr->id)->orderBy('id', 'DESC')->get()->first();
        $guhwcri = GasUnventedHotWaterCylinderRecordInspection::where('gas_unvented_hot_water_cylinder_record_id', $guhwcr->id)->orderBy('id', 'DESC')->get()->first();

        $logoPath = resource_path('images/gas_safe_register_dark.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $userSignBase64 = (isset($guhwcr->user->signature) && Storage::disk('public')->exists($guhwcr->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($guhwcr->user->signature->filename)) : '');
        $signatureBase64 = ($guhwcr->signature && Storage::disk('public')->exists($guhwcr->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($guhwcr->signature->filename)) : '');
        

        $report_title = 'Certificate of '.$guhwcr->certificate_number;
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
                                    $PDFHTML .= '<h1 class="text-white text-xl leading-none mt-0 mb-05">Mains Pressure Hot Water Cylinder Commissioning Checklist</h1>';
                                    $PDFHTML .= '<div class="text-white text-12px leading-1-3">';
                                        $PDFHTML .= 'Registered Business/engineer details can be checked at www.gassaferegister.co.uk or by calling 0800 408 5500';
                                    $PDFHTML .= '</div>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col2 align-middle text-center">';
                                    $PDFHTML .= '<label class="text-white uppercase font-medium text-12px leading-none mb-2 inline-block">Certificate Number</label>';
                                    $PDFHTML .= '<div class="inline-block bg-white w-32 text-center rounded-none leading-28px h-35px font-medium text-primary">'.$guhwcr->certificate_number.'</div>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($guhwcr->user->name) && !empty($guhwcr->user->name) ? $guhwcr->user->name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">GAS SAFE REG.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->user->company->gas_safe_registration_no) && !empty($guhwcr->user->company->gas_safe_registration_no) ? $guhwcr->user->company->gas_safe_registration_no : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">ID CARD NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->user->gas_safe_id_card) && !empty($guhwcr->user->gas_safe_id_card) ? $guhwcr->user->gas_safe_id_card : '').'</td>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->user->company->company_name) && !empty($guhwcr->user->company->company_name) ? $guhwcr->user->company->company_name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($guhwcr->user->company->pdf_address) && !empty($guhwcr->user->company->pdf_address) ? $guhwcr->user->company->pdf_address : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">TEL NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->user->company->company_phone) && !empty($guhwcr->user->company->company_phone) ? $guhwcr->user->company->company_phone : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Email</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->user->company->company_email) && !empty($guhwcr->user->company->company_email) ? $guhwcr->user->company->company_email : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->job->property->occupant_name) && !empty($guhwcr->job->property->occupant_name) ? $guhwcr->job->property->occupant_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($guhwcr->job->property->pdf_address) && !empty($guhwcr->job->property->pdf_address) ? $guhwcr->job->property->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->job->property->postal_code) && !empty($guhwcr->job->property->postal_code) ? $guhwcr->job->property->postal_code : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->customer->full_name) && !empty($guhwcr->customer->full_name) ? $guhwcr->customer->full_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->customer->company_name) && !empty($guhwcr->customer->company_name) ? $guhwcr->customer->company_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($guhwcr->customer->pdf_address) && !empty($guhwcr->customer->pdf_address) ? $guhwcr->customer->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($guhwcr->customer->postal_code) && !empty($guhwcr->customer->postal_code) ? $guhwcr->customer->postal_code : '').'</td>';
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
                            $PDFHTML .= '<th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-center">';
                                $PDFHTML .= 'Appliance Details';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Type';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Model';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Make';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Location';
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
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($guhwcrs->type) && !empty($guhwcrs->type) ? $guhwcrs->type : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($guhwcrs->model) && !empty($guhwcrs->model) ? $guhwcrs->model : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($guhwcrs->make) && !empty($guhwcrs->make) ? $guhwcrs->make : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($guhwcrs->location) && !empty($guhwcrs->location) ? $guhwcrs->location : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($guhwcrs->serial_no) && !empty($guhwcrs->serial_no) ? $guhwcrs->serial_no : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">'.(isset($guhwcrs->gc_number) && !empty($guhwcrs->gc_number) ? $guhwcrs->gc_number : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="7" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-center">';
                                $PDFHTML .= 'Inspection Details';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Indirect or direct';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Gas boiler and/or Solar, or Immersion Heaters';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Capacity (Ltrs)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Makers Warning label attached';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Inlet water pressure';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Flow Rate';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Fully commissioned';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcrs->direct_or_indirect) && !empty($guhwcrs->direct_or_indirect) ? $guhwcrs->direct_or_indirect : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcrs->boiler_solar_immersion) && !empty($guhwcrs->boiler_solar_immersion) ? $guhwcrs->boiler_solar_immersion : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcrs->capacity) && !empty($guhwcrs->capacity) ? $guhwcrs->capacity : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcrs->warning_label_attached) && !empty($guhwcrs->warning_label_attached) ? $guhwcrs->warning_label_attached : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcrs->water_pressure) && !empty($guhwcrs->water_pressure) ? $guhwcrs->water_pressure : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcrs->flow_rate) && !empty($guhwcrs->flow_rate) ? $guhwcrs->flow_rate : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcrs->fully_commissioned) && !empty($guhwcrs->fully_commissioned) ? $guhwcrs->fully_commissioned : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="8" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-center">';
                                $PDFHTML .= 'Hot Water Storage Vessel Operating Details';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'System operating pressure (Bar)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Operating pressure of expansion vessel (Bar)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Operating pressure of expansion valve (Bar)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Operating temperature of temperature relief valve ( C)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Operating temperature ( C)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Pressure of combined temperature and pressure relief valve (Bar)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Maximum primary circuit pressure (Bar)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Flow Temperature (indirectly heated vessel) ( C)';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->system_opt_pressure) && !empty($guhwcri->system_opt_pressure) ? $guhwcri->system_opt_pressure : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->opt_presure_exp_vsl) && !empty($guhwcri->opt_presure_exp_vsl) ? $guhwcri->opt_presure_exp_vsl : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->opt_presure_exp_vlv) && !empty($guhwcri->opt_presure_exp_vlv) ? $guhwcri->opt_presure_exp_vlv : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->tem_relief_vlv) && !empty($guhwcri->tem_relief_vlv) ? $guhwcri->tem_relief_vlv : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->opt_temperature) && !empty($guhwcri->opt_temperature) ? $guhwcri->opt_temperature : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->combined_temp_presr) && !empty($guhwcri->combined_temp_presr) ? $guhwcri->combined_temp_presr : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->max_circuit_presr) && !empty($guhwcri->max_circuit_presr) ? $guhwcri->max_circuit_presr : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->flow_temp) && !empty($guhwcri->flow_temp) ? $guhwcri->flow_temp : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="7" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-center">';
                                $PDFHTML .= 'Discharge Pipework (D1) relief valve to Tundish';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Nominal size of D1 (mm)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Length of D1 (mm)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Number of discharges';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Size of manifold, if more than one discharge';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Is tundish installed within the same location as the hot water storage vassel';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Is the tundish visible?';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Is automatic means of identifying discharge installed?';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d1_mormal_size) && !empty($guhwcri->d1_mormal_size) ? $guhwcri->d1_mormal_size : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d1_length) && !empty($guhwcri->d1_length) ? $guhwcri->d1_length : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d1_discharges_no) && !empty($guhwcri->d1_discharges_no) ? $guhwcri->d1_discharges_no : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d1_manifold_size) && !empty($guhwcri->d1_manifold_size) ? $guhwcri->d1_manifold_size : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d1_is_tundish_install_same_location) && !empty($guhwcri->d1_is_tundish_install_same_location) ? $guhwcri->d1_is_tundish_install_same_location : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d1_is_tundish_visible) && !empty($guhwcri->d1_is_tundish_visible) ? $guhwcri->d1_is_tundish_visible : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d1_is_auto_dis_intall) && !empty($guhwcri->d1_is_auto_dis_intall) ? $guhwcri->d1_is_auto_dis_intall : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-center">';
                                $PDFHTML .= 'Discharge Pipework (D2) - tundish to point of termination';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Norminal size of D2 (mm)';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Pipework Material';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Does pipework have a minimum vertical length of 300mm from tundish';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Does the pipework fall continuously to point of termination';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Method of termination';
                            $PDFHTML .= '</th>';
                            $PDFHTML .= '<th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">';
                                $PDFHTML .= 'Method of termination satisfactory';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d2_mormal_size) && !empty($guhwcri->d2_mormal_size) ? $guhwcri->d2_mormal_size : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d2_pipework_material) && !empty($guhwcri->d2_pipework_material) ? $guhwcri->d2_pipework_material : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d2_minimum_v_length) && !empty($guhwcri->d2_minimum_v_length) ? $guhwcri->d2_minimum_v_length : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d2_fall_continuously) && !empty($guhwcri->d2_fall_continuously) ? $guhwcri->d2_fall_continuously : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d2_termination_method) && !empty($guhwcri->d2_termination_method) ? $guhwcri->d2_termination_method : '').'</td>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->d2_termination_satisfactory) && !empty($guhwcri->d2_termination_satisfactory) ? $guhwcri->d2_termination_satisfactory : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-center">';
                                $PDFHTML .= 'Comments';
                            $PDFHTML .= '</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 border-b border-r w-col2">'.(isset($guhwcri->comments) && !empty($guhwcri->comments) ? $guhwcri->comments : '').'</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $inspectionDeate = (isset($guhwcr->inspection_date) && !empty($guhwcr->inspection_date) ? date('d-m-Y', strtotime($guhwcr->inspection_date)) : date('d-m-Y'));
                $nextInspectionDate = (isset($guhwcr->next_inspection_date) && !empty($guhwcr->next_inspection_date) ? date('d-m-Y', strtotime($guhwcr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($guhwcr->user->name) && !empty($guhwcr->user->name) ? $guhwcr->user->name : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($guhwcr->relation->name) && !empty($guhwcr->relation->name) ? $guhwcr->relation->name : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>';
                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($guhwcr->received_by) && !empty($guhwcr->received_by) ? $guhwcr->received_by : (isset($guhwcr->customer->full_name) && !empty($guhwcr->customer->full_name) ? $guhwcr->customer->full_name : '')).'</td>';
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


        $fileName = $guhwcr->certificate_number.'.pdf';
        if (Storage::disk('public')->exists('guhwcr/'.$guhwcr->customer_job_id.'/'.$guhwcr->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('guhwcr/'.$guhwcr->customer_job_id.'/'.$guhwcr->job_form_id.'/'.$fileName);
        }
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('guhwcr/'.$guhwcr->customer_job_id.'/'.$guhwcr->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('guhwcr/'.$guhwcr->customer_job_id.'/'.$guhwcr->job_form_id.'/'.$fileName);
    }

    public function approve($record_id)
    {
        try {
            $gasUnvenHWCRecord = GasUnventedHotWaterCylinderRecord::findOrFail($record_id);
            $gasUnvenHWCRecord->update([
                'status' => 'Approved'
            ]);

            return response()->json([
                    'success' => true,
                    'message' => 'Gas unvented hot water cylinder record successfully approved.',
                    'gwn_id' => $gasUnvenHWCRecord->id
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gas unvented hot water cylinder record not found. . The requested gas unvented hot water cylinder record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
       
    }


    public function approve_email(Request $request, $record_id)
    {
        try {
            $gasUnvenHWCRecord = GasUnventedHotWaterCylinderRecord::findOrFail($record_id);
            
            $updateResult = $gasUnvenHWCRecord->update([
                'status' => 'Approved & Sent'
            ]);

            if (!$updateResult) {
                throw new \Exception("Failed to update gas unvented hot water cylinder record status");
            }

            $emailSent = false;
            $emailError = null;
            
            try {
                $emailSent = $this->sendEmail($gasUnvenHWCRecord->id, $gasUnvenHWCRecord->job_form_id, $request->user_id);
            } catch (\Exception $e) {
                $emailError = $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'message' => $emailSent 
                    ? 'Gas unvented hot water cylinder record has been approved and emailed to the customer'
                    : 'Gas unvented hot water cylinder record has been approved but email failed: ' . 
                    ($emailError ?: 'Invalid or empty email address'),
                'gwn_id' => $gasUnvenHWCRecord->id
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gas unvented hot water cylinder record not found. . The requested gas unvented hot water cylinder record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function download($record_id)
    {
        try {
            $gasUnvenHWCRecord = GasUnventedHotWaterCylinderRecord::findOrFail($record_id);
            $thePdf = $this->generatePdf($gasUnvenHWCRecord->id);

            return response()->json([
                    'success' => true,
                    'download_url' => $thePdf,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gas unvented hot water cylinder record not found. . The requested gas unvented hot water cylinder record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
        
    }
}
