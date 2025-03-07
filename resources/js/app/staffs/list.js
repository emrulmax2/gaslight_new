(function () {
    "use strict";


    const el = document.querySelector("#edituser-modal");
    const editUserModal = tailwind.Modal.getOrCreateInstance(el);

    const updatesignatureModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatesignature-modal"));
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
                    title: 'Signature',
                    field: 'signature',
                    headerHozAlign: 'left',
                    
                    hozAlign: 'left',
                    width: '180',
                    download: false,
                    formatter(cell, formatterParams) {
                        let signatureUrl = cell.getData().signature;
                        if (signatureUrl) {
                            let a = $(`<div class="flex items-center lg:justify-center"> 
                                <img src="${signatureUrl}" class=" w-75 h-10 rounded-full" alt="signature">
                                </div>`);
                            return a[0];
                        } else {
                            return `<div class="flex items-center lg:justify-center">No Signature</div>`;
                        }
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
                        let signatureUrl = cell.getData().signature;
                        let a = [];
                        if (signatureUrl) {
                            a =
                                $(`<div class="flex items-center lg:justify-center">
                                    <a class="flex items-center mr-3 edit" href="javascript:;">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                            <a class="flex items-center delete text-danger" href="javascript:;">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                            </a>
                            </div>`);
                        } else {
                            a =
                                $(`<div class="flex items-center lg:justify-center">
                                    <a class="flex items-center mr-3 add-sgnature" href="javascript:;">
                                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Signature
                                </a>              
                                    <a class="flex items-center mr-3 edit" href="javascript:;">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                            <a class="flex items-center delete text-danger" href="javascript:;">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                            </a>
                            </div>`);
                        }
                        $(a)
                            .find(".edit")
                            .on("click", function () {

                                //editUserModal.toggle();

                                let id = cell.getData().id;
                                // name = cell.getData().name;
                                //let email = cell.getData().email;
                                //let gas_safe_id_card = cell.getData().gas_safe_id_card;
                                //let oil_registration_number = cell.getData().oil_registration_number;
                                //let installer_ref_no = cell.getData().installer_ref_no;
                                //$('#edituser-modal input[name="id"]').val(id);
                                //$('#edituser-modal input[name="name"]').val(name);
                                //$('#edituser-modal input[name="email"]').val(email);
                                //$('#edituser-modal input[name="gas_safe_id_card"]').val(gas_safe_id_card);
                               // $('#edituser-modal input[name="oil_registration_number"]').val(oil_registration_number);
                                //$('#edituser-modal input[name="installer_ref_no"]').val(installer_ref_no);
                                
                                location.href = route('staff.edit', id);

                            });

                            $(a)
                            .find(".add-sgnature")
                            .on("click", function () {
                                let id = cell.getData().id;
                                let company_id = cell.getData().company_id
                                updatesignatureModal.toggle();
                                $('#updatesignature-modal input[name="id"]').val(id);
                                $('#updatesignature-modal input[name="pid"]').val(company_id);
                                
                                $('#fileUploadForm input[name="id"]').val(id);
                                $('#addSignStaffForm input[name="edit_id"]').val(id);

                            });
                            
                        $(a)
                            .find(".delete")
                            .on("click", function () {
                                
                            });

                        

                        return a[0];

                    },
                },
            ],
            ajaxResponse:function(url, params, response){
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
