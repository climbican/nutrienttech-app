@extends('layouts.app')
@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Update Deficiency Correlation </h2>
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
                        <div class="row" style="margin-top: 20px;">
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
                                                <label for="defDescription">Desccription Text</label>
                                                <trix-editor  spellcheck="false" class="trix-content" ng-model="defDescription" angular-trix></trix-editor>
                                                <input type="hidden" ng-model="defDescription" ng-value="defDescription" name="defDescription"/>
                                                <!--<textarea class="form-control" rows="5" name="defDescription" ng-model="defDescription" placeholder="Deficiency description text here" data-auto-size></textarea>-->
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
                                <div class="row" id="image_cells">
                                    @if($images[0]->active === 0 && $def->active === 0)
                                        <input type="hidden" id="communityAddedImageId" name="communityAddedImageId" value="{{$images[0]->id}}"/>
                                    @endif
                                    @foreach($images as $im)
                                        <div class="col-sm-3 ind_image_cell @if($image_id == $im->id && $im->active == 0) images_not_approved_selected @elseif($im->active == 0) image_not_approved @endif" id="image_row_{{$im->id}}">
                                            <!-- TODO: NEEDS A FIELD FOR ACTIVE & APPROVED -->
                                            <div class="fileinput fileinput-new" data-provides="fileinput" style="margin:0px auto 25px auto;">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" id="preview3"><img src="{{url('/images/def/'.$im->image_name)}}"/></div>
                                                <div>
                                                    <div>
                                                        @if($im->active == 1)
                                                        <span class="btn btn-info btn-file">
                                                            <div>
                                                                <span class="fileinput-new">Stored Image</span>
                                                                <span class="fileinput-exists">Change</span>
                                                            </div>
                                                            <input type="hidden" name="existing[]" value="1"/>
                                                            <input type="file" name="images[]"/>
                                                        </span>
                                                        @else
                                                            <span class="btn btn-info">
                                                                <a href="" ng-confirm-click="'Are you sure you want to associate this image?'" confirmed-click="approveImage({{$im->id}})" style="color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Approve&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                                            </span>
                                                        @endif
                                                        <a href="#" class="btn btn-danger fileinput-exists remove" ng-confirm-click="Are you sure you want to remove this image?" confirmed-click="removeImage( {{$def->id}}, {{$im->id}})" data-dismiss="fileinput" style="display:inline;">Remove</a>
                                                    </div>
                                                    <div style="margin-top:4px;margin-right:4px;height:35px;">
                                                        <span class="btn" style="width:100%;background-color:#f4b400;">
                                                             <a href="{{url('/images/def/'.$im->image_name)}}" style="color:#ffffff;" download>Download</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
        /**
         *
         >>>>>>>>>>>>>>>>>>>>>> TODO: add flag on new items so that I can just add the new images... hopefully this shit works... <<<<<<<<<<<<<<<<
         **/
        if (window.addEventListener)
            window.addEventListener("load", loadScriptOnComplete, false);
        else if (window.attachEvent)
            window.attachEvent("onload", loadScriptOnComplete);
        else window.onload = loadScriptOnComplete;
        function loadScriptOnComplete() {
            /** for static file load
             var element = document.createElement("script");
             element.src = "defer.js";
             document.body.appendChild(element);
             */
            var form = document.getElementById('update_deficiency');
            var formSubmit = form.submit; //save reference to original submit function
            $(document).on('click', '.add', function(){
                var html = '';
                html += '<div class="col-sm-3 ind_image_cell">\n' +
                    '  <div class="fileinput fileinput-new" data-provides="fileinput" style="margin:0px auto 25px auto;">\n' +
                    '    <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>' +
                    '      <input type="hidden" name="existing[]"  value="0"/>'    +
                    '      <div>\n' +
                    '        <span class="btn btn-info btn-file">\n' +
                    '        <span class="fileinput-new">Image</span>\n' +
                    '        <span class="fileinput-exists">Change</span>\n' +
                    '          <input type="file" name="images[]">\n' +
                    '        </span>\n' +
                    '        <a href="#" class="btn btn-danger fileinput-exists remove" data-dismiss="fileinput">Remove</a>\n' +
                    '    </div>\n' +
                    '  </div>\n' +
                    '</div>';
                $('#image_cells').append(html);
            });
            $(document).on('click', '.remove', function(){
                //$(this).closest('.ind_image_cell').remove();
            });
        }
    </script>
@endsection
