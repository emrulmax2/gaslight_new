<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAddressStoreRequest;
use App\Http\Requests\OccupantStoreRequest;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerProperty;
use App\Models\CustomerPropertyOccupant;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CustomerJobAddressController extends Controller
{

    public function list(Request $request)
    {
        $query = CustomerProperty::with('customer', 'contact');
        
        $status = $request->has('status') && $request->query('status') != '' ? $request->query('status') : 1;
        $customer_id = (isset($request->customer_id) && !empty($request->customer_id) ? $request->customer_id : 0);
        $searchKey = $request->has('search') && !empty($request->query('search')) ? $request->query('search') : '';
        $sortField = $request->has('sort') && !empty($request->query('sort')) ? $request->query('sort') : 'is_primary';
        $sortOrder = $request->has('order') && !empty($request->query('order')) ? $request->query('order') : 'desc';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
        $query->where('customer_id', $customer_id);
        
        if ($status == 2) {
            $query->onlyTrashed();
        }

        $searchableColumns = Schema::getColumnListing((new CustomerProperty)->getTable());
        if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        
        $query->orderBy($sortField, $sortOrder);
        
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $properties = $query->paginate($limit, ['*'], 'page', $page);
        
        
        return response()->json([
            'data' => $properties->items(),
            'meta' => [
                'total' => $properties->total(),
                'per_page' => $properties->perPage(),
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'from' => $properties->firstItem(),
                'to' => $properties->lastItem(),
            ]
        ]);
    }

    public function job_address_store(JobAddressStoreRequest $request){
        try {
            $is_primary = $request->has('is_primary') && $request->is_primary > 0 ? $request->is_primary : 0;
            if($is_primary == 1):
                CustomerProperty::where('customer_id', $request->customer_id)->update([
                    'is_primary' => 0,
                    'updated_by' => $request->user()->id
                ]);
            endif;

            
            $has_occupants = (isset($request->has_occupants) && !empty($request->has_occupants) ? $request->has_occupants : 0);
            $address = CustomerProperty::create([
                'customer_id' => $request->customer_id,
                'is_primary' => $is_primary,
                'address_line_1' => (!empty($request->address_line_1) ? $request->address_line_1 : null),
                'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
                'postal_code' => $request->postal_code,
                'state' => (!empty($request->state) ? $request->state : null),
                'city' => $request->city,
                'country' => (!empty($request->country) ? $request->country : null),
                'latitude' => (!empty($request->latitude) ? $request->latitude : null),
                'longitude' => (!empty($request->longitude) ? $request->longitude : null),
                'note' => (!empty($request->note) ? $request->note : null),
                'has_occupants' => $has_occupants,
                'created_by' => $request->user()->id,
            ]);
            if($address->id && $has_occupants):
                $occupant = CustomerPropertyOccupant::create([
                    'customer_property_id' => $address->id,
                    'occupant_name' => (!empty($request->occupant->occupant_name) ? $request->occupant->occupant_name : null),
                    'occupant_email' => (!empty($request->occupant->occupant_email) ? $request->occupant->occupant_email : null),
                    'occupant_phone' => (!empty($request->occupant->occupant_phone) ? $request->occupant->occupant_phone : null),
                    'due_date' => (!empty($request->occupant->due_date) ? date('Y-m-d', strtotime($request->occupant->due_date)) : null),
                    'active' => 1,

                    'created_by' => $request->user()->id,
                ]);
            endif;

            return response()->json([
                'success' => true,
                'message' => 'Customer property successfully created.',
                'data' => $address
            ], 200);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
        
    }

    public function job_address_update(JobAddressStoreRequest $request, $address_id){

        try {
            $property = CustomerProperty::with(['customer.title', 'customer.contact'])->findOrFail($address_id);
            $property->makeHidden(['full_address_html', 'full_address_with_html']);
            $property->customer->makeHidden(['full_address_html','full_address_with_html']);

            $has_occupants = $request->has('has_occupants') && $request->has_occupants == 1 ? 1 : 0;
            $updateData = [];

            $updateData['has_occupants'] = $has_occupants;
            if($request->has('address_line_1')):
                $updateData['address_line_1'] = (isset($request->address_line_1) && !empty($request->address_line_1) ? $request->address_line_1 : null);
            endif;

            if($request->has('address_line_2')):
                $updateData['address_line_2'] = (isset($request->address_line_2) && !empty($request->address_line_2) ? $request->address_line_2 : null);
            endif;

            if($request->has('city')):
                $updateData['city'] = (isset($request->city) && !empty($request->city) ? $request->city : null);
            endif;

            if($request->has('state')):
                $updateData['state'] = (isset($request->state) && !empty($request->state) ? $request->state : null);
            endif;

            if($request->has('country')):
                $updateData['country'] = (isset($request->country) && !empty($request->country) ? $request->country : null);
            endif;

            if($request->has('postal_code')):
                $updateData['postal_code'] = (isset($request->postal_code) && !empty($request->postal_code) ? $request->postal_code : null);
            endif;

            if($request->has('latitude')):
                $updateData['latitude'] = (isset($request->latitude) && !empty($request->latitude) ? $request->latitude : null);
            endif;
            
            if($request->has('longitude')):
                $updateData['longitude'] = (isset($request->longitude) && !empty($request->longitude) ? $request->longitude : null);
            endif;

            if($request->has('note')):
                $updateData['note'] = (isset($request->note) && !empty($request->note) ? $request->note : null);
            endif;

            $updateData['updated_by'] = $request->user()->id;

            $property->update($updateData);

            if($has_occupants == 1):
                CustomerPropertyOccupant::onlyTrashed()->where('customer_property_id', $address_id)->restore();
            else:
                CustomerPropertyOccupant::where('customer_property_id', $address_id)->delete();
            endif;

            return response()->json([
                'success' => true,
                'message' => 'Customer property successfully updated.',
                'data' => $property
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => "Customer job address not found. . The requested Customer job address (ID: '.$address_id.') does not exist or may have been deleted."
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
    }

    public function single_job_address(Request $request, $address_id){
        try {
            $property = CustomerProperty::with(['customer.title', 'customer.contact', 'occupants'])->findOrFail($address_id);
    
            $property->makeHidden([
                'full_address_html',
                'full_address_with_html'
            ]);
    
            $property->customer->makeHidden([
                'full_address_html',
                'full_address_with_html'
            ]);
    
            return response()->json([
                'success' => true,
                'data' => $property
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "Customer job address not found. . The requested Customer job address (ID: '.$address_id.') does not exist or may have been deleted.",
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        };
        
    }

    public function job_address_destroy(Request $request){
       try {
            $occupant = CustomerPropertyOccupant::where('customer_property_id', $request->address_id)->delete();
            $property = CustomerProperty::findOrFail($request->address_id);
            $property->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer property successfully deleted.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested customer property does not exist'
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
    }

    public function job_address_restore(Request $request){
        try {
            $occupant = CustomerPropertyOccupant::onlyTrashed()->where('customer_property_id', $request->address_id)->restore();
            $property = CustomerProperty::onlyTrashed()->findOrFail($request->address_id);
            $property->restore();

            return response()->json([
                'success' => true,
                'message' => 'Customer property successfully restored.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested customer property does not exist or already restored'
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
        
    }

    public function occupantStore($property_id, OccupantStoreRequest $request){
        try {
            $occupant = CustomerPropertyOccupant::create([
                'customer_property_id' => $property_id,
                'occupant_name' => (!empty($request->occupant_name) ? $request->occupant_name : null),
                'occupant_email' => (!empty($request->occupant_email) ? $request->occupant_email : null),
                'occupant_phone' => (!empty($request->occupant_phone) ? $request->occupant_phone : null),
                'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
                'active' => 1,

                'created_by' => $request->user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Property occupant successfully created.',
                'data' => $occupant
            ], 200);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
    }

    public function occupantsList($property_id, Request $request){
        $status = $request->has('status') && $request->query('status') != '' ? $request->query('status') : 1;
        $searchKey = $request->has('search') && !empty($request->query('search')) ? $request->query('search') : '';
        $sortField = $request->has('sort') && !empty($request->query('sort')) ? $request->query('sort') : 'id';
        $sortOrder = $request->has('order') && !empty($request->query('order')) ? $request->query('order') : 'desc';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';

        $query = CustomerPropertyOccupant::where('active', $status)->where('customer_property_id', $property_id);
        

        $searchableColumns = Schema::getColumnListing((new CustomerProperty)->getTable());
        if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        $Query = $query->get();
        
        
        return response()->json([
            'data' => $Query,
            'meta' => [
                'total' => $Query->count(),
            ]
        ]);
    }

    public function occupantEidt($occupant_id, Request $request){
        try {
            $occupant = CustomerPropertyOccupant::with(['property'])->findOrFail($occupant_id);
    
            return response()->json([
                'success' => true,
                'data' => $occupant
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "The requested Occupant (ID: '.$occupant_id.') does not exist or may have been deleted.",
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        };
    }

    public function occupantUpdate(OccupantStoreRequest $request, $occupant_id){
        try {
            $occupant = CustomerPropertyOccupant::with(['property'])->findOrFail($occupant_id);
            $updateData = [];

            if($request->has('occupant_name')):
                $updateData['occupant_name'] = (isset($request->occupant_name) && !empty($request->occupant_name) ? $request->occupant_name : null);
            endif;
            if($request->has('occupant_email')):
                $updateData['occupant_email'] = (isset($request->occupant_email) && !empty($request->occupant_email) ? $request->occupant_email : null);
            endif;
            if($request->has('occupant_phone')):
                $updateData['occupant_phone'] = (isset($request->occupant_phone) && !empty($request->occupant_phone) ? $request->occupant_phone : null);
            endif;
            if($request->has('due_date')):
                $updateData['due_date'] = (isset($request->due_date) && !empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null);
            endif;
            $updateData['updated_by'] = $request->user()->id;
            $occupant->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Occupant details successfully updated.',
                'data' => $occupant
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => "The requested Occupant (ID: '.$occupant_id.') does not exist or may have been deleted."
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
    }
}
