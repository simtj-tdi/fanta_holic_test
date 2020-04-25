import React, { Component, Fragment } from 'react';
import Highcharts from 'highcharts';

const SELECTOR_PREFIX = 'LineChart_';
class LineChart extends Component {
    constructor(props) {
        super(props);
        this.selector = SELECTOR_PREFIX + this.props.id;
    }
    static defaultProps = {
        id: 0,
        item: {
            keys: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            values: [{
                name: 'Installation',
                data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175],
            }, {
                name: 'Manufacturing',
                data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434],
            }, {
                name: 'Sales & Distribution',
                data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387],
            }, {
                name: 'Project Development',
                data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227],
            }, {
                name: 'Other',
                data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111],
            }],
        },
    };
    componentDidMount(){
        this.barChart();
        Highcharts.setOptions({
            lang: {
              thousandsSep: ','
            }
        });
    };
    barChart = () => {
        const { keys, values, page } = this.props.item;
        console.log(values.map(val => val.data.map((cnt,key) =>
            (cnt - val.data[key-1])/val.data[key-1]*100
        )))
        const makerVal = values.map((val) => {
            return ({
                name: val.name,
                data: val.data.map((cnt, key) => {
                    let amt = Math.round((cnt - val.data[key-1])/val.data[key-1]*100)
                    if(key == 0 ) {
                        return cnt;
                    }
                    if(amt >= 10) {
                        return {y: cnt, marker: {
                                symbol: 'url(https://image.flaticon.com/icons/svg/497/497738.svg)',
                                width: 30,
                                height:30
                            }}
                    } else if (amt <= -10) {
                        return {y: cnt, marker: {
                                symbol: 'url(https://image.flaticon.com/icons/svg/564/564619.svg)',
                                width: 30,
                                height:30
                            }}
                    }
                    return cnt;
                })
            })
        });
        Highcharts.chart(this.selector , {
            title: false,

            subtitle: false,

            xAxis: {
                categories: keys
            },

            yAxis: {
                title: false,
                labels: {
                    formatter:function() {
                      return Highcharts.numberFormat(this.value, 0, '', ',');
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            tooltip: {
                shared: true,
                crosshairs: true,
            },

            plotOptions: {
                series: {
                    point:{
                        events:{
                            click: function(e){
                                if(e.point.marker){
                                    hs.htmlExpand(null, {
                                        pageOrigin: {
                                            x: e.pageX || e.clientX,
                                            y: e.pageY || e.clientY
                                        },
                                        headingText: e.point.category + ' 이슈',
                                        maincontentText: `
                                            <div class="card">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><a href="http://www.sportsseoul.com/news/read/806428" target="_blank">온페이스표 모바일 FPS 게임 중국·인도네이사·베트남 정식 서비스 눈앞</a></li>
                                                    <li class="list-group-item"><a href="http://aitimes.co.kr/news/view/114834" target="_blank">가장 인기 있는 무료 모바일게임은?…'쿵야 캐치마인드' 다운로드 순위 1위</a></li>
                                                    <li class="list-group-item"><a href="http://game.dailyesports.com/view.php?ud=201908191734209880da2c546b3a_26" target="_blank">모바일게임 기대작 '천요괴', 구글 플레이·원스토어 정식 출시</a></li>
                                                    <li class="list-group-item"><a href="http://www.thisisgame.com/webzine/game/nboard/225/?n=96964" target="_blank">도담게임즈, 모바일 퍼즐게임 '스위트 캔디 팝' 출시</a></li>
                                                    <li class="list-group-item"><a href="http://www.zdnet.co.kr/view/?no=20190818103652" target="_blank">中 모바일 게임 레전드오브블루몬, 국내 마켓 퇴출</a></li>
                                                </ul>
                                            </div>
                                        `,
                                        //width: 200
                                    });
                                }
                            }
                        },
                    },
                },
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    //enableMouseTracking: false
                }
            },

            series: (page === 'used' ? makerVal : values),

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            },
            credits: {
                enabled: false,
            }
        });
    };
    render() {
        return (
            <Fragment>
                <div id={this.selector}></div>
            </Fragment>
        );
    }
}

export default LineChart;