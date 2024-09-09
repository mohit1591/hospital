<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_stock extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('medicine_stock/medicine_stock_model','medicine_stock');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
       unauthorise_permission(63,415);
        $data['page_title'] = 'Medicine stock list'; 
        $this->session->unset_userdata('stock_search');
        // Default Search Setting
        $this->load->model('default_search_setting/default_search_setting_model'); 
        
        // End Defaul Search
        $data['form_data']=array('batch_no'=>'');
        $this->load->view('medicine_stock/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(63,415);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->medicine_stock->get_datatables();  
        //print '<pre>';print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $medicine_stock) { 
            $no++;
            $row = array();
            if($medicine_stock->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($medicine_stock->state))
            {
                $state = " ( ".ucfirst(strtolower($medicine_stock->state))." )";
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
            //m_id
              // $qty_data = $this->medicine_stock->get_batch_med_qty($medicine_stock->m_id,$medicine_stock->batch_no);
            //if($qty_data['total_qty']>0){
            $row[] = '<input type="checkbox" name="'.$medicine_stock->batch_no.'" class="checklist" value="'.$medicine_stock->id.'">'.$check_script;
            /*}else{
             $row[] ='';   
            }*/
            $rack_name= rack_list($medicine_stock->rack_no);
            $row[] = $medicine_stock->medicine_name;
            $row[] = $medicine_stock->company_name;
            $row[] = $medicine_stock->packing;
            $row[] = $medicine_stock->batch_no;
            $row[] = $medicine_stock->barcode;
         
           //$medicine_total_qty = $qty_data['total_qty'];
            $medicine_total_qty = $medicine_stock->stock_qty;
            if($medicine_stock->min_alrt>=$medicine_stock->stock_qty) //$qty_data['total_qty'])
            {
            $medicine_total_qty = '<div class="m_alert_green">'.$medicine_stock->stock_qty.'</div>';
            }
             /*if($qty_data['total_qty']>=0){
             $row[] = $medicine_total_qty;
             }else{
             $row[]=0;
             }*/
             
             if(!empty($medicine_stock->batch_no))
            {
              $batch_no = "'".$medicine_stock->batch_no."'";
            }
            else if($medicine_stock->batch_no=='0')
            {
              $batch_no = 0;
            }
            else
            {
               $batch_no = "''"; 
            }
             
            $row[]=$medicine_total_qty;
            /*  if($qty_data['total_qty']>=0){
             $row[] = '<a  onclick="return change_quantity('.$medicine_stock->id.','.trim($batch_no).');" title="Update Quantity"> '.$medicine_total_qty.' </a>';
             }else{
             $row[]='<a  onclick="return change_quantity('.$medicine_stock->id.','.$batch_no.');" title="Update Quantity"> 0 </a>';
             }*/

            /////////// Start expire alert ////////
            $alert_days = get_setting_value('MEDICINE_EXPIRY_INTIMATION_DAY');
            $expire_timestamp = strtotime($medicine_stock->expiry_date)-(86400*$alert_days);
            $expire_alert_days = date('Y-m-d',$expire_timestamp);
            //echo $expire_alert_days;
            $current_date = date('Y-m-d');
            //echo $expire_alert_days;
            $expire_date = date('d-m-Y',strtotime($medicine_stock->expiry_date));
            //strtotime($medicine_sess[$medicine->id.'.'.$medicine->batch_no]["manuf_date"]) < 31536000
            /*if(($current_date>=$expire_alert_days || $medicine_stock->expiry_date!="0000-00-00") && $expire_date!='01-01-1970')
            { 
               $expire_date = '<div class="m_alert_orange">'.$expire_date.'</div>';
            }else{
               $expire_date = '<div class="m_alert_orange"></div>';  
            }*/
            if($medicine_stock->expiry_date!='0000-00-00' && $medicine_stock->expiry_date!='00-00-0000' && $expire_date!='01-01-1970' && $expire_date!='00-00-0000' && $expire_date!='0000-00-00')
            {
                
                $dateTimestamp1 = strtotime($current_date);
                $dateTimestamp2 = strtotime($expire_date);
                
              if ($dateTimestamp1 > $dateTimestamp2)
              {
                $expire_date = '<div class="m_alert_red">'.$expire_date.'</div>';  
              }
              elseif($current_date>=$expire_alert_days)
              {
                $expire_date = '<div class="m_alert_orange">'.$expire_date.'</div>';  
              }
              else
              {
                $expire_date = $expire_date; 
              }
              
            }
            else
            {
              $expire_date = '<div class="m_alert_orange"></div>'; 
            }
            $row[] = $expire_date;

            $row[] = $medicine_stock->rack_nu;
            //$row[] = $medicine_stock->min_alrt;
           
           
            $row[] = $medicine_stock->mrp;
            $row[] = $medicine_stock->purchase_rate;
            
            
            ////////// End Expire alert ////////////
            
            
            //$row[] = $status; 
            if(date('d-M-Y',strtotime($medicine_stock->stock_created_date))=='01-Jan-1970'){
              $row[]='';

            }else{
              $row[] = date('d-M-Y',strtotime($medicine_stock->stock_created_date));
            }
          
            $btn_history="";
            $btn_reset="";
             if($users_data['parent_id']==$medicine_stock->branch_id){
             		if(!empty($medicine_stock->batch_no))
             		{
             			$batchno = $medicine_stock->batch_no;
             		}
             		else
             		{
             			$batchno = 0;
             		}
             		
             		
                    $print_barcode_url = "'".base_url('medicine_stock/print_barcode/'.$medicine_stock->id.'/'.$batchno)."'";
                    $btn_barcode='';
                    if(!empty($batchno))
                    {
                    $btn_barcode = '<a  class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_barcode_url.')"  title="Print Barcode" ><i class="fa fa-barcode"></i> Print Barcode </a>';
                    }

             		
             		$medicine_ids = "'".$medicine_stock->id."' , '".$batchno."', '".$medicine_stock->stock_qty."'";
                    $btn_history = ' <a class="btn-custom" href="'.base_url('medicine_stock/medicine_allot_history?id='.$medicine_stock->id.'&batch_no='.$batchno.'&type=1').'" style="'.$medicine_stock->id.'" title="Edit"><i class="fa fa-history"></i> History</a>';
                    $btn_reset = ' <a class="btn-custom" href="javascript:void(0)" onclick="return reset_medicine_stock('.$medicine_ids.')"  title="Edit"><i class="fa fa-refresh"></i> Reset</a>';
                     
             }  
             $row[] = $btn_history.$btn_barcode.$btn_reset;
           
          
            $data[] = $row;
            $i++;
        }
        
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_stock->count_all(),
                        "recordsFiltered" => $this->medicine_stock->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }
    
    public function medicine_stock_excel()
    {
        unauthorise_permission(63,578);
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Medicine Name','Medicine Company','Packing','Batch No.','Quantity','Expiry Date','Rack No.','Min Alert','MRP','Purchase Rate','Create Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
               $col++;
          }
          $list =$this->medicine_stock->search_report_data();
          //print_r($list);die;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {

                $alert_days = get_setting_value('MEDICINE_EXPIRY_INTIMATION_DAY');
                $expire_timestamp = strtotime($reports->expiry_date)-(86400*$alert_days);
                $expire_alert_days = date('Y-m-d',$expire_timestamp);
                //echo $expire_alert_days;
                $current_date = date('Y-m-d');
                //echo $expire_alert_days;
                /*$expire_date = date('d-m-Y',strtotime($reports->expiry_date));
                if($current_date>=$expire_alert_days && $reports->expiry_date!="0000-00-00")
                { 
                $expire_date =$expire_date;
                }else{
                  $expire_date='';  
                }*/
                $expire_date='';
                if($reports->expiry_date =='1970-01-01' || $reports->expiry_date =='0000-00-00' || $reports->expiry_date =='')
                {
                   $expire_date='';
                }else{
                 $expire_date = date('d-m-Y',strtotime($reports->expiry_date));
                }



             /* $qty_data = $this->medicine_stock->get_batch_med_qty($reports->id,$reports->batch_no);
              $medicine_total_qty = $qty_data['total_qty'];
              if($reports->min_alrt>=$qty_data['total_qty'])
              {
                $medicine_total_qty = $qty_data['total_qty'];
              }
                    if($qty_data['total_qty']>=0){
                        $medicine_total_qty= $medicine_total_qty;
                        }else{
                        $medicine_total_qty=0;
                    }*/
                    
                    $medicine_total_qty = $reports->stock_qty;
                    if($medicine_stock->min_alrt>=$reports->stock_qty) //$qty_data['total_qty'])
                    {
                        $medicine_total_qty = $reports->stock_qty;
                    }
                    
                    array_push($rowData,$reports->medicine_name,$reports->company_name,$reports->packing,$reports->batch_no,$medicine_total_qty,$expire_date,$reports->rack_nu,$reports->min_alrt,$reports->mrp,$reports->purchase_rate,date('d-M-Y H:i A',strtotime($reports->stock_created_date)));
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
          header("Content-Disposition: attachment; filename=medicine_sales_return_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function medicine_stock_csv()
    {
        unauthorise_permission(63,579);
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Medicine Name','Medicine Company','Packing','Batch No.','Quantity','Expiry Date','Rack No.','Min Alert','MRP','Purchase Rate','Create Date');
        $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->medicine_stock->search_report_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
               
           $i=0;
           foreach($list as $reports)
           {
                $alert_days = get_setting_value('MEDICINE_EXPIRY_INTIMATION_DAY');
                $expire_timestamp = strtotime($reports->expiry_date)-(86400*$alert_days);
                $expire_alert_days = date('Y-m-d',$expire_timestamp);
                //echo $expire_alert_days;
                $current_date = date('Y-m-d');
                //echo $expire_alert_days;
                $expire_date='';
                if($reports->expiry_date =='1970-01-01' || $reports->expiry_date =='0000-00-00' || $reports->expiry_date =='')
                {
                   $expire_date='';
                }else{
                 $expire_date = date('d-m-Y',strtotime($reports->expiry_date));
                }
               /* $qty_data = $this->medicine_stock->get_batch_med_qty($reports->id,$reports->batch_no);
                $medicine_total_qty = $qty_data['total_qty'];
                if($reports->min_alrt>=$qty_data['total_qty'])
                {
                    $medicine_total_qty = $qty_data['total_qty'];
                }
                if($qty_data['total_qty']>=0){
                    $medicine_total_qty= $medicine_total_qty;
                    }else{
                    $medicine_total_qty=0;
                }*/
                
                $medicine_total_qty = $reports->stock_qty;
                    if($medicine_stock->min_alrt>=$reports->stock_qty) //$qty_data['total_qty'])
                    {
                        $medicine_total_qty = $reports->stock_qty;
                    }
                
                  array_push($rowData,$reports->medicine_name,$reports->company_name,$reports->packing,$reports->batch_no,$medicine_total_qty,$expire_date,$reports->rack_nu,$reports->min_alrt,$reports->mrp,$reports->purchase_rate,date('d-M-Y H:i A',strtotime($reports->stock_created_date)));
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
        header("Content-Disposition: attachment; filename=medicine_stock_report_".time().".csv");  
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
      
    }

    public function pdf_medicine_stock()
    {    
        unauthorise_permission(63,580);
        $data['print_status']="";
        $data['data_list'] = $this->medicine_stock->search_report_data();
        $this->load->view('medicine_stock/medicine_stock_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_stock_report_".time().".pdf");
    }
    public function print_medicine_stock()
    {    
      unauthorise_permission(63,581);
      $data['print_status']="1";
      $data['data_list'] = $this->medicine_stock->search_report_data();
      $this->load->view('medicine_stock/medicine_stock_report_html',$data); 
    }
  
    public function delete($id="")
    {
        // unauthorise_permission(63,124);
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_stock->delete($id);
           $response = "Medicine entry successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        // unauthorise_permission(63,124);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_stock->deleteall($post['row_id']);
            $response = "Medicine entry successfully deleted.";
            echo $response;
        }
    }

   

    ///// employee Archive Start  ///////////////
    public function archive()
    {
       // unauthorise_permission(63,126);
        $data['page_title'] = 'Medicine entry archive list';
        $this->load->helper('url');
        $this->load->view('medicine_stock/archive',$data);
    }

    public function archive_ajax_list0()
    {
        //unauthorise_permission(63,126);
        $this->load->model('medicine_stock/medicine_stock_archive_model','medicine_stock_archive'); 

        $list = $this->medicine_stock_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine_stock) { 
            $no++;
            $row = array();
            if($medicine_stock->status==1)
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

            $row[] = '<input type="checkbox" name="medicine_stock[]" class="checklist" value="'.$medicine_stock->id.'">'.$check_script;
            $rack_name= rack_list($medicine_stock->rack_no);
            $row[] = $medicine_stock->medicine_name;
            $row[] = $medicine_stock->packing;
            $row[] = $rack_name[0]->rack_no;
            $row[] = $medicine_stock->mrp;
            $row[] = $medicine_stock->purchase_rate;
            $row[] = $medicine_stock->discount;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($medicine_stock->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           // if(in_array('128',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_medicine_stock('.$medicine_stock->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           // }
           // if(in_array('127',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$medicine_stock->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           // }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_stock_archive->count_all(),
                        "recordsFiltered" => $this->medicine_stock_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       // unauthorise_permission(63,128);
        $this->load->model('medicine_stock/medicine_stock_archive_model','medicine_stock_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_stock_archive->restore($id);
           $response = "Medicine entry successfully restore in medicine entry list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       // unauthorise_permission(63,128);
        $this->load->model('medicine_stock/medicine_stock_archive_model','medicine_stock_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_stock_archive->restoreall($post['row_id']);
            $response = "Medicine entry successfully restore in medicine entry list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission(63,127);
        $this->load->model('medicine_stock/medicine_stock_archive_model','medicine_stock_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_stock_archive->trash($id);
           $response = "Medicine entry successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       // unauthorise_permission(63,127);
        $this->load->model('medicine_stock/medicine_stock_archive_model','medicine_stock_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_stock->trashall($post['row_id']);
            $response = "Medicine entry successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

    public function advance_search()
    {
          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
          $data['manuf_company_list'] = $this->medicine_entry->manuf_company_list();
          $data['unit_list'] = $this->medicine_stock->unit_list();
          $data['form_data'] = array(
                                    
                                    "batch_no"=>"",
                                    "type"=>'',
                                    "medicine_name"=>"",
                                    "medicine_code"=>"", 
                                    "medicine_company"=>"",
                                    "qty_from"=>"",
                                    "qty_to"=>"",
                                    "min_alert"=>"",
                                    "price_to_mrp"=>"",
                                    "price_from_mrp"=>"",
                                    "price_to_purchase"=>"",
                                    "price_from_purchase"=>"",
                                    "rack_no"=>"",
                                    "packing"=>"",
                                    "end_date"=>"",
                                    "expiry_to"=>"",
                                    "branch_ids"=>"",
                                    "expiry_from"=>"",
                                    "unit1"=>"",
                                    "unit2"=>""
                                   
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('stock_search', $marge_post);
          }
          $purchase_search = $this->session->userdata('stock_search');
          if(isset($purchase_search) && !empty($purchase_search))
          {
              $data['form_data'] = $purchase_search;
          }
          $this->load->view('medicine_stock/advance_search',$data);
   }

    public function reset_search()
    {
        $this->session->unset_userdata('stock_search');
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
     public function allotement_to_branch()
     {
        unauthorise_permission(63,582);
         $post = $this->input->post();

         $medicine_data = $this->session->userdata('alloted_medicine_ids');
         // $medicine_batch = $this->session->userdata('alloted_medicine_batch_nos');
         if(!empty($medicine_data))
         {
            $medicine_data = array();
            $this->session->set_userdata('alloted_medicine_ids',$medicine_data);
         }
        
         if(isset($post) && !empty($post))
         {
               $medicine_data = array();
               $i=0;
               foreach ($post['medicine_ids'] as $medicine_id) {
                    
                        $medicine_data[$i]['medicine_id'] = $post['medicine_ids'][$i];
                        $medicine_data[$i]['batch_no'] = $post['batch_no'][$i];
                    
                    $i++;
               }
              
 
              $this->session->set_userdata('alloted_medicine_ids', $medicine_data);
             
         } 
        
         $data['page_title'] = 'Medicine Allotment';
         
         $medicine_list = $this->medicine_stock->get_medicine_allot_list();
        

         $row='';
         $i=1;
         $total_num = count($medicine_list);
         if(!empty($medicine_list))
         {
               foreach($medicine_list as $medicine_data)
               {
                        $qty_data = $this->medicine_stock->get_batch_med_qty($medicine_data['m_id'],$medicine_data['batch_no']); 
                   
                    $row.= '<tr><td align="center">'.$i.'<input type="hidden" name="medicine['.$medicine_data['id'].'][mid]" class="medicinechecklist" value="'.$medicine_data['id'].'"></td><td>'.$medicine_data['medicine_name'].'</td><td>'.$medicine_data['medicine_code'].'</td><td>'.$medicine_data['batch_no'].'<input type="hidden" name="medicine['.$medicine_data['id'].'][batch_no]" class="medicinechecklist" value="'.$medicine_data['batch_no'].'"></td><td><input type="text" id="qty_'.$medicine_data['id'].'" name="medicine['.$medicine_data['id'].'][qty]" data-qty="'.$qty_data['total_qty'].'" data-id="'.$medicine_data['id'].'" value = "'.$qty_data['total_qty'].'" onblur="check_max_qty(this);"/><p id="msg_'.$medicine_data['id'].'" style="color:red;"></p></td></tr>';
                    $i++;
               }
          }else{
               $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
          }
          $data['medicine_list'] = $row;
       

        
         $this->load->view('medicine_stock/allot_to_branch',$data);
     }
     public function allot_medicine_to_branch()
     {
        unauthorise_permission(63,582);
          $post = $this->input->post();

          $users_data = $this->session->userdata('auth_users');
          $data['page_title'] = 'Medicine Allotment';
           $data['medicine_list'] = '';
          if(isset($post) && !empty($post))
          {
              if($users_data['users_role']=='2')
              {

                     $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                     $this->form_validation->set_rules('sub_branch_id', 'branch', 'trim|required');
                    
                     if($this->form_validation->run() == TRUE)
                     {
                    
                          
                            $this->medicine_stock->allot_medicine_to_branch();
                            echo 1;
                            return false;
                     }
                    
                    else
                    {
                       
                         // echo "hello";
                         // print_r($this->session->userdata('alloted_medicine_ids'));
                         // die;
                         $medicine_list = $this->medicine_stock->get_medicine_allot_list();
                         $row='';
                         $i=0;
                         $total_num = count($medicine_list);
                         if(!empty($medicine_list))
                         {
                              foreach($medicine_list as $medicine_data)
                              {
                                   $qty_data = $this->medicine_stock->get_batch_med_qty($medicine_data['m_id'],$medicine_data['batch_no']);
                                   $check_script='';
                                   if($i==$total_num)
                                   {
                                        // $check_script = 
                                        //      "<script>$('#getmedicineselectAll').on('click', function () { 
                                        //           if ($(this).hasClass('allChecked')) {
                                        //                          $('.medicinechecklist').prop('checked', false);
                                        //           } else {
                                        //           $('.medicinechecklist').prop('checked', true);
                                        //           }
                                        //           $(this).toggleClass('allChecked');
                                        //      })</script>";
                                   }
                                   $row.= '<tr><td align="center">'.$i.'<input type="hidden" name="medicine['.$medicine_data['id'].'][mid]" class="medicinechecklist" value="'.$medicine_data['id'].'"></td><td>'.$medicine_data['medicine_name'].'</td><td>'.$medicine_data['medicine_code'].'</td><td>'.$medicine_data['batch_no'].'<input type="hidden" name="medicine['.$medicine_data['id'].'][batch_no]" class="medicinechecklist" value="'.$medicine_data['batch_no'].'"></td><td><input type="text" id="qty_'.$medicine_data['id'].'" name="medicine['.$medicine_data['id'].'][qty]" data-qty="'.$qty_data['total_qty'].'" data-id="'.$medicine_data['id'].'" value = "'.$qty_data['total_qty'].'" onblur="check_max_qty(this);"/><p id="msg_'.$medicine_data['id'].'" style="color:red;"></p></td></tr>';
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
     public function medicine_allot_history($id='')
     {
          $data['id'] = $this->input->get('id');
          $data['batch_no'] = $this->input->get('batch_no');
          $data['type'] = $this->input->get('type');
          $data['page_title'] = 'Medicine Allot History List';
          $this->load->view('medicine_stock/medicine_allot_history',$data);
          // $this->load->view('medicine_stock/medicine_allot_history',$data);
     }
    public function medicine_allot_history_ajax()
    {  
        $post = $this->input->post();
        
        unauthorise_permission(63,415);
        $users_data = $this->session->userdata('auth_users');
         $this->load->model('medicine_entry/medicine_entry_model'); 
         $medicine_datas=$this->medicine_entry_model->get_by_id($post['medicine_id']);
        $list = $this->medicine_stock->get_medicine_allot_history_datatables($post['medicine_id'],$post['batch_no'],$post['type'],$medicine_datas['branch_id']);  
        //print '<pre>';print_r( $list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $medicine_stock) 
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
	            $row[] = $medicine_stock->branch_name;
	            $row[] = $medicine_stock->medicine_code;
	            $row[] = $medicine_stock->medicine_name;
	            $row[] = $medicine_stock->company_name;
	            $row[] = $medicine_stock->quantity;
	            $row[] = date('d-M-Y',strtotime($medicine_stock->created_date));
            
            }
            if($post['type']==6)
            {
	            //$row[] = $medicine_stock->branch_name;
	            $row[] = $medicine_stock->medicine_code;
	            $row[] = $medicine_stock->medicine_name;
	            $row[] = $medicine_stock->batch_no;
	            $row[] = $medicine_stock->quantity;
	            $row[] = date('d-M-Y',strtotime($medicine_stock->created_date));
            
            }
            else
            {
    				  $row[] = $medicine_stock->medicine_code;
    				  $row[] = $medicine_stock->vendor_name;
    				  $row[] = $medicine_stock->medicine_name;
    				  $row[] = $medicine_stock->company_name;
    				  $row[] = $medicine_stock->batch_no;
                      $row[] = $medicine_stock->bar_code;
    				  $row[] = $medicine_stock->qty;
    				  $row[] = date('d-M-Y',strtotime($medicine_stock->purchase_date));
            }
            
            
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_stock->get_medicine_allot_history_count_all($post['medicine_id'],$post['batch_no'],$post['type'],$medicine_datas['branch_id']),
                        "recordsFiltered" => $this->medicine_stock->get_medicine_allot_history_count_filtered($post['medicine_id'],$post['batch_no'],$post['type'],$medicine_datas['branch_id']),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }
    
    
    public function change_quantity()
    {
      //echo "<pre>"; print_r($_POST); die();
      
      $post = $this->input->post();
      $id = $post['id'];
      $batch_no = $post['batch_no'];
      //echo "<pre>"; print_r($post); die();
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->medicine_stock->get_by_id($id);
      //  echo "<pre>"; print_r($result); die();
        $medicine_name = $result['medicine_name'];
        $mrp = $result['mrp'];
        $company_name = $result['company_name'];
        $branch_id = $result['branch_id'];
        $data['page_title'] = "Update Quantity ".$medicine_name;  
        

        $data['form_error'] = '';
        $data['form_data'] = array(
                                  'id'=>$result['id'],
                                  'medicine_name'=>$medicine_name, 
                                  'mrp'=>$mrp,
                                  'branch_id'=>$branch_id,
                                  'batch_no'=>$batch_no,
                                  'company_name'=>$company_name,

                                  );  
        //print_r($data);die();
        if(!empty($post) && !empty($post['quantity']))
        {   
            //echo "<pre>"; print_r($post); exit;
            $data['form_data'] = $this->_validate_medicine_quantity();
            if($this->form_validation->run() == TRUE)
            {
             // print_r($data);die();
                $this->medicine_stock->update_quantity();
                echo 1;
                return false;
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('medicine_stock/medicine_quantity',$data);       
      }
    }
 private function _validate_medicine_quantity()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'id'=>$post['id'],
                                        'quantity'=>$post['quantity'], 
                                        'batch_no'=>$post['batch_no'],
                                       ); 
            return $data['form_data'];
        }   
    }
    
    
    public function reset_medicine_stock()
    {
        $post = $this->input->post();
        $medicine_data = $this->medicine_stock->reset_medicine_stock();
        echo 'Medicine Quantity Updated Successfully.';
    }
    public function print_barcode($id,$batch_no)
    {
        //$result = $this->medicine_stock->get_by_id($id);
        if(!empty($batch_no))
        {
          $data['barcode_id'] = $batch_no;
          $this->load->view('medicine_stock/barcode',$data);
        }
    }
    
    
}
