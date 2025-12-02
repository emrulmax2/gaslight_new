<!-- BEGIN: Cancell Reason Modal Content -->
<x-base.dialog id="cancelReasonModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="cancelReasonForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Cancel Invoice</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="px-5 py-2 bg-slate-100">
                <div>
                    <x-base.form-label>Reason <span class="text-danger">*</span></x-base.form-label>
                    <div class="bg-white">
                        @if($reasons->count() > 0)
                            @foreach($reasons as $rsn)
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="cancel_reason_{{ $rsn->id }}">{{ $rsn->name }}</x-base.form-check.label>
                                    <x-base.form-check.input id="cancel_reason_{{ $rsn->id }}" name="invoice_cancel_reason_id" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="{{ $rsn->id }}"/>
                                </x-base.form-check>
                            @endforeach
                        @endif
                    </div>
                    <div class="acc__input-error error-invoice_cancel_reason_id text-danger text-xs" style="display: none;"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label>Note </x-base.form-label>
                    <x-base.form-textarea rows="4" name="cancel_reason_note" id="cancel_reason_note" class="w-full"></x-base.form-textarea>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateRsnBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Submit
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="invoice_id" id="invoice_id" value="0" />
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END:Cancel Reason Modal Content -->

<!-- BEGIN: Job Completed Confirm Modal Content -->
<x-base.dialog id="invoiceConfirmModal" class="max-w-full" staticBackdrop>
    <x-base.dialog.panel>
        <form method="post" action="#" id="invoiceConfirmForm">
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="AlertOctagon" />
                <div class="mt-5 text-3xl">Attention Required</div>
                <div class="mt-2 text-slate-500 confirmModalDesc">
                    The invoice has a due amount. Do you really want to mark this a paid invoice?
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <x-base.button class="actionButtons mr-1 w-24" data-tw-dismiss="modal" type="button" variant="outline-secondary">Cancel</x-base.button>
                <x-base.button class="actionButtons w-auto text-white" id="makePayment" type="button" variant="success" >
                    Make a Payment
                </x-base.button>
                <x-base.button class="actionButtons w-auto" id="markAsPaidBtn" type="button" variant="danger" >
                    Mark as Paid
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="invoice_id" value="0"/>
            </div>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Job Completed Confirm Modal Content -->

<!-- BEGIN: Approve & Send Email Content -->
<x-base.dialog id="makePaymentModal">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="makePaymentForm">
            <x-base.dialog.title class="justify-between">
                <div>
                    <h2 class="mr-auto text-base font-medium">Make Payment</h2>
                    <span class="dueLeft">This invoice has <span class="totalDue"></span> outstanding</span>
                </div>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="p-5">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Payment Date<span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.litepicker value="{{ date('Y-m-d') }}" name="payment_date" id="payment_date" class="w-full" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                        <div class="acc__input-error error-payment_date text-danger text-xs mt-1" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Payment Method<span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.form-select class="w-full" name="payment_method_id" id="payment_method_id">
                            <option value="">Please Select</option>
                            @if($payment_methods->count() > 0)
                                @foreach($payment_methods as $pmt)
                                    <option value="{{ $pmt->id }}">{{ $pmt->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                        <div class="acc__input-error error-payment_method_id text-danger text-xs mt-1" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 vatWrap">
                        <x-base.form-label>Amount<span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.form-input name="amount" id="amount" class="w-full" step="any" type="number" />
                        <div class="acc__input-error error-amount text-danger text-xs mt-1" style="display: none;"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="payBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Pay
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
            <input type="hidden" name="invoice_id" value="0"/>
            <input type="hidden" name="total_amount" value="0"/>
            <input type="hidden" name="due_amount" value="0"/>
            <input type="hidden" name="paid" value="1"/>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Approve & Send Email Modal Content -->

<!-- BEGIN: Make Refund Content -->
<x-base.dialog id="makeRefundModal">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="makeRefundForm">
            <x-base.dialog.title class="justify-between">
                <div>
                    <h2 class="mr-auto text-base font-medium">Make Refund</h2>
                    <span class="dueLeft">This invoice has <span class="totalDue"></span> paid.</span>
                </div>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="p-5">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Refund Date<span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.litepicker value="{{ date('Y-m-d') }}" name="payment_date" id="payment_date" class="w-full" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                        <div class="acc__input-error error-payment_date text-danger text-xs mt-1" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Payment Method<span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.form-select class="w-full" name="payment_method_id" id="payment_method_id">
                            <option value="">Please Select</option>
                            @if($payment_methods->count() > 0)
                                @foreach($payment_methods as $pmt)
                                    <option value="{{ $pmt->id }}">{{ $pmt->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                        <div class="acc__input-error error-payment_method_id text-danger text-xs mt-1" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 vatWrap">
                        <x-base.form-label>Amount<span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.form-input name="amount" id="amount" class="w-full" step="any" type="number" />
                        <div class="acc__input-error error-amount text-danger text-xs mt-1" style="display: none;"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="refundBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Pay Back
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
            <input type="hidden" name="invoice_id" value="0"/>
            <input type="hidden" name="total_amount" value="0"/>
            <input type="hidden" name="paid_amount" value="0"/>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Approve & Send Email Modal Content -->