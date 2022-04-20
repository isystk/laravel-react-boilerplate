<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ErrorType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    $users = $this->userService->list();

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
    $user = $this->userService->find($id);

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
    $user = $this->userService->find($id);

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

    [$user, $type, $exception] = $this->userService->save($id);
    if (!$user) {
        if ($type === ErrorType::NOT_FOUND) {
            abort(400);
        }
        throw $exception ?? new \Exception(__('common.Unknown Error has occurred.'));
    }

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

    [$user, $type, $exception] = $this->userService->delete($id);
    if (!$user) {
        if ($type === ErrorType::NOT_FOUND) {
            abort(400);
        }
        throw $exception ?? new \Exception(__('common.Unknown Error has occurred.'));
    }

    return redirect('/admin/user');
  }
}
