<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_master_model extends CI_Model {

	var $table = 'path_test';
	var $column = array('path_test.id','path_test.test_code','path_test.test_name','path_test.sort_order', 'path_test.test_type_id', 'hms_department.department', 'path_test_heads.test_heads', 'path_unit.unit','path_test.base_rate');  
	var $order = array('sort_order'=>'asc','id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($branch_id='',$not_in_test='')
	{
        $users_data = $this->session->userdata('auth_users');
        $company_data =  $this->session->userdata('company_data');
        $test_master_search = array('test_head'=>$_POST['test_head'], 'dept_id'=>$_POST['dept_id']);
        $this->session->set_userdata('test_master_search',$test_master_search);
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("path_test.*, path_unit.unit, hms_department.department, path_test_heads.test_heads,path_test_method.test_method,path_sample_type.sample_type"); 
		$this->db->from($this->table);  
        if(!empty($not_in_test))
        {
        	//$this->db->where('path_test.id NOT IN ('.$not_in_test.')');
        }
        if($branch_id!='inherit')
	{
           $this->db->where('path_test.is_deleted',0);
        }
        if($users_data['users_role']=='2')
        {
		    if(!empty($branch_id))
		    {
			    if($branch_id=='inherit')
			    {
				  
			        if(!empty($parent_branch_details)){
			            $id_list = [];
			            foreach($parent_branch_details as $id){ 
			                if(!empty($id) && $id>0){
                                $id_list[]  = $id['parent_id'];
			                } 
			            }
		                $branch_ids = implode(',', $id_list);
		                //print_r($id_list);die;
		                $this->db->where('path_test.branch_id IN('.$branch_ids.')');
		            }
    		       $this->db->where('path_test.id NOT IN (select download_id from path_test where branch_id IN ("'.$users_data['parent_id'].'")  AND is_deleted!=2)');
                }
                else if($branch_id==$users_data['parent_id'])
                {
				    $this->db->where('path_test.branch_id',$users_data['parent_id']);
				    $this->db->where('path_test.id NOT IN (select download_id from path_test where branch_id IN ("'.$users_data['parent_id'].'"))');
			    
			    }
			    else{
			            $this->db->where('path_test.branch_id',$branch_id);
			            $this->db->where('path_test.id NOT IN (select download_id from path_test where branch_id IN ("'.$users_data['parent_id'].'"))');
			    }
		    }
		    else
		    {
		    	$this->db->where('path_test.branch_id',$users_data['parent_id']);
		    }
		}
		else if($users_data['users_role']=='3')
		{
			$this->db->where('path_test.branch_id',$company_data['id']);
		}
		else if($users_data['users_role']=='1')
		{
           $this->db->where('path_test.branch_id',$users_data['parent_id']);
		}
		
        $this->db->join('path_unit','path_unit.id = path_test.unit_id','left');
        $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id','left');
        $this->db->join('hms_department','hms_department.id = path_test.dept_id','left');
        $this->db->join('path_test_method','path_test_method.id = path_test.method_id','left');
        $this->db->join('path_sample_type','path_sample_type.id = path_test.sample_test','left');

        $test_heads = $this->session->userdata('master_test_head');

        if(!empty($test_heads))
        {
        	$this->db->where('path_test.test_head_id',$test_heads);
        }
        
		if(!empty($_POST['dept_id']))
		{
		    $this->db->where('path_test.dept_id',$_POST['dept_id']);
		}
		
		if(!empty($_POST['test_head']))
		{
		    $this->db->where('path_test.test_head_id',$_POST['test_head']);
		}
		$i = 0; 
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop+
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	function get_datatables($branch_id='',$not_in_test='')
	{
		$this->_get_datatables_query($branch_id,$not_in_test);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	public function search_test_data()
	{
		$users_data = $this->session->userdata('auth_users');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("path_test.*, path_unit.unit, hms_department.department, path_test_heads.test_heads"); 
		$this->db->from($this->table);   
        $this->db->where('path_test.is_deleted',0);
        $this->db->where('path_test.branch_id',$users_data['parent_id']);
        $this->db->join('path_unit','path_unit.id = path_test.unit_id','left');
        $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id','left');
        $this->db->join('hms_department','hms_department.id = path_test.dept_id','left');
        $test_master_search = $this->session->userdata('test_master_search');

        if(!empty($test_master_search) && isset($test_master_search['test_head']) && !empty($test_master_search['test_head']))
        {
        	$this->db->where('path_test.test_head_id',$test_master_search['test_head']);
        }
        
		if(!empty($test_master_search) && isset($test_master_search['dept_id']) && !empty($test_master_search['dept_id']))
		{
		    $this->db->where('path_test.dept_id',$test_master_search['dept_id']);
		}
		$this->db->order_by('path_test.id','DESC');
		 
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	

	function count_filtered($branch_id='',$not_in_test='')
	{
		$this->_get_datatables_query($branch_id,$not_in_test);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
    
    public function get_by_id($id="")
	{
		$this->db->select('path_test.*');
		$this->db->from('path_test'); 
		if(!empty($id))
		{
          $this->db->where('path_test.id',$id);
		} 
		//$this->db->where('path_test.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$interpretation = "";
		$precuation = "";
		$interpretation_data = $this->session->userdata('multi_interpretation');  
		//echo "<pre>";print_r($interpretation_data);die;
		$base_rate = $post['base_rate'];
          //$branch_attribute = get_permission_attr(1,2); 
	      //if(in_array('1',$user_data['permission']['section']) && in_array('2',$user_data['permission']['action']) && $branch_attribute>0)
	     // {
          //    $base_rate = $post['base_rate']; sample_type_id
	      //}

		$data = array( 
						'branch_id'=>$user_data['parent_id'], 
						'dept_id'=>$post['dept_id'],
						'test_head_id'=>$post['test_head_id'],
						'test_name'=>$post['test_name'], 
						'default_value'=>$post['default_value'],
						'rate'=>$post['rate'],
						'base_rate'=>$post['base_rate'],
						'method_id'=>$post['method_id'],
						'unit_id'=>$post['unit_id'], 
						'range_from_pre'=>$post['range_from_pre'],
						'range_from'=>$post['range_from'],
						'range_from_post'=>$post['range_from_post'],
						'range_to_pre'=>$post['range_to_pre'],
						'range_to'=>$post['range_to'],
						'range_to_post'=>$post['range_to_post'],
						'all_range_show'=>$post['all_range_show'], 
						'test_type_id'=>$post['test_type_id'], 
						'precaution'=>$precuation,
						'sample_test'=>$post['sample_type_id'],
					    'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );

		if(!empty($post['data_id']) && $post['data_id']>0)
		{  
	        $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('path_test',$data); 
                 //echo $this->db->last_query();die;
			$test_id = $post['data_id']; 

			/////// Interpretation /////////////
			$this->db->where('test_id',$test_id);
			$this->db->delete('path_multi_interpration');
            if(!empty($post['interpretation']))
            {
            	foreach($post['interpretation'] as $key=>$interp)
            	{
            	   $inter_data = '';	
                   if(isset($interpretation_data[$key]))
                   {
                   	 $inter_data = $interpretation_data[$key];
                   }
                   $inter_arr = array(
                                       'title'=>$interp['title'],
                                       'interpration'=>$inter_data,
                                       'test_id'=>$test_id,
                                       'range_condition'=>$interp['condition'],
                                     );
                   $this->db->insert('path_multi_interpration',$inter_arr);   
            	}
            	$this->session->unset_userdata('multi_interpretation');  

            	$this->db->set('interpretation','');
            	$this->db->where('id',$post['data_id']);
			    $this->db->update('path_test',$data); 
            }
            	
			////////////////////////////////////

			$this->db->where(array('test_id'=>$test_id,'type'=>1));
			$this->db->delete('path_test_to_inventory_item');
		    //print '<pre>'; print_r($post);die;
		    if(!empty($post['item_name']))
		    {
		    	$array_item = array_values($post['item_name']);
		    }
		    if(!empty($post['item_id']))
		    {
		    	$array_item_id = array_values($post['item_id']);
		    }
		    if(!empty($post['unit_value']))
		    {
		    	$array_unit_value = array_values($post['unit_value']);
		    }
		    if(!empty($post['quantity']))
		    {
		    	 $array_quantity = array_values($post['quantity']);
		    }
            
            
            
           

          	if(!empty($array_item))
			{
				 $i=0;
				foreach($array_item as $item_name)
				{
						$data_insert_items= 
						array('branch_id'=>$user_data['parent_id'],
						'type'=>1,
						'test_id'=>$test_id,
						'booking_id'=>0,
						'item_id'=>$array_item_id[$i],
						'unit_id'=>$array_unit_value[$i],
						'qty'=>$array_quantity[$i],	
						);

                       $this->db->insert('path_test_to_inventory_item',$data_insert_items);
                       //echo $this->db->last_query();
						$i++;


				}	
				//die;
			} 

			// code to insert test suggestions after deleting previous
				if(isset($post['suggested_test_id']))
				{
					$this->db->where('test_id',$test_id);
					$this->db->delete('hms_path_test_suggesstion');

					$total_rows=count($post['suggested_test_id']);
					for($i=0;$i<=$total_rows;$i++)
					{
						if($post['suggested_test_id'][$i]!=0)
						{
							$data_array=array('test_id'=>$test_id,
									  	  'suggested_test_id'=>$post['suggested_test_id'][$i], 
									  	  'test_condition'=>$post['test_condition'][$i],
									  	  'created_by'=>$user_data['id'],
									  	  'created_date'=>date('Y-m-d H:i:s'),
					    				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
									  	  );
							$this->db->insert('hms_path_test_suggesstion',$data_array);	
						}
					}
					
				}
			// code to insert test suggestions after deleting previous


		}
		else
		{   
			$test_code = generate_unique_id(25);
            $this->db->set('test_code',$test_code);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('path_test',$data);   
			$test_id = $this->db->insert_id();

			/////// Interpretation /////////////
            if(!empty($post['interpretation']))
            {
            	foreach($post['interpretation'] as $key=>$interp)
            	{
            	   $inter_data = '';	
                   if(isset($interpretation_data[$key]))
                   {
                   	 $inter_data = $interpretation_data[$key];
                   }
                   $inter_arr = array(
                                       'title'=>$interp['title'],
                                       'interpration'=>$inter_data,
                                       'test_id'=>$test_id,
                                       'range_condition'=>$interp['condition'],
                                     );
                   $this->db->insert('path_multi_interpration',$inter_arr);   
            	}
            	$this->session->unset_userdata('multi_interpretation');  
            }
            	
			////////////////////////////////////
			if(!empty($post['item_name']))
		    {
		    	$array_item = array_values($post['item_name']);
		    }
		    if(!empty($post['item_id']))
		    {
		    	$array_item_id = array_values($post['item_id']);
		    }
		    if(!empty($post['unit_value']))
		    {
		    	$array_unit_value = array_values($post['unit_value']);
		    }
		    if(!empty($post['quantity']))
		    {
		    	 $array_quantity = array_values($post['quantity']);
		    }
            

          	if(!empty($array_item))
			{
				 $i=0;
				foreach($array_item as $item_name)
				{
						$data_insert_items= 
						array('branch_id'=>$user_data['parent_id'],
						'type'=>1,
						'test_id'=>$test_id,
						'booking_id'=>0,
						'item_id'=>$array_item_id[$i],
						'unit_id'=>$array_unit_value[$i],
						'qty'=>$array_quantity[$i],	
						);

                       $this->db->insert('path_test_to_inventory_item',$data_insert_items);
                       //echo $this->db->last_query();
						$i++;


				}	
				//die;
			}

			// code to insert test suggestions
				if(isset($post['suggested_test_id']))
				{
					$total_rows=count($post['suggested_test_id']);
					for($i=0;$i<=$total_rows;$i++)
					{
						if($post['suggested_test_id'][$i]!=0)
						{
							$data_array=array('test_id'=>$test_id,
									  	  'suggested_test_id'=>$post['suggested_test_id'][$i], 
									  	  'test_condition'=>$post['test_condition'][$i],
									  	  'created_by'=>$user_data['id'],
									  	  'created_date'=>date('Y-m-d H:i:s'),
					    				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
									  	  );
							$this->db->insert('hms_path_test_suggesstion',$data_array);	
						}
					}
					
				}
			// code to insert test suggestions


		}
        
        if(isset($post['formula']))
        {
        	$this->db->where('test_id',$test_id);
        	$this->db->delete('path_test_formula'); 
        	if(!empty($post['formula']))
        	{
                $exp_formula = explode(',',$post['formula']); 
	            foreach($exp_formula as $formula)
	            {
	            	if(!empty($formula))
	            	{
	            		if(strpos($formula, '|') !== false)
                        {
                           $this->db->set('type','1');
                        }  
						$this->db->set('test_id',$test_id);
						$this->db->set('formula_val',$formula); 
						$this->db->insert('path_test_formula'); 
	            	} 
	            }
        	} 

        }

        if(isset($post['condition']))
        {
        	$this->db->where('test_id',$test_id);
        	$this->db->delete('path_test_condition'); 
            if(!empty($post['condition']))
            {
	            $exp_formula = explode(',',$post['condition']);
	            foreach($exp_formula as $condition)
	            {
	            	if(!empty($condition))
	            	{
						$this->db->set('test_id',$test_id);
						$this->db->set('condition_val',$condition); 
						$this->db->insert('path_test_condition'); 
	            	} 
	            }
                if(isset($post['condition_result']))
                {
					$this->db->set('test_id',$test_id);
					$this->db->set('condition_result',$post['condition_result']); 
					$this->db->insert('path_test_condition'); 
                } 	
            }
        	

        }
        
        if(isset($post['test_under']) && $post['test_type_id']==1)
        {
        	$this->db->where('parent_id',$test_id);
        	$this->db->delete('path_test_under'); 
            if(!empty($post['test_under']))
            {
                $exp_test_under = explode(',',$post['test_under']);
	            foreach($exp_test_under as $child_id)
	            {
	            	if($child_id>0)
	            	{
						$this->db->set('parent_id',$test_id);
						$this->db->set('child_id',$child_id); 
						$this->db->insert('path_test_under');
	            	}  
	            }
            } 

        }
        
        //echo '<pre>'; print_r($post);die;
		$this->db->where('test_id',$test_id);
		$this->db->delete('path_test_range'); 
		
		if(isset($post['gender']) && !empty($post['gender']))
		{
			$total_row = count($post['gender']);
            $gender = array_values($post['gender']);
            $start_age = array_values($post['start_age']);
            $end_age = array_values($post['end_age']);
            $range_start_pre = array_values($post['range_start_pre']);
            $range_start = array_values($post['range_start']);
            $range_start_post = array_values($post['range_start_post']);
            $range_end_pre = array_values($post['range_end_pre']);
            $range_end = array_values($post['range_end']);
            $range_end_post = array_values($post['range_end_post']);
            $age_type = array_values($post['age_type']);
			
			for($i=0; $i<$total_row; $i++)
			{  
				$range_data = array(
					                 'test_id'=>$test_id,
					                 'range_type'=>'1',
					                 'gender'=>$gender[$i],
					                 'start_age'=>$start_age[$i],
					                 'end_age'=>$end_age[$i],
					                 'age_type'=>$age_type[$i],
					                 'range_from_pre'=>$range_start_pre[$i],
					                 'range_from'=>$range_start[$i],
					                 'range_from_post'=>$range_start_post[$i],
					                 'range_to_pre'=>$range_end_pre[$i],
					                 'range_to'=>$range_end[$i],
					                 'range_to_post'=>$range_end_post[$i]
					               ); 
				$this->db->insert('path_test_range',$range_data);
				//echo '<pre>'; print_r($range_data);die;
			}
		}  
		$this->session->unset_userdata('interpretation');
		$this->session->unset_userdata('precuation');	
	}

	public function item_list($ids="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('path_test_to_inventory_item.*,path_test_to_inventory_item.qty as inventory_qty,hms_stock_item_unit.unit as first_unit,path_stock_category.category,path_item.item,path_item.qty');  
		$this->db->where('path_test_to_inventory_item.test_id',$ids);
		$this->db->where('path_test_to_inventory_item.booking_id',0);
		$this->db->where('path_test_to_inventory_item.branch_id',$users_data['parent_id']); 
		$this->db->join('path_item`','path_item.id=path_test_to_inventory_item.item_id','left'); 
		$this->db->join('path_stock_category`','path_stock_category.id=path_item.category_id','left');
		$this->db->join('hms_stock_item_unit`','hms_stock_item_unit.id=path_test_to_inventory_item.unit_id','left');
		$query = $this->db->get('path_test_to_inventory_item');
		$result = $query->result_array();
		//print '<pre>'; print_r($result);die;
		return $result;
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_test');
			//echo $this->db->last_query();die;
    	} 
    }

    public function deleteall($ids=array())
    {
    	if(!empty($ids))
    	{ 

    		$id_list = [];
    		foreach($ids as $id)
    		{
    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$branch_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('path_test'); 
    	} 
    }   
    
    public function test_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');    
        $this->db->order_by('id','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('path_test');
        $result = $query->result(); 
        return $result; 
    }

    public function test_method_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('test_method','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('path_test_method');
        $result = $query->result(); 
        return $result; 
    }
    public function sample_type_list(){
    	$users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('sample_type','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('path_sample_type');
        $result = $query->result(); 
        return $result;
    }

    public function test_heads_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('test_heads','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('path_test_heads');
        $result = $query->result(); 
        return $result; 
    }

    public function unit_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('unit','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('path_unit');
        $result = $query->result(); 
        return $result; 
    }

    public function get_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('default_vals','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('default_vals LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('path_default_vals');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->default_vals;
	        	}
	        }
	        return $response; 
    	} 
    }

    public function test_formula($id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_test_formula.formula_val, path_test_formula.type');     
        $this->db->where('path_test_formula.test_id',$id);  
        $query = $this->db->get('path_test_formula');
        $result = $query->result(); 
        return $result; 
    }

    public function test_condition($id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_test_condition.*');     
        $this->db->where('path_test_condition.test_id',$id);  
        $query = $this->db->get('path_test_condition');
        $result = $query->result(); 
        return $result; 
    }

    public function test_under_list($id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_test_under.child_id');     
        $this->db->where('path_test_under.parent_id',$id);  
        $query = $this->db->get('path_test_under');
        $result = $query->result(); 
        return $result; 
    }

    public function advance_range_list($id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_test_range.*');     
        $this->db->where('path_test_range.test_id',$id);  
        $query = $this->db->get('path_test_range');
        $result = $query->result(); 
        return $result; 
    }

    // public function remove_range($id="")
    // {
    // 	if($id>0)
    // 	{
    // 		$this->db->where('id',$id);  
    //         $this->db->delete('path_test_range');
    // 	}
    // } 
    public function inherit_test_create($tid)
    {
    	$users_data = $this->session->userdata('auth_users'); 
        ////////////// Rate Plan Data ////////
		 $this->db->select('path_rate_plan.*');     
		 $this->db->where('hms_branch.id',$users_data['parent_id']);  
		 $this->db->join('path_rate_plan','path_rate_plan.id = hms_branch.rate_id');
	     $query_branch = $this->db->get('hms_branch'); 
	     $result_branch = $query_branch->result();
	     $result_branch = $result_branch[0];  
        /////////////////////////////////////
        
        $this->db->select('path_test.*');     
        $this->db->where('path_test.id',$tid);  
        $query = $this->db->get('path_test');
        $result = $query->result_array();  
        if($result)
        {
        	$test_code = generate_unique_id(25);
        	foreach($result as $post)
        	{ 	
        	  if(!empty($result_branch) && $users_data['users_role'])
        	  {
        	  	 $test_master_price = $result_branch->master_rate;  
        	  	 $test_base_price = $result_branch->base_rate; 
                 
                 ///////// Master Calculation //////////////////
                 if($result_branch->master_type==1)
                 {
                 	$pos_master = strpos($result_branch->master_rate,'-');
                    if ($pos_master === true) 
                       {
                          $master_price = str_replace('-', '',$result_branch->master_rate);
                          $master_price = $post['rate']-(($post['rate']/100)*$result_branch->master_rate);
                       }
                    else
                       {
							$master_price = str_replace('+', '',$result_branch->master_rate);
							$master_price = $post['rate']+(($post['rate']/100)*$result_branch->master_rate); 
                       } 
                 }
                 else
                 {
                    $pos_master = strpos($result_branch->master_rate,'-');
                    if ($pos_master === true) 
                       {
                          $master_price = str_replace('-', '',$result_branch->master_rate);
                          $master_price = $post['rate']-$result_branch->master_rate;
                       }
                    else
                       {
							$master_price = str_replace('+', '',$result_branch->master_rate);
							$master_price = $post['rate']+$result_branch->master_rate; 
                       } 
                 }
                 //////////////////////////////////////////////
                 //////////////// base rate calculation ///////
                 if($result_branch->base_type==1)
                 {
                 	$pos_master = strpos($result_branch->base_rate,'-');
                    if ($pos_master === true) 
                       {
                          $base_price = str_replace('-', '',$result_branch->base_rate);
                          $base_price = $post['base_rate']-(($post['base_rate']/100)*$result_branch->base_rate);
                       }
                    else
                       {
							$base_price = str_replace('+', '',$result_branch->base_rate);
							$base_price = $post['base_rate']+(($post['base_rate']/100)*$result_branch->base_rate); 
                       }  
                 }
                 else
                 {
                    $pos_master = strpos($result_branch->base_rate,'-');
                    if ($pos_master === true) 
                       {
                          $base_price = str_replace('-', '',$result_branch->base_rate);
                          $base_price = $post['base_rate']-$result_branch->base_rate;
                       }
                    else
                       {
							$base_price = str_replace('+', '',$result_branch->base_rate);
							$base_price = $post['base_rate']+$result_branch->base_rate; 
                       }
                 }
                 /////////////////////////////////////////////  
        	  }
        	  else
        	  {
				$master_price = $post['rate'];
				$base_price = $post['base_rate'];
        	  }	

        	  ///////////////// Set Download test head //////////
				$this->db->select('path_test_heads.*');     
				$this->db->where('path_test_heads.id',$post['test_head_id']);  
				$p_thead_query = $this->db->get('path_test_heads');
				$p_thead_result = $p_thead_query->result_array();  
                
                if(!empty($p_thead_result))
                {
					$this->db->select('path_test_heads.*');     
					$this->db->where('path_test_heads.test_heads',$p_thead_result[0]['test_heads']); 
					$this->db->where('path_test_heads.branch_id',$users_data['parent_id']);  
					$c_thead_query = $this->db->get('path_test_heads');
					$c_thead_result = $c_thead_query->result_array();
                    
                    if(!empty($c_thead_result))
                    {
                       $test_head_id = $c_thead_result[0]['id'];
                    }
                    else
                    {
                    	$test_heads_data = array( 
										'branch_id'=>$users_data['parent_id'],
										'dept_id'=>$post['dept_id'],
										'test_heads'=>$p_thead_result[0]['test_heads'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_test_heads',$test_heads_data); 
						$test_head_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_head_id = 0;
                } 
        	  //////////////////////////////////////////////

              ///////////////// Set Download Method //////////
				$this->db->select('path_test_method.*');     
				$this->db->where('path_test_method.id',$post['method_id']);  
				$p_tmethod_query = $this->db->get('path_test_method');
				$p_tmethod_result = $p_tmethod_query->result_array();  
                
                if(!empty($p_tmethod_result))
                {
					$this->db->select('path_test_method.*');     
					$this->db->where('path_test_method.test_method',$p_tmethod_result[0]['test_method']); 
					$this->db->where('path_test_method.branch_id',$users_data['parent_id']);  
					$c_tmethod_query = $this->db->get('path_test_method');
					$c_tmethod_result = $c_tmethod_query->result_array();
                    
                    if(!empty($c_tmethod_result))
                    {
                       $test_method_id = $c_tmethod_result[0]['id'];
                    }
                    else
                    {
                    	$test_method_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'test_method'=>$p_tmethod_result[0]['test_method'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_test_method',$test_method_data); 
						$test_method_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_method_id = 0;
                } 
        	  //////////////////////////////////////////////  

              ///////////////// Set Test Unit //////////
				$this->db->select('path_unit.*');     
				$this->db->where('path_unit.id',$post['unit_id']);  
				$p_unit_query = $this->db->get('path_unit');
				$p_unit_result = $p_unit_query->result_array();  
                
                if(!empty($p_unit_result))
                {
					$this->db->select('path_unit.*');     
					$this->db->where('path_unit.unit',$p_unit_result[0]['unit']); 
					$this->db->where('path_unit.branch_id',$users_data['parent_id']);  
					$c_unit_query = $this->db->get('path_unit');
					$c_unit_result = $c_unit_query->result_array();
                    
                    if(!empty($c_unit_result))
                    {
                       $test_unit_id = $c_unit_result[0]['id'];
                    }
                    else
                    {
                    	$test_unit_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'unit'=>$p_unit_result[0]['unit'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_unit',$test_unit_data); 
						$test_unit_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_unit_id = 0;
                } 
        	  ////////////////////////////////////////////// 

        	  ///////////////// Set Test Sample Type //////////
				$this->db->select('path_sample_type.*');     
				$this->db->where('path_sample_type.id',$post['sample_test']);  
				$p_stype_query = $this->db->get('path_sample_type');
				$p_stype_result = $p_stype_query->result_array();  
                
                if(!empty($p_stype_result))
                {
					$this->db->select('path_sample_type.*');     
					$this->db->where('path_sample_type.sample_type',$p_stype_result[0]['sample_type']); 
					$this->db->where('path_sample_type.branch_id',$users_data['parent_id']);  
					$c_stype_query = $this->db->get('path_sample_type');
					$c_stype_result = $c_stype_query->result_array();
                    
                    if(!empty($c_stype_result))
                    {
                       $sample_type_id = $c_stype_result[0]['id'];
                    }
                    else
                    {
                    	$test_sample_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'sample_type'=>$p_stype_result[0]['sample_type'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_sample_type',$test_sample_data); 
						$sample_type_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$sample_type_id = 0;
                } 
        	  //////////////////////////////////////////////  
  

        	  $data = array(
							'download_id'=>$tid, 
							'branch_id'=>$users_data['parent_id'], 
							'test_code'=>$test_code,
							'dept_id'=>$post['dept_id'],
							'test_head_id'=>$test_head_id,
							'test_name'=>$post['test_name'], 
							'default_value'=>$post['default_value'],
							'rate'=>$master_price,
							'base_rate'=>$base_price,
							'method_id'=>$test_method_id,
							'unit_id'=>$test_unit_id, 
							'range_to'=>$post['range_to'],
							'range_from'=>$post['range_from'], 
							'test_type_id'=>$post['test_type_id'],
							'sample_test'=>$sample_type_id,
							'interpretation'=>$post['interpretation'],
							'precaution'=>$post['precaution'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['id'],
							'created_date'=>date('Y-m-d H:i:s') 
        	  	           ); 
			  $this->db->insert('path_test',$data); 
			  $test_id = $this->db->insert_id();   
              return $test_id;
			  ////// Formula start /////////////
			  /*	$this->db->select('path_test_formula.*');     
				$this->db->where('path_test_formula.test_id',$tid);  
				$this->db->order_by('id','ASC');
				$query = $this->db->get('path_test_formula');
				$formula_list = $query->result();
			    if(!empty($formula_list))
			    {
			    	foreach($formula_list as $formula)
			    	{
			    		$formula_data = array(
			    			                   'test_id'=>$test_id,
			    			                   'formula_val'=>$formula->formula_val
			    			                 );
			    		$this->db->insert('path_test_formula',$formula_data); 
			    	}
			    }
			   */ 
			  ////// End Formula ///////////////

			  ////// Condition start /////////////
			  /*	$this->db->select('path_test_condition.*');     
				$this->db->where('path_test_condition.test_id',$tid);  
				$this->db->order_by('id','ASC');
				$query = $this->db->get('path_test_condition');
				$condition_list = $query->result();
			    if(!empty($condition_list))
			    {
			    	foreach($condition_list as $condition)
			    	{
			    		$condition_data = array(
			    			                   'test_id'=>$test_id,
			    			                   'condition_val'=>$condition->condition_val
			    			                 );
			    		$this->db->insert('path_test_condition',$condition_data); 
			    	}
			    }
			    */
			  ////// End Formula /////////////// 


        	}
        }
    }
    public function download_test($tid)
    {
    	$users_data = $this->session->userdata('auth_users'); 
        ////////////// Rate Plan Data ////////
		 $this->db->select('path_rate_plan.*');     
		 $this->db->where('hms_branch.id',$users_data['parent_id']);  
		 $this->db->join('path_rate_plan','path_rate_plan.id = hms_branch.rate_id');
	     $query_branch = $this->db->get('hms_branch'); 
	     $result_branch = $query_branch->result();
	     $result_branch = $result_branch[0];  
        /////////////////////////////////////
        
        $this->db->select('path_test.*');     
        $this->db->where('path_test.id',$tid);  
        $query = $this->db->get('path_test');
        $result = $query->result_array();  
        if($result)
        {
        	$test_code = generate_unique_id(25);
        	foreach($result as $post)
        	{ 	
        	  if(!empty($result_branch) && $users_data['users_role'])
        	  {
        	  	 $test_master_price = $result_branch->master_rate;  
        	  	 $test_base_price = $result_branch->base_rate; 
                 
                 ///////// Master Calculation //////////////////
                 if($result_branch->master_type==1)
                 {
                 	$pos_master = strpos($result_branch->master_rate,'-');
                    if ($pos_master === true) 
                       {
                          $master_price = str_replace('-', '',$result_branch->master_rate);
                          $master_price = $post['rate']-(($post['rate']/100)*$result_branch->master_rate);
                       }
                    else
                       {
							$master_price = str_replace('+', '',$result_branch->master_rate);
							$master_price = $post['rate']+(($post['rate']/100)*$result_branch->master_rate); 
                       } 
                 }
                 else
                 {
                    $pos_master = strpos($result_branch->master_rate,'-');
                    if ($pos_master === true) 
                       {
                          $master_price = str_replace('-', '',$result_branch->master_rate);
                          $master_price = $post['rate']-$result_branch->master_rate;
                       }
                    else
                       {
							$master_price = str_replace('+', '',$result_branch->master_rate);
							$master_price = $post['rate']+$result_branch->master_rate; 
                       } 
                 }
                 //////////////////////////////////////////////
                 //////////////// base rate calculation ///////
                 if($result_branch->base_type==1)
                 {
                 	$pos_master = strpos($result_branch->base_rate,'-');
                    if ($pos_master === true) 
                       {
                          $base_price = str_replace('-', '',$result_branch->base_rate);
                          $base_price = $post['base_rate']-(($post['base_rate']/100)*$result_branch->base_rate);
                       }
                    else
                       {
							$base_price = str_replace('+', '',$result_branch->base_rate);
							$base_price = $post['base_rate']+(($post['base_rate']/100)*$result_branch->base_rate); 
                       }  
                 }
                 else
                 {
                    $pos_master = strpos($result_branch->base_rate,'-');
                    if ($pos_master === true) 
                       {
                          $base_price = str_replace('-', '',$result_branch->base_rate);
                          $base_price = $post['base_rate']-$result_branch->base_rate;
                       }
                    else
                       {
							$base_price = str_replace('+', '',$result_branch->base_rate);
							$base_price = $post['base_rate']+$result_branch->base_rate; 
                       }
                 }
                 /////////////////////////////////////////////  
        	  }
        	  else
        	  {
				$master_price = $post['rate'];
				$base_price = $post['base_rate'];
        	  }	

        	  ///////////////// Set Download test head //////////
				$this->db->select('path_test_heads.*');     
				$this->db->where('path_test_heads.id',$post['test_head_id']);  
				$p_thead_query = $this->db->get('path_test_heads');
				$p_thead_result = $p_thead_query->result_array();  
                
                if(!empty($p_thead_result))
                {
					$this->db->select('path_test_heads.*');     
					$this->db->where('path_test_heads.test_heads',trim($p_thead_result[0]['test_heads'])); 
					$this->db->where('path_test_heads.branch_id',$users_data['parent_id']);  
					$c_thead_query = $this->db->get('path_test_heads');
					$c_thead_result = $c_thead_query->result_array();
                    
                    if(!empty($c_thead_result))
                    {
                       $test_head_id = $c_thead_result[0]['id'];
                    }
                    else
                    {
                    	$test_heads_data = array( 
										'branch_id'=>$users_data['parent_id'],
										'dept_id'=>$post['dept_id'],
										'test_heads'=>$p_thead_result[0]['test_heads'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_test_heads',$test_heads_data); 
						$test_head_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_head_id = 0;
                } 
        	  //////////////////////////////////////////////

              ///////////////// Set Download Method //////////
				$this->db->select('path_test_method.*');     
				$this->db->where('path_test_method.id',$post['method_id']);  
				$p_tmethod_query = $this->db->get('path_test_method');
				$p_tmethod_result = $p_tmethod_query->result_array();  
                
                if(!empty($p_tmethod_result))
                {
					$this->db->select('path_test_method.*');     
					$this->db->where('path_test_method.test_method',$p_tmethod_result[0]['test_method']); 
					$this->db->where('path_test_method.branch_id',$users_data['parent_id']);  
					$c_tmethod_query = $this->db->get('path_test_method');
					$c_tmethod_result = $c_tmethod_query->result_array();
                    
                    if(!empty($c_tmethod_result))
                    {
                       $test_method_id = $c_tmethod_result[0]['id'];
                    }
                    else
                    {
                    	$test_method_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'test_method'=>$p_tmethod_result[0]['test_method'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_test_method',$test_method_data); 
						$test_method_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_method_id = 0;
                } 
        	  //////////////////////////////////////////////  

              ///////////////// Set Test Unit //////////
				$this->db->select('path_unit.*');     
				$this->db->where('path_unit.id',$post['unit_id']);  
				$p_unit_query = $this->db->get('path_unit');
				$p_unit_result = $p_unit_query->result_array();  
                
                if(!empty($p_unit_result))
                {
					$this->db->select('path_unit.*');     
					$this->db->where('path_unit.unit',$p_unit_result[0]['unit']); 
					$this->db->where('path_unit.branch_id',$users_data['parent_id']);  
					$c_unit_query = $this->db->get('path_unit');
					$c_unit_result = $c_unit_query->result_array();
                    
                    if(!empty($c_unit_result))
                    {
                       $test_unit_id = $c_unit_result[0]['id'];
                    }
                    else
                    {
                    	$test_unit_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'unit'=>$p_unit_result[0]['unit'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_unit',$test_unit_data); 
						$test_unit_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_unit_id = 0;
                } 
        	  ////////////////////////////////////////////// 

        	  ///////////////// Set Test Sample Type //////////
				$this->db->select('path_sample_type.*');     
				$this->db->where('path_sample_type.id',$post['sample_test']);  
				$p_stype_query = $this->db->get('path_sample_type');
				$p_stype_result = $p_stype_query->result_array();  
                
                if(!empty($p_stype_result))
                {
					$this->db->select('path_sample_type.*');     
					$this->db->where('path_sample_type.sample_type',$p_stype_result[0]['sample_type']); 
					$this->db->where('path_sample_type.branch_id',$users_data['parent_id']);  
					$c_stype_query = $this->db->get('path_sample_type');
					$c_stype_result = $c_stype_query->result_array();
                    
                    if(!empty($c_stype_result))
                    {
                       $sample_type_id = $c_stype_result[0]['id'];
                    }
                    else
                    {
                    	$test_sample_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'sample_type'=>$p_stype_result[0]['sample_type'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_sample_type',$test_sample_data); 
						$sample_type_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$sample_type_id = 0;
                } 
        	  //////////////////////////////////////////////  

                

        	  $data = array(
							'download_id'=>$tid, 
							'branch_id'=>$users_data['parent_id'], 
							'test_code'=>$test_code,
							'dept_id'=>$post['dept_id'],
							'test_head_id'=>$test_head_id,
							'test_name'=>$post['test_name'], 
							'default_value'=>$post['default_value'],
							'rate'=>$master_price,
							'base_rate'=>$base_price,
							'method_id'=>$test_method_id,
							'unit_id'=>$test_unit_id, 
							'range_to'=>$post['range_to'],
							'range_from'=>$post['range_from'], 
							'test_type_id'=>$post['test_type_id'],
							'sample_test'=>$sample_type_id,
							'interpretation'=>$post['interpretation'],
							'precaution'=>$post['precaution'],
							'sort_order'=>$post['sort_order'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['id'],
							'created_date'=>date('Y-m-d H:i:s') 
        	  	           ); 
			  $this->db->insert('path_test',$data); 
			  $test_id = $this->db->insert_id();   
			  /////////////Interpretation////////////
                $this->db->select('path_multi_interpration.*');     
				$this->db->where('path_multi_interpration.test_id',$tid);  
				$this->db->order_by('id','ASC');
				$query = $this->db->get('path_multi_interpration');
				$interpreation_list = $query->result();
			    if(!empty($interpreation_list))
			    {
			    	$i=1;
			    	foreach($interpreation_list as $interpreation)
			    	{ 
			    		$under_data = array(
			    			                   'title'=>'Title'.$i,
			    			                   'interpration'=>$interpreation->interpration,
			    			                   'test_id'=>$test_id,
			    			                   'range_condition'=>$interpreation->range_condition
			    			                 );
			    		$this->db->insert('path_multi_interpration',$under_data); 
			    		$i++;
			    	}
			    } 
			  //////////// End Interpretation //////
              
              /////////////Test Range ////////////
                $this->db->select('path_test_range.*');     
				$this->db->where('path_test_range.test_id',$tid);  
				$this->db->order_by('id','ASC');
				$query = $this->db->get('path_test_range');
				$range_list = $query->result();
			    if(!empty($range_list))
			    { 
			    	foreach($range_list as $range)
			    	{ 
			    		$range_data = array(
			    			                   'test_id'=>$test_id,
			    			                   'range_type'=>$range->range_type,
			    			                   'gender'=>$range->gender,
			    			                   'start_age'=>$range->start_age,
			    			                   'end_age'=>$range->end_age,
			    			                   'age_type'=>$range->age_type,
			    			                   'range_from'=>$range->range_from,
			    			                   'range_to'=>$range->range_to,
			    			                   'range_from_pre'=>$range->range_from_pre,
			    			                   'range_from_post'=>$range->range_from_post,
			    			                   'range_to_pre'=>$range->range_to_pre,
			    			                   'range_to_post'=>$range->range_to_post
			    			               );
			    		$this->db->insert('path_test_range',$range_data);  
			    	}
			    } 
			  //////////// End Range //////

			  /////////////Test Suggestion ////////////
                /*$this->db->select('hms_path_test_suggesstion.*');     
				$this->db->where('hms_path_test_suggesstion.test_id',$tid);  
				$this->db->order_by('id','ASC');
				$query = $this->db->get('hms_path_test_suggesstion');
				$suggesstion_list = $query->result();
			    if(!empty($suggesstion_list))
			    { 
			    	foreach($suggesstion_list as $suggesstion)
			    	{ 
			    		$suggesstion_data = array(
			    			                   'test_id'=>$test_id,
			    			                   'suggested_test_id'=>$range->range_type,
			    			                   'gender'=>$range->gender,
			    			                   'start_age'=>$range->start_age,
			    			                   'end_age'=>$range->end_age,
			    			                   'age_type'=>$range->age_type,
			    			                   'range_from'=>$range->range_from,
			    			                   'range_to'=>$range->range_to,
			    			                   'range_from_pre'=>$range->range_from_pre,
			    			                   'range_from_post'=>$range->range_from_post,
			    			                   'range_to_pre'=>$range->range_to_pre,
			    			                   'range_to_post'=>$range->range_to_post
			    			               );
			    		$this->db->insert('path_test_range',$range_data);  
			    	}
			    } */
			  //////////// End Suggestion //////  

              ////// Under start /////////////
			 	$this->db->select('path_test_under.*');     
				$this->db->where('path_test_under.parent_id',$tid);  
				$this->db->order_by('id','ASC');
				$query = $this->db->get('path_test_under');
				$under_list = $query->result();
			    if(!empty($under_list))
			    {
			    	foreach($under_list as $under)
			    	{
			    		$u_test_data = get_test($under->child_id); 
			    		$this->db->select('*');
			    		$this->db->from('path_test');
			    		$this->db->where('lower(test_name)',trim(strtolower($u_test_data->test_name)));
			    		$this->db->where('branch_id', $users_data['parent_id']);
			    		$query = $this->db->get();
                        $check_u_test = $query->row_array();
                        if(!empty($check_u_test))
                        {
                           $under_test_id = $check_u_test['id'];
                        }
                        else
                        {  
                            $under_test_id = $this->inherit_test_create($under->child_id);
                        }
			    		$under_data = array(
			    			                   'parent_id'=>$test_id,
			    			                   'child_id'=>$under_test_id
			    			                 );
			    		$this->db->insert('path_test_under',$under_data); 
			    	}
			    }
			   
			  return $test_id;  
			  ////// End Under ///////////////

			  ////// Formula start /////////////
			    $this->db->select('path_test_formula.*');     
				$this->db->where('path_test_formula.test_id',$tid);  
				$this->db->order_by('id','ASC');
				$query = $this->db->get('path_test_formula');
				$formula_list = $query->result();
			    if(!empty($formula_list))
			    {
			    	foreach($formula_list as $formula)
			    	{ 
						    $u_test_data = get_test($formulat->formula_val); 
							$this->db->select('*');
							$this->db->from('path_test');
							$this->db->where('lower(test_name)',trim(strtolower($u_test_data->test_name)));
							$this->db->where('branch_id', $users_data['parent_id']);
							$query = $this->db->get();
							$check_f_test = $query->row_array();
							if(!empty($check_f_test))
							{
							   $formula_test_id = $check_f_test['id'];
							}
							else
							{  
								$formula_test_id = $this->inherit_test_create($formulat->formula_val);
							}
							
							
							if(is_numeric($formulat->formula_val) && $formulat->type==0)
						    {	
								$this->db->set('test_id',$formula_test_id);
								$this->db->set('formula_val',$formula->formula_val);
								$this->db->insert('path_test_formula');
						    } 
							else
							{
								$this->db->set('test_id',$formula_test_id);
								$this->db->set('formula_val',$formula->formula_val);
								if($formulat->type==1)
								{ 
								   $this->db->set('type',1);
								}				 
								$this->db->insert('path_test_formula');
							}
			    		
			    	}
			    } 
			  ////// End Formula ///////////////

			  ////// Condition start /////////////
			    /*$this->db->select('path_test_condition.*');     
				$this->db->where('path_test_condition.test_id',$tid);  
				$this->db->order_by('id','ASC');
				$query = $this->db->get('path_test_condition');
				$condition_list = $query->result();
			    if(!empty($condition_list))
			    {
				    if(is_numeric($condition->condition_val))
					{
					   
					}
			    	foreach($condition_list as $condition)
			    	{
			    		$condition_data = array(
			    			                   'test_id'=>$test_id,
			    			                   'condition_val'=>$condition->condition_val
			    			                 );
			    		$this->db->insert('path_test_condition',$condition_data); 
			    	}
			    } */
			  ////// End Formula /////////////// 


        	}
        }
    }
    

    public function downloadall($ids=array())
    {
    	$users_data = $this->session->userdata('auth_users');  
    	if(!empty($ids))
    	{ 
    		foreach($ids as $tid)
    		{
    			$this->download_test($tid);  
    		}
    	}
    }
     
     public function save_sort_order_data($id='',$sort_order_value=''){
        if(!empty($id) && $sort_order_value!=""){
        	$this->db->set('sort_order',$sort_order_value);
        	$this->db->where('id',$id);
        	$result='';
        	if($this->db->update('path_test')){
        		$result='true';
        		return $result;
        	}

        }
    }

    private function calc_string( $mathString )
	  {
	          $cf_DoCalc = create_function("", "return (" . $mathString . ");" );
	         
	          return $cf_DoCalc();
	  }  

	public function branch_rate_plan()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$result_branch = [];
		if($users_data['users_role']==2 && $users_data['parent_id']>0)
		{
			$this->db->select('path_rate_plan.*');     
			$this->db->where('hms_branch.id',$users_data['parent_id']);  
			$this->db->join('path_rate_plan','path_rate_plan.id = hms_branch.rate_id');
			$query_branch = $this->db->get('hms_branch'); 
			$result_branch = $query_branch->result();
		}  
		return $result_branch;
	} 

	public function doctor_test_rate($branch_id="",$doctor_id="",$test_id="")
	{
	   $arr = [];
       if(!empty($test_id))
       {
			$this->db->select('path_test_to_doctors.*');     
			if(!empty($doctor_id))
			{
               $this->db->where('path_test_to_doctors.doc_id',$doctor_id); 
			}  
			else
			{
				$this->db->where('path_test_to_doctors.doc_id',0); 
			}
			if(!empty($branch_id))
			{
               $this->db->where('path_test_to_doctors.branch_id',$branch_id); 
			}  
			$this->db->where('path_test_to_doctors.test_id',$test_id);   
			$query = $this->db->get('path_test_to_doctors'); 
			$result = $query->row();
			if(!empty($result))
			{
               $arr = array('rate'=>$result->rate, 'base_rate'=>$result->base_rate);
			} 
			return $arr;
       }
	} 

    
    public function admin_download_test($tid)
    {
    	 $users_data = $this->session->userdata('auth_users'); 
         ////////////// Rate Plan Data ////////
		 $this->db->select('path_rate_plan.*');     
		 $this->db->where('hms_branch.id',$users_data['parent_id']);  
		 $this->db->join('path_rate_plan','path_rate_plan.id = hms_branch.rate_id');
	     $query_branch = $this->db->get('hms_branch'); 
	     $result_branch = $query_branch->result();
	     $result_branch = $result_branch[0];  
        /////////////////////////////////////
        
        $this->db->select('path_test.*');     
        $this->db->where('path_test.id',$tid);  
        $query = $this->db->get('path_test');
        $result = $query->result_array();  
        if($result)
        {
        	$test_code = generate_unique_id(25);
        	foreach($result as $post)
        	{ 	
        	    $master_price = $post['rate'];
				$base_price = $post['base_rate'];

        	  ///////////////// Set Download test head //////////
				$this->db->select('path_test_heads.*');     
				$this->db->where('path_test_heads.id',$post['test_head_id']);  
				$p_thead_query = $this->db->get('path_test_heads');
				$p_thead_result = $p_thead_query->result_array();  
                
                if(!empty($p_thead_result))
                {
					$this->db->select('path_test_heads.*');     
					$this->db->where('path_test_heads.test_heads',trim($p_thead_result[0]['test_heads'])); 
					$this->db->where('path_test_heads.branch_id',$users_data['parent_id']);  
					$c_thead_query = $this->db->get('path_test_heads');
					$c_thead_result = $c_thead_query->result_array();
                    
                    if(!empty($c_thead_result))
                    {
                       $test_head_id = $c_thead_result[0]['id'];
                    }
                    else
                    {
                    	$test_heads_data = array( 
										'branch_id'=>$users_data['parent_id'],
										'dept_id'=>$post['dept_id'],
										'test_heads'=>$p_thead_result[0]['test_heads'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_test_heads',$test_heads_data); 
						$test_head_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_head_id = 0;
                } 
        	  //////////////////////////////////////////////

              ///////////////// Set Download Method //////////
				$this->db->select('path_test_method.*');     
				$this->db->where('path_test_method.id',$post['method_id']);  
				$p_tmethod_query = $this->db->get('path_test_method');
				$p_tmethod_result = $p_tmethod_query->result_array();  
                
                if(!empty($p_tmethod_result))
                {
					$this->db->select('path_test_method.*');     
					$this->db->where('path_test_method.test_method',$p_tmethod_result[0]['test_method']); 
					$this->db->where('path_test_method.branch_id',$users_data['parent_id']);  
					$c_tmethod_query = $this->db->get('path_test_method');
					$c_tmethod_result = $c_tmethod_query->result_array();
                    
                    if(!empty($c_tmethod_result))
                    {
                       $test_method_id = $c_tmethod_result[0]['id'];
                    }
                    else
                    {
                    	$test_method_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'test_method'=>$p_tmethod_result[0]['test_method'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_test_method',$test_method_data); 
						$test_method_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_method_id = 0;
                } 
        	  //////////////////////////////////////////////  

              ///////////////// Set Test Unit //////////
				$this->db->select('path_unit.*');     
				$this->db->where('path_unit.id',$post['unit_id']);  
				$p_unit_query = $this->db->get('path_unit');
				$p_unit_result = $p_unit_query->result_array();  
                
                if(!empty($p_unit_result))
                {
					$this->db->select('path_unit.*');     
					$this->db->where('path_unit.unit',$p_unit_result[0]['unit']); 
					$this->db->where('path_unit.branch_id',$users_data['parent_id']);  
					$c_unit_query = $this->db->get('path_unit');
					$c_unit_result = $c_unit_query->result_array();
                    
                    if(!empty($c_unit_result))
                    {
                       $test_unit_id = $c_unit_result[0]['id'];
                    }
                    else
                    {
                    	$test_unit_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'unit'=>$p_unit_result[0]['unit'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_unit',$test_unit_data); 
						$test_unit_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$test_unit_id = 0;
                } 
        	  ////////////////////////////////////////////// 

        	  ///////////////// Set Test Sample Type //////////
				$this->db->select('path_sample_type.*');     
				$this->db->where('path_sample_type.id',$post['sample_test']);  
				$p_stype_query = $this->db->get('path_sample_type');
				$p_stype_result = $p_stype_query->result_array();  
                
                if(!empty($p_stype_result))
                {
					$this->db->select('path_sample_type.*');     
					$this->db->where('path_sample_type.sample_type',$p_stype_result[0]['sample_type']); 
					$this->db->where('path_sample_type.branch_id',$users_data['parent_id']);  
					$c_stype_query = $this->db->get('path_sample_type');
					$c_stype_result = $c_stype_query->result_array();
                    
                    if(!empty($c_stype_result))
                    {
                       $sample_type_id = $c_stype_result[0]['id'];
                    }
                    else
                    {
                    	$test_sample_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'sample_type'=>$p_stype_result[0]['sample_type'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_sample_type',$test_sample_data); 
						$sample_type_id = $this->db->insert_id();    
                    }
					

                } 
                else
                {
                	$sample_type_id = 0;
                } 
        	  //////////////////////////////////////////////  

                

        	  $data = array(
							'download_id'=>0, 
							'branch_id'=>$users_data['parent_id'], 
							'test_code'=>$test_code,
							'old_id'=>$post['id'],
							'dept_id'=>$post['dept_id'],
							'test_head_id'=>$test_head_id,
							'test_name'=>$post['test_name'], 
							'default_value'=>$post['default_value'],
							'rate'=>$master_price,
							'base_rate'=>$base_price,
							'method_id'=>$test_method_id,
							'unit_id'=>$test_unit_id, 
							'range_to'=>$post['range_to'],
							'range_from'=>$post['range_from'], 
							'test_type_id'=>$post['test_type_id'],
							'sample_test'=>$sample_type_id,
							'result_type'=>$post['result_type'],
							'interpretation'=>$post['interpretation'],
							'sort_order'=>$post['sort_order'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['id'],
							'created_date'=>date('Y-m-d H:i:s') 
        	  	           );     
			  
			  $this->db->insert('path_test',$data); 
			  $test_id = $this->db->insert_id();   
			  
                 


        	}
        }
    }

    public function panel_test_rate($branch_id="",$panel_id="",$test_id="")
	{
		//print_r($test_id);die;
	   $arr = [];
       if(!empty($test_id))
       {
			
			$cases='';
			if(!empty($panel_id))
			{

			$cases= '(CASE WHEN path_panel_price.charge>0 THEN path_panel_price.charge ELSE path_test.rate END) as path_price';
			}
			else
			{
			$cases= 'path_test.rate as path_price';
			}
			$this->db->select("path_test.*,".$cases.",path_panel_price.panel_id,path_panel_price.test_head_id,path_panel_price.test_id"); 
			if(!empty($panel_id))
			{
			$this->db->join('path_panel_price','path_panel_price.test_id=path_test.id AND path_panel_price.panel_id = "'.$panel_id.'"','left');
			}
			else
			{
			$this->db->join('path_panel_price','path_panel_price.test_id=path_test.id','left'); 
			}
			if(!empty($branch_id))
			{
               $this->db->where('path_panel_price.branch_id',$branch_id); 
			}  

			
			$this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id','left');
			$this->db->where('path_panel_price.test_id',$test_id);
			$this->db->where('path_panel_price.panel_id',$panel_id);
			$this->db->group_by('path_test.id');   
			$query = $this->db->get('path_test'); 
			$result = $query->row();
			//echo $this->db->last_query();die;
			if(!empty($result))
			{
               $arr = array('path_price'=>$result->path_price,'panel_id'=>$result->panel_id,'test_head_id'=>$result->test_head_id,'test_id'=>$result->test_id);
			} 
			return $arr;
       }
	} 
    
	public function get_test_list_autocomplete($requested_term, $branch_id)
	{
		$this->db->select('id,test_name');
		$this->db->from('path_test');
		$this->db->where('branch_id',$branch_id);
		$this->db->where('test_name like  "%'.$requested_term.'%" ');
		$res=$this->db->get();

		//echo $this->db->last_query();die;
		$data_array=array();
		if($res->num_rows() > 0)
		{
			$rec_array=array();
			foreach($res->result() as $data )
			{
				$rec_array['value']=$data->test_name;
				$rec_array['id']=$data->id;
				array_push($data_array, $rec_array);
			}
			return $data_array;
		}

	}

	public function get_test_suggestions($test_id)
	{
		$this->db->select('hms_path_test_suggesstion.*, path_test.test_name');
		$this->db->from('hms_path_test_suggesstion');
		$this->db->join('path_test', 'path_test.id=hms_path_test_suggesstion.suggested_test_id');
		$this->db->where('hms_path_test_suggesstion.test_id',$test_id);
		$res=$this->db->get();
		if($res->num_rows() > 0 )
		{
			return $res->result();
		}
		else
		{
			return "empty";
		}
	}

	public function test_multi_interpretation($test_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('path_multi_interpration.*');
		$this->db->where('path_multi_interpration.test_id',$test_id); 
		$query = $this->db->get('path_multi_interpration'); 
		$result = $query->result_array();
		$data = [];
		$interpration = [];
		if(!empty($result))
		{
			$i = 0;
			foreach($result as $inter)
			{
				$data[$i]['title'] = $inter['title'];
				$data[$i]['condition'] = $inter['range_condition'];
				$interpration[$i] = $inter['interpration'];
				$i++;
			}
		}
		$this->session->set_userdata('multi_interpretation',$interpration);
		return $data;
	}

	public function get_test_multi_interpration($test_id="",$condition="")
	{
		$this->db->select('path_multi_interpration.*');
		if(!empty($test_id))
		{
			$this->db->where('path_multi_interpration.test_id',$test_id); 
		}
		if($condition!="")
		{
            $this->db->where('path_multi_interpration.range_condition',$condition);
		}  
		$query = $this->db->get('path_multi_interpration'); 
		//echo $this->db->last_query();die;
		return $query->result_array();
	}



	public function save_all_test($test_all_datas= array())
    {
    	//echo "<pre>"; print_r($test_all_datas); exit; 
    	$users_data = $this->session->userdata('auth_users'); 

    	///////////////// Set department //////////
		if(!empty($test_all_datas))
		{
		    foreach($test_all_datas as $test_all_data)
		    {
		    	if(!empty($test_all_data['dept_id']))
		    	{
		    			$this->db->select('hms_department.*');     
						$this->db->where('LOWER(hms_department.department)',strtolower($test_all_data['dept_id']));
						$this->db->where('(hms_department.branch_id='.$users_data['parent_id'].' OR hms_department.branch_id=0)');
						$this->db->where('hms_department.module',5);  
						$department_query = $this->db->get('hms_department');
						$department_result = $department_query->result_array(); 
						
		                if(!empty($department_result))
		                {
							$department_id = $department_result[0]['id'];
		                }
		                else
		                {
		                	$department_data = array( 
												'branch_id'=>$users_data['parent_id'],
												'module'=>5, 
												'department'=>$test_all_data['dept_id'],
												'ip_address'=>$_SERVER['REMOTE_ADDR'],
												'created_date'=>date('Y-m-d H:i:s'),
												
									         );
								$this->db->insert('hms_department',$department_data); 
								$department_id = $this->db->insert_id();
		                } 
		                
		    	}

		    	//department end 
		    	//echo $department_id; die; 

		    	//test head
		    	if(!empty($department_id) && !empty($test_all_data['test_head_id']))
		    	{
		    		
						$this->db->select('path_test_heads.*');     
						$this->db->where('LOWER(path_test_heads.test_heads)',strtolower($test_all_data['test_head_id']));	  
						$this->db->where('path_test_heads.dept_id',$department_id);
						
						$p_thead_query = $this->db->get('path_test_heads');
						$p_thead_result = $p_thead_query->result_array();  
		                
		                if(!empty($p_thead_result))
		                {
							$test_head_id = $p_thead_result[0]['id'];
		                } 
						else
		                {
		                    	$test_heads_data = array( 
												'branch_id'=>$users_data['parent_id'],
												'dept_id'=>$department_id,
												'test_heads'=>$test_all_data['test_head_id'],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );
								$this->db->insert('path_test_heads',$test_heads_data); 
								$test_head_id = $this->db->insert_id();    
		                }
		              
		    	}
		    	//test head end


		    	//test unit //

		    	///////////////// Set Test Unit //////////
		    	if(!empty($test_all_data['unit_id']))
		    	{
					$this->db->select('path_unit.*');     
					//$this->db->where('path_unit.unit',$test_all_data['unit_id']);
					$this->db->where('LOWER(path_unit.unit)',strtolower($test_all_data['unit_id'])); 
					$this->db->where('path_unit.branch_id',$users_data['parent_id']);  
					$c_unit_query = $this->db->get('path_unit');
					$c_unit_result = $c_unit_query->result_array();
			        
			        if(!empty($c_unit_result))
			        {
			           $test_unit_id = $c_unit_result[0]['id'];
			        }
			        else
			        {
			        	$test_unit_data = array( 
										'branch_id'=>$users_data['parent_id'], 
										'unit'=>$test_all_data['unit_id'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
							         );
						$this->db->insert('path_unit',$test_unit_data); 
						$test_unit_id = $this->db->insert_id();    
			        }
			    }    
			
			   //test unit end
		       // test method 

			    ///////////////// Set Download Method //////////
					$test_method_id='';
					if(!empty($test_all_data['method_id']) )
					{
						$this->db->select('path_test_method.*');     
							//$this->db->where('path_test_method.test_method',$p_tmethod_result[0]['test_method']);
							$this->db->where('LOWER(path_test_method.test_method)',strtolower($test_all_data['method_id'])); 
							$this->db->where('path_test_method.branch_id',$users_data['parent_id']);  
							$c_tmethod_query = $this->db->get('path_test_method');
							$c_tmethod_result = $c_tmethod_query->result_array();
		                    /*echo $this->db->last_query(); 
		                    print_r($c_tmethod_result);
		                    exit;*/
		                    if(!empty($c_tmethod_result))
		                    {
		                       $test_method_id = $c_tmethod_result[0]['id'];
		                    }
		                    else
		                    {
		                    	$test_method_data = array( 
												'branch_id'=>$users_data['parent_id'], 
												'test_method'=>$test_all_data['method_id'],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );
								$this->db->insert('path_test_method',$test_method_data); 
								$test_method_id = $this->db->insert_id();    
		                    }	
					}
				    // test method end

					// sample type
					$sample_type_id='';
				    if(!empty($test_all_data['sample_test']))
				    {
				    		$this->db->select('path_sample_type.*');     
							//$this->db->where('path_sample_type.sample_type',$p_stype_result[0]['sample_type']); 
							$this->db->where('LOWER(path_sample_type.sample_type)',strtolower($test_all_data['sample_test']));
							$this->db->where('path_sample_type.branch_id',$users_data['parent_id']);  
							$c_stype_query = $this->db->get('path_sample_type');
							$c_stype_result = $c_stype_query->result_array();
		                    
		                    if(!empty($c_stype_result))
		                    {
		                       $sample_type_id = $c_stype_result[0]['id'];
		                    }
		                    else
		                    {
		                    	$test_sample_data = array( 
												'branch_id'=>$users_data['parent_id'], 
												'sample_type'=>$test_all_data['sample_test'],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );
								$this->db->insert('path_sample_type',$test_sample_data); 
								$sample_type_id = $this->db->insert_id();    
		                    }
				    }
						    
				//sample type end	
				 if(!empty($test_all_data['test_type_id']) && strtolower($test_all_data['test_type_id'])=='normal')
				 {
				 	$test_type_id =0;
				 }
				 elseif(!empty($test_all_data['test_type_id']) && strtolower($test_all_data['test_type_id'])=='heading')
				 {
				 	$test_type_id =1;
				 }


				 $test_code = generate_unique_id(25);
				 

				  $data_test = array(
									'branch_id'=>$users_data['parent_id'], 
									'test_code'=>$test_code,
									'dept_id'=>$department_id,
									'test_head_id'=>$test_head_id,
									'test_name'=>$test_all_data['test_name'], 
									'default_value'=>$test_all_data['default_value'],
									'rate'=>$test_all_data['rate'],
									'base_rate'=>$test_all_data['base_rate'],//$base_price,
									'method_id'=>$test_method_id,
									'unit_id'=>$test_unit_id,
									'range_from_pre'=>$test_all_data['range_from_pre'], 
									'range_from'=>$test_all_data['range_from'],
									'range_from_post'=>$test_all_data['range_from_post'], 
									'range_to_pre'=>$test_all_data['range_to_pre'], 
									'range_to'=>$test_all_data['range_to'],
									'range_to_post'=>$test_all_data['range_to_post'],
									'all_range_show'=>0,
									'test_type_id'=>$test_all_data['test_type_id'],
									'sample_test'=>$sample_type_id,
									'ip_address'=>$_SERVER['REMOTE_ADDR'],
									
									'created_date'=>date('Y-m-d H:i:s') 
		        	  	           ); 


					  $this->db->insert('path_test',$data_test); 
					  $test_id = $this->db->insert_id();

					 //echo $this->db->last_query(); 
		                   // print_r($c_tmethod_result);
		                   // exit;  


					 //interpretation start
					 if(!empty($test_all_data['interpretation']) && !empty($test_id))
					 {
					 	

					 	$interpretation_data = 
					 					array( 
											'title'=>'Title 1', 
											'interpration'=>$test_all_data['interpretation'],
											'test_id'=>$test_id,
											'range_condition'=>'0'
									        );
						$this->db->insert('path_multi_interpration',$interpretation_data); 
						$interpretation_id = $this->db->insert_id();

					 }

				}

			}
		  }	
    
    	 
	public function check_duplicate($str="",$dept_id="",$test_head_id="")
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('path_test.*');
		$this->db->from('path_test'); 
		if(!empty($str))
		{
	    $this->db->where('path_test.test_name',trim($str));
		$this->db->where('path_test.test_head_id',trim($test_head_id));
		$this->db->where('path_test.dept_id',trim($dept_id));
		} 
		$this->db->where('path_test.branch_id',$user_data['parent_id']); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	
	
	
// Please write code above
}
?>