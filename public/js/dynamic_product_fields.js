$(document).ready(function(){

    var form = document.getElementById('create_product');
    var formSubmit = form.submit; //save reference to original submit function
    $(document).on('click', '.add', function(){
        var html = '';
        html += '<tr style="line-height:50px;padding-bottom:10px;">';
        html += '<td class="form-group"><div class="fg-line"><select  name="elementList[]" data-placeholder="Select a Element..." class="form-control elementList"><option value="">Select one</option>\n' +
            '@foreach($elements as $e)\n' +
            '<option value="{{$e->id}}">{{$e->element_name}}</option>@endforeach</select></div></td>';
        html += '<td class="form-group fg-float m-b-30 fg-toggled"><div class="fg-line">\n' +
            '   <input type="text" name="percentPPM" class="form-control fg-input percentPPM" ng-minlength="1" ng-maxlength="25">\n' +
            '   <label class="fg-label">Percent / PPM</label>\n' +
            '   </td>';
        html += '<td class="checkbox"><label><input type="checkbox" value="1" name="guaranteedAmt[]" class="guaranteedAmt" checked><i class="input-helper"></i>Is Guaranteed</label></td>';
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
            if($(this).val() == '')
            {
                error += "<p>Enter Item Name at "+count+" Row</p>";
                return false;
            }
            count = count + 1;
        });

        $('.percentPPM').each(function(){
            var count = 1;
            if($(this).val() == '')
            {
                error += "<p>Enter Item Quantity at "+count+" Row</p>";
                return false;
            }
            count = count + 1;
        });

        $('.guaranteedAmt').each(function(e){
            if(!$(this).prop('checked'))
            {
                this.value = 0;
                $('.guaranteedAmt').prop('checked', true);
            }
        });
        var form_data = $(this).serialize();
        form.submit();
    });

});