import React from 'react'
import {connect} from 'react-redux'
import {Route,Redirect} from 'react-router-dom'
const UserRoute = ({isAuthenticated,component:Component,...rest}) => (
  <Route {...rest} render={rest=>isAuthenticated?<Component {...rest} />:<Redirect to="/"/>}/>
)

const mapstatetoprops=state=>({
  isAuthenticated:!!state.userVerificationReducer.token})
export default connect(mapstatetoprops)(UserRoute)