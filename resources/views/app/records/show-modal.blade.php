
<!-- BEGIN: Send Email Content -->
<x-base.dialog id="sendEmailModal" size="xl" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="sendEmailForm" enctype="multipart/form-data">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Send Email</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" onclick="location.reload();" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="p-5">
                <div class="mb-3">
                    <x-base.form-label for="cc_email_address">Customer Email</x-base.form-label>
                    @if(!isset($record->customer->contact->email) || empty($record->customer->contact->email))
                        <x-base.form-input value="{{ (isset($record->customer->contact->email) && !empty($record->customer->contact->email) ? $record->customer->contact->email : '') }}" name="customer_email" class="w-full" type="text" placeholder="example@exm.com" />
                    @else
                        <x-base.form-input readonly value="{{ (isset($record->customer->contact->email) && !empty($record->customer->contact->email) ? $record->customer->contact->email : '') }}" name="customer_email" class="w-full" type="text" placeholder="example@exm.com" />
                    @endif
                    <div class="acc__input-error error-customer_email text-danger text-xs mt-1"></div>
                </div>
                <div class="mb-3">
                    <x-base.form-label for="cc_email_address">Send a copy to all emails</x-base.form-label>
                    <x-base.form-input value="{{ (isset($template->cc_email_address) ? $template->cc_email_address : '') }}" name="cc_email_address" class="w-full" type="text" placeholder="example@exm.com,example@exm.com...." />
                    <small class="leading-non mt-1">Use comma (,) seperator for multiple emails. ie: example@exm.com,example@exm.com....</small>
                </div>
                <div class="mb-3">
                    <x-base.form-label for="subject">Subject</x-base.form-label>
                    <x-base.form-input id="subject" value="{{ (isset($template->subject) ? $template->subject : '') }}" name="subject" class="w-full" type="text" placeholder="{{ $form->name }} for job at [jobaddr1] [jobaddr2]" />
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
                {{--<div class="flex items-start">
                    <div>
                        <x-base.form-label for="attachment" class="mb-0 attachmentCount" data-prevcount="{{ (isset($template->attachment) && $template->attachment->count() > 0 ? $template->attachment->count() : '0') }}">{{ (isset($template->attachment) && $template->attachment->count() > 0 ? $template->attachment->count() : '0') }} Attachments</x-base.form-label>
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
                                        </span>
                                    </x-base.menu.item>
                                    @endforeach
                                </x-base.menu.items>
                        </x-base.menu>
                        @endif
                    </div>
                </div>--}}
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" onclick="location.reload();" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="sendEmailBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Send
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
            <input type="hidden" name="record_id" value="{{ $record->id }}"/>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Send Email Modal Content -->