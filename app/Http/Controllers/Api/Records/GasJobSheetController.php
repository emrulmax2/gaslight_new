<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasJobSheetRecord;
use App\Models\GasJobSheetRecordDetail;
use App\Models\GasJobSheetRecordDocument;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;
use Exception;


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
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request){
        $user_id = $request->user()->id;
        $job_form_id = 18;
        $form = JobForm::find($job_form_id);

        $certificate_id = (isset($request->certificate_id) && $request->certificate_id > 0 ? $request->certificate_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $property = CustomerProperty::find($customer_property_id);
        
        $jobSheets = $request->jobSheets;

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

            return response()->json([
                'success' => true,
                'message' => 'Certificate successfully created.',
                'data' => $gasJobSheetRecord
                ], 200);
        else:
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator.'
            ], 304);
        endif;
    }

    public function getDetails($sheet_id){
        try {
            $boilerCommChecklist = GasJobSheetRecord::with(['customer', 'documents', 'signature'])->findOrFail($sheet_id);
          return response()->json([
                'success' => true,
                'data' => $boilerCommChecklist
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job sheet not found. The requested sheet (ID: '.$sheet_id.') does not exist or may have been deleted.',
            ], 500);
        }
    }
}
