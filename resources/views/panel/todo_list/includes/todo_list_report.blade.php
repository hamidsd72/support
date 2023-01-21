<button data-toggle="modal" data-target="#ReportModal" class="btn btn-labeled-left mx-2 bg-info">
  <i class="fa fa-comment"></i>
  گزارش
</button>

<!-- Modal -->
<div class="modal fade" id="ReportModal" tabindex="-1" role="dialog" aria-labelledby="ReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{route('todo.list.report',[$item->id,'multi'])}}" method="post" class="modal-content" enctype="multipart/form-data">
      {{csrf_field()}}
      <div class="modal-header">
        <h5 class="modal-title" id="ReportModalLabel">گزارش</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="form_send" class="container-fluid">
          <div class="row">
            @if(auth()->id()==$item->user_group_id && $item->status=='doing')
              <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
              <div class="form-group{{ $errors->has('time') ? ' has-error' : '' }}">

                <label for="time" class="form-label">زمان صرف شده(دقیقه) :</label>
                <input type="number" name="time" id="time" value="{{old('time',0)}}" class="form-control">
                @if ($errors->has('time'))
                  <span class="help-block"><strong>{{$errors->first('time')}}</strong></span>
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