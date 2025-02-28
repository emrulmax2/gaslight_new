<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\Title;
use App\Http\Requests\CustomerCreateRequest;
use App\Models\CustomerJobPriority;
use App\Models\CustomerJobStatus;
use App\Models\CustomerProperty;
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
            return response()->json(['msg' => 'Customer successfully created.', 'red' => route('customers')], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function edit(Customer $customer){
        $customer->load(['title', 'contact']);
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
            'title_id' => (!empty($request->title_id) ? $request->title_id : null),
            'first_name' => (!empty($request->first_name) ? $request->first_name : null),
            'last_name' => $request->last_name,
            'company_name' => (!empty($request->company_name) ? $request->company_name : null),
            'vat_no' => (!empty($request->vat_no) ? $request->vat_no : null),
            'address_line_1' => (!empty($request->address_line_1) ? $request->address_line_1 : null),
            'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
            'postal_code' => (!empty($request->postal_code) ? $request->postal_code : null),
            'state' => (!empty($request->state) ? $request->state : null),
            'city' => (!empty($request->city) ? $request->city : null),
            'country' => (!empty($request->country) ? $request->country : null),
            'note' => (!empty($request->note) ? $request->note : null),
            'auto_reminder' => (isset($request->auto_reminder) && $request->auto_reminder > 0 ? $request->auto_reminder : 0),
            'updated_by' => auth()->user()->id
        ];
        $customer = Customer::where('id', $customer_id)->update($data);

        $contact = CustomerContactInformation::where('customer_id', $customer_id)->update([
            'mobile' => (!empty($request->mobile) ? $request->mobile : null),
            'phone' => (!empty($request->phone) ? $request->phone : null),
            'email' => (!empty($request->email) ? $request->email : null),
            'other_email' => (!empty($request->other_email) ? $request->other_email : null),
            'updated_by' => auth()->user()->id
        ]);
        return response()->json(['msg' => 'Customer successfully updated.', 'red' => route('customers.jobs', $customer_id)], 200);
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
        $query = Customer::with('title', 'contact')->where(function($q) use($queryStr){
                    $q->where('first_name','LIKE','%'.$queryStr.'%')->orWhere('last_name','LIKE','%'.$queryStr.'%')
                        ->orWhere('company_name','LIKE','%'.$queryStr.'%')->orWhere('vat_no','LIKE','%'.$queryStr.'%')
                        ->orWhere('address_line_1','LIKE','%'.$queryStr.'%')->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                        ->orWhere('postal_code','LIKE','%'.$queryStr.'%')->orWhere('city','LIKE','%'.$queryStr.'%');
                })->orderBy('last_name')->get();
        if($query->count() > 0):
            $html .= '<div class="py-2 px-5 text-xs font-semibold bg-slate-100 rounded-md rounded-bl-none rounded-br-none">'.($query->count() == 1 ? $query->count().' result' : $query->count().' results').' found</div>';
                $html .= '<div class="results px-5 py-4" style="max-height: 250px; overflow-y: auto;">';
                    $i = 1;
                    foreach($query as $customer):
                        $html .= '<div data-id="'.$customer->id.'" data-title="'.$customer->full_name.'" class="searchResultItems flex items-center cursor-pointer '.($i != $query->count() ? ' pb-3 border-b border-slate-100 mb-3' : '').'">';
                            $html .= '<div>';
                                $html .= '<div class="group flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user h-4 w-4 stroke-[1.3] text-primary"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                                    //$html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin h-4 w-4 stroke-[1.3] text-primary"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                                $html .= '<div>';
                                    $html .= '<div class="whitespace-nowrap font-medium">';
                                        $html .= $customer->full_name;
                                    $html .= '</div>';
                                    $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                        $html .= $customer->address_line_1.' '.$customer->address_line_2.' '.$customer->city.', '.$customer->postal_code;
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
}
