@extends('admin.layouts.app')

@section('title', 'Edit Info Media')
@section('page_title', 'Edit Info Media')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.media-info.update', $post) }}" enctype="multipart/form-data">
            @method('PUT')
            @php($submitLabel = 'Update Info Media')
            @include('admin.posts._form')
        </form>
    </div>
</div>
@endsection
