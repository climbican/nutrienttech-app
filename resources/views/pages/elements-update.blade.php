@extends('layouts.app')

@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Update Element</h2>
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
                <form name="update_element_item" method="post" action="{{url('admin/element/update/'.$element->id)}}">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">
                        <div class="row">
                            <div class="col-md-3 m-b-20">
                                <div class="toggle-switch" data-ts-color="blue">
                                    <label for="ts3" class="ts-label">Is visible on Nutrients list</label>
                                    <input id="isVisible" name="isVisible" type="checkbox" hidden="hidden" value="1" @if($element->show_flag) checked @endif>
                                    <label for="isVisible" class="ts-helper"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-b-30 fg-toggled"
                                     ng-class="{ 'has-error' : (update_element_item.elementName.$invalid && !update_element_item.elementName.$pristine) || update_element_item.elementName.$touched && update_element_item.elementName.$invalid}">
                                    <div class="fg-line">
                                        <label class="fg-label">Element Name</label>
                                        <input type="text" name="elementName" ng-model="elementName"
                                               class="form-control fg-input"
                                               ng-minlength="3" ng-maxlength="45"
                                               ng-init="elementName='{{ $element->element_name}}'">
                                    </div>
                                    <div ng-messages="update_element_item.elementName.$error" ng-show="update_element_item.elementName.$dirty">
                                        <small class="help-block" ng-message="minlength">This too short</small>
                                        <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-b-30 fg-toggled"
                             ng-class="{ 'has-error' : (update_element_item.chemicalName.$invalid && !update_element_item.chemicalName.$pristine) || update_element_item.chemicalName.$touched && update_element_item.chemicalName.$invalid}">
                            <div class="fg-line">
                                <label class="fg-label">Chemical Name</label>
                                <input type="text" name="chemicalName" ng-model="chemicalName"
                                       class="form-control fg-input"
                                       ng-minlength="3" ng-maxlength="45"
                                        ng-init="chemicalName='{{$element->chemical_name }}'">
                            </div>
                            <div ng-messages="update_element_item.chemicalName.$error" ng-show="update_element_item.chemicalName.$dirty">
                                <small class="help-block" ng-message="minlength">This too short</small>
                                <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                            </div>
                        </div>
                        <!-- BENEFITS TEXT -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group m-b-30" ng-class="{ 'has-error' : (update_element_item.benefitsText.$invalid && !update_element_item.benefitsText.$pristine) || update_element_item.benefitsText.$touched && update_element_item.benefitsText.$invalid}">
                                    <label class="fg-label" style="font-weight:bold;">Benefits Text</label>
                                    <div class="fg-line">
                                        <textarea class="form-control" rows="5" name="benefitsText" ng-model="benefitsText" placeholder="Product benefitsText text here" ng-init="benefitsText='{{$element->benefits}}'"></textarea>
                                    </div>
                                    <div ng-messages="update_element_item.benefitsText.$error" ng-show="update_element_item.benefitsText.$dirty">
                                        <small class="help-block" ng-message="maxlength">Try and keep it less than 250 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- DEFICIENCY TEXT -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group m-b-30" ng-class="{ 'has-error' : (update_element_item.deficiencyText.$invalid && !update_element_item.deficiencyText.$pristine) || update_element_item.deficiencyText.$touched && update_element_item.deficiencyText.$invalid}">
                                    <label class="fg-label" style="font-weight:bold;">Deficiency Text</label>
                                    <div class="fg-line">
                                        <textarea class="form-control" rows="5" name="deficiencyText" ng-model="deficiencyText" placeholder="Product deficiencyText text here" ng-init="deficiencyText='{{$element->deficiency}}'"></textarea>
                                    </div>
                                    <div ng-messages="update_element_item.deficiencyText.$error" ng-show="update_element_item.deficiencyText.$dirty">
                                        <small class="help-block" ng-message="maxlength">Try and keep it less than 250 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Update Element</button>
                            </div>
                        </div>
                    </div><!--end card body -->
                </form>
            </div>
        </div>
    </section>
@endsection