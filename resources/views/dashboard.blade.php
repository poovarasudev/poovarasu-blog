@extends('layouts.app')

@section('title')
    Home Page
@endsection

@section('content')

    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>

    <section class="content">
        <h3>DASHBOARD</h3>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                <div class="card">
                    <div class="body">
                        <div id="barChartForMonths"></div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                <div class="card">
                    <div class="header">
                        <h2>Tags</h2>
                    </div>
                    <div class="body">
                            @foreach($tags as $tag)
                                @if($tag->posts_count >= 15)
                                    <button class="btn bg-green waves-effect m-b-5" type="button">{{ $tag->tag_name }} <span class="badge">{{ $tag->posts_count }}</span></button><br>
                                @elseif($tag->posts_count >= 10)
                                    <button class="btn bg-blue waves-effect m-b-5" type="button">{{ $tag->tag_name }} <span class="badge">{{ $tag->posts_count }}</span></button><br>
                                @elseif($tag->posts_count >= 5)
                                    <button class="btn bg-cyan waves-effect m-b-5" type="button">{{ $tag->tag_name }} <span class="badge">{{ $tag->posts_count }}</span></button><br>
                                @else
                                    @break
                                @endif
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                <div class="card">
                    <div class="body">
                        <div id="barChartForYears"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script>
        var current_year =new Date().getFullYear();
        var title_months = "Post vs User vs Months("+current_year+")";
        var title_years = "Post vs User vs Years";
        current_year-=8;
        var xaxis_value_years = [];
        for (var i = 0; i < 10; i++) {
            xaxis_value_years[i] = current_year++;
        }
            $.ajax({
                url: 'get-dashboard-datas',
                type: 'get',
                dataType: 'JSON',
                success: function (data) {
                    var result_data_for_months  = data[0];
                    var result_data_for_years  = data[1];
                    var xaxis_value_months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    chart(xaxis_value_months, result_data_for_months, title_months, "barChartForMonths", 1, 12);
                    chart(xaxis_value_years, result_data_for_years, title_years, "barChartForYears", xaxis_value_years[0], xaxis_value_years[xaxis_value_years.length-1]);

                }
            });

        function chart(xaxis_value, data, title, id, min, max){
            Highcharts.chart(id, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: title
                },
                xAxis: {
                    categories: xaxis_value,
                    min: min,
                    max: max
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Counts'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Posts',
                    data: data[0]
                },
                    {
                    name: 'Users',
                    data: data[1]

                }]
            });
        }



    </script>
@endsection

