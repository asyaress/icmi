@extends('admin.layouts.app')

@section('title', 'Tambah Video')
@section('page_title', 'Tambah Video')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
            @php($submitLabel = 'Simpan Video')
            @include('admin.videos._form')
        </form>
    </div>
</div>
@endsection
