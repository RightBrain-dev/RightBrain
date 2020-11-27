import React, { Component } from 'react'
import AddDropdownMaterialForm from '../forms/AddDropdownMaterialForm'
import {adddropdownmaterial,fetchdropdownmaterial} from '../../actions/materialcurd'
import {connect} from 'react-redux'
class DropDownMaterialModal extends Component {
  submit=data=>this.props.adddropdownmaterial(data).then(()=>{
    this.props.setsuccess()
    this.props.fetchdropdownmaterial()
  }
  
  )
  render() {
    return (
      <>
 <button type="button" className="btn btn-link" data-toggle="modal" data-target="#exampleModal">
  Add Material in dropdown
</button>

      <div className="modal fade" id="exampleModal" tabIndex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div className="modal-dialog">
    <div className="modal-content">
      <div className="modal-header">
        <h5 className="modal-title" id="exampleModalLabel">Add Dropdown Material</h5>
        <button type="button" className="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div className="modal-body">
        <AddDropdownMaterialForm  submit={this.submit} />
      </div>
      
    </div>
  </div>
</div>
      </>
    )
  }
}
export default connect(null,{adddropdownmaterial,fetchdropdownmaterial})(DropDownMaterialModal)