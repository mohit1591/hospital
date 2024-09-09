<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct()
    {
        parent::__construct(); 
    }

   
    public function dashboard_count()
    {
        $result_data=array();
        $user_data = $this->session->userdata('auth_users');
         $post=$this->input->post();
         if(!empty($post))
         {
             $start_date = date('Y-m-d',strtotime($post['start_date']));
             $end_date = date('Y-m-d',strtotime($post['end_date']));
             $start_date =  $start_date.' 00:00:00';
             if(!empty($post['end_date']))
             {
                $end_date = $end_date.' 23:59:59';
             }
             else{
                $end_date =  date('Y-m-d H:i:s');
            }             
         }
         else{
             $start_date =  date('Y-m-d');
             $start_date =  $start_date.' 00:00:00';
             $end_date = date('Y-m-d H:i:s');
         }


         $where = 'created_date  >= "'.$start_date.'" AND created_date <= "'.$end_date.'" AND is_deleted !=2 AND branch_id = "'.$user_data['parent_id'].'"';

        // print_r($where);die();

        $this->db->select("COUNT(id) as total_patient"); 
        
        
            $emp_ids='';
            if($user_data['emp_id']>0)
            {
                if($user_data['collection_type']=='1')
                {
                    $emp_ids= $user_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                 $where .= ' AND created_by = "'.$emp_ids.'"';
                
            }
        $this->db->where($where);
        $this->db->from('hms_patient');
        $result_data['patient_count']= $this->db->get()->row();
        $get=array('start_date'=>$start_date, 'end_date'=>$end_date);
        $total_debit=0;
        $total_balance=0;
        $datas=$this->collection_report_total($get);

        $balance_data=$this->collection_report_total_balance($get);

        if($datas['self_opd_coll']){
            $total_debit+=$datas['self_opd_coll']->debit;
            
        }
         if($datas['self_purchase_coll']){
            $total_debit+=$datas['self_purchase_coll']->debit;
            
        }
         if($datas['self_ambulance_coll']){
            $total_debit+=$datas['self_ambulance_coll']->debit;
            
        }
         if($datas['self_bill_coll']){
            $total_debit+=$datas['self_bill_coll']->debit;
           
        }
         if($datas['med_coll']){
            $total_debit+=$datas['med_coll']->debit;
            
        }
         if($datas['ipd_coll']){
            $total_debit+=$datas['ipd_coll']->debit;
            
        }
          if($datas['path_coll']){
            $total_debit+=$datas['path_coll']->debit;
            
        }
          if($datas['vaccine_coll']){
            $total_debit+=$datas['vaccine_coll']->debit;
            
        }
          if($datas['self_ot_coll']){
            $total_debit+=$datas['self_ot_coll']->debit;
            
        }
        if($datas['self_blood_bank_collection']){
            $total_debit+=$datas['self_blood_bank_collection']->debit;
            
        }
         if($datas['self_day_care_coll']){
            $total_debit+=$datas['self_day_care_coll']->debit;
            
        }


        //////////////////

        if($balance_data['self_opd_coll']){
            
            if(!empty($balance_data['self_opd_coll']->balance))
            {
            $total_balance+=$balance_data['self_opd_coll']->balance-1;
            }
            
        }
         if($balance_data['self_purchase_coll']){
              if(!empty($balance_data['self_purchase_coll']->balance))
            {
           $total_balance+=$balance_data['self_purchase_coll']->balance-1;
            }
           
        }
         if($balance_data['self_ambulance_coll']){
              if(!empty($balance_data['self_ambulance_coll']->balance))
            {
            $total_balance+=$balance_data['self_ambulance_coll']->balance-1;
            }
            
        }
         if($balance_data['self_bill_coll']){
              if(!empty($balance_data['self_bill_coll']->balance))
            {
            $total_balance+=$balance_data['self_bill_coll']->balance-1;
            }
            
        }
         if($balance_data['med_coll']){
               if(!empty($balance_data['med_coll']->balance))
            {
            $total_balance+=$balance_data['med_coll']->balance-1;
            }
            
        }
         if($balance_data['ipd_coll']){
             
            /* if($user_data['parent_id']=='110')
            {
                echo "<pre>"; print_r($balance_data['ipd_coll']); exit;
                
            }*/
            
             if(!empty($balance_data['ipd_coll']->balance))
            {
            $total_balance+=$balance_data['ipd_coll']->balance-1;
            }
            
        }
        //echo $total_balance; die;
          if($balance_data['path_coll']){
              if(!empty($balance_data['path_coll']->balance))
            {
                $total_balance+=$balance_data['path_coll']->balance;
            }
            
        }
        
          if($balance_data['vaccine_coll']){
              if(!empty($balance_data['vaccine_coll']->balance))
            {
           $total_balance+=$balance_data['vaccine_coll']->balance-1;
            }
            
        }
          if($balance_data['self_ot_coll']){
              if(!empty($balance_data['self_ot_coll']->balance))
            {
            $total_balance+=$balance_data['self_ot_coll']->balance-1;
            }
            
        }
        if($balance_data['self_blood_bank_collection']){
            if(!empty($balance_data['self_blood_bank_collection']->balance))
            {
           $total_balance+=$balance_data['self_blood_bank_collection']->balance-1;
            }
            
        }
         if($balance_data['self_day_care_coll']){
             if(!empty($balance_data['self_day_care_coll']->balance))
            {
            $total_balance+=$balance_data['self_day_care_coll']->balance-1;
            }
            
            
        }
        
        $result_data['collection']= array('debit'=>$total_debit, 'balance'=>$total_balance);
        //echo "<pre>";print_r($datas);die();
        return $result_data;
    }

    public function dashboard_module_count()
    {
         $user_data = $this->session->userdata('auth_users');
           $post=$this->input->post();
          if(!empty($post))
             {
                 $start_date = date('Y-m-d',strtotime($post['start_date']));
                 $end_date = date('Y-m-d',strtotime($post['end_date']));
                 $start_date =  $start_date.' 00:00:00';
                 if(!empty($post['end_date']))
                 {
                    $end_date = $end_date.' 23:59:59';
                 }
                 else{
                    $end_date =  date('Y-m-d H:i:s');
                }             
             }
             else{
                 $start_date =  date('Y-m-d');
                 $start_date =  $start_date.' 00:00:00';
                 $end_date = date('Y-m-d H:i:s');
             }

        $where = 'created_date  >= "'.$start_date.'" AND created_date <= "'.$end_date.'" AND is_deleted !=2 AND branch_id ="'.$user_data['parent_id'].'" AND is_deleted=0';
           $emp_ids='';
            if($user_data['emp_id']>0)
            {
                if($user_data['collection_type']=='1')
                {
                    $emp_ids= $user_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                 $where .= ' AND created_by = "'.$emp_ids.'"';
                
            }
        $this->db->where('hms_opd_booking.type=2 AND hms_opd_booking.is_deleted=0 AND '.$where);
        $result['opd_count'] = $this->db->get('hms_opd_booking')->num_rows();        
        $this->db->where($where);
        $result['ipd_count'] = $this->db->get('hms_ipd_booking')->num_rows();
     
        $this->db->where($where);
        $result['path_count'] = $this->db->get('path_test_booking')->num_rows();

        $this->db->where($where);
        $result['ot_count'] = $this->db->get('hms_operation_booking')->num_rows();

        $this->db->where($where);
        $result['phar_count'] = $this->db->get('hms_medicine_sale')->num_rows();

        $this->db->where($where);
        $this->db->where('type',3);
        $result['bill_count'] = $this->db->get('hms_opd_booking')->num_rows();

        return $result;

    }
    
    public function opd_specilization_count()
    {
         $user_data = $this->session->userdata('auth_users');
         $users_data = $this->session->userdata('auth_users');
           $post=$this->input->post();
          if(!empty($post))
             {
                 $start_date = date('Y-m-d',strtotime($post['start_date']));
                 $end_date = date('Y-m-d',strtotime($post['end_date']));
                 $start_date =  $start_date.' 00:00:00';
                 if(!empty($post['end_date']))
                 {
                    $end_date = $end_date.' 23:59:59';
                 }
                 else{
                    $end_date =  date('Y-m-d H:i:s');
                }             
             }
             else{
                 $start_date =  date('Y-m-d');
                 $start_date =  $start_date.' 00:00:00';
                 $end_date = date('Y-m-d H:i:s');
             }

        $where_opd = " AND hms_opd_booking.booking_date  >= '".$start_date."' AND hms_opd_booking.booking_date <= '".$end_date."' AND hms_opd_booking.is_deleted=0 AND hms_opd_booking.type=2 AND hms_opd_booking.branch_id =".$user_data['parent_id'];
           /*$emp_ids='';
            if($user_data['emp_id']>0)
            {
                if($user_data['collection_type']=='1')
                {
                    $emp_ids= $user_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                 $where .= ' AND created_by = "'.$emp_ids.'"';
                
            }*/
        $this->db->select('hms_specialization.specialization,count(hms_opd_booking.id) as total_opd');
        $this->db->from('hms_specialization');
        $this->db->join('hms_opd_booking','hms_opd_booking.specialization_id=hms_specialization.id'.$where_opd);
        
        //$this->db->where('hms_specialization.is_deleted',0);      
        $this->db->where('hms_specialization.branch_id',$user_data['parent_id']);
        
        if( in_array('224',$users_data['permission']['section']) || in_array('225',$users_data['permission']['section']) || in_array('226',$users_data['permission']['section']) ||
            in_array('227',$users_data['permission']['section']) || in_array('228',$users_data['permission']['section']) || in_array('229',$users_data['permission']['section']) || in_array('230',$users_data['permission']['section']) || in_array('231',$users_data['permission']['section']) || in_array('232',$users_data['permission']['section']) || in_array('233',$users_data['permission']['section']) || in_array('234',$users_data['permission']['section']) || in_array('235',$users_data['permission']['section']) || in_array('236',$users_data['permission']['section']) || in_array('237',$users_data['permission']['section']) || in_array('238',$users_data['permission']['section']) || in_array('239',$users_data['permission']['section']) || in_array('240',$users_data['permission']['section']) || in_array('241',$users_data['permission']['section']) || in_array('242',$users_data['permission']['section']) || in_array('243',$users_data['permission']['section']) || in_array('244',$users_data['permission']['section']) || in_array('245',$users_data['permission']['section']) || in_array('246',$users_data['permission']['section']) || in_array('247',$users_data['permission']['section']) )
        {
            //$this->db->or_Where(' branch_id in (0) and default_value=1 )');
           /// $this->db->where('status','1'); 
            $this->db->or_Where('hms_specialization.branch_id in (0) and hms_specialization.default_value=1');
            //$this->db->where('status','1'); 
        }
         /* pedic specialization */

         if(in_array('271',$users_data['permission']['section'])|| in_array('272',$users_data['permission']['section'])|| in_array('273',$users_data['permission']['section']) || in_array('274',$users_data['permission']['section']) || in_array('275',$users_data['permission']['section']) || in_array('276',$users_data['permission']['section']))
        {
            $this->db->or_Where('hms_specialization.branch_id in (0) and hms_specialization.default_value=2');
        }
        /* pedic specialization */
        /* dental specialization */

         if(in_array('277',$users_data['permission']['section'])|| in_array('278',$users_data['permission']['section'])|| in_array('279',$users_data['permission']['section']) || in_array('280',$users_data['permission']['section']) || in_array('281',$users_data['permission']['section']) || in_array('282',$users_data['permission']['section'])||in_array('277',$users_data['permission']['section'])|| in_array('283',$users_data['permission']['section'])|| in_array('284',$users_data['permission']['section']) || in_array('285',$users_data['permission']['section']) || in_array('286',$users_data['permission']['section']) || in_array('287',$users_data['permission']['section']) || in_array('288',$users_data['permission']['section'])|| in_array('289',$users_data['permission']['section'])|| in_array('290',$users_data['permission']['section'])|| in_array('291',$users_data['permission']['section'])|| in_array('292',$users_data['permission']['section'])|| in_array('293',$users_data['permission']['section'])|| in_array('294',$users_data['permission']['section'])|| in_array('295',$users_data['permission']['section']))
        {
            $this->db->or_Where('hms_specialization.branch_id in (0) and hms_specialization.default_value=3');
        }
        /* dental specialization */
       
            /* gyne*/
         if( in_array('299',$users_data['permission']['section']) || in_array('300',$users_data['permission']['section']) || in_array('301',$users_data['permission']['section']) 
         ||in_array('302',$users_data['permission']['section']) || in_array('303',$users_data['permission']['section']) || in_array('304',$users_data['permission']['section']) 
         || in_array('305',$users_data['permission']['section']) || in_array('306',$users_data['permission']['section']) || in_array('307',$users_data['permission']['section']) 
         || in_array('308',$users_data['permission']['section']) || in_array('309',$users_data['permission']['section']) || in_array('310',$users_data['permission']['section']) 
         || in_array('311',$users_data['permission']['section']) || in_array('312',$users_data['permission']['section']) || in_array('313',$users_data['permission']['section']) 
         || in_array('314',$users_data['permission']['section']) || in_array('315',$users_data['permission']['section']) || in_array('316',$users_data['permission']['section']) 
         || in_array('317',$users_data['permission']['section']) )
        {
            $this->db->or_Where('hms_specialization.branch_id in (0) and hms_specialization.default_value=4');
           // $this->db->where('status','1'); 
        }
        
        $this->db->group_by('hms_specialization.id');
        $result= $this->db->get()->result_array();
        //echo $this->db->last_query(); exit;
        //echo "<pre>"; print_r($result); exit;
        $resultrow='';
        if(!empty($result))
        {
            
            foreach($result as $rowd)
            {
               $resultrow .='<li class="list-group-item">'.$rowd['specialization'].'<span class="badge-success" >'.$rowd['total_opd'].'</span></li>'; 
            }
        }
        //echo "<pre>"; print_r($resultrow); exit;
        return $resultrow;

    }
    
    public function dashboard_graph_count()
    {
        $result_data=array();
        $user_data = $this->session->userdata('auth_users');
        $year=date('Y');
        $m=date('m');
         $where = 'branch_id = "'.$user_data['parent_id'].'" AND MONTH(created_date) AND YEAR(created_date)="'.$year.'" GROUP BY y, m order by m';
         $where1 = 'hms_payment.branch_id = "'.$user_data['parent_id'].'"  AND MONTH(hms_payment.created_date) AND YEAR(hms_payment.created_date)="'.$year.'" and MONTH(hms_payment.created_date) <="'.$m.'" GROUP BY y, m order by m';
           $emp_ids='';
            if($user_data['emp_id']>0)
            {
                if($user_data['collection_type']=='1')
                {
                    $emp_ids= $user_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                 $where .= ' AND created_by = "'.$emp_ids.'"';
                 $where1 .= ' AND hms_payment.created_by = "'.$emp_ids.'"';
                
            }
        $this->db->select("COUNT(id) as total_patient, YEAR(created_date) AS y, MONTH(created_date) AS m "); 
        $this->db->where($where);
        $this->db->from('hms_patient');
        $result_data['patient_count']= $this->db->get()->result_array();
       
        $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.total_amount) as total_amount, SUM(hms_payment.debit) as debit, YEAR(hms_payment.created_date) AS y, MONTH(hms_payment.created_date) AS m"); 
        $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
        $this->db->where($where1);          
        $this->db->from('hms_payment');
        $result_data['collection']= $this->db->get()->result_array();
        //echo "<pre>";print_r($result_data);die();
        return $result_data;
    }


    public function collection_report($get='')
    {
        ///1
        $users_data = $this->session->userdata('auth_users');
        $data=array(); 
        if(!empty($get))
        {  
            $this->db->select("hms_payment.id,hms_patient.patient_name, hms_patient.patient_code,hms_payment.debit, 
                (CASE 
                    WHEN hms_payment.section_id=14 THEN 'Day Care' 
                    WHEN hms_payment.section_id=10 THEN 'Blood Bank'
                    WHEN hms_payment.section_id=8 THEN 'OT'
                    WHEN hms_payment.section_id=7 THEN 'Vaccine' 
                    WHEN hms_payment.section_id=1 THEN 'Pathology'
                    WHEN hms_payment.section_id=5 THEN 'IPD'  
                    WHEN hms_payment.section_id=3 THEN 'Medicine Sale' 
                    WHEN hms_payment.section_id=2 THEN 'OPD'
                    WHEN hms_payment.section_id=4 THEN 'Billing'
                    WHEN hms_payment.section_id=13 THEN 'Ambulance'  
                    ELSE '' END) as module");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id AND hms_patient.is_deleted=0');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
            
             $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by',$emp_ids); 
            }
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date']." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date']." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }  
            $this->db->where('hms_payment.debit >0');       
            $this->db->order_by('hms_payment.id','DESC');
            $this->db->limit('20');
            $this->db->from('hms_payment');
            $data['self_opd_coll'] = $this->db->get()->result();
            return $data;
        } 
    
    }


    public function collection_report_total($get='')
    {
        ///1
        $users_data = $this->session->userdata('auth_users');
        $data=array(); 
        if(!empty($get))
        { 
            $users_data = $this->session->userdata('auth_users');
            $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit");
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
                $start_date=$get['start_date'];
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            //$this->db->where('hms_opd_booking.type',3);
            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (2)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->from('hms_payment');
            $data['self_opd_coll'] = $this->db->get()->row(); 


            /// 2 self_purchase_collection
            $branch_id = $users_data['parent_id']; 
             $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit");
 
            $this->db->join('hms_medicine_return','hms_medicine_return.id=hms_payment.parent_id AND hms_medicine_return.is_deleted=0');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_medicine_return.branch_id IN ('.$branch_id.')');
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
             //user collection
         
             //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
             
             $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            
            $this->db->where('hms_payment.section_id IN (12)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->from('hms_payment');
            $data['self_purchase_coll'] = $this->db->get()->row(); 

            /// 3 ambulance collections report

             $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit");  
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
            $this->db->where('hms_ambulance_booking.is_deleted',0); 
            $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);  
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
               //user collection
         
           //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
           
           $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_payment.section_id IN (13)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->from('hms_payment');
            $data['self_ambulance_coll']= $this->db->get()->row();
   
            /// 4 billing collection report

            $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit");
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
           
            //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
            
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (4)');  //billing section id 4
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
            $data['self_bill_coll'] = $this->db->get()->row();  
           // echo $this->db->last_query();die();

            /// 5 medicine collectoin report
          $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit");            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id AND hms_medicine_sale.is_deleted=0');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');

            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (6,10)','left');
   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
           
            //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
            
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
           
            $this->db->where('hms_payment.section_id IN (3)'); 
            $this->db->where('hms_medicine_sale.is_deleted','0');
            $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
          
            $this->db->from('hms_payment');
            $data['med_coll'] = $this->db->get()->row();  
           // echo $this->db->last_query();die(); 

            /// 6 ipd collection report

            $this->db->select("SUM((select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id  AND payment.branch_id = ".$users_data['parent_id'].")) as balance,SUM(hms_payment.debit) as debit"); 
            $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id','left');   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
                $start_date=$get['start_date'];
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
          
            //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
            
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
           
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
           
            $this->db->where('hms_payment.section_id IN (5)'); 
            $this->db->where('hms_ipd_booking.is_deleted','0');
            $this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit>0');
            $this->db->from('hms_payment');
            $data['ipd_coll'] = $this->db->get()->row(); 
            //echo $this->db->last_query();exit;
                  // 7 pathology collection report

            $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit");
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id AND path_test_booking.is_deleted=0');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($get['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($get['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
           
               // $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
               
               $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_payment.section_id',1); 
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit > 0');
            $this->db->from('hms_payment');
            $data['path_coll'] = $this->db->get()->row();  

            // 8 vaccination collection report 
           $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit"); 
            $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id');   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }
            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
    
               // $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
               
               $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
           
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
           
            $this->db->where('hms_payment.section_id IN (7)'); 
            $this->db->where('hms_vaccination_sale.is_deleted','0');
            $this->db->where('hms_vaccination_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
            $this->db->from('hms_payment');
           $data['vaccine_coll'] = $this->db->get()->row(); 

           // 9 ot collection list

           $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit"); 
           $this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            //user collection
        
              //  $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
              
              $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_payment.section_id IN (8)'); 
            $this->db->where('hms_operation_booking.is_deleted','0');
            $this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->from('hms_payment');
            $data['self_ot_coll'] = $this->db->get()->row(); 
            //echo $this->db->last_query();die();

            // 10 bloodbank collection report
           $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit"); 
            $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            //user collection

               // $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
               
               $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
           
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_payment.section_id IN (10)'); 
            $this->db->where('hms_blood_patient_to_recipient.is_deleted','0');
            $this->db->where('hms_blood_patient_to_recipient.branch_id',$users_data['parent_id']);   
            $this->db->where('hms_payment.debit>0');
            $this->db->from('hms_payment');
            $data['self_blood_bank_collection'] = $this->db->get()->row(); 

            // 11  daycare collection report

            $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit");         
            $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
   
               // $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
               $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
           
            $this->db->where('hms_day_care_booking.is_deleted',0); 
            $this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (14)'); 
            $this->db->where('hms_payment.debit > 0');            
            $this->db->from('hms_payment');
            $data['self_day_care_coll'] = $this->db->get()->row(); 
            return $data;
        } 
    
    }


    public function collection_report_total_balance($get='')
    {
        ///1
        $users_data = $this->session->userdata('auth_users');
        $data=array(); 
        if(!empty($get))
        { 
            $users_data = $this->session->userdata('auth_users');
            $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit");
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
                $start_date=$get['start_date'];
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            //$this->db->where('hms_opd_booking.type',3);
            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (2)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->where('hms_payment.balance > 1');
            
            $this->db->from('hms_payment');
            $data['self_opd_coll'] = $this->db->get()->row(); 


            /// 2 self_purchase_collection
            $branch_id = $users_data['parent_id']; 
             $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit");
 
            $this->db->join('hms_medicine_return','hms_medicine_return.id=hms_payment.parent_id AND hms_medicine_return.is_deleted=0');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_medicine_return.branch_id IN ('.$branch_id.')');
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
             //user collection
         
             //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
             
             $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            
            $this->db->where('hms_payment.section_id IN (12)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->where('hms_payment.balance > 1');
            $this->db->from('hms_payment');
            $data['self_purchase_coll'] = $this->db->get()->row(); 

            /// 3 ambulance collections report

             $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit");  
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
            $this->db->where('hms_ambulance_booking.is_deleted',0); 
            $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);  
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
               //user collection
         
           //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
           
           $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_payment.section_id IN (13)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->where('hms_payment.balance > 1');
            $this->db->from('hms_payment');
            $data['self_ambulance_coll']= $this->db->get()->row();
   
            /// 4 billing collection report

            $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit");
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
           
            //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
            
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (4)');  //billing section id 4
            $this->db->where('hms_payment.debit>0');
            $this->db->where('hms_payment.balance > 1');
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
            $data['self_bill_coll'] = $this->db->get()->row();  
           // echo $this->db->last_query();die();

            /// 5 medicine collectoin report
          $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit");            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id AND hms_medicine_sale.is_deleted=0');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');

            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (6,10)','left');
   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
           
            //$this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
            
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
           
            $this->db->where('hms_payment.section_id IN (3)'); 
            $this->db->where('hms_medicine_sale.is_deleted','0');
            $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
            $this->db->where('hms_payment.balance > 1');
          
            $this->db->from('hms_payment');
            $data['med_coll'] = $this->db->get()->row();  
           // echo $this->db->last_query();die(); 

            /// 6 ipd collection report

            $this->db->select("SUM((select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id  AND payment.branch_id = ".$users_data['parent_id'].")) as balance,SUM(hms_payment.debit) as debit"); 
            $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id','left');   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
                $start_date=$get['start_date'];
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
           
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
           
            /*$this->db->where('hms_payment.section_id IN (5)'); 
            $this->db->where('hms_ipd_booking.is_deleted','0');
            $this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit>0');
            $this->db->where('hms_payment.balance > 1');
            $this->db->from('hms_payment');*/
            
            $this->db->where('hms_payment.section_id IN (5)'); 
            $this->db->where('hms_ipd_booking.is_deleted','0');
            $this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.balance>0');
            //$this->db->where('hms_payment.debit>0');
            //$this->db->group_by('hms_payment.id');
            $this->db->from('hms_payment');
            
            $data['ipd_coll'] = $this->db->get()->row();
            /*if($users_data['parent_id']=='110')
            {
               echo $this->db->last_query(); exit;
            }*/
                  // 7 pathology collection report

            $this->db->select('SUM((select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id  AND branch_id = "'.$users_data['parent_id'].'")) as balance,SUM(hms_payment.debit) as debit');
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id AND path_test_booking.is_deleted=0');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($get['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($get['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
           
               // $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
               
               $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_payment.section_id',1); 
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
            //$this->db->where('hms_payment.debit > 0');
            $this->db->where('hms_payment.balance > 1'); //change on 26 jan 2021
            $this->db->from('hms_payment');
            $data['path_coll'] = $this->db->get()->row(); 
            //echo $this->db->last_query(); exit;

            // 8 vaccination collection report 
           $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit"); 
            $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id');   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }
            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
    
               // $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
               
               $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
           
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
           
            $this->db->where('hms_payment.section_id IN (7)'); 
            $this->db->where('hms_vaccination_sale.is_deleted','0');
            $this->db->where('hms_vaccination_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
            $this->db->where('hms_payment.balance > 1');
            $this->db->from('hms_payment');
           $data['vaccine_coll'] = $this->db->get()->row(); 

           // 9 ot collection list

           $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit"); 
           $this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            //user collection
        
              //  $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
              
              $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_payment.section_id IN (8)'); 
            $this->db->where('hms_operation_booking.is_deleted','0');
            $this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->where('hms_payment.balance > 1');
            $this->db->from('hms_payment');
            $data['self_ot_coll'] = $this->db->get()->row(); 
            //echo $this->db->last_query();die();

            // 10 bloodbank collection report
           $this->db->select("SUM(hms_payment.balance) as balance,SUM(hms_payment.debit) as debit"); 
            $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            //user collection

               // $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
               
               $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
           
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
          
            $this->db->where('hms_payment.section_id IN (10)'); 
            $this->db->where('hms_blood_patient_to_recipient.is_deleted','0');
            $this->db->where('hms_blood_patient_to_recipient.branch_id',$users_data['parent_id']);   
            $this->db->where('hms_payment.debit>0');
            $this->db->where('hms_payment.balance > 1');
            $this->db->from('hms_payment');
            $data['self_blood_bank_collection'] = $this->db->get()->row(); 

            // 11  daycare collection report

            $this->db->select("SUM(hms_payment.balance) as balance, SUM(hms_payment.debit) as debit");         
            $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=$get['start_date'];
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=$get['end_date'];
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
   
               // $this->db->where('hms_payment.created_by IN ('.$users_data['id'].')');
               $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
           
            $this->db->where('hms_day_care_booking.is_deleted',0); 
            $this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (14)'); 
            $this->db->where('hms_payment.debit > 0');   
            $this->db->where('hms_payment.balance > 1');         
            $this->db->from('hms_payment');
            $data['self_day_care_coll'] = $this->db->get()->row(); 
            return $data;
        } 
    
    }
    
     public function ambulance_doc_expiry($days='')
    {
        $users_data = $this->session->userdata('auth_users');
        $current_date = date('Y-m-d');
        $expiry_date =  date('Y-m-d', strtotime($current_date. ' + '.$days.' days'));
        $this->db->select('hms_ambulance_vehicle_documents.*, hms_ambulance_document.document,hms_ambulance_vehicle.vehicle_no,hms_ambulance_vehicle.chassis_no'); 
        $this->db->join('hms_ambulance_document','hms_ambulance_document.id=hms_ambulance_vehicle_documents.document_id','left');
        $this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id=hms_ambulance_vehicle_documents.vehicle_id','left');
        $this->db->where('hms_ambulance_vehicle_documents.branch_id',$users_data['parent_id']);
        $this->db->where('hms_ambulance_vehicle.is_deleted',0);
        $this->db->where('hms_ambulance_vehicle.status',1);
          $this->db->where('hms_ambulance_vehicle_documents.status',1);
       // $this->db->where('hms_ambulance_vehicle_documents.expiry_date >=',$current_date);
        $this->db->where('hms_ambulance_vehicle_documents.expiry_date <=',$expiry_date);
       
      //  $this->db->order_by('hms_ambulance_vehicle_documents.id','DESC');
       // $this->db->group_by('hms_ambulance_vehicle_documents.vehicle_id','DESC');
        $query = $this->db->get('hms_ambulance_vehicle_documents');
       // echo $this->db->last_query();die;
        $result = $query->result();
       /* $response=[];
        foreach($result as $res)
        {
            if($res->expiry_date < $current_date)
            {
                
            }
        }
        */
        return $result;
    }
    
    
    public function get_inventory_purchase_due($days='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_purchase_item.*,hms_medicine_vendors.name,(SELECT SUM(credit)-SUM(debit) FROM hms_payment WHERE hms_payment.parent_id=path_purchase_item.id AND hms_payment.vendor_id=path_purchase_item.vendor_id AND hms_payment.section_id=6 AND hms_payment.branch_id='.$users_data['parent_id'].') as due_amount'); 
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left');
        $this->db->where('path_purchase_item.branch_id',$users_data['parent_id']);
        $this->db->where('path_purchase_item.is_deleted',0);
        $query = $this->db->get('path_purchase_item');
        // echo $this->db->last_query();die;
        $result = $query->result();
        return $result;
    }



}

?>