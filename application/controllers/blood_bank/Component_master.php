<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_master extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/component_master/Component_master_model','component_master');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('261','1498');
        $data['page_title'] = 'Component Master List'; 
        $this->load->view('blood_bank/component_master/list',$data);
    }

    public function ajax_list()
    { 
      unauthorise_permission('261','1498');
        $list = $this->component_master->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $component_master) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($component_master->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$component_master->id.'">'.$check_script; 
            $row[] = $component_master->component;
            $row[] = $component_master->unit_price;   
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($component_master->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1500',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_component('.$component_master->id.');" class="btn-custom" href="javascript:void(0)" style="'.$component_master->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1501',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_component('.$component_master->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->component_master->count_all(),
                        "recordsFiltered" => $this->component_master->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('261','1499');
        $data['page_title'] = "Add Components Master";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'component'=>"",
                                   'unit_price'=>"",
                                   'component_expiry'=>"",
                                   
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->component_master->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/component_master/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('261','1500');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->component_master->get_by_id($id);  
        $data['page_title'] = "Update Components Master";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'component'=>$result['component'],
                                  'unit_price'=>$result['unit_price'],
                          'component_expiry'=>$result['component_expiry'],                                   
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->component_master->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/component_master/add',$data);       
      }
    }
     
    private function _validate()
    {
          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('component', 'component name', 'trim|required|callback_component');
          $this->form_validation->set_rules('unit_price', 'unit price', 'trim|required'); 
          //callback_deferral_reason
          if ($this->form_validation->run() == FALSE) 
          {  
          //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'component'=>$post['component'],
                                        'unit_price'=>$post['unit_price'],
                                        'component_expiry'=>$post['component_expiry'],    
                                        'status'=>$post['status']
            ); 
          return $data['form_data'];
          }   
    }
     /* callbackurl */
    /* check validation laready exit */
     public function component($str)
      {
        $post = $this->input->post();
         if(!empty($post['component']))
          {
               $this->load->model('blood_bank/general/blood_bank_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->component_master->get_by_id($post['data_id']);
                      if($data_cat['component']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $component = $this->general->check_component_name($str);

                        if(empty($component))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('component', 'The component name already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $check_component_name = $this->general->check_component_name($post['component'], $post['data_id']);
                    if(empty($check_component_name))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('component', 'The component name already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('component', 'The component  name field is required.');
               return false; 
          } 
      }
     /* check validation laready exit */
 
    public function delete($id="")
    {
      unauthorise_permission('261','1501');
       if(!empty($id) && $id>0)
       {
           $result = $this->component_master->delete($id);
           $response = "component master successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('261','1501');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->component_master->deleteall($post['row_id']);
            $response = "component master successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->component_master->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Bag Type']." detail";
        $this->load->view('blood_bank/bag_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('261','1502');
        $data['page_title'] = 'Component master archive list';
        $this->load->helper('url');
        $this->load->view('blood_bank/component_master/archive',$data);
    }

    public function archive_ajax_list()
    {
      unauthorise_permission('261','1502');
      $this->load->model('blood_bank/component_master/Component_master_archive_model','component_master_archive'); 

        $list = $this->component_master_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $component_master_archive) { 
            $no++;
            $row = array();
            if($component_master_archive->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$component_master_archive->id.'">'.$check_script; 
            $row[] = $component_master_archive->component;
             $row[] = $component_master_archive->unit_price;   
            $row[] = $status;
           // $row[] = date('d-M-Y H:i A',strtotime($component_master_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1505',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_component('.$component_master_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1503',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$component_master_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->component_master_archive->count_all(),
                        "recordsFiltered" => $this->component_master_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
        unauthorise_permission('261','1505');
        $this->load->model('blood_bank/component_master/Component_master_archive_model','component_master_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->component_master_archive->restore($id);
           $response = "Component master successfully restore in Component master list.";
           echo $response;
       }
    }

    function restoreall()
    { 
      unauthorise_permission('261','1505');
        $this->load->model('blood_bank/component_master/Component_master_archive_model','component_master_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->component_master_archive->restoreall($post['row_id']);
            $response = "Component master successfully restore in Component master list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('261','1503');
        $this->load->model('blood_bank/component_master/Component_master_archive_model','component_master_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->component_master_archive->trash($id);
           $response = "Component master successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('261','1503');
        $this->load->model('blood_bank/component_master/Component_master_archive_model','component_master_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->component_master_archive->trashall($post['row_id']);
            $response = "Component master successfully deleted parmanently.";
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