<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAddressStoreRequest;
use App\Http\Requests\OccupantDetailsStoreRequest;
use App\Models\ApplianceFlueType;
use App\Models\ApplianceLocation;
use App\Models\ApplianceTimeTemperatureHeating;
use App\Models\ApplianceType;
use App\Models\BoilerBrand;
use App\Models\Color;
use App\Models\CommissionDecommissionWorkType;
use App\Models\Customer;
use App\Models\CustomerProperty;
use App\Models\JobForm;
use App\Models\CustomerJob;
use App\Models\ExistingRecordDraft;
use App\Models\GasWarningClassification;
use App\Models\Invoice;
use App\Models\JobFormPrefixMumbering;
use App\Models\PaymentMethod;
use App\Models\PowerflushCirculatorPumpLocation;
use App\Models\PowerflushCylinderType;
use App\Models\PowerflushPipeworkType;
use App\Models\PowerflushSystemType;
use App\Models\Quote;
use App\Models\RadiatorType;
use App\Models\Relation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class NewRecordController extends Controller
{
    public function index(){
        return view('app.new-records.index', [
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
        ];

        if($form->slug == 'homeowner_gas_safety_record'):
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flue_types'] = ApplianceFlueType::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'landlord_gas_safety_record'):
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flue_types'] = ApplianceFlueType::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'gas_warning_notice'):
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['classifications'] = GasWarningClassification::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'gas_service_record'):
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'gas_breakdown_record'):
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
        elseif($form->slug == 'gas_boiler_system_commissioning_checklist'):
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
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
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
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
        return view('app.new-records.'.$form->slug.'.index', $data);
    }

    public function getJobs(Request $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;

        $html = '';
        $query = CustomerJob::with('customer', 'property', 'priority', 'status')->where('created_by', $user_id)->orderBy('id', 'DESC')->get();
        if($query->count() > 0):
            $html .= '<div class="results existingAddress">';
                $i = 1;
                foreach($query as $job):
                    $recordExist = ExistingRecordDraft::where('customer_job_id', $job->id)->where('job_form_id', $job_form_id)->get();
                    if($recordExist->count() == 0):
                        $html .= '<div data-id="'.$job->id.'" data-description="'.(!empty($job->description) ? $job->description : '').(isset($job->customer->full_name) && !empty($job->customer->full_name) ? $job->customer->full_name : '').(isset($job->customer->postal_code) && !empty($job->customer->postal_code) ? ' ('.$job->customer->postal_code.')' : '').'" class="customerJobItem flex items-center cursor-pointer '.($i != $query->count() ? ' mb-2' : '').' bg-white px-3 py-3">';
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
                                        $html .= (isset($job->customer->postal_code) && !empty($job->customer->postal_code) ? ' ('.$job->customer->postal_code.')' : '');
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
        $job = CustomerJob::with('customer', 'property')->find($job_id);

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

    public function getInvoiceNumber(Request $request){
        $user_id = auth()->user()->id;
        $form_id = $request->job_form_id;

        $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form_id)->orderBy('id', 'DESC')->get()->first();
        $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
        $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
        $userLastInvoice = Invoice::where('job_form_id', $form_id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
        $lastInvoiceNo = (isset($userLastInvoice->invoice_number) && !empty($userLastInvoice->invoice_number) ? $userLastInvoice->invoice_number : '');

        // $invSerial = $starting_form;
        // if(!empty($lastInvoiceNo)):
        //     preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
        //     $invSerial = (int) $invoiceNumbers[1] + 1;
        // endif;
        // $invoiceNumber = $prifix.str_pad($invSerial, 6, '0', STR_PAD_LEFT);

        $invSerial = $starting_form;
        if(!empty($lastInvoiceNo)):
            preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
            $invSerial = isset($invoiceNumbers[1]) ? ((int) $invoiceNumbers[1]) + 1 : $starting_form;
        endif;
        $invoiceNumber = $prifix . $invSerial;

        return response()->json(['invoiceNumber' => $invoiceNumber], 200);
    }

    public function getQuoteNumber(Request $request){
        $user_id = auth()->user()->id;
        $form_id = $request->job_form_id;

        $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form_id)->orderBy('id', 'DESC')->get()->first();
        $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
        $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
        $userLastQuote = Quote::where('job_form_id', $form_id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
        $lastQuoteNo = (isset($userLastQuote->quote_number) && !empty($userLastQuote->quote_number) ? $userLastQuote->quote_number : '');

        // $invSerial = $starting_form;
        // if(!empty($lastQuoteNo)):
        //     preg_match("/(\d+)/", $lastQuoteNo, $quoteNumbers);
        //     $invSerial = (int) $quoteNumbers[1] + 1;
        // endif;
        // $quoteNumber = $prifix.str_pad($invSerial, 6, '0', STR_PAD_LEFT);

        $qutSerial = $starting_form;
        if(!empty($lastQuoteNo)):
            preg_match("/(\d+)/", $lastQuoteNo, $quoteNumbers);
            $qutSerial = isset($quoteNumbers[1]) ? ((int) $quoteNumbers[1]) + 1 : $starting_form;
        endif;
        $quoteNumber = $prifix . $qutSerial;

        return response()->json(['quoteNumber' => $quoteNumber], 200);
    }

    public function getCustomers(Request $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;

        $html = '';
        $query = Customer::with('contact')->where('created_by', $user_id)->get();
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
                                            $html .= (isset($customer->address_line_1) && !empty($customer->address_line_1) ? $customer->address_line_1.' ' : '');
                                            $html .= (isset($customer->address_line_2) && !empty($customer->address_line_2) ? $customer->address_line_2.', ' : '');
                                            $html .= (isset($customer->city) && !empty($customer->city) ? $customer->city.', ' : '');
                                            $html .= (isset($customer->postal_code) && !empty($customer->postal_code) ? $customer->postal_code : '');
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
        $customer = Customer::find($request->customer_id);

        return response()->json(['customer' => $customer], 200);
    }
}
