<span ng-switch on="field.type.view">
    <span ng-switch-when="select">
        <div class="col-sm-3 m-b-15">
            <select chosen
                    ng-model="entity[field.name]"
                    name="<%field.name%>"
                    id="<%field.name%>"
                    data-placeholder="Select a Element..." class="w-100"
                    ng-options="item.id as item.name for item in field.type.options">
            </select>
        </div>
    </span>
    <span ng-switch-when="input">
        <div class="form-group fg-float m-b-30 fg-toggled"
             ng-class="{ 'has-error' : (create_product.percent.$invalid && !create_product.percent.$pristine) || create_product.percent.$touched && create_product.percent.$invalid}">
            <div class="fg-line">
                <input type="text"
                       name='<% "percent_"+ $index %>'
                       id='<% "percent_"+ $index %>'
                       class="form-control fg-input"
                       ng-minlength="1" ng-maxlength="25">
                <label class="fg-label">Percent</label>
            </div>
            <div ng-messages="create_product.percent.$error" ng-show="create_product.percent.$dirty">
                <small class="help-block" ng-message="minlength">This too short</small>
                <small class="help-block" ng-message="maxlength">Sorry we can only take 30 characters</small>
                <small class="help-block" ng-message="required">This field is required</small>
            </div>
        </div>
    </span>
</span>