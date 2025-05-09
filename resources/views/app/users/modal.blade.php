
<!-- BEGIN: Add New User Modal Content -->
<x-base.dialog id="addnew-modal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="userCreateForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center"><x-base.lucide class="h-4 w-4 mr-2 text-primary" icon="UserPlus"/> Create User</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-4 gap-y-2">
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label for="name">Name <span class="text-danger">*</span></x-base.form-label>
                        <x-base.form-input name="name" id="name" class="w-full" type="text" placeholder="John Doe" />
                        <div class="mt-1 text-danger acc-input-error error-name" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label for="email">Email <span class="text-danger">*</span></x-base.form-label>
                        <x-base.form-input name="email" id="email" class="w-full" type="text" placeholder="yourname@example.com" />
                        <div class="mt-1 text-danger acc-input-error error-email" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label for="password">Password <span class="text-danger">*</span></x-base.form-label>
                        <x-base.form-input name="password" id="password" class="w-full" type="password" placeholder="*********" />
                        <div id="password-strength" class="mt-2 grid h-1.5 w-full grid-cols-12 gap-4 password-strength">
                            <div id="strength-1" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            <div id="strength-2" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            <div id="strength-3" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            <div id="strength-4" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                        </div>
                        <div class="mt-1 text-danger acc-input-error error-password" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label for="password_confirmation">Password Confirmation</x-base.form-label>
                        <x-base.form-input name="password_confirmation" id="password_confirmation" class="w-full" type="password" placeholder="*********" />
                        <div class="mt-1 text-danger acc-input-error error-password_confirmation" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <x-base.form-label for="gas_safe_id_card">Gas Safe Id Card</x-base.form-label>
                        <x-base.form-input name="gas_safe_id_card" id="gas_safe_id_card" class="w-full" type="text" placeholder="" />
                        <div class="mt-1 text-danger acc-input-error error-gas_safe_id_card" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <x-base.form-label for="oil_registration_number">Oil Registration Number</x-base.form-label>
                        <x-base.form-input name="oil_registration_number" id="oil_registration_number" class="w-full" type="text" placeholder="" />
                        <div class="mt-1 text-danger acc-input-error error-oil_registration_number" style="display: none;"></div>
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <x-base.form-label for="installer_ref_no">Installer Ref No</x-base.form-label>
                        <x-base.form-input name="installer_ref_no" id="installer_ref_no" class="w-full" type="text" placeholder="" />
                        <div class="mt-2 text-danger acc-input-error error-installer_ref_no" style="display: none;"></div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-12 gap-x-4 gap-y-2">
                    @if($packages->count() > 0)
                        @foreach($packages as $pack)
                            <div class="col-span-12 sm:col-span-6 relative packageItems">
                                <input class="w-0 h-0 opacity-0 absolute left-0 top-0" id="pricing_package_{{ $pack->id }}" name="pricing_package_id" type="radio" value="{{ $pack->id }}"/>
                                <label class="packag block m-0 cursor-pointer" for="pricing_package_{{ $pack->id }}">
                                    <span class="bg-primary packageTop text-center block rounded rounded-br-none rounded-bl-none text-white py-6 px-5">
                                        <span class="text-xl font-bold leading-none mb-1 block">{{ $pack->title }}</span>
                                        <span class="text-xl font-bold leading-none block">{{ Number::currency($pack->price, 'GBP') }} /{{ $pack->period }}</span>
                                        <!-- <span class="text-slate-200 text-xs block">Exc. VAT</span> -->
                                    </span>
                                    <span class="px-4 packageBottom py-6 block border border-primary border-t-0 rounded rounded-tl-none rounded-tr-none text-center">
                                        <span class="block text-slate-500 text-xs mb-3">{{ $pack->description }}</span>
                                        <a href="#" class="ml-auto font-medium bg-primary rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">More Features</a>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    @endif
                    <div class="mt-2 text-danger error-pricing_package_id" style="display: none;"></div>
                </div>

                <div class="mt-4 grid grid-cols-12 gap-x-4 gap-y-2">
                    <div class="col-span-12">
                        <x-base.form-label for="card_holder_name">Card Holder Name</x-base.form-label>
                        <x-base.form-input name="card_holder_name" id="card_holder_name" class="w-full" type="text" placeholder="John Doe" />
                        <div class="mt-2 text-danger acc-input-error error-card_holder_name" style="display: none;"></div>
                    </div>

                    <div class="col-span-12">
                        <x-base.form-label for="card_details_label">Card Details</x-base.form-label>
                        <div id="card-element"></div>
                        <div class="mt-2 text-danger acc-input-error error-card_element" style="display: none;"></div>
                    </div>

                    <div class="col-span-12">
                        <x-base.form-label for="card_number_element">Card Number</x-base.form-label>
                        <div id="card_number_element" class="w-full rounded-md border border-slate-200 shadow-sm px-3"></div>
                        <div class="mt-2 text-danger acc-input-error error-card_number_element" style="display: none;"></div>
                    </div>
                    <div class="col-span-4">
                        <x-base.form-label for="card_expiry_element">Expiry Date</x-base.form-label>
                        <div id="card_expiry_element" class="w-full rounded-md border border-slate-200 shadow-sm px-3"></div>
                        <div class="mt-2 text-danger acc-input-error error-card_expiry_element" style="display: none;"></div>
                    </div>
                    <div class="col-span-4">
                        <x-base.form-label for="card_cvc_element">CVC</x-base.form-label>
                        <div id="card_cvc_element" class="w-full rounded-md border border-slate-200 shadow-sm px-3"></div>
                        <div class="mt-2 text-danger acc-input-error error-card_cvc_element" style="display: none;"></div>
                    </div>
                    <div class="col-span-4">
                        <x-base.form-label for="postal_code">Postal Code</x-base.form-label>
                        <x-base.form-input name="postal_code" id="postal_code" class="w-full" type="text" placeholder="G13 1LS" />
                        <div class="mt-2 text-danger acc-input-error error-postal_code" style="display: none;"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="userSaveBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Add New User Modal Content -->


