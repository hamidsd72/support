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
                <form action="{{route('todo-list.update',$item->id)}}" method="POST" enctype="multipart/form-data" class="container-fluid">
                    {{method_field('PATCH')}}
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$item->id}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cat_id">دسته بندی *</label>
                                <select class="form-control select2" name="cat_id" data-placeholder="انتخاب کنید" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($cats as $cat)
                                        <option value="{{$cat->id}}" {{old('cat_id',$item->cat_id)==$cat->id?'selected':''}}>{{$cat->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">ارجاع به *</label>
                                <select class="form-control select2" name="user_id" data-placeholder="انتخاب کنید" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($groups as $group)
                                        <option value="{{'g_'.$group->id}}" {{$item->type_ref=='multi' && old('user_id','g_'.$item->user_id)=='g_'.$group->id?'selected':''}}>{{$group->title.'(گروه)'}}</option>
                                    @endforeach
                                    @foreach($users as $user)
                                        <option value="{{'u_'.$user->id}}" {{$item->type_ref=='one' && old('user_id','u_'.$item->user_id)=='u_'.$user->id?'selected':''}}>{{$user->name}}{{ $user->role?'('.$user->role->description.')':'' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">عنوان *</label>
                                <input type="text" name="title" class="form-control" value="{{old('title',$item->title)}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority">اولویت *</label>
                                <select class="form-control select2" name="priority" required>
                                    <option value="low" {{old('priority',$item->priority)=='low'?'selected':''}}>کم</option>
                                    <option value="medium" {{old('priority',$item->priority)=='medium'?'selected':''}}>متوسط</option>
                                    <option value="top" {{old('priority',$item->priority)=='top'?'selected':''}}>زیاد</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_id">شرکت *</label>
                                <select class="form-control select2 company_select" onchange="company_select()" name="company_id" data-placeholder="انتخاب کنید" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($companys as $company)
                                        <option value="{{$company->id}}" {{old('company_id',$item->company_id)==$company->id?'selected':''}}>{{$company->company__name}}</option>
                                    @endforeach
                                    <option value="0">سایر</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contract_id">قرارداد *</label>
                                <select class="form-control contract_select not_select" name="contract_id" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($contracts as $contract)
                                        <option value="{{$contract->id}}" class="contract_all contract_{{$contract->user__id}} {{old('company_id',$item->company_id)==$contract->user__id?'':'d-none'}}" {{old('contract_id',$item->contract_id)==$contract->id?'selected':''}} >{{$contract->type}} ({{$contract->active==1?'فعال':'غیرفعال'}})</option>
                                    @endforeach
                                    <option value="0">سایر</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type_reminder">نوع یادآوری *</label>
                                <select class="form-control select2 type_reminder-select" name="type_reminder" required>
                                    <option value="date" {{old('type_reminder',$item->type_reminder)=='date'?'selected':''}}>تاریخ ثابت</option>
                                    <option value="week" {{old('type_reminder',$item->type_reminder)=='week'?'selected':''}}>هر هفته</option>
{{--                                    <option value="2week" {{old('type_reminder',$item->type_reminder)=='2week'?'selected':''}}>هر 2 هفته</option>--}}
{{--                                    <option value="month" {{old('type_reminder',$item->type_reminder)=='month'?'selected':''}}>هر ماه</option>--}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group reminder-div-input {{old('type_reminder',$item->type_reminder)!='date'?'d-none':''}}">
                                <label for="reminder_date">زمان یادآوری *</label>
                                <input type="text" name="reminder_date" class="form-control reminder-input datepicker1" value="{{old('reminder_date',$item->reminder_fa)}}" required readonly>
                            </div>
                            <div class="form-group reminder-div-select {{old('type_reminder',$item->type_reminder)!='date'?'':'d-none'}}">
                                <label for="reminder">زمان یادآوری *</label>
                                <select class="form-control select2 reminder-select" name="reminder[]" multiple>
                                    <option value="6" {{old('type_reminder',$item->type_reminder)!='date' && in_array(6,old('reminder',json_decode($item->reminder)))?'selected':''}}>شنبه</option>
                                    <option value="0" {{old('type_reminder',$item->type_reminder)!='date' && in_array(0,old('reminder',json_decode($item->reminder)))?'selected':''}}>یکشنبه</option>
                                    <option value="1" {{old('type_reminder',$item->type_reminder)!='date' && in_array(1,old('reminder',json_decode($item->reminder)))?'selected':''}}>دوشنبه</option>
                                    <option value="2" {{old('type_reminder',$item->type_reminder)!='date' && in_array(2,old('reminder',json_decode($item->reminder)))?'selected':''}}>سه شنبه</option>
                                    <option value="3" {{old('type_reminder',$item->type_reminder)!='date' && in_array(3,old('reminder',json_decode($item->reminder)))?'selected':''}}>چهار شنبه</option>
                                    <option value="4" {{old('type_reminder',$item->type_reminder)!='date' && in_array(4,old('reminder',json_decode($item->reminder)))?'selected':''}}>پنج شنبه</option>
                                    <option value="5" {{old('type_reminder',$item->type_reminder)!='date' && in_array(5,old('reminder',json_decode($item->reminder)))?'selected':''}}>جمعه</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">زمان شروع فعالیت</label>
                                <input type="text" name="start_date" class="form-control datepicker" value="{{old('start_date',$item->start_date_fa)}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">زمان پایان فعالیت</label>
                                <input type="text" name="end_date" class="form-control datepicker" value="{{old('end_date',$item->end_date_fa)}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="text">توضیح *</label>
                                <textarea class="form-control" name="text" rows="3">{{old('text',$item->text)}}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12 text-left">
                            <hr/>
                            <a href="{{$url_back}}" class="btn btn-danger float-right">برگشت</a>
                            <button type="submit" class="btn btn-primary float-left">ویرایش</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('script')
        <script>
            function company_select()
            {
                var c_id=$('.company_select').val()
                $('.contract_all').addClass('d-none')
                $('.contract_'+c_id).removeClass('d-none')
                $('.contract_select').val('')
            }
            $('.type_reminder-select').on('change',function (){
                var val=$(this).val()
                if(val=='date')
                {
                    $('.reminder-div-input').removeClass('d-none')
                    $('.reminder-input').attr('required',true)
                    $('.reminder-div-select').addClass('d-none')
                    $('.reminder-select').attr('required',false)
                }
                else
                {
                    $('.reminder-div-input').addClass('d-none')
                    $('.reminder-input').attr('required',false)
                    $('.reminder-div-select').removeClass('d-none')
                    $('.reminder-select').attr('required',true)
                }
            })
            $(".datepicker1").datepicker({
                dateFormat: 'yy,mm,dd',
                changeMonth: true,
                changeYear: true,
                minDate: new Date(new Date().getFullYear(), new Date().getMonth() , new Date().getDate()+1)
            });
        </script>
    @endpush

@endsection
