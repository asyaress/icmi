@extends('admin.layouts.app')

@section('title', 'Edit User Admin')
@section('page_title', 'Edit User Admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @method('PUT')
            @php($submitLabel = 'Update User')
            @include('admin.users._form')
        </form>
    </div>
</div>
@endsection
