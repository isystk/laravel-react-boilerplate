<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Services\CSV;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');

        // 検索フォーム
        $query = DB::table('users');

        // もしキーワードがあったら
        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($email !== null) {
            $query->where('email', '=', $email);
        }

        $query->select('id', 'name', 'email', 'created_at');
        $query->orderBy('id');
        $query->orderBy('created_at', 'desc');
        $users = $query->paginate(20);

        // dd($users);

        return view('admin.user.index', compact('users', 'name', 'email'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');

        // 検索フォーム
        $query = DB::table('users');

        // もしキーワードがあったら
        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($email !== null) {
            $query->where('email', '=', $email);
        }

        $query->select('id', 'name', 'email');
        $query->orderBy('id');
        $users = $query->get();

        // dd($users);

        $csvHeader = ['ID', '名前', 'メールアドレス'];
        $csvBody = [];
        foreach ($users as $user) {
            $line = [];
            $line[] = $user->id;
            $line[] = $user->name;
            $line[] = $user->email;
            $csvBody[] = $line;
        }
        return CSV::download($csvBody, $csvHeader, 'users.csv');
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
        // 入力チェック
        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        //
        $user = User::find($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $user->save();

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
        // ユーザーテーブルを削除
        $user = User::find($id);
        $user->delete();

        return redirect('/admin/user');
    }
}
