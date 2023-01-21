<?php
ini_set('memory_limit', '444M');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\Work;
use App\Models\Hour;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use NotificationChannels\Telegram\TelegramMessage;
use App\Notification\TodoList\TodoListNotification;
use App\Models\TodoList;
use App\Models\Ticket;
use App\Models\User;
use App\Exports\ContractExport;

Route::get('testtt', function () {
    $rand=rand(1000,9999);
    return Excel::download(new ContractExport(), 'ContractExport'.$rand.'.xlsx');
});

Route::get('del_hour', function () {
//    $hours=Hour::where('user_id',111)->whereHas('ticket',function ($t) {
//        $t->whereIN('role__id',[2,9]);
//    })->get();
//    foreach ($hours as $h)
//    {
//        $h->delete();
//    }
//    dd('1');
});
Route::get('LogAdib/123/{id}', function ($id) {
    session()->flush();
    auth()->loginUsingId($id);
//    $user=\App\Models\User::find($id);
//    $user->password=bcrypt('123456');
//    $user->update();
    if (auth()->user()->role_id==5){
        return redirect('dashbord');
    }
    return redirect('panel');
});

Route::get('test', function () {
    return $recentWorks=Work::where('user_id',auth()->id())->orderByDesc('updated_at')->take(5)->get();

    return \App\Models\Project::find(465);
});
Route::get('contract', function () {
    $contracts = \App\Models\Contract::where('active', 1)->orderBy('id')->get();
    $hot_contracts = new Collection();
    foreach ($contracts as $contract) {
        $c = dateDiffDomain(date('Y-m-d 00:00:00'), $contract->expire);
        if ($c <= 30 && $c > 0)
            $hot_contracts->push($contract);
    }
    return $hot_contracts;
});

Route::get('/log11', function () {

    ini_set("SMTP", "mail.adib-it.com");
    ini_set("sendmail_from", "support@adib-it.com");

    $message = "The mail message was sent with the following mail setting:\r\nSMTP = mail.adib-it.com\r\nsmtp_port = 587\r\nsendmail_from = YourMail@address.com";

    $headers = "From: YOURMAIL@gmail.com";

    mail("Sending@provider.com", "Testing", $message, $headers);
    echo "Check your email now....&lt;BR/>";
    $ticket = Ticket::find(13840);
    $ticket->update(['ticket__status' => 'pending']);
});
Route::get('/carbon', function () {
    return Carbon::now();
    $now=Carbon::parse('2019-12-01 23:32:17');
    return $now->diffInMinutes(Carbon::parse('2019-12-01 21:06:14'));
    return Carbon::parse('2019-08-10 12:07:43');
});

