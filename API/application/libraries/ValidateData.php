<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class ValidateData
	{
	
		public function __construct(){
		
			//parent::__construct();
			$this->CI = &get_instance();
			//$this->CI->load->library('response');
		}
		public function validate($fieldName,$lable=null,$isRequired=false,$minLenth=array(),$maxLenght=array(),$method='post') {


			//print $fieldName."\n";// exit;
			if($method == "post")
			$textCheck = trim($this->CI->input->post("{$fieldName}"));
			else
			$textCheck = trim($this->CI->input->get("{$fieldName}"));

			// validate only required fields
			if($isRequired == true)
			{
				if(!isset($textCheck) || empty($textCheck))
				{
					$status['msg'] = str_replace("{fieldName}",$lable,$this->CI->systemmsg->getErrorCode(218));
					$status['statusCode'] = 218;
					$status['data'] =array();
					$status['flag'] = 'F';
					$this->CI->response->output($status,200);
				}
			}

			// validate min length validation
			if(isset($minLenth) && !empty($minLenth) && $minLenth[0] == true){

					$textLenth = strlen($textCheck);
					if($textLenth < $minLenth[1] ){

						$minmsg = str_replace("{fieldName}",$lable,$this->CI->systemmsg->getErrorCode(219));
						$minnumbermsg = str_replace("{minchar}",$minLenth[1],$minmsg);
						$status['msg'] = $minnumbermsg;
						$status['statusCode'] = 219;
						$status['flag'] = 'F';
						$status['data'] =array();
						$this->CI->response->output($status,200);
					}
				}
			
			// validate max length validation
			if(isset($maxLenght) && !empty($maxLenght) && $maxLenght[0] == true){
				
					$textLenth = strlen($textCheck);
					if($textLenth > $maxLenght[1]){

						$maxmsg = str_replace("{fieldName}",$lable,$this->CI->systemmsg->getErrorCode(220));
						$maxnumbermsg = str_replace("{maxchar}",$maxLenght[1],$maxmsg);

						$status['msg'] = $maxnumbermsg;
						$status['statusCode'] = 220;
						$status['data'] =array();
						$status['flag'] = 'F';
						$this->CI->response->output($status,200);
					}
				}
			
			return  trim($this->CI->input->post("{$fieldName}"));
			
		}
		public function validateEmail($fieldName) {

		}
		
	}
	