
<!-- BEGIN: Approve & Send Email Content -->
<x-base.dialog id="makePaymentModal">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="makePaymentForm">
            <x-base.dialog.title class="justify-between">
                <div>
                    <h2 class="mr-auto text-base font-medium">Make Payment</h2>
                    <span class="dueLeft">This invoice has {{ Number::currency($invoice->invoice_due, 'GBP') }} outstanding</span>
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
                        <x-base.form-input name="amount" max="{{ $invoice->invoice_due }}" id="amount" class="w-full" step="any" type="number" />
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
            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}"/>
            <input type="hidden" name="total_amount" value="{{ $invoice->invoice_total }}"/>
            <input type="hidden" name="due_amount" value="{{ $invoice->invoice_due }}"/>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Approve & Send Email Modal Content -->

<!-- BEGIN: Send Email Content -->
<x-base.dialog id="sendEmailModal" size="xl" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="sendEmailForm" enctype="multipart/form-data">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Send Email</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" onclick="location.reload();" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="p-5">
                <div class="mb-3">
                    <x-base.form-label for="cc_email_address">Customer Email</x-base.form-label>
                    @if(!isset($invoice->customer->contact->email) || empty($invoice->customer->contact->email))
                        <x-base.form-input value="{{ (isset($invoice->customer->contact->email) && !empty($invoice->customer->contact->email) ? $invoice->customer->contact->email : '') }}" name="customer_email" class="w-full" type="text" placeholder="example@exm.com" />
                    @else
                        <x-base.form-input readonly value="{{ (isset($invoice->customer->contact->email) && !empty($invoice->customer->contact->email) ? $invoice->customer->contact->email : '') }}" name="customer_email" class="w-full" type="text" placeholder="example@exm.com" />
                    @endif
                    <div class="acc__input-error error-customer_email text-danger text-xs mt-1"></div>
                </div>
                <div class="mb-3">
                    <x-base.form-label for="cc_email_address">Send a copy to all emails</x-base.form-label>
                    <x-base.form-input value="{{ (isset($invoice->email_template->cc_email_address) ? $invoice->email_template->cc_email_address : '') }}" name="cc_email_address" class="w-full" type="text" placeholder="example@exm.com,example@exm.com...." />
                    <small class="leading-non mt-1">Use comma (,) seperator for multiple emails. ie: example@exm.com,example@exm.com....</small>
                </div>
                <div class="mb-3">
                    <x-base.form-label for="subject">Subject</x-base.form-label>
                    <x-base.form-input id="subject" value="{{ (isset($invoice->email_template->subject) ? $invoice->email_template->subject : '') }}" name="subject" class="w-full" type="text" placeholder="{{ $form->name }} for job at [jobaddr1] [jobaddr2]" />
                    <div class="acc__input-error error-subject text-danger text-xs mt-1"></div>
                </div>
                <div class="flex justify-between mb-3">
                    <label for="editEditor" class="form-label">Description <span class="text-danger">*</span></label>
                    {{--@include('app.settings.reminders.tags')--}}
                </div>
                <div>
                    <div class="editor" id="theEditor">{!! (isset($invoice->email_template->content) ? $invoice->email_template->content : '') !!}</div>
                    <div class="acc__input-error error-content text-danger text-xs mt-1"></div>
                </div>
                
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" onclick="location.reload();" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="sendEmailBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Send
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}"/>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Send Email Modal Content -->