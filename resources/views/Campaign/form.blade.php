@extends('layouts.master')

@push('style')
    <style>
        {{--C: 클릭형 F: 친구초대 I: 설치형 M: 멜론스트리밍--}}
        .select_type {
            display: none;
        }

    </style>

@endpush

@section('content')
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">홈</a>
            </li>
            <li class="breadcrumb-item">Campaign</li>
            <li class="breadcrumb-item active"><strong>수정</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-screen-smartphone"></i>Campaign 수정</div>
                            </div>
                            <div class="card-body">
                                <form enctype="multipart/form-data" method="POST" action="{{url('/admin/campaigns')}}" onsubmit="return value_check()">
                                    <input type="hidden" name="_method" value="PUT">
                                    {{--타입 선택하면 필요한 값들만 표시--}}
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">타입</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="event_type" id="event_type" disabled>
                                                {{--<option selected disabled>선택</option>--}}
                                                <option {{($campaign->event_type == 'M') ? 'selected' : ''}} value = 'M'>멜론 스트리밍</option>
                                                <option {{($campaign->event_type == 'F') ? 'selected' : ''}} value = 'F'>친구 초대</option>
                                                <option {{($campaign->event_type == 'I') ? 'selected' : ''}} value = 'I'>설치형</option>
                                                <option {{($campaign->event_type == 'C') ? 'selected' : ''}} value = 'C'>클릭형</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{--{{dd($campaign)}}--}}
                                    <div class="form-group row select_type C I M">
                                        <label for="push_type" class="col-sm-2 col-form-label">순서</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" value="{{$campaign->order_num}}" name="order_num" id="order_num" min="0">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C M I F">
                                        <label for="img_url" class="col-sm-2 col-form-label">로고</label>
                                        <div class="col-sm-10">
                                            <div class="alert alert-info" role="alert">
                                                이미지 권장 해상도 640 * 640
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    @if (isset($campaign->img_url))
                                                        <img src="{{env('CDN_URL').$campaign->img_url}}" class='card ' style ="width: 340px;">
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <input type="file" name="thumbnail" class='card' id="img_url">
                                                    <img id="blah" src="#" alt="need image"  class='card float-left'/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M I F">
                                        <label for="repeat" class="col-sm-2 col-form-label">반복 시간(분)/일회성 = 0</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"  value="{{$campaign->repeat}}" name="repeat" id="repeat">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M I F">
                                        <label for="app_package" class="col-sm-2 col-form-label">앱 페키지명</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"  value="{{$campaign->app_package}}" name="app_package" id="app_package">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M">
                                        <label for="push_title" class="col-sm-2 col-form-label">push_title</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->push_title}}" name="push_title" id="push_title">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M">
                                        <label for="push_message" class="col-sm-2 col-form-label">push_message</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->psuh_message}}" name="push_message" id="push_message">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M">
                                        <label for="push_tick" class="col-sm-2 col-form-label">push_tick</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->push_tick}}" name="push_tick" id="push_tick">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="thumbnail_1_1" class="col-sm-2 col-form-label">thumbnail_1_1</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->thumbnail_1_1}}" name="thumbnail_1_1" id="thumbnail_1_1">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="thumbnail_2_1" class="col-sm-2 col-form-label">thumbnail_2_1</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->thumbnail_2_1}}" name="thumbnail_2_1" id="thumbnail_2_1">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="thumbnail_3_1" class="col-sm-2 col-form-label">thumbnail_3_1</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"  value="{{$campaign->thumbnail_3_1}}" name="thumbnail_3_1" id="thumbnail_3_1">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="thumbnail_1_2" class="col-sm-2 col-form-label">thumbnail_1_2</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->thumbnail_1_2}}" name="thumbnail_1_2" id="thumbnail_1_2">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="thumbnail_2_2" class="col-sm-2 col-form-label">thumbnail_2_2</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->thumbnail_2_2}}" name="thumbnail_2_2" id="thumbnail_2_2">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="thumbnail_3_3" class="col-sm-2 col-form-label">thumbnail_3_3</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->thumbnail_3_3}}" name="thumbnail_3_3" id="thumbnail_3_3">
                                        </div>
                                    </div>
                                    <hr>
                                    <hr>
                                    <div class="form-group row">
                                        <label for="title" class="col-sm-2 col-form-label">캠페인 이름</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->title}}" name="title" id="title">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 col-form-label">캠페인 설명</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->description}}" name="description" id="description">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="url" class="col-sm-2 col-form-label">url</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->url}}" name="url" id="url">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="item_count" class="col-sm-2 col-form-label">캠페인 보상 개수</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{$campaign->item_count}}" name="item_count" id="item_count">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="state" class="col-sm-2 col-form-label">상태</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="state" id="state">
                                                <option {{($campaign->state == '1') ? "selected" : ""}} value="1">게시</option>
                                                <option {{($campaign->state == '0') ? "selected" : ""}} value="0">비게시</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="start_date" class="col-sm-2 col-form-label">광고시작일</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control datetimepicker" name="start_date" id="start_date" value="{{$campaign->start_date}}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="end_date" class="col-sm-2 col-form-label">광고종료일</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control datetimepicker"  name="end_date" id="end_date" value="{{$campaign->start_date}}" autocomplete=s"off">
                                        </div>
                                    </div>
                                    <button type="button" id="create_campaign_button" class="btn btn-primary">수정</button>
                                </form>
                                <button type="button" class="btn" id="test">test</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop

@push('script')
    <script>

        $(document).ready(function(){
            $('.select_type').css('display','none');
            selected_type = $("#event_type option:selected").val();
            $('.'+selected_type).css('display','flex');
            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });

            $('#event_type').change(function(){
                $('.select_type').css('display','none');
                selected_type = $("#event_type option:selected").val();
                $('.'+selected_type).css('display','flex');
            })

            $(document).on('change', 'input[type=file]', function (e) {
                var $target = $(this);
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#blah').attr('src', e.target.result);
                        $('#blah').css('width','340');
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });


        $('#test').on('click',function(){
            Swal.fire({
                title: 'test Submit your Github username',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Look up',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    return fetch(`//api.github.com/users/${login}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: `${result.value.login}'s avatar`,
                        imageUrl: result.value.avatar_url,
                    })
                    console.log(result);
                }
            })
        })
    </script>
@endpush
