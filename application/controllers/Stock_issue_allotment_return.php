<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_issue_allotment_return extends CI_Controller {
 
	function __construct() 
	{
  	parent::__construct();	
  	 auth_users();  
  	$this->load->model('stock_issue_allotment_return/stock_issue_allotment_return_model','stock_issue_allotment_return');
  	$this->load->library('form_validation');
  }

    
  	public function index()
      {
        unauthorise_permission(168,970);
        $data['page_title'] = 'Issue/Allot Return List'; 
        $this->session->unset_userdata('stock_issue_allotment_return_item_list');  
        $this->session->unset_userdata('stock_issue_allotment_return_payment_array');
        $this->session->unset_userdata('stock_issue_allotment_return_search'); 
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
        $data['form_data'] = array('purchase_no'=>'','invoice_id'=>'','paid_amount_to'=>'','paid_amount_from'=>'','balance_to'=>'','balance_from'=>'','start_date'=>$start_date, 'end_date'=>$end_date);
        $this->load->view('stock_issue_allotment_return/list',$data);
      }

    public function ajax_list()
    {  
        unauthorise_permission(168,970);
        $list = $this->stock_issue_allotment_return->get_datatables(); 
 //echo "<pre>"; print_r( $list); 

        $data = array();
        $no = $_POST['start'];
        $i = 1;
		$grand_total_amount =0;
	    $grand_total_discount=0;
	    $grand_paid_amount=0;
	    $grand_balance_amount=0;
	    $grand_net_amount =0;
        $total_num = count($list);
        //$row='';
        foreach ($list as $stock_issue_allotment_return) { 
            $no++;
            $row = array();
            if($stock_issue_allotment_return->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }

            ///// State name ////////
            $state = "";
            if(!empty($stock_issue_allotment_return->state))
            {
                $state = " ( ".ucfirst(strtolower($stock_issue_allotment_return->state))." )";
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
            $row[] = '<input type="checkbox" name="stock_issue_allotment_return[]" class="checklist" value="'.$stock_issue_allotment_return->id.'">';
            $row[] = $stock_issue_allotment_return->return_no;
            $row[] = $stock_issue_allotment_return->items;
            $row[] = $stock_issue_allotment_return->issue_no;
            $row[] = $stock_issue_allotment_return->member_name;
			$row[] = $stock_issue_allotment_return->total_amount;
			$row[] = $stock_issue_allotment_return->discount;
            $row[] = $stock_issue_allotment_return->net_amount;
            $row[] = $stock_issue_allotment_return->paid_amount;
            $row[] = $stock_issue_allotment_return->balance;
			
            $row[] = date('d-m-Y',strtotime($stock_issue_allotment_return->issue_date));

         
            $users_data = $this->session->userdata('auth_users');
           $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('972',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_stock_purchase('.$stock_issue_allotment_return->id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock_issue_allotment_return->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
             
          if(in_array('974',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_stock_purchase('.$stock_issue_allotment_return->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
           }
		   
           $print_url = "'".base_url('stock_issue_allotment_return/print_stock_issue_allotment_report/'.$stock_issue_allotment_return->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
            $row[] = $btnedit.$btndelete.$btnprint;
            $data[] = $row;
			
			
		  $grand_total_amount = $grand_total_amount+$stock_issue_allotment_return->total_amount;
		  $grand_total_discount = $grand_total_discount + $stock_issue_allotment_return->discount;
		  $grand_net_amount = $grand_net_amount + $stock_issue_allotment_return->net_amount;
		  $grand_paid_amount = $grand_paid_amount + $stock_issue_allotment_return->paid_amount;
		  $grand_balance_amount = $grand_balance_amount + $stock_issue_allotment_return->balance;
			
		   $tot_row = array();
           if($i==$total_num)
           {
              $tot_row[] = ''; 
              $tot_row[] = ''; 
              $tot_row[] = '';
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '<input type="text" class="w-90px" style="text-align:right;" value='.number_format($grand_total_amount,2).' readonly >';  
              $tot_row[] = '<input type="text" class="w-110px" style="text-align:right;" value='.number_format($grand_total_discount,2).' readonly >';   
              $tot_row[] = '<input type="text" class="w-110px" style="text-align:right;" value='.number_format($grand_net_amount,2).' readonly >';   
              $tot_row[] = '<input type="text" class="w-110px" style="text-align:right;" value='.number_format($grand_paid_amount,2).' readonly >'; 
              $tot_row[] = '<input type="text" class="w-110px" style="text-align:right;" value='.number_format($grand_balance_amount,2).' readonly >'; 
              $tot_row[] = '';
              $tot_row[] = '';
              $data[] = $tot_row; 
           }
			
			
			
            $i++;
          }
        

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_issue_allotment_return->count_all(),
                        "recordsFiltered" => $this->stock_issue_allotment_return->count_filtered(),
                        "data" => $data,
                );
         //output to json format
        echo json_encode($output);
    }

      public function stock_allotment_return_issue_excel()
    {
         
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
		  $grand_net_amount =0;
		 
		  
          $objWorksheet = $objPHPExcel->getActiveSheet();
         

          $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
        
		  $fields = array('Return Code','Items','Issue Code','Patient / Employee / Doctor Name','Total Amount','Discount','Net Amount','Paid Amount','Balance','Return Date');
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
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
          $this->load->model('general/general_model');
 
          $list =  $this->stock_issue_allotment_return->search_report_data();
          //print '<pre>'; print_r($list);die;
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {
				$grand_total_amount = $grand_total_amount + $reports->total_amount;
				$grand_total_discount = $grand_total_discount + $reports->discount;
				$grand_net_amount = $grand_net_amount + $reports->net_amount;
				$grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
				$grand_balance_amount = $grand_balance_amount + $reports->balance;
 
                  if(1)
                  {
                    $issue_date = date('d-m-Y',strtotime($reports->issue_date));  
                  } 
                  else
                  {
                    $issue_date = date('d-m-Y',strtotime($reports->issue_date));
                  }

                  array_push($rowData,$reports->return_no,$reports->items,$reports->issue_no,$reports->member_name,number_format($reports->total_amount,2),number_format($reports->discount,2),number_format($reports->net_amount,2),number_format($reports->paid_amount,2),number_format($reports->balance,2),$issue_date);
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
                          $objWorksheet->setCellValueByColumnAndRow(3,$row+1,'Total');
                          // added on 31-jan-2018
                          $objWorksheet->setCellValueByColumnAndRow(4,$row+1,number_format($grand_total_amount,2));
						  $objWorksheet->setCellValueByColumnAndRow(5,$row+1,number_format($grand_total_discount,2));
                          $objWorksheet->setCellValueByColumnAndRow(6,$row+1,number_format($grand_net_amount,2));
                          $objWorksheet->setCellValueByColumnAndRow(7,$row+1,number_format($grand_paid_amount,2));
                          $objWorksheet->setCellValueByColumnAndRow(8,$row+1,number_format($grand_balance_amount,2));
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                    }
                    $row++;
               }
                // added on 31-jan-2018
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':H'.$row.'')->getFont()->setBold( true );
                 // added on 31-jan-2018
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=stock_allotment_issue_return_report_".time().".xls");  
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
                ob_end_clean();
               $objWriter->save('php://output');
          }
        
    
        
    }	
	
public function stock_allotment_return_issue_csv()
    {
       
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
		  $grand_net_amount =0;
          $objWorksheet = $objPHPExcel->getActiveSheet();
         // print_r($objWorksheet);die;
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
          $fields = array('Return Code','Items','Issue Code','Patient / Employee / Doctor Name','Total Amount','Discount','Net Amount','Paid Amount','Balance','Return Date');
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
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
          $this->load->model('general/general_model');
         
           $list =  $this->stock_issue_allotment_return->search_report_data();
        
          $rowData = array();
          $data= array();
          //print '<pre>'; print_r($list);die;
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {

				$grand_total_amount = $grand_total_amount + $reports->total_amount;
				$grand_total_discount = $grand_total_discount + $reports->discount;
				$grand_net_amount = $grand_net_amount + $reports->net_amount;
				$grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
				$grand_balance_amount = $grand_balance_amount + $reports->balance;
 
                  if(1)
                  {
                    $issue_date = date('d-m-Y',strtotime($reports->issue_date));  
                  } 
                  else
                  {
                    $issue_date = date('d-m-Y',strtotime($reports->issue_date));
                  }

                  array_push($rowData,$reports->return_no,$reports->items,$reports->issue_no,$reports->member_name,number_format($reports->total_amount,2),number_format($reports->discount,2),number_format($reports->net_amount,2),number_format($reports->paid_amount,2),number_format($reports->balance,2),$issue_date);
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
                      $objWorksheet->setCellValueByColumnAndRow(3,$row+1,'Total');
                      $objWorksheet->setCellValueByColumnAndRow(4,$row+1,number_format($grand_total_amount,2));
					  $objWorksheet->setCellValueByColumnAndRow(5,$row+1,number_format($grand_total_discount,2));
					  $objWorksheet->setCellValueByColumnAndRow(6,$row+1,number_format($grand_net_amount,2));
					  $objWorksheet->setCellValueByColumnAndRow(7,$row+1,number_format($grand_paid_amount,2));
					  $objWorksheet->setCellValueByColumnAndRow(8,$row+1,number_format($grand_balance_amount,2));
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
          header("Content-Disposition: attachment; filename=stock_allotment_issue_return_report_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
                ob_end_clean();
               $objWriter->save('php://output');
          }
    
    
    }

    public function pdf_stock_allotment_return_issue()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->stock_issue_allotment_return->search_report_data();
        $this->load->view('stock_issue_allotment_return/stock_allotment_issue_return_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("stock_allotment_issue_return_report_".time().".pdf");
    }
    public function print_stock_allotment_return_issue()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->stock_issue_allotment_return->search_report_data();
      $this->load->view('stock_issue_allotment_return/stock_allotment_issue_return_report_html',$data); 
    }
    public function total_calc_return()
    {
      $response = $this->session->userdata('net_values_all');
      $data = array('net_amount'=>'0','discount'=>'0','balance'=>'0','paid_amount'=>'0');
      if(isset($response))
      {
      $data = $response;
      }
      echo json_encode($data,true);
    }
    public function reset_search()
    {
        $this->session->unset_userdata('stock_issue_allotment_return_search');
    }
     public function advance_search()
      {

          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
         // $data['simulation_list'] = $this->general_model->simulation_list();
          $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                      "vendor_code"=>""
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('stock_issue_allotment_return_search', $marge_post);
          }
          $stock_issue_allotment_return_search = $this->session->userdata('stock_issue_allotment_return_search');
          if(isset($stock_issue_allotment_return_search) && !empty($stock_issue_allotment_return_search))
          {
              $data['form_data'] = $stock_issue_allotment_return_search;
          }
          //$this->load->view('purchase/advance_search',$data);
        }

    public function add($ipd_id="",$patient_id="",$employee_type="")
    {
      
//echo "<pre>"; print_r($_POST);die;

          unauthorise_permission(168,971);
          $post = $this->input->post();
          $return_code = generate_unique_id(31);
          $this->load->model('general/general_model'); 
          $data['vendor_list'] = $this->stock_issue_allotment_return->vendor_list();
          $data['payment_mode']=$this->general_model->payment_mode();
          $this->load->model('employee/employee_model');
          $data['type_list'] = $this->employee_model->employee_type_list();
          $data['employee_list']= $this->stock_issue_allotment_return->get_employee();

          if(isset($post['user_type']))
          {
            $data['user_list']= $this->stock_issue_allotment_return->get_data_according_user($post['user_type']);
          
          }

          // if(!isset($post) || empty($post))
          // {
          // $this->session->unset_userdata('stock_items_data');  
          // }
          // $stock_issue_allotment_return_item_list = $this->session->userdata('stock_issue_allotment_return_item_list');

    if(!isset($post) || empty($post))
    {
    $this->session->unset_userdata('stock_issue_allotment_return_item_list');  
    }
    $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
    $data['stock_issue_allotment_return_item_list'] = $stock_issue_allotment_item_list;

    if(!empty($stock_issue_allotment_return_item_list))
    { 
    $item_id_arr = [];
    foreach($stock_issue_allotment_return_item_list as $key=>$item_sess)
    {
//echo "<pre>"print_r($item_sess);die;
     $item_id_arr[] = $key;
    } 
    $item_ids = implode(',', $item_id_arr);
    $data['stock_issue_allotment_item_list'] = $this->stock_issue_allotment_return->item_list($item_ids);
    }

        $stock_issue_allotment_return_item_list= '';
          $data['page_title'] = 'Issue/Allot Return';
          $data['data_id']='';
          $data['stock_issue_allotment_return_item_list']=$stock_issue_allotment_return_item_list;
          $data['form_data']=array(
                                      'data_id'=>'',
                                      'return_code'=>$return_code,
                                      'issue_date'=>date('d-m-Y'),
                                      'user_type'=>1,
                                      'employee_type'=>'',
                                      'user_type_id'=>'',
                                      'payment_mode'=>'',
                                      'total_amount'=>'0.00',
                                      "field_name"=>'',
                                      'user_name'=>'',
                                      'address'=>'',
                                      'discount_amount'=>'0.00',
                                      'pay_amount'=>'0.00',
                                      'balance_due'=>'0.00',
                                      'discount_percent'=>'0.00',
                                      'net_amount'=>'0.00',
                                      'item_discount'=>'0.00',
                                      'igst_amount'=>'0.00',
                                      'sgst_amount'=>'0.00',
                                      'cgst_amount'=>'0.00',
                                      'issue_no'=>'',
                                      );
        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
               $charge_id = $this->stock_issue_allotment_return->save();
                $this->session->set_flashdata('success','Allotment Return added successfully.');
                redirect(base_url('stock_issue_allotment_return'));
            }
            else
            {
                $data['form_error'] = validation_errors(); 
            }
        //print_r($data['form_error']);die;
                
        }
        $this->load->view('stock_issue_allotment_return/add',$data);
    }

    public function edit($id="")
    {

          unauthorise_permission(168,972);
          $post = $this->input->post();
          $purchase_code = generate_unique_id(27);
          $this->load->model('general/general_model'); 
          $result= $this->stock_issue_allotment_return->get_by_id($id);

         // echo "<pre>"; print_r($result); die;
         
          
          $data['vendor_list'] = $this->stock_issue_allotment_return->vendor_list();
          $data['payment_mode']=$this->general_model->payment_mode();
          $data['page_title'] = 'Allotment Return';
          $data['data_id']='';
          
          $data['payment_mode']=$this->general_model->payment_mode();
          //echo $result['mode_payment'];die;
          $get_payment_detail= $this->stock_issue_allotment_return->payment_mode_detail_according_to_field($result['payment_mode'],$id);
          
            $total_values='';
            for($i=0;$i<count($get_payment_detail);$i++) {
            $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

            }


            // $new_purchase_list = $this->session->userdata('stock_issue_allotment_return_item_list');
            // if(count($new_purchase_list)>=1)
            // {
            //  $stock_issue_allotment_return_item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
            // }
            // else
            // {
            //   $this->session->unset_userdata('stock_issue_allotment_return_item_list'); 
            //   $stock_issue_allotment_return_item_list= $this->stock_issue_allotment_return->get_purchase_to_purchase_by_id($id);
            //  //print '<pre>'; print_r($stock_issue_allotment_return_item_list);die;
            //   $this->session->set_userdata('stock_issue_allotment_return_item_list',$stock_issue_allotment_return_item_list);
            // }


$item_id_arr=[];

$result_item_list = $this->stock_issue_allotment_return->get_purchase_to_purchase_by_id($id);
//echo "<pre>"; print_r($result_item_list); die;
 if(empty($post))
 { 
    $result_item_list = $this->stock_issue_allotment_return->get_purchase_to_purchase_by_id($id); 
    $this->session->set_userdata('stock_issue_allotment_return_item_list',$result_item_list);
 }
 $item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
 $data['stock_issue_allotment_return_item_list'] = $item_list;
 $data['id'] = $id;

if(!empty($item_list))
{ 
  $item_id_arr = [];
  foreach($item_list  as $key=>$val)
  { 
     $item_id_arr[] = $key;
  } 
  $item_ids = implode(',', $item_id_arr);
  $data['stock_issue_allotment_item_list'] = $this->stock_issue_allotment_return->item_list($item_ids,$item_id_arr); 

//echo "<pre>";print_r($item_list);die;
 }


         if(isset($result['user_type']))
          {
            $data['user_list']= $this->stock_issue_allotment_return->get_data_according_user($result['user_type']);
          
          }
          $data['employee_list']= $this->stock_issue_allotment_return->get_employee($result['employee_type']);
          //print_r($data['employee_list']);die;
          $this->load->model('employee/employee_model');
          $data['type_list'] = $this->employee_model->employee_type_list();

          $data['stock_issue_allotment_return_item_list'] = $data['stock_issue_allotment_return_item_list']; 
          
          $data['form_data']=array(
                                      'data_id'=>$result['id'],
                                      'return_code'=>$result['return_no'],
                                      'issue_date'=>date('d-m-Y',strtotime($result['issue_date'])),
                                      'user_type'=>$result['user_type'],
                                      'user_type_id'=>$result['user_type_id'],
                                      'employee_type'=>$result['employee_type'],
                                      'payment_mode'=>$result['payment_mode'],
                                      'total_amount'=>$result['total_amount'],
                                      'discount_amount'=>$result['discount'],
                                      "field_name"=>$total_values,
                                      'pay_amount'=>$result['paid_amount'],
                                      'balance_due'=>$result['balance'],
                                      'address'=>$result['address'],
                                      'user_name'=>$result['member_name'],
                                      'discount_percent'=>$result['discount_percent'],
                                      'net_amount'=>$result['net_amount'],
                                      'item_discount'=>$result['item_discount'],
                                      'igst_amount'=>$result['igst'],
                                      'sgst_amount'=>$result['sgst'],
                                      'cgst_amount'=>$result['cgst'],
                                      'issue_no'=>$result['issue_no'],
                                      

                                      );

        if(isset($post) && !empty($post))
        {   
          
        
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {

               $purchase_id = $this->stock_issue_allotment_return->save();
                $this->session->set_flashdata('success','Allotment Return updated successfully.');
                redirect(base_url('stock_issue_allotment_return'));
            }
            else
            {
                $data['form_error'] = validation_errors(); 
            }


                
        }

        $this->load->view('stock_issue_allotment_return/add',$data);
    }

    
    private function _validate()
    {
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();   
        $total_values=array();
        if(isset($post['field_name']))
        {
            $count_field_names= count($post['field_name']);  
            $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);
            for($i=0;$i<$count_field_names;$i++) 
            {
              $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

            }
        } 
        if(isset($post['user_type']))
        {
        $data['user_list']= $this->stock_issue_allotment_return->get_data_according_user($post['user_type']);

        }

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        //$this->form_validation->set_rules('user_name', 'name', 'trim|required'); 
        $this->form_validation->set_rules('pay_amount', 'pay amount', 'trim|required');
         if($post['user_type']==1)
        {
           $this->form_validation->set_rules('employee_type', 'employee type', 'trim|required');
          $this->form_validation->set_rules('user_type_id', 'Employee name', 'trim|required');
        }
         if($post['user_type']==2)
        {
          $this->form_validation->set_rules('user_type_id', 'Patient name', 'trim|required');
        }
         if($post['user_type']==3)
        {
          $this->form_validation->set_rules('user_type_id', 'Doctor name', 'trim|required');
        }
         $stock_issue_allotment_return_item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
         if(isset($post['field_name']))
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
        if(!isset($stock_issue_allotment_return_item_list) && empty($stock_issue_allotment_return_item_list) && empty($post['data_id']))
        {
          $this->form_validation->set_rules('item_id', 'item_id', 'trim|callback_check_stock_purchase_item_id');
        }
        if(isset($post['user_name']) && !empty($post['user_name']))
        {
          $user_name=$post['user_name'];
        }
        else
        {
           $user_name="";
        }
        if(isset($post['address']) && !empty($post['address']))
        {
           $address=$post['address'];
        }
        else
        {
           $address="";
        }
        if(isset($post['vendor_code']) && !empty($post['vendor_code']))
        {
          $vendor_code=$post['vendor_code'];
        }
        else
        {
           $vendor_code='';
        }
         if(isset($post['employee_type']) && !empty($post['employee_type']))
        {
          $employee_type=$post['employee_type'];
        }
        else
        {
           $employee_type='';
        }

        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                      'data_id'=>$post['data_id'],
                                      'return_code'=>$post['return_code'],
                                      'issue_date'=>$post['issue_date'],
                                      'user_type'=>$post['user_type'],
                                      'user_type_id'=>$post['user_type_id'],
                                      'employee_type'=>$employee_type,
                                      'total_amount'=>$post['total_amount'],
                                      'user_name'=>$user_name,
                                      'address'=> $address,
                                      'vendor_code'=>$vendor_code,
                                      "field_name"=>$total_values,
                                      'item_discount'=>$post['item_discount'],
                                      'balance_due'=>$post['balance_due'],
                                      'discount_percent'=>$post['discount_percent'],
                                      'discount_amount'=>$post['discount_amount'],
                                      'payment_mode'=>$post['payment_mode'],
                                      'net_amount'=>$post['net_amount'],
                                      //"purchase_time"=>$post['purchase_time'],
                                      'pay_amount'=>$post['pay_amount'],
                                      'igst_amount'=>$post['igst_amount'],
                                      'sgst_amount'=>$post['sgst_amount'],
                                      'cgst_amount'=>$post['cgst_amount'],
                                      'issue_no'=>$post['issue_no'],
  
                                       );
                            
            return $data['form_data'];
        }   
    }

    public function check_stock_purchase_item_id()
    {
       $stock_issue_allotment_return_item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
       if(isset($stock_issue_allotment_return_item_list) && !empty($stock_issue_allotment_return_item_list))
       {
          return true;
       }
       else
       {
          $this->form_validation->set_message('check_stock_purchase_item_id', 'Please select a item.');
          return false;
       }
    }

    public function ipd_particular_calculation()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
           $charges = $post['charges'];
           $quantity = $post['quantity'];
           $amount = ($charges*$quantity);
           $pay_arr = array('charges'=>$charges, 'amount'=>$amount,'quantity'=>$quantity);
           $json = json_encode($pay_arr,true);
           echo $json;
       }
    }

    

    private function stock_issue_allotment_return_item_list()
    {
        $stock_issue_allotment_return_item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
        //print '<pre>';print_r($stock_issue_allotment_return_item_list);die;
         $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.booked_checkbox').prop('checked', false);
                                  } else {
                                      $('.booked_checkbox').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              "; 
        $rows = '<thead class="bg-theme"><tr>           
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectAll" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Total Price</th>
                  </tr></thead>';  
           if(isset($stock_issue_allotment_return_item_list) && !empty($stock_issue_allotment_return_item_list))
           {
              
              $i = 1;
              
              foreach($stock_issue_allotment_return_item_list as $purchase_item_list)
              {

                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="item_id[]" class="part_checkbox booked_checkbox" value="'.$purchase_item_list['item_id'].'"></td>
                            <td>'.$i.'<input type="hidden" value="'.$purchase_item_list['item_id'].'" id="item_id_'.$purchase_item_list['item_id'].'"/></td>
                            <td>'.$purchase_item_list['item_code'].'<input type="hidden" value="'.$purchase_item_list['item_code'].'" id="item_code_'.$purchase_item_list['item_id'].'"/><input type="hidden" value="'.$purchase_item_list['category_id'].'" id="category_id_'.$purchase_item_list['item_id'].'"/></td>
                            <td>'.$purchase_item_list['item_name'].'<input type="hidden" value="'.$purchase_item_list['item_name'].'" id="item_name_'.$purchase_item_list['item_id'].'"/></td>
                            <td><input type="text" value="'.$purchase_item_list['quantity'].'" name="quantity" id="quantity_'.$purchase_item_list['item_id'].'" onkeyup="payment_cal_perrow('.$purchase_item_list['item_id'].');"/></td>
                            <td>'.$purchase_item_list['unit'].'<input type="hidden" value="'.$purchase_item_list['unit'].'" id="unit_'.$purchase_item_list['item_id'].'"/></td>
                            <td>'.$purchase_item_list['item_price'].'<input type="hidden" value="'.$purchase_item_list['item_price'].'" id="item_price_'.$purchase_item_list['item_id'].'"/></td>
                            <td><input type="text" value="'.$purchase_item_list['total_amount'].'" id="total_price_'.$purchase_item_list['item_id'].'"/></td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Particular data not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function remove_stock_purchase_item_list()
    {
       $post =  $this->input->post();
       
       if(isset($post['item_id']) && !empty($post['item_id']))
       {
           $stock_issue_allotment_return_item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
          //print '<pre>'; print_r($this->session->userdata('stock_issue_allotment_return_item_list'));die;
           $stock_issue_allotment_return_payment_array = $this->session->userdata('stock_issue_allotment_return_payment_array'); 
           
           $particular_id_list = array_column($stock_issue_allotment_return_item_list, 'item_id'); 
           
           foreach($stock_issue_allotment_return_item_list as $key=>$item_list)
           { 
            
              if(in_array($item_list['item_id'],$post['item_id']))
              {  
                //echo "<pre>"; print_r($perticuller_ids); exit;
                 unset($stock_issue_allotment_return_item_list[$key]);
                 //echo $ipd_particular_payment['particulars_charges'];die;
                 $this->session->unset_userdata('stock_issue_allotment_return_payment_array');
                
              }
           }  
          
       
        $amount_arr = array_column($stock_issue_allotment_return_item_list, 'amount'); 
        $total_amount = array_sum($amount_arr);
        $this->session->set_userdata('stock_issue_allotment_return_item_list',$stock_issue_allotment_return_item_list);
        $html_data = $this->stock_issue_allotment_return_item_list();
        $particulars_charges = $total_amount;
        $bill_total = $total_amount;
        $response_data = array('html_data'=>$html_data,'total_amount'=>$total_amount);
        $json = json_encode($response_data,true);
        echo $json;
       }
    }

  public function archive_ajax_list()
    {
        unauthorise_permission(168,975);
        $this->load->model('stock_issue_allotment_return/stock_issue_allotment_return_archive_model','stock_issue_allotment_archive'); 

        $list = $this->stock_issue_allotment_archive->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $stock_issue_allotment_return) { 
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

            $row[] = '<input type="checkbox" name="stock_issue_allotment_return[]" class="checklist" value="'.$stock_issue_allotment_return->id.'">'.$check_script;
             $row[] = $stock_issue_allotment_return->return_no;
            $row[] = $stock_issue_allotment_return->member_name;
            $row[] = $stock_issue_allotment_return->total_amount;
            $row[] = date('d-m-Y',strtotime($stock_issue_allotment_return->issue_date));
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           if(in_array('975',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_stock_purchase('.$stock_issue_allotment_return->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('974',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$stock_issue_allotment_return->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
          }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_issue_allotment_archive->count_all(),
                        "recordsFiltered" => $this->stock_issue_allotment_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
  function get_emp_data($vendor_id="",$user_type="")
  {
    $data['vendor_list'] = $this->stock_issue_allotment_return->vendor_list($vendor_id,$user_type);
    $html='<div class="row m-b-5"><div class="col-md-2">  <label>Name</label>
                </div> <div class="col-md-10"> '.$data['vendor_list'][0]->name.'<input type="hidden" value="'.$data['vendor_list'][0]->name.'" name="user_name"/></div></div><div class="row m-b-5"><div class="col-md-2">
                  <label>Address</label></div><div class="col-md-10"> '.$data['vendor_list'][0]->address.'<input type="hidden" value="'.$data['vendor_list'][0]->address.'" name="address"/> </div></div>';
    echo $html;exit;
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
     $html.='<div class="row m-b-5"> <div class="col-md-5"><label>'.$payment_detail->field_name.'<span class="star">*</span></label></div><div class="col-md-7"><input type="text" name="field_name[]" value="" onkeypress="payment_calc_all();"><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="">'.$var_form_error.'</div></div></div>';
  }
  echo $html;exit;
  
}
 function get_item_values($vals="")
    {

        if(!empty($vals))
        {
            $result = $this->stock_issue_allotment_return->get_item_values($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

     function trashall()
    {
       unauthorise_permission(168,976);
        $this->load->model('stock_issue_allotment_return/stock_issue_allotment_return_archive_model','stock_issue_allotment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_issue_allotment_archive->trashall($post['row_id']);
            $response = "Allotment Return successfully deleted parmanently.";
            echo $response;
        }
    }
      public function trash($id="")
    {
        unauthorise_permission(168,976);
        $this->load->model('stock_issue_allotment_return/stock_issue_allotment_return_archive_model','stock_issue_allotment_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_issue_allotment_archive->trash($id);
           $response = "Allotment Return successfully deleted parmanently.";
           echo $response;
       }
    }
    function restoreall()
    { 
       unauthorise_permission(168,975);
        $this->load->model('stock_issue_allotment_return/stock_issue_allotment_return_archive_model','stock_issue_allotment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_issue_allotment_archive->restoreall($post['row_id']);
            $response = "Allotment Return successfully restore in stock purchase return list.";
            echo $response;
        }
    }
     public function restore($id="")
    {
       unauthorise_permission(168,975);
        $this->load->model('stock_issue_allotment_return/stock_issue_allotment_return_archive_model','stock_issue_allotment_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_issue_allotment_archive->restore($id);
           $response = "Allotment Return successfully restore in stock purchase return list.";
           echo $response;
       }
    }
     public function archive()
    {
       unauthorise_permission(168,975);
        $data['page_title'] = 'Allotment Return archive list';
        $this->load->helper('url');
        $this->load->view('stock_issue_allotment_return/archive',$data);
    }
    function deleteall()
    {
        unauthorise_permission(168,974);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_issue_allotment_return->deleteall($post['row_id']);
            $response = "Issue/allotment successfully deleted.";
            echo $response;
        }
    }
    public function delete($id="")
    {
        unauthorise_permission(168,974);
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_issue_allotment_return->delete($id);
           $response = "Issue/allotment successfully deleted.";
           echo $response;
       }
    }

    public function employee_list()
    {
          $this->load->model('general/general_model');
          $employee_type= $this->input->post('employee_type');
          $user_type_id= $this->input->post('user_type_id');
          
          if(!empty($employee_type)){
          $data['employee_list']= $this->stock_issue_allotment_return->get_employee($employee_type);
          }
        // print_r($data['employee_list']);
         $dropdown = '<option value="">Select Employee</option>'; 
        if(!empty($data['employee_list']))
          {
            $var='';
          foreach($data['employee_list'] as $employee_list)
          {
            if($user_type_id==$employee_list->id)
            {
               $var='selected="selected"';
            }
           
          $dropdown .= '<option value="'.$employee_list->id.'" '.$var.'>'.$employee_list->name.' ('.$employee_list->reg_no.')'.'</option>';
          }
        } 
        echo $dropdown; 
    }


    public function usertype_list()
    {
        $this->load->model('general/general_model');
        $user_type= $this->input->post('user_type');
        if(!empty($user_type)){
          $data['user_list']= $this->stock_issue_allotment_return->get_data_according_user($user_type);
          }
         if($user_type==1)
         {
                $dropdown = '<div class="col-md-4"><div class="row m-b-5"><div class="col-md-4"> <label>Employee Type</label></div><div class="col-md-8">';
                $dropdown.= '<select name="employee_type" onchange="employee_list_new(this.value);"><option value="">Select Employee</option>'; 
                if(!empty($data['user_list']))
                {
                $selected='';
                foreach($data['user_list'] as $user_list)
                {
                
                $dropdown .= '<option value="'.$user_list->ids.'">'.$user_list->emp_type.' ('.$user_list->reg_no.')'.'</option>';
                }
                } 
                $dropdown .='</select></div></div></div> <div class="col-md-4">  <div class="row m-b-5"><div class="col-md-4"><label>Employee Name</label></div><div class="col-md-8"> <select class="w-189px selectpicker" name="user_type_id" id="employee_list_n" onchange="appned_user_detail(1,this.value);" data-live-search="true"  ><option value="">Select</option></select></div></div></div> ';
                echo $dropdown; 
         }
         if($user_type==2)
         {
                $dropdown = '<div class="col-md-4"><div class="row m-b-5"><div class="col-md-4"> <label>Patient Name</label></div><div class="col-md-8">';
                $dropdown.= '<select name="user_type_id" class="selectpicker" onchange="appned_user_detail('.$user_type.',this.value);" data-live-search="true" ><option value="">Select Patient</option>'; 
                if(!empty($data['user_list']))
                {
                foreach($data['user_list'] as $user_list)
                {
                
                $dropdown .= '<option value="'.$user_list->ids.'" >'.$user_list->patient_name.' ('.$user_list->patient_code.')'.'</option>';
                }
                } 
                $dropdown .='</select></div></div></div>';
                echo $dropdown; 
         }
         if($user_type==3)
         {
              $dropdown = '<div class="col-md-4"><div class="row m-b-5"><div class="col-md-4"> <label>Doctor Name</label></div><div class="col-md-8">';
              $dropdown.= '<select name="user_type_id" class="selectpicker" onchange="appned_user_detail('.$user_type.',this.value);" data-live-search="true" ><option value="">Select Doctor</option>'; 
              if(!empty($data['user_list']))
              {
              foreach($data['user_list'] as $user_list)
              {
              
              $dropdown .= '<option value="'.$user_list->ids.'" >'.$user_list->doctor_name.'('.$user_list->doctor_code.')'.'</option>';
              }
              } 
              $dropdown .='</select></div></div></div>';
              echo $dropdown; 
         }
      
    }


//////added function 17-3-20 ////////

public function ajax_list_item()
{
       $item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
       $post = $this->input->post();  
       $ids=array();
       $table = '';
        if(!empty($item_list))
        { 
          $ids_arr= [];
          foreach($item_list as $key_m_arr=>$m_arr)
          {
             $ids_arr[] = $key_m_arr;
          }
          $item_ids = implode(',', $ids_arr);
          $data['item_new_list'] = $this->stock_issue_allotment_return->get_item_values($item_ids);
          if(!empty($data['item_new_list']))
          {
            foreach($data['item_new_list'] as $item_list){
                           $ids[]=$item_list->id;
           }
          }
           
        }
        $this->load->model('item_manage/item_manage_model','item_manage');
       
      
        $keywords= $this->input->post('search_keyword');
        $name= $this->input->post('name'); 
        if(!empty($post['item']) || !empty($post['item_code']) || !empty($post['company_name']) || !empty($post['conversion']) || !empty($post['mfc_date']) || !empty($post['qty']) || !empty($post['mrp']) || $post['mrp']==0 ||  !empty($post['discount']) || $post['discount']==0 || !empty($post['cgst']) || $post['cgst']==0 || !empty($post['igst']) || $post['igst']==0 || !empty($post['sgst']) || $post['sgst']==0 ||!empty($post['packing']))
        { 

        
          $result_item = $this->stock_issue_allotment_return->item_list_search();  

    //print_r($result_item); die;


        } 

        if(count($result_item)>0 && isset($result_item) || !empty($ids))
        {
          foreach($result_item as $item)
          {
              if(!in_array($item->id,$ids))
              {
                  $table.='<tr class="append_row">';
                  $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$item->id.'" onclick="add_check();"></td>';

                  $table.='<td>'.$item->item.'</a></td>';

                  $table.='<td>'.$item->packing.'</td>';
                  $table.='<td>'.$item->conversion.'</td>';
                  $table.='<td>'.$item->item_code.'</td>';
                  
                  $table.='<td>'.$item->company_name.'</td>';
                 
                  // $table.='<td>'.date('d-m-Y',strtotime($item->created_date)).'</td>';
                  $table.='<td>'.$item->qty.'</td>';

                  $table.='<td>'.$item->mrp.'</td>';
                 
                  $table.='<td>'.$item->discount.'</td>';
                  $table.='<td>'.$item->cgst.'</td>';
                  $table.='<td>'.$item->sgst.'</td>';
                  $table.='<td>'.$item->igst.'</td>';
                  $table.='</tr>';
              }
          }
        }
        else
        {
            $table.='<tr class="append_row"><td colspan="20" align="center" class="text-danger">No record found</td></tr>';
        }
               $output=array('data'=>$table);
               echo json_encode($output);
        }


       public function ajax_added_item(){
        //$this->load->model('item_manage/item_manage_model','item_manage');
         $item_sess = $this->session->userdata('stock_issue_allotment_return_item_list');
         $check_script="";
         $result_item = [];
        if(!empty($item_sess))
        { 
          $ids_arr= [];
          $purchase_arr= [];
          foreach($item_sess as $key_m_arr=>$m_arr)
          {
             $ids_arr[] = $key_m_arr;
             $purchase_arr[] = $m_arr['issue_return_id'];
          }
          $item_ids = implode(',', $ids_arr);
          $result_item = $this->stock_issue_allotment_return->item_list($item_ids);
           foreach($result_item as $item_list){
            //echo '<pre>';print_r($item_sess);die;

             $ids[]=$item_list->id;
           }
        }
     // $setting_data
                      $table='<div class="box_scroll table-bordered">';
                      $table.='<table class="table table-bordered table-striped m_pur_tbl1">';
                      $table.='<thead class="bg-theme">';
                      $table.='<tr>';
                      $table.='<th class="40" align="center">';
                       $table.='<input type="checkbox" name="" onClick="toggle_new(this);">';
                       $table.='</th>';
                        $table.='<th>Item Name</th>';
                        $table.='<th>Item Code</th>';
                       
                        $table.='<th>Packing</th>';
                    
                        $table.='<th>Conv.</th>';
                        $table.='<th>Mfg. Date</th>';
                        $table.='<th>Exp. Date</th>';
                       
                        $table.='<th>Qty</th>';
                        $table.='<th>Serial No.</th>';
                        $table.='<th>MRP</th>';
                       
                        $table.='<th>Discount</th>';
                        $table.='<th>CGST (%)</th>';
                        $table.='<th>SGST (%)</th>';
                        $table.='<th>IGST (%)</th>';
                    
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        if(count($result_item)>0 && isset($result_item) || !empty($ids)){
                        foreach($result_item as $item){


                             $serial_no=array();
                           $purchase_item_serial =$this->stock_issue_allotment_return->purchase_item_serial_by_id($purchase_arr[0],$item->id);
                          // echo "<pre>"; print_r($purchase_item_serial); die;
                            foreach ($purchase_item_serial as  $serial) 
                            {
                                array_push($serial_no, $serial->serial_no);
                            } 
                
                            $serial_data=implode(",", $serial_no);

                            if($item_sess[$item->id]["exp_date"]=="00-00-0000"){

                                $date_new=date('d-m-Y');;
                            }else{
                                $date_new=$item_sess[$item->id]["exp_date"];
                            }
                            if($item_sess[$item->id]["manuf_date"]=="00-00-0000"){

                                $date_newma=date('d-m-Y');
                            }else{
                                $date_newma=$item_sess[$item->id]["manuf_date"];
                            }
                            
                        $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$item->id."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate:  '".$date_new."',
                        });
                       
                        </script>";
/*$('#discount_".$item->id."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });*/
                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$item->id."').datepicker({
                              format: 'dd-mm-yyyy',
                              autoclose: true,
                              endDate: '".$date_newma."',
                            
                        });
                        
                          
                          $('#igst_".$item->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100');
                                 
                            }

                          });
                           $('#sgst_".$item->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100');
                                 
                            }
                            
                          });
                             $('#cgst_".$item->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100');
                                 
                            }
                            
                          });
                         
                        </script>";
                          //if(!in_array($item->id,$ids)){ 
                        $table.='<tr><input type="hidden" id="item_id_'.$item->id.'" name="item_id[]" value="'.$item->id.'"/>
                                 <input type="hidden" value="'.$item->conversion.'" name="conversion[]" id="conversion_'.$item->id.'" />
                                 <input type="hidden" value="'.$item->category_id.'" name="category_id[]" id="category_id_'.$item->id.'" />
                                 <input type="hidden" value="'.$item->item_code.'" name="item_code[]" id="item_code_'.$item->id.'" />'; 

                        $table.='<td><input type="checkbox" name="item_id[]" class="booked_checkbox" value="'.$item->id.'"></td>';
                        $table.='<td><input type="hidden" value="'.$item->item_name.'" name="item_name[]" id="item_name_'.$item->id.'" />'.$item->item_name.'</td>';
                        $table.='<td>'.$item->item_code.' </td>';

                        $table.='<td>'.$item->packing.'</td>';

                        $table.='<td>'.$item->conversion.'</td>';

                        $table.='<td><input type="text" value="'.$date_newma.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/>'.$check_script.'</td>';
                        
                        $table.='<td><input type="text" id="qty_'.$item->id.'" class="w-60px" name="qty[]" value="'.$item_sess[$item->id]["qty"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                        
                         if(empty($serial_data))
                        {
                            $nre ='';  
                        }
                        else
                        {
                            
                            $nre = json_encode($serial_data);  
                        }
                        
                        $table.="<td> <button id='add_serial_no' value='' class='btn-custom' type='button' onclick='return add_serial(".$item->id.",this.value,1)'> Add </button> </td><input type='hidden' name='serial_no_array[]' id='serial_no_array_".$item->id."' value='".$nre."'><input type='hidden' name='issued_ser_id_no_array[]' id='issued_ser_id_no_array_".$item->id."' value='".$nre."'>"; 

                       // $table.='<td></td>';
                        $table.='<td><input type="text" id="mrp_'.$item->id.'" class="w-60px" name="mrp[]" value="'.$item_sess[$item->id]["mrp"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                       
                       $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$item->id.'" value="'.$item_sess[$item->id]["discount"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';


                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$item_sess[$item->id]["cgst"].'" id="cgst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                         $table.='<td><input type="text" class="price_float" name="SGST[]" placeholder="SGST" style="width:59px;" value="'.$item_sess[$item->id]["sgst"].'" id="sgst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                          $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$item_sess[$item->id]["igst"].'" id="igst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        /*$table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$item->id.'" value="'.$item_sess[$item->id]["qty"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';*/
                      
                        $table.=' <td><input type="text" value="'.$item_sess[$item->id]["total_amount"].'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$item->id.'" /></td>';
                       
                        $table.='</tr>';
                          // }
                       }
                       }else{
                        $table.='<td colspan="20"  align="center" class="text-danger">No record found</td>';
                       }
                        $table.='</tbody>';
                        $table.='</table>';
                        $table.='</div>';
                        $table.='<div class="right">';
                        $table.='<a class="btn-new" onclick="item_list_vals();">Delete</a>';
                        $table.='</div>'; 
                     $output=array('data'=>$table);
                     echo json_encode($output);
        }


