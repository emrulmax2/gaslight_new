(function () {
    "use strict";
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

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addInvoiceItemModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addInvoiceItemModal"));
    const editInvoiceItemModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#editInvoiceItemModal"));
    const invoiceDiscountModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#invoiceDiscountModal"));
    const prePaymentInvoiceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#prePaymentInvoiceModal"));
    
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

    document.getElementById('addInvoiceItemModal').addEventListener('hide.tw.modal', function(event) {
        $('#addInvoiceItemModal input').val('');
        $('#addInvoiceItemModal textarea').val('');
        $('#addInvoiceItemModal input[name="srial"]').val('0');
    });

    document.getElementById('invoiceDiscountModal').addEventListener('hide.tw.modal', function(event) {
        $('#invoiceDiscountModal .modal-body input').val('');
        $('#invoiceDiscountModal [name="max_discount"]').val(0);
        $('#invoiceDiscountModal .dueLeft').html('This invoice has £0 outstanding.');
        $('#invoiceDiscountModal #removeDiscountBtn').fadeOut();
    });

    document.getElementById('prePaymentInvoiceModal').addEventListener('hide.tw.modal', function(event) {
        $('#prePaymentInvoiceModal .modal-body input').val('');
        $('#prePaymentInvoiceModal [name="max_advance"]').val(0);
        $('#prePaymentInvoiceModal .dueLeft').html('This invoice has £0 outstanding.');
        $('#prePaymentInvoiceModal #removeAdvanceBtn').fadeOut();
        payment_method_id.clear(true);
    });

    /* Init Variables */
    let isNonVatCheck = $("#nonVatInvoiceCheck").prop('checked') ? true : false;
    let rowCounter = 2;
    let invoiceItems = [];
    let prePaymentDetails = {};
    let discountAmountDetails = {};
    window.onload = calculateInvoice();
    
    $('#nonVatInvoiceCheck').on('change', function(){
        isNonVatCheck = $(this).prop('checked') ? true : false;
        if(!isNonVatCheck){
            $('#invoiceItemsTable').find('.vatCol, .vatField').removeClass('hidden').addClass('table-cell');
            $('.vatTotalField').fadeIn();
            $('.vatNumberField').fadeIn();
        }else{
            $('#invoiceItemsTable').find('.vatCol, .vatField').addClass('hidden').removeClass('table-cell');
            $('.vatTotalField').fadeOut();
            $('.vatNumberField').fadeOut();
        }

        //Recalculate the invoice here.....
        calculateInvoice();
    });



    /* BEGIN: Add Item */
    $('#addInvoiceItem').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let serial = getLastRowId();

        addInvoiceItemModal.show();
        document.getElementById('addInvoiceItemModal').addEventListener('shown.tw.modal', function(event){
            $('#addInvoiceItemModal [name="description"]').val('Item Description');
            $('#addInvoiceItemModal [name="units"]').val(1);
            $('#addInvoiceItemModal [name="price"]').val(0);
            $('#addInvoiceItemModal [name="srial"]').val(serial);

            if(!isNonVatCheck){
                $('#addInvoiceItemModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(20);
                });
            }else{
                $('#addInvoiceItemModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                });
            }
        });
    });

    $('#addInvoiceItemModal').on('click', '#addInvoiceItemBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $theBtn.siblings('button').attr('disabled', 'disabled');
        $theBtn.attr('disabled', 'disabled');
        $('.theLoader', $theBtn).fadeIn();

        var formError = 0;
        formError += ($('#addInvoiceItemModal [name="description"]').val() == '' ? 1 : 0);
        formError += ($('#addInvoiceItemModal [name="units"]').val() == '' ? 1 : 0);
        formError += ($('#addInvoiceItemModal [name="price"]').val() == '' ? 1 : 0);

        if(formError > 0){
            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();

            $('#addInvoiceItemModal .modal-body').remove('errorAlert');
            $('#addInvoiceItemModal .modal-body').prepend('<div class="col-span-12"><div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg><strong>Oops!&nbsp; Form validation error found. Description, Units & Price field can not be empty.</div></div>');
        
            setTimeout(() => {
                $('#addInvoiceItemModal .modal-body').remove('errorAlert');
            }, 2000);
        }else{
            let serial = $('#addInvoiceItemModal [name="srial"]').val() * 1;
            let description = $('#addInvoiceItemModal [name="description"]').val();
            let units = parseFloat($('#addInvoiceItemModal [name="units"]').val());
            let unitPrice = parseFloat($('#addInvoiceItemModal [name="price"]').val());
            let price = unitPrice * units;
            let vatRate = (!isNonVatCheck ? parseFloat($('#addInvoiceItemModal [name="vat"]').val()) : 0);
            let vatAmount = (!isNonVatCheck ? (price / 100) * vatRate : 0);
            let lineTotal = price + vatAmount;


            let html = '';
                html += '<tr data-id="'+serial+'" class="invoiceItemRow cursor-pointer">';
                    html += '<td class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 descriptions max-sm:block max-sm:w-full">';
                        html += '<div class="flex justify-start items-start">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 w-4 h-4 mr-3"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>';
                            html += '<span>'+description+'</span>';
                        html += '</div>';
                        html += '<input type="hidden" name="inv['+serial+'][descritpion]" class="description" value="'+description+'">';
                    html += '</td>';
                    html += '<td data-th="UNITS" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 units w-full sm:w-[120px] text-left sm:text-right max-sm:block">';
                        html += units;
                        html += '<input type="hidden" name="inv['+serial+'][units]" class="unit" value="'+units+'">';
                    html += '</td>';
                    html += '<td data-th="PRICE" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 prices w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                        html += '£'+unitPrice.toFixed(2);
                        html += '<input type="hidden" name="inv['+serial+'][unit_price]" class="unit_price" value="'+unitPrice+'">';
                    html += '</td>';
                    html += '<td data-th="VAT %" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 vatCol w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block '+(!isNonVatCheck ? 'table-cell max-sm:block' : 'hidden')+'">';
                        html += vatRate+'%';
                        html += '<input type="hidden" name="inv['+serial+'][vat_rate]" class="vat_rate" value="'+vatRate+'">';
                        html += '<input type="hidden" name="inv['+serial+'][vat_amount]" class="vat_amount" value="'+vatAmount+'">';
                    html += '</td>';
                    html += '<td data-th="LINE TOTAL" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 lineTotal w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                        html += '<span class="line_total_html">£'+lineTotal.toFixed(2)+'</span>';
                        html += '<input type="hidden" name="inv['+serial+'][line_total]" class="line_total" value="'+lineTotal+'">';
                    html += '</td>';
                html += '</tr>';

            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();
            addInvoiceItemModal.hide();

            console.log(html)
            $('#invoiceItemsTable tbody').append(html);
        
            calculateInvoice();
        }

    })
    /* END: Add Item */

    /* BEGIN: Edit Item */
    $('#invoiceItemsTable').on('click', '.invoiceItemRow', function(e){
        e.preventDefault();
        let $theRow = $(this);
        let serial = $theRow.attr('data-id');

        let description = $('.description', $theRow).val();
        let unit = $('.unit', $theRow).val();
        let unit_price = $('.unit_price', $theRow).val();
        let vat_rate = $('.vat_rate', $theRow).val();
        let vat_amount = $('.vat_amount', $theRow).val();
        let line_total = $('.line_total', $theRow).val();

        editInvoiceItemModal.show();
        document.getElementById('editInvoiceItemModal').addEventListener('shown.tw.modal', function(event){
            $('#editInvoiceItemModal [name="description"]').val(description);
            $('#editInvoiceItemModal [name="units"]').val(unit);
            $('#editInvoiceItemModal [name="price"]').val(unit_price);
            $('#editInvoiceItemModal [name="srial"]').val(serial);

            if(!isNonVatCheck){
                $('#editInvoiceItemModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(vat_rate);
                });
            }else{
                $('#editInvoiceItemModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                });
            }
        });
    });

    $('#editInvoiceItemModal').on('click', '#updateInvoiceItemBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $theBtn.siblings('button').attr('disabled', 'disabled');
        $theBtn.attr('disabled', 'disabled');
        $('.theLoader', $theBtn).fadeIn();

        var formError = 0;
        formError += ($('#editInvoiceItemModal [name="description"]').val() == '' ? 1 : 0);
        formError += ($('#editInvoiceItemModal [name="units"]').val() == '' ? 1 : 0);
        formError += ($('#editInvoiceItemModal [name="price"]').val() == '' ? 1 : 0);

        if(formError > 0){
            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();

            $('#editInvoiceItemModal .modal-body').remove('errorAlert');
            $('#editInvoiceItemModal .modal-body').prepend('<div class="col-span-12"><div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg><strong>Oops!&nbsp; Form validation error found. Description, Units & Price field can not be empty.</div></div>');
        
            setTimeout(() => {
                $('#editInvoiceItemModal .modal-body').remove('errorAlert');
            }, 2000);
        }else{
            let serial = $('#editInvoiceItemModal [name="srial"]').val();
            let description = $('#editInvoiceItemModal [name="description"]').val();
            let units = parseFloat($('#editInvoiceItemModal [name="units"]').val());
            let unitPrice = parseFloat($('#editInvoiceItemModal [name="price"]').val());
            let price = unitPrice * units;
            let vatRate = (!isNonVatCheck ? parseFloat($('#editInvoiceItemModal [name="vat"]').val()) : 0);
            let vatAmount = (!isNonVatCheck ? (price / 100) * vatRate : 0);
            let lineTotal = price + vatAmount;


            let html = '';
                html += '<td class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 descriptions max-sm:block max-sm:w-full">';
                    html += '<div class="flex justify-start items-start">';
                        html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 w-4 h-4 mr-3"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>';
                        html += '<span>'+description+'</span>';
                    html += '</div>';
                    html += '<input type="hidden" name="inv['+serial+'][descritpion]" class="description" value="'+description+'">';
                html += '</td>';
                html += '<td data-th="UNITS" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 units w-full sm:w-[120px] text-left sm:text-right max-sm:block">';
                    html += units;
                    html += '<input type="hidden" name="inv['+serial+'][units]" class="unit" value="'+units+'">';
                html += '</td>';
                html += '<td data-th="PRICE" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 prices w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                    html += '£'+unitPrice.toFixed(2);
                    html += '<input type="hidden" name="inv['+serial+'][unit_price]" class="unit_price" value="'+unitPrice+'">';
                html += '</td>';
                html += '<td data-th="VAT %" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 vatCol w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block '+(!isNonVatCheck ? 'table-cell max-sm:block' : 'hidden')+'">';
                    html += vatRate+'%';
                    html += '<input type="hidden" name="inv['+serial+'][vat_rate]" class="vat_rate" value="'+vatRate+'">';
                    html += '<input type="hidden" name="inv['+serial+'][vat_amount]" class="vat_amount" value="'+vatAmount+'">';
                html += '</td>';
                html += '<td data-th="LINE TOTAL" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 lineTotal w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                    html += '<span class="line_total_html">£'+lineTotal.toFixed(2)+'</span>';
                    html += '<input type="hidden" name="inv['+serial+'][line_total]" class="line_total" value="'+lineTotal+'">';
                html += '</td>';

            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();
            editInvoiceItemModal.hide();

            $('#invoiceItemsTable tbody tr[data-id="'+serial+'"]').html(html);
        
            calculateInvoice();
        }

    })
    /* END: Edit Item */

    /* BEGIN: Discount Item */
    $('#addDiscountBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let DueAmount = $('[name="due_price"]').val() * 1;
        let DueAmountHtml = '£'+DueAmount.toFixed(2);
        let DiscountExist = ($('#invoiceItemsTable tbody tr.invoiceDiscountRow').length > 0 ? true : false);

        if(DiscountExist){
            $('#invoiceItemsTable tbody tr.invoiceDiscountRow').trigger('click');
        }else if(DueAmount > 0 && !DiscountExist){
            let theLabel = 'This invoice has '+DueAmountHtml+' outstanding.'
            invoiceDiscountModal.show();
            document.getElementById('invoiceDiscountModal').addEventListener('shown.tw.modal', function(event){
                $('#invoiceDiscountModal .dueLeft').html(theLabel);
                $('#invoiceDiscountModal [name="discount_amount"]').val(DueAmount.toFixed(2));
                $('#invoiceDiscountModal [name="max_discount"]').val(DueAmount.toFixed(2));
                if(!isNonVatCheck){
                    $('#invoiceDiscountModal .discountVatField').fadeIn('fast', function(){
                        $('input', this).val(20);
                    });
                }else{
                    $('#invoiceDiscountModal .discountVatField').fadeOut('fast', function(){
                        $('input', this).val(0);
                    });
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

    $('#addDiscountModalBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let DiscountExist = ($('#invoiceItemsTable tbody tr.invoiceDiscountRow').length > 0 ? true : false);

        $theBtn.siblings('button').attr('disabled', 'disabled');
        $theBtn.attr('disabled', 'disabled');
        $('.theLoader', $theBtn).fadeIn();

        var formError = 0;
        formError += ($('#invoiceDiscountModal [name="discount_amount"]').val() == '' ? 1 : 0);
        formError += (($('#invoiceDiscountModal [name="discount_amount"]').val() * 1) > ($('#invoiceDiscountModal [name="max_discount"]').val() * 1) ? 1 : 0);

        if(formError > 0){
            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();

            $('#invoiceDiscountModal .modal-body').remove('errorAlert');
            $('#invoiceDiscountModal .modal-body').prepend('<div class="errorAlert col-span-12"><div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg><strong>Oops!&nbsp; Form validation error found. Discount can nto empty or grater than the outstanding amount.</div></div>');
        
            setTimeout(() => {
                $('#invoiceDiscountModal .modal-body').remove('errorAlert');
            }, 2000);
        }else{
            let units = 1;
            let unitPrice = parseFloat($('#invoiceDiscountModal [name="discount_amount"]').val());
            let price = unitPrice * units;
            let vatRate = (!isNonVatCheck ? parseFloat($('#invoiceDiscountModal [name="discount_vat_rate"]').val()) : 0);
            let vatAmount = (!isNonVatCheck ? (price / 100) * vatRate : 0);
            let lineTotal = price + vatAmount;


            let html = '';
                html += (!DiscountExist ? '<tr class="invoiceDiscountRow cursor-pointer">' : '');
                    html += '<td class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 descriptions max-sm:block max-sm:w-full">';
                        html += '<div class="flex justify-start items-start">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 w-4 h-4 mr-3"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>';
                            html += '<span>Discount</span>';
                        html += '</div>';
                        html += '<input type="hidden" name="inv[discount][descritpion]" class="description" value="Discount">';
                    html += '</td>';
                    html += '<td data-th="UNITS" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 units w-full sm:w-[120px] text-left sm:text-right max-sm:block">';
                        html += units;
                        html += '<input type="hidden" name="inv[discount][units]" class="unit" value="'+units+'">';
                    html += '</td>';
                    html += '<td data-th="PRICE" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 prices w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                        html += '£'+unitPrice.toFixed(2);
                        html += '<input type="hidden" name="inv[discount][unit_price]" class="unit_price" value="'+unitPrice+'">';
                    html += '</td>';
                    html += '<td data-th="VAT %" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 vatCol w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block  '+(!isNonVatCheck ? 'table-cell max-sm:block' : 'hidden')+'">';
                        html += vatRate+'%';
                        html += '<input type="hidden" name="inv[discount][vat_rate]" class="vat_rate" value="'+vatRate+'">';
                        html += '<input type="hidden" name="inv[discount][vat_amount]" class="vat_amount" value="'+vatAmount+'">';
                    html += '</td>';
                    html += '<td data-th="LINE TOTAL" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 lineTotal w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                        html += '<span class="line_total_html">-£'+lineTotal.toFixed(2)+'</span>';
                        html += '<input type="hidden" name="inv[discount][line_total]" class="line_total" value="'+lineTotal+'">';
                    html += '</td>';
                html += (!DiscountExist ? '</tr>' : '');

            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();
            invoiceDiscountModal.hide();

            if(DiscountExist){
                $('#invoiceItemsTable tbody tr.invoiceDiscountRow').html(html);
            }else{
                $('#invoiceItemsTable tbody').append(html);
            }
        
            calculateInvoice();
        }
    });

    $('#invoiceItemsTable').on('click', '.invoiceDiscountRow', function(e){
        e.preventDefault();
        let $theRow = $(this);

        let description = $('.description', $theRow).val();
        let unit = $('.unit', $theRow).val() * 1;
        let unit_price = $('.unit_price', $theRow).val() * 1;
        let vat_rate = $('.vat_rate', $theRow).val() * 1;
        let vat_amount = $('.vat_amount', $theRow).val() * 1;
        let line_total = $('.line_total', $theRow).val() * 1;

        let DueAmount = ($('[name="due_price"]').val() * 1 + unit_price);
        let DueAmountHtml = '£'+DueAmount.toFixed(2);
        let theLabel = 'This invoice has '+DueAmountHtml+' outstanding.'

        invoiceDiscountModal.show();
        document.getElementById('invoiceDiscountModal').addEventListener('shown.tw.modal', function(event){
            $('#invoiceDiscountModal .dueLeft').html(theLabel);
            $('#invoiceDiscountModal [name="discount_amount"]').val(unit_price);
            $('#invoiceDiscountModal [name="max_discount"]').val(DueAmount);
            if(!isNonVatCheck){
                $('#invoiceDiscountModal .discountVatField').fadeIn('fast', function(){
                    $('input', this).val(vat_rate);
                });
            }else{
                $('#invoiceDiscountModal .discountVatField').fadeOut('fast', function(){
                    $('input', this).val(vat_rate);
                });
            }
            $('#invoiceDiscountModal #removeDiscountBtn').fadeIn();
        });
    });

    $('#invoiceDiscountModal #removeDiscountBtn').on('click', function(e){
        e.preventDefault();

        invoiceDiscountModal.hide();
        $('#invoiceItemsTable tbody tr.invoiceDiscountRow').remove();
    
        calculateInvoice();
    });
    /* END: Discount Item */

    /* BEGIN: Pre Payment Item */
    $('#addPrePaymentBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let DueAmount = $('[name="due_price"]').val() * 1;
        let DueAmountHtml = '£'+DueAmount.toFixed(2);
        let AdvanceExist = ($('.paidToDateField').hasClass('hasPayment') ? true : false);

        if(AdvanceExist){
            let AdvanceAmount = $('#inv_advance_amount').val() * 1;
            let AdvanceMethodId = $('#inv_payment_method_id').val();
            let AdvancePayDate = $('#inv_advance_date').val();

            let DueAmount = ($('[name="due_price"]').val() * 1) + AdvanceAmount;
            let DueAmountHtml = '£'+DueAmount.toFixed(2);
            let theLabel = 'This invoice has '+DueAmountHtml+' outstanding.'

            prePaymentInvoiceModal.show();
            document.getElementById('prePaymentInvoiceModal').addEventListener('shown.tw.modal', function(event){
                $('#prePaymentInvoiceModal .dueLeft').html(theLabel);

                $('#prePaymentInvoiceModal [name="advance_amount"]').val(AdvanceAmount);
                $('#prePaymentInvoiceModal [name="advance_pay_date"]').val(AdvancePayDate);
                $('#prePaymentInvoiceModal [name="max_advance"]').val(DueAmount);
                payment_method_id.addItem(AdvanceMethodId);
                $('#prePaymentInvoiceModal #removeAdvanceBtn').fadeIn();
            });
        }else if(DueAmount > 0 && !AdvanceExist){
            let theLabel = 'This invoice has '+DueAmountHtml+' outstanding.'
            prePaymentInvoiceModal.show();
            document.getElementById('prePaymentInvoiceModal').addEventListener('shown.tw.modal', function(event){
                $('#prePaymentInvoiceModal .dueLeft').html(theLabel);
                $('#prePaymentInvoiceModal [name="advance_amount"]').val(DueAmount);
                $('#prePaymentInvoiceModal [name="advance_pay_date"]').val(getTodayDate());
                $('#prePaymentInvoiceModal [name="max_advance"]').val(DueAmount);
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

    $('#addAdvancePayBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $theBtn.siblings('button').attr('disabled', 'disabled');
        $theBtn.attr('disabled', 'disabled');
        $('.theLoader', $theBtn).fadeIn();

        var formError = 0;
        formError += ($('#prePaymentInvoiceModal [name="advance_amount"]').val() == '' ? 1 : 0);
        formError += ($('#prePaymentInvoiceModal [name="payment_method_id"]').val() == '' ? 1 : 0);
        formError += ($('#prePaymentInvoiceModal [name="advance_pay_date"]').val() == '' ? 1 : 0);
        formError += ($('#prePaymentInvoiceModal [name="advance_amount"]').val() > $('#prePaymentInvoiceModal [name="max_advance"]').val() ? 1 : 0);

        if(formError > 0){
            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();

            $('#prePaymentInvoiceModal .modal-body').remove('errorAlert');
            $('#prePaymentInvoiceModal .modal-body').prepend('<div class="errorAlert col-span-12"><div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg><strong>Oops!&nbsp; Form validation error found. All fields are required and Amount can not grater than the outstanding amount.</div></div>');
        
            setTimeout(() => {
                $('#prePaymentInvoiceModal .modal-body').remove('errorAlert');
            }, 2000);
        }else{
            let amount = $('#prePaymentInvoiceModal [name="advance_amount"]').val() * 1;
            let method_id = $('#prePaymentInvoiceModal [name="payment_method_id"]').val();
            let pay_date = $('#prePaymentInvoiceModal [name="advance_pay_date"]').val();

            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();
            prePaymentInvoiceModal.hide();

            $('.paidToDateField').addClass('hasPayment').fadeIn();
            $('#inv_advance_amount_html').html('£'+amount.toFixed(2));
            $('#inv_advance_amount').val(amount);
            $('#inv_payment_method_id').val(method_id);
            $('#inv_advance_date').val(pay_date);

        
            calculateInvoice();
        }
    });

    $('#removeAdvanceBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        prePaymentInvoiceModal.hide();
        $('#inv_advance_amount_html').html('£0.00');
        $('#inv_advance_amount').val('0');
        $('#inv_payment_method_id').val('0');
        $('#inv_advance_date').val('');
        $('.paidToDateField').removeClass('hasPayment').fadeOut();
    
        calculateInvoice();
    })
    /* END: Pre Payment Item */

    function getLastRowId(){
        let $theTable = $('#invoiceItemsTable');
        let serial = parseInt($theTable.find('.invoiceItemRow').last().attr('data-id'), 10);

        return (serial ? serial + 1 : 1);
    }

    function updateInvoiceRows(){
        let $theTable = $('#invoiceItemsTable');

        $theTable.find('.invoiceItemRow').each(function(){
            let $theRow = $(this);

            let description = $('.description', $theRow).val();
            let unit = $('.unit', $theRow).val() * 1;
            let unit_price = $('.unit_price', $theRow).val() * 1;
            let vat_rate = $('.vat_rate', $theRow).val() * 1;
            let vat_amount = $('.vat_amount', $theRow).val() * 1;

            let lineTotal = (!isNonVatCheck ? (unit_price * unit) + vat_amount : (unit_price * unit));
            $('.line_total_html', $theRow).html('£'+lineTotal.toFixed(2));
            $('.line_total', $theRow).val(lineTotal);
        });

        $theTable.find('.invoiceDiscountRow').each(function(){
            let $theRow = $(this);

            let unit = $('.unit', $theRow).val() * 1;
            let unit_price = $('.unit_price', $theRow).val() * 1;
            let vat_rate = $('.vat_rate', $theRow).val() * 1;
            let vat_amount = $('.vat_amount', $theRow).val() * 1;

            let lineTotal = (!isNonVatCheck ? (unit_price * unit) + vat_amount : (unit_price * unit));
            $('.line_total_html', $theRow).html('-£'+lineTotal.toFixed(2));
            $('.line_total', $theRow).val(lineTotal);
        });
    }

    function calculateInvoice(){
        updateInvoiceRows();

        let $theTable = $('#invoiceItemsTable');

        let subTotal = 0;
        let vatTotal = 0;
        let Total = 0;
        let Due = 0;
        let DiscountTotal = 0;
        let DiscountVatTotal = 0;

        /* Calculate Rows */
        $theTable.find('.invoiceItemRow').each(function(){
            let $theRow = $(this);

            let description = $('.description', $theRow).val();
            let unit = $('.unit', $theRow).val() * 1;
            let unit_price = $('.unit_price', $theRow).val() * 1;
            let vat_rate = $('.vat_rate', $theRow).val() * 1;
            let vat_amount = $('.vat_amount', $theRow).val() * 1;

            let lineTotalWV = unit_price * unit;

            subTotal += lineTotalWV;
            vatTotal += vat_amount;
            
        });
        
        $theTable.find('.invoiceDiscountRow').each(function(){
            let $theRow = $(this);

            let unit = $('.unit', $theRow).val() * 1;
            let unit_price = $('.unit_price', $theRow).val() * 1;
            let vat_rate = $('.vat_rate', $theRow).val() * 1;
            let vat_amount = $('.vat_amount', $theRow).val() * 1;

            let lineTotalWV = unit_price * unit;
            DiscountTotal += lineTotalWV;
            DiscountVatTotal += vat_amount;

            subTotal -= DiscountTotal;
            vatTotal = (!isNonVatCheck ? vatTotal - DiscountVatTotal : vatTotal);
        });

        let AdvanceAmount = ($('.paidToDateField').hasClass('hasPayment') ? parseFloat($('#inv_advance_amount').val()) : 0);
        

        Total = (!isNonVatCheck ? subTotal + vatTotal : subTotal);
        Due = Total - AdvanceAmount;

        $('.subtotal_price').html('£'+subTotal.toFixed(2));
        $('input[name="subtotal_price"]').val(subTotal);  

        $('.vat_total_price').html('£'+vatTotal.toFixed(2));
        $('input[name="vat_total_price"]').val(vatTotal); 

        $('.total_price').html('£'+Total.toFixed(2));
        $('input[name="total_price"]').val(Total); 

        $('.due_price').html('£'+Due.toFixed(2));
        $('input[name="due_price"]').val(Due);      
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

    /* BEGIN: Save Invoice */
    $('#JobInvoiceForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('JobInvoiceForm');
        let $theForm = $(this);
        let type = $theForm.find('[name="submit_type"]').val();

        $('.formSubmits', $theForm).attr('disabled', 'disabled');
        $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeIn();
        $('.formSubmits.submit_'+type, $theForm).addClass('active');

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('invoice.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('.formSubmits', $theForm).removeAttr('disabled');
            $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeOut();
            $('.formSubmits.submit_'+type, $theForm).removeClass('active');

            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                });
                if(type == 2){
                    window.open(response.data.pdf);
                }

                setTimeout(() => {
                    successModal.hide();
                }, 1500);
            }
        }).catch(error => {
            $('.formSubmits', $theForm).removeAttr('disabled');
            $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeOut();
            $('.formSubmits.submit_'+type, $theForm).removeClass('active');
            if (error.response) {
                if (error.response.status == 422) {
                    warningModal.show();
                    document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                        $("#warningModal .warningModalTitle").html("Error Found!");
                        $("#warningModal .warningModalDesc").html(error.response.data.msg);
                    });

                    setTimeout(() => {
                        warningModal.hide();
                    }, 1500);
                } else {
                    console.log('error');
                }
            }
        });
    })
    /* END: Save Invoice */

})();