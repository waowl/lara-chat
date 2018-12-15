<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\UnauthorizedException;

class Session extends Model
{
    protected $fillable = ['user1_id','user2_id'];

    public function chats()
    {
        return $this->hasManyThrough(Chat::class, Message::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function clearChats()
    {
        $this->chats()->where('user_id', auth()->id())->delete();
    }

    public function clearMessages()
    {
        $this->messages()->delete();
    }

    public function block()
    {
        $this->blocked = true;
        $this->blocked_id = auth()->id();
        $this->save();
    }

    public function unblock()
    {
        if ($this->blocked_id != auth()->id()) {
            throw new UnauthorizedException();
        }
        $this->blocked = false;
        $this->blocked_id = null;
        $this->save();
    }
}