// public function check_stock_avability()
//    {
//       $id= $this->input->post('mbid');
//       $explode_ids= explode('.',$id);
//       $qty= $this->input->post('qty');
//       $return= $this->stock_issue_allotment_return->check_stock_avability($explode_ids[0]);
//       $tot_val= $qty;
//       if($return >= $tot_val){
//       echo '0'; 
//       }else{
//       echo '1';

//       }    
// }


 public function set_item()
    {
       $this->load->model('item_manage/item_manage_model','item_manage');
       
       $post =  $this->input->post();
       if(isset($post['item_id']) && !empty($post['item_id']))
       {
         $purchase = $this->session->userdata('stock_issue_allotment_return_item_list');
         $item_id = [];
         $iid_arr = [];
         if(isset($purchase) && !empty($purchase))
         { 
           $total_amount=0;
            $post_iid_arr = [];
            $i=0;
            foreach($post['item_id'] as $ids)
            {
               $item_data = $this->stock_issue_allotment_return->item_list($ids);

               //print_r($item_data); die;

                $ratewithunit1= $item_data[0]->mrp*0;
                $qty=0;
                if($item_data[0]->conversion>0)
                {
                  $perpic_rate=$item_data[0]->mrp/$item_data[0]->conversion;
                }
                else
                {
                  $perpic_rate=$item_data[0]->mrp;
                }
                

                $tot_qty_with_rate= $perpic_rate*0;

                $total_discount = ($item_data[0]->discount/100)*$tot_qty_with_rate;

                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $item_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;

                $post_iid_arr[$ids] = array('item_id'=>$ids,'qty'=>'0','item_name'=>$item_data[0]->item_name,'conversion'=>$item_data[0]->conversion,'perpic_rate'=>$perpic_rate,'manuf_date'=>'00-00-0000', 'exp_date'=>'00-00-0000','discount'=>$item_data[0]->discount,'mrp'=>$item_data[0]->mrp,'cgst'=>$item_data[0]->cgst,'igst'=>$item_data[0]->igst,'sgst'=>$item_data[0]->sgst, 'total_amount'=>$total_amount, 'category_id'=>$item_data[0]->category_id); 
                $iid_arr[] = $ids;
                $i++;
            } 
            
            $item_id = $purchase+$post_iid_arr;

         } 
         else
         {
            $total_amount=0;
            $i=0;

            foreach($post['item_id'] as $ids)
            {

              $item_data = $this->stock_issue_allotment_return->item_list($ids);

             // echo "<pre>" ; print_r($item_data); die;
              if($item_data[0]->conversion>0)
              {
                $perpic_rate=$item_data[0]->mrp/$item_data[0]->conversion;
              }
              else
              {
                $perpic_rate=$item_data[0]->mrp; 
              }
              
              
              $tot_qty_with_rate=$perpic_rate*1;
                
                $total_discount = ($item_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $item_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;

                $post_iid_arr[$ids] = array('item_id'=>$ids,'qty'=>'0','item_name'=>$item_data[0]->item_name,'conversion'=>0,'manuf_date'=>'00-00-0000','perpic_rate'=>$perpic_rate,'exp_date'=>'00-00-0000', 'mrp'=>$item_data[0]->mrp,'discount'=>$item_data[0]->discount,'cgst'=>$item_data[0]->cgst,'igst'=>$item_data[0]->igst,'sgst'=>$item_data[0]->sgst,'total_amount'=>$total_amount,'category_id'=>$item_data[0]->category_id); 
                $iid_arr[] = $ids;
                $i++;
            }
            $item_id = $post_iid_arr;

          //  echo '<pre>';print_r($item_id);die;
            
         } 
         $item_ids = implode(',',$iid_arr);
         $this->session->set_userdata('stock_issue_allotment_return_item_list',$item_id);
         $this->ajax_added_item();
       }
    }

     public function remove_item_list()
    {
        $this->load->model('item_manage/item_manage_model','item_manage');
      
       $post =  $this->input->post();
       if(isset($post['item_id']) && !empty($post['item_id']))
       {
           $ids_list = $this->session->userdata('stock_issue_allotment_return_item_list');
           
             foreach($post['item_id'] as $post_id)
             {
                  if(array_key_exists($post_id,$ids_list))
                  {
                     unset($ids_list[$post_id]);
                  }
             } 
             $this->session->set_userdata('stock_issue_allotment_return_item_list',$ids_list);
           
           $item_list = [];
           $ids_list = $this->session->userdata('stock_issue_allotment_return_item_list');  
         
           $this->ajax_added_item();
       }
    }

    

   public function payment_calc_all()
    { 
        $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $inventory_setting_data = $this->inventory_discount_setting_model->get_default_setting();
       $item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
      
       if(!empty($item_list))
       {
        $post = $this->input->post();

//echo "<pre>"; print_r($post);

        $discount_type=$post['discount_type'];
        $total_amount = 0;
        $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        $tot_discount=0.00;
        $tot_discount_amount=0.00;
        $totigst_amount=0;
        $totcgst_amount=0;
        $totsgst_amount=0;
        $total_discount =0;
        $net_amount =0; 
        $payamount=0;
        $mrp=0;
        $conversion=0;
        $total_amountwithigst=0;
        $total_amountwithigst=0;
        $newamountwithcgst=0; 
        $total_new_amount=0;
        $i=0;
        foreach($item_list as $item)
        {    
       
//print '<pre>'; print_r($item_list);die;
            if($item['conversion']>0)
            {
              $perpic_rate= $item['mrp']/$item['conversion'];
            }
            else
            {
              $perpic_rate= $item['mrp'];
            }
            
            $tot_qty_with_rate= $perpic_rate*$item['qty'];

            $total_new_other_amount= $tot_qty_with_rate-($tot_qty_with_rate/100)*$item['discount'];

             $total_new_amount= $total_new_amount+$tot_qty_with_rate;

            $totigst_amount = $item['cgst'];
            $total_amountwithigst= ($total_new_other_amount/100)*$totigst_amount;
            $total_cgst=$total_cgst+ $total_amountwithigst;

            $totcgst_amount = $item['sgst'];
            $total_amountwithcgst= ($totcgst_amount/100)*$total_new_other_amount;
            $total_sgst=$total_sgst+$total_amountwithcgst;
            
            $totsgst_amount = $item['igst']; 
            $total_amountwithsgst= ($total_new_other_amount/100)*$totsgst_amount; 
            $total_igst=$total_igst+ $total_amountwithsgst; 

            
            
            if(isset($inventory_setting_data[1]) && !empty($inventory_setting_data) && $inventory_setting_data[1]==1)
            {
               $tot_discount_amount+= $item['discount']; 
            }
            else
            {
               
               $tot_discount_amount+= ($tot_qty_with_rate)/100*$item['discount']; 
            }
            

            $total_amount = ($total_new_amount-$tot_discount_amount);
            //$total_amount = ($total_new_amount-$tot_discount_amount)+($total_cgst+$total_sgst+$total_igst);

            $i++;
        } 



     if($discount_type==1)
     {
       $total_discount_inr = $post['discount'] ;
       $total_discount = round($total_discount_inr);
     } 
     else
     {
        $total_discount_perc = ($post['discount']/100)* $total_amount;
        $total_discount = round($total_discount_perc);
     }

        $item_discount=$tot_discount_amount; //total item discount
        $net_amount = $total_amount-$total_discount;

        //if($post['data_id']!='')
        if($post['pay']==1 || $post['data_id']!='')
        {
           $payamount = $post['pay_amount'];

        }else{
          $payamount = $net_amount;
        }
         
        $blance_due = $net_amount-$payamount;

        $total_cgst = number_format($total_cgst,2,'.','');
        $total_igst = number_format($total_igst,2,'.','');
        $total_sgst = number_format($total_sgst,2,'.','');
        $payamount = number_format($payamount,2,'.','');
       

       $pay_arr = array(
        'total_amount'=>number_format($total_amount,2,'.',''),
        'net_amount'=>number_format($net_amount,2,'.',''),
        'pay_amount'=>number_format($payamount,2,'.',''),
        'discount'=>$post['discount'],
        'sgst_amount'=>$total_sgst,
        'igst_amount'=>$total_igst,
        'cgst_amount'=> $total_cgst,
        'balance_due'=>number_format($blance_due,2,'.',''),
        'discount_amount'=>number_format($total_discount,2,'.',''),
        'item_discount'=>number_format($item_discount,2,'.',''));
        $json = json_encode($pay_arr,true);

      //print_r($pay_arr); die;

        echo $json;die;
        
       }
        
    } 


 public function payment_cal_perrow()
    {
        
        $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $inventory_setting_data = $this->inventory_discount_setting_model->get_default_setting();
       $this->load->model('item_manage/item_manage_model','item_manage');
       $post = $this->input->post();
      // echo "<pre>"; print_r($post); exit;

       $total_amount='';
       if(isset($post) && !empty($post))
       {
         $total_amount = 0;
         $item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
     //print_r($item_list);die;
        
         if(!empty($item_list))
         { 
            $item_data = $this->stock_issue_allotment_return->item_list($post['item_id']);

           // print_r($ratewithunit1);die;
            if($post['conversion']>0)
            {
                $perpic_rate =  $post['mrp']/$post['conversion'];
            //print_r($perpic_rate);die;    

            $qty=$post['mrp']/$post['conversion'];
            }
            else
            {
              $perpic_rate =  $post['mrp'];
            //print_r($perpic_rate);die;    

            $qty=$post['qty'];
            }
            

            $tot_qty_with_rate = $perpic_rate*$post['qty'];
            //echo $tot_qty_with_rate;
            
            //echo $qty;
            
            
            if(isset($inventory_setting_data[1]) && !empty($inventory_setting_data) && $inventory_setting_data[1]==1)
            {
              $total_discount = $post['discount'];
            }
            else
            {
               
               $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            }
            

            $tot_price=$tot_qty_with_rate-$total_discount;

            $cgstToPay = ($tot_price / 100) * $post['cgst'];
            $igstToPay = ($tot_price / 100) * $post['igst'];
            $sgstToPay = ($tot_price / 100) * $post['sgst'];

            $total_amount = $total_amount+$tot_price;
            //$total_amount = $total_amount+$tot_price-($cgstToPay+$igstToPay+$sgstToPay);

            $item_list[$post['item_id']] =  array('item_id'=>$post['item_id'],'item_name'=>$post['item_name'],'qty'=>$post['qty'],'manuf_date'=>$post['manuf_date'],'conversion'=>$post['conversion'],'perpic_rate'=>$perpic_rate,'exp_date'=>$post['expiry_date'],'mrp'=>$post['mrp'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'cgst'=>$post['cgst'],'discount'=>$post['discount'], 'total_amount'=>$total_amount,'total_amount'=>$total_amount,'category_id'=>$post['category_id'],'item_code'=>$post['item_code']); 
            $this->session->set_userdata('stock_issue_allotment_return_item_list', $item_list);
            $pay_arr = array('total_amount'=>number_format($total_amount,2));
            $json = json_encode($pay_arr,true);
            echo $json;
         }
       }
    }


  function check_unique_value($invoice='', $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->stock_issue_allotment_return->check_unique_value($users_data['parent_id'], $invoice, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'This Invoice Number already Registered.');
            $response = false;
        }
        return $response;
    }
   


//////added function 18-3-20 ////////

  public function search_sales($vals="")
   {
        if(!empty($vals))
        {
            $result = $this->stock_issue_allotment_return->search_sales($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
public function get_sales_item()
    {
       $post =  $this->input->post();
       if(isset($post['item_id']) && !empty($post['item_id']))
       {
         $purchase = $this->session->userdata('stock_issue_allotment_return_item_list');
         $item_id = [];
         $iid_arr = [];
         if(!empty($post['item_id']))
         { 
          $this->load->model('stock_issue_allotment/stock_issue_allotment_model','stock_issue_allotment');

          $result = $this->stock_issue_allotment->get_by_id($post['item_id']); 
           $result_item_list = $this->stock_issue_allotment->get_purchase_to_purchase_by_id($post['item_id']);

//echo "<pre>"; print_r($result); die;

           $total_amount=0;
           $post_iid_arr = [];
           
            foreach($result_item_list as $items)
            {
              $post_iid_arr[$items['item_id']] = array('item_id'=>$items['item_id'],'qty'=>$items['qty'], 'exp_date'=>date('d-m-Y',strtotime($items['exp_date'])),'manuf_date'=>date('d-m-Y',strtotime($items['manuf_date'])),'discount'=>$items['discount'],'mrp'=>$items['mrp'],'conversion'=>$items['conversion'],'cgst'=>$items['cgst'],'sgst'=>$items['sgst'],'igst'=>$items['igst'],'total_amount'=>$items['total_amount'],'perpic_rate'=>$items['perpic_rate'],'category_id'=>$items['category_id']); 

            } 
             $item_id = $post_iid_arr;   

         // echo "<pre>"; print_r($item_id); exit;
 
            if(isset($purchase) && !empty($purchase))
              { 
                $item_id = $purchase+$post_iid_arr;
              } 
              else
              {
                $item_id = $post_iid_arr;
              }
           }

          $this->session->set_userdata('stock_issue_allotment_return_item_list',$item_id);
          $this->ajax_added_item();         
      }
   }



public function print_stock_issue_allotment_report($ids=""){

      $user_detail= $this->session->userdata('auth_users');
      $data['page_title'] = "Stock issue allotment return";
	  $this->load->model('stock_issue_allotment_return/stock_issue_allotment_return_model','stock_issue_allotment_return');
      $this->load->model('general/general_model');
      if(!empty($ids)){
      $allotment_id= $ids;
      }else{
      $allotment_id= $this->session->userdata('allotment_id');
      }
      $get_detail_by_id= $this->stock_issue_allotment_return->get_by_id($allotment_id);
      $get_by_id_data=$this->stock_issue_allotment_return->get_all_detail_print($allotment_id,$get_detail_by_id['branch_id']);
	  
  //print '<pre>';print_r($get_by_id_data);die;
       $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['payment_mode']);
      $template_format= $this->stock_issue_allotment_return->template_format(array('section_id'=>9,'types'=>1,'sub_section'=>2,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['payment_mode']= $get_payment_detail;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $this->load->view('stock_issue_allotment_return/print_template_stock_issue_allotment_return',$data);
  }
  
  
  
    function search_serial_no($vals="")
    {
        $post = $this->input->post();
        $issue_allot_id = $post['issue_allot_id'];
        $item_id = $post['item_id'];
        if(!empty($vals))
        {
            $result = $this->stock_issue_allotment_return->search_serial_no($vals,$issue_allot_id,$item_id);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

}


?>