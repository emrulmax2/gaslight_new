<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerCreateRequest;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerProperty;
use App\Models\Title;
use Illuminate\Http\Request;

class JobAddressController extends Controller
{
    public function index(){
        return view('app.jobAddress.index', [
            'title' => 'Job Addresses - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Job Address', 'href' => route('job-addresses')],
                ['label' => 'Properties', 'href' => 'javascript:void(0);'],
            ],
        ]);
    }

    public function list(Request $request){
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = CustomerProperty::with('customer','contact')->orderByRaw(implode(',', $sorts))->where('created_by', auth()->user()->id);
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

    public function customers(Request $request){
        return view('app.jobAddress.customers', [
            'title' => 'Customers - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customers', 'href' => route('job-addresses.customers')],
                ['label' => 'Properties', 'href' => 'javascript:void(0);'],
            ],
        ]);
    }

    public function customers_list(Request $request){
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
                $q->where('first_name','LIKE','%'.$queryStr.'%')->orWhere('last_name','LIKE','%'.$queryStr.'%')
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
                    'first_name' => $list->first_name,
                    'last_name' => $list->last_name,
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

    public function customers_create(Request $request){
        return view('app.jobAddress.customer-create', [
            'title' => 'Customers - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customers', 'href' => route('job-addresses.customers')],
                ['label' => 'Properties', 'href' => 'javascript:void(0);'],
            ],
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get()
        ]);
    }

    public function customers_store(CustomerCreateRequest $request){
        $data = [
            'title_id' => (!empty($request->title_id) ? $request->title_id : null),
            'first_name' => (!empty($request->first_name) ? $request->first_name : null),
            'last_name' => $request->first_name,
            'company_name' => (!empty($request->company_name) ? $request->company_name : null),
            'vat_no' => (!empty($request->vat_no) ? $request->vat_no : null),
            'address_line_1' => (!empty($request->address_line_1) ? $request->address_line_1 : null),
            'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
            'postal_code' => (!empty($request->postal_code) ? $request->postal_code : null),
            'state' => (!empty($request->state) ? $request->state : null),
            'city' => (!empty($request->city) ? $request->city : null),
            'country' => (!empty($request->country) ? $request->country : null),
            'latitude' => (!empty($request->latitude) ? $request->latitude : null),
            'longitude' => (!empty($request->longitude) ? $request->longitude : null),
            'note' => (!empty($request->note) ? $request->note : null),
            'auto_reminder' => (isset($request->auto_reminder) && $request->auto_reminder > 0 ? $request->auto_reminder : 0),
            'created_by' => auth()->user()->id
        ];
        $customer = Customer::create($data);
        if($customer->id):
            $CustomerProperty = CustomerProperty::create([
                'customer_id' => $customer->id,
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
                'other_email' => (!empty($request->other_email) ? $request->other_email : null),
                'created_by' => auth()->user()->id
            ]);
            return response()->json(['msg' => 'Customer successfully created.', 'red' => route('job-addresses.customers')], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }


    public function job_address_create(Request $request){
        $customer_id = $request->customer_id;
        $customer_contact_info = CustomerContactInformation::where('customer_id', $customer_id)->first();
        return view('app.jobAddress.create-job-address', [
                        'title' => 'Customers - Gas Certificate APP',
                        'breadcrumbs' => [
                            ['label' => 'Customers', 'href' => route('job-addresses.customers')],
                            ['label' => 'Properties', 'href' => 'javascript:void(0);'],
                        ],
                        'customer' => Customer::where('id', $customer_id)->first(),
                        'customer_contact_info' => $customer_contact_info,
                    ]);
    }

    public function job_address_store(Request $request){
        $customer_id = $request->customer_id;
        $customer = Customer::find($customer_id);
        $address_lookup = $request->address_lookup;
        $data = [
            'customer_id' => $request->customer_id,
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

            'created_by' => auth()->user()->id,
        ];
        $address = CustomerProperty::create($data);
        if($address->id):
            return response()->json(['msg' => 'Customer Job Addresses successfully created.', 'red' => '', 'address' => $address_lookup, 'id' => $address->id], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function job_address_edit(Request $request){
        $property_id = $request->property_id;
        $property = CustomerProperty::find($property_id);
        $customer = Customer::where('id', $property->customer_id)->first();
        $customer_contact_info = CustomerContactInformation::where('customer_id', $customer->id)->first();
        return view('app.jobAddress.edit-job-address', [
                        'title' => 'Customers - Gas Certificate APP',
                        'breadcrumbs' => [
                            ['label' => 'Customers', 'href' => route('job-addresses.customers')],
                            ['label' => 'Properties', 'href' => 'javascript:void(0);'],
                        ],
                        'customer' => $customer,
                        'customer_contact_info' => $customer_contact_info,
                        'property' => $property,
                    ]);
    }

    public function job_address_update(Request $request){
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
        $property_id = $request->property_id;
        $property = CustomerProperty::find($property_id);
        $property->delete();
        return response()->json(['msg' => 'Job Addresses successfully deleted.','red' => ''], 200);
    }

    public function job_address_restore(Request $request){
        $property_id = $request->property_id;
        $property = CustomerProperty::onlyTrashed()->find($property_id);
        $property->restore();
        return response()->json(['msg' => 'Job Addresses successfully restored.','red' => ''], 200);
    }
}
