<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch_model extends CI_Model {

	var $table = 'hms_branch';
	/*var $column = array('hms_branch.id','hms_branch.branch_name','hms_branch.city_id','hms_branch.contact_no','hms_branch.branch_code',  'hms_branch.status','hms_branch.start_date','hms_branch.end_date','hms_branch.start_date','hms_branch.end_date');*/
	var $column = array('hms_branch.id','hms_branch.branch_name','hms_branch.city_id','hms_branch.contact_no','hms_branch.branch_code', 'hms_branch.status', 'hms_branch.status','hms_branch.status','hms_branch.start_date','hms_branch.end_date'); 
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('branch_search');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_branch.*, hms_users.last_login_ip,hms_users.last_login_time,hms_cities.city, hms_state.state"); 
		$this->db->from($this->table); 
        $this->db->join('hms_cities','hms_cities.id=hms_branch.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_branch.state_id','left');
        $this->db->join('hms_users','hms_users.parent_id=hms_branch.id AND hms_users.emp_id=0 AND hms_users.users_role=2','left');

        $this->db->where('hms_branch.is_deleted','0');
       
        $this->db->where('hms_branch.parent_id',$users_data['parent_id']);
        
        if($search['status']=='2')
        {
            $this->db->where('hms_branch.branch_type=2');
        }
        if($search['status']=='1')
        {
            //$branch_details[0]->end_date
            $today_date = date('Y-m-d');
            $where_date = "(hms_branch.status=1) AND (hms_branch.end_date >=".$today_date.")";
            
             //$this->db->where('hms_branch.status',1);
              $this->db->where('hms_branch.end_date>=',$today_date);
              $this->db->where('hms_branch.branch_type!=2');
            //$this->db->where($where_date,'',false);
        } 

        if($search['status']=='0')
        {
            //$branch_details[0]->end_date
            $today_date = date('Y-m-d');
            //$where_date = "(hms_branch.status=0) AND (hms_branch.end_date <=".$today_date.")";
            $where_date = "(hms_branch.status=0) AND (hms_branch.end_date <".$today_date.")";
            //$this->db->where($where_date,'',false);
            //$this->db->where('hms_branch.status',0);
              $this->db->where('hms_branch.end_date<',$today_date);
              $this->db->where('hms_branch.branch_type!=2');
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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('hms_branch.*, hms_users.id as users_id, hms_users.username, hms_users.status as login_status, hms_users.email,  hms_countries.country, hms_state.state, hms_cities.city');
		$this->db->from('hms_branch');
		$this->db->join('hms_users','hms_users.parent_id=hms_branch.id');
        $this->db->where('hms_users.users_role',2);
		//$this->db->join('hms_rate_plan','hms_rate_plan.id=hms_branch.rate_id','left');
		$this->db->join('hms_countries','hms_countries.id=hms_branch.country_id','left');
		$this->db->join('hms_state','hms_state.id=hms_branch.state_id','left');
		$this->db->join('hms_cities','hms_cities.id=hms_branch.city_id','left');
		$this->db->where('hms_branch.id',$id);
		//$this->db->where('hms_branch.is_deleted','0');
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->row_array();
	}

    public function get_patient_branch($patient_id="")
    {
        $this->db->select('hms_patient.*');
        $this->db->from('hms_patient');
        if(!empty($patient_id))
        {
         $this->db->where('id',$patient_id);   
        }
        $res= $this->db->get()->result();
        return $res;
    }



    public function get_doctor_branch($doctor_id="")
    {
        $this->db->select('hms_doctors.*');
        $this->db->from('hms_doctors');
        if(!empty($patient_id))
        {
         $this->db->where('id',$doctor_id);   
        }
        $res= $this->db->get()->result();
        return $res;
    }

    
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
        $company_data = $this->session->userdata('company_data');
		$post = $this->input->post(); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $data = array( 
                            'branch_name'=>$post['branch_name'],
                            'contact_no'=>$post['contact_no'],
                            'contact_person'=>$post['contact_person'], 
                            'address'=>$post['address'],
                            'address2'=>$post['address_second'],
                            'address3'=>$post['address_third'],
                            'city_id'=>$post['city_id'],
                            'state_id'=>$post['state_id'],
                            'country_id'=>$post['country_id'],
                            'branch_type'=>$post['branch_type'],
                            //'rate_id'=>$post['rate_id'],
                            'status'=>$post['branch_status'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'modified_by'=>$user_data['id'],
                            'start_date'=>date('Y-m-d', strtotime($post['start_date'])),
                            'end_date'=>date('Y-m-d', strtotime($post['end_date'])),
                            'modified_date'=>date('Y-m-d H:i:s')
				         );
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_branch',$data);
			//echo $this->db->last_query(); exit; 
            $branch_id = $post['data_id'];
			$data = array(
                            'email'=>$post['email'], 
                            'status'=>$post['login_status'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'], 
                            'modified_date'=>date('Y-m-d H:i:s')
				         );
            if(!empty($post['password']))
            {
                $this->db->set('password',md5($post['password']));
            }
			$this->db->where('parent_id',$branch_id);
			$this->db->where('users_role',2);
			$this->db->where('emp_id',0);
            $this->db->update('hms_users',$data);
            //update sub branch end date as parent branch
            $this->db->select('*');
            $this->db->where('parent_id',$branch_id);
            $this->db->where('is_deleted',0);
            $query_sub = $this->db->get('hms_branch');
            $sub_branch_list = $query_sub->result();
            if(!empty($sub_branch_list))
            {
                foreach($sub_branch_list as $sub_branch)
                {   
                    $sub_data = array('end_date'=>date('Y-m-d', strtotime($post['end_date'])));
                    $this->db->where('id',$sub_branch->id);
                    $this->db->where('parent_id',$branch_id);
                    $this->db->update('hms_branch',$sub_data);
                   
                }
            }

		}
		else
        {   
			$branch_code = generate_unique_id(1);
			$data = array(
                            'parent_id'=> $user_data['parent_id'],
                            'branch_code'=> $branch_code,
                            'branch_name'=>$post['branch_name'],
                            'contact_no'=>$post['contact_no'], 
                            'contact_person'=>$post['contact_person'],
                            'address'=>$post['address'],
                            'address2'=>$post['address_second'],
                            'address3'=>$post['address_third'],
                            'city_id'=>$post['city_id'],
                            'state_id'=>$post['state_id'],
                            'country_id'=>$post['country_id'],
                            'branch_type'=>$post['branch_type'],
                            //'rate_id'=>$post['rate_id'],
                            'status'=>$post['branch_status'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'created_by'=>$user_data['id'],
                            'start_date'=>date('Y-m-d', strtotime($post['start_date'])),
                            'end_date'=>date('Y-m-d', strtotime($post['end_date'])),
                            'created_date'=>date('Y-m-d H:i:s')
				         );
			$this->db->insert('hms_branch',$data);
            $branch_id = $this->db->insert_id();
            //echo $this->db->last_query(); exit;
            
            if($post['branch_type']==2)
            {

            }
            else
            {
            //get all inheritted data for new created  child branch
            $this->load->model('inherit_data/Inherit_data_model','inherit_data_model');
            $this->inherit_data_model->inherit_data($branch_id);
            
            $this->load->model('eye/inherit_data/Eye_inherit_data_model','eye_inherit_data_model');
            $this->eye_inherit_data_model->eye_inherit_data($branch_id);
            

             $this->load->model('dental/inherit_data/Dental_inherit_data_model','dental_inherit_data_model');
             $this->dental_inherit_data_model->dental_inherit_data($branch_id);


            $this->load->model('pediatrician/inherit_data/Pediatrician_inherit_data_model','pediatrician_inherit_data_model');
            $this->pediatrician_inherit_data_model->pediatrician_inherit_data($branch_id);

            $this->load->model('gynecology/inherit_data/Gynecology_inherit_data_model','gynecology_inherit');
            $this->gynecology_inherit->gynecology_inherit_data($branch_id);
            }
            ////////// End Email template ////////////
			$data = array(
                            'users_role'=>'2',
                            'users_type'=>'1',
                            'parent_id'=>$branch_id,
                            'email'=>$post['email'],
                            'username'=>$post['username'],
                            'password'=>md5($post['password']),
                            'status'=>$post['login_status'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'], 
                            'created_date'=>date('Y-m-d H:i:s')
				         );
            $this->db->insert('hms_users',$data);
            $users_id = $this->db->insert_id();

            $this->db->set('users_id',$users_id);
            $this->db->where('id',$branch_id);
            $this->db->update('hms_branch');
		    ////// Set Permission //////////////
            
            // Get Role permission
            if($user_data['users_role']==1)
            {
                $this->db->select('*');
                $this->db->where('users_role',2);
                $query = $this->db->get('hms_permission_to_role');
            }
            else
            {
                $this->db->select('*');
                $this->db->where('users_id',$user_data['id']);
                $query = $this->db->get('hms_permission_to_users');
            }
            
            $permission_result_list = $query->result();
            if(!empty($permission_result_list))
            {
            	foreach($permission_result_list as $permission_result)
            	{
                    if($user_data['users_role']==2 && $permission_result->section_id==1)
                    {

                    }
                    else
                    { 
            		$data = array(
                                    'users_role' =>2,
                                    'users_id' => $users_id,
                                    'master_id' => $user_data['parent_id'],
                                    'section_id' => $permission_result->section_id,
                                    'action_id' => $permission_result->action_id,
                                    'attribute_val' => $permission_result->attribute_val,
                                    'permission_status' => '1',
                                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                                    'created_by' =>$user_data['id'],
                                    'created_date' =>date('Y-m-d H:i:s'),
            			         );
            		$this->db->insert('hms_permission_to_users',$data);
                   }
            	}
            }
		    ///////////////////////////////////
		} 	
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
			$this->db->update('hms_branch');
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
			$this->db->update('hms_branch');
			//echo $this->db->last_query();die;
    	} 
    }

    public function permission_section_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_permission_section.*');
    	
    	if($user_data['users_role']==1)
    	{
            $this->db->join('hms_permission_section','hms_permission_section.id = hms_permission_to_role.section_id');
			$this->db->where('hms_permission_section.status','1');
			$this->db->group_by('hms_permission_section.id');
            $this->db->order_by('hms_permission_section.sort_order','asc');
			$this->db->from('hms_permission_to_role'); 
    	}
    	else if($user_data['users_role']==2)
    	{
    		$this->db->join('hms_permission_section','hms_permission_section.id = hms_permission_to_users.section_id');
    		if($user_data['parent_id']>0)
	    	{
	           $this->db->where('hms_permission_to_users.users_id',$user_data['id']);
	    	}
	    	else
	    	{
	           $this->db->where('hms_permission_to_users.master_id',$user_data['parent_id']);
	    	}
			$this->db->where('hms_permission_section.status','1');
			$this->db->group_by('hms_permission_section.id');
			$this->db->from('hms_permission_to_users'); 
             $this->db->order_by('hms_permission_section.sort_order','asc');
    	}
    	  
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
    	$result = $query->result();
    	return $result; 
    }
    

    public function branch_permission_section_list($branch_id="")
    {
         $this->db->select('hms_permission_to_users.section_id');
         $this->db->join('hms_users','hms_users.id = hms_permission_to_users.users_id'); 
         if(!empty($branch_id))
         {
            $this->db->join('hms_branch','hms_branch.id = hms_users.parent_id');
            $this->db->where('hms_users.parent_id',$branch_id); 
            $this->db->where('hms_users.users_role','2'); 
         }
         $this->db->from('hms_permission_to_users'); 
         $this->db->group_by('hms_permission_to_users.section_id');
         $query = $this->db->get(); 
         //echo $this->db->last_query();die;
         return $query->result_array();
    }

    public function branch_permission_action_list($section_id="",$branch_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	 

    	if($user_data['users_role']==1)
    	{
           $this->db->select('hms_permission_action.*,(select permission_status from hms_permission_to_users as user_perm where user_perm.users_id = '.$branch_id.' AND user_perm.section_id=hms_permission_to_users.section_id AND user_perm.action_id = hms_permission_to_users.action_id) as user_permission_status, (select attribute_val from hms_permission_to_users as user_perm where user_perm.users_id = '.$branch_id.' AND user_perm.section_id=hms_permission_to_users.section_id AND user_perm.action_id = hms_permission_to_users.action_id) as attribute_val');

           $this->db->join('hms_permission_action','hms_permission_action.id = hms_permission_to_role.action_id');

           $this->db->join('hms_permission_to_users','hms_permission_to_users.action_id = hms_permission_to_role.action_id AND hms_permission_to_users.users_role = "2" AND hms_permission_to_users.users_id = "'.$branch_id.'"','left');

           if(!empty($section_id))
	    	{
	    	   $this->db->where('hms_permission_action.section_id',$section_id); 
	    	} 
 
	    	$this->db->where('hms_permission_action.status','1'); 
	    	$this->db->group_by('hms_permission_action.id');
	    	$this->db->from('hms_permission_to_role'); 
    	}
    	else if($user_data['users_role']==2)
    	{
    		$this->db->select('hms_permission_action.*,(select permission_status from hms_permission_to_users as user_perm where user_perm.users_id = '.$branch_id.' AND user_perm.section_id=hms_permission_to_users.section_id AND user_perm.action_id = hms_permission_to_users.action_id) as user_permission_status, (select attribute_val from hms_permission_to_users as user_perm where user_perm.users_id = '.$branch_id.' AND user_perm.section_id=hms_permission_to_users.section_id AND user_perm.action_id = hms_permission_to_users.action_id) as attribute_val');

           $this->db->join('hms_permission_action','hms_permission_action.id = hms_permission_to_users.action_id'); 

           if($user_data['parent_id']>0)
	    	{
	           $this->db->where('hms_permission_to_users.users_id',$user_data['id']);
	    	}
	    	else
	    	{
	           $this->db->where('hms_permission_to_users.master_id',$user_data['parent_id']);
	    	}

           if(!empty($section_id))
	    	{
	    	   $this->db->where('hms_permission_action.section_id',$section_id); 
	    	} 
 
	    	$this->db->where('hms_permission_action.status','1'); 
	    	$this->db->group_by('hms_permission_action.id');
	    	$this->db->from('hms_permission_to_users');  
    	}

    	
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
    	$result = $query->result();
    	return $result; 
    }

    public function get_branch_permission_status($users_id="",$section_id="")
    {
       
        $this->db->select('*');
        $this->db->where('hms_permission_to_users.section_id',$section_id);
        $this->db->where('hms_permission_to_users.users_id',$users_id);
        $this->db->from('hms_permission_to_users');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $result = $query->result();
        return $result;  
        
    }

    public function save_branch_permission($branch_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	$post = $this->input->post();
    	if(!empty($post))
    	{
    		$this->db->where('users_id',$post['bid']);
    		$this->db->delete('hms_permission_to_users');

    		foreach($post['active'] as $active)
    		{
    			$explode = explode('-', $active);
    			$attr_val = "";
    			if(isset($post['attribute_val-'.$explode[1]]))
    			{
    				$attr_val = $post['attribute_val-'.$explode[1]];
    			}

    			if(isset($explode[2]) && !empty($explode[2]) && $explode[2]==1)
    			{
					$data = array(
					   'users_role'=>2,
					   'users_id'=>$post['bid'],
					   'master_id'=>$user_data['parent_id'],
					   'section_id'=>$explode[0],
					   'action_id'=>$explode[1],
					   'attribute_val'=>$attr_val,
					   'permission_status'=>1, 
					   'created_by'=>$user_data['id'],
					   'created_date'=>date('Y-m-d H:i:s')
					 );
					$this->db->insert('hms_permission_to_users',$data); 
    			} 
    		}
    	}
    }
    //get the branch_type and its start date and from date according to their Id
    public function get_branch_details($id=''){
        $this->db->select('branch_type,start_date,end_date');
        $this->db->where('id',$id);
        $this->db->from('hms_branch');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $result = $query->result();
        return $result; 

    }

     public function get_mail_temp_data(){
        $this->db->select('*');
        $this->db->where('form_name','28');
        $this->db->from('hms_email_branch_template');
        $query = $this->db->get();
       // echo $this->db->last_query();die;
        $result = $query->row();
        return $result; 

    }

    // function getOneLevel($parent_id)
    // {
    //     $this->db->select('id'); 
    //     $this->db->where('parent_id',$parent_id);                   
    //     $query = $this->db->get('hms_branch');
    //     // print_r($query->result_array());
    //     // die;
    //     $total_record = $query->num_rows(); 
    //     $child_id=array();
    //     if($total_record>0)
    //         {
    //             $test_list = $query->result(); 
    //             foreach($test_list as $test)
    //             {
    //                $child_id[] = $test->id;
    //             } 
    //         }
    //     return $child_id; 
    // }

    function getChildren($parent_id) 
    {
        $user_data = $this->session->userdata('auth_users');

       
        $this->db->select('id');
        $this->db->where('parent_id',$parent_id);
        $this->db->where('id!='.$parent_id);
        $query = $this->db->get('hms_branch');
        $tree = $query->result_array();
        // $i=0;
        // if (!empty($parent_id)) {
        //     if($i<=2){
        //         $tree = $this->getOneLevel($parent_id);
        //         foreach ($tree as $key => $val) {
        //             $ids = $this->getChildren($val);
        //             $tree = array_merge($tree, $ids);
        //             $i++;
        //         }
        //     }
        // }
        return $tree;
    }
    //  function getOneLevelParent($parent_id)
    // {
    //     $this->db->select('parent_id'); 
    //     $this->db->where('id',$parent_id);                   
    //     $query = $this->db->get('hms_branch');
    //     // print_r($query->result_array());
    //     // die;
    //     $total_record = $query->num_rows(); 
    //     $child_id=array();
    //     if($total_record>0)
    //         {
    //             $test_list = $query->result(); 
    //             foreach($test_list as $test)
    //             {
    //                $child_id[] = $test->parent_id;
    //             } 
    //         }
    //     return $child_id; 
    // }

    function getParent($parent_id) 
    {
        $user_data = $this->session->userdata('auth_users');

       
        $this->db->select('parent_id');
        $this->db->where('id',$parent_id);
        $query = $this->db->get('hms_branch');
        $tree = $query->result_array();
        // $tree = Array();
        // $i=0;
        // if (!empty($parent_id)) {
        //     if($i<=2){
        //         $tree = $this->getOneLevelParent($parent_id);
        //         foreach ($tree as $key => $val) {
        //             $ids = $this->getParent($val);
        //             $tree = array_merge($tree, $ids);
        //             $i++;
        //         }
        //     }
        // }
        return $tree;
    }

    function get_branch_name($branch_id)
    {
        $branch_name = "";
        if(!empty($branch_id))
        { 

            $id_list = [];
            foreach($branch_id as $id)
            {
                if(!empty($id) && $id>0)
                {
                  $id_list[]  = $id;
                } 
            }

            $branch_ids = implode(',', $id_list);
            $this->db->select('branch_name'); 
            $this->db->where('id',$branch_ids);                   
            $query = $this->db->get('hms_branch');
            $test_list = $query->result(); 
            foreach($test_list as $test)
            {
               $branch_name = $test->branch_name;
            } 
        }
        return $branch_name; 
    }
    function get_details($branch_id)
    {
       $this->db->select('hms_branch.*,hms_users.username,hms_users.password');
       $this->db->join('hms_users','hms_users.id=hms_branch.users_id');
       $this->db->where('hms_branch.id',$branch_id);
       $result= $this->db->get('hms_branch')->result();
       return $result;
    }

    public function branch_data($user_name,$password){

      $this->db->select('hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name');
        $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->where('hms_users.username',$user_name);
        $this->db->where('hms_users.password',$password);
        $this->db->where('hms_users.status','1'); 
        $query = $this->db->get('hms_users');
        $result = $query->row_array(); 
        //echo $this->db->last_query();die;

        // Permission gethering /
        $this->db->select('hms_permission_to_users.section_id, hms_permission_to_users.action_id');
        $this->db->where('users_id',$result['id']);   
        $query = $this->db->get('hms_permission_to_users');
        $permission_list = $query->result(); 

        $permission = [];
        if(!empty($permission_list))
        {
          $section = [];
          $action = [];
          foreach($permission_list as $permission)
          {
             if(!in_array($permission->section_id,$section))
             {
                $section[] = $permission->section_id;
             }

             $action[] = $permission->action_id;
          }
          $permission = array('section'=>$section, 'action'=>$action);

          if(!empty($result))
          {
            $result['permission'] = $permission;
          }
        }
        ////////////////////////

        return $result;
      }

       public function get_data_new_feature()
        {
            $users_data = $this->session->userdata('auth_users');
            $permission_section = $users_data['permission']['section'];
            $current_date = date('Y-m-d');
            $module_ids = '9';
            $this->db->select('*');
            $this->db->from('hms_admin_add_features');  
            $this->db->where('status',1); 
            $this->db->where('is_deleted',0);
            if(in_array('85', $permission_section))
            {
                $module_ids .= ',1';
            } 
            if(in_array('121', $permission_section))
            {
                $module_ids .= ',2';
            } 
            if(in_array('145', $permission_section))
            {
                $module_ids .= ',3';
            } 
            if(in_array('60', $permission_section))
            {
                $module_ids .= ',4';
            } 
            if(in_array('60', $permission_section))
            {
                $module_ids .= ',5';
            } 
            if(in_array('260', $permission_section))
            {
                $module_ids .= ',6';
            } 
            if(in_array('164', $permission_section))
            {
                $module_ids .= ',7';
            } 
            if(in_array('167', $permission_section))
            {
                $module_ids .= ',8';
            } 
            $this->db->where('section IN('.$module_ids.')'); 
            $this->db->WHERE ('"'.$current_date.'" >= start_date');
            $this->db->WHERE ('end_date >= "'.$current_date.'"');

            $query = $this->db->get();
           //echo $this->db->last_query();die;  
            return $query->result();
            
            
            //die;
            //return $res;
        }


        public function get_users_list($parent_id="")
    {
            $this->db->select("hms_users.*, hms_employees.reg_no, hms_employees.name, hms_employees.email, hms_emp_type.emp_type");
            $this->db->where('hms_users.emp_id > 0');   
            $this->db->join('hms_employees','hms_employees.id = hms_users.emp_id');
            $this->db->join('hms_emp_type','hms_emp_type.id = hms_employees.emp_type_id','left');
            $this->db->where('hms_employees.is_deleted',0);
            $this->db->where('hms_users.is_deleted',0);        
            if(!empty($parent_id))
        {
            $this->db->where('hms_users.parent_id',$parent_id);

        }
          $this->db->from('hms_users'); 
          $res= $this->db->get()->result();
          return $res;
    }
    
    public function get_branch_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_branch');
        $this->db->where('hms_branch.parent_id','0');
        $this->db->where('hms_branch.status','1');
        $this->db->where('hms_branch.id!=1');
        $this->db->where('hms_branch.is_deleted','0');
        $this->db->where('hms_branch.branch_type','1');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $result = $query->result();
        return $result;   
    }

     public function get_renewal_mail_template()
     {
        $this->db->select('hms_renewal_mail.*');
        $this->db->where('hms_renewal_mail.branch_id','0');
        $this->db->from('hms_renewal_mail');
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $result = $query->result();
        return $result; 

    }
    
    public function get_users_login_list($parent_id="")
    {
            
        $this->db->select('hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_employees.name,hms_employees.email, hms_emp_type.emp_type,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.reg_no ELSE hms_branch.branch_code END) as reg_no');
        $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->join('hms_emp_type','hms_emp_type.id = hms_employees.emp_type_id','left');
        if(!empty($parent_id))
            {
                $this->db->where('hms_users.parent_id',$parent_id);

            }
        $this->db->where('hms_users.status','1'); 
        $query = $this->db->get('hms_users');
        $res= $query->result();
        return $res;

    }
    
    
     public function get_employee_data_birthday()
    {   
        $users_data = $this->session->userdata('auth_users');
        $date_get=date('Y-m-1');
        $to_date_get=date('Y-m-31');
        $date_get_val = explode('-',$date_get);  
        $to_date_get_val = explode('-',$to_date_get); 
        $this->db->select('prl_employees.*');
        $this->db->from('prl_employees');
       if(isset($date_get_val) && !empty($date_get_val))
        {

            
                $this->db->where('MONTH(dob) >= "'.$date_get_val[1].'"');
            
        }
        if(isset($to_date_get_val) && !empty($to_date_get_val))
        {
            
                $this->db->where('MONTH(dob) <= "'.$to_date_get_val[1].'"');
            
        } 
        //$this->db->order_by(asc);
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $res= $this->db->get()->result();
       // echo $this->db->last_query();
        //die;
        return $res;
    }
    
    public function get_payroll_by_id($id)
	{
		$this->db->select('prl_branch.*, prl_users.id as users_id, prl_users.username, prl_users.status as login_status, prl_users.email,  prl_countries.country, prl_state.state, prl_cities.city');
		$this->db->from('prl_branch');
		$this->db->join('prl_users','prl_users.parent_id=prl_branch.id');
        $this->db->where('prl_users.users_role',2);
		//$this->db->join('prl_rate_plan','prl_rate_plan.id=prl_branch.rate_id','left');
		$this->db->join('prl_countries','prl_countries.id=prl_branch.country_id','left');

		$this->db->join('prl_state','prl_state.id=prl_branch.state_id','left');
		$this->db->join('prl_cities','prl_cities.id=prl_branch.city_id','left');
		$this->db->where('prl_branch.id',$id);
		//$this->db->where('prl_branch.is_deleted','0');
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->row_array();
	}


     
}
?>