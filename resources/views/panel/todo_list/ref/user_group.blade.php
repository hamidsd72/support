@extends('layouts.panel')
@section('styles_meta')
  <style>
      .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12
      {
          float: right;
      }
      .box-sh
      {
          box-shadow: 0 0 2px 0 #66666690;
          border-radius: 5px;
          padding: 20px;
          margin-bottom: 20px;
      }
  </style>
@endsection
@section('content')
  <div class="col-md-12">

    <div class="panel panel-default">
      <div class="panel-sidebar panel-heading">{{$title}}</div>
      <div class="panel-body">
        <div class="box-sh container-fluid">
          <form class="row" action="{{route('todo.list.ref.user.store',$ref->id)}}" method="post">
            @csrf
            <div class="col-md-6 mb-3">
              <div class="form-group">
                <label for="user_id">کاربر *</label>
                <select class="form-control select2" name="user_id" required>
                  <option value="">انتخاب کنید</option>
                  @foreach($users as $user)
                    <option value="{{$user->id}}" {{old('user_id') == $user->id?'selected':''}}>{{$user->name}}{{ $user->role?'('.$user->role->description.')':'' }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 pt-5">
              <button type="submit" class="btn btn-info mx-2">افزودن</button>
              <a href="{{$url_back}}" type="submit" class="btn btn-danger mx-2">بازگشت</a>
            </div>
          </form>
        </div>
        <table class="table table-togglable">
          <thead>
          <tr>
            <th data-hide="phone">کاربر</th>
            <th data-toggle="true">مرتب</th>
            <th data-toggle="true">ایجاد کننده</th>
            <th data-hide="phone">عملیات</th>
          </tr>
          </thead>
          <tbody>
          @foreach($items as $data)
            <tr>
              <td>{{$data->user?$data->user->name:'__'}}</td>
              <td>
                  <form action="{{route('todo.list.ref.user.sort',$data->id)}}" method="post">
                    @csrf
                    <input type="number" class="text-center form-control" style="width: 120px" name="sort" value="{{$data->sort}}" onchange="return this.form.submit()">
                  </form>
              </td>
              <td>{{$data->user_create?$data->user_create->name:'__'}}</td>

              <td>
                <div class="form-group d-flex">
                  <form action="{{route('todo.list.ref.user.delete',$data->id)}}" method="POST">
                    {{method_field('DELETE')}}
                    {{csrf_field()}}
                    <button class="text-danger mr-2" title="حذف"
                            onclick="return confirm('برای حذف مطمئن هستید؟')">
                      <i class="nav-icon i-File-Trash"></i>
                    </button>
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
