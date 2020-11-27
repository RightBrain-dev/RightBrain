import React, { Component } from 'react'
import {InlineError} from '../messages/InlineError'
import {signupValidations} from '../../validations/validations'
export default class SignUpForm extends Component {
  state={
    data:{
      name:'',
      mobileNumber:"",
      email:"",
      password:"",
      repassword:"",
    },
    errors:{}
  }
  onchange=e=>
  {
    this.setState({data:{...this.state.data,[e.target.name]:e.target.value}})
  }
  submit=(e)=>
  {
    e.preventDefault()
      const errors= signupValidations(this.state.data)
      this.setState({errors})
    if(Object.keys(errors).length===0)
    {
        this.props.submit(this.state.data)
        .then(()=>this.setState({data:{name:"",mobileNumber:"",email:"",password:"",repassword:""}}))
        .catch((err)=>this.setState({errors:err.response.data.error}))
    }
  }
  render() {
    const {data,errors} =this.state
    return (
      <>
      {errors.global&& <InlineError err={errors.global}/>}
        <form onSubmit={this.submit}>
        {errors.name&&<InlineError err={errors.name}/>}<br/>
        <label>Enter Your Name</label> <span className="text-danger">*</span>
          <input 
          type="text" 
          name="name"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.name}
          onChange={this.onchange}
          />
          {errors.email&&<InlineError err={errors.email}/>}<br/>
          <label>Email</label> <span className="text-danger">*</span>
          <input 
          type="text" 
          name="email"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.email}
          onChange={this.onchange}
          />
          {errors.mobile&&<InlineError err={errors.mobile}/>}<br/>
          <label>Mobile Number</label> <span className="text-danger">*</span>
          <input 
          type="text" 
          name="mobileNumber"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.mobileNumber}
          onChange={this.onchange}
          />
          {errors.password&&<InlineError err={errors.password}/>}<br/>
          <label>Create Password</label> <span className="text-danger">*</span>
          <input 
          type="text" 
          name="password"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.password}
          onChange={this.onchange}
          />
          {errors.repassword&&<InlineError err={errors.repassword}/>}<br/>
          <label>ReEnter Your Password</label> <span className="text-danger">*</span>
          <input 
          type="text" 
          name="repassword"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.repassword}
          onChange={this.onchange}
          />
          
          <div className="text-center mt-4">
          <button type="submit" className="btn btn-primary">SignUp</button>
          </div>
        </form>
      </>
    )
  }
}
