<?php

namespace App\Http\Controllers;

use Storage;
use Auth;
use App\Category;
use App\Post;
use Illuminate\Http\Request;


class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.posts.index', ['posts' => Post::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required | max: 255',
            'featured' => 'required | nullable',
            'category_id' => 'required',
            'content' => 'required'
        ]);

        $title = $request->title;
        $featured = $request->featured;
        $category_id = $request->category_id;
        $user_id = Auth::id();
        $content = $request->content;

        $upload_path = 'public/uploads';

        \DB::beginTransaction();

        try {
            $file = $featured->store($upload_path);
            $file_path = str_replace('public/uploads', 'uploads', Storage::url($file));
            $post = Post::create([
                'title' => $title,
                'featured' => $file_path,
                'category_id' => $category_id,
                'user_id' => $user_id,
                'content' => $content,
            ]);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
        }



        return redirect()->route('post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.edit')->with('post', $post)->with('categories', $categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'category_id' => 'required',
            'content' => 'required'
        ]);

        $post = Post::find($id);

        $title = $request->title;
        $featured = $request->featured;
        $category_id = $request->category_id;
        $user_id = Auth::id();
        $content = $request->content;

        $upload_path = 'public/uploads';

        \DB::beginTransaction();
        try {
            $file_path = $post->featured;
            if ($featured != null) {
                $file = $featured->store($upload_path);
                $file_path = str_replace('public/uploads', 'uploads', Storage::url($file));
            }

            $post->update([
                'title' => $title,
                'featured' => $file_path,
                'category_id' => $category_id,
                'user_id' => $user_id,
                'content' => $content,
            ]);

            $post->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
        }

        return redirect()->route('post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::destroy($id);
        return redirect()->back();
    }
}
