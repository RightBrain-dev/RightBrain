import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';
import * as serviceWorker from './serviceWorker';
import {Provider}  from 'react-redux'
import {createStore,applyMiddleware} from 'redux'
import thunk from 'redux-thunk'
import {composeWithDevTools} from 'redux-devtools-extension'
import rootReducer from './rootReducer'
import decode from 'jwt-decode'
//import * as jwtDecode from 'jwt-decode'
//import * as jwt_decode from 'jwt-decode'
import '../node_modules/bootstrap/dist/css/bootstrap.min.css'
import '../node_modules/bootstrap/dist/js/bootstrap.bundle'
import { userLoggedIn } from './actions/auth';
const store= createStore(rootReducer,composeWithDevTools(applyMiddleware(thunk)))

if(localStorage.usertoken)
{
  
  const payload=decode(localStorage.usertoken)
  const user={token: localStorage.usertoken,isverified:payload.user_info.is_verified_token,is_subscribed:payload.user_info.is_subscribed}
  store.dispatch(userLoggedIn(user))
}

ReactDOM.render(
  <Provider store={store}>
    <App />
    </Provider>,
  document.getElementById('root')
);

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();
