@extends('layouts.master')

@section('content')
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">홈</a>
            </li>
            <li class="breadcrumb-item">팬 위치분석</li>
            <li class="breadcrumb-item active"><strong>팬 위치분석</strong></li>

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
                                                <span class="input-group-text bg-dark">검색날짜</span>
                                            </div>
                                            <input class="form-control text-center" id="search_date"
                                                   type="text" value="2019-09-01" placeholder="" >
                                        </div>
                                    </div>

                                    <div class="form-inline form-group ">
                                        <div class="input-group mr-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-dark">분포도</span>
                                            </div>
                                            <select class="form-control" id="statsType" name="statsType">
                                                <option value="route">전체</option>
                                                <option value="home">거주지</option>
                                                <option value="company">근무지</option>
                                                {{--                                                <option value="economic" disabled="">사생팬</option>--}}
                                            </select>
                                        </div>
                                    </div>

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
                                    <input class="form-control w-50 ml-2 mt-2" id="pac-input">
                                    <div id="canvas-user-map" data-tap-disabled="true"
                                         style="width:100%; height:650px; margin: 0px;"></div>
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

    {{--todo npm 이슈 해결하면 numeral.min 두번 부르는거니 삭제 그떄까지만 유지--}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

    <script>
{{--        let app = '{{ Session::get('app') }}';--}}
        let app = 'leeseol';
        var map;
        var markerIcon;
        var locations = [];
        var markers = [];

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

            $('#search_date').datepicker({
                format: 'yyyy-mm-dd',
                language: 'ko',
                todayHighlight: true,
                autoclose: true,
                endDate : '{{ $searchDate }}'
            });

            $('#search_date, #statsType').on('change', function (e) {
                e.preventDefault();

                searchAction();
            });
        });

        var initMap = function () {
            markerIcon = {url: '/img/map/marker.png', scaledSize: new google.maps.Size(29, 42)};
            map = googleObj.initMapGoogle('canvas-user-map', {
                lat: 37.5721405,
                lng: 126.9748046
            });

            googleObj.setGoogleEvent('idle',function(){
                searchAction();
            });

            googleObj.ps.addListener('places_changed', function () {
                var places = googleObj.ps.getPlaces();
                places.forEach(function (place, index) {
                    map.panTo(new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()));
                });
                searchAction();
            });

            googleObj.setGoogleEvent('zoom_changed', function () {
                searchAction();
            });

            googleObj.setGoogleEvent('dragend', function () {
                searchAction();
            });
        };

        searchAction = function () {

            var precision = customZoomLevel[map.getZoom().toString()];
            var requestData = {
                app: app,
                top_left: [map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng()].toString(),
                bottom_right: [map.getBounds().getSouthWest().lat(), map.getBounds().getNorthEast().lng()].toString(),
                precision: customZoomLevel[map.getZoom().toString()],
                type: $('#statsType').val(), // 옵션(동선 'type' => $request->input('type', 'route'), //동선)
                date: $('#search_date').val(),
            };

            markers.forEach(function (item, index) {
                item.setMap(null);
            });

            console.log(requestData);
            $.ajax({
                    url: '/api/fanx/location/fan',
                    data: JSON.stringify(requestData),
                    dataType: 'json',
                    // processData: false,
                    contentType: "application/json;charset=utf-8",
                    type: 'POST',
                    success: function (data) {
                        var resultData = data.data;
                        locations =resultData;

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

                            if (precision >= 12) {
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
                                    icon: {
                                        url: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/' + countLevel,
                                        scaledSize: new google.maps.Size(80, 80),
                                        origin: new google.maps.Point(0, 0),
                                        anchor: new google.maps.Point(32, 65),
                                        labelOrigin: new google.maps.Point(40, 41)
                                    },
                                    label: {
                                        text: numeral(count).format('0,0'),
                                        // text: count.toString(),
                                        // text: numberWithCommas(count),
                                        color: "#000000",
                                        fontSize: "16px",
                                        fontWeight: "bold"
                                    },
                                    map: map
                                });
                            }

                            markers.push(tmpMarker);


                        });
                    }
                }
            );
        };
    </script>


    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjr6qzm-jMrc9GgFYoYENxU2O9mYKGC1A&libraries=places,drawing&callback=initMap"
        defer></script>

@endpush
