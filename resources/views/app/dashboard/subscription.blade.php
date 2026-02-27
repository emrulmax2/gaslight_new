@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Manage Subscription</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 sm:col-span-9">
            <div id="packageSLider" class="packageSLider owl-carousel">
                @php 
                    $bgClass = ['bg-pink-900', 'bg-purple-900', 'bg-orange-900', 'bg-indigo-900'];
                @endphp
                @if($packages->count() > 0)
                    @foreach($packages as $pack)
                        @if((isset($userPackage->package->period) && $userPackage->package->period == 'Free Trail') || (isset($userPackage->package->period) && $userPackage->package->period != 'Free Trail' && $pack->period != 'Free Trail'))
                        <div class="packageItem relative">
                            <div class="box packageWrap p-0 pt-[4px] rounded-xl rounded-bl-2xl rounded-br-2xl border {{ $bgClass[$loop->index] }} ">
                                <input class="w-0 h-0 opacity-0 absolute left-0 top-0 pricing_package_id" id="pricing_package_{{ $pack->id }}" name="pricing_package_id" type="radio" value="{{ $pack->id }}"/>
                                <div class="bg-white rounded-xl px-5 sm:px-7 py-10">
                                    <div class="packageHeader min-h-[127px] border-b border-b-slate-200">
                                        <span class="bg-success bg-opacity-30 text-primary inline-flex px-2 py-0.5 font-medium {{ (isset($userPackage->pricing_package_id) && $userPackage->active == 1 && $userPackage->pricing_package_id == $pack->id ? '' : 'opacity-0 -z-10')}}">Active</span>
                                        <h4 class="font-medium text-dark leading-none mt-2 mb-3">{{ $pack->title }}</h4>
                                        <h2 class="text-xl font-bold text-dark leading-none">
                                            {{ Number::currency($pack->price, 'GBP') }} 
                                            <span class="text-xs text-slate-500">/{{ $pack->period }}</span>
                                        </h2>
                                        @if(isset($userPackage->pricing_package_id) && $userPackage->pricing_package_id == $pack->id && $userPackage->cancellation_requested > 0)
                                            <span class="bg-danger bg-opacity-10 text-xs px-1.5 py-0.5 font-medium mt-2 inline-flex">
                                                Ended on - {{ date('d M Y', strtotime($userPackage->end)) }}
                                            </span>
                                        @elseif(isset($userPackage->pricing_package_id) && $userPackage->pricing_package_id == $pack->id && $userPackage->active == 1)
                                            <span class="bg-warning bg-opacity-10 text-xs px-1.5 py-0.5 font-medium mt-2 inline-flex">
                                                {{ $userPackage->package->period == 'Free Trail' ? 'Trail ended on ' : 'Next auto renew '}} - {{ date('d M Y', strtotime($userPackage->end)) }}
                                            </span>
                                        @elseif(isset($userPackage->pricing_package_id) && $userPackage->pricing_package_id != $pack->id && $userPackage->upgrade_to == $pack->id)
                                            <span class="bg-success bg-opacity-10 text-xs px-1.5 py-0.5 font-medium mt-2 inline-flex">
                                                Activated from - {{ date('d M Y', strtotime($userPackage->end)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="packageBody pt-5">
                                        <p class=" text-slate-500 flex items-start mb-10">
                                            <i data-lucide="check-circle" class="w-4 h-4 mr-3" style="flex: 0 0 auto; position: relative; top: 2px;"></i>
                                            <span>{{ $pack->description }}</span>
                                        </p>
                                        @if((isset($userPackage->pricing_package_id) && $userPackage->pricing_package_id == $pack->id) && (!isset($userPackage->cancellation_requested) || $userPackage->cancellation_requested != 1))
                                            @if((isset($userPackage->pricing_package_id) && $userPackage->pricing_package_id == $pack->id) && $userPackage->active == 1)
                                                <x-base.button data-id="{{ $user->id }}" id="unsubscripUserBtn" type="button" class="rounded-full px-3 w-36 text-center justify-center {{ $pack->period == 'Free Trail' ? 'opacity-0 -z-20' : '' }}" variant="danger" >
                                                    <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                                                    Cancel
                                                </x-base.button>
                                            @elseif($userPackage->package->period == 'Free Trail') 
                                                <x-base.button as="a" href="{{ route('company.dashboard.subscribe', $pack->id) }}" class="rounded-full px-3 w-36 text-center justify-center text-white" variant="success" >
                                                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                                    Get Plan
                                                </x-base.button>
                                            @elseif($pack->period !== 'Free Trail' && (!isset($userPackage->upgrade_to) || $userPackage->upgrade_to != $pack->id)) 
                                                <x-base.button type="button" data-packid="{{ $pack->id }}" data-id="{{ $user->id }}" id="updateSubscriptionBtn" class="rounded-full px-3 w-36 text-center justify-center text-white" variant="success" >
                                                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                                    Upgrade Now
                                                </x-base.button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @include('app.action-modals')
@endsection
<script src="https://js.stripe.com/v3/"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">


@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/owl.carousel.js')

    <script type="module">
        const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
        const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
        const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));

        //npm install owl.carousel
        document.addEventListener('DOMContentLoaded', function() {
            $('#packageSLider').owlCarousel({
                loop: false,
                margin: 10,
                nav: false,
                dots: false,
                autoplay: false,
                responsive: {
                    0: { 
                        items: 1.1,
                        center: true,
                        margin: 5,
                        loop: true,
                        dots: true
                    },
                    768: { 
                        items: 1.1,
                        center: true,
                        margin: 10,
                        loop: true
                    },
                    1024: { 
                        items: 3 
                    }
                }
            });
        })
        
        
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

        $(document).on('click', '#unsubscripUserBtn', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            let user_id = $theBtn.attr('data-id');

            confirmModal.show();
            document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
                $('#confirmModal .confirmModalTitle').html('Are you sure?');
                $('#confirmModal .confirmModalDesc').html('Do you really want to cancel subscription for this user? If yes then please click on the agree btn.');
                $('#confirmModal .agreeWith').attr('data-id', user_id).attr('data-action', 'DELETESUB');
            });
        });

        $(document).on('click', '#updateSubscriptionBtn', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            let user_id = $theBtn.attr('data-id');
            let package_id = $theBtn.attr('data-packid');

            confirmModal.show();
            document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
                $('#confirmModal .confirmModalTitle').html('Are you sure?');
                $('#confirmModal .confirmModalDesc').html('Do you really want to upgrade you subscription package? If yes then please click on the agree btn.');
                $('#confirmModal .agreeWith').attr('data-id', user_id).attr('data-package', package_id).attr('data-action', 'UPGRADESUB');
            });
        });

        $('#confirmModal .agreeWith').on('click', function(){
            let $agreeBTN = $(this);
            let row_id = $agreeBTN.attr('data-id');
            let action = $agreeBTN.attr('data-action');

            $('#confirmModal button').attr('disabled', 'disabled');
            if(action == 'DELETESUB'){
                axios({
                    method: 'POST',
                    url: route('users.cancel.subscription'),
                    data: {user_id : row_id},
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    if (response.status == 200) {
                        $('#confirmModal button').removeAttr('disabled');
                        confirmModal.hide();

                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', response.data.red);
                        });

                        setTimeout(() => {
                            successModal.hide();
                            if(response.data.red){
                                window.location.href = response.data.red
                            }else{
                                window.location.reload();
                            }
                        }, 2500);
                    }
                }).catch(error =>{
                    console.log(error)
                });
            }else if(action == 'UPGRADESUB'){
                let package_id = $agreeBTN.attr('data-package');
                axios({
                    method: 'POST',
                    url: route('company.dashboard.upgrade.subscriptions'),
                    data: {user_id : row_id, package_id : package_id},
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    if (response.status == 200) {
                        $('#confirmModal button').removeAttr('disabled');
                        confirmModal.hide();

                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', response.data.red);
                        });

                        setTimeout(() => {
                            successModal.hide();
                            if(response.data.red){
                                window.location.href = response.data.red
                            }else{
                                window.location.reload();
                            }
                        }, 5000);
                    }
                }).catch(error =>{
                    console.log(error)
                });
            }
        });
    </script>
@endPushOnce
