import React from 'react'
import {connect} from 'react-redux'
import {Route,Redirect} from 'react-router-dom'
const GustRoute = ({isAuthenticated,component:Component,...rest}) => (
  <Route {...rest} render={props=>!isAuthenticated?<Component {...props} />:<Redirect to="/dashboard"/>}/>
)

const mapstatetoprops=state=>({
  isAuthenticated:!!state.userVerificationReducer.token})
export default connect(mapstatetoprops)(GustRoute)