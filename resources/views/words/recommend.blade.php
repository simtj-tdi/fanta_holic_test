@extends('layouts.master')

@push('style')
    <style>
        #search_box {
            background: #cdcdcd;
            border: 1px solid #cfcfcf;
            border-radius: 6px;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            margin:19px 0px 22px 0px;
        }

        .box {
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            display: inline-block;
            border:1px solid #cccccc;
            background: white;
        }
    </style>
@endpush

@section('content')
    <main class="main bg-white">
        <form  class="form-inline" method="POST" onsubmit="return check_value()" action="{{url('/admin/recommend_tag')}}">
            {{ csrf_field() }}
            <div id="search_box" class="w-50  mt-0 mb-0">
                <div class="form-group box w-100 px-3 d-inline-block">
                    <h6 class="font-weight-bold ml-5  mt-2">추천 태그</h6>
                    <input type="text" class="form-control mb-2 w-75" id="word" name="word">
                    <button type="submit" class="ml-5 btn btn-primary">추가</button>
                </div>
            </div>
        </form>

        <hr/>
        <form class="form-inline">
            @if (isset($rows))
                @foreach($rows as $word)
                    <h3><button type="button" class="btn badge-pill badge-light btn_banned_word" name="{{$word}}">{{ $word }}</button></h3>
                @endforeach
            @endif
        </form>
    </main>
@endsection

@push('script')
    <script>
        var rows = new Array();
        @foreach($rows as $word)
        rows.push('<?php echo $word ?>');
        @endforeach

        function check_value(){
            // console.log(jQuery.inArray($('[name="word"]').val(), rows));
            if(jQuery.inArray($('[name="word"]').val(), rows) != -1){
                alert('이미 있는 추천태그 입니다.');
                return false;
            }

        }

        $(document).ready(function(){
            $(".btn_banned_word").on('click', function () {

                var word = $(this).attr('name');

                if (confirm(word + ' 을(를) 추천태그에서 삭제 하시겠습니까?')) {
                    $.ajax({
                        url: '/admin/recommend_tag/{id}',
                        type: 'delete',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        data: {
                            // '_method': 'delete',
                            'word': word
                        },
                        success: function (res) {
                            if (res) {
                                alert("삭제 되었습니다.");
                                location.reload();
                            }
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                }
            });
        });
    </script>
@endpush