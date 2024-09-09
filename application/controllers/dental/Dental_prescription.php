<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dental_prescription extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
       // unauthorise_permission('284','1399');
        $this->load->model('dental/dental_prescription/Dental_prescription_model','dental_prescription');
        $this->load->model('dental/dental_prescription/prescription_file_model','prescription_file');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->session->unset_userdata('chief_complain_data_list');
        $this->session->unset_userdata('previous_history_disease_data');
        $this->session->unset_userdata('previous_allergy_data');
        $this->session->unset_userdata('previous_oral_habit_data');
        $this->session->unset_userdata('diagnosis_list_data');
        $this->session->unset_userdata('treatment_list_data_values');
        $this->session->unset_userdata('previous_advice_data'); 
        
    } 


    public function add_dental_prescription($booking_id='')
    {
       $post = $this->input->post();
       //echo"<pre>"; print_r($post);
       if(empty($post))
       {
        $this->session->unset_userdata('chief_complain_data_list');
        $this->session->unset_userdata('previous_history_disease_data');
        $this->session->unset_userdata('previous_allergy_data');
        $this->session->unset_userdata('previous_oral_habit_data');
        $this->session->unset_userdata('diagnosis_list_data');
        $this->session->unset_userdata('treatment_list_data_values');
        $this->session->unset_userdata('previous_advice_data');

       }
       
       //print'<pre>';print_r($post);die;
       $this->load->model('opd/opd_model','opd');
       $this->load->model('dental/prescription_template/prescription_template_model','prescription_template');
       $this->load->helper('dental'); 
       $this->load->model('dental/general/dental_general_model','dental_general');
       $opd_booking_id=$this->uri->segment(4); 
       $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
       $opd_booking_data = $this->dental_prescription->get_by_id($opd_booking_id); 
       $next_app_date=$data['booking_data']['next_app_date'];
       $patient_id=$data['booking_data']['patient_id'];
       $data['page_title'] = 'Add Dental Prescription';
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
        $data['template_list'] = $this->dental_prescription->dental_template_list();
        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list(); 
        $data['chief_complaint_list'] =$this->prescription_template->dental_chief_complaint_list();
         $data['disease_list'] = $this->prescription_template->dental_disease_list(); 
         $data['allergy_list'] = $this->prescription_template->dental_allergy_list();
         $data['habit_list'] = $this->prescription_template->dental_habit_list();
         $data['diagnosis_list'] = $this->dental_prescription->dental_diagnosis_list(); 
         $data['treatment_list'] = $this->dental_prescription->dental_treatment_list();
         
         $data['treatment_type_list'] = $this->dental_prescription->dental_treatment_type_list();
         
         $data['investigation_category_list'] = $this->dental_prescription->dental_investigation_list(); 
         //print_r($data['investigation_category_list']);
        $data['prescription_tab_setting'] = get_dental_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_dental_prescription_medicine_tab_setting();
        $data['advice_list'] = $this->prescription_template->dental_advice_list();
        if(!empty($next_app_date) && $next_app_date!='1970-01-01')
        {
            $check_appointment=1;
        }
        else
        {
            $check_appointment=0;
        }
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
                                   'next_app_date'=>$next_app_date,
                                   'check_appointment'=>$check_appointment,
                                   'date_time_new' => '',
                                   'next_reason' => '',
                                  );
         if(isset($post) && !empty($post))
        {   
           //echo"<pre>";print_r($post);
           //die;
           // $data['form_data'] = $this->_validate();
           // if($this->form_validation->run() == TRUE)
            //{


              $prescription_id=$this->dental_prescription->save();
               $this->session->set_userdata('dental_prescription_id',$prescription_id);
              $this->session->set_flashdata('success','Prescription  successfully added.');

             redirect(base_url('prescription/?status=print_dental'));
            //}
            //else
            //{
               // $data['form_error'] = validation_errors();  
           // }    
        } 
        $this->load->model('next_appointment/Next_appointment_model', 'next_appointment_master');
      $data['next_appointment_list'] = $this->next_appointment_master->list();
        $this->load->view('dental/dental_prescription/add',$data);

    }

    public function add_chief_prepcription_list()
    {
           //echo "hi";die;
          $post = $this->input->post();
          //print_r($post);die;
          if(isset($post) && !empty($post))
          {   
            $chief_complain_data_list = $this->session->userdata('chief_complain_data_list'); 
            if(isset($chief_complain_data_list) && !empty($chief_complain_data_list))
            {
              $chief_complain_data_list = $chief_complain_data_list; 
              $tot = count($chief_complain_data_list);
            }
            else
            {
              $chief_complain_data_list = [];
              $tot=0;
            }

        
           
          $chief_complain_data_list[$tot+1] = array('chief_id'=>$tot+1,'chief_complaint_id'=>$post['chief_complaint_id'], 'teeth_name'=>$post['teeth_name'], 'reason'=>$post['reason'],'number'=>$post['number'],'time'=>$post['time'],'get_teeth_number_val'=>$post['get_teeth_number_val'],'chief_complaint_value'=>$post['chief_complaint_value']);
       // print_r($chief_complain_data_list);die;
         
          $this->session->set_userdata('chief_complain_data_list', $chief_complain_data_list);

          $html_data = $this->chief_prepcription_list_data();
          
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
            
       
    }


     private function chief_prepcription_list_data()
    {
       $chief_complain_data_list = $this->session->userdata('chief_complain_data_list');
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
                    <th>Teeth Name</th>
                    <th>Tooth No.</th>
                    <th>Reason</th>
                    <th>Duration</th>
                  
                  </tr></thead>';  
           if(isset($chief_complain_data_list) && !empty($chief_complain_data_list))
           {
              $i = 1;
              foreach($chief_complain_data_list as $chief_data)
              {
                //print_r($chief_data);
                //$chief_data['get_teeth_number_val']
                 $rows .= '<tr>
                             <td width="60" align="center"><input type="checkbox" name="chief_complaint[]" class="part_checkbox booked_checkbox" value="'.$chief_data['chief_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$chief_data['chief_complaint_value'].'</td>
                            <td>'.$chief_data['teeth_name'].'</td>
                                  <td>'.$chief_data['get_teeth_number_val'].'</td>
                            <td>'.$chief_data['reason'].'</td>
                             <td>'.$chief_data['number'].' '.$chief_data['time'].'</td>
                          
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="8" align="center" class=" text-danger "><div class="text-center">Chief complaint data not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    
    }



     public function remove_dental_chief()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         //echo "<pre>";print_r($post); exit;
         $data = array('chief_complaint'=>explode(',', $post['chief_complaint']));
         //print_r($data);
         //echo "<pre>";print_r($data); exit;
        if(isset($data['chief_complaint']) && !empty($data['chief_complaint']))
       {
          $chief_complain_data_list = $this->session->userdata('chief_complain_data_list');
          //echo "<pre>";print_r($chief_complain_data_list); exit;
          $chief_complain_id_list = array_column($chief_complain_data_list, 'get_teeth_number_val');
          foreach($chief_complain_data_list as $key=>$chief_complain_ids)
           {
               //echo $key; die;
              //echo "<pre>"; print_r($chief_complain_ids); exit;
               //chief_complaint_id
              //if(in_array($chief_complain_ids['get_teeth_number_val'],$data['chief_complaint']))
              if(in_array($chief_complain_ids['chief_id'],$data['chief_complaint']))
              {
                // unset($chief_complain_data_list[$key]);
                unset($chief_complain_data_list[$chief_complain_ids['chief_id']]);
              }
           }  

      $this->session->set_userdata('chief_complain_data_list',$chief_complain_data_list);
      
      
       //$chief_complain_data_list = $this->session->userdata('chief_complain_data_list');
      //echo "<pre>";print_r($chief_complain_data_list); exit;
      $html_data = $this->chief_prepcription_list_data();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}


     public function add_perma()
    {
         
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->dental_prescription->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('dental/dental_prescription/add_permanent_chart',$data);       
    }

     public function add_decidous()
    {
         
        $data['page_title'] = "Decidous Teeth";  
         $data['get_decidous']=$this->dental_prescription->get_decidous();
       $this->load->view('dental/dental_prescription/add_decious_chart',$data);       
    }

     public function add_perma_diagnosis()
    {
         
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->dental_prescription->get_permanent();
        //print_r($data['get_permanent']);
        $this->load->view('dental/dental_prescription/add_permanent_chart_diagnosis',$data);       
    }

     public function add_decidous_diagnosis()
    {
         
        $data['page_title'] = "Decidous Teeth";  
        $data['get_decidous']=$this->dental_prescription->get_decidous();
       $this->load->view('dental/dental_prescription/add_decious_chart_diagnosis',$data);       
    }

    public function add_perma_treatment()
    {
         
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->dental_prescription->get_permanent();
        //print_r($data['get_permanent']);
        $this->load->view('dental/dental_prescription/add_permanent_chart_treatment',$data);       
    }

     public function add_decidous_treatment()
    {
         
        $data['page_title'] = "Decidous Teeth";  
        $data['get_decidous']=$this->dental_prescription->get_decidous();
       $this->load->view('dental/dental_prescription/add_decious_chart_treatment',$data);       
    }





      public function previous_history_disease_data_list()
    {
      
          $post = $this->input->post();
          //print_r($post);die;
          $previous_history_disease_data = $this->session->userdata('previous_history_disease_data'); 
          if(isset($post) && !empty($post))
          {   
            $previous_history_disease_data = $this->session->userdata('previous_history_disease_data'); 
            if(isset($previous_history_disease_data) && !empty($previous_history_disease_data))
            {
                

              $previous_history_disease = $previous_history_disease_data; 
              $tot = count($previous_history_disease);
            }
            else
            {
              $previous_history_disease = [];
              $tot=0;
            }
           //$chief_complain_data_list[$tot+1] //esp3431 Manohar lal arora
          //$previous_history_disease[$post['disease_id']] 
          
          $previous_history_disease[$tot+1]= array('prev_id'=>$tot+1,'disease_id'=>$post['disease_id'], 'disease_value'=>$post['disease_value'], 'disease_details'=>$post['disease_details'],'operation'=>$post['operation'],'operation_date'=>$post['operation_date']);
        //print_r($previous_history_disease);die;
        
          $this->session->set_userdata('previous_history_disease_data', $previous_history_disease);
         //echo "<pre>"; print_r($previous_history_disease_data);die;
          $html_data = $this->previous_history_disease_list();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
    }
  }

   private function previous_history_disease_list()
    {
      $previous_history_disease_data = $this->session->userdata('previous_history_disease_data');
      //print_r($chief_complain_data);die;
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
                     <th>Details</th>
                      <th>Any Operation</th>
                       <th>Date</th>
                  </tr></thead>';  
           if(isset($previous_history_disease_data) && !empty($previous_history_disease_data))
           {
              $i = 1;
              foreach($previous_history_disease_data as $previous_history_disease)
              {

                                $date_dis='';
                              if((!empty($previous_history_disease['operation_date'])) && ($previous_history_disease['operation_date']!='0000-00-00') &&($previous_history_disease['operation_date']!='1970-01-01'))
                            
                              {
                                $date_dis=date('d-m-Y', strtotime($previous_history_disease['operation_date']));
                            
                              }
                              else
                              {
                                $date_dis='';
                              }
                          $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="disease_name[]" class="part_checkbox booked_checkbox" value="'.$previous_history_disease['prev_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$previous_history_disease['disease_value'].'</td>
                            <td>'.$previous_history_disease['disease_details'].'</td>
                            <td>'.$previous_history_disease['operation'].'</td>
                              <td>'.$date_dis.'</td>
                            
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="6" align="center" class=" text-danger "><div class="text-center">Previous history data are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function remove_previous_history_disease()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         //print_r($post);die;
         $data = array('disease_name'=>explode(',', $post['disease_name']));
         //print_r($data);die;
        if(isset($data['disease_name']) && !empty($data['disease_name']))
       {
         $previous_history_disease_data = $this->session->userdata('previous_history_disease_data');
         //echo "<pre>"; print_r($previous_history_disease_data);die;
          $chief_complain_id_list = array_column($previous_history_disease_data, 'disease_id');
          foreach($previous_history_disease_data as $key=>$previous_history_disease_ids)
           {
             /* if(in_array($previous_history_disease_ids['disease_id'],$data['disease_name']))
              {
                 unset($previous_history_disease_data[$key]);
              }*/
              
              if(in_array($previous_history_disease_ids['prev_id'],$data['disease_name']))
              {
                // unset($chief_complain_data_list[$key]);
                unset($previous_history_disease_data[$previous_history_disease_ids['prev_id']]);
              }
           }  

      $this->session->set_userdata('previous_history_disease_data',$previous_history_disease_data);
      $html_data = $this->previous_history_disease_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}

public  function allergy_list()
        { 
            $post = $this->input->post();
            $previous_allergy_data = $this->session->userdata('previous_allergy_data'); 
            if(isset($post) && !empty($post))
           {   
            $previous_allergy_data = $this->session->userdata('previous_allergy_data'); 
            if(isset($previous_allergy_data) && !empty($previous_allergy_data))
            {
              $previous_allergy = $previous_allergy_data; 
              $tot = count($previous_allergy);
            }
            else
            {
              $previous_allergy = [];
            }
           //
           
           
          $previous_allergy[$tot+1] = array('aller_id'=>$tot+1,'allergy_id'=>$post['allergy_id'], 'allergy_value'=>$post['allergy_value'], 'reason'=>$post['reason'],'number'=>$post['number'],'time'=>$post['time']);  
          $this->session->set_userdata('previous_allergy_data', $previous_allergy);
          $html_data = $this->allergy_data_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
   
     }

       private function allergy_data_list()
    {
      $previous_allergy_data = $this->session->userdata('previous_allergy_data');
      //print_r($chief_complain_data);die;
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
                    <th>Reason</th>
                   
                      <th>Duration</th>
                  </tr></thead>';  
           if(isset($previous_allergy_data) && !empty($previous_allergy_data))
           {
              $i = 1;
              foreach($previous_allergy_data as $previous_allergy_data_list)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="allergy[]" class="part_checkbox booked_checkbox" value="'.$previous_allergy_data_list['aller_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$previous_allergy_data_list['allergy_value'].'</td>
                            <td>'.$previous_allergy_data_list['reason'].'</td>
                          
                             <td>'.$previous_allergy_data_list['number'].'  '.$previous_allergy_data_list['time'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Allergy data are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

     public function remove_dental_allergy()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         $data = array('allergy'=>explode(',', $post['allergy']));
        // echo "<pre>"; print_r($data); exit;
        if(isset($data['allergy']) && !empty($data['allergy']))
       {
          $previous_allergy_data = $this->session->userdata('previous_allergy_data');
          //echo "<pre>"; print_r($data); exit;
          $allergy_id_list = array_column($previous_allergy_data, 'allergy_id');
          foreach($previous_allergy_data as $key=>$previous_allergy_ids)
           {
              // echo "<pre>"; print_r($previous_allergy_ids); exit;
              /*if(in_array($previous_allergy_ids['allergy_id'],$data['allergy']))
              {
                 unset($previous_allergy_data[$key]);
              }*/
              
              if(in_array($previous_allergy_ids['aller_id'],$data['allergy']))
              {  
                unset($previous_allergy_data[$previous_allergy_ids['aller_id']]);
              }
              
           }  

      $this->session->set_userdata('previous_allergy_data',$previous_allergy_data);
      $html_data = $this->allergy_data_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}

 public  function oral_habit_list()
        { 
            $post = $this->input->post();
            $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data'); 
            if(isset($post) && !empty($post))
           {   
            $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data'); 
            if(isset($previous_oral_habit_data) && !empty($previous_oral_habit_data))
            {
              $previous_oral_habit = $previous_oral_habit_data; 
              $tot = count($previous_oral_habit);
            }
            else
            {
              $previous_oral_habit_data = [];
            }
           
          $previous_oral_habit_data[$tot+1] = array('hab_id'=>$tot+1,'habit_id'=>$post['habit_id'], 'oral_habit_value'=>$post['oral_habit_value'],'number'=>$post['number'],'time'=>$post['time']);
           
         
          $this->session->set_userdata('previous_oral_habit_data', $previous_oral_habit_data);
          $html_data = $this->oral_habit_list_data();
      
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
    
     }

 private function oral_habit_list_data()
    {
      $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data');
      //print_r($chief_complain_data);die;
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
                    <th>Habit Name</th>
                      <th>Duration</th>
                  </tr></thead>';  
           if(isset($previous_oral_habit_data) && !empty($previous_oral_habit_data))
           {
              $i = 1;
              foreach($previous_oral_habit_data as $previous_oral_habit_data_list)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="habit[]" class="part_checkbox booked_checkbox" value="'.$previous_oral_habit_data_list['hab_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$previous_oral_habit_data_list['oral_habit_value'].'</td>
                        
                             <td>'.$previous_oral_habit_data_list['number'].'  '.$previous_oral_habit_data_list['time'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Oral habits data are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function remove_dental_oral_habit()
    {    
        
         $post =  $this->input->post();
         $data = array('habit'=>explode(',', $post['habit']));
      
        if(isset($data['habit']) && !empty($data['habit']))
       {
          $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data');
          
          $habit_id_list = array_column($previous_oral_habit_data, 'habit_id');
         
          foreach($previous_oral_habit_data as $key=>$previous_oral_habit_ids)
           {
              /*if(in_array($previous_oral_habit_ids['habit_id'],$data['habit']))
              {  
                 unset($previous_oral_habit_data[$key]);
              }*/
              
               if(in_array($previous_oral_habit_ids['hab_id'],$data['habit']))
              {  
                unset($previous_oral_habit_data[$previous_oral_habit_ids['hab_id']]);
              }
           }  

      $this->session->set_userdata('previous_oral_habit_data',$previous_oral_habit_data);
      $html_data = $this->oral_habit_list_data();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}


 public  function diagnosis_list()
        { 
            $post = $this->input->post();
            $diagnosis_list_data = $this->session->userdata('diagnosis_list_data'); 
            if(isset($post) && !empty($post))
           {   
            $diagnosis_list_data = $this->session->userdata('diagnosis_list_data'); 
            if(isset($diagnosis_list_data) && !empty($diagnosis_list_data))
            {
              $diagnosis_list_data = $diagnosis_list_data; 
              $tot = count($diagnosis_list_data);
            }
            else
            {
              $diagnosis_list_data = [];
            }
           
          $diagnosis_list_data[$tot+1] = array('diag_id'=>$tot+1,'diagnosis_id'=>$post['diagnosis_id'], 'diagnosis_value'=>$post['diagnosis_value'],'teeth_name_d'=>$post['teeth_name_d'],'get_teeth_number_val_diagnosis'=>$post['get_teeth_number_val_diagnosis']);
           
         
          $this->session->set_userdata('diagnosis_list_data', $diagnosis_list_data);
          $html_data = $this->diagnosis_list_data();
      
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
    
     }

 private function diagnosis_list_data()
    {
      $diagnosis_list_data = $this->session->userdata('diagnosis_list_data');
      //print_r($chief_complain_data);die;
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
                    <th>Diagnosis Name</th>
                    <th>Teeth Name</th>
                      <th>Tooth No.</th>
                  </tr></thead>';  
           if(isset($diagnosis_list_data) && !empty($diagnosis_list_data))
           {
              $i = 1;
              foreach($diagnosis_list_data as $diagnosis_list_data_list)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="habit[]" class="part_checkbox booked_checkbox" value="'.$diagnosis_list_data_list['diag_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$diagnosis_list_data_list['diagnosis_value'].'</td>
                        
                             <td>'.$diagnosis_list_data_list['teeth_name_d'].'</td>
                              <td>'.$diagnosis_list_data_list['get_teeth_number_val_diagnosis'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Diagnosis data are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function remove_dental_diagnosis_list()
    {    
        
         $post =  $this->input->post();
         $data = array('diagnosis'=>explode(',', $post['diagnosis']));
      
        if(isset($data['diagnosis']) && !empty($data['diagnosis']))
       {
          $diagnosis_list_data = $this->session->userdata('diagnosis_list_data');
          
          $diagnosis_id_list = array_column($diagnosis_list_data, 'get_teeth_number_val_diagnosis');
         
          foreach($diagnosis_list_data as $key=>$diagnosis_list_data_ids)
           {
              /*if(in_array($diagnosis_list_data_ids['get_teeth_number_val_diagnosis'],$data['diagnosis']))
              {  
                 unset($diagnosis_list_data[$key]);
              }*/
              
               if(in_array($diagnosis_list_data_ids['diag_id'],$data['diagnosis']))
              {  
                unset($diagnosis_list_data[$diagnosis_list_data_ids['diag_id']]);
              }
           }  

      $this->session->set_userdata('diagnosis_list_data',$diagnosis_list_data);
      $html_data = $this->diagnosis_list_data();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
    }


    public  function treatment_list()
        { 
            $post = $this->input->post();
            $treatment_list_data_values = $this->session->userdata('treatment_list_data_values'); 
            if(isset($post) && !empty($post))
           {   
            $treatment_list_data_values = $this->session->userdata('treatment_list_data_values'); 
            if(isset($treatment_list_data_values) && !empty($treatment_list_data_values))
            {
              $treatment_list_data_values = $treatment_list_data_values; 
              $tot=count($treatment_list_data_values);
            }
            else
            {
              $treatment_list_data_values = [];
            }
           if(!empty($post['treatment_type_id']))
           {
              $get_treatmes = get_treatment_type_name($post['treatment_type_id']);
           }
           else
           {
               $get_treatmes ='';
           }
          $treatment_list_data_values[$tot+1] = array('treat_id'=>$tot+1,'treatment_id'=>$post['treatment_id'], 'treatment_value'=>$post['treatment_value'],'teeth_name_treatment'=>$post['teeth_name_treatment'],'treatment_type_id'=>$get_treatmes,'treatment_remarks'=>$post['treatment_remarks'],''=>$post['treatment_remarks'],'get_teeth_number_val_treatment'=>$post['get_teeth_number_val_treatment']);
           
            
             
         
          $this->session->set_userdata('treatment_list_data_values', $treatment_list_data_values);
          $html_data = $this->treatment_list_data();
      
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
    
     }

 private function treatment_list_data()
    {
      $treatment_list_data_values = $this->session->userdata('treatment_list_data_values');
      //print_r($chief_complain_data);die;
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
                     <th>Treatment Name</th>
                     <th>Treatment Type</th>
                    <th>Teeth Name</th>
                      <th>Tooth No.</th>
                      <th>Remarks</th>
                  </tr></thead>';  
           if(isset($treatment_list_data_values) && !empty($treatment_list_data_values))
           {
              $i = 1;
              foreach($treatment_list_data_values as $treatment_list_data_list)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="treatment[]" class="part_checkbox booked_checkbox" value="'.$treatment_list_data_list['treat_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$treatment_list_data_list['treatment_value'].'</td>
                            <td>'.$treatment_list_data_list['treatment_type_id'].'</td>
                        
                             <td>'.$treatment_list_data_list['teeth_name_treatment'].'</td>
                              <td>'.$treatment_list_data_list['get_teeth_number_val_treatment'].'</td>
                              
                              <td>'.$treatment_list_data_list['treatment_remarks'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Treatment data are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function remove_dental_treatment_list()
    {    
        
         $post =  $this->input->post();
         $data = array('treatment'=>explode(',', $post['treatment']));
      //echo "<pre>"; print_r($post); die;
        if(isset($data['treatment']) && !empty($data['treatment']))
       {
          $treatment_list_data_values = $this->session->userdata('treatment_list_data_values');
          
          $treatment_id_list = array_column($treatment_list_data_values, 'get_teeth_number_val_treatment');
         
          foreach($treatment_list_data_values as $key=>$treatment_list_data_values_ids)
           {
             /* if(in_array($treatment_list_data_values_ids['get_teeth_number_val_treatment'],$data['treatment']))
              {  
                 unset($treatment_list_data_values[$key]);
              }*/
              
               if(in_array($treatment_list_data_values_ids['treat_id'],$data['treatment']))
              {  
                unset($treatment_list_data_values[$treatment_list_data_values_ids['treat_id']]);
              }
           }  

      $this->session->set_userdata('treatment_list_data_values',$treatment_list_data_values);
      $html_data = $this->treatment_list_data();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
    }

     public  function advice_list()
        { 
            $post = $this->input->post();
           // print_r($post);die;
            $previous_advice_data = $this->session->userdata('previous_advice_data'); 
            if(isset($post) && !empty($post))
           {   
            $previous_advice_data = $this->session->userdata('previous_advice_data'); 
            if(isset($previous_advice_data) && !empty($previous_advice_data))
            {
              $previous_advice = $previous_advice_data; 
              $tot =count($previous_advice);
            }
            else
            {
              $previous_advice = [];
            }
           
          $previous_advice[$tot+1] = array('advi_id'=>$tot+1,'advice_id'=>$post['advice_id'], 'advice_value'=>$post['advice_value']);
          $this->session->set_userdata('previous_advice_data', $previous_advice);
          $html_data = $this->advice_list_data();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
     // print_r($post);die;
     // echo "hi";die;
     }
      private function advice_list_data()
    {
      $previous_advice_data = $this->session->userdata('previous_advice_data');
      //print_r($previous_advice_data);die;
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
                    <th>Advice</th>
                  
                  </tr></thead>';  
           if(isset($previous_advice_data) && !empty($previous_advice_data))
           {
              $i = 1;
              foreach($previous_advice_data as $previous_advice)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="advice[]" class="part_checkbox booked_checkbox" value="'.$previous_advice['advi_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$previous_advice['advice_value'].'</td>
                         </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Advice data are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function remove_dental_advice()
    {    
         
         $post =  $this->input->post();
         $data = array('advice'=>explode(',', $post['advice']));
       
        if(isset($data['advice']) && !empty($data['advice']))
       {
          $previous_advice_data = $this->session->userdata('previous_advice_data');
          
          $hadvice_id_list = array_column($previous_advice_data, 'advice_id');
          
          foreach($previous_advice_data as $key=>$previous_advice_data_ids)
           {
             /* if(in_array($previous_advice_data_ids['advice_id'],$data['advice']))
              {  
                 unset($previous_advice_data[$key]);
              }*/
              
               if(in_array($previous_advice_data_ids['advi_id'],$data['advice']))
              {  
                unset($previous_advice_data[$previous_advice_data_ids['advi_id']]);
              }
           }  

      $this->session->set_userdata('previous_advice_data',$previous_advice_data);
      $html_data = $this->advice_list_data();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}

  public function view_prescription($prescription_id,$branch_id='')
    { 
        //unauthorise_permission(248,1448);
     //get_dental_prescription_tab_setting
       /* $data['form_data'] = $this->dental_prescription->get_by_ids($id);
        $data['prescription_tab_setting'] = get_dental_prescription_tab_setting('',$branch_id);
        $data['page_title'] = $data['form_data']['patient_name']." Prescription";
        $data['id'] = $id;
        $dental_prescription_info = $this->dental_prescription->get_detail_by_prescription_id($id);
        $data['all_detail']= $dental_prescription_info;
        $opd_booking_id=$data['all_detail']['prescription_list'][0]->booking_id;
        $this->load->model('opd/opd_model','opd');
        $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
        $patient_id=$data['booking_data']['patient_id'];   
        
        $data['prescription_medicine_tab_setting'] = get_dental_prescription_medicine_tab_setting('',$branch_id);*/
        
         $prescriptions_id= $this->session->userdata('dental_prescription_id');
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
        $opd_prescription_info = $this->dental_prescription->get_detail_by_prescription_id($prescription_id);
 //print_r($opd_prescription_info);
 //die;
        $template_format = $this->dental_prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
        
        $template_format_left = $this->dental_prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->dental_prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->dental_prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->dental_prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);

        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
      
        $data['all_detail']= $opd_prescription_info;
        
        $opd_booking_id=$data['all_detail']['prescription_list'][0]->booking_id;
        $data['prescription_tab_setting'] = get_dental_prescription_tab_setting('',$branch_id);


        $data['prescription_medicine_tab_setting'] = get_dental_prescription_medicine_tab_setting('',$branch_id);
        
        $this->load->model('opd/opd_model','opd');
       $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
       $patient_id=$data['booking_data']['patient_id'];
        $this->load->view('dental/dental_prescription/print_template',$data);
        $html = $this->output->get_output();
    }


 public function print_prescriptions($prescription_id="",$branch_id='')
    {

        //unauthorise_permission(248,1449);
      //print_r($prescription_id);
        $prescriptions_id= $this->session->userdata('dental_prescription_id');
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
        $opd_prescription_info = $this->dental_prescription->get_detail_by_prescription_id($prescription_id);
 //print_r($opd_prescription_info);
 //die;
        $template_format = $this->dental_prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
        
        $template_format_left = $this->dental_prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->dental_prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->dental_prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->dental_prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);

        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
      
        $data['all_detail']= $opd_prescription_info;
        
        $opd_booking_id=$data['all_detail']['prescription_list'][0]->booking_id;
        $data['prescription_tab_setting'] = get_dental_prescription_tab_setting('',$branch_id);


        $data['prescription_medicine_tab_setting'] = get_dental_prescription_medicine_tab_setting('',$branch_id);
        
        $this->load->model('opd/opd_model','opd');
       $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
       $patient_id=$data['booking_data']['patient_id'];
//echo "<pre>"; print_r($data); exit;
        $this->load->view('dental/dental_prescription/print_prescription_template',$data);
    } 
    
    public function print_blank_prescriptions($booking_id="",$branch_id='')
    {

        $data['type'] = 2;
        //$data['prescription_id'] = $prescription_id;
        $data['page_title'] = "Print Prescription";
        //$opd_prescription_info = $this->dental_prescription->get_detail_by_prescription_id($prescription_id);
        $opd_prescription_info = $this->dental_prescription->get_detail_by_booking_id($booking_id,$branch_id);
 //print_r($opd_prescription_info);
 //die;
        $template_format = $this->dental_prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
        
        $template_format_left = $this->dental_prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->dental_prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->dental_prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->dental_prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);

        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
      
        $data['all_detail']= $opd_prescription_info;
        
        $opd_booking_id=$data['all_detail']['prescription_list'][0]->booking_id;
        $data['prescription_tab_setting'] = get_dental_prescription_tab_setting('',$branch_id);


        $data['prescription_medicine_tab_setting'] = get_dental_prescription_medicine_tab_setting('',$branch_id);
        
        $this->load->model('opd/opd_model','opd');
       $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
       $patient_id=$data['booking_data']['patient_id'];
        //echo "<pre>"; print_r($data); exit;
        $this->load->view('dental/dental_prescription/print_prescription_template',$data);
    } 


     public function edit($prescription_id="",$opd_booking_id="")
      { 
        $users_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
        if(empty($post))
        {
            $this->session->unset_userdata('chief_complain_data_list');
            $this->session->unset_userdata('previous_history_disease_data');
            $this->session->unset_userdata('previous_allergy_data');
            $this->session->unset_userdata('previous_oral_habit_data');
            $this->session->unset_userdata('diagnosis_list_data');
            $this->session->unset_userdata('treatment_list_data_values');
            $this->session->unset_userdata('previous_advice_data'); 
        }

        //unauthorise_permission(248,1410);
      if(isset($prescription_id) && !empty($prescription_id) && is_numeric($prescription_id))
      {      
        //$this->load->model('eye/general/eye_general_model', 'eye_general_model');

         $this->load->model('dental/prescription_template/prescription_template_model','prescription_template');
          
       // print '<pre>';print_r($post);die;
          $this->load->helper('dental'); 
          $this->load->model('dental/general/dental_general_model','dental_general');
          //print_r($post);
          //die;
          
        $this->load->model('opd/opd_model','opd');
        $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
        $patient_id=$data['booking_data']['patient_id'];
        $get_by_id_data = $this->dental_prescription->get_by_prescription_id($prescription_id,$opd_booking_id);
        //print_r($get_by_id_data);die;
        
        $data['investigation_template_data'] = $get_by_id_data['investigation_template_data'];
       //echo"<pre>";
       //print_r($data['investigation_template_data']);die;
        $array_result = array();
        if(!empty($data['investigation_template_data']))
        {
            
            foreach ($data['investigation_template_data'] as $value) 
            {   //print_r($value);
               // $array_result[$value->investigation_template_id] = $value->investigation_sub;  
                $array_result[$value->sub_category_id] = $value->investigation_sub;  
            }
        } 
        $data['investigation_cat'] = $array_result;
        $data['medicine_template_data'] = $this->dental_prescription->get_dental_medicine_prescription_template($prescription_id,$opd_booking_id); 
        //print_r($data['investigation_cat']);
        $prescription_data = $get_by_id_data['prescription_list'][0];
        $data['prescription_tab_setting'] = get_dental_prescription_tab_setting();
        $opd_booking_data = $this->dental_prescription->get_by_id($prescription_id);
        $data['chief_complaint_list'] =$this->prescription_template->dental_chief_complaint_list();
         $data['disease_list'] = $this->prescription_template->dental_disease_list(); 
         $data['allergy_list'] = $this->prescription_template->dental_allergy_list();
         $data['habit_list'] = $this->prescription_template->dental_habit_list();
         $data['diagnosis_list'] = $this->dental_prescription->dental_diagnosis_list(); 
         $data['treatment_list'] = $this->dental_prescription->dental_treatment_list(); 
         
         $data['treatment_type_list'] = $this->dental_prescription->dental_treatment_type_list(); 
         
         $data['prescription_tab_setting'] = get_dental_prescription_tab_setting();
         $data['advice_list'] = $this->prescription_template->dental_advice_list();
         $data['template_list'] = $this->dental_prescription->dental_template_list();
         $chief_complaint_list = $this->dental_prescription->get_chief_complaint_list($prescription_id,$opd_booking_id);

        $chief_complain = $this->session->userdata('chief_complain_data_list');
        //echo "<pre>"; print_r($chief_complaint_list);
        if(!isset($chief_complain))
        {
           $this->session->set_userdata('chief_complain_data_list',$chief_complaint_list);
        }
        $disease_list = $this->dental_prescription->get_disease_list($prescription_id,$opd_booking_id);
        //print_r($disease_list);die;
        $previous_disease = $this->session->userdata('previous_history_disease_data');
        //print_r($previous_disease);die;
        if(!isset($previous_disease))
        {  //echo "hi";die;
           $this->session->set_userdata('previous_history_disease_data',$disease_list);
        }
        $previous_disease = $this->session->userdata('previous_history_disease_data');
       // print_r($previous_disease);die;
        $allergy_list = $this->dental_prescription->get_allergy_list($prescription_id,$opd_booking_id);
        //print_r($allergy_list);die;
        $previous_allergy = $this->session->userdata('previous_allergy_data');
        if(!isset($previous_allergy))
        {
           $this->session->set_userdata('previous_allergy_data',$allergy_list);
        }
        $oral_habit_list = $this->dental_prescription->get_oral_habit_list($prescription_id,$opd_booking_id);
        //print_r($oral_habit_list);die;
        $previous_oral_habit = $this->session->userdata('previous_oral_habit_data');
        if(!isset($previous_oral_habit))
        {
           $this->session->set_userdata('previous_oral_habit_data',$oral_habit_list);
        }
        $diagnosis_list = $this->dental_prescription->get_diagnosis_list($prescription_id,$opd_booking_id);
         //print_r($diagnosis_list);die;
        $diagnosis_data = $this->session->userdata('diagnosis_list_data');
        if(!isset($diagnosis_data))
        {
           $this->session->set_userdata('diagnosis_list_data',$diagnosis_list);
        }
        $treatment_list = $this->dental_prescription->get_treatment_list($prescription_id,$opd_booking_id);
       // print_r($treatment_list);die;
        $treatment_data = $this->session->userdata('treatment_list_data_values'); 
        if(!isset($treatment_data))
        {
           $this->session->set_userdata('treatment_list_data_values',$treatment_list);
        }
        $advice_list = $this->dental_prescription->get_advice_list($prescription_id,$opd_booking_id);
         //print_r($advice_list);die;
         $previous_advice_data = $this->session->userdata('previous_advice_data');
        if(!isset($previous_advice_data))
        {
           $this->session->set_userdata('previous_advice_data',$advice_list);
        }
         $data['prescription_medicine_tab_setting'] =get_dental_prescription_medicine_tab_setting();
         $data['prescription_tab_setting'] = get_dental_prescription_tab_setting();
         $data['investigation_category_list'] = $this->dental_prescription->dental_investigation_list(); 
         $data['page_title'] = "Update Prescription";  
         $post = $this->input->post();
      //print_r($data['investigation_category_list']);die;
         $data['form_error'] = ''; 
         
        //echo $prescription_data->next_appointment_date;
         $next_appointmentdate='';
        if(!empty($prescription_data->next_appointment_date) && ($prescription_data->next_appointment_date!='0000-00-00 00:00:00') && (date('d-m-Y',strtotime($prescription_data->next_appointment_date))!='01-01-1970'))
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
                              'check_appointment'=>$prescription_data->check_appointment,
                              "date_time_new" =>$prescription_data->date_time_new,
                              "next_reason" =>$prescription_data->next_reason
                              );
         //print"<pre>";print_r($data['form_data']);die; 

        
        if(isset($post) && !empty($post))
        {   

         
              
                $prescription_id = $this->dental_prescription->save();
             
                $this->session->set_userdata('dental_prescription_id',$prescription_id);
                $this->session->set_flashdata('success','Prescription updated successfully.');
                redirect(base_url('prescription/?status=print_dental'));
            

        }

        //print_r($data);die;
       $this->load->view('dental/dental_prescription/add',$data);       
      }
    } 
      

       public function delete_dental($id="")
    {
       //unauthorise_permission(248,1411);
       if(!empty($id) && $id>0)
       {
           $result = $this->dental_prescription->delete_dental($id);
           $response = "Prescription successfully deleted.";
           echo $response;
       }
    }

     public function add_perma_sub_category()
    {  
        $id=$this->uri->segment(4);
        $data['id'] = $id;
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->dental_prescription->get_permanent();
        $this->load->view('dental/dental_prescription/add_permanent_chart_category',$data);       
    }

     public function add_decidous_sub_category()
    {    
        $id=$this->uri->segment(4);
        $data['id'] = $id;   
        $data['page_title'] = "Decidous Teeth";  
        $data['get_decidous']=$this->dental_prescription->get_decidous();
        $this->load->view('dental/dental_prescription/add_decious_chart_category',$data);       
    }

   
   function get_dental_medicine_auto_vals($vals="")
    {
         //echo "hi";die;
        if(!empty($vals))
        {
            $result = $this->dental_prescription->get_dental_medicine_auto_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

   function get_dental_dosage_vals($vals="")
    {
        //echo 'rwe';die;
        if(!empty($vals))
        {
            $result = $this->dental_prescription->get_dental_dosage_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

   function get_dental_duration_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->dental_prescription->get_dental_duration_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_dental_frequency_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->dental_prescription->get_dental_frequency_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    } 

    function get_dental_advice_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->dental_prescription->get_dental_advice_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_template_data($template_id="")
    { 
        //echo $template_id;die;
         $this->session->unset_userdata('chief_complain_data_list');
      /*$x = $this->session->userdata('chief_complain_data_list');
         print_r($x);die;*/
           
        $this->session->unset_userdata('previous_history_disease_data');
        $this->session->unset_userdata('previous_allergy_data');
        $this->session->unset_userdata('previous_oral_habit_data');
        $this->session->unset_userdata('diagnosis_list_data');
        $this->session->unset_userdata('treatment_list_data_values');
        $this->session->userdata('previous_advice_data'); 
      if(($template_id)>0)
       {
        $template_data= $this->dental_prescription->get_template_data($template_id);
       echo $template_data;
        //print_r($result);die;
       }


    }

    function get_chief_complaint_data($template_id="")
    {
       
       if(($template_id)>0)
      {
         $this->session->unset_userdata('chief_complain_data_list');
         //$dsfdf= $this->session->userdata('chief_complain_data_list');

         $chief_complaint_data= $this->dental_prescription->get_chief_complaint_data($template_id);
         //print_r($chief_complaint_data); exit;
        $chief_complain_datalist = [];
        $i=1;
        foreach($chief_complaint_data as $chief_complaindata)
        {
        $chief_complain_datalist[$i]= array('chief_id'=>$i,'chief_complaint_id'=>$chief_complaindata['complaint_id'], 'teeth_name'=>$chief_complaindata['teeth_name'], 'reason'=>$chief_complaindata['reason'],'number'=>$chief_complaindata['number'],'time'=>$chief_complaindata['time'],'get_teeth_number_val'=>$chief_complaindata['tooth_no'],'chief_complaint_value'=>$chief_complaindata['complaint_name']);
        $i++;
        }
      //print_r($chief_complain_datalist);die;
    $this->session->set_userdata('chief_complain_data_list',$chief_complain_datalist);
    $chiefcomplain_data_list = $this->session->userdata('chief_complain_data_list');
   //print_r($chiefcomplain_data_list);die;

     echo json_encode($chiefcomplain_data_list);
        //print_r($result);die;
      }

    }

    function get_previous_history_data($template_id="")
    {
      // echo "hi";die;
      
       if(($template_id)>0)
      {   
         $this->session->unset_userdata('previous_history_disease_data');
         $previous_history_data= $this->dental_prescription->get_previous_history_data($template_id);
         $previous_history_data_list = [];
         if($previous_history_data!=NULL)
         {
             $i=1;
         foreach($previous_history_data as $previous_historydata)
         {
          $previous_history_data_list[$i] = array('prev_id'=>$i,'disease_id'=>$previous_historydata['disease_id'], 'disease_value'=>$previous_historydata['disease_name'], 'disease_details'=>$previous_historydata['disease_details'],'operation'=>$previous_historydata['operation'],'operation_date'=>$previous_historydata['operation_date']);
          $i++;
         }
         }
       $this->session->set_userdata('previous_history_disease_data',$previous_history_data_list);
       $previoushistorydata = $this->session->userdata('previous_history_disease_data');
       echo json_encode($previoushistorydata);
      }
       

    }
    
    function get_previous_allergy_data($template_id="")
    {
      if($template_id>0)
      {
         $this->session->unset_userdata('previous_allergy_data');
         $previous_allergy_data= $this->dental_prescription->get_previous_allergy_data($template_id);
        // print_r($previous_allergy_data);die;
         $previous_allergy_data_list = [];
         if($previous_allergy_data!=NULL)
         {
             $i=1;
         foreach($previous_allergy_data as $previous_allergydata)
        {
          $previous_allergy_data_list[$i] = array('aller_id'=>$i,'allergy_id'=>$previous_allergydata['allergy_id'], 'allergy_value'=>$previous_allergydata['allergy_name'], 'reason'=>$previous_allergydata['reason'],'number'=>$previous_allergydata['number'],'time'=>$previous_allergydata['time']);  
           //$this->session->set_userdata('previous_allergy_data', $previous_allergy);
        }
      }
       $this->session->set_userdata('previous_allergy_data',$previous_allergy_data_list);
      $previousallergydata = $this->session->userdata('previous_allergy_data');
       echo json_encode($previousallergydata);
      }

    }
  function get_oral_habit_data($template_id="")
    {
    if(($template_id)>0)
      {
      $this->session->unset_userdata('previous_allergy_data');
      $previous_oral_habit_data= $this->dental_prescription->get_previous_oral_habit_data($template_id);
      //print_r($previous_oral_habit_data);die;
         $previous_oral_habit_data_list = [];
         foreach($previous_oral_habit_data as $previous_oral_habit)
        {
             $previous_oral_habit_data_list[$previous_oral_habit['oral_habit_id']] = array('habit_id'=>$previous_oral_habit['oral_habit_id'], 'oral_habit_value'=>$previous_oral_habit['oral_habit_name'],'number'=>$previous_oral_habit['number'],'time'=>$previous_oral_habit['time']);
        }
       $this->session->set_userdata('previous_oral_habit_data',$previous_oral_habit_data_list);
        $previousoralhabitdata = $this->session->userdata('previous_oral_habit_data');
       echo json_encode($previousoralhabitdata);
      }

    }
    function get_diagnosis_data($template_id="")
    {
      if(($template_id)>0)
      {
        $this->session->unset_userdata('diagnosis_list_data');
        $previous_diagnosis_data= $this->dental_prescription->get_previous_diagnosis_data($template_id);
        //print_r($previous_diagnosis_data);die;
         $previous_diagnosis_data_list = [];
         if($previous_diagnosis_data!=NULL)
         {
         foreach($previous_diagnosis_data as $previous_diagnosis)
        {
              $previous_diagnosis_data_list[$previous_diagnosis['tooth_no']] = array('diagnosis_id'=>$previous_diagnosis['diagnosis_id'], 'diagnosis_value'=>$previous_diagnosis['diagnosis_name'],'teeth_name_d'=>$previous_diagnosis['teeth_name'],'get_teeth_number_val_diagnosis'=>$previous_diagnosis['tooth_no']);
        }
      }
       $this->session->set_userdata('diagnosis_list_data',$previous_diagnosis_data_list);
        $previousdiagnosisdata = $this->session->userdata('diagnosis_list_data');
       echo json_encode($previousdiagnosisdata);
      }

    }
       function get_treatment_data($template_id="")
    {
      if(($template_id)>0)
      {
       $previous_treatment_data= $this->dental_prescription->get_previous_treatment_data($template_id);
        $this->session->unset_userdata('treatment_list_data_values');
         $previous_treatment_data_list = [];
         $i=1;
         foreach($previous_treatment_data as $previous_treatment)
        {
            
              $previous_treatment_data_list[$previous_treatment['tooth_no']] =array('treat_id'=>$i,'treatment_id'=>$previous_treatment['treatment_id'], 'treatment_value'=>$previous_treatment['treatment_name'],'teeth_name_treatment'=>$previous_treatment['teeth_name'],'treatment_type_id'=>$previous_treatment['treatment_type_id'],'get_teeth_number_val_treatment'=>$previous_treatment['tooth_no'],'treatment_remarks'=>$previous_treatment['treatment_remarks']);
              $i++;
     

      }
     $this->session->set_userdata('treatment_list_data_values',$previous_treatment_data_list);
      $previoustreatmentdata = $this->session->userdata('treatment_list_data_values');
       echo json_encode($previoustreatmentdata);
    }
  }
  function get_advice_data($template_id="")
    {
      if(($template_id)>0)
      {
       $previous_advice_data= $this->dental_prescription->get_previous_advice_data($template_id);
       $this->session->unset_userdata('previous_advice_data');
       $previous_advice_data_list = [];
         foreach($previous_advice_data as $previous_advice)
        {
              $previous_advice_data_list[$previous_advice['advice_id']]= array('advice_id'=>$previous_advice['advice_id'], 'advice_value'=>$previous_advice['advice_name']);
       
       }
      $this->session->set_userdata('previous_advice_data',$previous_advice_data_list);
       $previousadvicedata = $this->session->userdata('previous_advice_data');
       echo json_encode($previousadvicedata);
    }
  }
  function get_medicine_data($template_id="")
  {
     if(($template_id)>0)
      {
       $previous_medicine_data= $this->dental_prescription->get_previous_medicine_data($template_id);
       echo $previous_medicine_data;

    }

  }
    function get_investigation_data($template_id="")
  { 
     if(($template_id)>0)
      {
        $previous_investigation_data= $this->dental_prescription->get_previous_investigation_data($template_id);
        echo json_encode($previous_investigation_data);

    }

  }
  
    //neha 20-2-2019
  public function upload_dental_prescription($prescription_id='',$booking_id='')
{ 
    if($prescription_id=='0' && !empty($booking_id))
    {
      $this->load->model('opd/opd_model','opd');
      $prescription_id=$this->opd->save_prescription_before_file_upload($booking_id);
    }
   // unauthorise_permission(321,1942);
    $data['page_title'] = 'Upload Dental Prescription';   
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
             $config['upload_path']   = DIR_UPLOAD_PATH.'dental/prescription/';  
             $config['allowed_types'] = 'pdf|jpeg|jpg|png|xls|csv'; 
             $config['max_size']      = 0; 
             $config['encrypt_name'] = TRUE; 
             $this->load->library('upload', $config);
             
             if ($this->upload->do_upload('prescription_files')) 
             {
                $file_data = $this->upload->data(); 
                $this->dental_prescription->save_file($file_data['file_name']);
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


    $this->load->view('dental/dental_prescription/add_file',$data);
}

public function view_files($prescription_id)
{ 
  //unauthorise_permission(321,1941);
  $data['page_title'] = "Dental Prescription Files";
  $data['prescription_id'] = $prescription_id;
  $this->load->view('dental/dental_prescription/view_prescription_files',$data);
}

public function ajax_file_list($prescription_id='')
{ 
        
        //unauthorise_permission(321,1941);
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
            if(!empty($prescription->prescription_files) && file_exists(DIR_UPLOAD_PATH.'dental/prescription/'.$prescription->prescription_files))
            {
                $sign_img = ROOT_UPLOADS_PATH.'dental/prescription/'.$prescription->prescription_files;
                $sign_img = '<a href="'.$sign_img.'" download><img src="'.$sign_img.'" width="100px" /></a>';
            }
             $row[] = $prescription->doc_name;
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
       //unauthorise_permission(321,1411);
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_file->delete($id);
           $response = "Prescription file successfully deleted.";
           echo $response;
       }
    }

    function deleteall_prescription_file()
    {
        //unauthorise_permission(321,1411);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_file->deleteall($post['row_id']);
            $response = "Prescription file successfully deleted.";
            echo $response;
        }
    }
   
}
?>