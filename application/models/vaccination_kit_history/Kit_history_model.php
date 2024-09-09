<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kit_history_model extends CI_Model {

	var $table = 'hms_vaccination_kit_stock'; 
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($package_id="0",$type="0")
	{
        $users_data = $this->session->userdata('auth_users'); 
        if($type==1)
        {
			$column = array('reciept_code', 'hms_patient.patient_code' , 'hms_patient.patient_name' , 'hms_packages.title' , 'hms_vaccination_kit_stock.credit', 'hms_vaccination_kit_stock.created_date'); 
			$this->db->select("hms_vaccination_kit_stock.*, hms_patient.patient_name, hms_patient.patient_code, hms_packages.title as package_name, hms_opd_booking.reciept_code as booking_code"); 
			$this->db->from($this->table);   
			$this->db->join('hms_patient','hms_patient.id = hms_vaccination_kit_stock.patient_id','left'); 
			$this->db->join('hms_packages','hms_packages.id = hms_vaccination_kit_stock.kit_id','left'); 
			$this->db->join('hms_opd_booking','hms_opd_booking.id = hms_vaccination_kit_stock.parent_id','left'); 
			$this->db->where('hms_vaccination_kit_stock.section_id',2);
			$this->db->where('hms_opd_booking.type',3);  
			$this->db->where('hms_vaccination_kit_stock.kit_id',$package_id);  
			$this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']); 
        }
        else if($type==2)
        {
            $column = array('id', 'from_branch' , 'to_branch' , 'hms_packages.title' , 'hms_vaccination_kit_stock.credit', 'hms_vaccination_kit_stock.created_date'); 

			$this->db->select("hms_vaccination_kit_stock.*, hms_branch.branch_name as from_branch, 
				(select branch.branch_name from hms_vaccination_kit_stock as kit_stock left join hms_branch as branch on branch.id = kit_stock.branch_id where kit_stock.parent_id = hms_vaccination_kit_stock.id) as to_branch, hms_packages.title as package_name"); 
			$this->db->from($this->table);   
			$this->db->join('hms_branch','hms_branch.id = hms_vaccination_kit_stock.branch_id','left'); 
			$this->db->join('hms_packages','hms_packages.id = hms_vaccination_kit_stock.kit_id','left');   
			$this->db->where('hms_vaccination_kit_stock.section_id',3); 
			$this->db->where('hms_vaccination_kit_stock.credit > 0'); 
			$this->db->where('hms_vaccination_kit_stock.kit_id',$package_id);  
			$this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']); 
        }
        else if($type==3){
            $column = array('id', 'from_branch' ,'to_branch','hms_packages.title','hms_packages.amount','hms_vaccination_kit_stock.debit','hms_vaccination_kit_stock.created_date'); 
            $this->db->select('hms_vaccination_kit_stock.*,hms_branch.branch_name as from_branch,(select branch.branch_name from hms_vaccination_kit_stock as kit_stock left join hms_branch as branch on branch.id = kit_stock.branch_id where kit_stock.parent_id = hms_vaccination_kit_stock.id) as to_branch,hms_packages.title as package_name,hms_packages.amount,');
            $this->db->from('hms_vaccination_kit_stock');
            $this->db->join('hms_packages','hms_packages.id=hms_vaccination_kit_stock.kit_id');
            $this->db->join('hms_branch','hms_branch.id = hms_vaccination_kit_stock.branch_id','left');
            $this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']);
            $this->db->where('hms_vaccination_kit_stock.kit_id',$package_id);
            $this->db->where('hms_packages.is_deleted',0);
             // $this->db->where('hms_vaccination_kit_stock.credit > 0'); 
            $this->db->where('(hms_vaccination_kit_stock.section_id=4 or hms_vaccination_kit_stock.section_id=0)');
           
          
        
        }
        else
        {
        $column = array('hms_opd_booking.booking_code', 'hms_patient.patient_code' , 'hms_patient.patient_name' , 'hms_packages.title' , 'hms_vaccination_kit_stock.credit', 'hms_vaccination_kit_stock.created_date'); 
		$this->db->select("hms_vaccination_kit_stock.*, hms_patient.patient_name, hms_patient.patient_code, hms_packages.title as package_name, hms_opd_booking.booking_code"); 
		$this->db->from($this->table);   
        $this->db->join('hms_patient','hms_patient.id = hms_vaccination_kit_stock.patient_id','left'); 
        $this->db->join('hms_packages','hms_packages.id = hms_vaccination_kit_stock.kit_id','left'); 
        $this->db->join('hms_opd_booking','hms_opd_booking.id = hms_vaccination_kit_stock.parent_id','left'); 
        $this->db->where('hms_opd_booking.type',2); 
        $this->db->where('hms_vaccination_kit_stock.section_id',1); 
        $this->db->where('hms_vaccination_kit_stock.kit_id',$package_id);  
        $this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']); 
	    }
		$i = 0;
	
		foreach ($column as $item) // loop column 
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

	function get_datatables($package_id="0",$type="0")
	{  
		$this->_get_datatables_query($package_id,$type);
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
	public function get_by_id($id)
	{
		$this->db->select('hms_packages.*');
		$this->db->from('hms_packages'); 
		$this->db->where('hms_packages.id',$id);
		$this->db->where('hms_packages.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
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


    // excel and others

    public function get_vaccination_kit_history_excel_data($package_id="0",$type="0")
	{
        $users_data = $this->session->userdata('auth_users'); 
        if($type==1)
        {
			$column = array('reciept_code', 'hms_patient.patient_code' , 'hms_patient.patient_name' , 'hms_packages.title' , 'hms_vaccination_kit_stock.credit', 'hms_vaccination_kit_stock.created_date'); 
			$this->db->select("hms_vaccination_kit_stock.*, hms_patient.patient_name, hms_patient.patient_code, hms_packages.title as package_name, hms_opd_booking.reciept_code as booking_code"); 
			$this->db->from($this->table);   
			$this->db->join('hms_patient','hms_patient.id = hms_vaccination_kit_stock.patient_id','left'); 
			$this->db->join('hms_packages','hms_packages.id = hms_vaccination_kit_stock.kit_id','left'); 
			$this->db->join('hms_opd_booking','hms_opd_booking.id = hms_vaccination_kit_stock.parent_id','left'); 
			$this->db->where('hms_vaccination_kit_stock.section_id',2);
			$this->db->where('hms_opd_booking.type',3);  
			$this->db->where('hms_vaccination_kit_stock.kit_id',$package_id);  
			$this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']); 
        }
        else if($type==2)
        {
            $column = array('id', 'from_branch' , 'to_branch' , 'hms_packages.title' , 'hms_vaccination_kit_stock.credit', 'hms_vaccination_kit_stock.created_date'); 

			$this->db->select("hms_vaccination_kit_stock.*, hms_branch.branch_name as from_branch, 
				(select branch.branch_name from hms_vaccination_kit_stock as kit_stock left join hms_branch as branch on branch.id = kit_stock.branch_id where kit_stock.parent_id = hms_vaccination_kit_stock.id) as to_branch, hms_packages.title as package_name"); 
			$this->db->from($this->table);   
			$this->db->join('hms_branch','hms_branch.id = hms_vaccination_kit_stock.branch_id','left'); 
			$this->db->join('hms_packages','hms_packages.id = hms_vaccination_kit_stock.kit_id','left');   
			$this->db->where('hms_vaccination_kit_stock.section_id',3); 
			$this->db->where('hms_vaccination_kit_stock.credit > 0'); 
			$this->db->where('hms_vaccination_kit_stock.kit_id',$package_id);  
			$this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']); 
        }
        else if($type==3){
            $column = array('id', 'from_branch' ,'to_branch','hms_packages.title','hms_packages.amount','hms_vaccination_kit_stock.debit','hms_vaccination_kit_stock.created_date'); 
            $this->db->select('hms_vaccination_kit_stock.*,hms_branch.branch_name as from_branch,(select branch.branch_name from hms_vaccination_kit_stock as kit_stock left join hms_branch as branch on branch.id = kit_stock.branch_id where kit_stock.parent_id = hms_vaccination_kit_stock.id) as to_branch,hms_packages.title as package_name,hms_packages.amount,');
            $this->db->from('hms_vaccination_kit_stock');
            $this->db->join('hms_packages','hms_packages.id=hms_vaccination_kit_stock.kit_id');
            $this->db->join('hms_branch','hms_branch.id = hms_vaccination_kit_stock.branch_id','left');
            $this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']);
            $this->db->where('hms_vaccination_kit_stock.kit_id',$package_id);
            $this->db->where('hms_packages.is_deleted',0);
             // $this->db->where('hms_vaccination_kit_stock.credit > 0'); 
            $this->db->where('(hms_vaccination_kit_stock.section_id=4 or hms_vaccination_kit_stock.section_id=0)');
           
          
        
        }
        else
        {
        $column = array('hms_opd_booking.booking_code', 'hms_patient.patient_code' , 'hms_patient.patient_name' , 'hms_packages.title' , 'hms_vaccination_kit_stock.credit', 'hms_vaccination_kit_stock.created_date'); 
		$this->db->select("hms_vaccination_kit_stock.*, hms_patient.patient_name, hms_patient.patient_code, hms_packages.title as package_name, hms_opd_booking.booking_code"); 
		$this->db->from($this->table);   
        $this->db->join('hms_patient','hms_patient.id = hms_vaccination_kit_stock.patient_id','left'); 
        $this->db->join('hms_packages','hms_packages.id = hms_vaccination_kit_stock.kit_id','left'); 
        $this->db->join('hms_opd_booking','hms_opd_booking.id = hms_vaccination_kit_stock.parent_id','left'); 
        $this->db->where('hms_opd_booking.type',2); 
        $this->db->where('hms_vaccination_kit_stock.section_id',1); 
        $this->db->where('hms_vaccination_kit_stock.kit_id',$package_id);  
        $this->db->where('hms_vaccination_kit_stock.branch_id',$users_data['parent_id']); 
	    }
		$query = $this->db->get(); 
		 //echo $this->db->last_query();die;
		return $query->result();
		
	}
    public function get_vaccination_kit_history_excel_data111(){
        
        $users_data = $this->session->userdata('auth_users');
        
         $this->db->select("hms_packages.id,hms_packages.title,hms_packages.status,hms_packages.created_date,hms_packages.branch_id,hms_packages.amount,hms_vaccination_kit_stock.branch_id as medicine_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_packages.id) as qty_kit");  
        $this->db->from($this->table); 
        $this->db->where('hms_packages.is_deleted','0');
        
        // $this->db->where('hms_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        $this->db->order_by('hms_packages.id','Desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function get_vaccination_kit_stock_csv_data(){
        
        $users_data = $this->session->userdata('auth_users');
        $medicine_kit_search_data = $this->session->userdata('medicine_kit_stock_search');

         $this->db->select("hms_packages.id,hms_packages.title,hms_packages.status,hms_packages.created_date,hms_packages.branch_id,hms_packages.amount,hms_vaccination_kit_stock.branch_id as medicine_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_packages.id) as qty_kit"); 
        $this->db->from($this->table); 
        $this->db->where('hms_packages.is_deleted','0');
        if(isset($medicine_kit_search_data) && !empty($medicine_kit_search_data))
        {
            if(array_key_exists('sub_branch_id',$medicine_kit_search_data))
            {
                if(!empty($medicine_kit_search_data['sub_branch_id']))
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$medicine_kit_search_data['sub_branch_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
            }
            else
            {
                $this->db->join('hms_vaccination_kit_stock','hms_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
            }
         
            if(array_key_exists('start_date',$medicine_kit_search_data))
            {
                if(!empty($medicine_kit_search_data['start_date']))
                {
                     $start_date=date('Y-m-d',strtotime($medicine_kit_search_data['start_date']))." 00:00:00";
                    $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                }
              
            }
            if(array_key_exists('end_date',$medicine_kit_search_data))
            {
                if(!empty($medicine_kit_search_data['end_date']))
                {
                     $end_date=date('Y-m-d',strtotime($medicine_kit_search_data['end_date']))." 23:59:59";
                    $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                }
              
            }
        
            // $this->db->where('hms_packages.branch_id',$users_data['parent_id']);
      
        
        }
        else{
            $this->db->join('hms_vaccination_kit_stock','hms_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
        }
        
        // $this->db->where('hms_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        $this->db->order_by('hms_packages.id','Desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function get_vaccination_kit_stock_pdf_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $medicine_kit_search_data = $this->session->userdata('medicine_kit_stock_search');

        $this->db->select("hms_packages.id,hms_packages.title,hms_packages.status,hms_packages.created_date,hms_packages.branch_id,hms_packages.amount,hms_vaccination_kit_stock.branch_id as medicine_kit_stock_branch_id,hms_vaccination_kit_stock.kit_id,hms_vaccination_kit_stock.section_id , (select sum(kit_stock.debit)-sum(kit_stock.credit) from hms_vaccination_kit_stock as kit_stock where kit_stock.kit_id = hms_packages.id) as qty_kit"); 
        $this->db->from($this->table); 
        $this->db->where('hms_packages.is_deleted','0');
        if(isset($medicine_kit_search_data) && !empty($medicine_kit_search_data))
        {
            if(array_key_exists('sub_branch_id',$medicine_kit_search_data))
            {
                if(!empty($medicine_kit_search_data['sub_branch_id']))
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$medicine_kit_search_data['sub_branch_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
                else
                {
                    $this->db->join('hms_vaccination_kit_stock','hms_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
                }
            }
            else
            {
                $this->db->join('hms_vaccination_kit_stock','hms_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
            }
         
            if(array_key_exists('start_date',$medicine_kit_search_data))
            {
                if(!empty($medicine_kit_search_data['start_date']))
                {
                     $start_date=date('Y-m-d',strtotime($medicine_kit_search_data['start_date']))." 00:00:00";
                    $this->db->where('hms_vaccination_kit_stock.created_date>="'.$start_date.'"');   
                }
              
            }
            if(array_key_exists('end_date',$medicine_kit_search_data))
            {
                if(!empty($medicine_kit_search_data['end_date']))
                {
                     $end_date=date('Y-m-d',strtotime($medicine_kit_search_data['end_date']))." 23:59:59";
                    $this->db->where('hms_vaccination_kit_stock.created_date<="'.$end_date.'"');   
                }
              
            }
        
            // $this->db->where('hms_packages.branch_id',$users_data['parent_id']);
      
        
        }
        else{
            $this->db->join('hms_vaccination_kit_stock','hms_packages.id=hms_vaccination_kit_stock.kit_id and hms_vaccination_kit_stock.branch_id='.$users_data['parent_id'].' and (hms_vaccination_kit_stock.section_id=3 or hms_vaccination_kit_stock.section_id=0)');
        }
        
        // $this->db->where('hms_packages.branch_id',$users_data['parent_id']);
        $this->db->group_by('hms_vaccination_kit_stock.kit_id');
        $this->db->order_by('hms_packages.id','Desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

     
  

}
?>