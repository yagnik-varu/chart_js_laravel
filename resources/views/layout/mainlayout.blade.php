<!DOCTYPE html> 
<html lang="en"> 

<head> 
	<meta charset="UTF-8"> 
	<meta name="viewport"
		content="width=device-width, 
				initial-scale=1.0"> 
	<title>Charts</title> 
	
	<link rel="stylesheet" href= 
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
		integrity= 
"sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
		crossorigin="anonymous"
		referrerpolicy="no-referrer" /> 
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <style>
            html, body { 
                height: 100%; 
                font-family: 'Ubuntu', sans-serif; 
            } 

            .gfg { 
                height: 50px; 
                width: 50px; 

            } 

            .mynav { 
                color: #fff; 
            } 

            .mynav li a { 
                color: #fff; 
                text-decoration: none; 
                width: 100%; 
                display: block; 
                border-radius: 5px; 
                padding: 8px 5px; 
            } 

            .mynav li a.active { 
                background: rgba(255, 255, 255, 0.2); 
            } 

            .mynav li a:hover { 
                background: rgba(255, 255, 255, 0.2); 
            } 

            .mynav li a i { 
                width: 25px; 
                text-align: center; 
            } 

			.side-margine{
				margin-left: 280px;

			}
			

        </style>
</head> 

<body> 

	<div class="container-fluid p-0 d-flex "> 
		<div id="bdSidebar"
			class="d-flex flex-column position-fixed
					flex-shrink-0 
					p-3 bg-dark h-100
					text-white offcanvas-md offcanvas-start"> 
			<a href="#"
			class="navbar-brand fs-1 ms-5 me-5"> Charts
			</a><hr> 
			
			<ul class="mynav nav nav-pills flex-column "> 
				<li class="nav-item mb-1"> 
					<a href="{{route('chart.yearMonth.view')}}"> 
						<i class="fa-solid fa-chart-simple"></i> 
						Chart
					</a> 
				</li> 
				<li class="nav-item mb-1"> 
					<a href="{{route('chart.index')}}"> 
						<i class="fa-regular fa-user"></i> 
						Author
					</a> 
				</li> 
				<li class="nav-item mb-1"> 
					<a href="{{route('chart.year.view')}}"> 
						{{-- <i class="fa-regular fa-user"></i>  --}}
						<i class="fa-solid fa-code-compare"></i>
						Compare Yearly
					</a> 
				</li> 

			</ul> 
			{{-- <hr>  --}}
			
		</div> 

		<div class="bg-light flex-fill h-100 "> 
			<div class="p-2 d-md-none d-flex text-white bg-success"> 
				<a href="#" class="text-white"
				data-bs-toggle="offcanvas"
				data-bs-target="#bdSidebar"> 
					<i class="fa-solid fa-bars"></i> 
				</a> 
				<span class="ms-3">GFG Portal</span> 
			</div> 
			<div class="p-4 side-margine"> 
				<div class="row"> 
					<div class="col"> 
						@yield('content')
					</div> 
				</div> 
			</div> 

		</div> 
	</div>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    @yield('scripts')
</body> 

</html>
