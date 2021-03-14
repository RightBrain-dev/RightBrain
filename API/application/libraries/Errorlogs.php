<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Errorlogs extends CI_Log
	{
		public function __construct(){
		
			parent::__construct();
			$this->CI = &get_instance();
		}
		public function catchError($description='',$heading='',$filename='',$lineNumber='',$functionName=''){

			$errorDetails['description'] = $description;
	        $errorDetails['heading'] = $heading;
	        $errorDetails['file'] = $filename;
	        $errorDetails['loginUser'] =  '1';
	        $errorDetails['lineNumber'] =  $lineNumber;
	        $errorDetails['function'] =  $functionName;
	        $errorDetails['deviceCall'] =  $_SERVER['HTTP_USER_AGENT'];
        	$errorDetails['ipAddress'] =  $_SERVER['REMOTE_ADDR'];
        	$isinsert = $this->CI->db->insert('errorlogs',$errorDetails);  
	       	/*var_dump($this->CI->db->error());
	       	var_dump($isinsert);
	        print $this->CI->db->last_query();*/
    	}
    	function write_log($level = 'error', $msg, $php_error = FALSE)
    	{
    		
    		//$result = parent::write_log($level, $msg, $php_error);

        	if ($result == TRUE && strtoupper($level) == 'ERROR')
        	{
	            $message = "An error occurred: \n\n";
	            $message .= $level.' - '.date($this->_date_fmt). ' --> '.$msg."\n";

	            $errorDetails['description'] = $message;
		        $errorDetails['heading'] = $level;
		        $errorDetails['file'] = '';
		        $errorDetails['loginUser'] =  '1';
		        $errorDetails['lineNumber'] = '';
		        $errorDetails['function'] =  '';
		        $errorDetails['deviceCall'] =  $_SERVER['HTTP_USER_AGENT'];
	        	$errorDetails['ipAddress'] =  $_SERVER['REMOTE_ADDR'];

             	$isinsert = $this->CI->db->insert('errorlogs',$errorDetails);  
	            //mail($to, $subject, $message, $headers);
		    }

        //return $result;

    	}
    	public function checkDBError($sqlerror,$dir,$line,$method)
    	{
    		if($sqlerror['code']!='' && $sqlerror['message'] !='')
			{
				$message = "Code : {$sqlerror['code']} \n Message : {$sqlerror['message']}";
		        $this->catchError($message,"SQL Error",$dir,$line,$method);
		        $status['msg'] ="Database error. Please contact to support team";
		        $status['errorCode'] ='209';
		        $this->CI->response->output($status,200);
		    }
		    else
		    {
		    	return true;
		    }

			
    	}
	}
	