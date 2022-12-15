@extends('layouts.panel')
@section('content')

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">{{$title}}</div>
            <div class="panel-body">
{{--                <div class="col-12">--}}
{{--                    <form action="{{url('panel/company_excel_import')}}" method="post" enctype="multipart/form-data">--}}
{{--                        {{csrf_field()}}--}}
{{--                        <input type="file" name="file">--}}
{{--                        <button type="submit" class="btn btn-info">ارسال فایل</button>--}}
{{--                    </form>--}}
{{--                </div>--}}
                <table class="table datatable-responsive table-togglable">
                    <thead>
                    <tr>
                        <th data-hide="phone">#</th>
                        <th data-toggle="true">نام شرکت</th>
                        <th data-hide="phone">تلفن شرکت</th>
                        <th data-hide="phone">ایمیل شرکت</th>
                        <th data-hide="phone">نام نماینده شرکت</th>
                        <th data-hide="phone">تلفن نماینده شرکت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $data)
                        <tr onclick="document.location = '{{route("company.show", $data->id)}}';">
                            <td>{{$data->id}}</td>
                            <td>{{$data->company__name}}</td>
                            <td>{{$data->company__phone}}</td>
                            <td>{{$data->email}}</td>
                            <td>{{$data->company__representative_name}}</td>
                            <td>{{$data->company__representative_phone}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
