<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	// this file is required to get the methods details.
	require_once("OutPut.php");

	class Xml extends OutPut 
	{
	
		public function __construct(){
		
			//
		}
		/*
		* function  	: senddata
		* param    		: $requestContentType,$data
		* Description 	: This function encode the data in xml and response to the client request.
		*/
		public function senddata($requestContentType,$data)
		{
			//print_r($data);exit;
			$xml = new SimpleXMLElement('<?xml version="1.0"?><result></result>');
			//$xmlsub = new SimpleXMLElement('<'.$key.'></'.$key.'>');
			foreach($data as $key=>$value)
			{
				if(is_object($value) || is_array($value))
				{	
					$subxml = $xml->addChild("row-".$key);
					foreach ($value as $keysub => $valuesub)
					{
						if(is_object($valuesub) || is_array($valuesub))
						{
							$subxml = $xml->addChild("row-sub-".$keysub);
							foreach ($valuesub as $keysub2 => $valuesub2)
							{
								$subxml->addChild($keysub2,htmlspecialchars($valuesub2));	
							}
						}else{	
						$subxml->addChild($keysub,htmlspecialchars($valuesub));	
						}
					}
				}
				else
				{
					$xml->addChild($key,htmlspecialchars($value));	
				}
			}
			echo $xml->asXML();
			exit();
		}
		/*
		* function  	: decode
		* param    		: $setdata
		* Description 	: This function decode the xml data and set data to post array or new array as per the set data type.
		*/
		public function decode($setdata='')
		{	

			$header = getallheaders();
			if(isset($header['SmemberID']) && !empty($header['SmemberID'])){
				$_POST['SmemberID'] = $header['SmemberID'];
			}
			else{
				$_POST['SmemberID'] = "";
			}

			if(isset($_REQUEST['data']) &&  !empty($_REQUEST['data']))
			{
				$res = json_decode($_REQUEST['data']);
				$responsearray = array();
				switch ($setdata) {
					case 'array':
						{
							foreach ($res as $key => $value){
								$responsearray[$key] = $value;
							}
							return $responsearray;
						}
						break;
					
					default:
						{
							foreach ($res as $key => $value){
								$_POST[$key] = $value;
							}
							return true;
							
						}
						break;
				}
			}
		}
	}
	