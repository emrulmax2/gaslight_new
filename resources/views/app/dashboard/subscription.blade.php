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
            <div class="col-span-12 sm:col-span-9">
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 px-5 py-3 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">
                            Available Packages
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-x-4 gap-y-2">
                            @if($packages->count() > 0)
                                @foreach($packages as $pack)
                                    <div class="col-span-12 sm:col-span-6 relative packageItems">
                                        <input class="w-0 h-0 opacity-0 absolute left-0 top-0 pricing_package_id" id="pricing_package_{{ $pack->id }}" name="pricing_package_id" type="radio" value="{{ $pack->id }}"/>
                                        <label class="packag block m-0 cursor-pointer" for="pricing_package_{{ $pack->id }}">
                                            <span class="bg-pending packageTop text-center block rounded rounded-br-none rounded-bl-none text-white py-6 px-5">
                                                <span class="text-xl font-bold leading-none mb-1 block">{{ $pack->title }}</span>
                                                <span class="text-xl font-bold leading-none block">{{ Number::currency($pack->price, 'GBP') }} /{{ $pack->period }}</span>
                                                <!-- <span class="text-slate-200 text-xs block">Exc. VAT</span> -->
                                            </span>
                                            <span class="px-4 packageBottom py-6 block border border-t-0 rounded rounded-tl-none rounded-tr-none text-center">
                                                <span class="block text-slate-500 text-xs mb-3">{{ $pack->description }}</span>
                                                <a href="#" class="ml-auto font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">More Features</a>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                            <div class="col-span-12 text-danger error-pricing_package_id" style="display: none;"></div>
                        </div>
                        <div class="mt-3">
                            <x-base.form-label for="card_holder_name">Card Holder Name</x-base.form-label>
                            <x-base.form-input name="card_holder_name" id="card_holder_name" class="w-full" type="text" placeholder="John Doe" />
                            <div class="mt-2 text-danger acc-input-error error-card_holder_name" style="display: none;"></div>
                        </div>
                        <div class="mt-3 grid grid-cols-12 gap-x-4 gap-y-3">
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
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3 z-10">
                <div class="intro-y box p-5">
                    <x-base.button type="submit" id="upgSubBtn" class="text-white w-full mb-3" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Upgrade Now
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button as="a" href="{{ route('company.dashboard') }}" class="w-full" variant="danger">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                        Cancel
                    </x-base.button>
                </div>
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
                lineHeight: '36px',
                fontWeight: 400,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                borderRadius: '0.375rem',
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
