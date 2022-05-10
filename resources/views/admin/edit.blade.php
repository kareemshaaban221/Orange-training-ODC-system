@extends('layouts.app', ['title' => 'edit admin'])

@section('content')
<div class="container-fluid">

    <div class="col-lg-6 m-auto col-12 p-0">
        <div class="p-5">

            @if(Session::has('err'))
                <div class="alert alert-danger">
                    {{Session::get('err')}}
                </div>
            @endif

            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Edit Admin | {{ucwords($admin->name)}}</h1>
            </div>

            <form class="user" action="{{route('admins.update', $admin->id)}}" method="POST">
                @csrf
                {{method_field('PUT')}}
                <div class="form-group">
                    <input type="text" class="form-control form-control-user"
                        id="exampleInputName"
                        placeholder="Enter Name..." name="name" value="{{$admin->name}}">

                    @error('name')
                        <small class="text-danger">* {{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="email" class="form-control form-control-user"
                        id="exampleInputEmail" aria-describedby="emailHelp"
                        placeholder="Enter Email Address..." name="email" value="{{$admin->email}}">

                    @error('email')
                        <small class="text-danger">* {{$message}}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Confirm
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
