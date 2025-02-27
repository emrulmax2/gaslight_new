("use strict");
var customerJobListTable = (function () {
    var _tableGen = function () {
        
        let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let status = $("#status").val() != "" ? $("#status").val() : "";
        let customer_id = ($('#customerJobListTable').attr('data-customerid') ? $('#customerJobListTable').attr('data-customerid') : 0);

        let tableContent = new Tabulator("#customerJobListTable", {
            ajaxURL: route("customers.jobs.list", customer_id),
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
                    title: 'Ref No',
                    field: 'reference_no',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    width: "180",
                },
                {
                    title: "Address",
                    field: "address_line_1",
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) { 
                        let address = '';
                        address += (cell.getData().address_line_1 != '' ? cell.getData().address_line_1+' ' : '');
                        address += (cell.getData().address_line_2 != '' ? cell.getData().address_line_2+', ' : '');
                        address += (cell.getData().city != '' ? cell.getData().city+', ' : '');
                        address += (cell.getData().postal_code != '' ? cell.getData().postal_code : '');
                        return (address != '' ? '<div class="text-slate-500 text-xs whitespace-normal">'+address+'</div>' : '');
                    }
                },
                {
                    title: "Customer",
                    field: "full_name",
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) { 
                        var html = '<a href="'+route('customers.jobs.show', [cell.getData().customer_id, cell.getData().id])+'" class="block">';
                                html += '<div class="font-medium whitespace-nowrap">'+cell.getData().full_name+'</div>';
                            html += '</a>';
                        return html;
                    }
                },
                {
                    title: 'Priority',
                    field: 'priority',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                },
                {
                    title: 'Description',
                    field: 'description',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) { 
                        return '<div class="text-slate-500 text-xs whitespace-normal">'+cell.getData().description+'</div>';
                    }
                },
                {
                    title: 'Due Date',
                    field: 'due_date',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                },
                {
                    title: 'Status',
                    field: 'status',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                },
                {
                    title: 'Estimated Value',
                    field: 'estimated_amount',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                },
                /*{
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
                            btns += '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'" class="delete_btn rounded-full bg-danger text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="Trash2" class="w-4 h-4"></i></button>';
                        }  else {
                            btns += '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'"  class="restore_btn rounded-full bg-success text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="rotate-cw" class="w-4 h-4"></i></button>';
                        }
                        
                        return btns;
                    },
                },*/
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

        tableContent.on("rowClick", (e, row) => {
            window.open(route('customers.jobs.show', [row.getData().customer_id, row.getData().id]), '_blank');
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


(function () {
    if ($("#customerJobListTable").length) {
        customerJobListTable.init();

        function filtercustomerJobListTable() {
            customerJobListTable.init();
        }

        // On submit filter form
        $("#tabulator-html-filter-form").on("keypress", function (event) {
                let keycode = event.keyCode ? event.keyCode : event.which;
                if (keycode == "13") {
                    event.preventDefault();
                    filtercustomerJobListTable();
                }
            }
        );

        // On click go button
        $("#tabulator-html-filter-go").on("click", function (event) {
            filtercustomerJobListTable();
        });

        // On reset filter form
        $("#tabulator-html-filter-reset").on("click", function (event) {
            $("#query").val("");
            $("#status").val("1");
            filtercustomerJobListTable();
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
    $('#customerJobListTable').on('click', '.delete_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete these record? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETEJOB');
        });
    });

    // Restore Trigger
    $('#customerJobListTable').on('click', '.restore_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to restore these record? Click on agree to continue.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'RESTOREJOB');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETEJOB'){
            let customer_id = ($('#customerJobListTable').attr('data-customerid') ? $('#customerJobListTable').attr('data-customerid') : 0);
            axios({
                method: 'delete',
                url: route('customers.jobs.destroy', [customer_id, row_id]),
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
                customerJobListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RESTOREJOB'){
            let customer_id = ($('#customerJobListTable').attr('data-customerid') ? $('#customerJobListTable').attr('data-customerid') : 0);
            axios({
                method: 'post',
                url: route('customers.jobs.restore', [customer_id, row_id]),
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
                customerJobListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }
    })

})();