<x-base.dialog id="updatesignature-modal" size="xl" staticBackdrop >
    <x-base.dialog.panel>
        <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" >
            <x-base.lucide class="h-8 w-8 text-slate-400" icon="X" />
        </a>
        <div class="px-16 pt-16 pb-5 flex items-left">
            <x-base.lucide
                class=" h-8 w-8 text-primary"
                icon="signature"
            />
            <div id="titleModal" class="text-2xl ml-2">Update Signature</div>
        </div>
        <div id="base-start" class=" flex flex-col px-16 pt-5 pb-16">
            <x-base.tab.group>
                <x-base.tab.list variant="tabs">
                    <x-base.tab id="example-1-tab" selected>
                        <x-base.tab.button class="w-full py-2" as="button" type="button">
                            Draw Signature
                        </x-base.tab.button>
                    </x-base.tab>
                    <x-base.tab id="example-2-tab">
                        <x-base.tab.button class="w-full py-2" as="button" type="button">
                            Upload
                        </x-base.tab.button>
                    </x-base.tab>
                </x-base.tab.list>
                <x-base.tab.panels class="border-b border-l border-r">
                    <x-base.tab.panel class="p-5 leading-relaxed" id="example-1" selected>
                        <form id="addSignUserForm" enctype="multipart/form-data">
                            <input type="hidden" name="edit_id" value="" id="edit_id">
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
                    <x-base.tab.panel class="p-5 leading-relaxed" id="example-2" >
                        <form id="myDropzone" action="/file-upload" class="dropzone [&.dropzone]:border-2 [&.dropzone]:border-dashed dropzone [&.dropzone]:border-slate-300/70 [&.dropzone]:bg-slate-50 [&.dropzone]:cursor-pointer [&.dropzone]:dark:bg-darkmode-600 [&.dropzone]:dark:border-white/5 dz-clickable" id="my-dropzone">
                            @csrf
                            <div class="fallback">
                                <input
                                    name="file"
                                    type="file"
                                />
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
                            <input type="hidden" name="pid" value="{{ auth()->user()->id }}" />
                        </form>
                        <div id="uploaded-view" class="border-dashed pt-5 mt-5 border-slate-300/60 rounded border-2 px-3 hidden"></div>


                        <div class="mt-3 text-center">
                            <form id="fileUploadForm" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="">
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

    </x-base.dialog.panel>
