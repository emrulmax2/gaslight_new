@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium">Invoice</h2>
        <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
            <x-base.button class="mr-2 shadow-md" variant="primary">Print</x-base.button>
            <x-base.menu class="ml-auto sm:ml-0">
                <x-base.menu.button class="!box px-2" as="x-base.button" >
                    <span class="flex h-5 w-5 items-center justify-center">
                        <x-base.lucide class="h-4 w-4" icon="Plus" />
                    </span>
                </x-base.menu.button>
                <x-base.menu.items class="w-40">
                    <x-base.menu.item>
                        <x-base.lucide class="mr-2 h-4 w-4" icon="File" /> Export Word
                    </x-base.menu.item>
                    <x-base.menu.item>
                        <x-base.lucide class="mr-2 h-4 w-4" icon="File" /> Export PDF
                    </x-base.menu.item>
                </x-base.menu.items>
            </x-base.menu>
        </div>
    </div>
    <div class="grid grid-cols-11 gap-x-6 pb-20 mt-5">
        <div class="intro-y col-span-2 hidden 2xl:block">
            <div class="sticky top-0">
                <div class="flex flex-col justify-center items-center shadow-md rounded-md bg-white p-5">
                    <x-base.button type="submit" id="jobSaveBtn" class="text-white w-full mb-2 approveAndEmailBtn" variant="linkedin">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                        Approve & Email
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button type="button" class="w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none hover:bg-[#3b5998] focus:bg-[#3b5998] hover:text-white focus:text-white">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="eye-off" />
                        Approve & Preview
                    </x-base.button>
                    <x-base.button type="button" id="addPrepaymentBtn" class="w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none hover:bg-[#4ab3f4] focus:bg-[#4ab3f4] hover:text-white focus:text-white">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />
                        Add Pre-Payment
                    </x-base.button>
                    <x-base.button type="button" id="addDiscountBtn" class="w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none hover:bg-[#517fa4e6] focus:bg-[#517fa4e6] hover:text-white focus:text-white">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="minus-circle" />
                        Add Discount
                    </x-base.button>
                    <x-base.button class="w-full border-0 cursor-pointer text-slate-500 shadow-none hover:bg-[#f1f5f9e6] focus:bg-[#f1f5f9e6]">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-right" />
                        Convert Invoice to Quote
                    </x-base.button>
                </div>
                <div class="flex justify-center mt-3">
                    <x-base.button class="mb-2 mr-1 w-full" variant="outline-success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="save" />
                        Save
                    </x-base.button>
                </div>
                <div class="flex items-center mt-3 w-full ml-0">
                    <label for="nonVatInvoiceCheck" class="cursor-pointer ml-0 font-medium">Non-VAT Invoice</label>
                    <input {{ (empty($company->vat_number) ? 'Checked' : '') }} name="vat_registerd" value="1" id="nonVatInvoiceCheck" type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white ml-auto">
                </div>
            </div>
        </div>
        <div class="intro-y col-span-11 2xl:col-span-9">
            <div class="intro-y box overflow-hidden">
                <div class="border-b border-slate-200/60 text-center dark:border-darkmode-400 sm:text-left">
                    <div class="w-full flex justify-between">
                        <div class="px-8 py-10 sm:px-8 sm:py-8">
                            <div class="text-3xl font-semibold text-primary">
                                <img class="w-28" src="{{ Vite::asset('resources/images/gas_safe_register.png') }}" alt="Gas Safe Register Logo">
                            </div>
                            <div class="mt-2">
                                <span class="font-bold text-xl">Address to</span>
                            </div>
                            <div class="mt-1">
                                <span class="block font-medium mb-1">{{ (isset($job->customer->full_name) ? $job->customer->full_name : '') }}</span>
                                {!! (isset($job->customer->full_address_html) ? $job->customer->full_address_html : '') !!}
                            </div>
                        </div>
                        <div class="px-5 py-10 sm:px-8 sm:py-8 text-right">
                            <div class="text-3xl font-semibold text-primary">Invoice</div>
                            <div class="mt-2">
                                <span class="font-bold text-xl">{{ (isset($company->company_name) ? $company->company_name : '')}}</span>
                            </div>
                            <div class="mt-1">
                                {!! (isset($company->full_address_html) ? $company->full_address_html : '') !!}
                                @if(isset($company->company_email))
                                    <div>
                                        <span>{{ $company->company_email }}</span>
                                    </div>
                                @endif
                                @if(isset($company->company_phone))
                                    <div>
                                        <span>{{ $company->company_phone }}</span>
                                    </div>
                                @endif
                                <br>
                                @if(!empty($company->vat_number))
                                <div class="vatNumberField">
                                    <span class="font-bold">VAT:</span>
                                    <span>{{ $company->vat_number }}</span>
                                </div>
                                @endif

                            </div>
                            <div class="mt-2 flex justify-end items-center gap-1">
                                <span class="font-bold">Date Issued:</span>
                                <x-base.litepicker name="issued_date" id="date_issued" value="{{ isset($job->issued_date) ? date('d-m-Y', strtotime($job->issued_date)) : date('d-m-Y') }}" class="block w-32 text-right" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off" />

                            </div>
                            <div class="mt-2 flex justify-end items-center gap-1">
                                <span class="font-bold">Job Ref No:</span>
                                <x-base.form-input class="block w-32 text-right" id="job_ref_no" type="text" value="{{ (isset($job->reference_no) ? $job->reference_no : $ref_no) }}" />
                            </div>
                            <div class="mt-10 lg:ml-auto lg:mt-0 lg:text-right">
                                <div class="mt-2">
                                    <span class="font-bold text-xl">Job Address</span>
                                </div>
                                <div class="mt-1">
                                    {!! (isset($job->property->full_address_html) ? $job->property->full_address_html : '') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-2 sm:px-8 sm:py-8">
                    <div class="overflow-x-auto">
                        <x-base.table bordered sm class="invoiceItemsTable">
                            <x-base.table.thead>
                                <x-base.table.tr>
                                    <x-base.table.th class="description whitespace-nowrap text-right">DESCRIPTION</x-base.table.th>
                                    <x-base.table.th class="units whitespace-nowrap text-right">UNITS</x-base.table.th>
                                    <x-base.table.th class="price whitespace-nowrap text-right">PRICE</x-base.table.th>
                                    <x-base.table.th class="vatField whitespace-nowrap text-right">VAT %</x-base.table.th>
                                    <x-base.table.th class="lineTotal whitespace-nowrap text-right">LINE TOTAL</x-base.table.th>
                                </x-base.table.tr>
                            </x-base.table.thead>
                            <x-base.table.tbody >
                                <x-base.table.tr class="editInvoiceModal" data-id="1" >
                                    <x-base.table.td class="description">
                                        <div class="flex justify-start items-start">
                                            <x-base.lucide class="w-4 h-4 mr-3" icon="check-circle" />
                                            <span>{{ (isset($job->description) && !empty($job->description) ? $job->description : 'Invoice Item') }}</span>
                                        </div>
                                    </x-base.table.td>
                                    <x-base.table.td class="units w-[120px] text-right">
                                        1
                                    </x-base.table.td>
                                    <x-base.table.td class="price w-[120px] text-right font-medium">
                                        {{ Number::currency(0, 'GBP') }}
                                    </x-base.table.td>
                                    <x-base.table.td class="vat w-[120px] text-right font-medium">
                                        {{ Number::currency(0, 'GBP') }}
                                    </x-base.table.td>
                                    <x-base.table.td class="lineTotal w-[120px] text-right font-medium">
                                        {{ Number::currency(0, 'GBP') }}
                                    </x-base.table.td>
                                </x-base.table.tr>
                            </x-base.table.tbody>
                        </x-base.table>
                        <x-base.button class="mt-2 ml-5 mb-2 mr-1 text-sm" variant="secondary" id="addInvoiceModalShow" >
                            <x-base.lucide class="mx-auto block" icon="Plus"/> Add Item 
                        </x-base.button>
                    </div>
                </div>
                <div class="flex flex-col-reverse px-5 pb-10 sm:flex-row sm:px-8 sm:pb-8">
                    <div class="mt-10 text-center sm:mt-0 sm:text-left w-2/5">
                        <div class="text-base text-slate-500">Notes:</div>
                        <div class="mt-2 text-lg font-medium text-primary">
                            <textarea name="notes" class="w-full transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 " id="" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="calculation text-center sm:ml-auto sm:text-right">
                        <div class="mt-2 font-medium text-md">
                            <span>Subtotal:</span>
                            <span class="ml-2 currency">$</span> <span class="subtotal_price">0.00</span>
                        </div>
                        <div class="mt-2 font-medium text-md vatTotalField">
                            <span>Vat Total:</span>
                            <span class="ml-2 currency">$</span> <span class="vat_total_price">0.00</span>
                        </div>
                        <div class="mt-2 font-bold text-md">
                            <span>Total:</span>
                            <span class="ml-2 currency">$</span> <span class="total_price">0.00</span>
                        </div>
                        <hr>
                        <div class="my-1 font-bold text-md paidToDateField hidden">
                            <span>Paid to date:</span>
                            <span class="ml-2 currency">$</span> <span class="paid_to_date">0.00</span>
                        </div>
                        <hr>
                        <div class="mt-2 font-medium text-md">
                            <span>Due:</span>
                            <span class="ml-2 currency">$</span> <span class="due_price">0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-base.form-input class="" id="customer_job_id" type="hidden" value="{{ $job->id }}" />
    </div>
    @include('app.records.invoice.modals')
    @include('app.action-modals')
@endsection
@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/records/invoice.js')
@endPushOnce