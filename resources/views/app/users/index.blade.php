@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center">
        <h2 class="mr-auto text-lg font-medium">Users List</h2>
        @if(Auth::user()->parent_id == null)
        <div class="flex">
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
        @endif
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
        <div class="flex flex-col sm:flex-row sm:items-center xl:items-start gap-4">
            <form class="flex flex-wrap items-center gap-4 w-full" id="tabulator-html-filter-form">
                
                <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                    <label class="w-full sm:w-auto font-medium">Value</label>
                    <x-base.form-input class="w-full sm:w-40 2xl:w-full" id="tabulator-html-filter-value" type="text" placeholder="Search..." />
                </div>
                {{-- <div class="items-center lg:mr-4 hidden sm:flex">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Status </label>
                    <x-base.form-select class="mt-2 w-full sm:mt-0 sm:w-auto 2xl:w-full" id="status" >
                        <option value="1">Active</option>
                        <option value="2">Archive</option>
                    </x-base.form-select>
                </div> --}}
        
                <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                    <x-base.button class="w-full sm:w-16" id="tabulator-html-filter-go" type="button" variant="primary">
                        Go
                    </x-base.button>
                    <x-base.button class="w-full sm:w-16" id="tabulator-html-filter-reset" type="button" variant="secondary">
                        Reset
                    </x-base.button>
                </div>
            </form>
        </div>
        
        <div class="scrollbar-hidden overflow-x-auto">
            <div class="mt-5" id="usersListTable"></div>
        </div>
    </div>
    
    @include('app.users.modal')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/custom/signature.css')
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
    @vite('resources/js/app/users/users.js')
    @vite('resources/js/app/staffs/dropzone.js')
@endPushOnce
