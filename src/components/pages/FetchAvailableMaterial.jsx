import React, { Component } from 'react'
import {connect} from 'react-redux'
import { Link } from 'react-router-dom'
import {fetchAvailableMaterial} from '../../actions/materialcurd'
class FetchAvailableMaterial extends Component {
  state={
    data:{
    token:localStorage.usertoken
    }
  }
  componentDidMount(){
    //console.log(this.state.data)
     this.props.fetchAvailableMaterial(this.state.data)
  }
  render() {
    const fetcheddata= 
    this.props.fetchAvailableMaterialdata.map(data=>{
      return( <tr key={data.m_id}>
        <th scope="row">{data.m_id}</th>
        <td>{data.material_name}</td>
        <td>{data.quantity}{data.material_unit}</td>
        <td>{data.material_size}</td>
        <td>{data.material_company}</td>
        <td><Link to="dsfds">Detail</Link></td>
      </tr>)
      
    })
    return (
      <>
  <table className="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">M_name</th>
      <th scope="col">Qa.</th>
      <th scope="col">size</th>
      <th scope="col">comapny</th>
      <th scope="col">Detail</th>
    </tr>
  </thead>
  <tbody>
  {fetcheddata}
  </tbody>
</table>
      </>
    )
  }
}
const mapstatetoprops=(state)=>({
  fetchAvailableMaterialdata:state.availableMaterialReducer
})
export default connect(mapstatetoprops,{fetchAvailableMaterial})(FetchAvailableMaterial)