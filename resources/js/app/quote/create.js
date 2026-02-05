import INTAddressLookUps from "../../address_lookup";

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        INTAddressLookUps();
    });
    //localStorage.clear();

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const customerJobAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#customerJobAddressModal"));
    const addJobAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addJobAddressModal"));
    const customerListModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#customerListModal"));
    
    
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

    document.getElementById("customerListModal").addEventListener("hide.tw.modal", function (event) {
        $('#customerListModal .customerSearchWrap').fadeOut('fast', function(e){
            $('input', this).val('');
        });
        $('#customerListModal .customersListWrap').html('');
    });

    document.getElementById("customerJobAddressModal").addEventListener("hide.tw.modal", function (event) {
        $('#customerJobAddressModal .customerJobAddressWrap').html('');
        $('#customerJobAddressModal .addJobAddressBtn').attr('data-customer-id', '0');
    });

    document.getElementById("addJobAddressModal").addEventListener("hide.tw.modal", function (event) {
        $('#addJobAddressModal input[type="text"]').val('');
        $('#addJobAddressModal input[name="customer_id"]').val('0');
    });



    /* Init Variables Start */
    let customer_id = 0;
    let job_id = 0;
    let job_form_id = $(document).find('[name="job_form_id"]').val();
    /* Init Variables End */

    /* On Click Linked Job Start */
    if(localStorage.job){
        let job = localStorage.getItem('job');
        let jobObj = JSON.parse(job);
        job_id = (jobObj.id ? jobObj.id : 0);

        if(job_id > 0){
            $('.linkedJobWrap').fadeIn('fast', function(){
                $('.jobWrap').find('.theDesc').html((jobObj.description ? jobObj.description : 'Click here to select a job')).addClass('font-medium');
                $('.jobWrap').find('.theId').val(job_id);
            })
        }else{
            $('.linkedJobWrap').fadeOut('fast', function(){
                $('.jobWrap').find('.theDesc').html('').removeClass('font-medium');
                $('.jobWrap').find('.theId').val('');
            })
        }
    }
    /*On Click Linked Job End */

    /* Storage Trigger Start */
    $(document).on('click', '.theStorageTrigger', function(e){
        e.preventDefault();
        let $theTrigger = $(this);
        let theKey = $theTrigger.attr('data-key');
        let theValue = $theTrigger.attr('data-value');

        localStorage.setItem(theKey, theValue);
        window.location.href = $theTrigger.attr('href');
    });
    /* Storage Trigger End */

    /* On Load Check & Set Customer Start */
    if(localStorage.customer){
        let customer = localStorage.getItem('customer');
        let customerObj = JSON.parse(customer);
            customer_id = customerObj.id;

        $('.customerWrap').find('.theDesc').html(customerObj.full_name).addClass('font-medium');
        $('.customerWrap').find('.theId').val(customer_id);

        $('.customerAddressWrap').fadeIn('fast', function(){
            $('.theDesc', this).html('Click here to add billing address').addClass('font-medium');
            $('.theId', this).val(0);
        })
        $('.customerPropertyWrap').fadeIn('fast', function(){
            $('.theDesc', this).html('Click here to add job address');
            $('.theId', this).val(0);
        })

    }
    $('.customerBlock').on('click', function(e){
        e.preventDefault();
        let $theCustomerBlock = $(this);
        let job_form_id = $('#quoteForm [name="job_form_id"]').val();
        
        $.ajax({
            type: 'POST',
            data: {job_form_id : job_form_id},
            url: route('quotes.get.customers'),
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            async: false,
            success: function(data) {
                customerListModal.show();
                document.getElementById("customerListModal").addEventListener("shown.tw.modal", function (event) {
                    $('#customerListModal .customerSearchWrap').fadeIn('fast', function(e){
                        $('input', this).val('');
                    });
                    $('#customerListModal .customersListWrap').html(data.html);
                });
            },
            error:function(e){
                console.log('Error');
            }
        });
    });
    $('#customerListModal').on('click', '.customerItem', function(e){
        e.preventDefault();
        let $theCustomer = $(this);

        if(!$theCustomer.hasClass('disabled')){
            $('#customerListModal .customerItem').addClass('disabled')

            $theCustomer.find('.theIcon').fadeOut();
            $theCustomer.find('.theLoader').fadeIn();

            let theCustomerId = $theCustomer.attr('data-id');
            let theCustomerDescription = $theCustomer.attr('data-description');

            
            $.ajax({
                type: 'POST',
                data: {customer_id : theCustomerId},
                url: route('quotes.linked.customer'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                success: function(data) {
                    $theCustomer.find('.theLoader').fadeOut();
                    $theCustomer.find('.theIcon').fadeIn();

                    let customerObj = data.customer;
                        customer_id = customerObj.id;
                        job_id = 0;
                    localStorage.setItem('customer', JSON.stringify(customerObj));
                    localStorage.removeItem('job');
                    localStorage.removeItem('job_address');
                    localStorage.removeItem('billing_address');

                    let billingAddressObj = customerObj.address;
                    let billingAddressId = billingAddressObj.id
                    let billingAddress = (billingAddressObj.address_line_1 != null ? billingAddressObj.address_line_1+' ' : '');
                        billingAddress += (billingAddressObj.address_line_2 != null ? billingAddressObj.address_line_2+', ' : '');
                        billingAddress += (billingAddressObj.city != null ? billingAddressObj.city+', ' : '');
                        billingAddress += (billingAddressObj.state != null ? billingAddressObj.state+', ' : '');
                        billingAddress += (billingAddressObj.postal_code != null ? billingAddressObj.postal_code : '');
                    localStorage.setItem('billing_address', JSON.stringify(billingAddressObj));
                    $('.customerAddressWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html(billingAddress).addClass('font-medium');
                        $('.theId', this).val(billingAddressId);
                    })

                    $('.jobWrap').find('.theDesc').html('Click here to select a job').removeClass('font-medium');
                    $('.jobWrap').find('.theId').val(0);

                    $('.customerWrap').find('.theDesc').html(customerObj.full_name).addClass('font-medium');
                    $('.customerWrap').find('.theId').val(customer_id);

                    $('.customerPropertyWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html('Click here to add job address').removeClass('font-medium');
                        $('.theId', this).val(0);
                    })


                    customerListModal.hide();
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }
    });

    const textSearchCustomer = document.getElementById('search_customer');
    textSearchCustomer.addEventListener('input', function() {
        const customerSearchValue = this.value.toLowerCase();
        const customerItems = document.querySelectorAll('#customerListModal .customerItem');
        customerItems.forEach(theCustomer => {
            const customerName = theCustomer.getAttribute('data-description').toLowerCase();
            if(customerName.includes(customerSearchValue)){
                theCustomer.style.display = 'flex';
                theCustomer.classList.add('inSearch');
                theCustomer.classList.remove('notInSearch');
            }else{
                theCustomer.style.display = 'none';
                theCustomer.classList.add('notInSearch');
                theCustomer.classList.remove('inSearch');
            }
        });

        checkHasInSearchItem();
    });

    function checkHasInSearchItem(){
        $('#customerListModal').find('.customersContainer').each(function(){
            if($(this).find('.inSearch').length > 0){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    }
    /* On Load Check & Set Customer End */

    /* Customer Billing Address Start */
    if(localStorage.billing_address){
        let billing_address = localStorage.getItem('billing_address');
        let billingAddressObj = JSON.parse(billing_address);

        let billingAddress = '';
        let billingAddressId = billingAddressObj.id
        billingAddress += (billingAddressObj.address_line_1 != null ? billingAddressObj.address_line_1+' ' : '');
        billingAddress += (billingAddressObj.address_line_2 != null ? billingAddressObj.address_line_2+', ' : '');
        billingAddress += (billingAddressObj.city != null ? billingAddressObj.city+', ' : '');
        billingAddress += (billingAddressObj.state != null ? billingAddressObj.state+', ' : '');
        billingAddress += (billingAddressObj.postal_code != null ? billingAddressObj.postal_code : '');
        
        $('.customerAddressWrap').fadeIn('fast', function(){
            $('.theDesc', this).html(billingAddress).addClass('font-medium');
            $('.theId', this).val(billingAddressId);
        })
    }
    $(document).on('click', '.customerAddressBlock', function(e){
        e.preventDefault();
        let $thecustomerAddressBlock = $(this);
        
        if(localStorage.customer && customer_id > 0){
            $.ajax({
                type: 'POST',
                data: {customer_id : customer_id},
                url: route('quotes.get.job.addresses'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                success: function(data) {
                    customerJobAddressModal.show();
                    document.getElementById("customerJobAddressModal").addEventListener("shown.tw.modal", function (event) {
                        $('#customerJobAddressModal .customerJobAddressWrap').html(data.html);
                        $('#customerJobAddressModal .addJobAddressBtn').attr('data-customer-id', customer_id).attr('data-type', 'billing');
                        $('#customerJobAddressModal input[name="address_type"]').val('billing');
                    });
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }
    });
    /* Customer Billing Address End */

    /* On Click Add Customer Address Start */
    if(localStorage.job_address){
        let job_address = localStorage.getItem('job_address');
        let jobAddressObj = JSON.parse(job_address);

        let theJobAddress = '';
        theJobAddress += (jobAddressObj.address_line_1 != null ? jobAddressObj.address_line_1+' ' : '');
        theJobAddress += (jobAddressObj.address_line_2 != null ? jobAddressObj.address_line_2+', ' : '');
        theJobAddress += (jobAddressObj.city != null ? jobAddressObj.city+', ' : '');
        theJobAddress += (jobAddressObj.state != null ? jobAddressObj.state+', ' : '');
        theJobAddress += (jobAddressObj.postal_code != null ? jobAddressObj.postal_code : '');
        $('.customerPropertyWrap').fadeIn('fast', function(){
            $('.theDesc', this).html(theJobAddress).addClass('font-medium');
            $('.theId', this).val(jobAddressObj.id);
        });

    }
    $(document).on('click', '.customerPropertyBlock', function(e){
        e.preventDefault();
        let $thecustomerPropertyBlock = $(this);
        
        if(localStorage.customer && customer_id > 0){
            $.ajax({
                type: 'POST',
                data: {customer_id : customer_id},
                url: route('quotes.get.job.addresses'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                success: function(data) {
                    customerJobAddressModal.show();
                    document.getElementById("customerJobAddressModal").addEventListener("shown.tw.modal", function (event) {
                        $('#customerJobAddressModal .customerJobAddressWrap').html(data.html);
                        $('#customerJobAddressModal .addJobAddressBtn').attr('data-customer-id', customer_id).attr('data-type', 'job');
                        $('#customerJobAddressModal input[name="address_type"]').val('job');
                    });
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }
    });
    /* On Click Add Customer Address End */

    /* Address On Choose Start */
    $('#customerJobAddressModal').on('click', '.customerJobAddressItem', function(e){
        e.preventDefault();
        let $theAddress = $(this);
        let theAddressId = $theAddress.attr('data-id');
        let theAddress = $theAddress.attr('data-address');
        let type = $('#customerJobAddressModal input[name="address_type"]').val();
        let jobAddressObj = $theAddress.attr('data-address-obj') != '' ? JSON.parse($theAddress.attr('data-address-obj')) : [];

        customerJobAddressModal.hide();
        if(type == 'billing'){
            localStorage.setItem('billing_address', JSON.stringify(jobAddressObj));
            $('.customerAddressWrap').fadeIn('fast', function(){
                $('.theDesc', this).html(theAddress).addClass('font-medium');
                $('.theId', this).val(theAddressId);
            })
        }else{
            localStorage.setItem('job_address', JSON.stringify(jobAddressObj));
            $('.customerPropertyWrap').fadeIn('fast', function(){
                $('.theDesc', this).html(theAddress).addClass('font-medium');
                $('.theId', this).val(theAddressId);
            });
        }

    });
    /* Address On Choose End */

    /* On Click Add Job Address Start */
    $('.addJobAddressBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let the_customer_id = $theBtn.attr('data-customer-id');
        let type = $theBtn.attr('data-type');

        customerJobAddressModal.hide();

        addJobAddressModal.show();
        document.getElementById('addJobAddressModal').addEventListener('shown.tw.modal', function(event){
            $('#addJobAddressModal input[name="customer_id"]').val(the_customer_id);
            $('#addJobAddressModal input[name="address_type"]').val(type);
        });
    });

    $('#addJobAddressForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addJobAddressForm');
        const $theForm = $(this);
        let type = $theForm.find('input[name="address_type"]').val();
        
        $('#addressSaveBtn', $theForm).attr('disabled', 'disabled');
        $("#addressSaveBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('quotes.store.job.addresses'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#addressSaveBtn', $theForm).removeAttr('disabled');
            $("#addressSaveBtn .theLoader").fadeOut();

            if (response.status == 200) {
                let address = response.data.address;
                let address_id = response.data.id;
                let theJobAddress = '';
                theJobAddress += (address.address_line_1 != null ? address.address_line_1+' ' : '');
                theJobAddress += (address.address_line_2 != null ? address.address_line_2+', ' : '');
                theJobAddress += (address.city != null ? address.city+', ' : '');
                theJobAddress += (address.state != null ? address.state+', ' : '');
                theJobAddress += (address.postal_code != null ? address.postal_code : '');

                addJobAddressModal.hide();
                if(type == 'billing'){
                    $('.customerAddressWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html(theJobAddress).addClass('font-medium');
                        $('.theId', this).val(address_id);
                    })
                }else{
                    $('.customerPropertyWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html(theJobAddress).addClass('font-medium');
                        $('.theId', this).val(address_id);
                    })
                }

                
            }
        }).catch(error => {
            $('#addressSaveBtn', $theForm).removeAttr('disabled');
            $("#addressSaveBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#addJobAddressForm .${key}`).addClass('border-danger');
                        $(`#addJobAddressForm  .error-${key}`).html(val);
                    }
                } else {
                    console.log('error');
                }
            }
        });
    })
    /* On Click Add Job Address End */


    /* Auto Load Certificate Edit Data Start */
    if(localStorage.quote_id && localStorage.getItem('quote_id') > 0){
        $('#quote_id').val(localStorage.getItem('quote_id'));
        if(localStorage.quote){
            // populate quote data
        }
    }else{
        $('#quote_id').val(0);
    }
    /* Auto Load Certificate Edit Data End */

    /* Auto Load Quote Edit Data Start */
    if(localStorage.quote_id && localStorage.getItem('quote_id') > 0){
        $('#quote_id').val(localStorage.getItem('quote_id'));
        if(localStorage.quoteDetails){
            let quoteDetails = localStorage.getItem('quoteDetails');
            let quoteObj = JSON.parse(quoteDetails);

            $('#non_vat_quote').val((quoteObj.non_vat_quote ? quoteObj.non_vat_quote : '0'))
            $('#vat_number').val((quoteObj.vat_number ? quoteObj.vat_number : ''))
            //$('#issued_date').val((quoteObj.issued_date ? quoteObj.issued_date : getTodayDate()))
            
        }
    }else{
        $('#quote_id').val(0);
    }
    /* Auto Load Quote Edit Data End */


    

    function getTodayDate(){
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1;
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        return dd+'-'+mm+'-'+yyyy;
    }

    function getNextYearDate(){
        const today = new Date();
        today.setFullYear(today.getFullYear() + 1);

        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1;
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        return dd+'-'+mm+'-'+yyyy;
    }

    /* Submit the Form */
    $(document).on('click', '#saveQuoteBtn', function(e){
        e.preventDefault();
        let job_form_id = $('#quoteForm').find('[name="job_form_id"]').val();
        if(job_form_id == 3 || job_form_id == 4){
            $('#quoteForm').trigger('submit');
        }else{
            $('.gsfSignature .sign-pad-button-submit').trigger('click');
        }
    });

    $('.gsfSignature .sign-pad-button-submit').on('click', function(){
        console.log('clicked');
    })

    $('#quoteForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('quoteForm');
        const $theForm = $(this);
        let jobFormId = $theForm.find('input[name="job_form_id"]').val();
        
        $('#saveQuoteBtn', $theForm).attr('disabled', 'disabled');
        $("#saveQuoteBtn .theLoader").fadeIn();

        let formValidation = validateCurrentForm(jobFormId);
        let errors = formValidation.errors;
        let formData = formValidation.formData;
        

        if($.isEmptyObject(errors)){
            axios({
                method: "post",
                url: route('quotes.store'),
                data: formData,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                // console.lor(response.data);
                // return false;
                
                if (response.status == 200) {
                    localStorage.clear();
                    window.location.href = response.data.red;
                }
            }).catch(error => {
                $('#saveQuoteBtn', $theForm).removeAttr('disabled');
                $("#saveQuoteBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#quoteForm .${key}`).addClass('border-danger');
                            $(`#quoteForm  .error-${key}`).html(val);
                        }
                    } else if (error.response.status == 304) {
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
        }else{
            let messages = '';
            for (const [key, value] of Object.entries(errors)) {
                if(value != ''){
                    messages += value+' ';
                }
            }

            $('#saveQuoteBtn', $theForm).removeAttr('disabled');
            $("#saveQuoteBtn .theLoader").fadeOut();

            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Validation Error Found!");
                $("#warningModal .warningModalDesc").html((messages != '' ? messages : 'Form falidation issue found! Please fill out all required data.'));
            });

            setTimeout(() => {
                warningModal.hide();
            }, 1500);
        }
    })

    function validateCurrentForm(jobFormId){
        const form = document.getElementById('quoteForm');
        const $theForm = $('#quoteForm');
        let formData = new FormData(form);

        let errors = {};
        let options = {};
        if($theForm.find('[name="customer_id"]').val() == 0 || $theForm.find('[name="customer_id"]').val() == ''){
            errors['customer_id'] = 'Please select a customer.';
        }
        if($theForm.find('[name="customer_address_id"]').val() == 0 || $theForm.find('[name="customer_address_id"]').val() == ''){
            errors['customer_address_id'] = 'Billing address can not be empty.';
        }
        // if($theForm.find('[name="customer_property_id"]').val() == 0 || $theForm.find('[name="customer_property_id"]').val() == ''){
        //     errors['customer_property_id'] = 'Job address can not be empty.';
        // }

        // Quote related Validation
        if(localStorage.quoteItems){
            let quoteItems = localStorage.getItem('quoteItems');
            options['quoteItems'] = quoteItems;
            //formData.append('quoteItems', quoteItems);
        }else{
            errors['item_error'] = 'Please add at least one item for this quote.&nbsp;';
        }
        if(localStorage.quoteDiscounts){
            let quoteDiscounts = localStorage.getItem('quoteDiscounts');
            options['quoteDiscounts'] = quoteDiscounts;
            //formData.append('quoteDiscounts', quoteDiscounts);
        }
        // if(localStorage.quoteNotes){
        //     let quoteNotes = localStorage.getItem('quoteNotes');
        //     options['quoteNotes'] = quoteNotes;
        //     //formData.append('quoteNotes', JSON.parse(quoteNotes));
        // }

        formData.append('options', JSON.stringify(options));
        return { errors : errors, formData : formData};
    }
})()