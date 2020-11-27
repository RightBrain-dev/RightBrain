import React, { Component } from 'react'
import MaterialButtons from '../buttons/MaterialButtons'
import AllEnteriesPage from './AllEnteriesPage'
import FetchAvailableMaterial from './FetchAvailableMaterial'
export default class material extends Component {
  render() {
    return (
      <div>
        <MaterialButtons/>
        <div className='container'>
          <div className='row mb-2' style={{height:"450px", maxHeight:"450px", minHeight:"450px"}}>
          <div className='col-12 col-lg-6 bg-light mt-2'>
          <div className='m-2 p-1'>
            <div className='text-center'>Available Material </div>
            <div style={{overflow: "scroll" , height:"430px"}}>
          <FetchAvailableMaterial/>
          </div>
          </div>
        </div>
        <div className='col-12 col-lg-6 bg-light mt-2 border border-lg-left'>
          <div className='m-2 p-1'>
              <div className='text-center'>
                All Material Entries
              </div>
              <div style={{overflow: "scroll" , height:"430px"}}>
              <AllEnteriesPage/>
              </div>
          </div>
        </div>
          </div>
        </div>
      </div>
    )
  }
}
