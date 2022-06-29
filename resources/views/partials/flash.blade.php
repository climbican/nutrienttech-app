<!--system messages -->
@if(Session::has('flash_message'))
    <div data-ng-init="userMessage('{{Session::get("flash_message")}}', '{{Session::get("message_type")}}')"></div>
    <script type="text/javascript">
        console.log('session flash executed');
    </script>

@endif

@if(Session::has('user_message'))
    <div data-ng-init="userMessage('{{Session::pull("user_message")}}', '{{Session::pull("message_type_get")}}')"></div>
@endif
