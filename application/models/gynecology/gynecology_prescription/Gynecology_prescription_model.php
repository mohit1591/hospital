<?php
require APPPATH . '/libraries/SendNotification.php';
class Gynecology_prescription_model extends CI_Model 
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

  public function get_gynecology_medicine_auto_vals($vals="")
    {   
      $vals = urldecode($vals);
      $response = array();
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');  
          $this->db->where('hms_medicine_entry.status','1'); 
          $this->db->order_by('hms_medicine_entry.medicine_name','ASC');
          $this->db->where('hms_medicine_entry.is_deleted',0);
          $this->db->where('hms_medicine_entry.medicine_name LIKE "%'.$vals.'%"');
          $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);
          //$this->db->where('hms_medicine_entry.type','2');  
          $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
          $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
          $query = $this->db->get('hms_medicine_entry');
     
          $result = $query->result(); 
        
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


     public function get_gynecology_dosage_vals($vals="")
  {
    $vals = urldecode($vals);
    $response = array();
    if(!empty($vals))
    {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('medicine_dosage','ASC');
          $this->db->where('is_deleted',0);
           $this->db->where('type',1);
          $this->db->where('medicine_dosage LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_opd_medicine_dosage');
          $result = $query->result(); 
       
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->medicine_dosage;
            }
          }
          return $response; 
    } 
  }


   public function get_gynecology_duration_vals($vals="")
    {
      $vals = urldecode($vals);
      $response = array();
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('medicine_dosage_duration','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('type',1);
          $this->db->where('medicine_dosage_duration  LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_opd_medicine_dosage_duration');
          //echo $this->db->last_query();die;
          $result = $query->result(); 
        
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->medicine_dosage_duration ;
            }
          }

          return $response; 
      } 
    }

      public function get_gynecology_frequency_vals($vals="")
        {
          $vals = urldecode($vals);
          $response = array();
          if(!empty($vals))
          {
                $users_data = $this->session->userdata('auth_users'); 
              $this->db->select('*');  
              $this->db->where('status','1'); 
              $this->db->order_by('medicine_dosage_frequency','ASC');
              $this->db->where('is_deleted',0);
              $this->db->where('type',1);
              $this->db->where('medicine_dosage_frequency LIKE "%'.$vals.'%"');
              $this->db->where('branch_id',$users_data['parent_id']);  
              $query = $this->db->get('hms_opd_medicine_dosage_frequency');
              $result = $query->result(); 
              
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


    public function get_gynecology_advice_vals($vals="")
    {
      $vals = urldecode($vals);
      $response = array();
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('advice','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('advice LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_gynecology_advice');
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

    public function get_gynecology_type_vals($vals="")
    {
      $vals = urldecode($vals);
      $response = array();
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('medicine_type','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('type',1);
          $this->db->where('medicine_type LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_opd_medicine_type');
          $result = $query->result(); 
          //echo $this->db->last_query();
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->medicine_type;
            }
          }
          return $response; 
      } 
    }


     public function gynecology_template_list()
    {
      $users_data= $this->session->userdata('auth_users');
      $this->db->select('*');  
      $this->db->where('status','1');
      $this->db->where('branch_id',$users_data['parent_id']);
      $this->db->where('is_deleted','0');   
      $query = $this->db->get('hms_gynecology_patient_template');
      //echo $this->db->last_query();die;
      $result = $query->result(); 
      return $result;
    }

/*public function get_by_prescription_id($prescription_id,$opd_booking_id)
  {
     $this->db->select("hms_gynecology_prescription.*,hms_gynecology_patient_template.template_name,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.adhar_no"); 
    $this->db->from('hms_gynecology_prescription'); 
    $this->db->where('hms_gynecology_prescription.id',$prescription_id);
    $this->db->join('hms_patient','hms_patient.id=hms_gynecology_prescription.patient_id','left');
    $this->db->join('hms_gynecology_patient_template','hms_gynecology_patient_template.id=hms_gynecology_prescription.template_id','left'); 
    $result_pre['prescription_list']= $this->db->get()->result();
    return $result_pre;

  }*/

  public function get_by_prescription_id($prescription_id,$opd_booking_id)
 {
    $this->db->select("hms_gynecology_prescription.*,hms_gynecology_patient_template.template_name,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.adhar_no,hms_opd_booking.booking_date,hms_opd_booking.booking_time");
   $this->db->from('hms_gynecology_prescription');
   $this->db->where('hms_gynecology_prescription.id',$prescription_id);
   $this->db->join('hms_patient','hms_patient.id=hms_gynecology_prescription.patient_id','left');
   
   $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_gynecology_prescription.booking_id','left');
   
   $this->db->join('hms_gynecology_patient_template','hms_gynecology_patient_template.id=hms_gynecology_prescription.template_id','left');
   $result_pre['prescription_list']= $this->db->get()->result();
   return $result_pre;

 }

    //oldddddddd

   
 

  public function get_detail_by_prescription_id($prescription_id)
  {
        $this->db->select("hms_opd_booking.opd_type,hms_gynecology_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_opd_booking.barcode_text,hms_opd_booking.barcode_type,hms_opd_booking.barcode_image"); 


        $this->db->from('hms_gynecology_prescription'); 
        $this->db->where('hms_gynecology_prescription.id',$prescription_id);
        $this->db->join('hms_patient','hms_patient.id=hms_gynecology_prescription.patient_id','left');
        $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_gynecology_prescription.booking_id','left'); 
        $result_pre['prescription_list']= $this->db->get()->result();
        //echo $this->db->last_query(); exit;


        $this->db->select('hms_gynecology_patient_history_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_patient_history_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_patient_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_patient_history_prescription');
        $result_pre['prescription_list']['patient_history']=$this->db->get()->result();
        //echo $this->db->last_query(); exit;

        $this->db->select('hms_gynecology_patient_family_history_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_patient_family_history_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_patient_family_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_patient_family_history_prescription');
        $result_pre['prescription_list']['family_history']=$this->db->get()->result();

        $this->db->select('hms_gynecology_patient_personal_history_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_patient_personal_history_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_patient_personal_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_patient_personal_history_prescription');
        $result_pre['prescription_list']['personal_history']=$this->db->get()->result();

        $this->db->select('hms_gynecology_patient_menstrual_history_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_patient_menstrual_history_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_patient_menstrual_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_patient_menstrual_history_prescription');
        $result_pre['prescription_list']['menstrual_history']=$this->db->get()->result();


         $this->db->select('hms_gynecology_patient_medical_history_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_patient_medical_history_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_patient_medical_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_patient_medical_history_prescription');
        $result_pre['prescription_list']['medical_history']=$this->db->get()->result();

        $this->db->select('hms_gynecology_patient_obestetric_history_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_patient_obestetric_history_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_patient_obestetric_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_patient_obestetric_history_prescription');
        $result_pre['prescription_list']['obestetric_history']=$this->db->get()->result();



        $this->db->select('hms_gynecology_patient_current_medication_history_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_patient_current_medication_history_prescription.medicine_template_id'); 
        $this->db->where('hms_gynecology_patient_current_medication_history_prescription.medicine_template_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_patient_current_medication_history_prescription');
        $result_pre['prescription_list']['prescription_history_data']=$this->db->get()->result();

        $this->db->select('hms_gynecology_prescription_medicine_booking.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_prescription_medicine_booking.medicine_template_id'); 
        $this->db->where('hms_gynecology_prescription_medicine_booking.medicine_template_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_prescription_medicine_booking');
        $result_pre['prescription_list']['prescription_data']=$this->db->get()->result();

        $this->db->select('hms_gynecology_patient_disease_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_patient_disease_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_patient_disease_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_patient_disease_prescription');
        $result_pre['prescription_list']['disease_history']=$this->db->get()->result();


        $this->db->select('hms_gynecology_complaint_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_complaint_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_complaint_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_complaint_prescription');
        $result_pre['prescription_list']['complaint']=$this->db->get()->result();

        $this->db->select('hms_gynecology_allergy_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_allergy_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_allergy_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_allergy_prescription');
        $result_pre['prescription_list']['allergy']=$this->db->get()->result();


        $this->db->select('hms_gynecology_general_examination_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_general_examination_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_general_examination_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_general_examination_prescription');
        $result_pre['prescription_list']['general_examination']=$this->db->get()->result();


        $this->db->select('hms_gynecology_clinical_examination_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_clinical_examination_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_clinical_examination_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_clinical_examination_prescription');
        $result_pre['prescription_list']['clinical_examination']=$this->db->get()->result();

        $this->db->select('hms_gynecology_investigation_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_investigation_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_investigation_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_investigation_prescription');
        $result_pre['prescription_list']['investigation_prescription']=$this->db->get()->result();

        $this->db->select('hms_gynecology_advice_prescription.*');
        $this->db->join('hms_gynecology_prescription','hms_gynecology_prescription.id = hms_gynecology_advice_prescription.gynec_prescription_id'); 
        $this->db->where('hms_gynecology_advice_prescription.gynec_prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_gynecology_advice_prescription');
        $result_pre['prescription_list']['advice_prescription']=$this->db->get()->result();
        

    return $result_pre;

  }



    public function get_by_ids($id)
  {
    $this->db->select("hms_gynecology_prescription.*,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time");  //,hms_opd_prescription_patient_test.*,hms_opd_prescription_patient_pres.*
    $this->db->from('hms_gynecology_prescription'); 
    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_gynecology_prescription.booking_id','left');
    $this->db->where('hms_gynecology_prescription.id',$id);
    $this->db->where('hms_gynecology_prescription.is_deleted','0'); 
    $query = $this->db->get(); 
    //echo $this
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
      $this->db->select('hms_branch_gynecology_prescription_setting.*');
      $this->db->where($data);
      if(!empty($branch_id))
      {
        $this->db->where('hms_branch_gynecology_prescription_setting.branch_id = "'.$branch_id.'"');
      }
      else
      {
        $this->db->where('hms_branch_gynecology_prescription_setting.branch_id = "'.$users_data['parent_id'].'"');
      }
       
      $this->db->from('hms_branch_gynecology_prescription_setting');
      $result=$this->db->get()->row();
      return $result;

    }

   

  

  public function save()
  {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
        //echo "<pre>"; print_r($post); exit;
        $patient_id = $post['patient_id'];
        $users_id = $this->get_user_id($patient_id);
        $patient_history_data = $this->session->userdata('patient_history_data');
        $patient_family_history_data = $this->session->userdata('patient_family_history_data');
        $patient_personal_history_data = $this->session->userdata('patient_personal_history_data'); 
        $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
        $patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
        $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
        $patient_disease_data = $this->session->userdata('patient_disease_data');  
        $patient_complaint_data = $this->session->userdata('patient_complaint_data');
        $patient_allergy_data = $this->session->userdata('patient_allergy_data');
        $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
        $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
        $patient_investigation_data = $this->session->userdata('patient_investigation_data');
        $patient_gpla_data = $this->session->userdata('patient_gpla_data');
        ///  print_r( $patient_gpla_data);die();
        $patient_advice_data = $this->session->userdata('patient_advice_data');

        $patient_right_ovary_data_arr = $this->session->userdata('patient_right_ovary_data');
        //echo "<pre>"; print_r($patient_right_ovary_data);die;
        $patient_left_ovary_data_arr = $this->session->userdata('patient_left_ovary_data');
        //echo "<pre>"; print_r($post);die;
        $patient_icsilab_data = $this->session->userdata('patient_icsilab_data');
        
        $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data');
        $fertility_data = $this->session->userdata('fertility_data');
         


         
        //echo "<pre>";print_r($patient_antenatal_care_data); exit;
        $next_appointment_date='NULL';
        $check_appointment='';
         
        if(!empty($post['check_appointment']))
        {
          $check_appointment=1;
          if(!empty($post['next_appointment_date']) && $post['next_appointment_date']!='0000-00-00 00:00:00' && date('Y-m-d',strtotime($post['next_appointment_date']))!='1970-01-01')
        {
            $next_appointment_date = date('Y-m-d H:i',strtotime($post['next_appointment_date']));
            
            
           
            	//next appointment 
            	$appointment_code = generate_unique_id(20,$user_data['parent_id']);
            	$appointment_data = array(
							
							'branch_id'=>$user_data['parent_id'],
							'parent_id'=>$post['booking_id'],
							'appointment_type'=>1, 
							'appointment_code'=>$appointment_code, 
							'appointment_date'=>date('Y-m-d',strtotime($post['next_appointment_date'])),
							'appointment_time'=>date('H:i:s', strtotime($post['next_appointment_date'])), 
							'type'=>1,
							
							'booking_date'=>date('Y-m-d',strtotime($post['next_appointment_date'])),
							'booking_status'=>0
					);


            	$this->db->set('patient_id',$post['patient_id']);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$appointment_data);    
	            $appointment_id = $this->db->insert_id(); 
	            //echo $this->db->last_query(); exit;

            
            
            
            
            
            
            
        }

        }
        else
        {
          $check_appointment=0;
          $next_appointment_date = date('Y-m-d H:i:s'); 
        }
         if(!empty($post['template_list']))
        {
          $template_id=$post['template_list'];

        }
        else
        {
          $template_id=0;
        }
        
        if(!empty($post['common_fertility_risk']))
        {
                $common_dfer= $post['common_fertility_risk'];
        }
        else
        {
           $common_dfer=0; 
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
               "weight"=>$post['weight'],
               "height"=>$post['height'],
              "bmi"=>$post['bmi'],
              "confirm_delivery_date"=>date('Y-m-d', strtotime($post['confirm_delivery_date'])),

              "lmps"=>date('Y-m-d',strtotime($post['lmps'])),
              "bp"=>$post['bp'],
              "edd"=>date('Y-m-d',strtotime($post['edd'])),
              "days"=>$post['days'],
              "pog"=>$post['pog'],
              "map"=>$post['map'],
              
              'template_id'=>$template_id,
              'next_appointment_date'=>!empty($post['next_appointment_date'])?$next_appointment_date:null,
              'check_appointment'=>$check_appointment,
              "status"=>1,
              'print_patient_history_flag'=>$post['print_patient_history_flag'],
            'print_complaints_flag'=>$post['print_complaints_flag'],
            'print_allergy_flag'=>$post['print_allergy_flag'],
            'print_disease_flag'=>$post['print_disease_flag'],
            'print_general_examination_flag'=>$post['print_general_examination_flag'],
            'print_clinical_examination_flag'=>$post['print_clinical_examination_flag'],
            'print_investigations_flag'=>$post['print_investigations_flag'],
            'print_medicine_flag'=>$post['print_medicine_flag'],
            'print_advice_flag'=>$post['print_advice_flag'],
            'print_next_app_flag'=>$post['print_next_app_flag'],
            'print_gpla_flag'=>$post['print_gpla_flag'],
            'print_follicular_flag'=>$post['print_follicular_flag'],
            'print_icsilab_flag'=>$post['print_icsilab_flag'],
            'print_fertility_flag'=>$post['print_fertility_flag'],
            'print_antenatal_flag'=>$post['print_antenatal_flag'],
            'common_fertility_risk'=>$common_fer,
            'date_time_new' => $post['date_time_new'],
            'next_reason' => $post['next_reason'],
            ); 
       
         
      if(!empty($post['data_id']) && $post['data_id']>0)
      {
         $data_id = $post['data_id'];
         $this->db->where('id',$post['data_id']);
         $this->db->update('hms_gynecology_prescription',$data);
      } 
      else
      {
       
        /*if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
          $this->db->where('patient_id',$post['patient_id']);
          $this->db->delete('hms_gynecology_prescription');
        }*/
        $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
        $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_gynecology_prescription',$data);  
        $data_id = $this->db->insert_id();
        //echo $this->db->last_query(); exit;
        //echo $data_id; die; 
      }

         //echo $this->db->last_query(); 
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
          
          'flag'=>3,
          'flag_id'=>$data_id,
          'next_appointment_date'=>$next_appointment_date,
          "status"=>1
          ); 

    $this->db->select('*');
    $this->db->where('branch_id',$user_data['parent_id']);
    $this->db->where('booking_id',$post['booking_id']);
    $this->db->where('flag_id',$data_id); //new code on 30 dec 2020
    $get_prescription = $this->db->get('hms_opd_prescription'); 
    $result_get_prescription = $get_prescription->result();
    
    if(!empty($result_get_prescription))
    {  
      $this->db->set('modified_by',$user_data['id']);
      $this->db->set('modified_date',date('Y-m-d H:i:s'));
      $this->db->where('patient_id',$post['patient_id']);
      $this->db->where('flag_id',$data_id);
      $this->db->where('flag',3);
      $this->db->update('hms_opd_prescription',$data_opd);
      //echo $this->db->last_query(); //exit;
    } 
    else
    {
      
       /*if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
          $this->db->where('patient_id',$post['patient_id']);
          $this->db->where('branch_id',$user_data['parent_id']);
          $this->db->delete('hms_opd_prescription');

        } */    
      $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
      $this->db->set('created_by',$user_data['id']);
      $this->db->set('created_date',date('Y-m-d H:i:s'));
      $this->db->insert('hms_opd_prescription',$data_opd); 
    }
    
    //echo $this->db->last_query(); exit;

    if(!empty($post['data_id']) && $post['data_id']>0)
    {
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
      //start fertility
      $this->db->where('gynec_prescription_id',$data_id);
      $this->db->delete('hms_gynecology_fertility_prescription');
      if(!empty($fertility_data))
      {
          //echo "<pre>"; print_r($fertility_data);//die;
          $fertility_ultrasound_images=0;
          $fertility_laparoscopy=0;
          $fertility_uploadhsg = 0;
          foreach($fertility_data as $fertility)
          {
              if(!empty($fertility['fertility_uploadhsg']))
              {
                  $fertility_uploadhsg++;
              }
              if(!empty($fertility['fertility_laparoscopy']))
              {
                  $fertility_laparoscopy++;
              }
              if(!empty($fertility['fertility_ultrasound_images']))
              {
                  $fertility_ultrasound_images++;
              }
              
             $patient_fertility_all = array(
                              "gynec_prescription_id "=>$data_id,
                              'branch_id'=>$user_data['parent_id'],
                              "booking_code"=>$post['booking_code'],
                              "patient_id"=>$post['patient_id'],
                              "booking_id"=>$post['booking_id'],
                              "fertility_co"=>$fertility['fertility_co'],
                              "fertility_uterine_factor"=>$fertility['fertility_uterine_factor'],
                              "fertility_tubal_factor"=>$fertility['fertility_tubal_factor'],
                              "fertility_uploadhsg"=>$fertility['fertility_uploadhsg'],
                              "fertility_laparoscopy"=>$fertility['fertility_laparoscopy'],
                              "fertility_risk"=>$fertility['fertility_risk'],
                              "fertility_decision"=>$fertility['fertility_decision'],
                              "fertility_ovarian_factor"=>$fertility['fertility_ovarian_factor'],
                              "fertility_ultrasound_images"=>$fertility['fertility_ultrasound_images'],
                              
                              "fertility_male_factor"=>$fertility['fertility_male_factor'],
                              "fertility_sperm_date"=>date('Y-m-d',strtotime($fertility['fertility_sperm_date'])),
                              "fertility_sperm_count"=>$fertility['fertility_sperm_count'],
                              "fertility_sperm_g3"=>$fertility['fertility_sperm_g3'],
                              "fertility_sperm_abnform"=>$fertility['fertility_sperm_abnform'],
                              "fertility_sperm_remarks"=>$fertility['fertility_sperm_remarks']
                    ); 
            $this->db->insert('hms_gynecology_fertility_prescription',$patient_fertility_all);  
            //echo $this->db->last_query(); exit;
          }
          
         // echo "two"; die;
          $fertility_ultrasound_images=0;
          $fertility_laparoscopy=0;
          $fertility_uploadhsg = 0;
          $message  = '';
        if(!empty($fertility_ultrasound_images))
        {
            $message .= 'You have'.$fertility_ultrasound_images.' ultrasound image uploaded.';
        }
        
        if(!empty($fertility_laparoscopy))
        {
            $message .=  $fertility_laparoscopy.' Laparoscopy Video.';
        }
        
        if(!empty($fertility_uploadhsg))
        {
            $message .= 'You have '.$fertility_uploadhsg.' Ultrasound Images.';
        }
                 
                  
        
        /* Push Notification */
            $sender_device_details= $this->get_device_detail($users_id);
           
            
             foreach($sender_device_details as $sender_device_detail)
              {
            
                $token=array($sender_device_detail->device_token);
                $order_id=''; 
                $sms_msg=urldecode($msg);
                $serverObject = new SendNotification(); 
            
                if($sender_device_detail->device_type=='ios'){
                  $jsonString = $serverObject->sendPushNotificationToFCMSever( $token, $sms_msg, $order_id );  
            
                }
                if($sender_device_detail->device_type=='android'){
                  $jsonString = $serverObject->sendPushNotificationToAndroidSever( $token, $sms_msg, $order_id );  
                }
            
            
              }
            /* Push Notification */
            
                 
                  
                 //$test_fertility_id = $this->db->insert_id(); 
               //echo $this->db->last_query(); //exit;
            }

            //die;
            //end fertility
    $this->db->where('gynec_prescription_id',$data_id);
    $this->db->delete('hms_gynecology_patient_history_prescription');            
      if(!empty($patient_history_data))
      { 

              foreach ($patient_history_data as $value) 
              {
                if (strpos($value['married_life_type'], 'Select') !== false) 
                  {
                      $value['married_life_type'] = "";
                  }
                  else
                  {
                    $value['married_life_type'] = $value['married_life_type'];
                  }
                  if (strpos($value['previous_delivery'], 'Select') !== false) 
                  {
                      $value['previous_delivery'] = "";
                  }
                  else
                  {
                    $value['previous_delivery'] = $value['previous_delivery'];
                  }
                  if (strpos($value['delivery_type'], 'Select') !== false) 
                  {
                      $value['delivery_type'] = "";
                  }
                  else
                  {
                    $value['delivery_type'] = $value['delivery_type'];
                  }
                  if(($value['married_life_unit']=='')||($value['married_life_unit']==0))
                  {
                   $value['married_life_unit']='';
                  }
                  else
                  {
                    $value['married_life_unit']=$value['married_life_unit'];

                  }
                  if(($value['marriage_no']=='')||($value['marriage_no']==0))
                  {
                   $value['marriage_no']='';
                  }
                  else
                  {
                    $value['marriage_no']=$value['marriage_no'];

                  }

                  $patient_history_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    "marriage_status"=>$value['marriage_status'],
                    'married_life_unit'=>$value['married_life_unit'],
                    'married_life_type'=>$value['married_life_type'],
                    'marriage_no'=>$value['marriage_no'],
                    'marriage_details'=>$value['marriage_details'],
                    'previous_delivery'=>$value['previous_delivery'],
                    'delivery_type'=>$value['delivery_type'],
                    'delivery_details'=>$value['delivery_details'],
                     'status'=>1,
                    );
                 
                 $this->db->insert('hms_gynecology_patient_history_prescription',$patient_history_data_all); 
                  $test_data_id = $this->db->insert_id(); 
              } 
            }

      
      $this->db->where('gynec_prescription_id',$data_id);
      $this->db->delete('hms_gynecology_patient_right_ovary_prescription');
      
        $patient_right_ovary_data = array(
        "gynec_prescription_id "=>$data_id,
        'branch_id'=>$user_data['parent_id'],
        "booking_code"=>$post['booking_code'],
        "patient_id"=>$post['patient_id'],
        "booking_id"=>$post['booking_id'],
        "types"=>1,
        "right_folli_date"=>date('Y-m-d',strtotime($post['right_folli_date'])),
        'right_folli_day'=>$post['right_folli_day'],
        'right_folli_protocol'=>$post['right_folli_protocol'],
        'right_folli_pfsh'=>$post['right_folli_pfsh'],
        'right_folli_hmg'=>$post['right_folli_hmg'],
        'right_folli_hp_hmg'=>$post['right_folli_hp_hmg'],
        'right_folli_agonist'=>$post['right_folli_agonist'],
        'right_folli_trigger'=>$post['right_folli_trigger'],  
        'right_folli_recfsh'=>$post['right_folli_recfsh'],
        'right_folli_antiagonist'=>$post['right_folli_antiagonist'],
        
        'endometriumothers'=>$post['endometriumothers'],
        'e2'=>$post['e2'],
        'p4'=>$post['p4'],
        'risk'=>$post['risk'],
        'others'=>$post['others'],
        ); 
        $this->db->insert('hms_gynecology_patient_right_ovary_prescription',$patient_right_ovary_data); 
       //echo $this->db->last_query();die;          
      if(!empty($patient_right_ovary_data_arr))
      { 
              foreach ($patient_right_ovary_data_arr as $value) 
              {
                  
                 if(!empty($value['right_follic_size']))
                 {
                     //echo "<pre>"; print_r($value);die;
                     if($value['right_follic_size']=='Select Size')
                     {
                        $size = '';
                     }
                     else
                     {
                         $size = $value['right_follic_size'];
                         $patient_right_ovary_data_all = array(
                        "gynec_prescription_id "=>$data_id,
                        'branch_id'=>$user_data['parent_id'],
                         "booking_code"=>$post['booking_code'],
                         "patient_id"=>$post['patient_id'],
                         "booking_id"=>$post['booking_id'],  
                         'right_follic_size'=>$size
                        );
                     
                     $this->db->insert('hms_gynecology_patient_right_ovary_prescription',$patient_right_ovary_data_all); 
                      $right_data_id = $this->db->insert_id(); 
                     }
                 }
                
              } 
            }
            
        if(!empty($patient_right_ovary_data_arr))
        { 
              foreach ($patient_right_ovary_data_arr as $value) 
              {
                 if(!empty($value['left_follic_size']))
                 {
                 //echo "<pre>"; print_r($value);die;
                 if($value['left_follic_size']=='Select Size')
                 {
                    $size = '';
                 }
                 else
                 {
                     $size = $value['left_follic_size'];
                     $patient_right_ovary_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],  
                     'left_follic_size'=>$size
                    );
                 
                 $this->db->insert('hms_gynecology_patient_right_ovary_prescription',$patient_right_ovary_data_all); 
                  $right_data_id = $this->db->insert_id(); 
                 }
                 
                 }

                
              } 
            }
            
            
            
            

            
            $this->db->where('gynec_prescription_id',$data_id);
            $this->db->delete('hms_gynecology_patient_left_ovary_prescription');
            
            $patient_left_ovary_data = array(
            "gynec_prescription_id "=>$data_id,
            'branch_id'=>$user_data['parent_id'],
            "booking_code"=>$post['booking_code'],
            "patient_id"=>$post['patient_id'],
            "booking_id"=>$post['booking_id'],
            "left_folli_date"=>date('Y-m-d',strtotime($post['left_folli_date'])),
            'left_folli_day'=>$post['left_folli_day'],
            'left_folli_protocol'=>$post['left_folli_protocol'],
            'left_folli_pfsh'=>$post['left_folli_pfsh'],
            'left_folli_hmg'=>$post['left_folli_hmg'],
            'left_folli_hp_hmg'=>$post['left_folli_hp_hmg'],
            'left_folli_agonist'=>$post['left_folli_agonist'],
            'left_folli_trigger'=>$post['left_folli_trigger'], 
            'endometriumothers'=>$post['endometriumothers'],
            'types'=>1,
            'e2'=>$post['e2'],
            'p4'=>$post['p4'],
            'risk'=>$post['risk'],
            'others'=>$post['others'],
            'left_folli_recfsh'=>$post['left_folli_recfsh'],
            'left_folli_antiagonist'=>$post['left_folli_antiagonist']
            ); 
            //echo "<pre>";
            $this->db->insert('hms_gynecology_patient_left_ovary_prescription',$patient_left_ovary_data);
            
            //echo $this->db->last_query(); exit;
            
            if(!empty($patient_left_ovary_data_arr))
            { 
              foreach ($patient_left_ovary_data_arr as $value) 
              {
                if($value['left_follic_size']=='Select Size')
                 {
                    $size = '';
                 }
                 else
                 {
                     $size = $value['left_follic_size'];
                     $patient_left_ovary_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                     "types"=>0,
                     'left_follic_size'=>$size
                    ); 
                 $this->db->insert('hms_gynecology_patient_left_ovary_prescription',$patient_left_ovary_data_all); 
                  $left_data_id = $this->db->insert_id(); 
                 }
                 
                
              } 
            }

            //icsilab

            
            //antenatal_care
            if(!empty($data_id))
            {
                $this->db->where('gynec_prescription_id',$data_id);
                $this->db->delete('hms_gynecology_patient_antenatal_care_prescription');    
            }
            
            if(!empty($patient_antenatal_care_data))
            { 
                $antenatal_ultrasound=0;
              foreach ($patient_antenatal_care_data as $value) 
              {
                  if(!empty($value['antenatal_ultrasound']))
                  {
                      $antenatal_ultrasound++;
                  }
                $patient_antenatal_care_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    
                  "antenatal_care_period"=>date('Y-m-d',strtotime($value['antenatal_care_period'])),
                    
                    "antenatal_first_date"=>$value['antenatal_first_date'],
                    "antenatal_ultrasound"=>$value['antenatal_ultrasound'],
                    "antenatal_expected_date"=>date('Y-m-d',strtotime($value['antenatal_expected_date'])),
                    
                  
                    "antenatal_remarks"=>$value['antenatal_remarks'],
                    );
                 
                 $this->db->insert('hms_gynecology_patient_antenatal_care_prescription',$patient_antenatal_care_data_all); 
                  $antenatal_care_data_id = $this->db->insert_id(); 
                 // echo $this->db->last_query();exit;
              } 
            }
    
    /* antenatal_care */
            //icsilab
            $this->db->where('gynec_prescription_id',$data_id);
            $this->db->delete('hms_gynecology_patient_icsilab_prescription');
            
            if(!empty($patient_icsilab_data))
            { 
              foreach ($patient_icsilab_data as $value) 
              {
                $patient_icsilab_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    "icsilab_date"=>date('Y-m-d',strtotime($value['icsilab_date'])),
                    "oocytes"=>$value['oocytes'],
                    "m2"=>$value['m2'],
                    "injected"=>$value['injected'],
                    "cleavge"=>$value['cleavge'],
                    "embryos_day3"=>$value['embryos_day3'],
                    "day5"=>$value['day5'],
                    "day_of_et"=>$value['day_of_et'],
                    "et"=>$value['et'],
                    "vit"=>$value['vit'],
                    "lah"=>$value['lah'],
                    "semen"=>$value['semen'],
                    "count"=>$value['count'],
                    "motility"=>$value['motility'],
                    "g3"=>$value['g3'],
                    "abn_form"=>$value['abn_form'],
                    "imsi"=>$value['imsi'],
                    "pregnancy"=>$value['pregnancy'],
                    "remarks"=>$value['remarks'],
                    );
                 
                 $this->db->insert('hms_gynecology_patient_icsilab_prescription',$patient_icsilab_data_all); 
                  $icsi_data_id = $this->db->insert_id(); 
              } 
            }
        

             //patient_family_history_data
             $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_patient_family_history_prescription');
                 
            if(!empty($patient_family_history_data))
              {
        
              foreach ($patient_family_history_data as $value) 
              {
                if (strpos($value['relation'], 'Select') !== false) 
                  {
                      $value['relation'] = "";
                  }
                  else
                  {
                    $value['relation'] = $value['relation'];
                  }
                  if (strpos($value['disease'], 'Select') !== false) 
                  {
                      $value['disease'] = "";
                  }
                  else
                  {
                    $value['disease'] = $value['disease'];
                  }
                  if (strpos($value['family_duration_type'], 'Select') !== false) 
                  {
                      $value['family_duration_type'] = "";
                  }
                  else
                  {
                    $value['family_duration_type'] = $value['family_duration_type'];
                  }

                  if(($value['family_duration_unit']=='')||($value['family_duration_unit']==0))
                  {
                   $value['family_duration_unit']='';
                  }
                  else
                  {
                    $value['family_duration_unit']=$value['family_duration_unit'];

                  }

                  if(($value['family_duration_type']=='')||($value['family_duration_type']==0))
                  {
                   $value['family_duration_type']='';
                  }
                  else
                  {
                    $value['family_duration_type']=$value['family_duration_type'];

                  }
                  


                  $patient_family_history_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    "relation "=>$value['relation'],
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    "disease"=>$value['disease'],
                    'family_description'=>$value['family_description'],                    
                    'family_duration_unit'=>$value['family_duration_unit'],
                    'family_duration_type'=>$value['family_duration_type'],
                     'status'=>1,
                    );
                  
                  $this->db->insert('hms_gynecology_patient_family_history_prescription',$patient_family_history_data_all); 
                  $test_data_id = $this->db->insert_id(); 
              } 
            }

         //patient_personal_history_data
         $this->db->where('gynec_prescription_id',$data_id);
         $this->db->delete('hms_gynecology_patient_personal_history_prescription');
          if(!empty($patient_personal_history_data))
                {
                 
                foreach ($patient_personal_history_data as $value) 
                {
                  if (strpos($value['br_discharge'], 'Select') !== false) 
                  {
                    $value['br_discharge'] = "";
                  }
                  else
                  {
                    $value['br_discharge'] = $value['br_discharge'];
                  }
                  if (strpos($value['side'], 'Select') !== false) 
                  {
                      $value['side'] = "";
                  }
                  else
                  {
                    $value['side'] = $value['side'];
                  }
                  if (strpos($value['hirsutism'], 'Select') !== false) 
                  {
                      $value['hirsutism'] = "";
                  }
                  else
                  {
                    $value['hirsutism'] = $value['hirsutism'];
                  }
                  if (strpos($value['white_discharge'], 'Select') !== false) 
                  {
                      $value['white_discharge'] = "";
                  }
                  else
                  {
                    $value['white_discharge'] = $value['white_discharge'];
                  }
                  if (strpos($value['type'], 'Select') !== false) 
                  {
                      $value['type'] = "";
                  }
                  else
                  {
                    $value['type'] = $value['type'];
                  }
                  if (strpos($value['dyspareunia'], 'Select') !== false) 
                  {
                      $value['dyspareunia'] = "";
                  }
                  else
                  {
                    $value['dyspareunia'] = $value['dyspareunia'];
                  }

                    $patient_personal_history_data = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                      "booking_id"=>$post['booking_id'],
                      'br_discharge'=>$value['br_discharge'],
                      "side"=>$value['side'],
                      'hirsutism'=>$value['hirsutism'],
                      'white_discharge'=>$value['white_discharge'],
                      'type'=>$value['type'],
                      'frequency_personal'=>$value['frequency_personal'],
                      'dyspareunia'=>$value['dyspareunia'],
                      'personal_details'=>$value['personal_details'],
                      'status'=>1,
                      );
                  
                   $this->db->insert('hms_gynecology_patient_personal_history_prescription',$patient_personal_history_data); 
                   //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }


              //patient_menstrual_history_data
               $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_patient_menstrual_history_prescription'); 
              if(!empty($patient_menstrual_history_data))
                {
                 
                foreach ($patient_menstrual_history_data as $value) 
                {
                  if (strpos($value['previous_cycle'], 'Select') !== false) 
                  {
                    $value['previous_cycle'] = "";
                  }
                  else
                  {
                    $value['previous_cycle'] = $value['previous_cycle'];
                  }
                  if (strpos($value['prev_cycle_type'], 'Select') !== false) 
                  {
                      $value['prev_cycle_type'] = "";
                  }
                  else
                  {
                    $value['prev_cycle_type'] = $value['prev_cycle_type'];
                  }
                  if (strpos($value['present_cycle'], 'Select') !== false) 
                  {
                      $value['present_cycle'] = "";
                  }
                  else
                  {
                    $value['present_cycle'] = $value['present_cycle'];
                  }
                  if (strpos($value['present_cycle_type'], 'Select') !== false) 
                  {
                      $value['present_cycle_type'] = "";
                  }
                  else
                  {
                    $value['present_cycle_type'] = $value['present_cycle_type'];
                  }
                  if (strpos($value['dysmenorrhea'], 'Select') !== false) 
                  {
                      $value['dysmenorrhea'] = "";
                  }
                  else
                  {
                    $value['dysmenorrhea'] = $value['dysmenorrhea'];
                  }
                  if (strpos($value['dysmenorrhea_type'], 'Select') !== false) 
                  {
                      $value['dysmenorrhea_type'] = "";
                  }
                  else
                  {
                    $value['dysmenorrhea_type'] = $value['dysmenorrhea_type'];
                  }
                  //print_r($previous_oral_habit_data);
                    $patient_menstrual_history_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "patient_id"=>$post['patient_id'],
                      "booking_id"=>$post['booking_id'],
                      'previous_cycle'=>$value['previous_cycle'],
                      "prev_cycle_type"=>$value['prev_cycle_type'],
                      'present_cycle'=>$value['present_cycle'],
                       'present_cycle_type'=>$value['present_cycle_type'],
                      "cycle_details"=>$value['cycle_details'],
                      'lmp_date'=>date('Y-m-d',strtotime($value['lmp_date'])),
                       'dysmenorrhea'=>$value['dysmenorrhea'],
                      "dysmenorrhea_type"=>$value['dysmenorrhea_type'],
                      'status'=>1
                      
                      );
                   
                   // echo"<pre>";print_r($patient_menstrual_history_data_all);
                    $this->db->insert('hms_gynecology_patient_menstrual_history_prescription',$patient_menstrual_history_data_all); 
                    $test_data_id = $this->db->insert_id(); 
                } 
              }


 //patient_medical_history_data
              $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_patient_medical_history_prescription');
              if(!empty($patient_medical_history_data))
                {
                  
                foreach ($patient_medical_history_data as $value) 
                {

                  if (strpos($value['tb'], 'Select') !== false) 
                  {
                    $value['tb'] = "";
                  }
                  else
                  {
                    $value['tb'] = $value['tb'];
                  }
                  if (strpos($value['dm'], 'Select') !== false) 
                  {
                      $value['dm'] = "";
                  }
                  else
                  {
                    $value['dm'] = $value['dm'];
                  }
                  if (strpos($value['ht'], 'Select') !== false) 
                  {
                      $value['ht'] = "";
                  }
                  else
                  {
                    $value['ht'] = $value['ht'];
                  }
                  
                    $patient_medical_history_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                      'tb'=>$value['tb'],
                      "tb_rx"=>$value['tb_rx'],
                      'dm'=>$value['dm'],
                      'dm_years'=>$value['dm_years'],
                      'dm_rx'=>$value['dm_rx'],
                      'ht'=>$value['ht'],
                      'medical_details'=>$value['medical_details'],
                      'medical_others'=>$value['medical_others'],
                      'status'=>1
                      
                      
                      );
                 
                   $this->db->insert('hms_gynecology_patient_medical_history_prescription',$patient_medical_history_data_all); 
                  
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

 //patient_obestetric_history_data
                $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_patient_obestetric_history_prescription');
               if(!empty($patient_obestetric_history_data))
                {
                 
                foreach ($patient_obestetric_history_data as $value) 
                {
                  //print_r($value_treatment);
                  //die;
                    $patient_obestetric_history_data = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                      'obestetric_g'=>$value['obestetric_g'],
                      "obestetric_p"=>$value['obestetric_p'],
                      'obestetric_l'=>$value['obestetric_l'],
                      'obestetric_e'=>$value['obestetric_e'],
                      
                      'obestetric_mtp'=>$value['obestetric_mtp'],
                      'obestetric_remarks'=>$value['obestetric_remarks'],
                      'status'=>1
                      
                      );
                  
                  $this->db->insert('hms_gynecology_patient_obestetric_history_prescription',$patient_obestetric_history_data); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }
                 // patient_Current_history_data
            $this->db->where('branch_id',$user_data['parent_id']);
                 $this->db->where('medicine_template_id',$data_id);
                $this->db->delete('hms_gynecology_patient_current_medication_history_prescription');     
          if(isset($post['prescription_patient']) && !empty($post['prescription_patient']))
          {

                  
         
        $new_array_pres=array_values($post['prescription_patient']);
        $total_prescription = count($new_array_pres);
         
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

         //echo"gggg";echo"<pre>";print_r($prescription_data);
             if(!empty($new_array_pres[$i]['medicine_name']))
             {
           $this->db->insert('hms_gynecology_patient_current_medication_history_prescription',$prescription_data); 
          }
           
        }


        

      }

      //patient_disease_data
               $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_patient_disease_prescription');
               if(!empty($patient_disease_data))
                {
                  
                foreach ($patient_disease_data as $value) 
                {
                  //print_r($value_treatment);
                  //die;
                  $patient_disease_id='';
                  $disease_value='';
                  $type='';
                  if($value['patient_disease_id']=='')
                  {
                    $patient_disease_id='';
                  }
                  else
                  {
                    $patient_disease_id=$value['patient_disease_id'];
                  }
                  if($value['disease_value']=='Select Disease')
                  {
                    $disease_value='';
                  }
                  else
                  {
                    $disease_value=$value['disease_value'];
                  }
                    
                if(strpos($value['patient_disease_type'], 'Select') !== false) 
                  {
                  
                    $type='';
                  }
                  else
                  {
                    $type=$value['patient_disease_type'];
                  }

                   if(($value['patient_disease_unit']=='')||($value['patient_disease_unit']==0))
                  {
                   $value['patient_disease_unit']='';
                  }
                  else
                  {
                    $value['patient_disease_unit']=$value['patient_disease_unit'];

                  }

                  

                    $patient_disease_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                       'patient_disease_id'=>$patient_disease_id,
                      'patient_disease_name'=>$disease_value,
                      "patient_disease_unit"=>$value['patient_disease_unit'],
                       "patient_disease_type"=>$type,
                      'disease_description'=>$value['disease_description'],
                      'status'=>1
                      
                      );
                   
                   $this->db->insert('hms_gynecology_patient_disease_prescription',$patient_disease_data_all); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

//patient_complaint_data
                $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_complaint_prescription');
               if(!empty($patient_complaint_data))
                {
                  
                foreach ($patient_complaint_data as $value) 
                {
                  //print_r($value_treatment);
                  //die;
                  $patient_complaint_id='';
                  $complaint_value='';
                  $type='';
                  if($value['patient_complaint_id']=='')
                  {
                    $patient_complaint_id='';
                  }
                  else
                  {
                    $patient_complaint_id=$value['patient_complaint_id'];
                  }
                  if($value['complaint_value']=='Select Complaint')
                  {
                    $complaint_value='';
                  }
                  else
                  {
                    $complaint_value=$value['complaint_value'];
                  }
                  if (strpos($value['patient_complaint_type'], 'Select') !== false) 
                  {
                    $type='';
                  }
                  else
                  {
                    $type=$value['patient_complaint_type'];
                  }
                   if(($value['patient_complaint_unit']=='')||($value['patient_complaint_unit']==0))
                  {
                   $value['patient_complaint_unit']='';
                  }
                  else
                  {
                    $value['patient_complaint_unit']=$value['patient_complaint_unit'];

                  }


                    $patient_complaint_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                       'patient_complaint_id'=>$patient_complaint_id,
                       'patient_complaint_name'=>$complaint_value,
                      'patient_complaint_type'=>$type,
                      "patient_complaint_unit"=>$value['patient_complaint_unit'],
                      'complaint_description'=>$value['complaint_description'],
                      'status'=>1
                      
                      );
                   
                  $this->db->insert('hms_gynecology_complaint_prescription',$patient_complaint_data_all); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

//patient_allergy_data
              $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_allergy_prescription');
              if(!empty($patient_allergy_data))
                {
                   
                foreach ($patient_allergy_data as $value) 
                {
                  //print_r($value_treatment);
                  //die;
                  $type='';
                  $patient_allergy_id='';
                  $allergy_value='';


                  if (strpos($value['patient_allergy_type'], 'Select') !== false) 
                  {
                    $type='';
                  }
                  else
                  {
                    $type=$value['patient_allergy_type'];
                  }

                  if($value['allergy_value']=='Select Allergy')
                  {
                    $allergy_value='';
                  }
                  else
                  {
                    $allergy_value=$value['allergy_value'];
                  }

                  if($value['patient_allergy_id']=='')
                  {
                    $patient_allergy_id='';
                  }
                  else
                  {
                    $patient_allergy_id=$value['patient_allergy_id'];
                  }
                    if(($value['patient_allergy_unit']=='')||($value['patient_allergy_unit']==0))
                  {
                   $value['patient_allergy_unit']='';
                  }
                  else
                  {
                    $value['patient_allergy_unit']=$value['patient_allergy_unit'];

                  }
                   


                    $patient_allergy_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                      'patient_allergy_id'=>$patient_allergy_id,
                       'patient_allergy_name'=>$allergy_value,
                      'patient_allergy_type'=>$type,
                      "patient_allergy_unit"=>$value['patient_allergy_unit'],
                      'allergy_description'=>$value['allergy_description'],
                      'status'=>1
                      
                      );
                   
                   $this->db->insert('hms_gynecology_allergy_prescription',$patient_allergy_data_all); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }


//patient_general_examination_data
               $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_general_examination_prescription');
               if(!empty($patient_general_examination_data))
                {
                  
                foreach ($patient_general_examination_data as $value) 
                {
                  //print_r($value_treatment);
                  //die;
                  $type='';
                  $patient_general_examination_id='';
                  $general_examination_value='';


                  

                  if($value['general_examination_value']=='Select Exam')
                  {
                    $general_examination_value='';
                  }
                  else
                  {
                    $general_examination_value=$value['general_examination_value'];
                  }

                  if($value['patient_general_examination_id']=='')
                  {
                    $patient_general_examination_id='';
                  }
                  else
                  {
                    $patient_general_examination_id=$value['patient_general_examination_id'];
                  }



                    $patient_general_examination_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                       'patient_general_examination_id'=>$patient_general_examination_id,
                       'patient_general_examination_name'=>$general_examination_value,
                      'general_examination_description'=>$value['general_examination_description'],
                      'status'=>1
                      
                      );
                    
                  $this->db->insert('hms_gynecology_general_examination_prescription',$patient_general_examination_data_all); 
                    //echo $this->db->last_query();die;
                    $test_data_id = $this->db->insert_id(); 
                } 
              }


//patient_clinical_examination_data
              $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_clinical_examination_prescription');
              if(!empty($patient_clinical_examination_data))
                {
                  
                foreach ($patient_clinical_examination_data as $value) 
                {
                  $type='';
                  $patient_clinical_examination_id='';
                  $clinical_examination_value='';


                 

                  if($value['clinical_examination_value']=='Select Exam')
                  {
                    $clinical_examination_value='';
                  }
                  else
                  {
                    $clinical_examination_value=$value['clinical_examination_value'];
                  }

                  if($value['patient_clinical_examination_id']=='')
                  {
                    $patient_clinical_examination_id='';
                  }
                  else
                  {
                    $patient_clinical_examination_id=$value['patient_clinical_examination_id'];
                  }
                    $patient_clinical_examination_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                       'patient_clinical_examination_id'=>$patient_clinical_examination_id,
                       'patient_clinical_examination_name'=>$clinical_examination_value,                     
                      'clinical_examination_description'=>$value['clinical_examination_description'],
                      'status'=>1
                      
                      );
                    
                 $this->db->insert('hms_gynecology_clinical_examination_prescription',$patient_clinical_examination_data_all); 
                   
                    $test_data_id = $this->db->insert_id(); 
                } 
              }


