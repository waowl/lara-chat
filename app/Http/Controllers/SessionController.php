<?php

namespace App\Http\Controllers;

use App\Events\SessionEvent;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function create(Request $request)
    {
        $session =  Session::create([
            'user1_id' => auth()->user()->id,
            'user2_id' => $request->user_id,
            ]);
        $resource = new SessionResource($session);
        broadcast(new SessionEvent($resource, auth()->user()));
        return $resource;

    }
}
