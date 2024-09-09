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
      $this->session->unset_userdata('patient_icsilab_data');

      
       
      
      
  } 



  public function add_gynecology_prescription($booking_id='',$prescription_id="",$opd_booking_id="")
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
      $this->session->unset_userdata('patient_right_ovary_data');
      $this->session->unset_userdata('patient_left_ovary_data');
      $this->session->unset_userdata('patient_icsilab_data');
      $this->session->unset_userdata('patient_antenatal_care_data'); 
      $this->session->unset_userdata('fertility_data'); 

      

      
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
                                "common_fertility_risk"=>"",
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
                                'confirm_delivery_date'=>'',
                                'temp'=>'',
                                'bp'=>'',
                                'pulse'=>'',
                                'vital_dm'=>'',
                                'bloodgroup'=>'',
                                'booking_time'=>$booking_time,
                                'booking_date'=>$booking_date,
                                'print_patient_history_flag'=>'0',
                                'print_disease_flag'=>'0',
                                'print_complaints_flag'=>'0',
                                'print_allergy_flag'=>'0',
                                'print_general_examination_flag'=>'0',
                                'print_clinical_examination_flag'=>'0',
                                'print_investigations_flag'=>'0',
                                'print_medicine_flag'=>'0',
                                'print_advice_flag'=>'0',
                                'print_next_app_flag'=>'0',
                                'print_gpla_flag'=>'0',
                                'print_follicular_flag'=>'0',
                                'print_icsilab_flag'=>'0',
                                'print_fertility_flag'=>'0',
                                'print_antenatal_flag'=>'0',
                                'patient_fertillity_risk'=>'',
                                
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

      //case when select from old ids

      if(!empty($opd_booking_id) && !empty($prescription_id))
      {
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
          //left
          $right_ovary_db_data = $this->gynecology_prescription->get_gynecology_right_ovary_list($prescription_id, $opd_booking_id);
          $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data');
          if(!isset($patient_right_ovary_data))
          {
            if(!empty($right_ovary_db_data))
            { 
                $i_row = 1;
                $phd_row = []; 
                foreach($right_ovary_db_data as $right_data)
                {

                  $phd_row[$i_row] = array('right_folli_date'=>$right_data['right_folli_date'], 'right_folli_day'=>$right_data['right_folli_day'],'right_folli_protocol'=>$right_data['right_folli_protocol'],'right_folli_pfsh'=>$right_data['right_folli_pfsh'],'right_folli_recfsh'=>$right_data['right_folli_recfsh'],'right_folli_hmg'=>$right_data['right_folli_hmg'],'right_folli_hp_hmg'=>$right_data['right_folli_hp_hmg'],'right_folli_agonist'=>$right_data['right_folli_agonist'],'right_folli_antiagonist'=>$right_data['right_folli_antiagonist'],'right_folli_trigger'=>$right_data['right_folli_trigger'],'right_follic_size'=>$right_data['right_follic_size'],'unique_id'=>$i_row);


             
                 $i_row++;
                } 
                $this->session->set_userdata('patient_right_ovary_data',$phd_row);
            }
          }
          ///right
          $left_ovary_db_data = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id);
          $patient_left_ovary_data = $this->session->userdata('patient_left_ovary_data');
          if(!isset($patient_left_ovary_data))
          {
            if(!empty($left_ovary_db_data))
            { 
                $i_row = 1;
                $phd_row = []; 
                foreach($left_ovary_db_data as $left_data)
                {

                   $phd_row[$i_row] = array('left_folli_date'=>$left_data['left_folli_date'], 'left_folli_day'=>$left_data['left_folli_day'],'left_folli_protocol'=>$left_data['left_folli_protocol'],'left_folli_pfsh'=>$left_data['left_folli_pfsh'],'left_folli_recfsh'=>$left_data['left_folli_recfsh'],'left_folli_hmg'=>$left_data['left_folli_hmg'],'left_folli_hp_hmg'=>$left_data['left_folli_hp_hmg'],'left_folli_agonist'=>$left_data['left_folli_agonist'],'left_folli_antiagonist'=>$left_data['left_folli_antiagonist'],'left_folli_trigger'=>$left_data['left_folli_trigger'],'left_follic_size'=>$left_data['left_follic_size'],'endometriumothers'=>$left_data['endometriumothers'],'e2'=>$left_data['e2'],'p4'=>$left_data['p4'],'risk'=>$left_data['risk'],'others'=>$left_data['others'],'unique_id'=>$i_row);

                
                 $i_row++;
                } 
                $this->session->set_userdata('patient_left_ovary_data',$phd_row);
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
          


           $patient_gpla_db_data = $this->gynecology_prescription->get_patient_gpla_list($prescription_id, $opd_booking_id);
          $patient_investigation_data = $this->session->userdata('patient_gpla_data');
          if(!isset($patient_investigation_data))
          {
            if(!empty($patient_gpla_db_data))
            { 
                $i_row = 1;
                $phd_row = []; 
                foreach($patient_gpla_db_data as $patient_gpla_data)
                {
                       $phd_row[$i_row] = array('patient_gpla_id'=>$patient_gpla_data['patient_gpla_id'], 'dog_value'=>$patient_gpla_data['dog_value'], 'mode_value'=>$patient_gpla_data['mode_value'], 'monthyear_value'=>$patient_gpla_data['monthyear_value'], 'unique_id_patient_gpla'=>$i_row);

                      $i_row++;
                } 
                $this->session->set_userdata('patient_gpla_data',$phd_row);
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

          //icsilab 

          $patient_icsilab_db_data = $this->gynecology_prescription->get_patient_icsilab_list($prescription_id, $opd_booking_id);
          $patient_icsilab_data = $this->session->userdata('patient_icsilab_data');

          if(!isset($patient_icsilab_data))
          {
            if(!empty($patient_icsilab_db_data))
            { 
                $i_row = 1;
                $phd_row = []; 
                foreach($patient_icsilab_db_data as $patient_icsilab_data)
                {
                    $phd_row[$i_row] = array('icsilab_date'=>$patient_icsilab_data['icsilab_date'], 'oocytes'=>$patient_icsilab_data['oocytes'], 'm2'=>$patient_icsilab_data['m2'],'injected'=>$patient_icsilab_data['injected'],'cleavge'=>$patient_icsilab_data['cleavge'],'embryos_day3'=>$patient_icsilab_data['embryos_day3'],'day5'=>$patient_icsilab_data['day5'],'day_of_et'=>$patient_icsilab_data['day_of_et'],'et'=>$patient_icsilab_data['et'],'vit'=>$patient_icsilab_data['vit'],'lah'=>$patient_icsilab_data['lah'],'semen'=>$patient_icsilab_data['semen'],'count'=>$patient_icsilab_data['count'],'motility'=>$patient_icsilab_data['motility'],'g3'=>$patient_icsilab_data['g3'],'abn_form'=>$patient_icsilab_data['abn_form'],'imsi'=>$patient_icsilab_data['imsi'],'pregnancy'=>$patient_icsilab_data['pregnancy'],'remarks'=>$patient_icsilab_data['remarks'],'unique_id_patient_icsilab'=>$i_row);

                 $i_row++;
                } 
                $this->session->set_userdata('patient_icsilab_data',$phd_row);
            }
          }

          //icsilab

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

          $patient_fertility_data = $this->gynecology_prescription->get_fertility_list($prescription_id, $opd_booking_id);

        //echo "<pre>"; print_r($data['patient_fertility_data']); exit;
        $data['fertility_co'] = $patient_fertility_data[0]->fertility_co;
        $data['fertility_uterine_factor'] = $patient_fertility_data[0]->fertility_uterine_factor;
        $data['fertility_tubal_factor'] = $patient_fertility_data[0]->fertility_tubal_factor;
        $data['fertility_uploadhsg'] = $patient_fertility_data[0]->fertility_uploadhsg;
        $data['fertility_laparoscopy'] = $patient_fertility_data[0]->fertility_laparoscopy;
        $data['fertility_risk'] = $patient_fertility_data[0]->fertility_risk;
        $data['fertility_decision'] = $patient_fertility_data[0]->fertility_decision;
        $data['fertility_ovarian_factor'] = $patient_fertility_data[0]->fertility_ovarian_factor;
        $data['fertility_ultrasound_images'] = $patient_fertility_data[0]->fertility_ultrasound_images;
        $data['fertility_male_factor'] = $patient_fertility_data[0]->fertility_male_factor;
        $data['fertility_sperm_date'] = $patient_fertility_data[0]->fertility_sperm_date;
        $data['fertility_sperm_count'] = $patient_fertility_data[0]->fertility_sperm_count;
        $data['fertility_sperm_motality'] = $patient_fertility_data[0]->fertility_sperm_motality;
        $data['fertility_sperm_g3'] = $patient_fertility_data[0]->fertility_sperm_g3;
        $data['fertility_sperm_abnform'] = $patient_fertility_data[0]->fertility_sperm_abnform;
        $data['fertility_sperm_remarks'] = $patient_fertility_data[0]->fertility_sperm_remarks;

      }

      ///end of select case
        
    
      $this->load->model('next_appointment/Next_appointment_model', 'next_appointment_master');
      $data['next_appointment_list'] = $this->next_appointment_master->list();
    
      $this->load->view('gynecology/gynecology_prescription/add',$data);

  }



   public function edit($prescription_id="",$opd_booking_id="")
    {  
      $users_data = $this->session->userdata('auth_users');
      /*$this->session->unset_userdata('patient_left_ovary_data');
      $this->session->unset_userdata('patient_right_ovary_data');*/
      //$this->session->unset_userdata('fertility_data');
      //unauthorise_permission(248,1410);
      //fertility
    
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
      $this->session->unset_userdata('patient_right_ovary_data');
      $this->session->unset_userdata('patient_left_ovary_data');
      $this->session->unset_userdata('patient_icsilab_data');
      $this->session->unset_userdata('patient_antenatal_care_data'); 
      $this->session->unset_userdata('fertility_data'); 
      $this->session->unset_userdata('patient_gpla_data'); 

      

      
     }
    
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
        //  $data['investigation_list'] = $this->prescription_template->gynecology_investigation_list(); 
         $data['investigation_list'] = $this->get_test_vals();
         
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
        
        $patient_fertillity_risk = $this->gynecology_prescription->get_last_risk($prescription_id, $opd_booking_id);
        
        
        $data['patient_fertillity_risk'] = $patient_fertillity_risk;
        
        if(isset($post) && !empty($post))
          {   
            $prescription_id=$this->gynecology_prescription->save();
            $this->session->set_userdata('gynec_prescription_id',$prescription_id);
            $this->session->set_flashdata('success','Prescription updated successfully.');
            redirect(base_url('prescription/?status=print_gynecology'));   
          }
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
        //left
        $data['right_ovary_data'] = $this->gynecology_prescription->get_gynecology_right_ovary_list($prescription_id, $opd_booking_id,1);
        $right_ovary_db_data = $this->gynecology_prescription->get_gynecology_right_ovary_list($prescription_id, $opd_booking_id,0);
        //print_r($right_ovary_db_data);die;
        $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data');
        if(!isset($patient_right_ovary_data))
        {
          if(!empty($right_ovary_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($right_ovary_db_data as $right_data)
              {

                $phd_row[$i_row] = array('right_follic_size'=>$right_data['right_follic_size'],'left_follic_size'=>$right_data['left_follic_size'],'unique_id'=>$i_row); 
               $i_row++;
              } 
              $this->session->set_userdata('patient_right_ovary_data',$phd_row);
          }
        }
        
        
       
              
        
        
        ///right
        $data['left_ovary_data'] = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,1);
        
        
        $data['left_ovary_data_edit'] = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,1);
        
        
       // echo "<pre>"; print_r($data['left_ovary_data']); exit;
        
        $left_ovary_db_data = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,0);
        /*$patient_left_ovary_data = $this->session->userdata('patient_left_ovary_data');
        if(!isset($patient_left_ovary_data))
        {
          if(!empty($left_ovary_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($left_ovary_db_data as $left_data)
              {

                 $phd_row[$i_row] = array('left_follic_size'=>$left_data['left_follic_size'],'unique_id'=>$i_row); 
               $i_row++;
              } 
              $this->session->set_userdata('patient_left_ovary_data',$phd_row);
          }
        }*/

        
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
        


         $patient_gpla_db_data = $this->gynecology_prescription->get_patient_gpla_list($prescription_id, $opd_booking_id);
        $patient_investigation_data = $this->session->userdata('patient_gpla_data');
        if(!isset($patient_investigation_data))
        {
          if(!empty($patient_gpla_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_gpla_db_data as $patient_gpla_data)
              {
                     $phd_row[$i_row] = array('patient_gpla_id'=>$patient_gpla_data['patient_gpla_id'], 'dog_value'=>$patient_gpla_data['dog_value'], 'mode_value'=>$patient_gpla_data['mode_value'], 'monthyear_value'=>$patient_gpla_data['monthyear_value'], 'unique_id_patient_gpla'=>$i_row);

                    $i_row++;
              } 
              $this->session->set_userdata('patient_gpla_data',$phd_row);
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

        //icsilab 

        $patient_icsilab_db_data = $this->gynecology_prescription->get_patient_icsilab_list($prescription_id, $opd_booking_id);
        $patient_icsilab_data = $this->session->userdata('patient_icsilab_data');

        if(!isset($patient_icsilab_data))
        {
          if(!empty($patient_icsilab_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_icsilab_db_data as $patient_icsilab_data)
              {
                  $phd_row[$i_row] = array('icsilab_date'=>$patient_icsilab_data['icsilab_date'], 'oocytes'=>$patient_icsilab_data['oocytes'], 'm2'=>$patient_icsilab_data['m2'],'injected'=>$patient_icsilab_data['injected'],'cleavge'=>$patient_icsilab_data['cleavge'],'embryos_day3'=>$patient_icsilab_data['embryos_day3'],'day5'=>$patient_icsilab_data['day5'],'day_of_et'=>$patient_icsilab_data['day_of_et'],'et'=>$patient_icsilab_data['et'],'vit'=>$patient_icsilab_data['vit'],'lah'=>$patient_icsilab_data['lah'],'semen'=>$patient_icsilab_data['semen'],'count'=>$patient_icsilab_data['count'],'motility'=>$patient_icsilab_data['motility'],'g3'=>$patient_icsilab_data['g3'],'abn_form'=>$patient_icsilab_data['abn_form'],'imsi'=>$patient_icsilab_data['imsi'],'pregnancy'=>$patient_icsilab_data['pregnancy'],'remarks'=>$patient_icsilab_data['remarks'],'unique_id_patient_icsilab'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_icsilab_data',$phd_row);
          }
        }

        //icsilab

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
      if(($prescription_data->lmps !='0000-00-00') && ($prescription_data->lmps !='1970-01-01'))
      {
         $lmps=date('d-m-Y',strtotime($prescription_data->lmps));
      }
      else{
        $lmps='';
      }
      if(($prescription_data->edd !='0000-00-00') && ($prescription_data->edd !='1970-01-01'))
      {
        $edd=date('d-m-Y',strtotime($prescription_data->edd));
      }
      else{
        $edd='';
      }
      
      $confirm_delivery_date = '';
      if(strtotime($prescription_data->confirm_delivery_date)>100000)
      {
          $confirm_delivery_date = date('d-m-Y', strtotime($prescription_data->confirm_delivery_date));
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
                            'common_fertility_risk'=>$prescription_data->common_fertility_risk,
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
                            'confirm_delivery_date'=>$confirm_delivery_date,
                            'temp'=>$prescription_data->temp,
                            'bp'=>$prescription_data->bp,
                            'pulse'=>$prescription_data->pulse,
                            'lmps'=>$lmps,
                            'edd'=>$edd,
                            'pog'=>$prescription_data->pog,
                            'days'=>$prescription_data->days,
                            'map'=>$prescription_data->map,
                            'pulse'=>$prescription_data->pulse,
                            'vital_dm'=>$prescription_data->dm,
                            'bloodgroup'=>$prescription_data->bloodgroup,
                            'booking_date'=>$prescription_data->booking_date,
                            'booking_time'=>$prescription_data->booking_time,
                            'check_appointment'=>$prescription_data->check_appointment,
'print_patient_history_flag'=>$prescription_data->print_patient_history_flag,
'print_disease_flag'=>$prescription_data->print_disease_flag,
'print_complaints_flag'=>$prescription_data->print_complaints_flag,
'print_allergy_flag'=>$prescription_data->print_allergy_flag,
'print_general_examination_flag'=>$prescription_data->print_general_examination_flag,
'print_clinical_examination_flag'=>$prescription_data->print_clinical_examination_flag,
'print_investigations_flag'=>$prescription_data->print_investigations_flag,
'print_medicine_flag'=>$prescription_data->print_medicine_flag,
'print_advice_flag'=>$prescription_data->print_advice_flag,
'print_next_app_flag'=>$prescription_data->print_next_app_flag,
'print_gpla_flag'=>$prescription_data->print_gpla_flag,
'print_follicular_flag'=>$prescription_data->print_follicular_flag,
'print_icsilab_flag'=>$prescription_data->print_icsilab_flag,
'print_fertility_flag'=>$prescription_data->print_fertility_flag,
'print_antenatal_flag'=>$prescription_data->print_antenatal_flag,
'date_time_new' =>$prescription_data->date_time_new,
'next_reason'=>$prescription_data->next_reason
                            
                            );
       //print"<pre>";print_r($data['form_data']);die; lmps


      

      

      $this->load->model('general/general_model');
      $data['vitals_list']=$this->general_model->vitals_list();

      $patient_fertility_data = $this->gynecology_prescription->get_antenatal_care_list($prescription_id, $opd_booking_id);
      $antenatal_arr = [];
      if(!empty($patient_fertility_data))
      {
          foreach($patient_fertility_data as $fertility_data)
          {
              //echo "<pre>"; print_r($fertility_data); exit;
              $antenatal_arr[] = array(
                  'antenatal_care_period'=>$fertility_data->antenatal_care_period,
                  'antenatal_expected_date'=>$fertility_data->antenatal_expected_date,
                  'antenatal_first_date'=>$fertility_data->antenatal_first_date, 
                  'antenatal_ultrasound'=>$fertility_data->antenatal_ultrasound,
                  'antenatal_remarks'=>$fertility_data->antenatal_remarks
                  );
          }
      }
      
      $this->session->set_userdata('patient_antenatal_care_data', $antenatal_arr);
      
      $patient_fertility_data = $this->gynecology_prescription->get_fertility_list($prescription_id, $opd_booking_id);

      //echo "<pre>"; print_r($patient_fertility_data); exit;
      //fertility_ovarian_factor
      $fertility_arr = [];
      if(!empty($patient_fertility_data))
      {
          foreach($patient_fertility_data as $fertility_data)
          {
              //echo "<pre>"; print_r($fertility_data); exit;
              $fertility_arr[] = array('fertility_co'=>$fertility_data->fertility_co, 'fertility_risk'=>$fertility_data->fertility_risk, 'fertility_uterine_factor'=>$fertility_data->fertility_uterine_factor, 'fertility_tubal_factor'=>$fertility_data->fertility_tubal_factor, 'fertility_decision'=>$fertility_data->fertility_decision, 'fertility_ovarian_factor'=>$fertility_data->fertility_ovarian_factor, 'fertility_uploadhsg'=>$fertility_data->fertility_uploadhsg, 'fertility_laparoscopy'=>$fertility_data->fertility_laparoscopy, 'fertility_ultrasound_images'=>$fertility_data->fertility_ultrasound_images, 'fertility_male_factor'=>$fertility_data->fertility_male_factor, 'fertility_sperm_date'=>date('d-m-Y', strtotime($fertility_data->fertility_sperm_date)), 'fertility_sperm_count'=>$fertility_data->fertility_sperm_count, 'fertility_sperm_motality'=>$fertility_data->fertility_sperm_motality, 'fertility_sperm_g3'=>$fertility_data->fertility_sperm_g3, 'fertility_sperm_abnform'=>$fertility_data->fertility_sperm_abnform, 'fertility_sperm_remarks'=>$fertility_data->fertility_sperm_remarks);
          }
      }
      
      $this->session->set_userdata('fertility_data', $fertility_arr);
      
      
      /*$data['fertility_co'] = $patient_fertility_data[0]->fertility_co;
      $data['fertility_risk'] = $patient_fertility_data[0]->fertility_risk;
      
      $data['fertility_uterine_factor'] = $patient_fertility_data[0]->fertility_uterine_factor;
      $data['fertility_tubal_factor'] = $patient_fertility_data[0]->fertility_tubal_factor;
      $data['fertility_uploadhsg'] = $patient_fertility_data[0]->fertility_uploadhsg;
      //$data['fertility_laparoscopy'] = $patient_fertility_data[0]->fertility_laparoscopy;
      
      $data['fertility_decision'] = $patient_fertility_data[0]->fertility_decision;
      $data['fertility_ovarian_factor'] = $patient_fertility_data[0]->fertility_ovarian_factor;
      $data['fertility_ultrasound_images'] = $patient_fertility_data[0]->fertility_ultrasound_images;
      $data['fertility_male_factor'] = $patient_fertility_data[0]->fertility_male_factor;
      $data['fertility_sperm_date'] = $patient_fertility_data[0]->fertility_sperm_date;
      $data['fertility_sperm_count'] = $patient_fertility_data[0]->fertility_sperm_count;
      $data['fertility_sperm_motality'] = $patient_fertility_data[0]->fertility_sperm_motality;
      $data['fertility_sperm_g3'] = $patient_fertility_data[0]->fertility_sperm_g3;
      $data['fertility_sperm_abnform'] = $patient_fertility_data[0]->fertility_sperm_abnform;
      $data['fertility_sperm_remarks'] = $patient_fertility_data[0]->fertility_sperm_remarks;*/
      $this->load->model('next_appointment/Next_appointment_model', 'next_appointment_master');
      $data['next_appointment_list'] = $this->next_appointment_master->list();
     
      $this->load->view('gynecology/gynecology_prescription/add',$data);

      
       
    }
  } 

  public function get_test_vals($vals="")
  {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('test_name','ASC');
        $this->db->where('is_deleted',0);
        // $this->db->where('test_name LIKE "'.$vals.'%"');
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('path_test');
        $result = $query->result(); 



        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('profile_name','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('profile_name LIKE "'.$vals.'%"');
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('path_profile');
        $result1 = $query->result(); 
        //echo $this->db->last_query();
       /* if(!empty($result))
        { 
          foreach($result as $vals)
          {
               $response['test_id'] = $vals->id;
               $response['test_name'] = $vals->test_name;
          }
        }
        return $response; */
        $data = array();
        if(!empty($result))
        { 
          
          foreach($result as $vals)
          {
              $data[] = ['id' =>$vals->id, 'name' =>$vals->test_name, 'is_profile' => false];
                    //$response[] = $vals->medicine;
          }
        }

        if(!empty($result1))
        { 
          foreach($result1 as $vals)
          {
              $data[] = ['id' =>$vals->id, 'name' =>$vals->profile_name, 'is_profile' => true];
               //$response[] = $vals->medicine;
          }
        }
    return $data;
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

  //


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
                      <th>Married Life Duration</th>
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
                      <th>Disease Duration</th>
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
        
        

        
        $patient_obestetric_history[$post['unique_id_obestetric_history']] = array('obestetric_g'=>$post['obestetric_g'], 'obestetric_p'=>$post['obestetric_p'],'obestetric_l'=>$post['obestetric_l'],'obestetric_e'=>$post['obestetric_e'],'obestetric_mtp'=>$post['obestetric_mtp'],'obestetric_remarks'=>$post['obestetric_remarks'],'unique_id_obestetric_history'=>$post['unique_id_obestetric_history']);
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
                      <th>A</th>
                      <th>E</th>
                      <th>MTP</th>
                      <th>Remarks</th>
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
                            
                            <td>'.$patient_obestetric_history_data['obestetric_e'].'</td>
                            
                            <td>'.$patient_obestetric_history_data['obestetric_mtp'].'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_remarks'].'</td>
                            
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
                             <td><div>'.$patient_advice_data_rec['advice_value'].'</div></td>
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


  function get_gynecology_medicine_auto_vals()
  {
       //echo "hi";die;
       $post = $this->input->post();
       
       $vals = $post['name_startsWith'];
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_medicine_auto_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  }


  function get_gynecology_dosage_vals()
  {
      $post = $this->input->post();
       
       $vals = $post['name_startsWith'];
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


   function get_gynecology_duration_vals()
  {
      $post = $this->input->post();
       
       $vals = $post['name_startsWith'];
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_duration_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  }

  function get_gynecology_frequency_vals()
  {
      $post = $this->input->post();
       
       $vals = $post['name_startsWith'];
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_frequency_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  } 


 function get_gynecology_advice_vals()
  {
      $post = $this->input->post();
       
       $vals = $post['name_startsWith'];
      if(!empty($vals))
      {
          $result = $this->gynecology_prescription->get_gynecology_advice_vals($vals);  
          if(!empty($result))
          {
            echo json_encode($result,true);
          } 
      } 
  }

  function get_gynecology_type_vals()
  {
      $post = $this->input->post();
       
       $vals = $post['name_startsWith'];
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




  public function patient_gpla_list()
    {
      $post = $this->input->post();

      $patient_gpla_data = $this->session->userdata('patient_gpla_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_gpla_data = $this->session->userdata('patient_gpla_data'); 
        if(isset($patient_gpla_data) && !empty($patient_gpla_data))
        {
          $patient_gpla = $patient_gpla_data;
        }
        else
        {
          $patient_gpla = [];
        }
  
        
        $patient_gpla[$post['unique_id_patient_gpla']] = array('patient_gpla_id'=>$post['patient_gpla_id'], 'dog_value'=>$post['dog_value'], 'mode_value'=>$post['mode_value'], 'monthyear_value'=>$post['monthyear_value'], 'unique_id_patient_gpla'=>$post['unique_id_patient_gpla']);
        $this->session->set_userdata('patient_gpla_data', $patient_gpla);
        $html_data = $this->patient_gpla_template_list();
       // print_r($html_data);die();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }


  private function patient_gpla_template_list()
    {
      $patient_gpla_data = $this->session->userdata('patient_gpla_data');
      
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
                    <th>DOG</th>
                    <th>Mode</th>
                    <th>Month Year</th>
                  </tr></thead><tbody>';  
           if(isset($patient_gpla_data) && !empty($patient_gpla_data))
           {
              $i = 1;

              foreach($patient_gpla_data as $key=>$patient_gpla_data_rec)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_gpla_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_gpla" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_gpla_data_rec['dog_value'].'</td>
                             <td>'.$patient_gpla_data_rec['mode_value'].'</td>
                             <td>'.$patient_gpla_data_rec['monthyear_value'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">GPLA are not available.</div></td>
                        </tr>';
           }
           $rows .= '</tbody></table>';

          
          return $rows;
    }
    
    public function remove_fertility_vals()
    {
        $fertility_data = $this->session->userdata('fertility_data');
        $fertility_arr = [];
        $post =  $this->input->post();
        $keys = explode(',', $post['keys']);
        if(!empty($fertility_data))
        {
            foreach($fertility_data as $key=>$fertility)
            {
                if (!in_array($key, $keys))
                {
                    $fertility_arr[] = $fertility;
                }
            }
            
            $this->session->set_userdata('fertility_data', $fertility_arr);
        }
        echo 1;
    }


    public function remove_gynecology_patient_gpla()
    {    
        $post =  $this->input->post();
        $data = array('patient_gpla_vals'=>explode(',', $post['patient_gpla_vals']));

        if(isset($data['patient_gpla_vals']) && !empty($data['patient_gpla_vals']))
        {
          $patient_gpla_data = $this->session->userdata('patient_gpla_data');
          $patient_gpla_id_list = array_column($patient_gpla_data, 'unique_id_patient_gpla');
          foreach($patient_gpla_data as $key=>$patient_gpla_ids)
          {
            if(in_array($patient_gpla_ids['unique_id_patient_gpla'],$data['patient_gpla_vals']))
            {
               unset($patient_gpla_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_gpla_data',$patient_gpla_data);
          $html_data = $this->patient_gpla_template_list();
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
      $this->session->unset_userdata('patient_gpla_data');
      
    if(($template_id)>0)
     {
      $template_data= $this->gynecology_prescription->get_template_data($template_id);
     echo $template_data;
      print_r($result);die;
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


        function load_gpla_values($template_id="")
        {
           
              if(($template_id)>0)
              {
              $this->session->unset_userdata('patient_gpla_data');


              $patient_gpla= $this->db->where('template_id',$template_id)->get('hms_gynecology_gpla_template')->result_array();
              $patient_gpla_data_list = [];
              $i_row = 1;
              foreach($patient_gpla as $patient_gpla_data)
              {
                // $patient_gpla_data_list[$i_row]= array('gpla_value'=>$patient_gpla_data['patient_advice_name'], 'unique_id_patient_advice'=>$i_row);
                $patient_gpla_data_list[$i_row] = array('patient_gpla_id'=>$patient_gpla_data['id'], 'dog_value'=>$patient_gpla_data['dog_value'], 'mode_value'=>$patient_gpla_data['mode_value'], 'monthyear_value'=>$patient_gpla_data['monthyear_value'], 'unique_id_patient_gpla'=>$i_row);
                $i_row++;
              }

              $this->session->set_userdata('patient_gpla_data',$patient_gpla_data_list);
              $patient_gpla_data_list = $this->session->userdata('patient_gpla_data');
              echo json_encode($patient_gpla_data_list);
              
            }
            else if($template_id==0)
            { 
              $this->session->unset_userdata('patient_gpla_data');
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

    public function view_prescription($prescription_id,$branch_id='')
    { 
        //unauthorise_permission(248,1448);
     
       

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

        $data['gpla_list']=$this->gynecology_prescription->gpla_list($prescription_id);
        //print_r($data['gpla_list']);die()
        $data['prescription_id'] = $prescription_id;
        $data['right_ovary_db_data'] = $this->gynecology_prescription->get_gynecology_right_ovary_list($prescription_id, $opd_booking_id,0);
        $data['right_ovary_dataa'] = $this->gynecology_prescription->get_gynecology_right_ovary_list($prescription_id, $opd_booking_id,1);

        $data['left_ovary_db_data'] = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id);
        $data['left_ovary_dataa'] = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,1);
        $data['patient_icsilab_db_data'] = $this->gynecology_prescription->get_patient_icsilab_list($prescription_id, $opd_booking_id);
        
        
        $data['patient_antenatal_care_db_data'] = $this->gynecology_prescription->get_patient_antenatal_care_list($prescription_id, $opd_booking_id);
        
        $patient_fertility_data = $this->gynecology_prescription->get_fertility_list2($prescription_id, $opd_booking_id);
    $data['list_fertility_data'] = $patient_fertility_data;    

      $data['fertility_co'] = $patient_fertility_data[0]->fertility_co;
      $data['fertility_uterine_factor'] = $patient_fertility_data[0]->fertility_uterine_factor;
      $data['fertility_tubal_factor'] = $patient_fertility_data[0]->fertility_tubal_factor;
      $data['fertility_uploadhsg'] = $patient_fertility_data[0]->fertility_uploadhsg;
      $data['fertility_laparoscopy'] = $patient_fertility_data[0]->fertility_laparoscopy;
      $data['fertility_risk'] = $patient_fertility_data[0]->fertility_risk;
      $data['fertility_decision'] = $patient_fertility_data[0]->fertility_decision;
      $data['fertility_ovarian_factor'] = $patient_fertility_data[0]->fertility_ovarian_factor;
      $data['fertility_ultrasound_images'] = $patient_fertility_data[0]->fertility_ultrasound_images;
      $data['fertility_male_factor'] = $patient_fertility_data[0]->fertility_male_factor;
      $data['fertility_sperm_date'] = $patient_fertility_data[0]->fertility_sperm_date;
      $data['fertility_sperm_count'] = $patient_fertility_data[0]->fertility_sperm_count;
      $data['fertility_sperm_motality'] = $patient_fertility_data[0]->fertility_sperm_motality;
      $data['fertility_sperm_g3'] = $patient_fertility_data[0]->fertility_sperm_g3;
      $data['fertility_sperm_abnform'] = $patient_fertility_data[0]->fertility_sperm_abnform;
      $data['fertility_sperm_remarks'] = $patient_fertility_data[0]->fertility_sperm_remarks;
        
        
        $this->load->view('gynecology/gynecology_prescription/view_template',$data);
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

        $data['gpla_list']=$this->gynecology_prescription->gpla_list($prescription_id);
        //print_r($data['gpla_list']);die()
        $data['prescription_id'] = $prescription_id;
        $data['right_ovary_dataa'] = $this->gynecology_prescription->get_gynecology_right_ovary_list($prescription_id, $opd_booking_id,1);
        
        $data['right_ovary_db_data'] = $this->gynecology_prescription->get_gynecology_right_ovary_list($prescription_id, $opd_booking_id,0); 
        // left_ovary_dataa
        $data['left_ovary_dataa'] = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,1);
        //for update case
        //$data['left_ovary_data'] = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,1);
        
        //echo "<pre>"; print_r($data['left_ovary_dataa']);
        $data['left_ovary_db_data'] = $this->gynecology_prescription->get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,0);
        $data['patient_icsilab_db_data'] = $this->gynecology_prescription->get_patient_icsilab_list($prescription_id, $opd_booking_id);
        
        $data['patient_antenatal_care_db_data'] = $this->gynecology_prescription->get_patient_antenatal_care_list($prescription_id, $opd_booking_id);
        
        
        $patient_fertility_data = $this->gynecology_prescription->get_fertility_list2($prescription_id, $opd_booking_id);
        $data['list_fertility_data'] = $patient_fertility_data;

      $data['fertility_co'] = $patient_fertility_data[0]->fertility_co;
      $data['fertility_uterine_factor'] = $patient_fertility_data[0]->fertility_uterine_factor;
      $data['fertility_tubal_factor'] = $patient_fertility_data[0]->fertility_tubal_factor;
      $data['fertility_uploadhsg'] = $patient_fertility_data[0]->fertility_uploadhsg;
      $data['fertility_laparoscopy'] = $patient_fertility_data[0]->fertility_laparoscopy;
      $data['fertility_risk'] = $patient_fertility_data[0]->fertility_risk;
      $data['fertility_decision'] = $patient_fertility_data[0]->fertility_decision;
      $data['fertility_ovarian_factor'] = $patient_fertility_data[0]->fertility_ovarian_factor;
      $data['fertility_ultrasound_images'] = $patient_fertility_data[0]->fertility_ultrasound_images;
      $data['fertility_male_factor'] = $patient_fertility_data[0]->fertility_male_factor;
      $data['fertility_sperm_date'] = $patient_fertility_data[0]->fertility_sperm_date;
      $data['fertility_sperm_count'] = $patient_fertility_data[0]->fertility_sperm_count;
      $data['fertility_sperm_motality'] = $patient_fertility_data[0]->fertility_sperm_motality;
      $data['fertility_sperm_g3'] = $patient_fertility_data[0]->fertility_sperm_g3;
      $data['fertility_sperm_abnform'] = $patient_fertility_data[0]->fertility_sperm_abnform;
      $data['fertility_sperm_remarks'] = $patient_fertility_data[0]->fertility_sperm_remarks; 
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
    //arvind data
     public function remove_right_ovary()
    {    
        $post =  $this->input->post();
        $data = array('right_ovary_vals'=>explode(',', $post['right_ovary_vals']));


        if(isset($data['right_ovary_vals']) && !empty($data['right_ovary_vals']))
        {
          $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data');
          $patient_history_id_list = array_column($patient_right_ovary_data, 'unique_id');
          foreach($patient_right_ovary_data as $key=>$patient_right_ovary_ids)
          {
            
            if(in_array($patient_right_ovary_ids['unique_id'],$data['right_ovary_vals']))
            {

               unset($patient_right_ovary_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_right_ovary_data',$patient_right_ovary_data);
          $html_data = $this->patient_right_ovary_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }


    public function remove_left_ovary()
    {    
        $post =  $this->input->post();

        $data = array('left_ovary_vals'=>explode(',', $post['left_ovary_vals']));
        if(isset($data['left_ovary_vals']) && !empty($data['left_ovary_vals']))
        {
          $patient_left_ovary_data = $this->session->userdata('patient_left_ovary_data');

          

          $patient_history_id_list = array_column($patient_left_ovary_data, 'unique_id');


          foreach($patient_left_ovary_data as $key=>$patient_left_ovary_ids)
          {
             // echo "<pre>"; print_r($data['left_ovary_vals']);
            if(in_array($patient_left_ovary_ids['unique_id'],$data['left_ovary_vals']))
            {

               unset($patient_left_ovary_data[$key]);
            }
          }   
          $this->session->set_userdata('patient_left_ovary_data',$patient_left_ovary_data);
          $html_data = $this->patient_left_ovary_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }



    

    public function remove_gynecology_patient_family_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_family_history_vals'=>explode(',', $post['patient_family_history_vals']));
        $patient_family_history_data = $this->session->userdata('patient_family_history_data');
        //echo "<pre>"; print_r($patient_family_history_data); exit;
        if(isset($data['patient_family_history_vals']) && !empty($data['patient_family_history_vals']))
        {
              //echo "<pre>"; print_r($patient_family_history_data); exit;
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


 public function remove_gynecology_patient_icsilab()
    {    
        $post =  $this->input->post(); 
        
        $data = array('patient_icsilab_vals'=>explode(',', $post['patient_icsilab_vals']));
        if(isset($data['patient_icsilab_vals']) && !empty($data['patient_icsilab_vals']))
        {
          $patient_icsilab_data = $this->session->userdata('patient_icsilab_data');
          //echo "pre"; print_r($patient_icsilab_data); exit;
          $patient_icsilab_id_list = array_column($patient_icsilab_data, 'unique_id_patient_icsilab');
          foreach($patient_icsilab_data as $key=>$patient_icsilab_ids)
          {
            if(in_array($patient_icsilab_ids['unique_id_patient_icsilab'],$data['patient_icsilab_vals']))
            {
               unset($patient_icsilab_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_icsilab_data',$patient_icsilab_data);
          $html_data = $this->patient_icsilab_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }
    
    public function remove_gynecology_patient_antenatal_care()
    {
        $fertility_data = $this->session->userdata('patient_antenatal_care_data'); 
        $fertility_arr = [];
        $post =  $this->input->post();
        $keys = explode(',', $post['keys']);
        if(!empty($fertility_data))
        {
            foreach($fertility_data as $key=>$fertility)
            {
                if (!in_array($key, $keys))
                {
                    $fertility_arr[] = $fertility;
                }
            }
            //echo "<pre>"; print_r($fertility_arr);die;
            $this->session->set_userdata('patient_antenatal_care_data', $fertility_arr);
        }
        echo 1;
    }
    
    /*public function remove_gynecology_patient_antenatal_care()
    {    
        $post =  $this->input->post(); 
        
        $data = array('patient_antenatal_care_vals'=>explode(',', $post['patient_antenatal_care_vals']));
        if(isset($data['patient_antenatal_care_vals']) && !empty($data['patient_antenatal_care_vals']))
        {
          $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data');
          //echo "pre"; print_r($patient_icsilab_data); exit;
          $patient_antenatal_care_id_list = array_column($patient_antenatal_care_data, 'unique_id_patient_antenatal_care');
          foreach($patient_antenatal_care_data as $key=>$patient_antenatal_care_ids)
          {
            if(in_array($patient_antenatal_care_ids['unique_id_patient_antenatal_care'],$data['patient_icsilab_vals']))
            {
               unset($patient_antenatal_care_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_antenatal_care_data',$patient_antenatal_care_data);
          $html_data = $this->patient_antenatal_care_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }*/
    
    
    private function patient_antenatal_care_template_list()
    {
      $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data');
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
                    <th scope="col">S.No.</th>
                  <th>Last Menstrual Period(LMP)</th>
                  <th>First Date</th>
                  <th>Expected Date of Delivery(EDD)</th>
                  <th>Remarks</th>
                    
                  </tr></thead>';  
           if(isset($patient_antenatal_care_data) && !empty($patient_antenatal_care_data))
           {
              $i = 1;
              foreach($patient_antenatal_care_data as $key=>$patient_antenatal_care_val)
              {
                
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_clinical_examination_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_icsilab" value="'.$key.'">
                            <td>'.$i.'</td>
                             
                             <td>'.$patient_antenatal_care_val['antenatal_care_period'].'</td>
                             <td>'.$patient_antenatal_care_val['antenatal_first_date'].'</td>
                             <td>'.$patient_antenatal_care_val['antenatal_expected_date'].'</td>
                             <td>'.$patient_antenatal_care_val['antenatal_remarks'].'</td>
                            
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="20" align="center" class=" text-danger "><div class="text-center">Antenatal data not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }
    
    private function patient_icsilab_template_list()
    {
      $patient_icsilab_data = $this->session->userdata('patient_icsilab_data');
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
                    <th>Date</th>
                    <th>Oocytes</th>
                    <th>M2</th>
                    <th>Injected</th>
                    <th>Cleavge</th>
                    <th>Embryos Day3</th>
                    <th>Day 5</th>
                    <th>Et</th>
                    <th>VIT</th>
                    <th>LAH</th>
                    <th>Semen</th>
                    <th>Count</th>
                    <th>Motility</th>
                    <th>G3</th>
                    <th>Abn. Form</th>
                    <th>IMSI</th>
                    <th>Pregnancy</th>
                    <th>Remarks</th>
                  </tr></thead>';  
           if(isset($patient_icsilab_data) && !empty($patient_icsilab_data))
           {
              $i = 1;
              foreach($patient_icsilab_data as $key=>$patient_icsilab_val)
              {
                
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_clinical_examination_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_icsilab" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_icsilab_val['icsilab_date'].'</td>
                             <td>'.$patient_icsilab_val['oocytes'].'</td>
                             <td>'.$patient_icsilab_val['m2'].'</td>
                             <td>'.$patient_icsilab_val['injected'].'</td>
                             <td>'.$patient_icsilab_val['cleavge'].'</td>
                             <td>'.$patient_icsilab_val['embryos_day3'].'</td>
                             <td>'.$patient_icsilab_val['day5'].'</td>
                             <td>'.$patient_icsilab_val['day_of_et'].'</td>
                             <td>'.$patient_icsilab_val['et'].'</td>
                             <td>'.$patient_icsilab_val['vit'].'</td>
                             <td>'.$patient_icsilab_val['lah'].'</td>
                             <td>'.$patient_icsilab_val['semen'].'</td>
                             <td>'.$patient_icsilab_val['count'].'</td>
                             <td>'.$patient_icsilab_val['motility'].'</td>
                             <td>'.$patient_icsilab_val['g3'].'</td>
                             <td>'.$patient_icsilab_val['abn_form'].'</td>
                             <td>'.$patient_icsilab_val['imsi'].'</td>
                             <td>'.$patient_icsilab_val['pregnancy'].'</td>
                             <td>'.$patient_icsilab_val['remarks'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="20" align="center" class=" text-danger "><div class="text-center">ICSI lab data not available.</div></td>
                        </tr>';
           }

           
           return $rows;
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
    
    public function get_advice_value()
  {
    $investigation_id = $this->input->post('value');
    $this->load->model('gynecology/advice/advice_model','patient_template');
    $std_value = $this->patient_template->get_by_id($investigation_id);
    echo $std_value['advice'];
  }


  //
  public function patient_right_ovary_list()
  {
    $post = $this->input->post();
    $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data'); 
    if(isset($post) && !empty($post))
    {       
      $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data'); 
      if(isset($patient_right_ovary_data) && !empty($patient_right_ovary_data))
      {
        $patient_right_ovary = $patient_right_ovary_data; 
      }
      else
      {
        $patient_right_ovary = [];
      }

      //$patient_right_ovary[$post['unique_id']] = array('right_follic_size'=>$post['right_follic_size'],'rec_count'=>$post['rec_count'],'unique_id'=>$post['unique_id']);
      if($post['right_follic_size']=='Select Size')
      {
          $sizeright = '';
      }
      else
      {
          $sizeright = $post['right_follic_size'];
      }
      if($post['left_follic_size']=='Select Size')
      {
          $sizeleft = '';
      }
      else
      {
          $sizeleft = $post['left_follic_size'];
      }
      
      $patient_right_ovary[$post['unique_id']] = array('right_folli_date'=>$post['right_folli_date'], 'right_folli_day'=>$post['right_folli_day'],'right_folli_protocol'=>$post['right_folli_protocol'],'right_folli_pfsh'=>$post['right_folli_pfsh'],'right_folli_recfsh'=>$post['right_folli_recfsh'],'right_folli_hmg'=>$post['right_folli_hmg'],'right_folli_hp_hmg'=>$post['right_folli_hp_hmg'],'right_folli_agonist'=>$post['right_folli_agonist'],'right_folli_antiagonist'=>$post['right_folli_antiagonist'],'right_folli_trigger'=>$post['right_folli_trigger'],
      
      'endometriumothers'=>$post['endometriumothers'],
      'e2'=>$post['e2'],
      'p4'=>$post['p4'],
      'risk'=>$post['risk'],
      'others'=>$post['others'],
      'right_follic_size'=>$sizeright,
      
      'left_follic_size'=>$sizeleft,'rec_count'=>$post['rec_count'],'unique_id'=>$post['unique_id']);
      
      $this->session->set_userdata('patient_right_ovary_data', $patient_right_ovary);
      $html_data = $this->patient_right_ovary_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data'); 
      //print_r($patient_right_ovary_data);die;
      echo $json;
      
   }
  }
  
  private function patient_right_ovary_template_list()
  {
      $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data');
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
                      <th scope="col">S.No.</th>
                      <th>Date</th>
                      <th>Day</th>
                      <th>protocol</th>
                      <th>PFSH</th>
                      <th>REC FSH</th>
                      <th>HMG</th>
                      <th>HP HMG </th>
                      <th>Agonist </th>
                      <th>Antagonist </th>
                      <th>Trigger</th>
                      
                      <th>Endometriumothers </th>
                      <th>E2 </th>
                      <th>P4 </th>
                      <th>Risk </th>
                      <th>Others </th>
                      
                      
                      <th>Right Size </th>
                      <th>Left Size </th>
                  </tr></thead>';  
           if(isset($patient_right_ovary_data) && !empty($patient_right_ovary_data))
           {
              $i = 1;
              foreach($patient_right_ovary_data as $key=>$rightovarydata)
              { 
                $rows .= '<tr name="patient_right_ovary_row" id="'.$key.'">
                            <td width="60" align="center"><input type="checkbox" name="patient_right_ovary[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$rightovarydata['right_folli_date'].'</td>
                            <td>'.$rightovarydata['right_folli_day'].'</td>
                            <td>'.$rightovarydata['right_folli_protocol'].'</td>
                            <td>'.$rightovarydata['right_folli_pfsh'].'</td>
                            <td>'.$rightovarydata['right_folli_recfsh'].'  </td>
                            <td>'.$rightovarydata['right_folli_hmg'].'  </td>
                            <td>'.$rightovarydata['right_folli_hp_hmg'].'  </td>
                            <td>'.$rightovarydata['right_folli_agonist'].'  </td>
                            <td>'.$rightovarydata['right_folli_antiagonist'].'  </td>
                            <td>'.$rightovarydata['right_folli_trigger'].'  </td>
                            
                            <td>'.$rightovarydata['endometriumothers'].'  </td>
                            <td>'.$rightovarydata['e2'].'  </td>
                            <td>'.$rightovarydata['p4'].'  </td>
                            <td>'.$rightovarydata['risk'].'  </td>
                            <td>'.$rightovarydata['others'].'  </td>
                            
                            
                            <td>'.$rightovarydata['right_follic_size'].'  </td>
                            <td>'.$rightovarydata['left_follic_size'].'  </td>
                            
                        </tr>';
                 $i++;                
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="19" align="center" class=" text-danger "><div class="text-center">No data available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  private function patient_right_ovary_template_list_old()
  {
      $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data');
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
                      <th>Size </th>
                  </tr></thead>';  
           if(isset($patient_right_ovary_data) && !empty($patient_right_ovary_data))
           {
              $i = 1;
              foreach($patient_right_ovary_data as $key=>$rightovarydata)
              { 
                $rows .= '<tr name="patient_right_ovary_row" id="'.$key.'">
                            <td width="60" align="center"><input type="checkbox" name="patient_right_ovary[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$key.'"> 
                            <td>'.$rightovarydata['right_follic_size'].'  </td>
                            
                        </tr>';
                 $i++;                
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="2" align="center" class=" text-danger "><div class="text-center">No data available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }




  public function patient_left_ovary_list()
  {
    $post = $this->input->post();

    $patient_left_ovary_data = $this->session->userdata('patient_left_ovary_data'); 
    if(isset($post) && !empty($post))
    {   
      $patient_left_ovary_data = $this->session->userdata('patient_left_ovary_data'); 
      if(isset($patient_left_ovary_data) && !empty($patient_left_ovary_data))
      {
        $patient_left_ovary = $patient_left_ovary_data; 
      }
      else
      {
        $patient_left_ovary = [];
      }


     // $patient_left_ovary[$post['unique_id']] = array('left_follic_size'=>$post['left_follic_size'],'rec_count'=>$post['rec_count'],'unique_id'=>$post['unique_id']);
     
     $patient_left_ovary[$post['unique_id']] = array('left_folli_date'=>$post['left_folli_date'], 'left_folli_day'=>$post['left_folli_day'],'left_folli_protocol'=>$post['left_folli_protocol'],'left_folli_pfsh'=>$post['left_folli_pfsh'],'left_folli_recfsh'=>$post['left_folli_recfsh'],'left_folli_hmg'=>$post['left_folli_hmg'],'left_folli_hp_hmg'=>$post['left_folli_hp_hmg'],'left_folli_agonist'=>$post['left_folli_agonist'],'left_folli_antiagonist'=>$post['left_folli_antiagonist'],'left_folli_trigger'=>$post['left_folli_trigger'],'left_follic_size'=>$post['left_follic_size'],'endometriumothers'=>$post['endometriumothers'],'e2'=>$post['e2'],'p4'=>$post['p4'],'risk'=>$post['risk'],'others'=>$post['others'],'rec_count'=>$post['rec_count'],'unique_id'=>$post['unique_id']);
     
      $this->session->set_userdata('patient_left_ovary_data', $patient_left_ovary);
      $html_data = $this->patient_left_ovary_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
      
   }
  }
  
  private function patient_left_ovary_template_list()
  {
      $patient_left_ovary_data = $this->session->userdata('patient_left_ovary_data');
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
                      <th scope="col">S.No.</th>
                      <th>Date</th>
                      <th>Day</th>
                      <th>protocol</th>
                      <th>PFSH</th>
                      <th>REC FSH</th>
                      <th>HMG</th>
                      <th>HP HMG </th>
                      <th>Agonist </th>
                      <th>Antagonist </th>
                      <th>Trigger</th>
                      <th>Size </th>
                      <th>Endometriumothers </th>
                      <th>E2 </th>
                      <th>P4 </th>
                      <th>Risk </th>
                      <th>Others </th>
                  </tr></thead>';  
           if(isset($patient_left_ovary_data) && !empty($patient_left_ovary_data))
           {
              $i = 1;
              foreach($patient_left_ovary_data as $key=>$leftovarydata)
              { 
                $rows .= '<tr name="patient_right_ovary_row" id="'.$key.'">
                            <td width="60" align="center"><input type="checkbox" name="patient_left_ovary[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$leftovarydata['left_folli_date'].'</td>
                            <td>'.$leftovarydata['left_folli_day'].'</td>
                            <td>'.$leftovarydata['left_folli_protocol'].'</td>
                            <td>'.$leftovarydata['left_folli_pfsh'].'</td>
                            <td>'.$leftovarydata['left_folli_recfsh'].'  </td>
                            <td>'.$leftovarydata['left_folli_hmg'].'  </td>
                            <td>'.$leftovarydata['left_folli_hp_hmg'].'  </td>
                            <td>'.$leftovarydata['left_folli_agonist'].'  </td>
                            <td>'.$leftovarydata['left_folli_antiagonist'].'  </td>
                            <td>'.$leftovarydata['left_folli_trigger'].'  </td>
                            <td>'.$leftovarydata['left_follic_size'].'  </td>
                            <td>'.$leftovarydata['endometriumothers'].'  </td>
                            <td>'.$leftovarydata['e2'].'  </td>
                            <td>'.$leftovarydata['p4'].'  </td>
                            <td>'.$leftovarydata['risk'].'  </td>
                            <td>'.$leftovarydata['others'].'  </td>
                            
                        </tr>';
                 $i++;                
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="18" align="center" class=" text-danger "><div class="text-center">No data available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

  private function patient_left_ovary_template_list_old()
  {
      $patient_left_ovary_data = $this->session->userdata('patient_left_ovary_data');
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
                      <th>Size </th> 
                  </tr></thead>';  
           if(isset($patient_left_ovary_data) && !empty($patient_left_ovary_data))
           {
              $i = 1;
              foreach($patient_left_ovary_data as $key=>$leftovarydata)
              { 
                $rows .= '<tr name="patient_right_ovary_row" id="'.$key.'">
                            <td width="60" align="center"><input type="checkbox" name="patient_left_ovary[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$key.'"> 
                            <td>'.$leftovarydata['left_follic_size'].'  </td> 
                        </tr>';
                 $i++;                
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="18" align="center" class=" text-danger "><div class="text-center">No data available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }







    public function patient_icsilab_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die; unique_id_patient_icsilab
      $post = $this->input->post();
      $patient_icsilab_data = $this->session->userdata('patient_icsilab_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_icsilab_data = $this->session->userdata('patient_icsilab_data'); 
        if(isset($patient_icsilab_data) && !empty($patient_icsilab_data))
        {
          $patient_icsilab = $patient_icsilab_data;
        }
        else
        {
          $patient_icsilab = [];
        }
       
      $patient_icsilab[$post['unique_id_patient_icsilab']] = array('icsilab_date'=>$post['icsilab_date'], 'oocytes'=>$post['oocytes'],'m2'=>$post['m2'],'injected'=>$post['injected'],'cleavge'=>$post['cleavge'],'embryos_day3'=>$post['embryos_day3'],'day5'=>$post['day5'],'day_of_et'=>$post['day_of_et'],'et'=>$post['et'], 'vit'=>$post['vit'], 'lah'=>$post['lah'], 'semen'=>$post['semen'], 'count'=>$post['count'], 'motility'=>$post['motility'], 'g3'=>$post['g3'], 'abn_form'=>$post['abn_form'], 'imsi'=>$post['imsi'], 'pregnancy'=>$post['pregnancy'], 'remarks'=>$post['remarks'],'unique_id_patient_icsilab'=>$post['unique_id_patient_icsilab']);
        
        $this->session->set_userdata('patient_icsilab_data', $patient_icsilab);
        $html_data = $this->patient_icsilab_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }
    
    public function patient_antenatal_care_list()
    { 
        $post = $this->input->post();
        $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data');
        //echo "<pre>"; print_r($fertility_data);die;
        if(!isset($patient_antenatal_care_data))
        {
            $this->session->set_userdata('patient_antenatal_care_data', []);
            $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data');
        }
        
          $antenatal_ultrasound = '';
          
          if(isset($_FILES['antenatal_ultrasound']) || !empty($_FILES['antenatal_ultrasound']['name']))
          {
            $config['upload_path']   = DIR_UPLOAD_PATH.'prescription/ultrasound/';  
            $config['allowed_types'] = 'pdf|jpeg|jpg|png|mpeg|mpg|mp4|mpe|qt|mov|avi|wmv'; 
            $config['max_size']      = 20000; 
            $config['encrypt_name'] = TRUE; 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if($this->upload->do_upload('antenatal_ultrasound')) 
            {
              $file_data = $this->upload->data(); 
              $antenatal_ultrasound = $file_data['file_name'];
            }
            /*else
            {
               $error =  $this->upload->display_errors();
               //echo "<pre>"; print_r($error); exit;
            }*/
          }
          $file_arr = array("antenatal_ultrasound"=>$antenatal_ultrasound); 
          $new_arr = array_merge($post,$file_arr);
          $patient_antenatal_care_data[] = $new_arr;
          //echo "<pre>"; print_r($fertility_data);die;
          $this->session->set_userdata('patient_antenatal_care_data', $patient_antenatal_care_data);
          
        
      //$this->session->unset_userdata('patient_disease_data');die; unique_id_patient_icsilab
      /*$post = $this->input->post();
      $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data'); 
        if(isset($patient_antenatal_care_data) && !empty($patient_antenatal_care_data))
        {
          $patient_antenatal_care = $patient_antenatal_care_data;
        }
        else
        {
          $patient_icsilab = [];
        }
       
      $patient_antenatal_care[$post['unique_id_patient_antenatal_care']] = array('antenatal_care_period'=>$post['antenatal_care_period'], 'antenatal_first_date'=>$post['antenatal_first_date'],'antenatal_expected_date'=>$post['antenatal_expected_date'], 'antenatal_remarks'=>$post['antenatal_remarks'],'unique_id_patient_antenatal_care'=>$post['unique_id_patient_antenatal_care']);
        
        $this->session->set_userdata('patient_antenatal_care_data', $patient_antenatal_care);
        $html_data = $this->patient_antenatal_care_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;*/
      } 
    
    public function load_antenatal_data()
    {
        $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data');
        if(!empty($patient_antenatal_care_data))
        {
            
            $i=1;
            $tr_data = '';
            foreach($patient_antenatal_care_data as $key=>$patient_antenatal_care)
            {
                /*<img src="<?php echo DIR_FS_PATH.'prescription/hsg/'.$fertility_uploadhsg; ?>" width="100px;">*/
                $tr = '';
                $tr .= '<tr>';
                $tr .= '<td><input class="antenatal_checkbox part_checkbox_antenatal_care" type="checkbox" name="antenatal_ids[]" value="'.$key.'" /></td>';
                $tr .= '<td>'.$i.'</td>';
                
                
                $tr .= '<td>'.$patient_antenatal_care['antenatal_care_period'].'</td>';
                $tr .= '<td>'.$patient_antenatal_care['antenatal_expected_date'].'</td>';
                $tr .= '<td>'.$patient_antenatal_care['antenatal_first_date'].'</td>';
                if(!empty($patient_antenatal_care['antenatal_ultrasound']))
                {
                    $ultrasound_path = ROOT_UPLOADS_PATH.'prescription/ultrasound/'.$patient_antenatal_care['antenatal_ultrasound'];
                    $file_arr = array("mpeg", "mpg", "mp4", "mpe", "qt", "mov", "avi", "wmv");
                    $ext = explode(".", $patient_antenatal_care['antenatal_ultrasound']);
                    //print_r($ext);die;
                    $files = "<img src='".$ultrasound_path."' width='60px;'>";
                    if(in_array($ext[1], $file_arr))
                    { 
                       $files = '<i class="fa fa-video-camera" style="font-size:50px; color:#000;"></i>';  
                    } 
                    $tr .= "<td><a href='".$ultrasound_path."' target='_blank'>".$files."</a></td>";
                }
                else
                {
                    $tr .= '<td></td>'; 
                }   
                $tr .= '<td>'.$patient_antenatal_care['antenatal_remarks'].'</td>';    
                
                 
                $tr .= '</tr>';
                $tr_data .= $tr;
                $i++; 
            }
            
        }
        else
        {
            $tr_data = '<tr><td colspan="18" style="color:red;">No Record Found</td></tr>';
        }
        echo $tr_data;
    }
    
    public function date_add_days()
    {
        $post = $this->input->post();
        if(!empty($post))
        {  
            $date = date('Y-m-d', strtotime($post['date']));
            $new_date = date('d-m-Y', strtotime($date. ' + '.$post['days'].'  days')); 
            echo $new_date;
        }
    }
    
    function dateDiffInDays($date1, $date2)  
    { 
        // Calculating the difference in timestamps 
        $diff = strtotime($date2) - strtotime($date1); 
          
        // 1 day = 24 hours 
        // 24 * 60 * 60 = 86400 seconds 
        return abs(round($diff / 86400)); 
    } 
    
    public function get_gestational_date20210526()
    {
        $post = $this->input->post(); //current_date -lmp
        $today_date = date('d-m-Y');  //$post['confirm_delivery']
        
        $antenatal_care_period = $post['antenatal_care_period'];
        //if(!empty($post['confirm_delivery']) && !empty($post['antenatal_expected_date']))
        //!empty($post['confirm_delivery']) &&
        if(!empty($post['antenatal_care_period']))
        {   
              $dateDiff = (strtotime($today_date)-strtotime($post['antenatal_care_period']))/86400; 
            $week = intval($dateDiff/7); 
            $day = $dateDiff%7;
            $new_date = 'Week '.$week.' Day '.$day; 
            echo $new_date;
        }  
    }
    
    public function get_gestational_date()
    {
        $post = $this->input->post(); //current_date -lmp
        $today_date = date('d-m-Y');  //$post['confirm_delivery']
        
        $antenatal_care_period = $post['antenatal_care_period'];
        //if(!empty($post['confirm_delivery']) && !empty($post['antenatal_expected_date']))
        //!empty($post['confirm_delivery']) &&
        if(!empty($post['antenatal_care_period']))
        {   
              $dateDiff = (strtotime($today_date)-strtotime($post['antenatal_care_period']))/86400; 
            $week = intval($dateDiff/7); 
            $day = $dateDiff%7;
            $new_date = 'Week '.$week.' Day '.$day; 
            echo $new_date;
        }  
    }
    
     public function get_gestational_days_date()
    {
        $post = $this->input->post(); 
        $today_date = date('d-m-Y');
        // && !empty($post['antenatal_expected_date'])
        if(!empty($post['confirm_delivery']))
        {   
              $dateDiff = (strtotime($post['confirm_delivery'])-strtotime($today_date))/86400; 
            $week = intval($dateDiff/7); 
            $day = $dateDiff%7;
            $new_date = 'Week '.$week.' Day '.$day; 
            echo $new_date;
        }  
    }
    
    
    
    
    public function add_fertility_data()
    { 
        $post = $this->input->post();
        $fertility_data = $this->session->userdata('fertility_data');
        //echo "<pre>"; print_r($fertility_data);die;
        if(!isset($fertility_data))
        {
            $this->session->set_userdata('fertility_data', []);
            $fertility_data = $this->session->userdata('fertility_data');
        }
        
        $fertility_uploadhsg = '';
        if(isset($_FILES['fertility_uploadhsg']) || !empty($_FILES['fertility_uploadhsg']['name']))
          {
            $config['upload_path']   = DIR_UPLOAD_PATH.'prescription/hsg/';  
            $config['allowed_types'] = 'pdf|jpeg|jpg|png'; 
            $config['max_size']      = 2000; 
            $config['encrypt_name'] = TRUE; 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if($this->upload->do_upload('fertility_uploadhsg')) 
            {
              $file_data = $this->upload->data(); 
              $fertility_uploadhsg = $file_data['file_name'];
            }
          }
          
          $fertility_laparoscopy = ''; 
          
        if(isset($_FILES['fertility_laparoscopy']) || !empty($_FILES['fertility_laparoscopy']['name']))
          {
            $config['upload_path']   = DIR_UPLOAD_PATH.'prescription/laparoscopy/';    
            $config['max_size']      = 10000; 
            $config['encrypt_name'] = TRUE; 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if($this->upload->do_upload('fertility_laparoscopy')) 
            {
              $file_data = $this->upload->data(); 
              $fertility_laparoscopy = $file_data['file_name'];
            }
          } 
          
          $fertility_ultrasound_images = '';
          
          if(isset($_FILES['fertility_ultrasound_images']) || !empty($_FILES['fertility_ultrasound_images']['name']))
          {
            $config['upload_path']   = DIR_UPLOAD_PATH.'prescription/ultrasound/';  
            $config['allowed_types'] = 'pdf|jpeg|jpg|png'; 
            $config['max_size']      = 2000; 
            $config['encrypt_name'] = TRUE; 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if($this->upload->do_upload('fertility_ultrasound_images')) 
            {
              $file_data = $this->upload->data(); 
              $fertility_ultrasound_images = $file_data['file_name'];
            }
          }
          $file_arr = array("fertility_uploadhsg"=>$fertility_uploadhsg, "fertility_laparoscopy"=>$fertility_laparoscopy, "fertility_ultrasound_images"=>$fertility_ultrasound_images); 
          $new_arr = array_merge($post,$file_arr);
          $fertility_data[] = $new_arr;
          //echo "<pre>"; print_r($fertility_data);die;
          $this->session->set_userdata('fertility_data', $fertility_data);
          
    }
    
    
    public function load_fertility_data()
    {
        $fertility_data = $this->session->userdata('fertility_data');
        if(!empty($fertility_data))
        {
            
            $i=1;
            $tr_data = '';
            foreach($fertility_data as $key=>$fertility)
            {
                /*<img src="<?php echo DIR_FS_PATH.'prescription/hsg/'.$fertility_uploadhsg; ?>" width="100px;">*/
                $tr = '';
                $tr .= '<tr>';
                $tr .= '<td><input class="fertility_checkbox part_checkbox_fertility" type="checkbox" name="fertility_ids[]" value="'.$key.'" /></td>';
                $tr .= '<td>'.$i.'</td>';
                $tr .= '<td>'.$fertility['fertility_co'].'</td>';
                $tr .= '<td>'.$fertility['fertility_risk'].'</td>';
                $tr .= '<td>'.$fertility['fertility_uterine_factor'].'</td>';
                $tr .= '<td>'.$fertility['fertility_tubal_factor'].'</td>';
                $tr .= '<td>'.$fertility['fertility_decision'].'</td>'; 
                $tr .= '<td>'.$fertility['fertility_ovarian_factor'].'</td>';
                
                if(!empty($fertility['fertility_uploadhsg']))
                {
                    $hsg_path = ROOT_UPLOADS_PATH.'prescription/hsg/'.$fertility['fertility_uploadhsg'];
                    $tr .= "<td><a href='".$hsg_path."' target='_blank'><img src='".$hsg_path."' width='60px;'></a></td>";
                }
                else
                {
                    $tr .= '<td></td>'; 
                }
                
                if(!empty($fertility['fertility_laparoscopy']))
                {
                    $fertility_laparoscopy_path = ROOT_UPLOADS_PATH.'prescription/laparoscopy/'.$fertility['fertility_laparoscopy'];
                    $laparoscopy_path_icon = ROOT_IMAGES_PATH.'video.png';
                    $tr .= "<td><a href='".$fertility_laparoscopy_path."' target='_blank'><img src='".$laparoscopy_path_icon."' width='60px;'></a></td>";
                }
                else
                {
                    $tr .= '<td></td>'; 
                }
                
                if(!empty($fertility['fertility_ultrasound_images']))
                {
                    $ultrasound_path = ROOT_UPLOADS_PATH.'prescription/ultrasound/'.$fertility['fertility_ultrasound_images'];
                    $tr .= "<td><a href='".$ultrasound_path."' target='_blank'><img src='".$ultrasound_path."' width='60px;'></a></td>";
                }
                else
                {
                    $tr .= '<td></td>'; 
                }  
                
                
                $tr .= '<td>'.$fertility['fertility_male_factor'].'</td>';
                $tr .= '<td>'.$fertility['fertility_sperm_date'].'</td>';
                $tr .= '<td>'.$fertility['fertility_sperm_count'].'</td>';
                $tr .= '<td>'.$fertility['fertility_sperm_motality'].'</td>';
                $tr .= '<td>'.$fertility['fertility_sperm_g3'].'</td>';
                $tr .= '<td>'.$fertility['fertility_sperm_abnform'].'</td>';
                $tr .= '<td>'.$fertility['fertility_sperm_remarks'].'</td>';
                $tr .= '</tr>';
                $tr_data .= $tr;
                $i++; 
            }
            
        }
        else
        {
            $tr_data = '<tr><td colspan="18" style="color:red;">No Record Found</td></tr>';
        }
        echo $tr_data;
    }

    



}

?>