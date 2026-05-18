@extends('admin.layouts.app')

@section('title', 'Tambah User Admin')
@section('page_title', 'Tambah User Admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @php($submitLabel = 'Simpan User')
            @include('admin.users._form')
        </form>
    </div>
</div>
@endsection
