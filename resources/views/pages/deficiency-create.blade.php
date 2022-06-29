@extends('layouts.app')
@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Add New Deficiency Correlation </h2>
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
            <div class="card">
                <form name="create_deficiency" id="create_deficiency" method="post" action="{{url('admin/deficiency/save/0')}}" enctype="multipart/form-data">
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
                                                data-placeholder="Select a Crop..." class="w-100" focus required>
                                            @foreach($crops as $e)
                                                <option value="{{$e->id}}">{{$e->name}} @if( (strtolower($e->sub_type) !== 'general') && (strtolower($e->sub_type) !== 'none') && $e->sub_type !== '') ({{$e->sub_type}}) @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div ng-messages="create_deficiency.cropID.$error" ng-show="create_deficiency.cropID.required">
                                        <small class="help-block" ng-message="required">This field is required</small>
                                    </div>
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-5 m-b-15">
                                        <label style="margin-bottom:5px;">Element Deficiency</label>
                                        <select chosen
                                                ng-model="elementID"
                                                name="elementID"
                                                data-placeholder="Select a Element..." class="w-100" required>
                                            @foreach($elements as $e)
                                                <option value="{{$e->id}}">{{$e->element_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group fg-float m-b-30 fg-toggled"
                                             ng-class="{'has-error' : (create_deficiency.nameShort.$invalid && !create_deficiency.nameShort.$pristine) || create_deficiency.nameShort.$touched && create_deficiency.nameShort.$invalid}">
                                            <div class="fg-line">
                                                <input type="text" name="nameShort" ng-model="nameShort"
                                                       class="form-control fg-input"
                                                       ng-minlength="3" ng-maxlength="25">
                                                <label class="fg-label">Deficiency Name Short</label>
                                            </div>
                                            <div ng-messages="create_deficiency.nameShort.$error" ng-show="create_deficiency.nameShort.$dirty">
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
                                        <div class="form-group m-b-30" ng-class="{ 'has-error' : (create_deficiency.defDescription.$invalid && !create_deficiency.defDescription.$pristine) || create_deficiency.defDescription.$touched && create_deficiency.defDescription.$invalid}">
                                            <div class="fg-line">
                                                <trix-editor  spellcheck="false" class="trix-content" ng-model="defDescription" angular-trix></trix-editor>
                                                <input type="hidden" ng-model="defDescription" ng-value="defDescription" name="defDescription"/>
                                                <!--<textarea class="form-control" rows="5" name="defDescription" ng-model="defDescription" placeholder="Deficiency description text here" data-auto-size></textarea>-->
                                            </div>
                                            <div ng-messages="create_deficiency.defDescription.$error" ng-show="create_deficiency.defDescription.compText.$dirty">
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
                                    <div class="col-sm-3 ind_image_cell">
                                        <div class="fileinput fileinput-new" data-provides="fileinput" style="margin:0px auto 25px auto;">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                            <div>
                                                <span class="btn btn-info btn-file">
                                                    <span class="fileinput-new">Select First</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input type="file" name="images[]"/>
                                                </span>
                                                <a href="#" class="btn btn-danger fileinput-exists remove" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Add Deficiency</button>
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
            /** for static file load
             var element = document.createElement("script");
             element.src = "defer.js";
             document.body.appendChild(element);
             */

            var form = document.getElementById('create_deficiency');
            var formSubmit = form.submit; //save reference to original submit function
            $(document).on('click', '.add', function(){
                var html = '';
                html += '<div class="col-sm-3 ind_image_cell">\n' +
                        '  <div class="fileinput fileinput-new" data-provides="fileinput" style="margin:0px auto 25px auto;">\n' +
                        '    <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>\n' +
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
                $(this).closest('.ind_image_cell').remove();
            });
        }
    </script>
@endsection