//patient_investigation_data
               $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_investigation_prescription');
if(!empty($patient_investigation_data))
                {
                 
                foreach ($patient_investigation_data as $value) 
                {
                 
                 
                  $patient_investigation_id='';
                  $investigation_value='';
                  if($value['investigation_value']=='Select Investigation')
                  {
                    $investigation_value='';
                  }
                  else
                  {
                    $investigation_value=$value['investigation_value'];
                  }

                  if($value['patient_investigation_id']=='')
                  {
                    $patient_investigation_id='';
                  }
                  else
                  {
                    $patient_investigation_id=$value['patient_investigation_id'];
                  }
               
                    if(!empty($value['investigation_date']) && $value['investigation_date']!='0000-00-00' && date('Y-m-d',strtotime($value['investigation_date']))!='1970-01-01')
                    {
                    $investigation_date = date('Y-m-d',strtotime($value['investigation_date']));
                    }
                    else
                    {
                    $investigation_date='';
                    }
                    $patient_investigation_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                       'patient_investigation_id'=>$patient_investigation_id,
                      'patient_investigation_name'=>$investigation_value,
                      'std_value'=>$value['std_value'],
                      'observed_value'=>$value['observed_value'],
                      'investigation_date'=>$investigation_date,
                      'status'=>1
                      
                      );
                    
                $this->db->insert('hms_gynecology_investigation_prescription',$patient_investigation_data_all); 
                //echo $this->db->last_query();die;
                   
                    $test_data_id = $this->db->insert_id(); 
                } 
              }






