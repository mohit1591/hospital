<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_model extends CI_Model {

  var $table = 'path_test_booking';
  var $column = array('path_test_booking.id', 'hms_patient.patient_code', 'path_test_booking.lab_reg_no', 'hms_patient.patient_name',   'hms_patient.relation_name', 'hms_patient.mobile_no', 'hms_patient.patient_email', 'hms_patient.insurance_type', 'hms_insurance_company.insurance_company', 'hms_insurance_type.insurance_type', 'hms_patient.polocy_no', 'hms_patient.tpa_id', 'hms_patient.ins_amount', 'hms_patient.ins_authorization_no','hms_patient.gender', 'hms_patient.age_y', 'hms_patient.address','path_test_booking.form_f', 'path_test_booking.tube_no', 

    'hms_doctors.doctor_name','hms_employees.name', 'emp.name','path_test_booking.booking_date', 'path_test_booking.remarks','hms_payment_mode.payment_mode','path_test_booking.total_amount','path_test_booking.home_collection_amount', 'path_test_booking.discount', 'path_test_booking.net_amount', 'path_test_booking.paid_amount', 'path_test_booking.balance','path_test_booking.status','path_test_booking.modified_date','path_test_booking.created_by');  
  var $order = array('id' => 'desc');

  //

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  private function _get_datatables_query()
  {
    $search_date = $this->session->userdata('search_booking_date');
    
    //print_r($search_date);die;
    $users_data = $this->session->userdata('auth_users');
    $complation_status=$this->session->userdata('complation_status');
    
    $post = $this->input->post();
    $this->db->select("path_test_booking.id, path_test_booking.branch_id, path_test_booking.delivered_status, path_test_booking.remarks,path_test_booking.complation_status,path_test_booking.verify_status,path_test_booking.lab_reg_no,path_test_booking.tube_no, path_test_booking.form_f, path_test_booking.booking_date, path_test_booking.net_amount,path_test_booking.total_amount, path_test_booking.paid_amount, path_test_booking.discount,path_test_booking.created_date, path_test_booking.modified_date,path_test_booking.home_collection_amount, hms_patient.patient_name, hms_patient.patient_code, (CASE When hms_patient.relation_type=1 THEN 'Father/o' When hms_patient.relation_type=2 THEN 'Husband/o' When hms_patient.relation_type=3 THEN 'Baby/o' When hms_patient.relation_type=4 THEN 'Son/o' When hms_patient.relation_type=5 THEN 'Daughter/o' Else '' END) as patient_relation, hms_patient.relation_name, hms_patient.mobile_no,
      hms_patient.patient_email,  (CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' Else 'Normal' END ) as ins_type, hms_insurance_company.insurance_company, hms_insurance_type.insurance_type , hms_patient.polocy_no, hms_patient.tpa_id, hms_patient.ins_amount, hms_patient.ins_authorization_no,(CASE WHEN hms_patient.gender=0 THEN 'Female' ELSE 'MALE' END) as patient_gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.age_h, concat_ws(', ', hms_patient.address, hms_patient.address2, hms_patient.address3 ) as patient_address,
        (CASE WHEN path_test_booking.form_f=1 THEN 'Yes' WHEN path_test_booking.form_f=2 THEN 'No' ELSE '' END ) as path_form_f, 
        
        (CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.   referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name, 

        hms_employees.name as sample_collected,
        emp.name as staff_reference, hms_payment_mode.payment_mode,
      (CASE WHEN hms_payment.parent_id > 0 THEN sum(hms_payment.credit)-sum(hms_payment.debit) ELSE '0.00' END) as balance, hms_doctors.doctor_pay_type, 
      (CASE WHEN created_users.emp_id>0 THEN created_emp.name ELSE 'Admin' END) as created_name,(select sum(payment.debit) as paid_amount1 from hms_payment as payment where payment.parent_id = path_test_booking.id AND payment.section_id =1 AND payment.branch_id = ".$users_data['parent_id'].") as paid_amount1,
(select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.parent_id = path_test_booking.id AND payment.section_id=1 AND payment.branch_id = ".$users_data['parent_id'].") as balance1
      "); 
    //(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,
    $this->db->from($this->table); 
    $this->db->join('hms_payment','hms_payment.parent_id = path_test_booking.id AND hms_payment.section_id = "1" AND hms_payment.branch_id="'.$users_data['parent_id'].'"','left');
    $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
    $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
    $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
      
    /** added on 26feb2018 **/
    $this->db->join('hms_insurance_type ','hms_insurance_type.id=hms_patient.insurance_type_id', 'left');
    $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id', 'left');
    $this->db->join('hms_employees','hms_employees.id=path_test_booking.sample_collected_by', 'left');

    $this->db->join('hms_employees as emp','emp.id=path_test_booking.staff_refrenace_id', 'left');
    $this->db->join('hms_users as created_users','created_users.id=path_test_booking.created_by', 'left');
    $this->db->join('hms_employees as created_emp','created_emp.id=created_users.emp_id', 'left');
      $this->db->join('hms_payment_mode','hms_payment_mode.id=path_test_booking.payment_mode', 'left');
      $this->db->join('path_test_booking_to_profile','path_test_booking_to_profile.test_booking_id = path_test_booking.id','left'); 
    $this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id','left');
    
    $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
    /** added on 26feb2018 **/


        $this->db->where('path_test_booking.is_deleted','0'); 
        if($users_data['users_role']==4)
        {
           $this->db->where('path_test_booking.patient_id',$users_data['parent_id']);
        }
        else if($users_data['users_role']==3)
        {
           $this->db->where('path_test_booking.referral_doctor',$users_data['parent_id']);
        }
        else
        {
          if(isset($post['branch_id']) && !empty($post['branch_id']))
          {
            $this->db->where('path_test_booking.branch_id',$post['branch_id']);
          }
          else
          {
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
          } 
        } 
        $this->db->group_by('path_test_booking.id');
        if(isset($search_date) && !empty($search_date))
        {
          if(isset($search_date['start_date']) && !empty($search_date['start_date'])
            )
          {
            $start_date = date('Y-m-d',strtotime($search_date['start_date']));
            $this->db->where('path_test_booking.booking_date >= "'.$start_date.'"');
          }

          if(isset($search_date['end_date']) && !empty($search_date['end_date'])
            )
          {
            $end_date = date('Y-m-d',strtotime($search_date['end_date']));
            $this->db->where('path_test_booking.booking_date <= "'.$end_date.'"');
          }

          if(isset($search_date['patient_code']) && !empty($search_date['patient_code']) )
          {
            $this->db->where('hms_patient.patient_code',$search_date['patient_code']);
          }

                if(isset($search_date['bar_code']) && !empty($search_date['bar_code']) )
          {
            $this->db->where('path_test_booking.barcode_text',$search_date['bar_code']);
          }
          if(isset($search_date['tube_no']) && !empty($search_date['tube_no']) )
          {
            $this->db->where('path_test_booking.tube_no',$search_date['tube_no']);
          }

          if(isset($search_date['lab_reg_no']) && !empty($search_date['lab_reg_no']) )
          {
            $this->db->where('path_test_booking.lab_reg_no',$search_date['lab_reg_no']);
          }

          if(isset($search_date['referral_doctor']) && !empty($search_date['referral_doctor']) )
          {
            $this->db->where('path_test_booking.referral_doctor',$search_date['referral_doctor']);
          }

          if(isset($search_date['attended_doctor']) && !empty($search_date['attended_doctor']) )
          {
            $this->db->where('path_test_booking.attended_doctor',$search_date['attended_doctor']);
          }
          
           if(isset($search_date['collection_center']) && !empty($search_date['collection_center']) )
          {
            $this->db->where('path_test_booking.collection_center',$search_date['collection_center']);
          }


          if(isset($search_date['simulation_id']) && !empty($search_date['simulation_id']) )
          {
            $this->db->where('hms_patient.simulation_id',$search_date['attended_doctor']);
          }

          if(isset($search_date['patient_name']) && !empty($search_date['patient_name']) )
          {
            $this->db->where('hms_patient.patient_name LIKE "'.$search_date['patient_name'].'%"');
          }

          if(isset($search_date['mobile_no']) && !empty($search_date['mobile_no']) )
          {
            $this->db->where('hms_patient.mobile_no',$search_date['mobile_no']);
          }

          if(isset($search_date['age_from']) && !empty($search_date['age_from']) )
          {
            $this->db->where('hms_patient.age_y >= "'.$search_date['age_from'].'"');
          }

          if(isset($search_date['age_to']) && !empty($search_date['age_to']) )
          {
            $this->db->where('hms_patient.age_y <= "'.$search_date['age_to'].'"');
          }

          if(isset($search_date['gender']) && !empty($search_date['gender']) )
          {
            $this->db->where('hms_patient.gender',$search_date['gender']);
          }

          if(isset($search_date['city_id']) && !empty($search_date['city_id']) )
          {
            $this->db->where('hms_patient.city_id',$search_date['city_id']);
          }

          if(isset($search_date['country_id']) && !empty($search_date['country_id']) )
          {
           // $this->db->where('hms_patient.country_id',$search_date['country_id']);
          }

          if(isset($search_date['state_id']) && !empty($search_date['state_id']) )
          {
            $this->db->where('hms_patient.state_id',$search_date['state_id']);
          }

          if(isset($search_date['pincode']) && !empty($search_date['pincode']) )
          {
            $this->db->where('hms_patient.pincode',$search_date['pincode']);
          }
 
          if($search_date['insurance_type']!='')
          {
            $this->db->where('hms_patient.insurance_type',$search_date['insurance_type']);
          }

          if(!empty($search_date['insurance_type_id']))
          {
            $this->db->where('hms_patient.insurance_type_id',$search_date['insurance_type_id']);
          }

          if(!empty($search_date['ins_company_id']))
          {
            $this->db->where('hms_patient.ins_company_id',$search_date['ins_company_id']);
          }
          
           if(isset($search_date['is_home_collection']) && !empty($search_date['is_home_collection']) )
          {
            $this->db->where('path_test_booking.is_home_collection',$search_date['is_home_collection']);
          }

          if(isset($search_date['complation_status']) && !empty($search_date['complation_status']) && $search_date['complation_status']==1 )
          {
            $this->db->where('path_test_booking.complation_status',1);
          }
          elseif(isset($search_date['complation_status']) && !empty($search_date['complation_status']) && $search_date['complation_status']==2 )
          {
            $this->db->where('path_test_booking.complation_status',0);
          }

 /* refered by code */
//print_r($search_date); die;
 //referred_by
      if(isset($search_date['referred_by']) && isset($search_date['referral_hospital']) && $search_date['referred_by']=='1' && !empty($search_date['referral_hospital']) )
      {
      $this->db->where('path_test_booking.referral_hospital' ,$search_date['referral_hospital']);
      }
      elseif(isset($search_date['refered_id']) && $search_date['referred_by']=='0' && !empty($search_date['refered_id']))
      {
          $this->db->where('path_test_booking.referral_doctor' ,$search_date['refered_id']);
      }
      
            if(isset($search_date['dept_id']) && !empty($search_date['dept_id'])
                )
            { 
                $this->db->where('path_test.dept_id = "'.$search_date["dept_id"].'"');
            }
    }


          if($complation_status!="" && !empty($complation_status))
          {
              if($complation_status==5)
              {
                $this->db->where('path_test_booking.delivered_status',1);
              }
              else if($complation_status==4)
              {
                $this->db->where('path_test_booking.verify_status=1 AND path_test_booking.delivered_status=0');
              }
              else if($complation_status==3)
              {
                $this->db->where('path_test_booking.complation_status=1 AND path_test_booking.verify_status=0 AND path_test_booking.delivered_status=0');
              }
              else if($complation_status==2)
              {
                $this->db->where('path_test_booking.delivered_status=0 AND path_test_booking.verify_status=0 AND path_test_booking.complation_status=0');
              }
              else
              {
                
              }
          }


          /*$emp_ids='';
          $access_users_label=array();
          if($users_data['emp_id']>0)
          {
              $this->load->model('general/general_model');
              $get_access_user_with_user= $this->general_model->get_access_user($users_data['id']);
              //echo "<pre>"; print_r($get_access_user_with_user); exit;
              if(isset($get_access_user_with_user) && !empty($get_access_user_with_user) && count($get_access_user_with_user)>0)
               {
                      foreach($get_access_user_with_user as $access_users)
                      {
                         $access_users_label[]=  $access_users->access_id;
                      }
                      $emp_ids= implode(',',$access_users_label).','.$users_data['id'];
                } 
                else
                {
                      $emp_ids= $users_data['id'];
                }


               //print '<pre>'; echo $emp_ids;die;
          }
          else
          {
              if(!empty($search_date["employee"]))
              {
                  $emp_ids=  $search_date["employee"];
              }
              else
              {
                $emp_ids= $users_data['id'];
              }
              

          }

          if(isset($emp_ids) && !empty($emp_ids))
          { 
              $this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
          }*/

          $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
                $this->db->where('path_test_booking.created_by',$emp_ids);
            }
        }
        elseif(!empty($search_date["employee"]))
        {
            $emp_ids=  $search_date["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
           //$this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
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
  public function item_list($test_id="",$booking_id="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('path_test_to_inventory_item.*, inventory_item.booking_id as booking_status,inventory_item.qty as booking_qty, inventory_item.unit_id as booking_unit,path_test_to_inventory_item.qty as inventory_qty,hms_stock_item_unit.unit as first_unit,path_stock_category.category,path_item.item,path_item.qty');  
      $this->db->join('path_test_to_inventory_item as inventory_item', 'inventory_item.item_id=path_test_to_inventory_item.item_id AND inventory_item.booking_id= "'.$booking_id.'"', 'left');
      $this->db->where('path_test_to_inventory_item.test_id',$test_id);
      $this->db->where('path_test_to_inventory_item.booking_id',0);
      $this->db->where('path_test_to_inventory_item.branch_id',$users_data['parent_id']); 
      $this->db->join('path_item`','path_item.id=path_test_to_inventory_item.item_id','left'); 
      $this->db->join('path_stock_category`','path_stock_category.id=path_item.category_id','left');
      $this->db->join('hms_stock_item_unit`','hms_stock_item_unit.id=path_test_to_inventory_item.unit_id','left'); 
      $this->db->group_by('path_test_to_inventory_item.item_id');
      $query = $this->db->get('path_test_to_inventory_item');
      $result = $query->result();
      //print_r($result);die;
      return $result;
    }
    /* save inventory data in test to inventory as well as stock item */
    public function insert_invntory()
    {
        $post= $this->input->post();
        $this->db->where(array('test_id'=>$post['test_id'],'booking_id'=>$post['booking_id']));
        $this->db->delete('path_test_to_inventory_item');
        
        $this->db->where(array('test_id'=>$post['test_id'],'parent_id'=>$post['booking_id']));
        $this->db->delete('path_stock_item');
        
    //$this->db->where(array('test_id'=>$post['test_id'],'parent_id'=>$post['booking_id'],'type'=>4));
    //$this->db->delete('path_stock_item');

        $user_data = $this->session->userdata('auth_users'); 

        if(!empty($post['item_name']))
        {
          $array_item = array_values($post['item_name']);
        }
        if(!empty($post['item_id']))
        {
          $array_item_id = array_values($post['item_id']);
        }
        if(!empty($post['unit_value']))
        {
          $array_unit_value = array_values($post['unit_value']);
        }
        if(!empty($post['quantity']))
        {
           $array_quantity = array_values($post['quantity']);
        }
            

      if(!empty($array_item))
      {
         $i=0;
         $qty='';
        foreach($array_item as $item_name)
        {
                        $data_insert_items= 
                        array('branch_id'=>$user_data['parent_id'],
                        'type'=>1,
                        'test_id'=>$post['test_id'],
                        'booking_id'=>$post['booking_id'],
                        'item_id'=>$array_item_id[$i],
                        'unit_id'=>$array_unit_value[$i],
                        'qty'=>$array_quantity[$i], 
                        );
                        $this->db->set('created_by',$user_data['id']);
                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                        $this->db->insert('path_test_to_inventory_item',$data_insert_items);
                        
                        /* enter data in stock table */

                         $check_parent_unit= get_checkparent_unit($array_unit_value[$i]);

                     
                         if(isset($check_parent_unit) && count($check_parent_unit)>0)
                         {
                           $qty=$array_quantity[$i]/$check_parent_unit[0]->qty_with_second_unit;
                            
                         }
                         else
                         {
                           $qty=$array_quantity[$i];
                         }
                        
                          $data_new_stock=array("branch_id"=>$user_data['parent_id'],
                                            "type"=>4,
                                            "parent_id"=>$post['booking_id'],
                                            'test_id'=>$post['test_id'],
                                            'item_id'=>$array_item_id[$i],
                                            "credit"=>$qty,
                                            "debit"=>'',
                                            "qty"=>$qty,
                                            "price"=>'',
                                            'cat_type_id'=>'',
                                            "item_name"=>'',
                                            //"vat"=>$medicine_list['vat'],
                                            'unit_id'=>$array_unit_value[$i],
                                            'item_code'=>'',
                                            "total_amount"=>'',
                                            'per_pic_price'=>'',
                                            "created_by"=>$user_data['id'],
                                            "created_date"=>date('Y-m-d H:i:s'),);
            $this->db->insert('path_stock_item',$data_new_stock);
                      //echo $this->db->last_query();die;
           /* enter data in stock table */

             //echo $this->db->last_query();
            $i++;


        } 
        //die;
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
        $search_date = $this->session->userdata('search_booking_date');
        $users_data = $this->session->userdata('auth_users');
        $complation_status=$this->session->userdata('complation_status');
        $this->db->from($this->table);
        $this->db->where('path_test_booking.is_deleted','0'); 
        $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
        if(isset($search_date) && !empty($search_date))
        {
          if(isset($search_date['start_date']) && !empty($search_date['start_date']))
          {
            $start_date = date('Y-m-d',strtotime($search_date['start_date']));
            $this->db->where('path_test_booking.booking_date >= "'.$start_date.'"');
          }

          if(isset($search_date['end_date']) && !empty($search_date['end_date'])
            )
          {
            $end_date = date('Y-m-d',strtotime($search_date['end_date']));
            $this->db->where('path_test_booking.booking_date <= "'.$end_date.'"');
          }
          
        }
        
         if($complation_status!="" && !empty($complation_status))
          {
              if($complation_status==5)
              {
                $this->db->where('path_test_booking.delivered_status',1);
              }
              else if($complation_status==4)
              {
                $this->db->where('path_test_booking.verify_status=1 AND path_test_booking.delivered_status=0');
              }
              else if($complation_status==3)
              {
                $this->db->where('path_test_booking.complation_status=1 AND path_test_booking.verify_status=0 AND path_test_booking.delivered_status=0');
              }
              else if($complation_status==2)
              {
                $this->db->where('path_test_booking.delivered_status=0 AND path_test_booking.verify_status=0 AND path_test_booking.complation_status=0');
              }
              else
              {
                
              }
          }
    return $this->db->count_all_results();
  }
    
    public function unit_list()
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('*');
      $this->db->where('branch_id',$user_data['parent_id']); 
      $this->db->where('is_deleted',0);  
      $query = $this->db->get('path_test_booking');
    return $query->result();
    }
  
   

  public function get_by_id($id)
  {
    $user_data = $this->session->userdata('auth_users');
    $company_data = $this->session->userdata('company_data');
    $sub_branches_data = $this->session->userdata('sub_branches_data');
    $branch_list = '';
    if(!empty($sub_branches_data))
    { 
           $branch_list = array_unique(array_column($sub_branches_data, 'id'));
    } 
    if(!empty($branch_list))
    {
      $branch_ids = implode(',', $branch_list);
      $branch_ids = $branch_ids.','.$user_data['parent_id'];
    }
    else
    {
      if(!empty($company_data))
      {
        $branch_ids = $company_data['id'];
      }
      else
      {
        $branch_ids = $user_data['parent_id'];
      }
      
    }
    $this->db->select('path_test_booking.*, hms_patient.patient_email,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.age_h ,hms_patient.address,,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob');
    $this->db->from('path_test_booking'); 
    $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id'); 
    //$this->db->where('path_test_booking.branch_id IN ('.$branch_ids.')'); 
    $this->db->where('path_test_booking.id',$id);
    $this->db->where('path_test_booking.is_deleted','0');
    $query = $this->db->get(); 
    return $query->row_array();
  }
  
     private function booking_payment_calculate()
    {
        $users_data = $this->session->userdata('auth_users');
        $booking_set_branch = $this->session->userdata('booking_set_branch');
        if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
           $branch_id = $booking_set_branch;
        }
        else
        {
           $branch_id = $users_data['parent_id'];
        }
      
      $post = $this->input->post();

      $booking_list = $this->session->userdata('book_test');
      $total_master_amount = 0;
      $total_base_amount = 0;
      $total_amount = 0;
      $test_base_rate = 0;
      $test_master_rate = 0;
        
        $profile_data = $this->session->userdata('set_profile'); 
    if(isset($profile_data) && !empty($profile_data))
    {
      foreach($profile_data as $prfl_data)
      {
        $total_master_amount = $total_master_amount+$prfl_data['price'];
        $total_base_amount = $total_base_amount+$prfl_data['base_price'];
        $total_amount = $total_amount+$prfl_data['price'];
        $test_base_rate = $test_base_rate+$prfl_data['base_price'];
        $test_master_rate = $test_master_rate+$prfl_data['price'];
      } 
    }

      if(!empty($booking_list))
         { 
           foreach($booking_list as $test)
           {
             /////////// Transection doctor payment ///////// 
              $test_data = get_test($test['id']);
              //gst applied
              $gst_val = $this->check_gst_vals($test['id']);
             
              
              
              //$rate = $test['price'];
              //echo "<pre>"; print_r($test_data);die;
              $master_rate = $test['price'];
              $base_rate = $test_data->base_rate;
              $booking_doctor_type = $this->session->userdata('booking_doctor_type');
             
              if(!empty($booking_doctor_type))
              {  
                    $rate_data = doctor_test_rate('',$booking_doctor_type[0]['id'],$test['id']);

                    if(!empty($rate_data))
                    {
                      $master_rate = $rate_data['rate'];
                      $base_rate = $rate_data['base_rate'];
                    }
                    else
                    {
                        $rate_branch_data = doctor_test_rate($branch_id,'',$test['id']); 
                        if(!empty($rate_branch_data))
                        { 
                              $patient_rate = $rate_branch_data['rate'];
                              $branch_rate = $rate_branch_data['base_rate'];
                        }
                        else
                        {
                              $patient_rate = $master_rate;
                              $branch_rate = $test_data->base_rate;
                        }
                        

                   

                   if($booking_doctor_type[0]['base_type']==1)
                   { 
                         $base_rate = "(".$branch_rate."/100)*".$booking_doctor_type[0]['base_rate'];  
                         $base_rate = $this->calc_string($base_rate); 
                         $base_rate = $branch_rate+$base_rate; 
                   }
                   else 
                   {
                     $replace_brate = str_replace('+', '', $booking_doctor_type[0]['base_rate']);   
                     $base_rate = $branch_rate+$replace_brate;

                   }
                    }
 
                  //echo '<pre>'; print_r($test_data);die;
              }
              else
              {
                $rate_data = doctor_test_rate($branch_id,'',$test['id']);
                  if(!empty($rate_data))
                  { 
                        $master_rate = $rate_data['rate'];
                        $base_rate = $rate_data['base_rate'];
                  }
                  else
                  {  
                        $master_rate = $test['price'];
                        $base_rate = $test_data->base_rate;
                  }
              }

               /* panel test rate */

             $test_panel_rate = $this->session->userdata('test_panel_rate');
                //print_r($test_panel_rate);die;
                if(isset($test_panel_rate) && !empty($test_panel_rate))
                {
                if(!empty($test_panel_rate['ins_company_id']) && isset($test_panel_rate['ins_company_id']))
                  {
                    $rate_branch_data = panel_test_rate($branch_id,$test_panel_rate['ins_company_id'],$test['id']);
                   if(!empty($rate_branch_data) && $rate_branch_data['path_price']>0 && isset($rate_branch_data['path_price']) )
          {

          $master_rate=$rate_branch_data['path_price'];
          }
          }
                }

              /* panel test rate */
            //////////////////////////////////////////////////
             $total_master_amount = $total_master_amount+$master_rate;
             $total_base_amount = $total_base_amount+$base_rate;
             
             $total_amount = $total_amount+$master_rate; 
             
             
             
             $test_base_rate = $test_base_rate+$base_rate;
             $test_master_rate = $test_master_rate+$master_rate;
           } 
         }

         //print_r($post);die;

          // added on 05-feb-2018
           if(!empty($post['home_collection_amount']))
           {
              $home_collection=$post['home_collection_amount'];
           }
           else
           {
            $home_collection='0.00';
           }
           $total_amount = $total_amount+$home_collection;
           $total_master_amount = $total_master_amount+$home_collection;
           $total_base_amount = $total_base_amount+$home_collection; //$base_rate
          // added on 05-feb-2018
        //echo $total_amount.'ssss'; 
        $gst_amount = $post['gst_amount'];
        if(!empty($gst_amount))
        {
            $total_amount = $total_amount-$gst_amount;
            $total_master_amount = $total_master_amount-$gst_amount;
            $total_base_amount = $total_base_amount-$gst_amount;
        }
        //echo $total_amount; die;
      $discount = $post['discount'];
      
      $net_amount = ($total_amount-$discount)+$gst_amount;
      $master_net_amount = ($total_master_amount-$discount)+$gst_amount;
      $base_net_amount = ($total_base_amount-$discount)+$gst_amount;
      $balance = $post['balance'];
      $paid_amount = $post['paid_amount'];
      $net_amount = number_format($net_amount,2,'.', '');
      $pay_arr = array('test_base_rate'=>$test_base_rate, 'test_master_rate'=>$test_master_rate ,'total_amount'=>number_format($total_amount,2,'.', ''),'total_master_amount'=>number_format($total_master_amount,2,'.', ''),'total_base_amount'=>number_format($total_base_amount,2,'.', ''),'master_net_amount'=>number_format($master_net_amount,2,'.', ''), 'base_net_amount'=>number_format($base_net_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''), 'balance'=>$balance, 'paid_amount'=>$paid_amount, 'home_collection'=>number_format($home_collection,2,'.','') );

      return $pay_arr;
    }

public function save()
{
     
    $user_data = $this->session->userdata('auth_users'); 
    
    // op code
     $dep=array();
     $dep_uni=array();
     $tokentype=$this->get_token_type_by_branch($user_data['parent_id']);
     $booked_test_list = $this->session->userdata('book_test'); 
     foreach ($booked_test_list as $test) 
     {
      $dept_id=$this->get_department_id($test['id']);
      $dep[]=$dept_id['dept_id'];
     }
     $dep_uni=array_unique($dep);
     
     
    $post = $this->input->post(); 
    //echo "<pre>"; print_r($post); exit;
    $booking_set_branch = $this->session->userdata('booking_set_branch');
    if(isset($booking_set_branch) && !empty($booking_set_branch))
    {
      $branch_id = $booking_set_branch;
    }
    else
    {
      $branch_id = $user_data['parent_id'];
    }
    $booking_doctor_type = $this->session->userdata('booking_doctor_type');
    
    $total_booked_profile_test = count($this->session->userdata('book_test'))+count($this->session->userdata('set_profile'));
    $per_booked_tp_discount = '0.00';
    if(!empty($post['discount']) && $post['discount']>0)
    {
       $per_booked_tp_discount = $post['discount']/$total_booked_profile_test;
    }
    //echo $per_booked_tp_discount;die;
    $total_comission_data = 0;
    if(!empty($booked_test_list))
     {
        if(!empty($post['referral_hospital']) || !empty($post['referral_doctor']))
        { 
         
         foreach($booked_test_list as $booked_test)
         {
           $test_data = get_test($booked_test['id']); 
           $comission_arr = get_doc_hos_comission($post['referral_doctor'],$post['referral_hospital'],$booked_test['price']-$per_booked_tp_discount,$test_data->dept_id,'',$post['discount']); 
            if(!empty($comission_arr))
            {
                $total_comission_data = $total_comission_data+$comission_arr['total_comission'];
            }  
         }
        } 
     }
     
     
     
    //echo $per_booked_tp_discount;die;
    $profile_data = $this->session->userdata('set_profile');
    
    if(!empty($profile_data))
     {
        if(!empty($post['referral_hospital']) || !empty($post['referral_doctor']))
        { 
         
         foreach($profile_data as $profile_d)
         { 
           $comission_arr = get_doc_hos_comission($post['referral_doctor'],$post['referral_hospital'],$profile_d['price']-$per_booked_tp_discount,8,'',$post['discount']); 
            if(!empty($comission_arr))
            {
                $total_comission_data = $total_comission_data+$comission_arr['total_comission'];
            }  
         }
        } 
     }
     
    $profile_id = 0; 
    $profile_all_test = [];
    $profile_price = '0';
    $profile_interpretation = '';
    $p_test_price = [];
    //echo "<pre>";print_r($profile_data);die;
    if(isset($profile_data))
    {
      foreach($profile_data as $profile_dat)
      {
        $profile_all_test = [];
        $profile_price = $profile_dat['price'];
        $profile_id = $profile_dat['id'];
        $profile_arr = get_profile($profile_dat['id']);  
        $profile_interpretation = $profile_arr['interpretation'];
        $profile_test_list = $this->profile_test_list($profile_id);
              //echo $profile_interpretation;die;
        ///// Get Profile Test Childs //////// 
        foreach($profile_test_list as $test)
        {
          $all_profile_child = $this->getChildren($test->test_id); 
 
            if(!empty($all_profile_child))
            {
              foreach($all_profile_child as $child_profile_test)
              { 
                      $profile_all_test[] = $child_profile_test;
              }   
            }
            $profile_all_test[] = $test->test_id;  
        } 
        $profile_all_test = array_unique($profile_all_test);
        
        foreach($profile_all_test as $p_test)
        {
          $p_test_data = get_test($p_test);
          if(!empty($p_test_data))
          { 
                     $p_test_price[] = array('id'=>$p_test, 'profile_id'=>$profile_id, 'price'=>$p_test_data->rate,'child_profile_status'=>'0');
          } 
        }
        

        /////// set child profile test //////////////////////
        $child_profile_list = $this->get_child_profile($profile_id);
        //echo "<pre>";print_r($child_profile_list);die;
        if(!empty($child_profile_list))
        {
          //echo "<pre>";print_r($child_profile_list);die;
          foreach($child_profile_list as $child_profile)
          {
            $cprofile_all_test = array();
            $child_profile_test_list = $this->profile_test_list($child_profile['multi_profile_id']);
            //echo "<pre>";print_r($child_profile_test_list);die;
            foreach($child_profile_test_list as $child_p_test)
            {
              $all_cprofile_child = $this->getChildren($child_p_test->test_id); 

                if(!empty($all_cprofile_child))
                {
                  foreach($all_cprofile_child as $child_cprofile_test)
                  { 
                          $cprofile_all_test[] = $child_cprofile_test;
                  }   
                }
                $cprofile_all_test[] = $child_p_test->test_id;  
            }

            $cprofile_all_test = array_unique($cprofile_all_test); 
            foreach($cprofile_all_test as $cp_test)
            {
              $p_test_data = get_test($cp_test);
              if(!empty($p_test_data))
              { 
                         $p_test_price[] = array('id'=>$cp_test,'profile_id'=>$child_profile['multi_profile_id'], 'price'=>$p_test_data->rate,'child_profile_status'=>'1');
              } 
            } 
            
          }
        } 
        //echo "<pre>";print_r($p_test_price);die;
        ////////////////////////////////////////////////////
      }
 
      $profile_all_test = $p_test_price;
      //echo "<pre>";print_r($profile_all_test);die;
      ///////////////////////////////////////
    }
        
        $lab_reg_no = generate_unique_id(26);
        $final_calculation = $this->booking_payment_calculate(); 
        ////
        $data_patient = array(    
                  'branch_id'=>$branch_id,
                  'simulation_id'=>$post['simulation_id'],
                  'patient_name'=>$post['patient_name'],
                  'patient_email'=>$post['patient_email'],
                  'mobile_no'=>$post['mobile_no'],
                  'gender'=>$post['gender'],
                  'age_y'=>$post['age_y'],
                  'age_m'=>$post['age_m'],
                  'age_d'=>$post['age_d'],
                  'age_h'=>$post['age_h'],  
                  "address"=>$post['address'],
                  "address2"=>$post['address_second'],
                  "address3"=>$post['address_third'],
                  'relation_type'=>$post['relation_type'],
                  'relation_name'=>$post['relation_name'],
                  'relation_simulation_id'=>$post['relation_simulation_id'],
                  'city_id'=>$post['city_id'],
                  'state_id'=>$post['state_id'],
                  'country_id'=>$post['country_id'],
                  'insurance_type_id'=>$post['insurance_type_id'],
                  'ins_company_id'=>$post['ins_company_id'],
                  'polocy_no'=>$post['polocy_no'],
                  'tpa_id'=>$post['tpa_id'], 
                  'ins_amount'=>$post['ins_amount'],
                  'ins_authorization_no'=>$post['ins_authorization_no'], 
                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                  'patient_category'=>$post['patient_category'], 
                ); 
                
      $opd_id ='0';
          if(isset($post['opd_id']))
          {
            $opd_id = $post['opd_id'];  
          }  
          
          $reminder_date='';     
           if(!empty($post['reminder_days']))
           {
               $reminder_date = date('Y-m-d', strtotime(date('Y-m-d',strtotime($post['booking_date'])). ' + '.$post['reminder_days'].' days'));
           }
           
    $data_test = array(    
              'branch_id'=>$branch_id, 
              'dept_id'=>$post['dept_id'],
              'ipd_id'=>$post['ipd_id'],
              'opd_id'=>$opd_id,
              'profile_id'=>'0',  
              'profile_interpretation'=>$profile_interpretation,
              'profile_amount'=>$profile_price,
              'referral_doctor'=>$post['referral_doctor'],
              'attended_doctor'=>$post['attended_doctor'],
              'sample_collected_by'=>$post['sample_collected_by'],
              'staff_refrenace_id'=>$post['staff_refrenace_id'],
              'collection_center'=>$post['collection_center'],
              'booking_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'booking_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time'])),
              'total_master_amount'=>$final_calculation['total_master_amount'],
              'master_net_amount'=>$final_calculation['master_net_amount'],
              'total_base_amount'=>$final_calculation['total_base_amount'],
              'base_net_amount'=>$final_calculation['base_net_amount'],
              'total_amount'=>$final_calculation['total_master_amount'],
              'pannel_type'=>$post['pannel_type'],
              'branch_id' =>$branch_id,
              'gst_amount'=>$post['gst_amount'],
              'net_amount'=>$post['net_amount'],
              'paid_amount'=>$post['paid_amount'],
              'discount'=>$post['discount'],
              'remarks'=>$post['remarks'],
              'form_f'=>$post['form_f'],
              'tube_no'=>$post['tube_no'],
              'balance'=>$post['net_amount']-$post['paid_amount'],
              'payment_mode'=>$post['payment_mode'],
              'referred_by'=>$post['referred_by'],
              'next_app_date'=>date('Y-m-d', strtotime($post['next_app_date'])),
              'referral_hospital'=>$post['referral_hospital'],
              'home_collection_amount'=>$final_calculation['home_collection'],
              'is_home_collection'=>$post['is_home_collection'],
              'ref_by_other'=>$post['ref_by_other'],
              'diseases'=>$post['diseases'],
               'reminder_days'=>$post['reminder_days'],
              'reminder_date'=>$reminder_date,
              'patient_category'=>$post['patient_category'],
              'authorize_person'=>$post['authorize_person']
            );
         
    if(!empty($post['data_id']) && $post['data_id']>0)
    {    
            $booking_id = $post['data_id'];
            
            $booking_id = $post['data_id'];
            $this->db->select('booking_date');
            $this->db->where('id',$booking_id);
            $query = $this->db->get('path_test_booking');
            $booking_date_row = $query->row();

            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('path_test_booking',$data_test);
            //echo $this->db->last_query(); exit;


            if(!empty($post['ipd_id']) && !empty($booking_id)) 
            { 
              $this->db->where('ipd_id',$post['ipd_id']);
              $this->db->where('parent_id',$booking_id);
              $this->db->where('type','7');
              $this->db->where('patient_id',$post['patient_id']);
              $this->db->delete('hms_ipd_patient_to_charge');
              //echo $this->db->last_query(); exit;
            }  

            ///////// Set Profile in db ////////
            $profile_sess_data = $this->session->userdata('set_profile');
            //echo "<pre>";print_r($profile_sess_data);die;
            if(isset($profile_sess_data) && !empty($profile_sess_data))
            {
              $this->db->where('test_booking_id',$post['data_id']);
              $this->db->delete('path_test_booking_to_profile');
              foreach($profile_sess_data as $p_key=>$profile_sess)
              { 
                  
                  $profile_gst='0';
                 $gst_vals = $this->check_gst_vals('',$p_key);
                 if(!empty($gst_vals))
                 {
                     $profile_gst = $profile['price']-($profile['price']/(1+($gst_val/100)));
                    
                 }
                 
                  
                $prf_sess = array(
                                       'test_booking_id'=>$post['data_id'],
                                       'profile_id'=>$p_key,
                                       'master_price'=>$profile_sess['price'],
                                       'base_price'=>$profile_sess['base_price'],
                                       'gst_amount'=>$profile_gst,
                                       'parent_id'=>'0'
                                 );
                $this->db->insert('path_test_booking_to_profile',$prf_sess);

                //for ipd booking 

                 if(!empty($post['ipd_id'])) 
             {
                $ipd_profile_name = get_profile_name($p_key);//path_test
                $patient_pathology_charge = array(
                  "branch_id"=>$user_data['parent_id'],
                  'ipd_id'=>$post['ipd_id'],
                  'parent_id'=>$booking_id,
                  'patient_id'=>$post['patient_id'],
                  'type'=>7,
                  'quantity'=>1,
                  'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                  'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                  'particular'=>$ipd_profile_name,
                  'price'=>$profile_sess['price'],
                  'panel_price'=>$profile_sess['price'],
                  'net_price'=>$profile_sess['price'],
                  'status'=>1,
                  'created_date'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
                //echo $this->db->last_query(); exit;
             }

                ///end of ipd
                ///// Start Child profile  ////////////////
                $child_profile = $this->get_child_profile($p_key);
                //echo "<pre>";print_r($profile_sess_data);die;
                if(!empty($child_profile))
                {
                  foreach($child_profile as $child_p)
                  {

                      $child_profile_order = $this->profile_sort_order($p_key,$child_p['multi_profile_id']);

                     $prf_sess = array(
                                       'test_booking_id'=>$post['data_id'],
                                       'profile_id'=>$child_p['multi_profile_id'],
                                       'master_price'=>$profile_sess['price'],
                                       'base_price'=>$profile_sess['base_price'],
                                       'parent_id'=>$p_key,
                                       'sort_order'=>$child_profile_order
                                 );
                     $this->db->insert('path_test_booking_to_profile',$prf_sess);

                     //for ipd booking  child
                    //only parent profile price should reflect in ipd
                   /*if(!empty($post['ipd_id'])) 
               {
                  $ipd_child_profile_name = get_profile_name($child_p['multi_profile_id']);//path_test
                  $patient_pathology_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_id'=>$post['ipd_id'],
                    'parent_id'=>$booking_id,
                    'patient_id'=>$post['patient_id'],
                    'type'=>7,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                    'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                    'particular'=>$ipd_child_profile_name,
                    'price'=>$profile_sess['price'],
                    'panel_price'=>$profile_sess['price'],
                    'net_price'=>$profile_sess['price'],
                    'status'=>1,
                    'created_date'=>date('Y-m-d H:i:s')
                  );
                  $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
                  //echo $this->db->last_query(); exit;
               }*/

                  ///end of ipd
                  } 
                   //echo $this->db->last_query();die;
                }
                ///// End Child Profile ////////////
              }
            }
            else
            {
                $this->db->where('test_booking_id',$post['data_id']);
              $this->db->delete('path_test_booking_to_profile');
            }
            ///////////////////////////////////  

            /*add sales banlk detail*/
    $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'section_id'=>10));
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
        'section_id'=>10,
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
 // if(empty($post['ipd_id']))  // commented on july 4 2020 as discuss with Harikesh Sir
  //{
            /*add sales banlk detail*/  
            
            $this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','1');
            $this->db->where('balance>0');
            $this->db->where('patient_id',$post['patient_id']);
            $query_d_pay = $this->db->get('hms_payment');
            $row_d_pay = $query_d_pay->result();

            $credit = $final_calculation['net_amount'];
            if(!empty($booking_doctor_type))
            {
               $credit = $final_calculation['total_base_amount'];
            } 
//+$post['home_collection_amount']
            $total_bal = ($post['net_amount'])-$post['paid_amount'];
            if($total_bal>0)
            {
              $pay_balance = $total_bal;
            }
            else
            {
              $pay_balance = 1;
            }    
            $booking_id = $post['data_id'];
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('path_test_booking',$data_test);
            ///////// Set Profile in db ////////
            $profile_sess_data = $this->session->userdata('set_profile');
            //echo "<pre>";print_r($profile_sess_data);die;
            if(isset($profile_sess_data) && !empty($profile_sess_data))
            {
              $this->db->where('test_booking_id',$post['data_id']);
              $this->db->delete('path_test_booking_to_profile');
              foreach($profile_sess_data as $p_key=>$profile_sess)
              {   
                $prf_sess = array(
                                       'test_booking_id'=>$post['data_id'],
                                       'profile_id'=>$p_key,
                                       'net_amount'=>$profile_sess['price']-$per_booked_tp_discount,
                                       'master_price'=>$profile_sess['price'],
                                       'base_price'=>$profile_sess['base_price'],
                                       'parent_id'=>'0'
                                 );
                $this->db->insert('path_test_booking_to_profile',$prf_sess);

                //for ipd booking 

                  /* if(!empty($post['ipd_id'])) 
               {
                  $ipd_profile_name = get_profile_name($p_key);//path_test
                  $patient_pathology_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_id'=>$post['ipd_id'],
                    'parent_id'=>$booking_id,
                    'patient_id'=>$post['patient_id'],
                    'type'=>7,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                    'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                    'particular'=>$ipd_profile_name,
                    'price'=>$profile_sess['price'],
                    'panel_price'=>$profile_sess['price'],
                    'net_price'=>$profile_sess['price'],
                    'status'=>1,
                    'created_date'=>date('Y-m-d H:i:s')
                  );
                  $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
                  //echo $this->db->last_query(); exit;
               }*/

                  ///end of ipd


                ///// Start Child profile  ////////////////
                $child_profile = $this->get_child_profile($p_key);
                //echo "<pre>";print_r($profile_sess_data);die;
                if(!empty($child_profile))
                {
                  foreach($child_profile as $child_p)
                  {
                    $child_profile_order = $this->profile_sort_order($p_key,$child_p['multi_profile_id']);

                     $prf_sess = array(
                                       'test_booking_id'=>$post['data_id'],
                                       'profile_id'=>$child_p['multi_profile_id'],
                                       'net_amount'=>$profile_sess['price']-$per_booked_tp_discount,
                                       'master_price'=>$profile_sess['price'],
                                       'base_price'=>$profile_sess['base_price'],
                                       'parent_id'=>$p_key,
                                       'sort_order'=>$child_profile_order
                                 );
                     $this->db->insert('path_test_booking_to_profile',$prf_sess);


                //for ipd booking 
                     //only parent profile in ipd
               /*if(!empty($post['ipd_id'])) 
               {
                  $ipd_child_profile_name = get_profile_name($child_p['multi_profile_id']);//path_test
                  $patient_pathology_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_id'=>$post['ipd_id'],
                    'parent_id'=>$booking_id,
                    'patient_id'=>$post['patient_id'],
                    'type'=>7,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                    'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                    'particular'=>$ipd_child_profile_name,
                    'price'=>$profile_sess['price'],
                    'panel_price'=>$profile_sess['price'],
                    'net_price'=>$profile_sess['price'],
                    'status'=>1,
                    'created_date'=>date('Y-m-d H:i:s')
                  );
                  $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
                  //echo $this->db->last_query(); exit;
               }*/

                  ///end of ipd
                  } 
                   //echo $this->db->last_query();die;
                }
                ///// End Child Profile ////////////
              }
            }
            ///////////////////////////////////  

            /*add sales banlk detail*/
    $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'section_id'=>10));
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
        'section_id'=>10,
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
  //if(empty($post['ipd_id']))  //  commented on july 4 2020 as discuss with Harikesh Sir
  //{
            /*add sales banlk detail*/  
            
            $this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','1');
            $this->db->where('balance>0');
            $this->db->where('patient_id',$post['patient_id']);
            $query_d_pay = $this->db->get('hms_payment');
            $row_d_pay = $query_d_pay->result();

            $credit = $final_calculation['net_amount'];
            if(!empty($booking_doctor_type))
            {
               $credit = $final_calculation['total_base_amount'];
            } 
            //+$post['home_collection_amount']
            $total_bal = ($post['net_amount'])-$post['paid_amount'];
            if($total_bal>0)
            {
              $pay_balance = $total_bal;
            }
            else
            {
              $pay_balance = 1;
            }


            if(!empty($row_d_pay))
            {
              foreach($row_d_pay as $row_d)
              {
                  $pay_data = array(
                      'parent_id'=>$booking_id,
                      'doctor_id'=>$post['referral_doctor'],
                      'hospital_id'=>$post['referral_hospital'],
                      'branch_id'=>$branch_id,
                      'section_id'=>'1',
                      'patient_id'=>$post['patient_id'],
                      'total_master_amount'=>$final_calculation['total_master_amount'],
                      'master_net_amount'=>$final_calculation['master_net_amount'],
                      'total_base_amount'=>$final_calculation['total_base_amount'],
                      'base_net_amount'=>$final_calculation['base_net_amount'],
                      'total_amount'=>$final_calculation['total_master_amount'], 
                      'discount_amount'=>$post['discount'],
                      'net_amount'=>$final_calculation['net_amount'],
                      'credit'=>$final_calculation['net_amount'], 
                      'debit'=>$post['paid_amount'],
                      'test_base_rate'=>$final_calculation['test_base_rate'], 
                      'test_master_rate'=>$final_calculation['test_master_rate'], 
                      'total_comission'=>$total_comission_data,
                      'balance'=>$pay_balance,
                      'pay_mode'=>$post['payment_mode'],
                      'created_by'=>$row_d->created_by,//$user_data['id'],
                      'created_date'=>$row_d->created_date, //date('Y-m-d H:i:s',strtotime($post['booking_date'])),
                    );
                  $this->db->where('id',$row_d->id); 
                  $this->db->update('hms_payment',$pay_data);
                  //echo $this->db->last_query(); exit;
              }
            }

        //} // commented on july 4 2020 as discuss with Harikesh Sir
            /*add sales banlk detail*/
    $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>5,'section_id'=>10));
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
        'type'=>5,
        'section_id'=>10,
        'p_mode_id'=>$post['payment_mode'],
        'branch_id'=>$user_data['parent_id'],
        'parent_id'=>$booking_id,
        'ip_address'=>$_SERVER['REMOTE_ADDR']
        );
        $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

      }
    }

    /*add sales banlk detail*/
            // End payment setup /////

      $this->db->set('modified_by',$user_data['id']);
      $this->db->set('modified_date',date('Y-m-d H:i:s'));
      $this->db->where('id',$post['patient_id']);
      $this->db->update('hms_patient',$data_patient); 
      $this->db->where('booking_id',$post['data_id']);
      $this->db->delete('path_test_booking_to_test'); 
      $booked_list = $this->session->userdata('book_test');   
      //echo "<pre>"; print_r($booked_list); die;
      if(!empty($booked_list))
      {
        $all_test = [];
        $booked_test_ids_arr = array_column($booked_list, 'id');
        if($profile_id>0)
        {
          $profile_test = '';
          if(!empty($profile_all_test))
          { 
            foreach($profile_all_test as $profile_test)
            {
              //echo "<pre>";print_r($profile_test);die;
              $booked_test_ids_arr[] = $profile_test['id'];
            }
          }
        } 
                
         //echo "<pre>"; print_r($booked_test_ids_arr);die;      
        foreach($booked_test_ids_arr as $test)
        {
          $all_child = $this->getChildren($test); 
            $all_test = array_merge($all_child,$all_test);
            $all_test[] = $test;
        }    
        $unique_test_list = array_unique($all_test);
        $unique_test_list = array_reverse($unique_test_list);
        
               
       //echo "<pre>"; print_r($unique_test_list);die;  
        if(!empty($unique_test_list))
        {  
          
             
                    //echo "<pre>";print_r($unique_test_list);die;
          foreach($unique_test_list as $unique_test)
          {
            $tst_profile_id = $this->test_to_booked_profile_id($unique_test);
            //echo $tst_profile_id;die;
            
            $test_data = get_test($unique_test);
            $parent_status = 0; 
            $child_profile_status = '0';
            $rate = 0;  

            if(in_array($unique_test,$booked_test_ids_arr))
            {  
               $parent_status = 1; 
               if(array_key_exists($unique_test, $booked_list))
               {
                 $rate = $booked_list[$unique_test]['price'];
               }
               else
               {
                 $rate = 0;
               }
                           
            } 


            ////////// Set Test Type 
            $booked_list_column = array_column($booked_list, 'id'); 
            if(in_array($unique_test,$booked_list_column) && in_array($unique_test,array_column($profile_all_test, 'id')))
            { 
              $test_type_para = 2;              
              $pkey = array_search($unique_test,array_column($profile_all_test, 'id'));
              if(isset($profile_all_test[$pkey]['child_profile_status']) && !empty($profile_all_test[$pkey]['child_profile_status']))
              {
                $child_profile_status = $profile_all_test[$pkey]['child_profile_status'];
                $tst_profile_id = $profile_all_test[$pkey]['profile_id'];
              }
              $rate = $profile_all_test[$pkey]['price']; 
            } 
            else if(in_array($unique_test,array_column($profile_all_test, 'id')))
            {   
                $test_type_para = 1;
                $parent_status==0;
                $pkey = array_search($unique_test,array_column($profile_all_test, 'id'));
                if(isset($profile_all_test[$pkey]['child_profile_status']) && !empty($profile_all_test[$pkey]['child_profile_status']))
                {
                  $child_profile_status = $profile_all_test[$pkey]['child_profile_status'];
                  $tst_profile_id = $profile_all_test[$pkey]['profile_id'];
                }
                $rate = $profile_all_test[$pkey]['price']; 
            }
            else
            {
              $test_type_para = 0;
              $child_profile_status = '0';
            }
                        //////////////////////////
                        // Set Discount test Price
            if($post['discount']>0  && ($parent_status==1 || $test_type_para==1 || $test_type_para==2))
            {    
              $total_discount = $post['discount'];
              $count_total_array = array_merge($this->session->userdata('book_test'),$profile_all_test);
              $total_tests = count($this->session->userdata('book_test'));

            //echo "<pre>";print_r($count_total_array);die; 
              $per_test_discount = $total_discount/$total_tests; 
               
              $test_net_amount = $rate-$per_booked_tp_discount; 
              
              
            }  
            else
            {
              $test_net_amount = $rate;
            } 
                        //// End    
            $test_interpretation = '';
            $result_test_data = get_test($unique_test);
            $multi_interp_data = test_multi_interpration($unique_test,'0');
             
            if(!empty($multi_interp_data))
            {
              $test_interpretation = $multi_interp_data[0]['interpration'];
            }
            else
            {
              $test_interpretation = $test_data->interpretation;
            }
            
            
             $test_gst='0';
                 $gst_vals = $this->check_gst_vals($unique_test);
                 if(!empty($gst_vals))
                 {
                   //$test_gst = ($gst_val/100*$test_net_amount); 
                    
                    $test_gst = $test_net_amount-($test_net_amount/(1+($gst_val/100)));
                    
                 }
            
            
            $booked_data = array(
                                  'booking_id'=>$booking_id,
                                  'test_id'=>$unique_test,
                                  'test_type'=>$test_type_para,
                                  'parent_status'=>$parent_status,
                                  'base_rate'=>$test_data->base_rate, 
                                  'master_rate'=>$test_data->rate, 
                                  'profile_id'=>$tst_profile_id,
                                  'interpretation'=>1, 
                                  'interpratation_data'=>$test_interpretation,
                                  'result'=>$result_test_data->default_value, 
                                  'print'=>1, 
                                  'child_profile_status'=>$child_profile_status, 
                                  'amount'=>$rate,
                                  'gst_amount'=>$test_gst,
                                  'net_amount'=>$test_net_amount, 
                              );
            $this->db->insert('path_test_booking_to_test',$booked_data);
             
            //echo $this->db->last_query()
          //patient to charge for ipd
          if(!empty($post['ipd_id']) && !empty($booking_id) && $test_type_para==0) 
          { 
            $profile_name = get_test_name($unique_test);//path_test
            $patient_pathology_charge = array(
              "branch_id"=>$user_data['parent_id'],
              'ipd_id'=>$post['ipd_id'],
              'parent_id'=>$booking_id,
              'patient_id'=>$post['patient_id'],
              'type'=>7,
              'quantity'=>1,
              'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'particular'=>$profile_name,
              'price'=>$test_net_amount,
              'panel_price'=>$test_net_amount,
              'net_price'=>$test_net_amount,
              'status'=>1,
              'created_date'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
          //echo $this->db->last_query(); exit;
          }




          }
        }  
      } 
      else if(!empty($profile_all_test))
      {
          foreach($profile_all_test as $ptest)
          {   
            $test_interpretation = '';
            $tst_profile_id = $this->test_to_booked_profile_id($ptest['id']);
            $test_data = get_test($ptest['id']); 
            $multi_interp_data = test_multi_interpration($ptest['id'],'0');
            if(!empty($multi_interp_data))
            {
              $test_interpretation = $multi_interp_data[0]['interpration'];
            }
            else
            {
              $test_interpretation = $test_data->interpretation;
            }
            $booked_data = array(
                      'booking_id'=>$booking_id,
                      'test_id'=>$ptest['id'],
                      'test_type'=>1,  
                      'base_rate'=>$test_data->base_rate, 
                      'master_rate'=>$test_data->rate, 
                      'interpretation'=>1,
                      'profile_id'=>$ptest['profile_id'],
                      'interpratation_data'=>$test_interpretation,
                      'result'=>$test_data->default_value, 
                      'child_profile_status'=>$ptest['child_profile_status'], 
                      'print'=>1, 
                      'amount'=>$test_data->rate, 
                        );
          $this->db->insert('path_test_booking_to_test',$booked_data);
          
          //patient to charge for ipd
         if(!empty($post['ipd_id']) && !empty($booking_id)) 
          {
            $test_name = get_profile_name($ptest['id']);//path_test
            $patient_pathology_charge = array(
              "branch_id"=>$user_data['parent_id'],
              'ipd_id'=>$post['ipd_id'],
              'parent_id'=>$booking_id,
              'patient_id'=>$post['patient_id'],
              'type'=>7,
              'quantity'=>1,
              'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'particular'=>$test_name,
              'price'=>$test_data->base_rate,
              'panel_price'=>$test_data->base_rate,
              'net_price'=>$test_data->base_rate,
              'status'=>1,
              'created_date'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
            //echo $this->db->last_query(); exit;
          }
          } 
      }


      


    


            if(!empty($row_d_pay))
            {
              if($booking_date_row->booking_date!=date('Y-m-d',strtotime($post['booking_date'])))  
              {
                $payment_created_date = date('Y-m-d',strtotime($post['booking_date']));
              }
              else
              {
                $payment_created_date = $row_d->created_date;
              }
              
              
              
              foreach($row_d_pay as $row_d)
              {
                  if(!empty($row_d->created_by))  
                  {
                    $payment_created_by = $row_d->created_by;
                  }
                  else
                  {
                    $payment_created_by = $user_data['id'];
                  }
                  $pay_data = array(
                      'parent_id'=>$booking_id,
                      'doctor_id'=>$post['referral_doctor'],
                      'hospital_id'=>$post['referral_hospital'],
                      'branch_id'=>$branch_id,
                      'section_id'=>'1',
                      'patient_id'=>$post['patient_id'],
                      'total_master_amount'=>$final_calculation['total_master_amount'],
                      'master_net_amount'=>$final_calculation['master_net_amount'],
                      'total_base_amount'=>$final_calculation['total_base_amount'],
                      'base_net_amount'=>$final_calculation['base_net_amount'],
                      'total_amount'=>$final_calculation['total_master_amount'], 
                      'discount_amount'=>$post['discount'],
                      'net_amount'=>$final_calculation['net_amount'],
                      'credit'=>$final_calculation['net_amount'], 
                      'debit'=>$post['paid_amount'],
                      'test_base_rate'=>$final_calculation['test_base_rate'], 
                      'test_master_rate'=>$final_calculation['test_master_rate'], 
                      'balance'=>$pay_balance,
                      'pay_mode'=>$post['payment_mode'],
                      'created_by'=>$payment_created_by,
                      'created_date'=>$payment_created_date, //date('Y-m-d H:i:s',strtotime($post['booking_date'])),
                    );
                  $this->db->where('id',$row_d->id); 
                  $this->db->update('hms_payment',$pay_data);
              }
            }


        //} // commented on july 4 2020 as discuss with Harikesh Sir
            /*add sales banlk detail*/
    $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>5,'section_id'=>10));
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
        'type'=>5,
        'section_id'=>10,
        'p_mode_id'=>$post['payment_mode'],
        'branch_id'=>$user_data['parent_id'],
        'parent_id'=>$booking_id,
        'ip_address'=>$_SERVER['REMOTE_ADDR']
        );
        $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

      }
    }

    /*add sales banlk detail*/
            // End payment setup /////

      $this->db->set('modified_by',$user_data['id']);
      $this->db->set('modified_date',date('Y-m-d H:i:s'));
      $this->db->where('id',$post['patient_id']);
      $this->db->update('hms_patient',$data_patient); 
      $this->db->where('booking_id',$post['data_id']);
      $this->db->delete('path_test_booking_to_test'); 
      $booked_list = $this->session->userdata('book_test');   

      if(!empty($booked_list))
      {
        $all_test = [];
        $booked_test_ids_arr = array_column($booked_list, 'id');
        if($profile_id>0)
        {
          $profile_test = '';
          if(!empty($profile_all_test))
          { 
            foreach($profile_all_test as $profile_test)
            {
              //echo "<pre>";print_r($profile_test);die;
              $booked_test_ids_arr[] = $profile_test['id'];
            }
          }
        } 
                
         //echo "<pre>"; print_r($booked_test_ids_arr);die;      
        foreach($booked_test_ids_arr as $test)
        {
          $all_child = $this->getChildren($test); 
            $all_test = array_merge($all_child,$all_test);
            $all_test[] = $test;
        }    
        $unique_test_list = array_unique($all_test);
        $unique_test_list = array_reverse($unique_test_list);
        
               

        if(!empty($unique_test_list))
        {  
          
           
                    //echo "<pre>";print_r($unique_test_list);die;
          foreach($unique_test_list as $unique_test)
          {
            $tst_profile_id = $this->test_to_booked_profile_id($unique_test);
            //echo $tst_profile_id;die;

            $test_data = get_test($unique_test);
            $parent_status = 0; 
            $child_profile_status = '0';
            $rate = 0;  

            if(in_array($unique_test,$booked_test_ids_arr))
            {  
               $parent_status = 1; 
               if(array_key_exists($unique_test, $booked_list))
               {
                 $rate = $booked_list[$unique_test]['price'];
               }
               else
               {
                 $rate = 0;
               }
                           
            } 


            ////////// Set Test Type 
            $booked_list_column = array_column($booked_list, 'id'); 
            if(in_array($unique_test,$booked_list_column) && in_array($unique_test,array_column($profile_all_test, 'id')))
            { 
              $test_type_para = 2;              
              $pkey = array_search($unique_test,array_column($profile_all_test, 'id'));
              if(isset($profile_all_test[$pkey]['child_profile_status']) && !empty($profile_all_test[$pkey]['child_profile_status']))
              {
                $child_profile_status = $profile_all_test[$pkey]['child_profile_status'];
                $tst_profile_id = $profile_all_test[$pkey]['profile_id'];
              }
              $rate = $profile_all_test[$pkey]['price']; 
            } 
            else if(in_array($unique_test,array_column($profile_all_test, 'id')))
            {   
                $test_type_para = 1;
                $parent_status==0;
                $pkey = array_search($unique_test,array_column($profile_all_test, 'id'));
                if(isset($profile_all_test[$pkey]['child_profile_status']) && !empty($profile_all_test[$pkey]['child_profile_status']))
                {
                  $child_profile_status = $profile_all_test[$pkey]['child_profile_status'];
                  $tst_profile_id = $profile_all_test[$pkey]['profile_id'];
                }
                $rate = $profile_all_test[$pkey]['price']; 
            }
            else
            {
              $test_type_para = 0;
              $child_profile_status = '0';
            }
                        //////////////////////////
                        // Set Discount test Price
            if($post['discount']>0  && ($parent_status==1 || $test_type_para==1 || $test_type_para==2))
            {    
              $total_discount = $post['discount'];
              $count_total_array = array_merge($this->session->userdata('book_test'),$profile_all_test);
              $total_tests = count($this->session->userdata('book_test'));

            //echo "<pre>";print_r($count_total_array);die;
              $per_test_discount = $total_discount/$total_tests;
              $test_net_amount = $rate-$per_booked_tp_discount;  
            }  
            else
            {
              $test_net_amount = $rate;
            } 
                        //// End    
            $test_interpretation = '';
            $result_test_data = get_test($unique_test);
            $multi_interp_data = test_multi_interpration($unique_test,'0');
             
            if(!empty($multi_interp_data))
            {
              $test_interpretation = $multi_interp_data[0]['interpration'];
            }
            else
            {
              $test_interpretation = $test_data->interpretation;
            } 

            $temp_result_data=$this->check_temp_test($booking_id,$unique_test);
         // print_r($temp_result_data);die;
          if(!empty($temp_result_data))
          {
            $booked_data = array(
              'booking_id'=>$booking_id,
              'test_id'=>$unique_test,
              'test_type'=>$test_type_para,
              'parent_status'=>$parent_status, 
              'profile_id'=>$tst_profile_id, 
              'result'=>$temp_result_data->result, 
              'sample_type_id'=>$temp_result_data->sample_type_id, 
              'child_profile_status'=>$child_profile_status, 
               'amount'=>$rate,//$temp_result_data->amount,
              'net_amount'=>$test_net_amount,//$temp_result_data->net_amount,
              'base_rate'=>$temp_result_data->base_rate,
              'master_rate'=>$temp_result_data->master_rate,
              'interpretation'=>$temp_result_data->interpretation,
              'interpratation_data'=>$temp_result_data->interpretation_data,
              'adv_interpretation_status'=>$temp_result_data->adv_interpretation_status,
              'print'=>$temp_result_data->print

            );
          }
          else
          {
            $booked_data = array(
                                  'booking_id'=>$booking_id,
                                  'test_id'=>$unique_test,
                                  'test_type'=>$test_type_para,
                                  'parent_status'=>$parent_status,
                                  'base_rate'=>$test_data->base_rate, 
                                  'master_rate'=>$test_data->rate, 
                                  'profile_id'=>$tst_profile_id,
                                  'interpretation'=>1, 
                                  'interpratation_data'=>$test_interpretation,
                                  'result'=>$result_test_data->default_value, 
                                  'print'=>1, 
                                  'child_profile_status'=>$child_profile_status, 
                                  'amount'=>$rate,
                                  'net_amount'=>$test_net_amount, 
                              );

            }
            $this->db->insert('path_test_booking_to_test',$booked_data);
            //echo $this->db->last_query()
          //patient to charge for ipd
          /*if(!empty($post['ipd_id']) && !empty($booking_id) && $test_type_para==0) 
          { 
            $profile_name = get_test_name($unique_test);//path_test
            $patient_pathology_charge = array(
              "branch_id"=>$user_data['parent_id'],
              'ipd_id'=>$post['ipd_id'],
              'parent_id'=>$booking_id,
              'patient_id'=>$post['patient_id'],
              'type'=>7,
              'quantity'=>1,
              'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'particular'=>$profile_name,
              'price'=>$test_net_amount,
              'panel_price'=>$test_net_amount,
              'net_price'=>$test_net_amount,
              'status'=>1,
              'created_date'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
          //echo $this->db->last_query(); exit;
          }*/




          }
        }  
      } 
      else if(!empty($profile_all_test))
      {
          foreach($profile_all_test as $ptest)
          {   
            $test_interpretation = '';
            $tst_profile_id = $this->test_to_booked_profile_id($ptest['id']);
            $test_data = get_test($ptest['id']); 
            $multi_interp_data = test_multi_interpration($ptest['id'],'0');
            if(!empty($multi_interp_data))
            {
              $test_interpretation = $multi_interp_data[0]['interpration'];
            }
            else
            {
              $test_interpretation = $test_data->interpretation;
            }

            $temp_result_data=$this->check_temp_test($booking_id,$ptest['id']);
            if(!empty($temp_result_data))
            {
             $booked_data = array(
              'booking_id'=>$booking_id,
              'test_id'=>$ptest['id'],
              'test_type'=>1,  
              'profile_id'=>$ptest['profile_id'],   
              'result'=>$temp_result_data->result, 
              'sample_type_id'=>$temp_result_data->sample_type_id,
              'child_profile_status'=>$ptest['child_profile_status'],
              'amount'=>$temp_result_data->amount,
              'net_amount'=>$temp_result_data->net_amount,
              'base_rate'=>$temp_result_data->base_rate,
              'master_rate'=>$temp_result_data->master_rate,
              'interpretation'=>$temp_result_data->interpretation,
              'interpratation_data'=>$temp_result_data->interpretation_data,
              'adv_interpretation_status'=>$temp_result_data->adv_interpretation_status,
              'print'=>$temp_result_data->print


            );
           }
           else
           {
              $booked_data = array(
                        'booking_id'=>$booking_id,
                        'test_id'=>$ptest['id'],
                        'test_type'=>1, 
                        'base_rate'=>$test_data->base_rate, 
                        'master_rate'=>$test_data->rate, 
                        'interpretation'=>1,
                        'profile_id'=>$ptest['profile_id'],
                        'interpratation_data'=>$test_interpretation,
                        'result'=>$test_data->default_value, 
                        'child_profile_status'=>$ptest['child_profile_status'], 
                        'print'=>1, 
                        'amount'=>$test_data->rate, 
                          );
            }
          $this->db->insert('path_test_booking_to_test',$booked_data);
          
          //patient to charge for ipd
          if(!empty($post['ipd_id']) && !empty($booking_id)) 
          {
            $test_name = get_profile_name($ptest['id']);//path_test
            $patient_pathology_charge = array(
              "branch_id"=>$user_data['parent_id'],
              'ipd_id'=>$post['ipd_id'],
              'parent_id'=>$booking_id,
              'patient_id'=>$post['patient_id'],
              'type'=>7,
              'quantity'=>1,
              'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
              'particular'=>$test_name,
              'price'=>$test_data->base_rate,
              'panel_price'=>$test_data->base_rate,
              'net_price'=>$test_data->base_rate,
              'status'=>1,
              'created_date'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
            //echo $this->db->last_query(); exit;
          }
          } 
      }

      ///token generate ////
      
      // for token insert
      if(in_array('2070',$user_data['permission']['action']))
        {
          $book_date=date('Y-m-d',strtotime($post['booking_date']));

          if($tokentype['type']==0)
          { 
            $token_no=$this->test_token($depid='',$book_date,$tokentype['type']);
            $tokenarray=array(
              'branch_id'=>$user_data['parent_id'],
              'patient_id'=>$post['patient_id'],
              'department_id'=>0,
              'booking_id'=>$booking_id,
              'booking_date'=>$book_date,
              'token_no'=>$token_no,
              'type'=>$tokentype['type']
            );
            $this->db->insert('path_patient_to_token',$tokenarray);
           }
           else if($tokentype['type']==1)
           {

            $exist_deptid=$this->get_token_exist_or_not($post['patient_id'],$booking_id,$book_date,$tokentype['type'],$user_data['parent_id']);
            //print_r($exist_deptid);die();
            if(!empty($exist_deptid))
            {
               $existed_dept_ids=array();
               foreach ($exist_deptid as $exist_dep)
               {
                $existed_dept_ids[]= $exist_dep['department_id'];
               }
                foreach ($exist_deptid as $exist_dep) {
                  if(in_array($exist_dep['department_id'], $dep_uni))
                  {

                  }
                  else{ // delete entries
                      $this->db->where('id',$exist_dep['id']);
                      $this->db->delete('path_patient_to_token');
                  }
              
                }
                foreach ($dep_uni as $unique_dept_id) {
                 if(in_array($unique_dept_id, $existed_dept_ids))
                  {
                  
                  }
                  else{ 
                      $token_no=$this->test_token($unique_dept_id,$book_date,$tokentype['type']);
                      $tokenarray=array(
                        'branch_id'=>$user_data['parent_id'],
                        'patient_id'=>$post['patient_id'],
                        'department_id'=>$unique_dept_id,
                        'booking_id'=>$booking_id,
                        'booking_date'=>$book_date,
                        'token_no'=>$token_no,
                        'type'=>$tokentype['type']
                      );
                      $this->db->insert('path_patient_to_token',$tokenarray);
                  }
              
                }
            }
            else
            {
                foreach ($dep_uni as $depid) {
                $token_no=$this->test_token($depid,$book_date,$tokentype['type']);
                $tokenarray=array(
                  'branch_id'=>$user_data['parent_id'],
                  'patient_id'=>$post['patient_id'],
                  'department_id'=>$depid,
                  'booking_id'=>$booking_id,
                  'booking_date'=>$book_date,
                  'token_no'=>$token_no,
                  'type'=>$tokentype['type']
                );
                $this->db->insert('path_patient_to_token',$tokenarray);
                }
            }
          }
       }
      
      
      /// End token genrate //
      


    }
    else
    { 
      //echo "dsd"; die;
      //echo "<pre>"; print_r($post); exit;
      $is_outsource=0;
        if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
          $patient_id = $post['patient_id'];
        } 
        else
        { 
          $patient_code = generate_unique_id(4);
          $this->db->set('patient_code',$patient_code); 
          $this->db->set('created_date',date('Y-m-d H:i:s'));
          $this->db->set('created_by',$user_data['id']);
          $this->db->insert('hms_patient',$data_patient);  
          $patient_id = $this->db->insert_id();

          
                

          $this->session->set_userdata('booking_patient_id',$patient_id); 
          ////// Set Patient
          $data = array(     
          "users_role"=>4,
          "parent_id"=>$patient_id,
          "username"=>'PAT000'.$patient_id,
          "password"=>md5('PASS'.$patient_id),
          "email"=>$post['patient_email'], 
          "status"=>'1',
          "ip_address"=>$_SERVER['REMOTE_ADDR'],
          "created_by"=>$user_data['id'],
          "created_date" =>date('Y-m-d H:i:s')
                 ); 
        $this->db->insert('hms_users',$data); 
              $users_id = $this->db->insert_id();    
       /* $this->db->select('*');
        $this->db->where('users_role','4');
        $query = $this->db->get('hms_permission_to_role');     
        $permission_list = $query->result();
        if(!empty($permission_list))
        {
          foreach($permission_list as $permission)
          {
            $data = array(
                    'users_role' =>4,
                    'users_id' => $users_id,
                    'master_id' => $patient_id,
                    'section_id' => $permission->section_id,
                    'action_id' => $permission->action_id, 
                    'permission_status' => '1',
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'created_by' =>$user_data['id'],
                    'created_date' =>date('Y-m-d H:i:s'),
                 );
            $this->db->insert('hms_permission_to_users',$data);
          }
        }*/

        ////////// Send SMS /////////////////////
        if(in_array('640',$user_data['permission']['action']))
        {
          if(!empty($post['mobile_no']))
          {
           send_sms('patient_registration',18,'',$post['mobile_no'],array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id));
          }
        }
    //////////////////////////////////////////

    ////////// SEND EMAIL ///////////////////
        if(!empty($post['patient_email']))
          { 
            $this->load->library('general_library'); 
            $this->general_library->email($post['patient_email'],'','','','','','patient_registration','18',array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id)); 
          }
        ////////////////////////////////////////
          ///// End /////////
        }
            $this->db->set('lab_reg_no',$lab_reg_no);
            $this->db->set('patient_id',$patient_id);
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('path_test_booking',$data_test);    
            $booking_id = $this->db->insert_id(); 
            //echo $this->db->last_query(); exit;
            ///////// Set Profile in db ////////
            $profile_sess_data = $this->session->userdata('set_profile');
            //echo "<pre>";print_r($profile_sess_data);die;
            if(isset($profile_sess_data) && !empty($profile_sess_data))
            {
              foreach($profile_sess_data as $p_key=>$profile_sess)
              { 
                  
                    $profile_gst='0';
                 $gst_vals = $this->check_gst_vals('',$p_key);
                 if(!empty($gst_vals))
                 {
                    //$profile_gst = ($gst_val/100*$profile_sess['price']); 
                    $profile_gst = $profile_sess['price']-($profile_sess['price']/(1+($gst_val/100)));
                 }

                $prf_sess = array(
                                   'test_booking_id'=>$booking_id,
                                   'profile_id'=>$p_key,
                                   'net_amount'=>$profile_sess['price']-$per_booked_tp_discount,
                                   'gst_amount'=>$profile_gst,
                                   'master_price'=>$profile_sess['price'],
                                   'base_price'=>$profile_sess['base_price']
                                 );
                $this->db->insert('path_test_booking_to_profile',$prf_sess);



                //for ipd booking 

            if(!empty($post['ipd_id'])) 
             {
                $ipd_profile_name = get_profile_name($p_key);//path_test
                $patient_pathology_charge = array(
                  "branch_id"=>$user_data['parent_id'],
                  'ipd_id'=>$post['ipd_id'],
                  'parent_id'=>$booking_id,
                  'patient_id'=>$post['patient_id'],
                  'type'=>7,
                  'quantity'=>1,
                  'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                  'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                  'particular'=>$ipd_profile_name,
                  'price'=>$profile_sess['price'],
                  'panel_price'=>$profile_sess['price'],
                  'net_price'=>$profile_sess['price'],
                  'status'=>1,
                  'created_date'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
                //echo $this->db->last_query(); exit;
             }

                ///end of ipd


                ///// Start Child profile  ////////////////
                $child_profile = $this->get_child_profile($p_key);
                if(!empty($child_profile))
                {
                  foreach($child_profile as $child_p)
                  {

                    $child_profile_order = $this->profile_sort_order($p_key,$child_p['multi_profile_id']);


                     $prf_sess = array(
                                       'test_booking_id'=>$booking_id,
                                       'profile_id'=>$child_p['multi_profile_id'],
                                       'net_amount'=>$profile_sess['price']-$per_booked_tp_discount,
                                       'master_price'=>$profile_sess['price'],
                                       'base_price'=>$profile_sess['base_price'],
                                       'parent_id'=>$p_key,
                                       'sort_order'=>$child_profile_order
                                 );
                     $this->db->insert('path_test_booking_to_profile',$prf_sess);

                      //for ipd booking 
                     //only parent profile
              

                  ///end of ipd
                  } 
                   //echo $this->db->last_query();die;
                }
                ///// End Child Profile ////////////
              }
            }
            ///////////////////////////////////
          

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
        'section_id'=>10,
        'p_mode_id'=>$post['payment_mode'],
        'branch_id'=>$user_data['parent_id'],
        'parent_id'=>$booking_id,
        'ip_address'=>$_SERVER['REMOTE_ADDR']
        );
        $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

      }
    }

    //if(empty($post['ipd_id']))  // commented on july 4 2020 as discuss with Harikesh Sir
    //{
            /*add sales banlk detail*/
            // Payment setup///
            $credit = $final_calculation['net_amount'];
            if(!empty($booking_doctor_type))
            {
              $credit = $final_calculation['total_base_amount'];
            }
            $home_collection_amount='0';
            if(!empty($post['home_collection_amount']))
            {
              $home_collection_amount = $post['home_collection_amount'];
            }
            //+$home_collection_amount
            $total_bal = ($post['net_amount'])-$post['paid_amount'];
            if($total_bal>0)
            {
              $pay_balance = $total_bal;
            }
            else
            {
              $pay_balance = 1;
            }
            $debit = $post['paid_amount'];
            $credits = $final_calculation['net_amount'];
            if(!empty($post['ipd_id'])) 
            {
              $pay_balance=0;
              $debit = 0; //$final_calculation['net_amount'];
              $credits = 0;
            }
            
            $pay_data = array(
                        'parent_id'=>$booking_id,
                        'doctor_id'=>$post['referral_doctor'],
                        'hospital_id'=>$post['referral_hospital'],
                        'branch_id'=>$branch_id,
                        'section_id'=>'1',
                        'patient_id'=>$patient_id,
                        'total_base_amount'=>$final_calculation['total_base_amount'],
                        'base_net_amount'=>$final_calculation['base_net_amount'],
                        'total_amount'=>$final_calculation['total_master_amount'],  
                        'discount_amount'=>$post['discount'],
                        'net_amount'=>$final_calculation['net_amount'],
                        'credit'=>$credits, 
                        'debit'=>$debit,
                        'test_base_rate'=>$final_calculation['test_base_rate'], 
                        'test_master_rate'=>$final_calculation['test_master_rate'], 
                        'balance'=>$pay_balance,
                        'pay_mode'=>$post['payment_mode'],
                        'total_comission'=>$total_comission_data,
                        'created_by'=>$user_data['id'],
                        'created_date'=>date('Y-m-d H:i:s',strtotime($post['booking_date'].' '.$post['booking_time'])),
                        
                        //date('Y-m-d H:i:s',strtotime($post['booking_date'])),
                     );
            
            $this->db->insert('hms_payment',$pay_data);
            $payment_id = $this->db->insert_id(); 


            //if(in_array('218',$user_data['permission']['section']))
            //{
                   if($post['paid_amount']>0)
                   {
                      $hospital_receipt_no= check_hospital_receipt_no();
                      $data_receipt_data= array('branch_id'=>$user_data['parent_id'],
                              'section_id'=>4,
                              'parent_id'=>$booking_id,
                              'payment_id'=>$payment_id,
                              'reciept_prefix'=>$hospital_receipt_no['prefix'],
                              'reciept_suffix'=>$hospital_receipt_no['suffix'],
                              'created_by'=>$user_data['id'],
                              'created_date'=>date('Y-m-d H:i:s')
                              );
                      $this->db->insert('hms_branch_hospital_no',$data_receipt_data);
                   }
           // }

        //} // commented on july 4 2020 as discuss with Harikesh Sir
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
        'type'=>5,
        'section_id'=>10,
        'p_mode_id'=>$post['payment_mode'],
        'branch_id'=>$user_data['parent_id'],
        'parent_id'=>$booking_id,
        'ip_address'=>$_SERVER['REMOTE_ADDR']
        );
        $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

      }
    }

    /*add sales banlk detail*/
            //echo "<pre>";print_r($post);die;
            // End payment setup /////
      $booked_list = $this->session->userdata('book_test');   
      //echo "<pre>";print_r($booked_list);die;
      if(!empty($booked_list))
      {
        $all_test = [];
        $booked_test_ids_arr = array_column($booked_list, 'id');
        if(!empty($profile_data))
        {
          $profile_test = '';
          //echo "<pre>";print_r($profile_all_test);die;
          if(!empty($profile_all_test))
          {
            foreach($profile_all_test as $profile_test)
            {
              $booked_test_ids_arr[] = $profile_test['id'];
            }
          }
        } 

        //echo "<pre>";print_r($booked_test_ids_arr);die;

        foreach($booked_test_ids_arr as $test)
        {
            $all_child = $this->getChildren($test); 
            $all_test = array_merge($all_child,$all_test);
            $all_test[] = $test;
        }     
        ////////// 
        $unique_test_list = array_unique($all_test);
        $unique_test_list = array_reverse($unique_test_list);
        if(!empty($unique_test_list))
        {
          foreach($unique_test_list as $unique_test)
          {
            $tst_profile_id = $this->test_to_booked_profile_id($unique_test);
            $parent_status = 0;
            $child_profile_status = '0';
            $test_data = get_test($unique_test);
            //echo "<pre>"; print_r($booked_list); exit;
            $rate = 0;
            $sample_type_id ='';
            if(array_key_exists($unique_test, $booked_list) && in_array($unique_test,$booked_test_ids_arr))
            {
               $parent_status = 1;
               if(array_key_exists($unique_test, $booked_list))
               {
                  $rate = $booked_list[$unique_test]['price']; //exit;
                  $sample_type_id = $booked_list[$unique_test]['sample_type_id'];
               }
               else
               {
                 $rate = 0;
                 $sample_type_id ='';
               } 
            }
                         
            $booked_list_column = array_column($booked_list, 'id');
            //echo "<pre>";print_r($booked_list_column);die;
            if(in_array($unique_test,$booked_list_column) && in_array($unique_test,array_column($profile_all_test, 'id')))
            { 
              $test_type_para = 2; 
              $pkey = array_search($unique_test,array_column($profile_all_test, 'id'));
              if(isset($profile_all_test[$pkey]['child_profile_status']) && !empty($profile_all_test[$pkey]['child_profile_status']))
                {
                  $child_profile_status = '1';
                  $tst_profile_id = $profile_all_test[$pkey]['profile_id'];
                }
              $rate = $profile_all_test[$pkey]['price'];
              $sample_type_id='';
            } 
            else if(in_array($unique_test,array_column($profile_all_test, 'id')))
            {   
              $test_type_para = 1;
               
              $parent_status==0;
              $pkey = array_search($unique_test,array_column($profile_all_test, 'id'));
              if(isset($profile_all_test[$pkey]['child_profile_status']) && !empty($profile_all_test[$pkey]['child_profile_status']))
                {
                  $child_profile_status = '1';
                  $tst_profile_id = $profile_all_test[$pkey]['profile_id'];
                }
              $rate = $profile_all_test[$pkey]['price']; 
              $sample_type_id='';
            }
            else
            {
              $test_type_para = 0;
              $child_profile_status = 0;
            }
            //echo $test_type_para;
             // Set Discount test Price
            if($post['discount']>0  && ($parent_status==1 || $test_type_para==1 || $test_type_para==2))
            {    
                $total_discount = $post['discount'];
                $count_total_array = array_merge($this->session->userdata('book_test'),$profile_all_test);
                $total_tests = count($this->session->userdata('book_test'));

            //echo "<pre>";print_r($count_total_array);die; 
              $per_test_discount = $total_discount/$total_tests; 
               
              $test_net_amount = $rate-$per_booked_tp_discount;
            }  
            else
            {
                  $test_net_amount = $rate;
            } 
                        //// End    
            $result_test_data = get_test($unique_test);
            //print_r($result_test_data);die;
            $multi_interp_data = test_multi_interpration($unique_test,'0');
            if($result_test_data->is_outsource=='1')
            {
                $is_outsource=1;    
            }
            
            if(!empty($multi_interp_data))
            {
              $test_interpretation = $multi_interp_data[0]['interpration'];
            }
            else
            {
              $test_interpretation = $result_test_data->interpretation;
            }
            
              $test_gst='0';
                 $gst_vals = $this->check_gst_vals($unique_test);
                 if(!empty($gst_vals))
                 {
                    //$test_gst = ($gst_val/100*$test_net_amount); 
                    $test_gst = $test_net_amount-($test_net_amount/(1+($gst_val/100)));
                 }
            
            $booked_data = array(
                                  'booking_id'=>$booking_id,
                                  'test_id'=>$unique_test,
                                  'profile_id'=>$tst_profile_id,
                                  'test_type'=>$test_type_para,
                                  'parent_status'=>$parent_status,
                                  'base_rate'=>$test_data->base_rate, 
                                  'master_rate'=>$test_data->rate, 
                                  'interpretation'=>1, 
                                  'interpratation_data'=>$test_interpretation, 
                                  'result'=>$result_test_data->default_value, 
                                  'print'=>1, 
                                  'child_profile_status'=>$child_profile_status, 
                                  'amount'=>$rate,
                                  'sample_type_id'=>$sample_type_id, 
                                  'net_amount'=>$test_net_amount, 
                         );
             
            $this->db->insert('path_test_booking_to_test',$booked_data);
 //echo $this->db->last_query(); exit;
            //patient to charge entry
            if(!empty($post['ipd_id']) && !empty($booking_id) && $test_type_para==0) 
            {
              $profile_name = get_test_name($unique_test);
              $patient_pathology_charge = array(
                "branch_id"=>$user_data['parent_id'],
                'ipd_id'=>$post['ipd_id'],
                'parent_id'=>$booking_id,
                'patient_id'=>$post['patient_id'],
                'type'=>7,
                'quantity'=>1,
                'start_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                'end_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                'particular'=>$profile_name,
                'price'=>$test_net_amount,
                'panel_price'=>$test_net_amount,
                'net_price'=>$test_net_amount,
                'status'=>1,
                'created_date'=>date('Y-m-d H:i:s')
              );
              $this->db->insert('hms_ipd_patient_to_charge',$patient_pathology_charge);
              
            }

            //foreach end 
          }
        } 
        ///////////
      }  
      else if(!empty($profile_all_test))
      {
          $test_interpretation = '';
          foreach($profile_all_test as $ptest)
          {
            $tst_profile_id = $this->test_to_booked_profile_id($ptest['id']);
            $test_data = get_test($ptest['id']); 
            $multi_interp_data = test_multi_interpration($ptest['id'],'0');
            if(!empty($multi_interp_data))
            {
              $test_interpretation = $multi_interp_data[0]['interpration'];
            }
            else
            {
              $test_interpretation = $test_data->interpretation;
            }
            
            if($test_data->is_outsource=='1')
            {
                $is_outsource=1;    
            }
          $booked_data = array(
                    'booking_id'=>$booking_id,
                    'test_id'=>$ptest['id'],
                    'test_type'=>1, 
                    'profile_id'=>$ptest['profile_id'],
                    'base_rate'=>$test_data->base_rate, 
                    'master_rate'=>$test_data->rate, 
                    'interpretation'=>1, 
                    'interpratation_data'=>$test_interpretation, 
                    'result'=>$test_data->default_value, 
                    'print'=>1, 
                    'child_profile_status'=>$ptest['child_profile_status'],
                    'amount'=>$test_data->rate, 
                  );
          $this->db->insert('path_test_booking_to_test',$booked_data);
            
          

          } 
      }
      
      
        $this->db->set('is_outsource',$is_outsource);
        $this->db->where('id',$booking_id);
        $this->db->update('path_test_booking');
      
      // for token insert
      if(in_array('2070',$user_data['permission']['action']))
        { 
        $booking_date=date('Y-m-d',strtotime($post['booking_date']));

        if($tokentype['type']==0)
        { 
          $token_no=$this->test_token($depid='',$booking_date,$tokentype['type']);
          $tokenarray=array(
            'branch_id'=>$user_data['parent_id'],
            'patient_id'=>$patient_id,
            'department_id'=>0,
            'booking_id'=>$booking_id,
            'booking_date'=>$booking_date,
            'token_no'=>$token_no,
            'type'=>$tokentype['type']
          );
          $this->db->insert('path_patient_to_token',$tokenarray);
         }
            else if($tokentype['type']==1)
             {
              foreach ($dep_uni as $depid) {
             $token_no=$this->test_token($depid,$booking_date,$tokentype['type']);
              $tokenarray=array(
                'branch_id'=>$user_data['parent_id'],
                'patient_id'=>$patient_id,
                'department_id'=>$depid,
                'booking_id'=>$booking_id,
                'booking_date'=>$booking_date,
                'token_no'=>$token_no,
                'type'=>$tokentype['type']
              );
              $this->db->insert('path_patient_to_token',$tokenarray);
              }
             }
      }

      
      if(!empty($post['invoice_id']))
        {
            $this->db->set('complation_status',1);
            $this->db->where('id',$post['invoice_id']);
            $this->db->update('path_test_booking_invoice');   
        }   


    } 
    
    if(!empty($post['next_app_date']) && $post['next_app_date']!='00-00-0000' && $post['next_app_date']!='01-01-1970')
        {  
            $this->db->where('booking_id',$booking_id);
            $this->db->where('booking_type','33'); 
            $this->db->delete('hms_next_appointment');
            
            $this->db->where('id',$booking_id); 
            $query_d_pay = $this->db->get('path_test_booking');
            $row_d_pay = $query_d_pay->row_array();
        
            $next_appointment_data = array( 
            'branch_id'=>$user_data['parent_id'],
            'booking_id'=>$booking_id,
            'booking_type'=>33, 
            'booking_code'=>$row_d_pay['lab_reg_no'], 
            'patient_id'=>$row_d_pay['patient_id'],
            'next_appointment'=>date('Y-m-d', strtotime($post['next_app_date'])), 
            'created_date'=>date('Y-m-d H:i:s') 
        );
            $this->db->insert('hms_next_appointment',$next_appointment_data);
            
            //echo $this->db->last_query(); exit;

        }
    
    
    $this->session->unset_userdata('advance_result');
    $this->session->unset_userdata('set_profile');
    $this->session->unset_userdata('booking_set_branch');
    $this->session->unset_userdata('booking_doctor_type'); 
    $this->session->unset_userdata('test_panel_rate');
    $this->session->unset_userdata('book_test');
    $this->session->unset_userdata('set_profile');
    
    return $booking_id;
  

}
 
    
    function getOneLevel($test_id)
    {
      $user_data = $this->session->userdata('auth_users'); 
    $this->db->select('child_id'); 
    $this->db->where('parent_id',$test_id);           
    $query = $this->db->get('path_test_under');
    $total_record = $query->num_rows(); 
    $child_id=array();
    if($total_record>0)
            {
              $test_list = $query->result(); 
              foreach($test_list as $test)
              {
                   $child_id[] = $test->child_id;
              } 
            }
        return $child_id; 
    }

    function getChildren($parent_id) 
    {
      $tree = Array();
      if (!empty($parent_id)) {
          $tree = $this->getOneLevel($parent_id); 
          foreach ($tree as $key => $val) {
              $ids = $this->getChildren($val);
              $tree = array_merge($tree, $ids);
          }
      }
      return $tree;
  }

  function getChildrentest($parent_id) 
    {
      $tree = Array();
      if (!empty($parent_id)) {
          $tree = $this->getOneLevel($parent_id);
          
      }
      return $tree;
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
      $this->db->update('path_test_booking');

      $this->db->set('status',0);
      $this->db->set('section_id',1);  
      $this->db->where('parent_id',$id);  
      $this->db->update('hms_payment');
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
      $this->db->update('path_test_booking');

      $this->db->set('status',0);
      $this->db->set('section_id',1);  
      $this->db->where('parent_id IN ('.$branch_ids.')');  
      $this->db->update('hms_payment');
      //echo $this->db->last_query();die;
      } 
    }

    public function updatealltest($booking_id='')
    { 
       $users_data = $this->session->userdata('auth_users'); 
       $post = $this->input->post();
       //echo "<pre>"; print_r($post); die;
          if(!empty($post['diagnose']) || !empty($post['diagnose_text']))
          {
              //insert and diagnose
              $patient_id = $post['patient_id'];
              $booking_date = $post['booking_date'];
              
              $this->db->where(array('branch_id'=>$users_data['parent_id'],'patient_id'=>$patient_id,'booking_id'=>$booking_id));
              $this->db->delete('path_test_diagnose');
              
              $data_insert_diagnose=array('branch_id'=>$users_data['parent_id'],
                                        'booking_id'=>$booking_id,
                                        'diagnose_text'=>$post['diagnose_text'],
                                        'diagnose'=>$post['diagnose'],
                                        'patient_id'=>$patient_id,
                                        
                                        );
                        $this->db->set('created_by',$users_data['id']);
                        $this->db->set('date',date('Y-m-d', strtotime($post['booking_date'])));
                        $this->db->insert('path_test_diagnose',$data_insert_diagnose);
                        //echo $this->db->last_query(); die;
              
               $this->db->set('diagnose_text',$post['diagnose_text']);
              $this->db->set('diagnose',$post['diagnose']);
              
              $run_update=1;
          }
          
          if(!empty($post['signature_id']))
          {
             $this->db->set('signature_id',$post['signature_id']);
             $run_update=1;
          }
          if(!empty($post['complation_date']))
          {
              $this->db->set('complation_date',date('Y-m-d',strtotime($post['complation_date'])));
              $this->db->set('complation_time',date('H:i:s', strtotime(date('d-m-Y').' '.$post['complation_time'])));
             $run_update=1; 	
          }
          
          
          if($run_update==1)
          {
            $this->db->where('id',$booking_id);
            $this->db->update('path_test_booking');
            //echo $this->db->last_query(); 
          }
       
       
       
       if(!empty($post['result']))
       {
          /*if(!empty($post['signature_id']))
          {
             $this->db->set('signature_id',$post['signature_id']);
             $this->db->where('id',$booking_id);
             $this->db->update('path_test_booking');
          }*/

          foreach($post['result'] as $r_key=>$result)
          {
              $status = 0;
              $print = 0;
              $interpretation = 0; 
              if(isset($post['status']) && !empty($post['status']) && in_array($r_key,$post['status']))
              {
                $status = 1;
              }
              if(isset($post['print']) && !empty($post['print']) && in_array($r_key,$post['print']))
              {
                $print = 1;
              }
              if(isset($post['interpretation']) && !empty($post['interpretation']) && in_array($r_key,$post['interpretation']))
              {
                $interpretation = 1;
              }
              $data = array(
                             'result'=>$result, 
                             'status'=>$status, 
                             'print'=>$print, 
                             'interpretation'=>$interpretation,   
                           );
              $this->db->where('test_id',$r_key);
              $this->db->where('booking_id',$booking_id); 
              $this->db->update('path_test_booking_to_test',$data);

          }
       }
    }

    public function referal_doctor_list($branch_id="",$pay_type="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $booking_set_branch = $this->session->userdata('booking_set_branch');
    $this->db->select('*');   
    $this->db->order_by('hms_doctors.doctor_name','ASC');
    $this->db->where('hms_doctors.is_deleted',0); 
    $this->db->where('hms_doctors.doctor_type IN (0,2)');
    if($pay_type!="")
    {
      $this->db->where('hms_doctors.doctor_pay_type',$pay_type); 
    } 
    if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('hms_doctors.branch_id',$booking_set_branch); 
        }
        else if(!empty($branch_id))
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

    public function profile_test_list($id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('path_profile_to_test.*, path_test.rate'); 
    $this->db->join('path_test','path_test.id = path_profile_to_test.test_id','left');
    if(!empty($id))
    {
      $this->db->where('path_profile_to_test.profile_id',$id);
    }  
    $this->db->order_by('path_profile_to_test.id','DESC');      
    $query = $this->db->get('path_profile_to_test');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function get_booking_profile($booking_id="", $profile_id="", $parent_id="0")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('path_test_booking_to_profile.*, path_profile.profile_name,path_profile.print_name');  
      $this->db->join('path_profile','path_profile.id = path_test_booking_to_profile.profile_id');
      if(!empty($booking_id))
      {
        $this->db->where('path_test_booking_to_profile.test_booking_id',$booking_id);
      }
      if(!empty($profile_id))
      {
        $this->db->where('path_test_booking_to_profile.profile_id',$profile_id);
      }     
      $this->db->where('path_test_booking_to_profile.parent_id',$parent_id);       
      //$this->db->order_by('path_profile.sort_order','ASC');  
      $this->db->order_by("path_test_booking_to_profile.sort_order ASC, path_profile.sort_order ASC"); 
      $query = $this->db->get('path_test_booking_to_profile');
      $result = $query->result(); 
      //echo $this->db->last_query(); 
      return $result; 
    }

    public function get_booking_profile_print_name($booking_id="",$parent_id="0")
    {
        $users_data = $this->session->userdata('auth_users');
//(CASE WHEN path_profile.print_name="" THEN path_profile.profile_name ELSE path_profile.print_name END) as profile_name
        $this->db->select('path_test_booking_to_profile.*,path_profile.profile_name,path_profile.print_name');  
        $this->db->join('path_profile','path_profile.id = path_test_booking_to_profile.profile_id');
        if(!empty($booking_id))
        {
            $this->db->where('path_test_booking_to_profile.test_booking_id',$booking_id);
        }  
        //$this->db->order_by('path_profile.sort_order','ASC'); 
        $this->db->order_by("path_test_booking_to_profile.sort_order ASC, path_profile.sort_order ASC"); 
        $this->db->where('path_test_booking_to_profile.parent_id',$parent_id);    
        $query = $this->db->get('path_test_booking_to_profile');
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }
    
      public function get_booking_parent_profile_print_name_list($booking_id="",$parent_id="0",$profile_id="0")
    {  
        $users_data = $this->session->userdata('auth_users');
//(CASE WHEN path_profile.print_name="" THEN path_profile.profile_name ELSE path_profile.print_name END) as profile_name
        $this->db->select('path_test_booking_to_profile.*,path_profile.profile_name,path_profile.print_name, count(path_test_booking_to_test.id) as total_test, count(cprofile.id) as total_child_profile');  
        $this->db->join('path_profile','path_profile.id = path_test_booking_to_profile.profile_id');
        if(!empty($booking_id))
        {
            $this->db->where('path_test_booking_to_profile.test_booking_id',$booking_id);
        }  
        //$this->db->order_by('path_profile.sort_order','ASC'); 
        $this->db->order_by("path_test_booking_to_profile.sort_order ASC, path_profile.sort_order ASC"); 
        $this->db->where('path_test_booking_to_profile.parent_id',$parent_id); 
        $this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking_to_profile.test_booking_id AND path_test_booking_to_test.profile_id=path_test_booking_to_profile.profile_id  AND path_test_booking_to_test.print=1', 'left'); 
        $this->db->join('path_test_booking_to_profile as cprofile','cprofile.test_booking_id = path_test_booking_to_profile.test_booking_id AND cprofile.parent_id=path_test_booking_to_profile.profile_id', 'left');
        $this->db->group_by('path_test_booking_to_profile.profile_id');
        $query = $this->db->get('path_test_booking_to_profile');
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }
    
     public function get_booking_profile_print_name_list($booking_id="",$parent_id="0",$profile_id="0")
    {  
        $users_data = $this->session->userdata('auth_users');
//(CASE WHEN path_profile.print_name="" THEN path_profile.profile_name ELSE path_profile.print_name END) as profile_name
        $profile_idquery ='';
        /*if($parent_id>0)
        {
            $profile_idquery = 'AND profile_id = "'.$profile_id.'"';
        }*/
        $this->db->select('path_test_booking_to_test.*,path_profile.profile_name,path_profile.print_name, (select profile.parent_id from path_test_booking_to_profile as profile where profile.test_booking_id = path_test_booking_to_test.booking_id AND profile.profile_id = path_test_booking_to_profile.profile_id AND profile.parent_id = "'.$parent_id.'" '.$profile_idquery.') as parent_id');  
        $this->db->join('path_profile','path_profile.id = path_test_booking_to_test.profile_id');
        if(!empty($booking_id))
        {
            $this->db->where('path_test_booking_to_test.booking_id',$booking_id);
        }  
        $this->db->join('path_test_booking_to_profile','path_test_booking_to_profile.profile_id = path_test_booking_to_test.profile_id', 'left');    
        //$this->db->order_by('path_profile.sort_order','ASC'); 
        $this->db->order_by("path_test_booking_to_profile.sort_order ASC, path_profile.sort_order ASC"); 
        $this->db->where('path_test_booking_to_test.profile_id>0');
        $this->db->where('path_test_booking_to_test.print','1');
        $this->db->where('path_test_booking_to_test.test_type IN (1,2)');
        //$this->db->where('path_test_booking_to_profile.parent_id',$parent_id);
        $this->db->group_by('path_test_booking_to_test.profile_id');
        $query = $this->db->get('path_test_booking_to_test');
         
        // echo $this->db->last_query();die;
        $result = $query->result();
        
        return $result;
    }

    public function attended_doctor_list($branch_id="")
    {
    $users_data = $this->session->userdata('auth_users');
    $booking_set_branch = $this->session->userdata('booking_set_branch'); 
    $this->db->select('*');   
    $this->db->order_by('hms_doctors.doctor_name','ASC');
    $this->db->where('hms_doctors.is_deleted',0); 
    $this->db->where('hms_doctors.doctor_type IN (1,2)'); 
    if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('hms_doctors.branch_id',$booking_set_branch); 
        }
        else if(!empty($branch_id))
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

    public function get_referral_hospital_data($branch_id="")
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_hospital.*');  
    $this->db->where('hms_hospital.branch_id',$branch_id);  
    $this->db->where('hms_hospital.is_deleted',0); 
    $query = $this->db->get('hms_hospital');
    $hospital_list = $query->result(); 
    
    $drop = '<select name="referral_hospital" id="referral_hospital">
             <option value="">Select Hospital</option>';
    if(!empty($hospital_list))
    {
        foreach($hospital_list as $hospital)
        {
            $drop .= '<option value="'.$hospital->id.'">'.$hospital->hospital_name.'</option>';
        }
    }
    $drop .= '</select>';
    
    return $json_data = $drop;
  }

  public function referal_hospital_list($branch_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');   
    $this->db->order_by('hms_hospital.hospital_name','ASC');
    $this->db->where('hms_hospital.is_deleted',0); 
    if(!empty($branch_id))
    {
      $this->db->where('hms_hospital.branch_id',$branch_id);
    }
    else
    {
      $this->db->where('hms_hospital.branch_id',$users_data['parent_id']);
    }
      
    $query = $this->db->get('hms_hospital');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function employee_list($branch_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $booking_set_branch = $this->session->userdata('booking_set_branch');
    $sub_branches_data = $this->session->userdata('sub_branches_data');
        if(!empty($sub_branches_data))
        {
          $branch_list = array_unique(array_column($sub_branches_data, 'id'));
          $branch_list[] = $users_data['parent_id'];
          $branch_list = implode(',', $branch_list);
        } 
        else
        {
          $branch_list = $users_data['parent_id'];
        } 
    $this->db->select('*');   
    $this->db->order_by('hms_employees.name','ASC');
    $this->db->where('is_deleted',0);  
    if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('hms_employees.branch_id',$booking_set_branch); 
        }
        else if(!empty($branch_id))
        {
          $this->db->where('hms_employees.branch_id',$branch_id); 
        }
        else
        {
          $this->db->where('hms_employees.branch_id IN ('.$branch_list.')'); 
        } 
    $query = $this->db->get('hms_employees');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function profile_list($branch_id="")
    {
    $users_data = $this->session->userdata('auth_users');
    $booking_set_branch = $this->session->userdata('booking_set_branch'); 
    $this->db->select('*');   
    $this->db->order_by('path_profile.profile_name','ASC');
    $this->db->where('is_deleted',0);  
    if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('branch_id',$booking_set_branch); 
        }
        else if(!empty($branch_id))
        {
          $this->db->where('branch_id',$branch_id); 
        }
        else
        {
          $this->db->where('branch_id',$users_data['parent_id']); 
        } 
    $this->db->where('status',1); 
    //$this->db->where('is_deleted',0); 
    $query = $this->db->get('path_profile');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }
    
    public function collection_center_list($branch_id="")
    {
        $users_data = $this->session->userdata('auth_users');
       
        $this->db->select('hms_collection_center.*');   
        $this->db->order_by('hms_collection_center.source','ASC');
        $this->db->where('hms_collection_center.is_deleted',0);  
        $this->db->where('hms_collection_center.branch_id',$users_data['parent_id']); 
        $this->db->where('hms_collection_center.status',1); 
        
        $query = $this->db->get('hms_collection_center');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result; 
    }

    public function test_head_list($dept_id="", $branch_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $booking_set_branch = $this->session->userdata('booking_set_branch'); 
    $this->db->select('path_test_heads.*');    
    //$this->db->order_by('path_test_heads.test_heads','ASC');
    if(!empty($dept_id))
    {
           $this->db->where('path_test_heads.dept_id',$dept_id);  
    } 
    $this->db->where('path_test_heads.is_deleted',0);  
    if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('path_test_heads.branch_id',$booking_set_branch); 
        }
        else if(!empty($branch_id))
        {
          $this->db->where('path_test_heads.branch_id',$branch_id); 
        }
        else
        {
          $this->db->where('path_test_heads.branch_id',$users_data['parent_id']); 
        }
    //$order = array('sort_order'=>'asc','id' => 'desc');
        //$this->db->order_by(key($order), $order[key($order)]);
        $this->db->order_by('path_test_heads.sort_order','ASC');
    $this->db->group_by('path_test_heads.id');  
    $query = $this->db->get('path_test_heads');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function head_test_list($head_id="",$profile_id="",$test_name="",$dept_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $booking_set_branch = $this->session->userdata('booking_set_branch');
    $booking_list = $this->session->userdata('book_test');
    $this->db->select('path_test.*');  
    if(!empty($head_id))
    {
           $this->db->where('path_test.test_head_id',$head_id);  
    } 


    if(!empty($dept_id))
    {
           $this->db->where('path_test.dept_id',$dept_id);  
    } 

    if(!empty($profile_id))
    {
       $this->db->join('path_profile_to_test','path_profile_to_test.test_id = path_test.id');   
           $this->db->where('path_profile_to_test.profile_id',$profile_id);  
    }   

    if(!empty($test_name))
    {
           /*$this->db->where('(path_test.test_name LIKE  "'.$test_name.'%" OR path_test.test_code LIKE  "'.$test_name.'%" OR path_test.rate LIKE  "'.$test_name.'%")'); */ 
           $this->db->where('(path_test.test_name LIKE "%'.trim($test_name).'%" OR path_test.test_code LIKE "%'.trim($test_name).'%")');  
    }  

    if(isset($booking_list) && !empty($booking_list))
    {
      $test_ids_arr = array_keys($booking_list);
      $test_ids = implode(',', $test_ids_arr);
      $this->db->where('path_test.id NOT IN ('.$test_ids.')');
    }
    
    $this->db->where('path_test.is_deleted',0);  
    if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('path_test.branch_id',$booking_set_branch); 
        }
        else
        {
          $this->db->where('path_test.branch_id',$users_data['parent_id']); 
        } 
        //$this->db->where('path_test.status',1);   
    // $order = array('path_test.sort_order'=>'asc','path_test.id' => 'desc');
    //$this->db->order_by(key($order), $order[key($order)]);
    $this->db->order_by('path_test.sort_order','ASC');
    $this->db->group_by('path_test.id'); 
    $this->db->limit(100, 0);
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function test_list($ids="",$branch_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $booking_set_branch = $this->session->userdata('booking_set_branch');
    $this->db->select('path_test.*'); 
    if(!empty($ids))
    {
      $this->db->where('path_test.id  IN ('.$ids.')'); 
    }
    $this->db->where('path_test.is_deleted',0);  
    if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('path_test.branch_id',$booking_set_branch); 
        }
        else if(!empty($branch_id))
        {
          $this->db->where('path_test.branch_id',$branch_id); 
        }
        else
        {
          $this->db->where('path_test.branch_id',$users_data['parent_id']); 
        }  
        $this->db->order_by('path_test.sort_order','ASC');
    $this->db->group_by('path_test.id');  
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function get_booked_test_list($booking_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');   
    if(!empty($booking_id))
    {
          $this->db->where('booking_id',$booking_id); 
    }    
    $this->db->where('path_test_booking_to_test.test_type IN (0,2)'); 
    $this->db->where('path_test_booking_to_test.parent_status','1');  
    $query = $this->db->get('path_test_booking_to_test');
    $result = $query->result();  
    $test_list = [];
    if(!empty($result))
    { 
      foreach($result as $test)
      {
               $test_list[$test->test_id] = array('id'=>$test->test_id, 'price'=>$test->amount,'sample_type_id'=>$test->sample_type_id);
      }
    }
    //echo $this->db->last_query(); 
    return $test_list; 
    }

    public function report_test_list($booking_id="",$parent_status="",$test_type="",$profile_id="",$child_profile_status="")
    { 
      
        
    $users_data = $this->session->userdata('auth_users');
    $sub_branches_data = $this->session->userdata('sub_branches_data');
    if(!empty($sub_branches_data))
    {
      $branch_list = array_unique(array_column($sub_branches_data, 'id'));
      $branch_list[] = $users_data['parent_id'];
      $branch_list = implode(',', $branch_list);
    }
    else
    {
        $branch_list = $users_data['parent_id'];
    } 
    $this->db->select('path_test.test_name,path_test.dept_id, path_test.result_type,  path_test.test_type_id, path_test.test_code, path_test_booking_to_test.*, (CASE WHEN path_test_formula.id > 0 THEN 1 ELSE 0 END) as formula_avl,path_test.test_result_type,hms_department.department');  
     
    if(!empty($booking_id))
    {
          $this->db->where('path_test_booking_to_test.booking_id',$booking_id); 
    }
        
    if(!empty($parent_status))
    {
          $this->db->where('path_test_booking_to_test.parent_status','1');  
    }
 
    if($test_type!="")
    {
          $this->db->where('path_test_booking_to_test.test_type IN ('.$test_type.')');  
    }

    if($child_profile_status!="")
    {
          $this->db->where('path_test_booking_to_test.child_profile_status IN ('.$child_profile_status.')');  
    }

    if(!empty($profile_id))
    {
          $this->db->where('path_test_booking_to_test.profile_id',$profile_id); 
          $this->db->join('path_profile_to_test','path_profile_to_test.test_id = path_test_booking_to_test.test_id AND path_profile_to_test.profile_id = path_test_booking_to_test.profile_id', "left");  
    }
        
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id');
        $this->db->join('hms_department','hms_department.id = path_test.dept_id','left');
        $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id');
        $this->db->join('path_test_formula','path_test_formula.test_id = path_test.id','left'); 
        if(!empty($profile_id))
        {
        $this->db->order_by('path_profile_to_test.sort_order','ASC');
        }
        else
        {
          $this->db->order_by('path_test_heads.sort_order','ASC');  
          //$this->db->order_by('path_test.test_type_id','DESC'); 
          $this->db->order_by('path_test.sort_order','ASC');
        } 
        $this->db->group_by('path_test.id');
    $query = $this->db->get('path_test_booking_to_test');
    $result = $query->result();  
    /*if(!empty($profile_id))
    {
      echo $this->db->last_query(); exit;
    }*/
    
    return $result; 
    
    
    }


    public function report_fill_test_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_test_booking_to_test.*,path_test.test_name, path_test.interpretation as test_interpretation,path_test.range_from,path_test.range_to,path_test.unit_id,path_test.test_type_id, path_test.range_from_pre, path_test.range_from_post, path_test.range_to_pre, path_test.range_to_post, path_test.all_range_show');
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left'); 
        $this->db->where('path_test_booking_to_test.booking_id = "'.$booking_id.'"');
        if(!empty($profile_id))
        {
           $this->db->where('path_test_booking_to_test.profile_id = "'.$profile_id.'"');
        } 
        $this->db->where('path_test_booking_to_test.print ="1"');
        
        $this->db->where('path_test_booking_to_test.test_type  IN (1,2)');

        //$this->db->order_by('path_test_heads.sort_order','ASC');  
        $this->db->order_by('path_test.test_type_id','DESC');

        $this->db->order_by('path_test.sort_order','ASC');
        $this->db->from('path_test_booking_to_test');
        $result =  $this->db->get()->result();
        return $result;
    }

    public function booking_data($id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $booking_set_branch = $this->session->userdata('booking_set_branch');
    $sub_branches_data = $this->session->userdata('sub_branches_data');
    if(!empty($sub_branches_data))
    {
      $branch_list = array_unique(array_column($sub_branches_data, 'id'));
      $branch_list[] = $users_data['parent_id'];
      $branch_list = implode(',', $branch_list);
    }
    else
    {
        $branch_list = $users_data['parent_id'];
    } 

    $this->db->select('path_test_booking.*, hms_patient.patient_code, path_profile.profile_name'); 
    if(!empty($id))
    {
      $this->db->where('path_test_booking.id',$id); 
    }
    $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id');
    $this->db->join('path_profile','path_profile.id = path_test_booking.profile_id','left');
    if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('path_test_booking.branch_id',$booking_set_branch); 
        }
        else
        {
          $this->db->where('path_test_booking.branch_id IN ('.$branch_list.')'); 
        } 
    $query = $this->db->get('path_test_booking');
    //echo $this->db->last_query();die;
    $result = $query->result(); 
    return $result;
    }
  
  
  public function get_test_advance_range($id="", $gender="", $age_y="", $age_m="", $age_d="",$age_h='')
    {  
    $this->db->select('path_test_range.*'); 
    if(!empty($id))
    {
      $this->db->where('path_test_range.test_id',$id); 
    } 
    if($gender!="")
    {
      $this->db->where('(path_test_range.gender = "'.$gender.'" OR path_test_range.gender = "2")');
      //$this->db->where('path_test_range.gender',$gender); 
    }

    if(!empty($age_y) && $age_y>0)
    {
      $this->db->where('path_test_range.age_type',2);
      $this->db->where($age_y.' BETWEEN path_test_range.start_age AND path_test_range.end_age'); 
    }

    else if(!empty($age_m) && $age_m>0)
    {
      $this->db->where('path_test_range.age_type',1);
      $this->db->where($age_m.' BETWEEN path_test_range.start_age AND path_test_range.end_age'); 
    }

    else if(!empty($age_d) && $age_d>0)
    {
      $this->db->where('path_test_range.age_type',0);
      $this->db->where($age_d.' BETWEEN path_test_range.start_age AND path_test_range.end_age'); 
    }

    else if(!empty($age_h) && $age_h>0)
    {
      //echo "sdsds";
      $this->db->where('path_test_range.age_type',3);
      $this->db->where($age_h.' BETWEEN path_test_range.start_age AND path_test_range.end_age'); 
    }


    $query = $this->db->get('path_test_range');
    $result = $query->result();
     //$userss_data = $this->session->userdata('auth_users');
     /* if($userss_data['parent_id']==114)
      {
         echo $this->db->last_query(); die;
      }*/
    //echo $this->db->last_query();
    //print_r($result); exit; 
    return $result;
    }
  
  function update_test_result($booking_id="",$test_id="",$result="")
  {
    $this->db->where('booking_id',$booking_id);
    $this->db->where('test_id',$test_id);
    $this->db->set('result',$result);
    $this->db->update('path_test_booking_to_test'); 
  }

  public function get_test_details($booking_id,$test_id)
  {
    $this->db->select('path_test.*,path_test_booking_to_test.result, path_test_booking_to_test.interpratation_data'); 
    $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id');
    if(!empty($test_id))
         {
           $this->db->where('path_test_booking_to_test.test_id',$test_id); 
         } 
         if(!empty($booking_id))
         {
           $this->db->where('path_test_booking_to_test.booking_id',$booking_id); 
         }
      $query = $this->db->get('path_test_booking_to_test'); 
    return $query->row();   
  }

  public function formula_test_list($booking_id="", $test_id="")
  {
       if(!empty($booking_id) && !empty($test_id))
       {
           $this->db->select('path_test_formula.test_id'); 
         if(!empty($test_id))
         {
           $this->db->where('path_test_formula.test_id',$test_id); 
         } 
         if(!empty($booking_id))
         {
           $this->db->where('path_test_booking_to_test.booking_id',$booking_id); 
         } 
         $this->db->join('path_test_booking_to_test','path_test_booking_to_test.test_id = path_test_formula.test_id');

         $this->db->order_by('path_test_formula.id','ASC');
       $query = $this->db->get('path_test_formula');
       //echo $this->db->last_query();die;
       $result = $query->result();
       $ids = [];
       if(!empty($result))
       { 
         foreach($result as $tid)
         {
          $ids[] = $tid->test_id;
         }
       } 
       return $ids;
       }
  }

  public function condition_test_list($booking_id="", $test_id="")
  {
       if(!empty($booking_id) && !empty($test_id))
       {
           $this->db->select('path_test_condition.test_id'); 
         if(!empty($test_id))
         {
           $this->db->where('path_test_condition.test_id',$test_id); 
         } 
         if(!empty($booking_id))
         {
           $this->db->where('path_test_booking_to_test.booking_id',$booking_id); 
         } 
         $this->db->join('path_test_booking_to_test','path_test_booking_to_test.test_id = path_test_condition.test_id');

         $this->db->order_by('path_test_condition.id','ASC');
       $query = $this->db->get('path_test_condition');
        
       $result = $query->result();
       $ids = [];
       if(!empty($result))
       { 
         foreach($result as $tid)
         {
          $ids[] = $tid->test_id;
         }
       } 
       return $ids;
       }
  }

    public function test_formula_ids($booking_id="",$test_id="")
    {
       $this->db->select('path_test_formula.*'); 
       if(!empty($test_id))
       {
         $this->db->where('path_test_formula.test_id',$test_id); 
       } 
       //$this->db->join('path_test_booking_to_test','path_test_booking_to_test.test_id = path_test_formula.formula_val','left');
       //$this->db->where('path_test_booking_to_test.booking_id',$booking_id); 
       $this->db->order_by('id','ASC');
     $query = $this->db->get('path_test_formula'); 
     $result = $query->result();
     $ids = [];
     if(!empty($result))
     { 
       foreach($result as $tid)
       {
        $ids[] = $tid;
       }
     } 
     return $ids;
    }

    public function test_condition_ids($booking_id="",$test_id="")
    {
       $this->db->select('path_test_condition.*'); 
       if(!empty($test_id))
       {
         $this->db->where('path_test_condition.test_id',$test_id); 
       } 
       //$this->db->join('path_test_booking_to_test','path_test_booking_to_test.test_id = path_test_formula.formula_val','left');
       //$this->db->where('path_test_booking_to_test.booking_id',$booking_id); 
       $this->db->order_by('id','ASC');
     $query = $this->db->get('path_test_condition'); 
     $result = $query->result();
     //echo $this->db->last_query();die;
     $ids = [];
     if(!empty($result))
     { 
       foreach($result as $tid)
       {
        $ids[] = $tid;
       }
     } 
     return $ids;
    }

    public function booking_test_list($booking_id="")
    {
       $this->db->select('path_test_booking_to_test.test_id'); 
       if(!empty($booking_id))
       {
         $this->db->where('path_test_booking_to_test.booking_id',$booking_id); 
       } 
     $query = $this->db->get('path_test_booking_to_test');
     $result = $query->result();  
     $ids = [];
     if(!empty($result))
     {
          foreach($result as $tests)
          {
            $ids[] = $tests->test_id;
          }
     }
     //print_r($ids);die;
     return $ids;
    }

    public function get_test_result($booking_id="",$test_id="")
    {
        $this->db->select('path_test_booking_to_test.result as final_results'); 
       if(!empty($test_id))
       {
         $this->db->where('path_test_booking_to_test.test_id',$test_id); 
       } 
       if(!empty($booking_id))
       {
         $this->db->where('path_test_booking_to_test.booking_id',$booking_id); 
       } 
       $query = $this->db->get('path_test_booking_to_test');
      //echo $this->db->last_query(); exit;
     $result = $query->result();  
     //echo "<pre>"; print_r($result);
     return $result[0]->final_results;
    }

    public function check_patient_balance($patient_id="",$branch_id="")
    {
      if(!empty($patient_id))
      {
        $users_data = $this->session->userdata('auth_users');
      $booking_set_branch = $this->session->userdata('booking_set_branch'); 
        $this->db->select('(sum(hms_payment.credit)-sum(hms_payment.debit)) as balance');
        $this->db->where('hms_payment.patient_id',$patient_id);
        if(isset($booking_set_branch) && !empty($booking_set_branch))
          {
            $this->db->where('hms_payment.branch_id',$booking_set_branch); 
          }
          else if(!empty($branch_id))
          {
            $this->db->where('hms_payment.branch_id',$branch_id); 
          }
          else
          {
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
          } 
        $this->db->where('hms_payment.status',1);
        $this->db->from('hms_payment');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
            $result = $query->result();
          return $result[0]->balance;
      }
    }

    public function get_doctors($id="",$doctor_pay_type="")
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('hms_doctors.*, path_rate_plan.master_rate, path_rate_plan.base_rate, path_rate_plan.master_type, path_rate_plan.base_type');
      $this->db->where('hms_doctors.branch_id',$user_data['parent_id']); 
      if(!empty($id))
      {
        $this->db->where('hms_doctors.id',$id);  
      }
      if(!empty($doctor_pay_type))
      {
        $this->db->where('hms_doctors.doctor_pay_type',$doctor_pay_type);  
      }
      $this->db->join('path_rate_plan','path_rate_plan.id = hms_doctors.rate_plan_id','left');
      $this->db->where('hms_doctors.is_deleted',0);  
      $query = $this->db->get('hms_doctors');
    return $query->result_array();
    }


    function get_all_detail_print($ids="",$branch_id="")
    {
      $result_booking=array();
      $user_data = $this->session->userdata('auth_users');
      $company_data = $this->session->userdata('company_data');
      
      //(select sum(credit)-sum(debit) from hms_payment where parent_id=path_test_booking.id) as patient_balance
      //patient_balance
      $booking_set_branch = $this->session->userdata('booking_set_branch');
    $this->db->select("path_test_booking.*,(CASE WHEN path_test_booking.ipd_id>0 THEN 0.00 ELSE (select sum(credit)-sum(debit) from hms_payment where patient_id=path_test_booking.patient_id and parent_id=path_test_booking.id) END) as patient_balance, hms_patient.*,hms_simulation.simulation,hms_users.*, (CASE WHEN hms_users.emp_id>0 THEN (select name from hms_employees where id = hms_users.emp_id) ELSE (select branch_name from hms_branch where id = hms_users.parent_id) END) as signature_name, path_profile.profile_name, ref_doctors.doctor_pay_type as ref_doctor_type,hms_payment_mode.payment_mode,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_gardian_relation.relation,hms_patient.relation_simulation_id,hms_patient.relation_type,hms_patient.relation_name,hms_insurance_type.insurance_type as insurance_name, hms_insurance_company.insurance_company,(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.   referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',ref_doctors.doctor_name) END) END) as doctor_hospital_name,path_test_booking.payment_mode as pay_mode_id,path_test_booking.id as test_book_id,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.address as address1,hms_patient_category.patient_category as patient_category_name,hms_authorize_person.authorize_person as authorize_person_name"); 
    
    
    $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
    
    $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
		$this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
		$this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
    
    $this->db->join('hms_insurance_type',' hms_insurance_type.id = hms_patient.insurance_type_id','left'); // insurance type
    $this->db->join('hms_insurance_company',' hms_insurance_company.id = hms_patient.ins_company_id','left'); // insurance type
    
    $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
    

    $this->db->join('hms_doctors as ref_doctors','ref_doctors.id = path_test_booking.referral_doctor','left');
    $this->db->join('hms_patient_category','hms_patient_category.id = path_test_booking.patient_category','left'); 
    $this->db->join('hms_authorize_person','hms_authorize_person.id = path_test_booking.authorize_person','left');
    
    $this->db->join('path_profile','path_profile.id = path_test_booking.profile_id','left');
    $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
    $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
    $this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
    $this->db->join('hms_users','hms_users.id = path_test_booking.created_by');  
    $this->db->join('hms_payment_mode','hms_payment_mode.id = path_test_booking.payment_mode'); 
    $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = path_test_booking.id AND hms_branch_hospital_no.section_id=4','left');
    $this->db->where('path_test_booking.is_deleted','0'); 
    if($user_data['users_role']==4)
    {
            $this->db->where('path_test_booking.branch_id',$company_data['id']); 
    }
    else if($user_data['users_role']==3)
    {
            $this->db->where('path_test_booking.branch_id',$company_data['id']); 
    }
    else
    {
      if(isset($booking_set_branch) && !empty($booking_set_branch))
          {
            $this->db->where('path_test_booking.branch_id',$booking_set_branch); 
          }
          else if(!empty($branch_id))
          {
            $this->db->where('path_test_booking.branch_id',$branch_id); 
          }
          else
          {
            $this->db->where('path_test_booking.branch_id',$user_data['parent_id']); 
          } 
    }
    
    $this->db->where('path_test_booking.id = "'.$ids.'"');
    $this->db->from($this->table);
    $result_booking['booking_list']= $this->db->get()->result();
    //echo $this->db->last_query(); exit;
    $this->db->select('path_test_booking_to_test.*');
    $this->db->join('path_test_booking','path_test_booking.id = path_test_booking_to_test.booking_id'); 
    $this->db->where('path_test_booking_to_test.parent_status =1');
    $this->db->where('path_test_booking_to_test.test_type IN (0,2)');
    $this->db->where('path_test_booking_to_test.booking_id = "'.$ids.'"');
    $this->db->from('path_test_booking_to_test');
    $result_booking['booking_list']['test_booking_list']=$this->db->get()->result();

    $this->db->select('path_profile.*');
    $this->db->join('path_test_booking','path_profile.id = path_test_booking.profile_id');  
    $this->db->where('path_test_booking.id',$ids);
    if(!empty($branch_id))
        {
          $this->db->where('path_test_booking.branch_id',$branch_id); 
        }
        else
        {
          $this->db->where('path_profile.branch_id',$user_data['parent_id']);
        } 
    $this->db->from('path_profile');
    $result_booking['profile_data']['test_booking_list']=$this->db->get()->result();

    return $result_booking;
    
    }

   public  function template_format($data="", $branch_id="")
   {
      $users_data = $this->session->userdata('auth_users');
      $company_data = $this->session->userdata('company_data');
      $booking_set_branch = $this->session->userdata('booking_set_branch');
      $this->db->select('path_print_branch_template.*');
      $this->db->where($data);
      //print_r($company_data);die;
      if($users_data['users_role']==4)
      {
            $this->db->where('path_print_branch_template.branch_id',$company_data['id']); 
      }
      else if($users_data['users_role']==3)
      {
            $this->db->where('path_print_branch_template.branch_id',$company_data['id']); 
      }
      else
      {
        if(isset($booking_set_branch) && !empty($booking_set_branch))
          {
            $this->db->where('path_print_branch_template.branch_id',$booking_set_branch); 
          }
          else if(!empty($branch_id))
          {
            $this->db->where('path_print_branch_template.branch_id',$branch_id); 
          }
          else
          {
            $this->db->where('path_print_branch_template.branch_id',$users_data['parent_id']); 
          }  
      }
      
      $this->db->from('path_print_branch_template');
      $query=$this->db->get()->row();
      //print_r($query);die;
      return $query;

    }

    public  function signature_list($id="", $branch_id="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $booking_set_branch = $this->session->userdata('booking_set_branch');
      $this->db->select('path_test_footer.*'); 
      if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('branch_id',$booking_set_branch); 
        }
        else if(!empty($branch_id))
        {
          $this->db->where('branch_id',$branch_id); 
        }
        else
        {
          $this->db->where('branch_id',$users_data['parent_id']); 
        } 
      if(!empty($id))
      {
        $this->db->where('id',$id);
      } 
      $this->db->where('is_deleted',0);
      $this->db->from('path_test_footer');
      $query=$this->db->get()->result();
      return $query; 
    }


     public function update_test()
  {
    $post = $this->input->post();
    $data = array(  
                      'result'=>$post['result'],
                      'remark'=>$post['remark']
                    );

    $this->db->where('booking_id',$post['booking_id']);
    $this->db->where('test_id',$post['test_id']);
    $this->db->update('path_test_booking_to_test',$data); 
   //echo $this->db->last_query();die;
  }
  
  public function test_to_test_head_list($booking_id="", $branch_id="")
  {
      $this->db->select('path_test_booking_to_test.*,path_test_heads.test_heads,path_test_heads.id as thead_id, path_test.dept_id');
    $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
    $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id');  
    $this->db->where('path_test_booking_to_test.booking_id = "'.$booking_id.'"');
    $this->db->where('path_test_booking_to_test.print','1');
    $this->db->group_by('path_test_heads.id');
    $this->db->where('path_test_booking_to_test.test_type IN (0,2)');
    $this->db->order_by('path_test_heads.sort_order','ASC');
    $this->db->from('path_test_booking_to_test');
    return $this->db->get()->result();
    //return $result_booking;
  }


  public function test_booking_print($booking_id="", $branch_id="")
  {
    $result_booking=array();
      $user_data = $this->session->userdata('auth_users');
      $company_data = $this->session->userdata('company_data');
    $this->db->select("path_test_booking.*,hms_patient.*,hms_users.*,path_profile.profile_name,path_profile.interpretation as profile_interpretation,hms_branch.branch_name,(CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' Else 'Normal' END ) as ins_type, hms_insurance_company.insurance_company, hms_insurance_type.insurance_type,hms_collection_center.source as center_name"); 
    $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
    $this->db->join('path_profile','path_profile.id = path_test_booking.profile_id','left');
    
    $this->db->join('hms_insurance_type ','hms_insurance_type.id=hms_patient.insurance_type_id', 'left');
        $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id', 'left');
        
    $this->db->join('hms_users','hms_users.id = path_test_booking.created_by');
    
    $this->db->join('hms_collection_center','hms_collection_center.id = path_test_booking.collection_center', 'left');
    
    $this->db->join('hms_branch','hms_branch.id = path_test_booking.branch_id');  
    $this->db->where('path_test_booking.is_deleted','0'); 
    if($user_data['users_role']==4)
    {
           $this->db->where('path_test_booking.branch_id = "'.$company_data['id'].'"');
    }
    else if($user_data['users_role']==3)
    {
           $this->db->where('path_test_booking.branch_id = "'.$company_data['id'].'"');
    }
    else
    {
      if(!empty($branch_id))
      {
             $this->db->where('path_test_booking.branch_id = "'.$branch_id.'"');
      }
      else
      {
              $this->db->where('path_test_booking.branch_id = "'.$user_data['parent_id'].'"');
      }  
    } 
    $this->db->where('path_test_booking.id = "'.$booking_id.'"');
    $this->db->from('path_test_booking');
    $result_booking['booking_list']= $this->db->get()->result();
    
    $this->db->select('path_test_booking_to_test.*,path_test_heads.test_heads,path_test_heads.id as thead_id, path_test.dept_id');
    $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
    $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id');  
    $this->db->where('path_test_booking_to_test.booking_id = "'.$booking_id.'"');
    $this->db->group_by('path_test_heads.id');
    $this->db->where('path_test_booking_to_test.test_type IN (0,2)');
    $this->db->order_by('path_test_heads.sort_order','ASC');
    $this->db->from('path_test_booking_to_test');
    $result_booking['booking_list']['test_head_list']=$this->db->get()->result();

    /////////// Signature Data ////////////
        //$this->db->select('path_test_footer.*');
        //$this->db->join('path_test_booking','path_test_booking.signature_id = path_test_footer.id');
         /* $this->db->select('path_test_footer.*,hms_doctors.doctor_name,hms_doctors.signature as sign_doctor,path_test_booking.attended_doctor');
        $this->db->join('path_test_booking','path_test_booking.signature_id = path_test_footer.id','left'); 
        $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.attended_doctor','left'); 
  
        $this->db->where('path_test_booking.id = "'.$booking_id.'"');
        $this->db->where('path_test_booking.is_deleted','0'); 
        $this->db->where('path_test_footer.is_deleted','0'); 
        if($user_data['users_role']==4)
    {
           $this->db->where('path_test_footer.branch_id = "'.$company_data['id'].'"');
    }
    else if($user_data['users_role']==3)
    {
           $this->db->where('path_test_footer.branch_id = "'.$company_data['id'].'"');
    }
    else
    {
      if(!empty($branch_id))
      {
             $this->db->where('path_test_footer.branch_id = "'.$branch_id.'"');
      }
      else
      {
              $this->db->where('path_test_footer.branch_id = "'.$user_data['parent_id'].'"');
      }  
    } 
        $this->db->from('path_test_footer');
    $result_booking['booking_list']['signature_data']=$this->db->get()->result(); */

/////////// Signature Data ////////////
        $this->db->select('path_test_footer.*,hms_doctors.doctor_name,hms_doctors.signature as sign_doctor,path_test_booking.attended_doctor');
        $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.attended_doctor','left');
       
         

        if($user_data['users_role']==4)
    {
           //$this->db->where('path_test_footer.branch_id = "'.$company_data['id'].'"');
            $this->db->join('path_test_footer','path_test_footer.id = path_test_booking.signature_id AND path_test_footer.branch_id = "'.$company_data['id'].'"  AND path_test_footer.is_deleted=0','left'); 

    }
    else if($user_data['users_role']==3)
    {
           //$this->db->where('path_test_footer.branch_id = "'.$company_data['id'].'"');
            $this->db->join('path_test_footer','path_test_footer.id = path_test_booking.signature_id AND path_test_footer.branch_id = "'.$company_data['id'].'"  AND path_test_footer.is_deleted=0','left'); 
    }
    else
    {
      if(!empty($branch_id))
      {
             //$this->db->where('path_test_footer.branch_id = "'.$branch_id.'"');
              $this->db->join('path_test_footer','path_test_footer.id = path_test_booking.signature_id AND path_test_footer.branch_id = "'.$branch_id.'"  AND path_test_footer.is_deleted=0','left'); 
      }
      else
      {
              //$this->db->where('path_test_footer.branch_id = "'.$user_data['parent_id'].'"');
               $this->db->join('path_test_footer','path_test_footer.id = path_test_booking.signature_id AND path_test_footer.branch_id = "'.$user_data['parent_id'].'" AND path_test_footer.is_deleted=0','left'); 
      }  
    }
        $this->db->where('path_test_booking.id = "'.$booking_id.'"');
        $this->db->where('path_test_booking.is_deleted','0'); 
        //$this->db->where('','0'); 
         
        $this->db->from('path_test_booking');
    $result_booking['booking_list']['signature_data']=$this->db->get()->result();
    ///////////////////////////////////////
    //echo "<pre>";print_r($result_booking['booking_list']); exit;
    //echo $this->db->last_query();die;
    return $result_booking;
  }

  public function get_child_ids($test_id='')
  {
    $this->db->select("child_id"); 
    $this->db->where('parent_id = "'.$test_id.'"');
    $query = $this->db->get('path_test_under');
    $result = $query->result_array();

    $array_test =array();
    foreach ($result as $value) { 
      $array_test[] = $value['child_id'];
    }
    return $array_test;
    ///return $result= $this->db->get()->result();

  }

  public function get_by_id_test($id)
  {
    $this->db->select('path_test.*');
    $this->db->from('path_test'); 
    $this->db->where('path_test.id',$id);
    $this->db->where('path_test.is_deleted','0');
    $query = $this->db->get(); 
    return $query->row();
  }

  public function get_test_under($id)
  {
    $this->db->select('path_test_under.child_id as test_id');
    $this->db->from('path_test_under'); 
    $this->db->where('path_test_under.parent_id',$id); 
    $query = $this->db->get(); 
    return $query->result_array();
  }

  public function updatealltestinterpretation($test_ids='',$status='0')
  {
    if(!empty($test_ids))
      { 
      $user_data = $this->session->userdata('auth_users');
      $this->db->set('interpretation',$status);
      $this->db->where('id',$test_ids); 
      $this->db->update('path_test_booking_to_test');
      //echo $this->db->last_query();
      } 
  }

  public function branch_rate_plan()
  {
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('path_rate_plan.*');     
    $this->db->where('hms_branch.id',$users_data['parent_id']);  
    $this->db->join('path_rate_plan','path_rate_plan.id = hms_branch.rate_id');
    $query_branch = $this->db->get('hms_branch'); 
    return $query->row();
  }


  function getChildtest($parent_id='',$i='') 
    {
      $tree = Array();
      if (!empty($parent_id)) {
          $tree = $this->getchildOneLevel($parent_id,$i);
          
      }
      return $tree;
  }

  function getchildOneLevel($test_id,$i='')
    {
    
    $this->db->select('child_id'); 
    $this->db->where('parent_id',$test_id);             
    $query = $this->db->get('path_test_under');
    $total_record = $query->num_rows(); 
    $child_id=array();
    if($total_record>0)
            {
              $test_list = $query->result(); 
              foreach($test_list as $test)
              {
                   $child_id[$i][] = $test->child_id;
              } 
            }
        return $child_id; 
    }

    private function calc_string( $mathString )
    {
            $cf_DoCalc = create_function("", "return (" . $mathString . ");" );
            if(is_numeric($cf_DoCalc))
            {
               $cf_DoCalc = number_format($cf_DoCalc,2,'.','');
            }
            return $cf_DoCalc();
    }


    public function advance_search_result()
    {
       $search_date = $this->session->userdata('search_booking_date');
       $users_data = $this->session->userdata('auth_users');
       $company_data = $this->session->userdata('company_data'); 
    $this->db->select("path_test_booking.*, hms_patient.patient_code, hms_patient.patient_name, (CASE WHEN hms_payment.parent_id > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.parent_id = path_test_booking.id) ELSE '0.00' END) as balance,

      (CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.   referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,(select sum(payment.credit) as paid_amount1 from hms_payment as payment where payment.parent_id = path_test_booking.id AND payment.section_id =1) as paid_amount1,
(select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.parent_id = path_test_booking.id AND payment.section_id=1 AND payment.branch_id = ".$users_data['parent_id'].") as balance1

      "); 
    //(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name

    $this->db->from($this->table); 
    $this->db->join('hms_payment','hms_payment.parent_id = path_test_booking.id','left');
    $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id'); 

$this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
    $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
 
        $this->db->where('path_test_booking.is_deleted','0');
        if($users_data['users_role']==4)
        {
           $this->db->where('path_test_booking.patient_id',$users_data['parent_id']);
        }
        else if($users_data['users_role']==3)
        {
           $this->db->where('path_test_booking.referral_doctor',$users_data['parent_id']);
        }
        else
        {
          $this->db->where('path_test_booking.branch_id',$users_data['parent_id']); 
        }   
        $this->db->group_by('path_test_booking.id');
        if(isset($search_date) && !empty($search_date))
        {
          if(isset($search_date['start_date']) && !empty($search_date['start_date'])
            )
          {
            $start_date = date('Y-m-d',strtotime($search_date['start_date']));
            $this->db->where('path_test_booking.booking_date >= "'.$start_date.'"');
          }

          if(isset($search_date['end_date']) && !empty($search_date['end_date'])
            )
          {
            $end_date = date('Y-m-d',strtotime($search_date['end_date']));
            $this->db->where('path_test_booking.booking_date <= "'.$end_date.'"');
          }

          if(isset($search_date['patient_code']) && !empty($search_date['patient_code']) )
          {
            $this->db->where('hms_patient.patient_code',$search_date['patient_code']);
          }

          if($search_date['insurance_type']!='')
            {
              $this->db->where('hms_patient.insurance_type',$search_date['insurance_type']);
            }

            if(!empty($search_date['insurance_type_id']))
            {
              $this->db->where('hms_patient.insurance_type_id',$search_date['insurance_type_id']);
            }

            if(!empty($search['ins_company_id']))
            {
              $this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
            }

          if(isset($search_date['lab_reg_no']) && !empty($search_date['lab_reg_no']) )
          {
            $this->db->where('path_test_booking.lab_reg_no',$search_date['lab_reg_no']);
          }

          if(isset($search_date['referral_doctor']) && !empty($search_date['referral_doctor']) )
          {
            $this->db->where('path_test_booking.referral_doctor',$search_date['referral_doctor']);
          }

          if(isset($search_date['attended_doctor']) && !empty($search_date['attended_doctor']) )
          {
            $this->db->where('path_test_booking.attended_doctor',$search_date['attended_doctor']);
          }
          
           if(isset($search_date['collection_center']) && !empty($search_date['collection_center']) )
          {
            $this->db->where('path_test_booking.collection_center',$search_date['collection_center']);
          }
          
           if(isset($search_date['is_home_collection']) && !empty($search_date['is_home_collection']) )
          {
            $this->db->where('path_test_booking.is_home_collection',$search_date['is_home_collection']);
          }
          
          


          if(isset($search_date['simulation_id']) && !empty($search_date['simulation_id']) )
          {
            $this->db->where('hms_patient.simulation_id',$search_date['attended_doctor']);
          }

          if(isset($search_date['patient_name']) && !empty($search_date['patient_name']) )
          {
            $this->db->where('hms_patient.patient_name LIKE "'.$search_date['patient_name'].'%"');
          }

          if(isset($search_date['mobile_no']) && !empty($search_date['mobile_no']) )
          {
            $this->db->where('hms_patient.mobile_no',$search_date['mobile_no']);
          }

          if(isset($search_date['age_from']) && !empty($search_date['age_from']) )
          {
            $this->db->where('hms_patient.age_y >= "'.$search_date['age_from'].'"');
          }

          if(isset($search_date['age_to']) && !empty($search_date['age_to']) )
          {
            $this->db->where('hms_patient.age_y <= "'.$search_date['age_to'].'"');
          }

          if(isset($search_date['gender']) && !empty($search_date['gender']) )
          {
            $this->db->where('hms_patient.gender',$search_date['gender']);
          }

          if(isset($search_date['city_id']) && !empty($search_date['city_id']) )
          {
            $this->db->where('hms_patient.city_id',$search_date['city_id']);
          }

          if(isset($search_date['country_id']) && !empty($search_date['country_id']) )
          {
            //$this->db->where('hms_patient.country_id',$search_date['country_id']);
          }

          if(isset($search_date['state_id']) && !empty($search_date['state_id']) )
          {
            $this->db->where('hms_patient.state_id',$search_date['state_id']);
          }

          if(isset($search_date['pincode']) && !empty($search_date['pincode']) )
          {
            $this->db->where('hms_patient.pincode',$search_date['pincode']);
          }

 /* refered by code */
      if(isset($search_date['referral_hospital']) && $search_date['referred_by']=='1' && !empty($search_date['referral_hospital']))
      {
      $this->db->where('path_test_booking.referral_hospital' ,$search_date['referral_hospital']);
      }
            elseif(isset($search_date['refered_id']) && $search_date['referred_by']=='0' && !empty($search_date['refered_id']))
            {
                $this->db->where('path_test_booking.referral_doctor' ,$search_date['refered_id']);
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
        elseif(!empty($search_date["employee"]))
        {
            $emp_ids=  $search_date["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            //$this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
        }

        $query = $this->db->get();
       //echo $this->db->last_query(); die;
        return $query->result();
    }



    public function complete_test_report($booking_id='',$status='')
    {  
    $this->db->set('complation_status',$status);
    $this->db->set('complation_date',date('Y-m-d'));
    $this->db->set('complation_time',date('H:i:s'));
    $this->db->where('id',$booking_id);
    $this->db->update('path_test_booking');  
    }

    public function  update_test_interpretation_data($interpretation_data_result,$booking_id,$test_id)
    {
        $this->db->set('interpratation_data',$interpretation_data_result);
      $this->db->where('booking_id',$booking_id);
      $this->db->where('test_id',$test_id);
      $this->db->update('path_test_booking_to_test');
      //echo $this->db->last_query();die;
    }

    function test_report_template_format($data="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $company_data = $this->session->userdata('company_data'); 
      $this->db->select('path_test_report_print_setting.*');
      $this->db->where($data);
      if($users_data['users_role']==4)
        {
            $this->db->where('path_test_report_print_setting.branch_id',$company_data['id']); 
        }
        else if($users_data['users_role']==3)
        {
            $this->db->where('path_test_report_print_setting.branch_id',$company_data['id']); 
        }
        else
        {
            $this->db->where('path_test_report_print_setting.branch_id',$users_data['parent_id']); 
        } 
      $this->db->from('path_test_report_print_setting');
      $result=$this->db->get()->row();
      //echo $this->db->last_query();die;
      return $result;

    } 

    public function report_html_template($part="",$branch_id="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('path_test_report_print_setting.*');
      if(!empty($branch_id))
      {
          $this->db->where('path_test_report_print_setting.branch_id',$branch_id);
      }
      else
      {
           $this->db->where('path_test_report_print_setting.branch_id',$users_data['parent_id']);
      } 
        $this->db->from('path_test_report_print_setting'); 
      $result=$this->db->get()->row(); 
      // echo $this->db->last_query();die;
      return $result;
    }

    public function save_profile_interpretation($booking_id="",$profile_interpretation="",$profile_id="")
    {  
      if(!empty($booking_id))
      {
        $this->db->set('profile_interpretation',$profile_interpretation);
        $this->db->where('test_booking_id',$booking_id);
        $this->db->where('profile_id',$profile_id);
        $this->db->update('path_test_booking_to_profile');
        //echo $this->db->last_query();
      }

    }
  function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
  {
    $users_data = $this->session->userdata('auth_users'); 

    $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
    $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
    $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
    $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
    $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
    $this->db->where('hms_payment_mode_field_value_acc_section.type','');
    $this->db->where('hms_payment_mode_field_value_acc_section.section_id',10);
    $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
    return $query;
  }

       
        public function test_to_booked_profile_id($test_id)
  {
    $this->db->select('path_profile_to_test.*'); 
      $this->db->where('path_profile_to_test.test_id',$test_id); 
      $this->db->from('path_profile_to_test');
      $result_list = $this->db->get()->result(); 
      $profile_session = $this->session->userdata('set_profile');  
        $profile_id =0;
      if(!empty($profile_session) && isset($profile_session) && !empty($profile_session))
      {
         $profile_ids = array_column($profile_session, 'id');
           foreach($result_list as $result)
           {  
             if(in_array($result->profile_id,$profile_ids))
             {
              $profile_id = $result->profile_id;
             }
           }
      } 
      return $profile_id;
  }


  public function get_profile_interpretation($booking_id="",$profile_id="")
  { 
    $this->db->select('path_test_booking_to_profile.profile_interpretation as interpretation'); 
    $this->db->where('path_test_booking_to_profile.test_booking_id',$booking_id); 
    $this->db->where('path_test_booking_to_profile.profile_id',$profile_id); 
    $this->db->from('path_test_booking_to_profile');
    $result = $this->db->get()->row();
      if(empty($result->interpretation))
      {
      $this->db->select('path_profile.interpretation'); 
      $this->db->where('path_profile.id',$profile_id); 
      $this->db->from('path_profile');
      $result = $this->db->get()->row();
      }
      return $result;
  }


  // Added on 05-Feb-2018 for home collection
  public function get_home_collection_data()
  {
    $user_data=$this->session->userdata('auth_users'); 
        $this->db->select('*');
        $this->db->from('hms_test_home_collection');
        $this->db->where('branch_id',$user_data['parent_id']);
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }
  // Added on 05-Feb-2018 for home collection


public function get_transaction_id($booking_id,$section_id,$type)
{
    
    $user_data=$this->session->userdata('auth_users'); 
        $this->db->select('hms_payment_mode_field_value_acc_section.field_value');
        $this->db->from('hms_payment_mode_field_value_acc_section');
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$user_data['parent_id']);
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$booking_id);
        
        $this->db->where('hms_payment_mode_field_value_acc_section.section_id',$section_id);
        
        $this->db->where('hms_payment_mode_field_value_acc_section.type',$type);
        $res=$this->db->get();
        //echo $this->db->last_query();die;
        return $res->result();
        
    
}

  public function get_suggested_test_for_range($test_condition, $test_id )
  {
    $this->db->select('hms_path_test_suggesstion.*, path_test.test_name');
    $this->db->from('hms_path_test_suggesstion');
    $this->db->join('path_test', 'path_test.id=hms_path_test_suggesstion.suggested_test_id');
    $this->db->where('hms_path_test_suggesstion.test_id',$test_id);
    $this->db->where('hms_path_test_suggesstion.test_condition',$test_condition);
    $res=$this->db->get();

    //echo $this->db->last_query();die;
    if($res->num_rows() > 0 )
    {
      return $res->result();
    }
    else
    {
      return "empty";
    }
   
  }


  public function update_verify_status($booking_id,$status)
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->set('verify_status',$status);
    $this->db->set('verify_by',$users_data['id']);
    $this->db->set('verify_date',date('Y-m-d H:i:s'));
    $this->db->where('branch_id',$users_data['parent_id']);
    $this->db->where('id',$booking_id);
    $this->db->update('path_test_booking');
  }

  public function update_delivered_test($id="",$status='0')
  {
    if(!empty($id) && $id>0)
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->set('delivered_status',$status);
      $this->db->set('delivered_by',$user_data['id']);
      $this->db->set('delivered_date',date('Y-m-d H:i:s'));
      $this->db->where('id',$id);
      $this->db->update('path_test_booking');
    } 
  }

  public function multi_interpretation_change_data($id="")
  {
    $this->db->select('*');
    $this->db->where('id',$id);
    $query = $this->db->get('path_multi_interpration');
    return $query->result_array();
  }

  public function save_adv_interpretation()
  {
    $post = $this->input->post();
    $this->db->set('interpratation_data',$post['interpretation_data']);
    $this->db->set('adv_interpretation_status',1);
    $this->db->where('booking_id',$post['booking_id']);
    $this->db->where('test_id',$post['test_id']);
    $this->db->update('path_test_booking_to_test');
  }

  public function get_dept_common_data($booking_id="")
  {   
    $this->load->model('test_report_verify/test_report_verify_model','test_report_verify');
    $this->db->select('DISTINCT(hms_department.id) as dept_id'); 
    $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
    $this->db->join('hms_department','hms_department.id = path_test.dept_id','left');
    $this->db->where('path_test_booking_to_test.booking_id',$booking_id);
    $data['department_list']= $this->db->get('path_test_booking_to_test')->result_array();
    //$data = [];
    $data_arr=array();
    //print '<pre>'; print_r($data['department_list']);die;
        if(!empty($data['department_list']))
        {
          foreach($data['department_list'] as $dept)
          {
            $data_arr[] = $dept['dept_id'];
          }
        }
       
    //$data['department_list']=$query->result_array();
    $data['selected_dept'] = $this->test_report_verify->branch_verify_dept();
    $common_array= array_intersect($data['selected_dept'],$data_arr);

     if(!empty($common_array))
     {
      return '2';die;
     }
     else
     {
       return '1';die;
     }
    
   // return $
  }

  public function get_child_profile($profile_id="")
  {
    if(!empty($profile_id))
    {
      $this->db->select('multi_profile_id');
      $this->db->where('profile_id',$profile_id);
      $query = $this->db->get('path_profile_to_profile');
      //echo $this->db->last_query();die;
      return $query->result_array();
    } 
  }

  public function test_booking_balance($booking_id="")
  {
    $this->db->select('sum(hms_payment.credit)-sum(hms_payment.debit) as balance');
    $this->db->where('parent_id',$booking_id);
    $this->db->where('section_id','1');
    $query = $this->db->get('hms_payment'); 
    $result = $query->result_array();
    if(!empty($result))
    {
       $balance = $result[0]['balance'];
    }
    else
    {
       $balance = '0';
    }
    return $balance;
  }


  public function get_booked_profiles($booking_id="")
  {
    $users_data = $this->session->userdata('auth_users');
    //(CASE WHEN path_profile.print_name="" THEN path_profile.profile_name ELSE path_profile.print_name END) as profile_name
    $this->db->select('path_test_booking_to_profile.*');  
    if(!empty($booking_id))
    {
        $this->db->where('path_test_booking_to_profile.test_booking_id',$booking_id);
    }      
    $query = $this->db->get('path_test_booking_to_profile');
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
  }

  public function sample_type_list($test_id='',$sample_id='')
  {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('*');  
      $this->db->where('status','1'); 
      $this->db->order_by('sample_type','ASC');
      $this->db->where('is_deleted',0);
      $this->db->where('branch_id',$users_data['parent_id']);  
      $query = $this->db->get('path_sample_type');
      $result = $query->result(); 
      //['.$child->id.'][price]
      $dropdown = '<select name="test_data['.$test_id.'][sample_type_id]" class="w-150px"><option value="">Select Sample</option>'; 
      if(!empty($result))
      {
        foreach($result as $test_sample_type)
        {
          $checked = '';
          if($test_sample_type->id==$sample_id)
          {
            $checked = 'selected="selected"';
          }

            
           $dropdown .= '<option '.$checked.' value="'.$test_sample_type->sample_type.'">'.$test_sample_type->sample_type.'</option>';
        }
      } 
      $dropdown .= '</select>';
      return $dropdown;
  }
 
 
  public function get_test_sample_name($sample_type_id="")
  {
    $users_data = $this->session->userdata('auth_users');
    $sample_type ='';
    
    if(!empty($sample_type_id))
    {
        $this->db->select('path_sample_type.sample_type');  
        $this->db->where('path_sample_type.id',$sample_type_id);
     
    $this->db->where('path_sample_type.is_deleted',0);
    $this->db->where('path_sample_type.branch_id',$users_data['parent_id']);       
    $query = $this->db->get('path_sample_type');
    $result = $query->result();
    if(!empty($result))
    {
      $sample_type = $result[0]->sample_type;
    }

    }

    //echo $this->db->last_query();
    return $sample_type;
  }


//neha 14-2-2019
  public function get_by_mobile_no($mobile)
  { 
      $users_data = $this->session->userdata('auth_users');
    $this->db->select("hms_patient.*, hms_cities.city, hms_state.state, hms_countries.country, hms_simulation.simulation,hms_sim.simulation as rel_simulation, hms_religion.religion, hms_relation.relation as gardian_relation, hms_insurance_type.insurance_type as insurance_types, hms_insurance_company.insurance_company, hms_users.username,hms_gardian_relation.relation,hms_patient.modified_date as patient_modified_date"); 
    $this->db->from('hms_patient'); 
    $this->db->where('hms_patient.mobile_no',$mobile);
    $this->db->where('hms_patient.branch_id',$users_data['parent_id']);
    // $this->db->where('hms_patient.is_deleted','0'); 
    $this->db->join('hms_users','hms_users.parent_id=hms_patient.id AND hms_users.users_role=4','left');
    $this->db->join('hms_religion','hms_religion.id=hms_patient.religion_id','left');
    $this->db->join('hms_relation','hms_relation.id=hms_patient.relation_id','left');
    $this->db->join('hms_countries','hms_countries.id=hms_patient.country_id','left');
    $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_simulation','hms_simulation.id=hms_patient.simulation_id','left');

        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'left'); 
         $this->db->join('hms_simulation as hms_sim','hms_sim.id=hms_patient.relation_simulation_id','left');
        $this->db->join('hms_insurance_type','hms_insurance_type.id=hms_patient.insurance_type_id','left');
        $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id','left');
    $query = $this->db->get(); 
    $result  = $query->result();
    //echo $this->db->last_query(); exit;
    return $result;
  }
  
  //neha 18-2-2019
  function update_sample_collected()
  {
    $post = $this->input->post();
    if(!empty($post))
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->set('sample_collected_date',date('Y-m-d',strtotime($post['sample_collected_date'])).' '.date('H:i:s',strtotime($post['sample_collected_time'])));
      $this->db->where('id',$post['testid']);
      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->update('path_test_booking');
      return 1;
    }
  }

  function update_sample_recieved()
  {
    $post = $this->input->post();
    if(!empty($post))
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->set('sample_received_date',date('Y-m-d',strtotime($post['sample_received_date'])).' '.date('H:i:s',strtotime($post['sample_received_time'])));
      $this->db->where('id',$post['testid']);
      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->update('path_test_booking');
      return 1;
    }
  }

  //neha 25-2-2019
  public function get_patient_byid($id)
  { 
    $this->db->select("hms_patient.*, hms_cities.city, hms_state.state, hms_countries.country, hms_simulation.simulation,hms_sim.simulation as rel_simulation, hms_religion.religion, hms_relation.relation as gardian_relation, hms_insurance_type.insurance_type as insurance_types, hms_insurance_company.insurance_company, hms_users.username,hms_gardian_relation.relation,hms_patient.modified_date as patient_modified_date"); 
    $this->db->from('hms_patient'); 
    $this->db->where('hms_patient.id',$id);
    // $this->db->where('hms_patient.is_deleted','0'); 
    $this->db->join('hms_users','hms_users.parent_id=hms_patient.id AND hms_users.users_role=4','left');
    $this->db->join('hms_religion','hms_religion.id=hms_patient.religion_id','left');
    $this->db->join('hms_relation','hms_relation.id=hms_patient.relation_id','left');
    $this->db->join('hms_countries','hms_countries.id=hms_patient.country_id','left');
    $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_simulation','hms_simulation.id=hms_patient.simulation_id','left');

        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'left'); 
         $this->db->join('hms_simulation as hms_sim','hms_sim.id=hms_patient.relation_simulation_id','left');
        $this->db->join('hms_insurance_type','hms_insurance_type.id=hms_patient.insurance_type_id','left');
        $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id','left');
    $query = $this->db->get(); 
    $result  = $query->row_array();
    //echo $this->db->last_query(); exit;
    return $result;
  }
 //neha 25-2-2019

  
  
  
  
  
  // op token code 25/07/2019
