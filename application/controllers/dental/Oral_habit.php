<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oral_habit extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/oral_habit/Oral_habit_model','oral_habit');
        $this->load->library('form_validation');
    }

    public function index()
    { 
       // echo "hi";die;
        unauthorise_permission('280','1653');
        $data['page_title'] = ' Oral Habit List'; 
        $this->load->view('dental/oral_habit/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('280','1653');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->oral_habit->get_datatables();  
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
            $row[] = $chief_complaints->oral_habit_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1655',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_oral_habit('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" style="'.$chief_complaints->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1656',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_oral_habit('.$chief_complaints->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->oral_habit->count_all(),
                        "recordsFiltered" => $this->oral_habit->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('280','1654');
        $data['page_title'] = "Add Oral Habit";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'oral_habit_name'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->oral_habit->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/oral_habit/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('280','1655');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->oral_habit->get_by_id($id);  
        $data['page_title'] = "Update Oral Habit";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'oral_habit_name'=>$result['oral_habit_name'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->oral_habit->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/oral_habit/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('oral_habit_name', 'oral habit name', 'trim|required|callback_oral_habit_name'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'oral_habit_name'=>$post['oral_habit_name'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function oral_habit_name($str){
 
          $post = $this->input->post();
          if(!empty($post['oral_habit_name']))
          {
               $this->load->model('dental/general/dental_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->oral_habit->get_by_id($post['data_id']);
                      if($data_cat['oral_habit_name']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_complain = $this->general->check_oral_habit($str);

                        if(empty($check_complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('oral_habit_name', 'The oral habit already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_oral_habit($post['oral_habit_name'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('oral_habit_name', 'The oral habit name already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('oral_habit_name', 'oral habit field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('280','1656');
       if(!empty($id) && $id>0)
       {
           $result = $this->oral_habit->delete($id);
           $response = "Oral Habit Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('280','1656');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->oral_habit->deleteall($post['row_id']);
            $response = "Oral Habit successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->chief_complaints->get_by_id($id);  
        $data['page_title'] = $data['form_data']['chief_complaints']." detail";
        $this->load->view('eye/chief_complaints/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('280','1657');
        $data['page_title'] = 'Oral Habit Archive List';
        $this->load->helper('url');
        $this->load->view('dental/oral_habit/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('280','1657');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('dental/oral_habit/oral_habit_archive_model','oral_habit_archive'); 

        $list = $this->oral_habit_archive->get_datatables(); 
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
            $row[] = $chief_complaints->oral_habit_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1659',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_oral_habit('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1658',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->oral_habit_archive->count_all(),
                        "recordsFiltered" => $this->oral_habit_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('280','1659');
        $this->load->model('dental/oral_habit/oral_habit_archive_model','oral_habit_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->oral_habit_archive->restore($id);
           $response = "Oral Habit successfully restore in Oral Habit list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('280','1659');
      $this->load->model('dental/oral_habit/oral_habit_archive_model','oral_habit_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->oral_habit_archive->restoreall($post['row_id']);
            $response = "Oral Habit successfully restore in Oral Habit list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('280','1658');
           $this->load->model('dental/oral_habit/oral_habit_archive_model','oral_habit_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->oral_habit_archive->trash($id);
           $response = "Oral Habit successfully restore in Oral Habit list.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('280','1658');
       $this->load->model('dental/oral_habit/oral_habit_archive_model','oral_habit_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->oral_habit_archive->trashall($post['row_id']);
            $response = "Oral Habit successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function oral_habit_dropdown()
  {

      $oral_habit_list = $this->oral_habit->oral_habit_list();
      $dropdown = '<option value="">Select oral habit</option>'; 
      if(!empty($oral_habit_list))
      {
        foreach($oral_habit_list as $oral_habit)
        {
           $dropdown .= '<option value="'.$oral_habit->id.'">'.$oral_habit->oral_habit_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>