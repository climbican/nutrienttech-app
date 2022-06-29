@extends('layouts.app')

@section('content')

    <section id="main">
        <section id="content">
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
                            <h2 class="lvh-label hidden-xs">Elements List</h2>
                            <ul class="lv-actions actions">
                                <li class="dropdown" uib-dropdown>
                                    <a href="" uib-dropdown-toggle aria-expanded="true">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{url('admin/element/create')}}">Add New</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="lv-body">
                            <!--repeater section-->
                            @foreach ($elements as $elem)
                                <div class="lv-item media">
                                    <div class="lv-title">Element Name:  {{$elem->element_name}} </div>
                                    <small class="lv-small" style="font-size:120%;">{{ substr($elem->element_desc, 0, 200) }}</small>
                                    <ul class="lv-attrs">
                                    <!--<li style="width:190px;">Symbol: {{$elem->symbol}}</li>-->
                                        <li><strong>Show on App :</strong> @if($elem->show_flag)Yes @else No @endif</li>
                                        <li style="width: 190px;">Chemical ID:  {{$elem->chemical_name}}</li>
                                        <li>Date Created: {{date('Y/m/d',substr($elem->create_dte,0,10) )}}</li>
                                        <li>Last Update: {{date('Y/m/d', substr($elem->last_update,0,10) )}}</li>
                                    </ul>
                                    <div class="lv-actions actions dropdown" uib-dropdown>
                                        <a href="" uib-dropdown-toggle aria-expanded="true">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="{{url('admin/element/update/'.$elem->id)}}">Edit</a>
                                            </li>
                                            <li>
                                                <a href="{{url('admin/element/delete/'.$elem->id)}}"  onclick="return confirm('Are you sure you want to remove {{$elem->element_name}}?')">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($numRows > 0)
                            <div class="text-center">{{$elements->links()}}  &nbsp; <span style="margin-left:4%; padding-top:30px;">Total {{$numRows}}</span></div>
                        @endif
                        @if($numRows < 1)
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <h3>You have no Elements listed yet</h3>
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