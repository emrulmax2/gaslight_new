import INTAddressLookUps from '../../address_lookup.js';

(function(){
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

    let tncTomOptions = {
        plugins: {
            dropdown_input: {}
        },
        placeholder: 'Search Here...',
        create: false,
        allowEmptyOption: true,
        onDelete: function (values) {
            return confirm( values.length > 1 ? "Are you sure you want to remove these " + values.length + " items?" : 'Are you sure you want to remove "' +values[0] +'"?' );
        },
    };

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const applianceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#applianceModal"));
    
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

    document.getElementById('applianceModal').addEventListener('hide.tw.modal', function(event) {
        $('#applianceModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#applianceModal textarea').val('');
        $('#applianceModal input[type="radio"]').prop('checked', false);
        $('#applianceModal input[type="checkbox"]').prop('checked', false);
        $('#applianceModal input[name="appliance_serial"]').val('1');
    });

    // Toggle N/A Button
    $(document).on('click', '.naToggleBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let thevalue = $theBtn.attr('data-value');

        $theBtn.siblings('input').val(thevalue);
        //formDataChanged = true;
    })

    /* Appliance Auto Load Start */
    if(localStorage.jobSheetAnswered){
        let questionAnswered = localStorage.getItem('jobSheetAnswered');
        $('.jobSheetBlock .theDesc').html((questionAnswered != '' ? questionAnswered : '0')+'/9').addClass('font-medium');
    }

    if(localStorage.jobSheetDocuments){
        let jobSheetDocuments = localStorage.getItem('jobSheetDocuments');
        if(jobSheetDocuments != ''){
            $('.customeUploads .customeUploadsContent').fadeOut('fast', function(){
                $('#uploadedFileWrap').fadeIn().html(JSON.parse(jobSheetDocuments));
                $('#job_sheet_files').val('');
            });
        }else{
            $('#uploadedFileWrap').fadeOut('fast', function(){
                $('.customeUploads .customeUploadsContent').fadeIn('fast', function(){
                    $('#job_sheet_files').val('');
                });
            }).html('');
        }
    }

    /* Appliance Auto Load End */
    $('.jobSheetBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let jobSheets = localStorage.getItem('jobSheets');

        applianceModal.show();
        document.getElementById("applianceModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.jobSheets){
                let jobSheetsObj = JSON.parse(jobSheets);
                if(!$.isEmptyObject(jobSheetsObj)){
                    for (const [key, value] of Object.entries(jobSheetsObj)) {
                        let $theInput = $('#applianceModal [name="'+key+'"]');
                        if($theInput.is('textarea')){
                            $theInput.val(value ? value : '');
                        }else{
                            if($theInput.attr('type') == 'radio'){
                                $('#applianceModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                            }else{
                                if(key != 'appliance_serial'){
                                    $theInput.val(value ? value : '');
                                }
                            }
                        }
                    }
                }
            }
        });
    });

    $('#applianceForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('applianceForm');
        const $theForm = $(this);

        $('#saveApplianceBtn', $theForm).attr('disabled', 'disabled');
        $("#saveApplianceBtn .theLoader").fadeIn();

        let form_data = $theForm.serializeArray();
        let formated_data = getFormatedData(form_data);

        let questionAnswered = 0;
        if(!$.isEmptyObject(formated_data)){
            for (const [key, value] of Object.entries(formated_data)) {
                if(value != ''){
                    questionAnswered += 1;
                }
            }
        }
        localStorage.setItem('jobSheetAnswered', questionAnswered);
        $('.jobSheetBlock .theDesc').html(questionAnswered+'/9').addClass('font-medium');
        localStorage.setItem('jobSheets', JSON.stringify(formated_data));

        
        $('#saveApplianceBtn', $theForm).removeAttr('disabled');
        $("#saveApplianceBtn .theLoader").fadeOut();
        applianceModal.hide();
    });

    function getFormatedData(formData){
        let theObject = {};
        for (var i = 0; i < formData.length; i++) {
            let theData = formData[i];
            let name = theData.name;
            let values = theData.value;
            theObject[name] = values;
        }

        return theObject;
    }

    /* Submit the Form */
    $(document).on('click', '#saveCertificateBtn', function(e){
        e.preventDefault();
        $('.gsfSignature .sign-pad-button-submit').trigger('click');
    });

    $('#certificateForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('certificateForm');
        const $theForm = $(this);
        let formData = new FormData(form);
        
        $('#saveCertificateBtn', $theForm).attr('disabled', 'disabled');
        $("#saveCertificateBtn .theLoader").fadeIn();

        let errors = {};
        if($theForm.find('[name="customer_id"]').val() == 0 || $theForm.find('[name="customer_id"]').val() == ''){
            errors['customer_id'] = 'Please select a customer.&nbsp;';
        }
        if($theForm.find('[name="customer_property_id"]').val() == 0 || $theForm.find('[name="customer_property_id"]').val() == ''){
            errors['customer_property_id'] = 'Job address can not be empty.&nbsp;';
        }
        if(localStorage.jobSheets){
            let jobSheets = localStorage.getItem('jobSheets');
            formData.append('jobSheets', jobSheets);
        }else{
            errors['jobSheets_error'] = 'Please fill out job sheet details.&nbsp;';
        }
        formData.append('file', $('#certificateForm input#job_sheet_files')[0].files[0]); 

        if($.isEmptyObject(errors)){
            axios({
                method: "post",
                url: route('new.records.gjsr.store.new'),
                data: formData,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    localStorage.clear();
                    window.location.href = response.data.red;
                }
            }).catch(error => {
                $('#saveCertificateBtn', $theForm).removeAttr('disabled');
                $("#saveCertificateBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#certificateForm .${key}`).addClass('border-danger');
                            $(`#certificateForm  .error-${key}`).html(val);
                        }
                    } else if (error.response.status == 304) {
                        warningModal.show();
                        document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                            $("#warningModal .warningModalTitle").html("Error Found!");
                            $("#warningModal .warningModalDesc").html(error.response.data.msg);
                        });

                        setTimeout(() => {
                            warningModal.hide();
                        }, 3000);
                    } else {
                        console.log('error');
                    }
                }
            });
        }else{
            let messages = '';
            for (const [key, value] of Object.entries(errors)) {
                if(value != ''){
                    messages += value+' ';
                }
            }

            $('#saveCertificateBtn', $theForm).removeAttr('disabled');
            $("#saveCertificateBtn .theLoader").fadeOut();

            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Validation Error Found!");
                $("#warningModal .warningModalDesc").html((messages != '' ? messages : 'Appliance, Safety checks, or Comments can not be empty.'));
            });

            setTimeout(() => {
                warningModal.hide();
            }, 3000);
        }
    })

    // Custom Upload
    $(document).on('change', '#job_sheet_files', function(){
        let input = document.getElementById('job_sheet_files');
        if (input.files) {
            const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/jpg'];
            var filesAmount = input.files.length;

            $('#uploadedFileWrap').find('.justIn').remove();

            for (var i = 0; i < filesAmount; i++) {
                let fileType = input.files[i]['type'];
                if(validImageTypes.includes(fileType)){
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $('#uploadedFileWrap').append('<span class="justIn inline-flex items-center justify-center image-fit h-[60px] w-[60px] bg-success bg-opacity-10 rounded-[3px] overflow-hidden mr-1 mb-1"><img class="rounded-[3px]" src="'+event.target.result+'"></span>');
                    }
            
                    reader.readAsDataURL(input.files[i]);
                }else{
                    $('#uploadedFileWrap').append('<span class="justIn inline-flex items-center justify-center h-[60px] w-[60px] bg-success bg-opacity-10 rounded-[3px] overflow-hidden mr-1 mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="file-text" class="lucide lucide-file-text stroke-1.5 h-8 w-8 text-success"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg></span>');
                }
            }

            $('.customeUploads .customeUploadsContent').fadeOut('fast', function(){
                $('#uploadedFileWrap').fadeIn();
            });
        }else{
            $('#uploadedFileWrap').fadeOut('fast', function(){
                $('.customeUploads .customeUploadsContent').fadeIn('fast', function(){
                    $('#job_sheet_files').val('');
                });
            })
        }
    });


    $(document).on('click', '.delete-doc', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let row_id = $theBtn.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete this document? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETEDOC');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETEDOC'){
            axios({
                method: 'delete',
                url: route('new.records.gjsr.destroy.document', row_id),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#gjsr_doc_'+row_id).remove();
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById('successModal').addEventListener('shown.tw.modal', function(event){
                        $('#successModal .successModalTitle').html('WOW!');
                        $('#successModal .successModalDesc').html(response.data.msg);
                        $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                    });

                    setTimeout(() => {
                        successModal.show();
                    }, 1500);
                }
            }).catch(error =>{
                console.log(error)
            });
        }
    });

})();