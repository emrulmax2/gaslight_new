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
                    <h2 class="font-medium text-base mr-auto">Update Company Information</h2>
                </div>
                <div class="p-5">
                    <form method="post" action="#" id="companySettingsForm" enctype="multipart/form-data">
                        <div class="flex flex-col max-xl:flex-col-reverse xl:flex-row ">
                            <div class="flex-1 mt-6 xl:mt-0">
                                <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                                    <div class="col-span-12 sm:col-span-4">
                                        <x-base.form-label for="company_name">Company Name</x-base.form-label>
                                        <x-base.form-input id="company_name" type="text" name="company_name" class="w-full" placeholder="Company Name" value="{{ (isset($opt['company_name']) ? $opt['company_name'] : '' ) }}" />
                                    </div>
                                    <div class="col-span-12 sm:col-span-4">
                                        <x-base.form-label for="company_phone">Phone </x-base.form-label>
                                        <x-base.form-input id="company_phone" type="text" name="company_phone" class="w-full" placeholder="Phone" value="{{ (isset($opt['company_phone']) ? $opt['company_phone'] : '' ) }}" />
                                    </div>
                                    <div class="col-span-12 sm:col-span-4">
                                        <x-base.form-label for="company_email">Email Address</x-base.form-label>
                                        <x-base.form-input id="company_email" type="text" name="company_email" class="w-full" placeholder="Email Address" value="{{ (isset($opt['company_email']) ? $opt['company_email'] : '' ) }}" />
                                    </div>
                                    <div class="col-span-12 sm:col-span-12 theAddressWrap" id="jobAddressWrap">
                                        <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                                            <div class="col-span-12 sm:col-span-12">
                                                <x-base.form-label for="customer_address_line_1">Address Lookup</x-base.form-label>
                                                <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                                            </div>
                                            <div class="col-span-12 sm:col-span-6">
                                                <x-base.form-label for="customer_address_line_1">Address Line 1</x-base.form-label>
                                                <x-base.form-input name="address_line_1" id="customer_address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" value="{{ (isset($opt['address_line_1']) ? $opt['address_line_1'] : '' ) }}" />
                                                <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                                            </div>
                                            <div class="col-span-12 sm:col-span-6">
                                                <x-base.form-label for="address_line_2">Address Line 2</x-base.form-label>
                                                <x-base.form-input name="address_line_2" id="address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2 (Optional)" value="{{ (isset($opt['address_line_2']) ? $opt['address_line_2'] : '' ) }}" />
                                            </div>
                                            <div class="col-span-12 sm:col-span-4">
                                                <x-base.form-label for="city">Town/City</x-base.form-label>
                                                <x-base.form-input name="city" id="city" class="w-full city" type="text" placeholder="Town/City" value="{{ (isset($opt['city']) ? $opt['city'] : '' ) }}" />
                                                <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                                            </div>
                                            <div class="col-span-12 sm:col-span-4">
                                                <x-base.form-label for="state">Region/County</x-base.form-label>
                                                <x-base.form-input name="state" id="state" class="w-full state" type="text" placeholder="Region/County" value="{{ (isset($opt['state']) ? $opt['state'] : '' ) }}" />
                                            </div>
                                            <div class="col-span-12 sm:col-span-4">
                                                <x-base.form-label for="postal_code">Post Code</x-base.form-label>
                                                <x-base.form-input name="postal_code" id="postal_code" class="w-full postal_code" type="text" placeholder="Post Code" value="{{ (isset($opt['postal_code']) ? $opt['postal_code'] : '' ) }}" />
                                                <div class="acc__input-error error-postal_code text-danger text-xs mt-1"></div>
                                            </div>
                                        </div>
                                        <x-base.form-input name="country" id="country" class="w-full country" type="hidden" value="{{ (isset($opt['country']) ? $opt['country'] : '' ) }}" />
                                        <x-base.form-input name="latitude" class="w-full latitude" type="hidden" value="{{ (isset($opt['latitude']) ? $opt['latitude'] : '' ) }}" />
                                        <x-base.form-input name="longitude" class="w-full longitude" type="hidden" value="{{ (isset($opt['longitude']) ? $opt['longitude'] : '' ) }}" />
                                    </div>
                                    <div class="col-span-12 sm:col-span-12">
                                        <x-base.form-label for="company_right">Copyright Info</x-base.form-label>
                                        <x-base.form-textarea rows="3" id="company_right" name="company_right" class="w-full" placeholder="Right reserved by GasCertificate.co.uk @ 2025">{{ (isset($opt['company_right']) ? $opt['company_right'] : '' ) }}</x-base.form-textarea>
                                    </div>
                                </div>
                                <x-base.button class="w-auto mt-5" id="updateCINF" type="submit" variant="primary">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                    Update
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                                <input type="hidden" name="category" value="SITE_SETTINGS"/>
                            </div>
                            <div class="w-52 mx-auto xl:mr-0 xl:ml-6">
                                <div class="border-2 border-dashed shadow-sm border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
                                    <div class="relative imgUploadWrap p-5 flex justify-center items-center cursor-pointer zoom-in mx-auto">
                                        <img class="max-w-full h-auto max-h-[100px] rounded-0 siteLogoImg" id="siteLogoImg" data-placeholder="{{ Vite::asset('resources/images/placeholders/200x200.jpg') }}" alt="Site Logo" src="{{ (isset($opt['site_logo']) && !empty($opt['site_logo']) && Storage::disk('public')->exists($opt['site_logo']) ? Storage::disk('public')->url($opt['site_logo']) : Vite::asset('resources/images/placeholders/200x200.jpg')) }}">
                                    </div>
                                    <div class="mx-auto cursor-pointer relative mt-2">
                                        <x-base.button class="w-full" type="button" variant="primary">Select Logo</x-base.button>
                                        <input type="file" accept=".jpg, .jpeg, .png, .gif, .svg" id="siteLogoUpload" name="site_logo" class="w-full h-full cursor-pointer top-0 left-0 absolute opacity-0">
                                    </div>
                                </div>
                                <div class="border-2 border-dashed shadow-sm border-slate-200/60 dark:border-darkmode-400 rounded-md p-5 mt-4">
                                    <div class="relative imgUploadWrap p-5 flex justify-center items-center cursor-pointer zoom-in mx-auto">
                                        <img class="max-w-full max-h-[100px] h-auto rounded-0" alt="Site Favicon siteFaviconImg" id="siteFaviconImg" data-placeholder="{{ Vite::asset('resources/images/placeholders/200x200.jpg') }}" src="{{ (isset($opt['site_favicon']) && !empty($opt['site_favicon']) && Storage::disk('public')->exists($opt['site_favicon']) ? Storage::disk('public')->url($opt['site_favicon']) : Vite::asset('resources/images/placeholders/200x200.jpg')) }}">
                                    </div>
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <x-base.button class="w-full" type="button" variant="primary">Select Favicon</x-base.button>
                                        <input accept=".png, .svg" type="file" name="site_favicon" id="siteFaviconUpload" class="w-full h-full cursor-pointer top-0 left-0 absolute opacity-0">
                                    </div>
                                </div>
                            </div>
                        </div>
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
    @vite('resources/js/app/superadmin/settings/settings.js')
@endPushOnce