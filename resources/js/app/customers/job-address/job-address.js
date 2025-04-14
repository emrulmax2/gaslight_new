("use strict");
import { route } from 'ziggy-js';
import INTAddressLookUps from '../../../address_lookup.js';
(function () { 

    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

var JobAddressListTable = (function () {
    var _tableGen = function () {
        
        let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let status = $("#status").val() != "" ? $("#status").val() : "";
        let customer_id = $("#customer_id").val() != "" ? $("#customer_id").val() : "";

        let tableContent = new Tabulator("#JobAddressListTable", {
            ajaxURL: route("customer.job-addresses.list", { customer_id: customer_id}),
            ajaxParams: { querystr: querystr, status: status },
            pagination: true,
            paginationMode:"remote",
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
                    minWidth: 80,
                    responsive: 0
                },
                {
                    title: 'Customer',
                    field: 'customer_full_name',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    minWidth: 200,
                    formatter(cell, formatterParams) {
                        const borderClass = 'border-b lg:border-b-0 border-slate-200 pb-2';
                        var html = `<div class="${borderClass}">`;
                        html += '<div class="flex items-start mb-1">';
                        html += '<span class="sm:hidden mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user w-4 h-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>';
                        html += '<div>';
                        html += '<div class="font-medium whitespace-normal">'+cell.getData().customer_full_name+'</div>';
                        html += (cell.getData().mobile != '' ? '<div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">'+cell.getData().mobile+'</div>' : '');
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        return html;
                    }
                },
                {
                    title: 'Address',
                    field: 'address',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    minWidth: 200,
                    formatter(cell, formatterParams) {
                        var html = '<div class="flex items-start mb-1">';
                        html += '<span class="sm:hidden mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin w-4 h-4"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg></span>';
                        html += '<div>';
                        html += '<div class="text-slate-500 text-xs whitespace-normal">'+cell.getData().address+'</div>';
                        html += '</div>';
                        html += '</div>';
                        return html;
                    }
                },
                // {
                //     title: "Actions",
                //     field: "id",
                //     headerSort: false,
                //     hozAlign: "center",
                //     headerHozAlign: "center",
                //     width: "120",
                //     download:false,
                //     vertAlign: 'middle',
                //     minWidth: 120,
                //     responsive: 0,
                //     visible: window.innerWidth >= 768,
                //     formatter(cell, formatterParams) {                        
                //         var btns = "";
                //         if (cell.getData().deleted_at == null) {
                //             btns += '<a href="' + route("customer.job-addresses.edit", { customer_id: cell.getData().customer_id, address_id: cell.getData().id }) + '" class="edit_btn rounded-full bg-primary text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="edit-2" class="w-4 h-4"></i></a>';
                //             btns += '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'" class="delete_btn rounded-full bg-danger text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="Trash2" class="w-4 h-4"></i></button>';
                //         }  else {
                //             btns += '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'"  class="restore_btn rounded-full bg-success text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="rotate-cw" class="w-4 h-4"></i></button>';
                //         }
                        
                //         return btns;
                //     },
                // },
            ],
            // ajaxResponse:function(url, params, response){
            //     return response.data;
            // },
            renderComplete() {
                createIcons({
                    icons,
                    attrs: { "stroke-width": 1.5 },
                    nameAttr: "data-lucide",
                });
            },

        });
        
        tableContent.on("rowClick", (e, row) => {
            window.location.href = route("customer.job-addresses.edit", { customer_id: row.getData().customer_id, address_id: row.getData().id });
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


        function updateColumnVisibility() {
            if (window.innerWidth < 768) {
                tableContent.getColumn("id").hide();
            } else {
                tableContent.getColumn("id").show();

            }
        }

        // Handle window resize
        window.addEventListener("resize", updateColumnVisibility);

        // Initial column visibility
        setTimeout(() => {
            updateColumnVisibility();
        }, 200);

   


    };
    return {
        init: function () {
            _tableGen();
        },
    };


    
})();


    if ($("#JobAddressListTable").length) {
        JobAddressListTable.init();

        function filterJobAddressListTable() {
            JobAddressListTable.init();
        }

        // On submit filter form
        $("#tabulator-html-filter-form").on("keypress", function (event) {
                let keycode = event.keyCode ? event.keyCode : event.which;
                if (keycode == "13") {
                    event.preventDefault();
                    filterJobAddressListTable();
                }
            }
        );

        // On click go button
        $("#tabulator-html-filter-go").on("click", function (event) {
            filterJobAddressListTable();
        });

        // On reset filter form
        $("#tabulator-html-filter-reset").on("click", function (event) {
            $("#query").val("");
            $("#status").val("1");
            filterJobAddressListTable();
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