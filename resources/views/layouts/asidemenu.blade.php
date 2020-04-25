<aside class="aside-menu">

    <!-- Tab panes-->
    <div class="tab-content">
        <div class="tab-pane active" id="timeline" role="tabpanel">
            <div class="list-group list-group-accent">
                {{$logs->onEachSide(1)->links()}}
                <div class="list-group-item list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase small">
                    최근수정게시물
                </div>
                <div id="load" style="position: relative;">
                    @foreach($logs as $log)
                        <div class="list-group-item list-group-item-accent-info px-1">
                            <div>
                                <a href="/admin/boards/{{$log->board_id}}/edit?type={{$log->board_type}}">
                                    <strong>{{$log->board_id}}</strong> {{$log->board_type}}
                                </a>
                            </div>
                            <small class="text-muted mr-3">
                                <i class="icon-calendar"></i>  {{$log->created_at}}</small>
                            <small class="text-muted">
                                <i class="icon-graph"></i> {{$log->update_name}}</small>
                        </div>
                    @endforeach
                </div>
                {{$logs->onEachSide(1)->links()}}
            </div>
        </div>

    </div>
</aside>