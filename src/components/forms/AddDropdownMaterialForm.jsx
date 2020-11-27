import React, { Component } from 'react'
import {add_dropdown_material_validations} from '../../validations/validations'
import {InlineError} from '../messages/InlineError'
import {connect} from 'react-redux'
class AddDropdownMaterialForm extends Component {
  state={
    data:{
      materialName:"",
      materialCompany:"",
      materialUnit:"",
      materialSize:"",
      token:this.props.token
    },
    closemodal:"",
    errors:{}
  }

  onchange=(e)=>{
   this.setState({data:{...this.state.data,[e.target.name]:e.target.value}})
    //console.log(this.state.data)
  }
  
  onsubmit=e=>{
    e.preventDefault()
    const errors =add_dropdown_material_validations(this.state.data)
    this.setState({errors})
    if(Object.keys(errors).length===0)
    {
      
    this.props.submit(this.state.data)
    .then(()=>{

     // 
      //this.setState({materialName:"",materialCompany:"",materialUnit:"",materialSize:""})
    })
  }else{
    return false
  }
  }
  render() {
    const {data,errors}=this.state
    return (
      <div>
       <form onSubmit={this.onsubmit}> 
       
       {errors.materialName&&<InlineError err={errors.materialName}/>}<br/>
         <label>Enter Material Name</label><span className="text-danger font-weight-bold">*</span>
         <input type="text" 
         className="form-control form-control-sm"
         name="materialName"
         autoComplete="off"
         value={data.materialName}
         onChange={this.onchange}
         />
         <label>Company Name</label><span className="text-danger font-weight-bold">*</span>
         <input type="text" 
         className="form-control form-control-sm"
         name="materialCompany"
         autoComplete="off"
         value={data.materialCompany}
         onChange={this.onchange}
         />
         {errors.materialUnit&&<InlineError err={errors.materialUnit}/>}<br/>
         <label>Unit (ex- Bags,Ton,Brass)</label><span className="text-danger font-weight-bold">*</span>
         <input type="text" 
         className="form-control form-control-sm"
         name="materialUnit"
         autoComplete="off"
         value={data.materialUnit}
         onChange={this.onchange}
         />
         <label>Size (ex- 10mm)</label><span className="text-danger font-weight-bold">*</span>
         <input type="text" 
         className="form-control form-control-sm"
         name="materialSize"
         autoComplete="off"
         value={data.materialSize}
         onChange={this.onchange}
         />
          <div className="modal-footer">
        <button type="button" className="btn btn-secondary" data-dismiss="modal">Cancle</button>
        <button type="submit" className="btn btn-primary">Save</button>
      </div>
         
         </form> 
      </div>
    )
  }
}
const mapstatetoprops=state=>({
  token:state.userVerificationReducer.token

})
export default connect(mapstatetoprops)(AddDropdownMaterialForm)