import React from 'react';
import {Grid} from 'react-bootstrap';
import moment from 'moment';
import jQuery from 'jquery';
import { IndexRoute, Router, Route, IndexRedirect, hashHistory } from 'react-router';

const App = React.createClass({
    getInitialState: function() {
        return {
            user:{
                name:'Dr Doe', 
                id:10
            },
            date:moment(),
            departments:[],
            doctors:[],
            appTypes:[],
            patient:{
                name:'',
                family:'',
                pid:-1,
            },
            appointment:{
                department:-1,
                doctor:0,
                date:moment(),
                time:'00:00',
                type:'',
                local:-1
            }
        }
    },
    updateDate:function(d){
        this.setState({date:d});
    },
    get_appointment_type:function(){
        jQuery.post('api.php', {func:'get_appointment_type'}, function(data){
            console.log(data);
        }.bind(this));
    },
    updatePatientDoc:function(val){
        let docs                = this.state;
        docs.appointment.doctor = Number(val.target.value);        
        this.setState(docs);
    },
    pull_departments:function(){
        jQuery.post('api.php', {func:'get_department_type', staff_id:this.state.user.id}, function(data){
            this.setState({departments:data});
        }.bind(this));
    },
    update_patient_department:function(e){
        let oldState = this.state;
        oldState.appointment.department = Number(e.target.value);
        if(Number(e.target.value))
        jQuery.post('api.php',{func:'get_staff', department_id:Number(e.target.value)}, function(data){
            oldState.doctors = data;
            this.setState(oldState);
        }.bind(this));
    },
    updateApptDate:function(e){
        let oldState = this.state;
        oldState.appointment.date = e;
        this.setState(oldState);
    },
    updateTime:function(e){
        let oldState = this.state;
        oldState.appointment.time = e.target.value;
        this.setState(oldState);        
    },
    saveAppointment:function(){
        console.log("I'll go save the appointment now");
            hashHistory.push('/appointment-saved');
        jQuery.post('api.php',{},function(data){
        });
    },
    saveWaitList:function(){
        hashHistory.push('/appointment-wait-listed');
    },
    componentWillMount:function(){
        this.pull_departments();
    },
    render:function(){
        const childrenWithProps = React.Children.map(this.props.children,
             (child) => React.cloneElement(child, {
                rootState:this.state,
                updateDate:this.updateDate,
                updateState:this.updateState,
                update_patient_department:this.update_patient_department,
                updatePatientDoc:this.updatePatientDoc,
                updateApptDate:this.updateApptDate,
                updateTime:this.updateTime,
                saveWaitList:this.saveWaitList,
                saveAppointment:this.saveAppointment
            })
        );

        return (
            <Grid>
                {childrenWithProps}
            </Grid>
        );
    }
});

export default App;
