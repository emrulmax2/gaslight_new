@extends('../layout/' . $layout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">{{ $subtitle }}</h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="{{ route('dashboard') }}" class="add_btn btn btn-primary shadow-md mr-2">Back To Dashboard</a>
        </div>
    </div>

    <!-- BEGIN: Settings Page Content -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
            <!-- BEGIN: Profile Info -->
            @include('pages.settings.sidebar')
            <!-- END: Profile Info -->
        </div>

        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
            <!-- BEGIN: Display Information -->
            <div class="intro-y box lg:mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">Update SMS API Settings</h2>
                </div>
                <div class="p-5">
                    <form method="post" action="#" id="companySettingsForm" enctype="multipart/form-data">
                        <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                            <div class="col-span-12 sm:col-span-6">
                                <label for="active_api" class="form-label">Active API</label>
                                <select id="active_api" name="active_api" class="form-control">
                                    <option value="">Please Select</option>
                                    <option {{ (isset($opt['active_api']) && $opt['active_api'] == '1' ? 'selected' : '' ) }} value="1">Text Local</option>
                                    <option {{ (isset($opt['active_api']) && $opt['active_api'] == '2' ? 'selected' : '' ) }}  value="2">SMS Eagle</option>
                                </select>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label for="textlocal_api" class="form-label">Textlocal API (https://textlocal.com/)</label>
                                <input id="textlocal_api" type="text" name="textlocal_api" class="form-control" placeholder="Textlocal API" value="{{ (isset($opt['textlocal_api']) ? $opt['textlocal_api'] : '' ) }}">
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label for="smseagle_api" class="form-label">SMSEagle API (https://www.smseagle.eu/)</label>
                                <input id="smseagle_api" type="text" name="smseagle_api" class="form-control" placeholder="SMSEagle API" value="{{ (isset($opt['smseagle_api']) ? $opt['smseagle_api'] : '' ) }}">
                            </div>
                        </div>
                        <button type="submit" id="updateCINF" class="btn btn-primary w-auto mt-4">
                            Update
                            <svg style="display: none;" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg"
                                stroke="white" class="w-4 h-4 ml-2">
                                <g fill="none" fill-rule="evenodd">
                                    <g transform="translate(1 1)" stroke-width="4">
                                        <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle>
                                        <path d="M36 18c0-9.94-8.06-18-18-18">
                                            <animateTransform attributeName="transform" type="rotate" from="0 18 18"
                                                to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </button>
                        <input type="hidden" name="category" value="SMS"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Settings Page Content -->

    <!-- BEGIN: Success Modal Content -->
    <div id="successModal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="check-circle" class="w-16 h-16 text-success mx-auto mt-3"></i>
                        <div class="text-3xl mt-5 successModalTitle"></div>
                        <div class="text-slate-500 mt-2 successModalDesc"></div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-primary w-24">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Success Modal Content -->
@endsection

@section('script')
    @vite('resources/js/settings.js')
@endsection