//patient_gpla_data
//echo "<pre>"; print_r($patient_gpla_data); exit;
              if(!empty($patient_gpla_data))
              {
                $this->db->where('gynec_prescription_id',$data_id);
                $this->db->delete('hms_gynecology_gpla_prescription');

                foreach ($patient_gpla_data as $value) 
                {
                 
               
                    
                    $patient_gpla_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                      'dog_value'=>$value['dog_value'],
                      'mode_value'=>$value['mode_value'],
                      'monthyear_value'=>$value['monthyear_value'],
                      'status'=>1
                      
                      );
                    
                $this->db->insert('hms_gynecology_gpla_prescription',$patient_gpla_data_all); 
              
                   
                    $test_data_id = $this->db->insert_id(); 
                } 
              }














              //advice
              $this->db->where('gynec_prescription_id',$data_id);
                 $this->db->delete('hms_gynecology_advice_prescription');
if(!empty($patient_advice_data))
                {
                  
                foreach ($patient_advice_data as $value) 
                {

                  $patient_advice_id='';
                  $advice_value='';
                  if($value['advice_value']=='Select Advice')
                  {
                    $advice_value='';
                  }
                  else
                  {
                    $advice_value=$value['advice_value'];
                  }

                  if($value['patient_advice_id']=='')
                  {
                    $patient_advice_id='';
                  }
                  else
                  {
                    $patient_advice_id=$value['patient_advice_id'];
                  }
                  
                    $patient_advice_data_all = array(
                      "gynec_prescription_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      "patient_id"=>$post['patient_id'],
                       "booking_id"=>$post['booking_id'],
                       'patient_advice_id'=>$patient_advice_id,
                      'patient_advice_name'=>$advice_value,
                      'status'=>1
                      
                      );
                   
                  $this->db->insert('hms_gynecology_advice_prescription',$patient_advice_data_all); 
                   
                    $test_data_id = $this->db->insert_id(); 
                } 
              }

                // medicine
          if(isset($post['prescription']) && !empty($post['prescription']))
         {
             $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('medicine_template_id',$data_id);
            $this->db->delete('hms_gynecology_prescription_medicine_booking');
         
        $new_array_pres=array_values($post['prescription']);
        $total_prescription = count($new_array_pres);
         
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
             
             //medicine id from master 
             
             if(!empty($new_array_pres[$i]['medicine_name']))
             {
                 	
						$this->db->select('hms_medicine_entry.*');  
						//$this->db->from('hms_opd_medicine');
						$this->db->where('hms_medicine_entry.medicine_name',$new_array_pres[$i]['medicine_name']);  
						$this->db->where('hms_medicine_entry.is_deleted=0'); 
						$this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_medicine_entry');
						$num = $query->num_rows();
						//echo $this->db->last_query();
						//echo $num; exit;
						if($num>0)
						{
							$company_data = $query->result_array();
							if(!empty($company_data))
						    {
							    $medicine_id = $company_data[0]['id'];
						    }
						}
						else
						{		
								$unit_id ='';
								$company_id ='';
								//medicine company end
								$salt ='';
								if(!empty($post['salt'][$i]))
								{
									$salt = $post['salt'][$i];
								}
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_name'=>$new_array_pres[$i]['medicine_name'],
												'type'=>0,
												'salt'=>0,
												'manuf_company'=>0,
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_medicine_entry',$data);
								$medicine_id = $this->db->insert_id(); 
								//echo $this->db->last_query(); exit;
						}

					
             }
             
             //end of medicine id
             
             $prescription_data = array(
            "medicine_template_id"=>$data_id,
            "branch_id"=>$user_data['parent_id'],
            'medicine_id'=>$medicine_id,
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

      
             if(!empty($new_array_pres[$i]['medicine_name']))
             {
             // echo"<pre>";print_r($prescription_data);
              //die;
          $this->db->insert('hms_gynecology_prescription_medicine_booking',$prescription_data);
           }
        }
      }
      //end shalini
    }

    else
    {
      // for add
      if(!empty($patient_history_data))
      {
        foreach ($patient_history_data as $value) 
        {
          if (strpos($value['married_life_type'], 'Select') !== false) 
            {
                $value['married_life_type'] = "";
            }
            else
            {
              $value['married_life_type'] = $value['married_life_type'];
            }
            if (strpos($value['previous_delivery'], 'Select') !== false) 
            {
                $value['previous_delivery'] = "";
            }
            else
            {
              $value['previous_delivery'] = $value['previous_delivery'];
            }
            if (strpos($value['delivery_type'], 'Select') !== false) 
            {
                $value['delivery_type'] = "";
            }
            else
            {
              $value['delivery_type'] = $value['delivery_type'];
            }

             if(($value['married_life_unit']=='')||($value['married_life_unit']==0))
            {
             $value['married_life_unit']='';
            }
            else
            {
              $value['married_life_unit']=$value['married_life_unit'];

            }
            if(($value['marriage_no']=='')||($value['marriage_no']==0))
            {
             $value['marriage_no']='';
            }
            else
            {
              $value['marriage_no']=$value['marriage_no'];

            }

            $patient_history_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
               "booking_code"=>$post['booking_code'],
               "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
              "marriage_status"=>$value['marriage_status'],
              'married_life_unit'=>$value['married_life_unit'],
              'married_life_type'=>$value['married_life_type'],
              'marriage_no'=>$value['marriage_no'],
              'marriage_details'=>$value['marriage_details'],
              'previous_delivery'=>$value['previous_delivery'],
              'delivery_type'=>$value['delivery_type'],
              'delivery_details'=>$value['delivery_details'],
               'status'=>1,
              );
            //echo"<pre>";print_r($patient_history_data_all);
           $this->db->insert('hms_gynecology_patient_history_prescription',$patient_history_data_all); 
            $test_data_id = $this->db->insert_id(); 
        } 
      }

      //patient_family_history_data
      if(!empty($patient_family_history_data))
      {
        foreach ($patient_family_history_data as $value) 
        {
          if (strpos($value['relation'], 'Select') !== false) 
            {
                $value['relation'] = "";
            }
            else
            {
              $value['relation'] = $value['relation'];
            }
            if (strpos($value['disease'], 'Select') !== false) 
            {
                $value['disease'] = "";
            }
            else
            {
              $value['disease'] = $value['disease'];
            }
            if (strpos($value['family_duration_type'], 'Select') !== false) 
            {
                $value['family_duration_type'] = "";
            }
            else
            {
              $value['family_duration_type'] = $value['family_duration_type'];
            }
             if(($value['family_duration_unit']=='')||($value['family_duration_unit']==0))
            {
             $value['family_duration_unit']='';
            }
            else
            {
              $value['family_duration_unit']=$value['family_duration_unit'];

            }

            if(($value['family_duration_type']=='')||($value['family_duration_type']==0))
            {
             $value['family_duration_type']='';
            }
            else
            {
              $value['family_duration_type']=$value['family_duration_type'];

            }


            $patient_family_history_data_all = array(
              "gynec_prescription_id "=>$data_id,
              "relation "=>$value['relation'],
              'branch_id'=>$user_data['parent_id'],
               "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
              "disease"=>$value['disease'],
              'family_description'=>$value['family_description'],                    
              'family_duration_unit'=>$value['family_duration_unit'],
              'family_duration_type'=>$value['family_duration_type'],
               'status'=>1,
              );
             //echo"<pre>";print_r($patient_family_history_data_all);
            $this->db->insert('hms_gynecology_patient_family_history_prescription',$patient_family_history_data_all); 
            $test_data_id = $this->db->insert_id(); 
        } 
      }

      //patient_personal_history_data
      if(!empty($patient_personal_history_data))
      {
         
        foreach ($patient_personal_history_data as $value) 
        {
          if (strpos($value['br_discharge'], 'Select') !== false) 
          {
            $value['br_discharge'] = "";
          }
          else
          {
            $value['br_discharge'] = $value['br_discharge'];
          }
          if (strpos($value['side'], 'Select') !== false) 
          {
              $value['side'] = "";
          }
          else
          {
            $value['side'] = $value['side'];
          }
          if (strpos($value['hirsutism'], 'Select') !== false) 
          {
              $value['hirsutism'] = "";
          }
          else
          {
            $value['hirsutism'] = $value['hirsutism'];
          }
          if (strpos($value['white_discharge'], 'Select') !== false) 
          {
              $value['white_discharge'] = "";
          }
          else
          {
            $value['white_discharge'] = $value['white_discharge'];
          }
          if (strpos($value['type'], 'Select') !== false) 
          {
              $value['type'] = "";
          }
          else
          {
            $value['type'] = $value['type'];
          }
          if (strpos($value['dyspareunia'], 'Select') !== false) 
          {
              $value['dyspareunia'] = "";
          }
          else
          {
            $value['dyspareunia'] = $value['dyspareunia'];
          }

            $patient_personal_history_data = array(
            "gynec_prescription_id "=>$data_id,
            'branch_id'=>$user_data['parent_id'],
             "patient_id"=>$post['patient_id'],
              "booking_id"=>$post['booking_id'],
              'br_discharge'=>$value['br_discharge'],
              "side"=>$value['side'],
              'hirsutism'=>$value['hirsutism'],
              'white_discharge'=>$value['white_discharge'],
              'type'=>$value['type'],
              'frequency_personal'=>$value['frequency_personal'],
              'dyspareunia'=>$value['dyspareunia'],
              'personal_details'=>$value['personal_details'],
              'status'=>1,
              );
         //  echo"<pre>";print_r($patient_personal_history_data);
           $this->db->insert('hms_gynecology_patient_personal_history_prescription',$patient_personal_history_data); 
            $test_data_id = $this->db->insert_id(); 
        } 
      }


      //patient_menstrual_history_data

      if(!empty($patient_menstrual_history_data))
      {
        foreach ($patient_menstrual_history_data as $value) 
        {
          if (strpos($value['previous_cycle'], 'Select') !== false) 
          {
            $value['previous_cycle'] = "";
          }
          else
          {
            $value['previous_cycle'] = $value['previous_cycle'];
          }
          if (strpos($value['prev_cycle_type'], 'Select') !== false) 
          {
              $value['prev_cycle_type'] = "";
          }
          else
          {
            $value['prev_cycle_type'] = $value['prev_cycle_type'];
          }
          if (strpos($value['present_cycle'], 'Select') !== false) 
          {
              $value['present_cycle'] = "";
          }
          else
          {
            $value['present_cycle'] = $value['present_cycle'];
          }
          if (strpos($value['present_cycle_type'], 'Select') !== false) 
          {
              $value['present_cycle_type'] = "";
          }
          else
          {
            $value['present_cycle_type'] = $value['present_cycle_type'];
          }
          if (strpos($value['dysmenorrhea'], 'Select') !== false) 
          {
              $value['dysmenorrhea'] = "";
          }
          else
          {
            $value['dysmenorrhea'] = $value['dysmenorrhea'];
          }
          if (strpos($value['dysmenorrhea_type'], 'Select') !== false) 
          {
              $value['dysmenorrhea_type'] = "";
          }
          else
          {
            $value['dysmenorrhea_type'] = $value['dysmenorrhea_type'];
          }
          //print_r($previous_oral_habit_data);
            $patient_menstrual_history_data_all = array(
              "gynec_prescription_id "=>$data_id,
            'branch_id'=>$user_data['parent_id'],
             "patient_id"=>$post['patient_id'],
              "booking_id"=>$post['booking_id'],
              'previous_cycle'=>$value['previous_cycle'],
              "prev_cycle_type"=>$value['prev_cycle_type'],
              'present_cycle'=>$value['present_cycle'],
               'present_cycle_type'=>$value['present_cycle_type'],
              "cycle_details"=>$value['cycle_details'],
              'lmp_date'=>date('Y-m-d',strtotime($value['lmp_date'])),
               'dysmenorrhea'=>$value['dysmenorrhea'],
              "dysmenorrhea_type"=>$value['dysmenorrhea_type'],
              'status'=>1
              
              );
           
           //echo"<pre>";print_r($patient_menstrual_history_data_all);
            $this->db->insert('hms_gynecology_patient_menstrual_history_prescription',$patient_menstrual_history_data_all); 
            $test_data_id = $this->db->insert_id(); 
        } 
      }


      //patient_medical_history_data
      if(!empty($patient_medical_history_data))
      {
        foreach ($patient_medical_history_data as $value) 
        {

          if (strpos($value['tb'], 'Select') !== false) 
          {
            $value['tb'] = "";
          }
          else
          {
            $value['tb'] = $value['tb'];
          }
          if (strpos($value['dm'], 'Select') !== false) 
          {
              $value['dm'] = "";
          }
          else
          {
            $value['dm'] = $value['dm'];
          }
          if (strpos($value['ht'], 'Select') !== false) 
          {
              $value['ht'] = "";
          }
          else
          {
            $value['ht'] = $value['ht'];
          }
          
            $patient_medical_history_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
              'tb'=>$value['tb'],
              "tb_rx"=>$value['tb_rx'],
              'dm'=>$value['dm'],
              'dm_years'=>$value['dm_years'],
              'dm_rx'=>$value['dm_rx'],
              'ht'=>$value['ht'],
              'medical_details'=>$value['medical_details'],
              'medical_others'=>$value['medical_others'],
              'status'=>1
              
              
              );
          //echo"<pre>";print_r($patient_medical_history_data_all);
           $this->db->insert('hms_gynecology_patient_medical_history_prescription',$patient_medical_history_data_all); 
          
            $test_data_id = $this->db->insert_id(); 
        } 
      }

      //patient_obestetric_history_data
      if(!empty($patient_obestetric_history_data))
      {
        foreach ($patient_obestetric_history_data as $value) 
        {
          //print_r($value_treatment);
          //die;
            $patient_obestetric_history_data = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
              'obestetric_g'=>$value['obestetric_g'],
              "obestetric_p"=>$value['obestetric_p'],
              'obestetric_l'=>$value['obestetric_l'],
             'obestetric_e'=>$value['obestetric_e'],
                      
                      'obestetric_mtp'=>$value['obestetric_mtp'],
                      'obestetric_remarks'=>$value['obestetric_remarks'],
              'status'=>1
              
              );
           //echo"<pre>";print_r($patient_obestetric_history_data);
          $this->db->insert('hms_gynecology_patient_obestetric_history_prescription',$patient_obestetric_history_data); 
            //echo $this->db->last_query();die;
            $test_data_id = $this->db->insert_id(); 
        } 
      }
      /*echo "hii";
      print_r($post['prescription_patient']);die;*/

      // patient_Current_history_data
      if(isset($post['prescription_patient']) && !empty($post['prescription_patient']))
      {
        $new_array_pres=array_values($post['prescription_patient']);
        $total_prescription = count($new_array_pres);
         
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
          if(!empty($new_array_pres[$i]['medicine_name']))
          {
            $this->db->insert('hms_gynecology_patient_current_medication_history_prescription',$prescription_data); 
          }
           
        }
      }


    ///end patient


