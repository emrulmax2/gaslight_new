@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Profile</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Personal Info</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="javascript:void(0);" data-tw-toggle="modal" data-tw-target="#updateUserNameModal" class="flex w-full items-center px-5 py-3">
                <span class="cursor-pointer image-fit inline-block h-8 w-8 overflow-hidden rounded-full mr-2 shadow-sm">
                    @if(!empty($user->photo) && Storage::disk('public')->exists('users/'.$user->id.'/'.$user->photo))
                        <img src="{{ $user->photo_url }}" alt="{{ $user->name }}"/>
                    @else
                        <x-avatar name="{{ auth()->user()->name }}" />
                    @endif
                </span>
                <span class="font-medium text-slate-500 text-sm">Name: {{ $user->name }}</span>
            </a>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Account Details</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="javascript:void(0);" data-type="email" data-required="1" data-title="Email" data-field="email" data-value="{{ $user->email }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="mail" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Email</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ $user->email }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-type="email" data-required="1" data-title="Email" data-field="email" data-value="{{ $user->email }}"  class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="smartphone" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Mobile</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($user->mobile) ? $user->mobile : 'N/A') }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-tw-toggle="modal" data-tw-target="#updatePasswordModal" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="key" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Password</span>
                    <span class="font-normal text-slate-400 text-xs block">Change password</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-type="text" data-required="0" data-title="Gas Safe ID Card" data-field="gas_safe_id_card" data-value="{{ $user->gas_safe_id_card }}" class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="id-card" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Gas Safe ID Card</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($user->gas_safe_id_card) ? $user->gas_safe_id_card : 'N/A') }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-type="text" data-required="0" data-title="Oil Registration Number" data-field="oil_registration_number" data-value="{{ $user->oil_registration_number }}" class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="hash" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Oil Registration Number</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($user->oil_registration_number) ? $user->oil_registration_number : 'N/A') }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-type="text" data-required="0" data-title="Installer Ref Number" data-field="installer_ref_no" data-value="{{ $user->installer_ref_no }}" class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="hash" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Installer Ref Number</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($user->installer_ref_no) ? $user->installer_ref_no : 'N/A') }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-type="number" data-required="1" data-title="Max Job Per Slot" data-field="max_job_per_slot" data-value="{{ $user->max_job_per_slot }}" class="fieldValueToggler flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="hash" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Max Job Per Slot</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($user->max_job_per_slot) ? $user->max_job_per_slot : 1) }}</span>
                </div>
            </a>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Signature</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="javascript:void(0);" data-tw-toggle="modal" data-tw-target="#updateSignatureModal" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="signature" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Signature</span>
                    <span class="font-normal text-slate-400 text-xs block">
                        @if($user->signature)
                            <img id="signature" src="{{ $user->signature ? Storage::disk('public')->url($user->signature->filename) : Vite::asset('resources/images/placeholders/signature-placeholder.jpg') }}" alt="signature" class="w-auto h-14" />
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </a>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Subscriptions</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="{{ route('company.dashboard.manage.subscriptions') }}" class="border-b flex w-full items-center px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" style="margin-top: -2px;" />
                <span class="font-medium text-slate-500 text-sm">
                    Manage Subscription
                </span>
            </a>
            
            <a href="{{ route('users.index') }}" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="users" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Company Members</span>
                    <span class="font-normal text-slate-400 text-xs block">
                        Manage subscription policies
                    </span>
                </div>
            </a>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Payments</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="{{ route('users.payment.methods', $user->id) }}" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="badge-pound-sterling" style="margin-top: 2px;" />
                
                <div class="w-full">
                    <span class="font-medium text-slate-500 text-sm">Payment Method</span>
                    <span class="font-normal text-slate-400 text-xs block">
                        Manage your payment methods
                    </span>
                </div>
            </a>
            <a href="{{ route('users.payment.history', $user->id) }}" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="receipt-pound-sterling" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Billing History</span>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ (isset($user->userpackage->start) && !empty($user->userpackage->start) ? date('jS F, Y', strtotime($user->userpackage->start)).' - ' : '') }}
                        {{ (isset($user->userpackage->end) && !empty($user->userpackage->end) ? date('jS F, Y', strtotime($user->userpackage->end)) : '') }}
                        {{ (isset($user->userpackage->package->title) && !empty($user->userpackage->package->title) ? ' ('.$user->userpackage->package->title.')' : '') }}
                    </span>
                </div>
            </a>
        </div>
    </div>

    @include('app.user.modal')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/custom/signature.css')
    @vite('resources/css/vendors/dropzone.css')
@endPushOnce


@pushOnce('vendors')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/dropzone.js')
    @vite('resources/js/app/staffs/dropzone.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/users/profile.js')
@endPushOnce