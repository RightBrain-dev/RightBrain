import React, { Component } from "react";
import { NavLink ,Link } from "react-router-dom";
import {logout} from '../../actions/auth'
import {connect} from 'react-redux'
class TopNavigationBar extends Component {
  render() {
    return (
      <>
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
          <div className="container-fluid">
            <NavLink className="navbar-brand" to="/dashboard">
              Right Brains
            </NavLink>
            <button
              className="navbar-toggler"
              type="button"
              data-toggle="collapse"
              data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span className="navbar-toggler-icon"></span>
            </button>
            <div
              className="collapse navbar-collapse"
              id="navbarSupportedContent"
            >
              <ul className="navbar-nav ml-auto mb-2 mb-lg-0">
                <li className="nav-item">
                  <NavLink
                    className="nav-link active"
                    aria-current="page"
                    to="dashboard"
                  >
                    Home
                  </NavLink>
                </li>
                <li className="nav-item">
                  <NavLink className="nav-link" to="material">
                    Material
                  </NavLink>
                </li>
                <li className="nav-item">
                  <NavLink  className="nav-link" to="labour">
                    Labour
                  </NavLink>
                  
                </li>
                <li className="nav-item">
                  <NavLink className="nav-link" to="labour">
                    DPR
                  </NavLink>
                  
                </li>
                <li className="nav-item">
                  <NavLink className="nav-link" to="labour">
                    WPR
                  </NavLink>
                  
                </li>
                <li className="nav-item">
                  <NavLink className="nav-link" to="labour">
                    MPR
                  </NavLink>
                  
                </li>
                <li className="nav-item">
                  <NavLink className="nav-link" to="labour">
                    Notification
                  </NavLink>
                  
                </li>
                <li className="nav-item">
                  <Link className="nav-link" to="" onClick={this.props.logout}>
                    LogOut
                  </Link>
                  
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </>
    );
  }
}
export default connect(null,{logout})(TopNavigationBar)