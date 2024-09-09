<?php
class Dental_prescription_model extends CI_Model 
{
	var $table = 'hms_dental_prescription_tab_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_by_id($id)
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_opd_booking.*,hms_opd_booking.policy_no as polocy_no,hms_opd_booking.insurance_type_id as insurance_type, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.adhar_no, hms_patient.patient_email,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id');
    
      $this->db->from('hms_opd_booking'); 
      $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
      $this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 
      $this->db->where('hms_opd_booking.id',$id);
      $this->db->where('hms_opd_booking.is_deleted','0');
      $query = $this->db->get(); 
      return $query->row_array();
  }
  
  public function check_appointment_done($id,$patient_id)
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_opd_booking.id');
    
      $this->db->from('hms_opd_booking'); 
      $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
      $this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 
      $this->db->where('hms_opd_booking.parent_id',$id);
      if(!empty($patient_id))
      {
        $this->db->where('hms_opd_booking.patient_id',$patient_id);  
      }
      $this->db->where('hms_opd_booking.is_deleted','0');
      $query = $this->db->get(); 
      return $query->row_array();
  }

  public function get_permanent()
  {
    $user_data = $this->session->userdata('auth_users');
      $this->db->select('*');
      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->where('status',1); 
      $this->db->where('is_deleted',0);
       $this->db->where('teeth_name',1);
      $this->db->order_by('sort_order','desc');
      $query = $this->db->get('hms_dental_teeth_chart');
    return $query->result();
    
  }

  public function get_decidous()
  {
    $user_data = $this->session->userdata('auth_users');
      $this->db->select('*');
      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->where('status',1); 
      $this->db->where('is_deleted',0);
       $this->db->where('teeth_name',2);
      $this->db->order_by('sort_order','desc');
      $query = $this->db->get('hms_dental_teeth_chart');
    return $query->result();
    
  }

  public function dental_diagnosis_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_dental_diagnosis');
        $result = $query->result(); 
        return $result; 
    }

    public function dental_treatment_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_dental_treatment');
        $result = $query->result(); 
        return $result; 
    }
     public function dental_treatment_type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_dental_treatment_type');
        $result = $query->result(); 
        return $result; 
    }

    public function dental_investigation_list()
    {
      $users_data= $this->session->userdata('auth_users');
      $this->db->select('*');  
      $this->db->where('status','1');
      $this->db->where('is_deleted','0');
      $this->db->where('branch_id',$users_data['parent_id']);
      $this->db->where('investigation_cat','0');   
      $query = $this->db->get('hms_dental_investigation');
      //echo $this->db->last_query();die;
      $result = $query->result(); 
      return $result;
    }

    public function dental_template_list()
    {
      $users_data= $this->session->userdata('auth_users');
      $this->db->select('*');  
      $this->db->where('status','1');
      $this->db->where('branch_id',$users_data['parent_id']);
      $this->db->where('is_deleted','0');   
      $query = $this->db->get('hms_dental_prescription_template');
      //echo $this->db->last_query();die;
      $result = $query->result(); 
      return $result;
    }

     public function get_by_prescription_id($prescription_id,$opd_booking_id)
  {
     $this->db->select("hms_dental_prescription.*,hms_dental_prescription_template.template_name,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.adhar_no"); 
    $this->db->from('hms_dental_prescription'); 
    $this->db->where('hms_dental_prescription.id',$prescription_id);
    $this->db->join('hms_patient','hms_patient.id=hms_dental_prescription.patient_id','left');
    $this->db->join('hms_dental_prescription_template','hms_dental_prescription_template.id=hms_dental_prescription.template_id','left'); 
    $result_pre['prescription_list']= $this->db->get()->result();
    //echo $this->db->last_query();die;
    $this->db->select('hms_dental_prescription_investigation_booking.*,hms_dental_investigation.investigation_sub');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id = hms_dental_prescription_investigation_booking.investigation_template_id');
    $this->db->join('hms_dental_investigation','hms_dental_investigation.id = hms_dental_prescription_investigation_booking.sub_category_id'); 
    $this->db->where('hms_dental_prescription_investigation_booking.investigation_template_id = "'.$prescription_id.'"');

      $this->db->where('hms_dental_prescription_investigation_booking.booking_id = "'.$opd_booking_id.'"');
    $this->db->from('hms_dental_prescription_investigation_booking');
    $result_pre['investigation_template_data']=$this->db->get()->result();
    //echo $this->db->last_query();die;
    return $result_pre;

  }

    public function get_detail_by_prescription_id($prescription_id)
  {
    $this->db->select("hms_opd_booking.opd_type,hms_dental_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time"); 
   

    $this->db->from('hms_dental_prescription'); 
    $this->db->where('hms_dental_prescription.id',$prescription_id);
    $this->db->join('hms_patient','hms_patient.id=hms_dental_prescription.patient_id','left');
    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_dental_prescription.booking_id','left'); 
    $result_pre['prescription_list']= $this->db->get()->result();
    
    
 
     $this->db->select('hms_dental_chief_complaint_prescription.*,hms_dental_chief_complaints.chief_complaints');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id = hms_dental_chief_complaint_prescription.dental_prescription_id');
    $this->db->join('hms_dental_chief_complaints','hms_dental_chief_complaints.id = hms_dental_chief_complaint_prescription.complaint_name_id'); 
    $this->db->where('hms_dental_chief_complaint_prescription.dental_prescription_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_chief_complaint_prescription');
    $result_pre['prescription_list']['chief_complaint']=$this->db->get()->result();
    

    $this->db->select('hms_dental_prescription_previous_history.*');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id = hms_dental_prescription_previous_history.dental_prescription_id'); 
    $this->db->where('hms_dental_prescription_previous_history.dental_prescription_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_prescription_previous_history');
    $result_pre['prescription_list']['previous_history']=$this->db->get()->result();


    $this->db->select('hms_dental_prescription_allergy_booking.*');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id = hms_dental_prescription_allergy_booking.dental_prescription_id'); 
    $this->db->where('hms_dental_prescription_allergy_booking.dental_prescription_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_prescription_allergy_booking');
    $result_pre['prescription_list']['allergy']=$this->db->get()->result();
    

    $this->db->select('hms_dental_prescription_oral_habit_booking.*');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id =   hms_dental_prescription_oral_habit_booking.dental_prescription_id'); 
    $this->db->where('hms_dental_prescription_oral_habit_booking.dental_prescription_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_prescription_oral_habit_booking');
    $result_pre['prescription_list']['oral_habit_booking']=$this->db->get()->result();


    $this->db->select('hms_dental_prescription_diagnosis_booking.*');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id =   hms_dental_prescription_diagnosis_booking.dental_prescription_id'); 
    $this->db->where('hms_dental_prescription_diagnosis_booking.dental_prescription_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_prescription_diagnosis_booking');
    $result_pre['prescription_list']['diagnosis_list']=$this->db->get()->result();

     $this->db->select('hms_dental_prescription_treatment_booking.*');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id =   hms_dental_prescription_treatment_booking.dental_prescription_id'); 
    $this->db->where('hms_dental_prescription_treatment_booking.dental_prescription_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_prescription_treatment_booking');
    $result_pre['prescription_list']['treatment_booking']=$this->db->get()->result();

    $this->db->select('hms_dental_prescription_advice_booking.*');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id =   hms_dental_prescription_advice_booking.dental_prescription_id'); 
    $this->db->where('hms_dental_prescription_advice_booking.dental_prescription_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_prescription_advice_booking');
    $result_pre['prescription_list']['advice_booking']=$this->db->get()->result();
    $this->db->select('hms_dental_prescription_medicine_booking.*');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id = hms_dental_prescription_medicine_booking.medicine_template_id'); 
    $this->db->where('hms_dental_prescription_medicine_booking.medicine_template_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_prescription_medicine_booking');
    $result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();
    
    $this->db->select('hms_dental_prescription_investigation_booking.*,hms_dental_investigation.investigation_sub as sub_cat_name');
    $this->db->join('hms_dental_prescription','hms_dental_prescription.id = hms_dental_prescription_investigation_booking.investigation_template_id');
    $this->db->join('hms_dental_investigation','hms_dental_investigation.id = hms_dental_prescription_investigation_booking.sub_category_id','left');

    $this->db->where('hms_dental_prescription_investigation_booking.investigation_template_id = "'.$prescription_id.'"');
    $this->db->from('hms_dental_prescription_investigation_booking');
    $result_pre['prescription_list']['investigation_presc_list']=$this->db->get()->result();
    //echo $this->db->last_query();die;
    return $result_pre;

  }



    public function get_by_ids($id)
  {
    $this->db->select("hms_dental_prescription.*,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time");  //,hms_opd_prescription_patient_test.*,hms_opd_prescription_patient_pres.*
    $this->db->from('hms_dental_prescription'); 
    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_dental_prescription.booking_id','left');
    $this->db->where('hms_dental_prescription.id',$id);
    $this->db->where('hms_dental_prescription.is_deleted','0'); 
    $query = $this->db->get(); 
    return $query->row_array();
    
  }
  
  public function delete_dental($id="")
    {
      if(!empty($id) && $id>0)
      {
      $user_data = $this->session->userdata('auth_users');
      $this->db->set('is_deleted',1);
      $this->db->set('deleted_by',$user_data['id']);
      $this->db->set('deleted_date',date('Y-m-d H:i:s'));
      $this->db->where('flag_id',$id);
      $this->db->update('hms_opd_prescription');

      $this->db->set('is_deleted',1);
      $this->db->set('deleted_by',$user_data['id']);
      $this->db->set('deleted_date',date('Y-m-d H:i:s'));
      $this->db->where('id',$id);
      $this->db->update('hms_dental_prescription');

      
      } 
    }

  function template_format($data="",$branch_id='')
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_dental_branch_prescription_setting.*');
      $this->db->where($data);
      if(!empty($branch_id))
      {
        $this->db->where('hms_dental_branch_prescription_setting.branch_id = "'.$branch_id.'"');
      }
      else
      {
        $this->db->where('hms_dental_branch_prescription_setting.branch_id = "'.$users_data['parent_id'].'"');
      }
       
      $this->db->from('hms_dental_branch_prescription_setting');
      $result=$this->db->get()->row();
      return $result;

    }

     public function get_dental_dosage_vals($vals="")
  {
    $vals = urldecode($vals);
    $response = [];
    if(!empty($vals))
    {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('dosage','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('dosage LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_dental_medicine_dosage');
          $result = $query->result(); 
        ///  echo $this->db->last_query();die;
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->dosage;
            }
          }
          return $response; 
    } 
  }

  public function get_dental_medicine_auto_vals($vals="")
    {   
      $vals = urldecode($vals);
      //echo "hi";die;
      $response = [];
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');  
          $this->db->where('hms_medicine_entry.status','1'); 
          $this->db->order_by('hms_medicine_entry.medicine_name','ASC');
          $this->db->where('hms_medicine_entry.is_deleted',0);
          $this->db->where('hms_medicine_entry.medicine_name LIKE "%'.$vals.'%"');
          $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);  
          $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
          $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
          $query = $this->db->get('hms_medicine_entry');
          //echo $this->db->last_query();die;
          $result = $query->result(); 
          //print '<pre>'; print_r($result);die;
         // echo $this->db->last_query();
          if(!empty($result))
          { 
            $data = array();
            foreach($result as $vals)
            {
                 $name = $vals->medicine_name.'|'.$vals->medicine_unit.'|'.$vals->salt.'|'.$vals->company_name;
          array_push($data, $name);
                 //$response[] = $vals->medicine;
            }

            echo json_encode($data);
          }
          
          //return $response; 
      } 
    }

    public function get_dental_advice_vals($vals="")
    {
      $vals = urldecode($vals);
      $response = [];
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('advice','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('advice LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_dental_advice');
          $result = $query->result(); 
          //echo $this->db->last_query();
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->advice;
            }
          }
          return $response; 
      } 
    }

    public function get_dental_frequency_vals($vals="")
    {
      $vals = urldecode($vals);
      $response = [];
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('medicine_dosage_frequency','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('medicine_dosage_frequency LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_dental_medicine_dosage_frequency');
          $result = $query->result(); 
          //echo $this->db->last_query();
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->medicine_dosage_frequency;
            }
          }
          return $response; 
      } 
    }

   public function get_dental_duration_vals($vals="")
    {
      $vals = urldecode($vals);
      $response = [];
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('medicine_dosage_duration','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('medicine_dosage_duration LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_dental_medicine_dosage_duration');
          $result = $query->result(); 
        // echo $this->db->last_query();die;
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->medicine_dosage_duration;
            }
          }
          return $response; 
      } 
    }

  public function save()
  {
        $user_data = $this->session->userdata('auth_users');
        $chief_complain_data = $this->session->userdata('chief_complain_data_list');
        $previous_history_disease_data = $this->session->userdata('previous_history_disease_data');
        $previous_allergy_data = $this->session->userdata('previous_allergy_data'); 
        $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data');
        $diagnosis_list_data = $this->session->userdata('diagnosis_list_data');
         $treatment_list_data_values = $this->session->userdata('treatment_list_data_values');
        $previous_advice_data = $this->session->userdata('previous_advice_data');  
        
        $post = $this->input->post();
        //print"<pre>";print_r($previous_allergy_data);die;
        //print_r($chief_complain_data);
        //die;
        $next_appointment_date='';
        $check_appointment='';
         
        if(!empty($post['check_appointment']))
        {
          $check_appointment=1;
          if(!empty($post['next_appointment_date']) && $post['next_appointment_date']!='0000-00-00 00:00:00' && date('Y-m-d',strtotime($post['next_appointment_date']))!='1970-01-01')
        {
            $next_appointment_date = date('Y-m-d H:i',strtotime($post['next_appointment_date']));
        }

        }
        else
        {
          $check_appointment=0;
          $next_appointment_date = ''; 
        }
         if(!empty($post['template_list']))
        {
          $template_id=$post['template_list'];

        }
        else
        {
          $template_id=0;
        }
        $data = array( 
              "branch_id"=> $user_data['parent_id'],      
              "booking_code"=>$post['booking_code'],
              "patient_code"=>$post['patient_code'],
              "patient_id"=>$post['patient_id'],
              "booking_id"=>$post['booking_id'],
              "patient_name"=>$post['patient_name'],
              "mobile_no"=>$post['mobile_no'],
              "gender"=>$post['gender'], 
              "age_y"=>$post['age_y'],
              "age_m"=>$post['age_m'],
              "age_d"=>$post['age_d'],
              'template_id'=>$template_id,
              'next_appointment_date'=>$next_appointment_date,
              'check_appointment'=>$check_appointment,
              'date_time_new' =>$post['date_time_new'],
              'date_time_new' => $post['date_time_new'],
              'next_reason' =>$post['next_reason'],
              "status"=>1
              ); 
       
          // $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
          // $this->db->set('created_by',$user_data['id']);
          // $this->db->set('created_date',date('Y-m-d H:i:s'));
          // $this->db->insert('hms_dental_prescription',$data); 
          // //echo $this->db->last_query();die;
          // $data_id = $this->db->insert_id();
          
      if($next_appointment_date)  //next_appoitnement
      {
          if(!empty($post['booking_id']))
          {
             $appoitment_done =  $this->check_appointment_done($post['booking_id'],$post['patient_id']);
            $appointment_ids = $appoitment_done['id'];
          }
          
          //echo $appointment_ids; die;
          if(empty($appointment_ids))
          {
              
                $opd_details = $this->get_by_id($post['booking_id']);
          
                $appointment_code = generate_unique_id(20,$user_data['parent_id']);
            	
            	if(!empty($opd_details['referral_doctor']))
                {
                    $referal = $opd_details['referral_doctor'];
                }
                else
                {
                    $referal=0;
                }
                
                $appointment_data = array(
							
							'branch_id'=>$user_data['parent_id'],
							'parent_id'=>$post['booking_id'],
							'appointment_type'=>1, 
							'appointment'=>1,
							'appointment_code'=>$appointment_code, 
							'appointment_date'=>$next_appointment_date,
							'appointment_time'=>date('Y-m-d H:i:s'), 
							'type'=>1,
							'specialization_id'=>$opd_details['specialization_id'],
							'attended_doctor'=>$opd_details['attended_doctor'],
							'referral_doctor'=>$referal,
							'ref_by_other'=>$opd_details['ref_by_other'],
							'booking_date'=>date('Y-m-d H:i:s'),
							'booking_status'=>0,
							'doctor_checked_status'=>0,
							
							'booking_type'=>0,
							'gravida'=>0,
							'para'=>0,
							'token_no'=>0,
							'reciept_code'=>0,
							'available_time'=>0,
							'time_value'=>0,
							'doctor_slot'=>0,
							'opd_type'=>0,
							'cheque_no'=>0,
							'cheque_date'=>date('Y-m-d'),
							'transaction_no'=>0,
							'patient_bp'=>0,
							'patient_temp'=>0,
							'patient_weight'=>0,
							'patient_height'=>0,
							'patient_spo2'=>0,
							'patient_rbs'=>0,
							'status'=>0,
							'confirm_date'=>date('Y-m-d'),
							'next_app_date'=>date('Y-m-d'),
							'source_from'=>0,
							'mlc_status'=>0,
							'mlc'=>0,
							'remarks'=>0,
							'barcode_text'=>0,
							'barcode_type'=>0,
							'barcode_image'=>0,
							'modified_by'=>0,
							'ip_address'=>0,
							'is_deleted'=>0,
							'deleted_by'=>0,
							'deleted_date'=>date('Y-m-d'),
							'dilate_start_time'=>date('Y-m-d H:i'),
							'dilate_time'=>date('Y-m-d H:i:s'),
							'cyclo_start_time'=>date('Y-m-d H:i'),
							'cyclo_time'=>date('Y-m-d H:i'),
							'app_type'=>0,
							
							
							
					);
            	
            	$this->db->set('patient_id',$opd_details['patient_id']);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$appointment_data);    
	            $appointment_id = $this->db->insert_id(); 
	            //echo $this->db->last_query(); exit;
              
          }
          
      }
      //end of appoitnment

      if(!empty($post['data_id']) && $post['data_id']>0)
      {
        //echo"hello";die;
         $data_id = $post['data_id'];
         $this->db->where('id',$post['data_id']);
         $this->db->update('hms_dental_prescription',$data);
      } 
      else
      {
        
        /*if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
          $this->db->where('patient_id',$post['patient_id']);
          $this->db->delete('hms_dental_prescription');

        }*/
        $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
        $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_dental_prescription',$data);  
        $data_id = $this->db->insert_id();
        //echo $data_id; die; 
      }


          $data_opd = array( 
          "branch_id"=> $user_data['parent_id'],      
          "booking_code"=>$post['booking_code'],
          "patient_code"=>$post['patient_code'],
          "patient_id"=>$post['patient_id'],
          "booking_id"=>$post['booking_id'],
          "patient_name"=>$post['patient_name'],
          "mobile_no"=>$post['mobile_no'],
          "gender"=>$post['gender'], 
          "age_y"=>$post['age_y'],
          "age_m"=>$post['age_m'],
          "age_d"=>$post['age_d'],
          'flag'=>2,
          'flag_id'=>$data_id,
          'next_appointment_date'=>$next_appointment_date,
          "status"=>1
          ); 

         //echo"test"; echo"<pre>";print_r($data_opd);
         // die;
   
    if(!empty($post['data_id']) && $post['data_id']>0)
    {
      $data_id='';
      if(!empty($post['data_id']))
      {
        $data_id=$post['data_id'];
      }
      else
      {
        $data_id='';
      }
     // echo "test1"; echo $data_id;
      //die;
      $this->db->set('modified_by',$user_data['id']);
      $this->db->set('modified_date',date('Y-m-d H:i:s'));
      $this->db->where('patient_id',$post['patient_id']);
      $this->db->where('flag_id',$data_id);
      $this->db->where('flag',2);
      $this->db->update('hms_opd_prescription',$data_opd);
    }
    else
    {
        
      //echo"test2"; echo $data_id; 
      //die;
       /*if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
          $this->db->where('patient_id',$post['patient_id']);
          $this->db->where('branch_id',$user_data['parent_id']);
          $this->db->delete('hms_opd_prescription');

        }*/     
      $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
      $this->db->set('created_by',$user_data['id']);
      $this->db->set('created_date',date('Y-m-d H:i:s'));
      $this->db->insert('hms_opd_prescription',$data_opd); 
    }
    
    if(!empty($post['data_id']) && $post['data_id']>0)
    {
        
        //delete all data first 
        $this->db->where('dental_prescription_id',$data_id);
        $this->db->delete('hms_dental_chief_complaint_prescription');
        
        $this->db->where('dental_prescription_id',$post['data_id']);
        $this->db->delete('hms_dental_prescription_previous_history');
        
        $this->db->where('dental_prescription_id',$post['data_id']);
        $this->db->delete('hms_dental_prescription_allergy_booking'); 
        
        $this->db->where('dental_prescription_id',$post['data_id']);
        $this->db->delete('hms_dental_prescription_oral_habit_booking'); 
        
        $this->db->where('dental_prescription_id',$post['data_id']);
        $this->db->delete('hms_dental_prescription_diagnosis_booking');
        
        $this->db->where('dental_prescription_id',$post['data_id']);
        $this->db->delete('hms_dental_prescription_treatment_booking');
        
        $this->db->where('dental_prescription_id',$post['data_id']);
        $this->db->delete('hms_dental_prescription_advice_booking');
        
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('medicine_template_id',$data_id);
        $this->db->delete('hms_dental_prescription_medicine_booking');
                
        //end of delete
  //chief_complain_data
      $data_id='';
      if(!empty($post['data_id']))
      {
        $data_id=$post['data_id'];
      }
      else
      {
        $data_id='';
      }
            if(!empty($chief_complain_data))
              {
                 
              foreach ($chief_complain_data as $value) 
              {
                  $chief_compalint_data_all = array(
                    "dental_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    "complaint_name_id"=>$value['chief_complaint_id'],
                    'teeth_name'=>$value['teeth_name'],
                    'tooth_number_id'=>$value['get_teeth_number_val'],
                    'reason'=>$value['reason'],
                    'duration_number'=>$value['number'],
                    'duration_time'=>$value['time'],
                     'status'=>1,
                    );
                  
                  $this->db->insert('hms_dental_chief_complaint_prescription',$chief_compalint_data_all); 
                //echo"whello";echo"<pre>";echo $this->db->last_query();
                  $test_data_id = $this->db->insert_id(); 
              } 
            }
       
       //previous_history_disease_data
            if(!empty($previous_history_disease_data))
              {
                //print_r($previous_history_disease_data);
                

              foreach ($previous_history_disease_data as $value) 
              {
                  $previous_history_data = array(
                    "dental_prescription_id "=>$data_id,
                    "disease_id "=>$value['disease_id'],
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    "disease_name"=>$value['disease_value'],
                    'disease_details'=>$value['disease_details'],                    
                    'operation'=>$value['operation'],
                    'operation_date'=>date('Y-m-d',strtotime($value['operation_date'])),
                    );
                  
                  $this->db->insert('hms_dental_prescription_previous_history',$previous_history_data); 
                 //echo"<pre>";echo $this->db->last_query();                 
                  $test_data_id = $this->db->insert_id(); 
              } 
            }
         //previous_allergy_data
          if(!empty($previous_allergy_data))
                {
                  //print_r($previous_allergy_data);
                 
                foreach ($previous_allergy_data as $value) 
                {
                    $allergy_data = array(
                    "dental_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                      "booking_id"=>$post['booking_id'],
                      'allergy_id'=>$value['allergy_id'],
                      "allergy_name"=>$value['allergy_value'],
                      'reason'=>$value['reason'],
                      'number'=>$value['number'],
                      'time'=>$value['time']
                      
                      );

                    $this->db->insert('hms_dental_prescription_allergy_booking',$allergy_data); 
                   // echo"<pre>";echo $this->db->last_query(); 
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

              if(!empty($previous_oral_habit_data))
                {
                 

                foreach ($previous_oral_habit_data as $value) 
                {
                  //print_r($previous_oral_habit_data);
                    $oral_habit_data = array(
                      "dental_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                      "booking_id"=>$post['booking_id'],
                      'oral_habit_id'=>$value['habit_id'],
                      "oral_habit_name"=>$value['oral_habit_value'],
                      'number'=>$value['number'],
                      'time'=>$value['time']
                      
                      );
                    
                    $this->db->insert('hms_dental_prescription_oral_habit_booking',$oral_habit_data); 
                    //echo"<pre>";echo $this->db->last_query(); 
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

              if(!empty($diagnosis_list_data))
                {
                  
                foreach ($diagnosis_list_data as $value_diagnosis) 
                {
                  //print_r($value_diagnosis);
                  //die;
                    $diagnosis_list = array(
                      "dental_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                      'diagnosis_id'=>$value_diagnosis['diagnosis_id'],
                      "diagnosis_name"=>$value_diagnosis['diagnosis_value'],
                      'tooth_number'=>$value_diagnosis['get_teeth_number_val_diagnosis'],
                      'teeth_name'=>$value_diagnosis['teeth_name_d']
                      
                      );
                    
                    $this->db->insert('hms_dental_prescription_diagnosis_booking',$diagnosis_list); 
                   //echo"<pre>";echo $this->db->last_query(); 
                    $test_data_id = $this->db->insert_id(); 
                } 
              }
               if(!empty($treatment_list_data_values))
                {
                    

                foreach ($treatment_list_data_values as $value_treatment) 
                {
                  //print_r($value_treatment);
                  //die;
                    $treatment_booking = array(
                      "dental_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                      'treatment_id'=>$value_treatment['treatment_id'],
                      "treatment_name"=>$value_treatment['treatment_value'],
                      'tooth_number'=>$value_treatment['get_teeth_number_val_treatment'],
                      'teeth_name'=>$value_treatment['teeth_name_treatment']
                      ,'treatment_type_id'=>$value_treatment['treatment_type_id'],'treatment_remarks'=>$value_treatment['treatment_remarks']
                      
                      );
                    
                    $this->db->insert('hms_dental_prescription_treatment_booking',$treatment_booking); 
                   //echo"<pre>";echo $this->db->last_query(); 
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

              if(!empty($previous_advice_data))
                {
                    

                foreach ($previous_advice_data as $value) 
                {
                    $advice_data = array(
                       "dental_prescription_id "=>$data_id,
                        'branch_id'=>$user_data['parent_id'],
                        "patient_id"=>$post['patient_id'],
                         "booking_id"=>$post['booking_id'],
                      'advice_id'=>$value['advice_id'],
                      "advice_name"=>$value['advice_value']
                          
                      );
                    
                    $this->db->insert('hms_dental_prescription_advice_booking',$advice_data);
                    //echo"<pre>";echo $this->db->last_query(); 
                      $test_data_id = $this->db->insert_id(); 
                } 
              }
                
               
                $this->db->where('branch_id',$user_data['parent_id']);
                $this->db->where('investigation_template_id',$data_id);
                 $this->db->delete('hms_dental_prescription_investigation_booking');

               if(!empty($post['sub_category']))
               {
                

                 $n=0;
                 foreach($post['sub_category'] as $key=>$val)
                {
                  $k=0;
                  foreach ($val['name'] as $value) 
                  {
                    $teeth_no = $val['teeth_no'][$k]; 
                    $remarks = $val['remarks'][$k];
                    $teeth_name = $value;
                    $data = array(
                                 "branch_id"=>$user_data['parent_id'],
                                 "investigation_template_id"=>$data_id,
                                 "patient_id"=>$post['patient_id'],
                                  "booking_id"=>$post['booking_id'],
                                 "sub_category_id"=>$key,
                                 "remarks"=>$remarks,
                                 "teeth_name"=>$teeth_name,
                                 "teeth_no"=>$teeth_no
                               );
                    $this->db->insert('hms_dental_prescription_investigation_booking',$data);  
                    //echo"<pre>";echo $this->db->last_query(); 
                  } 
                
                
                $n++;
                //echo"gghfg";echo $this->db->last_query(); 
              }
          }

          if(isset($post['prescription']) && !empty($post['prescription']))
         {
             
            
         // echo "hi";die;
         
        $new_array_pres=array_values($post['prescription']);
        $total_prescription = count($new_array_pres);
          //echo $total_prescription;die;
        for($i=0;$i<=$total_prescription-1;$i++)
        {
             if(!empty($new_array_pres[$i]['medicine_company']))
             {
            
              $medicine_company[$i]=$new_array_pres[$i]['medicine_company'];
             }
             else
             {
              
              $medicine_company[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_salt']))
             {
              $medicine_salt[$i]=$new_array_pres[$i]['medicine_salt'];
             }
             else
             {
              $medicine_salt[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_type']))
             {
              $medicine_type[$i]=$new_array_pres[$i]['medicine_type'];
             }
             else
             {
              $medicine_type[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_dose']))
             {
              $medicine_dose[$i]=$new_array_pres[$i]['medicine_dose'];
             }
             else
             {
              $medicine_dose[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_duration']))
             {
              $medicine_duration[$i]=$new_array_pres[$i]['medicine_duration'];
             }
             else
             {
              $medicine_duration[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_frequency']))
             {
              $medicine_frequency[$i]=$new_array_pres[$i]['medicine_frequency'];
             }
             else
             {
              $medicine_frequency[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_advice']))
             {
              $medicine_advice[$i]=$new_array_pres[$i]['medicine_advice'];
             }
             else
             {
              $medicine_advice[$i]='';
             }
             //echo "hi";die;
             $prescription_data = array(
            "medicine_template_id"=>$data_id,
            "branch_id"=>$user_data['parent_id'],
            "medicine_name"=>$new_array_pres[$i]['medicine_name'],
            "patient_id"=>$post['patient_id'],
             "booking_id"=>$post['booking_id'],
            "medicine_brand"=>$medicine_company[$i],
            "medicine_salt"=>$medicine_salt[$i],
            "medicine_type"=>$medicine_type[$i],
            "medicine_dose"=>$medicine_dose[$i],
            "medicine_duration"=>$medicine_duration[$i],
            "medicine_frequency"=>$medicine_frequency[$i],
            "medicine_advice"=>$medicine_advice[$i]
            );

         //print '<pre>'; print_r($prescription_data);
          $this->db->insert('hms_dental_prescription_medicine_booking',$prescription_data);
        //echo"<pre>";echo $this->db->last_query(); die;
        }
        

      }
    }

      else
          {
  //chief_complain_data
       //echo $data_id;
       //die;
            if(!empty($chief_complain_data))
              {
                 
              foreach ($chief_complain_data as $value) 
              {
                  $chief_compalint_data_all = array(
                    "dental_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    "complaint_name_id"=>$value['chief_complaint_id'],
                    'teeth_name'=>$value['teeth_name'],
                    'tooth_number_id'=>$value['get_teeth_number_val'],
                    'reason'=>$value['reason'],
                    'duration_number'=>$value['number'],
                    'duration_time'=>$value['time'],
                     'status'=>1,
                    );
                  
                  $this->db->insert('hms_dental_chief_complaint_prescription',$chief_compalint_data_all); 
                 //echo"hello";echo"<pre>";echo $this->db->last_query();die;
                  $test_data_id = $this->db->insert_id(); 
              } 
            }
       
       //previous_history_disease_data
            if(!empty($previous_history_disease_data))
              {
                //print_r($previous_history_disease_data);
              foreach ($previous_history_disease_data as $value) 
              {
                  $previous_history_data = array(
                    "dental_prescription_id "=>$data_id,
                    "disease_id "=>$value['disease_id'],
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    "disease_name"=>$value['disease_value'],
                    'disease_details'=>$value['disease_details'],                    
                    'operation'=>$value['operation'],
                    'operation_date'=>date('Y-m-d',strtotime($value['operation_date'])),
                    );
                  
                  $this->db->insert('hms_dental_prescription_previous_history',$previous_history_data); 
                 // echo $this->db->last_query();die;
                  $test_data_id = $this->db->insert_id(); 
              } 
            }
         //previous_allergy_data
          if(!empty($previous_allergy_data))
                {
                  //print_r($previous_allergy_data);
                foreach ($previous_allergy_data as $value) 
                {
                    $allergy_data = array(
                    "dental_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                      "booking_id"=>$post['booking_id'],
                      'allergy_id'=>$value['allergy_id'],
                      "allergy_name"=>$value['allergy_value'],
                      'reason'=>$value['reason'],
                      'number'=>$value['number'],
                      'time'=>$value['time']
                      
                      );

                    $this->db->insert('hms_dental_prescription_allergy_booking',$allergy_data); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

              if(!empty($previous_oral_habit_data))
                {
                foreach ($previous_oral_habit_data as $value) 
                {
                  //print_r($previous_oral_habit_data);
                    $oral_habit_data = array(
                      "dental_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                      "booking_id"=>$post['booking_id'],
                      'oral_habit_id'=>$value['habit_id'],
                      "oral_habit_name"=>$value['oral_habit_value'],
                      'number'=>$value['number'],
                      'time'=>$value['time']
                      
                      );
                    
                    $this->db->insert('hms_dental_prescription_oral_habit_booking',$oral_habit_data); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

              if(!empty($diagnosis_list_data))
                {
                foreach ($diagnosis_list_data as $value_diagnosis) 
                {
                  //print_r($value_diagnosis);
                  //die;
                    $diagnosis_list = array(
                      "dental_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                      'diagnosis_id'=>$value_diagnosis['diagnosis_id'],
                      "diagnosis_name"=>$value_diagnosis['diagnosis_value'],
                      'tooth_number'=>$value_diagnosis['get_teeth_number_val_diagnosis'],
                      'teeth_name'=>$value_diagnosis['teeth_name_d']
                      
                      );
                    
                    $this->db->insert('hms_dental_prescription_diagnosis_booking',$diagnosis_list); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }
               if(!empty($treatment_list_data_values))
                {
                foreach ($treatment_list_data_values as $value_treatment) 
                {
                  //print_r($value_treatment);
                  //die;
                    $treatment_booking = array(
                      "dental_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                      'treatment_id'=>$value_treatment['treatment_id'],
                      "treatment_name"=>$value_treatment['treatment_value'],
                      'tooth_number'=>$value_treatment['get_teeth_number_val_treatment'],
                      'teeth_name'=>$value_treatment['teeth_name_treatment']
                      ,'treatment_type_id'=>$value_treatment['treatment_type_id'],'treatment_remarks'=>$value_treatment['treatment_remarks']
                      
                      );
                    
                    $this->db->insert('hms_dental_prescription_treatment_booking',$treatment_booking); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

              if(!empty($previous_advice_data))
                {
                foreach ($previous_advice_data as $value) 
                {
                  //echo"<pre>";print_r($data_id);
                    //die;
                    $advice_data = array(
                       "dental_prescription_id "=>$data_id,
                        'branch_id'=>$user_data['parent_id'],
                        "patient_id"=>$post['patient_id'],
                         "booking_id"=>$post['booking_id'],
                      'advice_id'=>$value['advice_id'],
                      "advice_name"=>$value['advice_value']
                          
                      );
                    
                    $this->db->insert('hms_dental_prescription_advice_booking',$advice_data); 
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

               if(!empty($post['sub_category']))
               {
                //print_r($post['sub_category']);
                 $n=0;
                 foreach($post['sub_category'] as $key=>$val)
                {
                  $k=0;
                  foreach ($val['name'] as $value) 
                  {
                    //echo"<pre>";print_r($data_id);
                    //die;
                    $teeth_no = $val['teeth_no'][$k]; 
                    $remarks = $val['remarks'][$k];
                    $teeth_name = $value;
                    $data = array(
                                 "branch_id"=>$user_data['parent_id'],
                                 "investigation_template_id"=>$data_id,
                                 "patient_id"=>$post['patient_id'],
                                  "booking_id"=>$post['booking_id'],
                                 "sub_category_id"=>$key,
                                 "remarks"=>$remarks,
                                 "teeth_name"=>$teeth_name,
                                 "teeth_no"=>$teeth_no
                               );
                    $this->db->insert('hms_dental_prescription_investigation_booking',$data);  
                    //echo $this->db->last_query();die;
                  } 
                
                
                $n++;
                //echo $this->db->last_query(); 
              }
          }
          if(isset($post['prescription']) && !empty($post['prescription']))
         {
         // echo "hi";die;
        $new_array_pres=array_values($post['prescription']);
        $total_prescription = count($new_array_pres);
          //echo $total_prescription;die;
        for($i=0;$i<=$total_prescription-1;$i++)
        {
             if(!empty($new_array_pres[$i]['medicine_company']))
             {
            
              $medicine_company[$i]=$new_array_pres[$i]['medicine_company'];
             }
             else
             {
              
              $medicine_company[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_salt']))
             {
              $medicine_salt[$i]=$new_array_pres[$i]['medicine_salt'];
             }
             else
             {
              $medicine_salt[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_type']))
             {
              $medicine_type[$i]=$new_array_pres[$i]['medicine_type'];
             }
             else
             {
              $medicine_type[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_dose']))
             {
              $medicine_dose[$i]=$new_array_pres[$i]['medicine_dose'];
             }
             else
             {
              $medicine_dose[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_duration']))
             {
              $medicine_duration[$i]=$new_array_pres[$i]['medicine_duration'];
             }
             else
             {
              $medicine_duration[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_frequency']))
             {
              $medicine_frequency[$i]=$new_array_pres[$i]['medicine_frequency'];
             }
             else
             {
              $medicine_frequency[$i]='';
             }
             if(isset($new_array_pres[$i]['medicine_advice']))
             {
              $medicine_advice[$i]=$new_array_pres[$i]['medicine_advice'];
             }
             else
             {
              $medicine_advice[$i]='';
             }
             //echo "hi";die;
             $prescription_data = array(
            "medicine_template_id"=>$data_id,
            "branch_id"=>$user_data['parent_id'],
            "medicine_name"=>$new_array_pres[$i]['medicine_name'],
            "patient_id"=>$post['patient_id'],
             "booking_id"=>$post['booking_id'],
            "medicine_brand"=>$medicine_company[$i],
            "medicine_salt"=>$medicine_salt[$i],
            "medicine_type"=>$medicine_type[$i],
            "medicine_dose"=>$medicine_dose[$i],
            "medicine_duration"=>$medicine_duration[$i],
            "medicine_frequency"=>$medicine_frequency[$i],
            "medicine_advice"=>$medicine_advice[$i]
            );

         // print '<pre>'; print_r($prescription_data);die;
          $this->db->insert('hms_dental_prescription_medicine_booking',$prescription_data);
          //echo $this->db->last_query(); die; 
        }
        

      }
              
          }

  }


   public function get_template_data($id)
   {
      //echo "hi";die;
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_template.*');
    $this->db->from('hms_dental_prescription_template'); 
    $this->db->where('hms_dental_prescription_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_template.id',$id);
    $this->db->where('hms_dental_prescription_template.is_deleted','0');
    $query = $this->db->get(); 
   // echo $this->db->last_query();die;
    $result = $query->row_array();
    return json_encode($result);

   }

 public function get_chief_complaint_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_chief_complaints_template.*');
    $this->db->from('hms_dental_prescription_chief_complaints_template'); 
    $this->db->where('hms_dental_prescription_chief_complaints_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_chief_complaints_template.template_id',$template_id);
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    $result = $query->result_array();
   // return json_encode($result);
    return $result;

 }
 public function get_chief_complaint_data1($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_chief_complaints_template.*');
    $this->db->from('hms_dental_prescription_chief_complaints_template'); 
    $this->db->where('hms_dental_prescription_chief_complaints_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_chief_complaints_template.template_id',$template_id);
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    $result = $query->result_array();
    return $result;

 }
public function get_previous_history_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_disease_template.*');
    $this->db->from('hms_dental_prescription_disease_template'); 
    $this->db->where('hms_dental_prescription_disease_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_disease_template.template_id',$template_id);
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    $result = $query->result_array();
    return $result;

 }
 public function get_previous_allergy_data($template_id)
 { 
  // echo "hi";die;
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_allergy_template.*');
    $this->db->from('hms_dental_prescription_allergy_template'); 
    $this->db->where('hms_dental_prescription_allergy_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_allergy_template.template_id',$template_id);
    $query = $this->db->get(); 
   // echo $this->db->last_query();die;
    $result = $query->result_array();
    return $result;


 }
 public function get_previous_oral_habit_data($template_id)
 {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_oral_habit_template.*');
    $this->db->from('hms_dental_prescription_oral_habit_template'); 
    $this->db->where('hms_dental_prescription_oral_habit_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_oral_habit_template.template_id',$template_id);
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    $result = $query->result_array();
    return $result;


 }
 public function get_previous_diagnosis_data($template_id)
 {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_diagnosis_template.*');
    $this->db->from('hms_dental_prescription_diagnosis_template'); 
    $this->db->where('hms_dental_prescription_diagnosis_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_diagnosis_template.template_id',$template_id);
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    $result = $query->result_array();
    return $result;


 }
 public function get_previous_treatment_data($template_id)
 {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_treatment_template.*');
    $this->db->from('hms_dental_prescription_treatment_template'); 
    $this->db->where('hms_dental_prescription_treatment_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_treatment_template.template_id',$template_id);
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    $result = $query->result_array();
    return $result;


 }
public function get_previous_advice_data($template_id)
 {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_advice_template.*');
    $this->db->from('hms_dental_prescription_advice_template'); 
    $this->db->where('hms_dental_prescription_advice_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_advice_template.template_id',$template_id);
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    $result = $query->result_array();
    return $result;


 }
public function get_previous_medicine_data($template_id)
 {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_medicine_template.*');
    $this->db->from('hms_dental_prescription_medicine_template'); 
    $this->db->where('hms_dental_prescription_medicine_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dental_prescription_medicine_template.template_id',$template_id);
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    $result = $query->result_array();
    return json_encode($result);


 }
public function get_previous_investigation_data($template_id)
{
    //echo $template_id;die
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_dental_prescription_investigation_template.*,hms_dental_investigation.investigation_sub');
    $this->db->join('hms_dental_prescription_template','hms_dental_prescription_template.id = hms_dental_prescription_investigation_template.template_id');
    $this->db->join('hms_dental_investigation','hms_dental_investigation.id = hms_dental_prescription_investigation_template.sub_category_id'); 
    $this->db->where('hms_dental_prescription_investigation_template.template_id = "'.$template_id.'"');
     $this->db->where('hms_dental_prescription_investigation_template.branch_id',$user_data['parent_id']); 
    $this->db->from('hms_dental_prescription_investigation_template');
    $query = $this->db->get(); 
   // echo $this->db->last_query();die;
    $result = $query->result_array();
    return $result;
}
public function get_chief_complaint_list($prescription_id="",$opd_booking_id)
 {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_dental_chief_complaint_prescription.*,hms_dental_prescription_chief_complaints_template.complaint_name'); 
    $this->db->join('hms_dental_prescription_chief_complaints_template','hms_dental_prescription_chief_complaints_template.complaint_id = hms_dental_chief_complaint_prescription.complaint_name_id','left');
      $this->db->where('hms_dental_chief_complaint_prescription.branch_id',$users_data['parent_id']);  
      $this->db->where('hms_dental_chief_complaint_prescription.dental_prescription_id = "'.$prescription_id.'"');  
      $this->db->where('hms_dental_chief_complaint_prescription.booking_id = "'.$opd_booking_id.'"'); 
    
    $query = $this->db->get('hms_dental_chief_complaint_prescription');
  // echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $chief_complaint_list = [];
    if(!empty($result))
    { 
      $i=1;
      foreach($result as $chief_complaint)
      {
             $chief_complaint_list[$i]['chief_id'] = $i;
             $chief_complaint_list[$i]['chief_complaint_id'] = $chief_complaint->complaint_name_id;
               $chief_complaint_list[$i]['chief_complaint_value'] = $chief_complaint->complaint_name;
               $chief_complaint_list[$i]['teeth_name'] = $chief_complaint->teeth_name;
               $chief_complaint_list[$i]['get_teeth_number_val'] = $chief_complaint->tooth_number_id;
               $chief_complaint_list[$i]['reason'] = $chief_complaint->reason;
               $chief_complaint_list[$i]['number'] = $chief_complaint->duration_number;
               $chief_complaint_list[$i]['time'] = $chief_complaint->duration_time;
      $i++;
      }
    }
   // print_r($chief_complaint_list);die;
    return $chief_complaint_list; 
 }
 public function get_disease_list($prescription_id="",$opd_booking_id="")
 {
   $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');   
   $this->db->where('dental_prescription_id',$prescription_id); 
   $this->db->where('booking_id',$opd_booking_id); 
   $this->db->where('branch_id',$users_data['parent_id']); 
       
      
    $query = $this->db->get('hms_dental_prescription_previous_history');
   // echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $disease_list = [];
    if(!empty($result))
    { 
      $i=1;
      foreach($result as $disease)
      {
               $disease_list[$i]['prev_id'] = $i;
               $disease_list[$i]['disease_id'] = $disease->disease_id;
               $disease_list[$i]['disease_value'] = $disease->disease_name;
               $disease_list[$i]['disease_details'] = $disease->disease_details;
               $disease_list[$i]['operation'] = $disease->operation;
               $disease_list[$i]['operation_date'] = $disease->operation_date;
      $i++;
      }
    }
    //print_r($disease_list);die;
    return $disease_list; 
 }
 public function get_allergy_list($prescription_id="",$opd_booking_id="")
 {
   $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');   
   $this->db->where('dental_prescription_id',$prescription_id); 
   $this->db->where('booking_id',$opd_booking_id); 
   $this->db->where('branch_id',$users_data['parent_id']); 
       
      
    $query = $this->db->get('hms_dental_prescription_allergy_booking');
    //echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $allergy_list = [];
    if(!empty($result))
    { 
      $i=1;
      foreach($result as $allergy)
      {
               
               $allergy_list[$i]['aller_id'] = $i;
               $allergy_list[$i]['allergy_id'] = $allergy->allergy_id;
               $allergy_list[$i]['allergy_value'] = $allergy->allergy_name;
               $allergy_list[$i]['reason'] = $allergy->reason;
               $allergy_list[$i]['number'] = $allergy->number;
               $allergy_list[$i]['time'] = $allergy->time;
      $i++;
      }
    }
    //print_r($disease_list);die;
    return $allergy_list; 
 }
 public function get_oral_habit_list($prescription_id="",$opd_booking_id="")
 {
   $users_data = $this->session->userdata('auth_users'); 
   $this->db->select('*');   
   $this->db->where('dental_prescription_id',$prescription_id); 
   $this->db->where('booking_id',$opd_booking_id); 
   $this->db->where('branch_id',$users_data['parent_id']); 
       
      
    $query = $this->db->get('hms_dental_prescription_oral_habit_booking');
    //echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $oral_habit_list = [];
    if(!empty($result))
    { 
      $i=1;
      foreach($result as $oral_habit)
      {
          
               $oral_habit_list[$i]['hab_id'] = $i;
               $oral_habit_list[$i]['habit_id'] = $oral_habit->oral_habit_id;
               $oral_habit_list[$i]['oral_habit_value'] = $oral_habit->oral_habit_name;
               $oral_habit_list[$i]['number'] = $oral_habit->number;
               $oral_habit_list[$i]['time'] = $oral_habit->time;
      $i++;
      }
    }
    //print_r($disease_list);die;
    return $oral_habit_list; 
 }
 public function get_diagnosis_list($prescription_id="",$opd_booking_id="")
 {
   $users_data = $this->session->userdata('auth_users'); 
   $this->db->select('*');   
   $this->db->where('dental_prescription_id',$prescription_id); 
   $this->db->where('booking_id',$opd_booking_id); 
   $this->db->where('branch_id',$users_data['parent_id']); 
       
      
    $query = $this->db->get('hms_dental_prescription_diagnosis_booking');
    //echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $diagnosis_list = [];
    if(!empty($result))
    { 
      $i=1;
      foreach($result as $diagnosislist)
      {
               $diagnosis_list[$i]['diag_id'] = $i;
               $diagnosis_list[$i]['diagnosis_id'] = $diagnosislist->diagnosis_id;
               $diagnosis_list[$i]['diagnosis_value'] = $diagnosislist->diagnosis_name;
               $diagnosis_list[$i]['teeth_name_d'] = $diagnosislist->teeth_name;
               $diagnosis_list[$i]['get_teeth_number_val_diagnosis'] = $diagnosislist->tooth_number;
      $i++;
      }
    }
    //print_r($disease_list);die;
    return $diagnosis_list; 
 }
 public function get_treatment_list($prescription_id="",$opd_booking_id="")
 {
  $users_data = $this->session->userdata('auth_users'); 
   $this->db->select('*');   
   $this->db->where('dental_prescription_id',$prescription_id); 
   $this->db->where('booking_id',$opd_booking_id); 
   $this->db->where('branch_id',$users_data['parent_id']); 
       
      
    $query = $this->db->get('hms_dental_prescription_treatment_booking');
    //echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $treatment_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $treatmentlist)
      {
               
               $treatment_list[$i]['treat_id'] = $i;
               $treatment_list[$i]['treatment_id'] = $treatmentlist->treatment_id;
               $treatment_list[$i]['treatment_value'] = $treatmentlist->treatment_name;
               $treatment_list[$i]['teeth_name_treatment'] = $treatmentlist->teeth_name;
               $treatment_list[$i]['get_teeth_number_val_treatment'] = $treatmentlist->tooth_number;
               
               $treatment_list[$i]['treatment_type_id'] = $treatmentlist->treatment_type_id;
               $treatment_list[$i]['treatment_remarks'] = $treatmentlist->treatment_remarks;
               
               
      $i++;
      }
    }
    //print_r($disease_list);die;
    return $treatment_list; 
 }
 public function get_advice_list($prescription_id="",$opd_booking_id="")
 {
   $users_data = $this->session->userdata('auth_users'); 
   $this->db->select('*');   
   $this->db->where('dental_prescription_id',$prescription_id); 
   $this->db->where('booking_id',$opd_booking_id); 
   $this->db->where('branch_id',$users_data['parent_id']); 
   $query = $this->db->get('hms_dental_prescription_advice_booking');
    //echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $advice_list = [];
    if(!empty($result))
    { 
      $i=1;
      foreach($result as $advicelist)
      {
               $advice_list[$i]['advi_id'] = $i;
               $advice_list[$i]['advice_id'] = $advicelist->advice_id;
               $advice_list[$i]['advice_value'] = $advicelist->advice_name;
              
      $i++;
      }
    }
    //print_r($disease_list);die;
    return $advice_list; 
 }
 public function get_dental_medicine_prescription_template($prescription_id="",$opd_booking_id="")
 {
    $users_data=$this->session->userdata('auth_users');
    $this->db->select("hms_dental_prescription_medicine_booking.*"); 
    $this->db->from('hms_dental_prescription_medicine_booking'); 
    $this->db->where('hms_dental_prescription_medicine_booking.medicine_template_id',$prescription_id);
    $this->db->where('hms_dental_prescription_medicine_booking.booking_id',$opd_booking_id);
    $this->db->where('hms_dental_prescription_medicine_booking.branch_id',$users_data['parent_id']);
    $query = $this->db->get(); 
   // echo $this->db->last_query();die;
    $result = $query->result(); 
        return $result;
  
 }
  //neha 20-2-2019
 public function save_file($filename="")
  {
    //print_r($filename);die;
    $user_data = $this->session->userdata('auth_users');
    $post = $this->input->post();  
    $data = array( 
          'prescription_id'=>$post['prescription_id'],
          'doc_name'=>$post['doc_name'],
          'branch_id' => $user_data['parent_id'],
          'status' =>1,
          'ip_address'=>$_SERVER['REMOTE_ADDR']
             );
    if(!empty($post['data_id']) && $post['data_id']>0)
    {    
      if(!empty($filename))
      {
         $this->db->set('prescription_files',$filename);
      }
            $this->db->set('modified_by',$user_data['id']);
      $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
      $this->db->update('hms_dental_prescription_files',$data);  
    }
    else{    
      if(!empty($filename))
      {
         $this->db->set('prescription_files',$filename);
      }
      $this->db->set('created_by',$user_data['id']);
      $this->db->set('created_date',date('Y-m-d H:i:s'));
      $this->db->insert('hms_dental_prescription_files',$data);               
    }   
  }

  public function get_prescription_files($prescription_id='')
  {
    if(!empty($prescription_id) && $prescription_id>0)
      {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('*');
      $this->db->where('is_deleted',0);
      $this->db->where('status',1);
      $this->db->where('prescription_id',$prescription_id);
      $query = $this->db->get('hms_dental_prescription_files'); 
      $result = $query->result(); 
      //echo $this->db->last_query(); exit;
      return $result;
      
      } 
  }
  
  public function get_detail_by_booking_id($id,$branch_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_opd_booking.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_opd_booking.patient_bp as patientbp,hms_opd_booking.patient_temp as patienttemp,hms_opd_booking.patient_weight as patientweight,hms_opd_booking.patient_height as patientpr,hms_opd_booking.patient_spo2 as patientspo,hms_opd_booking.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation,hms_doctors.header_content,hms_doctors.seprate_header,hms_doctors.opd_header,hms_doctors.billing_header,hms_doctors.prescription_header');
		$this->db->from('hms_opd_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id');
		$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking. attended_doctor','left');
		
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		if(!empty($branch_id))
		{
			$this->db->where('hms_opd_booking.branch_id',$branch_id); 
		}
		else
		{
			$this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 	
		}
		
		$this->db->where('hms_opd_booking.id',$id);
		$this->db->where('hms_opd_booking.is_deleted','0');
		 
		$result_pre['prescription_list']= $this->db->get()->result();
		//echo "<pre>";print_r($result_pre); exit;
		return $result_pre;
	}
	
} 
?>