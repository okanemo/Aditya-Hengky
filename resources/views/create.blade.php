@extends('layout.app')

@section('content')
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h2>Add User</h2>
  </div>
  <div class="container">
    @if ($message = Session::get('error'))
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>{{ $message }}</h4>
      </div>
    @endif
   <form method="POST" action="{{ url('/user/create') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group {{ $errors->has('nama') ? 'has-error' : '' }}">
      <label for="name">Name</label>
      <input type="text" class="form-control" name="name" id="name" placeholder="Name">
      <span class="text-danger">{{ $errors->first('nama') }}</span>
    </div>
    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
      <label for="email">Email</label>
      <input type="text" class="form-control" name="email" id="email" placeholder="Email">
      <span class="text-danger">{{ $errors->first('email') }}</span>
    </div>
    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
      <label for="password">Password</label>
      <input type="password" class="form-control" name="password" id="password" placeholder="Password">
      <span class="text-danger">{{ $errors->first('password') }}</span>
    </div>
    <div class="form-group">
      <label for="permission">Permission</label>
      @foreach($permission as $perm)
      <div class="form-check">
        <input type="checkbox" class="form-input" name="permission[]" value="{{ $perm->id }}">
        <label class="form-check-label">{{ $perm->name }}</label>
      </div>
      @endforeach
    </div>
    <div class="form-group">
      <button type="submit" name="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-danger" href="{{ url('/user/list') }}">Cancel</a>
    </div>
   </form>
 </div>
@endsection