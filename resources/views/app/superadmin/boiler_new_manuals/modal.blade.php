<!-- BEGIN: Modal Content -->
<x-base.dialog id="addnew-modal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="createForm" enctype="multipart/form-data">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center"><x-base.lucide class=" h-4 w-4 text-primary mr-3" icon="thermometer-sun" />Create Boiler Manual</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-2">
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="gc_no" class="mb-1 tracking-normal">GC No</x-base.form-label>
                    <x-base.form-input name="gc_no" id="gc_no" class="w-full" type="text"/>
                    <div class="acc__input-error error-gc_no text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="model" class="mb-1 tracking-normal">Model</x-base.form-label>
                    <x-base.form-input name="model" id="model" class="w-full" type="text"/>
                    <div class="acc__input-error error-model text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="fuel_type" class="mb-1 tracking-normal">Flue Type</x-base.form-label>
                    <x-base.form-input name="fuel_type" id="fuel_type" class="w-full" type="text"/>
                    <div class="acc__input-error error-fuel_type text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="year_of_manufacture" class="mb-1 tracking-normal">Year of Manufacture </x-base.form-label>
                    <x-base.form-input name="year_of_manufacture" id="year_of_manufacture" class="w-full" type="text"/>
                    <div class="acc__input-error error-year_of_manufacture text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="url" class="mb-1 tracking-normal">URL</x-base.form-label>
                    <x-base.form-input name="url" id="url" class="w-full" type="text"/>
                    <div class="acc__input-error error-url text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6 relative">
                    <x-base.button as="label" for="addManualDocument" class="w-auto text-white sm:mt-[23px]" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="cloud-upload" />Upload Document
                    </x-base.button>
                    <x-base.form-input name="document" id="addManualDocument" accept=".pdf" class="w-0 h-0 absolute left-0 top-0 opacity-0" type="file"/>
                    <span id="addManualName" class="manualName block mt-2"></span>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="userSaveBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="boiler_brand_id" id="boiler_brand_id" value="{{ $boilerBrand->id }}" />
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->
 

<!-- BEGIN: Modal Content -->
<x-base.dialog id="edit-modal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="updateForm" enctype="multipart/form-data">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center"><x-base.lucide class=" h-4 w-4 text-primary mr-3" icon="thermometer-sun" />Edit Boiler Manual</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-2">
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="edit_gc_no" class="mb-1 tracking-normal">GC No</x-base.form-label>
                    <x-base.form-input name="gc_no" id="edit_gc_no" class="w-full" type="text"/>
                    <div class="acc__input-error error-gc_no text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="edit_model" class="mb-1 tracking-normal">Model</x-base.form-label>
                    <x-base.form-input name="model" id="edit_model" class="w-full" type="text"/>
                    <div class="acc__input-error error-model text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="edit_fuel_type" class="mb-1 tracking-normal">Flue Type</x-base.form-label>
                    <x-base.form-input name="fuel_type" id="edit_fuel_type" class="w-full" type="text"/>
                    <div class="acc__input-error error-fuel_type text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="edit_year_of_manufacture" class="mb-1 tracking-normal">Year of Manufacture </x-base.form-label>
                    <x-base.form-input name="year_of_manufacture" id="edit_year_of_manufacture" class="w-full" type="text"/>
                    <div class="acc__input-error error-year_of_manufacture text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="edit_url" class="mb-1 tracking-normal">URL</x-base.form-label>
                    <x-base.form-input name="url" id="edit_url" class="w-full" type="text"/>
                    <div class="acc__input-error error-url text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6 relative">
                    <x-base.button as="label" for="editManualDocument" class="w-auto text-white sm:mt-[23px]" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="cloud-upload" />Upload Document
                    </x-base.button>
                    <x-base.form-input name="document" id="editManualDocument" accept=".pdf" class="w-0 h-0 absolute left-0 top-0 opacity-0" type="file"/>
                    <span id="editManualName" class="manualName block mt-2"></span>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="UpdateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="boiler_brand_id" id="boiler_brand_id" value="{{ $boilerBrand->id }}" />
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="_method" value="PUT" />
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->




<x-base.dialog id="upload-excel-modal"
size="xl"
staticBackdrop
>
    <x-base.dialog.panel>
        <a
            class="absolute right-0 top-0 mr-3 mt-3"
            data-tw-dismiss="modal"
            href="#"
        >
            <x-base.lucide
                class="h-8 w-8 text-slate-400"
                icon="X"
            />
        </a>
        <div class="px-16 pt-16 pb-0 flex items-left">
            <x-base.lucide
                class=" h-6 w-6 text-primary"
                icon="file-spreadsheet"
            />
            <div id="titleModal" class="text-xl ml-2">Upload Boiler Manuals</div>
        </div>
        <div id="base-start" class=" flex flex-col px-16 pt-5 pb-16">
            <div>
                <form id="myDropzone" data-file-types="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|application/vnd.ms-excel" action="/file-upload" class="dropzone [&.dropzone]:border-2 [&.dropzone]:border-dashed dropzone [&.dropzone]:border-slate-300/70 [&.dropzone]:bg-slate-50 [&.dropzone]:cursor-pointer [&.dropzone]:dark:bg-darkmode-600 [&.dropzone]:dark:border-white/5 dz-clickable" >
                    @csrf
                    <div class="fallback">
                        <input
                            name="file"
                            type="file"
                        />
                    </div>
                    <div class="dz-message">
                        <div class="text-lg font-medium">
                            Drop files here or click to upload.
                        </div>
                        <div class="text-gray-600">
                            This is signature file upload. Selected files should
                            not over <span class="font-medium">2MB</span> and should be image file.
                        </div>
                    </div>
                    <input type="hidden" name="pid" value="{{ auth('superadmin')->user()->id }}" />
                </form>
                <div class="mb-4">
                    <form id="uploadExcelForm" enctype="multipart/form-data" class="mb-4">
                        <input type="hidden" name="file_id">
                        <input type="hidden" name="boiler_brand_id" id="boiler_brand_id" value="{{ $boilerBrand->id }}" />
                        <div class="mt-5 text-right">
                            <x-base.button class="w-36 border-0 rounded-0" id="userSaveBtn" type="submit" variant="primary">
                                <x-base.lucide
                                    class="h-6 w-6 mr-2"
                                    icon="circle-check-big"
                                /> Save
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        </div>
                    </form>
                </div>
                            
            </div>
        </div>

    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->