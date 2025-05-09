
(function(){
    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const updateUserDataModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updateUserDataModal"));
    const updatePasswordModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatePasswordModal"));
    const updateSignatureModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updateSignatureModal"));
    
    
    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
    });
    
    document.getElementById('updateUserDataModal').addEventListener('hide.tw.modal', function(event) {
        $('#updateUserDataModal .acc__input-error').html('');
        $('#updateUserDataModal .fieldTitle').text('Value');
        $('#updateUserDataModal .requiredLabel').addClass('hidden');
        $('#updateUserDataModal input[name="fieldValue"]').val('');
        $('#updateUserDataModal input[name="fieldName"]').val('');
    });
    
    document.getElementById('updatePasswordModal').addEventListener('hide.tw.modal', function(event) {
        $('#updatePasswordModal .acc__input-error').html('');
        $('#updatePasswordModal input[name="password"]').val('');
        $('#updatePasswordModal input[name="password_confirmation"]').val('');

        let box1 = document.getElementById('strength-1');
        let box2 = document.getElementById('strength-2');
        let box3 = document.getElementById('strength-3');
        let box4 = document.getElementById('strength-4');

        box1.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
        box2.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
        box3.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
        box4.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
        
        box1.classList.add('border-slate-400/20', 'bg-slate-400/30');
        box2.classList.add('border-slate-400/20', 'bg-slate-400/30');
        box3.classList.add('border-slate-400/20', 'bg-slate-400/30');
        box4.classList.add('border-slate-400/20', 'bg-slate-400/30');
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

    $(document).on('click', '.fieldValueToggler', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let theTitle = $theBtn.attr('data-title');
        let theField = $theBtn.attr('data-field');
        let theValue = $theBtn.attr('data-value');
        let theRequired = $theBtn.attr('data-required');
        let theType = $theBtn.attr('data-type');

        updateUserDataModal.show();
        document.getElementById('updateUserDataModal').addEventListener('shown.tw.modal', function(event){
            $('#updateUserDataModal .fieldTitle').text(theTitle);
            if(theRequired == 1){
                $('#updateUserDataModal .requiredLabel').removeClass('hidden');
                $('#updateUserDataModal input[name="fieldValue"]').val(theValue).addClass('require');
            }else{
                $('#updateUserDataModal .requiredLabel').addClass('hidden');
                $('#updateUserDataModal input[name="fieldValue"]').val(theValue).removeClass('require');
            }
            if(theType == 'email'){
                $('#updateUserDataModal input[name="fieldValue"]').attr('type', 'email');
            }else{
                $('#updateUserDataModal input[name="fieldValue"]').attr('type', 'text');
            }
            $('#updateUserDataModal input[name="fieldName"]').val(theField);
        });
    })

    $('#updateUserDataForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updateUserDataForm');
        const $theForm = $(this);
        
        $('#updateUserDataModal .acc__input-error').html('');
        $('#updateDataBtn', $theForm).attr('disabled', 'disabled');
        $("#updateDataBtn .theLoader").fadeIn();

        let errors = 0;
        $theForm.find('.require').each(function(){
            if($(this).val() == ''){
                errors += 1;
                $(this).siblings('.acc__input-error').html('This field is required.')
            }
        });

        if(errors > 0){
            $('#updateDataBtn', $theForm).removeAttr('disabled');
            $("#updateDataBtn .theLoader").fadeOut();

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('profile.update.data'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#updateDataBtn', $theForm).removeAttr('disabled');
                $("#updateDataBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    updateUserDataModal.hide();
                    window.location.reload();
                }
            }).catch(error => {
                $('#updateDataBtn', $theForm).removeAttr('disabled');
                $("#updateDataBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updateUserDataForm .${key}`).addClass('border-danger');
                            $(`#updateUserDataForm  .error-${key}`).html(val);
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
                }
            });
        }
    })


    $('#updatePasswordModal #password').on('input', function() {
        evaluatePasswordStrength()
    })

    $('#updatePasswordForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('updatePasswordForm');
        const $theForm = $(this);
        
        $('#updatePasswordModal .acc__input-error').html('');
        $('#updatePassBtn', $theForm).attr('disabled', 'disabled');
        $("#updatePassBtn .theLoader").fadeIn();

        let errors = 0;
        $theForm.find('.require').each(function(){
            if($(this).val() == ''){
                errors += 1;
                $(this).siblings('.acc__input-error').html('This field is required.')
            }
        });

        if(errors > 0){
            $('#updatePassBtn', $theForm).removeAttr('disabled');
            $("#updatePassBtn .theLoader").fadeOut();

            return false;
        }else{
            let form_data = new FormData(form);
            axios({
                method: "POST",
                url: route('profile.update.password'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#updatePassBtn', $theForm).removeAttr('disabled');
                $("#updatePassBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    updatePasswordModal.hide();
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
            }).catch(error => {
                $('#updatePassBtn', $theForm).removeAttr('disabled');
                $("#updatePassBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#updatePasswordForm .${key}`).addClass('border-danger');
                            $(`#updatePasswordForm  .error-${key}`).html(val);
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
                }
            });
        }
    })


    function evaluatePasswordStrength() {
        const password = document.getElementById('password').value;
        let strenghts = checkPasswordStrength(password);

        const box1 = document.getElementById('strength-1');
        const box2 = document.getElementById('strength-2');
        const box3 = document.getElementById('strength-3');
        const box4 = document.getElementById('strength-4');

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

    $("#addSignUserForm").submit(function(e) {
        e.preventDefault();
        
        const loadingElement = document.createElement('div');
        loadingElement.classList.add('loading-icon');
        loadingElement.setAttribute('data-lucide', 'loader');
        loadingElement.style.display = 'inline-block';
        loadingElement.style.marginLeft = '10px';
        let $theForm = $(this);
        $(".sign-pad-button-submit").append(loadingElement);

        createIcons({
                icons,
                attrs: { "stroke-width": 1.5 },
                nameAttr: "data-lucide",
            });
            let formData = new FormData(this);
        axios({
            method: "post",
            url: route('profile.draw-signature'),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $(".sign-pad-button-submit .loading-icon").remove();

            if (response.status == 201) {
                updateSignatureModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.reload();
                }, 1500);
            }
        }).catch(error => {
            $(".sign-pad-button-submit .loading-icon").remove();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#profileUpdateForm .${key}`).addClass('border-danger');
                        $(`#profileUpdateForm  .error-${key}`).html(val);
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
            }
        });

    }); 

    document.querySelector('#example-2-tab').addEventListener('shown.tw.tab', function() {
        const dropzoneElement = document.querySelector('.dropzone');
        if (dropzoneElement && !dropzoneElement.dropzone) {
            new Dropzone(dropzoneElement, {
                url: "/file-upload",
                maxFiles: 1,
                acceptedFiles: 'image/*', // Only accept image files
                init: function() {
                    this.on("success", function(file, response) {
                        console.log("File uploaded successfully");
                    });
                    this.on("error", function(file, response) {
                        console.log("File upload error");
                    });
                }
            });
        }
    });


    $('#fileUploadForm').submit(function(e) {
        e.preventDefault();
        const loadingElement = document.createElement('div');
        loadingElement.classList.add('loading-icon');
        loadingElement.setAttribute('data-lucide', 'loader');
        loadingElement.style.display = 'inline-block';
        loadingElement.style.marginLeft = '10px';
        let $theForm = $(this);
        $("#userFileSaveBtn").append(loadingElement);
        $('#userFileSaveBtn', $theForm).attr('disabled', 'disabled');

        createIcons({
                icons,
                attrs: { "stroke-width": 1.5 },
                nameAttr: "data-lucide",
        });

        let formData = new FormData(this);
        axios({
            method: "post",
            url: route('profile.upload-signature'),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 201) {
                updateSignatureModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.reload();
                }, 1500);
            }
        }).catch(error => {
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#profileUpdateForm .${key}`).addClass('border-danger');
                        $(`#profileUpdateForm  .error-${key}`).html(val);
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
            }
        });

    });

})()