<button data-toggle="modal" data-target="#ReportCheckModal" class="btn btn-labeled-left mx-2 bg-primary">
  <i class="fa fa-check"></i>
  چک لیست
</button>

<!-- Modal -->
<div class="modal fade" id="ReportCheckModal" tabindex="-1" role="dialog" aria-labelledby="ReportCheckModal"
     aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{route('todo.list.check.list',$item->id)}}" method="post" class="modal-content">
      {{csrf_field()}}
      <div class="modal-header">
        <h5 class="modal-title" id="ReportCheckModalLabel">چک لیست</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="form_send" class="container-fluid">
          <div class="row">
            <div class="col-12">
              <ul class="nav nav-tabs mt-5" role="tablist" {{$i=0}}>
                @foreach($tabs as $key_tab=>$tab)
                  @if(count($tab->checks))
                    <li class="nav-item float-right {{$i==0?'active':''}}">
                      <a class="nav-link" data-toggle="tab" href="#tab{{$tab->id}}" {{$i++}}>{{$tab->title}}</a>
                    </li>
                  @endif
                @endforeach
              </ul>
              <!-- Tab panes -->
              <div class="tab-content" {{$j=0}}>
                @foreach($tabs as $key_tab=>$tab)
                  @if(count($tab->checks))
                    <div id="tab{{$tab->id}}" class="container tab-pane {{$j==0?'active in':''}}">
                      <div class="row" {{$j++}}>
                        @foreach($tab->checks as $check)
                          <p>
                            @if($item->checks->where('check_id',$check->id)->first())
                              <i class="fa fa-check"></i>
                              <span dir="rtl">
                                  {{$check->title}}
                                  <smal class="text-danger">
                                ({{$item->checks->where('check_id',$check->id)->first()->user?$item->checks->where('check_id',$check->id)->first()->user->name:'__'}} {{$item->checks->where('check_id',$check->id)->first()->user && $item->checks->where('check_id',$check->id)->first()->user->role?$item->checks->where('check_id',$check->id)->first()->user->role->description:'__'}})({{g2j($item->checks->where('check_id',$check->id)->first()->created_at,'Y/m/d H:i')}})
                                  </smal>
                                </span>
                            @else
                              @if($item->type_ref=='one' && auth()->id()==$item->user_id || $item->type_ref=='multi' && auth()->id()==$item->user_group_id)
                                <input type="checkbox" name="check_id[]" value="{{$check->id}}">
                              @else
                                <i class="fa fa-close text-danger"></i>
                              @endif
                              <span dir="rtl">
                              {{$check->title}}
                            </span>
                            @endif
                          </p>
                        @endforeach
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      @if($item->type_ref=='one' && auth()->id()==$item->user_id || $item->type_ref=='multi' && auth()->id()==$item->user_group_id)
        @if($checks_count > count($item->checks))
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary float-left">ارسال فرم</button>
          </div>
        @endif
      @endif
    </form>
  </div>
</div>