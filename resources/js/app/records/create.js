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

    const jobAddressOccupantModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#jobAddressOccupantModal"));
    const addJobAddressOccupantModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addJobAddressOccupantModal"));

    const linkedJobModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#linkedJobModal"));
    const relationModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#relationModal"));
    
    
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

    document.getElementById("relationModal").addEventListener("hide.tw.modal", function (event) {
        $('#relationModal input[type="radio"]').prop('checked', false);
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
        let job_form_id = $('#certificateForm [name="job_form_id"]').val();
        
        $.ajax({
            type: 'POST',
            data: {job_form_id : job_form_id},
            url: route('records.get.jobs'),
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
                url: route('records.linked.job'),
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

                    
                    let billingAddress = '';
                    let billingAddressId = 0;
                    if(job.billing_address_id > 0){
                        let billingAddressObj = job.billing;
                        billingAddressId = billingAddressObj.id
                        billingAddress += (billingAddressObj.address_line_1 != null ? billingAddressObj.address_line_1+' ' : '');
                        billingAddress += (billingAddressObj.address_line_2 != null ? billingAddressObj.address_line_2+', ' : '');
                        billingAddress += (billingAddressObj.city != null ? billingAddressObj.city+', ' : '');
                        billingAddress += (billingAddressObj.state != null ? billingAddressObj.state+', ' : '');
                        billingAddress += (billingAddressObj.postal_code != null ? billingAddressObj.postal_code : '');

                        localStorage.setItem('billing_address', JSON.stringify(billingAddressObj));
                    }else{
                        let billingAddressObj = customer.address;
                        billingAddressId = billingAddressObj.id
                        billingAddress += (billingAddressObj.address_line_1 != null ? billingAddressObj.address_line_1+' ' : '');
                        billingAddress += (billingAddressObj.address_line_2 != null ? billingAddressObj.address_line_2+', ' : '');
                        billingAddress += (billingAddressObj.city != null ? billingAddressObj.city+', ' : '');
                        billingAddress += (billingAddressObj.state != null ? billingAddressObj.state+', ' : '');
                        billingAddress += (billingAddressObj.postal_code != null ? billingAddressObj.postal_code : '');

                        localStorage.setItem('billing_address', JSON.stringify(billingAddressObj));
                    }
                    $('.customerAddressWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html(billingAddress).addClass('font-medium');
                        $('.theId', this).val(billingAddressId);
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
        let job_form_id = $('#certificateForm [name="job_form_id"]').val();
        
        $.ajax({
            type: 'POST',
            data: {job_form_id : job_form_id},
            url: route('records.get.customers'),
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
                url: route('records.linked.customer'),
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
                url: route('records.get.job.addresses'),
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
                url: route('records.get.job.addresses'),
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
        let theOccupantName = $theAddress.attr('data-occupant');
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
            $('.customerPropertyWrap').fadeIn('fast', function(){
                $('.theDesc', this).html(theAddress).addClass('font-medium');
                $('.theId', this).val(theAddressId);
            });

            $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                $('.theDesc', this).html('Click here to add job address occupant');
                $('.theId', this).val(0);
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
            url: route('records.store.job.addresses'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#addressSaveBtn', $theForm).removeAttr('disabled');
            $("#addressSaveBtn .theLoader").fadeOut();

            if (response.status == 200) {
                addJobAddressModal.hide();
                let address = response.data.address;
                let address_id = response.data.id;
                let theJobAddress = '';
                theJobAddress += (address.address_line_1 != null ? address.address_line_1+' ' : '');
                theJobAddress += (address.address_line_2 != null ? address.address_line_2+', ' : '');
                theJobAddress += (address.city != null ? address.city+', ' : '');
                theJobAddress += (address.state != null ? address.state+', ' : '');
                theJobAddress += (address.postal_code != null ? address.postal_code : '');
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
                    $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html('Click here to add job address occupant');
                        $('.theId', this).val(0);
                    });
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
                url: route('records.get.job.address.occupant'),
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
            url: route('records.store.job.address.occupant'),
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

    /* On Click Add Relation Start */
    $('.relationBlock').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let relation_id = $('#relation_id').val();

        relationModal.show();
        document.getElementById('relationModal').addEventListener('shown.tw.modal', function(event){
            if(relation_id > 0){
                $('#relationModal #relation_'+relation_id).prop('checked', true);
            }else{
                $('#relationModal .relation_item').prop('checked', false);
            }
        });
    });
    $('#relationModal').on('change', '.relation_item', function(){
        let $theRelation = $('#relationModal .relation_item:checked');
        let theLabel = $theRelation.attr('data-label');
        let theRelationId = $theRelation.val();
        
        relationModal.hide();
        $('.relationBlock .theDesc').html(theLabel).addClass('font-medium');
        $('.relationBlock .theId').val(theRelationId);
    });
    /* On Click Add Relation End */


    /* Auto Load Certificate Edit Data Start */
    if(localStorage.certificate_id && localStorage.getItem('certificate_id') > 0){
        $('#certificate_id').val(localStorage.getItem('certificate_id'));
        if(localStorage.certificate){
            let certificate = localStorage.getItem('certificate');
            let certificateObj = JSON.parse(certificate);

            $('#inspection_date').val((certificateObj.inspection_date ? certificateObj.inspection_date : getTodayDate()))
            $('#next_inspection_date').val((certificateObj.next_inspection_date ? certificateObj.next_inspection_date : getNextYearDate()))
            $('#received_by').val((certificateObj.received_by ? certificateObj.received_by : ''))
            if(certificateObj.relation_id && certificateObj.relation_id > 0){
                $('.relationBlock .theDesc').html(certificateObj.relation_name).addClass('font-medium');
                $('.relationBlock .theId').val(certificateObj.relation_id);
            }else{
                $('.relationBlock .theDesc').html('N/A').removeClass('font-medium');
                $('.relationBlock .theId').val(0);
            }
            if(certificateObj.signature && certificateObj.signature != ''){
                $('.signatureImgWrap').fadeIn('fast', function(){
                    $('.theSignature', this).attr('src', certificateObj.signature);
                });
            }else{
                $('.signatureImgWrap').fadeOut('fast', function(){
                    $('.theSignature', this).attr('src', '');
                });
            }
        }
    }else{
        $('#certificate_id').val(0);
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
            $('#issued_date').val((invoiceObj.issued_date ? invoiceObj.issued_date : getTodayDate()))
            
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
    $(document).on('click', '#saveCertificateBtn', function(e){
        e.preventDefault();
        let job_form_id = $('#certificateForm').find('[name="job_form_id"]').val();
        if(job_form_id == 3 || job_form_id == 4){
            $('#certificateForm').trigger('submit');
        }else{
            $('.gsfSignature .sign-pad-button-submit').trigger('click');
        }
    });

    $('.gsfSignature .sign-pad-button-submit').on('click', function(){
        console.log('clicked');
    })

    $('#certificateForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('certificateForm');
        const $theForm = $(this);
        let jobFormId = $theForm.find('input[name="job_form_id"]').val();
        
        $('#saveCertificateBtn', $theForm).attr('disabled', 'disabled');
        $("#saveCertificateBtn .theLoader").fadeIn();

        let formValidation = validateCurrentForm(jobFormId);
        let errors = formValidation.errors;
        let formData = formValidation.formData;
        

        if($.isEmptyObject(errors)){
            axios({
                method: "post",
                url: route('records.store'),
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
                $('#saveCertificateBtn', $theForm).removeAttr('disabled');
                $("#saveCertificateBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#certificateForm .${key}`).addClass('border-danger');
                            $(`#certificateForm  .error-${key}`).html(val);
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

            $('#saveCertificateBtn', $theForm).removeAttr('disabled');
            $("#saveCertificateBtn .theLoader").fadeOut();

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
        const form = document.getElementById('certificateForm');
        const $theForm = $('#certificateForm');
        let formData = new FormData(form);

        let errors = {};
        let options = {};
        if($theForm.find('[name="customer_id"]').val() == 0 || $theForm.find('[name="customer_id"]').val() == ''){
            errors['customer_id'] = 'Please select a customer.';
        }
        if($theForm.find('[name="customer_address_id"]').val() == 0 || $theForm.find('[name="customer_address_id"]').val() == ''){
            errors['customer_address_id'] = 'Billing address can not be empty.';
        }
        if($theForm.find('[name="customer_property_id"]').val() == 0 || $theForm.find('[name="customer_property_id"]').val() == ''){
            errors['customer_property_id'] = 'Job address can not be empty.';
        }
        // if($theForm.find('[name="customer_property_occupant_id"]').val() == 0 || $theForm.find('[name="customer_property_occupant_id"]').val() == ''){
        //     errors['customer_property_occupant_id'] = 'Occoupant details can not be empty.';
        // }

        if(jobFormId == 6 || jobFormId == 7){
            if(localStorage.appliances){
                let appliances = localStorage.getItem('appliances');
                options['appliances'] = appliances;
                //formData.append('appliances', appliances);
            }else{
                errors['appliance_error'] = 'Please add at least one appliance.';
            }
            if(localStorage.safetyChecks){
                let safetyChecks = localStorage.getItem('safetyChecks');
                options['safetyChecks'] = safetyChecks;
                //formData.append('safetyChecks', safetyChecks);
            }
            if(localStorage.gsrComments){
                let gsrComments = localStorage.getItem('gsrComments');
                options['gsrComments'] = gsrComments;
                //formData.append('gsrComments', gsrComments);
            }
        }else if(jobFormId == 8){
            if(localStorage.appliances){
                let appliances = localStorage.getItem('appliances');
                options['appliances'] = appliances;
                //formData.append('appliances', appliances);
            }else{
                errors['appliance_error'] = 'Please add at least one appliance.&nbsp;';
            }
            if(localStorage.otherChecks){
                let otherChecks = localStorage.getItem('otherChecks');
                options['otherChecks'] = otherChecks;
            }
        }else if(jobFormId == 9){
            if(localStorage.appliances){
                let appliances = localStorage.getItem('appliances');
                options['appliances'] = appliances;
                //formData.append('appliances', appliances);
            }else{
                errors['appliance_error'] = 'Please add at least one appliance.&nbsp;';
            }
        }else if(jobFormId == 10){
            if(localStorage.appliances){
                let appliances = localStorage.getItem('appliances');
                options['appliances'] = appliances;
                //formData.append('appliances', appliances);
            }else{
                errors['appliance_error'] = 'Please add at least one appliance.&nbsp;';
            }
        }else if(jobFormId == 13){
            if(localStorage.appliances){
                let appliances = localStorage.getItem('appliances');
                options['appliances'] = appliances;
                //formData.append('appliances', appliances);
            }else{
                errors['appliance_error'] = 'Please add at least one appliance.&nbsp;';
            }
        }else if(jobFormId == 15){
            if(localStorage.radiators){
                let radiators = localStorage.getItem('radiators');
                options['radiators'] = radiators;
                //formData.append('radiators', radiators);
            }else{
                errors['radiator_error'] = 'Please add at least one Radiator.&nbsp;';
            }
            if(localStorage.powerFlushChecklist){
                let powerFlushChecklist = localStorage.getItem('powerFlushChecklist');
                options['powerFlushChecklist'] = powerFlushChecklist;
                //formData.append('powerFlushChecklist', powerFlushChecklist);
            }else{
                errors['checklist_error'] = 'Please fill out powerflush checklist data.&nbsp;';
            }
        }else if(jobFormId == 16){
            if(localStorage.appliances){
                let appliances = localStorage.getItem('appliances');
                options['appliances'] = appliances;
                //formData.append('appliances', appliances);
            }else{
                errors['appliance_error'] = 'Please add at least one appliance.&nbsp;';
            }
        }else if(jobFormId == 17){
            if(localStorage.unventedSystems){
                let unventedSystems = localStorage.getItem('unventedSystems');
                options['unventedSystems'] = unventedSystems;
                //formData.append('unventedSystems', unventedSystems);
            }else{
                errors['system_error'] = 'Please fill out unvented hot water system details.&nbsp;';
            }
            if(localStorage.inspectionRecords){
                let inspectionRecords = localStorage.getItem('inspectionRecords');
                options['inspectionRecords'] = inspectionRecords;
                //formData.append('inspectionRecords', inspectionRecords);
            }else{
                errors['inspection_error'] = 'Please fill out inspection record details details.&nbsp;';
            }
        }else if(jobFormId == 18){
            if(localStorage.jobSheets){
                let jobSheets = localStorage.getItem('jobSheets');
                options['jobSheets'] = jobSheets;
                //formData.append('jobSheets', jobSheets);
            }else{
                errors['jobSheets_error'] = 'Please fill out job sheet details.&nbsp;';
            }
            let input = document.getElementById('job_sheet_files');
            if(input.files.length > 0){
                formData.append('file', $('#certificateForm input#job_sheet_files')[0].files[0]); 
            }
        }
        formData.append('options', JSON.stringify(options));

        return { errors : errors, formData : formData};
    }
})()