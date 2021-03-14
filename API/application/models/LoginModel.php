<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class LoginModel extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function verifyUserDetails($userName=null,$password=null)
	{
		if(!is_null($userName) && !is_null($password))
		{
			$where = array("userName"=>$userName);
			$select = "adminID";
			$this->db->from("admin");
			$this->db->where($where);
			$query = $this->db->get();
			$sqlerror = $this->db->error();
			$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
			$result = $query->result();
			return $result;
		}
		{
			return false;
		}
	}
	public function saveadminInfo($data,$adminID){

		$this->db->where("adminID",$adminID);
		$res = $this->db->update("admin",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
	public function setSessionKey($adminID){

		$accessDate = date("Y/m/d H:i:s");
		$ip= $_SERVER["REMOTE_ADDR"];
		$data = array("adminID"=>$adminID,"sessionKey"=>session_id(),"accessDate"=>$accessDate,"createdDate"=>$accessDate,"IP"=>$ip);
		$res = $this->db->insert("adminSessions",$data);
		$sqlerror = $this->db->error();

		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
	public function unsetSessionKey($adminID){

		$where = array("adminID"=>$adminID);
		$this->db->where($where);
		$res = $this->db->delete("adminSessions");
		
		$sqlerror = $this->db->error();

		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
		return $res;
	}
	public function getDeviceDetails()
	{
	    $u_agent = $_SERVER['HTTP_USER_AGENT'];
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version= "";
	    $ub ="";
	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
	        $platform = 'linux';
	    }
	    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	        $platform = 'mac';
	    }
	    elseif (preg_match('(Windows 2000)', $u_agent)) {
	        $platform = 'windows2000';
	    }
	    elseif (preg_match('(Windows XP)', $u_agent)) {
	        $platform = 'windows XP';
	    }
	     elseif (preg_match('(Windows NT 5.2)', $u_agent)) {
	        $platform = 'windows Server 2003';
	    }
	     elseif (preg_match('(Windows NT 6.0)', $u_agent)) {
	        $platform = 'windows Vista';
	    }
	    elseif (preg_match('(Windows NT 6.1)', $u_agent)) {
	        $platform = 'windows 7';
	    }
	     elseif (preg_match('(Windows NT 6.2)', $u_agent)) {
	        $platform = 'windows 8';
	    }
	    elseif (preg_match('/win32/i', $u_agent)) {
	        $platform = 'windows';
	    }
	    
	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
	    {
	        $bname = 'Internet Explorer';
	        $ub = "MSIE";
	    }
	    elseif(preg_match('/Firefox/i',$u_agent))
	    {
	        $bname = 'Mozilla Firefox';
	        $ub = "Firefox";
	    }
	    elseif(preg_match('/Chrome/i',$u_agent))
	    {
	        $bname = 'Google Chrome';
	        $ub = "Chrome";
	    }
	    elseif(preg_match('/Safari/i',$u_agent))
	    {
	        $bname = 'Apple Safari';
	        $ub = "Safari";
	    }
	    elseif(preg_match('/Opera/i',$u_agent))
	    {
	        $bname = 'Opera';
	        $ub = "Opera";
	    }
	    elseif(preg_match('/Netscape/i',$u_agent))
	    {
	        $bname = 'Netscape';
	        $ub = "Netscape";
	    }
	    if($bname =='Unknown' && preg_match('/windows|win32/i', $u_agent))
	    {
			if(preg_match('/rv:11.0/',$u_agent))
			{
				$bname = 'Internet Explorer 11';
				$ub = "MSIE";
			}
		}
	   
	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
        	// we have no matching number just continue
    	}
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
	            $version= $matches['version'][0];
	        }
	        else {
	            $version= $matches['version'][1];
	        }
	    }
	    else {
	        $version= $matches['version'][0];
	    }
	   
	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}
	   
	   	$browserString = "<ul><li><b>userAgent : </b>{$u_agent}</li>
	   	<li><b>name : </b>{$bname}</li>
	   	<li><b>version : </b>{$version}</li>
	   	<li><b>pattern : </b>{$pattern}</li></ul>";

	   	$OSDetails = "<b>platform : </b>{$platform}";

	    return array("browserDetails"=>$browserString,"OSDetails"=>$OSDetails);
	}
	function getUserOS()
	{ 
		$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
	    $os_platform    =   "Unknown OS Platform";
		$os_array       =   array(
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );
		foreach ($os_array as $regex => $value)
		{ 
		    if (preg_match($regex, $user_agent))
		        $os_platform = $value;
	    }   
	    return $os_platform;
	}
	
	public function getSessionDetails($adminID)
	{
		
			$where = array("adminID"=>$adminID);
			$select = "*";
			$this->db->from("adminSessions");
			$this->db->where($where);
			$query = $this->db->get();
			$sqlerror = $this->db->error();
			$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);
			$result = $query->result();
			return $result;
		
	}
	public function updateSession($adminID){

		$accessDate = date("Y/m/d H:i:s");
		$data = array("accessDate"=>$accessDate);
		$this->db->where("adminID",$adminID);
		$res = $this->db->update("adminSessions",$data);
		$sqlerror = $this->db->error();
		$this->errorlogs->checkDBError($sqlerror,dirname(__FILE__),__LINE__,__METHOD__);

	}
	
}

	