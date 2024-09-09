<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_part_summary_report_model extends CI_Model {

    var $table = 'hms_opd_booking_to_particulars';
    var $column = array('hms_opd_booking_to_particulars.particulars','hms_opd_booking_to_particulars.quantity','hms_opd_booking_to_particulars.amount');  
    var $order = array('hms_opd_booking_to_particulars.id' => 'desc');
    //,'hms_department.department'
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    private function _get_datatables_query()
    {
        
        $users_data = $this->session->userdata('auth_users');
        $billing_collection_search_data = $this->session->userdata('part_collection_report');
        

        $this->db->select("hms_opd_booking_to_particulars.particulars,hms_opd_booking_to_particulars.quantity,hms_opd_booking_to_particulars.amount,sum(hms_opd_booking_to_particulars.quantity) AS PersonalCount,sum(hms_opd_booking_to_particulars.amount) AS PersonalAmount");
        $this->db->join("hms_opd_booking","hms_opd_booking.id=hms_opd_booking_to_particulars.booking_id",'left');
        $this->db->where('hms_opd_booking_to_particulars.branch_id',$users_data['parent_id']);   
            if(!empty($billing_collection_search_data['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($billing_collection_search_data['start_date']))." 00:00:00";

               $this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
            }

            if(!empty($billing_collection_search_data['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($billing_collection_search_data['end_date']))." 23:59:59";
                $this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
            }
            
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars'])
                )
            {
               $this->db->where('hms_opd_booking_to_particulars.particular',$billing_collection_search_data["particulars"]);
            }
         
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.type','3');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_opd_booking_to_particulars.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_opd_booking_to_particulars.id','DESC');
            }
            $this->db->group_by('hms_opd_booking_to_particulars.particular');
            $this->db->from('hms_opd_booking_to_particulars');
           


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

    function get_datatables($branch_id='')
    {
        $this->_get_datatables_query($branch_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }

    function search_report_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $billing_collection_search_data = $this->session->userdata('part_collection_report'); 
        
        $this->db->select("hms_opd_booking_to_particulars.particulars,hms_opd_booking_to_particulars.quantity,hms_opd_booking_to_particulars.amount,sum(hms_opd_booking_to_particulars.quantity) AS PersonalCount,sum(hms_opd_booking_to_particulars.amount) AS PersonalAmount");
        $this->db->join("hms_opd_booking","hms_opd_booking.id=hms_opd_booking_to_particulars.booking_id",'left');
        $this->db->where('hms_opd_booking_to_particulars.branch_id',$users_data['parent_id']);   
            if(!empty($billing_collection_search_data['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($billing_collection_search_data['start_date']))." 00:00:00";

               $this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
            }

            if(!empty($billing_collection_search_data['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($billing_collection_search_data['end_date']))." 23:59:59";
                $this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
            }
            
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars'])
                )
            {
               $this->db->where('hms_opd_booking_to_particulars.particular',$billing_collection_search_data["particulars"]);
            }
         
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.type','3');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_opd_booking_to_particulars.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_opd_booking_to_particulars.id','DESC');
            }
            //$this->db->group_by('hms_opd_booking_to_particulars.id');
            $this->db->from('hms_opd_booking_to_particulars');    //echo $this->db->last_query();die;

            $this->db->group_by('hms_opd_booking_to_particulars.particular');
            $new_self_billing = $this->db->get()->result();  
            //echo $this->db->last_query();die;
            return $new_self_billing;
            //echo $this->db->last_query();die;
            //return $result;
        
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
        $query = $this->db->get('path_religion');
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('path_religion.*');
        $this->db->from('path_religion'); 
        $this->db->where('path_religion.id',$id);
        $this->db->where('path_religion.is_deleted','0');
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
            $this->db->update('path_religion',$data);  
        }
        else{    
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('path_religion',$data);               
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
            $this->db->update('path_religion');
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
            $this->db->update('path_religion');
            //echo $this->db->last_query();die;
        } 
    }
    public function get_billing_collection_report_details($get=array())
    {
        

        if(!empty($get))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            if(!empty($sub_branch_details))
            {
                $child_ids_arr = array_column($sub_branch_details,'id');
                $child_ids = implode(',',$child_ids_arr);
            } 
            $this->db->select("path_expenses.*,path_expenses_category.exp_category"); 
            $this->db->from('path_expenses'); 
            $this->db->join("path_expenses_category","path_expenses.paid_to_id=path_expenses_category.id",'left');
            if(!empty($get['start_date']))
            {
              $this->db->where('path_expenses.expenses_date >= "'.$get['start_date'].'"');
            }

            if(!empty($get['end_date']))
            {
              $this->db->where('path_expenses.expenses_date<= "'.$get['end_date'].'"');   
            }

            if(!empty($get['branch_id']))
            {
              if(is_numeric($get['branch_id']) && $get['branch_id']>0)
              {
                 $this->db->where('path_expenses.branch_id',$get['branch_id']);  
              } 
              else if($get['branch_id']=='all') 
              {
                 $this->db->where('path_expenses.branch_id IN ('.$child_ids.')');  
              }  
            } 
            $query = $this->db->get();
            $result = $query->result();  
            return $result;
        } 
    }

}
?>