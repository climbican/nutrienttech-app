@extends('layouts.app')

@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Add new compatibility Statement</h2>
            </div>
            <div class="card" ng-controller="ProfileCtrl">
                <form name="profile_change_passord" method="post" action="{{url('admin/profile/update'.'/'.$user->id)}}">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">
                        <div class="form-group m-b-30">
                            <div class="fg-line">
                                <div style="font-size:1.2em;"> {{$user->name}}</div>
                                <label class="fg-label">Name</label>
                            </div>
                        </div>
                        <div class="form-group m-b-30">
                            <div class="fg-line">
                                <div style="font-size:1.2em;">{{$user->email}}</div>
                                <label class="fg-label">Email</label>
                            </div>
                        </div>
                        <div class="form-group m-b-30 fg-toggled">
                            <div class="fg-line">
                                <input type="text" name="currentPassword" ng-model="currentPassword"
                                       class="form-control fg-input">
                                <label class="fg-label">Current Password</label>
                            </div>
                        </div>
                        <div class="form-group m-b-30 fg-toggled">
                            <div class="fg-line">
                                <input type="password" name="newPassword" ng-model="newPassword"
                                       class="form-control fg-input">
                                <label class="fg-label">New Password</label>
                            </div>
                        </div>
                        <div class="form-group m-b-30 fg-toggled">
                            <div class="fg-line">
                                <input type="password" name="confirmPassword" ng-model="confirmPassword"
                                       class="form-control fg-input" ng-match="newPassword">
                                <label class="fg-label">Confirm Password</label>
                            </div>
                            <div ng-messages="profile_change_passord.confirmPassword.$error" ng-show="profile_change_passord.confirmPassword.$dirty">
                                <small class="help-block" ng-message="minlength">This too short</small>
                                <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Update Password</button>
                            </div>
                        </div>
                    </div><!--end card body -->
                </form>
            </div>
        </div>
    </section>
@endsection