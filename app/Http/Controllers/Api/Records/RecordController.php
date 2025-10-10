<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use App\Models\Record;
use App\Models\RecordOption;
use Barryvdh\DomPDF\Facade\Pdf;
use Creagia\LaravelSignPad\Signature;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecordController extends Controller
{
    public function store(Request $request){
        $user_id = $request->user()->id;
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

                'created_by' => $user_id
            ]);
            $customer_job_id = ($customerJob->id ? $customerJob->id : $customer_job_id);
        endif;
        /* Create Job If Empty */

        /* Store or Update Record */
        if($customer_job_id > 0):
            $record = Record::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'company_id' => $request->user()->companies->pluck('id')->first(),
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
                $options = $request->options;
                RecordOption::where('record_id', $record->id)->forceDelete();
                if(!empty($options)):
                    foreach($options as $key => $value):
                        RecordOption::create([
                            'record_id' => $record->id,
                            'job_form_id' => $job_form_id,
                            'name' => $key,
                            'value' => $value
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

                return response()->json([
                    'success' => true,
                    'message' => 'Certificate successfully created.',
                    'data' =>  Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'options'])->findOrFail($record->id),
                ], 200);
            else:
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later or contact with the administrator.'
                ], 400);
            endif;
        else:
            return response()->json([
                'success' => false,
                'message' => 'Jobs not found. Please select a job.'
            ], 400);
        endif;
        /* Store or Update Record */
    }

    public function edit($record_id, Request $record){
        try {
            $pdf_url = $this->generatePdf($record_id);
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
                ],
                'pdf_url' => $pdf_url
            ];

            $optionData = $this->sortOptionData($record_id);
            $data = array_merge($data, $optionData);

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found. The requested (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function approve($record_id)
    {
        try {
            $record = Record::findOrFail($record_id);
            $record->update([
                'status' => 'Approved'
            ]);

            return response()->json([
                    'success' => true,
                    'message' => 'Record successfully approved.',
                    'record_id' => $record->id
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found. Record (ID: '.$record_id.') does not exist or may have been deleted.',
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
            $record = Record::findOrFail($record_id);
            
            $updateResult = $record->update([
                'status' => 'Approved & Sent'
            ]);

            if (!$updateResult) {
                throw new \Exception("Failed to update record status");
            }

            $emailSent = false;
            $emailError = null;
            
            try {
                $emailSent = $this->sendEmail($record->id, $request->user_id);
            } catch (\Exception $e) {
                $emailError = $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'message' => $emailSent 
                    ? 'Record Certificate has been approved and emailed to the customer'
                    : 'Record Certificate has been approved but email failed: ' . 
                    ($emailError ?: 'Invalid or empty email address'),
                'record_id' => $record_id
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found. The requested record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
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
            case '9':
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

    public function sendEmail($record_id, $user_id){
        $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company'])
                    ->find($record_id);
        $user_id = (isset($record->created_by) && $record->created_by > 0 ? $record->created_by : $user_id);
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

    public function download($record_id)
    {
        try {
            $record = Record::findOrFail($record_id);
            $thePdf = $this->generatePdf($record_id);

            return response()->json([
                    'success' => true,
                    'download_url' => $thePdf,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found. The requested record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
        
    }
}
