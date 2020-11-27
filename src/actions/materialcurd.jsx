import api from '../api'



///////////////////////////////////////////added data action
export const addedmaterial=res=>({
  type:"adddedmaterial",
  res
})
 ////////////////////////////////////////////fetched data action ////////////
export const fetchedallentries=res=>({
  type:"fetchedallmaterial",
  res
})
export const fetchedAvailableMaterial=res=>({
  type:"fetchedAvailableMaterial",
  res
})

export const fetchdedropdownmaterial=res=>({
  type:"fetchdedropdownmaterial",
  res
})

export const fetchselectedvalue=res=>({
  type:"fetchselectedvalue",
  res
})

////////////////////////////////add datat/////////////////
export const addmaterial=data=>dispatch=>api.materialcurd.addmaterial(data).then(res=>dispatch(addedmaterial(res)))

export const adddropdownmaterial=data=>dispatch=>api.materialcurd.adddropdownmaterial(data).then((res)=>dispatch(addedmaterial(res)))


/////////////////////////////////////fetch data ////////////////////
export const fetchallentries=(token)=>dispatch=>api.materialcurd.fetchallentries(token).then((res)=>dispatch(fetchedallentries(res)))

export const fetchAvailableMaterial=(token)=>dispatch=>api.materialcurd.fetchAvailableMaterial(token).then((res)=>dispatch(fetchedAvailableMaterial(res)))
//export const fetchAvailableMaterial=(token)=>console.log(token)
             
export const fetchdropdownmaterial=(token)=>dispatch=>api.materialcurd.fetchdropdownmaterial(token).then(data1=>dispatch(fetchdedropdownmaterial(data1)))