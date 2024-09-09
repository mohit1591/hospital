<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Advance_stock_allotment_model extends CI_Model 
{
	var $table = 'path_item';
	var $column = array('path_item.id','path_item.item','path_item.item_code',' hms_inventory_company.company_name','path_stock_item.credit','path_stock_item.created_date','hms_branch.branch_name');  
    var $order = array(); //'id' => 'desc' 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('stock_purchase_item_list');
        $users_data = $this->session->userdata('auth_users');
		$this->db->select('path_item.*,path_stock_item.credit as quantity,path_stock_item.created_date,path_stock_item.parent_id,hms_branch.branch_name');
		$this->db->from('path_item');
		//$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left');

		$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');

		$this->db->join('hms_branch','path_stock_item.parent_id=hms_branch.id','left');
		$this->db->where('path_stock_item.branch_id',$users_data['parent_id']);
		$this->db->where('path_stock_item.type',5);
		$this->db->where('path_stock_item.credit > 0');  
		
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_stock_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_stock_item.created_date <= "'.$end_date.'"');
			}
		}
		//$this->db->where('path_stock_item.item_id',$item_id);
		$this->db->group_by('path_stock_item.parent_id,DATE_FORMAT(path_stock_item.created_date, "%Y-%m-%d")');
		$this->db->order_by('path_stock_item.created_date','DESC');
			
		
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
		$search = $this->session->userdata('stock_purchase_item_list');
        $users_data = $this->session->userdata('auth_users');
		
		$this->db->select('path_item.*,hms_inventory_company.company_name,path_stock_item.credit as quantity,path_stock_item.created_date,path_stock_item.parent_id,hms_branch.branch_name');
		$this->db->from('path_item');
		$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left');

		$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');

		$this->db->join('hms_branch','path_stock_item.parent_id=hms_branch.id','left');
		$this->db->where('path_stock_item.branch_id',$users_data['parent_id']);
		$this->db->where('path_stock_item.type',5);
		$this->db->where('path_stock_item.credit > 0');  
		
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_stock_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_stock_item.created_date <= "'.$end_date.'"');
			}
		}
		//$this->db->where('path_stock_item.item_id',$item_id);
		//$this->db->order_by('path_stock_item.id','DESC');
		
		
		return $this->db->count_all_results();
	}

	public function search_report_data()
	{
		$search = $this->session->userdata('stock_purchase_item_list');
        $users_data = $this->session->userdata('auth_users');
		$this->db->select('path_item.*,path_stock_item.credit as quantity,path_stock_item.created_date,path_stock_item.parent_id,hms_branch.branch_name');
		$this->db->from('path_item');
		//$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left');

		$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');

		$this->db->join('hms_branch','path_stock_item.parent_id=hms_branch.id','left');
		$this->db->where('path_stock_item.branch_id',$users_data['parent_id']);
		$this->db->where('path_stock_item.type',5);
		$this->db->where('path_stock_item.credit > 0');  
		
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_stock_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_stock_item.created_date <= "'.$end_date.'"');
			}
		}
		//$this->db->where('path_stock_item.item_id',$item_id);
		$this->db->group_by('path_stock_item.parent_id,DATE_FORMAT(path_stock_item.created_date, "%Y-%m-%d")');
		$this->db->order_by('path_stock_item.created_date','DESC');
		
		$result= $this->db->get()->result();
		 return $result;
	}
	
	public function get_items_allot($parent_id,$date_allot)
	{
	    
	   $users_data = $this->session->userdata('auth_users');
		$this->db->select('path_item.*,path_stock_item.credit as quantity,path_stock_item.created_date,path_stock_item.parent_id,hms_branch.branch_name,CONCAT(path_item.item) as ite');
		$this->db->from('path_item');
		$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');
        $this->db->join('hms_branch','path_stock_item.parent_id=hms_branch.id','left');
		$this->db->where('path_stock_item.branch_id',$users_data['parent_id']);
		$this->db->where('path_stock_item.type',5);
		$this->db->where('path_stock_item.credit > 0');  
		
		if(!empty($date_allot))
		{
			$start_date = $date_allot.' 00:00:00';
			$this->db->where('path_stock_item.created_date >= "'.$start_date.'"');
			$end_date = $date_allot.' 23:59:59';
			$this->db->where('path_stock_item.created_date <= "'.$end_date.'"');
		}
		$this->db->where('path_stock_item.parent_id',$parent_id);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$result = $query->result();
		//echo "<pre>"; print_r($result); die;
		$name='';
	    foreach($result as $vals)
    	{

           $name .=  $vals->item.' - '.$vals->item_code.' - '.$vals->quantity.'<br>';
			
    	}
    	
    	return $name;
	}
	
	public function get_items_print_allot($parent_id,$date_allot)
	{
	    
	   $users_data = $this->session->userdata('auth_users');
		$this->db->select('path_item.*,path_stock_item.credit as quantity,path_stock_item.created_date,path_stock_item.parent_id,hms_branch.branch_name,CONCAT(path_item.item) as ite,path_stock_item.id as stock_ids,path_stock_item.branch_id as branchids,path_stock_item.debit');
		$this->db->from('path_item');
		$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');
        $this->db->join('hms_branch','path_stock_item.parent_id=hms_branch.id','left');
		$this->db->where('path_stock_item.parent_id',$users_data['parent_id']);
		$this->db->where('path_stock_item.type',5);
		$this->db->where('path_stock_item.debit > 0');  
		//echo $date_allots = date('Y-m-d',strtotime($date_allot));
		if(!empty($date_allot))
		{
			$start_date = $date_allot.' 00:00:00';
			$this->db->where('path_stock_item.created_date >= "'.$start_date.'"');
			$end_date = $date_allot.' 23:59:59';
			$this->db->where('path_stock_item.created_date <= "'.$end_date.'"');
		}
		$this->db->where('path_stock_item.branch_id',$parent_id);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$result = $query->result();
		//echo "<pre>"; print_r($result); die;
		return $result;
	}
	
	
	
  function get_serial_no_by_stock($request_id="", $sp_id='',$branch_ids='')
  {
      $user_data=$this->session->userdata('auth_users');
      $this->db->select('inv_stock_serial_no.*');
      $this->db->where('inv_stock_serial_no.stock_id',$request_id);
      $this->db->where('inv_stock_serial_no.is_deleted',0);
      if(!empty($branch_ids))
      {
         $this->db->where('inv_stock_serial_no.branch_id',$branch_ids); 
      }
      else
      {
         $this->db->where('inv_stock_serial_no.branch_id',$user_data['parent_id']); 
      }
      
      $this->db->where('inv_stock_serial_no.sp_id',$sp_id);
      $this->db->where('inv_stock_serial_no.module_id',7);
      $query=$this->db->get('inv_stock_serial_no');
      //echo $this->db->last_query();die;
      return $query->result();
  }
	
	
	
	
	

	public function get_by_id($id)
	{
		$this->db->select('path_purchase_item.*,hms_medicine_vendors.name,hms_medicine_vendors.id as v_id,hms_medicine_vendors.vendor_id,hms_medicine_vendors.address');
		$this->db->from('path_purchase_item'); 
		$this->db->where('path_purchase_item.id',$id);
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=path_purchase_item.vendor_id');
		$this->db->where('hms_medicine_vendors.vendor_type',3);
		$this->db->where('path_purchase_item.is_deleted','0');
		
		$query = $this->db->get(); 
		return $query->row_array();
	}


	function get_purchase_to_purchase_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		 $this->db->select('path_purchase_item_to_purchase.*,path_purchase_item_to_purchase.total_amount as amount, path_item.item as item_name, path_item.item_code,path_purchase_item_to_purchase.qty as quantity, hms_stock_item_unit.unit, path_purchase_item_to_purchase.per_pic_price as item_price, path_item.id as item_id, path_item.price as purchase_amount,path_stock_category.category, path_purchase_item_to_purchase.expiry_date as exp_date');
		$this->db->from('path_purchase_item_to_purchase'); 
		$this->db->where('path_purchase_item_to_purchase.purchase_id',$id);
		$this->db->where('path_purchase_item_to_purchase.branch_id',$users_data['parent_id']);
		$this->db->join('path_item','path_item.id=path_purchase_item_to_purchase.item_id','left');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_purchase_item_to_purchase.unit_id','left');
		$this->db->join('path_stock_category`','path_stock_category.id=path_purchase_item_to_purchase.category_id','left');
	
		$query = $this->db->get()->result_array(); 
