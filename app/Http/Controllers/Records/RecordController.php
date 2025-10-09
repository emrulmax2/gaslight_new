<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAddressStoreRequest;
use App\Http\Requests\OccupantDetailsStoreRequest;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\ApplianceFlueType;
use App\Models\ApplianceLocation;
use App\Models\ApplianceMake;
use App\Models\ApplianceTimeTemperatureHeating;
use App\Models\ApplianceType;
use App\Models\BoilerBrand;
use App\Models\Color;
use App\Models\CommissionDecommissionWorkType;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasBreakdownRecord;
use App\Models\GasBreakdownRecordAppliance;
use App\Models\GasSafetyRecord;
use App\Models\GasSafetyRecordAppliance;
use App\Models\GasServiceRecord;
use App\Models\GasServiceRecordAppliance;
use App\Models\GasWarningClassification;
use App\Models\GasWarningNotice;
use App\Models\GasWarningNoticeAppliance;
use App\Models\GasBoilerSystemCommissioningChecklist;
use App\Models\GasBoilerSystemCommissioningChecklistAppliance;
use App\Models\GasCommissionDecommissionRecord;
use App\Models\GasCommissionDecommissionRecordAppliance;
use App\Models\GasJobSheetRecord;
use App\Models\GasJobSheetRecordDetail;
use App\Models\GasJobSheetRecordDocument;
use App\Models\GasPowerFlushRecord;
use App\Models\GasPowerFlushRecordChecklist;
use App\Models\GasPowerFlushRecordRediator;
use App\Models\GasUnventedHotWaterCylinderRecord;
use App\Models\GasUnventedHotWaterCylinderRecordInspection;
use App\Models\GasUnventedHotWaterCylinderRecordSystem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use App\Models\PaymentMethod;
use App\Models\PowerflushCirculatorPumpLocation;
use App\Models\PowerflushCylinderType;
use App\Models\PowerflushPipeworkType;
use App\Models\PowerflushSystemType;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\RadiatorType;
use App\Models\Record;
use App\Models\RecordOption;
use App\Models\Relation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class RecordController extends Controller
{
    public function index(){
        return view('app.records.index', [
            'title' => 'Create New Record - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Create Records', 'href' => 'javascript:void(0);'],
            ],
            'forms' => JobForm::with('childs')->where('parent_id', 0)->where('active', 1)->orderBy('id', 'ASC')->get()
        ]);
    }

    public function create(JobForm $form){
        $data = [
            'title' => 'Create New Record - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Create Records', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'relations' => Relation::where('active', 1)->orderBy('name', 'ASC')->get(),
            'locations' => ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get(),
            'boilers' => BoilerBrand::orderBy('name', 'ASC')->get(),
            'types' => ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get(),
            'flue_types' => ApplianceFlueType::where('active', 1)->orderBy('name', 'ASC')->get(),
        ];


        if($form->slug == 'homeowner_gas_safety_record'):
            //Nothing 
        elseif($form->slug == 'landlord_gas_safety_record'):
            //Nothing 
        elseif($form->slug == 'gas_warning_notice'):
            $data['classifications'] = GasWarningClassification::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'gas_service_record'):
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'gas_breakdown_record'):
            //Nothing 
        elseif($form->slug == 'gas_boiler_system_commissioning_checklist'):
            $data['timerTemp'] = ApplianceTimeTemperatureHeating::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'installation_commissioning_decommissioning_record'):
            $data['worktype'] = CommissionDecommissionWorkType::where('active', 1)->orderBy('id', 'ASC')->get();
        elseif($form->slug == 'unvented_hot_water_cylinders'):
            //Nothing 
        elseif($form->slug == 'job_sheet'):
            //Nothing 
        elseif($form->slug == 'power_flush_record'):
            $data['flush_types'] = PowerflushSystemType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flush_cylinder'] = PowerflushCylinderType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flush_pipework'] = PowerflushPipeworkType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flush_pump_location'] = PowerflushCirculatorPumpLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['radiator_type'] = RadiatorType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['color'] = Color::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'invoice'):
            $user = User::find(auth()->user()->id);
            $data['non_vat_invoice'] = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? 0 : 1);
            $data['vat_number'] = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? $user->companies[0]->vat_number : '');
            $data['methods'] = PaymentMethod::where('active', 1)->orderBy('name', 'asc')->get();
        elseif($form->slug == 'quote'):
            $user = User::find(auth()->user()->id);
            $data['non_vat_quote'] = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? 0 : 1);
            $data['vat_number'] = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? $user->companies[0]->vat_number : '');
        endif;
        return view('app.records.create', $data);
    }

    public function store(Request $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;
        $form = JobForm::find($job_form_id);

        $certificate_id = (isset($request->certificate_id) && $request->certificate_id > 0 ? $request->certificate_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $property = CustomerProperty::find($customer_property_id);

        /* Create Job If Empty */
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
        /* Create Job If Empty */

        /* Store or Update Record */
        if($customer_job_id > 0):
            $record = Record::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'company_id' => auth()->user()->companies->pluck('id')->first(),
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            
            if($record->id):
                $certificate_number = $this->generateCertificateNumber($record->id);
                $options = json_decode($request->options);
                RecordOption::where('record_id', $record->id)->forceDelete();
                if(!empty($options)):
                    foreach($options as $key => $value):
                        RecordOption::create([
                            'record_id' => $record->id,
                            'job_form_id' => $job_form_id,
                            'name' => $key,
                            'value' => json_decode($value)
                        ]);
                    endforeach;
                endif;

                if($request->input('sign') !== null):
                    $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                    $signatureData = base64_decode($signatureData);
                    if(strlen($signatureData) > 2621):
                        $record->deleteSignature();
                        
                        $imageName = 'signatures/' . Str::uuid() . '.png';
                        Storage::disk('public')->put($imageName, $signatureData);
                        $signature = new Signature();
                        $signature->model_type = Record::class;
                        $signature->model_id = $record->id;
                        $signature->uuid = Str::uuid();
                        $signature->filename = $imageName;
                        $signature->document_filename = null;
                        $signature->certified = false;
                        $signature->from_ips = json_encode([request()->ip()]);
                        $signature->save();
                    endif;
                endif;

                return response()->json(['msg' => 'Certificate successfully created.', 'red' => route('records.show', $record->id)], 200);
            else:
                return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
            endif;
        else:
            return response()->json(['msg' => 'Jobs not found. Please select a job.'], 304);
        endif;
        /* Store or Update Record */
    }

    public function show(Record $record){
        $user_id = auth()->user()->id;
        $record->load(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company']);
        $form = JobForm::find($record->job_form_id);

        $thePdf = $this->generatePdf($record->id);
        return view('app.records.show', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => $form->name, 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'record' => $record,
            'signature' => $record->signature ? Storage::disk('public')->url($record->signature->filename) : '',
            'thePdf' => $thePdf
        ]);
    }

    public function generateCertificateNumber($record_id){
        $record = Record::find($record_id);
        $user_id = $record->created_by;
        if(empty($record->certificate_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $record->job_form_id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastCertificate = Record::where('job_form_id', $record->job_form_id)->where('created_by', $user_id)->where('id', '!=', $record_id)->orderBy('id', 'DESC')->get()->first();
            $lastCertificateNo = (isset($userLastCertificate->certificate_number) && !empty($userLastCertificate->certificate_number) ? $userLastCertificate->certificate_number : '');

             $cerSerial = $starting_form;
            if(!empty($lastCertificateNo)):
                preg_match("/(\d+)/", $lastCertificateNo, $certificateNumbers);
                $cerSerial = isset($certificateNumbers[1]) ? ((int) $certificateNumbers[1]) + 1 : $starting_form;
            endif;
            $certificateNumber = $prifix . $cerSerial;
            Record::where('id', $record_id)->update(['certificate_number' => $certificateNumber]);

            return $certificateNumber;
        else:
            return false;
        endif;
    }

    public function recordAction(Request $request){
        $user_id = auth()->user()->id;
        $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company'])
                    ->find($request->record_id);
        $form = JobForm::find($record->job_form_id);
        $submit_type = $request->submit_type;

        $pdf = Storage::disk('public')->url('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$record->certificate_number.'.pdf');

        if($submit_type == 2):
            $data = [];
            $data['status'] = 'Approved & Sent';

            Record::where('id', $request->record_id)->update($data);
            
            $email = $this->sendEmail($request->record_id);
            $message = (!$email ? 'Certificate has been approved. Email cannot be sent due to an invalid or empty email address.' : 'Certificate has been approved and a copy of the certificate mailed to the customer');
        else:
            $data = [];
            $data['status'] = 'Approved';

            Record::where('id', $request->record_id)->update($data);
            $message = 'Certificate successfully approved.';
        endif;

        return response()->json(['msg' => $message, 'red' => route('company.dashboard'), 'pdf' => $pdf]);
    }

    public function sendEmail($record_id){
        $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company'])
                    ->find($record_id);
        $user_id = (isset($record->created_by) && $record->created_by > 0 ? $record->created_by : auth()->user()->id);
        $customerName = (isset($record->customer->full_name) && !empty($record->customer->full_name) ? $record->customer->full_name : '');
        $customerEmail = (isset($record->customer->contact->email) && !empty($record->customer->contact->email) ? $record->customer->contact->email : '');
        if(!empty($customerEmail)):
            $template = JobFormEmailTemplate::where('user_id', $user_id)->where('job_form_id', $record->job_form_id)->get()->first();
            $subject = (isset($template->subject) && !empty($template->subject) ? $template->subject : $record->form->name);
            $content = (isset($template->content) && !empty($template->content) ? $template->content : '');
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
                'smtp_username' => env('MAIL_USERNAME', 'info@gascertificate.co.uk'),
                'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
                'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
                
                'from_email'    => env('MAIL_FROM_ADDRESS', 'info@gascertificate.co.uk'),
                'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'),
            ];

            $attachmentFiles = [];
            Storage::disk('public')->url('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$record->certificate_number.'.pdf');
            if (Storage::disk('public')->exists('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$record->certificate_number.'.pdf')):
                $attachmentFiles[] = [
                    "pathinfo" => 'records/'.$record->created_by.'/'.$record->job_form_id.'/'.$record->certificate_number.'.pdf',
                    "nameinfo" => $record->certificate_number.'.pdf',
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

    public function editReady(Request $request){
        $record_id = $request->record_id;

        $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company'])
                    ->find($record_id);
        $data = [
            'certificate_id' => $record->id,
            'certificate' => [
                'inspection_date' => (isset($record->inspection_date) && !empty($record->inspection_date) ? date('d-m-Y', strtotime($record->inspection_date)) : ''),
                'next_inspection_date' => (isset($record->next_inspection_date) && !empty($record->next_inspection_date) ? date('d-m-Y', strtotime($record->next_inspection_date)) : ''),
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
            ]
        ];

        $optionData = $this->sortOptionData($record_id);
        $data = array_merge($data, $optionData);
        //dd($optionData);

        return response()->json(['row' => $data, 'form' => $record->job_form_id, 'red' => route('records.create', $record->job_form_id)], 200);
    }

    public function sortOptionData($record_id){
        $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company'])
                    ->find($record_id);
        
        $data = [];
        if($record->job_form_id == 6 || $record->job_form_id == 7):
            $data['applianceCount'] = $data['commentssAnswered'] = $data['safetyChecksAnswered'] = 0;
            $data['appliances'] = $data['safetyChecks'] = $data['gsrComments'] = [];
            if(isset($record->available_options->appliances) && !empty($record->available_options->appliances)):
                foreach($record->available_options->appliances as $appliance):
                    $data['appliances'][$appliance->appliance_serial] = (array) $appliance;

                    $data['applianceCount'] += 1;
                endforeach;
            endif;
            if(isset($record->available_options->safetyChecks) && !empty($record->available_options->safetyChecks)):
                $safetyChecks = (array) $record->available_options->safetyChecks;
                $data['safetyChecks'] = $safetyChecks;
                $data['safetyChecksAnswered'] = count(array_filter($safetyChecks, function($v) { return !empty($v); }));
            endif;
            if(isset($record->available_options->gsrComments) && !empty($record->available_options->gsrComments)):
                $gsrComments = (array) $record->available_options->gsrComments;
                $data['gsrComments'] = $gsrComments;
                $data['commentssAnswered'] = count(array_filter($gsrComments, function($v) { return !empty($v); }));
            endif;
        elseif($record->job_form_id == 8):
            $data['appliances'] = $record->available_options->appliances;
        endif;

        return $data;
    }

    public function generatePdf($record_id){
        $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'options'])->find($record_id);
        
        //dd($record->available_options->appliances);
        $logoPath = resource_path('images/gas_safe_register_yellow.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $report_title = 'Certificate of '.$record->certificate_number;

        $userSignBase64 = (isset($record->user->signature) && Storage::disk('public')->exists($record->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($record->user->signature->filename)) : '');
        $signatureBase64 = ($record->signature && Storage::disk('public')->exists($record->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($record->signature->filename)) : '');
        
        switch ($record->job_form_id) {
            case '6':
                $VIEW = 'app.records.pdf.'.$record->form->slug;
                break;
            case '7':
                $VIEW = 'app.records.pdf.'.$record->form->slug;
                break;
            case '8':
                $VIEW = 'app.records.pdf.'.$record->form->slug;
                break;
            default:
                $VIEW = '';
                break;
        }


        $fileName = $record->certificate_number.'.pdf';
        if (Storage::disk('public')->exists('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName);
        }
        $pdf = Pdf::loadView($VIEW, compact('record', 'logoBase64', 'report_title', 'userSignBase64', 'signatureBase64'))
            ->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'landscape') //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName);
    }


    public function getJobs(Request $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;

        $html = '';
        $query = CustomerJob::with('customer', 'customer.address', 'property', 'priority', 'thestatus')->where('created_by', $user_id)->orderBy('id', 'DESC')->get();
        if($query->count() > 0):
            $html .= '<div class="results existingAddress">';
                $i = 1;
                foreach($query as $job):
                    $recordExist = ExistingRecordDraft::where('customer_job_id', $job->id)->where('job_form_id', $job_form_id)->get();
                    if($recordExist->count() == 0):
                        $html .= '<div data-id="'.$job->id.'" data-description="'.(!empty($job->description) ? $job->description : '').(isset($job->customer->full_name) && !empty($job->customer->full_name) ? $job->customer->full_name : '').(isset($job->customer->address->postal_code) && !empty($job->customer->address->postal_code) ? ' ('.$job->customer->address->postal_code.')' : '').'" class="customerJobItem flex items-center cursor-pointer '.($i != $query->count() ? ' mb-2' : '').' bg-white px-3 py-3">';
                            $html .= '<div>';
                                $html .= '<div class="group relative flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="briefcase" class="theIcon lucide lucide-briefcase stroke-1.5 h-4 w-4 text-primary"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path><rect width="20" height="14" x="2" y="6" rx="2"></rect></svg>';
                                    $html .= '<span style="display: none;" class="h-4 w-4 theLoader absolute left-0 top-0 bottom-0 right-0 m-auto"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#0d9488"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                                $html .= '<div>';
                                    $html .= '<div class="whitespace-normal font-medium">';
                                        $html .= $job->description;
                                    $html .= '</div>';
                                    $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                        $html .= (isset($job->customer->full_name) && !empty($job->customer->full_name) ? $job->customer->full_name : '');
                                        $html .= (isset($job->customer->address->postal_code) && !empty($job->customer->address->postal_code) ? ' ('.$job->customer->address->postal_code.')' : '');
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';

                        $i++;
                    endif;
                endforeach;
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-warning border-warning bg-opacity-20 border-opacity-5 text-warning dark:border-warning dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-circle" class="lucide lucide-alert-circle stroke-1.5 mr-2 h-6 w-6"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg><span><strong>Oops!</strong> No jobs found.</span></div>';
            return response()->json(['suc' => 2, 'html' => $html], 200);
        endif;
    }

    public function linkedJob(Request $request){
        $job_id = $request->job_id;
        $job = CustomerJob::with('customer', 'customer.address', 'property')->find($job_id);

        return response()->json(['row' => $job], 200);
    }

    public function getJobAddressrs(Request $request){
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $html = '';
        $query = CustomerProperty::with('customer')->where('customer_id', $customer_id)->orderBy('address_line_1', 'ASC')->get();
        if($query->count() > 0):
            $html .= '<div class="results existingAddress">';
                $i = 1;
                foreach($query as $property):
                    $html .= '<div data-id="'.$property->id.'" data-occupant="'.(!empty($property->occupant_name) ? $property->occupant_name : $property->customer->full_name).'" data-address="'.$property->full_address.'" class="customerJobAddressItem flex items-center cursor-pointer '.($i != $query->count() ? ' mb-2' : '').' bg-white px-3 py-3">';
                        $html .= '<div>';
                            $html .= '<div class="group flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin h-4 w-4 stroke-[1.3] text-primary"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                            $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                            $html .= '<div>';
                                $html .= '<div class="whitespace-nowrap font-medium">';
                                    $html .= $property->address_line_1.' '.$property->address_line_2;
                                $html .= '</div>';
                                $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                    $html .= $property->city.', '.$property->postal_code.', '.$property->country;
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';

                    $i++;
                endforeach;
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-warning border-warning bg-opacity-20 border-opacity-5 text-warning dark:border-warning dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-circle" class="lucide lucide-alert-circle stroke-1.5 mr-2 h-6 w-6"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg><span><strong>Oops!</strong> The customer does not have any job addresses.</span></div>';
            return response()->json(['suc' => 2, 'html' => $html], 200);
        endif;
    }

    public function storeJobAddress(JobAddressStoreRequest $request){
        $customer_id = $request->customer_id;
        $customer = Customer::find($customer_id);

        $data = [
            'customer_id' => $request->customer_id,
            'address_line_1' => (!empty($request->address_line_1) ? $request->address_line_1 : null),
            'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
            'postal_code' => $request->postal_code,
            'state' => (!empty($request->state) ? $request->state : null),
            'city' => $request->city,
            'country' => (!empty($request->country) ? $request->country : null),
            'latitude' => (!empty($request->latitude) ? $request->latitude : null),
            'longitude' => (!empty($request->longitude) ? $request->longitude : null),
            'created_by' => auth()->user()->id,
        ];
        $address = CustomerProperty::create($data);

        if($address->id):
            return response()->json(['msg' => 'Customer Job Addresses successfully created.', 'red' => '', 'address' => $address, 'id' => $address->id], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function getJobAddressOccupent(Request $request){
        $property_id = (isset($request->property_id) && $request->property_id > 0 ? $request->property_id : 0);

        $html = '';
        $property = CustomerProperty::find($property_id);
        if(!empty($property->occupant_name)):
            $html .= '<div class="results existingOccupant">';
                $html .= '<div data-id="'.$property->id.'" data-occupant="'.(!empty($property->occupant_name) ? $property->occupant_name : '').'" class="jobAddressOccupantItem flex items-center cursor-pointer bg-white px-3 py-3">';
                    $html .= '<div>';
                        $html .= '<div class="group flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user stroke-1.5 h-4 w-4 text-success"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                        $html .= '<div>';
                            $html .= '<div class="whitespace-nowrap font-medium">';
                                $html .= $property->occupant_name;
                            $html .= '</div>';
                            $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                $html .= (!empty($property->occupant_email) ? $property->occupant_email : (!empty($property->occupant_phone) ? $property->occupant_phone : ''));
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-warning border-warning bg-opacity-20 border-opacity-5 text-warning dark:border-warning dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-circle" class="lucide lucide-alert-circle stroke-1.5 mr-2 h-6 w-6"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg><span><strong>Opps!</strong> The address does not have any occupant details.</span></div>';
            return response()->json(['suc' => 2, 'html' => $html], 200);
        endif;
    }

    public function storeJobAddressOccupent(OccupantDetailsStoreRequest $request){
        $property_id = $request->customer_property_id;

        $occupant = (!empty($request->occupant_name) ? $request->occupant_name : null);
        $data = [
            'occupant_name' => (!empty($request->occupant_name) ? ucwords($request->occupant_name) : null),
            'occupant_email' => (!empty($request->occupant_email) ? $request->occupant_email : null),
            'occupant_phone' => (!empty($request->occupant_phone) ? $request->occupant_phone : null),
        ];
        $address = CustomerProperty::where('id', $property_id)->update($data);
        return response()->json(['msg' => 'Customer Job Addresses occupant details successfully created.', 'red' => '', 'occupant' => $occupant, 'id' => $property_id], 200);
       
    }

    public function getCustomers(Request $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;

        $html = '';
        $query = Customer::with('address', 'contact')->where('created_by', $user_id)->get();
        $groupedCustomer = $query->groupBy(function ($item) {
            $full_names = explode(' ', $item->full_name);
            return strtoupper(substr($full_names[0], 0, 1));
        })->sortKeys();
        
        if($query->count() > 0):
            foreach($groupedCustomer as $letter => $customers):
                $html .= '<div class="box mb-0 shadow-none rounded-none border-none customersContainer">';
                    $html .= '<div class="flex flex-col items-center bg-slate-100 px-3 py-3 dark:border-darkmode-400 sm:flex-row">';
                        $html .= '<h2 class="mr-auto font-medium uppercase text-dark">';
                            $html .= $letter;
                        $html .= '</h2>';
                    $html .= '</div>';
                    $html .= '<div class="results existingAddress">';
                        $i = 1;
                        foreach($customers as $customer):
                            $allWords = explode(' ', $customer->full_name);
                            $label = (isset($allWords[0]) && !empty($allWords[0]) ? mb_substr($allWords[0], 0, 1) : '').(count($allWords) > 1 ? mb_substr(end($allWords), 0, 1) : '');
                            
                            $html .= '<div data-id="'.$customer->id.'" data-description="'.$customer->full_name.' '.$customer->postal_code.'" class="customerItem flex items-center cursor-pointer '.($i != $customers->count() ? ' border-b border-slate-100 ' : '').' bg-white px-3 py-3">';
                                $html .= '<div>';
                                    $html .= '<div class="group relative flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                        $html .= '<span class="text-primary text-xs uppercase font-medium">'.$label.'</span>';
                                        //$html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="users" class="lucide lucide-users stroke-1.5 h-4 w-4 text-primary"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
                                        $html .= '<span style="display: none;" class="h-4 w-4 theLoader absolute left-0 top-0 bottom-0 right-0 m-auto"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#0d9488"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span>';
                                    $html .= '</div>';
                                $html .= '</div>';
                                $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                                    $html .= '<div>';
                                        $html .= '<div class="whitespace-normal font-medium">';
                                            $html .= $customer->full_name;
                                        $html .= '</div>';
                                        $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                            $html .= (isset($customer->full_address) && !empty($customer->full_address) ? $customer->full_address : 'N/A');
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
    
                            $i++;
                        endforeach;
                    $html .= '</div>';
                $html .= '</div>';
            endforeach;

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-warning border-warning bg-opacity-20 border-opacity-5 text-warning dark:border-warning dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-circle" class="lucide lucide-alert-circle stroke-1.5 mr-2 h-6 w-6"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg><span><strong>Oops!</strong> No jobs found.</span></div>';
            return response()->json(['suc' => 2, 'html' => $html], 200);
        endif;
    }

    public function getLInkedCustomer(Request $request){
        $customer = Customer::with('address')->find($request->customer_id);

        return response()->json(['customer' => $customer], 200);
    }
}
