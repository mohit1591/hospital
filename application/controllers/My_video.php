<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_video extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		$this->load->library('form_validation');
		$this->load->library('healthcloud');
		
		
    }
    
    public function index()
    {
        ini_set('display_errors', 1);
  //echo gmdate("Y-m-d H:i:s"); 
 // $time = gmmktime();

//echo $milliseconds = round(microtime(true) * 1000);
//echo gmdate("Y-m-d H:i:s");


//echo gmdate("Y-m-d\TH:i:s\Z");
//echo date("Y-m-d H:i:s", $time); 
  $data = json_decode('{"serialId":13124,"conversation_id":"cres_200530054420930","patient_url":"https://pdc.arintra.com/vcon?cres_200530054420930","doctor_url":"https://docon-dot-kaisehain01-prod02.appspot.com/doctor/room/cres_200530054420930","passcode":"20930","start_valid":1590860660787,"finish_valid":1591119860787}');
  exit;
        //$date = date_create(date('d-m-Y h:i:s'));
        
//echo date_format($date, 'U'); exit;
        //$date = new DateTime('30-05-2020'); // format: MM/DD/YYYY
//echo $date->format('U'); exit;
       $data_video = $this->healthcloud->get_video_url();
    }
    
    
}
