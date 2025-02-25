<!-- BEGIN: Add Modal Content -->
<x-base.dialog id="add-invoice-modal" size="xl" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">
                Line Item
            </h2>
            <x-base.button
                class="p-0 addInvoiceModalHide"
                variant="outline-secondary"
            >
            <x-base.lucide
            class="h-8 w-8 text-slate-400"
            icon="X"
        />
            </x-base.button>
        </x-base.dialog.title>
        <x-base.dialog.description class="grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12 sm:col-span-6">
                <x-base.form-label for="description">Description</x-base.form-label>
                <textarea name="add_description" id="description" class="w-full transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 " id="" rows="10"></textarea>
            </div>
            <div class="col-span-12 sm:col-span-6 flex gap-3">
                <div class="">
                    <x-base.form-label for="units">Units</x-base.form-label>
                    <x-base.form-input
                        id="units"
                        name="add_units"
                        type="text"
                        placeholder="units"
                    />
                </div>
                <div>
                    <x-base.form-label for="price">Price</x-base.form-label>
                    <x-base.form-input
                        id="price"
                        name="add_price"
                        type="text"
                        placeholder="price"
                    />
                </div>
                <div class="addInvoiceVatField">
                    <x-base.form-label for="vat">Vat</x-base.form-label>
                    <x-base.form-input
                        id="vat"
                        name="add_vat"
                        type="text"
                        placeholder="vat"
                    />
                </div>
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer>
            <x-base.button
                class="mr-1 w-20 addInvoiceModalHide"
                type="button"
                variant="outline-secondary"
            >
                Cancel
            </x-base.button>
            <x-base.button
                class="w-20 AddInvoiceItemBtn"
                type="button"
                variant="primary"
            >
                Add
            </x-base.button>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Add Modal Content -->
<!-- BEGIN: Edit Modal Content -->
<x-base.dialog id="edit-invoice-modal" size="xl" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">
                Line Item
            </h2>
            <x-base.button
                class="p-0 editInvoiceModalHide"
                variant="outline-secondary"
            >
            <x-base.lucide
            class="h-8 w-8 text-slate-400"
            icon="X"
        />
            </x-base.button>
        </x-base.dialog.title>
        <x-base.dialog.description class="grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12 sm:col-span-6">
                <x-base.form-label for="description">Description</x-base.form-label>
                <textarea name="edit_description" id="description" class="w-full transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 " id="" rows="10"></textarea>
            </div>
            <div class="col-span-12 sm:col-span-6 flex gap-3">
                <div class="">
                    <x-base.form-label for="units">Units</x-base.form-label>
                    <x-base.form-input
                        id="units"
                        name="edit_units"
                        type="text"
                        placeholder="units"
                    />
                </div>
                <div>
                    <x-base.form-label for="price">Price</x-base.form-label>
                    <x-base.form-input
                        id="price"
                        name="edit_price"
                        type="text"
                        placeholder="price"
                    />
                </div>
                <div class="editInvoiceVatField">
                    <x-base.form-label for="vat">Vat</x-base.form-label>
                    <x-base.form-input
                        id="vat"
                        name="edit_vat"
                        type="text"
                        placeholder="vat"
                    />
                </div>
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer>
            <x-base.button
                class="mr-1 w-20 editInvoiceModalHide"
                type="button"
                variant="outline-secondary"
            >
                Cancel
            </x-base.button>
            <x-base.button
                class="w-20 updateInvoiceItemBtn"
                type="button"
                variant="primary"
            >
                Update
            </x-base.button>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Edit Modal Content -->