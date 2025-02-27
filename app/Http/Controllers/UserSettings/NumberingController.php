<?php

namespace App\Http\Controllers\UserSettings;

use App\Http\Controllers\Controller;
use App\Models\JobForm;
use App\Models\JobFormPrefixMumbering;
use Illuminate\Http\Request;

class NumberingController extends Controller
{
    public function index(){
        return view('app.settings.numbering.index', [
            'title' => 'Settings - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'settings', 'href' => route('user.settings')],
                ['label' => 'Numbering', 'href' => 'javascript:void(0);'],
            ],
            'forms' => JobForm::where('parent_id', '>', 0)->where('active', 1)->orderBy('id', 'ASC')->get(),
            'numberings' => JobFormPrefixMumbering::where('user_id', auth()->user()->id)->get()->keyBy('job_form_id')
        ]);
    }

    public function store(Request $request){
        $numbering = (isset($request->numbering) && !empty($request->numbering) ? $request->numbering : []);
        $user_id = auth()->user()->id;
        if(!empty($numbering)):
            foreach($numbering as $form_id => $form):
                $prefix = (isset($form['prefix']) && !empty($form['prefix']) ? $form['prefix'] : null);
                $starting_from = (isset($form['starting_from']) && $form['starting_from'] > 0 ? $form['starting_from'] : 1);

                JobFormPrefixMumbering::updateOrCreate([ 'user_id' => $user_id, 'job_form_id' => $form_id ], [
                    'prefix' => $prefix,
                    'starting_from' => $starting_from,
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]);
            endforeach;

            return response()->json(['msg' => 'Certificates, Jobs, Quotes, and Invoices Numbering successfully updated.', 'red' => ''], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }
}
