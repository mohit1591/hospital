<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_payment extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('vendor_payment/vendor_payment_model','vendor_payment');
		$this->load->library('form_validation');
    }

    
	public function index()
  {
      unauthorise_permission(99,629);
      $data['page_title']='Vendor Payment';
      $post = $this->input->post();
      $data['branch_list'] = $this->session->userdata('sub_branches_data');
      //print_r($data['branch_list']);die;
      $this->session->unset_userdata('type'); 
      $this->load->view('vendor_payment/list',$data);     
  }

  public function balance_list()
  {
    unauthorise_permission(99,629);
    $users_data = $this->session->userdata('auth_users');
    $post = $this->input->post();
    $type=2;
    if(isset($post['type']))
    {
      $type = $post['type'];
    }
   
    $record_list = $this->vendor_payment->vendor_to_balclearlist($type);
    $row_data = ''; 
    if(!empty($record_list))
    {
       $i=1;
       $total_balannce=0;
       foreach($record_list as $details)
       {
        if($details['balance']>0 or $details['balance']<0){
           $balance = "'".$details['balance']."'";
           $row_data.='<tr>
                        <td>'.$i.'</td>
                        <td>'.$details['name'].'</td>
                        <td>'.$details['code'].'</td>
                        <td>'.$details['balance'].'</td>';
          /*if(in_array('232',$users_data['permission']['action'])){
               $row_data.= '<td><button type="button" class="btn-custom" name="pay_now" 
                              id="pay_now" onclick="pay_now_to_branch('.$details['id'].','.$balance.','.$type.', '.$details['branch_id'].');">Pay Now</button>
                           </td>.';
          };*/
          
           if(in_array('232',$users_data['permission']['action'])){
               $row_data.= '<td>
                             <a href='.base_url('vendor_payment/check_history/').$details['id'].' class="btn-custom" name="view_purchase" id="view_purchase">View Purchase </a>
                <a href='.base_url('vendor_payment/view_history/').$details['id'].' class="btn-custom" name="pay_view" id="pay_view">Payment View </a>
                <a href="javascript:void(0);" class="btn-custom" onclick="return show_ledger_report('.$details['id'].')">Ledger Report </a>
              </td>.';
          }
          $row_data.='</tr>';
          $total_balannce=$total_balannce+$details['balance'];
                    $i++;
        }
        
      
       
                    
       }
       
        if($total_balannce=='0')
        {
          $row_data = '<tr><td colspan="5" class="text-center">No Record Found</td></tr>';
        }
    }
    else
    {
      $row_data = '<tr><td colspan="5" class="text-center">No Record Found</td></tr>';
    }
    echo $row_data;
  }
    
    public function pay_now($id="",$type="",$branch_id="")
  {

          unauthorise_permission(99,630);
          //$this->session->unset_userdata('balance');
          $this->load->model('general/general_model'); 
          $users_data = $this->session->userdata('auth_users');
          $data['page_title'] = 'Vendor Payment'; 
          $post = $this->input->post(); 
       //   print_r($post);die();
          $balance = '';
          $payment_id='';
          $expense_id='';
          $parent_id='';
          $section_id='';
          if(isset($post['bal']) && !empty($post['bal']))
          {
          $balance = $post['bal'];
          $this->session->set_userdata('balance',$post['bal']);
          }
          if(isset($post['expense_id']) && !empty($post['expense_id']))
          {
          $expense_id = $post['expense_id'];
          }
          if(isset($post['payment_id']) && !empty($post['payment_id']))
          {
          $payment_id = $post['payment_id'];
          }
          if(isset($post['section_id']) && !empty($post['section_id']))
          {
           $section_id = $post['section_id'];
          }
          if(isset($post['parent_id']) && !empty($post['parent_id']))
          {
           $parent_id = $post['parent_id'];
          }
          $data['payment_mode']=$this->general_model->payment_mode();
          $data['form_error'] = [];
          $data['form_data'] = array(
                   //  'id'=>$id,
                    'data_id'=>$id,
                    'paid_date'=>date('d-m-Y'),
                    'payment_mode'=>1,
                    'field_name'=>'',
                    'balance'=>$balance,
                    'expense_id'=>$expense_id,
                    'payment_id'=>$payment_id,
                    'section_id'=>$section_id,
                    'parent_id'=>$parent_id
                 ); 
       //echo "<pre>"; print_r($post); exit;
          if(isset($post) && !empty($post) && !isset($post['bal']))
          {
              $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                //echo 'dasdsa';die;
                $pay_id = $this->vendor_payment->payment_to_branch($id,$type,$branch_id);
                if(!empty($pay_id))
                {
                    $get_by_id_data = $this->vendor_payment->get_by_id($id);
                    //print_r($get_by_id_data);
                    $vendor_name = $get_by_id_data['name'];
                    $mobile_no = $get_by_id_data['mobile'];
                    $email = $get_by_id_data['email'];

                  //check permission
                  if(in_array('640',$users_data['permission']['action']))
                  {

                    if(!empty($mobile_no))
                    {
                      send_sms('vender_payment',9,$vendor_name,$mobile_no,array('{Name}'=>$vendor_name,'{Amt}'=>$post['balance'])); 
                    }
                  }

                  if(in_array('641',$users_data['permission']['action']))
                  {
                  if(!empty($email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($email,'','','','','1','vender_payment','9',array('{Name}'=>$vendor_name,'{Amt}'=>$post['balance']));
                     
                  }
                  }

                  
                }
                echo $pay_id;
                return false;                
              }
              else
              {
                  $data['form_error'] = validation_errors();  
              }  
          } 
          $this->load->view('vendor_payment/pay_vendor_to_branch',$data);

    }
  public function pay_now20210529($id="",$type="",$branch_id="")
  {
          unauthorise_permission(99,630);
          //$this->session->unset_userdata('balance');
          $this->load->model('general/general_model'); 
          $users_data = $this->session->userdata('auth_users');
          $data['page_title'] = 'Vendor Payment'; 
          $post = $this->input->post(); 
          $balance = '';
          if(isset($post['bal']) && !empty($post['bal']))
          {
          $balance = $post['bal'];
          $this->session->set_userdata('balance',$post['bal']);
          }
          $data['payment_mode']=$this->general_model->payment_mode();
          $data['form_error'] = [];
          $data['form_data'] = array(
                    'data_id'=>$id,
                    'paid_date'=>date('d-m-Y'),
                    'payment_mode'=>1,
                    //'bank_name'=>'',
                    'field_name'=>'',
                   // 'transection_no'=>'',
                   // 'cheque_no'=>'',
                   // 'cheque_date'=>'',
                    //'card_no'=>'',
                    'balance'=>$balance
                 ); 
       //echo "<pre>"; print_r($post); exit;
          if(isset($post) && !empty($post) && !isset($post['bal']))
          {
              $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                //echo 'dasdsa';die;
                $pay_id = $this->vendor_payment->payment_to_branch($id,$type,$branch_id);
                if(!empty($pay_id))
                {
                    $get_by_id_data = $this->vendor_payment->get_by_id($id);
                    //print_r($get_by_id_data);
                    $vendor_name = $get_by_id_data['name'];
                    $mobile_no = $get_by_id_data['mobile'];
                    $email = $get_by_id_data['email'];

                  //check permission
                  if(in_array('640',$users_data['permission']['action']))
                  {

                    if(!empty($mobile_no))
                    {
                      send_sms('vender_payment',9,$vendor_name,$mobile_no,array('{Name}'=>$vendor_name,'{Amt}'=>$post['balance'])); 
                    }
                  }

                  if(in_array('641',$users_data['permission']['action']))
                  {
                  if(!empty($email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($email,'','','','','1','vender_payment','9',array('{Name}'=>$vendor_name,'{Amt}'=>$post['balance']));
                     
                  }
                  }

                  
                }
                echo $pay_id;
                return false;                
              }
              else
              {
                  $data['form_error'] = validation_errors();  
              }  
          } 
          $this->load->view('vendor_payment/pay_vendor_to_branch',$data);

    }

   private function _validate()
    {
        $post = $this->input->post();  
        $total_values=array(); 

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
       
        

        /*if($post['payment_mode']==2)
        {
           $this->form_validation->set_rules('card_no', 'card no.', 'trim|required|numeric');
        }
        if($post['payment_mode']==4)
        {
          $this->form_validation->set_rules('transection_no', 'transection no.', 'trim|required'); 
        }
        if($post['payment_mode']==3)
        {
          $this->form_validation->set_rules('bank_name', 'bank name', 'trim|required'); 
          $this->form_validation->set_rules('cheque_no', 'cheque no.', 'trim|required|numeric'); 
          $this->form_validation->set_rules('cheque_date', 'cheque date', 'trim|required'); 
        }*/ 
         
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'balance'=>$post['balance'],
                                        'paid_date'=>$post['paid_date'],
                                        'payment_mode'=>$post['payment_mode'],
                                        'field_name'=> $total_values
                                        //'bank_name'=>$post['bank_name'],
                                       // 'transection_no'=>$post['transection_no'],
                                        //'cheque_no'=>$post['cheque_no'],
                                        //'cheque_date'=>$post['cheque_date'],
                                        //'card_no'=>$post['card_no'],
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
      echo $this->load->view('vendor_payment/drop_down_data',$data,true);
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
    public function print_patient_balance_receipt($id="",$type="")
    {
        $user_detail= $this->session->userdata('auth_users');
        $data['page_title'] = "Print Vendor Payment";
        $test_booking_id= $id;
        $get_by_id_data = $this->vendor_payment->patient_balance_receipt_data($id,$type);
        //print '<pre>'; print_r($get_by_id_data);die;
        $template_format = $this->vendor_payment->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>1));
        $data['type']=$type;
        $data['user_detail']=$user_detail;
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        $data['balance']=$this->session->userdata('balance');
        $this->load->view('vendor_payment/print_vendor_payment_report',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(56,367);
        $list = $this->medicine_entry->get_datatables();  
        //print_r( $list);
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $medicine_entry) { 
            $no++;
            $row = array();
            if($medicine_entry->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($medicine_entry->state))
            {
                $state = " ( ".ucfirst(strtolower($medicine_entry->state))." )";
            }
            //////////////////////// 
            
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
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="medicine_entry[]" class="checklist" value="'.$medicine_entry->id.'">'.$check_script;
            $rack_name= rack_list($medicine_entry->rack_no);
            $row[] = $medicine_entry->medicine_name;
            $row[] = $medicine_entry->packing;
            $row[] = $medicine_entry->rack_no;
            $row[] = $medicine_entry->mrp;
            $row[] = $medicine_entry->purchase_rate;
            $row[] = $medicine_entry->discount;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($medicine_entry->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('369',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_medicine_entry('.$medicine_entry->id.');" class="btn-custom" href="javascript:void(0)" style="'.$medicine_entry->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if(in_array('374',$users_data['permission']['action'])){
                $btnview=' <a class="btn-custom" onclick="return view_medicine_entry('.$medicine_entry->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
           }
            if(in_array('370',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_medicine_entry('.$medicine_entry->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }       
            $row[] = $btnedit.$btnview.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_entry->count_all(),
                        "recordsFiltered" => $this->medicine_entry->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

     public function vendor_payment()
    {     $data['page_title']='Vendor Payment';
          $post = $this->input->post();
          
          if(isset($post) && !empty($post))
          {
             $data['vendor_to_balclearlist'] = $this->billing->vendor_to_balclearlist();
          }
          $this->load->view('vendor_payment/vendor_payment',$data);
    }

	public function add()
	{
         unauthorise_permission(56,368);
		$this->load->model('general/general_model'); 
		$data['page_title'] = "Add medicine";
		$data['form_error'] = [];
		$data['unit_list'] = $this->medicine_entry->unit_list();
		$data['rack_list'] = $this->medicine_entry->rack_list();
		$data['manuf_company_list'] = $this->medicine_entry->manuf_company_list(); 
		$reg_no = generate_unique_id(10);
        //echo $reg_no;die;
		$post = $this->input->post();
		$data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "medicine_code"=>$reg_no,
                                    "medicine_name"=>"",
                                    "unit_id"=>"",
                                    "unit_second_id"=>"",
                                    "conversion"=>"",
                                    "min_alrt"=>"",
                                    "packing"=>"",
                                    "rack_no"=>"",
                                    "salt"=>"",
                                    "manuf_company"=>"",
                                    "mrp"=>"",
                                    "purchase_rate"=>"",
                                    "discount"=>"",
                                    "vat"=>"",
                                    "status"=>"1", 
			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_entry->save();
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        $this->session->unset_userdata('comission_data');
		$this->load->view('medicine_entry/add',$data);
	}

	public function edit($id="")
    {
         unauthorise_permission(56,369);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->medicine_entry->get_by_id($id); 
        //print_r($result);die;
        $reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
    	$data['unit_list'] = $this->medicine_entry->unit_list();
		$data['rack_list'] = $this->medicine_entry->rack_list();
		$data['manuf_company_list'] = $this->medicine_entry->manuf_company_list();
        $data['page_title'] = "Update medicine entry";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "medicine_code"=>$reg_no,
                                    "medicine_name"=>$result['medicine_name'],
                                    "unit_id"=>$result['unit_id'],
                                    "unit_second_id"=>$result['unit_second_id'],
                                    "conversion"=>$result['conversion'],
                                    "min_alrt"=>$result['min_alrt'],
                                    "packing"=>$result['packing'],
                                    "rack_no"=>$result['rack_no'],
                                    "salt"=>$result['salt'],
                                    "manuf_company"=>$result['manuf_company'],
                                    "mrp"=>$result['mrp'],
                                    "purchase_rate"=>$result['purchase_rate'],
                                    "vat"=>$result['vat'],
                                    "discount"=>$result['discount'],
                                    "status"=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_entry->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->session->unset_userdata('comission_data'); 
       $this->load->view('medicine_entry/add',$data);       
      }
    }
     
   
    public function delete($id="")
    {
        unauthorise_permission(56,370);
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_entry->delete($id);
           $response = "Medicine entry successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(56,370);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_entry->deleteall($post['row_id']);
            $response = "Medicine entry successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        unauthorise_permission(56,374);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_name']." detail";
        $this->load->view('medicine_entry/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(56,371);
        $data['page_title'] = 'Medicine entry archive list';
        $this->load->helper('url');
        $this->load->view('medicine_entry/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(56,371);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive'); 

        $list = $this->medicine_entry_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine_entry) { 
            $no++;
            $row = array();
            if($medicine_entry->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
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

            $row[] = '<input type="checkbox" name="medicine_entry[]" class="checklist" value="'.$medicine_entry->id.'">'.$check_script;
            $rack_name= rack_list($medicine_entry->rack_no);
            $row[] = $medicine_entry->medicine_name;
            $row[] = $medicine_entry->packing;
            $row[] = $medicine_entry->rack_no;
            $row[] = $medicine_entry->mrp;
            $row[] = $medicine_entry->purchase_rate;
            $row[] = $medicine_entry->discount;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($medicine_entry->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           if(in_array('373',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_medicine_entry('.$medicine_entry->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('372',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$medicine_entry->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_entry_archive->count_all(),
                        "recordsFiltered" => $this->medicine_entry_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(56,373);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_entry_archive->restore($id);
           $response = "Medicine entry successfully restore in medicine entry list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(56,373);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_entry_archive->restoreall($post['row_id']);
            $response = "Medicine entry successfully restore in medicine entry list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(56,372);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_entry_archive->trash($id);
           $response = "Medicine entry successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(56,372);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_entry->trashall($post['row_id']);
            $response = "Medicine entry successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function medicine_entry_dropdown()
  {
      $medicine_entry_list = $this->medicine_entry->employee_type_list();
      $dropdown = '<option value="">Select Medicine Entry</option>'; 
      if(!empty($medicine_entry_list))
      {
        foreach($medicine_entry_list as $medicine_entry)
        {
           $dropdown .= '<option value="'.$medicine_entry->id.'">'.$medicine_entry->medicine_name.'</option>';
        }
      } 
      echo $dropdown; 
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
  
    public function ledger_show($id='')
    {
        $data['page_title'] = "Ledger Report Print";  
        $data['vendor_id'] = $id;  
       $this->load->view('vendor_payment/ledger_popup',$data);       
    }
    
    public function ledger_print()
    {
        $get=$this->input->get();
        $datas= $this->vendor_payment->get_ledger_history($get);
        //echo '<pre>'; print_r($datas);die();
        $data['report_list'] = $datas['purchase_list'];
        $data['return_report_list'] = $datas['return_list'];
        $data['get'] = $get;
        $data['page_title'] ="Vendor Ledger Reports"; 
        $this->load->view('vendor_payment/ledger_reports_list',$data); 
    }
    
      public function check_history($id='')
      {
           unauthorise_permission(56,374);
         if(isset($id) && !empty($id) && is_numeric($id))
          {
            $data['vendor_id'] = $id;
            $data['page_title'] = "Vendor Purchase History";
            $this->load->view('vendor_payment/purchase_history_view',$data);       
          }
      }
    
      public function view_purchase_history($id='')
      {
           unauthorise_permission(56,374);
           $post = $this->input->post();
          // echo "<pre>"; print_r($post); exit;
          $module = $post['module'];
         if(isset($id) && !empty($id) && is_numeric($id))
          { 
            $data['vendor_id'] = $id;
            $list = $this->vendor_payment->get_purchase_history_by_id($id);  
            $html='';
              if(!empty($list)){ $i=0;
                      foreach ($list as $key => $detail) { $i++;
                        if($detail['totbalance']<=0)
                        {
                          $blns='';
                        }
                        else
                        { //$detail['type']
                          $blns='<button type="button" class="btn-custom" name="pay_now" 
                                  id="pay_now" onclick="pay_now_to_branch('.$detail['vendor_id'].','.$detail['totbalance'].','.$module.','.$detail['branch_id'].','.$detail['section_id'].','.$detail['parent_id'].');">Pay Now</button>';
                        }
                       $html.='<tr>
                          <td>'.$i.'</td>
                          <td>'.$detail['name'].'</td> 
                          <td>'.date('d-m-Y',strtotime($detail['created_date'])).'</td> 
                         <td>'.$detail['totbalance'].'</td> 
                          <td>'.$blns.'
                              <a href="'.base_url('vendor_payment/view_history/').$detail['vendor_id'].'/'.$detail['parent_id'].'" class="btn-custom" name="pay_view" id="pay_view">Payment View </a>
                          </td>
                        </tr>';
                     }}
                     else{
                       $html.='<tr><td colspan="5">No Record found</td></tr>';
                     }
                     echo $html;
                     exit();      
          }
      }
    
    
      public function view_history($id='',$parent_id='')
      {
           unauthorise_permission(56,374);
         if(isset($id) && !empty($id) && is_numeric($id))
          { 
            $data['type'] = $type;
            $data['list'] = $this->vendor_payment->get_history_by_id($id,$parent_id);  
            $data['page_title'] = "Vendor Payment History";
            $this->load->view('vendor_payment/history_view',$data);       
          }
      }


}
