<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
	// this file is required to get the methods details.
	require_once("OutPut.php");

	class Html extends OutPut 
	{
	
		public function __construct(){
		
			//
		}
		/*
		* function  	: senddata
		* param    		: $requestContentType,$data
		* Description 	: This function encode the data in json and response to the client request.
		*/
		public function senddata($requestContentType, $data)
		{
			if(strpos($requestContentType,'application/html') !== false){
				$response = $data;
				echo $response['msg'];
				exit();
			}
		}
		/*
		* function  	: decode
		* param    		: $setdata
		* Description 	: This function decode the json data and set data to post array or new array as per the set data type.
		*/
		public function decode($setdata='')
		{	
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
	