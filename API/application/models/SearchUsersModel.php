<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class SearchUsersModel extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function getTotalUsers($where=array())
	{
		$whereStr = "";
		$limitstr = "";
		foreach ($where as $key => $value) {
			if($whereStr == "")
				$whereStr .= $key." ".$value." ";
			else
				$whereStr .= " AND ".$key." ".$value." ";
 
		}
		if(trim($whereStr) != '' ){
			$whereStr = " WHERE ".$whereStr;
		}
		else{
			$whereStr="";
		}

		$sql = "SELECT id FROM ".$this->db->dbprefix."users ".$whereStr."";
		//$this->db->select('memberID');
		//$this->db->from('activeProfiles');
		$query = $this->db->query($sql);
		$rowcount = $query->num_rows();
		return $rowcount;
		
	}
	function GetUsersDetails($select = '',$where= array(),$limit='',$start='',$join='',$other)
	{
		$whereStr = "";
		$limitstr = "";
		foreach ($where as $key => $value) {
			if($whereStr == "")
				$whereStr .= $key." ".$value." ";
			else
				$whereStr .= " AND ".$key." ".$value." ";
 
		}
		if($start !='' && $limit!='')
		{
				$limitstr = "LIMIT ".$start.",".$limit;
		}
		else{
			$limitstr = "LIMIT 0,".$limit;
		}

		if(trim($whereStr) != '' ){
			$whereStr = " WHERE ".$whereStr;
		}
		else{
			$whereStr="";
		}
		if(isset($other['orderBy']) && !empty($other['orderBy']))
		{
			$orderBy = "ORDER BY ".$other['orderBy']." ".$other['order'];
		}else{
			$orderBy = "ORDER BY registerDate DESC";
		}

		$sql = "SELECT * FROM ".$this->db->dbprefix."users ".$whereStr." ".$orderBy." ".$limitstr;
		
		$query = $this->db->query($sql);

		$result = $query->result();
		return $result;
	}
	public function getUsersDetailsByParameter($select="*",$where=array())
	{
		$this->db->select($select);
		$this->db->from('users');
		if(isset($where) && !empty($where)){
			$this->db->where($where);
		}
		else{
			return false;
		}
		$query = $this->db->get();
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		$result = $query->result();
		return $result;
	}
	public function saveUsersInfo($data,$id){

		$this->db->where("id",$id);
		$res = $this->db->update("users",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
	public function createUsersInfo($data){

		$res = $this->db->insert("users",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $this->db->insert_id();
	}
	public function changeUsersStatus($statusCode,$ids){

		$idlist = explode(",",$ids);
		$data = array("status"=>$statusCode);
		$this->db->where_in("id",$idlist);
		$res = $this->db->update("users",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
}

	