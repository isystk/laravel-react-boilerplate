<?php

namespace App\Http\Controllers\Front;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

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
