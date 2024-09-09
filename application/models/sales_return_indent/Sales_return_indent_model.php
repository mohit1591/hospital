<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_return_indent_model extends CI_Model 
{
	var $table = 'hms_indent_sale_return';
	var $column = array('hms_indent_sale_return.id','hms_indent.indent','hms_indent_sale_return.sale_no','hms_indent_sale_return.id','hms_indent_sale_return.created_date', 'hms_indent_sale_return.modified_date');  
		var $order = array('id' => 'desc'); 
 
 public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('sale_search');
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_indent_sale_return.*, hms_indent.indent");
	
		$this->db->join('hms_indent','hms_indent.id = hms_indent_sale_return.indent_id','left'); 
	
		$this->db->from($this->table);
		$this->db->where('hms_indent_sale_return.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_indent_sale_return.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_indent_sale_return.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		if(isset($search) && !empty($search))
		{
			if(isset($search['start_date'])&& !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_indent_sale_return.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date']));
				$this->db->where('hms_indent_sale_return.sale_date <= "'.$end_date.'"');
			}


			if(isset($search['indent_name']) && !empty($search['indent_name']))
			{

				$this->db->where('hms_patient.indent',$search['indent_name']);
			}

			

			if(isset($search['sale_no']) && !empty($search['sale_no']))
			{
				$this->db->where('hms_indent_sale_return.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left');	

				}
				$this->db->where('hms_indent_sale_return_indent.discount = "'.$search['discount'].'"');
			}

			
			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) )
				{
				$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left');


			}
				$this->db->where('hms_indent_sale_return_indent.batch_no = "'.$search['batch_no'].'"');
			}
			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']))
				{	
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']))
				{	
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) )
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['igst']) && !empty($search['igst']))
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_indent_sale_return_indent.igst', $search['igst']);
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']))
				{	
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_indent_sale_return_indent.cgst', $search['cgst']);
			}

			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']) && empty($search['cgst']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_indent_sale_return_indent.sgst', $search['sgst']);
			}

			
			}else{

		}
		
		$emp_ids='';
		if($user_data['emp_id']>0)
		{
			if($user_data['record_access']=='1')
			{
				$emp_ids= $user_data['id'];
			}
		}
		elseif(!empty($search["employee"]) && is_numeric($search['employee']))
		{
			$emp_ids=  $search["employee"];
		}
		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('hms_indent_sale_return.created_by IN ('.$emp_ids.')');
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

	function search_report_data(){
		$search = $this->session->userdata('sale_search');
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_indent_sale_return.*, hms_indent.indent"); 

		$this->db->join('hms_indent','hms_indent.id = hms_indent_sale_return.indent_id','left'); 
		
		$this->db->from($this->table);
		$this->db->where('hms_indent_sale_return.is_deleted','0'); 
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_indent_sale_return.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_indent_sale_return.branch_id = "'.$user_data['parent_id'].'"');
		}
        if(isset($search) && !empty($search))
		{
			if(isset($search['start_date'])&& !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_indent_sale_return.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_indent_sale_return.sale_date <= "'.$end_date.'"');
			}


			if(isset($search['indent_name']) && !empty($search['indent_name']))
			{

				$this->db->where('hms_indent.indent',$search['indent']);
			}
		
			

			if(isset($search['sale_no']) && !empty($search['sale_no']))
			{
				$this->db->where('hms_indent_sale_return.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left');	

				}
				$this->db->where('hms_indent_sale_return_indent.discount = "'.$search['discount'].'"');
			}

			
			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) )
				{
				$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left');


			}
				$this->db->where('hms_indent_sale_return_indent.batch_no = "'.$search['batch_no'].'"');
			}
			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']))
				{	
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']))
				{	
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) )
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['igst']) && !empty($search['igst']))
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_indent_sale_return_indent.igst', $search['igst']);
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']))
				{	
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_indent_sale_return_indent.cgst', $search['cgst']);
			}

			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']) && empty($search['cgst']))
				{
					$this->db->join('hms_indent_sale_return_indent','hms_indent_sale_return_indent.sales_return_id = hms_indent_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id','left'); 
                }
				$this->db->where('hms_indent_sale_return_indent.sgst', $search['sgst']);
			}


		

		}else{

		}
		
		$emp_ids='';
		if($user_data['emp_id']>0)
		{
			if($user_data['record_access']=='1')
			{
				$emp_ids= $user_data['id'];
			}
		}
		elseif(!empty($search["employee"]) && is_numeric($search['employee']))
		{
			$emp_ids=  $search["employee"];
		}
		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('hms_indent_sale_return.created_by IN ('.$emp_ids.')');
		}
		$query = $this->db->get(); 

		$data= $query->result();
		
		return $data;
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
		$this->db->select('hms_indent_sale_return.*,hms_indent.indent');
		$this->db->join('hms_indent', 'hms_indent.id=hms_indent_sale_return.indent_id');
		$this->db->from('hms_indent_sale_return'); 
		 $this->db->where('hms_indent_sale_return.id',$id);
		$this->db->where('hms_indent_sale_return.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function get_medicine_by_sales_id($id="",$tamt="")
	{
		$this->db->select('hms_indent_sale_return_indent.*');
		$this->db->from('hms_indent_sale_return_indent'); 
		if(!empty($id))
		{
          $this->db->where('hms_indent_sale_return_indent.sales_return_id',$id);
		} 
		$query = $this->db->get()->result(); 
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $medicine)	
		  { 
                 $result[$medicine->medicine_id.'.'.$medicine->batch_no] = array('mid'=>$medicine->medicine_id, 'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'batch_no'=>$medicine->batch_no,'igst'=>$medicine->igst,'hsn_no'=>$medicine->hsn_no,'cgst'=>$medicine->cgst,'sgst'=>$medicine->sgst,'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->actual_amount,'bar_code'=>$medicine->bar_code,'total_amount'=>$medicine->total_amount,'total_pricewith_medicine'=>$tamt); 
		  } 
		} 
		
		return $result;
	}


	public function save()
	{ 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
	
	
		if(!empty($post['data_id']) && $post['data_id']>0)
		{  

            $created_by_id= $this->get_by_id($post['data_id']);
			
            $purchase_detail= $this->get_by_id($post['data_id']);
		 
          
            $blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$data = array(
				"indent_id"=>$post['indent_id'],
				'branch_id'=>$user_data['parent_id'],
				'sale_no'=>$post['sales_no'], 
				'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
				"remarks"=>$post['remarks']
			); 
			$this->db->where('id',$post['data_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->update('hms_indent_sale_return',$data);
        
                $sales_id= $post['data_id'];

			$sess_medicine_list= $this->session->userdata('medicine_id');
			$this->db->where('sales_return_id',$post['data_id']);
            $this->db->delete('hms_indent_sale_return_indent');

             $this->db->where(array('parent_id'=>$post['data_id'],'type'=>4));
            $this->db->delete('hms_medicine_stock');
            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{
					$data_purchase_topurchase = array(
					"sales_return_id"=>$post['data_id'],
					'medicine_id'=>$medicine_list['mid'],
					'qty'=>$medicine_list['qty'],
					'discount'=>$medicine_list['discount'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'sgst'=>$medicine_list['sgst'],
					'hsn_no'=>$medicine_list['hsn_no'],
					'bar_code'=>$medicine_list['bar_code'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'batch_no'=>$medicine_list['batch_no'],
					'per_pic_price'=>$medicine_list['per_pic_amount'],
					'conversion'=>$medicine_list['conversion'],
					'actual_amount'=>$medicine_list['sale_amount'],
					'modified_by'=>$user_data['id']
					);
					$this->db->insert('hms_indent_sale_return_indent',$data_purchase_topurchase);
				 
				  $data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>7,
					    	"parent_id"=>$post['data_id'],
					    	"m_id"=>$medicine_list['mid'],
					    	"credit"=>0,
					    	"debit"=>$medicine_list['qty'],
					    	"created_by"=>$user_data['id'],
							"batch_no"=>$medicine_list['batch_no'],
							"conversion"=>$medicine_list['conversion'],
							"mrp"=>$medicine_list['sale_amount'],
							"discount"=>$medicine_list['discount'],
							'igst'=>$medicine_list['igst'],
							'cgst'=>$medicine_list['cgst'],
							'sgst'=>$medicine_list['sgst'],
							'hsn_no'=>$medicine_list['hsn_no'],
							'bar_code'=>$medicine_list['bar_code'],
							"total_amount"=>$medicine_list['total_amount'],
							"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
							'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
							'per_pic_rate'=>$medicine_list['per_pic_amount'],
					    	"created_date"=>date('Y-m-d',strtotime($post['sales_date']))
					    	);
						 $this->db->insert('hms_medicine_stock',$data_new_stock);

				}
            } 

  
            
		}
		else{
			$sale_no = generate_unique_id(52);
			$data = array(
				'indent_id'=>$post['indent_id'],
				'branch_id'=>$user_data['parent_id'],
				'sale_no'=>$post['sales_no'],
				'return_no'=>$sale_no,
				'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),				
				'remarks'=>$post['remarks'],
				'created_by'=>$user_data['id'],
				'created_date'=>date('Y-m-d H:i:s')		
			); 
			$this->db->insert('hms_indent_sale_return',$data);
			$sales_id= $this->db->insert_id();
	

			$sess_medicine_list= $this->session->userdata('medicine_id'); 
            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{
					$data_purchase_topurchase = array(
					"sales_return_id"=>$sales_id,
					'medicine_id'=>$medicine_list['mid'],
					'qty'=>$medicine_list['qty'],
					'discount'=>$medicine_list['discount'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'sgst'=>$medicine_list['sgst'],
					'hsn_no'=>$medicine_list['hsn_no'],
					'bar_code'=>$medicine_list['bar_code'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'batch_no'=>$medicine_list['batch_no'],
					'per_pic_price'=>$medicine_list['per_pic_amount'],
					'conversion'=>$medicine_list['conversion'],
					'actual_amount'=>$medicine_list['sale_amount'],
					'created_by'=>$user_data['id'],
					'created_date'=>date('Y-m-d H:i:s'),
					);
					$this->db->insert('hms_indent_sale_return_indent',$data_purchase_topurchase);
				
                  $data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>7,
					    	"parent_id"=>$sales_id,
					    	"m_id"=>$medicine_list['mid'],
					    	"credit"=>0,
					    	"debit"=>$medicine_list['qty'],
					    	"created_by"=>$user_data['id'],
							"batch_no"=>$medicine_list['batch_no'],
							"conversion"=>$medicine_list['conversion'],
							"mrp"=>$medicine_list['sale_amount'],
							"discount"=>$medicine_list['discount'],
							'igst'=>$medicine_list['igst'],
							'cgst'=>$medicine_list['cgst'],
							'sgst'=>$medicine_list['sgst'],
							'hsn_no'=>$medicine_list['hsn_no'],
							'bar_code'=>$medicine_list['bar_code'],
							"total_amount"=>$medicine_list['total_amount'],
							"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
							'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
							'per_pic_rate'=>$medicine_list['per_pic_amount'],
					    	"created_date"=>date('Y-m-d',strtotime($post['sales_date']))
					    	);
						 $this->db->insert('hms_medicine_stock',$data_new_stock);

				}
            } 
		}
		$this->session->unset_userdata('medicine_id');
		return $sales_id;	 	
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
			$this->db->update('hms_indent_sale_return');
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
			$this->db->update('hms_indent_sale_return');
    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_sale');
        $result = $query->result(); 
        return $result; 
    } 

   public function medicine_list($ids="",$batch_no="",$type="")
    {
    	 
    	$users_data = $this->session->userdata('auth_users'); 
    	$medicine_list = $this->session->userdata('medicine_id');
		$this->db->select('hms_medicine_entry.*,hms_medicine_sale_to_medicine.bar_code,hms_medicine_sale_to_medicine.hsn_no as hsn,hms_medicine_sale_to_medicine.per_pic_price as per_price,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, hms_medicine_sale_to_medicine.batch_no,hms_medicine_sale_to_medicine.expiry_date,hms_medicine_sale_to_medicine.manuf_date,hms_medicine_sale_to_medicine.qty');
		$this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.medicine_id = hms_medicine_entry.id'); 
		
		/*if(!empty($ids))
		{
			$this->db->where('hms_medicine_entry.id  IN ('.$ids.')'); 
		}
		if(!empty($batch_no))
		{
			$this->db->where('hms_medicine_sale_to_medicine.batch_no  IN ('.$batch_no.')'); 
		}*/


		/*if(!empty($medicine_list))
        {
          $added_clouse = '';	
          $total_clouse = count($medicine_list);
          $i=1;
          foreach($medicine_list as $medicine_added)
          { 
            $added_clouse .= ' (hms_medicine_entry.id = "'.$medicine_added['mid'].'" AND hms_medicine_sale_to_medicine.batch_no = "'.$medicine_added['batch_no'].'")';
            if($i!=$total_clouse)
            {
            	$added_clouse .= ' OR ';
            }
            $i++;
          }
          $this->db->where($added_clouse); 
        }*/

        if(!empty($ids))
		{
			$this->db->where('hms_medicine_entry.id  IN ('.$ids.')'); 
		}
		if(!empty($batch_no))
		{
			if(!empty($type))
			{
              $this->db->where('hms_medicine_sale_to_medicine.batch_no IN ('.$batch_no.')'); 
			}
			else
			{
              $this->db->where('hms_medicine_sale_to_medicine.batch_no IN ("'.$batch_no.'")'); 
			} 
		}


		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');

		$this->db->where('hms_medicine_entry.is_deleted','0');  
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_medicine_sale_to_medicine.batch_no');  
		$query = $this->db->get('hms_medicine_entry');
		$result = $query->result();
		
		//echo $this->db->last_query();die;
	    return $result;
    }
	 public function doctor_list()
	    {
	    $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (0,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 

		//echo $this->db->last_query(); 
		return $result;
	    }
 

    public function medicine_list_search()
    {
    	$post = $this->input->post();  
        $users_data = $this->session->userdata('auth_users'); 
        $added_medicine_list = $this->session->userdata('medicine_id');
    	$added_medicine_ids = [];
    	$added_medicine_batch = [];
		/*$this->db->select('hms_medicine_entry.*,hms_medicine_sale.id as s_id,hms_medicine_sale_to_medicine.id as s_to_sid,hms_medicine_entry.created_date as create, hms_medicine_company.company_name,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2,(SELECT (sum(credit)-sum(debit)) from hms_medicine_stock where m_id = hms_medicine_entry.id and type=3) as avl_qty,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id) as stock_quantity');  
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		$this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.medicine_id = hms_medicine_entry.id');
		$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id');
		$this->db->group_by('hms_medicine_entry.id');*/

		$this->db->select('hms_medicine_sale_to_medicine.*,hms_medicine_sale_to_medicine.id as s_id,,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_entry.mrp,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,hms_medicine_entry.min_alrt,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_sale_to_medicine.batch_no = batch_no) as stock_quantity,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_sale_to_medicine.batch_no = batch_no) as qty, hms_medicine_sale_to_medicine.batch_no');  
		//$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id');


		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_sale_to_medicine.batch_no = batch_no)>0');
		


		if(!empty($post['medicine_name']))
		{
			
			//echo 'fsf';die;
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$post['medicine_name'].'%"');
			
		}

		if(!empty($post['medicine_code']))
		{  

			$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$post['medicine_code'].'%"');
			
		}
	
		
		if(!empty($post['qty']))
		{  
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)<='.$post['qty'].' as sqty');
			
		}
		if(!empty($post['rate']))
		{  
			$this->db->where('hms_medicine_entry.mrp = "'.$post['rate'].'"');
			
		}
		if(!empty($post['discount']))
		{  
			//echo 'fsf';die;
			$this->db->where('hms_medicine_entry.discount = "'.$post['discount'].'"');
			
		}
		if(!empty($post['cgst']))
		{  
			$this->db->where('hms_medicine_entry.cgst = "'.$post['cgst'].'"');
			
		}
		if(!empty($post['sgst']))
		{  
			$this->db->where('hms_medicine_entry.sgst = "'.$post['sgst'].'"');
			
		}
		if(!empty($post['igst']))
		{  
			$this->db->where('hms_medicine_entry.igst = "'.$post['igst'].'"');
			
		}
		if(!empty($post['hsn_no']))
		{  
			$this->db->where('hms_medicine_entry.hsn_no = "'.$post['hsn_no'].'"');
			
		}
		if(!empty($post['batch_number']))
		{  
			$this->db->where('hms_medicine_sale_to_medicine.batch_no = "'.$post['batch_number'].'"');
			
		}
		if(!empty($post['bar_code']))
		{  
			$this->db->where('hms_medicine_sale_to_medicine.bar_code LIKE "'.$post['bar_code'].'%"');
			
		}
		if(!empty($post['stock']))
		{  
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)<='.$post['stock'].'');
			
		}
	
		if(!empty($post['packing']))
		{  
			$this->db->where('hms_medicine_entry.packing LIKE "'.$post['packing'].'"');
			
		}
		
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
		if(!empty($post['medicine_company']))
		{  
			$this->db->where('hms_medicine_company.company_name LIKE"'.$post['medicine_company'].'%"');

			
		}

		if(isset($added_medicine_list) && !empty($added_medicine_list))
		{ 
			$arr_mids = [];
			if(!empty($added_medicine_list))
			{ 
			foreach($added_medicine_list as $medicine_data)
			{
			$arr_mids[] = "'".$medicine_data['mid'].".".$medicine_data['batch_no']."'";
			}
            $keys = implode(',', $arr_mids);
			} 
           
			$this->db->where('CONCAT(hms_medicine_sale_to_medicine.medicine_id,".",hms_medicine_sale_to_medicine.batch_no) NOT IN ('.$keys.') ');
		} 
		  
		$this->db->where('hms_medicine_entry.is_deleted','0');  
        $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_medicine_sale_to_medicine.medicine_id');
		$this->db->group_by('hms_medicine_sale_to_medicine.batch_no');
        $this->db->from('hms_medicine_sale_to_medicine');
       
        $query = $this->db->get(); 
        //print'<pre>';print_r($query->result());die;
        //echo $this->db->last_query();die;
        return $query->result();
        // $data= $query->result();
       // print'<pre>';print_r($data);die;
	
    }
