<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_summary_report_model extends CI_Model {

    var $table = 'hms_payment';
    var $column = array('hms_opd_booking.reciept_code','hms_opd_booking.booking_date', 'hms_patient.patient_name','hms_opd_booking_to_particulars.particulars','hms_doctors.doctor_name','hms_opd_booking.total_amount','hms_opd_booking.discount','hms_opd_booking.net_amount','hms_opd_booking.paid_amount','hms_opd_booking.balance','hms_opd_booking.booking_status','hms_opd_booking.modified_date');  
    var $order = array('hms_payment.id' => 'desc');
    //,'hms_department.department'
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    private function _get_datatables_query()
    {
        
        $users_data = $this->session->userdata('auth_users');
        $billing_collection_search_data = $this->session->userdata('billing_summary_search_data');
        

        $this->db->select("hms_patient.patient_name,hms_patient.patient_code, hms_doctors.doctor_name,hms_opd_booking.booking_date,hms_opd_booking.reciept_code as booking_code,hms_payment.net_amount,hms_payment.total_amount, hms_payment.debit,hms_payment.created_date, 
        hms_payment_mode.payment_mode as mode,hms_opd_booking.token_no,(select GROUP_CONCAT(hms_opd_booking_to_particulars.particulars) FROM hms_opd_booking_to_particulars where hms_opd_booking_to_particulars.booking_id = hms_opd_booking.id AND hms_opd_booking_to_particulars.branch_id=".$users_data['parent_id'].") as particular_name");
        
        

            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            
            $this->db->join('hms_opd_booking_to_particulars','hms_opd_booking_to_particulars.booking_id = hms_opd_booking.id','left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
            //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (2,12)','left');
           $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($billing_collection_search_data['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($billing_collection_search_data['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($billing_collection_search_data['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($billing_collection_search_data['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars'])
                )
            {
               $this->db->where('hms_opd_booking_to_particulars.particular',$billing_collection_search_data["particulars"]);
            }
            
            if(isset($billing_collection_search_data['particulars_name']) && !empty($billing_collection_search_data['particulars_name'])
                )
            {
               //$this->db->where('hms_opd_booking_to_particulars.particular',$billing_collection_search_data["particulars_name"]);
               $this->db->where('hms_opd_booking_to_particulars.particulars LIKE "'.$billing_collection_search_data["particulars_name"].'%"');
            }
            
            
         
            $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id',4);  //billing section id 4
            $this->db->where('hms_payment.debit>0');
            //$this->db->order_by('hms_payment.id','DESC');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->group_by('hms_opd_booking.id');
            $this->db->from('hms_payment');
            //$new_self_billing['self_bill_coll'] = $this->db->get()->result();  
            //echo $this->db->last_query();die;


        $i = 0;
       //$this->db->group_by('hms_opd_booking.id');
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
        $billing_collection_search_data = $this->session->userdata('billing_summary_search_data'); 
        
         $this->db->select("hms_patient.patient_name,hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,hms_opd_booking.booking_date,hms_opd_booking.reciept_code as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode,hms_opd_booking.token_no,(select GROUP_CONCAT(hms_opd_booking_to_particulars.particulars) FROM hms_opd_booking_to_particulars where hms_opd_booking_to_particulars.booking_id = hms_opd_booking.id AND hms_opd_booking_to_particulars.branch_id=".$users_data['parent_id'].") as particular_name");
         
         /*,(select GROUP_CONCAT(hms_opd_booking_to_particulars.particulars) FROM hms_opd_booking_to_particulars where hms_opd_booking_to_particulars.booking_id = hms_opd_booking.id AND hms_opd_booking_to_particulars.branch_id=".$users_data['parent_id'].") as particular_name*/

            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            
            $this->db->join('hms_opd_booking_to_particulars','hms_opd_booking_to_particulars.booking_id = hms_opd_booking.id','left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
           //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (2,12)','left');
           
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($billing_collection_search_data['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($billing_collection_search_data['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($billing_collection_search_data['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($billing_collection_search_data['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars'])
                )
            {
               $this->db->where('hms_opd_booking_to_particulars.particular',$billing_collection_search_data["particulars"]);
            }
            
            if(isset($billing_collection_search_data['particulars_name']) && !empty($billing_collection_search_data['particulars_name'])
                )
            {
               //$this->db->where('hms_opd_booking_to_particulars.particular',$billing_collection_search_data["particulars_name"]);
               $this->db->where('hms_opd_booking_to_particulars.particulars LIKE "'.$billing_collection_search_data["particulars_name"].'%"');
            }
            
            
         
            $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id',4);  //billing section id 4
            $this->db->where('hms_payment.debit>0');
            //$this->db->order_by('hms_payment.id','DESC');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            //$result = $this->db->get()->result();  
            //echo $this->db->last_query();die;

            $this->db->group_by('hms_opd_booking.id');
            $new_self_billing['self_bill_coll'] = $this->db->get()->result();  
            //echo $this->db->last_query();die;

        
        //echo $this->db->last_query();die;
            
            
            $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            
            
            
           
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');

             //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (2,12)','left');
            
            if(!empty($billing_collection_search_data['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($billing_collection_search_data['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($billing_collection_search_data['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($billing_collection_search_data['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            
            
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars']) || isset($billing_collection_search_data['particulars_name']) && !empty($billing_collection_search_data['particulars_name'])
                )
            {
              $this->db->join('hms_opd_booking_to_particulars','hms_opd_booking_to_particulars.booking_id = hms_opd_booking.id','left');      
            }
            
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars'])
                )
            {
            
            
               $this->db->where('hms_opd_booking_to_particulars.particular',$billing_collection_search_data["particulars"]);
            }
            
            if(isset($billing_collection_search_data['particulars_name']) && !empty($billing_collection_search_data['particulars_name'])
                )
            {
               //$this->db->where('hms_opd_booking_to_particulars.particular',$billing_collection_search_data["particulars_name"]);
               $this->db->where('hms_opd_booking_to_particulars.particulars LIKE "'.$billing_collection_search_data["particulars_name"].'%"');
            }
            
            
            
            $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.is_deleted','0'); 
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id',4);  //billing section id 4
            $this->db->where('hms_payment.debit>0');
            
            $this->db->group_by('hms_payment.pay_mode');
            
            $this->db->from('hms_payment');
            /*$this->db->get();
            echo $this->db->last_query(); exit;*/
            $new_self_billing['self_bill_coll_payment_mode'] = $this->db->get()->result();  


              /* self bill coll payment mode  */

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