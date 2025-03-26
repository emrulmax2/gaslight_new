@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center">
        <h2 class="mr-auto text-lg font-medium">Update Profile</h2>
    </div>
    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-12 ">
            <!-- BEGIN: Personal Information -->
            <div class="intro-y box mt-5">
                <div class="flex items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium">
                        Personal Information
                    </h2>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <form id="profileUpdateForm" method="POST" enctype="multipart/form-data" class="mb-4">
                            <div class="mt-3">
                                <x-base.form-label for="name">Name <span class="text-danger">*</span></x-base.form-label>
                                <x-base.form-input name="name" id="name" value="{{ $user->name }}" class="w-full" type="text" placeholder="John Doe" />
                                <span class="mt-2 text-danger error-name"></span>
                            </div>
                            <div class="mt-3">
                                  <x-base.form-label for="email">Email <span class="text-danger">*</span></x-base.form-label>
                                <x-base.form-input name="email" id="email" value="{{ $user->email }}" class="w-full" type="text" placeholder="yourname@example.com" />
                                <span class="mt-2 text-danger error-email"></span>
                            </div>
                            <div class="mt-3">
                                <x-base.form-label for="password">Password</x-base.form-label>
                                <x-base.form-input name="password" id="password" class="w-full" type="password" placeholder="*********" />
                                <div id="password-strength" class="mt-3.5 grid h-1.5 w-full grid-cols-12 gap-4 password-strength">
                                    <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                    <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                    <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                    <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                </div>
                                <span class="mt-2 text-danger error-password"></span>
                            </div>
                            <div class="mt-3">
                                <x-base.form-label for="password_confirmation">Password Confirmation</x-base.form-label>
                                <x-base.form-input name="password_confirmation" id="password_confirmation" class="w-full" type="password" placeholder="*********" />
                                <span class="mt-2 text-danger error-password_confirmation"></span>
                            </div>
                            <div class="grid grid-cols-12 gap-6">
                                <!-- BEGIN: Profile Menu -->
                                <div class="intro-y col-span-12 md:col-span-4">
                                    <div class="intro-y mt-5">
                                        <div class="mt-3">
                                            <x-base.form-label for="gas_safe_id_card">Gas Safe Id Card</x-base.form-label>
                                            <x-base.form-input name="gas_safe_id_card" id="gas_safe_id_card" value="{{ $user->gas_safe_id_card }}" class="w-full" type="text" placeholder="" />
                                            <span class="mt-2 text-danger error-gas_safe_id_card"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="intro-y col-span-12 md:col-span-4">
                                    <div class="intro-y mt-5">
                                        <div class="mt-3">
                                            <x-base.form-label for="oil_registration_number">Oil Registration Number</x-base.form-label>
                                            <x-base.form-input name="oil_registration_number" id="oil_registration_number" value="{{ $user->oil_registration_number }}" class="w-full" type="text" placeholder="" />
                                            <span class="mt-2 text-danger error-oil_registration_number"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="intro-y col-span-12 md:col-span-4">
                                    <div class="intro-y mt-5">
                                        <div class="mt-3">
                                            <x-base.form-label for="installer_ref_no">Installer Ref No</x-base.form-label>
                                            <x-base.form-input name="installer_ref_no" id="installer_ref_no" value="{{ $user->installer_ref_no }}" class="w-full" type="text" placeholder="" />
                                            <span class="mt-2 text-danger error-installer_ref_no"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-3">
                                <label data-tw-merge for="signature" class="inline-block mt-2 mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Signature
                                </label>
                                <img id="signature" src="{{ $user->signature ? Storage::disk('public')->url($user->signature->filename) : Vite::asset('resources/images/placeholders/signature-placeholder.jpg') }}" alt="signature" class="w-40 h-20" />
                            </div>
                            <div class="mt-3">
                                <x-base.button class="w-auto border-0 rounded-0" id="userUpdateBtn" type="submit" variant="primary">
                                    Update
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="intro-y box mt-5">
                <div class="px-5 pt-5 pb-1 sm:pb-10 flex items-left">
                    <x-base.lucide class=" h-6 w-6 text-primary" icon="signature" />
                    <div id="titleModal" class="text-xl ml-2">Update Signature</div>
                </div>
                <div id="base-start" class=" flex flex-col px-2 sm:px-16 pt-5 pb-16">
                    <x-base.tab.group>
                        <x-base.tab.list variant="tabs">
                            <x-base.tab id="example-1-tab" selected>
                                <x-base.tab.button class="w-full py-2" as="button" type="button">
                                    Draw Signature
                                </x-base.tab.button>
                            </x-base.tab>
                            <x-base.tab id="example-2-tab" >
                                <x-base.tab.button class="w-full py-2" as="button" type="button">
                                    Upload
                                </x-base.tab.button>
                            </x-base.tab>
                        </x-base.tab.list>
                        <x-base.tab.panels class="border-b border-l border-r">
                            <x-base.tab.panel class="p-5 leading-relaxed" id="example-1" selected>
                                <form id="addSignUserForm" enctype="multipart/form-data" class="bg-slate-100 rounded-lg">
                                    <x-creagia-signature-pad name='sign'
                                        border-color="#ccc"
                                        submit-name="Save"
                                        clear-name="Clear"
                                        submit-id="signSaveBtn"
                                        clear-id="clear"
                                        pad-classes="w-auto h-48 bg-white mt-10"
                                    />
                                </form>
                            </x-base.tab.panel>
                            <x-base.tab.panel class="p-5 leading-relaxed" id="example-2" >
                                <form id="myDropzone" action="/file-upload" class="dropzone [&.dropzone]:border-2 [&.dropzone]:border-dashed dropzone [&.dropzone]:border-slate-300/70 [&.dropzone]:bg-slate-50 [&.dropzone]:cursor-pointer [&.dropzone]:dark:bg-darkmode-600 [&.dropzone]:dark:border-white/5 dz-clickable" id="my-dropzone">
                                    @csrf
                                    <div class="fallback">
                                        <input name="file" type="file" />
                                    </div>
                                    <div class="dz-message">
                                        <div class="text-lg font-medium">
                                            Drop files here or click to upload.
                                        </div>
                                        <div class="text-gray-600">
                                            This is signature file upload. Selected files should
                                            not over <span class="font-medium">2MB</span> and should be image file.
                                        </div>
                                    </div>
                                </form>
                                <div id="uploaded-view" class="border-dashed pt-5 mt-5 border-slate-300/60 rounded border-2 px-3 hidden"></div>
                                <div class="mt-3 text-center">
                                    <form id="fileUploadForm" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <input type="hidden" name="file_id" value="" id="file_id">
                                        <input type="hidden" name="file_path" value="" id="file_path">
                                        <x-base.button class="w-auto border-0 rounded-0" id="userFileSaveBtn" type="submit" variant="primary">
                                            Save
                                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                        </x-base.button>
                                    </form>
                                </div>
                            </x-base.tab.panel>
                        </x-base.tab.panels>
                    </x-base.tab.group> 
                </div>
                </div>
            </div>
            <!-- END: Personal Information -->
        </div>
    </div>
    @include('app.user.modal')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/custom/signature.css')
    @vite('resources/css/vendors/dropzone.css')
