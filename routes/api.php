<?php



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/test','Api\Auth\ApiAuthController@another_log');

//샘플
Route::get('/',function(){
    return response()->json([
        'data'  => new stdClass(),
        'resultCode'=>[
            'code'  =>  0,
            'message'   =>  'Success'
        ]
    ]);
});

// 성인인증 (본인확인) KMC / NICE  필요시 적용

// [1] KMC //리얼브릭 방식 => deprecated
//Route::group(['prefix'=>'icert'],function(){
//    Route::get('/', 'Api\User\Icert\IcertController@index');//본인인증창
//    Route::get('/store', 'Api\User\Icert\IcertController@store');//본인인증 저장
//
//    // 성인인증 안내
//    Route::get('/notice', 'Mall2\CpclientController@notice')->name('mall2.notice');
//    // 약관동의
//    Route::get('/agreement', 'Mall2\CpclientController@agreement');
//    // 성인인증 시작
//    Route::get('/cpclient', 'Mall2\CpclientController@encrypt');
//    // 성인인증 성공
//    Route::post('/cpclient/success', 'Mall2\CpclientController@success');
//    // 성인인증 실패
//    Route::post('/cpclient/fail', 'Mall2\CpclientController@fail');
//});
//
// [2] NICE//    //pinxy19 방식
//Route::prefix('cpclient')->group(function () {
//    //성인인증 안내
////    Route::get('/', 'Api\User\Icert\CpclientController@policy');
//    //성인인증 시작
//    Route::get('/encrypt', 'Api\User\Icert\CpclientController@encrypt');
//    //성인인증 성공
//    Route::post('/success', 'Api\User\Icert\CpclientController@success');
//    //성인인증 실패
//    Route::post('/fail', 'Api\User\Icert\CpclientController@fail');
//    //ios => skip
//    Route::get('/skip', 'Api\User\Icert\CpclientController@skip');
//});

Route::group(['prefix' => '/campaigns'],function(){
    Route::post('/reward', 'Api\Campaign\Controller@campaign_reward');   // 충전소 리워드 받기
    Route::post('/push_reward', 'Api\Campaign\Controller@push_reward');   // 푸시 리워드 받기
    Route::put('/{campaign_id}/state','Api\Campaign\Controller@state_update')->middleware('auth:api');  //관리자화면 캠페인 on/off api todo 관리자만 써야함
});

