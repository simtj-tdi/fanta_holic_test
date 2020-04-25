@extends('layouts.master')

@section('content')
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">홈</a>
            </li>
            {{--<li class="breadcrumb-item">랭킹기준</li>--}}
            <li class="breadcrumb-item active"><strong>기준</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-badge"></i></div>
                                {{--<div class="float-right">--}}
                                    {{--/app_trends/excel--}}
                                    {{--<a href="{{ url('/admin/app_trends/excel?period='.$params['period'].'&gender='.$params['gender'].'&age='.$params['age']) }}"--}}
                                       {{--class="btn btn-success"><i class="fa fa-download"></i> 엑셀 다운로드</a>--}}
                                {{--</div>--}}
                            </div>
                            <div class="card-body">
                                <form enctype="multipart/form-data" method="POST" action="{{url("/admin/standard/{$standard->id}")}}">
                                    <input type="hidden" name="_method" value="PUT">
                                    {{ csrf_field() }}
                                    <div class="form-group row font-weight-bold">댓글 도배</div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">댓글도배기준(몇 초 이내)</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="spamming" name="spamming"
                                                   value="{{isset($standard['spamming'])? $standard['spamming'] : ''}}">
                                            {!! $errors->first('spamming', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <label for="manager" class="col-sm-2 col-form-label">30초 제한 기준(몇 번 경고)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="spam_count" name="spam_count"
                                                   value="{{isset($standard['spam_count'])? $standard['spam_count'] : ''}}">
                                            {!! $errors->first('spam_count', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <label for="manager" class="col-sm-2 col-form-label">블라인드 제한 기준(몇 번 30초제한)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="blind_count" name="blind_count"
                                                   value="{{isset($standard['blind_count'])? $standard['blind_count'] : ''}}">
                                            {!! $errors->first('blind_count', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <label for="tel" class="col-sm-2 col-form-label">블랙리스트 기준(몇 번 패널티)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="black_count" name="black_count"
                                                   value="{{isset($standard['black_count'])? $standard['black_count'] : ''}}">
                                            {!! $errors->first('black_count', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row  font-weight-bold">유저 활동 점수</div>
                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-2 col-form-label">댓글 좋아요 점수</label>
                                        <div class="col-sm-10">

                                            <input type="text" class="form-control" id="comment_like_score" name="comment_like_score"
                                                   value="{{isset($standard['comment_like_score'])? $standard['comment_like_score'] : ''}}">
                                            {!! $errors->first('comment_like_score', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="fax" class="col-sm-2 col-form-label">게시글 좋아요 점수</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="article_like_score" name="article_like_score"
                                                   value="{{isset($standard['article_like_score'])? $standard['article_like_score'] : ''}}">
                                            {!! $errors->first('article_like_score', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">댓글 작성 점수</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="comment_score" name="comment_score"
                                                   value="{{isset($standard['comment_score'])? $standard['comment_score'] : ''}}">
                                            {!! $errors->first('comment_score', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="memo" class="col-sm-2 col-form-label">로그인 보상 개수</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="login_reward" name="login_reward"
                                                   value="{{isset($standard['login_reward'])? $standard['login_reward'] : ''}}">
                                            {!! $errors->first('login_reward', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="form-group row  font-weight-bold"> 유저 등급 기준 (퍼센트)</div>
                                    <div class="form-group row">
                                        <label for="memo" class="col-sm-2 col-form-label">회장 기준</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="Chairman" name="Chairman"
                                                   value="{{isset($ranking_standard->Chairman)? $ranking_standard->Chairman : ''}}">
                                            {!! $errors->first('Chairman', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="memo" class="col-sm-2 col-form-label">부회장 기준</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="vice_Chairman" name="vice_Chairman"
                                                   value="{{isset($ranking_standard->vice_Chairman) ? $ranking_standard->vice_Chairman : ''}}">
                                            {!! $errors->first('vice_Chairman', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="memo" class="col-sm-2 col-form-label">명예 서포터즈 기준</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="Honor_supporters" name="Honor_supporters"
                                                   value="{{isset($ranking_standard->Honor_supporters)? $ranking_standard->Honor_supporters : ''}}">
                                            {!! $errors->first('Honor_supporters', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="memo" class="col-sm-2 col-form-label">서포터즈 기준</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="supporters" name="supporters"
                                                   value="{{isset($ranking_standard->supporters)? $ranking_standard->supporters : ''}}">
                                            {!! $errors->first('supporters', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="memo" class="col-sm-2 col-form-label">팬클럽 기준</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="fanclup" name="fanclup"
                                                   value="{{isset($ranking_standard->fanclup)? $ranking_standard->fanclup : ''}}">
                                            {!! $errors->first('fanclup', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="memo" class="col-sm-2 col-form-label">팬 기준</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="fan" name="fan"
                                                   value="{{isset($ranking_standard->fan)? $ranking_standard->fan : ''}}">
                                            {!! $errors->first('fan', '<span class="form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">수정</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop