@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $user->name.'\'s Plan'}}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="grid grid-cols-12 gap-4">
            @if($packages->count() > 0)
                @foreach($packages as $pack)
                    <div class="col-span-12 sm:col-span-6 relative packageItems">
                        <label class="{{ $user->userpackage->active == 1 && $pack->id == $user->userpackage->pricing_package_id ? 'active' : '' }} packag block m-0 cursor-pointer" for="pricing_package_{{ $pack->id }}">
                            <span class="bg-primary packageTop text-center block rounded rounded-br-none rounded-bl-none text-white py-6 px-5">
                                <span class="text-xl font-bold leading-none mb-1 block">{{ $pack->title }}</span>
                                <span class="text-xl font-bold leading-none block">{{ Number::currency($pack->price, 'GBP') }} /{{ $pack->period }}</span>
                            </span>
                            <span class="px-4 packageBottom py-6 block border border-primary border-t-0 rounded rounded-tl-none rounded-tr-none text-center">
                                <span class="block text-slate-500 text-xs">{{ $pack->description }}</span>
                                {!! $user->userpackage->active == 1 && $pack->id == $user->userpackage->pricing_package_id ? '<span class="mt-3 bg-success px-3 py-1 font-medium text-xs text-white mb-2 inline-flex">Current Subscription</span>' : '' !!}
                            </span>
                        </label>
                    </div>
                @endforeach
            @endif
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
    @vite('resources/js/app/users/profile.js')
@endPushOnce