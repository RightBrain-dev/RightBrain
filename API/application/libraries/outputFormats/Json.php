<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
	// this file is required to get the methods details.
	require_once("OutPut.php");

	class Json extends OutPut 
	{
	
		public function __construct()
		{
			$this->CI = &get_instance();
			$this->CI->load->helper('form');
		}
		/*
		* function  	: senddata
		* param    		: $requestContentType,$data
		* Description 	: This function encode the data in json and response to the client request.
		*/
		public function senddata($requestContentType, $data)
		{
			if(strpos($requestContentType,'application/json') !== false){
				$response = json_encode($data);
				echo $response;
				exit();
			}
		}
		/*
		* function  	: decode
		* param    		: $setdata
		* Description 	: This function decode the json data and set data to post array or new array as per the setdata type.
		*/
		public function decode($setdata='')
		{	
			$header = getallheaders();
			
			$method = $this->CI->input->method(TRUE);
			$REQUESTDATA = array();
			switch ($method) {
				case 'POST':{
					$REQUESTDATA = $_POST;
						$jsonString = file_get_contents("php://input");
						$REQUESTDATA = json_decode(trim($jsonString));
						
						if(!isset($REQUESTDATA) || empty($REQUESTDATA)){
							parse_str(file_get_contents("php://input"),$REQUESTDATA);
						}

				}break;
				case 'GET':{
					$REQUESTDATA = $_GET;
				}break;
				case 'DELETE':{
					//$jsonString = file_get_contents("php://input");
					$REQUESTDATA = $_GET;
					//$REQUESTDATA = json_decode($jsonString);
					//var_dump($jsonString);
				}
				break;
				case 'PUT':{
					//$jsonString = file_get_contents("php://input");
					//var_dump($jsonString); exit;
					$jsonString = file_get_contents("php://input");
						$REQUESTDATA = json_decode(trim($jsonString));
					
					if(!isset($REQUESTDATA) || empty($REQUESTDATA)){
						parse_str(file_get_contents("php://input"),$REQUESTDATA);
					}
					
				}break;
				default:{

				}
				break;
			}
			if(isset($header['SadminID']) && !empty($header['SadminID'])){
				$_POST['SadminID'] = $header['SadminID'];
			}
			else{
				$_POST['SadminID'] = "";
			}
			foreach ($REQUESTDATA as $key => $value)
			{
				if(is_array($value) || is_object($value))
				{
					foreach ($value as $subSuperKey => $valueSubSuperIndex)
					{
						if(is_array($valueSubSuperIndex) || is_object($valueSubSuperIndex))
						{
							foreach ($valueSubSuperIndex as $subkey => $valuesubindex)
							{
								$_POST[$key."_".$subSuperKey."_".$subkey] = $valuesubindex;
							}
						}
						else
						{
							$_POST[$key] = $valueSubSuperIndex;
						}
					}
				}
				else
				{
					$_POST[$key] = $value;	
				}
				$_POST["accessFrom"] = "mobile"; 
				$_POST[$key] = $value;
			}
			
			if(!empty($_POST['data']))
			{
				$res = json_decode($_POST['data']);
			}
			else
			{
				//$_POST['data']= "";
				return true;
			}
			
			$responsearray = array();
			switch ($setdata) {
				case 'array':
					{
						foreach ($res as $key => $value){
							$responsearray[$key] = $value;
						}
						$responsearray["accessFrom"] = "mobile";
						return $responsearray;
					}
					break;
				
				default:
					{
						foreach ($res as $key => $value)
						{
							if(is_array($value) || is_object($value))
							{
								foreach ($value as $subSuperKey => $valueSubSuperIndex)
								{
									if(is_array($valueSubSuperIndex) || is_object($valueSubSuperIndex))
									{
										foreach ($valueSubSuperIndex as $subkey => $valuesubindex)
										{
											$_POST[$key."_".$subSuperKey."_".$subkey] = $valuesubindex;
										}
									}
									else
									{
										$_POST[$key] = $valueSubSuperIndex;
									}
								}
							}
							else
							{
								$_POST[$key] = $value;	
							}
							$_POST["accessFrom"] = "mobile"; 
							$_POST[$key] = $value;
							
						}
						return true;
						
					}
					break;
			}
			
		}
		
	}
	