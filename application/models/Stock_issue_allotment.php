<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_issue_allotment extends CI_Controller {
 
	function __construct() 
	{
  	parent::__construct();	
  	 auth_users();  
  	$this->load->model('stock_issue_allotment/stock_issue_allotment_model','stock_issue_allotment');
  	$this->load->library('form_validation');
  }

    
  	public function index()
      {
        unauthorise_permission(58,385);
        $data['page_title'] = 'Allotment list'; 
        $this->session->unset_userdata('stock_issue_allotment_item_list');  
        $this->session->unset_userdata('stock_issue_allotment_payment_array');
        $this->session->unset_userdata('stock_issue_allotment_search'); 
        $data['form_data'] = array('purchase_no'=>'','invoice_id'=>'','paid_amount_to'=>'','paid_amount_from'=>'','balance_to'=>'','balance_from'=>'','start_date'=>date('d-m-Y'), 'end_date'=>date('d-m-Y'));
        $this->load->view('stock_issue_allotment/list',$data);
      }

    public function ajax_list()
    {  
        unauthorise_permission(56,367);
        $list = $this->stock_issue_allotment->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $stock_issue_allotment) { 
            $no++;
            $row = array();
            if($stock_issue_allotment->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }

            ///// State name ////////
            $state = "";
            if(!empty($stock_issue_allotment->state))
            {
                $state = " ( ".ucfirst(strtolower($stock_issue_allotment->state))." )";
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
            $row[] = '<input type="checkbox" name="stock_issue_allotment[]" class="checklist" value="'.$stock_issue_allotment->id.'">'.$check_script;
            $row[] = $stock_issue_allotment->issue_no;
            $row[] = $stock_issue_allotment->member_name;
            $row[] = $stock_issue_allotment->total_amount;
            $row[] = date('d-m-Y',strtotime($stock_issue_allotment->issue_date));
           // $row[] = $stock_issue_allotment->paid_amount;
            //$row[] = $stock_issue_allotment->balance;
          // $row[] = $stock_issue_allotment->discount;
             $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           //if(in_array('369',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_stock_purchase('.$stock_issue_allotment->id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock_issue_allotment->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
           // }
            //if(in_array('374',$users_data['permission']['action'])){
               // $btnview=' <a class="btn-custom" onclick="return view_medicine_entry('.$stock_issue_allotment->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
           
           // if(in_array('370',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_stock_purchase('.$stock_issue_allotment->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            //}       
            $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
          }
        

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_issue_allotment->count_all(),
                        "recordsFiltered" => $this->stock_issue_allotment->count_filtered(),
                        "data" => $data,
                );
         //output to json format
        echo json_encode($output);
    }

    public function stock_allotment_issue_excel()
    {
         $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Issue Code','Patient Name/Employee Name/ Doctor Name','Total Amount','Issue Date','Created Date');
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
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

               $col++;
          }
          $list = $this->stock_issue_allotment->search_report_data();

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->issue_no,$reports->member_name,$reports->total_amount,date('d-m-Y',strtotime($reports->issue_date)), date('d-M-Y H:i A',strtotime($reports->created_date)));
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
               foreach($data as $boking_data)
               {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=stock_allotment_issue_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

     public function stock_allotment_issue_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $fields  = array('Issue Code','Patient Name/Employee Name/ Doctor Name','Total Amount','Issue Date','Created Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->stock_issue_allotment->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                       array_push($rowData,$reports->issue_no,$reports->member_name,$reports->total_amount,date('d-m-Y',strtotime($reports->issue_date)), date('d-M-Y H:i A',strtotime($reports->created_date)));
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
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
         header("Content-Disposition: attachment; filename=stock_allotment_issue_report_".time().".csv");  
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }

    }

    public function pdf_stock_allotment_issue()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->stock_issue_allotment->search_report_data();
        $this->load->view('stock_issue_allotment/stock_allotment_issue_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("stock_allotment_issue_report_".time().".pdf");
    }
    public function print_stock_allotment_issue()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->stock_issue_allotment->search_report_data();
      $this->load->view('stock_issue_allotment/stock_allotment_issue_report_html',$data); 
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
        $this->session->unset_userdata('stock_issue_allotment_search');
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
              $this->session->set_userdata('stock_issue_allotment_search', $marge_post);
          }
          $stock_issue_allotment_search = $this->session->userdata('stock_issue_allotment_search');
          if(isset($stock_issue_allotment_search) && !empty($stock_issue_allotment_search))
          {
              $data['form_data'] = $stock_issue_allotment_search;
          }
          //$this->load->view('purchase/advance_search',$data);
        }

    public function add($ipd_id="",$patient_id="")
    {
          //print_r($_POST);die;
          unauthorise_permission(58,385);
          $post = $this->input->post();
          $issue_code = generate_unique_id(30);
          $this->load->model('general/general_model'); 
          $data['vendor_list'] = $this->stock_issue_allotment->vendor_list();
          $data['payment_mode']=$this->general_model->payment_mode();
          $this->load->model('employee/employee_model');
          $data['type_list'] = $this->employee_model->employee_type_list();
          if(isset($post['user_type']))
          {
            $data['user_list']= $this->stock_issue_allotment->get_data_according_user($post['user_type']);
          
          }
          if(!isset($post) || empty($post))
          {
          $this->session->unset_userdata('stock_items_data');  
          }
          $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');

          $data['page_title'] = 'Allotment';
          $data['data_id']='';
          $data['stock_issue_allotment_item_list']=$stock_issue_allotment_item_list;
          $data['form_data']=array(
                                      'data_id'=>'',
                                      'issue_code'=>$issue_code,
                                      'issue_date'=>date('d-m-Y'),
                                      'user_type'=>1,
                                      'employee_type'=>'',
                                      'user_type_id'=>'',
                                      //'payment_mode'=>'',
                                      'total_amount'=>'',
                                      "field_name"=>'',
                                      'user_name'=>'',
                                      'address'=>'',
                                     // 'discount_amount'=>'',
                                      //'pay_amount'=>'',
                                      //'balance_due'=>'',
                                      //'discount_percent'=>'',
                                      //'net_amount'=>''
                                      );
        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
               $charge_id = $this->stock_issue_allotment->save();
                $this->session->set_flashdata('success','Allotment added successfully.');
                redirect(base_url('stock_issue_allotment'));
            }
            else
            {
                $data['form_error'] = validation_errors(); 
            }
           //print_r($data['user_list']);die;
                
        }
        $this->load->view('stock_issue_allotment/add',$data);
    }

    public function edit($id="")
    {

          
          unauthorise_permission(58,385);
          $post = $this->input->post();
          $purchase_code = generate_unique_id(27);
          $this->load->model('general/general_model'); 
          $result= $this->stock_issue_allotment->get_by_id($id);
         
          
          $data['vendor_list'] = $this->stock_issue_allotment->vendor_list();
          $data['payment_mode']=$this->general_model->payment_mode();
          $data['page_title'] = 'Allotment';
          $data['data_id']='';
          
          $data['payment_mode']=$this->general_model->payment_mode();
          //echo $result['mode_payment'];die;
          $get_payment_detail= $this->stock_issue_allotment->payment_mode_detail_according_to_field($result['payment_mode'],$id);
          
            $total_values='';
            for($i=0;$i<count($get_payment_detail);$i++) {
            $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

            }
           
            $new_purchase_list = $this->session->userdata('stock_issue_allotment_item_list');
           
            if(count($new_purchase_list)>=1)
            {
             $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');
             
            }
            else
            {
              $this->session->unset_userdata('stock_issue_allotment_item_list'); 
              $stock_issue_allotment_item_list= $this->stock_issue_allotment->get_purchase_to_purchase_by_id($id);
             //print '<pre>'; print_r($stock_issue_allotment_item_list);die;
              $this->session->set_userdata('stock_issue_allotment_item_list',$stock_issue_allotment_item_list);
            
            }
            //print '<pre>'; print_r($stock_issue_allotment_item_list);die;
          // if(!isset($post) && empty($post) ||count($stock_issue_allotment_item_list)>=1)
          // {
          //   $stock_issue_allotment_item_list= $this->stock_issue_allotment->get_purchase_to_purchase_by_id($id);
          // $this->session->set_userdata('stock_issue_allotment_item_list',$stock_issue_allotment_item_list);
          // }
         
          // $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');
         if(isset($result['user_type']))
          {
            $data['user_list']= $this->stock_issue_allotment->get_data_according_user($result['user_type']);
          
          }
          $data['employee_list']= $this->stock_issue_allotment->get_employee($result['employee_type']);
          //print_r($data['employee_list']);die;
          $this->load->model('employee/employee_model');
          $data['type_list'] = $this->employee_model->employee_type_list();

          $data['stock_issue_allotment_item_list'] = $stock_issue_allotment_item_list;  
          
          $data['form_data']=array(
                                      'data_id'=>$result['id'],
                                      'issue_code'=>$result['issue_no'],
                                      'issue_date'=>date('d-m-Y',strtotime($result['issue_date'])),
                                      'user_type'=>$result['user_type'],
                                      'user_type_id'=>$result['user_type_id'],
                                      'employee_type'=>$result['employee_type'],
                                      //'payment_mode'=>$result['payment_mode'],
                                      'total_amount'=>$result['total_amount'],
                                      //'discount_amount'=>$result['discount'],
                                      "field_name"=>$total_values,
                                      //'pay_amount'=>$result['paid_amount'],
                                      //'balance_due'=>$result['balance'],
                                      'address'=>$result['address'],
                                      'user_name'=>$result['member_name']
                                     // 'discount_percent'=>$result['discount_percent'],
                                      //'net_amount'=>$result['net_amount'],
                                      );

        if(isset($post) && !empty($post))
        {   
          
        
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {

               $purchase_id = $this->stock_issue_allotment->save();
                $this->session->set_flashdata('success','Allotment updated successfully.');
                redirect(base_url('stock_issue_allotment'));
            }
            else
            {
                $data['form_error'] = validation_errors(); 
            }


                
        }

        $this->load->view('stock_issue_allotment/add',$data);
    }

    public function item_payment_calculation()
    {

          $post = $this->input->post();

          if(isset($post) && !empty($post))
          {   
            $item_new_array=array();
            $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');

            if(isset( $stock_issue_allotment_item_list) && !empty($stock_issue_allotment_item_list))
            {
               foreach($stock_issue_allotment_item_list as $stock_item_list)
              {
                $item_new_array[]= $stock_item_list['item_name'];
              }
            }
             
              
             if(in_array($post['item_name'],$item_new_array))
             {
                  $response_data = array('error'=>1, 'message'=>'Item Already Added Please Increase Quantity');
                   $json = json_encode($response_data,true);
                   echo $json;
             }
             else
             {

                  if(isset($stock_issue_allotment_item_list) && !empty($stock_issue_allotment_item_list))
                  {
                  $stock_issue_allotment_item_list = $stock_issue_allotment_item_list; 
                  }
                  else
                  {
                  $stock_issue_allotment_item_list = [];
                  }
                  $stock_issue_allotment_item_list[$post['item_id']] = array('item_id'=>$post['item_id'],'category_id'=>$post['category_id'],'item_code'=>$post['item_code'],'unit'=>$post['unit'], 'quantity'=>$post['quantity'], 'amount'=>$post['amount'],'item_price'=>$post['item_price'],'total_amount'=>$post['total_amount'],'item_name'=>$post['item_name']);
                  $amount_arr = array_column($stock_issue_allotment_item_list, 'amount'); 
                  $total_amount = array_sum($amount_arr);
                  $this->session->set_userdata('stock_issue_allotment_item_list', $stock_issue_allotment_item_list);

                  
                  $html_data = $this->stock_issue_allotment_item_list($post['remaining_quantity']);
                  $total = $total_amount;

                  if($total==0)
                  {
                  $totamount = '0.00';
                  }
                  else
                  {
                  $totamount = number_format($total,2,'.','');
                  }

                  $response_data = array('html_data'=>$html_data, 'total_amount'=>$totamount,'total_amount'=>number_format($total_amount,2,'.',''),'net_amount'=>$totamount,'paid_amount'=>number_format($total_amount,2,'.',''),'balance_due'=>0);
                  $stock_issue_allotment_payment_array = array('total_amount'=>$totamount,'total_amount'=>number_format($total_amount,2,'.',''));
                  $this->session->set_userdata('stock_issue_allotment_payment_array', $stock_issue_allotment_payment_array);
                  $json = json_encode($response_data,true);
                  echo $json;

             }
            
            
       }
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
        $data['user_list']= $this->stock_issue_allotment->get_data_according_user($post['user_type']);

        }

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('user_name', 'name', 'trim|required'); 
         $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');
         if(isset($post['field_name']))
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
        if(!isset($stock_issue_allotment_item_list) && empty($stock_issue_allotment_item_list) && empty($post['data_id']))
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

        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                      'data_id'=>$post['data_id'],
                                      'issue_code'=>$post['issue_code'],
                                      
                                      'issue_date'=>$post['issue_date'],
                                      'user_type'=>$post['user_type'],
                                      'user_type_id'=>$post['user_type_id'],
                                      'employee_type'=>$post['employee_type'],
                                      //'payment_mode'=>$post['payment_mode'],
                                      'total_amount'=>$post['total_amount'],
                                      'user_name'=>$user_name,
                                      'address'=> $address,
                                      'vendor_code'=>$vendor_code,
                                      //'balance_due'=>$post['balance_due'],
                                      //'pay_amount'=>$post['pay_amount'],
                                      "field_name"=>$total_values,
                                      //'discount_percent'=>$post['discount_percent'],
                                      //'discount_amount'=>$post['discount_amount'],
                                      //'net_amount'=>$post['net_amount']
                                        
                                       );

                                         
            return $data['form_data'];
        }   
    }

    public function check_stock_purchase_item_id()
    {
       $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');
       if(isset($stock_issue_allotment_item_list) && !empty($stock_issue_allotment_item_list))
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

    

    private function stock_issue_allotment_item_list($available_qty="")
    {
        $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');
        //print '<pre>';print_r($stock_issue_allotment_item_list);die;
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
                  </tr></thead>';  
           if(isset($stock_issue_allotment_item_list) && !empty($stock_issue_allotment_item_list))
           {
              
              $i = 1;
              
              foreach($stock_issue_allotment_item_list as $purchase_item_list)
              {

                 $rows .= '<tr>
                            <td width="60" align="center">';
                            if($available_qty >=1)
                            {
                             $rows .= '<input type="checkbox" name="item_id[]" class="part_checkbox booked_checkbox" value="'.$purchase_item_list['item_id'].'">'; 
                            }
                            else
                            {
                              $rows .= ''; 
                            }
                           

                             $rows .= '</td>
                            <td>'.$i.'<input type="hidden" value="'.$purchase_item_list['item_id'].'" id="item_id_'.$purchase_item_list['item_id'].'"/></td>
                            <td>'.$purchase_item_list['item_code'].'<input type="hidden" value="'.$purchase_item_list['item_code'].'" id="item_code_'.$purchase_item_list['item_id'].'"/><input type="hidden" value="'.$purchase_item_list['category_id'].'" id="category_id_'.$purchase_item_list['item_id'].'"/></td>
                            <td>'.$purchase_item_list['item_name'].'<input type="hidden" value="'.$purchase_item_list['item_name'].'" id="item_name_'.$purchase_item_list['item_id'].'"/></td>
                            <td><input type="text" value="'.$purchase_item_list['quantity'].'" name="quantity" id="quantity_'.$purchase_item_list['item_id'].'" onkeyup="payment_cal_perrow('.$purchase_item_list['item_id'].');"/></td>
                            <td>'.$purchase_item_list['unit'].'<input type="hidden" value="'.$purchase_item_list['unit'].'" id="unit_'.$purchase_item_list['item_id'].'"/></td>
                            <td>'.$purchase_item_list['item_price'].'<input type="hidden" value="'.$purchase_item_list['item_price'].'" id="item_price_'.$purchase_item_list['item_id'].'"/></td>
                            
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
           $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');
          //print '<pre>'; print_r($this->session->userdata('stock_issue_allotment_item_list'));die;
           $stock_issue_allotment_payment_array = $this->session->userdata('stock_issue_allotment_payment_array'); 
           
           $particular_id_list = array_column($stock_issue_allotment_item_list, 'item_id'); 
           
           foreach($stock_issue_allotment_item_list as $key=>$item_list)
           { 
            
              if(in_array($item_list['item_id'],$post['item_id']))
              {  
                //echo "<pre>"; print_r($perticuller_ids); exit;
                 unset($stock_issue_allotment_item_list[$key]);
                 //echo $ipd_particular_payment['particulars_charges'];die;
                 $this->session->unset_userdata('stock_issue_allotment_payment_array');
                
              }
           }  
          
       
        $amount_arr = array_column($stock_issue_allotment_item_list, 'amount'); 
        $total_amount = array_sum($amount_arr);
        $this->session->set_userdata('stock_issue_allotment_item_list',$stock_issue_allotment_item_list);
        $html_data = $this->stock_issue_allotment_item_list();
        $particulars_charges = $total_amount;
        $bill_total = $total_amount;
        $response_data = array('html_data'=>$html_data,'total_amount'=>$total_amount);
        $json = json_encode($response_data,true);
        echo $json;
       }
    }

  public function archive_ajax_list()
    {
        unauthorise_permission(56,371);
        $this->load->model('stock_issue_allotment/stock_issue_allotment_archive_model','stock_issue_allotment_archive'); 

        $list = $this->stock_issue_allotment_archive->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $stock_issue_allotment) { 
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

            $row[] = '<input type="checkbox" name="stock_issue_allotment[]" class="checklist" value="'.$stock_issue_allotment->id.'">'.$check_script;
             $row[] = $stock_issue_allotment->issue_no;
            $row[] = $stock_issue_allotment->member_name;
            $row[] = $stock_issue_allotment->net_amount;
            $row[] = $stock_issue_allotment->paid_amount;
            $row[] = $stock_issue_allotment->balance;
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           //if(in_array('373',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_stock_purchase('.$stock_issue_allotment->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           //}
           //if(in_array('372',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$stock_issue_allotment->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           //}
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
    $data['vendor_list'] = $this->stock_issue_allotment->vendor_list($vendor_id,$user_type);
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
            $result = $this->stock_issue_allotment->get_item_values($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

 public function payment_calc_all()
    { 
        $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list'); 
        
        $post = $this->input->post();
        $total_amount = 0;
        $total_discount =0;
       
        $net_amount =0; 
        $payamount=0; 
        $tot_discount_amount=0;
        if(!empty($stock_issue_allotment_item_list))
        {
          foreach($stock_issue_allotment_item_list as $stock_issue_allotment)
          {
              $total_amount+=$stock_issue_allotment['quantity']*$stock_issue_allotment['item_price'];
          }
        }
       
       // $total_discount = ($post['discount']/100)* $total_amount;
        if($post['discount']!='' && $post['discount']!=0){
          
        $total_discount = ($post['discount']/100)* $total_amount;

        }else{
          
        $total_discount=$tot_discount_amount;
        }
        $net_amount = ($total_amount-$total_discount);
         if($post['pay']==1 || $post['data_id']!=''){
           $payamount=$post['pay_amount'];
        }else{
          $payamount=$net_amount;
        }
         
      
        $blance_due=$net_amount - $payamount;
     
        $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'discount'=>$post['discount'],'balance_due'=>number_format($blance_due,2,'.',''),'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       } 

    public function payment_cal_perrow()
    {
      $post = $this->input->post();
      $total_amount='';
      if(isset($post) && !empty($post))
      {
      $total_amount = '';
      $stock_issue_allotment_item_list = $this->session->userdata('stock_issue_allotment_item_list');

      if(!empty($stock_issue_allotment_item_list))
      { 
     
        $stock_issue_allotment_item_list[$post['item_id']] =  array('item_id'=>$post['item_id'],'category_id'=>$post['category_id'],'item_code'=>$post['item_code'],'item_name'=>$post['item_name'],'amount'=>$post['item_price']*$post['quantity'],'unit'=>$post['unit'],'item_price'=>$post['item_price'],'quantity'=>$post['quantity'],'total_amount'=>''); 
        $this->session->set_userdata('stock_issue_allotment_item_list', $stock_issue_allotment_item_list);
        $pay_arr = array('total_amount'=>0);
        $json = json_encode($pay_arr,true);
        echo $json;
      }
      }
    }
     function trashall()
    {
       unauthorise_permission(58,390);
        $this->load->model('stock_issue_allotment/stock_issue_allotment_archive_model','stock_issue_allotment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_issue_allotment->trashall($post['row_id']);
            $response = "Allotment successfully deleted parmanently.";
            echo $response;
        }
    }
      public function trash($id="")
    {
        unauthorise_permission(58,390);
        $this->load->model('stock_issue_allotment/stock_issue_allotment_archive_model','stock_issue_allotment_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_issue_allotment_archive->trash($id);
           $response = "Allotment successfully deleted parmanently.";
           echo $response;
       }
    }
    function restoreall()
    { 
       unauthorise_permission(58,391);
        $this->load->model('stock_issue_allotment/stock_issue_allotment_archive_model','stock_issue_allotment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_issue_allotment_archive->restoreall($post['row_id']);
            $response = "Allotment successfully restore in stock purchase return list.";
            echo $response;
        }
    }
     public function restore($id="")
    {
       unauthorise_permission(58,391);
        $this->load->model('stock_issue_allotment/stock_issue_allotment_archive_model','stock_issue_allotment_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_issue_allotment_archive->restore($id);
           $response = "Allotment successfully restore in stock purchase return list.";
           echo $response;
       }
    }
     public function archive()
    {
       unauthorise_permission(58,389);
        $data['page_title'] = 'Allotment archive list';
        $this->load->helper('url');
        $this->load->view('stock_issue_allotment/archive',$data);
    }
    function deleteall()
    {
        unauthorise_permission(58,388);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_issue_allotment->deleteall($post['row_id']);
            $response = "Issue/allotment successfully deleted.";
            echo $response;
        }
    }
    public function delete($id="")
    {
        unauthorise_permission(58,388);
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_issue_allotment->delete($id);
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
          $data['employee_list']= $this->stock_issue_allotment->get_employee($employee_type);
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
           
          $dropdown .= '<option value="'.$employee_list->id.'" '.$var.'>'.$employee_list->name.'</option>';
          }
        } 
        echo $dropdown; 
    }


    public function usertype_list()
    {
        $this->load->model('general/general_model');
        $user_type= $this->input->post('user_type');
        if(!empty($user_type)){
          $data['user_list']= $this->stock_issue_allotment->get_data_according_user($user_type);
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
                
                $dropdown .= '<option value="'.$user_list->ids.'">'.$user_list->emp_type.'</option>';
                }
                } 
                $dropdown .='</select></div></div></div> <div class="col-md-4">  <div class="row m-b-5"><div class="col-md-4"><label>Employee Name</label></div><div class="col-md-8"> <select class="w-189px" name="user_type_id" id="employee_list_n" onchange="appned_user_detail(1,this.value);"><option value="">Select</option></select><a href="" class="btn-new">New</a> </div></div></div> ';
                echo $dropdown; 
         }
         if($user_type==2)
         {
                $dropdown = '<div class="col-md-4"><div class="row m-b-5"><div class="col-md-4"> <label>Patient Name</label></div><div class="col-md-8">';
                $dropdown.= '<select name="user_type_id" onchange="appned_user_detail('.$user_type.',this.value);"><option value="">Select Patient</option>'; 
                if(!empty($data['user_list']))
                {
                foreach($data['user_list'] as $user_list)
                {
                
                $dropdown .= '<option value="'.$user_list->ids.'" >'.$user_list->patient_name.'</option>';
                }
                } 
                $dropdown .='</select></div></div></div>';
                echo $dropdown; 
         }
         if($user_type==3)
         {
              $dropdown = '<div class="col-md-4"><div class="row m-b-5"><div class="col-md-4"> <label>Doctor Name</label></div><div class="col-md-8">';
              $dropdown.= '<select name="user_type_id" onchange="appned_user_detail('.$user_type.',this.value);"><option value="">Select Doctor</option>'; 
              if(!empty($data['user_list']))
              {
              foreach($data['user_list'] as $user_list)
              {
              
              $dropdown .= '<option value="'.$user_list->ids.'" >'.$user_list->doctor_name.'</option>';
              }
              } 
              $dropdown .='</select></div></div></div>';
              echo $dropdown; 
         }
      
    }

}


?>