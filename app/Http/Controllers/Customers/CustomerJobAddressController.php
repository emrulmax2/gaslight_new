<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\JobAddressStoreRequest;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerProperty;
use App\Models\Title;
use Illuminate\Http\Request;

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

    public function list(Request $request){
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);


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
                        $q->where('first_name','LIKE','%'.$queryStr.'%')
                            ->orWhere('last_name','LIKE','%'.$queryStr.'%');
                    })
                    ->orWhereHas('contact', function($q) use($queryStr){
                        $q->where('mobile','LIKE','%'.$queryStr.'%');
                    });
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
                $addressParts = array_filter([
                    $list->address_line_1,
                    $list->address_line_2,
                    $list->state,
                    $list->city,
                    $list->country,
                    $list->postal_code
                ]);
                $data[] = [
                    'id' => $list->id,
                    'sl' => $i,
                    'full_name' => $list->customer->full_name,
                    'mobile' => isset($list->contact->mobile) ? $list->contact->mobile : '',
                    'customer_id' => $list->customer_id,
                    'address' => implode(', ', $addressParts),
                    'deleted_at' => $list->deleted_at
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data]);
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
            return response()->json(['msg' => 'Customer Job Addresses successfully created.', 'red' => '', 'address' => $address_lookup, 'id' => $address->id], 200);
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
}
