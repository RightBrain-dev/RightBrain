import React, { Component } from 'react'

export default class ErrorMessage extends Component {
  render() {
    return (
      <div className="alert alert-danger" role="alert">
          {this.props.message}
        </div>
    )
  }
}
