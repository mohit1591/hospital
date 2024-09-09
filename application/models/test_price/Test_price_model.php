<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_price_model extends CI_Model {

	var $table = 'path_relation';
	var $column = array('path_relation.id','path_relation.relation', 'path_relation.status','path_relation.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	
    public function get_transactional_doctors_list(){
    	$users_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_doctors.doctor_name,hms_doctors.id');
    	$this->db->where('doctor_pay_type','2');
    	$this->db->where('branch_id',$users_data['parent_id']);
    	$query = $this->db->get('hms_doctors');
    	$result = $query->result();
    	return $result;
    }
    public function get_test_departments($module_id="",$branch_id='')
    {
    	/* 
        $this->db->select('hms_department.*');
    	$this->db->where('hms_department.module','5');
    	$query = $this->db->get('hms_department');
    	$result = $query->result();
    	return $result; 
        */
        $users_data = $this->session->userdata('auth_users');
        //$this->db->select('*');
        $this->db->select("hms_department.id,hms_department.branch_id,hms_department.module,hms_department.department,hms_department.ip_address,hms_department.is_deleted,hms_department.deleted_by,hms_department.deleted_date,hms_department.created_by,hms_department.modified_by,hms_department.modified_date,hms_department.created_date, (CASE WHEN hms_department.branch_id=0 THEN 1 ELSE hms_department_to_department_status.status END) status");
        
        $this->db->where('hms_department.is_deleted',0);
        $this->db->from('hms_department'); 
         $this->db->join('hms_department_to_department_status','hms_department_to_department_status.department_id = hms_department.id AND hms_department_to_department_status.branch_id="'.$users_data['parent_id'].'"','left');

        if(!empty($module_id))
        {
           $this->db->where('hms_department.module',$module_id); 
        }
        $this->db->where('(hms_department.branch_id='.$users_data['parent_id'].' OR hms_department.branch_id=0)'); 
        $this->db->where('hms_department_to_department_status.status','1');   
        $query = $this->db->get();
        $result = $query->result(); 
        return $result;

    }
    public function get_test_heads($department_id=''){
        $users_data = $this->session->userdata('auth_users');
    	if(!empty($department_id)){
    	    $this->db->select('path_test_heads.*');
    	    $this->db->where('dept_id',$department_id);
            $this->db->where('branch_id',$users_data['parent_id']);
    	    $query = $this->db->get('path_test_heads');
    	    $result = $query->result();
    	    return $result;
    	}
    }
    public function get_test_list()
    {
    	$post = $this->input->post();

        $users_data = $this->session->userdata('auth_users');
        if(!empty($post['doctors_id']))
        {
           
           
    	   $this->db->select('path_test.branch_id,path_test.rate,path_test.base_rate,path_test.id,path_test.test_name,path_test.test_head_id,path_test.dept_id,hms_department.department,path_test_to_doctors.rate as test_rate,path_test_to_doctors.base_rate as test_base_rate,path_test_to_doctors.test_id,path_test_to_doctors.doc_id');
        }
        else if(!empty($post['branch_id']))
        {
           
           
            $this->db->select('path_test.branch_id,path_test.rate,path_test.base_rate,path_test.id,path_test.test_name,path_test.test_head_id,path_test.dept_id,hms_department.department,path_test_to_doctors.rate as test_rate,path_test_to_doctors.base_rate as test_base_rate,path_test_to_doctors.test_id,path_test_to_doctors.doc_id');
        }
        else
        {
           
            $this->db->select('path_test.branch_id,path_test.rate,path_test.base_rate,path_test.id,path_test.test_name,path_test.test_head_id,path_test.dept_id,hms_department.department,path_test_to_doctors.rate as test_rate,path_test_to_doctors.base_rate as test_base_rate,path_test_to_doctors.test_id,path_test_to_doctors.doc_id');
        }
    	$this->db->from('path_test');
    	$this->db->join("hms_department","path_test.dept_id=hms_department.id","left");
        if(!empty($post['doctors_id']))
        {
          
            $this->db->join("path_test_to_doctors","path_test.id=path_test_to_doctors.test_id and path_test_to_doctors.doc_id=".$post['doctors_id'],"left");
        }
        else if(!empty($post['branch_id']))
        {
           
            $this->db->join("path_test_to_doctors","path_test.id=path_test_to_doctors.test_id and path_test_to_doctors.branch_id=".$post['branch_id']."  and path_test_to_doctors.doc_id=0" ,"left");
        }
        else
        {
           
            $this->db->join("path_test_to_doctors","path_test.id=path_test_to_doctors.test_id and path_test_to_doctors.branch_id=".$users_data['parent_id'],"left");
        }
     
    	if(!empty($post['department_id']))
    	{
            $this->db->where('path_test.dept_id',$post['department_id']); 
    	}

        if(!empty($post['test_head_id']))
        { 
            
            $this->db->where('path_test.test_head_id',$post['test_head_id']);
        }

    	if(!empty($post['branch_id']) && $post['branch_id']!=$users_data['parent_id'])
    	{
            
    		$this->db->where('path_test.branch_id',$post['branch_id']);
            
    	}
    	else
    	{
           
    		$this->db->where('path_test.branch_id',$users_data['parent_id']);
    	}
 
        $this->db->group_by('path_test.id');
    	$query = $this->db->get();
        //echo $this->db->last_query();die;
    	$result = $query->result_array();
    	//print_r($result);die;
    	return $result;


    }
    public function save_test_rate()
    {
        $data = array();
        $post = $this->input->post();
        $users_data = $this->session->userdata('auth_users');
        if(isset($post) && !empty($post))
        {
            
            if(!empty($post['doc_id']) && !empty($post['test_id']))
            {
              
                $result = $this->get_test_to_doctors();
                if(!empty($result))
                {
                    $data = array(
                        'rate'=>$post['rate'],
                        'base_rate'=>$post['base_rate'],
                    );

                    $this->db->where('branch_id',$users_data['parent_id']);
                    $this->db->where('(doc_id='.$result[0]['doc_id'].' and test_id='.$result[0]['test_id'].')');
                    $this->db->update('path_test_to_doctors',$data);
                }
                else
                {
                    $data = array(
                        'branch_id'=>$users_data['parent_id'],
                        'test_id'=>$post['test_id'],
                        'doc_id'=>$post['doc_id'],
                        'rate'=>$post['rate'],
                        'base_rate'=>$post['base_rate']
                    );
                    $this->db->insert('path_test_to_doctors',$data);
                }
            }
            else
            {

                $result = $this->get_test_to_doctors();
                if(!empty($result))
                {
                    $data = array(
                        'rate'=>$post['rate'],
                        'base_rate'=>$post['base_rate'],
                    );
                 
                    $this->db->where('branch_id',$result[0]['branch_id']);
                    

                    $this->db->where('test_id',$result[0]['test_id']);
                    $this->db->update('path_test_to_doctors',$data);
                }
                else
                {
                    $data = array(
                        'rate'=>$post['rate'],
                        'base_rate'=>$post['base_rate']
                    );
                    if(!empty($post['branch_id']))
                    {
                        $data['branch_id']=$post['branch_id'];
                    }
                    else
                    {
                        $data['branch_id'] = $users_data['parent_id'];
                    }
                    if(!empty($post['test_id']))
                    {
                        $data['test_id']=$post['test_id'];
                    }

                    $this->db->insert('path_test_to_doctors',$data);
                }

                $data = array(
                    'rate'=>$post['rate'],
                    'base_rate'=>$post['base_rate'],
                ); 
                $this->db->where('branch_id',$post['branch_id']); 
                $this->db->where('id',$result[0]['test_id']);
                $this->db->update('path_test',$data);
               
               
            }
        }
    }


    public function save_test_all_rate()
    {
        $data = array();
        $post = $this->input->post();
        $users_data = $this->session->userdata('auth_users');
        if(isset($post) && !empty($post['test_id']))
        {
            if(isset($post['sub_branch_id']) && !empty($post['sub_branch_id']))
            {
               $branch_id = $post['sub_branch_id'];
            }
            else
            {
                $branch_id = $users_data['parent_id'];
            }

            if(isset($post['doctors_id']) && !empty($post['doctors_id']))
            {
               $doctors_id = $post['doctors_id'];
            }
            else
            {
                $doctors_id = 0;
            }
            foreach($post['test_id'] as $test_data)
            {  
                if(isset($test_data['id']) && $test_data['id']>0)
                {
                    $this->db->where('branch_id',$branch_id);
                    $this->db->where('doc_id',$doctors_id);
                    $this->db->where('test_id',$test_data['id']);
                    $this->db->delete('path_test_to_doctors');
                    
                    $data = array(
                                   'branch_id'=>$branch_id,
                                   'test_id'=>$test_data['id'],
                                   'doc_id'=>$doctors_id,
                                   'rate'=>$test_data['test_rate'],
                                   'base_rate'=>$test_data['test_base_rate'],
                                 );
                    $this->db->insert('path_test_to_doctors',$data);
                } 
            }
        }
    }

    public function get_test_to_doctors()
    {
        $post = $this->input->post();
        $result = array();
        if(isset($post) && !empty($post))
        {
            $this->db->select('*');
            if(!empty($post['doc_id']))
            {
                $this->db->where('doc_id',$post['doc_id']);
            }
            if(!empty($post['test_id']))
            {
                $this->db->where('test_id',$post['test_id']);
            }
            if(!empty($post['branch_id']))
            {
                $this->db->where('branch_id',$post['branch_id']);
            }
            $query = $this->db->get('path_test_to_doctors');
            $result = $query->result_array();
            
        }
        return $result;
    }

    public function doctor_rate_plan($doctor_id="")
    {
        $users_data = $this->session->userdata('auth_users');  
        $company_data = $this->session->userdata('company_data');  
        $this->db->select('path_rate_plan.*');     
        $this->db->where('hms_doctors.branch_id',$company_data['parent_id']); 
        if(!empty($doctor_id))
        {
          $this->db->where('hms_doctors.id',$doctor_id);  
        } 
        $this->db->join('path_rate_plan','path_rate_plan.id = hms_doctors.rate_plan_id');
        $query_branch = $this->db->get('hms_doctors'); 
        $result_branch = $query_branch->result(); 
        return $result_branch;
    }
   
    public function doctor_test_price($test_id="", $doctor_id="")
    {
        $users_data = $this->session->userdata('auth_users');  
        $this->db->select('path_doctor_test_price.base_rate as doctor_base_rate, path_test.base_rate as test_base_rate'); 
        if(!empty($test_id))
        {
          $this->db->where('path_test.id',$test_id);  
        }  
        $this->db->join('path_doctor_test_price','path_doctor_test_price.test_id = path_test.id AND path_doctor_test_price.doctor_id = "'.$doctor_id.'"', 'left');
        $query_branch = $this->db->get('path_test'); 
        //echo $this->db->last_query();die;
        $result_branch = $query_branch->row_array(); 
        return $result_branch;
    }

    public function doctor_profile_price($profile_id="", $doctor_id="")
    {
        $users_data = $this->session->userdata('auth_users');  
        $this->db->select('path_profile_to_price.base_rate as doctor_base_rate, path_profile.base_rate as test_base_rate'); 
        if(!empty($profile_id))
        {
          $this->db->where('path_profile.id',$profile_id);  
        }  
        $this->db->join('path_profile_to_price','path_profile_to_price.profile_id = path_profile.id AND path_profile_to_price.doctor_id = "'.$doctor_id.'"', 'left');
        $query_branch = $this->db->get('path_profile'); 
        //echo $this->db->last_query();die;
        $result_branch = $query_branch->row_array(); 
        return $result_branch;
    }


}
?>