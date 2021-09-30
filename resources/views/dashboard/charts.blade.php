@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                            <div class="card-tools" style="display: flex">
                                <div class="input-group input-group-sm" style="width: 50px;">
                                    <button onclick="downloadCanvas()" class="btn-sm btn-primary">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                                <!-- form date picker-->
                                <form method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="datepicker" class="form-control float-right" id="datepicker">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <canvas id="lineChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
// function to save canvas to image
function downloadCanvas() {
    var canvas = document.getElementById("lineChart");
    var img = canvas.toDataURL("image/png");
    var tab = window.open('about:blank', '_blank');
    tab.document.write('<img src="'+img+'"/>');
    tab.document.close();
}

$(function () {
    //Date range picker
    $('#datepicker').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    })
    //$('#datepicker').val("");
})

@foreach ($data as $key => $ref)
var var{{$key}} = {!! $data[$key] !!};
@endforeach

var colorsBorder = [
    'rgba(78, 121, 167, 1)',
    'rgba(160, 203, 232, 1)',
    'rgba(242, 142, 43, 1)',
    'rgba(255, 190, 125, 1)',
    'rgba(89, 161, 79, 1)',
    'rgba(140, 209, 125, 1)',
    'rgba(182, 153, 45, 1)',
    'rgba(241, 206, 99, 1)',
    'rgba(73, 152, 148, 1)',
    'rgba(134, 188, 182, 1)',
    'rgba(225, 87, 89, 1)',
    'rgba(255, 157, 154, 1)',
    'rgba(121, 112, 110, 1)',
    'rgba(186, 176, 172, 1)',
    'rgba(211, 114, 149, 1)',
    'rgba(250, 191, 210, 1)',
    'rgba(176, 122, 161, 1)',
    'rgba(212, 166, 200, 1)',
    'rgba(157, 118, 96, 1)',
    'rgba(215, 181, 166, 1)'
];

var colorsBackground = [
    'rgba(78, 121, 167, 0.4)',
    'rgba(160, 203, 232, 0.4)',
    'rgba(242, 142, 43, 0.4)',
    'rgba(255, 190, 125, 0.4)',
    'rgba(89, 161, 79, 0.4)',
    'rgba(140, 209, 125, 0.4)',
    'rgba(182, 153, 45, 0.4)',
    'rgba(241, 206, 99, 0.4)',
    'rgba(73, 152, 148, 0.4)',
    'rgba(134, 188, 182, 0.4)',
    'rgba(225, 87, 89, 0.4)',
    'rgba(255, 157, 154, 0.4)',
    'rgba(121, 112, 110, 0.4)',
    'rgba(186, 176, 172, 0.4)',
    'rgba(211, 114, 149, 0.4)',
    'rgba(250, 191, 210, 0.4)',
    'rgba(176, 122, 161, 0.4)',
    'rgba(212, 166, 200, 0.4)',
    'rgba(157, 118, 96, 0.4)',
    'rgba(215, 181, 166, 0.4)'
];

@foreach ($dataset as $key => $ref)
@foreach ($ref as $param)
{{-- get the time to the labels on the first iteration --}}
@if ($loop->parent->first && $loop->first)
var labels = var{{$key}}.{{$key}}.map(function (e) {
    return e.time;
});
@endif

var {{$key.$param}} = var{{$key}}.{{$key}}.map(function (e) {
    return e.{{$param}};
});
@endforeach
@endforeach

const CHART = document.getElementById('lineChart');

var lineChart = new Chart(CHART, {
    type: '{{$chart}}',
    data: {
        labels: labels,
        datasets: [
    @foreach ($dataset as $key => $ref)
        @foreach ($ref as $param)
            {
                label: '{{$key}} - {{$param}}',
                fill: false,
                backgroundColor: colorsBackground[{{$j}}],
                borderColor: colorsBorder[{{$j++}}],
                data: {{$key.$param}}
            },
        @endforeach
    @endforeach
        ]
    },
    options: {
        responsive: true,
        title: {
            display: true,
            text: '{{ $title }}'
        },
        scales: {
            xAxes: [{
                type: 'time',
                time: {
                    parser: 'X',
                    // round: 'day'                                                                                                                                                                            
                    tooltipFormat: 'DD-MM-YYYY HH:mm',
                    displayFormats: {
                        second: 'HH:mm:ss',
                        minute: 'HH:mm',
                        hour: 'HH'
                    }
                },
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Time'
                }
            }]
        }
    }
})

// hide dataset on title click
$("#toggle").click(function() {
    chartInstance.data.datasets.forEach(function(ds) {
        ds.hidden = !ds.hidden;
    });
    chartInstance.update();
});
@endsection