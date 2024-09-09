<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allergy extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/allergy/Allergy_model','allergy');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //echo "hi";die;
        unauthorise_permission('279','1646');
        $data['page_title'] = 'Allergy List'; 
        $this->load->view('dental/allergy/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('279','1646');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->allergy->get_datatables();  
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
            $row[] = $chief_complaints->allergy_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1648',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_allergy('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" style="'.$chief_complaints->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1649',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_disease('.$chief_complaints->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->allergy->count_all(),
                        "recordsFiltered" => $this->allergy->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('279','1647');
        $data['page_title'] = "Add Allergy";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'allergy_name'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->allergy->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/allergy/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('279','1648');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->allergy->get_by_id($id);  
        $data['page_title'] = "Update Allergy";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'allergy_name'=>$result['allergy_name'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->allergy->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/allergy/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('allergy_name', 'allergy name', 'trim|required|callback_allergy'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'allergy_name'=>$post['allergy_name'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function allergy($str){
 
          $post = $this->input->post();
          if(!empty($post['allergy_name']))
          {
               $this->load->model('dental/general/dental_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->allergy->get_by_id($post['data_id']);
                      if($data_cat['allergy_name']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_complain = $this->general->check_allergy($str);

                        if(empty($check_complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('allergy_name', 'The allergy already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_allergy($post['allergy_name'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('allergy_name', 'The allergy already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('allergy_name', 'disease field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('279','1649');
       if(!empty($id) && $id>0)
       {
           $result = $this->allergy->delete($id);
           $response = "Allergy Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('279','1649');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->allergy->deleteall($post['row_id']);
            $response = "Allergy successfully deleted.";
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
        unauthorise_permission('279','1650');
        $data['page_title'] = 'Allergy Archive List';
        $this->load->helper('url');
        $this->load->view('dental/allergy/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('279','1650');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('dental/allergy/allergy_archive_model','allergy_archive'); 

        $list = $this->allergy_archive->get_datatables(); 
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
            $row[] = $chief_complaints->allergy_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1652',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_allergy('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1651',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->allergy_archive->count_all(),
                        "recordsFiltered" => $this->allergy_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('279','1652');
        $this->load->model('dental/allergy/allergy_archive_model','allergy_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->allergy_archive->restore($id);
           $response = "Allergy successfully restore in Allergy list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('279','1652');
       $this->load->model('dental/allergy/allergy_archive_model','allergy_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->allergy_archive->restoreall($post['row_id']);
            $response = "Allergy successfully restore in Allergy list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('279','1651');
           $this->load->model('dental/allergy/allergy_archive_model','allergy_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->allergy_archive->trash($id);
           $response = "Allergy successfully restore in Allergy list.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('279','1651');
        $this->load->model('dental/allergy/allergy_archive_model','allergy_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->allergy_archive->trashall($post['row_id']);
            $response = "Allergy successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function allergy_dropdown()
  {

      $allergy_list = $this->allergy->allergy_list();
      //print_r($allergy_list);die;
      $dropdown = '<option value="">Select allergy</option>'; 
      if(!empty($allergy_list))
      {
        foreach($allergy_list as $allergy)
        {
           $dropdown .= '<option value="'.$allergy->id.'">'.$allergy->allergy_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>