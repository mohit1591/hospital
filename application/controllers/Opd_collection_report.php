<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd_collection_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('reports/opd_collection_report_model','opd_collection_reports');
        $this->load->library('form_validation');
    }

    public function index()
    {   unauthorise_permission('89','564');
        $this->session->unset_userdata('opd_collection_resport_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('opd_collection_resport_search_data');
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
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'');
        $data['page_title'] = 'OPD Collection Report';
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list(); 
        $this->load->view('opd_collection/list',$data);
    }
    public function expenses(){
       $data['page_title'] = 'OPD Collection Reports';
       $this->load->view('opd_collection/expense_report',$data);
    }
    public function ajax_list()
    {  
        unauthorise_permission('89','564');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->opd_collection_reports->get_datatables();
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
        $grand_commission_amount=0;
        $grand_center_collection=0;
        foreach ($list as $reports) 
        {
         // print_r($reports);die;
            ////////// Check  List /////////////////
              $grand_total_amount = $grand_total_amount+$reports->total_amount;
              $grand_total_discount = $grand_total_discount + $reports->discount;
              //$grand_net_amount = $grand_net_amount + $reports->net_amount;
              
              
              if($reports->discount>0)
            {
                $paid_amt = $reports->total_amount-$reports->discount;
                $grand_paid_amount = $grand_paid_amount + $paid_amt;
            }
            else
            {
                $paid_amt = $reports->paid_amount;
                $grand_paid_amount = $grand_paid_amount + $paid_amt;
            }
              
              $grand_commission_amount = $grand_commission_amount+$reports->total_comission;
              $grand_center_collection = $grand_center_collection+($reports->paid_amount-$reports->total_comission);
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
                if($reports->status==1)
                {
                    $status = '<font color="green">Active</font>';
                }   
                else{
                    $status = '<font color="red">Inactive</font>';
                }
                if($reports->booking_status==0)
                {
                    $booking_status = '<font color="red">Pending</font>';
                }   
                elseif($reports->booking_status==1){
                    $booking_status = '<font color="green">Confirm</font>';
                }                 
                elseif($reports->booking_status==2){
                    $booking_status = '<font color="blue">Attended</font>';
                }                 
               ////////// Check list end ///////////// 
            $no++;
            $row = array(); 
            //$row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$reports->id.'">'.$check_script;
            if(!empty($reports->doctor_hospital_name))
            {
                $doctor_hospital_name=$reports->doctor_hospital_name;
            }
            else
            {
                $doctor_hospital_name = $reports->ref_by_other;
            }
            
            $row[] = $i; 
            $row[] = $reports->token_no;
            $row[] = $reports->booking_code;  
            $row[] = date('d-m-Y',strtotime($reports->booking_date));  
            $row[] = $reports->patient_name;  
            $row[] = 'Dr. '.$reports->consultant;  
            $row[] = $doctor_hospital_name;  
            //$row[] = $reports->department;  
            $row[] = $reports->total_amount;  
            $row[] = $reports->discount;  
            if($reports->discount>0)
            {
                $paid_amt = $reports->total_amount-$reports->discount;
            }
            else
            {
                $paid_amt = $reports->paid_amount;
            }
            //$row[] = $reports->net_amount;  
            $row[] = $paid_amt;  
            $row[] = $reports->total_comission;  
            $row[] = $reports->paid_amount-$reports->total_comission;  
            $row[] = $reports->balance; 
            //$row[] = $booking_status;  
                    
             
                   if(in_array('559',$users_data['permission']['action']))
                   {
                         $btn_edit = ' <a class="btn-custom" href="'.base_url("opd/edit_booking/".$reports->id).'" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>';
                    }
            if($reports->blnce>0 && $reports->parent_id>0){
            $print_pdf_url = "'".base_url('opd/print_booking_report/'.$reports->id)."'";
               $btn_print = ' <a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';
            
            }else{
            $print_url = "'".base_url('balance_clearance/print_patient_balance_receipt/'.$reports->pay_id.'/'.$reports->patient_new_id.'/'.$reports->c_date.'/'.$reports->section_id)."'";
            $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
            }
           

             // }
            $row[]=$btn_print;
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
              $tot_row[] = ''; 
              $tot_row[] = '';
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_total_amount,2,'.','').' readonly >';  
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;"  value='.number_format($grand_total_discount,2,'.','').' readonly >';   
              //$tot_row[] = '<input type="text" class="input-tiny" value='.$grand_net_amount.' readonly >';   
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_paid_amount,2,'.','').' readonly >';
              
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_commission_amount,2,'.','').' readonly >';
              
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_center_collection,2,'.','').' readonly >';
              
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_balance_amount,2,'.','').' readonly >'; 
              $tot_row[] = '';
              
              $data[] = $tot_row; 
           }

            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->opd_collection_reports->count_all(),
                        "recordsFiltered" => $this->opd_collection_reports->count_filtered(),
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
                        'start_date'=>$post['start_date'],
                        'branch_id'=>$post['branch_id'],
                        'referral_doctor'=>'',
                        'patient_code'=>'',
                        'patient_name'=>'',
                        'mobile_no'=>'',
                        'end_date'=>$post['end_date'],
                        'attended_doctor'=>'',
                        'employee'=>$post['employee'],
                        'attended_doctor'=>'',
                        'refered_id'=>'',
                        'referred_by'=>'0',
                        'referral_hospital'=>'',
                      );

         $this->session->set_userdata('opd_collection_resport_search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('opd_collection_resport_search_data');
    }

    public function opd_report_excel()
    {
        unauthorise_permission('89','560');
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          
          $checkbox_list = get_checkbox_coloumns_excel('12');
          
          //echo "<pre>"; print_r($checkbox_list); exit;
          $fields=array();
          $column_id_arr=[];
          foreach ($checkbox_list as $checkbox_list_data ) 
          {
              
                $fields[]= $checkbox_list_data->coloum_name;
                $column_id_arr[] = 'cl_'.$checkbox_list_data->coloum_id; 
              
          }
          
          //$fields = array('S.No.','Token No.','OPD No.','Booking Date','Patient Name','Consultant','Referred By','Total Amount','Discount','Paid Amount','Balance');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;

          // added on 02-feb-2018
            $ttl_amt=0;
            $ttl_disc=0;
            $ttl_paid=0;
            
            $ttl_comm=0;
            $ttl_coll=0;
            
            $ttl_bal=0;
          // added on 02-feb-2018

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
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
          
          $list = $this->opd_collection_reports->search_report_data_excel();
            // echo "<pre>"; print_r($list); exit;
          $total_excel_record = count($list);
          
          // set new array /// $checkbox_list $column_id_arr
          $opd_arr_all = [];
          //echo "<pre>"; print_r($list); exit;
          if(!empty($list))
          {
             foreach($list as $key=>$reports)
              {
                  $sub_opd_arr = [];
                  foreach($reports as $skey=>$rep)
                  {
                      if(in_array($skey,$column_id_arr))
                      {
                          $sub_opd_arr[] = $rep;
                          
                         
                      }
                  }
                  
                   $ttl_amt=$ttl_amt+$reports['cl_7'];
                        $ttl_bal=$ttl_bal+$reports['cl_12'];
                        $ttl_paid=$ttl_paid+$reports['cl_9'];
                        $ttl_comm=$ttl_comm+$reports['cl_10'];
                        $ttl_coll = $ttl_coll+($reports['cl_9']-$reports['cl_10']);
                        $ttl_disc=$ttl_disc+$reports['cl_8'];
                  
                  array_push($opd_arr_all,$sub_opd_arr);
                  
                 
                    
                    
                    //$commission_amt = $reports->paid_amount-$reports->total_comission;
                    
                  //echo "<pre>"; print_r($opd_arr_all); exit;
              }
             // echo "<pre>"; print_r($opd_arr_all); exit;
          }
          // end new warray //
          $rowData = array();
          $data= array();
          if(!empty($opd_arr_all))
          {
               
               $i=0;
               $k=1;
               foreach($opd_arr_all as $reports)
               {
                   //echo "<pre>"; print_r($reports); exit;
                    // added on 02-feb-2018
                    /*$ttl_amt=$ttl_amt+$reports->total_amount;
                    $ttl_bal=$ttl_bal+$reports->balance;
                    $ttl_paid=$ttl_paid+$reports->paid_amount;
                    $ttl_comm=$ttl_comm+$reports->total_comission;
                    $ttl_coll = $ttl_coll+($reports->paid_amount-$reports->total_comission);
                    $ttl_disc=$ttl_disc+$reports->discount;
                    
                    $commission_amt = $reports->paid_amount-$reports->total_comission;
                    $booking_date = date('d-m-Y',strtotime($reports->booking_date)).' '.date('h:i A',strtotime($reports->booking_time));*/
                    //echo "<pre>"; print_r($reports); exit;
                   // array_push($rowData,$reports);
                // echo "<pre>"; print_r($rowData); exit;
                    $rowData = $reports;
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;
                    $k++;
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
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                    }
                    $row++;
               }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,$ttl_amt);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$ttl_disc);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,$ttl_paid);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row,$ttl_comm);
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row,$ttl_coll);
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$row,$ttl_bal);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$row.':L'.$row.'')->getFont()->setBold( true );  
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
        header("Content-Disposition: attachment; filename=opd_collection_report_".time().".xls"); 
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
                ob_end_clean();
               $objWriter->save('php://output');
         }
      
    }

    public function opd_report_csv()
    {
         unauthorise_permission('89','560');
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          
          $checkbox_list = get_checkbox_coloumns('12');
          
          //echo "<pre>"; print_r($checkbox_list); exit;
          $fields=array();
          foreach ($checkbox_list as $checkbox_list_data ) 
          {
              if($checkbox_list_data->selected_status!='' && $checkbox_list_data->coloum_name!='Actions')
              {
                $fields[]= $checkbox_list_data->coloum_name;
              }
          }
          
          //echo "<pre>"; print_r($fields); exit;
          
          //$fields = array('S.No.','Token No.','OPD No.','Booking Date','Patient Name','Consultant','Referred By','Total Amount','Discount','Paid Amount','Balance');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;

          // added on 02-feb-2018
            $ttl_amt=0;
            $ttl_disc=0;
            $ttl_paid=0;
            
            $ttl_comm=0;
            $ttl_coll=0;
            
            $ttl_bal=0;
          // added on 02-feb-2018

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
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
          //new
          /*foreach ($checkbox_list as $checkbox_list_data ) 
          {
              if($checkbox_list_data->default_coloum=='1' && $checkbox_list_data->coloum_name!='Actions')
              {
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $checkbox_list_data->coloum_name);
                 
                 
                 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
              
               $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
               
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
              
                 
                  $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                  $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                   $col++;
                   $row_heading++;
              }
          }*/
          
          $list = $this->opd_collection_reports->search_report_data();
             //echo "<pre>"; print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $k=1;
               foreach($list as $reports)
               {
                  // added on 02-feb-2018
                    $ttl_amt=$ttl_amt+$reports->total_amount;
                    $ttl_bal=$ttl_bal+$reports->balance;
                    $ttl_paid=$ttl_paid+$reports->paid_amount;
                    $ttl_comm=$ttl_comm+$reports->total_comission;
                    $ttl_coll = $ttl_coll+($reports->paid_amount-$reports->total_comission);
                    $ttl_disc=$ttl_disc+$reports->discount;
                    
                    $commission_amt = $reports->paid_amount-$reports->total_comission;
                    $booking_date = date('d-m-Y',strtotime($reports->booking_date)).' '.date('h:i A',strtotime($reports->booking_time));
                    
                    array_push($rowData,$reports->token_no,$reports->booking_code,$booking_date,$reports->patient_name,'Dr. '.$reports->consultant,$reports->doctor_hospital_name,$reports->total_amount,$reports->discount,$reports->paid_amount,$reports->total_comission,$commission_amt,$reports->balance);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;
                    $k++;
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
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                    }
                    $row++;
               }
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,'Total');
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$ttl_amt);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$ttl_disc);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,$ttl_paid);
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$ttl_comm);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,$ttl_coll);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row,$ttl_bal);
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
         header("Content-Disposition: attachment; filename=opd_collection_report_".time().".csv");
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
        
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('opd_collection_resport_search_data',$post); 
        }
        //$data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        
