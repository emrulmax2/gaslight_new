<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CustomerProperty;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerPropertyStoreRequerst;

class PropertyController extends Controller
{
    public function index(Customer $customer){
        $customer->load(['title', 'contact']);
        return view('app.customers.properties.index', [
            'title' => 'Customers Job Addresses - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customers', 'href' => route('customers')],
                ['label' => 'Properties', 'href' => 'javascript:void(0);'],
            ],
            'customer' => $customer
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

        $query = CustomerProperty::orderByRaw(implode(',', $sorts))->where('customer_id', $customer_id);
        if(!empty($queryStr)):
            $query->where(function($q) use($queryStr){
                $q->where('address_line_1','LIKE','%'.$queryStr.'%')->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                    ->orWhere('postal_code','LIKE','%'.$queryStr.'%')->orWhere('state','LIKE','%'.$queryStr.'%')
                    ->orWhere('city','LIKE','%'.$queryStr.'%')
                    ->orWhere('country','LIKE','%'.$queryStr.'%');
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
                    'customer_id' => $list->customer_id,
                    'address' => implode(', ', $addressParts),
                    'deleted_at' => $list->deleted_at
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data]);
    }

    public function getCustomer(Request $request) {
        $customers = Customer::where('first_name', 'LIKE', $request->name . '%')
                            ->get(['id', 'first_name']);
        
        return response()->json([
            'data' => $customers
        ]);
    }

    public function getCustomerAddre(Request $request)
    {
            $customer_id = $request->customer_id;
            $customer = Customer::find($customer_id);
            return response()->json(['row' => $customer], 200);
    }

    public function store(CustomerPropertyStoreRequerst $request){
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

    public function edit(Request $request)
    {
        $property_id = $request->property_id;
        if (!$property_id) {
            return response()->json(['error' => 'Property ID is required'], 400);
        }
        $property = CustomerProperty::find($property_id);
        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }
        return response()->json(['row' => $property], 200);
    }

    public function update(CustomerPropertyStoreRequerst $request){
        $customer_id = $request->customer_id;
        $property_id = $request->property_id;

        $customer = Customer::find($customer_id);

        $data = [
            'customer_id' => $request->customer_id,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => !empty($request->address_line_2) ? $request->address_line_2 : null,
            'postal_code' => $request->postal_code,
            'state' => !empty($request->state) ? $request->state : null,
            'city' => $request->city,
            'country' => !empty($request->country) ? $request->country : null,
            'latitude' => !empty($request->latitude) ? $request->latitude : null,
            'longitude' => !empty($request->longitude) ? $request->longitude : null,
            'note' => !empty($request->note) ? $request->note : null,
            'occupant_name' => !empty($request->occupant_name) ? $request->occupant_name : null,
            'occupant_email' => !empty($request->occupant_email) ? $request->occupant_email : null,
            'occupant_phone' => !empty($request->occupant_phone) ? $request->occupant_phone : null,
            'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
            'updated_by' => auth()->user()->id,
            'updated_at' => Carbon::now(), 
        ];

        $updatedRows = CustomerProperty::where('id', $property_id)->update($data);

        if ($updatedRows) {
            $updatedProperty = CustomerProperty::findOrFail($property_id);

            return response()->json([
                'msg' => 'Customer Job Addresses Updated successfully.',
                'red' => '',
                'id' => $updatedProperty->id  
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Something went wrong. Please try again later or contact the administrator.',
                'red' => ''
            ], 304);
        }
    }

    public function search(Request $request){
        $queryStr = (isset($request->the_search_query) && !empty($request->the_search_query) ? $request->the_search_query : '');
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $html = '';
        $query = CustomerProperty::with('customer')->where('customer_id', $customer_id)->where(function($q) use($queryStr){
                    $q->where('address_line_1','LIKE','%'.$queryStr.'%')->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                        ->orWhere('postal_code','LIKE','%'.$queryStr.'%')->orWhere('city','LIKE','%'.$queryStr.'%');
                })->orderBy('address_line_1', 'ASC')->get();
        if($query->count() > 0):
            $html .= '<div class="py-2 px-5 text-xs font-semibold bg-slate-100 rounded-md rounded-bl-none rounded-br-none">'.($query->count() == 1 ? $query->count().' result' : $query->count().' results').' found</div>';
                $html .= '<div class="results px-5 py-4" style="max-height: 250px; overflow-y: auto;">';
                    $i = 1;
                    foreach($query as $property):
                        $html .= '<div data-id="'.$property->id.'" data-title="'.$property->address_line_1.' '.$property->address_line_2.', '.$property->postal_code.'" class="searchResultItems flex items-center cursor-pointer '.($i != $query->count() ? ' pb-3 border-b border-slate-100 mb-3' : '').'">';
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
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            return response()->json(['suc' => 2, 'html' => ''], 200);
        endif;
    }

    public function destroy(Customer $customer, $property_id){
        $customer = CustomerProperty::find($property_id)->delete();

        return response()->json(['msg' => 'Customer Job Addresses data successfully deleted.', 'red' => ''], 200);
    }

    public function restore(Customer $customer, $property_id){
        $customer = CustomerProperty::where('id', $property_id)->withTrashed()->restore();

        return response()->json(['msg' => 'Customer Job Addresses data Successfully Restored!', 'red' => ''], 200);
    }

}

