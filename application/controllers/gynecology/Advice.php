<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advice extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('gynecology/advice/Advice_model','advice');
        $this->load->library('form_validation');
    }

    public function index()
    { 
       
        //unauthorise_permission('299','1774');
        $data['page_title'] = 'Advice List'; 
        $this->load->view('gynecology/advice/list',$data);
    }

   

    public function ajax_list()
    { 
       
        //unauthorise_permission('299','1774');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->advice->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $advice) {
         // print_r($advice);die;
            $no++;
            $row = array();
            if($advice->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else
            {
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$advice->id.'">'.$check_script; 
            $row[] = $advice->advice;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($advice->created_date)); 
           
          $btnedit='';
          $btndelete='';
          //if(in_array('1776',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_advice('.$advice->id.');" class="btn-custom" href="javascript:void(0)" style="'.$advice->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          //}
          //if(in_array('1777',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_advice('.$advice->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          //}
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->advice->count_all(),
                        "recordsFiltered" => $this->advice->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       //unauthorise_permission('299','1775');
       
        $data['page_title'] = "Add Advice";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'advice'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->advice->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/advice/add',$data);       
    }

    
    public function edit($id="")
    {
       //unauthorise_permission('299','1776');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->advice->get_by_id($id);  
        $data['page_title'] = "Update Advice";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'advice'=>$result['advice'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->advice->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/advice/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('advice', 'advice', 'trim|required|callback_advice'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'advice'=>$post['advice'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function advice($str){
 
          $post = $this->input->post();
          if(!empty($post['advice']))
          {
               $this->load->model('gynecology/general/Gynecology_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->advice->get_by_id($post['data_id']);
                      if($data_cat['advice']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_complain = $this->general->check_advice($str);

                        if(empty($check_complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('advice', 'The advice already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_advice($post['advice'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('advice', 'The advice already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('advice', 'advice field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
        //unauthorise_permission('299','1777');
       if(!empty($id) && $id>0)
       {
           $result = $this->advice->delete($id);
           $response = "Advice Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('299','1777');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advice->deleteall($post['row_id']);
            $response = "Advice successfully deleted.";
            echo $response;
        }
    }

      


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('299','1778');
        $data['page_title'] = 'Advice Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/advice/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('299','1778');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('gynecology/advice/advice_archive_model','advice_archive'); 

        $list = $this->advice_archive->get_datatables(); 
       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $advice) { 
            $no++;
            $row = array();
            if($advice->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$advice->id.'">'.$check_script; 
            $row[] = $advice->advice;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($advice->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          //if(in_array('1780',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_advice('.$advice->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          //}
          //if(in_array('1779',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$advice->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           //}
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->advice_archive->count_all(),
                        "recordsFiltered" => $this->advice_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         //unauthorise_permission('299','1780');
        $this->load->model('gynecology/advice/advice_archive_model','advice_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->advice_archive->restore($id);
           $response = "Advice successfully restore in Advice list.";
           echo $response;
       }
    }

    function restoreall()
    { 
         //unauthorise_permission('299','1780');
      $this->load->model('gynecology/advice/advice_archive_model','advice_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advice_archive->restoreall($post['row_id']);
            $response = "Advice successfully restore in Advice list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         //unauthorise_permission('299','1779');
           $this->load->model('gynecology/advice/advice_archive_model','advice_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->advice_archive->trash($id);
           $response = "Advice successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
         //unauthorise_permission('299','1779');
       $this->load->model('gynecology/advice/advice_archive_model','advice_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advice_archive->trashall($post['row_id']);
            $response = "Advice successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function advice_dropdown()
  {

      $advice_list = $this->advice->advice_list();
      $dropdown = '<option value="">Select advice</option>'; 
      if(!empty($advice_list))
      {
        foreach($advice_list as $advice)
        {
           $dropdown .= '<option value="'.$advice->id.'">'.$advice->advice.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>