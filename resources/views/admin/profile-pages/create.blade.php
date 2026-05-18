@extends('admin.layouts.app')

@section('title', 'Tambah Halaman Profil - Admin ICMI Kaltim')
@section('page_title', 'Tambah Halaman Profil')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.profile-pages.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.profile-pages._form', ['submitLabel' => 'Simpan Halaman'])
        </form>
    </div>
</div>
@endsection
