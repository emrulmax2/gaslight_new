<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CommissionDecommissionWorkType;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\Invoice;
use App\Models\InvoiceOption;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use App\Models\Record;
use App\Models\RecordHistory;
use App\Models\RecordOption;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Creagia\LaravelSignPad\Signature;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class RecordController extends Controller
{
    public function store(Request $request){
        $user_id = $request->user()->id;
        $user = User::find($user_id);
        $company = (isset($user->companies[0]) && !empty($user->companies[0]) ? $user->companies[0] : []);

        $job_form_id = $request->job_form_id;
        $form = JobForm::find($job_form_id);

        $certificate_id = (isset($request->certificate_id) && $request->certificate_id > 0 ? $request->certificate_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $customer_property_occupant_id = (isset($request->customer_property_occupant_id) && $request->customer_property_occupant_id > 0 ? $request->customer_property_occupant_id : null);
        $property = CustomerProperty::find($customer_property_id);

        /* Create Job If Empty */
        if($customer_job_id == 0):
            $jobRefNo = $this->generateReferenceNo($customer_id, $company);
            $customerJob = CustomerJob::create([
                'customer_id' => $customer_id,
                'billing_address_id' => $request->billing_address_id,
                'customer_property_id' => $customer_property_id,
                'customer_property_occupant_id' => $customer_property_occupant_id,
                'description' => $form->name,
                'details' => 'Job created for '.$property->full_address,
                'reference_no' => $jobRefNo,
                'customer_job_status_id' => 1,

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
                'billing_address_id' => $request->billing_address_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,
                'customer_property_id' => $customer_property_id,
                'customer_property_occupant_id' => $customer_property_occupant_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            
            if($record->id):
                RecordHistory::create([
                    'record_id' => $record->id,
                    'action' => $certificate_id > 0 ? 'Updated' : 'Created',
                    'created_by' => $user_id,
                ]);

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

                if($job_form_id == 18):
                    $recordOption = RecordOption::where('record_id', $record->id)->where('name', 'jobSheetDocuments')->get()->first();
                    $jobSheetDocuments = (isset($recordOption->value) && !empty($recordOption->value) ? (array) $recordOption->value : []);
                    
                    if ($request->filled('job_sheet_files')):
                        $documents = $request->job_sheet_files;
                        foreach ($documents as $base64Image):
                            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)):
                                $fileType = strtolower($type[1]); // jpg, png, jpeg
                                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                            else:
                                continue;
                            endif;

                            if (!in_array($fileType, ['jpg', 'jpeg', 'png'])):
                                continue;
                            endif;

                            $imageData = base64_decode($base64Image);
                            if ($imageData === false) continue;

                            $fileName = Str::uuid() . '.' . $fileType;
                            $filePath = 'records/' . $user_id . '/' . $job_form_id . '/job_sheets/' . $fileName;
                            Storage::disk('public')->put($filePath, $imageData);
                            $jobSheetDocuments[] = $fileName;
                        endforeach;
                    endif;

                    RecordOption::where('record_id', $record->id)->where('name', 'jobSheetDocuments')->forceDelete();
                    RecordOption::create([
                        'record_id' => $record->id,
                        'job_form_id' => $job_form_id,
                        'name' => 'jobSheetDocuments',
                        'value' => $jobSheetDocuments
                    ]);
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

    public function edit($record_id, Request $request){
        try {
            $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'occupant', 'billing'])
                        ->find($record_id);
            if(isset($record->job->has_invoice) && $record->job->has_invoice):
                $record->job->invoice_id = (isset($record->job->invoice->id) && $record->job->invoice->id > 0 ? $record->job->invoice->id : null);
            endif;

            //$pdf_url = $this->generatePdf($record_id);
            $fileName = $this->generatePdfFileName($record->id);
            $pdf_url = Storage::disk('public')->url('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName);

            $data = [
                'certificate_id' => $record->id,
                'certificate' => [
                    'company_id' => $record->company_id,
                    'customer_id' => $record->customer_id,
                    'customer_job_id' => $record->customer_job_id,
                    'job_form_id' => $record->job_form_id,
                    'customer_property_id' => $record->customer_property_id,
                    'certificate_number' => $record->certificate_number,
                    'status' => $record->status,
                    'inspection_date' => (isset($record->inspection_date) && !empty($record->inspection_date) ? date('d-m-Y', strtotime($record->inspection_date)) : ''),
                    'next_inspection_date' => (isset($record->next_inspection_date) && !empty($record->next_inspection_date) ? date('d-m-Y', strtotime($record->next_inspection_date)) : ''),
                    'received_by' => (isset($record->received_by) && !empty($record->received_by) ? $record->received_by : ''),
                    'relation_id' => (isset($record->relation_id) && !empty($record->relation_id) ? $record->relation_id : ''),
                    'relation_name' => (isset($record->relation->name) && !empty($record->relation->name) ? $record->relation->name : ''),
                    'signature' => $record->signature && !empty($record->signature->filename) ? Storage::disk('public')->url($record->signature->filename) : '',
                    'email_sent_count' => $record->email_sent_count
                ],
                'job' => $record->job,
                'customer' => $record->customer,
                'job_address' => $record->job->property,
                'occupant' => (isset($record->occupant) && $record->occupant->count() > 0 ? $record->occupant : []),
                'pdf_url' => $pdf_url
            ];
            $billingAddress = [];
            if(isset($record->billing->id) && $record->billing->id > 0):
                $billingAddress = $record->billing;
            elseif(isset($record->job->billing->id) && $record->job->billing->id > 0):
                $billingAddress = $record->job->billing;
            else:
                $billingAddress = $record->customer->address;
            endif;
            if($billingAddress):
                $data['billing_address'] = [
                    'id' => $billingAddress->id ?? '0',
                    'address_line_1' => $billingAddress->address_line_1 ?? '',
                    'address_line_2' => $billingAddress->address_line_2 ?? '',
                    'postal_code' => $billingAddress->postal_code ?? '',
                    'state' => $billingAddress->state ?? '',
                    'city' => $billingAddress->city ?? '',
                    'country' => $billingAddress->country ?? '',
                    'latitude' => $billingAddress->latitude ?? '',
                    'longitude' => $billingAddress->longitude ?? '',
                ];
            endif;

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

    public function approve($record_id, Request $request)
    {
        try {
            $record = Record::findOrFail($record_id);
            $record->update([
                'status' => 'Approved'
            ]);
            RecordHistory::create([
                'record_id' => $record_id,
                'action' => 'Approved',
                'created_by' => $request->user()->id,
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

    public function approveEmail(Request $request, $record_id)
    {
        try {
            $record = Record::findOrFail($record_id);
            
            $updateResult = $record->update([
                'status' => 'Email Sent'
            ]);

            if (!$updateResult) {
                throw new \Exception("Failed to update record status");
            }

            $emailSent = false;
            $emailError = null;
            
            try {
                $emailSent = $this->sendEmail($record->id, $request->user_id);

                RecordHistory::create([
                    'record_id' => $record_id,
                    'action' => 'Email Sent',
                    'created_by' => $request->user()->id,
                ]);
            } catch (\Exception $e) {
                $emailError = $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'message' => $emailSent 
                    ? 'Record Certificate status has been updated and emailed to the customer'
                    : 'Record Certificate status has been updated but email failed: ' . 
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
        $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'occupant'])
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
            $data['applianceCount'] = $data['otherChecksAnswered'] = 0;
            $data['appliances'] = $data['otherChecks'] = [];
            if(isset($record->available_options->appliances) && !empty($record->available_options->appliances)):
                $appserial = 1;
                foreach($record->available_options->appliances as $appliance):
                    $data['appliances'][$appserial] = (array) $appliance;

                    $data['applianceCount'] += 1;
                    $appserial += 1;
                endforeach;
            endif;
            if(isset($record->available_options->otherChecks) && !empty($record->available_options->otherChecks)):
                $otherChecks = (array) $record->available_options->otherChecks;
                $data['otherChecks'] = $otherChecks;
                $data['otherChecksAnswered'] = count(array_filter($otherChecks, function($v) { return !empty($v); }));
            endif;
        elseif($record->job_form_id == 9):
            $data['appliances'] = $record->available_options->appliances;
        elseif($record->job_form_id == 10):
            $data['appliances'] = $record->available_options->appliances;
        elseif($record->job_form_id == 13):
            $data['appliances'] = $record->available_options->appliances;
        elseif($record->job_form_id == 15):
            $data['checklistAnswered'] = $data['radiatorCount'] = 0;
            $data['radiators'] = $data['powerFlushChecklist'] = [];
            if(isset($record->available_options->radiators) && !empty($record->available_options->radiators)):
                $red = 1;
                foreach($record->available_options->radiators as $radiator):
                    $data['radiators'][$red] = (array) $radiator;

                    $data['radiatorCount'] += 1;
                    $red++;
                endforeach;
            endif;
            if(isset($record->available_options->powerFlushChecklist) && !empty($record->available_options->powerFlushChecklist)):
                $powerFlushChecklist = (array) $record->available_options->powerFlushChecklist;
                $data['powerFlushChecklist'] = $powerFlushChecklist;
                $data['checklistAnswered'] = count(array_filter($powerFlushChecklist, function($v) { return !empty($v); }));
            endif;
        elseif($record->job_form_id == 16):
            $data['applianceAnswered'] = 0;
            $data['appliances'] = [];
            if(isset($record->available_options->appliances) && !empty($record->available_options->appliances)):
                $appliances = (array) $record->available_options->appliances;
                $data['appliances'] = $appliances;
                $data['applianceAnswered'] = count(array_filter($appliances, function($v) { return !empty($v); }));
            endif;
        elseif($record->job_form_id == 17):
            $data['systemAnswered'] = $data['inspectionAnswered'] = 0;
            $data['unventedSystems'] = $data['inspectionRecords'] = [];
            if(isset($record->available_options->unventedSystems) && !empty($record->available_options->unventedSystems)):
                $unventedSystems = (array) $record->available_options->unventedSystems;
                $data['unventedSystems'] = $unventedSystems;
                $data['systemAnswered'] = count(array_filter($unventedSystems, function($v) { return !empty($v); }));
            endif;
            if(isset($record->available_options->inspectionRecords) && !empty($record->available_options->inspectionRecords)):
                $inspectionRecords = (array) $record->available_options->inspectionRecords;
                $data['inspectionRecords'] = $inspectionRecords;
                $data['inspectionAnswered'] = count(array_filter($inspectionRecords, function($v) { return !empty($v); }));
            endif;
        elseif($record->job_form_id == 18):
            $data['jobSheetAnswered'] = $data['jobSheetDocumentsCount'] = 0;
            $data['jobSheets'] = [];
            $data['jobSheetDocuments'] = [];
            if(isset($record->available_options->jobSheets) && !empty($record->available_options->jobSheets)):
                $jobSheets = (array) $record->available_options->jobSheets;
                $data['jobSheets'] = $jobSheets;
                $data['jobSheetAnswered'] = count(array_filter($jobSheets, function($v) { return !empty($v); }));
            endif;
            if(isset($record->available_options->jobSheetDocuments) && !empty($record->available_options->jobSheetDocuments)):
                $jobSheetsDocs = (array) $record->available_options->jobSheetDocuments;
                $i = 1;
                foreach($jobSheetsDocs as $doc):
                    if(!empty($doc) && Storage::disk('public')->exists('records/'.$record->created_by.'/'.$record->job_form_id.'/job_sheets/'.$doc)):
                        $documentUrl = Storage::disk('public')->url('records/'.$record->created_by.'/'.$record->job_form_id.'/job_sheets/'.$doc);
                        $data['jobSheetDocuments'][$i]['name'] = $doc;
                        $data['jobSheetDocuments'][$i]['url'] = $documentUrl;
                        $i++;
                    endif;
                endforeach;
            endif;
        endif;

        return $data;
    }

    public function generatePdf($record_id){
        $worktypes = CommissionDecommissionWorkType::where('active', 1)->orderBy('id', 'ASC')->get();
        $record = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'options', 'billing'])->find($record_id);
       
        //dd($record->available_options->invoiceItems);
        $palmPath = resource_path('images/palm-of-hand.png');
        $palmBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($palmPath));

        $logoPath = resource_path('images/gas_safe_register_yellow.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $report_title = 'Certificate of '.$record->certificate_number;

        $userSignBase64 = (isset($record->user->signature) && Storage::disk('public')->exists($record->user->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($record->user->signature->filename)) : '');
        $signatureBase64 = ($record->signature && Storage::disk('public')->exists($record->signature->filename) ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($record->signature->filename)) : '');
        
        $VIEW = 'app.records.pdf.'.$record->form->slug;
        $fileName = $this->generatePdfFileName($record->id);
        
        if (Storage::disk('public')->exists('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName)) {
            Storage::disk('public')->delete('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName);
        }
        $paper = ($record->job_form_id == 3 || $record->job_form_id == 4 ? 'portrait' : 'landscape');
        $pdf = Pdf::loadView($VIEW, compact('record', 'logoBase64', 'report_title', 'userSignBase64', 'signatureBase64', 'worktypes', 'palmBase64'))
            ->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', $paper) //portrait landscape
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName);
    }

    public function sendEmail($record_id){
        $record = Record::with([
            'customer', 
            'property', 
            'customer.address', 
            'customer.contact', 
            'job', 
            'job.property', 
            'job.calendar',
            'job.calendar.slot',
            'form', 
            'user', 
            'user.company'])->find($record_id);
        $user_id = (isset($record->created_by) && $record->created_by > 0 ? $record->created_by : auth()->user()->id);
        $companyName = $record->user->companies->pluck('company_name')->first(); 
        $companyEmail = $record->user->companies->pluck('company_email')->first(); 
        $customerName = (isset($record->customer->full_name) && !empty($record->customer->full_name) ? $record->customer->full_name : '');
        $customerEmail = (isset($record->customer->contact->email) && !empty($record->customer->contact->email) ? $record->customer->contact->email : '');
        if(!empty($customerEmail)):
            $template = JobFormEmailTemplate::with('attachment')->where('user_id', $user_id)->where('job_form_id', $record->job_form_id)->get()->first();
            $emailData = ($template ? $this->renderEmailTemplate($record, $template) : []);

            $subject = (isset($emailData['subject']) && !empty($emailData['subject']) ? $emailData['subject'] : $record->form->name);
            $templateTitle = $subject;
            $content = (isset($emailData['content']) && !empty($emailData['content']) ? $emailData['content'] : '');
            $ccMail = (isset($emailData['cc_email_address']) && !empty($emailData['cc_email_address']) ? $emailData['cc_email_address'] : []);
            $ccMail[] = $record->user->email;

            if($content == ''):
                $content .= 'Hi '.$customerName.',<br/><br/>';
                $content .= 'Please check attachment for details.<br/><br/>';
                $content .= 'Thanks & Regards<br/>';
                $content .= !empty($companyName) ? $companyName : $record->user->name;
            endif;
            
            $sendTo = [$customerEmail]; 
            $configuration = [
                'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'smtp_port' => env('MAIL_PORT', '587'),
                'smtp_username' => env('MAIL_USERNAME', 'info@gascertificate.co.uk'),
                'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
                'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
                
            ];
            $configuration['from_name'] = !empty($companyName) ? $companyName : $record->user->name; 
            $configuration['from_email'] = !empty($companyEmail) ? $companyEmail : $record->user->email; 

            $attachmentFiles = [];
            $fileName = $this->generatePdfFileName($record->id);
            if (Storage::disk('public')->exists('records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName)):
                $attachmentFiles[0] = [
                    "pathinfo" => 'records/'.$record->created_by.'/'.$record->job_form_id.'/'.$fileName,
                    "nameinfo" => $fileName,
                    "mimeinfo" => 'application/pdf',
                    "disk" => 'public'
                ];
            endif;
            if(isset($emailData['attachmentFiles']) && !empty($emailData['attachmentFiles'])):
                $attachmentFiles = array_merge($attachmentFiles, $emailData['attachmentFiles']);
            endif;

            GCEMailerJob::dispatch($configuration, $sendTo, new GCESendMail($subject, $content, $attachmentFiles, $templateTitle, 'certificate'), $ccMail); 
            return true;
        else:
            return false;
        endif;
    }

    public function renderEmailTemplate(Record $record, JobFormEmailTemplate $template): array {
        // Build shortcode replacements
        $companyName = $record->user->companies->pluck('company_name')->first();
        $shortcodes = [
            ':customername'         => $record->customer->full_name ?? '',
            ':customercompany'      => $record->customer->company_name ?? '',
            ':jobref'               => $record->job->reference_no ?? '',
            ':jobbuilding'          => isset($record->job->property->address_line_1) && !empty($record->job->property->address_line_1) ? $record->job->property->address_line_1 : '',
            ':jobstreet'            => isset($record->job->property->address_line_2) && !empty($record->job->property->address_line_1) ? $record->job->property->address_line_2 : '',
            ':jobregion'            => isset($record->job->property->state) && !empty($record->job->property->state) ? $record->job->property->state : '',
            ':jobpostcode'          => isset($record->job->property->postal_code) && !empty($record->job->property->postal_code) ? $record->job->property->postal_code : '',
            ':jobtown'              => isset($record->job->property->city) && !empty($record->job->property->city) ? $record->job->property->city : '',
            ':propertyaddress'      => isset($record->property->full_address) && !empty($record->property->full_address) ? $record->property->full_address : '',
            ':contactphone'         => isset($record->user->mobile) && !empty($record->user->mobile) ? $record->user->mobile : '',
            ':companyname'          => $companyName ?? '',
            ':engineername'         => $record->user->name ?? '',
            ':eventdate'            => isset($record->job->calendar->date) && !empty($record->job->calendar->date) ? date('d-m-Y', strtotime($record->job->calendar->date)) : '',
            ':eventtime'            => (isset($record->job->calendar->slot->start) && !empty($record->job->calendar->slot->start) ? date('H:i', strtotime($record->job->calendar->slot->start)) : '').(isset($record->job->calendar->slot->end) && !empty($record->job->calendar->slot->end) ? ' - '.date('H:i', strtotime($record->job->calendar->slot->end)) : ''),
            // Add more shortcodes as needed
        ];

        // Replace shortcodes in subject and content
        $subject = str_replace(array_keys($shortcodes), array_values($shortcodes), $template->subject);
        $content = str_replace(array_keys($shortcodes), array_values($shortcodes), $template->content);

        $attachmentFiles = [];
        if(isset($template->attachment) && $template->attachment->count() > 0):
            $i = 1;
            foreach($template->attachment as $attachment):
                if(isset($attachment->download_url) && !empty($attachment->download_url)):
                    $attachmentFiles[$i] = [
                        "pathinfo" => 'template_attachments/'.$template->id.'/'.$attachment->current_file_name,
                        "nameinfo" => $attachment->current_file_name,
                        "mimeinfo" => $attachment->doc_type,
                        "disk" => 'public'
                    ];
                    $i++;
                endif;
            endforeach;
        endif;

        return [
            'subject' => $subject,
            'content' => $content,
            'attachmentFiles' => $attachmentFiles,
            'cc_email_address' => !empty($template->cc_email_address) ? explode(',', $template->cc_email_address) : [],
        ];
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

    private function generateReferenceNo($customerId, $company){
        $customer = Customer::find($customerId);
        if (!$customer) return null;
        
        $nameParts = (isset($company->company_name) && !empty($company->company_name) ? explode(' ', $company->company_name) : []);
        //$nameParts = explode(' ', trim($customer->company_name));
        $prefix = '';
        foreach ($nameParts as $part):
            $prefix .= strtoupper(substr($part, 0, 1));
        endforeach;
        $lastJob = CustomerJob::where('customer_id', $customerId)->orderBy('id', 'desc')->first();

        if ($lastJob && preg_match('/\d+$/', $lastJob->reference_no, $matches)):
            $nextNumber = intval($matches[0]) + 1;
        else:
            $nextNumber = 1;
        endif;
        $referenceNo = $prefix . $nextNumber;

        return $referenceNo;
    }

    function generatePdfFileName($record_id){
        $record = Record::with('job', 'job.property')->find($record_id);
        $address_line_1 = $record->job->property->address_line_1;
        $address_line_2 = $record->job->property->address_line_2;
        $postal_code = $record->job->property->postal_code;
        $certificate_no = $record->certificate_number;

        // Concatenate the fields
        $fileName = "{$address_line_1}_{$address_line_2}_{$postal_code}_{$certificate_no}";
        // Replace any non-alphanumeric characters with underscores
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '_', $fileName);
        // Replace multiple consecutive underscores with a single underscore
        $fileName = preg_replace('/_+/', '_', $fileName);
        // Trim underscores from start and end
        $fileName = trim($fileName, '_');
        // Optionally lowercase
        $fileName = Str::lower($fileName);
        // Add PDF extension
        return $fileName . '.pdf';
    }

    public function convertToInvoice(Request $request, $record_id){
        try {
            $invoice = DB::transaction(function () use ($request, $record_id) {
                $user_id = $request->user()->id;
                $user = User::find($user_id);
                $record = Record::with('job')->find($record_id);
                $company = (isset($user->companies[0]) && !empty($user->companies[0]) ? $user->companies[0] : []);

                $non_vat_status = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? 0 : 1);
                $vat_number = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? $user->companies[0]->vat_number : '');
                
                $vat_rate = 20;
                $unit_price = (isset($record->job->estimated_amount) && $record->job->estimated_amount > 0 ? $record->job->estimated_amount : 0);
                $unit = 1; 
                $subTotal = $unit_price * $unit;
                $vatAmount = ($non_vat_status ? 0 : ($subTotal / $vat_rate) * 100);
                $lineTotal = $subTotal + $vatAmount; 

                $invoice = Invoice::create([
                    'company_id' => auth()->user()->companies->pluck('id')->first(),
                    'customer_id' => $record->customer_id,
                    'billing_address_id' => $record->billing_address_id ?? null,
                    'customer_job_id' => $record->customer_job_id,
                    'job_form_id' => 4,
                    'customer_property_id' => ($record->customer_property_id > 0 ? $record->customer_property_id : (isset($record->job->customer_property_id) && $record->job->customer_property_id > 0 ? $record->job->customer_property_id : null)),
                    
                    'issued_date' => date('Y-m-d'),
                    'expire_date' => date('Y-m-d', strtotime("+30 days")),
                    
                    'updated_by' => $user_id,
                ]);
                $invoice_number = $this->generateInvoiceNumber($invoice->id);

                $invoiceItems[1] = [
                    'vat' => $vat_rate,
                    'edit' => 0,
                    'price' => $unit_price,
                    'units' => $unit,
                    'line_total' => $lineTotal,
                    'description' => (isset($record->job->description) && !empty($record->job->description) ? $record->job->description : 'Invoice Item 01'),
                    'inv_item_title' => (isset($record->job->description) && !empty($record->job->description) ? $record->job->description : 'Invoice Item 01'),
                    'inv_item_serial' => 1
                ];
                InvoiceOption::create([
                    'invoice_id' => $invoice->id,
                    'name' => 'invoiceItems',
                    'value' => $invoiceItems
                ]);

                $invoiceExtra = [
                    'non_vat_invoice' => $non_vat_status,
                    'vat_number' => $vat_number,
                ];
                if(isset($company->bank->payment_term) && !empty($company->bank->payment_term)):
                    $invoiceExtra['payment_term'] = (isset($company->bank->payment_term) && !empty($company->bank->payment_term) ? $company->bank->payment_term : '');
                else:
                    $invoiceExtra['payment_term'] = '';
                endif;
                InvoiceOption::create([
                    'invoice_id' => $invoice->id,
                    'name' => 'invoiceExtra',
                    'value' => $invoiceExtra
                ]);

                return $invoice;
            });

            return response()->json([
                'success' => true,
                'message' => 'Records successfully converted to invoice.',
                'data' => $invoice
            ], 200);
        }catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function generateInvoiceNumber($invoice_id){
        $invoice = Invoice::find($invoice_id);
        $user_id = $invoice->created_by;
        if(empty($invoice->invoice_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $invoice->job_form_id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastInvoice = Invoice::where('created_by', $user_id)->where('id', '!=', $invoice_id)->orderBy('id', 'DESC')->get()->first();
            $lastInvoiceNo = (isset($userLastInvoice->invoice_number) && !empty($userLastInvoice->invoice_number) ? $userLastInvoice->invoice_number : '');

             $cerSerial = $starting_form;
            if(!empty($lastInvoiceNo)):
                preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
                $cerSerial = isset($invoiceNumbers[1]) ? ((int) $invoiceNumbers[1]) + 1 : $starting_form;
            endif;
            $invoiceNumber = $prifix . $cerSerial;
            Invoice::where('id', $invoice_id)->update(['invoice_number' => $invoiceNumber]);

            return $invoiceNumber;
        else:
            return false;
        endif;
    }

    public function destroyJobSheetDoc(Request $request){
        $record_id = $request->record_id;
        $document_name = $request->document_name;
        $record = Record::find($record_id);

        $jobSheetsDocs = (array) $record->available_options->jobSheetDocuments;
        if(!empty($jobSheetsDocs) && !empty($document_name)):
            if (($key = array_search($document_name, $jobSheetsDocs)) !== false) :
                unset($jobSheetsDocs[$key]);
                if (Storage::disk('public')->exists('records/'.$record->created_by.'/'.$record->job_form_id.'/job_sheets/'.$document_name)) {
                    Storage::disk('public')->delete('records/'.$record->created_by.'/'.$record->job_form_id.'/job_sheets/'.$document_name);
                }
            endif;

            RecordOption::where('record_id', $record_id)->where('job_form_id', $record->job_form_id)->where('name', 'jobSheetDocuments')->update([
                'value' => $jobSheetsDocs,
            ]);

            return response()->json(['msg' => 'Job Sheet document Successfully deleted.', 'red' => ''], 200);
            return response()->json([
                'success' => true,
                'message' => 'Job Sheet Succesfully deleted',
            ], 200);
        else:
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 422);
        endif;
    }
}
