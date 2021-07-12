<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use App\Services\UserService;

class UserController extends Controller
{
  /**
   * @var UserService
   */
  protected $userService;

  public function __construct(UserService $userService)
  {
      $this->userService = $userService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $name = $request->input('name');
    $email = $request->input('email');

    $users = $this->userService->searchUser($name, $email, true);

    return view('admin.user.index', compact('users', 'name', 'email'));
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
    $user = User::find($id);

    return view('admin.user.show', compact('user'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
    $user = User::find($id);

    return view('admin.user.edit', compact('user'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {

    $this->userService->updateUser($request, $id);

    return redirect('admin/user');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {

    $this->userService->deleteUser($id);

    return redirect('/admin/user');
  }
}