Route::group(['prefix' => '/users'],function(){
    Route::post('/', 'Api\User\Controller@store');// 앱 구동 id return
    Route::get('/V2/{user_id}', 'Api\User\Controller@user_v2')->middleware('auth:api');         //마이페이지 프로필
    Route::get('/{user_id}/ban_boards','Api\User\Controller@user_ban_boards')->middleware('auth:api');//유저 차단함 목록
    Route::put('/{user_id}/withdraw','Api\User\Controller@withdraw')->middleware('auth:api');//회원 탈퇴
    Route::get('/{user_id}/push','Api\User\Controller@push_state')->middleware('auth:api');  //push on off state 조회
    Route::put('/{user_id}/push','Api\User\Controller@push_update')->middleware('auth:api');  //push on off update
    Route::put('/{user_id}/password','Api\User\Controller@password_update')->middleware('auth:api');//password update
    Route::put('/{user_id}/nickname','Api\User\Controller@nickname_update')->middleware('auth:api');//nickname update
    Route::put('/{user_id}/additional_info','Api\User\Controller@additional_info')->middleware('auth:api');//소셜 회원가입직후 닉네임,생년월일 없을시 추가입력요청
    Route::put('/{user_id}/gender','Api\User\Controller@gender_update'); // 성별 수정
    Route::get('/{user_id}/point_info','Api\User\Controller@point_info')->middleware('auth:api');   // 상단 포인트

    Route::get('/{user_id}/point_ranking','Api\User\Controller@point_ranking')->middleware('auth:api');   // 마이페이지 Point/Ranking
    Route::get('/{user_id}/point_ranking_graph','Api\User\Controller@point_ranking_graph')->middleware('auth:api');   // 마이페이지 Point/Ranking => 랭킹 그래프
    Route::get('/{user_id}/point_log','Api\User\Controller@point_log')->middleware('auth:api');   // 마이페이지 Point/Ranking => 포인트 로그[history]
    Route::get('/{user_id}/boards','Api\User\Controller@user_boards')->middleware('auth:api');          // 마이페이지 Drop Box
    Route::get('/{user_id}/activity_log','Api\User\UserScoreLogsController@index')->middleware('auth:api');          // 마이페이지 activity_log
    Route::get('/{user_id}/activity_log/{type}','Api\User\UserScoreLogsController@show')->middleware('auth:api');          // 마이페이지 activity_log
    Route::get('/{user_id}/setting','Api\User\Controller@setting')->middleware('auth:api');          // 마이페이지 setting

    Route::get('/bulk/ranking_v5','Api\User\Controller@ranking_v5'); // 랭킹_v5  ... 기획 수정 grade (유저 등급 폐기) 등급 뽑는 로직 제거 및 랭킹시스템변경 반영

    Route::post('/check/email','Api\User\Controller@email_check');//이메일 중복 확인
    Route::post('/check/nickname','Api\User\Controller@nickname_check');//닉네임 중복 확인
    Route::post('/check/V2/nickname','Api\User\Controller@nickname_check_v2');//닉네임 중복 확인

    Route::put('/', 'Api\User\Controller@signup');//회원가입
    Route::put('/V2','Api\User\Controller@signup_v2');//회원가입_v2
    Route::put('/V3','Api\User\Controller@signup_v3');//회원가입_v3

    Route::post('/find_validate_mobile','Api\User\Controller@find_validate_mobile'); //가입한 유저인지 확인후  인증 번호 발송
    Route::post('/validate_mobile','Api\User\Controller@validate_mobile');           //가입시 sms 인증 번호 발송

    Route::post('/validate_sms_number','Api\User\Controller@validate_sms_number');
    Route::get('/find/account','Api\User\Controller@account_find');// 아이디 찾기
    Route::put('/find/reset_password','Api\User\Controller@reset_password');// 비밀번호 찾기 =>비밀번호 재설정
    Route::put('/reset_mobile','Api\User\Controller@reset_mobile')->middleware('auth:api');//가입시 sms 인증 번호 발송

    Route::post('/{user_id}/profile_photo','Api\User\Controller@profile_photo_update')->middleware('auth:api'); // 프로필 사진 업로드
});

Route::group(['prefix' => '/auth'],function(){
    Route::post('/V3/login', 'Api\Auth\ApiAuthController@login_v3');//로그인_v2 (토큰 발급) 생년월일 추가
    Route::post('/V3/social_login','Api\Auth\ApiAuthController@social_login_v3');//기존 소셜 로그인 + 위챗 로그인
    Route::post('/logout', 'Api\Auth\ApiAuthController@logout')->middleware('auth:api');//로그아웃 (토큰 폐기)
});

//Route::group(['prefix'=>'/tags'],function(){
//    Route::get('/','Api\Tag\Controller@index');
//    Route::get('/popular_tags','Api\Tag\Controller@popular_tags');//추천검색어
//    Route::post('/{tag}','Api\Tag\Controller@search'); // 태그검색결과
//});

