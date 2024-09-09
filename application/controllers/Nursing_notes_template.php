<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nursing_notes_template extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();  
        auth_users();  
        $this->load->model('nursing_notes_template/prescription_template_model','prescriptiontemplate');
        $this->load->library('form_validation');
    }

    
      public function index()
    {
        
        unauthorise_permission(81,516);
        $data['page_title'] = 'Nursing Note List'; 
        $this->load->view('nursing_notes_template/list',$data);
    }

    public function ajax_list()
    {   
        unauthorise_permission(81,516);
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
            //$row[] = date('d-M-Y H:i A',strtotime($prescriptions->created_date)); 
            
            //Action button /////
            $btn_confirm="";
            $btn_edit = ""; 
            $btn_delete = ""; 
              
            if(in_array('518',$users_data['permission']['action'])) 
            {
              $btn_edit = ' <a class="btn-custom" href="'.base_url("nursing_notes_template/edit/".$prescriptions->id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if(in_array('519',$users_data['permission']['action'])) 
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
        unauthorise_permission(81,519);
       if(!empty($id) && $id>0)
       {
           $result = $this->prescriptiontemplate->delete($id);
           $response = "Nursing Note successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(81,519);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescriptiontemplate->deleteall($post['row_id']);
            $response = "Nursing Note Booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(81,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->prescriptiontemplate->get_by_id($id);  
        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('nursing_notes_template/view',$data);     
      }
    }  


   
    public function add()
    {
        unauthorise_permission(81,517);
            $this->load->model('general/general_model'); 
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
        $this->load->model('patient/patient_model');

        //$data['country_list'] = $this->general_model->country_list();
        //$data['simulation_list'] = $this->general_model->simulation_list();
        //$data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        //$data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        //$data['employee_list'] = $this->opd->employee_list();
        //$data['profile_list'] = $this->opd->profile_list();
        //$data['dept_list'] = $this->general_model->department_list(); 
        $data['chief_complaints'] = $this->general_model->nursing_chief_complaint_list(); 
        $data['examination_list'] = $this->opd->nursing_examinations_list();
        $data['diagnosis_list'] = $this->opd->nursing_diagnosis_list();  
        $data['suggetion_list'] = $this->opd->nursing_suggetion_list();  
        $data['prv_history'] = $this->opd->nursing_prv_history_list();  
        $data['personal_history'] = $this->opd->nursing_personal_history_list();  
        $data['prescription_tab_setting'] = get_ipd_nursing_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_ipd_nursing_prescription_medicine_tab_setting();
        $data['page_title'] = "Add Nursing Note";
                
        $post = $this->input->post();
       
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
                                  'chief_complaints'=>'',
                                  'examination'=>'',
                                  'diagnosis'=>'',
                                  'suggestion'=>'',
                                  'remark'=>'',
                                  'next_appointment_date'=>"",
                                  'appointment_date'=>"",
                                  );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
              $this->prescriptiontemplate->save();
              $this->session->set_flashdata('success','Nursing Note successfully added.');
              redirect(base_url('nursing_notes_template'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }    
        }   

         $this->load->view('nursing_notes_template/add_template',$data);
    }

    public function edit($id="")
    {
      unauthorise_permission('81','518');
      $data['prescription_tab_setting'] = get_prescription_tab_setting();
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->prescriptiontemplate->get_by_id($id); 
        
        $data['prescription_template_data'] = $this->prescriptiontemplate->get_opd_prescription_template($id); 
        $data['test_template_data'] = $this->prescriptiontemplate->get_opd_test_template($id); 

        $data['page_title'] = "Update Nursing Note";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'template_name'=>$result['template_name'], 
                                  /*'patient_bp'=>$result['patient_bp'],
                                  'patient_temp'=>$result['patient_temp'],
                                  'patient_weight'=>$result['patient_weight'],
                                  'patient_height'=>$result['patient_height'],
                                  'patient_spo2'=>$result['patient_spo2'],*/
                                  'prv_history'=>$result['prv_history'],
                                  'personal_history'=>$result['personal_history'],
                                  'chief_complaints'=>$result['chief_complaints'],
                                  'examination'=>$result['examination'],
                                  'diagnosis'=>$result['diagnosis'],
                                  'suggestion'=>$result['suggestion'],
                                  'remark'=>$result['remark'],
                                  'next_appointment_date'=>date('d-m-Y',strtotime($result['appointment_date'])),
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->prescriptiontemplate->save();
                $this->session->set_flashdata('success','Nursing Note updated successfully.');
                redirect(base_url('nursing_notes_template'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
         $this->load->model('opd/opd_model','opd');
        $this->load->model('general/general_model');
        $data['chief_complaints'] = $this->general_model->nursing_chief_complaint_list(); 
        $data['examination_list'] = $this->opd->nursing_examinations_list();
        $data['diagnosis_list'] = $this->opd->nursing_diagnosis_list();  
        $data['suggetion_list'] = $this->opd->nursing_suggetion_list();  
        $data['prv_history'] = $this->opd->nursing_prv_history_list();  
        $data['personal_history'] = $this->opd->nursing_personal_history_list();  
        $data['prescription_tab_setting'] = get_ipd_nursing_prescription_tab_setting(); 
        $data['prescription_medicine_tab_setting'] = get_ipd_nursing_prescription_medicine_tab_setting();
       $this->load->view('nursing_notes_template/add_template',$data);       
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
        
        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'template_name'=>$post['template_name'],
                                        'next_appointment_date'=>$post['next_appointment_date'],
                                        'suggestion'=>$post['suggestion'],
                                        'chief_complaints'=>$post['chief_complaints'],
                                        'examination'=>$post['examination'],
                                        'personal_history'=>$post['personal_history'],
                                        'medicine_frequency'=>$post['medicine_frequency'],
                                        'medicine_advice'=>$post['medicine_advice'],
                                        'prv_history'=>$post['prv_history'],
                                        'diagnosis'=>$post['diagnosis'],
                                        'remark'=>$post['remark'],

                                       ); 
            return $data['form_data'];
        }   
    }


    
    
    public function archive()
    {
        unauthorise_permission('81','543');
        $data['page_title'] = 'Nursing Note Archive List';
        $this->load->helper('url');
        $this->load->view('nursing_notes_template/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('81','543');
        $this->load->model('nursing_notes_template/prescription_template_archive_model','prescription_template_archive'); 

        $list = $this->prescription_template_archive->get_datatables();  
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
            //$row[] = date('d-M-Y H:i A',strtotime($prescriptiont->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('522',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_prescription_template('.$prescriptiont->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('544',$users_data['permission']['action'])){
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
       unauthorise_permission('81','522');
       $this->load->model('nursing_notes_template/prescription_template_archive_model','prescription_template_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_template_archive->restore($id);
           $response = "Nursing Note successfully restore in list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('81','522');
        $this->load->model('nursing_notes_template/prescription_template_archive_model','prescription_template_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_template_archive->restoreall($post['row_id']);
            $response = "Nursing Note successfully restore in list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('81','544');
       $this->load->model('nursing_notes_template/prescription_template_archive_model','prescription_template_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_template_archive->trash($id);
           $response = "Nursing Note successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('81','544');
         $this->load->model('nursing_notes_template/prescription_template_archive_model','prescription_template_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_template_archive->trashall($post['row_id']);
            $response = "Nursing Note successfully deleted parmanently.";
            echo $response;
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


}
