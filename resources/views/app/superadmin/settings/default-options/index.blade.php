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
            <!-- BEGIN: Display Information -->
            <div class="intro-y box lg:mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">Default Options Setting</h2>
                </div>
                <div class="p-5">
                    <form method="post" action="#" id="defaultOptionSettingForm" enctype="multipart/form-data">
                        <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="quote_expiry_days">Quote Expire Days</x-base.form-label>
                                <x-base.form-input id="quote_expiry_days" type="number" name="quote_expiry_days" class="w-full" placeholder="7 Days" value="{{ (isset($opt['quote_expiry_days']) ? $opt['quote_expiry_days'] : '' ) }}" />
                            </div>
                            <div class="col-span-12 sm:col-span-12">
                                <x-base.form-label for="payment_terms">Payment Terms </x-base.form-label>
                                <x-base.form-textarea rows="3" id="payment_terms" name="payment_terms" class="w-full" placeholder="">{{ (isset($opt['payment_terms']) ? $opt['payment_terms'] : '' ) }}</x-base.form-textarea>
                            </div>
                        </div>
                        <x-base.button class="w-auto mt-5" id="updateCINF" type="submit" variant="primary">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                            Update
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        <input type="hidden" name="category" value="DEFAULT_OPTIONS"/>
                    </form>
                </div>
            </div>
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
    @vite('resources/js/app/superadmin/settings/default-options.js')
@endPushOnce