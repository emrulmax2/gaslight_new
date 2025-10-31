@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>User Settings</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $subtitle }}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('superadmin.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: Settings Page Content -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
            <!-- BEGIN: Profile Info -->
            @include('app.superadmin.settings.sidebar')
            <!-- END: Profile Info -->
        </div>

        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
            <!-- BEGIN: Display Information -->
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-5">
                @if($forms->count() > 0)
                    @foreach($forms as $form)
                        <div class="grid_columns">
                            <div class="intro-y box p-5 h-[180px]">
                                <h2 class="mr-auto font-medium mb-1">{{ $form->name }} Email</h2>
                                <div class="mb-5">
                                    Email template when sending {{ $form->name }}.
                                </div>
                                <x-base.button size="sm" as="a" href="{{ route('superadmin.site.setting.email.template.create', $form->id) }}" class="shadow-md absolute right-5 left-auto bottom-5" variant="linkedin" >
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
        </div>
    </div>
    <!-- END: Settings Page Content -->

    @include('app.superadmin.settings.cancel-reason.index-modals')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
@endPushOnce

@pushOnce('vendors')
@endPushOnce

@pushOnce('scripts')
@endPushOnce