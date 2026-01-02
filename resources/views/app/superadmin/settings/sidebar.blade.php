<div class="intro-y box mt-5">
    <div class="relative flex items-center p-5">
        <div class="w-12 h-12 rounded-full inline-flex justify-center items-center bg-slate-100">
            <i data-lucide="settings" class="w-6 h-6 text-primary"></i>
        </div>
        <div class="ml-4 mr-auto">
            <div class="font-medium text-base">Settings</div>
            <div class="text-slate-500">{{ $subtitle }}</div>
        </div>
    </div>
    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400 settingsMenu">
        <ul class="m-0 p-0">
            <li class="hasChild">
                <a class="flex items-center {{ Route::currentRouteName() == 'superadmin.site.setting.default.opt' || Route::currentRouteName() == 'superadmin.site.setting' || Route::currentRouteName() == 'superadmin.site.setting.api' ? 'active text-primary font-medium' : '' }}" href="javascript:void(0);">
                    <i data-lucide="globe" class="w-4 h-4 mr-2"></i> Site Settings  <i data-lucide="chevron-down" class="w-4 h-4 ml-auto menuAgnle"></i>
                </a>
                <ul class="p-0 m-0 pl-5" style="display: {{ Route::currentRouteName() == 'superadmin.site.setting' || Route::currentRouteName() == 'superadmin.site.setting.api' ? 'block' : 'none' }};">
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Company Information
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.api' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.api') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> API Settings
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.default.opt' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.default.opt') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Default Options Setting
                        </a>
                    </li>
                </ul>
            </li>
            <li class="hasChild mt-5">
                <a class="flex items-center {{ Route::currentRouteName() == 'superadmin.site.setting.user.registration' || Route::currentRouteName() == 'superadmin.site.setting.pricing.package' || Route::currentRouteName() == 'superadmin.site.setting.referral.code' ? 'active text-primary font-medium' : '' }}" href="javascript:void(0);">
                    <i data-lucide="user-round-cog" class="w-4 h-4 mr-2"></i> User Settings  <i data-lucide="chevron-down" class="w-4 h-4 ml-auto menuAgnle"></i>
                </a>
                <ul class="p-0 m-0 pl-5" style="display: {{ Route::currentRouteName() == 'superadmin.site.setting.user.registration' || Route::currentRouteName() == 'superadmin.site.setting.pricing.package' || Route::currentRouteName() == 'superadmin.site.setting.referral.code' ? 'block' : 'none' }};">
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.user.registration' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.user.registration') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Registration Settings
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.pricing.package' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.pricing.package') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Pricing Packages
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.referral.code' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.referral.code') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Referral Codes
                        </a>
                    </li>
                </ul>
            </li>
            <li class="hasChild mt-5">
                <a class="flex items-center {{ Route::currentRouteName() == 'superadmin.site.setting.job.cancel.reason' ? 'active text-primary font-medium' : '' }}" href="javascript:void(0);">
                    <i data-lucide="Briefcase" class="w-4 h-4 mr-2"></i> Job Settings  <i data-lucide="chevron-down" class="w-4 h-4 ml-auto menuAgnle"></i>
                </a>
                <ul class="p-0 m-0 pl-5" style="display: {{ Route::currentRouteName() == 'superadmin.site.setting.job.cancel.reason' ? 'block' : 'none' }};">
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.job.cancel.reason' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.job.cancel.reason') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Cancel Reasons
                        </a>
                    </li>
                </ul>
            </li>
            <li class="hasChild mt-5">
                <a class="flex items-center {{ Route::currentRouteName() == 'superadmin.site.setting.email.template' || Route::currentRouteName() == 'superadmin.site.setting.email.template.create' ? 'active text-primary font-medium' : '' }}" href="javascript:void(0);">
                    <i data-lucide="Briefcase" class="w-4 h-4 mr-2"></i> Communication Settings <i data-lucide="chevron-down" class="w-4 h-4 ml-auto menuAgnle"></i>
                </a>
                <ul class="p-0 m-0 pl-5" style="display: {{ Route::currentRouteName() == 'superadmin.site.setting.email.template.create' || Route::currentRouteName() == 'superadmin.site.setting.email.template' ? 'block' : 'none' }};">
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.email.template.create' || Route::currentRouteName() == 'superadmin.site.setting.email.template' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.email.template') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Email Templates
                        </a>
                    </li>
                </ul>
            </li>
            <li class="hasChild mt-5">
                <a class="flex items-center {{ Route::currentRouteName() == 'superadmin.site.setting.inv.cancel.reason' || Route::currentRouteName() == 'superadmin.site.setting.payment.method' ? 'active text-primary font-medium' : '' }}" href="javascript:void(0);">
                    <i data-lucide="badge-pound-sterling" class="w-4 h-4 mr-2"></i> Invoice Settings <i data-lucide="chevron-down" class="w-4 h-4 ml-auto menuAgnle"></i>
                </a>
                <ul class="p-0 m-0 pl-5" style="display: {{ Route::currentRouteName() == 'superadmin.site.setting.inv.cancel.reason' || Route::currentRouteName() == 'superadmin.site.setting.payment.method' ? 'block' : 'none' }};">
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.payment.method' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.payment.method') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Payment Methods
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center mt-4 {{ Route::currentRouteName() == 'superadmin.site.setting.inv.cancel.reason' ? 'active text-primary' : '' }}" href="{{ route('superadmin.site.setting.inv.cancel.reason') }}">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-2"></i> Cancel Reason
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>