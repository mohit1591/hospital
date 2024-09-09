<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_collection_report_model extends CI_Model {

    //var $table = 'hms_medicine_sale';
    var $table = 'hms_payment';
    var $column = array('hms_medicine_sale.sale_no','hms_medicine_sale.sale_date', 'hms_patient.patient_name','hms_medicine_sale.referred_by','hms_medicine_sale.total_amount','hms_medicine_sale.discount','hms_medicine_sale.net_amount','hms_medicine_sale.paid_amount','hms_medicine_sale.balance');  
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
        $search_data = $this->session->userdata('medicine_collection_resport_search_data');
        $this->db->select("hms_medicine_sale.id, hms_medicine_sale.sale_no,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_medicine_sale.sale_date,  (CASE WHEN hms_payment.balance>0 THEN hms_medicine_sale.net_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_medicine_sale.discount) ELSE '0.00' END) as discount,(CASE WHEN hms_payment.balance > 0 THEN (hms_medicine_sale.discount) ELSE '0.00' END) as discount, (CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name, hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 3 AND payment.parent_id = hms_medicine_sale.id) ELSE '0.00' END) as balance");  

        $this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_payment.parent_id AND hms_medicine_sale.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');
        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',3);
        $this->db->where('hms_medicine_sale.is_deleted',0);

        
        if(isset($search_data['col_type']) && !empty($search_data['col_type']) && $search_data['col_type']==1)
        {
            $this->db->where('hms_medicine_sale.ipd_id!=""');
        }

        if(isset($search_data['col_type']) && !empty($search_data['col_type']) && $search_data['col_type']==2)
        {
            $this->db->where('hms_medicine_sale.opd_id!=""');
        }
        
        if(isset($search_data['col_type']) && !empty($search_data['col_type']) && $search_data['col_type']==3)
        {
            $this->db->where('hms_medicine_sale.opd_id=""');
            $this->db->where('hms_medicine_sale.ipd_id=""');
        }
        

        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_medicine_sale.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_medicine_sale.branch_id = "'.$users_data['parent_id'].'"');
        }

        if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                //$this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                //$this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            
            /*if(isset($search_data['refered_id']) && !empty($search_data['refered_id'])
                )
            { 
                $this->db->where('hms_medicine_sale.refered_id = "'.$search_data["refered_id"].'"');
                //$this->db->where('hms_doctors.doctor_type IN (0,2)');
            }
            
            if(isset($search_data['refered_id']) && !empty($search_data['refered_id'])
                )
            { 
                $this->db->where('hms_medicine_sale.refered_id = "'.$search_data["refered_id"].'"');
            }*/

            if($search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_medicine_sale.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif($search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_medicine_sale.refered_id' ,$search_data['refered_id']);
            }
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }
            
            if(isset($search_data['sale_no']) && !empty($search_data['sale_no'])
                )
            { 
                $this->db->where('hms_medicine_sale.sale_no LIKE "'.$search_data["sale_no"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }


            if(!empty($search_data['insurance_type']))
              {
                $this->db->where('hms_patient.insurance_type',$search_data['insurance_type']);
              }

              if(!empty($search_data['insurance_type_id']))
              {
                $this->db->where('hms_patient.insurance_type_id',$search_data['insurance_type_id']);
              }

              if(!empty($search_data['ins_company_id']))
              {
                $this->db->where('hms_patient.ins_company_id',$search_data['ins_company_id']);
              }
            
            
            if(isset($search_data['medicine_company']) &&  !empty($search_data['medicine_company']))
            {
                if(empty($search_data['medicine_name']))
                {
                    $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left');
                    $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
                }

                    $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left'); 
                    $this->db->where('hms_medicine_company.company_name LIKE"'.$search_data['medicine_company'].'%"');
            }
            
            
        }
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
        }
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

    private function _get_datatables_query20171207()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('medicine_collection_resport_search_data');
        //print_r($search_data);die;
        //$this->db->select("hms_medicine_sale.*, hms_doctors.doctor_name, hms_patient.patient_name"); 
        
        //$this->db->select("hms_medicine_sale.id, hms_medicine_sale.sale_no,hms_medicine_sale.balance as s_balance,hms_medicine_sale.paid_amount as s_paid_amount, hms_medicine_sale.sale_date, hms_payment.debit as paid_amount,(CASE WHEN hms_payment.balance>0 THEN hms_medicine_sale.net_amount ELSE '0.00' END) net_amount, hms_medicine_sale.net_amount as s_net_amount,(CASE WHEN hms_payment.balance > 0 THEN (hms_medicine_sale.discount) ELSE '0.00' END) as discount, hms_doctors.doctor_name, hms_patient.patient_name, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 3 AND payment.parent_id = hms_medicine_sale.id) ELSE '0.00' END) as balance");
        $this->db->select("hms_medicine_sale.id, hms_medicine_sale.sale_no,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_medicine_sale.sale_date,  (CASE WHEN hms_payment.balance>0 THEN hms_medicine_sale.net_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_medicine_sale.discount) ELSE '0.00' END) as discount, hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 3 AND payment.parent_id = hms_medicine_sale.id) ELSE '0.00' END) as balance");  

        $this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_payment.parent_id AND hms_medicine_sale.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');

        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',3);
        $this->db->where('hms_medicine_sale.is_deleted',0);

        
        //$this->db->where('hms_medicine_sale.booking_status',1); 
            
       
        
        /*if(isset($search_data) && !empty($search_data) && !empty($search_data['branch_id']))
        {  
             $this->db->where('hms_medicine_sale.branch_id',$search_data['branch_id']);
        }
        else
        {
            $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
        } */


        /*if(isset($search_data['branch_id']) && !empty($search_data['branch_id'])
                )
        {
            
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            $sub_branch_id = array_column($sub_branch_details, 'id'); 
            if($search_data['branch_id']=='all')
            {
                $sub_ids = implode(',', $sub_branch_id);
                if(!empty($sub_ids))
                {
                   $sub_ids = $users_data['parent_id'].','.$sub_ids;   
                }
                else
                {
                    $sub_ids = $users_data['parent_id'];
                }
                
                $this->db->where('hms_medicine_sale.branch_id IN ('.$sub_ids.')');
            }
            else
            {
                $this->db->where('hms_medicine_sale.branch_id',$search_data['branch_id']);
            }

        }
        else
        {
            $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
            
        }    */

        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_medicine_sale.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_medicine_sale.branch_id = "'.$users_data['parent_id'].'"');
        }

        if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                //$this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                //$this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            
            if(isset($search_data['refered_id']) && !empty($search_data['refered_id'])
                )
            { 
                $this->db->where('hms_medicine_sale.refered_id = "'.$search_data["refered_id"].'"');
                //$this->db->where('hms_doctors.doctor_type IN (0,2)');
            }
            
            if(isset($search_data['refered_id']) && !empty($search_data['refered_id'])
                )
            { 
                $this->db->where('hms_medicine_sale.refered_id = "'.$search_data["refered_id"].'"');
            }
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }
            
            if(isset($search_data['sale_no']) && !empty($search_data['sale_no'])
                )
            { 
                $this->db->where('hms_medicine_sale.sale_no LIKE "'.$search_data["sale_no"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }
            
            
        }
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
        $search_data = $this->session->userdata('medicine_collection_resport_search_data'); 
        

        $this->db->select("hms_medicine_sale.id, hms_medicine_sale.sale_no,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_medicine_sale.sale_date,  (CASE WHEN hms_payment.balance>0 THEN hms_medicine_sale.net_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_medicine_sale.discount) ELSE '0.00' END) as discount, (CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name, hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 3 AND payment.parent_id = hms_medicine_sale.id) ELSE '0.00' END) as balance");  

        $this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_payment.parent_id'); 
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');

        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',3);
        $this->db->where('hms_medicine_sale.is_deleted',0);
        
        if(isset($search_data['col_type']) && !empty($search_data['col_type']) && $search_data['col_type']==1)
        {
            $this->db->where('hms_medicine_sale.ipd_id!=""');
        }

        if(isset($search_data['col_type']) && !empty($search_data['col_type']) && $search_data['col_type']==2)
        {
            $this->db->where('hms_medicine_sale.opd_id!=""');
        }
        
        if(isset($search_data['col_type']) && !empty($search_data['col_type']) && $search_data['col_type']==3)
        {
            $this->db->where('hms_medicine_sale.opd_id=""');
            $this->db->where('hms_medicine_sale.ipd_id=""');
        }
        
        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_medicine_sale.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_medicine_sale.branch_id = "'.$users_data['parent_id'].'"');
        }    

        if(isset($search_data) && !empty($search_data))
        {
             if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                //$this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
                  $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                //$this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
                 $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            
           /* if(isset($search_data['refered_id']) && !empty($search_data['refered_id'])
                )
            { 
                $this->db->where('hms_medicine_sale.refered_id = "'.$search_data["refered_id"].'"');
                //$this->db->where('hms_doctors.doctor_type IN (0,2)');
            }*/

            if($search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_medicine_sale.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif($search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_medicine_sale.refered_id' ,$search_data['refered_id']);
            }
            
            
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }

            if(!empty($search_data['insurance_type']))
              {
                $this->db->where('hms_patient.insurance_type',$search_data['insurance_type']);
              }

              if(!empty($search_data['insurance_type_id']))
              {
                $this->db->where('hms_patient.insurance_type_id',$search_data['insurance_type_id']);
              }

              if(!empty($search_data['ins_company_id']))
              {
                $this->db->where('hms_patient.ins_company_id',$search_data['ins_company_id']);
              }
            
            if(isset($search_data['sale_no']) && !empty($search_data['sale_no'])
                )
            { 
                $this->db->where('hms_medicine_sale.sale_no LIKE "'.$search_data["sale_no"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }

            
            
        }
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
        }
        //$this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }

    function search_report_data20171207()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('medicine_collection_resport_search_data'); 
        /*$this->db->select("hms_medicine_sale.*, hms_doctors.doctor_name, hms_patient.patient_name"); 
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id');
        //$this->db->join('hms_department','hms_department.id = hms_opd_booking.dept_id'); //, hms_department.department
        $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
        //$this->db->where('hms_medicine_sale.type',0); 
        $this->db->where('hms_medicine_sale.is_deleted',0); */
        //$this->db->where('hms_medicine_sale.booking_status',1); 
        
          /*$this->db->select("hms_medicine_sale.id, hms_medicine_sale.sale_no,   hms_payment.debit as paid_amount, hms_medicine_sale.sale_date,  (CASE WHEN hms_payment.balance>0 THEN hms_medicine_sale.net_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_medicine_sale.discount) ELSE '0.00' END) as discount,  hms_doctors.doctor_name, hms_patient.patient_name, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 3 AND payment.parent_id = hms_medicine_sale.id) ELSE '0.00' END) as balance"); */ 

        $this->db->select("hms_medicine_sale.id, hms_medicine_sale.sale_no,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_medicine_sale.sale_date,  (CASE WHEN hms_payment.balance>0 THEN hms_medicine_sale.net_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_medicine_sale.discount) ELSE '0.00' END) as discount, hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 3 AND payment.parent_id = hms_medicine_sale.id) ELSE '0.00' END) as balance");  

        $this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_payment.parent_id'); 
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');

        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',3);
        $this->db->where('hms_medicine_sale.is_deleted',0);
          
        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_medicine_sale.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_medicine_sale.branch_id = "'.$users_data['parent_id'].'"');
        }    

        if(isset($search_data) && !empty($search_data))
        {
             if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                //$this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
                  $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                //$this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
                 $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            
            if(isset($search_data['refered_id']) && !empty($search_data['refered_id'])
                )
            { 
                $this->db->where('hms_medicine_sale.refered_id = "'.$search_data["refered_id"].'"');
                //$this->db->where('hms_doctors.doctor_type IN (0,2)');
            }
            
            
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }
            
            if(isset($search_data['sale_no']) && !empty($search_data['sale_no'])
                )
            { 
                $this->db->where('hms_medicine_sale.sale_no LIKE "'.$search_data["sale_no"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }
            
        }
        //$this->db->limit($_POST['length'], $_POST['start']);
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
  

    public function get_medicine_collection_list_details($get=array())
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
            if(!empty($get['from_date']))
            {
              $this->db->where('path_expenses.expenses_date >= "'.$get['from_date'].'"');
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