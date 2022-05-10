@extends('layouts.app', ['title' => 'admins'])

@section('content')
@if (Session::has('done'))
    <div class="alert alert-success text-center m-2">{{Session::get('done')}}</div>
@endif

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admins</h1>
    </div>

    <table class="table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        @foreach ($admins as $admin)
            <tr>
                <td><a href="{{route('admins.edit', $admin->id)}}">{{$admin->name}}</a></td>
                <td>{{$admin->email}}</td>
                <td>{{$admin->role}}</td>
                <td>
                    <form action="{{route('admins.destroy', $admin->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-window-close" title="Delete"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

</div>
@endsection
