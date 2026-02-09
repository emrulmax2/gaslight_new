(function () {
    "use strict";


    // Tabulator
    if ($("#userListTable").length) {
        let user =
        $('#userListTable').attr('data-user') != ''
            ? $('#userListTable').attr('data-user')
            : '0';
        let queryStr = $('#query-Eng').val() != '' ? $('#query-Eng').val() : '';
        let status = $('#status-Eng').val() != '' ? $('#status-Eng').val() : '1';
        // Setup Tabulator
        const tabulator = new Tabulator("#userListTable", {
            ajaxURL: route('superadmin.users.list'),
            ajaxParams: {
                user_id: user,
                queryStr: queryStr,
                status: status,
            },
            ajaxFiltering: true,
            ajaxSorting: true,
            printAsHtml: true,
            printStyled: true,
            pagination: 'remote',
            paginationSize: 10,
            paginationSizeSelector: [true, 5, 10, 20, 30, 40],
            layout: 'fitColumns',
            responsiveLayout: 'collapse',
            placeholder: 'No matching records found',
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
                            <div class="font-medium whitespace-nowrap">${response.package}</div>
                            <div class="text-xs text-slate-500 whitespace-nowrap">
                                <span class="font-medium text-success">${response.price}</span>
                                ${response.next_renew !== '' ? ' - '+response.next_renew : ''}
                            </div>
                        </div>`;
                    },
                },
                {
                    title: 'Status',
                    field: 'status',
                    headerHozAlign: 'left',
                    formatter(cell) {
                        const response = cell.getData();
                        return (response.status == 1 ? '<span class="bg-success text-xs text-white font-medium leading-none px-2 py-0.5">Active</span>' : '<span class="bg-danger text-xs text-white font-medium leading-none px-2 py-0.5">Inactive</span>')
                    },
                },
                {
                    title: 'Actions',
                    field: 'id',
                    headerSort: false,
                    hozAlign: 'right',
                    headerHozAlign: 'right',
                    width: '250',
                    download: false,
                    formatter(cell, formatterParams) {
                        
                        return '<a target="__blank" href="'+cell.getData().impersonate_url+'" class="transition text-white duration-200 border shadow-sm inline-flex items-center justify-center text-xs py-1.5 px-3 rounded-sm font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-success border-success dark:border-success w-auto ">\
                                    Login As <i data-lucide="log-in" class="stroke-1.5 w-5 h-5 ml-2"></i>\
                                </a><a href="javascript:void(0);" data-id="'+cell.getData().id+'" class="delete_row ml-2 transition text-white duration-200 border shadow-sm inline-flex items-center justify-center text-xs py-1.5 px-3 rounded-sm font-medium cursor-pointer focus:ring-4 focus:ring-danger focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-danger border-danger dark:border-danger w-auto ">\
                                    Delete <i data-lucide="trash-2" class="stroke-1.5 w-5 h-5 ml-2"></i>\
                                </a>';
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

        // Filter function
        function filterHTMLForm() {
            let field = $("#tabulator-html-filter-field").val();
            let type = $("#tabulator-html-filter-type").val();
            let value = $("#tabulator-html-filter-value").val();
            tabulator.setFilter(field, type, value);
        }

        // On submit filter form
        $("#tabulator-html-filter-form")[0].addEventListener(
            "keypress",
            function (event) {
                let keycode = event.keyCode ? event.keyCode : event.which;
                if (keycode == "13") {
                    event.preventDefault();
                    filterHTMLForm();
                }
            }
        );

        // On click go button
        $("#tabulator-html-filter-go").on("click", function (event) {
            filterHTMLForm();
        });

        // On reset filter form
        $("#tabulator-html-filter-reset").on("click", function (event) {
            $("#tabulator-html-filter-field").val("name");
            $("#tabulator-html-filter-type").val("like");
            $("#tabulator-html-filter-value").val("");
            filterHTMLForm();
        });

        // Export
        $("#tabulator-export-csv").on("click", function (event) {
            tabulator.download("csv", "data.csv");
        });

        $("#tabulator-export-json").on("click", function (event) {
            tabulator.download("json", "data.json");
        });

        $("#tabulator-export-xlsx").on("click", function (event) {
            tabulator.download("xlsx", "data.xlsx", {
                sheetName: "Products",
            });
        });

        $("#tabulator-export-html").on("click", function (event) {
            tabulator.download("html", "data.html", {
                style: true,
            });
        });

        // Print
        $("#tabulator-print").on("click", function (event) {
            tabulator.print();
        });

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
