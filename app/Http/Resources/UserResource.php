<?php

namespace App\Http\Resources;

use App\Models\Session;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'online' => false,
            'session' => $this->sessionDetails($this->id)
        ];
    }

    private function sessionDetails($id)
    {
        $ids = [auth()->user()->id, $id];
        $session =  Session::whereIn('user1_id', $ids)->whereIn('user2_id', $ids)->first();
        return new SessionResource($session);
    }
}
