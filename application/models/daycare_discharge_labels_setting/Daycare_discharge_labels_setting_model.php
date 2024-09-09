<?php
class Daycare_discharge_labels_setting_model extends CI_Model 
{
    var $table = 'hms_daycare_discharge_labels_setting'; 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    public function get_master_unique($status='',$print='')
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('hms_daycare_discharge_labels_setting.*, hms_daycare_branch_discharge_labels_setting.setting_name, hms_daycare_branch_discharge_labels_setting.setting_value,hms_daycare_branch_discharge_labels_setting.order_by,hms_daycare_branch_discharge_labels_setting.status,hms_daycare_branch_discharge_labels_setting.print_status,hms_daycare_branch_discharge_labels_setting.type');
      $this->db->join('hms_daycare_branch_discharge_labels_setting', 'hms_daycare_branch_discharge_labels_setting.unique_id=hms_daycare_discharge_labels_setting.id AND hms_daycare_branch_discharge_labels_setting.branch_id = "'.$user_data['parent_id'].'"');
      $this->db->from('hms_daycare_discharge_labels_setting');
      if(!empty($status))
      {
        $this->db->where('hms_daycare_branch_discharge_labels_setting.status',1);
      }
      if(!empty($print))
      {
        $this->db->where('hms_daycare_branch_discharge_labels_setting.print_status',1);
      }
      
      $this->db->where('hms_daycare_branch_discharge_labels_setting.gipsa_type',0);
         
      $this->db->order_by('hms_daycare_branch_discharge_labels_setting.order_by','ASC');  
      $result = $this->db->get()->result_array(); 
      
