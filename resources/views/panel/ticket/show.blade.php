@extends('layouts.panel')
@section('styles')
@endsection
@section('content')

 <div class="col-md-12">
  <div class="panel panel-default">
   <div class="panel-sidebar panel-heading">
    @if($data->ticket__type != 'invoices')
     مشاهده تیکت {{$data->id}}
    @else
     مشاهده فاکتور {{$data->id}}
    @endif
   </div>
   <div class="panel-body">

    <p>
      {{$data->ticket__title}}
      @if($data->referred)
      {{$data->contract_true=='no'?'(بدون قرارداد) ارجاع به '.$data->referred->name:''}}
      @else
      {{$data->contract_true=='no'?'(بدون قرارداد)':''}}
        @endif
    </p>

    <div class="col-md-12 alert alert-default">
     <div class="col-sm-6"><i class="fa fa-clock-o"></i> زمان ایجاد
      : @include('partials.ticket-jdate-register', $data) ({{$data->created_at->format('H:i')}})
     </div>
     <div class="col-sm-6"><i class="fa fa-clock-o"></i> آخرین بروزرسانی
      : @include('partials.ticket-jdate-update', $data) ({{$data->updated_at->format('H:i')}})
     </div>
     <div class="col-sm-6"><i class="fa fa-bar-chart"></i>
      @if($data->ticket__type != 'invoices')
       وضعیت تیکت :
      @else
       وضعیت فاکتور :
      @endif
      @if($data->ticket__status == "pending")
       <span class="table-status table-pending">در انتظار پاسخ</span>
      @elseif($data->ticket__status == "answered")
       <span class="table-status table-answered">پاسخ داده شده</span>
      @elseif($data->ticket__status == "closed")
       <span class="table-status table-closed">بسته شده</span>
      @elseif($data->ticket__status == "doing")
       <span class="table-status table-doing">در حال پیگیری</span>
      @elseif($data->ticket__status == "finished")
       <span class="table-status table-finished">به پایان رسیده</span>
      @elseif($data->ticket__status == "unpaid")
       <span class="table-status table-no-pay">پرداخت نشده</span>
      @elseif($data->ticket__status == "paid")
       <span class="table-status table-answered">پرداخت شده</span>
      @endif
     </div>
     <div class="col-sm-6"><i class="fa fa-user"></i> بخش :
      {{role_set($data->role__id)}}
      {{--                        @if($data->role__id == 1)--}}
      {{--                            مدیریت--}}
      {{--                        @elseif($data->role__id == 2)--}}
      {{--                            بخش فنی--}}
      {{--                        @elseif($data->role__id == 3)--}}
      {{--                            بخش مالی--}}
      {{--                        @elseif($data->role__id == 4)--}}
      {{--                            بخش پشتیبانی سایت و سئو--}}
      {{--                        @endif--}}
     </div>
    </div>
    @if(count($data->user->contracts))
     <div class="col-md-12 alert alert-default mt-2 mb-3" style="background: #f2eb9f">
      @foreach($data->user->contracts as $contract)
       @php $date = dateDiffDomain(date('Y-m-d 00:00:00'), $contract->expire);@endphp
       <div class="col-md-6">زمان باقی مانده از قرارداد :
        @if($date<=0)
         <span class="table-status table-no-pay">{{ $date }} روز گذشته و منقضی شده</span>
        @else
         <span class="table-status table-answered">{{ $date }} روز از قرارداد مانده</span>
        @endif
       </div>
       <div class="col-md-3">وضعیت :
        @if($contract->active == 1)

         <span class="table-status table-answered">فعال</span>

        @elseif($contract->active == 2)

         <span class="table-status table-no-pay">غیرفعال</span>

        @endif
       </div>
       <div class="col-md-3">
        <span class="alert alert-warning">{{ $contract->type }}</span>
       </div>
      @endforeach
     </div>
    @endif

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px;margin-bottom: 10px;">
     <div class="row tikcet a-b">
      @php
       $workTimesheet_doing=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('ticket',$data->id,'doing');
       $workTimesheet_finished=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('ticket',$data->id,'finished');
       $workTimesheet_paused=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('ticket',$data->id,'paused');
      @endphp
      @if($data->ticket__status != 'closed' and $data->ticket__status != 'unpaid')
       @if (Auth::user()->id == 111 or Auth::user()->id == 177 or Auth::user()->role_id == 8)
        <a href="{{url('panel/ticket_closed', $data->id)}}" class="table-status table-closed"
           onclick="return confirm('ینی واقعا میخوای ببندیش؟')"><i
                 class="fa fa-power-off"></i> بستن تیکت</a>
       @endif

       <a href="{{url('panel/ticket_doing', $data->id)}}" class="table-status table-doing"><i
                class="fa fa-rocket"></i> در حال پیگیری</a>

       {{--<a href="javascript:void(0);" class="table-status table-finished" data-toggle="modal" data-target="#hasDone"><i--}}
       {{--class="fa fa-check"></i> انجام شد</a>--}}
       @if(auth()->user()->role_id==1 or auth()->user()->role_id==9 or auth()->user()->id==10000099)
        <a href="javascript:void(0)" class="reference table-status table-pending"
           data-toggle="modal" data-target="#reference">
         <i class="fa fa-reply-all"></i> ارجاع تیکت</a>
         @elseif($data->contract_true=='no' && $data->referred_to==10000092 && auth()->id()==10000092)
           <a href="javascript:void(0)" class="reference table-status table-pending"
              data-toggle="modal" data-target="#reference">
             <i class="fa fa-reply-all"></i> ارجاع تیکت</a>
       @endif
       @if(auth()->user()->role_id==1 or auth()->user()->role_id==9)
        <a href="javascript:void(0)" class="reference table-status table-list-btn"
           data-toggle="modal" data-target="#reference_move">
         <i class="fa fa-reply-all"></i> انتقال تیکت</a>
         @elseif($data->contract_true=='no' && $data->referred_to==10000092 && auth()->id()==10000092)
           <a href="javascript:void(0)" class="reference table-status table-list-btn"
              data-toggle="modal" data-target="#reference_move">
             <i class="fa fa-reply-all"></i> انتقال تیکت</a>
       @endif

      @endif
      @if($data->user->company__site)
       <a target="_blank" href="http://{{$data->user->company__site}}" class="table-status table-finished"><i
                class="fa fa-web"></i>{{ $data->user->company__site }}</a>
      @endif
      @if($data->ticket__type == 'invoices')
       <a href="{{url('panel/invoice_confirm', $data->id)}}"
          class="confirm btn btn-labeled-left"><i class="fa fa-check"></i> تایید فاکتور</a>
      @endif

      @if($workTimesheet_doing)
       <a href="javascript:void(0)" style="background-image: linear-gradient(230deg, #759bff, #843cf6);"
          class="table-status table-doing"><i
                class="far fa-hourglass" style="margin-left: 5px;"></i>در حال انجام </a>
      @endif
      @if($workTimesheet_finished)
       <form action="{{url('panel/timesheet-store')}}" method="post" id="selectProject-form">
        <button type="submit" style="background-image: linear-gradient(230deg, #759bff, #843cf6);"
                class="table-status table-doing startWorkBtn"><i
                 class="far fa-play-circle" style="margin-left: 5px;"></i>از سرگیری مجدد
        </button>
        <input type="hidden" value="{{$data->id}}" name="type_id">
        <input type="hidden" value="ticket" name="type">
        {{ csrf_field() }}
       </form>
      @endif

      @if(!$workTimesheet_doing && !$workTimesheet_finished)
       <form action="{{url('panel/timesheet-store')}}" method="post" id="selectProject-form">
        <button type="submit" style="background-image: linear-gradient(230deg, #759bff, #843cf6);"
                class="table-status startWorkBtn table-doing"><i
                 class="far fa-play-circle" style="margin-left: 5px;"></i>
         {{ $workTimesheet_paused?'ادامه کار':'شروع کن' }}
        </button>
        <input type="hidden" value="{{$data->id}}" name="type_id">
        <input type="hidden" value="ticket" name="type">
        {{ csrf_field() }}
       </form>
      @endif

      <button type="button" class="answer btn btn-labeled-left"><b><i class="fa fa-comment"></i></b>
       پاسخ
      </button>
     </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
     <div class="row">

      <form action="{{url('panel/comment_store')}}" method="post" enctype="multipart/form-data"
            class="send_comment form-horizontal">
       <fieldset>
        <input type="hidden" name="ticket__id" value="{{$data->id}}">
        <input type="hidden" name="ticket__status" value="answered">
        <div class="form-group{{ $errors->has('comment__content') ? ' has-error' : '' }}">
         <div class="col-md-12">
          <label for="comment__content" class="form-label">پاسخ جدید :</label>
          <textarea id="comment__content" class="form-control" name="comment__content"
                    rows="10">{{old('comment__content')}}</textarea>
          @if ($errors->has('comment__content'))
           <span class="help-block"><strong>{{$errors->first('comment__content')}}</strong></span>
          @endif
         </div>
        </div>

        <div class="form-group{{ $errors->has('comment__attachment') ? ' has-error' : '' }}">
         <div class="col-md-12">
          <label for="comment__attachment" class="form-label">پیوست :</label>
          <p class="ticket__type">پسوندهای مجاز: .jpg, .gif, .jpeg, .png, .txt, .pdf,
           .zip, .rar</p>
          <input id="comment__attachment" type="file" name="comment__attachment[]"
                 class="form-control" multiple/>
          @if ($errors->has('comment__attachment'))
           <span class="help-block"><strong>{{$errors->first('comment__attachment')}}</strong></span>
          @endif
         </div>
        </div>

        {{csrf_field()}}
        <div id="ticketAnswerHour" class="modal fade" role="dialog">
         <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
           <div class="modal-header">
            <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">کارشناس گرامی لطفا اطلاعات را وارد کنید</h4>
           </div>
           <div class="modal-body" style="display: flex;">
            <div class="col-md-12 form-group{{ $errors->has('hour') ? ' has-error' : '' }}">
             <label for="hour" class="form-label">مجموع دقایق کاری :</label>
             <input id="hour" type="number" class="form-control" name="hour" required/>
             @if ($errors->has('hour'))
              <span class="help-block"><strong>{{$errors->first('hour')}}</strong></span>
             @endif
            </div>
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
            <button type="button" class="btn btn-success" onclick="document.getElementById('commentSubmit').click();">
             انجام شد
            </button>
           </div>
          </div>
         </div>
        </div>
        <button type="button" data-toggle="modal"
                data-target="{{ auth()->user()->role_id!=4 || $workTimesheet_doing ? '#ticketAnswerHour':'' }}"
                class="btn btn-labeled-left {{ auth()->user()->role_id==4 && !$workTimesheet_doing?'startWork':'' }}"><i
                 class="fa fa-check"></i> ارسال
        </button>
        <button type="submit" style="display: none;" id="commentSubmit" class="btn btn-labeled-left"><i
                 class="fa fa-check"></i></button>

       </fieldset>
      </form>

     </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 padding-0">
     <div class="row">

      <div class="ticket-message">
{{--      @dd($data->comments)--}}
       @foreach($data->comments->reverse()->sortByDesc('updated_at') as $comment)

        @if(isset($comment->user))
         @if($comment->user->role->id == 5)

          <div class="clientticket">
           <div class="clientheader"><h5><i class="fa fa-comments" aria-hidden="true"></i> <span
                     class="pull-left">#{{ $comment->id }}</span> @if(isset($comment->user)) {{$comment->user->name}} @endif @if(isset($comment->user))
              ( شرکت
              : {{$comment->user->company__name}} ) @endif</h5></div>
           <div class="clientmsg">
            <p>{!! html_entity_decode(nl2br($comment->comment__content)) !!}</p>
            @if(isset($comment->libraries)) <p class="text-center">تعداد فایل های پیوست
             : {{$comment->libraries->count()}}</p>
            @foreach($comment->libraries as $library)
             <div class="comment_attach"><i class="fa fa-paperclip"></i> <a
                      href="{{str_replace('index.php/','',url($library->file__path))}}" target="_blank">مشاهده
               فایل پیوست</a></div>
            @endforeach
            @endif
            <hr>
           </div>
           <div class="ticket-footer clearfix">
            <div class="tickets-timestamp"><i class="fa fa-clock-o"
                                              aria-hidden="true"></i> @include('partials.comment-jdate', $comment)
             ({{$comment->created_at->format('H:i')}})
            </div>
           </div>
          </div>

         @else
          <div class="adminticket">
           <div class="adminheader">
            <h5><i class="fa fa-comments" aria-hidden="true"></i> <span class="pull-left">#{{ $comment->id }}</span>
             ادیب ( پشتیبان )
             {!! $comment->confirmation==1?'':"<span class='table-status table-no-pay confirmation-status'>در انتظار تایید</span>" !!}
             @if($comment->confirmation==0)
              @if(auth()->user()->role_id==1)
{{--               <button data-id="{{$comment->id}}" class='table-status table-answered confirm-comment'>تایید</button>--}}
               <a href="javascript:void(0)" class="reference table-status table-answered"
                  data-toggle="modal" data-target="#active_comment">
               تایید</a>

               <!-- Modal -->
               <div id="active_comment" class="modal fade" role="dialog">
                <div class="modal-dialog">

                 <!-- Modal content-->
                 <div class="modal-content">

                  <div class="modal-header">
                   <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title text-success">تائید پاسخ کاربر</h4>
                  </div>
                  <form action="{{route('p-comment-confirm', $comment->id)}}" method="post">
                   {{ csrf_field() }}

                   <div class="modal-body">


                    <div class="form-group{{ $errors->has('role__id') ? ' has-error' : '' }}">
                     <label for="user__id" class="form-label">نوع تائید :</label>
                     <select id="status" name="status" class="select text-success"
                             data-placeholder="نوع تائید">
                      <option value="answered">تایید و اتمام کار</option>
                      <option value="waiting_answered">تایید و ادامه کار</option>
                      {{--<option value="">تائید و منتظر پاسخ</option>--}}

                     </select>

                    </div>


                  </div>
                  <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                   <button type="submit" class="btn btn-success" >تایید
                   </button>
                  </div>
                  </form>
                 </div>

                </div>
               </div>


               <button data-id="{{$comment->id}}" data-text="{{$comment->comment__content}}"
                       class='table-status table-pending edit-comment mr-2'>ویرایش
               </button>
              @endif
             @endif
            </h5>
           </div>
           <div class="adminmsg">
            <div class="comment_pic_name">
             <img src="{{url($comment->user->profile??'img/user-icon.png')}}" alt="{{$comment->user->name}}">
             <div class="comment_name">
              <h5>{{$comment->user->name}}</h5>
              <p><i class="fa fa-clock-o"
                    aria-hidden="true"></i> @include('partials.comment-jdate', $comment)
               ({{$comment->created_at->format('H:i')}})</p>
             </div>
            </div>
            <p class="p_comment">{!! html_entity_decode(nl2br($comment->comment__content)) !!}</p>
            @if(isset($comment->libraries)) <p class="text-center">تعداد فایل های پیوست
             : {{$comment->libraries->count()}}</p>
            @foreach($comment->libraries as $library)
             <div class="comment_attach"><i class="fa fa-paperclip"></i> <a
                      href="{{str_replace('index.php/','',url($library->file__path))}}" target="_blank">مشاهده
               فایل پیوست</a></div>
            @endforeach
            @endif
            <hr>
            <div class="container-fluid">
             <div class="comment_pic_name_1">
              <img src="{{url('img/logo.png')}}" alt="ادیب گستر عصر نوین">
              <div class="comment_name_1">
               <h4>{{$comment->user->name}}</h4>
               <h5>
                {{$comment->user->role->description}}
               </h5>
              </div>
             </div>
            </div>
            {{--                                                <p @if($comment->user->id == 1) style="direction: ltr; text-align: left" @endif>{{$comment->user->name}} -- @if($comment->user->id == 1) <code>< Full-stack web developer > - { Senior Programer }</code> @else {{$comment->user->role->description}} @endif</p>--}}
           </div>
           {{--                                            <div class="ticket-footer clearfix">--}}
           {{--                                                <div class="tickets-timestamp"><i class="fa fa-clock-o"--}}
           {{--                                                                                  aria-hidden="true"></i> @include('partials.comment-jdate', $comment)--}}
           {{--                                                    ({{$comment->created_at->format('H:i')}})--}}
           {{--                                                </div>--}}
           {{--                                            </div>--}}
          </div>
         @endif
        @endif

       @endforeach

       @if($data->ticket__type != 'invoices')

        @if(isset($data->user))

         @if($data->send__id == 0)

          <div class="clientticket">
           <div class="clientheader"><h5><i class="fa fa-comments"
                                            aria-hidden="true"></i> <span
                     class="pull-left">#{{ $data->id }}</span> {{$data->user->name}} (
             شرکت : {{$data->user->company__name}} )</h5></div>
           <div class="clientmsg">
            <p>{!! html_entity_decode(nl2br($data->ticket__content)) !!}</p>
            @if(isset($data->libraries)) <p class="text-center">تعداد فایل های پیوست
             : {{$data->libraries->count()}}</p>
            @foreach($data->libraries as $library)
             <div class="comment_attach"><i class="fa fa-paperclip"></i> <a
                      href="{{str_replace('index.php/','',url($library->file__path))}}" target="_blank">مشاهده
               فایل پیوست</a></div>
            @endforeach
            @endif
            <hr>
           </div>
           <div class="ticket-footer clearfix">
            <div class="tickets-timestamp"><i class="fa fa-clock-o"
                                              aria-hidden="true"></i> @include('partials.ticket-jdate-register', $data)
             ({{$data->created_at->format('H:i')}})
            </div>
           </div>
          </div>

         @elseif($data->send__id == 1)

          <div class="adminticket">
           <div class="adminheader"><h5><i class="fa fa-comments" aria-hidden="true"></i> <span
                     class="pull-left">#{{ $data->id }}</span>
             ادیب ( پشتیبان )</h5></div>
           <div class="adminmsg">
            <p>{!! html_entity_decode(nl2br($data->ticket__content)) !!}</p>
            @if(isset($data->libraries)) <p class="text-center">تعداد فایل های پیوست
             : {{$data->libraries->count()}}</p>
            @foreach($data->libraries as $library)
             <div class="comment_attach"><i class="fa fa-paperclip"></i> <a
                      href="{{str_replace('index.php/','',url($library->file__path))}}" target="_blank">مشاهده
               فایل پیوست</a></div>
            @endforeach
            @endif
            <hr>
           </div>
           <div class="ticket-footer clearfix">
            <div class="tickets-timestamp"><i class="fa fa-clock-o"
                                              aria-hidden="true"></i> @include('partials.ticket-jdate-register', $data)
             ({{$data->created_at->format('H:i')}})
            </div>
           </div>
          </div>

         @endif

        @else
         <div class="adminticket">
          <div class="adminheader"><h5><i class="fa fa-comments" aria-hidden="true"></i> <span
                    class="pull-left">#{{ $data->id }}</span> ادیب
            ( پشتیبان )</h5></div>
          <div class="adminmsg">
           <p>{!! html_entity_decode(nl2br($data->ticket__content)) !!}</p>
           @if(isset($data->libraries)) <p class="text-center">تعداد فایل های پیوست
            : {{$data->libraries->count()}}</p>
           @foreach($data->libraries as $library)
            <div class="comment_attach"><i class="fa fa-paperclip"></i> <a
                     href="{{str_replace('index.php/','',url($library->file__path))}}" target="_blank">مشاهده فایل
              پیوست</a></div>
           @endforeach
           @endif
           <hr>
          </div>
          <div class="ticket-footer clearfix">
           <div class="tickets-timestamp"><i class="fa fa-clock-o"
                                             aria-hidden="true"></i> @include('partials.ticket-jdate-register', $data)
            ({{$data->created_at->format('H:i')}})
           </div>
          </div>
         </div>
        @endif

       @endif
      </div>
     </div>

    </div>
   </div>

  </div>
 </div>

 <!-- Modal -->
 <div id="reference" class="modal fade" role="dialog">
  <div class="modal-dialog">

   <!-- Modal content-->
   <div class="modal-content">

    <div class="modal-header">
     <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
     <h4 class="modal-title">ارجاع تیکت به کاربر دیگر</h4>
    </div>
    <div class="modal-body">

     <form action="{{url('panel/reference', $data->id)}}" id="reference-form">

      <div class="form-group{{ $errors->has('role__id') ? ' has-error' : '' }}">
       <label for="user__id" class="form-label">برای کاربر :</label>
       <select id="user__id" name="user__id" class="select"
               data-placeholder="کاربر مربوطه را انتخاب کنید">
        <option value="">کاربر مربوطه را انتخاب کنید</option>

        @if(isset($merged_users1500))
         @foreach($merged_users1500 as $merged_users)
          <option value="{{ $merged_users->id }}">{{ $merged_users->name }} ({{role_set($merged_users->role_id)}})
          </option>
         @endforeach
        @endif
       </select>
       @if ($errors->has('role__id'))
        <span class="help-block"><strong>{{$errors->first('role__id')}}</strong></span>
       @endif
      </div>

     </form>
    </div>
    <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
     <button type="button" class="btn btn-success" onclick="document.getElementById('reference-form').submit();">ارجاع
     </button>
    </div>
   </div>

  </div>
 </div>

 <!-- Modal -->
 <div id="reference_move" class="modal fade" role="dialog">
  <div class="modal-dialog">

   <!-- Modal content-->
   <div class="modal-content">

    <div class="modal-header">
     <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
     <h4 class="modal-title">انتقال تیکت به بخش دیگر</h4>
    </div>
    <div class="modal-body">

     <form action="{{url('panel/reference-move', $data->id)}}" id="reference-move-form">

      <div class="form-group{{ $errors->has('role__id') ? ' has-error' : '' }}">
       <label for="user__id" class="form-label">برای بخش :</label>
       <select id="user__id" name="role__id" class="select"
               data-placeholder="بخش مربوطه را انتخاب کنید">
        <option value="">بخش مربوطه را انتخاب کنید</option>

        @if(isset($roles))
         @foreach($roles as $role)
          <option value="{{ $role->id }}">{{ $role->description }}</option>
         @endforeach
        @endif
       </select>
       @if ($errors->has('role__id'))
        <span class="help-block"><strong>{{$errors->first('role__id')}}</strong></span>
       @endif
      </div>

     </form>
    </div>
    <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
     <button type="button" class="btn btn-success" onclick="document.getElementById('reference-move-form').submit();">
      انتقال
     </button>
    </div>
   </div>

  </div>
 </div>

 <!-- Modal -->
 <div id="hasDone" class="modal fade" role="dialog">
  <div class="modal-dialog">

   <!-- Modal content-->
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close pull-left" data-dismiss="modal">&times;</button>
     <h4 class="modal-title">کارشناس گرامی لطفا اطلاعات را وارد کنید</h4>
    </div>
    <div class="modal-body">
     <form action="{{url('panel/ticket_finished')}}" method="POST" id="hasDone-form">

      <input type="hidden" name="ticket_id" value="{{$data->id}}"/>
      <input type="hidden" name="company_id" value="{{$data->user__id}}"/>
      <input type="hidden" name="user_id" value="{{Auth::user()->id}}"/>
      <div class="form-group{{ $errors->has('hour') ? ' has-error' : '' }}">
       <label for="hour" class="form-label">مجموع دقایق کاری برای این تیکت :</label>
       <input id="hour" type="number" class="form-control" name="hour" value="{{old('hour')}}"/>
       @if ($errors->has('hour'))
        <span class="help-block"><strong>{{$errors->first('hour')}}</strong></span>
       @endif
      </div>

      {{csrf_field()}}

     </form>
    </div>
    <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
     <button type="button" class="btn btn-success" onclick="document.getElementById('hasDone-form').submit();">انجام
      شد
     </button>
    </div>
   </div>

  </div>
 </div>

 {{-- edit comment --}}
 <div id="editComment" class="modal fade" role="dialog">
  <div class="modal-dialog">

   <!-- Modal content-->
   <div class="modal-content">
    <div class="modal-body">
     <form action="{{url('panel/comment-update')}}" method="post" id="editComment-form">
      <textarea id="comment__text" class="form-control" name="comment__text" rows="10"></textarea>
      {{ csrf_field() }}
     </form>
    </div>
    <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
     <button type="button" class="btn btn-success" onclick="document.getElementById('editComment-form').submit();">
      ویرایش و تایید
     </button>
    </div>
   </div>

  </div>
 </div>
@endsection
@section('scripts')
 <script>
     $('.edit-comment').click(function () {
         let text = $(this).data('text');
         let id = $(this).data('id');
         $('#comment__text').html(text);
         $('#editComment').modal('show');
         $('#editComment-form').attr('action', '{{route('comment-update')}}' + '/' + id);
     });
     $('.confirm-comment').click(function () {
         let id = $(this).data('id');
         $.ajax({
             url: '{{route('comment-confirm')}}' + '/' + id,
             method: 'GET',
             success: function (data) {
                 if (data == 'true') {
                     $('.confirmation-status').hide();
                     $('.confirm-comment').hide();
                     $('.confirm-edit').hide();
                 }
             }
         })
     })
 </script>
@endsection
