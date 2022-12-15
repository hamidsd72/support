@extends('layouts.panel')
@section('styles')
    <style>
        .ml-auto, .mx-auto {
            margin-left: auto !important;
        }
        .mr-auto, .mx-auto {
            margin-right: auto !important;
        }
        #accordion{
            border-radius: 3px;
            overflow: hidden;
        }
        #accordion div.card-link{
            color: #fff;
            display: inline-block;
            line-height: 30px;
            cursor: pointer;
            /*width: 100%;*/
        }
        .panel-body .card-header{
            background: #34495e;
            padding: 10px;
            border-right: 4px solid #dfc446;
        }
        .panel-body .card-header i{
            margin-left: 8px;
        }
        .panel-body .card-body{
            color:#444;
            border-right: 4px solid
            #df6f28;
            padding: 20px;
        }
        .row-phase{
            cursor: pointer;
            margin: 0px;
            padding: 5px 3px;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            -ms-transition: all 0.3s;
            -o-transition: all 0.3s;
            transition: all 0.3s;
        }
        .row-phase:hover{
            background: #2dcc70;
        }
        .btn-green{
            background: #2dcc70 !important;
            color: #fff !important;
            display: inline-block !important;
        }
        .ml-2{
            margin-left: 5px;
        }
        .mr-2{
            margin-right: 5px;
        }
        .mx-2{
            margin: 5px;
        }
        .table > thead > tr > th{
            font-size: 12px;
        }
        .table > thead > tr > th,.table > tbody > tr > td{
            text-align: center;
            font-weight: bold;
        }
        .selectProject-form{
            display: initial;
            float: left;
        }
    </style>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">{{$title}}</div>
            <div class="col-md-12 d-flex" style="padding: 10px 15px;">
                <a href="{{url('panel/work-create')}}" class="btn btn-green pull-left btn-sm">ایجاد کار</a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div id="accordion" class="">
                            @foreach($items as $key=>$item)
                                @php
                                    $workTimesheet_doing=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('work',$item->id,'doing');
                                    $workTimesheet_finished=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('work',$item->id,'finished');
                                    $workTimesheet_paused=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('work',$item->id,'paused');
                                @endphp
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-link" data-toggle="collapse" href="#collapse{{$key}}">
                                            <i class="far fa-clone"></i>
                                            {{ $item->title }}
                                            <a href="javascript:void(0)" style="margin-right: 10px;background: #fff;border: 0;color: #444 !important;" class="table-status startWorkBtn table-doing">
                                                ارجاع دهنده :
                                                {{ $item->referrer?$item->referrer->name:'نامشخص' }}
                                            </a>
                                            <a href="javascript:void(0)" style="margin-right: 10px;background: #fff;border: 0;color: #444 !important;" class="table-status startWorkBtn table-doing">
                                                نام شرکت :
                                                {{ $item->company?$item->company->company__name:'نامشخص' }}
                                            </a>
                                        </div>
                                        {{--@if(auth()->user()->role_id==1)--}}
                                            <a href="{{url('panel/work-edit',$item->id)}}" style="background-image: linear-gradient(230deg, #ff8600, #984c00);" class="table-status table-doing pull-left"><i
                                                        class="far fa-edit" style="margin-left: 5px;"></i>ویرایش کار</a>
                                        {{--@endif--}}
                                        @if($workTimesheet_doing)
                                            <form action="{{url('panel/work-stop')}}" method="post" class="selectProject-form">
                                                <button type="submit" style="margin-right: 10px;background-image: linear-gradient(230deg, rgb(232, 58, 58), rgb(234, 75, 75));" class="table-status startWorkBtn table-doing"><i
                                                            class="far fa-stop-circle" style="margin-left: 5px;"></i>اتمام کار
                                                </button>
                                                <input type="hidden" value="{{$item->id}}" name="id">
                                                {{ csrf_field() }}
                                            </form>
                                            <a href="javascript:void(0)" style="background-image: linear-gradient(230deg, #759bff, #843cf6);" class="table-status table-doing pull-left"><i
                                                        class="far fa-hourglass" style="margin-left: 5px;"></i>در حال انجام </a>
                                        @endif
                                        @if($workTimesheet_finished)
                                            <form action="{{url('panel/timesheet-store')}}" method="post" class="selectProject-form">
                                                <button type="submit" style="background-image: linear-gradient(230deg, #759bff, #843cf6);" class="table-status table-doing startWorkBtn"><i
                                                            class="far fa-play-circle" style="margin-left: 5px;"></i>از سرگیری مجدد</button>
                                                <input type="hidden" value="{{$item->id}}" name="type_id">
                                                <input type="hidden" value="work" name="type">
                                                {{ csrf_field() }}
                                            </form>
                                        @endif

                                        @if(!$workTimesheet_doing && !$workTimesheet_finished)
                                            <form action="{{url('panel/timesheet-store')}}" method="post" class="selectProject-form">
                                                <button type="submit" style="background-image: linear-gradient(230deg, #759bff, #843cf6);" class="table-status startWorkBtn table-doing"><i
                                                            class="far fa-play-circle" style="margin-left: 5px;"></i>
                                                    {{ $workTimesheet_paused?'ادامه کار':'شروع کن' }}
                                                </button>
                                                <input type="hidden" value="{{$item->id}}" name="type_id">
                                                <input type="hidden" value="work" name="type">
                                                {{ csrf_field() }}
                                            </form>
                                        @endif
                                        {{--<a href="{{ url('panel/phase_create',$item->id) }}" class="btn btn-green pull-left btn-sm">ثبت فاز</a>--}}
                                        {{--<a href="javascript:void(0)" data-id="{{$item->id}}" data-title="{{ $item->title }}" class="btn btn-green pull-left btn-sm edit-package ml-2">ویرایش پکیج</a>--}}
                                        {{--<a href="{{ url('panel/package-destroy',$item->id) }}" data-id="{{$item->id}}" data-title="{{ $item->title }}" class="btn btn-danger pull-left btn-sm edit-package ml-2">حذف پکیج</a>--}}
                                    </div>
                                    <div id="collapse{{$key}}" class="collapse in" data-parent="#accordion">
                                        <div class="card-body">
                                            {!! nl2br($item->description) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')

@endsection