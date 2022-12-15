<?php

namespace App\Http\Controllers\Panel;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Models\Phase;
use App\Product;
use App\Models\Library;
use App\Models\Project;
use App\Models\User;
use App\Models\Package;
use App\Models\WorkTimesheet;
use Carbon\Carbon;
use Error;
use Faker\Provider\Company;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Telegram\Bot\Api;

class AgreementController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('panel.agreement.index', ['title' => 'شرکت های من']);
    }

    public function edit($id) {
        $packages = Package::all();
        $user = Auth::user();
        $role_id = $user->role_id;
        $items = Project::orderBy('id')->where('project__status', 0)->get();
        $specialUser = User::find(111);
        $seo = collect(User::where('role_id', 4)->get());
        $seo = $seo->push($specialUser);
        $technical = User::where('role_id', 2)->get();
        $users = $seo->merge($technical);
        $data = Phase::where('id', $id)->first();
        if ($role_id != 1) {
            return view('panel.phase.edit', compact('data', 'packages', 'users', 'items'), ['title' => 'ویرایش فاز']);
        } else {
            return view('panel.phase.edit', compact('data', 'packages', 'users', 'items'), ['title' => 'ویرایش فاز']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($package_id = null, $forPackage = null)
    {
        $packages = Package::all();
        $items = Project::orderBy('id')->where('project__status', 0)->get();
        $seo = User::where('role_id', 4)->get();
        $technical = User::where('role_id', 2)->get();
        $users = $seo->merge($technical);
        return view('panel.phase.create', compact('package_id', 'items', 'packages', 'users', 'forPackage'), ['title' => 'ثبت فاز جدید']);
    }

    public function phase_completed_interface()
    {
        $items = Project::orderBy('id')->where('project__status', 0)->get();
        $seo = User::where('role_id', 4)->get();
        $technical = User::where('role_id', 2)->get();
        $customers = User::where('role_id', 5)->get();
        $users = $seo->merge($technical);
        return view('panel.phase.completed_interface', compact('items', 'users', 'customers'), ['title' => 'واکشی فازهای تکمیل شده']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'package_id' => 'required',
            'phase__name' => 'required',
        ]);

        try {
            $phase = Phase::create([
                'phase__name' => $request->phase__name,
                'phase__day' => $request->phase__day,
                'phase__details' => $request->phase__details,
                'package_id' => $request->package_id,
            ]);

            if ($request->has('callback_url')) {
                return redirect($request->callback_url)->with('status', 'فاز با موفقیت اضافه شد.');
            }

            return redirect('panel/user_phase')->with('status', 'فاز با موفقیت اضافه شد.');


        } catch (Exception $e) {
            return redirect()->back()->with('danger', 'خطایی رخ داده است ، دوباره امتحان کنید!');
        }


//    if ($request->project__id) {
//      $project = Project::where('id', $request->project__id)->first();
//      $project->project__status = 0;
//      $project->save();
//    }


//        if ($request->forPackage) {
//            $phase = Phase::create([
//                'project__id' => isset($project) ? $project->id : null,
//                'user__id' => $request->user__id,
//                'phase__name' => $request->phase__name,
//                'phase__day' => $request->phase__day,
//                'phase__details' => $request->phase__details,
//                'package_id' => $request->package_id,
//            ]);
//        } else {
//            if ($request->package_id) {
//
//
//                $package = Package::find($request->package_id);
//
//                if (count($package->phases) > 0) {
//
//                    foreach ($package->phases as $phs) {
//
//                        $newPhase = $phs->replicate();
//
//
//                        // if phase is being created from package page ,
//                        // forPackage input will be enabled else package_id must be null
//                        if (!$request->forPackage) {
//                            $newPhase->package_id = 0;
//                            $lastUserId = $newPhase->user__id;
//                            $newPhase->user__id = $request->user__id ? $request->user__id : $lastUserId;
//                            $lastPhaseDay = $newPhase->phase__day;
//                            $newPhase->phase__day = $request->phase__day ? $request->phase__day : $lastPhaseDay;
//                            $newPhase->project__id = (int)$request->project__id;
//                            $newPhase->phase__percent = "0";
//                        }
//
//                        $newPhase->save();
//                    }
//
//
//                } else {
//
//                    $phase = Phase::create([
//                        'project__id' => isset($project) ? $project->id : null,
//                        'user__id' => $request->user__id,
//                        'phase__name' => $request->phase__name,
//                        'phase__day' => $request->phase__day,
//                        'phase__details' => $request->phase__details,
//                        'package_id' => $request->package_id,
//                    ]);
//
//
//                }
//            } else {
//
//                $phase = Phase::create([
//                    'project__id' => isset($project) ? $project->id : null,
//                    'user__id' => $request->user__id,
//                    'phase__name' => $request->phase__name,
//                    'phase__day' => $request->phase__day,
//                    'phase__details' => $request->phase__details,
//                    'package_id' => 0
//                ]);
//
//            }
//        }


//        } catch (\Exception $e) {
//            return redirect()->back()->with('status', 'مشکلی وجود دارد.');
//        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'phase__name' => 'required',
        ]);


        try {
            $phase = Phase::where('id', $id)->firstOrFail();

            $phase->update([
                'phase__name' => $request->phase__name,
                'phase__day' => (int)$request->phase__day,
                'phase__details' => $request->phase__details,
            ]);

            $phase->save();
//            $idp = 'panel/project/' . $request->project__id;

            if ($request->has('callback_url')) {
                return redirect($request->callback_url)->with('status', 'فاز با موفقیت اضافه شد.');
            }

            return \redirect()->back()->with('status', 'فاز با موفقیت ویرایش شد.');

        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'خطایی رخ داده است ، دوباره امتحان کنید!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Phase $phase
     * @return \Illuminate\Http\Response
     */
    public function show(Phase $phase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Phase $phase
     * @return \Illuminate\Http\Response
     */
    public function users($id)
    {
        $users = User::where('role_id', $id)->get(['id', 'name']);
        $options = array();
        foreach ($users as $user) {
            $options += array($user->id => $user->name);
        }
        return Response::json($options);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Phase $phase
     * @return \Illuminate\Http\Response
     */
    public function user_phase()
    {
        $user = Auth::user();
        $user_id = $user->id;
        if ($user->role_id == 1) {
            $phases = Phase::orderBy('created_at', 'desc')->whereHas('project', function ($q) {
                $q->whereHas('contract', function ($r) {
                    $r->where('active', "1");
                });
            })->where('phase__percent', '!=', 100)->get();
            $noProjectPhases = Phase::doesnthave('project')->orderBy('created_at', 'desc')->where('phase__percent', '!=', 100)->where('package_id', '!=', null)->where('package_id', 0)->get();
        } else {
            $phases = Phase::where('user__id', $user_id)->whereHas('project', function ($q) {
                $q->whereHas('contract', function ($r) {
                    $r->where('active', "1");
                });
            })->where('phase__percent', '!=', 100)->orderBy('created_at', 'desc')->get();
            $noProjectPhases = Phase::doesnthave('project')->where('user__id', $user_id)->where('phase__percent', '!=', 100)->orderBy('created_at', 'desc')->where('package_id', '!=', null)->where('package_id', 0)->get();
        }
        //return count($phases);
        $users = User::where('role_id', 5)->get();
        return view('panel.phase.home', compact('phases', 'users', 'noProjectPhases'), ['title' => 'فازهای من']);
    }

    public function packages()
    {
        $packages = Package::all();
        return view('panel.phase.packages', compact('packages'), ['title' => 'پکیج ها']);
    }

    public function package_destroy($id)
    {
        $package = Package::find($id)->delete();
        return back()->with('status', 'پکیج حذف شد.');
    }

    public function package_update(Request $request, $id = null)
    {
        $package = Package::find($id);
        $package->title = $request->title;
        $package->update();
        return back()->with('status', 'پکیج ویرایش شد.');
    }

    public function package_store(Request $request, $id = null)
    {
        $package = new Package();
        $package->title = $request->title;
        $package->save();
        return back()->with('status', 'پکیج ثبت شد.');
    }

    public function phase_packages($id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $phases = Phase::orderBy('created_at', 'desc')->where('package_id', $id)->get();
        $users = User::where('role_id', 5)->get();
        return view('panel.phase.home', compact('phases', 'users'), ['title' => 'فازهای من']);
    }

    public function user_completed_phase(Request $request)
    {
        $phases = new Collection();
        if ($request->customer_id) {
            $company = User::find($request->customer_id);
            $projects = $company->projects;
            foreach ($projects as $project) {
                foreach ($project->phases->where('phase__percent', 100) as $phase) {
                    $phases = $phases->push($phase);
                }
            }
            $noProjectPhases = Phase::doesnthave('project')->where('user__id', $company->id)->where('phase__percent', '!=', 100)->orderBy('created_at', 'desc')->where('package_id', '!=', null)->where('package_id', 0)->get();
        }
        if ($request->project_id) {
            $project = Project::find($request->project_id);
            foreach ($project->phases->where('phase__percent', 100) as $phase) {
                $phases = $phases->push($phase);
            }
            $noProjectPhases = Phase::doesnthave('project')->where('phase__percent', '!=', 100)->orderBy('created_at', 'desc')->where('package_id', '!=', null)->where('package_id', 0)->get();
        }
        if ($request->user_id) {
            $phases = Phase::where('user__id', $request->user_id)->where('phase__percent', 100)->orderBy('created_at', 'desc')->get();
            $noProjectPhases = Phase::doesnthave('project')->where('user__id', $request->user_id)->where('phase__percent', '!=', 100)->orderBy('created_at', 'desc')->where('package_id', '!=', null)->where('package_id', 0)->get();
        }
        $users = User::where('role_id', 5)->get();
        return view('panel.phase.home', compact('phases', 'users', 'noProjectPhases'), ['title' => 'فازهای من']);
    }

    public function user_phase_show($id)
    {
        $data = Phase::where('id', $id)->first();
        return view('panel.phase.show', compact('data'));
    }

    public function phase_comment_store(Request $request)
    {
        $this->validate($request, [
            'phase_comments__hour' => 'required',
            'phase_comments__details' => 'required'
        ]);

        $user = Auth::user();
        $user_id = $user->id;
        $role_id = $user->role_id;

        $phase = Phase::where('id', $request->phase__id)->first();

        $workTimesheet_doing = WorkTimesheet::WorkTimeSheetByStatus('phase', $phase->id, 'doing');

        if ($role_id == 4 && !$workTimesheet_doing) {
            return Redirect()->back()->with('status', 'ابتدا کار را شروع نمایید تا ساعت کار مربوطه به آن ثبت شود');
        }

        try {

            $phase->phase__percent = $request->phase__percent;
            $phase->save();


            $comment = new Comment();

            if (isset($request->comment__phase_keyword)) {
                $comment->comment__phase_keyword = $request->comment__phase_keyword;
                $comment->comment__phase_description = $request->comment__phase_description;
                $comment->comment__phase_tags = $request->comment__phase_tags;
                $comment->comment__phase_optimize = $request->comment__phase_optimize;
                $comment->comment__phase_webmaster = $request->comment__phase_webmaster;
                $comment->comment__phase_google = $request->comment__phase_google;
                $comment->comment__phase_sitemap = $request->comment__phase_sitemap;
            }

            $comment->user__id = $user_id;
            $comment->comment__phase_hour = $request->phase_comments__hour;
            $comment->comment__content = $request->phase_comments__details;

            $phase->comments()->save($comment);
            if ($request->hasFile('comment__attachment')) {

//                try {
                foreach ($request->comment__attachment as $image) {

                    $file = $image;
                    $originalName = $file->getClientOriginalName();
                    $destinationPath = 'uploads/libraries/phases/';
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'phase-' . md5(time() . '-' . $originalName) . '.' . $extension;
                    $file->move($destinationPath, $fileName);
                    $f_path = $destinationPath . "" . $fileName;
                    $library = new Library();
                    $library->file__path = $f_path;
                    $comment->libraries()->save($library);

                }
//                } catch (\Exception $e) {
//                    abort(500);
//                }
            }

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: <CRM@adibit.ir>' . "\r\n";

            $user = User::find($phase->user__id);

            $message = "کارفرمای گرامی با سلام. شرح گزارش عملکرد کارشناسان شرکت ادیب بر روی پروژه شما از طریق لینک زیر ثبت شده است، لطفا ملاحظه بفرمایید" . "\r\n";
            $message .= "<a href = 'http://support.adib-it.com/dashbord/report/" . $phase->id . "'> مشاهده گزارش </a>";
            mail($user->email, $phase->phase__name, html_entity_decode($message), $headers);
            mail('mahami@adibit.ir', $phase->phase__name, html_entity_decode($message), $headers);

            //END WORKSHEET IF EXIST
            $today = Carbon::now();
            $time = $today->format('H:i:s');
            $date = $today->format('Y-m-d');

            $workTimesheet = WorkTimesheet::WorkTimeSheetByStatus('phase', $phase->id, 'doing');

            if ($workTimesheet) {
                $workTimesheet->status = 'finished';
                $workTimesheet->endTime = $time;
                $workTimesheet->endDate = $date;
                $workTimesheet->update();
            }

            return Redirect()->back()->with('status', 'گزارش شما ثبت شد.');

        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function phase_report($id)
    {
        $data = Phase::where('id', $id)->first();

        return view('panel.phase.index', compact('data'), ['title' => 'لیست گزارشات']);

    }

    public function reports()
    {

        $user = Auth::user();
        $role_id = $user->role_id;
        $farshad_id = $user->id;

        $site_users = Project::orderBy('id')->get();
        $sale_users = User::where('role_id', 6)->get();
        return view('panel.phase.reports', compact('site_users', 'sale_users'), ['title' => 'لیست شرکت ها']);

    }

    public function companies()
    {

        $companies = User::where('role_id', '=', 5)->where('suspended', 0)->orderByDesc('created_at')->get();
        return view('panel.phase.companies', compact('companies'), ['title' => 'لیست شرکت ها']);

    }

    public function user_reports($id)
    {
        $data = Phase::where('user__id', $id)->get();
        return view('panel.phase.user_reports', compact('data'), ['title' => 'لیست فازهای کاربر']);

    }

    public function destroy($id)
    {
        $phase = Phase::find($id);
        $phase->delete();
        return redirect()->to('panel/packages')->with('status', 'فاز با موفقیت حذف شد.');

    }


}
