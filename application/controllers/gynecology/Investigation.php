<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Investigation extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('gynecology/investigation/Investigation_model','investigation');
        $this->load->library('form_validation');
    }

    public function index()
    { 
      
        unauthorise_permission('309','1845');
        $data['page_title'] = 'Investigation List'; 
        $this->load->view('gynecology/investigation/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('309','1845');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->investigation->get_datatables();  
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
            $row[] = $chief_complaints->investigation;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1847',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_investigation('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" style="'.$chief_complaints->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1848',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_investigation('.$chief_complaints->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->investigation->count_all(),
                        "recordsFiltered" => $this->investigation->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
      unauthorise_permission('309','1846');
       
        $data['page_title'] = "Add Investigation";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'investigation'=>"",
                                  'std_value'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->investigation->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/investigation/add',$data);       
    }
    
    public function edit($id="")
    {
    unauthorise_permission('309','1847');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->investigation->get_by_id($id);  
        $data['page_title'] = "Update Investigation";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'investigation'=>$result['investigation'], 
                                  'std_value'=>$result['std_value'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->investigation->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/investigation/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('investigation', 'investigation', 'trim|required|callback_investigation');
        $this->form_validation->set_rules('std_value', 'Standard Value', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'investigation'=>$post['investigation'], 
                                        'std_value'=>$post['std_value'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function investigation($str){
 
          $post = $this->input->post();
          if(!empty($post['investigation']))
          {
               $this->load->model('gynecology/general/Gynecology_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->investigation->get_by_id($post['data_id']);
                      if($data_cat['investigation']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_complain = $this->general->check_investigation($str);

                        if(empty($check_complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('investigation', 'The investigation already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_investigation($post['investigation'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('investigation', 'The investigation already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('investigation', 'investigation field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('309','1848');
       if(!empty($id) && $id>0)
       {
           $result = $this->investigation->delete($id);
           $response = "Investigation Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('309','1848');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->investigation->deleteall($post['row_id']);
            $response = "Investigation successfully deleted.";
            echo $response;
        }
    }

      


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('309','1849');
        $data['page_title'] = 'Investigation Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/investigation/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('309','1849');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('gynecology/investigation/investigation_archive_model','investigation_archive'); 

        $list = $this->investigation_archive->get_datatables(); 
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
           
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$chief_complaints->id.'">'.$check_script; 
            $row[] = $chief_complaints->investigation;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1851',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_investigation('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1850',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->investigation_archive->count_all(),
                        "recordsFiltered" => $this->investigation_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('309','1851');
        $this->load->model('gynecology/investigation/investigation_archive_model','investigation_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->investigation_archive->restore($id);
           $response = "Investigation successfully restore in Investigation list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('309','1851');
      $this->load->model('gynecology/investigation/investigation_archive_model','investigation_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->investigation_archive->restoreall($post['row_id']);
            $response = "Investigation successfully restore in Investigation list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('309','1850');
           $this->load->model('gynecology/investigation/investigation_archive_model','investigation_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->investigation_archive->trash($id);
           $response = "Investigation successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('309','1850');
       $this->load->model('gynecology/investigation/investigation_archive_model','investigation_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->investigation_archive->trashall($post['row_id']);
            $response = "Investigation successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function investigation_dropdown()
  {

      $investigation_list = $this->investigation->investigation_list();
      $dropdown = '<option value="">Select investigation</option>'; 
      if(!empty($investigation_list))
      {
        foreach($investigation_list as $investigation)
        {
           $dropdown .= '<option value="'.$investigation->id.'">'.$investigation->investigation.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>