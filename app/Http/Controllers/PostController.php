<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware("auth:sanctum", except: ["index", "show"])
        ];
    }


    public function index(): Response
    {
        $posts = Post::latest()->with(["user"])->get();

        return response(["posts" => $posts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedFields = $request->validate([
            "title" => "required|min:5",
            "body" => "required|min:5",
        ]);

        $post = $request->user()->posts()->create($validatedFields);

        return ["post" => $post];
    }   

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response(["post" => $post, "user" => $post->user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize("modify", $post);

        $validatedFields = $request->validate([
            "title" => "required|min:5",
            "body" => "required|min:5",
        ]);

        $post->save($validatedFields);

        return response(["post" => $post]);
    }

    public function myposts(Request $request)
    {
        $posts = $request->user()->posts;
        return response($posts);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize("modify", $post);

        $post->delete();

        return response(["message" => "post deleted successfuly"]);
    }
}
