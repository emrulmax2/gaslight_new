("use strict");
var customerJobDocListTable = (function () {
    var _tableGen = function () {
        
        let querystr = $("#query").val() != "" ? $("#query").val() : "";
        let status = $("#status").val() != "" ? $("#status").val() : "";
        let customer_id = ($('#customerJobDocListTable').attr('data-customerid') ? $('#customerJobDocListTable').attr('data-customerid') : 0);
        let job_id = ($('#customerJobDocListTable').attr('data-jobid') ? $('#customerJobDocListTable').attr('data-jobid') : 0);

        let tableContent = new Tabulator("#customerJobDocListTable", {
            ajaxURL: route("customers.jobs.document.list", [customer_id, job_id]),
            ajaxParams: { querystr: querystr, status: status, customer_id : customer_id, job_id : job_id },
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
                },
                {
                    title: 'File Name',
                    field: 'display_file_name',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                },
                {
                    title: 'Uploaded At',
                    field: 'created_at',
                    headerHozAlign: "left",
                    vertAlign: 'middle',
                },
                {
                    title: "Actions",
                    field: "id",
                    headerSort: false,
                    hozAlign: "center",
                    headerHozAlign: "center",
                    width: "120",
                    download:false,
                    vertAlign: 'middle',
                    formatter(cell, formatterParams) {                        
                        var btns = "";
                        if(cell.getData().download_url != null){
                            btns += '<a href="'+cell.getData().download_url+'" target="_blank" class="rounded-full bg-success text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="download-cloud" class="w-4 h-4"></i></a>';
                        }
                        if (cell.getData().deleted_at == null) {
                            btns += '<button data-customer="'+cell.getData().customer_id+'" data-job="'+cell.getData().customer_job_id+'" data-id="' +cell.getData().id +'" class="delete_btn rounded-full bg-danger text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="Trash2" class="w-4 h-4"></i></button>';
                        }  else {
                            btns += '<button data-customer="'+cell.getData().customer_id+'" data-job="'+cell.getData().customer_job_id+'" data-id="' +cell.getData().id +'"  class="restore_btn rounded-full bg-success text-white p-0 w-[36px] h-[36px] inline-flex justify-center items-center ml-1"><i data-lucide="rotate-cw" class="w-4 h-4"></i></button>';
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

        // Export
        $("#tabulator-export-csv").on("click", function (event) {
            tableContent.download("csv", "data.csv");
        });

        $("#tabulator-export-json").on("click", function (event) {
            tableContent.download("json", "data.json");
        });

        $("#tabulator-export-xlsx").on("click", function (event) {
            window.XLSX = xlsx;
            tableContent.download("xlsx", "data.xlsx", {
                sheetName: "Title Details",
            });
        });

        $("#tabulator-export-html").on("click", function (event) {
            tableContent.download("html", "data.html", {
                style: true,
            });
        });

        // Print
        $("#tabulator-print").on("click", function (event) {
            tableContent.print();
        });
    };
    return {
        init: function () {
            _tableGen();
        },
    };
})();

(function(){
    "use strict";

    if ($("#customerJobDocListTable").length) {
        customerJobDocListTable.init();

        function filtercustomerJobDocListTable() {
            customerJobDocListTable.init();
        }

        // On submit filter form
        $("#tabulator-html-filter-form").on("keypress", function (event) {
                let keycode = event.keyCode ? event.keyCode : event.which;
                if (keycode == "13") {
                    event.preventDefault();
                    filtercustomerJobDocListTable();
                }
            }
        );

        // On click go button
        $("#tabulator-html-filter-go").on("click", function (event) {
            filtercustomerJobDocListTable();
        });

        // On reset filter form
        $("#tabulator-html-filter-reset").on("click", function (event) {
            $("#query").val("");
            $("#status").val("1");
            filtercustomerJobDocListTable();
        });
    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const jobUploadDocModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#jobUploadDocModal"));

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

    if($('#jobUploadDocForm').length > 0){
        Dropzone.autoDiscover = false;
        let jobDropZone = new Dropzone("#jobUploadDocForm", {
            autoProcessQueue: false,
            maxFiles: 5,
            maxFilesize: 20,
            parallelUploads: 5,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.xl,.xls,.xlsx,.doc,.docx,.ppt,.pptx,.txt",
            addRemoveLinks: true,
            accept: function(file, done) {
                if(file.name.match(/[`!@#$%^&*+\=\[\]{};':"\\|,<>\/?~]/)){
                    done("Oops! Your selected file name contain invalid character. Please rename and upload again.");
                }
                else { done(); }
            },
            success: function (file, response) {
                console.log('Success')
            },
            complete: function (file) {
                console.log('Complete')
                
            },
            error: function (file, response) {
                console.error(response);
                let errorMessage = typeof response === "string" ? response : response.message;

                $('#jobUploadDocModal .modal-body .uploadError').remove();
                $('#jobUploadDocModal .modal-body').prepend('<div role="alert" class="uploadError alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-5 flex items-center"><i data-tw-merge data-lucide="alert-octagon" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>'+errorMessage+'</div>');
                createIcons({icons, attrs: { "stroke-width": 1.5 }, nameAttr: "data-lucide"});

                jobDropZone.removeFile(file);
                setTimeout(function(){
                    $('#jobUploadDocModal .modal-body .uploadError').remove();
                }, 3000)

                console.log(errorMessage)
            },
            queuecomplete: function(){
                $('#uploadJobDocumentsBtn').removeAttr('disabled');
                $('#uploadJobDocumentsBtn').find('.theLoader').fadeOut();

                jobUploadDocModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html('Job files successfully uploaded.');
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.reload();
                }, 1500);
            },
        });

        $('#uploadJobDocumentsBtn').on('click', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            $theBtn.attr('disabled', 'disabled');
            $theBtn.find('.theLoader').fadeIn();
            
            if(jobDropZone.files.length > 0){
                jobDropZone.processQueue();
            }else{
                $theBtn.removeAttr('disabled');
                $theBtn.find('.theLoader').fadeOut();

                $('#jobUploadDocModal .modal-body .uploadError').remove();
                $('#jobUploadDocModal .modal-body').prepend('<div role="alert" class="uploadError alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-5 flex items-center"><i data-tw-merge data-lucide="alert-octagon" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>Oops! Please select at least one file.</div>');
                createIcons({icons, attrs: { "stroke-width": 1.5 }, nameAttr: "data-lucide"});

                setTimeout(function(){
                    $('#jobUploadDocModal .modal-body .uploadError').remove();
                }, 3000)
            }
        });
    }

    // Delete Trigger
    $('#customerJobDocListTable').on('click', '.delete_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete these record? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETEJDC');
        });
    });

    // Restore Trigger
    $('#customerJobDocListTable').on('click', '.restore_btn', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to restore these record? Click on agree to continue.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'RESTOREJDC');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETEJDC'){
            let customer_id = ($('#customerJobDocListTable').attr('data-customerid') ? $('#customerJobDocListTable').attr('data-customerid') : 0);
            let job_id = ($('#customerJobDocListTable').attr('data-jobid') ? $('#customerJobDocListTable').attr('data-jobid') : 0);
            axios({
                method: 'delete',
                url: route('customers.jobs.document.destroy', [customer_id, job_id, row_id]),
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
                customerJobDocListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RESTOREJDC'){
            let customer_id = ($('#customerJobDocListTable').attr('data-customerid') ? $('#customerJobDocListTable').attr('data-customerid') : 0);
            let job_id = ($('#customerJobDocListTable').attr('data-jobid') ? $('#customerJobDocListTable').attr('data-jobid') : 0);
            axios({
                method: 'post',
                url: route('customers.jobs.document.restore', [customer_id, job_id, row_id]),
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
                customerJobDocListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }
    })

})();