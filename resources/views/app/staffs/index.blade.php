@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Staff List - Gas Certificate App</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium">Users List</h2>
        <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
            <x-base.button
                class="mr-2 shadow-md"
                variant="primary"
                id="add-new"
            >
            <x-base.lucide
            class="h-4 w-4"
            icon="Plus"
        /> Add New User
            </x-base.button>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <x-base.alert
        class="my-7 flex items-center rounded-[0.6rem] border-primary/20 bg-primary/5 px-4 py-3 leading-[1.7]"
        variant="outline-primary"
    >
        <div class="">
            <x-base.lucide
                class="mr-2 h-7 w-7 fill-primary/10 stroke-[0.8]"
                icon="Lightbulb"
            />
        </div>
        <div class="ml-1 mr-8">
            <span class="font-medium">{{ $message }}</span>
        </div>
        <x-base.alert.dismiss-button class="btn-close text-primary">
            <x-base.lucide
                class="w-5 h-5"
                icon="X"
            />
        </x-base.alert.dismiss-button>
    </x-base.alert>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <x-base.alert
        class="my-7 flex items-center rounded-[0.6rem] border-danger/20 bg-danger/5 px-4 py-3 leading-[1.7]"
        variant="outline-danger"
    >
        <div class="">
            <x-base.lucide
                class="mr-2 h-7 w-7 fill-danger/10 stroke-[0.8]"
                icon="circle-x"
            />
        </div>
        <div class="ml-1 mr-8">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <x-base.alert.dismiss-button class="btn-close text-primary">
            <x-base.lucide
                class="w-5 h-5"
                icon="X"
            />
        </x-base.alert.dismiss-button>
    </x-base.alert>
    @endif
    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box mt-5 p-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form
                class="sm:mr-auto xl:flex"
                id="tabulator-html-filter-form"
            >
                <div class="items-center sm:mr-4 sm:flex">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">
                        Field
                    </label>
                    <x-base.form-select
                        class="mt-2 w-full sm:mt-0 sm:w-auto 2xl:w-full"
                        id="tabulator-html-filter-field"
                    >
                        <option value="name">Name</option>
                        <option value="category">Category</option>
                        <option value="remaining_stock">Remaining Stock</option>
                    </x-base.form-select>
                </div>
                <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">
                        Type
                    </label>
                    <x-base.form-select
                        class="mt-2 w-full sm:mt-0 sm:w-auto"
                        id="tabulator-html-filter-type"
                    >
                        <option value="like">like</option>
                        <option value="=">=</option>
                        <option value="<">&lt;</option>
                        <option value="<=">&lt;=</option>
                        <option value=">">&gt;</option>
                        <option value=">=">&gt;=</option>
                        <option value="!=">!=</option>
                    </x-base.form-select>
                </div>
                <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">
                        Value
                    </label>
                    <x-base.form-input
                        class="mt-2 sm:mt-0 sm:w-40 2xl:w-full"
                        id="tabulator-html-filter-value"
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
            <div class="mt-5 flex sm:mt-0">
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
            </div>
        </div>
        <div class="scrollbar-hidden overflow-x-auto">
            <div
                class="mt-5"
                id="staffListTable"
                data-user="{{ auth()->user()->id }}"
            ></div>
        </div>
    </div>
    
    <!-- END: HTML Table Data -->
    @include('app.staffs.modal')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/custom/signature.css')
@endPushOnce


@pushOnce('vendors')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/staffs/list.js')
    @vite('resources/js/app/staffs/modal.js')
    
@endPushOnce
