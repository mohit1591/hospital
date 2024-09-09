<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_pacakge_model extends CI_Model {

	var $table = 'hms_ot_pacakge';
	var $column = array('hms_ot_pacakge.id','hms_ot_pacakge.name','hms_ot_pacakge.type','hms_ot_pacakge.days','hms_ot_pacakge.amount','hms_ot_pacakge.remarks');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ot_pacakge.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_ot_pacakge.is_deleted','0');
        $this->db->where('hms_ot_pacakge.branch_id = "'.$users_data['parent_id'].'"');
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
    	$query = $this->db->get('hms_ot_pacakge');
		return $query->result();
    }

	public function get_by_id($id)
	{  

		$this->db->select("hms_ot_pacakge.*,hms_operation_type.operation_type");
		$this->db->from('hms_ot_pacakge'); 
		$this->db->where('hms_ot_pacakge.id',$id);
		$this->db->join('hms_operation_type','hms_operation_type.id=hms_ot_pacakge.type','left');
		
		$this->db->where('hms_ot_pacakge.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	public function get_by_id_ot_details($id)
	{
		$this->db->select("hms_ot_pacakge_details.*,hms_doctors.doctor_name,hms_ipd_perticular.particular,hms_doctors.id as doctor_id");
		$this->db->from('hms_ot_pacakge_details'); 
		$this->db->where('hms_ot_pacakge.id',$id);
		$this->db->join('hms_ot_pacakge','hms_ot_pacakge.id=hms_ot_pacakge_details.ot_package_id','left');
		$this->db->join('hms_doctors','hms_doctors.id=hms_ot_pacakge_details.doctor_id','left');
		$this->db->join('hms_ipd_perticular','hms_ipd_perticular.id=hms_ot_pacakge_details.particular_id','left');
		$this->db->where('hms_ot_pacakge.is_deleted','0');
		$query = $this->db->get(); 
		return $query->result();
	}
	
	/*public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'type'=>$post['type'],
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ot_pacakge',$data);  
		}
		else{ 
			if(!empty($post['ot_type_id']))
			{
				//echo "hi";die;
				$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'type'=>$post['ot_type_id'],
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
				//print_r($data);die;
			}
			else
			{
				//echo "hi";die;
				 $operation_array = array( 
					'branch_id'=>$user_data['parent_id'],
					'operation_type'=>$post['type'],
					'is_deleted'=>0,
					'deleted_by'=>$user_data['id'],
					'created_date'=>date('Y-m-d H:i:s'),
					'created_by'=>$user_data['id'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		         //print_r($operation_array);die;
				$this->db->insert('hms_operation_type',$operation_array);
				$ot_type_id=$this->db->insert_id();
				$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'type'=>$ot_type_id,
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
			}

			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ot_pacakge',$data);               
		} 	
	}

*/

	public function save()
	{
		//echo "hi";die;
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		
 	  if(!empty($post['data_id']) && $post['data_id']>0)
		{   
			//echo "hi";die;
			$this->db->where(array('ot_package_id'=>$post['data_id']));
			$this->db->delete('hms_ot_pacakge_details');
			$type_data= $this->check_ot_type($post['ot_pacakge_type_id']);
			
			if(!empty($type_data))
			{
			 $data = array(

			 	   'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
				   'type'=>$type_data['id'],
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
			         ); 
	            $this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
	            $this->db->where('id',$post['data_id']);
				$this->db->update('hms_ot_pacakge',$data);  
				$doctor_wise=$post['doctor_wise'];
				//print_r($doctor_wise);die;
				$particular_id='';
				$doctor_id='';
				/* new detail of ot */

				 if(!empty($doctor_wise))
				{
                       
						foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_id']) && !empty($val['doctor_id']))
						   {
						       
						   	  $doctor_id=$val['doctor_id'][0];

						   	  $data_ot = array( 
									'branch_id'=>$user_data['parent_id'],
									'doctor_id'=>$doctor_id,
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_package_id'=>$post['data_id'],

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	 
						   	 $this->db->insert('hms_ot_pacakge_details',$data_ot); 

						   }


						   if(isset($val['particular_id']) && !empty($val['particular_id']))
						   {
						   	  $particular_id=$val['particular_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									'particular_id'=>$particular_id,
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_package_id'=>$post['data_id'],

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  //print_r($data);
						   	  $this->db->insert('hms_ot_pacakge_details',$data); 
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
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
			         ); 
	            $this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
	            $this->db->where('id',$post['data_id']);
				$this->db->update('hms_ot_pacakge',$data);
				$doctor_wise=$post['doctor_wise'];
				$particular_id='';
				$doctor_id='';
				/* new detail of ot */

				if(!empty($doctor_wise))
				{


					     $i=0;
						foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_id']) && !empty($val['doctor_id']))
						   {
						   
						   	  $doctor_id=$val['doctor_id'][0];
						   	  $data_ot = array( 
									'branch_id'=>$user_data['parent_id'],
									'doctor_id'=>$doctor_id,
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_package_id'=>$post['data_id'],

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	 
						   	 $this->db->insert('hms_ot_pacakge_details',$data_ot); 

						   }


						   if(isset($val['particular_id']) && !empty($val['particular_id']))
						   {
						   	  $particular_id=$val['particular_id'][0];
						   	  $data_ot = array( 
									'branch_id'=>$user_data['parent_id'],
									'particular_id'=>$particular_id,
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_package_id'=>$post['data_id'],

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  $this->db->insert('hms_ot_pacakge_details',$data_ot); 
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
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_ot_pacakge',$data); 
                $last_id=$this->db->insert_id();
                $doctor_wise=$post['doctor_wise'];
                $particular_id='';
                $doctor_id='';

                //$particular_wise=$post['particular_wise'];
               //print_r($post);die;
				if(!empty($doctor_wise))
				{


					
						foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_id'][0]) && !empty($val['doctor_id'][0]) && $val['doctor_id'][0]!='')
						   {
						   
						   	  $doctor_id=$val['doctor_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									'doctor_id'=>$doctor_id,
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_package_id'=>$last_id,

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  //print_r($data);
						   	 
						   	   $this->db->insert('hms_ot_pacakge_details',$data); 

						   }
                           if(isset($val['particular_id'][0]) && !empty($val['particular_id'][0]) && $val['particular_id'][0]!='')
						   {
						      $particular_id=$val['particular_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									'particular_id'=>$particular_id,
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_package_id'=>$last_id,

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  $this->db->insert('hms_ot_pacakge_details',$data); 
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
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_ot_pacakge',$data); 
				$last_id=$this->db->insert_id();
                $doctor_wise=$post['doctor_wise'];
                $particular_id='';
                $doctor_id='';

				/* new code of ot details */

	         if(!empty($doctor_wise))
				{


					   
						foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_id']) && !empty($val['doctor_id']))
						   {
						   
						   	  $doctor_id=$val['doctor_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									'doctor_id'=>$doctor_id,
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_package_id'=>$last_id,

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	 
						   	 $this->db->insert('hms_ot_pacakge_details',$data); 

						   }


						   if(isset($val['particular_id']) && !empty($val['particular_id']))
						   {
						   	  $particular_id=$val['particular_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									'particular_id'=>$particular_id,
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'ot_package_id'=>$last_id,

									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  $this->db->insert('hms_ot_pacakge_details',$data); 
						   }
						
						} 
						

				}
                /* new details of ot */
			}
			
			//echo $this->db->last_query();die;              
		} 	
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
			$this->db->update('hms_ot_pacakge');
			//echo $this->db->last_query();die;
    	} 
    }
   public function check_type($type)
     {
       if(!empty($type))
    	{

		$this->db->select('hms_operation_type.*');
		$this->db->from('hms_operation_type'); 
		$this->db->where('hms_ot_pacakge.id',$id);
		$this->db->where('hms_ot_pacakge.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
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
			$this->db->update('hms_ot_pacakge');
			//echo $this->db->last_query();die;
    	} 
    }

    public function get_vals($vals="")
    { 
    	//echo "hi";die;
    	//echo $vals;die;
    	$response = '';
    	if(!empty($vals))
    	{ //echo "hi";die;
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('operation_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('operation_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_operation_type');
	        $result = $query->result(); 
	      // echo $this->db->last_query();
	      // print_r($result);die;
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	              // $response[] = $vals->operation_type.'|'.$vals->id;
	               $response[] = $vals->operation_type;
	        	}
	        }
	        return $response; 
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

}
?>