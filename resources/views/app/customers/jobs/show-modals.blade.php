
<!-- BEGIN: Job Action List Modal Content -->
<x-base.dialog id="jobActionsListModal" staticBackdrop size="lg">
    <x-base.dialog.panel>
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">Upload Documents</h2>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="modal-body">
            
        </x-base.dialog.description>
        <x-base.dialog.footer>
            <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
            <x-base.button class="w-auto" id="uploadJobDocumentsBtn" type="button" variant="primary">
                <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                Upload
                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
            </x-base.button>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->