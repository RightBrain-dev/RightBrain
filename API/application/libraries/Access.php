<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Access
	{
		public function __construct()
		{
			$this->CI = &get_instance();
			$this->CI->load->model('LoginModel');
			$this->CI->load->helper('url');
		}
		public function checkTokenKey(){
			$header = getallheaders();
			if(!isset($header['SadminID']) || empty($header['SadminID'])){
				$status['msg'] = $this->CI->systemmsg->getErrorCode(994);
				$status['statusCode'] = 994;
				$status['data'] = array();
				$status['flag'] = 'F';
				$this->CI->response->output($status,200);
			}

			$memberSession = $this->CI->LoginModel->getSessionDetails($header['SadminID']);
			$validate = false;
			$this->CI->LoginModel->updateSession($header['SadminID']);
			foreach ($memberSession as $key => $value) {
				$keyecypt = md5($value->sessionKey.$value->adminID);
				if($keyecypt == $header['token']){
					$validate = true;
					break;
				}
			}
			if (!$validate) {

				$status['msg'] = $this->CI->systemmsg->getErrorCode(994);
				$status['statusCode'] = 994;
				$status['data'] = array();
				$status['flag'] = 'F';
				$this->CI->response->output($status,200);
			}
			$_POST['SadminID'] = $header['SadminID'];
			
		}
		public function checkaccess($accsessfile='')
		{
			
			if($accsessfile != "VerifyUser")
			{
				if(!isset($_SESSION['USER']['User_id']) && empty($_SESSION['USER']['User_id']))
				{
					redirect("/");exit;
				}
			}
		}
		public function checksession()
		{
			
			if(!isset($_SESSION['USER']['User_id']) && empty($_SESSION['USER']['User_id']))
				{
					redirect("/");exit;
				}
		}
		public function checkModuleAccess()
		{
			
			if(!isset($_SESSION['USER']['User_group']) || $_SESSION['USER']['User_group'] != "4")
				{
					redirect("/logout");exit;
				}
		}
		public function Datahashing($string="")
		{
			return $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($_SESSION['KEY']), $string, MCRYPT_MODE_CFB, md5(md5($_SESSION['KEY']))));
		}
		public function DataDecyrpt($string="")
		{
			$encrypted = $string;
			$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($_SESSION['KEY']), base64_decode($encrypted), MCRYPT_MODE_CFB, md5(md5($_SESSION['KEY']))),"\0");
			return $decrypted;
		}
		
		
	}
	