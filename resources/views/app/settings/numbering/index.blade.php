@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium">Certificates, Jobs, Quotes, and Invoices Numbering</h2>
        <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
            <x-base.button as="a" href="{{ route('user.settings') }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                Settings
            </x-base.button>
        </div>
    </div>
    <form method="post" action="#" id="jobFormNumberingForm">
        <div class="mt-5 grid grid-cols-12 gap-5">
            @if($forms->count() > 0)
                <div class="col-span-12 sm:col-span-9">
                    <div class="grid grid-cols-12 gap-y-5 gap-x-4">
                        @foreach($forms as $form)
                            <div class="col-span-12 sm:col-span-3">
                                <div class="intro-y box">
                                    <div class="flex flex-col items-center border-b border-slate-200/60 p-3 dark:border-darkmode-400 sm:flex-row">
                                        <h2 class="mr-auto font-medium">{{ $form->name }}</h2>
                                    </div>
                                    <div class="p-3">
                                        <div>
                                            <x-base.form-input value="{{ (isset($numberings[$form->id]->prefix) && !empty($numberings[$form->id]->prefix) ? $numberings[$form->id]->prefix : '') }}" formInputSize="sm" name="numbering[{{ $form->id }}][prefix]" class="w-full rounded-sm" type="text" placeholder="Prefix (If required)" />
                                        </div>
                                        <div class="mt-2">
                                            <x-base.form-input value="{{ (isset($numberings[$form->id]->starting_from) && !empty($numberings[$form->id]->starting_from) ? $numberings[$form->id]->starting_from : '1') }}" formInputSize="sm" name="numbering[{{ $form->id }}][starting_from]" class="w-full rounded-sm" type="number" placeholder="Start From" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-3">
                    <div class="intro-y box p-5">
                        <x-base.button type="submit" id="saveUserNumberingBtn" class="w-full mb-3 text-white" variant="success" >
                            <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                            Update Settings
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        <x-base.button as="a" href="{{ route('user.settings') }}" class="w-full" variant="danger" >
                            <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                            Cancle
                        </x-base.button>
                    </div>
                </div>
            @else 
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center"><i data-tw-merge data-lucide="alert-triangle" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>Settings will be available Soon.</div>
                    </div>
                </div>
            @endif
        </div>
    </form>

    @include('app.action-modals')
@endsection

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/user-settings/numbering.js')
@endPushOnce