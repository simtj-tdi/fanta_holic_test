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
                                <button class="btn btn-primary btn-add-collect" type="button"><i class="fa fa-plus"></i> 풀 등록</button>
                            </div>
                        </div>

                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Pool ID</th>
                                <th scope="col">Pool 상태</th>
                                <th scope="col">Pool 노드 수</th>
                                <th scope="col">Pool vmSize</th>
                                <th scope="col">생성일</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <th scope="row">{{$row->id}}</th>
                                    <td>{{$row->pool_id}}</td>
                                    <td>{{isset($pools[$row->pool_id])? $pools[$row->pool_id]->state : '-'}}</td>
                                    <td>{{isset($pools[$row->pool_id])? $pools[$row->pool_id]->currentDedicatedNodes : '-'}}</td>
                                    <td>{{isset($pools[$row->pool_id])? $pools[$row->pool_id]->vmSize : '-'}}</td>
                                    <td>{{$row->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $rows->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCollectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="add_collect" name="add_collect" action="" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><strong>풀 등록</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Pool ID</label>
                                <div class="col-md-9">
                                    <input class="form-control" id="pool_id" name="pool_id" type="text" placeholder="">
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

        // 풀 등록 모달
        $('.btn-add-collect').on('click', function (e) {

            e.preventDefault();

            $('#addCollectModal').modal('show');
        });

        // 풀 등록 클릭
        $('#add_collect').on('submit', function (e) {

            e.preventDefault();

            if ($('#pool_id').val() == '') {
                alert('풀 아이디를 입력해 주십시오!');
                return false;
            }

            addCollect();
        });

        // 풀 등록
        function addCollect()
        {
            var formData = {
                'pool_id' : $('#pool_id').val()
            };

            $.ajax({
                url: '/admin/azure/pool',
                data: formData,
                dataType: 'json',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#addCollectModal').modal('hide');
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