@extends('layouts.app')
@section('content')
    <section id="main">
        <div id="content">
            <div class="container c-alt">
                <div class="card">
                    <div class="listview lv-bordered lv-lg">
                        @if (count($errors) > 0)
                            <section>
                                <!--SHOW ERRORS IF THERE ARE ANY -->
                                <div class="alert alert-danger alert-dismissable">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </section>
                        @endif
                        <div class="lv-header-alt clearfix">
                            <h2 class="lvh-label hidden-xs">Deficiency Association List</h2>
                            <ul class="lv-actions actions">
                                <li class="dropdown" uib-dropdown>
                                    <a href="" uib-dropdown-toggle aria-expanded="true">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{url('admin/deficiency/create')}}">Add New Deficiency</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/deficiency/list/0')}}">List all active</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/deficiency/list/1')}}">Crowdsourced Deficiencies</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/deficiency/community_images')}}">Crowdsourced Deficiency Images</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="lv-body">
                            <!--repeater section-->
                            @foreach ($def as $deficiency)
                                <div class="lv-item media">
                                    <div class="pull-left">
                                        <img class="lv-img" src="{{url('images/def/thumb/'.$deficiency->image_name)}}" alt="crop image"/>
                                    </div>
                                    <div class="lv-title">Name Short:  {{$deficiency->name_short}} </div>
                                    <div class="lv-title">Desc: {!!  substr($deficiency->deficiency_description, 0, 100) !!}</div>
                                    <ul class="lv-attrs">
                                        <li style="width: 190px;">For Crop:  {{$deficiency->crop_name}}</li>
                                        <li>Date Created: {{date('Y/m/d',substr($deficiency->create_dte,0,10) )}}</li>
                                        <li>Last Update: {{date('Y/m/d', substr($deficiency->last_update,0,10) )}}</li>
                                    </ul>
                                    <div class="lv-actions actions dropdown" uib-dropdown>
                                        <a href="" uib-dropdown-toggle aria-expanded="true">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="{{url('admin/deficiency/update/1/'.$deficiency->defId.'/0')}}">Edit</a>
                                            </li>
                                            <li>
                                                <a href="{{url('admin/deficiency/delete/'.$deficiency->defId)}}"  onclick="return confirm('Are you sure you want to remove {{$deficiency->name_short}}?')">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                            @if($numRows > 0)
                                <div class="text-center">{{$def->links('custom_pagination')}}  &nbsp; <span style="margin-left:4%; padding-top:20px;">Total {{$numRows}}</span></div>
                            @endif

                            @if($numRows < 1)
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <h3>You have no Deficiency Associations Listed yet</h3>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