      // print '<pre>';print_r($query);
      return $result;
    }
  public function get_vital_master_unique()
  {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('hms_daycare_vital_labels_setting.*, hms_daycare_branch_vital_labels_setting.setting_name, hms_daycare_branch_vital_labels_setting.setting_value,hms_daycare_branch_vital_labels_setting.order_by,hms_daycare_branch_vital_labels_setting.status,hms_daycare_branch_vital_labels_setting.print_status,hms_daycare_branch_vital_labels_setting.type,hms_daycare_branch_vital_labels_setting.vital_type');
      $this->db->join('hms_daycare_branch_vital_labels_setting', 'hms_daycare_branch_vital_labels_setting.unique_id=hms_daycare_vital_labels_setting.id AND hms_daycare_branch_vital_labels_setting.branch_id = "'.$user_data['parent_id'].'"','left');
      $this->db->from('hms_daycare_vital_labels_setting');
      $this->db->where('vital_type','0');
      $this->db->order_by('hms_daycare_branch_vital_labels_setting.order_by','ASC');  
      $query = $this->db->get()->result(); 
      // print '<pre>';print_r($query);
      return $query;
  }

  public function get_vital_master_unique_array($status='',$print='')
  {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('hms_daycare_vital_labels_setting.*, hms_daycare_branch_vital_labels_setting.setting_name, hms_daycare_branch_vital_labels_setting.setting_value,hms_daycare_branch_vital_labels_setting.order_by,hms_daycare_branch_vital_labels_setting.status,hms_daycare_branch_vital_labels_setting.print_status,hms_daycare_branch_vital_labels_setting.type,hms_daycare_branch_vital_labels_setting.vital_type');
      $this->db->join('hms_daycare_branch_vital_labels_setting', 'hms_daycare_branch_vital_labels_setting.unique_id=hms_daycare_vital_labels_setting.id AND hms_daycare_branch_vital_labels_setting.branch_id = "'.$user_data['parent_id'].'"','left');
      $this->db->from('hms_daycare_vital_labels_setting');
      $this->db->where('hms_daycare_branch_vital_labels_setting.vital_type','0');
      if(!empty($status))
      {
        $this->db->where('hms_daycare_branch_vital_labels_setting.status',1);
      }
      if(!empty($print))
      {
        $this->db->where('hms_daycare_branch_vital_labels_setting.print_status',1);
      }
      $this->db->order_by('hms_daycare_branch_vital_labels_setting.order_by','ASC');  
      $query = $this->db->get()->result_array(); 
      // print '<pre>';print_r($query);
      return $query;
  }

  public function get_seprate_vital_master_unique($status='',$print='')
  {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('hms_daycare_vital_labels_setting.*, hms_daycare_branch_vital_labels_setting.setting_name, hms_daycare_branch_vital_labels_setting.setting_value,hms_daycare_branch_vital_labels_setting.order_by,hms_daycare_branch_vital_labels_setting.status,hms_daycare_branch_vital_labels_setting.print_status,hms_daycare_branch_vital_labels_setting.type,hms_daycare_branch_vital_labels_setting.vital_type');
      $this->db->join('hms_daycare_branch_vital_labels_setting', 'hms_daycare_branch_vital_labels_setting.unique_id=hms_daycare_vital_labels_setting.id AND hms_daycare_branch_vital_labels_setting.branch_id = "'.$user_data['parent_id'].'"','left');
      $this->db->from('hms_daycare_vital_labels_setting');
      $this->db->where('hms_daycare_branch_vital_labels_setting.vital_type','1');
      if(!empty($status))
      {
        $this->db->where('hms_daycare_branch_vital_labels_setting.status',1);
      }
      if(!empty($print))
      {
        $this->db->where('hms_daycare_branch_vital_labels_setting.print_status',1);
      }
      $this->db->order_by('hms_daycare_branch_vital_labels_setting.order_by','ASC');  
      $query = $this->db->get()->result_array(); 
      // print '<pre>';print_r($query);
      return $query;
  }
    
    public function save()
    {  
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        //echo "<pre>"; print_r($post['vitals']); die;
        if(!empty($post['data']))
        {    
            $current_date = date('Y-m-d H:i:s');
      if(!empty($post['data']))
      {
              $this->db->where('branch_id',$user_data['parent_id']);
              $this->db->where('gipsa_type',0);
              
              $this->db->delete('hms_daycare_branch_discharge_labels_setting');
            foreach($post['data'] as $key=>$val)
            {   
                if(!empty($val['status']) && $val['status']==1)
                {
                    $status=1;
                }
                else
                {
                    $status=0;
                }
              if(!empty($val['print_status']) && $val['print_status']==1)
              {
                $print_status=1;
              }
              else
              {
                $print_status=0;
              }
                
                $data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$key,
                               "gipsa_type"=>0,
                               "setting_name"=>$val['setting_name'],
                               "setting_value"=>$val['setting_value'],
                               "status"=>$status,
                               "type"=>'common',
                               'print_status'=>$print_status,
                               "order_by"=>$val['order_by'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
                             );
                $this->db->insert('hms_daycare_branch_discharge_labels_setting',$data);

            }
         }
             
         if(!empty($post['vitals']))
         {
            $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->delete('hms_daycare_branch_vital_labels_setting');
            foreach($post['vitals'] as $key=>$val)
            { 
              if(!empty($val['status']) && $val['status']==1)
              {
                $status=1;
              }
              else
              {
                $status=0;
              }
              if(!empty($val['print_status']) && $val['print_status']==1)
              {
                $print_status=1;
              }
              else
              {
                $print_status=0;
              }
              if(!empty($val['vital_type']) && $val['vital_type']==1)
              {
                $vital_type=1;
              }
              else
              {
                $vital_type=0;
              }
              
              $data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$key,
                               "setting_name"=>$val['setting_name'],
                               "setting_value"=>$val['setting_value'],
                               'vital_type'=>$vital_type,
                               "type"=>'vitals',
                               "status"=>$status,
                               'print_status'=>$print_status,
                               "order_by"=>$val['order_by'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
                         );
              $this->db->insert('hms_daycare_branch_vital_labels_setting',$data);
            }
          }
        } 
    }


  public function get_active_master_unique()
  {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('hms_daycare_discharge_labels_setting.*, hms_daycare_branch_discharge_labels_setting.setting_name, hms_daycare_branch_discharge_labels_setting.setting_value,hms_daycare_branch_discharge_labels_setting.order_by,hms_daycare_branch_discharge_labels_setting.status,hms_daycare_branch_discharge_labels_setting.print_status');
      $this->db->join('hms_daycare_branch_discharge_labels_setting', 'hms_daycare_branch_discharge_labels_setting.unique_id=hms_daycare_discharge_labels_setting.id AND hms_daycare_branch_discharge_labels_setting.branch_id = "'.$user_data['parent_id'].'" AND  hms_daycare_branch_discharge_labels_setting.status = 1 AND  hms_daycare_branch_discharge_labels_setting.gipsa_type = 0','left');
      $this->db->from('hms_daycare_discharge_labels_setting');
      $this->db->order_by('hms_daycare_branch_discharge_labels_setting.order_by','ASC');  
      $query = $this->db->get()->result(); 
     // echo $this->db->last_query(); exit;
      return $query;
  }
  public function get_active_vital_master_unique($status='',$print='')
  {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('hms_daycare_vital_labels_setting.*, hms_daycare_branch_vital_labels_setting.setting_name, hms_daycare_branch_vital_labels_setting.setting_value,hms_daycare_branch_vital_labels_setting.order_by,hms_daycare_branch_vital_labels_setting.status,hms_daycare_branch_vital_labels_setting.print_status');
      $this->db->join('hms_daycare_branch_vital_labels_setting', 'hms_daycare_branch_vital_labels_setting.unique_id=hms_daycare_vital_labels_setting.id AND hms_daycare_branch_vital_labels_setting.branch_id = "'.$user_data['parent_id'].'" AND  hms_daycare_branch_vital_labels_setting.status = 1','left');
      $this->db->from('hms_daycare_vital_labels_setting');
      
      if(!empty($status))
      {
        $this->db->where('hms_daycare_branch_vital_labels_setting.status',1);
      }
      if(!empty($print))
      {
        $this->db->where('hms_daycare_branch_vital_labels_setting.print_status',1);
      }
      $this->db->order_by('hms_daycare_branch_vital_labels_setting.order_by','ASC');  
      $query = $this->db->get()->result(); 

      // print '<pre>';print_r($query);
      return $query;
  }
 
   
} 
?>