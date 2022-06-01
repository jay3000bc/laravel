@extends('main')

@section('title','| View Post')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<img src="{{asset('images/'.$post->images)}}" height="100" width="100">
				<h3>{{ $post->title}}</h3>
				<p>{!! $post->body !!}</p>
				<hr>
				<div>
					@foreach($post->tags as $tag) 
						<span class="label label-default">{{$tag->name}}</span>
					@endforeach	
				</div>
				<div class="comments">
					<h4>Comments <small>{{$post->comments()->count()}} total comments</small></h4>		
					<hr>
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Comment</th>
								<th>#</th>
							</tr>
						</thead>
						<tbody>
							@foreach($post->comments as $comment)
							<tr>
								<td>{{$comment->name}}</td>
								<td>{{$comment->comment}}</td>
								<td>
									<a href="/comments/{{$comment->id}}/edit" class="btn btn-primary">Edit</a>
									<a href="/comments/{{$comment->id}}" class="btn btn-danger">Delete</a>
									</td>
							</tr>
							@endforeach
						</tbody>
					</table>		
				</div>
			</div>
			<div class="col-md-4">
				<div class="well">
					<dl class="dl-horizontal">
						<label>
							Slug:
						</label>
						<p>
							<a href="{{url('blog/'.$post->slug)}}">{{url('blog/'.$post->slug)}}</a>							
						</p>
					</dl>
					<dl class="dl-horizontal">
						<label>
							Category:
						</label>
						<p>
							{{$post->category->name}}							
						</p>
					</dl>
					<dl class="dl-horizontal">
						<label>
							Created At:
						</label>
						<p>
							{{date('M j, Y h:ia',strtotime($post->created_at))}}
						</p>
					</dl>
					<dl class="dl-horizontal">
						<label>
							Last Updated:
						</label>
						<p>
							{{date('M j, Y h:ia',strtotime($post->updated_at))}}
						</p>
					</dl>
					<div class="row">
						<div class="col-md-6">
							<a href="/posts/{{$post->id}}/edit" class="btn btn-primary btn-block">Edit</a>
						</div>
						<div class="col-md-6">
						<!-- {!! Html::linkRoute('posts.destroy','Delete',array($post->id)) !!} -->
						<form action="/posts/{{$post->id}}" method="post">
							{{ method_field('DELETE') }}
							{{ csrf_field() }}
							<button type="submit" class="btn btn-danger btn-block">Delete</button>
						</form>
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<a href="/posts" class="btn btn-default btn-block" style="margin-top:20px;">Show All Posts</a>
						</div>
					</div>
				</div>
			 </div>
		</div>
	</div>
@endsection