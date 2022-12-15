@extends('layouts.panel')
@if(Auth()->user()->role_id==9 or Auth()->user()->role_id==2)
@section('styles_meta')
    <meta http-equiv="refresh" content="300">
@endsection
@endif
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="search">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="search"><i class="fa fa-search"></i> جستجوی پیشرفته</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ url('panel/ticket-search') }}" method="POST" id="fsearch">
                        <div class="form-group">
                            <label for="name">انتخاب شرکت</label>
                            <select name="name" id="name" class="form-control select">
                                <option>انتخاب کنید</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company__name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="number"> یا - شماره تیکت</label>
                            <input type="number" name="number" id="number" class="form-control"
                                   value="{{ old('number') }}"/>
                            {{ csrf_field() }}
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بیخیال نمیخوام</button>
                    <button type="button" class="btn btn-primary" onclick="$('#fsearch').submit();">بگرد ببینم چه
                        خبره!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">{{$title}}<span
                        class="pull-left">تعداد : {{ $data->count() }}</span></div>
            <div class="panel-body">
                <button class="btn btn-sm btn-danger pull-left" data-toggle="modal" data-target="#search"
                        style="margin-right: .5rem"><i class="fa fa-search"></i> جستجوی پیشرفته
                </button>
                <a href="{{ url('panel/ticket') }}" class="btn btn-sm btn-primary pull-left"><i
                            class="fa fa-search"></i> همه تیکت ها</a>
                <table class="table datatable-responsive table-togglable">
                    <thead>
                    <tr>
                        @if(isset($invoices))
                            <th data-hide="phone">#</th>
                            <th data-toggle="true">نام شرکت</th>
                            <th data-toggle="true">بخش مربوطه</th>
                            <th data-hide="phone">عنوان تیکت</th>
                            <th data-hide="phone">اولویت</th>
                            <th data-hide="phone">بروزرسانی</th>
                            <th data-hide="phone">پاسخ دهنده</th>
                            <th data-hide="phone" style="text-align: center">ارجاع به</th>
                            <th data-hide="phone">وضعیت</th>
                        @else
                            <th data-hide="phone">#</th>
                            <th data-toggle="true">عنوان</th>
                            <th data-hide="phone">شرکت</th>
                            <th data-hide="phone">اولویت</th>
                            <th data-hide="phone">تاریخ ثبت</th>
                            <th data-hide="phone">وضعیت</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $data)
                        @if($data->referred_to==auth()->user()->id)
                            <tr style="background: {{ count($data->comments->where('confirmation',0)) ? 'antiquewhite' : '' }}"
                                onclick="window.open('{{url("panel/ticket", $data->id)}}', '_blank');">
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
                                onclick="window.open('{{url("panel/ticket", $data->id)}}', '_blank');">
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
                            <tr onclick="window.open('{{url("panel/ticket", $data->id)}}', '_blank');">
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
                                <tr onclick="window.open('{{url("panel/ticket", $data->id)}}');">
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


@endsection
