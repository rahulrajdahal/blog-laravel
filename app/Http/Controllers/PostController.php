<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *  schema="Post",
 *  title="Post",
 * 	@OA\Property(
 * 		property="title",
 * 		type="string",
 * 		description="Title of the post.",
 * 	),
 * 	@OA\Property(
 * 		property="featured_image",
 * 		type="string",
 * 		format="binary",
 * 		description="Featured image for the post.",
 * 	),
 * 	@OA\Property(
 * 		property="category_id",
 * 		type="string",
 * 		description="Category Id the post belongs to.",
 * 	),
 * 	@OA\Property(
 * 		property="description",
 * 		type="string",
 * 		description="Description/content for the post.",
 * 	),
 * )
 */
class PostController extends Controller
{
    use ValidatesRequests;

    /**
     * @OA\Get(
     *     path="/api/v1/posts",
     *     summary="Fetch all posts.",
     *     tags={"Posts"},
     *     @OA\Response(
     *         response=200,
     *         description="Fetch all posts"
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
            $posts = Post::all();
            return response()->json(['data' => $posts, 'message' => "Posts fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/posts/{id}",
     *     summary="Fetch the post with specific id.",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the post to be fetched.",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post fetched successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Post not found"
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
            $post = Post::findOrFail($id);

            return response()->json(['data' => $post, 'message' => "Post fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/posts",
     *     summary="Create a new Post",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/Post")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully."
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'featured_image' => ['required', File::image()->types(['jpg', 'jpeg', 'png'])->max(5 * 1024)],
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required'
        ]);

        $featured_image = $request->featured_image;

        $upload_path = 'public/uploads/posts';


        try {
            $featuredImage = $featured_image->store($upload_path);
            $featuredImage_file_path = str_replace($upload_path, 'uploads/posts', Storage::url($featuredImage));

            $post = Post::create([
                'featured_image' => $featuredImage_file_path,
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'user_id' => Auth::id(),
                'description' => $request->description
            ]);

            return response()->json(['data' => $post, 'message' => "Post Created!"], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * @OA\Patch(
     *     path="/api/v1/posts/{id}",
     *     summary="Update post with specified id.",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the post to be updated.",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Post"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Post")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post updated successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Post not found"
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
            $post = Post::find($id);

            if (!$post) {
                return response()->json(['error' => $this->notFoundError('Post')], 404);
            }

            $featured_image = $request->file('featured_image');
            $upload_path = 'public/uploads/posts';

            if ($featured_image) {
                $featuredImage_old_path = public_path($post->featured_image);
                if (file_exists($featuredImage_old_path)) {
                    unlink($featuredImage_old_path);
                }

                $featuredImage = $featured_image->store($upload_path);
                $featuredImage_file_path = str_replace($upload_path, 'uploads/posts', Storage::url($featuredImage));
                $post->featured_image = $featuredImage_file_path;
            }

            $title = $request->title;
            $category_id = $request->category_id;
            $description = $request->description;

            if ($title) {
                $post->title = $title;
                $post->slug = Str::slug($title, '-');
            }
            if ($category_id) {
                $post->category_id = $category_id;
            }
            if ($description) {
                $post->description = $description;
            }

            $post->update();

            return response()->json(['data' => $post, 'message' => 'Post updated!'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/posts/{id}",
     *     summary="Update post with specified id.",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the post to be updated.",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Post"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Post")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post updated successfully"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function updatePUT(Request $request, $id)
    {
        $this->validate($request, [
            'featured_image' => ['required', File::image()->types(['jpg', 'jpeg', 'png'])->max(5 * 1024)],
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required'
        ]);

        $this->update($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/posts/{id}",
     *     summary="Delete post with specified id.",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id for the post to be deleted.",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post deleted successfully."
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
            Post::destroy($id);

            return response()->json(['message' => 'Post deleted'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
