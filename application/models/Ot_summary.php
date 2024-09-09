<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_summary/ot_summary_model','otsummary');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('73','460');
        $data['page_title'] = 'Operation Summary List'; 
        $this->load->view('ot_summary/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('73','460');
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
            $row[] = $ot->medicine;
            $row[] = $ot->type;  
            $row[] = $ot->salt;
            $row[] = $ot->brand;      
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($ot->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('462',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_medicine('.$ot->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('463',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_medicine('.$ot->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
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
        unauthorise_permission('73','461');
        $data['page_title'] = "Add Operation Summary";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'name'=>"",
                                  'diagnosis'=>"",
                                  'operation'=>"",
                                  'op_findings'=>"",
                                  'procedures'=>'',
                                  'pos_op_orders'=>'',
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
       $this->load->view('ot_summary/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('73','462');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->otsummary->get_by_id($id);  
        $data['page_title'] = "Update Operation Summary";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine'=>$result['medicine'], 
                                  'type'=>$result['type'],
                                  'salt'=>$result['salt'],
                                  'brand'=>$result['brand'],
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
       $this->load->view('ot_summary/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine', 'medicine', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine'=>$post['medicine'],
                                        'type'=>$post['type'],
                                        'salt'=>$post['salt'],
                                        'brand'=>$post['brand'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('73','463');
       if(!empty($id) && $id>0)
       {
           $result = $this->otsummary->delete($id);
           $response = "Operation summary successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('73','463');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->otsummary->deleteall($post['row_id']);
            $response = "Operation summary successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->otsummary->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine']." detail";
        $this->load->view('ot_summary/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('73','464');
        $data['page_title'] = 'Operation Summary Archive List';
        $this->load->helper('url');
        $this->load->view('ot_summary/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('73','464');
        $this->load->model('ot_summary/ot_summary_archive_model','ot_summary_archive'); 

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
            $row[] = $medicine->medicine;
            $row[] = $medicine->type;  
            $row[] = $medicine->salt;
            $row[] = $medicine->brand;   
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($medicine->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('466',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_medicine('.$medicine->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('465',$users_data['permission']['action'])){
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
        unauthorise_permission('73','466');
        $this->load->model('ot_summary/ot_summary_archive_model','ot_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_summary_archive->restore($id);
           $response = "Medicine successfully restore in Medicine list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('73','466');
        $this->load->model('ot_summary/ot_summary_archive_model','ot_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_summary_archive->restoreall($post['row_id']);
            $response = "Medicine successfully restore in Medicine list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('73','465');
        $this->load->model('ot_summary/ot_summary_archive_model','ot_summary_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_summary_archive->trash($id);
           $response = "Medicine successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('73','465');
        $this->load->model('ot_summary/ot_summary_archive_model','ot_summary_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_summary_archive->trashall($post['row_id']);
            $response = "Operation summary successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ot_summary_dropdown()
  {
	  $medicine_list = $this->otsummary->ot_summary_list();
      $dropdown = '<option value="">Select Medicine</option>'; 
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