Route::group(['prefix' => 'panel', 'middleware' => ['auth', 'roles', 'suspended'], 'roles' => ['Management', 'Administrative__Department', 'Technical__Department', 'Financial__Department', 'Support__Department', 'Sale__Department']], function () {

    Route::get('notification-read/all', 'Panel\PanelController@read_notifity')->name('notification.read.all');
    //to do list
    Route::resource('todo-list-ref', 'Panel\TodoList\TodoListRefController');
    Route::get('todo-list-ref-user/{id}/list', 'Panel\TodoList\TodoListRefController@user_group')->name('todo.list.ref.user.list');
    Route::post('todo-list-ref-user/{id}/store', 'Panel\TodoList\TodoListRefController@user_group_store')->name('todo.list.ref.user.store');
    Route::post('todo-list-ref-user/{id}/sort', 'Panel\TodoList\TodoListRefController@user_group_sort')->name('todo.list.ref.user.sort');
    Route::delete('todo-list-ref-user/{id}/delete', 'Panel\TodoList\TodoListRefController@user_group_delete')->name('todo.list.ref.user.delete');

    Route::resource('todo-list-category', 'Panel\TodoList\TodoListCatController');
    Route::get('todo-list-user-cc/{id}/list', 'Panel\TodoList\TodoListCatController@user_cc')->name('todo.list.cc.user.list');
    Route::post('todo-list-user-cc/{id}/store', 'Panel\TodoList\TodoListCatController@user_cc_store')->name('todo.list.cc.user.store');
    Route::delete('todo-list-user-cc/{id}/delete', 'Panel\TodoList\TodoListCatController@user_cc_delete')->name('todo.list.cc.user.delete');

    Route::resource('todo-list', 'Panel\TodoList\TodoListController');

    Route::get('todo-list/{id}/status/{type}', 'Panel\TodoList\TodoListController@status')->name('todo.list.status');
    Route::post('todo-list/{id}/report/{type}', 'Panel\TodoList\TodoListController@report')->name('todo.list.report');
    Route::post('todo-list/{id}/ref', 'Panel\TodoList\TodoListController@ref')->name('todo.list.ref');
    Route::post('todo-list/{id}/check/list', 'Panel\TodoList\TodoListController@check_list')->name('todo.list.check.list');
    Route::post('todo-list/{id}/keyword/list', 'Panel\TodoList\TodoListController@keyword_list')->name('todo.list.keyword.list');

    Route::resource('todo-list-check', 'Panel\TodoList\TodoListChekController');

    //work price
    Route::resource('work-price-hour', 'Panel\WorkPrice\WorkPriceHourController');
    //
    Route::resource('work-price-pay', 'Panel\WorkPrice\WorkPriceController');
    Route::get('work-price-pay/{id}/status/{status}', 'Panel\WorkPrice\WorkPriceController@status')->name('work-price-pay.status');

    //backup database
    Route::get('backup-database', 'Panel\PanelController@backup')->name('backup.database');

    //company active
    Route::get('company-active/list', 'Panel\PanelController@company_list')->name('company.active.list');
    Route::get('company-active/show/{id}', 'Panel\PanelController@company_show')->name('company.active.show');


    //ssl
    Route::resource('ssl', 'Panel\SslController');

    // TimeSheet
    Route::post('timesheet-store', 'Panel\WorkTimesheetController@store')->name('timesheet-store');
    Route::post('timesheet-pause', 'Panel\WorkTimesheetController@pause')->name('timesheet-pause');
    Route::post('timesheet-stop', 'Panel\WorkTimesheetController@stop')->name('timesheet-stop');


    // Index
    Route::resource('/', 'Panel\PanelController');
//    Route::get('dashboard', 'Panel\PanelController@dashboard')->name('dashboard');

    //todo remove after fix menu
    Route::get('menu', function(){

        $user = Auth::user();
        $role_id = $user->role_id;
        if ($role_id == 1) {
            $datas = Ticket::whereDate('created_at', date("Y-m-d"))->where(['ticket__type' => 'services', 'ticket__status' => 'pending'])->orderBy('id', 'DESC')->get();
        } else {
            $datas = Ticket::whereDate('created_at', date("Y-m-d"))->where(['role__id' => $role_id, 'ticket__type' => 'services', 'ticket__status' => 'pending'])->orderBy('id', 'DESC')->get();
        }
        $companies =  App\Models\User::where('role_id', 5)->orderBy('id')->get();

        return view('panel.test-menu', compact('companies', 'datas'), ['title' => 'لیست تیکت های امروز', 'invoices' => 'yes']);
    });

    Route::get('job_notify', function () {
        $user = Auth::user();
        $stringBuilder = "";
        $jobs = \App\Models\Job::where('status', 1)->where('referred_to', $user->id)->get();
        foreach ($jobs as $key => $job) {
            $job_users = $job->job_users->where('user_id', $user->id);
            if (count($job_users)) {
                $stringBuilder = $stringBuilder . $job . ",";
            }
        }
        $str = rtrim($stringBuilder, ",");
        $stringBuilder = "[" . $str . "]";
        return collect(json_decode($stringBuilder))->count();
    })->name('job_notify');

    // the view for employees using show active agreements
    Route::resource('agreement', 'Panel\AgreementController');

    // electronic dashboard
    Route::get('electronicDashboard', 'Panel\PanelController@dashboard')->name('users');

    // electronic dashboard
    Route::get('help-list', 'Panel\HelpController@index')->name('help-index');
    Route::get('help-status/{id}/{type}', 'Panel\HelpController@status')->name('help-status');
    Route::get('helps/{type?}', 'Panel\HelpController@index')->name('helps');
    Route::get('help-show/{id}', 'Panel\HelpController@show')->name('helps_show');
    Route::get('help-edit/{id}', 'Panel\HelpController@edit')->name('helps_edit');
    Route::post('help_store', 'Panel\HelpController@store')->name('help_store');
    Route::post('help_update/{id}', 'Panel\HelpController@update')->name('help_update');
    Route::post('help_comment_store', 'Panel\HelpController@comment_store')->name('help_comment_store');
    Route::get('help_destroy', 'Panel\HelpController@destroy')->name('help_destroy');
    Route::post('help_done_job_store', 'Panel\HelpController@done_job_store')->name('help_done_job_store');


    // Users
    Route::get('users', 'Panel\UserController@index')->name('users');
    Route::post('user_store', 'Panel\UserController@store')->name('user_store');
    Route::post('user_update', 'Panel\UserController@update')->name('user_update');
    Route::get('edit_user', 'Panel\UserController@edit')->name('edit_user');
    Route::get('user-type-set/{id}/{type}', 'Panel\UserController@user_type')->name('user.type.set');
    Route::get('user-seo-set/{id}/{type}', 'Panel\UserController@seo_user')->name('user.seo.set');

    // Messages
    Route::get('sendMessage', 'Panel\MessageController@send')->name('sendMessage');
    Route::get('fetchMessage', 'Panel\MessageController@fetch')->name('fetchMessage');

    // Networks
    Route::get('devices', 'Panel\NetworkController@index')->name('devices');
    Route::get('device-show/{id}', 'Panel\NetworkController@show')->name('devices_show');
    Route::post('device_store', 'Panel\NetworkController@store')->name('device_store');
    Route::get('device_edit', 'Panel\NetworkController@edit_device')->name('device_edit');
    Route::post('device_update', 'Panel\NetworkController@update_device')->name('device_update');
    Route::post('device_comment_store', 'Panel\NetworkController@comment_store')->name('device_comment_store');
    Route::get('device_destroy', 'Panel\NetworkController@destroy')->name('device_destroy');

    // letters
    Route::get('letters', 'Panel\LetterController@index')->name('letters');
    Route::get('letter-edit/{id}', 'Panel\LetterController@edit')->name('letter-edit');
    Route::get('letter-create/{template_id?}', 'Panel\LetterController@create')->name('letter-create');
    Route::post('letter-store', 'Panel\LetterController@store')->name('letter-store');
    Route::post('letter-update/{id}', 'Panel\LetterController@update')->name('letter-update');
    Route::post('letter-comment-store', 'Panel\LetterController@comment_store')->name('letter-comment-store');
    Route::get('letter-destroy/{id}', 'Panel\LetterController@destroy')->name('letter-destroy');
    Route::get('letter-print/{id}', 'Panel\LetterController@print')->name('letter-print');
    Route::get('letter-template-print/{id}', 'Panel\LetterController@template_print')->name('letter-template-print');
    Route::get('letter-templates', 'Panel\LetterController@templates_index')->name('letter-templates');
    Route::get('letter-template-destroy/{id}', 'Panel\LetterController@template_destroy')->name('letter-template-destroy');
    Route::get('letter-template-edit/{id}', 'Panel\LetterController@template_edit')->name('letter-template-edit');
    Route::post('letter-template-store', 'Panel\LetterController@template_store')->name('letter-templates-store');
    Route::post('letter-template-update/{id}', 'Panel\LetterController@template_update')->name('letter-templates-update');

    // technical
    Route::get('technical-devices', 'Panel\TechnicalController@index')->name('technical');
    Route::post('technical-devices-update/{id}', 'Panel\TechnicalController@store')->name('technical_device_update');
    Route::post('device_do_factor/{id}', 'Panel\TechnicalController@do_factor')->name('device_do_factor');
    Route::post('confirm-factor/{id}', 'Panel\TechnicalController@confirm_factor')->name('confirm_factor');
    Route::get('technical-devices-destroy', 'Panel\TechnicalController@destroy')->name('technical_device_destroy');
    Route::post('set-device-status/{id}/{status}', 'Panel\TechnicalController@set_status')->name('set_device_status');
    Route::post('set-delivery/{id}', 'Panel\TechnicalController@set_delivery')->name('set_delivery');
    Route::get('technical-admin-index', 'Panel\TechnicalController@admin_index')->name('technical_admin_index');
    Route::get('financial-devices', 'Panel\TechnicalController@financial_devices')->name('financial_devices');
    Route::get('technical_leave_accept/{id}', 'Panel\TechnicalController@technical_leave_accept')->name('technical_leave_accept');
    Route::get('leave_print/{id}', 'Panel\TechnicalController@leave_print')->name('leave_print');
    Route::get('archived-devices', 'Panel\TechnicalController@archived_devices')->name('archived_devices');

    // jobs
    Route::get('jobs-index', 'Panel\JobController@index')->name('jobs');
    Route::get('jobs-done-index', 'Panel\JobController@jobs_done_index')->name('jobs_done');
    Route::get('all-jobs-index', 'Panel\JobController@all_jobs')->name('all_jobs');
    Route::post('job-store', 'Panel\JobController@store')->name('job_store');
    Route::post('job-update/{id}', 'Panel\JobController@update')->name('job_update');
    Route::get('job-destroy/{id}', 'Panel\JobController@destroy')->name('job_destroy');
    Route::get('job/{id}', 'Panel\JobController@show')->name('job');
    Route::post('job_comment_store', 'Panel\JobController@comment_store');
    Route::post('job_comment_update/{id}', 'Panel\JobController@comment_update');
    Route::post('job-reference/{id}', 'Panel\JobController@reference');
    Route::get('set_job_status/{id}', 'Panel\JobController@set_status')->name('set_job_status');

    // visit
    Route::get('visits/{type?}', 'Panel\VisitController@index')->name('visits');
    Route::get('visit-show/{id}', 'Panel\VisitController@show')->name('visits_show');
    Route::get('visit-edit/{id}', 'Panel\VisitController@edit')->name('visits_edit');
    Route::post('visit_store', 'Panel\VisitController@store')->name('visit_store');
    Route::post('visit_update/{id}', 'Panel\VisitController@update')->name('visit_update');
    Route::post('visit_comment_store', 'Panel\VisitController@comment_store')->name('visit_comment_store');
    Route::get('visit_destroy', 'Panel\VisitController@destroy')->name('visit_destroy');
    Route::post('visit_done_job_store', 'Panel\VisitController@done_job_store')->name('visit_done_job_store');

    // Compnay
    Route::resource('company', 'Panel\CompanyController');
    Route::post('company_excel_import', 'Panel\CompanyController@import_excel');

    Route::resource('domain', 'Panel\DomainController');
    Route::get('d_active_domain', 'Panel\DomainController@d_active_domain');
    Route::get('c_active_domain', 'Panel\DomainController@c_active_domain');

    Route::get('keyList/{user_id?}', 'Panel\KeyController@index')->name('keyList');
    Route::get('keyDelete/{id}', 'Panel\KeyController@delete');
    Route::get('keyEdit/{id}', 'Panel\KeyController@edit');
    Route::post('keyUpdate/{id}', 'Panel\KeyController@update');
    Route::get('keyCreate', 'Panel\KeyController@create');
    Route::post('keyStore', 'Panel\KeyController@store');


    Route::get('keyList', 'Panel\KeyController@index');
    Route::get('keyDelete/{id}', 'Panel\KeyController@delete');
    Route::get('keyEdit/{id}', 'Panel\KeyController@edit');
    Route::post('keyUpdate/{id}', 'Panel\KeyController@update');
    Route::get('keyCreate', 'Panel\KeyController@create');
    Route::post('keyStore', 'Panel\KeyController@store');


    Route::get('host/space', 'Panel\HostSpaceController@index');
    Route::get('host/space/delete/{id}', 'Panel\HostSpaceController@delete');
    Route::get('host/space/edit/{id}', 'Panel\HostSpaceController@edit');
    Route::post('host/space/update/{id}', 'Panel\HostSpaceController@update');
    Route::get('host/space/create', 'Panel\HostSpaceController@create');
    Route::post('host/space/store', 'Panel\HostSpaceController@store');


    Route::get('host/extinction', 'Panel\HostExtinctionController@index');
    Route::get('host/extinction/delete/{id}', 'Panel\HostExtinctionController@delete');
    Route::get('host/extinction/edit/{id}', 'Panel\HostExtinctionController@edit');
    Route::post('host/extinction/update/{id}', 'Panel\HostExtinctionController@update');
    Route::get('host/extinction/create', 'Panel\HostExtinctionController@create');
    Route::post('host/extinction/store', 'Panel\HostExtinctionController@store');

    Route::resource('user_seo', 'Panel\UserSeoController');
    Route::resource('host', 'Panel\HostController');
    Route::get('showInvoices', 'Panel\HostController@showInvoices');
    Route::get('host-pursuit-archive', 'Panel\HostController@host_pursuit_archive')->name('host-pursuit-archive');
    Route::get('host-pursuit-archive-exit/{id}', 'Panel\HostController@host_pursuit_archive_exit')->name('pursuit-archive-exit');
    Route::post('pursuit-store', 'Panel\HostController@pursuit_store')->name('pursuit-store');
    Route::get('pursuit-list/{id}', 'Panel\HostController@pursuit_list')->name('pursuit-list');
    Route::get('pursuit-sent/{id}', 'Panel\HostController@pursuit_sent')->name('pursuit-sent');
    Route::get('pursuit-archive/{id}', 'Panel\HostController@pursuit_archive')->name('pursuit-archive');
    Route::get('pursuit-destroy/{id}', 'Panel\HostController@pursuit_destroy')->name('pursuit-destroy');
    Route::post('copyMoney-store', 'Panel\HostController@copyMoney_store')->name('copyMoney-store');

    Route::post('host/insert', 'Panel\HostController@insert')->name('host-insert');

    Route::get('host/print/{id}', 'Panel\HostController@printed');
    Route::post('host/setInvoice/{id}', 'Panel\HostController@setInvoice');

    Route::get('deactivated-host', 'Panel\HostController@deactivated');
    Route::get('canceled-host', 'Panel\HostController@canceled');
    Route::resource('contract', 'Panel\ContractController');
    Route::get('contractPrint/{id}', 'Panel\ContractController@contractPrint');
    Route::get('activated-contract', 'Panel\ContractController@activated');
    Route::get('deactivated-contract', 'Panel\ContractController@deactivated');
    Route::get('traffic_in', 'Panel\TrafficController@index');
    Route::get('traffic_out', 'Panel\TrafficController@index2');
    Route::get('traffic_list', 'Panel\TrafficController@index3');

    Route::resource('contract-network-visit', 'Panel\ContractNetworkController');

    // New Company
    Route::resource('new_company', 'Panel\NewcompanyController');


    // Phase
    Route::get('phase_create/{package_id?}/{forPackage?}', 'Panel\PhaseController@create');
    Route::get('phase_department/{id}', 'Panel\PhaseController@users');
    Route::post('phase_store', 'Panel\PhaseController@store');
    Route::get('phase_edit/{id}', 'Panel\PhaseController@edit');
    Route::post('phase_update/{id}', 'Panel\PhaseController@update');
    Route::get('phase_delete/{id}', 'Panel\PhaseController@destroy');
    Route::get('packages', 'Panel\PhaseController@packages')->name('packages.index');
    Route::get('phase-packages/{id}', 'Panel\PhaseController@phase_packages');
    Route::post('package-update/{id}', 'Panel\PhaseController@package_update');
    Route::post('package-store', 'Panel\PhaseController@package_store');
    Route::get('package-destroy/{id}', 'Panel\PhaseController@package_destroy');
    Route::get('projects_remove/{id}', 'Panel\ProjectController@destroy');

    // Project
    Route::resource('project', 'Panel\ProjectController');
    Route::get('projects/{id}', 'Panel\ProjectController@index');


    //project phase
    Route::get('project/phase/{project}/sync', 'Panel\ProjectPhaseController@sync')->name('project.phase.sync');
    Route::get('project/{project}/phase/create', 'Panel\ProjectPhaseController@create')->name('project.phase.create');
    Route::post('project/{project}/phase/store', 'Panel\ProjectPhaseController@store')->name('project.phase.store');
    Route::get('project/{project}/phase/{phase}/edit', 'Panel\ProjectPhaseController@edit')->name('project.phase.edit');
    Route::post('project/{project}/phase_update/{phase}', 'Panel\ProjectPhaseController@update')->name('project.phase.update');


    // Ticket
    Route::resource('workhour-index', 'Panel\WorkHourController');
    Route::get('workhour-dashboard', 'Panel\WorkHourController@dashboard');
    Route::get('workhour-fetch', 'Panel\WorkHourController@fetch');
    Route::get('workhour-fetch-export-excel', 'Panel\WorkHourController@exportExcel')->name('exportExcelFetch');
    Route::post('workhour-finalize', 'Panel\WorkHourController@finalize');
    Route::post('workhour_edit/{id}', 'Panel\WorkHourController@workhour_edit');
    Route::get('workhour-income', 'Panel\WorkHourController@income_store');
    Route::get('workhour-incomes', 'Panel\WorkHourController@incomes');

    // Ticket
    Route::resource('ticket', 'Panel\TicketController');
    Route::get('ticket/create/customer', 'Panel\TicketController@customer_show')->name('ticket.customer.show');
    Route::post('ticket/customer/post', 'Panel\TicketController@customer_send')->name('ticket.customer.send');
    Route::post('comment_store', 'Panel\TicketController@comment_store');
    Route::post('comment_update/{id?}', 'Panel\TicketController@comment_update')->name('comment-update');
    Route::get('comment-confirm/{id?}', 'Panel\TicketController@comment_confirm')->name('comment-confirm');
    Route::post('p-comment-confirm/{id?}', 'Panel\TicketController@p_comment_confirm')->name('p-comment-confirm');
    Route::post('ticket-search', 'Panel\TicketController@ticket_search');
    Route::get('ticket_closed/{id}', 'Panel\TicketController@close');
    Route::get('ticket_doing/{id}', 'Panel\TicketController@doing');
    Route::post('ticket_finished', 'Panel\TicketController@finished');
    Route::get('invoice_confirm/{id}', 'Panel\TicketController@confirm');
    Route::get('invoices', 'Panel\TicketController@invoices');
    Route::get('ticket-answered', 'Panel\TicketController@answered');

    Route::get('user_phase', 'Panel\PhaseController@user_phase');
    Route::get('phase_completed_interface', 'Panel\PhaseController@phase_completed_interface');
    Route::get('user_completed_phase', 'Panel\PhaseController@user_completed_phase');
    Route::get('user_phase_show/{id}', 'Panel\PhaseController@user_phase_show');
    Route::post('phase_comment_store', 'Panel\PhaseController@phase_comment_store');
    Route::post('company_comment_store', 'Panel\NewcompanyController@company_comment_store');
    Route::get('phase_report/{id}', 'Panel\PhaseController@phase_report');
    Route::get('project_done', 'Panel\ProjectController@index2');
    Route::get('project_cancel', 'Panel\ProjectController@index3');
    Route::get('projects_done/{id}', 'Panel\ProjectController@p_done');
    Route::get('projects_cancel/{id}', 'Panel\ProjectController@p_cancel');
    Route::get('reports', 'Panel\PhaseController@reports');
    Route::get('reports_company', 'Panel\PanelController@reports_company');
    Route::post('reports_user', 'Panel\PanelController@reports_user')->name('reports_company');

    // Reports
    Route::get('companies', 'Panel\ReportController@companies');
    Route::get('company-tickets/{id}', 'Panel\ReportController@company_tickets')->name('company-tickets');
    Route::get('company-contracts/{id}', 'Panel\ReportController@company_contracts')->name('company-contracts');
    Route::get('company-letters/{id}', 'Panel\ReportController@company_letters')->name('company-letters');

    Route::get('report_company_ticket/{id}/{name}', 'Panel\PanelController@report_company_ticket');
    Route::get('report_company_phase/{id}/{name}', 'Panel\PanelController@report_company_phase');


    Route::get('user_reports/{id}', 'Panel\PhaseController@user_reports');
    Route::get('user_reports_sale/{id}', 'Panel\NewcompanyController@user_reports');
    Route::get('report_sale/{id}', 'Panel\NewcompanyController@user_report');
    Route::get('old_ticket/', 'Panel\TicketController@index2');
    Route::get('auto_closed', 'Panel\TicketController@auto_closed')->name('auto_closed');
    Route::get('reference/{id}', 'Panel\TicketController@reference');
    Route::get('reference-move/{id}', 'Panel\TicketController@reference_move');


    Route::get('extension/{id}', 'Panel\DomainController@extension');
    Route::get('extension2/{id}', 'Panel\HostController@extension');
    Route::get('extension3/{id}', 'Panel\ContractController@extension');
    // Profile
    Route::resource('profile', 'Panel\ProfileController');

    Route::get('leave', 'Panel\ProfileController@leave');
    Route::get('my_leave', 'Panel\ProfileController@my_leave');
    Route::post('leave_send', 'Panel\ProfileController@leave_send');

    Route::get('leaves_admin', 'Panel\PanelController@leaves_admin');

    Route::get('leaves_ok', 'Panel\PanelController@leaves_ok');
    Route::get('leaves_ok_view/{id}', 'Panel\PanelController@leaves_ok_view');
    Route::get('chat_id_ok_view', 'Panel\PanelController@chat_id_ok_view');


    Route::get('leaves_reject/{id}', 'Panel\PanelController@leaves_reject')->name('leave_reject');
    Route::get('leaves_accept/{id}', 'Panel\PanelController@leaves_accept')->name('leave_accept');

    Route::get('cmn_1', 'Panel\PanelController@cmn_1');
    Route::get('cmn_2', 'Panel\PanelController@cmn_2');
    Route::get('cmn_3', 'Panel\PanelController@cmn_3');
    Route::get('cmn_4', 'Panel\PanelController@cmn_4');
    Route::get('cmn_5', 'Panel\PanelController@cmn_5');
    Route::get('cmn_6', 'Panel\PanelController@cmn_6');
    Route::get('cmn_7', 'Panel\PanelController@cmn_7');

    Route::get('addCustomer/{id}', 'Panel\PanelController@addCustomer');
    Route::post('addDraft', 'Panel\PanelController@addDraft');

    // work controller
    Route::get('works/{id?}', 'Panel\WorkController@index');
    Route::get('work-create', 'Panel\WorkController@create');
    Route::post('work-store', 'Panel\WorkController@store');
    Route::get('work-edit/{id}', 'Panel\WorkController@edit');
    Route::post('work-update/{id}', 'Panel\WorkController@update');
    Route::post('work-stop', 'Panel\WorkController@stop');


    Route::get('addDraft', 'Panel\PanelController@addDraft');
    Route::get('draftEdit/{id}', 'Panel\PanelController@editDraft');
    Route::get('contractDraft', 'Panel\PanelController@contractDraft');
    Route::get('contractDraftOk', 'Panel\PanelController@contractDraftOk');
    Route::post('storeDraft', 'Panel\PanelController@storeDraft');
    Route::post('updateDraft/{id}', 'Panel\PanelController@updateDraft');
    Route::get('draftOk/{id}', 'Panel\PanelController@okDraft');
    Route::get('draftNOk/{id}', 'Panel\PanelController@nokDraft');
    Route::get('draftPrint/{id}/{clause}', 'Panel\PanelController@draftPrint');
    Route::get('draftToOk/{id}', 'Panel\PanelController@draftToOk');



    // DeliveryFormOfGoods
    Route::get('deliveryFormOfGoods', 'Panel\TechnicalFormController@deliveryForm');
    Route::post('delivery/create', 'Panel\TechnicalFormController@create')->name('delivery-create');



    //messenger
    Route::get('messenger', 'Panel\MessengerController@index')->name('messenger-index');
    Route::post('messenger/send/text', 'Panel\MessengerController@sendText')->name('messenger-send-text');
    Route::post('messenger/get/message', 'Panel\MessengerController@messages')->name('messenger-get-message');


    //time_login_adib
    Route::get('time-login-adib/{status}', 'Panel\TimeAdibController@index')->name('time-login-index');
    Route::get('time-login-adib-status/{id}/{status}', 'Panel\TimeAdibController@status')->name('time-login-status');
    Route::get('time-login-adib-create', 'Panel\TimeAdibController@create')->name('time-login-create');
    Route::post('time-login-adib-create/post', 'Panel\TimeAdibController@create_post')->name('time-login-create-post');
    
});


