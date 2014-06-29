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
		{{ HTML::style('deps/bootstrap/css/bootstrap.min.css'); }}
		{{ HTML::style('deps/octicons/octicons.css'); }}
		{{ HTML::style('css/main.css'); }}
		{{ HTML::style('css/sidebar.css'); }}
	</head>
	<body>
	<div class="container">

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
						<li {{ isset($activeLink) ? '' : 'class="active"' }}><a href="http://douglas/public/">Home</a></li>
						<li class="dropdown {{ isset($activeLink) && $activeLink == "article" ? 'active' : '' }}">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">Articles <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="http://douglas/article/index/web">View</a></li>
								<li><a href="http://douglas/article/create">New</a></li>
								<li><a href="#">Copy</a></li>
								<li><a href="#">Move</a></li>
							</ul>
						</li>
						 @if(!Auth::check())
                    <li>{{ HTML::link('users/login', 'Login') }}</li>
            @else
                    <li>{{ HTML::link('users/logout', 'Logout') }}</li>
            @endif
						<!--<li>{{ HTML::link('users/login', 'Login') }}</li>-->
						<!--<li><a href="#">Users</a></li>-->
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container -->
		</nav>
	@show

	@section('pageAlerts')
		<!-- Master Page Messages -->


		<div class="pageAlerts">
			<!-- Used in Backbone -->
			<p class="hidden" id="pageAlert"></p>

			<!-- Legacy... Used on page load. TODO: Reqork to backbone and remove -->
			@if(Session::has('error'))
			<p class="bg-danger" id="pageErrorAlert">{{ $error = Session::get('error'); }}</p>
			@endif
			@if(Session::has('message'))
			<p class="bg-success" id="pageMessageAlert">{{ $message = Session::get('message'); }}</p>
			@endif

		</div>


	@show


	<div class="row">

	@section('pageSidebar')
		<!-- Master Page SideBar -->
		<div class="col-md-1"></div>
	@show

	@section('pageContent')
		<!-- Master Page Content -->
	@show

	</div>
	</div>

	@section('pageFooter')
		<!-- Page Footer -->
		<div id="footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-2 text-muted">Douglas for SugarCRM</div>
					<div class="col-md-9 text-muted" id="actionsPane"></div>
					<div class="col-md-1 text-muted waitIndicator pull-right">
						<span id="pageFooterWaitText" class="small hidden"></span>
						<span id="pageFooterWaitIcon" class="glyphicon spin hidden"></span>
					</div>
				</div>
			</div>
		</div>
	@show

	@section('pageJSLoad')
	    <!-- Bootstrap core JavaScript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    {{ HTML::script('deps/jquery/jquery-2.1.1.min.js'); }}
	    {{ HTML::script('deps/bootstrap/js/bootstrap.min.js'); }}
	    {{ HTML::script('deps/underscore/underscore-1.6.0.min.js'); }}
	    {{ HTML::script('deps/backbone/backbone-1.1.2.min.js'); }}
	    {{ HTML::script('js/main_bb.js'); }}

    @show
  </div>
  </body>
</html>
