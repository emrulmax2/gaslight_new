("use strict");
const updatesignatureModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatesignature-modal"));
const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
const succModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
let confModalDelTitle = 'Are you sure?';

var usersListTable = (function () {
    var _tableGen = function () {
    let queryField = $('#tabulator-html-filter-field').val() != '' ? $('#tabulator-html-filter-field').val() : '';
    let queryType = $('#tabulator-html-filter-type').val() != '' ? $('#tabulator-html-filter-type').val() : '';
    let queryValue = $('#tabulator-html-filter-value').val() != '' ? $('#tabulator-html-filter-value').val() : '';
    // let status = $('#status').val() != '' ? $('#status').val() : '1';

    const tableData = new Tabulator("#usersListTable", {
        ajaxURL: route('users.list'),
        ajaxParams: {
            queryField: queryField,
            queryType: queryType,
            queryValue: queryValue,
        },
        ajaxFiltering: true,
        ajaxSorting: true,
        printAsHtml: true,
        printStyled: true,
        pagination: true,
        paginationMode:"remote",
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
                title: 'Pricing Package',
                field: 'package_id',
                headerHozAlign: 'left',
                formatter(cell, formatterParams) {
                    let html = '';
                    if(cell.getData().package_id > 0){
                        html += '<div class="whitespace-nowrap font-medium">'+cell.getData().package+'</div>';
                        html += '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                            html += (cell.getData().package_start != '' ? cell.getData().package_start : '');
                            html += (cell.getData().package_end != '' ? ' - '+cell.getData().package_end : '');
                        html += '</div>';
                    }

                    return html;
                },
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
                    let parent_id = cell.getData().visibility_control;
                    let id = cell.getData().id;
                    let company_id = cell.getData().company_id;
                
                    let a = [];
                
                    if (parent_id !== null) {
                        return $('<div class="flex items-center lg:justify-center"></div>')[0];
                    }
                
                    if (signatureUrl) {
                        a = $(`<div class="flex items-center lg:justify-center">
                            <a class="flex items-center mr-3 edit" href="javascript:;">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                            <a class="flex items-center delete text-danger" href="javascript:;">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                            </a>
                        </div>`);
                    } 
                    else {
                        a = $(`<div class="flex items-center lg:justify-center flex-wrap sm:flex-nowrap gap-2 sm:gap-0">
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
                
                    $(a).find(".edit").on("click", function () {
                        location.href = route('users.edit', id);
                    });
                
                    $(a).find(".add-sgnature").on("click", function () {
                        updatesignatureModal.toggle();
                        $('#updatesignature-modal input[name="id"]').val(id);
                        $('#updatesignature-modal input[name="pid"]').val(company_id);
                        $('#fileUploadForm input[name="id"]').val(id);
                        $('#addSignUserForm input[name="edit_id"]').val(id);
                    });
                
                    $(a).find(".delete").on("click", function () {
                        console.log(id);
                    });
                
                    return a[0];
                }
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
if ($("#usersListTable").length) {
    usersListTable.init();
}

function filterusersListTable() {
    usersListTable.init();
}

  // On submit filter form
  $("#tabulator-html-filter-form").on("keypress", function (event) {
        let keycode = event.keyCode ? event.keyCode : event.which;
        if (keycode == "13") {
            event.preventDefault();
            filterusersListTable();
        }
    }
);

// On click go button
$("#tabulator-html-filter-go").on("click", function (event) {
    filterusersListTable();
});

// On reset filter form
$("#tabulator-html-filter-reset").on("click", function (event) {
    $("#tabulator-html-filter-field").val("name");
    $("#tabulator-html-filter-type").val("like");
    $("#tabulator-html-filter-value").val("");
    filterusersListTable();
});

$("#add-new").on("click", function () {
    const el = document.querySelector("#addnew-modal");
    const modal = tailwind.Modal.getOrCreateInstance(el);
    modal.toggle();
});

})();
