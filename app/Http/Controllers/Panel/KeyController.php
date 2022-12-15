<?php

namespace App\Http\Controllers\Panel;

use App\Models\User;
use App\Models\UserSeo;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KeyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($user_id=null)
    {
        if (!is_null($user_id)){
            $items = UserSeo::where('user_id',$user_id)
                ->get()
                ->groupBy(function($val) {
                    return Carbon::parse($val->created_at)->format('Y/m/d');
                });
        }else {
            $items = UserSeo::get()
                ->groupBy(function ($val) {
                    return Carbon::parse($val->created_at)->format('Y/m/d');
                });
        }
        $companies = UserSeo::select('user_id')->with('user')->distinct('user_id')->get();
        return view('panel.keySeo.index', compact('items','companies'), ['title' => 'لیست کلمات کلیدی']);
    }

    public function create()
    {
        $items = User::where('role_id', 5)->orderBy('id')->get();
        return view('panel.keySeo.create', compact('items'), ['title' => 'افزودن کلمات کلیدی']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'word' => 'required',
            'page' => 'required',
            'link' => 'required'
        ]);

        try {

            UserSeo::create([
                'user_id' => $request->user_id,
                'word' => $request->word,
                'page' => $request->page,
                'link' => $request->link
            ]);

            $idp = 'panel/keyList/';

            return Redirect::to($idp)->with('status', 'با موفقیت اضافه شد.');

        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'word' => 'required',
            'page' => 'required',
            'link' => 'required'
        ]);

        try {

            $item = UserSeo::find($id);
            $item->user_id = $request->user_id;
            $item->word = $request->word;
            $item->page = $request->page;
            $item->link = $request->link;
            $item->save();

            $idp = 'panel/keyList/';

            return Redirect::to($idp)->with('status', 'با موفقیت بروزرسانی شد.');

        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function delete($id)
    {
        $item = UserSeo::find($id);
        $item->delete();

        $idp = 'panel/keyList/';

        return Redirect::to($idp)->with('status', 'با موفقیت حذف شد.');
    }

    public function edit($id)
    {
        $data = UserSeo::find($id);
        $items = User::where('role_id', 5)->orderBy('id')->get();
        return view('panel.keySeo.edit', compact('items', 'data'), ['title' => 'بروزرسانی کلمه']);
    }

}
