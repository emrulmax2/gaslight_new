@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center justify-between">
        <h2 class="text-lg font-medium">{{ $form->name }} Email</h2>
        <div class="flex">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin" >
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>
    <form method="post" action="#" id="reminderTemplateForm" enctype="multipart/form-data">
        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
        <div class="mt-5 grid grid-cols-12 gap-5">
            <div class="col-span-12 sm:col-span-9">
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">
                            {{ $form->name }} Email<br/>
                            <span class="text-slate-500 text-xs font-normal">This is the template which is used each time an invoice is sent to the customer</span>
                        </h2>
                    </div>
                    <div class="p-5">
                        <x-base.form-label for="cc_email_address">Send a copy of all emails</x-base.form-label>
                        <x-base.form-input value="{{ (isset($template->cc_email_address) ? $template->cc_email_address : '') }}" name="cc_email_address" class="w-full" type="text" placeholder="example@exm.com,example@exm.com...." />
                    </div>
                </div>
                <div class="intro-y box mt-5 p-5">
                    <div class="mb-5">
                        <x-base.form-label for="subject">Subject</x-base.form-label>
                        <x-base.form-input value="{{ (isset($template->subject) ? $template->subject : '') }}" name="subject" class="w-full" type="text" placeholder="{{ $form->name }} for job at [jobaddr1] [jobaddr2]" />
                        <div class="acc__input-error error-subject text-danger text-xs mt-1"></div>
                    </div>
                    <div class="flex justify-between">
                        <label for="editEditor" class="form-label">Description <span class="text-danger">*</span></label>
                        @include('app.settings.reminders.tags')
                    </div>
                    <div class="mb-5">
                        <div class="editor" id="theEditor">{!! (isset($template->content) ? $template->content : '') !!}</div>
                        <div class="acc__input-error error-content text-danger text-xs mt-1"></div>
                    </div>
                    <div class="flex items-start">
                        <div>
                            <x-base.form-label for="attachment" class="mb-0 attachmentCount">0 Attachments</x-base.form-label>
                            <p class="text-xs text-slate-500 mb-3">Max single file size <span class="font-medium text-primary">5MB</span>. Max total file size <span class="font-medium text-primary">20MB</span></p>
                            <label class="border-dashed relative bg-slate-50 flex justify-start items-center p-3 rounded border-2 w-60 cursor-pointer">
                                <input accept="image/*, .xl, .xls, .xlsx, .doc, .docx, .pdf, .txt" type="file" id="attachments" name="attachments[]" multiple class="absolute left-0 top-0 w-0 h-0 opacity-0"/>
                                <div class="h-10 w-10 flex-none overflow-hidden rounded items-center justify-center bg-success border inline-flex mr-5">
                                    <x-base.lucide class="h-4 w-4 text-white" icon="upload-cloud" />
                                </div>
                                <span class="font-medium text-dark">Add Attachments</span>
                            </label>
                            <div class="acc__input-error error-attachments_error text-danger text-xs mt-1"></div>
                        </div>
                        <div class="ml-auto inline-flex justify-end items-start">
                            @if(isset($template->attachment) && $template->attachment->count() > 0)
                                <x-base.menu class="w-1/2 sm:w-auto">
                                    <x-base.menu.button type="button" class="w-full sm:w-auto" as="x-base.button" variant="outline-secondary" >
                                        <x-base.lucide class="mr-2 h-4 w-4" icon="download-cloud" /> 
                                        Attachments
                                    </x-base.menu.button>
                                    <x-base.menu.items as="ul" class="w-64">
                                        @foreach($template->attachment as $attachment)
                                        <x-base.menu.item as="li" class="flex items-center">
                                            <span class="inline-flex justify-start items-start"><x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" /> {{ $attachment->display_file_name }}</span>
                                            <span class="inline-flex justify-end ml-auto">
                                                <x-base.button as="a" target="_blank" href="{{ ($attachment->download_url ? $attachment->download_url : 'javascript:void(0);') }}" class="rounded-full w-[25px] h-[25px] p-0 items-center justify-center" variant="linkedin" >
                                                    <x-base.lucide class="h-3 w-3" icon="download-cloud"/>
                                                </x-base.button>
                                                <x-base.button data-id="{{ $attachment->id }}" type="button" class="delete_attachment rounded-full ml-1 w-[25px] h-[25px] p-0 items-center justify-center" variant="danger" >
                                                    <x-base.lucide class="h-3 w-3" icon="trash-2" />
                                                </x-base.button>
                                            </span>
                                        </x-base.menu.item>
                                        @endforeach
                                    </x-base.menu.items>
                            </x-base.menu>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3">
                <div class="intro-y box p-5">
                    <x-base.button type="submit" id="saveEmailTemplatesBtn" class="w-full mb-3 text-white" variant="success" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Save
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button as="a" href="{{ route('user.settings') }}" class="w-full" variant="danger" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                        Cancel
                    </x-base.button>
                </div>
            </div>
        </div>
    </form>

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
    @vite('resources/js/app/user-settings/reminder-create.js')
@endPushOnce