//patient_disease_data
       if(!empty($patient_disease_data))
        {
        foreach ($patient_disease_data as $value) 
        {
          //print_r($value_treatment);
          //die;
          $patient_disease_id='';
          $disease_value='';
          $type='';
          if($value['patient_disease_id']=='')
          {
            $patient_disease_id='';
          }
          else
          {
            $patient_disease_id=$value['patient_disease_id'];
          }
          if($value['disease_value']=='Select Disease')
          {
            $disease_value='';
          }
          else
          {
            $disease_value=$value['disease_value'];
          }

            if (strpos($value['patient_disease_type'], 'Select') !== false) 
          {
            $type='';
          }
          else
          {
            $type=$value['patient_disease_type'];
          }

            if(($value['patient_disease_unit']=='')||($value['patient_disease_unit']==0))
          {
           $value['patient_disease_unit']='';
          }
          else
          {
            $value['patient_disease_unit']=$value['patient_disease_unit'];

          }

            $patient_disease_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
               'patient_disease_id'=>$patient_disease_id,
              'patient_disease_name'=>$disease_value,
              "patient_disease_unit"=>$value['patient_disease_unit'],
               "patient_disease_type"=>$type,
              'disease_description'=>$value['disease_description'],
              'status'=>1
              
              );
           // echo"<pre>";print_r($patient_disease_data_all);
           $this->db->insert('hms_gynecology_patient_disease_prescription',$patient_disease_data_all); 
            //echo $this->db->last_query();die;
            $test_data_id = $this->db->insert_id(); 
        } 
      }

