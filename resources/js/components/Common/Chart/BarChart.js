import React, { Component, Fragment } from 'react';
import Highcharts from 'highcharts';

const SELECTOR_PREFIX = 'BarChart_';
class BarChart extends Component {
    constructor(props) {
        super(props);
        this.selector = SELECTOR_PREFIX + this.props.id;
    }
    static defaultProps = {
        id: 0,
        item: {
            keys: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            values: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 295.6, 454.4],
        },
    };
    barChart = (props) => {
        const { keys, values } = props.item;
        Highcharts.chart(this.selector , {
            chart: {
                type: 'column'
            },
            title: false,
            xAxis: {
                categories: keys
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: false
            },
            series: [{
                data: values
            }],
            colorByPoint: true,
            legend: {
                enabled: false
            },
            credits: {
                enabled: false,
            }
        });
    };
    componentWillReceiveProps(nextProps){
        this.barChart(nextProps);
    }
    render() {
        return (
            <Fragment>
                <div id={this.selector}></div>
            </Fragment>
        );
    } 
}

export default BarChart;