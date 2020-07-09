@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Edit Category: {{ $category->name }}
    </div>

    <div class="card-body">
        <form action="{{ route('category.update', ['category' => $category->id]) }}" method="post">
            @csrf
            {{ method_field('PUT') }}

            <div class="form-group">
                <label for="name">Name: </label>
                <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control">
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">Update Category</button>
            </div>
        </form>
    </div>
</div>


@endsection