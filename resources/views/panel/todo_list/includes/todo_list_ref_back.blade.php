@if(count($item->group_ref_user_back($item->user_id,$item->user_group_id)))
  <button data-toggle="modal" data-target="#BackRefModal" class="btn btn-labeled-left mx-2 bg-danger">
    <i class="fa fa-close"></i>
    بازگشت ارجاع
  </button>

  <!-- Modal -->
  <div class="modal fade" id="BackRefModal" role="dialog" aria-labelledby="BackRefModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <form action="{{route('todo.list.report',[$item->id,'multi_back'])}}" method="post" class="modal-content" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-header">
          <h5 class="modal-title" id="BackRefModalLabel">بازگشت ارجاع</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="form_send" class="container-fluid">
            <div class="row">
              @if($item->type_ref=='multi' && count($item->group_ref_user_back($item->user_id,$item->user_group_id)))
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
                  <div class="form-group{{ $errors->has('back_ref') ? ' has-error' : '' }}">
                      <label for="back_ref" class="form-label">بازگشت ارجاع به :</label>
                      <select class="form-control not_select" name="back_ref" id="back_ref">

                        @foreach($item->group_ref_user_back($item->user_id,$item->user_group_id) as $back)
                          <option value="{{$back->user_id}}">{{$back->user?$back->user->name:$back->user_id}}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('back_ref'))
                        <span class="help-block"><strong>{{$errors->first('back_ref')}}</strong></span>
                      @endif
                  </div>
                </div>
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
@endif