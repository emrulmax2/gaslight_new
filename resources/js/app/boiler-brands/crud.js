

(function () {
    "use strict";

    const successModal = tailwind.Modal.getOrCreateInstance(document.getElementById('successModal'));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.getElementById('warningModal'));
    const addNewModal = tailwind.Modal.getOrCreateInstance(document.getElementById('addnew-modal'));
    const editModal = tailwind.Modal.getOrCreateInstance(document.getElementById('edit-modal'));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));

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

    // Show modal
    $("#add-new").on("click", function () {
        const el = document.querySelector("#addnew-modal");
        const modal = tailwind.Modal.getOrCreateInstance(el);
        modal.toggle();
    });
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('createForm');

        let $theForm = $(this);
        
        $('#userSaveBtn', $theForm).attr('disabled');
        $("#userSaveBtn .theLoader").fadeIn();

        let formData = new FormData(form);
        axios({
            method: "post",
            url: route('superadmin.boiler-brand.store'),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();
            
            if (response.status == 201) {
                addNewModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.message);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.reload();
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
        let $theForm = $(this);
        
        $('#UpdateBtn', $theForm).attr('disabled');
        $("#UpdateBtn .theLoader").fadeIn();

        let formData = new FormData(form);
        let $editId =  $('#edit-modal input[name="id"]').val();
        axios({
            method: "post",
            url: route('superadmin.boiler-brand.update', $editId),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#UpdateBtn', $theForm).removeAttr('disabled');
            $("#UpdateBtn .theLoader").fadeOut();
            if (response.status == 204 ) {
                editModal.hide();
                
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.message);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.reload();
                }, 1500);
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
})();
