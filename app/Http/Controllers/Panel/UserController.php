<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use http\Env\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $users=User::where('role_id','!=',5)->orderBy('created_at','desc')->get();
        return view('panel.users.index',compact('users'),['title' => 'لیست کاربران']);
    }

    public function store(Request $request)
    {
        try{
            $user=new User();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->user_name=$request->user_name;
            $user->password=bcrypt($request->password);
            $user->role_id=$request->role_id;
            $user->save();
            return Redirect()->back()->with('status', 'کاربر ثبت شد.');
        }
        catch (\Exception $exception){
            abort(500);
        }
    }

    public function edit(Request $request)
    {
        $user=User::find($request->id);
        return $user;
    }


    public function update(Request $request)
    {
//        try{
            $user=User::find($request->id);
            $user->name=$request->name;
            $user->email=$request->email;
            $user->user_name=$request->user_name;
            if(!empty($request->password)){
                $user->password=bcrypt($request->password);
            }
            $user->role_id=$request->role_id;
            $user->suspended=$request->suspended;
            $user->draft_permission=$request->draft_permission;
            $user->update();
            return Redirect()->back()->with('status', 'کاربر ویرایش شد.');
//        }
//        catch (\Exception $exception){
//            abort(500);
//        }
    }
}
