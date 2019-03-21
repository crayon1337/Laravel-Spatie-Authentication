<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Http\Resources\Post as AppPost;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clearance'])->except('index', 'show', 'destroy', 'store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderby('id', 'desc')->paginate(5); //show only 5 items at a time in descending order

        return AppPost::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->isMethod('PATCH') ? Post::findOrFail
        ($request->post_id) : new Post;
        $post->id = $request->input('post_id');
        $post->title = $request->input('title');
        $post->body = $request->input('body');

        if($post->save())
        {
            return new AppPost($post);
        };
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id); //Find post of id = $id

        return new AppPost($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id); //Find post of id = $id
        if($post->delete())
            return new AppPost($post);
    }
}
