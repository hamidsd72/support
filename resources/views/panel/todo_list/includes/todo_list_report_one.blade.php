<button data-toggle="modal" data-target="#ReportOneModal" class="btn btn-labeled-left mx-2 bg-info">
  <i class="fa fa-comment"></i>
  گزارش
</button>

<!-- Modal -->
<div class="modal fade" id="ReportOneModal" tabindex="-1" role="dialog" aria-labelledby="ReportOneModalLabel"
     aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{route('todo.list.report',[$item->id,'one'])}}" method="post" class="modal-content"
          enctype="multipart/form-data">
      {{csrf_field()}}
      <div class="modal-header">
        <h5 class="modal-title" id="ReportOneModalLabel">گزارش</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="form_send" class="container-fluid">
          <div class="row">
            @if(auth()->id()==$item->user_id && $item->status=='doing')
              <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
                <div class="form-group{{ $errors->has('time') ? ' has-error' : '' }}">

                  <label for="time" class="form-label">زمان صرف شده(دقیقه) :</label>
                  <input type="number" name="time" id="time" value="{{old('time',0)}}" class="form-control">
                  @if ($errors->has('time'))
                    <span class="help-block"><strong>{{$errors->first('time')}}</strong></span>
                  @endif

                </div>
              </div>
              <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
                <div class="form-group{{ $errors->has('percent') ? ' has-error' : '' }}">

                  <label for="percent" class="form-label">درصد پیشرفت :</label>
                  <input type="number" name="percent" id="percent" value="{{old('percent',$item->percent)}}"
                         class="form-control">
                  @if ($errors->has('percent'))
                    <span class="help-block"><strong>{{$errors->first('percent')}}</strong></span>
                  @endif

                </div>
              </div>
              <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">

                  <label for="status" class="form-label">وضعیت :</label>
                  <select class="form-control not_select" name="status" id="status">
                    <option value="">بدون تغییر({{$item->status_set($item->status.'1')}})</option>
                    @if($item->status!='doing')
                      <option value="doing" {{old('status')=='doing'?'selected':''}}>درحال اجرا</option>
                    @endif
                    @if($item->status!='stop')
                      <option value="stop" {{old('status')=='stop'?'selected':''}}>متوقف</option>
                    @endif
                    @if($item->status!='end')
                      <option value="end" {{old('status')=='end'?'selected':''}}>اتمام</option>
                    @endif
                  </select>
                  @if ($errors->has('status'))
                    <span class="help-block"><strong>{{$errors->first('status')}}</strong></span>
                  @endif

                </div>
              </div>
              @if($item->type_reminder=='date' && $item->reminder <= date('Y-m-d'))
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12  px-1">
                  <div class="form-group{{ $errors->has('reminder') ? ' has-error' : '' }}">
                    <label for="reminder" class="form-label">تاریخ یادآوری جدید :</label>
                    <input type="text" name="reminder" id="reminder" value="" class="form-control datepicker123"
                           readonly>
                    @if ($errors->has('reminder'))
                      <span class="help-block"><strong>{{$errors->first('reminder')}}</strong></span>
                    @endif
                  </div>
                </div>
              @endif
            @endif
            <div class="col-xs-12">
              <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                <label for="text" class="form-label">توضیحات :</label>
                <textarea id="text" class="form-control" name="text"
                          rows="10" required>{{old('text')}}</textarea>
                @if ($errors->has('text'))
                  <span class="help-block"><strong>{{$errors->first('text')}}</strong></span>
                @endif
              </div>
            </div>

            <div class="col-xs-12">
              <div class="form-group{{ $errors->has('docs') ? ' has-error' : '' }}">
                <label for="docs" class="form-label">پیوست :</label>
                <p class="ticket__type">
                  پسوندهای مجاز: .jpg, .gif, .jpeg, .png, .txt, .pdf,
                  .zip, .rar, .mp4
                </p>
                <input id="docs" type="file" name="docs[]"
                       class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.txt,.rar,.zip,.mp4" multiple/>
                @if ($errors->has('docs'))
                  <span class="help-block">
                        <strong>{{$errors->first('docs')}}</strong>
                      </span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary float-left">ارسال فرم</button>
      </div>
    </form>
  </div>
</div>