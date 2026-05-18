@extends('admin.layouts.app')

@section('title', 'Tambah Tag')
@section('page_title', 'Tambah Tag')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.tags.store') }}">
            @php($submitLabel = 'Simpan Tag')
            @include('admin.tags._form')
        </form>
    </div>
</div>
@endsection

