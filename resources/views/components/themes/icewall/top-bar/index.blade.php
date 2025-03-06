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
        <!-- BEGIN: Search -->
        <div class="intro-x relative mr-3 sm:mr-6 max-sm:ml-auto">
            
            <div class="search hidden sm:block">
                <x-base.form-input
                    class="w-56 rounded-full border-transparent bg-slate-200 pr-8 shadow-none transition-[width] duration-300 ease-in-out focus:w-72 focus:border-transparent dark:bg-darkmode-400/70"
                    type="text"
                    placeholder="Search..."
                />
                <x-base.lucide
                    class="absolute inset-y-0 right-0 my-auto mr-3 h-5 w-5 text-slate-600 dark:text-slate-500"
                    icon="Search"
                />
            </div>
            <a
                class="relative text-white/70 sm:hidden"
                href=""
            >
                <x-base.lucide
                    class="h-5 w-5 dark:text-slate-500"
                    icon="Search"
                />
            </a>
            <x-base.transition
                class="search-result absolute right-0 z-10 mt-[3px] hidden"
                selector=".show"
                enter="transition-all ease-linear duration-150"
                enterFrom="mt-5 invisible opacity-0 translate-y-1"
                enterTo="mt-[3px] visible opacity-100 translate-y-0"
                leave="transition-all ease-linear duration-150"
                leaveFrom="mt-[3px] visible opacity-100 translate-y-0"
                leaveTo="mt-5 invisible opacity-0 translate-y-1"
            >
                <div class="box w-[450px] p-5">
                    <div class="mb-2 font-medium">Pages</div>
                    <div class="mb-5">
                        <a
                            class="flex items-center"
                            href=""
                        >
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-success/20 text-success dark:bg-success/10">
                                <x-base.lucide
                                    class="h-4 w-4"
                                    icon="Inbox"
                                />
                            </div>
                            <div class="ml-3">Mail Settings</div>
                        </a>
                        <a
                            class="mt-2 flex items-center"
                            href=""
                        >
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-pending/10 text-pending">
                                <x-base.lucide
                                    class="h-4 w-4"
                                    icon="Users"
                                />
                            </div>
                            <div class="ml-3">Users & Permissions</div>
                        </a>
                        <a
                            class="mt-2 flex items-center"
                            href=""
                        >
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary/80 dark:bg-primary/20">
                                <x-base.lucide
                                    class="h-4 w-4"
                                    icon="CreditCard"
                                />
                            </div>
                            <div class="ml-3">Transactions Report</div>
                        </a>
                    </div>
                    <div class="mb-2 font-medium">Users</div>
                    <div class="mb-5">
                        @foreach (array_slice($fakers, 0, 4) as $faker)
                            <a
                                class="mt-2 flex items-center"
                                href=""
                            >
                                <div class="image-fit h-8 w-8">
                                    <img
                                        class="rounded-full"
                                        src="{{ Vite::asset($faker['photos'][0]) }}"
                                        alt="Midone - Tailwind Admin Dashboard Template"
                                    />
                                </div>
                                <div class="ml-3">{{ $faker['users'][0]['name'] }}</div>
                                <div class="ml-auto w-48 truncate text-right text-xs text-slate-500">
                                    {{ $faker['users'][0]['email'] }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mb-2 font-medium">Products</div>
                    @foreach (array_slice($fakers, 0, 4) as $faker)
                        <a
                            class="mt-2 flex items-center"
                            href=""
                        >
                            <div class="image-fit h-8 w-8">
                                <img
                                    class="rounded-full"
                                    src="{{ Vite::asset($faker['images'][0]) }}"
                                    alt="Midone - Tailwind Admin Dashboard Template"
                                />
                            </div>
                            <div class="ml-3">{{ $faker['products'][0]['name'] }}</div>
                            <div class="ml-auto w-48 truncate text-right text-xs text-slate-500">
                                {{ $faker['products'][0]['category'] }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </x-base.transition>
        </div>
        <!-- END: Search -->
        <!-- BEGIN: Notifications -->
        <x-base.popover class="intro-x mr-4 sm:mr-6">
            <x-base.popover.button
                class="relative block text-white/70 outline-none before:absolute before:right-0 before:top-[-2px] before:h-[8px] before:w-[8px] before:rounded-full before:bg-danger before:content-['']"
            >
                <x-base.lucide
                    class="h-5 w-5 dark:text-slate-500"
                    icon="Bell"
                />
            </x-base.popover.button>
            <x-base.popover.panel class="mt-2 w-[280px] p-5 sm:w-[350px]">
                <div class="mb-5 font-medium">Settings</div>
                @foreach (array_slice($fakers, 0, 5) as $fakerKey => $faker)
                    <div @class([
                        'cursor-pointer relative flex items-center',
                        'mt-5' => $fakerKey,
                    ])>
                        <div class="image-fit relative mr-1 h-12 w-12 flex-none">
                            <img
                                class="rounded-full"
                                src="{{ Vite::asset($faker['photos'][0]) }}"
                                alt="Midone - Tailwind Admin Dashboard Template"
                            />
                            <div
                                class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-success dark:border-darkmode-600">
                            </div>
                        </div>
                        <div class="ml-2 overflow-hidden">
                            <div class="flex items-center">
                                <a
                                    class="mr-5 truncate font-medium"
                                    href=""
                                >
                                    {{ $faker['users'][0]['name'] }}
                                </a>
                                <div class="ml-auto whitespace-nowrap text-xs text-slate-400">
                                    {{ $faker['times'][0] }}
                                </div>
                            </div>
                            <div class="mt-0.5 w-full truncate text-slate-500">
                                {{ $faker['news'][0]['short_content'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </x-base.popover.panel>
        </x-base.popover>
        <!-- END: Notifications -->
        <!-- BEGIN: Account Menu -->
        <a href="{{ route('user.settings') }}" class="intro-x mr-4 sm:mr-6">
            <x-base.lucide
                class="h-5 w-5 text-white/70"
                icon="settings"
            />
        </a>
        <x-base.menu class="max-sm:mr-[45px]">
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
