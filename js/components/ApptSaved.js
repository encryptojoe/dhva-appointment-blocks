import React from 'react';
import { Grid, Row, Col, Alert} from 'react-bootstrap';

const ApptSaved = React.createClass({
    render() {
        return (
            <Grid>
                <Col sm={12} >
                    <Alert bsStyle="success">
                        <strong>Appointment Saved!</strong>
                    </Alert>
                </Col>
            </Grid>
        );
    }
});

export default ApptSaved;