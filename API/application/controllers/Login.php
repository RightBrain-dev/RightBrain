<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller. 
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	var $memberDetails;
	public function __construct(){
		
		parent::__construct();
		$this->load->model('LoginModel');
		if(!session_id())
		{
			session_start();
		}
	}
	public function index(){
		
	}
	public function verifyUser(){
		
		$this->response->decodeRequest();
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		if(trim($username) =="" || trim($password) ==""){
			$status['msg'] = $this->systemmsg->getErrorCode(210);
			$status['statusCode'] = 210;
			$status['keyDetails'] = "";
			$status['data'] = array();
			$status['flag'] = 'F';
			$this->response->output($status,200);
		}
		$aldata = array();
		
		$this->memberDetails = $this->LoginModel->verifyUserDetails($username,$password);
		if(!isset($this->memberDetails) || empty($this->memberDetails))
		{
			$status['msg'] = $this->systemmsg->getErrorCode(210);
			$status['statusCode'] = 210;
			$status['keyDetails'] = "";
			$status['data'] = array();
			$status['flag'] = 'F';
			$this->response->output($status,200);
		}
		$md5val= md5($this->memberDetails[0]->password);
		$res = substr($md5val,0,30);
      	$combine=$res.$_SESSION['salt'];
      	$shaval=sha1($combine);
      	$shaval_ss = substr($shaval,0,30);
      	if(!empty($this->memberDetails) && $password === $shaval_ss)
		{
			$Candidatetatus = $this->checkStatus($this->memberDetails[0]->status);
			if($Candidatetatus){
				$this->setSession($this->memberDetails[0]);
				$nowdate = date("Y/m/d H:i:s");
				$datasave = array("lastLogin"=>$nowdate);
				$this->LoginModel->saveadminInfo($datasave,$this->memberDetails[0]->adminID);
				$this->LoginModel->setSessionKey($this->memberDetails[0]->adminID);
				$keyecp = md5(session_id().$this->memberDetails[0]->adminID);
				$status['msg'] = $this->systemmsg->getSucessCode(410);
				$status['statusCode'] = 410;
				$status['keyDetails'] = session_id();
				$status['loginkey'] = $keyecp;
				$status['data'] = $this->memberDetails[0];
				$status['flag'] = 'S';
				$this->response->output($status,200);
			}
		}
		else
		{
			$status['msg'] = $this->systemmsg->getErrorCode(210);
			$status['statusCode'] = 210;
			$status['keyDetails'] = "";
			$status['data'] = array();
			$status['flag'] = 'F';
			$this->response->output($status,200);
		}
	
	}
	public function getsalt($userDetails='')
	{
		$salt = uniqid(mt_rand(), true);
		$_SESSION['salt'] = $salt;
		$status['msg'] = "sucess";
		$status['statusCode'] = 200;
		$status['data'] = array("salt"=>$salt);
		$status['flag'] = 'S';
		$this->response->output($status,200);
	}
	private function setSession($userDetails='')
	{
		$this->session->set_userdata("adminID",$userDetails->adminID);
		$this->session->set_userdata("name",$userDetails->name);
		$this->session->set_userdata("email",$userDetails->email);
	}

	public function logout()
	{
		$this->response->decodeRequest();
		$adminID = $this->input->post('adminID');
		$key = $this->input->post('key');
		$this->LoginModel->unsetSessionKey($adminID);
		//$this->LoginModel->setMemberOnlineStatus($adminID,'no');
		$this->session->unset_userdata("firstName");
		$this->session->unset_userdata("lastName");
		$this->session->unset_userdata("email");
		$status['msg'] = $this->systemmsg->getSucessCode(411);
		$status['statusCode'] = 411;
		$status['data'] = array();
		$status['flag'] = 'S';
		$this->response->output($status,200);
		
	}

	public function resetPassword()
	{
		$this->load->library("emails");
		$this->response->decodeRequest();
		$userNameEmail = $this->input->post('txt__userNameEmail');
		$checkEmail = strpos($userNameEmail,"@");
		if($checkEmail)
		{
			$where = array("email"=>addslashes($userNameEmail));
			$CandidateDetails = $this->CandidateModel->getCandidateDetailsByParameter("adminID,userName,firstName,lastName,email",$where);
			if(isset($CandidateDetails[0]->adminID) && !empty($CandidateDetails[0]->adminID))
			{
				$this->emails->sendForgotPasswordEmail($CandidateDetails);
			}
			else
			{
				$resDetails['msg'] = $this->systemmsg->getErrorCode(215);
				$resDetails['statusCode'] = 215;
				$status['data'] = array();
				$resDetails['flag'] = 'F';
				$this->response->output($resDetails,200);
			}

		}
		elseif(!empty($userNameEmail) && isset($userNameEmail))
		{
			$where = array("userName"=>addslashes($userNameEmail));
			$CandidateDetails = $this->CandidateModel->getCandidateDetailsByParameter("adminID,userName,firstName,lastName,email",$where);
			if(isset($CandidateDetails[0]->adminID) && !empty($CandidateDetails[0]->adminID))
			{
				$this->emails->sendForgotPasswordEmail($CandidateDetails[0]);
			}
			else
			{
				$resDetails['msg'] = $this->systemmsg->getErrorCode(215);
				$resDetails['statusCode'] = 215;
				$status['data'] = array();
				$resDetails['flag'] = 'F';
				$this->response->output($resDetails,200);
			}
		}
	}
	public function checkStatus($status="")
	{
		$resDetails = array();
		switch ($status){
			case 'inactive':{
					
				$resDetails['msg'] = $this->systemmsg->getErrorCode(211);
				$resDetails['statusCode'] = 211;
				$status['data'] = array();
				$resDetails['flag'] = 'F';
				$this->response->output($resDetails,200);
				break;
			}
			case 'delete':{

				$resDetails['msg'] = $this->systemmsg->getErrorCode(214);
				$resDetails['statusCode'] = 214;
				$status['data'] = array();
				$resDetails['flag'] = 'F';
				break;
			}
			default:{

				return true;
				break;
			}
				
		}
	}

}
