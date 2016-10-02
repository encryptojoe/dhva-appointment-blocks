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
           cstate.appointment.doctor > -1 &&
           cstate.appointment.time != '00:00:00'
        ){return true;} else {return false;}
    },
    dayTimePickShow:function(){
        let cstate = this.props.rootState;
        if(cstate.appointment.department > -1 && cstate.appointment.doctor > -1){
            return true;
        } else {return false;}
    },
    render:function(){
        const btns = <FormGroup controlId="buttons">
            <ButtonToolbar> <Button>Cancel</Button> {(this.saveReady()) ? <Button bsStyle="primary">Create</Button> : <span></span>} </ButtonToolbar> </FormGroup>;

        const dateTimePick = (this.dayTimePickShow()) ? 
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
                        Times will go here
                    </Col>
                </Row>
            </FormGroup>
             : <div></div>;

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

                        {btns}

                        <FormGroup controlId="calendar">
                            <Calendar
                                onNextMonth={() => this.props.updateDate(this.props.rootState.date.clone().add(1, 'months') ) }
                                onPrevMonth={() => this.props.updateDate(this.props.rootState.date.clone().subtract(1, 'months') ) }
                                date={this.props.rootState.date}
                                onPickDate={(date) => console.log(date)}
                                renderDay={(day) => day.format('D')}
                                {...this.props}
                            />
                        </FormGroup>
                    </Form>
                </Col>
            </Panel>
        );
    }
});

export default CreateAppt;