@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Settings</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $subtitle }}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('superadmin.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: Settings Page Content -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
            <!-- BEGIN: Profile Info -->
            @include('app.superadmin.settings.sidebar')
            <!-- END: Profile Info -->
        </div>

        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
            <form method="post" action="#" id="companySettingsForm" enctype="multipart/form-data">
                <input type="hidden" name="category" value="SITE_API"/>
                <!-- BEGIN: Display Information -->
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">Google API</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                            <div class="col-span-12 sm:col-span-12">
                                <x-base.form-label for="GOOGLE_MAP_API">Map API</x-base.form-label>
                                <x-base.form-textarea rows="2" id="GOOGLE_MAP_API" name="GOOGLE_MAP_API" class="w-full" placeholder="">{{ (isset($opt['GOOGLE_MAP_API']) ? $opt['GOOGLE_MAP_API'] : '' ) }}</x-base.form-textarea>
                            </div>
                        </div>
                        <x-base.button class="w-auto mt-5" id="updateCINF" type="submit" variant="primary">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                            Update
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                    </div>
                </div>
                <div class="intro-y box mt-5">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">Payment API</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                            <div class="col-span-12 sm:col-span-12">
                                <x-base.form-label for="STRIPE_KEY">Stripe KEY</x-base.form-label>
                                <x-base.form-textarea rows="2" id="STRIPE_KEY" name="STRIPE_KEY" class="w-full" placeholder="">{{ (isset($opt['STRIPE_KEY']) ? $opt['STRIPE_KEY'] : '' ) }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-12">
                                <x-base.form-label for="STRIPE_SECRET">Stripe SECRET</x-base.form-label>
                                <x-base.form-textarea rows="2" id="STRIPE_SECRET" name="STRIPE_SECRET" class="w-full" placeholder="">{{ (isset($opt['STRIPE_SECRET']) ? $opt['STRIPE_SECRET'] : '' ) }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-12">
                                <x-base.form-label for="STRIPE_WEBHOOK_SECRET">Stripe WEBHOOK SECRET</x-base.form-label>
                                <x-base.form-textarea rows="2" id="STRIPE_WEBHOOK_SECRET" name="STRIPE_WEBHOOK_SECRET" class="w-full" placeholder="">{{ (isset($opt['STRIPE_WEBHOOK_SECRET']) ? $opt['STRIPE_WEBHOOK_SECRET'] : '' ) }}</x-base.form-textarea>
                            </div>
                        </div>
                        <x-base.button class="w-auto mt-5" id="updateCINF" type="submit" variant="primary">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                            Update
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                    </div>
                </div>
                <div class="intro-y box mt-5">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">SMS API</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                            <div class="col-span-12 sm:col-span-12">
                                <x-base.form-label for="SMSEAGLE_SIM">SMS Eagle SIM SL</x-base.form-label>
                                <x-base.form-input type="number" id="SMSEAGLE_SIM" name="SMSEAGLE_SIM" class="w-full" value="{{ (isset($opt['SMSEAGLE_SIM']) ? $opt['SMSEAGLE_SIM'] : '' ) }}" placeholder="1-8" />
                            </div>
                            <div class="col-span-12 sm:col-span-12">
                                <x-base.form-label for="SMSEAGLE_API">SMS Eagle API</x-base.form-label>
                                <x-base.form-textarea rows="2" id="SMSEAGLE_API" name="SMSEAGLE_API" class="w-full" placeholder="">{{ (isset($opt['SMSEAGLE_API']) ? $opt['SMSEAGLE_API'] : '' ) }}</x-base.form-textarea>
                            </div>
                        </div>
                        <x-base.button class="w-auto mt-5" id="updateCINF" type="submit" variant="primary">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                            Update
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END: Settings Page Content -->

    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')

@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
    @vite('resources/js/vendors/axios.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/superadmin/settings/api.js')
@endPushOnce