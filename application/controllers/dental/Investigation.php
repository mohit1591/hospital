<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Investigation extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/investigation/Investigation_model','investigation');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //echo "hi";die;
        unauthorise_permission('287','1696');
        $data['page_title'] = 'Investigation List'; 
        $this->load->view('dental/investigation/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('287','1696');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->investigation->get_datatables();

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $cat_name='';
        $investigation_cat='';
        $total_num = count($list);
        foreach ($list as $investigation) {
         //print_r($investigation);die;
            $no++;
            $row = array();
            if($investigation->investigation_cat!=0)
            {
              $investigation_cat=investigation_cat_name($investigation->investigation_cat);
              //print_r($investigation_cat);
              //die;
              $cat_name=$investigation_cat[0]->investigation_sub;
            }
            else
            {
              $cat_name=0;
            }
            if($investigation->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$investigation->id.'">'.$check_script; 
             $row[] = $investigation->second_unit;   
            $row[] = $investigation->first_unit;   
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($investigation->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1698',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_teeth_number('.$investigation->id.');" class="btn-custom" href="javascript:void(0)" style="'.$investigation->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1699',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_teeth_number('.$investigation->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
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
       unauthorise_permission('287','1697');
        $data['page_title'] = "Add  Investigation"; 
        $data['investigation_cat_list'] =$this->investigation->get_investigation_cat_list();
        //print"<pre>";print_r($data['investigation_cat_list']);
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'investigation_cat'=>"",
                                   'investigation_sub'=>"",
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
       $this->load->view('dental/investigation/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('287','1698');

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->investigation->get_by_id($id);
      
        $data['investigation_cat_list'] =$this->investigation->get_investigation_cat_list();

        $data['page_title'] = "Update Investigation";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'investigation_cat'=>$result['investigation_cat'], 
                                  'investigation_sub'=>$result['investigation_sub'], 
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
       $this->load->view('dental/investigation/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('investigation_sub', 'investigation subcategory', 'trim|required'); 
        //callback_chief_complaints
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'investigation_cat'=>$post['investigation_cat'], 
                                        'investigation_sub'=>$post['investigation_sub'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function number($str){
 
          $post = $this->input->post();
          //print_r($post);
          //die;
          if(!empty($post['number']))
          {
               $this->load->model('dental/general/dental_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->teeth_number->get_by_id($post['data_id']);
                      if($data_cat['number']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $teeth_number = $this->general->teeth_numbers($str);

                        if(empty($teeth_number))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('number', 'The teeth number already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $teeth_number = $this->general->teeth_numbers($post['number'], $post['data_id']);
                    if(empty($teeth_number))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('number', 'The teeth number already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('number', 'teeth number field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
      unauthorise_permission('287','1699');
       if(!empty($id) && $id>0)
       {
           $result = $this->investigation->delete($id);
           $response = "investigation Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('287','1699');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->investigation->deleteall($post['row_id']);
            $response = "investigation successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->investigation->get_by_id($id);  
        $data['page_title'] = $data['form_data']['investigation']." detail";
        $this->load->view('dental/investigation/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('287','1700');
        $data['page_title'] = 'Investigation Archive List';
        $this->load->helper('url');
        $this->load->view('dental/investigation/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('287','1700');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('dental/investigation/Investigation_archive_model','investigation_archive'); 

        $list = $this->investigation_archive->get_datatables(); 
       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $cat_name='';
        foreach ($list as $investigation_archive) { 
            $no++;
            $row = array();
              if($investigation_archive->investigation_cat!=0)
            {
              $investigation_cat=investigation_cat_name($investigation_archive->investigation_cat);
              //($investigation_cat);
              $cat_name=$investigation_cat[0]->investigation_sub;
            }
            else
            {
              $cat_name=0;
            }
            if($investigation_archive->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$investigation_archive->id.'">'.$check_script; 
         $row[] = $investigation_archive->second_unit;   
            $row[] = $investigation_archive->first_unit;
            $row[] = $investigation_archive->investigation_sub;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($investigation_archive->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1702',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_investigation('.$investigation_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1701',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$investigation_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
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
        unauthorise_permission('287','1702');
        $this->load->model('dental/investigation/Investigation_archive_model','investigation_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->investigation_archive->restore($id);
           $response = "investigation successfully restore in investigation list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('287','1702');
          $this->load->model('dental/investigation/Investigation_archive_model','investigation_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->investigation_archive->restoreall($post['row_id']);
            $response = "investigation successfully restore in investigation list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('287','1701');
         $this->load->model('dental/investigation/Investigation_archive_model','investigation_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->investigation_archive->trash($id);
           $response = "investigation successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
         unauthorise_permission('287','1701');
        $this->load->model('dental/investigation/Investigation_archive_model','investigation_archive');  
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->investigation_archive->trashall($post['row_id']);
            $response = "investigation successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function teeth_number_dropdown()
  {

      $teeth_number = $this->teeth_number->teeth_number_list();
      $dropdown = '<option value="">Select teeth number</option>'; 
      if(!empty($teeth_number))
      {
        foreach($teeth_number as $teeth_number_list)
        {
           $dropdown .= '<option value="'.$teeth_number_list->id.'">'.$teeth_number_list->number.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>