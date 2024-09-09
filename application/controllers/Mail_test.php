<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_test extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('department_reports/reports_model','reports');
    }
    
   public function index()
    {   
        $start_date ='';
        $end_date = '';
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
        $data['page_title'] = 'Pathology Report List';
        $this->load->model('general/general_model');
        $data['dept_list'] = $this->general_model->active_department_list(5);
        $data['employee_list'] = $this->general_model->branch_user_list(); 
        $this->load->view('department_reports/list',$data);
    }
    public function ajax_list()
    {  
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->reports->get_datatables();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $total_amount = '0.00';
        $total_discount = '0.00';
        $total_net_amount = '0.00';
        $total_paid_amount = '0.00';
        $total_balance = '0.00';
        foreach ($list as $reports) {
         // print_r($reports);die;
            ////////// Check  List /////////////////
               $check_script = "";
               if($i==$total_num){
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
            $no++;
            $row = array(); 
            //$row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$reports->id.'">'.$check_script; 
            $total_amount += $reports->total_amount;
            $total_discount += $reports->discount;
            $total_net_amount += $reports->net_amount;
            $total_paid_amount += $reports->paid_amount;
            $total_balance += $reports->balance;

            $row[] = $reports->lab_reg_no;  
            $row[] = date('d-m-Y',strtotime($reports->booking_date));  
            $row[] = $reports->patient_name;  
            $row[] = $reports->doctor_hospital_name;   
            $row[] = $reports->department;  
            $row[] = $reports->total_amount;  
            $row[] = $reports->discount;  
            $row[] = $reports->net_amount;  
            $row[] = $reports->paid_amount;  
            $row[] = $reports->balance;  
            $btn_edit='';
            $btn_print='';
                    if(in_array('889',$users_data['permission']['action'])){
                         $btn_edit = ' <a class="btn-custom" href="'.base_url("test/edit_booking/".$reports->id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
                    }

                    
                    if($reports->print_type==0) 
                    {
                      $url = base_url('balance_clearance/print_patient_balance_receipt/').$reports->payment_id.'/'.$reports->id.'/undefine/1';
                    }
                    else
                    {
                      $url = base_url('test/print_test_booking_report/').$reports->id;
                    }
                    
                    $url = "'".$url."'";
                    $btn_print = ' <a class="btn-custom" href="javascript:void(0);" onclick="return print_window_page('.$url.');" title="Print"><i class="fa fa-print"></i> Print</a>';
                    $btn_delete = '';  
            $row[] = $btn_edit.$btn_print.$btn_delete;         
            $data[] = $row;
            $tot_row = [];
            if($i==$total_num)
            {  
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '<b>Total </b>';  
              $tot_row[] = '<input type="text" class="w-100px" value="'.number_format($total_amount,2).'" />';  
              $tot_row[] = '<input type="text" class="w-100px" value="'.number_format($total_discount,2).'" />';  
              $tot_row[] = '<input type="text" class="w-100px" value="'.number_format($total_net_amount,2).'" />';  
              $tot_row[] = '<input type="text" class="w-100px" value="'.number_format($total_paid_amount,2).'" />';  
              $tot_row[] = '<input type="text" class="w-100px" value="'.number_format($total_balance,2).'" />';
              $tot_row[] = '';
              $data[] = $tot_row;
            }

            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->reports->count_all(),
                        "recordsFiltered" => $this->reports->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function search_data()
    {
       $post = $this->input->post();
       if(!empty($post))
       {
              $users_data = $this->session->userdata('auth_users'); 
              $branch_id = $users_data['parent_id'];

              if(isset($post['branch_id']) && !empty($post['branch_id']))
              {
              $branch_id = $post['branch_id'];
              }
              $search_data =  array(
                                   'branch_id'=>$branch_id,
                                   'start_date'=>$post['start_date'],
                                   'referral_doctor'=>'',
                                   'dept_id'=>'',
                                   'patient_code'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>$post['end_date'],
                                   'attended_doctor'=>'',
                                   'profile_id'=>'',
                                   'sample_collected_by'=>'',
                                   'staff_refrenace_id'=>'',
                                   'employee'=>$post['employee'],
                                   'department'=>$post['department'],
                                 );
         $this->session->set_userdata('search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('search_data');
    }

    public function index_old()
    { 
     $msg='<html>
<head>
        <title></title>
</head>
<body>
<div style="max-width:650px;padding:1em;margin:20px auto;">
<table align="center" border="0" cellpadding="0" cellspacing="0" >
        <tbody>
                <tr>
                        <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0"
style="font-family:sans-serif, Arial;">
                                <tbody>
                                        <tr>
                                                <td style="padding:0 3%">
                                                <table>
                                                        <tbody>
                                                                <tr>
                                                                        <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                        <tr>
                                                                                                <td style="padding:0;" valign="top">
                                                                                                <h3>Hi Ankit Sharma1,</h3>
                                                                                                </td>
                                                                                                <td align="right" valign="top">&nbsp;</td>
                                                                                        </tr>
                                                                                </tbody>
                                                                        </table>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                        <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                        <tr>
                                                                                                <td style="font-family:sans-serif, Arial;padding:0;"
valign="top"><strong style="font-family:sans-serif, Arial;margin:0;">Welcome
to <b>Sara Technology Pvt. Ltd.</b></strong></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td style="font-family:sans-serif, Arial;padding:0; font-size:
14px;" valign="top"><br />
                                                                                                Please use following credentials:<br />
                                                                                                <b>Username : </b> PAT000303056<br />
                                                                                                <b>Password : </b> PASS303056</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td style="font-family:sans-serif, Arial;padding:0;"
valign="top"><br />
                                                                                                You&#39;re now one step closer to being Sara Technology Pvt.
Ltd. Don&#39;t forget to use your free trial, we know you&#39;ll love
it!</td>
                                                                                        </tr>
                                                                                </tbody>
                                                                        </table>
                                                                        </td>
                                                                </tr>
                                                        </tbody>
                                                </table>
                                                </td>
                                        </tr>
                                </tbody>
                        </table>
                        </td>
                </tr>
                <tr>
                        <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0"
style="font-family:sans-serif, Arial;font-size:12px;margin-top:2%"
width="100%">
                                <tbody>
                                        <tr>
                                                <td style="text-align: center;" valign="top">&copy; 2017 Sara
Technology Pvt. Ltd.</td>
                                        </tr>
                                </tbody>
                        </table>
                        </td>
                </tr>
        </tbody>
</table>
</div>
</body>
</html>';
                    $this->load->library('email');
					$this->load->library('form_validation');
					$config['protocol']    = 'smtp';
					$config['smtp_host'] = 'mail.ayurhomecare.in';//'ssl://smtp.gmail.com';
					$config['smtp_port'] = '2525';//'465';
					$config['smtp_user'] = 'info@ayurhomecare.in';//'arvind.kumar@sarasolutions.in';
					$config['smtp_pass'] = 'Ayurveda@home20';//'arvind#1988';
					$config['charset']    = 'utf-8';
					$config['newline']    = "\r\n";
					$config['mailtype'] = 'html';  
					$config['validation'] = TRUE; 
					$this->email->initialize($config);  
					$this->email->from('info@ayurhomecare.in', 'HMAS');
					$this->email->to('arvind.kumar@sarasolutions.in');
					$this->email->subject('thi is a test'); 
					
					$this->email->message($msg);
				$ms = 	$this->email->send();  
					echo "<pre>"; print_r($ms);
					echo $this->email->print_debugger();die;
      
    }
    
}