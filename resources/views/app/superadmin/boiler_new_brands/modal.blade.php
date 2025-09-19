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
            <div id="titleModal" class="text-xl ml-2">Create Boiler Brand</div>
        </div>
        <div id="base-start" class=" flex flex-col px-16 pt-5 pb-16">
            <div>
                <div class="mb-4">
                    <form id="createForm" enctype="multipart/form-data" class="mb-4">
                        
                        <div class="mt-3">
                            <label data-tw-merge for="name"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input data-tw-merge id="name" type="text" name="name" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-name"></div>
                        </div>
                     <div class="mt-3">
                            <label for="document" class="inline-block mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                File <span class="text-danger">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input id="document" type="file"  name="document" class="brandDocumentInput absolute inset-0 opacity-0 cursor-pointer z-10" />
                                <div class="w-full flex items-center border border-slate-200 shadow-sm rounded-md bg-white px-3 py-2 text-sm text-slate-500 dark:bg-darkmode-800 dark:border-transparent">
                                    <x-base.lucide class="h-5 w-5 text-slate-400 mr-2" icon="file" />
                                    <span id="brandDocumentfileName" class="truncate">Choose a file...</span>
                                    <span class="ml-auto inline-flex items-center px-3 py-1 rounded bg-primary text-white text-xs font-medium">
                                        Browse
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 text-danger error-document"></div>
                        </div>
                        <div class="mt-5 flex justify-between">
                            <x-base.button class="w-52 border-0 rounded-0 text-white" id="downloadSampleBtn" type="button" variant="success">
                                <x-base.lucide
                                    class="h-6 w-6 mr-2"
                                    icon="files"
                                /> Download Sample
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
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
        {{-- <div class="absolute left-0 top-0 ml-3 mt-3  bg-primary text-white text-sm font-semibold px-2 py-1 rounded">
            Step <span class="current-step"> 01 </span> of <span class="total-step">02</span>
        </div> --}}
        <div class="px-16 pt-16 pb-0 flex items-left">
            <x-base.lucide
                class=" h-6 w-6 text-primary"
                icon="file-pen-line"
            />
            <div id="titleModal" class="text-xl ml-2">Edit Boiler Brand</div>
        </div>
        <div id="base-start" class=" flex flex-col px-16 pt-5 pb-16">
            <div>
                <div class="mb-4">
                    <form id="updateForm" enctype="multipart/form-data" class="mb-4">
                        
                        <div class="mt-3">
                            <label data-tw-merge for="name"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input data-tw-merge id="name" type="text" name="name" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger error-name"></div>
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