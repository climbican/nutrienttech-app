@extends('layouts.app')

@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Update Crop</h2>
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <form name="update_crop_item" method="post" action="{{url('admin/crop/update/'.$crop->id)}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput" style="margin:0px auto 25px auto;">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"><img src="{{url('images/crop/'.$crop->image_url)}}" style="margin-top:1px;"/></div>
                                    <div>
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" name="cropImage" ng-model="cropImage" id="cropImage">
                                        </span>
                                        <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group m-b-30 fg-toggled"
                                     ng-class="{ 'has-error' : (update_crop_item.cropName.$invalid && !update_crop_item.cropName.$pristine) || update_crop_item.cropName.$touched && update_crop_item.cropName.$invalid}">
                                    <div class="fg-line">
                                        <label class="fg-label">Crop Name</label>
                                        <input type="text" name="cropName" ng-model="cropName"
                                               class="form-control fg-input"
                                               ng-minlength="3" ng-maxlength="25"
                                               ng-init="cropName='{{ $crop->name}}'"
                                               required>
                                    </div>
                                    <div ng-messages="update_crop_item.cropName.$error" ng-show="update_crop_item.cropName.$dirty">
                                        <small class="help-block" ng-message="minlength">This too short</small>
                                        <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                                        <small class="help-block" ng-message="required">This field is required</small>
                                    </div>
                                </div>
                                <div class="form-group m-b-30 fg-toggled"
                                     ng-class="{ 'has-error' : (update_crop_item.subType.$invalid && !update_crop_item.subType.$pristine) || update_crop_item.subType.$touched && update_crop_item.subType.$invalid}">
                                    <div class="fg-line">
                                        <label class="fg-label">Sub Type {{$crop->sub_type}}</label>
                                        <input type="text" name="subType" ng-model="subType"
                                               class="form-control fg-input"
                                               ng-minlength="3" ng-maxlength="25"
                                               ng-init="subType='{{$crop->sub_type}}'">
                                    </div>
                                    <div ng-messages="update_crop_item.subType.$error" ng-show="update_crop_item.subType.$dirty">
                                        <small class="help-block" ng-message="minlength">This too short</small>
                                        <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Save changes</button>
                            </div>
                        </div>
                    </div><!--end card body -->
                </form>
            </div>
        </div>
    </section>
@endsection