@extends('layout.app')

@section('content')
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h2>User</h2>
  </div>
  @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-check"></i>{{ $message }}</h4>
    </div>
  @endif
  <div class="table-responsive">
    @if (in_array('user.create', $role))
    <a href="{{ url('/user/create') }}">
      <button class="btn btn-success" type="button">
        <i class="icon-plus"> Add User</i>
      </button>
    </a>
    @endif
    <table class="table table-bordered" style="margin-top:10px" id="bahan">
      <thead>
        <tr>
          <td>Nama</td>
          <td>Email</td>
          <td width="30%">Aksi</td>
        </tr>
      </thead>
      @foreach($users as $user)
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @if (in_array('user.edit', $role))
              <a href="{{ url('user/edit/'. $user->id) }}"><button class="btn btn-primary" type ="button">Edit</button></a>&nbsp;
            @endif
            @if (in_array('user.delete', $role))
              <a href="{{ url('user/delete/'. $user->id) }}" onclick="return confirm('Are you sure to delete this?');"><button class="btn btn-danger" type ="button">Delete</button></a>
            @endif  
        </td>
      </tr>
      @endforeach
    </table>
  </div>
@endsection