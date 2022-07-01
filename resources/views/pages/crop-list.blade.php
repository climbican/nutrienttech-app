@extends('layouts.app')

@section('content')
    <section id="main">
        <div id="content">
            <div class="container c-alt">
            @if (count($errors) > 0)
                <!--SHOW ERRORS IF THERE ARE ANY -->
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="listview lv-bordered lv-lg">
                        <div class="lv-header-alt clearfix">
                            <h2 class="lvh-label hidden-xs">Compatibility List</h2>
                            <ul class="lv-actions actions">
                                <li class="dropdown" uib-dropdown>
                                    <a href="" uib-dropdown-toggle aria-expanded="true">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{url('')}}/admin/crop/create">Add New</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="lv-body">
                            <!--repeater section-->
                            @foreach ($crops as $crop)

                            <div class="lv-item media">
                                <div class="pull-left">
                                    <img class="lv-img" src="{{url('images/crop'.'/'.$crop->image_url)}}" alt="crop image"/>
                                </div>
                                <div class="lv-title">Name:  {{$crop->name}} </div>
                                <div class="lv-title">Sub type: {{$crop->sub_type}}</div>
                                <ul class="lv-attrs">
                                    <li style="width: 190px;">Lookup ID:  {{$crop->id}}</li>
                                    <li>Date Created: {{date('Y/m/d',substr($crop->create_dte,0,10) )}}</li>
                                    <li>Last Update: {{date('Y/m/d', substr($crop->last_update,0,10) )}}</li>
                                </ul>
                                <div class="lv-actions actions dropdown" uib-dropdown>
                                    <a href="" uib-dropdown-toggle aria-expanded="true">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{url('admin/crop/update/'.$crop->id)}}">Edit</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/crop/delete/'.$crop->id)}}">Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @endforeach
                        </div>
                        @if($numRows > 0)
                            <div class="text-center">{{$crops->links('custom_pagination')}}</div>
                        @endif

                        @if($numRows < 1)
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <h3>You have no Compatibility Statements yet</h3>
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
