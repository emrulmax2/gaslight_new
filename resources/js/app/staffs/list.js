    ("use strict");
    const el = document.querySelector("#edituser-modal");
    const editUserModal = tailwind.Modal.getOrCreateInstance(el);

    const updatesignatureModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatesignature-modal"));

    var staffListTable = (function () {
        var _tableGen = function () {
        let user = $('#staffListTable').attr('data-user') != '' ? $('#staffListTable').attr('data-user') : '0';
        let queryField = $('#tabulator-html-filter-field').val() != '' ? $('#tabulator-html-filter-field').val() : '';
        let queryType = $('#tabulator-html-filter-type').val() != '' ? $('#tabulator-html-filter-type').val() : '';
        let queryValue = $('#tabulator-html-filter-value').val() != '' ? $('#tabulator-html-filter-value').val() : '';
        let status = $('#status').val() != '' ? $('#status').val() : '1';
        // Setup Tabulator
        const tableData = new Tabulator("#staffListTable", {
            ajaxURL: route('staff.list'),
            ajaxParams: {
                user_id: user,
                queryField: queryField,
                queryType: queryType,
                queryValue: queryValue,
                status: status
            },
            ajaxFiltering: true,
            ajaxSorting: true,
            printAsHtml: true,
            printStyled: true,
            pagination: 'remote',
            paginationSize: 30,
            paginationSizeSelector: [true, 30,50,100],
            layout: 'fitColumns',
            responsiveLayout: 'collapse',
            placeholder: 'No matching records found',
            columns: [
                
                {
                    title: 'Sl',
                    field: 'id',
                    headerHozAlign: 'left',
                    minWidth: 80
                },
                {
                    title: 'Name',
                    field: 'name',
                    headerHozAlign: 'left',
                    minWidth: 180
                },
                {
                    title: 'Email',
                    field: 'email',
                    headerHozAlign: 'left',
                    minWidth: 200
                },
                {
                    title: 'Status',
                    field: 'status',
                    headerHozAlign: 'left',
                    minWidth: 80
                },

                {
                    title: 'Signature',
                    field: 'signature',
                    headerHozAlign: 'left',
                    hozAlign: 'left',
                    width: '180',
                    download: false,
                    minWidth: 180,
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
                                $(`<div class="flex items-center lg:justify-center flex-wrap sm:flex-nowrap gap-2 sm:gap-0">
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

        tableData.on("renderComplete", () => {
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
            tableData.redraw();
            createIcons({
                icons,
                "stroke-width": 1.5,
                nameAttr: "data-lucide",
            });
        });

    };
    return {
        init: function () {
            _tableGen();
        },
    };
})();

(function () {
    if ($("#staffListTable").length) {
        staffListTable.init();
    }

    function filterStaffListTable() {
        staffListTable.init();
    }

      // On submit filter form
      $("#tabulator-html-filter-form").on("keypress", function (event) {
            let keycode = event.keyCode ? event.keyCode : event.which;
            if (keycode == "13") {
                event.preventDefault();
                filterStaffListTable();
            }
        }
    );

    // On click go button
    $("#tabulator-html-filter-go").on("click", function (event) {
        filterStaffListTable();
    });

    // On reset filter form
    $("#tabulator-html-filter-reset").on("click", function (event) {
        $("#tabulator-html-filter-field").val("name");
        $("#tabulator-html-filter-type").val("like");
        $("#tabulator-html-filter-value").val("");
        filterStaffListTable();
    });
})();
