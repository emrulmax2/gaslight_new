import INTAddressLookUps from "../../address_lookup";

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        INTAddressLookUps();
    });

    
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
    const quoteItemModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#quoteItemModal"));
    const quoteDiscountModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#quoteDiscountModal"));
    const quoteNoteModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#quoteNoteModal"));
    
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
    let isVatQuote = ($('#quoteForm #non_vat_quote').val() == 1 ? false : true);
    quoteCalculation();
    /* Init The Calculation */

    /* Generate Or Autoload Quote Number Start */
    if(localStorage.quote_number && localStorage.getItem('quote_number') != ''){
        let quoteNumber = JSON.parse(localStorage.getItem('quote_number'));
        $('.quoteNoBlockWrap').fadeIn('fast', function(){
            $('.quoteNoBlock').find('.theDesc').html(quoteNumber).addClass('font-medium');
        });
    }else{
        $('.quoteNoBlockWrap').fadeOut('fast', function(){
            $('.quoteNoBlock').find('.theDesc').html('').removeClass('font-medium');
        });
    }
    /* Generate Or Autoload Quote Number End */

    /* Quote Item Manager Start */
    document.getElementById('quoteItemModal').addEventListener('hide.tw.modal', function(event) {
        $('#quoteItemModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#quoteItemModal textarea').val('');
        $('#quoteItemModal input[name="qut_item_serial"]').val('1');
        $('#quoteItemModal input[name="edit"]').val('0');
        $('#quoteItemModal #removeItemBtn').fadeOut();

        if(isVatQuote){
            $('#quoteItemModal .vatWrap').fadeIn('fast', function(){
                $('input', this).val(20);
            })
        }else{
            $('#quoteItemModal .vatWrap').fadeOut('fast', function(){
                $('input', this).val(0);
            })
        }
    });

    if(localStorage.quoteItems){
        let quoteItemsCount = localStorage.getItem('quoteItemsCount') * 1;
        let quoteItems = localStorage.getItem('quoteItems');
        let quoteItemsObj = JSON.parse(quoteItems);

        if(Object.keys(quoteItemsObj).length > 0){
            for (const [qut_item_serial, invItem] of Object.entries(quoteItemsObj)) {
                
                let units = invItem.units * 1;
                let unit_price = invItem.price * 1;
                let vat_rate = invItem.vat * 1;
                let line_total = invItem.line_total * 1;
                let quoteItemBlock = '';
                quoteItemBlock += '<div class="px-2 py-4 quoteItemWrap_'+qut_item_serial+' bg-white" style="margin-top: 2px">';
                    quoteItemBlock += '<a data-key="'+qut_item_serial+'" href="javascript:void(0);" class="editQuoteItemBtn flex justify-between items-center cursor-pointer quoteItemBlock_'+qut_item_serial+'">';
                        quoteItemBlock += '<div class="theDesc font-medium">'+invItem.qut_item_title+'</div>';
                        quoteItemBlock += '<div style="flex: 0 0 150px; margin-left: auto;" class="font-medium inline-flex justify-end items-center">';
                            quoteItemBlock += '<span>'+units+' x £'+unit_price.toFixed(2)+'</span>';
                            quoteItemBlock += '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil stroke-1.5 h-3 w-3 text-success"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg></span>';
                        quoteItemBlock += '</div>';
                    quoteItemBlock += '</a>';
                quoteItemBlock += '</div>';

                $('.allItemsWrap').fadeIn('fast', function(){
                    $('.allItemsWrap').append(quoteItemBlock);
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
        if(localStorage.quoteItems){
            let quoteItems = localStorage.getItem('quoteItems');
            let quoteItemsObj = JSON.parse(quoteItems);
            let serials = Object.keys(quoteItemsObj);
                serials.sort(function(a, b){return a - b})
            serial = (serials[serials.length - 1] * 1) + 1;
        }
        
        localStorage.setItem('quoteItemsCount', serial);
        
        quoteItemModal.show();
        document.getElementById("quoteItemModal").addEventListener("shown.tw.modal", function (event) {
            $('#quoteItemModal input[name="qut_item_serial"]').val(serial);
            $('#quoteItemModal input[name="edit"]').val(0);
            $('#quoteItemModal #removeItemBtn').fadeOut();

            if(isVatQuote){
                $('#quoteItemModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(20);
                })
            }else{
                $('#quoteItemModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                })
            }
        });
    });

    $(document).on('click', '.editQuoteItemBtn', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);
        let theSerial = $theModalBtn.attr('data-key');

        let quoteItems = localStorage.getItem('quoteItems');
        let quoteItemsObj = JSON.parse(quoteItems);
        let theItem = quoteItemsObj[theSerial];

        quoteItemModal.show();
        document.getElementById("quoteItemModal").addEventListener("shown.tw.modal", function (event) {
            $('#quoteItemModal input[name="qut_item_serial"]').val(theSerial);
            $('#quoteItemModal input[name="edit"]').val(theSerial);
            $('#quoteItemModal #removeItemBtn').fadeIn();

            if(isVatQuote){
                $('#quoteItemModal .vatWrap').fadeIn('fast', function(){
                    $('input', this).val(20);
                })
            }else{
                $('#quoteItemModal .vatWrap').fadeOut('fast', function(){
                    $('input', this).val(0);
                })
            }
        
            for (const [key, value] of Object.entries(theItem)) {
                let $theInput = $('#quoteItemModal [name="'+key+'"]');
                if($theInput.is('textarea')){
                    $theInput.val(value ? value : '');
                }else{
                    if($theInput.attr('type') == 'radio'){
                        $('#quoteItemModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                    }else{
                        if(key != 'qut_item_serial' && key != 'edit'){
                            $theInput.val(value ? value : '');
                        }
                    }
                }
            }
        });
    });

    $('#quoteItemForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('quoteItemForm');
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
            let qut_item_serial = $theForm.find('[name="qut_item_serial"]').val() * 1;
            let edit = $theForm.find('[name="edit"]').val();

            let form_data = $theForm.serializeArray();
            let formated_data = getFormatedData(form_data);

            let unit = $theForm.find('[name="units"]').val() * 1;
            let price = $theForm.find('[name="price"]').val() * 1;
            let vat = ($theForm.find('[name="vat"]').val() != '' && $theForm.find('[name="vat"]').val() > 0 ? $theForm.find('[name="vat"]').val() * 1 : 0);
            let unit_total = price * unit;
            let vat_total = (unit_total * vat) / 100;
            let line_total = unit_total + vat_total;

            let qut_item_title = ($theForm.find('[name="description"]').val() != '' ? $theForm.find('[name="description"]').val() : qut_item_serial+' Line Item');
            formated_data['qut_item_title'] = qut_item_title;
            formated_data['vat'] = vat;
            formated_data['line_total'] = line_total;
            
            if(edit > 0){
                let quoteItems = localStorage.getItem('quoteItems');
                let quoteItemsObj = JSON.parse(quoteItems);
                quoteItemsObj[qut_item_serial] = formated_data;

                localStorage.setItem('quoteItems', JSON.stringify(quoteItemsObj));

                let quoteItemBlock = ''
                    quoteItemBlock += '<a data-key="'+qut_item_serial+'" href="javascript:void(0);" class="editQuoteItemBtn flex justify-between items-center cursor-pointer quoteItemBlock_'+qut_item_serial+'">';
                        quoteItemBlock += '<div class="theDesc font-medium">'+qut_item_title+'</div>';
                        quoteItemBlock += '<div style="flex: 0 0 150px; margin-left: auto;" class="font-medium inline-flex justify-end items-center">';
                            quoteItemBlock += '<span>'+unit+' x £'+price.toFixed(2)+'</span>';
                            quoteItemBlock += '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil stroke-1.5 h-3 w-3 text-success"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg></span>';
                        quoteItemBlock += '</div>';
                    quoteItemBlock += '</a>';
                $('.allItemsWrap').find('.quoteItemWrap_'+qut_item_serial).html(quoteItemBlock);
            }else{
                if(localStorage.quoteItems){
                    let quoteItems = localStorage.getItem('quoteItems');
                    let quoteItemsObj = JSON.parse(quoteItems);
                    quoteItemsObj[qut_item_serial] = formated_data

                    localStorage.setItem('quoteItems', JSON.stringify(quoteItemsObj));
                    localStorage.setItem('quoteItemsCount', qut_item_serial);
                }else{
                    let quoteItemsObj = {};
                    quoteItemsObj[qut_item_serial] = formated_data;

                    localStorage.setItem('quoteItems', JSON.stringify(quoteItemsObj));
                    localStorage.setItem('quoteItemsCount', qut_item_serial);
                }
                let quoteItemBlock = '';
                    quoteItemBlock += '<div class="px-2 py-4 quoteItemWrap_'+qut_item_serial+' bg-white" style="margin-top: 2px">';
                        quoteItemBlock += '<a data-key="'+qut_item_serial+'" href="javascript:void(0);" class="editQuoteItemBtn flex justify-between items-center cursor-pointer quoteItemBlock_'+qut_item_serial+'">';
                            quoteItemBlock += '<div class="theDesc font-medium">'+qut_item_title+'</div>';
                            quoteItemBlock += '<div style="flex: 0 0 150px; margin-left: auto;" class="font-medium inline-flex justify-end items-center">';
                                quoteItemBlock += '<span>'+unit+' x £'+price.toFixed(2)+'</span>';
                                quoteItemBlock += '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil stroke-1.5 h-3 w-3 text-success"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg></span>';
                            quoteItemBlock += '</div>';
                        quoteItemBlock += '</a>';
                    quoteItemBlock += '</div>';
                $('.allItemsWrap').fadeIn('fast', function(){
                    $('.allItemsWrap').append(quoteItemBlock);
                });
            }

            let quoteItemsCount = localStorage.getItem('quoteItemsCount');
            
            $theForm.find('.acc__input-error').fadeOut().html('');
            $('#saveItemBtn', $theForm).removeAttr('disabled');
            $("#saveItemBtn .theLoader").fadeOut();
            quoteCalculation();
            quoteItemModal.hide();
        }
    });

    $('#removeItemBtn').on('click', function(e){
        e.preventDefault();
        let theSerial = $('#quoteItemModal input[name="qut_item_serial"]').val();

        if(theSerial > 0){
            let quoteItemsCount = localStorage.getItem('quoteItemsCount') * 1;
            let quoteItems = localStorage.getItem('quoteItems');
            let quoteItemsObj = JSON.parse(quoteItems);
            delete quoteItemsObj[theSerial];

            quoteItemsCount -= 1;
            localStorage.setItem('quoteItemsCount', quoteItemsCount);
            localStorage.setItem('quoteItems', JSON.stringify(quoteItemsObj));

            $('.allItemsWrap').find('.quoteItemWrap_'+theSerial).remove();
            quoteCalculation();
            quoteItemModal.hide();
        }
    })
    /* Quote Item Manager End */


    /* Quote Discount Start */
    if(localStorage.quoteDiscounts){
        let quoteDiscounts = localStorage.getItem('quoteDiscounts');
        let quoteDiscountsObj = JSON.parse(quoteDiscounts);
        let discountAmount = quoteDiscountsObj.amount * 1;
        
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
    
    document.getElementById('quoteDiscountModal').addEventListener('hide.tw.modal', function(event) {
        $('#quoteDiscountModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#quoteDiscountModal #removeDiscountBtn').fadeOut();

        // if(isVatQuote){
        //     $('#quoteDiscountModal .vatWrap').fadeIn('fast', function(){
        //         $('input', this).val(20);
        //     })
        // }else{
        //     $('#quoteDiscountModal .vatWrap').fadeOut('fast', function(){
        //         $('input', this).val(0);
        //     })
        // }
    });

    $(document).on('click', '.discountItemBlock', function(e){
        e.preventDefault();
        let quoteDiscounts = localStorage.getItem('quoteDiscounts');

        let totalDue = getDueAmountForDiscount();
        let theLabel = 'This quote has £'+totalDue.toFixed(e)+' outstanding.'
        quoteDiscountModal.show();
        document.getElementById("quoteDiscountModal").addEventListener("shown.tw.modal", function (event) {
            $('#quoteDiscountModal .dueLeft').html(theLabel);
            $('#quoteDiscountModal input[name="max_discount"]').val(totalDue);
            // if(isVatQuote){
            //     $('#quoteDiscountModal .vatWrap').fadeIn('fast', function(){
            //         $('input', this).val(20);
            //     })
            // }else{
            //     $('#quoteDiscountModal .vatWrap').fadeOut('fast', function(){
            //         $('input', this).val(0);
            //     })
            // }
            if(localStorage.quoteDiscounts){
                $('#quoteDiscountModal #removeDiscountBtn').fadeIn();
                let quoteDiscountsObj = JSON.parse(quoteDiscounts);
                if(!$.isEmptyObject(quoteDiscountsObj)){
                    for (const [key, value] of Object.entries(quoteDiscountsObj)) {
                        let $theInput = $('#quoteDiscountModal [name="'+key+'"]');
                        if($theInput.is('textarea')){
                            $theInput.val(value ? value : '');
                        }else{
                            if($theInput.attr('type') == 'radio'){
                                $('#quoteDiscountModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                            }else{
                                if(key != 'max_discount'){
                                    $theInput.val(value ? value : '');
                                }
                            }
                        }
                    }
                }
            }else{
                $('#quoteDiscountModal #removeDiscountBtn').fadeOut();
            }
        });
    })

    $('.addDiscountBtn').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let totalDue = getDueAmountForDiscount();
        if(totalDue > 0){
            let theLabel = 'This quote has £'+totalDue.toFixed(e)+' outstanding.'
            quoteDiscountModal.show();
            document.getElementById("quoteDiscountModal").addEventListener("shown.tw.modal", function (event) {
                $('#quoteDiscountModal .dueLeft').html(theLabel);
                $('#quoteDiscountModal input[name="amount"]').val(totalDue);
                $('#quoteDiscountModal input[name="max_discount"]').val(totalDue);
                // if(isVatQuote){
                //     $('#quoteDiscountModal .vatWrap').fadeIn('fast', function(){
                //         $('input', this).val(20);
                //     })
                // }else{
                //     $('#quoteDiscountModal .vatWrap').fadeOut('fast', function(){
                //         $('input', this).val(0);
                //     })
                // }
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
        $('#quoteDiscountModal #removeDiscountBtn').fadeOut();
    });

    $('#quoteDiscountForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('quoteDiscountForm');
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
            //let vat = ($theForm.find('[name="vat"]').val() != '' && $theForm.find('[name="vat"]').val() > 0 ? $theForm.find('[name="vat"]').val() * 1 : 0);
            formated_data['qut_item_title'] = 'Discount';
            //formated_data['vat'] = vat;

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
            
            localStorage.setItem('quoteDiscounts', JSON.stringify(formated_data));
            $('.addDiscountBtn').fadeOut('');

            
            $('#saveDiscountBtn', $theForm).removeAttr('disabled');
            $("#saveDiscountBtn .theLoader").fadeOut();
            $theForm.find('.acc__input-error').html('').fadeOut();
            quoteCalculation();
            quoteDiscountModal.hide();
        }
    }); 

    $('#removeDiscountBtn').on('click', function(e){
        e.preventDefault();
        
        localStorage.removeItem('quoteDiscounts');
        $('.allDiscountItemWrap').fadeOut('fast').html('');
        $('.addDiscountBtn').fadeIn('fast');

        quoteCalculation();
        quoteDiscountModal.hide();
    })
    /* Quote Discount END */

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
        localStorage.removeItem('quoteNotes');
        if(date){
            let theDate = date.dateInstance.toLocaleDateString('en-GB').replace(/\//g, "-");
            localStorage.setItem('issued_date', JSON.stringify(theDate));
        }
    });
    /* Issued Date Load Start */

    /* Note Auto Load Start 
    document.getElementById('quoteNoteModal').addEventListener('hide.tw.modal', function(event) {
        $('#quoteNoteModal textarea').val('');
    });
    if(localStorage.quoteNotes){
        let quoteNotes = localStorage.getItem('quoteNotes');
        $('.quoteNoteBlock .theDesc').html((quoteNotes != '' ? JSON.parse(quoteNotes) : 'N/A'));
    }
    $('.quoteNoteBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        quoteNoteModal.show();
        document.getElementById("quoteNoteModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.quoteNotes){
                let quoteNotes = localStorage.getItem('quoteNotes');
                $('#quoteNoteModal [name="note"]').val(JSON.parse(quoteNotes));
            }else{
                $('#quoteNoteModal [name="note"]').val('');
            }   
        });
    });

    $('#quoteNoteForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('quoteNoteForm');
        const $theForm = $(this);

        $('#saveNoteBtn', $theForm).attr('disabled', 'disabled');
        $("#saveNoteBtn .theLoader").fadeIn();

        let note = $theForm.find('[name="note"]').val();

        localStorage.removeItem('quoteNotes');
        if(note != ''){
            localStorage.setItem('quoteNotes', JSON.stringify(note));
        }
        $('.quoteNoteBlock .theDesc').html((note != '' ? note : 'N/A'));


        $('#saveNoteBtn', $theForm).removeAttr('disabled');
        $("#saveNoteBtn .theLoader").fadeOut();
        quoteNoteModal.hide();
    });
    Note Auto Load End */

    /* Extras Auto Load Start */
    if(localStorage.quoteExtra && localStorage.quoteExtra != null){
        let quoteExtra = JSON.parse(localStorage.getItem('quoteExtra'));
        if(quoteExtra.vat_number != ''){
            $('#vat_number').val(quoteExtra.vat_number);
        }
        if(quoteExtra.non_vat_quote != ''){
            $('#non_vat_quote').val(quoteExtra.non_vat_quote);
        }
    }
    /* Extras Auto Load End */

    function quoteCalculation(){
        let quoteItems = getQuoteItemTotal();
        let quoteDiscounts = getQuoteDiscountTotal();

        let invoiteItemTotal = quoteItems.invoiteItemTotal;
        let quoteItemVatTotal = quoteItems.quoteItemVatTotal;

        let hasDiscount = quoteDiscounts.hasDiscount;
        let discountTotal = quoteDiscounts.discountTotal;

        let vatAmount = quoteItemVatTotal;

        let total = invoiteItemTotal - (hasDiscount ? discountTotal : 0) + (isVatQuote ? vatAmount : 0);
        let totalBalance = total;
        
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
            if(isVatQuote){
                $('.vatTotalBlock').fadeIn('fast', function(){
                    $('.theDesc', this).html('£'+vatAmount.toFixed(2))
                });
            }else{
                $('.vatTotalBlock').fadeOut('fast', function(){
                    $('.theDesc', this).html('£0.0');
                });
            }
        });

        $('.quoteTotalBlock .theDesc').html('£'+total.toFixed(2));
        $('.quoteBalanceBlock .theDesc').html('£'+totalBalance.toFixed(2));

        $('#quoteSubTotal').val(invoiteItemTotal);
        $('#quoteTotal').val(total);
        return {
            'totalBalance' : totalBalance
        };
    }

    function getQuoteItemTotal(){
        let invoiteItemTotal = 0;
        let quoteItemVatTotal = 0;
        if(localStorage.quoteItems){
            let quoteItems = localStorage.getItem('quoteItems');
            let quoteItemsObj = JSON.parse(quoteItems);
    
            if(Object.keys(quoteItemsObj).length > 0){
                for (const [qut_item_serial, invItem] of Object.entries(quoteItemsObj)) {
                    
                    let units = invItem.units * 1;
                    let unit_price = invItem.price * 1;
                    let vat_rate = invItem.vat * 1;
                    
                    let total = unit_price * units;
                    let vatTotal = (total * vat_rate) / 100;

                    invoiteItemTotal += total;
                    quoteItemVatTotal += vatTotal;
                }
            }
        }

        return {'invoiteItemTotal' : invoiteItemTotal, 'quoteItemVatTotal' : quoteItemVatTotal};
    }

    function getQuoteDiscountTotal(){
        let discountTotal = 0;
        let hasDiscount = 0;

        if(localStorage.quoteDiscounts){
            hasDiscount = 1;
            let quoteDiscounts = localStorage.getItem('quoteDiscounts');
            let quoteDiscountsObj = JSON.parse(quoteDiscounts);
            let discountAmount = quoteDiscountsObj.amount * 1;
            
            discountTotal = discountAmount;
        }

        return {'hasDiscount' : hasDiscount, 'discountTotal' : discountTotal};
    }

    function getDueAmountForDiscount(){
        let totalDue = 0;
        let quoteItems = getQuoteItemTotal();
        let quoteDiscounts = getQuoteDiscountTotal();

        let invoiteItemTotal = quoteItems.invoiteItemTotal;
        let quoteItemVatTotal = quoteItems.quoteItemVatTotal;

        let hasDiscount = quoteDiscounts.hasDiscount;
        let discountTotal = quoteDiscounts.discountTotal;

        let due = invoiteItemTotal;
            totalDue = due;

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