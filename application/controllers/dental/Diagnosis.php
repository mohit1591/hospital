<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosis extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/diagnosis/Diagnosis_model','diagnosis');
        $this->load->library('form_validation');
    }

    public function index()
    { 
       // echo "hi";die;
        unauthorise_permission('283','1674');
        $data['page_title'] = 'Diagnosis List'; 
        $this->load->view('dental/diagnosis/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('283','1674');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->diagnosis->get_datatables();  
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
            $row[] = $chief_complaints->diagnosis;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1676',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_diagnosis('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" style="'.$chief_complaints->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1677',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_diagnosis('.$chief_complaints->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->diagnosis->count_all(),
                        "recordsFiltered" => $this->diagnosis->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('283','1675');
        $data['page_title'] = "Add Diagnosis";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'diagnosis'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->diagnosis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/diagnosis/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('283','1676');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->diagnosis->get_by_id($id);  
        $data['page_title'] = "Update Diagnosis";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'diagnosis'=>$result['diagnosis'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->diagnosis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/diagnosis/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('diagnosis', 'diagnosis', 'trim|required|callback_diagnosis'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'diagnosis'=>$post['diagnosis'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function diagnosis($str){
 
          $post = $this->input->post();
          if(!empty($post['diagnosis']))
          {
               $this->load->model('dental/general/dental_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->diagnosis->get_by_id($post['data_id']);
                      if($data_cat['diagnosis']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_complain = $this->general->check_diagnosis($str);

                        if(empty($check_complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('diagnosis', 'The diagnosis already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_diagnosis($post['diagnosis'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('diagnosis', 'The diagnosis already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('diagnosis', 'diagnosis field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('283','1677');
       if(!empty($id) && $id>0)
       {
           $result = $this->diagnosis->delete($id);
           $response = "Diagnosis Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('283','1677');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis->deleteall($post['row_id']);
            $response = "Diagnosis successfully deleted.";
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
       unauthorise_permission('283','1678');
        $data['page_title'] = 'Diagnosis Archive List';
        $this->load->helper('url');
        $this->load->view('dental/diagnosis/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('283','1678');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('dental/diagnosis/diagnosis_archive_model','diagnosis_archive'); 

        $list = $this->diagnosis_archive->get_datatables(); 
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
            $row[] = $chief_complaints->diagnosis;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1680',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_diagnosis('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1679',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->diagnosis_archive->count_all(),
                        "recordsFiltered" => $this->diagnosis_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('283','1680');
      $this->load->model('dental/diagnosis/diagnosis_archive_model','diagnosis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->diagnosis_archive->restore($id);
           $response = "Diagnosis successfully restore in Diagnosis list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('283','1680');
       $this->load->model('dental/diagnosis/diagnosis_archive_model','diagnosis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis_archive->restoreall($post['row_id']);
            $response = "Diagnosis successfully restore in Diagnosis list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('283','1679');
    $this->load->model('dental/diagnosis/diagnosis_archive_model','diagnosis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->diagnosis_archive->trash($id);
           $response = "Diagnosis successfully restore in Diagnosis list.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('283','1679');
       $this->load->model('dental/diagnosis/diagnosis_archive_model','diagnosis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis_archive->trashall($post['row_id']);
            $response = "Diagnosis successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function diagnosis_dropdown()
  {

      $diagnosis_list = $this->diagnosis->diagnosis_list();
      $dropdown = '<option value="">Select diagnosis</option>'; 
      if(!empty($diagnosis_list))
      {
        foreach($diagnosis_list as $diagnosis)
        {
           $dropdown .= '<option value="'.$diagnosis->id.'">'.$diagnosis->diagnosis.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>