@extends('layout.mainlayout')
@section('content')
<div class="container mb-4">
    <label for="year1">Year</label>
    <select name="year1" id="year1" class="form-control">
        <option value="all">No Filter</option>
        @foreach ($years as $year)
          <option value="{{$year->published_year}}">{{$year->published_year}}</option>
         @endforeach
    </select>
  </div>
<canvas id="myChart" class="container mt-3" ></canvas>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal" id="myModal2">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header justify-content-center">
            <h4 class="modal-title d-block "></h4>
        </div>
        <div class="modal-body">
            <div class="container-fluid mx-auto">
                <canvas id="subChart"></canvas>
            </div>
        </div>
    </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#year1, #sport').on('change',function(e){
        mainAjaxRequest();
    })

    $(window).on("load", function (e) {
        mainAjaxRequest();
    })

    function mainAjaxRequest(){
        
        $.ajax({
            type:'get',
            url:'{{route("chart.month.data")}}',
            data:{
                year:$('#year1').val(),
            },
            success:function(data){
                mainChartPrint(data);
            }
        })
        
    }

    function mainChartPrint(data){
        let chartStatus = Chart.getChart("myChart"); 
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        const canvas = document.getElementById('myChart')
        const ctx=canvas.getContext("2d");

        const chartData={
            labels: data.label,
            datasets: [{
            label: "Month Wise",
            data: data.value,
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            }]
        }

        const chartOption={
            aspectRatio:2,
            scales: {
                y: { beginAtZero: true}
            }
        }

        const mainChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options:chartOption,
        });

        canvas.onclick = function(e){
            const res = mainChart.getElementsAtEventForMode(e,'nearest',{intersect: true}, false);
            if (res.length === 0) return;
            const [{index}] = res;
            subChartAjax(data.label[index]);   
        };
    }

    function subChartAjax(month){
        $.ajax({
            type:'get',
            url:'{{route("chart.month.sport.data")}}',
            data:{
                year:$('#year1').val(),
                month:month
            },
            
            success:function(data){
                if(data.status == "success"){
                    subChartPrint(data);
                }
            }
        })
    }

    function subChartPrint(data){
        $('#myModal2').modal('show');
        $('.modal-title').text(data.title);
        
        let chartStatus = Chart.getChart("subChart"); // <canvas> id
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        const canvas2 = document.getElementById('subChart')
        const ctx = canvas2.getContext("2d");
        const subchartData={
            labels:data.label,
            datasets: [{
                data: data.value,
            }]
        };
        const subChartOption={
            aspectRatio:1,
            scales: {
                y: { beginAtZero: true}
            }
        }

        const subChart = new Chart(ctx, {
            type: 'pie',
            data: subchartData,
            options:subChartOption
        });
    };
</script>
@endsection