@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Dashboard - Midone - Tailwind Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-4 sm:mt-8">
                    <div class="intro-y flex h-auto sm:h-10 items-center">
                        <h2 class="mr-5 truncate text-lg font-medium">Engineers List</h2>
                        
                    </div>
                    <div class="mt-3 sm:mt-5 grid grid-cols-12 gap-2 sm:gap-6">
                    </div>

                    <!-- BEGIN: HTML Table Data -->
                    <div class="intro-y box p-5">
                        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                            <form
                                class="sm:mr-auto xl:flex"
                                id="tabulator-html-filter-form"
                            >
                                
                                <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">
                                        Query: 
                                    </label>
                                    <x-base.form-input

                                        class="mt-2 sm:mt-0 sm:w-40 2xl:w-full"
                                        id="query"
                                        type="text"
                                        placeholder="Search..."
                                    />
                                </div>
                                <div class="mt-2 xl:mt-0">
                                    <x-base.button
                                        class="w-full sm:w-16"
                                        id="tabulator-html-filter-go"
                                        type="button"
                                        variant="primary"
                                    >
                                        Go
                                    </x-base.button>
                                    <x-base.button
                                        class="mt-2 w-full sm:ml-1 sm:mt-0 sm:w-16"
                                        id="tabulator-html-filter-reset"
                                        type="button"
                                        variant="secondary"
                                    >
                                        Reset
                                    </x-base.button>
                                </div>
                            </form>
                            <!-- <div class="mt-5 flex sm:mt-0">
                                <x-base.button
                                    class="mr-2 w-1/2 sm:w-auto"
                                    id="tabulator-print"
                                    variant="outline-secondary"
                                >
                                    <x-base.lucide
                                        class="mr-2 h-4 w-4"
                                        icon="Printer"
                                    /> Print
                                </x-base.button>
                                <x-base.menu class="w-1/2 sm:w-auto">
                                    <x-base.menu.button
                                        class="w-full sm:w-auto"
                                        as="x-base.button"
                                        variant="outline-secondary"
                                    >
                                        <x-base.lucide
                                            class="mr-2 h-4 w-4"
                                            icon="FileText"
                                        /> Export
                                        <x-base.lucide
                                            class="ml-auto h-4 w-4 sm:ml-2"
                                            icon="ChevronDown"
                                        />
                                    </x-base.menu.button>
                                    <x-base.menu.items class="w-40">
                                        <x-base.menu.item id="tabulator-export-csv">
                                            <x-base.lucide
                                                class="mr-2 h-4 w-4"
                                                icon="FileText"
                                            /> Export CSV
                                        </x-base.menu.item>
                                        <x-base.menu.item id="tabulator-export-json">
                                            <x-base.lucide
                                                class="mr-2 h-4 w-4"
                                                icon="FileText"
                                            /> Export
                                            JSON
                                        </x-base.menu.item>
                                        <x-base.menu.item id="tabulator-export-xlsx">
                                            <x-base.lucide
                                                class="mr-2 h-4 w-4"
                                                icon="FileText"
                                            /> Export
                                            XLSX
                                        </x-base.menu.item>
                                        <x-base.menu.item id="tabulator-export-html">
                                            <x-base.lucide
                                                class="mr-2 h-4 w-4"
                                                icon="FileText"
                                            /> Export
                                            HTML
                                        </x-base.menu.item>
                                    </x-base.menu.items>
                                </x-base.menu>
                            </div> -->
                        </div>
                        <div class="scrollbar-hidden overflow-x-auto">
                            <div
                                class="mt-5"
                                id="userListTable"
                                data-user="{{ auth('superadmin')->user()->id }}"
                            ></div>
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->
                </div>

            </div>
        </div>
    </div>
    <!-- BEGIN: Suspension Modal Content -->
    <x-base.dialog id="suspensionModal" class="max-w-full">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="AlertOctagon" />
                <div class="mt-5 text-3xl">Are you sure!</div>
                <div class="mt-2 text-slate-500">Do you really want to suspend this user? If yes, then please click "Yes, I agree" to continue.</div>

                <div class="mt-3 text-left px-14 border-t border-b pt-5 pb-6">
                    <label for="suspension_reason_id" class="block text-center mb-1">Suspension Reason <span class="text-danger ml-1">*</span></label>
                    <x-base.tom-select class="w-full" data-placeholder="Select Reason" name="suspension_reason_id" id="suspension_reason_id" >
                        <option value="">Please select a reason</option>
                        @if($reasons->count() > 0)
                            @foreach($reasons as $rsn)
                                <option value="{{ $rsn->id }}">{{ $rsn->name }}</option>
                            @endforeach
                        @endif
                    </x-base.tom-select>
                    <div class="text-xs text-danger text-center mt-1 error-suspension_reason_id"></div>
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button" variant="outline-secondary">Cancel</x-base.button>
                <x-base.button class="w-auto" id="suspendBtn" type="button" variant="danger" >
                    Yes, I agree
                </x-base.button>
                <input type="hidden" name="user_id" value="0"/>
            </div>
        </x-base.dialog.panel>
    </x-base.dialog>
    <!-- END: Suspension Modal Content -->

    @include('app.action-modals')

@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')

@endPushOnce

@pushOnce('vendors')
@vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
    @vite('resources/js/vendors/axios.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/superadmin/dashboard/index.js')
@endPushOnce
