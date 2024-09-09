<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('doctors/doctors_model','doctors');
        $this->load->model('general/general_model');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
        unauthorise_permission(20,121);
        $this->session->unset_userdata('doctor_search');
        $data['specialization_list'] = $this->general_model->specialization_list();
        // Default Search Setting
        $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        }
        // End Defaul Search
        $data['form_data'] = array('doctor_code'=>'','doctor_name'=>'','specialization_id'=>'','doctor_type'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'');
        $this->session->set_userdata('doctor_search', $data['form_data']);
        $data['page_title'] = 'Doctor List'; 
        $this->load->view('doctors/list',$data);
    }

    public function ajax_list()
    {   
        unauthorise_permission(20,121);
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->doctors->get_datatables();

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $doctors) { 
            $no++;
            $row = array();
            if($doctors->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($doctors->state))
            {
                $state = " ( ".ucfirst(strtolower($doctors->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                  
            $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            if($users_data['parent_id']== $doctors->branch_id){  
            
                    $row[] = '<input type="checkbox" name="doctors[]" class="checklist" value="'.$doctors->id.'">'.$check_script;
                
            }else{
                $row[]='';
            }
            /*$specilization_name ="";
            $specilization_name = $this->general_model->get_specilization_name($doctors->specilization_id);*/
            $row[] = $doctors->doctor_code;             
            $row[] = $doctors->doctor_name;
            $row[] = $doctors->specialization;
            $row[] = $doctors->mobile_no; 
            $row[] = $doctor_type[$doctors->doctor_type];
            $row[] = $doctors->sharing_pattern;
            $row[] = $doctors->marketing_person;
            $row[] = $doctors->consultant_charge; 
            $row[] = $doctors->emergency_charge;
            if(strtotime($doctors->dob) > 0)
            {
                $row[]=date('d-m-Y',strtotime($doctors->dob));
            }
            else
            {
                $row[]="";
            }               
            if(strtotime($doctors->anniversary) > 0)
            {
                $row[]=date('d-m-Y',strtotime($doctors->anniversary));
            }
            else
            {
                $row[]="";
            }       
            $row[] = $doctors->panel_type;
            $row[] = $doctors->doc_schedule_type;
            $row[] = ($doctors->pincode=="") ? $doctors->doc_address : $doctors->doc_address.' - '.$doctors->pincode;
            $row[] = $doctors->email;
            $row[] = $doctors->alt_mobile_no;
            $row[] = $doctors->landline_no;
            $row[] = $doctors->pan_no;
            $row[] = $doctors->doc_reg_no;
            $row[] = $doctors->per_patient_timing;
            $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($doctors->created_date));  
           
            $btnedit='';
            $btndelete='';
            $btnview = '';
            $btn_rate_plan='';
            if($users_data['parent_id']==$doctors->branch_id){ 
                if(in_array('123',$users_data['permission']['action'])){
                     $btnedit = ' <a onClick="return edit_doctors('.$doctors->id.');" class="btn-custom" href="javascript:void(0)" style="'.$doctors->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
                     
                     
                     $btn_rate_plan = '<a  class="btn-custom"  href="'.base_url("doctor_rate_plan/index/".$doctors->id).'" title="Rate Plan"> Rate Plan</a>';
                }
            }
                if(in_array('125',$users_data['permission']['action'])){
                    $btnview=' <a class="btn-custom" onclick="return view_doctors('.$doctors->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
                }
            if($users_data['parent_id']==$doctors->branch_id){     
                if(in_array('124',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_doctors('.$doctors->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
                }
             } 
             
             $report =  '<a class="btn-custom" href="javascript:void(0)" onclick="doctor_report('.$doctors->id.')" title="Report"> Reports</a>';
             
            $row[] = $btnedit.$btn_rate_plan.$btnview.$btndelete.$report;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->doctors->count_all(),
                        "recordsFiltered" => $this->doctors->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function advance_search()
    {
        
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        $data['data']=get_setting_value('PATIENT_REG_NO');
        //print_r($data['data']);die;
        $data['country_list'] = $this->general_model->country_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['person_list'] = $this->general_model->employee_list();
        $data['rate_list'] = $this->general_model->get_rate_list();
        $data['days_list'] = $this->general_model->get_days_list(); 
         
        $data['form_data'] = array(
                                    "start_date"=>"",
                                    "end_date"=>"",
                                    "data_id"=>'',
                                    "branch_id"=>"",
                                    "doctor_code"=>"",
                                    "doctor_type"=>"",
                                    "doctor_name"=>"",
                                    "specilization_id"=>"",
                                    "mobile_no"=>"",
                                    "address"=>"",
                                  
                                    "country_id"=>"99",
                                    "pincode"=>"",
                                    "email"=>"",
                                    "alt_mobile_no"=>"",
                                    "landline_no"=>"",
                                    "doc_reg_no"=>"",
                                    "pan_no"=>"",
                                    "marketing_person_id"=>"",
                                    "doctor_pay_type"=>"",
                                    "consultant_charge"=>'',
                                    "emergency_charge"=>'',
                                    "country_code"=>"+91",
                                    'username'=>'',
                                    'dob'=>'',
                                    'anniversary'=>''
                                );
        
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('doctor_search', $merge);
        }
        $doctor_search = $this->session->userdata('doctor_search');
        if(isset($doctor_search) && !empty($doctor_search))
        {
            $data['form_data'] = $doctor_search;
        }
        //print '<pre>'; print_r($data['form_data']);die;
        $this->load->view('doctors/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('doctor_search');
    }

    /* doctor advance search */

	public function add()
	{
         unauthorise_permission(20,122);
		$this->load->model('general/general_model'); 
        $users_data = $this->session->userdata('auth_users');
		$data['page_title'] = "Add Doctor";
		$data['form_error'] = [];
		$data['country_list'] = $this->general_model->country_list();
		$data['specialization_list'] = $this->general_model->specialization_list();
        //print_r($data['specialization_list']);die;
        $specialization_row = count($data['specialization_list']);
		$data['person_list'] = $this->general_model->employee_list();
        $data['rate_list'] = $this->general_model->get_rate_list();
        $data['days_list'] = $this->general_model->get_days_list(); 
		$reg_no = generate_unique_id(3);
		$post = $this->input->post();
		$data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "doctor_code"=>$reg_no,
                                    "doctor_type"=>"",
                                    "doctor_name"=>"",
                                    "specilization_id"=>"",
                                    "mobile_no"=>"",
                                    "address"=>"",
                                    "address2"=>"",
                                    "address3"=>"",
                                    "city_id"=>"",
                                    "state_id"=>"",
                                    "country_id"=>"99",
                                    "pincode"=>"",
                                    "email"=>"",
                                    "alt_mobile_no"=>"",
                                    "landline_no"=>"",
                                    "doc_reg_no"=>"",
                                    "pan_no"=>"",
                                    "marketing_person_id"=>"",
                                    "doctor_pay_type"=>"",
                                    "status"=>"1",
                                    "consultant_charge"=>'',
                                    "emergency_charge"=>'',
                                    "country_code"=>"+91",
                                    /*"schedule_type"=>"daily",
                                    "days"=>"",
                                    "timings"=>"",*/
                                    "username"=>'',
                                    'dob'=>'',
                                    'anniversary'=>'',
                                    'per_patient_timing'=>'',
                                    'old_sign_img'=>'',
                                    'schedule_type'=>'0',
                                    'old_sign_img'=>'',
                                    "doctor_panel_type"=>"1",
                                    'specialization_row'=>$specialization_row,
                                    'qualification'=>"",
                                    'seprate_header'=>"2",
                                    'header_content'=>'',
                                    'opd_header'=>'',
                                    'billing_header'=>'',
                                    'prescription_header'=>''

 			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                if(!empty($_FILES['sign_img']['name']))
                { 
                $config['upload_path']   = DIR_UPLOAD_PATH.'doctor_signature/'; 
                $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                $config['max_size']      = 1000; 
                $config['encrypt_name'] = TRUE; 
                $this->load->library('upload', $config);
                if($this->upload->do_upload('sign_img')) 
                {
                $file_data = $this->upload->data(); 
                $doctor_id =$this->doctors->save($file_data['file_name']);
                echo 1;
                return false;
                } 

                }
                else
                {
                 $doctor_id = $this->doctors->save(); 
                }
                 //$doctor_id = $this->doctors->save();
                /////// Send SMS /////////
                //print_r($users_data['permission']['action']);die;
                if(in_array('640',$users_data['permission']['action']))
                {
                  if(!empty($post['mobile_no']))
                  { 
                    $parameter = array('{doctor_name}'=>'Dr. '.$post['doctor_name'],'{username}'=>'DOC000'.$doctor_id,'{password}'=>'PASS'.$doctor_id);
                    send_sms('doctor_registration',19,'',$post['mobile_no'],$parameter); 
                  }
                }
                ///////////////////////// 

                ////////// SEND EMAIL ///////////////////
                if(in_array('640',$users_data['permission']['action']))
                { 
                  if(!empty($post['email']))
                  { 
                    $this->load->library('general_functions'); 
                    $this->general_functions->email($post['email'],'','','','','1','doctor_registration','19',array('{doctor_name}'=>'Dr. '.$post['doctor_name'],'{username}'=>'DOC000'.$doctor_id,'{password}'=>'PASS'.$doctor_id)); 
                  }
                } 
                 
                ////////////////////////////////////////
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        $this->session->unset_userdata('comission_data');
		$this->load->view('doctors/add',$data);
	}

	public function edit($id="")
    {
         unauthorise_permission(20,123);
      if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->doctors->get_by_id($id);
        $data['doctor_availablity'] = $result['doctor_availablity'];
        $array_result = array();
        if(!empty($result['doctor_availablity']))
        {
            
            foreach ($result['doctor_availablity'] as $value) 
            {   //print_r($value);
                $array_result[$value->available_day] = $value->day_name;    
                 //$array_result[$value->available_day] = $value->available_day;
            }
        }
        $data['available_day'] = $array_result;

        // $reg_no = generate_unique_id(3);
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['person_list'] = $this->general_model->employee_list(); 
        $data['rate_list'] = $this->general_model->get_rate_list($id);
        $data['days_list'] = $this->general_model->get_days_list();
        $data['page_title'] = "Update Doctor";  
        $post = $this->input->post();
        $data['form_error'] = '';
         $dob ='';
        if(!empty($result['dob']) && $result['dob']!='0000-00-00' && $result['dob']!='1970-01-01')
        {
            $dob = date('d-m-Y',strtotime($result['dob']));
        } 



        $anniversary='';
        if(!empty($result['anniversary']) && $result['anniversary']!='0000-00-00' && $result['anniversary']!='1970-01-01')
        {
            $anniversary = date('d-m-Y',strtotime($result['anniversary']));
        }
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "doctor_code"=>$result['doctor_code'],
                                    "doctor_type"=>$result['doctor_type'],
                                    "doctor_name"=>$result['doctor_name'],
                                    "specilization_id"=>$result['specilization_id'],
                                    "mobile_no"=>$result['mobile_no'],
                                    "address"=>$result['address'],
                                    "address2"=>$result['address2'],
                                    "address3"=>$result['address3'],
                                    "city_id"=>$result['city_id'],
                                    "state_id"=>$result['state_id'],
                                    "country_id"=>$result['country_id'],
                                    "rate_plan_id"=>$result['rate_plan_id'],
                                    "pincode"=>$result['pincode'],
                                    "email"=>$result['email'],
                                    "alt_mobile_no"=>$result['alt_mobile_no'],
                                    "landline_no"=>$result['landline_no'],
                                    "doc_reg_no"=>$result['doc_reg_no'],
                                    "pan_no"=>$result['pan_no'],
                                    "marketing_person_id"=>$result['marketing_person_id'],
                                    "doctor_pay_type"=>$result['doctor_pay_type'],
                                    "status"=>$result['status'],
                                    "consultant_charge"=>$result['consultant_charge'],
                                    "emergency_charge"=>$result['emergency_charge'],
                                    
                                    "country_code"=>"+91",
                                    "days"=>$result['days'],
                                    "timings"=>$result['timings'],
                                    "username"=>$result['username'],
                                    'dob'=>$dob,
                                    'anniversary'=>$anniversary,
                                    'old_sign_img'=>$result['signature'],
                                    'per_patient_timing'=>$result['per_patient_timing'],
                                    'doctor_panel_type'=>$result['doctor_panel_type'],
                                    'schedule_type'=>$result['schedule_type'],
                                    'qualification'=>$result['qualification'],
                                    'seprate_header'=>$result['seprate_header'],
                                    'header_content'=>$result['header_content'],
                                    'opd_header'=>$result['opd_header'],
                                    'billing_header'=>$result['billing_header'],
                                    'prescription_header'=>$result['prescription_header'],
                                    'opd_header'=>$result['opd_header'],
                                    'billing_header'=>$result['billing_header'],
                                    'prescription_header'=>$result['prescription_header']


                                  );
                                  /*if($result['schedule_type']==1){
                                     $data['form_data']['schedule_type'] ="daily"; 
                                  }
                                  elseif($result['schedule_type']==2){
                                    $data['form_data']['schedule_type']="period";
                                  }  
                                  elseif($result['schedule_type']==3){
                                    $data['form_data']['schedule_type']="on_calls";
                                  }*/
       
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                if(!empty($_FILES['sign_img']['name']))
                { 
                    $config['upload_path']   = DIR_UPLOAD_PATH.'doctor_signature/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    if($this->upload->do_upload('sign_img')) 
                    {
                        $file_data = $this->upload->data(); 
                        $this->doctors->save($file_data['file_name']);
                        echo 1;
                        return false;
                    } 

                }
                else
                {
                    $this->doctors->save();
                    echo 1;
                    return false;
                }                
//$this->doctors->save();
                
                //echo 1;
                //return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->session->unset_userdata('comission_data'); 
       $this->load->view('doctors/add',$data);       
      }
    }

    public function check_email($str)
    {
        if(!empty($str))
        {
            $this->load->model('general/general_model','general');
            $post = $this->input->post();
            if(!empty($post['data_id']) && $post['data_id']>0)
            {
                return true;
            }
            else
            {
                $userdata = $this->general->check_email($str); 
                if(empty($userdata))
                {
                   return true;
                }
                else
                { 
                    $this->form_validation->set_message('check_email', 'Email already exists.');
                    return false;
                }
            }
        } 
    }
     
    private function _validate()
    {
        $field_list = mandatory_section_field_list(1);
        $users_data = $this->session->userdata('auth_users'); 
        $post = $this->input->post(); 
        $comission_data = $this->session->userdata('comission_data');

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('doctor_type', 'doctor type', 'trim|required'); 
        $this->form_validation->set_rules('doctor_name', 'doctor name', 'trim|required');
        $this->form_validation->set_rules('specilization_id', 'specilization', 'trim|required'); 
        $this->form_validation->set_rules('doctor_pay_type', 'sharing pattern', 'trim|required');   
        if(!empty($post['data_id']) && empty($comission_data))
        {
           $comission_data =  $this->doctors->doc_comission_data($post['data_id']);
        }
        
         if(isset($post) && $post['doctor_pay_type']==2)
        {
          $this->form_validation->set_rules('rate_plan_id', 'rate list', 'trim|required');   
        } 
        
       if($post['doctor_pay_type']==1 && empty($comission_data) && empty($post['data_id']))
       {
         
         $this->form_validation->set_rules('doctor_commission_data', 'doctor commission', 'trim|required'); 
       }

       if(!empty($field_list))
       {
            if($field_list[0]['mandatory_field_id']==3 && $field_list[0]['mandatory_branch_id']==
            $users_data['parent_id'])
            { 
                $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]');
            }
        } 
        $this->form_validation->set_rules('pan_no', 'pan no.', 'trim|min_length[10]|max_length[10]|alpha_numeric');
        $this->form_validation->set_rules('pincode', 'pincode', 'trim|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email|callback_check_email');
        $this->form_validation->set_rules('alt_mobile_no', 'alternate mobile no.', 'trim|numeric|min_length[10]|max_length[10]'); 
        $this->form_validation->set_rules('landline_no', 'landline no.', 'trim|numeric|min_length[9]|max_length[13]'); 

        if ($this->form_validation->run() == FALSE) 
        {  
            $username = '';
            if(isset($post['username']))
            {
                $username = $post['username'];
            }
            $schedule_type='';
            if(!empty($post['schedule_type']))
            {
                $schedule_type = $post['schedule_type']; 
            }

            $doctor_panel_type='';
            if(!empty($post['doctor_panel_type']))
            {
                $doctor_panel_type = $post['doctor_panel_type']; 
            }
            
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
										"data_id"=>$post['data_id'], 
										"doctor_code"=>$reg_no,
										"doctor_type"=>$post['doctor_type'],
										"doctor_name"=>$post['doctor_name'],
										"specilization_id"=>$post['specilization_id'],
										"mobile_no"=>$post['mobile_no'],
										"address"=>$post['address'],
                                        "address2"=>$post['address2'],
                                        "address3"=>$post['address3'],
										"city_id"=>$post['city_id'],
										"state_id"=>$post['state_id'],
										"country_id"=>$post['country_id'],
										"pincode"=>$post['pincode'],
										"email"=>$post['email'],
										"alt_mobile_no"=>$post['alt_mobile_no'],
										"landline_no"=>$post['landline_no'],
										"doc_reg_no"=>$post['doc_reg_no'],
										"pan_no"=>$post['pan_no'],
										"marketing_person_id"=>$post['marketing_person_id'],
										"doctor_pay_type"=>$post['doctor_pay_type'],
										"status"=>$post['status'],
                                        'doctor_panel_type'=>$doctor_panel_type,

                                        "consultant_charge"=>$post['consultant_charge'],
                                        'emergency_charge'=>$post['emergency_charge'],
                                        "country_code"=>"+91",
                                        'per_patient_timing'=>$post['per_patient_timing'],
                                        'schedule_type'=>$schedule_type,
                                        'username'=>$username,
                                            'old_sign_img'=>$post['old_sign_img'],
                                            'dob'=>$post['dob'],
                                            'anniversary'=>$post['anniversary'],
                                            'qualification'=>$post['qualification'],
                                            'seprate_header'=>$post['seprate_header'],
                                            'header_content'=>$post['header_content'],

                                            'opd_header'=>$post['opd_header'],
                                    'billing_header'=>$post['billing_header'],
                                    'prescription_header'=>$post['prescription_header'],


                                       );
                                
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
        unauthorise_permission(20,124);
       if(!empty($id) && $id>0)
       {
           $result = $this->doctors->delete($id);
           $response = "Doctor successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(20,124);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->doctors->deleteall($post['row_id']);
            $response = "Doctor successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        unauthorise_permission(20,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->doctors->get_by_id($id);

        $data['doctor_availablity'] = $data['form_data']['doctor_availablity'];
        $array_result = array();
        if(!empty($data['form_data']['doctor_availablity']))
        {
            foreach ($data['form_data']['doctor_availablity'] as $value) 
            {   
                $array_result[$value->available_day] = $value->day_name;    
            }
        }
        $data['available_day'] = $array_result;

        $data['days_list'] = $this->general_model->get_days_list();

        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('doctors/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(20,126);
        $data['page_title'] = 'Doctor Archive List';
        $this->load->helper('url');
        $this->load->view('doctors/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(20,126);
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('doctors/doctors_archive_model','doctors_archive'); 
        $list = $this->doctors_archive->get_datatables();
    
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $doctors) { 
            $no++;
            $row = array();
            if($doctors->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($doctors->state))
            {
                $state = " ( ".ucfirst(strtolower($doctors->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                  
            if($users_data['parent_id']==$doctors->branch_id){  
               
                    $row[] = '<input type="checkbox" name="doctors[]" class="checklist" value="'.$doctors->id.'">'.$check_script;
                
            }else{
                $row[]='';
            }
           $specilization_name ="";
           $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $specilization_name = $this->general_model->get_specilization_name($doctors->specilization_id);
            $row[] = $doctors->doctor_code;             
            $row[] = $doctors->doctor_name;
            $row[] = $specilization_name;
            $row[] = $doctor_type[$doctors->doctor_type];
            $row[] = $doctors->mobile_no; 
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($doctors->created_date));   
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
             if($users_data['parent_id']==$doctors->branch_id){  
                if(in_array('128',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_doctors('.$doctors->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
                }
                if(in_array('127',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$doctors->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                }
            }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->doctors_archive->count_all(),
                        "recordsFiltered" => $this->doctors_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(20,128);
        $this->load->model('doctors/doctors_archive_model','doctors_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->doctors_archive->restore($id);
           $response = "Doctor successfully restore in Doctor list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(20,128);
        $this->load->model('doctors/doctors_archive_model','doctors_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->doctors_archive->restoreall($post['row_id']);
            $response = "Doctor successfully restore in Doctor list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(20,127);
        $this->load->model('doctors/doctors_archive_model','doctors_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->doctors_archive->trash($id);
           $response = "Doctor successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(20,127);
        $this->load->model('doctors/doctors_archive_model','doctors_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->doctors_archive->trashall($post['row_id']);
            $response = "Doctor successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function doctors_dropdown()
  {
      $doctors_list = $this->doctors->doctors_list();
      $dropdown = '<option value="">Select Doctor</option>'; 
      if(!empty($doctors_list))
      {
        foreach($doctors_list as $doctors)
        {
           $dropdown .= '<option value="'.$doctors->id.'">'.$doctors->doctor_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  public function type_to_doctors($specilization_id="")
  {
      $doctors_list = $this->doctors->doctors_list($specilization_id);
      $dropdown = '<option value="">Select Doctor</option>'; 
      if(!empty($doctors_list))
      {
        foreach($doctors_list as $doctors)
        {
           $dropdown .= '<option value="'.$doctors->id.'">'.$doctors->doctor_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  public function type_attended_doctors($specilization_id="")
  {
      $doctors_list = $this->doctors->attended_doctors_list($specilization_id);
      $dropdown = '<option value="">Select Doctor</option>'; 
      if(!empty($doctors_list))
      {
        foreach($doctors_list as $doctors)
        {
           $dropdown .= '<option value="'.$doctors->id.'">'.$doctors->doctor_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  

  function get_comission($id="")
  {
    unauthorise_permission(38,224);
    $users_data = $this->session->userdata('auth_users');
    $json_data = [];
     if(!empty($id) && $id>0)
     {
        if($id==1)
        {
            $arr = array('lable'=>'Share Details', 'inputs'=>'<a href="javascript:void(0)" class="btn-commission"  onclick="comission();"><i class="fa fa-cog"></i> Commission</a>');
            $json_data = json_encode($arr);
        }
        else if($id==2)
        {
            $this->load->model('general/general_model');
            $rate_list = $this->general_model->get_rate_list();
            $drop = '<select name="rate_plan_id" id="rate_plan_id">
                     <option value="">Select Rate Plan</option>';
            if(!empty($rate_list))
            {
                foreach($rate_list as $rate)
                {
                    $drop .= '<option value="'.$rate->id.'">'.$rate->title.'</option>';
                }
            }
            $drop .= '</select>';
            //if(in_array('16',$users_data['permission']['action'])) {
                $drop.='<a href="javascript:void(0)" onclick="rate_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>';
            //}
            $arr = array('lable'=>'Rate list', 'inputs'=>$drop);
            $json_data = json_encode($arr);
        }
        echo $json_data; 
     }
  }

  public function add_comission()
  {
     $data['page_title'] = "Share Details";
     $this->load->model('general/general_model');
     $post = $this->input->post();
     if(isset($post['id']) && !empty($post['id']))
     {
        $comission_data = $this->doctors->doc_comission_data($post['id']);
        $this->session->set_userdata('comission_data',$comission_data);
     } 
      //$data['dept_list'] = $this->general_model->department_list();
     //$path_dept_list = $this->general_model->department_list('5'); 

     $data['dept_list'] = $this->general_model->department_list();    
     $path_dept_list = $this->general_model->active_department_list('5'); 
 
     $path_dept = [];
     if(!empty($path_dept_list))
     {
        foreach($path_dept_list as $path_department)
        {
           $path_dept[] = $path_department->department;
        } 
     }

     $data['path_dept'] = $path_dept;         
     if(isset($post['data']) && !empty($post['data']))
     { 
        $this->session->set_userdata('comission_data',$post);
        echo '1'; return false;
     }
     $this->load->view('doctors/add_comission',$data);
  }

  public function view_comission()
  {
     $data['page_title'] = "Share Details";
     $this->load->model('general/general_model');
     $post = $this->input->post();
     //$data['dept_list'] = $this->general_model->department_list();
     //$path_dept_list = $this->general_model->department_list('5'); 

     $data['dept_list'] = $this->general_model->department_list();    
     $path_dept_list = $this->general_model->active_department_list('5'); 

     $path_dept = [];
     if(!empty($path_dept_list))
     {
        foreach($path_dept_list as $path_department)
        {
           $path_dept[] = $path_department->department;
        } 
     }

     $data['path_dept'] = $path_dept;
     if(isset($post['id']) && !empty($post['id']))
     {
        $data['comission'] = $this->doctors->doc_comission_data($post['id']); 
     }  
     $this->load->view('doctors/view_comission',$data);
  }

    public function doctors_excel()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Doctor Reg. No.','Doctor Name','Specialization','Doctor Type','Mobile','Status');
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $col++;
            $row_heading++;
        }
        $list = $this->doctors->search_doctor_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0;
            foreach($list as $doctors)
            {
                $state = "";
                if(!empty($doctors->state))
                {
                    $state = " ( ".ucfirst(strtolower($doctors->state))." )";
                }
                $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
                $specilization_name ="";
                $specilization_name = $this->general_model->get_specilization_name($doctors->specilization_id);
                $doctor_types = $doctor_type[$doctors->doctor_type];
                $status = array('0'=>'Inactive','1'=>'Active');
                $doctor_status = $status[$doctors->status];
                array_push($rowData,$doctors->doctor_code,$doctors->doctor_name,$specilization_name,$doctor_types,$doctors->mobile_no,$doctor_status);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $doctors_data)
              {
                   $col = 0;
                   $row_val =1;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $doctors_data[$field]);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=doctor_list_".time().".xls");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
        
    
    }

    public function doctors_csv()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Doctor Reg. No.','Doctor Name','Specialization','Doctor Type','Mobile','Status');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->doctors->search_doctor_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0;
            foreach($list as $doctors)
            {
                $state = "";
                if(!empty($doctors->state))
                {
                    $state = " ( ".ucfirst(strtolower($doctors->state))." )";
                }
                $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
                $specilization_name ="";
                $specilization_name = $this->general_model->get_specilization_name($doctors->specilization_id);
                $doctor_types = $doctor_type[$doctors->doctor_type];
                $status = array('0'=>'Inactive','1'=>'Active');
                $doctor_status = $status[$doctors->status];
                array_push($rowData,$doctors->doctor_code,$doctors->doctor_name,$specilization_name,$doctor_types,$doctors->mobile_no,$doctor_status);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $doctors_data)
              {
                   $col = 0;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $doctors_data[$field]);
                        $col++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=doctor_list_".time().".csv");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }

    public function doctors_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->doctors->search_doctor_data();
        $this->load->view('doctors/doctors_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("doctors_list_".time().".pdf");
    }
    public function doctors_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->doctors->search_doctor_data();
      $this->load->view('doctors/doctors_html',$data); 
    }


    public function doctor_panel_charge()
    {
        $this->load->model('doctors/doctors_panel_model','doctors_panel');
        $data['page_title'] = 'Doctor panel charge List'; 
        $data['doctor_list'] = $this->doctors_panel->doctor_list();
        $post = $this->input->post();
        //print_r($data['doctor_list']); exit;
        if(isset($post) && !empty($post))
        {   
            if(!empty($post['doctor_id']))
            {
                $doctor_id = $this->doctors_panel->save_panel_charge();
                echo 1;
                return false;
            }
        }
        
        $this->load->view('doctors/add_panel_charge',$data);

    }

    public function doctor_panel_ajax()
    {  
        $this->load->model('doctors/doctors_panel_model','doctors_panel');
        $post = $this->input->post();
        //echo "<pre>";print_r($post); exit;
        $users_data = $this->session->userdata('auth_users');
        $list = $this->doctors_panel->get_datatables();   
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
       //print_r($list);
    if(!empty($list))
    {
        foreach ($list as $panel_history) 
        { 
           
            $no++;
            $row = array();
            $state = "";
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                  
            
           
            $row = array(); 
            $row[] = $panel_history->insurance_company;  
            $row[] = '<input type="text" id="charge" name="charge['.$panel_history->id.']" value="'.$panel_history->charge.'" >';
            $row[] = '<input type="text" id="emergency_charge" name="emergency_charge['.$panel_history->id.']" value="'.$panel_history->charge_emergency.'" >';
            $data[] = $row;
            $i++;
        }
    }
    else
    {
        $row[]='';
        $row[] = '<div class="text-center">No record found</div>';
        $row[]='';
        $data[] = $row;
    }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->doctors_panel->count_all(),
            "recordsFiltered" => $this->doctors_panel->count_filtered(),
            "data" => $data,
            );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }



    public function print_panel_charge($docotr_id='')
    {
        $data['charge_list'] ='';
        if(!empty($docotr_id))
        {
            $data['charge_list'] = $this->doctors->get_panel_list($docotr_id);   
        }
        $this->load->view('doctors/print_html',$data);
        
    }


    public function panel_advance_search()
    {
        $post = $this->input->post();
        //echo "<pre>"; print_r($post); exit;
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('panel_doctor_search', $post);
        }
    }

    // this function is used for sample excel sheet doctor list on 23-04-2018
      public function sample_import_doctors_excel()
    {
           
            // Starting the PHPExcel library
            //print_r('hello');
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Doctor Name(*)','Specialization','Mobile No.','Doctor Type(Referral,Attended,Both)','Marketing Person','Consulting Charge ','Emergency Charge','DOB(dd-mm-yyyy)','Anniversary(dd-mm-yyyy)','Doctor Panel Type(Normal,Panel)','Address','Country','State','City','PIN Code','Email','Alternate Mobile','LandLine(R)','PAN','Registration No.');

            
      
              $col = 0;
            foreach ($fields as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $col++;
            }
            $rowData = array();
            $data= array();
          
            // Fetching the table data
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            // Sending headers to force the user to download the file
            header('Content-Type: application/vnd.ms-excel charset=UTF-8');
            header("Content-Disposition: attachment; filename=doctors_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }

// this function is used for import excel sheet doctor list on 23-04-2018
     public function import_doctors_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Doctors excel';
        $arr_data = array();
        $header = array();
        $path='';

       // print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['doctor_list']) || $_FILES['doctor_list']['error']>0)
            {
               
               $this->form_validation->set_rules('doctor_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'patients/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('doctor_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 

                    //echo "<pre> dfd";print_r(DIR_UPLOAD_PATH.'patient/'); exit;
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];

                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }

                //echo "<pre>";print_r($arr_data); exit;
                if(!empty($arr_data))
                {
                    //echo '<pre>'; print_r($arr_data); exit;
                    $arrs_data = array_values($arr_data);
                    $total_doctors = count($arrs_data);
                    
                   $array_keys = array('doctor_name','specialization','mobile_no','doctor_type','marketing_person_id','consultant_charge','emergency_charge','dob','anniversary','doctor_panel_type','address','country','state','city','pincode','email','alt_mobile_no','landline_no','pan_no','doc_reg_no');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T');
                    $doctor_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $doctor_all_data= array();
                    for($i=0;$i<$total_doctors;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $doctor_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $doctor_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }

                   // echo '<pre>'; print_r($doctor_all_data); exit;
                    $this->doctors->save_all_doctors($doctor_all_data);
                }
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {
               $data['form_error'] = validation_errors();
            }
        }

        $this->load->view('doctors/import_doctors_excel',$data);
    } 
    
     public function load_transaction_modal()
    {
        $data['page_title']='Transaction Condition';
        $this->load->view('doctors/load_transaction_modal',$data);
    }
    
    public function print_doctor_report()
     {
            unauthorise_permission('42','245');
            $get = $this->input->get();
            $users_data = $this->session->userdata('auth_users');
            $branch_list= $this->session->userdata('sub_branches_data');
            $parent_id= $users_data['parent_id'];
            $branch_ids = array_column($branch_list, 'id'); 
            $data['branch_collection_list'] = [];
            $data['branch_detail']=$users_data;
            $data['company_data']=$this->session->userdata('company_data');
            //print_r($data['company_data']);
            $data['self_opd_collection_list'] = $this->doctors->opd_history_list($get);
            
            $data['self_billing_collection_list'] = $this->doctors->billing_history_list($get);
            $data['self_medicine_collection_list'] = $this->doctors->medicine_history_list($get);
            $data['self_ipd_collection_list'] = $this->doctors->ipd_history_list($get);
            $data['self_pathology_collection_list'] = $this->doctors->pathology_history_list($get);
            // consolidate collection op

            $data['self_ambulance_collection_list'] = ''; //$this->patient->ambulance_history_list($get);
            $data['self_ot_collection_list'] = $this->doctors->ot_history_list($get);
            $data['self_dialysis_collection_list'] = $this->doctors->dialysis_history_list($get);
            $data['self_inventory_collection_list'] = $this->doctors->inventory_history_list($get);
            $data['self_vacci_collection_list'] = ''; //$this->doctors->vacci_history_list($get);

             $data['self_eye_collection_list'] = $this->doctors->eye_history_list($get);
            $data['self_pedit_collection_list'] = $this->doctors->pedit_history_list($get);
            $data['self_dental_collection_list'] = $this->doctors->dental_history_list($get);
            $data['self_gyni_collection_list'] = $this->doctors->gyni_history_list($get);
            
            $data['self_day_care_collection_list'] = $this->doctors->day_care_history_list($get);
           
            $data['get'] = $get;
            //print_r($data);
            $this->load->view('doctors/list_consolidate_history_data',$data);  
     }
     
     
   function doctor_report($doctor_id)
   {
    
    $data['title'] = 'Sample Collected';
    $data['doctor_id'] = $doctor_id;
    $this->load->view('doctors/doctor_report', $data);
    
   }


}
