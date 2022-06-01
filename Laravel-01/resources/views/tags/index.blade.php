@extends('main')
@section('title','| Tags')
@section('content')
<div class="row">
		<div class="col-md-8">
			<h4>Tags</h4>
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($tags as $tag)
					<tr>
						<th>{{$tag->id}}</th>
						<td><a href="{{ route('tags.show',$tag->id)}}">{{$tag->name}}</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<div class="well">
				<form  action="/tags" method="POST">
					{{ csrf_field() }}
					<div class="form-group">
						<label>Tag Name:</label>
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