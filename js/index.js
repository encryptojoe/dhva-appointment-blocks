import React from 'react';
import { render } from 'react-dom';
import { IndexRoute, Router, Route, IndexRedirect, hashHistory } from 'react-router';

import App from './components/App';
// import Calendar from './components/Calendar';
import CreateAppt from './components/CreateAppt';

const router = (
    <Router history={hashHistory}>
        <Route path="/" component={App}>
            <IndexRoute component={CreateAppt}/>
        </Route>
    </Router>
);

render(router, document.getElementById('dhva'));