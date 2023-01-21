<?php
 
namespace App\Http\Controllers\Panel\TodoList;

use App\Models\TodoListChek;
use App\Models\TodoListCat;
use App\Models\TodoListTab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TodoListChekController extends Controller
{
    public function controller_title($type)
    {
        switch ($type)
        {
            case 'index':
                return 'چک لیست فعالیت';
                break;
            case 'create':
                return 'افزودن  چک لیست فعالیت';
                break;
            case 'edit':
                return 'ویرایش چک لیست فعالیت';
                break;
            case 'url_back':
                return route('todo-list-check.index');
                break;
            default:
                return '';
                break;
        }
    }
    public function __construct()
    {
        $this->middleware(['auth','todo']);
    }

    public function index()
    {
        $items=TodoListChek::orderByDesc('id')->get();
        return view('panel.todo_list.chek_list.index', compact('items'), ['title' => $this->controller_title('index')]);
    }
    public function show($id)
    {

    }
    public function create()
    {
        $url_back=$this->controller_title('url_back');
        $cats=TodoListCat::orderBy('id')->get();
        $tabs=TodoListTab::orderBy('id')->get();
        return view('panel.todo_list.chek_list.create',compact('url_back','cats','tabs'), ['title' => $this->controller_title('create')]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'cat_id' => 'required',
            'tab_id' => 'required',
            'title' => 'required',
            'status' => 'required',
        ]);
        try {
            $item = TodoListChek::create([
                'tab_id' => $request->tab_id,
                'cat_id' => $request->cat_id,
                'title' => $request->title,
                'status' => $request->status,
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function edit($id)
    {
        $url_back=$this->controller_title('url_back');
        $item=TodoListChek::findOrFail($id);
        $cats=TodoListCat::orderBy('id')->get();
        $tabs=TodoListTab::orderBy('id')->get();
        return view('panel.todo_list.chek_list.edit',compact('url_back','item','cats','tabs'), ['title' => $this->controller_title('edit')]);
    }
    public function update(Request $request,$id)
    {
        $item=TodoListChek::findOrFail($id);
        $this->validate($request, [
            'cat_id' => 'required',
            'tab_id' => 'required',
            'title' => 'required',
            'status' => 'required',
        ]);
        try {
            $item->update([
                'tab_id' => $request->tab_id,
                'cat_id' => $request->cat_id,
                'title' => $request->title,
                'status' => $request->status,
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function destroy($id)
    {
        $item=TodoListChek::findOrFail($id);
        try {
            if (count($item->todo_list_reports)){
                return redirect()->back()->withInput()->with('err_message', 'برای حذف چک لیست، ابتدا باید فعالیت متصل به این چک لیست حذف شوند');
            }
            $item->delete();
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
}
