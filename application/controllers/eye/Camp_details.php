<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Camp_details extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/camp_details/camp_details_model','camp_details');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('318','1899');
        $data['page_title'] = 'Camp Details List'; 
        $this->load->view('eye/camp_details/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('318','1899');
        $list = $this->camp_details->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $camp_details) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($camp_details->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$camp_details->id.'">'.$check_script;
            $camp_involved_data=array();
            $involved= $camp_details->camp_involved;

            //print_r($involved);
            if(!empty($involved))
            {
              $involved_val=$this->camp_details->get_inuser_name($involved);
              
                //$name= $involved_val['name'];
               
              $name=$involved_val;
             }
            else
            {
              $name='';
            }


            $row[] = $camp_details->camp_name;
            $row[] = $camp_details->camp_address;
           // $row[] = $camp_details->camp_involved;
            $row[]=$name;
            $row[] =date('d-M-Y',strtotime($camp_details->camp_date));   
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($camp_details->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1901',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_camp_details('.$camp_details->id.');" class="btn-custom" href="javascript:void(0)" style="'.$camp_details->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1902',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_camp_details('.$camp_details->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->camp_details->count_all(),
                        "recordsFiltered" => $this->camp_details->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
     
    public function add()
    {
        unauthorise_permission('318','1900');
        $data['page_title'] = "Add Camp Details"; 
        $this->load->model('employee/Employee_model','employee');
        $data['emp_list'] = $this->employee->employee_list();
         $this->load->model('general/general_model','general_model');
       // $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['country_list'] = $this->general_model->country_list();
        $post = $this->input->post();
       //print_r($post);
        //die;
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'camp_name'=>"",
                                  'camp_address'=>"",
                                  'camp_involved'=>"",
                                  'camp_date'=>"",
                                  'start_date'=>"",
                                  'end_date'=>"",
                                  'country_id'=>"",
                                  'state_id'=>"",
                                  'citys_id'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->camp_details->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/camp_details/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('318','1901');
        $this->load->model('general/general_model','general_model');
        $data['country_list'] = $this->general_model->country_list();
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->camp_details->get_by_id($id);
        //print_r($result);
       //die; 
        if(!empty($result['camp_date']))
        {
          $camp_date=date('d-M-Y',strtotime($result['camp_date']));
        } 
        else
        {
          $camp_date='';
        }
        if((!empty($result['start_date'])) && ($result['start_date']!='0000-00-00'))
        {
          $start_date=date('d-M-Y',strtotime($result['start_date']));
        } 
        else
        {
          $start_date='';
        }
        if((!empty($result['end_date'])) && ($result['end_date']!='0000-00-00'))
        {
          $end_date=date('d-M-Y',strtotime($result['end_date']));
        } 
        else
        {
          $end_date='';
        }
        //print_r($camp_date);
        //die;
        $data['page_title'] = "Update Camp Details"; 
        $this->load->model('employee/Employee_model','employee');
        $data['emp_list'] = $this->employee->employee_list();
       // print_r(expression)
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'camp_name'=>$result['camp_name'],
                                   'camp_address'=>$result['camp_address'],
                                   'camp_involved'=>$result['camp_involved'],
                                   'camp_date'=>$camp_date,
                                   'start_date'=>$start_date,
                                    'end_date'=>$end_date,
                                    'country_id'=>$result['country_id'],
                                   'state_id'=>$result['state_id'],
                                   'citys_id'=>$result['citys_id'],
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->camp_details->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/camp_details/add',$data);       
      }
    }
     
    private function _validate()
    {
          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('camp_name', 'camp name', 'trim|required'); 
          $this->form_validation->set_rules('camp_date', 'camp date', 'trim|required'); 
          //callback_deferral_reason
          if ($this->form_validation->run() == FALSE) 
          {  
          //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'camp_name'=>$post['camp_name'],
                                        'camp_address'=>$post['camp_address'], 
                                        //'camp_involved'=>$post['camp_involved'], 
                                        'camp_date'=>$post['camp_date'],  
                                        'status'=>$post['status'],
                                        'start_date'=>$post['start_date'],
                                        'end_date'=>$post['end_date'],
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
      unauthorise_permission('318','1902');
       if(!empty($id) && $id>0)
       {
           $result = $this->camp_details->delete($id);
           $response = "Camp details successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
         unauthorise_permission('318','1902');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->camp_details->deleteall($post['row_id']);
            $response = "Camp details successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->camp_details->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Bag Type']." detail";
        $this->load->view('eye/camp_details/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
          unauthorise_permission('318','1903');
         $data['page_title'] = 'Camp Details Archive List'; 
        $this->load->helper('url');
        $this->load->view('eye/camp_details/archive',$data);
    }

    public function archive_ajax_list()
    {
          unauthorise_permission('318','1903');
        $this->load->model('eye/camp_details/camp_details_archive_model','camp_details_archive'); 

        $list = $this->camp_details_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $camp_details_archive) { 
            $no++;
            $row = array();
            if($camp_details_archive->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
             $camp_involved_data=array();
            $involved= $camp_details_archive->camp_involved;

            //print_r($involved);
            if(!empty($involved))
            {
              $involved_val=$this->camp_details_archive->get_inuser_name($involved);
              
                //$name= $involved_val['name'];
               
              $name=$involved_val;
             

              
              
            }
            else
            {
              $name='';
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$camp_details_archive->id.'">'.$check_script; 
             $row[] = $camp_details_archive->camp_name;
            $row[] = $camp_details_archive->camp_address;
            $row[] = $name;
             $row[] =date('d-M-Y',strtotime($camp_details_archive->camp_date));   
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($camp_details_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1906',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_camp_details('.$camp_details_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1904',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$camp_details_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->camp_details_archive->count_all(),
                        "recordsFiltered" => $this->camp_details_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
         unauthorise_permission('318','1906');
        $this->load->model('eye/camp_details/camp_details_archive_model','camp_details_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->camp_details_archive->restore($id);
           $response = "Camp details successfully restore in Camp details list.";
           echo $response;
       }
    }

    function restoreall()
    { 
         unauthorise_permission('318','1906');
        $this->load->model('eye/camp_details/camp_details_archive_model','camp_details_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->camp_details_archive->restoreall($post['row_id']);
            $response = "Camp details successfully restore in Camp details list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
          unauthorise_permission('318','1904');
        $this->load->model('eye/camp_details/camp_details_archive_model','camp_details_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->camp_details_archive->trash($id);
           $response = "Camp details successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
          unauthorise_permission('318','1904');
        $this->load->model('eye/camp_details/camp_details_archive_model','camp_details_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->camp_details_archive->trashall($post['row_id']);
            $response = "Camp details successfully deleted parmanently.";
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
  
  public function camp_dropdown()
  {

      $camp_list = $this->camp_details->camp_dropdown();
      $dropdown = '<option value="">Select Camp</option>'; 
      if(!empty($camp_list))
      {
        foreach($camp_list as $campmaster)
        {
           $dropdown .= '<option value="'.$campmaster->id.'">'.$campmaster->camp_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>