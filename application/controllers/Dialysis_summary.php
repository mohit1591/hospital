<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dialysis_summary/dialysis_summary_model','dialysissummary');
        $this->load->library('form_validation');
    }

    public function index()
    { 
       // unauthorise_permission('206','1169');
        $data['page_title'] = 'Dialysis Summary Template List'; 
        $this->load->view('dialysis_summary/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission('206','1169');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->dialysissummary->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $tdialysisal_num = count($list);
        foreach ($list as $dialysis) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($dialysis->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$tdialysisal_num)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysis->id.'">'.$check_script; 
            $row[] = $dialysis->name;
            //$row[] = $dialysis->diagnosis;  
            //$row[] = $dialysis->dialysis_name;
            //$row[] = $dialysis->dialysis_findings;
            //$row[] = $dialysis->procedures;
            //$row[] = $dialysis->pos_dialysis_orders;      
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dialysis->created_date)); 
           
          $btnedit='';
          $btndelete='';
          //if(in_array('1171',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_dialysis_summary('.$dialysis->id.');" class="btn-custom" href="javascript:void(0)" style="'.$dialysis->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          //}
           //if(in_array('1172',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_dialysis_summary('.$dialysis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          //}
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTdialysisal" => $this->dialysissummary->count_all(),
                        "recordsFiltered" => $this->dialysissummary->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    

    
    public function add()
    {
        //unauthorise_permission('206','1170');
        $data['page_title'] = "Add Dialysis Summary Template"; 
         $data['dialysis_management_list']=$this->dialysissummary->management_list(); 
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'name'=>"",
                                  'diagnosis'=>"",
                                //  'dialysis'=>"",
                                  'dialysis_name_id'=>"",
                                  'dialysis_findings'=>"",
                                  'procedures'=>'',
                                  'pos_dialysis_orders'=>'',
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dialysissummary->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        
        $this->load->model('dialysis_patient_summary/dialysis_patient_summary_model','ipd_discharge_summary');
        $data['discharge_field_master_list'] = $this->ipd_discharge_summary->discharge_field_master_list(1,1);
        
        $get_payment_detail= $this->dialysissummary->discharge_master_detail_by_field();
      //print_r($get_payment_detail); exit;
      $total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;
        
       $this->load->view('dialysis_summary/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('206','1171');
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->dialysissummary->get_by_id($id);  
         $data['dialysis_management_list']=$this->dialysissummary->management_list();
        $data['page_title'] = "Update Dialysis Summary Template";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'name'=>$result['name'], 
                                  /*'diagnosis'=>$result['diagnosis'],
                                  'dialysis_name_id'=>$result['dialysis'],
                                  'dialysis_findings'=>$result['dialysis_findings'],
                                  'procedures'=>$result['procedures'],
                                  'pos_dialysis_orders'=>$result['pos_dialysis_orders'],*/
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dialysissummary->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        $this->load->model('dialysis_patient_summary/dialysis_patient_summary_model','ipd_discharge_summary');
        $data['discharge_test_list']= $this->ipd_discharge_summary->get_discharge_test($id);
        
         $get_payment_detail= $this->dialysissummary->discharge_master_detail_by_field($id);
      //print_r($get_payment_detail); exit;
      $total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;
      
        
       $this->load->view('dialysis_summary/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        // $this->form_validation->set_rules('dialysis_name_id', 'Dialysis', 'trim|required');  
         
         $total_values=array();
        if(isset($post['field_name'])) 
        {
          $count_field_names= count($post['field_name']);  
          $get_payment_detail= $this->dialysissummary->discharge_master_detail_by_field();
          //print_r($get_payment_detail); exit;
          $total_values=array();
          for($i=0;$i<count($get_payment_detail);$i++) 
          {
            $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->discharge_lable.'_'.$get_payment_detail[$i]->field_id.'_'.$get_payment_detail[$i]->type;
          }
          

        }
        
        
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'name'=>$post['name'],
                                        ///'diagnosis'=>$post['diagnosis'],
                                        //'dialysis_name_id'=>$post['dialysis_name_id'],
                                      //  'dialysis'=>$post['dialysis'],
                                       // 'dialysis_findings'=>$post['dialysis_findings'],
                                       // 'procedures'=>$post['procedures'],
                                       // 'pos_dialysis_orders'=>$post['pos_dialysis_orders'], 
                                        'status'=>$post['status'],
                                        "field_name"=>$total_values,
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('206','1172');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysissummary->delete($id);
           $response = "Dialysis summary successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
      // unauthorise_permission('206','1172');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysissummary->deleteall($post['row_id']);
            $response = "Dialysis summary successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->dialysissummary->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('dialysis_summary/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('206','1173');
        $data['page_title'] = 'Dialysis Summary Archive List';
        $this->load->helper('url');
        $this->load->view('dialysis_summary/archive',$data);
    }

    public function archive_ajax_list()
    {
       //unauthorise_permission('206','1173');
        $this->load->model('dialysis_summary/dialysis_summary_archive_model','dialysis_summary_archive'); 

        $list = $this->dialysis_summary_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $tdialysisal_num = count($list);
        foreach ($list as $medicine) { 
            $no++;
            $row = array();
            if($medicine->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$tdialysisal_num)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine->id.'">'.$check_script; 
            $row[] = $medicine->name;
            $row[] = $medicine->diagnosis;  
            $row[] = $medicine->dialysis_name;
            $row[] = $medicine->dialysis_findings;
            $row[] = $medicine->procedures;
            $row[] = $medicine->pos_dialysis_orders;   
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($medicine->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          //if(in_array('1175',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_dialysis_summary('.$medicine->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          //}
          //if(in_array('1174',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$medicine->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          //}
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTdialysisal" => $this->dialysis_summary_archive->count_all(),
                        "recordsFiltered" => $this->dialysis_summary_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       //unauthorise_permission('206','1175');
        $this->load->model('dialysis_summary/dialysis_summary_archive_model','dialysis_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_summary_archive->restore($id);
           $response = "Dialysis summary successfully restore in Dialysis summary list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('206','1175');
        $this->load->model('dialysis_summary/dialysis_summary_archive_model','dialysis_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_summary_archive->restoreall($post['row_id']);
            $response = "Dialysis summary successfully restore in Dialysis summary list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('206','1174');
        $this->load->model('dialysis_summary/dialysis_summary_archive_model','dialysis_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_summary_archive->trash($id);
           $response = "Dialysis summary successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       // unauthorise_permission('206','1174');
        $this->load->model('dialysis_summary/dialysis_summary_archive_model','dialysis_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_summary_archive->trashall($post['row_id']);
            $response = "Dialysis summary successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function dialysis_summary_dropdown()
  {
	  $medicine_list = $this->dialysissummary->dialysis_summary_list();
      $dropdown = '<option value="">Select Dialysis Summary</option>'; 
      if(!empty($medicine_list))
      {
        foreach($dialysis_summary_list as $dialysissummary)
        {
           $dropdown .= '<option value="'.$dialysissummary->id.'">'.$dialysissummary->name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  function get_vals($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->dialysissummary->get_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

}
?>