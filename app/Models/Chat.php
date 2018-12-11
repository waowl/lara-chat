<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $guarded = [];
    public function message()
    {
        $this->belongsTo(Message::class);
    }
}