//patient_complaint_data
       if(!empty($patient_complaint_data))
        {
        foreach ($patient_complaint_data as $value) 
        {
          //print_r($value_treatment);
          //die;
          $patient_complaint_id='';
          $complaint_value='';
          $type='';
          if($value['patient_complaint_id']=='')
          {
            $patient_complaint_id='';
          }
          else
          {
            $patient_complaint_id=$value['patient_complaint_id'];
          }
          if($value['complaint_value']=='Select Complaint')
          {
            $complaint_value='';
          }
          else
          {
            $complaint_value=$value['complaint_value'];
          }
          if (strpos($value['patient_complaint_type'], 'Select') !== false) 
          {
            $type='';
          }
          else
          {
            $type=$value['patient_complaint_type'];
          }

            if(($value['patient_complaint_unit']=='')||($value['patient_complaint_unit']==0))
          {
           $value['patient_complaint_unit']='';
          }
          else
          {
            $value['patient_complaint_unit']=$value['patient_complaint_unit'];

          }


            $patient_complaint_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
               'patient_complaint_id'=>$patient_complaint_id,
               'patient_complaint_name'=>$complaint_value,
              'patient_complaint_type'=>$type,
              "patient_complaint_unit"=>$value['patient_complaint_unit'],
              'complaint_description'=>$value['complaint_description'],
              'status'=>1
              
              );
           //echo"<pre>";print_r($patient_complaint_data_all);
          $this->db->insert('hms_gynecology_complaint_prescription',$patient_complaint_data_all); 
            //echo $this->db->last_query();die;
            $test_data_id = $this->db->insert_id(); 
        } 
      }

//patient_allergy_data
      if(!empty($patient_allergy_data))
        {
        foreach ($patient_allergy_data as $value) 
        {
          //print_r($value_treatment);
          //die;
          $type='';
          $patient_allergy_id='';
          $allergy_value='';


          if (strpos($value['patient_allergy_type'], 'Select') !== false) 
          {
            $type='';
          }
          else
          {
            $type=$value['patient_allergy_type'];
          }

          if($value['allergy_value']=='Select Allergy')
          {
            $allergy_value='';
          }
          else
          {
            $allergy_value=$value['allergy_value'];
          }

          if($value['patient_allergy_id']=='')
          {
            $patient_allergy_id='';
          }
          else
          {
            $patient_allergy_id=$value['patient_allergy_id'];
          }

            if(($value['patient_allergy_unit']=='')||($value['patient_allergy_unit']==0))
          {
           $value['patient_allergy_unit']='';
          }
          else
          {
            $value['patient_allergy_unit']=$value['patient_allergy_unit'];

          }



            $patient_allergy_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
              'patient_allergy_id'=>$patient_allergy_id,
               'patient_allergy_name'=>$allergy_value,
              'patient_allergy_type'=>$type,
              "patient_allergy_unit"=>$value['patient_allergy_unit'],
              'allergy_description'=>$value['allergy_description'],
              'status'=>1
              
              );
           //echo"<pre>";print_r($patient_allergy_data_all);
           $this->db->insert('hms_gynecology_allergy_prescription',$patient_allergy_data_all); 
            //echo $this->db->last_query();die;
            $test_data_id = $this->db->insert_id(); 
        } 
      }


//patient_general_examination_data
       if(!empty($patient_general_examination_data))
        {
        foreach ($patient_general_examination_data as $value) 
        {
          //print_r($value_treatment);
          //die;
          $type='';
          $patient_general_examination_id='';
          $general_examination_value='';



          if($value['general_examination_value']=='Select Exam')
          {
            $general_examination_value='';
          }
          else
          {
            $general_examination_value=$value['general_examination_value'];
          }

          if($value['patient_general_examination_id']=='')
          {
            $patient_general_examination_id='';
          }
          else
          {
            $patient_general_examination_id=$value['patient_general_examination_id'];
          }



            $patient_general_examination_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
               'patient_general_examination_id'=>$patient_general_examination_id,
               'patient_general_examination_name'=>$general_examination_value,
             
              'general_examination_description'=>$value['general_examination_description'],
              'status'=>1
              
              );
            //echo"<pre>";print_r($patient_general_examination_data_all);
          $this->db->insert('hms_gynecology_general_examination_prescription',$patient_general_examination_data_all); 
            //echo $this->db->last_query();die;
            $test_data_id = $this->db->insert_id(); 
        } 
      }


//patient_clinical_examination_data
      if(!empty($patient_clinical_examination_data))
        {
        foreach ($patient_clinical_examination_data as $value) 
        {
          $type='';
          $patient_clinical_examination_id='';
          $clinical_examination_value='';


        

          if($value['clinical_examination_value']=='Select Exam')
          {
            $clinical_examination_value='';
          }
          else
          {
            $clinical_examination_value=$value['clinical_examination_value'];
          }

          if($value['patient_clinical_examination_id']=='')
          {
            $patient_clinical_examination_id='';
          }
          else
          {
            $patient_clinical_examination_id=$value['patient_clinical_examination_id'];
          }
            $patient_clinical_examination_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
               'patient_clinical_examination_id'=>$patient_clinical_examination_id,
               'patient_clinical_examination_name'=>$clinical_examination_value,
              'clinical_examination_description'=>$value['clinical_examination_description'],
              'status'=>1
              
              );
             // echo"<pre>";print_r($patient_clinical_examination_data_all);
         $this->db->insert('hms_gynecology_clinical_examination_prescription',$patient_clinical_examination_data_all); 
           
            $test_data_id = $this->db->insert_id(); 
        } 
      }