$data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
          $data['doctors_list']= $this->general_model->doctors_list();
$data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        //$data['dept_list'] = $this->general_model->department_list(); 
        //$data['employee_list'] = $this->opd->employee_list();
        $data['profile_list'] = $this->opd->profile_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list(); 
        $data['search_data'] = $this->session->userdata('opd_collection_resport_search_data');
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list();
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
           $search_data = $data['search_data'];
           $data['form_data'] = array(
                                   'start_date'=>$search_data['start_date'],
                                   'referral_doctor'=>$search_data['referral_doctor'],
                                   'patient_code'=>$search_data['patient_code'],
                                   'patient_name'=>$search_data['patient_name'],
                                   'mobile_no'=>$search_data['mobile_no'],
                                   'end_date'=>$search_data['end_date'],
                                   'attended_doctor'=>$search_data['attended_doctor'],
                                   'refered_id'=>$search_data['refered_id'],
                                     'referred_by'=>$search_data['referred_by'],
                                     'referral_hospital'=>$search_data['referral_hospital'],
                                   'branch_id'=>$search_data['branch_id'],
                                   'employee'=>$post['employee'],
                                   "insurance_type"=>$search_data['insurance_type'],
                                    "insurance_type_id"=>$search_data['insurance_type_id'],
                                    "ins_company_id"=>$search_data['ins_company_id'],
                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'start_date'=>'',
                                   "referred_by"=>"",
                                          "refered_id"=>"",
                                          "referral_hospital"=>"",
                                   'patient_code'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>'',
                                   'attended_doctor'=>'',
                                   'profile_id'=>'',
                                   'branch_id'=>'',
                                   "insurance_type"=>"0",
                                    "insurance_type_id"=>"",
                                    "ins_company_id"=>"",
                                   'employee'=>'',
                                 );
        }  
        $this->load->view('opd_collection/advance_search',$data);
    }

    public function pdf_opd_report()
    {   
        unauthorise_permission('89','562');
        $data['print_status']="";
        $this->load->model('general/general_model');
          $get_date_time_setting = $this->general_model->get_date_time_setting('opd_collection_report');
          $data['date_time_status'] = $get_date_time_setting->date_time_status;
        $data['data_list'] = $this->opd_collection_reports->search_report_data();
        $this->load->view('opd_collection/opd_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("opd_collection_report_".time().".pdf");
    }

    public function print_opd_report()
    {   
        unauthorise_permission('89','563');
        $data['print_status']="1";
        $this->load->model('general/general_model');
          $get_date_time_setting = $this->general_model->get_date_time_setting('opd_collection_report');
          $data['date_time_status'] = $get_date_time_setting->date_time_status;
        $data['data_list'] = $this->opd_collection_reports->search_report_data();
        $this->load->view('opd_collection/opd_report_html',$data); 
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
    public function print_opd_collection_reports()
    { 
     unauthorise_permission('89','563');
     $get = $this->input->get();
     $data['opd_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['opd_collection_list'] = $this->opd_collection_reports->get_opd_collection_list_details($get);
     } 
     $this->load->view('opd_collection/list_opd_collection',$data);  

    }

    
     
}
?>