  export default function userVerificationReducer (state={},action={})
  {
    switch(action.type)
    {
      case "userLoggedIn":
        return action.user
      case "signedUp":
        return state
      case "userLoggedOut":
        return {}
      default :
      return state
    }
  }