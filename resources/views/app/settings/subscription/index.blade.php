@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">User Subscriptions & Invoices</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box mt-5 p-3 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form class="sm:mr-auto xl:flex" id="tabulator-html-filter-form" >
                <!-- <div class="mt-0 sm:mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial max-sm:hidden">Keywords</label>
                    <x-base.form-input class="max-sm:mt-2 sm:mt-0 sm:w-40 2xl:w-full" id="query" type="text" placeholder="Search..." />
                </div> -->
                <div class="items-center sm:mr-4 sm:flex max-sm:hidden">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Users </label>
                    <x-base.form-select class="mt-2 tom-select w-full sm:mt-0 sm:w-auto 2xl:w-44 p-0 pr-8" id="user_id" >
                        <option value="">All</option>
                        @if($users->count() > 0)
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        @endif
                    </x-base.form-select>
                </div>
                <div class="items-center sm:mr-4 sm:flex max-sm:hidden">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Status </label>
                    <x-base.form-select class="mt-2 w-full sm:mt-0 sm:w-auto 2xl:w-full" id="status" >
                        <option value="">All</option>
                        <option value="trialing">Trail</option>
                        <option value="active">Active</option>
                        <option value="incomplete">Incomplete</option>
                        <option value="incomplete_expired">Incomplete Expired</option>
                        <option value="canceled">Canceled</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="paused">Paused</option>
                    </x-base.form-select>
                </div>
                <div class="mt-2 xl:mt-0 max-sm:hidden">
                    <x-base.button class="w-full sm:w-16" id="tabulator-html-filter-go" type="button" variant="primary" >Go</x-base.button>
                    <x-base.button class="mt-2 w-full sm:ml-1 sm:mt-0 sm:w-16" id="tabulator-html-filter-reset" type="button" variant="secondary" >Reset</x-base.button>
                </div>
            </form>
            <div class="mt-5 flex sm:mt-0 max-sm:hidden">
                <!-- <x-base.button href="{{ (isset(request()->record) && !empty(request()->record) ? route('jobs.create', ['record' => request()->record]) : route('jobs.create')) }}" as="a" type="button" class="w-auto" variant="primary" >
                    <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" /> Add Job
                </x-base.button> -->
            </div>
        </div>
        <div class="scrollbar-hidden overflow-x-auto">
            <div class="mt-3 sm:mt-5 gca_responsive" id="subscriptionListTable" ></div>
        </div>
    </div>
    <!-- END: HTML Table Data -->

    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/vendors/tom-select.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/tom-select.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/user-settings/subscription.js')
@endPushOnce