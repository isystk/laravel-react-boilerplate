import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Example extends Component {

    constructor() {
        super();

        this.state = {
            likes: []
        };

    }

    componentDidMount() {
        fetch('/api/likes')
            .then(response => {
                return response.json();
            })
            .then(objects => {
                this.setState({ likes: objects.likes });
            });
    }

    renderLikes() {
        return this.state.likes.map((like, index) => {
            return (
                <div key={index}>
                    {like}<br />
                </div>
            );
        });
    }

    render() {
        return (
            <div className="container">
                <div className="row">
                    <div className="col-md-8 col-md-offset-2">
                        <div className="panel panel-default">
                            <div className="panel-heading">追加されたお気に入り</div>

                            <div className="panel-body">
                                <ul>
                                    {this.renderLikes()}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}


if (document.getElementById('app')) {
    ReactDOM.render(<Example />, document.getElementById('app'));
}
