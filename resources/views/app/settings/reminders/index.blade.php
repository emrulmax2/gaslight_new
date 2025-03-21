@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center justify-between">
        <h2 class="text-lg font-medium">Service Reminders and Email Templates</h2>
        <div class="flex">
            <x-base.button as="a" href="{{ route('user.settings') }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                Settings
            </x-base.button>
        </div>
    </div>
    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @if($forms->count() > 0)
            @foreach($forms as $form)
                <div class="grid_columns">
                    <div class="intro-y box p-5 h-[180px]">
                        <h2 class="mr-auto font-medium mb-1">{{ $form->name }} Email</h2>
                        <div class="mb-5">
                            Email template when sending {{ $form->name }}.
                        </div>
                        <x-base.button size="sm" as="a" href="{{ route('user.settings.reminder.templates.create', $form->id) }}" class="shadow-md absolute right-5 left-auto bottom-5" variant="linkedin" >
                            <x-base.lucide class="mr-2 h-3 w-3" icon="Pencil" />
                            Edit
                        </x-base.button>
                    </div>
                </div>
            @endforeach
        @else 
            <div class="col-span-12 intro-y">
                <div class="box p-5">
                    <div role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center"><i data-tw-merge data-lucide="alert-triangle" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>Settings will be available Soon.</div>
                </div>
            </div>
        @endif
    </div>

    @include('app.action-modals')
@endsection

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
@endPushOnce

@pushOnce('scripts')
    
@endPushOnce