import React, { Component } from 'react'
import {connect} from 'react-redux'
import "../css/login.css";
import { RemoveScrollBar } from "react-remove-scroll-bar";
import {validateToken} from '../../actions/auth'
import img1 from '../../assets/images/engineer.png'
import SuccessMessage from '../messages/SuccessMessage';
import ErrorMessage from '../messages/ErrorMessage';
import ResetPasswordForm from '../forms/ResetPasswordForm';
import {resetpassword} from '../../actions/auth'
class ResetPasswordPage extends Component {
  state={
    loading:true,
    success:false,
    error:{}
  }
  componentDidMount(){
  this.props.validateToken(this.props.match.params.token)
  .then(()=>this.setState({loading:false,success:true}))
  .catch((res)=>this.setState({loading:false,success:false,error:res.response.data}))
  }

  submit=data=>this.props.resetpassword(data)

  render() {
    const {loading,success,error}=this.state
    return (
      <>

     <div className="loginpagebody">
        <RemoveScrollBar/>
        <div className="container login_div">
          <div className="row">
            <div className="col-12 col-sm-6 col-md-6 col-lg-8 col-xl-4 col-xxl-4 mx-auto bg-light m-4 ">
                <div className="text-center mt-3 mb-3"><img src={img1}  alt="mypic" style={{height:"45px"}}/></div>
                <div className="pb-4 pt-2"> 
                 {loading&&<SuccessMessage message="Loading..."/>}     
                  {!loading&&success&&<ResetPasswordForm submit={this.submit} token={this.props.match.params.token}/>}
                 {!loading&&!success&&<ErrorMessage message={error.message}/>}
                </div>
                </div>
          </div>
        </div>
      </div>
      </>
    )
  }
}
export default connect(null,{validateToken,resetpassword})(ResetPasswordPage)