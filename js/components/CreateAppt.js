import React from 'react';
import moment from 'moment';
import {Panel, Row, Col, Form, FormGroup, FormControl, ControlLabel, ButtonToolbar, Button} from 'react-bootstrap';
import DatePicker from 'react-datepicker';
import Calendar from './Calendar';
import DoctorDrop from './DoctorDrop';
import R from 'ramda';

const CreateAppt = React.createClass({
    saveReady:function(){
        let cstate = this.props.rootState;
        
        if(cstate.appointment.department > -1 &&
           cstate.appointment.time != '00:00:00'
        ){return true;} else {return false;}
    },
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
            d = new Date(d.getYear(), d.getMonth(), d.getDay(), h, m, 0, 0);

            // if (idx > 0) result += ", ";
            let ctime = ("0" + h).slice(-2) + ":" + ("0" + m).slice(-2);
            result.push(ctime);
            
            d = addMinutes(d, 15);
        }
        return result;
    },
    render:function(){
        const slots        = this.genTimeSlots();
        const cbs          = {marginTop:10};
        // const btns         = <FormGroup controlId="buttons"><ButtonToolbar>  {(this.saveReady()) ? <Button bsStyle ="primary">Create</Button> : <span></span>} </ButtonToolbar> </FormGroup>;
        const dateTimePick = (this.dayTimePickShow()) ? 
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
                                <option value="00:00">Select</option>
                                {
                                    slots.map(function(time, i){
                                        return <option key={time}>{time}</option>
                                    })
                                }
                            </FormControl>
                            {(this.props.rootState.appointment.time != '00:00') ? <Button style={cbs} bsStyle="primary">Create</Button> : <div></div>}
                        </Col>
                    </Row>

                </FormGroup>
            </div>
             : <div></div>;

            const CalAvail = (this.props.rootState.appointment.department == -1) ? <FormGroup controlId="calendar"></FormGroup>: <FormGroup controlId="calendar">
                            <Calendar
                                onNextMonth={() => this.props.updateDate(this.props.rootState.date.clone().add(1, 'months') ) }
                                onPrevMonth={() => this.props.updateDate(this.props.rootState.date.clone().subtract(1, 'months') ) }
                                date={this.props.rootState.date}
                                onPickDate={(date) => console.log(date)}
                                renderDay={(day) => day.format('D')}
                                {...this.props}
                            />
                        </FormGroup>;
        return (
            <Panel bsStyle="primary" header="Create New Appointment">
                <Col sm={12}>
                    <h3>Patient: Joanna</h3>
                    <Form horizontal>

                        <FormGroup controlId="department">
                            <ControlLabel>Department</ControlLabel>
                            <FormControl componentClass="select" placeholder="select" onChange={this.props.update_patient_department}>
                                <option value="select">Select</option>
                                {
                                    this.props.rootState.departments.map(function(dept, i){
                                        return <option key={dept.ID} value={dept.ID}>{dept.DEPARTMENT}</option>
                                    })
                                }
                            </FormControl>
                        </FormGroup>

                        <DoctorDrop {...this.props} />

                        {dateTimePick}

                        {CalAvail}
                    </Form>
                </Col>
            </Panel>
        );
    }
});

export default CreateAppt;