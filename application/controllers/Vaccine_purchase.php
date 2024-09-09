<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccine_purchase extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('vaccine_purchase/vaccine_purchase_model','vaccine_purchase');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
        unauthorise_permission(181,1074);
        $this->session->unset_userdata('vaccine_id'); 
        $this->session->unset_userdata('net_values_all');
        $this->session->unset_userdata('vaccine_purchase_search');
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
        $data['page_title'] = 'Vaccine Purchase Vaccination List'; 
        $this->load->view('vaccine_purchase/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(181,1074);
        $list = $this->vaccine_purchase->get_datatables();    
        $session_data= $this->session->userdata('auth_users');
       
        $assoc_array = json_decode(json_encode($list),TRUE);
        $total_net_amount = array_sum(array_column($assoc_array,'net_amount'));
        $total_discount = array_sum(array_column($assoc_array,'discount_percent'));
        $total_balance= array_sum(array_column($assoc_array,'balance'));
        //$total_vat= array_sum(array_column($assoc_array,'vat'));
        $total_paid_amount= array_sum(array_column($assoc_array,'paid_amount'));
        $session_new_datas=array('net_amount'=>$total_net_amount,'discount'=>$total_discount,'balance'=>$total_balance,'paid_amount'=>$total_paid_amount);
        $this->session->set_userdata('net_values_all',$session_new_datas); 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $vaccine_purchase) { 
           $btn_list='';
           $btn_download_image='';
            $no++;
            $row = array();
           
           
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }          
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="vaccine_purchase[]" class="checklist" value="'.$vaccine_purchase->id.'">';  
            $row[] = $vaccine_purchase->purchase_id;
            $row[] = $vaccine_purchase->invoice_id;
            $row[] = $vaccine_purchase->vendor_name;
            //$row[] = $vaccine_purchase->total_amount;
            $row[] = $vaccine_purchase->net_amount;
            $row[] = $vaccine_purchase->paid_amount;
            $row[] = $vaccine_purchase->balance;
            $row[] = date('d-M-Y',strtotime($vaccine_purchase->purchase_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
            if($session_data['parent_id']==$vaccine_purchase->branch_id){

           if(in_array('1076',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_purchase('.$vaccine_purchase->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vaccine_purchase->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            //if(in_array('125',$users_data['permission']['action'])){
                /*$btnview=' <a class="btn-custom" onclick="return view_purchase('.$vaccine_purchase->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';*/
           // }
            if(in_array('1077',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_purchase('.$vaccine_purchase->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }
          }

          $new_path=base_url().'vaccine_purchase/print_purchase_recipt';
          //$btnprint = '<a class="btn-custom" onclick="openPrintWindow(123,'.$vaccine_purchase->id.');" target="_blank"><i class="fa fa-print"></i> Print</a>';
          $print_url = "'".base_url('vaccine_purchase/print_purchase_recipt/'.$vaccine_purchase->id)."'";
          $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
          $btn_download_image = ' <a class="btn-custom" href="'.base_url('/vaccine_purchase/download_image/'.$vaccine_purchase->id.'/'.$vaccine_purchase->branch_id).'" title="Download Image" data-url="512" target="_blank"><i class="fa fa-download"></i> Download Image</a>';

           $btn_list = ' 
                  <div class="dropdown">
                  <a class="btn-custom toggle" data-toggle="dropdown" onClick="" href="javascript:void(0)"><i class="fa fa-download"></i> Download</a>
                    <div class="dropdown-menu">
                      '.$btnprint.$btn_download_image.'
                    </div>
                  </div>

               ';


            $row[] = $btnedit.$btnview.$btndelete.$btn_list;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccine_purchase->count_all(),
                        "recordsFiltered" => $this->vaccine_purchase->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

   public function download_image($ids="",$branch_id='')
    {
      $data['type'] = 2;
      $data['download_type'] = '2'; //for image
      $user_detail= $this->session->userdata('auth_users');
    $this->load->model('general/general_model');
    if(!empty($ids)){
       $purchase_id= $ids;
     }else{
       $purchase_id= $this->session->userdata('purchase_id');
     }
      $data['page_title'] = "Add vaccine purchase medicine";
      $get_detail_by_id= $this->vaccine_purchase->get_by_id($purchase_id);

      $get_by_id_data=$this->vaccine_purchase->get_all_detail_print($purchase_id,$get_detail_by_id['branch_id']);
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['mode_payment']);
      $template_format= $this->vaccine_purchase->template_format(array('section_id'=>7,'types'=>1,'sub_section'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;
      $this->load->view('vaccine_purchase/print_template_vaccination',$data);
    }
    public function save_image()
    {
        $post = $this->input->post();
        //print_r($post); exit;
        $data = $post['data'];
        $patient_name = $post['patient_name'];
        $patient_code = $post['patient_code'];
        $data = substr($data,strpos($data,",")+1);
        $data = base64_decode($data);
        $file_name = 'vaccine_purchase'.strtolower($patient_name).'_'.strtolower($patient_code).'.png';
        $download  = REPORT_IMAGE_FS_VACCINE_PATH.$file_name;
        $file = DIR_UPLOAD_REPORT_VACCINE_IMAGE_PATH.$file_name;
        file_put_contents($file, $data);

        echo  $download;

    }

    public function vaccination_purchase_excel()
    {
         $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vaccine Purchase No.','Invoice No.','Vendor Name','Net Amount','Paid Amount','Balance','Created Date');
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
          $list = $this->vaccine_purchase->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->purchase_id,$reports->invoice_id,$reports->vendor_name,$reports->net_amount,$reports->paid_amount,$reports->balance, date('d-M-Y H:i A',strtotime($reports->created_date)));
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
          header("Content-Disposition: attachment; filename=vaccination_purchase_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }





    public function vaccination_purchase_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vaccine Purchase No.','Invoice No.','Vendor Name','Net Amount','Paid Amount','Balance','Created Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->vaccine_purchase->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->purchase_id,$reports->invoice_id,$reports->vendor_name,$reports->net_amount,$reports->paid_amount,$reports->balance,date('d-M-Y H:i A',strtotime($reports->created_date)));
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
         header("Content-Disposition: attachment; filename=vaccination_purchase_report_".time().".csv");  
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }

    }

    public function pdf_vaccination_purchase()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->vaccine_purchase->search_report_data();
        $this->load->view('vaccine_purchase/vaccination_purchase_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("vaccination_purchase_report_".time().".pdf");
    }
    public function print_vaccination_purchase()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->vaccine_purchase->search_report_data();
      $this->load->view('vaccine_purchase/vaccination_purchase_report_html',$data); 
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
    public function ajax_list_medicine(){
       $medicine_list = $this->session->userdata('vaccine_id');
       $post = $this->input->post();  
       $ids=array();
       $table = '';
        if(!empty($medicine_list))
        { 
          $ids_arr= [];
          foreach($medicine_list as $key_m_arr=>$m_arr)
          {
             $ids_arr[] = $key_m_arr;
          }
          $medicine_ids = implode(',', $ids_arr);
          $data['medicne_new_list'] = $this->vaccine_purchase->medicine_list($medicine_ids);
           foreach($data['medicne_new_list'] as $medicine_list){
                           $ids[]=$medicine_list->id;
           }
        }

        $this->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
        $keywords= $this->input->post('search_keyword');
        $name= $this->input->post('name'); 
        if(!empty($post['vaccination_name']) || !empty($post['vaccination_code']) || !empty($post['bar_code']) || !empty($post['vaccine_company']) || !empty($post['conv']) || !empty($post['mfc_date']) || !empty($post['unit1']) ||  !empty($post['unit2']) || !empty($post['mrp']) || $post['mrp']==0 || !empty($post['p_rate']) || !empty($post['discount']) || $post['discount']==0 || !empty($post['cgst']) || $post['cgst']==0 || !empty($post['hsn_no']) || !empty($post['igst']) || !empty($post['hsn_no']) || $post['igst']==0 || !empty($post['sgst']) || $post['sgst']==0 ||!empty($post['packing']))
        { 
        
          $result_medicine = $this->vaccine_purchase->medicine_list_search();  
        } 

        if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids))
        {
          foreach($result_medicine as $medicine)
          {
              if(!in_array($medicine->id,$ids))
              {
                  $table.='<tr class="append_row">';
                  $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$medicine->id.'" onclick="add_check();"></td>';
                  $table.='<td>'.$medicine->vaccination_name.'</td>';
                  $table.='<td>'.$medicine->packing.'</td>';
                  $table.='<td>'.$medicine->conversion.'</td>';
                  $table.='<td>'.$medicine->vaccination_code.'</td>';
                  $table.='<td>'.$medicine->hsn_no.'</td>';
                  $table.='<td>'.$medicine->company_name.'</td>';
                  $table.='<td>'.$medicine->bar_code.'<input type="hidden" name="bar_code[]" value="'.$medicine->bar_code.'" id="bar_code_'.$medicine->id.'"/></td>';
                  // $table.='<td>'.date('d-m-Y',strtotime($medicine->created_date)).'</td>';
                  $table.='<td>'.$medicine->vaccination_unit.'</td>';
                  $table.='<td>'.$medicine->vaccination_unit_2.'</td>';
                  $table.='<td>'.$medicine->mrp.'</td>';
                  $table.='<td>'.$medicine->purchase_rate.'</td>';
                  $table.='<td>'.$medicine->discount.'</td>';
                  $table.='<td>'.$medicine->cgst.'</td>';
                  $table.='<td>'.$medicine->sgst.'</td>';
                  $table.='<td>'.$medicine->igst.'</td>';
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

    public function advance_search()
      {
          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                      "vendor_code"=>"", 
                                      "vendor_name"=>"",
                                      "simulation_id"=>"",
                                      "mobile_no"=>"",
                                      "invoice_id"=>"",
                                      "purchase_no"=>"",
                                      "vaccination_name"=>"",
                                      "vaccination_company"=>"",
                                      "vaccination_code"=>"",
                                      "purchase_rate"=>"",
                                      "discount"=>"",
                                      "end_date"=>"",
                                      "igst"=>"",
                                      "cgst"=>"",
                                      "sgst"=>"",
                                      "batch_no"=>"",
                                      "unit1"=>"",
                                      "unit2"=>"",
                                      "packing"=>"",
                                      "conversion"=>"",
                                      "paid_amount_to"=>"",
                                      "paid_amount_from"=>"",
                                      "balance_to"=>"",
                                      "mrp_to"=>"",
                                      "mrp_from"=>"",
                                      "balance_from"=>"",
                                      "total_amount_to"=>"",
                                      "total_amount_from"=>"", 
                                      "status"=>"", 
                                      "bank_name"=>"",
                                      "branch_id"=>""
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('vaccine_purchase_search', $marge_post);
          }
          $vaccine_purchase_search = $this->session->userdata('vaccine_purchase_search');
          if(isset($vaccine_purchase_search) && !empty($vaccine_purchase_search))
          {
              $data['form_data'] = $vaccine_purchase_search;
          }
          $this->load->view('vaccine_purchase/advance_search',$data);
   }

   public function reset_search()
    {
        $this->session->unset_userdata('vaccine_purchase_search');
    }

    public function ajax_added_vaccine(){

         $this->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
         $medicine_sess = $this->session->userdata('vaccine_id');
        //echo '<pre>';print_r($medicine_sess);die;
         $check_script="";
         $result_medicine = [];
        if(!empty($medicine_sess))
        { 
          $ids_arr= [];
          foreach($medicine_sess as $key_m_arr=>$m_arr)
          {
             $ids_arr[] = $key_m_arr;
          }
          $medicine_ids = implode(',', $ids_arr);
          $result_medicine = $this->vaccine_purchase->medicine_list($medicine_ids);
           foreach($result_medicine as $medicine_list){
                           $ids[]=$medicine_list->id;
           }
        }
     // $setting_data= get_setting_value('MEDICINE_VAT');
        $table='<div class="box_scroll">';
             $table.='<table class="table table-bordered table-striped m_pur_tbl1">';
               $table.='<thead class="bg-theme">';
                    $table.='<tr>';
                      $table.='<th class="40" align="center">';
                       $table.='<input type="checkbox" name="" onClick="toggle_new(this);">';
                       $table.='</th>';
                        $table.='<th>Vaccination name</th>';
                        $table.='<th>Vaccination Code</th>';
                        $table.='<th>HSN No.</th>';
                        $table.='<th>Packing</th>';
                        $table.='<th>Batch No.</th>';
                        $table.='<th>Conv.</th>';
                        $table.='<th>Mfg. Date</th>';
                        $table.='<th>Exp. Date</th>';
                        $table.='<th>Barcode</th>';
                        $table.='<th>Unit1</th>';
                        $table.='<th>Unit2</th>';
                         $table.='<th>Free Unit1</th>';
                        $table.='<th>Free Unit2</th>';
                       // $table.='<th>Free</th>';
                        $table.='<th>MRP</th>';
                        $table.='<th>Purchase Rate</th>';
                        $table.='<th>Discount(%)</th>';
                        $table.='<th>CGST (%)</th>';
                        $table.='<th>SGST (%)</th>';
                        $table.='<th>IGST (%)</th>';
                        //$table.='<th>'.get_setting_value('MEDICINE_VAT_NAME').'</th>';
                        /*$table.='<th>Quantity</th>';*/
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids)){
                        foreach($result_medicine as $medicine){
                            if($medicine_sess[$medicine->id]["exp_date"]=="00-00-0000"){

                                $date_new=date('d-m-Y');;
                            }else{
                                $date_new=$medicine_sess[$medicine->id]["exp_date"];
                            }
                            if($medicine_sess[$medicine->id]["manuf_date"]=="00-00-0000"){

                                $date_newma=date('d-m-Y');
                            }else{
                                $date_newma=$medicine_sess[$medicine->id]["manuf_date"];
                            }
                        $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$medicine->id."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate:  '".$date_new."',
                        });
                       
                        </script>";

                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$medicine->id."').datepicker({
                              format: 'dd-mm-yyyy',
                              autoclose: true,
                              endDate: '".$date_newma."',
                            
                        });
                        
                          $('#discount_".$medicine->id."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#igst_".$medicine->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100');
                                 
                            }

                          });
                           $('#sgst_".$medicine->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100');
                                 
                            }
                            
                          });
                             $('#cgst_".$medicine->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100');
                                 
                            }
                            
                          });
                         
                        </script>";
                        	//if(!in_array($medicine->id,$ids)){ 
                        $table.='<tr><input type="hidden" id="medicine_id_'.$medicine->id.'" name="m_id[]" value="'.$medicine->id.'"/><input type="hidden" value="'.$medicine->conversion.'"  name="conversion[]" id="conversion_'.$medicine->id.'" />';
                        $table.='<td><input type="checkbox" name="vaccine_id[]" class="booked_checkbox" value="'.$medicine->id.'"></td>';
                        $table.='<td>'.$medicine->vaccination_name.'</td>';
                        $table.='<td>'.$medicine->vaccination_code.'</td>';
                         $table.='<td><input type="text" name="hsn_no[]" placeholder="HSN No." style="width:59px;" id="hsn_no_'.$medicine->id.'" value="'.$medicine_sess[$medicine->id]["hsn_no"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';

                        $table.='<td>'.$medicine->packing.'</td>';

                        $table.='<td><input type="text" value="'.$medicine_sess[$medicine->id]["batch_no"].'" name="batch_no[]" class="datepicker" placeholder="Batch Number" style="width:84px;" id="batch_no_'.$medicine->id.'" onchange="payment_cal_perrow('.$medicine->id.');"/></td>';
                        $table.='<td>'.$medicine->conversion.'</td>';

                        $table.='<td><input type="text" value="'.$date_newma.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$medicine->id.'" onchange="payment_cal_perrow('.$medicine->id.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine->id.'" onchange="payment_cal_perrow('.$medicine->id.');"/>'.$check_script.'</td>';

                        $table.='<td><input type="text" value="'.$medicine_sess[$medicine->id]["bar_code"].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$medicine->id.'" onkeyup="payment_cal_perrow('.$medicine->id.');validation_bar_code('.$medicine->id.');"/><div  id="barcode_error_'.$medicine->id.'"  style="color:red;"></div></td>';
                        
                        $table.='<td><input type="text" name="unit1[]" placeholder="Unit1" style="width:59px;" id="unit1_'.$medicine->id.'" value="'.$medicine_sess[$medicine->id]["unit1"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';
                        $table.='<td><input type="text" name="unit2[]" placeholder="Unit2" style="width:59px;" id="unit2_'.$medicine->id.'" value="'.$medicine_sess[$medicine->id]["unit2"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';

                        $table.='<td><input type="text" name="freeunit1[]" placeholder="Free Unit1" style="width:59px;" id="freeunit1_'.$medicine->id.'" value="'.$medicine_sess[$medicine->id]["freeunit1"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';
                        $table.='<td><input type="text" name="freeunit2[]" placeholder="Free Unit2" style="width:59px;" id="freeunit2_'.$medicine->id.'" value="'.$medicine_sess[$medicine->id]["freeunit2"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';
                       // $table.='<td></td>';
                        $table.='<td><input type="text" id="mrp_'.$medicine->id.'" name="mrp[]" value="'.$medicine_sess[$medicine->id]["mrp"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';
                        $table.='<td><input type="text" id="purchase_rate_'.$medicine->id.'" name="purchase_rate[]" value="'.$medicine_sess[$medicine->id]["purchase_amount"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$medicine->id.'" value="'.$medicine_sess[$medicine->id]["discount"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';
                       /* $table.='<td><input type="text" class="price_float" name="vat[]" placeholder="Vat" style="width:59px;" value="'.$medicine_sess[$medicine->id]["vat"].'" id="vat_'.$medicine->id.'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';*/
                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$medicine_sess[$medicine->id]["cgst"].'" id="cgst_'.$medicine->id.'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';

                         $table.='<td><input type="text" class="price_float" name="SGST[]" placeholder="SGST" style="width:59px;" value="'.$medicine_sess[$medicine->id]["sgst"].'" id="sgst_'.$medicine->id.'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';

                          $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$medicine_sess[$medicine->id]["igst"].'" id="igst_'.$medicine->id.'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';

                        /*$table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$medicine->id.'" value="'.$medicine_sess[$medicine->id]["qty"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';*/
                      
                        $table.=' <td><input type="text" value="'.$medicine_sess[$medicine->id]["total_amount"].'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$medicine->id.'" /></td>';
                       
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
                        $table.='<a class="btn-new" onclick="medicine_list_vals();">Delete</a>';
                        $table.='</div>'; 
                     $output=array('data'=>$table);
                     echo json_encode($output);
        }
    function check_bar_code()
    {
      $bar_code= $this->input->post('bar_code');
      $mbid= $this->input->post('mbid');
      if(!empty($bar_code))
      {
        $return= $this->vaccine_purchase->check_bar_code($bar_code,$mbid);
        if($return==2)
        {
          echo '1';exit;
        }
        else
        {
          echo '0';exit;
        }
      }
    }

    public function set_medicine()
    {
       
       $this->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
       $post =  $this->input->post();
       if(isset($post['vaccine_id']) && !empty($post['vaccine_id']))
       {
         $vaccine_purchase = $this->session->userdata('vaccine_id');
         $vaccine_id = [];
         $mid_arr = [];
         if(isset($vaccine_purchase) && !empty($vaccine_purchase))
         { 
           $total_amount=0;
            $post_mid_arr = [];
            $i=0;
            foreach($post['vaccine_id'] as $m_ids)
            {
               $medicine_data = $this->vaccine_purchase->medicine_list($m_ids);
               //print_r($medicine_data);
                $ratewithunit1= $medicine_data[0]->purchase_rate*0;
                $qty=0;
                //$ratewithunit1= $medicine_data[0]->purchase_rate*$post['unit1'];
                $perpic_rate=$medicine_data[0]->purchase_rate/$medicine_data[0]->conversion;
                $ratewithunit2=$perpic_rate*0;
                $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;

               /* $cgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;
                $total_discount = ($medicine_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/

                 $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $medicine_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;

                $post_mid_arr[$m_ids] = array('vid'=>$m_ids,'unit1'=>0,'unit2'=>0,'conversion'=>0,'perpic_rate'=>$perpic_rate,'manuf_date'=>'00-00-0000','batch_no'=>0,'freeunit1'=>0,'freeunit2'=>0,'hsn_no'=>$medicine_data[0]->hsn_no,'qty'=>'1','freeqty'=>'1', 'exp_date'=>'00-00-0000','discount'=>$medicine_data[0]->discount,'mrp'=>$medicine_data[0]->mrp,'cgst'=>$medicine_data[0]->cgst,'igst'=>$medicine_data[0]->igst,'sgst'=>$medicine_data[0]->sgst,'qty'=>$qty,'purchase_amount'=>$medicine_data[0]->purchase_rate, 'total_amount'=>$total_amount,'bar_code'=>$post['barcode'][$i]); 
                $mid_arr[] = $m_ids;
                $i++;
            } 
            
            $vaccine_id = $vaccine_purchase+$post_mid_arr;
            
         } 
         else
         {
            $total_amount=0;
            $i=0;

            foreach($post['vaccine_id'] as $m_ids)
            {

              $medicine_data = $this->vaccine_purchase->medicine_list($m_ids);

              $ratewithunit1= $medicine_data[0]->purchase_rate*0;
              //$ratewithunit1= $medicine_data[0]->purchase_rate*$post['unit1'];
              $qty=0;
              $freeqty=0;
              $perpic_rate=$medicine_data[0]->purchase_rate/$medicine_data[0]->conversion;
              $ratewithunit2=$perpic_rate*0;
              $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                
                /*$cgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;
                $total_discount = ($medicine_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/

                $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $medicine_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;



                $medicine_data = $this->vaccine_purchase->medicine_list($m_ids);
                $post_mid_arr[$m_ids] = array('vid'=>$m_ids,'unit1'=>0,'unit2'=>0,'conversion'=>0,'batch_no'=>0,'manuf_date'=>'00-00-0000','perpic_rate'=>$perpic_rate,'freeunit1'=>0,'freeunit2'=>0,'hsn_no'=>$medicine_data[0]->hsn_no,'qty'=>'1', 'exp_date'=>'00-00-0000', 'purchase_amount'=>$medicine_data[0]->purchase_rate,'mrp'=>$medicine_data[0]->mrp,'qty'=>$qty,'freeqty'=>$freeqty,'discount'=>$medicine_data[0]->discount,'cgst'=>$medicine_data[0]->cgst,'igst'=>$medicine_data[0]->igst,'sgst'=>$medicine_data[0]->sgst,'total_amount'=>$total_amount,'bar_code'=>$post['barcode'][$i]); 
                $mid_arr[] = $m_ids;
                $i++;
            }
            $vaccine_id = $post_mid_arr;
            
         } 
         $medicine_ids = implode(',',$mid_arr);
         $this->session->set_userdata('vaccine_id',$vaccine_id);
         //print_r($this->session->userdata('vaccine_id'));
         $this->ajax_added_vaccine();
       }
    }

     public function remove_vaccination_list()
    {

    	$this->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
       $post =  $this->input->post();
       if(isset($post['vaccine_id']) && !empty($post['vaccine_id']))
       {
           $ids_list = $this->session->userdata('vaccine_id');
           
           	 foreach($post['vaccine_id'] as $post_id)
           	 {
                  if(array_key_exists($post_id,$ids_list))
                  {
                  	 unset($ids_list[$post_id]);
                  }
           	 } 
           	 $this->session->set_userdata('vaccine_id',$ids_list);
           
           $medicne_list = [];
           $ids_list = $this->session->userdata('vaccine_id');  
         /*  $id_arr = []; 
           if(!empty($ids_list))
           {
             foreach($ids_list as $id_key=>$id_arr)
             {
               $id_arr[] = $id_key;
             }
           }
           $imp_ids = implode(',', $id_arr); 
           $medicine_listdata = [];
           if(!empty($imp_ids))
           {
           	$medicine_listdata = $this->vaccine_purchase->medicine_list($imp_ids);
           }*/
           $this->ajax_added_vaccine();
       }
    } 

	public function add($pid="")
	{
     //print_r($_POST);die;
        unauthorise_permission(181,1075);
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Purchase Vaccine";
        $data['button_value'] = "Save";
        $data['form_error'] = [];
        $post = $this->input->post();
        $vendor_id='';
        $purchase_no = "";
        $vendor_code = "";
        $name = "";
        $patient_name = "";
        $mobile_no = "";
        $email = "";
        $address = "";
       if($pid>0)
        {
           $vendor = $this->vaccine_purchase->get_vendor_by_id($pid);
           //print_r($vaccine_purchase);
           if(!empty($vendor))
           {
              $vendor_id = $vendor['id'];
              $vendor_code = $vendor['vendor_id'];
              $name = $vendor['name'];
              $mobile_no = $vendor['mobile'];
              $address = $vendor['address'];
              $email = $vendor['email'];
              }
        }else{
          $vendor_code=generate_unique_id(11);
          $medicine_list = $this->session->userdata('vaccine_id');
          $data['vaccine_id'] = $medicine_list;
        }
        if(!empty($medicine_list))
        { 
          $medicine_id_arr = [];
          foreach($medicine_list as $key=>$medicine_sess)
          {
             $medicine_id_arr[] = $key;
          } 
          $medicine_ids = implode(',', $medicine_id_arr);
          $data['medicne_new_list'] = $this->vaccine_purchase->medicine_list($medicine_ids);
        }
        $data['unit_list'] = $this->vaccine_purchase->unit_list();
        $invoice_no = generate_unique_id(12);
        $data['payment_mode']=$this->general_model->payment_mode();
       // echo $invoice_no;
        $purchase_no = generate_unique_id(37);
        //echo $purchase_no;
    		$data['form_data'] = array(
                                    "data_id"=>"",
                                    'vendor_id'=> $pid,
                                    "vendor_code"=> $vendor_code,
                                    "name"=>$name,
                                    'invoice_id'=>"",
                                    'purchase_no'=>$purchase_no,
                                    "address"=>$address,
                                    "mobile"=>$mobile_no,
                                    "email"=>$email,
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "remarks"=>"",
                                    "purchase_date"=>date('d-m-Y'),
                                    'total_amount'=>"0.00",
                                    'discount_amount'=>"0.00",
                                    'payment_mode'=>"",
                                    "field_name"=>'',
                                    //'bank_name'=>"",
                                    //'card_no'=>"",
                                    //'cheque_no'=>"",
                                    //'payment_date'=>date('d-m-Y'),
                                    'net_amount'=>"",
                                    //'vat_amount'=>"",
                                    'igst_amount'=>'',
                                    'sgst_amount'=>'',
                                    'cgst_amount'=>'',
                                   //'vat_percent'=>get_setting_value('MEDICINE_VAT_VALUE'),
                                    'discount_percent'=>'',
                                    'pay_amount'=>"",
                                    //'transaction_no'=>"",
                                    "country_code"=>"+91"
    			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $return_id= $this->vaccine_purchase->save();
              if(!empty($return_id))
              {
                
                $get_by_id_data = $this->vaccine_purchase->get_by_id($return_id);
                $get_vendor_by_id = $this->vaccine_purchase->get_vendor_by_id($get_by_id_data['vendor_id']);
                //print_r($get_by_id_data); exit;
                $vendor_name = $get_vendor_by_id['name'];
                $mobile_no = $get_vendor_by_id['mobile'];
                 $email = $get_vendor_by_id['email'];
                //print_r($get_by_id_data); exit;
                $purchase_id = $get_by_id_data['purchase_id'];
                $paid_amount = $get_by_id_data['paid_amount'];
                $net_amount = $get_by_id_data['net_amount'];
                //check permission
                if(in_array('640',$users_data['permission']['action']))
                {
                  
                  if(!empty($mobile_no))
                  {
                    send_sms('purchase_vaccine',6,$vendor_name,$mobile_no,array('{Name}'=>$vendor_name,'{Amt}'=>$net_amount,'{BillNo}'=>$purchase_id,'{PaidAmt}'=>$paid_amount)); 
                  }
              }

              if(in_array('641',$users_data['permission']['action']))
              {
                if(!empty($email))
                {
                  
                  $this->load->library('general_functions');
                  $this->general_functions->email($email,'','','','','1','purchase_vaccine','6',array('{Name}'=>$vendor_name,'{Amt}'=>$net_amount,'{BillNo}'=>$purchase_id,'{PaidAmt}'=>$paid_amount));
                   
                }
              }
            }
                $this->session->set_userdata('purchase_id',$return_id);
                $this->session->set_flashdata('success','Purchase has been successfully added.');
                redirect(base_url('vaccine_purchase/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       // print_r($data['form_data']);
      $this->load->view('vaccine_purchase/add',$data);
	}

	public function edit($id="")
    {
     unauthorise_permission(181,1076);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
         $post = $this->input->post();
         $result = $this->vaccine_purchase->get_by_id($id); 
         $medicine_id_arr=[];
         $result_vendor = $this->vaccine_purchase->get_vendor_by_id($result['vendor_id']);
         if(empty($post))
         { 
            $result_medince_list = $this->vaccine_purchase->get_medicine_by_purchase_id($id);
            //print_r($result_medince_list); 
            $this->session->set_userdata('vaccine_id',$result_medince_list);
        }
         $medicine_list = $this->session->userdata('vaccine_id');
         //print_r($medicine_list);die;
         $data['vaccine_id'] = $medicine_list;
         $data['id'] = $id;
        //print '<pre>';print_r($medicine_list);
        if(!empty($medicine_list))
        { 
          $medicine_id_arr = [];
          foreach($medicine_list  as $key=>$val)
          { 
             $medicine_id_arr[] = $key;
          } 
          $medicine_ids = implode(',', $medicine_id_arr);
          $data['medicne_new_list'] = $this->vaccine_purchase->medicine_list($medicine_ids);
          
        }

         $data['unit_list'] = $this->vaccine_purchase->unit_list();
       //print '<pre>';print_r($data['medicne_new_list']);
        $reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
        
        $data['payment_mode']=$this->general_model->payment_mode();
        //echo $result['mode_payment'];die;
        $get_payment_detail= $this->vaccine_purchase->payment_mode_detail_according_to_field($result['mode_payment'],$id);

        $total_values='';
        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
        //$data['manuf_company_list'] = $this->vaccine_purchase->manuf_company_list();
        $data['page_title'] = "Vaccine purchase";  
         $data['button_value'] = "Update";
        $data['form_error'] = ''; 
        $data['form_data'] = array(

                                    "vendor_id"=> $result['vendor_id'],
                                    "vendor_code"=>$result_vendor['vendor_id'],
                                    "name"=>$result_vendor['name'],
                                    'invoice_id'=>$result['invoice_id'],
                                    'purchase_no'=>$result['purchase_id'],
                                    "address"=>$result_vendor['address'],
                                    "mobile"=>$result_vendor['mobile'],
                                    "email"=>$result_vendor['email'],
                                    "data_id"=>$result['id'],
                                    "field_name"=>$total_values,
                                    "branch_id"=>$result['branch_id'],
                                    "purchase_date"=>date('d-m-Y',strtotime($result['purchase_date'])),
                                    'total_amount'=>$result['total_amount'],
                                    'discount_amount'=>$result['discount'],
                                    'payment_mode'=>$result['mode_payment'],
                                    'bank_name'=>$result['bank_name'],
                                    'card_no'=>$result['card_no'],
                                    'cheque_no'=>$result['cheque_no'],
                                    'net_amount'=>$result['net_amount'],
                                     "remarks"=>$result['remarks'],
                                    "field_name"=>$total_values,
                                    //'vat_amount'=>$result['vat'],
                                   // 'vat_percent'=>$result['vat_percent'],
                                    'igst_amount'=>$result['igst'],
                                    'sgst_amount'=>$result['sgst'],
                                    'cgst_amount'=>$result['cgst'],
                                    'discount_percent'=>$result['discount_percent'],
                                    'pay_amount'=>$result['paid_amount'],
                                    'payment_date'=>date('d-m-Y',strtotime($result['payment_date'])),
                                    "country_code"=>"+91"
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                 $return_id= $this->vaccine_purchase->save();
                 $this->session->set_userdata('purchase_id',$return_id);
                 $this->session->set_flashdata('success','Purchase has been successfully updated.');
                 redirect(base_url('vaccine_purchase/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  

            }     
        }
       //print '<pre>'; print_r($data);die;
        $this->load->view('vaccine_purchase/add',$data);  

      }
    }
    
   public function payment_calc_all()
    { 
       $medicine_list = $this->session->userdata('vaccine_id');
      
       if(!empty($medicine_list))
       {
        $post = $this->input->post();
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
        //print '<pre>'; print_r($medicine_list);die;
        $i=0;
        foreach($medicine_list as $medicine)
        {    
            $signal_unit1_price = $medicine['purchase_amount']*$medicine['unit1'];
            $signal_unit2_price = ($medicine['purchase_amount']/$medicine['conversion'])*$medicine['unit2'];
            //$total_amount += ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$medicine['discount']);
            $total_amount += $signal_unit1_price+$signal_unit2_price;
            $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$medicine['discount']);
            $total_cgst += ($total_row_amount/100)*$medicine['cgst']; 
            $total_sgst += ($total_row_amount/100)*$medicine['sgst'];
            $total_igst += ($total_row_amount/100)*$medicine['igst']; 
            $tot_discount_amount+= (($signal_unit1_price+$signal_unit2_price)/100*$medicine['discount']);
            $i++;
        } 

        if($post['discount']!='' && $post['discount']!=0){
          
        $total_discount = ($post['discount']/100)* $total_amount;

        }else{
          
        $total_discount=$tot_discount_amount;
        }

       // $total_discount = ($post['discount']/100)* $total_amount;
        $net_amount = ($total_amount-$total_discount)+$total_cgst+$total_igst+$total_sgst;
         if($post['pay']==1 || $post['data_id']!=''){
           $payamount=$post['pay_amount'];
        }else{
          $payamount=$net_amount;
        }
         
      
        $blance_due=$net_amount - $payamount;
        $total_cgst = number_format($total_cgst,2,'.','');
        $total_igst = number_format($total_igst,2,'.','');
        $total_sgst = number_format($total_sgst,2,'.','');
        $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'discount'=>$post['discount'],'igst'=>$post['igst'],'sgst'=>$post['sgst'],'cgst'=>$post['cgst'],'sgst_amount'=>$total_sgst,'igst_amount'=>$total_igst,'cgst_amount'=> $total_cgst,'balance_due'=>number_format($blance_due,2,'.',''),'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 

public function payment_cal_perrow()
    {
       $this->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
       $post = $this->input->post();
       $total_amount='';
       if(isset($post) && !empty($post))
       {
         $total_amount = 0;
         $medicine_list = $this->session->userdata('vaccine_id');
        
         if(!empty($medicine_list))
         { 
            $medicine_data = $this->vaccine_purchase->medicine_list($post['vaccine_id']);
            $ratewithunit1= $post['purchase_rate']*$post['unit1'];
            $perpic_rate=  $post['purchase_rate']/$post['conversion'];
//echo $perpic_rate;
            $ratewithunit2=$perpic_rate*$post['unit2'];
            $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
            //echo $tot_qty_with_rate;
            //$tot_qty_with_rate= $medicine_data[0]->purchase_rate*$post['unit1'];
            $qty=($post['conversion']*$post['unit1'])+$post['unit2'];
            $freeqty=($post['conversion']*$post['freeunit1'])+$post['freeunit2'];
            //echo $qty;
            $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            $tot_price=$tot_qty_with_rate-$total_discount;

            $cgstToPay = ($tot_price / 100) * $post['cgst'];
            $igstToPay = ($tot_price / 100) * $post['igst'];
            $sgstToPay = ($tot_price / 100) * $post['sgst'];
            $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;

             $medicine_list[$post['vaccine_id']] =  array('vid'=>$post['vaccine_id'],'freeunit1'=>$post['freeunit1'],'freeunit2'=>$post['freeunit2'],'unit1'=>$post['unit1'],'unit2'=>$post['unit2'],'manuf_date'=>$post['manuf_date'],'conversion'=>$post['conversion'],'perpic_rate'=>$perpic_rate,'batch_no'=>$post['batch_no'],'hsn_no'=>$post['hsn_no'],'exp_date'=>$post['expiry_date'],'qty'=>$qty,'freeqty'=>$freeqty,'mrp'=>$post['mrp'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'purchase_amount'=>$post['purchase_rate'], 'total_amount'=>$total_amount,'total_price'=>$total_amount,'bar_code'=>$post['bar_code']); 
            $this->session->set_userdata('vaccine_id', $medicine_list);
            $pay_arr = array('total_amount'=>number_format($total_amount,2));
            $json = json_encode($pay_arr,true);
            echo $json;
         }
       }
    }
    private function _validate()
    {

        $post = $this->input->post(); 
        $total_values=array(); 
        if(isset($post['field_name']))
          {
        $count_field_names= count($post['field_name']);  
        
        $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
        $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        } 
        } 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'vendor name', 'trim|required');
          $this->form_validation->set_rules('pay_amount', 'Pay amount', 'trim|required');
          $this->form_validation->set_rules('email', 'Email', 'valid_email');
          if(isset($post['field_name']))
          {
             $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
          }
         

        /*if(isset($_POST['payment_mode']) && $_POST['payment_mode']==2) {
        $this->form_validation->set_rules('card_no', 'Transaction no', 'trim|required');
         }
         if(isset($_POST['payment_mode']) && $_POST['payment_mode']==3) {
          $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
          $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');
          $this->form_validation->set_rules('payment_date', 'cheque date', 'trim|required');
        
         }
       if(isset($_POST['payment_mode']) && $_POST['payment_mode']==4) {
         $this->form_validation->set_rules('transaction_no', 'Transaction no', 'trim|required');
        }*/
           
        $this->form_validation->set_rules('mobile', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
       
        if ($this->form_validation->run() == FALSE) 
        {  
            $invoiceid = generate_unique_id(12);
            $purchase_no = generate_unique_id(13); 
            $vendor_code = generate_unique_id(11); 

              /*
              if(isset($post['bank_name'])){

              $bank_name=$post['bank_name'];
              }else{
              $bank_name='';
              }
              if(isset($post['card_no'])){

              $card_no=$post['card_no'];
              }else{
              $card_no='';
              }
              if(isset($post['cheque_no'])){

              $cheque_no=$post['cheque_no'];
              }else{
              $cheque_no='';
              }
              if(isset($post['payment_date']) && !empty($post['payment_date'])){

              $payment_date=date('d-m-Y',strtotime($post['payment_date']));

              }else{
              $payment_date='';
              }
              if(isset($post['transaction_no'])){

              $transaction_no=$post['transaction_no'];
              }else{
              $transaction_no='';
              } */


            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'], 
                                    "vendor_id"=>$_POST['vendor_id'],
                                    'vendor_code'=>$vendor_code,
                                    "invoice_id"=>$_POST['invoice_id'],
                                    "purchase_no"=>$purchase_no,
                                    "name"=>$_POST['name'],
                                    "email"=>$_POST['email'],
                                    "address"=>$_POST['address'],
                                    "mobile"=>$_POST['mobile'],
                                    'total_amount'=>$post['total_amount'],
                                    "purchase_date"=>$post['purchase_date'],
                                    'discount_amount'=>$post['discount_amount'],
                                    'payment_mode'=>$post['payment_mode'],
                                    //'bank_name'=>$bank_name,
                                    //'card_no'=>$card_no,
                                    //'cheque_no'=>$cheque_no,
                                    //'payment_date'=>$payment_date,
                                      'net_amount'=>$post['net_amount'],
                                      "remarks"=>$post['remarks'],
                                      "field_name"=>$total_values,
                                   // 'vat_amount'=>$post['vat_amount'],
                                    'igst_amount'=>$post['igst_amount'],
                                    'sgst_amount'=>$post['sgst_amount'],
                                    'cgst_amount'=>$post['cgst_amount'],
                                    'pay_amount'=>$post['pay_amount'],
                                   // 'vat_percent'=>$post['vat_percent'],
                                    'discount_percent'=>$post['discount_percent'],
                                    //'transaction_no'=>$transaction_no,
                                    "country_code"=>"+91"
                                   );  
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
        unauthorise_permission(181,1077);
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccine_purchase->delete($id);
           $response = "Vaccine purchase successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(181,1077);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccine_purchase->deleteall($post['row_id']);
            $response = "Vaccine purchase successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(58,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vaccination_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['vaccination_name']." detail";
        $this->load->view('vaccine_purchase/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(181,1080);
        $data['page_title'] = 'Vaccine purchase archive list';
        $this->load->helper('url');
        $this->load->view('vaccine_purchase/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(181,1080);
        $this->load->model('vaccine_purchase/purchase_archive_model','purchase_archive'); 

        $list = $this->purchase_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccine_purchase) { 
            $no++;
            $row = array();
          
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                  

            $row[] = '<input type="checkbox" name="vaccine_purchase[]" class="checklist" value="'.$vaccine_purchase->id.'">';  
            $row[] = $vaccine_purchase->purchase_id;
            $row[] = $vaccine_purchase->invoice_id;
            $row[] = $vaccine_purchase->vendor_name;
            //$row[] = $vaccine_purchase->total_amount;
            $row[] = $vaccine_purchase->net_amount;
            $row[] = $vaccine_purchase->paid_amount;
            $row[] = $vaccine_purchase->balance;
            $row[] = date('d-M-Y',strtotime($vaccine_purchase->purchase_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('1079',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_purchase('.$vaccine_purchase->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('1078',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$vaccine_purchase->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->purchase_archive->count_all(),
                        "recordsFiltered" => $this->purchase_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(181,1079);
        $this->load->model('vaccine_purchase/purchase_archive_model','purchase_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->purchase_archive->restore($id);
           $response = "Vaccine purchase  successfully restore in  vaccine purchase list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(181,1079);
        $this->load->model('vaccine_purchase/purchase_archive_model','purchase_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->purchase_archive->restoreall($post['row_id']);
            $response = "Vaccine purchase successfully restore in vaccine purchase list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(181,1078);
        $this->load->model('vaccine_purchase/purchase_archive_model','purchase_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->purchase_archive->trash($id);
           $response = "Vaccine purchase  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(181,1078);
        $this->load->model('vaccine_purchase/purchase_archive_model','purchase_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccine_purchase->trashall($post['row_id']);
            $response = "Vaccine purchase successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function medicine_entry_dropdown()
  {
      $medicine_entry_list = $this->vaccination_entry->employee_type_list();
      $dropdown = '<option value="">Select Vaccination Entry</option>'; 
      if(!empty($medicine_entry_list))
      {
        foreach($medicine_entry_list as $vaccination_entry)
        {
           $dropdown .= '<option value="'.$vaccination_entry->id.'">'.$vaccination_entry->vaccination_name.'</option>';
        }
      } 
      echo $dropdown; 
  }*/

  public function print_purchase_recipt($ids=""){
    $user_detail= $this->session->userdata('auth_users');
    $this->load->model('general/general_model');
    if(!empty($ids)){
       $purchase_id= $ids;
     }else{
       $purchase_id= $this->session->userdata('purchase_id');
     }
      $data['page_title'] = "Add vaccine purchase medicine";
      $get_detail_by_id= $this->vaccine_purchase->get_by_id($purchase_id);

      $get_by_id_data=$this->vaccine_purchase->get_all_detail_print($purchase_id,$get_detail_by_id['branch_id']);
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['mode_payment']);
      $template_format= $this->vaccine_purchase->template_format(array('section_id'=>7,'types'=>1,'sub_section'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;
      $this->load->view('vaccine_purchase/print_template_vaccination',$data);

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
       $html.='<div class="purchase_medicine_mod_of_payment"><label>'.$payment_detail->field_name.'<span class="star">*</span></label><input type="text" name="field_name[]" value="" onkeypress="payment_calc_all();"><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div>';
    }
    echo $html;exit;
    
  }


}
