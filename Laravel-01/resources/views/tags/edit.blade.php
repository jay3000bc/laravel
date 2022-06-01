@extends('main')
@section('title','| Edit Tag')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<h3>Edit Tag</h3>
			<form action="/tags/{{$tag->id}}" method="post">
				{{ method_field('PUT') }}
                {{ csrf_field() }}
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="name" class="form-control" value="{{$tag->name}}">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success">Save Changes</button>
				</div>
			</form>
		</div>
	</div>
@endsection