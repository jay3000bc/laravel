@extends('main')
@section('title',"| Tag $tag->name")
@section('content')
	<div class="row">
		<div class="col-md-8">
			<h1>{{$tag->name}} Tag <small>{{ count($tag->posts)}} Posts</small></h1>
		</div>
		<div class="col-md-2">
			<a href="{{ route('tags.edit',$tag->id)}}" class="btn btn-primary btn-block">Edit</a>
		</div>
		<div class="col-md-2">
			<form action="/tags/{{$tag->id}}" method="post">
				{{ method_field('DELETE') }}
				{{ csrf_field() }}
				<button type="submit" class="btn btn-danger btn-block">Delete</button>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Tags</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($tag->posts as $post)
					<tr>
						<th>{{$post->id}}</th>
						<td>{{$post->title}}</td>
						<td>
							@foreach($post->tags as $tag)
							 <span class="label label-default">{{$tag->name}}</span>
							@endforeach
						</td>
						<td><a href="{{ route('posts.show',$post->id)}}" class="btn btn-default btn-sm">View</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@endsection