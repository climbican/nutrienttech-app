@extends('layouts.app')
@section('content')
    <section id="main">
        <section id="content">
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
                                        <li><a href="{{url('admin/deficiency/list')}}">List All</a>
                                        </li>
                                        <li><a href="{{url('admin/deficiency/community_images')}}">Community Added Images</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="lv-body">
                            <!--repeater section-->
                            @foreach ($newImages as $deficiency)
                                <div class="lv-item media">
                                    <div class="pull-left">
                                        <img src="{{url('images/def'.'/'.$deficiency->image_name)}}" alt="crop image" style="height:100px;"/>
                                    </div>
                                    <div class="lv-title">Deficiency Name:  {{$deficiency->name_short}} </div>
                                    <div class="lv-title">Desc: {{substr($deficiency->deficiency_description, 0, 100)}}</div>
                                    <ul class="lv-attrs">
                                        <li style="width: 190px;">Crop Name:  {{$deficiency->crop_name}}</li>
                                    </ul>
                                    <div class="lv-actions actions dropdown" uib-dropdown>
                                        <a href="" uib-dropdown-toggle aria-expanded="true">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="{{url('admin/deficiency/community_image/approve/'.$deficiency->id.'/1')}}" onclick="return confirm('Are you sure you want to associate this image?')"style="color:#008000;">Approve</a>
                                            </li>
                                            <li>
                                                <a href="{{url('admin/deficiency/update/0/'.$deficiency->defId. '/'.$deficiency->id)}}">Edit</a>
                                            </li>
                                            <li>
                                                <a href="{{url('admin/deficiency/community_image/delete/'.$deficiency->id)}}" onclick="return confirm('Are you sure you want to remove the image?')" style="color:#ff0000;font-weight:bold;">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                            @if($numRows > 0)
                                <div class="text-center">{{$newImages->links()}}<span style="margin-left:4%; padding-top:20px;">Total {{$numRows}}</span></div>
                            @endif
                            @if($numRows < 1)
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <h3>You have no Community Images added yet</h3>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
