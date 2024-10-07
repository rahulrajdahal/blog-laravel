<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        try {
            $posts = Post::all();
            return response()->json(['data' => $posts, 'message' => "Posts fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);

            return response()->json(['data' => $post, 'message' => "Post fetched!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

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
