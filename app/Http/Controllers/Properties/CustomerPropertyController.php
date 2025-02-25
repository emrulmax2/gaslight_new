<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerPropertyStoreRequerst;
use App\Models\Customer;
use App\Models\CustomerProperty;
use Illuminate\Http\Request;

class CustomerPropertyController extends Controller
{
   public function create(){
    return view('app.properties.create', [
        'title' => 'Properties - Gas Certificate APP',
        'breadcrumbs' => [
            ['label' => 'Properties', 'href' => 'javascript:void(0);'],
        ],
    ]);
   }

    public function getCustomer(Request $request) {
        $customers = Customer::where('first_name', 'LIKE', $request->name . '%')
                            ->get(['id', 'first_name']);
        
        return response()->json([
            'data' => $customers
        ]);
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
            return response()->json(['msg' => 'Customer Property Address successfully created.', 'red' => '', 'address' => $address_lookup, 'id' => $address->id], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
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

}

