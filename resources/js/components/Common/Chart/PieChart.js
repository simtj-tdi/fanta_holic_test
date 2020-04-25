import React, { Component, Fragment } from 'react';
import Highcharts from 'highcharts';
//import ReactHighcharts from 'react-highcharts';

const SELECTOR_PREFIX = 'PieChart_';
class PieChart extends Component {
    constructor(props) {
        super(props);
        this.selector = SELECTOR_PREFIX + this.props.id;
    }
    static defaultProps = {
        id: 0, 
        item: {
            keys: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            values: [
                ['Apples', 29.9, false],
                ['Pears', 71.5, false],
                ['Oranges', 106.4, false],
                ['Plums', 129.2, false],
                ['Bananas', 144.0, false],
                ['Peaches', 176.0, false],
                ['Prunes', 135.6, true, true],
                ['Avocados', 148.5, false]
            ]
        },
    };
    componentWillReceiveProps(nextProps){
        this.pieChart(nextProps);
    }
    pieChart = (props) => {
        const { values } = props.item;
        Highcharts.chart(this.selector , {
            title: false,
            series: [{
                type: 'pie',
                allowPointSelect: true,
                keys: ['name', 'y', 'selected', 'sliced'],
                data: values,
                showInLegend: true
            }],
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
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

export default PieChart;