import INTAddressLookUps from '../../address_lookup.js';

(function(){
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }
    //localStorage.clear();

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const customerJobAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#customerJobAddressModal"));
    const addJobAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addJobAddressModal"));
    const customerListModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#customerListModal"));

    const jobAddressOccupantModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#jobAddressOccupantModal"));
    const addJobAddressOccupantModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addJobAddressOccupantModal"));

    const linkedJobModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#linkedJobModal"));
    
    
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

    document.getElementById("linkedJobModal").addEventListener("hide.tw.modal", function (event) {
        $('#linkedJobModal .linkedJobListWrap').html('');
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

    document.getElementById("addJobAddressOccupantModal").addEventListener("hide.tw.modal", function (event) {
        $('#addJobAddressOccupantModal input[type="text"]').val('');
        $('#addJobAddressOccupantModal input[name="customer_property_id"]').val('0');
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

        $('.jobWrap').find('.theDesc').html((jobObj.description ? jobObj.description : 'Click here to select a job')).addClass('font-medium');
        $('.jobWrap').find('.theId').val(job_id);
    }
    $(document).on('click', '.jobBlock', function(e){
        e.preventDefault();
        let $thejobBlock = $(this);
        let job_form_id = $('#invoiceForm [name="job_form_id"]').val();
        
        $.ajax({
            type: 'POST',
            data: {job_form_id : job_form_id},
            url: route('invoices.get.jobs'),
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            async: false,
            success: function(data) {
                linkedJobModal.show();
                document.getElementById("linkedJobModal").addEventListener("shown.tw.modal", function (event) {
                    $('#linkedJobModal .linkedJobListWrap').html(data.html);
                });
            },
            error:function(e){
                console.log('Error');
            }
        });
    });
    $('#linkedJobModal').on('click', '.customerJobItem', function(e){
        e.preventDefault();
        let $theJob = $(this);

        if(!$theJob.hasClass('disabled')){
            $('#linkedJobModal .customerJobItem').addClass('disabled')

            $theJob.find('.theIcon').fadeOut();
            $theJob.find('.theLoader').fadeIn();

            let theJobId = $theJob.attr('data-id');
            let theJobDescription = $theJob.attr('data-description');

            
            $.ajax({
                type: 'POST',
                data: {job_id : theJobId},
                url: route('invoices.linked.job'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                success: function(data) {
                    $theJob.find('.theLoader').fadeOut();
                    $theJob.find('.theIcon').fadeIn();

                    let job = data.row;
                    let customer = job.customer;
                        customer_id = customer.id;
                    let job_address = job.property;
                    let occupant = {
                        'customer_property_occupant_id' : job_address.id,
                        'occupant_email' : job_address.occupant_email,
                        'occupant_name' : job_address.occupant_name,
                        'occupant_phone' : job_address.occupant_phone
                    }
                    localStorage.setItem('job', JSON.stringify(job));
                    localStorage.setItem('customer', JSON.stringify(customer));
                    localStorage.setItem('job_address', JSON.stringify(job_address));
                    localStorage.setItem('occupant', JSON.stringify(occupant));

                    $('.jobWrap').find('.theDesc').html(job.description).addClass('font-medium');
                    $('.jobWrap').find('.theId').val(job.id);

                    $('.customerWrap').find('.theDesc').html(customer.full_name).addClass('font-medium');
                    $('.customerWrap').find('.theId').val(customer_id);

                    let customerAddressObj = customer.address;
                    let customerAddress = '';
                    customerAddress += (customerAddressObj.address_line_1 != null ? customerAddressObj.address_line_1+' ' : '');
                    customerAddress += (customerAddressObj.address_line_2 != null ? customerAddressObj.address_line_2+', ' : '');
                    customerAddress += (customerAddressObj.city != null ? customerAddressObj.city+', ' : '');
                    customerAddress += (customerAddressObj.state != null ? customerAddressObj.state+', ' : '');
                    customerAddress += (customerAddressObj.postal_code != null ? customerAddressObj.postal_code : '');
                    $('.customerAddressWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html(customerAddress).addClass('font-medium');
                        $('.theId', this).val(0);
                    })

                    let theJobAddress = '';
                    theJobAddress += (job_address.address_line_1 != null ? job_address.address_line_1+' ' : '');
                    theJobAddress += (job_address.address_line_2 != null ? job_address.address_line_2+', ' : '');
                    theJobAddress += (job_address.city != null ? job_address.city+', ' : '');
                    theJobAddress += (job_address.state != null ? job_address.state+', ' : '');
                    theJobAddress += (job_address.postal_code != null ? job_address.postal_code : '');
                    $('.customerPropertyWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html((theJobAddress != '' ? theJobAddress : 'Click here to add job address')).addClass((theJobAddress != '' ? 'font-medium' : ''));
                        $('.theId', this).val((job_address.id && job_address.id > 0 ? job_address.id : 0));
                    })
            
                    //if(job_form_id != 6 && job_form_id != 7 && job_form_id != 3 && job_form_id != 4){
                        $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                            $('.theDesc', this).html((occupant.occupant_name ? occupant.occupant_name : 'Click here to add job address occupant'));
                            $('.theId', this).val((occupant.customer_property_occupant_id ? occupant.customer_property_occupant_id : 0));
                        });
                    //}

                    linkedJobModal.hide();
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }
    });

    const textSearchJob = document.getElementById('search_job');
    textSearchJob.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const jobItems = document.querySelectorAll('#linkedJobModal .customerJobItem');
        jobItems.forEach(theJob => {
            const jobName = theJob.getAttribute('data-description').toLowerCase();
            if (jobName.includes(searchValue)) {
                theJob.style.display = 'flex';
            } else {
                theJob.style.display = 'none';
            }
        });
    });
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

        let customerAddressObj = customerObj.address;
        let customerAddress = '';
        customerAddress += (customerAddressObj.address_line_1 != null ? customerAddressObj.address_line_1+' ' : '');
        customerAddress += (customerAddressObj.address_line_2 != null ? customerAddressObj.address_line_2+', ' : '');
        customerAddress += (customerAddressObj.city != null ? customerAddressObj.city+', ' : '');
        customerAddress += (customerAddressObj.state != null ? customerAddressObj.state+', ' : '');
        customerAddress += (customerAddressObj.postal_code != null ? customerAddressObj.postal_code : '');

        $('.customerWrap').find('.theDesc').html(customerObj.full_name).addClass('font-medium');
        $('.customerWrap').find('.theId').val(customer_id);

        $('.customerAddressWrap').fadeIn('fast', function(){
            $('.theDesc', this).html(customerAddress).addClass('font-medium');
            $('.theId', this).val(0);
        })
        $('.customerPropertyWrap').fadeIn('fast', function(){
            $('.theDesc', this).html('Click here to add job address');
            $('.theId', this).val(0);
        })

        //if(job_form_id != 6 && job_form_id != 7 && job_form_id != 3 && job_form_id != 4){
        $('.customerPropertyOccupantWrap').fadeOut('fast', function(){
            $('.theDesc', this).html('Click here to add job address occupant');
            $('.theId', this).val(0);
        })
        //}
    }
    $('.customerBlock').on('click', function(e){
        e.preventDefault();
        let $theCustomerBlock = $(this);
        let job_form_id = $('#invoiceForm [name="job_form_id"]').val();
        
        $.ajax({
            type: 'POST',
            data: {job_form_id : job_form_id},
            url: route('invoices.get.customers'),
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
                url: route('invoices.linked.customer'),
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
                    localStorage.removeItem('occupant');

                    let customerAddressObj = customerObj.address;
                    let customerAddress = '';
                    customerAddress += (customerAddressObj.address_line_1 != null ? customerAddressObj.address_line_1+' ' : '');
                    customerAddress += (customerAddressObj.address_line_2 != null ? customerAddressObj.address_line_2+', ' : '');
                    customerAddress += (customerAddressObj.city != null ? customerAddressObj.city+', ' : '');
                    customerAddress += (customerAddressObj.state != null ? customerAddressObj.state+', ' : '');
                    customerAddress += (customerAddressObj.postal_code != null ? customerAddressObj.postal_code : '');

                    $('.jobWrap').find('.theDesc').html('Click here to select a job').removeClass('font-medium');
                    $('.jobWrap').find('.theId').val(0);

                    $('.customerWrap').find('.theDesc').html(customerObj.full_name).addClass('font-medium');
                    $('.customerWrap').find('.theId').val(customer_id);

                    $('.customerAddressWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html(customerAddress).addClass('font-medium');
                        $('.theId', this).val(0);
                    })
                    $('.customerPropertyWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html('Click here to add job address').removeClass('font-medium');
                        $('.theId', this).val(0);
                    })

                    //if(job_form_id != 6 && job_form_id != 7 && job_form_id != 3 && job_form_id != 4){
                        $('.customerPropertyOccupantWrap').fadeOut('fast', function(){
                            $('.theDesc', this).html('Click here to add job address occupant').removeClass('font-medium');
                            $('.theId', this).val(0);
                        })
                    //}

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

        if(localStorage.occupant){
            let occupant = localStorage.getItem('occupant');
            let occupantObj = JSON.parse(occupant);

            $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                $('.theDesc', this).html((occupantObj.occupant_name ? occupantObj.occupant_name : 'Click here to add job address occupant')).addClass('font-medium');
                $('.theId', this).val((occupantObj.customer_property_occupant_id ? occupantObj.customer_property_occupant_id : 0));
            })        
        }
    }
    $(document).on('click', '.customerPropertyBlock', function(e){
        e.preventDefault();
        let $thecustomerPropertyBlock = $(this);
        
        if(localStorage.customer && customer_id > 0){
            $.ajax({
                type: 'POST',
                data: {customer_id : customer_id},
                url: route('invoices.get.job.addresses'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                success: function(data) {
                    customerJobAddressModal.show();
                    document.getElementById("customerJobAddressModal").addEventListener("shown.tw.modal", function (event) {
                        $('#customerJobAddressModal .customerJobAddressWrap').html(data.html);
                        $('#customerJobAddressModal .addJobAddressBtn').attr('data-customer-id', customer_id);
                    });
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }
    });
    $('#customerJobAddressModal').on('click', '.customerJobAddressItem', function(e){
        e.preventDefault();
        let $theAddress = $(this);
        let theAddressId = $theAddress.attr('data-id');
        let theOccupantName = $theAddress.attr('data-occupant');
        let theAddress = $theAddress.attr('data-address');

        customerJobAddressModal.hide();
        $('.customerPropertyWrap').fadeIn('fast', function(){
            $('.theDesc', this).html(theAddress).addClass('font-medium');
            $('.theId', this).val(theAddressId);
        });

        //if(job_form_id != 6 && job_form_id != 7 && job_form_id != 3 && job_form_id != 4){
            $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                $('.theDesc', this).html('Click here to add job address occupant');
                $('.theId', this).val(0);
            });
        //}
    });
    /* On Click Add Customer Address End */

    /* On Click Add Job Address Start */
    $('.addJobAddressBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let the_customer_id = $theBtn.attr('data-customer-id');

        customerJobAddressModal.hide();

        addJobAddressModal.show();
        document.getElementById('addJobAddressModal').addEventListener('shown.tw.modal', function(event){
            $('#addJobAddressModal input[name="customer_id"]').val(the_customer_id);
        });
    });

    $('#addJobAddressForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addJobAddressForm');
        const $theForm = $(this);
        
        $('#addressSaveBtn', $theForm).attr('disabled', 'disabled');
        $("#addressSaveBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('invoices.store.job.addresses'),
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
                $('.customerPropertyWrap').fadeIn('fast', function(){
                    $('.theDesc', this).html(theJobAddress).addClass('font-medium');
                    $('.theId', this).val(address_id);
                })

                //if(job_form_id != 6 && job_form_id != 7 && job_form_id != 3 && job_form_id != 4){
                    $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html('Click here to add job address occupant');
                        $('.theId', this).val(0);
                    });
                //}
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

    /* On Click Add Customer Address Start */
    $(document).on('click', '.customerPropertyOccupantBlock', function(e){
        e.preventDefault();
        let $customerPropertyOccupantBlock = $(this);
        let property_id = ($('.customerPropertyWrap .theId').val() > 0 ? $('.customerPropertyWrap .theId').val() : 0)
        console.log(property_id)
        if(property_id > 0){
            $.ajax({
                type: 'POST',
                data: {property_id : property_id},
                url: route('invoices.get.job.address.occupant'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                success: function(data) {
                    jobAddressOccupantModal.show();
                    document.getElementById("jobAddressOccupantModal").addEventListener("shown.tw.modal", function (event) {
                        $('#jobAddressOccupantModal .customerJobAddressOccupantWrap').html(data.html);
                        $('#jobAddressOccupantModal .addJobAddressOccupantBtn').attr('data-customer-property-id', property_id);
                    });
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }
    });
    $('#jobAddressOccupantModal').on('click', '.jobAddressOccupantItem', function(e){
        e.preventDefault();
        let $theAddress = $(this);
        let thePropertyId = $theAddress.attr('data-id');
        let theOccupantName = $theAddress.attr('data-occupant');

        jobAddressOccupantModal.hide();
        $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
            $('.theDesc', this).html(theOccupantName).addClass('font-medium');
            $('.theId', this).val(thePropertyId);
        })
    });
    /* On Click Add Customer Address End */

    /* On Click Add Address Occupant Start */
    $('.addJobAddressOccupantBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let the_property_id = $theBtn.attr('data-customer-property-id');

        jobAddressOccupantModal.hide();

        addJobAddressOccupantModal.show();
        document.getElementById('addJobAddressOccupantModal').addEventListener('shown.tw.modal', function(event){
            $('#addJobAddressOccupantModal input[name="customer_property_id"]').val(the_property_id);
        });
    });

    $('#addJobAddressOccupantForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addJobAddressOccupantForm');
        const $theForm = $(this);
        
        $('#occupantSaveBtn', $theForm).attr('disabled', 'disabled');
        $("#occupantSaveBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('invoices.store.job.address.occupant'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#occupantSaveBtn', $theForm).removeAttr('disabled');
            $("#occupantSaveBtn .theLoader").fadeOut();

            if (response.status == 200) {
                let occupant = response.data.occupant;
                let occupant_id = response.data.id;

                addJobAddressOccupantModal.hide();
                $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                    $('.theDesc', this).html(occupant).addClass('font-medium');
                    $('.theId', this).val(occupant_id);
                })
            }
        }).catch(error => {
            $('#occupantSaveBtn', $theForm).removeAttr('disabled');
            $("#occupantSaveBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#addJobAddressOccupantForm .${key}`).addClass('border-danger');
                        $(`#addJobAddressOccupantForm  .error-${key}`).html(val);
                    }
                } else {
                    console.log('error');
                }
            }
        });
    })
    /* On Click Add Address Occupant End */

    


    /* Auto Load Certificate Edit Data Start */
    if(localStorage.invoice_id && localStorage.getItem('invoice_id') > 0){
        $('#invoice_id').val(localStorage.getItem('invoice_id'));
        if(localStorage.invoice){
            // populate invoice data
        }
    }else{
        $('#invoice_id').val(0);
    }
    /* Auto Load Certificate Edit Data End */

    /* Auto Load Invoice Edit Data Start */
    if(localStorage.invoice_id && localStorage.getItem('invoice_id') > 0){
        $('#invoice_id').val(localStorage.getItem('invoice_id'));
        if(localStorage.invoiceDetails){
            let invoiceDetails = localStorage.getItem('invoiceDetails');
            let invoiceObj = JSON.parse(invoiceDetails);

            $('#non_vat_invoice').val((invoiceObj.non_vat_invoice ? invoiceObj.non_vat_invoice : '0'))
            $('#vat_number').val((invoiceObj.vat_number ? invoiceObj.vat_number : ''))
            //$('#issued_date').val((invoiceObj.issued_date ? invoiceObj.issued_date : getTodayDate()))
            
        }
    }else{
        $('#invoice_id').val(0);
    }
    /* Auto Load Invoice Edit Data End */


    

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
    $(document).on('click', '#saveInvoiceBtn', function(e){
        e.preventDefault();
        let job_form_id = $('#invoiceForm').find('[name="job_form_id"]').val();
        if(job_form_id == 3 || job_form_id == 4){
            $('#invoiceForm').trigger('submit');
        }else{
            $('.gsfSignature .sign-pad-button-submit').trigger('click');
        }
    });

    $('.gsfSignature .sign-pad-button-submit').on('click', function(){
        console.log('clicked');
    })

    $('#invoiceForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('invoiceForm');
        const $theForm = $(this);
        let jobFormId = $theForm.find('input[name="job_form_id"]').val();
        
        $('#saveInvoiceBtn', $theForm).attr('disabled', 'disabled');
        $("#saveInvoiceBtn .theLoader").fadeIn();

        let formValidation = validateCurrentForm(jobFormId);
        let errors = formValidation.errors;
        let formData = formValidation.formData;
        

        if($.isEmptyObject(errors)){
            axios({
                method: "post",
                url: route('invoices.store'),
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
                $('#saveInvoiceBtn', $theForm).removeAttr('disabled');
                $("#saveInvoiceBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#invoiceForm .${key}`).addClass('border-danger');
                            $(`#invoiceForm  .error-${key}`).html(val);
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

            $('#saveInvoiceBtn', $theForm).removeAttr('disabled');
            $("#saveInvoiceBtn .theLoader").fadeOut();

            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Validation Error Found!");
                $("#warningModal .warningModalDesc").html((messages != '' ? messages : 'Form falidation issue found! Please fill out all required data.'));
            });

            // setTimeout(() => {
            //     warningModal.hide();
            // }, 1500);
        }
    })

    function validateCurrentForm(jobFormId){
        const form = document.getElementById('invoiceForm');
        const $theForm = $('#invoiceForm');
        let formData = new FormData(form);

        let errors = {};
        let options = {};
        if($theForm.find('[name="customer_id"]').val() == 0 || $theForm.find('[name="customer_id"]').val() == ''){
            errors['customer_id'] = 'Please select a customer.';
        }
        if($theForm.find('[name="customer_property_id"]').val() == 0 || $theForm.find('[name="customer_property_id"]').val() == ''){
            errors['customer_property_id'] = 'Job address can not be empty.';
        }

        // Invoice related Validation
        if(localStorage.invoiceItems){
            let invoiceItems = localStorage.getItem('invoiceItems');
            options['invoiceItems'] = invoiceItems;
            //formData.append('quoteItems', quoteItems);
        }else{
            errors['item_error'] = 'Please add at least one item for this quote.&nbsp;';
        }
        if(localStorage.invoiceDiscounts){
            let invoiceDiscounts = localStorage.getItem('invoiceDiscounts');
            options['invoiceDiscounts'] = invoiceDiscounts;
            //formData.append('quoteDiscounts', quoteDiscounts);
        }
        if(localStorage.invoiceAdvance){
            let invoiceAdvance = localStorage.getItem('invoiceAdvance');
            options['invoiceAdvance'] = invoiceAdvance;
            //formData.append('quoteAdvance', quoteAdvance);
        }
        if(localStorage.invoiceNotes){
            let invoiceNotes = localStorage.getItem('invoiceNotes');
            options['invoiceNotes'] = invoiceNotes;
            //formData.append('quoteNotes', JSON.parse(quoteNotes));
        }

        formData.append('options', JSON.stringify(options));
        return { errors : errors, formData : formData};
    }
})()