@extends('layouts.app')
@section('content')
    <section id="main">
        <div class="container">
            <div class="block-header">
                <h2>Android and Apple Analytics</h2>
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
            <div>
                <div style="width:100%;margin-top:20px;margin-bottom:40px;">
                    <div class="card" id="android_stats" style="width:100%;"></div>
                    <div class="card">Current total iOS installs 457</div>
                </div>
                <div class="card" id="ios_stats"></div>
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load('current', {'packages':['linechart']});
                    google.charts.setOnLoadCallback(drawChartApple);

                    function drawChartApple() {
                        var data = google.visualization.arrayToDataTable({!! $appleStats !!});

                        var options = {
                            title: 'iOS App Units',
                            curveType: 'function',
                            legend: { position: 'bottom' },
                            width: $(window).width()*0.8,
                            height: 400
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('android_stats'));

                        chart.draw(data, options);
                        window.onresize = function(event) { drawChartApple(); };
                    }

                    google.charts.setOnLoadCallback(drawChartAndroid);

                    function drawChartAndroid() {
                        var data = google.visualization.arrayToDataTable({!! $androidStats !!});

                        var options = {
                            title: 'Android Use Stats',
                            curveType: 'function',
                            legend: { position: 'bottom' },
                            width: $(window).width()*0.8,
                            height: 400,
                            selectionMode: 'multiple',
                            tooltip: {"trigger": 'both'},
                            aggregationTarget: 'none',
                            focusTarget: "category",
                            explorer : {
                                axis: 'horizontal',
                                actions: ['dragToZoom', 'rightClickToReset']
                            },
                            crosshair: {
                                trigger: "both",
                                orientation: 'vertical'
                            }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('ios_stats'));

                        chart.draw(data, options);
                        window.onresize = function(event) { drawChartAndroid(); };
                    }
                </script>

                <script>
                    const stats = {!! $androidStats !!};
                    console.log('android stats in JSON ' + JSON.stringify(stats));
                </script>
            </div>
        </div>
    </section>
