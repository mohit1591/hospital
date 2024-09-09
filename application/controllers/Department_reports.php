<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department_reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('department_reports/reports_model','reports');
        $this->load->model('general/general_model');
        
    }
    
    public function index()
    {   
        $start_date = date('d-m-Y');
        $end_date = date('d-m-Y');
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
        $data['page_title'] = 'Report List';
        $data['dept_list'] = $this->general_model->active_department_list(5);
        $data['referal_doctor_list'] = $this->general_model->referal_doctor_list();
        $this->load->view('department_reports/list',$data);
    }
    public function ajax_list()
    {  
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->reports->get_datatables();
        $dept_list = $this->general_model->active_department_list(5);
        
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
        $tot_amout=array();
         foreach ($list as $reports_one) 
         {
        if(!empty($dept_list))
            {
                foreach($dept_list as $dept)
                {
                    $department_charge = get_department_wise_charge($users_data['parent_id'],$dept->id,$reports_one->id);
                    //echo "<pre>"; print_r($department_charge[0]); exit;
                      if(!empty($department_charge[0]['total_amount']))
                      {
                         $tot_amout[$dept->id] += $department_charge[0]['total_amount'];
                         
                      }
                     
                     
                    
                }
            }
         }
            
        //    echo "<pre>"; print_r($tot_amout); exit;
            
        foreach ($list as $reports) {
         // print_r($reports);die;
            ////////// Check  List /////////////////
               $check_script = "";
                          
               ////////// Check list end ///////////// 
            $no++;
            $row = array(); 
            
            $total_amount += $reports->total_amount;
            $total_discount += $reports->discount;
            $total_net_amount += $reports->net_amount;
            $total_paid_amount += $reports->paid_amount;
            $total_balance += $reports->balance;

            
            $row[] = date('d-m-Y',strtotime($reports->booking_date));  
            $row[] = $reports->patient_name;  
            $row[] = $reports->doctor_hospital_name;  
            $row[] = $reports->total_amount;
           // $tot_amout =array();
            if(!empty($dept_list))
            {
                foreach($dept_list as $dept)
                {
                    //$row[] = $dept->department;  
                    
                    $department_charge = get_department_wise_charge($users_data['parent_id'],$dept->id,$reports->id);
                    //echo "<pre>"; print_r($department_charge[0]); exit;
                      if(!empty($department_charge[0]['total_amount']))
                      {
                         //$tot_amout[$dept->id] += $department_charge[0]['total_amount'];
                         $row[] = $department_charge[0]['total_amount'];
                      }
                      else
                      {
                         $row[]='';
                      }
                  
                    
                }
            }
              
             
            $row[] = $reports->discount;  
            $row[] = $reports->net_amount;  
            $row[] = $reports->paid_amount;  
            $row[] = $reports->balance;  
            
            $data[] = $row;
            $tot_row = [];
            if($i==$total_num)
            {  
              
               
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '<b>Total </b>';  
              $tot_row[] = '<input type="text" class="w-100px" value="'.number_format($total_amount,2).'" />';  
              //$dep_total=0;
              //echo "<pre>"; print_r($dept->id); 
              if(!empty($dept_list))
                {
                    $dep_total=0;
                    foreach($dept_list as $dept)
                    {
                        //$department_charges = get_department_wise_charge($users_data['parent_id'],$dept->id,$reports->id);
                        //$dep_total = $dep_total+$department_charges[0]['total_amount'];
                        $tot_row[] = '<input type="text" class="w-100px" value="'.number_format($tot_amout[$dept->id],2).'" />'; 
                      
                    }
                }
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
                                   'referral_doctor'=>$post['referral_doctor'],
                                   'dept_id'=>$post['dept_id'],
                                   'end_date'=>$post['end_date'],
                                   'department'=>$post['department'],
                                 );
         $this->session->set_userdata('dept_search_data',$search_data);
       }
    }
    
    
    

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('dept_search_data');
    }
    
    
    public function path_report_excel()
    {
         $users_data = $this->session->userdata('auth_users'); 
        $dept_list = $this->general_model->active_department_list(5);
        
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
            $amt_ttl=0;
            $ttl_disc=0;
            $ttl_net_amt=0; 
            $ttl_paid_amt=0;
            $ttl_blnce=0;
        
        $mylist = $this->reports->search_report_data();
        $tot_amout=array();
         foreach ($mylist as $reports_one) 
         {
            if(!empty($dept_list))
            {
                foreach($dept_list as $dept)
                {
                    $department_charge = get_department_wise_charge($users_data['parent_id'],$dept->id,$reports_one->id);
                      if(!empty($department_charge[0]['total_amount']))
                      {
                         $tot_amout[$dept->id] += $department_charge[0]['total_amount'];
                         
                      }
                     
                     
                    
                }
            }
         }
         
         if(!empty($dept_list))
          {
            $field_name[] = 'Date';
            $field_name[] = 'Patient Name';
            $field_name[] = 'Referred By';
            $field_name[] = 'Total Amount';  
            
            foreach($dept_list as $dept)
              {
                $field_name[]=  $dept->department;
              }   
            $field_name[] = 'Discount';
            $field_name[] = 'Net Amount';
            $field_name[] = 'Paid Amount';
            $field_name[] = 'Balance';
            $fields = $field_name;
          }
          
          
          $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $col = 0;

          foreach ($fields as $field)
          {
              $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
              $col++;
          }
          
       
        
        
        
        $list = $this->reports->search_report_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            $i=0;
            foreach($list as $reports)
            {
              // added on 31-jan-2018
                $amt_ttl=$reports->total_amount+$amt_ttl;
                $ttl_disc= $ttl_disc + $reports->discount;
                $ttl_net_amt = $ttl_net_amt + $reports->net_amount;
                $ttl_paid_amt=$ttl_paid_amt+ $reports->paid_amount;
                $ttl_blnce=$ttl_blnce+$reports->balance;
                // added on 31-jan-2018 
                $data_vals = array();
                if(!empty($dept_list))
                  {
                    $data_vals[]=date('d-m-Y',strtotime($reports->booking_date));
                    $data_vals[]=$reports->patient_name;
                    
                    $data_vals[]=$reports->doctor_hospital_name;
                    $data_vals[]=$reports->total_amount;
                     
                    
                foreach($dept_list as $dept)
                {
                    $department_charge = get_department_wise_charge($users_data['parent_id'],$dept->id,$reports->id);
                     if(!empty($department_charge[0]['total_amount']))
                      {
                          
                          $data_vals[]=$department_charge[0]['total_amount'];
                         
                         
                      }
                      else
                      {
                          $data_vals[]=0;
                      }
                     
                     
                    
                }
                        
                    
                     $data_vals[]=$reports->discount;
                     $data_vals[]=$reports->net_amount;
                     $data_vals[]=$reports->paid_amount;
                     $data_vals[]=$reports->balance;
                     $rowData = $data_vals;
                     $data_vals = array();
                  }
               // array_push($rowData,,,,$reports->department,,$reports->discount,$reports->net_amount,$reports->paid_amount,$reports->balance);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                  $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }
        $row = 2;
          if(!empty($data))
          {
              foreach($data as $patients_data)
              {
                  $col = 0;
                  foreach ($fields as $field)
                  { 
                      $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $patients_data[$field]);
                      $col++;
                  }
                  $row++;
              }
              
              
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,'Total');
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,$amt_ttl);
              //////////loop
              
              if(!empty($dept_list))
                {
                    $dep_c_total=4;
                    foreach($dept_list as $dept)
                    {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dep_c_total,$row,number_format($tot_amout[$dept->id],2));
                        
                       $dep_c_total++; 
                      
                    }
                }
              
              
               
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dep_c_total,$row,$ttl_disc);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dep_c_total+1,$row,$ttl_net_amt);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dep_c_total+2,$row,$ttl_paid_amt);
                
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dep_c_total+3,$row,$ttl_blnce);
                 
                //$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':I'.$row.'')->getFont()->setBold( true );  
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                
                
              /*$objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');*/
          }


          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=department_wise_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
      
     
    }
    
    public function pdf_path_report()
    {   
        
        $users_data = $this->session->userdata('auth_users'); 
        $data['dept_list'] = $this->general_model->active_department_list(5);
        
        $data['print_status']="";
        $data['data_list'] = $this->reports->search_report_data();
        $this->load->view('department_reports/path_report_html',$data);
        $html = $this->output->get_output();

        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("pathology_department_report_".time().".pdf");
    }

    public function print_path_report()
    {   
        $users_data = $this->session->userdata('auth_users'); 
        $data['dept_list'] = $this->general_model->active_department_list(5);
        $data['print_status']="1";
        $data['data_list'] = $this->reports->search_report_data();
        $this->load->view('department_reports/path_report_html',$data); 
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