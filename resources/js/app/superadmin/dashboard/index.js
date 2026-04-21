
var userListTable = (function () {
    var _tableGen = function () {        
        let queryStr = $("#query").val() != "" ? $("#query").val() : "";
        let status = $("#status").val() != "" ? $("#status").val() : "";
        

        const tabulator = new Tabulator("#userListTable", {
            ajaxURL: route('superadmin.users.list'),
            ajaxParams: {
                queryStr: queryStr
            },

            pagination: true,
            paginationMode:"remote",
            filterMode: "remote",
            sortMode: "remote",
            printAsHtml: true,
            printStyled: true,
            paginationSize: 50,
            paginationSizeSelector: [true, 20, 30, 50, 100, 200, 500],
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "No matching records found",
            columns: [
                {
                    title: 'Name',
                    field: 'name',
                    headerHozAlign: 'left',
                    formatter(cell) {
                        const response = cell.getData();
                        return `<div>
                            <div class="font-medium whitespace-nowrap">${response.name}</div>
                            <div class="text-xs text-slate-500 whitespace-nowrap">${response.company_name}</div>
                        </div>`;
                    },
                },
                {
                    title: 'Email',
                    field: 'email',
                    headerHozAlign: 'left',
                    formatter(cell) {
                        const response = cell.getData();
                        return `<div>
                            <div class="font-medium whitespace-nowrap">${response.email}</div>
                            <div class="text-xs text-slate-500 whitespace-nowrap">${response.mobile}</div>
                        </div>`;
                    },
                },
                {
                    title: 'Subscription',
                    field: 'package',
                    headerHozAlign: 'left',
                    formatter(cell) {
                        const response = cell.getData();
                        return `<div>
                            <div class="font-medium whitespace-nowrap ${response.color}">${response.package}</div>
                            <div class="text-xs text-slate-500 whitespace-nowrap">
                                <span class="font-medium text-success">${response.price}</span>
                                <span class="${response.color}">${response.next_renew !== '' ? ' - '+response.next_renew : ''}</span>
                            </div>
                        </div>`;
                    },
                },
                // {
                //     title: 'Status',
                //     field: 'status',
                //     headerHozAlign: 'left',
                //     formatter(cell) {
                //         const response = cell.getData();
                //         return (response.status == 1 ? '<span class="bg-success text-xs text-white font-medium leading-none px-2 py-0.5">Active</span>' : '<span class="bg-danger text-xs text-white font-medium leading-none px-2 py-0.5">Inactive</span>')
                //     },
                // },
                {
                    title: 'Actions',
                    field: 'id',
                    headerSort: false,
                    hozAlign: 'right',
                    headerHozAlign: 'right',
                    width: '250',
                    download: false,
                    formatter(cell, formatterParams) {
                        let html = '';
                            html += '<a target="__blank" href="'+cell.getData().impersonate_url+'" class="transition text-white duration-200 border shadow-sm inline-flex items-center justify-center text-xs py-1.5 px-3 rounded-sm font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-success border-success dark:border-success w-auto ">\
                                    Login As <i data-lucide="log-in" class="stroke-1.5 w-5 h-5 ml-2"></i></a>';
                            if(cell.getData().is_superadmin){
                                html += '<a href="javascript:void(0);" data-id="'+cell.getData().id+'" class="delete_row ml-2 transition text-white duration-200 border shadow-sm inline-flex items-center justify-center text-xs py-1.5 px-3 rounded-sm font-medium cursor-pointer focus:ring-4 focus:ring-danger focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-danger border-danger dark:border-danger w-auto ">\
                                        Delete <i data-lucide="trash-2" class="stroke-1.5 w-5 h-5 ml-2"></i>\
                                    </a>';
                            }

                        return html;
                    },
                },
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

        tabulator.on("renderComplete", () => {
            createIcons({
                icons,
                attrs: {
                    "stroke-width": 1.5,
                },
                nameAttr: "data-lucide",
            });
        });

        // Redraw table onresize
        window.addEventListener("resize", () => {
            tabulator.redraw();
            createIcons({
                icons,
                "stroke-width": 1.5,
                nameAttr: "data-lucide",
            });
        });

        // Print
        // $("#tabulator-print").on("click", function (event) {
        //     tabulator.print();
        // });

    };
    return {
        init: function () {
            _tableGen();
        },
    };
})();

(function () {
    "use strict";


    if ($("#userListTable").length) {
        userListTable.init();
    }

    function userListTableForm() {
        userListTable.init();
    }

    // On submit filter form
    $("#tabulator-html-filter-form").on("keypress", function (event) {
            let keycode = event.keyCode ? event.keyCode : event.which;
            if (keycode == "13") {
                event.preventDefault();
                userListTableForm();
            }
        }
    );

    // On click go button
    $("#tabulator-html-filter-go").on("click", function (event) {
        userListTableForm();
    });

    // On reset filter form
    $("#tabulator-html-filter-reset").on("click", function (event) {
        $("#query").val("");
        $("#status").val("1");
        userListTableForm();
    }); 


    // Tabulator
    if ($("#userListTable").length) {
        

        const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
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

        $('#userListTable').on("click", '.delete_row', function () {
            let id = $(this).attr('data-id');
            confirmModal.show();
            document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event) {
                $('#confirmModal .confirmModalTitle').html('Are you sure?');
                $('#confirmModal .confirmModalDesc').html('Do you really want to delete these record? This action can not be un done! If yes then please click on the agree btn.');
                $('#confirmModal .agreeWith').attr('data-id', id);
                $('#confirmModal .agreeWith').attr('data-action', 'DELETE');
            });
        });

        $('#confirmModal .agreeWith').on('click', function(){
            let $agreeBTN = $(this);
            let row_id = $agreeBTN.attr('data-id');
            let action = $agreeBTN.attr('data-action');

            $('#confirmModal button').attr('disabled', 'disabled');
            if(action == 'DELETE'){
                axios({
                    method: 'delete',
                    url: route('superadmin.users.delete', row_id),
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    if (response.status == 200) {
                        $('#confirmModal button').removeAttr('disabled');
                        confirmModal.hide();

                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.msg);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                        });

                        setTimeout(function(){
                            successModal.hide();
                            window.location.reload();
                        }, 1500)
                    }
                }).catch(error =>{
                    console.log(error)
                });
            }
        })
    }
})();
