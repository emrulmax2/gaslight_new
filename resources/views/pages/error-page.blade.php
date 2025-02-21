@extends('../themes/base')

@section('head')
    <title>Error Page - Midone - Tailwind Admin Dashboard Template</title>
@endsection

@section('content')
    <div class="py-2 bg-gradient-to-b from-theme-1 to-theme-2 dark:from-darkmode-800 dark:to-darkmode-800">
        <div class="container">
            <!-- BEGIN: Error Page -->
            <div class="flex flex-col items-center justify-center h-screen text-center error-page lg:flex-row lg:text-left">
                <div class="-intro-x lg:mr-20">
                    <img
                        class="h-48 w-[450px] lg:h-auto"
                        src="{{ Vite::asset('resources/images/error-illustration.svg') }}"
                        alt="Midone - Tailwind Admin Dashboard Template"
                    />
                </div>
                <div class="mt-10 text-white lg:mt-0">
                    <div class="font-medium intro-x text-8xl">404</div>
                    <div class="mt-5 text-xl font-medium intro-x lg:text-3xl">
                        Oops. This page has gone missing.
                    </div>
                    <div class="mt-3 text-lg intro-x">
                        You may have mistyped the address or the page may have moved.
                    </div>
                    <x-base.button
                        class="px-4 py-3 mt-10 text-white border-white intro-x dark:border-darkmode-400 dark:text-slate-200"
                    >
                        Back to Home
                    </x-base.button>
                </div>
            </div>
            <!-- END: Error Page -->
        </div>
    </div>
@endsection
