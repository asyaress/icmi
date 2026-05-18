@extends('admin.layouts.app')

@section('title', 'Tambah Galeri')
@section('page_title', 'Tambah Galeri')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
            @php($submitLabel = 'Simpan Galeri')
            @include('admin.galleries._form')
        </form>
    </div>
</div>
@endsection