function get_all_detail_print($ids="",$branch_ids="")
{
    	$result_sales=array();
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_indent_sale_return.*,hms_indent.indent"); 
		$this->db->join('hms_indent','hms_indent.id = hms_indent_sale_return.indent_id','left');
	
		$this->db->where('hms_indent_sale_return.is_deleted','0'); 
		
		$this->db->where('hms_indent_sale_return.branch_id = "'.$branch_ids.'"');
		
		$this->db->where('hms_indent_sale_return.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['sales_list']= $this->db->get()->result();
		//echo $this->db->last_query(); exit;
		$this->db->select('hms_indent_sale_return_indent.*,hms_indent_sale_return_indent.discount as m_discount,hms_medicine_entry.*,hms_indent_sale_return_indent.sgst as m_sgst,hms_indent_sale_return_indent.igst as m_igst,hms_indent_sale_return_indent.cgst as m_cgst,hms_indent_sale_return_indent.batch_no as m_batch_no,hms_indent_sale_return_indent.expiry_date as m_expiry_date,hms_indent_sale_return_indent.hsn_no as m_hsn_no');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_return_indent.medicine_id'); 
		$this->db->where('hms_indent_sale_return_indent.sales_return_id = "'.$ids.'"');
		$this->db->from('hms_indent_sale_return_indent');
		$result_sales['sales_list']['medicine_list']=$this->db->get()->result();
		
		return $result_sales;
		
    }

    function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	//$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	return $query;

    }

    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
    {
    	 $users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
	
    	$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
    	$this->db->where('hms_payment_mode_field_value_acc_section.type',4);
    	$this->db->where('hms_payment_mode_field_value_acc_section.section_id',1);
    	$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
    	return $query;
    }
    
    public function search_sales($vals="")
    {
      $response = '';
      if(!empty($vals))
      {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_medicine_sale.*,hms_patient.patient_name,hms_patient.patient_email as email,hms_patient.id as patient_id,hms_patient.mobile_no,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_patient.gender"); 
		$this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
		$this->db->where('hms_patient.is_deleted','0'); 
		$this->db->where('hms_medicine_sale.is_deleted','0'); 

		$this->db->where('hms_medicine_sale.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->from('hms_medicine_sale'); 
		$this->db->where('hms_medicine_sale.sale_no LIKE "'.$vals.'%"');
		$query = $this->db->get(); 
		$result = $query->result();

		$saledate = explode(' ', $result[0]->sale_date);
        $days=get_setting_value('Set_Return_Medicine_Minimum_Days');
        $expire=date('Y-m-d', strtotime($saledate[0]. ' + '.$days.' days'));
       
        $curDate = date('Y-m-d');
		$curDate=date('Y-m-d', strtotime($curDate));
		$saleDates = date('Y-m-d', strtotime($saledate[0]));
		$saleEnd = date('Y-m-d', strtotime($expire));

      if(!empty($result) && ($curDate >= $saleDates) && ($curDate <= $saleEnd))
      { 
        $data = array();
        foreach($result as $vals)
        {
            $name = $vals->sale_no.'|'.$vals->id.'|'.$vals->referred_by.'|'.$vals->referral_hospital.'|'.$vals->ref_by_other_doctor.'|'.$vals->ref_by_other_hospital.'|'.$vals->refered_id.'|'.$vals->ref_by_other.'|'.$vals->total_amount.'|'.$vals->net_amount.'|'.$vals->paid_amount.'|'.$vals->balance.'|'.$vals->remarks.'|'.$vals->discount.'|'.$vals->sgst.'|'.$vals->cgst.'|'.$vals->igst.'|'.$vals->discount_percent.'|'.$vals->vat_percent.'|'.$vals->payment_mode.'|'.$vals->simulation_id.'|'.$vals->patient_name.'|'.$vals->patient_id.'|'.$vals->mobile_no.'|'.$vals->email.'|'.$vals->relation_type.'|'.$vals->relation_simulation_id.'|'.$vals->relation_name.'|'.$vals->gender;
            array_push($data, $name);
        }

        echo json_encode($data);
      }
      else{
      	echo json_encode(array('status'=>0, 'message'=>'medicine not return'));
      }
      //return $response; 
  } 
}

 /* Letter head */
  function letterhead_template_format(){
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_print_letter_head_template_setting.*');
      $this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
      $this->db->where('unique_id',5); 
      $this->db->from('hms_print_letter_head_template_setting');
      $query=$this->db->get()->row();
      //print_r($query);exit;
      return $query;
    }
  /*Letter head */

} 
?>