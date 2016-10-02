import React from 'react';
import {Grid} from 'react-bootstrap';
const App = React.createClass({
    getInitialState: function() {
        return {user:{name:'John Doe', id:13243234}, 
            events:[
                {
                    start: '2015-07-20',
                    end: '2015-07-02',
                    eventClasses: 'optionalEvent',
                    title: 'test event',
                    description: 'This is a test description of an event',
                },
                {
                    start: '2015-07-19',
                    end: '2015-07-25',
                    title: 'test event',
                    description: 'This is a test description of an event',
                    data: 'you can add what ever random data you may want to use later',
                }
            ]
        }
    },
    render:function(){
        const childrenWithProps = React.Children.map(this.props.children,
             (child) => React.cloneElement(child, {
                rootState:this.state
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