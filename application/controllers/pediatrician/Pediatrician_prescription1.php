<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pediatrician_prescription1 extends CI_Controller 
{
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
    $this->load->model('pediatrician/pediatrician_prescription1/pediatrician_prescription_model','pediatrician'); 
    $this->load->model('pediatrician/pediatrician_age_chart/Pediatrician_age_chart_model','age_chart');
    $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
    $this->load->library('form_validation');
  }

  public function add_vaccination_prescription($booking_id="",$date_se="",$patient_id="")
  {
      $date_select=date('d-m-Y',strtotime($date_se));
      $data['vaccination_list']= $this->age_chart->vaccination_list();
      $data['age_list']= $this->age_chart->age_list();
      $data['date_age_list']= $this->pediatrician->date_age_list($date_select);
      $data['list'] = $this->age_chart->get_datatables(); 
      $data['booking_id']=$booking_id;
      $data['patient_id']=$patient_id;
      $data['vaccine_date']=$date_se;
      $data['page_title'] = 'Vaccination Prescription'; 
      $this->load->view('pediatrician/pediatrician_prescription1/vaccination_pediatrician',$data);
  }
   
   public function save_date_vaccine($vaccine_id="",$age_id="",$booking_id="",$patient_id="",$date_vaccine="",$edit_id='')
   {
    $this->load->model('general/general_model'); 
    $data['page_title']="Vaccination Prescription";
    $data['payment_mode']=$this->general_model->payment_mode();
    $data['res_vaccine'] = $this->pediatrician->check_stock_avability($vaccine_id);
    
    if(isset($data['res_vaccine']->avl_qty))
    {
     $avl_qty=$data['res_vaccine']->avl_qty;
    }
    else
    {
      $avl_qty='';
    }
    if(isset($data['res_vaccine']->mrp))
    {
      $mrp= $data['res_vaccine']->mrp;
    }
    else
    {
      $mrp='';
    }
    if(isset($data['res_vaccine']->discount))
    {
      $discount= $data['res_vaccine']->discount;
    }
    else
    {
      $discount='';
    }
    if(isset($vaccine_id))
    {
      $vaccine_id= $vaccine_id;
    }
    else
    {
      $vaccine_id='';
    }
    if(isset($age_id))
    {
      $age_id= $age_id;
    }
    else
    {
      $age_id='';
    }
    if(isset($edit_id))
    {
      $edit_id= $edit_id;
    }
    else
    {
      $edit_id='';
    }
    if(!empty($edit_id))
    {
      $result = $this->pediatrician->get_by_id_peditration_vaccine($edit_id);
      $data['payment_mode']=$this->general_model->payment_mode();
      $get_payment_detail= $this->pediatrician->payment_mode_detail_according_to_field($result['payment_mode'],$edit_id);
      $total_values='';
      for($i=0;$i<count($get_payment_detail);$i++) {
      $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;
      }
       
       $data['form_data']=array(
                            'data_id'=>$edit_id, 
                            'vaccination_date_time'=>date('d-m-Y H:i:s',strtotime($result['vaccination_date_time'])),
                            'vaccine_id'=>$result['vaccine_id'],
                            'age_id'=>$result['age_id'],
                             "field_name"=>$total_values,
                            'booking_id'=>$result['booking_id'],
                            'patient_id'=>$result['patient_id'],
                            'attended_doctor'=>$result['attended_doctor'],
                            'total_amount'=>$result['total_amount'],
                            'discount'=>$result['discount'],
                            'net_amount'=>$result['net_amount'],
                            'payment_mode'=>$result['payment_mode'],
                            'paid_amount'=>$result['paid_amount'],
                            'balance'=>$result['balance']
                            );
     
    }
    else
    {
      $data['form_data']=array(
                            'data_id'=>$edit_id,
                            'qty'=>$avl_qty,
                            'total_amount'=>$mrp,
                            'vaccine_id'=>$vaccine_id,
                            'age_id'=>$age_id,
                             "field_name"=>'',
                            'payment_mode'=>'',
                            'attended_doctor'=>'',
                            'booking_id'=>$booking_id,
                            'patient_id'=>$patient_id,
                            'discount'=>$discount 
                            );
    }
    
   // print_r($data['form_data']);die;
    $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();

    $this->load->view('pediatrician/pediatrician_prescription1/date_picker_vaccine',$data);
   }  

   public function add_vaccine_pres()
   {
      //unauthorise_permission('12','65');

        $data['page_title'] = "Add Simulation";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'vaccine_date_time'=>"",
                                  'attended_doctor'=>"",
                                  'total_amount'=>"",
                                  'discount'=>"",
                                  'net_amount'=>"",
                                  'paid_amount'=>"",
                                  "field_name"=>'',
                                  'balance'=>"" 
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
             if($this->form_validation->run() == TRUE)
            {
                $this->pediatrician->save();
                echo 1;
                return false;
                
             }
            else
            {
            $data['form_error'] = validation_errors();  
           }     
        }
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        $this->load->model('general/general_model'); 
        $data['payment_mode']=$this->general_model->payment_mode();
        $this->load->view('pediatrician/pediatrician_prescription1/date_picker_vaccine',$data); 
     } 

   public function edit_vaccine_pres($id="")
   {
      
      //unauthorise_permission('12','65');
        $result = $this->pediatrician->get_by_id_peditration_vaccine($id); 

        $data['page_title'] = "Add Simulation";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>$id, 
                                  'vaccination_date_time'=>date('d-m-Y H:i:s',strtotime($result['vaccination_date_time'])),
                                  'attended_doctor'=>$result['attended_doctor'],
                                  'total_amount'=>$result['total_amount'],
                                  'discount'=>$result['discount'],
                                  'net_amount'=>$result['net_amount'],
                                  'paid_amount'=>$result['paid_amount'],
                                  'balance'=>$result['balance']
                                  );    

        if(isset($post) && !empty($post))
        {   
          $data['form_data'] = $this->_validate();
          if($this->form_validation->run() == TRUE)
          {
          $this->pediatrician->save();
          echo 1;
          return false;

          }
          else
          {
          $data['form_error'] = validation_errors();  
          }     
        }
      $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
      $this->load->model('general/general_model'); 
      $data['payment_mode']=$this->general_model->payment_mode();
       $this->load->view('pediatrician/pediatrician_prescription1/date_picker_vaccine',$data); 
       
   
   }   
   private function _validate()
    {
        $post = $this->input->post();    
        $total_values=array();
        $this->load->model('general/general_model'); 
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        if(isset($post['field_name']))
        {
            $count_field_names= count($post['field_name']);  
            $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);
            for($i=0;$i<$count_field_names;$i++) 
            {
              $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

            }
        }
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation', 'simulation', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data']=array(
                            'data_id'=>$post['data_id'], 
                            'vaccination_date_time'=>date('d-m-Y H:i:s',strtotime($post['vaccination_date_time'])),
                            'vaccine_id'=>$post['vaccine_id'],
                            'age_id'=>$post['age_id'],
                             "field_name"=>$total_values,
                            'booking_id'=>$post['booking_id'],
                            'patient_id'=>$post['patient_id'],
                            'attended_doctor'=>$post['attended_doctor'],
                            'total_amount'=>$post['total_amount'],
                            'discount'=>$post['discount'],
                            'net_amount'=>$post['net_amount'],
                            'payment_mode'=>$post['payment_mode'],
                            'paid_amount'=>$post['paid_amount'],
                            'balance'=>$post['balance'],
                            "field_name"=>$total_values,
                            );
            return $data['form_data'];
        }   
    }
  public function payment_calc_all()
    { 
      $post = $this->input->post();
      $total_amount = 0;
      $total_discount =0;
      $net_amount =0;  
      $total_discount =0;
      $net_amount =0;  
      $total_new_amount=0;
      $tot_discount_amount=0;
      $payamount=0;

      if($post['discount']!='' && $post['discount']!=0)
      {
        $total_discount = ($post['discount']/100)*$post['total_amount'];}
      else
      {
        $total_discount=0;
      }
      $net_amount = ($post['total_amount']-$total_discount);
      if($post['pay']==1 || $post['data_id']!='')
      {
      $payamount=$post['pay_amount'];
      }else
      {
      $payamount=$net_amount;
      }
      $blance_dues=$net_amount -$payamount;
      $blance_due = number_format($blance_dues,2,'.','');
      $payamount = number_format($payamount,2,'.','');
      $pay_arr = array('total_amount'=>number_format($post['total_amount'],2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>$payamount,'balance_due'=>$blance_due,'discount'=>$post['discount'],'discount_amount'=>number_format($total_discount,2,'.',''));
      $json = json_encode($pay_arr,true);
      echo $json;die;
    }

    public function get_payment_mode_data()
    {
        $this->load->model('general/general_model'); 
        $payment_mode_id= $this->input->post('payment_mode_id');
        $error_field= $this->input->post('error_field');

        $get_payment_detail= $this->general_model->payment_mode_detail($payment_mode_id); 
        $html='';
        $var_form_error='';
        //print_r($get_payment_detail);die;
        foreach($get_payment_detail as $payment_detail)
        {

        if(!empty($error_field))
        {

        $var_form_error= $error_field; 
        }
        $html.='<div class="row"><div class="col-md-12 m-b1"><div class="row"><div class="col-md-4"><label>'.$payment_detail->field_name.'<span class="star">*</span></label></div> <div class="col-md-8"><input type="text" name="field_name[]" value="" onkeypress="payment_calc_all();"><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /></div><div class="f_right">'.$var_form_error.'</div></div></div></div>';
        }
        echo $html;exit;

    }

    public function history_vaccine_pres()
    {
       $data['page_title']="Vaccination History";

       $data['vaccine_history']= $this->pediatrician->get_vaccine_history();
       $this->load->view('pediatrician/pediatrician_prescription1/history_vaccine_pres',$data); 
    }
 } 

 ////////////////////////////////////////////// 12-may-2018///
 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pediatrician_prescription extends CI_Controller 
{
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
      $this->load->model('pediatrician/pediatrician_prescription/Pediatrician_prescription_model','prescription_model');
      $this->load->model('pediatrician/pediatrician_age_chart/Pediatrician_age_chart_model','age_chart');
    $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
    $this->load->library('form_validation');
  }

  public function index()
  {
    unauthorise_permission(275,1629);
    //$data['page_title'] = 'Prescription List'; 
    //$this->load->view('pediatrician/pediatrician_prescription/list',$data);
  }
  

  // Function to open add form starts here
  public function add_growth_prescription($booking_id="")
  {
   unauthorise_permission(275,1629);
  
    if($booking_id!='')
    {
      $data['get_growth_booking_id']=$this->prescription_model->get_growth_pres_by_id($booking_id);

      $data['growth_prescription_ipd_data'] = $this->prescription_model->get_by_opd_id($booking_id);
     
      $patient_id=$data['growth_prescription_ipd_data']['patient_id'];

    }
    if(($booking_id!="") && ($data['get_growth_booking_id']['id']!=''))
    {
      $data['growth_prescription_data'] = $this->prescription_model->get_by_id($booking_id);
      $data['growth_id']     = $data['growth_prescription_data']['id'];
      $data['booking_id']     = $data['growth_prescription_data']['booking_id'];
      $data['patient_id']     = $data['growth_prescription_data']['patient_id'];
      $data['page_title']=     "Edit Pediatrician Growth Prescription";
    }  
    else
    {
      $data['growth_prescription_data']   = "empty";
      $data['growth_id']     = "0";
      $data['booking_id']     = $booking_id;
      $data['patient_id']     = $patient_id;
      $data['page_title']="Add Pediatrician Growth Prescription";
    }
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    
    $this->load->view('pediatrician/pediatrician_prescription/add_growth',$data);
  }

  // function to open add form ends here     
 public function save_growth_prescription()
  {
    $response = $this->_validate();
    $post=$this->input->post();
    //print_r($post);
    if($response!=200)
    {
      echo $response;
    }
    else
    {
      $users_data = $this->session->userdata('auth_users');
      $branch_id=$users_data['parent_id'];
      $growth_id=$this->input->post('growth_id');
      
      $booking_id=$this->input->post('booking_id');
      $date_to_visit='';
      $dob='';
      if(!empty($this->input->post('date_to_visit')))
      {
       $date_to_visit = date('Y-m-d', strtotime($this->input->post('date_to_visit')));  
      }
      else
      {
        $date_to_visit='';
      }
      if(!empty($this->input->post('dob')))
      {
       $dob = date('Y-m-d', strtotime($this->input->post('dob')));  
      }
      else
      {
        $dob='';
      }
      if(!empty($this->input->post('oedema')))
      {
        $oedema_weight='';
        $weight='';
        $oedema_weight=$this->input->post('oedema');
        $weight=$this->input->post('weight');
        if($oedema_weight==2)
        {
          $weight=$weight;

        }
        else{
           $weight='';
        }
      }

      $growth_data_array=array(
                           'id'=>$this->input->post('growth_id'),
                           'booking_id'=>$this->input->post('booking_id'),
                           'patient_id'=>$this->input->post('patient_id'),
                           'branch_id'=>$branch_id,
                           'sex'=>$this->input->post('sex'),
                           'age_y'=>$this->input->post('age_y'),
                           'age_m'=>$this->input->post('age_m'),
                           'age_d'=>$this->input->post('age_d'),
                           'notes'=>$this->input->post('notes'),
                           'weight'=>$weight,
                           'height'=>$this->input->post('height'),
                           'measured'=>$this->input->post('measured'),
                           'oedema'=>$this->input->post('oedema'),
                           'head_circumference'=>$this->input->post('head_circumference'),
                           'muac'=>$this->input->post('muac'),
                           'dob'=>$dob,
                           'date_to_visit'=>$date_to_visit,
                           'triceps_skinfold'=>$this->input->post('triceps_skinfold'),
                           'subscapular_skinfold'=>$this->input->post('subscapular_skinfold'),
                           'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );
      //print_r($growth_data_array);
      //die;

     
      if($growth_id > 0)
      {
       
        $growth_condition=" id=".$growth_id." and booking_id=".$booking_id." and branch_id=".$branch_id." ";
        $growth_data_array["modified_by"]=$users_data['id'];
        $growth_data_array["modified_date"]=date('Y-m-d H:i:s');
        $growth_rec_id=$this->prescription_model->common_update('hms_pediatrician_growth_prescription',$growth_data_array, $growth_condition);
        echo json_encode(array('st'=>1, 'msg'=>'Growth updated successfully'));

      }
      else
      {

        $growth_data_array["branch_id"]=$branch_id;
        $growth_data_array["created_by"]=$users_data['id'];
        $growth_data_array["created_date"]=date('Y-m-d H:i:s');
        $growth_rec_id = $this->prescription_model->common_insert('hms_pediatrician_growth_prescription',$growth_data_array);
        if(!empty($growth_rec_id))
        {
        echo json_encode(array('st'=>1, 'msg'=>'Growth inserted successfully'));
        }
      }
    }
  }

  
  // Function validate
  public function _validate()
  {
    $users_data = $this->session->userdata('auth_users');
    //$field_list = mandatory_section_field_list(2);
    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
    $this->form_validation->set_rules('date_to_visit', 'date to visit', 'trim|required');
    $this->form_validation->set_rules('dob', 'dob', 'trim|required');
   // $this->form_validation->set_rules('weight', 'weight', 'trim|required');
    $this->form_validation->set_rules('height', 'height', 'trim|required');
    $this->form_validation->set_rules('measured', 'measured', 'trim|required');
    $this->form_validation->set_rules('oedema', 'oedema', 'trim|required');
    $this->form_validation->set_rules('age_y', 'age', 'trim|required');


    if ($this->form_validation->run() == FALSE) 
    { 
      echo json_encode(array('st'=>0, 'age_error'=>form_error('age_y'), 'date_to_visit_error'=>form_error('date_to_visit'), 'dob_error'=>form_error('dob'), 'height_error'=>form_error('height'), 'measured_error'=>form_error('measured'), 'oedema_error'=>form_error('oedema')));
    }
    else
    {
        return "200";
    }
  }

  public function ajax_list($booking_id='')
    { 
      //print_r($booking_id);
        unauthorise_permission(275,1629);
        $list = $this->prescription_model->get_datatables($booking_id);  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $prescription_ids=array();
       
        foreach ($list as $prescription_list) {

        // print_r($prescription_list);die;
            $no++;
            $row = array();
            if($prescription_list->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else
            {
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////medicine_preferred_reminder_service
            $check_script = "";
            if($i==$total_num)
            {
            } 
            $measured='';
            $oedema='';
            $sex='';
            if(!empty($prescription_list->measured))
            {
              if($prescription_list->measured==1)
              {
                $measured='Recumbent';

              }
              else
              {
                $measured='Standing';
              }
            }
            //print_r($prescription_list->oedema);
             if(!empty($prescription_list->oedema))
            {
              if($prescription_list->oedema==1)
              {
                $oedema='Yes';

              }
              else
              {
                $oedema='No';
              }
            }

             if(!empty($prescription_list->sex))
            {
              if($prescription_list->sex==0)
              {
                $sex='Male';

              }
              if($prescription_list->sex==1)
              {
                $sex='Female';

              }

              if($prescription_list->sex==2)
              {
                $sex='Others';

              }
            }
            ////////// Check list end ///////////// 
              $row[] = '<input type="checkbox" name="prescription_ids[]" class="checklist" value="'.$prescription_list->id.'">'.$check_script; 
              $row[] = ( strtotime($prescription_list->date_to_visit) > 0 ? date('d-M-Y', strtotime($prescription_list->date_to_visit)) : ''); 
              $row[] = $prescription_list->patient_name;
              $row[]=$prescription_list->weight; 
              $row[]=$oedema;
              $row[]=$measured;
              $row[]=$prescription_list->muac;
              $row[]=$prescription_list->height;
              $row[]=$prescription_list->triceps_skinfold;
              $row[]=$prescription_list->subscapular_skinfold;
            
            $row[]=$sex;
            $row[] = ( strtotime($prescription_list->dob) > 0 ? date('d-M-Y', strtotime($prescription_list->dob)) : ''); 
            $row[] = hms_patient_age_calulator($prescription_list->age_y,$prescription_list->age_m,$prescription_list->age_d);
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($prescription_list->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            //$btnedit='';
            $btndelete='';
            $actions='';
           
         //if(in_array('1509',$users_data['permission']['action']))
            //{
                 $btndelete = ' <a class="btn-custom" onClick="return delete_growth_type('.$prescription_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
            //}
        $row[] = $btndelete;
            $data[] = $row;
            $i++;
     }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription_model->count_all(),
                        "recordsFiltered" => $this->prescription_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

     public function delete($id="")
    {
      //unauthorise_permission(275,1624);
     
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_model->delete($id);
           $response = "Growth successfully deleted.";
           echo $response;
       }
    }

    public function deleteall()
    {
       unauthorise_permission(275,1624);
        $post = $this->input->post(); 
        if(!empty($post))
        {
            $result = $this->prescription_model->deleteall($post['row_id']);
            $response = "Growth successfully deleted.";
            echo $response;
        }
    }
    


  public function add_vaccination_prescription($booking_id="",$date_se="",$patient_id="")
  {
      $date_select=date('d-m-Y',strtotime($date_se));
      $data['vaccination_list']= $this->age_chart->vaccination_list();
      $data['age_list']= $this->age_chart->age_list();
      $data['date_age_list']= $this->prescription_model->date_age_list($date_select);
      $data['list'] = $this->age_chart->get_datatables(); 
      $data['booking_id']=$booking_id;
      $data['patient_id']=$patient_id;
      $data['vaccine_date']=$date_se;
      $data['page_title'] = 'Vaccination Prescription'; 
      $this->load->view('pediatrician/pediatrician_prescription/vaccination_pediatrician',$data);
  }

  public function save_date_vaccine($vaccine_id="",$age_id="",$booking_id="",$patient_id="",$date_vaccine="",$edit_id='')
   {
    $this->load->model('general/general_model'); 
    $data['page_title']="Vaccination Prescription";
    $data['payment_mode']=$this->general_model->payment_mode();
    $data['res_vaccine'] = $this->prescription_model->check_stock_avability($vaccine_id);
    
    if(isset($data['res_vaccine']->avl_qty) && $data['res_vaccine']->avl_qty>0)
    {
     $avl_qty=$data['res_vaccine']->avl_qty;
    }
    else
    {
      $avl_qty='No Stock Avaliable';
    }
    if(isset($data['res_vaccine']->mrp))
    {
      $mrp= $data['res_vaccine']->mrp;
    }
    else
    {
      $mrp='';
    }
    if(isset($data['res_vaccine']->discount))
    {
      $discount= $data['res_vaccine']->discount;
    }
    else
    {
      $discount='';
    }
    if(isset($vaccine_id))
    {
      $vaccine_id= $vaccine_id;
    }
    else
    {
      $vaccine_id='';
    }
    if(isset($age_id))
    {
      $age_id= $age_id;
    }
    else
    {
      $age_id='';
    }
    if(isset($edit_id))
    {
      $edit_id= $edit_id;
    }
    else
    {
      $edit_id='';
    }
    if(!empty($edit_id))
    {
      $result = $this->prescription_model->get_by_id_peditration_vaccine($edit_id);
      $data['payment_mode']=$this->general_model->payment_mode();
      $get_payment_detail= $this->prescription_model->payment_mode_detail_according_to_field($result['payment_mode'],$edit_id);
      $total_values='';
      for($i=0;$i<count($get_payment_detail);$i++) {
      $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;
      }
       
       $data['form_data']=array(
                            'data_id'=>$edit_id, 
                            'vaccination_date_time'=>date('d-m-Y',strtotime($result['vaccination_date_time'])),
                            'qty'=>$avl_qty,
                            'vaccine_id'=>$result['vaccine_id'],
                            'age_id'=>$result['age_id'],
                             "field_name"=>$total_values,
                            'booking_id'=>$result['booking_id'],
                            'patient_id'=>$result['patient_id'],
                            'attended_doctor'=>$result['attended_doctor'],
                            'total_amount'=>$result['total_amount'],
                            'discount'=>$result['discount'],
                            'net_amount'=>$result['net_amount'],
                            'payment_mode'=>$result['payment_mode'],
                            'paid_amount'=>$result['paid_amount'],
                            'balance'=>$result['balance']
                            );
     
    }
    else
    {
      $data['form_data']=array(
                            'data_id'=>$edit_id,
                            'qty'=>$avl_qty,
                            'total_amount'=>$mrp,
                            'vaccination_date_time'=>"",
                            'vaccine_id'=>$vaccine_id,
                            'age_id'=>$age_id,
                             "field_name"=>'',
                            'payment_mode'=>'',
                            'attended_doctor'=>'',
                            'booking_id'=>$booking_id,
                            'patient_id'=>$patient_id,
                            'discount'=>$discount 
                            );
    }
    
   // print_r($data['form_data']);die;
    $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();

    $this->load->view('pediatrician/pediatrician_prescription/date_picker_vaccine',$data);
   }


  public function add_vaccine_pres()
   {
      //unauthorise_permission('12','65');
        $this->session->unset_userdata('book_vaccine_id');
        $data['page_title'] = "Add Simulation";
        $data['last_id']='';   
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'vaccination_date_time'=>"",
                                  'attended_doctor'=>"",
                                  'total_amount'=>"",
                                  'discount'=>"",
                                  'net_amount'=>"",
                                  'paid_amount'=>"",
                                  "field_name"=>'',
                                  'balance'=>"" ,
                                 
                                  );    
      //$data['last_id']=''; 
        if(isset($post) && !empty($post))
        {
          
            $data['form_data'] = $this->_validate_vaccine();
             if($this->form_validation->run() == TRUE)
            {
               $last_id=$this->prescription_model->save_vaccine_pres();
               $this->session->set_userdata('book_vaccine_id', $last_id);
            
                echo 1;
               return false;
                
             }
            else
            {
            $data['form_error'] = validation_errors();  
           }     
        }
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        $this->load->model('general/general_model'); 
        $data['payment_mode']=$this->general_model->payment_mode();
        $this->load->view('pediatrician/pediatrician_prescription/date_picker_vaccine',$data); 
     } 

   public function edit_vaccine_pres($id="")
   {
      
      //unauthorise_permission('12','65');
        $result = $this->prescription_model->get_by_id_peditration_vaccine($id); 

        $data['page_title'] = "Add Simulation";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>$id, 
                                  'vaccination_date_time'=>date('d-m-Y H:i:s',strtotime($result['vaccination_date_time'])),
                                  'attended_doctor'=>$result['attended_doctor'],
                                  'total_amount'=>$result['total_amount'],
                                  'discount'=>$result['discount'],
                                  'net_amount'=>$result['net_amount'],
                                  'paid_amount'=>$result['paid_amount'],
                                  'balance'=>$result['balance']
                                  );    

        if(isset($post) && !empty($post))
        {   
          $data['form_data'] = $this->_validate_vaccine();
          if($this->form_validation->run() == TRUE)
          {
          $this->prescription_model->save_vaccine_pres();
          echo 1;
          return false;

          }
          else
          {
          $data['form_error'] = validation_errors();  
          }     
        }
      $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
      $this->load->model('general/general_model'); 
      $data['payment_mode']=$this->general_model->payment_mode();
       $this->load->view('pediatrician/pediatrician_prescription/date_picker_vaccine',$data); 
       
   
   }   
   private function _validate_vaccine()
    {
        $post = $this->input->post();    
        $total_values=array();
        $this->load->model('general/general_model'); 
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        if(isset($post['field_name']))
        {
            $count_field_names= count($post['field_name']);  
            $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);
            for($i=0;$i<$count_field_names;$i++) 
            {
              $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

            }
        }
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('vaccination_date_time', 'vaccination date time', 'trim|required'); 
         if(isset($post['field_name']))
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data']=array(
                            'data_id'=>$post['data_id'], 
                            'vaccination_date_time'=>date('d-m-Y H:i:s',strtotime($post['vaccination_date_time'])),
                            'vaccine_id'=>$post['vaccine_id'],
                            'age_id'=>$post['age_id'],
                             "field_name"=>$total_values,
                            'booking_id'=>$post['booking_id'],
                            'patient_id'=>$post['patient_id'],
                            'attended_doctor'=>$post['attended_doctor'],
                            'total_amount'=>$post['total_amount'],
                            'discount'=>$post['discount'],
                            'net_amount'=>$post['net_amount'],
                            'payment_mode'=>$post['payment_mode'],
                            'paid_amount'=>$post['paid_amount'],
                            'balance'=>$post['balance']
                             );
            return $data['form_data'];
        }   
    }

  public function payment_calc_all()
    { 
      $post = $this->input->post();
      $total_amount = 0;
      $total_discount =0;
      $net_amount =0;  
      $total_discount =0;
      $net_amount =0;  
      $total_new_amount=0;
      $tot_discount_amount=0;
      $payamount=0;

      if($post['discount']!='' && $post['discount']!=0)
      {
        $total_discount = ($post['discount']/100)*$post['total_amount'];}
      else
      {
        $total_discount=0;
      }
      $net_amount = ($post['total_amount']-$total_discount);
      if($post['pay']==1 || $post['data_id']!='')
      {
      $payamount=$post['pay_amount'];
      }else
      {
      $payamount=$net_amount;
      }
      $blance_dues=$net_amount -$payamount;
      $blance_due = number_format($blance_dues,2,'.','');
      $payamount = number_format($payamount,2,'.','');
      $pay_arr = array('total_amount'=>number_format($post['total_amount'],2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>$payamount,'balance_due'=>$blance_due,'discount'=>$post['discount'],'discount_amount'=>number_format($total_discount,2,'.',''));
      $json = json_encode($pay_arr,true);
      echo $json;die;
    }

    public function get_payment_mode_data()
    {
        $this->load->model('general/general_model'); 
        $payment_mode_id= $this->input->post('payment_mode_id');
        $error_field= $this->input->post('error_field');

        $get_payment_detail= $this->general_model->payment_mode_detail($payment_mode_id); 
        $html='';
        $var_form_error='';
        //print_r($get_payment_detail);die;
        foreach($get_payment_detail as $payment_detail)
        {

        if(!empty($error_field))
        {

        $var_form_error= $error_field; 
        }
        $html.='<div class="row"><div class="col-md-12 m-b1"><div class="row"><div class="col-md-4"><label>'.$payment_detail->field_name.'<span class="star">*</span></label></div> <div class="col-md-8"><input type="text" name="field_name[]" value="" onkeypress="payment_calc_all();"><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /></div><div class="f_right">'.$var_form_error.'</div></div></div></div>';
        }
        echo $html;exit;

    }

    


  public function print_pediatrician_prescription_recipt($ids="")
  {
     //print_r($ids);

      $user_detail= $this->session->userdata('auth_users');
      $this->load->model('general/general_model');

      if(!empty($ids))
      {
        $prescription_id= $ids;
        //print_r($ids);

      }
      else
      {
        $prescription_id= $this->session->userdata('book_vaccine_id');
       // print_r($ids);
      }

      $data['page_title'] = "Add";
      $get_detail_by_id= $this->prescription_model->get_by_id_prescription($prescription_id);

      $get_detail_by_detail= $this->prescription_model->get_by_id_print_prescription($prescription_id,$get_detail_by_id['branch_id']);
   
      $get_payment_detail=$this->prescription_model->get_prescription_payment_details_print($prescription_id);
      
        $get_payment_detail_by_id= $this->general_model->payment_mode_by_id('',$get_payment_detail['payment_mode']);
      
      $template_format= $this->prescription_model->template_format(array('section_id'=>11,'types'=>1,'sub_section'=>0,'branch_id'=>$get_detail_by_id['branch_id']));

      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['patient_detail']= $get_detail_by_id;
      $data['all_detail']= $get_detail_by_detail;

      $data['payment_mode_by_id']= $get_payment_detail_by_id;
      $data['payment_mode']= $get_payment_detail;
      //print_r($get_payment_detail);

      $this->load->view('pediatrician/pediatrician_vaccine_presciption_print_setting/print_template_prescription',$data);

  }

  public function history_vaccine_pres($booking_id="")
    {
       $data['page_title']="Vaccination History";
       $data['vaccine_history']= $this->prescription_model->get_vaccine_history($booking_id);
       $this->load->view('pediatrician/pediatrician_prescription/history_vaccine_pres',$data); 
    }
    

    function checkuserVaccine()
    {
       $data['uservaccinelist']= $this->prescription_model->getopdPatient();
       $DOB_B=array();
       $DOB_s=array();
       $nextdateofvaccine = date('d-m-Y', strtotime("+1 day"));
       $i=0;
       foreach($data['uservaccinelist'] as $usersVaccines)
       {
        if($usersVaccines->dob=="0000-00-00" || $usersVaccines->dob=="1970-01-01")
          {
           $DOB_B[$i]=$usersVaccines->dob;
          }
          else
          {
            $DOB_s[$i]['mobile_no']=$usersVaccines->mobile_no;
            $DOB_s[$i]['dob']=$usersVaccines->dob;
            $DOB_s[$i]['patient_name']=$usersVaccines->patient_name;
            $DOB_s[$i]['patient_email']=$usersVaccines->patient_email;
            $DOB_s[$i]['patient_id']=$usersVaccines->id;
          }
          $i++;

       }

      
      $patient_email='';
      $patient_name='';
      $mobile_no='';
      $date_new='';
      
      foreach($DOB_s as $birth_date)
      {
        $date_new= $birth_date['dob'];
        
        $vaccinerelatedage= $this->prescription_model->vaccinerelatedage();
        $i=0;
        $end_date_interval=array();
         foreach($vaccinerelatedage as $vaccine)
         {
            $j=0;
             $data[$i]['vaccine_name']=$vaccine['vaccination_name'];
             foreach($vaccine['age_list'] as $checkedagelist)
             {
              //////////checked vaccine used////////////
                $checkeduseralreadyusedvaccine= $this->prescription_model->checked_vaccine_already_user($checkedagelist->age_id,$checkedagelist->vaccination_id,$birth_date['patient_id']);

                //////////checked vaccine used////////////

                if($checkedagelist->start_age_type==1 && $checkedagelist->end_age_type==1)  //1 for days
                {
                    if($checkedagelist->start_age!='' && $checkedagelist->title!=0)
                    {

                    $end_date_interval[$j]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$checkedagelist->start_age.' day'));

                    }
                  else
                  {
                    $end_date_interval[$j]['start_age']='';  
                  }
                  if($checkedagelist->end_age!='' && $checkedagelist->title=='birth')
                  {

                    $end_date_interval[$j]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$checkedagelist->end_age.' day'));
                  }
                  else
                  {
                    $end_date_interval[$j]['end_age']='';
                  }

                }
                
             }
            print '<pre>';print_r($end_date_interval);die;
             $j++;

            $i++;
         }

        //print '<pre>';print_r($vaccinerelatedage);die;
          //$dateofbirthsaccage= $this->prescription_model->date_age_list($birth_date['dob']);


          
           // foreach($dateofbirthsaccage as $ages)
           // {
           //    if($ages['end_age']==$nextdateofvaccine)
           //    {
           //       if(!empty($birth_date['patient_email']))
           //       {
           //        $patient_email= $birth_date['patient_email'];
           //       }
           //       if(!empty($birth_date['patient_name']))
           //       {
           //        $patient_name= $birth_date['patient_name'];
           //       }
           //       if(!empty($birth_date['mobile_no']))
           //       {
           //        $mobile_no= $birth_date['mobile_no'];
           //       }
           //      if(!empty($mobile_no))
           //          {
           //            send_sms('pedic_vaccine_pres',27,$patient_name,$mobile_no,array('{patient_name}'=>$patient_name,'{vaccination Date}'=>$vaccination_date)); 
           //          }
           //    }
           // }
      }
    }
// Please write code above this
}
?>

?>
