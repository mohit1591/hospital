<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chief_complaints extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pediatrician/chief_complaints/Chiefcomplaint_model','chief_complaints');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('339','1964');
        $data['page_title'] = 'Chief Complaints List'; 
        $this->load->view('pediatrician/chief_complaints/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('339','1964');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->chief_complaints->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $chief_complaints) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($chief_complaints->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$chief_complaints->id.'">'.$check_script; 
            $row[] = $chief_complaints->chief_complaints;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1966',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_chief_complaints('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" style="'.$chief_complaints->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1967',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_chief_complaints('.$chief_complaints->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->chief_complaints->count_all(),
                        "recordsFiltered" => $this->chief_complaints->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('339','1965');
        $data['page_title'] = "Add Chief Complaints";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'chief_complaints'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->chief_complaints->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/chief_complaints/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('339','1966');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->chief_complaints->get_by_id($id);  
        $data['page_title'] = "Update Chief Complaints";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'chief_complaints'=>$result['chief_complaints'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->chief_complaints->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/chief_complaints/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('chief_complaints', 'chief complaints', 'trim|required|callback_chief_complaints'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'chief_complaints'=>$post['chief_complaints'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function chief_complaints($str){
 
          $post = $this->input->post();
          if(!empty($post['chief_complaints']))
          {
               $this->load->model('pediatrician/general/pediatrician_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->chief_complaints->get_by_id($post['data_id']);
                      if($data_cat['chief_complaints']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_complain = $this->general->check_complain($str);

                        if(empty($check_complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('chief_complaints', 'The cheif complain already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_complain($post['chief_complaints'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('chief_complaints', 'The cheif complain already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('chief_complaints', 'Cheif Complain field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('339','1967');
       if(!empty($id) && $id>0)
       {
           $result = $this->chief_complaints->delete($id);
           $response = "Chief Complaints Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('339','1967');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->chief_complaints->deleteall($post['row_id']);
            $response = "Chief Complaints successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->chief_complaints->get_by_id($id);  
        $data['page_title'] = $data['form_data']['chief_complaints']." detail";
        $this->load->view('pediatrician/chief_complaints/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('339','1968');
        $data['page_title'] = 'chief Complaints Archive List';
        $this->load->helper('url');
        $this->load->view('pediatrician/chief_complaints/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('339','1968');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('pediatrician/chief_complaints/chiefcomplaint_archive_model','chief_complaints_archive'); 

        $list = $this->chief_complaints_archive->get_datatables(); 
       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $chief_complaints) { 
            $no++;
            $row = array();
            if($chief_complaints->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$chief_complaints->id.'">'.$check_script; 
            $row[] = $chief_complaints->chief_complaints;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1970',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_chief_complaints('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1969',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->chief_complaints_archive->count_all(),
                        "recordsFiltered" => $this->chief_complaints_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('339','1970');
        $this->load->model('pediatrician/chief_complaints/chiefcomplaint_archive_model','chief_complaints_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->chief_complaints_archive->restore($id);
           $response = "Chief Complaint successfully restore in Chief Complaints list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('339','1970');
        $this->load->model('pediatrician/chief_complaints/chiefcomplaint_archive_model','chief_complaints_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->chief_complaints_archive->restoreall($post['row_id']);
            $response = "Chief Complaints successfully restore in Chief Complaints list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('339','1969');
        $this->load->model('pediatrician/chief_complaints/chiefcomplaint_archive_model','chief_complaints_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->chief_complaints_archive->trash($id);
           $response = "Chief Complaints successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('339','1969');
        $this->load->model('pediatrician/chief_complaints/chiefcomplaint_archive_model','chief_complaints_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->chief_complaints_archive->trashall($post['row_id']);
            $response = "Chief Complaints successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function chief_complaints_dropdown()
  {

      $chief_complaints_list = $this->chief_complaints->chief_complaints_list();
      $dropdown = '<option value="">Select chief complaints</option>'; 
      if(!empty($chief_complaints_list))
      {
        foreach($chief_complaints_list as $chief_complaints)
        {
           $dropdown .= '<option value="'.$chief_complaints->id.'">'.$chief_complaints->chief_complaints.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>