import React from 'react';
import { FormGroup, Row, Col, ControlLabel, FormControl, Button} from 'react-bootstrap';
import moment from 'moment';
import DatePicker from 'react-datepicker';

const DateTimePick = React.createClass({
    dayTimePickShow:function(){
        let cstate = this.props.rootState;
        if(cstate.appointment.department > -1){
            return true;
        } else {return false;}
    },
    genTimeSlots:function(){
        function addMinutes(date, minutes) {
            return new Date(date.getTime() + minutes*60000);
        }
        let d = new Date();
        let result = new Array();
        for (let idx = 0; idx < 10; idx++) {
            let m = (((d.getMinutes() + 7.5)/15 | 0) * 15) % 60;
            let h = ((((d.getMinutes()/105) + .5) | 0) + d.getHours()) % 24;
            d     = new Date(d.getYear(), d.getMonth(), d.getDay(), h, m, 0, 0);

            let ctime = ("0" + h).slice(-2) + ":" + ("0" + m).slice(-2);
            result.push(ctime);
            
            d = addMinutes(d, 15);
        }
        return result;
    },
    render() {
        if(!this.dayTimePickShow()){return <div></div>;}

        const slots = this.genTimeSlots();
        const cbs   = {marginTop:10};

        if(this.props.rootState.appointment.time != '00:00' && this.props.rootState.appointment.time != 'waitlist'){
            var btns = <Button style={cbs} bsStyle="primary" onClick={this.props.saveAppointment}>Create</Button>;
        } else if(this.props.rootState.appointment.time == 'waitlist'){
            var btns = <Button bsStyle="danger" style={cbs} onClick={this.props.saveWaitList}>Add to Waitlist</Button>;
        } else {
            var btns = <div></div>;
        }

        return (
            <div>
                <FormGroup controlId="date-pick">
                    <Row>
                        <Col sm={5}>
                            <ControlLabel>Pick Day</ControlLabel>
                        </Col>
                        <Col sm={5}>
                            <ControlLabel>Pick Time</ControlLabel>
                        </Col>
                    </Row>
                    <Row>
                        <Col sm={5}>
                            <DatePicker inline selected={this.props.rootState.appointment.date} minDate={moment()} onChange={this.props.updateApptDate} />
                        </Col>
                        <Col sm={5}>
                            <FormControl componentClass="select" placeholder="select" onChange={this.props.updateTime}>
                                <option value="00:00"></option>
                                {
                                    slots.map(function(time, i){
                                        return <option key={time}>{time}</option>
                                    })
                                }
                                <option value="waitlist">No Avail Times that work</option>                                
                            </FormControl>
                            {btns}
                        </Col>
                    </Row>
                </FormGroup>
            </div>
        );
    }
});

export default DateTimePick;