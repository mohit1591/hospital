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
        // unauthorise_permission('12','64');
        $data['page_title'] = 'IPD Discharge Summary List'; 
        $this->load->view('ipd_discharge_summary/list',$data);
    }

    public function ajax_list()
    { 
        // unauthorise_permission('12','64');
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
              if(in_array('66',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_discharge_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_discharge_summary->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('67',$users_data['permission']['action'])){
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
        // unauthorise_permission('12','65');
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
                                  'review_time_date'=>"",
                                   'status'=>"1",
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
     // unauthorise_permission('12','66');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_discharge_summary->get_by_id($id);  
        $data['page_title'] = "Update IPD Discharge Summary";  
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
                                  'review_time_date'=>$result['review_time_date'], 
                                  'status'=>$result['status'],
                                  
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
       $this->load->view('ipd_discharge_summary/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required'); 
        $this->form_validation->set_rules('chief_complaints', 'chief complaints', 'trim|required');
        $this->form_validation->set_rules('h_o_presenting', 'h/o presenting', 'trim|required');
        $this->form_validation->set_rules('on_examination', 'on examination', 'trim|required');
        $this->form_validation->set_rules('vitals_pulse', 'vitals pulse', 'trim|required');
        $this->form_validation->set_rules('vitals_chest', 'vitals chest', 'trim|required');
        $this->form_validation->set_rules('vitals_bp', 'vitals bp', 'trim|required');
        $this->form_validation->set_rules('vitals_cvs', 'vitals cvs', 'trim|required');
        $this->form_validation->set_rules('vitals_temp', 'vitals temp', 'trim|required');
        $this->form_validation->set_rules('vitals_p_a', 'vitals p/a', 'trim|required');
        $this->form_validation->set_rules('vitals_cns', 'vitals cns', 'trim|required');
        $this->form_validation->set_rules('provisional_diagnosis', 'provisional diagnosis', 'trim|required');
        $this->form_validation->set_rules('final_diagnosis', 'final diagnosis', 'trim|required');
        $this->form_validation->set_rules('course_in_hospital', 'course in hospital', 'trim|required');
        $this->form_validation->set_rules('investigations', 'investigations', 'trim|required');
        $this->form_validation->set_rules('discharge_time_condition', 'discharge time condition', 'trim|required');
        $this->form_validation->set_rules('discharge_advice', 'discharge advice', 'trim|required');
        $this->form_validation->set_rules('review_time_date', 'review_time_date', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'name'=>$post['name'],
                                        'summery_type'=>$post['summery_type'],
                                        'chief_complaints'=>$post['chief_complaints'],
                                        'h_o_presenting'=>$post['h_o_presenting'],
                                        'on_examination'=>$post['on_examination'],
                                        'vitals_pulse'=>$post['vitals_pulse'],
                                        'vitals_chest'=>$post['vitals_chest'],
                                        'vitals_bp'=>$post['vitals_bp'],
                                        'vitals_cvs'=>$post['vitals_cvs'],
                                        'vitals_temp'=>$post['vitals_temp'],
                                        'vitals_p_a'=>$post['vitals_p_a'],
                                        'vitals_cns'=>$post['vitals_cns'],
                                        'provisional_diagnosis'=>$post['provisional_diagnosis'],
                                        'final_diagnosis'=>$post['final_diagnosis'],
                                        'course_in_hospital'=>$post['course_in_hospital'],
                                        'investigations'=>$post['investigations'],
                                   'discharge_time_condition'=>$post['discharge_time_condition'],
                                        'discharge_advice'=>$post['discharge_advice'],
                                        'review_time_date'=>$post['review_time_date'], 
                                        'status'=>$post['status'],
                                        
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
        // unauthorise_permission('12','67');
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
        // unauthorise_permission('12','68');
        $data['page_title'] = 'IPD Discharge Summary Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_discharge_summary/archive',$data);
    }

    public function archive_ajax_list()
    {
        // unauthorise_permission('12','68');
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
            $row[] = date('d-M-Y H:i A',strtotime($ipd_discharge_summary->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('70',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_discharge_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('69',$users_data['permission']['action'])){
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
        // unauthorise_permission('12','70');
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
        // unauthorise_permission('12','70');
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
        // unauthorise_permission('12','69');
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
        // unauthorise_permission('12','69');
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