//patient_investigation_data
if(!empty($patient_investigation_data))
        {
        foreach ($patient_investigation_data as $value) 
        {
         
         
          $patient_investigation_id='';
          $investigation_value='';
          if($value['investigation_value']=='Select Investigation')
          {
            $investigation_value='';
          }
          else
          {
            $investigation_value=$value['investigation_value'];
          }

          if($value['patient_investigation_id']=='')
          {
            $patient_investigation_id='';
          }
          else
          {
            $patient_investigation_id=$value['patient_investigation_id'];
          }
         
            if(!empty($value['investigation_date']) && $value['investigation_date']!='0000-00-00' && date('Y-m-d',strtotime($value['investigation_date']))!='1970-01-01')
            {
            $investigation_date = date('Y-m-d',strtotime($value['investigation_date']));
            }
            else
            {
            $investigation_date='';
            }
            $patient_investigation_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
               'patient_investigation_id'=>$patient_investigation_id,
              'patient_investigation_name'=>$investigation_value,
              'std_value'=>$value['std_value'],
              'observed_value'=>$value['observed_value'],
              'investigation_date'=>$investigation_date,
              'status'=>1
              
              );
           //echo"<pre>";print_r($patient_investigation_data_all); 
        $this->db->insert('hms_gynecology_investigation_prescription',$patient_investigation_data_all); 
           
            $test_data_id = $this->db->insert_id(); 
        } 
      }


     // Folliculler scanning start ///
     /*
     $this->db->where('gynec_prescription_id',$data_id);
      $this->db->delete('hms_gynecology_patient_right_ovary_prescription');
      
        $patient_right_ovary_data = array(
        "gynec_prescription_id "=>$data_id,
        'branch_id'=>$user_data['parent_id'],
        "booking_code"=>$post['booking_code'],
        "patient_id"=>$post['patient_id'],
        "booking_id"=>$post['booking_id'],
        "types"=>1,
        "right_folli_date"=>date('Y-m-d',strtotime($post['right_folli_date'])),
        'right_folli_day'=>$post['right_folli_day'],
        'right_folli_protocol'=>$post['right_folli_protocol'],
        'right_folli_pfsh'=>$post['right_folli_pfsh'],
        'right_folli_hmg'=>$post['right_folli_hmg'],
        'right_folli_hp_hmg'=>$post['right_folli_hp_hmg'],
        'right_folli_agonist'=>$post['right_folli_agonist'],
        'right_folli_trigger'=>$post['right_folli_trigger'],  
        'right_folli_recfsh'=>$post['right_folli_recfsh'],
        'right_folli_antiagonist'=>$post['right_folli_antiagonist'],
        ); 
        $this->db->insert('hms_gynecology_patient_right_ovary_prescription',$patient_right_ovary_data); 
      // echo $this->db->last_query();die;          
      if(!empty($patient_right_ovary_data_arr))
      { 
              foreach ($patient_right_ovary_data_arr as $value) 
              {
                 //echo "<pre>"; print_r($value);die;
                 if($value['right_follic_size']=='Select Size')
                 {
                    $size = '';
                 }
                 else
                 {
                     $size = $value['right_follic_size'];
                     $patient_right_ovary_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],  
                     'right_follic_size'=>$size
                    );
                 
                 $this->db->insert('hms_gynecology_patient_right_ovary_prescription',$patient_right_ovary_data_all); 
                  $right_data_id = $this->db->insert_id(); 
                 }

                
              } 
            }

            
            $this->db->where('gynec_prescription_id',$data_id);
            $this->db->delete('hms_gynecology_patient_left_ovary_prescription');
            
            $patient_left_ovary_data = array(
            "gynec_prescription_id "=>$data_id,
            'branch_id'=>$user_data['parent_id'],
            "booking_code"=>$post['booking_code'],
            "patient_id"=>$post['patient_id'],
            "booking_id"=>$post['booking_id'],
            "left_folli_date"=>date('Y-m-d',strtotime($post['left_folli_date'])),
            'left_folli_day'=>$post['left_folli_day'],
            'left_folli_protocol'=>$post['left_folli_protocol'],
            'left_folli_pfsh'=>$post['left_folli_pfsh'],
            'left_folli_hmg'=>$post['left_folli_hmg'],
            'left_folli_hp_hmg'=>$post['left_folli_hp_hmg'],
            'left_folli_agonist'=>$post['left_folli_agonist'],
            'left_folli_trigger'=>$post['left_folli_trigger'], 
            'endometriumothers'=>$post['endometriumothers'],
            'types'=>1,
            'e2'=>$post['e2'],
            'p4'=>$post['p4'],
            'risk'=>$post['risk'],
            'others'=>$post['others'],
            'left_folli_recfsh'=>$post['left_folli_recfsh'],
            'left_folli_antiagonist'=>$post['left_folli_antiagonist']
            ); 
            $this->db->insert('hms_gynecology_patient_left_ovary_prescription',$patient_left_ovary_data);
            
            if(!empty($patient_left_ovary_data_arr))
            { 
              foreach ($patient_left_ovary_data_arr as $value) 
              {
                if($value['left_follic_size']=='Select Size')
                 {
                    $size = '';
                 }
                 else
                 {
                     $size = $value['left_follic_size'];
                     $patient_left_ovary_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                     "types"=>0,
                     'left_follic_size'=>$size
                    ); 
                 $this->db->insert('hms_gynecology_patient_left_ovary_prescription',$patient_left_ovary_data_all); 
                  $left_data_id = $this->db->insert_id(); 
                 }
                 
                
              } 
            }*/
            
            
            if(!empty($post['right_folli_day']) || !empty($post['right_folli_protocol']) || !empty($post['right_folli_pfsh']))
      {
      /*$this->db->where('gynec_prescription_id',$data_id);
      $this->db->delete('hms_gynecology_patient_right_ovary_prescription');*/
      
        $patient_right_ovary_data = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                    "booking_code"=>$post['booking_code'],
                    "patient_id"=>$post['patient_id'],
                    "booking_id"=>$post['booking_id'],
                    "types"=>1,
                    "right_folli_date"=>date('Y-m-d',strtotime($post['right_folli_date'])),
                    'right_folli_day'=>$post['right_folli_day'],
                    'right_folli_protocol'=>$post['right_folli_protocol'],
                    'right_folli_pfsh'=>$post['right_folli_pfsh'],
                    'right_folli_hmg'=>$post['right_folli_hmg'],
                    'right_folli_hp_hmg'=>$post['right_folli_hp_hmg'],
                    'right_folli_agonist'=>$post['right_folli_agonist'],
                    'right_folli_trigger'=>$post['right_folli_trigger'],  
                    'right_folli_recfsh'=>$post['right_folli_recfsh'],
                    'right_folli_antiagonist'=>$post['right_folli_antiagonist'],
                    
                    'endometriumothers'=>$post['endometriumothers'],
                    'e2'=>$post['e2'],
                    'p4'=>$post['p4'],
                    'risk'=>$post['risk'],
                    'others'=>$post['others'],
                    ); 
        $this->db->insert('hms_gynecology_patient_right_ovary_prescription',$patient_right_ovary_data); 
      }
      // echo $this->db->last_query();die;          
      if(!empty($patient_right_ovary_data_arr))
      { 
              foreach ($patient_right_ovary_data_arr as $value) 
              {
                  
                 if(!empty($value['right_follic_size']))
                 {
                     //echo "<pre>"; print_r($value);die;
                     if($value['right_follic_size']=='Select Size')
                     {
                        $size = '';
                     }
                     else
                     {
                         $size = $value['right_follic_size'];
                         $patient_right_ovary_data_all = array(
                        "gynec_prescription_id "=>$data_id,
                        'branch_id'=>$user_data['parent_id'],
                         "booking_code"=>$post['booking_code'],
                         "patient_id"=>$post['patient_id'],
                         "booking_id"=>$post['booking_id'],  
                         'right_follic_size'=>$size
                        );
                     
                     $this->db->insert('hms_gynecology_patient_right_ovary_prescription',$patient_right_ovary_data_all); 
                      $right_data_id = $this->db->insert_id(); 
                     }
                 }
                
              } 
            }
            
        if(!empty($patient_right_ovary_data_arr))
        { 
              foreach ($patient_right_ovary_data_arr as $value) 
              {
                 if(!empty($value['left_follic_size']))
                 {
                 //echo "<pre>"; print_r($value);die;
                 if($value['left_follic_size']=='Select Size')
                 {
                    $size = '';
                 }
                 else
                 {
                     $size = $value['left_follic_size'];
                     $patient_right_ovary_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],  
                     'left_follic_size'=>$size
                    );
                 
                 $this->db->insert('hms_gynecology_patient_right_ovary_prescription',$patient_right_ovary_data_all); 
                  $right_data_id = $this->db->insert_id(); 
                 }
                 
                 }

                
              } 
            }
            
            
            
            

            
            /*$this->db->where('gynec_prescription_id',$data_id);
            $this->db->delete('hms_gynecology_patient_left_ovary_prescription');*/
            
            $patient_left_ovary_data = array(
            "gynec_prescription_id "=>$data_id,
            'branch_id'=>$user_data['parent_id'],
            "booking_code"=>$post['booking_code'],
            "patient_id"=>$post['patient_id'],
            "booking_id"=>$post['booking_id'],
            "left_folli_date"=>date('Y-m-d',strtotime($post['left_folli_date'])),
            'left_folli_day'=>$post['left_folli_day'],
            'left_folli_protocol'=>$post['left_folli_protocol'],
            'left_folli_pfsh'=>$post['left_folli_pfsh'],
            'left_folli_hmg'=>$post['left_folli_hmg'],
            'left_folli_hp_hmg'=>$post['left_folli_hp_hmg'],
            'left_folli_agonist'=>$post['left_folli_agonist'],
            'left_folli_trigger'=>$post['left_folli_trigger'], 
            'endometriumothers'=>$post['endometriumothers'],
            'types'=>1,
            'e2'=>$post['e2'],
            'p4'=>$post['p4'],
            'risk'=>$post['risk'],
            'others'=>$post['others'],
            'left_folli_recfsh'=>$post['left_folli_recfsh'],
            'left_folli_antiagonist'=>$post['left_folli_antiagonist']
            ); 
            //echo "<pre>";
            $this->db->insert('hms_gynecology_patient_left_ovary_prescription',$patient_left_ovary_data);
            
            //echo $this->db->last_query(); exit;
            
            if(!empty($patient_left_ovary_data_arr))
            { 
              foreach ($patient_left_ovary_data_arr as $value) 
              {
                if($value['left_follic_size']=='Select Size')
                 {
                    $size = '';
                 }
                 else
                 {
                     $size = $value['left_follic_size'];
                     $patient_left_ovary_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                     "types"=>0,
                     'left_follic_size'=>$size
                    ); 
                 $this->db->insert('hms_gynecology_patient_left_ovary_prescription',$patient_left_ovary_data_all); 
                  $left_data_id = $this->db->insert_id(); 
                 }
                 
                
              } 
            }

            //icsilab
     // Folliculler scanning end //

     // Folliculler scanning end //


//fertility


      if(!empty($fertility_data))
      {
          //echo "<pre>"; print_r($fertility_data);//die;
          $fertility_ultrasound_images=0;
          $fertility_laparoscopy=0;
          $fertility_uploadhsg = 0;
          foreach($fertility_data as $fertility)
          {
              if(!empty($fertility['fertility_uploadhsg']))
              {
                  $fertility_uploadhsg++;
              }
              if(!empty($fertility['fertility_laparoscopy']))
              {
                  $fertility_laparoscopy++;
              }
              if(!empty($fertility['fertility_ultrasound_images']))
              {
                  $fertility_ultrasound_images++;
              }
              
             $patient_fertility_all = array(
                              "gynec_prescription_id "=>$data_id,
                              'branch_id'=>$user_data['parent_id'],
                              "booking_code"=>$post['booking_code'],
                              "patient_id"=>$post['patient_id'],
                              "booking_id"=>$post['booking_id'],
                              "fertility_co"=>$fertility['fertility_co'],
                              "fertility_uterine_factor"=>$fertility['fertility_uterine_factor'],
                              "fertility_tubal_factor"=>$fertility['fertility_tubal_factor'],
                              "fertility_uploadhsg"=>$fertility['fertility_uploadhsg'],
                              "fertility_laparoscopy"=>$fertility['fertility_laparoscopy'],
                              "fertility_risk"=>$fertility['fertility_risk'],
                              "fertility_decision"=>$fertility['fertility_decision'],
                              "fertility_ovarian_factor"=>$fertility['fertility_ovarian_factor'],
                              "fertility_ultrasound_images"=>$fertility['fertility_ultrasound_images'],
                              
                              "fertility_male_factor"=>$fertility['fertility_male_factor'],
                              "fertility_sperm_date"=>date('Y-m-d',strtotime($fertility['fertility_sperm_date'])),
                              "fertility_sperm_count"=>$fertility['fertility_sperm_count'],
                              "fertility_sperm_g3"=>$fertility['fertility_sperm_g3'],
                              "fertility_sperm_abnform"=>$fertility['fertility_sperm_abnform'],
                              "fertility_sperm_remarks"=>$fertility['fertility_sperm_remarks']
                    ); 
            $this->db->insert('hms_gynecology_fertility_prescription',$patient_fertility_all);  
            //echo $this->db->last_query(); exit;
          }
      }

      //advice
if(!empty($patient_advice_data))
        {
        foreach ($patient_advice_data as $value) 
        {

          $patient_advice_id='';
          $advice_value='';
          if($value['advice_value']=='Select Advice')
          {
            $advice_value='';
          }
          else
          {
            $advice_value=$value['advice_value'];
          }

          if($value['patient_advice_id']=='')
          {
            $patient_advice_id='';
          }
          else
          {
            $patient_advice_id=$value['patient_advice_id'];
          }
          
            $patient_advice_data_all = array(
              "gynec_prescription_id "=>$data_id,
              'branch_id'=>$user_data['parent_id'],
              "patient_id"=>$post['patient_id'],
               "booking_id"=>$post['booking_id'],
               'patient_advice_id'=>$patient_advice_id,
              'patient_advice_name'=>$advice_value,
              'status'=>1
              
              );
           //echo"<pre>";print_r($patient_advice_data_all);
          $this->db->insert('hms_gynecology_advice_prescription',$patient_advice_data_all); 
           
            $test_data_id = $this->db->insert_id(); 
        } 
      }

        // medicine
  if(isset($post['prescription']) && !empty($post['prescription']))
 {
 
$new_array_pres=array_values($post['prescription']);
$total_prescription = count($new_array_pres);
 
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
     
     //medicine id 
     
     if(!empty($new_array_pres[$i]['medicine_name']))
             {
                 	
						$this->db->select('hms_medicine_entry.*');  
						//$this->db->from('hms_opd_medicine');
						$this->db->where('hms_medicine_entry.medicine_name',$new_array_pres[$i]['medicine_name']);  
						$this->db->where('hms_medicine_entry.is_deleted=0'); 
						$this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_medicine_entry');
						$num = $query->num_rows();
						//echo $this->db->last_query();
						//echo $num; exit;
						if($num>0)
						{
							$company_data = $query->result_array();
							if(!empty($company_data))
						    {
							    $medicine_id = $company_data[0]['id'];
						    }
						}
						else
						{		
								$unit_id ='';
								$company_id ='';
								//medicine company end
								$salt ='';
								if(!empty($post['salt'][$i]))
								{
									$salt = $post['salt'][$i];
								}
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_name'=>$new_array_pres[$i]['medicine_name'],
												'type'=>0,
												'salt'=>0,
												'manuf_company'=>0,
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_medicine_entry',$data);
								$medicine_id = $this->db->insert_id(); 
								//echo $this->db->last_query(); exit;
						}

					
             }
     
     //end of medicine id
     
     $prescription_data = array(
    "medicine_template_id"=>$data_id,
    "branch_id"=>$user_data['parent_id'],
    'medicine_id'=>$medicine_id,
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

//echo"<pre>";print_r($prescription_data);
//die;
     if(!empty($new_array_pres[$i]['medicine_name']))
     {
  $this->db->insert('hms_gynecology_prescription_medicine_booking',$prescription_data);
   }
}
}     
        
        
        
        if(!empty($patient_antenatal_care_data))
            { 
                $antenatal_ultrasound=0;
              foreach ($patient_antenatal_care_data as $value) 
              {
                  if(!empty($value['antenatal_ultrasound']))
                  {
                      $antenatal_ultrasound++;
                  }
                $patient_antenatal_care_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    
                  "antenatal_care_period"=>date('Y-m-d',strtotime($value['antenatal_care_period'])),
                    
                    "antenatal_first_date"=>$value['antenatal_first_date'],
                    "antenatal_ultrasound"=>$value['antenatal_ultrasound'],
                    "antenatal_expected_date"=>date('Y-m-d',strtotime($value['antenatal_expected_date'])),
                    
                  
                    "antenatal_remarks"=>$value['antenatal_remarks'],
                    );
                 
                 $this->db->insert('hms_gynecology_patient_antenatal_care_prescription',$patient_antenatal_care_data_all); 
                  $antenatal_care_data_id = $this->db->insert_id(); 
                 // echo $this->db->last_query();exit;
              } 
            }
    
    /* antenatal_care */  
       
       
       //icsi lab add
       
       if(!empty($patient_icsilab_data))
            { 
              foreach ($patient_icsilab_data as $value) 
              {
                $patient_icsilab_data_all = array(
                    "gynec_prescription_id "=>$data_id,
                    'branch_id'=>$user_data['parent_id'],
                     "booking_code"=>$post['booking_code'],
                     "patient_id"=>$post['patient_id'],
                     "booking_id"=>$post['booking_id'],
                    "icsilab_date"=>date('Y-m-d',strtotime($value['icsilab_date'])),
                    "oocytes"=>$value['oocytes'],
                    "m2"=>$value['m2'],
                    "injected"=>$value['injected'],
                    "cleavge"=>$value['cleavge'],
                    "embryos_day3"=>$value['embryos_day3'],
                    "day5"=>$value['day5'],
                    "day_of_et"=>$value['day_of_et'],
                    "et"=>$value['et'],
                    "vit"=>$value['vit'],
                    "lah"=>$value['lah'],
                    "semen"=>$value['semen'],
                    "count"=>$value['count'],
                    "motility"=>$value['motility'],
                    "g3"=>$value['g3'],
                    "abn_form"=>$value['abn_form'],
                    "imsi"=>$value['imsi'],
                    "pregnancy"=>$value['pregnancy'],
                    "remarks"=>$value['remarks'],
                    );
                 
                 $this->db->insert('hms_gynecology_patient_icsilab_prescription',$patient_icsilab_data_all); 
                  $icsi_data_id = $this->db->insert_id(); 
              } 
            }
       ///icsi lab end
        
        
    }


  if(!empty($post['data']))
  {  
        $this->db->where('booking_id',$data_id);
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('type',3);
        $this->db->delete('hms_branch_vitals');
        $current_date = date('Y-m-d H:i:s');
        foreach($post['data'] as $key=>$val)
        {
            $data = array(
                             "branch_id"=>$user_data['parent_id'],
                             "type"=>3,
                             "booking_id"=>$data_id,
                             "vitals_id"=>$key,
                             "vitals_value"=>$val['name'],
                             
                            );
            
            $this->db->insert('hms_branch_vitals',$data);
            $id = $this->db->insert_id();
        } 
  }

}

public function get_user_id($patient_id='')
{
 
    $this->db->select('hms_users.id');
    $this->db->from('hms_users'); 
    $this->db->where('hms_users.parent_id',$patient_id); 
    $this->db->where('hms_users.users_role',4);
    $query = $this->db->get(); 
    $result = $query->result();
    $id = $result[0]->id;
    return $id;
}

