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
                <form action="{{route('todo-list-check.store')}}" method="POST" enctype="multipart/form-data" class="container-fluid">
                    @csrf
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">عنوان *</label>
                            <input type="text" name="title" class="form-control" value="{{old('title')}}" required>
                        </div>
                    </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">وضعیت *</label>
                                <select class="form-control select2" name="status" required>
                                    <option value="active" {{old('status')=='active'?'selected':''}}>فعال</option>
                                    <option value="pending" {{old('status')=='pending'?'selected':''}}>غیر فعال</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cat_id">دسته بندی *</label>
                                <select class="form-control select2" name="cat_id" required>
                                    @foreach($cats as $cat)
                                        <option value="{{$cat->id}}" {{old('cat_id')==$cat->id?'selected':''}}>{{$cat->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tab_id">تب *</label>
                                <select class="form-control select2" name="tab_id" required>
                                    @foreach($tabs as $tab)
                                        <option value="{{$tab->id}}" {{old('tab_id')==$tab->id?'selected':''}}>{{$tab->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="col-md-12 text-left">
                        <hr/>
                        <a href="{{$url_back}}" class="btn btn-danger float-right">برگشت</a>
                        <button type="submit" class="btn btn-primary float-left">افزودن</button>
                    </div>
                </div>
                </form>

            </div>
        </div>
    </div>

    @push('script')

    @endpush

@endsection
