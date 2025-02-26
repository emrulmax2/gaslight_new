
<!-- BEGIN: Modal Content -->
<x-base.dialog id="jobUploadDocModal" staticBackdrop size="lg">
    <x-base.dialog.panel>
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">Upload Documents</h2>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="modal-body">
            <form id="jobUploadDocForm" method="post" enctype="multipart/form-data" action="{{ route('customers.jobs.document.store', [$customer->id, $job->id]) }}" class="dropzone [&.dropzone]:border-2 [&.dropzone]:border-dashed [&.dropzone]:border-darkmode-200/60 [&.dropzone]:dark:bg-darkmode-600 [&.dropzone]:dark:border-white/5 dropzone dropzone">
                @csrf
                <div class="fallback">
                    <input name="documents[]" multiple type="file" />
                </div>
                <div class="dz-message" data-dz-message>
                    <div class="text-lg font-medium">
                        Drop files here or click to upload.
                    </div>
                    <div class="text-gray-600">
                        Maximum 5 files at a time and file size should under 20MB. Allowed extensions are
                        <span class="font-medium">.jpeg, .jpg, .png, .gif, .pdf, .xl, .xls, .xlsx, .doc, .docx, .ppt, .pptx, .txt</span>
                    </div>
                </div>
                <x-base.form-input name="customer_id" type="hidden" value="{{ $customer->id }}" />
                <x-base.form-input name="customer_job_id" type="hidden" value="{{ $job->id }}" />
            </form>
        </x-base.dialog.description>
        <x-base.dialog.footer>
            <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
            <x-base.button class="w-auto" id="uploadJobDocumentsBtn" type="button" variant="primary">
                <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                Upload
                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
            </x-base.button>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->