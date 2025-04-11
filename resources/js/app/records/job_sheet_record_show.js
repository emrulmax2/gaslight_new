

(function(){
    'use strict';

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
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

    $('#gasJobSheetRecordForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('gasJobSheetRecordForm');
        let $theForm = $(this);
        let type = $theForm.find('[name="submit_type"]').val();
        let gjsr_id = $theForm.find('#gjsr_id').val();

        $('.formSubmits', $theForm).attr('disabled', 'disabled');
        $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeIn();
        $('.formSubmits.submit_'+type, $theForm).addClass('active');

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('records.gjsr.store', gjsr_id),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('.formSubmits', $theForm).removeAttr('disabled');
            $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeOut();
            $('.formSubmits.submit_'+type, $theForm).removeClass('active');

            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    if(response.data.red){
                        window.location.href = response.data.red
                    }
                }, 1500);
            }
        }).catch(error => {
            $('.formSubmits', $theForm).removeAttr('disabled');
            $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeOut();
            $('.formSubmits.submit_'+type, $theForm).removeClass('active');
            if (error.response) {
                if (error.response.status == 422) {
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
})()