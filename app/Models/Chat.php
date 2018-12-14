<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $guarded = [];
    protected $dates= [
        'read_at'
    ];

    public function message()
    {
       return $this->belongsTo(Message::class);
    }
}
