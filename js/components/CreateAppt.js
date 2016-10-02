import React from 'react';
import moment from 'moment';
import {Panel, Row, Col, Form, FormGroup, FormControl, ControlLabel, ButtonToolbar, Button} from 'react-bootstrap';
import Calendar from './Calendar';
import DoctorDrop from './DoctorDrop';

// <FormGroup controlId="contactMethod">
    // <ControlLabel>Method of contact</ControlLabel>
    // <FormControl componentClass="select" placeholder="select">
        // <option value="select">Select</option>
        // <option value="ph-in">Phone Call - In Bound</option>
        // <option value="ph-out">Phone Call - Out Bound</option>
    // </FormControl>
// </FormGroup>

const CreateAppt = React.createClass({
    render:function(){
        const btns = (false) ? <FormGroup controlId="buttons"><ButtonToolbar> <Button>Cancel</Button> <Button bsStyle="primary">Create</Button> </ButtonToolbar> </FormGroup> : <div></div>;

        return (
            <Panel bsStyle="primary" header="Create New Appointment">
                <Col sm={12}>
                    <h3>Patient: Joanna</h3>
                    <Form horizontal>

                        <FormGroup controlId="department">
                            <ControlLabel>Department</ControlLabel>
                            <FormControl componentClass="select" placeholder="select">
                                <option value="select">Select</option>
                                {
                                    this.props.rootState.departments.map(function(dept, i){
                                        return <option key={dept.ID} value={dept.ID}>{dept.DEPARTMENT}</option>
                                    })
                                }
                            </FormControl>
                        </FormGroup>

                        <DoctorDrop {...this.props} />

                        <FormGroup controlId="calendar">
                            <Calendar
                                onNextMonth={() => this.props.updateDate(this.props.rootState.date.clone().add(1, 'months') ) }
                                onPrevMonth={() => this.props.updateDate(this.props.rootState.date.clone().subtract(1, 'months') ) }
                                date={this.props.rootState.date}
                                onPickDate={(date) => console.log(date)}
                                renderDay={(day) => day.format('D')}
                            />
                        </FormGroup>

                        {btns}
                    </Form>
                </Col>
            </Panel>
        );
    }
});

export default CreateAppt;