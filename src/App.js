import React, { Component } from 'react'
import {BrowserRouter as Router,Switch} from 'react-router-dom'
import Login from './components/pages/Login'
import Dashboard from './components/pages/Dashboard1'
import TopNavigationBar from './components/Navigations/TopNavigationBar'
import Labour from './components/pages/Labour'
import Material from './components/pages/Material'
import AddMaterialPage from './components/pages/AddMaterialPage'
import UpdateMaterialPage from './components/pages/UpdateMaterialPage'
import SignUpPage from './components/pages/SignUpPage'
import ForgotPasswordPage from './components/pages/ForgotPasswordPage'
import ResetPasswordPage from './components/pages/ResetPasswordPage'
import UserRoute from './components/routes/UserRoute'
import GustRoute from './components/routes/GustRoute'
import {connect} from 'react-redux'
class App extends Component {


  render() {
    return (
      <>
       <Router>
       {this.props.isAuthenticated&&<TopNavigationBar/>}
          <Switch>
            <GustRoute path="/" exact component={Login} />
            <GustRoute path="/signup" exact component={SignUpPage} />
           <GustRoute path="/forgotpassword" exact component={ForgotPasswordPage} />
           <GustRoute path="/reset_password/:token" exact component={ResetPasswordPage} />
            <UserRoute path='/dashboard' exact component={Dashboard}/>
            <UserRoute path="/labour" exact component={Labour} />
            <UserRoute path="/material" exact component={Material} />
            <UserRoute path="/addmaterial" exact component={AddMaterialPage} />
            <UserRoute path="/updatematerial" exact component={UpdateMaterialPage} />
          </Switch>
        </Router>
        
      </>
    )
  }
}
const mapstatetoprops=state=>({
  isAuthenticated:!!state.userVerificationReducer.token
})
export default connect(mapstatetoprops)(App)