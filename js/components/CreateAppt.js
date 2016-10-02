import React from 'react';
import moment from 'moment';
import {Panel, Row, Col, Form, FormGroup, FormControl, ControlLabel, ButtonToolbar, Button} from 'react-bootstrap';
import Calendar from './Calendar';

const CreateAppt = React.createClass({
    render:function(){
        return (
            <Panel bsStyle="primary" header="Create New Appointment">
                <Col sm={12}>
                    <Form horizontal>
                        <FormGroup controlId="contactMethod">
                            <ControlLabel>Method of contact</ControlLabel>
                            <FormControl componentClass="select" placeholder="select">
                                <option value="select">Select</option>
                                <option value="ph-in">Phone Call - In Bound</option>
                                <option value="ph-out">Phone Call - Out Bound</option>
                            </FormControl>
                        </FormGroup>

                        <FormGroup controlId="department">
                            <ControlLabel>Department</ControlLabel>
                            <FormControl componentClass="select" placeholder="select">
                                <option value="select">Select</option>
                                <option value="cardiology">Cardiology</option>
                                <option value="diagnostic-imaging">Diagnostic Imaging</option>
                            </FormControl>
                        </FormGroup>

                        <FormGroup controlId="doctor">
                            <ControlLabel>Doctor</ControlLabel>
                            <FormControl componentClass="select" placeholder="select">
                                <option value="select">Select</option>
                                <option value="noga-schoorl">Noga Schoorl</option>
                                <option value="shay-du">Shay Du</option>
                                <option value="iman-nibhanupudi">Iman Nibhanupudi</option>
                                <option value="enfys-meisner">Enfys Meisner</option>
                            </FormControl>
                        </FormGroup>

                        <FormGroup controlId="calendar">
                            <Calendar
                                onNextMonth={() => this.props.updateDate(this.props.rootState.date.clone().add(1, 'months') ) }
                                onPrevMonth={() => this.props.updateDate(this.props.rootState.date.clone().subtract(1, 'months') ) }
                                date={this.props.rootState.date}
                                onPickDate={(date) => console.log(date)}
                                renderDay={(day) => day.format('D')}
                            />
                        </FormGroup>

                        <FormGroup controlId="buttons">
                            <ButtonToolbar>
                                 <Button>Cancel</Button>
                                 <Button bsStyle="primary">Create</Button>
                            </ButtonToolbar>
                        </FormGroup>
                    </Form>
                </Col>
            </Panel>
        );
    }
});

export default CreateAppt;