@endPushOnce


@pushOnce('vendors')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/dropzone.js')
    @vite('resources/js/app/staffs/dropzone.js')
@endPushOnce

@pushOnce('scripts')
    <script type="module">
        const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
        const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
        function evaluateUpdatedPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBars = document.querySelectorAll('#password-strength > div');
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthBars.forEach((bar, index) => {
                if (index < strength) {
                    bar.classList.add('bg-theme-1/30');
                    bar.classList.remove('bg-slate-400/30');
                } else {
                    bar.classList.add('bg-slate-400/30');
                    bar.classList.remove('bg-theme-1/30');
                }
            });
        }
        $('#password').on('input', function() {
            evaluateUpdatedPasswordStrength()
        })


        $('#profileUpdateForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('profileUpdateForm');

        let $theForm = $(this);
        
        $('#userUpdateBtn', $theForm).attr('disabled');
        $("#userUpdateBtn .theLoader").fadeIn();

        let formData = new FormData(form);
    
        let $id = $('#id').val();
        axios({
            method: "post",
            url: route('profile.update'),
            data: formData,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#userUpdateBtn', $theForm).removeAttr('disabled');
            $("#userUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    location.reload();
                }, 1500);
            }
        }).catch(error => {
            $('#userUpdateBtn', $theForm).removeAttr('disabled');
            $("#userUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#profileUpdateForm .${key}`).addClass('border-danger');
                        $(`#profileUpdateForm  .error-${key}`).html(val);
                    }
                } else if (error.response.status == 304) {
                    warningModal.show();
                    document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                        $("#warningModal .warningModalTitle").html("No Change Found!");
                        $("#warningModal .warningModalDesc").html(error.response.data.message);
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

    // Initialize Dropzone when the second tab is shown
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
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();

            if (response.status == 201) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    location.reload();
                }, 1500);
            }
        }).catch(error => {
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();
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
                $('#userSaveBtn', $theForm).removeAttr('disabled');
                $("#userSaveBtn .theLoader").fadeOut();
                $(".sign-pad-button-submit .loading-icon").remove();

                if (response.status == 201) {
                    successModal.show();
                    document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                        $("#successModal .successModalTitle").html("Congratulations!");
                        $("#successModal .successModalDesc").html(response.data.msg);
                        $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                    });

                    setTimeout(() => {
                        successModal.hide();
                        location.reload();
                    }, 1500);
                }
            }).catch(error => {
                $('#userSaveBtn', $theForm).removeAttr('disabled');
                $("#userSaveBtn .theLoader").fadeOut();
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
    </script>
@endPushOnce