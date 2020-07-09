@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Tags
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </thead>

            <tbody>
                @foreach($tags as $tag)
                <tr>
                    <td>
                        {{ $tag->name }}
                    </td>
                    <td>
                        <a href="{{ route('tag.edit', ['tag' => $tag->id]) }}" class="btn btn-info btn-sm">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('tag.destroy', ['tag' => $tag->id]) }}" method="post">
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