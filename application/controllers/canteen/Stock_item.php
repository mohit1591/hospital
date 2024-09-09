<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock_item extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('canteen/stock_item/stock_item_model','stock_item');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //unauthorise_permission('170','984');
        $data['page_title'] = 'List Stock Item'; 
        $this->load->model('general/general_model','general_model');
        $data['category_lists'] = $this->general_model->canteen_category_list();
        $data['manuf_company_lists'] = $this->stock_item->manuf_company_list();
        $data['unit_lists'] = $this->stock_item->stock_item_unit_list();
        $this->session->unset_userdata('stock_item_serach');
        $this->load->view('canteen/stock_item/list',$data);
    }

    public function ajax_list()
    { 
       
       //unauthorise_permission('170','984');
       $users_data = $this->session->userdata('auth_users');
       $list = $this->stock_item->get_datatables();
      
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $stock_item) {
         // print_r($Category);die;
            $no++;
            $row = array();
            if($stock_item->status==1)
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
          if($users_data['parent_id']==$stock_item->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$stock_item->id.'">'.$check_script;
          }else{
               $row[]='';
          } 
            $qty_data = $this->stock_item->get_item_quantity($stock_item->id,$stock_item->category_id);
          
            $medicine_total_qty = $qty_data['total_qty'];
            $row[] = $stock_item->item_code;
            $row[] = $stock_item->item; 
            $row[] = $stock_item->price; 
            $row[] = $stock_item->category;
            if($stock_item->min_alert>=$qty_data['total_qty'])
            {
            $medicine_total_qty = '<div class="m_alert_red">'.$qty_data['total_qty'].'</div>';
            }
            if($qty_data['total_qty']>=0)
            {
            $row[] = $medicine_total_qty;
            }
            else
            {
            $row[]='0';
            }
            $row[] = $stock_item->min_alert;
            $row[] = $stock_item->unit;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($stock_item->created_date)); 
           
          $btnedit='';
          $btndelete='';
        
          if($users_data['parent_id']==$stock_item->branch_id){
               //if(in_array('986',$users_data['permission']['action'])){
                    $btnedit = ' <a onClick="return edit_stock_item('.$stock_item->id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock_item->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               //}
               // if(in_array('987',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_stock_item('.$stock_item->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               //}
          }
      
             $row[] = $btnedit.$btndelete; 
             
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_item->count_all(),
                        "recordsFiltered" => $this->stock_item->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

        public function stock_item_list_excel()
        {
      
          //unauthorise_permission('170','948');
          $this->load->library('excel');
          $qty='';
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Item Code','Item Name','MRP','Category','Quantity','Min. Alert','Unit');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      
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
                
               
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
          }
          $list = $this->stock_item->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $stock_item)
               {
                    $qty_data = $this->stock_item->get_item_quantity($stock_item->id,$stock_item->category_id);
                    if($qty_data['total_qty']>=0)
                    {
                    $qty = $qty_data['total_qty'];
                    }
                    else
                    {
                     $qty='0';
                    }
                   array_push($rowData,$stock_item->item_code,$stock_item->item,$stock_item->price, $stock_item->category,$qty,$stock_item->min_alert,$stock_item->unit);
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
          header("Content-Disposition: attachment; filename=item_list_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }


    }

    public function stock_item_list_csv()
    {
          //unauthorise_permission('170','984');
           // Starting the PHPExcel library
          $this->load->library('excel');
          $qty='';
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Item Code','Item Name','MRP','Category','Quantity','Min.Alert','Unit');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->stock_item->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $stock_item)
               {

                    $qty_data = $this->stock_item->get_item_quantity($stock_item->id,$stock_item->category_id);
                    if($qty_data['total_qty']>=0)
                    {
                    $qty = $qty_data['total_qty'];
                    }
                    else
                    {
                    $qty='0';
                    }
                   array_push($rowData,$stock_item->item_code,$stock_item->item,$stock_item->price, $stock_item->category,$qty,$stock_item->min_alert,$stock_item->unit);
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
          header("Content-Disposition: attachment; filename=item_list_report_".time().".csv");    
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }

    }

    public function pdf_stock_item_list()
    {    
        //unauthorise_permission('170','984');
        $data['print_status']="";
        $data['data_list'] = $this->stock_item->search_report_data();
        $this->load->view('canteen/stock_item/stock_item_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("item_list_report_".time().".pdf");
    }
    public function print_stock_item_list()
    { 
       //unauthorise_permission('170','984');   
       $data['print_status']="1";
       $data['data_list'] = $this->stock_item->search_report_data();
       $this->load->view('canteen/stock_item/stock_item_report_html',$data); 
    }
    
    
    
    public function add()
    {
        //unauthorise_permission('170','985');
        $data['page_title'] = "Add  Opening Stock";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $this->load->model('canteen/general/common_model'); 
        $data['category_lists'] = $this->common_model->canteen_category_list();
        $data['manuf_company_lists'] = $this->stock_item->manuf_company_list();
        $data['unit_lists'] = $this->stock_item->stock_item_unit_list();
        $item_code = generate_unique_id(33); 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'item'=>"",
                                  'category_id'=>'',
                                  'product_id'=>'',
                                  'price'=>'',
                                  'qty'=>'',
                                  'min_alert'=>'',
                                  'item_code'=>$item_code,
                                  'unit_id'=>'',
                                  'status'=>"1",
                                  'manuf_company'=>''
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->stock_item->save();

                echo 1; 
                return false;
                
            }
            else
            {
                 
                $data['form_error'] = validation_errors();  
                print_r($data['form_error']);die;
            }     
        }
     //print_r($data['form_error']);die();
       $this->load->view('canteen/stock_item/add',$data);       
    }
    
    public function edit($id="")
    {
       //unauthorise_permission('170','986');
        if(isset($id) && !empty($id) && is_numeric($id))
        {      
        $result = $this->stock_item->get_by_id($id);  
        //print_r($result);die;
        $this->load->model('general/general_model'); 
        $data['category_lists'] = $this->general_model->canteen_category_list();
        $data['manuf_company_lists'] = $this->stock_item->manuf_company_list();
        $data['unit_lists'] = $this->stock_item->stock_item_unit_list();
        $data['page_title'] = "Update Stock Item";  
        //$qty_data = $this->stock_item->get_item_quantity($stock_item->id,$stock_item->category_id);
            //  $medicine_total_qty = $qty_data['total_qty'];
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'item'=>$result['item'], 
                                  'item_code'=>$result['item_code'],
                                  'product_id'=>$result['product_id'],
                                  'price'=>$result['price'],
                                  'min_alert'=>$result['min_alert'],
                                  'qty'=>$result['qty'],
                                  'category_id'=>$result['category_id'],
                                  'unit_id'=>$result['unit_id'],
                                  'status'=>$result['status'],
                                  'manuf_company'=>$result['manuf_company']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
               
                $this->stock_item->save();
                echo 1;
                return false;
                
            }
            else
            {
               
                $data['form_error'] = validation_errors();  
            }     
        }
        $data['manuf_company_list'] = $this->stock_item->manuf_company_list(); 
        //print_r($data['form_data']);die;
       $this->load->view('canteen/stock_item/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('item_name', 'Item name', 'trim|required|callback_check_stockitem_name'); 
        $this->form_validation->set_rules('category_id', 'Item category', 'trim|required');  
        $this->form_validation->set_rules('price', 'Item price', 'trim|required'); 
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'item'=>$post['item'], 
                                        'item_code'=>$post['item_code'],
                                        'price'=>$post['price'],
                                        'category_id'=>$post['category_id'],
                                        'qty'=>$post['quantity'],
                                        'min_alert'=>$post['min_alert'],
                                        'unit_id'=>$post['unit_id'],
                                        'status'=>$post['status'],
                                        'manuf_company'=>$post['manuf_company'],
                                       ); 
            return $data['form_data'];
            //print_r($data['form_data']);die;
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
                  $data_cat= $this->stock_item->get_by_id($post['data_id']);
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
              $this->form_validation->set_message('check_stockitem_name', 'The stock item field is required.');
              return false; 
       } 
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('170','987');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_item->delete($id);
           $response = "Item successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('170','987');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_item->deleteall($post['row_id']);
            $response = "Item successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->stock_item->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Item']." detail";
        $this->load->view('canteen/stock_item/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('170','988');
        $data['page_title'] = 'Item Archive List';
        $this->load->helper('url');
        $this->load->view('canteen/stock_item/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('170','988');
        $this->load->model('canteen/stock_item/stock_item_archive_model','stock_item_archive'); 

    
            $list = $this->stock_item_archive->get_datatables();
          // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $stock_item) { 
            $no++;
            $row = array();
            if($stock_item->status==1)
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
         

                    $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$stock_item->id.'">'.$check_script; 
            $qty_data = $this->stock_item->get_item_quantity($stock_item->id,$stock_item->category_id);
            $medicine_total_qty = $qty_data['total_qty'];
            $row[] = $stock_item->item_code;
            $row[] = $stock_item->item; 
            $row[] = $stock_item->price; 
            $row[] = $stock_item->category;
            if($stock_item->min_alert>=$qty_data['total_qty'])
            {
            $medicine_total_qty = '<div class="m_alert_red">'.$qty_data['total_qty'].'</div>';
            }
            if($qty_data['total_qty']>=0)
            {
            $row[] = $medicine_total_qty;
            }
            else
            {
            $row[]=0;
            }
            $row[] = $stock_item->min_alert;
            $row[] = $stock_item->unit;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($stock_item->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
         
               //if(in_array('181',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_stock_item('.$stock_item->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               //}
               //if(in_array('180',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$stock_item->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               //}
     
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_item_archive->count_all(),
                        "recordsFiltered" => $this->stock_item_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('170','988');
        $this->load->model('canteen/stock_item/stock_item_archive_model','stock_item_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_item_archive->restore($id);
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
                $this->session->set_userdata('stock_item_serach', $marge_post);

            }
                $stock_item_serach = $this->session->userdata('stock_item_serach');
                if(isset($stock_item_serach) && !empty($stock_item_serach))
                  {
                  $data['form_data'] = $stock_item_serach;
                   }
            $this->load->view('ot_booking/advance_search',$data);
    }

    function restoreall()
    { 
        //unauthorise_permission('170','988');
        $this->load->model('canteen/stock_item/stock_item_archive_model','stock_item_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_item_archive->restoreall($post['row_id']);
            $response = "Item successfully restore in item list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('170','989');
        $this->load->model('canteen/stock_item/stock_item_archive_model','stock_item_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_item_archive->trash($id);
           $response = "Item successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('170','989');
        $this->load->model('canteen/stock_item/stock_item_archive_model','stock_item_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_item_archive->trashall($post['row_id']);
            $response = "Item successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

     public function stock_item_dropdown(){

          $stock_item_list = $this->stock_item->stock_item_list();
          $dropdown = '<option value="">Select Item</option>'; 
          if(!empty($stock_item_list)){
               foreach($stock_item_list as $stock_item){
                    $dropdown .= '<option value="'.$stock_item->id.'">'.$stock_item->item.'</option>';
               }
          } 
          echo $dropdown; 
     }

     public function get_second_unit()
     {
       $unit_id= $this->input->post('unit_id');
        $get_subunit=$this->stock_item->get_sub_unit($unit_id);
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
       header("Content-Disposition: attachment; filename=stock_item_import_".time().".xls");  
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
                    // die; stock_item

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
                    $this->stock_item->save_all_item($item_list_data);
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

        $this->load->view('canteen/stock_item/import_stock_item_excel',$data);
    }
    
     function get_product_values($vals="")
    {

        if(!empty($vals))
        {
            $this->load->model('canteen/products/products_model','products');
            $result = $this->products->get_product_values($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
}
?>