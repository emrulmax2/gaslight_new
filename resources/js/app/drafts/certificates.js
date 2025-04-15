("use strict");

var certificateListTable = (function () {
    var _tableGen = function () {
    let querystr = $('#query').val() != '' ? $('#query').val() : '';
    let status = $('#status').val() != '' ? $('#status').val() : '';
    let engineerId = $('#engineer').val() != '' ? $('#engineer').val() : '';
    let certificateType = $('#certificate_type').val() != '' ? $('#certificate_type').val() : '';
    let dateRange = $('#date_range').val() != '' ? $('#date_range').val() : '';

    const tableData = new Tabulator("#certificateListTable", {
        ajaxURL: route('records-and-drafts.list'),
        ajaxParams: {
            queryStr: querystr,
            status: status,
            engineerId: engineerId,
            certificateType: certificateType,
            dateRange: dateRange
        },
        ajaxFiltering: true,
        ajaxSorting: true,
        printAsHtml: true,
        printStyled: true,
        pagination: true,
        paginationMode:"remote",
        paginationSize: 30,
        paginationSizeSelector: [true, 30, 50, 100],
        layout: 'fitColumns',
        responsiveLayout: 'collapse',
        placeholder: 'No matching records found',
        columns: [
            {
                title: 'Sl',
                field: 'id',
                headerHozAlign: 'left',
                width: 100
            },
            {
                title: 'Landlord',
                field: 'landlord_name',
                headerHozAlign: 'left',
                formatter(cell, formatterParams) { 
                    var html = '<div class="font-medium text-slate-500 max-sm:text-xs whitespace-nowrap flex justify-start items-center">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user stroke-1.5 mr-2 h-4 w-4 text-slate-500 sm:hidden"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                            html += cell.getData().landlord_name;
                        html += '</div>';
                    return html;
                },
            },
            {
                title: 'Landlord Address',
                field: 'landlord_address',
                headerHozAlign: 'left',
                formatter(cell, formatterParams) {
                    let theIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin stroke-1.5 mr-2 h-4 w-4 text-slate-500 sm:hidden"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                    return (cell.getData().landlord_address != '' ? '<div class="text-slate-500 text-xs whitespace-normal flex justify-start items-start">'+cell.getData().landlord_address+'</div>' : '');
                },
            },
            {
                title: 'Inspection Address',
                field: 'inspection_address',
                headerHozAlign: 'left',
                formatter(cell, formatterParams) {
                    let theIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin stroke-1.5 mr-2 h-4 w-4 text-slate-500 sm:hidden"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                    return (cell.getData().landlord_address != '' ? '<div class="text-slate-500 text-xs whitespace-normal flex justify-start items-start">'+cell.getData().inspection_address+'</div>' : '');
                },
            },
            {
                title: 'Certificate Type',
                field: 'certificate_type',
                headerHozAlign: 'left',
                formatter(cell, formatterParams) { 
                    var html = '<div class=" text-slate-500 text-xs whitespace-normal">';
                            html += cell.getData().certificate_type;
                        html += '</div>';
                    return html;
                },
            },
            {
                title: 'Created At',
                field: 'created_at',
                headerHozAlign: 'left',
                formatter(cell, formatterParams) {
                    return (cell.getData().landlord_address != '' ? '<div class="text-slate-500 whitespace-normal text-xs leading-[1.3]">'+cell.getData().created_at+'</div>' : '');
                },
            },
            {
                title: 'Status',
                field: 'status',
                headerHozAlign: 'left',
                formatter(cell, formatterParams) {
                    if(cell.getData().status == 'Cancelled'){
                        return '<button class="font-medium bg-danger rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Cancelled</button>';
                    }else if(cell.getData().status == 'Approved & Sent'){
                        return '<button class="font-medium bg-success rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved & Sent</button>';
                    }else if(cell.getData().status == 'Approved'){
                        return '<button class="font-medium bg-primary rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved</button>';
                    }else{
                        return '<button class="font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'+cell.getData().status+'</button>';
                    }
                }
            }
        ],
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
    if ($("#certificateListTable").length) {
        certificateListTable.init();
    }

    function filtercertificateListTable() {
        certificateListTable.init();
    }

    $("#tabulator-html-filter-form").on("keypress", function (event) {
        let keycode = event.keyCode ? event.keyCode : event.which;
        if (keycode == "13") {
            event.preventDefault();
            filtercertificateListTable();
        }
    }
    );

    $("#tabulator-html-filter-go").on("click", function (event) {
        filtercertificateListTable();
    });

    $("#tabulator-html-filter-reset").on("click", function (event) {
        $('#query').val('');
        $('#engineer').val('all');
        $('#certificate_type').val('all');
        $('#date_range').val('');
        $('#status').val('all');
        filtercertificateListTable();
    });


})();
