<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Channel;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        $channel = Channel::where('name', $body['channel'])->first();

        $post = new Post();
        $post->content = json_encode($body["content"]);
        $post->author_id = $body["author"];
        $post->channel_id = $channel['id'];
        
        $post->save();

        return true;
    }

    public function get(string $id)
    {
        return response()->json(Post::find($id));
    }

    public function getAll(string $channel)
    {
        $channel = Channel::where('name', $channel)->first();

        $posts = Post::where('channel_id', $channel['id'])->get();

        return json_encode($posts);
    }

    public function update(Request $request, string $id)
    {
        $body = json_decode($request->getContent(), true);

        $post = Post::find($id);
        $post->title = $body['title'];
        $post->description = $body['description'];

        $post->save();
    }

    public function destroy(string $id)
    {
        $post = Post::find($id);
        $post->delete();
    }
}
