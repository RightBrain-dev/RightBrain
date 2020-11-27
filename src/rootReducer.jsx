import {combineReducers} from 'redux'
import {materialcurd,dropdownmaterialdata,materialcurdMessage} from './reducers/materialcurd'
import availableMaterialReducer from './reducers/availableMaterialReducer'
import userVerificationReducer from './reducers/userVerificationReducer'
export default combineReducers({
  materialcurd,
  availableMaterialReducer,
  materialcurdMessage,
  dropdownmaterialdata,
  userVerificationReducer
})