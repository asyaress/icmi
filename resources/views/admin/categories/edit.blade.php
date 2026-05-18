@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('page_title', 'Edit Kategori')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @method('PUT')
            @php($submitLabel = 'Update Kategori')
            @include('admin.categories._form')
        </form>
    </div>
</div>
@endsection

