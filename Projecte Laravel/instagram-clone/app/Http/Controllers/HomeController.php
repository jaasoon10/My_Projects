<?php

namespace App\Http\Controllers;

use App\Models\Image;

class HomeController extends Controller
{
    public function index()
    {
        $images = Image::with('user', 'likes', 'comments')
                       ->orderBy('created_at', 'desc')
                       ->paginate(5);

        return view('home', compact('images'));
    }
}
