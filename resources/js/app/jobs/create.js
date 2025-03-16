import INTAddressLookUps from '../../address_lookup.js';
import Litepicker from 'litepicker';

(function(){
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const addJobAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addJobAddressModal"));

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

    
    document.getElementById('addJobAddressModal').addEventListener('hide.tw.modal', function(event) {
        $('#addJobAddressModal input[type="text"]').val('');
        $('#addJobAddressModal input[name="customer_id"]').val('0');
    });

    
    /* BEGIN: Add Job */
    $('#addCustomerJobForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addCustomerJobForm');
        const $theForm = $(this);
        let customer_id = $theForm.find('[name="customer_id"]').val()
        
        $('#jobSaveBtn', $theForm).attr('disabled', 'disabled');
        $("#jobSaveBtn .theLoader").fadeIn();

        const urlParams = new URLSearchParams(window.location.search);
        const recordParam = urlParams.get('record');

        let form_data = new FormData(form);
        if(recordParam){
            form_data.append('record', recordParam);
        }
        axios({
            method: "post",
            url: route('jobs.store', customer_id),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#jobSaveBtn', $theForm).removeAttr('disabled');
            $("#jobSaveBtn .theLoader").fadeOut();

            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.href = response.data.red;
                }, 1500);
            }
        }).catch(error => {
            $('#jobSaveBtn', $theForm).removeAttr('disabled');
            $("#jobSaveBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#addCustomerJobForm .${key}`).addClass('border-danger');
                        $(`#addCustomerJobForm  .error-${key}`).html(val);
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
    })
    /* END: Add Job */
    
    /* BEGIN: Add New Address */
    $(document).on('click', '.addAddressBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let $form = $theBtn.closest('form');

        let customer_id = ($form.find('input[name="customer_id"]').val() > 0 ? $form.find('input[name="customer_id"]').val() : 0);
        if(customer_id > 0){
            addJobAddressModal.show();
            document.getElementById('addJobAddressModal').addEventListener('shown.tw.modal', function(event){
                $('#addJobAddressModal input[name="customer_id"]').val(customer_id);
            });
        }else{
            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Error Found!");
                $("#warningModal .warningModalDesc").html('Please select a customer first. Then add a new address.');
            });
        }
    })

    $('#addJobAddressModal').on('click', '.coptyCustomerAddress', function(e){
        let $theBtn = $(this);
        let customer_id = ($('#addJobAddressModal [name="customer_id"]').val() > 0 ? $('#addJobAddressModal [name="customer_id"]').val() : 0);
        
        $theBtn.attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();

        axios({
            method: "post",
            url: route('customers.get.details'),
            data: {customer_id : customer_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();

            if (response.status == 200) {
                let row = response.data.row;
                $('#addJobAddressModal input[name="address_line_1"]').val(row.address_line_1 ? row.address_line_1 : '');
                $('#addJobAddressModal input[name="address_line_2"]').val(row.address_line_2 ? row.address_line_2 : '');
                $('#addJobAddressModal input[name="city"]').val(row.city ? row.city : '');
                $('#addJobAddressModal input[name="state"]').val(row.state ? row.state : '');
                $('#addJobAddressModal input[name="postal_code"]').val(row.postal_code ? row.postal_code : '');
                $('#addJobAddressModal input[name="country"]').val(row.country ? row.country : '');
                $('#addJobAddressModal input[name="latitude"]').val(row.latitude ? row.latitude : '');
                $('#addJobAddressModal input[name="longitude"]').val(row.longitude ? row.longitude : '');
            }
        }).catch(error => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();
            if (error.response) {
                console.log('error');
            }
        });
    });

    $('#addJobAddressForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addJobAddressForm');
        const $theForm = $(this);
        let customer_id = $theForm.find('[name="customer_id"]').val();
        
        $('#addressSaveBtn', $theForm).attr('disabled', 'disabled');
        $("#addressSaveBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('customer.job-addresses.store', customer_id),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#addressSaveBtn', $theForm).removeAttr('disabled');
            $("#addressSaveBtn .theLoader").fadeOut();

            if (response.status == 200) {
                addJobAddressModal.hide();

                $('#addCustomerJobForm .address_name').val(response.data.address);
                $('#addCustomerJobForm [name="customer_property_id"]').val(response.data.id);

                $('#addCustomerJobForm .searchResultCotainter').fadeOut(function(){
                    $('.resultWrap', this).html('<div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>');
                });
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
    /* END: Add New Address */

    /* BEGIN: Search Address & Customers */
    let currentRequest = null;
    $('.search_input').on('keyup paste', function(){
        let $theSearchInput = $(this);
        let $theWrap = $theSearchInput.closest('.searchWrap');
        let theType = $theWrap.attr('data-type');
        let $theForm = $theWrap.closest('form');
        let $theIdInput = $theSearchInput.siblings('.the_id_input');
        let $theResultContainer = $theSearchInput.siblings('.searchResultCotainter');
        let customer_id = (theType == 'address' && $theForm.find('[name="customer_id"]').val() > 0 ? $theForm.find('[name="customer_id"]').val() : 0);
        let url = (theType == 'address' ? 'jobs.search.address' : 'jobs.search.customers');

        let the_search_query = $theSearchInput.val();
        $theIdInput.val('');
        $theWrap.addClass('active');
        $theResultContainer.fadeIn();
        $theResultContainer.find('.resultWrap').html('<div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>');
        
        if(theType == 'customer'){
            $theForm.find('.searchWrap[data-type="address"] .search_input').val('').attr('disabled', 'disabled');
            $theForm.find('.searchWrap[data-type="address"] .the_id_input').val('0');
        }
        if(the_search_query.length > 0){
            currentRequest = $.ajax({
                type: 'POST',
                data: {the_search_query : the_search_query, customer_id : customer_id},
                url: route(url),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                beforeSend : function()    {           
                    if(currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function(data) {
                    if(data.suc == 1){
                        $theResultContainer.find('.resultWrap').html(data.html);
                    }else{
                        $theResultContainer.find('.resultWrap').html('<div class="font-bold text-slate-300 py-10 text-center">No Result</div>');
                    }

                    setTimeout(() => {
                        createIcons({ icons, attrs: { "stroke-width": 1.5 }, nameAttr: "data-lucide" });
                    }, 50);
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }else{
            $theResultContainer.find('.resultWrap').html('<div class="font-bold text-slate-300 py-10 text-center">No Result</div>');
            if(currentRequest != null) {
                currentRequest.abort();
            }
        }
    });

    $(document).on('focus', '.search_input', function(){
        let $theSearchInput = $(this);
        let $theWrap = $theSearchInput.closest('.searchWrap');
        let theType = $theWrap.attr('data-type');
        let $theForm = $theWrap.closest('form');
        let $theIdInput = $theSearchInput.siblings('.the_id_input');
        let $theResultContainer = $theSearchInput.siblings('.searchResultCotainter');

        if(theType == 'address'){
            $theWrap.addClass('active');
            let customer_id = ($theForm.find('[name="customer_id"]').val() > 0 ? $theForm.find('[name="customer_id"]').val() : 0);
            currentRequest = $.ajax({
                type: 'POST',
                data: {customer_id : customer_id},
                url: route('jobs.get.customer.addresses'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                beforeSend : function()    {           
                    if(currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function(data) {
                    $theResultContainer.fadeIn();
                    if(data.suc == 1){
                        $theResultContainer.find('.resultWrap').html(data.html);
                    }else{
                        $theResultContainer.find('.resultWrap').html('<div class="font-bold text-slate-300 py-10 text-center">No Result</div>');
                    }

                    setTimeout(() => {
                        createIcons({ icons, attrs: { "stroke-width": 1.5 }, nameAttr: "data-lucide" });
                    }, 50);
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }else if(theType == 'customer'){
            $theWrap.addClass('active');
            currentRequest = $.ajax({
                type: 'POST',
                data: {all_customer : 1},
                url: route('jobs.search.customers'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                beforeSend : function()    {           
                    if(currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function(data) {
                    $theResultContainer.fadeIn();
                    if(data.suc == 1){
                        $theResultContainer.find('.resultWrap').html(data.html);
                    }else{
                        $theResultContainer.find('.resultWrap').html('<div class="font-bold text-slate-300 py-10 text-center">No Result</div>');
                    }

                    setTimeout(() => {
                        createIcons({ icons, attrs: { "stroke-width": 1.5 }, nameAttr: "data-lucide" });
                    }, 50);
                },
                error:function(e){
                    console.log('Error');
                }
            });
        }
    })

    $(document).on('click', '.searchResultItems', function(){
        let $theItem = $(this);
        let $theWrap = $theItem.closest('.searchWrap.active');
        let $theResultContainer = $theWrap.find('.searchResultCotainter');
        let $theIdInput = $theWrap.find('.the_id_input');
        let $theSearchInput = $theWrap.find('.search_input');
        let $theForm = $theWrap.closest('form');
        let dataOf = $theItem.attr('data-of');

        let the_id = $theItem.attr('data-id');
        let the_title = $theItem.attr('data-title');
        $theIdInput.val(the_id).trigger('change');
        $theSearchInput.val(the_title);
        $theWrap.removeClass('active');
        $theResultContainer.fadeOut(function(){
            $('.resultWrap', this).html('<div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>');
        });

        if(dataOf == 'customer'){
            $theForm.find('.searchWrap[data-type="address"] .search_input').removeAttr('disabled');
            $theForm.find('.searchWrap[data-type="address"] .the_id_input').val('0');
        }
    });

    $(document).on('mouseup', function(e){
        console.log('mouse up')
        $(document).find('.searchWrap.active').each(function(){
            let $theActiveWrap = $(this);
            let $theResultContainer = $theActiveWrap.find('.searchResultCotainter');
            let $theSearchInput = $theActiveWrap.find('.search_input');
            let $theIdInput = $theActiveWrap.find('.the_id_input');

            if (!$theActiveWrap.is(e.target) && $theActiveWrap.has(e.target).length === 0){
                $theIdInput.val('').trigger('change');
                $theSearchInput.val('');
                $theActiveWrap.removeClass('active');
                $theResultContainer.fadeOut(function(){
                    $('.resultWrap', this).html('<div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>');
                });
            }
        })
    });
    /* END: Search Address & Customers */


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
    const jobCalenderDate = new Litepicker({
        element: document.getElementById('job_calender_date'),
        ...dateOption
    });

    const calenderSlot = document.querySelector('.calenderSlot');

    jobCalenderDate.on('selected', (date) => {
        if(date){
            calenderSlot.classList.remove('hidden');
        }else{
            calenderSlot.classList.add('hidden');
        }
    });

})();