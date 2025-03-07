<!-- BEGIN: Top Bar -->
<div
    class="top-bar-boxed relative z-[51] -mx-5 mb-6 max-sm:pb-3 sm:mb-12 mt-0 sm:mt-12 h-[45px] sm:h-[70px] border-b border-white/[0.08] px-3 sm:-mx-8 sm:px-8 md:-mt-5 md:pt-0">
    <div class="flex h-full items-center">
        <!-- BEGIN: Logo -->
        <a
            class="-intro-x flex"
            href="{{ route('company.dashboard') }}"
        >
            <img
                class="w-6"
                src="{{ Vite::asset('resources/images/logo.svg') }}"
                alt="Icewall Tailwind Admin Dashboard Template"
            />
            <span class="ml-3 text-lg text-white max-sm:hidden"> Icewall </span>
        </a>
        <!-- END: Logo -->
        <!-- BEGIN: Breadcrumb --> 
        <x-base.breadcrumb
            class="-intro-x mr-auto h-full border-white/[0.08] md:ml-10 md:border-l md:pl-10 max-sm:hidden"
            light
        >
            <x-base.breadcrumb.link :index="0">Application</x-base.breadcrumb.link>
            <x-base.breadcrumb.link
                :index="1" 
                active="{{ (!isset($breadcrumbs) || (isset($breadcrumbs) && empty($breadcrumbs)) ? 'true' : 'false') }}" 
                href="{{ route('company.dashboard') }}"
            >
                Dashboard
            </x-base.breadcrumb.link>
            @if(isset($breadcrumbs) && !empty($breadcrumbs))
                @php $i = 1; @endphp
                @foreach($breadcrumbs as $crumbs)
                    <x-base.breadcrumb.link 
                        index="{{ ($i + 1) }}" 
                        active="{{ ($i == count($breadcrumbs) ? 'true' : 'false') }}" 
                        href="{{ $crumbs['href'] }}" 
                    >
                        {{ $crumbs['label'] }}
                    </x-base.breadcrumb.link>
                    @php $i++; @endphp
                @endforeach
            @endif
        </x-base.breadcrumb>
        <!-- END: Breadcrumb -->
        
        <!-- BEGIN: Account Menu -->
        <x-base.menu class="max-sm:mr-[45px] ml-auto">
            <x-base.menu.button
                class="image-fit zoom-in intro-x block h-8 w-8 scale-110 overflow-hidden rounded-full shadow-lg"
            >
                {{-- <img
                    src="{{ Vite::asset($faker['photos'][0]) }}"
                    alt="Midone - Tailwind Admin Dashboard Template"
                /> --}}
                @if(Auth::guard('superadmin')->check())
                
                    <x-avatar name="{{ auth('superadmin')->user()->name }}" />
                @else
                    <x-avatar name="{{ auth()->user()->name }}" />
                @endif
            </x-base.menu.button>
            <x-base.menu.items
                class="relative mt-px w-56 bg-theme-1/80 text-white before:absolute before:inset-0 before:z-[-1] before:block before:rounded-md before:bg-black"
            >
            <x-base.menu.header class="font-normal">
                
                @if(Auth::guard('superadmin')->check())
                <div class="font-medium">{{ auth('superadmin')->user()->name }}</div>
                    <div class="mt-0.5 text-xs text-white/70 dark:text-slate-500">
                        Super Admin
                    </div>
                @else
                <div class="font-medium">{{ auth()->user()->name }}</div>
                <div class="mt-0.5 text-xs text-white/70 dark:text-slate-500">
                    Admin
                </div>
                @endif
            </x-base.menu.header>
                <x-base.menu.divider class="bg-white/[0.08]" />
                
                @if(Auth::guard('superadmin')->check())
                
                <x-base.menu.item class="hover:bg-white/5" href="{{ route('superadmin.logout') }}">
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="ToggleRight"
                    /> Logout
                </x-base.menu.item>
                @else
                <x-base.menu.item class="hover:bg-white/5" href="{{ route('profile') }}">
                        <x-base.lucide
                            class="mr-2 h-4 w-4"
                            icon="User"
                        /> Profile
                </x-base.menu.item>
                <x-base.menu.divider class="bg-white/[0.08]" />
                <x-base.menu.item class="hover:bg-white/5" href="{{ route('logout') }}">
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="ToggleRight"
                    /> Logout
                </x-base.menu.item>
                @endif

                @if (session()->has('impersonate'))
                    <x-base.menu.divider class="bg-white/[0.08]" />
                    <x-base.menu.item class="hover:bg-white/5" href="{{ route('impersonate.stop') }}">
                        <x-base.lucide
                            class="mr-2 h-4 w-4"
                            icon="UserX"
                        /> Stop Impersonating
                    </x-base.menu.item>
                @endif
            </x-base.menu.items>
        </x-base.menu>

        <!-- END: Account Menu -->
    </div>
</div>
<!-- END: Top Bar -->

@pushOnce('scripts')
    @vite('resources/js/components/themes/icewall/top-bar.js')
@endPushOnce
