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
                            <h2 class="lvh-label hidden-xs">Sufficiency List</h2>
                            <ul class="lv-actions actions">
                                <li class="dropdown" uib-dropdown>
                                    <a href="" uib-dropdown-toggle aria-expanded="true">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{url('admin/sufficiency/create')}}">Add New</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/sufficiency/json-list')}}">Export for App</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="lv-body">
                            <!--repeater section-->
                            @foreach ($sufficiency as $suff)
                                <div class="lv-item media">
                                    <div class="lv-title"><strong>Crop Name:</strong>  {{$suff->cropName}} </div><div class="lv-title"><strong>Sub-Type: </strong>{{$suff->sub_type}}</div>
                                    <div class="lv-title"><strong>Growth Stage:</strong> {{$suff->growthStageName}}</div>
                                    <ul class="lv-attrs">
                                        <li style="width: 100px;">ID:  {{$suff->id}}</li>
                                        <li>Date Created: {{date('Y/m/d',substr($suff->create_dte,0,10) )}}</li>
                                    </ul>
                                    <ul class="lv-attrs">
                                        <li><strong>N&#37;</strong> @if($suff->n_percent == '')--@else{{$suff->n_percent}}@endif</li>
                                        <li><strong>NO3 PPM</strong> @if($suff->no3_ppm == '')--@else{{$suff->no3_ppm}}@endif</li>
                                        <li><strong>P Percent</strong> @if($suff->p_percent == '')--@else{{$suff->p_percent}}@endif</li>
                                        <li><strong>PO4 PPM</strong> @if($suff->po4_ppm == '')--@else{{$suff->po4_ppm}}@endif</li>
                                        <li><strong>K Percent</strong> @if($suff->k_percent == '')--@else{{$suff->k_percent}}@endif</li>
                                        <li><strong>CA Percent</strong> @if($suff->ca_percent == '')--@else{{$suff->ca_percent}}@endif</li>
                                        <li><strong>MG Percent</strong> @if($suff->mg_percent == '')--@else{{$suff->mg_percent}}@endif</li>
                                        <li><strong>S Percent</strong> @if($suff->s_percent == '')--@else{{$suff->s_percent}}@endif</li>
                                        <li><strong>B PPM</strong> @if($suff->b_ppm == '')--@else{{$suff->b_ppm}}@endif</li>
                                        <li><strong>CU PPM</strong> @if($suff->cu_ppm == '')--@else{{$suff->cu_ppm}}@endif</li>
                                        <li><strong>FE PPM</strong> @if($suff->fe_ppm == '')--@else{{$suff->fe_ppm}}@endif</li>
                                        <li><strong>MN PPM</strong> @if($suff->mn_ppm == '')--@else{{$suff->mn_ppm}}@endif</li>
                                        <li><strong>ZN PPM</strong> @if($suff->zn_ppm == '')--@else{{$suff->zn_ppm}}@endif</li>
                                        <li><strong>NA Percent</strong> @if($suff->na_percent == '')--@else{{$suff->na_percent}}@endif</li>
                                        <li><strong>CI Percent</strong> @if($suff->cl_percent == '')--@else{{$suff->cl_percent}}@endif</li>
                                    </ul>
                                    <div class="lv-actions actions dropdown" uib-dropdown>
                                        <a href="" uib-dropdown-toggle aria-expanded="true">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="{{url('')}}/admin/sufficiency/update/{{$suff->id}}">Edit</a>
                                            </li>
                                            <li>
                                                <a href="{{url('')}}/admin/sufficiency/delete/{{$suff->id}}" onclick="return confirm('Are you sure you want to remove this entry?')">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($numRows > 0)
                            <div class="text-center">{{$sufficiency->links()}}  &nbsp; <span style="margin-left:4%; padding-top:20px;">Total {{$numRows}}</span></div>
                        @endif

                        @if($numRows < 1)
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <h3>You have no Sufficiencies listed yet</h3>
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