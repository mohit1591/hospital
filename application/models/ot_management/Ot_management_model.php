<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_management_model extends CI_Model {

	var $table = 'hms_ot_management';
	var $column = array('hms_ot_management.id','hms_ot_management.name','hms_ot_management.type','hms_ot_management.hours','hms_ot_management.amount','hms_ot_management.status','hms_ot_management.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ot_management.*,hms_operation_type.id as ot_type_id,hms_operation_type.operation_type"); 
		$this->db->from($this->table); 
			$this->db->join('hms_operation_type','hms_operation_type.id=hms_ot_management.type','left');
        $this->db->where('hms_ot_management.is_deleted','0');
        $this->db->where('hms_ot_management.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function ot_pacakge_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('name','ASC'); 
    	$query = $this->db->get('hms_ot_management');
		return $query->result();
    }

    public function get_by_id_ot_details($id)
	{
		$this->db->select("hms_ot_details.*,hms_ot_details.particular_name as particular");
		$this->db->from('hms_ot_details'); 
		$this->db->where('hms_ot_management.id',$id);
		$this->db->join('hms_ot_management','hms_ot_management.id=hms_ot_details.ot_mgt_id','left');
		$this->db->join('hms_doctors','hms_doctors.id=hms_ot_details.doctor_id','left');
		$this->db->join('hms_ipd_perticular','hms_ipd_perticular.id=hms_ot_details.particular_id','left');
		$this->db->where('hms_ot_management.is_deleted','0');
		$query = $this->db->get(); 
		return $query->result();
	}

	public function get_by_id($id)
	{
		$this->db->select('hms_ot_management.*,hms_operation_type.id as ot_type_id,hms_operation_type.operation_type');
		$this->db->from('hms_ot_management'); 
		$this->db->where('hms_ot_management.id',$id);
		$this->db->where('hms_ot_management.is_deleted','0');
		$this->db->join('hms_operation_type','hms_operation_type.id=hms_ot_management.type');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		
		if(!empty($post['data_id']) && $post['data_id']>0)
		{   
			$type_data= $this->check_ot_type($post['ot_pacakge_type_id']);
			$this->db->where(array('ot_mgt_id'=>$post['data_id']));
			$this->db->delete('hms_ot_details');
			if(!empty($type_data))
			{
			 $data = array(
							'branch_id'=>$user_data['parent_id'],
							'name'=>$post['name'],
							'type'=>$type_data['id'],
							'hours'=>$post['hours'],
							'amount'=>$post['amount'],
							'status'=>$post['status'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'ot_procedure'=>$post['ot_procedure'],
			         ); 
	            $this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
	            $this->db->where('id',$post['data_id']);
				$this->db->update('hms_ot_management',$data);  

				$doctor_wise=$post['doctor_wise'];
				//print_r($doctor_wise);die;
				$particular_id='';
				$doctor_id='';
				/* new detail of ot */

				 if(!empty($doctor_wise))
				{
                       
						foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_name']) && !empty($val['doctor_name']))
						   {
						       
						   	  $doctor_id=$val['doctor_id'][0];

						   	  $data_ot = array( 
									'branch_id'=>$user_data['parent_id'],
									//'doctor_id'=>$doctor_id,
									'doctor_name'=>$val['doctor_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code'][0],
									'ot_mgt_id'=>$post['data_id'],

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	 
						   	 $this->db->insert('hms_ot_details',$data_ot); 

						   }


						   if(isset($val['particular_name']) && !empty($val['particular_name']))
						   {
						   	  $particular_id=$val['particular_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									//'particular_id'=>$particular_id,
									'particular_name'=>$val['particular_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_mgt_id'=>$post['data_id'],
									'code'=>$val['code_p'][0],

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  //print_r($data);
						   	  $this->db->insert('hms_ot_details',$data); 
						   }
						
						} 
						

				}

				/* new detail of ot */
			}
			else
			{
				$data_op_type = array( 
					'branch_id'=>$user_data['parent_id'],
					'operation_type'=>$post['ot_pacakge_type_id'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
					);
				$this->db->insert('hms_operation_type',$data_op_type);
				$last_insert_id=$this->db->insert_id();
				 $data = array(
							'branch_id'=>$user_data['parent_id'],
							'name'=>$post['name'],
							'type'=>$last_insert_id,
							'hours'=>$post['hours'],
							'amount'=>$post['amount'],
							'status'=>$post['status'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'ot_procedure'=>$post['ot_procedure'],
			         ); 
	            $this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
	            $this->db->where('id',$post['data_id']);
				$this->db->update('hms_ot_management',$data);

				$doctor_wise=$post['doctor_wise'];
				$particular_id='';
				$doctor_id='';
				/* new detail of ot */

				if(!empty($doctor_wise))
				{


					     $i=0;
						foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_name']) && !empty($val['doctor_name']))
						   {
						   
						   	  $doctor_id=$val['doctor_id'][0];
						   	  $data_ot = array( 
									'branch_id'=>$user_data['parent_id'],
									//'doctor_id'=>$doctor_id,
									'doctor_name'=>$val['doctor_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code'][0],
									'ot_mgt_id'=>$post['data_id'],

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	 
						   	 $this->db->insert('hms_ot_details',$data_ot); 

						   }


						   if(isset($val['particular_name']) && !empty($val['particular_name']))
						   {
						   	  $particular_id=$val['particular_id'][0];
						   	  $data_ot = array( 
									'branch_id'=>$user_data['parent_id'],
									//'particular_id'=>$particular_id,
									'particular_name'=>$val['particular_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code_p'][0],
									'ot_mgt_id'=>$post['data_id'],

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  $this->db->insert('hms_ot_details',$data_ot); 
						   }
						
						} 
						

				}
				
				/* new detail of ot */ 	
			}
		}
		else
		{    
             $type_data= $this->check_ot_type($post['ot_pacakge_type_id']);
                
			if(!empty($type_data))
			{
				$data = array(
						'branch_id'=>$user_data['parent_id'],
						'name'=>$post['name'],
						'type'=>$type_data['id'],
						'hours'=>$post['hours'],
						'amount'=>$post['amount'],
						'status'=>$post['status'],
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'ot_procedure'=>$post['ot_procedure'],
		         );
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_ot_management',$data); 
				$last_id=$this->db->insert_id();
				$doctor_wise=$post['doctor_wise'];
				$particular_id='';
				$doctor_id='';

                //$particular_wise=$post['particular_wise'];
              // print_r($post);die;
				if(!empty($doctor_wise))
				{

					foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_name'][0]) && !empty($val['doctor_name'][0]) && $val['doctor_name'][0]!='')
						   {
						   
						   	  $doctor_id=$val['doctor_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									//'doctor_id'=>$doctor_id,
									'doctor_name'=>$val['doctor_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code'][0],
									'ot_mgt_id'=>$last_id,

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  //print_r($data);
						   	 
						   	   $this->db->insert('hms_ot_details',$data);
						   	   //echo $this->db->last_query(); 

						   }
                           if(isset($val['particular_name'][0]) && !empty($val['particular_name'][0]) && $val['particular_name'][0]!='')
						   {
						      $particular_id=$val['particular_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									//'particular_id'=>$particular_id,
									'particular_name'=>$val['particular_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code_p'][0],
									'ot_mgt_id'=>$last_id,

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  $this->db->insert('hms_ot_details',$data); 
						   }
						  
						
						} 
						 
						

				}
			}
			else
			{

				$data_op_type = array( 
				'branch_id'=>$user_data['parent_id'],
				'operation_type'=>$post['ot_pacakge_type_id'],
				'status'=>$post['status'],
				'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				 $this->db->insert('hms_operation_type',$data_op_type);
				 $last_insert_id=$this->db->insert_id();
				 $data = array(
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'type'=>$last_insert_id,
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					'ot_procedure'=>$post['ot_procedure'],
				);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_ot_management',$data); 

				    $doctor_wise=$post['doctor_wise'];
                $particular_id='';
                $doctor_id='';

				/* new code of ot details */

				if(!empty($doctor_wise))
				{


					   
						foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_name']) && !empty($val['doctor_name']))
						   {
						   
						   	  $doctor_id=$val['doctor_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									//'doctor_id'=>$doctor_id,
									'doctor_name'=>$val['doctor_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code'][0],
									'ot_mgt_id'=>$last_id,

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	 
						   	 $this->db->insert('hms_ot_details',$data); 

						   }


						   if(isset($val['particular_name']) && !empty($val['particular_name']))
						   {
						   	  $particular_id=$val['particular_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									//'particular_id'=>$particular_id,
									'particular_name'=>$val['particular_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code_p'][0],
									'ot_mgt_id'=>$last_id,

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  $this->db->insert('hms_ot_details',$data); 
						   }
						
						} 
						

				}
				/* new details of ot */
			}
			
			//echo $this->db->last_query();die;              
		} 	
	}
        public function type_list()
          {
        $user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('operation_type','ASC'); 
    	$query = $this->db->get('hms_operation_type');
		$result = $query->result(); 
		return $result;
  }
  
	function check_ot_type($ot_pacakge_type_id="")
	{
		$this->db->select('hms_operation_type.operation_type,hms_operation_type.id');
		$this->db->from('hms_operation_type'); 
		$this->db->where('hms_operation_type.id',$ot_pacakge_type_id);
		$this->db->where('hms_operation_type.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
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
			$this->db->update('hms_ot_management');
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
			$this->db->update('hms_ot_management');
			//echo $this->db->last_query();die;
    	} 
    }

    public function get_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('operation_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('operation_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_operation_type');
	        $result = $query->result(); 
	       // echo $this->db->last_query();die;
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->operation_type.'|'.$vals->id;
	        	}
	        }
	        return $response; 
    	} 
    }


      public function save_all_ot_management($ot_all_data = array())
	{
	
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($ot_all_data))
        {
            foreach($ot_all_data as $ot_data)
            {
            	//print_r($doctor_data);
            	if(!empty($ot_data['name']))
            	{

						$type='';
					if(!empty($ot_data['type']))
            		{
            			//echo "hello"; print_r($doctor_data['specialization']);
		            	$this->db->select("hms_operation_type.*");
					    $this->db->from('hms_operation_type'); 
		                $this->db->where('LOWER(hms_operation_type.operation_type)',strtolower($ot_data['type'])); 
		                $this->db->where('hms_operation_type.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $ot_data_get = $query->result_array();

					    if(!empty($ot_data_get))
					    {
						    $type = $ot_data_get[0]['id'];
					    }
					    else
					    {
						 	$type_insert_data = array(
							'operation_type'=>$ot_data['type'],
							'branch_id'=>$users_data['parent_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_operation_type',$type_insert_data);
							$type = $this->db->insert_id();
					    }
					}
				
          
				$ot_data_array = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'name'=>$ot_data['name'],
	                    'type'=>$type,
	                    'hours'=>$ot_data['hours'],
	                    'amount'=>$ot_data['amount'],
						'status'=>1,					
	                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_date'=>date('Y-m-d H:i:s'),
				        'modified_date'=>date('Y-m-d H:i:s'),
				        'created_by'=>$users_data['parent_id']
				        
				    );

               //echo $this->db->last_query(); exit;
				    $this->db->insert('hms_ot_management',$ot_data_array);
	                //echo $this->db->last_query(); exit;
	            }
            }   	
        }
	}

}
?>