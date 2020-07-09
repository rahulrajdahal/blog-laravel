@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Categories
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </thead>

            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>
                        {{ $category->name }}
                    </td>
                    <td>
                        <a href="{{ route('category.edit', ['category' => $category->id]) }}" class="btn btn-info btn-sm">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('category.destroy', ['category' => $category->id]) }}" method="post">
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