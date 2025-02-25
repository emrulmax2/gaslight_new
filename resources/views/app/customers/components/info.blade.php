<div class="intro-y box mt-5 px-5 pt-5">
    <div class="-mx-5 flex flex-col border-b border-slate-200/60 pb-5 dark:border-darkmode-400 lg:flex-row relative">
        <x-base.button as="a" href="{{ route('customers.edit', $customer->id) }}" type="button" class="w-[40px] h-[40px] rounded-full p-0 items-center absolute right-5 top-0 text-white" variant="success"><x-base.lucide class="h-4 w-4" icon="Pencil" /></x-base.button>
        <div class="flex flex-1 items-center justify-center px-5 lg:justify-start">
            <div class="relative h-20 w-20 flex-none sm:h-24 sm:w-24 lg:h-32 lg:w-32 items-center justify-center bg-slate-100 border rounded-full inline-flex">
                <x-base.lucide class="h-12 w-12 text-success" icon="User" />
            </div>
            <div class="ml-5">
                <div class="w-24 truncate text-lg font-medium sm:w-40 sm:whitespace-normal">
                    {{ $customer->full_name }}
                </div>
                <div class="text-slate-500">{{ $customer->full_address }}</div>
            </div>
        </div>
        <div
            class="mt-6 flex-1 border-l border-r border-t border-slate-200/60 px-5 pt-5 dark:border-darkmode-400 lg:mt-0 lg:border-t-0 lg:pt-0">
            <div class="text-center font-medium lg:mt-3 lg:text-left">
                Basic Details
            </div>
            <div class="mt-4 flex flex-col items-center justify-center lg:items-start">
                <div class="flex items-center truncate sm:whitespace-normal">
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="check-circle"
                    /> Company Name: 
                    {{ !empty($customer->company_name) ? $customer->company_name : 'N/A' }}
                </div>
                <div class="mt-3 flex items-center truncate sm:whitespace-normal">
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="check-circle"
                    /> VAT No: 
                    {{ !empty($customer->vat_no) ? $customer->vat_no : 'N/A' }}
                </div>
                <div class="mt-3 flex items-center truncate sm:whitespace-normal">
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="check-circle"
                    /> Reminder: 
                    {!! $customer->auto_reminder == 1 ? '<span class="text-success bg-slate-100 px-2 py-0 font-medium ml-2">Yes</span>' : '<span class="text-danger bg-slate-100 px-2 py-0 font-medium ml-2">No</span>' !!}
                </div>
            </div>
        </div>
        <div class="mt-6 flex-1 border-t border-slate-200/60 px-5 pt-5 dark:border-darkmode-400 lg:mt-0 lg:border-0 lg:pt-0">
            <div class="text-center font-medium lg:mt-3 lg:text-left">
                Contact Details
            </div>
            <div class="mt-4 flex flex-col items-center justify-center lg:items-start">
                <div class="flex items-center truncate sm:whitespace-normal">
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="Mail"
                    />
                    {{ isset($customer->contact->email) && !empty($customer->contact->email) ? $customer->contact->email : 'N/A' }}
                </div>
                <div class="mt-3 flex items-center truncate sm:whitespace-normal">
                    <x-base.lucide
                        class="mr-2 h-4 w-4"
                        icon="smartphone"
                    /> Mobile
                    {{ isset($customer->contact->mobile) && !empty($customer->contact->mobile) ? $customer->contact->mobile : 'N/A' }}
                </div>
            </div>
        </div>
    </div>
    @include('app.customers.components.menu')
</div>