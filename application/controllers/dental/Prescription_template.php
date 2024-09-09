<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription_template extends CI_Controller {
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
    $this->load->model('dental/prescription_template/prescription_template_model','prescriptiontemplate');
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

        $data['page_title'] = 'Dental Prescription Template List'; 
        $this->load->view('dental/prescription_template/list',$data);
    }

    public function ajax_list()
    {   
       unauthorise_permission(295,1742);
        $this->session->unset_userdata('book_test');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->prescriptiontemplate->get_datatables();  
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
              $btn_edit = ' <a class="btn-custom" href="'.base_url("dental/prescription_template/edit/".$prescriptions->id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
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
                        "recordsTotal" => $this->prescriptiontemplate->count_all(),
                        "recordsFiltered" => $this->prescriptiontemplate->count_filtered(),
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
           $result = $this->prescriptiontemplate->delete($id);
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
            $result = $this->prescriptiontemplate->deleteall($post['row_id']);
            $response = "Dental Prescription Template Booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        //unauthorise_permission(247,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->prescriptiontemplate->get_by_id($id);  
        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('eye/prescription_template/view',$data);     
      }
    }  


   
  public function add()
  {
        unauthorise_permission(295,1743);
        $this->load->model('dental/general/dental_general_model','dental_general'); 
        $this->load->helper('dental'); 
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
        $data['chief_complaint_list'] = $this->prescriptiontemplate->dental_chief_complaint_list();
        $data['disease_list'] = $this->prescriptiontemplate->dental_disease_list();
        $data['allergy_list'] = $this->prescriptiontemplate->dental_allergy_list();
        $data['habit_list'] = $this->prescriptiontemplate->dental_habit_list();
        $data['advice_list'] = $this->prescriptiontemplate->dental_advice_list();
        $data['diagnosis_list'] = $this->prescriptiontemplate->dental_diagnosis_list();
        $data['treatment_list'] = $this->prescriptiontemplate->dental_treatment_list();
        $data['investigation_category_list'] = $this->prescriptiontemplate->dental_investigation_list();
   
        $data['blood_group_list'] =  $this->prescriptiontemplate->get_blood_group(); 
        //print_r($data['blood_group_list']);die;

       $data['prescription_tab_setting'] = get_dental_prescription_tab_setting();
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


              $this->prescriptiontemplate->save();
              $this->session->set_flashdata('success','Dental Prescription template successfully added.');
              redirect(base_url('dental/prescription_template'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }    
        }   

         $this->load->view('dental/prescription_template/add_template',$data);
    }

    public function edit($id="")
    {
      unauthorise_permission('295','1744');
 
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->prescriptiontemplate->get_by_id($id); 
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
        $data['medicine_template_data'] = $this->prescriptiontemplate->get_dental_medicine_prescription_template($id); 
       // print_r($data['medicine_template_data']);die;
        $data['chief_complaint_list'] = $this->prescriptiontemplate->dental_chief_complaint_list();
        $data['disease_list'] = $this->prescriptiontemplate->dental_disease_list();
        $data['allergy_list'] = $this->prescriptiontemplate->dental_allergy_list();
        $data['habit_list'] = $this->prescriptiontemplate->dental_habit_list();
        $data['advice_list'] = $this->prescriptiontemplate->dental_advice_list();
        $data['diagnosis_list'] = $this->prescriptiontemplate->dental_diagnosis_list();
        $data['treatment_list'] = $this->prescriptiontemplate->dental_treatment_list();
        $data['investigation_category_list'] = $this->prescriptiontemplate->dental_investigation_list();
        $chief_complaint_list = $this->prescriptiontemplate->get_chief_complaint_list($id);
        $chief_complain = $this->session->userdata('chief_complain_data');
        if(!isset($chief_complain))
        {
           $this->session->set_userdata('chief_complain_data',$chief_complaint_list);
        }
        $disease_list = $this->prescriptiontemplate->get_disease_list($id);
        //print_r($disease_list);die;
        $previous_disease = $this->session->userdata('previous_history_disease_data');
        //print_r($previous_disease);die;
        if(!isset($previous_disease))
        {  //echo "hi";die;
           $this->session->set_userdata('previous_history_disease_data',$disease_list);
        }
        $allergy_list = $this->prescriptiontemplate->get_allergy_list($id);
        $previous_allergy = $this->session->userdata('previous_allergy_data');
        if(!isset($previous_allergy))
        {
           $this->session->set_userdata('previous_allergy_data',$allergy_list);
        }
        $oral_habit_list = $this->prescriptiontemplate->get_oral_habit_list($id);
       //print_r($oral_habit_list);die;
        $previous_oral_habit = $this->session->userdata('previous_oral_habit_data');
        if(!isset($previous_oral_habit))
        {
           $this->session->set_userdata('previous_oral_habit_data',$oral_habit_list);
        }
        $diagnosis_list = $this->prescriptiontemplate->get_diagnosis_list($id);
       //print_r($diagnosis_list);die;
        $diagnosis_data_list = $this->session->userdata('diagnosis_data');
        //print_r($diagnosis_data_list);die;
        if(!isset($diagnosis_data_list))
        {
           $this->session->set_userdata('diagnosis_data',$diagnosis_list);
        }
        $treatment_list = $this->prescriptiontemplate->get_treatment_list($id);
       // print_r($treatment_list);die;
        $treatment_data = $this->session->userdata('treatment_data'); 
        if(!isset($treatment_data))
        {
           $this->session->set_userdata('treatment_data',$treatment_list);
        }
        $advice_list = $this->prescriptiontemplate->get_advice_list($id);
       // print_r($diagnosis_list);die;
         $previous_advice_data = $this->session->userdata('previous_advice_data');
        if(!isset($previous_advice_data))
        {
           $this->session->set_userdata('previous_advice_data',$advice_list);
        }
    /*    $data['investigation_template_data'] = $this->prescriptiontemplate->get_investigation_template_data($id); */

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
                $this->prescriptiontemplate->save();

                $this->session->set_flashdata('success','Dental Prescription template updated successfully.');
                redirect(base_url('dental/prescription_template'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
         $data['prescription_tab_setting'] = get_dental_prescription_tab_setting();
        $data['blood_group_list'] =  $this->prescriptiontemplate->get_blood_group();

       $data['prescription_medicine_tab_setting'] = get_dental_prescription_medicine_tab_setting();
       $this->load->view('dental/prescription_template/add_template',$data);       
      }
    }



   public function delete_pres_medicine($medicine_presc_id="",$template_id='')
   {
      if($medicine_presc_id>0)
      {
         $pres_temp_list = $this->prescriptiontemplate->delete_pres_medicine($medicine_presc_id,$template_id);
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
        $this->load->view('dental/prescription_template/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('295','1746');
        $this->load->model('dental/prescription_template/prescription_template_archive_model','prescription_template_archive'); 

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
       $this->load->model('dental/prescription_template/prescription_template_archive_model','prescription_template_archive'); 
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
        $this->load->model('dental/prescription_template/prescription_template_archive_model','prescription_template_archive'); 
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
       $this->load->model('dental/prescription_template/prescription_template_archive_model','prescription_template_archive');
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
        $this->load->model('dental/prescription_template/prescription_template_archive_model','prescription_template_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_template_archive->trashall($post['row_id']);
            $response = "Dental Prescription Template successfully deleted parmanently.";
            echo $response;
        }
    }



   
   function get_dental_medicine_auto_vals($vals="")
    {
         //echo "hi";die;
        if(!empty($vals))
        {
            $result = $this->prescriptiontemplate->get_dental_medicine_auto_vals($vals);  
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
            $result = $this->prescriptiontemplate->get_dental_dosage_vals($vals);  
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
            $result = $this->prescriptiontemplate->get_dental_duration_vals($vals);  
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
            $result = $this->prescriptiontemplate->get_dental_frequency_vals($vals);  
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
            $result = $this->prescriptiontemplate->get_dental_advice_vals($vals);  
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
        $diseases_data = $this->prescriptiontemplate->get_eye_diseases_data($branch_id);
        echo $diseases_data;
      }
    } 

    function get_test_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->prescriptiontemplate->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    } 
   public  function chief_complain_list()
     {
          
            $post = $this->input->post();
            $chief_complain_data = $this->session->userdata('chief_complain_data'); 
          if(isset($post) && !empty($post))
          {   
            $chief_complain_data = $this->session->userdata('chief_complain_data'); 
            if(isset($chief_complain_data) && !empty($chief_complain_data))
            {
              $chief_complain = $chief_complain_data; 
              $tot = count($chief_complain_data);
            }
            else
            {
              $chief_complain = [];
            }
           
          $chief_complain[$tot+1] = array('chief_id'=>$tot+1,'chief_complaint_id'=>$post['chief_complaint_id'], 'teeth_name'=>$post['teeth_name'], 'reason'=>$post['reason'],'number'=>$post['number'],'time'=>$post['time'],'chief_complaint_value'=>$post['chief_complaint_value'],'get_teeth_number_val'=>$post['get_teeth_number_val']);
       // print_r($chief_complain_data);die;
         
          $this->session->set_userdata('chief_complain_data', $chief_complain);
          $html_data = $this->chief_complaint_template_list();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
     // print_r($post);die;
     // echo "hi";die;
     }
      private function chief_complaint_template_list()
    {
      $chief_complain_data = $this->session->userdata('chief_complain_data');
      //echo "<pre>";print_r($chief_complain_data);die;
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
           if(isset($chief_complain_data) && !empty($chief_complain_data))
           {
              $i = 1;
              foreach($chief_complain_data as $chiefcomplaindata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="chief_complaint[]" class="part_checkbox booked_checkbox" value="'.$chiefcomplaindata['chief_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$chiefcomplaindata['chief_complaint_value'].'</td>
                            <td>'.$chiefcomplaindata['teeth_name'].'</td>
                            <td>'.$chiefcomplaindata['get_teeth_number_val'].'</td>
                            <td>'.$chiefcomplaindata['reason'].'</td>
                             <td>'.$chiefcomplaindata['number'].'  '.$chiefcomplaindata['time'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Chief Complaints are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function disease_list()
    {
      //echo "hi";die;
          $post = $this->input->post();
          //print_r($post);die;
          $previous_history_disease_data = $this->session->userdata('previous_history_disease_data'); 
          if(isset($post) && !empty($post))
          {   
            $previous_history_disease_data = $this->session->userdata('previous_history_disease_data'); 
            if(isset($previous_history_disease_data) && !empty($previous_history_disease_data))
            {
              $previous_history_disease = $previous_history_disease_data; 
            }
            else
            {
              $previous_history_disease = [];
            }
           
          $previous_history_disease[$post['disease_id']] = array('disease_id'=>$post['disease_id'], 'disease_value'=>$post['disease_value'], 'disease_details'=>$post['disease_details'],'operation'=>$post['operation'],'operation_date'=>$post['operation_date']);
        //print_r($previous_history_disease);die;
        
          $this->session->set_userdata('previous_history_disease_data', $previous_history_disease);
         // print_r($previous_history_disease_data);die;
          $html_data = $this->previous_history_disease_template_list();
          //print_r($html_data);die;
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
    }
  }

   private function previous_history_disease_template_list()
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
              foreach($previous_history_disease_data as $previoushistorydisease)
              {
                                         $date_dis='';
  if((!empty($previoushistorydisease['operation_date'])) && ($previoushistorydisease['operation_date']!='0000-00-00') &&($previoushistorydisease['operation_date']!='1970-01-01'))

  {
    $date_dis=date('d-m-Y', strtotime($previoushistorydisease['operation_date']));

  }
  else
  {
    $date_dis='';
  }

                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="disease_name[]" class="part_checkbox booked_checkbox" value="'.$previoushistorydisease['disease_id'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$previoushistorydisease['disease_value'].'</td>
                            <td>'.$previoushistorydisease['disease_details'].'</td>
                            <td>'.$previoushistorydisease['operation'].'</td>
                              <td>'.$date_dis.'</td>
                            
                            
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
    public function remove_dental_chief()
    {    
         //echo "hi";die;
         $post =  $this->input->post();
         $data = array('chief_complaint'=>explode(',', $post['chief_complaint']));
        if(isset($data['chief_complaint']) && !empty($data['chief_complaint']))
       {
          $chief_complain_data = $this->session->userdata('chief_complain_data');
          $chief_complain_id_list = array_column($chief_complain_data, 'chief_complaint_id');
          foreach($chief_complain_data as $key=>$chief_complain_ids)
           {
              /*if(in_array($chief_complain_ids['chief_complaint_id'],$data['chief_complaint']))
              {
                 unset($chief_complain_data[$key]);
              }*/
              
              if(in_array($chief_complain_ids['chief_id'],$data['chief_complaint']))
              {
                // unset($chief_complain_data_list[$key]);
                unset($chief_complain_data_list[$chief_complain_ids['chief_id']]);
              }
              
           }  

      $this->session->set_userdata('chief_complain_data',$chief_complain_data);
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
        $data['get_permanent']=$this->prescriptiontemplate->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('dental/prescription_template/add_permanent_chart',$data);       
    }

     public function add_decidous()
    {
         
        $data['page_title'] = "Decidous Teeth";  
         $data['get_decidous']=$this->prescriptiontemplate->get_decidous();
       $this->load->view('dental/prescription_template/add_decious_chart',$data);       
    }
     public function add_perma_diagnosis()
    {
     // echo "hi";die;
         
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->prescriptiontemplate->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('dental/prescription_template/add_permanent_chart_diagnosis',$data);       
    }

     public function add_decidous_diagnosis()
    {
         
        $data['page_title'] = "Decidous Teeth";  
         $data['get_decidous']=$this->prescriptiontemplate->get_decidous();
       $this->load->view('dental/prescription_template/add_decious_chart_diagnosis',$data);       
    }
    public function add_perma_treatment()
    {
     // echo "hi";die;
         
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->prescriptiontemplate->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('dental/prescription_template/add_permanent_chart_treatment',$data);       
    }

     public function add_decidous_treatment()
    {
         
        $data['page_title'] = "Decidous Teeth";  
         $data['get_decidous']=$this->prescriptiontemplate->get_decidous();
       $this->load->view('dental/prescription_template/add_decious_chart_treatment',$data);       
    }
    public function add_perma_sub_category()
    {
      // echo "hi";die;
       $id=$this->uri->segment(4);
     // echo $id;die;
        $data['id'] = $id;
        $data['page_title'] = "Permanent Teeth";  
        $data['get_permanent']=$this->prescriptiontemplate->get_permanent();
        //print_r($data['get_permanent']);
   
       $this->load->view('dental/prescription_template/add_permanent_chart_category',$data);       
    }

     public function add_decidous_sub_category()
    {
 
        $id=$this->uri->segment(4); 
        $data['id'] = $id;
        $data['page_title'] = "Decidous Teeth";  
        $data['get_decidous']=$this->prescriptiontemplate->get_decidous();
       $this->load->view('dental/prescription_template/add_decious_chart_category',$data);       
    }
}
