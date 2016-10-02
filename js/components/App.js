import React from 'react';
import {Grid} from 'react-bootstrap';
import moment from 'moment';

const App = React.createClass({
    getInitialState: function() {
        return {
            user:{
                name:'John Doe', 
                id:13243234
            }, 
            date:moment()
        }
    },
    updateDate:function(d){
        this.setState({date:d});
    },
    render:function(){
        const childrenWithProps = React.Children.map(this.props.children,
             (child) => React.cloneElement(child, {
                rootState:this.state,
                updateDate:this.updateDate
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