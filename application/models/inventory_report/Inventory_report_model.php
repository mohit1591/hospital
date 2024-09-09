<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_report_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	

	function get_datatables($branch_id='')
	{
		$this->_get_datatables_query($branch_id);
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
    
    public function religion_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('religion','ASC'); 
    	$query = $this->db->get('hms_religion');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_religion.*');
		$this->db->from('hms_religion'); 
		$this->db->where('hms_religion.id',$id);
		$this->db->where('hms_religion.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'religion'=>$post['religion'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_religion',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_religion',$data);               
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
			$this->db->update('hms_religion');
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
			$this->db->update('hms_religion');
			//echo $this->db->last_query();die;
    	} 
    }
    public function branch_inventory_list($get="")
    { 
            $users_data = $this->session->userdata('auth_users');
            if($get['section_type']==1)
            {
                $this->db->select("path_purchase_item.*,hms_medicine_vendors.name"); 
                $this->db->where('path_purchase_item.is_deleted','0'); 
                $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left');
                $this->db->where('path_purchase_item.branch_id',$get['branch_id']);

                if(!empty($get['start_date']))
                {
                $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

                $this->db->where('path_purchase_item.purchase_date >= "'.$start_date.'"');
                }

                if(!empty($get['end_date']))
                {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('path_purchase_item.purchase_date <= "'.$end_date.'"');
                }
                
                $emp_ids='';
                if($users_data['emp_id']>0)
                {
                    if($users_data['record_access']=='1')
                    {
                        $emp_ids= $users_data['id'];
                    }
                }
                /*elseif(!empty($search_data["employee"]))
                {
                    $emp_ids=  $search_data["employee"];
                }*/
                if(isset($emp_ids) && !empty($emp_ids))
                { 
                    $this->db->where('path_purchase_item.created_by IN ('.$emp_ids.')');
                }
                $this->db->from('path_purchase_item');
                $query = $this->db->get(); 
              
            return $query->result();

            }
            if($get['section_type']==2){
              
                $this->db->select("path_purchase_return_item.*, hms_medicine_vendors.name"); 
                $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_return_item.vendor_id','left'); 
                $this->db->where('path_purchase_return_item.is_deleted','0'); 
                $this->db->where('path_purchase_return_item.is_deleted','0'); 
                 $this->db->where('path_purchase_return_item.branch_id',$get['branch_id']);  
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('path_purchase_return_item.purchase_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('path_purchase_return_item.purchase_date <= "'.$end_date.'"');
            }
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            /*elseif(!empty($search_data["employee"]))
            {
                $emp_ids=  $search_data["employee"];
            }*/
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('path_purchase_return_item.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('path_purchase_return_item');
            $query = $this->db->get(); 
          // echo $this->db->last_query(); exit; 
            return $query->result();
                
            }
            if($get['section_type']==3){
               
                $this->db->select("hms_stock_issue_allotment.*,(CASE WHEN hms_stock_issue_allotment.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment.user_type_id)  WHEN hms_stock_issue_allotment.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment.user_type_id) WHEN hms_stock_issue_allotment.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment.user_type_id) ELSE 'N/A' END) as member_name"); 
                 $this->db->where('hms_stock_issue_allotment.branch_id',$get['branch_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_stock_issue_allotment.issue_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_stock_issue_allotment.issue_date <= "'.$end_date.'"');
            }
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            /*elseif(!empty($search_data["employee"]))
            {
                $emp_ids=  $search_data["employee"];
            }*/
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_stock_issue_allotment.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_stock_issue_allotment');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
                
            }
            if($get['section_type']==4){
             
               $this->db->select("hms_stock_issue_allotment_return_item.*,(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as member_name"); 
                $this->db->where('hms_stock_issue_allotment_return_item.branch_id',$get['branch_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_stock_issue_allotment_return_item.issue_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_stock_issue_allotment_return_item.issue_date <= "'.$end_date.'"');
            }
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            /*elseif(!empty($search_data["employee"]))
            {
                $emp_ids=  $search_data["employee"];
            }*/
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_stock_issue_allotment_return_item.created_by IN ('.$emp_ids.')');
            }
             $this->db->from('hms_stock_issue_allotment_return_item');
             $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
                
            }

             if($get['section_type']==5){
               
                $this->db->select("hms_garbage_stock_item.*"); 
                $this->db->where('hms_garbage_stock_item.is_deleted','0'); 
                $this->db->where('hms_garbage_stock_item.branch_id',$get['branch_id']);
                if(!empty($get['start_date']))
                {
                $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

                $this->db->where('hms_garbage_stock_item.created_date >= "'.$start_date.'"');
                }

                if(!empty($get['end_date']))
                {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_garbage_stock_item.created_date <= "'.$end_date.'"');
                }
                $emp_ids='';
                if($users_data['emp_id']>0)
                {
                    if($users_data['record_access']=='1')
                    {
                        $emp_ids= $users_data['id'];
                    }
                }
                /*elseif(!empty($search_data["employee"]))
                {
                    $emp_ids=  $search_data["employee"];
                }*/
                if(isset($emp_ids) && !empty($emp_ids))
                { 
                    $this->db->where('hms_garbage_stock_item.created_by IN ('.$emp_ids.')');
                }
                $this->db->from('hms_garbage_stock_item');
                $query = $this->db->get(); 
                 //echo $this->db->last_query(); exit; 
                return $query->result();
                
            }
             if($get['section_type']==6){
               
                $this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit,path_stock_item.credit as stock_qty ,path_item.id as item_id,path_stock_item.type,path_stock_item.credit"); 
                $this->db->where('path_item.is_deleted','0');
                $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
                $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
                $this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');
                
                
              
                
                // $this->db->join('path_purchase_item_to_purchase as pptop',"pptop.item_id = path_item.id ",'left');
                
                $this->db->where('path_item.branch_id',$get['branch_id']);

                // if(!empty($get['start_date']))
                // {
                // $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

                // $this->db->where('path_item.created_date >= "'.$start_date.'"');
                // }

                // if(!empty($get['end_date']))
                // {
                // $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                // $this->db->where('path_item.created_date <= "'.$end_date.'"');
                // }
                $emp_ids='';
                if($users_data['emp_id']>0)
                {
                    if($users_data['record_access']=='1')
                    {
                        $emp_ids= $users_data['id'];
                    }
                }
                /*elseif(!empty($search_data["employee"]))
                {
                    $emp_ids=  $search_data["employee"];
                }*/
                if(isset($emp_ids) && !empty($emp_ids))
                { 
                    $this->db->where('path_item.created_by IN ('.$emp_ids.')');
                }
                $this->db->from('path_item');
                $this->db->order_by('hms_stock_item_unit.unit','ASC');
                $this->db->order_by('path_item.item','ASC');
                $this->db->group_by('path_item.id');
                $query = $this->db->get(); 
                // echo $query->num_rows();
                return $query->result();
                
            }

  }

    public function self_gst_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 

        if($get['section_type']==1){
                
                $this->db->select("hms_medicine_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_medicine_purchase_to_purchase where purchase_id = hms_medicine_purchase.id) as total_medicine"); 

                $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 

                $this->db->where('hms_medicine_vendors.is_deleted','0'); 
                //$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company');
                $this->db->where('hms_medicine_purchase.is_deleted','0'); 
                 $this->db->where('hms_medicine_purchase.branch_id',$users_data['parent_id']); 
                  
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_purchase.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_purchase.created_date <= "'.$end_date.'"');
            }
             $this->db->from('hms_medicine_purchase');

            }
            if($get['section_type']==2){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_medicine_return.*, hms_medicine_vendors.name as vendor_name, (select count(id) from hms_medicine_return_to_return where purchase_return_id = hms_medicine_return.id) as total_medicine"); 
                $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_return.vendor_id','left'); 
                $this->db->where('hms_medicine_return.is_deleted','0'); 
                $this->db->where('hms_medicine_vendors.is_deleted','0'); 
                 $this->db->where('hms_medicine_return.branch_id',$users_data['parent_id']);  
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_return.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_return.created_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_medicine_return');
                
            }
            if($get['section_type']==3){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_medicine_sale.*, hms_patient.patient_name, (select count(id) from hms_medicine_sale_to_medicine where sales_id = hms_medicine_sale.id) as total_medicine,hms_doctors.doctor_name"); 
                $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
                $this->db->where('hms_medicine_sale.is_deleted','0'); 
                $this->db->where('hms_patient.is_deleted','0'); 
                $this->db->where('hms_doctors.is_deleted','0'); 
                $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
                $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_sale.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_sale.created_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_medicine_sale');
                
            }
            if($get['section_type']==4){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_medicine_sale_return.*, hms_patient.patient_name, (select count(id) from hms_medicine_sale_return_medicine where sales_return_id = hms_medicine_sale_return.id) as total_medicine,hms_doctors.doctor_name"); 
                $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale_return.patient_id','left'); 
                $this->db->where('hms_medicine_sale_return.is_deleted','0'); 
                $this->db->where('hms_patient.is_deleted','0'); 
                $this->db->where('hms_doctors.is_deleted','0'); 
                $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale_return.refered_id','left');
                $this->db->where('hms_medicine_sale_return.branch_id',$users_data['parent_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_sale_return.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_sale_return.created_date <= "'.$end_date.'"');
            }
             $this->db->from('hms_medicine_sale_return');
                
            }
            
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
          
    }
    
    public function print_inventory_advance_reports($get='')
    {
         if($get['section_type']==3){
               
        $users_data = $this->session->userdata('auth_users');
       
       $this->db->select('hms_stock_issue_allotment_to_issue_allotment.issue_return_id,hms_stock_issue_allotment_to_issue_allotment.category_id,hms_stock_issue_allotment_to_issue_allotment.total_amount,hms_stock_issue_allotment_to_issue_allotment.total_amount as amount,path_item.item as item_name,path_item.item_code,hms_stock_issue_allotment_to_issue_allotment.qty as quantity,hms_stock_item_unit.unit,hms_stock_issue_allotment_to_issue_allotment.per_pic_price as item_price,path_item.id as item_id,path_stock_category.category,hms_stock_issue_allotment.*,(CASE WHEN hms_stock_issue_allotment.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment.user_type_id)  WHEN hms_stock_issue_allotment.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment.user_type_id) WHEN hms_stock_issue_allotment.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment.user_type_id) ELSE "N/A" END) as member_name,(CASE WHEN hms_stock_issue_allotment.user_type=1 THEN "Employee" WHEN hms_stock_issue_allotment.user_type=2 THEN "Patient"  WHEN hms_stock_issue_allotment.user_type=3 THEN "Doctor" ELSE "N/A" END) as department_name');
        $this->db->from('hms_stock_issue_allotment_to_issue_allotment'); 
        //$this->db->where('hms_stock_issue_allotment_to_issue_allotment.issue_return_id',$issue_id);
        $this->db->where('hms_stock_issue_allotment_to_issue_allotment.branch_id',$users_data['parent_id']);
        $this->db->join('path_item','path_item.id=hms_stock_issue_allotment_to_issue_allotment.item_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=hms_stock_issue_allotment_to_issue_allotment.unit_id','left');
        $this->db->join('path_stock_category','path_stock_category.id=hms_stock_issue_allotment_to_issue_allotment.category_id','left');
        $this->db->join('hms_stock_issue_allotment','hms_stock_issue_allotment.id=hms_stock_issue_allotment_to_issue_allotment.issue_return_id','left');
        
        $this->db->where('hms_stock_issue_allotment.branch_id',$get['branch_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_stock_issue_allotment.issue_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_stock_issue_allotment.issue_date <= "'.$end_date.'"');
            }
            $this->db->order_by('hms_stock_issue_allotment.id','DESC'); 
             $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
                
            }
    }
    
    public function print_inventory_dialysis_reports($get='')
    {
         $users_data = $this->session->userdata('auth_users');
       
       $this->db->select('path_item.item as item_name,path_item.item_code,path_item.id as item_id,hms_dialysis_to_inventory_item.*,hms_patient.patient_name,hms_patient.patient_code,hms_dialysis_booking.booking_code');
        
        $this->db->from('hms_dialysis_to_inventory_item'); 
        $this->db->join('path_item','path_item.id=hms_dialysis_to_inventory_item.item_id','left');
        
		$this->db->join('hms_patient','hms_patient.id = hms_dialysis_to_inventory_item.patient_id','left');
		$this->db->join('hms_dialysis_booking','hms_dialysis_booking.id = hms_dialysis_to_inventory_item.booking_id','left');
        
        $this->db->where('hms_dialysis_to_inventory_item.branch_id',$get['branch_id']);   
            
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_dialysis_to_inventory_item.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_dialysis_to_inventory_item.created_date <= "'.$end_date.'"');
            }
            $this->db->order_by('hms_dialysis_to_inventory_item.id','DESC'); 
             $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
                
            
    }
}
?>