<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relation extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('relation/relation_model','relation');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('11','57');
        $data['page_title'] = 'Relation list'; 
        $this->load->view('relation/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('11','57');
        $list = $this->relation->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $relation) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($relation->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$relation->id.'">'.$check_script; 
            $row[] = $relation->relation;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($relation->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('59',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_relation('.$relation->id.');" class="btn-custom" href="javascript:void(0)" style="'.$relation->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('60',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_relation('.$relation->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->relation->count_all(),
                        "recordsFiltered" => $this->relation->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('11','58');
        $data['page_title'] = "Add Relation";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'relation'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->relation->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('relation/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('11','59');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->relation->get_by_id($id);  
        $data['page_title'] = "Update Relation";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'relation'=>$result['relation'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->relation->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('relation/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('relation', 'relation', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'relation'=>$post['relation'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('11','60');
       if(!empty($id) && $id>0)
       {
           $result = $this->relation->delete($id);
           $response = "relation successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('11','60');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->relation->deleteall($post['row_id']);
            $response = "relations successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->relation->get_by_id($id);  
        $data['page_title'] = $data['form_data']['relation']." detail";
        $this->load->view('relation/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('11','61');
        $data['page_title'] = 'relation archive list';
        $this->load->helper('url');
        $this->load->view('relation/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('15','61');
        $this->load->model('relation/relation_archive_model','relation_archive'); 

        $list = $this->relation_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $relation) { 
            $no++;
            $row = array();
            if($relation->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$relation->id.'">'.$check_script; 
            $row[] = $relation->relation;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($relation->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('63',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_relation('.$relation->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('62',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$relation->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->relation_archive->count_all(),
                        "recordsFiltered" => $this->relation_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('11','63');
        $this->load->model('relation/relation_archive_model','relation_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->relation_archive->restore($id);
           $response = "Relation successfully restore in Relation list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('11','63');
        $this->load->model('relation/relation_archive_model','relation_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->relation_archive->restoreall($post['row_id']);
            $response = "Relation successfully restore in Relation list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('11','62');
        $this->load->model('relation/relation_archive_model','relation_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->relation_archive->trash($id);
           $response = "Relation successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('11','62');
        $this->load->model('relation/relation_archive_model','relation_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->relation_archive->trashall($post['row_id']);
            $response = "Relation successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function Relation_dropdown()
  {

      $relation_list = $this->relation->relation_list();
      $dropdown = '<option value="">Select Relation</option>'; 
      if(!empty($relation_list))
      {
        foreach($relation_list as $relation)
        {
           $dropdown .= '<option value="'.$relation->id.'">'.$relation->relation.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>