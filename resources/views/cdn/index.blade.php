@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form  method="POST" action="{{url('/cdn')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="file" class="form-control" name="upload_file" id="upload_file">
                <button type="submit" class="btn btn-primary">등록</button>
            </form>
        </div>
    </div>

    @if ($url)
    <div>
        <input type="text" class="form-control" value="{{env('CDN_URL').$url}}" readonly>
        <img src="{{env('CDN_URL').$url}}">
    </div>
    @endif
@stop

