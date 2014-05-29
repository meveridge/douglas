<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>{{ $pageTitle }}</title>
		<!-- Bootstrap core CSS -->
		<link href="http://localhost/douglas/public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<style>
			html {
				position: relative;
				min-height: 100%;
			}
			body {
				padding-top: 50px;
				/* Margin bottom by footer height */
				margin-bottom: 60px;
			}
			#footer {
				position: absolute;
				bottom: 0;
				width: 100%;
				/* Set the fixed height of the footer here */
				height: 60px;
				background-color: #f5f5f5;
			}
			#pageErrorAlert,#pageMessageAlert{
				position: absolute;
				top:60px;
				left:50%;
				z-index:3;
				padding:20px;
			}

			ul #navigationTree {
	            list-style-type: none;
	            padding-left: 0;
	            overflow-x:auto;
	            white-space: nowrap;
            }
            .navTreeSpan{
	            cursor:pointer;
	        }

            .glyphicon {
                font-size: 0.7em;
                margin-left: 5px;
                color: #1774E6;

	        a:hover{
	            position:absolute;
	            background-color: #f2f1f0;
	            border: 1px solid #ddd;
    	    }

	        

		</style>
	</head>
	@section('navbar')
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li {{ isset($activeLink) ? '' : 'class="active"' }}><a href="http://localhost/douglas/public/">Home</a></li>
						<li class="dropdown {{ isset($activeLink) && $activeLink == "article" ? 'active' : '' }}">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">Articles <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="http://localhost/douglas/public/article">View</a></li>
								<li><a href="http://localhost/douglas/public/article/create">New</a></li>
								<li><a href="#">Copy</a></li>
								<li><a href="#">Move</a></li>
							</ul>
						</li>
						<li><a href="#">Users</a></li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container -->
		</nav>
	@show

	@section('pageAlerts')
		<!-- Master Page Messages -->
		@if(Session::has('error'))
		<p class="bg-danger" id="pageErrorAlert">{{ $error = Session::get('error'); }}</p>
		@endif
		@if(Session::has('message'))
		<p class="bg-success" id="pageMessageAlert">{{ $message = Session::get('message'); }}</p>
		@endif
		
		
	@show

	<div class="container-fluid">
	<div class="row">
	
	@section('pageSidebar')
		<!-- Master Page SideBar -->
	@show

	@section('pageContent')
		<!-- Master Page Content -->
	@show

	</div>
	</div>

	@section('pageFooter')
		<!-- Page Footer -->
		<div id="footer">
			<div class="container">
				<p class="text-muted">Douglas by Mark Everidge</p>
			</div>
		</div>
	@show

	@section('pageJSLoad')
	    <!-- Bootstrap core JavaScript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    <script src="http://localhost/douglas/public/jquery/jquery-2.1.1.min.js"></script>
	    <script src="http://localhost/douglas/public/bootstrap/js/bootstrap.min.js"></script>

		<script>
			$(document)
	    		.ready(function() {
	    			$("#pageErrorAlert").fadeOut(3600, function() {
	    				// Animation complete.
	  				});
	  				$("#pageMessageAlert").fadeOut(3600, function() {
	    				// Animation complete.
	  				});
	  			});
  		</script>

    @show

  </body>
</html>