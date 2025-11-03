<?php

namespace App\Http\Controllers\UserSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReminderTemplateStoreRequest;
use App\Models\JobForm;
use App\Models\JobFormBaseEmailTemplate;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormEmailTemplateAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReminderEmailTemplateController extends Controller
{
    public function index(){
        return view('app.settings.reminders.index', [
            'title' => 'Settings - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'settings', 'href' => route('user.settings')],
                ['label' => 'Reminder & Email Templates', 'href' => 'javascript:void(0);'],
            ],
            'forms' => JobForm::where('parent_id', '>', 0)->where('active', 1)->orderBy('id', 'ASC')->get()
        ]);
    }

    public function create(JobForm $form){
        return view('app.settings.reminders.create', [
            'title' => 'Settings - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'settings', 'href' => route('user.settings')],
                ['label' => 'Reminder & Email Templates', 'href' => route('user.settings.reminder.templates')],
                ['label' => 'Create', 'href' => 'javascript:void(0);'],
            ],
            'form' => $form,
            'template' => JobFormEmailTemplate::with('attachment')->where('user_id', auth()->user()->id)->where('job_form_id', $form->id)->get()->first()
        ]);
    }

    public function store(ReminderTemplateStoreRequest $request){
        $user_id = auth()->user()->id;
        $job_form_id = $request->job_form_id;

        $template = JobFormEmailTemplate::updateOrCreate([ 'user_id' => $user_id, 'job_form_id' => $job_form_id ], [
            'user_id' => $user_id,
            'job_form_id' => $job_form_id,
            'subject' => $request->subject,
            'content' => $request->content,
            'cc_email_address' => $request->cc_email_address,
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        if($template->id):
            if($request->hasFile('attachments')):
                $documents = $request->file('attachments');
                foreach($documents as $document):
                    $documentName = time().'_'.$template->id.'.'.$document->getClientOriginalExtension();
                    $path = $document->storeAs('template_attachments/'.$template->id, $documentName, 'public');

                    $data = [];
                    $data['job_form_email_template_id'] = $template->id;
                    $data['display_file_name'] = $documentName;
                    $data['current_file_name'] = $documentName;
                    $data['doc_type'] = $document->getClientMimeType();
                    $data['disk_type'] = 'local';
                    $data['path'] = Storage::disk('public')->url($path);
                    $data['created_by'] = $user_id;
                    JobFormEmailTemplateAttachment::create($data);
                endforeach;
            endif;
            return response()->json(['msg' => 'Template successfully updated.', 'red' => route('user.settings.reminder.templates.create', $job_form_id)], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function destroyAttachment($attachment_id){
        $attachment = JobFormEmailTemplateAttachment::find($attachment_id);
        $job_form_id = $attachment->template->job_form_id;
        $attachment->delete();

        return response()->json(['msg' => 'Attachment successfully deleted.', 'red' => route('user.settings.reminder.templates.create', $job_form_id)], 200);
    }

    public function reloadBaseData(Request $request){
        $form_id = $request->form_id;
        $user_id = auth()->user()->id;

        $baseTemplate = JobFormBaseEmailTemplate::where('job_form_id', $form_id)->get()->first();
        if(isset($baseTemplate->id) && $baseTemplate->id > 0):
            return response()->json(['row' => $baseTemplate, 'red' => ''], 200);
        else:
            return response()->json(['msg' => 'Base template not found!', 'red' => ''], 304);
        endif;
    }
}
