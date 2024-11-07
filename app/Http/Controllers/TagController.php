<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *  schema="Tag",
 *  title="Create a Tag",
 * 	@OA\Property(
 * 		property="title",
 * 		type="string"
 * 	),
 * )
 */



class TagController extends Controller
{
    use ValidatesRequests;

    /**
     * @OA\Get(
     *     path="/api/v1/tags",
     *     summary="Fetch all tags.",
     *     tags={"Tags"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Fetch all tags"
     *     )
     * )
     */
    public function index()
    {
        try {
            $tags = Tag::all();
            return response()->json(['data' => $tags, 'message' => "Tags fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags/{id}",
     *     summary="Fetch the tag with specific id.",
     *     tags={"Tags"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the tag to be fetched.",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag fetched successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Tag not found"
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
            $tag = Tag::findOrFail($id);

            return response()->json(['data' => $tag, 'message' => "Tag fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tags",
     *     summary="Create a new tag",
     *     tags={"Tags"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Tag"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Tag")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag created successfully."
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
            $tag = Tag::create(['title' => $request->title]);

            return response()->json(['data' => $tag, 'message' => 'Tag Created!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/tags/{id}",
     *     summary="Update tag with specified id.",
     *     tags={"Tags"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the tag to be updated.",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Tag"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Tag")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tag updated successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Tag not found"
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

    /**
     * @OA\Put(
     *     path="/api/v1/tags/{id}",
     *     summary="Update tag with specified id.",
     *     tags={"Tags"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the tag to be updated.",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Tag"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Tag")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tag updated successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Tag not found"
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
     *     path="/api/v1/tags/{id}",
     *     summary="Delete tag with specified id.",
     *     tags={"Tags"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the tag to be deleted.",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tag deleted successfully."
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
            Tag::destroy($id);
            return response()->json(['message' => 'Tag deleted'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
