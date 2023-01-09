<?php

namespace App\Http\Controllers\Front;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
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

    /**
     * @param Request $request
     * @return mixed
     */
    public function session(Request $request): mixed
    {
        return $request->user();
    }
}
