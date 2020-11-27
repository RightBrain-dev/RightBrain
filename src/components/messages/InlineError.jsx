import React from 'react'

export const InlineError = ({err}) => {
  return (
    <>
     <span className="text-danger" role="alert">{err}</span>
    </>
  )
}
