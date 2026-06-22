@extends('admin.layouts.app')

@section('title', 'Tambah Surat')
@section('page_title', 'Tambah Surat')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.downloads.store') }}" enctype="multipart/form-data">
            @php($submitLabel = 'Simpan Surat')
            @include('admin.downloads._form')
        </form>
    </div>
</div>
@endsection
