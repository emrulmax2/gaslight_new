import INTAddressLookUps from '../../address_lookup.js';

(function(){
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

    let tncTomOptions = {
        plugins: {
            dropdown_input: {}
        },
        placeholder: 'Search Here...',
        create: false,
        allowEmptyOption: true,
        onDelete: function (values) {
            return confirm( values.length > 1 ? "Are you sure you want to remove these " + values.length + " items?" : 'Are you sure you want to remove "' +values[0] +'"?' );
        },
    };
    let payment_method_id = new TomSelect(document.getElementById('payment_method_id'), tncTomOptions);

    
    let dateOption = {
        autoApply: true,
        singleMode: true,
        numberOfColumns: 1,
        numberOfMonths: 1,
        showWeekNumbers: false,
        minDate: new Date() - 1,
        inlineMode: false,
        format: "DD-MM-YYYY",
        dropdowns: {
            minYear: 1900,
            maxYear: 2050,
            months: true,
            years: true,
        },
    };
    const issuedDate = new Litepicker({
        element: document.getElementById('issued_date'),
        ...dateOption
    });

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const invoiceItemModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#invoiceItemModal"));
    const invoiceDiscountModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#invoiceDiscountModal"));
    const invoiceAdvanceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#invoiceAdvanceModal"));
    const invoiceNoteModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#invoiceNoteModal"));
    
    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
    });

    $('#successModal .agreeWith').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        if($theBtn.attr('data-action') == 'RELOAD'){
            if($theBtn.attr('data-redirect') != ''){
                window.location.href = $theBtn.attr('data-redirect');
            }else{
                window.location.reload();
            }
        }else{
            successModal.hide();
        }
    });
    
    /* Init The Calculation */
    let isVatInvoice = ($('#invoiceForm #non_vat_invoice').val() == 1 ? false : true);
    invoiceCalculation();
    /* Init The Calculation */

    /* Generate Or Autoload Invoice Number Start */
    if(localStorage.invoice_number && localStorage.getItem('invoice_number') != ''){
        let invoiceNumber = JSON.parse(localStorage.getItem('invoice_number'));
        $('.invoiceNoBlockWrap').fadeIn('fast', function(){
            $('.invoiceNoBlock').find('.theDesc').html(invoiceNumber).addClass('font-medium');
        });
    }else{
        $('.invoiceNoBlockWrap').fadeOut('fast', function(){
            $('.invoiceNoBlock').find('.theDesc').html('').removeClass('font-medium');
        });
    }
    /* Generate Or Autoload Invoice Number End */

    /* Invoice Item Manager Start */
    document.getElementById('invoiceItemModal').addEventListener('hide.tw.modal', function(event) {
        $('#invoiceItemModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#invoiceItemModal textarea').val('');
        $('#invoiceItemModal input[name="inv_item_serial"]').val('1');
        $('#invoiceItemModal input[name="edit"]').val('0');
        $('#invoiceItemModal #removeItemBtn').fadeOut();

        if(isVatInvoice){
            $('#invoiceItemModal .vatWrap').fadeIn('fast', function(){
                $('input', this).val(20);
            })
        }else{
            $('#invoiceItemModal .vatWrap').fadeOut('fast', function(){
                $('input', this).val(0);
            })
        }
    });

    if(localStorage.invoiceItems){
        let invoiceItemsCount = localStorage.getItem('invoiceItemsCount') * 1;
        let invoiceItems = localStorage.getItem('invoiceItems');
        let invoiceItemsObj = JSON.parse(invoiceItems);

        if(Object.keys(invoiceItemsObj).length > 0){
            for (const [inv_item_serial, invItem] of Object.entries(invoiceItemsObj)) {
                
                let units = invItem.units * 1;
                let unit_price = invItem.price * 1;
                let vat_rate = invItem.vat * 1;
                let line_total = invItem.line_total * 1;
                let invoiceItemBlock = '';
                invoiceItemBlock += '<div class="px-2 py-4 invoiceItemWrap_'+inv_item_serial+' bg-white" style="margin-top: 2px">';
                    invoiceItemBlock += '<a data-key="'+inv_item_serial+'" href="javascript:void(0);" class="editInvoiceItemBtn flex justify-between items-center cursor-pointer invoiceItemBlock_'+inv_item_serial+'">';
                        invoiceItemBlock += '<div class="theDesc font-medium">'+invItem.inv_item_title+'</div>';
                        invoiceItemBlock += '<div style="flex: 0 0 150px; margin-left: auto;" class="font-medium inline-flex justify-end items-center">';
                            invoiceItemBlock += '<span>'+units+' x £'+unit_price.toFixed(2)+'</span>';
                            invoiceItemBlock += '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil stroke-1.5 h-3 w-3 text-success"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg></span>';
                        invoiceItemBlock += '</div>';
                    invoiceItemBlock += '</a>';
                invoiceItemBlock += '</div>';

                $('.allItemsWrap').fadeIn('fast', function(){
                    $('.allItemsWrap').append(invoiceItemBlock);
                });
            }
        }else{
            $('.allItemsWrap').fadeOut('fast', function(){
                $('.allItemsWrap').html('');
            });
        }
    }

    $('.addItemBtn').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let serial = 1;
        if(localStorage.invoiceItems){
            let invoiceItems = localStorage.getItem('invoiceItems');
            let invoiceItemsObj = JSON.parse(invoiceItems);
            let serials = Object.keys(invoiceItemsObj);
                serials.sort(function(a, b){return a - b})
            serial = (serials[serials.length - 1] * 1) + 1;
        }
        
        localStorage.setItem('invoiceItemsCount', serial);
        
        invoiceItemModal.show();
        document.getElementById("invoiceItemModal").addEventListener("shown.tw.modal", function (event) {
            $('#invoiceItemModal input[name="inv_item_serial"]').val(serial);
            $('#invoiceItemModal input[name="edit"]').val(0);
            $('#invoiceItemModal #removeItemBtn').fadeOut();

            if(isVatInvoice){
                $('#invoiceItemModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(20);
                })
            }else{
                $('#invoiceItemModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                })
            }
        });
    });

    $(document).on('click', '.editInvoiceItemBtn', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);
        let theSerial = $theModalBtn.attr('data-key');

        let invoiceItems = localStorage.getItem('invoiceItems');
        let invoiceItemsObj = JSON.parse(invoiceItems);
        let theItem = invoiceItemsObj[theSerial];

        invoiceItemModal.show();
        document.getElementById("invoiceItemModal").addEventListener("shown.tw.modal", function (event) {
            $('#invoiceItemModal input[name="inv_item_serial"]').val(theSerial);
            $('#invoiceItemModal input[name="edit"]').val(theSerial);
            $('#invoiceItemModal #removeItemBtn').fadeIn();

            if(isVatInvoice){
                $('#invoiceItemModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(20);
                })
            }else{
                $('#invoiceItemModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                })
            }
        
            for (const [key, value] of Object.entries(theItem)) {
                let $theInput = $('#invoiceItemModal [name="'+key+'"]');
                if($theInput.is('textarea')){
                    $theInput.val(value ? value : '');
                }else{
                    if($theInput.attr('type') == 'radio'){
                        $('#invoiceItemModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                    }else{
                        if(key != 'inv_item_serial' && key != 'edit'){
                            $theInput.val(value ? value : '');
                        }
                    }
                }
            }
        });
    });

    $('#invoiceItemForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('invoiceItemForm');
        const $theForm = $(this);

        $theForm.find('.acc__input-error').fadeOut().html('');
        $('#saveItemBtn', $theForm).attr('disabled', 'disabled');
        $("#saveItemBtn .theLoader").fadeIn();

        let errors = 0;
        if($theForm.find('[name="description"]').val() == 0){
            errors += 1;
            $theForm.find('.error-description').fadeIn().html('This field is requeired.');
        }
        if($theForm.find('[name="units"]').val() == 0){
            errors += 1;
            $theForm.find('.error-units').fadeIn().html('This field is requeired.');
        }
        if($theForm.find('[name="price"]').val() == 0){
            errors += 1;
            $theForm.find('.error-price').fadeIn().html('This field is requeired.');
        }

        if(errors > 0){
            $('#saveItemBtn', $theForm).removeAttr('disabled');
            $("#saveItemBtn .theLoader").fadeOut();
        }else{
            let inv_item_serial = $theForm.find('[name="inv_item_serial"]').val() * 1;
            let edit = $theForm.find('[name="edit"]').val();

            let form_data = $theForm.serializeArray();
            let formated_data = getFormatedData(form_data);

            let unit = $theForm.find('[name="units"]').val() * 1;
            let price = $theForm.find('[name="price"]').val() * 1;
            let vat = ($theForm.find('[name="vat"]').val() != '' && $theForm.find('[name="vat"]').val() > 0 ? $theForm.find('[name="vat"]').val() * 1 : 0);
            let unit_total = price * unit;
            let vat_total = (unit_total * vat) / 100;
            let line_total = unit_total + vat_total;

            let inv_item_title = ($theForm.find('[name="description"]').val() != '' ? $theForm.find('[name="description"]').val() : inv_item_serial+' Line Item');
            formated_data['inv_item_title'] = inv_item_title;
            formated_data['vat'] = vat;
            formated_data['line_total'] = line_total;
            
            if(edit > 0){
                let invoiceItems = localStorage.getItem('invoiceItems');
                let invoiceItemsObj = JSON.parse(invoiceItems);
                invoiceItemsObj[inv_item_serial] = formated_data;

                localStorage.setItem('invoiceItems', JSON.stringify(invoiceItemsObj));

                let invoiceItemBlock = ''
                    invoiceItemBlock += '<a data-key="'+inv_item_serial+'" href="javascript:void(0);" class="editInvoiceItemBtn flex justify-between items-center cursor-pointer invoiceItemBlock_'+inv_item_serial+'">';
                        invoiceItemBlock += '<div class="theDesc font-medium">'+inv_item_title+'</div>';
                        invoiceItemBlock += '<div style="flex: 0 0 150px; margin-left: auto;" class="font-medium inline-flex justify-end items-center">';
                            invoiceItemBlock += '<span>'+unit+' x £'+price.toFixed(2)+'</span>';
                            invoiceItemBlock += '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil stroke-1.5 h-3 w-3 text-success"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg></span>';
                        invoiceItemBlock += '</div>';
                    invoiceItemBlock += '</a>';
                $('.allItemsWrap').find('.invoiceItemWrap_'+inv_item_serial).html(invoiceItemBlock);
            }else{
                if(localStorage.invoiceItems){
                    let invoiceItems = localStorage.getItem('invoiceItems');
                    let invoiceItemsObj = JSON.parse(invoiceItems);
                    invoiceItemsObj[inv_item_serial] = formated_data

                    localStorage.setItem('invoiceItems', JSON.stringify(invoiceItemsObj));
                    localStorage.setItem('invoiceItemsCount', inv_item_serial);
                }else{
                    let invoiceItemsObj = {};
                    invoiceItemsObj[inv_item_serial] = formated_data;

                    localStorage.setItem('invoiceItems', JSON.stringify(invoiceItemsObj));
                    localStorage.setItem('invoiceItemsCount', inv_item_serial);
                }
                let invoiceItemBlock = '';
                    invoiceItemBlock += '<div class="px-2 py-4 invoiceItemWrap_'+inv_item_serial+' bg-white" style="margin-top: 2px">';
                        invoiceItemBlock += '<a data-key="'+inv_item_serial+'" href="javascript:void(0);" class="editInvoiceItemBtn flex justify-between items-center cursor-pointer invoiceItemBlock_'+inv_item_serial+'">';
                            invoiceItemBlock += '<div class="theDesc font-medium">'+inv_item_title+'</div>';
                            invoiceItemBlock += '<div style="flex: 0 0 150px; margin-left: auto;" class="font-medium inline-flex justify-end items-center">';
                                invoiceItemBlock += '<span>'+unit+' x £'+price.toFixed(2)+'</span>';
                                invoiceItemBlock += '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil stroke-1.5 h-3 w-3 text-success"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg></span>';
                            invoiceItemBlock += '</div>';
                        invoiceItemBlock += '</a>';
                    invoiceItemBlock += '</div>';
                $('.allItemsWrap').fadeIn('fast', function(){
                    $('.allItemsWrap').append(invoiceItemBlock);
                });
            }

            let invoiceItemsCount = localStorage.getItem('invoiceItemsCount');
            
            $theForm.find('.acc__input-error').fadeOut().html('');
            $('#saveItemBtn', $theForm).removeAttr('disabled');
            $("#saveItemBtn .theLoader").fadeOut();
            invoiceCalculation();
            invoiceItemModal.hide();
        }
    });

    $('#removeItemBtn').on('click', function(e){
        e.preventDefault();
        let theSerial = $('#invoiceItemModal input[name="inv_item_serial"]').val();

        if(theSerial > 0){
            let invoiceItemsCount = localStorage.getItem('invoiceItemsCount') * 1;
            let invoiceItems = localStorage.getItem('invoiceItems');
            let invoiceItemsObj = JSON.parse(invoiceItems);
            delete invoiceItemsObj[theSerial];

            invoiceItemsCount -= 1;
            localStorage.setItem('invoiceItemsCount', invoiceItemsCount);
            localStorage.setItem('invoiceItems', JSON.stringify(invoiceItemsObj));

            $('.allItemsWrap').find('.invoiceItemWrap_'+theSerial).remove();
            invoiceCalculation();
            invoiceItemModal.hide();
        }
    })
    /* Invoice Item Manager End */


    /* Invoice Discount Start */
    if(localStorage.invoiceDiscounts){
        let invoiceDiscounts = localStorage.getItem('invoiceDiscounts');
        let invoiceDiscountsObj = JSON.parse(invoiceDiscounts);
        let discountAmount = invoiceDiscountsObj.amount * 1;
        
        let discountItemBlock = '';
            discountItemBlock += '<div class="px-2 py-4 discountItemWrap bg-white">';
                discountItemBlock += '<a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer discountItemBlock">';
                    discountItemBlock += '<div class="theDesc font-medium">Discount</div>';
                    discountItemBlock += '<div style="flex: 0 0 150px; margin-left: auto;" class="font-medium inline-flex justify-end items-center">';
                        discountItemBlock += '<span>£'+discountAmount.toFixed(2)+'</span>';
                        discountItemBlock += '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil stroke-1.5 h-3 w-3 text-success"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg></span>';
                    discountItemBlock += '</div>';
                discountItemBlock += '</a>';
            discountItemBlock += '</div>';

        $('.allDiscountItemWrap').fadeIn('fast').html(discountItemBlock);
        $('.addDiscountBtn').fadeOut('');
    }
    
    document.getElementById('invoiceDiscountModal').addEventListener('hide.tw.modal', function(event) {
        $('#invoiceDiscountModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#invoiceDiscountModal #removeDiscountBtn').fadeOut();

        if(isVatInvoice){
            $('#invoiceDiscountModal .vatWrap').fadeIn('fast', function(){
                $('input', this).val(20);
            })
        }else{
            $('#invoiceDiscountModal .vatWrap').fadeOut('fast', function(){
                $('input', this).val(0);
            })
        }
    });

    $(document).on('click', '.discountItemBlock', function(e){
        e.preventDefault();
        let invoiceDiscounts = localStorage.getItem('invoiceDiscounts');

        let totalDue = getDueAmountForDiscount();
        let theLabel = 'This invoice has £'+totalDue.toFixed(e)+' outstanding.'
        invoiceDiscountModal.show();
        document.getElementById("invoiceDiscountModal").addEventListener("shown.tw.modal", function (event) {
            $('#invoiceDiscountModal .dueLeft').html(theLabel);
            $('#invoiceDiscountModal input[name="max_discount"]').val(totalDue);
            if(isVatInvoice){
                $('#invoiceDiscountModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(20);
                })
            }else{
                $('#invoiceDiscountModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                })
            }
            if(localStorage.invoiceDiscounts){
                $('#invoiceDiscountModal #removeDiscountBtn').fadeIn();
                let invoiceDiscountsObj = JSON.parse(invoiceDiscounts);
                if(!$.isEmptyObject(invoiceDiscountsObj)){
                    for (const [key, value] of Object.entries(invoiceDiscountsObj)) {
                        let $theInput = $('#invoiceDiscountModal [name="'+key+'"]');
                        if($theInput.is('textarea')){
                            $theInput.val(value ? value : '');
                        }else{
                            if($theInput.attr('type') == 'radio'){
                                $('#invoiceDiscountModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                            }else{
                                if(key != 'max_discount'){
                                    $theInput.val(value ? value : '');
                                }
                            }
                        }
                    }
                }
            }else{
                $('#invoiceDiscountModal #removeDiscountBtn').fadeOut();
            }
        });
    })

    $('.addDiscountBtn').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let totalDue = getDueAmountForDiscount();
        if(totalDue > 0){
            let theLabel = 'This invoice has £'+totalDue.toFixed(e)+' outstanding.'
            invoiceDiscountModal.show();
            document.getElementById("invoiceDiscountModal").addEventListener("shown.tw.modal", function (event) {
                $('#invoiceDiscountModal .dueLeft').html(theLabel);
                $('#invoiceDiscountModal input[name="amount"]').val(totalDue);
                $('#invoiceDiscountModal input[name="max_discount"]').val(totalDue);
                if(isVatInvoice){
                    $('#invoiceDiscountModal .vatWrap').fadeIn('fast', function(){
                        $('input', this).val(20);
                    })
                }else{
                    $('#invoiceDiscountModal .vatWrap').fadeOut('fast', function(){
                        $('input', this).val(0);
                    })
                }
            });
        }else{
            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Oops!");
                $("#warningModal .warningModalDesc").html('This invoice does not have any due.');
            });

            setTimeout(() => {
                warningModal.hide();
            }, 1500);
        }
        $('#invoiceDiscountModal #removeDiscountBtn').fadeOut();
    });

    $('#invoiceDiscountForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('invoiceDiscountForm');
        const $theForm = $(this);

        $('#saveDiscountBtn', $theForm).attr('disabled', 'disabled');
        $("#saveDiscountBtn .theLoader").fadeIn();
        $theForm.find('.acc__input-error').html('').fadeOut();

        let errors = 0;
        if($theForm.find('[name="amount"]').val() == 0){
            errors += 1;
            $theForm.find('.error-amount').fadeIn().html('This field is requeired.');
        } else if(($('[name="amount"]', $theForm).val() * 1) > ($('[name="max_discount"]', $theForm).val() * 1)){
            errors += 1;
            $theForm.find('.error-amount').fadeIn().html('Discount amount can nto empty or grater than the due amount');
        }

        if(errors > 0){
            $('#saveDiscountBtn', $theForm).removeAttr('disabled');
            $("#saveDiscountBtn .theLoader").fadeOut();
        }else{
            let form_data = $theForm.serializeArray();
            let formated_data = getFormatedData(form_data);

            let amount = $theForm.find('[name="amount"]').val() * 1;
            let vat = ($theForm.find('[name="vat"]').val() != '' && $theForm.find('[name="vat"]').val() > 0 ? $theForm.find('[name="vat"]').val() * 1 : 0);
            formated_data['inv_item_title'] = 'Discount';
            formated_data['vat'] = vat;

            let discountItemBlock = '';
                discountItemBlock += '<div class="px-2 py-4 discountItemWrap bg-white">';
                    discountItemBlock += '<a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer discountItemBlock">';
                        discountItemBlock += '<div class="theDesc font-medium">Discount</div>';
                        discountItemBlock += '<div style="flex: 0 0 150px; margin-left: auto;" class="font-medium inline-flex justify-end items-center">';
                            discountItemBlock += '<span>£'+amount.toFixed(2)+'</span>';
                            discountItemBlock += '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil stroke-1.5 h-3 w-3 text-success"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg></span>';
                        discountItemBlock += '</div>';
                    discountItemBlock += '</a>';
                discountItemBlock += '</div>';

            $('.allDiscountItemWrap').fadeIn('fast').html(discountItemBlock);
            
            localStorage.setItem('invoiceDiscounts', JSON.stringify(formated_data));
            $('.addDiscountBtn').fadeOut('');

            
            $('#saveDiscountBtn', $theForm).removeAttr('disabled');
            $("#saveDiscountBtn .theLoader").fadeOut();
            $theForm.find('.acc__input-error').html('').fadeOut();
            invoiceCalculation();
            invoiceDiscountModal.hide();
        }
    }); 

    $('#removeDiscountBtn').on('click', function(e){
        e.preventDefault();
        
        localStorage.removeItem('invoiceDiscounts');
        $('.allDiscountItemWrap').fadeOut('fast').html('');
        $('.addDiscountBtn').fadeIn('fast');

        invoiceCalculation();
        invoiceDiscountModal.hide();
    })
    /* Invoice Discount END */

    /* Invoice Advance Start */
    if(localStorage.invoiceAdvance){
        let invoiceAdvance = localStorage.getItem('invoiceAdvance');
        let invoiceAdvanceObj = JSON.parse(invoiceAdvance);

        let advance_amount = (invoiceAdvanceObj.advance_amount ? invoiceAdvanceObj.advance_amount * 1 : 0);
        $('.advanceBlock .theDesc').html('£'+advance_amount.toFixed(2));
    }

    document.getElementById('invoiceAdvanceModal').addEventListener('hide.tw.modal', function(event) {
        $('#invoiceAdvanceModal input').val('');
        $('#invoiceAdvanceModal [name="max_advance"]').val(0);
        $('#invoiceAdvanceModal .dueLeft').html('This invoice has £0 outstanding.');
        $('#invoiceAdvanceModal #removeAdvanceBtn').fadeOut();

        payment_method_id.clear(true);
    });

    $('.advanceBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);
        let totalDue = getDueAmountForAdvance();

        if(totalDue > 0){
            let theLabel = 'This invoice has £'+totalDue.toFixed(2)+' outstanding.'
            invoiceAdvanceModal.show();
            document.getElementById("invoiceAdvanceModal").addEventListener("shown.tw.modal", function (event) {
                $('#invoiceAdvanceModal .dueLeft').html(theLabel);
                $('#invoiceAdvanceModal [name="max_advance"]').val(totalDue.toFixed(2));
                if(localStorage.invoiceAdvance){
                    let invoiceAdvance = localStorage.getItem('invoiceAdvance');
                    let invoiceAdvanceObj = JSON.parse(invoiceAdvance);
                    console.log(invoiceAdvanceObj);
                    if(!$.isEmptyObject(invoiceAdvanceObj)){
                        for (const [key, value] of Object.entries(invoiceAdvanceObj)) {
                            if(key == 'payment_method_id'){
                                payment_method_id.addItem(value);
                            }else{
                                let $theInput = $('#invoiceAdvanceModal [name="'+key+'"]');
                                if(key != 'max_advance'){
                                    $theInput.val(value ? value : '');
                                }
                            }
                        }
                    }
                    $('#invoiceAdvanceModal #removeAdvanceBtn').fadeIn();
                }else{
                    $('#invoiceAdvanceModal [name="advance_amount"]').val(totalDue.toFixed(2));
                    $('#invoiceAdvanceModal #removeAdvanceBtn').fadeOut();
                }
            });
        }else{
            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Oops!");
                $("#warningModal .warningModalDesc").html('This invoice does not have any due.');
            });

            setTimeout(() => {
                warningModal.hide();
            }, 1500);
        }
    });

    $('#invoiceAdvanceForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('invoiceAdvanceForm');
        const $theForm = $(this);

        $('#addAdvancePayBtn', $theForm).attr('disabled', 'disabled');
        $("#addAdvancePayBtn .theLoader").fadeIn();
        $theForm.find('.acc__input-error').html('').fadeOut();

        let errors = 0;
        if($theForm.find('[name="advance_amount"]').val() == 0){
            errors += 1;
            $theForm.find('.error-advance_amount').fadeIn().html('This field is requeired.');
        } else if(($('[name="advance_amount"]', $theForm).val() * 1) > ($('[name="max_advance"]', $theForm).val() * 1)){
            errors += 1;
            $theForm.find('.error-advance_amount').fadeIn().html('Pre-Payment amount can nto empty or grater than the due amount');
        }

        if(errors > 0){
            $('#addAdvancePayBtn', $theForm).removeAttr('disabled');
            $("#addAdvancePayBtn .theLoader").fadeOut();
        }else{
            let advance_amount = $theForm.find('[name="advance_amount"]').val() * 1;
            let form_data = $theForm.serializeArray();
            let formated_data = getFormatedData(form_data);

            localStorage.removeItem('invoiceAdvance');
            localStorage.setItem('invoiceAdvance', JSON.stringify(formated_data));

            $('.advanceBlock .theDesc').html('£'+advance_amount.toFixed(2));

            $('#addAdvancePayBtn', $theForm).removeAttr('disabled');
            $("#addAdvancePayBtn .theLoader").fadeOut();
            $theForm.find('.acc__input-error').html('').fadeOut();
            
            invoiceCalculation();
            invoiceAdvanceModal.hide();
        }
    }); 

    $('#removeAdvanceBtn').on('click', function(e){
        e.preventDefault();
        
        localStorage.removeItem('invoiceAdvance');
        $('.advanceBlock .theDesc').html('£0.0');

        invoiceCalculation();
        invoiceAdvanceModal.hide();
    })
    /* Invoice Advance End */

    /* Issued Date Load Start */
    if(localStorage.issued_date){
        let issued_date = localStorage.getItem('issued_date');
        if(issued_date != ''){
            $('#issued_date').val(JSON.parse(issued_date));
            issuedDate.setDate(JSON.parse(issued_date)); 
        }else{
            $('#issued_date').val(getTodayDate());
            issuedDate.setDate(getTodayDate()); 
        }
    }
    issuedDate.on('selected', (date) => {
        localStorage.removeItem('invoiceNotes');
        if(date){
            let theDate = date.dateInstance.toLocaleDateString('en-GB').replace(/\//g, "-");
            localStorage.setItem('issued_date', JSON.stringify(theDate));
        }
    });
    /* Issued Date Load Start */

    /* Note Auto Load Start */
    document.getElementById('invoiceNoteModal').addEventListener('hide.tw.modal', function(event) {
        $('#invoiceNoteModal textarea').val('');
    });
    if(localStorage.invoiceNotes){
        let invoiceNotes = localStorage.getItem('invoiceNotes');
        $('.invoiceNoteBlock .theDesc').html((invoiceNotes != '' ? JSON.parse(invoiceNotes) : 'N/A'));
    }
    $('.invoiceNoteBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        invoiceNoteModal.show();
        document.getElementById("invoiceNoteModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.invoiceNotes){
                let invoiceNotes = localStorage.getItem('invoiceNotes');
                $('#invoiceNoteModal [name="note"]').val(JSON.parse(invoiceNotes));
            }else{
                $('#invoiceNoteModal [name="note"]').val('');
            }   
        });
    });

    $('#invoiceNoteForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('invoiceNoteForm');
        const $theForm = $(this);

        $('#saveNoteBtn', $theForm).attr('disabled', 'disabled');
        $("#saveNoteBtn .theLoader").fadeIn();

        let note = $theForm.find('[name="note"]').val();

        localStorage.removeItem('invoiceNotes');
        if(note != ''){
            localStorage.setItem('invoiceNotes', JSON.stringify(note));
        }
        $('.invoiceNoteBlock .theDesc').html((note != '' ? note : 'N/A'));


        $('#saveNoteBtn', $theForm).removeAttr('disabled');
        $("#saveNoteBtn .theLoader").fadeOut();
        invoiceNoteModal.hide();
    });
    /* Note Auto Load End */

    /* Extras Auto Load Start */
    if(localStorage.invoiceExtra && localStorage.invoiceExtra != null){
        let invoiceExtra = JSON.parse(localStorage.getItem('invoiceExtra'));
        if(invoiceExtra.vat_number != ''){
            $('#vat_number').val(invoiceExtra.vat_number);
        }
        if(invoiceExtra.non_vat_quote != ''){
            $('#non_vat_quote').val(invoiceExtra.non_vat_quote);
        }
    }
    /* Extras Auto Load End */

    function invoiceCalculation(){
        let invoiceItems = getInvoiceItemTotal();
        let invoiceDiscounts = getInvoiceDiscountTotal();
        let invoiceAdvances = getInvoiceAdvanceTotal();

        let invoiteItemTotal = invoiceItems.invoiteItemTotal;
        let invoiceItemVatTotal = invoiceItems.invoiceItemVatTotal;

        let hasDiscount = invoiceDiscounts.hasDiscount;
        let discountTotal = invoiceDiscounts.discountTotal;
        let discountVatTotal = invoiceDiscounts.discountVatTotal;

        let hasAdvance = invoiceAdvances.hasAdvance;
        let advanceTotal = invoiceAdvances.advanceTotal;

        let vatAmount = invoiceItemVatTotal - (hasDiscount ? discountVatTotal : 0);

        let total = invoiteItemTotal - (hasDiscount ? discountTotal : 0) + (isVatInvoice ? vatAmount : 0);
        let totalBalance = total - (hasAdvance ? advanceTotal : 0);
        
        $('.lineTotalBlock').fadeIn('fast', function(){
            $('.subTotalBlock .theDesc').html('£'+invoiteItemTotal.toFixed(2));
            if(hasDiscount){
                $('.discountBlock').fadeIn('fast', function(){
                    $('.theDesc', this).html('-£'+discountTotal.toFixed(2))
                });
            }else{
                $('.discountBlock').fadeOut('fast', function(){
                    $('.theDesc', this).html('£0.00')
                });
            }
            if(isVatInvoice){
                $('.vatTotalBlock').fadeIn('fast', function(){
                    $('.theDesc', this).html('£'+vatAmount.toFixed(2))
                });
            }else{
                $('.vatTotalBlock').fadeOut('fast', function(){
                    $('.theDesc', this).html('£0.0');
                });
            }
        });

        if(hasAdvance){
            $('.invoiceAdvanceBlock').fadeIn('fast', function(){
                $('.theDesc', this).html('-£'+advanceTotal.toFixed(2))
            });
        }else{
            $('.invoiceAdvanceBlock').fadeOut('fast', function(){
                $('.theDesc', this).html('£0.00');
            });
        }

        $('.invoiceTotalBlock .theDesc').html('£'+total.toFixed(2));
        $('.invoiceBalanceBlock .theDesc').html('£'+totalBalance.toFixed(2));

        return {
            'totalBalance' : totalBalance
        };
    }

    function getInvoiceItemTotal(){
        let invoiteItemTotal = 0;
        let invoiceItemVatTotal = 0;
        if(localStorage.invoiceItems){
            let invoiceItems = localStorage.getItem('invoiceItems');
            let invoiceItemsObj = JSON.parse(invoiceItems);
    
            if(Object.keys(invoiceItemsObj).length > 0){
                for (const [inv_item_serial, invItem] of Object.entries(invoiceItemsObj)) {
                    
                    let units = invItem.units * 1;
                    let unit_price = invItem.price * 1;
                    let vat_rate = invItem.vat * 1;
                    
                    let total = unit_price * units;
                    let vatTotal = (total * vat_rate) / 100;

                    invoiteItemTotal += total;
                    invoiceItemVatTotal += vatTotal;
                }
            }
        }

        return {'invoiteItemTotal' : invoiteItemTotal, 'invoiceItemVatTotal' : invoiceItemVatTotal};
    }

    function getInvoiceDiscountTotal(){
        let discountTotal = 0;
        let discountVatTotal = 0;
        let hasDiscount = 0;

        if(localStorage.invoiceDiscounts){
            hasDiscount = 1;
            let invoiceDiscounts = localStorage.getItem('invoiceDiscounts');
            let invoiceDiscountsObj = JSON.parse(invoiceDiscounts);
            let discountAmount = invoiceDiscountsObj.amount * 1;
            let vat_rate = invoiceDiscountsObj.vat * 1;
            
            discountTotal = discountAmount;
            discountVatTotal = (discountTotal * vat_rate) / 100;
        }

        return {'hasDiscount' : hasDiscount, 'discountTotal' : discountTotal, 'discountVatTotal' : discountVatTotal};
    }

    function getInvoiceAdvanceTotal(){
        let hasAdvance = 0;
        let advanceTotal = 0;

        if(localStorage.invoiceAdvance){
            let invoiceAdvance = localStorage.getItem('invoiceAdvance');
            let invoiceAdvanceObj = JSON.parse(invoiceAdvance);

            hasAdvance = 1;
            advanceTotal = (invoiceAdvanceObj.advance_amount ? invoiceAdvanceObj.advance_amount * 1 : 0);
        }

        return {'hasAdvance' : hasAdvance, 'advanceTotal' : advanceTotal};
    }

    function getDueAmountForDiscount(){
        let totalDue = 0;
        let invoiceItems = getInvoiceItemTotal();
        let invoiceDiscounts = getInvoiceDiscountTotal();
        let invoiceAdvances = getInvoiceAdvanceTotal();

        let invoiteItemTotal = invoiceItems.invoiteItemTotal;
        let invoiceItemVatTotal = invoiceItems.invoiceItemVatTotal;

        let hasDiscount = invoiceDiscounts.hasDiscount;
        let discountTotal = invoiceDiscounts.discountTotal;
        let discountVatTotal = invoiceDiscounts.discountVatTotal;

        let hasAdvance = invoiceAdvances.hasAdvance;
        let advanceTotal = invoiceAdvances.advanceTotal;

        let due = invoiteItemTotal + (isVatInvoice ? invoiceItemVatTotal : 0);
            totalDue = due - (hasAdvance ? advanceTotal : 0);

        return totalDue;
    }

    function getDueAmountForAdvance(){
        let totalDue = 0;
        let invoiceItems = getInvoiceItemTotal();
        let invoiceDiscounts = getInvoiceDiscountTotal();
        let invoiceAdvances = getInvoiceAdvanceTotal();

        let invoiteItemTotal = invoiceItems.invoiteItemTotal;
        let invoiceItemVatTotal = invoiceItems.invoiceItemVatTotal;

        let hasDiscount = invoiceDiscounts.hasDiscount;
        let discountTotal = invoiceDiscounts.discountTotal;
        let discountVatTotal = invoiceDiscounts.discountVatTotal;

        let hasAdvance = invoiceAdvances.hasAdvance;
        let advanceTotal = invoiceAdvances.advanceTotal;

        let vatAmount = invoiceItemVatTotal - (hasDiscount ? discountVatTotal : 0);

            totalDue = invoiteItemTotal - (hasDiscount ? discountTotal : 0) + (isVatInvoice ? vatAmount : 0);

        return totalDue;
    }

    function getFormatedData(formData){
        let theObject = {};
        for (var i = 0; i < formData.length; i++) {
            let theData = formData[i];
            let name = theData.name;
            let values = theData.value;
            theObject[name] = values;
        }

        return theObject;
    }

    function getTodayDate(){
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1;
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        return dd+'-'+mm+'-'+yyyy;
    }
    function formatDateToDdMmYyyy(theDateString) {
        const date = new Date(theDateString);
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        const formattedDay = day < 10 ? '0' + day : day;
        const formattedMonth = month < 10 ? '0' + month : month;

        return `${formattedDay}-${formattedMonth}-${year}`;
    }

})();