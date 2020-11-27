import React, { Component } from "react";
import "../css/login.css";
import { RemoveScrollBar } from "react-remove-scroll-bar";
import img1 from "../../assets/images/engineer.png";
import ForgotPasswordForm from "../forms/ForgotPasswordForm";
import SuccessMessage from '../messages/SuccessMessage'
import {connect} from 'react-redux'
import {forgotpassword} from '../../actions/auth'
class ForgotPasswordPage extends Component {
  state = {
    success:false,
  };
  submit=data=>this.props.forgotpassword(data).then(()=>this.setState({success:true}))
  render() {
    return (
      <div>
        <div className="loginpagebody">
          <RemoveScrollBar />
          <div className="container login_div">
            <div className="row">
              <div className="col-12 col-sm-6 col-md-6 col-lg-8 col-xl-4 col-xxl-4 mx-auto bg-light m-4 mt-5">
                <div className="text-center mt-3 mb-3">
                  <img src={img1} alt="mypic" style={{ height: "45px" }} />
                </div>
                {this.state.success?<SuccessMessage message="Email Has Been Sent"/>:<ForgotPasswordForm submit={this.submit}/>}

              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
export default connect(null,{forgotpassword})(ForgotPasswordPage)