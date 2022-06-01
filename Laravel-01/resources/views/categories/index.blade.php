@extends('main')
@section('title','| Categories')
@section('content')
	<div class="row">
		<div class="col-md-8">
			<h4>Categories</h4>
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($categories as $category)
					<tr>
						<th>{{$category->id}}</th>
						<td>{{$category->name}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<div class="well">
				<form  action="/categories" method="POST">
					{{ csrf_field() }}
					<div class="form-group">
						<label>Category Name:</label>
						<input type="text" name="name" class="form-control" required>
					</div>
					<div class="text-center">
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection