import React from 'react';
import {FormGroup, FormControl, ControlLabel} from 'react-bootstrap';

const DoctorDrop = React.createClass({
    render() {
        return (
            <div></div>
        );
        if(this.props.doctors.length === 0 || this.props.doctors === undefined){return <div></div>;}
        
        return (
            <FormGroup controlId="doctor">
                <ControlLabel>Doctor</ControlLabel>
                <FormControl componentClass="select" placeholder="select">
                    <option value="select">Select</option>
                    {
                        this.props.doctors.map(function(doc, i){
                            // console.log(doc);
                            return null;
                        })
                    }
                </FormControl>
            </FormGroup>
        );
    }
});

export default DoctorDrop;