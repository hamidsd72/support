@extends('layouts.panel')
@section('content')
    {{-- company --}}
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">شرکت های من<span class="pull-left">تعداد : </span></div>
            <div class="panel-body">
                {{-- <button class="btn btn-sm btn-success pull-left" data-toggle="modal" data-target="#add_user" style="margin-right: .5rem">
                    <i class="fa fa-user"></i> افزودن کاربر</button> --}}
                <table class="table datatable-responsive22 table-togglable">
                    <thead>
                    <tr>
                            <th data-hide="phone">#</th>
                            <th data-toggle="true">نام</th>
                            <th data-toggle="true">نام شرکت</th>
                            <th data-hide="phone">ایمیل</th>
                            <th data-hide="phone">تلفن</th>
                            <th data-hide="phone">دسترسی</th>
                            <th data-hide="phone">وضعیت</th>
                            <th data-hide="phone">دستور</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach(auth()->user()->company()->get() as $data)
                            {{-- <tr onclick="document.location = '{{route("new_company.show", $data->id)}}';"> --}}
                            <tr>
                                <td>{{$data->updated_at->format('Y/m/d H:i')}}</td>
                                <td>{{$data->name}} <span class="badge"> وضعیت: {{$data->cat}}</span> </td>
                                <td>{{$data->phone}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{$data->manager}}</td>
                                <td>{{$data->percent}}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- end company --}}

    {{-- userSeo --}}
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">کلمات کلیدی</div>
            <div class="panel-body">
                <a href="{{ url('panel/keyCreate') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> افزودن</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-warning" data-target="#companies" data-toggle="modal"><i class="fa fa-plus"></i> شرکت ها</a>
                <table class="table table-togglable">
                    <thead>
                    <tr>
                        <th data-hide="phone">#</th>
                        <th data-toggle="true">نام شرکت</th>
                        <th data-hide="phone">کلمه کلیدی</th>
                        <th data-hide="phone">لینک</th>
                        <th data-hide="phone">صفحه</th>
                        <th data-hide="phone">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(auth()->user()->userSeo()->get() as $key=>$rows)
                        <tr>
                            <td style="background: aliceblue;font-weight: bold;" colspan="6">{{ $key }}</td>
                        </tr>
                        @foreach($rows as $key=>$item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->user->company__name }}</td>
                                <td>{{ $item->word }}</td>
                                <td>{{ $item->page }}</td>
                                <td>{{ $item->link }}</td>
                                <td>
                                    <div class="btn btn-group btn-group-xs">
                                        <a href="{{ url('panel/keyEdit', $item->id) }}" class="btn btn-success"><i class="fa fa-edit"></i> بروزرسانی</a>
                                        <a href="{{ url('panel/keyDelete', $item->id) }}" class="btn btn-danger"><i class="fa fa-times"></i> حذف</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- end userSeo --}}
    
    {{-- phase --}}
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">فازهای طراحی سایت</div>
            <div class="panel-body">
                <table class="table datatable-responsive table-togglable">
                    <thead>
                    <tr>
                        <th data-hide="phone">#</th>
                        <th data-toggle="true">نام فاز</th>
                        <th data-toggle="true">نام کارشناس</th>
                        <th data-hide="phone">نام شرکت</th>
                        <th data-hide="phone">پروژه</th>
                        <th data-hide="phone">روز باقی مانده</th>
                        <th data-hide="phone">تاریخ ایجاد</th>
                        <th data-hide="phone">وضعیت</th>
                        <th data-hide="phone">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(auth()->user()->phases()->get() as $data)
                        @if($data->project->project__type=='سایت')
                            {{-- <tr onclick="document.location = '{{url("panel/user_phase_show", $data->id)}}';"> --}}
                            <tr>
                                <td>{{$data->id}}</td>
                                <td>{{$data->phase__name}}</td>
                                <td>{{$data->user ? $data->user->name : 'نامشخص' }}</td>
                                <td>{{$data->project ? $data->project->user->company__name : ''}}</td>
                                <td>{{$data->project ? $data->project->project__name : ''}}</td>
                                @php
                                    $currentTime = Carbon\Carbon::now();
                                    $passedDays=$currentTime->diffInDays($data->created_at);
                                @endphp
                                <td style="direction: ltr">
                                    {{(int)$data->phase__day-$passedDays}} Day
                                </td>
                                <td>{{ my_jdate($data->created_at,'Y/m/d') }}</td>
                                <td class="p_done">
                                    <div class="meter"><span
                                                style="width: {{number_format($data->phase__percent, 2)}}%">{{number_format($data->phase__percent, 0)}}
                                            %</span></div>
                                </td>
                                <td>
                                    <a href="{{ url('panel/phase_edit',$data->id) }}">
                                        <i class="fa fa-pencil"></i>
                                        ویرایش
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">فازهای سئو</div>
            <div class="panel-body">
                <table class="table datatable-responsive table-togglable">
                    <thead>
                    <tr>
                        <th data-hide="phone">#</th>
                        <th data-toggle="true">نام فاز</th>
                        <th data-toggle="true">نام کارشناس</th>
                        <th data-hide="phone">نام شرکت</th>
                        <th data-hide="phone">پروژه</th>
                        <th data-hide="phone">روز باقی مانده</th>
                        <th data-hide="phone">تاریخ ایجاد</th>
                        <th data-hide="phone">وضعیت</th>
                        <th data-hide="phone">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(auth()->user()->phases()->get() as $data)
                        @if($data->project->project__type=='سئو' &&  $data->project->project__status != 1)
                            @if($data->project->project__status != 2)
                                {{-- <tr onclick="document.location = '{{url("panel/user_phase_show", $data->id)}}';"> --}}
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->phase__name}}</td>
                                    <td>{{$data->user ? $data->user->name : 'نامشخص' }}</td>
                                    <td>{{$data->project ? $data->project->user->company__name : ''}}</td>
                                    <td>{{$data->project ? $data->project->project__name : ''}}</td>
                                    @php
                                        $currentTime = Carbon\Carbon::now();
                                        $passedDays=$currentTime->diffInDays($data->created_at);
                                    @endphp
                                    <td style="direction: ltr">
                                        {{(int)$data->phase__day-$passedDays}} Day
                                    </td>
                                    <td>{{ my_jdate($data->created_at,'Y/m/d') }}</td>
                                    <td class="p_done">
                                        <div class="meter"><span
                                                    style="width: {{number_format($data->phase__percent, 2)}}%">{{number_format($data->phase__percent, 0)}}
                                                %</span></div>
                                    </td>
                                    <td>
                                        <a href="{{ url('panel/phase_edit',$data->id) }}">
                                            <i class="fa fa-pencil"></i>
                                            ویرایش
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">فازهای بی هدف</div>
            <div class="panel-body">
                <table class="table datatable-responsive table-togglable">
                    <thead>
                    <tr>
                        <th data-hide="phone">#</th>
                        <th data-toggle="true">نام فاز</th>
                        <th data-toggle="true">نام کارشناس</th>
                        <th data-hide="phone">نام شرکت</th>
                        <th data-hide="phone">پروژه</th>
                        <th data-hide="phone">روز باقی مانده</th>
                        <th data-hide="phone">تاریخ ایجاد</th>
                        <th data-hide="phone">وضعیت</th>
                        <th data-hide="phone">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(auth()->user()->phases()->where('package_id', 0)->get() as $data)
                        {{-- <tr onclick="document.location = '{{url("panel/user_phase_show", $data->id)}}';"> --}}
                        <tr>
                            <td>{{$data->id}}</td>
                            <td>{{$data->phase__name}}</td>
                            <td>{{$data->user ? $data->user->name : 'نامشخص' }}</td>
                            <td>{{$data->project ? $data->project->user->company__name : ''}}</td>
                            <td>{{$data->project ? $data->project->project__name : ''}}</td>
                            @php
                                $currentTime = Carbon\Carbon::now();
                                $passedDays=$currentTime->diffInDays($data->created_at);
                            @endphp
                            <td style="direction: ltr">
                                {{--@if(is_numeric($data->phase__day))--}}
                                {{(int)$data->phase__day-$passedDays}} Day
                                {{--@endif--}}
                            </td>
                            <td>{{ my_jdate($data->created_at,'Y/m/d') }}</td>
                            <td class="p_done">
                                <div class="meter"><span style="width: {{number_format($data->phase__percent, 2)}}%">{{number_format($data->phase__percent, 0)}}
                                        %</span></div>
                            </td>
                            <td>
                                <a href="{{ url('panel/phase_edit',$data->id) }}">
                                    <i class="fa fa-pencil"></i>
                                    ویرایش
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- end phase --}}

    {{-- ticket --}}
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">تیکت ها<span
                        class="pull-left">تعداد : {{ auth()->user()->tickets()->where('ticket__type','services')->count() }}</span></div>
            <div class="panel-body">
                <button class="btn btn-sm btn-danger pull-left" data-toggle="modal" data-target="#search"
                        style="margin-right: .5rem"><i class="fa fa-search"></i> جستجوی پیشرفته
                </button>
                <a href="{{ url('panel/ticket') }}" class="btn btn-sm btn-primary pull-left"><i
                            class="fa fa-search"></i> همه تیکت ها</a>
                <table class="table datatable-responsive table-togglable">
                    <thead>
                    <tr>
                        <th data-hide="phone">#</th>
                        <th data-toggle="true">نام شرکت</th>
                        <th data-toggle="true">بخش مربوطه</th>
                        <th data-hide="phone">عنوان تیکت</th>
                        <th data-hide="phone">اولویت</th>
                        <th data-hide="phone">بروزرسانی</th>
                        <th data-hide="phone">پاسخ دهنده</th>
                        <th data-hide="phone" style="text-align: center">ارجاع به</th>
                        <th data-hide="phone">وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(auth()->user()->tickets()->where('ticket__type','services')->get() as $data)
                        @if($data->referred_to==auth()->user()->id)
                            <tr style="background: {{ count($data->comments->where('confirmation',0)) ? 'antiquewhite' : '' }}"
                                {{-- onclick="window.open('{{url("panel/ticket", $data->id)}}', '_blank');" --}}
                                >
                                <td>{{$data->id}}</td>
                                <td>{{mb_substr($data->user->company__name,0,9, "utf-8")}}...</td>
                                <td>{{role_set($data->role__id)}}</td>
                                <td title="{{$data->ticket__title}}">{{mb_substr($data->ticket__title,0,25, "utf-8")}}
                                    ... @if($data->seen__id == 0) <span class="pulsating-circle"></span> @endif</td>
                                <td>
                                    @if($data->ticket__priority == 'high')
                                        <span class="table-status table-no-pay">ز</span>
                                    @elseif($data->ticket__priority == 'normal')
                                        <span class="table-status table-answered">م</span>
                                    @elseif($data->ticket__priority == 'low')
                                        <span class="table-status table-closed">ک</span>
                                    @endif
                                </td>
                                <td>
                                    @include('partials.ticket-jdate-update', $data)
                                    ({{$data->updated_at->format('H:i')}})
                                </td>
                                <td>@if($data->comments->count() != 0)@if(isset($data->comments->last()->user)){{$data->comments->last()->user->name}}@endif @else
                                        کاربر @endif</td>
                                <td>
                                    @if(!empty($data->referred_to) && is_numeric($data->referred_to))
                                        <?php
                                        $user = \App\Models\User::find($data->referred_to);
                                        echo $user->name;
                                        ?>
                                    @endif
                                </td>
                                <td>
                                    @if($data->ticket__status == "pending")
                                        <span style="font-size: 9px"
                                              class="table-status table-pending">در انتظار پاسخ</span>
                                    @elseif($data->ticket__status == "answered")
                                        <span style="font-size: 9px"
                                              class="table-status table-answered">پاسخ داده شده</span>
                                    @elseif($data->ticket__status == "waiting_answered")
                                        <span style="font-size: 9px"
                                              class="table-status table-answered">پاسخ داده شده</span>
                                  <span style="font-size: 9px"
                                        class="table-status table-pending">در انتظار پاسخ</span>
                                    @elseif($data->ticket__status == "closed")
                                        <span style="font-size: 9px" class="table-status table-closed">بسته شده</span>
                                    @elseif($data->ticket__status == "doing")
                                        <span style="font-size: 9px"
                                              class="table-status table-doing">در حال پیگیری</span>
                                    @elseif($data->ticket__status == "finished")
                                        <span style="font-size: 9px"
                                              class="table-status table-finished">به پایان رسیده</span>
                                    @elseif($data->ticket__status == "unpaid")
                                        <span style="font-size: 9px"
                                              class="table-status table-no-pay">پرداخت نشده</span>
                                    @elseif($data->ticket__status == "paid")
                                        <span style="font-size: 9px"
                                              class="table-status table-answered">پرداخت شده</span>
                                    @endif
                                </td>
                            </tr>
                        @elseif(auth()->user()->role_id==1 || auth()->user()->role_id==8)
                            <tr style="background: {{ count($data->comments->where('confirmation',0)) ? 'antiquewhite' : '' }}"
                                {{-- onclick="window.open('{{url("panel/ticket", $data->id)}}', '_blank');" --}}
                                >
                                <td>{{$data->id}}</td>
                                <td>{{mb_substr($data->user->company__name,0,9, "utf-8")}}...</td>
                                <td>{{role_set($data->role__id)}}</td>
                                <td title="{{$data->ticket__title}}">{{mb_substr($data->ticket__title,0,25, "utf-8")}}
                                    ... @if($data->seen__id == 0) <span class="pulsating-circle"></span> @endif</td>
                                <td>
                                    @if($data->ticket__priority == 'high')
                                        <span class="table-status table-no-pay">ز</span>
                                    @elseif($data->ticket__priority == 'normal')
                                        <span class="table-status table-answered">م</span>
                                    @elseif($data->ticket__priority == 'low')
                                        <span class="table-status table-closed">ک</span>
                                    @endif
                                </td>
                                <td>
                                    @include('partials.ticket-jdate-update', $data)
                                    ({{$data->updated_at->format('H:i')}})
                                </td>
                                <td>@if($data->comments->count() != 0)@if(isset($data->comments->last()->user)){{$data->comments->last()->user->name}}@endif @else
                                        کاربر @endif</td>
                                <td>
                                    @if(!empty($data->referred_to) && is_numeric($data->referred_to))
                                        <?php
                                        $user = \App\Models\User::find($data->referred_to);
                                        echo $user ? $user->name : '';
                                        ?>
                                    @endif
                                </td>
                                <td style="font-size: 9px;">
                                    @if($data->ticket__status == "pending")
                                        <span style="font-size: 9px"
                                              class="table-status table-pending">در انتظار پاسخ</span>
                                    @elseif($data->ticket__status == "answered")
                                        <span style="font-size: 9px"
                                              class="table-status table-answered">پاسخ داده شده</span>
                                    @elseif($data->ticket__status == "closed")
                                        <span style="font-size: 9px" class="table-status table-closed">بسته شده</span>
                                    @elseif($data->ticket__status == "doing")
                                        <span style="font-size: 9px"
                                              class="table-status table-doing">در حال پیگیری</span>
                                    @elseif($data->ticket__status == "finished")
                                        <span style="font-size: 9px"
                                              class="table-status table-finished">به پایان رسیده</span>
                                    @elseif($data->ticket__status == "unpaid")
                                        <span style="font-size: 9px"
                                              class="table-status table-no-pay">پرداخت نشده</span>
                                    @elseif($data->ticket__status == "paid")
                                        <span style="font-size: 9px"
                                              class="table-status table-answered">پرداخت شده</span>
                                    @endif
                                </td>
                            </tr>
                        @elseif(auth()->user()->role_id==3 || auth()->user()->role_id==6 || auth()->user()->role_id==7)
                            <tr 
                            {{-- onclick="window.open('{{url("panel/ticket", $data->id)}}', '_blank');" --}}
                                >
                                <td>{{$data->id}}</td>
                                <td>{{mb_substr($data->user->company__name,0,9, "utf-8")}}...</td>
                                <td>{{role_set($data->role__id)}}</td>
                                <td title="{{$data->ticket__title}}">{{mb_substr($data->ticket__title,0,25, "utf-8")}}
                                    ... @if($data->seen__id == 0) <span class="pulsating-circle"></span> @endif</td>
                                <td>
                                    @if($data->ticket__priority == 'high')
                                        <span class="table-status table-no-pay">ز</span>
                                    @elseif($data->ticket__priority == 'normal')
                                        <span class="table-status table-answered">م</span>
                                    @elseif($data->ticket__priority == 'low')
                                        <span class="table-status table-closed">ک</span>
                                    @endif
                                </td>
                                <td>
                                    @include('partials.ticket-jdate-update', $data)
                                    ({{$data->updated_at->format('H:i')}})
                                </td>
                                <td>@if($data->comments->count() != 0)@if(isset($data->comments->last()->user)){{$data->comments->last()->user->name}}@endif @else
                                        کاربر @endif</td>
                                <td>
                                    @if(!empty($data->referred_to) && is_numeric($data->referred_to))
                                        <?php
                                        $user = \App\Models\User::find($data->referred_to);
                                        echo $user->name;
                                        ?>
                                    @endif
                                </td>
                                <td style="font-size: 9px;">
                                    @if($data->ticket__status == "pending")
                                        <span style="font-size: 9px"
                                              class="table-status table-pending">در انتظار پاسخ</span>
                                    @elseif($data->ticket__status == "answered")
                                        <span style="font-size: 9px"
                                              class="table-status table-answered">پاسخ داده شده</span>
                                    @elseif($data->ticket__status == "closed")
                                        <span style="font-size: 9px" class="table-status table-closed">بسته شده</span>
                                    @elseif($data->ticket__status == "doing")
                                        <span style="font-size: 9px"
                                              class="table-status table-doing">در حال پیگیری</span>
                                    @elseif($data->ticket__status == "finished")
                                        <span style="font-size: 9px"
                                              class="table-status table-finished">به پایان رسیده</span>
                                    @elseif($data->ticket__status == "unpaid")
                                        <span style="font-size: 9px"
                                              class="table-status table-no-pay">پرداخت نشده</span>
                                    @elseif($data->ticket__status == "paid")
                                        <span style="font-size: 9px"
                                              class="table-status table-answered">پرداخت شده</span>
                                    @endif
                                </td>
                            </tr>
                        @elseif(auth()->user()->role_id==2 || auth()->user()->role_id==9)
                            @if(in_array($data->role__id,[2,9]))
                                <tr 
                                {{-- onclick="window.open('{{url("panel/ticket", $data->id)}}');" --}}
                                    >
                                    <td>{{$data->id}}</td>
                                    <td>{{mb_substr($data->user->company__name,0,9, "utf-8")}}...</td>
                                    <td>{{role_set($data->role__id)}}</td>
                                    <td title="{{$data->ticket__title}}">{{mb_substr($data->ticket__title,0,25, "utf-8")}}
                                        ... @if($data->seen__id == 0) <span class="pulsating-circle"></span> @endif</td>
                                    <td>
                                        @if($data->ticket__priority == 'high')
                                            <span class="table-status table-no-pay">ز</span>
                                        @elseif($data->ticket__priority == 'normal')
                                            <span class="table-status table-answered">م</span>
                                        @elseif($data->ticket__priority == 'low')
                                            <span class="table-status table-closed">ک</span>
                                        @endif
                                    </td>
                                    <td>
                                        @include('partials.ticket-jdate-update', $data)
                                        ({{$data->updated_at->format('H:i')}})
                                    </td>
                                    <td>@if($data->comments->count() != 0)@if(isset($data->comments->last()->user)){{$data->comments->last()->user->name}}@endif @else
                                            کاربر @endif</td>
                                    <td>
                                        @if(!empty($data->referred_to) && is_numeric($data->referred_to))
                                            <?php
                                            $user = \App\Models\User::find($data->referred_to);
                                            echo $user->name;
                                            ?>
                                        @endif
                                    </td>
                                    <td style="font-size: 9px;">
                                        @if($data->ticket__status == "pending")
                                            <span style="font-size: 9px" class="table-status table-pending">در انتظار پاسخ</span>
                                        @elseif($data->ticket__status == "answered")
                                            <span style="font-size: 9px" class="table-status table-answered">پاسخ داده شده</span>
                                        @elseif($data->ticket__status == "closed")
                                            <span style="font-size: 9px"
                                                  class="table-status table-closed">بسته شده</span>
                                        @elseif($data->ticket__status == "doing")
                                            <span style="font-size: 9px"
                                                  class="table-status table-doing">در حال پیگیری</span>
                                        @elseif($data->ticket__status == "finished")
                                            <span style="font-size: 9px" class="table-status table-finished">به پایان رسیده</span>
                                        @elseif($data->ticket__status == "unpaid")
                                            <span style="font-size: 9px"
                                                  class="table-status table-no-pay">پرداخت نشده</span>
                                        @elseif($data->ticket__status == "paid")
                                            <span style="font-size: 9px"
                                                  class="table-status table-answered">پرداخت شده</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    {{-- end ticket --}}

@endsection
