@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('page_title', 'Tambah Kategori')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @php($submitLabel = 'Simpan Kategori')
            @include('admin.categories._form')
        </form>
    </div>
</div>
@endsection

