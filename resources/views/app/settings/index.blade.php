@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>User Settings</title>
@endsection

@section('subcontent')
<div class="grid grid-cols-12 gap-x-6 gap-y-10">
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
            {{-- <div id="settings2" class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-4 xl:col-span-3">
                <div  class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                    <div class="p-5 box ">
                        <div class="flex flex-col items-center pt-5">
                            <div
                                class="flex items-center justify-center w-16 h-16 border-4 rounded-full border-white/70 bg-primary">
                                <x-base.lucide
                                    class="h-8 w-8 stroke-[1.5] text-white"
                                    icon="users"
                                />
                            </div>
                            <div class="mt-5 text-base font-medium text-center">
                                Manage Users
                            </div>
                            <div class="mt-0.5 px-8 text-center text-slate-500">
                                Enable or disable users, and manage their login credentials and access permissions.
                            </div>
                            <x-base.button
                                id="manage-staffs"
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
            </div> --}}
            
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
        </div>
    </div>
</div>
@endsection

@pushOnce('scripts')
    <script type="module">
        (function () {
            

            async function ManageCompany() {
                // Reset state
                location.href = route('company.index');
            }

          //  async function ManageUsers() {
                // Reset state
          //      location.href = route('staff.index');
          //  }



            $('#manage-company').on('click', function() {
                ManageCompany()
            })


           // $('#manage-staffs').on('click', function() {
          //      ManageUsers()
          //  })
        })()
    </script>
@endPushOnce