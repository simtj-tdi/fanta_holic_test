@extends('layouts.master')

@section('content')
    <main class="main p-3">
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-right">
                                <button class="btn btn-primary btn-add-jobschedule" type="button"><i class="fa fa-plus"></i> 잡 스케줄 등록</button>
                            </div>
                        </div>

                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">상태</th>
                                <th scope="col">반복 간격</th>
                                <th scope="col">Task Command Line</th>
                                <th scope="col">최근 실행 Job</th>
                                <th scope="col">다음 실행 시간</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <th scope="row">{{$row->id}}</th>
                                    <td scope="row">{{$row->state}}</td>
                                    <td scope="row">{{$row->schedule->recurrenceInterval}}</td>
                                    <td scope="row">
                                        <textarea class="form-control font-xs" data-id="{{ $row->id }}" rows="2" readonly>{{ $row->jobSpecification->jobManagerTask->commandLine }}</textarea>
                                    </td>
                                    <td scope="row">{{$row->executionInfo->recentJob->id}}</td>
                                    <td scope="row">{{\Carbon\Carbon::createFromTimestamp(strtotime($row->executionInfo->nextRunTime))->toDateTimeString()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addJobscheduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="add_jobschedule" name="add_jobschedule" action="" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><strong>잡 스케줄 등록</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Pool 선택</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="pool_id" name="pool_id">
                                        @foreach ($pools as $pool)
                                            <option value="{{$pool->id}}">{{$pool->pool_id}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">인스타그램 계정</label>
                                <div class="col-md-9">
                                    <input class="form-control" id="instagram_id" name="instagram_id" type="text" placeholder="">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">성별</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="1">남자</option>
                                        <option value="2">여자</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">반복 간격</label>
                                <div class="col-md-9">
                                    <div class="form-inline">
                                    <input class="form-control col-2" id="schedule_day" name="schedule_day" type="number" value="0"> 일
                                    <input class="form-control col-2" id="schedule_hour" name="schedule_hour" type="number" value="1"> 시
                                    <input class="form-control col-2" id="schedule_min" name="schedule_min" type="number" value="0"> 분
                                    <input class="form-control col-2" id="schedule_sec" name="schedule_sec" type="number" value="0"> 초
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                        </div>
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
    <script type="text/javascript">

        // 잡 스케줄 등록 모달
        $('.btn-add-jobschedule').on('click', function (e) {

            e.preventDefault();

            $('#addJobscheduleModal').modal('show');
        });

        // 잡 스케줄 등록 클릭
        $('#add_jobschedule').on('submit', function (e) {

            e.preventDefault();

            if ($('#instagram_id').val() == '') {
                alert('인스타그램 계정을 입력해 주십시오!');
                return false;
            }

            if ($('#pool_id').val() == '') {
                alert('풀을 선택해 주십시오!');
                return false;
            }

            addJobschedule();
        });

        // 잡 스케줄 등록
        function addJobschedule()
        {
            var formData = {
                'instagram_id' : $('#instagram_id').val(),
                'gender' : $('#gender').val(),
                'pool_id' : $('#pool_id').val(),
                'schedule_day' : $('#schedule_day').val(),
                'schedule_hour' : $('#schedule_hour').val(),
                'schedule_min' : $('#schedule_min').val(),
                'schedule_sec' : $('#schedule_sec').val(),
            };

            $.ajax({
                url: '/admin/azure/jobschedule',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                dataType: 'json',
                type: 'POST',
                beforeSend: function () {
                    $('#addJobscheduleModal').modal('hide');
                }
            }).
            done(function (response) {
                // var result = JSON.parse(response);
                var result = response.result;

                if (result == 'success') {
                    alert('정상적으로 등록되었습니다!');

                    location.reload();
                } else {
                    alert('등록에 실패하였습니다!')
                }
            }).
            fail(function () {
                alert('오류가 발생하였습니다!');
            }).
            always(function () {
                // $.LoadingOverlay('hide');
            });
        }
    </script>
@endpush