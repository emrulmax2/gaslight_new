<!-- BEGIN: Modal Content -->
<x-base.dialog id="addnew-modal"
size="xl"
staticBackdrop
>
    <x-base.dialog.panel>
        <a
            class="absolute right-0 top-0 mr-3 mt-3"
            data-tw-dismiss="modal"
            href="#"
        >
            <x-base.lucide
                class="h-8 w-8 text-slate-400"
                icon="X"
            />
        </a>
        {{-- <div class="absolute left-0 top-0 ml-3 mt-3  bg-primary text-white text-sm font-semibold px-2 py-1 rounded">
            Step <span class="current-step"> 01 </span> of <span class="total-step">02</span>
        </div> --}}
        <div class="px-16 pt-16 pb-5 flex items-left">
            <x-base.lucide
                class=" h-8 w-8 text-primary"
                icon="UserPlus"
            />
            <div id="titleModal" class="text-2xl ml-2">Create User</div>
        </div>
        <div id="base-start" class=" flex flex-col px-16 pt-5 pb-16">
            <div>
                <div class="mb-4">
                    <form id="customForm" enctype="multipart/form-data" class="mb-4">
                        <div class="mt-3">
                            <label data-tw-merge for="email" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input data-tw-merge id="email" type="text" placeholder="dane@codejam.com" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger"></div>
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="name" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input data-tw-merge id="name" type="text" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger"></div>
                        </div>

                        
                        <div>
                            <label data-tw-merge for="input-state-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Password
                            </label>
                            <input id="password" data-tw-merge id="input-state-1"  type="password" name="password" placeholder="*********" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 border-success border-success" />
                            <div id="password-strength" class="mt-3.5 grid h-1.5 w-full grid-cols-12 gap-4">
                                <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            </div>
                            <div class="mt-2 text-danger"></div>
                            <div id="error-password" class="register__input-error text-danger mt-2 dark:text-orange-400"></div>
                        
                        </div>
                        <div class="mt-3">
                            <label data-tw-merge for="password_confirmation" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Pasword Confirm <span class="text-danger">*</span>
                            </label>
                            <input data-tw-merge id="password_confirmation" name="password_confirmation" type="password" placeholder="*********" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                            <div class="mt-2 text-danger"></div>
                        </div>
                        <div class="grid grid-cols-12 gap-6">
                            <!-- BEGIN: Profile Menu -->
                            <div class="intro-y col-span-12 md:col-span-4">
                                <div class="intro-y mt-5">
                                    <div class="mt-3">
                                        <label data-tw-merge for="gas_safe_id_card" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Gas Safe Id Card <span class="text-danger">*</span>
                                        </label>
                                        <input data-tw-merge id="gas_safe_id_card" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                        <div class="mt-2 text-danger"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="intro-y col-span-12 md:col-span-4">
                                <div class="intro-y mt-5">
                                    <div class="mt-3">
                                        <label data-tw-merge for="oil_registration_number" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Oil Registration Number <span class="text-danger">*</span>
                                        </label>
                                        <input data-tw-merge id="oil_registration_number" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                        <div class="mt-2 text-danger"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="intro-y col-span-12 md:col-span-4">
                                <div class="intro-y mt-5">
                                    <div class="mt-3">
                                        <label data-tw-merge for="installer_ref_no" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Installer Ref No <span class="text-danger">*</span>
                                        </label>
                                        <input data-tw-merge id="installer_ref_no" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                        <div class="mt-2 text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <x-base.tab.group>
                        <x-base.tab.list variant="tabs">
                            <x-base.tab
                                id="example-1-tab"
                                selected
                            >
                                <x-base.tab.button
                                    class="w-full py-2"
                                    as="button"
                                >
                                    Signature
                                </x-base.tab.button>
                            </x-base.tab>
                            <x-base.tab id="example-2-tab">
                                <x-base.tab.button
                                    class="w-full py-2"
                                    as="button"
                                >
                                    Upload
                                </x-base.tab.button>
                            </x-base.tab>
                        </x-base.tab.list>
                        <x-base.tab.panels class="border-b border-l border-r">
                            <x-base.tab.panel
                                class="p-5 leading-relaxed"
                                id="example-1"
                                selected
                            >
                            <form id="addStaffForm" enctype="multipart/form-data">
                                <input type="hidden" name="name" value="">
                                <input type="hidden" name="email" value="">
                                <input type="hidden" name="password" value="">
                                <input type="hidden" name="password_confirmation" value="">
                                <input type="hidden" name="gas_safe_id_card" value="">
                                <input type="hidden" name="oil_registration_number" value="">
                                <input type="hidden" name="file_upload_id" value="">
                                <input type="hidden" name="installer_ref_no" value="">
                                    
                                    <x-creagia-signature-pad name='sign' 
                                    border-color="#fff"
                                    submit-name="Save"
                                    clear-name="Clear"
                                    submit-id="signSaveBtn"
                                    clear-id="clear"
                                    pad-classes="w-full h-48"
                                />
                            </form>
                            </x-base.tab.panel>
                            <x-base.tab.panel
                                class="p-5 leading-relaxed"
                                id="example-2"
                            >
                            <x-base.dropzone
                                class="dropzone"
                                data-single="true"
                                action="/file-upload"
                            >
                                <div class="text-lg font-medium">
                                    Drop files here or click to upload.
                                </div>
                                <div class="text-gray-600">
                                    This is just a demo dropzone. Selected files are
                                    <span class="font-medium">not</span> actually
                                    uploaded.
                                </div>
                            </x-base.dropzone>
                                <div class="mt-3 text-center">
                                    <x-base.button class="w-auto border-0 rounded-0" id="userSaveBtn" type="submit" variant="primary">
                                        Save
                                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                    </x-base.button>
                                </div>
                            </x-base.tab.panel>
                        </x-base.tab.panels>
                    </x-base.tab.group>

            
                </div>
                            
            </div>
        </div>

    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->

@pushOnce('scripts')
    <script type="module">

function evaluatePasswordStrength() {
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
                evaluatePasswordStrength()
        })

        $('#addStaffForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addStaffForm');
        const $theForm = $(this);
        
        $('#userSaveBtn', $theForm).attr('disabled', 'disabled');
        $("#userSaveBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('staff.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();

            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.href = response.data.red;
                }, 1500);
            }
        }).catch(error => {
            $('#userSaveBtn', $theForm).removeAttr('disabled');
            $("#userSaveBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#customerCreateForm .${key}`).addClass('border-danger');
                        $(`#customerCreateForm  .error-${key}`).html(val);
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
    })
    </script>
@endPushOnce