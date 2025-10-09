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
    <form method="post" action="#" id="recordForm">
        <div class="grid grid-cols-11 gap-x-6 pb-20 mt-5">
            <div class="intro-y col-span-12 max-sm:mt-5 sm:col-span-2 order-2 sm:order-1">
                <div class="sticky top-0">
                    <div class="flex flex-col justify-center items-center shadow-md rounded-md bg-white p-5">
                        <x-base.button data-id="{{ $record->id }}" type="button" class="editRecordBtn justify-start w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#164e63] [&.active]:text-white hover:bg-[#164e63] focus:bg-[#164e63] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="Pencil" />
                            Edit
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        <x-base.button value="1" onclick="this.form.submit_type.value = this.value" type="submit" class="formSubmits justify-start submit_1 action_btns w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                            Approve
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        <x-base.button value="2" onclick="this.form.submit_type.value = this.value" type="submit" class="formSubmits justify-start submit_2 action_btns w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                            Approve & Email
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        @if(!empty($thePdf))
                        <x-base.button as="a" href="{{ $thePdf }}" class="action_btns justify-start w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#3b5998] [&.active]:text-white hover:bg-[#3b5998] focus:bg-[#3b5998] hover:text-white focus:text-white">
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
                        <object class="pdfViewer" data="{{ $thePdf }}" type="application/pdf">
                            <embed src="{{ $thePdf }}" type="application/pdf">
                                <p>This browser does not support PDFs. Please download the PDF to view it: <a target="_blank" href="{{ $thePdf }}">Download PDF</a>.</p>
                            </embed>
                        </object>
                    @else
                        <x-base.alert class="mb-2 flex items-center" variant="soft-pending" >
                            <x-base.lucide class="mr-2 h-6 w-6" icon="AlertTriangle" />
                            Something went wrong. The certificate not generated! Please contact with the administrator.
                        </x-base.alert>
                    @endif
                </div>
            </div>
            <input id="record_id" name="record_id" type="hidden" value="{{ $record->id }}" />
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
    @vite('resources/js/app/records/show.js')
@endPushOnce