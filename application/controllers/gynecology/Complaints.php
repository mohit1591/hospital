<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Complaints extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('gynecology/complaints/Complaint_model','complaints');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        
        unauthorise_permission('302','1795');
        $data['page_title'] = 'Complaints List'; 
        $this->load->view('gynecology/complaints/list',$data);
    }


    public function ajax_list()
    { 
       
        unauthorise_permission('302','1795');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->complaints->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $complaints_list) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($complaints_list->status==1)
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
            
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$complaints_list->id.'">'.$check_script; 
            $row[] = $complaints_list->complaints;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($complaints_list->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1797',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_complaints('.$complaints_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$complaints_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1798',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_complaints('.$complaints_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->complaints->count_all(),
                        "recordsFiltered" => $this->complaints->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {

        unauthorise_permission('302','1796');
        $data['page_title'] = "Add Complaints";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'complaints'=>"",
                                  'status'=>"1"
                                  );    
        //print_r($post);die;

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->complaints->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/complaints/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('302','1797');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->complaints->get_by_id($id);  
        $data['page_title'] = "Update Complaints";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'complaints'=>$result['complaints'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->complaints->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/complaints/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('complaints', 'complaints', 'trim|required|callback_complaints'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'complaints'=>$post['complaints'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function complaints($str){
 
          $post = $this->input->post();
          if(!empty($post['complaints']))
          {
               $this->load->model('gynecology/general/gynecology_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->complaints->get_by_id($post['data_id']);
                      if($data_cat['complaints']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $complain = $this->general->complaints($str);

                        if(empty($complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('complaints', 'The complaints already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $complain = $this->general->complaints($post['complaints'], $post['data_id']);
                    if(empty($complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('complaints', 'The complaints already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('complaints', 'complaints field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('302','1798');
       if(!empty($id) && $id>0)
       {
           $result = $this->complaints->delete($id);
           $response = "Complaints Successfully deleted.";
           echo $response;
       }
    }


    function deleteall()
    {
       unauthorise_permission('302','1798');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->complaints->deleteall($post['row_id']);
            $response = "Complaints successfully deleted.";
            echo $response;
        }
    }

   

    ///// employee Archive Start  ///////////////
    public function archive()
    {
      unauthorise_permission('302','1799');
        $data['page_title'] = 'Complaints Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/complaints/archive',$data);
    }


    public function archive_ajax_list()
    {
        unauthorise_permission('302','1799');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('gynecology/complaints/Complaint_archive_model','complaints_archive'); 

        $list = $this->complaints_archive->get_datatables(); 
       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $complaints_archive) { 
            $no++;
            $row = array();
            if($complaints_archive->status==1)
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
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$complaints_archive->id.'">'.$check_script; 
            $row[] = $complaints_archive->complaints;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($complaints_archive->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1801',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_complaints('.$complaints_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1800',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$complaints_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->complaints_archive->count_all(),
                        "recordsFiltered" => $this->complaints_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('302','1801');
        $this->load->model('gynecology/complaints/Complaint_archive_model','complaints_archive'); 

        //$this->load->model('gynecology/complaints/chiefcomplaint_archive_model','chief_complaints_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->complaints_archive->restore($id);
           $response = "Complaint successfully restore in Complaints list.";
           echo $response;
       }
    }


    function restoreall()
    { 
       unauthorise_permission('302','1801');
         $this->load->model('gynecology/complaints/Complaint_archive_model','complaints_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->complaints_archive->restoreall($post['row_id']);
            $response = "Complaints successfully restore in Complaints list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('302','1800');
        $this->load->model('gynecology/complaints/Complaint_archive_model','complaints_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->complaints_archive->trash($id);
           $response = "Complaints successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('302','1800');
       $this->load->model('gynecology/complaints/Complaint_archive_model','complaints_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->complaints_archive->trashall($post['row_id']);
            $response = "Complaints successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function complaints_dropdown()
  {
      //echo "hi";die;
      $complaints_list = $this->complaints->complaints_list();
      $dropdown = '<option value="">Select complaint</option>'; 
      if(!empty($complaints_list))
      {
        foreach($complaints_list as $complaints)
        {
           $dropdown .= '<option value="'.$complaints->id.'">'.$complaints->complaints.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>