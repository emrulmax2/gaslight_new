("use strict");
import { route } from 'ziggy-js';
import INTAddressLookUps from '../address_lookup.js';
(function () { 

    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

var customerPropertyListTable = (function () {
    var _tableGen = function () {
        
        let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let status = $("#status").val() != "" ? $("#status").val() : "";
        let customer_id = ($('#customerPropertyListTable').attr('data-customerid') ? $('#customerPropertyListTable').attr('data-customerid') : 0);

        let tableContent = new Tabulator("#customerPropertyListTable", {
            ajaxURL: route("customers.job-addresses.list", customer_id),
            ajaxParams: { querystr: querystr, status: status, customer_id : customer_id },
            paginationMode: "remote",
            filterMode: "remote",
            sortMode: "remote",
            printAsHtml: true,
            printStyled: true,
            paginationSize: 10,
            paginationSizeSelector: [true, 5, 10, 20, 30, 40],
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "No matching records found",
            columns: [
                {
                    title: "#ID",
                    field: "id",
                    vertAlign: 'middle',
                    width: "80",
                },
                {
                    title: 'Address',
                    field: 'address',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                },
                {
                    title: "Actions",
                    field: "id",
                    headerSort: false,
                    hozAlign: "center",
                    headerHozAlign: "center",
                    width: "120",
                    download:false,
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) {                        
                        var btns = "";
                        if (cell.getData().deleted_at == null) {
                            btns += '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'" data-tw-toggle="modal" data-tw-target="#editCustomerPropertyModal" class="edit_btn rounded-full bg-primary text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="edit-2" class="w-4 h-4"></i></button>';
                            btns += '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'" class="delete_btn rounded-full bg-danger text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="Trash2" class="w-4 h-4"></i></button>';
                        }  else {
                            btns += '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'"  class="restore_btn rounded-full bg-success text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="rotate-cw" class="w-4 h-4"></i></button>';
                        }
                        
                        return btns;
                    },
                },
            ],
            ajaxResponse:function(url, params, response){
                return response.data;
            },
            renderComplete() {
                createIcons({
                    icons,
                    attrs: { "stroke-width": 1.5 },
                    nameAttr: "data-lucide",
                });
            },
        });

        tableContent.on("renderComplete", () => {
            createIcons({
                icons,
                attrs: { "stroke-width": 1.5 },
                nameAttr: "data-lucide",
            });
        });

        // Redraw table onresize
        window.addEventListener("resize", () => {
            tableContent.redraw();
            createIcons({
                icons,
                "stroke-width": 1.5,
                nameAttr: "data-lucide",
            });
        });

        // Export
        $("#tabulator-export-csv").on("click", function (event) {
            tableContent.download("csv", "data.csv");
        });

        $("#tabulator-export-json").on("click", function (event) {
            tableContent.download("json", "data.json");
        });

        $("#tabulator-export-xlsx").on("click", function (event) {
            window.XLSX = xlsx;
            tableContent.download("xlsx", "data.xlsx", {
                sheetName: "Title Details",
            });
        });

        $("#tabulator-export-html").on("click", function (event) {
            tableContent.download("html", "data.html", {
                style: true,
            });
        });

        // Print
        $("#tabulator-print").on("click", function (event) {
            tableContent.print();
        });
    };
    return {
        init: function () {
            _tableGen();
        },
    };


    
})();


    if ($("#customerPropertyListTable").length) {
        customerPropertyListTable.init();

        function filtercustomerPropertyListTable() {
            customerPropertyListTable.init();
        }

        // On submit filter form
        $("#tabulator-html-filter-form").on("keypress", function (event) {
                let keycode = event.keyCode ? event.keyCode : event.which;
                if (keycode == "13") {
                    event.preventDefault();
                    filtercustomerPropertyListTable();
                }
            }
        );

        // On click go button
        $("#tabulator-html-filter-go").on("click", function (event) {
            filtercustomerPropertyListTable();
        });

        // On reset filter form
        $("#tabulator-html-filter-reset").on("click", function (event) {
            $("#query").val("");
            $("#status").val("1");
            filtercustomerPropertyListTable();
        });
    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addCustomerPropertyModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addCustomerPropertyModal"));
    const editCustomerPropertyModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#editCustomerPropertyModal"));
    
    
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

    
    document.getElementById('editCustomerPropertyModal').addEventListener('hide.tw.modal', function(event) {
        $('#editCustomerPropertyModal input:not([name="customer_id"])').val('');
        $('#editCustomerPropertyModal textarea').val('');
    });
    document.getElementById('addCustomerPropertyModal').addEventListener('hide.tw.modal', function(event) {
        $('#addCustomerPropertyModal input:not([name="customer_id"])').val('');
        $('#addCustomerPropertyModal textarea').val('');
    });

    // Delete Trigger
    $('#customerPropertyListTable').on('click', '.delete_btn', function(){
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
    $('#customerPropertyListTable').on('click', '.restore_btn', function(){
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

    //Store Form
    $('#propertyCreateForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('propertyCreateForm');
        const $theForm = $(this);
        let customer_id = $theForm.find('[name="customer_id"]').val()
        
        $('#propertySaveBtn', $theForm).attr('disabled', 'disabled');
        $("#propertySaveBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('customers.job-addresses.store', customer_id),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#propertySaveBtn', $theForm).removeAttr('disabled');
            $("#propertySaveBtn .theLoader").fadeOut();

            if (response.status == 200) {
                addCustomerPropertyModal.hide()
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });
                setTimeout(() => {
                    successModal.hide();
                    window.location.href = response.data.red;
                }, 1500);
            }
        }).catch(error => {
            $('#propertySaveBtn', $theForm).removeAttr('disabled');
            $("#propertySaveBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#propertyCreateForm .${key}`).addClass('border-danger');
                        $(`#propertyCreateForm  .error-${key}`).html(val);
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

    //Property Edit
    $('#customerPropertyListTable').on('click', '.edit_btn', function(e) {
        let property_id = $(this).data("id"); 
        
        let $theBtn = $(this);
        let customer_id = ($('#editCustomerPropertyModal [name="customer_id"]').val() > 0 ? $('#editCustomerPropertyModal [name="customer_id"]').val() : 0);
        
        $theBtn.attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();
    
        axios({
            method: "get",
            url: route('customers.job-addresses.edit', { customer: customer_id, property_id: property_id }), 
            params: { customer_id: customer_id }, 
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        }).then(response => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();
    
            if (response.status == 200) {
                let row = response.data.row;
                editCustomerPropertyModal.show();
                document.getElementById('editCustomerPropertyModal').addEventListener('shown.tw.modal', function(event){
                    $('#editCustomerPropertyModal input[name="property_id"]').val(row.id ? row.id : '');
                    $('#editCustomerPropertyModal input[name="address_line_1"]').val(row.address_line_1 || '');
                    $('#editCustomerPropertyModal input[name="address_line_2"]').val(row.address_line_2 || '');
                    $('#editCustomerPropertyModal input[name="city"]').val(row.city || '');
                    $('#editCustomerPropertyModal input[name="state"]').val(row.state || '');
                    $('#editCustomerPropertyModal input[name="postal_code"]').val(row.postal_code || '');
                    $('#editCustomerPropertyModal input[name="country"]').val(row.country || '');
                    $('#editCustomerPropertyModal input[name="latitude"]').val(row.latitude || '');
                    $('#editCustomerPropertyModal input[name="longitude"]').val(row.longitude || '');
                    $('#editCustomerPropertyModal input[name="occupant_name"]').val(row.occupant_name || '');
                    $('#editCustomerPropertyModal input[name="occupant_email"]').val(row.occupant_email || '');
                    $('#editCustomerPropertyModal input[name="occupant_phone"]').val(row.occupant_phone || '');
                    $('#editCustomerPropertyModal input[name="due_date"]').val(row.due_date || '');
                    $('#editCustomerPropertyModal textarea[name="note"]').val(row.note || '');
                })
            }
        }).catch(error => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();
            console.error("Error:", error);
        });
    });

    //Update Form
    $('#updatePropertyForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updatePropertyForm');
        const $theForm = $(this);
        let customer_id = $theForm.find('[name="customer_id"]').val()
        let property_id = $theForm.find('[name="property_id"]').val()
        
        $('#updatePropertyBtn', $theForm).attr('disabled', 'disabled');
        $("#updatePropertyBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('customers.job-addresses.update', { customer: customer_id, property_id: property_id }),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#updatePropertyBtn', $theForm).removeAttr('disabled');
            $("#updatePropertyBtn .theLoader").fadeOut();

            if (response.status == 200) {
                editCustomerPropertyModal.hide();
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });
                setTimeout(() => {
                    successModal.hide();
                    window.location.href = response.data.red;
                }, 1500);
            }
        }).catch(error => {
            $('#updatePropertyBtn', $theForm).removeAttr('disabled');
            $("#updatePropertyBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#updatePropertyForm .${key}`).addClass('border-danger');
                        $(`#updatePropertyForm  .error-${key}`).html(val);
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
    
    // Confirm Modal Action Delete
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETEProperty'){
            let customer_id = ($('#customerPropertyListTable').attr('data-customerid') ? $('#customerPropertyListTable').attr('data-customerid') : 0);
            axios({
                method: 'delete',
                url: route('customers.job-addresses.destroy', [customer_id, row_id]),
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
                customerPropertyListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RESTOREProperty'){
            let customer_id = ($('#customerPropertyListTable').attr('data-customerid') ? $('#customerPropertyListTable').attr('data-customerid') : 0);
            axios({
                method: 'post',
                url: route('customers.job-addresses.restore', [customer_id, row_id]),
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
                customerPropertyListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }
    })
  
    //Copy Address
    $('#addCustomerPropertyModal').on('click', '.coptyCustomerAddress', function(e){
        let $theBtn = $(this);
        let customer_id = ($('#addCustomerPropertyModal [name="customer_id"]').val() > 0 ? $('#addCustomerPropertyModal [name="customer_id"]').val() : 0);
        
        $theBtn.attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();

        axios({
            method: "post",
            url: route('getCustomer.address'),
            data: {customer_id : customer_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();

            if (response.status == 200) {
                let row = response.data.row;
                $('#addCustomerPropertyModal input[name="address_line_1"]').val(row.address_line_1 ? row.address_line_1 : '');
                $('#addCustomerPropertyModal input[name="address_line_2"]').val(row.address_line_2 ? row.address_line_2 : '');
                $('#addCustomerPropertyModal input[name="city"]').val(row.city ? row.city : '');
                $('#addCustomerPropertyModal input[name="state"]').val(row.state ? row.state : '');
                $('#addCustomerPropertyModal input[name="postal_code"]').val(row.postal_code ? row.postal_code : '');
                $('#addCustomerPropertyModal input[name="country"]').val(row.country ? row.country : '');
                $('#addCustomerPropertyModal input[name="latitude"]').val(row.latitude ? row.latitude : '');
                $('#addCustomerPropertyModal input[name="longitude"]').val(row.longitude ? row.longitude : '');
            }
        }).catch(error => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();
            if (error.response) {
                console.log('error');
            }
        });
    });

    //Copy Address Edit
    $('#editCustomerPropertyModal').on('click', '.coptyCustomerAddress', function(e){
        let $theBtn = $(this);
        let customer_id = ($('#editCustomerPropertyModal [name="customer_id"]').val() > 0 ? $('#editCustomerPropertyModal [name="customer_id"]').val() : 0);
        
        $theBtn.attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();

        axios({
            method: "post",
            url: route('getCustomer.address'),
            data: {customer_id : customer_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();

            if (response.status == 200) {
                let row = response.data.row;
                $('#editCustomerPropertyModal input[name="address_line_1"]').val(row.address_line_1 ? row.address_line_1 : '');
                $('#editCustomerPropertyModal input[name="address_line_2"]').val(row.address_line_2 ? row.address_line_2 : '');
                $('#editCustomerPropertyModal input[name="city"]').val(row.city ? row.city : '');
                $('#editCustomerPropertyModal input[name="state"]').val(row.state ? row.state : '');
                $('#editCustomerPropertyModal input[name="postal_code"]').val(row.postal_code ? row.postal_code : '');
                $('#editCustomerPropertyModal input[name="country"]').val(row.country ? row.country : '');
                $('#editCustomerPropertyModal input[name="latitude"]').val(row.latitude ? row.latitude : '');
                $('#editCustomerPropertyModal input[name="longitude"]').val(row.longitude ? row.longitude : '');
            }
        }).catch(error => {
            $theBtn.removeAttr('disabled');
            $theBtn.find('.theLoader').fadeOut();
            if (error.response) {
                console.log('error');
            }
        });
    });

})();