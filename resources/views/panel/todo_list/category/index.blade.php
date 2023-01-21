@extends('layouts.panel')
@section('styles_meta')
    <style>
        .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12
        {
            float: right;
        }
    </style>
@endsection
@section('content')
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">{{$title}}</div>
            <div class="panel-body">
                <a href="{{route('todo-list-category.create')}}" class="btn btn-labeled-left">افزودن</a>
                <table class="table datatable-responsive20 table-togglable">
                    <thead>
                    <tr>
                        <th data-hide="phone">#</th>
                        <th data-hide="phone">عنوان</th>
                        <th data-hide="phone">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $key=>$data)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$data->title}}</td>
                            <td>
                                <div class="form-group d-flex">
                                    <a href="{{route('todo.list.cc.user.list', $data->id)}}" class="text-success mr-2" title="کاربران cc"><i class="nav-icon i-Checked-User"></i></a>
                                    <a href="{{route('todo-list-category.edit', $data->id)}}" class="text-info" title="ویرایش"><i class="nav-icon i-File-Edit"></i></a>
                                    <form action="{{route('todo-list-category.destroy',$data->id)}}" method="POST">
                                        {{method_field('DELETE')}}
                                        {{csrf_field()}}
{{--                                        <button class="text-danger mr-2" title="حذف"--}}
{{--                                                onclick="return confirm('برای حذف مطمئن هستید؟')">--}}
{{--                                            <i class="nav-icon i-File-Trash"></i>--}}
{{--                                        </button>--}}
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
