<?php

namespace App\Http\Controllers;

use App\Events\MessageReadEvent;
use App\Events\PrivateChannelEvent;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

class ChatController extends Controller {

    public function send(Session $session, Request $request )
    {
            $message = $session->messages()->create(['content' => $request->get('content')]);
            $chat = $message->createForSend($session->id);
            $message->createForReceive($session->id, $request->get('to_user'));
            broadcast(new PrivateChannelEvent($message->content, $chat));
            return response($chat->id, 200);
    }

    public function getChats(Session $session)
    {
        return ChatResource::collection($session->chats->where('user_id', auth()->id()));
    }

    public function read(Session $session)
    {
        $chats =  Chat::where('session_id', $session->id)->where('read_at', null)->where('type', 0)->where('user_id', '!=', auth()->id())->get();
        if(!empty($chats)) {
            foreach ($chats as $chat)
            {
                $this->markAsRead($chat);
                broadcast(new MessageReadEvent(new ChatResource($chat), $chat->session_id));
            }
        }
        return $chats;
    }

    private function markAsRead($chat)
    {
        $chat->update(['read_at' => Carbon::now()]);
    }

    public function clear(Session $session)
    {
        $session->clearChats();
        $session->chats->count() == 0 ? $session->clearMessages() : '';
        return response('cleared', 200);
    }
}
