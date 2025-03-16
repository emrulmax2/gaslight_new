import Litepicker from "litepicker";
(function(){
    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));

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

    /* BEGIN: Update Job */
    $('#updateJobForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updateJobForm');
        const $theForm = $(this);
        
        $('#jobUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#jobUpdateBtn .theLoader").fadeIn();

        const urlParams = new URLSearchParams(window.location.search);
        const recordParam = urlParams.get('record');

        let form_data = new FormData(form);

        if(recordParam){
            form_data.append('record', recordParam);
        }
        axios({
            method: "post",
            url: route('jobs.update'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#jobUpdateBtn', $theForm).removeAttr('disabled');
            $("#jobUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.href = response.data.red;
                }, 1500);
            }
        }).catch(error => {
            $('#jobUpdateBtn', $theForm).removeAttr('disabled');
            $("#jobUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#updateJobForm .${key}`).addClass('border-danger');
                        $(`#updateJobForm  .error-${key}`).html(val);
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
    /* END: Update Job */


    let dateOption = {
        autoApply: true,
        singleMode: true,
        numberOfColumns: 1,
        numberOfMonths: 1,
        showWeekNumbers: false,
        minDate: new Date() - 1,
        inlineMode: false,
        format: "DD-MM-YYYY",
        dropdowns: {
            minYear: 1900,
            maxYear: 2050,
            months: true,
            years: true,
        },
    };
    const jobCalenderDate = new Litepicker({
        element: document.getElementById('job_calender_date'),
        ...dateOption
    });

    const calenderSlot = document.querySelector('.calenderSlot');

    jobCalenderDate.on('selected', (date) => {
        if(date){
            calenderSlot.classList.remove('hidden');
        }else{
            calenderSlot.classList.add('hidden');
        }
    });
})();