<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Channel;
use App\Models\Vote;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        $channel = Channel::where('name', $body['channel'])->first();

        $post = new Post();
        $post->content = json_encode($body["content"]);
        $post->author_id = $body["author"];
        $post->channel_id = $channel->id;

        $post->save();

        return true;
    }

    public function get(string $id)
    {
        return new PostResource(Post::find($id));
    }

    public function getAll(string $channel)
    {
        $channel = Channel::where('name', $channel)->first();

        $posts = Post::where('channel_id', $channel['id'])->get();

        return PostResource::collection($posts);
    }

    public function update(Request $request, string $id)
    {
        $body = json_decode($request->getContent(), true);

        $post = Post::find($id);
        $post->content = json_encode($body['content']);

        $post->save();
    }

    public function destroy(string $id)
    {
        $post = Post::find($id);
        $post->delete();
        echo true;
    }

    public function vote(string $id, Request $request)
    {
        $body = json_decode($request->getContent(), true);

        if ($vote = Vote::where('user_id', '=', $body['user_id'], 'and')->where('post_id', '=', $id)->first()) {
            $vote->value = $body['value'];
            $vote->save();

            return;
        }

        
        $vote = new Vote();
        $vote->value = $body['value'];
        $vote->user_id = $body['user_id'];
        $vote->post_id = $id;
        $vote->save();

    }
}
