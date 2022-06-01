@extends('main')
@section('title',"| $post->title")
@section('content')
<img src="{{asset('images/'.$post->images)}}" height="100" width="100">
<h3>{{$post->title}}</h3>
<p>{!! $post->body !!}</p>
<form action="/comments/{{$post->id}}" method="POST">
	{{ csrf_field() }}
	<div class="row">
		<div class="col-md-6">
			<input type="text" name="name" class="form-control" placeholder="Name">
		</div>
		<div class="col-md-6">
			<input type="email" name="email" class="form-control" placeholder="Email">
		</div>
	</div><br>
	<div class="row">
		<div class="col-md-12">
			<textarea name="comment" class="form-control">Type Your Comment</textarea>
		</div>
	</div><br>
	<div class="row">	
		<div class="col-md-12">
			<button type="submit" class="btn btn-success btn-block">Add Comment</button>
		</div>
	</div>
</form>
<div class="row">
	<div class="col-md-12">
		<h3>Previous Comments</h3>
		<ul>
			@foreach($post->comments as $comment)
			<li>{{ $comment->comment}}<a href="#"><i class="fa fa-pencil"></i></a><br>{{$comment->name}}</li>

			<hr>
			@endforeach
		</ul>
	</div>
</div>

<hr>
<p>Posted In: {{ $post->category->name}}</p>
@endsection
