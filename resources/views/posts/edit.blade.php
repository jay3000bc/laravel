@extends('main')
@section('title','| Edit Post')
@section('stylesheets')
{!! Html::style('css/select2.min.css') !!}
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>
@endsection
<style type="text/css">
    .mce-notification-inner,#mceu_29 {
        display: none!important;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Edit Post</h1>
            <hr>
            <form  action="/posts/{{$post->id}}" method="POST" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="{{ $post->title}}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="slug">Slug:</label>
                    <input type="text" id="slug" name="slug" value="{{ $post->slug}}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category_id">Category:</label>
                    <select class="form-control" name="category_id">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" {{ ($category->id == $post->category_id) ? 'selected' : '' }}>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tags">Tags:</label>
                    <select class="form-control select2-multi" name="tags[]" multiple="multiple">
                        @foreach($tags as $tag)
                        <option value="{{$tag->id }}">{{$tag->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                        <label for="image"> Uplaod Image:</label>
                        <input type="file" id="image" name="image">
                    </div>
                <div class="form-group">
                    <label for="body">Body:</label>
                    <textarea id="body" name="body" class="form-control">{{ $post->body}}</textarea>
                </div>
                <div class="text-center">
                     <a href="/posts/{{$post->id}}" class="btn btn-primary">Cancel</a>  
                    <input type="submit" name="submit" value="Save Changes" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    {!! Html::script('js/select2.min.js') !!}
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2-multi').select2();
            $('.select2-multi').select2().val({!! json_encode($post->tags()->getRelatedIds()) !!}).trigger('change');

        });
    </script>
 @endsection