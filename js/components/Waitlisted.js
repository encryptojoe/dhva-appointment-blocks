import React from 'react';
import { Grid, Row, Col, Alert} from 'react-bootstrap';

const Waitlisted = React.createClass({
    render() {
        return (
            <Grid>
                <Col sm={12} >
                    <Alert bsStyle="success">
                        <strong>Saved to waitlist!</strong>
                    </Alert>
                </Col>
            </Grid>
        );
    }
});

export default Waitlisted;