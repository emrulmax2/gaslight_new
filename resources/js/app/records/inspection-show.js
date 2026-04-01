

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

    $(document).on('click', '.duplicateRecordBtn', function(e){
        e.preventDefault();
        let $theTable = $('#monthlyReminderTable');
        let $theBtn = $(this);
        let record_id = $theBtn.attr('data-id');

        $theTable.find('.duplicateRecordBtn').attr('disabled', 'disabled');
        $theTable.find('.duplicateRecordBtn .theLoader').fadeOut();
        $theBtn.find(".theLoader").fadeIn();

        localStorage.clear();
        localStorage.setItem('certificate_id', '0');

        axios({
            method: "post",
            url: route('records.edit.ready'),
            data: {record_id : record_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $theTable.find('.duplicateRecordBtn').removeAttr('disabled');
            $theTable.find('.duplicateRecordBtn .theLoader').fadeOut();
            if (response.status == 200) {

                // console.log(response.data.row);
                // return false;
                let row = response.data.row;
                let form_id = response.data.form;
                
                localStorage.setItem('certificate_id', 0);
                //localStorage.setItem('certificate_number', JSON.stringify(row.certificate_number));
                //localStorage.setItem('certificate', JSON.stringify(row.certificate));
                //localStorage.setItem('job', JSON.stringify(row.job));
                localStorage.setItem('customer', JSON.stringify(row.customer));
                localStorage.setItem('job_address', JSON.stringify(row.job_address));
                localStorage.setItem('occupant', JSON.stringify(row.occupant));
                localStorage.setItem('billing_address', JSON.stringify(row.billing_address));


                if(form_id == 6 || form_id == 7){
                    localStorage.setItem('safetyChecksAnswered', row.safetyChecksAnswered);
                    localStorage.setItem('safetyChecks', JSON.stringify(row.safetyChecks));
                    localStorage.setItem('commentssAnswered', row.commentssAnswered);
                    localStorage.setItem('gsrComments', JSON.stringify(row.gsrComments));
                    localStorage.setItem('applianceCount', row.applianceCount);
                    localStorage.setItem('appliances', JSON.stringify(row.appliances));
                }else if(form_id == 9){
                    localStorage.setItem('appliances', JSON.stringify(row.appliances));
                }

                window.location.href = route('records.create', form_id);
            }
        }).catch(error => {
            $theTable.find('.duplicateRecordBtn').removeAttr('disabled');
            $theTable.find('.duplicateRecordBtn .theLoader').fadeOut();
            if (error.response) {
                console.log('error');
            }
        });
    })
})()