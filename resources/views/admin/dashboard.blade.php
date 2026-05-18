@extends('admin.layouts.app')

@section('title', 'Dashboard Admin ICMI Kaltim')
@section('page_title', 'Dashboard')

@section('content')
<div class="row g-3">
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total User Admin</p>
                <h3 class="mb-0">{{ $userCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">User Aktif</p>
                <h3 class="mb-0">{{ $activeUsersCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Role</p>
                <h3 class="mb-0">{{ $roleCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Permission</p>
                <h3 class="mb-0">{{ $permissionCount }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Berita</p>
                <h3 class="mb-0">{{ $newsCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Opini & Tokoh</p>
                <h3 class="mb-0">{{ $opinionCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Info Media</p>
                <h3 class="mb-0">{{ $mediaInfoCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Galeri</p>
                <h3 class="mb-0">{{ $galleryCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Video ICMI TV</p>
                <h3 class="mb-0">{{ $videoCount }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h2 class="h6">Progress Sprint 1-4</h2>
        <ul class="mb-0">
            <li>Auth admin aktif.</li>
            <li>Role dan permission dasar aktif.</li>
            <li>CRUD user admin tersedia.</li>
            <li>CRUD konten Berita, Opini & Tokoh, Info Media tersedia.</li>
            <li>CRUD Galeri dan ICMI TV aktif (multi-image + embed YouTube).</li>
        </ul>
    </div>
</div>
@endsection
