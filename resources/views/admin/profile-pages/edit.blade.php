@extends('admin.layouts.app')

@section('title', 'Edit Halaman Profil - Admin ICMI Kaltim')
@section('page_title', 'Edit Halaman Profil')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.profile-pages.update', $page) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.profile-pages._form', ['submitLabel' => 'Update Halaman'])
        </form>
    </div>
</div>
@endsection
