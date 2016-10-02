import React from 'react';
import {Grid} from 'react-bootstrap';
import moment from 'moment';
import jQuery from 'jquery';

const App = React.createClass({
    getInitialState: function() {
        return {
            user:{
                name:'John Doe', 
                id:13243234
            },
            date:moment(),
            departments:[],
            doctors:[],
            patient:{
                department:-1,
                doctor:-1,
                date:'0000-00-00',
                time:'00:00:00'
            }
        }
    },
    updateDate:function(d){
        this.setState({date:d});
    },
    updatePatientDoc:function(val){
        let docs       = this.state;
        patient.doctor = val;
        
        this.setState(docs);
    },
    pull_departments:function(){
        jQuery.post('api.php', {func:'pull_departments'}, function(data){
            this.setState({departments:data});
        }.bind(this));
    },
    componentWillMount:function(){
        this.pull_departments();
    },
    render:function(){
        const childrenWithProps = React.Children.map(this.props.children,
             (child) => React.cloneElement(child, {
                rootState:this.state,
                updateDate:this.updateDate,
                updateState:this.updateState
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