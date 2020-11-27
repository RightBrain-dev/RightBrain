import React, { Component } from 'react'
import {connect} from 'react-redux'
class AlertMessage extends Component {
  render() {
    const message=this.props.mess
    return (
      <>
        <div className="alert alert-success" role="alert">
          {message}
        </div>
      </>
    )
  }
}
function mapSateToProps (state)
{
return {mess:state.materialcurdMessage.message}
}
export default connect(mapSateToProps)(AlertMessage)