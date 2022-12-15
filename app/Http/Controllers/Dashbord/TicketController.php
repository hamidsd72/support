<?php

namespace App\Http\Controllers\Dashbord;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\Sms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Telegram\Bot\Laravel\Facades\Telegram;


class TicketController extends Controller
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
        $data = Ticket::where(['user__id' => $user_id, 'ticket__type' => 'services'])->get();
        return view('dashbord.ticket.home', compact('data'), ['title' => 'لیست تیکت ها', 'invoices' => 'yes']);
    }

//    function deleteTicket($id)
//    {
//        $ticket = Ticket::find($id);
//        if (!is_null($ticket)) {
//            $comments = Comment::where('commendable_id', $ticket->id)->get();
//            foreach ($comments as $key => $item) {
//                $item->delete();
//            }
//            $ticket->delete();
//        }
//
//    }

    public function invoices()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $data = Ticket::where(['user__id' => $user_id, 'ticket__type' => 'invoices'])->get();
        return view('dashbord.ticket.home', compact('data'), ['title' => 'لیست فاکتورها']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereNotIn('id', [1, 5, 8])->get();
        return view('dashbord.ticket.create', compact('roles'), ['title' => 'ثبت تیکت']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return response()->json($request);
        $validate=$this->validate($request, [
            'role__id' => 'required',
            'ticket__title' => 'required',
            'ticket__content' => 'required',
            'ticket__priority' => 'required',
            'contract_id' => 'required',
        ],
        [
            'contract_id.required' => 'انتخاب قرارداد الزامی می باشد',
            'role__id.required' => 'انتخاب بخش الزامی می باشد',
            'ticket__title.required' => 'پر کردن عنوان الزامی می باشد',
            'ticket__content.required' => 'توضیحات تیکت الزامی می باشد',
        ]
        );

//
        $user = Auth::user();
//
        $user_id = $user->id;
        $data = Ticket::where('user__id', $user_id)->get();

//        try {

            $ticket = Ticket::create([
                'user__id' => $user_id,
                'role__id' => $request->role__id,
                'ticket__status' => 'pending',
                'ticket__title' => $request->ticket__title,
                'ticket__priority' => $request->ticket__priority,
                'ticket__content' => $request->ticket__content,
                'contract_id' => $request->contract_id
            ]);
            $type='';
            switch ($request->role__id) {
                case '2':
                    $type = 'بخش شبکه و سخت افزار';
                    break;
                case '3':
                    $type = 'بخش مالی';
                    break;
                case '4':
                    $type = 'بخش سایت و سئو';
                    break;
                case '6':
                    $type = 'بخش فروش';
                    break;
                case '9':
                    $type = 'مدیریت فنی';
                    break;
            }
            $msg= '<h3 style="text-align: right;font-weight: bold;margin-top: 0px">کاربر گرامی </h3>';
            $msg.='<p>با تشکر از تماس شما با واحد '.$type.'، درخواست شما با مشخصات زیر در این واحد ثبت شده است.</p>';
            $msg.='<p>عنوان درخواست: '.$request->ticket__title.'</p>';
            $msg.='<p>وضعیت درخواست: درانتظار تائید</p>';
            $msg.='<p>شناسه تیکت : '.$ticket->id.'</p>';
            $msg.='<hr style="text-align: center;border:1px dashed #848080;margin: 50px ">';
            $msg.='<p style="text-align: center">(پاسخ های ارسالی از طریق ایمیل قابل بررسی نیستند)</p>';
            send_mail($user->email,$request->ticket__title,$msg);
            if ($request->role__id == 9 || $request->role__id == 2) {
                $message = "تیکت:
$ticket->id
$user->company__name";
//Sms::SendSms($message,'09125474829');
            }


            if ($request->hasFile('comment__attachment')) {

                try {
                    foreach ($request->comment__attachment as $image) {

                        $file = $image;
                        $originalName = $file->getClientOriginalName();
                        $destinationPath = 'uploads/libraries/tickets/';
                        $extension = $file->getClientOriginalExtension();
                        $fileName = 'tikcet-' . md5(time() . '-' . $originalName) . '.' . $extension;
                        $file->move($destinationPath, $fileName);
                        $f_path = $destinationPath . "" . $fileName;
                        $library = new Library();
                        $library->file__path = $f_path;
                        $ticket->libraries()->save($library);

                    }
                } catch (\Exception $e) {
                    abort(500);
                }
            }

            require_once('jdf.php');
            $jalali_date = jdate("Y/m/j (H:i)");

            if ($request->role__id == 2) {

                $message = "کارشناس گرامی، تیکت جدید برای بخش شما ثبت گردید لطفا بررسی فرمایید. \n";
                $message .= "برای بخش : شبکه و سخت افزار \n";
                $message .= "نام تیکت : " . $request->ticket__title . "\n";
                $message .= "فرستنده : " . Auth::user()->company__name . "\n";
                $message .= "تاریخ ثبت تیکت : " . $jalali_date;

                //telegram_notify(115, $message);
                //telegram_notify(153, $message);
                //telegram_notify(2, $message);
            }

            if ($request->role__id == 4) {

                $message = "کارشناس گرامی، تیکت جدید برای بخش شما ثبت گردید لطفا بررسی فرمایید. \n";
                $message .= "برای بخش : فنی و پشتیبانی سایت و سئو \n";
                $message .= "نام تیکت : " . $request->ticket__title . "\n";
                $message .= "فرستنده : " . Auth::user()->company__name . "\n";
                $message .= "تاریخ ثبت تیکت : " . $jalali_date;

                //telegram_notify(230, $message);
                //telegram_notify(2, $message);
                //telegram_notify(111, $message);
            }

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: <CRM@adibit.ir>' . "\r\n";

//            mail('mahami@adibit.ir',$request->ticket__title, html_entity_decode($request->ticket__content) , $headers);
//            Mail::send('emails.email', ['ticket' => $ticket, 'customer_user'=> $user], function ($m) use ($ticket, $user) {
//                $m->from('CRM@adibit.ir', ''.$ticket->ticket__title.' تیکت جدید: ');
//
//                $m->to('mahami@adibit.ir', 'ریاست محترم شرکت ادیب')->subject($ticket->ticket__title);
//            });
//            try {
                if ($user->email != null) {

//                    Mail::send('emails.email', ['ticket' => $ticket, 'customer_user'=> $user], function ($m) use ($ticket, $user) {
//                        $m->from('CRM@adibit.ir', $ticket->ticket__title);
//
//                        $m->to($user->email, $user->name)->subject($ticket->ticket__title);
//                    });

                }
//            } catch (\Exception $e) {
//                abort(500);
//            }

            if ($request->role__id == 2) {
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                $headers .= 'From: <CRM@adibit.ir>' . "\r\n";

//                Mail::send('emails.email', ['ticket' => $ticket, 'customer_user'=> $user], function ($m) use ($ticket, $user) {
//                    $m->from('CRM@adibit.ir', ''.$ticket->ticket__title.' تیکت جدید: ');
//
//                    $m->to('mahami@adibit.ir', 'بخش شبکه و سخت افزار')->subject($ticket->ticket__title);
//                });
            }

//            $this->sendTelegram($ticket->id);
            return redirect('dashbord/ticket')->with('status', 'تیکت با موفقیت ثبت شد');

//        } catch (\Exception $e) {
//            abort(500);
//        }

    }

    public function comment_store(Request $request)
    {

        $this->validate($request, [
            'comment__content' => 'required'
        ]);

        $ticket = Ticket::where('id', $request->ticket__id)->first();
        if ($ticket->ticket__status == 'finished') {
            $ticket->touch();
            $ticket->created_at = Carbon::now()->toDateTimeString();
            $ticket->update();
        }
        $user = Auth::user();
        $user_id = $user->id;

        try {

            $ticket->ticket__status = $request->ticket__status;
            $ticket->seen__id = 0;

            $ticket->save();

            $comment = new Comment();

            $comment->user__id = $user_id;
            $comment->comment__content = $request->comment__content;

            $ticket->comments()->save($comment);

            if ($request->hasFile('comment__attachment')) {

                try {
                    foreach ($request->comment__attachment as $image) {

                        $file = $image;
                        $originalName = $file->getClientOriginalName();
                        $destinationPath = 'uploads/libraries/tickets/';
                        $extension = $file->getClientOriginalExtension();
                        $fileName = 'tikcet-' . md5(time() . '-' . $originalName) . '.' . $extension;
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

            $date = $ticket->updated_at;
            $timestamp = (strtotime($date));
            require_once('jdf.php');
            $jalali_date = jdate("Y/m/j (H:i)", $timestamp);

            if ($ticket->role__id == 2) {

                $message = "کارشناس گرامی، تیکت جدید برای بخش شما ثبت گردید لطفا بررسی فرمایید. \n";
                $message .= "برای بخش : شبکه و سخت افزار \n";
                $message .= "شماره تیکت : " . $ticket->id . "\n";
                $message .= "نام تیکت : " . $ticket->ticket__title . "\n";
                $message .= "فرستنده : " . Auth::user()->company__name . "\n";
                $message .= "آخرین بروزرسانی : " . $jalali_date;

                //telegram_notify(115, $message);
                //telegram_notify(153, $message);
                //telegram_notify(2, $message);
            }

            if ($ticket->role__id == 4) {

                $message = "کارشناس گرامی، به تیکت شما پاسخ داده شد \n";
                $message .= "برای بخش : فنی و پشتیبانی سایت و سئو \n";
                $message .= "شماره تیکت : " . $ticket->id . "\n";
                $message .= "نام تیکت : " . $ticket->ticket__title . "\n";
                $message .= "فرستنده : " . Auth::user()->company__name . "\n";
                $message .= "آخرین بروزرسانی : " . $jalali_date;

                //telegram_notify(230, $message);
                //telegram_notify(2, $message);
                //telegram_notify(111, $message);

            }

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: <CRM@adibit.ir>' . "\r\n";

//            Mail::send('emails.email', ['ticket' => $ticket, 'customer_user'=> $user], function ($m) use ($ticket, $user) {
//                $m->from('CRM@adibit.ir', ''.$ticket->ticket__title.' تیکت جدید: ');
//
//                $m->to('mahami@adibit.ir', 'ریاست محترم شرکت ادیب')->subject($ticket->ticket__title);
//            });

            if ($request->role__id == 2) {
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                $headers .= 'From: <CRM@adibit.ir>' . "\r\n";
//                Mail::send('emails.email', ['ticket' => $ticket, 'customer_user'=> $user], function ($m) use ($ticket, $user) {
//                    $m->from('CRM@adibit.ir', ''.$ticket->ticket__title.' تیکت جدید: ');
//
//                    $m->to('ceo@adibit.ir', 'ریاست محترم شرکت ادیب')->subject($ticket->ticket__title);
//                });
            }

            $this->tgCommnet($comment->id);

            return redirect('dashbord/ticket')->with('status', 'پاسخ شما ثبت شد.');

        } catch (\Exception $e) {
            abort(500);
        }

    }

    public function tgCommnet($comment_id)
    {
        $tg_comment = Comment::find($comment_id);
        $tg_ticket = Ticket::find($tg_comment->ticket->id);
        $tg_ticket_code = $tg_ticket->id;
        $tg_ticket_title = $tg_ticket->ticket__title;
        $tg_ticket_creator = $tg_ticket->creator_id;
        $tg_user = $tg_comment->user__id;
        $tg_date = $tg_comment->created_at;
        $tg_content = $tg_comment->comment__content;
        if (!is_null($tg_user)) {
            $tg_user = User::find($tg_user);
            $tg_user_id = $tg_user;
            if (!is_null($tg_user)) {

                $tg_user = $tg_user->company__name;
                if (is_null($tg_user)) {
                    $tg_user = $tg_user_id->name;
                } else {
                    $tg_user = $tg_user_id->name . " ( " . $tg_user . " )";
                }
            }
        }
        if (!is_null($tg_date)) {
            $tg_date = (strtotime($tg_date));
            require_once('jdf.php');
            $tg_date = jdate("Y/m/j (H:i)", $tg_date);
        }

        if (!is_null($tg_ticket_creator)) {
            $tg_ticket_creator = User::find($tg_ticket_creator);
            $tg_ticket_creator_id = $tg_ticket_creator;
            if (!is_null($tg_ticket_creator)) {

                $tg_ticket_creator = $tg_ticket_creator->company__name;
                if (is_null($tg_ticket_creator)) {
                    $tg_ticket_creator = $tg_ticket_creator_id->name;
                } else {
                    $tg_ticket_creator = $tg_ticket_creator_id->name . " ( " . $tg_ticket_creator . " )";
                }
            }
        }

        $text = "$tg_user به تیکت $tg_ticket_code پاسخ داد\n"
            . "<b>ثبت: </b> $tg_date\n"
            . "<b>عنوان تیکت: </b> $tg_ticket_title\n"
            . "<b>متن پاسخ: </b> \n $tg_content";

//        Telegram::sendMessage([
//            'chat_id' => -1001174338090,
//            'parse_mode' => 'HTML',
//            'text' => $text
//        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $data = $ticket;
        return view('dashbord.ticket.show', compact('data'));
    }

    public function sendTelegram($ticket_id)
    {
        $tg_ticket = Ticket::find($ticket_id);
        $tg_title = $tg_ticket->ticket__title;
        $tg_priorty = $tg_ticket->ticket__priority;
        $tg_user = $tg_ticket->user__id;
        $tg_creator = $tg_ticket->creator_id;
        $tg_date = $tg_ticket->created_at;
        $tg_content = $tg_ticket->ticket__content;
        $tg_code = $tg_ticket->id;
        $tg_role = $tg_ticket->role__id;
        $tg_link = url("panel/ticket", $tg_ticket->id);

        if (!is_null($tg_user)) {
            $tg_user = User::find($tg_user);
            $tg_user_id = $tg_user;
            if (!is_null($tg_user)) {

                $tg_user = $tg_user->company__name;
                if (is_null($tg_user)) {
                    $tg_user = $tg_user_id->name;
                } else {
                    $tg_user = $tg_user_id->name . " ( " . $tg_user . " )";
                }
            }
        }
        if (!is_null($tg_creator)) {
            $tg_creator = User::find($tg_creator);
            $tg_creator_id = $tg_creator;
            if (!is_null($tg_creator)) {

                $tg_creator = $tg_creator->company__name;
                if (is_null($tg_creator)) {
                    $tg_creator = $tg_creator_id->name;
                } else {
                    $tg_creator = $tg_creator_id->name . " ( " . $tg_creator . " )";
                }
            }
        }

        if (!is_null($tg_priorty)) {
            switch ($tg_priorty) {
                case 'low':
                    $tg_priorty = 'کم';
                    break;
                case 'normal':
                    $tg_priorty = 'متوسط';
                    break;
                case 'high':
                    $tg_priorty = 'زیاد';
                    break;
            }
        }

        if (!is_null($tg_date)) {
            $tg_date = (strtotime($tg_date));
            require_once('jdf.php');
            $tg_date = jdate("Y/m/j (H:i)", $tg_date);
        }

        if (!is_null($tg_role)) {
            $tg_role = Role::find($tg_role);
            if (!is_null($tg_role)) {
                $tg_role = $tg_role->description;
            }
        }

        $text = "تیکت جدید در سایت ثبت شده است\n"
            . "<b>کد: </b> $tg_code\n"
            . "<b>بخش: </b> $tg_role\n"
            . "<b>عنوان: </b> $tg_title\n"
            . "<b>اولویت: </b> $tg_priorty\n"
            . "<b>فرستنده: </b> $tg_user\n"
            . "<b>ثبت: </b> $tg_date\n"
            . "<b>مشاهده: </b> \n $tg_link\n"
            . "<b>متن تیکت: </b> \n $tg_content";

//        Telegram::sendMessage([
//            'chat_id' => -1001174338090,
//            'parse_mode' => 'HTML',
//            'text' => $text
//        ]);

    }

}
