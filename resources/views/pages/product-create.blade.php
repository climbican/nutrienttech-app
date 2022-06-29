@extends('layouts.app')
@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Add new Product</h2>
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
                <form name="create_product" id="create_product" method="post" action="{{url('admin/product/save')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="card-body card-padding">
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-sm-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput" style="margin:0px auto 25px auto;">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
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
                                                <input id="isActive" name="isActive" type="checkbox" hidden="hidden" value="1" checked>
                                                <label for="isActive" class="ts-helper"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="toggle-switch" data-ts-color="blue">
                                                <label for="ts3" class="ts-label">Is Visible in <strong>Mobile APP</strong></label>
                                                <input id="showFlag" name="showFlag" type="checkbox" hidden="hidden" value="1" checked>
                                                <label for="isActive" class="ts-helper"></label>
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
                                                @foreach($product_group as $pg)<option value="{{$pg->id}}">{{$pg->name}}</option>@endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group fg-float m-b-30 fg-toggled"
                                             ng-class="{ 'has-error' : (create_product.productName.$invalid && !create_product.productName.$pristine) || create_product.productName.$touched && create_product.productName.$invalid}">
                                            <div class="fg-line">
                                                <input type="text" name="productName" ng-model="productName"
                                                       class="form-control fg-input"
                                                       ng-minlength="3" ng-maxlength="45">
                                                <label class="fg-label">Product Name</label>
                                            </div>
                                            <div ng-messages="create_product.productName.$error" ng-show="create_product.productName.$dirty">
                                                <small class="help-block" ng-message="minlength">This too short</small>
                                                <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                                                <small class="help-block" ng-message="required">This field is required</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group fg-float m-b-30 fg-toggled"
                                             ng-class="{ 'has-error' : (create_product.subTitle.$invalid && !create_product.subTitle.$pristine) || create_product.subTitle.$touched && create_product.subTitle.$invalid}">
                                            <div class="fg-line">
                                                <input type="text" name="subTitle" ng-model="subTitle"
                                                       class="form-control fg-input"
                                                       ng-minlength="3" ng-maxlength="25">
                                                <label class="fg-label">Sub Title</label>
                                            </div>
                                            <div ng-messages="create_product.subTitle.$error" ng-show="create_product.subTitle.$dirty">
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
                                        <div class="form-group m-b-30" ng-class="{ 'has-error' : (create_product.description.$invalid && !create_product.description.$pristine) || create_product.description.$touched && create_product.description.$invalid}">
                                            <div class="fg-line">
                                                <label for="description">Description Text</label>
                                                <trix-editor  spellcheck="false" class="trix-content" ng-model="description" angular-trix></trix-editor>
                                                <input type="hidden" ng-model="description" ng-value="description" name="description"/>
                                                <!--<textarea class="form-control" rows="5" name="description" ng-model="description" placeholder="Product description text here" data-auto-size></textarea>-->
                                            </div>
                                            <div ng-messages="create_product.description.$error" ng-show="create_product.description.compText.$dirty">
                                                <small class="help-block" ng-message="maxlength">Try and keep it less than 1,000 characters</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ADD PRODUCT BENEFITS -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group m-b-30" ng-class="{ 'has-error' : (create_product.benefits.$invalid && !create_product.benefits.$pristine) || create_product.benefits.$touched && create_product.benefits.$invalid}">
                                            <div class="fg-line">
                                                <label for="description">Benefits Text</label>
                                                <trix-editor  spellcheck="false" class="trix-content" ng-model="benefits" angular-trix></trix-editor>
                                                <input type="hidden" ng-model="benefits" ng-value="benefits" name="benefits"/>
                                                <!--<textarea class="form-control" rows="5" name="benefits" ng-model="benefits" placeholder="Product benefits exa: <li>benefit one</li>" data-auto-size></textarea>-->
                                            </div>
                                            <div ng-messages="create_product.benefits.$error" ng-show="create_product.benefits.compText.$dirty">
                                                <small class="help-block" ng-message="maxlength">Try and keep it less than 1,000 characters</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- DILUTION -->
                                <div class="row">
                                    <div class="col-sm-12">
                                       <!-- <p class="c-black f-500 m-t-20 m-b-20">title</p>-->
                                        <div class="form-group m-b-30" ng-class="{ 'has-error' : (create_product.dilution.$invalid && !create_product.dilution.$pristine) || create_product.dilution.$touched && create_product.dilution.$invalid}">
                                            <div class="fg-line">
                                                <label for="description">Dilution Text</label>
                                                <trix-editor  spellcheck="false" class="trix-content" ng-model="dilution" angular-trix></trix-editor>
                                                <input type="hidden" ng-model="dilution" ng-value="dilution" name="dilution"/>
                                                <!--<textarea class="form-control" rows="5" name="dilution" ng-model="dilution" placeholder="Dilution Text here" data-auto-size></textarea>-->
                                            </div>
                                            <div ng-messages="create_product.dilution.$error" ng-show="create_product.dilution.$dirty">
                                                <small class="help-block" ng-message="maxlength">Try and keep it less than 1,000 characters</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             <!-- PRODUCT COMPATIBILITY -->
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-6">
                                        <div class=" form-group">
                                           <!-- <label for="compatibility">Compatibility</label>-->
                                            <select chosen
                                                    ng-model="compatibilityType"
                                                    name="compatibilityType"
                                                    data-placeholder="Compatibility Statement..." class="w-100" ng-options="item.id as item.name for item in compatSelect">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group fg-float m-b-30 fg-toggled"
                                             ng-class="{ 'has-error' : (create_product.netContents.$invalid && !create_product.netContents.$pristine) || create_product.netContents.$touched && create_product.netContents.$invalid}">
                                            <div class="fg-line">
                                                <input type="text" name="netContents" ng-model="netContents"
                                                       class="form-control fg-input"
                                                       ng-minlength="3" ng-maxlength="45">
                                                <label class="fg-label">Net Contents</label>
                                            </div>
                                            <div ng-messages="create_product.subTitle.$error" ng-show="create_product.subTitle.$dirty">
                                                <small class="help-block" ng-message="minlength">This too short</small>
                                                <small class="help-block" ng-message="maxlength">Sorry we can only take 45 characters</small>
                                                <small class="help-block" ng-message="required">This field is required</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END 14 -->
                                <div class="card">
                                    <div class="card-header">
                                        <h2>Product Nutrient List <small>List of nutrients in the product, specify guaranteed or not with checkbox</small></h2>
                                    </div>

                                    <div class="card-body table-responsive">
                                        <table class="table" id="item_table">
                                            <tr>
                                                <th>Nutrient Name</th>
                                                <th>Percent / PPM </th>
                                                <th>Is Guaranteed</th>
                                                <th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm m-t-5">Add Product</button>
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

                var form = document.getElementById('create_product');
                var formSubmit = form.submit; //save reference to original submit function
                $(document).on('click', '.add', function(){
                    var html = '';
                    html += '<tr>';
                    html += '<td class="form-group"><div class="fg-line"><select  name="elementList[]" data-placeholder="Select a Element..." class="form-control elementList"><option value="">Select one</option>\n' +
                        '@foreach($elements as $e)\n' +
                        '<option value="{{$e->id}}">{{$e->element_name}}</option>@endforeach</select></div></td>';
                    html += '<td class="form-group fg-float m-b-30 fg-toggled"><div class="fg-line">\n' +
                        '   <input type="text" name="percentPPM[]" class="form-control fg-input percentPPM" ng-minlength="1" ng-maxlength="25">\n' +
                        '   <label class="fg-label">Percent / PPM</label>\n' +
                        '   </td>';
                    html += '<td class="checkbox" style="margin:0px;padding-top:21px;padding-bottom:0px;"><label><input type="checkbox" value="1" name="guaranteedAmt[]" class="guaranteedAmt" checked><i class="input-helper"></i>Is Guaranteed</label></td>';
                    html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';

                    $('#item_table').append(html);
                });

                $(document).on('click', '.remove', function(){
                    $(this).closest('tr').remove();
                });

                $('#create_product').submit(function(event){
                    var form = this;
                    event.preventDefault();
                    var error = '';
                    $('.elementList').each(function(){
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
