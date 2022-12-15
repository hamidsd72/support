@extends('layouts.panel')
@section('styles')
    <style>
        .table td, .table th{
            text-align: center !important;
        }
    </style>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">{{$title}}</div>
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
                    @foreach($items as $key=>$rows)
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
    <div id="companies" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        @foreach($companies as $company)
                            @if($company->user)
                                <div class="col-md-3 text-center" style="margin-bottom: 10px;">
                                    <a class="btn btn-info btn-sm" style="display: block;" href="{{ route('keyList',$company->user) }}">{{ $company->user->company__name }}</a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-success" onclick="document.getElementById('companies-form').submit();">ویرایش</button>
                </div>
            </div>
        </div>
    </div>

@endsection
