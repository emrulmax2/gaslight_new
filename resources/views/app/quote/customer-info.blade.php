<div class="intro-y box mt-5 bg-slate-200 rounded-none border-none px-2 pb-2 linkedJobWrap" style="display: none;">
    <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
        <h2 class="mr-auto text-base font-medium">
            Linked Job
        </h2>
    </div>
    <div class="px-2 py-3 jobWrap bg-white">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer jobBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Select Job</div>
                <div class="theDesc">Click here to select a job</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
            <input type="hidden" id="job_id" name="job_id" value="0" class="theId"/>
        </a>
    </div>
</div>
<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
    <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
        <h2 class="mr-auto text-base font-medium">
            Customer Details
        </h2>
    </div>
    <div class="px-2 py-3 customerWrap bg-white">
        <!--<a href="{{ route('customers') }}" data-key="record_url" data-value="{{ url()->current() }}" class="theStorageTrigger flex justify-between items-center cursor-pointer customerBlock"> -->
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer customerBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Customer</div>
                <div class="theDesc">Click here to select a customer</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
            <input type="hidden" id="customer_id" name="customer_id" value="0" class="theId"/>
        </a>
    </div>
    <div class="px-2 py-3 mt-2 customerAddressWrap bg-white" style="display: none;">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer customerAddressBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Billing Address</div>
                <div class="theDesc">Click here to add customer billing address</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"></span>
            <input type="hidden" name="customer_address_id" value="0" class="theId"/>
        </a>
    </div>
    <div class="px-2 py-3 mt-2 customerPropertyWrap bg-white" style="display: none;">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer customerPropertyBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Address</div>
                <div class="theDesc">Click here to add job address</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
            <input type="hidden" name="customer_property_id" value="0" class="theId"/>
        </a>
    </div>
</div>