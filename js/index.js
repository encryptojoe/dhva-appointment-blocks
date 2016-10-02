import React from 'react';
import { render } from 'react-dom';
import { IndexRoute, Router, Route, IndexRedirect, hashHistory } from 'react-router';

import App from './components/App';

import CreateAppt from './components/CreateAppt';
import ListAppts from './components/ListAppts';
import ApptSaved from './components/ApptSaved';
import Waitlisted from './components/Waitlisted';
import Login from './components/Login';

const router = (
    <Router history={hashHistory}>
        <Route path="/" component={App}>
            <IndexRoute component={Login}/>
            <Route path="/create-appointment" component={CreateAppt} />
            <Route path="/appointment-saved" component={ApptSaved} />
            <Route path="/appointment-wait-listed" component={Waitlisted} />
        </Route>
    </Router>
);

render(router, document.getElementById('dhva'));