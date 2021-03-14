<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masters extends CI_Controller {

	var $imagesPATH = "";
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('CommonModel');
		$this->load->library("pagination");
		$this->load->library("response");
		$this->load->library("ValidateData");
		$this->imagesPATH = $this->config->item("imagesPATH");


	}

	public function getSchoolDetails()
	{
		//$this->access->checkTokenKey();
		$this->response->decodeRequest();
		$textSearch = trim($this->input->post('textSearch'));
		$isAll = $this->input->post('getAll');
		$curPage = $this->input->post('curpage');
		$country = $this->input->post('country');
		//print $curPage; exit;
		//print_r($_POST);exit;
		$textval = $this->input->post('textval');
		$orderBy = $this->input->post('orderBy');
		$order = $this->input->post('order');
		
		$statuscode = $this->input->post('status');
		

		$config = array();
		if(!isset($orderBy) || empty($orderBy)){
			$orderBy = "schoolName";
			$order ="ASC";
		}
		$other = array("orderBy"=>$orderBy,"order"=>$order);
		
		$config = $this->config->item('pagination');
		$wherec = $join = array();
		if(isset($textSearch) && !empty($textSearch) && isset($textval) && !empty($textval)){

		$wherec["$textSearch like  "] = "'".$textval."%'";
		}

		if(isset($statuscode) && !empty($statuscode)){
		$statusStr = str_replace(",",'","',$statuscode);
		$wherec["status"] = 'IN ("'.$statusStr.'")';
		}

		if(isset($country) && !empty($country)){
		$wherec["country"] = "='$country'";
		}


		$config["base_url"] = base_url() . "schoolDetails";
	    $config["total_rows"] = $this->CommonModel->getCountByParameter('schoolID','schoolMaster',$wherec);
	    $config["uri_segment"] = 2;
	    $this->pagination->initialize($config);
	    if(isset($curPage) && !empty($curPage)){
		$curPage = $curPage;
		$page = $curPage * $config["per_page"];
		}
		else{
		$curPage = 0;
		$page = 0;
		}
		$isAll = $this->input->post('getAll');
		if($isAll=="Y"){
			$schoolDetails = $this->CommonModel->GetMasterListDetails($selectC='','schoolMaster',$wherec,'','',$join,$other);	
		}else{
			$schoolDetails = $this->CommonModel->GetMasterListDetails($selectC='','schoolMaster',$wherec,$config["per_page"],$page,$join,$other);	
		}
		
		$status['data'] = $schoolDetails;
		$status['paginginfo']["curPage"] = $curPage;
		if($curPage <=1)
		$status['paginginfo']["prevPage"] = 0;
		else
		$status['paginginfo']["prevPage"] = $curPage - 1 ;

		$status['paginginfo']["pageLimit"] = $config["per_page"] ;
		$status['paginginfo']["nextpage"] =  $curPage+1 ;
		$status['paginginfo']["totalRecords"] =  $config["total_rows"];
		$status['paginginfo']["start"] =  $page;
		$status['paginginfo']["end"] =  $page+ $config["per_page"] ;
		$status['loadstate'] = true;
		if($config["total_rows"] <= $status['paginginfo']["end"])
		{
		$status['msg'] = $this->systemmsg->getErrorCode(232);
		$status['statusCode'] = 400;
		$status['flag'] = 'S';
		$status['loadstate'] = false;
		$this->response->output($status,200);
		}
		if($schoolDetails){
		$status['msg'] = "sucess";
		$status['statusCode'] = 400;
		$status['flag'] = 'S';
		$this->response->output($status,200);

		}else{
		$status['msg'] = $this->systemmsg->getErrorCode(227);
		$status['statusCode'] = 227;
		$status['flag'] = 'F';
		$this->response->output($status,200);
		}				
	}

	public function school($id='')
	{
		$this->response->decodeRequest();
		$method = $this->input->method(TRUE);
		
		if($method === "PUT" || $method === "POST"){
			$schoolDetails = array();
			
			$updateDate = date("Y/m/d H:i:s");
			$schoolDetails['schoolName'] = $this->validatedata->validate('schoolName','School name',true,'',array());
			$schoolDetails['schoolAddress'] = $this->validatedata->validate('schoolAddress','School Address',true,'',array());
			$schoolDetails['latitude'] = $this->validatedata->validate('latitude','Latitude',true,'',array());
			$schoolDetails['longitude'] = $this->validatedata->validate('longitude','Longitude',true,'',array());
			//$schoolDetails['image'] = $this->validatedata->validate('image','Image',true,'',array());
			$schoolDetails['memberAddress'] = $this->validatedata->validate('memberAddress','Member Address',true,'',array());

			$schoolDetails['pagelink'] = $this->validatedata->validate('pagelink','Link Required',true,'',array());

			$schoolDetails['forumlink'] = $this->validatedata->validate('forumlink','Forum Link Required',true,'',array());

			

			$schoolDetails['country'] = $this->validatedata->validate('country','Country Required',true,'',array());
			$schoolDetails['description'] = $this->validatedata->validate('description','Description Required',true,'',array());
			
			$wherec = array("schoolID"=>$id);
			$schoolData = $this->CommonModel->getMasterDetails('schoolMaster','',$wherec);

			if(isset($schoolData) && !empty($schoolData)){
				$schoolDetails['modifiedDate'] = $updateDate;
				$schoolDetails['modifiedBy'] = $this->input->post('SadminID');

				$isinsert = $this->CommonModel->updateMasterDetails('schoolMaster',$schoolDetails,$wherec);
				if(!$isinsert){
					$status['msg'] = $this->systemmsg->getErrorCode(998);
					$status['statusCode'] = 998;
					$status['data'] = array();
					$status['flag'] = 'F';
					$this->response->output($status,200);

				}else{
					$status['msg'] = $this->systemmsg->getSucessCode(400);
					$status['statusCode'] = 400;
					$status['data'] =array();
					$status['flag'] = 'S';
					$this->response->output($status,200);
				}
			}
			else{
				$projectDetails['status'] ="active";
				$schoolDetails['createdBy'] = $this->input->post('SadminID');
				$schoolDetails['createdDate'] = $updateDate;	
				$schoolDetails['modifiedDate']='0';
				$isinsert = $this->CommonModel->saveMasterDetails('schoolMaster',$schoolDetails);
				if(!$isinsert){

					$status['msg'] = $this->systemmsg->getErrorCode(998);
					$status['statusCode'] = 998;
					$status['data'] = array();
					$status['flag'] = 'F';
					$this->response->output($status,200);
				}else{

					$status['msg'] = $this->systemmsg->getSucessCode(400);
					$status['statusCode'] = 400;
					$status['data'] =array();
					$status['flag'] = 'S';
					$this->response->output($status,200);
				}
			}	
		}
			
		else
		{
			$where = array("schoolID"=>$id);
			$schoolHistory = $this->CommonModel->getMasterDetails('schoolMaster','',$where);
			if(isset($schoolHistory) && !empty($schoolHistory)){

			$status['data'] = $schoolHistory;
			$status['statusCode'] = 200;
			$status['flag'] = 'S';
			$this->response->output($status,200);
			}else{

			$status['msg'] = $this->systemmsg->getErrorCode(227);
			$status['statusCode'] = 227;
			$status['data'] =array();
			$status['flag'] = 'F';
			$this->response->output($status,200);
			}
			
		}
	}
	public function schoolChangeStatus()
	{
		$this->access->checkTokenKey();
		$this->response->decodeRequest(); 
		$action = $this->input->post("action");
			if(trim($action) == "changeStatus"){
				$ids = $this->input->post("list");
				$statusCode = $this->input->post("status");	
				$changestatus = $this->CommonModel->changeMasterStatus('schoolMaster',$statusCode,$ids,'schoolID');
				
			if($changestatus){

				$status['data'] = array();
				$status['statusCode'] = 200;
				$status['flag'] = 'S';
				$this->response->output($status,200);
			}else{
				$status['data'] = array();
				$status['msg'] = $this->systemmsg->getErrorCode(996);
				$status['statusCode'] = 996;
				$status['flag'] = 'F';
				$this->response->output($status,200);
			}
		}
	}
	public function SetSchoolPic($schoolID) {
        
        $this->load->library('slim');
        $imagename = 'IE_' . time() . "_" .$schoolID. ".jpg";
        try {
            $images = $this->slim->getImages();
        }
        catch (Exception $e) {

            $this->slim->outputJSON(array(
                'status' => SlimStatus::FAILURE,
                'message' => 'Unknown'
            ));
			return;
        }
		// No image found under the supplied input name
        if ($images === false) {

            $this->slim->outputJSON(array(
                'status' => SlimStatus::FAILURE,
                'message' => 'No data posted'
            ));
            return;
        }

        // Should always be one image (when posting async), so we'll use the first on in the array (if available)
        $image = array_shift($images);

        if (!isset($image)) {

            $this->slim->outputJSON(array(
                'status' => SlimStatus::FAILURE,
                'message' => 'No images found'
            ));

            return;
        }

        if (!isset($image['output']['data']) && !isset($image['input']['data'])) {

            $this->slim->outputJSON(array(
                'status' => SlimStatus::FAILURE,
                'message' => 'No image data'
            ));

            return;
        }

        // if we've received output data save as file
        if (isset($image['output']['data'])) {

        	$this->delSchoolPic($schoolID);
            // get the name of the file
            $name = $image['output']['name'];

            // get the crop data for the output image
            $data = $image['output']['data'];

            $output =$this->slim->saveFile($data, $name,$this->imagesPATH.'school/'.$schoolID.'/');
        }

        if (isset($image['input']['data'])) {

            // get the name of the file
            $name = $image['input']['name'];

            // get the crop data for the output image
            $data = $image['input']['data'];
			$input = $this->slim->saveFile($data, $name,$this->imagesPATH.'school/'.$schoolID.'/');

        }

        $response = array(
            'status' => SlimStatus::SUCCESS
        );

        if (isset($output) && isset($input)) {

            $response['output'] = array(
                'file' => $output['name'],
                'path' => $output['path']
            );

            $response['input'] = array(
                'file' => $input['name'],
                'path' => $input['path']
            );

        }
        else {
            $response['file'] = isset($output) ? $output['name'] : $input['name'];
            $response['path'] = isset($output) ? $output['path'] : $input['path'];
        }
		
        $updateDate = date("Y/m/d H:i:s");
        $data = array('profilePic' => $imagename,"lastUpdated"=>$updateDate);
       	
        $isrename = rename($this->imagesPATH.'school/'.$schoolID.'/'.$response['file'],$this->imagesPATH.'school/'.$schoolID.'/'. $imagename);

        $wherec = array("schoolID"=>$schoolID);
		$schoolDetails['image'] =$imagename;
		$isinsert = $this->CommonModel->updateMasterDetails('schoolMaster',$schoolDetails,$wherec);

        //$isupdate = $this->MembersModel->savebasicInfo($data,$schoolID);
        
       $this->slim->outputJSON($response);
    }
    public function delSchoolPic($schoolID){

    	$where = array("schoolID"=>$schoolID);
		$schoolHistory = $this->CommonModel->getMasterDetails('schoolMaster','',$where);
		if(!isset($schoolHistory) || empty($schoolHistory)){
			$status['msg'] = $this->systemmsg->getErrorCode(996);
			$status['statusCode'] = 996;
			$status['flag'] = 'F';
			$this->response->output($status,200);
    	}
    	$img = $this->imagesPATH.'school/'.$schoolID.'/'.$schoolHistory[0]->image;
    	if(isset($schoolHistory[0]->image) && !empty($schoolHistory[0]->image)){

    		if(file_exists($img)){
    			unlink($img);
    		}	
    	}
    	
    	$schoolDetails = array();
    	$updateDate = date("Y/m/d H:i:s");
        $wherec = array("schoolID"=>$schoolID);
		$schoolDetails['image'] ="";
		$schoolDetails['modifiedDate'] =$updateDate;
		$isupdate = $this->CommonModel->updateMasterDetails('schoolMaster',$schoolDetails,$wherec);

        if($isupdate){
			$status['data'] = array();
			$status['statusCode'] = 200;
			$status['flag'] = 'S';
			$this->response->output($status,200);
		}
		else{
			$status['msg'] = $this->systemmsg->getErrorCode(996);
			$status['statusCode'] = 996;
			$status['flag'] = 'F';
			$this->response->output($status,200);
		}
    }
    public function countryList()
    {
    	$where = array("status"=>"active");
		$schoolHistory = $this->CommonModel->getMasterDetails('countries','',$where);
		if(isset($schoolHistory) && !empty($schoolHistory)){

		$status['data'] = $schoolHistory;
		$status['statusCode'] = 200;
		$status['flag'] = 'S';
		$this->response->output($status,200);
		}else{

		$status['msg'] = $this->systemmsg->getErrorCode(227);
		$status['statusCode'] = 227;
		$status['data'] =array();
		$status['flag'] = 'F';
		$this->response->output($status,200);
		}
			
    }

}