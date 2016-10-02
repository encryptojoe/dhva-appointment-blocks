import React from 'react';
// import EventCalendar from 'react-event-calendar';
import moment from 'moment';
// BigCalendar.momentLocalizer(moment);
import {Table} from 'react-bootstrap';

const Calendar = React.createClass({
    createMonth:function(){
        let startOfMonth = moment().startOf('week');
        let endOfMonth = moment().endOf('month');

        let days = [];
        let day = startOfMonth;

        while (day <= endOfMonth) {
            days.push(day.toArray().slice(0,3));
            day = day.clone().add(1, 'd');
        }

        return days;
    },
    render:function(){
        let dates = this.createMonth();
        return (
            <div>
                <Table responsive>
                    <thead>
                        <tr>
                            <td>Sun</td>
                            <td>Mon</td>
                            <td>Tues</td>
                            <td>Wed</td>
                            <td>Thur</td>
                            <td>Fri</td>
                            <td>Sat</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </Table>               
            </div>
        );
    }
});

export default Calendar;