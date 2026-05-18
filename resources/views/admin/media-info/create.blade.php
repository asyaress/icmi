@extends('admin.layouts.app')

@section('title', 'Tambah Info Media')
@section('page_title', 'Tambah Info Media')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.media-info.store') }}" enctype="multipart/form-data">
            @php($submitLabel = 'Simpan Info Media')
            @include('admin.posts._form')
        </form>
    </div>
</div>
@endsection
