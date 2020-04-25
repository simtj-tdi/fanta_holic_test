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
            <li class="breadcrumb-item">아티스트 신청 관리</li>
            <li class="breadcrumb-item active"><strong>전체</strong></li>
        </ol>
        <div class="container-fluid">

            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2">전체 게시물 ( {{count($list) }} )</div>
                                <div class="float-right">

                                </div>
                            </div>
                            <div class="card-body">

                                <table class="table table-responsive-sm"
                                       style="text-align: center;vertical-align:middle;">
                                    <thead>
                                    <tr style="vertical-align: middle;">
                                        <th>ID</th>
                                        <th>아티스트 신청 내용</th>
                                        <th>등록일</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $key => $val)
                                        <tr style="height: 100px;vertical-align: middle;">
                                            <td>{{ $val->id  }}</td>
                                            <td>{{ $val->artist_name }}</td>
                                            <td>{{ $val->created_at  }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger delBtn">삭제</button>
                                                <form action="{{route('artist.applyDelete', $val->id)}}" method="post" >
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div class="text-center">
                                    {{ $list->links() }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')

    <script type="text/javascript">


        $(document).ready(function () {
            $('.delBtn').click(function(){
                if( !confirm('정말 삭제하시겠습니까?'))
                {
                    return false;
                }

                $($(this).next()).submit();
            });

        });

        //php 변수 자바스크립트로 넘기는 코드 js파일로 뜯어노면 오류나서 남겨둠
        var token = "{{csrf_token()}}";
    </script>
@endpush
