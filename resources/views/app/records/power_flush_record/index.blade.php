<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
    <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
        <h2 class="mr-auto text-base font-medium">
            Powerflush Checklist
        </h2>
    </div>
    <div class="px-2 py-3 pwChecklistWrap bg-white">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer pwChecklistBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Question Answered</div>
                <div class="theDesc">0/45</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
        </a>
    </div>
</div>

<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
    <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
        <h2 class="mr-auto text-base font-medium">
            Radiators
        </h2>
    </div>
    <div class="px-2 py-3 radiatorWrap bg-white">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer radiatorBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Number of Radiators</div>
                <div class="theDesc">0</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"></span>
        </a>
    </div>
    <div class="allRadiatorsWrap" style="display: none;"></div>
    <a href="javascript:void(0);" class="addRadiatorBtn text-primary flex justify-between items-center p-3 bg-white mt-2 text-base">Add Radiator<x-base.lucide class="ml-auto h-4 w-4" icon="plus-circle" /></a>
</div>