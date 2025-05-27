<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasWarningNotice;
use App\Models\GasWarningNoticeAppliance;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Signature;
use Exception;

class GasWarningNoticeController extends Controller
{
     public function checkAndUpdateRecordHistory($record_id){
        $record = GasWarningNotice::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasWarningNotice::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasWarningNotice::class,
            'model_id' => $record->id,
            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]); 
    }


   public function store(Request $request)
    {
        try {
            $job_form_id = 8;
            
            $user_id = $request->user()->id;
            $form = JobForm::findOrFail($job_form_id);

            $certificate_id = $request->certificate_id ?? 0;
            $customer_job_id = $request->job_id ?? 0;
            $customer_id = $request->customer_id;
            $customer_property_id = $request->customer_property_id;
            $property = CustomerProperty::findOrFail($customer_property_id);
            
            $appliances = $request->appliances;

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

            $gasWarningRecord = GasWarningNotice::updateOrCreate(
                [
                    'id' => $certificate_id,
                    'customer_job_id' => $customer_job_id,
                    'job_form_id' => $job_form_id
                ],
                [
                    'customer_id' => $customer_id,
                    'customer_job_id' => $customer_job_id,
                    'job_form_id' => $job_form_id,
                    'inspection_date' => $request->inspection_date ? date('Y-m-d', strtotime($request->inspection_date)) : null,
                    'next_inspection_date' => $request->next_inspection_date ? date('Y-m-d', strtotime($request->next_inspection_date)) : null,
                    'received_by' => $request->received_by,
                    'relation_id' => $request->relation_id,
                    'updated_by' => $user_id,
                ]
            );

            $this->checkAndUpdateRecordHistory($gasWarningRecord->id);

            if (!empty($appliances) && $gasWarningRecord->id) {
                $appliance_serial = $appliances->appliance_serial ?? 1;
                
                GasWarningNoticeAppliance::updateOrCreate(
                    [
                        'gas_warning_notice_id' => $gasWarningRecord->id,
                        'appliance_serial' => $appliance_serial
                    ],
                    [
                        'gas_warning_notice_id' => $gasWarningRecord->id,
                        'appliance_serial' => $appliance_serial,
                        'appliance_location_id' => $appliances->appliance_location_id ?? null,
                        'boiler_brand_id' => $appliances->boiler_brand_id ?? null,
                        'model' => $appliances->model ?? null,
                        'appliance_type_id' => $appliances->appliance_type_id ?? null,
                        'serial_no' => $appliances->serial_no ?? null,
                        'gc_no' => $appliances->gc_no ?? null,
                        'gas_warning_classification_id' => $appliances->gas_warning_classification_id ?? null,
                        'gas_escape_issue' => $appliances->gas_escape_issue ?? null,
                        'pipework_issue' => $appliances->pipework_issue ?? null,
                        'ventilation_issue' => $appliances->ventilation_issue ?? null,
                        'meter_issue' => $appliances->meter_issue ?? null,
                        'chimeny_issue' => $appliances->chimeny_issue ?? null,
                        'fault_details' => $appliances->fault_details ?? null,
                        'action_taken' => $appliances->action_taken ?? null,
                        'actions_required' => $appliances->actions_required ?? null,
                        'reported_to_hse' => $appliances->reported_to_hse ?? null,
                        'reported_to_hde' => $appliances->reported_to_hde ?? null,
                        'left_on_premisies' => $appliances->left_on_premisies ?? null,
                        'updated_by' => $user_id,
                    ]
                );
            }

            if ($request->has('sign')) {
                $signatureData = str_replace('data:image/png;base64,', '', $request->sign);
                $signatureData = base64_decode($signatureData);
                
                if (strlen($signatureData) > 2621) {
                    $gasWarningRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    
                    $signature = new Signature();
                    $signature->model_type = GasWarningNotice::class;
                    $signature->model_id = $gasWarningRecord->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([$request->ip()]);
                    $signature->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Certificate successfully created/updated',
                'data' => [
                    'id' => $gasWarningRecord->id,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function getDetails($notice_id){
        try {
            $gasWarningNotice = GasWarningNotice::with(['customer', 'appliance'])->findOrFail($notice_id);
          return response()->json([
                'success' => true,
                'data' => $gasWarningNotice
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gas warning notice not found. . The requested Gas warning notice (ID: '.$notice_id.') does not exist or may have been deleted.',
            ], 500);
        }
    }
}
