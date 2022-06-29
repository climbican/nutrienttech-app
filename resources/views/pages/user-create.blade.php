@extends('layouts.app')
@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Register New User</h2>
            </div>
            <!-- error messages -->
            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <form name="create_new_user" method="post" action="{{url('/admin/profile/save')}}">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">
                        <!-- ASSOCIATED ELEMENT -->
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group fg-float m-b-30 fg-toggled"
                                     ng-class="{'has-error' : (create_new_user.nameShort.$invalid && !create_new_user.nameShort.$pristine) || create_new_user.nameShort.$touched && create_new_user.nameShort.$invalid}">
                                    <div class="fg-line">
                                        <input id="userName" type="text" class="form-control fg-input" name="userName" value="{{ old('userName') }}">
                                        <label class="fg-label">Name</label>
                                        @if ($errors->has('userName'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('userName') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group fg-float m-b-30 fg-toggled"
                                     ng-class="{'has-error' : (create_new_user.nameShort.$invalid && !create_new_user.nameShort.$pristine) || create_new_user.nameShort.$touched && create_new_user.nameShort.$invalid}">
                                    <div class="fg-line">
                                        <input id="email" type="email" class="form-control fg-input" name="email" value="{{ old('email') }}">
                                        <label for="email" class="fg-label">E-Mail Address</label>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:25px;">
                            <div class="form-group m-b-30">
                                <div class="col-md-8">
                                    <select chosen
                                            ng-model="userType"
                                            name="userType"
                                            data-placeholder="Select a User Type..." class="w-100" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group fg-float m-b-30 fg-toggled"
                                     ng-class="{'has-error' : (create_new_user.password.$invalid && !create_new_user.password.$pristine) || create_new_user.password.$touched && create_new_user.password.$invalid}">
                                    <div class="fg-line">
                                        <input id="password" type="password" class="form-control" name="password">
                                        <label for="email" class="fg-label">Password</label>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group fg-float m-b-30 fg-toggled {{ $errors->has('confirmPassword') ? ' has-error' : '' }}" >
                                    <div class="fg-line">
                                        <input id="confirmPassword" type="password" class="form-control" name="confirmPassword">
                                        <label for="confirmPassword" class="fg-label">Password</label>
                                    </div>
                                    @if ($errors->has('confirmPassword'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('confirmPassword') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Add User</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--end card body -->
        </div>
    </section>
@endsection