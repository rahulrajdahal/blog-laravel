@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Posts
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <th>Name</th>
                <th>Image</th>
                <th>Edit</th>
                <th>Delete</th>
            </thead>

            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>
                        {{ $post->title }}
                    </td>
                    <td>
                        <img src="{{ asset($post->featured) }}" alt="{{ $post->title }}" height="50px" width="70px">
                    </td>
                    <td>
                        <a href="{{ route('post.edit', ['post' => $post->id]) }}" class="btn btn-info btn-sm">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('post.destroy', ['post' => $post->id]) }}" method="post">
                            @csrf
                            {{ method_field('DELETE') }}

                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection