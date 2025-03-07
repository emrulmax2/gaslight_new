("use strict");
// Tabulator

const addNewModal = tailwind.Modal.getOrCreateInstance(document.getElementById('addnew-modal'));
const editModal = tailwind.Modal.getOrCreateInstance(document.getElementById('edit-modal'));
const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));

var BoilerBrandListTable = (function () {

    var _tableGen = function () {        
            let querystr = $("#query").val() != "" ? $("#query").val() : "";
            let status = $("#status").val() != "" ? $("#status").val() : "";
            let boiler_brand_id = $("#boiler_brand_id").val() != "" ? $("#boiler_brand_id").val() : "";
            // Setup Tabulator
            const tabulator = new Tabulator("#boilerBrandListTable", {
                ajaxURL: route('superadmin.boiler-manual.list'),
                ajaxParams: {
                    queryStr: querystr,
                    status: status,
                    'boiler_brand_id': boiler_brand_id,
                },
                pagination: "remote", // Ensure pagination is set to remote
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
                        title: 'Sl',
                        field: 'id',
                        headerHozAlign: 'left',
                    },
                    {
                        title: 'GC No',
                        field: 'gc_no',
                        headerHozAlign: 'left',
                    },
                    {
                        title: 'URL',
                        field: 'url',
                        headerHozAlign: 'left',
                    },
                    {
                        title: 'Model',
                        field: 'model',
                        headerHozAlign: 'left',
                    },
                    {
                        title: 'Fuel Type',
                        field: 'fuel_type',
                        headerHozAlign: 'left',
                    },
                    {
                        title: 'Year of Manufacture',
                        field: 'year_of_manufacture',
                        headerHozAlign: 'left',
                    },

                    {
                        title: 'Actions',
                        field: 'id',
                        headerSort: false,
                        headerHozAlign: 'center',
                        hozAlign:"center",
                        download: false,
                        formatter(cell, formatterParams) {
                            let a =
                                $(`<div class="flex items-center lg:justify-center">
                                                 
                                    <a class="flex items-center mr-3 edit" href="javascript:;">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                            <a class="flex items-center delete text-danger" href="javascript:;">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                            </a>
                            </div>`);
                            $(a)
                            .find(".edit")
                            .on("click", function () {

                                //editUserModal.toggle();

                                let id = cell.getData().id;
                                $('#edit-modal input[name="id"]').val(id);
                                $('#edit-modal input[name="gc_no"]').val(cell.getData().gc_no);
                                $('#edit-modal input[name="url"]').val(cell.getData().url);
                                $('#edit-modal input[name="model"]').val(cell.getData().model);
                                $('#edit-modal input[name="fuel_type"]').val(cell.getData().fuel_type);
                                $('#edit-modal input[name="year_of_manufacture"]').val(cell.getData().year_of_manufacture);

                                editModal.toggle();
                                //location.href = route('staff.edit', id);

                            });

                            
                        $(a)
                            .find(".delete")
                            .on("click", function () {
                                
                                let id = cell.getData().id;
                            });

                        

                        return a[0];
                        },
                    },
                ],
                ajaxResponse:function(url, params, response){
                    //console.log("Pagination Data:", response); // Log the response to check pagination data
        
                    return response;
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

            // Export
            $("#tabulator-export-csv").on("click", function (event) {
                tabulator.download("csv", "data.csv");
            });

            $("#tabulator-export-json").on("click", function (event) {
                tabulator.download("json", "data.json");
            });

            $("#tabulator-export-xlsx").on("click", function (event) {
                window.XLSX = xlsx;
                tabulator.download("xlsx", "data.xlsx", {
                    sheetName: "Title Details",
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

    };
    return {
        init: function () {
            _tableGen();
        },
    };
})();

(function(){
    
    if ($("#boilerBrandListTable").length) {
        BoilerBrandListTable.init();
    }

    function filterBoilerBrandListTable() {
        BoilerBrandListTable.init();
    }

    // On submit filter form
    $("#tabulator-html-filter-form").on("keypress", function (event) {
            let keycode = event.keyCode ? event.keyCode : event.which;
            if (keycode == "13") {
                event.preventDefault();
                filterBoilerBrandListTable();
            }
        }
    );

    // On click go button
    $("#tabulator-html-filter-go").on("click", function (event) {
        filterBoilerBrandListTable();
    });

    // On reset filter form
    $("#tabulator-html-filter-reset").on("click", function (event) {
        $("#query").val("");
        $("#status").val("1");
        filterBoilerBrandListTable();
    }); 
})();
