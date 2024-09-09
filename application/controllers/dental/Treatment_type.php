<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Treatment_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/treatment_type/treatment_type_model','treatment');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //echo "hi";die;
        unauthorise_permission('281','1660');
        $data['page_title'] = 'Material List'; 
        $this->load->view('dental/treatment_type/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('281','1660');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->treatment->get_datatables();  
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
            $row[] = $chief_complaints->treatment;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1662',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_treatment('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" style="'.$chief_complaints->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1663',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_treatment('.$chief_complaints->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->treatment->count_all(),
                        "recordsFiltered" => $this->treatment->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('281','1661');
        $data['page_title'] = "Add Material";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'treatment'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->treatment->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/treatment_type/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('281','1662');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->treatment->get_by_id($id);  
        $data['page_title'] = "Update Material";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'treatment'=>$result['treatment'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->treatment->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/treatment_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('treatment', 'treatment', 'trim|required|callback_treatment'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'treatment'=>$post['treatment'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function treatment($str){
 
          $post = $this->input->post();
          if(!empty($post['treatment']))
          {
               $this->load->model('dental/general/dental_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->treatment->get_by_id($post['data_id']);
                      if($data_cat['treatment']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_complain = $this->general->check_treatment($str);

                        if(empty($check_complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('treatment', 'The treatment already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_treatment($post['treatment'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('treatment', 'The treatment already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('treatment', 'treatment field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('281','1663');
       if(!empty($id) && $id>0)
       {
           $result = $this->treatment->delete($id);
           $response = "Material Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('281','1663');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->treatment->deleteall($post['row_id']);
            $response = "Treatment successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->chief_complaints->get_by_id($id);  
        $data['page_title'] = $data['form_data']['chief_complaints']." detail";
        $this->load->view('eye/treatment_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('281','1664');
        $data['page_title'] = 'Material Archive List';
        $this->load->helper('url');
        $this->load->view('dental/treatment_type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('281','1664');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('dental/treatment_type/treatment_type_archive_model','treatment_archive'); 

        $list = $this->treatment_archive->get_datatables(); 
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
            $row[] = $chief_complaints->treatment;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1666',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_allergy('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1665',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->treatment_archive->count_all(),
                        "recordsFiltered" => $this->treatment_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('281','1666');
        $this->load->model('dental/treatment_type/treatment_archive_model','treatment_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->treatment_archive->restore($id);
           $response = "Material successfully restore in Treatment list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('281','1666');
       $this->load->model('dental/treatment_type/treatment_archive_model','treatment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->treatment_archive->restoreall($post['row_id']);
            $response = "Material successfully restore in Treatment list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('281','1665');
           $this->load->model('dental/treatment_type/treatment_archive_model','treatment_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->treatment_archive->trash($id);
           $response = "Material successfully restore in Treatment list.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('281','1665');
        $this->load->model('dental/treatment_type/treatment_type_archive_model','treatment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->treatment_archive->trashall($post['row_id']);
            $response = "Material successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function treatment_dropdown()
  {

      $treatment_list = $this->treatment->treatment_list();
      $dropdown = '<option value="">Select Material</option>'; 
      if(!empty($treatment_list))
      {
        foreach($treatment_list as $treatment)
        {
           $dropdown .= '<option value="'.$treatment->id.'">'.$treatment->treatment.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>