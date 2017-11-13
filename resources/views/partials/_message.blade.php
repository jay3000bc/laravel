@if (Session::has('success'))
	<p class="alert alert-success" role='alert'>
		<strong>Success:</strong>{{Session::get('success')}}
	</p>
@endif
@if (count($errors) > 0)
	<p class="alert alert-danger" role='alert'>
		<strong>Error:</strong>
		@foreach($errors->all() as $error)
		<ul>
			<li>{{$error}}</li>
		</ul>
		@endforeach
	</p>
@endif