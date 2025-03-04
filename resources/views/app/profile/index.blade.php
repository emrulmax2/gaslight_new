@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Update Profile - Midone - Tailwind Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center">
        <h2 class="mr-auto text-lg font-medium">Update Profile</h2>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 flex flex-col-reverse lg:col-span-4 lg:block 2xl:col-span-3">
            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5">
                    <div class="image-fit h-12 w-12">
                        <img
                            class="rounded-full"
                            src="{{ Vite::asset($fakers[0]['photos'][0]) }}"
                            alt="Midone - Tailwind Admin Dashboard Template"
                        />
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="text-base font-medium">
                            {{ $user->name }}
                        </div>
                        <div class="text-slate-500">{{ $user->role }}</div>
                    </div>
                </div>
                <div class="border-t border-slate-200/60 p-5 dark:border-darkmode-400">
                    <a
                        class="flex items-center font-medium text-primary"
                        href=""
                    >
                        <x-base.lucide
                            class="mr-2 h-4 w-4"
                            icon="Activity"
                        /> Personal
                        Information
                    </a>
                    {{-- <a
                        class="mt-5 flex items-center"
                        href=""
                    >
                        <x-base.lucide
                            class="mr-2 h-4 w-4"
                            icon="Box"
                        /> Account Settings
                    </a>
                    <a
                        class="mt-5 flex items-center"
                        href=""
                    >
                        <x-base.lucide
                            class="mr-2 h-4 w-4"
                            icon="Lock"
                        /> Change Password
                    </a>
                    <a
                        class="mt-5 flex items-center"
                        href=""
                    >
                        <x-base.lucide
                            class="mr-2 h-4 w-4"
                            icon="Settings"
                        /> User
                        Settings
                    </a> --}}
                </div>
            </div>
        </div>
        <!-- END: Profile Menu -->
        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
            <!-- BEGIN: Personal Information -->
            <div class="intro-y box mt-5">
                <div class="flex items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium">
                        Personal Information
                    </h2>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <form id="customerUpdateForm" method="POST" enctype="multipart/form-data" class="mb-4">
                            
                            <input type="hidden" name="id" value="{{ auth()->user()->id }}" id="id">
                            <div class="mt-3">
                                <label data-tw-merge  for="email1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input data-tw-merge id="email1" value="{{ $user->email }}" type="text" name="email" placeholder="dane@codejam.com" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                <div class="mt-2 text-danger error-email"></div>
                            </div>
                            <div class="mt-3">
                                <label data-tw-merge for="name1"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input data-tw-merge id="name1" value="{{ $user->name }}" type="text" name="name" placeholder="Dane Jam" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                <div class="mt-2 text-danger error-name"></div>
                            </div>
                            <div>
                                <label data-tw-merge for="password1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Password
                                </label>
                                <input id="password1" data-tw-merge   type="password" name="password" placeholder="*********" class="password disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 border-success border-success" />
                                <div id="password-strength1" class="mt-3.5 grid h-1.5 w-full grid-cols-12 gap-4 password-strength">
                                    <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                    <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                    <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                    <div class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                </div>
                                <div class="mt-2 text-danger error-password"></div>
                               
                            </div>
                            <div class="mt-3">
                                <label data-tw-merge for="password_confirmation1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Pasword Confirm <span class="text-danger ">*</span>
                                </label>
                                <input data-tw-merge id="password_confirmation1" name="password_confirmation" type="password" placeholder="*********" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                <div class="mt-2 text-danger"></div>
                            </div>
                            <div class="grid grid-cols-12 gap-6">
                                <!-- BEGIN: Profile Menu -->
                                <div class="intro-y col-span-12 md:col-span-4">
                                    <div class="intro-y mt-5">
                                        <div class="mt-3">
                                            <label data-tw-merge for="gas_safe_id_card1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                Gas Safe Id Card <span class="text-danger ">*</span>
                                            </label>
                                            <input data-tw-merge id="gas_safe_id_card1" value="{{ $user->gas_safe_id_card }}" name="gas_safe_id_card" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                            <div class="mt-2 text-danger error-gas_safe_id_card"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="intro-y col-span-12 md:col-span-4">
                                    <div class="intro-y mt-5">
                                        <div class="mt-3">
                                            <label data-tw-merge for="oil_registration_number1"  class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                Oil Registration Number <span class="text-danger">*</span>
                                            </label>
                                            <input data-tw-merge id="oil_registration_number1" value="{{ $user->oil_registration_number }}" name="oil_registration_number" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                            <div class="mt-2 text-danger error-oil_registration_number"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="intro-y col-span-12 md:col-span-4">
                                    <div class="intro-y mt-5">
                                        <div class="mt-3">
                                            <label data-tw-merge for="installer_ref_no1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                Installer Ref No <span class="text-danger">*</span>
                                            </label>
                                            <input data-tw-merge id="installer_ref_no1" name="installer_ref_no" value="{{ $user->installer_ref_no }}"  type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" />
                                            <div class="mt-2 text-danger error-installer_ref_no"></div>
                                        </div>
                                    </div>
                                </div>
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
    
@endPushOnce

@pushOnce('scripts')
    <script type="module">

const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
        function evaluateUpdatedPasswordStrength() {
            const password = document.getElementById('password1').value;
            const strengthBars = document.querySelectorAll('#password-strength1 > div');
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
        $('#password1').on('input', function() {
            evaluateUpdatedPasswordStrength()
        })


        $('#customerUpdateForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('customerUpdateForm');
        //const form2 = document.getElementById('addStaffForm');


        let $theForm = $(this);
        
        $('#userUpdateBtn', $theForm).attr('disabled');
        $("#userUpdateBtn .theLoader").fadeIn();

        let formData = new FormData(form);
        
        // const form2Data = new FormData(form2);

        // for (const [key, value] of form2Data.entries()) {
        //     formData.append(key, value);
        // }
        let $id = $('#id').val();
        axios({
            method: "post",
            url: route('user.update',$id),
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
                    //$("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
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
                        $(`#customerUpdateForm .${key}`).addClass('border-danger');
                        $(`#customerUpdateForm  .error-${key}`).html(val);
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
    </script>
@endPushOnce