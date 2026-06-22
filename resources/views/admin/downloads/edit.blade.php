@extends('admin.layouts.app')

@section('title', 'Edit Surat')
@section('page_title', 'Edit Surat')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.downloads.update', $download) }}" enctype="multipart/form-data">
            @method('PUT')
            @php($submitLabel = 'Update Surat')
            @include('admin.downloads._form')
        </form>
    </div>
</div>
@endsection
