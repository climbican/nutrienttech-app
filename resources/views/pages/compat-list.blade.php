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
                            <h2 class="lvh-label hidden-xs">Compatibility List</h2>
                            <ul class="lv-actions actions">
                                <li class="dropdown" uib-dropdown>
                                    <a href="" uib-dropdown-toggle aria-expanded="true">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{url('')}}/admin/compatibility/create">Add New</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="lv-body">
                            <!--repeater section-->
                            @foreach ($compatibility as $compat)

                                <div class="lv-item media">
                                        <div class="lv-title">Name Short:  {{$compat->name_short}} </div>
                                        <small class="lv-small" style="font-size:120%;">{{ substr($compat->comp_text, 0, 200) }}</small>
                                        <ul class="lv-attrs">
                                            <li style="width: 190px;">Lookup ID:  {{$compat->id}}</li>
                                            <li>Date Created: {{date('Y/m/d',substr($compat->create_dte,0,10) )}}</li>
                                            <li>Last Update: {{date('Y/m/d', substr($compat->last_update,0,10) )}}</li>
                                        </ul>
                                        <div class="lv-actions actions dropdown" uib-dropdown>
                                            <a href="" uib-dropdown-toggle aria-expanded="true">
                                                <i class="zmdi zmdi-more-vert"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="{{url('')}}/admin/compatibility/update/{{$compat->id}}">Edit</a>
                                                </li>
                                                <li>
                                                    <a href="{{url('')}}/admin/compatibility/delete/{{$compat->id}}">Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                            @endforeach
                                </div>

                            @if($numRows > 0)
                                <div class="text-center"> Total Listed {{$numRows}}</div>
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
        </section>
    </section>

@endsection