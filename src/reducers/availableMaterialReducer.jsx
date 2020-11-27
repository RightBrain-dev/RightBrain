
  export default function availableMaterialReducer (state=[],action={})
  {
    switch(action.type)
    {
      case "fetchedAvailableMaterial":
        return action.res
      default :
      return state
    }
  }