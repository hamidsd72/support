@extends('layouts.panel')
@section('styles_meta')
  <style>
      .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12,
      .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
          float: right;
      }

      .box-sh {
          box-shadow: 0 0 2px 0 #00000030;
          border-radius: 5px;
          padding: 20px 40px;
      }

      .btn-labeled-left.bg-danger {
          background: darkred;
      }

      .btn-labeled-left.bg-info {
          background: darkblue;
      }

      .btn-labeled-left.bg-danger:hover,
      .btn-labeled-left.bg-info:hover {
          background: #FF5722;
      }

      .modal-header .close {
          float: left;
          margin-top: -20px;
      }

      .modal-open .modal {
          z-index: 99999;
      }

      .comment_attach {
          float: unset !important;
          margin: 5px 0 !important;
      }
  </style>
@endsection
@section('content')

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-sidebar panel-heading">
        {{$item->title}}
      </div>
      <div class="panel-body">

        <p>
          دسته بندی :
          {{$item->cat?$item->cat->title:'__'}}
        </p>

        <div class="container-fluid alert alert-default">
          <div class="row">
            <div class="col-sm-6 py-2">
              <i class="fa fa-clock-o"></i>
              زمان ایجاد
              : @include('partials.ticket-jdate-register',['data'=>$item]) ({{$item->created_at->format('H:i')}})
            </div>
            <div class="col-sm-6 py-2">
              <i class="fa fa-clock-o"></i>
              آخرین بروزرسانی
              : @include('partials.ticket-jdate-update', ['data'=>$item]) ({{$item->updated_at->format('H:i')}})
            </div>

            <div class="col-sm-6 py-2">
              <i class="fa fa-user"></i>
              کاربر مسئول :
              @if($item->type_ref=='one')
                {{$item->user_ref?$item->user_ref->name:'__'}}
              @else
                {{$item->group_ref?$item->group_ref->title:'__'}}
                {{$item->group_ref_user ? '('.$item->group_ref_user->name.')':'__'}}
              @endif
            </div>
            <div class="col-sm-6 py-2">
              <i class="fa fa-user"></i>
              ایجاد شده توسط :
              {{$item->user_create?$item->user_create->name:'__'}}
            </div>
            <div class="col-sm-6 py-2">
              <i class="fa fa-home"></i>
              شرکت :
              {{$item->company_user?$item->company_user->company__name:'__'}}
              {{$item->company_contract?'('.$item->company_contract->type.')':'__'}}
            </div>
            <div class="col-sm-6 py-2">
              <i class="fa fa-clock-o"></i>
              زمان یادآوری :
              {{$item->type_reminder=='date'?$item->reminder_fa:$item->reminder_day($item->reminder).' '.$item->reminder_set($item->type_reminder)}}
            </div>
            <div class="col-sm-6 py-2">
              <i class="fa fa-bar-chart"></i>
              اولویت :
              {!! $item->priority_set($item->priority) !!}
            </div>
            <div class="col-sm-6 py-2">
              <i class="fa fa-bar-chart"></i>
              وضعیت :
              {!! $item->status_set($item->status) !!}
            </div>
          </div>
        </div>
        <div class="container-fluid alert alert-warning mt-2 mb-3">
          <div class="row">
            <div class="col-sm-6 py-3 text-right">
              <i class="fa fa-clock-o"></i>
              زمان صرف شده:
              {{min2time($item->time)}}
            </div>
            <div class="col-sm-6 py-3 text-right">
              <i class="fa fa-percent"></i>
              درصد پیشرفت:
              {{$item->percent}}%
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
          <div class="row">
            @if($item->status=='end')
              <div class="col-xs-12 alert alert-danger text-center py-3 my-3">
                فعالیت به پایان رسیده است
              </div>
            @else
              <div class="col-xs-12 my-3">



                @if($item->type_ref=='multi')


                  @if(auth()->id()==$item->user_group_id)
                    @if($item->status=='pending' || $item->status=='stop')
                      <a href="{{route('todo.list.status',[$item->id,'doing'])}}"
                         class="btn btn-labeled-right mx-2 bg-success">
                        <i class="fa fa-check"></i>
                        شروع فعالیت
                      </a>
                    @else
                      <a href="{{route('todo.list.status',[$item->id,'stop'])}}"
                         class="btn btn-labeled-right mx-2 bg-danger">
                        <i class="fa fa-close"></i>
                        توقف فعالیت
                      </a>
                    @endif
                  @endif

                  @if($item->status=='doing')
                    @if(auth()->id()==$item->user_group_id)
                      @include('panel.todo_list.includes.todo_list_ref')
                    @endif
                  @endif

                  @include('panel.todo_list.includes.todo_list_report')

                  @if($item->status=='doing')
                    @if(auth()->id()==$item->user_group_id)
                      @include('panel.todo_list.includes.todo_list_ref_back')
                    @endif
                  @endif

                @else
                  @if($ref_change)
                    @include('panel.todo_list.includes.ref')
                  @endif

                  @if(auth()->id()==$item->user_id)
                    @if($item->status=='pending' || $item->status=='stop')
                      <a href="{{route('todo.list.status',[$item->id,'doing'])}}"
                         class="btn btn-labeled-right mx-2 bg-success">
                        <i class="fa fa-check"></i>
                        شروع فعالیت
                      </a>
                    @else
                      <a href="{{route('todo.list.status',[$item->id,'stop'])}}"
                         class="btn btn-labeled-right mx-2 bg-danger">
                        <i class="fa fa-close"></i>
                        توقف فعالیت
                      </a>
                    @endif
                  @endif

                  @if($item->status=='doing')
                    @include('panel.todo_list.includes.todo_list_report_one')
                  @endif
                @endif
                  @if($item->status=='doing')
                    @if($check)
                      @include('panel.todo_list.includes.todo_list_checks')
                    @endif
                    @if($keyword)
                      @include('panel.todo_list.includes.todo_list_keyword')
                    @endif
                  @endif
                @if(auth()->id()==$item->user_id_create)
                  <a href="{{route('todo-list.edit',$item->id)}}"
                     class="btn btn-labeled-right mx-2 bg-info">
                    <i class="fa fa-edit"></i>
                    ویرایش فعالیت
                  </a>
                @endif
              </div>
            @endif
          </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 padding-0">
          <div class="row">

            <div class="ticket-message">
              @foreach($comments as $comment)
                <div class="clientticket">
                  <div class="clientheader">
                    <h5>
                      <i aria-hidden="true" class="fa fa-comments"></i>
                      {{$comment->user?$comment->user->name:$comment->user_id}}

                      <span class="pull-left">
                         <i aria-hidden="true" class="fa fa-clock-o"></i>
                        @include('partials.ticket-jdate-register', ['data'=>$comment]) ({{$comment->created_at->format('H:i')}})
                      </span>
                    </h5>
                  </div>
                  <div class="clientmsg">
                    <p>
                      {!! html_entity_decode(nl2br($comment->text)) !!}
                    </p>
                    @if(!blank($comment->change_item))
                      <hr>
                      <h5>مشخصات</h5>
                      @foreach(explode('___',$comment->change_item) as $key=>$ch_item)
                        @if($key>0)
                          <span class="mx-2">-</span>
                        @endif
                        <span>
                          {!! $ch_item !!}
                        </span>
                      @endforeach
                    @endif
                    <hr>
                    <p class="text-center">تعداد فایل های پیوست
                      : {{count($comment->libraries)}}</p>

                    @foreach($comment->libraries as $library)
                      <div class="comment_attach" style="display: inline-block">
                        <i class="fa fa-paperclip"></i>
                        <a href="{{str_replace('index.php/','',url($library->file__path))}}" target="_blank">
                          مشاهده فایل پیوست
                        </a>
                      </div>
                    @endforeach

                  </div>
                </div>
              @endforeach
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>



@endsection
@section('scripts')
  <script>
      $('#mySelect2').select2({
          dropdownParent: $('#RefModal')
      });

      $(".datepicker123").datepicker({
          dateFormat: 'yy,mm,dd',
          changeMonth: true,
          changeYear: true,
          minDate: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() + 1)
      });
  </script>
@endsection
