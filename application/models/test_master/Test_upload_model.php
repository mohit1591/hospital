<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_upload_model extends CI_Model {
 
	
    public function interpretation()
    {
         $this->db->select('path_test.*');
	     if(!empty($test_list))
	     {
	         $this->db->where('path_test.branch_id IN (42,25)');
	     }
	     $query_branch = $this->db->get('path_test'); 
	     //echo $this->db->last_query();die;
	     $test_list = $query_branch->result();  
	     if(!empty($test_list))
	     {
	       foreach($test_list as $test)
	       {
	         if(!empty($test->interpretation))
                 {
                    $this->db->set('interpretation','');
                    $this->db->where('id',$test->id);
                    $this->db->update('path_test');
                    
					/*$test_inter_arr = array(    
							                'title' => 'Interpration', 
							                'interpration' => $test->interpretation,  
							                'test_id' => $test->id, 
							                'range_condition' => '0'
					                      );
					   $this->db->insert('path_multi_interpration',$test_inter_arr);*/
                 }
	       }         
	     }
    }

    public function download_test($bid="",$tid="")
    {
    	if(!empty($bid) || !empty($tid))
    	{
    	$test_id = ''; 
    	$users_data = $this->session->userdata('auth_users'); 
        ////////////// Rate Plan Data ////////
		 $this->db->select('path_test.*, hms_department.department, path_test_heads.test_heads, path_test_heads.sort_order test_heads_order, path_unit.unit, path_test_method.test_method, path_sample_type.sample_type');  
		 $this->db->join('hms_department','hms_department.id = path_test.dept_id','left');
		 $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id','left');
		 $this->db->join('path_unit','path_unit.id = path_test.unit_id','left');
		 $this->db->join('path_test_method','path_test_method.id = path_test.method_id','left');
		 $this->db->join('path_sample_type','path_sample_type.id = path_test.sample_test','left');
		 if(!empty($bid))
		 {
		   $this->db->where('path_test.branch_id',$bid);  	
		 }

		 if(!empty($tid))
		 {
		   $this->db->where('path_test.id',$tid);  	
		 }
		  
	     $query_branch = $this->db->get('path_test'); 
	     //echo $this->db->last_query();die;
	     $test_list = $query_branch->result();  
        /////////////////////////////////////
	     if(!empty($test_list))
	     {
	     	foreach ($test_list as $test) 
	     	{
	     		//////////// Department///////////////
	     		if(!empty($test->department))
	     		{
                    $this->db->select('hms_department.*');
		     	    $this->db->where('hms_department.department',$test->department);
		     	    $this->db->where('hms_department.is_deleted!=2');
		     	    $this->db->where('hms_department.branch_id',$users_data['parent_id']);
		     	    $dept_query = $this->db->get('hms_department');
		     	    //echo $this->db->last_query();die;
		     	    $dept_data = $dept_query->result();
		     	    if(!empty($dept_data))   
		     	    {
	                   $dept_id = $dept_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $dept_arr = array(
	                   	                'branch_id' => $users_data['parent_id'], 
	                   	                'module' => '5', 
	                   	                'department' => $test->department, 
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('hms_department',$dept_arr);
	                   $dept_id = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// test heads///////////////
	     		if(!empty($test->test_heads))
	     		{
                    $this->db->select('path_test_heads.*');
		     	    $this->db->where('path_test_heads.test_heads',$test->test_heads);
		     	    $this->db->where('path_test_heads.is_deleted!=2');
		     	    $this->db->where('path_test_heads.branch_id',$users_data['parent_id']);
		     	    $test_head_query = $this->db->get('path_test_heads');
		     	    $test_head_data = $test_head_query->result();
		     	    if(!empty($test_head_data))   
		     	    {
	                   $test_head_id = $test_head_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $test_head_arr = array(
	                   	                'branch_id' => $users_data['parent_id'], 
	                   	                'dept_id' => $dept_id, 
	                   	                'test_heads' => $test->test_heads, 
	                   	                'status' => '1', 
	                   	                'sort_order'=>$test->test_heads_order,
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('path_test_heads',$test_head_arr);
	                   $test_head_id = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// Unit ///////////////
	     		$unit_id = '0';
	     		if(!empty($test->unit))
	     		{
                    $this->db->select('path_unit.*');
		     	    $this->db->where('path_unit.unit',$test->unit);
		     	    $this->db->where('path_unit.is_deleted!=2');
		     	    $this->db->where('path_unit.branch_id',$users_data['parent_id']);
		     	    $unit_query = $this->db->get('path_unit');
		     	    $unit_data = $unit_query->result();
		     	    if(!empty($unit_data))   
		     	    {
	                   $unit_id = $unit_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $unit_arr = array(
	                   	                'branch_id' => $users_data['parent_id'],   
	                   	                'unit' => $test->unit, 
	                   	                'status' => '1',  
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('path_unit',$unit_arr);
	                   $unit_id = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// Method ///////////////
	     		$method_id = '0';
	     		if(!empty($test->test_method))
	     		{
                    $this->db->select('path_test_method.*');
		     	    $this->db->where('path_test_method.test_method',$test->unit);
		     	    $this->db->where('path_test_method.is_deleted!=2');
		     	    $this->db->where('path_test_method.branch_id',$users_data['parent_id']);
		     	    $method_query = $this->db->get('path_test_method');
		     	    $method_data = $method_query->result();
		     	    if(!empty($method_data))   
		     	    {
	                   $method_id = $method_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $method_arr = array(
	                   	                'branch_id' => $users_data['parent_id'],   
	                   	                'test_method' => $test->test_method, 
	                   	                'status' => '1',  
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('path_test_method',$method_arr);
	                   $method_id = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// Sample Type ///////////////
	     		$sample_test = '0';
	     		if(!empty($test->sample_type))
	     		{
                    $this->db->select('path_sample_type.*');
		     	    $this->db->where('path_sample_type.sample_type',$test->sample_test);
		     	    $this->db->where('path_sample_type.is_deleted!=2');
		     	    $this->db->where('path_sample_type.branch_id',$users_data['parent_id']);
		     	    $sample_type_query = $this->db->get('path_sample_type');
		     	    $sample_type_data = $sample_type_query->result();
		     	    if(!empty($sample_type_data))   
		     	    {
	                   $sample_test = $sample_type_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $sample_type_arr = array(
	                   	                'branch_id' => $users_data['parent_id'],   
	                   	                'sample_type' => $test->sample_type, 
	                   	                'status' => '1',  
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('path_sample_type',$sample_type_arr);
	                   $sample_test = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// Test Insert ///////////////
	     		$this->db->select('path_test.*');
	     	    $this->db->where('path_test.dept_id',$dept_id);
	     	    $this->db->where('path_test.test_head_id',$test_head_id);
	     	    $this->db->where('path_test.test_name',$test->test_name);
	     	    $this->db->where('path_test.is_deleted!=2');
	     	    $this->db->where('path_test.branch_id',$users_data['parent_id']);
	     	    $test_query = $this->db->get('path_test');
	     	    $test_data = $test_query->result();
	     	    //echo $this->db->last_query();die;
	     	    if(!empty($test_data))   
	     	    { 
                   $test_id = $test_data[0]->id;
	     	    }
	     	    else
	     	    { 
	     		 $test_arr = array( 
						'branch_id'=>$users_data['parent_id'], 
						'dept_id'=>$dept_id,
						'test_head_id'=>$test_head_id,
						'test_code'=>$test->test_code, 
						'test_name'=>$test->test_name, 
						'default_value'=>$test->default_value,
						'rate'=>$test->rate,
						'base_rate'=>$test->base_rate,
						'method_id'=>$method_id,
						'unit_id'=>$unit_id, 
						'interpretation'=>$test->interpretation, 
						'range_from_pre'=>$test->range_from_pre,
						'range_from'=>$test->range_from,
						'range_from_post'=>$test->range_from_post,
						'range_to_pre'=>$test->range_to_pre,
						'range_to'=>$test->range_to,
						'range_to_post'=>$test->range_to_post,
						'all_range_show'=>$test->all_range_show, 
						'test_type_id'=>$test->test_type_id, 
						'sample_test'=>$sample_test,
						'sort_order'=>$test->sort_order, 
						'status'=>'1', 
						'created_by'=>$users_data['id'], 
						'created_date'=>date('Y-m-d H:i:s'), 
					    'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		         $this->db->insert('path_test',$test_arr);
		         //echo $this->db->last_query();die;
	             $test_id = $this->db->insert_id();
                 
                 if(!empty($test->interpretation))
                 {
					$test_inter_arr = array(    
							                'title' => 'Interpration', 
							                'interpration' => $test->interpretation,  
							                'test_id' => $test_id, 
							                'range_condition' => '0'
					                      );
					   $this->db->insert('path_multi_interpration',$test_inter_arr);
                 } 
	     		//////////////////////////////////////

	             //////////// Default value ///////////////
                   /* $this->db->select('path_default_vals.*'); 
		     	    $this->db->where('path_default_vals.is_deleted!=2');
		     	    $this->db->where('path_default_vals.branch_id',$bid);
		     	    $default_query = $this->db->get('path_default_vals');
		     	    $default_data = $default_query->result();
		     	    if(!empty($default_data))   
		     	    {
		     	    	foreach ($default_query as $default_val) 
		     	    	{
		     	    		//echo "<pre>"; print_r($default_val);die;
							$this->db->select('path_default_vals.*'); 
							$this->db->where('path_default_vals.is_deleted!=2');
							$this->db->where('path_default_vals.default_vals',$default_val->default_vals);
							$this->db->where('path_default_vals.branch_id',$users_data['parent_id']);
							$default_branch_query = $this->db->get('path_default_vals');
							$default_branch_data = $default_branch_query->result();
							if(empty($default_branch_data))
							{
								$s_default_arr = array(
	                   	                'branch_id' => $users_data['parent_id'],   
	                   	                'default_vals' => $default_val->default_vals, 
	                   	                'status' => '1',  
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                           $this->db->insert('path_default_vals',$s_default_arr);
							}
		     	    	} 
		     	    } */
	     		//////////////////////////////////////

		     	//////////// Multi Range ///////////////
                    $this->db->select('path_test_range.*'); 
		     	    $this->db->where('path_test_range.test_id',$test->id);
		     	    $range_query = $this->db->get('path_test_range');
		     	    $range_data = $range_query->result();
		     	    if(!empty($range_data))   
		     	    {
		     	    	foreach($range_data as $range) 
		     	    	{
								$range_arr = array(
													'test_id'=>$test_id,
													'range_type'=>$range->range_type,
													'gender'=>$range->gender,
													'start_age'=>$range->start_age,
													'end_age'=>$range->end_age,
													'age_type'=>$range->age_type,
													'range_from_pre'=>$range->range_from_pre,
													'range_from'=>$range->range_from,
													'range_from_post'=>$range->range_from_post,
													'range_to_pre'=>$range->range_to_pre,
													'range_to'=>$range->range_to,
													'range_to_post'=>$range->range_to_post
	                   	                           );
	                            $this->db->insert('path_test_range',$range_arr);
		     	    	} 
		     	    } 
	     		//////////////////////////////////////

	     		//////////// Formula ///////////////
                    $this->db->select('path_test_formula.*'); 
		     	    $this->db->where('path_test_formula.test_id',$test->id);
		     	    $this->db->order_by('path_test_formula.id','asc');
		     	    $formula_query = $this->db->get('path_test_formula');
		     	    $formula_data = $formula_query->result();
		     	    //echo $this->db->last_query();
		     	    //echo "<pre>"; print_r($formula_data);die;
		     	    if(!empty($formula_data))   
		     	    {
		     	    	foreach($formula_data as $formula) 
		     	    	{
		     	    		   if($formula->type!='1' && is_numeric(trim($formula->formula_val)))
		     	    		   {			     	    		   	 
		     	    		   	 $formula_val = $this->download($formula->formula_val); 
		     	    		     //echo $formula_val;die;
		     	    		   }
		     	    		   else
		     	    		   {
		     	    		   	 $formula_val = $formula->formula_val;
		     	    		   }
		     	    		    
								$formula_arr = array(
													'test_id'=>$test_id,
													'formula_val'=>$formula_val,
													'type'=>$formula->type
	                   	                           );
	                            $this->db->insert('path_test_formula',$formula_arr);
		     	    	} 
		     	    } 
	     		//////////////////////////////////////  

	     		//////////// Condition ///////////////
                    $this->db->select('path_test_condition.*'); 
		     	    $this->db->where('path_test_condition.test_id',$test->id);
		     	    $this->db->order_by('path_test_condition.id','asc');
		     	    $condition_query = $this->db->get('path_test_condition');
		     	    $condition_data = $condition_query->result();
		     	    if(!empty($condition_data))   
		     	    {
		     	    	foreach($condition_data as $condition) 
		     	    	{
								$condition_arr = array(
													'test_id'=>$test_id,
													'formula_val'=>$condition->condition_val,
													'type'=>$condition->condition_result
	                   	                           );
	                            $this->db->insert('path_test_condition',$condition_arr);
		     	    	} 
		     	    } 
	     		////////////////////////////////////// 

	     		//////////// multi Interpretation ///////////////
                    $this->db->select('path_multi_interpration.*'); 
		     	    $this->db->where('path_multi_interpration.test_id',$test->id);
		     	    $this->db->order_by('path_multi_interpration.id','ASC');
		     	    $intepretation_query = $this->db->get('path_multi_interpration');
		     	    $intepretation_data = $intepretation_query->result();
		     	    if(!empty($intepretation_data))   
		     	    {
		     	    	foreach($intepretation_data as $intepretation) 
		     	    	{
								$intepretation_arr = array(
													'test_id'=>$test_id,
													'title'=>$intepretation->title,
													'interpration'=>$intepretation->interpration,
													'range_condition'=>$intepretation->range_condition
	                   	                           );
	                            $this->db->insert('path_multi_interpration',$intepretation_arr);
		     	    	} 
		     	    } 
	     		//////////////////////////////////////

		     	//////////// Suggestion ///////////////
                   /* $this->db->select('hms_path_test_suggesstion.*'); 
		     	    $this->db->where('hms_path_test_suggesstion.test_id',$test->id);
		     	    $this->db->order_by('hms_path_test_suggesstion.id','ASC');
		     	    $suggetion_query = $this->db->get('hms_path_test_suggesstion');
		     	    $suggetion_data = $suggetion_query->result();
		     	    if(!empty($suggetion_data))   
		     	    {
		     	    	foreach($suggetion_data as $suggetion) 
		     	    	{
							$suggetion_arr = array(
												'test_id'=>$test_id,
												'suggested_test_id'=>$suggetion->suggested_test_id,
												'test_condition'=>$suggetion->test_condition
                   	                           );
                            $this->db->insert('hms_path_test_suggesstion',$suggetion_arr);
		     	    	} 
		     	    } */
	     		//////////////////////////////////////   

	     		//////////// Test Under ///////////////
                  /*  $this->db->select('path_test_under.*'); 
		     	    $this->db->where('path_test_under.parent_id',$test->id);
		     	    $this->db->order_by('path_test_under.id','ASC');
		     	    $under_query = $this->db->get('path_test_under');
		     	    $under_data = $under_query->result();
		     	    if(!empty($under_data))   
		     	    {
		     	    	foreach($under_data as $under) 
		     	    	{
							$under_arr = array(
												'parent_id'=>$test_id,
												'child_id'=>$under->suggested_test_id
                   	                           );
                            $this->db->insert('path_test_under',$under_arr);
		     	    	} 
		     	    } */
	     		//////////////////////////////////////    

	     		    
               


 

	     	}
	     }

    	 
    } 
   }
}

   public function download($tid)
   {

    	if(!empty($tid))
    	{ 
    	$users_data = $this->session->userdata('auth_users'); 
        ////////////// Rate Plan Data ////////
		 $this->db->select('path_test.*, hms_department.department, path_test_heads.test_heads, path_test_heads.sort_order test_heads_order, path_unit.unit, path_test_method.test_method, path_sample_type.sample_type');  
		 $this->db->join('hms_department','hms_department.id = path_test.dept_id','left');
		 $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id','left');
		 $this->db->join('path_unit','path_unit.id = path_test.unit_id','left');
		 $this->db->join('path_test_method','path_test_method.id = path_test.method_id','left');
		 $this->db->join('path_sample_type','path_sample_type.id = path_test.sample_test','left'); 

		 if(!empty($tid))
		 {
		   $this->db->where('path_test.id',$tid);  	
		 }
		  
	     $query_branch = $this->db->get('path_test'); 
	     //echo $this->db->last_query();die;
	     $test = $query_branch->row();  
        /////////////////////////////////////
	     if(!empty($test))
	     { 
	     		//////////// Department///////////////
	     		if(!empty($test->department))
	     		{
                    $this->db->select('hms_department.*');
		     	    $this->db->where('hms_department.department',$test->department);
		     	    $this->db->where('hms_department.is_deleted!=2');
		     	    $this->db->where('hms_department.branch_id',$users_data['parent_id']);
		     	    $dept_query = $this->db->get('hms_department');
		     	    //echo $this->db->last_query();die;
		     	    $dept_data = $dept_query->result();
		     	    if(!empty($dept_data))   
		     	    {
	                   $dept_id = $dept_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $dept_arr = array(
	                   	                'branch_id' => $users_data['parent_id'], 
	                   	                'module' => '5', 
	                   	                'department' => $test->department, 
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('hms_department',$dept_arr);
	                   $dept_id = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// test heads///////////////
	     		if(!empty($test->test_heads))
	     		{
                    $this->db->select('path_test_heads.*');
		     	    $this->db->where('path_test_heads.test_heads',$test->test_heads);
		     	    $this->db->where('path_test_heads.is_deleted!=2');
		     	    $this->db->where('path_test_heads.branch_id',$users_data['parent_id']);
		     	    $test_head_query = $this->db->get('path_test_heads');
		     	    $test_head_data = $test_head_query->result();
		     	    if(!empty($test_head_data))   
		     	    {
	                   $test_head_id = $test_head_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $test_head_arr = array(
	                   	                'branch_id' => $users_data['parent_id'], 
	                   	                'dept_id' => $dept_id, 
	                   	                'test_heads' => $test->test_heads, 
	                   	                'status' => '1', 
	                   	                'sort_order'=>$test->test_heads_order,
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('path_test_heads',$test_head_arr);
	                   $test_head_id = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// Unit ///////////////
	     		$unit_id = '0';
	     		if(!empty($test->unit))
	     		{
                    $this->db->select('path_unit.*');
		     	    $this->db->where('path_unit.unit',$test->unit);
		     	    $this->db->where('path_unit.is_deleted!=2');
		     	    $this->db->where('path_unit.branch_id',$users_data['parent_id']);
		     	    $unit_query = $this->db->get('path_unit');
		     	    $unit_data = $unit_query->result();
		     	    if(!empty($unit_data))   
		     	    {
	                   $unit_id = $unit_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $unit_arr = array(
	                   	                'branch_id' => $users_data['parent_id'],   
	                   	                'unit' => $test->unit, 
	                   	                'status' => '1',  
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('path_unit',$unit_arr);
	                   $unit_id = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// Method ///////////////
	     		$method_id = '0';
	     		if(!empty($test->test_method))
	     		{
                    $this->db->select('path_test_method.*');
		     	    $this->db->where('path_test_method.test_method',$test->unit);
		     	    $this->db->where('path_test_method.is_deleted!=2');
		     	    $this->db->where('path_test_method.branch_id',$users_data['parent_id']);
		     	    $method_query = $this->db->get('path_test_method');
		     	    $method_data = $method_query->result();
		     	    if(!empty($method_data))   
		     	    {
	                   $method_id = $method_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $method_arr = array(
	                   	                'branch_id' => $users_data['parent_id'],   
	                   	                'test_method' => $test->test_method, 
	                   	                'status' => '1',  
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('path_test_method',$method_arr);
	                   $method_id = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// Sample Type ///////////////
	     		$sample_test = '0';
	     		if(!empty($test->sample_type))
	     		{
                    $this->db->select('path_sample_type.*');
		     	    $this->db->where('path_sample_type.sample_type',$test->sample_test);
		     	    $this->db->where('path_sample_type.is_deleted!=2');
		     	    $this->db->where('path_sample_type.branch_id',$users_data['parent_id']);
		     	    $sample_type_query = $this->db->get('path_sample_type');
		     	    $sample_type_data = $sample_type_query->result();
		     	    if(!empty($sample_type_data))   
		     	    {
	                   $sample_test = $sample_type_data[0]->id;
		     	    }
		     	    else
		     	    {
	                   $sample_type_arr = array(
	                   	                'branch_id' => $users_data['parent_id'],   
	                   	                'sample_type' => $test->sample_type, 
	                   	                'status' => '1',  
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                   $this->db->insert('path_sample_type',$sample_type_arr);
	                   $sample_test = $this->db->insert_id();

		     	    }
	     		}
	     		//////////////////////////////////////

	     		//////////// Test Insert ///////////////
	     		$this->db->select('path_test.*');
	     	    $this->db->where('path_test.dept_id',$dept_id);
	     	    $this->db->where('path_test.test_head_id',$test_head_id);
	     	    $this->db->where('path_test.test_name',$test->test_name);
	     	    $this->db->where('path_test.is_deleted!=2');
	     	    $this->db->where('path_test.branch_id',$users_data['parent_id']);
	     	    $test_query = $this->db->get('path_test');
	     	    $test_data = $test_query->result();
	     	    if(!empty($test_data))   
	     	    {
                   $test_id = $test_data[0]->id;
	     	    }
	     	    else
	     	    {
	     		 $test_arr = array( 
						'branch_id'=>$users_data['parent_id'], 
						'dept_id'=>$dept_id,
						'test_head_id'=>$test_head_id,
						'test_code'=>$test->test_code, 
						'test_name'=>$test->test_name, 
						'default_value'=>$test->default_value,
						'rate'=>$test->rate,
						'base_rate'=>$test->base_rate,
						'method_id'=>$method_id,
						'unit_id'=>$unit_id, 
						'interpretation'=>$test->interpretation, 
						'range_from_pre'=>$test->range_from_pre,
						'range_from'=>$test->range_from,
						'range_from_post'=>$test->range_from_post,
						'range_to_pre'=>$test->range_to_pre,
						'range_to'=>$test->range_to,
						'range_to_post'=>$test->range_to_post,
						'all_range_show'=>$test->all_range_show, 
						'test_type_id'=>$test->test_type_id, 
						'sample_test'=>$sample_test,
						'sort_order'=>$test->sort_order, 
						'status'=>'1', 
						'created_by'=>$users_data['id'], 
						'created_date'=>date('Y-m-d H:i:s'), 
					    'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		         $this->db->insert('path_test',$test_arr);
	             $test_id = $this->db->insert_id();
                 
                 if(!empty($test->interpretation))
                 {
					$test_inter_arr = array(    
							                'title' => 'Interpration', 
							                'interpration' => $test->interpretation,  
							                'test_id' => $test_id, 
							                'range_condition' => '0'
					                      );
					   $this->db->insert('path_multi_interpration',$test_inter_arr);
                 } 
	     		//////////////////////////////////////
                 
	             //////////// Default value ///////////////
                   /* $this->db->select('path_default_vals.*'); 
		     	    $this->db->where('path_default_vals.is_deleted!=2');
		     	    $this->db->where('path_default_vals.branch_id',$bid);
		     	    $default_query = $this->db->get('path_default_vals');
		     	    $default_data = $default_query->result();
		     	    if(!empty($default_data))   
		     	    {
		     	    	foreach ($default_query as $default_val) 
		     	    	{
		     	    		//echo "<pre>"; print_r($default_val);die;
							$this->db->select('path_default_vals.*'); 
							$this->db->where('path_default_vals.is_deleted!=2');
							$this->db->where('path_default_vals.default_vals',$default_val->default_vals);
							$this->db->where('path_default_vals.branch_id',$users_data['parent_id']);
							$default_branch_query = $this->db->get('path_default_vals');
							$default_branch_data = $default_branch_query->result();
							if(empty($default_branch_data))
							{
								$s_default_arr = array(
	                   	                'branch_id' => $users_data['parent_id'],   
	                   	                'default_vals' => $default_val->default_vals, 
	                   	                'status' => '1',  
	                   	                'ip_address' => $_SERVER['REMOTE_ADDR'], 
	                   	                'created_by' => $users_data['id'], 
	                   	                'created_date' => date('Y-m-d H:i:s') 
	                   	             );
	                           $this->db->insert('path_default_vals',$s_default_arr);
							}
		     	    	} 
		     	    } */
	     		//////////////////////////////////////


		     	//////////// Multi Range ///////////////
                    $this->db->select('path_test_range.*'); 
		     	    $this->db->where('path_test_range.test_id',$test->id);
		     	    $range_query = $this->db->get('path_test_range');
		     	    $range_data = $range_query->result();
		     	    if(!empty($range_data))   
		     	    {
		     	    	foreach($range_data as $range) 
		     	    	{
								$range_arr = array(
													'test_id'=>$test_id,
													'range_type'=>$range->range_type,
													'gender'=>$range->gender,
													'start_age'=>$range->start_age,
													'end_age'=>$range->end_age,
													'age_type'=>$range->age_type,
													'range_from_pre'=>$range->range_from_pre,
													'range_from'=>$range->range_from,
													'range_from_post'=>$range->range_from_post,
													'range_to_pre'=>$range->range_to_pre,
													'range_to'=>$range->range_to,
													'range_to_post'=>$range->range_to_post
	                   	                           );
	                            $this->db->insert('path_test_range',$range_arr);
		     	    	} 
		     	    } 
	     		//////////////////////////////////////

	     		//////////// Formula ///////////////
                    $this->db->select('path_test_formula.*'); 
		     	    $this->db->where('path_test_formula.test_id',$test->id);
		     	    $this->db->order_by('path_test_formula.id','asc');
		     	    $formula_query = $this->db->get('path_test_formula');
		     	    $formula_data = $formula_query->result();
		     	    //echo $this->db->last_query();
		     	    //echo "<pre>"; print_r($formula_data);die;
		     	    if(!empty($formula_data))   
		     	    {
		     	    	foreach($formula_data as $formula) 
		     	    	{ 
		     	    		/*if($formula->type!='1' && is_numeric(trim($formula->formula_val)))
		     	    		   {			     	    		   	 
		     	    		   	 $formula_val = $this->download($formula->formula_val); 
		     	    		     //echo $formula_val;die;
		     	    		   }
		     	    		   else
		     	    		   {*/
		     	    		   	 $formula_val = $formula->formula_val;
		     	    		   //}
		     	    		    
								$formula_arr = array(
													'test_id'=>$test_id,
													'formula_val'=>$formula_val,
													'type'=>$formula->type
	                   	                           );
	                            $this->db->insert('path_test_formula',$formula_arr);
		     	    	} 
		     	    } 
	     		//////////////////////////////////////  

	     		//////////// Condition ///////////////
                    $this->db->select('path_test_condition.*'); 
		     	    $this->db->where('path_test_condition.test_id',$test->id);
		     	    $this->db->order_by('path_test_condition.id','asc');
		     	    $condition_query = $this->db->get('path_test_condition');
		     	    $condition_data = $condition_query->result();
		     	    if(!empty($condition_data))   
		     	    {
		     	    	foreach($condition_data as $condition) 
		     	    	{
								$condition_arr = array(
													'test_id'=>$test_id,
													'formula_val'=>$condition->condition_val,
													'type'=>$condition->condition_result
	                   	                           );
	                            $this->db->insert('path_test_condition',$condition_arr);
		     	    	} 
		     	    } 
	     		////////////////////////////////////// 

	     		//////////// multi Interpretation ///////////////
                    $this->db->select('path_multi_interpration.*'); 
		     	    $this->db->where('path_multi_interpration.test_id',$test->id);
		     	    $this->db->order_by('path_multi_interpration.id','ASC');
		     	    $intepretation_query = $this->db->get('path_multi_interpration');
		     	    $intepretation_data = $intepretation_query->result();
		     	    if(!empty($intepretation_data))   
		     	    {
		     	    	foreach($intepretation_data as $intepretation) 
		     	    	{
								$intepretation_arr = array(
													'test_id'=>$test_id,
													'title'=>$intepretation->title,
													'interpration'=>$intepretation->interpration,
													'range_condition'=>$intepretation->range_condition
	                   	                           );
	                            $this->db->insert('path_multi_interpration',$intepretation_arr);
		     	    	} 
		     	    } 
	     		//////////////////////////////////////

		     	//////////// Suggestion ///////////////
                    /*$this->db->select('hms_path_test_suggesstion.*'); 
		     	    $this->db->where('hms_path_test_suggesstion.test_id',$test->id);
		     	    $this->db->order_by('hms_path_test_suggesstion.id','ASC');
		     	    $suggetion_query = $this->db->get('hms_path_test_suggesstion');
		     	    $suggetion_data = $suggetion_query->result();
		     	    if(!empty($suggetion_data))   
		     	    {
		     	    	foreach($suggetion_data as $suggetion) 
		     	    	{
							$suggetion_arr = array(
												'test_id'=>$test_id,
												'suggested_test_id'=>$suggetion->suggested_test_id,
												'test_condition'=>$suggetion->test_condition
                   	                           );
                            $this->db->insert('hms_path_test_suggesstion',$suggetion_arr);
		     	    	} 
		     	    } */
	     		//////////////////////////////////////   

	     		//////////// Test Under ///////////////
                    /*$this->db->select('path_test_under.*'); 
		     	    $this->db->where('path_test_under.parent_id',$test->id);
		     	    $this->db->order_by('path_test_under.id','ASC');
		     	    $under_query = $this->db->get('path_test_under');
		     	    $under_data = $under_query->result();
		     	    if(!empty($under_data))   
		     	    {
		     	    	foreach($under_data as $under) 
		     	    	{
							$under_arr = array(
												'parent_id'=>$test_id,
												'child_id'=>$under->suggested_test_id
                   	                           );
                            $this->db->insert('path_test_under',$under_arr);
		     	    	} 
		     	    }*/ 
	     		//////////////////////////////////////    
                  
	     		    
               return $test_id;


 

	     	} 

    	 
    }
    
   }

   }
   
   
   public function download_profile($bid="")
   {
         $this->db->select('path_profile.*'); 
         $this->db->where('path_profile.branch_id',$bid);
         $this->db->where('path_profile.is_deleted','0');
         $this->db->order_by('path_profile.id','asc');
         $query = $this->db->get('path_profile');
         //echo $this->db->last_query();die;
         $result = $query->result_array();
         //echo "<pre>"; print_r($result);die;
         if(!empty($result))   
         {
         	foreach($result as $profile) 
         	{
               $data = array( 
					'branch_id'=>0,
					'profile_name'=>$profile['profile_name'],
                    'print_name' =>$profile['print_name'],
                    'master_rate'=>$profile['master_rate'],
                    'base_rate' =>$profile['base_rate'],
                    'interpretation' =>$profile['interpretation'],
					'status'=>$profile['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					'sort_order'=>$profile['sort_order'],
					'created_date'=>date('Y-m-d h:i:s')
		         );	    
		        $this->db->insert('path_profile',$data);  
		        $profile_id = $this->db->insert_id();
		        
                $this->db->select('path_profile_to_test.*'); 
                $this->db->where('path_profile_to_test.profile_id',$profile['id']); 
                $pquery = $this->db->get('path_profile_to_test');
                $presult = $pquery->result_array();
                //echo '<pre>'; print_r($presult);die;
                if(!empty($presult))
                {
                    foreach($presult as $ptest)
                    {
                     $this->db->select('path_test.*');   
                     $this->db->where('path_test.id',$ptest['test_id']);  	 
					  
				     $query_branch = $this->db->get('path_test');  
				     $test_list = $query_branch->result();  
				     //echo '<pre>'; print_r($test_list);die;
				     if(!empty($test_list))
				     {
				     	/////// Test Get ////////
						$this->db->select('path_test.id');  
						$this->db->where('path_test.dept_id',$test_list[0]->dept_id);  
						$this->db->where('path_test.test_name',$test_list[0]->test_name); 
						$this->db->where('path_test.branch_id','0');  	 
						$tquery_branch = $this->db->get('path_test');  
						$ttest_list = $tquery_branch->result();
						//echo '<pre>'; print_r($ttest_list);die;
				     	////////////////////////
				     	if(!empty($ttest_list))
				     	{
				     		$pdata = array(  
							'profile_id'=>$profile_id,
							'test_id' =>$ttest_list[0]->id,
							'is_child_test_selected'=>$ptest['is_child_test_selected']
							);	    
							$this->db->insert('path_profile_to_test',$pdata);
				     	}
						
				     }
                         
  
                    }
                }
                
                
		        
         	}
         }	
   }

}
?>