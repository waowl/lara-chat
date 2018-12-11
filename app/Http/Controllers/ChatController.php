<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Models\Message;
use App\Models\Session;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

class ChatController extends Controller {

    public function send(Session $session, Request $request )
    {
            $message = $session->messages()->create(['content' => $request->get('content')]);
            $message->createForSend($session->id);
            $message->createForReceive($session->id, $request->get('to_user'));
            return $message;
    }

    public function getChats(Session $session)
    {
        return ChatResource::collection($session->chats->where('user_id', auth()->id()));
    }
}
