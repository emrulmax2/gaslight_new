import { initGetAddressAutocomplete } from "../../getAddressAutocomplete";

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        initGetAddressAutocomplete({
            token: import.meta.env.VITE_GETADDRESS_API_KEY
        });
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
    let boiler_brand_id = new TomSelect(document.getElementById('boiler_brand_id'), tncTomOptions);
    let appliance_time_temperature_heating_id = new TomSelect(document.getElementById('appliance_time_temperature_heating_id'), tncTomOptions);

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

        boiler_brand_id.clear(true);
        appliance_time_temperature_heating_id.clear(true);
    });

    /* Appliance Auto Load Start */
    if(localStorage.appliances){
        let appliances = localStorage.getItem('appliances');
        let applianceObj = JSON.parse(appliances);
        
        $('.applianceBlock').find('.theDesc').html((applianceObj.appliance_title && applianceObj.appliance_title != '' ? applianceObj.appliance_title : 'N/A')).addClass('font-medium');
    }

    /* Appliance Auto Load End */
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
                        if(key == 'appliance_location_id'){
                            appliance_location_id.addItem(value)
                        }else if(key == 'boiler_brand_id'){
                            boiler_brand_id.addItem(value)
                        }else if(key == 'appliance_type_id'){
                            appliance_type_id.addItem(value)
                        }else if(key == 'appliance_time_temperature_heating_id'){
                            appliance_time_temperature_heating_id.addItem(value)
                        }else{
                            let $theInput = $('#applianceModal [name="'+key+'"]');
                            if($theInput.is('textarea')){
                                $theInput.val(value ? value : '');
                            }else{
                                if($theInput.attr('type') == 'radio'){
                                    if(key == 'is_in_hard_water_area'){
                                        if(value == 'Yes'){
                                            $('#applianceModal .maufacturerWrap').fadeIn()
                                        }else{
                                            $('#applianceModal .maufacturerWrap').fadeOut()
                                        }
                                    }
                                    if(key == 'is_scale_reducer_fitted'){
                                        if(value == 'Yes'){
                                            $('#applianceModal .reducerFittedWrap').fadeIn()
                                        }else{
                                            $('#applianceModal .reducerFittedWrap').fadeOut()
                                        }
                                    }
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

    $('#applianceModal input[name="is_in_hard_water_area"]').on('change', function(){
        let is_in_hard_water_area = $('#applianceModal input[name="is_in_hard_water_area"]:checked').val();

        if(is_in_hard_water_area == 'Yes'){
            $('#applianceModal .maufacturerWrap').fadeIn(function(){
                $('#applianceModal .maufacturerWrap input[type="radio"]').prop('checked', false);
            })
        }else{
            $('#applianceModal .maufacturerWrap').fadeOut(function(){
                $('#applianceModal .maufacturerWrap input[type="radio"]').prop('checked', false);
            })
        }
    })

    $('#applianceModal input[name="is_scale_reducer_fitted"]').on('change', function(){
        let is_scale_reducer_fitted = $('#applianceModal input[name="is_scale_reducer_fitted"]:checked').val();

        if(is_scale_reducer_fitted == 'Yes'){
            $('#applianceModal .reducerFittedWrap').fadeIn(function(){
                $('#applianceModal .reducerFittedWrap input').val('');
            })
        }else{
            $('#applianceModal .reducerFittedWrap').fadeOut(function(){
                $('#applianceModal .reducerFittedWrap input').val('');
            })
        }
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

        
        let $applianceMake = $theForm.find('.applianceMake')
        let applianceMakeName = $('option:selected', $applianceMake).text();

        let appliance_title = (applianceMakeName != '' ? applianceMakeName+' ' : '');
        
        formated_data['appliance_label'] = 'Appliance '+appliance_serial;
        formated_data['appliance_title'] = (appliance_title != '' ? appliance_title : '');
        
        localStorage.setItem('appliances', JSON.stringify(formated_data));
        $('.applianceBlock').find('.theDesc').html((appliance_title != '' ? appliance_title : 'N/A')).addClass('font-medium');

        
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


})();