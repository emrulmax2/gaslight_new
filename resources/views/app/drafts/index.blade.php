@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center">
        <h2 class="mr-auto text-lg font-medium">Certificates</h2>
        <div class="flex gap-2">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>
    
    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box mt-5 p-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form class="sm:mr-auto lg:flex w-full lg:w-auto" id="tabulator-html-filter-form" >
                <div class="items-center sm:mr-4 xl:mt-0">
                    <label class="flex-none xl:w-auto xl:flex-initial">Keywords</label>
                    <x-base.form-input class="2xl:w-full h-[35px] rounded-[3px]" id="query" type="text" placeholder="Search..." />
                </div>
                <div class="items-center lg:mr-4 mt-2 lg:mt-0">
                    <label class="flex-none xl:w-auto xl:flex-initial">Engineer</label>
                    <x-base.form-select class="mt-1 w-full sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px]" id="engineer" >
                        <option value="all">All</option>
                        @foreach($engineers as $engineer)
                            <option value="{{ $engineer->id }}">{{ $engineer->name }}</option>
                        @endforeach
                    </x-base.form-select>
                </div>
                <div class="items-center lg:mr-4 mt-2 lg:mt-0 2xl:w-64">
                    <label class="flex-none">Certificate Type </label>
                    <x-base.form-select class="mt-1 w-auto sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px] max-w-full" id="certificate_type" >
                        <option value="all">All</option>
                        @foreach($certificate_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </x-base.form-select>
                </div>
                <div class="items-center  lg:mr-4 mt-2 lg:mt-0 w-full sm:w-56 ">
                    <label class="flex-none">Date Range </label>
                    <x-base.litepicker class="mx-auto w-full h-[35px] rounded-[3px]" id="date_range"  />
                </div>
                <div class="items-center lg:mr-4 mt-2 lg:mt-0">
                    <label class="flex-none">Status </label>
                    <x-base.form-select class="mt-1 w-full sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px]" id="status" >
                        <option value="all">All</option>
                        <option value="Draft">Draft</option>
                        <option value="Approved">Approved</option>
                        <option value="Approved & Sent">Approved & Sent</option>
                        <option value="Cancelled">Cancelled</option>
                    </x-base.form-select>
                </div>
                <div class="mt-4 lg:mt-0 text-right ml-0 sm:ml-auto xl:pt-[20px]">
                    <x-base.button class="w-full sm:w-16 h-[35px]" id="tabulator-html-filter-go" type="button" variant="primary" >Go</x-base.button>
                    <x-base.button class="mt-1 w-full sm:ml-1 sm:mt-0 sm:w-16 h-[35px]" id="tabulator-html-filter-reset" type="button" variant="secondary" >Reset</x-base.button>
                </div>
            </form>
        </div>
        <div class="scrollbar-hidden overflow-x-auto">
            <div class="mt-5 gca_responsive" id="certificateListTable" ></div>
        </div>
    </div>
    <!-- END: HTML Table Data -->

    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/drafts/certificates.js')
@endPushOnce