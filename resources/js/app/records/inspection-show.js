

(function(){
    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
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

    $('#monthlyReminderTable').on('click', '.sendReminderMailBtn', function(e){
        e.preventDefault();
        let $theTable = $('#monthlyReminderTable');
        let $theBtn = $(this);
        let record_id = $theBtn.attr('data-id')
    
        $theTable.find('.sendReminderMailBtn').attr('disabled', 'disabled');
        $theTable.find('.sendReminderMailBtn .theLoader').fadeOut();
        $theBtn.find(".theLoader").fadeIn();

        axios({
            method: "post",
            url: route('upcoming.inspection.send.reminder'),
            data: { record_id : record_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $theTable.find('.sendReminderMailBtn').removeAttr('disabled');
            $theTable.find('.sendReminderMailBtn .theLoader').fadeOut();

            if (response.status == 200) {

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.reload();
                }, 1500);
            }
        }).catch(error => {
            $theTable.find('.sendReminderMailBtn').removeAttr('disabled');
            $theTable.find('.sendReminderMailBtn .theLoader').fadeOut();
            if (error.response) {
                console.log('error');
            }
        });
    });
})()