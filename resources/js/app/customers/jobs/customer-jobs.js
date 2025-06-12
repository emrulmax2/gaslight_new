("use strict");
var customerJobListTable = (function () {
    var _tableGen = function () {

        let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let customer_id = $("#customer_id").val() != "" ? $("#customer_id").val() : "";

        axios({
            method: 'get',
            url: route('customer.jobs.list', {customer_id: customer_id, querystr: querystr}),
            data: {customer_id: customer_id, querystr: querystr},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                $('#customerJobListTable').html(response.data.html);

                createIcons({
                    icons,
                    attrs: { "stroke-width": 1.5 },
                    nameAttr: "data-lucide",
                });
            }
        }).catch(error =>{
            console.log(error)
        });

        
    };
    return {
        init: function () {
            _tableGen();
        },
    };
})();


(function () {
    if ($("#customerJobListTable").length) {
        customerJobListTable.init();

        function filtercustomerJobListTable() {
            customerJobListTable.init();
        }

        $("#query").on("keypress", function (e) {
            var key = e.keyCode || e.which;
            if(key === 13){
                e.preventDefault();
    
                customerJobListTable.init();
            }
        });

    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addJobCalenderModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addJobCalenderModal"));
    
    
    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
    });
    
    document.getElementById('addJobCalenderModal').addEventListener('hide.tw.modal', function(event) {
        $('#addJobCalenderModal input[name="customer_job_id"]').val('0');
        $('#addJobCalenderModal input[name="date"]').val('');
        $('#addJobCalenderModal input[type="radio"]').prop('checked', false);
    });

    $('#customerJobListTable').on('click', '.addCalenderBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let customer_job_id = $theBtn.attr('data-id');

        axios({
            method: "post",
            url: route('jobs.get.calendar.details'),
            data: {customer_job_id : customer_job_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                var row = response.data.row;
                $('#addJobCalenderModal input[name="customer_job_id"]').val(customer_job_id);
                $('#addJobCalenderModal input[name="date"]').val(row.date);
                $('#addJobCalenderModal input#slot-'+row.calendar_time_slot_id).prop('checked', true);
            }
        }).catch(error => {
            if (error.response) {
                console.log('error');
            }
        });
    });

    $('#addJobCalenderForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addJobCalenderForm');
        const $theForm = $(this);
        
        $('#addCalendarBtn', $theForm).attr('disabled', 'disabled');
        $("#addCalendarBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('jobs.add.to.calendar'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#addCalendarBtn', $theForm).removeAttr('disabled');
            $("#addCalendarBtn .theLoader").fadeOut();

            if (response.status == 200) {
                addJobCalenderModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                });

                setTimeout(() => {
                    successModal.hide();
                }, 1500);
            }
            customerJobListTable.init();
        }).catch(error => {
            $('#addCalendarBtn', $theForm).removeAttr('disabled');
            $("#addCalendarBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#addJobCalenderForm .${key}`).addClass('border-danger');
                        $(`#addJobCalenderForm  .error-${key}`).html(val);
                    }
                } else {
                    console.log('error');
                }
            }
        });
    })

})();