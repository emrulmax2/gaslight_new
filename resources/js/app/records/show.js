

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

    $('#recordForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('recordForm');
        let $theForm = $(this);
        let type = $theForm.find('[name="submit_type"]').val();
        let gsr_id = $theForm.find('#gsr_id').val();
        let form_slug = $theForm.find('#form_slug').val();

        $('.formSubmits', $theForm).attr('disabled', 'disabled');
        $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeIn();
        $('.formSubmits.submit_'+type, $theForm).addClass('active');

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('records.action'),
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

    $('.editRecordBtn').on('click', function(e){
        e.preventDefault();
        var $theBtn = $(this);
        var record_id = $theBtn.attr('data-id');

        $theBtn.addClass('active').attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();
        $theBtn.siblings('.action_btns').removeClass('active').attr('disabled', 'disabled');

        axios({
            method: "post",
            url: route('records.edit.ready'),
            data: {record_id : record_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                // console.log(response.data);
                // return false;

                let row = response.data.row;
                let form_id = response.data.form;
                
                localStorage.setItem('certificate_id', row.certificate_id);
                localStorage.setItem('certificate', JSON.stringify(row.certificate));
                localStorage.setItem('job', JSON.stringify(row.job));
                localStorage.setItem('customer', JSON.stringify(row.customer));
                localStorage.setItem('job_address', JSON.stringify(row.job_address));
                localStorage.setItem('occupant', JSON.stringify(row.occupant));


                if(form_id == 6 || form_id == 7){
                    localStorage.setItem('safetyChecksAnswered', row.safetyChecksAnswered);
                    localStorage.setItem('safetyChecks', JSON.stringify(row.safetyChecks));
                    localStorage.setItem('commentssAnswered', row.commentssAnswered);
                    localStorage.setItem('gsrComments', JSON.stringify(row.gsrComments));
                    localStorage.setItem('applianceCount', row.applianceCount);
                    localStorage.setItem('appliances', JSON.stringify(row.appliances));
                }else if(form_id == 8 || form_id == 9 || form_id == 10 || form_id == 13){
                    localStorage.setItem('appliances', JSON.stringify(row.appliances));
                }

                window.location.href = response.data.red
            }
        }).catch(error => {
            if (error.response) {
                console.log('error');
            }
        });
    })
})()