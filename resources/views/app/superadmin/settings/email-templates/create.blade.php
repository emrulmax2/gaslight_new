@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>User Settings</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $subtitle }}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('superadmin.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: Settings Page Content -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
            <!-- BEGIN: Profile Info -->
            @include('app.superadmin.settings.sidebar')
            <!-- END: Profile Info -->
        </div>

        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
            <form method="post" action="#" id="reminderTemplateForm" enctype="multipart/form-data">
                <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                <div class="mt-5 grid grid-cols-12 gap-5">
                    <div class="col-span-12 sm:col-span-9">
                        <div class="intro-y box">
                            <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                                <h2 class="mr-auto text-base font-medium">
                                    {{ $form->name }} Email<br/>
                                    <span class="text-slate-500 text-xs font-normal">This is the base template which is merged with engineer's template during registration.</span>
                                </h2>
                            </div>
                            <div class="p-5">
                                <div class="mb-5">
                                    <x-base.form-label for="subject">Subject</x-base.form-label>
                                    <x-base.form-input value="{{ (isset($template->subject) ? $template->subject : '') }}" name="subject" class="w-full" type="text" placeholder="{{ $form->name }} for job at [jobaddr1] [jobaddr2]" />
                                    <div class="acc__input-error error-subject text-danger text-xs mt-1"></div>
                                </div>
                                <div class="flex justify-between mb-3">
                                    <label for="editEditor" class="form-label">Description <span class="text-danger">*</span></label>
                                    @include('app.settings.reminders.tags')
                                </div>
                                <div>
                                    <div class="editor" id="theEditor">{!! (isset($template->content) ? $template->content : '') !!}</div>
                                    <div class="acc__input-error error-content text-danger text-xs mt-1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-y mt-5 box p-5 text-center">
                            <x-base.button type="submit" id="saveEmailTemplatesBtn" class="w-auto text-white" variant="success" >
                                <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                Save
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                            <x-base.button as="a" href="{{ route('superadmin.site.setting.email.template') }}" class="w-auto" variant="danger" >
                                <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                                Cancel
                            </x-base.button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END: Settings Page Content -->

    @include('app.superadmin.settings.cancel-reason.index-modals')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/ckeditor.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/ckeditor/classic.js')
    @vite('resources/js/vendors/toastify.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/superadmin/settings/email-templates.js')
@endPushOnce