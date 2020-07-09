@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Edit Post: {{ $post->title }}
    </div>

    <div class="card-body">
        <form action="{{ route('post.update', ['post' => $post->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}

            <div class="form-group">
                <label for="title">Title: </label>
                <input type="text" name="title" id="title" value="{{ $post->title }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="featured">Featured Image: </label>
                <input type="file" name="featured" id="featured" class="form-control">
            </div>

            <div class="form-group">
                <select name="category_id" id="category_id" class="form-control">
                    <option selected>Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if($category->id == $post->category_id) selected @endif>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="content">Content: </label>
                <textarea name="content" id="content" cols="30" rows="10" class="form-control">{{ $post->content }}</textarea>
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">Update Post</button>
            </div>
        </form>
    </div>
</div>


@endsection