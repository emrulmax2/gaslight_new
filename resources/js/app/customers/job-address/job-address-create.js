import { initGetAddressAutocomplete } from "../../../getAddressAutocomplete.js";

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        initGetAddressAutocomplete({
            token: import.meta.env.VITE_GETADDRESS_API_KEY
        });
    });

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));

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

    $('.coptyCustomerAddress').on('click', function(e){
        let $theBtn = $(this);
        let customer_id = $("#customer_id").val();
        
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
                // console.log($("#customer_address_line_1"))
                // console.log(response)
                $('#jobAddressWrap input[name="address_line_1"]').val(row.address_line_1 ? row.address_line_1 : '');
                $('#jobAddressWrap input[name="address_line_2"]').val(row.address_line_2 ? row.address_line_2 : '');
                $('#jobAddressWrap input[name="city"]').val(row.city ? row.city : '');
                $('#jobAddressWrap input[name="state"]').val(row.state ? row.state : '');
                $('#jobAddressWrap input[name="postal_code"]').val(row.postal_code ? row.postal_code : '');
                $('#jobAddressWrap input[name="country"]').val(row.country ? row.country : '');
                $('#jobAddressWrap input[name="latitude"]').val(row.latitude ? row.latitude : '');
                $('#jobAddressWrap input[name="longitude"]').val(row.longitude ? row.longitude : '');
            }
        }).catch(error => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();
            if (error.response) {
                console.log('error');
            }
        });
    });


    $('.addJobAddrBtn').on('click', function() {
        $('#addJobAddressForm').submit();
    });

    $('#has_occupants').on('change', function(e){
        let $theCheckbox = $(this);
        if($theCheckbox.prop('checked')){
            $('.occupantSection').addClass('pb-2');
            $('#occupantWrap').fadeIn('fast', function(){
                $('input', this).val('');
            })
        }else{
            $('.occupantSection').removeClass('pb-2');
            $('#occupantWrap').fadeOut('fast', function(){
                $('input', this).val('');
            })
        }
    })

    $('#addJobAddressForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addJobAddressForm');
        const $theForm = $(this);
        let customer_id = $theForm.find('[name="customer_id"]').val();
        
        $('#addJobAddressForm .acc__input-error').html('').removeClass('mt-1');
        $('.addJobAddrBtn', $theForm).attr('disabled', 'disabled');
        $(".addJobAddrBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('customer.job-addresses.store', customer_id),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('.addJobAddrBtn', $theForm).removeAttr('disabled');
            $(".addJobAddrBtn .theLoader").fadeOut();
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
            $('.addJobAddrBtn', $theForm).removeAttr('disabled');
            $(".addJobAddrBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#addJobAddressForm .${key}`).addClass('border-danger');
                        $(`#addJobAddressForm  .error-${key}`).html(val).addClass('mt-1');
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
    /* END: Add New Address */

    $('.updateJobAddrBtn').on('click', function() {
        $('#updatejobAddressForm').submit();
    });

    $('#updatejobAddressForm').on('submit', function(e){
            e.preventDefault();
     
            const form = document.getElementById('updatejobAddressForm');
            const $theForm = $(this);
            let property_id = $theForm.find('[name="property_id"]').val()
            let customer_id = $theForm.find('[name="customer_id"]').val()
            
            $('.updateJobAddrBtn', $theForm).attr('disabled', 'disabled');
            $(".updateJobAddrBtn .theLoader").fadeIn();
    
            let form_data = new FormData(form);
            axios({
                method: "post",
                url: route('customer.job-addresses.update', {customer_id: customer_id, address_id: property_id }),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                
                $('.updateJobAddrBtn', $theForm).removeAttr('disabled');
                $(".updateJobAddrBtn .theLoader").fadeOut();
                
    
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
                $('.updateJobAddrBtn', $theForm).removeAttr('disabled');
                $(".updateJobAddrBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updatejobAddressForm .${key}`).addClass('border-danger');
                            $(`#updatejobAddressForm  .error-${key}`).html(val);
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



    /* BEGIN: Search Address & Customers */
    let currentRequest = null;
    $('.search_input').on('keyup paste', function(){
        let $theSearchInput = $(this);
        let $theWrap = $theSearchInput.closest('.searchWrap');
        let $theForm = $theWrap.closest('form');
        let $theIdInput = $theSearchInput.siblings('.the_id_input');
        let $theResultContainer = $theSearchInput.siblings('.searchResultCotainter');
        let customer_id = ($theForm.find('[name="customer_id"]').val() > 0 ? $theForm.find('[name="customer_id"]').val() : 0);
        

        let the_search_query = $theSearchInput.val();
        $theIdInput.val('');
        $theWrap.addClass('active');
        $theResultContainer.fadeIn();
        $theResultContainer.find('.resultWrap').html('<div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>');
        if(the_search_query.length > 0){
            currentRequest = $.ajax({
                type: 'POST',
                data: {the_search_query : the_search_query, customer_id : customer_id},
                url: route('properties.search'),
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

    $(document).on('click', '.searchResultItems', function(){
        let $theItem = $(this);
        let $theWrap = $theItem.closest('.searchWrap.active');
        let $theResultContainer = $theWrap.find('.searchResultCotainter');
        let $theIdInput = $theWrap.find('.the_id_input');
        let $theSearchInput = $theWrap.find('.search_input');

        let the_id = $theItem.attr('data-id');
        let the_title = $theItem.attr('data-title');
        $theIdInput.val(the_id).trigger('change');
        $theSearchInput.val(the_title);
        $theWrap.removeClass('active');
        $theResultContainer.fadeOut(function(){
            $('.resultWrap', this).html('<div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>');
        });
    });

    $(document).on('mouseup', function(e){
        $(document).find('.searchWrap.active').each(function(){
            let $theActiveWrap = $(this);
            let $theResultContainer = $theActiveWrap.find('.searchResultCotainter');
            let $theSearchInput = $theActiveWrap.find('.search_input');
            let $theIdInput = $theActiveWrap.find('.the_id_input');

            if (!$theResultContainer.is(e.target) && $theResultContainer.has(e.target).length === 0){
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
})();