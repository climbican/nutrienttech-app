@extends('layouts.app')
@section('content')
    <section id="main">
        <h4 style="margin-left:15px;">JSON CONTENT FOR USE IN APP</h4>
        <pre><code class="language-json">{{$fields}}</code></pre>
    </section>

    <link rel="stylesheet" href="{{url('css/prism.css')}}"/>
    <script src="{{url('js/prism.js')}}"></script>
@endsection