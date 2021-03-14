
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class CommonModel extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function getCountByParameter($select,$table,$where=array(),$other=array(),$join=array())
	{
		$whereStr = "";
		$limitstr = "";
		foreach ($where as $key => $value) {
			if($whereStr == "")
				$whereStr .= $key." ".$value." ";
			else
				$whereStr .= " AND ".$key." ".$value." ";
 
		}
		$joinsql='';
		if(isset($join) && !empty($join)){
			foreach ($join as $key => $value) {
				$joinsql .= " ".$value['type']." ".$this->db->dbprefix.$value['table']." as ".$value['alias']." ON t.".$value['key1']." = ".$value['alias'].".".$value['key2'];
			}
		}else{
			$joinsql="";
		}
		if(trim($whereStr) != '' ){
			$whereStr = " WHERE ".$whereStr;
		}
		else{
			$whereStr="";
		}
		if(isset($other['whereIn']) && !empty($other['whereIn'])){
				
				if(trim($whereStr) == "")
					$whereStr .= " WHERE ".$other['whereIn']." IN (".$other['whereData'].") ";
				else
					$whereStr .= " AND ".$other['whereIn']." IN (".$other['whereData'].") ";
		}

		$sql = "SELECT ".$select." FROM ".$this->db->dbprefix.$table." as t ".$joinsql.$whereStr."";

		//$sql = "SELECT ".$select." FROM ".$this->db->dbprefix."{$table} as t ".$joinsql.$whereStr." ".$orderBy." ".$limitstr;
		$query = $this->db->query($sql);
		$rowcount = $query->num_rows();
		return $rowcount;
		
	}
	public function GetMasterListDetails($select ='',$table,$where= array(),$limit='',$start='',$join=array(),$other=array())
	{
		if($select ==''){
			$select = "*";
		}
		$whereStr = "";
		$limitstr = "";
		foreach ($where as $key => $value) {
			if($whereStr == "")
			$whereStr .= $key." ".$value." ";
			else
			$whereStr .= " AND ".$key." ".$value." ";
			 
		}
		// change for all record. For linking to other form need all records. so skip pagination.
		if($start !='' && $limit!='')
		{
			$limitstr = "LIMIT ".$start.",".$limit;
		}
		else{
			if(isset($limit) && !empty($limit)){
				$limitstr = "LIMIT 0,".$limit;	
			}else{
				$limitstr = "";
			}
			
		}

		if(trim($whereStr) != '' ){
			$whereStr = " WHERE ".$whereStr;
		}
		else{
			$whereStr="";
		}

		if(isset($other['whereIn']) && !empty($other['whereIn'])){
				
			if(trim($whereStr) == "")
				$whereStr .= " WHERE ".$other['whereIn']." IN (".$other['whereData'].") ";
			else
				$whereStr .= " AND ".$other['whereIn']." IN (".$other['whereData'].") ";
		}
		
		if(isset($other['orderBy']) && !empty($other['orderBy']))
		{
			$orderBy = "ORDER BY ".$other['orderBy']." ".$other['order'];
		}else
		{
			$orderBy="";
		}
		$joinsql='';
		if(isset($join) && !empty($join)){
			foreach ($join as $key => $value) {
				$joinsql .= " ".$value['type']." ".$this->db->dbprefix.$value['table']." as ".$value['alias']." ON t.".$value['key1']." = ".$value['alias'].".".$value['key2'];
				//print_r($joinsql);exit;
			}
		}else{
			$joinsql="";
		}

		$sql = "SELECT ".$select." FROM ".$this->db->dbprefix."{$table} as t ".$joinsql.$whereStr." ".$orderBy." ".$limitstr;
		
		
		$query = $this->db->query($sql);


		if(isset($other["resultType"]) && !empty($other["resultType"])){
			$result = $query->result_array();
		}else{
			$result = $query->result();
		}
		//print $this->db->last_query();
		return $result;
	}
	public function saveContactDetails($data){

		$res = $this->db->insert("contactus",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
	public function isSubscribed($email){
		$this->db->select("*");
		$this->db->from("subscribe");
		$this->db->where('email',$email);
		$query = $this->db->get();
		$sqlerror = $this->db->error();
		$result = $query->result();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $result;
	}

	public function countFiltered($table){
		$this->db->select("*");
		$this->db->from($table);
		$query = $this->db->get();
		$sqlerror = $this->db->error();
		$result = $query->num_rows();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $result;
	}
	
	public function getUniqueCode($length=6){
		$token = "";
   		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
   		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
   		$codeAlphabet.= "0123456789";
   		$max = strlen($codeAlphabet); // edited

   		for ($i=0; $i < $length; $i++) {
      		$randomNumber = rand(0, $max - 1);
      		$token .= substr($codeAlphabet, $randomNumber, 1);
   		}
   		return $token;
	}
	public function getMasterDetails($master='',$select="*",$where=array())
	{

		if(!isset($select) || empty($select)){
			$select="*";
		}
		if(!isset($master) || empty($master)){
			return false;
		}

			$this->db->select($select);
			$this->db->from($master);
			if(isset($where) && !empty($where)){
				$this->db->where($where);
			}
			
			$query = $this->db->get();
			
			$sqlerror = $this->db->error();
			$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
			$result = $query->result();
			//print $this->db->last_query();
			return $result;
		
	}
	public function saveMasterDetails($tableName,$data){

		if(!isset($tableName) || empty($tableName)){
			return false;
		}

		if(!isset($data) || empty($data)){
			return false;
		}
		$res = $this->db->insert($tableName,$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}

	public function updateMasterDetails($tableName,$data,$where){
		
		if(!isset($tableName) || empty($tableName)){
			return false;
		}
		if(!isset($data) || empty($data)){
			return false;
		}
		if(!isset($where) || empty($where)){
			return false;
		}
		$this->db->where($where);
		$res = $this->db->update($tableName,$data);
		//print $this->db->last_query();
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}

	public function deleteMasterDetails($tableName,$where){	
		if(!isset($tableName) || empty($tableName)){
			return false;
		}
		if(!isset($where) || empty($where)){
			return false;
		}		
		$this->db->where($where);
		$res = $this->db->delete($tableName);
			   
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
	public function changeMasterStatus($tableName,$statusCode,$ids,$primaryID){

		if(!isset($tableName) || empty($tableName)){
			return false;
		}
		if(!isset($ids) || empty($ids)){
			return false;
		}	

		if(!isset($primaryID) || empty($primaryID)){
			return false;
		}	

        $idlist = explode(",",$ids);
        $data = array("status"=>$statusCode);
        $this->db->where_in($primaryID,$idlist);
        $res = $this->db->update($tableName,$data);
        $sqlerror = $this->db->error();
        $this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
        return $res;
    }
    public function getMonthByID($id)
	{
		$months = array("1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");
		return $months[$id];
	}
	public function num2words($num, $currency) { 	
	
	$ZERO = "zero"; 	
	$MINUS = "minus"; 
			 /* zero is shown as "" since it is never used in combined forms */ 		 /* 0 .. 19 */ 		
	$lowName = array( "", "One", "Two", "Three", "Four", "Five", 		 "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", 		 "Sixteen", "Seventeen", "Eighteen", "Nineteen"); 
		$tys = array( "", "", "Twenty", "Thirty", "Forty", "Fifty", 		 "Sixty", "Seventy", "Eighty", "Ninety");  	
				 /* 0, 10, 20, 30 ... 90 */ 		 

		switch ($currency) 	{   	
		
		case 'INR': 	//$groupName = array( "", "Hundred", "Thousand", "Lakh", "Crore","Arab", "Kharab"); 
						$groupName = array("", "Hundred", "Thousand", "Lakh", "Crore", "Hundred", "Thousand", "Lakh", "");
						 
			// How many of this group is needed to form one of the succeeding group. 					
			// Indian: unit, hundred, thousand, lakh, crore 				

			//	$divisor = array( 100, 10, 100, 100,100000,100000000000) ;
			
					$divisor = array( 100, 10, 100, 100, 100, 10, 100, 100, 10) ;
			 		break;

		 case 'Paise': 	$groupName = array();
				 	$divisor = array(100);
			 	break;
		}
		$num = str_replace(",","",$num);
		$num = number_format($num,2,'.','');
		$cents = substr($num,strlen($num)-2,strlen($num)-1);
		$num = (int)$num;

		$s = "";

		if ( $num == 0 ) $s = $ZERO;
		$negative = ($num < 0 );
		if ( $negative ) $num = -$num;

		// Work least significant digit to most, right to left.
		// until high order part is all 0s.
		for ( $i=0; $num>0; $i++ )
		{
			$remdr = (int)($num % $divisor[$i]);
			$num = $num / $divisor[$i];
			if ( $remdr == 0 )
				continue;

			$t = "";
			if ( $remdr < 20 ) 		 
				$t = $lowName[$remdr]; 
			else if ( $remdr < 100 )
			{
				$units = (int)$remdr % 10;
				$tens = (int)$remdr / 10;
				$t = $tys [$tens];
							
				if ( $units != 0 )
					$t .= " " . $lowName[$units];
			}
			else

				$t = $inWords[$remdr];
	//echo $t; exit;

			$s = $t . " " . $groupName[$i] . " "  . $s;
			$num = (int)$num;
		}

		$s = trim($s);
		if ( $negative )
			$s = $MINUS . " " . $s;
			
			
			 if (($cents != '00') && ($s == 'zero')){		
			  $s = $cents." Paise only";
			  return $s; 
			
			 }
			
			
					switch($currency)
					{
				 
					case 'INR':		$s .= " Rupees";
								 if ($cents != '00')
										$s .= " and " . $this->num2words($cents, 'Paise');
				
									$s .= " Only";
									break;
				
					 case 'Paise':	$s .= " Paise";
					}	
		return $s;
		
	}
}	
