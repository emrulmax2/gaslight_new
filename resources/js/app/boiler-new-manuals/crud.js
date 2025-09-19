

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
    $("#upload-excel").on("click", function () {
        const el = document.querySelector("#upload-excel-modal");
        const modal = tailwind.Modal.getOrCreateInstance(el);
        modal.toggle();
    });

    $('#createForm').on('change', '#addManualDocument', function(){
        showFileName('addManualDocument', 'addManualName');
    });

    $('#updateForm').on('change', '#editManualDocument', function(){
        showFileName('editManualDocument', 'editManualName');
    });
    
    function showFileName(inputId, targetPreviewId) {
        let fileInput = document.getElementById(inputId);
        let namePreview = document.getElementById(targetPreviewId);
        let fileName = fileInput.files[0].name;
        namePreview.innerHTML = '<span class="text-success inline-flex items-start whitespace-normal justify-start"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 mr-2 h-4 w-4"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>'+fileName+'</span>';
        return false;
    };
    
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('createForm');
        let $theForm = $(this);
        
        $('#userSaveBtn', $theForm).attr('disabled');
        $("#userSaveBtn .theLoader").fadeIn();

        let formData = new FormData(form);
        formData.append('file', $('#createForm input[name="document"]')[0].files[0]); 
        axios({
            method: "post",
            url: route('superadmin.boiler-new-manual.store'),
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
                        $("#warningModal .warningModalDesc").html(error.response.data.message);
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
            url: route('superadmin.boiler-new-manual.update', $editId),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#UpdateBtn', $theForm).removeAttr('disabled');
            $("#UpdateBtn .theLoader").fadeOut();
            if (response.status == 200 ) {
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
                        $("#warningModal .warningModalDesc").html(error.response.data.message);
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
            url: route('superadmin.boiler-new-manual.import'),
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
