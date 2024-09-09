<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_packages_model extends CI_Model {

	var $table = 'hms_ipd_packages';
	var $column = array('hms_ipd_packages.id','hms_ipd_packages.name','hms_ipd_packages.amount','hms_ipd_packages.status','hms_ipd_packages.created_date','hms_ipd_packages.modified_date');  
   
	var $order = array('id' => 'desc');


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
     	$this->db->select("hms_ipd_packages.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_ipd_packages.is_deleted','0');
        $this->db->where('hms_ipd_packages.branch_id',$users_data['parent_id']);
        $this->db->where('hms_ipd_packages.type',0);    
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
    private function _get_medicne_datatables_query($particular_ids=array())
    {
        $users_data = $this->session->userdata('auth_users');
     
        $this->db->select("hms_ipd_perticular_entry.*,hms_ipd_perticular_company.id as comp_id,hms_ipd_perticular_company.company_name"); 
        $this->db->from('hms_ipd_perticular_entry');
        $this->db->join('hms_ipd_perticular_company','hms_ipd_perticular_entry.manuf_company=hms_ipd_perticular_company.id');

        if(!empty($particular_ids))
        {
            if(!empty($particular_ids))
            { 
                $id_list = [];
                foreach($particular_ids as $id)
                {
                    if(!empty($id) && $id>0)
                    {
                        $id_list[]  = $id;
                    } 
                }
            }
            $particular_id = implode(',', $id_list);
            $this->db->where('hms_ipd_perticular_entry.id NOT IN ('.$particular_id.')');
        }
        $this->db->where('(hms_ipd_perticular_entry.is_deleted=0 and hms_ipd_perticular_company.is_deleted=0)'); 
        $this->db->where('(hms_ipd_perticular_entry.branch_id='.$users_data['parent_id'].' and hms_ipd_perticular_company.branch_id='.$users_data['parent_id'].')');
       
        
        $i = 0;
    
        foreach ($this->column1 as $item) // loop column 
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

                if(count($this->column1) - 1 == $i) //last loop+
                    $this->db->group_end(); //close bracket
            }
            $column1[$i] = $item; // set column array variable to order processing
            $i++;
        }
        
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($column1[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order1))
        {
            $order = $this->order1;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_particular_datatables($particular_ids)
    {
        $this->_get_medicne_datatables_query($particular_ids);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        // echo $this->db->last_query();die;
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
    function particular_count_filtered()
    {
        $this->_get_medicne_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function particular_count_all()
    {
        $this->db->from('hms_ipd_perticular');
        return $this->db->count_all_results();
    }
    
    public function email_template_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('subject','ASC'); 
    	$query = $this->db->get('hms_ipd_packages');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_ipd_packages.*');
		$this->db->from('hms_ipd_packages'); 
		$this->db->where('hms_ipd_packages.id',$id);
		$this->db->where('hms_ipd_packages.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save($ids=array())
	{
        $post = $this->input->post();  
		$user_data = $this->session->userdata('auth_users');
        $perticular_list = $this->session->userdata('perticular_list');  
        //echo "<pre>";print_r($perticular_list);die;
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['package_name'],
                    'amount' =>$post['amount'],
                    'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['pack_id']) && $post['pack_id']>0)
		{      
                $this->db->set('modified_by',$user_data['id']);
    			$this->db->set('modified_date',date('Y-m-d H:i:s'));
                $this->db->where('id',$post['pack_id']);
    			$this->db->update('hms_ipd_packages',$data);  
                $pack_id = $post['pack_id'];
        }
		else
        { 
    			$this->db->set('type',0);
                $this->db->set('created_by',$user_data['id']);
    			$this->db->set('created_date',date('Y-m-d H:i:s'));
    			$this->db->insert('hms_ipd_packages',$data); 
                $pack_id =$this->db->insert_id();  
                // echo $this->db->last_query();die;
    		    
                //panel package insert
            
                $data_pert_new = array( 
                                'branch_id'=>$user_data['parent_id'], 
                                'package_id'=>$pack_id,
                                'type'=>0,
                                'panel_company_id'=>0,
                                'charge'=>$post['amount'],
                );
                    $this->db->insert('hms_ipd_package_charge',$data_pert_new);

                $get_all_comapny= $this->get_panel_list();
                foreach($get_all_comapny as $panel_comapny)
                {
                        $data_new = array(
                        'branch_id'=>$user_data['parent_id'], 
                        'package_id'=>$pack_id,
                        'type'=>1,
                        'panel_company_id'=>$panel_comapny->id,
                        'charge'=>$post['amount'],
                        );
                    $this->db->insert('hms_ipd_package_charge',$data_new);

                }
                  

        } 

        if(!empty($perticular_list))
        {
            $this->db->where('package_id',$pack_id);  
            $this->db->delete('hms_ipd_particular_to_packages');

            if(!empty($perticular_list))  
            {  
                foreach($perticular_list as $key=>$perticular)
                {  
                     $this->db->set('package_id',$pack_id);
                     $this->db->set('particular_id',$key);
                     $this->db->set('perticuler_amount',$perticular['particular_amount']);
                     $this->db->insert('hms_ipd_particular_to_packages');  
                }
            }
        }        
	
	}
   
	public function get_panel_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('insurance_company','ASC'); 
        $query = $this->db->get('hms_insurance_company');
        return $query->result();
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
			$this->db->update('hms_ipd_packages');
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
			$this->db->update('hms_ipd_packages');
			//echo $this->db->last_query();die;
    	} 
    }
    //fetch parent test
    public function parent_test_list(){
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->distinct('path_test.test_name');
    	$this->db->select("path_test.id,path_test.test_name");
    	$this->db->from("path_test");
    	$this->db->join("path_test_under","path_test.id=path_test_under.parent_id");
    	$this->db->where("path_test.branch_id='0'");
    	$this->db->or_where("path_test.branch_id",$user_data['parent_id']);
    	// $this->db->group_by("path_test_under.parent_id");
    	$query = $this->db->get(); 
		return $query->result();
    	
    }
    //fetch the child test according to parent test ids on change
    public function get_added_particular_list()
    {
        $particular_ids = $this->session->userdata('particular_ids');
        $users_data = $this->session->userdata('auth_users');
        $id_list = [];
        if(!empty($particular_ids))
        {
    		foreach($particular_ids as $child_test){
    			if(!empty($child_test) && $child_test>=0){
                    $id_list[]  = $child_test;
    			} 
    		}
    		$particular_ids = implode(',', $id_list);
    	}
    	else{
    		$particular_ids='';
    	}
    		
        $this->db->select("*");
        $this->db->where('is_deleted','0');
         $this->db->where('type','0');  
        $this->db->where('branch_id',$users_data['parent_id']);
        if($particular_ids){
           $this->db->where('id IN('.$particular_ids.')');
        } 
        else
        {
            $this->db->where('id IN("")');
        }
        
        $query = $this->db->get('hms_ipd_perticular'); 
        return $query->result_array();
		
    }
    //add the selected child test into selected child test list
    public function addallparticular($ids=array())
    {
        //check if array comes in json_encoded form means string in case of empty
        if(!is_array($ids)){
            $ids = json_decode($ids);
        }
        //ends
    	

        if(!empty($ids))
    	{
            //checks if array comes in json_enocded and not empty 
            // if(!is_array($ids)){
            //     $ids = json_decode($ids);
            // }
            //ends

    		$id_list = [];
    		foreach($ids as $id)
    		{
    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$medicne_ids = implode(',', $id_list);
			$this->db->select("hms_ipd_perticular_entry.*,hms_ipd_perticular_company.company_name,hms_ipd_perticular_company.id as comp_id");
            $this->db->from("hms_ipd_perticular_entry");
            $this->db->join('hms_ipd_perticular_company','hms_ipd_perticular_entry.manuf_company=hms_ipd_perticular_company.id');
            $this->db->where('hms_ipd_perticular_entry.id IN ('.$medicne_ids.')');
          
           
            // $this->db->group_by("path_test_under.parent_id");
            $query = $this->db->get(); 
            
            // echo $this->db->last_query();die;
            return $query->result_array();
    	
    	} 
    }
    public function get_rest_child_test_after_delete(){
		$particular_ids = $this->session->userdata('particular_ids');
		$id_list = [];
        if(!empty($particular_ids)){
    		foreach($particular_ids as $child_test){
    			if(!empty($child_test) && $child_test>=0){
                    $id_list[]  = $child_test;
    			} 
    		}
    		$particular_ids = implode(',', $id_list);
        }
        else{
    		$particular_ids='';
    	}
        if($particular_ids){
	        $this->db->select("test_name,id");
            $this->db->where('id IN ('.$particular_ids.')');
            $this->db->from('path_test');
            // $this->db->group_by("path_test_under.parent_id");
           $query = $this->db->get(); 
           return $query->result_array();
       }else{
        $result=array();
        return $result;
       }
    }
    public function selected_particular($id='')
    {
        $user_data = $this->session->userdata('auth_users');
     
        if(!empty($id)){
            
            $this->db->select("hms_ipd_packages.*,hms_ipd_particular_to_packages.particular_id as pert_id, hms_ipd_particular_to_packages.perticuler_amount as particuler_amount, hms_ipd_perticular.particular as particular_name");
            $this->db->from("hms_ipd_packages");
            $this->db->join("hms_ipd_particular_to_packages","hms_ipd_packages.id=hms_ipd_particular_to_packages.package_id");
            $this->db->join("hms_ipd_perticular","hms_ipd_perticular.id=hms_ipd_particular_to_packages.particular_id");
            $this->db->where("hms_ipd_particular_to_packages.package_id='".$id."'");
            $this->db->where("hms_ipd_packages.is_deleted",'0');
            $this->db->where("hms_ipd_packages.branch_id",$user_data['parent_id']);
            
            // $this->db->or_where("(path_test.branch_id='0' OR path_test.branch_id='".$user_data['parent_id']."') OR (path_profile.branch_id='0' OR path_profile.branch_id='".$user_data['parent_id']."')");
            $query = $this->db->get();
            //echo $this->db->last_query();die; 
            return $query->result();
           
        }


    }
        public function save_sort_order_data($id='',$sort_order_value=''){
        if(!empty($id) && !empty($sort_order_value)){
            $this->db->set('sort_order',$sort_order_value);
            $this->db->where('id',$id);
            $result='';
            if($this->db->update('hms_ipd_packages')){
                $result='true';
                return $result;
            }

        }
    }
    public function get_selected_particular()
    {
        $users_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
         $particular_id="";
        if(is_array(json_decode($post['particular_id'])))
        {
            $particular_ids =json_decode($post['particular_id']);
            if(!empty($particular_ids)){
                foreach($particular_ids as $child_test){
                    if(!empty($child_test) && $child_test>=0){ 
                        $id_list[]  = $child_test;
                    } 
                } 
                $particular_id = implode(',', $id_list);
            }
            
        }
        $result = array();
        if(isset($post) && !empty($post['particular_id']))
        {
            $this->db->select('*, (CASE WHEN id!=0 THEN '.$post['particular_amount'].' ELSE charge END) as particular_amount');
            $this->db->where('branch_id',$users_data['parent_id']);
            if(!empty($particular_id))
            { 
                $this->db->where('id IN ('.$particular_id.')');
            }
            else
            {
                $this->db->where('id IN ('.$post['particular_id'].')');
            }
            $query = $this->db->get('hms_ipd_perticular');
            $result = $query->result_array();
           
        }
        return $result;
    }
    public function get_updated_particular_dropdown()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('type','0');
        $this->db->where('is_deleted','0');
        $query = $this->db->get('hms_ipd_perticular');
        $result = $query->result();
        return $result;

    }
    
}
?>