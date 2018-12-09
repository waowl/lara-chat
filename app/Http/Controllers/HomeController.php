<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return UserResource
     */
    public function index()
    {
        return view('home');
    }

    public function getFriends()
    {
        return UserResource::collection(User::where('id', '!=', auth()->user()->id)->get());
    }


}
