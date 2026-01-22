import { initGetAddressAutocomplete } from "../../getAddressAutocomplete.js";

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        initGetAddressAutocomplete({
            token: import.meta.env.VITE_GETADDRESS_API_KEY
        });
    });

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const updateCustomerDataModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updateCustomerDataModal"));
    const customerAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#customerAddressModal"));
    const customerNoteModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#customerNoteModal"));
    const reminderModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#reminderModal"));
    const customerNameModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#customerNameModal"));
    
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
    
    document.getElementById('updateCustomerDataModal').addEventListener('hide.tw.modal', function(event) {
        $('#updateCustomerDataModal .acc__input-error').html('');
        $('#updateCustomerDataModal .fieldTitle').text('Value');
        $('#updateCustomerDataModal .requiredLabel').addClass('hidden');
        $('#updateCustomerDataModal input[name="fieldValue"]').val('');
        $('#updateCustomerDataModal input[name="fieldName"]').val('');
        $('#updateCustomerDataModal input[name="theModel"]').val('customer');
    });

    $('#customerNameForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('customerNameForm');
        const $theForm = $(this);
        
        $('#customerNameModal .acc__input-error').html('');
        $('#saveNameBtn', $theForm).attr('disabled', 'disabled');
        $("#saveNameBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('customers.update'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#saveNameBtn', $theForm).removeAttr('disabled');
            $("#saveNameBtn .theLoader").fadeOut();

            if (response.status == 200) {
                customerNameModal.hide();
                window.location.reload();
            }
        }).catch(error => {
            $('#saveNameBtn', $theForm).removeAttr('disabled');
            $("#saveNameBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#customerNameForm .${key}`).addClass('border-danger');
                        $(`#customerNameForm  .error-${key}`).html(val);
                    }
                } else {
                    console.log('error');
                }
            }
        });
    });

    $(document).on('click', '.fieldValueToggler', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let theTitle = $theBtn.attr('data-title');
        let theField = $theBtn.attr('data-field');
        let theValue = $theBtn.attr('data-value');
        let theRequired = $theBtn.attr('data-required');
        let theType = $theBtn.attr('data-type');
        let theModel = $theBtn.attr('data-model');

        updateCustomerDataModal.show();
        document.getElementById('updateCustomerDataModal').addEventListener('shown.tw.modal', function(event){
            $('#updateCustomerDataModal .fieldTitle').text(theTitle);
            if(theRequired == 1){
                $('#updateCustomerDataModal .requiredLabel').removeClass('hidden');
                $('#updateCustomerDataModal input[name="fieldValue"]').val(theValue).addClass('require');
            }else{
                $('#updateCustomerDataModal .requiredLabel').addClass('hidden');
                $('#updateCustomerDataModal input[name="fieldValue"]').val(theValue).removeClass('require');
            }
            if(theType == 'email'){
                $('#updateCustomerDataModal input[name="fieldValue"]').attr('type', 'email');
            }else{
                $('#updateCustomerDataModal input[name="fieldValue"]').attr('type', 'text');
            }
            $('#updateCustomerDataModal input[name="fieldName"]').val(theField);
            $('#updateCustomerDataModal input[name="theModel"]').val(theModel);
        });
    })

    $('#updateCustomerDataForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updateCustomerDataForm');
        const $theForm = $(this);
        
        $('#updateCustomerDataModal .acc__input-error').html('');
        $('#updateDataBtn', $theForm).attr('disabled', 'disabled');
        $("#updateDataBtn .theLoader").fadeIn();

        let errors = 0;
        $theForm.find('.require').each(function(){
            if($(this).val() == ''){
                errors += 1;
                $(this).siblings('.acc__input-error').html('This field is required.')
            }
        });

        if(errors > 0){
            $('#updateDataBtn', $theForm).removeAttr('disabled');
            $("#updateDataBtn .theLoader").fadeOut();

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('customers.update.field.value'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#updateDataBtn', $theForm).removeAttr('disabled');
                $("#updateDataBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    updateCustomerDataModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#updateDataBtn', $theForm).removeAttr('disabled');
                $("#updateDataBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updateCustomerDataForm .${key}`).addClass('border-danger');
                            $(`#updateCustomerDataForm  .error-${key}`).html(val);
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
        }
    });

    $('#customerNoteForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('customerNoteForm');
        const $theForm = $(this);
        
        $('#customerNoteModal .acc__input-error').html('');
        $('#updateNoteBtn', $theForm).attr('disabled', 'disabled');
        $("#updateNoteBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('customers.update.field.value'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#updateNoteBtn', $theForm).removeAttr('disabled');
            $("#updateNoteBtn .theLoader").fadeOut();

            if (response.status == 200) {
                customerNoteModal.hide();
                window.location.reload();
            }
        }).catch(error => {
            $('#updateNoteBtn', $theForm).removeAttr('disabled');
            $("#updateNoteBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#customerNoteForm .${key}`).addClass('border-danger');
                        $(`#customerNoteForm  .error-${key}`).html(val);
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
    });

    $('#reminderForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('reminderForm');
        const $theForm = $(this);
        
        $('#reminderModal .acc__input-error').html('').removeClass('mt-1');
        $('#saveReminderBtn', $theForm).attr('disabled', 'disabled');
        $("#saveReminderBtn .theLoader").fadeIn();

        let errors = 0;
        if($theForm.find('input[name="fieldValue"]:checked').length == 0){
            errors += 1;
        }

        if(errors > 0){
            $('#reminderModal .error-fieldValue').html('This field is required.').addClass('mt-1');
            $('#saveReminderBtn', $theForm).removeAttr('disabled');
            $("#saveReminderBtn .theLoader").fadeOut();

            setTimeout(() => {
                $('#reminderModal .acc__input-error').html('').removeClass('mt-1');
            }, 1500);

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('customers.update.field.value'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#saveReminderBtn', $theForm).removeAttr('disabled');
                $("#saveReminderBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    reminderModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#saveReminderBtn', $theForm).removeAttr('disabled');
                $("#saveReminderBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#reminderForm .${key}`).addClass('border-danger');
                            $(`#reminderForm  .error-${key}`).html(val);
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
        }
    });


    // Store Company Address Details
    $('#customerAddressForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('customerAddressForm');
        const $theForm = $(this);
        
        $('#adrUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#adrUpdateBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('customers.update.address.info'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#adrUpdateBtn', $theForm).removeAttr('disabled');
            $("#adrUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                customerAddressModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(function(){
                    successModal.hide();
                    window.location.reload();
                }, 1500)
            }
        }).catch(error => {
            $('#adrUpdateBtn', $theForm).removeAttr('disabled');
            $("#adrUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#customerAddressForm .${key}`).addClass('border-danger');
                        $(`#customerAddressForm  .error-${key}`).html(val);
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
    });
})();