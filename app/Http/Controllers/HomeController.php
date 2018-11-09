<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->id());

        return view('home', compact('user'));
//        return view('home');
    }

    public function upload(Reqeust $req) {
        $this->validate($req, [
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png',
                'dimensions:min_width=10,min_height=10,max_width=400,max_height=400'

            ]
        ]);

        if ($req->file('file')->isValid([])) {
            $filename = $req->file->store('public/avatar');

            $user = User::find(auth()->id());
            $user->avatar_filename = basename($filename);
            $user->save();

            return redirect('/home')->with('success', '保存しました');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['file' => '画像アップロードされていないか不正なデータです']);
        }
    }
}
