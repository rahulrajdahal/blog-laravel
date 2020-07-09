@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Create Post
    </div>

    <div class="card-body">
        <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Title: </label>
                <input type="text" name="title" placeholder="Post Title" class="form-control">
            </div>

            <div class="form-group">
                <label for="featured">Featured Image: </label>
                <input type="file" name="featured" class="form-control">
            </div>

            <div class="form-group">
                <select name="category_id" id="category_id" class="form-control">
                    <option selected>Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="content">Content: </label>
                <textarea name="content" id="content" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">Add Post</button>
            </div>
        </form>
    </div>
</div>


@endsection