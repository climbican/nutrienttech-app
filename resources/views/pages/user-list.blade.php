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
                        <h2 class="lvh-label hidden-xs">User List</h2>
                        <ul class="lv-actions actions">
                            <li class="dropdown" uib-dropdown>
                                <a href="" uib-dropdown-toggle aria-expanded="true">
                                    <i class="zmdi zmdi-more-vert"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="{{url('admin/profile/create')}}">Add New</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="lv-body">
                        <!--repeater section-->
                        @foreach ($profiles as $profile)
                        <div class="lv-item media">
                            <small class="media-body">
                                <div class="lv-title">Name:  {{$profile->name}} </div>
                                <div class="lv-title">Email: {{$profile->email}} </div>
                                <ul class="lv-attrs">
                                    <li style="width: 190px;">User ID:  {{$profile->id}}</li>
                                    <li>Date Created: {{$profile->create_dte}}</li>
                                    <li>Last Update: {{$profile->last_update}}</li>
                                </ul>
                                <div class="lv-actions actions dropdown" uib-dropdown>
                                    <a href="" uib-dropdown-toggle aria-expanded="true">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{url('')}}/admin/profile/update/{{$profile->id}}">Edit</a>
                                        </li>
                                        <li>
                                            <a href="{{url('')}}/admin/profile/delete/{{$profile->id}}">Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </small>
                        </div>
                        @endforeach

                    @if($numRows > 0)
                    <div class="text-center"> Total Listed {{$numRows}}</div>
                    @endif

                    @if($numRows < 1)
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h3>You have no Users listed yet</h3>
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