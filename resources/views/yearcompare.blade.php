@extends('layout.mainlayout')
@section('content')
<div class="container mb-4">
    <label for="year1">Year-1</label>
    <select name="year1" id="year1" class="form-control">
        @foreach ($years as $year)
          <option value="{{$year->published_year}}">{{$year->published_year}}</option>
      @endforeach
    </select>
    <label for="year2">Year-2</label>
    <select name="year2" id="year2" class="form-control">
        @foreach ($years as $year)
          <option value="{{$year->published_year}}">{{$year->published_year}}</option>
      @endforeach
    </select>
  </div>
<canvas id="myChart" class="container mt-3" ></canvas>

@endsection

@section('scripts')
<script>
    $('#year1, #year2').on('change',function(e){
        sendRequest();
    })

    $(window).on("load", function (e) {
        sendRequest();
    })

    function sendRequest(){
        $.ajax({
            type:'get',
            url:'{{route("chart.year.data")}}',
            data:{
                year1:$('#year1').val(),
                year2:$('#year2').val()
            },
            
            success:function(data){
                console.log(data)
                printChart(data)
            }
        })  
    }

    function printChart(data){
        let chartStatus = Chart.getChart("myChart"); // <canvas> id
      // if chart is exist we clear it
        if (chartStatus != undefined) {
        chartStatus.destroy();
        }
   
        const ctx = document.getElementById('myChart').getContext("2d");
            const mixedChart = new Chart(ctx, {
                data: {
                    labels: data.labels,
                    datasets: [{
                        type: 'line',
                        label: data.label1,
                        data: data.value1,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill:true,
                    }, {
                        type: 'line',
                        label: data.label2,
                        data: data.value2,
                        fill: true,
                        borderColor: 'rgb(54, 162, 235)'
                    }]
                },
                options:{
                    aspectRatio:2,
                    scales: {
                    y: {
                        beginAtZero: true
                    }
                    }
                }
            });
    }
</script>

@endsection