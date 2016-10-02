import React from 'react';
import moment from 'moment';
import {Panel, Row, Col, Form, FormGroup, FormControl, ControlLabel, ButtonToolbar, Button} from 'react-bootstrap';
// import DatePicker from 'react-datepicker';
import Calendar from './Calendar';
import DoctorDrop from './DoctorDrop';
import R from 'ramda';

import DateTimePick from './DateTimePick';

const CreateAppt = React.createClass({
    saveReady:function(){
        let cstate = this.props.rootState;
        
        if(cstate.appointment.department > -1 &&
           cstate.appointment.time != '00:00:00'
        ){return true;} else {return false;}
    },
    
    
    render:function(){
        
        
        const CalAvail     = (this.props.rootState.appointment.department == -1) ? <FormGroup controlId="calendar"></FormGroup>: <FormGroup controlId="calendar">
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
                    <h2>Patient: Joanna</h2>
                    <Form horizontal>

                        <FormGroup controlId="department">
                            <ControlLabel>Department</ControlLabel>
                            <FormControl componentClass="select" placeholder="select" onChange={this.props.update_patient_department}>
                                <option value="-1">Select</option>
                                {
                                    this.props.rootState.departments.map(function(dept, i){
                                        return <option key={dept.ID} value={dept.ID}>{dept.DEPARTMENT}</option>
                                    })
                                }
                            </FormControl>
                        </FormGroup>

                        <DoctorDrop {...this.props} />

                        <DateTimePick {...this.props}/>

                        {CalAvail}
                    </Form>
                </Col>
            </Panel>
        );
    }
});

export default CreateAppt;