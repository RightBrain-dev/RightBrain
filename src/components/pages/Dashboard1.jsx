import React, { Component } from 'react'
import { connect } from 'react-redux'

class Dashboard1 extends Component {
state={
  paymentLink:"http://localhost/rightbrain1-api/payment/paytm.php"
}
  render() {
    const {paymentLink} =this.state
    return  (<div>
    <div className='container'>
      {console.log(this.props.is_subscribed)}
    {this.props.is_subscribed==="no"?
    <div className="row mt-1">
    <div className="alert alert-primary" role="alert">Activate Your Account For Awesomeness <a target="blank" href={paymentLink}>Click Here</a></div>
    </div>:<div></div>}
     <div className='row mt-1' style={{height:"500px", maxHeight:"500px", minHeight:"450px"}}>
     <div className='col-12 col-lg-12 bg-light mt-1'>
     <div className='m-1 p-1'>
       
       <div style={{overflow: "scroll" , height:"500px"}}>
     </div>
     </div>
   </div>
  
     </div>
     
    </div>
  </div>
)
  }
}
const mapStateToProps=state=>({ 
  is_verified:state.userVerificationReducer.isverified,
  is_subscribed:state.userVerificationReducer.is_subscribed
})
export default connect(mapStateToProps)(Dashboard1)