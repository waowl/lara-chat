<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class BlockController extends Controller
{

    public function block(Session $session)
    {
        $session->blocK();
        return response(null, 201);
    }

    public function unblock(Session $session)
    {
        try {
            $session->unblock();
        } catch (UnauthorizedException $e){
            return response('Sorry, you can\'t unblock this session', 401);
        }
        return response(null, 201);

    }

}
