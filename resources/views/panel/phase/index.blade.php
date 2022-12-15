@extends('layouts.panel')
@section('content')

    <div class="col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 padding-0">
            <div class="row">

                <div class="ticket-message" style="margin-top: 0 !important;">

                    @foreach($data->comments->reverse() as $comment)

                        <div class="adminticket" style="background-color: #fff;">
                            <div class="adminheader"><h5><i class="fa fa-comments" aria-hidden="true"></i> ادیب ( کارشناس )</h5></div>
                            <div class="adminmsg">
                                <p>{!! html_entity_decode($comment->comment__content) !!}</p>
                                @if(isset($comment->libraries)) <p class="text-center">تعداد فایل های پیوست
                                    : {{$comment->libraries->count()}}</p>
                                @foreach($comment->libraries as $library)
                                    <div class="comment_attach"><i class="fa fa-paperclip"></i> <a
                                                href="{{url($library->file__path)}}" target="_blank">مشاهده
                                            فایل پیوست</a></div>
                                @endforeach
                                @endif
                                <hr>
                                <p>تعداد ساعات کاری : {{$comment->comment__phase_hour}} ساعت <br/><br/> {{$comment->user->name}} ( {{$comment->user->role->description}} - {{$comment->user->role->name}} )</p>
                            </div>
                            <div class="ticket-footer clearfix">
                                <div class="tickets-timestamp"><i class="fa fa-clock-o" aria-hidden="true"></i> @include('partials.comment-jdate', $comment) ({{$comment->created_at->format('H:i')}})</div>
                            </div>
                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

@endsection
