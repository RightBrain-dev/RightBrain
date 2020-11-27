import React from 'react'
import { Link } from 'react-router-dom/cjs/react-router-dom.min'

export const ButtonDesing = ({values}) => {
  return (
    <>
    <Link className='btn btn-light btn-sm m-2 mr-3 m-sm-3'   to={values.linkto} >{values.name}
    
     </Link>
    </>
  )
}
