<?php
/**
 * Created by PhpStorm.
 * User: nsmg-new
 * Date: 2019-04-17
 * Time: 오후 2:44
 */

Route::get('/login', 'Admin\Login\LoginController@index');
Route::post('/login', 'Admin\Login\LoginController@login');
Route::post('/logout', 'Admin\Login\LoginController@logout');
Route::get('/password/{email}', 'Admin\Login\LoginController@password');
Route::post('/password', 'Admin\Login\LoginController@password_update');
Route::post('/password', '\Login\LoginController@showRegistrationForm');

Route::get('/channel', 'ChannelController@store');
Route::get('/channel/vlive', 'ChannelController@vlive_store');
Route::get('/channel/youtube', 'ChannelController@youtube_store');

//TEST
//Route::get('/Lib' '');

Route::group([
    'middleware' => ['web','admin']
],function(){
    Route::get('/','FanX\DashboardController@index')->name('client.dashboard');;

    Route::get('/location/fan/stats','FanX\LocationController@fanShow')->name('fan.location.analysis');
    Route::get('/location/stalker/stats ','FanX\LocationController@stalkerShow');

    Route::get('/app/stats/installed','FanX\AppController@installedShow');
    Route::get('/app/stats/uninstalled','FanX\AppController@uninstalledShow');
    Route::get('/app/stats/used','FanX\AppController@usedShow');

    Route::get('/trend/keyword/stats','FanX\TrendController@keywordShow');

    //팬 유형분석
    Route::get('/fan/type/stats','FanX\FanController@typeShow') ->name('fan.type.analysis');
    //팬 성향분석
    Route::get('/fan/character/stats','FanX\FanController@characterShow')->name('fan.tendency.analysis');

    Route::get('/customer/request','FanX\RequestController@showRequests');
    Route::get('/customer/error','FanX\RequestController@showErrors');

    Route::get('/sample/{id}', 'FanX\DashboardController@sample');

    Route::group(['prefix' => '/boards'], function () {
        //Board Controller로 통합
        //리스트
        Route::get('/', 'Admin\Board\BBSController@index')->name('board.index');
        Route::get('/new', 'Admin\Board\BBSController@show')->name('board.new');  //등록Form
        Route::get('/{id}','Admin\Board\BBSController@show')->name('board.show'); //수정Form

        Route::post('/','Admin\Board\BoardController@store')->name('board.store'); //생성

        Route::delete('/{id}','Admin\Board\BBSController@delete');//삭제


//        Route::get('/', 'Admin\Board\BoardController@get_news')->name('board.index'); //네이버 뉴스 api 테스트용
//        Route::get('/', 'Admin\Board\BoardController@naver_news')->name('board.index'); //News.php 테스트용

        Route::put('/{id}','Admin\Board\BBSController@update'); //수정
        Route::patch('/','Admin\Board\BBSController@patch');
//        Route::put('/{board_id}','Admin\Board\BoardController@update');
        Route::put('/select/{id}','Admin\Board\BBSController@select_update'); //게시/미게시

        Route::delete('/bulk','Admin\Board\BoardController@destroy');                    //삭제
        Route::put('/bulk/gender','Admin\Board\BoardController@gender_update');           //성별 수정
//        Route::put('/bulk/open','Admin\Board\BoardController@open_update');               //게시 on off
        Route::put('/bulk/text','Admin\Board\BoardController@text_update');               //text_check 후 update
        Route::put('/bulk/face','Admin\Board\BoardController@face_update');
        Route::put('/bulk/tag','Admin\Board\BoardController@tag_update');                 //태그 수정
        Route::get('/bulk/tag/common','Admin\Board\BoardController@common_tag');          //공통 태그 리스트
        Route::get('/bulk/app_review','Admin\Board\BoardController@app_review');              //검수 관리 게시판
        Route::put('/bulk/app_review','Admin\Board\BoardController@app_review_update');   //검수 수정

        Route::get('/chart','Admin\Board\ChartController@index');                        //데이터 수집량 차트 페이지
        Route::get('/chart/data','Admin\Board\ChartController@data');                    //데이터 불러오기
    });

    Route::group(['prefix'=> 'musics'],function(){
        Route::get('/','Admin\Music\MusicController@index'); // 음악리스트
        Route::post('/','Admin\Music\MusicController@store');; //음악 등록
//    Route::get('/{music_id}/edit','Admin\Music\MusicController@edit');// 음악 수정화면
        Route::put('/{music_id}/','Admin\Music\MusicController@update');  // 음악 수정
    });


    Route::group(['prefix'=> 'collect_batches'],function(){
        Route::get('/','Admin\Data\CrawlingController@index');            // 리스트
        Route::delete('/{id}','Admin\Data\CrawlingController@delete');    //삭제
        Route::put('/{id}','Admin\Data\CrawlingController@state_update'); //수정
        Route::post('/','Admin\Data\CrawlingController@store');           //생성
        Route::post('/bulk/execute','Admin\Data\CrawlingController@execute');    //크롤링 실행
    });


    Route::group(['prefix'=> 'azure'],function(){
        Route::get('/','Admin\Data\BatchController@index');  // Azure batch pools
        Route::post('/pool','Admin\Data\BatchController@store');  // Azure batch pools 등록
        Route::get('/jobs','Admin\Data\BatchController@jobs');  // Azure batch jobs
        Route::post('/job','Admin\Data\BatchController@job_store');  // Azure batch jobs 등록
        Route::get('/jobschedule','Admin\Data\BatchController@jobschedule');  // Azure batch job schedules
        Route::post('/jobschedule','Admin\Data\BatchController@jobschedule_store');  // Azure batch job schedules 등록
    });


    Route::group(['prefix'=>'collect_rules'],function(){
        Route::put('/','Admin\Data\CrawlingController@rule_update');   //수정
    });

    Route::group(['prefix'=>'banned_words'],function(){
        Route::get('/','Admin\BannedWord\BannedWordController@index'); //금칙어 리스트
        Route::post('/','Admin\BannedWord\BannedWordController@store');  //금칙어 등록
        Route::delete('/{id}','Admin\BannedWord\BannedWordController@delete'); //금칙어 삭제
    });

    Route::group(['prefix'=>'recommend_tag'],function(){
        Route::get('/','Admin\RecommendTag\RecommendTagController@index'); //추천태그 리스트
        Route::post('/','Admin\RecommendTag\RecommendTagController@store');  //추천태그 등록
        Route::delete('/{id}','Admin\RecommendTag\RecommendTagController@delete'); //추천태그 삭제
    });

    Route::group(['prefix'=>'users'],function(){
        Route::get('/','Admin\User\UserController@index')->name('users.index');              //유저리스트
        Route::get('/{id}','Admin\User\UserController@show');           //유저 상세
        Route::put('/{id}','Admin\User\UserController@update');         //수정
        Route::delete('/{id}','Admin\User\UserController@delete');         //삭제
    });

    Route::group(['prefix'=>'standard'],function(){
        Route::get('/','Admin\Standard\Controller@index');   //랭킹기준,유저활동점수기준 등록화면
        Route::put('/{id}','Admin\Standard\Controller@update'); // 수정
    });

    Route::group(['prefix'=>'pushes'],function(){
        Route::get('/','Admin\Push\PushController@index');            // push 리스트
        Route::get('/create','Admin\Push\PushController@create');     //생성 화면
        Route::post('/','Admin\Push\PushController@store');           //생성
        Route::get('/{push_id}/edit','Admin\Push\PushController@edit');       //수정 화면
        Route::put('/{push_id}','Admin\Push\PushController@update');          //수정
        Route::delete('/{push_id}','Admin\Push\PushController@destroy');       //삭제
    });

    Route::group(['prefix'=>'notices'],function(){
        Route::get('/','Admin\Notice\NoticeController@index');   //공지 리스트
        Route::delete('/bulk','Admin\Notice\NoticeCOntroller@destroy');  //공지 삭제
        Route::put('{notice_id}','Admin\Notice\NoticeController@edit'); //공지 수정
        Route::post('/','Admin\Notice\NoticeController@store');     //공지 등록
    });

    Route::group(['prefix'=>'schedules'],function(){
        Route::get('/','Admin\Schedule\ScheduleController@index');   //스케줄 리스트
        Route::delete('/bulk','Admin\Schedule\ScheduleController@destroy');  //스케줄 삭제
        Route::put('{notice_id}','Admin\Schedule\ScheduleController@edit'); //스케줄 수정
        Route::post('/','Admin\Schedule\ScheduleController@store');     //스케줄 등록
    });

    Route::group(['prefix' => 'search'],function (){
        Route::resource('/keyword','Admin\Search\KeywordController')->name('get','index');
    });

    Route::group(['prefix' => 'artist'], function () {
        Route::get('/apply','Admin\Artist\Controller@applyList')->name('artist.Apply');
        Route::delete('/applyDelete/{id}','Admin\Artist\Controller@applyDelete')->name('artist.applyDelete');
    });

    Route::group(['prefix'=>'campaigns'],function(){
        Route::get('/','Admin\Campaign\CampaignController@index');               //Push 리스트
        Route::get('/create','Admin\Campaign\CampaignController@create');        //생성 화면
        Route::post('/','Admin\Campaign\CampaignController@store');              //생성
        Route::get('/{push_id}/edit','Admin\Campaign\CampaignController@edit');  //수정 화면
        Route::put('/{push_id}','Admin\Campaign\CampaignController@update');     //수정
        Route::delete('/{push_id}','Admin\Campaign\CampaignController@destroy'); //삭제
    });

    Route::group(['prefix'=>'comments'],function(){
        Route::put('/{id}','Admin\Comment\CommentController@put')->name('comment.put');     //수정 - 나중에 추가
        Route::delete('/{id}','Admin\Comment\CommentController@delete')->name('comment.put');; //삭제 - 나중에 추가
    });

});
