import React from 'react';
import { render } from 'react-dom';
import { IndexRoute, Router, Route, IndexRedirect, hashHistory } from 'react-router';

import App from './components/App';

// import Calendar from './components/Calendar';
import CreateAppt from './components/CreateAppt';
import ListAppts from './components/ListAppts';

const router = (
    <Router history={hashHistory}>
        <Route path="/" component={App}>
            <IndexRoute component={CreateAppt}/>
            <Route path="/appointment-list" component={ListAppts} />
        </Route>
    </Router>
);

render(router, document.getElementById('dhva'));