@extends('layouts.app')
@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Add new Element</h2>
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
                <form name="create_element_item" method="post" action="{{url('')}}/admin/element/save">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">
                        <div class="row">
                            <div class="col-md-3 m-b-20">
                                <div class="toggle-switch" data-ts-color="blue">
                                    <label for="ts3" class="ts-label">Is visible on Nutrients list</label>
                                    <input id="isVisible" name="isVisible" type="checkbox" hidden="hidden" value="1" checked>
                                    <label for="isVisible" class="ts-helper"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group fg-float m-b-30 fg-toggled"
                                     ng-class="{ 'has-error' : (create_element_item.elementName.$invalid && !create_element_item.elementName.$pristine) || create_element_item.elementName.$touched && create_element_item.elementName.$invalid}">
                                    <div class="fg-line">
                                        <input type="text" name="elementName" ng-model="elementName"
                                               class="form-control fg-input"
                                               ng-minlength="1" ng-maxlength="45">
                                        <label class="fg-label">Element Name</label>
                                    </div>
                                    <div ng-messages="create_element_item.elementName.$error" ng-show="create_element_item.elementName.$dirty">
                                        <small class="help-block" ng-message="minlength">This too short</small>
                                        <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group fg-float m-b-30 fg-toggled"
                             ng-class="{ 'has-error' : (create_element_item.chemicalName.$invalid && !create_element_item.chemicalName.$pristine) || create_element_item.chemicalName.$touched && create_element_item.chemicalName.$invalid}">
                            <div class="fg-line">
                                <input type="text" name="chemicalName" ng-model="chemicalName"
                                       class="form-control fg-input"
                                       ng-minlength="1" ng-maxlength="45">
                                <label class="fg-label">Chemical Name</label>
                            </div>
                            <div ng-messages="create_element_item.chemicalName.$error" ng-show="create_element_item.chemicalName.$dirty">
                                <small class="help-block" ng-message="minlength">This too short</small>
                                <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                            </div>
                        </div>
                        <!-- BENEFITS TEXT -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group m-b-30" ng-class="{ 'has-error' : (create_element_item.benefitsText.$invalid && !create_element_item.benefitsText.$pristine) || create_element_item.benefitsText.$touched && create_element_item.benefitsText.$invalid}">
                                    <div class="fg-line">
                                        <textarea class="form-control" rows="5" name="benefitsText" ng-model="benefitsText" placeholder="Product benefitsText text here" data-auto-size></textarea>
                                    </div>
                                    <div ng-messages="create_element_item.benefitsText.$error" ng-show="create_element_item.benefitsText.$dirty">
                                        <small class="help-block" ng-message="maxlength">Try and keep it less than 250 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- DEFICIENCY TEXT -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group m-b-30" ng-class="{ 'has-error' : (create_element_item.deficiencyText.$invalid && !create_element_item.deficiencyText.$pristine) || create_element_item.deficiencyText.$touched && create_element_item.deficiencyText.$invalid}">
                                    <div class="fg-line">
                                        <textarea class="form-control" rows="5" name="deficiencyText" ng-model="deficiencyText" placeholder="Product deficiencyText text here" data-auto-size></textarea>
                                    </div>
                                    <div ng-messages="create_element_item.deficiencyText.$error" ng-show="create_element_item.deficiencyText.$dirty">
                                        <small class="help-block" ng-message="maxlength">Try and keep it less than 250 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Add Element</button>
                            </div>
                        </div>
                    </div><!--end card body -->
                </form>
            </div>
        </div>
    </section>
@endsection