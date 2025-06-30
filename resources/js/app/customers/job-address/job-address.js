import { route } from 'ziggy-js';
import INTAddressLookUps from '../../../address_lookup.js';

("use strict");
var JobAddressListTable = (function () {
    var _tableGen = function () {

        let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let customer_id = $("#customer_id").val() != "" ? $("#customer_id").val() : "";

        axios({
            method: 'get',
            url: route('customer.job-addresses.list', {customer_id: customer_id, querystr: querystr}),
            data: {customer_id: customer_id, querystr: querystr},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                $('#JobAddressListTable').html(response.data.html);

                createIcons({
                    icons,
                    attrs: { "stroke-width": 1.5 },
                    nameAttr: "data-lucide",
                });
            }
        }).catch(error =>{
            console.log(error)
        });
        

    };
    return {
        init: function () {
            _tableGen();
        },
    };

})();
    
(function(){
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

    if ($("#JobAddressListTable").length) {
        JobAddressListTable.init();

        function filterJobAddressListTable() {
            JobAddressListTable.init();
        }

        $("#query").on("keypress", function (e) {
            var key = e.keyCode || e.which;
            if(key === 13){
                e.preventDefault();
    
                JobAddressListTable.init();
            }
        });

    }


    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));

    
    
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

    


    // Delete Trigger
    $('#JobAddressListTable').on('click', '.delete_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete these record? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETEProperty');
        });
    });

    // Restore Trigger
    $('#JobAddressListTable').on('click', '.restore_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to restore these record? Click on agree to continue.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'RESTOREProperty');
        });
    });

    

   
    
    // Confirm Modal Action Delete
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');


        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETEProperty'){
            axios({
                method: 'delete',
                url: route('customer.job-addresses.job_address_destroy', [row_id]),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById('successModal').addEventListener('shown.tw.modal', function(event){
                        $('#successModal .successModalTitle').html('WOW!');
                        $('#successModal .successModalDesc').html(response.data.msg);
                    });
                }
                JobAddressListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RESTOREProperty'){
            axios({
                method: 'post',
                url: route('customer.job-addresses.job_address_restore', [row_id]),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById('successModal').addEventListener('shown.tw.modal', function(event){
                        $('#successModal .successModalTitle').html('WOW!');
                        $('#successModal .successModalDesc').html(response.data.msg);
                    });
                }
                JobAddressListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }
    })

})();