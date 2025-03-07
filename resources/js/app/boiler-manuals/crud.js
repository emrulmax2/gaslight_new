

(function () {
    "use strict";

    const successModal = tailwind.Modal.getOrCreateInstance(document.getElementById('successModal'));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.getElementById('warningModal'));
    const addNewModal = tailwind.Modal.getOrCreateInstance(document.getElementById('addnew-modal'));
    const editModal = tailwind.Modal.getOrCreateInstance(document.getElementById('edit-modal'));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    // Show modal
    $("#add-new").on("click", function () {
        const el = document.querySelector("#addnew-modal");
        const modal = tailwind.Modal.getOrCreateInstance(el);
        modal.toggle();
    });
    $("#upload-excel").on("click", function () {
        const el = document.querySelector("#upload-excel-modal");
        const modal = tailwind.Modal.getOrCreateInstance(el);
        modal.toggle();
    });
    
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('createForm');
        //const form2 = document.getElementById('addStaffForm');


        let $theForm = $(this);
        
        $('#userSaveBtn', $theForm).attr('disabled');
        $("#userSaveBtn .theLoader").fadeIn();

        let formData = new FormData(form);
        
        // const form2Data = new FormData(form2);

        // for (const [key, value] of form2Data.entries()) {
        //     formData.append(key, value);
        // }
        
        axios({
            method: "post",
            url: route('superadmin.boiler-manual.store'),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();
            console.log(response.data);
            if (response.status == 201) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.message);
                    //$("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    location.reload();
                }, 1500);
            }
        }).catch(error => {
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#createForm .${key}`).addClass('border-danger');
                        $(`#createForm  .error-${key}`).html(val);
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


    $('#updateForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('updateForm');
        //const form2 = document.getElementById('addStaffForm');


        let $theForm = $(this);
        
        $('#UpdateBtn', $theForm).attr('disabled');
        $("#UpdateBtn .theLoader").fadeIn();

        let formData = new FormData(form);
        
        // const form2Data = new FormData(form2);

        // for (const [key, value] of form2Data.entries()) {
        //     formData.append(key, value);
        // }
        let $editId =  $('#edit-modal input[name="id"]').val();
        axios({
            method: "post",
            url: route('superadmin.boiler-manual.update',$editId),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#UpdateBtn', $theForm).removeAttr('disabled');
            $("#UpdateBtn .theLoader").fadeOut();
            if (response.status == 200 ) {
                
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.message);
                });
                successModal.show();
                setTimeout(() => {
                    successModal.hide();
                    location.reload();
                }, 5500);
            }
        }).catch(error => {
            $('#UpdateBtn', $theForm).removeAttr('disabled');
            $("#UpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#edit-modal .${key}`).addClass('border-danger');
                        $(`#edit-modal  .error-${key}`).html(val);
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


    $('#uploadExcelForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('uploadExcelForm');
        //const form2 = document.getElementById('addStaffForm');


        let $theForm = $(this);
        
        $('#userSaveBtn', $theForm).attr('disabled');
        $("#userSaveBtn .theLoader").fadeIn();

        let formData = new FormData(form);
        
        // const form2Data = new FormData(form2);

        // for (const [key, value] of form2Data.entries()) {
        //     formData.append(key, value);
        // }
        
        axios({
            method: "post",
            url: route('superadmin.boiler-manual.import'),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();
            console.log(response.data);
            if (response.status == 201) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.message);
                    //$("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    location.reload();
                }, 1500);
            }
        }).catch(error => {
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#createForm .${key}`).addClass('border-danger');
                        $(`#createForm  .error-${key}`).html(val);
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
