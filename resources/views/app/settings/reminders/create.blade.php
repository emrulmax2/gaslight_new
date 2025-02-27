@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $form->name }} Email</h2>
        <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
            <x-base.button as="a" href="{{ route('user.settings.reminder.templates') }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                Back To List
            </x-base.button>
        </div>
    </div>
    <div class="mt-5 grid grid-cols-12 gap-5">
        <div class="col-span-12 sm:col-span-9">
            <div class="intro-y box">
                <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                    <h2 class="mr-auto text-base font-medium">
                        {{ $form->name }} Email<br/>
                        <span class="text-slate-500 text-xs font-normal">This is the template which is used each time an invoice is sent to the customer</span>
                    </h2>
                </div>
                <div class="p-5">

                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-3">
            <div class="intro-y box p-5">
                <x-base.button type="submit" id="saveUserNumberingBtn" class="w-full mb-3 text-white" variant="success" >
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <x-base.button as="a" href="{{ route('user.settings') }}" class="w-full" variant="danger" >
                    <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                    Cancle
                </x-base.button>
            </div>
        </div>
    </div>

    @include('app.action-modals')
@endsection

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
@endPushOnce

@pushOnce('scripts')
    
@endPushOnce