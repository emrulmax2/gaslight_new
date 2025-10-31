<?php

namespace App\Http\Controllers\SuperAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReminderTemplateStoreRequest;
use App\Models\JobForm;
use App\Models\JobFormBaseEmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    public function index(){
        return view('app.superadmin.settings.email-templates.index',[
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'Email Templates',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'Email Templates', 'href' => 'javascript:void(0);'],
            ],
            'forms' => JobForm::where('parent_id', '>', 0)->where('active', 1)->orderBy('id', 'ASC')->get()
        ]);
    }

    public function create(JobForm $form){
        return view('app.superadmin.settings.email-templates.create', [
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'Email Templates',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'Email Templates', 'href' => 'javascript:void(0);'],
                ['label' => 'Create', 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'template' => JobFormBaseEmailTemplate::where('job_form_id', $form->id)->get()->first()
        ]);
    }

    public function store(ReminderTemplateStoreRequest $request){
        $user_id = Auth::guard('superadmin')->user()->id;
        $job_form_id = $request->job_form_id;

        $template = JobFormBaseEmailTemplate::updateOrCreate(['job_form_id' => $job_form_id ], [
            'job_form_id' => $job_form_id,
            'subject' => $request->subject,
            'content' => $request->content,
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        if($template->id):
            return response()->json(['msg' => 'Template successfully updated.', 'red' => route('superadmin.site.setting.email.template.create', $job_form_id)], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }
}