// 위에 임시 route 랑 같음
Route::group(['prefix'=>'/boards'],function(){
    Route::get('/info/{board_id}', 'Api\Board\Controller@board_info'); //게시물  정보

    Route::get('/tag_filter','Api\Board\Controller@tag_board_list'); //태그 검색 적용 리스트
    Route::get('/fanfeed_best','Api\Board\Controller@fanfeed_best'); // 팬피드 베스트 게시물
    Route::get('/artist_best','Api\Board\Controller@artist_best');  // 아티스트 베스트 게시물
//    Route::get('/best','Api\Board\Controller@best_list'); // 베스트게시물 리스트 아티스트/팬피드 베스트 게시물 통합 type(artist/fanfeed)구분 안씀

    Route::get('/V6/mix','Api\Board\Controller@mix_list_v6'); // type별로 섞어서 로비리스트V6 => 게시물 작성일자 최신순
    Route::get('/V6/{type}','Api\Board\Controller@single_list_v6');//개별 리스트V6
    Route::get('/list_like_board','Api\Board\Controller@list_like_board')->middleware('auth:api');//좋아요 리스트
    Route::get('/get_list/{type}','Api\Board\Controller@get_list'); // 컨텐츠 리스트
//    Route::get('/V6/event_list','Api\Board\Controller@event_list_v6'); //이벤트 리스트V6 기획변경으로인해 안씀 이미 배포된 앱에서 사용중이라 냅둠
    Route::post('/delete/{board_id}','Api\Board\Controller@delete_board'); //게시물 삭제
    Route::post('/update_thumbnail/{board_id}','Api\Board\Controller@update_thumbnail'); //썸네일 변경

    Route::get('/get_list_artist/','Api\Board\Controller@get_list_artist');//아티스트 리스트
    Route::get('/get_list_follow_artist/','Api\Board\Controller@get_list_follow_artist');//팔로우 아티스트 리스트


    Route::post('/like','Api\Board\Controller@like')->middleware('auth:api'); //게시물 좋아요 && 마이핀 저장
    Route::post('/board_like','Api\Board\Controller@board_like'); //게시물 좋아요 && 마이핀 저장
    Route::post('/ban','Api\Board\Controller@ban')->middleware('auth:api'); //게시물 싫어요

    Route::get('/bulk/youtube_api_key','Api\Board\Controller@youtube_api_key');  // 유투브 개발자키

    Route::post('/fanfeed','Api\Board\Controller@fanfeed_upload')->middleware('auth:api');  // 팬피드 업로드

    //2019.8.27
    Route::post('/item','Api\Board\Controller@item')->middleware('auth:api'); //게시물 좋아요 아이템 사용

    Route::get('/refresh/{board_id}','Api\Board\Controller@refresh'); //게시물 새로고침
    Route::get('/refresh/V2/{board_id}','Api\Board\Controller@refresh_v2'); //게시물 새로고침
    Route::post('/upload_news','Api\Board\Controller@upload_news'); //뉴스게시물 등록

    Route::post('/upload_news','Api\Board\Controller@upload_news'); //뉴스게시물 등록

});

//댓글
Route::group(['prefix'=>'comments'],function(){
    Route::put('/{comment_id}','Api\Comment\Controller@edit');//댓글수정
    Route::get('/V3','Api\Comment\Controller@index_v3');//댓글목록 V3
    Route::get('/V3/reply','Api\Comment\Controller@reply_v3'); //대댓글 목록 V3
    Route::post('/','Api\Comment\Controller@store')->middleware('auth:api');//댓글등록
    Route::post('/reply','Api\Comment\Controller@reply_store')->middleware('auth:api');//대댓글등록

    Route::post('/delete/{comment_id}','Api\Comment\Controller@destroy')->middleware('auth:api'); //댓글삭제 //클라 delete method 문제? post method에 url수정함
    Route::post('/like','Api\Comment\Controller@like')->middleware('auth:api'); //댓글 좋아요
    Route::post('/report','Api\Comment\Controller@report')->middleware('auth:api'); //댓글 신고
});

Route::group(['prefix'=>'notices'],function(){
    Route::get('/','Api\Notice\Controller@notice_list'); // 공지리스트
});

Route::group(['prefix'=>'schedules'],function(){
    Route::get('/V2','Api\Schedule\Controller@schedule_list_v2'); // 스케줄리스트
    Route::get('/check','Api\Schedule\Controller@schedule_check'); // 달력표시용 스케줄 확인
});

Route::get('/banners','Api\Banner\BannerController@banner'); // 상단 배너 이미지 리스트

