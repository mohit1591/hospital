<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Duration extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/duration/duration_model','duration');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('290','1717');
        $data['page_title'] = 'Duration List'; 
        $this->load->view('dental/duration/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('290','1717');
        $list = $this->duration->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $duration) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($duration->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$duration->id.'">'.$check_script; 
            $row[] = $duration->medicine_dosage_duration;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($duration->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('1719',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_duration('.$duration->id.');" class="btn-custom" href="javascript:void(0)" style="'.$duration->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1720',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_duration('.$duration->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->duration->count_all(),
                        "recordsFiltered" => $this->duration->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('290','1718');
        $data['page_title'] = "Add Duration";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine_dosage_duration'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->duration->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/duration/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('290','1719');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->duration->get_by_id($id);  
        $data['page_title'] = "Update Duration";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_dosage_duration'=>$result['medicine_dosage_duration'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->duration->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/duration/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_dosage_duration', 'duration', 'trim|required|callback_medicine_dosage_duration'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_dosage_duration'=>$post['medicine_dosage_duration'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
     /* callbackurl */
    /* check validation laready exit */

    public function medicine_dosage_duration($str){
 
          $post = $this->input->post();
          if(!empty($post['medicine_dosage_duration']))
          {
               $this->load->model('dental/general/dental_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->duration->get_by_id($post['data_id']);
                      if($data_cat['medicine_dosage_duration']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_duration = $this->general->check_duration($str);

                        if(empty($check_duration))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('medicine_dosage_duration', 'The duration already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $check_duration = $this->general->check_duration($post['medicine_dosage_duration'], $post['data_id']);
                    if(empty($check_duration))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('medicine_dosage_duration', 'The duration already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('medicine_dosage_duration', 'The duration field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('290','1720');
       if(!empty($id) && $id>0)
       {
           $result = $this->duration->delete($id);
           $response = "Duration successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('290','1720');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->duration->deleteall($post['row_id']);
            $response = "Duration successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->duration->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_dosage_duration']." detail";
        $this->load->view('eye/duration/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('290','1721');
        $data['page_title'] = 'Duration Archive List';
        $this->load->helper('url');
        $this->load->view('dental/duration/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('290','1721');
        $this->load->model('dental/duration/duration_archive_model','duration_archive'); 

        $list = $this->duration_archive->get_datatables();  
         //print_r( $list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $duration) { 
            $no++;
            $row = array();
            if($duration->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$duration->id.'">'.$check_script; 
            $row[] = $duration->medicine_dosage_duration;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($duration->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1723',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_duration('.$duration->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1722',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$duration->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->duration_archive->count_all(),
                        "recordsFiltered" => $this->duration_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('290','1723');
        $this->load->model('dental/duration/duration_archive_model','duration_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->duration_archive->restore($id);
           $response = "Duration successfully restore in Duration list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('290','1723');
        $this->load->model('dental/duration/duration_archive_model','duration_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->duration_archive->restoreall($post['row_id']);
            $response = "Duration successfully restore in Duration list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('290','1722');
        $this->load->model('dental/duration/duration_archive_model','duration_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->duration_archive->trash($id);
           $response = "Duration successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('290','1722');
        $this->load->model('dental/duration/duration_archive_model','duration_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->duration_archive->trashall($post['row_id']);
            $response = "Duration successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function duration_dropdown()
  {

      $duration_list = $this->duration->duration_list();
      $dropdown = '<option value="">Select Duration</option>'; 
      if(!empty($duration_list))
      {
        foreach($duration_list as $duration)
        {
           $dropdown .= '<option value="'.$duration->id.'">'.$duration->medicine_dosage_duration.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>