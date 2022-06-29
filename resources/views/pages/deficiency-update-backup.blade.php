@extends('layouts.app')
@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>
                    @if($def->active == 1)
                        Update Deficiency Correlation
                    @else
                        Change / Approve crowd sourced deficiency
                    @endif
                </h2>
            </div>
            <!-- error messages -->
            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card" ng-init="fetchDeficiency('{{$def->id}}')">
                <form name="update_deficiency" id="update_deficiency" method="post" action="{{url('admin/deficiency/update/'.$def->id)}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">
                        <!-- ASSOCIATED ELEMENT -->
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-5 m-b-15">
                                        <label style="margin-bottom:5px;">Associated Crop</label>
                                        <select chosen
                                                ng-model="cropID"
                                                name="cropID"
                                                data-placeholder="Select a Crop..." class="w-100" ng-options="item.id as item.name for item in cropSelect">
                                        </select>
                                    </div>
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-5 m-b-15">
                                        <label style="margin-bottom:5px;">Element Deficiency</label>
                                        <select chosen
                                                ng-model="elementID"
                                                name="elementID"
                                                data-placeholder="Select a Element..." class="w-100" ng-options="item.id as item.name for item in elementSelect">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if($def->active == 0)
                                <div class="col-sm-4">
                                    <div class="form-group" style="margin-top:28px;margin-right:25px;float:right;">
                                        <div class="toggle-switch" data-ts-color="blue">
                                            <label for="ts3" class="ts-label">Approve crowd sourced deficiency</label>
                                            <input id="approveCrowdSourcedDeficiency" name="approveCrowdSourcedDeficiency" type="checkbox" hidden="hidden" value="1">
                                            <label for="approveCrowdSourcedDeficiency" class="ts-helper"></label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row " style="margin-top: 20px;">
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group fg-toggled m-b-30"
                                             ng-class="{'has-error' : (update_deficiency.nameShort.$invalid && !update_deficiency.nameShort.$pristine) || update_deficiency.nameShort.$touched && update_deficiency.nameShort.$invalid}">
                                            <div class="fg-line">
                                                <label class="fg-label">Deficiency Name Short</label>
                                                <input type="text" name="nameShort" ng-model="nameShort"
                                                       class="form-control fg-input"
                                                       ng-minlength="3" ng-maxlength="45">
                                            </div>
                                            <div ng-messages="update_deficiency.nameShort.$error" ng-show="update_deficiency.nameShort.$dirty">
                                                <small class="help-block" ng-message="minlength">This too short</small>
                                                <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                                                <small class="help-block" ng-message="required">This field is required</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ADD PRODUCT DESCRIPTION -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group m-b-30" ng-class="{ 'has-error' : (update_deficiency.defDescription.$invalid && !update_deficiency.defDescription.$pristine) || update_deficiency.defDescription.$touched && update_deficiency.defDescription.$invalid}">
                                            <div class="fg-line">
                                                <textarea class="form-control" rows="5" name="defDescription" ng-model="defDescription" placeholder="Deficiency description text here" data-auto-size></textarea>
                                            </div>
                                            <div ng-messages="update_deficiency.defDescription.$error" ng-show="update_deficiency.defDescription.$dirty">
                                                <small class="help-block" ng-message="maxlength">Try and keep it less than 1,000 characters</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row" style="margin-bottom:20px;">
                                    <div class="col-md-8">Add additional images with the <span style="font-weight:bold;font-size:110%;">"+"</span> button</div>
                                    <div class="col-md-4 text-right"><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></div>
                                </div>
                                <div class="row" style="margin-bottom:20px;">
                                    <div class="col-md-8"><strong>NOTE ON BACKGROUND COLORS:</strong><br>
                                        ** White --  Approved image(s) <br>
                                        *** Dark gray -- Selected image from list view. <br>
                                        **** Light gray -- Community added images not yet approved.</div>
                                </div>
                                <div class="listview lv-bordered lv-lg">
                                    <div class="lv-body" id="image_rows">
                                        @if(isset($images[0]))
                                            @if($images[0]->active === 0 && $def->active === 0)
                                                <input type="hidden" id="communityAddedImageId" name="communityAddedImageId" value="{{$images[0]->id}}"/>
                                            @endif
                                            @foreach($images as $im)
                                                <div id="image_row_{{$im->id}}" class="lv-item media @if($image_id == $im->id && $im->active == 0) images_not_approved_selected @elseif($im->active == 0) image_not_approved @endif">
                                                    <div class="pull-left" style="width:150px;">
                                                        <div style="height:60px;" data-trigger="fileinput" id="preview3"><img src="{{url('/images/def/'.$im->image_name)}}" style="height:59px;"/></div>
                                                    </div>
                                                    <div class="lv-title"><strong>Name: </strong> {{$im->image_name}} </div>
                                                    <ul class="lv-attrs">
                                                        <li><strong>Date Created:</strong> {{date('Y/m/d', (int)substr($im->create_dte, 0,10))}}</li>
                                                        <li><strong>Last Update:</strong> {{date('Y/m/d', (int)substr($im->last_update, 0, 10))}}</li>
                                                    </ul>
                                                    <input type="file" id="newImage" name="images[]"  style="display: none;" />
                                                    <input type="hidden" name="existing[]" value="1"/>
                                                    <div class="lv-actions actions dropdown" uib-dropdown>
                                                        <a href="" uib-dropdown-toggle aria-expanded="true">
                                                            <i class="zmdi zmdi-more-vert"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-right" style="background:#ffffff;">
                                                            <li>
                                                                <a href="{{url('admin/deficiency/community_image/approve/'.$def->id)}}" ng-confirm-click="'Are you sure you want to associate this image?'" style="color:#008000;">Approve</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{url('/images/def/'.$im->image_name)}}" style="color:rgba(0,0,0,97);" download>Download</a>
                                                            </li>
                                                            <li>
                                                                <a href="#" class="fileinput-exists remove" ng-confirm-click="Are you sure you want to remove this image?" confirmed-click="removeImage( {{$def->id}}, {{$im->id}})" data-dismiss="fileinput" style="color:#ff0000;">Remove</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div>You do not have any images for this Deficiency, please add at least one.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Update Deficiency</button>
                            </div>
                        </div>
                    </div><!--end card body -->
                </form>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        if (window.addEventListener)
            window.addEventListener("load", loadScriptOnComplete, false);
        else if (window.attachEvent)
            window.attachEvent("onload", loadScriptOnComplete);
        else window.onload = loadScriptOnComplete;

        function loadScriptOnComplete() {
            $('#changeImage').on('click', function() {
                $('#newImage').trigger('click');
            });
            /** for static file load
             var element = document.createElement("script");
             element.src = "defer.js";
             document.body.appendChild(element);
             */
            var form = document.getElementById('update_deficiency');
            var formSubmit = form.submit; //save reference to original submit function
            $(document).on('click', '.add', function(){
                let html = '';
                html += '<div class="lv-item media" data-provides="fileinput">' +
                    '    <div class="pull-left" style="width:60px;height:60px;">' +
                    '        <div style="height:59px; background-color:#fff;" class="fileinput-preview thumbnail" data-trigger="fileinput" id="preview11"></div>' +
                    '        <input type="hidden" name="existing[]" value="0"/>' +
                    '        <input type="file" id="newImage" name="images[]"  style="display:none;"/>' +
                    '    </div>' +
                    '    <div class="lv-actions actions dropdown" uib-dropdown>' +
                    '       <a href="" uib-dropdown-toggle aria-expanded="false">' +
                    '           <i class="zmdi zmdi-more-vert"></i></a>' +
                    '       <ul class="dropdown-menu dropdown-menu-right" style="background:#ffffff;">' +
                    '            <li><a href="#" class="fileinput-exists" data-trigger="fileinput" style="color:#008000;">Change</a></li>' +
                    '            <li><a href="#" class="fileinput-exists remove" data-dismiss="fileinput" style="color:#ff0000;">Remove</a></li>' +
                    '        </ul>' +
                    '    </div>' +
                    '</div>';
                $('#image_rows').append(html);
            });
            $(document).on('click', '.remove', function(){
                $(this).closest('.lv-item').remove();
            });
        }
    </script>
@endsection
