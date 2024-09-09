<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_collection_report_model extends CI_Model {

    var $table = 'hms_payment';
    var $column = array('hms_opd_booking.reciept_code','hms_opd_booking.booking_date', 'hms_patient.patient_name','docs.doctor_name','hms_doctors.doctor_name','hms_opd_booking.total_amount','hms_opd_booking.discount','hms_opd_booking.net_amount','hms_opd_booking.paid_amount','hms_opd_booking.balance','hms_opd_booking.booking_status','hms_opd_booking.modified_date');  
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
        $billing_collection_search_data = $this->session->userdata('billing_collection_search_data');
        

        $this->db->select("hms_opd_booking.id, hms_opd_booking.status, hms_opd_booking.booking_status, hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.id as pay_id, hms_opd_booking.reciept_code,hms_payment.balance as blnce, hms_opd_booking.booking_date, (CASE WHEN hms_payment.balance>0 THEN hms_opd_booking.total_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_opd_booking.discount) ELSE '0.00' END) as discount, hms_opd_booking.type, docs.doctor_name, hms_patient.patient_name,hms_patient.id as patient_new_id,(CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 4 AND payment.parent_id = hms_opd_booking.id AND payment.branch_id = '".$users_data['parent_id']."') ELSE '0.00' END) as balance,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,hms_opd_booking.token_no");  
        $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
        $this->db->join('hms_opd_booking','hms_opd_booking.id = hms_payment.parent_id'); 
        $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id');
         $this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
        $this->db->join('hms_doctors as docs','docs.id = hms_opd_booking.attended_doctor','left');
         
        $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');

        $this->db->join('hms_opd_booking_to_particulars','hms_opd_booking_to_particulars.booking_id = hms_opd_booking.id','left');
        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',4);
        $this->db->where('hms_opd_booking.type',3); 
        $this->db->where('hms_opd_booking.is_deleted != 2');


        if(isset($billing_collection_search_data['branch_id']) && $billing_collection_search_data['branch_id']!=''){
        $this->db->where('hms_opd_booking.branch_id IN ('.$billing_collection_search_data['branch_id'].')');
        }else{
        $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
        }

        if(isset($billing_collection_search_data) && !empty($billing_collection_search_data))
        {
            if(isset($billing_collection_search_data['start_date']) && !empty($billing_collection_search_data['start_date'])
                )
            {
                $start_date = date('Y-m-d',strtotime($billing_collection_search_data['start_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
                //$this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
            }

            if(isset($billing_collection_search_data['end_date']) && !empty($billing_collection_search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($billing_collection_search_data['end_date'])).' 23:59:59';
                //$this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            if(isset($billing_collection_search_data['particular']) && !empty($billing_collection_search_data['particular'])
                )
            {
               $this->db->where('hms_opd_booking_to_particulars.particular = "'.$billing_collection_search_data["particular"].'"');
            }
            
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars'])
                )
            {
               $this->db->where('hms_opd_booking_to_particulars.particulars LIKE "'.$billing_collection_search_data["particulars"].'%"');
            }

            if(isset($billing_collection_search_data['referral_doctor']) && !empty($billing_collection_search_data['referral_doctor'])
                )
            { 
                $this->db->where('hms_doctors.id = "'.$billing_collection_search_data["referral_doctor"].'"');
                $this->db->where('hms_doctors.doctor_type IN (0,2)');
            }


            if(!empty($billing_collection_search_data['insurance_type']))
            {
                $this->db->where('hms_opd_booking.pannel_type',$billing_collection_search_data['insurance_type']);
            }

            if(!empty($billing_collection_search_data['insurance_type_id']))
            {
                $this->db->where('hms_opd_booking.insurance_type_id',$billing_collection_search_data['insurance_type_id']);
            }

            if(!empty($billing_collection_search_data['ins_company_id']))
            {
                $this->db->where('hms_opd_booking.ins_company_id',$billing_collection_search_data['ins_company_id']);
            }
            
            

            

                    
            if(isset($billing_collection_search_data['patient_name']) && !empty($billing_collection_search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$billing_collection_search_data["patient_name"].'%"');
            }
            
            if(isset($billing_collection_search_data['patient_code']) && !empty($billing_collection_search_data['patient_code'])
                )
            { 
                $this->db->where('hms_patient.patient_code LIKE "'.$billing_collection_search_data["patient_code"].'%"');
            }
            
            if(isset($billing_collection_search_data['mobile_no']) && !empty($billing_collection_search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$billing_collection_search_data["mobile_no"].'%"');
            }
            
            if(isset($billing_collection_search_data['attended_doctor']) && !empty($billing_collection_search_data['attended_doctor'])
                )
            { 
                
                $this->db->where('docs.id = "'.$billing_collection_search_data["attended_doctor"].'"');
                $this->db->where('docs.doctor_type IN (1,2)');
            }

            
            /* refered by code */
            if(isset($billing_collection_search_data['referral_hospital']) && $billing_collection_search_data['referred_by']=='1' && !empty($billing_collection_search_data['referral_hospital']))
            {
            $this->db->where('hms_opd_booking.referral_hospital' ,$billing_collection_search_data['referral_hospital']);
            }
            elseif(isset($billing_collection_search_data['refered_id']) && $billing_collection_search_data['referred_by']=='0' && !empty($billing_collection_search_data['refered_id']))
            {
                $this->db->where('hms_opd_booking.referral_doctor' ,$billing_collection_search_data['refered_id']);
            }
            /* refered by code */

            
        }

        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($billing_collection_search_data["employee"]))
        {
            $emp_ids=  $billing_collection_search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
        }
        $this->db->group_by('hms_payment.id');

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
        $billing_collection_search_data = $this->session->userdata('billing_collection_search_data'); 
        
         $this->db->select("hms_opd_booking.id, hms_opd_booking.status, hms_opd_booking.booking_status, hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.id as pay_id, hms_opd_booking.reciept_code,hms_payment.balance as blnce, hms_opd_booking.booking_date, (CASE WHEN hms_payment.balance>0 THEN hms_opd_booking.total_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_opd_booking.discount) ELSE '0.00' END) as discount, hms_opd_booking.type, docs.doctor_name, hms_patient.patient_name,hms_patient.id as patient_new_id,(CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 4 AND payment.parent_id = hms_opd_booking.id AND payment.branch_id = '".$users_data['parent_id']."') ELSE '0.00' END) as balance,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,hms_opd_booking.token_no");  
        $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
        $this->db->join('hms_opd_booking','hms_opd_booking.id = hms_payment.parent_id'); 
        $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id');
         $this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
        $this->db->join('hms_doctors as docs','docs.id = hms_opd_booking.attended_doctor','left');
         
        $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');

        $this->db->join('hms_opd_booking_to_particulars','hms_opd_booking_to_particulars.booking_id = hms_opd_booking.id','left');
        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',4);
        $this->db->where('hms_opd_booking.type',3); 
        $this->db->where('hms_opd_booking.is_deleted != 2');


        if(isset($billing_collection_search_data['branch_id']) && $billing_collection_search_data['branch_id']!=''){
        $this->db->where('hms_opd_booking.branch_id IN ('.$billing_collection_search_data['branch_id'].')');
        }else{
        $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
        }

        if(isset($billing_collection_search_data) && !empty($billing_collection_search_data))
        {
            if(isset($billing_collection_search_data['start_date']) && !empty($billing_collection_search_data['start_date'])
                )
            {
                $start_date = date('Y-m-d',strtotime($billing_collection_search_data['start_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
                //$this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
            }

            if(isset($billing_collection_search_data['end_date']) && !empty($billing_collection_search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($billing_collection_search_data['end_date'])).' 23:59:59';
                //$this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            if(isset($billing_collection_search_data['particular']) && !empty($billing_collection_search_data['particular'])
                )
            {
               $this->db->where('hms_opd_booking_to_particulars.particular = "'.$billing_collection_search_data["particular"].'"');
            }
            
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars'])
                )
            {
               $this->db->where('hms_opd_booking_to_particulars.particulars LIKE "'.$billing_collection_search_data["particulars"].'%"');
            }


            if(!empty($billing_collection_search_data['insurance_type']))
            {
                $this->db->where('hms_opd_booking.pannel_type',$billing_collection_search_data['insurance_type']);
            }

            if(!empty($billing_collection_search_data['insurance_type_id']))
            {
                $this->db->where('hms_opd_booking.insurance_type_id',$billing_collection_search_data['insurance_type_id']);
            }

            if(!empty($billing_collection_search_data['ins_company_id']))
            {
                $this->db->where('hms_opd_booking.ins_company_id',$billing_collection_search_data['ins_company_id']);
            }


            if(isset($billing_collection_search_data['referral_doctor']) && !empty($billing_collection_search_data['referral_doctor'])
                )
            { 
                $this->db->where('hms_doctors.id = "'.$billing_collection_search_data["referral_doctor"].'"');
                $this->db->where('hms_doctors.doctor_type IN (0,2)');
            }
            
            

            

                    
            if(isset($billing_collection_search_data['patient_name']) && !empty($billing_collection_search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$billing_collection_search_data["patient_name"].'%"');
            }
            
            if(isset($billing_collection_search_data['patient_code']) && !empty($billing_collection_search_data['patient_code'])
                )
            { 
                $this->db->where('hms_patient.patient_code LIKE "'.$billing_collection_search_data["patient_code"].'%"');
            }
            
            if(isset($billing_collection_search_data['mobile_no']) && !empty($billing_collection_search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$billing_collection_search_data["mobile_no"].'%"');
            }
            
            if(isset($billing_collection_search_data['attended_doctor']) && !empty($billing_collection_search_data['attended_doctor'])
                )
            { 
                
                $this->db->where('docs.id = "'.$billing_collection_search_data["attended_doctor"].'"');
                $this->db->where('docs.doctor_type IN (1,2)');
            }

            
            /* refered by code */
            if(isset($billing_collection_search_data['referral_hospital']) && $billing_collection_search_data['referred_by']=='1' && !empty($billing_collection_search_data['referral_hospital']))
            {
            $this->db->where('hms_opd_booking.referral_hospital' ,$billing_collection_search_data['referral_hospital']);
            }
            elseif(isset($billing_collection_search_data['refered_id']) && $billing_collection_search_data['referred_by']=='0' && !empty($billing_collection_search_data['refered_id']))
            {
                $this->db->where('hms_opd_booking.referral_doctor' ,$billing_collection_search_data['refered_id']);
            }
            /* refered by code */

            
        }

        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($billing_collection_search_data["employee"]))
        {
            $emp_ids=  $billing_collection_search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
        }
        $this->db->group_by('hms_payment.id');

        //$this->db->order_by('hms_opd_booking.booking_date','ASC');
        $this->db->order_by('hms_payment.id','DESC');
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