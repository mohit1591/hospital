<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daycare_discharge_field_master extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('daycare_discharge_field_master/daycare_discharge_field_master_model','field_master');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('416','2506');
        $data['page_title'] = 'Day Care Discharge field Master'; 
        $this->load->view('daycare_discharge_field_master/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('416','2506');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->field_master->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $discharge_data) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($discharge_data->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$discharge_data->id.'">'; 
            $row[] = $discharge_data->discharge_lable;
            $row[] = $discharge_data->discharge_short_code;
            if($discharge_data->type==1)
            {
              $type='textbox';
            }
            else
            {
              $type='textarea';
            }
            $row[] = $type;
            $row[] = $discharge_data->sort_order;    
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($discharge_data->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('2508',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_discharge_field_master('.$discharge_data->id.');" class="btn-custom" href="javascript:void(0)" style="'.$discharge_data->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('2507',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_discharge_field_master('.$discharge_data->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->field_master->count_all(),
                        "recordsFiltered" => $this->field_master->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('416','2505');
        $data['page_title'] = "Add Day Care Discharge field";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                    'data_id'=>"", 
                                    'discharge_lable'=>"",
                                    'discharge_short_code'=>"", 
                                    'type'=>"", 
                                    'sort_order'=>"",  
                                    'status'=>1
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->field_master->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('daycare_discharge_field_master/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('416','2508');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->field_master->get_by_id($id);  
        $data['page_title'] = "Update Day Care Discharge field";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                    'data_id'=>$result['id'],
                                    'discharge_lable'=>$result['discharge_lable'], 
                                    'discharge_short_code'=>$result['discharge_short_code'], 
                                    'type'=>$result['type'], 
                                    'sort_order'=>$result['sort_order'],  
                                    'status'=>$result['status']

                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->field_master->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('daycare_discharge_field_master/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('discharge_lable', 'discharge lable', 'trim|required');
        $this->form_validation->set_rules('discharge_short_code', 'discharge short code', 'trim|required'); 
        $this->form_validation->set_rules('type', 'type', 'trim|required');


        
        if ($this->form_validation->run() == FALSE) 
        {  
            if(!empty($post['type']))
            {
                $type = $post['type'];
            }
            else
            {
                $type = '';
            }
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'discharge_lable'=>$post['discharge_lable'],
                                        'discharge_short_code'=>$post['discharge_short_code'], 
                                        'type'=>$type, 
                                        'sort_order'=>$post['sort_order'],  
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('416','2507');
       if(!empty($id) && $id>0)
       {
           $result = $this->field_master->delete($id);
           $response = "Day Care Discharge field Master successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('416','2507');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->field_master->deleteall($post['row_id']);
            $response = "Day Care Discharge field Master successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->field_master->get_by_id($id);  
        $data['page_title'] = $data['form_data']['discharge_lable']." detail";
        $this->load->view('daycare_discharge_field_master/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('416','2507');
        $data['page_title'] = 'Day Care Discharge field Archive List';
        $this->load->helper('url');
        $this->load->view('daycare_discharge_field_master/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('416','2507');
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('daycare_discharge_field_master/daycare_discharge_field_master_archive_model','field_master_archive'); 

        $list = $this->field_master_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $discharge_data) { 
            $no++;
            $row = array();
            if($discharge_data->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$discharge_data->id.'">'.$check_script; 
            $row[] = $discharge_data->discharge_lable;
            $row[] = $discharge_data->discharge_short_code;
            if($discharge_data->type==1)
            {
              $type='textbox';
            }
            else
            {
              $type='textarea';
            }
            $row[] = $type;
            $row[] = $discharge_data->sort_order;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($discharge_data->created_date)); 
           
          $btnrestore='';
          $btndelete='';
          if(in_array('2507',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_discharge_field_master('.$discharge_data->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('2507',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$discharge_data->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->field_master_archive->count_all(),
                        "recordsFiltered" => $this->field_master_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('416','2506');
        $this->load->model('daycare_discharge_field_master/daycare_discharge_field_master_archive_model','field_master_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->field_master_archive->restore($id);
           $response = "Day Care Discharge field successfully restore in discharge field list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('416','2506');
        $this->load->model('daycare_discharge_field_master/daycare_discharge_field_master_archive_model','field_master_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->field_master_archive->restoreall($post['row_id']);
            $response = "Day Care Discharge field successfully restore in discharge field list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('416','2506');
        $this->load->model('daycare_discharge_field_master/daycare_discharge_field_master_archive_model','field_master_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->field_master_archive->trash($id);
           $response = "Day Care Discharge field successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('416','2506');
        $this->load->model('daycare_discharge_field_master/daycare_discharge_field_master_archive_model','field_master_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->field_master_archive->trashall($post['row_id']);
            $response = "Day Care Discharge field successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function discharge_field_master_history_dropdown()
  {

      $discharge_field_list = $this->field_master->discharge_field_master_list();
      $dropdown = '<option value="">Select </option>'; 
      if(!empty($personal_history_list))
      {
        foreach($discharge_field_list as $discharge_field)
        {
           $dropdown .= '<option value="'.$discharge_field->id.'">'.$discharge_field->discharge_lable.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>