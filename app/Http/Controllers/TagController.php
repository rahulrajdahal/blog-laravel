<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        try {
            $tags = Tag::all();
            return response()->json(['data' => $tags, 'message' => "Tags fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $tag = Tag::findOrFail($id);

            return response()->json(['data' => $tag, 'message' => "Tag fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required']);

        try {
            $tag = Tag::create(['title' => $request->title]);

            return response()->json(['data' => $tag, 'message' => 'Tag Created!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {

        try {
            $tag = Tag::find($id);

            if (!$tag) {
                return response()->json(['error' => $this->notFoundError('Tag')], 404);
            }

            $tag->title = $request->title;
            $tag->update();

            return response()->json(['data' => $tag, 'message' => 'Tag updated'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updatePUT(Request $request, $id)
    {
        $this->validate($request, ['title' => 'required']);
        $this->update($request, $id);
    }
    public function destroy($id)
    {
        try {
            Tag::destroy($id);
            return response()->json(['message' => 'Tag deleted'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
