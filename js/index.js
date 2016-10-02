import React from 'react';
import { render } from 'react-dom';
import { IndexRoute, Router, Route, IndexRedirect, hashHistory } from 'react-router';

import App from './components/App';

import CreateAppt from './components/CreateAppt';
import ListAppts from './components/ListAppts';
import ApptSaved from './components/ApptSaved';

const router = (
    <Router history={hashHistory}>
        <Route path="/" component={App}>
            <IndexRoute component={CreateAppt}/>
            <Route path="/appointment-list" component={ListAppts} />
            <Route path="/appointment-saved" component={ApptSaved} />
        </Route>
    </Router>
);

render(router, document.getElementById('dhva'));