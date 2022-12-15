<?php

namespace App\Http\Controllers\Panel;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Excel;
use App\Imports\UserImport;
class CompanyController extends Controller
{

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();
        $role_id = $user->role_id;
        $farshad_id = $user->id;
        $data = User::where('role_id', 5)->orderBy('id')->get();
        return view('panel.company.home', compact('data'), ['title' => 'شرکت ها']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = Auth::user();
        $role_id = $user->role_id;

        if ($role_id != 1 and $role_id != 3 and $role_id != 7 and $role_id != 6 and $role_id != 8) {
            return abort(403);
        } else {
            return view('panel.company.create', ['title' => 'ثبت شرکت جدید']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //cheraaghe / bRC80_x6;
        //dd($request->all());

        $this->validate($request, [
            'company__name' => 'required',
            'email' => 'nullable|email|max:255|unique:users',
            'name' => 'required',
            'user_name' => 'required|unique:users',
            'password' => 'required',
        ]);

        try {

            User::create([
                'company__name' => $request->company__name,
                'company__phone' => $request->company__phone,
                'company__fax' => $request->company__fax,
                'email' => $request->email,
                'company__telegram' => $request->company__telegram,
                'name' => $request->name,
                'company__manager_phone' => $request->company__manager_phone,
                'company__representative_name' => $request->company__representative_name,
                'company__representative_phone' => $request->company__representative_phone,
                'company__address' => $request->company__address,
                'company__site' => $request->company__site,
                'user_name' => $request->user_name,
                'password' => bcrypt($request->password)
            ]);

            return redirect('panel/company')->with('status', 'شرکت با موفقیت ثبت شد.');

        } catch (\Exception $e) {
            dd($e);
            abort(500);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = Auth::user();
        $role_id = $user->role_id;
        $farshad_id = $user->id;

        $data = User::where('id', $id)->first();

        return view('panel.company.show', compact('data'), ['title' => $data->company__name]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = Auth::user();
        $role_id = $user->role_id;
        $farshad_id = $user->id;

        $data = User::where('id', $id)->first();

        if ($role_id != 1 and $farshad_id != 1 and $role_id != 7 and $role_id != 6 and $role_id != 8) {
            return abort(403);
        } else {
            return view('panel.company.edit', compact('data'), ['title' => $data->company__name]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $input, $id)
    {

        $this->validate($input, [
            'company__name' => 'required',
            'name' => 'required',
            'email' => 'max:255|nullable|email|unique:users,email,'.$id,
        ]);

        $user = User::where('id', $id)->first();
        $user->company__name = $input["company__name"];
        $user->company__phone = $input["company__phone"];
        $user->company__fax = $input["company__fax"];
        $user->email = $input["email"];
        $user->company__telegram = $input["company__telegram"];
        $user->company__site = $input["company__site"];
        $user->name = $input["name"];
        $user->company__manager_phone = $input["company__manager_phone"];
        $user->company__representative_name = $input["company__representative_name"];
        $user->company__representative_phone = $input["company__representative_phone"];
        $user->company__address = $input["company__address"];

        if ($input->has('password')) {
            $user->password = bcrypt($input['password']);
        }

        $user->update();

        return Redirect::route('company.show', $id)->with('status', 'شرکت با موفقیت ویرایش شد.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function import_excel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required||mimes:xls,xlsx',
        ],
            [
                'file.required' => 'لطفا  فایل اکسل را وارد کنید',
                'file.mimes' => 'لطفا فرمت صحیح اکسل را انتخاب کنید(xls,xlsx)',
            ]);
        Excel::import(new UserImport, $request->file('file'));
        return redirect()->back()->with('flash_message', 'با موفقیت ثبت شد.');
    }
}
