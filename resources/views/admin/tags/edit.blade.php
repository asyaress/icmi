@extends('admin.layouts.app')

@section('title', 'Edit Tag')
@section('page_title', 'Edit Tag')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
            @method('PUT')
            @php($submitLabel = 'Update Tag')
            @include('admin.tags._form')
        </form>
    </div>
</div>
@endsection

