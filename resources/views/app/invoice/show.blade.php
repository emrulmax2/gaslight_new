@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Edit {{ $form->name }}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin"><x-base.lucide class="h-4 w-4" icon="home" /></x-base.button>
        </div>
    </div>
    <form method="post" action="#" id="recordForm">
        <div class="grid grid-cols-11 gap-x-6 pb-20 mt-5">
            <div class="intro-y col-span-12 max-sm:mt-5 sm:col-span-2 order-2 sm:order-1">
                <div class="sticky top-0">
                    <div class="flex flex-col justify-center items-center shadow-md rounded-md bg-white p-5">
                        <x-base.button data-id="{{ $invoice->id }}" type="button" class="editRecordBtn justify-start w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#164e63] [&.active]:text-white hover:bg-[#164e63] focus:bg-[#164e63] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="Pencil" />
                            Edit
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        @if(!empty($thePdf))
                        <x-base.button as="a" href="{{ $thePdf }}" class="action_btns justify-start w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#3b5998] [&.active]:text-white hover:bg-[#3b5998] focus:bg-[#3b5998] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="download" />
                            Download
                        </x-base.button>
                        @endif
                        @if(isset($invoice->customer->contact->email) && !empty($invoice->customer->contact->email))
                            <x-base.button type="button" data-tw-toggle="modal" data-tw-target="#sendEmailModal" class="justify-start submit_2 action_btns w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                                {{ $invoice->status == 'Send' ? 'Resend' : 'Send' }}
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        @else 
                            <x-base.button type="button" data-tw-toggle="modal" data-tw-target="#sendEmailModal" class="justify-start submit_2 action_btns w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                                Insert & Send
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        @endif
                        @if($invoice->pay_status == 'Unpaid' && ( isset($invoice->invoice_due) && $invoice->invoice_due > 0))
                            <x-base.button type="button" data-tw-toggle="modal" data-tw-target="#makePaymentModal" class="makePayment justify-start action_btns w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#4ab3f4] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#4ab3f4] hover:text-white focus:text-white">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="badge-pound-sterling" />
                                Make a Payment
                            </x-base.button>
                        @endif
                    </div>
                    <input type="hidden" value="1" name="submit_type"/>
                </div>
            </div>
            <div class="intro-y col-span-12 sm:col-span-9 order-1 sm:order-2">
                <div class="intro-y box p-5 relative overflow-hidden">
                    @if($invoice->pay_status == 'Canceled' || $invoice->pay_status == 'Refunded')
                        <button class="ml-auto -rotate-45 absolute w-[158px] top-[14px] left-[-49px] font-medium bg-danger text-white text-[12px] leading-none uppercase text-center py-1.5">{{ $invoice->pay_status }}</button>
                    @elseif($invoice->pay_status == 'Paid')
                        <button class="ml-auto -rotate-45 absolute w-[158px] top-[14px] left-[-49px] font-medium bg-success text-white text-[12px] leading-none uppercase text-center py-1.5">{{ $invoice->pay_status }}</button>
                    @else
                        <button class="ml-auto -rotate-45 absolute w-[158px] top-[14px] left-[-49px] font-medium bg-pending text-white text-[12px] leading-none uppercase text-center py-1.5">{{ $invoice->pay_status }}</button>
                    @endif
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
            <input id="invoice_id" name="invoice_id" type="hidden" value="{{ $invoice->id }}" />
        </div>
    </form>

   
    @include('app.invoice.show-modal')
    @include('app.action-modals')
@endsection
@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/vendors/tom-select.css')
    @vite('resources/css/vendors/ckeditor.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/tom-select.js')
    @vite('resources/js/vendors/ckeditor/classic.js')
    @vite('resources/js/vendors/toastify.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/invoice/show.js')
@endPushOnce