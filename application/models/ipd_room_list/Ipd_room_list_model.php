<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_room_list_model extends CI_Model {

	var $table = 'hms_ipd_rooms';
	var $column = array('hms_ipd_rooms.id','hms_ipd_room_category.room_category','hms_ipd_rooms.room_no','hms_ipd_rooms.created_date','hms_ipd_rooms.modified_date');    
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $search = $this->session->userdata('room_list');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_ipd_rooms.*,hms_ipd_room_category.room_category,hms_ipd_room_category.id as room_category_id"); 
		//$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.room_id=hms_ipd_rooms.id','right');
      
		$this->db->from($this->table); 
		$this->db->join('hms_ipd_room_category','hms_ipd_rooms.room_type_id=hms_ipd_room_category.id','left');
	
        $this->db->where('hms_ipd_room_category.is_deleted','0');
$this->db->where('hms_ipd_room_category.status','1');
        $this->db->where('hms_ipd_rooms.is_deleted','0');
        $this->db->where('(hms_ipd_rooms.branch_id='.$users_data['parent_id'].' and hms_ipd_room_category.branch_id='.$users_data['parent_id'].')');
	    $i = 0;
	    if(isset($search) && !empty($search))
		{
			if(!empty($search['room_type']))
			{
				$this->db->where('hms_ipd_room_category.room_category',$search['room_type']);
			}
		}
	
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

	function search_report_data(){
		$users_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('room_list');

		$this->db->select("hms_ipd_rooms.*,hms_ipd_room_category.room_category,hms_ipd_room_category.id as room_category_id"); 
		$this->db->from($this->table); 
		//$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.room_id=hms_ipd_rooms.id','left');
        
		$this->db->join('hms_ipd_room_category','hms_ipd_rooms.room_type_id=hms_ipd_room_category.id','left');
	
        $this->db->where('hms_ipd_room_category.is_deleted','0');
        $this->db->where('hms_ipd_rooms.is_deleted','0');
        $this->db->where('(hms_ipd_rooms.branch_id='.$users_data['parent_id'].' and hms_ipd_room_category.branch_id='.$users_data['parent_id'].')');
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['room_type']))
			{
				$this->db->where('hms_ipd_room_category.room_category',$search['room_type']);
			}
		}
	    $query = $this->db->get(); 

		$data= $query->result();
		
		return $data;
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
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
	public function count_bad($room_id=""){
		$this->db->select('count(hms_ipd_room_to_bad.bad_no) as total_bad');
		$this->db->from('hms_ipd_room_to_bad');
		$this->db->where('room_id',$room_id);
		$result= $this->db->get()->result();
		return $result;
	}

	public function get_all_beds_counts($type='',$room_type_id='',$room_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('count(hms_ipd_room_to_bad.bad_no) as total_bad');
		$this->db->from('hms_ipd_room_to_bad');
		$this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_room_to_bad.room_id AND hms_ipd_rooms.is_deleted=0','left');
		
		
		if(!empty($room_type_id))
		{
			$this->db->where('hms_ipd_room_to_bad.room_type_id',$room_type_id);	
		}

		
		if(!empty($room_id))
		{
			$this->db->where('hms_ipd_room_to_bad.room_id',$room_id);	
		}
		
		if(!empty($type) && $type==1)
		{
			$this->db->where('hms_ipd_room_to_bad.status',1);
		}
		if(!empty($type) && $type==2)
		{
			$this->db->where('hms_ipd_room_to_bad.status',0);
		}
		$this->db->where('hms_ipd_rooms.is_deleted',0);
		$this->db->where('hms_ipd_room_to_bad.branch_id',$user_data['parent_id']);
		$result= $this->db->get()->result();
		//echo $this->db->last_query();
		return $result;
	}
    
    public function simulation_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('simulation','ASC'); 
    	$query = $this->db->get('hms_ipd_rooms');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ipd_rooms.*,hms_ipd_room_category.room_category,count(hms_ipd_room_to_bad.bad_no) as total_bad"); 
		$this->db->from($this->table); 
		$this->db->join('hms_ipd_room_category','hms_ipd_rooms.room_type_id=hms_ipd_room_category.id','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.room_id=hms_ipd_rooms.id','left');
        $this->db->where('hms_ipd_room_category.is_deleted','0');
         $this->db->where('hms_ipd_rooms.is_deleted','0');
        $this->db->where('hms_ipd_rooms.id',$id);
        $this->db->where('(hms_ipd_rooms.branch_id='.$users_data['parent_id'].' and hms_ipd_room_category.branch_id='.$users_data['parent_id'].')');
		$query = $this->db->get(); 
		return $query->row_array();
	}

	function get_charges_according($room_id="",$room_charge_id="",$panel_company_id='',$types='')
	{
		$data=array();
       $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_ipd_room_charge.*');
    	$this->db->join('hms_ipd_room_charge_type','hms_ipd_room_charge_type.id=hms_ipd_room_charge.room_charge_type_id');
    	$this->db->where('hms_ipd_room_charge.branch_id',$user_data['parent_id']);
    	$this->db->where('hms_ipd_room_charge_type.status',1);
    	//$this->db->where('hms_ipd_room_charge.types',0);
    	$this->db->where('hms_ipd_room_charge.room_id',$room_id);
    	$this->db->where('hms_ipd_room_charge.room_charge_type_id',$room_charge_id);
    	if(!empty($panel_company_id))
    	{
    		$this->db->where('hms_ipd_room_charge.panel_company_id',$panel_company_id);
    	}
    	if(!empty($types) && $types=='1')
    	{
    		$this->db->where('hms_ipd_room_charge.types',0);
    	}
    	if(!empty($types) && $types=='2')
    	{
    		$this->db->where('hms_ipd_room_charge.types',1);
    	}
    	$query = $this->db->get('hms_ipd_room_charge')->result();
    	//echo $this->db->last_query();die;
    	if(count($query)>0)
    	{
    		//echo $query[0]->room_charge;die;
    		$data['charge']= $query[0]->room_charge;
    		$data['code']= substr($query[0]->charge_code,2,6);
    	}

    	//print_r($data);die;
    	return $data;
    	//print_r($query[0]->room_charge);die;
		
	}
	
	public function save()
	{

		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$get_old_ipd_room_cha= $this->get_old_ipd_room_charge($post['data_id']); 

		/*echo "<pre>"; print_r($post['charges']);
		echo "<br>eerrrrr";*/
		


		//echo "<pre>"; print_r($get_old_ipd_room_cha); exit;
		$charges_keys = array_keys($post['charges']);
	  
		$room_no = $post['room_no'];
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'room_type_id'=>$post['room_category_id'],
				    'room_no'=>$room_no,
				    'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
		
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ipd_rooms',$data); 
			
		//new code 
		if(!empty($post['charges']))
		{		

				$old_total_rm_charges = count($get_old_ipd_room_cha);
				$new_total_rm_charges = count($post['charges']);
				if($old_total_rm_charges<=$new_total_rm_charges)
				{
					if(!empty($post['charges']))
					{
						
						//echo "<pre>"; print_r($post['charges']); exit;
		            	foreach($post['charges'] as $key=>$charges)
		            	{
		            		$room_charge_for_row = $this->get_old_ipd_room_charge($post['data_id'],$post['room_category_id'],$key);
		            		//echo "<pre>"; print_r($room_charge_for_row); exit;
		            		if(!empty($room_charge_for_row))
		            		{
				                   $ipd_room_charge = array(
						            	'branch_id'=>$user_data['parent_id'],
						            	'types'=>0,
						            	'room_type_id'=>$post['room_category_id'],
						            	'room_id'=>$post['data_id'],
						            	'room_no'=>$room_no,
						            	'charge_code'=>'10'.$charges[0],
						            	'room_charge_type_id'=>$key,
						            	'room_charge'=>$charges[1],
						            	'panel_type_id'=>0,
						            	'panel_company_id'=>0

						            );

				                    $this->db->where('id',$room_charge_for_row[0]->id);
									$this->db->update('hms_ipd_room_charge',$ipd_room_charge);
									//echo $this->db->last_query(); //die;
			            	}
			            	else
			            	{
			            		$ipd_room_charge = array(
						            	'branch_id'=>$user_data['parent_id'],
						            	'types'=>0,
						            	'room_type_id'=>$post['room_category_id'],
						            	'room_id'=>$post['data_id'],
						            	'room_no'=>$room_no,
						            	'charge_code'=>'10'.$charges[0],
						            	'room_charge_type_id'=>$key,
						            	'room_charge'=>$charges[1],
						            	'panel_type_id'=>0,
						            	'panel_company_id'=>0

						            );

				                    $this->db->insert('hms_ipd_room_charge',$ipd_room_charge);
			            	}
			            	//die;
		            	
		            }

				}
			}
			else
			{	
					if(!empty($post['charges']))
					{
							foreach($post['charges'] as $key=>$charges)
			            	{
			            		$room_charge_for_row = $this->get_old_ipd_room_charge($post['data_id'],$post['room_category_id'],$key);
			            		//echo "<pre>"; print_r($room_charge_for_row); exit;
			            		if(!empty($room_charge_for_row))
			            		{
					                   $ipd_room_charge = array(
							            	'branch_id'=>$user_data['parent_id'],
							            	'types'=>0,
							            	'room_type_id'=>$post['room_category_id'],
							            	'room_id'=>$post['data_id'],
							            	'room_no'=>$room_no,
							            	'charge_code'=>'10'.$charges[0],
							            	'room_charge_type_id'=>$key,
							            	'room_charge'=>$charges[1],
							            	'panel_type_id'=>0,
							            	'panel_company_id'=>0

							            );

					                    $this->db->where('id',$room_charge_for_row[0]->id);
										$this->db->update('hms_ipd_room_charge',$ipd_room_charge); 

						            
				            	}
				            	else
				            	{
				            		
				            		$where_charge=array('room_id'=>$post['data_id'],'room_charge_type_id'=>$key,'room_type_id'=>$post['room_category_id']);
   									$this->db->delete('hms_ipd_room_charge',$where_charge);

				            		
				            	}
				            	
			            	
			            }
			        }
			}
				

			//exit;
            	
				/*
				working but not for new charges
				if(!empty($get_old_ipd_room_cha))
				{
					foreach ($get_old_ipd_room_cha as $get_old_ipd) 
					{
						
						$charges_olds = $post['charges'][$get_old_ipd->room_charge_type_id];
						$ipd_new_room_charge = array(
			            	'branch_id'=>$user_data['parent_id'],
			            	'types'=>0,
			            	'room_type_id'=>$post['room_category_id'],
			            	'room_id'=>$post['data_id'],
			            	'room_no'=>$get_old_ipd->room_no,
			            	'charge_code'=>'10'.$charges_olds[0],
			            	'room_charge_type_id'=>$get_old_ipd->room_charge_type_id,
			            	'room_charge'=>$charges_olds[1],
			            	'panel_type_id'=>0,
			            	'panel_company_id'=>0

		            	);
						$this->db->where('id',$get_old_ipd->id);
						$this->db->update('hms_ipd_room_charge',$ipd_new_room_charge); 
						
					}
				}*/

			
			$get_old_ipd_room_bed= $this->get_old_ipd_room_bed($post['data_id']);
			$total_added_bed = count($get_old_ipd_room_bed); //die; exit;
			$count = 0;
			//this condition is for adding more in existing room else of it will remove and update the room
			if($total_added_bed<=$post['total_bad'])
			{
				for($j=0;$j<$post['total_bad'];$j++)
		            {
		            	$count = $count+1;
		            	$bed_no[$j] =$count;

		            	$get_old_ipd_room_bed= $this->get_old_ipd_room_bed($post['data_id'],$count); 
		            	if(!empty($get_old_ipd_room_bed))
		            	{
		            		$bed_ids = $get_old_ipd_room_bed[0]->id;
		            		$room_no = $get_old_ipd_room_bed[0]->room_no;
		            		$bad_no = $get_old_ipd_room_bed[0]->bad_no;
		            		$bad_name = $get_old_ipd_room_bed[0]->bad_name;
		            		$status = $get_old_ipd_room_bed[0]->status;
		            		$ipd_id = $get_old_ipd_room_bed[0]->ipd_id;
						    
						    $data[$j] = array(
				                'branch_id'=>$user_data['parent_id'],
				                'room_id'=>$post['data_id'],
				                'room_no'=>$room_no,
				                'bad_no'=>$bad_no,
				                'bad_name'=>$bad_name,
				                'status'=>$status,
				                'ipd_id'=>$ipd_id,
				                'room_type_id'=>$post['room_category_id'],
				                'created_by'=>$user_data['id'],
				                'created_date'=>date('Y-m-d H:i:s'),
				            );
				            $this->db->where('id',$bed_ids);
							$this->db->update('hms_ipd_room_to_bad',$data[$j]);
						}
		            	else
		            	{
		            		///insert new bed
		            		$data[$j] = array(
				                'branch_id'=>$user_data['parent_id'],
				                'room_id'=>$post['data_id'],
				                'room_no'=>$room_no,
				                'bad_no'=>$bed_no[$j],
				                'bad_name'=>$bed_no[$j],
				                'room_type_id'=>$post['room_category_id'],
				                'created_by'=>$user_data['id'],
				                'created_date'=>date('Y-m-d H:i:s'),
				            );
				            $this->db->insert('hms_ipd_room_to_bad',$data[$j]);
		            	}
		            	
		            	
		            
			            
			        }
			}
			else
			{ 	
				//for remove bed no  if no of bed is less than the old added no of bed
				for($j=0;$j<$total_added_bed;$j++)
	            {
	            	$count = $count+1;
	            	$bed_no[$j] =$count;
	            	if($count<=$post['total_bad'])
	            	{
		            	$get_old_ipd_room_bed= $this->get_old_ipd_room_bed($post['data_id'],$count); 
		            	if(!empty($get_old_ipd_room_bed))
		            	{
		            		
		            		$bed_ids = $get_old_ipd_room_bed[0]->id;
		            		$room_no = $get_old_ipd_room_bed[0]->room_no;
		            		$bad_no = $get_old_ipd_room_bed[0]->bad_no;
		            		$bad_name = $get_old_ipd_room_bed[0]->bad_name;
		            		$status = $get_old_ipd_room_bed[0]->status;
		            		$ipd_id = $get_old_ipd_room_bed[0]->ipd_id;
						    
						    $data[$j] = array(
				                'branch_id'=>$user_data['parent_id'],
				                'room_id'=>$post['data_id'],
				                'room_no'=>$room_no,
				                'bad_no'=>$bad_no,
				                'bad_name'=>$bad_name,
				                'status'=>$status,
				                'ipd_id'=>$ipd_id,
				                'room_type_id'=>$post['room_category_id'],
				                'created_by'=>$user_data['id'],
				                'created_date'=>date('Y-m-d H:i:s'),
				            );
				            $this->db->where('id',$bed_ids);
							$this->db->update('hms_ipd_room_to_bad',$data[$j]);
						}
	            	
	            	}
	            	else
	            	{
	            		$where_room_bad_charge=array('room_id'=>$post['data_id'],'bad_no'=>$bed_no[$j]);
   						$this->db->delete('hms_ipd_room_to_bad',$where_room_bad_charge);
					}
	            }


			}

             

				


				/*die;

            	foreach($post['charges'] as $key=>$charges)
            	{
            		
                   $ipd_room_charge = array(
		            	'branch_id'=>$user_data['parent_id'],
		            	'types'=>0,
		            	'room_type_id'=>$post['room_category_id'],
		            	'room_id'=>$post['data_id'],
		            	'room_no'=>$room_no,
		            	'charge_code'=>'10'.$charges[0],
		            	'room_charge_type_id'=>$key,
		            	'room_charge'=>$charges[1],
		            	'panel_type_id'=>0,
		            	'panel_company_id'=>0

		            );
		            $this->db->insert('hms_ipd_room_charge',$ipd_room_charge);
		            
		           //echo $this->db->last_query();
            	}*/
            	//die;
            	
            }
			
			

		}
		else
		{    
			
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ipd_rooms',$data);
            $room_id = $this->db->insert_id();


            /////entry in hms_ipd_room_charges////////////////// 
            if(!empty($post['charges']))
            {
            	foreach($post['charges'] as $key=>$charges)
            	{
                   $ipd_room_charge = array(
		            	'branch_id'=>$user_data['parent_id'],
		            	'types'=>0,
		            	'room_type_id'=>$post['room_category_id'],
		            	'room_id'=>$room_id,
		            	'room_no'=>$room_no,
		            	'room_charge_type_id'=>$key,
		            	'charge_code'=>'10'.$charges[0],
		            	'room_charge'=>$charges[1],
		            	'panel_type_id'=>0,
		            	'panel_company_id'=>0

		            );
		            $this->db->insert('hms_ipd_room_charge',$ipd_room_charge);
            	}
            }

            $get_all_comapny= $this->get_panel_list();
            foreach($get_all_comapny as $panel_comapny){

            	foreach($post['charges'] as $key=>$charges)
            	{
            		
                   $ipd_room_charge = array(
		            	'branch_id'=>$user_data['parent_id'],
		            	'types'=>1,
		            	'room_type_id'=>$post['room_category_id'],
		            	'room_id'=>$room_id,
		            	'room_no'=>$room_no,
		            	'charge_code'=>$charges[0],
		            	'room_charge_type_id'=>$key,
		            	'room_charge'=>$charges[1],
		            	'panel_type_id'=>0,
		            	'panel_company_id'=>$panel_comapny->id

		            );
		            $this->db->insert('hms_ipd_room_charge',$ipd_room_charge);
		            
		           //echo $this->db->last_query();
            	}

            } 



            ////////////////////////////////////////////////////
			
			/////insert data into hms_ipd_room_to_bed///////////
			$count = 0;
            for($j=0;$j<$post['total_bad'];$j++)
            {
            	$count = $count+1;
            	$bed_no[$j] =$count;
            
	            $data[$j] = array(
	                'branch_id'=>$user_data['parent_id'],
	                'room_id'=>$room_id,
	                'room_no'=>$room_no,
	                'bad_no'=>$bed_no[$j],
	                'bad_name'=>$bed_no[$j],
	                'room_type_id'=>$post['room_category_id'],
	                'created_by'=>$user_data['id'],
	                'created_date'=>date('Y-m-d H:i:s'),
	            );
	            $this->db->insert('hms_ipd_room_to_bad',$data[$j]);
	        }
	    
	        //////////////////////////////////////////////////////



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

	public function bed_save(){
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'bad_no'=>$post['bad_no'],
				    'bad_name'=>$post['bad_name'],
				    'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
		
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ipd_room_to_bad',$data);
			
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
			$this->db->update('hms_ipd_rooms');
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
			$this->db->update('hms_ipd_rooms');
			//echo $this->db->last_query();die;
    	} 
    }

    function get_simulation_name($simulation_id)
    {
        $this->db->select('simulation'); 
        $this->db->where('id',$simulation_id);                   
        $query = $this->db->get('hms_ipd_rooms');
        $test_list = $query->result(); 
            foreach($test_list as $simulations)
            {
               $simulation = $simulations->simulation;
            } 
        
        return $simulation; 
    }
    function  get_bed_to_room_list($room_data=array()){
    	
    	$result = array();
    	$users_data = $this->session->userdata('auth_users');
    	if(!empty($room_data) && isset($room_data)){
    		$this->db->select('hms_ipd_rooms.created_date,hms_ipd_rooms.id as room_id,hms_ipd_room_to_bad.id as bed_id,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name');
    		$this->db->from('hms_ipd_rooms');
    		$this->db->join('hms_ipd_room_to_bad','hms_ipd_rooms.id=hms_ipd_room_to_bad.room_id and hms_ipd_room_to_bad.room_id='.$room_data['roomno'].' and hms_ipd_room_to_bad.room_type_id='.$room_data['roomcategory']);
    		$this->db->where('(hms_ipd_room_to_bad.branch_id='.$users_data['parent_id'].' and hms_ipd_rooms.branch_id='.$users_data['parent_id'].')');
    		$this->db->where('hms_ipd_rooms.is_deleted',0);
    		// $this->db->where('hms_beds_to_room_to_roomcat.room_id',$room_data['roomno']);
    		// $this->db->where('hms_beds_to_room_to_roomcat.room_cat_id',$room_data['roomcategory']);

    		$query = $this->db->get();
    		// echo $this->db->last_query();die;
    		$result = $query->result_array();

    	}
    
    	return $result;
    	
    }
    public function get_ipd_room_details($id=""){
    
    	$users_data = $this->session->userdata('auth_users');
        if(!empty($id)){

        	
        	/////////get the room details///////////////
            $this->db->select('hms_ipd_rooms.*,hms_ipd_room_category.room_category,count(hms_ipd_room_to_bad.bad_no) as total_bad');
        	$this->db->from('hms_ipd_rooms');
        	$this->db->join('hms_ipd_room_category','hms_ipd_rooms.room_type_id=hms_ipd_room_category.id and hms_ipd_room_category.branch_id='.$users_data['parent_id'].'');
        	$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.room_id=hms_ipd_rooms.id','left');
       
            $this->db->where('hms_ipd_rooms.id',$id);
        	$this->db->where('hms_ipd_rooms.is_deleted',0);
        	$this->db->where('hms_ipd_room_category.is_deleted',0);
            
        	// $this->db->group_by('hms_ipd_room_charge.id');

        	$query = $this->db->get();
        	// echo $this->db->last_query();die;
        	$room_details = $query->result_array();
        	$room_charge_details = $this->get_room_charge_details($id);
        	$room_det = array_merge($room_details,$room_charge_details);


           
        	// }
        }
        return $room_det;
      
    }

    public function get_room_charge_details($room_id='', $panel_type_id="0", $panel_company_id="0")
    {    
        $users_data = $this->session->userdata('auth_users');
        ///////////get charge details/////////////////
        $this->db->select('hms_ipd_room_charge.room_charge, hms_ipd_room_charge_type.charge_type');
        $this->db->join('hms_ipd_room_charge_type','hms_ipd_room_charge_type.id = hms_ipd_room_charge.room_charge_type_id','left');
        $this->db->where('hms_ipd_room_charge.panel_type_id',$panel_type_id); 
        $this->db->where('hms_ipd_room_charge.panel_company_id',$panel_company_id); 
        $this->db->where('hms_ipd_room_charge.branch_id',$users_data['parent_id']); 
        $this->db->where('hms_ipd_room_charge.room_id',$room_id);
        $this->db->where('hms_ipd_room_charge.types',0);
        $query = $this->db->get('hms_ipd_room_charge');
        return $query->result_array(); 
    }

    public function get_bed_by_id($id){
    	$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ipd_room_to_bad.*"); 
		$this->db->from('hms_ipd_room_to_bad'); 
		$this->db->where('hms_ipd_room_to_bad.id',$id);
        $this->db->where('hms_ipd_room_to_bad.branch_id='.$users_data['parent_id'].'');
		$query = $this->db->get(); 
		return $query->row_array();
		//echo $this->db->last_query();die;
    }

    public function get_patient_detail($bed_id="",$ipd_id=""){
    	$this->db->select("hms_ipd_booking.*,hms_patient.patient_name"); 
		$this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id','left');
		
		if(!empty($ipd_id))
		{
			$this->db->where('hms_ipd_booking.id',$ipd_id);	
			
		}
		$result= $this->db->get('hms_ipd_booking')->result();
		return $result;
    }


    public function get_room_list($id="",$room_type_id='')
	{	
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ipd_room_category.*');
		$this->db->from('hms_ipd_room_category'); 
		$this->db->where('hms_ipd_room_category.status',1); 
    	$this->db->where('hms_ipd_room_category.is_deleted',0); 
    	if(!empty($id))
    	{
    		$this->db->where('hms_ipd_room_category.id',$id);
    	}
		$this->db->where('hms_ipd_room_category.branch_id',$user_data['parent_id']);
		$query = $this->db->get();
		$result = $query->result();
		//echo $this->db->last_query(); exit; 
		return $result;
	}



	////24 september 2020

		public function get_old_ipd_room_charge($room_id='',$room_type_id='',$room_charge_type_id='')
	    {
	    	$user_data = $this->session->userdata('auth_users');
	    	$this->db->select('hms_ipd_room_charge.*');
	    	
	    	$this->db->where('hms_ipd_room_charge.room_id',$room_id);
	    	if(!empty($room_type_id))
	    	{
	    		$this->db->where('hms_ipd_room_charge.room_type_id',$room_type_id);	
	    	}
	    	if(!empty($room_charge_type_id))
	    	{
	    		$this->db->where('hms_ipd_room_charge.room_charge_type_id',$room_charge_type_id);	
	    	}
	    	
	    	
	    	$this->db->where('hms_ipd_room_charge.types',0);
	    	$this->db->where('hms_ipd_room_charge.branch_id',$user_data['parent_id']);
	    	$query = $this->db->get('hms_ipd_room_charge');
	    	//echo $this->db->last_query(); exit; 
			return $query->result();
	    }

	    public function get_old_ipd_room_bed($room_id='',$bad_no='')
	    {

	    	$user_data = $this->session->userdata('auth_users');
	    	$this->db->select('hms_ipd_room_to_bad.*');
	    	
	    	if(!empty($room_id))
	    	{
	    		$this->db->where('hms_ipd_room_to_bad.room_id',$room_id);
	    	}
	    	if(!empty($bad_no))
	    	{
	    		$this->db->where('hms_ipd_room_to_bad.bad_no',$bad_no);
	    	}
	    	
	    	$this->db->where('hms_ipd_room_to_bad.branch_id',$user_data['parent_id']);
	    	$query = $this->db->get('hms_ipd_room_to_bad');
	    	//echo $this->db->last_query(); exit; 
			return $query->result();

	    }
	    
  

}
?>