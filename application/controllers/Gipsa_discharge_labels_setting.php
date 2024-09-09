<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gipsa_discharge_labels_setting  extends CI_Controller { 

    public function __construct()
    {
        parent::__construct();
        auth_users();
        unauthorise_permission('112','691');
        $this->load->model('gipsa_discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
    	
        unauthorise_permission('112','691');
        $data['page_title'] = 'Discharge Labels Settings'; 
        $post = $this->input->post();
       // print '<pre>'; print_r($post);die;
        //exit;
        if(!empty($post))
        { 
            $this->discharge_labels_setting->save();
            echo 'Your Discharge label updated.';;
            return false;
        } 
        //$data['discharge_labels_setting_list'] = $this->discharge_labels_setting->get_master_unique();
        //$data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique();
		$discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique();
		$discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique();
		$arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
		$sortArray = array();
			foreach($arr as $value){ //print_r($value);
			    foreach($value as $key=>$value)
			    {
			        if(!isset($sortArray[$key])){
			            $sortArray[$key] = array();
			        }
			        $sortArray[$key][] = $value;
			    }
			}
			$orderby = "order_by";
 			array_multisort($sortArray[$orderby],SORT_ASC,$arr);
		$data['discharge_labels_setting_list'] = $arr;
		$data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique();
		//print '<pre>'; print_r($data['discharge_labels_setting_list']); exit;
        $this->load->view('gipsa_discharge_labels_setting/add',$data);
    } 
      
   
}
?>