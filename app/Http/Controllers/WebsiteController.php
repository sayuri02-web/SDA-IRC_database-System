<?php

namespace App\Http\Controllers;

use App\Models\PastorMessage;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function home()
    {
        return view('website.home');
    }

    public function pastorsMessage()
    {
        $pastorMessage = PastorMessage::with('member')
            ->where('is_published', true)
            ->latest()
            ->first();

        return view('website.pastors-message', compact('pastorMessage'));
    }
}
