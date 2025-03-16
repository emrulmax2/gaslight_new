("use strict");
var jobListTable = (function () {
    var _tableGen = function () {
        
        let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let status = $("#status").val() != "" ? $("#status").val() : "";
        let recordparams = $("#jobListTable").attr('data-params') != "" ? $("#jobListTable").attr('data-params') : "";

        let tableContent = new Tabulator("#jobListTable", {
            ajaxURL: route("jobs.list"),
            ajaxParams: { querystr: querystr, status: status, recordparams : recordparams},
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
                    title: "#ID",
                    field: "id",
                    vertAlign: 'middle',
                    width: "80",
                    responsive: 0,
                    cellClick:function(e, cell){
                        window.location.href = cell.getData().url;
                    },
                    cellTap:function(e, cell){
                        window.location.href = cell.getData().url;
                    }
                },
                {
                    title: 'Description',
                    field: 'description',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    minWidth: 220,
                    formatter(cell, formatterParams) { 
                        return '<div class="font-medium whitespace-normal">'+cell.getData().description+'</div>';
                    },
                    cellClick:function(e, cell){
                        window.location.href = cell.getData().url;
                    },
                    cellTap:function(e, cell){
                        window.location.href = cell.getData().url;
                    }
                },
                {
                    title: "Customer",
                    field: "full_name",
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    minWidth: 220,
                    formatter(cell, formatterParams) { 
                        var html = '<div class="font-medium text-slate-500 max-sm:text-xs whitespace-nowrap flex justify-start items-center">';
                                html += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user stroke-1.5 mr-2 h-4 w-4 text-slate-500 sm:hidden"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                                html += cell.getData().full_name;
                            html += '</div>';
                        return html;
                    },
                    cellClick:function(e, cell){
                        window.location.href = cell.getData().url;
                    },
                    cellTap:function(e, cell){
                        window.location.href = cell.getData().url;
                    }
                },
                {
                    title: "Address",
                    field: "address_line_1",
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    minWidth: 300,
                    formatter(cell, formatterParams) {
                        let theIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin stroke-1.5 mr-2 h-4 w-4 text-slate-500 sm:hidden"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                        let address = '';
                        address += (cell.getData().address_line_1 != '' ? cell.getData().address_line_1+' ' : '');
                        address += (cell.getData().address_line_2 != '' ? cell.getData().address_line_2+', ' : '');
                        address += (cell.getData().city != '' ? cell.getData().city+', ' : '');
                        address += (cell.getData().postal_code != '' ? cell.getData().postal_code : '');
                        return (address != '' ? '<div class="text-slate-500 text-xs whitespace-normal flex justify-start items-start">'+theIcon+address+'</div>' : '');
                    },
                    cellClick:function(e, cell){
                        let url = route('jobs.show', cell.getData().id);
                        let urlParams = new URLSearchParams(window.location.search);
                        let recordParam = urlParams.get('record');

                        if(recordParam){
                            url += '?record='+recordParam;
                        }

                        window.location.href = url;
                        console.log('click')
                    },
                    cellTap:function(e, cell){
                         let url = route('jobs.show', cell.getData().id);
                        let urlParams = new URLSearchParams(window.location.search);
                        let recordParam = urlParams.get('record');

                        if(recordParam){
                            url += '?record='+recordParam;
                        }
                        
                        window.location.href = url;
                        console.log('tap')
                    }
                },
                {
                    title: 'Priority',
                    field: 'priority',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    responsive: 0,
                    cellClick:function(e, cell){
                        window.location.href = cell.getData().url;
                    },
                    cellTap:function(e, cell){
                        window.location.href = cell.getData().url;
                    }
                },
                {
                    title: 'Status',
                    field: 'status',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    responsive: 0,
                    cellClick:function(e, cell){
                        window.location.href = cell.getData().url;
                    },
                    cellTap:function(e, cell){
                        window.location.href = cell.getData().url;
                    }
                },
                {
                    title: 'Estimated Value',
                    field: 'estimated_amount',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                    responsive: 0,
                    cellClick:function(e, cell){
                        window.location.href = cell.getData().url;
                    },
                    cellTap:function(e, cell){
                        window.location.href = cell.getData().url;
                    }
                },
                {
                    title: "&nbsp;",
                    field: "action",
                    headerSort: false,
                    hozAlign: "center",
                    headerHozAlign: "center",
                    width: "80",
                    download:false,
                    editable: false,
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) {     
                        if(cell.getData().calendar_added == 1){
                            var dates = cell.getData().calendar_date;
                                dates = dates.split('_');
                            var btns = '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'" data-tw-toggle="modal" data-tw-target="#addJobCalenderModal" class="addCalenderBtn addedCalBtn border-0 rounded-[3px] bg-slate-200 text-dark p-0 w-[36px] h-[36px] inline-block text-center ml-1">';
                                    btns += '<span style="background: '+cell.getData().calendar_color+'" class="block rounded-t-[3px] -mt-[3px] bg-success py-[1px] text-center text-white whitespace-nowrap font-medium uppercase leading-[1.2] text-[10px]">'+dates[1]+'</span>';
                                    btns += '<span style="color: '+cell.getData().calendar_color+'" class="block leading-[1] pt-[5px] text-[14px] font-bold">'+dates[0]+'</span>';
                                btns += '</button>';
                        }else{            
                            var btns = '<button data-customer="'+cell.getData().customer_id+'" data-id="' +cell.getData().id +'" data-tw-toggle="modal" data-tw-target="#addJobCalenderModal" class="addCalenderBtn rounded-full bg-success text-white p-0 w-[30px] sm:w-[36px] h-[30px] sm:h-[36px] inline-flex justify-center items-center ml-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" data-lucide="calendar-days" class="lucide lucide-calendar-days w-3 sm:w-4 h-3 sm:h-4"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg></button>';
                        } 

                        return btns;
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

        // tableContent.on("cellClick", function(e, cell){
        //     console.log('no click')
        //     var row = cell.getRow(cell);
        //     var row_id = row.getData().id;
        //     var field = cell.getColumn().getField();
        //     if(field != 'action'){
        //         //window.location.href = route('jobs.show', row_id);
        //     }
        // });

        tableContent.on("rowClick", (e, row) => {
            var cells = row.getCells();
            if (window.innerWidth < 768) {
                if(!$(e.target).hasClass('addCalenderBtn') && !$(e.target).parents('button').hasClass('addCalenderBtn')){
                    window.location.href = row.getData().url;
                }
            }
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

        

        function updateColumnVisibility() {
            if (window.innerWidth < 768) {
                tableContent.getColumn("id").hide();
                tableContent.getColumn("priority").hide();
                tableContent.getColumn("status").hide();
                tableContent.getColumn("estimated_amount").hide();

            } else {
                tableContent.getColumn("id").show();
                tableContent.getColumn("priority").show();
                tableContent.getColumn("status").show();
                tableContent.getColumn("estimated_amount").show();
            }
        }
        
        window.addEventListener("resize", updateColumnVisibility);
        setTimeout(() => {
            updateColumnVisibility();
        }, 200)
    };
    return {
        init: function () {
            _tableGen();
        },
    };
})();


(function () {
    if ($("#jobListTable").length) {
        jobListTable.init();

        function filterjobListTable() {
            jobListTable.init();
        }

        // On submit filter form
        $("#tabulator-html-filter-form").on("keypress", function (event) {
                let keycode = event.keyCode ? event.keyCode : event.which;
                if (keycode == "13") {
                    event.preventDefault();
                    filterjobListTable();
                }
            }
        );

        // On click go button
        $("#tabulator-html-filter-go").on("click", function (event) {
            filterjobListTable();
        });

        // On reset filter form
        $("#tabulator-html-filter-reset").on("click", function (event) {
            $("#query").val("");
            $("#status").val("1");
            filterjobListTable();
        });
    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addJobCalenderModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addJobCalenderModal"));
    
    
    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
    });
    
    document.getElementById('addJobCalenderModal').addEventListener('hide.tw.modal', function(event) {
        $('#addJobCalenderModal input[name="customer_job_id"]').val('0');
        $('#addJobCalenderModal input[name="date"]').val('');
        $('#addJobCalenderModal input[type="radio"]').prop('checked', false);
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


    // Delete Trigger
    $('#jobListTable').on('click', '.delete_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete these record? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETEJOB');
        });
    });

    // Restore Trigger
    $('#jobListTable').on('click', '.restore_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to restore these record? Click on agree to continue.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'RESTOREJOB');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETEJOB'){
            let customer_id = ($('#jobListTable').attr('data-customerid') ? $('#jobListTable').attr('data-customerid') : 0);
            axios({
                method: 'delete',
                url: route('customers.jobs.destroy', [customer_id, row_id]),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById('successModal').addEventListener('shown.tw.modal', function(event){
                        $('#successModal .successModalTitle').html('WOW!');
                        $('#successModal .successModalDesc').html(response.data.msg);
                    });
                }
                jobListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RESTOREJOB'){
            let customer_id = ($('#jobListTable').attr('data-customerid') ? $('#jobListTable').attr('data-customerid') : 0);
            axios({
                method: 'post',
                url: route('customers.jobs.restore', [customer_id, row_id]),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById('successModal').addEventListener('shown.tw.modal', function(event){
                        $('#successModal .successModalTitle').html('WOW!');
                        $('#successModal .successModalDesc').html(response.data.msg);
                    });
                }
                jobListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }
    });

    $('#jobListTable').on('click', '.addCalenderBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let customer_job_id = $theBtn.attr('data-id');

        axios({
            method: "post",
            url: route('jobs.get.calendar.details'),
            data: {customer_job_id : customer_job_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                var row = response.data.row;
                $('#addJobCalenderModal input[name="customer_job_id"]').val(customer_job_id);
                $('#addJobCalenderModal input[name="date"]').val(row.date);
                $('#addJobCalenderModal input#slot-'+row.calendar_time_slot_id).prop('checked', true);
            }
        }).catch(error => {
            if (error.response) {
                console.log('error');
            }
        });
    });

    $('#addJobCalenderForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addJobCalenderForm');
        const $theForm = $(this);
        
        $('#addCalendarBtn', $theForm).attr('disabled', 'disabled');
        $("#addCalendarBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('jobs.add.to.calendar'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#addCalendarBtn', $theForm).removeAttr('disabled');
            $("#addCalendarBtn .theLoader").fadeOut();

            if (response.status == 200) {
                addJobCalenderModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                });

                setTimeout(() => {
                    successModal.hide();
                }, 1500);
            }
            jobListTable.init();
        }).catch(error => {
            $('#addCalendarBtn', $theForm).removeAttr('disabled');
            $("#addCalendarBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#addJobCalenderForm .${key}`).addClass('border-danger');
                        $(`#addJobCalenderForm  .error-${key}`).html(val);
                    }
                } else {
                    console.log('error');
                }
            }
        });
    })

})();