<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_salary_model extends CI_Model {

	var $table = 'hms_expenses';
	var $column = array('hms_expenses.id','hms_expenses.expenses_date', 'hms_expenses.paid_amount','hms_expenses.payment_mode','hms_expenses.created_date','hms_expenses.modified_date');  
	var $order = array('hms_expenses.id' => 'desc'); 


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $employe_search_detail= $this->session->userdata('employe_search_detail');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $this->db->select("hms_expenses.*,hms_payment_mode.payment_mode,hms_employees.name as emp_names"); 
        $this->db->from($this->table); 
       
        $this->db->where('(hms_expenses.is_deleted="0" AND hms_expenses.type="1")');
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode');
        
        $this->db->join('hms_employees','hms_employees.id=hms_expenses.employee_id');
        
       if(isset($employe_search_detail['branch_id']) && $employe_search_detail['branch_id']!=''){
        $this->db->where('hms_expenses.branch_id IN ('.$employe_search_detail['branch_id'].')');
        }else{
        $this->db->where('hms_expenses.branch_id = "'.$users_data['parent_id'].'"');
        }
		
		$emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($employe_search_detail["employee"]) && is_numeric($employe_search_detail['employee']))
        {
            $emp_ids=  $employe_search_detail["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_expenses.created_by IN ('.$emp_ids.')');
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
		$users_data = $this->session->userdata('auth_users');
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_expenses.*,hms_employees.name,hms_employees.contact_no,hms_emp_type.emp_type,hms_employees.email as employee_email,hms_payment_mode.payment_mode"); 
        $this->db->from($this->table); 
        $this->db->join("hms_employees","hms_expenses.employee_id=hms_employees.id",'left');
        $this->db->join("hms_emp_type","hms_expenses.emp_type_id=hms_emp_type.id",'left');
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
        $this->db->where('hms_expenses.id',$id);
        $this->db->where('hms_expenses.is_deleted','0');
        $this->db->where('(hms_expenses.branch_id = "'.$users_data['parent_id'].'" OR hms_expenses.branch_id = "0")');
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->row_array();
	}
	
	function search_data()
    {
    	$users_data = $this->session->userdata('auth_users');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $employe_search_detail= $this->session->userdata('employe_search_detail');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $this->db->select("hms_expenses.*,hms_payment_mode.payment_mode,hms_employees.name as emp_names"); 
        $this->db->from($this->table); 
       
        $this->db->where('(hms_expenses.is_deleted="0" AND hms_expenses.type="1")');
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode');
        
        $this->db->join('hms_employees','hms_employees.id=hms_expenses.employee_id');
        
       if(isset($employe_search_detail['branch_id']) && $employe_search_detail['branch_id']!=''){
        $this->db->where('hms_expenses.branch_id IN ('.$employe_search_detail['branch_id'].')');
        }else{
        $this->db->where('hms_expenses.branch_id = "'.$users_data['parent_id'].'"');
        }
		
		$emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($employe_search_detail["employee"]) && is_numeric($employe_search_detail['employee']))
        {
            $emp_ids=  $employe_search_detail["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_expenses.created_by IN ('.$emp_ids.')');
        }
	    $query = $this->db->get(); 

		$data= $query->result();
		//echo $this->db->last_query();
		return $data;
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $data = array( 
                        'vouchar_no'=>$post['voucher_no'],
                        'expenses_date'=>date('Y-m-d', strtotime($post['expenses_date'])), 
                        'emp_type_id'=>$post['emp_type_id'],
                        'paid_amount'=>$post['paid_amount'], 
                        'payment_mode'=>$post['payment_mode'], 
                        'remarks'=>$post['remark'], 
                        'employee_id'=>$post['employee_id'],
                       // 'transaction_no'=>$post['transaction_no'], 
                        //'branch_name'=>$post['branch_name'], 
                       // 'cheque_no'=>$post['cheque_no'],
                        //'cheque_date'=>date('Y-m-d', strtotime($post['cheque_date'])),
                        'employee_patient_balance'=>$post['patient_balance'],
                        'employee_advance'=>$post['advance_paid'],
                        'employee_balance'=>$post['balance'],
                        'branch_id' =>$user_data['parent_id'],
                        'employee_pay_now'=>$post['pay_now'],
                        'type'=>'1',
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'modified_by'=>$user_data['id'],
                        'modified_date'=>date('Y-m-d H:i:s')
	        );

            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_expenses',$data);
             /*add sales banlk detail*/
                $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>6,'section_id'=>5));
                $this->db->delete('hms_payment_mode_field_value_acc_section');
                if(!empty($post['field_name']))
                {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                {
                $data_field_value= array(
                'field_value'=>$post['field_name'][$i],
                'field_id'=>$post['field_id'][$i],
                'type'=>6,
                'section_id'=>5,
                'p_mode_id'=>$post['payment_mode'],
                'branch_id'=>$user_data['parent_id'],
                'parent_id'=>$post['data_id'],
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

            /*add sales banlk detail*/  
          	$id = $post['data_id'];


		}
		else{   
			// $branch_code = generate_unique_id(1);
			$data = array( 
                'vouchar_no'=>$post['voucher_no'],
                'expenses_date'=>date('Y-m-d', strtotime($post['expenses_date'])), 
                 'emp_type_id'=>$post['emp_type_id'],
                'paid_amount'=>$post['paid_amount'], 
                'payment_mode'=>$post['payment_mode'], 
                'remarks'=>$post['remark'], 
               // 'transaction_no'=>$post['transaction_no'], 
               // 'branch_name'=>$post['branch_name'], 
                //'cheque_no'=>$post['cheque_no'],
                'employee_id'=>$post['employee_id'],
                //'cheque_date'=>date('Y-m-d', strtotime($post['cheque_date'])),
                'employee_patient_balance'=>$post['patient_balance'],
				'employee_advance'=>$post['advance_paid'],
				'employee_balance'=>$post['balance'],
				  'branch_id' =>$user_data['parent_id'],
				'employee_pay_now'=>$post['pay_now'],
				'type'=>'1',
                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                'modified_by'=>$user_data['id'],
                'modified_date'=>date('Y-m-d H:i:s'),
                'created_date'=>date('Y-m-d H:i:s')
            );
                $this->db->insert('hms_expenses',$data);
                $id = $this->db->insert_id(); 

               /*add sales banlk detail*/
             
                if(!empty($post['field_name']))
                {
                        $post_field_value_name= $post['field_name'];
                        $counter_name= count($post_field_value_name); 
                        for($i=0;$i<$counter_name;$i++) 
                        {
                        $data_field_value= array(
                        'field_value'=>$post['field_name'][$i],
                        'field_id'=>$post['field_id'][$i],
                        'type'=>6,
                        'section_id'=>5,
                        'p_mode_id'=>$post['payment_mode'],
                        'branch_id'=>$user_data['parent_id'],
                        'parent_id'=>$id,
                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                        );
                        $this->db->set('created_by',$user_data['id']);
                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                        $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                        }
                }

            /*add sales banlk detail*/  
         } 	
          return $id;
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
			$this->db->update('hms_expenses');
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
			$this->db->update('hms_expenses');
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
    	}
    	  
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
    	$result = $query->result();
    	return $result; 
    }


    public function branch_permission_action_list($section_id="",$branch_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	 

    	if($user_data['users_role']==1)
    	{
           $this->db->select('hms_permission_action.*,hms_permission_to_users.permission_status as user_permission_status, hms_permission_to_users.attribute_val');

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
    		$this->db->select('hms_permission_action.*,hms_permission_to_users.permission_status as user_permission_status, hms_permission_to_users.attribute_val');

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
        $this->db->from('hms_expenses');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $result = $query->result();
        return $result; 

    }

    function sms_template_format($data="")
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_sms_branch_template.*');
    	$this->db->where($data);
    	$this->db->where('hms_sms_branch_template.branch_id = "'.$users_data['parent_id'].'"'); 
    	$this->db->from('hms_sms_branch_template');
    	$query=$this->db->get()->row();
    	//echo $this->db->last_query();
    	return $query;

    }

    function sms_url()
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_branch_sms_config.*');
    	//$this->db->where($data);
    	$this->db->where('hms_branch_sms_config.branch_id = "'.$users_data['parent_id'].'"'); 
    	$this->db->from('hms_branch_sms_config');
    	$query=$this->db->get()->row();
    	//echo $this->db->last_query();
    	return $query;

    }

    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

        $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
        $this->db->where('hms_payment_mode_field_value_acc_section.type',6);
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.section_id',5);
        $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();

        return $query;
    }

}
?>