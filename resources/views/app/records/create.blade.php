@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $form->name }}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <form method="post" action="#" id="certificateForm" enctype="multipart/form-data">
        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
        <input type="hidden" name="certificate_id" id="certificate_id" value="0"/>

        @if($form->id == 3)
            <div class="intro-y box mt-5 bg-slate-200 rounded-none border-none px-2 py-2 quoteNoBlockWrap" style="display: none;">
                <div class="px-2 py-3 quoteNoWrap bg-white">
                    <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer quoteNoBlock">
                        <div>
                            <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">QUOTE #</div>
                            <div class="theDesc">N/A</div>
                        </div>
                        <span style="flex: 0 0 16px; margin-left: 20px;"></span>
                    </a>
                </div>
            </div>
        @endif

        <!-- BEGIN: Job & Customer Blade -->
        @include('app.records.customer-info')
        <!-- END: Job & Customer Blade -->
        
        <!-- BEGIN: Job & Customer Blade -->
        @include('app.records.'.$form->slug.'.index')
        <!-- END: Job & Customer Blade -->
        

        <!-- BEGIN: Job & Customer Blade -->
        @if($form->id != 3 && $form->id != 4)
            @include('app.records.signature')
        @endif
        <!-- END: Job & Customer Blade -->
        

        <div class="intro-y box mt-2 rounded-none border-none px-2 py-2">
            <div class="flex justify-center items-center">
                <x-base.button class="w-auto text-white" id="saveCertificateBtn" type="button" variant="success">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Create {{ ($form->id == 3 ? 'Quote' : ($form->id == 4 ? 'Invoice' : 'Certificate')) }}
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </div>
        </div>
    </form>

    <!-- END: HTML Table Data -->

    @include('app.records.'.$form->slug.'.modal')
    @include('app.records.modal')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/vendors/tom-select.css')
    @vite('resources/css/custom/signature.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/tom-select.js')
    @vite('resources/js/vendors/sign-pad.min.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/records/create.js')
    @vite('resources/js/app/records/'.$form->slug.'.js')
@endPushOnce