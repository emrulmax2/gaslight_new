

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

    $('#createForm').on('change', '#boilerBrandLogoAdd', function(){
        showPreview('boilerBrandLogoAdd', 'boilerBrandLogoImgAdd')
    })

    $('#updateForm').on('change', '#boilerBrandLogoEdit', function(){
        showPreview('boilerBrandLogoEdit', 'boilerBrandLogoImgEdit')
    })

    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('createForm');

        let $theForm = $(this);
        
        $('#userSaveBtn', $theForm).attr('disabled');
        $("#userSaveBtn .theLoader").fadeIn();

        let formData = new FormData(form);
        formData.append('file', $('#createForm input[name="logo"]')[0].files[0]); 
        axios({
            method: "post",
            url: route('superadmin.boiler-new-brand.store'),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            console.log(response);
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
                    //window.location.reload();
                }, 1500);
            }
        }).catch(error => {
            console.log(error);
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
        formData.append('file', $('#updateForm input[name="logo"]')[0].files[0]); 
        let $editId =  $('#edit-modal input[name="id"]').val();
        axios({
            method: "post",
            url: route('superadmin.boiler-new-brand.update', $editId),
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

    document.querySelector('.brandDocumentInput').addEventListener('change', function(e) {
        let fileName = e.target.files.length ? e.target.files[0].name : 'Choose a file...';
        document.getElementById('brandDocumentfileName').textContent = fileName;
    });

   $('#downloadSampleBtn').on('click', function(e){
        e.preventDefault();

        let $btn = $(this);
        $btn.attr('disabled', true);
        $btn.find('.theLoader').fadeIn();

        axios({
            url: route('superadmin.boiler-new-brand.download.sample'),
            method: 'GET',
            responseType: 'blob',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'boiler-brand-sample.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }).catch((error) => {
            console.error(error);
        }).finally(() => {
            $btn.removeAttr('disabled');
            $btn.find('.theLoader').fadeOut();
        });
    });

    function showPreview(inputId, targetImageId) {
        var src = document.getElementById(inputId);
        var target = document.getElementById(targetImageId);
        var fr = new FileReader();
        fr.onload = function () {
            target.src = fr.result;
        }
        fr.readAsDataURL(src.files[0]);
    };

})();
