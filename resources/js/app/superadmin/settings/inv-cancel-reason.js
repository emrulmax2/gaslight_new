import Litepicker from "litepicker";
("use strict");
// Tabulator

var invCancelReasonList = (function () {
    var _tableGen = function () {        
            let querystr = $("#query").val() != "" ? $("#query").val() : "";
            let status = $("#status").val() != "" ? $("#status").val() : "";
            

            const tabulator = new Tabulator("#invCancelReasonList", {
                ajaxURL: route('superadmin.site.setting.inv.cancel.reason.list'),
                ajaxParams: {
                    queryStr: querystr,
                    status: status
                },

                pagination: true,
                paginationMode:"remote",
                filterMode: "remote",
                sortMode: "remote",
                printAsHtml: true,
                printStyled: true,
                paginationSize: 50,
                paginationSizeSelector: [true, 20, 30, 50, 100, 200, 500],
                layout: "fitColumns",
                responsiveLayout: "collapse",
                placeholder: "No matching records found",
                columns: [
                    {
                        title: 'Sl',
                        field: 'id',
                        headerHozAlign: 'left',
                        width: 120
                    },
                    {
                        title: 'Reason',
                        field: 'name',
                        headerHozAlign: 'left',
                        formatter(cell, formatterParams) { 
                            var html = '<div>';
                                    html += '<div class="font-medium whitespace-nowrap">'+cell.getData().name+'</div>';
                                html += '</div>';
                            return html;
                        }
                    },
                    {
                        title: 'Active',
                        field: 'active',
                        headerHozAlign: 'left',
                        formatter(cell, formatterParams) {
                            return cell.getData().active == 1 ? '<span class="inline-flex font-medium bg-success text-xs text-white px-1 py-0.5">Yes</span>' : '<span class="inline-flex font-medium bg-danger text-xs text-white px-1 py-0.5">No</span>';
                        }
                    },
                    {
                        title: 'Actions',
                        field: 'id',
                        headerSort: false,
                        headerHozAlign: 'center',
                        hozAlign:"center",
                        download: false,
                        formatter(cell, formatterParams) {
                            let a;
                            if(cell.getData().deleted_at != null){
                                a = $(`<div class="flex items-center lg:justify-center">            
                                        <a class="inline-flex justify-center items-center restore w-[30px] h-[30px] bg-primary rounded-full text-white" href="javascript:;">
                                            <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                                        </a>
                                    </div>`);
                                    $(a).find(".restore").on("click", function () {
                                        const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
                                        confirmModal.show();
                                        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
                                            $('#confirmModal .confirmModalTitle').html('Are you sure?');
                                            $('#confirmModal .confirmModalDesc').html('Do you really want to restore these record? Click on agree to continue.');
                                            $('#confirmModal .agreeWith').attr('data-id', cell.getData().id);
                                            $('#confirmModal .agreeWith').attr('data-action', 'RESTORE');
                                        });
                                    });
                            }else{
                                a = $(`<div class="flex items-center lg:justify-center">            
                                        <a class="inline-flex justify-center items-center edit w-[30px] h-[30px] bg-success rounded-full text-white" href="javascript:;">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <a class="inline-flex justify-center items-center ml-1 delete w-[30px] h-[30px] bg-danger rounded-full text-white" href="javascript:;">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>`);
                                $(a).find(".edit").on("click", function () {
                                    const editInvCancelReasonModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#editInvCancelReasonModal"));
                                    editInvCancelReasonModal.show();
                                    document.getElementById('editInvCancelReasonModal').addEventListener('shown.tw.modal', function(event) {
                                        $('#editInvCancelReasonModal [name="name"]').val(cell.getData().name);
                                        $('#editInvCancelReasonModal [name="id"]').val(cell.getData().id);
                                        if(cell.getData().active == 1){
                                            $('#editInvCancelReasonModal [name="active"]').prop('checked', true);
                                        }else{
                                            $('#editInvCancelReasonModal [name="active"]').prop('checked', false);
                                        }
                                        $('#editInvCancelReasonModal .acc__input-error').html('');
                                    });
                                });
                                $(a).find(".delete").on("click", function () {
                                    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
                                    confirmModal.show();
                                    document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
                                        $('#confirmModal .confirmModalTitle').html('Are you sure?');
                                        $('#confirmModal .confirmModalDesc').html('Do you really want to delete these record? If yes then please click on the agree btn.');
                                        $('#confirmModal .agreeWith').attr('data-id', cell.getData().id);
                                        $('#confirmModal .agreeWith').attr('data-action', 'DELETE');
                                    });
                                });
                            }

                        

                            return a[0];
                        },
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
    
    if ($("#invCancelReasonList").length) {
        invCancelReasonList.init();
    }

    function filterinvCancelReasonList() {
        invCancelReasonList.init();
    }

    // On submit filter form
    $("#tabulator-html-filter-form").on("keypress", function (event) {
            let keycode = event.keyCode ? event.keyCode : event.which;
            if (keycode == "13") {
                event.preventDefault();
                filterinvCancelReasonList();
            }
        }
    );

    // On click go button
    $("#tabulator-html-filter-go").on("click", function (event) {
        filterinvCancelReasonList();
    });

    // On reset filter form
    $("#tabulator-html-filter-reset").on("click", function (event) {
        $("#query").val("");
        $("#status").val("1");
        filterinvCancelReasonList();
    }); 


    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addInvCancelReasonModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addInvCancelReasonModal"));
    const editInvCancelReasonModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#editInvCancelReasonModal"));

    document.getElementById('addInvCancelReasonModal').addEventListener('hide.tw.modal', function(event) {
        $('#addInvCancelReasonModal input:not([type="checkbox"])').val('');
        $('#addInvCancelReasonModal .acc__input-error').html('');
    });
    document.getElementById('editInvCancelReasonModal').addEventListener('hide.tw.modal', function(event) {
        $('#editInvCancelReasonModal input:not([type="checkbox"])').val('');
        $('#editInvCancelReasonModal input[name="id"]').val('0');
        $('#editInvCancelReasonModal .acc__input-error').html('');
    });

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

    
    $('.settingsMenu ul li.hasChild > a').on('click', function(e){
        e.preventDefault();
        
        $(this).toggleClass('active text-primary font-medium');
        $(this).siblings('ul').slideToggle();
    });


    $('#addInvCancelReasonForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addInvCancelReasonForm');
    
        document.querySelector('#saveICRBtn').setAttribute('disabled', 'disabled');
        document.querySelector("#saveICRBtn .theLoader").style.cssText ="display: inline-block;";

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('superadmin.site.setting.inv.cancel.reason.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            document.querySelector('#saveICRBtn').removeAttribute('disabled');
            document.querySelector("#saveICRBtn .theLoader").style.cssText = "display: none;";
            
            if (response.status == 200) {
                addInvCancelReasonModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html( "Congratulations!" );
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                });     

                setTimeout(() => {
                    successModal.hide();
                }, 1500);
            }
            invCancelReasonList.init();
        }).catch(error => {
            document.querySelector('#saveICRBtn').removeAttribute('disabled');
            document.querySelector("#saveICRBtn .theLoader").style.cssText = "display: none;";
            if (error.response.status == 422) {
                for (const [key, val] of Object.entries(error.response.data.errors)) {
                    $(`#addInvCancelReasonForm .${key}`).addClass('border-danger');
                    $(`#addInvCancelReasonForm  .error-${key}`).html(val);
                }
            } else if (error.response.status == 304) {
                warningModal.show();
                document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                    $("#warningModal .warningModalTitle").html("Error Found!");
                    $("#warningModal .warningModalDesc").html(error.response.data.msg);
                });

                setTimeout(() => {
                    warningModal.hide();
                }, 1500);
            } else {
                console.log('error');
            }
        });
    });


    $('#editInvCancelReasonForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('editInvCancelReasonForm');
    
        document.querySelector('#editICRBtn').setAttribute('disabled', 'disabled');
        document.querySelector("#editICRBtn .theLoader").style.cssText ="display: inline-block;";

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('superadmin.site.setting.inv.cancel.reason.update'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            document.querySelector('#editICRBtn').removeAttribute('disabled');
            document.querySelector("#editICRBtn .theLoader").style.cssText = "display: none;";
            
            if (response.status == 200) {
                editInvCancelReasonModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html( "Congratulations!" );
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                });     

                setTimeout(() => {
                    successModal.hide();
                }, 1500);
            }
            invCancelReasonList.init();
        }).catch(error => {
            document.querySelector('#editICRBtn').removeAttribute('disabled');
            document.querySelector("#editICRBtn .theLoader").style.cssText = "display: none;";
            if (error.response.status == 422) {
                for (const [key, val] of Object.entries(error.response.data.errors)) {
                    $(`#editInvCancelReasonForm .${key}`).addClass('border-danger');
                    $(`#editInvCancelReasonForm  .error-${key}`).html(val);
                }
            } else if (error.response.status == 304) {
                warningModal.show();
                document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                    $("#warningModal .warningModalTitle").html("Error Found!");
                    $("#warningModal .warningModalDesc").html(error.response.data.msg);
                });

                setTimeout(() => {
                    warningModal.hide();
                }, 1500);
            } else {
                console.log('error');
            }
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETE'){
            axios({
                method: 'delete',
                url: route('superadmin.site.setting.inv.cancel.reason.destroy', row_id),
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
                invCancelReasonList.init();
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RESTORE'){
            axios({
                method: 'post',
                url: route('superadmin.site.setting.inv.cancel.reason.restore', row_id),
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
                invCancelReasonList.init();
            }).catch(error =>{
                console.log(error)
            });
        }
    })
})();