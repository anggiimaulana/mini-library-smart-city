<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Resources;
use App\Models\VisitorProgress;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'title' => 'Home - Smart City Mini Library',
            'slug' => 'home'
        ]);
    }
}
