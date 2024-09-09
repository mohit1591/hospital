<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_stock extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('item_stock/item_stock_model','item_manage');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('170','984');
        $data['page_title'] = 'Stock Item List'; 
        $this->session->unset_userdata('stock_items_serach');
        $this->load->view('item_stock/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('170','984');
       $users_data = $this->session->userdata('auth_users');
       
       $list = $this->item_manage->get_datatables();

       // print_r($list);die;
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $item_manage) 
        {
            
             $serial_no=array();
           $purchase_item_serial =$this->item_manage->purchase_item_serial_by_id($item_manage->id,$item_manage->stockids);
            //echo "<pre>"; print_r($purchase_item_serial); die;
            foreach ($purchase_item_serial as  $serial) 
            {
                array_push($serial_no, $serial->serial_no);
            } 

            $serial_data=implode(",", $serial_no);
            
            if(empty($serial_data))
            {
                $nre ='';  
                $table ='';
            }
            else
            {
                
                $nre = json_encode($serial_data);  
                $table ="<button  value='".$nre."' class='btn-custom' type='button' onclick='return show_serial(this.value,".$item_manage->id.",1)'> Show </button>"; 
            }
            
            

            $no++;
            $row = array();
            if($item_manage->status==1)
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
            ////////// Check list end ///////////// 
          $row[] = '<input type="checkbox" name="'.$item_manage->id.'" class="checklist" value="'.$item_manage->id.'">'.$check_script;
		    $row[]=$i; 
          
            $row[] = $item_manage->item_code;
            $row[] = $item_manage->item; 
            $row[] = $item_manage->mrp;
            $row[] = $item_manage->price; 
            $row[] = $item_manage->category;
			//$row[] = $item_manage->stock_qty;
			
			
			  if($item_manage->min_alert>=$item_manage->stock_qty)
              {
               $row[] = '<div class="m_alert_green">'.$item_manage->stock_qty.'</div>'.' '.$table;
              }
			  elseif(empty($item_manage->min_alert)){
                $row[] = $item_manage->stock_qty.' '.$table;
		      }
		      else{
                $row[] = $item_manage->stock_qty.' '.$table;
		      }
			
            

            //$row[] = $item_manage->min_alert;
            $row[] = $item_manage->rack_no;
            $row[] = $status;
            $row[] = date('d-M-Y',strtotime($item_manage->created_date)); 
            
           // date('d-M-Y H:i A',strtotime($item_manage->created_date)); 
           
          $btnedit='';
          $btndelete='';
        
            if($users_data['parent_id']==$item_manage->branch_id){

               $btn_history = ' <a class="btn-custom" href="'.base_url('item_stock/item_allot_history?id='.$item_manage->id.'&type=1').'" style="'.$item_manage->id.'" title="Edit"><i class="fa fa-history"></i> History</a>';

               if(in_array('986',$users_data['permission']['action'])){
                $btnview=' <a class="btn-custom" onclick="return view_inventory_entry('.$item_manage->id.')" href="javascript:void(0)" title="View"><i class="fa fa-eye"></i> View </a>';
               }

               //  if(in_array('987',$users_data['permission']['action'])){
               //      $btndelete = ' <a class="btn-custom" onClick="return delete_item_manage('.$item_manage->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               // }
          }
      
             $row[] = $btnedit.$btndelete.$btnview.$btn_history; 
             
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->item_manage->count_all(),
                        "recordsFiltered" => $this->item_manage->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

        public function item_stock_list_excel()
        {
      
          unauthorise_permission('170','948');
          $this->load->library('excel');
          //$qty='';
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Item Code','Item Name','MRP','Price','Category','Quantity','Min.Alert','Rack No.','Created Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          
      
           //$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          foreach ($fields as $field)
          {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
   
                
               
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
          }

          $list = $this->item_manage->search_report_data();
          //echo"<pre>";print_r($list);die;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $item_manage)

               {


                    // $qty_data = $this->item_manage->get_item_quantity($item_manage->id,$item_manage->category_id);
                    // if($qty_data['total_qty']>=0)
                    // {
                    // $qty = $qty_data['total_qty'];
                    // }
                    // else
                    // {
                    //  $qty='0';
                    // }
                   array_push($rowData,$item_manage->item_code,$item_manage->item,$item_manage->mrp,$item_manage->price, $item_manage->category,$item_manage->stock_qty,$item_manage->min_alert,$item_manage->rack_no,$item_manage->created_date);
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

                        //$objPHPExcel->getActiveSheet()->getStyle($row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                       // $objPHPExcel->getActiveSheet()->getStyle($row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
          header("Content-Disposition: attachment; filename=item_stock_list_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }


    }

    public function item_stock_list_csv()
    {
           unauthorise_permission('170','984');
           // Starting the PHPExcel library
          $this->load->library('excel');
          //$qty='';
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Item Code','Item Name','MRP','Price','Category','Quantity','Min.Alert','Rack No.','Created Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->item_manage->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $item_manage)
               {

                   array_push($rowData,$item_manage->item_code,$item_manage->item,$item_manage->mrp,$item_manage->price, $item_manage->category,$item_manage->stock_qty,$item_manage->min_alert,$item_manage->rack_no,$item_manage->created_date);
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
          header("Content-Disposition: attachment; filename=item_stock_list_report_".time().".csv");    
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }

    }

    public function pdf_item_stock_list()
    {    
        unauthorise_permission('170','984');
        $data['print_status']="";
        $data['data_list'] = $this->item_manage->search_report_data();
        $this->load->view('item_stock/item_stock_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("item_stock_list_report_".time().".pdf");
    }
    public function print_item_manage_list()
    { 
       unauthorise_permission('170','984');   
       $data['print_status']="1";
       $data['data_list'] = $this->item_manage->search_report_data();
       $this->load->view('item_stock/item_stock_report_html',$data); 
    }
    
    
    
    public function add()
    {
        unauthorise_permission('170','985');
        $data['page_title'] = "Add Stock Items";  
        $post = $this->input->post();
        
    //echo "<pre>";
    // print_r($post);die(); 

        $data['form_error'] = []; 
        $this->load->model('general/general_model'); 
        $data['category_list'] = $this->general_model->category_list();
        $data['manuf_company_list'] = $this->item_manage->manuf_company_list(); 
        $data['vendor_list'] = $this->general_model->vendor_list();
        $data['stock_item_unit_list'] = $this->item_manage->stock_item_unit_list();

        $data['rack_list'] = $this->item_manage->rack_list();
        $data['unit_list'] = $this->item_manage->unit_list();
        

        $item_code = generate_unique_id(33); 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'item'=>"",
                                  'category_id'=>'',
                                  'item_price'=>'',
                                  //'quantity'=>'',
                                  //'min_alert'=>'',
                                  'second_unit'=>'',
                                  //'qty_with_second_unit'=>'',
                                  'item_code'=>$item_code,
                                  //'second_unit_name'=>'',
                                  'stock_item_unit'=>'',

                                  'mrp'=>'',
                                  //'sgst'=>'',
                                  //'cgst'=>'',
                                  //'igst'=>'',
                                  //'discount'=>'',
                                  'conversion'=>'',
                                  'packing'=>'',
                                  'rack_no'=>'',
                                  
                                  'status'=>"1",
                                  'manuf_company'=>''
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->item_manage->save();

                echo 1; return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
 //print_r($data['form_error']);die();
       $this->load->view('item_stock/add',$data);       
    }
    
    public function edit($id="")
    {
       unauthorise_permission('170','986');
        if(isset($id) && !empty($id) && is_numeric($id))
        {      
        $result = $this->item_manage->get_by_id($id);  

 //print_r($result);die;

        $this->load->model('general/general_model'); 
        $data['category_list'] = $this->general_model->category_list();
        $data['vendor_list'] = $this->general_model->vendor_list();

        $data['rack_list'] = $this->item_manage->rack_list();
        $data['unit_list'] = $this->item_manage->unit_list();

        $data['page_title'] = "Update Stock Item";  
        $data['stock_item_unit_list'] = $this->item_manage->stock_item_unit_list();
        $data['manuf_company_list'] = $this->item_manage->manuf_company_list(); 

        $post = $this->input->post();

     

        $data['form_data'] = array(
                                  

                                  'data_id'=>$result['id'],
                                  'item'=>$result['item'], 
                                  'item_code'=>$result['item_code'],
                                  'item_price'=>$result['price'],
                                  //'min_alert'=>$result['min_alert'],
                                  //'quantity'=>$result['qty'],
                                  'category_id'=>$result['category_id'],
                                  'second_unit'=>$result['second_unit'],
                                  //'second_unit_name'=>$result['second_unit'],
                                  //'qty_with_second_unit'=>$result['qty_with_second_unit'],
                                  'stock_item_unit'=>$result['unit_id'],

                                  'mrp'=>$result['mrp'],
                                  //'sgst'=>$result['sgst'],
                                  //'cgst'=>$result['cgst'],
                                  //'igst'=>$result['igst'],
                                  //'discount'=>$result['discount'],
                                  'conversion'=>$result['conversion'],
                                  'packing'=>$result['packing'],
                                  'rack_no'=>$result['rack_no'],

                                  'status'=>$result['status'],
                                  'manuf_company'=>$result['manuf_company']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
 
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->item_manage->save();

                echo 1; return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

 //print_r($data['form_error']);die();

       $this->load->view('item_stock/add',$data);       
      }
    }
     
    private function _validate()
    {   
        $field_list = mandatory_section_field_list(12);
        $users_data = $this->session->userdata('auth_users');
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 

        $this->form_validation->set_rules('item', 'item', 'trim|required|callback_check_stockitem_name'); 
        $this->form_validation->set_rules('category_id', 'item category', 'trim|required');  

        //$this->form_validation->set_rules('stock_item_unit', 'unit 1st', 'trim|required'); 
        $this->form_validation->set_rules('second_unit', 'unit 2nd.', 'trim|required'); 
        $this->form_validation->set_rules('conversion', 'conversion', 'trim|required');
       

        if(!empty($field_list)){
          
          if($field_list[0]['mandatory_field_id']=='57' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
              
                $this->form_validation->set_rules('item_price', 'item price', 'trim|required|numeric|callback_check_item_price');
               //$this->form_validation->set_rules('item_price', 'item price', 'trim|required'); 
          }
          else
          {
          
          $this->form_validation->set_rules('item_price', 'item price', 'callback_check_item_price');
          }
                             
        }
        

       if ($this->form_validation->run() == FALSE) 
        { 
      
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'item'=>$post['item'], 
                                        'item_code'=>$post['item_code'],
                                        'item_price'=>$post['item_price'],
                                        'category_id'=>$post['category_id'],
                                        //'quantity'=>$post['quantity'],
                                       // 'min_alert'=>$post['min_alert'],
                                        //'qty_with_second_unit'=>$post['qty_with_second_unit'],
                                        //'second_unit_name'=>$post['second_unit_name'],
                                        'manuf_company'=>$post['manuf_company'],
                                        'mrp'=>$post['mrp'],
                                        'sgst'=>$post['sgst'],
                                        'cgst'=>$post['cgst'],
                                        'igst'=>$post['igst'],
                                        'discount'=>$post['discount'],
                                        'stock_item_unit'=>$post['stock_item_unit'],
                                        'second_unit'=>$post['second_unit'],
                                        'conversion'=>$post['conversion'],
                                        'packing'=>$post['packing'],
                                        'rack_no'=>$post['rack_no'],

                                        'status'=>$post['status'],

                                       ); 
            return $data['form_data'];
 //print_r($data['form_data']);die;
        }   
    }


public function check_item_price($str)
    {

      $post = $this->input->post();
      if(!empty($str))
      {
          $this->load->model('general/general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                
             if(!empty($post['item_price']) && $post['item_price']> $post['mrp'])
                  {
                     $this->form_validation->set_message('check_item_price', 'Item Price must be less and equal to MRP');
                   return false;
                  }
                  else
                  {
                     return true;
                  }
          }
          else
          {
                 if(!empty($post['item_price']) && $post['item_price']>$post['mrp'])
                  {
                     $this->form_validation->set_message('check_item_price', 'Item Price must be less and equal to MRP');
                   return false;
                  }
                  else
                  {
                     return true;
                  }
          }  
      }

     else
      {
        $this->form_validation->set_message('check_item_price', 'Item Price field is required.');
              return false; 
      } 
     
    }


  public function view_item($id="")
      {  
        unauthorise_permission('170','986');
        $this->load->model('general/general_model'); 
        $data['category_list'] = $this->general_model->category_list();
        $data['vendor_list'] = $this->general_model->vendor_list();

        $data['rack_list'] = $this->item_manage->rack_list();
        $data['unit_list'] = $this->item_manage->unit_list();

        $data['page_title'] = "Update Stock Item";  
        $data['stock_item_unit_list'] = $this->item_manage->stock_item_unit_list();
        $data['manuf_company_list'] = $this->item_manage->manuf_company_list(); 

        if(isset($id) && !empty($id) && is_numeric($id))
        { 

        $data['form_data'] = $this->item_manage->get_by_id($id);

        $data['page_title'] = $data['form_data']['item']." detail";
        $this->load->view('item_stock/view',$data);      
       }
    }



    public function check_stockitem_name($str)
    {

          $post = $this->input->post();
         
           if(!empty($str))
            {
              $this->load->model('general/general_model','general'); 
              if(!empty($post['data_id']) && $post['data_id']>0)
              {
                  $data_cat= $this->item_manage->get_by_id($post['data_id']);
                  if($data_cat['item']==$str && $post['data_id']==$data_cat['id'] && $post['category_id']==$data_cat['category_id'])
                  {
                    
                  return true;  
                  }
                else
                {

                $check_stockitem_name = $this->general->check_stockitem_name($post['item'],$post['category_id']);

                if(empty($check_stockitem_name))
                  {
                  return true;
                  }
                  else
                  {
                  $this->form_validation->set_message('check_stockitem_name', 'The stock item already exists.');
                  return false;
                  }
                }
              }
              else
              {
                $check_stockitem_name = $this->general->check_stockitem_name($post['item'],$post['category_id']);
                if(empty($check_stockitem_name))
                  {
                  return true;
                  }
                else
                  {
                  $this->form_validation->set_message('check_stockitem_name', 'The stock item already exists.');
                  return false;
                  }
                }  
              }
              else
              {
              $this->form_validation->set_message('check_stockitem_name', 'The stock unit field is required.');
              return false; 
       } 
    }
 
    public function delete($id="")
    {
       unauthorise_permission('170','987');
       if(!empty($id) && $id>0)
       {
           $result = $this->item_manage->delete($id);
           $response = "Item successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('170','987');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->item_manage->deleteall($post['row_id']);
            $response = "Item successfully deleted.";
            echo $response;
        }
    }

  

    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('170','988');
        $data['page_title'] = 'Item Archive List';
        $this->load->helper('url');
        $this->load->view('item_stock/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('170','988');
        $this->load->model('item_stock/item_opening_stock_archive_model','item_manage_archive'); 

    
            $list = $this->item_manage_archive->get_datatables();
          // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $item_manage) { 
            $no++;
            $row = array();
            if($item_manage->status==1)
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
            ////////// Check list end ///////////// 
         

                    $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$item_manage->id.'">'.$check_script; 
            $qty_data = $this->item_manage->get_item_quantity($item_manage->id,$item_manage->category_id);
            $medicine_total_qty = $qty_data['total_qty'];
            $row[] = $item_manage->item_code;
            $row[] = $item_manage->item; 
            $row[] = $item_manage->mrp;
            $row[] = $item_manage->price; 
            $row[] = $item_manage->category;
            $row[] = $item_manage->stock_qty;
            
            // if($item_manage->min_alert>=$qty_data['total_qty'])
            // {
            // $medicine_total_qty = '<div class="m_alert_red">'.$qty_data['total_qty'].'</div>';
            // }
            // if($qty_data['total_qty']>=0)
            // {
            // $row[] = $medicine_total_qty;
            // }
            // else
            // {
            // $row[]='0';
            // }
            // $row[] = $item_manage->min_alert;
            $row[] = $item_manage->rack_no;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($item_manage->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
         
               if(in_array('181',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_item_manage('.$item_manage->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
               if(in_array('180',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$item_manage->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
     
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->item_manage_archive->count_all(),
                        "recordsFiltered" => $this->item_manage_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('170','988');
        $this->load->model('item_stock/item_opening_stock_archive_model','item_manage_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->item_manage_archive->restore($id);
           $response = "Item successfully restore in item list.";
           echo $response;
       }
    }

    public function advance_search()
    {

            $this->load->model('general/general_model'); 
            $data['page_title'] = "Advance Search";
            $post = $this->input->post();
           
            $data['form_data'] = array(
                                     // "start_date"=>"",
                                      //"end_date"=>"",
                                        "item_name"=>"",
                                        "item_code"=>"",
                                        "category"=>""
                                     );
            if(isset($post) && !empty($post))
            {
            //print_r($post);die;
                $marge_post = array_merge($data['form_data'],$post);
                $this->session->set_userdata('stock_items_serach', $marge_post);

            }
                $stock_items_serach = $this->session->userdata('stock_items_serach');
                if(isset($stock_items_serach) && !empty($stock_items_serach))
                  {
                  $data['form_data'] = $stock_items_serach;
                   }
            $this->load->view('ot_booking/advance_search',$data);
    }

    function restoreall()
    { 
        unauthorise_permission('170','988');
        $this->load->model('item_stock/item_opening_stock_archive_model','item_manage_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->item_manage_archive->restoreall($post['row_id']);
            $response = "Item successfully restore in item list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('170','989');
        $this->load->model('item_stock/item_opening_stock_archive_model','item_manage_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->item_manage_archive->trash($id);
           $response = "Item successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('170','989');
        $this->load->model('item_stock/item_opening_stock_archive_model','item_manage_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->item_manage_archive->trashall($post['row_id']);
            $response = "Item successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

     public function item_manage_dropdown(){

          $item_manage_list = $this->item_manage->item_manage_list();
          $dropdown = '<option value="">Select Item</option>'; 
          if(!empty($item_manage_list)){
               foreach($item_manage_list as $item_manage){
                    $dropdown .= '<option value="'.$item_manage->id.'">'.$item_manage->item.'</option>';
               }
          } 
          echo $dropdown; 
     }

     public function get_second_unit()
     {
       $unit_id= $this->input->post('unit_id');
        $get_subunit=$this->item_manage->get_sub_unit($unit_id);
        //print_r($get_subunit);die;
        $ids='';
        $name='';
        $name_shown='';
        if(!empty($get_subunit))
        {
           $ids=$get_subunit[0]->id;
           $name=$get_subunit[0]->first_unit;
           $name_shown='/'.$get_subunit[0]->first_unit;
        
              $html=' <div class="col-md-4"></div> <div class="col-md-8">1 '.$get_subunit[0]->second_unit.' = <input type="text" name="qty_with_second_unit" id="quantity" value="" data-toggle="tooltip"  title="Allow only numeric." placeholder="Enter Unit Quantity" class="tooltip-text price_float w-133px"> <input type="hidden" name="second_unit" value="'.$ids.'" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float w-50px" /><input type="hidden" value="'.$name.'" name="second_unit_name"/>'.$name_shown.'</div>';
          echo $html;
       exit;
     }

        
    
     }



     public function inventory_item_sample_excel()
     {
        //unauthorise_permission(97,627);
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
       
        //$fields = array('Item Name(*)','Price','Category','Unit','Second Unit','Unit1 Qty','Unit2 Qty(*)','Min Alert','Per Pic Price');
        $fields = array('Item Name(*)','Price','Category','Unit','Second Unit','Unit1 Qty','Unit2 Qty(*)','Min Alert');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $rowData = array();
        $data= array();
      
        // Fetching the table data
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel charset=UTF-8');
       header("Content-Disposition: attachment; filename=item_manage_import_".time().".xls");  
        header("Pragma: no-cache"); 
        header("Expires: 0");
      
            ob_end_clean();
            $objWriter->save('php://output');
       
    }

    public function inventory_item_import_excel()
    {
       //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import item excel';
        $arr_data = array();
        $header = array();
        $path='';
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['item_list']) || $_FILES['item_list']['error']>0)
            {
               
               $this->form_validation->set_rules('item_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('item_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }
                if(!empty($arr_data))
                {
                    $arrs_data = array_values($arr_data);
                    $total_item_list = count($arrs_data);
                    // print_r($arr_data);
                    // die; item_manage

                    $array_keys = array('item','price','category_id','unit_id','second_unit','qty','qty_with_second_unit','min_alert');
                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H');
                    
                    $item_list_data = array();
                    $j=0;
                    $m=0;
                    $data='';
                    $item_list_data= array();
                    for($i=0;$i<$total_item_list;$i++)
                    {
                        $item_list_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $item_list_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $item_list_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                    $this->item_manage->save_all_item($item_list_data);
                }
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {

                $data['form_error'] = validation_errors();
                

            }
        }

        $this->load->view('item_stock/import_item_manage_excel',$data);
    }
    
    //inventory item alot
    public function allotement_to_branch()
    {
         $post = $this->input->post();

         $inventory_data = $this->session->userdata('alloted_inventory_ids');
         // $medicine_batch = $this->session->userdata('alloted_medicine_batch_nos');
         if(!empty($inventory_data))
         {
            $inventory_data = array();
            $this->session->set_userdata('alloted_inventory_ids',$inventory_data);
         }
        //echo "<pre>"; print_r($post); exit;
         if(isset($post) && !empty($post))
         {
               $inventory_data = array();
               $i=0;
               foreach ($post['inventory_ids'] as $medicine_id) {
                    
                        $inventory_data[$i]['inventory_id'] = $post['inventory_ids'][$i];
                        $inventory_data[$i]['batch_no'] = $post['batch_no'][$i];
                    
                    $i++;
               }
              $this->session->set_userdata('alloted_inventory_ids', $inventory_data);
             
         } 
        
         $data['page_title'] = 'Inventory Allotment';
         
         $inventory_list = $this->item_manage->get_item_list();
        

         $row='';
         $i=1;
         $total_num = count($inventory_list);
         if(!empty($inventory_list))
         {
               foreach($inventory_list as $inventory_data)
               {
                        $qty_data = $this->item_manage->get_batch_med_qty($inventory_data['item_id']); 
                        
                        
                   
                   $table_ser="<td> <button id='add_serial_no' value='' class='btn-custom' type='button' onclick='return add_serial(".$inventory_data['item_id'].",this.value,1)'> Add </button> </td><input type='hidden' name='serial_no_array[]' id='serial_no_array_".$inventory_data['item_id']."' value=''><input type='hidden' name='issued_ser_id_no_array[]' id='issued_ser_id_no_array_".$inventory_data['item_id']."' value=''>"; 
                   
                   
                    $row.= '<tr><td align="center">'.$i.'<input type="hidden" name="medicine['.$inventory_data['id'].'][item_id]" class="medicinechecklist" value="'.$inventory_data['id'].'"></td><td>'.$inventory_data['item'].'</td><td>'.$inventory_data['item_code'].'</td><td><input type="text" id="qty_'.$inventory_data['id'].'" name="medicine['.$inventory_data['id'].'][qty]" data-qty="'.$qty_data['total_qty'].'" data-id="'.$inventory_data['id'].'" value = "'.$qty_data['total_qty'].'" onblur="check_max_qty(this);"/><p id="msg_'.$inventory_data['id'].'" style="color:red;"></p></td>'.$table_ser.'</tr>';
                    $i++;
               
                   
               }
          }else{
               $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
          }
          //echo "<pre>"; print_r($row); exit;
          
          $data['inventory_list'] = $row;
       

        
         $this->load->view('item_stock/allot_to_branch',$data);
     }
     public function allot_item_to_branch()
     {
        
          $post = $this->input->post();

          $users_data = $this->session->userdata('auth_users');
          $data['page_title'] = 'Item Allotment';
           $data['medicine_list'] = '';
          if(isset($post) && !empty($post))
          {
              if($users_data['users_role']=='2')
              {

                     $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                     $this->form_validation->set_rules('sub_branch_id', 'branch', 'trim|required');
                    
                     if($this->form_validation->run() == TRUE)
                     {
                    
                          
                            $this->item_manage->allot_item_to_branch();
                            echo 1;
                            return false;
                     }
                    
                    else
                    {
                       
                         // echo "hello";
                         // print_r($this->session->userdata('alloted_medicine_ids'));
                         // die;
                         $inv_list = $this->item_manage->get_item_list();
                         $row='';
                         $i=0;
                         $total_num = count($inv_list);
                         if(!empty($inv_list))
                         {
                              foreach($inv_list as $medicine_data)
                              {
                                   $qty_data = $this->item_manage->get_batch_med_qty($medicine_data['item_id']);
                                   $check_script='';
                                   if($i==$total_num)
                                   {
                                        
                                   }
                                   $row.= '<tr><td align="center">'.$i.'<input type="hidden" name="medicine['.$medicine_data['id'].'][item_id]" class="medicinechecklist" value="'.$medicine_data['id'].'"></td><td>'.$medicine_data['item'].'</td><td>'.$medicine_data['item_code'].'</td><td><input type="text" id="qty_'.$medicine_data['id'].'" name="medicine['.$medicine_data['id'].'][qty]" data-qty="'.$qty_data['total_qty'].'" data-id="'.$medicine_data['id'].'" value = "'.$qty_data['total_qty'].'" onblur="check_max_qty(this);"/><p id="msg_'.$medicine_data['id'].'" style="color:red;"></p></td></tr>';
                                   $i++;
                              }
                         }else{
                              $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
                         }
                         $data['medicine_list'] = $row;
                         $data['form_error'] = validation_errors(); 
                      
                    }

               }
          }



          
          $this->load->view('medicine_stock/allot_to_branch',$data);
     }
     public function item_allot_history($id='')
     {
          $data['id'] = $this->input->get('id');
          $data['type'] = $this->input->get('type');
          $data['page_title'] = 'Inventory Allot History List';
          $this->load->view('item_stock/item_allot_history',$data);
          // $this->load->view('medicine_stock/medicine_allot_history',$data);
     }
    public function item_allot_history_ajax()
    {  
        $post = $this->input->post();
        
        //unauthorise_permission(63,415);
        $users_data = $this->session->userdata('auth_users');
         $this->load->model('item_manage/item_manage_model'); 
         $item_datas=$this->item_manage_model->get_by_id($post['item_id']);
        $list = $this->item_manage->get_item_allot_history_datatables($post['item_id'],$post['type'],$item_datas['branch_id']);  
        //print '<pre>';print_r( $list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $item_stock) 
        { 
            $no++;
            $row = array();
            $state = "";
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
            
            $row[] = $i;
            if($post['type']==5)
            {
	            $row[] = $item_stock->branch_name;
	            $row[] = $item_stock->item_code;
	            $row[] = $item_stock->item;
	            $row[] = $item_stock->company_name;
	            $row[] = $item_stock->quantity;
	            $row[] = date('d-M-Y',strtotime($item_stock->created_date));
            
            }
            else
            {
    				  $row[] = $item_stock->item_code;
    				  $row[] = $item_stock->vendor_name;
    				  $row[] = $item_stock->item;
    				  $row[] = $item_stock->company_name;
    				  //$row[] = $item_stock->batch_no;
                      //$row[] = $item_stock->bar_code;
    				  $row[] = $item_stock->qty;
    				  $row[] = date('d-M-Y',strtotime($item_stock->purchase_date));
            }
            
            
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->item_manage->get_item_allot_history_count_all($post['item_id'],$post['type'],$item_datas['branch_id']),
                        "recordsFiltered" => $this->item_manage->get_item_allot_history_count_filtered($post['item_id'],$post['type'],$item_datas['branch_id']),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }
    
    
    public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
            $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name="sub_branch_id"><option value="">Select</option></option>';
            if(!empty($sub_branch_details)){
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
    //end of alot
    
    function search_serial_no($vals="")
    {
        $post = $this->input->post();
        //echo "<pre>"; print_r($post); exit;
        $issue_allot_id = $post['issue_allot_id'];
        $item_id = $post['item_id'];
        if(!empty($vals))
        {
            $result = $this->item_manage->search_serial_no($vals,$issue_allot_id,$item_id);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
}
?>