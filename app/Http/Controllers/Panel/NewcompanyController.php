<?php

namespace App\Http\Controllers\Panel;

use App\Models\Company;
use App\Models\Comment;
use App\Models\Library;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class NewcompanyController extends Controller
{


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
        $user_id = $user->id;
        $items = Company::where(['user__id' => $user_id])->orderByDesc('id')->get();
        return view('panel.new_company.home', compact('items'), ['title' => 'شرکت های من']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('panel.new_company.create', ['title' => 'ثبت شرکت جدید']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //return dd($request->all());

        $user = Auth::user();
        $user_id = $user->id;

        $this->validate($request, [
            'name' => 'required|max:255|unique:companies',
            'phone' => 'required|max:255|unique:companies',
            'manager' => 'required',
            'source' => 'required',
        ]);

        try {

            Company::create([
                'user__id' => $user_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'manager' => $request->manager,
                'site' => $request->site,
                'source' => $request->source,
            ]);

            return redirect('panel/new_company')->with('status', 'شرکت با موفقیت ثبت شد.');

        } catch (\Exception $e) {
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
        $data = Company::where('id', $id)->first();
        return view('panel.new_company.show', compact('data'), ['title' => $data->name]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Company::where('id', $id)->first();
        return view('panel.new_company.edit', compact('data'), ['title' => $data->name]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $this->validate($request, [
            'name' => 'required|max:255|unique:companies',
            'manager' => 'required',
            'site' => 'required',
            'source' => 'required',
        ]);

        $company = Company::where('id', $id)->first();
        $company->name = $request->name;
        $company->site = $request->site;
        $company->manager = $request->manager;

        $company->save();

        return Redirect::route('new_company.show', $id)->with('status', 'شرکت با موفقیت ویرایش شد.');

    }

    public function company_comment_store(Request $request)
    {
        //return dd($request->all());

        $this->validate($request, [
            'percent' => 'required',
            'company_d_percent' => 'required',
            'phase_comments__details' => 'required'
        ]);

        $user = Auth::user();
        $user_id = $user->id;

        $company = Company::where('id', $request->company__id)->first();


        try {

            $company->cat = $request->company_d_percent;

            if ($request->company_d_percent == 'درخواست جلسه حضوری') {
                $company->type = '1';
            }

            $company->percent = $request->percent;
            $company->save();


            $comment = new Comment();

            $comment->user__id = $user_id;
            $comment->comment__phase_hour = $request->percent;
            $comment->company_d_percent = $request->company_d_percent;
            $comment->comment__content = $request->phase_comments__details;

            $company->comments()->save($comment);

            if ($request->hasFile('comment__attachment')) {

                try {
                    foreach ($request->comment__attachment as $image) {

                        $file = $image;
                        $originalName = $file->getClientOriginalName();
                        $destinationPath = 'uploads/libraries/new_company/';
                        $extension = $file->getClientOriginalExtension();
                        $fileName = 'new_company-' . md5(time() . '-' . $originalName) . '.' . $extension;
                        $file->move($destinationPath, $fileName);
                        $f_path = $destinationPath . "" . $fileName;
                        $library = new Library();
                        $library->file__path = $f_path;
                        $comment->libraries()->save($library);

                    }
                } catch (\Exception $e) {
                    abort(500);
                }
            }

            return Redirect()->back()->with('status', 'گزارش شما ثبت شد.');

        } catch (\Exception $e) {
            abort(500);
        }
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


    public function user_reports($id)
    {
        $data = Company::where(['user__id' => $id, 'status' => 0])->orderBy('id')->get();
        return view('panel.new_company.reports', compact('data'), ['title' => 'شرکت های مربوط']);
    }

    public function user_report($id)
    {

        $data = Company::where(['id' => $id, 'status' => 0])->orderBy('id')->first();
        return view('panel.new_company.report', compact('data'), ['title' => 'لیست گزارشات']);

    }

}
