
(function(){
    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const inviteaFriendModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#inviteaFriendModal"));
    const sendSmsInvitationModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#sendSmsInvitationModal"));

    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .successCloser').attr('data-action', 'NONE').attr('data-red', '');
    });

    $('#successModal .successCloser').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        if($theBtn.attr('data-action') == 'RELOAD'){
            if($theBtn.attr('data-red') != ''){
                window.location.href = $theBtn.attr('data-red');
            }else{
                window.location.reload();
            }
        }else{
            successModal.hide();
        }
    });

    document.getElementById('sendSmsInvitationModal').addEventListener('hide.tw.modal', function(event) {
        $('#sendSmsInvitationModal #phone_numbers').val('');
        $('#sendSmsInvitationModal #messages').val($('#sendSmsInvitationModal #messages').attr('data-text'));
    });

    $(document).on('click', '#copyCodeBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let theCode = $theBtn.attr('data-code');
        navigator.clipboard.writeText(theCode);

        $theBtn.html('Copied!');
        setTimeout(() => {
            $theBtn.html('Copy');
        }, 2000);
    });

    $('#sendSmsInvitationForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('sendSmsInvitationForm');
        const $theForm = $(this);
        
        $('#sendMessageBtn', $theForm).attr('disabled', 'disabled');
        $("#sendMessageBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('company.dashboard.send.invitation.sms'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#sendMessageBtn', $theForm).removeAttr('disabled');
            $("#sendMessageBtn .theLoader").fadeOut();

            if (response.status == 200) {
                sendSmsInvitationModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .successCloser").attr('data-action', 'NONE').attr('data-red', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                }, 1500);
            }
        }).catch(error => {
            $('#sendMessageBtn', $theForm).removeAttr('disabled');
            $("#sendMessageBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#sendSmsInvitationForm .${key}`).addClass('border-danger');
                        $(`#sendSmsInvitationForm  .error-${key}`).html(val);
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


})();