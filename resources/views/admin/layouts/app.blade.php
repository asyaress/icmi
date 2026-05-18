<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin ICMI Kaltim')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        body { background: #f7f7f7; }
        .admin-shell { min-height: 100vh; }
        .sidebar {
            background: #111;
            color: #fff;
            min-height: 100vh;
            padding: 1.25rem 1rem;
        }
        .sidebar .brand {
            color: #f4c400;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .55rem;
        }
        .sidebar .brand-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            border-radius: 8px;
            background: #fff;
            padding: 3px;
        }
        .sidebar .brand-text {
            line-height: 1.15;
            font-size: .92rem;
        }
        .sidebar .nav-link {
            color: #e5e7eb;
            border-radius: .5rem;
            margin-bottom: .4rem;
            padding: .55rem .75rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #f4c400;
            color: #111;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }
        .content-wrap { padding: 1.25rem; }
        .card { border: 1px solid #e5e7eb; }
        .btn-primary {
            background: #f4c400;
            border-color: #d6ab00;
            color: #111;
            font-weight: 600;
        }
        .btn-primary:hover { background: #d6ab00; border-color: #b18f00; color: #111; }
    </style>
    @stack('head')
</head>
<body>
<div class="container-fluid admin-shell">
    <div class="row">
        <aside class="col-md-3 col-lg-2 sidebar">
            <a class="brand d-inline-block mb-4" href="{{ route('admin.dashboard') }}">
                <img class="brand-logo" src="{{ asset('logo-icmi.png') }}" alt="Logo ICMI Kaltim">
                <span class="brand-text">ICMI Kaltim Admin</span>
            </a>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">Berita</a>
                <a class="nav-link {{ request()->routeIs('admin.opinions.*') ? 'active' : '' }}" href="{{ route('admin.opinions.index') }}">Opini & Tokoh</a>
                <a class="nav-link {{ request()->routeIs('admin.media-info.*') ? 'active' : '' }}" href="{{ route('admin.media-info.index') }}">Info Media</a>
                <a class="nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}" href="{{ route('admin.galleries.index') }}">Galeri</a>
                <a class="nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">ICMI TV</a>
                <a class="nav-link {{ request()->routeIs('admin.media-manager.*') ? 'active' : '' }}" href="{{ route('admin.media-manager.index') }}">Media Manager</a>
                <a class="nav-link {{ request()->routeIs('admin.profile-pages.*') ? 'active' : '' }}" href="{{ route('admin.profile-pages.index') }}">Profil Sekilas ICMI</a>
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Kategori</a>
                <a class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">Tag</a>
                <a class="nav-link {{ request()->routeIs('admin.settings.home.*') ? 'active' : '' }}" href="{{ route('admin.settings.home.edit') }}">Setting Home</a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users Admin</a>
                <a class="nav-link" href="{{ route('home') }}" target="_blank">Lihat Website</a>
            </nav>
        </aside>
        <main class="col-md-9 col-lg-10 px-0">
            <div class="topbar d-flex justify-content-between align-items-center px-3 py-2">
                <h1 class="h5 m-0">@yield('page_title', 'Dashboard')</h1>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">{{ auth()->user()->name ?? '' }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-dark">Logout</button>
                    </form>
                </div>
            </div>
            <div class="content-wrap">
                @include('admin.partials.alerts')
                @yield('content')
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
@stack('scripts')
</body>
</html>
