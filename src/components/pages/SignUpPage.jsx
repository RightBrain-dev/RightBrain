import React, { Component } from "react";
import "../css/login.css";
import { RemoveScrollBar } from "react-remove-scroll-bar";
import img1 from '../../assets/images/engineer.png'
import SignUpForm from '../forms/SignUpForm'
import {signup} from '../../actions/auth'
import {connect} from 'react-redux'
import { Link } from "react-router-dom/cjs/react-router-dom.min";
import SuccessMessage from '../messages/SuccessMessage'
class SignUpPage extends Component {
  state={
    message:""
  }
  submit=data=>this.props.signup(data).then((res)=>{
    this.setState({message:(res.res.message)})
    this.props.history.push('/signup')})

  render() {
    const {message} =this.state
    return (
      <div className="loginpagebody">
       <RemoveScrollBar/>
        <div className="container signup_div">
          <div className="row">
            <div className="col-12 col-sm-6 col-md-6 col-lg-8 col-xl-4 col-xxl-4 mx-auto bg-light">
                <div className="text-center mt-3 mb-1"><img src={img1}  alt="mypic" style={{height:"45px"}}/></div>
                <div className="pb-2 pt-1">
                  {message&&<SuccessMessage message={message} gotologin="Go To Login" />} 
                  <SignUpForm submit={this.submit}/>
                </div>
                <div className="text-right">
                  <Link className="nav-link text-sm" to='/'>already User? SignIn</Link></div>
                  
            </div>
          </div>
        </div>
      </div>
    );
  }
}
export default connect(null,{signup})(SignUpPage)