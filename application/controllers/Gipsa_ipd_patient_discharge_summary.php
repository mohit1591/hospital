<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gipsa_ipd_patient_discharge_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('gipsa_ipd_patient_discharge_summary/ipd_patient_discharge_summary_model','ipd_discharge_summary');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('124','749');
        $data['page_title'] = 'IPD Patient Discharge Summary List'; 
        $this->load->model('general/general_model');
        $get_setting=get_setting_value('IPD_Discharge_Summary_Medicine');
        //echo $get_setting;die();
        $data['medicine_setting']=$get_setting;
        $this->load->view('gipsa_ipd_patient_discharge_summary/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('124','749');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');

        $list = $this->ipd_discharge_summary->get_datatables(); 
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);

        $this->load->model('general/general_model');
        $get_setting=get_setting_value('IPD_Discharge_Summary_Medicine');
        
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
            $row[] = $ipd_discharge_summary->ipd_no;
            $row[] = $ipd_discharge_summary->patient_code;
            $row[] = $ipd_discharge_summary->patient_name;
            $row[] = $ipd_discharge_summary->mobile_no;

             
            $row[] = $status;
            $row[] = date('d-M-Y h:i A',strtotime($ipd_discharge_summary->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_discharge_summary->branch_id)
            {
              if(in_array('751',$users_data['permission']['action'])){
               $btnedit =' <a  href="'.base_url("gipsa_ipd_patient_discharge_summary/edit/".$ipd_discharge_summary->id).'" class="btn-custom" style="'.$ipd_discharge_summary->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('752',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_discharge_summary('.$ipd_discharge_summary->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
            $btnmedic ='';
             if(in_array('756',$users_data['permission']['action']) && $get_setting==0)
             {
             $btnmedic =' <a  href="'.base_url("gipsa_ipd_patient_discharge_summary/add_medicine/".$ipd_discharge_summary->id).'" class="btn-custom" style="'.$ipd_discharge_summary->id.'" title="Add Medicine"><i class="fa fa-pencil" aria-hidden="true"></i> Add Medicine</a>';
             }
            $btnmedicine_view ='';
            if(in_array('757',$users_data['permission']['action']) && $get_setting==0)
             {
             $btnmedicine_view =' <a  href="'.base_url("gipsa_ipd_patient_discharge_summary/view_medicine/".$ipd_discharge_summary->id).'" class="btn-custom" style="'.$ipd_discharge_summary->id.'" title="Add Medicine"><i class="fa fa-pencil" aria-hidden="true"></i> View Medicine</a>';
             }

             $print_pdf_url = "'".base_url('gipsa_ipd_patient_discharge_summary/print_discharge_summary/'.$ipd_discharge_summary->id)."'";
             // <a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>
               //$btn_print = '<a id="ipd_summary_print" class="btn-custom" href="javascript:void(0);"> Print </a>';


               $btn_print = ' <a onClick="return print_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_discharge_summary->id.'" title="Print"><i class="fa fa-print"></i> Print</a>';

            $btn_medicine_print='';
            if(in_array('758',$users_data['permission']['action']))
            {  
              $print_medicine_url = "'".base_url('gipsa_ipd_patient_discharge_summary/print_discharge_summary_medicine/'.$ipd_discharge_summary->id)."'";
             $btn_medicine_print = '<a href="javascript:void(0)" class="btn-custom" onClick="return print_window_page('.$print_medicine_url.');"> <i class="fa fa-print"></i> Print Medicine</a>';
            }
             $row[] = $btnedit.$btndelete.$btn_print.$btnmedic.$btnmedicine_view.$btn_medicine_print;
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
    
    
    public function add($ipd_id="",$patient_id="")
    {
        unauthorise_permission('124','750');
        $data['page_title'] = "Add IPD Discharge Summary";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'ipd_id'=>$ipd_id, 
                                  'patient_id'=>$patient_id, 
                                  'summery_type'=>"",
                                  'chief_complaints'=>"",
                                  'h_o_presenting'=>"",
                                  'h_o_past'=>"",
                                  'mlc_fir_no'=>"",
                                  'ho_any_allergy'=>"",
                                  'on_examination'=>"",
                                  'vitals_pulse'=>"",
                                  'vitals_chest'=>"",
                                  'vitals_bp'=>"",
                                  'vitals_cvs'=>"",
                                  'vitals_temp'=>"",
                                  'vitals_p_a'=>"",
                                  'vitals_cns'=>"",
                                  'medicine_administered'=>"",
                                  'medicine_prescribed'=>"",
                                  'lama_dama_reasons'=>"",
                                  'refered_data'=>"",
                                  'icd_code'=>"",
                                  'admission_reason'=>"",
                                  'family_history'=>"",
                                  'alcohol_history'=>"",
                                  'procedure_performedand'=>"",
                                  'nutritional_advice'=>"",
                                  'consultants_name'=>"",
                                  'provisional_diagnosis'=>"",
                                  'final_diagnosis'=>"",
                                  'course_in_hospital'=>"",
                                  'investigations'=>"",
                                  'discharge_time_condition'=>"",
                                  'discharge_advice'=>"",
                                  'review_time_date'=>date('d-m-Y'),
                                  'review_time'=>date('H:i:s'),
                                  'date_of_pro'=>date('d-m-Y'),
                                  'time_of_pro'=>date('H:i:s'),
                                  'medicine_name'=>"",
                                  'medicine_dose'=>"",
                                  'medicine_duration'=>"",
                                  'medicine_frrequency'=>"",
                                  'medicine_advice'=>"",
                                  'test_name'=>"",
                                  'test_date'=>"",
                                  'result'=>"",
                                   'status'=>"1",
                              );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save();
                
                $this->session->set_userdata('discharge_summary_id',$discharge_summary_id);
                $this->session->set_flashdata('success','Discharge summary_id updated successfully.');
                redirect(base_url('gipsa_ipd_patient_discharge_summary/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                
            }     
        }
      $this->load->model('general/general_model');
      $data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
      $data['simulation_list'] = $this->general_model->simulation_list();   
      /********** Medicine  ****************/
      $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
      /*********Medicine ***************/
      $this->load->model('gipsa_discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
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
      $orderby = "order_by";
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);
      $data['discharge_field_master_list'] = $this->ipd_discharge_summary->discharge_field_master_list(1,1);
      //print_r($data['discharge_field_master_list']); exit;

      $get_payment_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field();
      //print_r($get_payment_detail); exit;
      $total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;



      $data['template_list'] = $this->ipd_discharge_summary->template_list();
      $this->load->view('gipsa_ipd_patient_discharge_summary/add',$data);       
    }

    function get_template_data($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->ipd_discharge_summary->template_data($template_id);
        echo $templatedata;
      }
    }


     // -> function to find gender according to selected ipd_discharge_summary
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_discharge_summary_id = $this->input->post('ipd_discharge_summary_id');
         $data='';
          if(!empty($ipd_discharge_summary_id)){
               $result = $this->general_model->find_gender($ipd_discharge_summary_id);
               if(!empty($result))
               {
                  $male = "";
                  $female = ""; 
                  $others=""; 
                  if($result['gender']==1)
                  {
                     $male = 'checked="checked"';
                  } 
                  else if($result['gender']==0)
                  {
                     $female = 'checked="checked"';
                  } 
                  else if($result['gender']==2){
                    $others = 'checked="checked"';
                  }
                  $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
                          <input type="radio" name="gender" '.$female.' value="0" /> Female &nbsp;
                          <input type="radio" name="gender" '.$others.' value="2" /> Others ';
               }
 
          }
          echo $data;
    }
    // -> end:
    public function edit($id="")
    {
     unauthorise_permission('124','751');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_discharge_summary->get_by_id($id);  
       
    // echo "<pre>";  print_r($data['prescription_presc_list']);die;
        $this->load->model('general/general_model');
        $get_setting=get_setting_value('IPD_Discharge_Summary_Medicine');
        $data['medicine_setting']=$get_setting;

        $data['page_title'] = "Update IPD Discharge Summary";  
        $post = $this->input->post();
        $data['form_error'] = '';
        $patient_id = $result['patient_id'];
        $ipd_id = $result['ipd_id']; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'patient_id'=>$result['patient_id'],
                                  'ipd_id'=>$result['ipd_id'],
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
                                  'medicine_administered'=>$result['medicine_administered'],
                                  'investigations'=>$result['investigations'],
                                  'h_o_past'=>$result['h_o_past'],
                                  'mlc_fir_no'=>$result['mlc_fir_no'],
                                  'ho_any_allergy'=>$result['ho_any_allergy'],
                                  'medicine_prescribed'=>$result['medicine_prescribed'],
                                  'lama_dama_reasons'=>$result['lama_dama_reasons'],
                                  'refered_data'=>$result['refered_data'],
                                  'icd_code'=>$result['icd_code'],
                                  'admission_reason'=>$result['admission_reason'],
                                  'family_history'=>$result['family_history'],
                                  'alcohol_history'=>$result['alcohol_history'],
                                  'procedure_performedand'=>$result['procedure_performedand'],
                                  'nutritional_advice'=>$result['nutritional_advice'],
                                  'consultants_name'=>$result['consultants_name'],
                                  'discharge_time_condition'=>$result['discharge_time_condition'],
                                  'discharge_advice'=>$result['discharge_advice'],
                                  'review_time_date'=>date('d-m-Y',strtotime($result['review_time_date'])), 
                                  'review_time'=>$result['review_time'], 
                                  'date_of_pro'=>date('d-m-Y',strtotime($result['date_of_pro'])), 
                                  'time_of_pro'=>$result['time_of_pro'], 
                                  'status'=>$result['status'],
                                  
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save();
                //echo $discharge_summary_id; exit;
                $this->session->set_userdata('discharge_summary_id',$discharge_summary_id);
                $this->session->set_flashdata('success','Discharge summary updated successfully.');
                redirect(base_url('gipsa_ipd_patient_discharge_summary/?status=print'));
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
        $data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);

        $data['simulation_list'] = $this->general_model->simulation_list();   
        $this->load->model('gipsa_discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
        //$data['discharge_labels_setting_list'] = $this->discharge_labels_setting->get_active_master_unique();
        //$data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_active_vital_master_unique();

 /********** Medicine Administered of discharge summary ************/
       $data['prescription_med_test_list']=$result['id'];
       $data['prescription_admin_presc_list']= $this->ipd_discharge_summary->get_discharge_medicine_administered($id);
      
       
       /********** Medicine Administered of discharge summary ************/

        /********** Medicine of discharge summary ************/
      $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
       $data['prescription_test_list']=$result['id'];
       $data['prescription_presc_list']= $this->ipd_discharge_summary->get_discharge_medicine($id);

       /********** Medicine of discharge summary ************/

       /********** Investigation  of discharge summary ************/
       $data['test_list']=$result['id'];
       $data['discharge_test_list']= $this->ipd_discharge_summary->get_discharge_test($id);
       /********** Medicine of discharge summary ************/

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
      $data['template_list'] = $this->ipd_discharge_summary->template_list();

      $data['discharge_field_master_list'] = $this->ipd_discharge_summary->discharge_field_master_list(1,1);
      /////
      $get_payment_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field($id);
      //print_r($get_payment_detail); exit;
      $total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;
      //print_r($total_values); exit;
      /////
      $this->load->view('gipsa_ipd_patient_discharge_summary/add',$data);       
      }
    }
    private function _validatemedicine()
    {
      $post = $this->input->post();
       $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required');

        $data['form_data'] = array(
                                        
                                       ); 
            return $data['form_data'];
    }
    private function _validate()
    {
        $post = $this->input->post();

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('patient_name', 'patient_name', 'trim|required');
        

        $total_values=array();
        if(isset($post['field_name'])) 
        {
          $count_field_names= count($post['field_name']);  
          $get_payment_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field();
          //print_r($get_payment_detail); exit;
          $total_values=array();
          for($i=0;$i<count($get_payment_detail);$i++) 
          {
            $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->discharge_lable.'_'.$get_payment_detail[$i]->field_id.'_'.$get_payment_detail[$i]->type;
          }
          

        }
        /*if(isset($post['field_name'])) 
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }*/


        if ($this->form_validation->run() == FALSE) 
        {  
            $summery_type='';
            if(!empty($post['summery_type']))
            {
                $summery_type = $post['summery_type'];
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
                                        'patient_id'=>$post['patient_id'],
                                        'ipd_id'=>$post['ipd_id'],
                                        'summery_type'=>$summery_type,
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
                                        'review_time'=>$post['review_time'], 
                                        'status'=>$post['status'],
                                        "field_name"=>$total_values,
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       // unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_discharge_summary->delete($id);
           $response = "IPD Discharge Summary successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('124','752');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary->deleteall($post['row_id']);
            $response = "IPD Discharge Summarys successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_discharge_summary->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_discharge_summary']." detail";
        $this->load->view('gipsa_ipd_patient_discharge_summary/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('124','753');
        $data['page_title'] = 'IPD Discharge Summary Archive List';
        $this->load->helper('url');
        $this->load->view('gipsa_ipd_patient_discharge_summary/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('124','753');
        $this->load->model('gipsa_ipd_patient_discharge_summary/ipd_patient_discharge_summary_archive_model','ipd_discharge_summary_archive'); 
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
             $row[] = $ipd_discharge_summary->ipd_no;
            $row[] = $ipd_discharge_summary->patient_code;
            $row[] = $ipd_discharge_summary->patient_name;
            $row[] = $ipd_discharge_summary->mobile_no;
             
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_discharge_summary->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('755',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_patient_discharge_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('754',$users_data['permission']['action'])){
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
        unauthorise_permission('124','755');
        $this->load->model('gipsa_ipd_patient_discharge_summary/ipd_patient_discharge_summary_archive_model','ipd_discharge_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_summary_archive->restore($id);
           $response = "IPD Discharge Summary successfully restore in IPD Discharge Summary List.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('124','755');
        $this->load->model('gipsa_ipd_patient_discharge_summary/ipd_patient_discharge_summary_archive_model','ipd_discharge_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary_archive->restoreall($post['row_id']);
            $response = "IPD Discharge Summarys successfully restore in IPD Discharge Summary List.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('124','754');
        $this->load->model('gipsa_ipd_patient_discharge_summary/ipd_patient_discharge_summary_archive_model','ipd_discharge_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_summary_archive->trash($id);
           $response = "IPD Discharge Summary successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('124','754');
        $this->load->model('gipsa_ipd_patient_discharge_summary/ipd_patient_discharge_summary_archive_model','ipd_discharge_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary_archive->trashall($post['row_id']);
            $response = "IPD Discharge Summary successfully deleted parmanently.";
            echo $response;
        }
    }


    public function print_discharge_summary($summary_id="",$branch_id='',$type='')
    {
         
       /* $discharge_summary_id= $this->session->userdata('discharge_summary_id');
        if(!empty($discharge_summary_id))
        {
            $discharge_summary_id = $discharge_summary_id;
        }
        else
        {
            $discharge_summary_id =$summary_id;
        }
        
        $data['page_title'] = "Print Discharge Summary";
        $summary_info = $this->ipd_discharge_summary->get_detail_by_summary_id($discharge_summary_id);
        $template_format = $this->ipd_discharge_summary->template_format(array('setting_name'=>'DISCHARGE_SUMMARY_PRINT_SETTING'),$branch_id,$type);
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $summary_info;

      $this->load->model('gipsa_ipd_patient_discharge_summary/discharge_labels_setting_model','discharge_labels_setting');
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
      $orderby = "order_by"; //discharge_labels_setting_list
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);

        $signature_image = get_doctor_signature($data['all_detail']['discharge_list'][0]->attend_doctor_id);
        
         $signature_reprt_data ='';
         if(!empty($signature_image))
         {
         
           $signature_reprt_data .='<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
          <table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
            <tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">Signature</b></td>
            </tr>';
            
            if(!empty($signature_image->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->sign_img))
            {
            
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->sign_img.'"></div></td>
            </tr>';
            
             }
             
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
            </tr>
            
          </table></div></div>';

         }
        $data['signature'] = $signature_reprt_data;
        $discharge_master_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field_print($discharge_summary_id);
        //print_r($get_payment_detail); exit;
        $total_values='';
        for($i=0;$i<count($discharge_master_detail);$i++) 
        {
            $total_values[]= $discharge_master_detail[$i]->field_value.'__'.$discharge_master_detail[$i]->discharge_lable.'__'.$discharge_master_detail[$i]->field_id.'__'.$discharge_master_detail[$i]->type.'__'.$discharge_master_detail[$i]->discharge_short_code;
        }
        $data['field_name'] = $total_values;

        $this->load->view('gipsa_ipd_patient_discharge_summary/print_discharge_summary_template',$data);*/
        
        
        
         
        $discharge_summary_id= $this->session->userdata('discharge_summary_id');
        if(!empty($discharge_summary_id))
        {
            $discharge_summary_id = $discharge_summary_id;
        }
        else
        {
            $discharge_summary_id =$summary_id;
        }
        
        $data['page_title'] = "Print Discharge Summary";
        $summary_info = $this->ipd_discharge_summary->get_detail_by_summary_id($discharge_summary_id);
        //print_r($summary_info);die;
        $template_format = $this->ipd_discharge_summary->template_format(array('setting_name'=>'DISCHARGE_SUMMARY_PRINT_SETTING'),$branch_id,$type);
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $summary_info;

     $this->load->model('general/general_model');
 /************ Medicine Administered*************/


        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
       //echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
        if(!empty($summary_info['medicine_administered_detail'][0]->medicine_name) && $summary_info['medicine_administered_detail'][0]->medicine_name!=""){
          $medicine_admib_data='';
            $medicine_admib_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="10" style="padding:4px;" valign="top">MEDICATION ADMINISTERED:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_admib_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_admib_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_admib_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_administered_detail'] as $prescription_presc)
                     { 
                         $medicine_admib_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_admib_data .='</tr></tbody>
                  </table>';

                  $data['medicine_administered_list']=$medicine_admib_data;
        }
        else{
          $data['medicine_administered_list']='';
        }
 // print_r($data['medicine_administered_list']);die;
      
        /************ Medicine Administered *************/



        /************ Medicine *************/


        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
      // echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
        if(!empty($summary_info['medicine_list'][0]->medicine_name) && $summary_info['medicine_list'][0]->medicine_name!=""){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="10" style="padding:4px;" valign="top">MEDICATION PRESCRIBED:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_list'] as $prescription_presc)
                     { 
                         $medicine_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_data .='</tr></tbody>
                  </table>';

                  $data['medicine_list']=$medicine_data;
        }
        else{
          $data['medicine_list']='';
        }
  
      
        /************ Medicine *************/

          /************ Investigation *************/
          $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($discharge_summary_id);

        if(!empty($summary_info['investigation_test_list'][0]->test_name) && $summary_info['investigation_test_list'][0]->test_name!=""){
            $test_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="6" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Test name</th>
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Result</th>
                      ';

              
                       
                       $i=0;
                       
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                    
                         $test_data .='<tr>';

                  $test_data .='<tr style="border-bottom:1px dashed #ccc;">
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$discharge_test_list->test_name.'</td>
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$discharge_test_list->test_date.'</td>
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$discharge_test_list->result.'</td>
                      </tr>';

                      $i++; } 

             $test_data .='</tr></tbody>
                  </table>';

                  $data['test_list']=$test_data;
        }
  
      
        /************ Investigation *************/

      $this->load->model('gipsa_discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
     // echo "<pre>" ;print_r($discharge_labels_setting_list);die;
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
     //  echo "<pre>" ;print_r($arr);die;
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
      $orderby = "order_by"; //discharge_labels_setting_list
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);

        $signature_image = get_doctor_signature($data['all_detail']['discharge_list'][0]->attend_doctor_id);
        
         $signature_reprt_data ='';
         if(!empty($signature_image))
         {
         
           $signature_reprt_data .='<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
          <table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
            <tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">Signature</b></td>
            </tr>';
            
            if(!empty($signature_image->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->sign_img))
            {
            
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->sign_img.'"></div></td>
            </tr>';
            
             }
             
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
            </tr>
            
          </table></div></div>';

         }
        $data['signature'] = $signature_reprt_data;
        $discharge_master_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field_print($discharge_summary_id);
        //print_r($get_payment_detail); exit;
        $total_values=array();
        for($i=0;$i<count($discharge_master_detail);$i++) 
        {
            $total_values[]= $discharge_master_detail[$i]->field_value.'__'.$discharge_master_detail[$i]->discharge_lable.'__'.$discharge_master_detail[$i]->field_id.'__'.$discharge_master_detail[$i]->type.'__'.$discharge_master_detail[$i]->discharge_short_code;
        }
       //  $data['summary_medicine_data'] = $this->ipd_discharge_summary->get_medicine_by_id($summary_id);
        $data['field_name'] = $total_values;

        $this->load->view('gipsa_ipd_patient_discharge_summary/print_discharge_summary_template',$data);
    
    }

    public function add_medicine($summary_id='')
    {
        unauthorise_permission('124','756');
        $data['page_title'] = "Add IPD Discharge Summary";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array();    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validatemedicine();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save_medicine();
                
                $this->session->set_userdata('medicine_discharge_summary_id',$summary_id);
                $this->session->set_flashdata('success','Discharge summary medicine saved successfully.');
                redirect(base_url('gipsa_ipd_patient_discharge_summary/?status=print_medicine'));
                
            }
            else
            { 
                $data['form_error'] = validation_errors();  
                
            }     
        }
      $this->load->model('general/general_model');
      $data['summary_data'] = $this->ipd_discharge_summary->get_by_id($summary_id);
      //print_r($data['summary_data']);
      /*$data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
      $data['simulation_list'] = $this->general_model->simulation_list(); */  
      $this->load->model('gipsa_discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
      $data['discharge_labels_setting_list'] = $this->discharge_labels_setting->get_active_master_unique();
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_active_vital_master_unique();
      $data['template_list'] = $this->ipd_discharge_summary->template_list();

      $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
      $this->load->view('gipsa_ipd_patient_discharge_summary/add_medicine',$data); 
    }

    public function view_medicine($summary_id='')
    {
        unauthorise_permission('124','757');
        $data['page_title'] = "IPD Discharge Summary Medicine";  
        
        $data['summary_id'] = $summary_id;    
       
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
        $this->load->view('gipsa_ipd_patient_discharge_summary/view_medicine',$data); 
    }

    
   
    public function print_discharge_summary_medicine($medicine_summary_ids="",$branch_id='')
    {
        unauthorise_permission('124','758'); 
        $medicine_discharge_summary_id= $this->session->userdata('medicine_discharge_summary_id');
        
        /* 23rd Feb 2019 neha by code*/
        
        // if(!empty($medicine_discharge_summary_id))
        // {
        //     $medicine_summary_id = $medicine_discharge_summary_id;
        // }
        // else
        // {
        //     $medicine_summary_id =$medicine_summary_id;
        // }
        
         /* 23rd Feb 2019 neha by code*/
        
        
        
        if(!empty($medicine_summary_ids))
        {
        	$medicine_summary_id =$medicine_summary_ids;
        }else
        {
            $medicine_summary_id = $medicine_discharge_summary_id;
        }
        
        
        
        $data['page_title'] = "Print Medicine Discharge Summary";
        $medicine_summary_info = $this->ipd_discharge_summary->get_medicine_detail_by_summary_id($medicine_summary_id);
        if(empty($medicine_summary_info['discharge_list']))
        {
          $medicine_summary_info = $this->ipd_discharge_summary->get_detail_by_summary_id($medicine_summary_id);
        }
       

        $template_format = $this->ipd_discharge_summary->medicine_template_format(array('setting_name'=>'DISCHARGE_MEDICINE_PRINT_SETTING'),$branch_id);

        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $medicine_summary_info;
        //print_r($data['all_detail']); exit;
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
        if(!empty($data['all_detail']['discharge_list'][0]->attend_doctor_id))
        {
          $signature_image = get_doctor_signature($data['all_detail']['discharge_list'][0]->attend_doctor_id);  
        }
        
        
        $data['summary_medicine_data'] = $this->ipd_discharge_summary->get_medicine_by_id($medicine_summary_id);
        //print_r($data['summary_medicine_data']);
        $discharge_medicine_list='';
        //if(!empty($data['summary_medicine_data'])) 
        //{ 
           $discharge_medicine_list .='<table  style="width: 100%;text-align:left;" >';
            $discharge_medicine_list .='<thead>';
            foreach ($data['prescription_medicine_tab_setting'] as $med_value) 
            { 
               if(!empty($med_value->setting_value)) { $laevel_name =  $med_value->setting_value; } else { $laevel_name =  $med_value->var_title; }
               $discharge_medicine_list .='<th>'.$laevel_name.'</th>';
            }
            $discharge_medicine_list .='</thead>';
            if(!empty($data['summary_medicine_data']))
            { 
                        
                        $discharge_medicine_list .='<tbody>';
                         $l=1;
                        foreach ($data['summary_medicine_data'] as $prescription_presc) 
                        {
                          
                        
                        $discharge_medicine_list .='<tr>';
                      
                        
                        foreach ($data['prescription_medicine_tab_setting'] as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $discharge_medicine_list .='<td>'. $prescription_presc['medicine_name'].'</td>';
                        
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_brand'].'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_salt'].'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_type'].'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_dose'].'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_duration'].'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_frequency'].'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $discharge_medicine_list .='<td>'.$prescription_presc['medicine_advice'].'</td>';
                       } 
                       }
                        
                      $discharge_medicine_list .='</tr>';
                   } 
                   $discharge_medicine_list .='</tbody>';
              }
              else
              {
                //$discharge_medicine_list .='<tbody><tr><td colspan="6" align="center">No matching records found</td></tr></tbody>';
              
              }  
          
        $discharge_medicine_list .='</table>';  
     //}




         $signature_reprt_data ='';
         if(!empty($signature_image))
         {
         
           $signature_reprt_data .='<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
          <table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
            <tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">Signature</b></td>
            </tr>';
            
            if(!empty($signature_image->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->sign_img))
            {
            
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: right;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->sign_img.'"></div></td>
            </tr>';
            
             }
             
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
            </tr>
            
          </table></div></div>';

         }

        $data['signature'] = $signature_reprt_data;
        $data['discharge_medicine_list_row'] =$discharge_medicine_list;
        $this->load->view('gipsa_ipd_patient_discharge_summary/print_discharge_summary_medicine_template',$data);
    }


    public function medicine_ajax_list($summary_id)
    {   
      unauthorise_permission('124','757');
       
        $this->load->model('gipsa_ipd_patient_discharge_summary/ipd_medicine_discharge_summary_model','medicine_discharge_summary');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
       //$list = $this->ipd_discharge_summary->get_datatables();
        $list = $this->medicine_discharge_summary->get_medicine_by_id($summary_id); 
//echo "<pre>";        print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_discharge_summary) {
          
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
           
            ////////// Check list end ///////////// 
           
            $checkboxs = "";
           
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_discharge_summary['id'].'">'.$check_script;
            foreach ($prescription_medicine_tab_setting as $tab_value) 
            { 
            if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_name'];
            }


            if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
            {  
            $row[] = $ipd_discharge_summary['medicine_type'];
            }

            if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
            {
                $row[] = $ipd_discharge_summary['medicine_salt'];
            }
            if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
            {
                $row[] = $ipd_discharge_summary['medicine_brand'];
            }

            if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_dose'];
            }
            if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_duration'];
            }
            if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_frequency'];
            }
            if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_advice'];
            }
}
            $btnedit='';
            $btndelete='';
          
            
            $data[] = $row;
            $i++;
        }
//echo "<pre>";print_r($data); exit;
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_discharge_summary->count_all(),
                        "recordsFiltered" => $this->medicine_discharge_summary->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    function deleteall_medicine()
    {
        unauthorise_permission('124','759');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary->deleteall_medicine($post['row_id']);
            $response = "Medicne successfully deleted.";
            echo $response;
        }
    }

    public function print_template($id)
    {
        $data['page_title'] = 'Select Template';
        //$post = $this->input->post();  
        $data['id'] = $id;
        $this->load->view('gipsa_ipd_patient_discharge_summary/template',$data);
   
    }

}
?>