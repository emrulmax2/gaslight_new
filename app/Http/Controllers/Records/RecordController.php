<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\ApplianceFlueType;
use App\Models\ApplianceLocation;
use App\Models\ApplianceMake;
use App\Models\ApplianceType;
use App\Models\BoilerBrand;
use App\Models\Company;
use App\Models\CustomerJob;
use App\Models\GasSafetyRecord;
use App\Models\GasSafetyRecordAppliance;
use App\Models\JobForm;
use App\Models\Relation;
use Illuminate\Support\Number;

class RecordController extends Controller
{
    public function index($record, CustomerJob $job){
        $job->load(['customer', 'customer.contact', 'property']);
        $form = JobForm::where('slug', $record)->get()->first();
        $data = [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => ucfirst($record), 'href' => 'javascript:void(0);'],
            ],
            'record' => $record,
            'form' => $form,
            'job' => $job,
            'ref_no' => $this->generateUniqueReferenceNo(),
            'company' => Company::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get()->first(),
        ];
        if($record == 'invoice'):
            
        elseif($record == 'homeowner_gas_safety_record'):
            $GasSafetyRecord = GasSafetyRecord::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();
            $gsr_id = (isset($GasSafetyRecord->id) && $GasSafetyRecord->id > 0 ? $GasSafetyRecord->id : 0);
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flue_types'] = ApplianceFlueType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gsr'] = $GasSafetyRecord;
            $data['gsra1'] = GasSafetyRecordAppliance::with('make', 'type')->where('gas_safety_record_id', $gsr_id)->where('appliance_serial', 1)->get()->first();
            $data['gsra2'] = GasSafetyRecordAppliance::with('make', 'type')->where('gas_safety_record_id', $gsr_id)->where('appliance_serial', 2)->get()->first();
            $data['gsra3'] = GasSafetyRecordAppliance::with('make', 'type')->where('gas_safety_record_id', $gsr_id)->where('appliance_serial', 3)->get()->first();
            $data['gsra4'] = GasSafetyRecordAppliance::with('make', 'type')->where('gas_safety_record_id', $gsr_id)->where('appliance_serial', 4)->get()->first();
        endif;


        return view('app.records.'.$record.'.index', $data);
    }

    protected function generateUniqueReferenceNo($length = 6) {
        $generateRefNo = '';
        while (true) {
            $generateRefNo = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
            if (!CustomerJob::where('reference_no', $generateRefNo)->exists()) {
                break;
            }
        }
        return $generateRefNo;
    }

    protected function generateInvoiceNumber(){
        
    }
}
