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
                                <button class="btn btn-primary btn-add-job" type="button"><i class="fa fa-plus"></i> 잡 등록</button>
                            </div>
                        </div>

                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">상태</th>
                                <th scope="col">Task Command Line</th>
                                <th scope="col">시작시간</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($rows as $row)
                                @if ($row->state != "completed")
                                <tr>
                                    <th scope="row">{{$row->id}}</th>
                                    <th scope="row">{{$row->state}}</th>
                                    <th scope="row">
                                        @if (isset($row->jobManagerTask))
                                        <textarea class="form-control font-xs" data-id="{{ $row->id }}" rows="2" readonly>{{ $row->jobManagerTask->commandLine }}</textarea>
                                        @else
                                        -
                                        @endif
                                    </th>
                                    <th scope="row">
                                        {{\Carbon\Carbon::createFromTimestamp(strtotime($row->executionInfo->startTime), 'Asia/Seoul')->toDateTimeString()}}
                                    </th>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addJobModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="add_job" name="add_job" action="" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><strong>잡 등록</strong></h5>
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

        // 잡 등록 모달
        $('.btn-add-job').on('click', function (e) {

            e.preventDefault();

            $('#addJobModal').modal('show');
        });

        // 잡 스케줄 등록 클릭
        $('#add_job').on('submit', function (e) {

            e.preventDefault();

            if ($('#pool_id').val() == '') {
                alert('풀을 선택해 주십시오!');
                return false;
            }

            if ($('#instagram_id').val() == '') {
                alert('인스타그램 계정을 입력해 주십시오!');
                return false;
            }

            addJob();
        });

        // 잡 등록
        function addJob()
        {
            var formData = {
                'pool_id' : $('#pool_id').val(),
                'instagram_id' : $('#instagram_id').val(),
                'gender' : $('#gender').val(),
            };

            $.ajax({
                url: '/admin/azure/job',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                dataType: 'json',
                type: 'POST',
                beforeSend: function () {
                    $('#addJobModal').modal('hide');
                }
            }).
            done(function (response) {
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