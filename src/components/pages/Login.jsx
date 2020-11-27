import React, { Component } from "react";
import "../css/login.css";
//import { RemoveScrollBar } from "react-remove-scroll-bar";
import img1 from '../../assets/images/engineer.png'
import LoginForm from '../forms/LoginForm'
import {login} from '../../actions/auth.jsx'
import {connect} from 'react-redux'
import SuccessMessage from '../messages/SuccessMessage'
import { Link } from "react-router-dom/cjs/react-router-dom.min";
class Login extends Component {
 
  state={
    message:""
  }

  submit=credentials=>this.props.login(credentials)
  .then(()=>this.props.history.push('/dashboard'))

getsuccessmessage=(message)=>{ 
  this.setState({message:message})
}

  render() {
    const {message} =this.state
    return (
      <div className="loginpagebody">
      
        <div className="container login_div">
          <div className="row">
            <div className="col-12 col-sm-6 col-md-6 col-lg-8 col-xl-4 col-xxl-4 mx-auto bg-light m-4 ">
                <div className="text-center mt-3 mb-3"><img src={img1}  alt="mypic" style={{height:"45px"}}/></div>
                <div className="pb-4 pt-2"> 
                {message&&<SuccessMessage messsage={message} />}
                  <LoginForm submit={this.submit}/>
                </div>
                <div className="d-inline-block">
                  <Link className="nav-link text-sm" to='/forgotpassword'>Forgot Password?</Link></div>
                <div className="text-right d-inline-block">
                  <Link className="nav-link text-sm" to='/signup'>New User? SignUp?</Link></div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
export default connect(null,{login})(Login)