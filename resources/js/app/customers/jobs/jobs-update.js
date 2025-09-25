(function(){
    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const updateJobDataModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updateJobDataModal"));
    const jobDtlDescModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#jobDtlDescModal"));
    const updatePriorityModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatePriorityModal"));
    const updateStatusModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updateStatusModal"));
    const updateApointDateModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updateApointDateModal"));
    const statusUpdateModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#statusUpdateModal"));

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
    
    document.getElementById('updateJobDataModal').addEventListener('hide.tw.modal', function(event) {
        $('#updateJobDataModal .acc__input-error').html('');
        $('#updateJobDataModal .fieldTitle').text('Value');
        $('#updateJobDataModal .requiredLabel').addClass('hidden');
        $('#updateJobDataModal input[name="fieldValue"]').val('');
        $('#updateJobDataModal input[name="fieldName"]').val('');
    });
    
    document.getElementById('jobDtlDescModal').addEventListener('hide.tw.modal', function(event) {
        $('#jobDtlDescModal .acc__input-error').html('');
        $('#jobDtlDescModal .fieldTitle').text('Value');
        $('#jobDtlDescModal .requiredLabel').addClass('hidden');
        $('#jobDtlDescModal [name="fieldValue"]').val('');
        $('#jobDtlDescModal [name="fieldName"]').val('');
    });

    /* BEGIN: Update Job */
    $('#updateJobForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updateJobForm');
        const $theForm = $(this);
        
        $('#jobUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#jobUpdateBtn .theLoader").fadeIn();

        let customer_job_id = $theForm.find('[name="customer_job_id"]').val();
        let customer_id = $theForm.find('[name="customer_id"]').val();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('customer.jobs.update', {customer_id: customer_id, customer_job_id: customer_job_id }),
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
                    $('#updateApointDateModal .jobSlotWrap').fadeIn('fast', function(){
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
                    $('#updateApointDateModal .jobSlotWrap').fadeOut('fast', function(){
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


    $(document).on('click', '.fieldValueToggler', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let theTitle = $theBtn.attr('data-title');
        let theField = $theBtn.attr('data-field');
        let theValue = $theBtn.attr('data-value');
        let theRequired = $theBtn.attr('data-required');
        let theType = $theBtn.attr('data-type');

        updateJobDataModal.show();
        document.getElementById('updateJobDataModal').addEventListener('shown.tw.modal', function(event){
            $('#updateJobDataModal .fieldTitle').text(theTitle);
            if(theRequired == 1){
                $('#updateJobDataModal .requiredLabel').removeClass('hidden');
                $('#updateJobDataModal input[name="fieldValue"]').val(theValue).addClass('require');
            }else{
                $('#updateJobDataModal .requiredLabel').addClass('hidden');
                $('#updateJobDataModal input[name="fieldValue"]').val(theValue).removeClass('require');
            }
            if(theType == 'email'){
                $('#updateJobDataModal input[name="fieldValue"]').attr('type', 'email');
            }else if(theType == 'number'){
                $('#updateJobDataModal input[name="fieldValue"]').attr('type', 'number').attr('step', 'any');
            }else{
                $('#updateJobDataModal input[name="fieldValue"]').attr('type', 'text');
            }
            $('#updateJobDataModal input[name="fieldName"]').val(theField);
        });
    })

    $('#updateJobDataForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updateJobDataForm');
        const $theForm = $(this);
        
        $('#updateJobDataModal .acc__input-error').html('');
        $('#updateDataBtn', $theForm).attr('disabled', 'disabled');
        $("#updateDataBtn .theLoader").fadeIn();

        let errors = 0;
        $theForm.find('.require').each(function(){
            if($(this).val() == ''){
                errors += 1;
                $(this).siblings('.acc__input-error').html('This field is required.')
            }
        });

        if(errors > 0){
            $('#updateDataBtn', $theForm).removeAttr('disabled');
            $("#updateDataBtn .theLoader").fadeOut();

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('customer.jobs.update.data'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#updateDataBtn', $theForm).removeAttr('disabled');
                $("#updateDataBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    updateJobDataModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#updateDataBtn', $theForm).removeAttr('disabled');
                $("#updateDataBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updateJobDataForm .${key}`).addClass('border-danger');
                            $(`#updateJobDataForm  .error-${key}`).html(val);
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
        }
    });


    $(document).on('click', '.textValueToggler', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let theTitle = $theBtn.attr('data-title');
        let theField = $theBtn.attr('data-field');
        let theValue = $theBtn.attr('data-value');
        let theRequired = $theBtn.attr('data-required');
        let theType = $theBtn.attr('data-type');

        jobDtlDescModal.show();
        document.getElementById('jobDtlDescModal').addEventListener('shown.tw.modal', function(event){
            $('#jobDtlDescModal .fieldTitle').text(theTitle);
            if(theRequired == 1){
                $('#jobDtlDescModal .requiredLabel').removeClass('hidden');
                $('#jobDtlDescModal [name="fieldValue"]').val(theValue).addClass('require');
            }else{
                $('#jobDtlDescModal .requiredLabel').addClass('hidden');
                $('#jobDtlDescModal [name="fieldValue"]').val(theValue).removeClass('require');
            }
            $('#jobDtlDescModal [name="fieldName"]').val(theField);
        });
    })

    $('#jobDtlDescForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('jobDtlDescForm');
        const $theForm = $(this);
        
        $('#jobDtlDescModal .acc__input-error').html('');
        $('#updateTextBtn', $theForm).attr('disabled', 'disabled');
        $("#updateTextBtn .theLoader").fadeIn();

        let errors = 0;
        $theForm.find('.require').each(function(){
            if($(this).val() == ''){
                errors += 1;
                $(this).siblings('.acc__input-error').html('This field is required.')
            }
        });

        if(errors > 0){
            $('#updateTextBtn', $theForm).removeAttr('disabled');
            $("#updateTextBtn .theLoader").fadeOut();

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('customer.jobs.update.data'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#updateTextBtn', $theForm).removeAttr('disabled');
                $("#updateTextBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    jobDtlDescModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#updateTextBtn', $theForm).removeAttr('disabled');
                $("#updateTextBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#jobDtlDescForm .${key}`).addClass('border-danger');
                            $(`#jobDtlDescForm  .error-${key}`).html(val);
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
        }
    });

    $('#updatePriorityForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updatePriorityForm');
        const $theForm = $(this);
        
        $('#updatePriorityModal .acc__input-error').html('');
        $('#savePriorityBtn', $theForm).attr('disabled', 'disabled');
        $("#savePriorityBtn .theLoader").fadeIn();

        let errors = 0;
        if($theForm.find('[name="fieldValue"]:checked').length == 0){
            errors += 1;
            $theForm.find('.error-fieldValue').html('This field is required.')
        }

        if(errors > 0){
            $('#savePriorityBtn', $theForm).removeAttr('disabled');
            $("#savePriorityBtn .theLoader").fadeOut();

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('customer.jobs.update.data'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#savePriorityBtn', $theForm).removeAttr('disabled');
                $("#savePriorityBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    updatePriorityModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#savePriorityBtn', $theForm).removeAttr('disabled');
                $("#savePriorityBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updatePriorityForm .${key}`).addClass('border-danger');
                            $(`#updatePriorityForm  .error-${key}`).html(val);
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
        }
    });

    $('#updateStatusForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updateStatusForm');
        const $theForm = $(this);
        
        $('#updateStatusModal .acc__input-error').html('');
        $('#saveStatusBtn', $theForm).attr('disabled', 'disabled');
        $("#saveStatusBtn .theLoader").fadeIn();

        let errors = 0;
        if($theForm.find('[name="fieldValue"]:checked').length == 0){
            errors += 1;
            $theForm.find('.error-fieldValue').html('This field is required.')
        }

        if(errors > 0){
            $('#saveStatusBtn', $theForm).removeAttr('disabled');
            $("#saveStatusBtn .theLoader").fadeOut();

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('customer.jobs.update.data'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#saveStatusBtn', $theForm).removeAttr('disabled');
                $("#saveStatusBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    updateStatusModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#saveStatusBtn', $theForm).removeAttr('disabled');
                $("#saveStatusBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updateStatusForm .${key}`).addClass('border-danger');
                            $(`#updateStatusForm  .error-${key}`).html(val);
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
        }
    });

    $('#updateApointDateForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updateApointDateForm');
        const $theForm = $(this);
        
        $('#updateApointDateModal .acc__input-error').html('');
        $('#updateAptBtn', $theForm).attr('disabled', 'disabled');
        $("#updateAptBtn .theLoader").fadeIn();

        let errors = 0;
        if($theForm.find('[name="fieldValue"]:checked').length == 0){
            errors += 1;
            $theForm.find('.error-fieldValue').html('This field is required.')
        }
        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('customer.jobs.update.appointment.date'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#updateAptBtn', $theForm).removeAttr('disabled');
            $("#updateAptBtn .theLoader").fadeOut();

            if (response.status == 200) {
                updateApointDateModal.hide();
                window.location.reload();
            }
        }).catch(error => {
            $('#updateAptBtn', $theForm).removeAttr('disabled');
            $("#updateAptBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#updateApointDateForm .${key}`).addClass('border-danger');
                        $(`#updateApointDateForm  .error-${key}`).html(val);
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

        $('.jobStatusUpdateModal').on('click', function(e) {

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
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                });
            }
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