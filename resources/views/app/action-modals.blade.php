<!-- BEGIN: Success Modal Content -->
<x-base.dialog id="successModal" class="max-w-full">
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-success" icon="CheckCircle" />
            <div class="mt-5 text-3xl successModalTitle">Good job!</div>
            <div class="mt-2 text-slate-500 successModalDesc">You clicked the button! </div>
        </div>
        <div class="px-5 pb-8 text-center">
            <x-base.button class="w-24 agreeWith" data-action="NONE" data-redirect="" type="button" variant="primary">Ok</x-base.button>
        </div>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Success Modal Content -->

<!-- BEGIN: Warning Modal Content -->
<x-base.dialog id="warningModal" class="max-w-full">
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="AlertOctagon" />
            <div class="mt-5 text-3xl warningModalTitle">Good job!</div>
            <div class="mt-2 text-slate-500 warningModalDesc">You clicked the button! </div>
        </div>
        <div class="px-5 pb-8 text-center">
            <x-base.button class="w-24" data-tw-dismiss="modal" type="button" variant="primary">Ok</x-base.button>
        </div>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Warning Modal Content -->

<!-- BEGIN: Confirm Modal Content -->
<x-base.dialog id="confirmModal" class="max-w-full">
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="AlertOctagon" />
            <div class="mt-5 text-3xl confirmModalTitle">Are you sure?</div>
            <div class="mt-2 text-slate-500 confirmModalDesc">
                Do you really want to delete these records? <br>
                This process cannot be undone.
            </div>
        </div>
        <div class="px-5 pb-8 text-center">
            <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button" variant="outline-secondary">Cancel</x-base.button>
            <x-base.button class="w-auto agreeWith" type="button" variant="danger" >Yes, I agree</x-base.button>
        </div>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Confirm Modal Content -->