import INTAddressLookUps from '../../../address_lookup.js';

(function(){
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }


    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const updatePropertyDataModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatePropertyDataModal"));
    const updatePropertyDueDateModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatePropertyDueDateModal"));
    const jobAddressNoteModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#jobAddressNoteModal"));
    const propertyAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#propertyAddressModal"));
    
    
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
    
    document.getElementById('updatePropertyDataModal').addEventListener('hide.tw.modal', function(event) {
        $('#updatePropertyDataModal .acc__input-error').html('');
        $('#updatePropertyDataModal .fieldTitle').text('Value');
        $('#updatePropertyDataModal .requiredLabel').addClass('hidden');
        $('#updatePropertyDataModal input[name="fieldValue"]').val('');
        $('#updatePropertyDataModal input[name="fieldName"]').val('');
        $('#updatePropertyDataModal input[name="theModel"]').val('customer');
    });

    
    $(document).on('click', '.fieldValueToggler', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let theTitle = $theBtn.attr('data-title');
        let theField = $theBtn.attr('data-field');
        let theValue = $theBtn.attr('data-value');
        let theRequired = $theBtn.attr('data-required');
        let theType = $theBtn.attr('data-type');

        updatePropertyDataModal.show();
        document.getElementById('updatePropertyDataModal').addEventListener('shown.tw.modal', function(event){
            $('#updatePropertyDataModal .fieldTitle').text(theTitle);
            if(theRequired == 1){
                $('#updatePropertyDataModal .requiredLabel').removeClass('hidden');
                $('#updatePropertyDataModal input[name="fieldValue"]').val(theValue).addClass('require');
            }else{
                $('#updatePropertyDataModal .requiredLabel').addClass('hidden');
                $('#updatePropertyDataModal input[name="fieldValue"]').val(theValue).removeClass('require');
            }
            if(theType == 'email'){
                $('#updatePropertyDataModal input[name="fieldValue"]').attr('type', 'email');
            }else{
                $('#updatePropertyDataModal input[name="fieldValue"]').attr('type', 'text');
            }
            $('#updatePropertyDataModal input[name="fieldName"]').val(theField);
        });
    })

    $('#updatePropertyDataForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updatePropertyDataForm');
        const $theForm = $(this);
        
        $('#updatePropertyDataModal .acc__input-error').html('');
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
                url: route('customer.job-addresses.update.data'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#updateDataBtn', $theForm).removeAttr('disabled');
                $("#updateDataBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    updatePropertyDataModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#updateDataBtn', $theForm).removeAttr('disabled');
                $("#updateDataBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updatePropertyDataForm .${key}`).addClass('border-danger');
                            $(`#updatePropertyDataForm  .error-${key}`).html(val);
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

    $('#updatePropertyDueDateForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updatePropertyDueDateForm');
        const $theForm = $(this);
        
        $('#updatePropertyDueDateModal .acc__input-error').html('');
        $('#updateDueDateBtn', $theForm).attr('disabled', 'disabled');
        $("#updateDueDateBtn .theLoader").fadeIn();

        let errors = 0;
        $theForm.find('.require').each(function(){
            if($(this).val() == ''){
                errors += 1;
                $(this).siblings('.acc__input-error').html('This field is required.')
            }
        });

        if(errors > 0){
            $('#updateDueDateBtn', $theForm).removeAttr('disabled');
            $("#updateDueDateBtn .theLoader").fadeOut();

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('customer.job-addresses.update.data'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#updateDueDateBtn', $theForm).removeAttr('disabled');
                $("#updateDueDateBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    updatePropertyDueDateModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#updateDueDateBtn', $theForm).removeAttr('disabled');
                $("#updateDueDateBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updatePropertyDueDateForm .${key}`).addClass('border-danger');
                            $(`#updatePropertyDueDateForm  .error-${key}`).html(val);
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

    $('#jobAddressNoteForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('jobAddressNoteForm');
        const $theForm = $(this);
        
        $('#jobAddressNoteModal .acc__input-error').html('');
        $('#updateNoteBtn', $theForm).attr('disabled', 'disabled');
        $("#updateNoteBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('customer.job-addresses.update.data'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#updateNoteBtn', $theForm).removeAttr('disabled');
            $("#updateNoteBtn .theLoader").fadeOut();

            if (response.status == 200) {
                jobAddressNoteModal.hide();
                window.location.reload();
            }
        }).catch(error => {
            $('#updateNoteBtn', $theForm).removeAttr('disabled');
            $("#updateNoteBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#jobAddressNoteForm .${key}`).addClass('border-danger');
                        $(`#jobAddressNoteForm  .error-${key}`).html(val);
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


    // Store Company Address Details
    $('#propertyAddressForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('propertyAddressForm');
        const $theForm = $(this);
        
        $('#propertyAddressModal .acc__input-error').html('');
        $('#adrUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#adrUpdateBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('customer.job-addresses.update.address'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#adrUpdateBtn', $theForm).removeAttr('disabled');
            $("#adrUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                propertyAddressModal.hide();
                window.location.reload();
            }
        }).catch(error => {
            $('#adrUpdateBtn', $theForm).removeAttr('disabled');
            $("#adrUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#propertyAddressForm .${key}`).addClass('border-danger');
                        $(`#propertyAddressForm  .error-${key}`).html(val);
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