("use strict");
// Tabulator

var adminUserListTable = (function () {
    var _tableGen = function () {        
            let querystr = $("#query").val() != "" ? $("#query").val() : "";
            let status = $("#status").val() != "" ? $("#status").val() : "";
            

            const tabulator = new Tabulator("#adminUserListTable", {
                ajaxURL: route('superadmin.site.setting.user.manager.list'),
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
                        title: 'Name',
                        field: 'name',
                        headerHozAlign: 'left',
                        formatter(cell, formatterParams) { 
                            var html = '<div class="flex justify-start items-center">';
                                    html += '<div class="image-fit zoom-in h-10 w-10 mr-4">';
                                        html += '<img class="rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]" src="'+cell.getData().photo_url+'" alt="'+cell.getData().name+'"/>';
                                    html += '</div>';
                                    html += '<div class="font-medium whitespace-nowrap">'+cell.getData().name+'</div>';
                                html += '</div>';
                            return html;
                        }
                    },
                    {
                        title: 'Email',
                        field: 'email',
                        headerHozAlign: 'left',
                    },
                    {
                        title: 'Mobile',
                        field: 'mobile',
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
                                    const editUserModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#editUserModal"));
                                    editUserModal.show();
                                    document.getElementById('editUserModal').addEventListener('shown.tw.modal', function(event) {
                                        $('#editUserModal [name="name"]').val(cell.getData().name);
                                        $('#editUserModal [name="email"]').val(cell.getData().email);
                                        $('#editUserModal [name="mobile"]').val(cell.getData().mobile);
                                        $('#editUserModal [name="id"]').val(cell.getData().id);
                                        $('#editUserModal #edit_userImageAdd').attr('src', cell.getData().photo_url)
                                        if(cell.getData().status == 1){
                                            $('#editUserModal [name="status"]').prop('checked', true);
                                        }else{
                                            $('#editUserModal [name="status"]').prop('checked', false);
                                        }
                                        $('#editUserModal .acc__input-error').html('');
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
    
    if ($("#adminUserListTable").length) {
        adminUserListTable.init();
    }

    function filteradminUserListTable() {
        adminUserListTable.init();
    }

    // On submit filter form
    $("#tabulator-html-filter-form").on("keypress", function (event) {
            let keycode = event.keyCode ? event.keyCode : event.which;
            if (keycode == "13") {
                event.preventDefault();
                filteradminUserListTable();
            }
        }
    );

    // On click go button
    $("#tabulator-html-filter-go").on("click", function (event) {
        filteradminUserListTable();
    });

    // On reset filter form
    $("#tabulator-html-filter-reset").on("click", function (event) {
        $("#query").val("");
        $("#status").val("1");
        filteradminUserListTable();
    }); 


    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addUserModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addUserModal"));
    const editUserModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#editUserModal"));

    document.getElementById('addUserModal').addEventListener('hide.tw.modal', function(event) {
        $('#addUserModal input:not([type="checkbox"])').val('');
        $('#addUserModal .acc__input-error').html('');

        var placeholder = $('#addUserModal #userImageAdd').attr('data-placeholder');
        $('#addUserModal #userImageAdd').attr('src', placeholder);
    });
    document.getElementById('editUserModal').addEventListener('hide.tw.modal', function(event) {
        $('#editUserModal input:not([type="checkbox"])').val('');
        $('#editUserModal input[name="id"]').val('0');
        $('#editUserModal .acc__input-error').html('');
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

    $('#password').on('input', function() {
        evaluatePasswordStrength(0)
    })

    $('#edit_password').on('input', function() {
        evaluatePasswordStrength(1)
    })

    $('#togglePasswordShow').on('click', function() {
        togglePasswordVisibility('password', 'togglePasswordIcon')
    })
    $('#toggleConfirmPasswordShow').on('click', function() {
        togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmationIcon')
    })

    $('#edit_togglePasswordShow').on('click', function() {
        togglePasswordVisibility('edit_password', 'edit_togglePasswordIcon')
    })
    $('#edit_toggleConfirmPasswordShow').on('click', function() {
        togglePasswordVisibility('edit_password_confirmation', 'edit_togglePasswordConfirmationIcon')
    })

    $('#addUserForm').on('change', '#userPhotoAdd', function(){
        showPreview('userPhotoAdd', 'userImageAdd')
    })

    $('#editUserForm').on('change', '#edit_userPhotoAdd', function(){
        console.log('changed')
        showPreview('edit_userPhotoAdd', 'edit_userImageAdd')
    })


    $('#addUserForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addUserForm');
    
        document.querySelector('#saveUserBtn').setAttribute('disabled', 'disabled');
        document.querySelector("#saveUserBtn .theLoader").style.cssText ="display: inline-block;";

        let form_data = new FormData(form);
        form_data.append('file', $('#addUserForm input[name="photo"]')[0].files[0]); 
        axios({
            method: "post",
            url: route('superadmin.site.setting.user.manager.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            document.querySelector('#saveUserBtn').removeAttribute('disabled');
            document.querySelector("#saveUserBtn .theLoader").style.cssText = "display: none;";
            
            if (response.status == 200) {
                addUserModal.hide();

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
            adminUserListTable.init();
        }).catch(error => {
            document.querySelector('#saveUserBtn').removeAttribute('disabled');
            document.querySelector("#saveUserBtn .theLoader").style.cssText = "display: none;";
            if (error.response.status == 422) {
                for (const [key, val] of Object.entries(error.response.data.errors)) {
                    $(`#addUserForm .${key}`).addClass('border-danger');
                    $(`#addUserForm  .error-${key}`).html(val);
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


    $('#editUserForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('editUserForm');
    
        document.querySelector('#editUserBtn').setAttribute('disabled', 'disabled');
        document.querySelector("#editUserBtn .theLoader").style.cssText ="display: inline-block;";

        let form_data = new FormData(form);
        form_data.append('file', $('#addUserForm input[name="photo"]')[0].files[0]); 
        axios({
            method: "post",
            url: route('superadmin.site.setting.user.manager.update'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            document.querySelector('#editUserBtn').removeAttribute('disabled');
            document.querySelector("#editUserBtn .theLoader").style.cssText = "display: none;";
            
            if (response.status == 200) {
                editUserModal.hide();

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
            adminUserListTable.init();
        }).catch(error => {
            document.querySelector('#editUserBtn').removeAttribute('disabled');
            document.querySelector("#editUserBtn .theLoader").style.cssText = "display: none;";
            if (error.response.status == 422) {
                for (const [key, val] of Object.entries(error.response.data.errors)) {
                    $(`#editUserForm .${key}`).addClass('border-danger');
                    $(`#editUserForm  .error-${key}`).html(val);
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
                url: route('superadmin.site.setting.user.manager.destroy', row_id),
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
                adminUserListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RESTORE'){
            axios({
                method: 'post',
                url: route('superadmin.site.setting.user.manager.restore', row_id),
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
                adminUserListTable.init();
            }).catch(error =>{
                console.log(error)
            });
        }
    })

    function showPreview(inputId, targetImageId) {
        var src = document.getElementById(inputId);
        var target = document.getElementById(targetImageId);
        var title = document.getElementById('selected_image_title');
        var fr = new FileReader();
        fr.onload = function () {
            target.src = fr.result;
        }
        fr.readAsDataURL(src.files[0]);
    };

    function evaluatePasswordStrength(edit = 0) {
        let password, strenghts;
        let box1, box2, box3, box4;

        if(edit == 1){
            password = document.getElementById('edit_password').value;
            strenghts = checkPasswordStrength(password);

            box1 = document.getElementById('edit_strength-1');
            box2 = document.getElementById('edit_strength-2');
            box3 = document.getElementById('edit_strength-3');
            box4 = document.getElementById('edit_strength-4');
        } else {
            password = document.getElementById('password').value;
            strenghts = checkPasswordStrength(password);

            box1 = document.getElementById('strength-1');
            box2 = document.getElementById('strength-2');
            box3 = document.getElementById('strength-3');
            box4 = document.getElementById('strength-4');
        }

        switch (strenghts) {
                case 1:
                        box1.classList.remove('border-slate-400/20', 'bg-slate-400/30')
                        box1.classList.add('bg-danger', 'border-danger');
                        break;
                case 2: 
                        box2.classList.remove('border-slate-400/20', 'bg-slate-400/30')
                        box2.classList.add('bg-warning', 'border-warning');
                        break;
                case 3: 
                        box3.classList.remove('border-slate-400/20', 'bg-slate-400/30')
                        box3.classList.add('bg-pending', 'border-pending');
                        break;
                case 4: 
                case 5: 
                case 6: 
                case 7: 
                case 8: 
                case 9: 
                        box4.classList.remove('border-slate-400/20', 'bg-slate-400/30')
                        box4.classList.add('bg-success', 'border-success');
                        break;
                default:
                        box1.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
                        box2.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
                        box3.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
                        box4.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
                        
                        box1.classList.add('border-slate-400/20', 'bg-slate-400/30');
                        box2.classList.add('border-slate-400/20', 'bg-slate-400/30');
                        box3.classList.add('border-slate-400/20', 'bg-slate-400/30');
                        box4.classList.add('border-slate-400/20', 'bg-slate-400/30');
                        break;
        }
    }

    function checkPasswordStrength(password) {
        // Initialize variables
        let strength = 0;
        let tips = "";

        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
            strength += 1;
        } else {}

        //If it has numbers and characters
        if (password.match(/([0-9])/)) {
            strength += 1;
        } else {}

        //If it has one special character
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
            strength += 1;
        } else {}

        //If password is greater than 7
        if (password.length > 7) {
            strength += 1;
        } else {}
        
        // Return results
        if (strength < 2) {
            return strength;
        } else if (strength === 2) {
            return strength;
        } else if (strength === 3) {
            return strength;
        } else {
            return strength;
        }
    }

    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.setAttribute('data-lucide', 'eye');
        } else {
            passwordInput.type = 'password';
            icon.setAttribute('data-lucide', 'eye-off');
        }
        //lucide.createIcons();

        createIcons({
            icons,
            attrs: {
                "stroke-width": 1.5,
            },
            nameAttr: "data-lucide",
        });
    }

})();