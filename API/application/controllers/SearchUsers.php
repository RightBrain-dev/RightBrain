<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SearchUsers extends CI_Controller {

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
		$this->load->helper('form');
		$this->load->model('SearchUsersModel');
		$this->load->model('CommonModel');
		$this->load->library("pagination");
		$this->load->library('ValidateData');
		$this->load->library('emails');
		
	}
	public function index()
	{
		$this->access->checkTokenKey();
		$this->response->decodeRequest();
		$textSearch = trim($this->input->post('textSearch'));
		$curPage = $this->input->post('curpage');
		$textval = $this->input->post('textval');
		
		$statuscode = $this->input->post('status');
		$gender = $this->input->post('gender');
		$registerDateFrom = $this->input->post('registerDateFrom');
		$registerDateTo = $this->input->post('registerDateTo');
		
		
		$orderBy = $this->input->post('orderBy');
		$order = $this->input->post('order');
		

		$config = array();
		if(!isset($orderBy) || empty($orderBy)){
			$orderBy = "";
			$order ="ASC";
		}
		$other = array("orderBy"=>$orderBy,"order"=>$order);
		
        $config = $this->config->item('pagination');
		$wherec = $join = array();
		
		if(isset($textSearch) && !empty($textSearch) && isset($textval) && !empty($textval)){
			$wherec["$textSearch like  "] = "'".$textval."%'";
		}

		if(isset($registerDateFrom) && !empty($registerDateFrom)){

			if(!isset($registerDateFrom) || empty($registerDateFrom)){
				$registerDateTo = date("Y-m-d");
			}
			$wherec["date(registerDate) >= "] = "'".date("Y-m-d",strtotime($registerDateFrom))."'";
			$wherec["date(registerDate) <= "] = "'".date("Y-m-d",strtotime($registerDateTo))."'";
		}

		if(isset($statuscode) && !empty($statuscode)){
			$statusStr = str_replace(",",'","',$statuscode);
			$wherec["status"] = 'IN ("'.$statusStr.'")';
		}

		if(isset($gender) && !empty($gender)){
			$statusStr = str_replace(",",'","',$gender);
			$wherec["gender"] = 'IN ("'.$statusStr.'")';
		}
		
		$config["base_url"] = base_url() . "members";
        $config["total_rows"] = $this->SearchUsersModel->getTotalUsers($wherec);
        $config["uri_segment"] = 2;
        $this->pagination->initialize($config);
        if(isset($curPage) && !empty($curPage)){
			$curPage = $curPage;
			$page = $curPage * $config["per_page"];
		}
		else{
			$curPage = 0;
			$page = 0;
		}
        
        $memberDetails = $this->SearchUsersModel->GetUsersDetails($selectC='',$wherec,$config["per_page"],$page,$join,$other);
		
		$status['data'] = $memberDetails;
		
		$status['paginginfo']["curPage"] = $curPage;
		if($curPage <=1)
			$status['paginginfo']["prevPage"] = 0;
		else
			$status['paginginfo']["prevPage"] = $curPage - 1 ;

		$status['paginginfo']["pageLimit"] = $config["per_page"] ;
		$status['paginginfo']["nextpage"] =  $curPage+1 ;
		$status['paginginfo']["totalRecords"] =  $config["total_rows"];
		$status['paginginfo']["start"] =  $page;
		$status['paginginfo']["end"] =  $page+ $config["per_page"] ;
		$status['loadstate'] = true;
		if($config["total_rows"] <= $status['paginginfo']["end"])
		{
			$status['msg'] = $this->systemmsg->getErrorCode(232);
			$status['statusCode'] = 400;
			$status['flag'] = 'S';
			$status['loadstate'] = false;
			$this->response->output($status,200);
		}
		if($memberDetails){
			$status['msg'] = "sucess";
			$status['statusCode'] = 400;
			$status['flag'] = 'S';
			$this->response->output($status,200);

		}else{
			$status['msg'] = $this->systemmsg->getErrorCode(227);
			$status['statusCode'] = 227;
			$status['flag'] = 'F';
			$this->response->output($status,200);
		}
	}
	public function changeStatus()
	{
		$this->access->checkTokenKey();
		$this->response->decodeRequest(); 
		$action = $this->input->post("action");
		if(trim($action) == "changeStatus"){
			$ids = $this->input->post("list");
			$statusCode = $this->input->post("status");	
			$changestatus = $this->SearchUsersModel->changeUsersStatus($statusCode,$ids);
			
			if($changestatus){

				$status['data'] = array();
				$status['statusCode'] = 200;
				$status['flag'] = 'S';
				$this->response->output($status,200);
			}else{
				$status['data'] = array();
				$status['msg'] = $this->systemmsg->getErrorCode(996);
				$status['statusCode'] = 996;
				$status['flag'] = 'F';
				$this->response->output($status,200);
			}
		}
	}
	public function register()
	{
		$this->response->decodeRequest();
		$CAPTCHA = $this->input->post("g-recaptcha-response");
		$res = $this->post_captcha($CAPTCHA);
		if (!$res['success']){
	        // What happens when the CAPTCHA wasn't checked
	       	$status['msg'] = "Please make sure you check the security CAPTCHA box";
			$status['statusCode'] = 216;
			$status['flag'] = 'F';
			$this->response->output($status,200);
	    }else{	        
			$language = $this->input->post("language");
			$area = $this->input->post("area");
			$mobile = $this->input->post("mobile");
			$gender = $this->input->post("gender");
			$isRegister = $this->SearchUsersModel->getUsersDetailsByParameter("*",array("number"=>$mobile));
			if(isset($isRegister) && !empty($isRegister)){

				$status['data'] = array();
				$status['msg'] = $this->systemmsg->getErrorCode(223);
				$status['statusCode'] = 996;
				$status['flag'] = 'F';
				$this->response->output($status,200);
			}

			$userDetails = array();
			$userDetails["language"] = $language;
			$userDetails["area"] = $area;
			$userDetails["number"] = $mobile;
			$userDetails["gender"] = $gender;
			$userDetails["IP"] = $_SERVER['REMOTE_ADDR'];
			$createUser = $this->SearchUsersModel->createUsersInfo($userDetails);
			if($createUser){
				$status['data'] = array();
				$status['msg'] = $this->systemmsg->getSucessCode(419);
				$status['statusCode'] = 419;
				$status['flag'] = 'S';
				$this->response->output($status,200);
			}else{
				$status['data'] = array();
				$status['msg'] = $this->systemmsg->getErrorCode(998);
				$status['statusCode'] = 998;
				$status['flag'] = 'F';
				$this->response->output($status,200);
			}
		}

	}
	function post_captcha($user_response) {
        $fields_string = '';
        $fields = array(
            'secret' => '6Ld4BlgUAAAAAJahYwacJXXJbI5EwBfXLAeszi0c',
            'response' => $user_response
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
