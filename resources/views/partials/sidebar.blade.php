<!DOCTYPE html>
<html lang="en">
	<head>
		@section('head')
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" media="screen" type="text/css">
			<!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="/css/app.css">
		@show
	</head>
	<body>

		<div class="container">

			<img src="/img/logo.png">
			<img src="/img/pt_logout_on.png">

			<div class="row">

			{{--*/  $errorClass = (session('flag'))?session('flag'):'info' /*--}}
			@if (count($errors) > 0)
				<div class="alert alert-{{$errorClass}}" role="alert">
					@foreach($errors->all() as $error)
						<p>
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<span class="sr-only">Error:</span>
							{{ $error }}
						</p>
					@endforeach
				</div>
			@endif

			  <br />
			  <div class="col-md-10 col-md-offset-1">
				  <div class="row">
				    <div class="col-md-3">
							<img class="img-thumbnail" alt="350x350" src="http://placehold.it/350x350">
							<br /><br />
							<div class="list-group">
				  	  	    <a class="list-group-item" href="/">Home</a>
				  	  	    <a class="list-group-item" href="/users/members">Members</a>
				  	  		<a class="list-group-item" href="/users/roles">Roles</a>
				  	  		<a class="list-group-item" href="/districts">Districts</a>
				  	  		<a class="list-group-item" href="/organizations">Organizations</a>
				  	  		<a class="list-group-item" href="/groups">Groups</a>
				  	  		<a class="list-group-item" href="/auth/logout">Logout</a>
				    		</div>
				    </div>
				    <div class="col-md-9">
				    	@yield('content')
				    </div>
				  </div>
			  </div>
			</div>

		</div>
		@section('footer_scripts')
			<script src="http://code.jquery.com/jquery.js"></script>
		    <!-- jQuery library -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		    <!-- Latest compiled JavaScript -->
			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		@show
	</body>
</html>