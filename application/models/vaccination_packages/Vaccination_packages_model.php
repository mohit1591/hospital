<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_packages_model extends CI_Model {

	var $table = 'hms_vaccination_packages';
	var $column = array('hms_vaccination_packages.id','hms_vaccination_packages.title','hms_vaccination_packages.amount','hms_vaccination_packages.status','hms_vaccination_packages.created_date',  'hms_vaccination_packages.modified_date');  
   
	var $order = array('id' => 'desc');


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
     
		$this->db->select("hms_vaccination_packages.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_vaccination_packages.is_deleted','0');
        $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
        
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
    private function _get_vaccination_kit_history_datatables_query()
    {
        $post = $this->input->post();
     
        $users_data = $this->session->userdata('auth_users');
        $vaccination_kit_search_data = $this->session->userdata('vaccination_kit_stock_search');

        if($post['opt_type']=='1'){
            $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , sum(hms_vaccination_kit_stock.debit)-sum(hms_vaccination_kit_stock.credit) as qty_kit 
                "); 
            $this->db->from($this->table); 
            $this->db->where('hms_vaccination_packages.is_deleted','0');
            $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0 or hms_vaccination_kit_stock.section_id=4)');
                $this->db->or_where('(hms_vaccination_kit_stock.kit_id IN (select package_id from hms_branch_to_package where branch_to_id = '.$users_data['parent_id'].'))');
          
            
            // $this->db->where('hms_vaccination_kit_stock.section_id',3);
            // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
            $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        }
        else if($post['opt_type']=='2'){

        }
        else if($post['opt_type']=='3'){
            
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
    function get_vaccination_kit_history_datatables()
    {
        $this->_get_vaccination_kit_history_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }
    private function _get_vaccination_kit_datatables_query()
    {
     
        $users_data = $this->session->userdata('auth_users');
        $vaccination_kit_search_data = $this->session->userdata('vaccination_kit_stock_search');
        //print_r($vaccination_kit_search_data);die;
        // $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id ,(select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_vaccination_packages.id) as qty_kit"); 
         $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , sum(hms_vaccination_kit_stock.debit)-sum(hms_vaccination_kit_stock.credit) as qty_kit 
            "); 
        $this->db->join('hms_vaccination_packages','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id');
        $this->db->from('hms_vaccination_kit_stock'); 
        $this->db->where('hms_vaccination_packages.is_deleted','0');
        if(isset($vaccination_kit_search_data) && !empty($vaccination_kit_search_data))
        {
            /*if(array_key_exists('sub_branch_id',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['sub_branch_id']))
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$vaccination_kit_search_data['sub_branch_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0 or hms_vaccination_kit_stock.section_id=4)');
                    $this->db->or_where('(hms_vaccination_kit_stock.kit_id IN (select package_id from hms_branch_to_package where branch_to_id = '.$vaccination_kit_search_data['sub_branch_id'].'))');
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0 or hms_vaccination_kit_stock.section_id=4)');
                    $this->db->or_where('(hms_vaccination_kit_stock.kit_id IN (select package_id from hms_branch_to_package where branch_to_id = '.$users_data['parent_id'].'))');
                }
            }
            else
            { 
                $this->db->or_where('(hms_vaccination_kit_stock.kit_id IN (select package_id from hms_branch_to_package where branch_to_id = '.$users_data['parent_id'].'))');
            }*/ 
         
            if(array_key_exists('start_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['start_date']))
                {
                     $start_date=date('Y-m-d',strtotime($vaccination_kit_search_data['start_date']))." 00:00:00";
                    $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                }
              
            }
            if(array_key_exists('end_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['end_date']))
                {
                     $end_date=date('Y-m-d',strtotime($vaccination_kit_search_data['end_date']))." 23:59:59";
                    $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                }
              
            }
        
            // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
      
        
        } 

        //$this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']);
        if(isset($vaccination_kit_search_data['branch_id']) && $vaccination_kit_search_data['branch_id']!=''){
        $this->db->where('hms_vaccination_kit_stock.branch_id IN ('.$vaccination_kit_search_data['branch_id'].')');
        }else{
        $this->db->where('hms_vaccination_kit_stock.branch_id = "'.$users_data['parent_id'].'"');
        }
        // $this->db->where('hms_vaccination_kit_stock.section_id',3);
        // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        
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




    function get_vaccination_kit_datatables()
    {
        $this->_get_vaccination_kit_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }
    private function _get_medicne_datatables_query($vaccination_kit_data=array())
    {
        $users_data = $this->session->userdata('auth_users');
     
        $this->db->select("hms_vaccination_entry.*,hms_vaccination_company.id as comp_id,hms_vaccination_company.company_name"); 
        $this->db->from('hms_vaccination_entry');
        $this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');
        if(!empty($vaccination_kit_data))
        {
            if(!empty($vaccination_kit_data))
            { 
                $id_list = [];
                foreach($vaccination_kit_data as $id)
                {
                    if(!empty($id) && $id>0)
                    {
                        $id_list[]  = $id;
                    } 
                }
            }
            $vaccination_id = implode(',', $id_list);
            $this->db->where('hms_vaccination_entry.id NOT IN ('.$vaccination_id.')');
        }
        $this->db->where('(hms_vaccination_entry.is_deleted=0 and hms_vaccination_company.is_deleted=0)'); 
        $this->db->where('(hms_vaccination_entry.branch_id='.$users_data['parent_id'].' and hms_vaccination_company.branch_id='.$users_data['parent_id'].')');
       
        
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

    function get_vaccination_datatables($vaccination_kit_data)
    {
        $this->_get_medicne_datatables_query($vaccination_kit_data);
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
    function vaccination_count_filtered()
    {
        $this->_get_medicne_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function vaccination_count_all()
    {
        $this->db->from('hms_medicine');
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
    	$query = $this->db->get('hms_vaccination_packages');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_vaccination_packages.*');
		$this->db->from('hms_vaccination_packages'); 
		$this->db->where('hms_vaccination_packages.id',$id);
		$this->db->where('hms_vaccination_packages.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save($ids=array())
	{
        $vaccination_kit_data = $this->session->userdata('vaccination_kit_data'); 
      

        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'title'=>$post['package_title'],
                    'amount' =>$post['amount'],
                    'quantity'=>$post['package_quantity'],
                     'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );


		if(!empty($post['pack_id']) && $post['pack_id']>0)
		{     
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['pack_id']);
			$this->db->update('hms_vaccination_packages',$data);  

            $pack_id = $post['pack_id'];
            //////////Medicine Kit Stock Entry//////////////
            $this->db->select('*');
            $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('kit_id',$post['pack_id']);
            $this->db->where('section_id',0);
            // $this->db->where('parent_id',0);
            $query = $this->db->get('hms_vaccination_kit_stock');
            $medicine_stock_kit = $query->result_array();

            if(!empty($medicine_stock_kit))
            {
              

                $this->db->set('debit',$post['package_quantity']);
                $this->db->where('kit_id',$medicine_stock_kit[0]['kit_id']);
                $this->db->where('section_id',0);
                // $this->db->where('parent_id',$medicine_stock_kit[0]['kit_id']);
                $this->db->where('branch_id',$user_data['parent_id']);
                $this->db->update('hms_vaccination_kit_stock');
               
            }
           
        }
		else
        { 
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_vaccination_packages',$data); 
            $pack_id =$this->db->insert_id(); 
            ///////Add Package Into Medicine Kit Stock////////////// 
            $medicine_data_kit = array(
                'branch_id'=>$user_data['parent_id'],
                'kit_id'=>$pack_id,
                'debit'=>$post['package_quantity'],
                'parent_id'=>$pack_id
            );
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_vaccination_kit_stock',$medicine_data_kit);

            // echo $this->db->last_query();die;

		} 
       
        if(!empty($vaccination_kit_data))  
        {
          
           
            $this->db->where('package_id',$pack_id);
            $this->db->delete('hms_packages_to_vaccination');
            // foreach($vaccination_kit_data as $medicine)
            // { 
                $vaccination_data = array_values($vaccination_kit_data);
              
                $medicine_data_count = count($vaccination_data);
                for($i=0;$i<$medicine_data_count;$i++)
                {
                    $data_array[$i] = array(
                        'package_id'=>$pack_id,
                        'branch_id'=>$user_data['parent_id'],
                        'vaccination_id'=>$vaccination_data[$i]['id'],
                        'unit1_qty'=>$vaccination_data[$i]['qty1'],
                        'unit2_qty'=>$vaccination_data[$i]['qty2'],
                        'total_qty'=>$vaccination_data[$i]['total_qty']
                    );
                    // $this->db->set('package_id',$pack_id);
                   // $this->db->set('vaccination_id',$vaccination_id);
                   $this->db->insert('hms_packages_to_vaccination',$data_array[$i]);  
               }
                
               
            // }
          
        }
        else
        {
           
            $this->db->where('package_id',$pack_id);
            $this->db->delete('hms_packages_to_vaccination');
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
			$this->db->update('hms_vaccination_packages');
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
			$this->db->update('hms_vaccination_packages');
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
    public function get_added_vaccination_list()
    { 
        $vaccination_kit_data = $this->session->userdata('vaccination_kit_data');
      

        $users_data = $this->session->userdata('auth_users'); 
        $post = $this->input->post();
        $this->db->select('hms_vaccination_entry.*,sum(hms_vaccination_stock.debit)-sum(hms_vaccination_stock.credit) as total_qty, hms_vaccination_company.company_name');
        $this->db->from('hms_vaccination_entry');
        $this->db->join('hms_vaccination_stock','hms_vaccination_entry.id=hms_vaccination_stock.v_id');
        $this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');
        $this->db->where('hms_vaccination_entry.is_deleted',0);
        $this->db->where('hms_vaccination_entry.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_entry.id');
        if(isset($post) && !empty($post['search_data']))
        {
            $this->db->where('(hms_vaccination_entry.vaccination_name LIKE "'.$post['search_data'].'%" OR hms_vaccination_company.company_name LIKE "'.$post['search_data'].'%" OR hms_vaccination_entry.vaccination_code LIKE "'.$post['search_data'].'%")'); 
        }
       if(!empty($vaccination_kit_data))
        {
          
                $id_list = array_column($vaccination_kit_data,'id');
                $mids = implode(',',$id_list);   
            
            $this->db->where('hms_vaccination_entry.id NOT IN('.$mids.')');
        } 

        $order = array('hms_vaccination_entry.id' => 'desc');
        $this->db->order_by(key($order), $order[key($order)]);

        $query = $this->db->get();  

        return $query->result_array();
		
    }
    //add the selected child test into selected child test list
    public function addallvaccination($ids=array())
    {
        $users_data = $this->session->userdata('auth_users'); 
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
			$this->db->select('hms_vaccination_entry.*,sum(hms_vaccination_stock.debit)-sum(hms_vaccination_stock.credit) as total_qty, hms_vaccination_company.company_name');
          $this->db->from('hms_vaccination_entry');
          $this->db->join('hms_vaccination_stock','hms_vaccination_entry.id=hms_vaccination_stock.v_id');
          $this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');
          $this->db->where('hms_vaccination_entry.is_deleted',0);
          $this->db->where('hms_vaccination_entry.branch_id',$users_data['parent_id']);
           $this->db->group_by('hms_vaccination_entry.id');
           $this->db->where('hms_vaccination_entry.id IN ('.$medicne_ids.')');
          
           
            // $this->db->group_by("path_test_under.parent_id");
            $query = $this->db->get(); 
            
            // echo $this->db->last_query();die;
            return $query->result_array();
    	
    	} 
    }
    public function get_rest_child_test_after_delete(){
		$vaccination_kit_data = $this->session->userdata('vaccination_kit_data');
		$id_list = [];
        if(!empty($vaccination_kit_data)){
    		foreach($vaccination_kit_data as $child_test){
    			if(!empty($child_test) && $child_test>=0){
                    $id_list[]  = $child_test;
    			} 
    		}
    		$vaccination_kit_data = implode(',', $id_list);
        }
        else{
    		$vaccination_kit_data='';
    	}
        if($vaccination_kit_data){
	        $this->db->select("test_name,id");
            $this->db->where('id IN ('.$vaccination_kit_data.')');
            $this->db->from('path_test');
            // $this->db->group_by("path_test_under.parent_id");
           $query = $this->db->get(); 
           return $query->result_array();
       }else{
        $result=array();
        return $result;
       }
    }
    public function selected_medicine($id=''){
        $user_data = $this->session->userdata('auth_users');
     
        if(!empty($id)){
            
            $this->db->select("hms_vaccination_entry.*,hms_vaccination_company.id as comp_id,hms_vaccination_company.company_name,hms_packages_to_vaccination.unit1_qty,hms_packages_to_vaccination.unit2_qty,hms_packages_to_vaccination.total_qty");
            $this->db->from("hms_vaccination_entry");
            $this->db->join("hms_packages_to_vaccination","hms_vaccination_entry.id=hms_packages_to_vaccination.vaccination_id");
            $this->db->where("hms_packages_to_vaccination.package_id='".$id."'");
            $this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');
            $this->db->where("hms_vaccination_entry.is_deleted",'0');
            $this->db->where("hms_vaccination_entry.branch_id",$user_data['parent_id']);
            
            // $this->db->or_where("(path_test.branch_id='0' OR path_test.branch_id='".$user_data['parent_id']."') OR (path_profile.branch_id='0' OR path_profile.branch_id='".$user_data['parent_id']."')");
            $query = $this->db->get();
            // echo $this->db->last_query();die; 
            return $query->result_array();
           
        }


    }
        public function save_sort_order_data($id='',$sort_order_value=''){
        if(!empty($id) && !empty($sort_order_value)){
            $this->db->set('sort_order',$sort_order_value);
            $this->db->where('id',$id);
            $result='';
            if($this->db->update('hms_vaccination_packages')){
                $result='true';
                return $result;
            }

        }
    }
     public function get_vaccination_kit_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $medicine_kit_ids = $this->session->userdata('alloted_vaccination_kit_ids');
        $vaccination_kit_search_data = $this->session->userdata('vaccination_kit_stock_search');
        $result = array();
        if(!empty($medicine_kit_ids))
        {
            $id_list = [];
            foreach($medicine_kit_ids as $id)
            {
                if(!empty($id) && $id>0)
                {
                    $id_list[]  = $id;
                }
            } 
            
            $medicine_kit_id = implode(',', $id_list);
            // $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_vaccination_packages.id) as qty_kit"); 
            $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , sum(hms_vaccination_kit_stock.debit)-sum(hms_vaccination_kit_stock.credit) as qty_kit 
            "); 
            $this->db->from($this->table); 
            $this->db->where('hms_vaccination_packages.is_deleted','0');
            if(isset($vaccination_kit_search_data) && !empty($vaccination_kit_search_data))
            {
                if(array_key_exists('sub_branch_id',$vaccination_kit_search_data))
                {
                    if(!empty($vaccination_kit_search_data['sub_branch_id']))
                    {
                        $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$vaccination_kit_search_data['sub_branch_id'].' and hms_vaccination_kit_stock.kit_id IN('.$medicine_kit_id.') and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                    }
                    else
                    {
                        $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and hms_vaccination_kit_stock.kit_id IN('.$medicine_kit_id.')  and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                    }
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and hms_vaccination_kit_stock.kit_id IN('.$medicine_kit_id.') and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
                // if(array_key_exists('start_date',$vaccination_kit_search_data))
                // {
                //     if(!empty($vaccination_kit_search_data['start_date']))
                //     {
                //         $start_date=date('Y-m-d',strtotime($vaccination_kit_search_data['start_date']))." 00:00:00";
                //         $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                //     }
                // }
                // if(array_key_exists('end_date',$vaccination_kit_search_data))
                // {
                //     if(!empty($vaccination_kit_search_data['end_date']))
                //     {
                //         $end_date=date('Y-m-d',strtotime($vaccination_kit_search_data['end_date']))." 23:59:59";
                //         $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                //     }
                // }
                // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
            }
            else{
                $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and hms_vaccination_kit_stock.kit_id IN('.$medicine_kit_id.') and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
            }
            // $this->db->where('hms_vaccination_kit_stock.section_id',3);
            // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
            $this->db->group_by('hms_vaccination_kit_stock.kit_id');
            
            //     $this->db->select("hms_vaccination_packages.*");
            //     $this->db->where("branch_id",$users_data['parent_id']);
            //     $this->db->where('id IN ('.$medicine_kit_id.')');
            //     $this->db->from('hms_vaccination_packages');
           
            $query = $this->db->get();
                // echo $this->db->last_query();die;
            $result = $query->result_array();
        }
        return $result;
    }
    // public function allot_vaccination_kit_to_branch()
    // {
    //     $post = $this->input->post();
    //     $users_data = $this->session->userdata('auth_users'); 
    //     if(isset($post) && !empty($post))
    //     {

    //         foreach($post['medicine'] as $mkit_id=>$medicine_kit)
    //         {
    //             $medicine_kit_id = '';
    //             ///////////////// Medicine Kit Stock Entry //////////
                
    //             // $this->db->select('*');
    //             // $this->db->where('branch_id',$users_data['parent_id']);
    //             // $this->db->where('id',$medicine_kit['mkit_id']);
    //             // $query = $this->db->get('hms_vaccination_packages');
    //             // $allot_by_branch_package_det = $query->result_array();
              
    //             // if(!empty($allot_by_branch_package_det))
    //             // {
    //             //     $this->db->select('*');
    //             //     $this->db->where('branch_id',$post['sub_branch_id']);
    //             //     $this->db->where('title',$allot_by_branch_package_det[0]['title']);
    //             //     $query = $this->db->get('hms_vaccination_packages');
    //             //     $allot_to_branch_package_det = $query->result_array();
                   
    //             //     if(!empty($allot_to_branch_package_det)){
                        
    //             //         $medicine_kit_id = $allot_to_branch_package_det[0]['id'];
                        
    //             //     }
    //             //     else{

    //             //         $data = array(
    //             //             'branch_id'=>$post['sub_branch_id'],
    //             //             'title'=>$allot_by_branch_package_det[0]['title'],
    //             //             'amount'=>$allot_by_branch_package_det[0]['amount'],
    //             //             'quantity'=>$medicine_kit['qty'],
    //             //             'status'=>$allot_by_branch_package_det[0]['status'],
    //             //             'ip_address'=>$_SERVER['REMOTE_ADDR'],
    //             //             'created_date'=>date('Y-m-d H:i:s')

    //             //         );
    //             //         $this->db->insert('hms_vaccination_packages',$data);
    //             //         $medicine_kit_id = $this->db->insert_id();

    //             //     }

                       
    //             // }
    //             /////////////////Fetch Medicine from Kit for self Branch////////////
    //             $this->db->select('hms_vaccination_entry.id as med_id,hms_vaccination_entry.branch_id as med_branch_id,,hms_vaccination_entry.vaccination_code,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.unit_id,hms_vaccination_entry.unit_second_id,hms_vaccination_entry.conversion,hms_vaccination_entry.min_alrt,hms_vaccination_entry.packing,hms_vaccination_entry.rack_no,hms_vaccination_entry.salt,hms_vaccination_entry.manuf_company,hms_vaccination_entry.mrp,hms_vaccination_entry.purchase_rate,hms_vaccination_entry.hsn_no,hms_vaccination_entry.vat,hms_vaccination_entry.discount,hms_vaccination_entry.igst,hms_vaccination_entry.cgst,hms_vaccination_entry.status as med_status,hms_vaccination_entry.is_deleted as med_is_deleted,hms_vaccination_entry.deleted_date as med_deleted_date,hms_vaccination_unit.id as units_ids,hms_vaccination_unit.medicine_unit,hms_vaccination_unit.branch_id as unit_branch_id,hms_vaccination_unit.status as unit_status,hms_vaccination_unit.deleted_by as unit_deleted_by,hms_vaccination_unit.deleted_date as unit_deleted_date,hms_vaccination_company.id as com_id,hms_vaccination_company.company_name,hms_vaccination_company.branch_id as com_branch_id,hms_vaccination_company.status as com_status,hms_vaccination_company.deleted_by as com_deleted_by,hms_vaccination_company.deleted_date as com_deleted_date,hms_packages_to_vaccination.total_qty,hms_packages_to_vaccination.unit1_qty,hms_packages_to_vaccination.unit2_qty');
    //             $this->db->from('hms_vaccination_entry');
    //             $this->db->join('hms_packages_to_vaccination','hms_vaccination_entry.id=hms_packages_to_vaccination.vaccination_id and hms_packages_to_vaccination.branch_id='.$users_data['parent_id']);
    //              $this->db->join('hms_vaccination_unit','hms_vaccination_unit.id=hms_vaccination_entry.unit_id','left');
               
    //             $this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left'); 
    //             $this->db->where('hms_packages_to_vaccination.package_id',$medicine_kit['mkit_id']);
                
    //             // $this->db->group_by('hms_packages_to_vaccination.package_id');
    //             $query = $this->db->get();
    //             // echo $this->db->last_query();die;
    //             $m_result = $query->result_array();
    //             // print_r($m_result);
    //             // echo "3";
    //             if(!empty($m_result))
    //             {

                    
    //                 /////////////////Fetch Medicine for Kit for /////////////////
    //                 /////////////////Allocated_branch////////////////////////////
    //                // $this->db->select('hms_vaccination_entry.id as med_id,hms_vaccination_entry.branch_id as med_branch_id,,hms_vaccination_entry.vaccination_code,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.unit_id,hms_vaccination_entry.unit_second_id,hms_vaccination_entry.conversion,hms_vaccination_entry.min_alrt,hms_vaccination_entry.packing,hms_vaccination_entry.rack_no,hms_vaccination_entry.salt,hms_vaccination_entry.manuf_company,hms_vaccination_entry.mrp,hms_vaccination_entry.purchase_rate,hms_vaccination_entry.hsn_no,hms_vaccination_entry.vat,hms_vaccination_entry.discount,hms_vaccination_entry.igst,hms_vaccination_entry.cgst,hms_vaccination_entry.status as med_status,hms_vaccination_entry.is_deleted as med_is_deleted,hms_vaccination_entry.deleted_date as med_deleted_date,hms_vaccination_unit.id as units_ids,hms_vaccination_unit.medicine_unit,hms_vaccination_unit.branch_id as unit_branch_id,hms_vaccination_unit.status as unit_status,hms_vaccination_unit.deleted_by as unit_deleted_by,hms_vaccination_unit.deleted_date as unit_deleted_date,hms_vaccination_company.id as com_id,hms_vaccination_company.company_name,hms_vaccination_company.branch_id as com_branch_id,hms_vaccination_company.status as com_status,hms_vaccination_company.deleted_by as com_deleted_by,hms_vaccination_company.deleted_date as com_deleted_date,hms_packages_to_vaccination.total_qty');
    //                // $this->db->from('hms_vaccination_entry');
    //                // $this->db->join('hms_packages_to_vaccination','hms_vaccination_entry.id=hms_packages_to_vaccination.vaccination_id and hms_packages_to_vaccination.branch_id='.$post['sub_branch_id']);
    //                // $this->db->join('hms_vaccination_unit','hms_vaccination_unit.id=hms_vaccination_entry.unit_id','left');
               
    //                // $this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left'); 
    //                // $this->db->where('hms_packages_to_vaccination.package_id',$medicine_kit_id);
                
                  
    //                // $query = $this->db->get();
                  
    //                // $m_sub_result = $query->result_array();
    //                // print_r($m_sub_result);
    //                // echo "4";
                  

    //                 $m_result_count = count($m_result);
    //                 // for($m=0;$m<$m_result_count;$m++){
    //                     $branch_by_package_data = array(
    //                        'branch_id'=>$users_data['parent_id'],
    //                        'branch_to_id'=>$post['sub_branch_id'],
    //                        'package_id'=>$medicine_kit['mkit_id'],
    //                        'quantity'=>$medicine_kit['qty'],
    //                     );
    //                     $this->db->insert('hms_branch_to_package',$branch_by_package_data);
    //                     credit_package_quantity_to_stock($users_data['parent_id'],$medicine_kit['mkit_id'],$medicine_kit['qty'],$patient_id="",$section_id='3');
    //                      /////////medicine allocate to branch //////////
    //                      $data_allot_to_branch = array( 
    //                         'branch_id'=>$post['sub_branch_id'],
    //                         'section_id'=>3,
    //                         'kit_id'=>$medicine_kit['mkit_id'],
    //                         'patient_id'=>'0',
    //                         'credit'=>'0',
    //                         'parent_id'=>$medicine_kit['mkit_id'],
    //                         'debit'=>$medicine_kit['qty'],
    //                         'created_date'=>date('Y-m-d H:i:s'),
    //                         'created_by'=>$users_data['parent_id'],
    //                     );
    //                    $this->db->insert('hms_vaccination_kit_stock',$data_allot_to_branch);
    //                   //     ///////////////////////////////////////
                        

    //                 // }
    //                 for($m=0;$m<$m_result_count;$m++){ 
    //                 echo "1";
    //                 die;   
    //                     ///////// Check Unit  //////////////
    //                     // $medicine_unit_data = array();
    //                     // if(!empty($m_sub_result)){
    //                     //      $this->db->select("hms_vaccination_unit.*");
    //                     //     $this->db->from('hms_vaccination_unit'); 
    //                     //     $this->db->where('hms_vaccination_unit.id',$m_sub_result[$m]['unit_id']); 
    //                     //     $this->db->where('hms_vaccination_unit.branch_id='.$post['sub_branch_id']);  
    //                     //     $query = $this->db->get(); 
                            
    //                     //     $medicine_unit_data = $query->result_array();
    //                     //     print_r($medicine_unit_data);
    //                     //     echo "5";
                           
                         
    //                     // }
    //                     // else{
    //                     $this->db->select("hms_vaccination_unit.*");
    //                     $this->db->from('hms_vaccination_unit'); 
    //                     $this->db->where('hms_vaccination_unit.id',$m_result[$m]['unit_id']); 
    //                     $this->db->where('hms_vaccination_unit.branch_id='.$users_data['parent_id']);  
    //                     $query = $this->db->get(); 
                         
    //                     $medicine_unit_data = $query->result_array();
    //                     //      print_r($medicine_unit_data);
    //                     //     echo "6";
    //                     // }
    //                     if(!empty($medicine_unit_data))
    //                     {
    //                         $m_unit_id = $medicine_unit_data[0]['id'];
    //                     }
    //                     else
    //                     {
    //                         $medicine_unit_data = array(
    //                             'branch_id'=>$post['sub_branch_id'],
    //                             'medicine_unit'=>$m_result[$m]['medicine_unit'],
    //                             'status'=>'1',
    //                             'is_deleted'=>'0',
                                
    //                             'ip_address'=>$_SERVER['REMOTE_ADDR'],
    //                             'created_by'=>$users_data['parent_id'],
    //                             'created_date'=>date('Y-m-d H:i:s')
    //                         );
    //                         if(!empty($m_result[$m]['unit_deleted_by'])){
    //                             $medicine_unit_data['deleted_by'] = $m_result[$m]['unit_deleted_by'];
    //                         }
    //                         else
    //                         {
    //                              $medicine_unit_data['deleted_by'] = '00:00:0000';
    //                         }
    //                          if(!empty($m_result[$m]['unit_deleted_date'])){
    //                             $medicine_unit_data['deleted_date'] = $m_result[$m]['unit_deleted_date'];
    //                         }
    //                         else
    //                         {
    //                              $medicine_unit_data['deleted_date'] = '00:00:0000';
    //                         }
    //                         $this->db->insert('hms_vaccination_unit',$medicine_unit_data);
    //                         $m_unit_id = $this->db->insert_id();
    //                     }
    //                     ///////// check Unit Second ////////
    //                     //             $medicine_sec_unit_data = array();
    //                     //             if(!empty($m_sub_result)){
    //                     //                  $this->db->select("hms_vaccination_unit.*");
    //                     //                 $this->db->from('hms_vaccination_unit'); 
    //                     //                 $this->db->where('hms_vaccination_unit.id',$m_sub_result[$m]['unit_second_id']); 
    //                     //                 $this->db->where('hms_vaccination_unit.branch_id='.$post['sub_branch_id']);  
    //                     //                 $query = $this->db->get(); 
                                        
    //                     //                 $medicine_sec_unit_data = $query->result_array();
    //                     //                   print_r($medicine_sec_unit_data);
    //                     //                 echo "7";
                                       
                                      
    //                     //             }
    //                     //             else{
    //                     $this->db->select("hms_vaccination_unit.*");
    //                     $this->db->from('hms_vaccination_unit'); 
    //                     $this->db->where('hms_vaccination_unit.id',$m_result[$m]['unit_second_id']); 
    //                     $this->db->where('hms_vaccination_unit.branch_id='.$users_data['parent_id']);  
    //                     $query = $this->db->get(); 
                       
    //                     $medicine_sec_unit_data = $query->result_array();
    //         //                  print_r($medicine_sec_unit_data);
    //         //                 echo "8";
                          
    //         //             }
    //                     if(!empty($medicine_sec_unit_data))
    //                     {
    //                         $m_sec_unit_id = $medicine_sec_unit_data[0]['id'];
    //                     }
    //                     else
    //                     {
    //                         $medicine_sec_unit_data = array(
    //                             'branch_id'=>$post['sub_branch_id'],
    //                             'medicine_unit'=>$m_result[$m]['medicine_unit'],
    //                             'status'=>'1',
    //                             'is_deleted'=>'0',
                               
    //                             'ip_address'=>$_SERVER['REMOTE_ADDR'],
    //                             'created_by'=>$users_data['parent_id'],
    //                             'created_date'=>date('Y-m-d H:i:s')
    //                         );
    //                          if(!empty($m_result[$m]['unit_deleted_by'])){
    //                             $medicine_sec_unit_data['deleted_by'] = $m_result[$m]['unit_deleted_by'];
    //                         }
    //                         else
    //                         {
    //                              $medicine_sec_unit_data['deleted_by'] = '00:00:0000';
    //                         }
    //                          if(!empty($m_result[$m]['unit_deleted_date'])){
    //                             $medicine_sec_unit_data['deleted_date'] = $m_result[$m]['unit_deleted_date'];
    //                         }
    //                         else
    //                         {
    //                              $medicine_sec_unit_data['deleted_date'] = '00:00:0000';
    //                         }
    //                         $this->db->insert('hms_vaccination_unit',$medicine_sec_unit_data);
    //                         $m_sec_unit_id = $this->db->insert_id();
    //                     }
    //                     ///////// Check Company ////////////
    //                     //             $medicine_company_data = array();
    //                     //             if(!empty($m_sub_result)){
    //                     //                  $this->db->select("hms_vaccination_company.*");
    //                     //                 $this->db->from('hms_vaccination_company'); 
    //                     //                 $this->db->where('hms_vaccination_company.company_name',$m_sub_result[$m]['company_name']); 
    //                     //                 $this->db->where('hms_vaccination_company.branch_id='.$post['sub_branch_id']);  
    //                     //                 $query = $this->db->get(); 
                                        
    //                     //                 $medicine_company_data = $query->result_array();
    //                     //                 print_r($medicine_company_data);
    //                     //                 echo "9";
                                        
                                     
    //                     //             }
    //                     //             else{
    //                     $this->db->select("hms_vaccination_company.*");
    //                     $this->db->from('hms_vaccination_company'); 
    //                     $this->db->where('hms_vaccination_company.company_name',$m_result[$m]['company_name']); 
    //                     $this->db->where('hms_vaccination_company.branch_id='.$users_data['parent_id']);  
    //                     $query = $this->db->get(); 
                       
    //                     $medicine_company_data = $query->result_array();
    //                     //                 print_r($medicine_company_data);
    //                     //                 echo "10";
                                     
    //                     //             }

    //                     if(!empty($medicine_company_data))
    //                     {
    //                         $m_company_id = $medicine_company_data[0]['id'];
    //                     }
    //                     else
    //                     {
    //                         $medicine_company_data = array(
    //                             'branch_id'=>$post['sub_branch_id'],
                               
    //                             'status'=>'1',
    //                              'is_deleted'=>'0',
                               
    //                             'ip_address'=>$_SERVER['REMOTE_ADDR'],
    //                             'created_by'=>$users_data['parent_id'],
    //                             'created_date'=>date('Y-m-d H:i:s')
    //                         );
    //                         if(!empty($m_result[$m]['com_deleted_by'])){
    //                             $medicine_company_data['deleted_by'] = $m_result[$m]['com_deleted_by'];
    //                         }
    //                         else
    //                         {
    //                              $medicine_company_data['deleted_by'] = '0';
    //                         }
    //                          if(!empty($m_result[$m]['com_deleted_date'])){
    //                             $medicine_company_data['deleted_date'] = $m_result[$m]['com_deleted_date'];
    //                         }
    //                         else
    //                         {
    //                              $medicine_company_data['deleted_date'] = '00:00:0000';
    //                         }
    //                          if(!empty($m_result[$m]['company_name'])){
    //                             $medicine_company_data['company_name'] = $m_result[$m]['company_name'];
    //                         }
    //                         else
    //                         {
    //                              $medicine_company_data['company_name'] = '';
    //                         }
    //                         $this->db->insert('hms_vaccination_company',$medicine_company_data);
    //                         $m_company_id = $this->db->insert_id();
    //                     }
                         
    //                     ////////////////////////////////////
                        
    //                     ///////// Check Medicine //////////
    //                     //             $checked_medicine_data = array();
    //                     //             if(!empty($m_sub_result)){
    //                     //                 $this->db->select("hms_vaccination_entry.*");
    //                     //                 $this->db->from('hms_vaccination_entry'); 
    //                     //                 $this->db->where('hms_vaccination_entry.vaccination_name',$m_sub_result[$m]['vaccination_name']);
    //                     //                 if(!empty($m_sub_result[$m]['manuf_company']))
    //                     //                 {
    //                     //                 $this->db->where('hms_vaccination_entry.manuf_company',$m_sub_result[$m]['manuf_company']); 
    //                     //                 }
    //                     //                 $this->db->where('hms_vaccination_entry.branch_id='.$post['sub_branch_id']);  
    //                     //                 $query = $this->db->get(); 
                                        
    //                     //                 $checked_medicine_data = $query->result_array();
    //                     //                 print_r($checked_medicine_data);
    //                     //                 echo "11";
                                        
                                      
                                       
    //                     //             }
    //                     //             else{
                            
    //                     $this->db->select("hms_vaccination_entry.*");
    //                     $this->db->from('hms_vaccination_entry'); 
    //                     $this->db->where('hms_vaccination_entry.vaccination_name',$m_result[$m]['vaccination_name']);
    //                     if(!empty($m_result[$m]['manuf_company']))
    //                     {
    //                     $this->db->where('hms_vaccination_entry.manuf_company',$m_result[$m]['manuf_company']); 
    //                     }
    //                     $this->db->where('hms_vaccination_entry.branch_id='.$users_data['parent_id']);  
    //                     $query = $this->db->get(); 
                        
    //                     $checked_medicine_data = $query->result_array();
    //                     //                 print_r($checked_medicine_data);
    //                     //                 echo "12";
                                        
    //                     //             }
           
    //                     if(!empty($checked_medicine_data))
    //                     {
    //                         $med_id = $checked_medicine_data[0]['id'];
    //                     }
    //                     else
    //                     {
    //                         $new_add_medicine = array(
    //                             'branch_id'=>$post['sub_branch_id'],
    //                             'vaccination_code'=>$m_result[$m]['vaccination_code'],
    //                             'vaccination_name'=>$m_result[$m]['vaccination_name'],
    //                             'unit_id'=>$m_unit_id,
    //                             'unit_second_id'=>$m_sec_unit_id,
    //                             'min_alrt'=>$m_result[$m]['min_alrt'],
    //                             'packing'=>$m_result[$m]['packing'], 
    //                             'salt'=>$m_result[$m]['salt'],
    //                             'manuf_company'=>$m_company_id,
    //                             'mrp'=>$m_result[$m]['mrp'],
    //                             'purchase_rate'=>$m_result[$m]['purchase_rate'],
    //                             'vat'=>$m_result[$m]['vat'],
    //                             'discount'=>$m_result[$m]['discount'],
    //                             'status'=>'1',
    //                              'is_deleted'=>'0',
                               
    //                             'ip_address'=>$_SERVER['REMOTE_ADDR'],
    //                             'created_by'=>$users_data['id'],  
    //                             'created_date'=>date('Y-m-d H:i:s'),

    //                         );
    //                          if(!empty($m_result[$m]['med_deleted_by'])){
    //                             $new_add_medicine['deleted_by'] = $m_result[$m]['med_deleted_by'];
    //                         }
    //                         else
    //                         {
    //                              $new_add_medicine['deleted_by'] = '0';
    //                         }
    //                          if(!empty($m_result[$m]['med_deleted_date'])){
    //                             $new_add_medicine['deleted_date'] = $m_result[$m]['med_deleted_date'];
    //                         }
    //                         else
    //                         {
    //                              $new_add_medicine['deleted_date'] = '00:00:0000';
    //                         }
    //                          if(empty($m_result[$m]['conversion']) || $m_result[$m]['conversion']==0){
    //                              $new_add_medicine['conversion'] = '1';
                                
    //                         }
    //                         else
    //                         {
    //                             $new_add_medicine['conversion'] = $m_result[$m]['conversion'];
    //                         }
    //                         $this->db->insert('hms_vaccination_entry',$new_add_medicine);
    //                         $med_id = $this->db->insert_id();
    //                     }
                   
    //                     //             if(empty($m_sub_result)){
                                    
    //                     //                 /////////Enter Medicine_to_kit_to_allocated_branch////////////
                                       
    //                     //                 $medicine_to_medicine_kit_to_allot_branch = array(
    //                     //                     'branch_id'=>$post['sub_branch_id'],
    //                     //                     'package_id'=>$medicine_kit_id,
    //                     //                     'vaccination_id'=>$med_id,
    //                     //                     'unit1_qty'=>$m_result[$m]['unit1_qty'],
    //                     //                     'unit2_qty'=>$m_result[$m]['unit2_qty'],
    //                     //                     'total_qty'=>$m_result[$m]['total_qty']
    //                     //                 );
    //                     //                 $this->db->insert('hms_packages_to_vaccination',$medicine_to_medicine_kit_to_allot_branch);

                          
    //                     //             }
    //                     ///////////////////////////////////
                        
    //                     ///////////////// Stock Entry //////////
                
    //                     /////////medicine allocate by branch //////////
    //                     $total_med_qty = $m_result[$m]['total_qty']*$medicine_kit['qty'];
    //                     $data_allot_by_branch = array( 
    //                         'branch_id'=>$users_data['parent_id'],
    //                          'type'=>6,
    //                         'v_id'=>$m_result[$m]['med_id'],
    //                         'credit'=>$total_med_qty,
    //                         'debit'=>'0',
    //                         'batch_no'=>'',
                           
    //                          'is_deleted'=>'0',
                            
    //                         'ip_address'=>$_SERVER['REMOTE_ADDR'],
    //                         'created_date'=>date('Y-m-d H:i:s'),
    //                         'modified_date'=>date('Y-m-d H:i:s'),
    //                         'created_by'=>$users_data['parent_id'],
    //                         'purchase_rate'=>$m_result[$m]['purchase_rate'],
    //                         'discount'=>$m_result[$m]['discount'],
    //                         'vat'=>$m_result[$m]['vat'],
    //                         'total_amount'=>$m_result[$m]['total_qty'],
    //                         'expiry_date'=>'',
    //                         'manuf_date'=>'',
    //                         'per_pic_rate'=>'',
    //                         'kit_status'=>'1'
    //                     );
    //                     if(!empty($m_result[$m]['med_deleted_by'])){
    //                         $data_allot_by_branch['deleted_by'] = $m_result[$m]['med_deleted_by'];
    //                     }
    //                     else
    //                     {
    //                         $data_allot_by_branch['deleted_by'] = '0';
    //                     }
    //                     if(!empty($m_result[$m]['med_deleted_date'])){
    //                         $data_allot_by_branch['deleted_date'] = $m_result[$m]['med_deleted_date'];
    //                     }
    //                     else
    //                     {
    //                         $data_allot_by_branch['deleted_date'] = '00:00:0000';
    //                     }
    //                     if(empty($m_result[$m]['conversion']) || $m_result[$m]['conversion']==0){
    //                         $data_allot_by_branch['conversion'] = '1';
    //                     }
    //                     else
    //                     {
    //                         $data_allot_by_branch['conversion'] = $m_result[$m]['conversion'];
    //                     }
                        
    //                     $this->db->insert('hms_vaccination_stock',$data_allot_by_branch);
    //                     /////////medicine allocate to branch //////////

    //                     $data_allot_to_branch = array( 
    //                         'branch_id'=>$post['sub_branch_id'],
    //                           'type'=>6,
    //                         'v_id'=>$med_id,
    //                         'credit'=>'0',
    //                         'debit'=>$total_med_qty,
    //                         'batch_no'=>'',
    //                         'is_deleted'=>'0',
                            
    //                         'ip_address'=>$_SERVER['REMOTE_ADDR'],
    //                         'created_date'=>date('Y-m-d H:i:s'),
    //                         'modified_date'=>date('Y-m-d H:i:s'),
    //                         'created_by'=>$users_data['parent_id'],
    //                         'purchase_rate'=>$m_result[$m]['purchase_rate'],
    //                         'discount'=>$m_result[$m]['discount'],
    //                         'vat'=>$m_result[$m]['vat'],
    //                         'total_amount'=>$m_result[$m]['total_qty'],
    //                         'expiry_date'=>'',
    //                         'manuf_date'=>'',
    //                         'per_pic_rate'=>'',
    //                          'kit_status'=>'1'
    //                     );
    //                     if(!empty($m_result[$m]['med_deleted_by'])){
    //                         $data_allot_to_branch['deleted_by'] = $m_result[$m]['med_deleted_by'];
    //                     }
    //                     else
    //                     {
    //                         $data_allot_to_branch['deleted_by'] = '0';
    //                     }
    //                     if(!empty($m_result[$m]['med_deleted_date'])){
    //                         $data_allot_to_branch['deleted_date'] = $m_result[$m]['med_deleted_date'];
    //                     }
    //                     else
    //                     {
    //                         $data_allot_to_branch['deleted_date'] = '00:00:0000';
    //                     }
    //                     if(empty($m_result[$m]['conversion']) || $m_result[$m]['conversion']==0){
    //                         $data_allot_by_branch['conversion'] = '1';
    //                     }
    //                     else
    //                     {
    //                         $data_allot_by_branch['conversion'] = $m_result[$m]['conversion'];
    //                     }
    //                     $this->db->insert('hms_vaccination_stock',$data_allot_to_branch);
    //                     ///////////////////////////////////////
    //                 }
    //             }
    //         //     /////////medicine kit allocate by branch //////////
    //         //     $data_allot_by_branch = array( 
    //         //         'branch_id'=>$users_data['parent_id'],
    //         //         'section_id'=>3,
    //         //         'kit_id'=>$medicine_kit['mkit_id'],
    //         //         'credit'=>$medicine_kit['qty'],
    //         //         'debit'=>'0',
    //         //         'parent_id'=>$medicine_kit['mkit_id'],
    //         //         'created_date'=>date('Y-m-d H:i:s'),
    //         //         'created_by'=>$users_data['parent_id'],
    //         //     );
    //         //     $this->db->insert('hms_vaccination_kit_stock',$data_allot_by_branch);

    //         //     /////////medicine allocate to branch //////////

    //         //     $data_allot_to_branch = array( 
    //         //         'branch_id'=>$post['sub_branch_id'],
    //         //         'section_id'=>3,
    //         //         'kit_id'=>$medicine_kit['mkit_id'],
    //         //         'credit'=>'0',
    //         //         'parent_id'=>$medicine_kit['mkit_id'],
    //         //         'debit'=>$medicine_kit['qty'],
    //         //         'created_date'=>date('Y-m-d H:i:s'),
    //         //         'created_by'=>$users_data['parent_id'],
             
    //         //     );
    //         //     $this->db->insert('hms_vaccination_kit_stock',$data_allot_to_branch);
    //         // //     ///////////////////////////////////////
    //         }
    //         $this->session->unset_userdata('alloted_vaccination_kit_ids');
        
    //     }
    // }
     public function allot_vaccination_kit_to_branch()
    {
        $post = $this->input->post();
        $users_data = $this->session->userdata('auth_users'); 
        // $alloted_kit_medicine = $this->session->userdata('alloted_kit_medicine'); 
        if(isset($post) && !empty($post))
        {
           
         
            foreach($post['medicine'] as $mkit_id=>$medicine_kit)
            {
              
               
                $medicine_kit_id = '';
           
              
                /////////////////Fetch Medicine from Kit for self Branch////////////
                $this->db->select('hms_vaccination_entry.id as med_id,hms_vaccination_entry.branch_id as med_branch_id,hms_vaccination_entry.vaccination_code,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.unit_id,hms_vaccination_entry.unit_second_id,hms_vaccination_entry.conversion,hms_vaccination_entry.min_alrt,hms_vaccination_entry.packing,hms_vaccination_entry.rack_no,hms_vaccination_entry.salt,hms_vaccination_entry.manuf_company,hms_vaccination_entry.mrp,hms_vaccination_entry.purchase_rate,hms_vaccination_entry.hsn_no,hms_vaccination_entry.vat,hms_vaccination_entry.discount,hms_vaccination_entry.igst,hms_vaccination_entry.cgst,hms_vaccination_entry.status as med_status,hms_vaccination_entry.is_deleted as med_is_deleted,hms_vaccination_entry.deleted_date as med_deleted_date,hms_vaccination_unit.id as units_ids,hms_vaccination_unit.medicine_unit,hms_vaccination_unit.branch_id as unit_branch_id,hms_vaccination_unit.status as unit_status,hms_vaccination_unit.deleted_by as unit_deleted_by,hms_vaccination_unit.deleted_date as unit_deleted_date,hms_vaccination_company.id as com_id,hms_vaccination_company.company_name,hms_vaccination_company.branch_id as com_branch_id,hms_vaccination_company.status as com_status,hms_vaccination_company.deleted_by as com_deleted_by,hms_vaccination_company.deleted_date as com_deleted_date,hms_packages_to_vaccination.total_qty,hms_packages_to_vaccination.unit1_qty,hms_packages_to_vaccination.unit2_qty');
                $this->db->from('hms_vaccination_entry');
                $this->db->join('hms_packages_to_vaccination','hms_vaccination_entry.id=hms_packages_to_vaccination.vaccination_id and hms_packages_to_vaccination.branch_id='.$users_data['parent_id'],'left');
                 $this->db->join('hms_vaccination_unit','hms_vaccination_unit.id=hms_vaccination_entry.unit_id','left');
               
                $this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left'); 
                $this->db->where('hms_packages_to_vaccination.package_id',$medicine_kit['mkit_id']);
                $query = $this->db->get();
                $m_result = $query->result_array();
              //echo $this->db->last_query(); exit;
                //echo "<pre>"; print_r($m_result);  exit;
                //////////// 
                $branch_by_package_data = array(
                   'branch_id'=>$users_data['parent_id'],
                   'branch_to_id'=>$post['sub_branch_id'],
                   'package_id'=>$medicine_kit['mkit_id'],
                   'quantity'=>$medicine_kit['qty'],
                );
                $this->db->insert('hms_branch_to_package',$branch_by_package_data);
                ////////////////
                /////medicine kit allocate by branch //////////
                    $data_allot_by_branch = array( 
                        'branch_id'=>$users_data['parent_id'],
                        'section_id'=>3,
                        'kit_id'=>$medicine_kit['mkit_id'],
                        'credit'=>$medicine_kit['qty'],
                        'debit'=>'0',
                        'parent_id'=>0,
                        'created_date'=>date('Y-m-d H:i:s'),
                        'created_by'=>$users_data['parent_id'],
                    );
                    $this->db->insert('hms_vaccination_kit_stock',$data_allot_by_branch);
                    $allot_id =$this->db->insert_id(); 

                    $data_allot_to_branch = array( 
                        'branch_id'=>$post['sub_branch_id'],
                        'section_id'=>3,
                        'kit_id'=>$medicine_kit['mkit_id'],
                        'patient_id'=>'0',
                        'credit'=>'0',
                        'parent_id'=>$allot_id,
                        'debit'=>$medicine_kit['qty'],
                        'created_date'=>date('Y-m-d H:i:s'),
                        'created_by'=>$users_data['parent_id'],
                    );
                    $this->db->insert('hms_vaccination_kit_stock',$data_allot_to_branch);

                if(!empty($m_result))
                {
                   
                   
                    
                   

                    

                     /////////medicine allocate to branch //////////
                     
                  
                   
                      
                    
                   
                    ///////////////////////////////////////

                }
                
          
            }
            $this->session->unset_userdata('alloted_vaccination_kit_ids');
        
        }
    }
    public function get_vec_kit_qty($mkit_id="",$branch_id="")
    {
       
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('(sum(debit)-sum(credit)) as total_qty');
        if(!empty($branch_id)){
            $this->db->where('branch_id',$branch_id); 
        }
        else{
             $this->db->where('branch_id',$user_data['parent_id']);
        }
        
        $this->db->where('kit_id',$mkit_id);

        $query = $this->db->get('hms_vaccination_kit_stock');
       /* print_r($query->row_array());
        echo $this->db->last_query();die;*/
        return $query->row_array();
    }
    public function get_vaccination_kit_stock_excel_data(){
        
        $users_data = $this->session->userdata('auth_users');
        $vaccination_kit_search_data = $this->session->userdata('vaccination_kit_stock_search');

         $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_vaccination_packages.id) as qty_kit");  
        $this->db->from($this->table); 
        $this->db->where('hms_vaccination_packages.is_deleted','0');
        if(isset($vaccination_kit_search_data) && !empty($vaccination_kit_search_data))
        {
            if(array_key_exists('sub_branch_id',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['sub_branch_id']))
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$vaccination_kit_search_data['sub_branch_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
            }
            else
            {
                $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
            }
         
            if(array_key_exists('start_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['start_date']))
                {
                     $start_date=date('Y-m-d',strtotime($vaccination_kit_search_data['start_date']))." 00:00:00";
                    $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                }
              
            }
            if(array_key_exists('end_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['end_date']))
                {
                     $end_date=date('Y-m-d',strtotime($vaccination_kit_search_data['end_date']))." 23:59:59";
                    $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                }
              
            }
        
            // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
      
        
        }
        else{
            $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
        }
        
        // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        $this->db->order_by('hms_vaccination_packages.id','Desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function get_vaccination_kit_stock_csv_data(){
        
        $users_data = $this->session->userdata('auth_users');
        $vaccination_kit_search_data = $this->session->userdata('vaccination_kit_stock_search');

         $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_vaccination_packages.id) as qty_kit"); 
        $this->db->from($this->table); 
        $this->db->where('hms_vaccination_packages.is_deleted','0');
        if(isset($vaccination_kit_search_data) && !empty($vaccination_kit_search_data))
        {
            if(array_key_exists('sub_branch_id',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['sub_branch_id']))
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$vaccination_kit_search_data['sub_branch_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
            }
            else
            {
                $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
            }
         
            if(array_key_exists('start_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['start_date']))
                {
                     $start_date=date('Y-m-d',strtotime($vaccination_kit_search_data['start_date']))." 00:00:00";
                    $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                }
              
            }
            if(array_key_exists('end_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['end_date']))
                {
                     $end_date=date('Y-m-d',strtotime($vaccination_kit_search_data['end_date']))." 23:59:59";
                    $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                }
              
            }
        
            // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
      
        
        }
        else{
            $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
        }
        
        // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        $this->db->order_by('hms_vaccination_packages.id','Desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function get_vaccination_kit_stock_pdf_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $vaccination_kit_search_data = $this->session->userdata('vaccination_kit_stock_search');

        $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_vaccination_packages.id) as qty_kit"); 
        $this->db->from($this->table); 
        $this->db->where('hms_vaccination_packages.is_deleted','0');
        if(isset($vaccination_kit_search_data) && !empty($vaccination_kit_search_data))
        {
            if(array_key_exists('sub_branch_id',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['sub_branch_id']))
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$vaccination_kit_search_data['sub_branch_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
            }
            else
            {
                $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
            }
         
            if(array_key_exists('start_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['start_date']))
                {
                     $start_date=date('Y-m-d',strtotime($vaccination_kit_search_data['start_date']))." 00:00:00";
                    $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                }
              
            }
            if(array_key_exists('end_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['end_date']))
                {
                     $end_date=date('Y-m-d',strtotime($vaccination_kit_search_data['end_date']))." 23:59:59";
                    $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                }
              
            }
        
            // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
      
        
        }
        else{
            $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
        }
        
        // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        $this->db->order_by('hms_vaccination_packages.id','Desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function get_medicine_kit_stock_data()
    {
       $users_data = $this->session->userdata('auth_users');
        $vaccination_kit_search_data = $this->session->userdata('vaccination_kit_stock_search');

         $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_vaccination_packages.id) as qty_kit"); 
        $this->db->from($this->table); 
        $this->db->where('hms_vaccination_packages.is_deleted','0');
        if(isset($vaccination_kit_search_data) && !empty($vaccination_kit_search_data))
        {
            if(array_key_exists('sub_branch_id',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['sub_branch_id']))
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$vaccination_kit_search_data['sub_branch_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
            }
            else
            {
                $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
            }
         
            if(array_key_exists('start_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['start_date']))
                {
                     $start_date=date('Y-m-d',strtotime($vaccination_kit_search_data['start_date']))." 00:00:00";
                    $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                }
              
            }
            if(array_key_exists('end_date',$vaccination_kit_search_data))
            {
                if(!empty($vaccination_kit_search_data['end_date']))
                {
                     $end_date=date('Y-m-d',strtotime($vaccination_kit_search_data['end_date']))." 23:59:59";
                    $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                }
              
            }
        
            // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
      
        
        }
        else{
            $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
        }
        
        // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        $this->db->order_by('hms_vaccination_packages.id','Desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function get_vaccination_kit_branch_details($get=array())
    {
       
        $users_data = $this->session->userdata('auth_users');
        $vaccination_kit_search_data = $this->session->userdata('vaccination_kit_stock_search');

        $this->db->select("hms_vaccination_packages.id,hms_vaccination_packages.title,hms_vaccination_packages.status,hms_vaccination_packages.created_date,hms_vaccination_packages.branch_id,hms_vaccination_packages.amount,hms_vaccination_kit_stock.branch_id as vaccination_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_vaccination_packages.id) as qty_kit"); 
        $this->db->from($this->table); 
        $this->db->where('hms_vaccination_packages.is_deleted','0');
        if(isset($get) && !empty($get))
        {
            if(array_key_exists('branch_id',$get))
            {
                if(!empty($get['branch_id']))
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$get['branch_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
            }
            else
            {
                $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
            }
         
            if(array_key_exists('start_date',$get))
            {
                if(!empty($get['start_date']))
                {
                     $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";
                    $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                }
              
            }
            if(array_key_exists('end_date',$get))
            {
                if(!empty($get['end_date']))
                {
                     $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                }
              
            }
        
            // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
      
        
        }
        else{
            $this->db->join('hms_vaccination_kit_stock','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
        }
        
        // $this->db->where('hms_vaccination_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        $this->db->order_by('hms_vaccination_packages.id','Desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function add_vaccination_kit_quantity($kit_id="",$opt="",$row_id=""){
        $post = $this->input->post();
       
        $users_data = $this->session->userdata('auth_users');
        if(isset($post) && !empty($post)){

            if(!empty($kit_id)){

               /////////medicine allocate to branch //////////
                    
                    $data_allot_to_branch = array( 
                    'branch_id'=>$users_data['parent_id'],
                    'section_id'=>4,
                    'kit_id'=>$kit_id,
                    'patient_id'=>'0',
                    'credit'=>'0',
                    'parent_id'=>$kit_id,
                    'debit'=>$post['quantity'],
                    'created_date'=>date('Y-m-d H:i:s'),
                    'created_by'=>$users_data['parent_id'],
                );
                if($opt=='add'){
                        
                    $this->db ->insert('hms_vaccination_kit_stock',$data_allot_to_branch);
                }
                else if($opt=='edit')
                {
                         
          

                    $this->db->set('debit',$post['quantity']);
                    $this->db->where('kit_id',$kit_id);
                    $this->db->where('id',$row_id);
                    $this->db->where('branch_id',$users_data['parent_id']);
                    $this->db->update('hms_vaccination_kit_stock');
                }
              
            }
        }

    }
    public function add_vaccination_kit_qty_manage($id=""){
        $users_data = $this->session->userdata('auth_users');
        $result = array();
        if(!empty($id)){
            $this->db->select('hms_vaccination_kit_stock.*,hms_vaccination_packages.title,hms_vaccination_packages.amount');
            $this->db->from('hms_vaccination_kit_stock');
            $this->db->join('hms_vaccination_packages','hms_vaccination_packages.id=hms_vaccination_kit_stock.kit_id');
            $this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']);
            $this->db->where('hms_vaccination_kit_stock.kit_id',$id);
            $this->db->where('hms_vaccination_packages.is_deleted',0);
            $this->db->where('hms_vaccination_kit_stock.section_id',4);
            $this->db->order_by('hms_vaccination_kit_stock.id','ASC');
            $query = $this->db->get();
            $result = $query->result_array();
        }
        return $result;
    }
    

  

    
}
?>