<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Add_prescription extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();  
        auth_users();  
        $this->load->model('pediatrician/add_prescription/prescription_model','prescription');
        $this->load->model('pediatrician/add_prescription/Pediatrician_file_model','pediatrician_files_model');
        $this->load->library('form_validation');
    }

    //pedic Prescription 

    public function prescription($booking_id="")
    {
        $data['prescription_tab_setting'] = get_pediatrician_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_pediatrician_prescription_medicine_tab_setting();

        $this->load->model('general/general_model'); 
        
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $post = $this->input->post();
        $patient_id = "";
        $patient_code = "";
        $simulation_id = "";
        $patient_name = "";
        $mobile_no = "";
        $gender = "";
        $age_y = "";
        $age_m = "";
        $age_d = "";
        $address = "";
        $city_id = "";
        $state_id = "";
        $country_id = ""; 
      
        if($booking_id>0)
        {

           $this->load->model('opd/opd_model');
           $this->load->model('patient/patient_model');
           $this->load->model('pediatrician/prescription_template/prescription_template_model','prescriptiontemplate');
    
           $opd_booking_data = $this->opd_model->get_by_id($booking_id);
           //echo "<pre>";print_r($opd_booking_data); exit;
           if(!empty($opd_booking_data))
           {
               
               //present age of patient
               
              if($opd_booking_data['dob']=='1970-01-01' || $opd_booking_data['dob']=="0000-00-00")
              {
                $present_age = get_patient_present_age('',$opd_booking_data);
                //echo "<pre>"; print_r($present_age);
              }
              else
              {
                $dob=date('d-m-Y',strtotime($opd_booking_data['dob']));
                
                $present_age = get_patient_present_age($dob,$opd_booking_data);
              }
              
              $age_y = $present_age['age_y'];
              $age_m = $present_age['age_m'];
              $age_d = $present_age['age_d'];
              //$age_h = $present_age['age_h'];
              //present age of patient
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
              /*$age_y = $opd_booking_data['age_y'];
              $age_m = $opd_booking_data['age_m'];
              $age_d = $opd_booking_data['age_d'];*/
              $address = $opd_booking_data['address'];
              $city_id = $opd_booking_data['city_id'];
              $state_id = $opd_booking_data['state_id'];
              $country_id = $opd_booking_data['country_id']; 
              $appointment_date = $opd_booking_data['appointment_date'];
              
              $relation_name = $opd_booking_data['relation_name'];
              $relation_type = $opd_booking_data['relation_type'];
              $relation_simulation_id = $opd_booking_data['relation_simulation_id'];
              
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


        }
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        
        $this->load->model('pediatrician/general/pediatrician_general_model','pediatrician_general'); 
        $data['chief_complaints'] = $this->pediatrician_general->chief_complaint_list(); 
        $data['examination_list'] = $this->prescription->examinations_list();
        //print_r($data['examination_list']);die;
        $this->load->model('opd/opd_model','opd'); 
        $data['diagnosis_list'] = $this->opd->diagnosis_list();  
        $data['suggetion_list'] = $this->prescription->suggetion_list(); 
        $data['prv_history'] = $this->prescription->prv_history_list();   
        $data['personal_history'] = $this->prescription->personal_history_list();  
        $data['template_list'] = $this->prescription->template_list();    
        $data['page_title'] = "Prescription";
                
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
                                  'country_id'=>$country_id,
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
                                  'remark'=>'',
                                  'next_appointment_date'=>"",
                                  "relation_name"=>$relation_name,
                                  "relation_type"=>$relation_type,
                                  "relation_simulation_id"=>$relation_simulation_id,
                                  'next_reason' => ""
                                  );
          if(isset($post) && !empty($post))
          {   
            //echo "<pre>"; print_r($post); exit;
            /*$this->opd->save_prescription();
            $this->session->set_flashdata('success','Prescription successfully added.');
            redirect(base_url('prescription'));*/

            $prescription_id = $this->prescription->save_prescription();
            $this->session->set_userdata('pediatrician_prescription_id',$prescription_id);
            $this->session->set_flashdata('success','Prescription successfully added.');
            redirect(base_url('prescription/?status=print_pediatrician'));


          }   
          $this->load->model('next_appointment/Next_appointment_model', 'next_appointment_master');
                $data['next_appointment_list'] = $this->next_appointment_master->list();

         $this->load->view('pediatrician/add_prescription/prescription',$data);
    }

     public function edit($prescription_id="",$opd_booking_id="")
      { 

        $users_data = $this->session->userdata('auth_users');
        //unauthorise_permission(248,1410);
      if(isset($prescription_id) && !empty($prescription_id) && is_numeric($prescription_id))
      {      
        $this->load->model('pediatrician/general/pediatrician_general_model', 'pediatrician_general_model');
        $this->load->model('pediatrician/prescription_template/prescription_template_model','prescription_template');
        $post = $this->input->post();

           /* biometric detail */
          $this->load->model('opd/opd_model','opd');
          $this->load->model('general/general_model'); 
          $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
          $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
          $patient_id=$data['booking_data']['patient_id'];

      

        $get_by_id_data = $this->prescription->get_by_prescription_id($prescription_id); 
        //echo "<pre>";print_r($get_by_id_data); exit;
        $prescription_data = $get_by_id_data['prescription_list'][0];
       //print '<pre>';  print_r($prescription_data);die;
        $prescription_test_list = $get_by_id_data['prescription_list']['prescription_test_list'];


        $prescription_presc_list = $get_by_id_data['prescription_list']['prescription_presc_list'];
        //print '<pre>'; print_r($prescription_presc_list);die;
        $data['prescription_tab_setting'] = get_pediatrician_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_pediatrician_prescription_medicine_tab_setting();
        // print '<pre>'; print_r($data['prescription_medicine_tab_setting']);die;
        //print '<pre>'; print_r($data['prescription_medicine_tab_setting']);die;
        $opd_booking_data = $this->prescription->get_by_id($prescription_id);
        
        $data['chief_complaints'] = $this->pediatrician_general_model->chief_complaint_list(); // chief 

        $data['examination_list'] = $this->prescription->examinations_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $this->load->model('opd/opd_model','opd'); 
        $data['diagnosis_list'] = $this->opd->diagnosis_list();  
        $data['suggetion_list'] = $this->prescription->suggetion_list(); 
        $data['prv_history'] = $this->prescription->prv_history_list();   
        $data['personal_history'] = $this->prescription->personal_history_list();  
        $data['template_list'] = $this->prescription->template_list();    
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['page_title'] = "Update Prescription";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        //print_r($data['vitals_list']);
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
                              'suggestion'=>$prescription_data->suggestion,
                             
                              'prv_history'=>$prescription_data->prv_history,
                              'personal_history'=>$prescription_data->personal_history,
                              'chief_complaints'=>$prescription_data->chief_complaints,
                              'examination'=>$prescription_data->examination,
                              'diagnosis'=>$prescription_data->diagnosis,
                              'remark'=>$prescription_data->remark,
                              'next_appointment_date'=>$next_appointmentdate,
                              "relation_name"=>$prescription_data->relation_name,
                              "relation_type"=>$prescription_data->relation_type,
                              "relation_simulation_id"=>$prescription_data->relation_simulation_id,
                              "date_time_new" =>$prescription_data->date_time_new,
                              "next_reason"=>$prescription_data->next_reason
                              );

        $data['prescription_test_list'] = $prescription_test_list;
        

        $data['prescription_presc_list'] = $prescription_presc_list;  
        // $data['cheif_template_data'] = $cheif_compalin;
        // $data['systemic_illness_template_data'] =  $systemic_illness;
        // $data['diagnosis_template_data'] = $diagnosis_list; 

        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validatepre();
            if($this->form_validation->run() == TRUE)
            {
                $prescription_id = $this->prescription->save_prescription();

                $this->session->set_userdata('pediatrician_prescription_id',$prescription_id);
                $this->session->set_flashdata('success','Prescription updated successfully.');
                redirect(base_url('prescription/?status=print_pediatrician'));

                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            } 

            

        }
        $this->load->model('next_appointment/Next_appointment_model', 'next_appointment_master');
      $data['next_appointment_list'] = $this->next_appointment_master->list();
        $this->load->view('pediatrician/add_prescription/edit_prescription',$data);       
      }
    }
     private function _validatepre()
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
      if($template_id>0)
      {
        $templatedata = $this->prescription->template_data($template_id);
        echo $templatedata;
      }
    }



    function get_template_test_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->prescription->get_template_test_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_template_prescription_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->prescription->get_template_prescription_data($template_id);
        
        echo $templatetestdata;
      }
    }
    

   function complaints_name($complaints_id="")
   {
      if($complaints_id>0)
      {
         $complaintsname = $this->prescription->compalaints_list($complaints_id);
         echo $complaintsname;
      }
    }

   

    function examination_name($examination_id="")
    {
      if($examination_id>0)
      {
         $examination_name = $this->prescription->examination_list($examination_id);
         echo $examination_name;
      }
    }

    function diagnosis_name($diagnosis_id="")
    {
      if($diagnosis_id>0)
      {
        $this->load->model('opd/opd_model','opd'); 
         

         $diagnosis_name = $this->opd->diagnosis_name($diagnosis_id);
         echo $diagnosis_name;
      }
    }
    //hms_opd_suggetion
    function suggetion_name($suggetion_id="")
    {
      if($suggetion_id>0)
      {
         $suggetion_name = $this->prescription->suggetion_name($suggetion_id);
         echo $suggetion_name;
      }
    }

    function personal_history_name($personal_his_id="")
    {
      if($personal_his_id>0)
      {

         $personal_his_name = $this->prescription->personal_history_name($personal_his_id);
         echo $personal_his_name;
      }
    }

    function prv_history_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->prescription->prv_history_name($pre_id);
         echo $pre_name;
      }
    }


    public function particular_billing_list()
    {
        unauthorise_permission(85,121);
        $data['page_title'] = 'OPD Particular Billing list'; 
        $this->load->view('opd/particular_billing_list',$data);
    }

    

    public function billing($pid="")
    {
        unauthorise_permission(85,530);
        //$this->session->unset_userdata('billing_data_array');
        $this->load->model('general/general_model');  
        $post = $this->input->post();
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
        $patient_email="";
        $quantity="1";
        $amount ="";
        $charges = "";
        $balance_amount = '0.00';
        $particulars ="";
        $city_id='';
        $state_id="";
        $country_id="99";
        $attended_doctor="";
        $diseases="";
        $type=1;
        $ref_by_other ="";
        $referral_doctor="";
        if($pid>0)
        {
           $this->load->model('patient/patient_model');
           $patient_data = $this->patient_model->get_by_id($pid);
           if(!empty($patient_data))
           {
              $patient_id = $patient_data['id'];
              $patient_email = $patient_data['patient_email'];
              $patient_code = $patient_data['patient_code'];
              $simulation_id = $patient_data['simulation_id'];
              $patient_name = $patient_data['patient_name'];
              $mobile_no = $patient_data['mobile_no'];
              $gender = $patient_data['gender'];
              $age_y = $patient_data['age_y'];
              $age_m = $patient_data['age_m'];
              $age_d = $patient_data['age_d'];
              $address = $patient_data['address'];
              $city_id = $patient_data['city_id'];
              $state_id = $patient_data['state_id'];
              $country_id = $patient_data['country_id'];
              $patient_email = $patient_data['patient_email'];

              /*$charges = $patient_data['charges'];
              $amount = $form_data['amount'];
              $quantity  = $form_data['quantity'];*/
           }
        }
        $data['diseases_list'] = $this->opd->diseases_list();

            $data['simulation_list'] = $this->general_model->simulation_list();
            $data['particulars_list'] = $this->general_model->particulars_list();
            $data['country_list'] = $this->general_model->country_list();
        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
            $data['page_title'] = "OPD Billings";  
        $post = $this->input->post();
        if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }
        
        $booking_code = generate_unique_id(9);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'patient_code'=>$patient_code,
                                  'booking_code'=>$booking_code,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'dept_id'=>"",
                                  'attended_doctor'=>$attended_doctor,
                                  'address'=>$address,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'patient_email'=>$patient_email,
                                  'booking_date'=>date('d-m-Y'),
                                  'charges'=>$charges,  
                                  'total_amount'=>"0.00",
                                  'net_amount'=>"0.00",
                                  'paid_amount'=>"0.00",
                                  'discount'=>"0.00",
                                  'particulars'=>'',
                                  'quantity'=>$quantity,
                                  'amount'=>$amount,
                                  'balance'=>$balance_amount,
                                  'branch_name'=>'',
                                  'transaction_no'=>'',
                                  'cheque_no'=>'',
                                  'cheque_date'=>'',
                                  'type'=>$type,
                                  'diseases'=>$diseases,
                                  "ref_by_other"=>$ref_by_other,
                                  'referral_doctor'=>$referral_doctor,
                                     "country_code"=>"+91"
                                  );    

        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validatebilling();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->opd->save_booking();
                //$this->session->set_flashdata('success','OPD particular successfully saved.');
                //redirect(base_url('opd'));
                $this->session->set_userdata('opd_booking_id',$booking_id);
                $this->session->set_flashdata('success','OPD Particular successfully saved.');
                redirect(base_url('opd/?status=print&type=1'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('opd/billing',$data); 
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


    public function print_booking_report($id="",$branch_id='')
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
      $get_by_id_data = $this->opd->get_all_detail_print($booking_id,$branch_id);
      //print_r($get_by_id_data);die;
      $template_format = $this->opd->template_format(array('section_id'=>1,'types'=>1),$branch_id);
      //print_r($get_by_id_data); exit;
      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
     // print_r($data);die;
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
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_by_id_data['payment_mode']);
      $data['all_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;
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
        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['source_list'] = $this->opd->source_list();  

        $data['form_data'] = array(
                                    "start_date"=>'',//date('d-m-Y')
                                    "end_date"=>'',//date('d-m-Y')
                                    "booking_from_date"=>'',
                                    "booking_to_date"=>'',
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
                                    "country_id"=>"99",
                                    "pincode"=>"",
                                    "amount_from"=>"",
                                    "amount_to"=>"",
                                    "paid_amount_from"=>"",
                                    "paid_amount_to"=>"",
                                    "booking_code"=>"",
                                    "referral_doctor"=>"",
                                    "specialization_id"=>"",
                                    "attended_doctor"=>"",
                                    "patient_email"=>"",
                                    "start_time"=>"",
                                    "end_time"=>"",
                                    "booking_status"=>"",
                                    "status"=>"", 
                                    "remark"=>"",
                                    "branch_id"=>'',
                                    'source_from'=>'',
                                    "disease"=>"",
                                    "disease_code"=>"",
                                  );
        if(isset($post) && !empty($post))
        {
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('opd_search', $marge_post);

        }
        $opd_search = $this->session->userdata('opd_search');
        if(isset($opd_search) && !empty($opd_search))
        {
            $data['form_data'] = $opd_search;
        }
        $this->load->view('opd/advance_search',$data);
    }

    public function get_branch_data($branch_id)
    {
        if($branch_id>0)
        {
          $branchdata = $this->opd->branch_data($branch_id);
          
          echo $branchdata;
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('opd_search');
    }

    function get_simulation_data($branch_id="")
    {
      if($branch_id>0)
      {
        $simulationdata = $this->opd->get_simulation_data($branch_id);
        echo $simulationdata;
      }
    }

    function get_referral_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $doctor_data = $this->opd->get_referral_doctor_data($branch_id);
        echo $doctor_data;
      }
    }

    function get_specialization_data($branch_id="")
    {
      if($branch_id>0)
      {
        $specialization_data = $this->opd->get_specialization_data($branch_id);
        echo $specialization_data;
      }
    }

    function get_attended_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $attended_data = $this->opd->get_attended_doctor_data($branch_id);
        echo $attended_data;
      }
    }


    public function opd_excel_old()
    {
        $list = $this->opd->search_opd_data();
       // echo "<pre>";print_r($list); exit;
        $columnHeader = '';  
        $columnHeader = " OPD No.". "\t"  . "Patient Name" . "\t" . "Patient Reg. No.". "\t" . "Doctor Name". "\t" . "Specialization" . "\t". "Appointment Date" ."\t". "Age" . "\t" . "Gender" . "\t". "Mobile" ;
        $setData = '';
        if(!empty($list))
        {
            $rowData = "";
            foreach($list as $opds)
            {
                
            if($opds->booking_status==0)
            {
                $booking_status = '<font color="red">Pending</font>';
            }   
            elseif($opds->booking_status==1){
                $booking_status = '<font color="green">Confirm</font>';
            }                 
            elseif($opds->booking_status==2){
                $booking_status = '<font color="blue">Attended</font>';
            } 
            

            $attended_doctor_name ="";
            $attended_doctor_name = get_doctor_name($opds->attended_doctor);
            $specialization_id = get_specilization_name($opds->specialization_id);
            $booking_code = $opds->booking_code;
            
            $patient_name = $opds->patient_name;
            if($opds->appointment_date!='0000-00-00')
            {
              $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
            }
            else
            {
                $appointment_date = ""; 
            }
            
            $booking_date = date('d M Y',strtotime($opds->booking_date));

                $genders = array('0'=>'Female', '1'=>'Male', '2'=>'Other');
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
                $age .= "/ ".$age_m." ".$month;
                }
                if($age_d>0)
                {
                $day = 'Days';
                if($age_d==1)
                {
                  $day = 'Day';
                }
                $age .= "/ ".$age_d." ".$day;
                }
                $patient_age =  $age;
                $bookingcode = " ";
                if(!empty($opds->booking_code))
                {
                  $bookingcode = $opds->booking_code;
                }
                $rowData = $bookingcode ."\t". $opds->patient_name . "\t" . $opds->patient_code. "\t" . $attended_doctor_name. "\t". $specialization_id. "\t" .  $appointment_date . "\t" . $patient_age . "\t" . $gender . "\t" . $opds->mobile_no; 
                $setData .= trim($rowData) . "\n";    
           
            }
        }

      

        echo '<pre>'.$setData;die;
        header("Content-type: application/octet-stream");  
        header("Content-Disposition: attachment; filename=opd_list_".time().".xls");  
        header("Pragma: no-cache");  
        header("Expires: 0");  

        echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
    }


    public function opd_excel()
    {
       // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $data_patient_reg = get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array('OPD NO.','Patient Name',$data_patient_reg,'Appointment Date','Booking Date','Age','Gender','Mobile','Doctor Name','Specialization');
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
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          $list = $this->opd->search_opd_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $opds)
               {
                    if($opds->booking_status==0)
                    {
                         $booking_status = '<font color="red">Pending</font>';
                    }   
                    elseif($opds->booking_status==1){
                         $booking_status = '<font color="green">Confirm</font>';
                    }                 
                    elseif($opds->booking_status==2){
                         $booking_status = '<font color="blue">Attended</font>';
                    } 
                    $attended_doctor_name ="";
                    $attended_doctor_name = get_doctor_name($opds->attended_doctor);
                    $specialization_id = get_specilization_name($opds->specialization_id);
                    $booking_code = $opds->booking_code;
                    $patient_name = $opds->patient_name;
                    $booking_date = date('d M Y',strtotime($opds->booking_date));
                    if($opds->appointment_date!='0000-00-00')
                    {
                         $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
                    }
                    else
                    {
                         $appointment_date = ""; 
                    }
                    $genders = array('0'=>'Female','1'=>'Male','2'=>'Other');
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
                    array_push($rowData,$opds->booking_code,$opds->patient_name,$opds->patient_code,$appointment_date,$booking_date,$patient_age,$gender,$opds->mobile_no,$attended_doctor_name,$specialization_id);
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
          header("Content-Disposition: attachment; filename=opd_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function opd_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array('OPD NO.','Patient Name',$data_patient_reg,'Appointment Date','Booking Date','Age','Gender','Mobile','Doctor Name','Specialization');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->opd->search_opd_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $opds)
               {
                    if($opds->booking_status==0)
                    {
                         $booking_status = '<font color="red">Pending</font>';
                    }   
                    elseif($opds->booking_status==1){
                         $booking_status = '<font color="green">Confirm</font>';
                    }                 
                    elseif($opds->booking_status==2){
                         $booking_status = '<font color="blue">Attended</font>';
                    } 
                    $attended_doctor_name ="";
                    $attended_doctor_name = get_doctor_name($opds->attended_doctor);
                    $specialization_id = get_specilization_name($opds->specialization_id);
                    $booking_code = $opds->booking_code;
                    $patient_name = $opds->patient_name;
                    $booking_date = date('d M Y',strtotime($opds->booking_date));
                    if($opds->appointment_date!='0000-00-00')
                    {
                         $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
                    }
                    else
                    {
                         $appointment_date = ""; 
                    }
                    $genders = array('0'=>'Female','1'=>'Male','2'=>'Other');
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
                    array_push($rowData,$opds->booking_code,$opds->patient_name,$opds->patient_code,$appointment_date,$booking_date,$patient_age,$gender,$opds->mobile_no,$attended_doctor_name,$specialization_id);
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

    public function opd_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->opd->search_opd_data();
        $this->load->view('opd/opd_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("opd_list_".time().".pdf");
    }
    public function opd_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->opd->search_opd_data();
      $this->load->view('opd/opd_html',$data); 
    }
    /*public function saved_reffered_doctor_id(){
          $post = $this->input->post();
          if(isset($post) && !empty($post)){
               $this->session->set_userdata('referral_doctor_id',$post['referal_doctor_id']);
          }
          $referral_doctor_id = $this->session->userdata('referral_doctor_id');
          echo $referral_doctor_id;
     }*/


    function get_test_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->prescription->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_medicine_auto_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->prescription->get_medicine_auto_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_dosage_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->prescription->get_dosage_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_type_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->prescription->get_type_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    

    function get_duration_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->prescription->get_duration_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_frequency_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->prescription->get_frequency_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }  


    function get_advice_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->prescription->get_advice_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_diseases_data($branch_id="")
    {
      if($branch_id>0)
      {
        $diseases_data = $this->opd->get_diseases_data($branch_id);
        echo $diseases_data;
      }
    }

    function get_package_data($branch_id="")
    {
      if($branch_id>0)
      {
        $this->load->model('general/general_model'); 
        $package_data = $this->general_model->get_package_data($branch_id);
        echo $package_data;
      }
    }

    

    function get_source_from_data($branch_id="")
    {
      if($branch_id>0)
      {
        $source_from_data = $this->opd->get_source_from_data($branch_id);
        echo $source_from_data;
      }
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

        $html.='<div class="row m-b-5"><div class="col-md-12"><div class="row"><div class="col-md-5"><b>'.$payment_detail->field_name.'<span class="star">*</span></b></div> <div class="col-md-7"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div></div></div>';
          }
        echo $html;exit;

    }  
    
  public function checkbox_list_save()
  {
   // print_r($this->input->post());
    $users_data = $this->session->userdata('auth_users');
    $col_ids_array=$this->input->post('rec_id');
    $module_id=$this->input->post('module_id');
    $branch_id=$users_data['parent_id'];
    $this->opd->delete_existing_branch_list_cols($module_id,$branch_id);
    $this->opd->insert_new_cols_branch_list_cols($branch_id, $module_id, $col_ids_array);
    echo "Record Inserted Successfully";
  }

  public function reset_coloumn_record()
  {
    $module_id=$this->input->post('module_id');
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    $this->opd->delete_existing_branch_list_cols($module_id,$branch_id);
    echo json_encode(array("status"=>200));
  }

  public function update_doctor_status($opd_id="",$status_type="")
  {
    $update_status= $this->opd->update_doctor_status($opd_id,$status_type);
    echo $update_status;exit;
  }

  public function get_validity_date_in_between()
  {
      $doctor_id= $this->input->post('doctor_id');
      $booking_date= $this->input->post('booking_date');
      $patient_id= $this->input->post('patient_id');
      $result= $this->opd->get_validity_date_in_between($doctor_id,$booking_date,$patient_id);
      echo $result;die;
  }
  public function get_validate_date()
  {
      $doctor_id= $this->input->post('doctor_id');
      $booking_date= $this->input->post('booking_date');
      $patient_id= $this->input->post('patient_id');
      $result= $this->opd->get_validate_date($doctor_id,$booking_date,$patient_id);
      echo json_encode(array('date'=>$result));exit;
  }

