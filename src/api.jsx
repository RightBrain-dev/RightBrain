import axios from 'axios'

export default  {
  materialcurd:{
    addmaterial:data=>axios.post('/material/add_material.php',(data)).then(res=>res.data),
    fetchallentries:(token)=>axios.post('/material/fetchallenteries.php',(token)).then(res=>res.data),
    fetchAvailableMaterial:(token)=>axios.post('/material/fetch_available_material.php',(token)).then(res=>res.data),
    adddropdownmaterial:data=>axios.post('/material/add_dropdown_material.php',(data)).then(res=>res.data),   
    fetchdropdownmaterial:(token)=>axios.post('/material/fetch_dropdown_material.php',(token)).then(res=>res.data)
    },
    user:{
      login:credentials=>axios.post('/user/loginuser.php',(credentials)).then(res=>res.data),
      signup:data=>axios.post('/user/signupuser.php',(data)).then(res=>res.data)
      ,
      forgotpassword:data=>axios.post('/user/forgotpassword.php',(data)),
      validateToken:token=>axios.post('/user/validate_token.php',(token)),
      resetpassword:data=>axios.post('/user/reset_password.php',(data))
    }




} 