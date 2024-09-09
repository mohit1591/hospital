<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_advance_discharge_summary_template extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users(); 
        $this->load->model('ipd_advnace_discharge_summary_template/ipd_advance_discharge_summary_template_model','ipd_discharge_summary');
        $this->load->library('form_validation');
    }

    public function index()
    {  
        unauthorise_permission('111','684');
        $data['page_title'] = ' Advance Discharge Summary Template List'; 
        $this->load->view('ipd_advance_discharge_summary_template/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('111','684');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->ipd_discharge_summary->get_datatables();
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_discharge_summary) {
         // print_r($ipd_discharge_summary);die;
            $no++;
            $row = array();
            if($ipd_discharge_summary->status==1)
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
            $checkboxs = "";
            if($users_data['parent_id']==$ipd_discharge_summary->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_discharge_summary->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ipd_discharge_summary->name; 
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_discharge_summary->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_discharge_summary->branch_id)
            {
              if(in_array('686',$users_data['permission']['action']))
              {
               $edit_url = base_url('ipd_advance_discharge_summary_template/edit/').$ipd_discharge_summary->id; 
               $btnedit =' <a  class="btn-custom" href="'.$edit_url.'" style="'.$ipd_discharge_summary->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('687',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_advance_discharge_summary_template('.$ipd_discharge_summary->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_discharge_summary->count_all(),
                        "recordsFiltered" => $this->ipd_discharge_summary->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    { 
        unauthorise_permission('124','750');
        $data['page_title'] = "Add IPD Discharge Summary";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"",  
                                  'name'=>"",
                                  'summery_type'=>"",
                                  'chief_complaints'=>"",
                                  'h_o_presenting'=>"",
                                  'on_examination'=>"",
                                  'vitals_pulse'=>"",
                                  'vitals_chest'=>"",
                                  'vitals_bp'=>"",
                                  'vitals_cvs'=>"",
                                  'vitals_temp'=>"",
                                  'vitals_p_a'=>"",
                                  'vitals_cns'=>"",

                                  'vitals_r_r'=>'',
                                  'vitals_saturation'=>'',
                                  'vitals_pupils'=>'',
                                  'vitals_pallor'=>'',
                                  'vitals_icterus'=>'',
                                  'vitals_cyanosis'=>'',
                                  'vitals_clubbing'=>'',
                                  'vitals_edema'=>'',
                                  'vitals_lymphadenopathy'=>'',

                                  'provisional_diagnosis'=>"",
                                  'final_diagnosis'=>"",
                                  'course_in_hospital'=>"",
                                  'investigations'=>"",
                                  'discharge_time_condition'=>"",
                                  'discharge_advice'=>"",
                                  'review_time_date'=>date('d-m-Y'),
                                  'review_time'=>date('H:i:s'),
                                   'status'=>"1",
                                   'remarks'=>get_setting_value("IPD_DISCHARGE_REMARKS"),
                              );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save();
                //echo '<pre>'; print_r($post);die;
                $this->session->set_userdata('discharge_summary_id',$discharge_summary_id);
                $this->session->set_flashdata('success','IPD Advance Discharge Summery Tamplate Created successfully.');
                redirect(base_url('ipd_advance_discharge_summary_template'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                
            }     
        }
      $this->load->model('general/general_model');
     // $data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
      $data['simulation_list'] = $this->general_model->simulation_list();   
      $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      //echo "<pre>"; print_r($discharge_labels_setting_list); exit;
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      
      $orderby = "order_by";
     
      $data['test_report_data'] = [];
      $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
      $sortArray = array();
      foreach($arr as $value)
      { 
          foreach($value as $key=>$value)
          {
              if(!isset($sortArray[$key])){
                  $sortArray[$key] = array();
              }
              $sortArray[$key][] = $value;
          }
      }
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      //echo "<pre>"; print_r($data['discharge_labels_setting_list']); exit;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);
      //$data['discharge_field_master_list'] = $this->ipd_discharge_summary->discharge_field_master_list(1,1);
      //print_r($data['discharge_field_master_list']); exit;
      $this->load->model('ipd_patient_discharge_summary/ipd_patient_advance_discharge_summary_model');
      $get_payment_detail= $this->ipd_patient_advance_discharge_summary_model->discharge_master_detail_by_field();
      //print_r($get_payment_detail); exit; 
      /*$total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;*/



      //$data['template_list'] = $this->ipd_discharge_summary->template_list();

      //$ipd_test_data = $this->ipd_discharge_summary->get_ipd_pathology_test($ipd_id);

      //$booking_id = $ipd_test_data['id'];
      
      //$data['path_booking_id'] =$booking_id;

      ///
 
      ////
      $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting(); 
      $this->load->view('ipd_advance_discharge_summary_template/add',$data);
    }
    

    public function edit($id="")
    {
      unauthorise_permission('111','686');
      if(isset($id) && !empty($id) && is_numeric($id))
      {

        $result = $this->ipd_discharge_summary->get_by_id($id);  

        $data['page_title'] = "Update IPD Advance Discharge Summary Template";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'], 
                                  'name'=>$result['name'],
                                  'summery_type'=>$result['summery_type'],
                                  'chief_complaints'=>$result['chief_complaints'],
                                  'h_o_presenting'=>$result['h_o_presenting'],
                                  'on_examination'=>$result['on_examination'],
                                  'vitals_pulse'=>$result['vitals_pulse'],
                                  'vitals_chest'=>$result['vitals_chest'],
                                  'vitals_bp'=>$result['vitals_bp'],
                                  'vitals_cvs'=>$result['vitals_cvs'],
                                  'vitals_temp'=>$result['vitals_temp'],
                                  'vitals_p_a'=>$result['vitals_p_a'],
                                  'vitals_cns'=>$result['vitals_cns'],
                                  'provisional_diagnosis'=>$result['provisional_diagnosis'],
                                  'final_diagnosis'=>$result['final_diagnosis'],
                                  'course_in_hospital'=>$result['course_in_hospital'],
                                  'investigations'=>$result['investigations'],
                                  'discharge_time_condition'=>$result['discharge_time_condition'],
                                  'discharge_advice'=>$result['discharge_advice'],
                                  
                                  'treatment_given'=>$result['treatment_given'],
                                  'operation_notes'=>$result['operation_notes'],
                                  'surgery_preferred'=>$result['surgery_preferred'],
                                  'past_history'=>$result['past_history'],
                                  'menstrual_history'=>$result['menstrual_history'],
                                  'obstetric_history'=>$result['obstetric_history'],

                                  'diagnosis'=>$result['diagnosis'],
                                  'vitals_r_r'=>$result['vitals_r_r'],
                                  'vitals_saturation'=>$result['vitals_saturation'],
                                  'vitals_pupils'=>$result['vitals_pupils'],
                                  'vitals_pallor'=>$result['vitals_pallor'],
                                  'vitals_icterus'=>$result['vitals_icterus'],
                                  'vitals_cyanosis'=>$result['vitals_cyanosis'],
                                  'vitals_clubbing'=>$result['vitals_clubbing'],
                                  'vitals_edema'=>$result['vitals_edema'],
                                  'vitals_lymphadenopathy'=>$result['vitals_lymphadenopathy'],
                                  'family_history'=>$result['family_history'],
                                  'birth_history'=>$result['birth_history'],
                                  'general_history'=>$result['general_history'],
                                  'systemic_examination'=>$result['systemic_examination'],
                                  'local_examination'=>$result['local_examination'],
                                  'specific_findings'=>$result['specific_findings'],
  
                                  'status'=>$result['status'],
                                  'remarks'=>$result['remarks'],
                                  
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save();
                //echo $discharge_summary_id; exit;
                $this->session->set_userdata('discharge_summary_id',$discharge_summary_id);
                $this->session->set_flashdata('success','IPD Advance Discharge summary Template updated successfully.');
                redirect(base_url('ipd_advance_discharge_summary_template'));
                //echo 1;
                //return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                //print_r(validation_errors()); die;
            }     
        }
        $this->load->model('general/general_model');  
        $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
       
        $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
        $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
        $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
      $sortArray = array();
      foreach($arr as $value){ //print_r($value);
          foreach($value as $key=>$value)
          {
              if(!isset($sortArray[$key])){
                  $sortArray[$key] = array();
              }
              $sortArray[$key][] = $value;
          }
      }
      $orderby = "order_by";
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);         

      //$data['discharge_field_master_list'] = $this->ipd_discharge_summary->discharge_field_master_list(1,1);
      /////
      //$get_payment_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field($id);
      //print_r($get_payment_detail); exit;
      /*$total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;
      */
      $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
       
       /********** Medicine of discharge summary ************/
       $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
       //$data['prescription_test_list']=$result['id'];
       $data['prescription_presc_list']= $this->ipd_discharge_summary->get_discharge_medicine($id,$result['ipd_id']);
       //echo '<pre>'; print_r($data['prescription_presc_list']);die;
       /********** Medicine of discharge summary ************/

       /********** Investigation  of discharge summary ************/
       $data['test_list']=$result['id'];
      // $data['discharge_test_list']= $this->ipd_discharge_summary->get_discharge_test($id);
       /********** Medicine of discharge summary ************/
       
      //print_r($total_values); exit;
      /////
 
      //end test booking list
      $this->load->view('ipd_advance_discharge_summary_template/add',$data);
      }
      
    }
     
    private function _validate()
    {
        $post = $this->input->post();

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
            $summary_type='';
            if(!empty($post['summary_type']))
            {
                $summary_type = $post['summary_type'];
            }
            $chief_complaints='';
            if(!empty($post['chief_complaints']))
            {
                $chief_complaints = $post['chief_complaints'];
            }
            $h_o_presenting='';
            if(!empty($post['h_o_presenting']))
            {
                $h_o_presenting = $post['h_o_presenting'];
            }
            $on_examination='';
            if(!empty($post['on_examination']))
            {
                $on_examination = $post['on_examination'];
            }
            $vitals_pulse='';
            if(!empty($post['vitals_pulse']))
            {
                $vitals_pulse = $post['vitals_pulse'];
            }
            $vitals_chest='';
            if(!empty($post['vitals_chest']))
            {
                $vitals_chest = $post['vitals_chest'];
            }
            $vitals_bp='';
            if(!empty($post['vitals_bp']))
            {
                $vitals_bp = $post['vitals_bp'];
            }
            $vitals_cvs='';
            if(!empty($post['vitals_cvs']))
            {
                $vitals_cvs = $post['vitals_cvs'];
            }
            $vitals_temp='';
            if(!empty($post['vitals_temp']))
            {
                $vitals_temp = $post['vitals_temp'];
            }

            $vitals_p_a='';
            if(!empty($post['vitals_p_a']))
            {
                $vitals_p_a = $post['vitals_p_a'];
            }

            $vitals_cns='';
            if(!empty($post['vitals_cns']))
            {
                $vitals_cns = $post['vitals_cns'];
            }

            $provisional_diagnosis='';
            if(!empty($post['provisional_diagnosis']))
            {
                $provisional_diagnosis = $post['provisional_diagnosis'];
            }

            $final_diagnosis='';
            if(!empty($post['final_diagnosis']))
            {
                $final_diagnosis = $post['final_diagnosis'];
            }

            $course_in_hospital='';
            if(!empty($post['course_in_hospital']))
            {
                $course_in_hospital = $post['course_in_hospital'];
            }

            $investigations='';
            if(!empty($post['investigations']))
            {
                $investigations = $post['investigations'];
            }
            $discharge_time_condition='';
            if(!empty($post['discharge_time_condition']))
            {
                $discharge_time_condition = $post['discharge_time_condition'];
            }

            $discharge_advice='';
            if(!empty($post['discharge_advice']))
            {
                $discharge_advice = $post['discharge_advice'];
            }

            $review_time_date='';
            if(!empty($post['review_time_date']))
            {
                $review_time_date = $post['review_time_date'];
            }

            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'name'=>$post['name'],
                                        'summary_type'=>$summary_type,
                                        'chief_complaints'=>$chief_complaints,
                                        'h_o_presenting'=>$h_o_presenting,
                                        'on_examination'=>$on_examination,
                                        'vitals_pulse'=>$vitals_pulse,
                                        'vitals_chest'=>$vitals_chest,
                                        'vitals_bp'=>$vitals_bp,
                                        'vitals_cvs'=>$vitals_cvs,
                                        'vitals_temp'=>$vitals_temp,
                                        'vitals_p_a'=>$vitals_p_a,
                                        'vitals_cns'=>$vitals_cns,
                                        'provisional_diagnosis'=>$provisional_diagnosis,
                                        'final_diagnosis'=>$final_diagnosis,
                                        'course_in_hospital'=>$course_in_hospital,
                                        'investigations'=>$investigations,
                                   'discharge_time_condition'=>$discharge_time_condition,
                                        'discharge_advice'=>$discharge_advice,
                                        'review_time_date'=>$review_time_date, 
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
      unauthorise_permission('111','687');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_discharge_summary->delete($id);
           $response = "IPD Advance Discharge Summary Template successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('111','687');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary->deleteall($post['row_id']);
            $response = "IPD Advance Discharge Summary Template successfully deleted.";
            echo $response;
        }
    }

    /*public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_discharge_summary->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_discharge_summary']." detail";
        $this->load->view('ipd_discharge_summary/view',$data);     
      }
    }*/  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('111','688');
        $data['page_title'] = 'IPD Advance Discharge Summary Template Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_advance_discharge_summary_template/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('111','688');
      $this->load->model('ipd_advnace_discharge_summary_template/ipd_advance_discharge_summary_template_archive_model','ipd_discharge_summary_archive');
      $list = $this->ipd_discharge_summary_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_discharge_summary) {
         // print_r($ipd_discharge_summary);die;
            $no++;
            $row = array();
            if($ipd_discharge_summary->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_discharge_summary->id.'">'.$check_script; 
             $row[] = $ipd_discharge_summary->name; 
             
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_discharge_summary->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('690',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_advance_discharge_summary_template('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('689',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_discharge_summary_archive->count_all(),
                        "recordsFiltered" => $this->ipd_discharge_summary_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('111','690');
        $this->load->model('ipd_advnace_discharge_summary_template/ipd_advance_discharge_summary_template_archive_model','ipd_discharge_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_summary_archive->restore($id);
           $response = "IPD Advance Discharge Summary Template successfully restore in IPD Advance Discharge Summary Template List.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('111','690');
        $this->load->model('ipd_advnace_discharge_summary_template/ipd_advance_discharge_summary_template_archive_model','ipd_discharge_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary_archive->restoreall($post['row_id']);
            $response = "IPD Advance Discharge Summary Template successfully restore in IPD Advance Discharge Summary Template List.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('111','689');
        $this->load->model('ipd_advnace_discharge_summary_template/ipd_advance_discharge_summary_template_archive_model','ipd_discharge_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_summary_archive->trash($id);
           $response = "IPD Advance Discharge Summary Template successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('111','689');
        $this->load->model('ipd_advnace_discharge_summary_template/ipd_advance_discharge_summary_template_archive_model','ipd_discharge_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary_archive->trashall($post['row_id']);
            $response = "IPD Advance Discharge Summary Template successfully deleted parmanently.";
            echo $response;
        }
    } 

}
?>