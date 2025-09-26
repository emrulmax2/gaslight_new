import INTAddressLookUps from '../../../address_lookup.js';

(function(){
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));

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

    $('.settingsMenu ul li.hasChild > a').on('click', function(e){
        e.preventDefault();
        
        $(this).toggleClass('active text-primary font-medium');
        $(this).siblings('ul').slideToggle();
    });

    $('#companySettingsForm').on('change', '#siteFaviconUpload', function(){
        showPreview('siteFaviconUpload', 'siteFaviconImg')
    });

    $('#companySettingsForm').on('change', '#siteLogoUpload', function(){
        showPreview('siteLogoUpload', 'siteLogoImg')
    });

    function showPreview(inputId, targetImageId) {
        var src = document.getElementById(inputId);
        var target = document.getElementById(targetImageId);
        var title = document.getElementById('selected_image_title');
        var fr = new FileReader();
        fr.onload = function () {
            target.src = fr.result;
        }
        fr.readAsDataURL(src.files[0]);
    };
    
    $('#companySettingsForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('companySettingsForm');
    
        document.querySelector('#updateCINF').setAttribute('disabled', 'disabled');
        document.querySelector("#updateCINF .theLoader").style.cssText ="display: inline-block;";

        let form_data = new FormData(form);
        if($('#companySettingsForm input[name="site_logo"]').length > 0){
            form_data.append('file', $('#companySettingsForm input[name="site_logo"]')[0].files[0]); 
        }
        if($('#companySettingsForm input[name="site_logo"]').length > 0){
            form_data.append('file', $('#companySettingsForm input[name="site_favicon"]')[0].files[0]); 
        }
        axios({
            method: "post",
            url: route('superadmin.site.setting.update'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            document.querySelector('#updateCINF').removeAttribute('disabled');
            document.querySelector("#updateCINF .theLoader").style.cssText = "display: none;";
            console.log(response.data.msg);
            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html( "Congratulations!" );
                    $("#successModal .successModalDesc").html('Active settings data successfully updated.');
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });     

                setTimeout(() => {
                    successModal.hide();
                    window.location.href = response.data.red;
                }, 1500);
            }
        }).catch(error => {
            document.querySelector('#updateCINF').removeAttribute('disabled');
            document.querySelector("#updateCINF .theLoader").style.cssText = "display: none;";
            if (error.response) {
                console.log('error');
            }
        });
    });


})();