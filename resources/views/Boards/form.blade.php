@extends('layouts.master')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">


@endpush
@section('content')
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin">홈</a>
            </li>
            <li class="breadcrumb-item">게시물 관리</li>
            <li class="breadcrumb-item active"><strong>전체</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <form action="{{ (!empty($info)) ? route('board.show', ['id'=>$info->id]) : route('board.store') }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @if( !empty($info->id) )
                                <input type="hidden" name="_method" value="PUT">
                            @endif
                            <div class="card">
                                @if (Session::has('message'))
                                    <div class="alert alert-danger font-weight-bold">{{ Session::get('message') }}</div>
                                @endif
                                <div class="card-header">
                                    <div class="float-left mt-2"><b>게시물 Detail</b></div>
                                    <div class="float-right">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if( !empty($info))
                                                <div class="form-group row">
                                                    <label for="id" class="col-sm-2 col-form-label text-center">게시물
                                                        아이디</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control col-md-4" id="id"
                                                               name="id"
                                                               value="{{isset($info->id)? $info->id : ''}}" readonly>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group row">
                                                <label for="type" class="col-sm-2 col-form-label text-center">
                                                    게시물 종류
                                                </label>
                                                <div class="col-sm-10">
                                                    @php
                                                        $stateConfig = [ '0' => '미검수', '1'=>'게시' ,'2'=>'미게시' ];
                                                        $channelConfig = ['event' => '이벤트', 'fanfeed' => 'FanFeed','instagram' => '인스타그램',
                                                           'myfeed' => '마이피드','news' => '뉴스','twitter' => '트위터','vlive' => '브이라이브','youtube'=> '유튜브'];
                                                    @endphp
                                                    <select class="form-control" name="type">
                                                        <option value="">선택해주세요</option>

                                                        @foreach( $channelConfig as $channelKey => $channelVal)
                                                            <option value="{{$channelKey}}"
                                                                    @if(!empty($type) && $type === $channelKey) selected
                                                                    @else disabled @endif>{{$channelVal}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="thumbnails" class="col-sm-2 col-form-label text-center">기존
                                                    thumbnail</label>
                                                <div class="col-sm-10">
                                                    <div class="row">
                                                        <div class="col">
                                                            @if (isset($info->thumbnail_url))
                                                                <img src="{{env('CDN_URL').$info->thumbnail_url}}"
                                                                     class='card'
                                                                     style="width: 340px;">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="thumbnails" class="col-sm-2 col-form-label text-center">
                                                    변경할 썸네일
                                                    <span class="badge badge-warning rounded-circle"
                                                          data-toggle="tooltip"
                                                          data-html="true" data-placement="right"
                                                          title="이미지 권장 해상도<br> 640px * 640px">?</span>
                                                </label>
                                                <div class="col-sm-10">
                                                    <div class="row">
                                                        <div class="custom-file col-md-4">
                                                            <input type="file" name="thumbnail"
                                                                   class="custom-file-input"
                                                                   id="thumbnail">
                                                            <label class="custom-file-label" for="inputGroupFile01">Choose
                                                                file</label>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <img id="blah" src="#" class='card float-left d-none'/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="thumbnails" class="col-sm-2 col-form-label text-center">
                                                    기존 데이터
{{--                                                    {{ json_decode($info->data)[0]->image }}--}}
                                                </label>
                                                <div class="col-sm-10">

                                                    @if(isset($info->data) && $info->type != 'fanfeed')

                                                        <div class="col bxslider">


                                                            @foreach($info->data as $val)
                                                                @if(isset($val->image))
                                                                    <div>
                                                                        <img src="{{env('CDN_URL').$val->image}}">
                                                                    </div>
                                                                @elseif(isset($val->video->src))
                                                                    <div>
                                                                        <div class="video_wrapper">
                                                                            <video src="{{env('CDN_URL').$val->video->src}}"
                                                                                   controls autoplay="autoplay"
                                                                                   poster="{{env('CDN_URL').$val->video->poster}}"></video>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="thumbnails" class="col-sm-2 col-form-label text-center">
                                                    교체할 이미지
                                                    <span class="badge badge-warning rounded-circle"
                                                          data-toggle="tooltip"
                                                          data-html="true" data-placement="right"
                                                          title="이미지 권장 해상도<br> 640px * 640px">?</span>
                                                </label>
                                                <div class="col-sm-10">
                                                    <div class="custom-file col-md-4">
                                                        <input type="file" name="data_files[]"
                                                               class="custom-file-input"
                                                               id="data_files[]" multiple>
                                                        <label class="custom-file-label" for="inputGroupFile01">
                                                            Choosefile
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10">
                                                    <div class="gallery bxslider_2 "></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="title"
                                                       class="col-sm-2 col-form-label text-center">제목</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="title" name="title"
                                                           value="{{isset($info->title)? $info->title : ''}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="contents"
                                                       class="col-sm-2 col-form-label text-center">내용</label>
                                                <div class="col-sm-10">
                                                    <textarea type="text" class="form-control h-100" id="contents"
                                                              name="contents"
                                                    >{{isset($info->contents)? $info->contents : ''}}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="ori_tag" class="col-sm-2 col-form-label text-center">자체
                                                    태그</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="custom_tag[]"
                                                           name="custom_tag"
                                                           value="{{!empty($info->custom_tag)? implode(',',$info->custom_tag) : ''}}"
                                                           placeholder="콤마(,)로 태그 분할">

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="state"
                                                       class="col-sm-2 col-form-label text-center">상태</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" name="state" id="state">
                                                        <option value="0" @if (isset($info->state)) {{($info->state=='0')? 'selected' : ''}} @endif>
                                                            대기
                                                        </option>
                                                        <option value="1" @if (isset($info->state)) {{($info->state=='1')? 'selected' : ''}} @endif>
                                                            게시
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label text-center">url</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="url" name="url"
                                                           value="{{isset($info->post)? $info->post : ''}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="ori_tag" class="col-sm-2 col-form-label text-center">오리지널
                                                    태그</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="ori_tag[]"
                                                           name="ori_tag"
                                                           value="{{!empty($info->ori_tag)? implode(',',$info->ori_tag) : ''}}"
                                                           placeholder="콤마(,)로 태그 분할"
                                                           readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label text-center">오리지널 썸네일
                                                    url</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="ori_thumbnail"
                                                           name="ori_thumbnail"
                                                           value="{{isset($info->ori_thumbnail)? $info->ori_thumbnail : ''}}"
                                                           readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label text-center">원 게시물 작성자</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="sns_account"
                                                           name="sns_account"
                                                           value="{{isset($info->sns_account)? $info->sns_account : ''}}"
                                                           readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label text-center">작성일</label>
                                                <div class="col-sm-10">

{{--                                                    <input type="text" class="form-control" id="created_at" name="created_at" value="" />--}}
                                                    <input type="text" class="form-control datepicker"
                                                           id="created_at" name="created_at"
                                                           value="{{ date("Y-m-d H:i:s") }}"
                                                           readonly>



                                                </div>
{{--                                                <label class="col-sm-2 col-form-label text-center">검수일</label>--}}
                                                <input type="hidden" class="form-control datepicker"
                                                       id="validation_at" name="validation_at"
                                                       value="{{isset($info->validation_at)? $info->validation_at : ''}}"
                                                       readonly>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-success">전송</button>
{{--                                    <button type="submit" class="btn btn-danger">리스트</button>--}}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><b>댓글 관리</b></div>
                                <div class="float-right"></div>
                            </div>
                            <div class="card-body">
                                @if( empty($info->comments))
                                    <div class="text-center">
                                        등록된 댓글이 없습니다.
                                    </div>
                                @else
                                    <table class="table table-responsive-sm text-center">
                                        <thead>
                                        <tr>
                                            <th>댓글 ID</th>
                                            <th>아이디</th>
                                            <th>댓글</th>
                                            <th>등록일</th>
                                            <th>상태</th>
                                            <th>관리</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($info->comments as $key => $comment)
                                            <tr>
                                                <td>{{$comment->id}}</td>
                                                <td>{{$comment->user_nickname}}</td>
                                                <td>{{$comment->comment}}</td>
                                                <td>{{$comment->created_at}}</td>
                                                <td>
                                                    
                                                </td>
                                                <th>

                                                </th>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection


@push('script')

    <script>
        $(document).ready(function () {
            // $('.slider').bxSlider();

            $('.bxslider').bxSlider({
                mode: 'fade',
                captions: true,
                slideWidth: 600,
                adaptiveHeight: true
            });

            var slider = $('.bxslider_2').bxSlider({
                mode: 'fade',
                captions: true,
                slideWidth: 600,
                adaptiveHeight: true
            });

            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d H:i:s',
                step: 1
            });

            $('[name="restore_article"]').on('click', function () {
                $('[name="restore_check"]').val(true);
                $('#edit_board').submit();
            });


            $(document).on('change', 'input[name^="thumbnail"]', function (e) {
                var $target = $(this);
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#blah').attr('src', e.target.result);
                        $('#blah').css('width', '340');
                        $('#blah').removeClass('d-none');
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            var imagesPreview = function (input, placeToInsertImagePreview) {
                if (input.files) {
                    var filesAmount = input.files.length;
                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();
                        cnt = 0;
                        reader.onload = function (event) {
                            if (event.target.result.substring(5, 10) == 'video') {
                                $($.parseHTML('<div class="d-images data-image-' + cnt + '">')).appendTo(placeToInsertImagePreview);
                                $($.parseHTML('<div>')).addClass('video_wrapper').addClass('video_wrapper-' + cnt).appendTo('div.data-image-' + cnt);
                                $($.parseHTML('<video>'))
                                    .attr('controls', true)
                                    .attr('autoplay', 'autoplay')
                                    .attr('src', event.target.result)
                                    .appendTo('div.video_wrapper-' + cnt);
                            } else {
                                $($.parseHTML('<div class="d-images data-image-' + cnt + '">')).appendTo(placeToInsertImagePreview);
                                $($.parseHTML('<img>')).attr('src', event.target.result).appendTo('div.data-image-' + cnt);
                            }

                            cnt++;
                            if (cnt === filesAmount) {
                                slider.reloadSlider();
                            }
                        };

                        reader.readAsDataURL(input.files[i]);
                    }
                }
            };

            $('[name = "data_files[]"]').on('change', function () {
                $('.gallery').empty();
                imagesPreview(this, 'div.gallery');
            });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        });


    </script>
@endpush
