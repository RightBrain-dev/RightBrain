<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Mails extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	function getEmailTemplate($templateName='')
	{
		$where = array("name"=>$templateName,"status"=>"active");
		$this->db->select("*");
		$this->db->from("mails");
		$this->db->where($where);
		$query = $this->db->get();
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		$result = $query->result();
		return $result;
	}
	
}

	