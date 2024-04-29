<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ReactController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('front.react');
    }

}
