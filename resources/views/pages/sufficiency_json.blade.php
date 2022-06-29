@extends('layouts.app')
@section('content')
    <section id="main">
        <h4 style="margin-left:15px;">JSON CONTENT FOR USE IN APP</h4>
        <div id="json_content" style="margin:15px;">
            Waiting for rendering of JSON
        </div>
    </section>
    <script>
        var pullJson = new XMLHttpRequest();
        pullJson.open('GET','{{url('admin/sufficiency/json_data')}}');
        pullJson.setRequestHeader("Content-Type", "application/json; charset=UTF8");
        pullJson.onreadystatechange = function () {
            if (pullJson.readyState === 4) {
                const p = JSON.parse(pullJson.responseText);
                const t = JSON.stringify(p.list);
                document.getElementById('json_content').innerHTML = '<pre><code class="json">' + t.replace(/"(\w+)"\s*:/g, '$1:') + '</pre></code>';
            }
        };
        pullJson.send();
    </script>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/highlight.min.js"></script>
@endsection