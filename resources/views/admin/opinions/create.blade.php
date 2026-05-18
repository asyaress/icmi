@extends('admin.layouts.app')

@section('title', 'Tambah Opini/Tokoh')
@section('page_title', 'Tambah Opini/Tokoh')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.opinions.store') }}" enctype="multipart/form-data">
            @php($submitLabel = 'Simpan Opini/Tokoh')
            @include('admin.posts._form')
        </form>
    </div>
</div>
@endsection
