<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class PostController extends Controller
{
    public function store(Request $request)
    {
    }

    public function get(string $id)
    {
        return response()->json(Post::find($id));
    }

    public function getAll(Request $request)
    {
        $category = $request->route("category");

        $category_id = Category::where('name', $category)->first();

        $posts = Post::where('category_id', $category_id)->get();

        return json_encode($posts);
    }

    public function update(Request $request, string $id)
    {
        
    }

    public function destroy(string $id)
    {
        //
    }
}
