("use strict");
var subscriptionListTable = (function () {
    var _tableGen = function () {
        
        //let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let user = $("#user_id").val() != "" ? $("#user_id").val() : "";
        let status = $("#status").val() != "" ? $("#status").val() : "";

        let tableContent = new Tabulator("#subscriptionListTable", {
            ajaxURL: route("user.subscriptions.list"),
            ajaxParams: { status: status, user : user},//querystr: querystr,
            pagination: true,
            paginationMode:"remote",

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
                    title: 'User',
                    field: 'name',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    minWidth: 220,
                    formatter(cell, formatterParams) { 
                        var html = '';
                            html += '<div>';
                                html += '<div class="whitespace-nowrap font-medium">'+cell.getData().name+'</div>';
                                html += '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">'+cell.getData().email+'</div>';
                            html += '</div>';
                        return html;
                    }
                },
                {
                    title: "Package",
                    field: "package",
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) { 
                        var html = '';
                            html += '<div>';
                                html += '<div class="whitespace-nowrap font-medium">'+cell.getData().package+'</div>';
                                html += '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">'+cell.getData().start+' - '+cell.getData().end+'</div>';
                            html += '</div>';

                        return html;
                    }
                },
                {
                    title: "Price",
                    field: "price",
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                },
                {
                    title: 'Status',
                    field: 'status',
                    headerHozAlign: "center",
                    hozAlign: "center",
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) { 
                        if(cell.getData().status == 'trialing'){
                            return '<button class="font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Trialing</button>';
                        }else if(cell.getData().status == 'active'){
                            return '<button class="font-medium bg-success rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Active</button>';
                        }else if(cell.getData().status == 'incomplete'){
                            return '<button class="font-medium bg-warning rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Incomplete</button>';
                        }else if(cell.getData().status == 'incomplete_expired'){
                            return '<button class="font-medium bg-warning rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Incomplete Expired</button>';
                        }else if(cell.getData().status == 'canceled'){
                            return '<button class="font-medium bg-danger rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Canceled</button>';
                        }else if(cell.getData().status == 'unpaid'){
                            return '<button class="font-medium bg-danger rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Unpaid</button>';
                        }else if(cell.getData().status == 'paused'){
                            return '<button class="font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Paused</button>';
                        }
                    }
                },
                {
                    title: "Action",
                    field: "action",
                    headerSort: false,
                    hozAlign: "center",
                    headerHozAlign: "center",
                    download:false,
                    editable: false,
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) {     
                        var btns = '<a href="'+route('user.subscriptions.download.invoice', cell.getData().id) +'" class="printInvoice rounded-full bg-success text-white p-0 w-[30px] h-[30px] inline-flex justify-center items-center ml-1"><svg data-v-14c8c335="" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer-icon lucide-printer lucide-icon w-3 h-3"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"></path><rect x="6" y="14" width="12" height="8" rx="1"></rect></svg></a>';

                        return btns;
                    },
                },
            ],
            renderComplete() {
                createIcons({
                    icons,
                    attrs: { "stroke-width": 1.5 },
                    nameAttr: "data-lucide",
                });
            },
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

        

        // function updateColumnVisibility() {
        //     if (window.innerWidth < 768) {
        //         tableContent.getColumn("id").hide();
        //         tableContent.getColumn("priority").hide();
        //         tableContent.getColumn("status").hide();
        //         tableContent.getColumn("estimated_amount").hide();

        //     } else {
        //         tableContent.getColumn("id").show();
        //         tableContent.getColumn("priority").show();
        //         tableContent.getColumn("status").show();
        //         tableContent.getColumn("estimated_amount").show();
        //     }
        // }
        
        // window.addEventListener("resize", updateColumnVisibility);
        // setTimeout(() => {
        //     updateColumnVisibility();
        // }, 200)
    };
    return {
        init: function () {
            _tableGen();
        },
    };
})();


(function(){

    let subTomOptions = {
        plugins: {
            dropdown_input: {}
        },
        placeholder: 'Search Here...',
        create: false,
        allowEmptyOption: true,
        onDelete: function (values) {
            return confirm( values.length > 1 ? "Are you sure you want to remove these " + values.length + " items?" : 'Are you sure you want to remove "' +values[0] +'"?' );
        },
    };
    let userId = new TomSelect(document.getElementById('user_id'), subTomOptions);

    if ($("#subscriptionListTable").length) {
        subscriptionListTable.init();

        function filtersubscriptionListTable() {
            subscriptionListTable.init();
        }

        // On submit filter form
        $("#tabulator-html-filter-form").on("keypress", function (event) {
                let keycode = event.keyCode ? event.keyCode : event.which;
                if (keycode == "13") {
                    event.preventDefault();
                    filtersubscriptionListTable();
                }
            }
        );

        // On click go button
        $("#tabulator-html-filter-go").on("click", function (event) {
            filtersubscriptionListTable();
        });

        // On reset filter form
        $("#tabulator-html-filter-reset").on("click", function (event) {
            $("#query").val("");
            $("#status").val("1");
            userId.clear(true);
            filtersubscriptionListTable();
        });
    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    
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

})()