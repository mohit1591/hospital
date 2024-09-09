<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_patient_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dialysis_patient_summary/dialysis_patient_summary_model','ipd_discharge_summary');
        $this->load->library('form_validation');
    }

    public function index($dialysis_id='',$patient_id='')
    { 
       // unauthorise_permission('124','749');
        $data['page_title'] = 'Dialysis Patient Summary List'; 
        
        $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {
            if(!empty($dialysis_id) && !empty($patient_id))
            {
                $start_date = '';
                $end_date = '';
            }
            else
            {
                $start_date = date('d-m-Y');
                $end_date = date('d-m-Y');
            }
            
        }
        // End Defaul Search
      $data['form_data'] = array('patient_name'=>'','mobile_no'=>'','patient_code'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'dialysis_no'=>'','dialysis_id'=>$dialysis_id,'patient_id'=>$patient_id);
        $this->load->view('dialysis_patient_summary/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission('124','749');
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
            $row[] = $ipd_discharge_summary->booking_code;
            $row[] = $ipd_discharge_summary->patient_code;
            $row[] = $ipd_discharge_summary->patient_name;
            $row[] = $ipd_discharge_summary->mobile_no;

             
            //$row[] = $status;
            $row[] = date('d-m-Y',strtotime($ipd_discharge_summary->summary_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_discharge_summary->branch_id)
            {
              //if(in_array('751',$users_data['permission']['action'])){
               $btnedit =' <a  href="'.base_url("dialysis_patient_summary/edit/".$ipd_discharge_summary->id).'" class="btn-custom" style="'.$ipd_discharge_summary->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              //}
              //if(in_array('752',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_discharge_summary('.$ipd_discharge_summary->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              // }
            }
            $btnmedic ='';
            //if(in_array('756',$users_data['permission']['action']))
           // {
            $btnmedic =' <a  href="'.base_url("dialysis_patient_summary/add_medicine/".$ipd_discharge_summary->id).'" style="'.$ipd_discharge_summary->id.'" title="Add Medicine"><i class="fa fa-pencil" aria-hidden="true"></i> Add Medicine</a>';
            //}
            $btnmedicine_view ='';
            //if(in_array('757',$users_data['permission']['action']))
            //{
            $btnmedicine_view =' <a  href="'.base_url("dialysis_patient_summary/view_medicine/".$ipd_discharge_summary->id).'"  style="'.$ipd_discharge_summary->id.'" title="Add Medicine"> <i class="fa fa-eye" aria-hidden="true"></i> View Medicine</a>';
            //}

             $print_pdf_url = "'".base_url('dialysis_patient_summary/print_discharge_summary/'.$ipd_discharge_summary->id)."'";
            
            $btn_print='';
            if($users_data['parent_id']!='113')
            {
               $btn_print = ' <a onClick="return print_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_discharge_summary->id.'" title="Print"><i class="fa fa-print"></i> Print</a>'; 
            }
               
    
      $btn_letter_print = ' <a onClick="return print_letter_head_summary('.$ipd_discharge_summary->id.','.$ipd_discharge_summary->branch_id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_discharge_summary->id.'" title="Print pdf"><i class="fa fa-print"></i> Print Pdf</a>';
      
            $btn_medicine_print='';
            //if(in_array('758',$users_data['permission']['action']))
            //{  
              $print_medicine_url = "'".base_url('dialysis_patient_summary/print_discharge_summary_medicine/'.$ipd_discharge_summary->id)."'";
             $btn_medicine_print = '<a href="javascript:void(0)"  onClick="return print_window_page('.$print_medicine_url.');"> <i class="fa fa-print"></i> Print Medicine</a>';
            //}
            
            $btn_advance='';
          
           
            $btn_advanced = '<div class="slidedown">
              <button disabled class="btn-custom">More <span class="caret"></span></button>
                <ul class="slidedown-content">
                '.$btnmedic.$btnmedicine_view.$btn_medicine_print.'
              </ul>
            </div> ';
           
             $row[] = $btnedit.$btndelete.$btn_print.$btn_letter_print.$btneditadvance.$btn_letter_print_adv;
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
    
    
     public function advance_search()
    {

            $this->load->model('general/general_model'); 
            $data['page_title'] = "Advance Search";
            $data['insurance_type_list'] = $this->general_model->insurance_type_list();
            $data['insurance_company_list'] = $this->general_model->insurance_company_list(); 
            $post = $this->input->post();
           //echo "<pre>"; print_r($post); exit;
           $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        }
            $data['form_data'] = array(
                                      "start_date"=>$start_date,
                                      "end_date"=>$end_date,
                                      "dialysis_no"=>"",
                                      "patient_code"=>"",
                                      "patient_name"=>"",
                                      "mobile_no"=>"",
                                      "dialysis_date"=>"",
                                      "adhar_no"=>"",
                                      'all'=>'',
                                      "dialysis_time"=>"",
                                      "insurance_type"=>"",
                                      "insurance_type_id"=>"",
                                      "ins_company_id"=>"",
                                      "dialysis_no"=>"",
                                      
                                      );
            if(isset($post) && !empty($post))
            {
            //print_r($post);die;
                $marge_post = array_merge($data['form_data'],$post);
                $this->session->set_userdata('dialysis_summary_serach', $marge_post);

            }
                $purchase_search = $this->session->userdata('dialysis_summary_serach');
                if(isset($purchase_search) && !empty($purchase_search))
                {
                  $data['form_data'] = $purchase_search;
                }
            $this->load->view('dialysis_booking/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('dialysis_summary_serach');
    }
    
    
    public function add($dialysis_id="",$patient_id="")
    {
        //unauthorise_permission('124','750');
        $data['page_title'] = "Add Dialysis Summary";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'dialysis_id'=>$dialysis_id, 
                                  'patient_id'=>$patient_id, 
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
                                  'provisional_diagnosis'=>"",
                                  'final_diagnosis'=>"",
                                  'course_in_hospital'=>"",
                                  'investigations'=>"",
                                  'discharge_time_condition'=>"",
                                  'discharge_advice'=>"",
                                  'review_time_date'=>date('d-m-Y'),
                                  'review_time'=>date('H:i:s'),
                                  'death_date'=>date('d-m-Y'),
                                  'death_time'=>date('H:i:s'),
                                  

                                   'status'=>"1",
                                   'summary_date'=>date('d-m-Y'),
                              );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $dialysis_summary_id = $this->ipd_discharge_summary->save();
                
                $this->session->set_userdata('dialysis_summary_id',$dialysis_summary_id);
                $this->session->set_flashdata('success','Dialysis summary updated successfully.');
                redirect(base_url('dialysis_patient_summary/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                
            }     
        }
      $this->load->model('general/general_model');
      
      $data['patient_details']= $this->general_model->get_patient_according_to_dialysis($dialysis_id,$patient_id);
      
      $this->load->model('discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
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
      
      $data['simulation_list'] = $this->general_model->simulation_list(); 
      
      //print_r($data['discharge_field_master_list']); exit;

      $get_payment_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field();
      //print_r($get_payment_detail); exit;  //discharge_test_list
      $total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;



      $data['template_list'] = $this->ipd_discharge_summary->template_list();
      
      /********** Medicine  ****************/
      $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_print_tab_setting();
      /*********Medicine ***************/
      
      $this->load->view('dialysis_patient_summary/add',$data);       
    }

    function get_template_data($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->ipd_discharge_summary->template_data($template_id);
        echo $templatedata;
      }
    }
    
    function get_template_test_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->ipd_discharge_summary->get_dialysis_value($template_id);
        echo $templatetestdata;
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
    
    public function edit($id="")
    {
     //unauthorise_permission('124','751');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_discharge_summary->get_by_id($id);  
        //echo "<pre>"; print_r($result); exit;
        $data['page_title'] = "Update Dialysis Summary";  
        $post = $this->input->post();
        $data['form_error'] = '';
        $patient_id = $result['patient_id'];
        $dialysis_id = $result['dialysis_id']; 

        if(empty($result['death_date']) || $result['death_date']=='0000-00-00')
        {
          $death_date='';
        }
        else
        {
          $death_date = date('d-m-Y',strtotime($result['death_date']));
        }
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'patient_id'=>$result['patient_id'],
                                  'dialysis_id'=>$result['dialysis_id'],
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
                    
                    
                                  'review_time_date'=>date('d-m-Y',strtotime($result['review_time_date'])), 
                                  'review_time'=>$result['review_time'], 

                                  'death_date'=>$death_date, 
                                  'death_time'=>$result['death_time'], 

                                  'status'=>$result['status'],
                                  'summary_date'=>date('d-m-Y',strtotime($result['summary_date'])), 
                                  
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save();
                //echo $discharge_summary_id; exit;
                $this->session->set_userdata('dialysis_summary_id',$discharge_summary_id);
                $this->session->set_flashdata('success','Dialysis summary updated successfully.');
                redirect(base_url('dialysis_patient_summary/?status=print'));
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
        $data['patient_details']= $this->general_model->get_patient_according_to_dialysis($dialysis_id,$patient_id);
        $data['simulation_list'] = $this->general_model->simulation_list();   
        $this->load->model('discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
        //$data['discharge_labels_setting_list'] = $this->discharge_labels_setting->get_active_master_unique();
        //$data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_active_vital_master_unique();

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
      $data['discharge_labels_setting_list'] = '';//$arr;
      $data['discharge_vital_setting_list'] = '';//$this->discharge_labels_setting->get_vital_master_unique_array(1,1);        
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
      
      ///
      
       
        $this->load->model('discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
       
       /********** Medicine of discharge summary ************/
       $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_print_tab_setting();
       $data['prescription_test_list']=$result['id'];
       $data['prescription_presc_list']= $this->ipd_discharge_summary->get_discharge_medicine($id);
       /********** Medicine of discharge summary ************/

       /********** Investigation  of discharge summary ************/
       $data['test_list']=$result['id'];
       $data['discharge_test_list']= $this->ipd_discharge_summary->get_discharge_test($id);
       /********** Medicine of discharge summary ************/
       
      //print_r($total_values); exit;
      /////
      $this->load->view('dialysis_patient_summary/add',$data);       
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
                                        'dialysis_id'=>$post['dialysis_id'],
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
           $response = "IPD Dialysis Summary successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        //unauthorise_permission('124','752');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary->deleteall($post['row_id']);
            $response = "IPD Dialysis Summarys successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_discharge_summary->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_discharge_summary']." detail";
        $this->load->view('dialysis_patient_summary/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('124','753');
        $data['page_title'] = 'Dialysis Summary Archive List';
        $this->load->helper('url');
        $this->load->view('dialysis_patient_summary/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('124','753');
        $this->load->model('dialysis_patient_summary/dialysis_summary_archive_model','ipd_discharge_summary_archive'); 
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
            //if(in_array('755',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_patient_discharge_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              //}
              //if(in_array('754',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               //}
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
        //unauthorise_permission('124','755');
        $this->load->model('dialysis_patient_summary/dialysis_patient_summary_archive_model','ipd_discharge_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_summary_archive->restore($id);
           $response = "Dialysis Summary successfully restore in IPD Discharge Summary List.";
           echo $response;
       }
    }

    function restoreall()
    { 
       //unauthorise_permission('124','755');
        $this->load->model('dialysis_patient_summary/dialysis_patient_summary_archive_model','ipd_discharge_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary_archive->restoreall($post['row_id']);
            $response = "Dialysis Summarys successfully restore in IPD Discharge Summary List.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       // unauthorise_permission('124','754');
        $this->load->model('dialysis_patient_summary/dialysis_patient_summary_archive_model','ipd_discharge_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_summary_archive->trash($id);
           $response = "Dialysis Summary successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('124','754');
        $this->load->model('dialysis_patient_summary/dialysis_patient_summary_archive_model','ipd_discharge_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary_archive->trashall($post['row_id']);
            $response = "Dialysis Summary successfully deleted parmanently.";
            echo $response;
        }
    }


    public function print_discharge_summary($summary_id="",$branch_id='',$type='')
    {
        //get_ipd_assigned_doctors_name
         
      $discharge_summary_id= $this->session->userdata('dialysis_summary_id');
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
        //echo "<pre>"; print_r($summary_info); exit;
        $template_format = $this->ipd_discharge_summary->template_format(array('setting_name'=>'DIALYSIS_SUMMARY_PRINT_SETTING'),$branch_id,$type);
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $summary_info;

      $this->load->model('discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
      //$discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      //$discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      /*$arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
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
      $data['discharge_labels_setting_list'] = $arr;*/
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);

        $signature_image = '';//get_doctor_signature($data['all_detail']['discharge_list'][0]->doctor_id);
        
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
        $data['field_name'] = $total_values;
        
        
        
        
        
        
        
        /************ Medicine Administered*************/


        $prescription_medicine_tab_setting = get_dialysis_prescription_medicine_print_tab_setting();
       //echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
        if(!empty($summary_info['medicine_administered_detail'][0]->medicine_name) && $summary_info['medicine_administered_detail'][0]->medicine_name!=""){
          $medicine_admib_data='';
            $medicine_admib_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
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


        $prescription_medicine_tab_setting = get_dialysis_prescription_medicine_print_tab_setting();
      // echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
      if(!empty($summary_info['medicine_list']))
      {
        if(!empty($prescription_medicine_tab_setting)){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="15" style="padding:4px;" valign="top">Medicine Prescribed:</th>
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
      }
      else{
          $data['medicine_list']='';
        }
  
      
        /************ Medicine *************/

          /************ Investigation *************/
          $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($discharge_summary_id);
        if(!empty($discharge_summary_test_list))
        {
        //if(!empty($summary_info['investigation_test_list'][0]->test_name) && $summary_info['investigation_test_list'][0]->test_name!=""){
            $test_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="6" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left"  style="padding:4px;" valign="top">Test name</th>
                      <th  align="left"  style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" style="padding:4px;" valign="top">Result</th>
                      ';

              
                       
                       $i=0;
                       
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                    
                         $test_data .='<tr>';

                  $test_data .='<tr style="border-bottom:1px dashed #ccc;">
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->test_name.'</td>
                        <td align="left" style="padding:4px;" valign="top">'.$discharge_test_list->test_date.'</td>
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->result.'</td>
                      </tr>';

                      $i++; } 

             $test_data .='</tr></tbody>
                  </table>';

                  $data['test_list']=$test_data;
        }
        else
        {
            $data['test_list']='';
        }
       // }
  
     // echo "<pre>"; print_r($test_data);
        /************ Investigation *************/
         $dialysis_id = $summary_info['discharge_list'][0]->dialysis_id;
        /* $ipd_assigned_doctors=get_dialysis_assigned_doctors_name($dialysis_id);
        $ipd_assigned_doctors=$ipd_assigned_doctors[0]->assigned_doctor;
        $ipd_assigned_doctors=explode(',', $ipd_assigned_doctors);
        $ipd_assigned_doctors=implode(', Dr. ', $ipd_assigned_doctors);*/
        $data['ipd_assigned_doctors'] = '';//$ipd_assigned_doctors;

        //echo "<pre>"; print_r($data); exit;
        $this->load->view('dialysis_patient_summary/print_discharge_summary_template',$data);
    
    }

    public function add_medicine($summary_id='')
    {
        //unauthorise_permission('124','756');
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
                redirect(base_url('dialysis_patient_summary/?status=print_medicine'));
                
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
      $this->load->model('discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
      $data['discharge_labels_setting_list'] = $this->discharge_labels_setting->get_active_master_unique();
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_active_vital_master_unique();
      $data['template_list'] = $this->ipd_discharge_summary->template_list();

      $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_print_tab_setting();
      $this->load->view('dialysis_patient_summary/add_medicine',$data); 
    }

    public function view_medicine($summary_id='')
    {
        //unauthorise_permission('124','757');
        $data['page_title'] = "IPD Discharge Summary Medicine";  
        
        $data['summary_id'] = $summary_id;    
       
        $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_print_tab_setting();
        $this->load->view('dialysis_patient_summary/view_medicine',$data); 
    }

    
   
    public function print_discharge_summary_medicine($medicine_summary_ids="",$branch_id='')
    {
        //('124','758'); 
        $medicine_discharge_summary_id= $this->session->userdata('medicine_discharge_summary_id');
        
        
        
        
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
        $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_print_tab_setting();
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
        
        
        
        
        
        $this->load->view('dialysis_patient_summary/print_discharge_summary_medicine_template',$data);
    }


    public function medicine_ajax_list($summary_id)
    {   
      //unauthorise_permission('124','757');
       
        $this->load->model('dialysis_patient_summary/dialysis_patient_summary_model','medicine_discharge_summary');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $prescription_medicine_tab_setting = get_dialysis_prescription_medicine_print_tab_setting();
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
                        "recordsTotal" => count($list),//$this->medicine_discharge_summary->count_all(),
                        "recordsFiltered" => count($list),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    function deleteall_medicine()
    {
        //unauthorise_permission('124','759');
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
        $this->load->view('dialysis_patient_summary/template',$data);
   }
    
    public function print_discharge_summary_letter_head($summary_id="",$branch_id='',$type='')
    {
        $users_data = $this->session->userdata('auth_users');
        //$booked_total_department = $this->test->booked_total_department($booking_id);;
        $test_heading_interpretation = '';
        $booking_data_discharge =$this->ipd_discharge_summary->get_detail_by_summary_id($summary_id);
        //echo "<pre>"; print_r($booking_data_discharge);die;
        $summary_info = $this->ipd_discharge_summary->get_detail_by_summary_id($summary_id);
        //echo "<pre>"; print_r($booking_data);die;
        $this->load->library('m_pdf');
        $report_templ_part = $this->ipd_discharge_summary->report_html_template('',$branch_id);
        $template_format = $this->ipd_discharge_summary->test_report_template_format(array('setting_name'=>'DIALYSIS_SUMMARY_PRINT_SETTING'));

       /* $this->load->model('discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
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
      $discharge_labels_setting_list= $arr;
      //echo "<pre>";print_r($discharge_labels_setting_list); exit;
      $discharge_vital_setting_list= $this->discharge_labels_setting->get_vital_master_unique_array(1,1);
      $discharge_field_master_list= $this->ipd_discharge_summary->discharge_field_master_list(1,1);*/

      $booking_data = $booking_data_discharge['discharge_list'][0];
    //  print_r($booking_data);die;
        $patient_name=$booking_data->patient_name;
        $patient_code=$booking_data->patient_code;
        $attended_doctor=$booking_data->attend_doctor_id;
        $mobile_no = $booking_data->mobile_no;
        $tube_no = $booking_data->tube_no;
        $gender = $booking_data->gender;
        $age_y = $booking_data->age_y;
        $age_m = $booking_data->age_m;
        $age_d = $booking_data->age_d;
        $age_h = $booking_data->age_h;
        $booking_date = $booking_data->booking_date;
        $booking_time = $booking_data->booking_time;
        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$gender];
       
        $age = "";
        if($age_y>0)
        {
        $year = 'Y';
        if($age_y==1)
        {
          $year = 'Y';
        }
        $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
        $month = 'M';
        if($age_m==1)
        {
          $month = 'M';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'D';
        if($age_d==1)
        {
          $day = 'D';
        }
        $age .= ", ".$age_d." ".$day;
        }
        if($age_h>0)
        {
        $hours = 'H';
        if($age_h==1)
        {
          $hours = 'H';
        }
        $age .= $age_h." ".$hours;
        }
        $patient_age =  $age; 
        $doctor_name = get_doctor_name($attended_doctor);
        $qualification= get_doctor_qualifications($attended_doctor);
      /*******Format***********/
      
      /**********Format********/
      

   
   $dialysis_date_time='';
    if(!empty($booking_data->dialysis_date) && $booking_data->dialysis_date!='0000-00-00')
    {
                 $time = '';
                if(date('h:i:s', strtotime($booking_data->dialysis_time))!='12:00:00')
                {
                    $time = date('h:i A', strtotime($booking_data->dialysis_time));
                }
               $dialysis_date_time = date('d-m-Y',strtotime($booking_data->dialysis_date)).' '.$time;
           
             
    }
  
    $booking_date_time = $dialysis_date_time;

      //$pdf_template = $this->load->view('test/test_pdf_template',$pdf_data,true);
       ///////////////
      $header_replace_part = $report_templ_part->page_details;
      
      $header_replace_part = str_replace("{patient_name}",$patient_name,$header_replace_part); 
/* for doctor qualification */
    //address setting
    $this->load->model('address_print_setting/address_print_setting_model','address_setting');
      $address_setting_list = $this->address_setting->get_master_unique();
    if(empty($address_setting_list))
    {
         $header_replace_part = str_replace("{patient_address}",$booking_data->address,$header_replace_part);
    }
    else
    {
        $address='';
        if($address_setting_list[0]->address1)
        {
           $address .= $booking_data->address.' '; 
        }
        if($address_setting_list[0]->address2)
        {
           $address .= $booking_data->address2.' '; 
        }
       
        if($address_setting_list[0]->address3)
        {
           $address .=  $booking_data->address3.' '; 
        }
      
        if($address_setting_list[0]->city)
        {
           $address .=  $booking_data->city_name.' '; 
        }
       
        if($address_setting_list[0]->state)
        {
           $address .= $booking_data->state_name.' '; 
           //echo $address;die;
        }
       
        if($address_setting_list[0]->country)
        {
           $address .=  $booking_data->country_name.' '; 
        }
        
        if($address_setting_list[0]->pincode)
        {
           $address .= $booking_data->pincode; 
        }
        
        
       
        
        $header_replace_part = str_replace("{patient_address}",$address,$header_replace_part);
    }

      

      $header_replace_part = str_replace("{patient_reg_no}",$patient_code,$header_replace_part);

      $header_replace_part = str_replace("{mobile_no}",$mobile_no,$header_replace_part);

     
      $header_replace_part = str_replace("{booking_code}",$booking_data->booking_code,$header_replace_part);
      $header_replace_part = str_replace("{dialysis_date}",$booking_date_time,$header_replace_part);

      $header_replace_part = str_replace("{doctor_name}",$doctor_name,$header_replace_part);

      $header_replace_part = str_replace("{specialization}",$qualification,$header_replace_part);

      $header_replace_part = str_replace("{booking_time}", $test_booking_time, $header_replace_part);
      //booking time//
      $header_replace_part = str_replace("{admission_date}",$booking_data->admission_date,$header_replace_part);
      //$header_replace_part = str_replace("{discharge_date}",$booking_data->discharge_date,$header_replace_part);
      
      
      
      $dialysis_id = $summary_info['discharge_list'][0]->dialysis_id;
         $ipd_assigned_doctors=get_dialysis_assigned_doctors_name($dialysis_id);
        $ipd_assigned_doctors=$ipd_assigned_doctors[0]->assigned_doctor;
        $ipd_assigned_doctors=explode(',', $ipd_assigned_doctors);
        $ipd_assigned_doctors=implode(', Dr. ', $ipd_assigned_doctors);
        $data['ipd_assigned_doctors'] = $ipd_assigned_doctors;
      
      $header_replace_part = str_replace("{assigned_doctor}",$ipd_assigned_doctors,$header_replace_part);
      
      if(!empty($booking_data->relation))
        {
        
        $header_replace_part = str_replace("{parent_relation_type}",$booking_data->relation,$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_type}",'',$header_replace_part);
        }

    if(!empty($booking_data->relation_name))
        {
        $rel_simulation = get_simulation_name($booking_data->relation_simulation_id);
        $header_replace_part = str_replace("{parent_relation_name}",$rel_simulation.' '.$booking_data->relation_name,$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_name}",'',$header_replace_part);
        }
        
        
      
      
      
      
      
      if(!empty($all_detail['discharge_list'][0]->relation))
        {
        
        $template_data = str_replace("{parent_relation_type}",$all_detail['discharge_list'][0]->relation,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_type}",'',$template_data);
        }

    if(!empty($all_detail['discharge_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['discharge_list'][0]->relation_simulation_id);
        $template_data = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['discharge_list'][0]->relation_name,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_name}",'',$template_data);
        }
      
    
    

      $gender_age = $gender.'/'.$patient_age;

      $header_replace_part = str_replace("{patient_age}",$gender_age,$header_replace_part);
      
     
        
            
    $header_replace_part = str_replace("{summary_type}",$sumtype,$header_replace_part);
   

      $middle_replace_part = $report_templ_part->page_middle;
      $pos_start = strpos($middle_replace_part, '{start_loop}');
      $pos_end = strpos($middle_replace_part, '{end_loop}');
      $row_last_length = $pos_end-$pos_start;
      $row_loop = substr($middle_replace_part,$pos_start+12,$row_last_length-12);


    foreach ($discharge_labels_setting_list as $value) 
    {   
    //echo "<pre>".$value->setting_name;     
    if(strcmp(strtolower($value['setting_name']),'cheif_complaints')=='0')
    {

        if(!empty($value['setting_value'])) { $cheif_complaints_name =  $value['setting_value']; } else { $cheif_complaints_name =  $value['var_title']; }

            if(!empty($booking_data_discharge['discharge_list'][0]->chief_complaints)){ 
            $chief_complaints.= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>'.$cheif_complaints_name.' :</b>
               '.nl2br($booking_data_discharge['discharge_list'][0]->chief_complaints).'
              </div>
            </div>';
            }
    }

   



    if(strcmp(strtolower($value['setting_name']),'ho_presenting_illness')=='0')
    {
       
         if(!empty($value['setting_value'])) { $Personal_history =  $value['setting_value']; } else { $Personal_history =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->h_o_presenting)){ 
        $h_o_presenting_illness = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Personal_history.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->h_o_presenting).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value['setting_name']),'on_examination')=='0')
    {   
        if(!empty($value['setting_value'])) { $on_examination_name =  $value['setting_value']; } else { $on_examination_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->on_examination)){ 
        $on_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$on_examination_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->on_examination).'
          </div>
        </div>';
        }
    }
    
    
    
    if(strcmp(strtolower($value['setting_name']),'past_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $past_history_name =  $value['setting_value']; } else { $past_history_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->past_history)){ 
                $past_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$past_history_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->past_history).'
                  </div>
                </div>';
                }
            }
            
            
    if(strcmp(strtolower($value['setting_name']),'personal_history')=='0')
    {   
        if(!empty($value['setting_value'])) { $personal_history_name =  $value['setting_value']; } else { $personal_history_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->personal_history)){ 
        $personal_history = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$personal_history_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->personal_history).'
          </div>
        </div>';
        }
    }

    if(strcmp(strtolower($value['setting_name']),'birth_history')=='0')
    {   
        if(!empty($value['setting_value'])) { $birth_history_name =  $value['setting_value']; } else { $birth_history_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->birth_history)){ 
        $birth_history = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$birth_history_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->birth_history).'
          </div>
        </div>';
        }
    }

    if(strcmp(strtolower($value['setting_name']),'general_history')=='0')
    {   
        if(!empty($value['setting_value'])) { $general_history_name =  $value['setting_value']; } else { $general_history_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->general_history)){ 
        $general_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$general_history_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->general_history).'
          </div>
        </div>'.$general_examination_vital;
        }
    }

     if(strcmp(strtolower($value['setting_name']),'systemic_examination')=='0')
            {   
                if(!empty($value['setting_value'])) { $systemic_examination_name =  $value['setting_value']; } else { $systemic_examination_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->systemic_examination)){ 
                $systemic_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$systemic_examination_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->systemic_examination).'
                  </div>
                </div>'.$systemic_examination_vital;
                }
            }

            if(strcmp(strtolower($value['setting_name']),'local_examination')=='0')
            {   
                if(!empty($value['setting_value'])) { $local_examination_name =  $value['setting_value']; } else { $local_examination_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->local_examination)){ 
                $local_examination = '<div style="float:left;width:100%;margin:1em 0 4em;">
                  <div style="font-size:small;line-height:18px;"><b>'.$local_examination_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->local_examination).'
                  </div>
                </div>';
                }
            }
            
            
            if(strcmp(strtolower($value['setting_name']),'specific_findings')=='0')
            {   
                if(!empty($value['setting_value'])) { $specific_findings_name =  $value['setting_value']; } else { $specific_findings_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->specific_findings)){ 
                $specific_findings = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$specific_findings_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->specific_findings).'
                  </div>
                </div>';
                }
            }
            
         if(strcmp(strtolower($value['setting_name']),'diagnosis')=='0')
        {
    
            if(!empty($value['setting_value'])) { $diagnosis_name =  $value['setting_value']; } else { $diagnosis_name =  $value['var_title']; }
    
                if(!empty($booking_data_discharge['discharge_list'][0]->diagnosis)){ 
                $diagnosis.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$diagnosis_name.' :</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->diagnosis).'
                  </div>
                </div>';
                }
        }

            if(strcmp(strtolower($value['setting_name']),'menstrual_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $menstrual_history_name =  $value['setting_value']; } else { $menstrual_history_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->menstrual_history)){ 
                $menstrual_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$menstrual_history_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->menstrual_history).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'obstetric_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $obstetric_history_name =  $value['setting_value']; } else { $obstetric_history_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->obstetric_history)){ 
                $obstetric_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$obstetric_history_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->obstetric_history).'
                  </div>
                </div>';
                }
            }
