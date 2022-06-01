@extends('main')
@section('title',"| Edit Comment")
@section('content')
	<div class="row">
		<form method="POST" action="/comments/{{$comment->id}}">
		 	{{ method_field('PUT') }}
			{{ csrf_field() }}
			<div class="col-md-8 col-md-offset-2">
				<div class="row">
					<div class="col-md-6">
						<input type="text" name="name" class="form-control" value="{{$comment->name}}" disabled>
					</div>
					<div class="col-md-6">
						<input type="email" name="email" class="form-control" value="{{$comment->email}}" disabled>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<textarea name="comment" class="form-control">{{$comment->comment}}</textarea>
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn  btn-primary">Save Changes</button>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection