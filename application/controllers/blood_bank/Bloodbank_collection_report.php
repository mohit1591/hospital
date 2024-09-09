<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bloodbank_collection_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/report/Bloodbank_collection_report_model','bloodbank_collection_report');
        $this->load->library('form_validation');
    }
    

    public function index()
    {  
        unauthorise_permission('268','1560');
        $this->session->unset_userdata('bloodbank_collection_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('bloodbank_collection_search_data');
        // Default Search Setting
        $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        }
        // End Defaul Search
        $data['form_data'] = array('from_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'');
        $data['page_title'] = 'Blood Bank Collection Report';
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list();
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']=$this->blood_general_model->get_component_list();
        $this->load->view('blood_bank/bloodbank_collection/list',$data);
    }


    public function expenses()
    {
       $data['page_title'] = 'Medicine Collection Reports';
       $this->load->view('medicine_collection/expense_report',$data);
    }


    

    public function ajax_list()
    {  
        unauthorise_permission('260','1560');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->bloodbank_collection_report->get_datatables();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $grand_total_amount =0;
        $grand_total_discount=0;
        //$grand_net_amount=0;
        $grand_paid_amount=0;
        $grand_balance_amount=0;
        foreach($list as $reports) 
        {
          //print_r($reports);
           
              $grand_total_discount = $grand_total_discount + $reports->discount;
              //$grand_net_amount = $grand_net_amount + $reports->net_amount;
              $grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
              $grand_balance_amount = $grand_balance_amount + $reports->balance;
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
           
            $row[] = $reports->issue_code;  
            $row[] = date('d-m-Y',strtotime($reports->requirement_date));  
            $row[] = $reports->patient_name;
            $row[] = $reports->component;
            $row[] = $reports->comp_unite;
            //$row[] = $reports->doctor_hospital_name;   
            $row[] = $reports->total_amount;
            $grand_total_amount = $grand_total_amount+$reports->total_amount;  
            $row[] = $reports->discount;    
            $row[] = $reports->paid_amount;  
            $row[] = $reports->balance; 
           
            if($reports->blnce>0 && $reports->parent_id>0){
               $print_url = "'".base_url('blood_bank/recipient/print_issue_recipt/'.$reports->id)."'";
               $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
             }else{
               $print_url = "'".base_url('balance_clearance/print_patient_balance_receipt/'.$reports->pay_id.'/'.$reports->patient_new_id.'/'.$reports->c_date.'/'.$reports->section_id)."'";
               $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
             }
           

            $btn_edit = ' <a class="btn-custom" href="'.base_url("sales_medicine/edit/".$reports->id).'" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>';
                    

              
            $row[] = $btn_print; 
            $row[] = $btn_edit;         
            $data[] = $row;

            $tot_row = array();
           if($i==$total_num)
           {
              
              
              $tot_row[] = '';
              $tot_row[] = '';  
              $tot_row[] = '';
              $tot_row[] = '';
              $tot_row[] = '';
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_total_amount,2).' readonly >';  
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;"  value='.number_format($grand_total_discount,2).' readonly >';   
              //$tot_row[] = '<input type="text" class="w-90px" value='.$grand_net_amount.' readonly >';   
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_paid_amount,2).' readonly >'; 
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_balance_amount,2).' readonly >'; 
              $tot_row[] = '';
              $tot_row[] = '';
              $data[] = $tot_row; 
           }

            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bloodbank_collection_report->count_all(),
                        "recordsFiltered" => $this->bloodbank_collection_report->count_filtered(),
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
	     $search_data =  array(
                                   'from_date'=>$post['from_date'],
                                   'referral_doctor'=>'',
                                   'sale_no'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>$post['end_date'],
                                   'branch_id'=>$post['branch_id']
                                   
                                 );
         $this->session->set_userdata('bloodbank_collection_search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('bloodbank_collection_search_data');
    }

    public function bloodbank_report_excel()
    {
         
        unauthorise_permission('268','1568');
              // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $grand_total_amount =0;
          $grand_total_discount=0;
          $grand_paid_amount=0;
          $grand_balance_amount=0;
          $objWorksheet = $objPHPExcel->getActiveSheet();
         // print_r($objWorksheet);die;

          $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
          $fields = array('Issue Code','Requirement Date' ,'Patient Name','Component','Component Unit','Net Amount','Discount','Paid Amount','Balance');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
			$this->load->model('general/general_model');
          $list =  $this->bloodbank_collection_report->search_report_data();
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {
               		if(!empty($reports->requirement_date))
				{
					//$sale_date = date('d-m-Y',strtotime($reports->requirement_date)).' '.date('h:i A',strtotime($reports->c_date));
					$requirement_date = date('d-m-Y',strtotime($reports->requirement_date));
				} 
				else
				{
					$requirement_date = date('d-m-Y',strtotime($reports->requirement_date));
				}


                $grand_total_amount = $grand_total_amount+$reports->total_amount;
                  $grand_total_discount = $grand_total_discount + $reports->discount;
                  $grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
                  $grand_balance_amount = $grand_balance_amount + $reports->balance;
                  $discount=$reports->discount;
                
                  array_push($rowData,$reports->issue_code,$requirement_date,$reports->patient_name,$reports->component,$reports->comp_unite,number_format($reports->total_amount,2),$discount,number_format($reports->paid_amount,2),number_format($reports->balance,2));
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

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
               foreach($data as $reports_data)
               {
                    $col = 0;
                     $row_val=1;
                    foreach ($fields as $field)
                    { 
                          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                          // added on 31-jan-2018
                          $objWorksheet->setCellValueByColumnAndRow(4,$row+1,'Total');
                          // added on 31-jan-2018
                          $objWorksheet->setCellValueByColumnAndRow(5,$row+1,$grand_total_amount);
                          $objWorksheet->setCellValueByColumnAndRow(6,$row+1,$grand_total_discount);
                          $objWorksheet->setCellValueByColumnAndRow(7,$row+1,$grand_paid_amount);
                          $objWorksheet->setCellValueByColumnAndRow(8,$row+1,$grand_balance_amount);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                    }
                    $row++;
               }
                // added on 31-jan-2018
                //$objPHPExcel->getActiveSheet()->getStyle('D'.$row.':H'.$row.'')->getFont()->setBold( true );
                 // added on 31-jan-2018
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=bloodbank_collection_report_".time().".xls");  
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
                ob_end_clean();
               $objWriter->save('php://output');
          }
        
    
        
    }

     public function bloodbank_report_csv()
    {
    	 unauthorise_permission('268','1566');
       
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);

            $grand_total_amount =0;
          $grand_total_discount=0;
          $grand_paid_amount=0;
          $grand_balance_amount=0;
          $objWorksheet = $objPHPExcel->getActiveSheet();
         // print_r($objWorksheet);die;
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
           $fields = array('Issue Code','Requirement Date','Patient Name','Component','Component Unit','Net Amount','Discount','Paid Amount','Balance');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading=1;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
          $this->load->model('general/general_model');
          $list =  $this->bloodbank_collection_report->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {
               		if(!empty($reports->requirement_date))
				{
					//$sale_date = date('d-m-Y',strtotime($reports->requirement_date)).' '.date('h:i A',strtotime($reports->c_date));
					$requirement_date = date('d-m-Y',strtotime($reports->requirement_date));
				} 
				else
				{
					$requirement_date = date('d-m-Y',strtotime($reports->requirement_date));
				}
                   $grand_total_amount = $grand_total_amount+$reports->total_amount;
                  $grand_total_discount =$grand_total_discount + $reports->discount;
                  $grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
                  $grand_balance_amount = $grand_balance_amount + $reports->balance;
  			
                    array_push($rowData,$reports->issue_code,$requirement_date,$reports->patient_name,$reports->component,$reports->comp_unite,number_format($reports->total_amount,2),number_format($reports->discount,2),number_format($reports->paid_amount,2),number_format($reports->balance,2));
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

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
               foreach($data as $reports_data)
               {
                  //print_r($reports_data);die;
                    $col = 0;
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                      $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                      $objWorksheet->setCellValueByColumnAndRow(4,$row+1,'Total');
                      $objWorksheet->setCellValueByColumnAndRow(5,$row+1,$grand_total_amount);
                      $objWorksheet->setCellValueByColumnAndRow(6,$row+1,$grand_total_discount);
                      $objWorksheet->setCellValueByColumnAndRow(7,$row+1,$grand_paid_amount);
                      $objWorksheet->setCellValueByColumnAndRow(8,$row+1,$grand_balance_amount);
                         $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                    }
                    $row++;
               }

          
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=bloodbank_collection_report_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
                ob_end_clean();
               $objWriter->save('php://output');
          }
    
    
    }

    public function advance_search()
    {
      $data['page_title'] = "Advance Search";
        $this->load->model('opd/opd_model','opd');
        $this->load->model('general/general_model','general_model');
        $post = $this->input->post(); 
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['doctors_list']= $this->general_model->doctors_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['employee_list'] = $this->general_model->branch_user_list();

        $data['profile_list'] = $this->opd->profile_list();
        $data['search_data'] = $this->session->userdata('bloodbank_collection_search_data');
        
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']=$this->blood_general_model->get_component_list();
        
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
           $search_data = $data['search_data'];
           $data['form_data'] = array(
                                   'from_date'=>$search_data['from_date'],
                                   'patient_name'=>$search_data['patient_name'],
                                   'mobile_no'=>$search_data['mobile_no'],
                                   'end_date'=>$search_data['end_date'],
                                   'refered_id'=>$search_data['refered_id'],
                                    'referred_by'=>$search_data['referred_by'],
                                     'referral_hospital'=>$search_data['referral_hospital'],
                                   'branch_id'=>$search_data['branch_id'],
                                   'employee'=>$search_data['employee'],
                                   'component_id'=>$search_data['component_id'],
                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'from_date'=>'',
                                   "referred_by"=>"",
                                    "refered_id"=>"",
                                    "referral_hospital"=>"",
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>'',
                                   'attended_doctor'=>'',
                                   'profile_id'=>'',
                                   'branch_id'=>'',
                                   'employee'=>'',
                                   'component_id'=>'',
                                 );
        }  
          if(isset($post) && !empty($post))
          {
          $marge_post = array_merge($data['form_data'],$post);
          $this->session->set_userdata('bloodbank_collection_search_data', $marge_post);
          }
      
        $this->load->view('blood_bank/bloodbank_collection/advance_search',$data);
    }


    public function pdf_bloodbank_report()
    {   
         unauthorise_permission('268','1569');
        $data['print_status']="";
        $this->load->model('general/general_model');
        $data['data_list'] = $this->bloodbank_collection_report->search_report_data();
        $this->load->view('blood_bank/bloodbank_collection/bloodbank_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("bloodbank_collection_report_".time().".pdf");
    }

    
     public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
               if(!empty($sub_branch_details)){
                    $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name ="sub_branch_id" ><option value="">Select Sub Branch</option><option value="all" >All</option></option><option  selected="selected"  value='.$users_data['parent_id'].'>Self</option>';
                 
                     $i=0;
                     foreach($sub_branch_details as $key=>$value){
                         $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                         $i = $i+1;
                    }
               }
               $dropdown.='</select>';
               echo $dropdown; 
        }
         
       
    }
    public function print_bloodbank_collection_report()
    { 
    unauthorise_permission('268','1565');
     $get = $this->input->get();
     $data['medicine_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['medicine_collection_list'] = $this->bloodbank_collection_report->get_opd_collection_list_details($get);
     } 
     $this->load->view('medicine_collection/list_medicine_collection',$data);  

    }

    public function print_all_bloodbank_report()
    {   
        unauthorise_permission('268','1569');
        $data['print_status']="1";
        $this->load->model('general/general_model');
        //$get_date_time_setting = $this->general_model->get_date_time_setting('opd_collection_report');
          //$date_time_status = $get_date_time_setting->date_time_status;
          //$get_date_time_setting = $this->general_model->get_date_time_setting('opd_collection_report');
//data['date_time_status'] = $get_date_time_setting->date_time_status;
        $data['data_list'] = $this->bloodbank_collection_report->search_report_data();
        $this->load->view('blood_bank/bloodbank_collection/bloodbank_report_html',$data); 
    }

    
     
}
?>