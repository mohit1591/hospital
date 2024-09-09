<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        //branch_creation_rights();
        $this->load->model('rate/rate_model','rate');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission('138','819');
        $data['page_title'] = 'Rate List'; 
        $this->load->view('rate/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('138','819');
        $users_data = $this->session->userdata('auth_users');
       
            $list = $this->rate->get_datatables();
         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $rate) { 
            $no++;
            $row = array();
            if($rate->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($rate->state))
            {
                $state = " ( ".ucfirst(strtolower($rate->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
            ////////// Check list end ///////////// 
            if($rate->master_type==1)
            {
                $master_rate = $rate->master_rate." %";
            }
            else
            {
                $master_rate = 'Rs. '.$rate->master_rate;
            }

            if($rate->base_type==1)
            {
                $base_rate = $rate->base_rate." %";
            }
            else
            {
                $base_rate = 'Rs. '.$rate->base_rate;
            }
            if($users_data['parent_id']==$rate->branch_id){ 
                $row[] = '<input type="checkbox" name="rate[]" class="checklist" value="'.$rate->id.'">'.$check_script;
            }else{
                $row[]='';
            }
            $row[] = $rate->title;
            $row[] = $master_rate; 
            $row[] = $base_rate; 
            $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($rate->created_date)); 
            
            //Action button /////
            $btnedit = "";
            $btndelete = "";
       
            if($users_data['parent_id']==$rate->branch_id){ 
                if(in_array('821',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_rate('.$rate->id.');" class="btn-custom" href="javascript:void(0)" style="'.$rate->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
                }
                if(in_array('822',$users_data['permission']['action'])) 
                {
                    $btndelete = ' <a class="btn-custom" onClick="return delete_rate('.$rate->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

          
             $row[] = $btnedit.$btndelete;              
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->rate->count_all(),
                        "recordsFiltered" => $this->rate->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {  
        unauthorise_permission('138','820');
        $data['page_title'] = "Add Rate Plan"; 
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"",
                                  'title'=>"",
                                  'master_rate'=>"",
                                  'base_rate'=>"",
                                  'master_type'=>"0",
                                  'base_type'=>"0",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->rate->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       $this->load->view('rate/add',$data);       
    }
    
    public function edit($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        unauthorise_permission('138','821');
        $result = $this->rate->get_by_id($id);   
        $data['page_title'] = "Update Rate";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$id,
                                  'title'=>$result['title'],
                                  'master_rate'=>$result['master_rate'],
                                  'base_rate'=>$result['base_rate'],
                                  'master_type'=>$result['master_type'],
                                  'base_type'=>$result['base_type'],
                                  'status'=>$result['status']

                                  );
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->rate->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       $this->load->view('rate/add',$data);       
      }  
    }
     
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('title', 'title', 'trim|required|min_length[2]'); 
        $this->form_validation->set_rules('master_rate', 'patient rate', 'trim|required|numeric');
        $this->form_validation->set_rules('base_rate', 'branch rate', 'trim|required|numeric');
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                'data_id'=>$_POST['data_id'],
                'title'=>$_POST['title'],
                'master_rate'=>$_POST['master_rate'],
                'base_rate'=>$_POST['base_rate'],
                'master_type'=>$_POST['master_type'],
                'base_type'=>$_POST['base_type'],
                'status'=>$_POST['status']
                );  
            $data['form_error'] = validation_errors();  
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('138','822');
       if(!empty($id) && $id>0)
       {
           $result = $this->rate->delete($id);
           $response = "rate successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('138','822');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->rate->deleteall($post['row_id']);
            $response = "Rate successfully deleted.";
            echo $response;
        }
    }



    ///// rate Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('138','823');
        $data['page_title'] = 'Rate Archive List';
        $this->load->helper('url');
        $this->load->view('rate/archive',$data);
    }

    public function archive_ajax_list()
    { 
        unauthorise_permission('138','823');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('rate/rate_archive_model','rate_archive');
       
               $list = $this->rate_archive->get_datatables();
              
             
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $rate) { 
            $no++;
            $row = array();
            if($rate->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($rate->state))
            {
                $state = " ( ".ucfirst(strtolower($rate->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
            ////////// Check list end ///////////// 
            if($rate->master_type==1)
            {
                $master_rate = $rate->master_rate." %";
            }
            else
            {
                $master_rate = 'Rs. '.$rate->master_rate;
            }

            /*if($rate->base_type==1)
            {
                $base_type = $rate->base_type." %";
            }
            else
            {
                $base_type = 'Rs. '.$rate->base_type;
            }*/

            if($rate->base_type==1)
            {
                $base_rate = $rate->base_rate." %";
            }
            else
            {
                $base_rate = 'Rs. '.$rate->base_rate;
            }

            $row[] = '<input type="checkbox" name="rate[]" class="checklist" value="'.$rate->id.'">'.$check_script;
            $row[] = $rate->title;
            $row[] = $master_rate; 
            $row[] = $base_rate; 
            $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($rate->created_date)); 
            
            ////Action button ////////////
            $btn_restore = "";
            $btn_delete = "";
            if(in_array('826',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_rate('.$rate->id.');" class="btn-custom" href="javascript:void(0)" style="'.$rate->id.'" title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore</a>';
            }

            if(in_array('824',$users_data['permission']['action'])) 
            {
               $btn_delete = ' <a onClick="return trash('.$rate->id.');" class="btn-custom" href="javascript:void(0)" style="'.$rate->id.'" title="View"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            /////////////////////////////
            $row[] = $btn_restore.$btn_delete;                  
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->rate_archive->count_all(),
                        "recordsFiltered" => $this->rate_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('138','826');
        $this->load->model('rate/rate_archive_model','rate_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->rate_archive->restore($id);
           $response = "Rate successfully restore in Rate list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('138','826');
        $this->load->model('rate/rate_archive_model','rate_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->rate_archive->restoreall($post['row_id']);
            $response = "Rate successfully restore in Rate list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('138','824');
        $this->load->model('rate/rate_archive_model','rate_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->rate_archive->trash($id);
           $response = "Rate successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('138','824');
        $this->load->model('rate/rate_archive_model','rate_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->rate_archive->trashall($post['row_id']);
            $response = "Rate successfully deleted parmanently.";
            echo $response;
        }
    }
    public function rate_dropdown()
  {
      $rate_list = $this->rate->rate_list();
      $dropdown = '<option value="">Select Rates</option>'; 
      if(!empty($rate_list))
      {
        foreach($rate_list as $rate)
        {
           $dropdown .= '<option value="'.$rate->id.'">'.$rate->title.'</option>';
        }
      } 
      echo $dropdown; 
  }


  public function view($rate_id="")
  {
    $data['result'] = $this->rate->get_by_id($rate_id); 
    $data['page_title'] = "Rate Plan";
    $this->load->view('rate/view',$data);       
  }
  



}
?>