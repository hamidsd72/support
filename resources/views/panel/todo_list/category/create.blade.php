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
                <form action="{{route('todo-list-category.store')}}" method="POST" enctype="multipart/form-data" class="container-fluid">
                    @csrf
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">عنوان *</label>
                            <input type="text" name="title" class="form-control" value="{{old('title')}}" required>
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
