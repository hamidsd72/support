<button data-toggle="modal" data-target="#RefModal" class="btn btn-labeled-right mx-2 bg-info">
  <i class="fa fa-retweet"></i>
  ارجاع به دیگری
</button>

<!-- Modal -->
<div class="modal fade" id="RefModal" role="dialog">
  <div class="modal-dialog">
    <form action="{{route('todo.list.ref',$item->id)}}" method="post" class="modal-content" enctype="multipart/form-data">
      {{csrf_field()}}
      <div class="modal-header">
        <h5 class="modal-title">ارجاع به دیگری</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group{{ $errors->has('user__id') ? ' has-error' : '' }}">
          <label for="user__id" class="form-label">برای کاربر :</label>
          <select id="mySelect2" name="user_id" class="not_select"
                  data-placeholder="کاربر را انتخاب کنید">
            <option value="">کاربر را انتخاب کنید</option>

            @if(isset($users))
              @foreach($users as $user)
                <option value="{{$user->id}}" {{old('user_id')==$user->id?'selected':''}}>{{$user->name}}{{ $user->role?'('.$user->role->description.')':'' }}</option>
              @endforeach
            @endif
          </select>
          @if ($errors->has('user__id'))
            <span class="help-block"><strong>{{$errors->first('user__id')}}</strong></span>
          @endif
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary float-left">ارسال فرم</button>
      </div>
    </form>
  </div>
</div>