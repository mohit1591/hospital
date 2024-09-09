<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Add_prescription extends CI_Controller 
{
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
    $this->load->model('eye/add_prescription/add_prescription_model','add_prescript'); 
    $this->load->model('eye/add_prescription/prescription_file_model','prescription_file');
    $this->load->model('eye/biometric_details/biometric_details_model','biometric_model');
		$this->load->library('form_validation');
    }

    public function index($booking_id="")
    {
      
      /** load required models **/
      unauthorise_permission(248,1418);
      $users_data = $this->session->userdata('auth_users');
      $this->load->model('opd/opd_model','opd');
      $this->load->model('eye/general/eye_general_model', 'eye_general_model');
      $this->load->model('eye/prescription_template/prescription_template_model','prescription_template');
       $opd_booking_id=$this->uri->segment(3);
      /* biometric detail */
       $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
       $patient_id=$data['booking_data']['patient_id'];

        $data['ucva_bcva_data']=$this->biometric_model->get_biometric_details_ucva_bcva($opd_booking_id,$patient_id);
        $data['keratometer_details']=$this->biometric_model->get_biometric_details_keratometer($opd_booking_id,$patient_id);
        $data['iol_details']=$this->biometric_model->get_biometric_details_iol($opd_booking_id,$patient_id);
        $data['keratometer_data']=$this->biometric_model->get_keratometer_list();
        $data['iol_data']=$this->biometric_model->get_iol_section_list();
        /* biometric detail */


      /** load required models **/
     
      $data['prescription_tab_setting'] = get_eye_prescription_tab_setting();
      $data['prescription_medicine_tab_setting'] = get_eye_prescription_medicine_tab_setting();

      $opd_booking_data = $this->add_prescript->get_by_id($opd_booking_id);
      $data['page_title']="Eye Prescription";
   		if(!empty($opd_booking_data))
       	{
          $booking_id = $opd_booking_data['id'];
          $referral_doctor = $opd_booking_data['referral_doctor'];
          $booking_code = $opd_booking_data['booking_code'];
          $attended_doctor = $opd_booking_data['attended_doctor'];
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


   		$data['template_list'] = $this->prescription_template->eye_template_list(); // template dropdown 
   		$data['prv_history'] = $this->prescription_template->eye_prv_history_list();  // previous history dropdown
   		$data['chief_complaints'] = $this->eye_general_model->chief_complaint_list(); // chief complaint list
   		$data['personal_history'] = $this->prescription_template->eye_personal_history_list(); // personal history
   		$data['examination_list'] = $this->prescription_template->eye_examinations_list(); // examination history

   		$data['diagnosis_list'] = $this->prescription_template->eye_diagnosis_list();  
   		$data['suggetion_list'] = $this->prescription_template->eye_suggetion_list();  
   		$data['systemic_illness_list'] = $this->prescription_template->systemic_illness_list();  
   		$post = $this->input->post();

     


   		  $data['form_error'] = []; 
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
                                  "right_eye_file"=>"",
                                  "right_eye_file"=>'',
                                  "left_eye_file"=>'',
                                  "right_eye_dicussion"=>'',
                                  "left_eye_dicussion"=>'',
                                  "old_img_right"=>"",
                                  "left_eye_file"=>"",
                                  "old_img_left"=>"",
                                  // 'country_id'=>$country_id,
                                  // 'patient_bp'=>$patient_bp,
                                  // 'patient_temp'=>$patient_temp,
                                  // 'patient_weight'=>$patient_weight,
                                  // 'patient_spo2'=>$patient_spo2,
                                  // 'patient_height'=>$patient_height,
                                  // 'patient_rbs'=>$patient_rbs,
                                  'prv_history'=>"",
                                  'personal_history'=>"",
                                  'chief_complaints'=>'',
                                  'examination'=>'',
                                  'diagnosis'=>'',
                                  'suggestion'=>'',
                                  'systemic_illness'=>'',
                                  'remark'=>'',
                                  'next_appointment_date'=>"",
                                  'date_time_new' => '',
                                  'next_reason' => '',
                                  );
          if(isset($post) && !empty($post))
          {   

            /* biometric detail here */

            $data_array=array("ucva_nva_left"=>$this->input->post('ucva_nva_left'),
                              "ucva_nva_right"=>$this->input->post('ucva_nva_right'),
                              "ucva_dva_left"=>$this->input->post('ucva_dva_left'),
                              "ucva_dva_right"=>$this->input->post('ucva_dva_right'),
                              "bcva_sph_left"=>$this->input->post('bcva_sph_left'),
                              "bcva_sph_right"=>$this->input->post('bcva_sph_right'),
                              "bcva_cyl_left"=>$this->input->post('bcva_cyl_left'),
                              "bcva_cyl_right"=>$this->input->post('bcva_cyl_right'),
                              "bcva_axis_left"=>$this->input->post('bcva_axis_left'),
                              "bcva_axis_right"=>$this->input->post('bcva_axis_right'),
                              "bcva_add_left"=>$this->input->post('bcva_add_left'),
                              "bcva_add_right"=>$this->input->post('bcva_add_right'),
                              "bcva_dva_left"=>$this->input->post('bcva_dva_left'),
                              "bcva_dva_right"=>$this->input->post('bcva_dva_right'),
                              "bcva_nva_left"=>$this->input->post('bcva_nva_left'),
                              "bcva_nva_right"=>$this->input->post('bcva_nva_right'),
                              "branch_id"=>$this->input->post('branch_id'),
                              "opd_booking_id"=>$this->input->post('opd_booking_id'),
                              "patient_id"=>$this->input->post('patient_id'),
                              "created_by"=>$users_data['id'],
                              "ip_address"=>$_SERVER['REMOTE_ADDR'],
            );
            $this->biometric_model->common_pre_insert('hms_eye_biometric_details_ucva_bcva','1');
            $this->biometric_model->common_insert('hms_eye_biometric_details_ucva_bcva',$data_array);
            $keratometer_left=$this->input->post('kera_le');
            $keratometer_right=$this->input->post('kera_re');
            $kara_main_array=array();
            $this->biometric_model->common_pre_insert('hms_eye_biometric_details_keratometer','2');


            $kara_main_array=array();
            foreach($keratometer_left as $key=>$val)
            {
            $kera_array=array(
                              "kera_id"=>$key,
                              "right_eye"=>$keratometer_right[$key],
                              "left_eye"=>$keratometer_left[$key],
                              "branch_id"=>$this->input->post('branch_id'),
                              "opd_booking_id"=>$this->input->post('opd_booking_id'),
                              "patient_id"=>$this->input->post('patient_id'),
                              "created_by"=>$users_data['id'],
                              "ip_address"=>$_SERVER['REMOTE_ADDR'],
            );

            $this->biometric_model->common_insert('hms_eye_biometric_details_keratometer',$kera_array);
            }
            $iol_section_left=$this->input->post('iol_le');
            $iol_section_right=$this->input->post('iol_re');
            $kara_main_array=array();
            $this->biometric_model->common_pre_insert('hms_eye_biometric_details_iol','3');
            foreach($iol_section_left as $key=>$val)
            {
            $iol_array=array(
                          "iol_id"=>$key,
                          "right_eye"=>$iol_section_right[$key],
                          "left_eye"=>$iol_section_left[$key],
                          "branch_id"=>$this->input->post('branch_id'),
                          "opd_booking_id"=>$this->input->post('opd_booking_id'),
                          "patient_id"=>$this->input->post('patient_id'),
                          "created_by"=>$users_data['id'],
                          "ip_address"=>$_SERVER['REMOTE_ADDR'],
            );
            // print '<pre>'; print_r($iol_array);
           $this->biometric_model->common_insert('hms_eye_biometric_details_iol',$iol_array);
            }
            //die;
            /* biometric detail here */
          
              // print '<pre>'; print_r($_FILES);die;
             /* nasal test */
             if(!empty($_FILES['right_eye_file']['name']))
                { 

                    $config['upload_path']   = DIR_UPLOAD_PATH.'eye/right_eye_image/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    //print_r($config);die;
                    if($this->upload->do_upload('right_eye_file')) 
                    {
                     
                    $file_data = $this->upload->data(); 
                    $file_name1= $file_data['file_name'];
                    }
              
                }
                else
                {
                  $file_name1=''; 
                }


             if(!empty($_FILES['left_eye_file']['name']))
                { 
                    $this->upload->initialize($config);
                    $config['upload_path']   = DIR_UPLOAD_PATH.'eye/left_eye_image/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if($this->upload->do_upload('left_eye_file')) 
                    {
                     $file_data_left = $this->upload->data(); 
                     $file_name2= $file_data_left['file_name'];
                    } 
              }
              else
              {
                $file_name2='';
              }

               /* nasal test */
 

          $prescription_id = $this->add_prescript->save_prescription($file_name1,$file_name2);
          $this->session->set_userdata('eye_prescription_id',$prescription_id);
          $this->session->set_flashdata('success','Prescription successfully added.');
          redirect(base_url('prescription/?status=print_eye'));


          }   

        $this->load->model('next_appointment/Next_appointment_model', 'next_appointment_master');
        $data['next_appointment_list'] = $this->next_appointment_master->list();
    	$this->load->view('eye/add_prescription/prescription',$data);

    }

      public function edit($prescription_id="",$opd_booking_id="")
      { 
        $users_data = $this->session->userdata('auth_users');
        unauthorise_permission(248,1410);
      if(isset($prescription_id) && !empty($prescription_id) && is_numeric($prescription_id))
      {      
        $this->load->model('eye/general/eye_general_model', 'eye_general_model');
        $this->load->model('eye/prescription_template/prescription_template_model','prescription_template');
        $post = $this->input->post();

           /* biometric detail */
        $this->load->model('opd/opd_model','opd');
       $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
       $patient_id=$data['booking_data']['patient_id'];

        $data['ucva_bcva_data']=$this->biometric_model->get_biometric_details_ucva_bcva($opd_booking_id,$patient_id);
        $data['keratometer_details']=$this->biometric_model->get_biometric_details_keratometer($opd_booking_id,$patient_id);
        $data['iol_details']=$this->biometric_model->get_biometric_details_iol($opd_booking_id,$patient_id);
        $data['keratometer_data']=$this->biometric_model->get_keratometer_list();
        $data['iol_data']=$this->biometric_model->get_iol_section_list();
        /* biometric detail */

        $get_by_id_data = $this->add_prescript->get_by_prescription_id($prescription_id); 
        //echo "<pre>";print_r($get_by_id_data); exit;
        $prescription_data = $get_by_id_data['prescription_list'][0];
       //print '<pre>';  print_r($prescription_data);die;
        $prescription_test_list = $get_by_id_data['prescription_list']['prescription_test_list'];
        $prescription_presc_list = $get_by_id_data['prescription_list']['prescription_presc_list'];
        //print '<pre>'; print_r($prescription_presc_list);die;
        $diagnosis_list = $get_by_id_data['prescription_list']['diagnosis_list'];
        $systemic_illness = $get_by_id_data['prescription_list']['systemic_illness'];
        $cheif_compalin = $get_by_id_data['prescription_list']['cheif_compalin'];
        $data['prescription_tab_setting'] = get_eye_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_eye_prescription_medicine_tab_setting();
        // print '<pre>'; print_r($data['prescription_medicine_tab_setting']);die;
        //print '<pre>'; print_r($data['prescription_medicine_tab_setting']);die;
        $opd_booking_data = $this->add_prescript->get_by_id($prescription_id);
        
        $data['chief_complaints'] = $this->eye_general_model->chief_complaint_list(); // chief complaint list
        $data['examination_list'] = $this->prescription_template->eye_examinations_list(); // 
        $data['diagnosis_list'] = $this->prescription_template->eye_diagnosis_list();  
        $data['suggetion_list'] = $this->prescription_template->eye_suggetion_list();
        $data['prv_history'] = $this->prescription_template->eye_prv_history_list();  // previous history dropdown
        $data['personal_history'] = $this->prescription_template->eye_personal_history_list(); // 
        $data['template_list'] = $this->prescription_template->eye_template_list(); // template dropdown 
        $data['systemic_illness_list'] = $this->prescription_template->systemic_illness_list(); 
       
        $data['page_title'] = "Update Prescription";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        //print_r($prescription_data);
        //echo $prescription_data->next_appointment_date;
        if(!empty($prescription_data->next_appointment_date) && $prescription_data->next_appointment_date!='0000-00-00 00:00:00' && date('d-m-Y',strtotime($prescription_data->next_appointment_date))!='01-01-1970')
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
                              //'patient_bp'=>$prescription_data->patient_bp,
                              //'patient_temp'=>$prescription_data->patient_temp,
                              //'patient_weight'=>$prescription_data->patient_weight,
                              //'patient_spo2'=>$prescription_data->patient_spo2,
                              //'patient_height'=>$prescription_data->patient_height,
                              //'patient_rbs'=>$prescription_data->patient_rbs,
                              'prv_history'=>$prescription_data->prv_history,
                              'personal_history'=>$prescription_data->personal_history,
                              //'chief_complaints'=>$prescription_data->chief_complaints,
                              'examination'=>$prescription_data->examination,
                              //'diagnosis'=>$prescription_data->diagnosis,
                              'suggestion'=>$prescription_data->suggestion,
                              'remark'=>$prescription_data->remark,
                              "old_img_right"=>$prescription_data->right_eye_image,
                              "old_img_left"=>$prescription_data->left_eye_image,
                              "right_eye_file"=>$prescription_data->right_eye_image,
                              "left_eye_file"=>$prescription_data->left_eye_image,
                              "right_eye_dicussion"=>$prescription_data->right_eye_discussion,
                              "left_eye_dicussion"=>$prescription_data->left_eye_discussion,
                              'next_appointment_date'=>$next_appointmentdate,
                              "date_time_new" =>$prescription_data->date_time_new,
                              'next_reason'=>$prescription_data->next_reason
                              );

        $data['prescription_test_list'] = $prescription_test_list;
        

        $data['prescription_presc_list'] = $prescription_presc_list;  
        $data['cheif_template_data'] = $cheif_compalin;
        $data['systemic_illness_template_data'] =  $systemic_illness;
        $data['diagnosis_template_data'] = $diagnosis_list; 

        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                /*$this->prescription->save_prescription();
                $this->session->set_flashdata('success','Prescription updated successfully.');
                redirect(base_url('prescription'));*/


                /* biometric detail here */
                  $data_array=array("ucva_nva_left"=>$this->input->post('ucva_nva_left'),
                                    "ucva_nva_right"=>$this->input->post('ucva_nva_right'),
                                    "ucva_dva_left"=>$this->input->post('ucva_dva_left'),
                                    "ucva_dva_right"=>$this->input->post('ucva_dva_right'),
                                    "bcva_sph_left"=>$this->input->post('bcva_sph_left'),
                                    "bcva_sph_right"=>$this->input->post('bcva_sph_right'),
                                    "bcva_cyl_left"=>$this->input->post('bcva_cyl_left'),
                                    "bcva_cyl_right"=>$this->input->post('bcva_cyl_right'),
                                    "bcva_axis_left"=>$this->input->post('bcva_axis_left'),
                                    "bcva_axis_right"=>$this->input->post('bcva_axis_right'),
                                    "bcva_add_left"=>$this->input->post('bcva_add_left'),
                                    "bcva_add_right"=>$this->input->post('bcva_add_right'),
                                    "bcva_dva_left"=>$this->input->post('bcva_dva_left'),
                                    "bcva_dva_right"=>$this->input->post('bcva_dva_right'),
                                    "bcva_nva_left"=>$this->input->post('bcva_nva_left'),
                                    "bcva_nva_right"=>$this->input->post('bcva_nva_right'),
                                    "branch_id"=>$this->input->post('branch_id'),
                                    "opd_booking_id"=>$this->input->post('opd_booking_id'),
                                    "patient_id"=>$this->input->post('patient_id'),
                                    "created_by"=>$users_data['id'],
                                    "ip_address"=>$_SERVER['REMOTE_ADDR'],
                  );
                  //print '<pre>'; print_r($data_array);die;
                  $this->biometric_model->common_pre_insert('hms_eye_biometric_details_ucva_bcva','1');
                  $this->biometric_model->common_insert('hms_eye_biometric_details_ucva_bcva',$data_array);
                  $keratometer_left=$this->input->post('kera_le');
                  $keratometer_right=$this->input->post('kera_re');
                  $kara_main_array=array();
                  $this->biometric_model->common_pre_insert('hms_eye_biometric_details_keratometer','2');
                  foreach($keratometer_left as $key=>$val)
                  {
                  $kera_array=array(
                                    "kera_id"=>$key,
                                    "right_eye"=>$keratometer_right[$key],
                                    "left_eye"=>$keratometer_left[$key],
                                    "branch_id"=>$this->input->post('branch_id'),
                                    "opd_booking_id"=>$this->input->post('opd_booking_id'),
                                    "patient_id"=>$this->input->post('patient_id'),
                                    "created_by"=>$users_data['id'],
                                    "ip_address"=>$_SERVER['REMOTE_ADDR'],
                  );

                  $this->biometric_model->common_insert('hms_eye_biometric_details_keratometer',$kera_array);
                  }
                  $iol_section_left=$this->input->post('iol_le');
                  $iol_section_right=$this->input->post('iol_re');
                  $kara_main_array=array();
                  $this->biometric_model->common_pre_insert('hms_eye_biometric_details_iol','3');
                  foreach($iol_section_left as $key=>$val)
                  {
                  $iol_array=array(
                                "iol_id"=>$key,
                                "right_eye"=>$iol_section_right[$key],
                                "left_eye"=>$iol_section_left[$key],
                                "branch_id"=>$this->input->post('branch_id'),
                                "opd_booking_id"=>$this->input->post('opd_booking_id'),
                                "patient_id"=>$this->input->post('patient_id'),
                                "created_by"=>$users_data['id'],
                                "ip_address"=>$_SERVER['REMOTE_ADDR'],
                  );
                  // print '<pre>'; print_r($iol_array);
                  $this->biometric_model->common_insert('hms_eye_biometric_details_iol',$iol_array);
                  }
                  /* biometric detail here */

                  /* nasal test */

                if(!empty($_FILES['right_eye_file']['name']))
                { 

                    $config['upload_path']   = DIR_UPLOAD_PATH.'eye/right_eye_image/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    //print_r($config);die;
                    if($this->upload->do_upload('right_eye_file')) 
                    {
                     
                    $file_data = $this->upload->data(); 
                    $file_name1= $file_data['file_name'];
                    }
              
                }
                else
                {
                  $file_name1= $post['old_img_right'];
                }


             if(!empty($_FILES['left_eye_file']['name']))
                { 
                    $this->upload->initialize($config);
                    $config['upload_path']   = DIR_UPLOAD_PATH.'eye/left_eye_image/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if($this->upload->do_upload('left_eye_file')) 
                    {
                     $file_data_left = $this->upload->data(); 
                     $file_name2= $file_data_left['file_name'];
                    } 
              }
             else
             {
              $file_name2= $post['old_img_left'];
             }


               /* nasal test */

                $prescription_id = $this->add_prescript->save_prescription($file_name1,$file_name2);

                $this->session->set_userdata('eye_prescription_id',$prescription_id);
                $this->session->set_flashdata('success','Prescription updated successfully.');
                redirect(base_url('prescription/?status=print_eye'));

                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            } 

            

        }

        $this->load->model('next_appointment/Next_appointment_model', 'next_appointment_master');
        $data['next_appointment_list'] = $this->next_appointment_master->list();
       $this->load->view('eye/add_prescription/prescription',$data);       
      }
    }
    private function _validate()
    {
        $field_list = mandatory_section_field_list(4);
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
        
       
       
        if ($this->form_validation->run() == FALSE) 
        {  
           
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'booking_code'=>$post['booking_code'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        'gender'=>$post['gender'],
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        'address'=>$post['address'],
                                        /*'patient_bp'=>$post['patient_bp'],
                                        'patient_temp'=>$post['patient_temp'],
                                        'patient_weight'=>$post['patient_weight'],
                                        'patient_height'=>$post['patient_height'],
                                        'patient_sop'=>$post['patient_sop'],
                                        'patient_rbs'=>$post['patient_rbs'],*/
                                        'diseases'=>$post['diseases'],
                                        
                                        
                                       ); 
            return $data['form_data'];
        }   
    }

    function get_template_data($template_id="")
    {
      $this->load->model('eye/add_prescription/add_prescription_model','add_prescript');
      if($template_id>0)
      {
        $templatedata = $this->add_prescript->template_data($template_id);
        //print_r($templatedata);die;
        echo $templatedata;
      }
    }

    function get_template_test_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->add_prescript->get_template_test_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_template_prescription_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->add_prescript->get_template_prescription_data($template_id);
        echo $templatetestdata;
      }
    }

   function get_diagnosis_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->add_prescript->get_diagnosis_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_cheif_complain_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->add_prescript->get_cheif_complain_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_systemic_illness_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->add_prescript->get_systemic_illness_data($template_id);
        echo $templatetestdata;
      }
    }

   public function print_prescriptions($prescription_id="",$branch_id='')
    {

        unauthorise_permission(248,1449);
        $prescriptions_id= $this->session->userdata('eye_prescription_id');
        if(!empty($prescriptions_id))
        {
            $prescription_id = $prescriptions_id;
        }
        else
        {
            $prescription_id =$prescription_id;
        }
          
        $data['type'] = 2;
        $data['prescription_id'] = $prescription_id;
        $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->add_prescript->get_detail_by_prescription_id($prescription_id);

        $template_format = $this->add_prescript->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->add_prescript->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->add_prescript->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->add_prescript->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->add_prescript->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        //print_r($data['template_data']);die;
        $data['all_detail']= $opd_prescription_info;
         //print '<pre>';print_r($data['all_detail']);die;
        $opd_booking_id=$data['all_detail']['prescription_list'][0]->booking_id;
        $data['prescription_tab_setting'] = get_eye_prescription_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_eye_prescription_medicine_tab_setting('',$branch_id);

        //echo "<pre>";print_r($data['all_detail']['prescription_list']); exit;
        $signature_image = get_doctor_signature($data['all_detail']['prescription_list'][0]->attended_doctor);
        
   $signature_reprt_data ='';
   if(!empty($signature_image))
   {
   
    $signature_reprt_data .= '<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse; font-size:13px; margin-top: 5%;"> 
    <tr>
        <td width="70%"></td>
        <td valign="top" align="center"><b>Signature</b></td>
    </tr>';

if (!empty($signature_image->signature) && file_exists(DIR_UPLOAD_PATH . 'doctor_signature/' . $signature_image->signature)) {
    $signature_reprt_data .= '<tr>
        <td width="70%"></td>
        <td valign="top" align="center"><img width="90px" src="' . ROOT_UPLOADS_PATH . 'doctor_signature/' . $signature_image->signature . '"></td>
    </tr>';
}

$signature_reprt_data .= '<tr>
        <td width="70%"></td>
        <td valign="top" align="center"><small><i>Dr. ' . nl2br($signature_image->doctor_name) . '</i></small></td>
    </tr>
    <tr>
        <td width="70%"></td>
        <td valign="top" align="center"><small><i>' . nl2br($signature_image->qualification) . '</i></small></td>
    </tr>
    <tr>
        <td width="70%"></td>
        <td valign="top" align="center"><small><i>' . nl2br($signature_image->doc_reg_no) . '</i></small></td>
    </tr>
</table>';


   }
      /* biometric detail */
      $this->load->model('opd/opd_model','opd');
      $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
       $patient_id=$data['booking_data']['patient_id'];

        $data['ucva_bcva_data']=$this->biometric_model->get_biometric_details_ucva_bcva($opd_booking_id,$patient_id);
        $data['keratometer_details']=$this->biometric_model->get_biometric_details_keratometer($opd_booking_id,$patient_id);
        $data['iol_details']=$this->biometric_model->get_biometric_details_iol($opd_booking_id,$patient_id);
        //print_r($data['iol_details']);die;
        $data['keratometer_data']=$this->biometric_model->get_keratometer_list();

        $data['iol_data']=$this->biometric_model->get_iol_section_list();
        /* biometric detail */



        $data['signature'] = $signature_reprt_data;
        //$data['file_name'] = $file_name;
        $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();

        $this->load->view('eye/add_prescription/print_prescription_template',$data);
    }  
    public function view_prescription($id,$branch_id='')
    { 
        unauthorise_permission(248,1448);
        $data['form_data'] = $this->add_prescript->get_by_ids($id);
        $data['prescription_data'] = $this->add_prescript->get_eye_prescription($id); 
        //print_r();die;
        $data['test_data'] = $this->add_prescript->get_eye_test($id); 
        $data['page_title'] = $data['form_data']['patient_name']." Prescription";
        $data['prescription_tab_setting'] = get_eye_prescription_tab_setting('',$branch_id);
        $data['prescription_medicine_tab_setting'] = get_eye_prescription_medicine_tab_setting('',$branch_id);
         $data['id'] = $id;
          $opd_prescription_info = $this->add_prescript->get_detail_by_prescription_id($id);
          //print_r($opd_prescription_info);die;
         $data['all_detail']= $opd_prescription_info;

        /* biometric detail */
          $opd_booking_id=$data['all_detail']['prescription_list'][0]->booking_id;
        $this->load->model('opd/opd_model','opd');
        $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
        $patient_id=$data['booking_data']['patient_id'];

        $data['ucva_bcva_data']=$this->biometric_model->get_biometric_details_ucva_bcva($opd_booking_id,$patient_id);
        $data['keratometer_details']=$this->biometric_model->get_biometric_details_keratometer($opd_booking_id,$patient_id);
        $data['iol_details']=$this->biometric_model->get_biometric_details_iol($opd_booking_id,$patient_id);
        //print_r($data['iol_details']);die;
        $data['keratometer_data']=$this->biometric_model->get_keratometer_list();

        $data['iol_data']=$this->biometric_model->get_iol_section_list();
        /* biometric detail */

       
        $this->load->view('eye/add_prescription/print_template1',$data);
        $html = $this->output->get_output();
    }

