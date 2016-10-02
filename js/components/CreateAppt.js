import React from 'react';
import moment from 'moment';
import {Panel, Row, Col, Form, FormGroup, FormControl, ControlLabel, ButtonToolbar, Button} from 'react-bootstrap';
import DatePicker from 'react-datepicker';
import Calendar from './Calendar';
import DoctorDrop from './DoctorDrop';

const CreateAppt = React.createClass({
    render:function(){
        const btns = (false) ? <FormGroup controlId="buttons"><ButtonToolbar> <Button>Cancel</Button> <Button bsStyle="primary">Create</Button> </ButtonToolbar> </FormGroup> : <div></div>;

        const datePick = (this.props.rootState.appointment.doctor > -1) ? <DatePicker inline selected={this.props.rootState.appointment.date} minDate={moment()} onChange={this.props.updateApptDate} /> : <div></div>;

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

                        <FormGroup controlId="date-pick">
                            {datePick}
                        </FormGroup>

                        <FormGroup controlId="btns">
                            {btns}
                        </FormGroup>

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