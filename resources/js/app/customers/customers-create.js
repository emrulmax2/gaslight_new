import INTAddressLookUps from "../../address_lookup";

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        INTAddressLookUps();
    });

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

    $('#customerCreateForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('customerCreateForm');
        const $theForm = $(this);
        
        $('#customerCreateForm .acc__input-error').html('').removeClass('mt-1');
        $('#customerSaveBtn', $theForm).attr('disabled', 'disabled');
        $("#customerSaveBtn .theLoader").fadeIn();

        const urlParams = new URLSearchParams(window.location.search);
        const recordParam = urlParams.get('record');

        let form_data = new FormData(form);

        if(recordParam){
            form_data.append('record', recordParam);
        }
        axios({
            method: "post",
            url: route('customers.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#customerSaveBtn', $theForm).removeAttr('disabled');
            $("#customerSaveBtn .theLoader").fadeOut();

            if (response.status == 200) {
                let red = (response.data.red ? response.data.red : '');
                if(localStorage.record_url){
                    red = localStorage.getItem('record_url');
                    localStorage.setItem('customer', JSON.stringify(response.data.customer));
                    localStorage.removeItem('record_url');
                }

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', red);
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.href = red;
                }, 1500);
            }
        }).catch(error => {
            $('#customerSaveBtn', $theForm).removeAttr('disabled');
            $("#customerSaveBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#customerCreateForm .${key}`).addClass('border-danger');
                        $(`#customerCreateForm  .error-${key}`).html(val).addClass('mt-1');
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
})();


