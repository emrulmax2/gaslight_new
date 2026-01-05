<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerAddressUpdateRequest;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\Title;
use App\Http\Requests\CustomerCreateRequest;
use App\Models\CustomerJobPriority;
use App\Models\CustomerJobStatus;
use App\Models\CustomerProperty;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index(){
        return view('app.customers.index', [
            'title' => 'Customers - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customers', 'href' => 'javascript:void(0);'],
            ],
        ]);
    }

    public function list(Request $request){
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');


        $query = Customer::with('title', 'contact', 'address')->where('created_by', auth()->user()->id);
        if(!empty($queryStr)):
            $query->whereHas('address', function($q) use($queryStr){
                $q->where('full_name','LIKE','%'.$queryStr.'%')
                    ->orWhere('company_name','LIKE','%'.$queryStr.'%')->orWhere('vat_no','LIKE','%'.$queryStr.'%')
                    ->orWhere('address_line_1','LIKE','%'.$queryStr.'%')->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                    ->orWhere('postal_code','LIKE','%'.$queryStr.'%')->orWhere('city','LIKE','%'.$queryStr.'%');
            });
        endif;
        $query = $query->get();
        $groupedCustomer = $query->groupBy(function ($item) {
            $full_names = explode(' ', $item->full_name);
            return strtoupper(substr($full_names[0], 0, 1));
        })->sortKeys();

        $html = '';
        if($groupedCustomer->count() > 0):
            foreach($groupedCustomer as $letter => $customers):
                $html .= '<div class="box mb-0 shadow-none rounded-none border-none">';
                    $html .= '<div class="flex flex-col items-center bg-slate-200 px-3 py-3 dark:border-darkmode-400 sm:flex-row">';
                        $html .= '<h2 class="mr-auto font-medium uppercase text-dark">';
                            $html .= $letter;
                        $html .= '</h2>';
                    $html .= '</div>';
                    $html .= '<div class="results existingAddress">';
                        $i = 1;
                        foreach($customers as $customer):
                            $allWords = explode(' ', $customer->full_name);
                            $label = (isset($allWords[0]) && !empty($allWords[0]) ? mb_substr($allWords[0], 0, 1) : '').(count($allWords) > 1 ? mb_substr(end($allWords), 0, 1) : '');
                            
                            $html .= '<div data-id="'.$customer->id.'" data-description="'.$customer->full_name.' '.$customer->postal_code.'" class="customer_row flex items-center cursor-pointer '.($i != $customers->count() ? ' border-b border-slate-100 ' : '').' bg-white px-3 py-3">';
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
                                        $html .= '<div class="mt-0.5 whitespace-normal text-xs text-slate-500">';
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
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg>
                        No match found.
                    </div>';
        endif;
        
        return response()->json(['html' => $html]);
    }

    public function listOld(Request $request){
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = Customer::with('title', 'contact')->orderByRaw(implode(',', $sorts))->where('created_by', auth()->user()->id);
        if(!empty($queryStr)):
            $query->where(function($q) use($queryStr){
                $q->where('full_name','LIKE','%'.$queryStr.'%')
                    ->orWhere('company_name','LIKE','%'.$queryStr.'%')->orWhere('vat_no','LIKE','%'.$queryStr.'%')
                    ->orWhere('address_line_1','LIKE','%'.$queryStr.'%')->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                    ->orWhere('postal_code','LIKE','%'.$queryStr.'%')->orWhere('city','LIKE','%'.$queryStr.'%');
            });
        endif;
        if($status == 2):
            $query->onlyTrashed();
        endif;

        $total_rows = $query->count();
        $page = (isset($request->page) && $request->page > 0 ? $request->page : 0);
        $perpage = (isset($request->size) && $request->size == 'true' ? $total_rows : ($request->size > 0 ? $request->size : 10));
        $last_page = $total_rows > 0 ? ceil($total_rows / $perpage) : '';
        
        $limit = $perpage;
        $offset = ($page > 0 ? ($page - 1) * $perpage : 0);

        $Query= $query->skip($offset)
               ->take($limit)
               ->get();

        $data = array();

        if(!empty($Query)):
            $i = 1;
            foreach($Query as $list):
                $data[] = [
                    'id' => $list->id,
                    'sl' => $i,
                    'full_name' => $list->full_name,
                    'customer_full_name' => $list->customer_full_name,
                    'company_name' => $list->company_name,
                    'vat_no' => $list->vat_no,
                    'email' => (isset($list->contact->email) && !empty($list->contact->email) ? $list->contact->email : ''),
                    'mobile' => (isset($list->contact->mobile) && !empty($list->contact->mobile) ? $list->contact->mobile : ''),
                    'address_line_1' => $list->address_line_1,
                    'address_line_2' => $list->address_line_2,
                    'city' => $list->city,
                    'state' => $list->state,
                    'postal_code' => $list->postal_code,
                    'deleted_at' => $list->deleted_at
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data]);
    }

    public function create(){
        return view('app.customers.create', [
            'title' => 'Customers - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customers', 'href' => 'javascript:void(0);'],
                ['label' => 'New Customer', 'href' => 'javascript:void(0);'],
            ],
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get()
        ]);
    }

    public function store(CustomerCreateRequest $request){
        $data = [
            'company_id' => auth()->user()->companies->pluck('id')->first(),
            //'title_id' => (!empty($request->title_id) ? $request->title_id : null),
            'full_name' => (isset($request->full_name) && !empty($request->full_name) ? $request->full_name : null),
            'company_name' => (!empty($request->company_name) ? $request->company_name : null),
            //'vat_no' => (!empty($request->vat_no) ? $request->vat_no : null),
            'note' => (!empty($request->note) ? $request->note : null),
            'auto_reminder' => (isset($request->auto_reminder) && $request->auto_reminder > 0 ? $request->auto_reminder : 0),
            'created_by' => auth()->user()->id
        ];
        $customer = Customer::create($data);
        if($customer->id):
            $CustomerProperty = CustomerProperty::create([
                'customer_id' => $customer->id,
                'is_primary' => 1,
                'address_line_1' => (!empty($request->address_line_1) ? $request->address_line_1 : null),
                'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
                'postal_code' => (!empty($request->postal_code) ? $request->postal_code : null),
                'state' => (!empty($request->state) ? $request->state : null),
                'city' => (!empty($request->city) ? $request->city : null),
                'country' => (!empty($request->country) ? $request->country : null),
                'note' => (!empty($request->note) ? $request->note : null),
                'latitude' => (!empty($request->latitude) ? $request->latitude : null),
                'longitude' => (!empty($request->longitude) ? $request->longitude : null),
    
                'created_by' => auth()->user()->id,
            ]);
            
            $contact = CustomerContactInformation::create([
                'customer_id' => $customer->id,
                'mobile' => (!empty($request->mobile) ? $request->mobile : null),
                'phone' => (!empty($request->phone) ? $request->phone : null),
                'email' => (!empty($request->email) ? $request->email : null),
                //'other_email' => (!empty($request->other_email) ? $request->other_email : null),
                'created_by' => auth()->user()->id
            ]);

            $customerData = [
                'id' => $customer->id,
                'full_name' => $customer->full_name,
                'address_line_1' => $CustomerProperty->address_line_1,
                'address_line_2' => $CustomerProperty->address_line_2,
                'postal_code' => $CustomerProperty->postal_code,
                'state' => $CustomerProperty->state,
                'city' => $CustomerProperty->city,
                'country' => $CustomerProperty->country,
                'mobile' => (!empty($request->mobile) ? $request->mobile : ''),
            ];
            return response()->json([
                'msg' => 'Customer successfully created.',
                'record' => $request->record,
                'red' => isset(request()->record) && !empty(request()->record) 
                    ? route('jobs.create', ['record' => request()->record])
                    : route('customers'),
                'customer' => $customerData
            ], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function edit(Customer $customer){
        $customer->load(['address', 'title', 'contact']);
        return view('app.customers.edit', [
            'title' => 'Customers - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customers', 'href' => 'javascript:void(0);'],
                ['label' => 'edit', 'href' => 'javascript:void(0);'],
            ],
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get(),
            'customer' => $customer
        ]);
    }

    public function update(CustomerCreateRequest $request){
        $customer_id = $request->id;

        $data = [
            //'title_id' => (!empty($request->title_id) ? $request->title_id : null),
            'full_name' => (isset($request->full_name) && !empty($request->full_name) ? $request->full_name : null),
            'updated_by' => auth()->user()->id
        ];
        $customer = Customer::where('id', $customer_id)->update($data);

        return response()->json(['msg' => 'Customer successfully updated.', 'red' => ''], 200);
    }

    public function destroy($customer_id){
        $customer = Customer::find($customer_id)->delete();

        return response()->json(['msg' => 'Customer data successfully deleted.', 'red' => ''], 200);
    }

    public function restore($customer_id){
        $customer = Customer::where('id', $customer_id)->withTrashed()->restore();

        return response()->json(['msg' => 'Customer data Successfully Restored!', 'red' => ''], 200);
    }

    public function getDetails(Request $request){
        $customer_id = $request->customer_id;
        $customer = Customer::find($customer_id);
        return response()->json(['row' => $customer], 200);
    }

    public function search(Request $request){
        $queryStr = (isset($request->the_search_query) && !empty($request->the_search_query) ? $request->the_search_query : '');

        $html = '';
        $query = Customer::with('address', 'title', 'contact')->where(function($q) use($queryStr){
                    $q->where('full_name','LIKE','%'.$queryStr.'%')
                        ->orWhere('company_name','LIKE','%'.$queryStr.'%')->orWhere('vat_no','LIKE','%'.$queryStr.'%');
                })->orWhereHas('address', function($q) use($queryStr){
                    $q->orWhere('address_line_1','LIKE','%'.$queryStr.'%')->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                        ->orWhere('postal_code','LIKE','%'.$queryStr.'%')->orWhere('city','LIKE','%'.$queryStr.'%');
                })->orderBy('full_name')->get();
        if($query->count() > 0):
            $html .= '<div class="py-2 px-5 text-xs font-semibold bg-slate-100 rounded-md rounded-bl-none rounded-br-none">'.($query->count() == 1 ? $query->count().' result' : $query->count().' results').' found</div>';
                $html .= '<div class="results px-5 py-4" style="max-height: 250px; overflow-y: auto;">';
                    $i = 1;
                    foreach($query as $customer):
                        $html .= '<div data-id="'.$customer->id.'" data-title="'.$customer->customer_full_name.'" class="searchResultItems flex items-center cursor-pointer '.($i != $query->count() ? ' pb-3 border-b border-slate-100 mb-3' : '').'">';
                            $html .= '<div>';
                                $html .= '<div class="group flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user h-4 w-4 stroke-[1.3] text-primary"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                                    //$html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin h-4 w-4 stroke-[1.3] text-primary"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                                $html .= '<div>';
                                    $html .= '<div class="whitespace-nowrap font-medium">';
                                        $html .= $customer->customer_full_name;
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

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            return response()->json(['suc' => 2, 'html' => ''], 200);
        endif;
    }

    public function updateFieldValue(Request $request){
        $customer_id = $request->id;
        $value = (isset($request->fieldValue) && !empty($request->fieldValue) ? $request->fieldValue : null);
        $field = $request->fieldName;

        if($customer_id > 0 && $field != '' && !empty($request->theModel)):
            if($request->theModel == 'contact'):
                $contactInfo = CustomerContactInformation::where('customer_id', $customer_id);
                $contactInfo->update([$field => $value]);
            else:
                $customer = Customer::find($customer_id);
                $customer->update([$field => $value]);
            endif;

            return response()->json(['msg' => 'Customer data successfully updated.'], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function updateAddressInfo(CustomerAddressUpdateRequest $request){
        $customer = Customer::with('address')->find($request->customer_id);
        $postal_code = (!empty($request->postal_code) ? $request->postal_code : null);
        $address_line_1 = (!empty($request->address_line_1) ? $request->address_line_1 : null);
        $address_line_2 = (!empty($request->address_line_2) ? $request->address_line_2 : null);
        $city = (!empty($request->city) ? $request->city : null);

        
        $addressData = [
            'customer_id' => $request->customer_id,
            'address_line_1' => $address_line_1,
            'address_line_2' => $address_line_2,
            'postal_code' => $postal_code,
            'state' => (!empty($request->state) ? $request->state : null),
            'city' => $city,
            'country' => (!empty($request->country) ? $request->country : null),
            'latitude' => (!empty($request->latitude) ? $request->latitude : null),
            'longitude' => (!empty($request->longitude) ? $request->longitude : null),
        ];

        try{
            if( (!isset($customer->address->postal_code) || $customer->address->postal_code != $postal_code) || 
                (!isset($customer->address->address_line_1) || $customer->address->address_line_1 != $address_line_1) || 
                (!isset($customer->address->address_line_2) || $customer->address->address_line_2 != $address_line_2) || 
                (!isset($customer->address->city) || $customer->address->city != $city)
            ):
                CustomerProperty::where('customer_id', $request->customer_id)->where('is_primary', 1)->update([
                    'is_primary' => 0, 
                    'updated_by' => auth()->user()->id
                ]);

                $addressData['is_primary'] = 1;
                $addressData['note'] = null;
                $addressData['created_by'] = auth()->user()->id;
                $CustomerProperty = CustomerProperty::create($addressData);
            else:
                $addressData['updated_by'] = auth()->user()->id;
                CustomerProperty::where('customer_id', $request->customer_id)->where('is_primary', 1)->update($addressData);
            endif;
            return response()->json(['msg' => 'Customer Address successfully updated.', 'red' => '', ], 200);
        }catch(Exception $e){
            return response()->json(['msg' => 'Something went wrong. Please try again later.', 'red' => '', ], 304);
        }
    }
}
