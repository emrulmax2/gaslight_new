<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\JobAddressStoreRequest;
use App\Http\Requests\PropertyAddressUpdateRequest;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerProperty;
use App\Models\Title;
use Illuminate\Http\Request;
use PhpParser\Builder\Property;

class CustomerJobAddressController extends Controller
{
    public function index(Request $request){
        return view('app.customers.job-address.index', [
            'title' => 'Job Addresses - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Job Address', 'href' => route('customer.job-addresses', $request->customer_id)],
                ['label' => 'Properties', 'href' => 'javascript:void(0);'],
            ],
            'customer' => Customer::where('id', $request->customer_id)->first()
        ]);
    }

    public function list($customer_id, Request $request){
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');


        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = CustomerProperty::with('customer','contact')->orderByRaw(implode(',', $sorts))->where('customer_id', $customer_id);
        if(!empty($queryStr)):
            $query->where(function($q) use($queryStr){
                $q->where('address_line_1','LIKE','%'.$queryStr.'%')
                    ->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                    ->orWhere('postal_code','LIKE','%'.$queryStr.'%')
                    ->orWhere('state','LIKE','%'.$queryStr.'%')
                    ->orWhere('city','LIKE','%'.$queryStr.'%')
                    ->orWhere('country','LIKE','%'.$queryStr.'%')
                    ->orWhereHas('customer', function($q) use($queryStr){
                        $q->where('full_name','LIKE','%'.$queryStr.'%');
                    })
                    ->orWhereHas('contact', function($q) use($queryStr){
                        $q->where('mobile','LIKE','%'.$queryStr.'%');
                    });
            });
        endif;
        $Query= $query->get();

        $html = '';
        if($Query->count() > 0):
            foreach($Query as $list):
                $html .= '<a data-id="'.$list->id.'" href="'.route('customer.job-addresses.edit', [$list->customer_id, $list->id]).'" class="relative propoertyWrapWrap px-0 py-4 border-b border-b-slate-100 flex w-full items-center">';
                    $html .= '<div class="mr-auto">';
                        $html .= '<div class="font-medium text-dark leading-none flex justify-start items-start">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user stroke-1.5 mr-2 h-4 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                            $html .= '<span>'.$list->customer->customer_full_name.'</span>';
                        $html .= '</div>';
                        if(isset($list->contact->mobile) && !empty($list->contact->mobile)):
                        $html .= '<div class=" text-slate-500 text-xs leading-none mt-1.5 flex justify-start items-start">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="smartphone" class="lucide lucide-smartphone stroke-1.5 mr-2 h-3 w-4"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect><path d="M12 18h.01"></path></svg>';
                            $html .= '<span>'.$list->contact->mobile.'</span>';
                        $html .= '</div>';
                        endif;
                        $html .= '<div class="mt-3 text-slate-500 leading-none flex justify-start items-start">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" style="top: -3px;" class="lucide lucide-map-pin stroke-1.5 mr-2 h-4 w-4 relative"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                            $html .= '<span>'.$list->full_address.'</span>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="ml-auto">';
                        $html .= '<span class="text-slate-600">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>';
                        $html .= '</span>';
                    $html .= '</div>';
                $html .= '</a>';
            endforeach;
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg>
                        No match found.
                    </div>';
        endif;
        
        return response()->json(['html' => $html]);
    }


    public function job_address_create(Request $request){
        $customer_id = $request->customer_id;
        $customer_contact_info = CustomerContactInformation::where('customer_id', $customer_id)->first();
        return view('app.customers.job-address.create-job-address', [
                        'title' => 'Customers - Gas Certificate APP',
                        'breadcrumbs' => [
                            ['label' => 'Customers', 'href' => route('customers')],
                            ['label' => 'Properties', 'href' => 'javascript:void(0);'],
                        ],
                        'customer' => Customer::where('id', $customer_id)->first(),
                        'customer_contact_info' => $customer_contact_info,
                    ]);
    }

    public function job_address_store(JobAddressStoreRequest $request){
        $customer_id = $request->customer_id;
        $customer = Customer::find($customer_id);
        $address_lookup = $request->address_lookup;
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
            'note' => (!empty($request->note) ? $request->note : null),
            'occupant_name' => (!empty($request->occupant_name) ? $request->occupant_name : null),
            'occupant_email' => (!empty($request->occupant_email) ? $request->occupant_email : null),
            'occupant_phone' => (!empty($request->occupant_phone) ? $request->occupant_phone : null),
            'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),

            'created_by' => auth()->user()->id,
        ];
        $address = CustomerProperty::create($data);
        if($address->id):
            return response()->json(['msg' => 'Customer Job Addresses successfully created.', 'red' => route('customer.job-addresses', $customer_id), 'address' => $address_lookup, 'id' => $address->id], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function job_address_edit(Request $request, $customer_id, $address_id){

        $property = CustomerProperty::findOrFail($address_id);
        $customer = Customer::where('id', $customer_id)->firstOrFail();
        $customer_contact_info = CustomerContactInformation::where('customer_id', $customer->id)->firstOrFail();
        return view('app.customers.job-address.edit-job-address', [
                        'title' => 'Customers - Gas Certificate APP',
                        'breadcrumbs' => [
                            ['label' => 'Customers', 'href' => route('customers')],
                            ['label' => 'Properties', 'href' => 'javascript:void(0);'],
                        ],
                        'customer' => $customer,
                        'customer_contact_info' => $customer_contact_info,
                        'property' => $property,
                    ]);
    }

    public function job_address_update(JobAddressStoreRequest $request){
        $property_id = $request->property_id;
        $property = CustomerProperty::find($property_id);
        $data = [
            'address_line_1' => $request->address_line_1,
            'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
            'postal_code' => $request->postal_code,
            'state' => (!empty($request->state) ? $request->state : null),
            'city' => $request->city,
            'country' => (!empty($request->country) ? $request->country : null),
            'latitude' => (!empty($request->latitude) ? $request->latitude : null),
            'longitude' => (!empty($request->longitude) ? $request->longitude : null),
            'note' => (!empty($request->note) ? $request->note : null),
            'occupant_name' => (!empty($request->occupant_name) ? $request->occupant_name : null),
            'occupant_email' => (!empty($request->occupant_email) ? $request->occupant_email : null),
            'occupant_phone' => (!empty($request->occupant_phone) ? $request->occupant_phone : null),
            'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
            'updated_by' => auth()->user()->id,
        ];
        $property->update($data);
        return response()->json(['msg' => 'Customer Job Addresses successfully updated.', 'red' => '', 'id' => $property_id], 200);
    }

    public function job_address_destroy(Request $request){
        $property_id = $request->address_id;
        $property = CustomerProperty::find($property_id);
        $property->delete();
        return response()->json(['msg' => 'Job Addresses successfully deleted.','red' => ''], 200);
    }

    public function job_address_restore(Request $request){
        $property_id = $request->address_id;
        $property = CustomerProperty::onlyTrashed()->find($property_id);
        $property->restore();
        return response()->json(['msg' => 'Job Addresses successfully restored.','red' => ''], 200);
    }


    public function updateJobAddressData(Request $request){
        $property_id = $request->id;
        $value = (isset($request->fieldValue) && !empty($request->fieldValue) ? ($request->fieldName == 'due_date' ? (!empty($request->fieldValue) ? date('Y-m-d', strtotime($request->fieldValue)) : null) : $request->fieldValue) : null);
        $field = $request->fieldName;

        if($property_id > 0 && $field != ''):
            $property = CustomerProperty::find($property_id);
            $property->update([$field => $value]);

            return response()->json(['msg' => 'Customer Job Address data successfully updated.'], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function updateJobAddress(PropertyAddressUpdateRequest $request){
        $property = CustomerProperty::find($request->id);
        
        $addressData = [
            'address_line_1' => (!empty($request->address_line_1) ? $request->address_line_1 : null),
            'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
            'postal_code' => (!empty($request->postal_code) ? $request->postal_code : null),
            'state' => (!empty($request->state) ? $request->state : null),
            'city' => (!empty($request->city) ? $request->city : null),
            'country' => (!empty($request->country) ? $request->country : null),
            'latitude' => (!empty($request->latitude) ? $request->latitude : null),
            'longitude' => (!empty($request->longitude) ? $request->longitude : null),
        ];

        $customerUpdate = CustomerProperty::where('id', $property->id)->update($addressData);

        if($customerUpdate):
            return response()->json(['msg' => 'Customer Job Address successfully updated.', 'red' => '', ], 200);
        else:
            return response()->json(['msg' => 'No change found.', 'red' => '', ], 304);
        endif;
    }
}
