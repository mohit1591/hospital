<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ipd_summary_report_model extends CI_Model 
{
    var $table = 'hms_ipd_booking';
    var $column = array('hms_ipd_booking.id','hms_ipd_booking.discharge_bill_no','hms_ipd_booking.ipd_no','hms_patient.patient_name','hms_patient.patient_code','hms_ipd_booking.admission_date','hms_ipd_booking.discharge_date','hms_doctors.doctor_name','hms_patient.mobile_no','hms_ipd_rooms.room_no','hms_ipd_room_to_bad.bad_no','hms_ipd_booking.discount_amount_dis_bill');  
    //var $order = array('id' => 'ipd_discharge_created_date'); 
    var $order = array('discharge_date' => 'desc'); 


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('ipd_summary_report');

        //echo "<pre>";print_r($search); exit;hms_patient.address2,hms_patient.address3,
        $this->db->select("hms_ipd_booking.*,hms_patient.patient_name,hms_patient.age_d,hms_patient.age_m,hms_patient.age_y,hms_patient.age_h,hms_patient.gender,hms_patient.patient_code,hms_patient.address,hms_doctors.doctor_name,hms_patient.mobile_no,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_booking.created_date as createdate,(select sum(payment.credit)  from hms_payment as payment where payment.section_id = 5 AND payment.branch_id = '".$user_data['parent_id']."'  AND payment.parent_id = hms_ipd_booking.id) as ipd_total_amount,(select sum(payment.debit)  from hms_payment as payment where payment.section_id = 5 AND payment.branch_id = '".$user_data['parent_id']."'  AND payment.parent_id = hms_ipd_booking.id) as ipd_paid_amount,(select sum(payment.credit)-sum(payment.debit)  from hms_payment as payment where payment.section_id = 5 AND payment.branch_id = '".$user_data['parent_id']."'  AND payment.parent_id = hms_ipd_booking.id) as ipd_balance_amount,(select sum(expensess.paid_amount)  from hms_expenses as expensess where expensess.type = 10 AND expensess.branch_id = '".$user_data['parent_id']."'  AND expensess.parent_id = hms_ipd_booking.id) as ipd_refund_amount"); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id AND hms_patient.is_deleted!=2','left'); 
        $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left'); 
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
        $this->db->where('hms_ipd_booking.is_deleted','0'); 
        $this->db->group_by('hms_ipd_booking.id'); 
        if($user_data['users_role']==4)
        {
        $this->db->where('hms_ipd_booking.patient_id = "'.$user_data['parent_id'].'"'); 
        }
        elseif($user_data['users_role']==3)
        {
            $this->db->where('hms_ipd_booking.referral_doctor = "'.$user_data['parent_id'].'"'); 
        }
        else
        {
        $this->db->where('hms_ipd_booking.branch_id = "'.$user_data['parent_id'].'"'); 
        
        }
        $this->db->from($this->table); 

        
        /////// Search query start //////////////
        if($user_data['users_role']==3)
        {

        }
        else
        {
            $this->db->where('hms_ipd_booking.discharge_status =1');
        }
        
        if(isset($search) && !empty($search))
        {
            
             if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                //$this->db->where('hms_ipd_booking.created_date >= "'.$start_date.'"');
                $this->db->where('hms_ipd_booking.discharge_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                //$this->db->where('hms_ipd_booking.created_date <= "'.$end_date.'"');
                $this->db->where('hms_ipd_booking.discharge_date <= "'.$end_date.'"');
            }
            if(isset($search['room_no']) && !empty($search['room_no']))
            {
                $this->db->where('hms_ipd_rooms.room_no',$search['room_no']);
            }

            if(isset($search['patient_name']) && !empty($search['patient_name']))
            {

                $this->db->where('hms_patient.patient_name',$search['patient_name']);
            }
            
            if(isset($search['attended_doctor']) && !empty($search['attended_doctor']))
            {

                $this->db->where('hms_ipd_booking.attend_doctor_id',$search['attended_doctor']);
            }
                if(isset($search['ipd_no']) && !empty($search['ipd_no']))
            {

                $this->db->where('hms_ipd_booking.ipd_no',$search['ipd_no']);
            }

            if(isset($search['patient_code']) && !empty($search['patient_code']))

            {
                
              $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
            }

            if(isset($search['adhar_no']) && !empty($search['adhar_no']))

            {
                
              $this->db->where('hms_patient.adhar_no LIKE "'.$search['adhar_no'].'%"');
            }

            if(isset($search['mobile_no']) && !empty($search['mobile_no']))
            {
                $this->db->where('hms_patient.mobile LIKE "'.$search['mobile_no'].'%"');
            }

            if(isset($search['patient_type']) && !empty($search['patient_type']))
            {
                if($search['patient_type']=='-1')
                {
                    $this->db->where('hms_ipd_booking.patient_type IN (1,2)');
                }
                elseif($search['patient_type']=='1')
                {
                    $this->db->where('hms_ipd_booking.patient_type = "1"');
                }
                elseif($search['patient_type']=='2')
                {
                    $this->db->where('hms_ipd_booking.patient_type = "2"');
                }
            }

            

            
            

        }
        
        $emp_ids='';
        if($user_data['emp_id']>0)
        {
            if($user_data['record_access']=='1')
            {
                $emp_ids= $user_data['id'];
            }
        }
        elseif(!empty($search["employee"]) && is_numeric($search['employee']))
        {
            $emp_ids=  $search["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_ipd_booking.created_by IN ('.$emp_ids.')');
        }
        $this->db->order_by('hms_ipd_booking.ipd_discharge_created_date','DESC');
        /////// Search query end //////////////
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



    function search_report_data()
    {
       
       $search = $this->session->userdata('ipd_summary_report');
       $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_ipd_booking.*,hms_patient.*,hms_users.*,hms_ipd_packages.name as package_name,hms_ipd_panel_company.panel_company,hms_ipd_panel_type.panel_type,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category,hms_ipd_booking.created_date as createdate,
            (select sum(payment.credit)  from hms_payment as payment where payment.section_id = 5 AND payment.branch_id = '".$user_data['parent_id']."'  AND payment.parent_id = hms_ipd_booking.id) as ipd_total_amount,

            (select sum(payment.debit)  from hms_payment as payment where payment.section_id = 5 AND payment.branch_id = '".$user_data['parent_id']."'  AND payment.parent_id = hms_ipd_booking.id) as ipd_paid_amount,

            (select sum(payment.credit)-sum(payment.debit)  from hms_payment as payment where payment.section_id = 5 AND payment.branch_id = '".$user_data['parent_id']."'  AND payment.parent_id = hms_ipd_booking.id) as ipd_balance_amount,(select sum(expensess.paid_amount)  from hms_expenses as expensess where expensess.type = 10 AND expensess.branch_id = '".$user_data['parent_id']."'  AND expensess.parent_id = hms_ipd_booking.id) as ipd_refund_amount"); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id AND hms_patient.is_deleted!=2','left');
        $this->db->join('hms_ipd_packages','hms_ipd_packages.id = hms_ipd_booking.package_id','left');
        $this->db->join('hms_ipd_panel_company','hms_ipd_panel_company.id = hms_ipd_booking.panel_name','left');
        $this->db->join('hms_ipd_panel_type','hms_ipd_panel_type.id = hms_ipd_booking.panel_type','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
        
        $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');
        
        
        $this->db->join('hms_users','hms_users.id = hms_ipd_booking.created_by');  
        //$this->db->where('hms_ipd_booking.is_deleted!=','2'); 
        $this->db->where('hms_ipd_booking.is_deleted','0');
        $this->db->group_by('hms_ipd_booking.id'); 
    if($user_data['users_role']==4)
        {
        $this->db->where('hms_ipd_booking.patient_id = "'.$user_data['parent_id'].'"'); 
        }
        elseif($user_data['users_role']==3)
        {
            $this->db->where('hms_ipd_booking.referral_doctor = "'.$user_data['parent_id'].'"'); 
        }
        else
        {
        $this->db->where('hms_ipd_booking.branch_id = "'.$user_data['parent_id'].'"'); 
        
        }
        $this->db->from($this->table); 

        
        /////// Search query start //////////////
        if($user_data['users_role']==3)
        {

        }
        else
        {
            $this->db->where('hms_ipd_booking.discharge_status =1');
        }
        
        if(isset($search) && !empty($search))
        {
            
             if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                //$this->db->where('hms_ipd_booking.created_date >= "'.$start_date.'"');
                $this->db->where('hms_ipd_booking.discharge_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                //$this->db->where('hms_ipd_booking.created_date <= "'.$end_date.'"');
                $this->db->where('hms_ipd_booking.discharge_date <= "'.$end_date.'"');
            }
            if(isset($search['room_no']) && !empty($search['room_no']))
            {
                $this->db->where('hms_ipd_rooms.room_no',$search['room_no']);
            }

            if(isset($search['patient_name']) && !empty($search['patient_name']))
            {

                $this->db->where('hms_patient.patient_name',$search['patient_name']);
            }
            
            if(isset($search['attended_doctor']) && !empty($search['attended_doctor']))
            {

                $this->db->where('hms_ipd_booking.attend_doctor_id',$search['attended_doctor']);
            }
                if(isset($search['ipd_no']) && !empty($search['ipd_no']))
            {

                $this->db->where('hms_ipd_booking.ipd_no',$search['ipd_no']);
            }

            if(isset($search['patient_code']) && !empty($search['patient_code']))

            {
                
              $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
            }

            if(isset($search['adhar_no']) && !empty($search['adhar_no']))

            {
                
              $this->db->where('hms_patient.adhar_no LIKE "'.$search['adhar_no'].'%"');
            }

            if(isset($search['mobile_no']) && !empty($search['mobile_no']))
            {
                $this->db->where('hms_patient.mobile LIKE "'.$search['mobile_no'].'%"');
            }

            if(isset($search['patient_type']) && !empty($search['patient_type']))
            {
                if($search['patient_type']=='-1')
                {
                    $this->db->where('hms_ipd_booking.patient_type IN (1,2)');
                }
                elseif($search['patient_type']=='1')
                {
                    $this->db->where('hms_ipd_booking.patient_type = "1"');
                }
                elseif($search['patient_type']=='2')
                {
                    $this->db->where('hms_ipd_booking.patient_type = "2"');
                }
            }

            

            
            

        }
        
        $emp_ids='';
        if($user_data['emp_id']>0)
        {
            if($user_data['record_access']=='1')
            {
                $emp_ids= $user_data['id'];
            }
        }
        elseif(!empty($search["employee"]) && is_numeric($search['employee']))
        {
            $emp_ids=  $search["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_ipd_booking.created_by IN ('.$emp_ids.')');
        }
                  
        $this->db->order_by('hms_ipd_booking.ipd_discharge_created_date','DESC');
        $query = $this->db->get(); 
        $data= $query->result();
        //echo $this->db->last_query();die;
        return $data;
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




    function get_all_detail_print($ids="")
    {
//hms_patient.address2,hms_patient.address3,
        $result_ipd=array();
        $user_data = $this->session->userdata('auth_users'); //*,hms_patient.*,hms_users.*
        $this->db->select("hms_ipd_booking.*, hms_patient.patient_email, hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_ipd_packages.name as package_name,hms_ipd_panel_company.panel_company,hms_ipd_panel_type.panel_type,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category,hms_payment_mode.payment_mode,hms_ipd_booking.created_date as createdate,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix"); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id','left');
        $this->db->join('hms_ipd_packages','hms_ipd_packages.id = hms_ipd_booking.package_id','left');
        $this->db->join('hms_ipd_panel_company','hms_ipd_panel_company.id = hms_ipd_booking.panel_name','left');
        $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_ipd_booking.payment_mode','left');
        $this->db->join('hms_ipd_panel_type','hms_ipd_panel_type.id = hms_ipd_booking.panel_type','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
        
        $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');
        $this->db->join('hms_users','hms_users.id = hms_ipd_booking.created_by');
        $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_ipd_booking.id AND hms_branch_hospital_no.section_id=3','left');   
        $this->db->where('hms_ipd_booking.is_deleted!=','2'); 
        

        if($user_data['users_role']==4)
        {
            if(!empty($branch_id))
            {
                $this->db->where('hms_ipd_booking.patient_id = "'.$branch_id.'"');  
            }
            else
            {
                $this->db->where('hms_ipd_booking.patient_id = "'.$user_data['parent_id'].'"'); 
            }
        }
        elseif($user_data['users_role']==3)
        {
            $this->db->where('hms_ipd_booking.referral_doctor = "'.$user_data['parent_id'].'"'); 
        }
        else
        {
            if(!empty($branch_id))
            {
                $this->db->where('hms_ipd_booking.branch_id = "'.$branch_id.'"');   
            }
            else
            {
                $this->db->where('hms_ipd_booking.branch_id = "'.$user_data['parent_id'].'"'); 
            }
        }   
        
        
        $this->db->where('hms_ipd_booking.id = "'.$ids.'"');
        $this->db->from($this->table);

        $result_ipd['ipd_list']= $this->db->get()->result();
        //echo $this->db->last_query();

        $this->db->select('hms_signature.*');
        $this->db->join('hms_ipd_booking','hms_ipd_booking.attend_doctor_id = hms_signature.doctor_id');  
        $this->db->where('hms_ipd_booking.id = "'.$ids.'"');
        $this->db->where('hms_signature.is_deleted','0'); 
        $this->db->where('hms_signature.signature_type','2'); 
        
        if($user_data['users_role']==3)
        {
            $this->db->where('hms_signature.doctor_id = "'.$user_data['parent_id'].'"'); 
        }
        else
        {
            $this->db->where('hms_signature.branch_id = "'.$user_data['parent_id'].'"');
        }   
        $this->db->from('hms_signature');
        $result_ipd['signature_data']=$this->db->get()->result();
        //echo $this->db->last_query();
        return $result_ipd;
        
    }




    public function get_by_id($id)
    {
        $this->db->select('hms_ipd_booking.*,hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_ipd_rooms.room_no');
        $this->db->from('hms_ipd_booking'); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id'); 
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
        $this->db->where('hms_ipd_booking.id',$id);
        $this->db->where('hms_ipd_booking.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }
    public function get_vendor_by_id($id)
    {

        $this->db->select('hms_medicine_vendors.*');
        $this->db->from('hms_medicine_vendors'); 
        $this->db->where('hms_medicine_vendors.id',$id);
        //$this->db->where('hms_medicine_vendors.is_deleted','0');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->row_array();
    }

    public function get_medicine_by_purchase_id($id="")
    {
        $this->db->select('hms_medicine_purchase_to_purchase.*');
        $this->db->from('hms_medicine_purchase_to_purchase'); 
        if(!empty($id))
        {
          $this->db->where('hms_medicine_purchase_to_purchase.purchase_id',$id);
        } 
        $query = $this->db->get()->result(); 
        $result = [];
        if(!empty($query))
        {
          foreach($query as $medicine)  
          {
                $ratewithunit1= $medicine->purchase_rate*$medicine->unit1;
                $perpic_rate= 0;
                $ratewithunit2=$perpic_rate*$medicine->unit1;
                $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                $vatToPay = ($tot_qty_with_rate / 100) * $medicine->vat;
                $totalPrice = $tot_qty_with_rate + $vatToPay;
                $total_discount = ($medicine->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;
                
                $result[$medicine->medicine_id] = array('mid'=>$medicine->medicine_id,'conversion'=>$medicine->conversion,'unit1'=>$medicine->unit1,'unit2'=>$medicine->unit2,'perpic_rate'=>$perpic_rate,'batch_no'=>$medicine->batch_no,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'mrp'=>$medicine->mrp,'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'discount'=>$medicine->discount,'vat'=>$medicine->vat, 'purchase_amount'=>$medicine->purchase_rate, 'total_amount'=>$medicine->total_amount); 
          } 
        } 
        
        return $result;
    }


    public function save()
    {
        $user_data = $this->session->userdata('auth_users');

        $post = $this->input->post();
        //echo "<pre>"; print_r($post); exit;
        $data_patient = array(
                "patient_code"=>$post['patient_reg_code'],
                "patient_name"=>$post['name'],
                'simulation_id'=>$post['simulation_id'],
                'branch_id'=>$user_data['parent_id'],
                'adhar_no'=>$post['adhar_no'],
                'gender'=>$post['gender'],
                'age_m'=>$post['age_m'],
                'age_y'=>$post['age_y'],
                'age_d'=>$post['age_d'],
                'address'=>$post['address'],
                'address2'=>$post['address_second'],
                'address3'=>$post['address_third'],
                'mobile_no'=>$post['mobile']
             );
                
        if(!empty($post['data_id']) && $post['data_id']>0)
        { 
            //Update Start
            $booking_detail= $this->get_by_id($post['data_id']);
            $patient_id_new= $this->get_patient_by_id($booking_detail['patient_id']);
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id', $post['patient_id']);
            $this->db->update('hms_patient',$data_patient);
          
            
            if(isset($post['id_number'])){ $panel_id_no=$post['id_number']; }else{ $panel_id_no=''; }
            if(isset($post['company_name'])){ $panel_name=$post['company_name']; }else{ $panel_name=''; }
            if(isset($post['panel_type'])){ $panel_type=$post['panel_type']; }else{ $panel_type=''; }
            if(isset($post['policy_number'])){ $policy_number=$post['policy_number']; }else{ $policy_number=''; }
            if(isset($post['authorization_amount'])){ $authorization_amount=$post['authorization_amount']; }else{ $authorization_amount=''; }
            
                $data = array(
                    "patient_id"=>$post['patient_id'],
                    'branch_id'=>$user_data['parent_id'],
                    'attend_doctor_id'=>$post['attended_doctor'],
                    'mlc'=>$post['mlc'],
                    'payment_mode'=>$post['payment_mode'],
                    //'bank_name'=>$bank_name,
                    //'card_no'=>$card_no,
                    //'cheque_no'=>$cheque_no,
                    //'cheque_date'=>$cheque_date,
                    'remarks'=>$post['remarks'],
                    'panel_id_no'=>$panel_id_no,
                    'room_type_id'=>$post['room_id'],
                    'room_id'=>$post['room_no_id'],
                    'bad_id'=>$post['bed_no_id'],
                    'attend_doctor_id'=>$post['attended_doctor'],
                    'referral_doctor'=>$post['referral_doctor'],

                    'patient_type'=>$post['patient_type'],
                    'panel_name'=>$panel_name,
                    'panel_type'=>$panel_type,
                    'panel_polocy_no'=>$policy_number,
                    'admission_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['admission_time'])),
                    "package_type"=>$post['package'],
                    "package_id"=>$post['package_id'],
                    'authrization_amount'=>$authorization_amount,
                    'admission_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                    'advance_payment'=>$post['advance_deposite'],
                    'referred_by'=>$post['referred_by'],
                     'referral_hospital'=>$post['referral_hospital'],
                    //'transaction_no'=>$transaction_no
                ); 
 
                $this->db->where('id',$post['data_id']);
                $this->db->set('modified_by',$user_data['id']);
                $this->db->set('modified_date',date('Y-m-d H:i:s'));
                $this->db->update('hms_ipd_booking',$data);

                /*add sales banlk detail*/
                /*$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>9,'section_id'=>3));
                $this->db->delete('hms_payment_mode_field_value_acc_section');
                if(!empty($post['field_name']))
                {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                {
                $data_field_value= array(
                'field_value'=>$post['field_name'][$i],
                'field_id'=>$post['field_id'][$i],
                'type'=>9,
                'section_id'=>3,
                'p_mode_id'=>$post['payment_mode'],
                'branch_id'=>$user_data['parent_id'],
                'parent_id'=>$post['data_id'],
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }*/

                /*add sales banlk detail*/

                //Bed Change on update
                if(!empty($post['data_id']))
                {   
                    $this->db->select('*');
                    $this->db->from('hms_ipd_room_to_bad');
                    $this->db->where('ipd_id',$post['data_id']);
                    $query = $this->db->get(); 
                    $result = $query->row_array();
                    $bed_id = $result['id'];
                        if(!empty($bed_id))
                        {
                            $this->db->set('status','0');
                            $this->db->set('ipd_id','0');
                            $this->db->where('id',$bed_id);
                            $this->db->update('hms_ipd_room_to_bad');
                        }
                        $this->db->set('status','1');
                        $this->db->set('ipd_id',$post['data_id']);
                        $this->db->where('id',$post['bed_no_id']);
                        $this->db->update('hms_ipd_room_to_bad');
                }


                
                $this->db->where('ipd_booking_id',$post['data_id']);
                $this->db->delete('hms_ipd_assign_doctor');

                
                
                $count_assigned_doctor= count($post['assigned_doctor_list']);
                for($i=0;$i< $count_assigned_doctor;$i++)
                {
                        $assigned_doctor = array(
                        "branch_id"=>$user_data['parent_id'],
                        'ipd_booking_id'=>$post['data_id'],
                        'doctor_id'=>$post['assigned_doctor_list'][$i],
                        'created_date'=>date('Y-m-d H:i:s')
                     );
                        $this->db->insert('hms_ipd_assign_doctor',$assigned_doctor);
                }
                

            /*
            if(!empty($post['data_id']) && !empty($post['advance_deposite']) && $post['advance_deposite']!='0.00') 
            {
            $advance_patient_charge = array(
                "branch_id"=>$user_data['parent_id'],
                'ipd_id'=>$post['data_id'],
                'patient_id'=>$post['patient_id'],
                'type'=>2,
                'quantity'=>1,
                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                'payment_date'=>date('Y-m-d'),
                'particular'=>'Advance Payment',
                'price'=>$post['advance_deposite'],
                'panel_price'=>$post['advance_deposite'],
                'net_price'=>$post['advance_deposite'],
                'status'=>1,
                'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
            );
            $this->db->insert('hms_ipd_patient_to_charge',$advance_patient_charge);
            }
            */
            //registration charge
            
            //only work on added
            if(!empty($post['data_id']) && !empty($post['reg_charge']) && $post['reg_charge']!='0.00') 
            {
                $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>1));
                    $this->db->delete('hms_ipd_patient_to_charge');

                $registration_patient_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_id'=>$post['data_id'],
                    'patient_id'=>$post['patient_id'],
                    'type'=>1,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                    'particular'=>'Registration Charge',
                    'price'=>$post['reg_charge'],
                    'panel_price'=>$post['reg_charge'],
                    'net_price'=>$post['reg_charge'],
                    'status'=>1,
                    'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
                );
                $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
            }

            if(!empty($post['data_id']) && empty($post['package_id']) && $post['patient_type']=='1') 
            {
                     /* new ipd charge entry code */
                 
                    $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>3));
                    $this->db->delete('hms_ipd_patient_to_charge');

                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->room_type_list(); 
                    $room_charge_type_list = $this->general_model->room_charge_type_list();
                     $charges='';
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],'',$post['patient_type']);
                         $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge']; 
                         

                        $registration_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$post['data_id'],
                                'patient_id'=>$post['patient_id'],
                                'start_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date'])),
                                'type'=>3,
                                'quantity'=>1,
                                'transfer_status'=>1,
                                'particular'=>$charge_type,
                                'price'=>$charges,
                                'panel_price'=>$charges,
                                'net_price'=>$charges,
                                'status'=>1,
                                'created_date'=>date('Y-m-d H:i:s')
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
                        
                    } 
                   }
            }
            elseif(!empty($post['data_id']) && empty($post['package_id']) && $post['patient_type']=='2') 
            {
                    $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>3));
                    $this->db->delete('hms_ipd_patient_to_charge');

                    //panel charges
                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->room_type_list(); 
                    $room_charge_type_list = $this->general_model->room_charge_type_list();
                    
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],$post['company_name'],$post['patient_type']);
                        $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];
                        
                        $patient_charge_type = array(
                                                    "branch_id"=>$user_data['parent_id'],
                                                    'ipd_id'=>$post['data_id'],
                                                    'patient_id'=>$post['patient_id'],
                                                    'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                                                    'type'=>3,
                                                    'quantity'=>1,
                                                    'transfer_status'=>1,
                                                    'particular'=>$charge_type,
                                                    'price'=>$charges,
                                                    'panel_price'=>$charges,
                                                    'net_price'=>$charges,
                                                    'status'=>1,
                                                    'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
                                                );
                        $this->db->insert('hms_ipd_patient_to_charge',$patient_charge_type);
                        
                    } 
                   }
            }
            elseif(!empty($post['data_id']) && !empty($post['package_id']) && $post['patient_type']=='1')
            {
                    $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>4));
                    $this->db->delete('hms_ipd_patient_to_charge');
                    //for package without panel
                    $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
                    $panel_list = $this->package_panel_charge->get_panel_list();
                    $package_list = $this->package_panel_charge->get_package_list('',$post['package_id'],1);
                    if(!empty($package_list[0]))
                    {
                        $package_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$post['data_id'],
                                'patient_id'=>$post['patient_id'],
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                                'type'=>4,
                                'transfer_status'=>1,
                                'quantity'=>1,
                                'particular'=>$package_list[0]['name'],
                                'price'=>$package_list[0]['package_charge'],
                                'panel_price'=>$package_list[0]['package_charge'],
                                'net_price'=>$package_list[0]['package_charge'],
                                'status'=>1,
                                'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$package_patient_charge);
                        
                   
                   }
            }
            elseif(!empty($post['data_id']) && !empty($post['package_id']) && $post['patient_type']=='2')
            {
                    $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>4));
                    $this->db->delete('hms_ipd_patient_to_charge');
                    
                    //for package with panel
                    $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
                    $panel_list = $this->package_panel_charge->get_panel_list();
                    $package_list = $this->package_panel_charge->get_package_list($post['company_name'],$post['package_id'],2);
                    if(!empty($package_list[0]))
                    {
                        $package_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$post['data_id'],
                                'patient_id'=>$post['patient_id'],
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                                'type'=>4,
                                'transfer_status'=>1,
                                'quantity'=>1,
                                'particular'=>$package_list[0]['name'],
                                'price'=>$package_list[0]['package_charge'],
                                'panel_price'=>$package_list[0]['package_charge'],
                                'net_price'=>$package_list[0]['package_charge'],
                                'status'=>1,
                                'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$package_patient_charge);
                        
                   
                   }
            }

                /* new ipd charge entry  code */
                /* //It is due to read only in update 
                $this->db->where('parent_id',$post['data_id']);
                $this->db->where('section_id','5');
                $this->db->where('type','4');
                $this->db->where('patient_id',$post['patient_id']);
                $this->db->delete('hms_payment'); 

                $payment_data = array(
                                'parent_id'=>$post['data_id'],
                                'branch_id'=>$user_data['parent_id'],
                                'section_id'=>'5',  //'section_id'=>'3',
                                'type'=>'4',  //'section_id'=>'3',
                                'hospital_id'=>$post['referral_hospital'],
                                'doctor_id'=>$post['referral_doctor'],
                                'patient_id'=>$post['patient_id'],
                                'credit'=>'',
                                'debit'=>$post['advance_deposite'],//$post['reg_charge'],
                                'pay_mode'=>$post['payment_mode'],
                                'created_date'=>date('Y-m-d h:i:s',strtotime($post['admission_date'])),
                                'created_by'=>$user_data['id']
                             );
                $this->db->insert('hms_payment',$payment_data);
                $ipd_booking_id= $post['data_id'];
            

                
                $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>9,'section_id'=>4));
                $this->db->delete('hms_payment_mode_field_value_acc_section');
                if(!empty($post['field_name']))
                {
                    $post_field_value_name= $post['field_name'];
                    $counter_name= count($post_field_value_name); 
                    for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_field_value= array(
                            'field_value'=>$post['field_name'][$i],
                            'field_id'=>$post['field_id'][$i],
                            'type'=>9,
                            'section_id'=>4,
                            'p_mode_id'=>$post['payment_mode'],
                            'branch_id'=>$user_data['parent_id'],
                            'parent_id'=>$post['data_id'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                        );
                        $this->db->set('created_by',$user_data['id']);
                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                        $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                    }
                }

                ,
                                'patient_id'=>
                */
                /*add sales banlk detail*/

                /* if patient discharge then add dischrage data to */ 
        if(!empty($post['discharge_date']) && $post['discharge_date']!='1970-01-01' && $post['discharge_date']!='1970-01-01 00:00:00')
        {       
                   $users_data = $this->session->userdata('auth_users');
                   $patient_id = $post['patient_id'];
                   $ipd_id = $post['data_id'];
                   $this->db->select('*');
                   $this->db->from('hms_ipd_patient_to_charge');
                   $this->db->where('hms_ipd_patient_to_charge.type',3);
                   $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
                   $query= $this->db->get()->result();

                    foreach($query as $data)
                    {
                          
                        if($data->end_date=='0000-00-00 00:00:00')
                        {

                             $update_data=  array('end_date'=>date('Y-m-d H:i:s',strtotime($post['discharge_date'])));
                             $this->db->where('id',$data->id);
                             $this->db->update('hms_ipd_patient_to_charge',$update_data);
                        }
                    }

                
                $this->db->select('*');
                $this->db->from('hms_ipd_patient_to_charge');
                $this->db->where('hms_ipd_patient_to_charge.type',3);
                $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
                $res= $this->db->get()->result();
                //echo "<pre>";print_r($res);die;
                $res = json_decode(json_encode($res), true);
                
                $new_array=array();
                foreach($res as $data_query)
                {
                    //echo "<pre>";print_r($data_query);die;
                        $date1 = new DateTime(date('Y-m-d',strtotime($data_query['start_date'])));
                        $date2 = new DateTime(date('Y-m-d',strtotime($data_query['end_date'])));
                        $date2->modify("+1 days");
                        $interval = $date1->diff($date2);
                        $days= $interval->days;
                        
                        for($i=0;$i<$days;$i++)
                        { //print
                           $data_query['start_date'] = date('Y-m-d', strtotime($data_query['end_date'])-($i*86400)); 

                             $insert_charge_entry= array('branch_id'=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                );

                             //print '<pre>'; print_r($insert_charge_entry);
                            $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                           
                        }

                        $this->db->where('id',$data_query['id']);
                        $this->db->delete('hms_ipd_patient_to_charge');
        }
        }
        

            //Update End
        }
        else
        {
            //New Booking start users_data
            //echo $post['package_id']; 
            //print '<pre>'; print_r($_POST);
            $created_date = date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s', strtotime(date('d-m-Y').' '.$post['admission_time']));
            $patient_data= $this->get_patient_by_id($post['patient_id']);
            if(count($patient_data)>0)
            {
                $this->db->set('modified_by',$user_data['id']);
                $this->db->set('modified_date',$created_date);
                $this->db->where('id',$post['patient_id']);
                $this->db->update('hms_patient',$data_patient);
                $patient_id= $post['patient_id'];
            }
            else
            {
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',$created_date);
                $this->db->insert('hms_patient',$data_patient); 
                $patient_id= $this->db->insert_id();
            }
            /*if(isset($post['bank_name'])){ $bank_name=$post['bank_name']; }else{ $bank_name=''; }
            if(isset($post['card_no'])){ $card_no=$post['card_no']; }else{ $card_no=''; }
            if(isset($post['cheque_no'])){ $cheque_no=$post['cheque_no']; } else { $cheque_no=''; }
            if(isset($post['cheque_date'])){ $cheque_date=date('Y-m-d',strtotime($post['cheque_date'])); }else{ $cheque_date=''; }
            if(isset($post['transaction_no'])){ $transaction_no=$post['transaction_no']; }else{ $transaction_no=''; }
            */

            if(isset($post['id_number'])){ $panel_id_no=$post['id_number']; }else{ $panel_id_no=''; }
            if(isset($post['company_name'])){ $panel_name=$post['company_name']; }else{ $panel_name=''; }
            if(isset($post['panel_type'])){ $panel_type=$post['panel_type']; }else{ $panel_type=''; }
            if(isset($post['policy_number'])){ $policy_number=$post['policy_number']; }else{ $policy_number=''; }
            if(isset($post['authorization_amount'])){ $authorization_amount=$post['authorization_amount']; }else{ $authorization_amount=''; }
            if(isset($post['package_id'])){ $package_id=$post['package_id']; }else{ $package_id=''; }
            
            $ipd_no = generate_unique_id(22);
            //$reciept_date = date('Y-m-d h:i:s',strtotime($post['admission_date']));
            $data = array(
                    "patient_id"=>$patient_id,
                    'branch_id'=>$user_data['parent_id'],
                    'ipd_no'=>$ipd_no,
                    'attend_doctor_id'=>$post['attended_doctor'],
                    'mlc'=>$post['mlc'],
                    'payment_mode'=>$post['payment_mode'],
                    //'bank_name'=>$bank_name,
                    //'card_no'=>$card_no,
                    //'cheque_no'=>$cheque_no,
                    //'cheque_date'=>$cheque_date,
                    'remarks'=>$post['remarks'],
                    'panel_id_no'=>$panel_id_no,
                    'room_type_id'=>$post['room_id'],
                    'room_id'=>$post['room_no_id'],
                    'bad_id'=>$post['bed_no_id'],
                    'attend_doctor_id'=>$post['attended_doctor'],
                    'referral_doctor'=>$post['referral_doctor'],
                    'patient_type'=>$post['patient_type'],
                    'panel_name'=>$panel_name,
                    'panel_type'=>$panel_type,
                    "package_type"=>$post['package'],
                    "package_id"=>$package_id,
                    'remarks'=>$post['remarks'],
                    'panel_polocy_no'=>$policy_number,
                    'admission_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['admission_time'])),
                    'authrization_amount'=>$authorization_amount,
                    'admission_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                    'advance_payment'=>$post['advance_deposite']
                    //'transaction_no'=>$transaction_no
            ); 
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',$created_date);
            $this->db->insert('hms_ipd_booking',$data);
            $ipd_booking_id= $this->db->insert_id();
            //Update bed status 

                /* genereate receipt number */
            if(in_array('218',$user_data['permission']['section']))
            {
                if($post['advance_deposite']>0)
                {
                $hospital_receipt_no= check_hospital_receipt_no();
                $data_receipt_data= array('branch_id'=>$user_data['parent_id'],
                                    'section_id'=>3,
                                    'parent_id'=>$ipd_booking_id,
                                    'reciept_prefix'=>$hospital_receipt_no['prefix'],
                                    'reciept_suffix'=>$hospital_receipt_no['suffix'],
                                    'created_by'=>$user_data['id'],
                                    'created_date'=>$created_date
                                    );
                $this->db->insert('hms_branch_hospital_no',$data_receipt_data); 
                }
            }
            
                //echo $this->db->last_query();die;
                /* generate receipt number */


            /*add sales banlk detail*/
                if(!empty($post['field_name']))
                {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                {
                $data_field_value= array(
                'field_value'=>$post['field_name'][$i],
                'field_id'=>$post['field_id'][$i],
                'type'=>9,
                'section_id'=>3,
                'p_mode_id'=>$post['payment_mode'],
                'branch_id'=>$user_data['parent_id'],
                'parent_id'=>$ipd_booking_id,
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

                /*add sales banlk detail*/
            if(!empty($ipd_booking_id))
            {
                $this->db->set('status','1');
                $this->db->set('ipd_id',$ipd_booking_id);
                $this->db->where('id',$post['bed_no_id']);
                $this->db->update('hms_ipd_room_to_bad');
            }
            $count_assigned_doctor= count($post['assigned_doctor_list']);
            for($i=0;$i< $count_assigned_doctor;$i++)
            {
                $assigned_doctor = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_booking_id'=>$ipd_booking_id,
                    'doctor_id'=>$post['assigned_doctor_list'][$i],
                    'created_date'=>$created_date
                );
                $this->db->insert('hms_ipd_assign_doctor',$assigned_doctor);
            }


            //insert particular
            //advance amount paid
            if(!empty($ipd_booking_id) && !empty($post['advance_deposite']) && $post['advance_deposite']!='0.00') 
            {
                $advance_patient_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_id'=>$ipd_booking_id,
                    'patient_id'=>$patient_id,
                    'type'=>2,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                    'payment_date'=>date('Y-m-d'),
                    'particular'=>'Advance Payment',
                    'price'=>$post['advance_deposite'],
                    'panel_price'=>$post['advance_deposite'],
                    'net_price'=>$post['advance_deposite'],
                    'status'=>1,
                    'created_date'=>$created_date
                );
                $this->db->insert('hms_ipd_patient_to_charge',$advance_patient_charge);
                $advance_payment_id= $this->db->insert_id(); //charge id will be advance payment id


                 //Payment Details 
                //payment data inserted only when the advance payment is not blank
            
                    $payment_data = array(
                                        'parent_id'=>$ipd_booking_id,
                                        'branch_id'=>$user_data['parent_id'],
                                        'section_id'=>'5',  
                                        'type'=>'4',  
                                        'total_amount'=>$post['advance_deposite'],
                                        'net_amount'=>$post['advance_deposite'],
                                        'paid_amount'=>$post['advance_deposite'],
                                        'hospital_id'=>$post['referral_hospital'],
                                        'doctor_id'=>$post['referral_doctor'],
                                        'patient_id'=>$patient_id,
                                        
                                        'credit'=>'',//$post['advance_deposite'],
                                        'debit'=>$post['advance_deposite'],//'',
                                        'pay_mode'=>$post['payment_mode'],
                                        //'bank_name'=>$bank_name,
                                        //'card_no'=>$card_no,
                                        //'cheque_no'=>$cheque_no,
                                        //'cheque_date'=>$cheque_date,
                                        //'transection_no'=>$transaction_no,
                                        'created_date'=>$created_date,
                                        'created_by'=>$user_data['id']
                                     );

                    

                    $this->db->insert('hms_payment',$payment_data); 

                           /*add sales banlk detail*/
                    if(!empty($post['field_name']))
                    {
                        $post_field_value_name= $post['field_name'];
                        $counter_name= count($post_field_value_name); 
                        for($i=0;$i<$counter_name;$i++) 
                            {
                                $data_field_value= array(
                                'field_value'=>$post['field_name'][$i],
                                'field_id'=>$post['field_id'][$i],
                                'type'=>9,
                                'section_id'=>4,
                                'p_mode_id'=>$post['payment_mode'],
                                'branch_id'=>$user_data['parent_id'],
                                'parent_id'=>$ipd_booking_id,
                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                );
                        $this->db->set('created_by',$user_data['id']);
                        $this->db->set('created_date',$created_date);
                        $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                        }
                    }


            }
            //registration charge
            if(!empty($ipd_booking_id) && !empty($post['reg_charge']) && $post['reg_charge']!='0.00') 
            {
            $registration_patient_charge = array(
                "branch_id"=>$user_data['parent_id'],
                'ipd_id'=>$ipd_booking_id,
                'patient_id'=>$patient_id,
                'type'=>1,
                'quantity'=>1,
                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                'particular'=>'Registration Charge',
                'price'=>$post['reg_charge'],
                'panel_price'=>$post['reg_charge'],
                'net_price'=>$post['reg_charge'],
                'status'=>1,
                'created_date'=>$created_date
            );
            $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
            }

            if(!empty($ipd_booking_id) && empty($post['package_id']) && $post['patient_type']=='1') 
            {
                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->room_type_list(); 
                    $room_charge_type_list = $this->general_model->room_charge_type_list();
                     $charges='';
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],'',$post['patient_type']);
                         $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge']; 
                         

                        $registration_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_booking_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                                'type'=>3,
                                'quantity'=>1,
                                'transfer_status'=>1,
                                'particular'=>$charge_type,
                                'price'=>$charges,
                                'panel_price'=>$charges,
                                'net_price'=>$charges,
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
                        
                    } 
                   }
            }
            elseif(!empty($ipd_booking_id) && empty($post['package_id']) && $post['patient_type']=='2') 
            {
                    //panel charges
                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->room_type_list(); 
                    $room_charge_type_list = $this->general_model->room_charge_type_list();
                    
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],$post['company_name'],$post['patient_type']);
                        $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];
                        //echo "<pre>";print_r($form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]); exit;
                        $registration_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_booking_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                                'type'=>3,
                                'quantity'=>1,
                                'transfer_status'=>1,
                                'particular'=>$charge_type,
                                'price'=>$charges,
                                'panel_price'=>$charges,
                                'net_price'=>$charges,
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
                        
                    } 
                   }
            }
            elseif(!empty($ipd_booking_id) && !empty($post['package_id']) && $post['patient_type']=='1')
            {
                    //for package without panel
                    $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
                    $panel_list = $this->package_panel_charge->get_panel_list();
                    $package_list = $this->package_panel_charge->get_package_list('',$post['package_id'],1);
                    if(!empty($package_list[0]))
                    {
                        $package_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_booking_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                                'type'=>4,
                                'transfer_status'=>1,
                                'quantity'=>1,
                                'particular'=>$package_list[0]['name'],
                                'price'=>$package_list[0]['package_charge'],
                                'panel_price'=>$package_list[0]['package_charge'],
                                'net_price'=>$package_list[0]['package_charge'],
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$package_patient_charge);
                        
                   
                   }
            }
            elseif(!empty($ipd_booking_id) && !empty($post['package_id']) && $post['patient_type']=='2')
            {
                    //for package with panel
                    $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
                    $panel_list = $this->package_panel_charge->get_panel_list();
                    $package_list = $this->package_panel_charge->get_package_list($post['company_name'],$post['package_id'],2);
                    if(!empty($package_list[0]))
                    {
                        $package_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_booking_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                                'type'=>4,
                                'transfer_status'=>1,
                                'quantity'=>1,
                                'particular'=>$package_list[0]['name'],
                                'price'=>$package_list[0]['package_charge'],
                                'panel_price'=>$package_list[0]['package_charge'],
                                'net_price'=>$package_list[0]['package_charge'],
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$package_patient_charge);
                        
                   
                   }
            }

            
            
         /*add sales banlk detail*/ 


        }
            
            return $ipd_booking_id; 
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
            $this->db->update('hms_ipd_booking');
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
            $this->db->update('hms_ipd_booking');
        } 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_purchase');
        $result = $query->result(); 
        return $result; 
    } 

   
    function template_format($data="",$branch_id=''){
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_print_branch_template.*');
        $this->db->where($data);
        if($users_data['users_role']==3 && !empty($branch_id))
        {
            $this->db->where('branch_id  IN ('.$branch_id.')');
        }
        else
        {
            $this->db->where('branch_id  IN ('.$users_data['parent_id'].')');   
        }
        
        $this->db->from('hms_print_branch_template');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();die;
        //print_r($query);exit;
        return $query;

    }

    public function get_patient_by_id($id)
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_patient.*');
        $this->db->from('hms_patient'); 
        $this->db->where('branch_id  IN ('.$users_data['parent_id'].')');
        $this->db->where('hms_patient.id',$id);
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function attended_doctor_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.doctor_type IN (1,2)'); 
        $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        // print '<pre>'; print_r($result);
        return $result; 
    }

    public function referal_doctor_list($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.doctor_type IN (0,2)'); 
        if(!empty($branch_id))
        {
            $this->db->where('hms_doctors.branch_id',$branch_id);
        }
        else
        {
            $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        }
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result; 
    }

    public function assigned_doctor_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.doctor_type IN (1,2)'); 
        $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        // print '<pre>'; print_r($result);
        return $result; 
    }

    public function aasigned_doctor_by_id($id=''){
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        if(!empty($id)){
        $this->db->where('ipd_booking_id',$id);
        }
        $this->db->order_by('id','ASC');
        $this->db->group_by('doctor_id');
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_ipd_assign_doctor');
        $result = $query->result(); 
        // echo $this->db->last_query();die;
        return $result; 
    }

    public function re_admit_patient($ipd_id,$patient_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $update_booking_date= array('discharge_status'=>0);
        $this->db->where(array('id'=>$ipd_id,'patient_id'=>$patient_id));
        $this->db->update('hms_ipd_booking',$update_booking_date);
        if(!empty($ipd_id) && !empty($patient_id))
        {
            $insert_re_admit = array('branch_id'=>$users_data['parent_id'],'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'admit_status'=>0,'created_date'=>date('Y-m-d H:i:s'),'created_by'=>$users_data['id']);
            $this->db->insert('hms_ipd_patient_readmit',$insert_re_admit);
            return 1;
        }   
    }

    public function update_discharge_data($ipd_id,$patient_id)
    {
       $users_data = $this->session->userdata('auth_users');
       $data['CHARGES']="";
       $this->db->select('*');
       $this->db->from('hms_ipd_patient_to_charge');
       $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
       $query= $this->db->get()->result();

        foreach($query as $data)
        {
              
            if($data->end_date=='0000-00-00 00:00:00')
            {
                
                 $update_data=  array('end_date'=>date('Y-m-d H:i:s'));
                 $this->db->where('id',$data->id);
                 $this->db->update('hms_ipd_patient_to_charge',$update_data);
            }
        }
        $update_booking_date= array('discharge_status'=>1);
        $this->db->where(array('id'=>$ipd_id,'patient_id'=>$patient_id));
        $this->db->update('hms_ipd_booking',$update_booking_date);
        /* get discharge table data  */


        /* update room to bed */
            $this->db->select('*');
            $this->db->from('hms_ipd_room_to_bad');
            $this->db->where('ipd_id',$ipd_id);
            $query = $this->db->get(); 
            $result = $query->row_array();

            $bed_id = $result['id'];
            $this->db->set('status','0');
            $this->db->set('ipd_id','0');
            $this->db->where('id',$result['id']);
            $this->db->update('hms_ipd_room_to_bad');

            /* update room to bed */

        $this->db->select('*');
        $this->db->from('hms_ipd_patient_to_charge');
        $this->db->where('hms_ipd_patient_to_charge.type',3);
        $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
        $res= $this->db->get()->result();
        //echo "<pre>";print_r($res);die;
        $res = json_decode(json_encode($res), true);
        
        $new_array=array();
        foreach($res as $data_query)
        {
            //echo "<pre>";print_r($data_query);die;
                $date1 = new DateTime(date('Y-m-d',strtotime($data_query['start_date'])));
                $date2 = new DateTime(date('Y-m-d',strtotime($data_query['end_date'])));
                $date2->modify("+1 days");
                $interval = $date1->diff($date2);
                $days= $interval->days;
                
                for($i=0;$i<$days;$i++)
                { //print
                   $data_query['start_date'] = date('Y-m-d', strtotime($data_query['end_date'])-($i*86400)); 

                     $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                        'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                        'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                        );

                     //print '<pre>'; print_r($insert_charge_entry);
                    $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                   
                }

                $this->db->where('id',$data_query['id']);
                $this->db->delete('hms_ipd_patient_to_charge');
                //echo $this->db->last_query();die;
                

        }


        
        return 1;exit;
        
    }


    public function save_readmit()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();  
        

        if(!empty($post['ipd_id']) && !empty($post['patient_id']))
        {
            $this->db->select('hms_payment.*');
            $this->db->from('hms_payment'); 
            $this->db->where('hms_payment.branch_id',$user_data['parent_id']);
            $this->db->where('hms_payment.patient_id',$post['patient_id']);
            $this->db->where('hms_payment.parent_id',$post['ipd_id']);
            $this->db->where('hms_payment.section_id',5);
            $this->db->where('hms_payment.type',5);
            
            $query = $this->db->get(); 
            $result = $query->row_array();
            $balance =  $result['balance'];
            if($balance > 0)
            {
                //echo "<pre>";print_r($result); exit;
                $registration_patient_charge = array(
                            "branch_id"=>$user_data['parent_id'],
                            'ipd_id'=>$post['ipd_id'],
                            'patient_id'=>$post['patient_id'],
                            'start_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                            'end_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                            'type'=>9,
                            'quantity'=>1,
                            'particular'=>'Discharge Payment',
                            'price'=>$balance,
                            'panel_price'=>$balance,
                            'net_price'=>$balance,
                            'status'=>1,
                            'created_date'=>date('Y-m-d H:i:s')
                        );

                    $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
            }
            else
            {
                //echo "<pre>";print_r($result); exit;
                $registration_patient_charge = array(
                            "branch_id"=>$user_data['parent_id'],
                            'ipd_id'=>$post['ipd_id'],
                            'patient_id'=>$post['patient_id'],
                            'start_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                            'end_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                            'type'=>9,
                            'quantity'=>1,
                            'particular'=>'Discharge Refund',
                            'price'=>$balance,
                            'panel_price'=>$balance,
                            'net_price'=>$balance,
                            'status'=>1,
                            'created_date'=>date('Y-m-d H:i:s')
                        );

                    $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);    
            }

            
                $this->db->where(array('branch_id'=>$user_data['parent_id'],'patient_id'=>$post['patient_id'],'parent_id'=>$post['ipd_id'],'section_id'=>5));
                $this->db->where('type!=',4);
                $this->db->delete('hms_payment');
                
            
            
        }
        //payment
        $this->load->model('general/general_model'); 
        $room_type_list = $this->general_model->room_type_list(); 
        $room_charge_type_list = $this->general_model->room_charge_type_list();
        if(!empty($room_charge_type_list))
        {
            $room_charge_type_list_count = count($room_charge_type_list);
            for($i=0;$i<$room_charge_type_list_count;$i++)
            {
                $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id']);
                $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];

                $registration_patient_charge = array(
                        "branch_id"=>$user_data['parent_id'],
                        'ipd_id'=>$post['ipd_id'],
                        'patient_id'=>$post['patient_id'],
                        'start_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                        'type'=>3,
                        'quantity'=>1,
                        'transfer_status'=>1,
                        'particular'=>$charge_type,
                        'price'=>$charges,
                        'panel_price'=>$charges,
                        'net_price'=>$charges,
                        'status'=>1,
                        'created_date'=>date('Y-m-d H:i:s')
                    );

                $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
                
            } 


       }

        $update_booked_data= array('room_type_id'=>$post['room_id'],'room_id'=>$post['room_no_id'],'bad_id'=>$post['bed_no_id']);
        $this->db->where(array('id'=>$post['ipd_id'],'patient_id'=>$post['patient_id']));
        $this->db->update('hms_ipd_booking',$update_booked_data);
        $this->db->select('*');
        $this->db->from('hms_ipd_room_to_bad');
        $this->db->where('ipd_id',$post['ipd_id']);
        $query = $this->db->get(); 
        $result = $query->row_array();

        $bed_id = $result['id'];
        $this->db->set('status','1');
        $this->db->set('ipd_id',$post['ipd_id']);
        $this->db->where('id',$result['id']);
        $this->db->update('hms_ipd_room_to_bad');


        

    }

    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

        $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
        $this->db->where('hms_payment_mode_field_value_acc_section.type',9);
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.section_id',3);
        $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();

        return $query;
    }

} 
?>