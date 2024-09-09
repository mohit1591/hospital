<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leads extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('crm/leads/leads_model','leads');
        $this->load->model('general/general_model'); 
        $this->load->library('form_validation');
    }

    public function index()
    {     
        unauthorise_permission(397,2430);
        $this->session->unset_userdata('advance_search'); 
        $this->session->unset_userdata('book_test');
        $this->session->unset_userdata('set_profile'); 
        $data['form_data'] = array(
                                   'lead_id'=>'',
                                   'start_date'=>date('d-m-Y'),
                                   'end_date'=>date('d-m-Y'),
                                   'lead_type_id'=>'',
                                   'lead_source_id'=>'',
                                   'name'=>'',
                                   'email'=>'',
                                   'phone'=>'',
                                   'age'=>'',
                                   'address'=>'',  
                                   'appointment_date'=>'',
                                   'appointment_time'=>'',
                                   'followup_date'=>'',
                                   'followup_time'=>'', 
                                   'call_status'=>'', 
                                   'department_id'=>''
                                 );
        $this->session->set_userdata('crm_advance_search', $data['form_data']);
        $data['page_title'] = 'Leads List';  
        $this->load->view('crm/leads/list',$data);
    }

    public function ajax_list()
    {   
        unauthorise_permission(397,2430);
        $users_data = $this->session->userdata('auth_users');  
        $list = $this->leads->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $leads) {
         // print_r($leads);die;
            $no++;
            $row = array();  
            
            $followup_date = '';
            if(!empty($leads['followup_date']) && strtotime($leads['followup_date'])>1000000) 
            {
                $followup_date = date('d-m-Y', strtotime($leads['followup_date'])).' '.date('h:i A', strtotime($leads['followup_time']));
            }

            $appointment_date = '';
            if(!empty($leads['appointment_date']) && strtotime($leads['appointment_date'])>1000000) 
            {
                $appointment_date = date('d-m-Y', strtotime($leads['appointment_date'])).' '.date('h:i A', strtotime($leads['appointment_time']));
            }
 
            $row[] = '<input type="checkbox" name="call_status[]" class="checklist" value="'.$leads['id'].'">'; 
            $row[] = $leads['crm_code']; 
            
            
            if($leads['department_id']=='-1')
            {
                $row[] = 'Vaccination';
            }
            elseif($leads['department_id']=='-2')
            {
                $row[] = 'Other';
            }
            else
            {
               $row[] = $leads['department'];
            }
            
            
            $row[] = $leads['lead_type']; 
            $row[] = $leads['source']; 
            $row[] = $leads['name'];  
            $row[] = $leads['phone']; 
            $row[] = $followup_date; 
            $row[] = $appointment_date;
            $row[] = $leads['last_remark']; 
            $row[] = $leads['uname']; 
            $row[] = $leads['current_status']; 
            //$row[] = date('d-m-Y h:i A', strtotime($leads['created_date']));  
            $btn = '';
            if(empty($leads['booking_id']))
            {


            if(in_array('2432',$users_data['permission']['action'])) 
            {
              if(empty($leads['status']))
              {
                $btn .= ' <a class="btn-custom" href="'.base_url('crm/leads/edit/'.$leads['id']).'" style="margin-bottom:5px;" ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
            }
            
            if(in_array('2433',$users_data['permission']['action'])) 
            {
              $btn .= ' <a href="javascript:void(0);" class="btn-custom" onClick="return lead_followup('.$leads['id'].');"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Follow-Up</a>';
            } 

            if(in_array('2434',$users_data['permission']['action'])) 
              {
                $btn .= ' <a class="btn-custom" href="'.base_url('crm/leads/history/'.$leads['id']).'" style="margin-top:5px;" ><i class="fa fa-history" aria-hidden="true"></i> History</a>';
              }
             /* if($leads['parent_status']==1)
             {
              $btn .= '<br/> <span style="font-weight:bold; color:green">Booked</font>';
             }*/

          }
          else
          {
             if(in_array('2434',$users_data['permission']['action'])) 
              {
                $btn .= ' <a class="btn-custom" href="'.base_url('crm/leads/history/'.$leads['id']).'" style="margin-top:5px;" ><i class="fa fa-history" aria-hidden="true"></i> History</a>';
              }
             /*if($leads['parent_status']==1)
             {
              $btn .= '<br/> <span style="font-weight:bold; color:green">Booked</font>';
             }*/ 
             
          }


             

            $row[] = $btn;       
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->leads->count_all(),
                        "recordsFiltered" => $this->leads->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
   /* function advance_search()
    {  
        $post = $this->input->post();   
        if(!empty($post))
        {
            $this->session->set_userdata('advance_search',$post); 
        }
    }

    function reset_search()
    {   
        $this->session->unset_userdata('advance_search'); 
    } */

    public function add()
    {   
      $users_data = $this->session->userdata('auth_users');  
       $crm_id = generate_unique_id(72); 
       $data['lead_type_list'] = $this->leads->lead_type_list();
       
       $data['lead_source_list'] = $this->leads->lead_source_list(); 
       //echo "sdsd"; die;
       //$data['department_list'] = $this->leads->department_list(); 
       $this->load->model('general/general_model');
       $data['department_list'] = $this->general_model->department_list();    
        /*$path_dept_list = $this->general_model->active_department_list('5'); 
        
        $path_dept = [];
        if(!empty($path_dept_list))
        {
            foreach($path_dept_list as $path_department)
            {
                $path_dept[] = $path_department->department;
            } 
        }
        
        $data['path_dept'] = $path_dept;*/   
       $data['dept_list'] = $this->leads->lab_department_list(); 
       $data['profile_list'] = $this->leads->profile_list();
       $data['operation_list']= $this->leads->operation_list();
       $data['country_list'] = $this->general_model->country_list();
       $data['call_status'] = $this->leads->call_status();
       $data['specialization_list'] = $this->leads->specialization_list($users_data['parent_id']);
       $data['form_error'] = [];
       $post = $this->input->post();
       
       //$data['specialization_list'] = $this->leads->specialization_list($post['branch_id']);
       $data['form_data'] = array(
                                   'lead_id'=>$crm_id,
                                   'data_id'=>'',
                                   'lead_type_id'=>'',
                                   'lead_source_id'=>'',
                                   'name'=>'',
                                   'email'=>'',
                                   'phone'=>'',
                                   'age_y'=>'',
                                   'age_m'=>'',
                                   'age_d'=>'',
                                   'gender'=>0,
                                   'home_collection'=>0,
                                   'address'=>'',
                                   'address2'=>'',
                                   'address3'=>'',
                                   'city_id'=>'',
                                   'state_id'=>'',
                                   'country_id'=>'',
                                   'call_date'=>date('d-m-Y'),
                                   'call_time'=>'',
                                   'call_remark'=>'',
                                   'call_status'=>'',
                                   'specialization_id'=>'',
                                   'attended_doctor'=>'',
                                   'appointment_date'=>'',
                                   'appointment_time'=>'',
                                   'followup_date'=>'',
                                   'followup_time'=>'',
                                   'total_amount'=>'0.00',
                                   'opd_service'=>'',
                                   'billing_service'=>'',
                                   'ipd_service'=>'', 
                                   'department_id'=>'',
                                   'dept_id'=>''

                                 );
       if(isset($post) && !empty($post))
       {
          $booking_list = $this->session->userdata('book_test');
          if($post['home_collection']==1)
          {
            $home_collection = $this->leads->get_home_collection_data();
            if(!empty($home_collection))
             {
               $charge = $home_collection['charge'];
               $this->session->set_userdata('home_collection',$charge);
             } 
          }
           
          if(!empty($booking_list))
          { 
            $test_ids_arr = array_keys($booking_list);
            $test_ids = implode(',', $test_ids_arr);
            $data['booked_test_list'] = $this->leads->test_list($test_ids);
          }
          if(!empty($post['dept_id']))
            {
              $data['head_list'] = $this->leads->test_head_list($post['dept_id'],$users_data['parent_id']);
            }
            
            $data['specialization_list'] = $this->leads->specialization_list($users_data['parent_id']);
            
            if(!empty($post['specialization_id']))
            {
               $data['doctor_list'] = $this->leads->doctor_specilization_list($post['specialization_id']); 
            }

            $data['form_data'] = $this->_validate();

            if($this->form_validation->run() == TRUE)
            {
               $this->leads->save();
               redirect(base_url('crm/leads'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
                //print_r($data['form_error']);die;
            }  
       }
       else
       {
          $this->session->unset_userdata('book_test');
          $this->session->unset_userdata('set_profile'); 
       }
       //print_r($data['lead_type_list']);die;
       $data['page_title'] = 'Add Lead';
       
       $data['save_url'] = base_url('crm/leads/add');
       $this->load->view('crm/leads/add_lead',$data); 
    }


    public function edit($id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
     if(!empty($id) && $id>0)
     {
       
       $result = $this->leads->get_by_id($id);
       $data['lead_type_list'] = $this->leads->lead_type_list();
       $data['lead_source_list'] = $this->leads->lead_source_list(); 
      // $data['department_list'] = $this->leads->department_list(); 
       $data['dept_list'] = $this->leads->lab_department_list(); 
       
       $this->load->model('general/general_model');
       $data['department_list'] = $this->general_model->department_list();    
        /*$path_dept_list = $this->general_model->active_department_list('5'); 
        
        $path_dept = [];
        if(!empty($path_dept_list))
        {
            foreach($path_dept_list as $path_department)
            {
                $path_dept[] = $path_department->department;
            } 
        }*/
        
        $data['path_dept'] = $path_dept;
       
       $data['profile_list'] = $this->leads->profile_list();
       $data['operation_list']= $this->leads->operation_list();
       $data['country_list'] = $this->general_model->country_list();
       $data['call_status'] = $this->leads->call_status();
       $data['form_error'] = [];
       $post = $this->input->post();
       $bid = $users_data['parent_id'];
        $data['specialization_list'] = $this->leads->specialization_list($bid);
        if(!empty($result['specialization_id']))
        {
           $data['doctor_list'] = $this->leads->doctor_specilization_list($result['specialization_id']); 
        }
        $home_collection_amount = '0.00';
        if($result['home_collection']==1)
        { 
          $home_collection = $this->leads->get_home_collection_data();
            if(!empty($home_collection))
             {
               $charge = $home_collection['charge'];
               $this->session->set_userdata('home_collection',$charge);
               $home_collection_amount = $charge;
             } 
        }


       //echo '<pre>';print_r($result);die;
       //$data['specialization_list'] = $this->leads->specialization_list($post['branch_id']);
       $data['form_data'] = array(
                                   'lead_id'=>$result['crm_code'],
                                   'data_id'=>$result['id'],
                                   'lead_type_id'=>$result['lead_type_id'],
                                   'lead_source_id'=>$result['lead_source_id'],
                                   'name'=>$result['name'],
                                   'email'=>$result['email'],
                                   'phone'=>$result['phone'],
                                   'age_y'=>$result['age_y'],
                                   'age_m'=>$result['age_m'],
                                   'age_d'=>$result['age_d'],
                                   'home_collection_amount'=>$home_collection_amount,
                                   'gender'=>$result['gender'],
                                   'address'=>$result['address'],
                                   'address2'=>$result['address2'],
                                   'address3'=>$result['address3'],
                                   'city_id'=>$result['city_id'],
                                   'state_id'=>$result['state_id'],
                                   'country_id'=>$result['country_id'],
                                   'call_date'=>$result['call_date'],
                                   'call_time'=>$result['call_time'],
                                   'call_status'=>$result['call_status'],
                                   'call_remark'=>$result['call_remark'], 
                                   'total_amount'=>$result['total_amount'], 
                                   'home_collection'=>$result['home_collection'], 
                                   'specialization_id'=>$result['specialization_id'],
                                   'attended_doctor'=>$result['attended_doctor'],
                                   'appointment_date'=>date('d-m-Y', strtotime($result['appointment_date'])),
                                   'appointment_time'=>date('h:i A', strtotime($result['appointment_time'])),
                                   'followup_date'=>date('d-m-Y', strtotime($result['followup_date'])),
                                   'followup_time'=>date('h:i A', strtotime($result['followup_time'])),
                                   'opd_service'=>$result['opd_service'],
                                   'billing_service'=>$result['billing_service'],
                                   'ipd_service'=>$result['ipd_service'], 
                                   'department_id'=>$result['department_id'],
                                   'total_amount'=>$result['total_amount'],
                                   'dept_id'=>$result['dept_id'],
                                   'ot_id'=>$result['ot_id'],
                                   'ot_service'=>$result['ot_service']
                                 );
       //echo '<pre>';print_r($data['form_data']);die;
       if(isset($post) && !empty($post))
       {
          if(!empty($post['dept_id']))
            {
              $data['head_list'] = $this->leads->test_head_list($post['dept_id'],$users_data['parent_id']);
            }
           
           $data['specialization_list'] = $this->leads->specialization_list($users_data['parent_id']);
            
            if(!empty($post['specialization_id']))
            {
               $data['doctor_list'] = $this->leads->doctor_specilization_list($post['specialization_id']); 
            }
            

            $data['form_data'] = $this->_validate();

            if($this->form_validation->run() == TRUE)
            {
               $this->leads->save();
               redirect(base_url('crm/leads'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
                //print_r($data['form_error']);die;
            }  
       }
       else
       {
            if(empty($post) && !empty($result['department_id']) && ($result['department_id']==8))
            {
                $lead_test_list = $this->leads->lead_test_list($result['id']); 
                if(!empty($lead_test_list))
                {
                   $test_list = array_column($lead_test_list, 'test_id');
                   $test_ids = implode(',',$test_list);
                   $test_data_list = $this->leads->test_list($test_ids);
                   $data['booked_test_list'] = $test_data_list;
                   
                   if(!empty($test_data_list))
                   {
                     $post_test = [];
                     foreach($test_data_list as $test)
                     { 
                        $post_test[$test->id] = array('id'=>$test->id, 'name'=>$test->test_name, 'price'=>$test->rate);
                     }  

                     $this->session->set_userdata('book_test', $post_test);
                   } 
                }


                $lead_profile_list = $this->leads->lead_profile_list($result['id']);
                if(!empty($lead_profile_list))
                {
                    foreach($lead_profile_list as $profile)
                    {
                        $this->set_profile2($profile['profile_id'], $profile['price']);
                    }
                }  
            }
       }
       //print_r($data['form_data']);die;

       $data['save_url'] = base_url('crm/leads/edit/').$id;
       $data['page_title'] = 'Edit Lead';
       $this->load->view('crm/leads/add_lead',$data); 
     } 
    }

    public function followup($lead_id="")
    {

        if(!empty($lead_id) && $lead_id>0)
        {
            $result = $this->leads->get_by_id($lead_id);
            $data['call_status'] = $this->leads->call_status();
            //print_r($result);die;
            $data['form_error'] = [];
            $data['form_data'] = array(
                                   'lead_id'=>$result['id'],
                                   'data_id'=>'',
                                   'call_date'=>'',
                                   'call_time'=>'',
                                   'followup_date'=>'',
                                   'followup_time'=>'',
                                   'call_status'=>'',
                                   'remark'=>''
                                 );
         $post = $this->input->post();
         if(isset($post) && !empty($post))
           { 
                $data['form_data'] = $this->_validate_followup(); 
                if($this->form_validation->run() == TRUE)
                {
                   $this->leads->save_followup();
                   echo 1;
                   return false;
                }
                else
                {
                    $data['form_error'] = validation_errors(); 
                }  
           }
            $data['page_title'] = 'Lead ('.$result['crm_code'].') Follow-Up';
            $this->load->view('crm/leads/followup',$data); 
        }
    }


    private function _validate()
    {
        $post = $this->input->post();   
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('lead_type_id', 'lead type', 'trim|required'); 
        $this->form_validation->set_rules('lead_source_id', 'lead source', 'trim|required'); 
        $this->form_validation->set_rules('name', 'name', 'trim|required'); 
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email'); 
        $this->form_validation->set_rules('phone', 'phone', 'trim|required|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('age', 'age', 'trim|numeric'); 
       // $this->form_validation->set_rules('appointment_date', 'appointment date', 'trim|required'); 
       // $this->form_validation->set_rules('appointment_time', 'appointment time', 'trim|required'); 
       // $this->form_validation->set_rules('followup_date', 'followup date', 'trim|required');
       // $this->form_validation->set_rules('followup_time', 'followup time', 'trim|required'); 

        $this->form_validation->set_rules('call_date', 'call date', 'trim|required');
        //$this->form_validation->set_rules('call_time', 'call time', 'trim|required'); 
        $this->form_validation->set_rules('call_status', 'call status', 'trim|required'); 

        $this->form_validation->set_rules('department_id', 'department', 'trim|required'); 
        if(!empty($post['department_id']) && $post['department_id']==1)
        {
          $this->form_validation->set_rules('specialization_id', 'specialization', 'trim|required');
          $this->form_validation->set_rules('attended_doctor', 'consultant', 'trim|required');
          $this->form_validation->set_rules('opd_service', 'services', 'trim|required'); 
        } 
        if(!empty($post['department_id']) && $post['department_id']==4)
        { 
          $this->form_validation->set_rules('billing_service', 'services', 'trim|required');
        } 

        if(!empty($post['department_id']) && $post['department_id']==5)
        { 
          $this->form_validation->set_rules('ipd_service', 'services', 'trim|required');
        } 

        if(!empty($post['department_id']) && $post['department_id']==6)
        { 
          $this->form_validation->set_rules('ot_id', 'surgery', 'trim|required');
          $this->form_validation->set_rules('ot_service', 'services', 'trim|required');
        }        

        $home_collection_amount = '0.00';
        if($post['home_collection']==1)
        { 
          $home_collection = $this->leads->get_home_collection_data();
            if(!empty($home_collection))
             {
               $charge = $home_collection['charge'];
               $this->session->set_userdata('home_collection',$charge);
               $home_collection_amount = $charge;
             } 
        }   
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(72);  
            $data['form_data'] = array(
                                           'lead_id'=>$reg_no,
                                           'data_id'=>$post['data_id'],
                                           'lead_type_id'=>$post['lead_type_id'],
                                           'lead_source_id'=>$post['lead_source_id'],
                                           'name'=>$post['name'],
                                           'email'=>$post['email'],
                                           'home_collection_amount'=>$home_collection_amount,
                                           'phone'=>$post['phone'],
                                           'age_y'=>$post['age_y'],
                                           'age_m'=>$post['age_m'],
                                           'age_d'=>$post['age_d'],
                                           'gender'=>$post['gender'],
                                           'address'=>$post['address'],
                                           'address2'=>$post['address2'],
                                           'address3'=>$post['address3'],
                                           'city_id'=>$post['city_id'],
                                           'state_id'=>$post['state_id'],
                                           'country_id'=>$post['country_id'],
                                           'call_date'=>$post['call_date'],
                                           'call_time'=>$post['call_time'],
                                           'call_status'=>$post['call_status'],
                                           'call_remark'=>$post['call_remark'],
                                           'total_amount'=>$post['total_amount'],
                                           'home_collection'=>$result['home_collection'], 
                                           'specialization_id'=>$post['specialization_id'],
                                           'attended_doctor'=>$post['attended_doctor'],
                                           'appointment_date'=>$post['appointment_date'],
                                           'appointment_time'=>$post['appointment_time'],
                                           'followup_date'=>$post['followup_date'],
                                           'followup_time'=>$post['followup_time'],
                                           'opd_service'=>$post['opd_service'],
                                           'billing_service'=>$post['billing_service'],
                                           'ipd_service'=>$post['ipd_service'], 
                                           'department_id'=>$post['department_id'],
                                           'dept_id'=>$post['dept_id'],
                                           'ot_id'=>$post['ot_id'],
                                           'ot_service'=>$post['ot_service'],
                                       ); 
            return $data['form_data'];
        }   
        
    }

    private function _validate_followup()
    {
        $post = $this->input->post();   
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('call_date', 'call date', 'trim|required'); 
        $this->form_validation->set_rules('call_time', 'call time', 'trim|required'); 
        //$this->form_validation->set_rules('followup_date', 'followup date', 'trim|required'); 
        //$this->form_validation->set_rules('followup_time', 'followup time', 'trim|required'); 
        $this->form_validation->set_rules('call_status', 'status', 'trim|required');
        $this->form_validation->set_rules('remark', 'remark', 'trim|required');         
        
        if ($this->form_validation->run() == FALSE) 
        {   
            $data['form_data'] = array(
                                           'lead_id'=>$post['lead_id'],
                                           'data_id'=>$post['data_id'],
                                           'call_date'=>$post['call_date'],
                                           'call_time'=>$post['call_time'],
                                           'followup_date'=>$post['followup_date'],
                                           'followup_time'=>$post['followup_time'],
                                           'call_status'=>$post['call_status'],
                                           'remark'=>$post['remark'],
                                       ); 
            return $data['form_data'];
        }   
        
    }


    public function specialization_list()
    {   
        $users_data = $this->session->userdata('auth_users');
        $branch_id = $users_data['parent_id'];
        //$referral_doctor_id = $this->session->userdata('referral_doctor_id');
        $data = '<option value="">Select Specialization</option>';  
        $specialization_list = $this->leads->specialization_list($branch_id); 
        if(!empty($specialization_list))
        {
            foreach($specialization_list as $specialization)
            {
                //if($doctors->id!==$referral_doctor_id){

                    $data .= '<option value="'.$specialization->id.'">'.ucfirst(strtolower($specialization->specialization)).'</option>';
                //}
            }
        }
         

        echo $data;
    }

    public function doctor_specilization_list($specilization_id="")
    {
        //$referral_doctor_id = $this->session->userdata('referral_doctor_id');
        $data = '<option value="">Select Consultant</option>';
        if(!empty($specilization_id))
        {
            $this->session->set_userdata('specilization_id',$specilization_id);
            $doctors_list = $this->leads->doctor_specilization_list($specilization_id); 
            if(!empty($doctors_list))
            {
                foreach($doctors_list as $doctors)
                {
                    //if($doctors->id!==$referral_doctor_id){

                        $data .= '<option value="'.$doctors->id.'">'.ucfirst(strtolower($doctors->doctor_name)).'</option>';
                    //}
                }
            }
        }
        else
        {
            $doctors_list = $this->general->doctor_specilization_list();
            if(!empty($doctors_list))
            {
                foreach($doctors_list as $doctors)
                {
                    if($doctors->id!==$referral_doctor_id){

                        $data .= '<option value="'.$doctors->id.'">'.ucfirst(strtolower($doctors->doctor_name)).'</option>';
                    }
                }
            }
        }

        echo $data;
    }


    public function particulars_list($particulars_id="")
    {
        if(!empty($particulars_id))
        {
            $particulars_list = $this->general->particulars_list($particulars_id); 
            if(!empty($particulars_list))
            {
                $charge = '';
                foreach($particulars_list as $particulars)
                {
                   $charge = $particulars->charge;
                }
            }
        }
            $pay_arr = array('charges'=>$charge, 'amount'=>$charge,'quantity'=>1);
            $json = json_encode($pay_arr,true);
            echo $json;
    }

    function dept_test_heads($dept_id="")
    {
      if($dept_id>0)
      {
         $options = "";
         $test_head_list = $this->leads->test_head_list($dept_id,184);
         if(!empty($test_head_list))
         {
            foreach($test_head_list as $test_head)
            {
               $options .= '<option value="'.$test_head->id.'">'.ucwords(strtolower($test_head->test_heads)).'</option>';
            } 
         }
         echo $options;
      }
    }

    function test_list($head_id="",$profile_id="",$search="",$dept_id="")
    {
      $users_data = $this->session->userdata('auth_users');
      $post=$this->input->post();
      if(isset($post) && !empty($post))
      {
        $head_id=$this->input->post('test_head_id');
        $profile_id=$this->input->post('profile_id');
        $search=$this->input->post('search_text');
        $dept_id=$this->input->post('dept_id');
      }
      $rows = '<thead class="bg-theme"><tr>
                    <th width="60" align="center" style="text-align: center;">
                     <input name="selectall" class="" onClick="toggle(this);" value="" type="checkbox">
                    </th>
                    <th>Test ID</th>
                    <th>Test Name</th> 
                    <th>Rate</th>
                  </tr></thead>';
      if($head_id>0 OR $profile_id>0 OR !empty($search)  OR $dept_id>0)
      { 
         $child_list = $this->leads->head_test_list($head_id,$profile_id,$search,$dept_id);
         //print_r($child_list);die 
         if(!empty($child_list))
         {    
            //$sample_type_list=$this->leads->sample_type_list_new();
            /*$dropdown='';
            if(!empty($sample_type_list))
            {
              foreach($sample_type_list as $test_sample_type)
              {
                $checked = '';
                if($test_sample_type->id==$sample_id)
                {
                  $checked = 'selected="selected"';
                }

                  
                 $dropdown .= '<option '.$checked.' value="'.$test_sample_type->sample_type.'">'.$test_sample_type->sample_type.'</option>';
              }
            }*/ 
            foreach($child_list as $child)
            {     
 
                //$test_panel_rate = $this->session->userdata('test_panel_rate');
               $master_rate = $child->rate;
               $rows .= '<tr>
                                  <td width="60" align="center"><input type="checkbox" name="test_data['.$child->id.'][id]" class="child_checkbox" value="'.$child->id.'" ></td>
                                  <td>'.$child->test_code.'</td>
                                  <td>'.$child->test_name.'</td>
                                   
                                  
                                  <td>'.$master_rate.'</td>
                              </tr>';
            } 
         }
         else
         {
             $rows .= '<tr>  
                        <td colspan="4" style="text-align:center;"><div class="text-danger">Test not available.</div></td>
                      </tr>';
         }
         echo $rows;
      }
    }



    public function set_booking_test()
    { 
       $post =  $this->input->post();
       //print_r($post);die;
       if(isset($post['test_data']) && !empty($post['test_data']))
       {
         $booked_test = $this->session->userdata('book_test');
         
         $book_test = [];
         if(!empty($post['test_data']))
         { 
           $post_test = [];
           foreach($post['test_data'] as $test_data)
           {
             if(!empty($test_data['id']))
             {
               $post_test[$test_data['id']] = array('id'=>$test_data['id'], 'price'=>$test_data['price']); 
             }
             
           }
         }



         if(isset($booked_test) && !empty($booked_test))
         { 
            $book_test_arr = $booked_test+$post_test;
         } 
         else
         {
            $book_test_arr = $post_test;
         }
        // $book_test = array_unique($book_test);
         
         $this->session->set_userdata('book_test',$book_test_arr);
         $booked_test = $this->session->userdata('book_test');
         //echo '<pre>'; print_r($booked_test);die;
         $this->list_booked_test();
       }
    }
  

    public function list_booked_test()
    {
       $booked_test = $this->session->userdata('book_test');
       $profile_data = $this->session->userdata('set_profile'); 
       //echo '<pre>'; print_r($profile_data);die;
       $total_test = count($booked_test);  
       $profile_row = "";
       $p_order = 1;
       $profile_order = 1;
       $profile_order_cell = 0;
       if(isset($profile_data) && !empty($profile_data))
        {  

          foreach($profile_data as $profile)
          {
             $profile_order_cell = $profile['order'];
             $profile_row .= '<tr>
                              <td width="40" align="center">
                                 <input type="checkbox" name="test_id[]" class="booked_checkbox" value="'.$profile['id'].'" >
                              </td>
                              <td>'.$profile['id'].'</td>
                              <td>'.$profile['name'].'</td> 
                              <td>'.$profile['price'].'</td>
                          </tr>';
          } 
        }

       $rows = '<thead class="bg-theme"><tr> 
                    <th width="60" align="center" style="text-align:center;">
                     <input name="selectall" class="" onClick="final_toggle(this);" value="" type="checkbox">
                    </th>
                    <th>Test ID</th>
                    <th>Test Name</th> 
                    <th>Rate</th>
                  </tr></thead>';  
         
         if(isset($booked_test) && !empty($booked_test))
         { 
            $test_ids_arr = array_keys($booked_test);
            $test_ids = implode(',',$test_ids_arr);
            $test_list = $this->leads->test_list($test_ids);
            $i = 1;
            if($total_test>1)
            {
              $profile_order = $total_test-$profile_order_cell;
              if($profile_order==0)
              {
                $profile_order = 1;
              }
            }
              
            foreach($test_list as $test)
            { 
               $master_rate = $booked_test[$test->id]['price'];//$this->make_master_price($test->rate);
               //$sample_type_id = $booked_test[$test->id]['sample_type_id'];
               $rows .= '<tr>
                                  <td width="60" align="center"><input type="checkbox" name="test_id[]" class="booked_checkbox" value="'.$test->id.'" ></td>
                                  <td>'.$test->test_code.'</td>
                                  <td>'.$test->test_name.'</td> 
                                  <td>'.$test->rate.'</td>
                              </tr>';
                              
            $i++;
            } 
            if(isset($profile_data) && !empty($profile_data))
              {
                 /*if($i==$profile_order)
                 {
                   $rows .= $profile_row;
                 }*/
                 $rows .= $profile_row;
              }
         }
         else
         {
            if(isset($profile_data) && !empty($profile_data))
            {
              $rows .= $profile_row;
            }
            else
            {
             $rows .= '<tr>  
                        <td colspan="4"><div class="text-danger">Test not available.</div></td>
                      </tr>';
            }          
         } 
         echo $rows;
    }


   public function set_profile($profile_id="",$price="")
  {
       $post=$this->input->post(); 
       if(!empty($profile_id) && is_numeric($profile_id) && $profile_id>0)
       {
          $profile_arr = $this->session->userdata('set_profile'); 
          if(!isset($profile_arr))
          {
            $profile_arr = [];
          }
          $booking_doctor_type = $this->session->userdata('booking_doctor_type'); 
          $booking_list = $this->session->userdata('book_test'); 
          $result = $this->leads->profile_price($profile_id); 
          $master_rate = $result['master_rate'];
          $base_rate = $result['base_rate']; 
          if(!empty($post['profile_price_updated']))
          {
            $master_rate = $post['profile_price_updated'];
            $base_rate = $post['profile_price_updated'];
          }

          if(!empty($price))
          {
             $base_rate = $price;
             $master_rate = $price;
          } 
          
          ////// Set Profile Price ///
           
          ////// End Profile Price //////
          $total_test = 1;
          if(!empty($booking_list))
          {
            $total_test = count($booking_list);
          }

/*print and name*/
        if(!empty($result['print_name']))
          {
            $profile_name = $result['profile_name'].' ('.$result['print_name'].')';
          }
          else
          {
            $profile_name = $result['profile_name'];
          }

          $profile_arr[$profile_id] = array('id'=>$profile_id, 'name'=>$profile_name, 'order'=>$total_test, 'price'=>$master_rate,'base_price'=>$base_rate);
          //$total_amount = $total_amount+$base_rate;
           //echo "<pre>";print_r($profile_arr);die;
          $this->session->set_userdata('set_profile',$profile_arr);
          echo 1; 
       }
       else
       {
         echo 'Please select test profile.';
       }
  }


   public function set_profile2($profile_id="",$price="")
  {
       $post=$this->input->post(); 
       if(!empty($profile_id) && is_numeric($profile_id) && $profile_id>0)
       {
          $profile_arr = $this->session->userdata('set_profile'); 
          if(!isset($profile_arr))
          {
            $profile_arr = [];
          }
          $booking_doctor_type = $this->session->userdata('booking_doctor_type'); 
          $booking_list = $this->session->userdata('book_test'); 
          $result = $this->leads->profile_price($profile_id); 
          $master_rate = $result['master_rate'];
          $base_rate = $result['base_rate']; 
          if(!empty($post['profile_price_updated']))
          {
            $master_rate = $post['profile_price_updated'];
            $base_rate = $post['profile_price_updated'];
          }

          if(!empty($price))
          {
             $base_rate = $price;
             $master_rate = $price;
          } 
          
          ////// Set Profile Price ///
           
          ////// End Profile Price //////
          $total_test = 1;
          if(!empty($booking_list))
          {
            $total_test = count($booking_list);
          }

/*print and name*/
        if(!empty($result['print_name']))
          {
            $profile_name = $result['profile_name'].' ('.$result['print_name'].')';
          }
          else
          {
            $profile_name = $result['profile_name'];
          }

          $profile_arr[$profile_id] = array('id'=>$profile_id, 'name'=>$profile_name, 'order'=>$total_test, 'price'=>$master_rate,'base_price'=>$base_rate);
          //$total_amount = $total_amount+$base_rate;
           //echo "<pre>";print_r($profile_arr);die;
          $this->session->set_userdata('set_profile',$profile_arr);
           
       } 
  }


  public function profile_price($id="",$panel_id="")
    {
         $master_rate ='0.00';   
         if(!empty($id) && is_numeric($id) && $id>0)
         {
           $booking_doctor_type = $this->session->userdata('booking_doctor_type');  
           $result = $this->leads->profile_price($id,$panel_id);
           if(!empty($result))
           {
              $master_rate = $result['master_rate'];
           } 
         }  
        echo $master_rate; 
  }

  public function remove_booked_test()
    {
       $post =  $this->input->post();
       
       if(isset($post['test_id']) && !empty($post['test_id']))
       {
           $booked_test = $this->session->userdata('book_test');
           $booked_session_test = $booked_test;
           $profile_data = $this->session->userdata('set_profile');
           $test_data = []; 

           if(!empty($booked_test))
           {
              foreach($post['test_id'] as $tid)
              {
                 if(is_numeric($tid) && array_key_exists($tid,$booked_test))
                 {
                    unset($booked_test[$tid]);
                 } 
              }
              //$profile_data = $this->session->userdata('set_profile');
              //$test_ids_arr = array_keys($booked_test);
              //$test_data = array_diff($test_ids_arr,$post['test_id']);  
              //print_r($profile_data);die;
           }

           if(!empty($profile_data))
           {
              //echo "<pre>"; print_r($profile_data);die;
              $profile_data = $this->session->userdata('set_profile');
              //$profile_list = $profile_data;
              foreach($post['test_id'] as $pid)
              {  //print_r($profile_data[$pid]); die;
                 if(!empty($profile_data) && isset($profile_data[$pid]))
                 {// echo "ddd";die;
                    unset($profile_data[$pid]); 
                    /*foreach($profile_data as $p_key=>$profile)
                    {
                      if($tid == 'p_'.$p_key)
                      {  
                         unset($profile_list[$p_key]); 
                      }
                    }*/ 
                    
                 }//echo "eee";die;
              }
              $this->session->set_userdata('set_profile',$profile_data); 
              
              
           }
           
           if(!empty($booked_session_test))
           {
             $test_ids_arr = array_keys($booked_test);
             $this->session->set_userdata('book_test',$booked_test);
             $test_ids = implode(',', $test_ids_arr);
           } 
           
           $this->list_booked_test();
       }
    } 


    public function history($lead_id='')
    {     
        unauthorise_permission(397,2434);
        if(!empty($lead_id) && $lead_id>0)
        {
          $data['lead_data'] = $this->leads->get_by_id($lead_id);
          $this->session->unset_userdata('advance_search'); 
          $data['page_title'] = 'Lead History ('.$data['lead_data']['crm_code'].')'; 
          $data['lead_id'] = $lead_id;  
          
          $this->load->view('crm/leads/history',$data);
        } 
    }

    public function ajax_list_history($lead_id)
    {   
        unauthorise_permission(397,2434);
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('crm/leads/leads_history_model','leads_history_model');
        $list = $this->leads_history_model->get_datatables($lead_id);
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $leads) {
         // print_r($leads);die;
            $no++;
            $row = array();  
            
            $followup_date = '';
            if(!empty($leads['followup_date']) && strtotime($leads['followup_date'])>1000000) 
            {
                $followup_date = date('d-m-Y', strtotime($leads['followup_date'])).' '.date('h:i A', strtotime($leads['followup_time']));
            }

            $followup_time = '';
            if(!empty(strtotime($leads['followup_time']))) 
            {
                $followup_time = date('h:i A', strtotime($leads['followup_time']));
            }

            $appointment_date = '';
            if(!empty($leads['call_date']) && strtotime($leads['call_date'])>100000) 
            {
                $appointment_date = date('d-m-Y', strtotime($leads['call_date']));
            }

            $appointment_time = '';
            if(!empty(strtotime($leads['call_time']))) 
            {
                $appointment_time = date('h:i A', strtotime($leads['call_time']));
            }

            
            if($leads['parent_status']==1)
            {
                $call_status .= $leads['callstatus'].'<br/><font color="red"><b>Started</b></font>';
            }
            else
            {
              $call_status = $leads['callstatus'];
            }
 
             
            $row[] = $i; 
            $row[] = $appointment_date; 
            $row[] = $appointment_time; 
            $row[] = $followup_date; 
            $row[] = $followup_time;  
            $row[] = $call_status; 
            $row[] = $leads['call_remark'];   
            $row[] = date('d-m-Y h:i A', strtotime($leads['created_date']));      
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->leads_history_model->count_all($lead_id),
                        "recordsFiltered" => $this->leads_history_model->count_filtered($lead_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function advance_search()
    {
        $data['page_title'] = 'Advance Search'; 
        $data['lead_type_list'] = $this->leads->lead_type_list();
        $data['lead_source_list'] = $this->leads->lead_source_list(); 
        $data['department_list'] = $this->leads->department_list();
        $data['call_status'] = $this->leads->call_status();
        $data['dept_list'] = $this->leads->lab_department_list(); 
        $data['profile_list'] = $this->leads->profile_list();
        $data['operation_list']= $this->leads->operation_list();
        $data['form_data'] = $this->session->userdata('crm_advance_search');
        $this->load->view('crm/leads/advance_search',$data);
    }


    public function set_advance_search()
    { 
       $post = $this->input->post(); 
       if(!empty($post))
       { 
         $this->session->set_userdata('crm_advance_search',$post);
       }

       $advance_search = $this->session->userdata('crm_advance_search');  
       echo '1';
    }

    public function reset_advance_search()
    {
       $this->session->unset_userdata('crm_advance_search');
    }

    public function set_home_collection($type='0')
    {
      $charge = '0.00';
      if($type==1)
      {
           $home_collection = $this->leads->get_home_collection_data();

           if(!empty($home_collection))
           {
             $charge = $home_collection['charge'];

             $this->session->set_userdata('home_collection',$charge);
           }
      }
      else
      {
         $this->session->unset_userdata('home_collection');
      }
      echo  $charge;
    }

    public function set_total_payment()
    {
        $booked_test = $this->session->userdata('book_test');
        $profile_data = $this->session->userdata('set_profile'); 
        $home_collection = $this->session->userdata('home_collection');
        $total_amount = 0;
        if(!empty($home_collection))
        {
          $total_amount = $home_collection;
        }
        
        if(isset($profile_data) && !empty($profile_data))
        {   
          foreach($profile_data as $profile)
          {
            $total_amount = $total_amount+$profile['price'];
          }
        }  


        if(isset($booked_test) && !empty($booked_test))
        {
                    $test_ids_arr = array_keys($booked_test);
                    $test_ids = implode(',',$test_ids_arr);
                    $test_list = $this->leads->test_list($test_ids);               
                    foreach($test_list as $test)
                    {
                      $total_amount = $total_amount+$test->rate; 
                    }
        }



        echo number_format($total_amount,2, '.', '');
    }


     public function send_sms()
    {
        $data['page_title'] = 'Send SMS/Email';    
        $data['sms_template'] = $this->leads->get_sms();
        $data['email_template'] = $this->leads->get_email();
        $post = $this->input->post();     

        if(!empty($post) && $post['status']=='send')
        {
          $lead_list = $this->session->userdata('sms_id');
          if(!empty($lead_list))
          {
            foreach($lead_list as $lead)
            { 
              $lead_data = $this->leads->get_by_id($lead);  
              //print_r($lead_data);die;
              if(!empty($lead_data))    
              { 
                  if(!empty($lead_data['phone']))
                  {
                    $adate = date('d-m-Y', strtotime($lead_data['appointment_date'])).' '.date('h:i A', strtotime($lead_data['appointment_time']));
                    $parameter = array('{name}'=>$lead_data['phone'],'{appointment_date}'=>$adate,'{lead_id}'=>$lead_data['crm_code']);
                    send_sms('crm_lead',35,'',$lead_data['phone'],$parameter,$post['sms_msg']);  
                  } 

                  if(!empty($lead_data['email']))
                  {
                     $adate = date('d-m-Y', strtotime($lead_data['appointment_date'])).' '.date('h:i A', strtotime($lead_data['appointment_time']));
                     $this->load->library('general_functions'); 
                     $parameter = array('{name}'=>$lead_data['phone'],'{appointment_date}'=>$adate,'{lead_id}'=>$lead_data['crm_code']);
                     $this->general_functions->email($post['email'],'','','','','1','crm_lead','35',$parameter, $post['email_msg']); 
                  }
              }
            }
          }
           
          $this->session->unset_userdata('sms_id');
          echo '1';die;
        }
        $this->session->set_userdata('sms_id',$post['row_id']);
        $this->load->view('crm/leads/send_sms',$data);
    }
    
    
    public function lead_excel()
    {
       // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          
          // Field names in the first row
          $fields = array('Lead ID','Department','Lead Type','Source','Name','Phone','Follow-Up Date/Time','Appointment Date/Time','Last Remarks','Created By','Status');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          foreach ($fields as $field)
          {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
              $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(16);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          $list = $this->leads->search_crm_data();
          //echo "<pre>"; print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $leads)
               {
                   
                    $followup_date = '';
                    if(!empty($leads['followup_date']) && strtotime($leads['followup_date'])>1000000) 
                    {
                        $followup_date = date('d-m-Y', strtotime($leads['followup_date'])).' '.date('h:i A', strtotime($leads['followup_time']));
                    }
        
                    $appointment_date = '';
                    if(!empty($leads['appointment_date']) && strtotime($leads['appointment_date'])>1000000) 
                    {
                        $appointment_date = date('d-m-Y', strtotime($leads['appointment_date'])).' '.date('h:i A', strtotime($leads['appointment_time']));
                    }
                    
                    if($leads['department_id']=='-1')
                    {
                        $department_id = 'Vaccination';
                    }
                    elseif($leads['department_id']=='-2')
                    {
                        $department_id = 'Other';
                    }
                    else
                    {
                       $department_id = $leads['department'];
                    }
                    
                    array_push($rowData,$leads['crm_code'],$department_id,$leads['lead_type'],$leads['source'],$leads['name'],$leads['phone'],$followup_date,$appointment_date,$leads['last_remark'],$leads['uname'],$leads['current_status']);
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
               foreach($data as $boking_data)
               {
                    $col = 0;
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);

                         $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                         $col++;
                         $row_val++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=crm_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }
    
    
    public function lead_pdf()
    {
        
        $data['print_status']="";
        $data['data_list'] = $this->leads->search_crm_data();
        $this->load->view('crm/leads/leads_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("leads_list_".time().".pdf");   
    }
    
    public function lead_print()
    {
       
       $data['print_status']="1";
       $data['data_list'] = $this->leads->search_crm_data();
       $this->load->view('crm/leads/leads_html',$data);  
    }


     
 
}
?>