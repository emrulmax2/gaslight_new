<!-- BEGIN: Modal Content -->
<x-base.dialog id="addnew-modal"
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
        {{-- <div class="absolute left-0 top-0 ml-3 mt-3  bg-primary text-white text-sm font-semibold px-2 py-1 rounded">
            Step <span class="current-step"> 01 </span> of <span class="total-step">02</span>
        </div> --}}
        <div class="px-16 pt-16 pb-0 flex items-left">
            <x-base.lucide
                class=" h-6 w-6 text-primary"
                icon="thermometer-sun"
            />
            <div id="titleModal" class="text-xl ml-2">Create Boiler Manual</div>
        </div>
        <div id="base-start" class=" flex flex-col px-16 pt-5 pb-16">
            <div>
                <div class="mb-4">
                    <form id="createForm" enctype="multipart/form-data" class="mb-4">
                        <input type="hidden" name="boiler_brand_id" id="boiler_brand_id" value="{{ $boilerBrand->id }}" />
                        <div class="mt-3">
                            <label data-tw-merge for="gc_no"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                GC No 
                            </label>
                            <input data-tw-merge id="gc_no" type="text" name="gc_no" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-gc_no"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="model"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Model 
                            </label>
                            <input data-tw-merge id="model" type="text" name="model" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-url"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="url"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                URL 
                            </label>
                            <input data-tw-merge id="url" type="text" name="url" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-url"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="fuel_type"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Fuel Type 
                            </label>
                            <input data-tw-merge id="fuel_type" type="text" name="fuel_type" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-name"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="year_of_manufacture"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Year of Manufacture 
                            </label>
                            <input data-tw-merge id="year_of_manufacture" type="text" name="year_of_manufacture" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-year_of_manufacture"></div>
                        </div>
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


<!-- BEGIN: Modal Content -->
<x-base.dialog id="edit-modal"
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
                icon="file-pen-line"
            />
            <div id="titleModal" class="text-xl ml-2">Edit Boiler Manual</div>
        </div>
        <div id="base-start" class=" flex flex-col px-16 pt-5 pb-16">
            <div>
                <div class="mb-4">
                    <form id="updateForm" enctype="multipart/form-data" class="mb-4">
                        
                        <input type="hidden" name="boiler_brand_id" id="boiler_brand_id" value="{{ $boilerBrand->id }}" />
                        <div class="mt-3">
                            <label data-tw-merge for="gc_no"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                GC No 
                            </label>
                            <input data-tw-merge id="gc_no" type="text" name="gc_no" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-gc_no"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="model"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Model 
                            </label>
                            <input data-tw-merge id="model" type="text" name="model" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-url"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="url"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                URL 
                            </label>
                            <input data-tw-merge id="url" type="text" name="url" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-url"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="fuel_type"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Fuel Type 
                            </label>
                            <input data-tw-merge id="fuel_type" type="text" name="fuel_type" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-name"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="year_of_manufacture"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Year of Manufacture 
                            </label>
                            <input data-tw-merge id="year_of_manufacture" type="text" name="year_of_manufacture" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-year_of_manufacture"></div>
                        </div>
                        <div class="mt-5 text-right">
                            <input type="hidden" name="id" id="id" />
                            <input type="hidden" name="_method" value="PUT" />
                            <x-base.button class="w-36 border-0 rounded-0" id="UpdateBtn" type="submit" variant="primary">
                                <x-base.lucide
                                    class="h-6 w-6 mr-2"
                                    icon="circle-check-big"
                                /> Update
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