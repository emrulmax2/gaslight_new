@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Company Users</h2>
        <div class="flex mt-0 w-auto">
            @if(Auth::user()->parent_id == null)
                <x-base.button data-tw-toggle="modal" data-tw-target="#addnew-modal" class="mr-2 shadow-md" variant="primary" id="add-new" >
                    <x-base.lucide class="h-4 w-4" icon="Plus" /> Add New User
                </x-base.button>
            @endif
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div id="searchBox" class="intro-y box mt-5 p-0 border-none relative">
        <x-base.form-input class="m-0 w-full" id="query" type="text" autocomplete="off" placeholder="Search..."/>
        <x-base.lucide class="h-4 w-4 absolute right-2 top-0 bottom-0 m-auto text-slate-400" icon="search" />
    </div>

    <div class="box px-5 rounded-none scrollbar-hidden overflow-x-auto mt-5">
        <div id="usersListTable"></div>
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