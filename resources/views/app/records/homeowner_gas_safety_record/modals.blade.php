<!-- BEGIN: Confirm Modal Content -->
<x-base.dialog id="formChangeAlertModal" class="max-w-full">
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-warning" icon="AlertOctagon" />
            <div class="mt-5 text-3xl">Alert!</div>
            <div class="mt-2 text-slate-500">
                You made a couple of changes on this step. Please make sure you want continue or not.
            </div>
        </div>
        <div class="px-5 pb-8 text-center">
            <x-base.button class="mr-1 w-24 stayHereBtn text-white" type="button" variant="danger">Stay here</x-base.button>
            <x-base.button data-nextindex="NONE" data-targetid="NONE" class="w-auto continueBtn text-white" type="button" variant="success" >Ignore changes</x-base.button>
        </div>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Confirm Modal Content -->
 
<!-- BEGIN: Confirm Modal Content -->
<x-base.dialog id="applianceConfirmModal" class="max-w-full">
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-warning" icon="AlertOctagon" />
            <div class="mt-5 text-3xl">Alert!</div>
            <div class="mt-2 text-slate-500">
                Do you want to add more appliances? If yes then click on the agree button and continue.
            </div>
        </div>
        <div class="px-5 pb-8 text-center">
            <x-base.button class="mr-1 w-24 canceleMore text-white" type="button" variant="danger">No, I don't</x-base.button>
            <x-base.button class="w-auto agreeMore text-white" type="button" variant="success" >Yes, I agree</x-base.button>
        </div>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Confirm Modal Content -->