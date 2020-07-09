@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Create Category
    </div>

    <div class="card-body">
        <form action="{{ route('category.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label for="name">Name: </label>
                <input type="text" name="name" id="name" placeholder="Add Category" class="form-control">
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">Add Category</button>
            </div>
        </form>
    </div>
</div>


@endsection