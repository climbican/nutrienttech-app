@extends('layouts.app')

@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Welcome to the Nutritech Mobile App Admin {{ Auth::user()->name }}</h2>
                <p>You can manage app data from this location.</p>
            </div>
        @if (count($errors) > 0)
            <!-- SHOW GENERAL ERRORS NEED TO FORMAT THEM AS GROWL OR OTHER -->
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>
@endsection
