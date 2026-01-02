@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $user->name.'\'s Payment Methods'}}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
            <x-base.button as="a" href="{{ route('users.add.payment.method', [$user->id, $user->userpackage->stripe_customer_id]) }}" class="shadow-md ml-2 text-white" variant="success">
                <x-base.lucide class="h-4 w-4 mr-2" icon="plus-circle" /> Add Method
            </x-base.button>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <div class="box rounded-md p-0 overflow-hidden">
            @if(isset($methods) && !empty($methods))
                @foreach($methods as $method)
                <a href="#" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="badge-pound-sterling" style="margin-top: 2px;" />
                    <div class="w-full">
                        <span class="font-medium text-slate-500 text-sm">Payment Method</span>
                        @if($method->type == 'card')
                            @php 
                                $card = $method->card;
                                $expireM = (isset($card->exp_month) && !empty($card->exp_month) ? $card->exp_month : '');
                                $expireY = (isset($card->exp_year) && !empty($card->exp_year) ? $card->exp_year : '');
                                $expires = (!empty($expireM) && !empty($expireY) ? date('F Y', strtotime('01-'.$expireM.'-'.$expireY)) : '');

                                $cardName = (isset($card-> display_brand) && !empty($card-> display_brand) ? ucfirst(str_replace('_', ' ', $card-> display_brand)) : '');
                                $cardName .= (isset($card-> last4) && !empty($card-> last4) ? '&nbsp;....'.$card-> last4 : '');
                            @endphp
                            <div class="w-full mt-2 flex items-center">
                                <div class="mr-auto">
                                    @if(isset($card-> brand) && !empty($card-> brand))
                                        <div class="mb-2">
                                            <img src="{{ Vite::asset('resources/images/cards/'.$card->brand.'.png') }}" alt="{{ $cardName }}" class="w-auto h-[20px]"/>
                                        </div>
                                    @endif
                                    <div class="font-bold text-dark text-base leading-none mb-2 capitalize">{!! $cardName !!}</div>
                                    <div class=" text-slate-500 text-xs leading-none mb-4"> Expires {{ $expires }}</div>
                                    @if($default_id == $method->id)
                                    <span class="text-xs bg-success-40 text-dark leading-none font-medium px-2 py-0.5">Active</span>
                                    @endif
                                </div>
                                <div class="ml-auto hidden">
                                    <button type="button" id="deleteMethod" data-user="{{ $user->id }}" data-customer="{{ $user->userpackage->stripe_customer_id }}" class="text-danger text-opacity-60 hover:text-opacity-100"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </div>
                        @endif
                    </div>
                </a>
                @endforeach
            @endif
        </div>
    </div>

    @include('app.action-modals')
@endsection