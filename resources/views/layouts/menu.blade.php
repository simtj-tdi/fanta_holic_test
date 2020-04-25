<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link  {{request()->route()->getName() == 'client.dashboard' ? 'active':''}}"
                   href="{{ route('client.dashboard') }}">
                    <span class="nav-icon icon-speedometer"></span>
                    Home
                </a>
            </li>
            {{--@endif--}}
            <li class="nav-title">FAN Big Data</li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('fan.type.analysis')}}">
                    <i class="nav-icon icons cui-calculator"></i>팬 유형분석
                </a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="{{ route('fan.location.analysis') }}">
                    <i class="nav-icon icons cui-calculator"></i>팬 위치분석
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item font-xs">
                        <a class="nav-link pl-5" href="{{ route('fan.location.analysis') }}">
                            팬 위치분석
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('fan.tendency.analysis')}}">
                    <i class="nav-icon icons cui-calculator"></i>팬 성향분석
                </a>
            </li>
            <li class="nav-title">APP MANAGEMENT</li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="{{route('board.index')}}">
                    <i class="nav-icon icons cui-note"></i>게시물 관리
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::path() === 'admin/boards' && Request::get('schChannel') == "instagram" ) ? "active"  : ""}} pl-5" href="{{route('board.index',['schChannel' => 'instagram'])}}">
                            인스타그램
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::path() === 'admin/boards' && Request::get('schChannel') == "youtube" ) ? "active"  : ""}} pl-5" href="{{route('board.index',['schChannel' => 'youtube'])}}">
                            유튜브
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::path() === 'admin/boards' && Request::get('schChannel') == "twitter" ) ? "active"  : ""}} pl-5" href="{{route('board.index',['schChannel' => 'twitter'])}}">
                            트위터
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::path() === 'admin/boards' && Request::get('schChannel') == "vlive" ) ? "active"  : ""}} pl-5" href="{{route('board.index',['schChannel' => 'vlive'])}}">
                            브이 라이브
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::path() === 'admin/boards' && Request::get('schChannel') == "news" ) ? "active"  : ""}} pl-5" href="{{route('board.index',['schChannel' => 'news'])}}">
                            뉴스
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon icons cui-note"></i>마케팅 관리
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {{isset($schedule_menu) ? $schedule_menu : ""}} pl-5" href="/admin/schedules">
                            스케쥴
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isset($push_menu) ? $push_menu : ""}} pl-5" href="/admin/pushes">
                            푸쉬알림
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isset($notice_menu) ? $notice_menu : ""}} pl-5" href="/admin/notices">
                            공지사항
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isset($music_menu) ? $music_menu : ""}} pl-5" href="/admin/musics">
                            음악
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon icons cui-note"></i>팬 관리
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {{ url()->current() == route('keyword.index') ?"" :"" }} pl-5" href="{{route('keyword.index')}}">
                            추천 검색어
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ url()->current() == route('artist.Apply') ?"" :"" }} pl-5" href="{{route('artist.Apply')}}">
                            아티스트 신청
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isset($user_menu) ? $user_menu : ""}} pl-5" href="/admin/users">
                            유저
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isset($standard_menu) ? $standard_menu : ""}} pl-5" href="/admin/standard">
                            랭킹,및 활동기준
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{isset($banned_word_menu) ? $banned_word_menu : ""}} pl-5" href="/admin/banned_words">
                            금칙어
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
