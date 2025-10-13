<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
    <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
        <h2 class="mr-auto text-base font-medium">
            Items
        </h2>
    </div>
    <div class="allItemsWrap mb-2" style="display: none;"></div>
    <a href="javascript:void(0);" class="addItemBtn text-primary flex justify-between items-center mb-2 p-3 bg-white text-base">Add Item<x-base.lucide class="ml-auto h-4 w-4" icon="plus-circle" /></a>
    
    <div class="allDiscountItemWrap mb-2" style="display: none;"></div>
    <a href="javascript:void(0);" class="addDiscountBtn text-primary flex justify-between items-center p-3 bg-white text-base">Add Discount<x-base.lucide class="ml-auto h-4 w-4" icon="plus-circle" /></a>
</div>

<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 py-2">
    <div class="px-2 py-3 calculationWrap bg-white">
        <div class="lineTotalBlock border-b border-slate-300 mb-3" style="display: none;">
            <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase subTotalBlock">
                <div class="text-slate-500 heLabel">Sub Total</div>
                <div class="ml-auto w-[80px] text-slate-700 text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
            </div>
            <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase discountBlock" style="display: none;">
                <div class="text-slate-500 heLabel">Discount</div>
                <div class="ml-auto w-[80px] text-danger text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
            </div>
            <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase vatTotalBlock" style="display: none;">
                <div class="text-slate-500 heLabel">Vat Total</div>
                <div class="ml-auto w-[80px] text-slate-700 text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
            </div>
        </div>
        <div class="calculationBlock">
            <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase quoteTotalBlock">
                <div class="text-slate-500 heLabel">Total</div>
                <div class="ml-auto w-[80px] text-slate-700 text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
            </div>
            <div class="flex justify-between font-medium  text-xs leading-none uppercase quoteBalanceBlock">
                <div class="text-slate-800 heLabel">Balance Due</div>
                <div class="ml-auto w-[80px] text-[#000000] text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 py-2">
    <div class="px-2 py-3 bg-white">
        <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
            <div class="w-full">
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Quote Date</div>
                <div class="theDesc w-full relative">
                    <x-base.litepicker id="issued_date" name="issued_date" value="{{ date('d-m-Y') }}" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off"/>
                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 py-2">
    <div class="px-2 py-3 quoteNoteWrap bg-white">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer quoteNoteBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Notes for Customer</div>
                <div class="theDesc">N/A</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="pencil" /></span>
        </a>
    </div>
</div>