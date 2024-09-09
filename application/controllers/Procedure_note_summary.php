<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Procedure_note_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('procedure_note_summary/procedure_note_summary_model','otsummary');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('133','800');
        $data['page_title'] = 'Procedure Note Summary List'; 
        $this->load->view('procedure_note_summary/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('133','800');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->otsummary->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($ot->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot->id.'">'.$check_script; 
            $row[] = $ot->name;
            $row[] = $ot->diagnosis;  
            $row[] = $ot->operation_name;
            $row[] = $ot->op_findings;
            //$row[] = $ot->procedures;
            //$row[] = $ot->pos_op_orders;      
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ot->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('802',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_ot_summary('.$ot->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('803',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_ot_summary('.$ot->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->otsummary->count_all(),
                        "recordsFiltered" => $this->otsummary->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    

    
    public function add()
    {
     





        unauthorise_permission('133','801');
        $data['page_title'] = "Add Procedure Note Summary";  
        $post = $this->input->post();
        $data['operation_list']= $this->otsummary->get_operation_list();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'name'=>"",
                                  'diagnosis'=>"",
                                  'operation'=>"",
                                  'op_findings'=>"",
                                  'procedures'=>'',
                                  'pos_op_orders'=>'',
                                  'blood_transfusion'=>'',
                                  'blood_loss'=>'',
                                  'drain'=>'',
                                  'histopathological'=>'',
                                  'microbiological'=>'',
                                  'remark'=>'',
                                  
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->otsummary->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        $data['medicine_data']='';
        $data['rec_id']=0;
       $this->load->view('procedure_note_summary/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('133','802');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->otsummary->get_by_id($id);  
        $data['page_title'] = "Update Procedure Note Summary"; 
          $data['operation_list']= $this->otsummary->get_operation_list(); 
          $data['medicine_data']=$this->otsummary->get_summary_medicine_data($id);
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'name'=>$result['name'], 
                                  'diagnosis'=>$result['diagnosis'],
                                  'operation'=>$result['operation'],
                                  'op_findings'=>$result['op_findings'],
                                  'procedures'=>$result['procedures'],
                                  'pos_op_orders'=>$result['pos_op_orders'],
                                  'blood_transfusion'=>$result['blood_transfusion'],
                                  'blood_loss'=>$result['blood_loss'],
                                  'drain'=>$result['drain'],
                                  'histopathological'=>$result['histopathological'],
                                  'microbiological'=>$result['microbiological'],
                                  'remark'=>$result['remark'],
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->otsummary->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        $data['rec_id']=$id;
       $this->load->view('procedure_note_summary/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'name'=>$post['name'],
                                        'diagnosis'=>$post['diagnosis'],
                                        'operation'=>$post['operation'],
                                        'op_findings'=>$post['op_findings'],
                                        'procedures'=>$post['procedures'],
                                        'pos_op_orders'=>$post['pos_op_orders'], 'blood_transfusion'=>$post['blood_transfusion'],
                                  'blood_loss'=>$post['blood_loss'],
                                  'drain'=>$post['drain'],
                                  'histopathological'=>$post['histopathological'],
                                  'microbiological'=>$post['microbiological'],
                                  'remark'=>$post['remark'],
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('133','803');
       if(!empty($id) && $id>0)
       {
           $result = $this->otsummary->delete($id);
           $response = "Procedure Note summary successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('133','803');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->otsummary->deleteall($post['row_id']);
            $response = "Procedure Note summary successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->otsummary->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('procedure_note_summary/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('133','804');
        $data['page_title'] = 'Procedure Note Summary Archive List';
        $this->load->helper('url');
        $this->load->view('procedure_note_summary/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('133','804');
        $this->load->model('procedure_note_summary/procedure_note_summary_archive_model','ot_summary_archive'); 

        $list = $this->ot_summary_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine->id.'">'.$check_script; 
            $row[] = $medicine->name;
            $row[] = $medicine->diagnosis;  
            $row[] = $medicine->operation_name;
            $row[] = $medicine->op_findings;
            $row[] = $medicine->procedures;
            $row[] = $medicine->pos_op_orders;   
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($medicine->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('806',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ot_summary('.$medicine->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('803',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$medicine->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_summary_archive->count_all(),
                        "recordsFiltered" => $this->ot_summary_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission('133','806');
        $this->load->model('procedure_note_summary/procedure_note_summary_archive_model','ot_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_summary_archive->restore($id);
           $response = "Procedure Note summary successfully restore in Procedure Note summary list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('133','806');
        $this->load->model('procedure_note_summary/procedure_note_summary_archive_model','ot_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_summary_archive->restoreall($post['row_id']);
            $response = "Procedure Note summary successfully restore in Procedure Note summary list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('133','805');
        $this->load->model('procedure_note_summary/procedure_note_summary_archive_model','ot_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_summary_archive->trash($id);
           $response = "Procedure Note summary successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('133','805');
        $this->load->model('procedure_note_summary/procedure_note_summary_archive_model','ot_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_summary_archive->trashall($post['row_id']);
            $response = "Procedure Note summary successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ot_summary_dropdown()
  {
	  $medicine_list = $this->otsummary->ot_summary_list();
      $dropdown = '<option value="">Select Procedure Note Summary</option>'; 
      if(!empty($medicine_list))
      {
        foreach($ot_summary_list as $otsummary)
        {
           $dropdown .= '<option value="'.$otsummary->id.'">'.$otsummary->name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  function get_vals($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->otsummary->get_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

}
?>