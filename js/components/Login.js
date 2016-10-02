import React from 'react';
import {Grid, Row, Col, Button, FormGroup, FormControl, ControlLabel, Well } from 'react-bootstrap';
import { IndexRoute, Router, Route, IndexRedirect, hashHistory } from 'react-router';

const Login =  React.createClass({
    handleLogin:function(event){
        hashHistory.push('/create-appointment');
    },
    render() {
        
        return (
            <Grid>
                <br/><br/><br/>
                <Row>
                     <Col sm={6} smOffset={3} xs={12}>
                        <Well>
                            <form onSubmit={this.handleLogin}>
                                <FormGroup controlId="user">
                                    <ControlLabel>User</ControlLabel>
                                    <FormControl type="text"/>
                                </FormGroup>
                                <FormGroup controlId="pass">
                                    <ControlLabel>Password</ControlLabel>
                                    <FormControl type="password" />
                                </FormGroup>
                                <FormGroup controlId="pass">
                                    <Button bsStyle='primary' bsSize='large' block type="submit" onClick={this.handleLogin}><i className="fa fa-sign-in" aria-hidden="true"></i> Login</Button>
                                </FormGroup>
                            </form>
                        </Well>
                    </Col>
                </Row>
            </Grid>
        );
    }
});

export default Login;
