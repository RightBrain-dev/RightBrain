import React, { Component } from "react";
import MaterialButtons from "../buttons/MaterialButtons";
import AddMaterialForm from "../forms/AddMaterialForm";
import {connect} from 'react-redux'
import {addmaterial} from '../../actions/materialcurd'
class AddMaterialPage extends Component {
  submit=(data)=>this.props.addmaterial(data).then(()=>this.props.history.push('/addmaterial'))
  render() {
    return (
      <>
        <MaterialButtons />
        <AddMaterialForm submit={this.submit}/>
      </>
    );
  }
}
export default connect(null,{addmaterial})(AddMaterialPage)