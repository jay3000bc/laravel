@extends('main')
@section('title',"| Delete Comment")
@section('content')
	<div class="row">
		<div class="col-md-12">
			<form method="POST" action="/comments/{{$comment->id}}">
				{{ method_field('DELETE') }}
				{{ csrf_field() }}
				<p>Name:{{$comment->name}}</p>
				<p>Email:{{$comment->email}}</p>
				<p>Comment:{{$comment->comment}}</p>
				<h4>Do You Want To delete this comment</h4>
				<button type="submit" class="btn btn-danger">Yes</button>
				<a href="/posts/{{$comment->post->id}}" class="btn btn-success">No</a>
			</form>
		</div>
	</div>

@endsection