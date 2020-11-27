import React, { Component } from 'react'
import {InlineError} from '../messages/InlineError'
export default class ForgotPasswordForm extends Component {
  state={
    data:{
      email:""
    },
    errors:""
  }
  onchange=e=>
  {
    this.setState({data:{email:e.target.value}})
    console.log(this.state.data.email)
  }
  onsubmit=e=>
  {
    e.preventDefault()
    this.props.submit(this.state.data)
    .catch(err=>this.setState({errors:err.response.data.error}))
  }
  render() {
    const {data,errors}=this.state
    return (
      <div>
        <h3>Reset Your Password</h3>
        {errors.global&&<InlineError err={errors.global}/>}
       <form onSubmit={this.onsubmit}>
       <label>Enter your Registerd Email</label> <span className="text-danger">*</span>
          <input 
          type="email" 
          name="email"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.email}
          onChange={this.onchange}
          />
          <div className="text-center mt-4 mb-3">
          <button type="submit" className="btn btn-link">Send Reset Password link</button>
          </div>
       </form>
      </div>
    )
  }
}
