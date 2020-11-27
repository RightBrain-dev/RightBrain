import api from '../api'

export const userLoggedIn=(user)=>({
  type:"userLoggedIn",
  user
})
export const signedup=(res)=>({
  type:"signedUp",
  res
})

export const userLoggedout=()=>({
  type:"userLoggedOut",
})


export const login=credentials=>dispatch=>api.user.login(credentials)
.then(user=>{
 localStorage.usertoken=user.token
 //const user={token:user1.token,isverified:user1.is_verified_token,is_subscribed:user1.is_subscribed}
  dispatch(userLoggedIn(user))})

export const signup=data=>dispatch=>api.user.signup(data).then((res)=>dispatch(signedup(res)))

export const logout=()=>dispatch=>{
  localStorage.removeItem("usertoken")
  dispatch(userLoggedout())
}

export const forgotpassword=(data)=>()=>api.user.forgotpassword(data)


export const validateToken=token=>()=>api.user.validateToken({token})

export const resetpassword=data=>()=>api.user.resetpassword(data)

