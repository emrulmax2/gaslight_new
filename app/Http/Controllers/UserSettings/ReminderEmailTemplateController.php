<?php

namespace App\Http\Controllers\UserSettings;

use App\Http\Controllers\Controller;
use App\Models\JobForm;
use Illuminate\Http\Request;

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
            'form' => $form
        ]);
    }
}