public function upload_eye_prescription($prescription_id='',$booking_id='')
{ 
    unauthorise_permission(248,1450);
    
    if($prescription_id=='0' && !empty($booking_id))
    {
      $this->load->model('opd/opd_model','opd');
      $prescription_id=$this->opd->save_prescription_before_file_upload($booking_id);
    }
    
    $data['page_title'] = 'Upload Eye Prescription';   
    $data['form_error'] = [];
    $data['prescription_files_error'] = [];
    $post = $this->input->post();

    $data['form_data'] = array(
                                 'data_id'=>'',
                                 'prescription_id'=>$prescription_id,
                                 'old_prescription_files'=>''
                              );

    if(isset($post) && !empty($post))
    {   

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        $this->form_validation->set_rules('prescription_id', 'prescription', 'trim|required'); 
        if(!isset($_FILES['prescription_files']) || empty($_FILES['prescription_files']['name']))
        {
          $this->form_validation->set_rules('prescription_files', 'prescription file', 'trim|required');  
        }
        
        
        if($this->form_validation->run() == TRUE)
        {
             $config['upload_path']   = DIR_UPLOAD_PATH.'eye/prescription/';  
             $config['allowed_types'] = 'pdf|jpeg|jpg|png|xls|csv'; 
             $config['max_size']      = 1000; 
             $config['encrypt_name'] = TRUE; 
             $this->load->library('upload', $config);
             
             if ($this->upload->do_upload('prescription_files')) 
             {
                $file_data = $this->upload->data(); 
                $this->add_prescript->save_file($file_data['file_name']);
                echo 1;
                return false;
              } 
             else
              { 
                $data['prescription_files_error'] = $this->upload->display_errors();
                $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'prescription_id'=>$post['prescription_id'],
                                       'old_prescription_files'=>$post['old_prescription_files']
                                       );
              }   
        }
        else
        {
          
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'prescription_id'=>$post['prescription_id'], 
                                        'old_prescription_files'=>$post['old_prescription_files']
                                       );
            $data['form_error'] = validation_errors();
            

        }   

    }

    //$data['uploaded_files'] = $this->prescription->get_uploaded_files($prescription_id);


    $this->load->view('eye/add_prescription/add_file',$data);
}