// please write code above    


/*
    public function print_blank_prescriptions($booking_id="",$branch_id='')
    {
        $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->prescription->get_detail_by_booking_id($booking_id,$branch_id);
        $template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>1),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>1),$branch_id);
        $template_format_right = $this->prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>1),$branch_id);
        $template_format_top = $this->prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>1));
        $template_format_bottom = $this->prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>1),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_prescription_print_tab_setting();
        //print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $data['prescription_id'] = $booking_id;
        $data['type'] = 1;

        $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();
        $this->load->view('prescription/print_prescription_template',$data);
    }
*/



    public function print_prescription_pdf($id)
    {
            $opd_prescription_info = $this->prescription->get_by_ids($id);
            $prescription_opd_prescription_info = $this->prescription->get_opd_prescription($id); 
            $prescription_opd_test_info = $this->prescription->get_opd_test($id);
            
            $booking_id = $opd_prescription_info['booking_id'];

            $this->load->model('opd/opd_model','opd');
            $opd_details = $this->opd->get_by_id($booking_id);
            $referral_doctor = $opd_details['referral_doctor'];
            
            $patient_code = $opd_prescription_info['patient_code'];
            $attended_doctor = $opd_prescription_info['attended_doctor'];
            $specialization_id = $opd_details['specialization_id'];
            $booking_code = $opd_prescription_info['booking_code'];
            $patient_name = $opd_prescription_info['patient_name'];
            $mobile_no = $opd_prescription_info['mobile_no'];
            $gender = $opd_prescription_info['gender'];
            $age_y = $opd_prescription_info['age_y'];
            $age_m = $opd_prescription_info['age_m'];
            $age_d = $opd_prescription_info['age_d'];
            $patient_bp = $opd_prescription_info['patient_bp'];
            $patient_temp = $opd_prescription_info['patient_temp'];
            $patient_weight = $opd_prescription_info['patient_weight'];
            $patient_height = $opd_prescription_info['patient_height'];
            $patient_rbs = $opd_prescription_info['patient_rbs'];
            $patient_spo2 = $opd_prescription_info['patient_spo2'];
            $next_appointment_date = $opd_prescription_info['next_appointment_date'];
            $prv_history = $opd_prescription_info['prv_history'];
            $personal_history = $opd_prescription_info['personal_history'];
            $chief_complaints = $opd_prescription_info['chief_complaints'];
            $examination = $opd_prescription_info['examination'];
            $diagnosis = $opd_prescription_info['diagnosis'];
            $suggestion = $opd_prescription_info['suggestion'];
            $remark = $opd_prescription_info['remark'];
            if($opd_prescription_info['appointment_date']!='0000-00-00')
            {
                $appointment_date =  date('d-M-Y H:i A',strtotime($opd_prescription_info['appointment_date']));
            }
            else
            {
                $appointment_date ="";
            }
            

            $genders = array('0'=>'Female','1'=>'Male');
            $gender = $genders[$gender];

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
            $doctor_name = get_doctor_name($attended_doctor);
            $referral_doctor = get_doctor_name($referral_doctor);
            
          $prescription_tab_setting = get_prescription_tab_setting(); 
            $presc_prescriptions = '';
          foreach ($prescription_tab_setting as $value) 
          {
            $test_content = '';
            if(!empty($prescription_opd_test_info)) 
            {
               if(strcmp(strtolower($value->setting_name),'test_result')=='0'){
               $test_content .= '<div style="width:100%;font-weight:bold;text-align: center;margin-top:5%;text-decoration:underline;">'.$value->setting_value.'</div>';
             
                
             $test_content .= '<table border="1" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
                <tr><td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;font-weight:bold;">Test Name</div></td></tr>';
            
                      
                    foreach ($prescription_opd_test_info as $testdata) {  
                       $test_content .= '<tr><td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;">'.$testdata->test_name.'</div></td></tr>';
                         
                      }  
                    
                   
               $test_content .='</table>';

            }

            }
        if(!empty($prescription_opd_prescription_info)) {   
           if(strcmp(strtolower($value->setting_name),'prescription')=='0'){
           
           $presc_prescriptions .= '<table border="1" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
            <tr>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Name</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Type</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Dose</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Frequency</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Duration</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Advice</u></th>
          </tr>';
        
          
                foreach ($prescription_opd_prescription_info as $prescriptiondata) { 
            $presc_prescriptions .='<tr>
                          <td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;">'.$prescriptiondata->medicine_name.'</div></td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_type.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_dose.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_frequency.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_duration.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_advice.'</td>
                      </tr>';
                }  
                   
                

        $presc_prescriptions .='</table>';  
        }


        } 


        if(!empty($suggestion))
        {
        if(strcmp(strtolower($value->setting_name),'suggestions')=='0'){
        $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
          </tr>

          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$suggestion.'</div></td>
          </tr>
        </table>';
        }
        }
        if(!empty($prv_history))
        {
            if(strcmp(strtolower($value->setting_name),'prv_history')=='0'){
                $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
                  <tr>
                    <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
                  </tr>

                  <tr>
                    <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$prv_history.'</div></td>
                  </tr>
                </table>';
            }
        }
        if(!empty($personal_history))
        {
        if(strcmp(strtolower($value->setting_name),'personal_history')=='0'){

        $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
          </tr>

          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$personal_history.'</div></td>
          </tr>
        </table>';
        }
        }
        if(!empty($chief_complaints))
        {
          if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0')
          {
            $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
              <tr>
                <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
              </tr>

              <tr>
                <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$chief_complaints.'</div></td>
              </tr>
            </table>';
        }
        }
        if(!empty($examination))
        {
        if(strcmp(strtolower($value->setting_name),'examination')=='0')
        {
        $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
          </tr>

          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$examination.'</div></td>
          </tr>
        </table>';
        }
        }
        if(!empty($diagnosis))
        {
        if(strcmp(strtolower($value->setting_name),'diagnosis')=='0'){ 
        $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
          </tr>

          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$diagnosis.'</div></td>
          </tr>
        </table>';
        }
        }
        if(!empty($appointment_date) && $appointment_date!=='0000-00-00')
        {
         if(strcmp(strtolower($value->setting_name),'appointment')=='0')
         { 
            $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
              <tr>
                <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
              </tr>

              <tr>
                <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$appointment_date.'</div></td>
              </tr>
            </table>';

        }
    }

    }


         
         $pdf_data['patient_code'] = $patient_code;
         $pdf_data['patient_name'] = $patient_name;
         $pdf_data['presc_prescriptions'] = $presc_prescriptions;
         $pdf_data['test_content'] = $test_content;
         $pdf_data['gender'] = $gender;
         $pdf_data['patient_age'] = $patient_age;
         $pdf_data['referral_doctor'] = $referral_doctor;
         $pdf_data['doctor_name'] = $doctor_name;
         $pdf_data['booking_code'] = $booking_code;
         $pdf_data['mobile_no'] = $mobile_no;
         $pdf_data['specialization_id'] = get_specilization_name($specialization_id);
         //$pdf_data['suggestions'] = $suggestion;
         $pdf_data['prv_history'] = $prv_history;
         $pdf_data['chief_complaints'] = $chief_complaints;
         $pdf_data['examination'] = $examination;
         //$pdf_data['personal_history'] = $personal_history;
         $pdf_data['patient_bp']=$patient_bp;
         $pdf_data['patient_temp']=$patient_temp;
         $pdf_data['patient_weight']=$patient_weight;
         $pdf_data['patient_height']=$patient_height;
         $pdf_data['patient_spo2']=$patient_spo2;
         $pdf_data['patient_rbs']=$patient_rbs;
         $pdf_data['appointment_date'] = $appointment_date;
         $pdf_data['diagnosis'] = $diagnosis;
         $pdf_template = $this->load->view('prescription/pdf_template',$pdf_data,true);
         $result['success'] = true;
         $result['msg'] = 'Print prescription success';
         $result['pdf_template'] = $pdf_template;
         echo @json_encode($result);
    }

    


