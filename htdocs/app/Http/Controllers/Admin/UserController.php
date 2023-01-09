<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ErrorType;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Services\UserService;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {

        $name = $request->input('name');
        $email = $request->input('email');

        $users = $this->userService->list();

        return view('admin.user.index', compact('users', 'name', 'email'));
    }


    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        $user = $this->userService->find($id);

        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $user = $this->userService->find($id);

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, string $id): RedirectResponse
    {

        [$user, $type, $exception] = $this->userService->save($id);
        if (!$user) {
            if ($type === ErrorType::NOT_FOUND) {
                abort(400);
            }
            throw $exception ?? new Exception(__('common.Unknown Error has occurred.'));
        }

        return redirect('admin/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(string $id): RedirectResponse
    {

        [$user, $type, $exception] = $this->userService->delete($id);
        if (!$user) {
            if ($type === ErrorType::NOT_FOUND) {
                abort(400);
            }
            throw $exception ?? new Exception(__('common.Unknown Error has occurred.'));
        }

        return redirect('/admin/user');
    }
}
