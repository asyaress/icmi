@extends('admin.layouts.app')

@section('title', 'Edit Video')
@section('page_title', 'Edit Video')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data">
            @method('PUT')
            @php($submitLabel = 'Update Video')
            @include('admin.videos._form')
        </form>
    </div>
</div>
@endsection
