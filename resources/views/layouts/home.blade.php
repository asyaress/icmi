<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.home.head')
</head>
<body>

    @include('partials.shared.page-loader')

    @include('partials.home.offcanvas')

    @include('partials.home.search')

    @include('partials.home.top-header')

    @include('partials.home.header')

    @yield('content')

    @include('partials.home.footer')

    @include('partials.home.scripts')

</body>
</html>
