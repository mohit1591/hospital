<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Previous_history extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/previous_history/Previous_history_model','previoushis');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('224','1258');
        $data['page_title'] = 'Previous History List'; 
        $this->load->view('eye/previous_history/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('224','1258');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->previoushis->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $previoushis) {
         // print_r($previoushis);die;
            $no++;
            $row = array();
            if($previoushis->status==1)
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
           /* $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              ";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$previoushis->id.'">'.$check_script; 
            $row[] = $previoushis->prv_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($previoushis->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('1260',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_previous_history('.$previoushis->id.');" class="btn-custom" href="javascript:void(0)" style="'.$previoushis->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1261',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_previous_history('.$previoushis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
         }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->previoushis->count_all(),
                        "recordsFiltered" => $this->previoushis->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('224','1259');
        $data['page_title'] = "Add Previous History";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'prv_history'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->previoushis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/previous_history/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('224','1260');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->previoushis->get_by_id($id);  
        $data['page_title'] = "Update Previous History";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'prv_history'=>$result['prv_history'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->previoushis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/previous_history/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('prv_history', 'previous history', 'trim|required|callback_prv_history'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'prv_history'=>$post['prv_history'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
     /* callbackurl */
    /* check validation laready exit */

    public function prv_history($str){
 
          $post = $this->input->post();
          if(!empty($post['prv_history']))
          {
               $this->load->model('eye/general/eye_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                      $data_cat= $this->previoushis->get_by_id($post['data_id']);
                      if($data_cat['prv_history']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $prv_history = $this->general->check_prv_history($str);

                        if(empty($prv_history))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('prv_history', 'The previous history already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $prv_history = $this->general->check_prv_history($post['prv_history'], $post['data_id']);
                    if(empty($prv_history))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('prv_history', 'The previous history  already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('prv_history', 'The previous history field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('224','1261');
       if(!empty($id) && $id>0)
       {
           $result = $this->previoushis->delete($id);
           $response = "Previous History successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('224','1261');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->previoushis->deleteall($post['row_id']);
            $response = "Previous History successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->previoushis->get_by_id($id);  
        $data['page_title'] = $data['form_data']['previoushis']." detail";
        $this->load->view('eye/previous_history/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('224','1262');
        $data['page_title'] = 'Previous History Archive List';
        $this->load->helper('url');
        $this->load->view('eye/previous_history/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('224','1262');
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('eye/previous_history/previous_history_archive_model','previoushis_archive'); 

        $list = $this->previoushis_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $previoushis) { 
            $no++;
            $row = array();
            if($previoushis->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$previoushis->id.'">'.$check_script; 
            $row[] = $previoushis->prv_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($previoushis->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1264',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_previous_history('.$previoushis->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
         }
          if(in_array('1263',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$previoushis->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->previoushis_archive->count_all(),
                        "recordsFiltered" => $this->previoushis_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('224','1264');
        $this->load->model('eye/previous_history/previous_history_archive_model','previoushis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->previoushis_archive->restore($id);
           $response = "Previous History successfully restore in Previous History List.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('224','1264');
        $this->load->model('eye/previous_history/previous_history_archive_model','previoushis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->previoushis_archive->restoreall($post['row_id']);
            $response = "Previous History successfully restore in Previous History List.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('224','1263');
        $this->load->model('eye/previous_history/previous_history_archive_model','previoushis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->previoushis_archive->trash($id);
           $response = "Previous History successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('224','1263');
        $this->load->model('eye/previous_history/previous_history_archive_model','previoushis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->previoushis_archive->trashall($post['row_id']);
            $response = "Previous History successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function previoushis_dropdown()
  {

      $previoushis_list = $this->previoushis->previoushis_list();
      $dropdown = '<option value="">Select previoushis</option>'; 
      if(!empty($previoushis_list))
      {
        foreach($previoushis_list as $previoushis)
        {
           $dropdown .= '<option value="'.$previoushis->id.'">'.$previoushis->previoushis.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>