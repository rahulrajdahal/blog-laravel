@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Create Tag
    </div>

    <div class="card-body">
        <form action="{{ route('tag.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label for="name">Name: </label>
                <input type="text" name="name" placeholder="Add tag" class="form-control">
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">Add Tag</button>
            </div>
        </form>
    </div>
</div>


@endsection