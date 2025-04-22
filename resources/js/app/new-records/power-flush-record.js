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
    let powerflush_system_type_id = new TomSelect(document.getElementById('powerflush_system_type_id'), tncTomOptions);
    let boiler_brand_id = new TomSelect(document.getElementById('boiler_brand_id'), tncTomOptions);
    let appliance_type_id = new TomSelect(document.getElementById('appliance_type_id'), tncTomOptions);
    let appliance_location_id = new TomSelect(document.getElementById('appliance_location_id'), tncTomOptions);
    let powerflush_cylinder_type_id = new TomSelect(document.getElementById('powerflush_cylinder_type_id'), tncTomOptions);
    let powerflush_pipework_type_id = new TomSelect(document.getElementById('powerflush_pipework_type_id'), tncTomOptions);
    let powerflush_circulator_pump_location_id = new TomSelect(document.getElementById('powerflush_circulator_pump_location_id'), tncTomOptions);
    let radiator_type_id = new TomSelect(document.getElementById('radiator_type_id'), tncTomOptions);
    let color_id = new TomSelect(document.getElementById('color_id'), tncTomOptions);
    let before_color_id = new TomSelect(document.getElementById('before_color_id'), tncTomOptions);

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const powerflushChecklistModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#powerflushChecklistModal"));
    const radiatorModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#radiatorModal"));
    
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

    document.getElementById('powerflushChecklistModal').addEventListener('hide.tw.modal', function(event) {
        $('#powerflushChecklistModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#powerflushChecklistModal textarea').val('');
        $('#powerflushChecklistModal input[type="radio"]').prop('checked', false);
        $('#powerflushChecklistModal input[type="checkbox"]').prop('checked', false);
        $('#powerflushChecklistModal input[name="radiator_serial"]').val('1');

        powerflush_system_type_id.clear(true);
        boiler_brand_id.clear(true);
        appliance_type_id.clear(true);
        appliance_location_id.clear(true);
        powerflush_cylinder_type_id.clear(true);
        powerflush_pipework_type_id.clear(true);
        powerflush_circulator_pump_location_id.clear(true);
        radiator_type_id.clear(true);
        color_id.clear(true);
        before_color_id.clear(true);
    });

    document.getElementById('radiatorModal').addEventListener('hide.tw.modal', function(event) {
        $('#radiatorModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#radiatorModal textarea').val('');
        $('#radiatorModal input[type="radio"]').prop('checked', false);
        $('#radiatorModal input[type="checkbox"]').prop('checked', false);
        $('#radiatorModal input[name="radiator_serial"]').val('1');
        $('#radiatorModal input[name="edit"]').val('0');
    });

    // Toggle N/A Button
    $(document).on('click', '.naToggleBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let thevalue = $theBtn.attr('data-value');

        $theBtn.siblings('input').val(thevalue);
        //formDataChanged = true;
    })

    /* Poserflush Checklist Auto Load Start */
    if(localStorage.checklistAnswered){
        let questionAnswered = localStorage.getItem('checklistAnswered');
        $('.pwChecklistBlock .theDesc').html((questionAnswered != '' ? questionAnswered : '0')+'/45').addClass('font-medium');
    }
    $('.pwChecklistBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let powerFlushChecklist = localStorage.getItem('powerFlushChecklist');

        powerflushChecklistModal.show();
        document.getElementById("powerflushChecklistModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.powerFlushChecklist){
                let powerFlushChecklistObj = JSON.parse(powerFlushChecklist);
                if(!$.isEmptyObject(powerFlushChecklistObj)){
                    for (const [key, value] of Object.entries(powerFlushChecklistObj)) {
                        if(key == 'powerflush_system_type_id'){
                            powerflush_system_type_id.addItem(value)
                        }else if(key == 'boiler_brand_id'){
                            boiler_brand_id.addItem(value)
                        }else if(key == 'appliance_type_id'){
                            appliance_type_id.addItem(value)
                        }else if(key == 'appliance_location_id'){
                            appliance_location_id.addItem(value)
                        }else if(key == 'powerflush_cylinder_type_id'){
                            powerflush_cylinder_type_id.addItem(value)
                        }else if(key == 'powerflush_pipework_type_id'){
                            powerflush_pipework_type_id.addItem(value)
                        }else if(key == 'powerflush_circulator_pump_location_id'){
                            powerflush_circulator_pump_location_id.addItem(value)
                        }else if(key == 'radiator_type_id'){
                            radiator_type_id.addItem(value)
                        }else if(key == 'color_id'){
                            color_id.addItem(value)
                        }else if(key == 'before_color_id'){
                            before_color_id.addItem(value)
                        }else{
                            let $theInput = $('#powerflushChecklistModal [name="'+key+'"]');
                            if($theInput.is('textarea')){
                                $theInput.val(value ? value : '');
                            }else{
                                if($theInput.attr('type') == 'radio'){
                                    $('#powerflushChecklistModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                                }else{
                                    if(key != 'radiator_serial'){
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
    $('#powerflushChecklistForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('powerflushChecklistForm');
        const $theForm = $(this);

        $('#saveChecklistBtn', $theForm).attr('disabled', 'disabled');
        $("#saveChecklistBtn .theLoader").fadeIn();

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
        localStorage.setItem('checklistAnswered', questionAnswered);
        $('.pwChecklistBlock .theDesc').html(questionAnswered+'/45');

        localStorage.setItem('powerFlushChecklist', JSON.stringify(formated_data));

        
        $('#saveChecklistBtn', $theForm).removeAttr('disabled');
        $("#saveChecklistBtn .theLoader").fadeOut();
        powerflushChecklistModal.hide();
    });
    /* Poserflush Checklist Auto Load End */

    /* Radiators Auto Load Start */
    if(localStorage.radiators){
        let radiatorCount = localStorage.getItem('radiatorCount') * 1;
        let radiators = localStorage.getItem('radiators');
        let radiatorsObj = JSON.parse(radiators);
        
        if(Object.keys(radiatorsObj).length > 0){
            for (const [serial, appliance] of Object.entries(radiatorsObj)) {
                let radiatorBLock = '';
                    radiatorBLock += '<div class="px-2 py-3 radiatorWrap_'+serial+' bg-white" style="margin-top: 2px">';
                        radiatorBLock += '<a data-key="'+serial+'" href="javascript:void(0);" class="editRadiatorBtn flex justify-between items-center cursor-pointer radiatorBlock_'+serial+'">';
                            radiatorBLock += '<div>';
                                radiatorBLock += '<div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Appliance '+serial+'</div>';
                                radiatorBLock += '<div class="theDesc font-medium">('+serial+') Rediator '+(appliance.radiator_title != '' ? ' ('+appliance.radiator_title+')' : '')+'</div>';
                            radiatorBLock += '</div>';
                            radiatorBLock += '<span style="flex: 0 0 16px; margin-left: 20px;"></span>';
                        radiatorBLock += '</a>';
                    radiatorBLock += '</div>';
                $('.allRadiatorsWrap').fadeIn('fast', function(){
                    $('.allRadiatorsWrap').append(radiatorBLock);
                });
            }
            $('.radiatorWrap').find('.theDesc').html(radiatorCount);
        }else{
            $('.allRadiatorsWrap').fadeOut('fast', function(){
                $('.allRadiatorsWrap').html('');
            });
            $('.radiatorWrap').find('.theDesc').html('0');
        }

        // if(radiatorCount == 4){
        //     $('.addApplianceBtn').fadeOut();
        // }
    }

    $(document).on('click', '.editRadiatorBtn', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);
        let theSerial = $theModalBtn.attr('data-key');

        let radiators = localStorage.getItem('radiators');
        let radiatorsObj = JSON.parse(radiators);
        let theRadiator = radiatorsObj[theSerial];
        
        radiatorModal.show();
        document.getElementById("radiatorModal").addEventListener("shown.tw.modal", function (event) {
            $('#radiatorModal input[name="radiator_serial"]').val(theSerial);
            $('#radiatorModal input[name="edit"]').val(theSerial);
        
            for (const [key, value] of Object.entries(theRadiator)) {
                let $theInput = $('#radiatorModal [name="'+key+'"]');
                if($theInput.is('textarea')){
                    $theInput.val(value ? value : '');
                }else{
                    if($theInput.attr('type') == 'radio'){
                        $('#radiatorModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                    }else{
                        if(key != 'radiator_serial' && key != 'edit'){
                            $theInput.val(value ? value : '');
                        }
                    }
                }
            }
        });
    });

    $('.addRadiatorBtn').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        let serial = 1;
        if(localStorage.radiators){
            let radiators = localStorage.getItem('radiators');
            let radiatorsObj = JSON.parse(radiators);
            let serials = Object.keys(radiatorsObj);
                serials.sort(function(a, b){return a - b})
            serial = (serials[serials.length - 1] * 1) + 1;
        }
        //if(serial <= 4){
        localStorage.setItem('radiatorCount', serial);
        
        radiatorModal.show();
        document.getElementById("radiatorModal").addEventListener("shown.tw.modal", function (event) {
            $('#radiatorModal input[name="radiator_serial"]').val(serial);
            $('#radiatorModal input[name="edit"]').val(0);
        });
        //}
    });

    $('#radiatorForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('radiatorForm');
        const $theForm = $(this);

        $('#saveRadiatorBtn', $theForm).attr('disabled', 'disabled');
        $("#saveRadiatorBtn .theLoader").fadeIn();

        
        let radiator_serial = $theForm.find('[name="radiator_serial"]').val() * 1;
        let edit = $theForm.find('[name="edit"]').val();

        let form_data = $theForm.serializeArray();
        let formated_data = getFormatedData(form_data);

        let $radiatorLocationName = $theForm.find('.reaiator_location_name');
        let radiatorLoacationName = $radiatorLocationName.val();

        formated_data['radiator_label'] = '('+radiator_serial+') Radiator ';
        formated_data['radiator_title'] = (radiatorLoacationName != '' ? radiatorLoacationName : '');
        
        if(edit == 1){
            let radiators = localStorage.getItem('radiators');
            let radiatorsObj = JSON.parse(radiators);
            radiatorsObj[radiator_serial] = formated_data;

            localStorage.setItem('radiators', JSON.stringify(radiatorsObj));

            let radiatorBLock = '';
                radiatorBLock += '<a data-key="'+radiator_serial+'" href="javascript:void(0);" class="editRadiatorBtn flex justify-between items-center cursor-pointer radiatorBlock_'+radiator_serial+'">';
                    radiatorBLock += '<div>';
                        radiatorBLock += '<div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Appliance '+radiator_serial+'</div>';
                        radiatorBLock += '<div class="theDesc font-medium">('+radiator_serial+') Rediator '+(radiatorLoacationName != '' ? ' ('+radiatorLoacationName+')' : '')+'</div>';
                    radiatorBLock += '</div>';
                    radiatorBLock += '<span style="flex: 0 0 16px; margin-left: 20px;"></span>';
                radiatorBLock += '</a>';
            $('.allRadiatorsWrap').find('.applianceWrap_'+radiator_serial).html(radiatorBLock);
        }else{
            if(localStorage.radiators){
                let radiators = localStorage.getItem('radiators');
                let radiatorsObj = JSON.parse(radiators);
                radiatorsObj[radiator_serial] = formated_data

                localStorage.setItem('radiators', JSON.stringify(radiatorsObj));
                localStorage.setItem('radiatorCount', radiator_serial);
            }else{
                let radiatorsObj = {};
                    radiatorsObj[radiator_serial] = formated_data;

                localStorage.setItem('radiators', JSON.stringify(radiatorsObj));
                localStorage.setItem('radiatorCount', radiator_serial);
            }

            let radiatorBLock = '';
                radiatorBLock += '<div class="px-2 py-3 radiatorWrap_'+radiator_serial+' bg-white" style="margin-top: 2px">';
                    radiatorBLock += '<a data-key="'+radiator_serial+'" href="javascript:void(0);" class="editRadiatorBtn flex justify-between items-center cursor-pointer radiatorBlock_'+radiator_serial+'">';
                        radiatorBLock += '<div>';
                            radiatorBLock += '<div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Appliance '+radiator_serial+'</div>';
                            radiatorBLock += '<div class="theDesc font-medium">('+radiator_serial+') Rediator '+(radiatorLoacationName != '' ? ' ('+radiatorLoacationName+')' : '')+'</div>';
                        radiatorBLock += '</div>';
                        radiatorBLock += '<span style="flex: 0 0 16px; margin-left: 20px;"></span>';
                    radiatorBLock += '</a>';
                radiatorBLock += '</div>';
            $('.allRadiatorsWrap').fadeIn('fast', function(){
                $('.allRadiatorsWrap').append(radiatorBLock);
            });
            $('.radiatorWrap').find('.theDesc').html(radiator_serial);
        }

        let radiatorCount = localStorage.getItem('radiatorCount');
        // if(radiatorCount == 4){
        //     $('.addApplianceBtn').fadeOut();
        // }
        
        $('#saveRadiatorBtn', $theForm).removeAttr('disabled');
        $("#saveRadiatorBtn .theLoader").fadeOut();
        radiatorModal.hide();
    });
    /* Radiators Auto Load End */


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
        if(localStorage.radiators){
            let radiators = localStorage.getItem('radiators');
            formData.append('radiators', radiators);
        }else{
            errors['radiator_error'] = 'Please add at least one Radiator.&nbsp;';
        }
        if(localStorage.powerFlushChecklist){
            let powerFlushChecklist = localStorage.getItem('powerFlushChecklist');
            formData.append('powerFlushChecklist', powerFlushChecklist);
        }else{
            errors['checklist_error'] = 'Please fill out powerflush checklist data.&nbsp;';
        }

        if($.isEmptyObject(errors)){
            axios({
                method: "post",
                url: route('records.gas.power.flush.store.new'),
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

})();