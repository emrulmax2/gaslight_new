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

    /* Appliance Auto Load Start */
    if(localStorage.appliances){
        let appliances = localStorage.getItem('appliances');
        let applianceObj = JSON.parse(appliances);
        
        $('.applianceBlock').find('.theDesc').html((applianceObj.appliance_title && applianceObj.appliance_title != '' ? applianceObj.appliance_title : 'N/A')).addClass('font-medium');
    }

    /* Appliance Auto Load End */
    if(localStorage.applianceAnswered){
        let questionAnswered = localStorage.getItem('applianceAnswered');
        $('.applianceBlock .theDesc').html((questionAnswered != '' ? questionAnswered : '0')+'/5');
    }

    $('.applianceBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let appliances = localStorage.getItem('appliances');
        
        applianceModal.show();
        document.getElementById("applianceModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.appliances){
                let applianceObj = JSON.parse(appliances);
                
                if(!$.isEmptyObject(applianceObj)){
                    for (const [key, value] of Object.entries(applianceObj)) {
                        if(key == 'work_type' && value.length > 0){
                            for(let i = 0; i < value.length; i++){
                                $('#applianceModal #work_type_'+value[i]).prop('checked', true);
                            }
                        }else{
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
            }
        });
    });

    // Toggle N/A Button
    $(document).on('click', '.naToggleBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let thevalue = $theBtn.attr('data-value');

        $theBtn.siblings('input').val(thevalue);
        //formDataChanged = true;
    })

    $('#applianceForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('applianceForm');
        const $theForm = $(this);

        $('#saveApplianceBtn', $theForm).attr('disabled', 'disabled');
        $("#saveApplianceBtn .theLoader").fadeIn();

        
        let appliance_serial = $theForm.find('[name="appliance_serial"]').val() * 1;
        let edit = $theForm.find('[name="edit"]').val();

        let form_data = $theForm.serializeArray();
        let formated_data = getFormatedData(form_data);

        let questionAnswered = 0;
        if(!$.isEmptyObject(formated_data)){
            for (const [key, value] of Object.entries(formated_data)) {
                if(value != '' && key != 'appliance_serial'){
                    questionAnswered += 1;
                }
            }
        }
        localStorage.setItem('applianceAnswered', questionAnswered);
        $('.applianceBlock .theDesc').html(questionAnswered+'/5');
        
        localStorage.setItem('appliances', JSON.stringify(formated_data));

        
        $('#saveApplianceBtn', $theForm).removeAttr('disabled');
        $("#saveApplianceBtn .theLoader").fadeOut();
        applianceModal.hide();
    });

    function getFormatedData(formData){
        let theObject = {};
        let workType = [];
        for (var i = 0; i < formData.length; i++) {
            let theData = formData[i];
            let name = theData.name;
            let values = theData.value;
            if(name == 'work_type[]'){
                workType.push(values);
            }else{
                theObject[name] = values;
            }
        }
        theObject['work_type'] = workType;

        return theObject;
    }

})();