<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gynecology_prescription extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
      auth_users();
      unauthorise_permission('315','1888');
      $this->load->model('gynecology/gynecology_prescription/Gynecology_prescription_model','gynecology_prescription');
      $this->load->library('form_validation');
  }

  public function index()
  {

     
      $this->session->unset_userdata('patient_history_data');
      $this->session->unset_userdata('patient_family_history_data');
      $this->session->unset_userdata('patient_personal_history_data');
      $this->session->unset_userdata('patient_menstrual_history_data');
      $this->session->unset_userdata('patient_medical_history_data');
      $this->session->unset_userdata('patient_obestetric_history_data');
      $this->session->unset_userdata('patient_disease_data');
      $this->session->unset_userdata('patient_complaint_data');
      $this->session->unset_userdata('patient_allergy_data');
      $this->session->unset_userdata('patient_general_examination_data');
      $this->session->unset_userdata('patient_clinical_examination_data');  
      $this->session->unset_userdata('patient_investigation_data');  
      $this->session->unset_userdata('patient_advice_data');
       
      
      
  } 



  public function add_gynecology_prescription($booking_id='')
  {
    
     $post = $this->input->post();
     if(empty($post))
     {
      $this->session->unset_userdata('patient_history_data');
      $this->session->unset_userdata('patient_family_history_data');
      $this->session->unset_userdata('patient_personal_history_data');
      $this->session->unset_userdata('patient_menstrual_history_data');
      $this->session->unset_userdata('patient_medical_history_data');
      $this->session->unset_userdata('patient_obestetric_history_data');
      $this->session->unset_userdata('patient_disease_data');
      $this->session->unset_userdata('patient_complaint_data');
      $this->session->unset_userdata('patient_allergy_data');
      $this->session->unset_userdata('patient_general_examination_data');
      $this->session->unset_userdata('patient_clinical_examination_data');
      $this->session->unset_userdata('patient_investigation_data'); 
      $this->session->unset_userdata('patient_advice_data');
     }
     
     $this->load->model('opd/opd_model','opd');
     $this->load->model('gynecology/patient_template/patient_template_model','prescription_template');
    
     $this->load->helper('gynecology'); 
     $this->load->model('gynecology/general/gynecology_general_model','gynecology_general'); 
     $opd_booking_id=$this->uri->segment(4); 
     $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
     $opd_booking_data = $this->gynecology_prescription->get_by_id($opd_booking_id); 
    // echo "<pre>";print_r($opd_booking_data);die();
     $patient_id=$data['booking_data']['patient_id'];
     $data['page_title'] = 'Add Gynecology Prescription';
     if(!empty($opd_booking_data))
      {
        $booking_id = $opd_booking_data['id'];
        $referral_doctor = $opd_booking_data['referral_doctor'];
        $booking_code = $opd_booking_data['booking_code'];
        $attended_doctor = $opd_booking_data['attended_doctor'];
        $booking_date = $opd_booking_data['booking_date'];
        $booking_time = $opd_booking_data['booking_time'];
        $patient_id = $opd_booking_data['patient_id'];//
        $simulation_id = $opd_booking_data['simulation_id'];
        $patient_code = $opd_booking_data['patient_code'];
        $patient_name = $opd_booking_data['patient_name'];
        $mobile_no = $opd_booking_data['mobile_no'];
        $gender = $opd_booking_data['gender'];
        $age_y = $opd_booking_data['age_y'];
        $age_m = $opd_booking_data['age_m'];
        $age_d = $opd_booking_data['age_d'];
        $address = $opd_booking_data['address'];
        $city_id = $opd_booking_data['city_id'];
        $state_id = $opd_booking_data['state_id'];
        $country_id = $opd_booking_data['country_id']; 
        $appointment_date = $opd_booking_data['appointment_date'];
        $aadhaar_no ='';

        if(!empty($opd_booking_data['adhar_no']))
        {
          $aadhaar_no = $opd_booking_data['adhar_no'];
        }
        $patient_bp = $opd_booking_data['patient_bp'];
        $patient_temp = $opd_booking_data['patient_temp'];
        $patient_weight = $opd_booking_data['patient_weight'];
        $patient_height = $opd_booking_data['patient_height'];
        $patient_spo2 = $opd_booking_data['patient_spo2'];
        $patient_rbs = $opd_booking_data['patient_rbs'];
      }
      $data['template_list'] = $this->gynecology_prescription->gynecology_template_list();
      $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
      $data['attended_doctor_list'] = $this->opd->attended_doctor_list(); 
      $data['relation_list'] = $this->prescription_template->gynecology_realtion_list();
      $data['disease_list'] = $this->prescription_template->gynecology_disease_list();
      $data['complaint_list'] = $this->prescription_template->gynecology_complaint_list();
      $data['allergy_list'] = $this->prescription_template->gynecology_allergy_list();
      $data['general_examination_list'] = $this->prescription_template->gynecology_general_examination_list();
      $data['clinical_examination_list'] = $this->prescription_template->gynecology_clinical_examination_list();
      $data['investigation_list'] = $this->prescription_template->gynecology_investigation_list();
      $data['advice_list'] = $this->prescription_template->gynecology_advice_list();
      $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting();
      $data['prescription_patient_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
      $data['prescription_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
      $data['form_data'] = array(
                                'data_id'=>"", 
                                'patient_id'=>$patient_id,
                                'booking_id'=>$booking_id,
                                'attended_doctor'=>$attended_doctor,
                                'appointment_date'=>$appointment_date,
                                'patient_code'=>$patient_code,
                                'booking_code'=>$booking_code,
                                'simulation_id'=>$simulation_id,
                                'patient_name'=>$patient_name,
                                "aadhaar_no"=>$aadhaar_no,
                                'mobile_no'=>$mobile_no,
                                'gender'=>$gender,
                                'age_y'=>$age_y,
                                'age_m'=>$age_m,
                                'age_d'=>$age_d,
                                'dept_id'=>"",
                                'address'=>$address,
                                'city_id'=>$city_id,
                                'state_id'=>$state_id,
                                'chief_complaints'=>'',
                                'disease_details'=>"",
                                'operation'=>"",
                                'operation_date'=>date('d-m-Y'),
                                 'disease_id'=>"",
                                'examination'=>'',
                                'diagnosis'=>'',
                                'suggestion'=>'',
                                'systemic_illness'=>'',
                                'remark'=>'',
                                'reason'=>"",
                                'next_appointment_date'=>"",
                                'check_appointment'=>'',
                                'complaint_name_id'=>"",
                                'appointment_time'=>"",
                                'get_teeth_number_val'=>'',
                                'allergy_id'=>"",
                                'habit_id'=>"",
                                'diagnosis_id'=>'',
                                 'treatment_id'=>'',
                                 'advice_id'=>'',
                                 'template_list'=>'',
                                 'weight'=>'',
                                'height'=>'',
                                'bmi'=>'',
                                'temp'=>'',
                                'bp'=>'',
                                'pulse'=>'',
                                'vital_dm'=>'',
                                'bloodgroup'=>'',
                                'booking_time'=>$booking_time,
                                'booking_date'=>$booking_date
                                );
       if(isset($post) && !empty($post))
      {   
      
            $prescription_id=$this->gynecology_prescription->save();
             $this->session->set_userdata('gynec_prescription_id',$prescription_id);
            $this->session->set_flashdata('success','Prescription  successfully added.');
           redirect(base_url('prescription/?status=print_gynecology'));   
      } 

      $this->load->model('general/general_model');
      $data['vitals_list']=$this->general_model->vitals_list();

      $this->load->view('gynecology/gynecology_prescription/add',$data);

  }



   public function edit($prescription_id="",$opd_booking_id="")
    { 
      
      $users_data = $this->session->userdata('auth_users');

      //unauthorise_permission(248,1410);
    if(isset($prescription_id) && !empty($prescription_id) && is_numeric($prescription_id))
    {      
	       $this->load->model('opd/opd_model','opd');
	       $this->load->model('gynecology/patient_template/patient_template_model','prescription_template');
	       $this->load->helper('gynecology'); 
	       $this->load->model('gynecology/general/gynecology_general_model','gynecology_general'); 
	       $post = $this->input->post();     
             $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
             $patient_id=$data['booking_data']['patient_id'];
	       $data['template_list'] = $this->gynecology_prescription->gynecology_template_list();
	       $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
	       $data['attended_doctor_list'] = $this->opd->attended_doctor_list(); 
	       $data['relation_list'] = $this->prescription_template->gynecology_realtion_list();
	       $data['disease_list'] = $this->prescription_template->gynecology_disease_list();
	       $data['complaint_list'] = $this->prescription_template->gynecology_complaint_list();
	       $data['allergy_list'] = $this->prescription_template->gynecology_allergy_list();
	       $data['general_examination_list'] = $this->prescription_template->gynecology_general_examination_list();
	       $data['clinical_examination_list'] = $this->prescription_template->gynecology_clinical_examination_list();
	       $data['investigation_list'] = $this->prescription_template->gynecology_investigation_list();
	       $data['advice_list'] = $this->prescription_template->gynecology_advice_list();
	       $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting();
	       $data['prescription_patient_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
	       $data['prescription_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
         $get_by_id_data = $this->gynecology_prescription->get_by_prescription_id($prescription_id,$opd_booking_id);
            
         $data['medicine_template_data'] = $this->gynecology_prescription->get_gyneclogy_medicine_prescription_template($prescription_id,$opd_booking_id); 

        $data['medicine_template_data_patient'] = $this->gynecology_prescription->get_gyneclogy_medicine_prescription_patient($prescription_id,$opd_booking_id); 

        $prescription_data = $get_by_id_data['prescription_list'][0];

        $opd_booking_data = $this->gynecology_prescription->get_by_id($prescription_id); 

        $patient_history_db_data = $this->gynecology_prescription->get_gynecology_patient_history_list($prescription_id, $opd_booking_id);
        $patient_history_data = $this->session->userdata('patient_history_data');
        if(!isset($patient_history_data))
        {
          if(!empty($patient_history_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_history_db_data as $patient_history)
              {

               $phd_row[$i_row] = array('marriage_status'=>$patient_history['marriage_status'], 'married_life_unit'=>$patient_history['married_life_unit'],'married_life_type'=>$patient_history['married_life_type'],'marriage_no'=>$patient_history['marriage_no'],'marriage_details'=>$patient_history['marriage_details'],'previous_delivery'=>$patient_history['previous_delivery'],'delivery_type'=>$patient_history['delivery_type'],'delivery_details'=>$patient_history['delivery_details'],'unique_id'=>$i_row);
               $i_row++;
              } 
              $this->session->set_userdata('patient_history_data',$phd_row);
          }
        }

        $patient_family_history_db_data = $this->gynecology_prescription->get_gynecology_family_history_list($prescription_id, $opd_booking_id);
        $patient_family_history_data = $this->session->userdata('patient_family_history_data');
        if(!isset($patient_family_history_data))
        {
          if(!empty($patient_family_history_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_family_history_db_data as $patient_family_history)
              {
            
                if(!empty($patient_family_history['family_duration_type']))
                {
                  $patient_family_history['family_duration_type']=$patient_family_history['family_duration_type'];
                }
                else
                {
                  $patient_family_history['family_duration_type']='';

                }
                if(!empty($patient_family_history['disease_id']))
                {
                  $patient_family_history['disease_id']=$patient_family_history['disease_id'];
                }
                else
                {
                  $patient_family_history['disease_id']='';

                }
                if(!empty($patient_family_history['relation_id']))
                {
                  $patient_family_history['relation_id']=$patient_family_history['relation_id'];
                }
                else
                {
                  $patient_family_history['relation_id']='';

                }

                $phd_row[$i_row] = array('relation'=>$patient_family_history['relation'], 'disease'=>$patient_family_history['disease'],'family_description'=>$patient_family_history['family_description'],'family_duration_unit'=>$patient_family_history['family_duration_unit'],'family_duration_type'=>$patient_family_history['family_duration_type'],'relation_id'=>$patient_family_history['relation_id'],'disease_id'=>$patient_family_history['disease_id'],'unique_id_family_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_family_history_data',$phd_row);
          }
        }
        

        $family_personal_list = $this->gynecology_prescription->get_gynecology_personal_history_list($prescription_id, $opd_booking_id);
        $patient_personal_history_data = $this->session->userdata('patient_personal_history_data');
        if(!isset($patient_personal_history_data))
        {
          if(!empty($family_personal_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($family_personal_list as $patient_personal_history)
              {

                $phd_row[$i_row] = array('br_discharge'=>$patient_personal_history['br_discharge'], 'side'=>$patient_personal_history['side'],'hirsutism'=>$patient_personal_history['hirsutism'],'white_discharge'=>$patient_personal_history['white_discharge'],'type'=>$patient_personal_history['type'],'frequency_personal'=>$patient_personal_history['frequency_personal'],'dyspareunia'=>$patient_personal_history['dyspareunia'],'personal_details'=>$patient_personal_history['personal_details'], 'unique_id_personal_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_personal_history_data',$phd_row);
          }
        }
        

        $menstrual_personal_list = $this->gynecology_prescription->get_gynecology_menstrual_history_list($prescription_id, $opd_booking_id);
        $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
        if(!isset($patient_menstrual_history_data))
        {
          if(!empty($menstrual_personal_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($menstrual_personal_list as $patient_menstrual_history)
              {
                $lmp_date='';
                if(($patient_menstrual_history['lmp_date']=="1970-01-01")||($patient_menstrual_history['lmp_date']==""))
                {
                  $lmp_date = "";
                }
                else
                {
                  $lmp_date = date("d-m-Y",strtotime($patient_menstrual_history['lmp_date']));
                }

                $phd_row[$i_row] = array('previous_cycle'=>$patient_menstrual_history['previous_cycle'], 'prev_cycle_type'=>$patient_menstrual_history['prev_cycle_type'],'present_cycle'=>$patient_menstrual_history['present_cycle'],'present_cycle_type'=>$patient_menstrual_history['present_cycle_type'],'lmp_date'=>$lmp_date,'dysmenorrhea'=>$patient_menstrual_history['dysmenorrhea'],'dysmenorrhea_type'=>$patient_menstrual_history['dysmenorrhea_type'],'cycle_details'=>$patient_menstrual_history['cycle_details'], 'unique_id_menstrual_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_menstrual_history_data',$phd_row);
          }
        }
        

        $medical_list = $this->gynecology_prescription->get_gynecology_medical_history_list($prescription_id, $opd_booking_id);
        $patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
        if(!isset($patient_medical_history_data))
        {
          if(!empty($medical_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($medical_list as $patient_medical_history)
              {

                $phd_row[$i_row] = array('tb'=>$patient_medical_history['tb'], 'tb_rx'=>$patient_medical_history['tb_rx'],'dm'=>$patient_medical_history['dm'],'dm_years'=>$patient_medical_history['dm_years'],'dm_rx'=>$patient_medical_history['dm_rx'],'ht'=>$patient_medical_history['ht'],'medical_details'=>$patient_medical_history['medical_details'],'medical_others'=>$patient_medical_history['medical_others'],'unique_id_medical_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_medical_history_data',$phd_row);
          }
        }
        

        $obestetric_list = $this->gynecology_prescription->get_gynecology_obestetric_history_list($prescription_id, $opd_booking_id);
        $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
        if(!isset($patient_obestetric_history_data))
        {
          if(!empty($obestetric_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($obestetric_list as $patient_obestetric_history)
              {

                $phd_row[$i_row] = array('obestetric_g'=>$patient_obestetric_history['obestetric_g'], 'obestetric_p'=>$patient_obestetric_history['obestetric_p'],'obestetric_l'=>$patient_obestetric_history['obestetric_l'],'obestetric_mtp'=>$patient_obestetric_history['obestetric_mtp'],'unique_id_obestetric_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_obestetric_history_data',$phd_row);
          }
        }
        

        $disease_list = $this->gynecology_prescription->get_gynecology_disease_list($prescription_id, $opd_booking_id);
        $patient_disease_data = $this->session->userdata('patient_disease_data');
        if(!isset($patient_disease_data))
        {
          if(!empty($disease_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($disease_list as $patient_disease_data)
              {
                if(!empty($patient_disease_data['disease_value']))
                {
                  $patient_disease_data['disease_value']=$patient_disease_data['disease_value'];
                }
                else
                {
                  $patient_disease_data['disease_value']='';
                }

                $phd_row[$i_row] = array('patient_disease_id'=>$patient_disease_data['patient_disease_id'], 'disease_value'=>$patient_disease_data['disease_value'], 'patient_disease_unit'=>$patient_disease_data['patient_disease_unit'],'patient_disease_type'=>$patient_disease_data['patient_disease_type'],'disease_description'=>$patient_disease_data['disease_description'],'unique_id_patient_disease'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_disease_data',$phd_row);
          }
        }
        

        $patient_advice_db_data = $this->gynecology_prescription->get_patient_advice_list($prescription_id, $opd_booking_id);
        $patient_advice_data = $this->session->userdata('patient_advice_data');
        if(!isset($patient_advice_data))
        {
          if(!empty($patient_advice_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_advice_db_data as $patient_advice_data)
              {

                $phd_row[$i_row] = array('patient_advice_id'=>$patient_advice_data['patient_advice_id'], 'advice_value'=>$patient_advice_data['advice_value'], 'unique_id_patient_advice'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_advice_data',$phd_row);
          }
        }
        

        $patient_investigation_db_data = $this->gynecology_prescription->get_patient_investigation_list($prescription_id, $opd_booking_id);
        $patient_investigation_data = $this->session->userdata('patient_investigation_data');
        if(!isset($patient_investigation_data))
        {
          if(!empty($patient_investigation_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_investigation_db_data as $patient_investigation_data)
              {
                if(!empty($patient_investigation_data['std_value']))
                {
                  $patient_investigation_data['std_value'] = $patient_investigation_data['std_value'];
                }
                else
                {
                  $patient_investigation_data['std_value'] = "";
                }
                if(!empty($patient_investigation_data['observed_value']))
                {
                  $patient_investigation_data['observed_value'] = $patient_investigation_data['observed_value'];
                }
                else
                {
                  $patient_investigation_data['observed_value'] = "";
                }

                $investigation_date='';
                if(($patient_investigation_data['investigation_date']=="1970-01-01")||($patient_investigation_data['investigation_date']=="") ||($patient_investigation_data['investigation_date']=="0000-00-00") ||($patient_investigation_data['investigation_date']=="00-00-0000"))
                {
                  $investigation_date = "";
                }
                else
                {
                  $investigation_date = date("d-m-Y",strtotime($patient_investigation_data['investigation_date']));
                }

                $phd_row[$i_row] = array('patient_investigation_id'=>$patient_investigation_data['patient_investigation_id'], 'investigation_value'=>$patient_investigation_data['investigation_value'], 'std_value'=>$patient_investigation_data['std_value'], 'observed_value'=>$patient_investigation_data['observed_value'], 'unique_id_patient_investigation'=>$i_row, 'investigation_date'=>$investigation_date);

               $i_row++;
              } 
              $this->session->set_userdata('patient_investigation_data',$phd_row);
          }
        }
        

        $patient_clinical_examination_db_data = $this->gynecology_prescription->get_patient_clinical_examination_list($prescription_id, $opd_booking_id);
        $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');

        if(!isset($patient_clinical_examination_data))
        {
          if(!empty($patient_clinical_examination_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_clinical_examination_db_data as $patient_clinical_examination_data)
              {

                $phd_row[$i_row] = array('patient_clinical_examination_id'=>$patient_clinical_examination_data['patient_clinical_examination_id'], 'clinical_examination_value'=>$patient_clinical_examination_data['clinical_examination_value'],'clinical_examination_description'=>$patient_clinical_examination_data['clinical_examination_description'],'unique_id_patient_clinical_examination'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_clinical_examination_data',$phd_row);
          }
        }

        

        $patient_general_examination_db_data = $this->gynecology_prescription->get_patient_general_examination_list($prescription_id, $opd_booking_id);
        $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');

        if(!isset($patient_general_examination_data))
        {
          if(!empty($patient_general_examination_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_general_examination_db_data as $patient_general_examination_data)
              {

                $phd_row[$i_row] = array('patient_general_examination_id'=>$patient_general_examination_data['patient_general_examination_id'], 'general_examination_value'=>$patient_general_examination_data['general_examination_value'], 'general_examination_description'=>$patient_general_examination_data['general_examination_description'],'unique_id_patient_general_examination'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_general_examination_data',$phd_row);
          }
        }

        $patient_allergy_db_data = $this->gynecology_prescription->get_patient_allergy_list($prescription_id, $opd_booking_id);
        $patient_allergy_data = $this->session->userdata('patient_allergy_data');

        if(!isset($patient_allergy_data))
        {
          if(!empty($patient_allergy_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_allergy_db_data as $patient_allergy_data)
              {

                $phd_row[$i_row] = array('patient_allergy_id'=>$patient_allergy_data['patient_allergy_id'], 'allergy_value'=>$patient_allergy_data['allergy_value'], 'patient_allergy_unit'=>$patient_allergy_data['patient_allergy_unit'],'patient_allergy_type'=>$patient_allergy_data['patient_allergy_type'],'allergy_description'=>$patient_allergy_data['allergy_description'],'unique_id_patient_allergy'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_allergy_data',$phd_row);
          }
        }

        $patient_complaint_db_data = $this->gynecology_prescription->get_patient_complaint_list($prescription_id, $opd_booking_id);
        $patient_complaint_data = $this->session->userdata('patient_complaint_data');

        if(!isset($patient_complaint_data))
        {
          if(!empty($patient_complaint_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_complaint_db_data as $patient_complaint_data)
              {
                if(!empty($patient_complaint_data['patient_complaint_name']))
                {
                  $patient_complaint_data['patient_complaint_name']=$patient_complaint_data['patient_complaint_name'];
                }
                else
                {
                  $patient_complaint_data['patient_complaint_name']='';
                }

                $phd_row[$i_row] = array('patient_complaint_id'=>$patient_complaint_data['patient_complaint_id'], 'complaint_value'=>$patient_complaint_data['patient_complaint_name'], 'patient_complaint_unit'=>$patient_complaint_data['patient_complaint_unit'],'patient_complaint_type'=>$patient_complaint_data['patient_complaint_type'],'complaint_description'=>$patient_complaint_data['complaint_description'],'unique_id_patient_complaint'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_complaint_data',$phd_row);
          }
        }

        
 

       $data['page_title'] = "Update Prescription";  
       $post = $this->input->post();
 
       $data['form_error'] = ''; 
       
     
       $next_appointmentdate='';
      if(!empty($prescription_data->next_appointment_date) && ($prescription_data->next_appointment_date!='0000-00-00 00:00:00') && (date('d-m-Y',strtotime($prescription_data->next_appointment_date))!='1970-01-01'))
      {
          $next_appointmentdate = date('d-m-Y H:i A',strtotime($prescription_data->next_appointment_date));
      }   
      else
      {
          $next_appointmentdate = ''; 
      }
      $data['form_data'] = array(
                            'data_id'=>$prescription_id, 
                            'patient_id'=>$prescription_data->patient_id,
                            'booking_id'=>$prescription_data->booking_id,
                            'attended_doctor'=>$prescription_data->attended_doctor,
                            'appointment_date'=>$prescription_data->appointment_date,
                            'patient_code'=>$prescription_data->patient_code,
                            'booking_code'=>$prescription_data->booking_code,
                            'simulation_id'=>$prescription_data->simulation_id,
                            'patient_name'=>$prescription_data->patient_name,
                            'mobile_no'=>$prescription_data->mobile_no,
                            'gender'=>$prescription_data->gender,
                            'aadhaar_no'=>$prescription_data->adhar_no,
                            'age_y'=>$prescription_data->age_y,
                            'age_m'=>$prescription_data->age_m,
                            'age_d'=>$prescription_data->age_d,
                            'address'=>$prescription_data->address,
                            'city_id'=>$prescription_data->city_id,
                            'state_id'=>$prescription_data->state_id,
                            'country_id'=>$prescription_data->country_id,
                            'next_appointment_date'=>$next_appointmentdate,
                            'template_list'=>$prescription_data->template_id,
                            'complaint_name_id'=>'',
                            'disease_id'=>'',
                            'disease_details'=>'',
                            'operation'=>'',
                            'operation_date'=>'',
                            'reason'=>"",
                            'allergy_id'=>"",
                            'habit_id'=>"",
                            'diagnosis_id'=>'',
                            'treatment_id'=>'',
                            'advice_id'=>'',
                            'weight'=>$prescription_data->weight,
                            'height'=>$prescription_data->height,
                            'bmi'=>$prescription_data->bmi,
                            'temp'=>$prescription_data->temp,
                            'bp'=>$prescription_data->bp,
                            'pulse'=>$prescription_data->pulse,
                            'vital_dm'=>$prescription_data->dm,
                            'bloodgroup'=>$prescription_data->bloodgroup,
                            'booking_date'=>$prescription_data->booking_date,
                            'booking_time'=>$prescription_data->booking_time,
                            'check_appointment'=>$prescription_data->check_appointment,
                            );
       //print"<pre>";print_r($data['form_data']);die; 

         if(isset($post) && !empty($post))
      {   
      
            $prescription_id=$this->gynecology_prescription->save();
             $this->session->set_userdata('gynec_prescription_id',$prescription_id);
            $this->session->set_flashdata('success','Prescription updated successfully.');
           redirect(base_url('prescription/?status=print_gynecology'));   
      }

      $this->load->model('general/general_model');
      $data['vitals_list']=$this->general_model->vitals_list();

      $this->load->view('gynecology/gynecology_prescription/add',$data);

      
       
    }
  } 



//

  public function patient_history_list()
  {
    $post = $this->input->post();
    $patient_history_data = $this->session->userdata('patient_history_data'); 
    if(isset($post) && !empty($post))
    {   
      $patient_history_data = $this->session->userdata('patient_history_data'); 
      if(isset($patient_history_data) && !empty($patient_history_data))
      {
        $patient_history = $patient_history_data; 
      }
      else
      {
        $patient_history = [];
      }
      $patient_history[$post['unique_id']] = array('marriage_status'=>$post['marriage_status'], 'married_life_unit'=>$post['married_life_unit'],'married_life_type'=>$post['married_life_type'],'marriage_no'=>$post['marriage_no'],'marriage_details'=>$post['marriage_details'],'previous_delivery'=>$post['previous_delivery'],'delivery_type'=>$post['delivery_type'],'delivery_details'=>$post['delivery_details'],'rec_count'=>$post['rec_count'],'unique_id'=>$post['unique_id']);
      $this->session->set_userdata('patient_history_data', $patient_history);
      $html_data = $this->patient_history_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
      
   }
  }

  private function patient_history_template_list()
    {
      $patient_history_data = $this->session->userdata('patient_history_data');
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
                      <th>Married</th>
                      <th>Married Life</th>
                      <th>Marriage No.</th>
                      <th>Married Details</th>
                      <th>Previous Delivery</th>
                      <th>Delivery Type</th>
                      <th>Delivery Details </th>
                  </tr></thead>';  
           if(isset($patient_history_data) && !empty($patient_history_data))
           {
              $i = 1;
              foreach($patient_history_data as $key=>$patienthistorydata)
              { 
                if (strpos($patienthistorydata['married_life_type'], 'Select') !== false) 
                {
                    $patienthistorydata['married_life_type'] = "";
                }
                else
                {
                  $patienthistorydata['married_life_type'] = $patienthistorydata['married_life_type'];
                }
                if (strpos($patienthistorydata['previous_delivery'], 'Select') !== false) 
                {
                    $patienthistorydata['previous_delivery'] = "";
                }
                else
                {
                  $patienthistorydata['previous_delivery'] = $patienthistorydata['previous_delivery'];
                }
                if (strpos($patienthistorydata['delivery_type'], 'Select') !== false) 
                {
                    $patienthistorydata['delivery_type'] = "";
                }
                else
                {
                  $patienthistorydata['delivery_type'] = $patienthistorydata['delivery_type'];
                }
                 $rows .= '<tr name="patient_history_row" id="'.$key.'">
                            <td width="60" align="center"><input type="checkbox" name="patient_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patienthistorydata['marriage_status'].'</td>
                            <td>'.$patienthistorydata['married_life_unit'].' '.$patienthistorydata['married_life_type'].'</td>
                            <td>'.$patienthistorydata['marriage_no'].'</td>
                            <td>'.$patienthistorydata['marriage_details'].'</td>
                             <td>'.$patienthistorydata['previous_delivery'].'  </td>
                             <td>'.$patienthistorydata['delivery_type'].'  </td>
                             <td>'.$patienthistorydata['delivery_details'].'  </td>
                            
                        </tr>';
                 $i++;                
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function patient_family_history_list()
    {
      $post = $this->input->post();
      $patient_family_history_data = $this->session->userdata('patient_family_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_family_history_data = $this->session->userdata('patient_family_history_data'); 
        if(isset($patient_family_history_data) && !empty($patient_family_history_data))
        {
          $patient_history = $patient_family_history_data; 
        }
        else
        {
          $patient_history = [];
        }
        $patient_history[$post['unique_id_family_history']] = array('relation'=>$post['relation'], 'disease'=>$post['disease'],'family_description'=>$post['family_description'],'family_duration_unit'=>$post['family_duration_unit'],'family_duration_type'=>$post['family_duration_type'],'relation_id'=>$post['relation_id'],'disease_id'=>$post['disease_id'],'unique_id_family_history'=>$post['unique_id_family_history']);
        $this->session->set_userdata('patient_family_history_data', $patient_history);
        $html_data = $this->patient_family_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_family_history_template_list()
    {
      $patient_family_history_data = $this->session->userdata('patient_family_history_data');
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
                      <th>Relation</th>
                      <th>Disease</th>
                      <th>Description</th>
                      <th>Duration</th>
                  </tr></thead>';  
           if(isset($patient_family_history_data) && !empty($patient_family_history_data))
           {
              $i = 1;
              foreach($patient_family_history_data as $key=>$patient_family_history_data)
              {
                if (strpos($patient_family_history_data['relation'], 'Select') !== false) 
                {
                    $patient_family_history_data['relation'] = "";
                }
                else
                {
                  $patient_family_history_data['relation'] = $patient_family_history_data['relation'];
                }
                if (strpos($patient_family_history_data['disease'], 'Select') !== false) 
                {
                    $patient_family_history_data['disease'] = "";
                }
                else
                {
                  $patient_family_history_data['disease'] = $patient_family_history_data['disease'];
                }
                if (strpos($patient_family_history_data['family_duration_type'], 'Select') !== false) 
                {
                    $patient_family_history_data['family_duration_type'] = "";
                }
                else
                {
                  $patient_family_history_data['family_duration_type'] = $patient_family_history_data['family_duration_type'];
                }
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_family_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_family_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_family_history_data['relation'].'</td>
                            <td>'.$patient_family_history_data['disease'].'</td>
                            <td>'.$patient_family_history_data['family_description'].'</td>
                            <td>'.$patient_family_history_data['family_duration_unit'].' '.$patient_family_history_data['family_duration_type'].'</td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  public function patient_personal_history_list()
    {
      $post = $this->input->post();
      $patient_personal_history_data = $this->session->userdata('patient_personal_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_personal_history_data = $this->session->userdata('patient_personal_history_data'); 
        if(isset($patient_personal_history_data) && !empty($patient_personal_history_data))
        {
          $patient_history = $patient_personal_history_data; 
        }
        else
        {
          $patient_history = [];
        }
        $patient_history[$post['unique_id_personal_history']] = array('br_discharge'=>$post['br_discharge'], 'side'=>$post['side'],'hirsutism'=>$post['hirsutism'],'white_discharge'=>$post['white_discharge'],'type'=>$post['type'],'frequency_personal'=>$post['frequency_personal'],'dyspareunia'=>$post['dyspareunia'],'personal_details'=>$post['personal_details'], 'unique_id_personal_history'=>$post['unique_id_personal_history']);
        $this->session->set_userdata('patient_personal_history_data', $patient_history);
        $html_data = $this->patient_personal_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_personal_history_template_list()
    {
      $patient_personal_history_data = $this->session->userdata('patient_personal_history_data');
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
                      <th>Breast Discharge</th>
                      <th>Side</th>
                      <th>Hirsutism</th>
                      <th>White Discharge</th>
                      <th>Type</th>
                      <th>Frequency</th>
                      <th>Dyspareunia</th>
                      <th>Details</th>
                  </tr></thead>';  
           if(isset($patient_personal_history_data) && !empty($patient_personal_history_data))
           {
              $i = 1;
              foreach($patient_personal_history_data as $key=>$patient_personal_history_data)
              {
                if (strpos($patient_personal_history_data['br_discharge'], 'Select') !== false) 
                {
                  $patient_personal_history_data['br_discharge'] = "";
                }
                else
                {
                  $patient_personal_history_data['br_discharge'] = $patient_personal_history_data['br_discharge'];
                }
                if (strpos($patient_personal_history_data['side'], 'Select') !== false) 
                {
                    $patient_personal_history_data['side'] = "";
                }
                else
                {
                  $patient_personal_history_data['side'] = $patient_personal_history_data['side'];
                }
                if (strpos($patient_personal_history_data['hirsutism'], 'Select') !== false) 
                {
                    $patient_personal_history_data['hirsutism'] = "";
                }
                else
                {
                  $patient_personal_history_data['hirsutism'] = $patient_personal_history_data['hirsutism'];
                }
                if (strpos($patient_personal_history_data['white_discharge'], 'Select') !== false) 
                {
                    $patient_personal_history_data['white_discharge'] = "";
                }
                else
                {
                  $patient_personal_history_data['white_discharge'] = $patient_personal_history_data['white_discharge'];
                }
                if (strpos($patient_personal_history_data['type'], 'Select') !== false) 
                {
                    $patient_personal_history_data['type'] = "";
                }
                else
                {
                  $patient_personal_history_data['type'] = $patient_personal_history_data['type'];
                }
                if (strpos($patient_personal_history_data['dyspareunia'], 'Select') !== false) 
                {
                    $patient_personal_history_data['dyspareunia'] = "";
                }
                else
                {
                  $patient_personal_history_data['dyspareunia'] = $patient_personal_history_data['dyspareunia'];
                }
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_personal_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_personal_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_personal_history_data['br_discharge'].'</td>
                            <td>'.$patient_personal_history_data['side'].'</td>
                            <td>'.$patient_personal_history_data['hirsutism'].'</td>
                            <td>'.$patient_personal_history_data['white_discharge'].'</td>
                            <td>'.$patient_personal_history_data['type'].'</td>
                            <td>'.$patient_personal_history_data['frequency_personal'].'</td>
                            <td>'.$patient_personal_history_data['dyspareunia'].'</td>
                            <td>'.$patient_personal_history_data['personal_details'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function patient_menstrual_history_list()
    {
      $post = $this->input->post();
         //print_r($post);

      $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data'); 
        if(isset($patient_menstrual_history_data) && !empty($patient_menstrual_history_data))
        {
          $patient_menstrual_history = $patient_menstrual_history_data; 
        }
        else
        {
          $patient_menstrual_history = [];
        }
        $lmp_date='';
        if(($post['lmp_date']=="1970-01-01")||($post['lmp_date']==""))
        {
          $lmp_date = "";

        }
        else
        {
          $lmp_date = date("d-m-Y",strtotime($post['lmp_date']));
        }

        $patient_menstrual_history[$post['unique_id_menstrual_history']] = array('previous_cycle'=>$post['previous_cycle'], 'prev_cycle_type'=>$post['prev_cycle_type'],'present_cycle'=>$post['present_cycle'],'present_cycle_type'=>$post['present_cycle_type'],'lmp_date'=>$lmp_date,'dysmenorrhea'=>$post['dysmenorrhea'],'dysmenorrhea_type'=>$post['dysmenorrhea_type'],'cycle_details'=>$post['cycle_details'],'unique_id_menstrual_history'=>$post['unique_id_menstrual_history']);
        $this->session->set_userdata('patient_menstrual_history_data', $patient_menstrual_history);
        $html_data = $this->patient_menstrual_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_menstrual_history_template_list()
    {
      $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
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
                      <th>Previous Cycle</th>
                      <th>Cycle Type</th>
                      <th>Present Cycle</th>
                      <th>Cycle Type</th>
                      <th>Details</th>
                      <th>LMP Date</th>
                      <th>Dysmenorrhea</th>
                      <th>Dysmenorrhea Type</th>
                  </tr></thead>';  
           if(isset($patient_menstrual_history_data) && !empty($patient_menstrual_history_data))
           {
              $i = 1;
              foreach($patient_menstrual_history_data as $key=>$patient_menstrual_history_data)
              {
                if (strpos($patient_menstrual_history_data['previous_cycle'], 'Select') !== false) 
                {
                  $patient_menstrual_history_data['previous_cycle'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['previous_cycle'] = $patient_menstrual_history_data['previous_cycle'];
                }
                if (strpos($patient_menstrual_history_data['prev_cycle_type'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['prev_cycle_type'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['prev_cycle_type'] = $patient_menstrual_history_data['prev_cycle_type'];
                }
                if (strpos($patient_menstrual_history_data['present_cycle'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['present_cycle'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['present_cycle'] = $patient_menstrual_history_data['present_cycle'];
                }
                if (strpos($patient_menstrual_history_data['present_cycle_type'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['present_cycle_type'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['present_cycle_type'] = $patient_menstrual_history_data['present_cycle_type'];
                }
                if (strpos($patient_menstrual_history_data['dysmenorrhea'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['dysmenorrhea'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['dysmenorrhea'] = $patient_menstrual_history_data['dysmenorrhea'];
                }
                if (strpos($patient_menstrual_history_data['dysmenorrhea_type'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['dysmenorrhea_type'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['dysmenorrhea_type'] = $patient_menstrual_history_data['dysmenorrhea_type'];
                }
                
               
                if(($patient_menstrual_history_data['lmp_date']=="01-01-1970")||($patient_menstrual_history_data['lmp_date']==''))
                {
                  $lmp_date = "";
                }
                else
                {
                $lmp_date = $patient_menstrual_history_data['lmp_date'];  
               }
             
           //print_r($patient_menstrual_history_data);
           //echo $patient_menstrual_history_data['lmp_date'];
           //echo $lmp_date;
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_menstrual_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_menstrual_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_menstrual_history_data['previous_cycle'].'</td>
                            <td>'.$patient_menstrual_history_data['prev_cycle_type'].'</td>
                            <td>'.$patient_menstrual_history_data['present_cycle'].'</td>
                            <td>'.$patient_menstrual_history_data['present_cycle_type'].'</td>
                            <td>'.$patient_menstrual_history_data['cycle_details'].'</td>
                            <td>'.$lmp_date.'</td>
                            <td>'.$patient_menstrual_history_data['dysmenorrhea'].'</td>
                            <td>'.$patient_menstrual_history_data['dysmenorrhea_type'].'</td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


  public function patient_medical_history_list()
    {

      //$this->session->unset_userdata('patient_personal_history_data');die;
      $post = $this->input->post();

      $patient_medical_history_data = $this->session->userdata('patient_medical_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_medical_history_data = $this->session->userdata('patient_medical_history_data'); 
        if(isset($patient_medical_history_data) && !empty($patient_medical_history_data))
        {
          $patient_medical_history = $patient_medical_history_data; 
        }
        else
        {
          $patient_medical_history = [];
        }
        $patient_medical_history[$post['unique_id_medical_history']] = array('tb'=>$post['tb'], 'tb_rx'=>$post['tb_rx'],'dm'=>$post['dm'],'dm_years'=>$post['dm_years'],'dm_rx'=>$post['dm_rx'],'ht'=>$post['ht'],'medical_details'=>$post['medical_details'],'medical_others'=>$post['medical_others'],'unique_id_medical_history'=>$post['unique_id_medical_history']);
        //print_r( $patient_medical_history);die;
        $this->session->set_userdata('patient_medical_history_data', $patient_medical_history);
        $html_data = $this->patient_medical_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_medical_history_template_list()
    {
      $patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
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
                      <th>T.B</th>
                      <th>Rx</th>
                      <th>D.M</th>
                      <th>Years</th>
                      <th>Rx</th>
                      <th>H.T</th>
                      <th>Details</th>
                      <th>Others</th>
                  </tr></thead>';  
           if(isset($patient_medical_history_data) && !empty($patient_medical_history_data))
           {
              $i = 1;
              foreach($patient_medical_history_data as $key=>$patient_medical_history_data)
              {
                if (strpos($patient_medical_history_data['tb'], 'Select') !== false) 
                {
                  $patient_medical_history_data['tb'] = "";
                }
                else
                {
                  $patient_medical_history_data['tb'] = $patient_medical_history_data['tb'];
                }
                if (strpos($patient_medical_history_data['dm'], 'Select') !== false) 
                {
                    $patient_medical_history_data['dm'] = "";
                }
                else
                {
                  $patient_medical_history_data['dm'] = $patient_medical_history_data['dm'];
                }
                if (strpos($patient_medical_history_data['ht'], 'Select') !== false) 
                {
                    $patient_medical_history_data['ht'] = "";
                }
                else
                {
                  $patient_medical_history_data['ht'] = $patient_medical_history_data['ht'];
                }
                
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_medical_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_medical_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_medical_history_data['tb'].'</td>
                            <td>'.$patient_medical_history_data['tb_rx'].'</td>
                            <td>'.$patient_medical_history_data['dm'].'</td>
                            <td>'.$patient_medical_history_data['dm_years'].'</td>
                            <td>'.$patient_medical_history_data['dm_rx'].'</td>
                            <td>'.$patient_medical_history_data['ht'].'</td>
                            <td>'.$patient_medical_history_data['medical_details'].'</td>
                            <td>'.$patient_medical_history_data['medical_others'].'</td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  public function patient_obestetric_history_list()
    {

      //$this->session->unset_userdata('patient_personal_history_data');die;
      $post = $this->input->post();

      $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data'); 
        if(isset($patient_obestetric_history_data) && !empty($patient_obestetric_history_data))
        {
          $patient_obestetric_history = $patient_obestetric_history_data; 
        }
        else
        {
          $patient_obestetric_history = [];
        }
        $patient_obestetric_history[$post['unique_id_obestetric_history']] = array('obestetric_g'=>$post['obestetric_g'], 'obestetric_p'=>$post['obestetric_p'],'obestetric_l'=>$post['obestetric_l'],'obestetric_mtp'=>$post['obestetric_mtp'],'unique_id_obestetric_history'=>$post['unique_id_obestetric_history']);
        //print_r( $patient_obestetric_history);die;
        $this->session->set_userdata('patient_obestetric_history_data', $patient_obestetric_history);
        $html_data = $this->patient_obestetric_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_obestetric_history_template_list()
    {
      $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
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
                      <th>G</th>
                      <th>P</th>
                      <th>L</th>
                      <th>MTP</th>
                  </tr></thead>';  
           if(isset($patient_obestetric_history_data) && !empty($patient_obestetric_history_data))
           {
              $i = 1;
              foreach($patient_obestetric_history_data as $key=>$patient_obestetric_history_data)
              {
                
                
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_obestetric_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_obestetric_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_g'].'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_p'].'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_l'].'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_mtp'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  public function patient_disease_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_disease_data = $this->session->userdata('patient_disease_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_disease_data = $this->session->userdata('patient_disease_data'); 
        if(isset($patient_disease_data) && !empty($patient_disease_data))
        {
          $patient_disease = $patient_disease_data;
        }
        else
        {
          $patient_disease = [];
        }
       
        $patient_disease[$post['unique_id_patient_disease']] = array('patient_disease_id'=>$post['patient_disease_id'], 'disease_value'=>$post['disease_value'], 'patient_disease_unit'=>$post['patient_disease_unit'],'patient_disease_type'=>$post['patient_disease_type'],'disease_description'=>$post['disease_description'],'unique_id_patient_disease'=>$post['unique_id_patient_disease']);
        $this->session->set_userdata('patient_disease_data', $patient_disease);
        $html_data = $this->patient_disease_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
    }
  }

   private function patient_disease_template_list()
    {
      $patient_disease_data = $this->session->userdata('patient_disease_data');
      //print_r($patient_disease_data);die;
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
                    <th>Disease Name</th>
                    <th>Duration</th>
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_disease_data) && !empty($patient_disease_data))
           {
              $i = 1;
              foreach($patient_disease_data as $key=>$patient_disease_data_rec)
              {
                if (strpos($patient_disease_data_rec['patient_disease_type'], 'Select') !== false) 
                {
                    $patient_disease_data_rec['patient_disease_type'] = "";
                }
                else
                {
                  $patient_disease_data_rec['patient_disease_type'] = $patient_disease_data_rec['patient_disease_type'];
                }
                  $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_disease_name[]" class="part_checkbox booked_checkbox" value="'.$key.'"  ></td>
                            <input type="hidden" name="unique_id_patient_disease" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_disease_data_rec['disease_value'].'</td>
                            <td>'.$patient_disease_data_rec['patient_disease_unit'].' '.$patient_disease_data_rec['patient_disease_type'].'</td>
                            <td>'.$patient_disease_data_rec['disease_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  public function patient_complaint_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_complaint_data = $this->session->userdata('patient_complaint_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_complaint_data = $this->session->userdata('patient_complaint_data'); 
        if(isset($patient_complaint_data) && !empty($patient_complaint_data))
        {
          $patient_complaint = $patient_complaint_data;
        }
        else
        {
          $patient_complaint = [];
        }
       
        $patient_complaint[$post['unique_id_patient_complaint']] = array('patient_complaint_id'=>$post['patient_complaint_id'], 'complaint_value'=>$post['complaint_value'], 'patient_complaint_unit'=>$post['patient_complaint_unit'],'patient_complaint_type'=>$post['patient_complaint_type'],'complaint_description'=>$post['complaint_description'],'unique_id_patient_complaint'=>$post['unique_id_patient_complaint']);
        $this->session->set_userdata('patient_complaint_data', $patient_complaint);
        $html_data = $this->patient_complaint_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_complaint_template_list()
    {
      $patient_complaint_data = $this->session->userdata('patient_complaint_data');
      //print_r($patient_complaint_data);die;
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
                    <th>Complaint Name</th>
                    <th>Duration</th>
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_complaint_data) && !empty($patient_complaint_data))
           {
              $i = 1;
              foreach($patient_complaint_data as $key=>$patient_complaint_data_rec)
              {
                if (strpos($patient_complaint_data_rec['patient_complaint_type'], 'Select') !== false) 
                {
                    $patient_complaint_data_rec['patient_complaint_type'] = "";
                }
                else
                {
                  $patient_complaint_data_rec['patient_complaint_type'] = $patient_complaint_data_rec['patient_complaint_type'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_complaint_name[]" class="part_checkbox booked_checkbox" value="'.$key.'"  ></td>
                            <input type="hidden" name="unique_id_patient_complaint" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_complaint_data_rec['complaint_value'].'</td>
                            <td>'.$patient_complaint_data_rec['patient_complaint_unit'].' '.$patient_complaint_data_rec['patient_complaint_type'].'</td>
                            <td>'.$patient_complaint_data_rec['complaint_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Complaint are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  public function patient_allergy_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_allergy_data = $this->session->userdata('patient_allergy_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_allergy_data = $this->session->userdata('patient_allergy_data'); 
        if(isset($patient_allergy_data) && !empty($patient_allergy_data))
        {
          $patient_allergy = $patient_allergy_data;
        }
        else
        {
          $patient_allergy = [];
        }
       
        $patient_allergy[$post['unique_id_patient_allergy']] = array('patient_allergy_id'=>$post['patient_allergy_id'], 'allergy_value'=>$post['allergy_value'], 'patient_allergy_unit'=>$post['patient_allergy_unit'],'patient_allergy_type'=>$post['patient_allergy_type'],'allergy_description'=>$post['allergy_description'],'unique_id_patient_allergy'=>$post['unique_id_patient_allergy']);
        $this->session->set_userdata('patient_allergy_data', $patient_allergy);
        $html_data = $this->patient_allergy_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_allergy_template_list()
    {
      $patient_allergy_data = $this->session->userdata('patient_allergy_data');
      //print_r($patient_allergy_data);die;
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
                    <th>Allergy Name</th>
                    <th>Duration</th>
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_allergy_data) && !empty($patient_allergy_data))
           {
              $i = 1;
              foreach($patient_allergy_data as $key=>$patient_allergy_data_rec)
              {
                if (strpos($patient_allergy_data_rec['patient_allergy_type'], 'Select') !== false) 
                {
                    $patient_allergy_data_rec['patient_allergy_type'] = "";
                }
                else
                {
                  $patient_allergy_data_rec['patient_allergy_type'] = $patient_allergy_data_rec['patient_allergy_type'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_allergy_name[]" class="part_checkbox booked_checkbox" value="'.$key.'"  ></td>
                            <input type="hidden" name="unique_id_patient_allergy" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_allergy_data_rec['allergy_value'].'</td>
                            <td>'.$patient_allergy_data_rec['patient_allergy_unit'].' '.$patient_allergy_data_rec['patient_allergy_type'].'</td>
                            <td>'.$patient_allergy_data_rec['allergy_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Allergy are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  
  public function patient_general_examination_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_general_examination_data = $this->session->userdata('patient_general_examination_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_general_examination_data = $this->session->userdata('patient_general_examination_data'); 
        if(isset($patient_general_examination_data) && !empty($patient_general_examination_data))
        {
          $patient_general_examination = $patient_general_examination_data;
        }
        else
        {
          $patient_general_examination = [];
        }
       
        $patient_general_examination[$post['unique_id_patient_general_examination']] = array('patient_general_examination_id'=>$post['patient_general_examination_id'], 'general_examination_value'=>$post['general_examination_value'],'general_examination_description'=>$post['general_examination_description'],'unique_id_patient_general_examination'=>$post['unique_id_patient_general_examination']);
        $this->session->set_userdata('patient_general_examination_data', $patient_general_examination);
        $html_data = $this->patient_general_examination_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_general_examination_template_list()
    {
      $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
      //print_r($patient_general_examination_data);die;
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
                    <th>Exam Name</th>
                   
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_general_examination_data) && !empty($patient_general_examination_data))
           {
              $i = 1;
              foreach($patient_general_examination_data as $key=>$patient_general_examination_data_rec)
              {
                
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_general_examination_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_general_examination" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_general_examination_data_rec['general_examination_value'].'</td>
                            
                            <td>'.$patient_general_examination_data_rec['general_examination_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">General Examination are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_clinical_examination_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data'); 
        if(isset($patient_clinical_examination_data) && !empty($patient_clinical_examination_data))
        {
          $patient_clinical_examination = $patient_clinical_examination_data;
        }
        else
        {
          $patient_clinical_examination = [];
        }
       
        $patient_clinical_examination[$post['unique_id_patient_clinical_examination']] = array('patient_clinical_examination_id'=>$post['patient_clinical_examination_id'], 'clinical_examination_value'=>$post['clinical_examination_value'], 'clinical_examination_description'=>$post['clinical_examination_description'],'unique_id_patient_clinical_examination'=>$post['unique_id_patient_clinical_examination']);
        $this->session->set_userdata('patient_clinical_examination_data', $patient_clinical_examination);
        $html_data = $this->patient_clinical_examination_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_clinical_examination_template_list()
    {
      $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
      //print_r($patient_clinical_examination_data);die;
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
                    <th>Exam Name</th>
                   
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_clinical_examination_data) && !empty($patient_clinical_examination_data))
           {
              $i = 1;
              foreach($patient_clinical_examination_data as $key=>$patient_clinical_examination_data_rec)
              {
                
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_clinical_examination_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_clinical_examination" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_clinical_examination_data_rec['clinical_examination_value'].'</td>
                            
                            <td>'.$patient_clinical_examination_data_rec['clinical_examination_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Clinical Examination are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  public function patient_advice_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_advice_data = $this->session->userdata('patient_advice_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_advice_data = $this->session->userdata('patient_advice_data'); 
        if(isset($patient_advice_data) && !empty($patient_advice_data))
        {
          $patient_advice = $patient_advice_data;
        }
        else
        {
          $patient_advice = [];
        }
       
        $patient_advice[$post['unique_id_patient_advice']] = array('patient_advice_id'=>$post['patient_advice_id'], 'advice_value'=>$post['advice_value'], 'unique_id_patient_advice'=>$post['unique_id_patient_advice']);
        $this->session->set_userdata('patient_advice_data', $patient_advice);
        $html_data = $this->patient_advice_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_advice_template_list()
    {
      $patient_advice_data = $this->session->userdata('patient_advice_data');
      //print_r($patient_advice_data);die;
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
                    <th>Advice Name</th>
                  </tr></thead>';  
           if(isset($patient_advice_data) && !empty($patient_advice_data))
           {
              $i = 1;
              foreach($patient_advice_data as $key=>$patient_advice_data_rec)
              {
                if (strpos($patient_advice_data_rec['advice_value'], 'Select') !== false) 
                {
                    $patient_advice_data_rec['advice_value'] = "";
                }
                else
                {
                  $patient_advice_data_rec['advice_value'] = $patient_advice_data_rec['advice_value'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_advice_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_advice" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_advice_data_rec['advice_value'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Advice are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    function get_gynecology_medicine_auto_vals($vals="")
  {
       //echo "hi";die;
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_medicine_auto_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  }


  function get_gynecology_dosage_vals($vals="")
  {
      //echo 'rwe';die;
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_dosage_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  }


   function get_gynecology_duration_vals($vals="")
  {
      
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_duration_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  }

  function get_gynecology_frequency_vals($vals="")
  {
      
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_frequency_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  } 


 function get_gynecology_advice_vals($vals="")
  {
      
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_advice_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  }

  function get_gynecology_type_vals($vals="")
  {
      
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_type_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  }




  public function patient_investigation_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_investigation_data = $this->session->userdata('patient_investigation_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_investigation_data = $this->session->userdata('patient_investigation_data'); 
        if(isset($patient_investigation_data) && !empty($patient_investigation_data))
        {
          $patient_investigation = $patient_investigation_data;
        }
        else
        {
          $patient_investigation = [];
        }
        //echo $post['investigation_date'];die;
        
        if(($post['investigation_date']=="01-01-1970")||($post['investigation_date']==""))
        {
          $investigation_date = "";
        }
        else
        {
          $investigation_date = $post['investigation_date'];
        }
        
        $patient_investigation[$post['unique_id_patient_investigation']] = array('patient_investigation_id'=>$post['patient_investigation_id'], 'investigation_value'=>$post['investigation_value'], 'std_value'=>$post['std_value'], 'observed_value'=>$post['observed_value'], 'unique_id_patient_investigation'=>$post['unique_id_patient_investigation'], 'investigation_date'=>$investigation_date);
        $this->session->set_userdata('patient_investigation_data', $patient_investigation);
        $html_data = $this->patient_investigation_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

  public function get_std_value()
  {
    $investigation_id = $this->input->post('value');
    $this->load->model('gynecology/patient_template/patient_template_model','patient_template');
    $std_value = $this->patient_template->get_std_value($investigation_id);
    echo $std_value[0]->std_value;
  }

  private function patient_investigation_template_list()
    {
      $patient_investigation_data = $this->session->userdata('patient_investigation_data');
      //print_r($patient_investigation_data);die;
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
                    <th>Investigation Name</th>
                    <th>Std. Value</th>
                    <th>Observed Value</th>
                    <th>Date</th>
                  </tr></thead>';  
           if(isset($patient_investigation_data) && !empty($patient_investigation_data))
           {
              $i = 1;
              foreach($patient_investigation_data as $key=>$patient_investigation_data_rec)
              {
                if (strpos($patient_investigation_data_rec['investigation_value'], 'Select') !== false) 
                {
                    $patient_investigation_data_rec['investigation_value'] = "";
                }
                else
                {
                  $patient_investigation_data_rec['investigation_value'] = $patient_investigation_data_rec['investigation_value'];
                }
                $investigation_date='';
                if(($patient_investigation_data_rec['investigation_date']=="1970-01-01")||($patient_investigation_data_rec['investigation_date']=="") ||($patient_investigation_data_rec['investigation_date']=="0000-00-00") ||($patient_investigation_data_rec['investigation_date']=="00-00-0000"))
                {
                  $investigation_date = "";

                }
                else
                {
                  $investigation_date = date("d-m-Y",strtotime($patient_investigation_data_rec['investigation_date']));
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_investigation_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_investigation" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_investigation_data_rec['investigation_value'].'</td>
                             <td>'.$patient_investigation_data_rec['std_value'].'</td>
                             <td>'.$patient_investigation_data_rec['observed_value'].'</td>
                             <td>'.$patient_investigation_data_rec['investigation_date'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  

  function get_template_data($template_id="")
  { 
      //echo $template_id;die;
      $this->session->unset_userdata('patient_history_data');
      $this->session->unset_userdata('patient_family_history_data');
      $this->session->unset_userdata('patient_personal_history_data');
      $this->session->unset_userdata('patient_menstrual_history_data');
      $this->session->unset_userdata('patient_medical_history_data');
      $this->session->unset_userdata('patient_obestetric_history_data');
      $this->session->unset_userdata('patient_disease_data');
      $this->session->unset_userdata('patient_complaint_data');
      $this->session->unset_userdata('patient_allergy_data');
      $this->session->unset_userdata('patient_general_examination_data');
      $this->session->unset_userdata('patient_clinical_examination_data');
      $this->session->unset_userdata('patient_investigation_data'); 
      $this->session->unset_userdata('patient_advice_data');
      
    if(($template_id)>0)
     {
      $template_data= $this->gynecology_prescription->get_template_data($template_id);
     echo $template_data;
      //print_r($result);die;
     }


  }


  function load_patient_history_data($template_id="")
  {
     
    if(($template_id)>0)
    {
      $this->session->unset_userdata('patient_history_data');


      $patient_history= $this->gynecology_prescription->load_patient_history_data($template_id);
      $patient_history_data_list = [];
      $i_row = 1;
      foreach($patient_history as $patient_history_detail)
      {
        $patient_history_data_list[$i_row]= array('marriage_status'=>$patient_history_detail['marriage_status'], 'married_life_unit'=>$patient_history_detail['married_life_unit'], 'married_life_type'=>$patient_history_detail['married_life_type'],'marriage_no'=>$patient_history_detail['marriage_no'],'marriage_details'=>$patient_history_detail['marriage_details'],'previous_delivery'=>$patient_history_detail['previous_delivery'],'delivery_type'=>$patient_history_detail['delivery_type'],'delivery_details'=>$patient_history_detail['delivery_details'], 'unique_id'=>$i_row);
        $i_row++;
      }

      $this->session->set_userdata('patient_history_data',$patient_history_data_list);
      $patient_history_data_list = $this->session->userdata('patient_history_data');
      echo json_encode($patient_history_data_list);
      //print_r($result);die;
    }
    else if($template_id==0)
    { 
      $this->session->unset_userdata('patient_history_data');
    }

  }

  function load_patient_family_history_data($template_id="")
  {
     
    if(($template_id)>0)
    {
      $this->session->unset_userdata('patient_family_history_data');
      $patient_family_history= $this->gynecology_prescription->load_patient_family_history_data($template_id);
      $patient_family_history_data_list = [];
      $i_row = 1;
      foreach($patient_family_history as $patient_history_detail)
      {
          $patient_family_history_data_list[$i_row]= array('relation'=>$patient_history_detail['relation'], 'disease'=>$patient_history_detail['disease'], 'family_description'=>$patient_history_detail['family_description'],'family_duration_unit'=>$patient_history_detail['family_duration_unit'],'family_duration_type'=>$patient_history_detail['family_duration_type'],'unique_id_family_history'=>$i_row);
          $i_row++;
      }

      $this->session->set_userdata('patient_family_history_data',$patient_family_history_data_list);
      $patient_family_history_data_list = $this->session->userdata('patient_family_history_data');
      echo json_encode($patient_family_history_data_list);
    
    }
    else if($template_id==0)
    { 
      $this->session->unset_userdata('patient_family_history_data');
    }


  }

  function load_patient_personal_history_data($template_id="")
  {
     
        if(($template_id)>0)
        {
        $this->session->unset_userdata('patient_personal_history_data');
        $patient_personal= $this->gynecology_prescription->load_patient_personal_history_data($template_id);
        $patient_personal_history_data_list = [];
        $i_row = 1;
        foreach($patient_personal as $patient_personal_history)
        {
          $patient_personal_history_data_list[$i_row]= array('br_discharge'=>$patient_personal_history['br_discharge'], 'side'=>$patient_personal_history['side'], 'hirsutism'=>$patient_personal_history['hirsutism'],'white_discharge'=>$patient_personal_history['white_discharge'],'type'=>$patient_personal_history['type'],'frequency_personal'=>$patient_personal_history['frequency_personal'],'dyspareunia'=>$patient_personal_history['dyspareunia'],'personal_details'=>$patient_personal_history['personal_details'], 'unique_id_personal_history'=>$i_row);
          $i_row++;
        }

        $this->session->set_userdata('patient_personal_history_data',$patient_personal_history_data_list);
        $patient_personal_history_data_list = $this->session->userdata('patient_personal_history_data');
        echo json_encode($patient_personal_history_data_list);
        
    }
    else if($template_id==0)
    { 
      $this->session->unset_userdata('patient_personal_history_data');
    }

  }
  

  function load_patient_menstrual_history_data($template_id="")
  {
     
        if(($template_id)>0)
        {
        $this->session->unset_userdata('patient_menstrual_history_data');

        $patient_menstrual= $this->gynecology_prescription->load_patient_menstrual_history_data($template_id);
        $patient_menstrual_history_data_list = [];
        $i_row = 1;
        foreach($patient_menstrual as $patient_menstrual_history)
        {
          
          //print_r($patient_menstrual_history);
        $lmp_date='';
        if(!empty($patient_menstrual_history['lmp_date']))
        {
          if(!empty($patient_menstrual_history['lmp_date']) && $patient_menstrual_history['lmp_date']!='0000-00-00')
                  {
                    $lmp_date = date('d-m-Y',strtotime($patient_menstrual_history['lmp_date']));
                  }
        }

          $patient_menstrual_history_data_list[$i_row]= array('previous_cycle'=>$patient_menstrual_history['previous_cycle'], 'prev_cycle_type'=>$patient_menstrual_history['prev_cycle_type'], 'present_cycle'=>$patient_menstrual_history['present_cycle'],'present_cycle_type'=>$patient_menstrual_history['present_cycle_type'],'cycle_details'=>$patient_menstrual_history['cycle_details'],'lmp_date'=>$lmp_date,'dysmenorrhea'=>$patient_menstrual_history['dysmenorrhea'],'dysmenorrhea_type'=>$patient_menstrual_history['dysmenorrhea_type'], 'unique_id_menstrual_history'=>$i_row);
          $i_row++;
        
        }

        $this->session->set_userdata('patient_menstrual_history_data',$patient_menstrual_history_data_list);
        $patient_menstrual_history_data_list = $this->session->userdata('patient_menstrual_history_data');
        echo json_encode($patient_menstrual_history_data_list);
        
    }
    else if($template_id==0)
    { 
      $this->session->unset_userdata('patient_menstrual_history_data');
    }

  }


        function load_patient_medical_history_data($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_medical_history_data');
              $patient_medical= $this->gynecology_prescription->load_patient_medical_history_data($template_id);
              $patient_medical_history_data_list = [];
              $i_row = 1;
              foreach($patient_medical as $patient_medical_history)
              {
                $patient_medical_history_data_list[$i_row]= array('tb'=>$patient_medical_history['tb'], 'tb_rx'=>$patient_medical_history['tb_rx'], 'dm'=>$patient_medical_history['dm'],'dm_years'=>$patient_medical_history['dm_years'],'dm_rx'=>$patient_medical_history['dm_rx'],'ht'=>$patient_medical_history['ht'],'medical_details'=>$patient_medical_history['medical_details'],'medical_others'=>$patient_medical_history['medical_others'],'unique_id_medical_history'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_medical_history_data',$patient_medical_history_data_list);
              $patient_medical_history_data_list = $this->session->userdata('patient_medical_history_data');
              echo json_encode($patient_medical_history_data_list);
              
              }

        }


        function load_patient_obestetric_history_data($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_obestetric_history_data');
              $patient_obestetric= $this->gynecology_prescription->load_patient_obestetric_history_data($template_id);
              $patient_obestetric_history_data_list = [];
              $i_row = 1;
              foreach($patient_obestetric as $patient_obestetric_history)
              {
                $patient_obestetric_history_data_list[$i_row]= array('obestetric_g'=>$patient_obestetric_history['obestetric_g'], 'obestetric_p'=>$patient_obestetric_history['obestetric_p'], 'obestetric_l'=>$patient_obestetric_history['obestetric_l'],'obestetric_mtp'=>$patient_obestetric_history['obestetric_mtp'],'unique_id_obestetric_history'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_obestetric_history_data',$patient_obestetric_history_data_list);
              $patient_obestetric_history_data_list = $this->session->userdata('patient_obestetric_history_data');
              echo json_encode($patient_obestetric_history_data_list);
              
          }
          else if($template_id==0)
          { 
            $this->session->unset_userdata('patient_obestetric_history_data');
          }

        }


        function load_disease_values($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_disease_data');
              $patient_disease= $this->gynecology_prescription->load_disease_values($template_id);
              $patient_disease_history_data_list = [];
              $i_row = 1;
              foreach($patient_disease as $patient_disease_history)
              {
                
                $patient_disease_history_data_list[$i_row]= array('patient_disease_id'=>$patient_disease_history['patient_disease_id'],'disease_value'=>$patient_disease_history['patient_disease_name'], 'patient_disease_unit'=>$patient_disease_history['patient_disease_unit'], 'patient_disease_type'=>$patient_disease_history['patient_disease_type'],'disease_description'=>$patient_disease_history['disease_description'],'unique_id_patient_disease'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_disease_data',$patient_disease_history_data_list);
              $patient_disease_history_data_list = $this->session->userdata('patient_disease_data');
              echo json_encode($patient_disease_history_data_list);
              
            }
            else if($template_id==0)
            { 
              $this->session->unset_userdata('patient_disease_data');
            }

        }


         function load_complaints_values($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_complaint_data');


              $patient_complaint= $this->gynecology_prescription->load_complaints_values($template_id);
              $patient_complaint_history_data_list = [];
              $i_row = 1;
              foreach($patient_complaint as $patient_complaint_history)
              {
                
                $patient_complaint_history_data_list[$i_row]= array('patient_complaint_id'=>$patient_complaint_history['patient_complaint_id'],'complaint_value'=>$patient_complaint_history['patient_complaint_name'], 'patient_complaint_type'=>$patient_complaint_history['patient_complaint_type'], 'patient_complaint_unit'=>$patient_complaint_history['patient_complaint_unit'],'complaint_description'=>$patient_complaint_history['complaint_description'],'unique_id_patient_complaint'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_complaint_data',$patient_complaint_history_data_list);
              $patient_complaint_history_data_list = $this->session->userdata('patient_complaint_data');
              echo json_encode($patient_complaint_history_data_list);
              
          }
          else if($template_id==0)
          { 
            $this->session->unset_userdata('patient_complaint_data');
          }

        }


         function load_allergy_values($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_allergy_data');


              $patient_allergy= $this->gynecology_prescription->load_allergy_values($template_id);
              $patient_allergy_data_list = [];
              $i_row = 1;
              foreach($patient_allergy as $patient_allergy_history)
              {
                

              
                $patient_allergy_data_list[$i_row]= array('patient_allergy_name'=>$patient_allergy_history['patient_allergy_name'] ,'patient_allergy_id'=>$patient_allergy_history['patient_allergy_id'],'allergy_value'=>$patient_allergy_history['patient_allergy_name'], 'patient_allergy_type'=>$patient_allergy_history['patient_allergy_type'], 'patient_allergy_unit'=>$patient_allergy_history['patient_allergy_unit'],'allergy_description'=>$patient_allergy_history['allergy_description'],'unique_id_patient_allergy'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_allergy_data',$patient_allergy_data_list);
              $patient_allergy_data_list = $this->session->userdata('patient_allergy_data');
              echo json_encode($patient_allergy_data_list);
              
            }
            else if($template_id==0)
            { 
              $this->session->unset_userdata('patient_allergy_data');
            }

        }


        function load_general_examination_values($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_general_examination_data');


              $patient_general= $this->gynecology_prescription->load_general_examination_values($template_id);
              $patient_general_history_data_list = [];
              $i_row = 1;
              foreach($patient_general as $patient_general_history)
              {
                $patient_general_history_data_list[$i_row]= array('patient_general_examination_name'=>$patient_general_history['patient_general_examination_name'], 'general_examination_value'=>$patient_general_history['patient_general_examination_name'], 'patient_general_examination_id'=>$patient_general_history['patient_general_examination_id'], 'patient_general_examination_unit'=>$patient_general_history['patient_general_examination_unit'], 'patient_general_examination_type'=>$patient_general_history['patient_general_examination_type'],'general_examination_description'=>$patient_general_history['general_examination_description'],'unique_id_patient_general_examination'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_general_examination_data',$patient_general_history_data_list);
              $patient_general_history_data_list = $this->session->userdata('patient_general_examination_data');
              echo json_encode($patient_general_history_data_list);
              
            }
            else if($template_id==0)
            { 
              $this->session->unset_userdata('patient_general_examination_data');
            }

        }


        function load_clinical_examination_values($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_clinical_examination_data');


              $clinical= $this->gynecology_prescription->load_clinical_examination_values($template_id);
              $clinical_examination_data_list = [];
              $i_row = 1;
              foreach($clinical as $clinical_examination)
              {
                $clinical_examination_data_list[$i_row]= array('patient_clinical_examination_name'=>$clinical_examination['patient_clinical_examination_name'], 'clinical_examination_value'=>$clinical_examination['patient_clinical_examination_name'], 'patient_clinical_examination_id'=>$clinical_examination['patient_clinical_examination_id'], 'patient_clinical_examination_unit'=>$clinical_examination['patient_clinical_examination_unit'], 'patient_clinical_examination_type'=>$clinical_examination['patient_clinical_examination_type'],'clinical_examination_description'=>$clinical_examination['clinical_examination_description'],'unique_id_patient_clinical_examination'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_clinical_examination_data',$clinical_examination_data_list);
              $clinical_examination_data_list = $this->session->userdata('patient_clinical_examination_data');
              echo json_encode($clinical_examination_data_list);
              
            }
            else if($template_id==0)
            { 
              $this->session->unset_userdata('patient_clinical_examination_data');
            }

        }

        function load_investigation_values($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_investigation_data');

              $investigation= $this->gynecology_prescription->load_investigation_values($template_id);
              $investigation_data_list = [];
              $i_row = 1;
              foreach($investigation as $investigation_data)
              {
                $investigation_date='';
                if(!empty($investigation_data['investigation_date']))
                {
                  if(!empty($investigation_data['investigation_date']) && $investigation_data['investigation_date']!='0000-00-00' && $investigation_data['investigation_date']!='00-00-0000' && $investigation_data['investigation_date']!='')
                  {
                    $investigation_date = date('d-m-Y',strtotime($investigation_data['investigation_date']));
                  }
                  else
                  {
                    $investigation_date="";
                  }
                }
                $investigation_data_list[$i_row]= array('investigation_value'=>$investigation_data['patient_investigation_name'], 'patient_investigation_id'=>$investigation_data['patient_investigation_id'], 'std_value'=>$investigation_data['std_value'], 'observed_value'=>$investigation_data['observed_value'], 'unique_id_patient_investigation'=>$i_row , 'investigation_date'=>$investigation_date);
                $i_row++;
              }

              $this->session->set_userdata('patient_investigation_data',$investigation_data_list);
              $investigation_data_list = $this->session->userdata('patient_investigation_data');
              echo json_encode($investigation_data_list);
              
            }
            else if($template_id==0)
            { 
              $this->session->unset_userdata('patient_investigation_data');
            }

        }


        function load_advice_values($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_advice_data');


              $patient_advice= $this->gynecology_prescription->load_advice_values($template_id);
              $patient_advice_data_list = [];
              $i_row = 1;
              foreach($patient_advice as $patient_advice_data)
              {
                $patient_advice_data_list[$i_row]= array('advice_value'=>$patient_advice_data['patient_advice_name'], 'unique_id_patient_advice'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_advice_data',$patient_advice_data_list);
              $patient_advice_data_list = $this->session->userdata('patient_advice_data');
              echo json_encode($patient_advice_data_list);
              
            }
            else if($template_id==0)
            { 
              $this->session->unset_userdata('patient_advice_data');
            }


        }

        function get_medicine_data($template_id="")
        {
          if(($template_id)>0)
          {
            $previous_medicine_data= $this->gynecology_prescription->get_medicine_data($template_id);
            echo json_encode($previous_medicine_data);
          }
        }


        function get_tabing_medicine_data($template_id="")
        {
          if(($template_id)>0)
          {
            $medicine_data= $this->gynecology_prescription->get_tabing_medicine_data($template_id);
            echo json_encode($medicine_data);

          }

        }

     public function delete_gynic($id="")
      {
         //unauthorise_permission(248,1411);
         if(!empty($id) && $id>0)
         {
             $result = $this->gynecology_prescription->delete_gynic($id);
             $response = "Prescription successfully deleted.";
             echo $response;
         }
      }

    public function view_prescription($id,$branch_id='')
    { 
        //unauthorise_permission(248,1448);
     
        $data['form_data'] = $this->gynecology_prescription->get_by_ids($id);
        $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting('',$branch_id);
        $data['page_title'] = $data['form_data']['patient_name']." Prescription";
        $data['id'] = $id;
        $dental_prescription_info = $this->gynecology_prescription->get_detail_by_prescription_id($id);
        $data['all_detail']= $dental_prescription_info;
        $opd_booking_id=$data['all_detail']['prescription_list'][0]->booking_id;
        $data['prescription_patient_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();

        $this->load->model('opd/opd_model','opd');
        $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
        $patient_id=$data['booking_data']['patient_id'];      
        $this->load->view('gynecology/gynecology_prescription/print_template',$data);
        $html = $this->output->get_output();
    }
    




        public function print_prescriptions($prescription_id="",$branch_id='')
        {

        //unauthorise_permission(248,1449);
        //print_r($prescription_id);
        $prescriptions_id= $this->session->userdata('prescription_id');
        if(!empty($prescriptions_id))
        {
          $prescription_id = $prescriptions_id;
        }
        else
        {
          $prescription_id =$prescription_id;
        }

        $data['type'] = 3;
        $data['prescription_id'] = $prescription_id;
        $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->gynecology_prescription->get_detail_by_prescription_id($prescription_id);
        //echo"<pre>";print_r($opd_prescription_info);

        $data['prescription_patient_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();

        $template_format = $this->gynecology_prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);

        $template_format_left = $this->gynecology_prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->gynecology_prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->gynecology_prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->gynecology_prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);

        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;

        $data['template_data']=$template_format->setting_value;

        $data['all_detail']= $opd_prescription_info;

        $opd_booking_id=$data['all_detail']['prescription_list'][0]->booking_id;
        $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting('',$branch_id);

        $this->load->model('opd/opd_model','opd');
        $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
        $patient_id=$data['booking_data']['patient_id'];

         $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();

        $data['prescription_id'] = $prescription_id;
        $this->load->view('gynecology/gynecology_prescription/print_prescription_template',$data);
        }


    // functions to delete table record from session   
    public function remove_gynecology_patient_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_history_vals'=>explode(',', $post['patient_history_vals']));
        if(isset($data['patient_history_vals']) && !empty($data['patient_history_vals']))
        {
          $patient_history_data = $this->session->userdata('patient_history_data');
          $patient_history_id_list = array_column($patient_history_data, 'unique_id');
          foreach($patient_history_data as $key=>$patient_history_ids)
          {
            if(in_array($patient_history_ids['unique_id'],$data['patient_history_vals']))
            {
               unset($patient_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_history_data',$patient_history_data);
          $html_data = $this->patient_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_family_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_family_history_vals'=>explode(',', $post['patient_family_history_vals']));
        if(isset($data['patient_family_history_vals']) && !empty($data['patient_family_history_vals']))
        {
          $patient_family_history_data = $this->session->userdata('patient_family_history_data');
          $patient_family_history_id_list = array_column($patient_family_history_data, 'unique_id_family_history');
          foreach($patient_family_history_data as $key=>$patient_family_history_ids)
          {
            if(in_array($patient_family_history_ids['unique_id_family_history'],$data['patient_family_history_vals']))
            {
               unset($patient_family_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_family_history_data',$patient_family_history_data);
          $html_data = $this->patient_family_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_personal_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_personal_history_vals'=>explode(',', $post['patient_personal_history_vals']));
        if(isset($data['patient_personal_history_vals']) && !empty($data['patient_personal_history_vals']))
        {
          $patient_personal_history_data = $this->session->userdata('patient_personal_history_data');
          $patient_personal_history_id_list = array_column($patient_personal_history_data, 'unique_id_personal_history');
          foreach($patient_personal_history_data as $key=>$patient_personal_history_ids)
          {
            if(in_array($patient_personal_history_ids['unique_id_personal_history'],$data['patient_personal_history_vals']))
            {
               unset($patient_personal_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_personal_history_data',$patient_personal_history_data);
          $html_data = $this->patient_personal_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_menstrual_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_menstrual_history_vals'=>explode(',', $post['patient_menstrual_history_vals']));
        if(isset($data['patient_menstrual_history_vals']) && !empty($data['patient_menstrual_history_vals']))
        {
          $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
          $patient_menstrual_history_id_list = array_column($patient_menstrual_history_data, 'unique_id_menstrual_history');
          foreach($patient_menstrual_history_data as $key=>$patient_menstrual_history_ids)
          {
            if(in_array($patient_menstrual_history_ids['unique_id_menstrual_history'],$data['patient_menstrual_history_vals']))
            {
               unset($patient_menstrual_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_menstrual_history_data',$patient_menstrual_history_data);
          $html_data = $this->patient_menstrual_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_medical_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_medical_history_vals'=>explode(',', $post['patient_medical_history_vals']));
        if(isset($data['patient_medical_history_vals']) && !empty($data['patient_medical_history_vals']))
        {
          $patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
          $patient_medical_history_id_list = array_column($patient_medical_history_data, 'unique_id_medical_history');
          foreach($patient_medical_history_data as $key=>$patient_medical_history_ids)
          {
            if(in_array($patient_medical_history_ids['unique_id_medical_history'],$data['patient_medical_history_vals']))
            {
               unset($patient_medical_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_medical_history_data',$patient_medical_history_data);
          $html_data = $this->patient_medical_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_obestetric_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_obestetric_history_vals'=>explode(',', $post['patient_obestetric_history_vals']));
        if(isset($data['patient_obestetric_history_vals']) && !empty($data['patient_obestetric_history_vals']))
        {
          $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
          $patient_obestetric_history_id_list = array_column($patient_obestetric_history_data, 'unique_id_obestetric_history');
          foreach($patient_obestetric_history_data as $key=>$patient_obestetric_history_ids)
          {
            if(in_array($patient_obestetric_history_ids['unique_id_obestetric_history'],$data['patient_obestetric_history_vals']))
            {
               unset($patient_obestetric_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_obestetric_history_data',$patient_obestetric_history_data);
          $html_data = $this->patient_obestetric_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_disease()
    {    
        $post =  $this->input->post();
        $data = array('patient_disease_vals'=>explode(',', $post['patient_disease_vals']));
        if(isset($data['patient_disease_vals']) && !empty($data['patient_disease_vals']))
        {
          $patient_disease_data = $this->session->userdata('patient_disease_data');
          $patient_disease_id_list = array_column($patient_disease_data, 'unique_id_patient_disease');
          foreach($patient_disease_data as $key=>$patient_disease_ids)
          {
            if(in_array($patient_disease_ids['unique_id_patient_disease'],$data['patient_disease_vals']))
            {
               unset($patient_disease_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_disease_data',$patient_disease_data);
          $html_data = $this->patient_disease_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_complaint()
    {    
        $post =  $this->input->post();
        $data = array('patient_complaint_vals'=>explode(',', $post['patient_complaint_vals']));
        if(isset($data['patient_complaint_vals']) && !empty($data['patient_complaint_vals']))
        {
          $patient_complaint_data = $this->session->userdata('patient_complaint_data');
          $patient_complaint_id_list = array_column($patient_complaint_data, 'unique_id_patient_complaint');
          foreach($patient_complaint_data as $key=>$patient_complaint_ids)
          {
            if(in_array($patient_complaint_ids['unique_id_patient_complaint'],$data['patient_complaint_vals']))
            {
               unset($patient_complaint_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_complaint_data',$patient_complaint_data);
          $html_data = $this->patient_complaint_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_allergy()
    {    
        $post =  $this->input->post();
        $data = array('patient_allergy_vals'=>explode(',', $post['patient_allergy_vals']));
        if(isset($data['patient_allergy_vals']) && !empty($data['patient_allergy_vals']))
        {
          $patient_allergy_data = $this->session->userdata('patient_allergy_data');
          $patient_allergy_id_list = array_column($patient_allergy_data, 'unique_id_patient_allergy');
          foreach($patient_allergy_data as $key=>$patient_allergy_ids)
          {
            if(in_array($patient_allergy_ids['unique_id_patient_allergy'],$data['patient_allergy_vals']))
            {
               unset($patient_allergy_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_allergy_data',$patient_allergy_data);
          $html_data = $this->patient_allergy_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_general_examination()
    {    
        $post =  $this->input->post();
        $data = array('patient_general_examination_vals'=>explode(',', $post['patient_general_examination_vals']));
        if(isset($data['patient_general_examination_vals']) && !empty($data['patient_general_examination_vals']))
        {
          $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
          $patient_general_examination_id_list = array_column($patient_general_examination_data, 'unique_id_patient_general_examination');
          foreach($patient_general_examination_data as $key=>$patient_general_examination_ids)
          {
            if(in_array($patient_general_examination_ids['unique_id_patient_general_examination'],$data['patient_general_examination_vals']))
            {
               unset($patient_general_examination_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_general_examination_data',$patient_general_examination_data);
          $html_data = $this->patient_general_examination_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_clinical_examination()
    {    
        $post =  $this->input->post();
        $data = array('patient_clinical_examination_vals'=>explode(',', $post['patient_clinical_examination_vals']));
        if(isset($data['patient_clinical_examination_vals']) && !empty($data['patient_clinical_examination_vals']))
        {
          $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
          $patient_clinical_examination_id_list = array_column($patient_clinical_examination_data, 'unique_id_patient_clinical_examination');
          foreach($patient_clinical_examination_data as $key=>$patient_clinical_examination_ids)
          {
            if(in_array($patient_clinical_examination_ids['unique_id_patient_clinical_examination'],$data['patient_clinical_examination_vals']))
            {
               unset($patient_clinical_examination_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_clinical_examination_data',$patient_clinical_examination_data);
          $html_data = $this->patient_clinical_examination_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_investigation()
    {    
        $post =  $this->input->post();
        $data = array('patient_investigation_vals'=>explode(',', $post['patient_investigation_vals']));
        if(isset($data['patient_investigation_vals']) && !empty($data['patient_investigation_vals']))
        {
          $patient_investigation_data = $this->session->userdata('patient_investigation_data');
          $patient_investigation_id_list = array_column($patient_investigation_data, 'unique_id_patient_investigation');
          foreach($patient_investigation_data as $key=>$patient_investigation_ids)
          {
            if(in_array($patient_investigation_ids['unique_id_patient_investigation'],$data['patient_investigation_vals']))
            {
               unset($patient_investigation_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_investigation_data',$patient_investigation_data);
          $html_data = $this->patient_investigation_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_advice()
    {    
        $post =  $this->input->post();
        $data = array('patient_advice_vals'=>explode(',', $post['patient_advice_vals']));
        if(isset($data['patient_advice_vals']) && !empty($data['patient_advice_vals']))
        {
          $patient_advice_data = $this->session->userdata('patient_advice_data');
          $patient_advice_id_list = array_column($patient_advice_data, 'unique_id_patient_advice');
          foreach($patient_advice_data as $key=>$patient_advice_ids)
          {
            if(in_array($patient_advice_ids['unique_id_patient_advice'],$data['patient_advice_vals']))
            {
               unset($patient_advice_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_advice_data',$patient_advice_data);
          $html_data = $this->patient_advice_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function print_blank_prescriptions($booking_id="",$branch_id='')
    {
        //unauthorise_permission(248,1420);
        $data['page_title'] = "Print Prescription";
        //$opd_prescription_info = $this->gynecology_prescription->get_detail_by_prescription_id($booking_id);
        //print_r($opd_prescription_info);die;
        $opd_prescription_info = $this->gynecology_prescription->get_detail_by_booking_id($booking_id,$branch_id);
        $template_format = $this->gynecology_prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>1),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->gynecology_prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>1),$branch_id);
        $template_format_right = $this->gynecology_prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>1),$branch_id);
        $template_format_top = $this->gynecology_prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>1));
        $template_format_bottom = $this->gynecology_prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>1),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
        $data['prescription_patient_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $data['prescription_id'] = $booking_id;
        $opd_booking_id=$booking_id;
        $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting('',$branch_id);

        $this->load->model('opd/opd_model','opd');
        $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
        $patient_id=$data['booking_data']['patient_id'];
        $data['type'] = 1;

         $this->load->view('gynecology/gynecology_prescription/print_prescription_template',$data);
    }
}

?>