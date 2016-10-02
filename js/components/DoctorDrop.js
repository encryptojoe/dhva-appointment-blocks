import React from 'react';
import {FormGroup, FormControl, ControlLabel} from 'react-bootstrap';

const DoctorDrop = React.createClass({
    render() {
        if(this.props.rootState.doctors === undefined || this.props.rootState.doctors.length === 0){return <div></div>;}
        
        return (
            <FormGroup controlId="doctor">
                <ControlLabel>Doctor</ControlLabel>
                <FormControl componentClass="select" placeholder="select" onChange={this.props.updatePatientDoc}>
                    <option value="select">Select</option>
                    {
                        this.props.rootState.doctors.map(function(doc, i){
                            return <option key={doc.ID} value={doc.ID}>{doc.FAMILY}, {doc.NAME} ({doc.STAFF_TYPE})</option>;
                        })
                    }
                </FormControl>
            </FormGroup>
        );
    }
});

export default DoctorDrop;