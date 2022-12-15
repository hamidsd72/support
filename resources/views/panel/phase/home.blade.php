@extends('layouts.panel')
@section('content')

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
                    @foreach($phases as $data)
                        @if($data->project->project__type=='سایت')
                            <tr onclick="document.location = '{{url("panel/user_phase_show", $data->id)}}';">
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
                    @foreach($phases as $data)
                        @if($data->project->project__type=='سئو' &&  $data->project->project__status != 1)
                            @if($data->project->project__status != 2)
                                <tr onclick="document.location = '{{url("panel/user_phase_show", $data->id)}}';">
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
            <div class="panel-sidebar panel-heading">فازها بر اساس نام شرکت</div>
            <div class="panel-body">
                <table class="table datatable-responsive table-togglable">
                    <thead>
                    <tr>
                        <th data-toggle="true">نام شرکت</th>
                        <th data-toggle="true">مدیر شرکت</th>
                        <th data-toggle="true">تعداد پروژه</th>
                        {{--<th data-toggle="true">میانگین انجام شده ها</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        @php $project_count=count($user->projects) @endphp
                        @if($project_count>0)
                            <tr>
                                <td>{{$user->company__name}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$project_count}}</td>
                                {{--<td class="p_done">--}}
                                {{--<div class="meter"><span style="width: {{number_format($data->phase__percent, 2)}}%">{{number_format($data->phase__percent, 0)}}%</span></div>--}}
                                {{--</td>--}}
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
                    @foreach($noProjectPhases as $data)
                        <tr onclick="document.location = '{{url("panel/user_phase_show", $data->id)}}';">
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


@endsection
