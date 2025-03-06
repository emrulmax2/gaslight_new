@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Boiler Brand Manual</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-4 sm:mt-8">
                    <div class="intro-y flex h-auto sm:h-10 items-center">
                        <h2 class="mr-5 truncate text-lg font-medium">{{ $boilerBrand->name }} Brand</h2>
                        <div class=" ml-auto mt-4 flex w-full sm:mt-0 sm:w-auto items-end">
                            
                            
                            <x-base.button as='a' href="{{ route('superadmin.boiler-brand.index') }}" id="back"  class="shadow-md mr-2" variant="outline-primary" >
                                <x-base.lucide class="mr-2 h-6 w-6" icon="circle-arrow-left" />
                                Back To Boiler Brand
                            </x-base.button>

                            <x-base.button id="add-new"  class="shadow-md add_btn" variant="primary" >
                                <x-base.lucide class="mr-2 h-6 w-6" icon="circle-plus" />
                                Add Manual
                            </x-base.button>

                            
                            <x-base.button id="upload-excel"  class="shadow-md upload_excel" variant="linked-in" >
                                <x-base.lucide class="mr-2 h-6 w-6" icon="file-spreadsheet" />
                                Upload Excel
                            </x-base.button>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-5 grid grid-cols-12 gap-2 sm:gap-6">
                    </div>

                    <!-- BEGIN: HTML Table Data -->
                    <div class="intro-y box mt-5 p-5">
                        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                            <form
                                class="sm:mr-auto xl:flex"
                                id="tabulator-html-filter-form"
                            >
                                
                                <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Query </label>
                                    <x-base.form-input class="mt-2 sm:mt-0 sm:w-40 2xl:w-full" id="query" type="text" placeholder="Search..." />
                                </div>
                                <div class="items-center sm:mr-4 sm:flex">
                                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Status </label>
                                    <x-base.form-select class="mt-2 w-full sm:mt-0 sm:w-auto 2xl:w-full" id="status" >
                                        <option value="1">Active</option>
                                        <option value="2">Archive</option>
                                    </x-base.form-select>
                                </div>
                                <div class="mt-2 xl:mt-0">
                                    <x-base.button class="w-full sm:w-16" id="tabulator-html-filter-go" type="button" variant="primary" >Go</x-base.button>
                                    <x-base.button class="mt-2 w-full sm:ml-1 sm:mt-0 sm:w-16" id="tabulator-html-filter-reset" type="button" variant="secondary" >Reset</x-base.button>
                                </div>
                            </form>
                        </div>
                        <div class="scrollbar-hidden overflow-x-auto">
                            <div
                                class="mt-5"
                                id="boilerBrandListTable"

                                data-boiler_brand_id="{{ $boilerBrand->id }}"
                            ></div>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->
                </div>

            </div>
        </div>
    </div>

    @include('app.superadmin.boiler_manuals.modal')
    @include('app.action-modals')
@endsection



@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/vendors/dropzone.css')

@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/dropzone.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/boiler-manuals/list.js')
    @vite('resources/js/app/boiler-manuals/crud.js')
    @vite('resources/js/app/boiler-manuals/dropzone.js')
@endPushOnce