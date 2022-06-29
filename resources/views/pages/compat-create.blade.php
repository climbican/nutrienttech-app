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
                <form name="create_compatibility_item" method="post" action="{{url('')}}/admin/compatibility/save">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">
                        <div class="form-group fg-float m-b-30 fg-toggled"
                             ng-class="{ 'has-error' : (create_compatibility_item.nameShort.$invalid && !create_compatibility_item.nameShort.$pristine) || create_compatibility_item.nameShort.$touched && create_compatibility_item.nameShort.$invalid}">
                            <div class="fg-line">
                                <input type="text" name="nameShort" ng-model="nameShort"
                                       class="form-control fg-input"
                                       ng-minlength="1" ng-maxlength="25">
                                <label class="fg-label">Short Reference Name</label>
                            </div>
                            <div ng-messages="create_compatibility_item.nameShort.$error" ng-show="create_compatibility_item.nameShort.$dirty">
                                <small class="help-block" ng-message="minlength">This too short</small>
                                <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                            </div>
                        </div>

                        <div class="form-group fg-float m-b-30 fg-toggled" ng-class="{ 'has-error' : (create_compatibility_item.compText.$invalid && !create_compatibility_item.compText.$pristine) || create_compatibility_item.compText.$touched && create_compatibility_item.compText.$invalid}">
                            <div class="fg-line">
                                <textarea class="form-control" rows="5" name="compText" ng-model="compText"></textarea>

                                <label class="fg-label">Compatibility Text</label>
                            </div>
                            <div ng-messages="create_compatibility_item.compText.$error" ng-show="create_compatibility_item.compText.$dirty">
                                <small class="help-block" ng-message="maxlength">Try and keep it less than 1,000 characters</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Add Compatibility statement</button>
                            </div>
                        </div>
                    </div><!--end card body -->
                </form>
            </div>
        </div>
    </section>
@endsection