Route::group(['prefix' => 'dashbord', 'middleware' => ['auth', 'roles'], 'roles' => ['Customer']], function () {

    // Index
    Route::resource('/', 'Dashbord\DashbordController');

    // Profile
    Route::resource('profile', 'Dashbord\ProfileController');

    // Ticket
    Route::resource('ticket', 'Dashbord\TicketController');
    Route::post('comment_store', 'Dashbord\TicketController@comment_store');
    Route::get('invoices', 'Dashbord\TicketController@invoices');
    Route::get('domain', 'Dashbord\DomainController@index');
    Route::get('host', 'Dashbord\HostController@index');
    Route::get('report/{id}', 'Dashbord\ProjectController@report');

    Route::get('blocked', 'Dashbord\DashbordController@blocked');

    // Project
    Route::resource('project', 'Dashbord\ProjectController', ['only' => ['index', 'show']]);

});

Auth::routes();

// Index

Route::group(['middleware' => ['cors']], function () {
    Route::get('/', 'HomeController@home');

    Route::get('has_ticket/{id}', 'HomeController@has_ticket');
    Route::get('expire', 'HomeController@expire');


    Route::get('home', 'HomeController@home');

    Route::post('panel/traf/{id}', 'HomeController@traf');
});

Route::get('/telegram', 'HomeController@telegram');
