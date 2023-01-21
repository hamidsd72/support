<button data-toggle="modal" data-target="#ReportKeyModal" class="btn btn-labeled-left mx-2 bg-warning">
  <i class="fa fa-keyboard-o"></i>
  گزارش(کلمات کلیدی)
</button>

<!-- Modal -->
<div class="modal fade" id="ReportKeyModal" tabindex="-1" role="dialog" aria-labelledby="ReportKeyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{route('todo.list.keyword.list',$item->id)}}" method="post" class="modal-content">
      {{csrf_field()}}
      <div class="modal-header">
        <h5 class="modal-title" id="ReportKeyModalLabel">گزارش(کلمات کلیدی)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="form_send" class="container-fluid">
          <div class="row">
            @if(count($item->keywords))
              @foreach($item->keywords as $keyword)
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
                  <p>
                    کلمه: {{$keyword->keyword}}
                  </p>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
                  <p dir="ltr">
                    رتبه: {{$keyword->num}}
                  </p>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 px-1">
                  <p dir="ltr" class="text-left">
                    <a href="{{$keyword->link}}">
                      {{$keyword->link}}
                    </a>
                  </p>
                </div>
                <div class="col-xs-12">
                  <p>
                    <smal class="text-danger">
                      {{$keyword->user?$keyword->user->name:'__'}} {{$keyword->user && $keyword->user->role?$keyword->user->role->description:'__'}}({{g2j($keyword->created_at,'Y/m/d H:i')}})
                    </smal>
                  </p>
                  <hr/>
                </div>
              @endforeach
            @elseif($item->type_ref=='one' && auth()->id()!=$item->user_id || $item->type_ref=='multi' && auth()->id()!=$item->user_group_id)
              <div class="col-xs-12 alert alert-danger py-2">
                گزارشی ثبت نشده
              </div>
            @endif
              @if($item->type_ref=='one' && auth()->id()==$item->user_id || $item->type_ref=='multi' && auth()->id()==$item->user_group_id)
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
                  <div class="form-group{{ $errors->has('keyword') ? ' has-error' : '' }}">
                    <label for="keyword" class="form-label">کلمه * :</label>
                    <input type="text" name="keyword" id="keyword" value="{{old('keyword')}}" class="form-control" required>
                    @if ($errors->has('keyword'))
                      <span class="help-block"><strong>{{$errors->first('keyword')}}</strong></span>
                    @endif
                  </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-1">
                  <div class="form-group{{ $errors->has('num') ? ' has-error' : '' }}">
                    <label for="num" class="form-label">رتبه * :</label>
                    <input type="text" name="num" id="num" value="{{old('num')}}" class="form-control" placeholder="صفحه 2 لینک 3" required>
                    @if ($errors->has('num'))
                      <span class="help-block"><strong>{{$errors->first('num')}}</strong></span>
                    @endif
                  </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 px-1">
                  <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
                    <label for="link" class="form-label">لینک * :</label>
                    <input type="url" name="link" id="link" value="{{old('link')}}" class="form-control text-left" dir="ltr" required>
                    @if ($errors->has('link'))
                      <span class="help-block"><strong>{{$errors->first('link')}}</strong></span>
                    @endif
                  </div>
                </div>
              @endif

          </div>
        </div>
      </div>
      @if($item->type_ref=='one' && auth()->id()==$item->user_id || $item->type_ref=='multi' && auth()->id()==$item->user_group_id)
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary float-left">ارسال فرم</button>
      </div>
      @endif
    </form>
  </div>
</div>