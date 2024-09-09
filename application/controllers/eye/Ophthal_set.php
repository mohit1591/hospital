<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ophthal_set extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		
        $this->load->model('general/general_model');
        $this->load->model('eye/ophthal_set/ophthal_set_model','ophthal_set');
		$this->load->library('form_validation');
    }

    
	public function index() 
    {
      unauthorise_permission('390','2388');
        $data['page_title'] = 'Ophthal set List'; 
        $this->load->view('eye/ophthal_set/list',$data);
    }

    public function ajax_list()
    { 
    unauthorise_permission('390','2388');
      $users_data = $this->session->userdata('auth_users');
      $sub_branch_details = $this->session->userdata('sub_branches_data');
      $parent_branch_details = $this->session->userdata('parent_branches_data');
      $list = $this->ophthal_set->get_datatables();
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_list) {
         // print_r($ipd_perticular);die;
            $no++;
            $row = array();
            if($test_list->status==1)
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
            $checkboxs = "";
            if($users_data['parent_id']==$test_list->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_list->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $test_list->ophthal_set_name;
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$test_list->branch_id)
            {
              //if(in_array('2110',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ophthal_set_list('.$test_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$test_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              //}
             //if(in_array('2111',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ophthal_set_list('.$test_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               // }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                       "recordsTotal" => $this->ophthal_set->count_all(),
                        "recordsFiltered" => $this->ophthal_set->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 

      public function add()
    {
        unauthorise_permission('390','2388');
        $data['page_title'] = 'Add Ophthal Set'; 
        $this->load->model('general/general_model'); 
        // $data['country_list'] = $this->general_model->country_list();
        $data['eye_region'] = $this->general_model->eye_region();
        //print_r($data['eye_region']);die;
      
        $post = $this->input->post();
      //  print_r($post);die;
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                'ophthal_set_name'=>"",
                                'eye_region_test_head'=>"",
                                'investigation_id'=>"",
                                'investig_name'=>"",
                                'eye_side'=>"",
                        
                                  );   


        if(isset($post) && !empty($post))
        {   
         // echo "heo";die;
          
         $data['form_data'] = $this->_validate('');
             if($this->form_validation->run() == TRUE)
             {
              
                $this->ophthal_set->save();
                echo 1;
                return false;
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
             
            }
        }
       $this->load->view('eye/ophthal_set/add',$data);       
    }

     public function edit($id="")
    {
     unauthorise_permission('390','2388');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
          $this->load->model('general/general_model'); 
          $data['eye_region'] = $this->general_model->eye_region();
        $result = $this->ophthal_set->get_by_id($id); 
        $investigations = $this->ophthal_set->get_investigationby_id($id);
       // echo "<pre>";print_r($result);die; 
        $data['page_title'] = "Edit Ophthal Set detail";  
        $data['investigations']=$investigations;
        $post = $this->input->post();
      //  print_r($post);die;
        $dob=date('d-m-Y',strtotime($result['dob']));
        $data['form_error'] = ''; 
         $data['form_data'] = array(
                                  'data_id'=>$result['id'], 
                                'ophthal_set_name'=>$result['ophthal_set_name'],
                                'eye_region_test_head'=>$result['eye_region_test_head'],
                                'investigation_id'=>$result['investigation_id'],
                                'investig_name'=>$result['investig_name'],
                                'eye_side'=>$result['eye_side'],
                                  );  
       
        
        if(isset($post) && !empty($post))
        {   
        	//echo "hello";die;
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->ophthal_set->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/ophthal_set/add',$data);       
      }
    }
      private function _validate($id='')
    {
        $post = $this->input->post();  
      //  print_r($post);die;
      
        $this->load->model('general/general_model'); 
      
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('ophthal_set_name', 'Ophthal set name', 'trim|required|callback_check_unique_value['.$id.']'); 
        //$this->form_validation->set_rules('type', 'Vehicle Type', 'trim|required'); 
  

          
        if ($this->form_validation->run() == FALSE) 
        {  
           // echo 'kl';die;
           // $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                          'data_id'=>$post['data_id'], 
                                          'ophthal_set_name'=>$post['ophthal_set_name'],
                                          'eye_region_test_head'=>$post['eye_region_test_head'],
                                          'investigation_id'=>$post['investig_id'],
                                          'investig_name'=>$post['investig_name'],
                                          'eye_side'=>$post['eye_side'],
                                       ); 
         
            //print '<pre>'; print_r($data['form_data']);die;
             return $data['form_data'];
            
        }
     //   return $data['form_data'];   
    }

    public function delete($id="")
    {
       unauthorise_permission('390','2388');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ophthal_set->delete($id);
           $response = "Ophthal set successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
      unauthorise_permission('390','2388');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ophthal_set->deleteall($post['row_id']);
            $response = "Ophthal set successfully deleted.";
            echo $response;
        }
    }

      private function _validatetype()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('type', 'type', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'type'=>$post['type'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }

    public function eye_region_test($test_head_id="")
    {
    //  print_r($test_head_id);die('dd');
      if($test_head_id>0)
      {
         $options = "";
          $this->load->model('general/general_model'); 
         $test_head_list = $this->general_model->test_head_list($test_head_id);
        // print_r($test_head_list);die;
         if(!empty($test_head_list))
         {
            foreach($test_head_list as $test_head)
            {
               $options .= '<option value="'.$test_head->id.'">'.ucwords(strtolower($test_head->test_name)).'</option>';
            } 
         }
         echo $options;
      }
    }


    public function ophthal_set_list($id)
    {
       $investigations = $this->ophthal_set->get_investigationby_id($id);     
       print_r(json_encode($investigations));
    }

     public function ophthal_investigation_list()
    {
       $investigations = $this->general_model->ophthal_alltest_list();    
       print_r(json_encode($investigations));
    }


     public function advice_eye_region_test($test_head_id="")
    {
      if($test_head_id>0)
      {
         $options = "";
          $this->load->model('general/general_model'); 
         $test_head_list = $this->general_model->procedure_test_list($test_head_id);
         if(!empty($test_head_list))
         {
            foreach($test_head_list as $test_head)
            {
               $options .= '<option data-toggle="modal" data-target="#eye_region_modal" value="'.$test_head->id.'">'.ucwords(strtolower($test_head->test_name)).'</option>';
            } 
         }
         echo $options;
      }
    }

    /* Search test */
     public function investigation_search($keyword="")
    {

      if($keyword)
      {
         $options = "";
         $test_head_list = $this->ophthal_set->investigation_list($keyword);
        
         if(!empty($test_head_list))
         {
            foreach($test_head_list as $test_head)
            {
               $options .= '<div class="append_row_opt" rel="'.$test_head->test_name.'"data-id="'.$test_head->id.'" data-type="'.$test_head->test_head_id.'" id="investig">'.ucwords(strtolower($test_head->test_name)).'</div>';
            } 
         }
         echo $options;
      }
    }
    /* Search test */

  function check_unique_value($set, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->ophthal_set->check_unique_value($users_data['parent_id'], $set,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Set Name already exist.');
            $response = false;
        }
        return $response;
    }

}
