@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Upgrade Subscription</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <form method="post" action="#" id="upgradeSubscriptionForm">
        @csrf
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-4 relative">
                <div class="box packageWrap p-0 pt-[4px] rounded-xl rounded-bl-2xl rounded-br-2xl border bg-pink-900">
                    <input class="w-0 h-0 opacity-0 absolute left-0 top-0 pricing_package_id" id="pricing_package_{{ $pack->id }}" name="pricing_package_id" type="hidden" value="{{ $pack->id }}"/>
                    <div class="bg-white rounded-xl px-5 sm:px-7 py-7">
                        <div class="packageHeader min-h-[127px] border-b border-b-slate-200">
                            <span class="bg-warning bg-opacity-15 inline-flex px-2 py-0.5 font-medium">Activate</span>
                            <h4 class="font-medium text-dark leading-none mt-2 mb-3">{{ $pack->title }}</h4>
                            <h2 class="text-xl font-bold text-dark leading-none">
                                {{ Number::currency($pack->price, 'GBP') }} 
                                <span class="text-xs text-slate-500">/{{ $pack->period }}</span>
                            </h2>
                        </div>
                        <div class="packageBody pt-5">
                            <p class=" text-slate-500 flex items-start mb-7">
                                <i data-lucide="check-circle" class="w-4 h-4 mr-3" style="flex: 0 0 auto; position: relative; top: 2px;"></i>
                                <span>{{ $pack->description }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-8">
                <div class="px-2 py-3 bg-white mb-4">
                    <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
                        <div class="w-full">
                            <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Card Holder Name <span class="text-danger">*</span></div>
                            <div class="theDesc w-full relative">
                                <x-base.form-input id="card_holder_name" name="card_holder_name" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" autocomplete="off"/>
                                <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="user" />
                            </div>
                            <div class="mt-1 text-xs font-medium text-danger acc-input-error error-card_holder_name" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <div class="px-2 py-3 bg-white mb-4">
                    <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
                        <div class="w-full">
                            <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Card Number <span class="text-danger">*</span></div>
                            <div class="theDesc w-full relative">
                                <div id="card_number_element" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none"></div>
                                <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="user" />
                            </div>
                            <div class="mt-1 text-xs font-medium text-danger acc-input-error error-card_number_element" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-x-2.5 gap-y-2.5 mb-4">
                    <div class="col-span-12 sm:col-span-4">
                        <div class="px-2 py-3 bg-white">
                            <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
                                <div class="w-full">
                                    <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Expiry Date <span class="text-danger">*</span></div>
                                    <div class="theDesc w-full relative">
                                        <div id="card_expiry_element" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none"></div>
                                        <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar-days" />
                                    </div>
                                    <div class="mt-1 text-xs font-medium text-danger acc-input-error error-card_expiry_element" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="px-2 py-3 bg-white">
                            <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
                                <div class="w-full">
                                    <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">CVC <span class="text-danger">*</span></div>
                                    <div class="theDesc w-full relative">
                                        <div id="card_cvc_element" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none"></div>
                                        <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="id-card" />
                                    </div>
                                    <div class="mt-1 text-xs font-medium text-danger acc-input-error error-card_cvc_element" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="px-2 py-3 bg-white">
                            <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
                                <div class="w-full">
                                    <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Postal Code <span class="text-danger">*</span></div>
                                    <div class="theDesc w-full relative">
                                        <x-base.form-input id="postal_code" name="postal_code" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" autocomplete="off"/>
                                        <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                    </div>
                                    <div class="mt-1 text-xs font-medium text-danger acc-input-error error-postal_code" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-base.button class="w-full sm:w-auto items-center justify-center text-white" id="upgradeSubBtn" type="button" variant="success">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Upgrade Now
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </div>
        </div>
    </form>

    @include('app.action-modals')
@endsection
<script src="https://js.stripe.com/v3/"></script>

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
@endPushOnce

@pushOnce('scripts')

    <script type="module">
        const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
        const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
        const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
        
        
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

        const form = document.getElementById('upgradeSubscriptionForm')
        let $theForm = $('#upgradeSubscriptionForm');
        const stripe = Stripe('{{ env("STRIPE_KEY") }}');
        const elements = stripe.elements();

        let style = {
            base: {
                iconColor: '#666EE8',
                color: '#475569',
                fontSize: '14px',
                lineHeight: '20px',
                fontWeight: 400,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                borderRadius: '0',
                border: '0',
                '::placeholder': {
                    color: '#9ca3af',
                },
            },
        };

        var cardNumberElement = elements.create('cardNumber', {
            style: style,
            showIcon: true,
            placeholder: '1234 1234 1234 1234',
        });
        cardNumberElement.mount('#card_number_element');

        var cardExpiryElement = elements.create('cardExpiry', {
            style: style
        });
        cardExpiryElement.mount('#card_expiry_element');

        var cardCvcElement = elements.create('cardCvc', {
            style: style
        });
        cardCvcElement.mount('#card_cvc_element');

        const carHolderName = document.getElementById('card_holder_name');
        const postalCode = document.getElementById('postal_code');
        let $theButton = $('#upgSubBtn', $theForm);

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
            if (event.error) {
                $('.error-card_cvc_element', $theForm).fadeIn().html(event.error.message)
            } else {
                $('.error-card_cvc_element', $theForm).fadeOut().html('')
            }

            if (event.complete) {
                $theButton.removeAttr('disabled');
            } else {
                $theButton.attr('disabled', 'disabled');
            }
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            $('.acc-input-error', $theForm).fadeOut().html('');
            $theButton.attr('disabled');
            $(".theLoader", $theButton).fadeIn();

            if($('.pricing_package_id:checked').length == 0){
                $('.error-pricing_package_id', $theForm).fadeIn().html('Please select a package.');
                
                $theButton.removeAttr('disabled');
                $(".theLoader", $theButton).fadeOut();

                return false;
            }
            if(carHolderName.value == ''){
                $('.error-card_holder_name', $theForm).fadeIn().html('This field is required.');
                
                $theButton.removeAttr('disabled');
                $(".theLoader", $theButton).fadeOut();

                return false;
            }
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
                    url: route('company.dashboard.upgrade.subscription'),
                    data: formData,
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    $theButton.removeAttr('disabled');
                    $('.theLoader', $theButton).fadeOut();
                    
                    if (response.status == 200) {
                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                        });

                        setTimeout(() => {
                            successModal.hide();
                            if(response.data.red){
                                window.location.href = response.data.red;
                            }else{
                                window.location.reload();
                            }
                        }, 2500);
                    }
                }).catch(error => {
                    $theButton.removeAttr('disabled');
                    $('.theLoader', $theButton).fadeOut();
                    if (error.response) {
                        console.log(error.response);
                        if (error.response.status == 422) {
                            for (const [key, val] of Object.entries(error.response.data.errors)) {
                                $(`#upgradeSubscriptionForm .${key}`).addClass('border-danger');
                                $(`#upgradeSubscriptionForm  .error-${key}`).fadeIn().html(val);
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

        
    </script>
@endPushOnce