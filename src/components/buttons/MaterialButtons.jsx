import React, { Component } from 'react'
import { ButtonDesing } from './butttondesign/ButtonDesing'

export default class MaterialButtons extends Component {
  render() {
    const buttonvalues=[
      {
        name:'Add Material',
        linkto:'addmaterial'
      },
    /*  {
        name:'Update Material',
        linkto:'updatematerial'
      },
      {
        name:'Delete Material',
        linkto:'addmaterial'
      },*/
      
    ]
    return (
      <div>
        <div className='container'>
          <div className='row'>
            <div className='text-center'>
              {buttonvalues.map((values,index)=>
              <ButtonDesing key={index} values={values} />)}
            </div>
          </div>
        </div>
      </div>
    )
  }
}
