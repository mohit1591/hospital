<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_master extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_master/test_master_model','test_master');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission(143,855);
        $this->session->unset_userdata('interpretation');
        $this->session->unset_userdata('multi_interpretation');
        $this->session->unset_userdata('precaution');
		    $this->load->model('general/general_model'); 
        //$data['dept_list'] = $this->general_model->department_list(5);
        $data['dept_list'] = $this->general_model->active_department_list(5); 
        $data['page_title'] = 'Test Master List'; 
        $this->load->view('test_master/test_master',$data);
    } 

    public function ajax_list()
    {  
        unauthorise_permission(143,855);
        $this->session->unset_userdata('book_test');
        $this->session->unset_userdata('master_test_head');
        $users_data = $this->session->userdata('auth_users');
        $company_data = $this->session->userdata('company_data');
		$post = $this->input->post();
		$rate_type = 0;
    		if(isset($post['branch_id']) && !empty($post['branch_id']) && $post['branch_id']=='inherit')
    		{
    		   $rate_type =1;
    		} 

            $branch_id = $this->input->post('branch_id');
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            $parent_branch_details = $this->session->userdata('parent_branches_data');

            $list='';
            if($users_data['users_role']=='2')
            {
                if(!empty($branch_id))
            	   {
                   $list = $this->test_master->get_datatables($branch_id);
                 }
            	  else
            	   {
                   $list = $this->test_master->get_datatables($users_data['parent_id']);
                }
            }

            if($users_data['users_role']=='3')
            {
              $list = $this->test_master->get_datatables($company_data['id']);
            }
            else if($users_data['users_role']=='1')
            {
                $list = $this->test_master->get_datatables();
            }  
            $data = array();
            $no = $_POST['start'];
            $i = 1;
            $total_num = count($list);
    		//echo $rate_type;die; 
    		$result_heading = array('1'=>'Enable', '0'=>'Disable');
         foreach ($list as $test_master) 
    		{
		  //////////////////Rate Calculation///////////////// 
			$master_price = $test_master->rate;  
            if($rate_type==1)
			{
			$current_rate_plan = $this->test_master->branch_rate_plan();

            if(!empty($current_rate_plan) && $users_data['users_role']==2)
                {           
                     //print_r($current_rate_plan);die;
                     $current_rate_plan = $current_rate_plan[0];         
                     ///////// Master Calculation //////////////////
                     if($current_rate_plan->master_type==1)
                     { 
                      $master_price = "(".$test_master->rate."/100)*".$current_rate_plan->master_rate; 
                      //echo $test_master->rate;die;  
                      $master_price = $this->calc_string($master_price);  
                     }
                     else
                     {
                        $master_price = str_replace('+', '', $current_rate_plan->master_rate); 
                     }
                     ////////////////////////////////////////////// 

                     $master_price = $test_master->rate+$master_price; 

                }
            }
			//////////////////////////////////////////////////
         // print_r($simulation);die;
            $no++;
            $row = array(); 
            
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
            $users_data = $this->session->userdata('auth_users');

            $test_type_arr = array('0'=>'Normal', '1'=>'Heading');
            if($users_data['users_role']!='3')
            {
                if($users_data['parent_id']==$test_master->branch_id)
                { 
                  $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_master->id.'">';
                }
                else if(!empty($parent_branch_details))
                {  
                    if(in_array($test_master->branch_id,$parent_branch_details)){
                         
                        $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_master->id.'">';
                    }
                    else
                    {
                        $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_master->id.'">';
                    }
                }
                else if($test_master->branch_id==0)
                {
                   
                   $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_master->id.'">';
                }
                 else
                {
                   
                   $row[]='';
                } 
            } 
            $row[] = $test_master->test_code;  
            $row[] = $test_master->test_name;  
            $row[] = $test_type_arr[$test_master->test_type_id];  
            $row[] = $test_master->department;  
            $row[] = $test_master->test_heads;  
            $row[] = $test_master->unit;  
            
            if($users_data['users_role']=='3')
            {
              $base_rate = $this->doctor_base_rate($test_master->id, $users_data['parent_id']);
              $row[] = $base_rate;  
            } 
            else
            {
              $row[] = $master_price;  
            }
            
            
            if($users_data['users_role']=='3')
            {
              $row[] = $result_heading[$test_master->result_heading];
            } 
            else
            {
              $active_result_heading_checked='';
              $inactive_result_heading_checked='';
              
              
              if($test_master->result_heading==1)
              {
                $active_result_heading_checked = 'checked="checked"';
              }
              else
              {
                $inactive_result_heading_checked = 'checked="checked"';
              }
              
              $row[] = '<input type="radio" value="1" '.$active_result_heading_checked.' name="result_heading_'.$i.'" onClick="update_result_heading_status('.$test_master->id.',1)"/> Enable
            <input type="radio" value="0" name="result_heading_'.$i.'" '.$inactive_result_heading_checked.' onClick="update_result_heading_status('.$test_master->id.',0)"/> Disable';
            }
            
            if($users_data['users_role']=='3')
            {
              $row[] = $test_master->sort_order;
            } 
            else
            {
              $row[] = '<input type="text" width="30" class="input-tiny" value='.$test_master->sort_order.' name="sortorder" onkeyup="sort_test_master('.$test_master->id.',this.value)"/>';
            }
            
            //$row[] = date('d-M-Y H:i A',strtotime($test_master->created_date)); 
            
            $btnedit='';
            $btndelete='';
            $btn_download = '';
            if($users_data['parent_id']!=$test_master->branch_id)
            { 
               if(in_array('862',$users_data['permission']['action'])){ 
                    $btn_download = ' <a class="btn-custom" onClick="return download_test('.$test_master->id.')" href="javascript:void(0)" title="Download"><i class="fa fa-download"></i> Download</a>';
               }
            }
            else
            {
               if(in_array('857',$users_data['permission']['action'])){ 
                     $btnedit =' <a  class="btn-custom" href="'.base_url("test_master/edit/").$test_master->id.'"  title="Edit" target="_blank"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';  
               }
               if(in_array('858',$users_data['permission']['action'])){ 
                    $btndelete = ' <a class="btn-custom" onClick="return delete_test('.$test_master->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               }
            }      
             if($users_data['users_role']!='3')
             {
               $row[] = $btnedit.$btndelete.$btn_download;
             }
             
             
        
            $data[] = $row;
            $i++;
        }
		
		
		
		if($users_data['users_role']=='2')
		{
			if(!empty($branch_id))
			   {
			      $branch_id = $branch_id;
			   }
			  else
			   {
			      $branch_id = $users_data['parent_id']; 
			   }
		}
		if($users_data['users_role']=='3')
		{
		   $branch_id = $company_data['id']; 
		}
		else if($users_data['users_role']=='1')
		{
			$branch_id = "";
		} 

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_master->count_all($branch_id),
                        "recordsFiltered" => $this->test_master->count_filtered($branch_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    } 

    public function add()
    { 
        //print_r($this->input->post());die;
        unauthorise_permission(143,856);
        $this->session->unset_userdata('test_dept_id');
        $data['page_title'] = 'Add Test Master';   
        $test_code = generate_unique_id(25);
        $this->load->model('general/general_model'); 
        //$data['dept_list'] = $this->general_model->department_list(5);
        $data['dept_list'] = $this->general_model->active_department_list(5);    
        $data['method_list'] = $this->test_master->test_method_list();  
        $data['heads_list'] = $this->test_master->test_heads_list();
        $data['sample_type_list'] = $this->test_master->sample_type_list();
        $data['unit_list'] = $this->test_master->unit_list(); 
        $data['test_list'] = $this->test_master->test_list();    
        $data['range_list'] = [];
        $data['form_error'] = []; 
        $post = $this->input->post();
        $data['int_i'] = 0;
        $data['form_data'] = array(
                                     'data_id'=>'',
                                     'test_code'=>$test_code,
                                     'dept_id'=>'',
                                     'test_head_id'=>'',
                                     'test_name'=>'', 
                                     'default_value'=>'',
                                     'rate'=>'',
                                     'base_rate'=>'',
                                     'method_id'=>'',
                                     'unit_id'=>'',
                                     'formula'=>'',
                                     'range_from_pre'=>'',
                                     'range_from'=>'',
                                     'range_from_post'=>'',
                                     'range_to_pre'=>'',
                                     'range_to'=>'',
                                     'range_to_post'=>'',
                                     'all_range_show'=>'',
                                     'test_under'=>'',
                                     'condition'=>'',
                                     'condition_result'=>'',
                                     'test_type_id'=>'0',
                                     'interpretation'=>'',
                                     'precaution'=>'',
                                     'item_list'=>'',
                                     'int_i'=>$data['int_i'],
                                     'sample_type_id'=>'',
                                     'test_result_type'=>'',
                                     'is_outsource'=>'',
                                  );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_master->save();
                $this->session->set_flashdata('flash_msg', 'Test successfully created');
                redirect('test_master');
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
                
            }     
        }

        $this->load->view('test_master/add_master',$data);
    } 

    public function edit($id="")
    {   
        unauthorise_permission(143,857);
        $this->session->unset_userdata('test_dept_id');
        if(isset($id) && !empty($id) && is_numeric($id))
        {      
          $result = $this->test_master->get_by_id($id); 
          //echo '<pre>';print_r($inter_data);die;
          $this->load->model('general/general_model'); 
          //$data['dept_list'] = $this->general_model->department_list(5); 
          $data['dept_list'] = $this->general_model->active_department_list(5);  
          $data['method_list'] = $this->test_master->test_method_list();  
          $data['heads_list'] = $this->test_master->test_heads_list();
          $data['unit_list'] = $this->test_master->unit_list(); 
          $data['test_list'] = $this->test_master->test_list();
          $data['range_list'] = $this->test_master->advance_range_list($id);
          $data['sample_type_list'] = $this->test_master->sample_type_list();
          $data['test_suggestions']=$this->test_master->get_test_suggestions($id);
          $formula_list = $this->test_master->test_formula($id); 
          $data['item_list']= $this->test_master->item_list($id);
          $formula = '';
          $total_item_list = count($data['item_list']);
           if($total_item_list>0)
          {
            $data['int_i'] = $total_item_list;
          }
          else
          {
            $data['int_i'] = 0;
          }
          if(!empty($formula_list))
          {
            foreach($formula_list as $formula_val)
            {
                if($formula_val->type==1)
                {
                  $formula .= '|'.$formula_val->formula_val.',';
                }
                else
                {
                  $formula .= $formula_val->formula_val.',';
                }
                
            }
          }

          $condition_list = $this->test_master->test_condition($id);
          $condition = '';
          $condition_result = '';
          if(!empty($condition_list))
          {
            foreach($condition_list as $condition_val)
            {
                if(!empty($condition_val->condition_result))
                {
                   $condition_result = $condition_val->condition_result;
                }
                else
                {
                   $condition .= $condition_val->condition_val.',';
                } 
                
            }
          } 
          $test_under_list = $this->test_master->test_under_list($id);
          $test_under = '';
          if(!empty($test_under_list))
          {
            foreach($test_under_list as $test_under_val)
            {
                $test_under .= $test_under_val->child_id.',';
            }
          }

          $data['page_title'] = "Update Test";  
          $post = $this->input->post();
          $inter_data = '';
          if(!isset($post) || empty($post))
          {
            $inter_data = $this->test_master->test_multi_interpretation($id);
          }
          $data['form_error'] = ''; 
          $data['form_data'] = array(
                                     'data_id'=>$result->id,
                                     'test_code'=>$result->test_code,
                                     'dept_id'=>$result->dept_id,
                                     'test_head_id'=>$result->test_head_id,
                                     'test_name'=>$result->test_name, 
                                     'default_value'=>$result->default_value,
                                     'rate'=>$result->rate,
                                     'base_rate'=>$result->base_rate,
                                     'method_id'=>$result->method_id,
                                     'unit_id'=>$result->unit_id,
                                     'formula'=>$formula,
                                     'range_from_pre'=>$result->range_from_pre,
                                     'range_from'=>$result->range_from,
                                     'range_from_post'=>$result->range_from_post,
                                     'range_to_pre'=>$result->range_to_pre,
                                     'range_to'=>$result->range_to,
                                     'range_to_post'=>$result->range_to_post,
                                     'all_range_show'=>$result->all_range_show,
                                     'test_under'=>$test_under,
                                     'condition'=>$condition,
                                     'int_i'=>$data['int_i'],
                                     'item_list'=>$data['item_list'],
                                     'interpretation'=>$inter_data,
                                     'condition_result'=>$condition_result,
                                     'test_type_id'=>$result->test_type_id,
                                     'sample_type_id'=>$result->sample_test,
                                     'test_result_type'=>$result->test_result_type,
                                     'is_outsource'=>$result->is_outsource,
                                    );   
          $this->session->set_userdata('master_test_head',$result->test_head_id);
            
          if(isset($post) && !empty($post))
          {   
              $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                $this->test_master->save();
                $this->session->set_flashdata('flash_msg', 'Test successfully updated');
                redirect('test_master');
                  
              }
              else
              { 
                  $data['form_error'] = validation_errors(); 
              }     
          }
          
         $this->load->view('test_master/add_master',$data);   
        }
        
    }

    private function _validate()
    {
      $post = $this->input->post();  //echo "<pre>"; print_r($post); exit;
      $field_list = mandatory_section_field_list(7);
      $users_data = $this->session->userdata('auth_users');
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
      $this->form_validation->set_rules('dept_id', 'department', 'trim|required');
      $this->form_validation->set_rules('test_name', 'test name', 'trim|required');
      $this->form_validation->set_rules('test_head_id', 'test head', 'trim|required');
      $this->form_validation->set_rules('rate', 'rate', 'trim|required|numeric');
      if(!empty($field_list)){

        
         
          if($field_list[0]['mandatory_field_id']=='33' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
               $this->form_validation->set_rules('default_value', 'default value', 'trim|required');
          }
          if($field_list[1]['mandatory_field_id']=='34' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
               $this->form_validation->set_rules('method_id', 'method', 'trim|required');
          }
        
         
           if($field_list[3]['mandatory_field_id']=='36' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){ 
          
                $this->form_validation->set_rules('unit_id', 'unit', 'trim|required');
           }
    }
      
      //echo $base_rate = $post['base_rate'];die;
         if(!empty($field_list)){
               

               if($field_list[2]['mandatory_field_id']=='35' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                    $this->form_validation->set_rules('base_rate', 'base rate', 'trim|required|numeric');
               }
          }
  

     
   
        if ($this->form_validation->run() == FALSE) 
        {  

            $test_code = generate_unique_id(25);
            $test_under = [];
            if(isset($post['test_under']))
            {
              $test_under = $post['test_under'];
            }

            $test_formula = [];
            if(isset($post['formula']))
            {
              $test_formula = $post['formula'];
            }

            $test_condition = [];
            if(isset($post['condition']))
            {
              $test_condition = $post['condition'];
            }
            if(!empty($post['data_id']))
            {
              $data['item_list']= $this->test_master->item_list($id);
              $total_item_list = count($data['item_list']);
              if($total_item_list>0)
              {
                  $data['int_i'] = $total_item_list;
              }
              else
              {
                  $data['int_i'] = 0;
              }
            }
            else
            {
                $data['int_i'] = 0;
            }

            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'dept_id'=>$post['dept_id'],
                                        'test_code'=>$test_code,
                                        'test_head_id'=>$post['test_head_id'],
                                        'test_name'=>$post['test_name'], 
                                        'default_value'=>$post['default_value'],
                                        'rate'=>$post['rate'],
                                        'test_under'=>$test_under,
                                        'base_rate'=>$post['base_rate'],
                                        'method_id'=>$post['method_id'],
                                        'unit_id'=>$post['unit_id'],
                                        'formula'=>$test_formula,
                                        'range_from_pre'=>$post['range_from_pre'],
                                        'range_from'=>$post['range_from'],
                                        'range_from_post'=>$post['range_from_post'],
                                        'range_to_pre'=>$post['range_to_pre'],
                                        'range_to'=>$post['range_to'],
                                        'range_to_post'=>$post['range_to_post'],
                                        'all_range_show'=>$post['all_range_show'],
                                        'condition'=>$test_condition,
                                        'int_i'=>$data['int_i'],
                                        'condition_result'=>$post['condition_result'],
                                        'test_type_id'=>$post['test_type_id'],
                                        'interpretation'=>$post['interpretation'],
                                        'sample_type_id'=>$post['sample_type_id'],
                                        'test_result_type'=>$post['test_result_type'],
                                        'is_outsource'=>$post['is_outsource'],
                                       ); 
            return $data['form_data'];
        }   
    }

    public function test_ajax_list($test_id="")
    {  
        $test_master_head = $this->session->userdata('master_test_head');
        $list = [];
        if(isset($test_master_head) && !empty($test_master_head))
        {
          $list = $this->test_master->get_datatables('',$test_id);  
        }
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_master) {
         // print_r($simulation);die;
            $no++;
            $row = array(); 
               
            $row[] = $test_master->test_code;  
            $row[] = '<a href="'.base_url("test_master/edit/".$test_master->id).'">'.$test_master->test_name.'</a>';     
            $row[] = $test_master->rate;   
            $row[] = '<input type="text" width="30" class="input-tiny" value='.$test_master->sort_order.' name="sortorder" onkeyup="sort_test_master('.$test_master->id.',this.value)"/>';
            $tt_code = "'".$test_master->test_code."'";   
            $row[] = '
                    <a href="javascript:void(0)" class="btn-new" onclick="condition('.$test_master->id.','.$tt_code.')">Condition</a>
                    <a href="javascript:void(0)" class="btn-new" onclick="formula('.$test_master->id.','.$tt_code.')">Formula</a> 
                    <a href="javascript:void(0)" class="btn-new"  onclick="test_under('.$test_master->id.','.$tt_code.')">Under</a>';              

        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_master->count_all('',$test_id),
                        "recordsFiltered" => $this->test_master->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    } 
    
    public function delete($id="")
    { 
        unauthorise_permission(143,858);
       if(!empty($id) && $id>0)
       {
           
           $result = $this->test_master->delete($id);
           $response = "Test successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    { 
        unauthorise_permission(143,858);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_master->deleteall($post['row_id']);
            $response = "Tests successfully deleted.";
            echo $response;
        }
    }

    public function add_interpretation($row_id='')
    {
      $data['page_title'] = 'Add Interpretation';      
      $data['form_error'] = []; 
      $interpretation = ""; 
      $interpretation_data = $this->session->userdata('multi_interpretation');
        
      if(isset($interpretation_data[$row_id]) && !empty($interpretation_data[$row_id]))
      {  
        $interpretation = $interpretation_data[$row_id];
      }
      $data['form_data'] = array(
                                   'data_id'=>'',
                                   'row_id'=>$row_id,
                                   'interpretation'=>$interpretation
                                ); 
      $post = $this->input->post();   

      if(!empty($post))
      {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('interpretation', 'interpretation', 'trim'); //required
        if($this->form_validation->run() == TRUE) 
        { 
          $interpretation_data[$post['row_id']] = $post['interpretation'];
          $this->session->set_userdata('multi_interpretation',$interpretation_data);
           echo 1; return false;
        }
        else
        {
          $data['form_error'] = validation_errors();  
          $data['form_data'] = array(
                                   'data_id'=>$post['data_id'],
                                   'row_id'=>$post['row_id'],
                                   'interpretation'=>$post['interpretation']
                                ); 
        }
      } 
      $this->load->view('test_master/add_interpretation',$data);
    }

    public function add_precuation()
    {
      $data['page_title'] = 'Add Precuation';      
      $data['form_error'] = [];
      $precaution = ""; 
      $precuation_data = $this->session->userdata('precaution');
      if(isset($precuation_data))
      {  
        $precaution = $precuation_data;
      }
      $data['form_data'] = array(
                                   'data_id'=>'',
                                   'precaution'=>$precaution
                                ); 
      $post = $this->input->post();   

      if(!empty($post))
      {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('precaution', 'precaution', 'trim|required');
        if($this->form_validation->run() == TRUE) 
        { 
           $this->session->set_userdata('precaution',$post['precaution']);
           echo 1; return false;
        }
        else
        {
          $data['form_error'] = validation_errors();  
          $data['form_data'] = array(
                                   'data_id'=>$post['data_id'],
                                   'precaution'=>$post['precaution']
                                ); 
        }
      }  
      $this->load->view('test_master/add_precuation',$data);
    }
    ////////// Footer Sign section end ////////////////


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(143,859);
        $data['page_title'] = 'Test Archive List';
        $this->load->helper('url');
        $this->load->view('test_master/archive_test',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(143,859);
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('test_master/test_archive_model','test_archive'); 

     

            $list = $this->test_archive->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_master) {
         // print_r($simulation);die;
            $no++;
            $row = array(); 
            
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
            $test_type_arr = array('0'=>'Normal', '1'=>'Hading');
             if($users_data['parent_id']==$test_master->branch_id){
                
                         $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_master->id.'">'.$check_script;
                  
               }else{
                    $row='';
               } 
            $row[] = $test_master->test_code;  
            $row[] = $test_master->test_name;  
            $row[] = $test_type_arr[$test_master->test_type_id];  
            $row[] = $test_master->department;  
            $row[] = $test_master->test_method;  
            $row[] = $test_master->unit;  
            $row[] = $test_master->base_rate;  
            $row[] = date('d-M-Y H:i A',strtotime($test_master->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if($users_data['parent_id']==$test_master->branch_id){
                    if(in_array('861',$users_data['permission']['action'])){ 
                         $btn_restore = ' <a onClick="return restore_test('.$test_master->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
                    }
                    if(in_array('860',$users_data['permission']['action'])){  
                         $btn_delete = ' <a onClick="return trash('.$test_master->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                    }
              }
            // End Action Button //
 
           $row[] = $btn_restore.$btn_delete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_archive->count_all(),
                        "recordsFiltered" => $this->test_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(143,861);
        $this->load->model('test_master/Test_archive_model','test_master_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_master_archive->restore($id);
           $response = "Test successfully restore in Test list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(143,861);
         $this->load->model('test_master/Test_archive_model','test_master_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_master_archive->restoreall($post['row_id']);
            $response = "Tests successfully restore in Test list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(143,860);
         $this->load->model('test_master/Test_archive_model','test_master_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_master_archive->trash($id);
           $response = "Test successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(143,860);
         $this->load->model('test_master/Test_archive_model','test_master_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_master_archive->trashall($post['row_id']);
            $response = "Test successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////



    function get_vals($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->test_master->get_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
    function get_default_vals($test_id='',$vals="")
    {
        if(!empty($test_id) && !empty($vals))
        {
            $result = $this->test_master->get_default_vals($test_id,$vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    public function remove_range($id="")
    {
      if($id>0)
      {
        $this->test_master->remove_range($id);  
      }
    }

    public function download_test($tid="")
    {
       if($tid>0)
       {
         $this->test_master->download_test($tid);  
         echo 'Test successfully download in your Test list.';
       }
    }
    
    public function downloadall()
    {
        unauthorise_permission(143,862);
       
        $post = $this->input->post();  
        if(!empty($post['row_id']))
        {
          foreach($post['row_id'] as $tid)
          {
            $this->test_master->download_test($tid);  
          }
            //$result = $this->test_master->downloadall($post['row_id']);
          $response = "Test successfully Downloaded successfully.";
          echo $response;
        }
    }
    public function save_sort_order_data(){
     $post = $this->input->post();
     $id = $post['test_id'];
     $sort_order_value = $post['sort_order_value'];
     if(!empty($id) && $sort_order_value!=""){
          $result = $this->test_master->save_sort_order_data($id,$sort_order_value);
          echo $result;
          die;

     }

    }
   public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
            $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" onchange="get_selected_branch_test_master_list(this.value); "><option value="">Select</option></option><option value="inherit">Inherited</option><option  selected="selected" value='.$users_data['parent_id'].'>Self</option>';
            // if(!empty($sub_branch_details)){
            //     $i=0;
            //     foreach($sub_branch_details as $key=>$value){
            //         $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
            //         $i = $i+1;
            //     }
               
            // }
            $dropdown.='</select>';
            echo $dropdown; 
        }
         
       
    }

  private function calc_string( $mathString )
  {
          $cf_DoCalc = create_function("", "return (" . $mathString . ");" );
         
          return $cf_DoCalc();
  }  

  public function set_test_head($head_id="")
  {
    if(!empty($head_id))
    {
       $this->session->set_userdata('master_test_head',$head_id);
    }
    else
    {
       $this->session->unset_userdata('master_test_head');
    }
  }


  public function test_excel()
    {
        $this->load->model('general/general_model');
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Test Code','Test Name','Test Type','Department','Test Head','Unit','Price','Sort Order');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->test_master->search_test_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0;
            $test_type_arr = array('0'=>'Normal', '1'=>'Hading');
            foreach($list as $test_master)
            {       
                array_push($rowData,$test_master->test_code,$test_master->test_name,$test_type_arr[$test_master->test_type_id],$test_master->department,$test_master->test_heads,$test_master->unit, $test_master->rate, $test_master->sort_order);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $doctors_data)
              {
                   $col = 0;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $doctors_data[$field]);
                        $col++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=export_test_master_".time().".xls");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
    }


    public function update_test_excel()
    {
        $this->load->model('general/general_model');
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array(
                         'TEST ID',
                         'DEPARTMENT',
                         'TEST HEAD',
                         'TEST NAME',
                         'PATIENT RATE',
                         'BRANCH RATE', 
                         'UNIT',
                         'METHOD',
                         'SAMPLE TYPE'
                       );
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->test_master->search_test_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0;
            $test_type_arr = array('0'=>'Normal', '1'=>'Hading');
            foreach($list as $test_master)
            {       
                array_push(
                          $rowData,
                          $test_master->id,
                          $test_master->department,
                          $test_master->test_heads,
                          $test_master->test_name,
                          $test_master->base_rate,
                          $test_master->rate,    
                          $test_master->unit, 
                          $test_master->test_method, 
                          $test_master->sample_type);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $doctors_data)
              {
                   $col = 0;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $doctors_data[$field]);
                        $col++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=update_test_master_".time().".xls");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
    }

    public function test_csv()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
       $fields = array('Test Code','Test Name','Test Type','Department','Test Head','Unit','Price','Sort Order');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->test_master->search_test_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0;
            $test_type_arr = array('0'=>'Normal', '1'=>'Hading');
            foreach($list as $test_master)
            {       
                array_push($rowData,$test_master->test_code,$test_master->test_name,$test_type_arr[$test_master->test_type_id],$test_master->department,$test_master->test_heads,$test_master->unit, $test_master->rate, $test_master->sort_order);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $doctors_data)
              {
                   $col = 0;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $doctors_data[$field]);
                        $col++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=doctor_list_".time().".csv");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
        
    }

    public function test_pdf()
    {    
        $this->load->model('general/general_model');
        $data['print_status']="";
        $data['data_list'] = $this->test_master->search_test_data();
        $this->load->view('test_master/test_export_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("test_list_".time().".pdf");
    }

    public function test_print()
    {    
      $this->load->model('general/general_model');  
      $data['print_status']="1";
      $data['data_list'] = $this->test_master->search_test_data();
      $this->load->view('test_master/test_export_html',$data); 
    }

    public function doctor_base_rate($test_id="", $doctor_id="")
    {  
      $this->load->model('test_price/test_price_model','test_price');
      if(isset($doctor_id) && !empty($doctor_id))
          {
            $check_doctor_rate = $this->test_price->doctor_test_price($test_id, $doctor_id); 
            if(empty($check_doctor_rate['doctor_base_rate']))
            {
              $test_base_rate = $check_doctor_rate['test_base_rate'];
              $rate_plan_data = $this->test_price->doctor_rate_plan($doctor_id);
              if(!empty($rate_plan_data))
                {
                /* Doctor Base rate */
                if($rate_plan_data[0]->base_type==1)
                  {
                    $pos_base = strpos($rate_plan_data[0]->base_rate,'-');
                    if ($pos_base === true) 
                       {
                          $base_rate = str_replace('-', '',$rate_plan_data[0]->base_rate);
                          $base_rate = $test_base_rate-(($test_base_rate/100)*$rate_plan_data[0]->base_rate);
                       }
                    else
                       {
                            $base_rate = str_replace('+', '',$rate_plan_data[0]->base_rate);
                            $base_rate = $test_base_rate+(($test_base_rate/100)*$rate_plan_data[0]->base_rate); 
                       }

                    }
                    else
                    {
                        $pos_base = strpos($rate_plan_data[0]->base_rate,'-');
                         if ($pos_base === true) 
                         {
                            $base_rate = str_replace('-', '',$rate_plan_data[0]->base_rate);
                            $base_rate = $test_base_rate-$rate_plan_data[0]->base_rate;
                         }
                         else
                         {
                              $base_rate = str_replace('+', '',$rate_plan_data[0]->base_rate);
                              $base_rate = $test_base_rate+$rate_plan_data[0]->base_rate;
                         }
                    }
                    //////////////////////////
                }
                else
                {
                  $base_rate = $test_base_rate;
                }
            }
            else
            {
               $base_rate = $check_doctor_rate['test_base_rate'];
            }
            
                
          }  
        return  number_format($base_rate, 2, '.', ''); 
    }

     public function admin_downloadall()
    {
        unauthorise_permission(143,862);
        $this->db->select('id');
        $this->db->where('branch_id', 25);
        $this->db->where('old_id > 0');
        $this->db->where('is_deleted',0);
        $query = $this->db->get('path_test');
        //echo $this->db->last_query();
        $row = $query->result_array();
        //echo count($row);die;
        // echo "<pre>";print_r($row);die;
        if(!empty($row))
        {
          foreach($row as $tid)
          {
            $this->test_master->admin_download_test($tid['id']);  
          }
            //$result = $this->test_master->downloadall($post['row_id']);
          $response = "Test successfully Downloaded successfully.";
          echo $response;
        }
    }

    public function get_auto_complete_test()
    {
      $term=$this->uri->segment(3);
      $users_data = $this->session->userdata('auth_users');
      $branch_id=$users_data['parent_id'];
      $result=$this->test_master->get_test_list_autocomplete($term,$branch_id);
      echo json_encode($result);  
    }
    
    public function sample_import_test_master_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Department(*)','Test Head(*)','Test Name(*)','Patient Rate(*)','Branch Rate','Default value','Units','Range from (Prefix)','Range from (Value)','Range from(Suffix)','Range to(Prefix)','Range to (Value)','Range to(Suffix)','Method','Sample type','All Range','Test type','Interpretation');

            $col = 0;
            foreach ($fields as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $col++;
            }
            $rowData = array();
            $data= array();
          
            // Fetching the table data
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            // Sending headers to force the user to download the file
            header('Content-Type: application/vnd.ms-excel charset=UTF-8');
            header("Content-Disposition: attachment; filename=test_master_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }
    
    
    public function import_test_master_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Test Master excel';
        $arr_data = array();
        $header = array();
        $path='';

        //print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['test_list']) || $_FILES['test_list']['error']>0)
            {
               
               $this->form_validation->set_rules('test_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               //echo DIR_UPLOAD_PATH.'import_master/'; die;
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('test_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }

                //echo "<pre>";print_r($arr_data); exit;
                if(!empty($arr_data))
                {
                    $arrs_data = array_values($arr_data);
                    $total_test = count($arrs_data);
                    
                   $array_keys = array('dept_id','test_head_id','test_name','rate','base_rate','default_value','unit_id','range_from_pre','range_from','range_from_post','range_to_pre','range_to','range_to_post','method_id','sample_test','all_range_show','test_type_id','interpretation');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R');
                    $patient_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $test_all_data= array();
                    for($i=0;$i<$total_test;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $test_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $test_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                    
                    $this->test_master->save_all_test($test_all_data);
                }

                //echo "<pre>";print_r($test_all_data); exit; 
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {

                $data['form_error'] = validation_errors();
                

            }
        }

        $this->load->view('test_master/import_test_master_excel',$data);
    }


    public function import_update_test_master_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Updated Test Master excel';
        $arr_data = array();
        $header = array();
        $path='';
        //print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['test_list']) || $_FILES['test_list']['error']>0)
            {
               
               $this->form_validation->set_rules('test_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               //echo DIR_UPLOAD_PATH.'import_master/'; die;
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('test_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format

                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }

                //echo "<pre>";print_r($arr_data); exit;
                if(!empty($arr_data))
                { 
                    $arrs_data = array_values($arr_data);
                    $total_test = count($arrs_data);
                    
                   $array_keys = array('id','dept_id','test_head_id','test_name','rate','base_rate','unit_id','method_id','sample_test');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I');
                    $patient_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $test_all_data= array();
                    for($i=0;$i<$total_test;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $test_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $test_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    } 
                    $this->test_master->udpate_all_test($test_all_data);
                }

                //echo "<pre>";print_r($test_all_data); exit; 
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {

                $data['form_error'] = validation_errors();
                

            }
        }

        $this->load->view('test_master/import_update_test_master_excel',$data);
    }
    
    
    public function save_test_result_heading_status()
      {
         $post = $this->input->post();
         $test_id = $post['test_id'];
         
         $status = $post['status'];
         if(!empty($test_id))
         {
              $result = $this->test_master->save_test_result_heading_status($test_id,$status);
              echo $result;
              die;
    
         }
    
        }
    
    


// Please write code above
}
?>