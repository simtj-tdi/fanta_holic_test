@extends('layouts.master')

@push('style')
    <style>
        .table td {
            vertical-align: middle;
            text-align: center;
        }
    </style>
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

            <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 style="display: none;" aria-hidden="true">
                <form method="POST" action="{{route('keyword.index')}}">
                    @csrf
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">등록</h4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">키워드</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="키워드를 입력해주세요." name="name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Save changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 style="display: none;" aria-hidden="true">
                <form method="post" action="" id="updateFormId">
                    @method('PUT')
                    @csrf
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">수정</h4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">키워드</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="updateKeywordName" placeholder="키워드를 입력해주세요." name="name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="updateModal">닫기</button>
                                <button class="btn btn-primary" type="submit">저장</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2">전체 게시물 ( {{count($list) }} )</div>
                                <div class="float-right">

                                    {{--                                        <a href="/admin/campaigns/create" class="btn btn-success mb-2" role="button">새 게시물</a>--}}
                                    {{--                                                                            <button type="button" id="create_notice_button" class="btn btn-success mb-2">new</button>--}}
                                    {{--                                                                            <button id="delete_button" class="btn btn-danger mb-2" disabled>삭제(미완성)</button>--}}
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#createModal">등록
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">

                                <table class="table table-responsive-sm"
                                       style="text-align: center;vertical-align:middle;">
                                    <thead>
                                    <tr style="vertical-align: middle;">
                                        <th>키워드 ID</th>
                                        <th>키워드</th>
                                        <th>수정일</th>
                                        <th>등록일</th>
                                        <th>관리</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $rowKey => $val)
                                        <tr style="height: 100px;vertical-align: middle;">
                                            <td>{{ $val->id  }}</td>
                                            <td>{{ $val->name }}</td>
                                            <td>{{ $val->updated_at}}</td>
                                            <td>{{ $val->created_at}}</td>
                                            <td>
                                                <button type="button" name="{{$val->name}}"
                                                        class="btn btn-success updateBtn" id="{{$val->id}}"
                                                        data-toggle="modal" data-target="#updateModal">수정
                                                </button>

                                                <button type="button" class="btn btn-danger delBtn">삭제</button>
                                                <form action="{{route('keyword.index')}}/{{$val->id}}" method="post" >
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    {{--<script src="/js/app.js"></script>--}}
    {{--    <script src="/js/board_control.js"></script>--}}
    {{--    <script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/search_list_search.js?version=0.5.1"></script>--}}
    <script type="text/javascript">


        $(document).ready(function () {

            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });

            $('#allCheckBox').click(function () {
                let checked = $(this).is(':checked');
                $('.bbsCheck').prop('checked', checked);
                $.checkLengthBox();
            });


            $('.updateBtn').click(function () {
                $('#updateKeywordName').val($(this).attr('name'));
                let keyword = '{{route('keyword.index')}}';
                $('#updateFormId').attr('action',keyword+'/'+ $(this).attr('id'));
            });

            $('.delBtn').click(function(){
                if( !confirm('정말 삭제하시겠습니까?'))
                {
                    return false;
                }

                $($(this).next()).submit();
            });


            $('.bbsCheck').click(function () {
                $.checkLengthBox();
            });

            $.checkLengthBox = function () {
                if ($('.bbsCheck:checked').length > 0) {
                    $('#actionBox').removeClass('d-none');
                    $('#actionBox').slideDown(200);
                } else {
                    $('#actionBox').slideUp(200);
                    $('#actionBox').addClass('d-none');

                }
            }
            //썸네일 이미지
            $('.thumbnailImgBox').click(function () {
                if ($(this).width() === 250 && $(this).height() === 100) {
                    $(this).css('width', 'auto');
                    $(this).css('height', 'auto');
                } else {
                    $(this).css('width', '250px');
                    $(this).css('height', '100px');
                }
            });

            //날짜 버튼
            $('.changeDateBtn').click(function () {
                let startDate = $(this).attr('startDate');
                let endDate = $(this).attr('endDate');
                $('input[name=startDate]').val(startDate);
                $('input[name=endDate]').val(endDate);

                $(this.form)[0].submit();
            });
        });

        //php 변수 자바스크립트로 넘기는 코드 js파일로 뜯어노면 오류나서 남겨둠
        var token = "{{csrf_token()}}";
    </script>
@endpush
