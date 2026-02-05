import INTAddressLookUps from "../../address_lookup";

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        INTAddressLookUps();
    });

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
    const applianceSystemModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#applianceSystemModal"));
    const applianceInspectionModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#applianceInspectionModal"));
    
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

    document.getElementById('applianceSystemModal').addEventListener('hide.tw.modal', function(event) {
        $('#applianceSystemModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#applianceSystemModal textarea').val('');
        $('#applianceSystemModal input[type="radio"]').prop('checked', false);
        $('#applianceSystemModal input[type="checkbox"]').prop('checked', false);
        $('#applianceSystemModal input[name="appliance_serial"]').val('1');
    });

    document.getElementById('applianceInspectionModal').addEventListener('hide.tw.modal', function(event) {
        $('#applianceInspectionModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#applianceInspectionModal textarea').val('');
        $('#applianceInspectionModal input[type="radio"]').prop('checked', false);
        $('#applianceInspectionModal input[type="checkbox"]').prop('checked', false);
        $('#applianceInspectionModal input[name="appliance_serial"]').val('1');
    });

    // Toggle N/A Button
    $(document).on('click', '.naToggleBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let thevalue = $theBtn.attr('data-value');

        $theBtn.siblings('input').val(thevalue);
        //formDataChanged = true;
    })

    /* Unvented System Auto Load Start */
    if(localStorage.systemAnswered){
        let questionAnswered = localStorage.getItem('systemAnswered');
        $('.uhwSystemBlock .theDesc').html((questionAnswered != '' ? questionAnswered : '0')+'/13').addClass('font-medium');
    }

    $('.uhwSystemBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let unventedSystems = localStorage.getItem('unventedSystems');

        applianceSystemModal.show();
        document.getElementById("applianceSystemModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.unventedSystems){
                let unventedSystemsObj = JSON.parse(unventedSystems);
                if(!$.isEmptyObject(unventedSystemsObj)){
                    for (const [key, value] of Object.entries(unventedSystemsObj)) {
                        let $theInput = $('#applianceSystemModal [name="'+key+'"]');
                        if($theInput.is('textarea')){
                            $theInput.val(value ? value : '');
                        }else{
                            if($theInput.attr('type') == 'radio'){
                                $('#applianceSystemModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
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

    $('#applianceSystemForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('applianceSystemForm');
        const $theForm = $(this);

        $('#saveSystemBtn', $theForm).attr('disabled', 'disabled');
        $("#saveSystemBtn .theLoader").fadeIn();

        let form_data = $theForm.serializeArray();
        let formated_data = getFormatedData(form_data);

        let systemAnswered = 0;
        if(!$.isEmptyObject(formated_data)){
            for (const [key, value] of Object.entries(formated_data)) {
                if(value != ''){
                    systemAnswered += 1;
                }
            }
        }
        localStorage.setItem('systemAnswered', systemAnswered);
        $('.uhwSystemBlock').find('.theDesc').html(systemAnswered+'/13').addClass('font-medium');
        localStorage.setItem('unventedSystems', JSON.stringify(formated_data));

        
        $('#saveSystemBtn', $theForm).removeAttr('disabled');
        $("#saveSystemBtn .theLoader").fadeOut();
        applianceSystemModal.hide();
    });

    /* Inspection Record Auto Load Start */
    if(localStorage.inspectionAnswered){
        let questionAnswered = localStorage.getItem('inspectionAnswered');
        $('.inspectionRecBlock .theDesc').html((questionAnswered != '' ? questionAnswered : '0')+'/22').addClass('font-medium');
    }

    $('.inspectionRecBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let inspectionRecords = localStorage.getItem('inspectionRecords');

        applianceInspectionModal.show();
        document.getElementById("applianceInspectionModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.inspectionRecords){
                let inspectionRecordsObj = JSON.parse(inspectionRecords);
                if(!$.isEmptyObject(inspectionRecordsObj)){
                    for (const [key, value] of Object.entries(inspectionRecordsObj)) {
                        let $theInput = $('#applianceInspectionModal [name="'+key+'"]');
                        if($theInput.is('textarea')){
                            $theInput.val(value ? value : '');
                        }else{
                            if($theInput.attr('type') == 'radio'){
                                $('#applianceInspectionModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
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

    $('#applianceInspectionForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('applianceInspectionForm');
        const $theForm = $(this);

        $('#saveInspectionBtn', $theForm).attr('disabled', 'disabled');
        $("#saveInspectionBtn .theLoader").fadeIn();

        let form_data = $theForm.serializeArray();
        let formated_data = getFormatedData(form_data);

        let inspectionAnswered = 0;
        if(!$.isEmptyObject(formated_data)){
            for (const [key, value] of Object.entries(formated_data)) {
                if(value != ''){
                    inspectionAnswered += 1;
                }
            }
        }
        localStorage.setItem('inspectionAnswered', inspectionAnswered);
        $('.inspectionRecBlock').find('.theDesc').html(inspectionAnswered+'/22').addClass('font-medium');
        localStorage.setItem('inspectionRecords', JSON.stringify(formated_data));

        
        $('#saveInspectionBtn', $theForm).removeAttr('disabled');
        $("#saveInspectionBtn .theLoader").fadeOut();
        applianceInspectionModal.hide();
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

    

})();