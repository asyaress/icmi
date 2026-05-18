@extends('admin.layouts.app')

@section('title', 'Edit Berita')
@section('page_title', 'Edit Berita')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
            @method('PUT')
            @php($submitLabel = 'Update Berita')
            @include('admin.posts._form')
        </form>
    </div>
</div>
@endsection

