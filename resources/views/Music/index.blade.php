@extends('layouts.master')

@push('style')
    <style>
        .edit_album_form,
        .edit_artist_form,
        .new_album_form,
        .new_artist_form{
            display: none;
        }
    </style>
@endpush

@section('content')
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin">홈</a>
            </li>
            <li class="breadcrumb-item">마케팅 관리</li>
            <li class="breadcrumb-item active"><strong>음원 관리</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-screen-smartphone"></i>음원 관리</div>
                                <div class="float-right">
                                    {{--<a href="/admin/campaigns/create" class="btn btn-success mb-2" role="button">새 게시물</a>--}}
                                    <button type="button" id="create_music_button" class="btn btn-success mb-2">new</button>
                                    <button id="delete_button" class="btn btn-danger mb-2">삭제[미완성]</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="callout callout-warning">
                                                    <small class="text-muted">검색결과</small>
                                                    <br>
                                                    <strong class="h4">{{ isset($search_count)? $search_count: 0 }}</strong>
                                                    <div class="chart-wrapper">
                                                        <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="callout callout-info">
                                                    <small class="text-muted">총 개수</small>
                                                    <br>
                                                    <strong class="h4">{{ isset($total)? $total: 0 }}</strong>
                                                    <div class="chart-wrapper">
                                                        <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-0 mb-4">
                                <form class="form-inline" method="GET" action="{{url('/admin/musics')}}">
                                    {{--<input type="hidden" id="last" name="last" value="{{$params['last']}}">--}}
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row pl-3">
                                                <div class="form-inline form-group">
                                                    <div class="col"><label for="page_cnt">게시물 수: </label></div>
                                                    <div class="input-group">
                                                        <select class="form-control" name="page_cnt" id="page_cnt">
                                                            <option value="15" {{ ($params['page_cnt']=='15') ? 'selected' : '' }}>15</option>
                                                            <option value="30" {{ ($params['page_cnt']=='30') ? 'selected' : '' }}>30</option>
                                                            <option value="50" {{ ($params['page_cnt']=='50') ? 'selected' : '' }}>50</option>
                                                            <option value="100" {{ ($params['page_cnt']=='100') ? 'selected' : '' }}>100</option>
                                                            <option value="1000" {{ ($params['page_cnt']=='1000') ? 'selected' : '' }}>1000</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-inline form-group">
                                                    <div class="col"><label for="start_date">검색 기간: </label></div>
                                                    <div class="input-group mr-1">
                                                        <input type="text" class="form-control datetimepicker" id="start_date" name="start_date" value="{{$params['start_date']}}" placeholder="" autocomplete="off">
                                                    </div>
                                                    ~
                                                    <div class="input-group">
                                                        <input type="text" class="form-control datetimepicker" id="end_date" name="end_date" value="{{$params['end_date']}}" placeholder="" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-inline form-group">
                                                    <div class="col"><label for="state">상태: </label></div>
                                                    <div class="input-group">
                                                        <select class="form-control" name="state" id="state">
                                                            <option value="" {{ ($params['state']=='') ? 'selected' : '' }}>전체</option>
                                                            <option value="0" {{ ($params['state']=='0') ? 'selected' : '' }}>대기</option>
                                                            <option value="1" {{ ($params['state']=='1') ? 'selected' : '' }}>게시</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row pl-3 mt-3">

                                                <div class="form-inline form-group">

                                                    <select class="form-control mr-1" name ="search_key" id="search_key">
                                                        <option {{ ($params['search_key']=='album_title') ? 'selected' : '' }} value="album_title">앨범명</option>
                                                        <option {{ ($params['search_key']=='artist_name') ? 'selected' : '' }} value="artist_name">가수이름</option>
                                                        <option {{ ($params['search_key']=='music_title') ? 'selected' : '' }} value="music_title">노래명</option>
                                                    </select>

                                                    <div class="input-group">
                                                        <input class="form-control" name="search_value" id="search_value" type="text" value="{{ $params['search_value'] }}" placeholder="" autocomplete="off">
                                                        <div class="input-group-append">
                                                    <span class="input-group-text" id="search_suffix">
                                                    </span>
                                                        </div>
                                                    </div>

                                                    <button class="btn btn-primary ml-2" type="submit"><i class="fa fa-search"></i> 검색</button>

                                                    <button class="btn btn-secondary ml-2 btn-refresh" type="button"><i class="fa fa-refresh"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr class="mt-2 mb-4">
                                <form id='delete_post' method="POST" action="{{url('/admin/musics/bulk/delete')}}">
                                    {{ csrf_field() }}
                                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th class="text-center"><input type="checkbox" name="check_all" id="check_all"></th>
                                            <th class="text-center">미리보기</th>
                                            <th class="text-center">앨범</th>
                                            <th class="text-center">가수 </th>
                                            <th class="text-center">제목</th>
                                            <th class="text-center">상태</th>
                                            <th class="text-center">뮤직비디오url</th>
                                            <th class="text-center">멜론 url</th>
                                            <th class="text-center btn-change-sort"
                                                style="cursor: pointer;"
                                                data-key="play_count"
                                                data-value="{{ ($params['sort_value'] == 'desc' ? 'asc' : 'desc') }}">
                                                재생수<i class="fa fa-fw d-none" id="sort_play_count"></i>
                                            </th>
                                            <th class="text-center btn-change-sort"
                                                style="cursor: pointer;"
                                                data-key="created_at"
                                                data-value="{{ ($params['sort_value'] == 'desc' ? 'asc' : 'desc') }}">
                                                등록 날짜<i class="fa fa-fw d-none" id="sort_created_at"></i>
                                            </th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($musics as $val)
                                            {{--{{dd($val)}}--}}
                                            {{--<input type="hidden" id="music_{{$val->id}}_id" value="{{$val->id}}">--}}
                                            {{--<input type="hidden" id="music_{{$val->id}}_album_id" value="{{$val->album_id}}">--}}
                                            {{--<input type="hidden" id="music_{{$val->id}}_title" value="{{$val->title}}">--}}
                                            {{--<input type="hidden" id="music_{{$val->id}}_thumbnail_url" value="{{$val->thumbnail_url}}">--}}
                                            {{--<input type="hidden" id="music_{{$val->id}}_mv_url" value="{{$val->mv_url}}">--}}
                                            {{--<input type="hidden" id="music_{{$val->id}}_melon_url" value="{{$val->melon_url}}">--}}
                                            {{--<input type="hidden" id="music_{{$val->id}}_lyrics" value="{{$val->lyrics}}">--}}
                                            {{--<input type="hidden" id="music_{{$val->id}}_artist_ids" value="@foreach($val->artists as $artist){{$artist->id}},@endforeach">--}}
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="check_item[]" id="check_item" value="{{$val->id}}">
                                                </td>
                                                <td class="text-center">
                                                    @if (isset($val->thumbnail_url))
                                                        <img src="{{env('CDN_URL').$val->thumbnail_url}}" height="100" width="100">
                                                    @else
                                                        미리보기 없음
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{$val->album->title}}
                                                </td>
                                                <td class="text-center">
                                                    @foreach($val->artists as $artist)
                                                        {{$artist->name}},
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    {{$val->title}}
                                                </td>
                                                <td class="text-center">
                                                    <div class="mt-2">
                                                        <label class="switch switch-3d switch-success">
                                                            <input class="switch-input music-activated" id="{{ $val->id }}" type="checkbox" {{ ($val->state == 1 ? 'checked' : '') }}>
                                                            <span class="switch-slider"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="https://www.youtube.com/watch?v={{$val->mv_url}}" target="_blank">{{$val->mv_url}}</a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="https://www.melon.com/webplayer/mini.htm?contsIds={{$val->melon_url}}&contsType=S" target="_blank">
                                                       {{$val->melon_url}}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    {{$val->play_count}}
                                                </td>
                                                <td class="text-center">
                                                    {{$val->created_at}}
                                                </td>
                                                <td class="text-center">
{{--                                                    <a href="/admin/musics/{{$val->id}}/edit">--}}
                                                        <button type='button' data-value="{{$val->id}}" class="btn btn-outline-primary edit_music_button">수정</button>
                                                    {{--</a>--}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--edit Modal--}}

        <div class="modal fade" id="editMusicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" method="POST"
                          {{--action="{{url("/admin/musics")}}"--}}
                          onsubmit="return edit_value_check()"
                          id = "edit_music_form">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"><strong>음원 수정</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="edit_album_id" class="col-sm-2 col-form-label">앨범</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="edit_album_id" id="edit_album_id">
                                        {{--<option selected disabled>선택</option>--}}
                                        @foreach($albums as $album)
                                            {{--<option style="background-image:url({{app('config')['celeb']['jihoon']['cdn']}}{{$album->thumbnail_url}});" value="{{$album->id}}">--}}
                                            <option value="{{$album->id}}">
                                                {{$album->title}}
                                            </option>
                                        @endforeach
                                        <option value="new_album">new album</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row edit_album_form">
                                <label for="edit_album_title" class="col-sm-2 col-form-label">앨범 제목</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="edit_album_title" id="edit_album_title">
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="edit_album_thumbnail" class="col-sm-2 col-form-label">앨범 로고</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control-file" name="edit_album_thumbnail" id="edit_album_thumbnail">
                                    {{--<img id ='existing_album_thumbnail' src="#" class="w-50">--}}
                                    <img id="edit_album_thumbnail_blah" class="w-50" src="#" alt="update album logo" />
                                </div>
                            </div>
                            <div class="form-group row edit_album_form">
                                <label for="edit_album_released_at" class="col-sm-2 col-form-label">앨범 발매일</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control datetimepicker" id="edit_album_released_at" name="edit_album_released_at" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row edit_album_form">
                                <label for="edit_album_genre" class="col-sm-2 col-form-label">앨범 장르</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="edit_album_genre" name="edit_album_genre">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_artist_id" class="col-sm-2 col-form-label">가수</label>
                                <div class="col-sm-10">
                                    {{--todo 가수 여러명 선택--}}
                                    <select class="form-control" name="edit_artist_id" id="edit_artist_id">
                                        <option  selected disabled>선택</option>
                                        @foreach($artists as $artist)
                                            <option value="{{$artist->id}}">{{$artist->name}}</option>
                                        @endforeach
                                        <option value="new_artist">new artist</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row edit_artist_form">
                                <label for="edit_artist_name" class="col-sm-2 col-form-label">가수이름</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control-file" name="edit_artist_name" id="edit_artist_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_thumbnail_url" class="col-sm-2 col-form-label">음원 로고</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control-file" name="edit_thumbnail_url" id="edit_thumbnail_url">
                                    <img id="edit_thumbnail_url_blah" src="#" alt="update music logo" class="w-50" />
                                </div>
                            </div>
                            {{--<div class="form-group row">--}}
                            {{--<label for="repeat" class="col-sm-2 col-form-label">반복 시간(분)/일회성 = 0</label>--}}
                            {{--<div class="col-sm-10">--}}
                            {{--<input type="number" class="form-control" name="repeat" id="repeat" min="0" step="1" value="-1">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group row">
                                <label for="edit_title" class="col-sm-2 col-form-label">음악이름</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="edit_title" id="edit_title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_lyrics" class="col-sm-2 col-form-label">가사</label>
                                <div class="col-sm-10">
                                    <textarea cols="40" rows="8"  class="form-control" name="edit_lyrics" id="edit_lyrics"></textarea>
                                </div>
                            </div>
                            {{--<div class="form-group row">--}}
                            {{--<label for="push_title" class="col-sm-2 col-form-label">push_title</label>--}}
                            {{--<div class="col-sm-10">--}}
                            {{--<input type="text" class="form-control" name="push_title" id="push_title">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group row">--}}
                            {{--<label for="push_content" class="col-sm-2 col-form-label">push_content</label>--}}
                            {{--<div class="col-sm-10">--}}
                            {{--<input type="text" class="form-control" name="push_content" id="push_content">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group row">--}}
                            {{--<label for="push_tick" class="col-sm-2 col-form-label">push_tick</label>--}}
                            {{--<div class="col-sm-10">--}}
                            {{--<input type="text" class="form-control" name="push_tick" id="push_tick">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group row">
                                <label for="edit_melon_url" class="col-sm-2 col-form-label">멜론 url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="edit_melon_url" id="edit_melon_url">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_mv_url" class="col-sm-2 col-form-label">유투브 뮤직비디오 url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="edit_mv_url" id="edit_mv_url">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_reward_count" class="col-sm-2 col-form-label">멜론 보상 개수</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="edit_reward_count" id="edit_reward_count" value="20" min="0" step="5">
                                </div>
                            </div>
                            {{--<div class="form-group row">--}}
                            {{--<label  class="col-sm-3 col-form-label">게시기간</label>--}}
                            {{--<div class="col-sm-9">--}}
                            {{--<div class="row">--}}
                            {{--<div class="col">--}}
                            {{--<input type="text" class="form-control datetimepicker" name="create_start_date" id="create_start_date" autocomplete="off">--}}
                            {{--</div>--}}
                            {{--~--}}
                            {{--<div class="col">--}}
                            {{--<input type="text" class="form-control datetimepicker"  name="create_end_date" id="create_end_date" autocomplete="off">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-dot-circle-o"></i> 수정</button>
                            <button class="btn btn-sm btn-danger" type="reset"><i class="fa fa-refresh"></i> 다시</button>
                            <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-ban"></i> 닫기</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- create Modal -->
        <div class="modal fade" id="addMusicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" method="POST" action="{{url('/admin/musics')}}" onsubmit="return value_check()">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"><strong>음원 추가</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="album_id" class="col-sm-2 col-form-label">앨범</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="album_id" id="album_id">
                                        <option selected disabled>선택</option>
                                        @foreach($albums as $album)
                                            {{--<option style="background-image:url({{app('config')['celeb']['jihoon']['cdn']}}{{$album->thumbnail_url}});" value="{{$album->id}}">--}}
                                            <option value="{{$album->id}}">
                                                {{$album->title}}
                                            </option>
                                        @endforeach
                                        <option value="new_album">new album</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row new_album_form">
                                <label for="album_title" class="col-sm-2 col-form-label">앨범 제목</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="album_title" id="album_title">
                                </div>
                            </div>
                            <div class="form-group row new_album_form">
                                <label for="album_thumbnail" class="col-sm-2 col-form-label">앨범 로고</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control-file" name="album_thumbnail" id="album_thumbnail">
                                    <img id="album_thumbnail_blah" src="#" alt="need album logo" />
                                </div>
                            </div>
                            <div class="form-group row new_album_form">
                                <label for="album_released_at" class="col-sm-2 col-form-label">앨범 발매일</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control datetimepicker" id="album_released_at" name="album_released_at" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row new_album_form">
                                <label for="album_genre" class="col-sm-2 col-form-label">앨범 장르</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="album_genre" name="album_genre">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="artist_id" class="col-sm-2 col-form-label">가수</label>
                                <div class="col-sm-10">
                                    {{--todo 가수 여러명 선택--}}
                                    <select class="form-control" name="artist_id" id="artist_id">
                                        <option  selected disabled>선택</option>
                                        @foreach($artists as $artist)
                                            <option value="{{$artist->id}}">{{$artist->name}}</option>
                                        @endforeach
                                            <option value="new_artist">new artist</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row new_artist_form">
                                <label for="artist_name" class="col-sm-2 col-form-label">가수이름</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control-file" name="artist_name" id="artist_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="thumbnail_url" class="col-sm-2 col-form-label">음원 로고</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control-file" name="thumbnail_url" id="thumbnail_url">
                                    <img id="thumbnail_url_blah" src="#" alt="need music logo" />
                                </div>
                            </div>
                            {{--<div class="form-group row">--}}
                                {{--<label for="repeat" class="col-sm-2 col-form-label">반복 시간(분)/일회성 = 0</label>--}}
                                {{--<div class="col-sm-10">--}}
                                    {{--<input type="number" class="form-control" name="repeat" id="repeat" min="0" step="1" value="-1">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">음악이름</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lyrics" class="col-sm-2 col-form-label">가사</label>
                                <div class="col-sm-10">
                                    <textarea cols="40" rows="8"  class="form-control" name="lyrics" id="lyrics"></textarea>
                                </div>
                            </div>
                            {{--<div class="form-group row">--}}
                                {{--<label for="push_title" class="col-sm-2 col-form-label">push_title</label>--}}
                                {{--<div class="col-sm-10">--}}
                                    {{--<input type="text" class="form-control" name="push_title" id="push_title">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group row">--}}
                                {{--<label for="push_content" class="col-sm-2 col-form-label">push_content</label>--}}
                                {{--<div class="col-sm-10">--}}
                                    {{--<input type="text" class="form-control" name="push_content" id="push_content">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group row">--}}
                                {{--<label for="push_tick" class="col-sm-2 col-form-label">push_tick</label>--}}
                                {{--<div class="col-sm-10">--}}
                                    {{--<input type="text" class="form-control" name="push_tick" id="push_tick">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group row">
                                <label for="melon_url" class="col-sm-2 col-form-label">멜론 url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="melon_url" id="melon_url">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mv_url" class="col-sm-2 col-form-label">유투브 뮤직비디오 url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="mv_url" id="mv_url">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="reward_count" class="col-sm-2 col-form-label">보상 개수</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="reward_count" id="reward_count" value="20" min="0" step="5">
                                </div>
                            </div>
                            {{--<div class="form-group row">--}}
                                {{--<label  class="col-sm-3 col-form-label">게시기간</label>--}}
                                {{--<div class="col-sm-9">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col">--}}
                                            {{--<input type="text" class="form-control datetimepicker" name="create_start_date" id="create_start_date" autocomplete="off">--}}
                                        {{--</div>--}}
                                        {{--~--}}
                                        {{--<div class="col">--}}
                                            {{--<input type="text" class="form-control datetimepicker"  name="create_end_date" id="create_end_date" autocomplete="off">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-dot-circle-o"></i> 등록</button>
                            <button class="btn btn-sm btn-danger" type="reset"><i class="fa fa-refresh"></i> 다시</button>
                            <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-ban"></i> 닫기</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@stop

@push('script')
    <script>
        var accessToken = '{{ Session::get('access_token')  }}';
        var tokenType = '{{ Session::get('token_type') }}';

        var update_lock = false;
        var sort_key = '{{ $params['sort_key'] }}';
        var sort_value = '{{ $params['sort_value'] }}';

        var musics = <?=  json_encode($musics) ?>;
        var cdn_url = <?= json_encode($cdn_url) ?>;

        // 검색항목 변경
        function changeSearchInput(value)
        {
            switch (value) {
                case 'created_at':

                    // $('#search_value').datepicker({
                    //     format: 'yyyy-mm-dd',
                    //     language: 'ko',
                    //     todayHighlight: true,
                    //     autoclose: true
                    // });

                    $('#search_suffix').append('<i class="fa fa-calendar"></i>')
                    break;

                default:

                    $('#search_suffix').text('포함');
                    break;
            }
        }

        function value_check(){
            //신규 앨범 등록일경우
            if($('#album_id').val()=='new_album'){
                if($('#album_title').val() == ''){
                    swal.fire('앨범제목!');
                    return false;
                }
                if($('#album_thumbnail').val() == ''){
                    swal.fire('앨범로고!');
                    return false;
                }
            }
            //신규 가수 등록일경우
            if($('#artist_id').val()=='new_artist'){
                if($('#artist_name').val()==''){
                    swal.fire('가수이름!');
                    return false;
                }
            }
            if($('#album_id').val()==null){
                swal.fire('앨범!');
                return false;
            }
            if($('#artist_id').val()==null){
                swal.fire('가수!');
                return false;
            }
            if($('#thumbnail_url').val()==''){
                swal.fire('음원 로고!');
                return false;
            }
            // if($('#repeat').val()==-1){
            //     swal.fire('반복시간!');
            //     return false;
            // }
            if($('#title').val()==''){
                swal.fire('음악명!');
                return false;
            }
            // if($('#push_title').val()==''){
            //     swal.fire('push_title!');
            //     return false;
            // }
            // if($('#push_message').val()==''){
            //     swal.fire('push_message!');
            //     return false;
            // }
            // if($('#push_tick').val()==''){
            //     swal.fire('push_tick!');
            //     return false;
            // }
            if($('#melon_url').val()==''){
                swal.fire('멜론 url!');
                return false;
            }
            // if($('#create_start_date').val()==''){
            //     swal.fire('게시기간!');
            //     return false;
            // }
            // if($('#create_end_date').val()==''){
            //     swal.fire('게시기간!');
            //     return false;
            // }
            if($('#lyrics').val()==''){
                swal.fire('가사!');
                return false;
            }
        }

        //음원수정시 값 검사
        function edit_value_check(){
            //신규 앨범 등록일경우
            if($('#edit_album_id').val()=='new_album'){
                if($('#edit_album_title').val() == ''){
                    swal.fire('앨범제목!');
                    return false;
                }
                if($('#edit_album_thumbnail').val() == ''){
                    swal.fire('앨범로고!');
                    return false;
                }
            }
            //신규 가수 등록일경우
            if($('#edit_artist_id').val()=='new_artist'){
                if($('#edit_artist_name').val()==''){
                    swal.fire('가수이름!');
                    return false;
                }
            }
        }
        $('#create_music_button').on('click',function(e){
            e.preventDefault();

            $('#addMusicModal').modal('show');
        });


        // 초기화 클릭
        $('.btn-refresh').on('click', function (e) {

            e.preventDefault();

            location.href = location.pathname;
        });



        // 앨범 이미지 입력시 미리보기 생성
        $(document).on('change', 'input[name^="album_thumbnail"]', function (e) {
            var $target = $(this);
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#album_thumbnail_blah').attr('src', e.target.result);
                    $('#album_thumbnail_blah').css('width','320');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // 앨범  수정 이미지 입력시 미리보기 생성
        $(document).on('change', 'input[name^="edit_album_thumbnail"]', function (e) {
            var $target = $(this);
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#edit_album_thumbnail_blah').attr('src', e.target.result);
                    $('#edit_album_thumbnail_blah').css('width','320');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // 음원 이미지 입력시 미리보기 생성
        $(document).on('change', 'input[name^="thumbnail_url"]', function (e) {
            var $target = $(this);
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#thumbnail_url_blah').attr('src', e.target.result);
                    $('#thumbnail_url_blah').css('width','320');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // 음원 수정 이미지 입력시 미리보기 생성
        $(document).on('change', 'input[name^="edit_thumbnail_url"]', function (e) {
            var $target = $(this);
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#edit_thumbnail_url_blah').attr('src', e.target.result);
                    $('#edit_thumbnail_url_blah').css('width','320');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });


        $(document).ready(function(){

            // 검색항목 선택
            $('#search_key').on('change', function (e) {

                e.preventDefault();

                // $('#search_value').datepicker('destroy');
                $('#search_suffix').empty();

                changeSearchInput($('#search_key option:selected').val());

                $('#search_value').val('');
                $('#search_value').focus();
            });


            changeSearchInput('{{ $params['search_key'] }}');

            $('#delete_button').click(function(){
                //todo
                swal.fire('개발중')
                return false;
                $('#delete_post').submit();
            });

            $('.edit_music_button').click(function(e){
                e.preventDefault();
                music_id = $(this).data('value');
                music = $.grep(musics.data,function(e){return e.id === music_id})[0];
                $('#edit_music_form').attr('action','/admin/musics/'+music_id);
                $('#edit_album_id').val(music.album.id);
                $('#edit_album_thumbnail_blah').attr('src', cdn_url+music.album.thumbnail_url);
                $('#edit_artist_id').val(music.artists[0].id);
                $('#edit_thumbnail_url_blah').attr('src', cdn_url+music.thumbnail_url);
                $('#edit_title').val(music.title);
                $('#edit_lyrics').val(music.lyrics);
                $('#edit_melon_url').val(music.melon_url);
                $('#edit_mv_url').val(music.mv_url);
                $('#edit_reward_count').val(music.reward_count);
                $('#editMusicModal').modal('show');
            });

            // checkbox all check
            $("#check_all").click(function(){
                if($("#check_all").prop("checked")){
                    $("input[id=check_item]").prop("checked",true);
                }else{
                    $("input[id=check_item]").prop("checked",false);
                }
            });

            //todo if 문 걸릴시 해당 생성 폼 모달 더 뛰우던지 해야함
            $('#album_id').change(function(){
                if($('#album_id').val() == 'new_album') {
                    $('.new_album_form').css('display','flex');
                }else{
                    $('.new_album_form').css('display','none');
                }
            });

            $('#edit_album_id').change(function(e){
                if($('#edit_album_id').val() == 'new_album') {
                    $('.edit_album_form').css('display','flex');
                    $('#edit_album_thumbnail_blah').attr('src','#');
                }else{
                    album_id = $('#edit_album_id').val();

                    album = $.grep(musics.data,function(e){return e.album_id === album_id});

                    $('.edit_album_form').css('display','none');
                    $('#edit_album_thumbnail_blah').attr('src','#');
                }
            });


            $('#artist_id').change(function(){
                if($('#artist_id').val() == 'new_artist') {
                    $('.new_artist_form').css('display','flex');
                }else{
                    $('.new_artist_form').css('display','none');
                }
            });
            $('#edit_artist_id').change(function(){
                if($('#edit_artist_id').val() == 'new_artist') {
                    $('.edit_artist_form').css('display','flex');
                }else{
                    $('.edit_artist_form').css('display','none');
                }
            });

            // datetime picker

            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false,
            });

            // 정렬항목 선택
            $('.btn-change-sort').on('click', function () {

                var sort_key = $(this).data('key');
                var sort_value = $(this).data('value');

                location.href = location.pathname + '?page={{ $musics->currentPage() }}&page_cnt=' + encodeURI($('#page_cnt').val())
                        + "&start_date="  + encodeURI($('#start_date').val())
                        + "&end_date="  + encodeURI($('#end_date').val())
                        + "&state="   +  encodeURI($('#state').val())
                        + "&search_key="   +  encodeURI($('#search_key').val())
                        + "&search_value="   +  encodeURI($('#search_value').val())
                    +'&sort_key=' + encodeURI(sort_key) + '&sort_value=' + encodeURI(sort_value);
            });

            switch (sort_key) {
                case 'play_count':
                    $('#sort_play_count').removeClass('d-none');

                    if (sort_value == 'asc') {
                        $('#sort_play_count').addClass('fa-sort-amount-asc');
                    } else {
                        $('#sort_play_count').addClass('fa-sort-amount-desc');
                    }

                    break;
                default:

                    $('#sort_created_at').removeClass('d-none');

                    if (sort_value == 'asc') {
                        $('#sort_created_at').addClass('fa-sort-amount-asc');
                    } else {
                        $('#sort_created_at').addClass('fa-sort-amount-desc');
                    }

                    break;
            }
        });

        // 활성 변경
        $('.music-activated').on('click', function (e) {
            console.log(this.id);
            console.log(($(this).is(':checked') ? '1' : '0'));
            updatemusic(this.id, ($(this).is(':checked') ? '1' : '0'));
        });

        // 음원 상태 수정
        function updatemusic(id, state)
        {
            if (update_lock == true) {
                swal.fire('수정에 실패하였습니다!');
                return false;
            } else {
                update_lock = true;
            }

            var params = {
                _method : 'PUT',
                state : state
            };

            $.ajax({
                url: '/api/musics/'+id+'/state',
                headers: {
                    'Accept' : 'application/json',
                    'Authorization' : tokenType + ' ' + accessToken,
                },
                data: params,
                type: 'put',
                beforeSend: function () {
                }
            }).
            done(function (response) {
                var result = response.result;

                if (result == 'success') {
                    swal.fire('수정하였습니다')
                } else {
                    swal.fire('수정에 실패하였습니다!');
                }
            }).
            fail(function (response) {
                console.log(response); console.log(response);
                swal.fire('수정에 실패하였습니다!');
            }).
            always(function (response) {
                console.log(response);
                update_lock = false;
            });
        }
    </script>
@endpush
