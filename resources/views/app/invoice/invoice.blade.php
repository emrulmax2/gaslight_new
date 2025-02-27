@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
<div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
    <h2 class="mr-auto text-lg font-medium">Invoice</h2>
    <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
        <x-base.button
            class="mr-2 shadow-md"
            variant="primary"
        >
            Print
        </x-base.button>
        <x-base.menu class="ml-auto sm:ml-0">
            <x-base.menu.button
                class="!box px-2"
                as="x-base.button"
            >
                <span class="flex h-5 w-5 items-center justify-center">
                    <x-base.lucide
                        class="h-4 w-4"
                        icon="Plus"
                    />
                </span>
            </x-base.menu.button>
            <x-base.menu.items class="w-40">
                <x-base.menu.item>
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="File"
                    /> Export Word
                </x-base.menu.item>
                <x-base.menu.item>
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="File"
                    /> Export PDF
                </x-base.menu.item>
            </x-base.menu.items>
        </x-base.menu>
    </div>
</div>
<div class="mt-5 grid grid-cols-11 gap-x-6 pb-20">
    <div class="intro-y col-span-2 hidden 2xl:block">
        <div class="sticky top-0 pt-6">
            <div class="flex flex-col justify-center items-center shadow-md rounded-md bg-white p-5">
                <x-base.button class="mb-2 mr-1 w-52" variant="danger">Approve & Email</x-base.button>
                <button class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none mb-2 mr-1">Approve & Review</button>
                <button class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none mb-2 mr-1">Convert Quote to Invoice</button>
            </div>
            <div class="flex justify-center mt-3">
                <x-base.button
                class="mb-2 mr-1 w-full"
                variant="outline-danger"
            >
                Save
            </x-base.button>
            </div>
            <div class="flex justify-between items-center mt-3">
                <span>Non-Vat Invoice</span>
                <x-base.form-switch.input
                id="nonVatInvoiceCheck"
                type="checkbox"
            />
            </div>
        </div>
    </div>
    <div class="intro-y col-span-11 2xl:col-span-9">
        <div class="intro-y box mt-5 overflow-hidden">
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
                            <span>Mr Limon Sarker</span>
                            <br>
                            <span>189 Launge avenue</span>
                            <br>
                            <span>Romford</span>
                            <br>
                            <span>Grater London</span>
                            <br>
                            <span>RM2</span>
                        </div>
                        <div class="mt-2 flex justify-end items-center gap-1">
                            <x-base.button
                            class="mb-2 mr-1"
                            variant="outline-danger"
                        >
                            Use alternative billing address
                        </x-base.button>
                        </div>
                    </div>
                    <div class="px-5 py-10 sm:px-8 sm:py-8 text-right">
                        <div class="text-3xl font-semibold text-primary">Quote</div>
                        <div class="mt-2">
                            <span class="font-bold text-xl">Test User</span>
                        </div>
                        <div class="mt-1">
                            <span>1 Example Street</span>
                            <br>
                            <span>Example Town</span>
                            <br>
                            <span>EX1 1EX</span>
                            <br>
                            <span>kldkfjoie@gmail.com</span>
                            <br>
                            <div>
                                <span class="font-bold">VAT:</span>
                                <span>123456789</span>
                            </div>
                        </div>
                        <div class="mt-2 flex justify-end items-center gap-1">
                            <span class="font-bold">Date Issued:</span>
                            <x-base.litepicker class="block w-32" data-single-mode="true" />
                        
                        </div>
                        <div class="mt-2 flex justify-end items-center gap-1">
                            <span class="font-bold">Job Ref No:</span>
                            <x-base.form-input class="block w-32" id="regular-form-1" type="text" />
                        </div>
                        <div class="mt-10 lg:ml-auto lg:mt-0 lg:text-right">
                            <div class="mt-2">
                                <span class="font-bold text-xl">Job Address</span>
                            </div>
                            <div class="mt-1">
                                <span>5 Talbot Road</span>
                                <br>
                                <span>Baurnemouth</span>
                                <br>
                                <span>Baurnemouth, Cristach and poole</span>
                                <br>
                                <span>BH9, 2JB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-5 py-2 sm:px-8 sm:py-8">
                <div class="overflow-x-auto">
                    <x-base.table class="invoiceItemsTable">
                        <x-base.table.thead>
                            <x-base.table.tr>
                                <x-base.table.th class="description whitespace-nowrap border-b-2 dark:border-darkmode-400">
                                    DESCRIPTION
                                </x-base.table.th>
                                <x-base.table.th class="units whitespace-nowrap border-b-2 text-right dark:border-darkmode-400">
                                    Units
                                </x-base.table.th>
                                <x-base.table.th class="price whitespace-nowrap border-b-2 text-right dark:border-darkmode-400">
                                    PRICE
                                </x-base.table.th>
                                <x-base.table.th class="vatField whitespace-nowrap border-b-2 text-right dark:border-darkmode-400">
                                    Vat %
                                </x-base.table.th>
                                <x-base.table.th class="lineTotal whitespace-nowrap border-b-2 text-right dark:border-darkmode-400">
                                    Line Total
                                </x-base.table.th>
                            </x-base.table.tr>
                        </x-base.table.thead>
                        <x-base.table.tbody >
                            <x-base.table.tr class="editInvoiceModal" data-id="1" >
                                <x-base.table.td class="description border-b dark:border-darkmode-400 flex gap-2">
                                    <div>
                                        <x-base.lucide
                                            class="mx-auto block"
                                            icon="grip-vertical"
                                        />
                                    </div>
                                    <div class="whitespace-nowrap font-medium">
                                        Gas Certificate
                                    </div>
                                </x-base.table.td>
                                <x-base.table.td class="units w-32 border-b text-right dark:border-darkmode-400">
                                    10
                                </x-base.table.td>
                                <x-base.table.td class="w-32 border-b text-right dark:border-darkmode-400">
                                    <span class="currency">$</span> <span class="price">25</span>
                                </x-base.table.td>
                                <x-base.table.td class="vatField w-32 border-b text-right font-medium dark:border-darkmode-400">
                                    <span class="currency">$</span> <span class="vat">5</span>
                                </x-base.table.td>
                                <x-base.table.td class="w-32 border-b text-right font-medium dark:border-darkmode-400">
                                    <span class="currency">$</span> <span class="lineTotal">250</span>
                                </x-base.table.td>
                            </x-base.table.tr>
                        </x-base.table.tbody>
                    </x-base.table>
                    <x-base.button class="mt-2 ml-5 mb-2 mr-1" variant="outline-danger" id="addInvoiceModalShow" > <x-base.lucide
                        class="mx-auto block"
                        
                        icon="Plus"
                    /> Add Item </x-base.button>
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
                        <span class="ml-2 currency">$</span> <span class="subtotal_price">250.00</span>
                    </div>
                    <div class="mt-2 font-medium text-md vatTotalField">
                        <span>Vat Total:</span>
                        <span class="ml-2 currency">$</span> <span class="vat_total_price">12.50</span>
                    </div>
                    <div class="mt-2 font-bold text-md">
                        <span>Total:</span>
                        <span class="ml-2 currency">$</span> <span class="total_price">262.50</span>
                    </div>
                    <hr>
                    <div class="mt-2 font-medium text-md">
                        <span>Due:</span>
                        <span class="ml-2 currency">$</span> <span class="due_price">262.50</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 @include('app.invoice.modals')
@endsection

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/print-invoice.js')
@endPushOnce