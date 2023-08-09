@extends('layouts.dashboard')

@section('title','Admins')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Admins</li>
    <li class="breadcrumb-item active">Edit Admins</li>
@endsection

@section('content')

    <form action="{{route('dashboard.admins.update',$admin->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('dashboard.admins._form',[
            'button_label' => 'Upload'
        ])
    </form>
@stop
