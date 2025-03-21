@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium">Quote</h2>
        {{--<div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
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
        </div>--}}
    </div>
    <form method="post" action="#" id="JobQuoteForm">
        <div class="grid grid-cols-11 gap-x-6 pb-20 mt-5">
            <div class="intro-y col-span-12 max-sm:mb-5 sm:col-span-2">
                <div class="sticky top-0">
                    <div class="flex flex-col justify-center items-center shadow-md rounded-md bg-white p-5">
                        <x-base.button value="3" onclick="this.form.submit_type.value = this.value" type="submit" class="text-white w-full mb-2 formSubmits submit_3" variant="linkedin">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                            Approve & Email
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        <x-base.button value="2" onclick="this.form.submit_type.value = this.value" type="submit" class="formSubmits submit_2 w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#3b5998] [&.active]:text-white hover:bg-[#3b5998] focus:bg-[#3b5998] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="eye-off" />
                            Approve & Preview
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        <x-base.button type="button" id="addDiscountBtn" class="w-full border-0 cursor-pointer text-slate-500 shadow-none hover:bg-[#517fa4e6] focus:bg-[#517fa4e6] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="minus-circle" />
                            Add Discount
                        </x-base.button>
                        @if($hasInvoice == 0)
                        <x-base.button type="button" data-id="{{ $quote->id }}" id="convertQuotToInvBtn" class="w-full border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-right" />
                            Convert Quot to Invoice
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        @endif
                    </div>
                    <div class="flex justify-center mt-3">
                        <x-base.button value="1" onclick="this.form.submit_type.value = this.value" type="submit" class="formSubmits submit_1 mb-2 mr-1 w-full" variant="outline-success">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="save" />
                            Save
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#84cc16" icon="oval" />
                        </x-base.button>
                    </div>
                    <x-base.form-switch class="flex items-center mt-3 w-full ml-0">
                        <x-base.form-switch.label for="nonVatQuoteCheck" class="cursor-pointer ml-0 font-medium">Non-VAT Quote</x-base.form-switch.label>
                        <x-base.form-switch.input checked="{{ ($quote->non_vat_quote == 1 ? 1 : 0) }}" id="nonVatQuoteCheck" class="mr-0 ml-auto" type="checkbox" name="non_vat_quote" value="1" />
                    </x-base.form-switch>
                    <input type="hidden" value="1" name="submit_type"/>
                </div>
            </div>
            <div class="intro-y col-span-12 sm:col-span-9">
                <div class="intro-y box overflow-hidden">
                    <div class="border-b border-slate-200/60 text-center dark:border-darkmode-400 sm:text-left">
                        <div class="w-full flex justify-between flex-row py-5 px-5 sm:px-8 sm:py-8">
                            <div class=" text-left">
                                <div class="text-3xl font-semibold text-primary">
                                    <img class="w-28" src="{{ Vite::asset('resources/images/gas_safe_register.png') }}" alt="Gas Safe Register Logo">
                                </div>
                                <div class="mt-5">
                                    <span class="font-bold text-xl">Address to</span>
                                </div>
                                <div class="mt-1">
                                    <span class="block font-medium mb-1">{{ (isset($job->customer->full_name) ? $job->customer->full_name : '') }}</span>
                                    {!! (isset($job->customer->full_address_html) ? $job->customer->full_address_html : '') !!}
                                </div>
                            </div>
                            <div class="text-right max-sm:max-w-[50%]">
                                <div class="text-3xl font-semibold">Invoice</div>
                                <div class="text-xl font-semibold text-primary">Ref: {{ $quote->quote_number }}</div>
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
                                    @if($quote->non_vat_quote != 1)
                                    <div class="vatNumberField">
                                        <span class="font-bold">VAT:</span>
                                        <span>{{ $quote->vat_number }}</span>
                                    </div>
                                    @endif

                                </div>
                                <div class="mt-2 flex justify-end items-center gap-1">
                                    <span class="font-bold max-sm:hidden">Date Issued:</span>
                                    <x-base.litepicker name="issued_date" id="date_issued" value="{{ !empty($quote->issued_date) ? date('d-m-Y', strtotime($quote->issued_date)) : date('d-m-Y') }}" class="block w-28 sm:w-32  text-right" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off" />

                                </div>
                                <div class="mt-2 flex justify-end items-center gap-1">
                                    <span class="font-bold max-sm:hidden">Job Ref No:</span>
                                    <x-base.form-input class="block  w-28 sm:w-32 text-right" name="reference_no" type="text" value="{{ (isset($quote->reference_no) ? $quote->reference_no : '') }}" />
                                </div>
                                <div class="mt-5 sm:mt-10 lg:ml-auto lg:mt-0 lg:text-right">
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
                    <div class="px-5 py-5 sm:px-8 sm:py-8">
                        <div class="overflow-x-auto">
                            <x-base.table bordered sm id="quoteItemsTable">
                                <x-base.table.thead class="max-sm:hidden">
                                    <x-base.table.tr>
                                        <x-base.table.th class="description whitespace-nowrap text-left">DESCRIPTION</x-base.table.th>
                                        <x-base.table.th class="units whitespace-nowrap text-right">UNITS</x-base.table.th>
                                        <x-base.table.th class="price whitespace-nowrap text-right">PRICE</x-base.table.th>
                                        <x-base.table.th class="vatField whitespace-nowrap text-right {{ $quote->non_vat_quote == 1 ? 'hidden' : 'table-cell max-sm:block' }}">VAT %</x-base.table.th>
                                        <x-base.table.th class="lineTotal whitespace-nowrap text-right">LINE TOTAL</x-base.table.th>
                                    </x-base.table.tr>
                                </x-base.table.thead>
                                <x-base.table.tbody >
                                    @php $serial = 1; @endphp
                                    @if(isset($quote->items) && $quote->items->count() > 0)
                                        @foreach($quote->items as $item)
                                            @php 
                                                $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                                                $unitPrice = (!empty($item->unit_price) && $item->unit_price > 0 ? $item->unit_price : 0);
                                                $vatRate = (!empty($item->vat_rate) && $item->vat_rate > 0 ? $item->vat_rate : 0);
                                                $vatAmount = ($unitPrice * $vatRate) / 100;
                                                $lineTotal = ($unitPrice * $units) + $vatAmount;
                                                $key = ($item->type == 'Discount' ? 'discount' : $serial);
                                                $class = ($item->type == 'Discount' ? 'quoteDiscountRow' : 'quoteItemRow');
                                            @endphp
                                            <x-base.table.tr class="{{ $class }} cursor-pointer" data-id="{{ $serial }}" >
                                                <x-base.table.td class="descriptions max-sm:block max-sm:w-full">
                                                    <div class="flex justify-start items-start">
                                                        <x-base.lucide class="w-4 h-4 mr-3" icon="check-circle" />
                                                        <span>{{ (isset($item->description) && !empty($item->description) ? $item->description : 'Quote Item') }}</span>
                                                    </div>
                                                    <input type="hidden" name="qot[{{ $key }}][descritpion]" class="description" value="{{ (isset($item->description) && !empty($item->description) ? $item->description : 'Quote Item') }}"/>
                                                </x-base.table.td>
                                                <x-base.table.td data-th="UNITS" class="units w-full sm:w-[120px] text-left sm:text-right max-sm:block">
                                                    {{ $units }}
                                                    <input type="hidden" name="qot[{{ $key }}][units]" class="unit" value="{{ $units }}"/>
                                                </x-base.table.td>
                                                <x-base.table.td data-th="PRICE" class="prices w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">
                                                    {{ Number::currency($unitPrice, 'GBP') }}
                                                    <input type="hidden" name="qot[{{ $key }}][unit_price]" class="unit_price" value="{{ $unitPrice }}"/>
                                                </x-base.table.td>
                                                <x-base.table.td data-th="VAT %" class="vatCol w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block {{ $quote->non_vat_quote == 1 ? 'hidden' : 'table-cell max-sm:block' }}">
                                                    {{ $vatRate.'%' }}
                                                    <input type="hidden" name="qot[{{ $key }}][vat_rate]" class="vat_rate" value="{{ $vatRate }}"/>
                                                    <input type="hidden" name="qot[{{ $key }}][vat_amount]" class="vat_amount" value="{{ $vatAmount }}"/>
                                                </x-base.table.td>
                                                <x-base.table.td data-th="LINE TOTAL" class="lineTotal w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">
                                                    <span class="line_total_html">{{ $item->type == 'Discount' ? '-' : ''}}{{ Number::currency($lineTotal, 'GBP') }}</span>
                                                    <input type="hidden" name="qot[{{ $key }}][line_total]" class="line_total" value="{{ $lineTotal }}"/>
                                                </x-base.table.td>
                                            </x-base.table.tr>
                                            @php $serial++; @endphp
                                        @endforeach
                                    @else
                                        @php 
                                            $units = 1;
                                            $unitPrice = (!empty($job->estimated_amount) && $job->estimated_amount > 0 ? $job->estimated_amount : 0);
                                            $vatRate = 20;
                                            $vatAmount = (!empty($job->estimated_amount) && $job->estimated_amount > 0 ? ($job->estimated_amount * $vatRate) / 100 : 0);
                                            $lineTotal = ($unitPrice * $units) + $vatAmount;
                                        @endphp
                                        <x-base.table.tr class="quoteItemRow cursor-pointer" data-id="1" >
                                            <x-base.table.td class="descriptions max-sm:block max-sm:w-full">
                                                <div class="flex justify-start items-start">
                                                    <x-base.lucide class="w-4 h-4 mr-3" icon="check-circle" />
                                                    <span>{{ (isset($job->description) && !empty($job->description) ? $job->description : 'Quote Item') }}</span>
                                                </div>
                                                <input type="hidden" name="qot[1][descritpion]" class="description" value="{{ (isset($job->description) && !empty($job->description) ? $job->description : 'Quote Item') }}"/>
                                            </x-base.table.td>
                                            <x-base.table.td data-th="UNITS" class="units w-full sm:w-[120px] text-left sm:text-right max-sm:block">
                                                {{ $units }}
                                                <input type="hidden" name="qot[1][units]" class="unit" value="{{ $units }}"/>
                                            </x-base.table.td>
                                            <x-base.table.td data-th="PRICE" class="prices w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">
                                                {{ Number::currency($unitPrice, 'GBP') }}
                                                <input type="hidden" name="qot[1][unit_price]" class="unit_price" value="{{ $unitPrice }}"/>
                                            </x-base.table.td>
                                            <x-base.table.td data-th="VAT %" class="vatCol w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block {{ $quote->non_vat_quote == 1 ? 'hidden' : 'table-cell max-sm:block' }}">
                                                {{ $vatRate.'%' }}
                                                <input type="hidden" name="qot[1][vat_rate]" class="vat_rate" value="{{ $vatRate }}"/>
                                                <input type="hidden" name="qot[1][vat_amount]" class="vat_amount" value="{{ $vatAmount }}"/>
                                            </x-base.table.td>
                                            <x-base.table.td data-th="LINE TOTAL" class="lineTotal w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">
                                                <span class="line_total_html">{{ Number::currency($lineTotal, 'GBP') }}</span>
                                                <input type="hidden" name="qot[1][line_total]" class="line_total" value="{{ $lineTotal }}"/>
                                            </x-base.table.td>
                                        </x-base.table.tr>
                                    @endif
                                </x-base.table.tbody>
                            </x-base.table>
                            <x-base.button class="mt-3 mb-2 mr-1 text-sm" variant="secondary" id="addQuoteItem" >
                                <x-base.lucide class="mx-auto block" icon="Plus"/> Add Item 
                            </x-base.button>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse px-5 pb-10 sm:flex-row sm:px-8 sm:pb-8">
                        <div class="mt-10 text-center sm:mt-0 sm:text-left w-full sm:w-3/5">
                            <div class="grid grid-cols-12 gap-4 max-sm:text-left">
                                <div class="col-span-12 sm:col-span-6">
                                    <x-base.form-label class="mb-1 max-sm:text-left">Payment Terms</x-base.form-label>
                                    <x-base.form-textarea rows="2" name="payment_term" class="w-full">{{ (isset($quote->payment_term) ? $quote->payment_term : '') }}</x-base.form-textarea>
                                </div>
                                <div class="col-span-12 sm:col-span-6 sm:pl-10">
                                    <x-base.form-label class="mb-1">Bank Details:</x-base.form-label>
                                    @if(isset($company->bank->bank_name) && !empty($company->bank->bank_name))
                                    <div class="mb-1 max-sm:text-left">
                                        <span class="font-medium text-slate-400 mr-1 inline-flex w-[120px]">Bank Name:</span>
                                        <span>{{ $company->bank->bank_name}}</span>
                                    </div>
                                    @endif
                                    @if(isset($company->bank->name_on_account) && !empty($company->bank->name_on_account))
                                    <div class="mb-1 max-sm:text-left">
                                        <span class="font-medium text-slate-400 mr-1 inline-flex w-[120px]">Account Name:</span>
                                        <span>{{ $company->bank->name_on_account}}</span>
                                    </div>
                                    @endif
                                    @if(isset($company->bank->sort_code) && !empty($company->bank->sort_code))
                                    <div class="mb-1 max-sm:text-left">
                                        <span class="font-medium text-slate-400 mr-1 inline-flex w-[120px]">Sort Code:</span>
                                        <span>{{ $company->bank->sort_code}}</span>
                                    </div>
                                    @endif
                                    @if(isset($company->bank->account_number) && !empty($company->bank->account_number))
                                    <div class="mb-1 max-sm:text-left">
                                        <span class="font-medium text-slate-400 mr-1 inline-flex w-[120px]">Account Number:</span>
                                        <span>{{ $company->bank->account_number}}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <x-base.form-label class="mb-1">Notes</x-base.form-label>
                                    <x-base.form-textarea rows="2" name="notes" class="w-full">{{ (!empty($quote->notes) ? $quote->notes : '') }}</x-base.form-textarea>
                                </div>
                            </div>
                        </div>
                        <div class="calculation sm:ml-auto text-right">
                            <div class="mt-2 font-medium text-md">
                                <span>Subtotal:</span>
                                <span class="w-[80px] inline-flex justify-end subtotal_price max-sm:ml-auto">£0.00</span>
                                <input type="hidden" name="sub_total" value="0"/>
                            </div>
                            <div class="mt-2 font-medium text-md vatTotalField" style="display: {{ $quote->non_vat_quote == 1 ? 'none' : 'block' }};">
                                <span>Vat Total:</span>
                                <span class="w-[80px] inline-flex justify-end vat_total_price">£0.00</span>
                                <input type="hidden" name="vat_total_price" value="0"/>
                            </div>
                            <div class="mt-2 font-bold text-md">
                                <span>Total:</span>
                                <span class="w-[80px] inline-flex justify-end total_price">£0.00</span>
                                <input type="hidden" name="total_price" value="0"/>
                            </div>
                            <hr>
                            <div class="mt-2 font-medium text-md">
                                <span>Due:</span>
                                <span class="w-[80px] inline-flex justify-end due_price">£0.00</span>
                                <input type="hidden" name="due_price" value="0"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input id="customer_job_id" name="customer_job_id" type="hidden" value="{{ $job->id }}" />
            <input id="customer_id" name="customer_id" type="hidden" value="{{ $job->customer_id }}" />
            <input id="job_form_id" name="job_form_id" type="hidden" value="{{ $form->id }}" />
            <input id="quote_id" name="quote_id" type="hidden" value="{{ $quote->id }}" />
        </div>
    </form>

    @include('app.records.quote.modals')
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
    @vite('resources/js/app/records/quote.js')
@endPushOnce