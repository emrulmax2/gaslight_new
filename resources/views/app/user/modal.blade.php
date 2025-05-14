<!-- BEGIN: Update User Name Modal Content -->
<x-base.dialog id="updateUserNameModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updateUserNameForm" enctype="multipart/form-data">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Name</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2 items-center">
                    <div class="col-span-12 sm:col-span-4">
                        <div class="w-32 h-32 flex-none image-fit relative">
                            <img alt="User Photo" class="rounded-full userImageAdd" id="userImageAdd" data-placeholder="{{ $user->photo_url }}" src="{{ $user->photo_url }}">
                            <label for="userPhotoAdd" class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-3  cursor-pointer">
                                <i data-lucide="camera" class="w-4 h-4 text-white"></i>
                            </label>
                            <input type="file" accept=".jpeg,.jpg,.png,.gif" name="photo" class="absolute w-0 h-0 overflow-hidden opacity-0" id="userPhotoAdd"/>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label>Full Name<span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.form-input value="{{ $user->name }}" name="name" class="w-full h-[35px] rounded-[3px]" />
                        <div class="acc__input-error error-name text-danger text-xs mt-1"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateUNameBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $user->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update User Name Modal Content -->
 
<!-- BEGIN: Update Modal Content -->
<x-base.dialog id="updateUserDataModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updateUserDataForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Data</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12">
                        <x-base.form-label><span class="fieldTitle">Value</span><span class="requiredLabel text-danger hidden ml-1">*</span></x-base.form-label>
                        <x-base.form-input value="" name="fieldValue" class="w-full h-[35px] rounded-[3px]" />
                        <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateDataBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $user->id }}"/>
                <input type="hidden" name="fieldName" value=""/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Modal Content -->
 
<!-- BEGIN: Update Password Modal Content -->
<x-base.dialog id="updatePasswordModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updatePasswordForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Update Password</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div>
                    <x-base.form-label for="password">Password</x-base.form-label>
                    <x-base.form-input name="password" id="password" class="w-full" type="password" placeholder="*********" />
                    <div id="password-strength" class="mt-3.5 grid h-1.5 w-full grid-cols-12 gap-4 password-strength">
                        <div id="strength-1" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                        <div id="strength-2" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                        <div id="strength-3" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                        <div id="strength-4" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                    </div>
                    <div class="acc__input-error error-password text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="password_confirmation">Password Confirmation</x-base.form-label>
                    <x-base.form-input name="password_confirmation" id="password_confirmation" class="w-full" type="password" placeholder="*********" />
                    <span class="mt-2 text-danger error-password_confirmation"></span>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updatePassBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $user->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Password Modal Content -->

<!-- BEGIN: Update Signature Modal Content -->
<x-base.dialog id="updateSignatureModal" staticBackdrop size="lg">
    <x-base.dialog.panel class="rounded-none">
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">Update Password</h2>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="">
            <x-base.tab.group>
                <x-base.tab.list variant="boxed-tabs">
                    <x-base.tab id="example-1-tab" selected>
                        <x-base.tab.button class="w-full py-2 font-medium rounded-tr-none rounded-br-none bg-slate-100 text-success [&.active]:bg-success [&.active]:text-white shadow-none" as="button" type="button">
                            Draw Signature
                        </x-base.tab.button>
                    </x-base.tab>
                    <x-base.tab id="example-2-tab" >
                        <x-base.tab.button class="w-full py-2 font-medium rounded-tl-none rounded-bl-none bg-slate-100 text-success [&.active]:bg-success [&.active]:text-white shadow-none" as="button" type="button">
                            Upload Signature
                        </x-base.tab.button>
                    </x-base.tab>
                </x-base.tab.list>
                <x-base.tab.panels class="mt-5">
                    <x-base.tab.panel class="leading-relaxed" id="example-1" selected>
                        <form id="addSignUserForm" enctype="multipart/form-data" class="bg-slate-100 rounded-lg p-5">
                            <x-creagia-signature-pad name='sign'
                                border-color="#ccc"
                                submit-name="Save"
                                clear-name="Clear"
                                submit-id="signSaveBtn"
                                clear-id="clear"
                                pad-classes="w-auto h-48 bg-white mt-10"
                            />
                        </form>
                    </x-base.tab.panel>
                    <x-base.tab.panel class="leading-relaxed" id="example-2" >
                        <form id="myDropzone" action="/file-upload" class="dropzone [&.dropzone]:border-2 [&.dropzone]:border-dashed dropzone [&.dropzone]:border-slate-300/70 [&.dropzone]:bg-slate-50 [&.dropzone]:cursor-pointer [&.dropzone]:dark:bg-darkmode-600 [&.dropzone]:dark:border-white/5 dz-clickable" id="my-dropzone">
                            @csrf
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                            <div class="dz-message">
                                <div class="text-lg font-medium">
                                    Drop files here or click to upload.
                                </div>
                                <div class="text-gray-600">
                                    This is signature file upload. Selected files should
                                    not over <span class="font-medium">2MB</span> and should be image file.
                                </div>
                            </div>
                        </form>
                        <div id="uploaded-view" class="border-dashed pt-5 mt-5 border-slate-300/60 rounded border-2 px-3 hidden"></div>
                        <div class="mt-3 text-center">
                            <form id="fileUploadForm" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="file_id" value="" id="file_id">
                                <input type="hidden" name="file_path" value="" id="file_path">
                                <x-base.button class="w-auto border-0 rounded-0" id="userFileSaveBtn" type="submit" variant="primary">
                                    Upload Signature
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </form>
                        </div>
                    </x-base.tab.panel>
                </x-base.tab.panels>
            </x-base.tab.group>
        </x-base.dialog.description>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Signature Modal Content -->