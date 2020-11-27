import React, { Component } from 'react'
import MaterialButtons from '../buttons/MaterialButtons'

export default class UpdateMaterialPage extends Component {
  render() {
    return (
      <>
      <MaterialButtons/>
        <div className='container'>
      <div className='row'>
        <div className='col-12 bg-light mt-2'>
          <div className='m-2 p-1'>
          Update Material Here
          </div>
        </div>
      </div>
        </div>
      </>
    )
  }
}
