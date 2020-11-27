import React, { Component } from 'react'
import { connect } from 'react-redux'
import {fetchallentries} from '../../actions/materialcurd'
import { Link } from 'react-router-dom/cjs/react-router-dom.min'
class AllEnteriesPage extends Component {
state={
  data:{
  token:localStorage.usertoken
  }
}
  
  componentDidMount(){
    this.props.fetchallentries(this.state.data)
    //console.log(this.state.data)
   }
  render() {
      const allenteries=this.props.alldata.slice(0,15).map(entries=>{
        return ( <tr key={entries.e_id}>
          <th scope="row">{entries.e_id}</th>
          <td>{entries.dc_number}</td>
          <td>{entries.material_name}</td>
          <td>{entries.Quantity} {entries.material_unit}</td>
          <td>{entries.material_size}</td>
          <td><Link>Detail</Link></td>
        </tr>)
      })
    return (
      <>
       <table className="table table-striped table-hover table-responsive table-sm">
       <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">DC No.</th>
      <th scope="col">M. Name</th>
      <th scope="col">Qantity</th>
      <th scope="col">Size</th>
      <th scope="col">Detail</th>
    </tr>
  </thead>
  <tbody>
   {allenteries}
    </tbody>
    </table>
      </>
    )
  }
}
const mapstatetoprops=state=>({
 alldata:state.materialcurd
})
export default connect(mapstatetoprops,{fetchallentries})(AllEnteriesPage)