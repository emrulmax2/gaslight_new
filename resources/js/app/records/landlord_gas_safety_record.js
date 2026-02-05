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
    let appliance_location_id = new TomSelect(document.getElementById('appliance_location_id'), tncTomOptions);
    let boiler_brand_id = new TomSelect(document.getElementById('boiler_brand_id'), tncTomOptions);
    let appliance_type_id = new TomSelect(document.getElementById('appliance_type_id'), tncTomOptions);
    let appliance_flue_type_id = new TomSelect(document.getElementById('appliance_flue_type_id'), tncTomOptions);

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const applianceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#applianceModal"));
    const safetyCheckModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#safetyCheckModal"));
    const commentsModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#commentsModal"));
    
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
        $('#applianceModal input[name="appliance_serial"]').val('0');
        $('#applianceModal input[name="edit"]').val('0');

        appliance_location_id.clear(true);
        boiler_brand_id.clear(true);
        appliance_type_id.clear(true);
        appliance_flue_type_id.clear(true);
    });

    document.getElementById('safetyCheckModal').addEventListener('hide.tw.modal', function(event) {
        $('#safetyCheckModal input[type="radio"]').prop('checked', false);
    });

    document.getElementById('commentsModal').addEventListener('hide.tw.modal', function(event) {
        $('#commentsModal textarea').val('');
        $('#commentsModal input[type="radio"]').prop('checked', false);
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
    /* Appliance Auto Load End */
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
            $('#applianceModal input[name="edit"]').val(theSerial);
        
            for (const [key, value] of Object.entries(theAppliance)) {
                if(key == 'appliance_location_id'){
                    appliance_location_id.addItem(value)
                }else if(key == 'boiler_brand_id'){
                    boiler_brand_id.addItem(value)
                }else if(key == 'appliance_type_id'){
                    appliance_type_id.addItem(value)
                }else if(key == 'appliance_flue_type_id'){
                    appliance_flue_type_id.addItem(value)
                }else{
                    let $theInput = $('#applianceModal [name="'+key+'"]');
                    if($theInput.is('textarea')){
                        $theInput.val(value ? value : '');
                    }else{
                        if($theInput.attr('type') == 'radio'){
                            $('#applianceModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                        }else{
                            if(key != 'appliance_serial' && key != 'edit'){
                                $theInput.val(value ? value : '');
                            }
                        }
                    }
                }
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
        let applianceTypeName = $('option:selected', $applianceType).text();
        let $applianceMake = $theForm.find('.applianceMake')
        let applianceMakeName = $('option:selected', $applianceMake).text();

        let appliance_title = (applianceMakeName != '' ? applianceMakeName+' ' : '');
            appliance_title += (applianceTypeName != '' ? applianceTypeName : '');
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

    /* Auto Load Safety Checks */
    if(localStorage.safetyChecksAnswered){
        let questionAnswered = localStorage.getItem('safetyChecksAnswered');
        $('.safetyCheckBlock .theDesc').html((questionAnswered != '' ? questionAnswered : '0')+'/8');
    }

    $('.safetyCheckBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        safetyCheckModal.show();
        document.getElementById("safetyCheckModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.safetyChecks){
                let safetyChecks = localStorage.getItem('safetyChecks');
                let safetyChecksObj = JSON.parse(safetyChecks);

                if(!$.isEmptyObject(safetyChecksObj)){
                    for (const [key, value] of Object.entries(safetyChecksObj)) {
                        if(value != ''){
                            $('#safetyCheckModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                        }else{
                            $('#safetyCheckModal [name="'+key+'"]').prop('checked', false);
                        }
                    }
                }
            }
        });
    });

    $('#safetyCheckForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('safetyCheckForm');
        const $theForm = $(this);

        $('#saveSafetyBtn', $theForm).attr('disabled', 'disabled');
        $("#saveSafetyBtn .theLoader").fadeIn();

        let form_data = $theForm.serializeArray();
        let formated_data = getFormatedData(form_data);

        localStorage.removeItem('safetyChecks');
        localStorage.setItem('safetyChecks', JSON.stringify(formated_data));

        let questionAnswered = 0;
        if(!$.isEmptyObject(formated_data)){
            for (const [key, value] of Object.entries(formated_data)) {
                if(value != ''){
                    questionAnswered += 1;
                }
            }
        }
        localStorage.setItem('safetyChecksAnswered', questionAnswered);
        $('.safetyCheckBlock .theDesc').html(questionAnswered+'/8');

        $('#saveSafetyBtn', $theForm).removeAttr('disabled');
        $("#saveSafetyBtn .theLoader").fadeOut();
        safetyCheckModal.hide();
    });

    /* Comments Auto Load */
    if(localStorage.commentssAnswered){
        let questionAnswered = localStorage.getItem('commentssAnswered');
        $('.commentsBlock .theDesc').html((questionAnswered != '' ? questionAnswered : '0')+'/4');
    }
    $('.commentsBlock').on('click', function(e){
        e.preventDefault();
        let $theModalBtn = $(this);

        commentsModal.show();
        document.getElementById("commentsModal").addEventListener("shown.tw.modal", function (event) {
            if(localStorage.gsrComments){
                let gsrComments = localStorage.getItem('gsrComments');
                let gsrCommentsObj = JSON.parse(gsrComments);

                if(!$.isEmptyObject(gsrCommentsObj)){
                    for (const [key, value] of Object.entries(gsrCommentsObj)) {
                        let $theInput = $('#commentsModal [name="'+key+'"]');
                        if($theInput.is('textarea')){
                            $theInput.val(value ? value : '');
                        }else{
                            if($theInput.attr('type') == 'radio'){
                                $('#commentsModal [name="'+key+'"][value="'+value+'"]').prop('checked', true);
                            }else{
                                $theInput.val(value ? value : '');
                            }
                        }
                    }
                }
            }
        });
    });

    $('#commentsForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('commentsForm');
        const $theForm = $(this);

        $('#saveCommentsBtn', $theForm).attr('disabled', 'disabled');
        $("#saveCommentsBtn .theLoader").fadeIn();

        let form_data = $theForm.serializeArray();
        let formated_data = getFormatedData(form_data);

        localStorage.removeItem('gsrComments');
        localStorage.setItem('gsrComments', JSON.stringify(formated_data));

        let questionAnswered = 0;
        if(!$.isEmptyObject(formated_data)){
            for (const [key, value] of Object.entries(formated_data)) {
                if(value != ''){
                    questionAnswered += 1;
                }
            }
        }
        localStorage.setItem('commentssAnswered', questionAnswered);
        $('.commentsBlock .theDesc').html(questionAnswered+'/4');

        $('#saveCommentsBtn', $theForm).removeAttr('disabled');
        $("#saveCommentsBtn .theLoader").fadeOut();
        commentsModal.hide();
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