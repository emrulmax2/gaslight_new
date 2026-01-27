<?php

namespace App\Http\Controllers\Api\JobForms;

use App\Http\Controllers\Controller;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use Exception;
use Illuminate\Http\Request;

class JobFormController extends Controller
{
    public function list(Request $request){
        try{
            $jobForms = JobForm::with('childs')->where('parent_id', 0)->whereNot('id', 1)->where('active', 1)->orderBy('id', 'ASC')->get();
            return response()->json([
                'success' => true,
                'data' => $jobForms
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 422);
        }
    }

    public function formTemplate(Request $request){
        try{
            $template = JobFormEmailTemplate::with('attachment')->where('user_id', $request->user()->id)->where('job_form_id', $request->job_form_id)->get()->first();
            return response()->json([
                'success' => true,
                'template' => $template
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 422);
        }
    }
}
