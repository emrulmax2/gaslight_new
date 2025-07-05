@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Initial Setup</title>
@endsection

@section('subcontent')
<div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
    <h2 class="mr-auto text-lg font-medium">Let’s do the initial setup</h2>
</div>
<form id="step1-form" action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="{{ $user->id }}" />
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-9">
            <!-- Personal Information Section -->
            <div class="intro-y box">
                <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                    <h2 class="mr-auto text-base font-medium">Update Password</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="password">Password <span class="text-danger">*</span></x-base.form-label>
                            <x-base.form-input name="password" id="password" class="w-full" type="password" placeholder="*********" />
                            <div id="password-strength" class="mt-2 grid h-1.5 w-full grid-cols-12 gap-4 password-strength">
                                <div id="strength-1" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="strength-2" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="strength-3" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="strength-4" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            </div>
                            <div class="mt-1 text-danger acc-input-error error-password" style="display: none;"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="password_confirmation">Password Confirmation</x-base.form-label>
                            <x-base.form-input name="password_confirmation" id="password_confirmation" class="w-full" type="password" placeholder="*********" />
                            <div class="mt-1 text-danger acc-input-error error-password_confirmation" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-y box mt-3">
                <div class="gsfSignature border rounded-[3px] h-auto py-10 bg-slate-100 rounded-b-none flex justify-center items-center">
                    <x-creagia-signature-pad name='sign'
                        border-color="#e5e7eb"
                        submit-name="Save"
                        clear-name="Clear Signature"
                        submit-id="signSaveBtn"
                        clear-id="clear"
                        pad-classes="w-auto h-48 bg-white mt-0"
                    />
                    <div class="customeUploads border-2 border-dashed border-slate-500 flex items-center text-center h-[200px] max-h-[200px] sm:w-[70%] rounded-[5px] p-[20px]" style="display: none">
                        <label for="signature_file" class="text-center upload-message my-[3em] relative w-full cursor-pointer">
                            <div class="customeUploadsContent">
                                <span class="text-lg font-medium">
                                    Drop files here or click to upload.
                                </span><br/>
                                <span class="text-gray-600">
                                    This is signature file upload. Selected files should<br/>
                                    not over <span class="font-medium">2MB</span> and should be image file.
                                </span><br/>
                            </div>
                            <img src="" alt="signature" id="signature_image" class="h-[80px] w-auto inline-block" style="display: none"/>
                        </label>
                        <input type="file" id="signature_file" name="signature_file" accept="image/*" class="w-0 h-0 opacity-0 absolute left-0 top-0"/>
                    </div>
                </div>
                <div class="intSetupSignatureBtns flex">
                    <x-base.button type="button" class="signBtns w-[50%] rounded-br-none active flex justify-center items-center rounded-t-none [&.active]:bg-success [&.active]:text-white" variant="secondary">
                        Draw Signature
                    </x-base.button>
                    <x-base.button type="button" class="uploadBtns w-[50%] rounded-bl-none flex justify-center items-center rounded-t-none [&.active]:bg-success [&.active]:text-white" variant="secondary">
                        Upload Signature
                    </x-base.button>
                </div>
                <span class="text-danger block acc-input-error mt-1 text-center error-signature"></span>
            </div>
        </div>    
        <div class="intro-y col-span-12 lg:col-span-3">
            <!-- Save and Cancel Buttons -->
            <div class="intro-y box ">
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-12">
                            <div class="flex flex-col space-y-4">
                                <x-base.button type="button" id="companySetupBtn" class="w-full text-white shadow-md" variant="success">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                    Save and Exit
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/custom/signature.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/dropzone.js')
    @vite('resources/js/vendors/sign-pad.min.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/users/initial-staff-setup.js')
@endPushOnce