//surgery_preferred
             if(strcmp(strtolower($value['setting_name']),'surgery_preferred')=='0')
            {   
                if(!empty($value['setting_value'])) { $surgery_preferred_name =  $value['setting_value']; } else { $surgery_preferred_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->surgery_preferred)){ 
                $surgery_preferred = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$surgery_preferred_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->surgery_preferred).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'operation_notes')=='0')
            {   
                if(!empty($value['setting_value'])) { $operation_notes_name =  $value['setting_value']; } else { $operation_notes_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->operation_notes)){ 
                 $operation_notes = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$operation_notes_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->operation_notes).'
                  </div>
                </div>'; 
                }
            }

            if(strcmp(strtolower($value['setting_name']),'treatment_given')=='0')
            {   
                if(!empty($value['setting_value'])) { $treatment_given_name =  $value['setting_value']; } else { $treatment_given_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->treatment_given)){ 
                $treatment_given = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$treatment_given_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->treatment_given).'
                  </div>
                </div>';
                }
            }

              if(strcmp(strtolower($value['setting_name']),'mlc_fir_no')=='0')
              {
                if(!empty($value['setting_value'])) { $mlc_fir_no_name =  $value['setting_value']; } else { $mlc_fir_no_name =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->mlc_fir_no)){ 
                $mlc_fir_no = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$mlc_fir_no_name.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->mlc_fir_no).'
                </div>
                </div>';
                 } 

              }
                  if(strcmp(strtolower($value['setting_name']),'lama_dama_reasons')=='0')
              {
                if(!empty($value['setting_value'])) { $lama_dama_reason =  $value['setting_value']; } else { $lama_dama_reason =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->lama_dama_reasons)){ 
                $lama_dama_reasons = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$lama_dama_reason.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->lama_dama_reasons).'
                </div>
                </div>';
                 }

              }
                  if(strcmp(strtolower($value['setting_name']),'refered_data')=='0')
              {
                if(!empty($value['setting_value'])) { $refered_data_name =  $value['setting_value']; } else { $refered_data_name =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->refered_data)){ 
                $refered_data = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$refered_data_name.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->refered_data).'
                </div>
                </div>';
                 }

              }

    

    if(strcmp(strtolower($value['setting_name']),'vitals')=='0')
    {
        if(!empty($value['setting_value'])) { $vitals_name =  $value['setting_value']; } else { $vitals_name =  $value['var_title']; }
       
        $vitals = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$vitals_name.' :</b>';
        $vitals .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
            <tbody>
                <tr>';
            foreach($discharge_vital_setting_list as $discharge_vital_labels)
            {
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'pulse')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $pulse_name = $discharge_vital_labels['setting_value']; } else { $pulse_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_pulse))
                    {
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$pulse_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_pulse).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>'; 
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'chest')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_chest_name = $discharge_vital_labels['setting_value']; } else { $vitals_chest_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_chest))
                    {
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_chest_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_chest).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>'; 
                    }
                }


                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'bp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $bp_name = $discharge_vital_labels['setting_value']; } else { $bp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_bp))
                    {
                    
                    $vitals .='
                            <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$bp_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                        <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_bp).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cvs')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_cvs_name = $discharge_vital_labels['setting_value']; } else { $vitals_cvs_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cvs))
                    {
                    
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_cvs_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cvs).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'temp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_temp_name = $discharge_vital_labels['setting_value']; } else { $vitals_temp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_temp))
                    {
                    
                    $vitals .='

                    <td align="left" valign="top" width="20%">
                    <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                        <tbody>
                            <tr>
                                <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_temp_name.'</b></th>
                            </tr>
                            <tr>
                                <td align="left" valign="top">
                                <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_temp).'</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cns')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $cns_name = $discharge_vital_labels['setting_value']; } else { $cns_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cns))
                    {
                    
                    $vitals .='
                    <td align="left" valign="top" width="20%">
                        <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                            <tbody>
                                <tr>
                                    <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$cns_name.'</b></th>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">
                                    <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cns).'</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'p_a')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $p_a_name = $discharge_vital_labels['setting_value']; } else { $p_a_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_p_a))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$p_a_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                        <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_p_a).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }     
            
           

        }


         $vitals .='</tr>
                    </tbody>
                </table></div>
                        </div>';
      
    }
        
    






    if(strcmp(strtolower($value['setting_name']),'provisional_diagnosis')=='0')
    { 
        if(!empty($value['setting_value'])) { $Diagnosis_name =  $value['setting_value']; } else { $Diagnosis_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->provisional_diagnosis)){ 
        $provisional_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Diagnosis_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->provisional_diagnosis).'
          </div>
        </div>';
        }
    }
        



    if(strcmp(strtolower($value['setting_name']),'final_diagnosis')=='0')
    {
        
        if($users_data['parent_id']=='113')
        {
            $final_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;">
                <b> Diagnosis :</b>'.nl2br($booking_data_discharge['discharge_list'][0]->diagnosis).'
              
              </div>
            </div>';
        }
        else
        {
            if(!empty($value['setting_value'])) { $final_diagnosis_name =  $value['setting_value']; } else { $final_diagnosis_name =  $value['var_title']; }
            if(!empty($booking_data_discharge['discharge_list'][0]->final_diagnosis)){ 
            $final_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;">
                <b> '.$final_diagnosis_name.' :</b>'.nl2br($booking_data_discharge['discharge_list'][0]->final_diagnosis).'
              
              </div>
            </div>';
            } 
        }
    }    
    


    if(strcmp(strtolower($value['setting_name']),'course_in_hospital')=='0')
    { 
        if(!empty($value['setting_value'])) { $course_in_hospital_name =  $value['setting_value']; } else { $course_in_hospital_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->course_in_hospital)){ 
        $course_in_hospital = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$course_in_hospital_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->course_in_hospital).'
          </div>
        </div>';
        }
    }


    if(strcmp(strtolower($value['setting_name']),'investigation')=='0')
    { 
        if(!empty($value['setting_value'])) { $investigations_name =  $value['setting_value']; } else { $investigations_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->investigations)){ 
        $investigation = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$investigations_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->investigations).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value['setting_name']),'condition_at_discharge_time')=='0')
    { 
        if(!empty($value['setting_value'])) { $discharge_time_condition_name =  $value['setting_value']; } else { $discharge_time_condition_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->discharge_time_condition)){ 
        $condition_at_discharge_time = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$discharge_time_condition_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->discharge_time_condition).'
          </div>
        </div>';
        }
    }

    if(strcmp(strtolower($value['setting_name']),'advise_on_discharge')=='0')
    { 
        if(!empty($value['setting_value'])) { $discharge_time_condition_name =  $value['setting_value']; } else { $discharge_time_condition_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->discharge_advice)){ 
        $advise_on_aischarge = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$discharge_time_condition_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->discharge_advice).'
          </div>
        </div>';
        }
    }
    
   
   if(strcmp(strtolower($value['setting_name']),'review_time_and_date')=='0')
   {
       if(!empty($value['setting_value'])) 
       { 
            $review_time_date_name =  $value['setting_value']; } else { $review_time_date_name =  $value['var_title']; 
       }
       $time='';
       if(!empty($booking_data_discharge['discharge_list'][0]->review_time_date) && $booking_data_discharge['discharge_list'][0]->review_time_date!='00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='00:00:00 00:00:00')
       {
            $time = $booking_data_discharge['discharge_list'][0]->review_time;
       }
       if(!empty($booking_data_discharge['discharge_list'][0]->review_time) && $booking_data_discharge['discharge_list'][0]->review_time!='0000-00-00 00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='1970:01:01 00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='1970-01-01')
       {
        $review_time_and_date .= '<div style="float:left;width:100%;margin:1em 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$review_time_date_name.' :</b>
            '.date('d-m-Y',strtotime($booking_data_discharge['discharge_list'][0]->review_time_date)).' '.$time.'
          </div>
        </div>';
       }
    }

    //seprated vitals
               
               //echo "<pre>"; print_r($value); 
                if(strcmp(strtolower($value['setting_name']),'pulse')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_pulse)){ 
                    $vitals_pulse.= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$pulse_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_pulse).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'chest')=='0' && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $vitals_chest_name =  $value['setting_value']; } else { $vitals_chest_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_chest)){ 
                    $vitals_chest .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_chest_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_chest).'
                      </div>
                    </div>';
                    }
                }
               
                if(strcmp(strtolower($value['setting_name']),'bp')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $bp_name =  $value['setting_value']; } else { $bp_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_bp)){ 
                    $vitals_bp .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$bp_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_bp).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'cvs')=='0'  && $value['type'] =='vitals')
                { //echo $booking_data_discharge['discharge_list'][0]->vitals_cvs.''. strtolower($value['setting_name']);

                    if(!empty($value['setting_value'])) { $vitals_cvs_name =  $value['setting_value']; } else { $vitals_cvs_name =  $value['var_title']; }

                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cvs)){  


                    $vitals_cvs .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_cvs_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cvs).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'temp')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $vitals_temp_name =  $value['setting_value']; } else { $vitals_temp_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_temp)){ 
                    $vitals_temp .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_temp_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_temp).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'cns')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $cns_name =  $value['setting_value']; } else { $cns_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cns)){ 
                    $vitals_cns .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$cns_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cns).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'p_a')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $p_a_name =  $value['setting_value']; } else { $p_a_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_p_a)){ 
                    $vitals_p_a .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$p_a_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_p_a).'
                      </div>
                    </div>';
                    }
                }

    }
    
    
    $remarks='';
        if(!empty($booking_data_discharge['discharge_list'][0]->remarks)){ 
        $remarks = '<div style="float:left;width:100%;margin:1em 0 4em;">
          <div style="font-size:small;line-height:18px;">
           '.nl2br($booking_data_discharge['discharge_list'][0]->remarks).'
          </div>
        </div>';
        }
    
    
    
              $death_time_and_date='';
               $deathtime='';
               if(!empty($booking_data_discharge['discharge_list'][0]->death_date) && $booking_data_discharge['discharge_list'][0]->death_date!='00:00:00' && $booking_data_discharge['discharge_list'][0]->death_date!='00:00:00 00:00:00')
               {
                    $deathtime = $booking_data_discharge['discharge_list'][0]->death_time;
               }
               if(!empty($booking_data_discharge['discharge_list'][0]->death_time) && $booking_data_discharge['discharge_list'][0]->death_time!='0000-00-00 00:00:00' && $booking_data_discharge['discharge_list'][0]->death_date!='1970:01:01 00:00:00' && $booking_data_discharge['discharge_list'][0]->death_date!='1970-01-01')
               {
                $death_time_and_date .= '<div style="float:left;width:100%;margin:1em 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>Death Date Time :</b>
                    '.date('d-m-Y',strtotime($booking_data_discharge['discharge_list'][0]->death_date)).' '.$time.'
                  </div>
                </div>';
               }
            
    
    
    
   
    
    
     $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 20px;"> 
      <tr>
        <td align="left"><div>&nbsp;</div>
        </td><td width="80%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;">'.$booking_data_discharge['discharge_list'][0]->doctor_name.'</b></td>
      </tr>';
      
      
      
     
      $signature_reprt_data .='</table>';
    
    //echo $signature_reprt_data; die;
    $middle_replace_part = str_replace("{signature}", $signature_reprt_data,$middle_replace_part);
    
    
    $middle_replace_part = str_replace("{chief_complaints}",$chief_complaints,$middle_replace_part);
    $middle_replace_part = str_replace("{h_o_presenting_illness}",$h_o_presenting_illness,$middle_replace_part);
    $middle_replace_part = str_replace("{on_examination}",$on_examination,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals}",$vitals,$middle_replace_part);
    $middle_replace_part = str_replace("{provisional_diagnosis}",$provisional_diagnosis,$middle_replace_part);
    $middle_replace_part = str_replace("{final_diagnosis}",$final_diagnosis,$middle_replace_part);
    $middle_replace_part = str_replace("{course_in_hospital}",$course_in_hospital,$middle_replace_part);
    //$middle_replace_part = str_replace("{investigation}",$investigation,$middle_replace_part);
    $middle_replace_part = str_replace("{condition_at_discharge_time}",$condition_at_discharge_time,$middle_replace_part);
    $middle_replace_part = str_replace("{advise_on_discharge}",$advise_on_aischarge,$middle_replace_part);
    $middle_replace_part = str_replace("{review_time_and_date}",$review_time_and_date,$middle_replace_part);

    //
    $middle_replace_part = str_replace("{h_o_past}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{mlc_fir_no}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{ho_any_allergy}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{medicine_prescribed}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{lama_dama_reasons}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{refered_data}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{icd_code}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{admission_reason}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{family_history}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{alcohol_history}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{procedure_performedand}",'',$middle_replace_part);
     $middle_replace_part = str_replace("{nutritional_advice}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{consultants_name}",'',$middle_replace_part);

    //

    $middle_replace_part = str_replace("{vitals_pulse}",$vitals_pulse,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_chest}",$vitals_chest,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_bp}",$vitals_bp,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_cvs}",$vitals_cvs,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_temp}",$vitals_temp,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_cns}",$vitals_cns,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_p_a}",$vitals_p_a,$middle_replace_part);
    
    
    
    
    
     $discharge_master_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field_print($summary_id);
       // print_r($discharge_master_detail); exit;
        $total_values=array();
        for($i=0;$i<count($discharge_master_detail);$i++) 
        {
            $total_values[]= $discharge_master_detail[$i]->field_value.'__'.$discharge_master_detail[$i]->discharge_lable.'__'.$discharge_master_detail[$i]->field_id.'__'.$discharge_master_detail[$i]->type.'__'.$discharge_master_detail[$i]->discharge_short_code;
        }
      
       // $field_name = $total_values;
        
        if(!empty($total_values))
        { 
            $data_discharge='';
            foreach ($total_values as $field_names) 
            {
               //echo $tot_values[4];
                $tot_values= explode('__',$field_names);
                if(!empty($tot_values[0]))
                {
                     $data_discharge = '<div style="float:left;width:100%;margin:1em 0 0;">
                          <div style="font-size:small;line-height:18px;">
                            <b>'.ucfirst($tot_values[1]).' :</b>
                           '.nl2br($tot_values[0]).'
                          </div>
                        </div>';
                    $middle_replace_part = str_replace($tot_values[4],$data_discharge,$middle_replace_part);
                }
                else
                {
                    $middle_replace_part = str_replace($tot_values[4],'',$middle_replace_part); 
                }
               
                        

            } 
        }
    

        $prescription_medicine_tab_setting = get_dialysis_prescription_medicine_print_tab_setting();
      // echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
        if(!empty($summary_info['medicine_list'][0]->medicine_name) && $summary_info['medicine_list'][0]->medicine_name!=""){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="10" style="padding:4px;" valign="top">MEDICINE DETAILS:</th>
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

                
        }
        else{
          $medicine_data='';
        }
  
      
        /************ Medicine *************/

          /************ Investigation *************/
          $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($summary_id);
        //echo "<pre>"; print_r($discharge_summary_test_list); die;
        if(!empty($summary_info['investigation_test_list'][0]->test_name) && $summary_info['investigation_test_list'][0]->test_name!=""){
            $test_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="3" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
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

                 
        }
        
        
       
        $prescription_medicine_tab_setting = get_dialysis_prescription_medicine_print_tab_setting();
      // echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
      if(!empty($summary_info['medicine_list']))
      {
        if(!empty($prescription_medicine_tab_setting)){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="<?php echo count($prescription_medicine_tab_setting);  ?>" style="padding:4px;" valign="top">Medicine Prescribed:</th>
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

                  $medicine_list=$medicine_data;
        }
        else{
          $medicine_list='';
        }
    }
    else{
          $medicine_list='';
        }
  
    
       $middle_replace_part = str_replace("{medicine_detail}",$medicine_list,$middle_replace_part);
        /************ Investigation *************/
        
        
         $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($summary_id);

        //if(!empty($summary_info['investigation_test_list'][0]->test_name) && $summary_info['investigation_test_list'][0]->test_name!=""){
            $investigation_list_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="3" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left"  style="padding:4px;" valign="top">Test name</th>
                      <th  align="left"  style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" style="padding:4px;" valign="top">Result</th>
                      ';

              
                       
                       $i=0;
                       
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                    
                         $investigation_list_data .='<tr>';

                  $investigation_list_data .='<tr style="border-bottom:1px dashed #ccc;">
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->test_name.'</td>
                        <td align="left" style="padding:4px;" valign="top">'.$discharge_test_list->test_date.'</td>
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->result.'</td>
                      </tr>';

                      $i++; } 

             $investigation_list_data .='</tr></tbody>
                  </table>';

                 // $data['test_list']=$test_data;

    //echo $test_data; die;
    $middle_replace_part = str_replace("{medicine_prescribed}",$medicine_data,$middle_replace_part);
      $middle_replace_part = str_replace("{investigation}",$test_data,$middle_replace_part); 
      
       $middle_replace_part = str_replace("{investigation_list}",$investigation_list_data,$middle_replace_part);


       /*$middle_replace_part = str_replace("{past_history}", '', $middle_replace_part);
        $middle_replace_part = str_replace("{menstrual_history}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{obstetric_history}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{surgery_preferred}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{operation_notes}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{treatment_given}", '', $middle_replace_part);*/ 
        
        
        $middle_replace_part = str_replace("{personal_history}",$personal_history,$middle_replace_part);
        $middle_replace_part = str_replace("{birth_history}",$birth_history,$middle_replace_part);
        $middle_replace_part = str_replace("{general_examination}",$general_examination,$middle_replace_part);
        $middle_replace_part = str_replace("{systemic_examination}",$systemic_examination,$middle_replace_part);
        $middle_replace_part = str_replace("{local_examination}",$local_examination,$middle_replace_part);
        $middle_replace_part = str_replace("{diagnosis}",$diagnosis,$middle_replace_part);
        $middle_replace_part = str_replace("{specific_findings}",$specific_findings,$middle_replace_part);
        $middle_replace_part = str_replace("{remarks}",$remarks,$middle_replace_part);
        $middle_replace_part = str_replace("{summary_type}",$sumtype,$middle_replace_part);
        
        $middle_replace_part = str_replace("{past_history}",$past_history,$middle_replace_part);
        $middle_replace_part = str_replace("{obstetric_history}",$obstetric_history,$middle_replace_part);
        $middle_replace_part = str_replace("{menstrual_history}",$menstrual_history,$middle_replace_part);
        //echo $operation_notes; exit;
        $middle_replace_part = str_replace("{surgery_preferred}",$surgery_preferred,$middle_replace_part);
        $middle_replace_part = str_replace("{operation_notes}",$operation_notes,$middle_replace_part);
        $middle_replace_part = str_replace("{treatment_given}",$treatment_given,$middle_replace_part);
        
        //operation_notes
        
        $middle_replace_part = str_replace("{medicine_administered_detail}", '', $middle_replace_part);
         $middle_replace_part = str_replace("{medicine_detail}", '', $middle_replace_part); 
         
          // $middle_replace_part = str_replace("{created_fields}",$data_discharge,$middle_replace_part); 
           
        // $middle_replace_part = str_replace("{signature}", '',$middle_replace_part); 

      
        //echo $middle_replace_part; die;
        //echo "<pre>"; print_r($report_templ_part->page_footer);die;
        $footer_data_part = $report_templ_part->page_footer;

   // $footer_data_part=str_replace("{doctor_name}",$doctor_name,$footer_data_part);
   // $footer_data_part=str_replace("{signature}",$signature,$footer_data_part);
 // echo $middle_replace_part;die;

        ////////////////////

      $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 

    
   
     if($type=='Download')
    {
        if($template_format->header_pdf==1)
        {
           $page_header = $report_templ_part->page_header;
           $page_header = str_replace("{summary_type}",$sumtype,$page_header);
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$report_templ_part->page_header.'</div>';
           $page_header = str_replace("{summary_type}",$sumtype,$page_header);
        }

        if($template_format->details_pdf==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_format->middle_pdf==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_format->footer_pdf==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
        }
       $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part);
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf'; 
        $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
        // $this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "F");
        
    }
    else 
    { 

    // echo $middle_replace_part;die;
        
        if($template_format->header_print==1)
        {
           $page_header = $report_templ_part->page_header;
           $page_header = str_replace("{summary_type}",$sumtype,$page_header);
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$report_templ_part->page_header.'</div><br></br>';
           $page_header = str_replace("{summary_type}",$sumtype,$page_header);
        }

        if($template_format->details_print==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_format->middle_print==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_format->footer_print==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
           $footer_data_part = '<p style="height:'.$template_format->pixel_value.'px;"></p>'; 
            $this->m_pdf->pdf->defaultfooterline = 0;
        }

        

        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part); 
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
    }  
  
  
      
    }

}
?>