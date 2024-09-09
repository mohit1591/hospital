<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banking_report_model extends CI_Model {

  
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
    public function branch_bank_report_list($get="")
    { 
            $user_data = $this->session->userdata('auth_users');
            $this->db->select("hms_banking.*,hms_bank_account.account_holder,hms_bank_account.account_no,hms_bank.bank_name"); 
            $this->db->from('hms_banking'); 
            $this->db->where('hms_banking.is_deleted','0');
            $this->db->join('hms_bank_account','hms_bank_account.id=hms_banking.account_id','left');
            $this->db->join('hms_bank','hms_bank.id=hms_bank_account.bank_name','left');
         
            $this->db->where('hms_banking.branch_id',$user_data['parent_id']);
                  
            if(!empty($get['start_date']))
            {
            $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

            $this->db->where('hms_banking.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
            $this->db->where('hms_banking.created_date <= "'.$end_date.'"');
            }
           $query = $this->db->get(); 
            return $query->result();
            
        
    }

    public function banking_according_total($get="",$ids='')
    { 
            $user_data = $this->session->userdata('auth_users');
            $this->db->select("hms_banking.*,sum(hms_banking.amount) as total_amount,hms_bank_account.account_holder,hms_bank_account.account_no,hms_bank.bank_name, CONCAT(hms_bank.bank_name,'/',hms_bank_account.account_no) as name_account_no"); 
            $this->db->from('hms_banking'); 
            $this->db->where('hms_banking.is_deleted','0');
            $this->db->join('hms_bank_account','hms_bank_account.id=hms_banking.account_id','left');
            $this->db->join('hms_bank','hms_bank.id=hms_bank_account.bank_name','left');
            $this->db->where('hms_bank_account.is_deleted','0');
           // $this->db->where('hms_banking.branch_id',$user_data['parent_id']);
            $this->db->where('hms_banking.branch_id IN ('.$ids.')'); 
            $this->db->group_by('hms_banking.account_id');     
            if(!empty($get['start_date']))
            {
            $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

            $this->db->where('hms_banking.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
            $this->db->where('hms_banking.created_date <= "'.$end_date.'"');
            }
           $query = $this->db->get()->result(); 
           return $query;
            //echo $this->db->last_query();die;
            
        
    }

   public function banking_report_list_all_branch($get="",$ids=[])
    { 

         if(!empty($ids))
        { 
            $data=array();
            if($get['branch_id']=='all'){
            $branch_id = implode(',', $ids); 
            }else{
            $branch_id=$get['branch_id'];
            }
            $user_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch.*');
            //$this->db->from('hms_branch');
            $this->db->where('id IN ('.$branch_id.')'); 
            $result= $this->db->get('hms_branch')->result();
            $data_branhcwith_name=array();
            $i = 0;
           foreach($result as $res)
           {

            //$data['bank_name'][]=$res->branch_name;

            $this->db->select("hms_banking.*,hms_bank_account.account_holder,hms_bank_account.account_no,hms_bank.bank_name"); 
            $this->db->where('hms_banking.is_deleted','0');
            $this->db->join('hms_bank_account','hms_bank_account.id=hms_banking.account_id','left');
            $this->db->join('hms_bank','hms_bank.id=hms_bank_account.bank_name','left');
            $this->db->where('hms_banking.branch_id',$res->id); 
            $this->db->where('hms_banking.branch_id != ',0,FALSE);
            if(!empty($get['start_date']))
            {
            $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

            $this->db->where('hms_banking.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
            $this->db->where('hms_banking.created_date <= "'.$end_date.'"');
            }
            $query = $this->db->get('hms_banking')->result_array(); 

           // echo $this->db->last_query();
                if(!empty($query))
                {
                    $data['bank_name'][$i]['branch_name']= $res->branch_name; 
                    $data['bank_name'][$i]['result']= $query; 
                    $i++;
                } 
            }
           return $data;
          

        }
            
        
    }

      public function self_branch_bank_list($get="")
      {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($get))
        {  
             $this->db->select("hms_banking.*,hms_bank_account.account_holder,hms_branch.branch_name,hms_bank_account.account_no,hms_bank.bank_name,hms_branch.id"); 
            $this->db->from('hms_banking'); 
            $this->db->where('hms_banking.is_deleted','0');
            $this->db->join('hms_bank_account','hms_bank_account.id=hms_banking.account_id','left');
            $this->db->join('hms_bank','hms_bank.id=hms_bank_account.bank_name','left');
            $this->db->join('hms_branch','hms_branch.id=hms_banking.branch_id','left');
             $this->db->where('hms_banking.branch_id',$users_data['parent_id']);  
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_banking.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_banking.created_date <= "'.$end_date.'"');
            }
           
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
        } 
    }

    public function self_gst_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if($get['section_type']==1){
                
                $this->db->select("hms_canteen_purchase.*,hms_canteen_vendors.name as vendor_name,hms_canteen_vendors.vendor_id as v_id,hms_canteen_vendors.mobile, (select count(id) from hms_canteen_purchase_to_purchase where purchase_id = hms_canteen_purchase.id) as total_medicine"); 

                $this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_purchase.vendor_id','left'); 

                $this->db->where('hms_canteen_vendors.is_deleted','0'); 
                //$this->db->join('hms_canteen_manuf_company','hms_canteen_manuf_company.id = hms_canteen_master_entry.manuf_company');
                $this->db->where('hms_canteen_purchase.is_deleted','0'); 
                 $this->db->where('hms_canteen_purchase.branch_id',$users_data['parent_id']); 
                  
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_canteen_purchase.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_canteen_purchase.created_date <= "'.$end_date.'"');
            }
             $this->db->from('hms_canteen_purchase');

            }
            if($get['section_type']==2){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_canteen_return.*, hms_canteen_vendors.name as vendor_name, (select count(id) from hms_canteen_return_to_return where purchase_return_id = hms_canteen_return.id) as total_medicine"); 
                $this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_return.vendor_id','left'); 
                $this->db->where('hms_canteen_return.is_deleted','0'); 
                $this->db->where('hms_canteen_vendors.is_deleted','0'); 
                 $this->db->where('hms_canteen_return.branch_id',$users_data['parent_id']);  
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_canteen_return.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_canteen_return.created_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_canteen_return');
                
            }
            if($get['section_type']==3){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_canteen_sale.*, hms_patient.patient_name, (select count(id) from hms_canteen_sale_to_sale where sales_id = hms_canteen_sale.id) as total_medicine,hms_doctors.doctor_name"); 
                $this->db->join('hms_patient','hms_patient.id = hms_canteen_sale.patient_id','left'); 
                $this->db->where('hms_canteen_sale.is_deleted','0'); 
                $this->db->where('hms_patient.is_deleted','0'); 
                $this->db->where('hms_doctors.is_deleted','0'); 
                $this->db->join('hms_doctors','hms_doctors.id = hms_canteen_sale.refered_id','left');
                $this->db->where('hms_canteen_sale.branch_id',$users_data['parent_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_canteen_sale.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_canteen_sale.created_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_canteen_sale');
                
            }
            if($get['section_type']==4){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_canteen_sale_return.*, hms_patient.patient_name, (select count(id) from hms_canteen_sale_return where sales_return_id = hms_canteen_sale_return.id) as total_medicine,hms_doctors.doctor_name"); 
                $this->db->join('hms_patient','hms_patient.id = hms_canteen_sale_return.patient_id','left'); 
                $this->db->where('hms_canteen_sale_return.is_deleted','0'); 
                $this->db->where('hms_patient.is_deleted','0'); 
                $this->db->where('hms_doctors.is_deleted','0'); 
                $this->db->join('hms_doctors','hms_doctors.id = hms_canteen_sale_return.refered_id','left');
                $this->db->where('hms_canteen_sale_return.branch_id',$users_data['parent_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_canteen_sale_return.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_canteen_sale_return.created_date <= "'.$end_date.'"');
            }
             $this->db->from('hms_canteen_sale_return');
                
            }
            
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
          
    }
}
?>