public function get_department_id($testid)
{
  $this->db->select('path_test.dept_id');
  $this->db->from('path_test_booking_to_test');
  $this->db->join('path_test','path_test.id=path_test_booking_to_test.test_id');
  $this->db->where('path_test_booking_to_test.test_id',$testid);
  $query=$this->db->get();
  return $query->row_array();
}
public function get_token_type_by_branch($brachid)
{
  $this->db->select('type');
  $this->db->from('path_token_setting');
  $this->db->where('branch_id',$brachid);
  $query=$this->db->get();
  return $query->row_array();
}


 public function get_patient_token_details_for_type_department($depid='',$booking_date='',$type='')
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("path_patient_to_token.*"); 
     $this->db->from('path_patient_to_token');
     $this->db->where('path_patient_to_token.department_id',$depid);
     $this->db->where('path_patient_to_token.booking_date',$booking_date);
     $this->db->where('path_patient_to_token.type',$type);
     $this->db->where('path_patient_to_token.branch_id',$user_data['parent_id']);
     $this->db->order_by("id", "DESC");
       $this->db->limit(1, 0);
     $query = $this->db->get(); 
    // echo $this->db->last_query();die();
     return $query->row_array();

    }
    public function get_patient_token_details_for_type_hospital($booking_date='',$type='')
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("path_patient_to_token.*"); 
     $this->db->from('path_patient_to_token');
     $this->db->where('path_patient_to_token.booking_date',$booking_date);
     $this->db->where('path_patient_to_token.type',$type);
     $this->db->where('path_patient_to_token.branch_id',$user_data['parent_id']);
     $this->db->order_by("id", "DESC");
       $this->db->limit(1, 0);
     $query = $this->db->get(); 
    // echo $this->db->last_query();die();
     return $query->row_array();

    }
    public function test_token($depid='',$booked_date='',$token_type='')
    {
      $booking_date=$booked_date;
        $token_no='';
           if($token_type=='0') //hospital wise token no
            { 
               $patient_token_details_for_hospital=$this->get_patient_token_details_for_type_hospital($booking_date,$token_type);
              if($patient_token_details_for_hospital['token_no']>'0')
              {
                $token_no=$patient_token_details_for_hospital['token_no']+1;
              }
              else
              {
                $token_no='1';     
              } 
                 
             }
             elseif($token_type=='1') // department wise token no
             {
                $patient_token_details_for_doctor=$this->get_patient_token_details_for_type_department($depid,$booking_date,$token_type);
              if($patient_token_details_for_doctor['token_no']>'0')
              {
                $token_no=$patient_token_details_for_doctor['token_no']+1;
              }
              else
              {
                $token_no='1';
              }

             }
             return $token_no;
    }


    public function get_token_exist_or_not($patient,$booking_id,$booked_date,$token_type,$branch_id)
    {
     $this->db->select("department_id,id"); 
     $this->db->from('path_patient_to_token');
     $this->db->where('path_patient_to_token.booking_date',$booked_date);
     $this->db->where('path_patient_to_token.type',$token_type);
     $this->db->where('path_patient_to_token.patient_id',$patient);
     $this->db->where('path_patient_to_token.branch_id',$branch_id);
     $this->db->where('path_patient_to_token.booking_id',$booking_id);
     $query = $this->db->get(); 
     //echo $this->db->last_query();die();
     return $query->result_array();

    }


    public function payment_list($booking_id="",$branch_id="")
    {
      //echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_payment.*, hms_payment_mode.payment_mode"); 
     $this->db->from('hms_payment');
     $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
     $this->db->where('hms_payment.branch_id',$branch_id);
     $this->db->where('hms_payment.parent_id',$booking_id);
     $this->db->where('hms_payment.section_id','1');
     $this->db->order_by('hms_payment.id', 'DESC');
     $query = $this->db->get(); 
     return $query->result_array();
    }


    public function total_payment($booking_id="",$branch_id="",$section_id="")
    {
      //echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("
                         sum(hms_payment.credit) total_credit, 
                         sum(hms_payment.debit) total_debit, 
                         (sum(hms_payment.credit)-sum(hms_payment.debit)) as total_balance
                         "); 
     $this->db->from('hms_payment');
     $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
     $this->db->where('hms_payment.branch_id',$branch_id);
     $this->db->where('hms_payment.parent_id',$booking_id);
     $this->db->where('hms_payment.section_id',$section_id);
     $query = $this->db->get(); 
     //echo $this->db->last_query(); 
     return $query->row_array();
    }
    
 public function sample_type_list_new($test_id='',$sample_id='')
  {
      $users_data = $this->session->userdata('auth_users'); 
      

      /*$this->db->select('path_sample_type.*,(select `path_test`.`sample_test` from path_test where path_test.sample_test=path_sample_type.id AND path_test.branch_id='.$users_data['parent_id'].') as sampl');*/ 
      $this->db->select('path_sample_type.*'); 
      $this->db->where('status','1'); 
      $this->db->order_by('sample_type','ASC');
      $this->db->where('is_deleted',0);
      $this->db->where('branch_id',$users_data['parent_id']);  
      $query = $this->db->get('path_sample_type');
      //echo $this->db->last_query();die();
      $result = $query->result(); 
      return $result;
      
  }
  
   public function patient_booking_list($ipd_id="", $branch_id="")
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select("path_test_booking.id,path_test_booking.patient_id,path_test_booking.ipd_id,path_test_booking.booking_date"); 
    $this->db->where('path_test_booking.is_deleted','0'); 
    $this->db->where('path_test_booking.branch_id = "'.$user_data['parent_id'].'"');
    $this->db->where('path_test_booking.ipd_id = "'.$ipd_id.'"');
    $this->db->from('path_test_booking');
    $this->db->order_by('path_test_booking.booking_date','ASC');
    $result_booking = $this->db->get()->result();
    return $result_booking;
  }
  
  public function get_all_profile_test_list_for_summary($booking_id="",$profile_id="")
  {
        //echo $profile_id; //echo $profile_id; //exit;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_test_booking_to_test.*,path_test.test_name, path_test.interpretation as test_interpretation,path_test.range_from,path_test.range_to,path_test.unit_id,path_test.test_type_id, path_test.range_from_pre, path_test.range_from_post, path_test.range_to_pre, path_test.range_to_post, path_test.all_range_show,path_test_method.test_method,path_sample_type.sample_type');
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left'); 
        $this->db->where('path_test_booking_to_test.booking_id = "'.$booking_id.'"');
        if(!empty($profile_id))
        {
           $this->db->where('path_test_booking_to_test.profile_id = "'.$profile_id.'"');
           $this->db->join('path_profile_to_test','path_profile_to_test.test_id = path_test_booking_to_test.test_id AND path_profile_to_test.profile_id = path_test_booking_to_test.profile_id','left');
           /*********New Code**************/
          $this->db->join('path_test_method','path_test_method.id = path_test.method_id','left'); 
          $this->db->join('path_sample_type','path_sample_type.id = path_test.sample_test','left'); 
        /**********New Code************/
        } 
        $this->db->where('path_test_booking_to_test.print ="1"');
        
        $this->db->where('path_test_booking_to_test.test_type  IN (1,2)');
        $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id'); 
        
        if(!empty($profile_id))
        {
           $this->db->order_by('path_profile_to_test.sort_order','ASC');
        }
        else
        {
            $this->db->order_by('path_test_heads.sort_order','ASC');  
            $this->db->order_by('path_test.sort_order','ASC');    
        }
        //$this->db->where('path_test_heads.id = "'.$thead_id.'"');
        $this->db->from('path_test_booking_to_test');
        $result =  $this->db->get()->result();
        //echo $this->db->last_query(); exit;
        return $result; 
   }

  
   public function get_total_test($id="")
   {
      if(!empty($id))
      {
          $this->db->select('path_profile_to_test.id'); 
          $this->db->where('path_profile_to_test.profile_id',$id);
          $query = $this->db->get('path_profile_to_test');
          //echo $this->db->last_query(); exit;
          $result = $query->result(); 
          $total = count($result);
          return $total;
      }
    }

    public function get_booked_count($profile_id='',$ipd_id='')
    {

      $sql = "SELECT * FROM `path_test_booking_to_profile` WHERE test_booking_id IN(SELECT id  FROM `path_test_booking` WHERE `ipd_id` = ".$ipd_id.") AND profile_id=".$profile_id;

      $query = $this->db->query($sql);
      //echo $this->db->last_query(); exit;
      $result = $query->result();
      $total = count($result); 
      if(!empty($total))
      {
        return $total;
      }
      else
      {
        return '0';
      }
      
      
    }


  function get_test_result_by_date($test_id="",$profile_id="",$date="",$booking_id='')
  {
     $users_data = $this->session->userdata('auth_users');
    $this->db->select('path_test_booking_to_test.result');
    $this->db->join('path_test_booking','path_test_booking.id = path_test_booking_to_test.booking_id AND path_test_booking.booking_date="'.$date.'" AND path_test_booking.branch_id="'.$users_data['parent_id'].'"');
    $this->db->where('path_test_booking_to_test.test_id',$test_id);
    if(!empty($profile_id))
    {
      $this->db->where('path_test_booking_to_test.profile_id',$profile_id);
    }
    $this->db->where('path_test_booking_to_test.booking_id',$booking_id);
    $query = $this->db->get('path_test_booking_to_test');
    //echo "<pre>";echo $this->db->last_query();
    $result = $query->row_array(); 
     
    return $result;
  }
  
   public function default_doctor_setting()
   {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_default_path_doct_setting.doctor_id"); 
        $this->db->from('hms_default_path_doct_setting');
        $this->db->where('hms_default_path_doct_setting.branch_id',$user_data['parent_id']);
        $query = $this->db->get(); 
        return $query->row_array();
   }
   
   public function get_report_test($booking_id="")
   {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('*');   
    
      $this->db->where('booking_id',$booking_id); 
      $query = $this->db->get('path_test_booking_to_test');
      $result = $query->result();  
      $test_list = [];
      if(!empty($result))
      { 
        foreach($result as $test)
        {
         $test_list[] = array('test_id'=>$test->test_id, 'amount'=>$test->amount,'sample_type_id'=>$test->sample_type_id,'result'=>$test->result,'interpretation'=>$test->interpretation,'interpretation_data'=>$test->interpratation_data,'print'=>$test->print,'net_amount'=>$test->net_amount,'master_rate'=>$test->master_rate,'base_rate'=>$test->base_rate,'adv_interpretation_status'=>$test->adv_interpretation_status);
       }
     }
     
     return $test_list; 
    }
    
    public function check_temp_test($booking_id="",$test_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->where('booking_id',$booking_id);   
        $this->db->where('path_temp_test_booking.test_id',$test_id);  
        $query = $this->db->get('path_temp_test_booking');
        $result = $query->row();  
        
        return $result; 
    }
    
    public function profile_sort_order($id='',$sub_profile='') //2095
  {
        $this->db->select("sort_order");
        $this->db->where("profile_id",$id);
        $this->db->where('multi_profile_id',$sub_profile);
        $this->db->from('path_profile_to_profile');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die; 
        $result_order = $query->result();
        return $p_order = $result_order[0]->sort_order; 

    }
    
     public function get_widal_value($booking_id='',$test_id='')
      {
       $users_data = $this->session->userdata('auth_users'); 
       if(!empty($users_data['parent_id']))
       { 
        $this->db->select('hms_path_test_booking_to_test_widal.*');
        $this->db->from('hms_path_test_booking_to_test_widal'); 
        $this->db->where('hms_path_test_booking_to_test_widal.booking_id',$booking_id); 
        $this->db->where('hms_path_test_booking_to_test_widal.test_id',$test_id);
        $this->db->where('hms_path_test_booking_to_test_widal.branch_id',$users_data['parent_id']); 
        $query = $this->db->get(); 
        return $query->row();
      }
    }
    public function update_widal_value()
    {
      $users_data = $this->session->userdata('auth_users'); 
      $post=$this->input->post();
      $data=array('branch_id'=>$users_data['parent_id'], 'booking_id'=>$post['booking_id'], 'test_id'=>$post['test_id'], 'typho_1'=>$post['typho_1'], 'typho_2'=>$post['typho_2'], 'typho_3'=>$post['typho_3'], 'typho_4'=>$post['typho_4'], 'typho_5'=>$post['typho_5'], 'typhh_1'=>$post['typhh_1'], 'typhh_2'=>$post['typhh_2'], 'typhh_3'=>$post['typhh_3'], 'typhh_4'=>$post['typhh_4'], 'typhh_5'=>$post['typhh_5'], 'typhah_1'=>$post['typhah_1'], 'typhah_2'=>$post['typhah_2'], 'typhah_3'=>$post['typhah_3'], 'typhah_4'=>$post['typhah_4'], 'typhah_5'=>$post['typhah_5'], 'typhbh_1'=>$post['typhbh_1'], 'typhbh_2'=>$post['typhbh_2'], 'typhbh_3'=>$post['typhbh_3'], 'typhbh_4'=>$post['typhbh_4'], 'typhbh_5'=>$post['typhbh_5']);
      if(!empty($post['data_id']) || $post['data_id'] !='')
      {
        $this->db->where('hms_path_test_booking_to_test_widal.booking_id',$post['booking_id']);         
        $this->db->where('hms_path_test_booking_to_test_widal.test_id',$post['test_id']);
        $this->db->where('hms_path_test_booking_to_test_widal.branch_id',$users_data['parent_id']);
        $this->db->update('hms_path_test_booking_to_test_widal',$data);
      }
      else{
        $this->db->insert('hms_path_test_booking_to_test_widal',$data);
      }
      return 1;
    }  
    
    
    public function crm_get_by_id($id='')
    {
      $this->db->select("crm_leads.*, crm_department.department, crm_lead_type.lead_type, crm_source.source, crm_users.name as uname");
      $this->db->join("crm_department","crm_department.id=crm_leads.department_id","left");
      $this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
      $this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
      $this->db->join("crm_users","crm_users.id=crm_leads.created_by","left"); 
      $this->db->where('crm_leads.id', $id);
      $query = $this->db->get('crm_leads');
      //echo $this->db->last_query(); 
      return $query->row_array();
    }
    
    public function lead_test_list($lead_id="")
    {
      $this->db->select('crm_lead_booking_to_test.test_id');
      if(!empty($lead_id))
        {
               $this->db->where('crm_lead_booking_to_test.lead_id',$lead_id);  
        } 
      $query = $this->db->get('crm_lead_booking_to_test');
      //echo $this->db->last_query(); 
      return $query->result_array();
    }
    
    public function lead_profile_list($lead_id="")
    {
      $this->db->select('crm_lead_booking_to_profile.profile_id');
      if(!empty($lead_id))
        {
               $this->db->where('crm_lead_booking_to_profile.lead_id',$lead_id);  
        } 
      $query = $this->db->get('crm_lead_booking_to_profile');
      return $query->result_array();
    }
    
    public function get_total_booking($complation_status='')
   {
        $search_date = $this->session->userdata('search_booking_date');
        $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        $users_data = $this->session->userdata('auth_users');
        $this->db->from('path_test_booking');
        $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id'); 

        $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
        $this->db->where('path_test_booking.is_deleted','0'); 
        $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
        
        
        if(isset($search_date) && !empty($search_date))
        {
          if(isset($search_date['start_date']) && !empty($search_date['start_date']))
          {
            $start_date = date('Y-m-d',strtotime($search_date['start_date']));
            $this->db->where('path_test_booking.booking_date >= "'.$start_date.'"');
          }

          if(isset($search_date['end_date']) && !empty($search_date['end_date'])
            )
          {
            $end_date = date('Y-m-d',strtotime($search_date['end_date']));
            $this->db->where('path_test_booking.booking_date <= "'.$end_date.'"');
          }
          
            
            if(isset($search_date['simulation_id']) && !empty($search_date['simulation_id']) )
          {
            $this->db->where('hms_patient.simulation_id',$search_date['attended_doctor']);
          }

          if(isset($search_date['patient_name']) && !empty($search_date['patient_name']) )
          {
            $this->db->where('hms_patient.patient_name LIKE "'.$search_date['patient_name'].'%"');
          }

          if(isset($search_date['mobile_no']) && !empty($search_date['mobile_no']) )
          {
            $this->db->where('hms_patient.mobile_no',$search_date['mobile_no']);
          }

          if(isset($search_date['age_from']) && !empty($search_date['age_from']) )
          {
            $this->db->where('hms_patient.age_y >= "'.$search_date['age_from'].'"');
          }

          if(isset($search_date['age_to']) && !empty($search_date['age_to']) )
          {
            $this->db->where('hms_patient.age_y <= "'.$search_date['age_to'].'"');
          }

          if(isset($search_date['gender']) && !empty($search_date['gender']) )
          {
            $this->db->where('hms_patient.gender',$search_date['gender']);
          }

          if(isset($search_date['city_id']) && !empty($search_date['city_id']) )
          {
            $this->db->where('hms_patient.city_id',$search_date['city_id']);
          }

          if(isset($search_date['country_id']) && !empty($search_date['country_id']) )
          {
            //$this->db->where('hms_patient.country_id',$search_date['country_id']);
          }

          if(isset($search_date['state_id']) && !empty($search_date['state_id']) )
          {
            $this->db->where('hms_patient.state_id',$search_date['state_id']);
          }

          if(isset($search_date['pincode']) && !empty($search_date['pincode']) )
          {
            $this->db->where('hms_patient.pincode',$search_date['pincode']);
          }

 /* refered by code */
      if(isset($search_date['referral_hospital']) && $search_date['referred_by']=='1' && !empty($search_date['referral_hospital']))
      {
      $this->db->where('path_test_booking.referral_hospital' ,$search_date['referral_hospital']);
      }
            elseif(isset($search_date['refered_id']) && $search_date['referred_by']=='0' && !empty($search_date['refered_id']))
            {
                $this->db->where('path_test_booking.referral_doctor' ,$search_date['refered_id']);
            }
          
        }
        else
        {
            
            $this->db->where('path_test_booking.booking_date >= "'.date('Y-m-d').'"');
         
            $this->db->where('path_test_booking.booking_date <= "'.date('Y-m-d').'"');
          
        }
        
         if($complation_status!="" && !empty($complation_status))
          {
              if($complation_status==5)
              {
                $this->db->where('path_test_booking.delivered_status',1);
              }
              else if($complation_status==4)
              {
                $this->db->where('path_test_booking.verify_status=1 AND path_test_booking.delivered_status=0');
              }
              else if($complation_status==3)
              {
                $this->db->where('path_test_booking.complation_status=1 AND path_test_booking.verify_status=0 AND path_test_booking.delivered_status=0');
              }
              else if($complation_status==2)
              {
                $this->db->where('path_test_booking.delivered_status=0 AND path_test_booking.verify_status=0 AND path_test_booking.complation_status=0');
              }
              else if($complation_status==6)
              {
                $this->db->where('path_test_booking.is_home_collection=1');
              }
              else
              {
                
              }
              
              $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                    $this->db->where('path_test_booking.created_by',$emp_ids);
                }
            }
            
          }
         // echo $this->db->last_query(); //exit;
        return $this->db->count_all_results();
   }
   
    public function check_gst_vals($test_id='',$profile_id='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_gst_vals.highlight as gst_status,path_gst_vals.gst_vals');  
        $this->db->where('path_gst_vals.branch_id',$users_data['parent_id']);  
        $query = $this->db->get('path_gst_vals');
        $result = $query->result(); 
        $gst_status = $result[0]->gst_status; 
        if($gst_status=='0')
        {
            return false;
        }
        elseif($gst_status=='1')
        {
            
            return $gst_vals = $result[0]->gst_vals;
        }
        elseif($gst_status=='2' && !empty($test_id))
        {
            
            $this->db->select("path_gst_vals.*"); 
    		$this->db->from('path_gst_vals');
    		$this->db->join('path_gst_val_to_test','path_gst_val_to_test.gst_val_id = path_gst_vals.id AND path_gst_val_to_test.test_id='.$test_id);
    
    		$this->db->where('path_gst_val_to_test.highlight_type',2);
    		$this->db->where('path_gst_val_to_test.test_id',$test_id);
    		
    		$this->db->group_by('path_gst_vals.id');
    		$result_2= $this->db->get()->result();
    		return $gst_vals = $result_2[0]->gst_vals; 
            
        }
        elseif($gst_status=='2' && !empty($profile_id))
        {
            
            $this->db->select("path_gst_vals.*"); 
    		$this->db->from('path_gst_vals');
    		$this->db->join('path_gst_val_to_test','path_gst_val_to_test.gst_val_id = path_gst_vals.id AND path_gst_val_to_test.profile_id='.$profile_id);
    
    		$this->db->where('path_gst_val_to_test.highlight_type',2);
    		$this->db->where('path_gst_val_to_test.profile_id',$profile_id);
    		
    		$this->db->group_by('path_gst_vals.id');
    		$result_2= $this->db->get()->result();
    		//echo $this->db->last_query(); exit;
    		return $gst_vals = $result_2[0]->gst_vals; 
            
        }
        
    }
     
    public function get_gst_vals($test_id='',$profile_id='')
    {
        $users_data = $this->session->userdata('auth_users'); 
    	if(!empty($test_id) || !empty($profile_id))
    	{
            
	        $this->db->select('path_gst_vals.*');  
	        $this->db->where('path_gst_vals.branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('path_gst_vals');
	        $result = $query->result(); 
	        
	        $highlight = $result[0]->highlight; 
	        if($highlight=='0')
	        {
	            return 0;
	        }
	        elseif($highlight=='1')
	        {
	            return $gst_vals = $result[0]->gst_vals; 
	        }
	        elseif($highlight=='2' && !empty($test_id))
	        {
	            
	            $this->db->select("path_gst_vals.*"); 
        		$this->db->from('path_gst_vals');
        		$this->db->join('path_gst_val_to_test','path_gst_val_to_test.gst_val_id = path_gst_vals.id AND path_gst_val_to_test.test_id='.$test_id);
        
        		$this->db->where('path_gst_val_to_test.highlight_type',2);
        		$this->db->where('path_gst_val_to_test.test_id',$test_id);
        		
        		$this->db->group_by('path_gst_vals.id');
        		$result_2= $this->db->get()->result();
        		return $gst_vals = $result_2[0]->gst_vals; 
	            
	        }
	        elseif($highlight=='2' && !empty($profile_id))
	        {
	            
	            $this->db->select("path_gst_vals.*"); 
        		$this->db->from('path_gst_vals');
        		$this->db->join('path_gst_val_to_test','path_gst_val_to_test.gst_val_id = path_gst_vals.id AND path_gst_val_to_test.profile_id='.$profile_id);
        
        		$this->db->where('path_gst_val_to_test.highlight_type',2);
        		$this->db->where('path_gst_val_to_test.profile_id',$profile_id);
        		
        		$this->db->group_by('path_gst_vals.id');
        		$result_2= $this->db->get()->result();
        		return $gst_vals = $result_2[0]->gst_vals; 
	            
	        }
        } 
    }
    
    //invoice 
    
  
  public function invoice_get_by_id($id)
  {
    $user_data = $this->session->userdata('auth_users');
    $branch_ids = $user_data['parent_id'];
    $this->db->select('path_test_booking_invoice.*, hms_patient.patient_email,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.age_h ,hms_patient.address,,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob');
    $this->db->from('path_test_booking_invoice'); 
    $this->db->join('hms_patient','hms_patient.id = path_test_booking_invoice.patient_id'); 
    $this->db->where('path_test_booking_invoice.id',$id);
    $this->db->where('path_test_booking_invoice.is_deleted','0');
    
    $query = $this->db->get(); 
    return $query->row_array();
  }
    
    
    
    public function invoice_test_list($booking_id="")
    {
      $this->db->select('path_test_booking_to_test_invoice.test_id');
      if(!empty($booking_id))
        {
               $this->db->where('path_test_booking_to_test_invoice.booking_id',$booking_id);  
        } 
      $this->db->where('path_test_booking_to_test_invoice.test_type IN (0,2)'); 
      $this->db->where('path_test_booking_to_test_invoice.parent_status','1');     
      $query = $this->db->get('path_test_booking_to_test_invoice');
      //echo $this->db->last_query(); 
      return $query->result_array();
    }
    
    public function invoice_profile_list($test_booking_id="")
    {
      $this->db->select('path_test_booking_to_profile_invoice.profile_id');
      if(!empty($test_booking_id))
        {
         $this->db->where('path_test_booking_to_profile_invoice.test_booking_id',$test_booking_id);  
        } 
      $query = $this->db->get('path_test_booking_to_profile_invoice');
      return $query->result_array();
    }
    
//end of invoice
  
  
    public function diseases_list($branch_id="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_disease.disease','ASC');
		$this->db->where('hms_disease.status',1);
		$this->db->where('hms_disease.is_deleted',0);
		if(!empty($branch_id))
		{
			$this->db->where('hms_disease.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_disease.branch_id',$users_data['parent_id']);	
		}
		  
		$query = $this->db->get('hms_disease');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }
    public function patient_diagnose_list($patient_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_test_diagnose.*, path_diagnose.diagnose as diagnose_name'); 
        $this->db->join('path_diagnose','path_diagnose.id = path_test_diagnose.diagnose','left');
        if(!empty($patient_id))
        {
          $this->db->where('path_test_diagnose.patient_id',$patient_id);
        }  
        $this->db->where('path_test_diagnose.branch_id',$users_data['parent_id']);
        $this->db->order_by('path_test_diagnose.date','DESC');      
        $query = $this->db->get('path_test_diagnose');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result; 
    }
    
    public function remarks_list($branch_id="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_test_remarks.remarks_title','ASC');
		$this->db->where('hms_test_remarks.is_deleted',0);
		if(!empty($branch_id))
		{
			$this->db->where('hms_test_remarks.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_test_remarks.branch_id',$users_data['parent_id']);	
		}
		  
		$query = $this->db->get('hms_test_remarks');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }
    
    public function get_all_test_advance_range($id="", $gender="", $age_y="", $age_m="", $age_d="",$age_h='')
    {  
    $this->db->select('path_test_range.*'); 
    if(!empty($id))
    {
      $this->db->where('path_test_range.test_id',$id); 
    } 
    if($gender!="")
    {
      $this->db->where('(path_test_range.gender = "'.$gender.'" OR path_test_range.gender = "2")');
      //$this->db->where('path_test_range.gender',$gender); 
    }

    if(!empty($age_y) && $age_y>0)
    {
      $this->db->where('path_test_range.age_type',2);
      $this->db->where($age_y.' BETWEEN path_test_range.start_age AND path_test_range.end_age'); 
    }

    else if(!empty($age_m) && $age_m>0)
    {
      $this->db->where('path_test_range.age_type',1);
      $this->db->where($age_m.' BETWEEN path_test_range.start_age AND path_test_range.end_age'); 
    }

    else if(!empty($age_d) && $age_d>0)
    {
      $this->db->where('path_test_range.age_type',0);
      $this->db->where($age_d.' BETWEEN path_test_range.start_age AND path_test_range.end_age'); 
    }

    else if(!empty($age_h) && $age_h>0)
    {
      //echo "sdsds";
      $this->db->where('path_test_range.age_type',3);
      $this->db->where($age_h.' BETWEEN path_test_range.start_age AND path_test_range.end_age'); 
    }


    $query = $this->db->get('path_test_range');
    $result = $query->result();
     //$userss_data = $this->session->userdata('auth_users');
     /* if($userss_data['parent_id']==114)
      {
         echo $this->db->last_query(); die;
      }*/
    //echo $this->db->last_query();
    //print_r($result); exit; 
    return $result;
    }

// Please write code above
}
?>