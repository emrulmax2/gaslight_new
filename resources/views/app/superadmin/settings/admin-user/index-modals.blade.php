<!-- BEGIN: Modal Content -->
<x-base.dialog id="addUserModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="addUserForm" enctype="multipart/form-data">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add User</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <div class="col-span-12 sm:col-span-4">
                    <div class="image-fit h-44 w-44 rounded-full relative">
                        <img id="userImageAdd" class="rounded-full w-full h-full" data-placeholder="{{ Vite::asset('resources/images/placeholders/200x200.jpg') }}" src="{{ Vite::asset('resources/images/placeholders/200x200.jpg') }}" alt="image"/>
                        <input type="file" name="photo" class="w-0 h-0 opacity-0 left-0 top-0" id="userPhotoAdd"/>
                        <x-base.button as="label" for="userPhotoAdd" class="absolute right-0 bottom-[25px] p-0 rounded-full w-[35px] h-[35px] inline-flex items-center justify-center text-white" variant="primary">
                            <x-base.lucide class="h-4 w-4" icon="camera" />
                        </x-base.button>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="grid grid-cols-12 gap-x-6 gap-y-3">
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="name">Full Name <span class="text-danger ml-2">*</span></x-base.form-label>
                            <x-base.form-input name="name" id="name" class="w-full" type="text" />
                            <div class="acc__input-error error-name text-danger text-xs mt-1"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="email">Email <span class="text-danger ml-2">*</span></x-base.form-label>
                            <x-base.form-input name="email" id="email" class="w-full" type="email" />
                            <div class="acc__input-error error-email text-danger text-xs mt-1"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Password <span class="text-danger ml-2">*</span></x-base.form-label>
                            <div class="relative">
                                <x-base.form-input type="password" placeholder="************" name="password" id="password" />
                                <span id="togglePasswordShow" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                                    <i id="togglePasswordIcon" data-lucide="eye-off"></i>
                                </span>
                            </div>
                            <div id="password-strength" class="mt-3.5 grid h-1.5 w-full grid-cols-12 gap-2">
                                <div id="strength-1" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="strength-2" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="strength-3" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="strength-4" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            </div>
                            <div class="acc__input-error error-password mt-2 text-danger text-left text-xs"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Password Confirmation <span class="text-danger ml-2">*</span></x-base.form-label>
                            <div class="relative">
                                <x-base.form-input class="block" type="password" placeholder="************" name="password_confirmation" id="password_confirmation" />
                                <span id="toggleConfirmPasswordShow" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" >
                                    <i id="togglePasswordConfirmationIcon" data-lucide="eye-off"></i>
                                </span>
                            </div>
                            <div class="acc__input-error error-password_confirmation mt-2 text-danger text-left text-xs"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="mobile">Mobile</x-base.form-label>
                            <x-base.form-input name="mobile" id="mobile" class="w-full" type="text" />
                            <div class="acc__input-error error-mobile text-danger text-xs mt-1"></div>
                        </div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="float-left mt-2">
                    <x-base.form-check>
                        <div data-tw-merge class="flex items-center">
                            <label data-tw-merge for="status" class="cursor-pointer mr-5">Active</label>
                            <x-base.form-switch.input checked="1" class="" id="status" name="status" value="1" type="checkbox" />
                        </div>
                    </x-base.form-check>
                </div>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveUserBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Add User
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->
 
<!-- BEGIN: Modal Content -->
<x-base.dialog id="editUserModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="editUserForm" enctype="multipart/form-data">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Update User</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <div class="col-span-12 sm:col-span-4">
                    <div class="image-fit h-44 w-44 rounded-full relative">
                        <img id="edit_userImageAdd" class="rounded-full w-full h-full" data-placeholder="{{ Vite::asset('resources/images/placeholders/200x200.jpg') }}" src="{{ Vite::asset('resources/images/placeholders/200x200.jpg') }}" alt="image"/>
                        <input type="file" name="photo" class="w-0 h-0 opacity-0 left-0 top-0" id="edit_userPhotoAdd"/>
                        <x-base.button as="label" for="edit_userPhotoAdd" class="absolute right-0 bottom-[25px] p-0 rounded-full w-[35px] h-[35px] inline-flex items-center justify-center text-white" variant="primary">
                            <x-base.lucide class="h-4 w-4" icon="camera" />
                        </x-base.button>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="grid grid-cols-12 gap-x-6 gap-y-3">
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="edit_name">Full Name <span class="text-danger ml-2">*</span></x-base.form-label>
                            <x-base.form-input name="name" id="edit_name" class="w-full" type="text" />
                            <div class="acc__input-error error-name text-danger text-xs mt-1"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="edit_email">Email <span class="text-danger ml-2">*</span></x-base.form-label>
                            <x-base.form-input name="email" id="edit_email" class="w-full" type="email" />
                            <div class="acc__input-error error-email text-danger text-xs mt-1"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Password <span class="text-danger ml-2">*</span></x-base.form-label>
                            <div class="relative">
                                <x-base.form-input type="password" placeholder="************" name="password" id="edit_password" />
                                <span id="edit_togglePasswordShow" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                                    <i id="edit_togglePasswordIcon" data-lucide="eye-off"></i>
                                </span>
                            </div>
                            <div id="edit_password-strength" class="mt-3.5 grid h-1.5 w-full grid-cols-12 gap-2">
                                <div id="edit_strength-1" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="edit_strength-2" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="edit_strength-3" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                <div id="edit_strength-4" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            </div>
                            <div class="acc__input-error error-password mt-2 text-danger text-left text-xs"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Password Confirmation <span class="text-danger ml-2">*</span></x-base.form-label>
                            <div class="relative">
                                <x-base.form-input class="block" type="password" placeholder="************" name="password_confirmation" id="edit_password_confirmation" />
                                <span id="edit_toggleConfirmPasswordShow" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" >
                                    <i id="edit_togglePasswordConfirmationIcon" data-lucide="eye-off"></i>
                                </span>
                            </div>
                            <div class="acc__input-error error-password_confirmation mt-2 text-danger text-left text-xs"></div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="edit_mobile">Mobile</x-base.form-label>
                            <x-base.form-input name="mobile" id="edit_mobile" class="w-full" type="text" />
                            <div class="acc__input-error error-mobile text-danger text-xs mt-1"></div>
                        </div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="float-left mt-2">
                    <x-base.form-check>
                        <div data-tw-merge class="flex items-center">
                            <label data-tw-merge for="edit_status" class="cursor-pointer mr-5">Active?</label>
                            <x-base.form-switch.input class="" id="edit_status" name="status" value="1" type="checkbox" />
                        </div>
                    </x-base.form-check>
                </div>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="editUserBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Update
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="0"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->