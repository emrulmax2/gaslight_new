<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerProperty;
use App\Models\CustomerPropertyOccupant;
use Illuminate\Http\Request;

class LocalStorageController extends Controller
{
    public function getUsersJobAddress($user_id){
        $properties = CustomerProperty::whereHas('customer', function($q) use($user_id){
            $q->where('created_by', $user_id);
        })->get();

        if($properties->count() > 0):
            return response()->json([
                'success' => true,
                'data' => $properties
            ]);
        else:
            return response()->json([
                'success' => false,
                'data' => $properties,
                'message' => 'User does not have any property.'
            ]);
        endif;
    }


    public function getUsersOccupants($user_id){
        $property_ids = CustomerProperty::whereHas('customer', function($q) use($user_id){
            $q->where('created_by', $user_id);
        })->get()->pluck('id')->unique()->toArray();
        if(!empty($property_ids)):
            $occupants = CustomerPropertyOccupant::with('property')->whereIn('customer_property_id', $property_ids)->get();
        
            return response()->json([
                'success' => true,
                'data' => $occupants
            ]);
        else:
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'User does not have any property and occupant.'
            ]);
        endif;
    }
}
