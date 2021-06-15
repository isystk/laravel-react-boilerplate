<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReactController extends Controller
{
  public function index()
  {
    return view('front.react');
  }

  public function session(Request $request)
  {
    return $request->user();
  }
}
