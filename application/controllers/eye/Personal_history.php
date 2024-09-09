<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personal_history extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/personal_history/personal_history_model','personal_history');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('230','1300');
        $data['page_title'] = 'Personal History List'; 
        $this->load->view('eye/personal_history/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('230','1300');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->personal_history->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $personal_history) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($personal_history->status==1)
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
            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$personal_history->id.'">'.$check_script; 
            $row[] = $personal_history->personal_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($personal_history->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1302',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_personal_history('.$personal_history->id.');" class="btn-custom" href="javascript:void(0)" style="'.$personal_history->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1303',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_personal_history('.$personal_history->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
         }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->personal_history->count_all(),
                        "recordsFiltered" => $this->personal_history->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('230','1301');
        $data['page_title'] = "Add Personal History";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'personal_history'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->personal_history->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/personal_history/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('230','1302');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->personal_history->get_by_id($id);  
        $data['page_title'] = "Update Personal History";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'personal_history'=>$result['personal_history'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->personal_history->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/personal_history/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('personal_history', 'personal history', 'trim|required|callback_personal_history'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'personal_history'=>$post['personal_history'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
       /* callbackurl */
    /* check validation laready exit */

    public function personal_history($str){
 
          $post = $this->input->post();
          if(!empty($post['personal_history']))
          {
               $this->load->model('eye/general/eye_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->personal_history->get_by_id($post['data_id']);
                      if($data_cat['personal_history']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_personal_history = $this->general->check_personal_history($str);

                        if(empty($check_personal_history))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('personal_history', 'The personal history already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $personal_history = $this->general->check_personal_history($post['personal_history'], $post['data_id']);
                    if(empty($personal_history))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('personal_history', 'The personal history already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('personal_history', 'The Personal history field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('230','1303');
       if(!empty($id) && $id>0)
       {
           $result = $this->personal_history->delete($id);
           $response = "Personal History successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('230','1303');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->personal_history->deleteall($post['row_id']);
            $response = "Personal History successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->personal_history->get_by_id($id);  
        $data['page_title'] = $data['form_data']['personal_history']." detail";
        $this->load->view('eye/personal_history/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('230','1304');
        $data['page_title'] = 'Personal History Archive List';
        $this->load->helper('url');
        $this->load->view('eye/personal_history/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('230','1304');
         $users_data = $this->session->userdata('auth_users');
         $this->load->model('eye/personal_history/personal_history_archive_model','personal_history_archive'); 

        $list = $this->personal_history_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $personal_history) { 
            $no++;
            $row = array();
            if($personal_history->status==1)
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
            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$personal_history->id.'">'.$check_script; 
            $row[] = $personal_history->personal_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($personal_history->created_date)); 
           
          $btnrestore='';
          $btndelete='';
          if(in_array('1306',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_personal_history('.$personal_history->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
         }
          if(in_array('1305',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$personal_history->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->personal_history_archive->count_all(),
                        "recordsFiltered" => $this->personal_history_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('230','1306');
        $this->load->model('eye/personal_history/personal_history_archive_model','personal_history_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->personal_history_archive->restore($id);
           $response = "Personal History successfully restore in Personal History list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('230','1306');
        $this->load->model('eye/personal_history/personal_history_archive_model','personal_history_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->personal_history_archive->restoreall($post['row_id']);
            $response = "Personal History successfully restore in Personal History list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('230','1305');
        $this->load->model('eye/personal_history/personal_history_archive_model','personal_history_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->personal_history_archive->trash($id);
           $response = "Personal History successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('230','1305');
        $this->load->model('eye/personal_history/personal_history_archive_model','personal_history_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->personal_history_archive->trashall($post['row_id']);
            $response = "Personal History successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function personal_history_dropdown()
  {

      $personal_history_list = $this->personal_history->personal_history_list();
      $dropdown = '<option value="">Select Personal history</option>'; 
      if(!empty($personal_history_list))
      {
        foreach($personal_history_list as $personal_history)
        {
           $dropdown .= '<option value="'.$personal_history->id.'">'.$personal_history->personal_history.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>