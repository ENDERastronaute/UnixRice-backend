<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Channel;
use App\Models\Vote;
use Carbon\Carbon;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $content = json_decode($request->input('content'));

        $channel = Channel::where('name', $request->input('channel'))->first();

        $post = new Post();
        $post->author_id = intval($request->input('author'));
        $post->channel_id = $channel->id;

        $content->images = [];

        foreach ($request->allFiles() as $key => $file) {
            if (strpos($key, 'image_') === 0) {
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('images'), $filename);

                $content->images[] = $filename;
            }
        }

        $post->content = json_encode($content);

        $post->save();

        return new PostResource($post);
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

    public function getTrending()
    {   
        $posts = Post::with('votes')
            ->get()
            ->filter(function ($post) {
                $threshold = 10;

                $votes = $post->votes;

                $votesCount = 0;

                foreach ($votes as $vote) {
                    $votesCount++;
                }

                $hoursSinceCreation = Carbon::now()->diffInHours($post->created_at) + 1;

                return $votesCount / $hoursSinceCreation > $threshold;
            });

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
