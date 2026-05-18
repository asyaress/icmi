@extends('admin.layouts.app')

@section('title', 'Edit Galeri')
@section('page_title', 'Edit Galeri')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data">
            @method('PUT')
            @php($submitLabel = 'Update Galeri')
            @include('admin.galleries._form')
        </form>
    </div>
</div>
@endsection
