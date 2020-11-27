import React, { Component } from 'react'
import { Link } from 'react-router-dom'

export default class SuccessMessage extends Component {
  render() {
    return (
      <div className="alert alert-success" role="alert">
          {this.props.message} {this.props.gotologin&& <Link to={'/'}>Go To Login</Link>}
        </div>
    )
  }
}
