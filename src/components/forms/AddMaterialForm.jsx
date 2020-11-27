import React, { Component } from "react";
import AlertMessage from '../messages/AlertMessage'
import {addedmaterial,fetchdropdownmaterial, fetchselectedvalue} from '../../actions/materialcurd'
import {connect} from 'react-redux'
import DropDownMaterialModal from '../modals/DropDownMaterialModal'
import {add_material_form_validations} from '../../validations/validations'
import { InlineError } from '../messages/InlineError'
class AddMaterialForm extends Component {
  state = {
    data1:{
      token:this.props.token
    },
    data: {
      name: "",
      dcnumber: "",
      companyName: "",
      quantity: "",
      unit: "",
      msize: "",
      dealerName: "",
      description: "",
      dealerBillDate: "",
      addedBy: "1",
      addedDateTime: "",
      token:this.props.token
    },
    success:false,
    errors:{}
  };
  onchange=(e)=>{
      this.setState({data:{...this.state.data,[e.target.name]:e.target.value}})
      //console.log(this.state.data)
  }
  onchange1=e=>{
    this.setState({data:{...this.state.data,[e.target.name]:e.target.value}})
    //console.log(e.target.value)
    const selectedvalue=e.target.value
    this.props.fetchselectedvalue(selectedvalue)
  }
  onsubmit=(e)=>{
    e.preventDefault()
    const errors=add_material_form_validations(this.state.data)
    this.setState({errors})
    if(Object.keys(errors).length===0)
    {
    this.props.submit(this.state.data)
    .then(()=>addedmaterial({ 
    }) )
    .then(()=>this.setState({success:true}))
    }
  }
  setsuccess=()=>{
    this.setState({success:true})
  }
  componentDidMount(){
    this.props.fetchdropdownmaterial(this.state.data1)
    //console.log(this.state.data1)
 }
 static getDerivedStateFromProps(props,state)
 { 
      if(props.filtereddata.length!==0)
      {
        const companyName=props.filtereddata[0].material_company
        const unit=props.filtereddata[0].material_unit
        const msize=props.filtereddata[0].material_size
        const temp={
         data:{...state.data,companyName:companyName,unit:unit,msize:msize}
        }
         return temp
        } 
      return null
    }
  render() {
  const { data,success,errors} = this.state
  const dropdowndata=this.props.dropdowndata.map(data=>{
    return(
    <option value={data.mterial_id} key={data.mterial_id}>{data.material_name} ({ data.material_company}, {data.material_unit}, {data.material_size})</option>
    )
  })

    return (
      <>
        <div className="container">
          <div className="row">
        <div className="text-left bg-light"><DropDownMaterialModal setsuccess=
        {this.setsuccess}/>
        </div>
        </div>
          {success && <AlertMessage/>}
          <form onSubmit={this.onsubmit}>
          <div className="row">
            <div className="col-12 col-sm-12 col-md-12 col-lg-6 bg-light">
              <div className="text-dark m-2 p-2">
                {errors.name&&<InlineError err={errors.name}/>}<br/>
                <label className="form-label">Select Material Name </label><span className="text-danger font-weight-bold">*</span>
                <select className="form-control form-control-sm" name="name" 
                onChange={this.onchange1}>
                  <option defaultValue="">select material</option>
                  {dropdowndata}
              </select>
              {errors.dcnumber&&<InlineError err={errors.dcnumber}/>}<br/>
                <label className="form-label">DC Number (string)</label><span className="text-danger font-weight-bold">*</span>
                <input
                  type="text"
                  autoComplete="off"
                  className="form-control form-control-sm"
                  placeholder="somthing"
                  name="dcnumber"
                  value={data.dcnumber}
                  onChange={this.onchange}
                />
                
                <label className="form-label">
                  Material Company Name
                </label>
                <input
                type="text"
                  className="form-control form-control-sm"
                  autoComplete="off"
                  placeholder="somthing"
                  name="companyName"
                  disabled
                  value={data.companyName}
                  onChange={this.onchange}
                />
                {errors.quantity&&<InlineError err={errors.quantity}/>}<br/>
                <label className="form-label">Quntity</label><span className="text-danger font-weight-bold">*</span>
                <input
                type="number"
                  className="form-control form-control-sm"
                  autoComplete="off"
                  placeholder="somthing"
                  name="quantity"
                  value={data.quantity}
                  onChange={this.onchange}
                  onKeyPress={this.checknumber}
                  pattern="[0-9]*" 
                  inputMode="numeric"
                />
                <label className="form-label">Unit</label><span className="text-danger font-weight-bold">*</span>
                <input
                type="text"
                  className="form-control form-control-sm"
                   autoComplete="off"
                  placeholder="unit"
                  name="unit"
                  disabled
                  value={data.unit}
                  onChange={this.onchange}
                />
                <label className="form-label">Size (String) </label>
                <input
                type="text"
                  className="form-control form-control-sm"
                  placeholder="msize"
                  name="msize"
                  disabled
                  value={data.msize}
                  onChange={this.onchange}
                />
                {errors.dealerName&&<InlineError err={errors.dealerName}/>}<br/>
                <label className="form-label">Dealer Name </label><span className="text-danger font-weight-bold">*</span>
                <input
                type="text"
                  className="form-control form-control-sm"
                  placeholder="dealerName"
                  name="dealerName"
                  value={data.dealerName}
                  onChange={this.onchange}
                />
              </div>
            </div>
            <div className="col-12 col-sm-12 col-md-12 col-lg-6 bg-light">
              <div className="bg-light text-dark p-2 mt-lg-2">
                <label className="form-label">
                  Description (limit 300 words){" "}
                </label>
                <textarea
                rows="6"
                  className="form-control form-control-sm"
                  placeholder="somthing"
                  name="description"
                  value={data.description}
                  onChange={this.onchange}
                />
                {errors.dealerBillDate&&<InlineError err={errors.dealerBillDate}/>}<br/>
                <label className="form-label">Dealer Bill Date </label><span className="text-danger font-weight-bold">*</span>
                <input
                  className="form-control form-control-sm"
                  placeholder="dealerBillDate"
                  name="dealerBillDate"
                  value={data.dealerBillDate}
                  onChange={this.onchange}
                  type="date"
                />
                <div className="col-12 text-center">
              <button type='submit' className="btn btn-primary btn-sm  m-2 p-2">
                Save And Add
              </button>
              <button className="btn btn-primary btn-sm m-2 p-2">Cancel</button>
            </div>
              </div>
            </div>
          </div>
          </form>
          <div className='mt-5 ml-5'></div>
          <div className="row">
            
          </div>
          
        </div>
      </>
    );
  }
}
const mapstatetoprops=state=>({
  dropdowndata:state.dropdownmaterialdata.dropdownmaterialdata1,
  filtereddata:state.dropdownmaterialdata.selecteddata,
  token:state.userVerificationReducer.token
})


export default connect(mapstatetoprops,{fetchdropdownmaterial,fetchselectedvalue})(AddMaterialForm)