//echo $this->db->last_query();die;

		
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $item_list)	
		  {

            $result[$item_list['item_id']] =  array('item_id'=>$item_list['item_id'],'category_id'=>$item_list['category_id'],'item_code'=>$item_list['item_code'],'total_price'=>$item_list['total_amount'],'item_name'=>$item_list['item_name'].'-'.$item_list['category'],'amount'=>$item_list['item_price'],'item_price'=>$item_list['item_price'],'perpic_rate'=>$item_list['item_price'],'quantity'=>$item_list['quantity'],'exp_date'=>date('d-m-Y',strtotime($item_list['exp_date'])),'manuf_date'=>date('d-m-Y',strtotime($item_list['manuf_date'])),'unit1'=>$item_list['unit1'],'unit2'=>$item_list['unit2'],'mrp'=>$item_list['mrp'],'purchase_amount'=>$item_list['purchase_amount'],'discount'=>$item_list['discount'],'cgst'=>$item_list['cgst'],'sgst'=>$item_list['sgst'],'igst'=>$item_list['igst'],'freeunit1'=>$item_list['freeunit1'],'freeunit2'=>$item_list['freeunit2'],'total_amount'=>$item_list['total_amount'],'payment_mode'=>$item_list['payment_mode']
			
			
            ); 

		  } 
		} 
		
		return $result;
	}
    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
		$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
		$this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.type',1);
		$this->db->where('hms_payment_mode_field_value_acc_section.section_id',14);
		$this->db->where('hms_payment_mode_field_value_acc_section.branch_id = "'.$users_data['parent_id'].'"');
		$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
		//echo $this->db->last_query();die;
	     return $query;
	}


	    public function unit_list($unit_id="")
    {
    	//echo $unit_id;
       $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
         if(!empty($unit_id)){
         	
        	$this->db->where('id',$unit_id);
        }
        $this->db->where('is_deleted',0);
        $this->db->where('hms_stock_item_unit.branch_id',$users_data['parent_id']);
        $this->db->order_by('unit','ASC'); 
        $query = $this->db->get('hms_stock_item_unit');
        $result = $query->result(); 
       //print '<pre>'; print_r($result);
        return $result; 
    }

    function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	//print_r($query);exit;
    	return $query;

    }

    public function get_patient_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_patient.*');
		$this->db->from('hms_patient'); 
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].')');
		$this->db->where('hms_patient.id',$id);
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function attended_doctor_list()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (1,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		// print '<pre>'; print_r($result);
		return $result; 
	}

	public function assigned_doctor_list()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (0,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		// print '<pre>'; print_r($result);
		return $result; 
	}

	public function aasigned_doctor_by_id($id=''){
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		if(!empty($id)){
		$this->db->where('ipd_booking_id',$id);
		}
		$this->db->order_by('id','ASC');
		$this->db->group_by('doctor_id');
		$this->db->where('branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_ipd_assign_doctor');
		$result = $query->result(); 
		// echo $this->db->last_query();die;
		return $result; 
	}

	public function vendor_list($vendor_id="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		$this->db->where('branch_id = "'.$users_data['parent_id'].'"');
		if(!empty($vendor_id))
		{
		 $this->db->where('id',$vendor_id);	
		}
		$this->db->where('status',1);	
		$this->db->where('vendor_type',3);
		$query = $this->db->get('hms_medicine_vendors');

		$result = $query->result(); 
		return $result; 
	} 

	public function get_item_values($vals="")
	{
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
            //$this->db->select('(sum(debit)-sum(credit)) as total_qty');
	        $this->db->select('path_stock_item.*,(sum(path_stock_item.debit)-sum(path_stock_item.credit)) as qty,path_stock_item.item_id, hms_stock_item_unit.unit as first_unit, hms_second_unit.unit as second_unit_name, hms_second_unit.id as s_id, path_stock_category.category, path_item.item, path_item.price, path_item.item_code, path_item.category_id, path_item.mrp, path_item.sgst, path_item.cgst, path_item.igst, path_item.discount, path_item.conversion, path_item.packing');  
	        
	         
	         $this->db->join('path_item','path_item.id=path_stock_item.item_id','left');
	          $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
	         $this->db->join('hms_stock_item_unit as hms_second_unit','hms_second_unit.id=path_item.second_unit','left');
	       
	        $this->db->where('path_item.status','1'); 
	        $this->db->order_by('path_item.item','ASC');
	        $this->db->where('path_item.is_deleted',0);
	        $this->db->group_by('path_stock_item.item_id');
	        $this->db->where('path_item.item LIKE "'.$vals.'%"');
	        $this->db->where('path_item.branch_id',$users_data['parent_id']);  
	         $this->db->join('path_stock_category`','path_stock_category.id=path_item.category_id','left');
	        $query = $this->db->get('path_stock_item');
//echo $this->db->last_query();die;
	        $result = $query->result();
  //print_r($result);die;

	        
	         $data = array();
	       
	         $unit_first=array();	
	         $dropdown=[];
	         $new_option='';
	         $unit_second=array();
	         $unit_second_id=[];
	         $unit_first_id=[];
					if(!empty($result))
					{ 

						if(!empty($result[0]->first_unit))
						{
						$unit_first['first_name']= $result[0]->first_unit;
						$unit_first['id']= $result[0]->unit_id;
						$unit_first_id[]=$unit_first;
						}

						if(!empty($result[0]->second_unit_name))
						{
						$unit_second['first_name']=$result[0]->second_unit_name;
						$unit_second['id']=$result[0]->s_id;
						$unit_second_id[]=$unit_second;
						}

						// print_r($unit_first);
						 //echo '<br>';
						 //print_r($unit_second);die;
						$dropdown[]=array_merge($unit_first_id,$unit_second_id);
						$dropdown_value= array_unique($dropdown);

						$new_option.='<option>Select Unit</option>';
						if(!empty($dropdown_value[0]) && count($dropdown_value[0])>0)
						{
                        
						foreach($dropdown_value[0] as $options)
						{
							
						if(!empty($options))
						{
						$new_option.= '<option value="'.$options['id'].'">'.$options['first_name'].'</option>';

						}


						}
						}


					}
				//	print_r($result);die;
	        
            if(!empty($result))
            {
            	foreach($result as $vals)
	        	{

                   $name = $result[0]->item.'-'.$result[0]->category.'|'.$result[0]->item_code.'|'.$result[0]->first_unit.'|'.$result[0]->price.'|'.$result[0]->item_id.'|'.$result[0]->category_id.'|'.$result[0]->qty.'|'.$new_option.'|'.$result[0]->item;
					array_push($data, $name);
	        	}
                 //print_r($data);die;
	        	echo json_encode($data);
           }
	        
	        //return $response; 
    	} 
    }