public function view_files($prescription_id)
{ 
  unauthorise_permission(248,1447);
  $data['page_title'] = "Eye Prescription Files";
  $data['prescription_id'] = $prescription_id;
  $this->load->view('eye/add_prescription/view_prescription_files',$data);
}

public function ajax_file_list($prescription_id='')
{ 
        
        unauthorise_permission(248,1447);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->prescription_file->get_datatables($prescription_id);
        //print '<pre>'; print_r($list);die;  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescription) { 
            $no++;
            $row = array();
            if($prescription->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
                        
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
            $row[] = '<input type="checkbox" name="prescription[]" class="checklist" value="'.$prescription->id.'">'.$check_script;

            $sign_img = "";
            if(!empty($prescription->prescription_files) && file_exists(DIR_UPLOAD_PATH.'eye/prescription/'.$prescription->prescription_files))
            {
                $sign_img = ROOT_UPLOADS_PATH.'eye/prescription/'.$prescription->prescription_files;
                //$sign_img = '<img src="'.$sign_img.'" width="100px" />';
                 $sign_img = '<a href="'.$sign_img.'" download><img src="'.$sign_img.'" width="100px"/></a>';
            }

            $row[] = $sign_img;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($prescription->created_date));  

            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_delete = "";
            $btn_view_pre ="";
            $btn_print_pre="";
            $btn_upload_pre="";
            $btn_view_upload_pre="";
            
            $row[] = '<a class="btn-custom" onClick="return delete_prescription_file('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';                 
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription_file->count_all($prescription_id),
                        "recordsFiltered" => $this->prescription_file->count_filtered($prescription_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    

    public function delete_prescription_file($id="")
    {
       unauthorise_permission(248,1411);
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_file->delete($id);
           $response = "Prescription file successfully deleted.";
           echo $response;
       }
    }
    public function delete_eye($id="")
    {
       unauthorise_permission(248,1411);
       if(!empty($id) && $id>0)
       {
           $result = $this->add_prescript->delete_eye($id);
           $response = "Prescription successfully deleted.";
           echo $response;
       }
    }

    function deleteall_prescription_file()
    {
        unauthorise_permission(248,1411);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_file->deleteall($post['row_id']);
            $response = "Prescription file successfully deleted.";
            echo $response;
        }
    }

    public function print_blank_prescriptions($booking_id="",$branch_id='')
    {
        unauthorise_permission(248,1420);
        $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->add_prescript->get_detail_by_booking_id($booking_id,$branch_id);
        $template_format = $this->add_prescript->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>1),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->add_prescript->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>1),$branch_id);
        $template_format_right = $this->add_prescript->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>1),$branch_id);
        $template_format_top = $this->add_prescript->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>1));
        $template_format_bottom = $this->add_prescript->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>1),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_eye_prescription_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_eye_prescription_medicine_tab_setting('',$branch_id);
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $data['prescription_id'] = $booking_id;
        $data['type'] = 1;

         $this->load->view('eye/add_prescription/print_prescription_template',$data);
    }

    public function search_box_data()
    {
      $this->load->model('opd/opd_model','opd');
      $this->load->model('eye/general/eye_general_model', 'eye_general_model');
      $this->load->model('eye/prescription_template/prescription_template_model','prescription_template');
        $post = $this->input->post();
        $type = $post['type'];
        $output = [];
        if($post['class'] == 'chief_complaints_data'){
          $getData = $this->eye_general_model->chief_complaint_list("",$type); 
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->chief_complaints
            );
          }
        } else if ($post['class'] == 'prv_history_data') {
          $getData = $this->prescription_template->eye_prv_history_list($type);
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->prv_history
            );
          }
        } else if($post['class'] == 'personal_history_data') {
          $getData = $this->opd->prescription_template->eye_personal_history_list($type);
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->personal_history
            );
          }
        } else if($post['class'] == 'examination_data') {
          $getData = $this->prescription_template->eye_examinations_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->examination
            );
          }
        } else if($post['class'] == 'diagnosis_data') {
          $getData = $this->prescription_template->eye_diagnosis_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->diagnosis
            );
          }
        } else if($post['class'] == 'suggestion_data') {
          $getData = $this->prescription_template->eye_suggetion_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->medicine_suggetion
            );
          }
        } else if($post['class'] == 'systemic_illness_data') {
          $getData = $this->prescription_template->systemic_illness_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->systemic_illness
            );
          }
        }
        echo json_encode($output);
    }
// Please write code above this 
}
?>
