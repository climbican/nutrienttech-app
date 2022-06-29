@extends('layouts.app')

@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Add new Crop</h2>
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
                <form name="create_crop_item" method="post" action="{{url('admin/crop/save')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput" style="margin:0px auto 25px auto;">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
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
                                <div class="form-group fg-float m-b-30 fg-toggled"
                                     ng-class="{ 'has-error' : (create_crop_item.cropName.$invalid && !create_crop_item.cropName.$pristine) || create_crop_item.cropName.$touched && create_crop_item.cropName.$invalid}">
                                    <div class="fg-line">
                                        <input type="text" name="cropName" ng-model="cropName"
                                               class="form-control fg-input"
                                               ng-minlength="3" ng-maxlength="25">
                                        <label class="fg-label">Crop Name</label>
                                    </div>
                                    <div ng-messages="create_crop_item.cropName.$error" ng-show="create_crop_item.cropName.$dirty">
                                        <small class="help-block" ng-message="minlength">This too short</small>
                                        <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                                        <small class="help-block" ng-message="required">This field is required</small>
                                    </div>
                                </div>
                                <div class="form-group fg-float m-b-30 fg-toggled"
                                     ng-class="{ 'has-error' : (create_crop_item.subType.$invalid && !create_crop_item.subType.$pristine) || create_crop_item.subType.$touched && create_crop_item.subType.$invalid}">
                                    <div class="fg-line">
                                        <input type="text" name="subType" ng-model="subType"
                                               class="form-control fg-input"
                                               ng-minlength="3" ng-maxlength="25">
                                        <label class="fg-label">Sub Type</label>
                                    </div>
                                    <div ng-messages="create_crop_item.subType.$error" ng-show="create_crop_item.subType.$dirty">
                                        <small class="help-block" ng-message="minlength">This too short</small>
                                        <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                                        <small class="help-block" ng-message="required">This field is required</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Add Crop</button>
                            </div>
                        </div>
                    </div><!--end card body -->
                </form>
            </div>
        </div>
    </section>
@endsection