// add bottom 4 feb 20 

     public function item_list($ids="")
    {

    	$users_data = $this->session->userdata('auth_users'); 
		$this->db->select("path_item.*,path_stock_category.category, hms_stock_item_unit.unit as unit1, hms_stock_item_unit_2.unit as unit2, hms_inventory_racks.rack_no, hms_inventory_company.company_name, path_item.price as purchase_rate");

		if(!empty($ids))
		{
			$this->db->where('path_item.id IN ('.$ids.')'); 
		}
		$this->db->where('path_item.is_deleted','0');
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');

        $this->db->join('hms_inventory_company','hms_inventory_company.id=path_item.manuf_company','left');
        $this->db->join('hms_stock_item_unit as hms_stock_item_unit_2','hms_stock_item_unit_2.id = path_item.second_unit','left');

        $this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');

        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');

        $this->db->where('path_item.branch_id',$users_data['parent_id']);

        $this->db->where('path_item.is_deleted','0'); 
		$this->db->group_by('path_item.id');
	
		$query = $this->db->get('path_item');
		$result = $query->result(); 
	//echo $this->db->last_query();die;
		return $result; 
    }


    	public function item_list_search()
	{
		$users_data = $this->session->userdata('auth_users'); 
    	$post = $this->input->post();  
		$this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit as unit1, hms_stock_item_unit_2.unit as unit2, hms_inventory_racks.rack_no, hms_inventory_company.company_name, path_stock_item.type"); 

		
        $this->db->where('path_item.is_deleted','0');
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');

        $this->db->join('hms_inventory_company','hms_inventory_company.id=path_item.manuf_company','left');
        $this->db->join('hms_stock_item_unit as hms_stock_item_unit_2','hms_stock_item_unit_2.id = path_item.second_unit','left');

        $this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');

        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');

        $this->db->where('path_item.branch_id',$users_data['parent_id']);




      if(!empty($post['item']))
		{
		$this->db->where('path_item.item LIKE "'.$post['item'].'%"');
	
		}

		if(!empty($post['item_code']))
		{ 
		$this->db->where('path_item.item_code LIKE "'.$post['item_code'].'%"');	
		}
	
		if(!empty($post['unit1']))
		{  
		$this->db->where('hms_stock_item_unit.id = "'.$post['unit1'].'"');
		}

		if(!empty($post['unit2']))
		{  
		$this->db->where('hms_stock_item_unit_2.id ="'.$post['unit2'].'"');	
		}

		if(!empty($post['mrp']))
		{  
		$this->db->where('path_item.mrp = "'.$post['mrp'].'"');	
		}

		if(!empty($post['p_rate']))
		{  
		$this->db->where('path_item.price = "'.$post['p_rate'].'"');	
		}

		if(!empty($post['discount']))
		{  
		$this->db->where('path_item.discount = "'.$post['discount'].'"');
		}
	
		if(!empty($post['sgst']))
		{  
		 $this->db->where('path_item.sgst = "'.$post['sgst'].'"');	
		}
		if(!empty($post['cgst']))
		{  
		$this->db->where('path_item.cgst = "'.$post['cgst'].'"');	
		}

		if(!empty($post['igst']))
		{  
		$this->db->where('path_item.igst = "'.$post['igst'].'"');	
		}

		if(!empty($post['conversion']))
		{  
		$this->db->where('path_item.conversion = "'.$post['conversion'].'"');	
		}

		if(!empty($post['manuf_company']))
		{  
		$this->db->where('hms_inventory_company.company_name = "'.$post['manuf_company'].'"');	
		}

		if(!empty($post['packing']))
		{  
		$this->db->where('path_item.packing LIKE "'.$post['packing'].'%"');	
		}


		$this->db->where('path_item.branch_id IN ('.$users_data['parent_id'].')');
		$this->db->where('path_item.is_deleted','0'); 
		$this->db->group_by('path_item.id');
		$this->db->from('path_item');
        $query = $this->db->get(); 

        $data= $query->result();
       //print_r($data);die;
   //echo $this->db->last_query();die;
       return  $query->result();
	
	}



     public function check_unique_value($branch_id, $invoice_id, $id='')
    {
    	$this->db->select('hms_medicine_purchase.*');
		$this->db->from('hms_medicine_purchase'); 
		$this->db->where('hms_medicine_purchase.branch_id',$branch_id);
		$this->db->where('hms_medicine_purchase.id',$invoice_id);
		if(!empty($id))
		$this->db->where('hms_medicine_purchase.id !=',$id);
		$this->db->where('hms_medicine_purchase.is_deleted','0');
		$query = $this->db->get(); 
		$result=$query->row_array();
		if(!empty($result))
		{
			return 1;
		}
		else{
			return 0;
		}
    }
