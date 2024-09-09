<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bag_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/bag_type/bag_type_model','bag_type');
        $this->load->library('form_validation');
    }

    public function index()
    { 

       unauthorise_permission('257','1466');
        $data['page_title'] = 'Bag Type List'; 
        $this->load->view('blood_bank/bag_type/list',$data);
    }

    public function ajax_list()
    { 

        unauthorise_permission('257','1466');
        $list = $this->bag_type->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $bag_type) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($bag_type->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////medicine_preferred_reminder_service
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bag_type->id.'">'.$check_script; 
            $row[] = $bag_type->bag_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($bag_type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1468',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_bag_type('.$bag_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$bag_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1469',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_bag_type('.$bag_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bag_type->count_all(),
                        "recordsFiltered" => $this->bag_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('257','1467');
        $data['page_title'] = "Add Bag Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'bag_type'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->bag_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/bag_type/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('257','1468');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->bag_type->get_by_id($id);  
        $data['page_title'] = "Update Bag Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'bag_type'=>$result['bag_type'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->bag_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/bag_type/add',$data);       
      }
    }
     
    private function _validate()
    {
          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('bag_type', 'bag type', 'trim|required|callback_bag_type'); 
          //callback_deferral_reason
          if ($this->form_validation->run() == FALSE) 
          {  
          //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'bag_type'=>$post['bag_type'], 
                                        'status'=>$post['status']
            ); 
          return $data['form_data'];
          }   
    }
     /* callbackurl */
    /* check validation laready exit */
     public function bag_type($str)
      {
        $post = $this->input->post();
         if(!empty($post['bag_type']))
          {
               $this->load->model('blood_bank/general/blood_bank_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->bag_type->get_by_id($post['data_id']);
                      if($data_cat['bag_type']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $bag_type = $this->general->check_bag_type($str);

                        if(empty($bag_type))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('bag_type', 'The bag type already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $deferral_reason = $this->general->check_bag_type($post['bag_type'], $post['data_id']);
                    if(empty($deferral_reason))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('bag_type', 'The bag type already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('bag_type', 'The bag type field is required.');
               return false; 
          } 
      }
     /* check validation laready exit */
 
    public function delete($id="")
    {
      unauthorise_permission('257','1469');
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_type->delete($id);
           $response = "Bag Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('257','1469');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bag_type->deleteall($post['row_id']);
            $response = "Bag Type successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->bag_type->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Bag Type']." detail";
        $this->load->view('blood_bank/bag_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('257','1470');
        $data['page_title'] = 'Bag Type archive list';
        $this->load->helper('url');
        $this->load->view('blood_bank/bag_type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('257','1470');
        $this->load->model('blood_bank/bag_type/bag_type_archive_model','bag_type_archive'); 

        $list = $this->bag_type_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $bag_type_archive) { 
            $no++;
            $row = array();
            if($bag_type_archive->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bag_type_archive->id.'">'.$check_script; 
            $row[] = $bag_type_archive->bag_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($bag_type_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1473',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_bag_type('.$bag_type_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1471',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$bag_type_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bag_type_archive->count_all(),
                        "recordsFiltered" => $this->bag_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
        unauthorise_permission('257','1473');
        $this->load->model('blood_bank/bag_type/bag_type_archive_model','bag_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_type_archive->restore($id);
           $response = "Bag Type successfully restore in Bag Type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('257','1473');
        $this->load->model('blood_bank/bag_type/bag_type_archive_model','bag_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bag_type_archive->restoreall($post['row_id']);
            $response = "Bag Type  successfully restore in Bag Type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('257','1471');
        $this->load->model('blood_bank/bag_type/bag_type_archive_model','bag_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_type_archive->trash($id);
           $response = "Bag Type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('257','1471');
        $this->load->model('blood_bank/bag_type/bag_type_archive_model','bag_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bag_type_archive->trashall($post['row_id']);
            $response = "Bag Type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function bag_type_archive_dropdown()
  {

      $deferral_reason_archive_list = $this->bag_type_archive->bag_type_archive_list();
      $dropdown = '<option value="">Select Bag Type</option>'; 
      if(!empty($deferral_reason_archive_list))
      {
        foreach($deferral_reason_archive_list as $deferral_reason_archive)
        {
           $dropdown .= '<option value="'.$deferral_reason_archive->id.'">'.$deferral_reason_archive->deferral_reason_archive.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>