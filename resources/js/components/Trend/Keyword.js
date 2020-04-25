import React, {Component} from 'react';
import ReactDOM from "react-dom";
import DateFickerCom from '../Common/DatePickerCom'
import LineChart from "../Common/Chart/LineChart"
import axios from "axios";
import * as moment from "moment/moment";
import './Keyword.scss';

const d = new Date();
const lastWeek = d.getDate();
const endDay = new Date();
const day = endDay.getDate();
class Keyword extends Component {
    constructor(props){
        super(props);
        this.api = {
            'get.stats': '/api/fanx/trend/keyword/stats',
        };
        this.state = {
            appRes: {
                items:[],
                label:[],
            },
            startDate: d.setDate(lastWeek - 8),
            endDate: endDay.setDate(day - 1),
            areaChartM: '',
            areaChartW: '',
            isToggle: true
        };
        this.handleChangeStart = this.handleChangeStart.bind(this);
        this.handleChangeEnd = this.handleChangeEnd.bind(this);
    }
    componentDidMount() {
        this.getApi();
    }

    getApi = () => {
        const sDate = this.state.startDate;
        const eDate = this.state.endDate;
        axios.get(this.api['get.stats'], {
            params:{
                app: 'jihoon',
                type: 'naver',
                start_date: moment(sDate).format('YYYY-MM-DD'),
                end_date: moment(eDate).format('YYYY-MM-DD'),
            }
        }).then(res => {//console.log(res.data.data);
            this.setState({
                appRes: res.data.data
            });
        }).catch(err => {console.log(err)
        }).finally(()=>{this.setChart(true)});
    };
    setChart = (refresh = false) => {
        if(refresh) this.setState({ areaChart: ''});
        const { appRes } = this.state;
        const { items, label } = appRes;
        this.setState({
            areaChartM: <LineChart id={1}
                item={{
                  keys: label.map(val => val),
                  values: items.map(val=> ({'name':val.name, 'data': val.items.map(cnt => Number(cnt.mobile_count))}))
                }}
            />,
            areaChartW: <LineChart id={2}
                item={{
                    keys: label.map(val => val),
                    values: items.map(val=> ({'name':val.name, 'data': val.items.map(cnt => Number(cnt.pc_count))}))
                }}
            />
        })
    };
    handleChangeStart(date) {
        let pm = new Promise(resolve => {
            this.setState({
                startDate: date
            });
            resolve();
        });
        pm.then(() => { this.getApi() });
    }
    handleChangeEnd(date) {
        let pm = new Promise(resolve => {
            this.setState({
                endDate: date
            });
            resolve();
        });
        pm.then(() => { this.getApi() });

    }
    toggleHidden = () => {
        this.setState({
            isToggle: !this.state.isToggle
        })
    };

    render() {
        const { appRes, areaChartM, areaChartW } = this.state;
        const { items, label } = appRes;
        const breadcrumb = (
            <ol className="breadcrumb">
                <li className="breadcrumb-item"><a href="/">홈</a></li>
                <li className="breadcrumb-item">트렌드</li>
                <li className="breadcrumb-item active"><strong>키워드</strong></li>
            </ol>
        );
        const contentHead = (
            <div className="card-header">
                <div className="float-left mt-2"><i className='' style={{'marginRight':'3px'}}> </i>키워드</div>
                <div className="float-right">
                    <button	className="btn btn-outline-primary" style={{ width: '120px' }} onClick={this.toggleHidden.bind(this)}>그래프 보기</button>
                </div>
            </div>
        );
        const item = items.map((val,idx) => {
            return (
                <tr key={idx}>
                    <td className="text-left middle text-nowrap">{val.name}</td>
                    {val.items.map((cnt, idx) => {
                        const Icon = (()=>{
                            switch(cnt.status){
                                case 'increased':
                                    return (<span className="tri-ico" style={{'color':'red'}}>▲</span>);
                                case 'decreased':
                                    return (<span className="tri-ico" style={{'color':'blue'}}>▼</span>);
                                default:
                                    break;
                            }
                        })();
                        return (
                            <React.Fragment key={idx}>
                                <td className="text-right text-nowrap">{cnt.mobile_count.toLocaleString()}</td>
                                <td className="text-right text-nowrap">{cnt.pc_count.toLocaleString()}</td>
                                <td className="text-right text-nowrap">
                                    <span className="wrap-block">
                                        <span className="block">{Number(cnt.count).toLocaleString()}</span>
                                        { (cnt.amount !== 0) && <span className="block">({ cnt.amount }%)</span>}
                                    </span>
                                    {Icon}
                                </td>
                            </React.Fragment>
                        )
                    })}
                </tr>
            )
        });
        const contentItems = (
            <table className="table table-responsive-sm table-striped table-hover table-outline mb-0 table-bordered app-table">
                <thead className="thead-light">
                <tr>
                    <th className="text-center middle" rowSpan={2}>키워드</th>
                    {label.map((val,idx) => {
                        return (
                            <th key={idx} className='text-center' colSpan={3}>{val}</th>
                        )
                    })}
                </tr>
                <tr>
                    {label.map((val,idx) => (<React.Fragment key={idx}><th className="text-center text-nowrap title-head">모바일</th><th className="text-center text-nowrap title-head">웹</th><th className="text-center text-nowrap title-head">합계</th></React.Fragment>))}
                </tr>
                </thead>
                <tbody>
                    {item}
                </tbody>
            </table>
        );
        return (
            <main className="main">
                {breadcrumb}
                <div className="container-fluid">
                    <div className="animated fadeIn">
                        <div className="row">
                            <div className="col-md-12">
                                <div className="card">
                                    {contentHead}
                                    <div className="card-body">
                                        <div className="row">
                                            <div className="col-sm-12">
                                                <div className="row pl-3">
                                                    <div className="form-inline form-group">
                                                        <DateFickerCom
                                                            startDate={this.state.startDate}
                                                            endDate={this.state.endDate}
                                                            handleChangeStart={this.handleChangeStart}
                                                            handleChangeEnd={this.handleChangeEnd}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style={{'display': (!this.state.isToggle) ? 'none' : 'block'}}>
                                            <div className="chart-area">
                                                <h2>모바일 월간 조회수</h2>
                                                { areaChartM }
                                            </div>
                                            <div className="chart-area">
                                                <h2>웹 월간 조회수</h2>
                                                { areaChartW }
                                            </div>
                                        </div>
                                        <hr/>
                                        <div className="wrap-table">
                                            {contentItems}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        );
    }
}

export default Keyword;
if (document.getElementById('pf_react_keyword')) {
    ReactDOM.render(<Keyword />, document.getElementById('pf_react_keyword'));
}