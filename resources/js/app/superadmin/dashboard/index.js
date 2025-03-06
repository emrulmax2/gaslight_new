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
                    title: 'Sl',
                    field: 'id',
                    headerHozAlign: 'left',
                },
                {
                    title: 'Name',
                    field: 'name',
                    headerHozAlign: 'left',
                },
                {
                    title: 'Email',
                    field: 'email',
                    headerHozAlign: 'left',
                },
                {
                    title: 'Status',
                    field: 'status',
                    headerHozAlign: 'left',
                },
                
                {
                    title: 'Company Name',
                    field: 'company_name',
                    headerHozAlign: 'left',
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
                        
                        return '<a target="__blank" href="'+cell.getData().impersonate_url+'" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-success border-success text-slate-900 dark:border-success mb-2 mr-2 w-32 ">\
                        Login As <i data-lucide="log-in" class="stroke-1.5 w-5 h-5 mr-2"></i>\
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
    }
})();
