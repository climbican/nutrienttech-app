@extends('layouts.app')
@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Update Product ({{$prod->productName}})</h2>
            </div>
            <!-- error messages TODO:  left off here , use the same model as the SUFFICIENCY PAGE -->
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
                <div class="card-body" ng-init="initializeProdValues('{{$prod->description}}', '{{$prod->benefits}}', '{{$prod->dilution}}')">
                    <form name="update_product" id="update_product" method="post" action="{{url('admin/product/update'.'/'.$prod->id)}}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="card-body card-padding">
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-sm-3">
                                    <div class="fileinput fileinput-new" data-provides="fileinput" style="margin:0px auto 25px auto;">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"><img src="{{url('images/product/'.$prod->image_url)}}" style="margin-top:1px;"/></div>
                                        <div>
                                <span class="btn btn-info btn-file">
                                    <span class="fileinput-new">Product label / Logo</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="productImage" ng-model="productImage" id="productImage">
                                </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row" style="margin-bottom:20px;">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="toggle-switch" data-ts-color="blue">
                                                    <label for="ts3" class="ts-label">Is Active Product</label>
                                                    <input id="isActive" name="isActive" type="checkbox" hidden="hidden" value="1" @if($prod->active) checked @endif>
                                                    <label for="isActive" class="ts-helper"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="toggle-switch" data-ts-color="blue">
                                                    <label for="ts3" class="ts-label">Is Visible in <strong>Mobile APP</strong></label>
                                                    <input id="showFlag" name="showFlag" type="checkbox" hidden="hidden" value="1" @if($prod->show_flag) checked @endif>
                                                    <label for="showFlag" class="ts-helper"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom:20px;">
                                        <div class="col-sm-6">
                                            <div class=" form-group">
                                                <!-- <label for="compatibility">Compatibility</label>-->
                                                <select chosen
                                                        name="productGroup"
                                                        data-placeholder="Product Group" class="w-100">
                                                    <option value="none">-PRODUCT GROUP-</option>
                                                    @foreach($product_group as $pg)<option value="{{$pg->id}}" @if($pg->id == $prod->productGroupID){{'selected'}}@endif>{{$pg->name}}</option>@endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group m-b-30 fg-toggled"
                                                 ng-class="{ 'has-error' : (update_product.productName.$invalid && !update_product.productName.$pristine) || update_product.productName.$touched && update_product.productName.$invalid}">
                                                <div class="fg-line">
                                                    <label class="fg-label">Product Name</label>
                                                    <input type="text" name="productName" value="{{$prod->productName}}" id="productName"
                                                           class="form-control fg-input"
                                                           ng-minlength="3" ng-maxlength="45">
                                                </div>
                                                <div ng-messages="update_product.productName.$error" ng-show="update_product.productName.$dirty">
                                                    <small class="help-block" ng-message="minlength">This too short</small>
                                                    <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                                                    <small class="help-block" ng-message="required">This field is required</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group m-b-30 fg-toggled"
                                                 ng-class="{ 'has-error' : (update_product.subTitle.$invalid && !update_product.subTitle.$pristine) || update_product.subTitle.$touched && update_product.subTitle.$invalid}">
                                                <div class="fg-line">
                                                    <label class="fg-label">Sub Title</label>
                                                    <input type="text" name="subTitle" value="{{$prod->subTitle}}"
                                                           class="form-control fg-input"
                                                           ng-minlength="3" ng-maxlength="25">
                                                </div>
                                                <div ng-messages="update_product.subTitle.$error" ng-show="update_product.subTitle.$dirty">
                                                    <small class="help-block" ng-message="minlength">This too short</small>
                                                    <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                                                    <small class="help-block" ng-message="required">This field is required</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ADD PRODUCT DESCRIPTION -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group m-b-30" ng-class="{ 'has-error' : (update_product.description.$invalid && !update_product.description.$pristine) || update_product.description.$touched && update_product.description.$invalid}">
                                                <label class="fg-label" style="font-weight:bold;">Description</label>
                                                <div class="fg-line">
                                                    <trix-editor  spellcheck="false" class="trix-content" ng-model="description" angular-trix></trix-editor>
                                                    <input type="hidden" ng-model="description" ng-value="description" name="description"/>
                                                    <!--<textarea class="form-control" rows="5" name="description" value="{{$prod->description}}" placeholder="Product description text here" data-auto-size>{{$prod->description}}</textarea>-->
                                                </div>
                                                <div ng-messages="update_product.description.$error" ng-show="update_product.description.$dirty">
                                                    <small class="help-block" ng-message="maxlength">Try and keep it less than 1,000 characters</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ADD PRODUCT BENEFITS -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group m-b-30" ng-class="{ 'has-error' : (update_product.benefits.$invalid && !update_product.benefits.$pristine) || update_product.benefits.$touched && update_product.benefits.$invalid}">
                                                <label class="fg-label" style="font-weight:bold;">Benefits</label>
                                                <div class="fg-line">
                                                    <trix-editor  spellcheck="false" class="trix-content" ng-model="benefits" angular-trix></trix-editor>
                                                    <input type="hidden" ng-model="benefits" ng-value="benefits" name="benefits"/>
                                                </div>
                                                <div ng-messages="update_product.benefits.$error" ng-show="update_product.benefits.$dirty">
                                                    <small class="help-block" ng-message="maxlength">Try and keep it less than 1,000 characters</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- DILUTION -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <!-- <p class="c-black f-500 m-t-20 m-b-20">title</p>-->
                                            <div class="form-group m-b-30" ng-class="{ 'has-error' : (update_product.dilution.$invalid && !update_product.dilution.$pristine) || update_product.dilution.$touched && update_product.dilution.$invalid}">
                                                <label class="fg-label" style="font-weight:bold;">Dilution</label>
                                                <div class="fg-line">
                                                    <trix-editor  spellcheck="false" class="trix-content" ng-model="dilution" angular-trix></trix-editor>
                                                    <input type="hidden" ng-model="dilution" ng-value="dilution" name="dilution"/>
                                                </div>
                                                <div ng-messages="update_product.dilution.$error" ng-show="update_product.dilution.$dirty">
                                                    <small class="help-block" ng-message="maxlength">Try and keep it less than 1,000 characters</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom:10px;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Compatibility Statement</label>
                                                <select chosen
                                                        name="compatibilityType"
                                                        id="compatibilityType"
                                                        data-placeholder="Select One..." class="w-100">
                                                    @foreach($compat as $c)
                                                        <option value="{{$c->id}}" @if($prod->compatibility == $c->id){{'selected'}}@endif>{{$c->name_short}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group m-b-30 fg-toggled"
                                                 ng-class="{ 'has-error' : (create_product.netContents.$invalid && !create_product.netContents.$pristine) || create_product.netContents.$touched && create_product.netContents.$invalid}">
                                                <div class="fg-line">
                                                    <label class="fg-label">Net Contents</label>
                                                    <input type="text" name="netContents" value="{{$prod->net_contents}}"
                                                           class="form-control fg-input"
                                                           ng-minlength="3" ng-maxlength="45">
                                                </div>
                                                <div ng-messages="create_product.subTitle.$error" ng-show="create_product.netContents.$dirty">
                                                    <small class="help-block" ng-message="minlength">This too short</small>
                                                    <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                                                    <small class="help-block" ng-message="required">This field is required</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SELECT INCLUDED INGREDIENTS / PERCENTAGES -->
                                    <div class="card" style="border:solid #fefefe 1px;">
                                        <div class="card-header">
                                            <h2>Product Nutrient List <small>List of nutrients in the product, specify guaranteed or not with checkbox</small></h2>
                                        </div>

                                        <div class="card-body table-responsive">
                                            <table class="table" id="item_table">
                                                <thead>
                                                    <tr>
                                                        <th>Nutrient Name</th>
                                                        <th>Percent / PPM </th>
                                                        <th>Is Guaranteed</th>
                                                        <th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($element_fields as $ef)
                                                    <tr>
                                                        <td class="form-group">
                                                            <div class="fg-line">
                                                                <select  name="elementList[]" data-placeholder="Select a Element..." class="form-control elementList">
                                                                    <option value="">Select one</option>
                                                                    @foreach($elements as $e)
                                                                        <option value="{{$e->id}}" @if($e->id === $ef->element_id){{'selected'}}@endif>{{$e->element_name}}</option>@endforeach
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td class="form-group fg-float m-b-30 fg-toggled">
                                                            <div class="fg-line">
                                                                <input type="text" name="percentPPM[]" value="{{$ef->percent}}" class="form-control fg-input percentPPM" ng-maxlength="25">
                                                            </div>
                                                        </td>
                                                        <td class="checkbox fg-float fg-toggled" style="margin:0px;padding-top:21px;padding-bottom:0px;">
                                                            <label><input type="checkbox" value="1" name="guaranteedAmt[]" class="guaranteedAmt" @if($ef->is_guaranteed_amt){{'checked'}}@endif><i class="input-helper"></i>Is Guaranteed</label></td>
                                                        <td>
                                                            <button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-primary btn-sm m-t-5">Update {{$prod->productName}}</button>
                                </div>
                            </div>
                        </div><!--end card body -->
                    </form>
                </div>
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

             NOTE: !!  I THINK THE ONLY WAY TO IMPROVE THIS PART IS TO ALLOW MICRO SERVER INTERACTIONS ALLOWING INDIVIDUAL ROWS TO BE DELETE / UPDATE ON THEIR OWN.
             OR --> OR --> BETTER --> -->

             USE JSON [{ID, AMT, GUARNTEED},  {ID, AMT, GUARNTEED}, {ID, AMT, GUARNTEED} ]

             AND STORE IN ONE ROW INSTEAD OF
             THIS WOULD ELIMINATE THE NEED FOR BRIDGE TABLES

             */

            var form = document.getElementById('update_product');
            var formSubmit = form.submit; //save reference to original submit function
            $(document).on('click', '.add', function(){
                var html = '';
                html += '<tr>';
                html += '<td class="form-group"><div class="fg-line"><select  name="elementList[]" data-placeholder="Select a Element..." class="form-control elementList"><option value="">Select one</option>\n' +
                    '@foreach($elements as $e)\n' +
                    '<option value="{{$e->id}}">{{$e->element_name}}</option>@endforeach</select></div></td>';
                html += '<td class="form-group fg-float m-b-30 fg-toggled"><div class="fg-line">\n' +
                    '<input type="text" name="percentPPM[]" class="form-control fg-input percentPPM"/>' +
                    '   <label class="fg-label">Percent / PPM</label>\n' +
                    '   </td>';
                html += '<td class="checkbox" style="margin:0px;padding-top:21px;padding-bottom:0px;"><label><input type="checkbox" value="1" name="guaranteedAmt[]" class="guaranteedAmt" checked><i class="input-helper"></i>Is Guaranteed</label></td>';
                html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';

                $('#item_table').append(html);
            });

            $(document).on('click', '.remove', function(){
                $(this).closest('tr').remove();
            });

            $('#update_product').submit(function(event){
                var form = this;
                event.preventDefault();
                var error = '';
                $('.elementList').each(function() {
                    var count = 1;
                    if($(this).val() == '') {
                        error += "<p>Enter Item Name at "+count+" Row</p>";
                        return false;
                    }
                    count = count + 1;
                });

                $('.percentPPM').each(function(){
                    var count = 1;
                    if($(this).val() == '') {
                        error += "<p>Enter Item Quantity at "+count+" Row</p>";
                        return false;
                    }
                    count = count + 1;
                });

                $('.guaranteedAmt').each(function(e){
                    if(!$(this).prop('checked')) {
                        this.value = 0;
                        $('.guaranteedAmt').prop('checked', true);
                    }
                });
                var form_data = $(this).serialize();
                form.submit();
            });
        }
    </script>
@endsection
