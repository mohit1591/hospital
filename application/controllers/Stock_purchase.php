<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_purchase extends CI_Controller {
 
	function __construct() 
	{
  	parent::__construct();	
  	 auth_users();  
  	$this->load->model('stock_purchase/stock_purchase_model','stock_purchase');
  	$this->load->library('form_validation');
  }

    
  	public function index()
      {
        unauthorise_permission(165,952);
        $data['page_title'] = 'Purchase List'; 
        $this->session->unset_userdata('stock_purchase_item_list');  
        $this->session->unset_userdata('stock_item_payment_payment_array');
        $this->session->unset_userdata('stock_purchase_search'); 
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
        $this->load->view('stock_purchase/list',$data);
      }

    public function ajax_list()
    {  
        unauthorise_permission(165,952);
        $list = $this->stock_purchase->get_datatables();  
		// echo "<pre>"; print_r($list); exit;	
		
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
        foreach ($list as $stock_purchase) { 
            $no++;
            $row = array();
            if($stock_purchase->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($stock_purchase->state))
            {
                $state = " ( ".ucfirst(strtolower($stock_purchase->state))." )";
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
            $row[] = '<input type="checkbox" name="stock_purchase[]" class="checklist" value="'.$stock_purchase->id.'">';
            $row[] = $stock_purchase->purchase_no;
            $row[] = $stock_purchase->items;
            $row[] = date('d-m-Y',strtotime($stock_purchase->purchase_date));
            $row[] = $stock_purchase->name;
			$row[] = $stock_purchase->total_amount;
			$row[] = $stock_purchase->discount;
            $row[] = $stock_purchase->net_amount;
            $row[] = $stock_purchase->paid_amount;
            $row[] = $stock_purchase->balance;
			
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
          if(in_array('954',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_stock_purchase('.$stock_purchase->id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock_purchase->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
           }
            
           if(in_array('955',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_stock_purchase('.$stock_purchase->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';	
           }  
		   
 		    $print_url = "'".base_url('stock_purchase/print_stock_issue_purchase/'.$stock_purchase->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
			
            $row[] = $btnedit.$btndelete.$btnprint;
            
             $data[] = $row;
			
			  $grand_total_amount = $grand_total_amount+$stock_purchase->total_amount;
			  $grand_total_discount = $grand_total_discount + $stock_purchase->discount;
			  $grand_net_amount = $grand_net_amount + $stock_purchase->net_amount;
			  $grand_paid_amount = $grand_paid_amount + $stock_purchase->paid_amount;
			  $grand_balance_amount = $grand_balance_amount + $stock_purchase->balance;
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
              $data[] = $tot_row; 
           }
		   
            $i++;
          }
        

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_purchase->count_all(),
                        "recordsFiltered" => $this->stock_purchase->count_filtered(),
                        "data" => $data,
                );
         //output to json format
        echo json_encode($output);
    }

 
public function stock_purchase_excel()
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
     
		  $fields = array('Purchase No.','Items','Purchase Date','Vendor Name','Total Amount','Discount','Net Amount','Paid Amount','Balance');
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
 
         $list = $this->stock_purchase->search_report_data();
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
 
                array_push($rowData,$reports->purchase_no,$reports->items,date('d-m-Y',strtotime($reports->purchase_date)),$reports->name,number_format($reports->total_amount,2),number_format($reports->discount,2),$reports->net_amount,$reports->paid_amount,number_format($reports->balance,2));
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
                // added on 24-mar-20
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':H'.$row.'')->getFont()->setBold( true );
                 // // added on 24-mar-20
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=stock_purchase_report_".time().".xls");  
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
                ob_end_clean();
               $objWriter->save('php://output');
          } 
        
    }
	
	

public function stock_purchase_csv()
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
          $fields = array('Purchase No.','Items','Purchase Date','Vendor Name','Total Amount','Discount','Net Amount','Paid Amount','Balance');
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
         
            $list = $this->stock_purchase->search_report_data();
        
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
 
 
                   array_push($rowData,$reports->purchase_no,$reports->items,date('d-m-Y',strtotime($reports->purchase_date)),$reports->name,number_format($reports->total_amount,2),number_format($reports->discount,2),$reports->net_amount,$reports->paid_amount,number_format($reports->balance,2));
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
               // print_r($reports_data);die;
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
          header("Content-Disposition: attachment; filename=stock_purchas_report_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
                ob_end_clean();
               $objWriter->save('php://output');
          }
 
    }

    public function pdf_stock_purchase()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->stock_purchase->search_report_data();
        $this->load->view('stock_purchase/stock_purchase_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("stock_purchase_report_".time().".pdf");
    }
    public function print_stock_purchase()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->stock_purchase->search_report_data();
      $this->load->view('stock_purchase/stock_purchase_report_html',$data); 
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
        $this->session->unset_userdata('stock_purchase_search');
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
                                      "vendor_code"=>"",
                                      "purchase_no"=>"",
                                      "item"=>"",
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('stock_purchase_search', $marge_post);
          }
          $stock_purchase_search = $this->session->userdata('stock_purchase_search');
          if(isset($stock_purchase_search) && !empty($stock_purchase_search))
          {
              $data['form_data'] = $stock_purchase_search;
          }
          //$this->load->view('purchase/advance_search',$data);
        }


   public function add($ipd_id="",$patient_id="")
    {

 // echo '<pre>' ;   
 //print_r($_POST);die;

          unauthorise_permission(165,953);
          $post = $this->input->post();
          $purchase_code = generate_unique_id(27);

          $data['button_value'] = "Save";
          $this->load->model('general/general_model'); 
          $data['vendor_list'] = $this->stock_purchase->vendor_list();
          $data['payment_mode']=$this->general_model->payment_mode();
          $data['unit_list'] = $this->stock_purchase->unit_list();

          if(!isset($post) || empty($post))
          {
          $this->session->unset_userdata('item_id');  
          }

          $stock_purchase_item_list = $this->session->userdata('item_id');
          $data['item_id'] = $stock_purchase_item_list;
 
        if(!empty($stock_purchase_item_list))
        { 
          $item_id_arr = [];
          foreach($stock_purchase_item_list as $key=>$item_sess)
          {
             $item_id_arr[] = $key;
          } 
          $item_ids = implode(',', $item_id_arr);
          $data['stock_purchase_item_list'] = $this->stock_purchase->item_list($item_ids);
        }
         /* New */
     // echo "<pre>";print_r($data['stock_purchase_item_list']);die;

          $data['page_title'] = 'Stock purchase';
          $data['data_id']='';
          $data['stock_purchase_item_list']=$stock_purchase_item_list;
          $data['form_data']=array(
                                      'data_id'=>'',
                                      'purchase_code'=>$purchase_code,
                                      'purchase_date'=>date('d-m-Y'),
                                      'vendor_id'=>'',
                                      'payment_mode'=>'',
                                      'total_amount'=>'',
                                      'discount_amount'=>'',
                                      'pay_amount'=>'',
                                      'balance_due'=>'',
                                      'discount_percent'=>'',
                                      'net_amount'=>'',

                                      "field_name"=>'', 
                                      'item_discount'=>"",
                                      'igst_amount'=>'',
                                      'sgst_amount'=>'',
                                      'cgst_amount'=>'',
                                      'customer_code'=>'',
                                      'payment_due_date'=>date('d-m-Y'),
                                      


                                      );
        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
               $charge_id = $this->stock_purchase->save();
               //   echo $return_id;die; 
                $this->session->set_flashdata('success','Stock purchase Item added successfully.');
                redirect(base_url('stock_purchase'));
            }
            else
            {
                $data['form_error'] = validation_errors(); 
            }

          //print_r($data['form_error']);die;   

        }

        $this->load->view('stock_purchase/add',$data);
    }

    public function edit($id="")
    { 
          unauthorise_permission(165,954);
          $post = $this->input->post();
          $purchase_code = generate_unique_id(27);
          $this->load->model('general/general_model'); 
          $result= $this->stock_purchase->get_by_id($id);
          //print_r($result);die;
          $data['button_value'] = "Update";
          $data['unit_list'] = $this->stock_purchase->unit_list();  
          $data['vendor_list'] = $this->stock_purchase->vendor_list();
          $data['payment_mode']=$this->general_model->payment_mode();
          $data['page_title'] = 'Stock Purchase Update';
          $data['payment_mode']=$this->general_model->payment_mode();
          //echo $result['mode_payment'];die;
          $get_payment_detail= $this->stock_purchase->payment_mode_detail_according_to_field($result['payment_mode'],$id);

          //print_r($get_payment_detail);die;
          $total_values=array();
          for($i=0;$i<count($get_payment_detail);$i++) 
          {
            $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;
          }
        
        $item_id_arr=[];
        $result_item_list = $this->stock_purchase->get_purchase_to_purchase_by_id($id);
         //echo "<pre>";print_r($result_item_list);die;
        if(empty($post))
        { 
            $result_item_list = $this->stock_purchase->get_purchase_to_purchase_by_id($id); 
            //echo "<pre>"; print_r($result_item_list); exit;
            $this->session->set_userdata('item_id',$result_item_list);
        }
        $stock_purchase_item_list = $this->session->userdata('item_id');
        $data['item_id'] = $stock_purchase_item_list;
        if(empty($stock_purchase_item_list))
        { 
              $item_id_arr = [];
              foreach($stock_purchase_item_list  as $key=>$val)
              { 
                 $item_id_arr[] = $key;
              } 
              $item_ids = implode(',', $item_id_arr);
              $data['stock_purchase_item_list'] = $this->stock_purchase->item_list($item_ids); 
              //echo "<pre>";print_r($data['stock_purchase_item_list']);die;
         }
           $data['stock_purchase_item_list'] = $stock_purchase_item_list;  
           $data['form_data']=array('data_id'=>$result['id'],
                                      'purchase_code'=>$result['purchase_no'],
                                      'purchase_date'=>date('d-m-Y',strtotime($result['purchase_date'])),
                                      'vendor_id'=>$result['v_id'],
                                      'payment_mode'=>$result['payment_mode'],
                                      'total_amount'=>$result['total_amount'],
                                      'discount_amount'=>$result['discount'],
                                      "field_name"=>$total_values,
                                      'pay_amount'=>$result['paid_amount'],
                                      'balance_due'=>$result['balance'],
                                      'address'=>$result['address'],
                                      'vendor_code'=>$result['vendor_id'],
                                      'vendor_name'=>$result['name'],
                                      'discount_percent'=>$result['discount_percent'],
                                      'net_amount'=>$result['net_amount'],
                                      "field_name"=>'', 
                                      'item_discount'=>$result['item_discount'],
                                      'igst_amount'=>$result['igst'],
                                      'sgst_amount'=>$result['sgst'],
                                      'cgst_amount'=>$result['cgst'],
                                      'customer_code'=>$result['customer_code'],
                                      'payment_due_date'=>date('d-m-Y',strtotime($result['payment_due_date'])),
                                      );

        if(isset($post) && !empty($post))
        {   
          
        
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {

                $purchase_id = $this->stock_purchase->save();
                $this->session->set_userdata('purchase_no',$purchase_id);
                $this->session->set_flashdata('success','Stock purchase Item Updated successfully.');
                redirect(base_url('stock_purchase'));
            }
            else
            {
                $data['form_error'] = validation_errors(); 
                
                //print '<pre>'; print_r($data['form_error']);die;
            }
            //print_r($data['form_error']);die;
                
        }
        $this->load->view('stock_purchase/add',$data);
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

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        $this->form_validation->set_rules('pay_amount', 'Pay amount', 'trim|required'); 
        $this->form_validation->set_rules('vendor_id', 'vendor name', 'trim|required'); 
        
        if(isset($post['field_name']))
          {
             $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
          }
         
         $stock_purchase_item_list = $this->session->userdata('item_id');
         if(isset($post['field_name']))
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }

        if(isset($post['vendor_name']) && !empty($post['vendor_name']))
        {
          $vendor_name=$post['vendor_name'];
        }
        else
        {
           $vendor_name="";
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

        if ($this->form_validation->run() == FALSE) 
        {  

          if(isset($post['discount_percent'])){

              $discount_percent=$post['discount_percent'];
              }else{
              $discount_percent='';
              }

              if(isset($post['cgst_amount'])){

              $cgst_amount=$post['cgst_amount'];
              }else{
              $cgst_amount='';
              }

              if(isset($post['sgst_amount'])){

              $sgst_amount=$post['sgst_amount'];
              }else{
              $sgst_amount='';
              }

              if(isset($post['igst_amount'])){

              $igst_amount=$post['igst_amount'];
              }else{
              $igst_amount='';
              }
              if(isset($post['net_amount'])){

              $net_amount=$post['net_amount'];
              }else{
              $net_amount='';
              }
              if(isset($post['payment_mode'])){

              $payment_mode=$post['payment_mode'];
              }else{
              $payment_mode='';
              }

              if(isset($post['total_amount'])){

              $total_amount=$post['total_amount'];
              }else{
              $total_amount='';
              }
               if(isset($post['discount_amount'])){

              $discount_amount=$post['discount_amount'];
              }else{
              $discount_amount='';
              }

              if(isset($post['pay_amount'])){

              $pay_amount=$post['pay_amount'];
              }else{
              $pay_amount='';
              }
              
            
            
  
            $data['form_data'] = array(
                                      'data_id'=>$post['data_id'],
                                      'purchase_code'=>$post['purchase_code'],
                                      'purchase_date'=>$post['purchase_date'],
                                      'vendor_id'=>$post['vendor_id'],
                                      
                                      'total_amount'=>$post['total_amount'],
                                      'vendor_name'=>$vendor_name,
                                      'address'=> $address,
                                      'vendor_code'=>$vendor_code,
                                      'balance_due'=>$post['balance_due'],
                      
                                      "field_name"=>$total_values,
                                      'discount_percent'=>$post['discount_percent'],

                                      'discount_amount'=>$discount_amount,
                                      'payment_mode'=>$payment_mode,
                                      'net_amount'=>$net_amount,
                                      "purchase_time"=>@$post['purchase_time'],
                                      "remarks"=>@$post['remarks'],
                                      'igst_amount'=>$igst_amount,
                                      'sgst_amount'=>$sgst_amount,
                                      'cgst_amount'=>$cgst_amount,
                                      'pay_amount'=>$pay_amount,
                                      'customer_code'=>$post['customer_code'],
                                      'payment_due_date'=>$post['payment_due_date'],

                                      ); 

            return $data['form_data'];
        }   
    }


  public function archive_ajax_list()
    {
        unauthorise_permission(165,956);
        $this->load->model('stock_purchase/stock_purchase_archive_model','stock_purchase_archive'); 

        $list = $this->stock_purchase_archive->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $stock_purchase) { 
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

            $row[] = '<input type="checkbox" name="stock_purchase[]" class="checklist" value="'.$stock_purchase->id.'">'.$check_script;
            $row[] = $stock_purchase->purchase_no;
            $row[] = date('d-m-Y',strtotime($stock_purchase->purchase_date));
            $row[] = $stock_purchase->name;
            $row[] = $stock_purchase->net_amount;
            $row[] = $stock_purchase->paid_amount;
             $row[] = $stock_purchase->balance;
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           if(in_array('956',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_stock_purchase('.$stock_purchase->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('957',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$stock_purchase->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_purchase_archive->count_all(),
                        "recordsFiltered" => $this->stock_purchase_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
  function get_vandor_data($vendor_id="")
  {
    $data['vendor_list'] = $this->stock_purchase->vendor_list($vendor_id);
   
   $html='<div class="row m-b-5"> <div class="col-md-4"><label>Vendor Code</label></div><div class="col-md-8">
                  '.$data['vendor_list'][0]->vendor_id.'<input type="hidden" value="'.$data['vendor_list'][0]->vendor_id.'" name="vendor_code"/> </div></div> <div class="row m-b-5"> <div class="col-md-4">  <label>Name</label>
                </div> <div class="col-md-8">  '.$data['vendor_list'][0]->name.'<input type="hidden" value="'.$data['vendor_list'][0]->name.'" name="vendor_name"/></div></div>

                <div class="row m-b-5"> <div class="col-md-4">  <label>Mobile No.</label>
                </div> <div class="col-md-8">  '.$data['vendor_list'][0]->mobile.'<input type="hidden" value="'.$data['vendor_list'][0]->mobile.'" name="vendor_mobile"/></div></div>

                <div class="row m-b-5"> <div class="col-md-4">  <label>Email</label>
                </div> <div class="col-md-8">  '.@$data['vendor_list'][0]->vendor_email.'<input type="hidden" value="'.@$data['vendor_list'][0]->vendor_email.'" name="vendor_email"/></div></div>

                <div class="row m-b-5"><div class="col-md-4">
                  <label>Address</label></div><div class="col-md-8"> '.$data['vendor_list'][0]->address.'<input type="hidden" value="'.$data['vendor_list'][0]->address.', '.$data['vendor_list'][0]->address2.', '.$data['vendor_list'][0]->address3.'" name="address"/> </div></div>';
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

 // function get_item_values($vals="")
 //    {
 //        if(!empty($vals))
 //        {
 //            $result = $this->stock_purchase->get_item_values($vals);  
 //            if(!empty($result))
 //            {
 //              echo json_encode($result,true);
 //            } 

 //             //print_r($result); die;
 //        } 
 //    }


  function trashall()
    {
       unauthorise_permission(165,957);
        $this->load->model('stock_purchase/stock_purchase_archive_model','stock_purchase_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_purchase_archive->trashall($post['row_id']);
            $response = "Stock item purchase successfully deleted parmanently.";
            echo $response;
        }
    }
      public function trash($id="")
    {
        unauthorise_permission(165,957);
        $this->load->model('stock_purchase/stock_purchase_archive_model','stock_purchase_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_purchase_archive->trash($id);
           $response = "Stock item purchase successfully deleted parmanently.";
           echo $response;
       }
    }
    function restoreall()
    { 
       unauthorise_permission(165,956);
        $this->load->model('stock_purchase/stock_purchase_archive_model','stock_purchase_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_purchase_archive->restoreall($post['row_id']);
            $response = "Stock item purchase successfully restore in stock purchase list.";
            echo $response;
        }
    }
     public function restore($id="")
    {
       unauthorise_permission(165,956);
        $this->load->model('stock_purchase/stock_purchase_archive_model','stock_purchase_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_purchase_archive->restore($id);
           $response = "Stock item purchase successfully restore in stock purchase list.";
           echo $response;
       }
    }
     public function archive()
    {
       unauthorise_permission(165,956);
        $data['page_title'] = 'Stock Item purchase archive list';
        $this->load->helper('url');
        $this->load->view('stock_purchase/archive',$data);
    }
    function deleteall()
    {
        unauthorise_permission(165,955);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_purchase->deleteall($post['row_id']);
            $response = "Stock item purchase successfully deleted.";
            echo $response;
        }
    }
    public function delete($id="")
    {
        unauthorise_permission(165,955);
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_purchase->delete($id);
           $response = "Stock item purchase successfully deleted.";
           echo $response;
       }
    }


//add bottom 4 feb 20

public function ajax_list_item(){
       $item_list = $this->session->userdata('item_id');
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
          $data['item_new_list'] = $this->stock_purchase->get_item_values($item_ids);
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
        if(!empty($post['item']) || !empty($post['item_code']) || !empty($post['company_name']) || !empty($post['conversion']) || !empty($post['mfc_date']) || !empty($post['unit1']) ||  !empty($post['unit2']) || !empty($post['mrp']) || $post['mrp']==0 || !empty($post['p_rate']) || !empty($post['discount']) || $post['discount']==0 || !empty($post['cgst']) || $post['cgst']==0 || !empty($post['igst']) || $post['igst']==0 || !empty($post['sgst']) || $post['sgst']==0 ||!empty($post['packing']))
        { 

        
          $result_item = $this->stock_purchase->item_list_search();  

      //print_r($result_item); die;


        } 

        if(count($result_item)>0 && isset($result_item) || !empty($ids))
        {
          foreach($result_item as $item)
          {
              if(!in_array($item->id,$ids))
              {
                  if(!empty($item->conversion))
                  {
                      $conver = $item->conversion;
                  }
                  else
                  {
                      $conver = 1;
                  }
                  $table.='<tr class="append_row">';
                  $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$item->id.'" onclick="add_check();"></td>';

                  $table.='<td>'.$item->item.'</a></td>';

                  $table.='<td>'.$item->packing.'</td>';
                  $table.='<td>'.$conver.'</td>';
                  $table.='<td>'.$item->item_code.'</td>';
                  
                  $table.='<td>'.$item->company_name.'</td>';
                 
                  // $table.='<td>'.date('d-m-Y',strtotime($item->created_date)).'</td>';
                  if($item->type==3){
                  $table.='<td>'.$item->unit1.'</td>';} 
                  else {
                  $table.='<td>'.$item->unit_id.'</td>';}

                  if($item->type==3){
                  $table.='<td>'.$item->unit2.'</td>';} 
                  else {
                  $table.='<td>'.$item->second_unit.'</td>';}

                  $table.='<td>'.$item->mrp.'</td>';
                  $table.='<td>'.@$item->purchase_rate.'</td>';
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


public function ajax_added_item()
{
        //$this->load->model('item_manage/item_manage_model','item_manage');
         $item_sess = $this->session->userdata('item_id');
         //echo "<pre>"; print_r($item_sess); die;
         $check_script="";
         $result_item = [];
        if(!empty($item_sess))
        { 
          $ids_arr= [];
          $purchase_arr= [];
          foreach($item_sess as $key_m_arr=>$m_arr)
          {
             // echo "<pre>"; print_r($m_arr); die;
             $ids_arr[] = $key_m_arr;
            $purchase_arr[] = @$m_arr['purchase_id'];
             
          }
          $item_ids = implode(',', $ids_arr);
          $result_item = $this->stock_purchase->item_list($item_ids);
           foreach($result_item as $item_list)
           {
                $ids[]=$item_list->id;
           }
        }
        
       // echo "<pre>"; print_r($purchase_arr[0]); die;
         // $setting_data
                      $table='<div class=" box_scroll">';
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
                       
                        $table.='<th>Unit1</th>';
                        $table.='<th>Unit2</th>';
                        $table.='<th>Serial No.</th>';
                         $table.='<th>Free Unit1</th>';
                        $table.='<th>Free Unit2</th>';
                
                        $table.='<th>MRP</th>';
                        $table.='<th>Purchase Rate</th>';
                        $table.='<th>Discount</th>';
                        $table.='<th>CGST (%)</th>';
                        $table.='<th>SGST (%)</th>';
                        $table.='<th>IGST (%)</th>';
                    
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        if(count($result_item)>0 && isset($result_item) || !empty($ids)){
                        foreach($result_item as $item)
                        {
                            if(!empty($item->conversion))
                            {
                                $conver = $item->conversion;
                            }
                            else
                            {
                                $conver = 1;
                            }
                           // echo $item->purchase_id; echo $item->id; die;
                           $serial_no=array();
                           $purchase_item_serial =$this->stock_purchase->purchase_item_serial_by_id($purchase_arr[0],$item->id);
                          // echo "<pre>"; print_r($purchase_item_serial); die;
                            foreach ($purchase_item_serial as  $serial) 
                            {
                                array_push($serial_no, $serial->serial_no);
                            } 
                
                            $serial_data=implode(",", $serial_no);
                            //echo "<pre>"; print_r($serial_data); die;
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
                        $table.='<tr>
						<input type="hidden" id="item_id_'.$item->id.'" name="item_id[]" value="'.$item->id.'"/>
						 <input type="hidden" value="'.$item->item_code.'"  name="item_code[]" id="item_code_'.$item->id.'" />
                            <input type="hidden" value="'.$conver.'"  name="conversion[]" id="conversion_'.$item->id.'" />';
                        $table.='<td><input type="checkbox" name="item_id[]" class="booked_checkbox" value="'.$item->id.'"></td>';
                        $table.='<td><input type="hidden" value="'.$item->item.'" name="item[]" id="item_'.$item->id.'" />'.$item->item.'</td>';
                        $table.='<td>'.$item->item_code.' </td>';

                        $table.='<td>'.$item->packing.'</td>';

                        $table.='<td>'.$conver.'<input type="hidden" name="conversion[]" id="conversion_'.$item->id.'" value="'.$conver.'"/></td>';

                        $table.='<td><input type="text" value="'.$date_newma.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/>'.$check_script.'</td>';
              
                        $table.='<td><input type="text" name="unit1[]" placeholder="Unit1" style="width:59px;" id="unit1_'.$item->id.'" value="'.$item_sess[$item->id]["unit1"].'" onkeyup="payment_cal_perrow('.$item->id.');"/>
                        <span id="unit1_max_'.$item->id.'" class="text-danger"></span></td>';
                        $table.='<td><input type="text" name="unit2[]" placeholder="Unit2" style="width:59px;" id="unit2_'.$item->id.'" value="'.$item_sess[$item->id]["unit2"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                        if(empty($serial_data))
                        {
                            $nre ='';  
                            $nre_d='';
                        }
                        else
                        {
                            
                            $nre = json_encode($serial_data); 
                            $nre_d = $serial_data; 
                            
                        }
                        
                        $table.="<td> <button id='add_serial_no' value='' class='btn-custom' type='button' onclick='return add_serial(".$item->id.",this.value,1)'> Add </button> </td><input type='hidden' name='serial_no_array[$item->id][]' id='serial_post_no_array_".$item->id."' value='".$nre_d."'><input type='hidden' id='serial_no_array_".$item->id."' value='".$nre."'>"; 
                        
                          

                        $table.='<td><input type="text" name="freeunit1[]" placeholder="Free Unit1" style="width:59px;" id="freeunit1_'.$item->id.'" value="'.$item_sess[$item->id]["freeunit1"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                        $table.='<td><input type="text" name="freeunit2[]" placeholder="Free Unit2" style="width:59px;" id="freeunit2_'.$item->id.'" value="'.$item_sess[$item->id]["freeunit2"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                       // $table.='<td></td>';
                        $table.='<td><input type="text" id="mrp_'.$item->id.'" class="w-60px" name="mrp[]"  value="'.$item_sess[$item->id]["mrp"].'" onkeyup="payment_cal_perrow('.$item->id.');"/><span id="mrp_error_'.$item->id.'" class="text-danger" style="display:none;"></span></td>';

                        $table.='<td><input type="text" id="purchase_rate_'.$item->id.'" class="w-60px"  name="purchase_rate[]" value="'.$item_sess[$item->id]["purchase_rate"].'" onkeyup="payment_cal_perrow('.$item->id.');"/><span id="purchase_rate_error_'.$item->id.'" class="text-danger" style="display:none;"></span></td>';

                        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$item->id.'" value="'.$item_sess[$item->id]["discount"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                       /* $table.='<td><input type="text" class="price_float" name="vat[]" placeholder="Vat" style="width:59px;" value="'.$item_sess[$item->id]["vat"].'" id="vat_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';*/
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





 public function set_item()
    {
       $this->load->model('item_manage/item_manage_model','item_manage');
       
       $post =  $this->input->post();
       if(isset($post['item_id']) && !empty($post['item_id']))
       {
         $purchase = $this->session->userdata('item_id');
         $item_id = [];
         $iid_arr = [];
         if(isset($purchase) && !empty($purchase))
         { 
           $total_amount=0;
            $post_iid_arr = [];
            $i=0;
            foreach($post['item_id'] as $ids)
            {
               $item_data = $this->stock_purchase->item_list($ids);

 //print_r($item_data); die;

                $ratewithunit1= $item_data[0]->purchase_rate*0;
                $qty=0;
                //$ratewithunit1= $item_data[0]->purchase_rate*$post['unit1'];
                $perpic_rate=$item_data[0]->purchase_rate/$item_data[0]->conversion;
                $ratewithunit2=$perpic_rate*0;
                $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;

               /* $cgstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;
                $total_discount = ($item_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/

                 $total_discount = ($item_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $item_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;

                $post_iid_arr[$ids] = array('item_id'=>$ids,'unit1'=>0,'unit2'=>0,'item'=>0,'item_code'=>$item_data[0]->item_code,'conversion'=>0,'perpic_rate'=>$perpic_rate,'manuf_date'=>'00-00-0000','freeunit1'=>0,'freeunit2'=>0,'qty'=>'1','freeqty'=>'1', 'exp_date'=>'00-00-0000','discount'=>$item_data[0]->discount,'mrp'=>$item_data[0]->mrp,'cgst'=>$item_data[0]->cgst,'igst'=>$item_data[0]->igst,'sgst'=>$item_data[0]->sgst,'qty'=>$qty,'purchase_amount'=>$item_data[0]->purchase_rate,'purchase_rate'=>$item_data[0]->purchase_rate, 'total_amount'=>$total_amount); 
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

              $item_data = $this->stock_purchase->item_list($ids);


              $ratewithunit1= $item_data[0]->purchase_rate*0;
              //$ratewithunit1= $item_data[0]->purchase_rate*$post['unit1'];
              $qty=0;
              $freeqty=0;
              $perpic_rate=$item_data[0]->purchase_rate/$item_data[0]->conversion;
              $ratewithunit2=$perpic_rate*0;
              $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                
                /*$cgstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;
                $total_discount = ($item_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/

                $total_discount = ($item_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $item_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;



                $item_data = $this->stock_purchase->item_list($ids);

//echo '<pre>';print_r($item_data);die;
   

                $post_iid_arr[$ids] = array('item_id'=>$ids,'unit1'=>0,'unit2'=>0,'item'=>$item_data[0]->item,'item_code'=>$item_data[0]->item_code,'conversion'=>0,'batch_no'=>0,'manuf_date'=>'00-00-0000','perpic_rate'=>$perpic_rate,'freeunit1'=>0,'freeunit2'=>0,'qty'=>'1', 'exp_date'=>'00-00-0000', 'purchase_amount'=>$item_data[0]->purchase_rate,'purchase_rate'=>$item_data[0]->purchase_rate,'mrp'=>$item_data[0]->mrp,'qty'=>$qty,'freeqty'=>$freeqty,'discount'=>$item_data[0]->discount,'cgst'=>$item_data[0]->cgst,'igst'=>$item_data[0]->igst,'sgst'=>$item_data[0]->sgst,'total_amount'=>$total_amount); 
                $iid_arr[] = $ids;
                $i++;
            }
            $item_id = $post_iid_arr;
            
         } 
         $item_ids = implode(',',$iid_arr);
         $this->session->set_userdata('item_id',$item_id);
   // print_r($this->session->userdata('item_id'));
         $this->ajax_added_item();
       }
    }

     public function remove_item_list()
    {
        $this->load->model('item_manage/item_manage_model','item_manage');
      
       $post =  $this->input->post();
       if(isset($post['item_id']) && !empty($post['item_id']))
       {
           $ids_list = $this->session->userdata('item_id');
           
             foreach($post['item_id'] as $post_id)
             {
                  if(array_key_exists($post_id,$ids_list))
                  {
                     unset($ids_list[$post_id]);
                  }
             } 
             $this->session->set_userdata('item_id',$ids_list);
           
           $item_list = [];
           $ids_list = $this->session->userdata('item_id');  
         
           $this->ajax_added_item();
       }
    }

   public function payment_calc_all()
    { 
       // $post = $this->input->post();
       // echo "<pre>"; print_r($post); exit;

       $item_list = $this->session->userdata('item_id');
      //echo "<pre>"; print_r($item_list);die;
      $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $inventory_setting_data = $this->inventory_discount_setting_model->get_default_setting();
       if(!empty($item_list))
       {
        $post = $this->input->post();
        $discount_type=$post['discount_type'];
       // echo "<pre>"; print_r($post); exit;
        $total_amount = 0;
        $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        $tot_discount=0;
        $tot_discount_amount=0;
       // $totigst_amount=0;
       // $totcgst_amount=0;
       // $totsgst_amount=0;
        $total_discount =0;
        $totsgst_amount=0;
        $net_amount =0; 
        $payamount=0; 
        $purchase_amount=0;
        $total_amountwithigst=0;
       //$newamountwithigst=0;
        $total_amountwithigst=0;
       //$total_amountwithigst=0;
        $newamountwithcgst=0;
        //$newamountwithsgst=0;
        $total_new_amount=0;

        //print '<pre>'; print_r($item_list);die;
        $i=0;
        //echo "<pre>";print_r($item_list);die;
        foreach($item_list as $item)
        {    
          //echo "<pre>";print_r($item);die;
          if($item['purchase_id']!="")
          {
          $signal_unit1_price = $item['purchase_rate']*$item['unit1'];
          if(!empty($item['conversion']))
              {
                 $conversion =$item['conversion'];
              }
              else
              {
                  $conversion=1;
              }
              $signal_unit2_price = ($item['purchase_rate']/$conversion)*$item['unit2'];
              
          //$signal_unit2_price = ($item['purchase_rate']/$item['conversion'])*$item['unit2'];
          }
          else{
            $signal_unit1_price = $item['purchase_rate']*$item['unit1'];
            if($item['conversion']>0)
            {
              $signal_unit2_price = ($item['purchase_rate']/$item['conversion'])*$item['unit2'];
            }
            else
            {
                $signal_unit2_price = $item['unit2'];
            }
            
            }
            $total_amount += $signal_unit1_price+$signal_unit2_price;
            $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$item['discount']);
            $total_cgst += ($total_row_amount/100)*$item['cgst']; 
            $total_sgst += ($total_row_amount/100)*$item['sgst'];
            $total_igst += ($total_row_amount/100)*$item['igst']; 
            
            //row wise discount
            if(isset($inventory_setting_data[1]) && !empty($inventory_setting_data) && $inventory_setting_data[1]==1)
            {
               $tot_discount_amount+= $item['discount'];
            }
            else
            {
               
               $tot_discount_amount+= ($signal_unit1_price+$signal_unit2_price)/100*$item['discount'];
            }
            
            $i++;
        } 


            /*if($post['discount']!='' && $post['discount']!=0)
            {
              $total_discount_perc = ($post['discount']/100)* $total_amount;
              $total_discount = round($total_discount_perc);
            }*/
            
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

            
            
            $total_medicine_discount=$tot_discount_amount; //total item discount

            $net_amount = ($total_amount-$total_discount)+$total_cgst+$total_igst+$total_sgst-$tot_discount_amount;
            if($post['pay']==1 || $post['data_id']!=''){
            $payamount=$post['pay_amount'];
            }else{
            $payamount=$net_amount;
            }
         
      
            $blance_due=$net_amount - $payamount;
            $total_igst = number_format($total_igst,2,'.','');
            $total_igst = number_format($total_igst,2,'.','');
            $total_sgst = number_format($total_sgst,2,'.','');
            $igst=0;
            if(!empty($post['igst']) && $post['igst']!='undefined')
            {
              $igst = $post['igst'];
            }
            $sgst=0;
            if(!empty($post['sgst']) && $post['sgst']!='undefined')
            {
              $sgst = $post['sgst'];
            }
            $cgst=0;
            if(!empty($post['cgst']) && $post['cgst']!='undefined')
            {
              $cgst = $post['cgst'];
            }

       

        $pay_arr = array('total_amount'=>round($total_amount), 'net_amount'=>round($net_amount),'pay_amount'=>round($payamount),'discount'=>$post['discount'],'igst'=>$igst,'sgst'=>$sgst,'cgst'=>$cgst,'sgst_amount'=>$total_sgst,'igst_amount'=>$total_igst,'cgst_amount'=> $total_cgst,'balance_due'=>round($blance_due),'discount_amount'=>number_format($total_discount,2,'.',''),'item_discount'=>number_format($total_medicine_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 


 public function payment_cal_perrow()
 {
       $this->load->model('item_manage/item_manage_model','item_manage');

     //$post = $this->input->post();
     //echo "<pre>"; print_r($post); exit;
     $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $inventory_setting_data = $this->inventory_discount_setting_model->get_default_setting();
        

       $post = $this->input->post();
       $total_amount='';
       if(isset($post) && !empty($post))
       {
         $total_amount = 0;
         $item_list = $this->session->userdata('item_id');
     //print_r($item_list);die;
        
         if(!empty($item_list))
         { 
            $item_data = $this->stock_purchase->item_list($post['item_id']);



            $ratewithunit1= $post['purchase_rate']*$post['unit1'];
           // print_r($ratewithunit1);die;
            if($post['conversion']>0){
            $perpic_rate=  $post['purchase_rate']/$post['conversion'];
            }
            else
            {
              $perpic_rate=  $post['purchase_rate'];
            }
            //print_r($perpic_rate);die;
            //echo $perpic_rate;
            $ratewithunit2=$perpic_rate*$post['unit2'];
            $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
            //echo $tot_qty_with_rate;
            //$tot_qty_with_rate= $item_data[0]->purchase_rate*$post['unit1'];
            if($post['conversion']>0)
            {
              $qty=($post['conversion']*$post['unit1'])+$post['unit2'];
              $freeqty=($post['conversion']*$post['freeunit1'])+$post['freeunit2'];
            }
            else
            {
              $qty=$post['unit1']+$post['unit2'];
              $freeqty=$post['freeunit1']+$post['freeunit2'];
            }
            
            
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
            $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;



             $item_list[$post['item_id']] =  array('item_id'=>$post['item_id'],'item'=>$post['item'],'item_code'=>$post['item_code'],'freeunit1'=>$post['freeunit1'],'freeunit2'=>$post['freeunit2'],'unit1'=>$post['unit1'],'unit2'=>$post['unit2'],'manuf_date'=>$post['manuf_date'],'conversion'=>$post['conversion'],'perpic_rate'=>$perpic_rate,'exp_date'=>$post['expiry_date'],'qty'=>$qty,'freeqty'=>$freeqty,'mrp'=>$post['mrp'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'purchase_amount'=>$post['purchase_rate'],'purchase_rate'=>$post['purchase_rate'], 'total_amount'=>$total_amount,'total_price'=>$total_amount); 
            $this->session->set_userdata('item_id', $item_list);
            $pay_arr = array('total_amount'=>round($total_amount));
            $json = json_encode($pay_arr,true);
            echo $json;
         }
       }
    }


  function check_unique_value($invoice='', $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->stock_purchase->check_unique_value($users_data['parent_id'], $invoice, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'This Invoice Number already Registered.');
            $response = false;
        }
        return $response;
    }

  /********** print**************/  
  public function print_stock_issue_purchase($ids=""){

      $user_detail= $this->session->userdata('auth_users');
      $data['page_title'] = "Stock issue purchase";
	  $this->load->model('stock_purchase/stock_purchase_model','stock_purchase');
      $this->load->model('general/general_model');
      if(!empty($ids)){
      $purchase= $ids;
      }else{
      $purchase= $this->session->userdata('purchase');
      }
      $get_detail_by_id= $this->stock_purchase->get_by_id($purchase);
      $get_by_id_data=$this->stock_purchase->get_all_detail_print($purchase,$get_detail_by_id['branch_id']);
	  
  //print '<pre>';print_r($get_by_id_data);die;
       $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['payment_mode']);
      $template_format= $this->stock_purchase->template_format(array('section_id'=>9,'types'=>1,'sub_section'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['payment_mode']= $get_payment_detail;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $this->load->view('stock_purchase/print_template_stock_purchase',$data);
  }
  
  function get_item_values($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->stock_purchase->get_item_values($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }


}


?>