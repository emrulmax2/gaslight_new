(function () {
    "use strict";



    // Tabulator
    if ($("#staffListTable").length) {
        let user =
        $('#staffListTable').attr('data-user') != ''
            ? $('#staffListTable').attr('data-user')
            : '0';
        let queryStr = $('#query-Eng').val() != '' ? $('#query-Eng').val() : '';
        let status = $('#status-Eng').val() != '' ? $('#status-Eng').val() : '1';
        // Setup Tabulator
        const tabulator = new Tabulator("#staffListTable", {
            ajaxURL: route('staff.list'),
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
                    field: 'sl',
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
                    title: 'Actions',
                    field: 'id',
                    headerSort: false,
                    hozAlign: 'right',
                    headerHozAlign: 'right',
                    width: '120',
                    download: false,
                    formatter(cell, formatterParams) {
                        var btns = '';
                        if (cell.getData().deleted_at == null) {
                            btns +=
                                '<button data-id="' +
                                cell.getData().id +
                                '" data-tw-toggle="modal" data-tw-target="#editNoteModal" type="button" class="edit_btn btn-rounded btn btn-success text-white p-0 w-9 h-9 ml-1"><i data-lucide="Pencil" class="w-4 h-4"></i></a>';
                            if (cell.getData().delete_url != null) {
                                btns +=
                                    '<button data-url="' +
                                    cell.getData().delete_url +
                                    '"  data-id="' +
                                    cell.getData().id +
                                    '" class="delete_btn btn btn-danger text-white btn-rounded ml-1 p-0 w-9 h-9"><i data-lucide="Trash2" class="w-4 h-4"></i></button>';
                            }
                        } else if (cell.getData().deleted_at != null) {
                            btns +=
                                '<button data-id="' +
                                cell.getData().id +
                                '" class="restore_btn btn btn-linkedin text-white btn-rounded ml-1 p-0 w-9 h-9"><i data-lucide="rotate-cw" class="w-4 h-4"></i></button>';
                        }

                        return btns;
                    },
                },
            ],
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
