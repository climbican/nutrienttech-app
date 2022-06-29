@extends('layouts.app')

@section('content')
    <section id="main">
        <section id="content">
            <div class="container c-alt">
                @if (count($errors) > 0)
                    <section>
                        <!--SHOW ERRORS IF THERE ARE ANY -->
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                @endif
                <div class="card">
                    <div class="listview lv-bordered lv-lg">
                        <div class="lv-header-alt clearfix">
                            <h2 class="lvh-label hidden-xs">Product List</h2>
                            <ul class="lv-actions actions">
                                <li class="dropdown" uib-dropdown>
                                    <a href="" uib-dropdown-toggle aria-expanded="true">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{url('')}}/admin/product/create">Add New Product</a>
                                        </li>
                                        <li><a href="{{url('')}}/admin/product/list">List All</a></li>
                                        <li>
                                            <a href="{{url('admin/product/json-list')}}">Export for Product listings in App</a>
                                        </li>
                                        <li>
                                            <a href="{{url('admin/product/all/element/chart/data')}}">Export for Chart Data in App</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="lv-body">
                            <!--repeater section-->
                            @foreach ($products as $product)
                                <div class="lv-item media">
                                    <div class="pull-left">
                                        <img class="lv-img" src="{{url('images/product'.'/'.$product->image_url)}}" alt="crop image"/>
                                    </div>
                                    <div class="lv-title">Name:  {{$product->product_name}} </div>
                                    <div class="lv-title">Sub title: {{$product->product_subname}}</div>
                                    <ul class="lv-attrs">
                                        <li style="width: 190px;">Lookup ID:  {{$product->id}}</li>
                                        <li>Date Created: {{date('Y/m/d',substr($product->create_dte,0,10) )}}</li>
                                        <li>Last Update: {{date('Y/m/d', substr($product->last_update,0,10) )}}</li>
                                    </ul>
                                    <div class="lv-actions actions dropdown" uib-dropdown>
                                        <a href="" uib-dropdown-toggle aria-expanded="true">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="{{url('')}}/admin/product/update/{{$product->id}}">Edit</a>
                                            </li>
                                            <li>
                                                <a href="{{url('')}}/admin/product/delete/{{$product->id}}" onclick="return confirm('Are you sure you want to remove {{$product->product_name}}?')">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                            @if($numRows > 0)
                                <div class="text-center">{{$products->links()}}  &nbsp; <span style="margin-left:4%; padding-top:20px;">Total {{$numRows}}</span></div>
                            @endif

                            @if($numRows < 1)
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <h3>You have no Products Listed yet</h3>
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
