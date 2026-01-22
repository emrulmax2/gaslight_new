<!DOCTYPE html>
<html
    class="opacity-0"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities."
    >
    <meta name="keywords" content="admin template, midone Admin Template, dashboard template, flat admin template, responsive admin template, web app"
    >
    <meta name="author" content="LCC Team">
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/logo_icon.png') }}">
    
    @yield('head')

    <!-- BEGIN: CSS Assets-->
    @stack('styles')
    <!-- END: CSS Assets-->

    @vite('resources/css/app.css')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API', 'YOUR_API_KEY') }}&libraries=places"></script>
    <script src="https://cdn.getaddress.io/scripts/getaddress-autocomplete-3.1.6.js"></script>
    
    @routes
</head>
<!-- END: Head -->

<body>
    {{--<x-theme-switcher />--}}

    @yield('content')

    <!-- BEGIN: Vendor JS Assets-->
    @vite('resources/js/vendors/dom.js')
    @vite('resources/js/vendors/tailwind-merge.js')
    @stack('vendors')
    <!-- END: Vendor JS Assets-->

    <!-- BEGIN: Pages, layouts, components JS Assets-->
    @vite('resources/js/components/base/theme-color.js')
    @stack('scripts')
    <!-- END: Pages, layouts, components JS Assets-->
</body>

</html>
