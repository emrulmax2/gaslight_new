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
    let appliance_location_id = new TomSelect(document.getElementById('appliance_location_id'), tncTomOptions);
    let appliance_type_id = new TomSelect(document.getElementById('appliance_type_id'), tncTomOptions);
    let gas_warning_classification_id = new TomSelect(document.getElementById('gas_warning_classification_id'), tncTomOptions);

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const applianceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#applianceModal"));
    const otherCheckModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#otherCheckModal"));
    
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

    document.getElementById('applianceModal').addEventListener('hidden.tw.modal', function(event) {
        $('#applianceModal input:not([type="radio"]):not([type="checkbox"])').val('');
        $('#applianceModal textarea').val('');
        $('#applianceModal .otherDetailsWrap').fadeOut('fast', function(){
            $('#applianceModal textarea[name="other_issue_details"]').val('');
        })
        $('#applianceModal input[type="radio"]').prop('checked', false);
        $('#applianceModal input[type="checkbox"]').prop('checked', false);
        $('#applianceModal input[name="appliance_serial"]').val('1');
        $('#applianceModal input[name="edit"]').val('0');

        appliance_location_id.clear(true);
        appliance_type_id.clear(true);

        $('#applianceModal .classificationWraps').fadeOut('fast', function(){
            gas_warning_classification_id.clear(true);
        });
    });

    document.getElementById('otherCheckModal').addEventListener('hidden.tw.modal', function(event) {
        $('#otherCheckModal input[type="radio"]').prop('checked', false);
    });

    /* Appliance Auto Load Start */
    if(localStorage.appliances){
        let applianceCount = localStorage.getItem('applianceCount') * 1;
        let appliances = localStorage.getItem('appliances');
        let applianceObj = JSON.parse(appliances);
        
        if(Object.keys(applianceObj).length > 0){
            for (const [serial, appliance] of Object.entries(applianceObj)) {
                let applianceBLock = '';
                    applianceBLock += '<div class="px-2 py-3 applianceWrap_'+serial+' bg-white" style="margin-top: 2px">';
                        applianceBLock += '<a data-key="'+serial+'" href="javascript:void(0);" class="editApplianceBtn flex justify-between items-center cursor-pointer applianceBlock_'+serial+'">';
                            applianceBLock += '<div>';
                                applianceBLock += '<div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Appliance '+serial+'</div>';
                                applianceBLock += (appliance.appliance_title != '' ? '<div class="theDesc font-medium">'+appliance.appliance_title+'</div>' : '');
                            applianceBLock += '</div>';
                            applianceBLock += '<span style="flex: 0 0 16px; margin-left: 20px;"></span>';
                        applianceBLock += '</a>';
                    applianceBLock += '</div>';
                $('.allApplianceWrap').fadeIn('fast', function(){
                    $('.allApplianceWrap').append(applianceBLock);
                });
            }
            $('.applianceWrap').find('.theDesc').html(applianceCount);
        }else{
            $('.allApplianceWrap').fadeOut('fast', function(){
                $('.allApplianceWrap').html('');
            });
            $('.applianceWrap').find('.theDesc').html('0');
        }

        if(applianceCount == 4){
            $('.addApplianceBtn').fadeOut();
        }
    }

    $(document).on('click', '.editApplianceBtn', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);
        let theSerial = $theModalBtn.attr('data-key');

        let appliances = localStorage.getItem('appliances');
        let applianceObj = JSON.parse(appliances);
        let theAppliance = applianceObj[theSerial];

        applianceModal.show();
        document.getElementById("applianceModal").addEventListener("shown.tw.modal", function (event) {
            $('#applianceModal input[name="appliance_serial"]').val(theSerial);
            $('#applianceModal input[name="edit"]').val(1);
        
            let hasYes = 0;
            for (const [key, value] of Object.entries(theAppliance)) {
                if(key == 'appliance_location_id'){
                    appliance_location_id.addItem(value)
                }else if(key == 'appliance_type_id'){
                    appliance_type_id.addItem(value)
                }else{
                    let $theInput = $('#applianceModal [name="'+key+'"]');
                    if($theInput.is('textarea')){
                        $theInput.val(value ? value : '');
                    }else{
                        if($theInput.attr('type') == 'radio'){
                            hasYes += (value == 'Yes' ? 1 : 0);
                            $('#applianceModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                        }else{
                            if(key != 'appliance_serial' && key != 'edit'){
                                $theInput.val(value ? value : '');
                            }
                        }

                        if(key == 'other_issue'){
                            if(value == 'Yes'){
                                $('#applianceModal .otherDetailsWrap').fadeIn('fast', function(){
                                    $('#applianceModal textarea[name="other_issue_details"]').val(theAppliance.other_issue_details);
                                })
                            }else{
                                $('#applianceModal .otherDetailsWrap').fadeOut('fast', function(){
                                    $('#applianceModal textarea[name="other_issue_details"]').val('');
                                });
                            }
                        }
                    }
                }
            }

            if(hasYes > 0){
                $('#applianceModal .classificationWraps').fadeIn('fast', function(){
                    gas_warning_classification_id.addItem(theAppliance.gas_warning_classification_id);
                })
            }else{
                $('#applianceModal .classificationWraps').fadeOut('fast', function(){
                    gas_warning_classification_id.clear(true);
                });
            }
        });
    })

    $('.addApplianceBtn').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        //localStorage.removeItem('applianceCount');
        //localStorage.removeItem('appliances');
        let serial = 1;
        if(localStorage.appliances){
            let appliances = localStorage.getItem('appliances');
            let applianceObj = JSON.parse(appliances);
            let serials = Object.keys(applianceObj);
                serials.sort(function(a, b){return a - b})
            serial = (serials[serials.length - 1] * 1) + 1;
        }
        if(serial <= 4){
            localStorage.setItem('applianceCount', serial);
            
            applianceModal.show();
            document.getElementById("applianceModal").addEventListener("shown.tw.modal", function (event) {
                $('#applianceModal input[name="appliance_serial"]').val(serial);
                $('#applianceModal input[name="edit"]').val(0);
            });
        }
    });

    // Toggle N/A Button
    $(document).on('click', '.naToggleBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let thevalue = $theBtn.attr('data-value');

        $theBtn.siblings('input').val(thevalue);
        //formDataChanged = true;
    })
    
    $('#applianceModal input[name="other_issue"]').on('change', function(){
        let otherIssue = $('#applianceModal input[name="other_issue"]:checked').val();
        if(otherIssue == 'Yes'){
            $('#applianceModal .otherDetailsWrap').fadeIn('fast', function(){
                $('#applianceModal textarea[name="other_issue_details"]').val('');
            })
        }else{
            $('#applianceModal .otherDetailsWrap').fadeOut('fast', function(){
                $('#applianceModal textarea[name="other_issue_details"]').val('');
            });
        }
    })
    
    $('#applianceModal input[type="radio"]').on('change', function(){
        let hasYes = 0;
        $('#applianceModal input[type="radio"]:checked').each(function(){
            if($(this).val() == 'Yes'){
                hasYes += 1;
            }
        })
        if(hasYes > 0){
            $('#applianceModal .classificationWraps').fadeIn('fast', function(){
                gas_warning_classification_id.clear(true);
            })
        }else{
            $('#applianceModal .classificationWraps').fadeOut('fast', function(){
                gas_warning_classification_id.clear(true);
            });
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

        let $applianceType = $theForm.find('.applianceType');
        let applianceTypeName = ($('option:selected', $applianceType).val() !== '' ? $('option:selected', $applianceType).text() : '');
        
        let appliance_title = (applianceTypeName != '' ? applianceTypeName : '');
        formated_data['appliance_label'] = 'Appliance '+appliance_serial;
        formated_data['appliance_title'] = (appliance_title != '' ? appliance_title : '');
        
        if(edit == 1){
            let appliances = localStorage.getItem('appliances');
            let applianceObj = JSON.parse(appliances);
            applianceObj[appliance_serial] = formated_data;

            localStorage.setItem('appliances', JSON.stringify(applianceObj));

            let applianceBLock = '';
                applianceBLock += '<a data-key="'+appliance_serial+'" href="javascript:void(0);" class="editApplianceBtn flex justify-between items-center cursor-pointer applianceBlock_'+appliance_serial+'">';
                    applianceBLock += '<div>';
                        applianceBLock += '<div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Appliance '+appliance_serial+'</div>';
                        applianceBLock += (appliance_title != '' ? '<div class="theDesc font-medium">'+appliance_title+'</div>' : '');
                    applianceBLock += '</div>';
                    applianceBLock += '<span style="flex: 0 0 16px; margin-left: 20px;"></span>';
                applianceBLock += '</a>';
            $('.allApplianceWrap').find('.applianceWrap_'+appliance_serial).html(applianceBLock);
        }else{
            if(localStorage.appliances){
                let appliances = localStorage.getItem('appliances');
                let applianceObj = JSON.parse(appliances);
                applianceObj[appliance_serial] = formated_data

                localStorage.setItem('appliances', JSON.stringify(applianceObj));
                localStorage.setItem('applianceCount', appliance_serial);
            }else{
                let applianceObj = {};
                    applianceObj[appliance_serial] = formated_data;

                localStorage.setItem('appliances', JSON.stringify(applianceObj));
                localStorage.setItem('applianceCount', appliance_serial);
            }
            let applianceBLock = '';
                applianceBLock += '<div class="px-2 py-3 applianceWrap_'+appliance_serial+' bg-white" style="margin-top: 2px">';
                    applianceBLock += '<a data-key="'+appliance_serial+'" href="javascript:void(0);" class="editApplianceBtn flex justify-between items-center cursor-pointer applianceBlock_'+appliance_serial+'">';
                        applianceBLock += '<div>';
                            applianceBLock += '<div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Appliance '+appliance_serial+'</div>';
                            applianceBLock += (appliance_title != '' ? '<div class="theDesc font-medium">'+appliance_title+'</div>' : '');
                        applianceBLock += '</div>';
                        applianceBLock += '<span style="flex: 0 0 16px; margin-left: 20px;"></span>';
                    applianceBLock += '</a>';
                applianceBLock += '</div>';
            $('.allApplianceWrap').fadeIn('fast', function(){
                $('.allApplianceWrap').append(applianceBLock);
            });
            $('.applianceWrap').find('.theDesc').html(appliance_serial);
        }

        let applianceCount = localStorage.getItem('applianceCount');
        if(applianceCount == 4){
            $('.addApplianceBtn').fadeOut();
        }
        
        $('#saveApplianceBtn', $theForm).removeAttr('disabled');
        $("#saveApplianceBtn .theLoader").fadeOut();
        applianceModal.hide();
    });

    /* Auto Load Other Checks */
    if(localStorage.otherChecksAnswered){
        let questionAnswered = localStorage.getItem('otherChecksAnswered');
        $('.otherCheckBlock .theDesc').html((questionAnswered != '' ? questionAnswered : '0')+'/3');
    }

    $('.otherCheckBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        otherCheckModal.show();
        document.getElementById("otherCheckModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.otherChecks){
                let otherChecks = localStorage.getItem('otherChecks');
                let otherChecksObj = JSON.parse(otherChecks);

                if(!$.isEmptyObject(otherChecksObj)){
                    for (const [key, value] of Object.entries(otherChecksObj)) {
                        if(value != ''){
                            $('#otherCheckModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                        }else{
                            $('#otherCheckModal [name="'+key+'"]').prop('checked', false);
                        }
                    }
                }
            }
        });
    });

    $('#otherCheckForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('otherCheckForm');
        const $theForm = $(this);

        $('#saveotherBtn', $theForm).attr('disabled', 'disabled');
        $("#saveotherBtn .theLoader").fadeIn();

        let form_data = $theForm.serializeArray();
        let formated_data = getFormatedData(form_data);

        localStorage.removeItem('otherChecks');
        localStorage.setItem('otherChecks', JSON.stringify(formated_data));

        let questionAnswered = 0;
        if(!$.isEmptyObject(formated_data)){
            for (const [key, value] of Object.entries(formated_data)) {
                if(value != ''){
                    questionAnswered += 1;
                }
            }
        }
        localStorage.setItem('otherChecksAnswered', questionAnswered);
        $('.otherCheckBlock .theDesc').html(questionAnswered+'/3');

        $('#saveotherBtn', $theForm).removeAttr('disabled');
        $("#saveotherBtn .theLoader").fadeOut();
        otherCheckModal.hide();
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