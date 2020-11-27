import React, { Component } from 'react'
import { InlineError } from '../messages/InlineError'
import {loginValidations} from '../../validations/validations'
export default class LoginForm extends Component {

  state={
    
    data:{
      userEmail:"rightbrain414@gmail.com",
      userPassword:"12345"
    },
    success:false,
    errors:{}
  }

  onchange=e=>
  {
    this.setState({data:{...this.state.data,[e.target.name]:e.target.value}})
  }
  onsubmit=(e)=>
  {
      e.preventDefault()
 
        const errors=loginValidations(this.state.data)
        this.setState({errors})
        if(Object.keys(errors).length===0)
        {
         this.props.submit(this.state.data)
         .catch(err=>this.setState({errors:err.response.data.error}))
        }
  }


  render() {
    const {data,errors}=this.state
    return (
      <>
        {errors.global&&<InlineError err={errors.global}/>}
        <form onSubmit={this.onsubmit}>
          {errors.email&&<InlineError err={errors.email}/>}<br/>
          <label>Email</label> <span className="text-danger">*</span>
          <input 
          type="text" 
          name="userEmail"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.userEmail}
          onChange={this.onchange}
          />
          {errors.password &&<InlineError err={errors.password}/>}<br/>
          <label>Password</label> <span className="text-danger">*</span>
          <input 
          type="password" 
          name="userPassword"
          className="form-control form-control-sm"
          autoComplete="off"
          value={data.userPassword}
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
