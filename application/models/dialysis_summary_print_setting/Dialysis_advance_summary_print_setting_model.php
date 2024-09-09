<?php
class Dialysis_advance_summary_print_setting_model extends CI_Model 
{
	var $table = 'hms_branch_discharge_advance_summary_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique($type='0')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_branch_discharge_advance_summary_setting.*');
		$this->db->from('hms_branch_discharge_advance_summary_setting'); 
    $this->db->where('hms_branch_discharge_advance_summary_setting.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_branch_discharge_advance_summary_setting.setting_name','DISCHARGE_SUMMARY_PRINT_SETTING'); 
		$query = $this->db->get();
    return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
    //print_r($post); exit; 
    $current_date = date('Y-m-d H:i:s');
    // 
    // [type] => 0
    // [data_id] => 86
    // [message]
    if(!empty($post['data_id']))
    {
        $data = array(
                     "branch_id"=>$user_data['parent_id'],
                     "type"=>$post['type'],
                     "setting_name"=>'DISCHARGE_SUMMARY_PRINT_SETTING',
                     "setting_value"=>$post['message'],
                     "ip_address"=>$_SERVER['REMOTE_ADDR'],
                     "created_by"=>$user_data['id'], 
                     "created_date"=>$current_date
               );
        $this->db->where('id',$post['data_id']);
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->update('hms_branch_discharge_advance_summary_setting',$data);
    }

   } 


    public function get_hms_prescription_page()
    {
    	$this->db->select('page_data');  
        $query = $this->db->get('hms_branch_discharge_advance_summary_setting');
        $result = $query->row();
        $data='';
        if(!empty($result))
        {
        	$data = $result->page_data;
        }
        return $data;
    }

    function template_format($data="",$branch_id='')
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_branch_discharge_advance_summary_setting.*');
      $this->db->where($data);
      if(!empty($branch_id))
      {
        $this->db->where('hms_branch_discharge_advance_summary_setting.branch_id = "'.$branch_id.'"');
      }
      else
      {
        $this->db->where('hms_branch_discharge_advance_summary_setting.branch_id = "'.$users_data['parent_id'].'"');
      }
       
      $this->db->from('hms_branch_discharge_advance_summary_setting');
      $result=$this->db->get()->row();
      return $result;

    }

    public function second_template_format($sub_section="")
    {

        $users_data = $this->session->userdata('auth_users');
        $data=array('type'=>$sub_section);
        $this->db->select("hms_branch_discharge_advance_summary_setting.*"); 
        $this->db->from('hms_branch_discharge_advance_summary_setting'); 
        $this->db->where($data);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get();
        //echo $this->db->last_query();die; 
        return $query->row_array();
  }


  

} 
?>