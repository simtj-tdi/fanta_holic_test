@extends('layouts.master')

@section('content')
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">홈</a>
            </li>
            <li class="breadcrumb-item">팬 위치분석</li>
            <li class="breadcrumb-item active"><strong>사생팬 분석</strong></li>

        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            {{--                            <div class="card-header">인구</div>--}}
                            <div class="card-body">
                                <div class="row pl-3">
                                    <div class="form-inline form-group">
                                        <div class="input-group mr-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-dark">분석기간</span>
                                            </div>
                                            <input class="form-control text-center datepicker" id="search_date"
                                                   type="text" value=""
                                                   placeholder="" style="width:200px;">
                                        </div>
                                    </div>
                                    <div class="form-inline form-group">
                                        <div class="input-group mr-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-dark">반경</span>
                                            </div>
                                            <input class="form-control text-center" id="search_radius" type="number"
                                                   value="500">
                                            <div class="input-group-append">
                                                <span class="input-group-text">m</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-inline form-group ">
                                        <div class="">
                                            <button class="btn btn-primary" type="button" id="btnSearch"
                                                    onclick="searchAction();">검색
                                            </button>
                                        </div>
                                    </div>

                                    {{--                                    <div class="form-inline form-group ">--}}
                                    {{--                                        <div class="input-group mr-3">--}}
                                    {{--                                            <div class="input-group-prepend">--}}
                                    {{--                                                <span class="input-group-text bg-dark">옵션</span>--}}
                                    {{--                                            </div>--}}
                                    {{--                                            <select class="form-control" id="statsType" name="statsType">--}}
                                    {{--                                                <option value="route">동선</option>--}}
                                    {{--                                                <option value="route">거주지</option>--}}
                                    {{--                                                <option value="route">근무지</option>--}}
                                    {{--                                                --}}{{--                                                <option value="economic" disabled="">사생팬</option>--}}
                                    {{--                                            </select>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}

                                    <div class="form-inline form-group d-none">
                                        <div class="input-group mr-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-dark">계수처리</span>
                                            </div>
                                            <select class="form-control" id="population">
                                                <option value="total">전체인구</option>
                                                <option value="economic">경제인구</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-inline form-group d-none">
                                        <div class="">
                                            <button class="btn btn-secondary" type="button" id="btnRefresh"><i
                                                    class="fa fa-refresh"></i> 다각형 제거
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <table
                                        class="table table-responsive-sm table-striped table-hover table-outline mb-0 table-bordered app-table">
                                        <colgroup>
                                            <col span="1" style="width: 10%;">
                                            <col span="1" style="width: 45%;">
                                            <col span="1" style="width: 25%;">
                                            <col span="1" style="width: 10%;">
                                            <col span="1" style="width: 10%;">
                                        </colgroup>
                                        <thead class="thead-light">
                                        <tr>
                                            <th class="text-center middle">No.</th>
                                            <th class="text-center middle">ADID</th>
                                            <th class="text-center middle">근접빈도</th>
                                            <th class="text-center middle">Staff</th>
                                            <th class="text-center middle">지도</th>
                                        </tr>
                                        </thead>
                                        <tbody id="ListBox">
                                        </tbody>
                                    </table>

                                    <div class="modal" tabindex="-1" role="dialog" id="googleModal">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">사생팬 동선</h5>
                                                    <input type="text" id="datePick" class="form-control-sm" style="margin-left:10px;" autocomplete="off"/>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="canvas-user-map" style="width: 100%; height: 500px;"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    <script src="/js/google_map.js"></script>
    <script>
        let app = '{{ Session::get('app') }}';
        var map;
        var markerIcon;
        var locations = "";
        var markers = [];
        var circles = [];
        var mapInfo = {
            top_left : "37.687107404480564,126.65793156284121",
            bottom_right:  "37.41491789953907,127.34457707065371",
        };

        var localRequestData = {
            app: app,
            top_left: mapInfo.top_left,
            bottom_right: mapInfo.bottom_right,
            precision: 0,
            type: 'route', // 옵션(동선 'type' => $request->input('type', 'route'), //동선)
            date: '',
             ads_id : "",
        }

        var customZoomLevel = {
            '1': 1,
            '2': 1,
            '3': 1,
            '4': 2,
            '5': 2,
            '6': 2,
            '7': 3,
            '8': 3,
            '9': 4,
            '10': 4,
            '11': 4,
            '12': 5,
            '13': 5,
            '14': 6,
            '15': 6,
            '16': 6,
            '17': 6
        };

        $(document).ready(function () {
            $('#search_date').daterangepicker({
                "locale": {
                    "format": "YYYY-MM-DD",
                    "separator": " ~ ",
                    "applyLabel": "적용",
                    "cancelLabel": "취소",
                    "fromLabel": "시작일",
                    "toLabel": "종료일",
                    "customRangeLabel": "Custom",
                    "weekLabel": "W",
                    "daysOfWeek": [
                        "일",
                        "월",
                        "화",
                        "수",
                        "목",
                        "금",
                        "토"
                    ],
                    "monthNames": [
                        "1월",
                        "2월",
                        "3월",
                        "4월",
                        "5월",
                        "6월",
                        "7월",
                        "8월",
                        "9월",
                        "10월",
                        "11월",
                        "12월"
                    ],
                    "firstDay": 1
                },
                "startDate": "{{ date('Y-m-d',strtotime('-8day')) }} ",
                "endDate":  "{{ date('Y-m-d',strtotime('-1day')) }} ",
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                //$('#search_stats').submit();
            });

            $('#datePick').datepicker({
                lang:'ko',
                format:'yyyy-mm-dd',
                // monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                // dayNamesMin: ['일','월','화','수','목','금','토'],
                autoclose: true,
                onSelect: function(date) {
                },
            }).on("change", function() {
                searchModalAction();
                // alert('asdfdsf');
            });;

            searchAction();
        });

        var initMap = function () {
            markerIcon = {url: '/img/map/marker.png', scaledSize: new google.maps.Size(29, 42)};
            map = googleObj.initMapGoogle('canvas-user-map');

            map.setZoom(11);

            googleObj.setGoogleEvent('idle',function(){
                localRequestData.precision = customZoomLevel[map.getZoom().toString()];
                mapInfo.top_left = [map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng()].toString();
                mapInfo.bottom_right = [map.getBounds().getSouthWest().lat(), map.getBounds().getNorthEast().lng()].toString();
            });


            googleObj.setGoogleEvent('zoom_changed',function(){
                localRequestData.precision = customZoomLevel[map.getZoom().toString()];
                localRequestData.top_left = [map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng()].toString();
                localRequestData.bottom_right = [map.getBounds().getSouthWest().lat(), map.getBounds().getNorthEast().lng()].toString();
                searchModalAction();
            });

            googleObj.setGoogleEvent('dragend',function(){
                localRequestData.top_left = [map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng()].toString();
                localRequestData.bottom_right = [map.getBounds().getSouthWest().lat(), map.getBounds().getNorthEast().lng()].toString();
                searchModalAction();
            });


            googleObj.ps.addListener('places_changed', function () {
                var places = googleObj.ps.getPlaces();
                places.forEach(function (place, index) {
                    var tmpLocation = [place.geometry.location.lat(), place.geometry.location.lng()].toString();
                    locations = {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()};
                    locationString = tmpLocation;
                    map.panTo(new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()));

                    var tmpMarker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations.lat, locations.lng),
                        map: map
                    });

                    var tmpCircles = new google.maps.Circle({
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: '#FF0000',
                        fillOpacity: 0.35,
                        map: map,
                        center: new google.maps.LatLng(locations.lat, locations.lng),
                        radius: 500,
                        editable: true,
                    });

                    google.maps.event.addListener(circle, 'distance_changed', function () {
                        searchAction(circle.center);
                        //displayInfo(circle);
                    });
                    markers.push(tmpMarker);
                    circles.push(tmpCircles);
                });
                searchAction(locationString);
            });

            // googleObj.setGoogleEvent('zoom_changed', function () {
            //     // searchAction();
            // });
            //
            // googleObj.setGoogleEvent('dragend', function () {
            //     // searchAction();h
            // });
        };

        searchAction = function (locationString) {
            var requestData = {
                app: app,
                // location: locationString,
                radius: $('#search_radius').val(),
                search_date: $('#search_date').val(),
            };

            markers.forEach(function (item, index) {
                item.setMap(null);
            });

            $.ajax({
                    url: '/api/fanx/location/stalker',
                    data: JSON.stringify(requestData),
                    dataType: 'json',
                    // processData: false,
                    contentType: "application/json;charset=utf-8",
                    type: 'POST',
                    success: function (data) {
                        var resultData = data.data.items;
                        $('#ListBox').empty();
                        console.log(resultData);
                        var contentHtml = "";
                        if (resultData.length == 0) {
                            contentHtml = '<tr><td colspan="5">검색된 결과가 없습니다</td></tr>';
                            $('#ListBox').append(contentHtml)
                        }

                        var totalCnt = resultData.length;

                        resultData.forEach(function (row, index) {
                            contentHtml = "<tr class='text-center'>" +
                                "<td>" + (totalCnt - index) + "</td>" +
                                "<td>" + row.ads_id + "</td>" +
                                "<td>" + row.count + "</td>";
                                if(row.staff == true){
                                    contentHtml += "<td> <button class='btn btn-warning btn-sm radius_btn'>Y</button></td>";
                                }
                                else {
                                    contentHtml += "<td />"
                                }
                            contentHtml +="<td><button class='btn btn-primary btn-sm btn-modal' ads_id='" + row.ads_id + "'>지도</button></td>" +
                                "</tr>";
                            $('#ListBox').append(contentHtml)
                        });

                        $('.btn-modal').on('click', function () {
                            $('#googleModal').modal('toggle');
                            modalMapInit($(this).attr('ads_id'));
                        });
                    }
                }
            );
        };



        var searchModalAction = function (ads_id){


            markers.forEach(function (item, index) {
                item.setMap(null);
            });

            console.log('localRequestData',localRequestData);

            localRequestData.date = $('#datePick').val();
            // localRequestData.date =$('#search_date').data('daterangepicker').startDate._i;

            $.ajax({
                url: '/api/fanx/location/fan',
                data: JSON.stringify(localRequestData),
                dataType: 'json',
                // processData: false,
                contentType: "application/json;charset=utf-8",
                type: 'POST',
                success: function (data) {

                    var resultData = data.data;
                    locations =resultData;
                    var bounds = new google.maps.LatLngBounds();
                    resultData.forEach(function (row, index) {

                        var lat = row[0];
                        var lng = row[1];
                        var count = row[2];

                        var countLevel = 'm1.png';
                        if (50 <= count) countLevel = 'm1.png';
                        if (100 <= count) countLevel = 'm2.png';
                        if (150 <= count) countLevel = 'm3.png';
                        if (1000 <= count) countLevel = 'm4.png';
                        if (10000 <= count) countLevel = 'm5.png';

                        if (localRequestData.precision >= 12) {
                            var tmpMarker = new google.maps.Marker({
                                position: new google.maps.LatLng(lat, lng),
                                map: map
                            });

                            tmpMarker.addListener('click', function () {
                                setLatLng(this.getPosition().lat(), this.getPosition().lng());
                                map.setZoom(map.getZoom() + 1)
                            });
                        }
                        else {
                            var tmpMarker = new google.maps.Marker({
                                position: new google.maps.LatLng(lat, lng),
                                map: map
                            });
                        }
                        bounds.extend(tmpMarker.getPosition());
                        markers.push(tmpMarker);
                    });
                    // map.fitBounds(bounds);
                }
            });

        }

        var modalMapInit = function( ads_id ){

            localRequestData.ads_id = ads_id;
            $('#datePick').datepicker("setDate", $('#search_date').data('daterangepicker').startDate._i );
            searchModalAction(ads_id);
        }
    </script>


    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjr6qzm-jMrc9GgFYoYENxU2O9mYKGC1A&libraries=places,drawing&callback=initMap"
        defer></script>

@endpush
