<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SearchAdmin extends CI_Controller {

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
		$this->load->model('SearchAdminModel');
		$this->load->model('CommonModel');
		$this->load->library("pagination");
		$this->load->library("ValidateData");

		//"paginginfo":{"curPage":1,"prevPage":0,"pageLimit":"20","nextpage":2,"lastpage":123,"totalRecords":"3696","start":1,"end":30}
		
	}
	public function index()
	{
		$this->access->checkTokenKey();
		$this->response->decodeRequest();
		$textSearch = trim($this->input->post('textSearch'));
		$curPage = $this->input->post('curpage');
		$textval = $this->input->post('textval');
		$isAll = $this->input->post('getAll');
		$statuscode = $this->input->post('status');
		$memberID = trim($this->input->post("adminID"));
		$SmemberID = trim($this->input->post("SmemberID"));
		$orderBy = $this->input->post('orderBy');
		$order = $this->input->post('order');
		
		$config = array();
		$other = array("orderBy"=>$orderBy,"order"=>$order);
		$other['lat']="";
		$other['long']="";
		
		
        $config = $this->config->item('pagination');
		$wherec = $join = array();
		if(!isset($orderBy) || empty($orderBy)){
			$orderBy = "name";
			$order ="ASC";
		}
		
		if(isset($textSearch) && !empty($textSearch) && isset($textval) && !empty($textval)){
			$wherec["$textSearch like  "] = "'".$textval."%'";
		}

		if(isset($statuscode) && !empty($statuscode)){
			$statusStr = str_replace(",",'","',$statuscode);
			$wherec["status"] = 'IN ("'.$statusStr.'")';
		}
		
        $config["base_url"] = base_url() . "members";
        $config["total_rows"] = $this->SearchAdminModel->getTotalMembers($wherec);
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
		if($isAll=="Y"){
			$join = array();
			$adminDetails = $this->CommonModel->GetMasterListDetails($selectC='','admin',$wherec,'','',$join,$other);	
		}
        
        $adminDetails = $this->SearchAdminModel->GetMembersDetails($selectC='',$wherec,$config["per_page"],$page,$join,$other);
		
		$status['data'] = $adminDetails;
		
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
		if($adminDetails){
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
		if(trim($action) == "delete"){
			$ids = $this->input->post("list");
			$deleteMember = $this->SearchAdminModel->changeMemberStatus('delete',$ids);
		
			if($deleteMember){

				$status['data']=array();
				$status['statusCode'] = 200;
				$status['flag'] = 'S';
				$this->response->output($status,200);
			}
		}

		if(trim($action) == "changeStatus"){
			$ids = $this->input->post("list");	
			$statusCode = $this->input->post("status");	
			$changestatus = $this->SearchAdminModel->changeMemberStatus($statusCode,$ids);
			
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

		public function getAdminDetails($adminID='')
	{
		$this->access->checkTokenKey();
		$this->response->decodeRequest(); 

		$method = $this->input->method(TRUE);

		switch ($method) {
		case "PUT":
			{
				$adminDetails = array();
				$updateDate = date("Y/m/d H:i:s");
				$adminDetails['name'] = $this->validatedata->validate('name','Admin Name',true,'',array());
				$adminDetails['userName'] = $this->validatedata->validate('userName','User Name',true,'',array());
				$adminDetails['email'] = $this->validatedata->validate('email','Email-Id',true,'',array());
				$adminDetails['password'] = $this->validatedata->validate('password','Password',true,'',array());
				$adminDetails['status'] = $this->validatedata->validate('status','status',true,'',array());
				$adminDetails['userRoleID'] = $this->validatedata->validate('userRoleID','User Role',true,'',array());
				$adminDetails['address'] = $this->validatedata->validate('address','Address',false,'',array());
				$adminDetails['panNo'] = $this->validatedata->validate('panNo','PAN Number',false,'',array());
				$adminDetails['aadhaarNo'] = $this->validatedata->validate('aadhaarNo','aadhaar Number',false,'',array());
				$adminDetails['qualification'] = $this->validatedata->validate('qualification','Qualification',false,'',array());
				$adminDetails['dateOfBirth'] = $this->validatedata->validate('dateOfBirth','Date Of Birth',false,'',array());
				$adminDetails['dateOfJoin'] = $this->validatedata->validate('dateOfJoin','Date Of Join',false,'',array());

				$adminDetails['createdBy'] = $this->input->post('SadminID');
				$adminDetails['createdDate'] = $updateDate;	
				$adminDetails['modifiedDate']='0';
				$iscreated = $this->SearchAdminModel->saveAdminDetails($adminDetails);

				if(!$iscreated){
					$status['msg'] = $this->systemmsg->getErrorCode(998);
					$status['statusCode'] = 998;
					$status['data'] = array();
					$status['flag'] = 'F';
					$this->response->output($status,200);

				}else{
					$status['msg'] = $this->systemmsg->getSucessCode(400);
					$status['statusCode'] = 400;
					$status['data'] =array();
					$status['flag'] = 'S';
					$this->response->output($status,200);
				}


				break;
			}
			//
			case "POST":
			{

				$adminDetails = array();
				$updateDate = date("Y/m/d H:i:s");
				$adminDetails['name'] = $this->validatedata->validate('name','Admin Name',true,'',array());
				$adminDetails['userName'] = $this->validatedata->validate('userName','User Name',true,'',array());
				
				$adminDetails['password'] = $this->validatedata->validate('password','Password',true,'',array());
				$adminDetails['email'] = $this->validatedata->validate('email','Email-Id',true,'',array());
				$adminDetails['status'] = $this->validatedata->validate('status','status',true,'',array());
				$adminDetails['userRoleID'] = $this->validatedata->validate('userRoleID','User Role',true,'',array());
				$adminDetails['address'] = $this->validatedata->validate('address','Address',false,'',array());
				$adminDetails['panNo'] = $this->validatedata->validate('panNo','PAN Number',false,'',array());
				$adminDetails['aadhaarNo'] = $this->validatedata->validate('aadhaarNo','aadhaar Number',false,'',array());
				$adminDetails['qualification'] = $this->validatedata->validate('qualification','Qualification',false,'',array());
				$adminDetails['dateOfBirth'] = $this->validatedata->validate('dateOfBirth','Date Of Birth',false,'',array());
				$adminDetails['dateOfJoin'] = $this->validatedata->validate('dateOfJoin','Date Of Join',false,'',array());

				$adminDetails['modifiedDate'] = $updateDate;
				$adminDetails['modifiedBy'] = $this->input->post('SadminID');
				$iscreated = $this->SearchAdminModel->updateAdminDetails($adminDetails,$adminID);

				if(!$iscreated){
					$status['msg'] = $this->systemmsg->getErrorCode(998);
					$status['statusCode'] = 998;
					$status['data'] = array();
					$status['flag'] = 'F';
					$this->response->output($status,200);

				}else{
					$status['msg'] = $this->systemmsg->getSucessCode(400);
					$status['statusCode'] = 400;
					$status['data'] =array();
					$status['flag'] = 'S';
					$this->response->output($status,200);
				}


				break;
			}
			default:
			{
				$adminHistory = $this->SearchAdminModel->getAdminDetails($adminID);
				if(isset($adminHistory) && !empty($adminHistory)){

				$status['data'] = $adminHistory;
				$status['statusCode'] = 200;
				$status['flag'] = 'S';
				$this->response->output($status,200);
				}else{

				$status['msg'] = $this->systemmsg->getErrorCode(227);
				$status['statusCode'] = 227;
				$status['data'] =array();
				$status['flag'] = 'F';
				$this->response->output($status,200);
				}
				break;
			}	
		}	
	}
}
