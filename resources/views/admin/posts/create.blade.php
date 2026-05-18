@extends('admin.layouts.app')

@section('title', 'Tambah Berita')
@section('page_title', 'Tambah Berita')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
            @php($submitLabel = 'Simpan Berita')
            @include('admin.posts._form')
        </form>
    </div>
</div>
@endsection

