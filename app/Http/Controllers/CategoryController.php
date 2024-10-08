<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *  schema="Category",
 *  title="Category",
 * 	@OA\Property(
 * 		property="title",
 * 		type="string"
 * 	),
 * )
 */
class CategoryController extends Controller
{
    use ValidatesRequests;

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Fetch all categories.",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Fetch all tags"
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json(['data' => $categories, 'message' => "Categories fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     *     summary="Fetch the category with specific id.",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the category to be fetched.",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category fetched successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json(['data' => $category, 'message' => "Category fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     summary="Create a new Category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Category"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Category")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully."
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/v1/categories/{id}",
     *     summary="Update category with specified id.",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the category to be updated.",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Category"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Category")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category updated successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     summary="Update category with specified id.",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the category to be updated.",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Category"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Category")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category updated successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function updatePUT(Request $request, $id)
    {
        $this->validate($request, ['title' => 'required']);
        $this->update($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     summary="Delete category with specified id.",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the category to be deleted.",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted successfully."
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
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
