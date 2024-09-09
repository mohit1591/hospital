<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frequency extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/frequency/frequency_model','frequency');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('234','1328');
        $data['page_title'] = 'Frequency List'; 
        $this->load->view('eye/frequency/list',$data);
    }

    public function ajax_list()
    { 
         unauthorise_permission('234','1328');
        $list = $this->frequency->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $frequency) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($frequency->status==1)
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
            ////////// Check list end /////////////  medicine_dosage_frequency
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$frequency->id.'">'.$check_script; 
            $row[] = $frequency->medicine_dosage_frequency;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($frequency->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('1330',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_frequency('.$frequency->id.');" class="btn-custom" href="javascript:void(0)" style="'.$frequency->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1331',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_frequency('.$frequency->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->frequency->count_all(),
                        "recordsFiltered" => $this->frequency->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('234','1329');
        $data['page_title'] = "Add Frequency";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine_dosage_frequency'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->frequency->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/frequency/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('234','1330');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->frequency->get_by_id($id);  
        $data['page_title'] = "Update Frequency";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_dosage_frequency'=>$result['medicine_dosage_frequency'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->frequency->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/frequency/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_dosage_frequency', 'frequency', 'trim|required|callback_medicine_dosage_frequency'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_dosage_frequency'=>$post['medicine_dosage_frequency'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }

      /* callbackurl */
    /* check validation laready exit */

    public function medicine_dosage_frequency($str){
 
          $post = $this->input->post();
          if(!empty($post['medicine_dosage_frequency']))
          {
               $this->load->model('eye/general/eye_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->frequency->get_by_id($post['data_id']);
                      if($data_cat['medicine_dosage_frequency']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_frequency = $this->general->check_frequency($str);

                        if(empty($check_frequency))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('medicine_dosage_frequency', 'The frequency already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_frequency($post['medicine_dosage_frequency'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('medicine_dosage_frequency', 'The frequency already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('medicine_dosage_frequency', 'The frequency field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('234','1331');
       if(!empty($id) && $id>0)
       {
           $result = $this->frequency->delete($id);
           $response = "Frequency successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('234','1331');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->frequency->deleteall($post['row_id']);
            $response = "Frequency successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->frequency->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_dosage_frequency']." detail";
        $this->load->view('eye/frequency/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('234','1332');
        $data['page_title'] = 'Frequency Archive List';
        $this->load->helper('url');
        $this->load->view('eye/frequency/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('234','1332');
        $this->load->model('eye/frequency/frequency_archive_model','frequency_archive'); 

        $list = $this->frequency_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $frequency) { 
            $no++;
            $row = array();
            if($frequency->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$frequency->id.'">'.$check_script; 
            $row[] = $frequency->medicine_dosage_frequency;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($frequency->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1334',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_frequency('.$frequency->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
         }
          if(in_array('1333',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$frequency->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->frequency_archive->count_all(),
                        "recordsFiltered" => $this->frequency_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('234','1334');
        $this->load->model('eye/frequency/frequency_archive_model','frequency_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->frequency_archive->restore($id);
           $response = "Frequency successfully restore in Frequency list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('234','1334');
        $this->load->model('eye/frequency/frequency_archive_model','frequency_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->frequency_archive->restoreall($post['row_id']);
            $response = "Frequency successfully restore in Frequency list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       unauthorise_permission('234','1333');
        $this->load->model('eye/frequency/frequency_archive_model','frequency_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->frequency_archive->trash($id);
           $response = "Frequency successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('234','1333');
        $this->load->model('eye/frequency/frequency_archive_model','frequency_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->frequency_archive->trashall($post['row_id']);
            $response = "Frequency successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function frequency_dropdown()
  {

      $frequency_list = $this->frequency->frequency_list();
      $dropdown = '<option value="">Select Frequency</option>'; 
      if(!empty($frequency_list))
      {
        foreach($frequency_list as $frequency)
        {
           $dropdown .= '<option value="'.$frequency->id.'">'.$frequency->medicine_dosage_frequency.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>