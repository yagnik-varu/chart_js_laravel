
@extends('layout.mainlayout')
@section('content')
<div class="container ">
  <div class="container mb-4">
    <label for="author">Author</label>
    <select name="author" id="authorList" class="form-control">
      @foreach ($authors as $author)
          <option value="{{$author->id}}">{{$author->name}}</option>
      @endforeach
    </select>
  </div>
  <div class="d-flex flex-row">
    <form class="ms-4" id="filterForm">
      <div class="d-inline-block">
        <label class="form-label" for="date-from">from</label>
        <input class="form-control" type="date" name="from" id="date-from">
      </div>
      <div class="d-inline-block">
        <label class="form-label" class="ms-3" for="date-to">to</label>
        <input class="form-control" type="date" name="to" id="date-to">
      </div>
    </form>

    <div id="charts" class="ms-5 d-flex flex-row mt-4">
      <div class="form-check ms-4">
        <label class="form-check-label" for="bar">Bar</label>
        <input type="radio" class="form-check-input" name="chart_type" id="bar" value="bar" checked>
      </div>  
      <div class="form-check ms-4">
        <label class="form-check-label" for="pie">Pie</label>
        <input type="radio" class="form-check-input" name="chart_type" id="pie" value="pie">
      </div>  
      <div class="form-check ms-4">
        <label class="form-check-label" for="line">Line</label>
        <input type="radio" class="form-check-input" name="chart_type" id="line" value="line">
      </div>  
      <div class="form-check ms-4">
        <label class="form-check-label" for="doughnut">doughnut</label>
        <input type="radio" class="form-check-input" name="chart_type" id="doughnut" value="doughnut">
      </div>  
      <div class="form-check ms-4">
        <label class="form-check-label" for="polarArea">polarArea</label>
        <input type="radio" class="form-check-input" name="chart_type" id="polarArea" value="polarArea">
      </div>  
      <div class="form-check ms-4">
        <label class="form-check-label" for="radar">radar</label>
        <input type="radio" class="form-check-input" name="chart_type" id="radar" value="radar">
      </div>  
    </div>

  </div>
  {{-- Canvas for drawing --}}
  <canvas id="myChart" class="container mt-3" ></canvas>
</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      
<script>
  var FetchedData;

  $(window).on("load", sendRequest); // Send request on load concisely

  $('#filterForm').submit(function(e) {
    e.preventDefault();
    sendRequest();
  });

  $('input:radio[name=chart_type]').on("change", function(e){
    prepareChart(FetchedData);
  });

  $('#authorList, #date-from, #date-to').change(function(e) {
    sendRequest();
    if(validateDate()){
        sendRequest();
    }else{
        alert('ending date can not be small from starting date');
        $('#date-to').val("")
    }
    console.log("Change detected");
  }); // Combined event listener

  function sendRequest() {
    const from = $('#date-from').val();
    const to = $('#date-to').val();
    const author = $('#authorList').val();
    const sendUrl = "{{route('allData',':id')}}".replace(':id', author);

    $.ajax({
      type: 'get',
      url: sendUrl,
      data: { from, to }, // Object destructuring
      success: function(data) {
        console.log(data);
        FetchedData = data.data;
        prepareChart(FetchedData);
      }
    });
  }

  function prepareChart(data) {
    // console.log(data);
    const chartStatus = Chart.getChart('myChart');

    if (chartStatus) {
      chartStatus.destroy();
    }

    const canvas = document.getElementById('myChart')
    const ctx = canvas.getContext('2d');

    const chartType = $('input:radio[name=chart_type]:checked').val();

    const chart = new Chart(ctx, {
      type: chartType,
      data: {
        labels: data.label,
        datasets: [{
          label: 'sport blogs',
          data: data.value,
          borderWidth: 1
        }]
      },
      options: {
        aspectRatio: 2,
        scales: {
          y: {
            beginAtZero: true
          }
        },
  
      }
    });

    // dddddddddddddddddddddddddddddddddddddddddddddd

    
   
  }

  function validateDate() { // Not used in provided code, but kept for reference
    console.log('validate date')
    var from = new Date($('#date-from').val());
    var to = new Date($('#date-to').val());
    if(to<from){
      return false;
    }else{
      return true;
    }
  }

</script>

@endsection

