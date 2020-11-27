export function materialcurd (state=[],action={})
{
  switch(action.type)
  {
    case "fetchedallmaterial":
    return action.res
    default :
    return state
  }
}
export function materialcurdMessage (state=[],action={})
{
  switch(action.type)
  {
    case "adddedmaterial":
      return action.res
    default :
    return state
  }
}
const intialdropdowndata={
  dropdownmaterialdata1:[],
  selecteddata:[],
}
export function dropdownmaterialdata (state=intialdropdowndata,action={})
{
  switch(action.type)
  {
    case "fetchdedropdownmaterial":
    return {...state,dropdownmaterialdata1:action.res}
    
    case "fetchselectedvalue":
    {
      const filterdata=state.dropdownmaterialdata1.filter(item=>item.mterial_id===action.res)
      return {...state,selecteddata:filterdata}
    }
    default :
    return state 
  }
}

