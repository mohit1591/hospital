<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_patient extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('ipd_patient/ipd_patient_model','ipd_patient');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
        unauthorise_permission(19,113);
        $this->session->unset_userdata('patient_search');
        $this->session->unset_userdata('ipd_particular_billing');
        $this->session->unset_userdata('ipd_particular_payment');
        $data['page_title'] = 'IPD Patient List'; 
        $data['form_data'] = array('patient_code'=>'','patient_name'=>'','mobile_no'=>'','address'=>'','start_date'=>date('d-m-Y'), 'end_date'=>date('d-m-Y'));
        $this->load->view('ipd_patient/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(19,113);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->ipd_patient->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $patient) { 
            $no++;
            $row = array();
            if($patient->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($patient->state))
            {
                $state = " ( ".ucfirst(strtolower($patient->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            }                   
            $row[] = '<input type="checkbox" name="patient[]" class="checklist" value="'.$patient->id.'">'.$check_script;
            $gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');
            $row[] = $patient->patient_code; 
            $row[] = date('Y').'/'.$patient->ipd_no;
            $row[] = $patient->patient_name;
            
            ///////////// Age calculation //////////
                $age_y = $patient->age_y;
                $age_m = $patient->age_m;
                $age_d = $patient->age_d;
                $age = "";
                if($age_y>0)
                {
                $year = 'Years';
                if($age_y==1)
                {
                  $year = 'Year';
                }
                $age .= $age_y." ".$year;
                }
                if($age_m>0)
                {
                $month = 'Months';
                if($age_m==1)
                {
                  $month = 'Month';
                }
                $age .= ", ".$age_m." ".$month;
                }
                if($age_d>0)
                {
                $day = 'Days';
                if($age_d==1)
                {
                  $day = 'Day';
                }
                $age .= ", ".$age_d." ".$day;
                } 
            ///////////////////////////////////////
            $row[] = $age; 
            $row[] = $gender[$patient->gender];
            $row[] = $patient->mobile_no;
            if(!empty($patient->address))
            {
                $row[] = substr($patient->address,0,50);
            }
            else
            {
                $row[]="";
            }   

            //Action button /////
            $ot_booking = "";
            $ipd_charge_entry='';
           // $patient_id=  ;807
            if(in_array('807',$users_data['permission']['action']))
            {
            $ot_booking = ' <a class="btn-custom" href="'.base_url('ot_booking/add/?reg='.base64_encode($patient->p_id)).'" style="'.$patient->id.'" title="Delete" data-url="512"><i class="fa fa-plus"></i> OT Booking</a>';
            }
            if(in_array('385',$users_data['permission']['action']))
            {
             $ipd_charge_entry = ' <a class="btn-custom" href="'.base_url('ipd_charge_entry/add/'.$patient->p_id.'/'.$patient->id).'"  title="Charge Entry" data-url="512"><i class="fa fa-plus"></i> Charge Entry</a>';
            }
             $row[] = $ot_booking.$ipd_charge_entry;                
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_patient->count_all(),
                        "recordsFiltered" => $this->ipd_patient->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
     
}
