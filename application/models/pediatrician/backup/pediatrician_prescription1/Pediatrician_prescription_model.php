<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_prescription_model extends CI_Model 
{
  	var $table = 'hms_blood_donor';
    var $column = array('hms_blood_donor.id','hms_blood_donor.donor_name','hms_blood_donor.donor_email','hms_blood_donor.status','hms_blood_donor.created_date','hms_blood_donor.modified_date');  
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

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_donor.*, hms_simulation.simulation,  hms_gardian_relation.relation, sim.simulation as relation_simulation,
            (CASE WHEN hms_blood_donor.gender=1 THEN 'Male' WHEN hms_blood_donor.gender=0 THEN 'Female' ELSE 'Other' END ) as gender, concat_ws(' ',hms_blood_donor.address,hms_blood_donor.address2,hms_blood_donor.address3) as address, hms_cities.city, hms_state.state, hms_blood_group.blood_group, hms_blood_mode_of_donation.mode_of_donation,hms_blood_preferred_reminder_service.preferred_reminder_service"); 
     
        $this->db->join('hms_patient','hms_patient.id=hms_blood_donor.patient_id','Left');
        $this->db->join('hms_simulation', 'hms_simulation.id=hms_patient.simulation_id', 'Left');
        $this->db->join('hms_simulation as sim', 'sim.id=hms_patient.relation_simulation_id', 'Left');
        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'Left');
        $this->db->join('hms_relation', 'hms_relation.id=hms_patient.relation_type', 'Left');
        $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','Left');
        $this->db->join('hms_blood_mode_of_donation','hms_blood_mode_of_donation.id=hms_blood_donor.mode_of_donation','Left');
        $this->db->join('hms_blood_preferred_reminder_service','hms_blood_preferred_reminder_service.id=hms_blood_donor.reminder_service_id','left');
        $this->db->from($this->table); 
        $this->db->where('hms_blood_donor.is_deleted','0');
        $this->db->where('hms_blood_donor.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function get_by_id($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('dnr.* , sim.simulation as simulation_donor, (CASE WHEN dnr.gender=0 THEN "Female" WHEN dnr.gender=1 THEN "Male" ELSE "Other" END) as donor_gender , bg.blood_group , rs.preferred_reminder_service');
        $this->db->from('hms_blood_donor dnr'); 
        $this->db->join('hms_simulation sim','sim.id=dnr.simulation_id','Left');
        $this->db->join('hms_blood_group bg', 'bg.id=dnr.blood_group_id','left');
        $this->db->join('hms_blood_preferred_reminder_service rs', 'rs.id=reminder_service_id','left');
        $this->db->where('dnr.branch_id',$branch_id);
        $this->db->where('dnr.id',$id);
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function delete_donor($id)
    {
        if($id!="" && !empty($id))
        {
            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',1);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$id);
            $this->db->update('hms_blood_donor');

        }
    }


    public function delete_donor_all($ids)
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
            $this->db->update('hms_blood_donor');
            //echo $this->db->last_query();die;
        }
    }

      function excel_donor_data()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_donor.*,hms_blood_group.blood_group,hms_cities.city,hms_state.state,hms_countries.country");
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_cities','hms_cities.id=hms_blood_donor.city_id','left');
         
         $this->db->join('hms_state','hms_state.id=hms_blood_donor.state_id','left');
         $this->db->join('hms_countries','hms_countries.id=hms_blood_donor.country_id','left');
        $this->db->where('hms_blood_donor.is_deleted','0');

        $search = $this->session->userdata('donor_search');
        //print_r($search); exit;
        
        
        
            if(isset($search) && !empty($search))
            {
            $this->db->where('hms_blood_donor.branch_id = "'.$search['branch_id'].'"');
            }
            else
            {
            $this->db->where('hms_blood_donor.branch_id = "'.$user_data['parent_id'].'"');  
            }
        

        $this->db->from($this->table); 
        $this->db->order_by('hms_blood_donor.id','desc');
        $query = $this->db->get(); 

        $data= $query->result();
       // echo $this->db->last_query();die;
        return $data;
    }

     // function to get donor components extracted
    public function get_examination_hide($donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_stock.*');
        $this->db->from('hms_blood_stock');  
        $this->db->where('hms_blood_stock.donor_id',$donor_id);
         $this->db->where('hms_blood_stock.is_issued',1);
        //$this->db->where('ec.is_deleted!=2');
        $this->db->where('hms_blood_stock.branch_id',$branch_id);
        $res=$this->db->get();
       //echo $this->db->last_query();die;
        if($res->num_rows() > 0)
            return "200";
        else
            return "empty";
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
        $this->db->where('hms_pedic_age_vaccine_master.is_deleted!=2');
        $this->db->where('hms_pedic_age_vaccine_master.branch_id',$users_data['parent_id']);
        //$this->db->order_by('hms_pedic_age_vaccine_master.start_age','ASC');
        //$this->db->order_by('CAST(start_age as INT)','ASC');
        //$this->db->order_by('CAST(end_age as INT)','ASC');
        $this->db->order_by('start_age_type','ASC');
        $this->db->order_by('CAST(start_age as INT)','ASC');
        $this->db->order_by('end_age_type','ASC');
        $this->db->order_by('CAST(end_age as INT)','ASC');
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

    public function save()
    {
        $user_data = $this->session->userdata('auth_users');
        $post= $this->input->post();
        $data_pres=array('branch_id'=>$user_data['parent_id'],
                         'patient_id'=>$post['patient_id'],
                         'booking_id'=>$post['booking_id'],
                         'attended_doctor'=>$post['attended_doctor'],
                         'vaccination_date_time'=>date('Y-m-d H:i:s',strtotime($post['vaccination_date_time'])),
                         'qty'=>$post['qty'],
                         'total_amount'=>$post['total_amount'],
                         'paid_amount'=>$post['paid_amount'],
                         'vaccine_id'=>$post['vaccine_id'],
                         'balance'=>$post['balance'],
                         'payment_mode'=>$post['payment_mode'],
                         'net_amount'=>$post['net_amount'],
                         'age_id'=>$post['age_id'],
                         );
        if(isset($post['data_id']) &&  !empty($post['data_id']))
        {
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id', $post['data_id']);
            $this->db->update('hms_pediatrician_vaccine_prescription',$data_pres);


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
                        'discount_amount'=>$post['discount'],
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


            /* payment table entry */

            /* stock entry */

              $this->db->where(array('parent_id'=>$post['data_id'],'type'=>7));
              $this->db->delete('hms_medicine_stock');

               $data_new_stock=array("branch_id"=>$user_data['parent_id'],
                        "type"=>7,
                        "parent_id"=>$post['data_id'],
                        "v_id"=>$post['vaccine_id'],
                        "credit"=>$post['qty'],
                        "debit"=>0,
                        "batch_no"=>'',
                        "conversion"=>'',
                        "mrp"=>$post['total_amount'],
                        "discount"=>$post['discount'],
                        'sgst'=>'',
                        'hsn_no'=>'',
                        'igst'=>'',
                        'cgst'=>'',
                        'bar_code'=>'',
                        "total_amount"=>$post['total_amount'],
                        "expiry_date"=>'',
                        'manuf_date'=>'',
                        'per_pic_rate'=>'',
                        "created_by"=>$user_data['id'],
                        "created_date"=>date('Y-m-d H:i:s'),
                        );
                     $this->db->insert('hms_vaccination_stock',$data_new_stock);


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
        }
        else
        {

        $this->db->insert('hms_pediatrician_vaccine_prescription',$data_pres);
        $last_id= $this->db->insert_id();

        /* entry in stock table */

        $data_new_stock=array("branch_id"=>$user_data['parent_id'],
                        "type"=>7,
                        "parent_id"=>$last_id,
                        "v_id"=>$post['vaccine_id'],
                        "credit"=>$post['qty'],
                        "debit"=>0,
                        "batch_no"=>'',
                        "conversion"=>'',
                        "mrp"=>$post['total_amount'],
                        "discount"=>$post['discount'],
                        'sgst'=>'',
                        'hsn_no'=>'',
                        'igst'=>'',
                        'cgst'=>'',
                        'bar_code'=>'',
                        "total_amount"=>$post['total_amount'],
                        "expiry_date"=>'',
                        'manuf_date'=>'',
                        'per_pic_rate'=>'',
                        "created_by"=>$user_data['id'],
                        "created_date"=>date('Y-m-d H:i:s'),
                        );
                     $this->db->insert('hms_vaccination_stock',$data_new_stock);

                     $payment_data = array(
                                'parent_id'=>$last_id,
                                'branch_id'=>$user_data['parent_id'],
                                'section_id'=>'7',
                                'type'=>'8',
                                'doctor_id'=>$post['attended_doctor'],
                                'patient_id'=>$post['patient_id'],
                                'total_amount'=>str_replace(',', '', $post['total_amount']),
                                'discount_amount'=>$post['discount'],
                                'net_amount'=>str_replace(',', '', $post['net_amount']),
                                'credit'=>str_replace(',', '', $post['net_amount']),
                                'debit'=>$post['paid_amount'],
                                'pay_mode'=>$post['payment_mode'],
                                'balance'=>$post['balance'],
                                'paid_amount'=>$post['paid_amount'],
                                'created_date'=>date('Y-m-d H:i:s'),
                                'created_by'=>$user_data['id']
                             );
                $this->db->insert('hms_payment',$payment_data);
                $payment_id = $this->db->insert_id();

                /* genereate receipt number */
                if(in_array('218',$user_data['permission']['section']))
                {
                   if($post['paid_amount']>0)
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
    public function get_by_id_peditration_vaccine($id="")
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_pediatrician_vaccine_prescription.*');
        $this->db->from('hms_pediatrician_vaccine_prescription'); 
        $this->db->where('hms_pediatrician_vaccine_prescription.id',$id);
        $this->db->where('hms_pediatrician_vaccine_prescription.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();

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
    public function get_vaccine_history()
    {
        $user_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_pediatrician_vaccine_prescription.*,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.vaccination_code,hms_pedic_age_vaccine_master.title');   
        $this->db->join('hms_pedic_age_vaccine_master','hms_pedic_age_vaccine_master.id = hms_pediatrician_vaccine_prescription.age_id');
        $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_pediatrician_vaccine_prescription.vaccine_id');
        $this->db->where('hms_pediatrician_vaccine_prescription.branch_id = "'.$user_data['parent_id'].'"');
        $query = $this->db->get('hms_pediatrician_vaccine_prescription');
        $result = $query->result(); 
       

        return $result;
    }

// Please write code above    
}