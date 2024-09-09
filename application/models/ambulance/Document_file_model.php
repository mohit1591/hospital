<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_file_model extends CI_Model {

	var $table = 'hms_ambulance_vehicle_documents';
	var $column = array('hms_ambulance_vehicle_documents.id','hms_ambulance_vehicle_documents.document_id','hms_ambulance_document.document','hms_ambulance_vehicle_documents.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $get = $this->session->userdata('docs_search');
		$this->db->select("hms_ambulance_vehicle_documents.*,hms_ambulance_document.document,hms_ambulance_vehicle.vehicle_no"); 
		$this->db->from($this->table);  
		$this->db->join('hms_ambulance_document',"hms_ambulance_document.id=hms_ambulance_vehicle_documents.document_id"); 
		$this->db->join('hms_ambulance_vehicle',"hms_ambulance_vehicle.id=hms_ambulance_vehicle_documents.vehicle_id",'left');
        $this->db->where('hms_ambulance_vehicle_documents.branch_id = "'.$users_data['parent_id'].'"');
		$i = 0;
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{				
				if($i===0) // first loop
				{
					$this->db->group_start(); 
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

         if(!empty($get))
         { 
            if(!empty($get['create_from']))
            {
               $create_from=date('Y-m-d',strtotime($get['create_from'])).' 00:00:00';
               $this->db->where('hms_ambulance_vehicle_documents.created_date >= "'.$create_from.'"');
            }
            if(!empty($get['create_to']))
            {
                $create_to=date('Y-m-d',strtotime($get['create_to'])).' 23:59:59';
                $this->db->where('hms_ambulance_vehicle_documents.created_date <= "'.$create_to.'"');
            }
            if(!empty($get['renewal_from']))
            {
               $renewal_from=date('Y-m-d',strtotime($get['renewal_from']));
               $this->db->where('hms_ambulance_vehicle_documents.renewal_date >= "'.$renewal_from.'"');
            }
            if(!empty($get['renewal_to']))
            {
                $renewal_to=date('Y-m-d',strtotime($get['renewal_to']));
                $this->db->where('hms_ambulance_vehicle_documents.renewal_date <= "'.$renewal_to.'"');
            }
                if(!empty($get['exp_from']))
            {
               $exp_from=date('Y-m-d',strtotime($get['exp_from']));
               $this->db->where('hms_ambulance_vehicle_documents.expiry_date >= "'.$exp_from.'"');
            }
            if(!empty($get['exp_to']))
            {
                $exp_to=date('Y-m-d',strtotime($get['exp_to']));
                $this->db->where('hms_ambulance_vehicle_documents.expiry_date <= "'.$exp_to.'"');
            }

            if(!empty($get['vehicle_id']))
            {
               $this->db->where('hms_ambulance_vehicle_documents.vehicle_id',$get['vehicle_id']);
            }
            if(!empty($get['document_id']))
            {
                $this->db->where('hms_ambulance_vehicle_documents.document_id',$get['document_id']);
            }
            if(!empty($get['remark']))
            {
                $this->db->where('hms_ambulance_vehicle_documents.remarks LIKE "%'.$get['remark'].'%"');
            }
         }
	}

	function get_datatables($vehicle_id='')
	{
		$this->_get_datatables_query();
		if(!empty($vehicle_id)){
		   $this->db->where('vehicle_id',$vehicle_id);
		}
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		return $query->result();
	}

	function count_filtered($vehicle_id='')
	{
		$this->_get_datatables_query();
		if(!empty($vehicle_id)){
		   $this->db->where('vehicle_id',$vehicle_id);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($vehicle_id='')
	{
		$this->db->from($this->table);
		if(!empty($vehicle_id)){
		   $this->db->where('vehicle_id',$vehicle_id);
		}
		return $this->db->count_all_results();
	}
 


    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{ 
    		$this->db->select('*');  
			$this->db->where('id',$id);
			$query = $this->db->get('hms_ambulance_vehicle_documents');
			$result = $query->result();
			if(!empty($result))
			{
			  if(!empty($result[0]->vehicle_docs))
			  {
			  	unlink(DIR_UPLOAD_PATH.'vehicle_docs/'.$result[0]->vehicle_docs);
			  }	 
			}  

			$this->db->where('id',$id);
			$this->db->delete('hms_ambulance_vehicle_documents'); 
    	} 
    }

    public function deleteall($ids=array())
    {
    	if(!empty($ids))
    	{ 

    		$id_list = [];
    		foreach($ids as $id)
    		{
    			$this->db->select('*');  
				$this->db->where('id',$id);
				$query = $this->db->get('hms_ambulance_vehicle_documents');
				$result = $query->result();
				if(!empty($result))
				{
				  if(!empty($result[0]->vehicle_docs))
				  {
				  	unlink(DIR_UPLOAD_PATH.'vehicle_docs/'.$result[0]->vehicle_docs);
				  }	 
				}

    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$banch_ids = implode(',', $id_list); 
			$this->db->where('id IN ('.$banch_ids.')');
			$this->db->delete('hms_ambulance_vehicle_documents'); 
    	} 
    }

    public function save_file($filename="")
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'vehicle_id'=>$post['vehicle_id'],
					'renewal_date'=>date('Y-m-d',strtotime($post['renewal_date'])),
					'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'])),
					'document_id'=>$post['document_id'],
					'remarks'=>$post['remark'],
					'vehicle_docs'=>$filename,
					'branch_id' => $user_data['parent_id'],
					'status' =>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
		    
			if(!empty($filename))
			{
			   $this->db->set('document_name',$filename);
			}
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ambulance_vehicle_documents',$data);  
		}
		else{    
		    
		    $pre_data=$this->get_document_by_id($post['vehicle_id'],$post['document_id']);
		    if(!empty($pre_data))
		    {
		       $this->db->update('hms_ambulance_vehicle_documents',array('status'=>0),array('vehicle_id'=>$post['vehicle_id'],'document_id'=>$post['document_id'])); 
		    }
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ambulance_vehicle_documents',$data);               
		} 
		//echo $this->db->last_query();die();
	}

	public function document_list(){
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('id,document');
		$this->db->from('hms_ambulance_document'); 
		$this->db->where('branch_id',$user_data['parent_id']);
		$query = $this->db->get(); 
		$result=$query->result();
		return $result;
	}
	
	function search_report_data(){
		$users_data = $this->session->userdata('auth_users');
		$get = $this->session->userdata('docs_search');
		$this->db->select("hms_ambulance_vehicle_documents.*,hms_ambulance_document.document,hms_ambulance_vehicle.vehicle_no"); 
		$this->db->from($this->table); 
		$this->db->join('hms_ambulance_document',"hms_ambulance_document.id=hms_ambulance_vehicle_documents.document_id");
		
		$this->db->join('hms_ambulance_vehicle',"hms_ambulance_vehicle.id=hms_ambulance_vehicle_documents.vehicle_id",'left');
		
		
		$this->db->where('hms_ambulance_vehicle_documents.branch_id',$users_data['parent_id']);
		 if(!empty($get))
         { 
            if(!empty($get['create_from']))
            {
               $create_from=date('Y-m-d',strtotime($get['create_from'])).' 00:00:00';
               $this->db->where('hms_ambulance_vehicle_documents.created_date >= "'.$create_from.'"');
            }
            if(!empty($get['create_to']))
            {
                $create_to=date('Y-m-d',strtotime($get['create_to'])).' 23:59:59';
                $this->db->where('hms_ambulance_vehicle_documents.created_date <= "'.$create_to.'"');
            }
            if(!empty($get['renewal_from']))
            {
               $renewal_from=date('Y-m-d',strtotime($get['renewal_from']));
               $this->db->where('hms_ambulance_vehicle_documents.renewal_date >= "'.$renewal_from.'"');
            }
            if(!empty($get['renewal_to']))
            {
                $renewal_to=date('Y-m-d',strtotime($get['renewal_to']));
                $this->db->where('hms_ambulance_vehicle_documents.renewal_date <= "'.$renewal_to.'"');
            }
                if(!empty($get['exp_from']))
            {
               $exp_from=date('Y-m-d',strtotime($get['exp_from']));
               $this->db->where('hms_ambulance_vehicle_documents.expiry_date >= "'.$exp_from.'"');
            }
            if(!empty($get['exp_to']))
            {
                $exp_to=date('Y-m-d',strtotime($get['exp_to']));
                $this->db->where('hms_ambulance_vehicle_documents.expiry_date <= "'.$exp_to.'"');
            }

            if(!empty($get['vehicle_id']))
            {
               $this->db->where('hms_ambulance_vehicle_documents.vehicle_id',$get['vehicle_id']);
            }
            if(!empty($get['document_id']))
            {
                $this->db->where('hms_ambulance_vehicle_documents.document_id',$get['document_id']);
            }
            if(!empty($get['remark']))
            {
                $this->db->where('hms_ambulance_vehicle_documents.remarks LIKE "%'.$get['remark'].'%"');
            }
         }
		$query = $this->db->get(); 
		return $query->result();
	}
	
	public function get_document_by_id($veh_id,$doc_id)
	{
	    $this->db->select('*');
	    $this->db->where('vehicle_id',$veh_id);
	    $this->db->where('document_id',$doc_id);
	    $this->db->from('hms_ambulance_vehicle_documents');
	    $query=$this->db->get();
	    return $query->row();
	    
	}

}
?>