</x-base.dialog>

<script src="https://js.stripe.com/v3/"></script>
@pushOnce('scripts')
    <script type="module">
        const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
        const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
        const addnewModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addnew-modal"));
        const updatesignatureModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatesignature-modal"));

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

            // const password = document.getElementById('password').value;
            // const strengthBars = document.querySelectorAll('#password-strength > div');
            // let strength = 0;

            // if (password.length >= 8) strength++;
            // if (/[A-Z]/.test(password)) strength++;
            // if (/[0-9]/.test(password)) strength++;
            // if (/[^A-Za-z0-9]/.test(password)) strength++;

            // strengthBars.forEach((bar, index) => {
            //     if (index < strength) {
            //         bar.classList.add('bg-theme-1/30');
            //         bar.classList.remove('bg-slate-400/30');
            //     } else {
            //         bar.classList.add('bg-slate-400/30');
            //         bar.classList.remove('bg-theme-1/30');
            //     }
            // });
        }
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
        $('#password').on('input', function() {
            evaluatePasswordStrength()
        })


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
            let $id = $('#edit_id').val();
            console.log($id)
            axios({
                method: "post",
                // url: route('users.draw-signature', {'user_id': $id}),
                url: route('users.draw-signature', { user_id: $id }),
                data: formData,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $(".sign-pad-button-submit .loading-icon").fadeOut();

                if (response.status == 201) {

                    successModal.show();
                    
                    document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                        $("#successModal .successModalTitle").html("Congratulations!");
                        $("#successModal .successModalDesc").html(response.data.message);
                        $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                    });

                    setTimeout(() => {
                        successModal.hide();
                        window.location.reload();

                    }, 1500);

                }
            }).catch(error => {
                $(".sign-pad-button-submit .loading-icon").fadeOut();
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
            let $id = $('#edit_id').val();
            axios({
                method: "post",
                url: route('users.upload-signature', { user_id: $id }),
                data: formData,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#userSaveBtn', $theForm).removeAttr('disabled');
                $("#userSaveBtn .theLoader").fadeOut();

                if (response.status == 201) {
                    successModal.show();
                    document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                        $("#successModal .successModalTitle").html("Congratulations!");
                        $("#successModal .successModalDesc").html(response.data.message);
                        $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                    });

                    setTimeout(() => {
                        successModal.hide();
                        window.location.reload();
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


        /* New USER registration Start */
        const form = document.getElementById('userCreateForm')
        let $theForm = $('#userCreateForm');
        const stripe = Stripe('{{ env("STRIPE_KEY") }}');
        const elements = stripe.elements();

        let style = {
            base: {
                iconColor: '#666EE8',
                color: '#475569',
                fontSize: '14px',
                lineHeight: '36px',
                fontWeight: 400,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                borderRadius: '0.375rem',
                '::placeholder': {
                    color: '#9ca3af',
                },
            },
        };

        var cardNumberElement = elements.create('cardNumber', { style: style, showIcon: true, placeholder: '1234 1234 1234 1234'});
        cardNumberElement.mount('#card_number_element');

        var cardExpiryElement = elements.create('cardExpiry', { style: style });
        cardExpiryElement.mount('#card_expiry_element');

        var cardCvcElement = elements.create('cardCvc', { style: style });
        cardCvcElement.mount('#card_cvc_element');

        const email = document.getElementById('email');
        const carHolderName = document.getElementById('card_holder_name');
        const postalCode = document.getElementById('postal_code');
        let $theButton = $('#userSaveBtn', $theForm);

        cardNumberElement.on('change', function(event) {
            if (event.error) {
                $('.error-card_number_element', $theForm).fadeIn().html(event.error.message)
            } else {
                $('.error-card_number_element', $theForm).fadeOut().html('')
            }

            if (event.complete) {
                $theButton.removeAttr('disabled');
            } else {
                $theButton.attr('disabled', 'disabled');
            }
        });
        cardExpiryElement.on('change', function(event) {
            if (event.error) {
                $('.error-card_expiry_element', $theForm).fadeIn().html(event.error.message)
            } else {
                $('.error-card_expiry_element', $theForm).fadeOut().html('')
            }

            if (event.complete) {
                $theButton.removeAttr('disabled');
            } else {
                $theButton.attr('disabled', 'disabled');
            }
        });
        cardCvcElement.on('change', function(event) {
            if(event.error){
                $('.error-card_cvc_element', $theForm).fadeIn().html(event.error.message);
            }else{
                $('.error-card_cvc_element', $theForm).fadeOut().html('');
            }

            if(event.complete){
                $theButton.removeAttr('disabled');
            }else{
                $theButton.attr('disabled', 'disabled');
            }
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            $('.acc-input-error', $theForm).fadeOut().html('');
            $theButton.attr('disabled');
            $(".theLoader", $theButton).fadeIn();

            if(postalCode.value == ''){
                $('.error-postal_code', $theForm).fadeIn().html('This field is required.');
                
                $theButton.removeAttr('disabled');
                $(".theLoader", $theButton).fadeOut();

                return false;
            }

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardNumberElement,
                billing_details:{
                    name: carHolderName.value,
                    email: email.value,
                    address: {
                        postal_code : postalCode.value
                    }
                }
            });

            if (error) {
                $('input[name="token"]', $theForm).remove();
                $('.error-card_number_element', $theForm).fadeIn().html(error.message);
                $theButton.removeAttr('disabled');
                $('.theLoader', $theButton).fadeOut();
            } else {
                let token = document.createElement('input')
                    token.setAttribute('type', 'hidden')
                    token.setAttribute('name', 'token')
                    token.setAttribute('value', paymentMethod.id);
                    form.appendChild(token);

                // Send paymentMethod.id to your server to complete the payment
                //console.log(paymentMethod);

                $('.acc-input-error', $theForm).fadeOut().html('');
                $theButton.attr('disabled', 'disabled');
                $('.theLoader', $theButton).fadeIn();

                let formData = new FormData(form);
                axios({
                    method: "post",
                    url: route('users.store'),
                    data: formData,
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    $theButton.removeAttr('disabled');
                    $('.theLoader', $theButton).fadeOut();

                    if (response.status == 200) {
                        addnewModal.hide();

                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                        });

                        setTimeout(() => {
                            successModal.hide();
                            window.location.reload();
                        }, 2500);
                    }
                }).catch(error => {
                    $theButton.removeAttr('disabled');
                    $('.theLoader', $theButton).fadeOut();
                    if (error.response) {
                        console.log(error.response);
                        if (error.response.status == 422) {
                            for (const [key, val] of Object.entries(error.response.data.errors)) {
                                $(`#userCreateForm .${key}`).addClass('border-danger');
                                $(`#userCreateForm  .error-${key}`).fadeIn().html(val);
                            }
                        } else if (error.response.status == 304) {
                            warningModal.show();
                            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                                $("#warningModal .warningModalTitle").html("Error Found!");
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
            }

        });
        /* New USER registration End */

        
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

    document.querySelector('#example-1-tab').addEventListener('shown.tw.tab', function() {})
    </script>
@endPushOnce