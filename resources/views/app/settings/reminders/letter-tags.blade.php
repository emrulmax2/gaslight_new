<x-base.preview>
    <div class="flex justify-center">
        <x-base.menu>
            <button type="button" class="dropdown-toggle p-0 border-0 bg-white text-primary font-medium underline rounded-0" aria-expanded="false" data-tw-toggle="dropdown">[TAGS]</button>
            <x-base.notification class="flex flex-col sm:flex-row" id="coppiedNodeEl">
                <div class="font-medium">
                    Tag Copied!
                </div>
            </x-base.notification>
            <x-base.menu.items class="w-72 sm:w-96 emailTags overflow-y-auto h-60">
                <x-base.menu.header>Customer Tags</x-base.menu.header>
                <x-base.menu.item>[DATA=customers]first_name[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customers]last_name[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customers]full_name[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customers]company_name[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customers]full_address[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customers]occupant_name[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customers]occupant_email[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customers]occupant_phone[/DATA]</x-base.menu.item>

                <x-base.menu.header>Customer Job Tags</x-base.menu.header>
                <x-base.menu.item>[DATA=customer_jobs]due_date[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customer_jobs]reference_no[/DATA]</x-base.menu.item>
                <x-base.menu.item>[DATA=customer_jobs]estimated_amount[/DATA]</x-base.menu.item>

            </x-base.menu.items>
        </x-base.menu>
    </div>
</x-base.preview>