// add bottom 4 feb 20 


 function get_all_detail_print($ids="",$branch_id="")
	{
		$users_data = $this->session->userdata('auth_users');
		$result_pur=array();
    	$this->db->select('path_purchase_item.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.id as v_id,hms_medicine_vendors.vendor_id,hms_medicine_vendors.address as vendor_address,hms_medicine_vendors.email as vendor_email, hms_medicine_vendors.mobile as vendor_mobile');
		$this->db->from('path_purchase_item'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=path_purchase_item.vendor_id');
		$this->db->where('hms_medicine_vendors.vendor_type',3);
		$this->db->where('path_purchase_item.id',$ids);
		$this->db->where('path_purchase_item.is_deleted','0');
		$result_pur['purchases_list']= $this->db->get()->result();
//echo $this->db->last_query();die;	
		$this->db->select('path_purchase_item_to_purchase.*,path_purchase_item_to_purchase.total_amount as amount, path_item.item as item_name, path_item.item_code,path_purchase_item_to_purchase.qty as quantity, hms_stock_item_unit.unit, path_purchase_item_to_purchase.per_pic_price as item_price, path_item.id as item_id, path_item.price as purchase_amount,path_stock_category.category, path_purchase_item_to_purchase.expiry_date as exp_date');
		$this->db->from('path_purchase_item_to_purchase'); 
		$this->db->where('path_purchase_item_to_purchase.purchase_id',$ids);
		
		$this->db->where('path_purchase_item_to_purchase.branch_id',$users_data['parent_id']);
		$this->db->join('path_item','path_item.id=path_purchase_item_to_purchase.item_id','left');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_purchase_item_to_purchase.unit_id','left');
		$this->db->join('path_stock_category`','path_stock_category.id=path_purchase_item_to_purchase.category_id','left');
		$result_pur['purchases_list']['item_list']=$this->db->get()->result();
//echo $this->db->last_query();die;
		return $result_pur;
		
    }

}
 
?>