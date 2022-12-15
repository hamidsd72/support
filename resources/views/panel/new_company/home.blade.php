@extends('layouts.panel')
@section('content')

    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">همه مشتریان</div>
            <div class="panel-body">
                <a href="{{url('panel/new_company/create')}}" class="btn btn-labeled-left">ثبت شرکت جدید</a>
                <table class="table datatable-responsive table-togglable">
                    <thead>
                    <tr>
                        <th data-hide="phone">تاریخ بروزرسانی</th>
                        <th data-toggle="true">نام شرکت</th>
                        <th data-hide="phone">تلفن شرکت</th>
                        <th data-hide="phone">ایمیل شرکت</th>
                        <th data-hide="phone">نام مدیر شرکت</th>
                        <th data-hide="phone">درصد احتمالی</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $data)
                        <tr onclick="document.location = '{{route("new_company.show", $data->id)}}';">
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

{{--        <div class="panel panel-default">--}}
{{--            <div class="panel-sidebar panel-heading">عدم نیاز</div>--}}
{{--            <div class="panel-body">--}}
{{--                <a href="{{url('panel/new_company/create')}}" class="btn btn-labeled-left">ثبت شرکت جدید</a>--}}
{{--                <table class="table datatable-responsive table-togglable">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th data-hide="phone">تاریخ بروزرسانی</th>--}}
{{--                        <th data-toggle="true">نام شرکت</th>--}}
{{--                        <th data-hide="phone">تلفن شرکت</th>--}}
{{--                        <th data-hide="phone">ایمیل شرکت</th>--}}
{{--                        <th data-hide="phone">نام مدیر شرکت</th>--}}
{{--                        <th data-hide="phone">درصد احتمالی</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($items as $data2)--}}
{{--                        @if($data2->cat == 'عدم نیاز')--}}
{{--                            <tr onclick="document.location = '{{route("new_company.show", $data2->id)}}';">--}}
{{--                                <td>{{$data2->updated_at->format('Y/m/d H:i')}}</td>--}}
{{--                                <td>{{$data2->name}}</td>--}}
{{--                                <td>{{$data2->phone}}</td>--}}
{{--                                <td>{{$data2->email}}</td>--}}
{{--                                <td>{{$data2->manager}}</td>--}}
{{--                                <td>{{$data2->percent}}%</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="panel panel-default">--}}
{{--            <div class="panel-sidebar panel-heading">نیاز به مذاکره</div>--}}
{{--            <div class="panel-body">--}}
{{--                <a href="{{url('panel/new_company/create')}}" class="btn btn-labeled-left">ثبت شرکت جدید</a>--}}
{{--                <table class="table datatable-responsive table-togglable">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th data-hide="phone">تاریخ بروزرسانی</th>--}}
{{--                        <th data-toggle="true">نام شرکت</th>--}}
{{--                        <th data-hide="phone">تلفن شرکت</th>--}}
{{--                        <th data-hide="phone">ایمیل شرکت</th>--}}
{{--                        <th data-hide="phone">نام مدیر شرکت</th>--}}
{{--                        <th data-hide="phone">درصد احتمالی</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($items as $data3)--}}
{{--                        @if($data3->cat == 'نیاز به مذاکره')--}}
{{--                            <tr onclick="document.location = '{{route("new_company.show", $data3->id)}}';">--}}
{{--                                <td>{{$data3->updated_at->format('Y/m/d H:i')}}</td>--}}
{{--                                <td>{{$data3->name}}</td>--}}
{{--                                <td>{{$data3->phone}}</td>--}}
{{--                                <td>{{$data3->email}}</td>--}}
{{--                                <td>{{$data3->manager}}</td>--}}
{{--                                <td>{{$data3->percent}}%</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="panel panel-default">--}}
{{--            <div class="panel-sidebar panel-heading">بعدا تماس بگیرید</div>--}}
{{--            <div class="panel-body">--}}
{{--                <a href="{{url('panel/new_company/create')}}" class="btn btn-labeled-left">ثبت شرکت جدید</a>--}}
{{--                <table class="table datatable-responsive table-togglable">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th data-hide="phone">تاریخ بروزرسانی</th>--}}
{{--                        <th data-toggle="true">نام شرکت</th>--}}
{{--                        <th data-hide="phone">تلفن شرکت</th>--}}
{{--                        <th data-hide="phone">ایمیل شرکت</th>--}}
{{--                        <th data-hide="phone">نام مدیر شرکت</th>--}}
{{--                        <th data-hide="phone">درصد احتمالی</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($items as $data4)--}}
{{--                        @if($data4->cat == 'بعدا تماس بگیرید')--}}
{{--                            <tr onclick="document.location = '{{route("new_company.show", $data4->id)}}';">--}}
{{--                                <td>{{$data4->updated_at->format('Y/m/d H:i')}}</td>--}}
{{--                                <td>{{$data4->name}}</td>--}}
{{--                                <td>{{$data4->phone}}</td>--}}
{{--                                <td>{{$data4->email}}</td>--}}
{{--                                <td>{{$data4->manager}}</td>--}}
{{--                                <td>{{$data4->percent}}%</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>

@endsection
