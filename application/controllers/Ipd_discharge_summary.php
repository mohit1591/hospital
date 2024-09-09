<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_discharge_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_discharge_summary/ipd_discharge_summary_model','ipd_discharge_summary');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('111','684');
        $data['page_title'] = 'IPD Discharge Summary List'; 
        $this->load->view('ipd_discharge_summary/list',$data);
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
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_discharge_summary->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_discharge_summary->branch_id)
            {
              if(in_array('686',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_discharge_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_discharge_summary->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('687',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_discharge_summary('.$ipd_discharge_summary->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
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
        unauthorise_permission('111','685');
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
                                  'provisional_diagnosis'=>"",
                                  'final_diagnosis'=>"",
                                  'course_in_hospital'=>"",
                                  'investigations'=>"",
                                  'discharge_time_condition'=>"",
                                  'discharge_advice'=>"",
                                  'review_time_date'=>date('d-m-Y'),
                                  'review_time'=>date('h:i:s'),
                                   'status'=>"1",
                              );    

        if(isset($post) && !empty($post))
        {   
            // echo "<pre>"; print_r($post);die;
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_discharge_summary->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                
            }     
        }
      $this->load->model('discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
      //$data['discharge_labels_setting_list'] = $this->discharge_labels_setting->get_active_master_unique();
      //$data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_active_vital_master_unique();

$discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique();
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique();
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
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array();
      $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
      $this->load->view('ipd_discharge_summary/add',$data);       
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
     unauthorise_permission('111','686');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_discharge_summary->get_by_id($id);  
        $data['page_title'] = "Update IPD Discharge Summary";  
        $post = $this->input->post();

        $this->db->where('discharge_summary_id', $id);
        $query = $this->db->get('hms_ipd_discharge_summery_medicine');
        $discharge_summery_medicine = $query->result();

        $this->db->where('discharge_summary_id', $id);
        $query1 = $this->db->get('hms_ipd_discharge_summary_test');
        $discharge_summary_test = $query1->result();

        $data['form_error'] = ''; 
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
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
                                  'review_time_date'=>$result['review_time_date'],
                                  'review_time_date'=>date('d-m-Y',strtotime($result['review_time_date'])), 
                                  'review_time'=>$result['review_time'],
                                  'status'=>$result['status'],
                                  'discharge_summery_medicine' => $discharge_summery_medicine,
                                  'discharge_summary_test' => $discharge_summary_test
                                  
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_discharge_summary->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        $this->load->model('discharge_labels_setting/discharge_labels_setting_model','discharge_labels_setting');
        //$data['discharge_labels_setting_list'] = $this->discharge_labels_setting->get_active_master_unique();
        //$data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_active_vital_master_unique();

$discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique();
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique();
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
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array();
        $this->load->view('ipd_discharge_summary/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required');
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
                                        'name'=>$post['name'],
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
           $response = "IPD Discharge Summary successfully deleted.";
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
        $this->load->view('ipd_discharge_summary/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('111','688');
        $data['page_title'] = 'IPD Discharge Summary Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_discharge_summary/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('111','688');
        $this->load->model('ipd_discharge_summary/ipd_discharge_summary_archive_model','ipd_discharge_summary_archive'); 
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
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_discharge_summary->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('690',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_discharge_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
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
        $this->load->model('ipd_discharge_summary/ipd_discharge_summary_archive_model','ipd_discharge_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_summary_archive->restore($id);
           $response = "IPD Discharge Summary successfully restore in IPD Discharge Summary List.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('111','690');
        $this->load->model('ipd_discharge_summary/ipd_discharge_summary_archive_model','ipd_discharge_summary_archive');
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
        unauthorise_permission('111','689');
        $this->load->model('ipd_discharge_summary/ipd_discharge_summary_archive_model','ipd_discharge_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_summary_archive->trash($id);
           $response = "IPD Discharge Summary successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('111','689');
        $this->load->model('ipd_discharge_summary/ipd_discharge_summary_archive_model','ipd_discharge_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary_archive->trashall($post['row_id']);
            $response = "IPD Discharge Summary successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  // public function ipd_discharge_summary_dropdown()
  // {
  //    $ipd_discharge_summary_list = $this->ipd_discharge_summary->ipd_discharge_summary_list();
  //    $dropdown = '<option value="">Select ipd_discharge_summary</option>'; 
  //    $ipd_discharge_summarys_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
  //    if(!empty($ipd_discharge_summary_list))
  //    {
  //         foreach($ipd_discharge_summary_list as $ipd_discharge_summary)
  //         {
  //              if(in_array($ipd_discharge_summary->ipd_discharge_summary,$ipd_discharge_summarys_array)){
  //                   $selected_ipd_discharge_summary = 'selected="selected"';
  //              }
  //              else
  //              {
  //                 $selected_ipd_discharge_summary = '';  
  //              }
  //              $dropdown .= '<option value="'.$ipd_discharge_summary->id.'" '.$selected_ipd_discharge_summary.' >'.$ipd_discharge_summary->ipd_discharge_summary.'</option>';
  //         }
  //    } 
  //    echo $dropdown; 
  // }
  

}
?>