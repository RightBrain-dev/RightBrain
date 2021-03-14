<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class SearchAdminModel extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function getTotalMembers($where=array())
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

		$sql = "SELECT adminID FROM ".$this->db->dbprefix."admin ".$whereStr."";
		$query = $this->db->query($sql);
		$rowcount = $query->num_rows();
		return $rowcount;
		
	}
	function GetMembersDetails($select = '',$where= array(),$limit='',$start='',$join='',$other)
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
			$orderBy = "ORDER BY createdDate DESC";
		}

		$sql = "SELECT u.*,ad.roleName FROM ".$this->db->dbprefix."admin as u LEFT JOIN ".$this->db->dbprefix."userRoleMaster as ad ON u.userRoleID = ad.userRoleID ".$whereStr." ".$orderBy." ".$limitstr;		
		
		$query = $this->db->query($sql);

		$result = $query->result();
		return $result;
	}

	public function changeMemberStatus($status,$ids){
		
		$data = array("status"=>$status);
		$memberIDs = explode(",",$ids);
		$this->db->where_in("adminID",$memberIDs);
		$res = $this->db->update("admin",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}

	public function deleteMember($memberID){
		
		$this->db->where("adminID",$memberID);
		$res = $this->db->delete("admin");
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
	function getAdminDetails($adminID)
	{
		$this->db->select("*");
		$this->db->from('admin');
		$this->db->where("adminID",$adminID);
		$query = $this->db->get();
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		$result = $query->result();
		return $result;
	}
	
	
	public function updateAdminDetails($data,$adminID)
	{
		$this->db->where("adminID",$adminID);
		$res = $this->db->update("admin",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
	public function saveAdminDetails($data)
	{
		$isinsert = $this->db->insert("admin",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $isinsert;
	}
	public function getAccessCompanyList($companyList='')
	{
		$this->db->select("*");
		$this->db->from('companyMaster');
		$this->db->where_in("companyID",$companyList);
		$query = $this->db->get();
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		$result = $query->result();
		return $result;
	}
}

	