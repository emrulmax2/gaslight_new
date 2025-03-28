@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $form->name }}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin"><x-base.lucide class="h-4 w-4" icon="home" /></x-base.button>
        </div>
    </div>
    <form method="post" action="#" id="gasPowerFlushRecordForm">
        <div class="grid grid-cols-11 gap-x-6 pb-20 mt-5">
            <div class="intro-y col-span-12 max-sm:mt-5 sm:col-span-2 order-2 sm:order-1">
                <div class="sticky top-0">
                    <div class="flex flex-col justify-center items-center shadow-md rounded-md bg-white p-5">
                        <x-base.button as="a" href="{{ route('records', [$form->slug, $gpfr->customer_job_id]) }}" class="w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#164e63] [&.active]:text-white hover:bg-[#164e63] focus:bg-[#164e63] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="Pencil" />
                            Edit
                        </x-base.button>
                        <x-base.button value="1" onclick="this.form.submit_type.value = this.value" type="submit" class="formSubmits submit_1 w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                            Approve
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        <x-base.button value="2" onclick="this.form.submit_type.value = this.value" type="submit" class="formSubmits submit_2 w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                            Approve & Email
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        @if(!empty($thePdf))
                        <x-base.button as="a" href="{{ $thePdf }}" class="w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#3b5998] [&.active]:text-white hover:bg-[#3b5998] focus:bg-[#3b5998] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="download" />
                            Download
                        </x-base.button>
                        @endif
                    </div>
                    <input type="hidden" value="1" name="submit_type"/>
                </div>
            </div>
            <div class="intro-y col-span-12 sm:col-span-9 order-1 sm:order-2">
                <div class="intro-y box p-5">
                    @if(!empty($thePdf))
                        <object data="{{ $thePdf }}" type="application/pdf" style="width: 100%; height: 98vh;">
                            <embed src="{{ $thePdf }}" type="application/pdf">
                                <p>This browser does not support PDFs. Please download the PDF to view it: <a target="_blank" href="{{ $thePdf }}">Download PDF</a>.</p>
                            </embed>
                        </object>
                    @else 
                    <div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-0 flex items-center"><i data-tw-merge data-lucide="alert-octagon" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>
                        <span><strong>Oops!</strong> It's look like that certificate not generated yet. Please edit the form and submit the signature again or contact with the administrator.</span>
                    </div>
                    @endif
                </div>
            </div>
            <input id="customer_job_id" name="customer_job_id" type="hidden" value="{{ $gpfr->customer_job_id }}" />
            <input id="customer_id" name="customer_id" type="hidden" value="{{ $gpfr->customer_id }}" />
            <input id="job_form_id" name="job_form_id" type="hidden" value="{{ $form->id }}" />
            <input id="gpfr_id" name="gpfr_id" type="hidden" value="{{ $gpfr->id }}" />
            <input id="form_slug" name="form_slug" type="hidden" value="{{ $form->slug }}" />
        </div>
    </form>

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
    @vite('resources/js/app/records/power_flush_record_show.js')
@endPushOnce