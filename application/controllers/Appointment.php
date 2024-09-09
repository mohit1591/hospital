<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('appointment/appointment_model','appointment');
		$this->load->library('form_validation');
    }

    
    public function index()
    {
        unauthorise_permission(93,594);
        $this->session->unset_userdata('appointment_search');
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
        $data['form_data'] = array('patient_name'=>'','mobile_no'=>'','appointment_code'=>'','mobile_no'=>'','appointment_from_date'=>$start_date, 'appointment_end_date'=>$end_date,'app_status'=>1);
        $this->session->set_userdata('appointment_search', $data['form_data']);
        $data['page_title'] = 'Appointment List'; 
        $this->load->view('appointment/list',$data);
    }

    public function ajax_list()
    {   

        unauthorise_permission(93,594);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->appointment->get_datatables();
        $recordsTotal =$this->appointment->count_all();
        $recordsFiltered = $recordsTotal;
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test) {
         // print_r($test);die;
            $no++;
            $row = array(); 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            
            } 
            
            if($users_data['parent_id']==$test->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test->id.'">'; 
             }else{
               $row[]='';
             }
            $row[] = $test->appointment_code;
            $row[] = $test->patient_name;
            $row[] = $test->mobile_no;
            $appointment_date='';
            if($test->appointment_date!='0000-00-00' && $test->appointment_date!='1970-01-01'){
              $appointment_date = date('d-m-Y',strtotime($test->appointment_date));
            }
            
             $appointment_time = date('h:i A',strtotime($test->appointment_time));
             
            $row[] =$appointment_date.' '.$appointment_time;
            $row[] = $test->patient_reg_no;
            $row[] = $test->gender;
            $row[] = $test->address;
            $row[] = $test->father_husband_simulation." ".$test->father_husband;
            $row[] = $test->patient_email;
            $row[] = $test->insurance_type;
            $row[] = $test->insurance_company;

            $row[] = $test->patient_source;
            $row[] = $test->disease;
            $row[] = $test->doctor_hospital_name;
            $row[] = $test->specialization;


            $row[] = "Dr. ".$test->doctor_name;
            $row[] = date('H:i A', strtotime($test->appointment_time)); 
            $row[] = $test->policy_no;
            //Action button /////
            $btn_confirm="";
            $btn_edit = ""; 
            $btn_delete = ""; 
            $btn_prescription = ""; 
            $blank_btn_print =''; 
            $btn_view='';
            
            if($test->type=='1')
            {
                
                if($users_data['parent_id'] == $test->branch_id)
                 {
                 if(in_array('531',$users_data['permission']['action'])){
                      $btn_confirm = ' <a href="javascript:void(0);" class="btn-custom" onclick="return confirm_booking('.$test->id.');" title="Confirm Appointment"><i class="fa fa-pencil"></i> Confirm </a>';
                 }
               }
    
                if($users_data['parent_id'] == $test->branch_id)
                 {
                 if(in_array('524',$users_data['permission']['action'])){
            		    $btn_edit = ' <a class="btn-custom" href="'.base_url("appointment/edit/".$test->id).'" title="Edit Appointment"><i class="fa fa-pencil"></i> Edit</a>';
                  } 
            }
             
              
              if(in_array('525',$users_data['permission']['action'])){
                    $btn_delete = ' <a class="btn-custom" onClick="return delete_appointment('.$test->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
               }
            
            }
            else
            {
               
                
                if($users_data['parent_id'] == $test->branch_id)
                 {
                 if(in_array('531',$users_data['permission']['action'])){
                      $btn_confirm = ' <a class="btn-custom" href="javascript:void(0)" disabled title="Already Confirmed"><i class="fa fa-pencil"></i> Already Confirmed </a>';
                 }
               }
    
                if($users_data['parent_id'] == $test->branch_id)
                 {
                 if(in_array('524',$users_data['permission']['action'])){
            		    $btn_edit = ' <a  disabled class="btn-custom" href="javascript:void(0)" title="Already Confirmed"><i class="fa fa-pencil"></i> Edit</a>';
                  } 
            }
             
              
              if(in_array('525',$users_data['permission']['action'])){
                    $btn_delete = ' <a class="btn-custom" disabled href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
               } 
            }
            // End Action Button //
            $row[] = $btn_confirm.$btn_edit.$btn_view.$btn_delete;   
            $data[] = $row;
            $i++;
        }
        
        

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $recordsTotal,
                        "recordsFiltered" => $recordsFiltered,
                        "data" => $data,
                );
        
    
                
        //output to json format
        echo json_encode($output);
    }


  public function add($pid="")
  {  
        $users_data = $this->session->userdata('auth_users');
        unauthorise_permission(93,588);
        $this->load->model('general/general_model'); 
        $post = $this->input->post();
        //print_r($post); exit;
        $patient_id = "";
        $patient_code = "";
        $simulation_id = "";
        $patient_name = "";
        $mobile_no = "";
        $gender = "1";
        $age_y = "";
        $age_m = "";
        $age_d = "";
        $address = "";
        $city_id = "";
        $state_id = "";
        $country_id = "99"; 
        $patient_email ="";
        $patient_bp ="";
        $patient_temp ="";
        $patient_weight ="";
        $patient_height ="";
        $patient_sop ="";
        $patient_rbs ="";
        $appointment_code="";
        $appointment_date="";
        $appointment_time ="";
        $ref_by_other ="";
        $payment_mode="";
        $diseases="";
        $branch_id =$users_data['parent_id'];
        $type=0;
        $package_id="";
        $diseases="";
        $specialization_id="";
        $source_from="";
        $referred_by='';
        $relation_name="";
        $relation_type="";
        $relation_simulation_id="";
        $address2='';
        $address3='';
        $adhar_no='';

        //insurance
        $insurance_type='';
        $insurance_type_id='';
        $ins_company_id='';
        $polocy_no='';
        $tpa_id='';
        $ins_amount='';
        $ins_authorization_no='';
        $opd_type="0";
        $pannel_type="0";
        $adhar_no ="";
      

        if($pid>0)
        {
           $this->load->model('patient/patient_model');
           $patient_data = $this->patient_model->get_by_id($pid);
           if(!empty($patient_data))
           {
              $patient_id = $patient_data['id'];
              $patient_code = $patient_data['patient_code'];
              $simulation_id = $patient_data['simulation_id'];
              $patient_name = $patient_data['patient_name'];
              $mobile_no = $patient_data['mobile_no'];
              $gender = $patient_data['gender'];
              /*$age_y = $patient_data['age_y'];
              $age_m = $patient_data['age_m'];
              $age_d = $patient_data['age_d'];*/
              
              /* present age*/
              $dob ='';
              if($patient_data['dob']!='0000-00-00' && $patient_data['dob']!='1970-01-01')
              {
                  $dob = date('d-m-Y',strtotime($patient_data['dob']));
              }
              $present_age = get_patient_present_age($dob,$patient_data);
              $age_y = $present_age['age_y'];
              $age_m = $present_age['age_m'];
              $age_d = $present_age['age_d'];
              if(!empty($present_age['age_h']))
              {
                  $age_h = $present_age['age_h'];
              }
              else
              {
                  $age_h ='0';
              }
              /* present age*/
              
              $address = $patient_data['address'];
              $address2 = $patient_data['address2'];
              $address3 = $patient_data['address3'];
              $city_id = $patient_data['city_id'];
              $state_id = $patient_data['state_id'];
              $country_id = $patient_data['country_id'];
              $patient_email = $patient_data['patient_email'];
              $relation_type = $patient_data['relation_type']; 
              $relation_name = $patient_data['relation_name'];
              $relation_simulation_id = $patient_data['relation_simulation_id'];
              $adhar_no= $patient_data['adhar_no']; 
           }
        }

      
        $data['country_list'] = $this->general_model->country_list();
        if(!empty($post['branch_id']))
        {
          $data['simulation_list'] = $this->general_model->simulation_list($post['branch_id']);
        }
        else
        {
          $data['simulation_list'] = $this->general_model->simulation_list();  
        }

        if(!empty($post['branch_id']))
        {
          $data['specialization_list'] = $this->general_model->specialization_list($post['branch_id']);
        }
        else
        {
          $data['specialization_list'] = $this->general_model->specialization_list();
            
        }

        if(!empty($post['branch_id']))
        {
          $data['package_list'] = $this->general_model->package_list($post['branch_id']);
        }
        else
        {
          $data['package_list'] = $this->general_model->package_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['referal_doctor_list'] = $this->appointment->referal_doctor_list($post['branch_id']);
        }
        else
        {
          $data['referal_doctor_list'] = $this->appointment->referal_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['referal_hospital_list'] = $this->appointment->referal_hospital_list($post['branch_id']);
        }
        else
        {
          $data['referal_hospital_list'] = $this->appointment->referal_hospital_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['attended_doctor_list'] = $this->appointment->attended_doctor_list($post['branch_id'],$post['specialization']);
        }
        else
        {
          $data['attended_doctor_list'] = $this->appointment->attended_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['attended_doctor_list'] = $this->appointment->attended_doctor_list($post['branch_id']);
        }
        else
        {
          $data['attended_doctor_list'] = $this->appointment->attended_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['employee_list'] = $this->appointment->employee_list($post['branch_id']);
        }
        else
        {
          $data['employee_list'] = $this->appointment->employee_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['profile_list'] = $this->appointment->profile_list($post['branch_id']);
        }
        else
        {
          $data['profile_list'] = $this->appointment->profile_list();
        }
        if(!empty($post['branch_id']))
        {
          $data['source_list'] = $this->appointment->source_list($post['branch_id']);

        }
        else
        {
          $data['source_list'] = $this->appointment->source_list();

        }

        if(!empty($post['branch_id']))
        {
           $data['diseases_list'] = $this->appointment->diseases_list($post['branch_id']);
        }
        else
        {
           $data['diseases_list'] = $this->appointment->diseases_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['insurance_type_list'] = $this->general_model->insurance_type_list($post['branch_id']);
        }
        else
        {
          $data['insurance_type_list'] = $this->general_model->insurance_type_list();  

        }
        if(!empty($post['branch_id']))
        {
          $data['insurance_company_list'] = $this->general_model->insurance_company_list($post['branch_id']);
        }
        else
        {
          $data['insurance_company_list'] = $this->general_model->insurance_company_list(); 
        }

        $attended_doctor ='';
        if(!empty($post['attended_doctor']))
        {
          $attended_doctor =$post['attended_doctor'];
        }
        $available_time ='';
        if(!empty($post['available_time']))
        {
          $available_time =$post['available_time'];
        }
        $doctor_slot ='';
        if(!empty($post['doctor_slot']))
        {
          $doctor_slot =$post['doctor_slot'];
        }
        $appointment_date='';
        if(!empty($post['appointment_date']))
        {
          $appointment_date =$post['appointment_date'];
        }


        
        $data['doctor_available_time'] = $this->general_model->doctor_time($attended_doctor);
        $data['doctor_available_slot'] = $this->get_doctor_slot($attended_doctor,$available_time,$doctor_slot,$appointment_date);
        
        
        $this->load->model('opd/opd_model','opd'); //default doctor
        $doct_set=$this->opd->default_doctor_setting();
            $def_specl_id='';
            $def_doct_id='';
            if(!empty($doct_set))
            {
              $specialization_id=$doct_set['specialize_id'];
              $def_doct_id=$doct_set['doctor_id'];
            }
        
        $data['page_title'] = "Appointment Booking";  
       
        
        
        $post = $this->input->post();
        
        if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }
        
        $appointment_code = generate_unique_id(20);
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'patient_code'=>$patient_code,
                                  'appointment_code'=>$appointment_code,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'patient_email'=>$patient_email,
                                  'address'=>$address,
                                  'address_second'=>$address2,
                                  'address_third'=>$address3,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'relation_type'=>$relation_type,
                                  'relation_name'=>$relation_name,
                                  'relation_simulation_id'=>$relation_simulation_id,
                                  'specialization'=>'',
                                  'referral_doctor'=>"",
                                  'referral_hospital'=>"",
                                  'attended_doctor'=>$def_doct_id,
                                  'sample_collected_by'=>"",
                                  'staff_refrenace_id'=>"",
                                  'patient_bp' =>"",
                                  'patient_temp' =>"",
                                  'patient_weight' =>"",
                                  'patient_height' =>"",
                                  'patient_sop' =>"",
                                  'patient_rbs' =>"",
                                  'appointment_date'=>date('d-m-Y'),
                                  'appointment_time'=>date('H:i:s'),
                                  'ref_by_other'=>$ref_by_other,
                                  'payment_mode'=>"cash",
                                  'branch_id'=>$branch_id,
                                  'package_id'=>$package_id,
                                  'diseases'=>$diseases,
                                  'specialization_id'=>$specialization_id,
                                  "country_code"=>"+91",
                                  'source_from'=>'',
                                  'remarks'=>'',
                                  'referred_by'=>'',
                                  'time_value'=>'',
                                  "insurance_type"=>$insurance_type,
                                  "insurance_type_id"=>$insurance_type_id,
                                  "ins_company_id"=>$ins_company_id,
                                  "polocy_no"=>$polocy_no,
                                  "tpa_id"=>$tpa_id,
                                  "ins_amount"=>$ins_amount,
                                  "ins_authorization_no"=>$ins_authorization_no,
                                  'opd_type'=>$opd_type,
                                  'pannel_type'=>$pannel_type,
                                  'adhar_no'=>$adhar_no,
                                  );    

        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validateform();
            if($this->form_validation->run() == TRUE)
            {
              $booking_id = $this->appointment->save();
             // echo $booking_id;die;
              //send sms
              if(!empty($booking_id))
              {
                $get_by_id_data = $this->appointment->get_by_id($booking_id);
              //  echo $get_by_id_data['branch_id'];die
                //check sms permission
               // print_r($get_by_id_data); exit;
                  $doctor_name = $get_by_id_data['doctor_name'];
                  $appointment_code = $get_by_id_data['appointment_code'];
                  $appointment_date = $get_by_id_data['appointment_date'];
                  $appointment_time = $get_by_id_data['appointment_time'];
                
                  $datetime = date('d-m-Y',strtotime($appointment_date)).' '.$appointment_time;
                  $patient_name = $get_by_id_data['patient_name'];
                  $mobile_no = $get_by_id_data['mobile_no'];
                  $patient_email = $get_by_id_data['patient_email'];
                if(in_array('640',$users_data['permission']['action']))
                {
                  if(!empty($mobile_no))
                  {
                    send_sms('opd_appointment',1,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{Datetime}'=>$datetime,'{Doctor}'=>'Dr '.$doctor_name,'{AppointmentNo}'=>$appointment_code)); 
                  }
                }
                //check email permission
                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($patient_email))
                  { 
                    $this->load->library('general_functions'); 
                    $this->general_functions->email($patient_email,'','','','','1','opd_appointment','1',array('{Name}'=>$patient_name,'{Datetime}'=>$datetime,'{Doctor}'=>'Dr '.$doctor_name,'{AppointmentNo}'=>$appointment_code)); 
                  }
                } 
              }

              $this->session->set_flashdata('success','Appointment saved successfully.');
              redirect(base_url('appointment'));
            }
            else
            {
               $data['form_error'] = validation_errors();  
            }     
        }

        

       $this->load->view('appointment/add',$data);       
    }

    public function edit($id="")
    { 
       unauthorise_permission(93,589);
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model'); 
        $post = $this->input->post();
        $result = $this->appointment->get_by_id($id); 
        //echo "<pre>"; print_r($result); exit; appoi
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->appointment->referal_doctor_list();
        $data['attended_doctor_list'] = $this->appointment->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();  
        $data['package_list'] = $this->general_model->package_list();
        $data['diseases_list'] = $this->appointment->diseases_list();
        $data['source_list'] = $this->appointment->source_list();
        $data['referal_hospital_list'] = $this->appointment->referal_hospital_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();  
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();

        $data['doctor_available_time'] = $this->general_model->doctor_time($result['attended_doctor']);
        $data['doctor_available_slot'] = $this->get_doctor_slot($result['attended_doctor'],$result['available_time'],$result['doctor_slot'],$result['appointment_date'],$result['id']);

        $data['page_title'] = "Update Appointment";  
        $post = $this->input->post();
        $data['form_error'] = ''; 

        $appointment_date='';
        if($result['appointment_date']!='0000-00-00' && $result['appointment_date']!='1970-01-01')
        {
            $appointment_date = date('d-m-Y',strtotime($result['appointment_date']));
        }
        $adhar_no='';
        if(!empty($result['adhar_no']))
        {
            $adhar_no = $result['adhar_no'];
        }
        
        /* present age*/
        $dob ='';
        if($result['dob']!='0000-00-00' && $result['dob']!='1970-01-01')
        {
             $dob = date('d-m-Y',strtotime($result['dob']));
        }
        //print_r($result);
        $present_age = get_patient_present_age($dob,$result);
        $age_y = $present_age['age_y'];
        $age_m = $present_age['age_m'];
        $age_d = $present_age['age_d'];
        //$age_h = $present_age['age_h'];
        /* present age*/
        
        $data['form_data'] = array(
                                    'data_id'=>$result['id'],
                                    'branch_id'=>$result['branch_id'], 
                                    'patient_id'=>$result['patient_id'], 
                                    'patient_code'=>$result['patient_code'],
                                    'appointment_code'=>$result['appointment_code'],
                                    'simulation_id'=>$result['simulation_id'],
                                    'patient_name'=>$result['patient_name'],
                                    
                                    'mobile_no'=>$result['mobile_no'],
                                    'adhar_no'=>$adhar_no,
                                    'gender'=>$result['gender'],
                                    'age_y'=>$age_y,
                                    'patient_email'=>$result['patient_email'],
                                    'age_m'=>$age_m,
                                    'age_d'=>$age_d,
                                    'pay_now'=>$result['pay_now'],
                                    'address'=>$result['address'],
                                    'city_id'=>$result['city_id'],
                                    'state_id'=>$result['state_id'],
                                    'country_id'=>$result['country_id'],
                                    'package_id'=>$result['package_id'],
                                    'referral_doctor'=>$result['referral_doctor'],
                                    'referral_hospital'=>$result['referral_hospital'],
                                    'specialization'=>$result['specialization_id'],
                                    'attended_doctor'=>$result['attended_doctor'],
                                    'appointment_date'=>$appointment_date,
                                    'appointment_time'=>$result['appointment_time'],
                                    'ref_by_other'=>$result['ref_by_other'],
                                    'diseases'=>$result['diseases'],
                                    "country_code"=>"+91",
                                    'source_from'=>$result['source_from'],
                                    'remarks'=>$result['remarks'],
                                    'referred_by'=>$result['referred_by'],
                                    'address_second'=>$result['address2'],
                                    'address_third'=>$result['address3'],
                                    'available_time'=>$result['available_time'],
                                    'doctor_slot'=>$result['doctor_slot'],
                                    'time_value'=>$result['time_value'],
                                    "insurance_type"=>$result['insurance_type'],
                                    "insurance_type_id"=>$result['insurance_type_id'],
                                    "ins_company_id"=>$result['ins_company_id'],
                                    "polocy_no"=>$result['polocy_no'],
                                    "tpa_id"=>$result['tpa_id'],
                                    "ins_amount"=>$result['ins_amount'],
                                    
                                    'relation_type'=>$result['relation_type'],
                                    'relation_name'=>$result['relation_name'],
                                    'relation_simulation_id'=>$result['relation_simulation_id'],

                                    "ins_authorization_no"=>$result['ins_authorization_no'],
                                    'opd_type'=>$result['opd_type'],
                                    'pannel_type'=>$result['pannel_type']

                                    
                                  );  
        
        if(isset($post) && !empty($post))
        {   

            $data['form_data'] = $this->_validateform();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->appointment->save();
                
              /*if(!empty($booking_id))
              {
                  $get_data = get_sms_setting('opd_appointment',2);
                  if($get_data==1)
                  {
                      $sms_format = $this->appointment->sms_template_format(array('form_name'=>1));
                      $get_by_id_data = $this->appointment->get_by_id($booking_id);
                      $patient_name = $get_by_id_data['patient_name'];
                      $mobile_no = $get_by_id_data['mobile_no'];
                      $sms_url = $this->appointment->sms_url();
                      $url = $sms_url->url;
                      $url = str_replace("{mobile_no}",$mobile_no,$url);
                      $url = str_replace("{message}",$sms_format->template,$url);
                      if(!empty($mobile_no) && isset($mobile_no))
                      {
                       
                        send_sms($url);  
                      }
                  }
              }*/  

                $this->session->set_flashdata('success','Appointment Updated successfully.');
                redirect(base_url('appointment'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('appointment/add',$data);       
      }
    }

	
    public function delete_appointment($id="")
    {
        unauthorise_permission(93,590);
       if(!empty($id) && $id>0)
       {
           $result = $this->appointment->delete_appointment($id);
           $response = "Appointment successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(93,590);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->appointment->deleteall($post['row_id']);
            $response = "Appointment successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(85,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->appointment->get_by_id($id); 

        $data['page_title'] = $data['form_data']['patient_name']." Appointment detail";
        $this->load->view('appointment/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(93,591);
        $data['page_title'] = 'Appointment Archive List';
        $this->load->helper('url');
        $this->load->view('appointment/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(93,591);
        $this->load->model('appointment/appointment_archive_model','appointment_archive'); 
		    $users_data = $this->session->userdata('auth_users');
        $list = $this->appointment_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $opd) { 
            $no++;
            $row = array();
            
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            } 

                      

            $attended_doctor_name ="";
            $attended_doctor_name = get_doctor_name($opd->attended_doctor);

            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$opd->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$opd->id.'">'; 
             }else{
               $row[]='';
             }
            $row[] = $opd->appointment_code;
            $row[] = $opd->patient_name;
            $row[] = $opd->mobile_no;
            $row[] = date('d M Y',strtotime($opd->appointment_date)). ' '.$opd->appointment_time;
            $row[] = $opd->doctor_name;
            //$row[] = date('d-M-Y H:i A',strtotime($opd->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
            if(in_array('527',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_appointment('.$opd->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            }
            if(in_array('528',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$opd->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->appointment_archive->count_all(),
                        "recordsFiltered" => $this->appointment_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore_appointment($id="")
    {
        unauthorise_permission(93,592);
        $this->load->model('appointment/appointment_archive_model','appointment_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->appointment_archive->restore($id);
           $response = "Appointment successfully restore in list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(93,592);
        $this->load->model('appointment/appointment_archive_model','appointment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->appointment_archive->restoreall($post['row_id']);
            $response = "Appointment successfully restore in list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(93,593);
        $this->load->model('appointment/appointment_archive_model','appointment_archive');
        if(!empty($id) && $id>0)
        {
           $result = $this->appointment_archive->trash($id);
           $response = "Appointment parmanently deleted.";
           echo $response;
        }
    }

    function trashall()
    {
        unauthorise_permission(93,593);
        $this->load->model('appointment/appointment_archive_model','appointment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->appointment_archive->trashall($post['row_id']);
            $response = "OPD successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function opd_dropdown()
  {
      $opd_list = $this->opd->employee_type_list();
      $dropdown = '<option value="">Select OPD</option>'; 
      if(!empty($opd_list))
      {
        foreach($opd_list as $opd)
        {
           $dropdown .= '<option value="'.$opd->id.'">'.$opd->doctor_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  

 public function get_allsub_branch_list(){
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
		$branch_name = get_branch_name($parent_branch_details[0]);
        $dropdown="";
		$dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id"><option value="">Select Branch</option><option  selected="selected" value='.$users_data['parent_id'].'>Self</option>';
          if(!empty($branch_name))
          {
              $dropdown.='</option><option value="'.$parent_branch_details[0].'">'.$branch_name.'</option>';
           

          }
          $dropdown.='</select>';
             // if(!empty($sub_branch_details)){
             //     $i=0;
             //    foreach($sub_branch_details as $key=>$value){
             //         $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
             //         $i = $i+1;
             //     }
             //   }
             
        
          
            echo $dropdown; 
        
         
       
    }

    public function get_doctor($id="")
    {
        $option="";
        $doctor_list = getDoctor($id);  
       
        if(!empty($doctor_list))
         { 
        $option ='<select name="state" class="form-control"><option value="">Select Doctor</option>';
        if(!empty($doctor_list))
         {
          foreach($doctor_list as $doctorlist)
           {
           $option .= '<option value="'.$doctorlist->id.'">'.$doctorlist->doctor_name.'</option>';
           }
            $option .= '</select>';
         }
        }
        echo $option;
    }
    
    


    
    private function _validateform()
    {
        $post = $this->input->post(); 
        $field_list = mandatory_section_field_list(3);
        $users_data = $this->session->userdata('auth_users');  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'Simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'Patient Name', 'trim|required'); 
        //$this->form_validation->set_rules('attended_doctor', 'Consultant', 'trim|required'); 
        //$this->form_validation->set_rules('specialization', 'Specialization', 'trim|required'); 
        $this->form_validation->set_rules('adhar_no', 'adhar no', 'min_length[12]|max_length[16]'); 
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
        
        if(!empty($result['opd_type']) && isset($result['opd_type']))
        {
        $opd_type=$result['opd_type'];
        }

        if(!empty($result['pannel_type']) && isset($result['pannel_type']))
        {
        $pannel_type=$result['pannel_type'];
        }

        if(!empty($field_list)){
            
            if($field_list[0]['mandatory_field_id']=='25' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]');
            }
           
            if($field_list[1]['mandatory_field_id']=='26' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                $this->form_validation->set_rules('age_y', 'age', 'trim|required');
            }
        
            if($field_list[2]['mandatory_field_id']=='41' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('specialization', 'Specialization', 'trim|required'); 
            }
            if($field_list[3]['mandatory_field_id']=='42' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('attended_doctor', 'Consultant', 'trim|required'); 
            }
        }
         
          
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
           if(!empty($post['pay_now'])){
              $pay_now = 1;
            }
            else
            {
              $pay_now = 0;
            }
            $doctor_slot='';
            if(!empty($post['doctor_slot']))
            {
              $doctor_slot = $post['doctor_slot'];
            }
            $insurance_type='';
            if(isset($post['insurance_type']))
            {
              $insurance_type = $post['insurance_type'];
            }
            $insurance_type_id='';
            if(isset($post['insurance_type_id']))
            {
              $insurance_type_id = $post['insurance_type_id'];
            }
            $ins_company_id='';
            if(isset($post['ins_company_id']))
            {
              $ins_company_id = $post['ins_company_id'];
            }
            $opd_type = '';
            if(!empty($post['opd_type']))
            {
              $opd_type = $post['opd_type'];
            }
            $pannel_type = '';
            if(!empty($post['pannel_type']))
            {
              $pannel_type = $post['pannel_type'];
            }
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'appointment_code'=>$post['appointment_code'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        
                                        'mobile_no'=>$post['mobile_no'],
                                        'gender'=>$post['gender'],
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        'address'=>$post['address'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                       
                                        'appointment_date'=>$post['appointment_date'],
                                        'appointment_time'=>$post['appointment_time'],
                                        'patient_email'=>$post['patient_email'],
                                        'referral_hospital'=>$post['referral_hospital'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'referred_by'=>$post['referred_by'],
                                        'appointment_time'=>$post['appointment_time'],
                                        'specialization'=>$post['specialization'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'diseases'=>$post['diseases'],
                                        "country_code"=>"+91",
                                        'source_from'=>$post['source_from'],
                                        'branch_id'=>$post['branch_id'],
                                        'relation_type'=>$post['relation_type'],
                                        'relation_name'=>$post['relation_name'],
                                        'relation_simulation_id'=>$post['relation_simulation_id'],
                                        'remarks'=>$post['remarks'],
                                        'adhar_no'=>$post['adhar_no'],
                                        'address_second'=>$post['address_second'],
                                        'address_third'=>$post['address_third'],
                                        'available_time'=>$post['available_time'],
                                        'doctor_slot'=>$doctor_slot,
                                        'opd_type'=>$opd_type,
                                        'pannel_type'=>$pannel_type,
                                        "insurance_type"=>$insurance_type,
                                        "insurance_type_id"=>$insurance_type_id,
                                        "ins_company_id"=>$ins_company_id,
                                        "polocy_no"=>$post['polocy_no'],
                                        "tpa_id"=>$post['tpa_id'],
                                        "ins_amount"=>$post['ins_amount'],
                                        "ins_authorization_no"=>$post['ins_authorization_no'],
                                        'pannel_type'=>$pannel_type,
                                        'opd_type'=>$opd_type,


                                       ); 
            return $data['form_data'];
        }   
    }


    public function doctor_rate()
    {
       $post = $this->input->post();
       //print_r($post); exit;
       if(isset($post) && !empty($post) && !empty($post['doctor_id']))
       {
         $doctor_rate="";
         $total_amount = 0;
         $doctor_id = $post['doctor_id'];
         $this->load->model('general/general_model');
         $doctor_data = $this->general_model->doctors_list($doctor_id);
         $consultant_charge = $doctor_data[0]->consultant_charge; 
        // $pay_arr = array('total_amount'=>$consultant_charge); //number_format
         $netamount = $consultant_charge-$post['discount'];
         $discount = $post['discount'];
         //$balance = number_format($consultant_charge-$post['discount'],2,'.', ''); 
         

         $pay_arr = array('total_amount'=>number_format($consultant_charge,2,'.', ''),'discount'=>number_format($discount,2,'.', ''),'net_amount'=>number_format($netamount,2,'.', ''),'paid_amount'=>number_format($consultant_charge-$post['discount'],2,'.', ''));	 	

         $json = json_encode($pay_arr,true);
         echo $json;
         
       }
    }

    public function package_rate()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post) && !empty($post['package_id']))
       {
         $package_rate="";
         $total_amount = 0;
         $package_id = $post['package_id'];
         $this->load->model('general/general_model');
         $package_data = $this->general_model->package_list($package_id);
         $amount = $package_data[0]->amount;
         $discount = $post['discount'];
         $paid_amount = $post['paid_amount'];
         //echo $amount+$post['total_amount']-$post['discount']; die; 
         //$amount_arr = array('total_amount'=>number_format($amount+$post['total_amount']-$post['discount'],2,'.', '')); //number_format
         $balance = number_format($amount+$post['total_amount']-$post['discount']-$post['paid_amount'],2,'.', ''); 
         $amount_arr = array('total_amount'=>number_format($amount+$post['total_amount'],2,'.', ''),'discount'=>number_format($post['discount'],2,'.', ''),'net_amount'=>number_format($amount+$post['total_amount']-$post['discount'],2,'.', ''),'paid_amount'=>number_format($amount+$post['total_amount']-$post['discount'],2,'.', ''),'balance'=>$balance);

         $json = json_encode($amount_arr,true);
         echo $json;
         
       }
    }

    


    public function calculate_payment()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
         
           $total_amount = $post['total_amount'];
           $discount = $post['discount'];
           $net_amount = $total_amount-$discount;
           $balance = $post['balance'];
           $paid_amount = $post['paid_amount'];
           $paid_amount = $net_amount;
           $pay_arr = array('total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''), 'balance'=>number_format($balance,2,'.', ''), 'paid_amount'=>number_format($paid_amount,2,'.', ''));
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
    }

    public function get_doctor_available_days()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
           $days_data = $this->appointment->get_doctor_available_days($post['doctor_id'],$post['booking_date']);
           $json = json_encode($days_data,true);
           echo $json;
        }
    }

    public function confirm_booking($id)
    {
       unauthorise_permission(93,596);
      $this->load->helper('url');
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->appointment->get_by_id($id);
        //echo "<pre>";print_r($result); exit;
        $patient_id = $result['patient_id'];
        $total_amount = $result['total_amount'];
        $net_amount = $result['net_amount'];
        $paid_amount = $result['paid_amount'];
        $discount = $result['discount'];
        $balance = $result['balance'];
        $payment_mode = $result['payment_mode'];
        //$branch_name = $result['branch_name'];
        $consultant = $result['attended_doctor']; 
        $specialization_id= $result['specialization_id'];
        
        $ins_company_id = $result['ins_company_id'];
    $panel_type = $result['pannel_type'];
    $opd_type = $result['opd_type'];
        $rate_data = $this->appointment->opd_doctor_rate($result['attended_doctor'],$ins_company_id,$panel_type,$opd_type);
        
        if(!empty($result['attended_doctor']))
        {
          $this->load->model('doctors/doctors_model');
          $doctor_data = $this->doctors_model->get_by_id($result['attended_doctor']);
          
          $data['schedule_type'] = $doctor_data['schedule_type'];
        }

        if(!empty($total_amount) && $total_amount!='0.00'){ $total_amount = $total_amount; }else { $total_amount = $rate_data; }
        if(!empty($net_amount) && $total_amount!='0.00'){ $net_amount = $net_amount; }else{ $net_amount = $rate_data; }
        if(!empty($paid_amount) && $total_amount!='0.00' && $paid_amount!='0.00'){  $paid_amount = $paid_amount;}else{ $paid_amount = $rate_data;}
        if(!empty($discount) ){ $discount = $discount; }else{ $discount = '0.00'; }
        $balance = $net_amount-$paid_amount;
        
        $data['page_title'] = "Confirm Booking";  
        $post = $this->input->post();
        $booking_code = generate_unique_id(9);
        $this->load->model('general/general_model');
        $data['form_error'] = ''; 
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'booking_code'=>$booking_code,
                                  'appointment_id'=>$id, 
                                  'booking_date'=>date('d-m-Y',strtotime($result['appointment_date'])),
                                  'booking_time'=>$result['appointment_time'],
                                  'total_amount'=>$total_amount, 
                                  'discount'=>$discount,
                                  'net_amount'=>$net_amount,
                                  'paid_amount'=>$paid_amount,
                                  'balance'=>$balance,
                                  'booking_status'=>1,
                                  'field_name'=>'',
                                  //'branch_name'=>"",
                                  //'cheque_no'=>"",
                                  //'cheque_date'=>'',
                                  //'transaction_no'=>"",
                                  'payment_mode'=>"",
                                  'patient_id'=>$patient_id,
                                  'consultant'=>$consultant,
                                  'attended_doctor' =>$consultant,   
                                  'specialization_id'=>$specialization_id,
                                  );  
        
        if(!empty($post))
        {   
            
            $data['form_data'] = $this->_validate_booking_confirm();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->appointment->confirm_booking();
                
                $this->session->set_userdata('opd_booking_id',$booking_id);
                //$this->session->set_flashdata('success','Opd booking successfully booked.');

                echo 1;
                return false;
                
            }
            else
            {
                  $total_values=array(); 
          if(isset($post['field_name']))
                  {
                  $count_field_names= count($post['field_name']);  
          $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);
         for($i=0;$i<$count_field_names;$i++) 
         {
                  $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

                 }
                 } 
                $booking_code = generate_unique_id(9);
                 $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'booking_code'=>$booking_code,
                                  'appointment_id'=>$id, 
                                  //'booking_date'=>date('d-m-Y',strtotime($result['booking_date'])),
                                  //'booking_time'=>$result['booking_time'],
                                  'total_amount'=>$total_amount, 
                                  'discount'=>$discount,
                                  'net_amount'=>$net_amount,
                                  'paid_amount'=>$paid_amount,
                                  'balance'=>$balance,
                                  'booking_status'=>1,
                                  'field_name'=>$total_values,
                                  //'branch_name'=>$post['branch_name'],
                                 // 'cheque_no'=>$post['cheque_no'],
                                  //'cheque_date'=>$cheque_date,
                                  //'transaction_no'=>$post['transaction_no'],
                                  'consultant'=>$consultant,
                                  'payment_mode'=>$post['payment_mode'],
                                  'patient_id'=>$patient_id
                                  );  
                $data['form_error'] = validation_errors(); 


            }     
        }
       $this->load->view('appointment/confirm',$data);       
      }
    }


    private function _validate()
    {
        $field_list = mandatory_section_field_list(4);
         $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
       	$this->form_validation->set_rules('attended_doctor','consultant','trim|required');
         if(!empty($field_list)){
            
            if($field_list[0]['mandatory_field_id']=='27' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                 $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }
           
            if($field_list[1]['mandatory_field_id']=='28' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
            }
        }
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $appointment_code = generate_unique_id(20); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'appointment_code'=>$post['appointment_code'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        'relation_type'=>$post['relation_type'],
                                        'relation_name'=>$post['relation_name'],
                                        'gender'=>$post['gender'],
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        'address'=>$post['address'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'diseases'=>$post['diseases'],
                                        'relation_type'=>$post['relation_type'],
                                        'relation_name'=>$post['relation_name'],
                                        'relation_simulation_id'=>$post['relation_simulation_id'],

                                        'specialization_id'=>$post['specialization_id'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'referral_hospital'=>$post['referral_hospital'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'appointment_date'=>$post['appointment_date'],
                                        'appointment_time'=>$post['appointment_time'],

                                        'patient_email'=>$post['patient_email'],
                                       ); 
            return $data['form_data'];
        }   
    }


    private function _validatebilling()
    {
        $field_list = mandatory_section_field_list(4);
         $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
       	
         if(!empty($field_list)){
            
            if($field_list[0]['mandatory_field_id']=='27' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                 $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }
           
            if($field_list[1]['mandatory_field_id']=='28' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
            }
        }
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
       
        $opd_particular_billing_list = $this->session->userdata('opd_particular_billing');
        if(!isset($opd_particular_billing_list) && empty($opd_particular_billing_list))
        {
          $this->form_validation->set_rules('particular_id', 'particular_id', 'trim|callback_check_particular_id');
        }   
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2);
            $balance_amount ="";
            if(!empty($post['patient_id']))
            {
              $balance_amount = $this->opd->check_patient_balance($post['patient_id']);    
            } 
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'booking_code'=>$post['booking_code'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        'gender'=>$post['gender'],
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        'address'=>$post['address'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'patient_email'=>$post['patient_email'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'booking_date'=>$post['booking_date'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$balance_amount,
                                        'relation_type'=>$post['relation_type'],
                                        'relation_name'=>$post['relation_name'],
                                        'relation_simulation_id'=>$post['relation_simulation_id'],
                                        'type'=>$post['type'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'referral_hospital'=>$post['referral_hospital'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                       ); 
            return $data['form_data'];
        }   
    }


    

    ///////
    public function check_particular_id()
    {
       $opd_particular_billing_list = $this->session->userdata('opd_particular_billing');
       if(isset($opd_particular_billing_list) && !empty($opd_particular_billing_list))
       {
          return true;
       }
       else
       {
          $this->form_validation->set_message('check_particular', 'Please select a particular.');
          return false;
       }
    }
    

    private function _validate_booking_confirm()
    {
        $post = $this->input->post();  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('total_amount', 'total amount', 'trim|required'); 
        //$this->form_validation->set_rules('booking_date', 'Booking Date', 'trim|required'); 
        $this->form_validation->set_rules('net_amount', 'Net Amount', 'trim|required'); 
        $this->form_validation->set_rules('paid_amount', 'Paid Amount', 'trim|required');
         if(isset($post['field_name']))
         {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required');
         }
        
       
        $total_values=array(); 

        if(isset($post['field_name']))
        {
        $count_field_names= count($post['field_name']);  

        $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) 
        {
        $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        }
        }   
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        //'booking_date'=>$post['booking_date'],
                                        //'booking_time'=>$post['booking_time'],
                                        'total_amount'=>$post['total_amount'], 
                                        'net_amount'=>$post['net_amount'],
                                        'discount'=>$post['discount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'field_name'=>$total_values,
                                         'consultant'=>$post['consultant'],
                                        'balance'=>$post['balance']
                                        
                                       ); 
            return $data['form_data'];
        }  

    }

  //callback_float_validation
  public function float_validation($str)
	{
		 if($str!='0.00'  || $str!='0' )
		 {
		 	return true;
		 }
		 else
		 {
		 	$this->form_validation->set_message('float_validation', 'Please add doctor rate first.');
		 	return false;
			
		}	
	}

	//opd Prescription 


    
    function get_template_data($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->opd->template_data($template_id);
        echo $templatedata;
      }
    }



    function get_template_test_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->opd->get_template_test_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_template_prescription_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->opd->get_template_prescription_data($template_id);
        
        echo $templatetestdata;
      }
    }
    

   function complaints_name($complaints_id="")
   {
      if($complaints_id>0)
      {
         $complaintsname = $this->opd->compalaints_list($complaints_id);
         echo $complaintsname;
      }
    }

   

    function examination_name($examination_id="")
    {
      if($examination_id>0)
      {
         $examination_name = $this->opd->examination_list($examination_id);
         echo $examination_name;
      }
    }

    function diagnosis_name($diagnosis_id="")
    {
      if($diagnosis_id>0)
      {
         $diagnosis_name = $this->opd->diagnosis_name($diagnosis_id);
         echo $diagnosis_name;
      }
    }
    //hms_opd_suggetion
    function suggetion_name($suggetion_id="")
    {
      if($suggetion_id>0)
      {
         $suggetion_name = $this->opd->suggetion_name($suggetion_id);
         echo $suggetion_name;
      }
    }

    function personal_history_name($personal_his_id="")
    {
      if($personal_his_id>0)
      {
         $personal_his_name = $this->opd->personal_history_name($personal_his_id);
         echo $personal_his_name;
      }
    }

    function prv_history_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->opd->prv_history_name($pre_id);
         echo $pre_name;
      }
    }


    public function particular_billing_list()
    {
        unauthorise_permission(85,121);
        $data['page_title'] = 'OPD Particular Billing list'; 
        $this->load->view('opd/particular_billing_list',$data);
    }

    



    public function particular_calculation()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
           $charges = $post['charges'];
           $quantity = $post['quantity'];
           $amount = ($charges*$quantity);
           //$amount = number_format($net_amount,2);
           $pay_arr = array('charges'=>$charges, 'amount'=>$amount,'quantity'=>$quantity);
           $json = json_encode($pay_arr,true);
           echo $json;
       }
    }


    public function particular_payment_calculation()
    {
       //print_r($this->session->all_userdata()); exit;
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {   
			$billing_particular = $this->session->userdata('opd_particular_billing'); 
			if(isset($billing_particular) && !empty($billing_particular))
			{
                $billing_particular = $billing_particular; 
			}
			else
			{
				$billing_particular = [];
			}

			$billing_particular[$post['particular']] = array('particular'=>$post['particular'], 'quantity'=>$post['quantity'], 'amount'=>$post['amount'],'particulars'=>$post['particulars']);
			$amount_arr = array_column($billing_particular, 'amount'); 
		    $total_amount = array_sum($amount_arr);
            
            $this->session->set_userdata('opd_particular_billing', $billing_particular);
            $html_data = $this->opd_perticuller_list();
            
            $response_data = array('html_data'=>$html_data, 'total_amount'=>$total_amount, 'net_amount'=>$total_amount-$post['discount'], 'discount'=>$post['discount']);
            
            $opd_particular_payment_array = array('total_amount'=>$total_amount, 'net_amount'=>$total_amount-$post['discount'], 'discount'=>$post['discount']);
            

            $this->session->set_userdata('opd_particular_payment', $opd_particular_payment_array);
            $json = json_encode($response_data,true);
            echo $json;
            
       }
    }

     public function particular_payment_disc()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
           $discount = $post['discount'];
           $total_amount = $post['total_amount'];
           $net_amount = $total_amount-$discount;
           $balance = $post['balance'];
           $paid_amount = $post['paid_amount'];
           $paid_amount = $net_amount;
           $pay_arr = array('total_amount'=>$total_amount, 'net_amount'=>$net_amount, 'discount'=>$discount, 'balance'=>$balance, 'paid_amount'=>$paid_amount);
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
    }

    public function remove_opd_particular()
    {
       $post =  $this->input->post();
       
       if(isset($post['particular_id']) && !empty($post['particular_id']))
       {
           $opd_particular_billing = $this->session->userdata('opd_particular_billing'); 
           $particular_id_list = array_column($opd_particular_billing, 'particular');
           foreach($post['particular_id'] as $post_perticuler)
           {
           	  if(in_array($post_perticuler,$particular_id_list))
           	  { 
           	  	 unset($opd_particular_billing[$post_perticuler]);
           	  }
           }  

			$amount_arr = array_column($opd_particular_billing, 'amount'); 
			$total_amount = array_sum($amount_arr);
			$this->session->set_userdata('opd_particular_billing',$opd_particular_billing);
			$html_data = $this->opd_perticuller_list();
			$response_data = array('html_data'=>$html_data, 'total_amount'=>$total_amount, 'net_amount'=>$total_amount, 'discount'=>0);
			$json = json_encode($response_data,true);
			echo $json;
       }
    }

    private function opd_perticuller_list()
    {
    	$particular_data = $this->session->userdata('opd_particular_billing');
    	 $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              "; 
    	$rows = '<thead class="bg-theme"><tr>  			
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Particular</th>
                    <th>Charges</th>
                     <th>Quantity</th>
                  </tr></thead>';  
           if(isset($particular_data) && !empty($particular_data))
           {
           	  $i = 1;
              foreach($particular_data as $particulardata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="particular_id[]" class="part_checkbox booked_checkbox" value="'.$particulardata['particular'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$particulardata['particulars'].'</td>
                            <td>'.$particulardata['amount'].'</td>
                            <td>'.$particulardata['quantity'].'</td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Particular data not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function print_booking_report($id="",$type='')
    {
      
      $data['page_title'] = "Print Bookings";
      $booking_id= $this->session->userdata('opd_booking_id');
      if(!empty($id))
      {
        $booking_id = $id;
      }
      else if(isset($booking_id) && !empty($booking_id))
      {
        $booking_id =$booking_id;
      }
      else
      {
        $booking_id = '';
      } 
      $get_by_id_data = $this->opd->get_all_detail_print($booking_id);
      $template_format = $this->opd->template_format(array('section_id'=>1,'types'=>1));

      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      $this->load->model('packages/packages_model','package');
      $selected_medicine_result = $this->package->selected_medicine($package_id);
		  $medicine_arr = array();
      if(!empty($selected_medicine_result))
      {             
      	 foreach($selected_medicine_result as $medicine_data)
      	 {  
      	      $medicine_arr[] = $medicine_data->id; 
      	 }
      }
      $data['medicine_ids']=$medicine_arr;
      $data['template_data']=$template_format;
      $data['all_detail']= $get_by_id_data;
      if($type=='1')
      {
        $data['page_type'] = 'Billing';  
      }
      else
      {
        $data['page_type'] = 'Booking';
      }
      $this->load->view('opd/print_template_opd',$data);
    }

    public function print_blank_booking_report($id="",$type='')
    {
      
      $booking_id= $this->session->userdata('opd_booking_id');
      if(!empty($id))
      {
        $booking_id = $id;
      }
      else if(isset($booking_id) && !empty($booking_id))
      {
        $booking_id =$booking_id;
      }
      else
      {
        $booking_id = '';
      }
      if($type=='1')
      {
        $data['page_type'] = 'Billing';  
      }
      else
      {
        $data['page_type'] = 'Booking';
      } 
      $get_by_id_data = $this->opd->get_all_detail_print($booking_id);
      $template_format = $this->opd->template_format(array('section_id'=>1,'types'=>1));
      $data['template_data']=$template_format;
      
      $data['all_detail']= $get_by_id_data;
      //print '<pre>';print_r($get_by_id_data); exit;
      //print '<pre>';print_r($data['all_detail']); exit;
      $this->load->view('opd/print_blank_template_opd',$data);
    }

    


    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->appointment->referal_doctor_list();
        $data['attended_doctor_list'] = $this->appointment->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();  
        $data['source_list'] = $this->appointment->source_list();
       
        $data['form_data'] = array(
                                    // "start_date"=>'',//date('d-m-Y')
                                    // "end_date"=>'',//date('d-m-Y')
                                    // "booking_from_date"=>'',
                                    // "booking_to_date"=>'',
                                    "referred_by"=>"",
                                    "refered_id"=>"",
                                    "appointment_from_date"=>'',
                                    "appointment_to_date"=>'',
                                    "patient_code"=>"", 
                                    "patient_name"=>"",
                                    "simulation_id"=>"",
                                    "mobile_no"=>"",
                                    "gender"=>"",
                                    "age_y"=>"",
                                    "age_m"=>"",
                                    "age_d"=>"",
                                    "dob"=>"",
                                    "address"=>"",
                                    "city_id"=>"",
                                    "state_id"=>"",
                                    "country_id"=>"",
                                    "pincode"=>"",
                                    "amount_from"=>"",
                                    "amount_to"=>"",
                                    "paid_amount_from"=>"",
                                    "paid_amount_to"=>"",
                                    "booking_code"=>"",
                                    "referral_doctor"=>"",
                                    "referral_hospital"=>"",
                                    "specialization_id"=>"",
                                    "attended_doctor"=>"",
                                    "patient_email"=>"",
                                    "start_time"=>"",
                                    "end_time"=>"",
                                    'adhar_no'=>'',
                                    // "booking_status"=>"",
                                    "status"=>"", 
                                    "remark"=>"",
                                    "disease"=>"",
                                    "disease_code"=>"",
                                    "branch_id"=>'',
                                    "appointment_code"=>'',
                                    'source_from'=>'',
                                    'app_status'=>'',
                                    
                                  );
        if(isset($post) && !empty($post))
        {
            
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('appointment_search', $marge_post);
        }
        $opd_search = $this->session->userdata('appointment_search');
        if(isset($opd_search) && !empty($opd_search))
        {
            $data['form_data'] = $opd_search;
        }
        //print_r($data); exit;
        $this->load->view('appointment/advance_search',$data);
    }

    public function get_branch_data($branch_id)
    {
        if($branch_id>0)
        {
          $branchdata = $this->appointment->branch_data($branch_id);
          
          echo $branchdata;
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('appointment_search');
    }

    function get_simulation_data($branch_id="")
    {
      if($branch_id>0)
      {
        $simulationdata = $this->appointment->get_simulation_data($branch_id);
        echo $simulationdata;
      }
    }

    function get_referral_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $doctor_data = $this->appointment->get_referral_doctor_data($branch_id);
        echo $doctor_data;
      }
    }

    function get_referral_hospital_data($branch_id="")
    {
      if($branch_id>0)
      {
        $hospital_data = $this->appointment->get_referral_hospital_data($branch_id);
        echo $hospital_data;
      }
    }

    function get_specialization_data($branch_id="")
    {
      if($branch_id>0)
      {
        $specialization_data = $this->appointment->get_specialization_data($branch_id);
        echo $specialization_data;
      }
    }

    function get_diseases_data($branch_id="")
    {
      if($branch_id>0)
      {
        $specialization_data = $this->appointment->get_diseases_data($branch_id);
        echo $specialization_data;
      }
    }

    function get_source_from_data($branch_id="")
    {
      if($branch_id>0)
      {
        $specialization_data = $this->appointment->get_source_from_data($branch_id);
        echo $specialization_data;
      }
    }

    function get_attended_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $attended_data = $this->appointment->get_attended_doctor_data($branch_id);
        echo $attended_data;
      }
    }


    public function appointment_excel()
    {
        // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Appointment No','Patient Name','Mobile No','Appointment Date & Time','Doctor Name');
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
          $list = $this->appointment->search_opd_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;

               foreach($list as $opds)
               {
                    $attended_doctor_name ="";
                    $attended_doctor_name = get_doctor_name($opds->attended_doctor);
                    $specialization_id = get_specilization_name($opds->specialization_id);
                    $booking_code = $opds->booking_code;
                    $patient_name = $opds->patient_name;
                    $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
                    $appointment_time = date('h:i A',strtotime($opds->appointment_time));
                    $appointmentdate = $appointment_date.' '. $appointment_time;
                    $genders = array('0'=>'Female','1'=>'Male','2'=>'Others');
                    $gender = $genders[$opds->gender];
                    $age_y = $opds->age_y;
                    $age_m = $opds->age_m;
                    $age_d = $opds->age_d;
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
                    $patient_age =  $age;
                    array_push($rowData,$opds->appointment_code,$opds->patient_name,$opds->mobile_no,$appointmentdate,$attended_doctor_name);
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
                    $row_val =1;
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
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=appointment_list_".time().".xls");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
          }

        // $list = $this->appointment->search_opd_data();
        // $columnHeader = '';  
        // $columnHeader = "Appointment No." . "\t" . "Patient Name" . "\t". "Mobile No.". "\t"  . "Appointment Date" ."\t". "Doctor Name";
        // $setData = '';
        // if(!empty($list))
        // {
        //     $rowData = "";
        //     foreach($list as $opds)
        //     {
                
        //     $attended_doctor_name ="";
        //     $attended_doctor_name = get_doctor_name($opds->attended_doctor);
        //     $specialization_id = get_specilization_name($opds->specialization_id);
        //     $booking_code = $opds->booking_code;
        //     $patient_name = $opds->patient_name;
        //     $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
        //     $appointment_time = date('d M Y',strtotime($opds->appointment_time));

        //     $appointmentdate = $appointment_date.' '. $appointment_time;
        //         $genders = array('0'=>'Female','1'=>'Male');
        //         $gender = $genders[$opds->gender];
        //         $age_y = $opds->age_y;
        //         $age_m = $opds->age_m;
        //         $age_d = $opds->age_d;
        //         $age = "";
        //         if($age_y>0)
        //         {
        //         $year = 'Years';
        //         if($age_y==1)
        //         {
        //           $year = 'Year';
        //         }
        //         $age .= $age_y." ".$year;
        //         }
        //         if($age_m>0)
        //         {
        //         $month = 'Months';
        //         if($age_m==1)
        //         {
        //           $month = 'Month';
        //         }
        //         $age .= ", ".$age_m." ".$month;
        //         }
        //         if($age_d>0)
        //         {
        //         $day = 'Days';
        //         if($age_d==1)
        //         {
        //           $day = 'Day';
        //         }
        //         $age .= ", ".$age_d." ".$day;
        //         }
        //         $patient_age =  $age;

        //         $rowData = $opds->appointment_code . "\t" . $opds->patient_name. "\t". $opds->mobile_no ."\t". $appointmentdate ."\t". $attended_doctor_name; 
        //         $setData .= trim($rowData) . "\n";    
        //     }
        // }

      

        // //echo $setData;die;
        // header("Content-type: application/octet-stream");  
        // header("Content-Disposition: attachment; filename=appointment_list_".time().".xls");  
        // header("Pragma: no-cache");  
        // header("Expires: 0");  

        // echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
    }

    public function appointment_csv()
    {
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Appointment No','Patient Name','Mobile No','Appointment Date & Time','Doctor Name');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->appointment->search_opd_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $opds)
               {
                    $attended_doctor_name ="";
                    $attended_doctor_name = get_doctor_name($opds->attended_doctor);
                    $specialization_id = get_specilization_name($opds->specialization_id);
                    $booking_code = $opds->booking_code;
                    $patient_name = $opds->patient_name;
                    $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
                    $appointment_time = date('d M Y',strtotime($opds->appointment_time));
                    $appointmentdate = $appointment_date.' '. $appointment_time;
                    $genders = array('0'=>'Female','1'=>'Male','2'=>'Others');
                    $gender = $genders[$opds->gender];
                    $age_y = $opds->age_y;
                    $age_m = $opds->age_m;
                    $age_d = $opds->age_d;
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
                    $patient_age =  $age;
                    array_push($rowData,$opds->appointment_code,$opds->patient_name,$opds->mobile_no,$appointmentdate,$attended_doctor_name);
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
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
         header("Content-Disposition: attachment; filename=opd_list_".time().".csv");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
          }
       
    }

    public function appointment_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->appointment->search_opd_data();
        $this->load->view('appointment/appointment_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("appointment_list_".time().".pdf");
    }
    public function appointment_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->appointment->search_opd_data();
      $this->load->view('appointment/appointment_html',$data); 
    }

    public function get_payment_mode_data()
    {
      $this->load->model('general/general_model'); 
      $payment_mode_id= $this->input->post('payment_mode_id');
      $error_field= $this->input->post('error_field');

      $get_payment_detail= $this->general_model->payment_mode_detail($payment_mode_id); 
      $html='';
      $var_form_error='';
      foreach($get_payment_detail as $payment_detail)
      {

      if(!empty($error_field))
      {

      $var_form_error= $error_field; 
      }

      $html.='<div class="row m-b-5"><div class="col-md-4"><label>'.$payment_detail->field_name.'<label class="star">*</span></div> <div class="col-md-8"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>';
      }
      echo $html;exit;

    }

    function get_doctor_slot($doctor_id='',$time_id='',$selected='',$appointment_date='',$appointment_id='')
    {
        $post = $this->input->post(); 
        $this->load->model('general/general_model');
        $option =''; 
        if(!empty($doctor_id) && !empty($time_id))
        {
            $booked_slot_list = $this->general_model->get_booked_slot($doctor_id,$time_id,$appointment_date,$appointment_id);
            $booked_slot = array();
            foreach ($booked_slot_list as $booked_list) 
            { 
              $booked_slot[] = $booked_list->doctor_slot;
            }   

           // print_r($booked_slot); exit();
            $time_list = $this->general_model->doctor_slot($doctor_id,$time_id);
            $per_patient_time = $this->general_model->per_patient_time($doctor_id);
            $time1='';
            if(!empty($time_list[0]->time1))
            {
              $time1 = strtotime($time_list[0]->time1);  
            }
            $time2='';
            if(!empty($time_list[0]->time2))
            {
              $time2 = strtotime($time_list[0]->time2);
            }
            
            
            $total_time = $time2-$time1;
            $time_in_minute = $total_time/60;  
            $total_slot ='';
            if(!empty($per_patient_time[0]->per_patient_timing))
            {
              $total_slot = $time_in_minute/$per_patient_time[0]->per_patient_timing;  
            }
            
            $slot_data = '';  
            $option .= "<option value=''>Select Time</option>";
            $start_slot = $time1;
            $end_slot = $start_slot+($per_patient_time[0]->per_patient_timing*60);
            for($i=0;$i<$total_slot;$i++)
            { 
                $slot_data = date('H:i A', $start_slot).' To '.date('H:i A', $end_slot);
                $time_values = date('h:i:s A', $start_slot-60);
                $start_slot = $end_slot+60;
                $end_slot = ($end_slot+($per_patient_time[0]->per_patient_timing*60));
                $chek='';
                
        if($selected==$time_values)
        {
          $chek = 'selected="selected"';
        }
        if(!in_array($slot_data, $booked_slot))
        {
          $option .= "<option ".$chek." value='".$time_values."'>".$slot_data."</option>";
        }
            } 
        }
        return $option;
    }
    
    
    function get_patient_detail_no_mobile($mobile)
   {
    $this->load->model('test/test_model','test');
     $patient_data = $this->test->get_by_mobile_no($mobile);
     //echo "<pre>"; print_r($patient_data); die;
     $html="";
     if(!empty($patient_data))
     { 
      
       foreach($patient_data as $patient_list)
       {
          $html.='<input type="radio" value="'.$patient_list->id.'" class="term" name="patient_id"> '.$patient_list->patient_name.'<br>';
       }
           $data=array('st'=>1,'patient_list'=>$html);
            echo json_encode($data);
            return false;
            
     }
    else{
            $data=array('st'=>0);
            echo json_encode($data);
            return false;
          }
   
 }

 function get_patient_detail_byid($patient_id)
   {
    $this->load->model('test/test_model','test');
     $patient_data = $this->test->get_patient_byid($patient_id);
     //print_r($patient_data);die;
     if(!empty($patient_data))
          {
            $data=array('st'=>1,'patient_detail'=>$patient_data);
            echo json_encode($data);
            return false;
          }
      else
          {
            $data=array('st'=>0);
            echo json_encode($data);
            return false;
          }

   }

    
}
?>