public function print_blank_prescription_pdf($id)
{
        $opd_prescription_info = $this->prescription->get_by_ids($id);
        //echo "<pre>";print_r($opd_prescription_info); exit;
        $prescription_opd_prescription_info = $this->prescription->get_opd_prescription($id); 
        $prescription_opd_test_info = $this->prescription->get_opd_test($id);
        
        $booking_id = $opd_prescription_info['booking_id'];

        $this->load->model('opd/opd_model','opd');
        
        $opd_details = $this->opd->get_by_id($id);

        $this->load->model('patient/patient_model','patient');
        $patient_id = $opd_details['patient_id'];
        $patient_details = $this->patient->get_by_id($patient_id);
        //print_r($patient_details);
        $referral_doctor = $opd_details['referral_doctor'];
        $attended_doctor = $opd_details['attended_doctor'];
        
        $specialization_id = $opd_details['specialization_id'];
        $patient_code = $patient_details['patient_code'];
        //$attended_doctor = $opd_prescription_info['attended_doctor'];
        
        $booking_code = $opd_prescription_info['booking_code'];
        $simulation_id = get_simulation_name($patient_details['simulation_id']);
        $patient_name = $simulation_id .' '.$patient_details['patient_name'];
        $mobile_no = $patient_details['mobile_no'];
        $gender = $patient_details['gender'];
        $age_y = $patient_details['age_y'];
        $age_m = $patient_details['age_m'];
        $age_d = $patient_details['age_d'];
        
        $patient_bp = $opd_prescription_info['patient_bp'];
        $patient_temp = $opd_prescription_info['patient_temp'];
        $patient_weight = $opd_prescription_info['patient_weight'];
        $patient_height = $opd_prescription_info['patient_height'];
        $patient_rbs = $opd_prescription_info['patient_rbs'];
        $patient_spo2 = $opd_prescription_info['patient_spo2'];
        $next_appointment_date = $opd_prescription_info['next_appointment_date'];
        $prv_history = $opd_prescription_info['prv_history'];
        $personal_history = $opd_prescription_info['personal_history'];
        $chief_complaints = $opd_prescription_info['chief_complaints'];
        $examination = $opd_prescription_info['examination'];
        $diagnosis = $opd_prescription_info['diagnosis'];
        $suggestion = $opd_prescription_info['suggestion'];
        $remark = $opd_prescription_info['remark'];
        if($opd_prescription_info['appointment_date']!='0000-00-00')
        {
            $appointment_date =  $opd_prescription_info['appointment_date'];
        }
        else
        {
            $appointment_date ="";
        }
        

        $genders = array('0'=>'Female','1'=>'Male');
        $gender = $genders[$gender];

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
        $doctor_name = get_doctor_name($attended_doctor);
        $referral_doctor = get_doctor_name($referral_doctor);
        $specialization_id = get_specilization_name($specialization_id);
        $prescription_tab_setting = get_prescription_tab_setting(); 
        $presc_prescriptions = '';
        $test_content ="";
      


     $pdf_data['doctor_name'] = $doctor_name;
     $pdf_data['specialization_id'] = $specialization_id;    
     $pdf_data['patient_code'] = $patient_code;
     $pdf_data['patient_name'] = $patient_name;
     $pdf_data['presc_prescriptions'] = $presc_prescriptions;
     $pdf_data['test_content'] = $test_content;
     $pdf_data['gender'] = $gender;
     $pdf_data['patient_age'] = $patient_age;
     $pdf_data['referral_doctor'] = $referral_doctor;
     $pdf_data['doctor_name'] = $doctor_name;
     $pdf_data['booking_code'] = $booking_code;
     $pdf_data['mobile_no'] = $mobile_no;
     //$pdf_data['suggestions'] = $suggestion;
     $pdf_data['prv_history'] = $prv_history;
     $pdf_data['chief_complaints'] = $chief_complaints;
     $pdf_data['examination'] = $examination;
     //$pdf_data['personal_history'] = $personal_history;
     $pdf_data['patient_bp']='';
     $pdf_data['patient_temp']='';
     $pdf_data['patient_weight']='';
     $pdf_data['patient_height']='';
     $pdf_data['patient_spo2']='';
     $pdf_data['patient_rbs']='';
     $pdf_data['appointment_date'] = '';
     $pdf_data['diagnosis'] = '';
     
     $pdf_template = $this->load->view('prescription/pdf_template',$pdf_data,true);
     $result['success'] = true;
     $result['msg'] = 'Print prescription success';
     $result['pdf_template'] = $pdf_template;
     
    
    
    echo @json_encode($result);
}






  public function upload_pediatrician_prescription($prescription_id='',$booking_id='')
{ 
    unauthorise_permission(275,2057);
    if($prescription_id=='0' && !empty($booking_id))
    {
      $this->load->model('opd/opd_model','opd');
      $prescription_id=$this->opd->save_prescription_before_file_upload($booking_id);
    }
    $data['page_title'] = 'Upload Pediatrician Prescription';   
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
             $config['upload_path']   = DIR_UPLOAD_PATH.'pediatrician/prescription/';  
             $config['allowed_types'] = 'pdf|jpeg|jpg|png|xls|csv'; 
             $config['max_size']      = 1000; 
             $config['encrypt_name'] = TRUE; 
             $this->load->library('upload', $config);
             
             if ($this->upload->do_upload('prescription_files')) 
             {
                $file_data = $this->upload->data(); 
                $this->prescription->save_file($file_data['file_name']);
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

    $this->load->view('pediatrician/add_prescription/add_file',$data);
}


public function view_files($prescription_id)
{ 
  unauthorise_permission(275,2054);
  $data['page_title'] = "Pediatrician Prescription Files";
  $data['prescription_id'] = $prescription_id;
  $this->load->view('pediatrician/add_prescription/view_prescription_files',$data);
}

  public function ajax_file_list($prescription_id='')
  { 
        
        unauthorise_permission(275,2054);

        $users_data = $this->session->userdata('auth_users');
        $list = $this->pediatrician_files_model->get_datatables($prescription_id);
       // print '<pre>'; print_r($list);die;  
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
            if(!empty($prescription->prescription_files) && file_exists(DIR_UPLOAD_PATH.'pediatrician/prescription/'.$prescription->prescription_files))
            {
                $sign_img = ROOT_UPLOADS_PATH.'pediatrician/prescription/'.$prescription->prescription_files;
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
                        "recordsTotal" => $this->pediatrician_files_model->count_all($prescription_id),
                        "recordsFiltered" => $this->pediatrician_files_model->count_filtered($prescription_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    

    public function delete_prescription_file($id="")
    {
        unauthorise_permission(275,2057);
       if(!empty($id) && $id>0)
       {
           $result = $this->pediatrician_files_model->delete($id);
           $response = "Pediatrician Prescription file successfully deleted.";
           echo $response;
       }
    }

    function deleteall_prescription_file()
    {
        unauthorise_permission(248,1411);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->pediatrician_files_model->deleteall($post['row_id']);
            $response = "Prescription file successfully deleted.";
            echo $response;
        }
    }


    public function delete_pedic_pres($id="")
    {
       //unauthorise_permission(86,535);
       if(!empty($id) && $id>0)
       {
           $result = $this->pediatrician_files_model->delete_prescription($id);
           $response = "Prescription successfully deleted.";
           echo $response;
       }
    }

   

public function view_prescription($id,$branch_id='')
{ 
    $data['form_data'] = $this->pediatrician_files_model->get_by_ids($id);
    $data['prescription_data'] = $this->pediatrician_files_model->get_pediatrician_prescription($id); 
    $data['test_data'] = $this->pediatrician_files_model->get_pediatrician_test($id); 
    $data['page_title'] = $data['form_data']['patient_name']." Prescription";
    $data['prescription_tab_setting'] = get_pediatrician_prescription_tab_setting('',$branch_id);
    $data['prescription_medicine_tab_setting'] = get_pediatrician_prescription_medicine_tab_setting('',$branch_id);
    $data['id'] = $id;
    $this->load->model('general/general_model');
    $data['vitals_list']=$this->general_model->vitals_list();
    $this->load->view('pediatrician/add_prescription/print_template_pedic',$data);
    $html = $this->output->get_output();
}

  public function print_prescriptions($prescription_id="",$branch_id='')
    {
         
        $prescriptions_id= $this->session->userdata('pediatrician_prescription_id');

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
        $opd_prescription_info = $this->pediatrician_files_model->get_detail_by_prescription_id($prescription_id);
 //echo '<pre>'; print_r($opd_prescription_info);die;
        $template_format = $this->pediatrician_files_model->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->pediatrician_files_model->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->pediatrician_files_model->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->pediatrician_files_model->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->pediatrician_files_model->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
         $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['template_data']=$template_format->setting_value;
// echo "<pre>"; print_r($opd_prescription_info); exit;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_pediatrician_prescription_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_pediatrician_prescription_medicine_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['all_detail']['prescription_list'][0]); exit;
        $signature_image = get_doctor_signature($data['all_detail']['prescription_list'][0]->attended_doctor);
        
   $signature_reprt_data ='';
   if(!empty($signature_image))
   {
   
    $signature_reprt_data .= '<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse; font-size:13px; margin-top: 5%;"> 
    <tr>
        <td width="70%"></td>
        <td valign="top" align="center"><b>Consultant Doctor</b></td>
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
   


        $data['signature'] = $signature_reprt_data;
        
        $this->load->view('pediatrician/add_prescription/print_prescription_template',$data);
    }


    public function print_blank_prescriptions($booking_id="",$branch_id='')
    {
       
       
        
        $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->pediatrician_files_model->get_detail_by_booking_id($booking_id,$branch_id);


        //$template_format = $this->pediatrician_files_model->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>1),$branch_id);
        $template_format = $this->pediatrician_files_model->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5),$branch_id);

        //print_r($template_format);die();

        $template_format_left = $this->pediatrician_files_model->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>1),$branch_id);

        $template_format_right = $this->pediatrician_files_model->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>1),$branch_id);
  
        $template_format_top = $this->pediatrician_files_model->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>1));
        $template_format_bottom = $this->pediatrician_files_model->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>1),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_pediatrician_prescription_tab_setting();
        //print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_pediatrician_prescription_medicine_tab_setting();
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $data['prescription_id'] = $booking_id;
        $data['type'] = 1;
         $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();  
        $this->load->view('pediatrician/add_prescription/print_prescription_template',$data);
    
    
       /* $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->pediatrician_files_model->get_detail_by_booking_id($booking_id,$branch_id);


        $template_format = $this->pediatrician_files_model->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>1),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->pediatrician_files_model->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>1),$branch_id);
        $template_format_right = $this->pediatrician_files_model->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>1),$branch_id);
        $template_format_top = $this->pediatrician_files_model->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>1));
        $template_format_bottom = $this->pediatrician_files_model->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>1),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_pediatrician_prescription_tab_setting();
        //print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_pediatrician_prescription_medicine_tab_setting();
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $data['prescription_id'] = $booking_id;
        $data['type'] = 1;

        $this->load->view('pediatrician/add_prescription/print_prescription_template',$data);*/
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
          $getData = $this->pediatrician_general->chief_complaint_list("", $type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->chief_complaints
            );
          }
        } else if ($post['class'] == 'prv_history_data') {
          $getData = $this->prescription->prv_history_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->prv_history
            );
          }
        } else if($post['class'] == 'personal_history_data') {
          $getData = $this->prescription->personal_history_list($type); 
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->personal_history
            );
          }
        } else if($post['class'] == 'examination_data') {
          $getData = $this->prescription->examinations_list($type);
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->examination
            );
          }
        } else if($post['class'] == 'diagnosis_data') {
          $getData = $this->opd->diagnosis_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->diagnosis
            );
          }
        } else if($post['class'] == 'suggestion_data') {
          $getData = $this->prescription->suggetion_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->medicine_suggetion
            );
          }
        }
        echo json_encode($output);
    }
}
?>