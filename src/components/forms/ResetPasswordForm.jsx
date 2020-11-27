import React, { Component } from 'react'
import {ResetPasswordValidations} from '../../validations/validations'
import { InlineError } from '../messages/InlineError'
import SuccessMessage from '../messages/SuccessMessage'
export default class ResetPasswordForm extends Component {
  state={
    data:{
      password:"",
      repassword:"",
      token:this.props.token
    },
    errors:{},
    success:false
  }

  onchange=e=>
  {
    this.setState({data:{...this.state.data,[e.target.name]:e.target.value}})
   
  }
  submit=e=>
  {
    e.preventDefault();
    const errors=ResetPasswordValidations(this.state.data)
      this.setState({errors});
      if(Object.keys(errors).length===0)
      {
        this.props.submit(this.state.data)
        .then(()=>this.setState({success:true}))
        .catch((res)=>this.setState({errors:(res.response.data)}))
      }
  }
  render() {
    const {data,errors,success}=this.state
    return (
      <>
       <h4>Reset Your Password Here</h4>
       {success&&<SuccessMessage message="Your Password Has Been Changed " gotologin="true"/>}
       {errors.message&&<InlineError err={errors.message}/>}
       <form  onSubmit={this.submit}>
         {errors.password&&<InlineError err={errors.password}/>} <br/>
       <label>Enter Your New Password</label> <span className="text-danger">*</span>
          <input 
          type="password" 
          name="password"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.password}
          onChange={this.onchange}
          />
          {errors.repassword&&<InlineError err={errors.repassword}/>}<br/>
          <label>ReEnter Your New Password, Please</label> <span className="text-danger">*</span>
          <input 
          type="password" 
          name="repassword"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.repassword}
          onChange={this.onchange}
          />
          <div className="text-center mt-4">
          <button type="submit" className="btn btn-primary">Login</button>
          </div>
      </form>
      </>
    )
  }
}
