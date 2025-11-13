import Litepicker from 'litepicker';

("use strict");
var customerJobListTable = (function () {
    var _tableGen = function () {

        let $activeStatus = $(document).find('.jobStatsDropdown .jobStatusBtn.active');
        let status = $activeStatus.attr('data-status');
        let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let customer_id = $("#customer_id").val() != "" ? $("#customer_id").val() : "";


        axios({
            method: 'get',
            url: route('customer.jobs.list', {customer_id: customer_id, querystr: querystr, status : status}),
            data: {customer_id: customer_id, querystr: querystr, status : status},
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
    const jobStatusDropdown = tailwind.Dropdown.getOrCreateInstance(document.querySelector("#jobStatusDropdown"));
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

        $(document).on('click', '.jobStatusBtn', function(e){
            let $theBtn = $(this);
            let theStatus = $theBtn.attr('data-status');
            let label = $theBtn.attr('data-label');

            $(document).find('.jobStatsDropdown .jobStatusBtn').removeClass('active');
            $theBtn.addClass('active');
            $(document).find('.jobStatsuSelected .label').html(label);
            
            jobStatusDropdown.hide();
            customerJobListTable.init();
        })

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

    jobCalenderDate.on('selected', (date) => {
        if(date){
            let theDate = date.dateInstance.toLocaleDateString('en-GB').replace(/\//g, "-");
            axios({
                method: "post",
                url: route('jobs.get.slot.status'),
                data: {date : theDate},
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    let max = response.data.max;
                    let jobs = response.data.jobs;
                    $('#addJobCalenderModal .jobSlotWrap').fadeIn('fast', function(){
                        $('[type="radio"]', this).prop('checked', false).removeAttr('disabled');
                        if (!$.isEmptyObject(jobs)) {
                            $.each(jobs, function(index, job) {
                                if(job.totalJob >= max)
                                $('#calendar_time_slots_'+job.calendar_time_slot_id).attr('disabled', 'disabled')
                            })
                        }
                    
                    })
                }
            }).catch(error => {
                if (error.response) {
                   onsole.log('error');
                    $('#addJobCalenderModal .timeSloatWrap').fadeOut('fast', function(){
                        $('[type="radio"]', this).prop('checked', false).removeAttr('disabled');
                    })
                }
            });
            
        }else{
            $('.timeSloatWrap').fadeOut('fast', function(){
                $('[type="radio"]', this).prop('checked', false).removeAttr('disabled');
            })
        }
    });

    // $('#customerJobListTable').on('click', '.addCalenderBtn', function(e){
    //     e.preventDefault();
    //     let $theBtn = $(this);
    //     let customer_job_id = $theBtn.attr('data-id');

    //     axios({
    //         method: "post",
    //         url: route('jobs.get.calendar.details'),
    //         data: {customer_job_id : customer_job_id},
    //         headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
    //     }).then(response => {
    //         if (response.status == 200) {
    //             var row = response.data.row;
    //             $('#addJobCalenderModal input[name="customer_job_id"]').val(customer_job_id);
    //             $('#addJobCalenderModal input[name="date"]').val(row.date);
    //             $('#addJobCalenderModal input#slot-'+row.calendar_time_slot_id).prop('checked', true);
    //         }
    //     }).catch(error => {
    //         if (error.response) {
    //             console.log('error');
    //         }
    //     });
    // });

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

    var clickTimeout;
    var isDoubleClick = false;

    // $('#customerJobListTable').on('click', '.customerJobItem', function(e) {

    //     let $theBtn = $(this);
    //     let edit_url = $theBtn.attr('edit_url');

    //     clickTimeout = setTimeout(function() {
    //         if (!isDoubleClick) {
    //             window.location.href = edit_url;
    //         } else {
    //             isDoubleClick = false;
    //         }
    //     }, 200);
    // });
    $('#customerJobListTable').on('click', '.JobListItem', function(event) {
        let $target = $(event.target);

        if ($target.is('.addCalenderBtn') || $target.is('.addCalenderChild')) {
            let $parents = $target.closest('.JobListItem');
            let $theBtn = $parents.find('.addCalenderBtn');
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
                    if(row.date != ''){
                        $('#addJobCalenderModal .jobSlotWrap').fadeIn('fast', function(){
                            let max = response.data.max;
                            let jobs = response.data.jobs;
                            $('#addJobCalenderModal .jobSlotWrap').fadeIn('fast', function(){
                                $('[type="radio"]', this).prop('checked', false).removeAttr('disabled');
                                $('#addJobCalenderModal input#calendar_time_slots_'+row.calendar_time_slot_id).prop('checked', true);
                                if (!$.isEmptyObject(jobs)) {
                                    $.each(jobs, function(index, job) {
                                        if(job.totalJob >= max){
                                            $('#addJobCalenderModal #calendar_time_slots_'+job.calendar_time_slot_id).attr('disabled', 'disabled')
                                        }
                                    })
                                }
                            
                            })
                        })
                    }else{
                        $('#addJobCalenderModal .jobSlotWrap').fadeOut('fast', function(){
                            $('#addJobCalenderModal input[type="radio"]').prop('checked', false).removeAttr('disabled');
                        })
                    }
                }
            }).catch(error => {
                if (error.response) {
                    console.log('error');
                }
            });
        } else {
            let $theBtn = $(this);
            let show_url = $theBtn.attr('show_url');

            clickTimeout = setTimeout(function() {
                if (!isDoubleClick) {
                    window.location.href = show_url;
                } else {
                    isDoubleClick = false;
                }
            }, 200);
        }
    });

})();