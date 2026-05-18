@extends('admin.layouts.app')

@section('title', 'Edit Opini/Tokoh')
@section('page_title', 'Edit Opini/Tokoh')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.opinions.update', $post) }}" enctype="multipart/form-data">
            @method('PUT')
            @php($submitLabel = 'Update Opini/Tokoh')
            @include('admin.posts._form')
        </form>
    </div>
</div>
@endsection
