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
            'author_id' => $this->author_id,
            'username' => $this->user->username,
            'avatar' => $this->user->avatar,
            'votes' => VoteResource::collection($this->votes),
            'content' => $this->content
        ];
    }
}