Route::group(['prefix' => 'musics'], function () {
    Route::get('/V2','Api\Music\Controller@music_list_v2');  // 음원리스트 보상확인 추가 //todo 2019/11/13 이후 앱배포시 삭제
    Route::get('/V3','Api\Music\Controller@music_list_v3');  // 음원리스트 뮤직비디오 게시물 정보 제거
    Route::put('/{music_id}/state','Api\Music\Controller@state_update')->middleware('auth:api'); //  게시/비게시
    Route::post('/{music_id}/reward','Api\Music\Controller@reward')->middleware('auth:api'); //  스트리밍 보상 지급
    Route::get('/videos','Api\Music\Controller@music_video_list');  // 뮤직 비디오 리스트//todo 2019-11-13 이후 앱배포시 삭제
    Route::get('/videos/V2','Api\Music\Controller@music_video_list_v2');  // 뮤직 비디오 리스트v2
    Route::get('/videos/V3','Api\Music\Controller@music_video_list_v3');  // 뮤직 비디오 리스트v3
});

Route::group(['prefix' => 'follows'], function () {
  Route::post('/','Api\Follow\Controller@index')->middleware('auth:api'); // 팔로우
  Route::post('/delete','Api\Follow\Controller@unfollow')->middleware('auth:api'); // 언팔로우
  Route::get('/get_list/','Api\Follow\Controller@get_list_follow_artist');//팔로우 아티스트 리스트
});

Route::group(['prefix' => 'albums'], function () {
    Route::get('/','Api\Music\Controller@album_list')->name('api.albums');   // 셀럽 앨범 리스트 todo 2019/11/13 이후 앱배포시 삭제
    Route::get('/V2','Api\Music\Controller@album_list_v2');   // 셀럽 앨범 리스트v2
    Route::get('/musics','Api\Music\Controller@album_music_list');       // 앨범 기준 음원 리스트//todo 2019-11-13 이후 앱배포시 삭제
    Route::get('/V2/musics','Api\Music\Controller@album_music_list_v2');       // 앨범 기준 음원 리스트
});

Route::group(['prefix' => 'shopitems'],function(){
    Route::get('/','Api\Shop\Controller@index');
    Route::post('/','Api\Shop\Controller@store')->middleware('auth:api');
    Route::get('/banner','Api\Shop\Controller@shop_banner'); // 인앱샾 상단 리스트
});

Route::group(['prefix'  =>  'log'],function(){
    Route::post('/referrer','Api\Log\Controller@referrer'); // 마켓 레퍼러 저장
});

////Seach
Route::group(['prefix' => 'search'],function (){
    Route::get('/', 'Api\Search\SearchController@index');
    Route::get('/{type}', 'Api\Search\SearchController@show');
});

Route::group(['prefix' => 'keywords'],function (){
    Route::get('/', 'Api\Search\KeywordController@index');
    Route::get('/{search}', 'Api\Search\KeywordController@show');
});

// 로그인 필요 api 접근시 토큰 없거나 틀리거나 만료토큰
Route::get('/invalidtoken',function(){
    return response()->json(['resultCode'=>[
        'code'  =>  -3004,
        'message'   =>  'invalidToken'
    ]
    ]);
});

// 1회용 코드
// 멜론 스트리밍 push 메세지 발신 => 지훈튜브 기준 지훈노래보냄
//  크리샤 츄 멜론 푸시 발생기 필요하다고 할시 app이름, push 제목 내용 정도만 수정하고 사용가능
Route::get('/push_generator','Api\TempController@push_generator');


// 보여주기용 관리자화면 페이지들용 으로 알고있음
Route::group(['prefix' => 'fanx'], function () {

    // 앱
    Route::group(['prefix' => 'app'], function () {
        Route::get('/stats', 'Api\FanX\Stats\AppController@get');
    });

    // 트랜드
    Route::group(['prefix' => 'trend'], function () {
        Route::get('/keyword/stats', 'Api\FanX\Stats\KeywordController@get');
    });

    // GIS
    Route::group(['prefix' => 'location'], function () {
        Route::post('/fan', 'Api\FanX\Location\FanController@get');
        Route::post('/stalker', 'Api\FanX\Location\StalkerController@get');
    });

    Route::post('/customer/request', 'Api\FanX\Customer\RequestController@store');
    Route::delete('/customer/request/{id}', 'Api\FanX\Customer\RequestController@delete');
});

Route::get('/translation','Api\Translation\Controller@translation');
Route::post('/artist/apply','Api\Artist\Controller@apply');