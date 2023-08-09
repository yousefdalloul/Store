@extends('layouts.dashboard')

@section('title','Create Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')

    <form action="{{route('dashboard.roles.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        @include('dashboard.roles._form')
    </form>

@stop
