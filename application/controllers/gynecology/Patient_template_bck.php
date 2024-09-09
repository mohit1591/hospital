<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_template extends CI_Controller {
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
    $this->load->model('gynecology/patient_template/patient_template_model','patienttemplate');
    $this->load->library('form_validation');
    }

    
    public function index()
    {
        // echo "hi";die;
         unauthorise_permission(295,1742);
        $this->session->unset_userdata('chief_complain_data');
        $this->session->unset_userdata('previous_history_disease_data');
        $this->session->unset_userdata('previous_allergy_data');
        $this->session->unset_userdata('previous_oral_habit_data');
        $this->session->unset_userdata('previous_advice_data');
        $this->session->unset_userdata('diagnosis_data');
        $this->session->unset_userdata('treatment_data');

        $data['page_title'] = 'Gynecology Patient Template List'; 
        $this->load->view('gynecology/patient_template/list',$data);
    }

    public function ajax_list()
    {   
       unauthorise_permission(295,1742);
        $this->session->unset_userdata('book_test');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->patienttemplate->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescriptions) {
         // print_r($test);die;
            $no++;
            $row = array(); 
            
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

            if($prescriptions->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }  
           
            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$prescriptions->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$prescriptions->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $prescriptions->template_name;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($prescriptions->created_date)); 
      
            //Action button /////
            $btn_confirm="";
            $btn_edit = ""; 
            $btn_delete = ""; 
              
            if(in_array('1744',$users_data['permission']['action'])) 
            {
              $btn_edit = ' <a class="btn-custom" href="'.base_url("gynecology/patient_template/edit/".$prescriptions->id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
           }
            if(in_array('1745',$users_data['permission']['action'])) 
            {
              $btn_delete = ' <a class="btn-custom" onClick="return delete_template('.$prescriptions->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>'; 
            }
              
            // End Action Button //
            
            $row[] = $btn_edit.$btn_delete;   
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->patienttemplate->count_all(),
                        "recordsFiltered" => $this->patienttemplate->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

  
    public function delete($id="")
    {
        unauthorise_permission(295,1745);
       if(!empty($id) && $id>0)
       {
           $result = $this->patienttemplate->delete($id);
           $response = "Dental Prescription Template successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission(295,1745);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->patienttemplate->deleteall($post['row_id']);
            $response = "Dental Prescription Template Booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        //unauthorise_permission(247,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->patienttemplate->get_by_id($id);  
        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('eye/patient_template/view',$data);     
      }
    }  


   
  public function add()
  {
        unauthorise_permission(295,1743);
        $this->load->model('gynecology/general/Gynecology_general_model','gynecology_general'); 
        $this->load->helper('gynecology'); 
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
        
        $this->load->model('opd/opd_model','opd');
        //$this->load->model('patient/patient_model');

        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();

      //  $data['chief_complaints'] = $this->eye_general->chief_complaint_list(); 
        
        
        $data['relation_list'] = $this->patienttemplate->gynecology_realtion_list();
        $data['disease_list'] = $this->patienttemplate->gynecology_disease_list();
        $data['complaint_list'] = $this->patienttemplate->gynecology_complaint_list();
        $data['allergy_list'] = $this->patienttemplate->gynecology_allergy_list();
        $data['general_examination_list'] = $this->patienttemplate->gynecology_general_examination_list();
        $data['clinical_examination_list'] = $this->patienttemplate->gynecology_clinical_examination_list();

        //old
        /*$data['allergy_list'] = $this->patienttemplate->dental_allergy_list();*/
        $data['habit_list'] = $this->patienttemplate->dental_habit_list();
        $data['advice_list'] = $this->patienttemplate->dental_advice_list();
        $data['diagnosis_list'] = $this->patienttemplate->dental_diagnosis_list();
        $data['treatment_list'] = $this->patienttemplate->dental_treatment_list();
        $data['investigation_category_list'] = $this->patienttemplate->dental_investigation_list();
   
        $data['blood_group_list'] =  $this->patienttemplate->get_blood_group(); 
        //print_r($data['blood_group_list']);die;

       $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting();
      // echo "<pre>";print_r($data['prescription_tab_setting']);die;

        $data['prescription_medicine_tab_setting'] = get_dental_prescription_medicine_tab_setting();

        $data['page_title'] = "Add Dental Prescription Template";
                
        $post = $this->input->post();
        //print_r($post);die;
       
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  "template_name"=>'',
                                  'patient_bp'=>"",
                                  'patient_temp'=>"",
                                  'patient_weight'=>"",
                                  'patient_spo2'=>"",
                                  'patient_height'=>'',
                                  'prv_history'=>"",
                                  'personal_history'=>"",
                                  'examination'=>'',
                                  'diagnosis'=>'',
                                  'suggestion'=>'',
                                  'biometric_details'=>'',
                                  'systemic_illness'=>'',
                                  'remark'=>'',
                                  'next_appointment_date'=>"",
                                  'appointment_time'=>"",
                                  'appointment_date'=>"",
                                  'blood_group'=>"",
                                  'disease_id'=>"",
                                  'disease_details'=>"",
                                  'operation'=>"",
                                  'operation_date'=>"",
                                  'chief_complaint_id'=>"",
                                  'reason'=>"",
                                  'teeth_name'=>"",
                                  'number'=>"",
                                  'time'=>"",
                                  'allergy_id'=>"",
                                  'habit_id'=>"",
                                  'advice_id'=>"",
                                  'diagnosis_id'=>"",
                                  'treatment_id'=>"",
                                  'investigation_id'=>"",
                                  'check_appointment'=>"",
                                  'next_appointment_date'=>""

                                  );
        if(isset($post) && !empty($post))
        {   
           
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {


              $this->patienttemplate->save();
              $this->session->set_flashdata('success','Dental Prescription template successfully added.');
              redirect(base_url('gynecology/patient_template'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }    
        }   

         $this->load->view('gynecology/patient_template/add_template',$data);
    }

    public function edit($id="")
    {
      unauthorise_permission('295','1744');
 
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->patienttemplate->get_by_id($id); 
       //print_r($result);die;
        $data['investigation_template_data'] = $result['investigation_template_data'];
        $array_result = array();
        if(!empty($result['investigation_template_data']))
        {
            
            foreach ($result['investigation_template_data'] as $value) 
            {   //print_r($value);
                $array_result[$value->sub_category_id] = $value->investigation_sub;    
                 //$array_result[$value->available_day] = $value->available_day;
            }
        }


        $data['investigation_cat'] = $array_result;
        $data['medicine_template_data'] = $this->patienttemplate->get_dental_medicine_prescription_template($id); 
       // print_r($data['medicine_template_data']);die;
        $data['chief_complaint_list'] = $this->patienttemplate->dental_chief_complaint_list();
        $data['disease_list'] = $this->patienttemplate->dental_disease_list();
        $data['allergy_list'] = $this->patienttemplate->dental_allergy_list();
        $data['habit_list'] = $this->patienttemplate->dental_habit_list();
        $data['advice_list'] = $this->patienttemplate->dental_advice_list();
        $data['diagnosis_list'] = $this->patienttemplate->dental_diagnosis_list();
        $data['treatment_list'] = $this->patienttemplate->dental_treatment_list();
        $data['investigation_category_list'] = $this->patienttemplate->dental_investigation_list();
        $chief_complaint_list = $this->patienttemplate->get_chief_complaint_list($id);
        $chief_complain = $this->session->userdata('chief_complain_data');
        if(!isset($chief_complain))
        {
           $this->session->set_userdata('chief_complain_data',$chief_complaint_list);
        }
        $disease_list = $this->patienttemplate->get_disease_list($id);
        //print_r($disease_list);die;
        $previous_disease = $this->session->userdata('previous_history_disease_data');
        //print_r($previous_disease);die;
        if(!isset($previous_disease))
        {  //echo "hi";die;
           $this->session->set_userdata('previous_history_disease_data',$disease_list);
        }
        $allergy_list = $this->patienttemplate->get_allergy_list($id);
        $previous_allergy = $this->session->userdata('previous_allergy_data');
        if(!isset($previous_allergy))
        {
           $this->session->set_userdata('previous_allergy_data',$allergy_list);
        }
        $oral_habit_list = $this->patienttemplate->get_oral_habit_list($id);
       //print_r($oral_habit_list);die;
        $previous_oral_habit = $this->session->userdata('previous_oral_habit_data');
        if(!isset($previous_oral_habit))
        {
           $this->session->set_userdata('previous_oral_habit_data',$oral_habit_list);
        }
        $diagnosis_list = $this->patienttemplate->get_diagnosis_list($id);
       //print_r($diagnosis_list);die;
        $diagnosis_data_list = $this->session->userdata('diagnosis_data');
        //print_r($diagnosis_data_list);die;
        if(!isset($diagnosis_data_list))
        {
           $this->session->set_userdata('diagnosis_data',$diagnosis_list);
        }
        $treatment_list = $this->patienttemplate->get_treatment_list($id);
       // print_r($treatment_list);die;
        $treatment_data = $this->session->userdata('treatment_data'); 
        if(!isset($treatment_data))
        {
           $this->session->set_userdata('treatment_data',$treatment_list);
        }
        $advice_list = $this->patienttemplate->get_advice_list($id);
       // print_r($diagnosis_list);die;
         $previous_advice_data = $this->session->userdata('previous_advice_data');
        if(!isset($previous_advice_data))
        {
           $this->session->set_userdata('previous_advice_data',$advice_list);
        }
    /*    $data['investigation_template_data'] = $this->patienttemplate->get_investigation_template_data($id); */

       //print_r($data['investigation_template_data']);die;
        $data['page_title'] = "Dental Update Prescription Template";  
        $post = $this->input->post();
        //print_r($post);die;
        $data['form_error'] = ''; 

  $next_appointmentdate='';
          if(!empty($result['appointment_date']) && ($result['appointment_date']!='0000-00-00 00:00:00') && (date('d-m-Y',strtotime($result['appointment_date']))!='01-01-1970'))
        {
            $next_appointmentdate = date('d-m-Y H:i A',strtotime($result['appointment_date']));
        }   
        else
        {
            $next_appointmentdate = ''; 
        }


        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'template_name'=>$result['template_name'], 
                                  'chief_complaint_id'=>"",
                                  'disease_id'=>"",
                                  'disease_details'=>"",
                                  'operation'=>"",
                                  'operation_date'=>"",
                                  'allergy_id'=>"",
                                  'habit_id'=>"",
                                  'reason'=>"",
                                  'treatment_id'=>"",
                                  'advice_id'=>"",
                                  'blood_group'=>$result['blood_group'],
                                  'next_appointment_date'=>$next_appointmentdate,
                                  'check_appointment'=>$result['check_appointment'],
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->patienttemplate->save();

                $this->session->set_flashdata('success','Dental Prescription template updated successfully.');
                redirect(base_url('gynecology/patient_template'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
         $data['prescription_tab_setting'] = get_dental_prescription_tab_setting();
        $data['blood_group_list'] =  $this->patienttemplate->get_blood_group();

       $data['prescription_medicine_tab_setting'] = get_dental_prescription_medicine_tab_setting();
       $this->load->view('gynecology/patient_template/add_template',$data);       
      }
    }



   public function delete_pres_medicine($medicine_presc_id="",$template_id='')
   {
      if($medicine_presc_id>0)
      {
         $pres_temp_list = $this->patienttemplate->delete_pres_medicine($medicine_presc_id,$template_id);
         echo $pres_temp_list;
      }
    }

    private function _validate()
    {
        $post = $this->input->post();
        //print_r($post); exit;    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('template_name', 'Template Name', 'trim|required');
        $remark=''; 
        if(!empty($post['remark']))
        {
          $remark=$post['remark'];
        }
        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'template_name'=>$post['template_name'],
                                          'blood_group'=>'',
                                           'chief_complaint_id'=>'',
                                           'disease_details'=>'',
                                           'disease_id'=>'',
                                           'operation_date'=>'',
                                           'operation'=>'',
                                           'allergy_id'=>'',
                                           'reason'=>'',
                                           'habit_id'=>'',
                                           'diagnosis_id'=>'',
                                           'treatment_id'=>'',
                                           'advice_id'=>'',
                                           'check_appointment'=>'',
                                           'next_appointment_date'=>'',
                                        'remark'=>$remark,

                                       ); 
            return $data['form_data'];
        }   
    }


    
    
    public function archive()
    {
       unauthorise_permission('295','1746');
        $data['page_title'] = 'Dental Prescription Template Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/patient_template/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('295','1746');
        $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive'); 

        $list = $this->prescription_template_archive->get_datatables(); 
        //print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescriptiont) { 
            $no++;
            $row = array();
            if($prescriptiont->status==1)
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
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$prescriptiont->id.'">'.$check_script; 
            $row[] = $prescriptiont->template_name;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($prescriptiont->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1748',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_prescription_template('.$prescriptiont->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1747',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$prescriptiont->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription_template_archive->count_all(),
                        "recordsFiltered" => $this->prescription_template_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function restore($id="")
    {
       unauthorise_permission('295','1748');
       $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_template_archive->restore($id);
           $response = "Dental Prescription Template successfully restore in list.";
           echo $response;
       }
    }

    function restoreall()
    { 
      unauthorise_permission('295','1748');
        $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_template_archive->restoreall($post['row_id']);
            $response = "Dental Prescription Template successfully restore in list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('295','1747');
       $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_template_archive->trash($id);
           $response = "Dental Prescription Template successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('295','1747');
        $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_template_archive->trashall($post['row_id']);
            $response = "Dental Prescription Template successfully deleted parmanently.";
            echo $response;
        }
    }



   
   function get_gynecology_medicine_auto_vals($vals="")
    {
         //echo "hi";die;
        if(!empty($vals))
        {
            $result = $this->patienttemplate->get_gynecology_medicine_auto_vals($vals);  
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
            $result = $this->patienttemplate->get_dental_dosage_vals($vals);  
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
            $result = $this->patienttemplate->get_dental_duration_vals($vals);  
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
            $result = $this->patienttemplate->get_dental_frequency_vals($vals);  
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
            $result = $this->patienttemplate->get_dental_advice_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    function get_eye_diseases_data($branch_id="")
    {
      if($branch_id>0)
      {
        $diseases_data = $this->patienttemplate->get_eye_diseases_data($branch_id);
        echo $diseases_data;
      }
    } 

    function get_test_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->patienttemplate->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    } 
    public function patient_history_list()
    {

      //$this->session->unset_userdata('patient_history_data');die;
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
        $patient_history[] = array('marriage_status'=>$post['marriage_status'], 'married_life_unit'=>$post['married_life_unit'],'married_life_type'=>$post['married_life_type'],'marriage_no'=>$post['marriage_no'],'marriage_details'=>$post['marriage_details'],'previous_delivery'=>$post['previous_delivery'],'delivery_type'=>$post['delivery_type'],'delivery_details'=>$post['delivery_details'],'row_id'=>$post['row_id']);
        $this->session->set_userdata('patient_history_data', $patient_history);
        $html_data = $this->patient_history_template_list();
        //print_r($html_data);die;
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
              foreach($patient_history_data as $patienthistorydata)
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
                 $rows .= '<tr id="'.$patienthistorydata['row_id'].'">
                            <td width="60" align="center"><input type="checkbox" name="patient_history[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$i.'">
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

      //$this->session->unset_userdata('patient_history_data');die;
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
        $patient_history[] = array('relation'=>$post['relation'], 'disease'=>$post['disease'],'family_description'=>$post['family_description'],'family_duration_unit'=>$post['family_duration_unit'],'family_duration_type'=>$post['family_duration_type']);
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
              foreach($patient_family_history_data as $patient_family_history_data)
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
                            <td width="60" align="center"><input type="checkbox" name="patient_family_history[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$i.'">
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

      //$this->session->unset_userdata('patient_personal_history_data');die;
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
        $patient_history[] = array('br_discharge'=>$post['br_discharge'], 'side'=>$post['side'],'hirsutism'=>$post['hirsutism'],'white_discharge'=>$post['white_discharge'],'type'=>$post['type'],'dyspareunia'=>$post['dyspareunia'],'personal_details'=>$post['personal_details']);
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
                      <th>Dyspareunia</th>
                      <th>Details</th>
                  </tr></thead>';  
           if(isset($patient_personal_history_data) && !empty($patient_personal_history_data))
           {
              $i = 1;
              foreach($patient_personal_history_data as $patient_personal_history_data)
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
                            <td width="60" align="center"><input type="checkbox" name="patient_personal_history[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$i.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_personal_history_data['br_discharge'].'</td>
                            <td>'.$patient_personal_history_data['side'].'</td>
                            <td>'.$patient_personal_history_data['hirsutism'].'</td>
                            <td>'.$patient_personal_history_data['white_discharge'].'</td>
                            <td>'.$patient_personal_history_data['type'].'</td>
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

      //$this->session->unset_userdata('patient_personal_history_data');die;
      $post = $this->input->post();

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
        $patient_menstrual_history[] = array('previous_cycle'=>$post['previous_cycle'], 'prev_cycle_type'=>$post['prev_cycle_type'],'present_cycle'=>$post['present_cycle'],'present_cycle_type'=>$post['present_cycle_type'],'lmp_date'=>$post['lmp_date'],'dysmenorrhea'=>$post['dysmenorrhea'],'dysmenorrhea_type'=>$post['dysmenorrhea_type'],'cycle_details'=>$post['cycle_details']);
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
              foreach($patient_menstrual_history_data as $patient_menstrual_history_data)
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
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_menstrual_history[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$i.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_menstrual_history_data['previous_cycle'].'</td>
                            <td>'.$patient_menstrual_history_data['prev_cycle_type'].'</td>
                            <td>'.$patient_menstrual_history_data['present_cycle'].'</td>
                            <td>'.$patient_menstrual_history_data['present_cycle_type'].'</td>
                            <td>'.$patient_menstrual_history_data['cycle_details'].'</td>
                            <td>'.$patient_menstrual_history_data['lmp_date'].'</td>
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
        $patient_medical_history[] = array('tb'=>$post['tb'], 'tb_rx'=>$post['tb_rx'],'dm'=>$post['dm'],'dm_years'=>$post['dm_years'],'dm_rx'=>$post['dm_rx'],'ht'=>$post['ht'],'medical_details'=>$post['medical_details'],'medical_others'=>$post['medical_others']);
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
              foreach($patient_medical_history_data as $patient_medical_history_data)
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
                            <td width="60" align="center"><input type="checkbox" name="patient_medical_history[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$i.'">
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
        $patient_obestetric_history[] = array('obestetric_g'=>$post['obestetric_g'], 'obestetric_p'=>$post['obestetric_p'],'obestetric_l'=>$post['obestetric_l'],'obestetric_mtp'=>$post['obestetric_mtp']);
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
              foreach($patient_obestetric_history_data as $patient_obestetric_history_data)
              {
                
                
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_obestetric_history[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$i.'">
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
       
        $patient_disease[] = array('patient_disease_id'=>$post['patient_disease_id'], 'disease_value'=>$post['disease_value'], 'patient_disease_unit'=>$post['patient_disease_unit'],'patient_disease_type'=>$post['patient_disease_type'],'disease_description'=>$post['disease_description']);
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
              foreach($patient_disease_data as $patient_disease_data_rec)
              {
                if (strpos($patient_disease_data_rec['patient_disease_type'], 'Select') !== false) 
                {
                    $patient_disease_data_rec['patient_disease_type'] = "";
                }
                else
                {
                  $patient_disease_data_rec['patient_disease_type'] = $patient_disease_data_rec['patient_disease_type'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_disease_name[]" class="part_checkbox booked_checkbox"  ></td>
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
       
        $patient_complaint[] = array('patient_complaint_id'=>$post['patient_complaint_id'], 'complaint_value'=>$post['complaint_value'], 'patient_complaint_unit'=>$post['patient_complaint_unit'],'patient_complaint_type'=>$post['patient_complaint_type'],'complaint_description'=>$post['complaint_description']);
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
              foreach($patient_complaint_data as $patient_complaint_data_rec)
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
                            <td width="60" align="center"><input type="checkbox" name="patient_complaint_name[]" class="part_checkbox booked_checkbox"  ></td>
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
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
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
       
        $patient_allergy[] = array('patient_allergy_id'=>$post['patient_allergy_id'], 'allergy_value'=>$post['allergy_value'], 'patient_allergy_unit'=>$post['patient_allergy_unit'],'patient_allergy_type'=>$post['patient_allergy_type'],'allergy_description'=>$post['allergy_description']);
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
              foreach($patient_allergy_data as $patient_allergy_data_rec)
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
                            <td width="60" align="center"><input type="checkbox" name="patient_allergy_name[]" class="part_checkbox booked_checkbox"  ></td>
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
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
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
       
        $patient_general_examination[] = array('patient_general_examination_id'=>$post['patient_general_examination_id'], 'general_examination_value'=>$post['general_examination_value'], 'patient_general_examination_unit'=>$post['patient_general_examination_unit'],'patient_general_examination_type'=>$post['patient_general_examination_type'],'general_examination_description'=>$post['general_examination_description']);
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
                    <th>Duration</th>
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_general_examination_data) && !empty($patient_general_examination_data))
           {
              $i = 1;
              foreach($patient_general_examination_data as $patient_general_examination_data_rec)
              {
                if (strpos($patient_general_examination_data_rec['patient_general_examination_type'], 'Select') !== false) 
                {
                    $patient_general_examination_data_rec['patient_general_examination_type'] = "";
                }
                else
                {
                  $patient_general_examination_data_rec['patient_general_examination_type'] = $patient_general_examination_data_rec['patient_general_examination_type'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_general_examination_name[]" class="part_checkbox booked_checkbox"  ></td>
                            <td>'.$i.'</td>
                             <td>'.$patient_general_examination_data_rec['general_examination_value'].'</td>
                            <td>'.$patient_general_examination_data_rec['patient_general_examination_unit'].' '.$patient_general_examination_data_rec['patient_general_examination_type'].'</td>
                            <td>'.$patient_general_examination_data_rec['general_examination_description'].'</td>
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
       
        $patient_clinical_examination[] = array('patient_clinical_examination_id'=>$post['patient_clinical_examination_id'], 'clinical_examination_value'=>$post['clinical_examination_value'], 'patient_clinical_examination_unit'=>$post['patient_clinical_examination_unit'],'patient_clinical_examination_type'=>$post['patient_clinical_examination_type'],'clinical_examination_description'=>$post['clinical_examination_description']);
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
                    <th>Duration</th>
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_clinical_examination_data) && !empty($patient_clinical_examination_data))
           {
              $i = 1;
              foreach($patient_clinical_examination_data as $patient_clinical_examination_data_rec)
              {
                if (strpos($patient_clinical_examination_data_rec['patient_clinical_examination_type'], 'Select') !== false) 
                {
                    $patient_clinical_examination_data_rec['patient_clinical_examination_type'] = "";
                }
                else
                {
                  $patient_clinical_examination_data_rec['patient_clinical_examination_type'] = $patient_clinical_examination_data_rec['patient_clinical_examination_type'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_clinical_examination_name[]" class="part_checkbox booked_checkbox"  ></td>
                            <td>'.$i.'</td>
                             <td>'.$patient_clinical_examination_data_rec['clinical_examination_value'].'</td>
                            <td>'.$patient_clinical_examination_data_rec['patient_clinical_examination_unit'].' '.$patient_clinical_examination_data_rec['patient_clinical_examination_type'].'</td>
                            <td>'.$patient_clinical_examination_data_rec['clinical_examination_description'].'</td>
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

    //old

    
     public  function allergy_list()
        { 
            $post = $this->input->post();
           //print_r($post);die;
            $previous_allergy_data = $this->session->userdata('previous_allergy_data'); 
            if(isset($post) && !empty($post))
           {   
            $previous_allergy_data = $this->session->userdata('previous_allergy_data'); 
            if(isset($previous_allergy_data) && !empty($previous_allergy_data))
            {
              $previous_allergy = $previous_allergy_data; 
            }
            else
            {
              $previous_allergy = [];
            }
           
          $previous_allergy[$post['allergy_id']] = array('allergy_id'=>$post['allergy_id'], 'allergy_value'=>$post['allergy_value'], 'reason'=>$post['reason'],'number'=>$post['number'],'time'=>$post['time']);
       // print_r($previous_allergy);die;
         
          $this->session->set_userdata('previous_allergy_data', $previous_allergy);
          $html_data = $this->allergy_template_list();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
     // print_r($post);die;
     // echo "hi";die;
     }
       private function allergy_template_list()
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
              foreach($previous_allergy_data as $previousallergydata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="allergy[]" class="part_checkbox booked_checkbox" value="'.$previousallergydata['allergy_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$previousallergydata['allergy_value'].'</td>
                            <td>'.$previousallergydata['reason'].'</td>
                          
                             <td>'.$previousallergydata['number'].'  '.$previousallergydata['time'].'</td>
                            
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
     public  function oral_habit_list()
        { 
            $post = $this->input->post();
           // print_r($post);die;
            $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data'); 
            if(isset($post) && !empty($post))
           {   
            $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data'); 
            if(isset($previous_oral_habit_data) && !empty($previous_oral_habit_data))
            {
              $previous_oral_habit = $previous_oral_habit_data; 
            }
            else
            {
              $previous_oral_habit_data = [];
            }
           
          $previous_oral_habit_data[$post['habit_id']] = array('habit_id'=>$post['habit_id'], 'oral_habit_value'=>$post['oral_habit_value'],'number'=>$post['number'],'time'=>$post['time']);
           //print_r($previous_oral_habit_data);die;
         
          $this->session->set_userdata('previous_oral_habit_data', $previous_oral_habit_data);
          $html_data = $this->oral_habit_template_list();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
     // print_r($post);die;
     // echo "hi";die;
     }
     private function oral_habit_template_list()
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
              foreach($previous_oral_habit_data as $previousoralhabitdata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="habit[]" class="part_checkbox booked_checkbox" value="'.$previousoralhabitdata['habit_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$previousoralhabitdata['oral_habit_value'].'</td>
                        
                             <td>'.$previousoralhabitdata['number'].'  '.$previousoralhabitdata['time'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Oral habits are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
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
            }
            else
            {
              $previous_advice = [];
            }
           
          $previous_advice[$post['advice_id']] = array('advice_id'=>$post['advice_id'], 'advice_value'=>$post['advice_value']);
          $this->session->set_userdata('previous_advice_data', $previous_advice);
          $html_data = $this->advice_template_list();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
     // print_r($post);die;
     // echo "hi";die;
     }
      private function advice_template_list()
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
              foreach($previous_advice_data as $previousadvicedata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="advice[]" class="part_checkbox booked_checkbox" value="'.$previousadvicedata['advice_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$previousadvicedata['advice_value'].'</td>
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
    public  function diagnosis_list()
     {
          
            $post = $this->input->post();
            //print_r($post);die;
            $diagnosis_data = $this->session->userdata('diagnosis_data'); 
          if(isset($post) && !empty($post))
          {   
            $diagnosis_data = $this->session->userdata('diagnosis_data'); 
            if(isset($diagnosis_data) && !empty($diagnosis_data))
            {
              $diagnosisdata = $diagnosis_data; 
            }
            else
            {
              $diagnosisdata = [];
            }
           
          $diagnosisdata[$post['diagnosis_id']] = array('diagnosis_id'=>$post['diagnosis_id'], 'teeth_name_diagnosis'=>$post['teeth_name_diagnosis'],'diagnosis_value'=>$post['diagnosis_value'],'get_teeth_number_val_diagnosis'=>$post['get_teeth_number_val_diagnosis']);
       // print_r($chief_complain_data);die;
         
          $this->session->set_userdata('diagnosis_data', $diagnosisdata);
          $html_data = $this->diagnosis_template_list();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
     // print_r($post);die;
     // echo "hi";die;
     }
      private function diagnosis_template_list()
    {
      $diagnosis_data = $this->session->userdata('diagnosis_data');
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
           if(isset($diagnosis_data) && !empty($diagnosis_data))
           {
              $i = 1;
              foreach($diagnosis_data as $diagnosisdata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="diagnosis[]" class="part_checkbox booked_checkbox" value="'.$diagnosisdata['diagnosis_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$diagnosisdata['diagnosis_value'].'</td>
                            <td>'.$diagnosisdata['teeth_name_diagnosis'].'</td>
                            <td>'.$diagnosisdata['get_teeth_number_val_diagnosis'].'</td>
                          
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Diagnosis are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }
     public  function treatment_list()
     {
          
            $post = $this->input->post();
            //print_r($post);die;
            $treatment_data = $this->session->userdata('treatment_data'); 
          if(isset($post) && !empty($post))
          {   
            $treatment_data = $this->session->userdata('treatment_data'); 
            if(isset($treatment_data) && !empty($treatment_data))
            {
              $treatmentdata = $treatment_data; 
            }
            else
            {
              $treatmentdata = [];
            }
           
          $treatmentdata[$post['treatment_id']] = array('treatment_id'=>$post['treatment_id'], 'teeth_name_treatment'=>$post['teeth_name_treatment'],'treatment_value'=>$post['treatment_value'],'get_teeth_number_val_treatment'=>$post['get_teeth_number_val_treatment']);
       // print_r($chief_complain_data);die;
         
          $this->session->set_userdata('treatment_data', $treatmentdata);
          $html_data = $this->treatment_template_list();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
     // print_r($post);die;
     // echo "hi";die;
     }
      private function treatment_template_list()
    {
      $treatment_data = $this->session->userdata('treatment_data');
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
                     <th>Treatment</th>
                    <th>Teeth Name</th>
                    <th>Tooth No.</th>
                  </tr></thead>';  
           if(isset($treatment_data) && !empty($treatment_data))
           {
              $i = 1;
              foreach($treatment_data as $treatmentdata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="treatment[]" class="part_checkbox booked_checkbox" value="'.$treatmentdata['treatment_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$treatmentdata['treatment_value'].'</td>
                            <td>'.$treatmentdata['teeth_name_treatment'].'</td>
                            <td>'.$treatmentdata['get_teeth_number_val_treatment'].'</td>
                          
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Treatments are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }
    public function remove_gynecology_patient_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_history_vals'=>explode(',', $post['patient_history_vals']));
        if(isset($data['patient_history_vals']) && !empty($data['patient_history_vals']))
        {
          $patient_history_data = $this->session->userdata('patient_history_data');
          $patient_history_id_list = array_column($patient_history_data, 'unique_id');
          print_r($patient_history_data);die;
          foreach($patient_history_data as $key=>$patient_history_ids)
          {
            if(in_array($patient_history_ids['unique_id'],$data['patient_history_vals']))
            {
               unset($patient_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_history_data',$patient_history_data);
          $html_data = $this->chief_complaint_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
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
          //print_r($previous_history_disease_data);die;
          $chief_complain_id_list = array_column($previous_history_disease_data, 'disease_id');
          foreach($previous_history_disease_data as $key=>$previous_history_disease_ids)
           {
              if(in_array($previous_history_disease_ids['disease_id'],$data['disease_name']))
              {
                 unset($previous_history_disease_data[$key]);
              }
           }  

      $this->session->set_userdata('previous_history_disease_data',$previous_history_disease_data);
      $html_data = $this->previous_history_disease_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}
 public function remove_dental_allergy()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         $data = array('allergy'=>explode(',', $post['allergy']));
        if(isset($data['allergy']) && !empty($data['allergy']))
       {
          $previous_allergy_data = $this->session->userdata('previous_allergy_data');
          $allergy_id_list = array_column($previous_allergy_data, 'allergy_id');
          foreach($previous_allergy_data as $key=>$previous_allergy_ids)
           {
              if(in_array($previous_allergy_ids['allergy_id'],$data['allergy']))
              {
                 unset($previous_allergy_data[$key]);
              }
           }  

      $this->session->set_userdata('previous_allergy_data',$previous_allergy_data);
      $html_data = $this->allergy_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}
public function remove_dental_oral_habit()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         $data = array('habit'=>explode(',', $post['habit']));
         //print_r($data);die;
        if(isset($data['habit']) && !empty($data['habit']))
       {
          $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data');
          //print_r($previous_oral_habit_data);die;
          $habit_id_list = array_column($previous_oral_habit_data, 'habit_id');
          //print_r($habit_id_list);die;
          foreach($previous_oral_habit_data as $key=>$previous_oral_habit_ids)
           {//print_r($previous_oral_habit_data);die;
              if(in_array($previous_oral_habit_ids['habit_id'],$data['habit']))
              {  //echo "hi";die;
                 unset($previous_oral_habit_data[$key]);
              }
           }  

      $this->session->set_userdata('previous_oral_habit_data',$previous_oral_habit_data);
      $html_data = $this->oral_habit_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}
public function remove_dental_advice()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         $data = array('advice'=>explode(',', $post['advice']));
         //print_r($data);die;
        if(isset($data['advice']) && !empty($data['advice']))
       {
          $previous_advice_data = $this->session->userdata('previous_advice_data');
          //print_r($previous_oral_habit_data);die;
          $hadvice_id_list = array_column($previous_advice_data, 'advice_id');
          //print_r($habit_id_list);die;
          foreach($previous_advice_data as $key=>$previous_advice_data_ids)
           {//print_r($previous_oral_habit_data);die;
              if(in_array($previous_advice_data_ids['advice_id'],$data['advice']))
              {  //echo "hi";die;
                 unset($previous_advice_data[$key]);
              }
           }  

      $this->session->set_userdata('previous_advice_data',$previous_advice_data);
      $html_data = $this->advice_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}
 public function remove_diagnosis()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         //print_r($post);die;
         $data = array('diagnosis'=>explode(',', $post['diagnosis']));
        if(isset($data['diagnosis']) && !empty($data['diagnosis']))
       {
          $diagnosis_data = $this->session->userdata('diagnosis_data');
          $diagnosis_id_list = array_column($diagnosis_data, 'diagnosis_id');
          foreach($diagnosis_data as $key=>$diagnosis_ids)
           {
              if(in_array($diagnosis_ids['diagnosis_id'],$data['diagnosis']))
              {
                 unset($diagnosis_data[$key]);
              }
           }  

      $this->session->set_userdata('diagnosis_data',$diagnosis_data);
      $html_data = $this->diagnosis_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}
 public function remove_treatment()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         //print_r($post);die;
         $data = array('treatment'=>explode(',', $post['treatment']));
        if(isset($data['treatment']) && !empty($data['treatment']))
       {
          $treatment_data = $this->session->userdata('treatment_data');
          $treatment_id_list = array_column($treatment_data, 'treatment_id');
          foreach($treatment_data as $key=>$treatment_ids)
           {
              if(in_array($treatment_ids['treatment_id'],$data['treatment']))
              {
                 unset($treatment_data[$key]);
              }
           }  

      $this->session->set_userdata('treatment_data',$treatment_data);
      $html_data = $this->treatment_template_list();
      $response_data = array('html_data'=>$html_data);
      $json = json_encode($response_data,true);
      echo $json;
       
    }
}
   public function add_perma()
    {
     // echo "hi";die;
         
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->patienttemplate->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('gynecology/patient_template/add_permanent_chart',$data);       
    }

     public function add_decidous()
    {
         
        $data['page_title'] = "Decidous Teeth";  
         $data['get_decidous']=$this->patienttemplate->get_decidous();
       $this->load->view('gynecology/patient_template/add_decious_chart',$data);       
    }
     public function add_perma_diagnosis()
    {
     // echo "hi";die;
         
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->patienttemplate->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('gynecology/patient_template/add_permanent_chart_diagnosis',$data);       
    }

     public function add_decidous_diagnosis()
    {
         
        $data['page_title'] = "Decidous Teeth";  
         $data['get_decidous']=$this->patienttemplate->get_decidous();
       $this->load->view('gynecology/patient_template/add_decious_chart_diagnosis',$data);       
    }
    public function add_perma_treatment()
    {
     // echo "hi";die;
         
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->patienttemplate->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('gynecology/patient_template/add_permanent_chart_treatment',$data);       
    }

     public function add_decidous_treatment()
    {
         
        $data['page_title'] = "Decidous Teeth";  
         $data['get_decidous']=$this->patienttemplate->get_decidous();
       $this->load->view('gynecology/patient_template/add_decious_chart_treatment',$data);       
    }
    public function add_perma_sub_category()
    {
      // echo "hi";die;
       $id=$this->uri->segment(4);
     // echo $id;die;
        $data['id'] = $id;
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->patienttemplate->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('gynecology/patient_template/add_permanent_chart_category',$data);       
    }

     public function add_decidous_sub_category()
    {
 
        $id=$this->uri->segment(4); 
        $data['id'] = $id;
        $data['page_title'] = "Decidous Teeth";  
        $data['get_decidous']=$this->patienttemplate->get_decidous();
       $this->load->view('gynecology/patient_template/add_decious_chart_category',$data);       
    }
}
