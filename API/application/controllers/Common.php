<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

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
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('CommonModel');
		$this->load->model('LoginModel');
		$this->load->model('SearchTransformerModel');
		$this->load->library("pagination");
		$this->load->library("emails");
		$this->load->library("response");

	}
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function firstmsg()
	{
		
		$message ="welcome Message";
		$Subject = "Welcome to tridentengineer.com";
		$tDetails = $this->CommonModel->firstmsgList();
		$done = false;
		foreach ($tDetails as $key => $value) {
				
			$tDetails = $this->SearchTransformerModel->getTransformerDetailsSingle($value->tID);
			$mailDetails = array();
			$mailDetails['rating'] = $tDetails[0]->rating;
			$mailDetails['siteName']= $tDetails[0]->siteName;
			$mailDetails['contactEmail']= $tDetails[0]->contactEmail;
			$mailDetails['contactNo']= $tDetails[0]->contactNo;
			$mailDetails['message']=$message;
			$mailDetails['subject'] = $Subject;
			
			$isSMSSent =  $this->emails->sendSMS($mailDetails);

			$isMailSent = $this->emails->sendDueEmail($mailDetails);

			if($isSMSSent == true && $isMailSent== true){
				$done = true;
				$data = array("firstmsg"=>'yes');
				$this->SearchTransformerModel->saveTransformerInfo($data,$tDetails[0]->tID);
			}else{
				$status['msg'] = $this->systemmsg->getErrorCode(998);
				$status['statusCode'] = 998;
				$status['data'] =array();
				$status['flag'] = 'F';
				$this->response->output($status,200);
				$done = false;
			}
		}
		if($done){

			$status['msg'] = $this->systemmsg->getSucessCode(400);
			$status['statusCode'] = 400;
			$status['data'] =array();
			$status['flag'] = 'S';
			$this->response->output($status,200);	
		}else{
			$status['msg'] = $this->systemmsg->getErrorCode(998);
			$status['statusCode'] = 998;
			$status['data'] =array();
			$status['flag'] = 'F';
			$this->response->output($status,200);
		}
		

	}
}

