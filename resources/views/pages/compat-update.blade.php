@extends('layouts.app')

@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Add new compatibility Statement</h2>
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
                <form name="update_compatibility_item" method="post" action="{{url('admin/compatibility/update'.'/'.$compatibility->id)}}">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">
                        <div class="form-group m-b-30 fg-toggled"
                             ng-class="{ 'has-error' : (update_compatibility_item.nameShort.$invalid && !update_compatibility_item.nameShort.$pristine) || update_compatibility_item.nameShort.$touched && update_compatibility_item.nameShort.$invalid}">
                            <div class="fg-line">
                                <label class="fg-label">Short Reference Name</label>
                                <input type="text" name="nameShort" ng-model="nameShort"
                                       class="form-control fg-input"
                                       ng-minlength="3" ng-maxlength="25" ng-init="nameShort='{{ $compatibility->name_short}}'">
                            </div>
                            <div ng-messages="update_compatibility_item.nameShort.$error" ng-show="update_compatibility_item.nameShort.$dirty">
                                <small class="help-block" ng-message="minlength">This too short</small>
                                <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                            </div>
                        </div>

                        <div class="form-group fg-toggled m-b-30" ng-class="{ 'has-error' : (update_compatibility_item.compText.$invalid && !update_compatibility_item.compText.$pristine) || update_compatibility_item.compText.$touched && update_compatibility_item.compText.$invalid}">
                            <div class="fg-line">
                                <label class="fg-label" style="padding-bottom:10px;">Compatability Text</label>
                                <textarea class="form-control" rows="5" name="compText" ng-model="compText" ng-init="compText='{{ $compatibility->comp_text}}'"></textarea>
                            </div>
                            <div ng-messages="update_compatibility_item.compText.$error" ng-show="update_compatibility_item.compText.$dirty">
                                <small class="help-block" ng-message="maxlength">Try and keep it less than 2,000 characters</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Update</button>
                            </div>
                        </div>
                    </div><!--end card body -->
                </form>
            </div>
        </div>
    </section>
@endsection