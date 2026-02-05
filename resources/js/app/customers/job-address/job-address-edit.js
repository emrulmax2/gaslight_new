import INTAddressLookUps from "../../../address_lookup";

("use strict");
var JobAddressOccupantsListTable = (function () {
    var _tableGen = function () {

        let property_id = $("#JobAddressOccupantsListTable").attr('data-propery') != "" ? $("#JobAddressOccupantsListTable").attr('data-propery') : "";
        
        axios({
            method: 'post',
            url: route('job-addresses.occupant.list'),
            data: {property_id: property_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                $('#JobAddressOccupantsListTable').html(response.data.html);

                createIcons({
                    icons,
                    attrs: { "stroke-width": 1.5 },
                    nameAttr: "data-lucide",
                });
            }
        }).catch(error =>{
            console.log(error)
        });
        

    };
    return {
        init: function () {
            _tableGen();
        },
    };

})();

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        INTAddressLookUps();
    });

    if($('#has_occupants').prop('checked')){
        JobAddressOccupantsListTable.init();
    }

    function filterJobAddressListTable() {
        JobAddressOccupantsListTable.init();
    }

    function destroyJobAddressListTable(){
        $('#JobAddressOccupantsListTable').html('')
    }

    $("#query").on("keypress", function (e) {
        var key = e.keyCode || e.which;
        if(key === 13){
            e.preventDefault();

            JobAddressOccupantsListTable.init();
        }
    });


    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const updatePropertyDataModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatePropertyDataModal"));
    const updatePropertyDueDateModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatePropertyDueDateModal"));
    const jobAddressNoteModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#jobAddressNoteModal"));
    const propertyAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#propertyAddressModal"));
    const addOccupantModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addOccupantModal"));
    const editOccupantModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#editOccupantModal"));
    
    
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

    document.getElementById('addOccupantModal').addEventListener('hide.tw.modal', function(event) {
        $('#addOccupantModal .acc__input-error').html('');
        $('#addOccupantModal .modal-body input').val('');
    });

    document.getElementById('editOccupantModal').addEventListener('hide.tw.modal', function(event) {
        $('#editOccupantModal .acc__input-error').html('');
        $('#editOccupantModal .modal-body input').val('');
        $('#editOccupantModal [name="id"]').val('0');
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

    $('#has_occupants').on('change', function(e){
        let $theCheckbox = $(this);
        if($theCheckbox.prop('checked')){
            $('.occupantSection').addClass('pb-2');
            $('.occupantTableWrap').fadeIn('fast', function(){
                filterJobAddressListTable();
                $('.addOccupantToggler').fadeIn();
            })
        }else{
            $('.occupantSection').removeClass('pb-2');
            $('.occupantTableWrap').fadeOut('fast', function(){
                destroyJobAddressListTable();
                $('.addOccupantToggler').fadeOut();
            })
        }
    });

    $('#has_occupants').on('change', function (e) {
        let $theCheckbox = $(this);
        let isChecked = $theCheckbox.prop('checked');
        let propertyId = $theCheckbox.attr('data-propertyid');
        $theCheckbox.prop('disabled', true);
        
        axios({
            method: "POST",
            url: route('job-addresses.update.occupant.status'),
            data: {
                property_id: propertyId,
                has_occupants: isChecked ? 1 : 0,
            },
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(function (response) {
            if (isChecked) {
                $('.occupantSection').addClass('pb-2');
                $('.occupantTableWrap').fadeIn('fast', function () {
                    filterJobAddressListTable();
                    $('.addOccupantToggler').fadeIn();
                });
            } else {
                $('.occupantSection').removeClass('pb-2');
                $('.occupantTableWrap').fadeOut('fast', function () {
                    destroyJobAddressListTable();
                    $('.addOccupantToggler').fadeOut();
                });
            }
        }).catch(function (error) {
            console.error('Error:', error);
        }).finally(function () {
            $theCheckbox.prop('disabled', false);
        });
    });

    $('#addOccupantForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addOccupantForm');
        const $theForm = $(this);
        
        $('#addOccupantForm .acc__input-error').html('');
        $('#addOccupantBtn', $theForm).attr('disabled', 'disabled');
        $("#addOccupantBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('job-addresses.occupant.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#addOccupantBtn', $theForm).removeAttr('disabled');
            $("#addOccupantBtn .theLoader").fadeOut();

            if (response.status == 200) {
                addOccupantModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });
                setTimeout(() => {
                    successModal.hide();
                }, 1500);
            }
            filterJobAddressListTable();
        }).catch(error => {
            $('#addOccupantBtn', $theForm).removeAttr('disabled');
            $("#addOccupantBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#addOccupantForm .${key}`).addClass('border-danger');
                        $(`#addOccupantForm  .error-${key}`).html(val);
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

    $(document).on('click', '.occupantWrap', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let occupant_id = $theBtn.attr('data-id');
        let customer_id = $theBtn.attr('data-customerid');

        axios({
            method: "post",
            url: route('job-addresses.occupant.edit'),
            data: {occupant_id : occupant_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                let row = response.data.row;

                editOccupantModal.show();
                document.getElementById("editOccupantModal").addEventListener("shown.tw.modal", function (event) {
                    $('#editOccupantModal [name="occupant_name"]').val(row.occupant_name ? row.occupant_name : '');
                    $('#editOccupantModal [name="occupant_phone"]').val(row.occupant_phone ? row.occupant_phone : '');
                    $('#editOccupantModal [name="occupant_email"]').val(row.occupant_email ? row.occupant_email : '');
                    $('#editOccupantModal [name="due_date"]').val(row.due_date ? row.due_date : '');
                    $('#editOccupantModal [name="id"]').val(occupant_id);
                });
            }
        }).catch(error => {
            if (error.response) {
                console.log('error');
            }
        });
    })   

    $('#editOccupantForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('editOccupantForm');
        const $theForm = $(this);
        
        $('#editOccupantForm .acc__input-error').html('');
        $('#editOccupantBtn', $theForm).attr('disabled', 'disabled');
        $("#editOccupantBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('job-addresses.occupant.update'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#editOccupantBtn', $theForm).removeAttr('disabled');
            $("#editOccupantBtn .theLoader").fadeOut();
            console.log(response.data);
            if (response.status == 200) {
                editOccupantModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });
                setTimeout(() => {
                    successModal.hide();
                }, 1500);
            }
            filterJobAddressListTable();
        }).catch(error => {
            $('#editOccupantBtn', $theForm).removeAttr('disabled');
            $("#editOccupantBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#editOccupantForm .${key}`).addClass('border-danger');
                        $(`#editOccupantForm  .error-${key}`).html(val);
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