<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Systemic_illness extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/systemic_illness/Systemicillness_model','systemic_illness');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('237','1349');
        $data['page_title'] = 'Systemic Illness List'; 
        $this->load->view('eye/systemic_illness/list',$data);
    }

    public function ajax_list()
    { 
       
       unauthorise_permission('237','1349');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->systemic_illness->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $systemic_illness) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($systemic_illness->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$systemic_illness->id.'">'.$check_script; 
            $row[] = $systemic_illness->systemic_illness;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($systemic_illness->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1351',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_systemic_illness('.$systemic_illness->id.');" class="btn-custom" href="javascript:void(0)" style="'.$systemic_illness->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1352',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_systemic_illness('.$systemic_illness->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->systemic_illness->count_all(),
                        "recordsFiltered" => $this->systemic_illness->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('237','1350');
        $data['page_title'] = "Add Systemic Illness";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'systemic_illness'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->systemic_illness->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/systemic_illness/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('237','1351');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->systemic_illness->get_by_id($id);  
        $data['page_title'] = "Update Systemic Illness";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'systemic_illness'=>$result['systemic_illness'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->systemic_illness->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/systemic_illness/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('systemic_illness', 'systemic illness', 'trim|required|callback_systemic_illness'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'systemic_illness'=>$post['systemic_illness'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function systemic_illness($str){
 
          $post = $this->input->post();
          if(!empty($post['systemic_illness']))
          {
               $this->load->model('eye/general/eye_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->systemic_illness->get_by_id($post['data_id']);
                      if($data_cat['systemic_illness']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $systemic_illness = $this->general->check_systemic_illness($str);

                        if(empty($systemic_illness))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('systemic_illness', 'The systemic illness already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $systemic_illness = $this->general->check_systemic_illness($post['systemic_illness'], $post['data_id']);
                    if(empty($systemic_illness))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('systemic_illness', 'The systemic illness already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('systemic_illness', 'The systemic illness field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('237','1352');
       if(!empty($id) && $id>0)
       {
           $result = $this->systemic_illness->delete($id);
           $response = "Systemic Illness Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('237','1352');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->systemic_illness->deleteall($post['row_id']);
            $response = "Systemic Illness successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->systemic_illness->get_by_id($id);  
        $data['page_title'] = $data['form_data']['systemic_illness']." detail";
        $this->load->view('eye/systemic_illness/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('237','1353');
        $data['page_title'] = 'Systemic Illness Archive List';
        $this->load->helper('url');
        $this->load->view('eye/systemic_illness/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('237','1353');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('eye/systemic_illness/systemicillness_archive_model','systemic_illness_archive'); 

        $list = $this->systemic_illness_archive->get_datatables(); 
       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $systemic_illness) { 
            $no++;
            $row = array();
            if($systemic_illness->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$systemic_illness->id.'">'.$check_script; 
            $row[] = $systemic_illness->systemic_illness;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($systemic_illness->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1355',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_systemic_illness('.$systemic_illness->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1354',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$systemic_illness->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->systemic_illness_archive->count_all(),
                        "recordsFiltered" => $this->systemic_illness_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('237','1355');
         $this->load->model('eye/systemic_illness/systemicillness_archive_model','systemic_illness_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->systemic_illness_archive->restore($id);
           $response = "Systemic Illness successfully restore in Systemic Illness list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('237','1355');
        $this->load->model('eye/systemic_illness/systemicillness_archive_model','systemic_illness_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->systemic_illness_archive->restoreall($post['row_id']);
            $response = "Systemic Illness successfully restore in Systemic illness list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('237','1354');
       $this->load->model('eye/systemic_illness/systemicillness_archive_model','systemic_illness_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->systemic_illness_archive->trash($id);
           $response = "Systemic Illness successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('237','1354');
       $this->load->model('eye/systemic_illness/systemicillness_archive_model','systemic_illness_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->systemic_illness_archive->trashall($post['row_id']);
            $response = "Systemic Illness successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function systemic_illness_dropdown()
  {

      $systemic_illness_list = $this->systemic_illness->systemic_illness_list();
      $dropdown = '<option value="">Select Systemic illness</option>'; 
      if(!empty($systemic_illness_list))
      {
        foreach($systemic_illness_list as $systemic_illness)
        {
           $dropdown .= '<option value="'.$systemic_illness->id.'">'.$systemic_illness->systemic_illness.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>