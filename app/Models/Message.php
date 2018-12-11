<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['content'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function createForSend($session_id)
    {
        $this->chats()->create([
            'session_id' => $session_id,
            'user_id' => auth()->user()->id,
            'type' => 0
        ]);
    }

    public function createForReceive($session_id, $to_user)
    {
        $this->chats()->create([
            'session_id' => $session_id,
            'user_id' => $to_user,
            'type' => 1
        ]);
    }

}
