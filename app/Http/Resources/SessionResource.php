<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'open' => false,
            'user1_id' => $this->user1_id,
            'user2_id' => $this->user2_id,
            'blocked' => $this->blocked,
            'blocked_id' => $this->blocked_id,
            'unread_count' => $this->chats->where('read_at',null)->where('type', 0)->where('user_id', '!=', auth()->user()->id)->count()
        ];
    }
}
