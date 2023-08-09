@extends('layouts.dashboard')

@section('title','Create Admins')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')

    <form action="{{route('dashboard.admins.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        @include('dashboard.admins._form')
    </form>

@stop
