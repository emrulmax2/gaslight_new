@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Create Certificate</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box mt-5">
        @if($forms->isNotEmpty())
            @foreach($forms as $form)
                <div class="box mb-0 shadow-none rounded-none border-none">
                    <div class="flex flex-col items-center bg-slate-100 px-3 py-3 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto font-medium text-dark">
                            {{ $form->name }}
                        </h2>
                    </div>
                    <div>
                        @if(isset($form->childs) && $form->childs->count() > 0)
                            @foreach($form->childs as $child)
                                <a href="{{ route('records.create', $child->id) }}" class="flex w-full border-b border-b-slate-200/60 bg-white px-3 py-2 hover:text-primary justify-start items-center">
                                    <x-base.lucide class="mr-3 h-3 w-3" icon="check-circle" />
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <!-- END: HTML Table Data -->

    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/records/index.js')
@endPushOnce