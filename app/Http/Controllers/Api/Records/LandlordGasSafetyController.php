<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasLandlordSafetyRecord;
use App\Models\GasLandlordSafetyRecordAppliance;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Exception;

class LandlordGasSafetyController extends Controller
{

     public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasLandlordSafetyRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasLandlordSafetyRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasLandlordSafetyRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request)
    {
        try {
            $job_form_id = 7;

            $user_id = $request->user()->id;
            $form = JobForm::findOrFail($job_form_id);

            $certificate_id = $request->certificate_id ?? 0;
            $customer_job_id = $request->job_id ?? 0;
            $customer_id = $request->customer_id;
            $customer_property_id = $request->customer_property_id;
            $property = CustomerProperty::findOrFail($customer_property_id);
            
            $appliances = $request->appliances;
            $safetyChecks = $request->safetyChecks;
            $glsrComments = $request->glsrComments;

            if ($customer_job_id == 0) {
                $customerJob = CustomerJob::create([
                    'customer_id' => $customer_id,
                    'customer_property_id' => $customer_property_id,
                    'description' => $form->name,
                    'details' => 'Job created for '.$property->full_address,
                    'created_by' => $user_id
                ]);
                $customer_job_id = $customerJob->id;
            }

            $gasSafetyRecord = GasLandlordSafetyRecord::updateOrCreate(
                [
                    'id' => $certificate_id, 
                    'customer_job_id' => $customer_job_id, 
                    'job_form_id' => $job_form_id
                ], 
                [
                    'customer_id' => $customer_id,
                    'customer_job_id' => $customer_job_id,
                    'job_form_id' => $job_form_id,
                    'satisfactory_visual_inspaction' => $safetyChecks->satisfactory_visual_inspaction ?? null,
                    'emergency_control_accessible' => $safetyChecks->emergency_control_accessible ?? null,
                    'satisfactory_gas_tightness_test' => $safetyChecks->satisfactory_gas_tightness_test ?? null,
                    'equipotential_bonding_satisfactory' => $safetyChecks->equipotential_bonding_satisfactory ?? null,
                    'co_alarm_fitted' => $safetyChecks->co_alarm_fitted ?? null,
                    'co_alarm_in_date' => $safetyChecks->co_alarm_in_date ?? null,
                    'co_alarm_test_satisfactory' => $safetyChecks->co_alarm_test_satisfactory ?? null,
                    'smoke_alarm_fitted' => $safetyChecks->smoke_alarm_fitted ?? null,
                    'fault_details' => $glsrComments->fault_details ?? null,
                    'rectification_work_carried_out' => $glsrComments->rectification_work_carried_out ?? null,
                    'details_work_carried_out' => $glsrComments->details_work_carried_out ?? null,
                    'flue_cap_put_back' => $glsrComments->flue_cap_put_back ?? null,
                    'inspection_date' => $request->inspection_date ? date('Y-m-d', strtotime($request->inspection_date)) : null,
                    'next_inspection_date' => $request->next_inspection_date ? date('Y-m-d', strtotime($request->next_inspection_date)) : null,
                    'received_by' => $request->received_by ?? null,
                    'relation_id' => $request->relation_id ?? null,
                    'updated_by' => $user_id,
                ]
            );

            $this->checkAndUpdateRecordHistory($gasSafetyRecord->id);

            if (!empty($appliances)) {
                foreach ($appliances as $serial => $appliance) {
                    GasLandlordSafetyRecordAppliance::updateOrCreate(
                        [
                            'gas_landlord_safety_record_id' => $gasSafetyRecord->id, 
                            'appliance_serial' => $serial
                        ],
                        [
                            'gas_landlord_safety_record_id' => $gasSafetyRecord->id,
                            'appliance_serial' => $serial,
                            'appliance_location_id' => $appliance->appliance_location_id ?? null,
                            'boiler_brand_id' => $appliance->boiler_brand_id ?? null,
                            'model' => $appliance->model ?? null,
                            'appliance_type_id' => $appliance->appliance_type_id ?? null,
                            'serial_no' => $appliance->serial_no ?? null,
                            'gc_no' => $appliance->gc_no ?? null,
                            'appliance_flue_type_id' => $appliance->appliance_flue_type_id ?? null,
                            'opt_pressure' => $appliance->opt_pressure ?? null,
                            'safety_devices' => $appliance->safety_devices ?? null,
                            'spillage_test' => $appliance->spillage_test ?? null,
                            'smoke_pellet_test' => $appliance->smoke_pellet_test ?? null,
                            'low_analyser_ratio' => $appliance->low_analyser_ratio ?? null,
                            'low_co' => $appliance->low_co ?? null,
                            'low_co2' => $appliance->low_co2 ?? null,
                            'high_analyser_ratio' => $appliance->high_analyser_ratio ?? null,
                            'high_co' => $appliance->high_co ?? null,
                            'high_co2' => $appliance->high_co2 ?? null,
                            'satisfactory_termination' => $appliance->satisfactory_termination ?? null,
                            'flue_visual_condition' => $appliance->flue_visual_condition ?? null,
                            'adequate_ventilation' => $appliance->adequate_ventilation ?? null,
                            'landlord_appliance' => $appliance->landlord_appliance ?? null,
                            'inspected' => $appliance->inspected ?? null,
                            'appliance_visual_check' => $appliance->appliance_visual_check ?? null,
                            'appliance_serviced' => $appliance->appliance_serviced ?? null,
                            'appliance_safe_to_use' => $appliance->appliance_safe_to_use ?? null,
                            'updated_by' => $user_id,
                        ]
                    );
                }
            }

            if ($request->input('sign') !== null) {
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                
                if (strlen($signatureData) > 2621) {
                    $gasSafetyRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    
                    $signature = new Signature();
                    $signature->model_type = GasLandlordSafetyRecord::class;
                    $signature->model_id = $gasSafetyRecord->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([request()->ip()]);
                    $signature->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => $gasSafetyRecord->wasRecentlyCreated ? 'Certificate successfully created' : 'Certificate successfully updated',
                'data' => GasLandlordSafetyRecord::with(['customer','appliance','signature'])->findOrFail($gasSafetyRecord->id)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDetails(Request $request, $gas_safety_id)
    {
        try {
            $gasSafetyRecord = GasLandlordSafetyRecord::with(['customer','appliance','signature'])->findOrFail($gas_safety_id);

            $user_id = $request->user_id;
            $gasSafetyRecord->load(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
            $form = JobForm::find($gasSafetyRecord->job_form_id);
            $record = $form->slug;

            if(empty($glsr->certificate_number)):
                $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
                $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
                $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
                $userLastCertificate = GasLandlordSafetyRecord::where('job_form_id', $form->id)->where('created_by', $user_id)->where('id', '!=', $gasSafetyRecord->id)->orderBy('id', 'DESC')->get()->first();
                $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

                $cerSerial = $starting_form;
                if(!empty($lastCertificateNo)):
                    preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                    $cerSerial = isset($certificateNumbers[1]) ? ((int) $certificateNumbers[1]) + 1 : $starting_form;
                endif;
                $certificateNumber = $prifix . $cerSerial;
                GasLandlordSafetyRecord::where('id', $gasSafetyRecord->id)->update(['certificate_number' => $certificateNumber]);
            endif;

            $thePdf = $this->generatePdf($gasSafetyRecord->id);
            return response()->json([
                'success' => true,
                'data' => [
                    'form' => $form,
                    'gas_safety_record' => $gasSafetyRecord,
                    'gas_safety_record1' => GasLandlordSafetyRecordAppliance::where('gas_landlord_safety_record_id', $gasSafetyRecord->id)->where('appliance_serial', 1)->get()->first(),
                    'gas_safety_record2' => GasLandlordSafetyRecordAppliance::where('gas_landlord_safety_record_id', $gasSafetyRecord->id)->where('appliance_serial', 2)->get()->first(),
                    'gas_safety_record3' => GasLandlordSafetyRecordAppliance::where('gas_landlord_safety_record_id', $gasSafetyRecord->id)->where('appliance_serial', 3)->get()->first(),
                    'gas_safety_record4' => GasLandlordSafetyRecordAppliance::where('gas_landlord_safety_record_id', $gasSafetyRecord->id)->where('appliance_serial', 4)->get()->first(),
                    'signature' => $gasSafetyRecord->signature ? Storage::disk('public')->url($gasSafetyRecord->signature->filename) : '',
                    'pdf_url' => $thePdf
                ]
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Landlord gas safety record not found. The requested landlord gas safety record (ID: '.$gas_safety_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Landlord safety record not found. . The requested Landlord safety record (ID: '.$gas_safety_id.') does not exist or may have been deleted.',
            ], 500);
        }
    }

    public function generatePdf($glsr_id) {
        $glsr = GasLandlordSafetyRecord::with('customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company')->find($glsr_id);
        $glsra1 = GasLandlordSafetyRecordAppliance::where('gas_landlord_safety_record_id', $glsr->id)->where('appliance_serial', 1)->get()->first();
        $glsra2 = GasLandlordSafetyRecordAppliance::where('gas_landlord_safety_record_id', $glsr->id)->where('appliance_serial', 2)->get()->first();
        $glsra3 = GasLandlordSafetyRecordAppliance::where('gas_landlord_safety_record_id', $glsr->id)->where('appliance_serial', 3)->get()->first();
        $glsra4 = GasLandlordSafetyRecordAppliance::where('gas_landlord_safety_record_id', $glsr->id)->where('appliance_serial', 4)->get()->first();

        $logoPath = resource_path('images/gas_safe_register_dark.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $userSignBase64 = (isset($glsr->user->signature) && Storage::disk('public')->exists($glsr->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($glsr->user->signature->filename)) : '');
        $signatureBase64 = ($glsr->signature && Storage::disk('public')->exists($glsr->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($glsr->signature->filename)) : '');

        $report_title = 'Certificate of '.$glsr->certificate_number;
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
                                .p-25{padding: 0.625rem;}
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

                $PDFHTML .= '<div class="header bg-primary p-25">';
                    $PDFHTML .= '<table class="grid grid-cols-12 gap-4 items-center">';
                        $PDFHTML .= '<tbody>';
                            $PDFHTML .= '<tr>';
                                $PDFHTML .= '<td class="w-col2 align-middle">';
                                    $PDFHTML .= '<img class="w-auto h-80px" src="'.$logoBase64.'" alt="Gas Safe Register Logo">';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col8 text-center align-middle px-5">';
                                    $PDFHTML .= '<h1 class="text-white text-xl leading-none mt-0 mb-05">Landlord Gas Safety Record</h1>';
                                    $PDFHTML .= '<div class="text-white text-12px leading-1-3">';
                                        $PDFHTML .= 'This inspection is for gas safety purposes only to comply with the Gas Safety (Installation and Use) Regulations. Flues have been inspected visually and checked for satisfactory evacuation of products of combustion
                                                    A detailed internal inspection of the flue integrity, construction and lining has NOT been carried out.
                                                    Registered Business/engineer details can be checked at www.gassaferegister.co.uk or by calling 0800 408 5500';
                                    $PDFHTML .= '</div>';
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-col2 align-middle text-center">';
                                    $PDFHTML .= '<label class="text-white uppercase font-medium text-12px leading-none mb-2 inline-block">Certificate Number</label>';
                                    $PDFHTML .= '<div class="inline-block bg-white w-32 text-center rounded-none leading-28px h-35px font-medium text-primary">'.$glsr->certificate_number.'</div>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($glsr->user->name) && !empty($glsr->user->name) ? $glsr->user->name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">GAS SAFE REG.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->user->company->gas_safe_registration_no) && !empty($glsr->user->company->gas_safe_registration_no) ? $glsr->user->company->gas_safe_registration_no : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">ID CARD NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->user->gas_safe_id_card) && !empty($glsr->user->gas_safe_id_card) ? $glsr->user->gas_safe_id_card : '').'</td>';
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
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->user->company->company_name) && !empty($glsr->user->company->company_name) ? $glsr->user->company->company_name : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($glsr->user->company->pdf_address) && !empty($glsr->user->company->pdf_address) ? $glsr->user->company->pdf_address : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">TEL NO.</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->user->company->company_phone) && !empty($glsr->user->company->company_phone) ? $glsr->user->company->company_phone : '').'</td>';
                                                            $PDFHTML .= '</tr>';
                                                            $PDFHTML .= '<tr>';
                                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Email</td>';
                                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->user->company->company_email) && !empty($glsr->user->company->company_email) ? $glsr->user->company->company_email : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->job->property->occupant_name) && !empty($glsr->job->property->occupant_name) ? $glsr->job->property->occupant_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($glsr->job->property->pdf_address) && !empty($glsr->job->property->pdf_address) ? $glsr->job->property->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->job->property->postal_code) && !empty($glsr->job->property->postal_code) ? $glsr->job->property->postal_code : '').'</td>';
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
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->customer->full_name) && !empty($glsr->customer->full_name) ? $glsr->customer->full_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company Name</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px leading-none align-middle">'.(isset($glsr->customer->company_name) && !empty($glsr->customer->company_name) ? $glsr->customer->company_name : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">'.(isset($glsr->customer->pdf_address) && !empty($glsr->customer->pdf_address) ? $glsr->customer->pdf_address : '').'</td>';
                                            $PDFHTML .= '</tr>';
                                            $PDFHTML .= '<tr>';
                                                $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>';
                                                $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">'.(isset($glsr->customer->postal_code) && !empty($glsr->customer->postal_code) ? $glsr->customer->postal_code : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->location->name) && !empty($glsra1->location->name) ? $glsra1->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->make->name) && !empty($glsra1->make->name) ? $glsra1->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->model) && !empty($glsra1->model) ? $glsra1->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->type->name) && !empty($glsra1->type->name) ? $glsra1->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->serial_no) && !empty($glsra1->serial_no) ? $glsra1->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->flue->name) && !empty($glsra1->flue->name) ? $glsra1->flue->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-140px border-b">'.(isset($glsra1->opt_pressure) && !empty($glsra1->opt_pressure) ? $glsra1->opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">2</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->location->name) && !empty($glsra2->location->name) ? $glsra2->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->make->name) && !empty($glsra2->make->name) ? $glsra2->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->model) && !empty($glsra2->model) ? $glsra2->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->type->name) && !empty($glsra2->type->name) ? $glsra2->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->serial_no) && !empty($glsra2->serial_no) ? $glsra2->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->flue->name) && !empty($glsra2->flue->name) ? $glsra2->flue->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-140px border-b">'.(isset($glsra2->opt_pressure) && !empty($glsra2->opt_pressure) ? $glsra2->opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">3</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->location->name) && !empty($glsra3->location->name) ? $glsra3->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->make->name) && !empty($glsra3->make->name) ? $glsra3->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->model) && !empty($glsra3->model) ? $glsra3->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->type->name) && !empty($glsra3->type->name) ? $glsra3->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->serial_no) && !empty($glsra3->serial_no) ? $glsra3->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->flue->name) && !empty($glsra3->flue->name) ? $glsra3->flue->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-140px border-b">'.(isset($glsra3->opt_pressure) && !empty($glsra3->opt_pressure) ? $glsra3->opt_pressure : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">4</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->location->name) && !empty($glsra4->location->name) ? $glsra4->location->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->make->name) && !empty($glsra4->make->name) ? $glsra4->make->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->model) && !empty($glsra4->model) ? $glsra4->model : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->type->name) && !empty($glsra4->type->name) ? $glsra4->type->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->serial_no) && !empty($glsra4->serial_no) ? $glsra4->serial_no : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->flue->name) && !empty($glsra4->flue->name) ? $glsra4->flue->name : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-140px border-b">'.(isset($glsra4->opt_pressure) && !empty($glsra4->opt_pressure) ? $glsra4->opt_pressure : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->safety_devices) && !empty($glsra1->safety_devices) ? $glsra1->safety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->spillage_test) && !empty($glsra1->spillage_test) ? $glsra1->spillage_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->smoke_pellet_test) && !empty($glsra1->smoke_pellet_test) ? $glsra1->smoke_pellet_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra1->low_analyser_ratio) && !empty($glsra1->low_analyser_ratio) ? $glsra1->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra1->low_co) && !empty($glsra1->low_co) ? $glsra1->low_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra1->low_co2) && !empty($glsra1->low_co2) ? $glsra1->low_co2 : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra1->high_analyser_ratio) && !empty($glsra1->high_analyser_ratio) ? $glsra1->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra1->high_co) && !empty($glsra1->high_co) ? $glsra1->high_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b">'.(isset($glsra1->high_co2) && !empty($glsra1->high_co2) ? $glsra1->high_co2 : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">2</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->safety_devices) && !empty($glsra2->safety_devices) ? $glsra2->safety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->spillage_test) && !empty($glsra2->spillage_test) ? $glsra2->spillage_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->smoke_pellet_test) && !empty($glsra2->smoke_pellet_test) ? $glsra2->smoke_pellet_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra2->low_analyser_ratio) && !empty($glsra2->low_analyser_ratio) ? $glsra2->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra2->low_co) && !empty($glsra2->low_co) ? $glsra2->low_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra2->low_co2) && !empty($glsra2->low_co2) ? $glsra2->low_co2 : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra2->high_analyser_ratio) && !empty($glsra2->high_analyser_ratio) ? $glsra2->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra2->high_co) && !empty($glsra2->high_co) ? $glsra2->high_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b">'.(isset($glsra2->high_co2) && !empty($glsra2->high_co2) ? $glsra2->high_co2 : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">3</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->safety_devices) && !empty($glsra3->safety_devices) ? $glsra3->safety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->spillage_test) && !empty($glsra3->spillage_test) ? $glsra3->spillage_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->smoke_pellet_test) && !empty($glsra3->smoke_pellet_test) ? $glsra3->smoke_pellet_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra3->low_analyser_ratio) && !empty($glsra3->low_analyser_ratio) ? $glsra3->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra3->low_co) && !empty($glsra3->low_co) ? $glsra3->low_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra3->low_co2) && !empty($glsra3->low_co2) ? $glsra3->low_co2 : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra3->high_analyser_ratio) && !empty($glsra3->high_analyser_ratio) ? $glsra3->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra3->high_co) && !empty($glsra3->high_co) ? $glsra3->high_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b">'.(isset($glsra3->high_co2) && !empty($glsra3->high_co2) ? $glsra3->high_co2 : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">4</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->safety_devices) && !empty($glsra4->safety_devices) ? $glsra4->safety_devices : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->spillage_test) && !empty($glsra4->spillage_test) ? $glsra4->spillage_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->smoke_pellet_test) && !empty($glsra4->smoke_pellet_test) ? $glsra4->smoke_pellet_test : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra4->low_analyser_ratio) && !empty($glsra4->low_analyser_ratio) ? $glsra4->low_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra4->low_co) && !empty($glsra4->low_co) ? $glsra4->low_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra4->low_co2) && !empty($glsra4->low_co2) ? $glsra4->low_co2 : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra4->high_analyser_ratio) && !empty($glsra4->high_analyser_ratio) ? $glsra4->high_analyser_ratio : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b border-r">'.(isset($glsra4->high_co) && !empty($glsra4->high_co) ? $glsra4->high_co : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 w-90px border-b">'.(isset($glsra4->high_co2) && !empty($glsra4->high_co2) ? $glsra4->high_co2 : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->flue_visual_condition) && !empty($glsra1->flue_visual_condition) ? $glsra1->flue_visual_condition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->adequate_ventilation) && !empty($glsra1->adequate_ventilation) ? $glsra1->adequate_ventilation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->landlord_appliance) && !empty($glsra1->landlord_appliance) ? $glsra1->landlord_appliance : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->inspected) && !empty($glsra1->inspected) ? $glsra1->inspected : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->appliance_visual_check) && !empty($glsra1->appliance_visual_check) ? $glsra1->appliance_visual_check : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra1->appliance_serviced) && !empty($glsra1->appliance_serviced) ? $glsra1->appliance_serviced : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b w-140px">'.(isset($glsra1->appliance_safe_to_use) && !empty($glsra1->appliance_safe_to_use) ? $glsra1->appliance_safe_to_use : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">2</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->flue_visual_condition) && !empty($glsra2->flue_visual_condition) ? $glsra2->flue_visual_condition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->adequate_ventilation) && !empty($glsra2->adequate_ventilation) ? $glsra2->adequate_ventilation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->landlord_appliance) && !empty($glsra2->landlord_appliance) ? $glsra2->landlord_appliance : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->inspected) && !empty($glsra2->inspected) ? $glsra2->inspected : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->appliance_visual_check) && !empty($glsra2->appliance_visual_check) ? $glsra2->appliance_visual_check : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra2->appliance_serviced) && !empty($glsra2->appliance_serviced) ? $glsra2->appliance_serviced : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b w-140px">'.(isset($glsra2->appliance_safe_to_use) && !empty($glsra2->appliance_safe_to_use) ? $glsra2->appliance_safe_to_use : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">3</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->flue_visual_condition) && !empty($glsra3->flue_visual_condition) ? $glsra3->flue_visual_condition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->adequate_ventilation) && !empty($glsra3->adequate_ventilation) ? $glsra3->adequate_ventilation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->landlord_appliance) && !empty($glsra3->landlord_appliance) ? $glsra3->landlord_appliance : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->inspected) && !empty($glsra3->inspected) ? $glsra3->inspected : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->appliance_visual_check) && !empty($glsra3->appliance_visual_check) ? $glsra3->appliance_visual_check : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra3->appliance_serviced) && !empty($glsra3->appliance_serviced) ? $glsra3->appliance_serviced : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b w-140px">'.(isset($glsra3->appliance_safe_to_use) && !empty($glsra3->appliance_safe_to_use) ? $glsra3->appliance_safe_to_use : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">4</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->flue_visual_condition) && !empty($glsra4->flue_visual_condition) ? $glsra4->flue_visual_condition : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->adequate_ventilation) && !empty($glsra4->adequate_ventilation) ? $glsra4->adequate_ventilation : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->landlord_appliance) && !empty($glsra4->landlord_appliance) ? $glsra4->landlord_appliance : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->inspected) && !empty($glsra4->inspected) ? $glsra4->inspected : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->appliance_visual_check) && !empty($glsra4->appliance_visual_check) ? $glsra4->appliance_visual_check : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">'.(isset($glsra4->appliance_serviced) && !empty($glsra4->appliance_serviced) ? $glsra4->appliance_serviced : '').'</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b w-140px">'.(isset($glsra4->appliance_safe_to_use) && !empty($glsra4->appliance_safe_to_use) ? $glsra4->appliance_safe_to_use : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($glsr->satisfactory_visual_inspaction) && !empty($glsr->satisfactory_visual_inspaction) ? $glsr->satisfactory_visual_inspaction : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-1 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">EMERGENCY CONTROL ACCESSIBLE</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($glsr->emergency_control_accessible) && !empty($glsr->emergency_control_accessible) ? $glsr->emergency_control_accessible : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-1 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">SATISFACTORY GAS TIGHTNESS TEST</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($glsr->satisfactory_gas_tightness_test) && !empty($glsr->satisfactory_gas_tightness_test) ? $glsr->satisfactory_gas_tightness_test : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-1 py-05 text-10px tracking-normal text-left leading-1-5 border-b border-r">EQUIPOTENTIAL BONDING SATISFACTION</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($glsr->equipotential_bonding_satisfactory) && !empty($glsr->equipotential_bonding_satisfactory) ? $glsr->equipotential_bonding_satisfactory : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($glsr->co_alarm_fitted) && !empty($glsr->co_alarm_fitted) ? $glsr->co_alarm_fitted : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">ARE CO ALARMS IN DATE</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($glsr->co_alarm_in_date) && !empty($glsr->co_alarm_in_date) ? $glsr->co_alarm_in_date : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">TESTING CO ALARMS</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($glsr->co_alarm_test_satisfactory) && !empty($glsr->co_alarm_test_satisfactory) ? $glsr->co_alarm_test_satisfactory : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-5 border-b border-r">SMOKE ALARMS FITTED</td>';
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-5 w-60px border-b">'.(isset($glsr->smoke_alarm_fitted) && !empty($glsr->smoke_alarm_fitted) ? $glsr->smoke_alarm_fitted : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 align-top h-83px">'.(isset($glsr->fault_details) && !empty($glsr->fault_details) ? $glsr->fault_details : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 align-top  h-83px">'.(isset($glsr->rectification_work_carried_out) && !empty($glsr->rectification_work_carried_out) ? $glsr->rectification_work_carried_out : '').'</td>';
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
                                            $PDFHTML .= '<td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 h-60px align-top">'.(isset($glsr->details_work_carried_out) && !empty($glsr->details_work_carried_out) ? $glsr->details_work_carried_out : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                                $PDFHTML .= '<table class="table table-sm bordered border-primary mt-1-5">';
                                    $PDFHTML .= '<tbody>';
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-12px uppercase px-2 py-1 leading-none align-middle">HAS FLUE CAP BEEN PUT BACK?</td>';
                                            $PDFHTML .= '<td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-1 text-12px w-130px leading-none align-middle">'.(isset($glsr->flue_cap_put_back) && !empty($glsr->flue_cap_put_back) ? $glsr->flue_cap_put_back : '').'</td>';
                                        $PDFHTML .= '</tr>';
                                    $PDFHTML .= '</tbody>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                            $PDFHTML .= '<td class="w-col9 pl-1 pr-0 pb-0 pt-0 align-top">';
                                $inspectionDeate = (isset($glsr->inspection_date) && !empty($glsr->inspection_date) ? date('d-m-Y', strtotime($glsr->inspection_date)) : date('d-m-Y'));
                                $nextInspectionDate = (isset($glsr->next_inspection_date) && !empty($glsr->next_inspection_date) ? date('d-m-Y', strtotime($glsr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                                
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
                                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-50px align-top">';
                                                                if($userSignBase64):
                                                                    $PDFHTML .= '<img src="'.$userSignBase64.'" alt="signature" class="h-50px w-auto inline-block"/>';
                                                                endif;
                                                            $PDFHTML .= '</td>';
                                                        $PDFHTML .= '</tr>';
                                                        $PDFHTML .= '<tr>';
                                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Issued By</td>';
                                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($glsr->user->name) && !empty($glsr->user->name) ? $glsr->user->name : '').'</td>';
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
                                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($glsr->relation->name) && !empty($glsr->relation->name) ? $glsr->relation->name : '').'</td>';
                                                        $PDFHTML .= '</tr>';
                                                        $PDFHTML .= '<tr>';
                                                            $PDFHTML .= '<td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>';
                                                            $PDFHTML .= '<td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">'.(isset($glsr->received_by) && !empty($glsr->received_by) ? $glsr->received_by : (isset($glsr->customer->full_name) && !empty($glsr->customer->full_name) ? $glsr->customer->full_name : '')).'</td>';
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

                            $PDFHTML .= '</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

            $PDFHTML .= '</body>';
        $PDFHTML .= '</html>';


        $fileName = $glsr->certificate_number.'.pdf';
        if (Storage::disk('public')->exists('glsr/'.$glsr->customer_job_id.'/'.$glsr->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('glsr/'.$glsr->customer_job_id.'/'.$glsr->job_form_id.'/'.$fileName);
        }
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('glsr/'.$glsr->customer_job_id.'/'.$glsr->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('glsr/'.$glsr->customer_job_id.'/'.$glsr->job_form_id.'/'.$fileName);
    }

    public function sendEmail($glsr_id, $job_form_id, $user_id){
        $glsr = GasLandlordSafetyRecord::with('job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($glsr_id);
        $customerName = (isset($glsr->customer->full_name) && !empty($glsr->customer->full_name) ? $glsr->customer->full_name : '');
        $customerEmail = (isset($glsr->customer->contact->email) && !empty($glsr->customer->contact->email) ? $glsr->customer->contact->email : '');
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

            $fileName = $glsr->certificate_number.'.pdf';
            $attachmentFiles = [];
            if (Storage::disk('public')->exists('glsr/'.$glsr->customer_job_id.'/'.$glsr->job_form_id.'/'.$fileName)):
                $attachmentFiles[] = [
                    "pathinfo" => 'glsr/'.$glsr->customer_job_id.'/'.$glsr->job_form_id.'/'.$fileName,
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
    public function approve($gsr_id)
    {
        try {
            $gasSafetyRecord = GasLandlordSafetyRecord::findOrFail($gsr_id);
            $gasSafetyRecord->update([
                'status' => 'Approved'
            ]);

            return response()->json([
                    'success' => true,
                    'message' => 'Landlord gas safety record successfully approved.',
                    'gsr_id' => $gasSafetyRecord->id
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Landlord gas safety record not found. The requested landlord gas safety record (ID: '.$gsr_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
       
    }


    public function approve_email(Request $request, $gsr_id)
    {
        try {
            $gasSafetyRecord = GasLandlordSafetyRecord::findOrFail($gsr_id);
            
            $updateResult = $gasSafetyRecord->update([
                'status' => 'Approved & Sent'
            ]);

            if (!$updateResult) {
                throw new \Exception("Failed to update landlord gas safety record status");
            }

            $emailSent = false;
            $emailError = null;
            
            try {
                $emailSent = $this->sendEmail($gasSafetyRecord->id, $gasSafetyRecord->job_form_id, $request->user_id);
            } catch (\Exception $e) {
                $emailError = $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'message' => $emailSent 
                    ? 'Landlord gas safety record Certificate has been approved and emailed to the customer'
                    : 'Landlord gas safety record Certificate has been approved but email failed: ' . 
                    ($emailError ?: 'Invalid or empty email address'),
                'gsr_id' => $gasSafetyRecord->id
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Landlord gas safety record not found. The requested landlord gas safety record (ID: '.$gsr_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function download($gsr_id)
    {
        try {
            $gasSafetyRecord = GasLandlordSafetyRecord::findOrFail($gsr_id);
            $thePdf = $this->generatePdf($gasSafetyRecord->id);

            return response()->json([
                    'success' => true,
                    'download_url' => $thePdf,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Landlord gas safety record not found. The requested landlord gas safety record (ID: '.$gsr_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
        
    }
}
