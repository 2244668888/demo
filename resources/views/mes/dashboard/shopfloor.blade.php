@extends('layouts.app')
@section('title')
    SHOPFLOOR
@endsection
@section('content')
    <style>
        .machine {
            display: inline-block;
            width: 80px;
            height: 200px;
            margin: 10px;
            text-align: center;
            line-height: 200px;
            color: black;
            font-weight: bold;
            border: 1px solid black;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
        }

        .bg-gray {
            background-color: rgb(207, 203, 203);
        }

        .m5-to-m12 {
            display: inline-block;
            width: 70px;
            height: 150px;
            margin: 10px;
            text-align: center;
            line-height: 150px;
            color: black;
            font-weight: bold;
            border: 1px solid black;
        }

        .section-title {
            display: inline-block;
            width: 100px;
            height: 100px;
            text-align: center;
            color: black;
            border: 1px solid black;
            font-size: 12px;
            line-height: 50px;
            margin: 10px;
        }

        .machine-2 {
            width: 400px;
            height: 100px;
            border: 1px solid black;
            display: inline-block;
            text-align: center;
            line-height: 100px;
            color: black;
            margin: 10px;
        }

        .machine-3 {
            width: 200px;
            height: 100px;
            border: 1px solid black;
            display: inline-block;
            text-align: center;
            line-height: 100px;
            color: black;
            margin: 10px;
        }

        .mold {
            display: inline-block;
            width: 50px;
            height: 90px;
            text-align: center;
            line-height: 100px;
            color: black;
            border: 1px solid black;
            margin: 10px;
        }

        .blink {
            animation: blink 3s linear infinite;
            border: 4px solid red;
        }

        @keyframes blink {
            50% {
                opacity: 0;
                border-color: transparent;
            }
        }

        hr {
            color: black;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .top_machine_div {
            display: flex;
            justify-content: flex-end;
        }

        .toilet {
            display: inline-block;
            width: 100px;
            text-align: center;
            color: black;
            border: 1px solid black;
            font-size: 12px;
            line-height: 50px;
            margin: 10px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-7 top_machine_div">
                    <div class="machine bg-gray" id="B3">B3</div>
                    <div class="machine bg-gray" id="B2">B2</div>
                    <div class="machine bg-gray" id="B1">B1</div>
                </div>
                <div class="col-md-5 text-center">
                    <div class="toilet">TOILET</div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="machine bg-gray" id="M1">M1</div>
                    <div class="machine bg-gray" id="M2">M2</div>
                    <div class="machine bg-gray" id="M3">M3</div>
                    <div class="machine bg-gray" id="M4">M4</div>
                </div>
                <div class="col-md-8">
                    <div class="m5-to-m12 bg-gray" id="M5">M5</div>
                    <div class="m5-to-m12 bg-gray" id="M6">M6</div>
                    <div class="m5-to-m12 bg-gray" id="M7">M7</div>
                    <div class="m5-to-m12 bg-gray" id="M8">M8</div>
                    <div class="m5-to-m12 bg-gray" id="M9">M9</div>
                    <div class="m5-to-m12 bg-gray" id="M10">M10</div>
                    <div class="m5-to-m12 bg-gray" id="M11">M11</div>
                    <div class="m5-to-m12 bg-gray" id="M12">M12</div>
                    <div class="section-title">INJECTION MOLD AREA</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-4">
                    <div class="machine-2 section-title">INJECTION MOLD STORAGE</div>
                    <div class="machine-3 section-title">MOLD MAINTENANCE</div>
                    <div class="machine mold bg-gray" id="M14">M14</div>
                    <div class="machine mold bg-gray" id="M13">M13</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            setInterval(renderData, 10000);

            function renderData() {
                $.ajax({
                    url: '{{ route('shopfloor.generate') }}',
                    type: 'GET',
                    success: function(response) {
                        $.each(response, function(key, data) {
                            var element = data;
                            var color = element.color;
                            $(`#${element.code}`).addClass(`bg-${color}`);
                            if (element.call_for_assistance) {
                                $(`#${element.code}`).addClass("blink");
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                    }
                });
            }

            renderData();
        });
    </script>
@endsection
