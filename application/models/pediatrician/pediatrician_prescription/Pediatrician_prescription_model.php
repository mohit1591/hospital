<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_prescription_model extends CI_Model 
{
  	 var $table = 'hms_pediatrician_growth_prescription';
   var $column = array('hms_pediatrician_growth_prescription.id','hms_pediatrician_growth_prescription.date_to_visit','hms_patient.patient_name','hms_pediatrician_growth_prescription.oedema','hms_pediatrician_growth_prescription.sex','hms_pediatrician_growth_prescription.measured','hms_patient.dob','hms_patient.age_y','hms_pediatrician_growth_prescription.muac','hms_pediatrician_growth_prescription.head_circumference','hms_pediatrician_growth_prescription.triceps_skinfold','hms_pediatrician_growth_prescription.subscapular_skinfold','hms_pediatrician_growth_prescription.status','hms_pediatrician_growth_prescription.height','hms_pediatrician_growth_prescription.weight','hms_pediatrician_growth_prescription.created_date','hms_pediatrician_growth_prescription.modified_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

     // Function to common Insert
    public function common_insert($table_name, $data_array)
    {
        $this->db->insert($table_name,$data_array);
        //echo $this->db->last_query();die;
        return $this->db->insert_id();
    }
 
      // Function to common Insert
      public function insert_all($table_name, $data_array)
        {
            $this->db->insert($table_name,$data_array);
          
        }

         // Function to common update
    public function common_update($table_name,$data_array,$where_condition="")
    {
        if($where_condition!="")
        {
            $this->db->where(''.$where_condition.'');
        }
        $this->db->limit('1');
        $this->db->update($table_name,$data_array);
        return ($this->db->affected_rows() > 0) ? 200 : 0; 
    }

    private function _get_datatables_query($patient_id='')
    {
        $users_data = $this->session->userdata('auth_users');
         //$post= $this->input->post();
        //print_r($post);
        $this->db->select("hms_pediatrician_growth_prescription.*,hms_patient.id as pat_id,hms_patient.patient_name,hms_opd_booking.booking_code as opd_no"); 
        $this->db->from($this->table); 
        $this->db->join('hms_patient','hms_patient.id=hms_pediatrician_growth_prescription.patient_id','Left');
        $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_pediatrician_growth_prescription.booking_id','left');
        //$this->db->where('hms_pediatrician_growth_prescription.booking_id ="'.$booking_id.'"');
        $this->db->where('hms_pediatrician_growth_prescription.patient_id ="'.$patient_id.'"');
        $this->db->where('hms_pediatrician_growth_prescription.is_deleted','0');
        $this->db->where('hms_pediatrician_growth_prescription.branch_id = "'.$users_data['parent_id'].'"');
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

    function get_datatables($patient_id='')
    {
        $this->_get_datatables_query($patient_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();  
        //echo $this->db->last_query();die;
        return $query->result();
    }

function get_datatables_growth()
{
    $user_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_pediatrician_vaccine_prescription.*,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.vaccination_code,hms_pedic_age_vaccine_master.title');   
      $this->db->join('hms_pedic_age_vaccine_master','hms_pedic_age_vaccine_master.id = hms_pediatrician_vaccine_prescription.age_id');
      $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_pediatrician_vaccine_prescription.vaccine_id');
      $this->db->where('hms_pediatrician_vaccine_prescription.branch_id = "'.$user_data['parent_id'].'"');
      $this->db->where('hms_pediatrician_vaccine_prescription.booking_id = "'.$booking_id.'"');
      $query = $this->db->get('hms_pediatrician_vaccine_prescription');
      $result = $query->result(); 
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
    
    public function get_by_id($id,$growth_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_pediatrician_growth_prescription.*');
        $this->db->from('hms_pediatrician_growth_prescription'); 
        //$this->db->join('hms_simulation sim','sim.id=dnr.simulation_id','Left');
        //$this->db->join('hms_blood_group bg', 'bg.id=dnr.blood_group_id','left');
        //$this->db->join('hms_blood_preferred_reminder_service rs', 'rs.id=reminder_service_id','left');
        $this->db->where('hms_pediatrician_growth_prescription.branch_id',$branch_id);
        $this->db->where('hms_pediatrician_growth_prescription.booking_id',$id);
        $this->db->where('hms_pediatrician_growth_prescription.id',$growth_id);
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function get_growth_pres_by_id($booking_id='',$patient_id="",$growth_id='')
    {
    	$users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_pediatrician_growth_prescription.*');
        $this->db->from('hms_pediatrician_growth_prescription'); 
        $this->db->where('hms_pediatrician_growth_prescription.branch_id',$branch_id);
        $this->db->where('hms_pediatrician_growth_prescription.booking_id',$booking_id);
        $this->db->where('hms_pediatrician_growth_prescription.patient_id',$patient_id);
         $this->db->where('hms_pediatrician_growth_prescription.id',$growth_id);
        $query = $this->db->get();
        //echo $this->db->last_query();die; 
        return $query->row_array();
    }

    public function get_by_opd_id($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_opd_booking.*,hms_patient.patient_code,hms_patient.patient_name,hms_patient.adhar_no,hms_patient.mobile_no,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.gender,hms_patient.dob');
        $this->db->from('hms_opd_booking'); 
        $this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','Left');
        $this->db->where('hms_opd_booking.branch_id',$branch_id);
        $this->db->where('hms_opd_booking.id',$id);
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function delete($id)
    {
        if($id!="" && !empty($id))
        {
            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',1);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$id);
            $this->db->update('hms_pediatrician_growth_prescription');

        }
    }


    public function deleteall($ids)
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
            $this->db->update('hms_pediatrician_growth_prescription');
            //echo $this->db->last_query();die;
        }
    }

    public function date_age_list($date_select="")
    {
         $date_new= date('d-m-Y',strtotime($date_select));

        //ECHO $curent_date= strtotime(date('d-m-Y'));
        $users_data = $this->session->userdata('auth_users');
        $end_date_interval=array();
        $start_date_interval=array();
        $this->db->select('hms_pedic_age_vaccine_master.id,hms_pedic_age_vaccine_master.title,hms_pedic_age_vaccine_master.start_age,hms_pedic_age_vaccine_master.end_age,hms_pedic_age_vaccine_master.start_age_type,hms_pedic_age_vaccine_master.end_age_type');
        $this->db->from('hms_pedic_age_vaccine_master');
        $this->db->where('hms_pedic_age_vaccine_master.is_deleted=0');
        $this->db->where('hms_pedic_age_vaccine_master.status=1');
        $this->db->where('hms_pedic_age_vaccine_master.branch_id',$users_data['parent_id']);
        //$this->db->order_by('hms_pedic_age_vaccine_master.start_age','ASC');
        //$this->db->order_by('CAST(start_age as INT)','ASC');
        //$this->db->order_by('CAST(end_age as INT)','ASC');
        $this->db->order_by('start_age_type','ASC');
       // $this->db->order_by('CAST(start_age as INT)','ASC');
        $this->db->order_by('end_age_type','ASC');
        //$this->db->order_by('CAST(end_age as INT)','ASC');
        $result_age_list=  $this->db->get()->result();
        $i=0;
       foreach($result_age_list as $age_list)
        {
           if($age_list->start_age_type==1 && $age_list->end_age_type==1)  //1 for days
           {
                 if($age_list->start_age!='' && $age_list->title!=0)
                {

                  $end_date_interval[$i]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->start_age.' day'));
                  
                }
                else
                {
                  $end_date_interval[$i]['start_age']='';  
                }
                if($age_list->end_age!='' && strtoupper($age_list->title)=='BIRTH')
                {

                  $end_date_interval[$i]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->end_age.' day'));
                }
                else
                {
                    $end_date_interval[$i]['end_age']='';
                }
               
           }

           //2 for week
           if($age_list->start_age_type==2 && $age_list->end_age_type==2)
           {
                if($age_list->start_age!=0 && $age_list->start_age!='')
                {
                  $end_date_interval[$i]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->start_age.' week'));
                  
                }
                else
                {
                    $end_date_interval[$i]['start_age']='';
                }
                if($age_list->end_age!='' && $age_list->end_age!=0)
                {

                  $end_date_interval[$i]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->end_age.' week'));
                }
                else
                {
                  $end_date_interval[$i]['end_age']='';
                }
               
            }
          
            if($age_list->start_age_type==3 && $age_list->end_age_type==3)
           {
                if($age_list->start_age!=0 && $age_list->start_age!='')
                {

                  $end_date_interval[$i]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->start_age.' month'));
                  
                }
                else
                {
                  $end_date_interval[$i]['start_age']='';  
                }
                if($age_list->end_age!='' && $age_list->end_age!=0)
                {

                  $end_date_interval[$i]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->end_age.' month'));
                }
                else
                {
                  $end_date_interval[$i]['end_age']='';
                }
               
            }//3 months
           

            if($age_list->start_age_type==4 && $age_list->end_age_type==4)
            {
                if($age_list->start_age!=0 && $age_list->start_age!='')
                {
                  $end_date_interval[$i]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->start_age.' year'));
                  
                }
                else
                {
                  $end_date_interval[$i]['start_age']='';  
                }
                if($age_list->end_age!='' && $age_list->end_age!=0)
                {

                  $end_date_interval[$i]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->end_age.' year'));
                }
                else
                {
                  $end_date_interval[$i]['end_age']='';
                }
               
             }//4 week

           
             $i++;
        }  
       // $arr= array_merge($start_date_interval,$end_date_interval);
      
        return $end_date_interval;

    }

  public function check_stock_avability($id=""){
      //if(!empty($batch_no)){
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id  and v_id="'.$id.'") as avl_qty,hms_vaccination_stock.mrp,hms_vaccination_stock.discount');   
    $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_stock.v_id');
    $this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
   // $this->db->where('hms_vaccination_stock.batch_no',$batch_no);
    
    $this->db->where('hms_vaccination_stock.v_id',$id);
     //$this->db->group_by('hms_vaccination_stock.batch_no');
    $query = $this->db->get('hms_vaccination_stock');
    //echo $this->db->last_query();die;
        $result = $query->row(); 
       
         return $result;
     // }
    }

    public function save_vaccine_pres()
    {
        $user_data = $this->session->userdata('auth_users');
        $post= $this->input->post();

       $sess_medicine_list= $this->session->userdata('billing_vaccine_ids');
       $data_pres=array( 'branch_id'=>$user_data['parent_id'],
                         'patient_id'=>$post['patient_id'],
                         'booking_id'=>$post['booking_id'],
                         'attended_doctor'=>$post['attended_doctor'],
                         'vaccination_date_time'=>date('Y-m-d H:i:s',strtotime($post['vaccination_date_time'])),
                         'total_amount'=>$post['total_amount'],
                         'paid_amount'=>$post['pay_amount'],
                         'balance'=>$post['balance_due'],
                         'discount_percent'=>$post['discount_percent'],
                         'discount_amount'=>$post['discount_amount'],
                         'payment_mode'=>$post['payment_mode'],
                         'net_amount'=>$post['net_amount'],
                         );
        if(isset($post['data_id']) &&  !empty($post['data_id']))
        {
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id', $post['data_id']);
            $this->db->update('hms_pediatrician_vaccine_prescription',$data_pres);

            $sess_medicine_list= $this->session->userdata('billing_vaccine_ids');
           
            $this->db->where('vaccine_pres_id',$post['data_id']);
            $this->db->delete('hms_pediatrician_vaccine_prescription_to_vaccine');

            /* payment table entry */

             $this->db->where('parent_id',$post['data_id']);
              $this->db->where('section_id','7');
              $this->db->where('type','8');
              $this->db->where('balance>0');
              $this->db->where('patient_id',$post['patient_id']);
              $query_d_pay = $this->db->get('hms_payment');
              $row_d_pay = $query_d_pay->result();
              //get ids and update the id and created date for balance clearence payment 
              if(!empty($row_d_pay))
              {
                  foreach($row_d_pay as $row_d)
                  {
                      $payment_data = array(
                        'parent_id'=>$post['data_id'],
                        'branch_id'=>$user_data['parent_id'],
                        'section_id'=>'7',
                        'type'=>'8',
                        'doctor_id'=>$post['attended_doctor'],
                        'patient_id'=>$post['patient_id'],
                        'total_amount'=>str_replace(',', '', $post['total_amount']),
                        'discount_amount'=>$post['discount_amount'],
                        'net_amount'=>str_replace(',', '', $post['net_amount']),
                        'credit'=>str_replace(',', '', $post['net_amount']),
                        'debit'=>$post['paid_amount'],
                        'pay_mode'=>$post['payment_mode'],
                        'balance'=>$post['balance'],
                        'paid_amount'=>$post['paid_amount'],
                        'created_date'=>$row_d->created_date,
                        'created_by'=>$user_data['id']
                      );

                    $this->db->where('id',$row_d->id);
                    $this->db->update('hms_payment',$payment_data);
                      

                  }


                $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'section_id'=>20,'type'=>5));
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
                        'section_id'=>20,
                        'type'=>5,
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
              }

        if(!empty($sess_medicine_list))
        { 
          foreach($sess_medicine_list as $medicine_list)
          {
            //print '<pre>';print_r($medicine_list);
            //$m_data= explode('.',$medicine_list['mid']);
            $data_purchase_topurchase = array(
            "vaccine_pres_id"=>$post['data_id'],
            'v_id'=>$medicine_list['vid'],
            'qty'=>$medicine_list['qty'],
            'discount'=>$medicine_list['discount'],
            'sgst'=>$medicine_list['sgst'],
            'igst'=>$medicine_list['igst'],
            'cgst'=>$medicine_list['cgst'],
            'hsn_no'=>$medicine_list['hsn_no'],
            'age_id'=>$medicine_list['age_id'],
            'vaccination_date_time'=>date('Y-m-d H:i:s',strtotime($post['vaccination_date_time'])),
            'total_amount'=>$medicine_list['total_amount'],
            'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
            'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
            'batch_no'=>$medicine_list['batch_no'],
            'bar_code'=>$medicine_list['bar_code'],
            'per_pic_price'=>$medicine_list['per_pic_amount'],
            'conversion'=>$medicine_list['conversion'],
            'actual_amount'=>$medicine_list['sale_amount']

            );
            $this->db->insert('hms_pediatrician_vaccine_prescription_to_vaccine',$data_purchase_topurchase);
            //echo $this->db->last_query();
              $this->db->where(array('parent_id'=>$post['data_id'],'type'=>7));
              $this->db->delete('hms_medicine_stock');
            $data_new_stock=array(  "branch_id"=>$user_data['parent_id'],
                                    "type"=>7,
                                    "parent_id"=>$post['data_id'],
                                    "m_id"=>$medicine_list['vid'],
                                    "credit"=>$medicine_list['qty'],
                                    "debit"=>0,
                                    "batch_no"=>$medicine_list['batch_no'],
                                    "conversion"=>$medicine_list['conversion'],
                                    "mrp"=>$medicine_list['sale_amount'],
                                    "discount"=>$medicine_list['discount'],
                                    'sgst'=>$medicine_list['sgst'],
                                    'igst'=>$medicine_list['igst'],
                                    'cgst'=>$medicine_list['cgst'],
                                    'hsn_no'=>$medicine_list['hsn_no'],
                                    'bar_code'=>$medicine_list['bar_code'],
                                    "total_amount"=>$medicine_list['total_amount'],
                                    "expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
                                    'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
                                    'per_pic_rate'=>$medicine_list['per_pic_amount'],
                                  "created_by"=>$user_data['id'],
                                  "created_date"=>date('Y-m-d') //,strtotime($post['sales_date'])
                                );
            $this->db->insert('hms_vaccination_stock',$data_new_stock);

            
         

          }
        //die;
        }


            /* stock entry */

      $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'section_id'=>20));
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
              'section_id'=>20,
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
        $last_id=$post['data_id'];
        }
  //add
    else 
    {
    $sess_medicine_list= $this->session->userdata('billing_vaccine_ids');
    $this->db->insert('hms_pediatrician_vaccine_prescription',$data_pres);
    $last_id= $this->db->insert_id();
    /* add data into stock and relation table */
    if(!empty($sess_medicine_list))
    { 
       foreach($sess_medicine_list as $medicine_list)
       {
          //print '<pre>';print_r($medicine_list);
          $m_data= explode('.',$medicine_list['vid']);
          $data_purchase_topurchase = array(
          "vaccine_pres_id"=>$last_id,
          'v_id'=>$medicine_list['vid'],
          'qty'=>$medicine_list['qty'],
          'discount'=>$medicine_list['discount'],
          'sgst'=>$medicine_list['sgst'],
          'igst'=>$medicine_list['igst'],
          'cgst'=>$medicine_list['cgst'],
          'age_id'=>$medicine_list['age_id'],
          'hsn_no'=>$medicine_list['hsn_no'],
          'bar_code'=>$medicine_list['bar_code'],
          'vaccination_date_time'=>date('Y-m-d H:i:s',strtotime($post['vaccination_date_time'])),
          'total_amount'=>$medicine_list['total_amount'],
          'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
          'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
          'batch_no'=>$medicine_list['batch_no'],
          'per_pic_price'=>$medicine_list['per_pic_amount'],
          'conversion'=>$medicine_list['conversion'],
          'actual_amount'=>$medicine_list['sale_amount']
          );
          $this->db->insert('hms_pediatrician_vaccine_prescription_to_vaccine',$data_purchase_topurchase);
          //echo $this->db->last_query();
          $data_new_stock=array("branch_id"=>$user_data['parent_id'],
                                "type"=>7,
                                "parent_id"=>$last_id,
                                "v_id"=>$medicine_list['vid'],
                                "credit"=>$medicine_list['qty'],
                                "debit"=>0,
                                "batch_no"=>$medicine_list['batch_no'],
                                "conversion"=>$medicine_list['conversion'],
                                "mrp"=>$medicine_list['sale_amount'],
                                "discount"=>$medicine_list['discount'],
                                'sgst'=>$medicine_list['sgst'],
                                'hsn_no'=>$medicine_list['hsn_no'],
                                'igst'=>$medicine_list['igst'],
                                'cgst'=>$medicine_list['cgst'],
                                'bar_code'=>$medicine_list['bar_code'],
                                "total_amount"=>$medicine_list['total_amount'],
                                "expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
                                'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
                                'per_pic_rate'=>$medicine_list['per_pic_amount'],
                                "created_by"=>$user_data['id'],
                                "created_date"=>date('Y-m-d') //,strtotime($post['sales_date'])
              );
            $this->db->insert('hms_vaccination_stock',$data_new_stock);
            //echo $this->db->last_query();die;
         }


         /* add data into stock and relation table */
        /* entry in stock table */


             $payment_data = array(
                        'parent_id'=>$last_id,
                        'branch_id'=>$user_data['parent_id'],
                        'section_id'=>'7',
                        'type'=>'8',
                        //'doctor_id'=>$post['attended_doctor'],
                        'patient_id'=>$post['patient_id'],
                        'total_amount'=>str_replace(',', '', $post['total_amount']),
                        'discount_amount'=>$post['discount_amount'],
                        'net_amount'=>str_replace(',', '', $post['net_amount']),
                        'credit'=>str_replace(',', '', $post['net_amount']),
                        'debit'=>$post['pay_amount'],
                        'pay_mode'=>$post['payment_mode'],
                        'balance'=>$post['balance_due'],
                        'paid_amount'=>$post['pay_amount'],
                        'created_date'=>date('Y-m-d H:i:s'),
                        'created_by'=>$user_data['id']
                     );
                $this->db->insert('hms_payment',$payment_data);
                $payment_id = $this->db->insert_id();

                /* genereate receipt number */
                if(in_array('218',$user_data['permission']['section']))
                {
                   if($post['pay_amount']>0)
                   {
                     $hospital_receipt_no= check_hospital_receipt_no();
                     $data_receipt_data= array(
                                'branch_id'=>$user_data['parent_id'],
                                'section_id'=>20,
                                'parent_id'=>$last_id,
                                'payment_id'=>$payment_id,
                                'reciept_prefix'=>$hospital_receipt_no['prefix'],
                                'reciept_suffix'=>$hospital_receipt_no['suffix'],
                                'created_by'=>$user_data['id'],
                                'created_date'=>date('Y-m-d H:i:s')
                                );
                    $this->db->insert('hms_branch_hospital_no',$data_receipt_data);
                   }
                } 

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
                            'section_id'=>20,
                            'p_mode_id'=>$post['payment_mode'],
                            'branch_id'=>$user_data['parent_id'],
                            'parent_id'=>$last_id,
                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                    );
                    $this->db->set('created_by',$user_data['id']);
                    $this->db->set('created_date',date('Y-m-d H:i:s'));
                    $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
                    }
                    }

                    if(!empty($post['field_name']))
                    {
                    $post_field_value_name= $post['field_name'];
                    $counter_name= count($post_field_value_name); 
                    for($i=0;$i<$counter_name;$i++) 
                    {
                    $data_field_value= array(
                            'field_value'=>$post['field_name'][$i],
                            'field_id'=>$post['field_id'][$i],
                            'section_id'=>20,
                            'type'=>5,
                            'p_mode_id'=>$post['payment_mode'],
                            'branch_id'=>$user_data['parent_id'],
                            'parent_id'=>$payment_id,
                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                    );
                    $this->db->set('created_by',$user_data['id']);
                    $this->db->set('created_date',date('Y-m-d H:i:s'));
                    $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
                    }
                    }
                   


                    /*add sales banlk detail*/
        }
       

        /* entry in stock table */
    }
     return $last_id;
  }

  public function get_by_id_peditration_vaccine($id="",$tamt="")
  { 

    $this->db->select('hms_pediatrician_vaccine_prescription_to_vaccine.*,hms_pediatrician_vaccine_prescription.total_amount as t_amount');
    $this->db->from('hms_pediatrician_vaccine_prescription_to_vaccine'); 
    if(!empty($id))
    {
          $this->db->where('hms_pediatrician_vaccine_prescription_to_vaccine.vaccine_pres_id',$id);
          $this->db->join('hms_pediatrician_vaccine_prescription','hms_pediatrician_vaccine_prescription.id=hms_pediatrician_vaccine_prescription_to_vaccine.vaccine_pres_id','left');
    } 
    $query = $this->db->get()->result(); 

    $result = [];
    $total_price_medicine_amount='';

    if(!empty($query))
    {
      foreach($query as $medicine)  
      {   
          $result[$medicine->v_id.'.'.$medicine->batch_no] = array('vid'=>$medicine->v_id, 'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'batch_no'=>$medicine->batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->actual_amount, 'age_id'=>$medicine->age_id,'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
          $tamt = 0;
      } 
    } 
    //die;
    //print '<pre>';print_r($result);die;
    return $result;
  }
    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 

        $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);

        $this->db->where('hms_payment_mode_field_value_acc_section.section_id',20);
        $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
        return $query;
    }


    function template_format($data=""){
        
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_print_branch_template.*');
        $this->db->where($data);
        $this->db->from('hms_print_branch_template');
        $query=$this->db->get()->row();
        return $query;

    }

     public function get_by_id_prescription($prescription_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_pediatrician_vaccine_prescription.*,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_patient.patient_name,hms_patient.patient_code,hms_patient.mobile_no,hms_patient.gender,hms_pediatrician_vaccine_prescription.id as prescription_id,hms_vaccination_entry.vaccination_name');
        $this->db->from('hms_pediatrician_vaccine_prescription');
         $this->db->join('hms_patient','hms_patient.id=hms_pediatrician_vaccine_prescription.patient_id', 'Left');
         $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id=hms_pediatrician_vaccine_prescription.vaccine_id', 'Left');
        $this->db->where('hms_pediatrician_vaccine_prescription.branch_id',$branch_id);
        $this->db->where('hms_pediatrician_vaccine_prescription.id',$prescription_id);
        $query = $this->db->get(); 
       //echo $this->db->last_query();die;
        return $query->row_array();
    }


     public function get_by_id_print_prescription($ids="",$branch_ids="")
    {
        
        $result_sales=array();
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_pediatrician_vaccine_prescription.*,hms_patient.*,hms_gardian_relation.relation,hms_simulation.simulation,hms_sms.simulation as rel_simulation,hms_pediatrician_vaccine_prescription.id as prescription_id"); 
        $this->db->join('hms_patient','hms_patient.id = hms_pediatrician_vaccine_prescription.patient_id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
        $this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
         //$this->db->join('hms_pedic_age_vaccine_master','hms_pedic_age_vaccine_master.id = hms_pediatrician_vaccine_prescription.age_id');
        $this->db->where('hms_pediatrician_vaccine_prescription.is_deleted','0'); 
        $this->db->where('hms_pediatrician_vaccine_prescription.branch_id = "'.$branch_ids.'"'); 
        $this->db->where('hms_pediatrician_vaccine_prescription.id = "'.$ids.'"');
        $this->db->from('hms_pediatrician_vaccine_prescription');
        $result_sales['prescription_print_list']= $this->db->get()->result();

        $this->db->select('hms_pediatrician_vaccine_prescription_to_vaccine.*,hms_pediatrician_vaccine_prescription_to_vaccine.discount as m_discount,hms_vaccination_entry.*,hms_vaccination_entry.discount as m_disc,hms_pediatrician_vaccine_prescription_to_vaccine.sgst as m_sgst,hms_pediatrician_vaccine_prescription_to_vaccine.igst as m_igst,hms_pediatrician_vaccine_prescription_to_vaccine.cgst as m_cgst,hms_pediatrician_vaccine_prescription_to_vaccine.batch_no as m_batch_no,hms_pediatrician_vaccine_prescription_to_vaccine.expiry_date as m_expiry_date,hms_pediatrician_vaccine_prescription_to_vaccine.hsn_no as m_hsn_no');
          $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_pediatrician_vaccine_prescription_to_vaccine.v_id'); 
          $this->db->where('hms_pediatrician_vaccine_prescription_to_vaccine.vaccine_pres_id = "'.$ids.'"');
          $this->db->from('hms_pediatrician_vaccine_prescription_to_vaccine');
          $result_sales['prescription_print_list']['vaccine_list']=$this->db->get()->result();
          return $result_sales;
       
       //return $result_sales;
    }


     public function get_prescription_payment_details_print($prescription_id)
    {
        $user_data = $this->session->userdata('auth_users');
        $branch_id=$user_data['parent_id'];
        $this->db->select('*');
        $this->db->from('hms_pediatrician_vaccine_prescription');
        $this->db->where('id',$prescription_id);
        $this->db->where('branch_id',$branch_id);
        $res=$this->db->get();
        if($res->num_rows() > 0)
                return $res->row_array();
            else
                return "empty";
    }
  public function get_vaccine_history($booking_id="")
  {
   
      $user_data = $this->session->userdata('auth_users'); 
      //,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.vaccination_code,hms_pediatrician_vaccine_prescription.discount_amount as dis
      $this->db->select('hms_pediatrician_vaccine_prescription.*,hms_doctors.doctor_name');   
      $this->db->join('hms_doctors','hms_doctors.id = hms_pediatrician_vaccine_prescription.attended_doctor','left');
      $this->db->where('hms_pediatrician_vaccine_prescription.branch_id = "'.$user_data['parent_id'].'"');
      $this->db->where('hms_pediatrician_vaccine_prescription.booking_id = "'.$booking_id.'"');
      $query = $this->db->get('hms_pediatrician_vaccine_prescription');
      //echo $this->db->last_query();die;
      $result = $query->result(); 
    return $result;
  }

  public function getopdPatient()
  { 
      $user_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_patient.id,hms_patient.patient_email,hms_patient.mobile_no,hms_patient.patient_name,hms_patient.dob,hms_opd_booking.booking_code,hms_opd_booking.branch_id');   
      $this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left'); 
      $this->db->where('hms_opd_booking.booking_type',2);
      $this->db->where('hms_opd_booking.is_deleted','0');
      $this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');
      $this->db->where('hms_opd_booking.specialization_id',PEDIATRICIAN_SPECIALIZATION_ID);
      $this->db->group_by('hms_opd_booking.id');
      $this->db->order_by('hms_opd_booking.id','DESC');
      $this->db->limit('1');
      $query = $this->db->get('hms_opd_booking');
      //echo $this->db->last_query();die;
      $result = $query->result(); 
      return $result;
  }

  public function age_vaccin_list($branch_id="")
  { 
      $this->db->select('hms_pediatrician_age_vaccination.*,hms_vaccination_entry.vaccination_name');   
      $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id=hms_pediatrician_age_vaccination.vaccination AND hms_pediatrician_age_vaccination.is_deleted=0','left'); 
      $this->db->where('hms_vaccination_entry.is_deleted','0');
     // $this->db->where('hms_pediatrician_age_vaccination.is_deleted','0');
      $this->db->where('hms_pediatrician_age_vaccination.branch_id',$branch_id); 
      $this->db->group_by('hms_pediatrician_age_vaccination.id');
      $query = $this->db->get('hms_pediatrician_age_vaccination');
      $result = $query->result(); 
      return $result;
  }

  public function check_upcoming_vaccine($vaccine_id="")
  {
      $this->db->select('hms_pediatrician_age_vaccination_to_age.*');   
      $this->db->join('hms_pediatrician_age_vaccination','hms_pediatrician_age_vaccination.id=hms_pediatrician_age_vaccination_to_age.age_vaccination_id','left');
      $this->db->where('hms_pediatrician_age_vaccination.age_vaccination_id',$vaccine_id);     
      $query = $this->db->get('hms_pediatrician_age_vaccination_to_age');
      $result = $query->result(); 
      return $result;
  }
   
  public function vacc_age_list($age_id)
  {
      $this->db->select('hms_pedic_age_vaccine_master.*');    
      $this->db->where('hms_pedic_age_vaccine_master.id',$age_id);
      $this->db->where('hms_pedic_age_vaccine_master.status=1');
      $this->db->where('hms_pedic_age_vaccine_master.is_deleted!=2');     
      $query = $this->db->get('hms_pedic_age_vaccine_master');
      $result = $query->result(); 
      return $result;
  } 
  public function vaccine_related_age_data($vaccine_id)
  {
      $this->db->select('hms_pediatrician_age_vaccination_to_age.*');   
      $this->db->join('hms_pediatrician_age_vaccination','hms_pediatrician_age_vaccination.id = hms_pediatrician_age_vaccination_to_age.age_vaccination_id','left');
      $this->db->where('hms_pediatrician_age_vaccination_to_age.age_vaccination_id',$vaccine_id);     
      $query = $this->db->get('hms_pediatrician_age_vaccination_to_age');
      $result = $query->result(); 
      return $result;
      /*$user_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_pediatrician_age_vaccination.vaccination as vaccination_id,hms_pediatrician_age_vaccination.id,hms_vaccination_entry.vaccination_name');   
      $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_pediatrician_age_vaccination.vaccination');
      $this->db->where('hms_pediatrician_age_vaccination.branch_id = "'.$user_data['parent_id'].'"');
      $this->db->where('hms_pediatrician_age_vaccination.is_deleted!=',2);
   
      $query = $this->db->get('hms_pediatrician_age_vaccination');
       //echo $this->db->last_query();die;
      $result = $query->result(); 
      $i=0;
      $data=array();
       foreach($result as $res)
       {
        //print_r($res->vaccination_id);
          $data[$i]['vaccination_id']=$res->vaccination_id;
          $data[$i]['id']=$res->id;
          $data[$i]['vaccination_name']=$res->vaccination_name;
          $this->db->select('hms_pediatrician_age_vaccination_to_age.age_vaccination_id,hms_pediatrician_age_vaccination_to_age.vaccine_id,hms_pediatrician_age_vaccination_to_age.age_id,hms_pedic_age_vaccine_master.title,hms_pedic_age_vaccine_master.start_age,hms_pedic_age_vaccine_master.end_age,hms_pedic_age_vaccine_master.start_age_type,hms_pedic_age_vaccine_master.end_age_type');   
          $this->db->join('hms_pedic_age_vaccine_master','hms_pedic_age_vaccine_master.id = hms_pediatrician_age_vaccination_to_age.age_id');
          $this->db->where('hms_pediatrician_age_vaccination_to_age.branch_id = "'.$user_data['parent_id'].'"');
          $this->db->where('hms_pediatrician_age_vaccination_to_age.age_vaccination_id = "'.$res->id.'"');
          $this->db->where('hms_pediatrician_age_vaccination_to_age.type',1);
          $this->db->where('hms_pediatrician_age_vaccination_to_age.is_deleted!=',2);

          $query = $this->db->get('hms_pediatrician_age_vaccination_to_age');
          $data[$i]['age_list'] = $query->result();
            //print_r($data[$i]['age_list']);
           //echo $this->db->last_query();
            //die;
          $i++;
       }*/
       //die;
      // echo $this->db->last_query();

        //return $data;
     // return $result;
  }
  
  public function vaccinerelatedage()
  {
      $user_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_pediatrician_age_vaccination.vaccination as vaccination_id,hms_pediatrician_age_vaccination.id,hms_vaccination_entry.vaccination_name');   
      $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_pediatrician_age_vaccination.vaccination');
      $this->db->where('hms_pediatrician_age_vaccination.branch_id = "'.$user_data['parent_id'].'"');
      $this->db->where('hms_pediatrician_age_vaccination.is_deleted!=',2);
   
      $query = $this->db->get('hms_pediatrician_age_vaccination');
       //echo $this->db->last_query();die;
      $result = $query->result(); 
      $i=0;
      $data=array();
       foreach($result as $res)
       {
        //print_r($res->vaccination_id);
          $data[$i]['vaccination_id']=$res->vaccination_id;
          $data[$i]['id']=$res->id;
          $data[$i]['vaccination_name']=$res->vaccination_name;
          $this->db->select('hms_pediatrician_age_vaccination_to_age.age_vaccination_id,hms_pediatrician_age_vaccination_to_age.vaccine_id,hms_pediatrician_age_vaccination_to_age.age_id,hms_pedic_age_vaccine_master.title,hms_pedic_age_vaccine_master.start_age,hms_pedic_age_vaccine_master.end_age,hms_pedic_age_vaccine_master.start_age_type,hms_pedic_age_vaccine_master.end_age_type');   
          $this->db->join('hms_pedic_age_vaccine_master','hms_pedic_age_vaccine_master.id = hms_pediatrician_age_vaccination_to_age.age_id');
          $this->db->where('hms_pediatrician_age_vaccination_to_age.branch_id = "'.$user_data['parent_id'].'"');
          $this->db->where('hms_pediatrician_age_vaccination_to_age.age_vaccination_id = "'.$res->id.'"');
          $this->db->where('hms_pediatrician_age_vaccination_to_age.type',1);
          $this->db->where('hms_pediatrician_age_vaccination_to_age.is_deleted!=',2);

          $query = $this->db->get('hms_pediatrician_age_vaccination_to_age');
          $data[$i]['age_list'] = $query->result();
            //print_r($data[$i]['age_list']);
           //echo $this->db->last_query();
            //die;
          $i++;
       }
       //die;
      // echo $this->db->last_query();

        return $data;
     // return $result;
  }

  public function checked_vaccine_already_user($age_id="",$vaccination_id="",$patient_id="")
  {
    $user_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_pediatrician_vaccine_prescription.*');   
   
    $this->db->where('hms_pediatrician_vaccine_prescription.branch_id = "'.$user_data['parent_id'].'"');
    $this->db->where('hms_pediatrician_vaccine_prescription.age_id',$age_id); 
    $this->db->where('hms_pediatrician_vaccine_prescription.vaccine_id',$vaccination_id);  
    $this->db->where('hms_pediatrician_vaccine_prescription.patient_id = "'.$patient_id.'"');
    $this->db->where('hms_pediatrician_vaccine_prescription.is_deleted!=',2);
    $query = $this->db->get('hms_pediatrician_vaccine_prescription');
    //echo $this->db->last_query();
    $result = $query->result();



    if(!empty($result)) 
    {
    return $result ;
  }
  else
  {
    return '';
  }
  }


  public function date_age_all_list($date_select="")
    {
         //$date_new= date('d-m-Y',strtotime($date_select));

          $date_new= date('d-m-Y',strtotime($date_select));

        //ECHO $curent_date= strtotime(date('d-m-Y'));
        $users_data = $this->session->userdata('auth_users');
        $end_date_interval=array();
        $start_date_interval=array();
        $this->db->select('hms_pedic_age_vaccine_master.id,hms_pedic_age_vaccine_master.title,hms_pedic_age_vaccine_master.start_age,hms_pedic_age_vaccine_master.end_age,hms_pedic_age_vaccine_master.start_age_type,hms_pedic_age_vaccine_master.end_age_type');
        $this->db->from('hms_pedic_age_vaccine_master');
        $this->db->where('hms_pedic_age_vaccine_master.is_deleted=0');
        $this->db->where('hms_pedic_age_vaccine_master.status=1');
        $this->db->where('hms_pedic_age_vaccine_master.branch_id',$users_data['parent_id']);
        //$this->db->order_by('hms_pedic_age_vaccine_master.start_age','ASC');
        //$this->db->order_by('CAST(start_age as INT)','ASC');
        //$this->db->order_by('CAST(end_age as INT)','ASC');
        $this->db->order_by('start_age_type','ASC');
        //$this->db->order_by('CAST(start_age as INT)','ASC');
        $this->db->order_by('end_age_type','ASC');
        //$this->db->order_by('CAST(end_age as INT)','ASC');
        $result_age_list=  $this->db->get()->result();
        $i=0;
       foreach($result_age_list as $age_list)
        {
           if($age_list->start_age_type==1 && $age_list->end_age_type==1)  //1 for days
           {
                 if($age_list->start_age!='' && $age_list->title!=0)
                {

                  $end_date_interval[$i]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->start_age.' day'));
                  
                }
                else
                {
                  $end_date_interval[$i]['start_age']='';  
                }
                if($age_list->end_age!='' && $age_list->title=='birth')
                {

                  $end_date_interval[$i]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->end_age.' day'));
                }
                else
                {
                    $end_date_interval[$i]['end_age']='';
                }
               
           }

           //2 for week
           if($age_list->start_age_type==2 && $age_list->end_age_type==2)
           {
                if($age_list->start_age!=0 && $age_list->start_age!='')
                {
                  $end_date_interval[$i]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->start_age.' week'));
                  
                }
                else
                {
                    $end_date_interval[$i]['start_age']='';
                }
                if($age_list->end_age!='' && $age_list->end_age!=0)
                {

                  $end_date_interval[$i]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->end_age.' week'));
                }
                else
                {
                  $end_date_interval[$i]['end_age']='';
                }
               
            }
          
            if($age_list->start_age_type==3 && $age_list->end_age_type==3)
           {
                if($age_list->start_age!=0 && $age_list->start_age!='')
                {

                  $end_date_interval[$i]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->start_age.' month'));
                  
                }
                else
                {
                  $end_date_interval[$i]['start_age']='';  
                }
                if($age_list->end_age!='' && $age_list->end_age!=0)
                {

                  $end_date_interval[$i]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->end_age.' month'));
                }
                else
                {
                  $end_date_interval[$i]['end_age']='';
                }
               
            }//3 months
           

            if($age_list->start_age_type==4 && $age_list->end_age_type==4)
            {
                if($age_list->start_age!=0 && $age_list->start_age!='')
                {
                  $end_date_interval[$i]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->start_age.' year'));
                  
                }
                else
                {
                  $end_date_interval[$i]['start_age']='';  
                }
                if($age_list->end_age!='' && $age_list->end_age!=0)
                {

                  $end_date_interval[$i]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$age_list->end_age.' year'));
                }
                else
                {
                  $end_date_interval[$i]['end_age']='';
                }
               
             }//4 week

           
             $i++;
        }  
       // $arr= array_merge($start_date_interval,$end_date_interval);
       
        return $end_date_interval;


    }

    public function get_medicine_list()
    {
      $users_data = $this->session->userdata('auth_users');
      $medicine_data = $this->session->userdata('alloted_medicine_ids');

        $result = array();
      if(!empty($medicine_data))
      {
        $id_list = [];
        $batch_list = [];
        // $l=0;
        foreach($medicine_data as $medicine)
        {
          
          
        if(!empty($medicine['medicine_id']) && $medicine['medicine_id']>0)
          {
                    $id_list[]  = $medicine['medicine_id'];
          }
         
      
      
        if(!empty($medicine['batch_no']) && $medicine['batch_no']>0)
          {
                    $batch_list[]  = $medicine['batch_no'];
          }
             
        
           // $l++;
        } 
        
        
        $medicine_ids = implode(',', $id_list);
        $batch_nos = implode(',', $batch_list);
        
        
           $this->db->select("hms_medicine_entry.*,hms_medicine_stock.batch_no,hms_medicine_stock.m_id");
          $this->db->from('hms_medicine_entry');
    
              $this->db->join('hms_medicine_stock','hms_medicine_entry.id=hms_medicine_stock.m_id');
            
              $this->db->where('hms_medicine_entry.id IN ('.$medicine_ids.')');
              $this->db->where('hms_medicine_stock.m_id IN ('.$medicine_ids.')');
              if($batch_nos!=0){
                  $this->db->where('hms_medicine_stock.batch_no IN ('.$batch_nos.')');
              }else{
                $this->db->where('hms_medicine_stock.batch_no',0);
              }
              $this->db->where('(hms_medicine_entry.is_deleted=0 and hms_medicine_stock.is_deleted=0)');
          $this->db->where('(hms_medicine_entry.branch_id='.$users_data['parent_id'].' and hms_medicine_stock.branch_id='.$users_data['parent_id'].')');
          $this->db->group_by('hms_medicine_stock.m_id');
          $query = $this->db->get();
          // echo $this->db->last_query();die;
          $result = $query->result_array();
       
      }
      return $result;
    }

    public function medicine_list($ids="",$batch_no="",$age_id="")
    { 

        $users_data = $this->session->userdata('auth_users'); 
        $medicine_list = $this->session->userdata('billing_vaccine_ids');
        //print_r($medicine_list);die;
        $keys = '';
        $keys_ages='';
        if(!empty($age_id) && is_array($age_id))
        {
          $key_arr_ages = [];
          $j=0;
          foreach($age_id as $age_ids)
          {
                  $key_arr_ages[] = "'".$age_ids."'";
                 $j++;
          }
          $keys_ages = implode(',', $key_arr_ages);

        }
        if(!is_array($age_id))
        {
          $keys_ages="'".$age_id."'"; 
        }
        if(!is_array($ids) || !is_array($batch_no))
        {
          $keys = "'".$ids."'";
          
        }
        else if(is_array($ids) || is_array($batch_no))
        { 
          $key_arr = [];
          $total_key = count($ids);
          $i=0;
          foreach($ids as $id)
          {
                  $key_arr[] = "'".$id."'";
                 $i++;
          }
          $keys = implode(',', $key_arr);
         }
        else
        {
          

          $arr_mids = [];
          if(!empty($medicine_list))
          { 
          foreach($medicine_list as $medicine_data)
          {
          $arr_mids[] = "'".$medicine_data['vid']."'";
          }
          $keys = implode(',', $arr_mids);
          }
          //$keys = $ids.'.'.$batch_no;
        }
        $this->db->select('hms_vaccination_entry.*, hms_vaccination_stock.bar_code,hms_vaccination_stock.mrp as m_rp,hms_vaccination_stock.hsn_no as hsn,hms_vaccination_stock.conversion as conv,hms_vaccination_unit.vaccination_unit, hms_vaccination_unit_2.vaccination_unit as vaccination_unit_2, hms_vaccination_stock.batch_no,hms_vaccination_stock.expiry_date,hms_vaccination_stock.manuf_date,hms_vaccination_stock.debit as qty,hms_pediatrician_age_vaccination_to_age.age_id');
        $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_stock.v_id'); 
        $this->db->join('hms_pediatrician_age_vaccination_to_age','hms_pediatrician_age_vaccination_to_age.vaccine_id = hms_vaccination_stock.v_id AND hms_pediatrician_age_vaccination_to_age.age_id IN ('.$keys_ages.') '); 
        if(!empty($keys))
        { 
            $this->db->where('CONCAT(hms_vaccination_stock.v_id) IN ('.$keys.') ');
            //,".",hms_vaccination_stock.batch_no
        } 
        $this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
        $this->db->join('hms_vaccination_unit as hms_vaccination_unit_2','hms_vaccination_unit_2.id = hms_vaccination_entry.unit_second_id','left');

        $this->db->where('hms_vaccination_entry.is_deleted','0');  
        $this->db->where('hms_vaccination_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->where('hms_vaccination_stock.expiry_date >= "'.date('Y-m-d').'"');
        //$this->db->group_by('hms_vaccination_entry.id, hms_vaccination_stock.batch_no');  
        $this->db->group_by('hms_vaccination_stock.v_id');  
        $query = $this->db->get('hms_vaccination_stock');
        $result = $query->result(); 

        return $result; 
    }
    
    
    public function mark_vaccination()
    {
        $user_data = $this->session->userdata('auth_users');
        $post= $this->input->post();
        $booking_date = post['booking_date'];
        $data_pres=array( 'branch_id'=>$user_data['parent_id'],
                         'patient_id'=>$post['patient_id'],
                         'booking_id'=>$post['booking_id'],
                         'booking_code'=>0,
                         'vaccine_id'=>$post['vaccine_id'],
                         'age_id'=>$post['age_id'],
                         'qty'=>1,
                         'sgst'=>'0.00',
                          'igst'=>'0.00',
                          'cgst'=>'0.00',
                         'attended_doctor'=>$post['attended_doctor'],
                         'vaccination_date_time'=>date('Y-m-d H:i:s',strtotime($post['booking_date'])),
                         
                         
                         'total_amount'=>'0.00',
                         'paid_amount'=>'0.00',
                         'balance'=>'0.00',
                         'discount_percent'=>0,
                         'discount_amount'=>'0.00',
                         'payment_mode'=>0,
                         'net_amount'=>'0.00',
                         'status'=>1,
                         'is_deleted'=>0,
                         'deleted_by'=>0,
                         'created_by'=>$user_data['id'],
                         
                         );
     
   
    $this->db->insert('hms_pediatrician_vaccine_prescription',$data_pres);
    $last_id= $this->db->insert_id();
    // echo $this->db->last_query();die;
    $data_purchase_topurchase = array(
          "vaccine_pres_id"=>$last_id,
          'v_id'=>$post['vaccine_id'],
          'qty'=>1,
          'discount'=>'0.00',
          'sgst'=>'0.00',
          'igst'=>'0.00',
          'cgst'=>'0.00',
          'age_id'=>$post['age_id'],
          'hsn_no'=>0,
          'bar_code'=>0,
          'vaccination_date_time'=>date('Y-m-d H:i:s',strtotime($post['booking_date'])),
          'total_amount'=>'0.00',
          'expiry_date'=>date('Y-m-d'),
          'manuf_date'=>date('Y-m-d'),
          'batch_no'=>0,
          'per_pic_price'=>'0.00',
          'conversion'=>0,
          'actual_amount'=>'0.00'
          );
          $this->db->insert('hms_pediatrician_vaccine_prescription_to_vaccine',$data_purchase_topurchase);
          // echo $this->db->last_query();die;
     return $last_id;
  }
  
  
   public function get_consolidate_prescription($all_growth_ids='')
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_pediatrician_growth_prescription.*');
        $this->db->from('hms_pediatrician_growth_prescription'); 
        $this->db->where('hms_pediatrician_growth_prescription.branch_id',$branch_id);
        $this->db->where('hms_pediatrician_growth_prescription.id IN ('.$all_growth_ids.')');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result_array();
    }
  
  

// Please write code above    
}