public function get_last_risk($gynec_prescription_id='',$booking_id='')
{
 
    $this->db->select('hms_gynecology_fertility_prescription.fertility_risk');
    $this->db->from('hms_gynecology_fertility_prescription'); 
    $this->db->where('hms_gynecology_fertility_prescription.gynec_prescription_id',$gynec_prescription_id); 
    $this->db->where('hms_gynecology_fertility_prescription.booking_id',$booking_id); 
    $this->db->order_by('hms_gynecology_fertility_prescription.id','DESC');
    $query = $this->db->get(); 
    $result = $query->result();
    if(!empty($result))
    {
        $fertility_risk = $result[0]->fertility_risk;
        
    }
    else
    {
        $fertility_risk ='';
    }
    return $fertility_risk;
}




   public function delete_gynic($id="")
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
      $this->db->update('hms_gynecology_prescription');

      
      } 
    }






    public function get_gynecology_patient_history_list($prescription_id="",$opd_booking_id)
 {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_gynecology_patient_history_prescription.*'); 
      $this->db->where('hms_gynecology_patient_history_prescription.branch_id',$users_data['parent_id']);  
      $this->db->where('hms_gynecology_patient_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
      $this->db->where('hms_gynecology_patient_history_prescription.booking_id = "'.$opd_booking_id.'"'); 
    
    $query = $this->db->get('hms_gynecology_patient_history_prescription');
  // echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $patient_history_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_history)
      {
             $patient_history_list[$i]['marriage_status'] = $patient_history->marriage_status;
               $patient_history_list[$i]['married_life_unit'] = $patient_history->married_life_unit;
               $patient_history_list[$i]['married_life_type'] = $patient_history->married_life_type;
               $patient_history_list[$i]['marriage_no'] = $patient_history->marriage_no;
               $patient_history_list[$i]['marriage_details'] = $patient_history->marriage_details;
               $patient_history_list[$i]['previous_delivery'] = $patient_history->previous_delivery;
               $patient_history_list[$i]['delivery_type'] = $patient_history->delivery_type;
                $patient_history_list[$i]['delivery_details'] = $patient_history->delivery_details;
      $i++;
      }
    }
  
    return $patient_history_list; 
 }


       public function get_gynecology_family_history_list($prescription_id="",$opd_booking_id)
       {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('hms_gynecology_patient_family_history_prescription.*'); 
            $this->db->where('hms_gynecology_patient_family_history_prescription.branch_id',$users_data['parent_id']);  
            $this->db->where('hms_gynecology_patient_family_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
            $this->db->where('hms_gynecology_patient_family_history_prescription.booking_id = "'.$opd_booking_id.'"'); 
            
            $query = $this->db->get('hms_gynecology_patient_family_history_prescription');

            $result = $query->result(); 
            
            $family_history_list = [];
            if(!empty($result))
            { 
              $i=0;
              foreach($result as $family_history)
              {
                  $family_history_list[$i]['relation'] = $family_history->relation;
                  $family_history_list[$i]['disease'] = $family_history->disease;
                  $family_history_list[$i]['family_description'] = $family_history->family_description;
                  $family_history_list[$i]['family_duration_unit'] = $family_history->family_duration_unit;
                  $family_history_list[$i]['family_duration_type  '] = $family_history->family_duration_type ;
                  $i++;
              }
            }
          
            return $family_history_list; 
       }

      public function get_gynecology_personal_history_list($prescription_id="",$opd_booking_id)
     {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('hms_gynecology_patient_personal_history_prescription.*'); 
          $this->db->where('hms_gynecology_patient_personal_history_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_patient_personal_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_patient_personal_history_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_patient_personal_history_prescription');
        
          $result = $query->result(); 
          
          $family_personal_list = [];
          if(!empty($result))
          { 
            $i=0;
            foreach($result as $personal_history)
            {
              $family_personal_list[$i]['br_discharge'] = $personal_history->br_discharge;
              $family_personal_list[$i]['side'] = $personal_history->side;
              $family_personal_list[$i]['hirsutism'] = $personal_history->hirsutism;
              $family_personal_list[$i]['white_discharge'] = $personal_history->white_discharge;
              $family_personal_list[$i]['type'] = $personal_history->type ;
              $family_personal_list[$i]['frequency_personal'] = $personal_history->frequency_personal ;
              $family_personal_list[$i]['dyspareunia'] = $personal_history->dyspareunia ;
              $family_personal_list[$i]['personal_details'] = $personal_history->personal_details ;

              $i++;
            }
          }
        
          return $family_personal_list; 
     }


      public function get_gynecology_menstrual_history_list($prescription_id="",$opd_booking_id)
     {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('hms_gynecology_patient_menstrual_history_prescription.*'); 
          $this->db->where('hms_gynecology_patient_menstrual_history_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_patient_menstrual_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_patient_menstrual_history_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_patient_menstrual_history_prescription');
        
          $result = $query->result(); 
          
          $menstrual_personal_list = [];
          if(!empty($result))
          { 
            $i=0;
            foreach($result as $menstrual_history)
            {
              $menstrual_personal_list[$i]['previous_cycle'] = $menstrual_history->previous_cycle;
              $menstrual_personal_list[$i]['prev_cycle_type'] = $menstrual_history->prev_cycle_type;
              $menstrual_personal_list[$i]['present_cycle'] = $menstrual_history->present_cycle;
              $menstrual_personal_list[$i]['present_cycle_type'] = $menstrual_history->present_cycle_type;
              $menstrual_personal_list[$i]['cycle_details'] = $menstrual_history->cycle_details ;
              $menstrual_personal_list[$i]['lmp_date'] = $menstrual_history->lmp_date ;
              $menstrual_personal_list[$i]['dysmenorrhea'] = $menstrual_history->dysmenorrhea ;
              $menstrual_personal_list[$i]['dysmenorrhea_type'] = $menstrual_history->dysmenorrhea_type ;
              $i++;
            }
          }
        
          return $menstrual_personal_list; 
     }


      public function get_gynecology_medical_history_list($prescription_id="",$opd_booking_id)
     {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('hms_gynecology_patient_medical_history_prescription.*'); 
          $this->db->where('hms_gynecology_patient_medical_history_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_patient_medical_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_patient_medical_history_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_patient_medical_history_prescription');
        
          $result = $query->result(); 
          
          $medical_list = [];
          if(!empty($result))
          { 
            $i=0;
            foreach($result as $medical_history)
            {
              $medical_list[$i]['tb'] = $medical_history->tb;
              $medical_list[$i]['tb_rx'] = $medical_history->tb_rx;
              $medical_list[$i]['dm'] = $medical_history->dm;
              $medical_list[$i]['dm_years'] = $medical_history->dm_years;
              $medical_list[$i]['dm_rx'] = $medical_history->dm_rx ;
              $medical_list[$i]['ht'] = $medical_history->ht ;
              $medical_list[$i]['medical_details'] = $medical_history->medical_details ;
              $medical_list[$i]['medical_others'] = $medical_history->medical_others ;
              $i++;
            }
          }
        
          return $medical_list; 
     }

      public function get_gynecology_obestetric_history_list($prescription_id="",$opd_booking_id)
     {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('hms_gynecology_patient_obestetric_history_prescription.*'); 
          $this->db->where('hms_gynecology_patient_obestetric_history_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_patient_obestetric_history_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_patient_obestetric_history_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_patient_obestetric_history_prescription');
        
          $result = $query->result(); 
          
          $obestetric_list = [];
          if(!empty($result))
          { 
            $i=0;
            foreach($result as $obestetric_history)
            {
              $obestetric_list[$i]['obestetric_g'] = $obestetric_history->obestetric_g;
              $obestetric_list[$i]['obestetric_p'] = $obestetric_history->obestetric_p;
              $obestetric_list[$i]['obestetric_l'] = $obestetric_history->obestetric_l;
               $obestetric_list[$i]['obestetric_e'] = $obestetric_history->obestetric_e;
              $obestetric_list[$i]['obestetric_mtp'] = $obestetric_history->obestetric_mtp;
              $obestetric_list[$i]['obestetric_remarks'] = $obestetric_history->obestetric_remarks;
              $i++;
            }
          }
        
          return $obestetric_list; 
     }
     
     

       public function get_gynecology_disease_list($prescription_id="",$opd_booking_id)
     {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('hms_gynecology_patient_disease_prescription.*'); 
          $this->db->where('hms_gynecology_patient_disease_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_patient_disease_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_patient_disease_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_patient_disease_prescription');
        
          $result = $query->result(); 
          
          $disease_list = [];
          if(!empty($result))
          { 
            $i=0;
            foreach($result as $disease_history)
            {
              $disease_list[$i]['patient_disease_id'] = $disease_history->patient_disease_id;
              $disease_list[$i]['patient_disease_name'] = $disease_history->patient_disease_name;
              $disease_list[$i]['patient_disease_unit'] = $disease_history->patient_disease_unit;
              $disease_list[$i]['patient_disease_type'] = $disease_history->patient_disease_type;
              
              $disease_list[$i]['disease_description'] = $disease_history->disease_description;
              $i++;
            }
          }
        
          return $disease_list; 
     }


     public function get_patient_advice_list($prescription_id="",$opd_booking_id)
  {
    $users_data = $this->session->userdata('auth_users'); 
       $this->db->select('hms_gynecology_advice_prescription.*'); 
          $this->db->where('hms_gynecology_advice_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_advice_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_advice_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_advice_prescription');
    $result = $query->result(); 
    $patient_advice_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_advice)
      {
        $patient_advice_list[$i]['patient_advice_id'] = $patient_advice->patient_advice_id;
        $patient_advice_list[$i]['advice_value'] = $patient_advice->patient_advice_name;
        $i++;
      }
    }
    return $patient_advice_list; 
  }

  public function get_patient_investigation_list($prescription_id="",$opd_booking_id)
  {
       $users_data = $this->session->userdata('auth_users'); 
         $this->db->select('hms_gynecology_investigation_prescription.*'); 
          $this->db->where('hms_gynecology_investigation_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_investigation_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_investigation_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_investigation_prescription');
    $result = $query->result(); 
    $patient_investigation_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_investigation)
      {
        $patient_investigation_list[$i]['patient_investigation_id'] = $patient_investigation->patient_investigation_id;
        $patient_investigation_list[$i]['investigation_value'] = $patient_investigation->patient_investigation_name;
        $patient_investigation_list[$i]['std_value'] = $patient_investigation->std_value;
        $patient_investigation_list[$i]['observed_value'] = $patient_investigation->observed_value;
        $patient_investigation_list[$i]['investigation_date'] = $patient_investigation->investigation_date;
        $i++;
      }
    }
    return $patient_investigation_list; 
  }

  public function get_patient_clinical_examination_list($prescription_id="",$opd_booking_id)
  {
        $users_data = $this->session->userdata('auth_users'); 
         $this->db->select('hms_gynecology_clinical_examination_prescription.*'); 
          $this->db->where('hms_gynecology_clinical_examination_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_clinical_examination_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_clinical_examination_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_clinical_examination_prescription');
    $result = $query->result(); 
    $patient_clinical_examination_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_clinical_examination)
      {
        $patient_clinical_examination_list[$i]['clinical_examination_value'] = $patient_clinical_examination->patient_clinical_examination_name;
        $patient_clinical_examination_list[$i]['patient_clinical_examination_id'] = $patient_clinical_examination->patient_clinical_examination_id;
        $patient_clinical_examination_list[$i]['clinical_examination_description'] = $patient_clinical_examination->clinical_examination_description;

        $i++;
      }
    }
    return $patient_clinical_examination_list; 
  }

  public function get_patient_general_examination_list($prescription_id="",$opd_booking_id)
  {
        $users_data = $this->session->userdata('auth_users'); 
         $this->db->select('hms_gynecology_general_examination_prescription.*'); 
          $this->db->where('hms_gynecology_general_examination_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_general_examination_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_general_examination_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_general_examination_prescription');
    $result = $query->result(); 
    $patient_general_examination_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_general_examination)
      {
        $patient_general_examination_list[$i]['general_examination_value'] = $patient_general_examination->patient_general_examination_name;
        $patient_general_examination_list[$i]['patient_general_examination_id'] = $patient_general_examination->patient_general_examination_id;
        $patient_general_examination_list[$i]['general_examination_description'] = $patient_general_examination->general_examination_description;

        $i++;
      }
    }
    return $patient_general_examination_list; 
  }

  public function get_patient_allergy_list($prescription_id="",$opd_booking_id)
  {
        $users_data = $this->session->userdata('auth_users'); 
         $this->db->select('hms_gynecology_allergy_prescription.*'); 
          $this->db->where('hms_gynecology_allergy_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_allergy_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_allergy_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_allergy_prescription');
    $result = $query->result(); 
    $patient_allergy_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_allergy)
      {
        $patient_allergy_list[$i]['allergy_value'] = $patient_allergy->patient_allergy_name;
        $patient_allergy_list[$i]['patient_allergy_id'] = $patient_allergy->patient_allergy_id;
        $patient_allergy_list[$i]['patient_allergy_type'] = $patient_allergy->patient_allergy_type;
        $patient_allergy_list[$i]['patient_allergy_unit'] = $patient_allergy->patient_allergy_unit;
        $patient_allergy_list[$i]['allergy_description'] = $patient_allergy->allergy_description;

        $i++;
      }
    }
    return $patient_allergy_list; 
  }

  public function get_patient_complaint_list($prescription_id="",$opd_booking_id)
  {
        $users_data = $this->session->userdata('auth_users'); 
         $this->db->select('hms_gynecology_complaint_prescription.*'); 
          $this->db->where('hms_gynecology_complaint_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_complaint_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_complaint_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_complaint_prescription');
        
    $result = $query->result(); 
    $patient_complaint_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_complaint)
      {
        $patient_complaint_list[$i]['patient_complaint_name'] = $patient_complaint->patient_complaint_name;
        $patient_complaint_list[$i]['patient_complaint_id'] = $patient_complaint->patient_complaint_id;
        $patient_complaint_list[$i]['patient_complaint_type'] = $patient_complaint->patient_complaint_type;
        $patient_complaint_list[$i]['patient_complaint_unit'] = $patient_complaint->patient_complaint_unit;
        $patient_complaint_list[$i]['complaint_description'] = $patient_complaint->complaint_description;

        $i++;
      }
    }
    return $patient_complaint_list; 
  }

  public function get_antenatal_care_list($prescription_id="",$opd_booking_id)
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_gynecology_patient_antenatal_care_prescription.*'); 
    $this->db->where('hms_gynecology_patient_antenatal_care_prescription.branch_id',$users_data['parent_id']);  
    $this->db->where('hms_gynecology_patient_antenatal_care_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
    $this->db->where('hms_gynecology_patient_antenatal_care_prescription.booking_id = "'.$opd_booking_id.'"'); 
    $query = $this->db->get('hms_gynecology_patient_antenatal_care_prescription');
    $result = $query->result(); 
    return $result; 
  }
  
  
  public function get_fertility_list($prescription_id="",$opd_booking_id)
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_gynecology_fertility_prescription.*'); 
    $this->db->where('hms_gynecology_fertility_prescription.branch_id',$users_data['parent_id']);  
    $this->db->where('hms_gynecology_fertility_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
    $this->db->where('hms_gynecology_fertility_prescription.booking_id = "'.$opd_booking_id.'"'); 
    $query = $this->db->get('hms_gynecology_fertility_prescription');
    $result = $query->result(); 
    return $result; 
  }
  
    public function get_fertility_list2($prescription_id="",$opd_booking_id)
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_gynecology_fertility_prescription.*'); 
    $this->db->where('hms_gynecology_fertility_prescription.branch_id',$users_data['parent_id']);  
    $this->db->where('hms_gynecology_fertility_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
    $this->db->where('hms_gynecology_fertility_prescription.booking_id = "'.$opd_booking_id.'"'); 
    $query = $this->db->get('hms_gynecology_fertility_prescription');
    $result = $query->result_array(); 
    return $result; 
  }

    


   public function get_template_data($id)
   {
     
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_patient_template.*');
    $this->db->from('hms_gynecology_patient_template'); 
    $this->db->where('hms_gynecology_patient_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_patient_template.id',$id);
    $this->db->where('hms_gynecology_patient_template.is_deleted','0');
    $query = $this->db->get(); 
    $result = $query->row_array();
    return json_encode($result);

   }


 public function load_patient_history_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_prescription_patient_history_template.*');
    $this->db->from('hms_gynecology_prescription_patient_history_template'); 
    $this->db->where('hms_gynecology_prescription_patient_history_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_prescription_patient_history_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

 public function load_patient_family_history_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_patient_family_history_template.*');
    $this->db->from('hms_gynecology_patient_family_history_template'); 
    $this->db->where('hms_gynecology_patient_family_history_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_patient_family_history_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

  public function load_patient_personal_history_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_patient_personal_history_template.*');
    $this->db->from('hms_gynecology_patient_personal_history_template'); 
    $this->db->where('hms_gynecology_patient_personal_history_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_patient_personal_history_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }


   public function load_patient_menstrual_history_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_patient_menstrual_history_template.*');
    $this->db->from('hms_gynecology_patient_menstrual_history_template'); 
    $this->db->where('hms_gynecology_patient_menstrual_history_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_patient_menstrual_history_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

  public function load_patient_medical_history_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_patient_medical_history_template.*');
    $this->db->from('hms_gynecology_patient_medical_history_template'); 
    $this->db->where('hms_gynecology_patient_medical_history_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_patient_medical_history_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

 public function load_patient_obestetric_history_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_patient_obestetric_history_template.*');
    $this->db->from('hms_gynecology_patient_obestetric_history_template'); 
    $this->db->where('hms_gynecology_patient_obestetric_history_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_patient_obestetric_history_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

 public function load_disease_values($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_patient_disease_template.*');
    $this->db->from('hms_gynecology_patient_disease_template'); 
    $this->db->where('hms_gynecology_patient_disease_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_patient_disease_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

 public function load_complaints_values($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_complaint_template.*');
    $this->db->from('hms_gynecology_complaint_template'); 
    $this->db->where('hms_gynecology_complaint_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_complaint_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

  public function load_allergy_values($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_allergy_template.*');
    $this->db->from('hms_gynecology_allergy_template'); 
    $this->db->where('hms_gynecology_allergy_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_allergy_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

   public function load_general_examination_values($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_general_examination_template.*');
    $this->db->from('hms_gynecology_general_examination_template'); 
    $this->db->where('hms_gynecology_general_examination_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_general_examination_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

   public function load_clinical_examination_values($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_clinical_examination_template.*');
    $this->db->from('hms_gynecology_clinical_examination_template'); 
    $this->db->where('hms_gynecology_clinical_examination_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_clinical_examination_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

 public function load_investigation_values($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_investigation_template.*');
    $this->db->from('hms_gynecology_investigation_template'); 
    $this->db->where('hms_gynecology_investigation_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_investigation_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

  public function load_advice_values($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_advice_template.*');
    $this->db->from('hms_gynecology_advice_template'); 
    $this->db->where('hms_gynecology_advice_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_advice_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

  public function get_medicine_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_patient_current_medication_history_template.*');
    $this->db->from('hms_gynecology_patient_current_medication_history_template'); 
    $this->db->where('hms_gynecology_patient_current_medication_history_template.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_patient_current_medication_history_template.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }

 public function get_tabing_medicine_data($template_id)
 { 

    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_gynecology_template_medicine_booking.*');
    $this->db->from('hms_gynecology_template_medicine_booking'); 
    $this->db->where('hms_gynecology_template_medicine_booking.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_gynecology_template_medicine_booking.template_id',$template_id);
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;

 }
 
 public function get_gyneclogy_medicine_prescription_patient($prescription_id="",$opd_booking_id="")
 {
    $users_data=$this->session->userdata('auth_users');
    $this->db->select("hms_gynecology_patient_current_medication_history_prescription.*"); 
    $this->db->from('hms_gynecology_patient_current_medication_history_prescription'); 
    $this->db->where('hms_gynecology_patient_current_medication_history_prescription.medicine_template_id',$prescription_id);
    $this->db->where('hms_gynecology_patient_current_medication_history_prescription.booking_id',$opd_booking_id);
    $this->db->where('hms_gynecology_patient_current_medication_history_prescription.branch_id',$users_data['parent_id']);
    $query = $this->db->get(); 
   // echo $this->db->last_query();die;
    $result = $query->result(); 
        return $result;
  
 }

  public function get_gyneclogy_medicine_prescription_template($prescription_id="",$opd_booking_id="")
 {
    $users_data=$this->session->userdata('auth_users');
    $this->db->select("hms_gynecology_prescription_medicine_booking.*"); 
    $this->db->from('hms_gynecology_prescription_medicine_booking'); 
    $this->db->where('hms_gynecology_prescription_medicine_booking.medicine_template_id',$prescription_id);
    $this->db->where('hms_gynecology_prescription_medicine_booking.booking_id',$opd_booking_id);
    $this->db->where('hms_gynecology_prescription_medicine_booking.branch_id',$users_data['parent_id']);
    $query = $this->db->get(); 
   // echo $this->db->last_query();die;
    $result = $query->result(); 
        return $result;
  
 }

 public function get_detail_by_booking_id($id,$branch_id='')
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_opd_booking.opd_type,hms_opd_booking.*,hms_opd_booking.next_app_date as next_appointment_date,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_opd_booking.patient_bp as patientbp,hms_opd_booking.patient_temp as patienttemp,hms_opd_booking.patient_weight as patientweight,hms_opd_booking.patient_height as patientpr,hms_opd_booking.patient_spo2 as patientspo,hms_opd_booking.patient_rbs as patientrbs');
    $this->db->from('hms_opd_booking'); 
    $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
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



  public function gpla_list($id)
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('*');
    
      $this->db->from('hms_gynecology_gpla_prescription'); 
      $this->db->where('hms_gynecology_gpla_prescription.branch_id',$user_data['parent_id']); 
      $this->db->where('hms_gynecology_gpla_prescription.gynec_prescription_id',$id);
      $query = $this->db->get(); 
      return $query->result_array();
  }
  

    public function get_patient_gpla_list($prescription_id="",$opd_booking_id)
  {
       $users_data = $this->session->userdata('auth_users'); 
         $this->db->select('hms_gynecology_gpla_prescription.*'); 
          $this->db->where('hms_gynecology_gpla_prescription.branch_id',$users_data['parent_id']);  
          $this->db->where('hms_gynecology_gpla_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
          $this->db->where('hms_gynecology_gpla_prescription.booking_id = "'.$opd_booking_id.'"'); 
          
          $query = $this->db->get('hms_gynecology_gpla_prescription');
    $result = $query->result(); 
    $patient_gpla_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_gpla)
      {
        $patient_gpla_list[$i]['patient_gpla_id'] = $patient_gpla->patient_gpla_id;
        $patient_gpla_list[$i]['dog_value'] = $patient_gpla->dog_value;
        $patient_gpla_list[$i]['mode_value'] = $patient_gpla->mode_value;
        $patient_gpla_list[$i]['monthyear_value'] = $patient_gpla->monthyear_value;
        $i++;
      }
    }
    return $patient_gpla_list; 
  }




  public function get_gynecology_right_ovary_list($prescription_id="",$opd_booking_id,$type='0')
 {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_gynecology_patient_right_ovary_prescription.*'); 
      $this->db->where('hms_gynecology_patient_right_ovary_prescription.branch_id',$users_data['parent_id']);  
      $this->db->where('hms_gynecology_patient_right_ovary_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
      $this->db->where('hms_gynecology_patient_right_ovary_prescription.booking_id = "'.$opd_booking_id.'"'); 
      $this->db->where('hms_gynecology_patient_right_ovary_prescription.types',$type); 
    $query = $this->db->get('hms_gynecology_patient_right_ovary_prescription');
    //echo $this->db->last_query();die;
    $result = $query->result_array(); 
    
    /*$right_ovary_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_history)
      { 

          $right_ovary_list[$i]['right_folli_date'] = $patient_history->right_folli_date;
          $right_ovary_list[$i]['right_folli_day'] = $patient_history->right_folli_day;
          $right_ovary_list[$i]['right_folli_protocol'] = $patient_history->right_folli_protocol;
          $right_ovary_list[$i]['right_folli_pfsh'] = $patient_history->right_folli_pfsh;
          $right_ovary_list[$i]['right_folli_recfsh'] = $patient_history->right_folli_recfsh;
          $right_ovary_list[$i]['right_folli_hmg'] = $patient_history->right_folli_hmg;
          $right_ovary_list[$i]['right_folli_hp_hmg'] = $patient_history->right_folli_hp_hmg;
          $right_ovary_list[$i]['right_folli_agonist'] = $patient_history->right_folli_agonist;
          $right_ovary_list[$i]['right_folli_antiagonist'] = $patient_history->right_folli_antiagonist;
          $right_ovary_list[$i]['right_folli_trigger'] = $patient_history->right_folli_trigger;
          $right_ovary_list[$i]['right_follic_size'] = $patient_history->right_follic_size;
          $i++;
      }
    }*/
  
    return $result; 
 }




 public function get_gynecology_left_ovary_list($prescription_id="",$opd_booking_id,$type=0)
 {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_gynecology_patient_left_ovary_prescription.*'); 
      $this->db->where('hms_gynecology_patient_left_ovary_prescription.branch_id',$users_data['parent_id']);  
      $this->db->where('hms_gynecology_patient_left_ovary_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
      $this->db->where('hms_gynecology_patient_left_ovary_prescription.booking_id = "'.$opd_booking_id.'"'); 
    $this->db->where('hms_gynecology_patient_left_ovary_prescription.types',$type); 
    $query = $this->db->get('hms_gynecology_patient_left_ovary_prescription');
  // echo $this->db->last_query();die;
    $result = $query->result_array(); 
    
    /*$left_ovary_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_history)
      {
          $left_ovary_list[$i]['left_folli_date'] = $patient_history->left_folli_date;
          $left_ovary_list[$i]['left_folli_day'] = $patient_history->left_folli_day;
          $left_ovary_list[$i]['left_folli_protocol'] = $patient_history->left_folli_protocol;
          $left_ovary_list[$i]['left_folli_pfsh'] = $patient_history->left_folli_pfsh;
          $left_ovary_list[$i]['left_folli_recfsh'] = $patient_history->left_folli_recfsh;
          $left_ovary_list[$i]['left_folli_hmg'] = $patient_history->left_folli_hmg;
          $left_ovary_list[$i]['left_folli_hp_hmg'] = $patient_history->left_folli_hp_hmg;
          $left_ovary_list[$i]['left_folli_agonist'] = $patient_history->left_folli_agonist;
          $left_ovary_list[$i]['left_folli_antiagonist'] = $patient_history->left_folli_antiagonist;
          $left_ovary_list[$i]['left_folli_trigger'] = $patient_history->left_folli_trigger;
          $left_ovary_list[$i]['left_follic_size'] = $patient_history->left_follic_size;

          $left_ovary_list[$i]['endometriumothers'] = $patient_history->endometriumothers;
          $left_ovary_list[$i]['e2'] = $patient_history->e2;
          $left_ovary_list[$i]['p4'] = $patient_history->p4;
          $left_ovary_list[$i]['risk'] = $patient_history->risk;
          $left_ovary_list[$i]['others'] = $patient_history->others;
        $i++;
      }
    }*/
  
    return $result; 
 }


 public function get_patient_icsilab_list($prescription_id="",$opd_booking_id)
 {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_gynecology_patient_icsilab_prescription.*'); 
      $this->db->where('hms_gynecology_patient_icsilab_prescription.branch_id',$users_data['parent_id']);  
      $this->db->where('hms_gynecology_patient_icsilab_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
      $this->db->where('hms_gynecology_patient_icsilab_prescription.booking_id = "'.$opd_booking_id.'"'); 
    
    $query = $this->db->get('hms_gynecology_patient_icsilab_prescription');
  // echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $icsilab_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_history)
      { 
          $icsilab_list[$i]['icsilab_date'] = $patient_history->icsilab_date;
          $icsilab_list[$i]['oocytes'] = $patient_history->oocytes;
          $icsilab_list[$i]['m2'] = $patient_history->m2;
          $icsilab_list[$i]['injected'] = $patient_history->injected;
          $icsilab_list[$i]['cleavge'] = $patient_history->cleavge;
          $icsilab_list[$i]['embryos_day3'] = $patient_history->embryos_day3;
          $icsilab_list[$i]['day5'] = $patient_history->day5;
          $icsilab_list[$i]['day_of_et'] = $patient_history->day_of_et;
          
          $icsilab_list[$i]['et'] = $patient_history->et;
          $icsilab_list[$i]['vit'] = $patient_history->vit;
          $icsilab_list[$i]['lah'] = $patient_history->lah;
          $icsilab_list[$i]['semen'] = $patient_history->semen;
          $icsilab_list[$i]['count'] = $patient_history->count;
          $icsilab_list[$i]['motility'] = $patient_history->motility;
          $icsilab_list[$i]['g3'] = $patient_history->g3;
          $icsilab_list[$i]['abn_form'] = $patient_history->abn_form;
          $icsilab_list[$i]['imsi'] = $patient_history->imsi;
          $icsilab_list[$i]['pregnancy'] = $patient_history->pregnancy;
          $icsilab_list[$i]['remarks'] = $patient_history->remarks;
          $i++;
      }
    }
  
    return $icsilab_list; 
 }
 
 public function get_patient_antenatal_care_list($prescription_id="",$opd_booking_id)
 {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_gynecology_patient_antenatal_care_prescription.*'); 
      $this->db->where('hms_gynecology_patient_antenatal_care_prescription.branch_id',$users_data['parent_id']);  
      $this->db->where('hms_gynecology_patient_antenatal_care_prescription.gynec_prescription_id = "'.$prescription_id.'"');  
      $this->db->where('hms_gynecology_patient_antenatal_care_prescription.booking_id = "'.$opd_booking_id.'"'); 
    
    $query = $this->db->get('hms_gynecology_patient_antenatal_care_prescription');
  // echo $this->db->last_query();die;
    $result = $query->result(); 
    
    $antenatal_care_list = [];
    if(!empty($result))
    { 
      $i=0;
      foreach($result as $patient_history)
      { 
          $antenatal_care_list[$i]['antenatal_care_period'] = $patient_history->antenatal_care_period;
          $antenatal_care_list[$i]['antenatal_first_date'] = $patient_history->antenatal_first_date;
          $antenatal_care_list[$i]['antenatal_expected_date'] = $patient_history->antenatal_expected_date;
          $antenatal_care_list[$i]['antenatal_ultrasound'] = $patient_history->antenatal_ultrasound;
          $antenatal_care_list[$i]['antenatal_remarks'] = $patient_history->antenatal_remarks;
          $i++;
      }
    }
  
    return $antenatal_care_list; 
 }
 
 public function get_device_detail($user_id)
{
  $this->db->select('hms_user_devices.device_token,hms_user_devices.device_type,hms_user_devices.device_id');
    $this->db->where('hms_user_devices.users_id',$user_id);
    $this->db->where('hms_user_devices.login_status',1);
    $this->db->from('hms_user_devices');
    $query = $this->db->get();
    return $query->result();
} 



} 
?>