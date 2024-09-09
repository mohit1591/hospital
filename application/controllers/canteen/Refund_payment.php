<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund_payment extends CI_Controller {
 
  function __construct() 
    {
    parent::__construct();  
    auth_users();  
    $this->load->model('canteen/refund_payment/Refund_payment_type_model','refund_payment');
    $this->load->library('form_validation');
    }

    
  public function index()
  { 
      unauthorise_permission(190,1118);
      $data['page_title']='Refund Payment';
      $post = $this->input->post();
      $this->session->unset_userdata('refund_search_data');
      $data['branch_list'] = $this->session->userdata('sub_branches_data');
      $data['section_id'] = 2;
      $this->session->unset_userdata('type'); 
      $this->load->view('canteen/refund_payment/list',$data);     
  }

 public function ajax_list()
 { 
        unauthorise_permission(190,1118);
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->refund_payment->get_datatables();
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;

        $total_num = count($list);

        foreach ($list as $details) 
        {
         
            $no++;
            $row = array();
           
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
           
            ////////// Check list end ///////////// 
            $checkboxs = "";
            if($users_data['parent_id']==$details->branch_id)
            {
               $row[] = $i;
            }else{
               $row[]='';
            }
         
            $row[] = $details->patient_name;
            $row[] = $details->mobile_no;
            $row[] = $details->department;
            $row[] = $details->patient_code;
        
           // $row[] = $status;
            $row[] = date('d-M-Y',strtotime($details->created_date)); 
            $btnrefund='';
            $btnview='';
            
          
            if($users_data['parent_id']==$details->branch_id)
            {
              if(in_array('1119',$users_data['permission']['action'])){
               $btnrefund =' <a class="btn-custom" href="'.base_url('canteen/refund_payment/pay_now/'.$details->id.'/'.$details->parent_id.'/'.$details->section_id.'/'.$details->branch_id).'" style="'.$details->id.'">Refund</a>';
              }
              if(in_array('1118',$users_data['permission']['action'])){
               $btnedit =' <a class="btn-custom" href="'.base_url('canteen/refund_payment/view_refund_details/'.$details->id.'/'.$details->parent_id.'/'.$details->section_id.'/'.$details->branch_id).'" style="'.$details->id.'">View</a>';
              }

            }
          
             $row[] = $btnrefund.$btnedit;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->refund_payment->count_all(),
                        "recordsFiltered" => $this->refund_payment->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

   public function azax_refund_list()
    {
       //echo "hi";die;
        unauthorise_permission(190,1118);
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->refund_payment->get_datatables_for_refund_list();
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
         $i=0;
        foreach ($list as $details) 
        {
         
            $no++;
            $row = array();
          
           if($details['section_id']==1)
            {
                $status = 'Pathology';
            }   
            elseif ($details['section_id']==2){
                $status = 'OPD';
            }
            elseif ($details['section_id']==4){
                $status = 'OPD Billing';
            } 
            elseif ($details['section_id']==8){
                $status = 'OT';
            }
            elseif ($details['section_id']==10){
                   $status = 'Blood Bank';
            }
            elseif ($details['section_id']==13){
                   $status = 'Ambulance';
            }
            else
            {
              $status='Medicine';
            }
           
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
           
            ////////// Check list end ///////////// 
            $checkboxs = "";
            if($users_data['parent_id']==$details['branch_id'])
            {
               $row[] = $i+1;
            }else{
               $row[]='';
            }
         
            $row[] = $details['patient_name'];
            $row[] = $status;
            $row[] = $details['refund_amount'];
            $row[] = date('d-m-Y',strtotime($details['refund_date']));
        
           // $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($details['created_date'])); 
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->refund_payment->count_all(),
                        "recordsFiltered" => $this->refund_payment->count_filtered_for_refund(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

  public function refund_list()
  {
        $data['page_title']='Refund Payment';

        $this->load->view('canteen/refund_payment/refund_list',$data);
  }

  public function save_refund_details($id="",$section_id="",$parent_id="",$branch_id)
  {

        $data['page_title']='Refund Payment';
        $post=$this->input->post();
        if(isset($post) && !empty($post) && !empty($post['refund_amount']))
        {
              $insert_array=array(
              'patient_id'=>$post['patient_id'],
              'section_id'=>$post['section_id'],
              'parent_id'=>$post['parent_id'],
              'branch_id'=>$post['branch_id'],
              'doctor_id'=>"",
              'refund_amount'=>$post['refund_amount'],
              'refund_date'=> date("Y-m-d", strtotime($post['refund_date']) ),
              'created_date'=>date('Y-m-d H:i:s'),
              'status'=>"",
              'type'=>5,
              'created_by'=>date('Y-m-d H:i:s')

                                  
            );
             //print_r($insert_array);die;
                $pid=$this->refund_payment->insert_refund_data($insert_array);
                if(!empty($pid))
                {

                 $this->session->set_flashdata('success','Refund Payment successfully added.');
               //echo $this->session->flashdata('success');die;
                redirect('canteen/refund_payment/refund_list');
                }
                   
      }
     $this->load->view('canteen/refund_payment/refund_list',$data);
  }

 

 
  public function pay_now($pid="", $parent_id="",$section_id="",$branch_id="")
  {
         //echo $pid;die;
         // unauthorise_permission(190,1118);
          $this->load->model('general/general_model'); 
          $this->load->model('blood_bank/recipient/Recipient_model','recipient'); 
          $users_data = $this->session->userdata('auth_users');
          $this->load->model('opd/opd_model','opd');
          $this->load->model('ambulance/booking_model','ambulance_booking');
          $this->load->model('test/test_model','path_test_booking');
          $this->load->model('patient/patient_model','patient');
          $this->load->model('opd_billing/opd_billing_model','opdbilling');
          $this->load->model('sales_medicine/sales_medicine_model','sales_medicine');
          $this->load->model('ipd_booking/ipd_booking_model','ipdbooking');
          $this->load->model('ot_booking/ot_booking_model','otbooking');
          $this->load->model('opd_billing/opd_billing_model','opdbilling');
          $data['page_title'] = 'Refund Amount';  
          $post = $this->input->post(); 
          $data['booking_data']= $this->opd->get_by_id($parent_id);
          $data['booking_details_for_patient']=$this->refund_payment->get_booking_details_for_patient($pid);
          $data['patient_data']=$this->patient->get_by_id($pid);
          $data['pathology_booking_data']= $this->path_test_booking->get_by_id($parent_id);
         //print_r($data['pathology_booking_data']);die;
          $data['medicine_booking_data']=$this->sales_medicine->get_by_id($parent_id);
          $data['ot_booking_data']=$this->otbooking->get_by_id($parent_id);
          $data['receipent_booking_data']=$this->recipient->get_by_id($pid);
          //echo $pid; exit;
          $data['billing_data']=$this->opdbilling->get_by_id($parent_id);
          $data['ambulance_data']=$this->ambulance_booking->get_by_id($parent_id);
         //print_r($data['billing_data']);die;
          $balance = '';
          if(isset($post['bal']) && !empty($post['bal']))
          {
            $balance = $post['bal'];
            $this->session->set_userdata('balance',$post['bal']);
          }

          $data['payment_mode']=$this->general_model->payment_mode();
          $data['form_error'] = [];
          $data['form_data'] = array(
                    'pid'=>$pid,
                    'parent_id'=>$parent_id,
                    'section_id'=>$section_id,
                    'branch_id'=>$branch_id,
                    'field_name'=>'',
                    'paid_date'=>date('d-m-Y'),
                   // 'paid_time'=>date('H:i:s'),
                    'payment_mode'=>1,
                   
                  
                    'balance'=>$balance
                 ); 
       
         
        //  print_r($data);die;
          $this->load->view('canteen/refund_payment/pay_patient_to_branch',$data);

    }

    private function _validate()
    {
        $post = $this->input->post();   
        if(isset($post['field_name']))
        {
          $count_field_names= count($post['field_name']);  

          $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
         $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        }
        }  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('balance', 'balance', 'trim|required|numeric'); 
         $this->form_validation->set_rules('paid_date', 'paid date', 'trim|required'); 
        $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required'); 
         if(isset($post['field_name']))
        {
           $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
       
        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                        'pid'=>$post['patient_id'],
                                        'section_id'=>$post['section_id'],
                                        'parent_id'=>$post['parent_id'],
                                        'branch_id'=>$post['branch_id'],
                                        'balance'=>$post['balance'],
                                        'paid_date'=>$post['paid_date'],
                                        'field_name'=> $total_values,
                                        'payment_mode'=>$post['payment_mode']
                                        
                                       ); 
            return $data['form_data'];
        }   
    }

   public function get_drop_down_value()
   {
      //$this->session->unset_userdata('type',$type);
      $type= $this->input->post('type'); 
      $data['type']= $type;
      //$this->session->set_userdata('type',$type);
      echo $this->load->view('canteen/refund_payment/drop_down_data',$data,true);
   }
    public function get_allsub_branch_list()
    {
      //print_r($this->session->userdata());die;
      $sub_branch_details = $this->session->userdata('sub_branches_data');

      $parent_branch_details = $this->session->userdata('parent_branches_data');
       $users_data = $this->session->userdata('auth_users');
      // if($users_data['users_role']==2){
              $dropdown = '';
             if(!empty($sub_branch_details)){
                 $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name ="sub_branch_id" onchange="" ><option value="">Select Sub Branch</option>';
               
                   $i=0;
                   foreach($sub_branch_details as $key=>$value){
                       $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                       $i = $i+1;
                  }
                  $dropdown.='</select>';
             }
             
             echo $dropdown; 
      // }
       
     
    }
    public function print_patient_balance_receipt($id="",$patient_id="",$payment_date='',$section_id="")
    {}

    public function view_refund_details($pid="", $parent_id="",$section_id="",$branch_id="")
    {  
         //echo $pid.$parent_id.$section_id.$branch_id;die;
         $data['page_title']='Refund Payment';
         $data['refund_data']=$this->refund_payment->get_refund_data_of_patient($pid);
    //print_r($data['refund_data']);die;
          $this->load->view('canteen/refund_payment/view_refund_details',$data);

    }  


     public function balance_clearance()
    {     $data['page_title']='Balance Clearance';
          $post = $this->input->post();
          
          if(isset($post) && !empty($post))
          {
             $data['patient_to_balclearlist'] = $this->billing->patient_to_balclearlist();
          }
          $this->load->view('balance_clearance/balance_clearance',$data);
    }


  public function search_data()
  {
      $post = $this->input->post(); 
    // print_r($post);die;
      $this->session->set_userdata('refund_search_data',$post);
  }

  public function get_payment_mode_data()
  {
    $this->load->model('general/general_model'); 
    $payment_mode_id= $this->input->post('payment_mode_id');
    $error_field= $this->input->post('error_field');

    $get_payment_detail= $this->general_model->payment_mode_detail($payment_mode_id); 
    $html='';
    $var_form_error='';
    foreach($get_payment_detail as $payment_detail)
    {

    if(!empty($error_field))
    {

    $var_form_error= $error_field; 
    }

    $html.='<div class="row m-b-5"><div class="col-md-5"><label>'.$payment_detail->field_name.'<label class="star">*</span></div> <div class="col-md-7"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>';
    }
    echo $html;exit;

  }


}
