<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'channel_id' => $this->channel_id,
            'channel' => $this->channel->name,
            'author_id' => $this->author_id,
            'username' => $this->author->username,
            'avatar' => $this->author->avatar,
            'votes' => VoteResource::collection($this->votes),
            'content' => $this->content
        ];
    }
}