<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.page.head')
</head>
<body class="@yield('body_class', 'gray-bg bg-2')">

    @include('partials.page.offcanvas')

    @include('partials.page.search')

    @php($topHeaderCoverClass = trim($__env->yieldContent('top_header_cover_class')) ?: 'bg_cover')
    @include('partials.page.top-header', ['topHeaderCoverClass' => $topHeaderCoverClass])

    @include('partials.page.header')

    @yield('content')

    @include('partials.page.footer')

    @include('partials.page.scripts')

</body>
</html>
