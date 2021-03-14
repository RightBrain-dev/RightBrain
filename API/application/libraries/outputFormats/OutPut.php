<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	abstract class OutPut
	{
		 	abstract public function senddata($requestContentType, $data);
			abstract public function decode($setdata);
	}
	