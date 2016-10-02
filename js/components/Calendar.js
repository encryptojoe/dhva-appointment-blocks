import React, { Component, PropTypes } from 'react';
import moment from 'moment';
import createDateObjects from './createDateObjects';
import R from 'ramda';

/*base taken from https://github.com/Hanse/react-calendar He deserves the credit. I have modified this file*/
export default class Calendar extends Component {

    static propTypes = {
        weekOffset: PropTypes.number.isRequired,
        date: PropTypes.object.isRequired,
        renderDay: PropTypes.func,
        onNextMonth: PropTypes.func.isRequired,
        onPrevMonth: PropTypes.func.isRequired,
        onPickDate: PropTypes.func
    }

    static defaultProps = {
        weekOffset: 0,
        renderDay: day => day.format('YYYY-MM-D'),
    }

    render() {
        const { date, weekOffset, renderDay, onNextMonth, onPrevMonth, onPickDate } = this.props;

        return (
            <div className='Calendar'>
                <div className='Calendar-header'>
                    <button onClick={onPrevMonth}>&laquo;</button>
                    <div className='Calendar-header-currentDate'>{date.format('MMMM YYYY')}</div>
                        <button onClick={onNextMonth}>&raquo;</button>
                    </div>
                    <div className='Calendar-grid'>
                        {createDateObjects(date, weekOffset).map((day, i) =>
                        <div
                            key={`day-${i}`}
                            className={`Calendar-grid-item ${day.classNames || ''}`}
                            onClick={(e) => onPickDate(day.day)}
                        >
                        <span className="calendar-day-numerical">{renderDay(day.day)}</span>{(!R.has('classNames', day)) ? <span><br/> Available: {Math.round(Math.random()*10) + 1}</span> : <span><br/></span>}
                    </div>
                    )}
                </div>
            </div>
    );
  }
}