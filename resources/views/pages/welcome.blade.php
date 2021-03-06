@extends('main')
@section('title','| Welcomepage')
@section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron">
                  <h1>Welcome To My blog</h1>
                  <p class="lead">Thank You for visiting.</p>
                  <p><a class="btn btn-primary btn-lg" href="#" role="button">Popular Post</a></p>
                </div>   
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
            @foreach($posts as $post)
                <div class="post">
                    
                    <h2>{{$post->title}}</h2>
                    <p>{{ substr(strip_tags($post->body),0,30)}}{{strlen(strip_tags($post->body)) > 30 ? "..." : ""}}<br>
                    <a href="{{url('blog/'.$post->slug)}}" class="btn btn-primary">read more</a></p>
                </div>
                <hr>
                @endforeach
            </div>

            <div class="col-md-3 col-md-offset-1">
                <h2>Sidebar</h2>
            </div>
        </div>    
    @endsection