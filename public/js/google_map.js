const googleObj = {
    manager: {},
    ps: {},
    map: {},
    circles: [],
    markers: [], //마커 배열
    overlays: [], // 오버레이 배열
    searchList: [], //검색결과 배열
    polygons: [], //도형 배열
    initMapGoogle: function (mapId, center =  {
        lat: 37.55113690659291 ,
        lng: 127.0012543167474
    }) {
        console.log(center);
        var drawingMapContainer = document.getElementById(mapId);
        var drawingMap = {
            // zoom: 16,
            zoom: 11,
            center:  center,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_TOP
            },
            scaleControl: true,
            streetViewControl: false,
            rotateControl: true,
            fullscreenControl: false,
            scrollwheel: true,
            navigationControl: false,
            mapTypeControl: false,
        };

        this.map = new google.maps.Map(drawingMapContainer, drawingMap);
        //구글은 최대 맵 레벨 지정할 필요가 없음
        // map.setMaxLevel(13);
        this.initPs();
        return this.map;
    },
    // 장소 검색 객체를 생성합니다.
    initPs: function (place_id = 'pac-input') {
        var input = document.getElementById(place_id);
        this.ps = new google.maps.places.SearchBox(input);
        console.log(document.getElementById(place_id));
        this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    },
    initDrawManger: function (callback = function () {
    }) {
        var options = { // Drawing manager를 생성할 때 사용할 옵션입니다
            map: map, // Drawing manager로 그리기 요소를 그릴 map 객체입니다
            drawingMode: [ // Drawing manager로 제공할 그리기 요소 모드입니다
                google.maps.drawing.OverlayType.MARKER,
                google.maps.drawing.OverlayType.POLYLINE,
                google.maps.drawing.OverlayType.RECTANGLE,
                google.maps.drawing.OverlayType.CIRCLE,
                google.maps.drawing.OverlayType.POLYGON
            ],
            // 사용자에게 제공할 그리기 가이드 툴팁입니다
            // 사용자에게 도형을 그릴때, 드래그할때, 수정할때 가이드 툴팁을 표시하도록 설정합니다
            guideTooltip: ['draw', 'drag', 'edit'],
            markerOptions: { // 마커 옵션입니다
                draggable: true, // 마커를 그리고 나서 드래그 가능하게 합니다
                removable: true // 마커를 삭제 할 수 있도록 x 버튼이 표시됩니다
            },
            polylineOptions: { // 선 옵션입니다
                draggable: true, // 그린 후 드래그가 가능하도록 설정합니다
                removable: true, // 그린 후 삭제 할 수 있도록 x 버튼이 표시됩니다
                editable: true, // 그린 후 수정할 수 있도록 설정합니다
                strokeColor: '#39f', // 선 색
                hintStrokeStyle: 'dash', // 그리중 마우스를 따라다니는 보조선의 선 스타일
                hintStrokeOpacity: 0.5  // 그리중 마우스를 따라다니는 보조선의 투명도
            },
            rectangleOptions: {
                draggable: true,
                removable: true,
                editable: true,
                strokeColor: '#39f', // 외곽선 색
                fillColor: '#39f', // 채우기 색
                fillOpacity: 0.5 // 채우기색 투명도
            },
            circleOptions: {
                draggable: true,
                removable: true,
                editable: true,
                strokeColor: '#39f',
                fillColor: '#39f',
                fillOpacity: 0.5
            },
            polygonOptions: {
                draggable: false,
                removable: true,
                editable: false,
                strokeColor: '#39f',
                fillColor: '#00ff0000',
                fillOpacity: 0.5,
                hintStrokeStyle: 'dash',
                hintStrokeOpacity: 0.5
            }
        };
        // 위에 작성한 옵션으로 Drawing manager를 생성합니다
        this.manager = new google.maps.drawing.DrawingManager(options);
        callback();
    },
    initCluster: function () {
        return new google.maps.MarkerClusterer({
            map: map, // 마커들을 클러스터로 관리하고 표시할 지도 객체
            averageCenter: true, // 클러스터에 포함된 마커들의 평균 위치를 클러스터 마커 위치로 설정
            minLevel: 10, // 클러스터 할 최소 지도 레벨
            calculator: [50, 100, 1000, 10000], // 클러스터의 크기 구분 값, 각 사이값마다 설정된 text나 style이 적용된다
            texts: getTexts, // texts는 ['삐약', '꼬꼬', '꼬끼오', '치멘'] 이렇게 배열로도 설정할 수 있다
            styles: [{ // calculator 각 사이 값 마다 적용될 스타일을 지정한다
                width: '30px', height: '30px',
                background: 'rgba(51, 204, 255, .8)',
                borderRadius: '15px',
                color: '#000',
                textAlign: 'center',
                fontWeight: 'bold',
                lineHeight: '31px'
            },
                {
                    width: '40px', height: '40px',
                    background: 'rgba(255, 153, 0, .8)',
                    borderRadius: '20px',
                    color: '#000',
                    textAlign: 'center',
                    fontWeight: 'bold',
                    lineHeight: '41px'
                },
                {
                    width: '50px', height: '50px',
                    background: 'rgba(255, 51, 204, .8)',
                    borderRadius: '25px',
                    color: '#000',
                    textAlign: 'center',
                    fontWeight: 'bold',
                    lineHeight: '51px'
                },
                {
                    width: '60px', height: '60px',
                    background: 'rgba(255, 80, 80, .8)',
                    borderRadius: '30px',
                    color: '#000',
                    textAlign: 'center',
                    fontWeight: 'bold',
                    lineHeight: '61px'
                }
            ]
        });

    },
    goMovePath: function (search_id, callback = function () {
    }) {
        var searchInfo = this.searchList[search_id];
        map.panTo(new google.maps.LatLng(searchInfo.y, searchInfo.x));
        callback([searchInfo]);
    },
    searchAction: function (keyword, currentPage) {
        return new Promise((resolve, reject) => {
            this.ps.keywordSearch(keyword, function (data, status, pagination) {
                if (status === google.maps.services.Status.OK || status === google.maps.services.Status.ZERO_RESULT) {
                    resolve(data);
                    // 페이지 번호를 표출합니다
                    // displayPagination(pagination);
                } else if (status === google.maps.services.Status.ERROR) {
                    alert('검색 결과 중 오류가 발생했습니다.');
                    return;
                }
            }, {page: currentPage});
        });
    },


    //그리기 이벤트
    setDrawPoly: function (type) {
        // 그리기 중이면 그리기를 취소합니다
        googleObj.manager.cancel();
        // 클릭한 그리기 요소 타입을 선택합니다
        googleObj.manager.select(google.maps.drawing.OverlayType[type]);
    },

    serachPlaces: function (callBack = function () {
    }) {
        // if (keyword.length == 0) {
        //     throw new ValidatioNException('키워드를 1자 이상 넣어주세요');
        // }

        var _self = this;
        this.searchList = [];
        this.ps.addListener('places_changed', function () {

            var places = _self.ps.getPlaces();
            var bounds = new google.maps.LatLngBounds();

            places.forEach(function (place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                _self.searchList[place.id] = geoPoint;
                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });

            if (Object.keys(_self.searchList).length == 1) {
                var key = Object.keys(_self.searchList)[0];
                var pt = new google.maps.LatLng(locations[key].lat, locations[key].lon);
                // map.fitBounds(bounds);
                _self.map.setCenter(pt);
                _self.map.setZoom(16);
                // map.fitBounds(bounds);
            } else {
                _self.map.fitBounds(bounds);
            }

            callBack();


        });
        //
        // var totalCnt = 0;
        // var totalPage = 1;
        // var pageSize = 15;
        // var currentPage = 1;
        //
        // googleObj.searchList = [];
        // var arrayList = [];
        //
        //
        //
        // var _self = this;
        // this.ps.keywordSearch(keyword, async function (data, status, pagination1) {
        //     totalPage = pagination1.last;
        //     totalCnt = pagination1.totalCount;
        //
        //     while (currentPage <= totalPage) {
        //         await _self.searchAction(keyword, currentPage).then(function (data) {
        //
        //             if (data.length > 0) {
        //                 data.forEach(function (searchInfo, searchKey) {
        //                     //갯수 100개 제한
        //                     if (Object.keys(googleObj.searchList).length <= 100) {
        //                         arrayList.push(searchInfo);
        //                         googleObj.searchList[searchInfo.id] = searchInfo;
        //                     }
        //                 });
        //             }
        //
        //             var result = arrayList;
        //             if (option == "limit") {
        //                 if (arrayList.length > 15) {
        //                     result = arrayList.slice(0, 14);
        //                 }
        //             }
        //
        //             if (currentPage == totalPage) {
        //                 console.log('callback before');
        //                 callBack(result);
        //                 console.log('callback after');
        //             }
        //         });
        //         currentPage++;
        //     }
        // });
    },
    setMakersInfo: function () {

    },

    getDataFromDrawingMap: function () {
        // Drawing this.manager에서 그려진 데이터 정보를 가져옵니다
        var data = this.manager.getData();

        var poligonData = data[google.maps.drawing.OverlayType.POLYGON];

        if (poligonData.length > 0) {
            var points = poligonData[0].points;
            // refreshMarker(points);
            // 아래 지도에 그려진 도형이 있다면 모두 지웁니다
            // googleObj.clearDraw();
        }
    },
    drawPolygo: function (polygons) {
        var len = polygons.length, i = 0;

        for (; i < len; i++) {
            var path = pointsToPath(polygons[i].points);
            var style = polygons[i].options;
            var polygon = new google.maps.Polygon({
                map: map,
                path: path,
                strokeColor: style.strokeColor,
                strokeOpacity: style.strokeOpacity,
                strokeStyle: style.strokeStyle,
                strokeWeight: style.strokeWeight,
                fillColor: style.fillColor,
                fillOpacity: style.fillOpacity
            });
            drawControl.push(polygon);
        }
    },
    // Drawing manager에서 가져온 데이터 중
    // 선과 다각형의 꼭지점 정보를 google.maps.LatLng객체로 생성하고 배열로 반환하는 함수입니다
    pointsToPath: function (points) {
        var len = points.length,
            path = [],
            i = 0;
        for (; i < len; i++) {
            var latlng = new google.maps.LatLng(points[i].y, points[i].x);
            path.push(latlng);
        }
        return path;
    },
    goMarker: function (markers_id) {
        google.maps.event.trigger(googleObj.markers[markers_id], 'mouseover');
        map.panTo(new google.maps.LatLng(googleObj.markers[markers_id].getPosition().lat(), googleObj.markers[markers_id].getPosition().lng()));
    },
    //그리기 이벤트
    selectOverlay: function (type) {
        // 그리기 중이면 그리기를 취소합니다
        googleObj.manager.cancel();
        // 클릭한 그리기 요소 타입을 선택합니다
        googleObj.manager.select(google.maps.drawing.OverlayType[type]);
    },
    deleteMarker: function (index) {
        if (!this.markers.hasOwnProperty(index)) {
            console.log('delete fail');
            console.log('index', index);
            return false;
        }


        console.log('delete target', index);

        this.markers[index].setMap(null);
        delete this.markers[index];
    },

    deleteOverlay: function (index) {
        this.overlays[index].setMap(null);
        delete this.overlays[index];
    },
    //마커 삭제
    clearMarker: function () {
        for (var [key, marker] of Object.entries(this.markers)) {
            marker.setMap(null);
        }
        this.markers = [];
    },
    //오버레이 삭제
    clearOverlay: function () {
        for (var [key, overlay] of Object.entries(this.overlays)) {
            overlay.setMap(null);
        }
        this.overlays = [];
    },
    clearCircle: function () {
        for (var [key, circle] of Object.entries(this.circles)) {
            circle.setMap(null);
        }
        this.circles = [];
    },
    //이전에 있던 도형삭제
    clearDraw: function () {
        var _self = this;
        this.polygons.forEach(function (draw) {
            console.log(_self.manager);
            _self.manager.remove(draw.target);
        });
        this.polygons = [];
    },
    // 도형, 마커, 오버레이 전체 삭제
    clearAll: function () {
        this.clearMarker();
        this.clearOverlay();
        this.clearCircle();
        this.clearDraw();
    },
    setGoogleEvent: function (eventName, callBack = function () {}) {

        google.maps.event.addListener(this.map, eventName, function () {
            callBack();
            // // 지도 중심좌표를 얻어옵니다
            // var latlng = map.getCenter();
            //
            // var message = '변경된 지도 중심좌표는 ' + latlng.lat() + ' 이고, ';
            // message += '경도는 ' + latlng.lng() + ' 입니다';
            //
            // var resultDiv = document.getElementById('result');
            // resultDiv.innerHTML = message;
        });
    },
    removeGoogleEvent: function (eventName, callBack = function () {
    }) {
    }
    ,
    setDrawEvent: function (eventName, callBack = function () {
    }) {
        this.manager.addListener(eventName, function (data) {
            callBack(data);
        });
    },
    removeDrawEvent: function (eventName, callBack = function () {
    }) {

    }
};
