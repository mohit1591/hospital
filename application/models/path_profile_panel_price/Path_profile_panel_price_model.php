<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Path_profile_panel_price_model extends CI_Model {

	var $table = 'path_profile';
	var $column = array('path_profile_panel_price.id','path_profile_panel_price.relation', 'path_profile_panel_price.status','path_profile_panel_price.created_date');  
	var $order = array('path_profile.id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    private function _get_datatables_query($branch_id='',$not_in_test='')
    {
        //print_r($_POST);die;
        $cases='';
        $users_data = $this->session->userdata('auth_users');
        $company_data =  $this->session->userdata('company_data');
        // if(isset($_POST['test_head']) && !empty($_POST['test_head']))
        // {
        //   $test_master_search = array('test_head'=>$_POST['test_head'], 'dept_id'=>$_POST['dept_id']);
        // $this->session->set_userdata('test_master_search',$test_master_search);  
        // }
        
       if(!empty($_POST['paneln_ids']))
        {

            $cases= '(CASE WHEN path_profile_panel_price.master_rate > 0 THEN path_profile_panel_price.master_rate ELSE path_profile.master_rate END) as master_rate , (CASE WHEN path_profile_panel_price.base_rate > 0 THEN path_profile_panel_price.base_rate ELSE path_profile.base_rate END) as base_rate,path_profile_panel_price.panel_id,path_profile_panel_price.profile_id';
        }
        else
        {

           $cases= 'path_profile.master_rate , path_profile.base_rate';
           //print_r($cases);die;
        }
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $this->db->select("path_profile.*,".$cases); 
        if(!empty($_POST['paneln_ids']))
        {
            $this->db->join('path_profile_panel_price','path_profile_panel_price.profile_id=path_profile.id AND path_profile_panel_price.panel_id = "'.$_POST['paneln_ids'].'"','left');
        }
        else
        {
           $this->db->join('path_profile_panel_price','path_profile_panel_price.profile_id=path_profile.id','left'); 
        }
        
        $this->db->from($this->table);  
         $this->db->group_by('path_profile.id');  
        if(!empty($not_in_test))
        {
            //$this->db->where('path_profile.id NOT IN ('.$not_in_test.')');
        }
        
        $this->db->where('path_profile.is_deleted',0);
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
                        $this->db->where('path_profile.branch_id IN('.$branch_ids.')');
                    }
                   $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                }
                else if($branch_id==$users_data['parent_id'])
                {
                    $this->db->where('path_profile.branch_id',$users_data['parent_id']);
                    $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                
                }
                else{
                        $this->db->where('path_profile.branch_id',$branch_id);
                        $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                }
            }
            else
            {
                $this->db->where('path_profile.branch_id',$users_data['parent_id']);
            }
        }
        else if($users_data['users_role']=='3')
        {
            $this->db->where('path_profile.branch_id',$company_data['id']);
        }
        else if($users_data['users_role']=='1')
        {
           $this->db->where('path_profile.branch_id',$users_data['parent_id']);
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
    
    function count_filtered($branch_id='',$not_in_test='')
    {
        $this->_get_datatables_query($branch_id,$not_in_test);
        $query = $this->db->get();
        return $query->num_rows();
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
    public function count_all()
    {
        $this->db->from('path_profile');
        return  $this->db->count_all_results();
      // print_r($this->db->last_query());die;
       
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
    public function get_test_departments($module_id='5')
    {
    	/*$users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_department.id,hms_department.branch_id,hms_department.module,hms_department.department,hms_department.ip_address,hms_department.is_deleted,hms_department.deleted_by,hms_department.deleted_date,hms_department.created_by,hms_department.modified_by,hms_department.modified_date,hms_department.created_date, (CASE WHEN hms_department.branch_id=0 THEN 1 ELSE hms_department_to_department_status.status END) status");
        //(CASE WHEN hms_department.branch_id=0 THEN hms_department.status ELSE hms_department_to_department_status.status END) as status 
        $this->db->from('hms_department'); 
         $this->db->join('hms_department_to_department_status','hms_department_to_department_status.department_id = hms_department.id','left');

        if(!empty($module_id))
        {
           
            $this->db->where('hms_department.module',$module_id); 
        }
        if(!empty($branch_id))
        {
            $this->db->where('(hms_department.branch_id='.$branch_id.' OR hms_department.branch_id=0)'); 
        }
        else
        {
            $this->db->where('(hms_department.branch_id='.$users_data['parent_id'].' OR hms_department.branch_id=0)'); 
        }
        
        $this->db->where('hms_department_to_department_status.status','1');   
        $query = $this->db->get();
        $result = $query->result(); 
        return $result;*/

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
    	    $this->db->select('path_profile_heads.*');
    	    $this->db->where('dept_id',$department_id);
            $this->db->where('is_deleted',0);
            $this->db->where('branch_id',$users_data['parent_id']);
    	    $query = $this->db->get('path_profile_heads');
    	    $result = $query->result();
    	    return $result;
    	}
    }
    public function get_profile_list()
    {
    	$post = $this->input->post();

        $users_data = $this->session->userdata('auth_users');
         if(!empty($post['branch_id']))
        {
           
           
            $this->db->select('path_profile.*,path_profile_panel_price.id,path_profile_panel_price.panel_id,path_profile_panel_price.profile_id,(CASE WHEN path_profile_panel_price.master_rate>0 THEN path_profile_panel_price.master_rate ELSE path_profile.master_rate END) as master_rate,(CASE WHEN path_profile_panel_price.base_rate>0 THEN path_profile_panel_price.base_rate ELSE path_profile.base_rate END) as base_rate,path_profile.branch_id,,path_profile.id');
            $this->db->join('path_profile_panel_price','path_profile_panel_price.profile_id=path_profile.id','left');
        }
        else
        {
           
            $this->db->select('path_profile.branch_id,path_profile.master_rate,path_profile.base_rate,path_profile.id,path_profile.profile_name');
        }
    	$this->db->from('path_profile');
        if(!empty($post['paneln_ids']))
        {
          
            $this->db->where('path_profile_panel_price.panel_id',$post['paneln_ids']);
        }
//         

    	if(!empty($post['branch_id']) && $post['branch_id']!=$users_data['parent_id'])
    	{
            
    		$this->db->where('path_profile.branch_id',$post['branch_id']);
            
    	}
    	else
    	{
           
    		$this->db->where('path_profile.branch_id',$users_data['parent_id']);
    	}
 
        //$this->db->group_by('path_profile.id');
    	$query = $this->db->get();
        //echo $this->db->last_query();die;
    	$result = $query->result_array();
    	//print_r($result);die;
    	return $result;


    }
    public function save_panel_rate()
    {
        $data = array();
        $post = $this->input->post();
        $users_data = $this->session->userdata('auth_users');
        if(isset($post) && !empty($post))
        {
            
            if(!empty($post['profile_id']))
            {
              
                $result = $this->get_path_panel_charge($post['profile_id'],$post['paneln_ids']);
               //print '<pre>' ;print_r($result);die;  
                if(!empty($result))
                {
                    $data = array(
                        'branch_id'=>$users_data['parent_id'],
                        'profile_id'=>$post['profile_id'],
                        'master_rate'=>$post['master_rate'],
                        'base_rate'=>$post['base_rate'],
                        'type'=>0,
                        'panel_id'=>$post['paneln_ids']
                    );

                    $this->db->where('branch_id',$users_data['parent_id']);
                    $this->db->where('(profile_id='.$result[0]->profile_id.' and panel_id='.$result[0]->panel_id.' and id='.$result[0]->id.')');
                    $this->db->update('path_profile_panel_price',$data);
                   //echo $this->db->last_query();die;
                }
                else
                {
                   $data = array(
                        'branch_id'=>$users_data['parent_id'],
                        'profile_id'=>$post['profile_id'],
                        'master_rate'=>$post['master_rate'],
                        'base_rate'=>$post['base_rate'],
                        'type'=>0,
                        'panel_id'=>$post['paneln_ids']
                    );
                    $this->db->set('created_by',$users_data['id']);
                    $this->db->set('created_date',date('Y-m-d H:i:s'));

                    //$this->db->set('modified_by',$user_data['id']);
                    //$this->db->set('modified_date',date('Y-m-d H:i:s'));
                    $this->db->insert('path_profile_panel_price',$data);
                    //echo $this->db->last_query();die;
                }
            }
         
        }
    }


    public function save_panel_all_rate()
    {
        //echo 'dd';die;
        $data = array();
        $post = $this->input->post();
        //echo "<pre>"; print_r($post);die;
        $users_data = $this->session->userdata('auth_users');
         //$test_master_head = $this->session->userdata('master_test_head');
    //  print_r($test_master_head);die;
       
        if(isset($post) && !empty($post['profile_id']))
        {
           $branch_id = $users_data['parent_id'];
           
            foreach($post['profile_id'] as $profile_data)
            {  
                if(isset($profile_data) && $profile_data > 0)
                {
                    $check=$this->check_profile_panel($profile_data['profiles_id']);
        
                if($check==1){
                    $this->db->where('branch_id',$branch_id);
                    $this->db->where('profile_id',$profile_data['profiles_id']);
                    $this->db->where('panel_id',$post['paneln_ids']);
                    $this->db->delete('path_profile_panel_price');
                }
                
                   //echo 1;die;
                $data = array(
                        'branch_id'=>$users_data['parent_id'],
                        'profile_id'=>$profile_data['profiles_id'],
                        'master_rate'=>$profile_data['master_rate'],
                        'base_rate'=>$profile_data['base_rate'],
                        'type'=>0,
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'panel_id'=>$post['paneln_ids']
                    );
        
                    $this->db->insert('path_profile_panel_price',$data);
                //echo $this->db->last_query();die;
                   
                
            } 
                
               
            }
           
        }
    }
    function get_path_panel_charge($profile_id="",$paneln_ids="")
    {
       $this->db->select('*');
       $this->db->where('panel_id',$paneln_ids);
       $this->db->where('profile_id',$profile_id);
       $res= $this->db->get('path_profile_panel_price')->result();
       return $res;
    }
   
    function check_profile_panel($profile_id)
    {

       $this->db->select('*');
       $this->db->where('profile_id',$profile_id);
       $query= $this->db->get('path_profile_panel_price');

       $res=$this->db->affected_rows();
       //echo $this->db->last_query();die;
       if($res > 0)
       {
       // echo 1;die;
        return 1;
       }
       else{
        //echo 2;die;
        return 0;
       }
    }
   
   public function get_panel_name($panel_id)
   {
        $this->db->select('hms_insurance_company.insurance_company');
        $this->db->where('hms_insurance_company.id',$panel_id);
        $query= $this->db->get('hms_insurance_company');
        //echo $this->db->last_query();die; 
        $results = $query->result();
        return $results[0]->insurance_company;
   }
   
   public function search_profile_list($panel_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_profile.id as profileID,path_profile.profile_name,path_profile.print_name,path_profile_panel_price.id,path_profile_panel_price.panel_id,path_profile_panel_price.profile_id,(CASE WHEN path_profile_panel_price.master_rate>0 THEN path_profile_panel_price.master_rate ELSE path_profile.master_rate END) as master_rate,(CASE WHEN path_profile_panel_price.base_rate>0 THEN path_profile_panel_price.base_rate ELSE path_profile.base_rate END) as base_rate,path_profile.branch_id');
            
        $this->db->join('path_profile_panel_price','path_profile_panel_price.profile_id=path_profile.id','left');
        $this->db->from('path_profile');
        if(!empty($panel_id))
        {
          $this->db->where('path_profile_panel_price.panel_id',$panel_id);
        }
        $this->db->where('path_profile.branch_id',$users_data['parent_id']);
    	//$this->db->group_by('path_profile.id');
    	$query = $this->db->get();
        //echo $this->db->last_query();die;
    	$result = $query->result();
    	//print_r($result);die;
    	return $result;


    }
    
    public function udpate_all_profile_panel($test_all_datas= array())
    {
        //echo "<pre>"; print_r($test_all_datas); exit; 
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($test_all_datas))
        {
            foreach($test_all_datas as $test_all_data)
            { 
                
               
                  $data = array(
                        'branch_id'=>$users_data['parent_id'],
                        'profile_id'=>$test_all_data['profile_id'],
                        'master_rate'=>$test_all_data['master_rate'],
                        'base_rate'=>$test_all_data['master_rate'],
                        'type'=>0,
                        'panel_id'=>$test_all_data['panel_id']
                    );

                    $this->db->where('branch_id',$users_data['parent_id']);
                    $this->db->where('(profile_id='.$test_all_data['profile_id'].' and panel_id='.$test_all_data['panel_id'].' and id='.$test_all_data['profile_panel_id'].')');
                    $this->db->update('path_profile_panel_price',$data);
                   //echo $this->db->last_query();die;

                }

            }
    }
    



}
?>