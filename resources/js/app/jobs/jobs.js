("use strict");
var jobListTable = (function () {
    var _tableGen = function () {

        let $activeStatus = $(document).find('.jobStatsDropdown .jobStatusBtn.active');
        let status = $activeStatus.attr('data-status');
        let querystr = $("#query").val() != "" ? $("#query").val() : "";

        axios({
            method: 'get',
            url: route('jobs.list', {querystr: querystr, status : status}),
            data: { querystr: querystr, status : status},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                $('#jobListTable').html(response.data.html);

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
    const jobStatusDropdown = tailwind.Dropdown.getOrCreateInstance(document.querySelector("#jobStatusDropdown"));

    if ($("#jobListTable").length) {
        jobListTable.init();

        function filterjobListTable() {
            jobListTable.init();
        }

        $("#query").on("keypress", function (e) {
            var key = e.keyCode || e.which;
            if(key === 13){
                e.preventDefault();
    
                jobListTable.init();
            }
        });

        $(document).on('click', '.jobStatusBtn', function(e){
            let $theBtn = $(this);
            let theStatus = $theBtn.attr('data-status');

            $(document).find('.jobStatsDropdown .jobStatusBtn').removeClass('active');
            $theBtn.addClass('active');
            $(document).find('.jobStatsuSelected .label').html(theStatus);
            
            jobStatusDropdown.hide();
            jobListTable.init();
        })

    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addJobCalenderModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addJobCalenderModal"));
    const statusUpdateModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#statusUpdateModal"));
    
    
    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
    });
    
    document.getElementById('addJobCalenderModal').addEventListener('hide.tw.modal', function(event) {
        $('#addJobCalenderModal input[name="customer_job_id"]').val('0');
        $('#addJobCalenderModal input[name="date"]').val('');
        $('#addJobCalenderModal input[type="radio"]').prop('checked', false);
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


    // Delete Trigger
    $('#jobListTable').on('click', '.delete_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete these record? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETEJOB');
        });
    });

    // Restore Trigger
    $('#jobListTable').on('click', '.restore_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to restore these record? Click on agree to continue.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'RESTOREJOB');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETEJOB'){
            let customer_id = ($('#jobListTable').attr('data-customerid') ? $('#jobListTable').attr('data-customerid') : 0);
            axios({
                method: 'delete',
                url: route('customers.jobs.destroy', [customer_id, row_id]),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById('successModal').addEventListener('shown.tw.modal', function(event){
                        $('#successModal .successModalTitle').html('WOW!');
                        $('#successModal .successModalDesc').html(response.data.msg);
                    });
                }
                jobListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RESTOREJOB'){
            let customer_id = ($('#jobListTable').attr('data-customerid') ? $('#jobListTable').attr('data-customerid') : 0);
            axios({
                method: 'post',
                url: route('customers.jobs.restore', [customer_id, row_id]),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById('successModal').addEventListener('shown.tw.modal', function(event){
                        $('#successModal .successModalTitle').html('WOW!');
                        $('#successModal .successModalDesc').html(response.data.msg);
                    });
                }
                jobListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }
    });

    $('#jobListTable').on('click', '.addCalenderBtn', function(e){
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
            jobListTable.init();
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

    var clickTimeout;
    var isDoubleClick = false;

    $('#jobListTable').on('click', '.JobListItem', function(e) {

        let $theBtn = $(this);
        let show_url = $theBtn.attr('show_url');

        clickTimeout = setTimeout(function() {
            if (!isDoubleClick) {
                window.location.href = show_url;
            } else {
                isDoubleClick = false;
            }
        }, 200);
    });



    $('#jobListTable').on('dblclick', '.JobListItem', function(e) {
        clearTimeout(clickTimeout);
        isDoubleClick = true;

        let $theBtn = $(this);
        let jobId = $theBtn.attr('data-id');
        let currentStatus = $theBtn.attr('data-status');

        function updateModalStatus(status) {
            $('#statusUpdateForm input[name="status"]').prop('checked', false);
            $('#statusUpdateForm input[name="customer_job_id"]').val(jobId);;

            if (status === 'Due') {
                $('#status_due').prop('checked', true);
            } else if (status === 'Completed') {
                $('#status_completed').prop('checked', true);
            } else if (status === 'Cancelled') {
                $('#status_cancelled').prop('checked', true);
            } else {
                console.warn('Unknown status:', status);
            }
        }

        updateModalStatus(currentStatus);

        statusUpdateModal.show();

        setTimeout(function() { isDoubleClick = false; }, 200);
    });

    $('#statusUpdateForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('statusUpdateForm');
        const $theForm = $(this);
        
        $('#updateStatusBtn', $theForm).attr('disabled', 'disabled');
        $("#updateStatusBtn .theLoader").fadeIn();
        let customerJobId = $theForm.find('[name="customer_job_id"]').val();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('customer.jobs.status.update', {customer_job_id: customerJobId,}),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#updateStatusBtn', $theForm).removeAttr('disabled');
            $("#updateStatusBtn .theLoader").fadeOut();

            if (response.status == 200) {
                statusUpdateModal.hide();

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
            jobListTable.init();
        }).catch(error => {
            $('#updateStatusBtn', $theForm).removeAttr('disabled');
            $("#updateStatusBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#statusUpdateForm .${key}`).addClass('border-danger');
                        $(`#statusUpdateForm  .error-${key}`).html(val);
                    }
                } else {
                    console.log('error');
                }
            }
        });
    })

})();