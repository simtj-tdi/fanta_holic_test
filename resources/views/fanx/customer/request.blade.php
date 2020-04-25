@extends('layouts.master')

@section('content')
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">홈</a>
            </li>
            <li class="breadcrumb-item">Customer Service</li>
            <li class="breadcrumb-item active"><strong>상위분석 요청</strong></li>
            <!-- Breadcrumb Menu-->
            {{--<li class="breadcrumb-menu d-md-down-none">--}}
            {{--<div class="btn-group" role="group" aria-label="Button group">--}}
            {{--<a class="btn" href="#">--}}
            {{--<i class="icon-speech"></i>--}}
            {{--</a>--}}
            {{--<a class="btn" href="./">--}}
            {{--<i class="icon-graph"></i>  Dashboard</a>--}}
            {{--<a class="btn" href="#">--}}
            {{--<i class="icon-settings"></i>  Settings</a>--}}
            {{--</div>--}}
            {{--</li>--}}
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="fa fa-send"></i> 상위분석 요청</div>
                                <div class="float-right">
                                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#requestModal"><i class="fa fa-plus"></i> 분석 요청</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            @if (empty($params['contents']) === false)
                                                <div class="col-sm-2">
                                                    <div class="callout callout-info">
                                                        <small class="text-muted">검색결과 개수</small>
                                                        <br>
                                                        <strong class="h4">{{ number_format($requests->total()) }}</strong>
                                                        <div class="chart-wrapper">
                                                            <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-sm-2">
                                                <div class="callout callout-danger">
                                                    <small class="text-muted">총 개수</small>
                                                    <br>
                                                    <strong class="h4">{{ number_format($summary['pending'] + $summary['completed']) }}</strong>
                                                    <div class="chart-wrapper">
                                                        <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="callout callout-warning">
                                                    <small class="text-muted">대기중</small>
                                                    <br>
                                                    <strong class="h4">{{ number_format($summary['pending']) }}</strong>
                                                    <div class="chart-wrapper">
                                                        <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="callout callout-success">
                                                    <small class="text-muted">완료</small>
                                                    <br>
                                                    <strong class="h4">{{ number_format($summary['completed']) }}</strong>
                                                    <div class="chart-wrapper">
                                                        <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-0 mb-4">

                                <form id="searchRequest">
                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="form-inline form-group">

                                                <label class="my-auto mx-2 font-weight-bold">상태</label>
                                                <select class="form-control" name="status">
                                                    <option value="">전체</option>
                                                    <option value="pending"{{ $params['status'] == 'pending' ? ' selected' : '' }}>대기중</option>
                                                    <option value="completed"{{ $params['status'] == 'completed' ? ' selected' : '' }}>완료</option>
                                                </select>

                                                <label class="my-auto mx-2 font-weight-bold">내용</label>
                                                <input class="form-control" name="contents" type="text" value="{{ $params['contents'] }}" autocomplete="off">

                                                <button class="btn btn-primary ml-2" type="submit"><i class="fa fa-search"></i> 검색</button>

                                                <button class="btn btn-secondary ml-2" type="button" id="btnRefresh"><i class="fa fa-refresh"></i></button>

                                            </div>

                                        </div>
                                    </div>
                                </form>

                                <hr class="mt-2 mb-4">

                                <table class="table table-responsive-sm table-hover table-outline table-striped mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">상태</th>
                                        <th class="text-center">종류</th>
                                        <th class="text-center">내용</th>
                                        <th class="text-center">등록일</th>
                                        <th class="text-center">작업</th>
                                    </tr>
                                    </thead>
                                    <tbody id="list">
                                    @foreach ($requests as $item)
                                        <tr>
                                            <td class="text-center">
                                                @if ($item->status == 'completed')
                                                    <button class="btn btn-sm btn-pill btn-success" type="button">완료</button>
                                                @else
                                                    <button class="btn btn-sm btn-pill btn-warning" type="button">대기중</button>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->category }}</td>
                                            <td class="text-center">
                                                <textarea class="form-control font-xs" rows="2" readonly>{{ $item->contents }}</textarea>
                                            </td>
                                            <td class="text-center">{{ $item->created_at->toDateString() }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-danger btn-delete-request" data-id="{{ $item->id }}" type="button">삭제</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <br>

                                {{ $requests->appends($params)->links() }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="requestForm">
                    <input type="hidden" id="requestUserId" value="">
                    <div class="modal-header">
                        <h5 class="modal-title"><strong><i class="fa fa-send"></i> 상위분석 요청</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="requestCategory">항목</label>
                                <select class="form-control" id="requestCategory">
                                    <option value="팬분석">팬분석</option>
                                    <option value="기타">기타</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="requestContents">내용</label>
                                    <textarea class="form-control" id="requestContents" rows="9" placeholder="" autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-send"></i> 등록</button>
                        <button class="btn btn-sm btn-dark" type="reset"><i class="fa fa-repeat"></i> 다시</button>
                        <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-close"></i> 닫기</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">

        function addRequest() {

            var requestData = {
                app: '{{ Session::get('app') }}',
                type: 'request',
                category: $('#requestCategory').val(),
                contents: $('#requestContents').val()
            };

            $('body').loadingModal({text: '데이터 전송중입니다...', 'animation': 'rotatingPlane'});
            $('body').loadingModal('show');

            $.post('/api/fanx/customer/request', requestData, function (data) {
                var result = data.result;

                if (result == 'success') {

                    alert('정상적으로 등록되었습니다!');

                    $('#requestModal').modal('hide');
                    $('#requestForm')[0].reset();

                    window.location = window.location.href.split("?")[0];

                } else {

                    alert('등록시 오류가 발생하였습니다!');
                }
            });

            $('body').loadingModal('hide');
        }


        function deleteRequest(id) {

            var requestData = {
                _method: 'DELETE'
            };

            $('body').loadingModal({text: '데이터 전송중입니다...', 'animation': 'rotatingPlane'});
            $('body').loadingModal('show');

            $.post('/api/fanx/customer/request/' + id, requestData, function (data) {
                var result = data.result;

                if (result == 'success') {

                    alert('정상적으로 삭제되었습니다!');

                    location.reload();

                } else {

                    alert('삭제시 오류가 발생하였습니다!');
                }
            });

            $('body').loadingModal('hide');
        }


        $(document).ready(function () {

            $('#requestForm').on('submit', function (e) {

                e.preventDefault();

                if ($('#requestCategory').val() == '') {
                    alert('항목을 선택해 주십시오!');
                    return false;
                }

                if ($('#requestContents').val() == '') {
                    alert('내용을 입력해 주십시오!');
                    return false;
                }

                addRequest();
            });

            $('#btnRefresh').on('click', function () {
                window.location = window.location.href.split("?")[0];
            });

            $('.btn-delete-request').on('click', function () {
                if (confirm('정말 삭제하시겠습니까?')) {
                    deleteRequest($(this).data('id'));
                }
            });
        });

    </script>
@endpush