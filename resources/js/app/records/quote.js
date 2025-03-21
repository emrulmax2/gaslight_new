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
    //let payment_method_id = new TomSelect(document.getElementById('payment_method_id'), tncTomOptions);

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addQuoteItemModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addQuoteItemModal"));
    const editQuoteItemModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#editQuoteItemModal"));
    const quoteDiscountModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#quoteDiscountModal"));
    
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

    document.getElementById('addQuoteItemModal').addEventListener('hide.tw.modal', function(event) {
        $('#addQuoteItemModal input').val('');
        $('#addQuoteItemModal textarea').val('');
        $('#addQuoteItemModal input[name="srial"]').val('0');
    });

    document.getElementById('quoteDiscountModal').addEventListener('hide.tw.modal', function(event) {
        $('#quoteDiscountModal .modal-body input').val('');
        $('#quoteDiscountModal [name="max_discount"]').val(0);
        $('#quoteDiscountModal .dueLeft').html('This quote has £0 outstanding.');
        $('#quoteDiscountModal #removeDiscountBtn').fadeOut();
    });

    /* Init Variables */
    let isNonVatCheck = $("#nonVatQuoteCheck").prop('checked') ? true : false;
    let rowCounter = 2;
    let quoteItems = [];
    let prePaymentDetails = {};
    let discountAmountDetails = {};
    window.onload = calculateQuote();
    
    $('#nonVatQuoteCheck').on('change', function(){
        isNonVatCheck = $(this).prop('checked') ? true : false;
        if(!isNonVatCheck){
            $('#quoteItemsTable').find('.vatCol, .vatField').removeClass('hidden').addClass('table-cell');
            $('.vatTotalField').fadeIn();
            $('.vatNumberField').fadeIn();
        }else{
            $('#quoteItemsTable').find('.vatCol, .vatField').addClass('hidden').removeClass('table-cell');
            $('.vatTotalField').fadeOut();
            $('.vatNumberField').fadeOut();
        }

        //Recalculate the quote here.....
        calculateQuote();
    });



    /* BEGIN: Add Item */
    $('#addQuoteItem').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let serial = getLastRowId();

        addQuoteItemModal.show();
        document.getElementById('addQuoteItemModal').addEventListener('shown.tw.modal', function(event){
            $('#addQuoteItemModal [name="description"]').val('Item Description');
            $('#addQuoteItemModal [name="units"]').val(1);
            $('#addQuoteItemModal [name="price"]').val(0);
            $('#addQuoteItemModal [name="srial"]').val(serial);

            if(!isNonVatCheck){
                $('#addQuoteItemModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(20);
                });
            }else{
                $('#addQuoteItemModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                });
            }
        });
    });

    $('#addQuoteItemModal').on('click', '#addQuoteItemBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $theBtn.siblings('button').attr('disabled', 'disabled');
        $theBtn.attr('disabled', 'disabled');
        $('.theLoader', $theBtn).fadeIn();

        var formError = 0;
        formError += ($('#addQuoteItemModal [name="description"]').val() == '' ? 1 : 0);
        formError += ($('#addQuoteItemModal [name="units"]').val() == '' ? 1 : 0);
        formError += ($('#addQuoteItemModal [name="price"]').val() == '' ? 1 : 0);

        if(formError > 0){
            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();

            $('#addQuoteItemModal .modal-body').remove('errorAlert');
            $('#addQuoteItemModal .modal-body').prepend('<div class="col-span-12"><div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg><strong>Oops!&nbsp; Form validation error found. Description, Units & Price field can not be empty.</div></div>');
        
            setTimeout(() => {
                $('#addQuoteItemModal .modal-body').remove('errorAlert');
            }, 2000);
        }else{
            let serial = $('#addQuoteItemModal [name="srial"]').val() * 1;
            let description = $('#addQuoteItemModal [name="description"]').val();
            let units = parseFloat($('#addQuoteItemModal [name="units"]').val());
            let unitPrice = parseFloat($('#addQuoteItemModal [name="price"]').val());
            let price = unitPrice * units;
            let vatRate = (!isNonVatCheck ? parseFloat($('#addQuoteItemModal [name="vat"]').val()) : 0);
            let vatAmount = (!isNonVatCheck ? (price / 100) * vatRate : 0);
            let lineTotal = price + vatAmount;


            let html = '';
                html += '<tr data-id="'+serial+'" class="quoteItemRow cursor-pointer">';
                    html += '<td class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 descriptions max-sm:block max-sm:w-full">';
                        html += '<div class="flex justify-start items-start">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 w-4 h-4 mr-3"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>';
                            html += '<span>'+description+'</span>';
                        html += '</div>';
                        html += '<input type="hidden" name="qot['+serial+'][descritpion]" class="description" value="'+description+'">';
                    html += '</td>';
                    html += '<td data-th="UNITS" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 units w-full sm:w-[120px] text-left sm:text-right max-sm:block">';
                        html += units;
                        html += '<input type="hidden" name="qot['+serial+'][units]" class="unit" value="'+units+'">';
                    html += '</td>';
                    html += '<td data-th="PRICE" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 prices w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                        html += '£'+unitPrice.toFixed(2);
                        html += '<input type="hidden" name="qot['+serial+'][unit_price]" class="unit_price" value="'+unitPrice+'">';
                    html += '</td>';
                    html += '<td data-th="VAT %" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 vatCol w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block '+(!isNonVatCheck ? 'table-cell max-sm:block' : 'hidden')+'">';
                        html += vatRate+'%';
                        html += '<input type="hidden" name="qot['+serial+'][vat_rate]" class="vat_rate" value="'+vatRate+'">';
                        html += '<input type="hidden" name="qot['+serial+'][vat_amount]" class="vat_amount" value="'+vatAmount+'">';
                    html += '</td>';
                    html += '<td data-th="LINE TOTAL" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 lineTotal w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                        html += '<span class="line_total_html">£'+lineTotal.toFixed(2)+'</span>';
                        html += '<input type="hidden" name="qot['+serial+'][line_total]" class="line_total" value="'+lineTotal+'">';
                    html += '</td>';
                html += '</tr>';

            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();
            addQuoteItemModal.hide();

            console.log(html)
            $('#quoteItemsTable tbody').append(html);
        
            calculateQuote();
        }

    })
    /* END: Add Item */

    /* BEGIN: Edit Item */
    $('#quoteItemsTable').on('click', '.quoteItemRow', function(e){
        e.preventDefault();
        let $theRow = $(this);
        let serial = $theRow.attr('data-id');

        let description = $('.description', $theRow).val();
        let unit = $('.unit', $theRow).val();
        let unit_price = $('.unit_price', $theRow).val();
        let vat_rate = $('.vat_rate', $theRow).val();
        let vat_amount = $('.vat_amount', $theRow).val();
        let line_total = $('.line_total', $theRow).val();

        editQuoteItemModal.show();
        document.getElementById('editQuoteItemModal').addEventListener('shown.tw.modal', function(event){
            $('#editQuoteItemModal [name="description"]').val(description);
            $('#editQuoteItemModal [name="units"]').val(unit);
            $('#editQuoteItemModal [name="price"]').val(unit_price);
            $('#editQuoteItemModal [name="srial"]').val(serial);

            if(!isNonVatCheck){
                $('#editQuoteItemModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(vat_rate);
                });
            }else{
                $('#editQuoteItemModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                });
            }
        });
    });

    $('#editQuoteItemModal').on('click', '#updateQuoteItemBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $theBtn.siblings('button').attr('disabled', 'disabled');
        $theBtn.attr('disabled', 'disabled');
        $('.theLoader', $theBtn).fadeIn();

        var formError = 0;
        formError += ($('#editQuoteItemModal [name="description"]').val() == '' ? 1 : 0);
        formError += ($('#editQuoteItemModal [name="units"]').val() == '' ? 1 : 0);
        formError += ($('#editQuoteItemModal [name="price"]').val() == '' ? 1 : 0);

        if(formError > 0){
            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();

            $('#editQuoteItemModal .modal-body').remove('errorAlert');
            $('#editQuoteItemModal .modal-body').prepend('<div class="col-span-12"><div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg><strong>Oops!&nbsp; Form validation error found. Description, Units & Price field can not be empty.</div></div>');
        
            setTimeout(() => {
                $('#editQuoteItemModal .modal-body').remove('errorAlert');
            }, 2000);
        }else{
            let serial = $('#editQuoteItemModal [name="srial"]').val();
            let description = $('#editQuoteItemModal [name="description"]').val();
            let units = parseFloat($('#editQuoteItemModal [name="units"]').val());
            let unitPrice = parseFloat($('#editQuoteItemModal [name="price"]').val());
            let price = unitPrice * units;
            let vatRate = (!isNonVatCheck ? parseFloat($('#editQuoteItemModal [name="vat"]').val()) : 0);
            let vatAmount = (!isNonVatCheck ? (price / 100) * vatRate : 0);
            let lineTotal = price + vatAmount;


            let html = '';
                html += '<td class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 descriptions max-sm:block max-sm:w-full">';
                    html += '<div class="flex justify-start items-start">';
                        html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 w-4 h-4 mr-3"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>';
                        html += '<span>'+description+'</span>';
                    html += '</div>';
                    html += '<input type="hidden" name="qot['+serial+'][descritpion]" class="description" value="'+description+'">';
                html += '</td>';
                html += '<td data-th="UNITS" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 units w-full sm:w-[120px] text-left sm:text-right max-sm:block">';
                    html += units;
                    html += '<input type="hidden" name="qot['+serial+'][units]" class="unit" value="'+units+'">';
                html += '</td>';
                html += '<td data-th="PRICE" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 prices w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                    html += '£'+unitPrice.toFixed(2);
                    html += '<input type="hidden" name="qot['+serial+'][unit_price]" class="unit_price" value="'+unitPrice+'">';
                html += '</td>';
                html += '<td data-th="VAT %" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 vatCol vatCol w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block '+(!isNonVatCheck ? 'table-cell max-sm:block' : 'hidden')+'">';
                    html += vatRate+'%';
                    html += '<input type="hidden" name="qot['+serial+'][vat_rate]" class="vat_rate" value="'+vatRate+'">';
                    html += '<input type="hidden" name="qot['+serial+'][vat_amount]" class="vat_amount" value="'+vatAmount+'">';
                html += '</td>';
                html += '<td data-th="LINE TOTAL" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 lineTotal w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                    html += '<span class="line_total_html">£'+lineTotal.toFixed(2)+'</span>';
                    html += '<input type="hidden" name="qot['+serial+'][line_total]" class="line_total" value="'+lineTotal+'">';
                html += '</td>';

            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();
            editQuoteItemModal.hide();

            $('#quoteItemsTable tbody tr[data-id="'+serial+'"]').html(html);
        
            calculateQuote();
        }

    })
    /* END: Edit Item */

    /* BEGIN: Discount Item */
    $('#addDiscountBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let DueAmount = $('[name="due_price"]').val() * 1;
        let DueAmountHtml = '£'+DueAmount.toFixed(2);
        let DiscountExist = ($('#quoteItemsTable tbody tr.quoteDiscountRow').length > 0 ? true : false);

        if(DiscountExist){
            $('#quoteItemsTable tbody tr.quoteDiscountRow').trigger('click');
        }else if(DueAmount > 0 && !DiscountExist){
            let theLabel = 'This quote has '+DueAmountHtml+' outstanding.'
            quoteDiscountModal.show();
            document.getElementById('quoteDiscountModal').addEventListener('shown.tw.modal', function(event){
                $('#quoteDiscountModal .dueLeft').html(theLabel);
                $('#quoteDiscountModal [name="discount_amount"]').val(DueAmount.toFixed(2));
                $('#quoteDiscountModal [name="max_discount"]').val(DueAmount.toFixed(2));
                if(!isNonVatCheck){
                    $('#quoteDiscountModal .discountVatField').fadeIn('fast', function(){
                        $('input', this).val(20);
                    });
                }else{
                    $('#quoteDiscountModal .discountVatField').fadeOut('fast', function(){
                        $('input', this).val(0);
                    });
                }
            });
        }else{
            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Oops!");
                $("#warningModal .warningModalDesc").html('This quote does not have any due.');
            });

            setTimeout(() => {
                warningModal.hide();
            }, 1500);
        }

    });

    $('#addDiscountModalBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let DiscountExist = ($('#quoteItemsTable tbody tr.quoteDiscountRow').length > 0 ? true : false);

        $theBtn.siblings('button').attr('disabled', 'disabled');
        $theBtn.attr('disabled', 'disabled');
        $('.theLoader', $theBtn).fadeIn();

        var formError = 0;
        formError += ($('#quoteDiscountModal [name="discount_amount"]').val() == '' ? 1 : 0);
        formError += (($('#quoteDiscountModal [name="discount_amount"]').val() * 1) > ($('#quoteDiscountModal [name="max_discount"]').val() * 1) ? 1 : 0);

        if(formError > 0){
            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();

            $('#quoteDiscountModal .modal-body').remove('errorAlert');
            $('#quoteDiscountModal .modal-body').prepend('<div class="errorAlert col-span-12"><div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg><strong>Oops!&nbsp; Form validation error found. Discount can nto empty or grater than the outstanding amount.</div></div>');
        
            setTimeout(() => {
                $('#quoteDiscountModal .modal-body').remove('errorAlert');
            }, 2000);
        }else{
            let units = 1;
            let unitPrice = parseFloat($('#quoteDiscountModal [name="discount_amount"]').val());
            let price = unitPrice * units;
            let vatRate = (!isNonVatCheck ? parseFloat($('#quoteDiscountModal [name="discount_vat_rate"]').val()) : 0);
            let vatAmount = (!isNonVatCheck ? (price / 100) * vatRate : 0);
            let lineTotal = price + vatAmount;


            let html = '';
                html += (!DiscountExist ? '<tr class="quoteDiscountRow cursor-pointer">' : '');
                    html += '<td class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 descriptions max-sm:block max-sm:w-full">';
                        html += '<div class="flex justify-start items-start">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 w-4 h-4 mr-3"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>';
                            html += '<span>Discount</span>';
                        html += '</div>';
                        html += '<input type="hidden" name="qot[discount][descritpion]" class="description" value="Discount">';
                    html += '</td>';
                    html += '<td data-th="UNITS" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 units w-full sm:w-[120px] text-left sm:text-right max-sm:block">';
                        html += units;
                        html += '<input type="hidden" name="qot[discount][units]" class="unit" value="'+units+'">';
                    html += '</td>';
                    html += '<td data-th="PRICE" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 prices w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                        html += '£'+unitPrice.toFixed(2);
                        html += '<input type="hidden" name="qot[discount][unit_price]" class="unit_price" value="'+unitPrice+'">';
                    html += '</td>';
                    html += '<td data-th="VAT %" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 vatCol w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block  '+(!isNonVatCheck ? 'table-cell max-sm:block' : 'hidden')+'">';
                        html += vatRate+'%';
                        html += '<input type="hidden" name="qot[discount][vat_rate]" class="vat_rate" value="'+vatRate+'">';
                        html += '<input type="hidden" name="qot[discount][vat_amount]" class="vat_amount" value="'+vatAmount+'">';
                    html += '</td>';
                    html += '<td data-th="LINE TOTAL" class="border-b dark:border-darkmode-300 border-l border-r border-t px-4 py-2 lineTotal w-full sm:w-[120px] text-left sm:text-right font-medium max-sm:block">';
                        html += '<span class="line_total_html">-£'+lineTotal.toFixed(2)+'</span>';
                        html += '<input type="hidden" name="qot[discount][line_total]" class="line_total" value="'+lineTotal+'">';
                    html += '</td>';
                html += (!DiscountExist ? '</tr>' : '');

            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();
            quoteDiscountModal.hide();

            if(DiscountExist){
                $('#quoteItemsTable tbody tr.quoteDiscountRow').html(html);
            }else{
                $('#quoteItemsTable tbody').append(html);
            }
        
            calculateQuote();
        }
    });

    $('#quoteItemsTable').on('click', '.quoteDiscountRow', function(e){
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
        let theLabel = 'This quote has '+DueAmountHtml+' outstanding.'

        quoteDiscountModal.show();
        document.getElementById('quoteDiscountModal').addEventListener('shown.tw.modal', function(event){
            $('#quoteDiscountModal .dueLeft').html(theLabel);
            $('#quoteDiscountModal [name="discount_amount"]').val(unit_price);
            $('#quoteDiscountModal [name="max_discount"]').val(DueAmount);
            if(!isNonVatCheck){
                $('#quoteDiscountModal .discountVatField').fadeIn('fast', function(){
                    $('input', this).val(vat_rate);
                });
            }else{
                $('#quoteDiscountModal .discountVatField').fadeOut('fast', function(){
                    $('input', this).val(vat_rate);
                });
            }
            $('#quoteDiscountModal #removeDiscountBtn').fadeIn();
        });
    });

    $('#quoteDiscountModal #removeDiscountBtn').on('click', function(e){
        e.preventDefault();

        quoteDiscountModal.hide();
        $('#quoteItemsTable tbody tr.quoteDiscountRow').remove();
    
        calculateQuote();
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
            let theLabel = 'This quote has '+DueAmountHtml+' outstanding.'

            prePaymentQuoteModal.show();
            document.getElementById('prePaymentQuoteModal').addEventListener('shown.tw.modal', function(event){
                $('#prePaymentQuoteModal .dueLeft').html(theLabel);

                $('#prePaymentQuoteModal [name="advance_amount"]').val(AdvanceAmount);
                $('#prePaymentQuoteModal [name="advance_pay_date"]').val(AdvancePayDate);
                $('#prePaymentQuoteModal [name="max_advance"]').val(DueAmount);
                payment_method_id.addItem(AdvanceMethodId);
                $('#prePaymentQuoteModal #removeAdvanceBtn').fadeIn();
            });
        }else if(DueAmount > 0 && !AdvanceExist){
            let theLabel = 'This quote has '+DueAmountHtml+' outstanding.'
            prePaymentQuoteModal.show();
            document.getElementById('prePaymentQuoteModal').addEventListener('shown.tw.modal', function(event){
                $('#prePaymentQuoteModal .dueLeft').html(theLabel);
                $('#prePaymentQuoteModal [name="advance_amount"]').val(DueAmount);
                $('#prePaymentQuoteModal [name="advance_pay_date"]').val(getTodayDate());
                $('#prePaymentQuoteModal [name="max_advance"]').val(DueAmount);
            });
        }else{
            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Oops!");
                $("#warningModal .warningModalDesc").html('This quote does not have any due.');
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
        formError += ($('#prePaymentQuoteModal [name="advance_amount"]').val() == '' ? 1 : 0);
        formError += ($('#prePaymentQuoteModal [name="payment_method_id"]').val() == '' ? 1 : 0);
        formError += ($('#prePaymentQuoteModal [name="advance_pay_date"]').val() == '' ? 1 : 0);
        formError += ($('#prePaymentQuoteModal [name="advance_amount"]').val() > $('#prePaymentQuoteModal [name="max_advance"]').val() ? 1 : 0);

        if(formError > 0){
            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();

            $('#prePaymentQuoteModal .modal-body').remove('errorAlert');
            $('#prePaymentQuoteModal .modal-body').prepend('<div class="errorAlert col-span-12"><div role="alert" class="alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-2 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg><strong>Oops!&nbsp; Form validation error found. All fields are required and Amount can not grater than the outstanding amount.</div></div>');
        
            setTimeout(() => {
                $('#prePaymentQuoteModal .modal-body').remove('errorAlert');
            }, 2000);
        }else{
            let amount = $('#prePaymentQuoteModal [name="advance_amount"]').val() * 1;
            let method_id = $('#prePaymentQuoteModal [name="payment_method_id"]').val();
            let pay_date = $('#prePaymentQuoteModal [name="advance_pay_date"]').val();

            $theBtn.siblings('button').removeAttr('disabled');
            $theBtn.removeAttr('disabled');
            $('.theLoader', $theBtn).fadeOut();
            prePaymentQuoteModal.hide();

            $('.paidToDateField').addClass('hasPayment').fadeIn();
            $('#inv_advance_amount_html').html('£'+amount.toFixed(2));
            $('#inv_advance_amount').val(amount);
            $('#inv_payment_method_id').val(method_id);
            $('#inv_advance_date').val(pay_date);

        
            calculateQuote();
        }
    });

    $('#removeAdvanceBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        prePaymentQuoteModal.hide();
        $('#inv_advance_amount_html').html('£0.00');
        $('#inv_advance_amount').val('0');
        $('#inv_payment_method_id').val('0');
        $('#inv_advance_date').val('');
        $('.paidToDateField').removeClass('hasPayment').fadeOut();
    
        calculateQuote();
    })
    /* END: Pre Payment Item */

    function getLastRowId(){
        let $theTable = $('#quoteItemsTable');
        let serial = parseInt($theTable.find('.quoteItemRow').last().attr('data-id'), 10);

        return (serial ? serial + 1 : 1);
    }

    function updateQuoteRows(){
        let $theTable = $('#quoteItemsTable');

        $theTable.find('.quoteItemRow').each(function(){
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

        $theTable.find('.quoteDiscountRow').each(function(){
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

    function calculateQuote(){
        updateQuoteRows();

        let $theTable = $('#quoteItemsTable');

        let subTotal = 0;
        let vatTotal = 0;
        let Total = 0;
        let Due = 0;
        let DiscountTotal = 0;
        let DiscountVatTotal = 0;

        /* Calculate Rows */
        $theTable.find('.quoteItemRow').each(function(){
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
        
        $theTable.find('.quoteDiscountRow').each(function(){
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

        //let AdvanceAmount = ($('.paidToDateField').hasClass('hasPayment') ? parseFloat($('#inv_advance_amount').val()) : 0);
        

        Total = (!isNonVatCheck ? subTotal + vatTotal : subTotal);
        //Due = Total - AdvanceAmount;
        Due = Total;

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

    /* BEGIN: Save Quote */
    $('#JobQuoteForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('JobQuoteForm');
        let $theForm = $(this);
        let type = $theForm.find('[name="submit_type"]').val();

        $('.formSubmits', $theForm).attr('disabled', 'disabled');
        $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeIn();
        $('.formSubmits.submit_'+type, $theForm).addClass('active');

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('quote.store'),
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
    /* END: Save Quote */

    /* BEGIN: Quote To Invoice */
    $('#convertQuotToInvBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let quote_id = $theBtn.attr('data-id');

        $('.theLoader', $theBtn).fadeIn();
        $theBtn.addClass('active');
        $theBtn.siblings('button').attr('disabled', 'disabled');

        axios({
            method: "post",
            url: route('quote.convert.to.invoice'),
            data: { quote_id : quote_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
                $('.theLoader', $theBtn).fadeOut();
                $theBtn.removeClass('active');
                $theBtn.siblings('button').removeAttr('disabled');

            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    if(response.data.red){
                        window.location.href = response.data.red;
                    }
                }, 1500);
            }
        }).catch(error => {
            $('.theLoader', $theBtn).fadeOut();
            $theBtn.removeClass('active');
            $theBtn.siblings('button').removeAttr('disabled');

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
    /* END: Quote To Invoice */

})();