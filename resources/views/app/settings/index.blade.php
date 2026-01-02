@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>User Settings</title>
@endsection

@section('subcontent')
<div class="settingsBox mt-5">
    <h3 class="font-medium leading-none mb-3 text-dark">Manage</h3>
    <div class="box rounded-md p-0 overflow-hidden">
        <a href="{{ route('profile') }}" class="border-b flex w-full items-center px-5 py-3">
            <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" style="margin-top: -2px;" />
            <span class="font-medium text-slate-500 text-sm">My Account</span>
        </a>
        <!-- <a href="{{ route('users.index') }}" class="flex w-full items-center px-5 py-3">
            <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" style="margin-top: -2px;" icon="users" />
            <span class="font-medium text-slate-500 text-sm">Company Members</span>
        </a> -->
    </div>
</div>

<div class="settingsBox mt-5">
    <h3 class="font-medium leading-none mb-3 text-dark">Company</h3>
    <div class="box rounded-md p-0 overflow-hidden">
        <a href="{{ route('company.index') }}" class="flex w-full items-center px-5 py-3 border-b">
            <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" style="margin-top: -2px;" icon="building" />
            <span class="font-medium text-slate-500 text-sm">Company Settings</span>
        </a>
        <!-- <a href="{{ route('user.subscriptions') }}" class="flex w-full items-center px-5 py-3">
            <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" style="margin-top: -2px;" icon="user-cog" />
            <span class="font-medium text-slate-500 text-sm">Subscriptions & Invoices</span>
        </a> -->
    </div>
</div>

<div class="settingsBox mt-5">
    <h3 class="font-medium leading-none mb-3 text-dark">Form Settings</h3>
    <div class="box rounded-md p-0 overflow-hidden">
        <a href="{{ route('user.settings.numbering') }}" class="border-b flex w-full items-center px-5 py-3">
            <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="book-text"  style="margin-top: -2px;"/>
            <span class="font-medium text-slate-500 text-sm">Invoices & Certificates Numbering</span>
        </a>
        <a href="{{ route('user.settings.reminder.templates') }}" class="flex w-full items-center px-5 py-3">
            <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" style="margin-top: -2px;" icon="bell-ring" />
            <span class="font-medium text-slate-500 text-sm">Service Reminders and Email Templates</span>
        </a>
    </div>
</div>

{{--<div class="grid grid-cols-12 gap-x-6 gap-y-10">
    <div class="col-span-12">
        <div class="flex items-center h-10 px-5 my-5">
            <div class="text-base font-medium group-[.mode--light]:text-white"></div>
        </div>
        <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
            <div id="settings1" class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-4 xl:col-span-3">
                <div  class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                    <div class="p-5 box ">
                        <div class="flex flex-col items-center pt-5">
                            <div
                                class="flex items-center justify-center w-16 h-16 border-4 rounded-full border-white/70 bg-primary">
                                <x-base.lucide
                                    class="h-8 w-8 stroke-[1.5] text-white"
                                    icon="building"
                                />
                            </div>
                            <div class="mt-5 text-base font-medium text-center">
                                Company Setting
                            </div>
                            <div class="mt-0.5 px-8 text-center text-slate-500">
                                Manage your registration numbers, business address, and company logo.
                            </div>
                            <x-base.button
                                id="manage-company"
                                class="w-full mt-7"
                                type="button"
                                rounded
                                variant="primary"
                            >
                                Manage
                            </x-base.button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="settings2" class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-4 xl:col-span-3">
                <div  class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                    <div class="p-5 box ">
                        <div class="flex flex-col items-center pt-5">
                            <div class="flex items-center justify-center w-16 h-16 border-4 rounded-full border-white/70 bg-primary">
                                <x-base.lucide class="h-8 w-8 stroke-[1.5] text-white" icon="users" />
                            </div>
                            <div class="mt-5 text-base font-medium text-center">
                                Manage Users
                            </div>
                            <div class="mt-0.5 px-8 text-center text-slate-500">
                                Enable or disable users, and manage their login credentials and access permissions.
                            </div>
                            <x-base.button class="w-full mt-7" type="button" rounded variant="primary" as="a" href="{{ route('users.index') }}">
                                Manage
                            </x-base.button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="settings3" class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-4 xl:col-span-3">
                <div  class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                    <div class="p-5 box ">
                        <div class="flex flex-col items-center pt-5">
                            <div
                                class="flex items-center justify-center w-16 h-16 border-4 rounded-full border-white/70 bg-primary">
                                <x-base.lucide
                                    class="h-8 w-8 stroke-[1.5] text-white"
                                    icon="book-text"
                                />
                            </div>
                            <div class="mt-5 text-base font-medium text-center">
                                Manage Invoice Details and Certificate Numbering
                            </div>
                            <div class="mt-0.5 px-8 text-center text-slate-500">
                                Modify the information displayed on your invoices and control your certificate numbering.
                            </div>
                            <x-base.button class="w-full mt-7" type="button" rounded variant="primary" as="a" href="{{ route('user.settings.numbering') }}" >
                                Manage
                            </x-base.button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="settings3" class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-4 xl:col-span-3">
                <div  class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                    <div class="p-5 box ">
                        <div class="flex flex-col items-center pt-5">
                            <div
                                class="flex items-center justify-center w-16 h-16 border-4 rounded-full border-white/70 bg-primary">
                                <x-base.lucide
                                    class="h-8 w-8 stroke-[1.5] text-white"
                                    icon="bell-ring"
                                />
                            </div>
                            <div class="mt-5 text-base font-medium text-center">
                                Service Reminders and Email Templates
                            </div>
                            <div class="mt-0.5 px-8 text-center text-slate-500">
                                Customize your email templates and configure your reminder settings.
                            </div>
                            <x-base.button
                                class="w-full mt-7"
                                type="button"
                                rounded
                                variant="primary"
                                as="a"
                                href="{{ route('user.settings.reminder.templates') }}"
                            >
                            Manage
                            </x-base.button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="settings4" class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-4 xl:col-span-3">
                <div  class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                    <div class="p-5 box ">
                        <div class="flex flex-col items-center pt-5">
                            <div
                                class="flex items-center justify-center w-16 h-16 border-4 rounded-full border-white/70 bg-primary">
                                <x-base.lucide
                                    class="h-8 w-8 stroke-[1.5] text-white"
                                    icon="user-cog"
                                />
                            </div>
                            <div class="mt-5 text-base font-medium text-center">
                                Subscriptions & Invoices
                            </div>
                            <div class="mt-0.5 px-8 text-center text-slate-500">
                                Company users subscription details and invoices.
                            </div>
                            <x-base.button
                                class="w-full mt-7"
                                type="button"
                                rounded
                                variant="primary"
                                as="a"
                                href="{{ route('user.subscriptions') }}"
                            >
                            Manage
                            </x-base.button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>--}}
@endsection