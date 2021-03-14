<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('CommonModel');
		$this->load->library('emails');
		$this->load->library('ValidateData');
	}

	public function index(){
		$this->load->view('welcome_message');
		
	}

	public function getDashboardCount(){
		
		$statusInfo = new stdClass();
		
		$select="schoolID";
		$where = array();
		$table="schoolMaster";
		$totalSchool = $this->CommonModel->getCountByParameter($select,$table,$where);

		$statusInfo->inactive = 0;
		$statusInfo->delete = 0;
		$statusInfo->active = 0;
		$statusInfo->totalSchool = $totalSchool;

		$select="schoolID";
		$where = array("status ="=>"'active'");
		$statusInfo->active = $this->CommonModel->getCountByParameter($select,$table,$where);
		$where = array("status ="=>"'inactive'");
		$statusInfo->inactive = $this->CommonModel->getCountByParameter($select,$table,$where);
		$where = array("status ="=>"'delete'");
		$statusInfo->delete = $this->CommonModel->getCountByParameter($select,$table,$where);
		
		$status['data'] = $statusInfo;
		$status['statusCode'] = 200;
		$status['flag'] = 'S';
		$this->response->output($status,200);
	}
	
}
