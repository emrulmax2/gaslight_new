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
            url: route('new.records.get.jobs'),
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
                url: route('new.records.linked.job'),
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

                    let customerAddress = '';
                    customerAddress += (customer.address_line_1 != null ? customer.address_line_1+' ' : '');
                    customerAddress += (customer.address_line_2 != null ? customer.address_line_2+', ' : '');
                    customerAddress += (customer.city != null ? customer.city+', ' : '');
                    customerAddress += (customer.state != null ? customer.state+', ' : '');
                    customerAddress += (customer.postal_code != null ? customer.postal_code : '');
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
            
                    $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                        $('.theDesc', this).html((occupant.occupant_name ? occupant.occupant_name : 'Click here to add job address occupant'));
                        $('.theId', this).val((occupant.customer_property_occupant_id ? occupant.customer_property_occupant_id : 0));
                    })

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

        let customerAddress = '';
        customerAddress += (customerObj.address_line_1 != null ? customerObj.address_line_1+' ' : '');
        customerAddress += (customerObj.address_line_2 != null ? customerObj.address_line_2+', ' : '');
        customerAddress += (customerObj.city != null ? customerObj.city+', ' : '');
        customerAddress += (customerObj.state != null ? customerObj.state+', ' : '');
        customerAddress += (customerObj.postal_code != null ? customerObj.postal_code : '');

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

        $('.customerPropertyOccupantWrap').fadeOut('fast', function(){
            $('.theDesc', this).html('Click here to add job address occupant');
            $('.theId', this).val(0);
        })
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
                $('.theDesc', this).html((occupantObj.occupant_name ? occupantObj.occupant_name : 'Click here to add job address occupant'));
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
                url: route('new.records.get.job.addresses'),
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

        $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
            $('.theDesc', this).html('Click here to add job address occupant');
            $('.theId', this).val(0);
        })
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
            url: route('new.records.store.job.addresses'),
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

                $('.customerPropertyOccupantWrap').fadeIn('fast', function(){
                    $('.theDesc', this).html('Click here to add job address occupant');
                    $('.theId', this).val(0);
                })
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
                url: route('new.records.get.job.address.occupant'),
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
            url: route('new.records.store.job.address.occupant'),
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

        relationModal.show();
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
})()