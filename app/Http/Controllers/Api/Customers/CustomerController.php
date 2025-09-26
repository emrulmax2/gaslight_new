<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerContactInfoStoreRequest;
use App\Http\Requests\Api\CustomerContactInfoUpdateRequest;
use App\Http\Requests\Api\CustomerPropertyStoreRequest;
use App\Http\Requests\CustomerCreateRequest;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerProperty;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; 

class CustomerController extends Controller
{

    public function list(Request $request)
    {
        $query = Customer::where('created_by', $request->user()->id);

        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'full_name';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

        $searchableColumns = Schema::getColumnListing((new Customer)->getTable());
        if (!empty($searchKey)) {
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        }
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $titles = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $titles->items(),
            'meta' => [
                'total' => $titles->total(),
                'per_page' => $titles->perPage(),
                'current_page' => $titles->currentPage(),
                'last_page' => $titles->lastPage(),
                'from' => $titles->firstItem(),
                'to' => $titles->lastItem(),
            ]
        ]);
    }

    public function storeCustomer(CustomerCreateRequest $request){
        try {
        $data = [
            // 'title_id' => (!empty($request->title_id) ? $request->title_id : null),
            'full_name' => (isset($request->full_name) && !empty($request->full_name) ? $request->full_name : null),
            'company_name' => (!empty($request->company_name) ? $request->company_name : null),
            // 'vat_no' => (!empty($request->vat_no) ? $request->vat_no : null),
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
            'created_by' => $request->user()->id
        ];
        $customer = Customer::create($data);
        if($customer->id):
            $customerProperty = CustomerProperty::create([
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
    
                'created_by' => $request->user()->id,
            ]);
            
            $contact = CustomerContactInformation::create([
                'customer_id' => $customer->id,
                'mobile' => (!empty($request->mobile) ? $request->mobile : null),
                'phone' => (!empty($request->phone) ? $request->phone : null),
                'email' => (!empty($request->email) ? $request->email : null),
                // 'other_email' => (!empty($request->other_email) ? $request->other_email : null),
                'created_by' => $request->user()->id
            ]);

            return response()->json([
                'message' => 'Customer successfully created.',
                'data' => [
                    'customer' => $customer,
                    'customerProperty' => $customerProperty,
                    'contact' => $contact
                ]
            ], 200);
        else:
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 200);
        endif;

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        };
    }

    public function customerPropertyStore(CustomerPropertyStoreRequest $request)
    {
       try {
            $property = CustomerProperty::create([
                'customer_id' => $request->customer_id,
                'address_line_1' => (!empty($request->address_line_1) ? $request->address_line_1 : null),
                'address_line_2' => (!empty($request->address_line_2) ? $request->address_line_2 : null),
                'postal_code' => (!empty($request->postal_code) ? $request->postal_code : null),
                'state' => (!empty($request->state) ? $request->state : null),
                'city' => (!empty($request->city) ? $request->city : null),
                'country' => (!empty($request->country) ? $request->country : null),
                'note' => (!empty($request->note) ? $request->note : null),
                'latitude' => (!empty($request->latitude) ? $request->latitude : null),
                'longitude' => (!empty($request->longitude) ? $request->longitude : null),
                'created_by' => $request->user()->id,
            ]);
            $propertyData = [
                'id' => $property->id,
                'address_line_1' => $property->address_line_1,
                'address_line_2' => $property->address_line_2,
                'postal_code' => $property->postal_code,
                'state' => $property->state,
                'city' => $property->city,
                'country' => $property->country,
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
            ];
            return response()->json([
                'message' => 'Customer property successfully created.',
                'data' => $propertyData
            ], 200);
       }  catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        }; 
    }

    public function customerContactInfoStore(CustomerContactInfoStoreRequest $request)
    {
        try {
            $contact = CustomerContactInformation::create([
                'customer_id' => $request->customer_id,
                'mobile' => (!empty($request->mobile) ? $request->mobile : null),
                'phone' => (!empty($request->phone) ? $request->phone : null),
                'email' => (!empty($request->email) ? $request->email : null),
                'other_email' => (!empty($request->other_email) ? $request->other_email : null),
                'created_by' => $request->user()->id,
            ]);
            $customerContactData = [
                'id' => $contact->id,
                'mobile' => $contact->mobile,
                'phone' => $contact->phone,
                'email' => $contact->email,
                'other_email' => $contact->other_email,
            ];
            return response()->json([
                'message' => 'Customer contact successfully created.',
                'data' => $customerContactData
            ], 200);
        }  catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 304);
        };
    }

    public function updateCustomer(Request $request)
    {
        try {
            $customer = Customer::with(['contact'])
                ->withCount(['properties as number_of_job_address', 'jobs as number_of_jobs'])
                ->findOrFail($request->id);

            $customer->makeHidden([
                'full_address_html',
                'full_address_with_html'
            ]);

            $fields = ['full_name','company_name', 'address_line_1', 'address_line_2', 'postal_code', 'state', 'city',  'country', 'latitude', 'longitude', 'note','auto_reminder' ];

            $updateData = $request->only($fields);
            foreach ($updateData as $key => $value) {
                $updateData[$key] = !empty($value) ? $value : null;
            }

            $updateData['updated_by'] = $request->user()->id;
            $customer->update($updateData);

            return response()->json([
                'message' => 'Customer successfully updated.',
                'data' => $customer
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Customer not found. The requested Customer (ID: '.$request->id.') does not exist or may have been deleted.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact the administrator',
            ], 500);
        }
    }



    public function updateCustomerContact(CustomerContactInfoUpdateRequest $request)
    {
        try {
            $contact = CustomerContactInformation::findOrFail($request->id);
            $contact->update([
                'mobile' => $request->filled('mobile') ? $request->mobile : $contact->mobile,
                'phone' => $request->filled('phone') ? $request->phone : $contact->phone,
                'email' => $request->filled('email') ? $request->email : $contact->email,
                'other_email' => $request->filled('other_email') ? $request->other_email : $contact->other_email,
                'updated_by' => $request->user()->id
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Contact updated successfully',
                'data' => $contact,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }
    public function destroy($customer_id)
    {
        try {
            $customer = Customer::findOrFail($customer_id);
            $customer->delete();
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully',
                'data' => [
                    'id' => $customer->id,
                    'deleted_at' => now()->toISOString()
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }
    public function restore($customer_id)
    {
        try {
            $customer = Customer::withTrashed()->findOrFail($customer_id);
            
            if (!$customer->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer is not deleted',
                ], 400);
            }
            $customer->restore();
            return response()->json([
                'success' => true,
                'message' => 'Customer successfully restored',
                'data' =>  $customer
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function getDetails($id)
    {
        try {
            $customer = Customer::withCount(['properties as number_of_job_address', 'jobs as number_of_jobs'])
                        ->findOrFail($id);
            $customer->makeHidden([
                'full_address_html',
                'full_address_with_html'
            ]);
            return response()->json([
                'success' => true,
                'data'  => $customer,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested customer does not exist'
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

    public function getCustomerContactInfo($customer_id)
    {
        try {
            $contact_info = CustomerContactInformation::where('customer_id', $customer_id)->first();
            return response()->json([
                'success' => true,
                'data'  => $contact_info,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested customer does not exist'
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
    public function getCustomerProperty($customer_id)
    {
        try {
            $customerProperty = CustomerProperty::where('customer_id', $customer_id)->first();
            return response()->json([
                'success' => true,
                'data'  => $customerProperty,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested customer does not exist'
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
