<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json(['data' => $categories, 'message' => "Categories fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json(['data' => $category, 'message' => "Category fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required']);

        try {
            $category = Category::create(['title' => $request->title]);

            return response()->json(['data' => $category, 'message' => "Category Created!"], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function update(Request $request, $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json(['error' => $this->notFoundError('Category')], 404);
            }

            $category->title = $request->title;
            $category->update();

            return response()->json(['data' => $category, 'message' => 'Category updated'], 204);
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
            Category::destroy($id);

            return response()->json(['message' => 'Category deleted'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
