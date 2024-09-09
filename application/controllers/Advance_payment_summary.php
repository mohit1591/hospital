<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advance_payment_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('summary_reports/advance_payment_model','advancepayment');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('127','773');
        $data['page_title'] = 'IPD Payment Summary'; 
        $this->session->unset_userdata('advance_summary_serach');
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
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
        $this->load->view('advance_summary/list',$data);
    }

    public function ajax_list()
    { 
         unauthorise_permission('127','773');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->advancepayment->get_datatables();  
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        
        $total_num = count($list);
        foreach ($list as $ot) {
         // print_r($relation);die;
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
            
            $row[] = $i;
            
            $row[] = $ot->reciept_prefix.$ot->reciept_suffix;
            $row[] = $ot->ipd_no;
            $row[] = $ot->patient_name;
            $row[] = $ot->patient_code;
            
            
            $row[] = $ot->net_price;
            $row[] = $ot->payment_mode;
            $data[] = $row;
            $i++;
        }



        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->advancepayment->count_all(),
                        "recordsFiltered" => $this->advancepayment->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    
    
    public function advance_search()
    {

            $this->load->model('general/general_model'); 
            $data['page_title'] = "Advance Search";
            $post = $this->input->post();

            $data['form_data'] = array(
                                  "start_date"=>"",
                                  "end_date"=>"",
                                  "ipd_no"=>"",
                                  "patient_code"=>"",
                                  "patient_name"=>"",
                                  "mobile_no"=>"",
                                  "operation_date"=>"",
                                  'all'=>'',
                                  "operation_time"=>"",
                                  
                                  );
            if(isset($post) && !empty($post))
            {
                $marge_post = array_merge($data['form_data'],$post);
                $this->session->set_userdata('advance_summary_serach', $marge_post);
            }

            $advance_payment_serach = $this->session->userdata('advance_summary_serach');
            if(isset($advance_payment_serach) && !empty($advance_payment_serach))
            {
                $data['form_data'] = $advance_payment_serach;
            }

            $this->load->view('advance_payment/advance_search',$data);
    }
    public function reset_search()
    {
        $this->session->unset_userdata('advance_summary_serach');
    }
    
    public function search_data()
    {
       $post = $this->input->post();
       if(!empty($post))
       {
       $search_data =  array(
                                   'start_date'=>$post['start_date'],
                                   'branch_id'=>$post['branch_id'],
                                   
                                   'end_date'=>$post['end_date'],
                                   
                                 );
         $this->session->set_userdata('advance_summary_serach',$search_data);
       }
    }


    public function report_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('S. No.','Receipt No.','IPD No.','Patient Name','Reg. No.','Amount','Payment Mode');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          $ttl_amt=0;
           
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
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
              $data_list = $this->advancepayment->search_report_data();
              $list = $data_list['self_advance_payment'];
              
              

              
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $k=1;
               foreach($list as $reports)
               {
                  $ttl_amt=$ttl_amt+$reports->net_price;
                    
                    

                    array_push($rowData,$k,$reports->reciept_prefix.$reports->reciept_suffix,$reports->ipd_no,$reports->patient_name,$reports->patient_code,$reports->net_price,$reports->payment_mode);
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$ttl_amt);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$row.':F'.$row.'')->getFont()->setBold( true );  
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
        header("Content-Disposition: attachment; filename=advance_payment_report_".time().".xls"); 
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
                ob_end_clean();
               $objWriter->save('php://output');
         }
      
    }



    public function pdf_report()
    {   
        $data['print_status']="";
        //$data['data_list'] = $this->advancepayment->search_report_data();
        
        $data_list = $this->advancepayment->search_report_data();
       
         $data['data_list'] = $data_list['self_advance_payment'];
         $data['self_advance_payment_mode'] = $data_list['self_advance_payment_mode'];
              
              


        $this->load->view('advance_summary/report_html',$data);
        $html = $this->output->get_output();
        // Load library

        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("advance_summary_report_".time().".pdf");
    }

    public function print_report()
    {   
        $data['print_status']="1";
        //$data['data_list'] = $this->advancepayment->search_report_data();
        $data_list = $this->advancepayment->search_report_data();
       //echo "<pre>"; print_r($data_list); die;
         $data['data_list'] = $data_list['self_advance_payment'];
         $data['self_advance_payment_mode'] = $data_list['self_advance_payment_mode'];
        $this->load->view('advance_summary/report_html',$data); 
    }




   
    public function add($ipd_id="",$patient_id="")
    {
        unauthorise_permission('127','774');
        $this->load->model('general/general_model');
        $data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
        $data['page_title']="Advance Payment";
        $data['form_error'] = []; 
        $post= $this->input->post();
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'ipd_id'=>$ipd_id,
                                  "patient_id"=>$patient_id,
                                  "payment_mode"=>'',
                                  'particular'=>'Advance Payment',
                                  "amount"=>'',
                                  'bank_name'=>'',
                                  //'card_no'=>'',
                                  'cheque_date'=>date('d-m-Y'),
                                  'cheque_no'=>'',
                                  'field_name'=>'',
                                  'transaction_no'=>'',
                                  "payment_date"=>date('d-m-Y')
                                  );    

        if(isset($post) && !empty($post))
        { 

            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {

                $advance_payment_id= $this->advancepayment->save();

                 //send sms
              $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
              if(!empty($ipd_id))
              {
                  $get_by_id_data = $this->ipd_booking->get_by_id($ipd_id);
                  $patient_name = $get_by_id_data['patient_name'];
                  $booking_code = $get_by_id_data['ipd_no'];
                  $paid_amount = $get_by_id_data['advance_deposite'];
                  $mobile_no = $get_by_id_data['mobile_no'];
                  $room_no = $get_by_id_data['room_no'];
                  $patient_email = $get_by_id_data['patient_email'];
                  $amount = $post['amount'];
                //check permission
                if(in_array('640',$users_data['permission']['action']))
                {
                    if(!empty($mobile_no))
                    {
                      send_sms('advance_payment',15,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{IPDNo}'=>$booking_code,'{Amt}'=>$amount));  
                    }
                    
                }

                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($patient_email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($patient_email,'','','','','1','advance_payment','15',array('{Name}'=>$patient_name,'{IPDNo}'=>$booking_code,'{Amt}'=>$amount));
                     
                  }
                } 
              }

                $this->session->set_userdata('advance_payment_id',$advance_payment_id);
                $this->session->set_flashdata('success','Advance Payment has been successfully updated.');
                redirect(base_url('advance_payment/?status=print')); // /?status=print
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }  
         }
        //print_r( $data['form_error']);die;
       $this->load->view('advance_payment/add',$data); 
    }
    
    public function edit($id="")
    {
      

      unauthorise_permission('127','775');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model');
        $result = $this->advancepayment->get_by_id($id);  
        //echo "<pre>"; print_r($result); exit;
         $data['patient_details']= $this->general_model->get_patient_according_to_ipd($result['ipd_id'],$result['patient_id']);

        $data['page_title'] = "Update Advance Payment";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['payment_mode']=$this->general_model->payment_mode();
        $get_payment_detail= $this->advancepayment->payment_mode_detail_according_to_field($result['payment_mode'],$id);
        //print_r($get_payment_detail);die;
        $total_values='';
        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
        $data['form_data'] = array(
                                  'data_id'=>$result['id'], 
                                  'ipd_id'=>$result['ipd_id'],
                                  "patient_id"=>$result['patient_id'],
                                  "payment_mode"=>$result['payment_mode'],
                                  'particular'=>$result['particular'],
                                  "amount"=>$result['net_price'],
                                  //'bank_name'=>$result['bank_name'],
                                  'field_name'=>$total_values,
                                  //'card_no'=>$result['transaction_no'],
                                  //'cheque_date'=>date('d-m-Y',strtotime($result['cheque_date'])),
                                  //'cheque_no'=>$result['cheque_no'],
                                  //'transaction_no'=>$result['transaction_no'],
                                  "payment_date"=>date('d-m-Y',strtotime($result['payment_date']))
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
               
                $advance_payment_id= $this->advancepayment->save();
                $this->session->set_userdata('advance_payment_id',$advance_payment_id);
                $this->session->set_flashdata('success','Advance Payment has been successfully updated.');
                redirect(base_url('advance_payment/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }  
 //print '<pre>'; print_r($data);die;
        }
       
       $this->load->view('advance_payment/add',$data);       
      }
    }
     
    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
        $this->form_validation->set_rules('particular', 'particular', 'trim|required');
        $total_values=array(); 
        if(isset($post['field_name']))
        {
          $count_field_names= count($post['field_name']);  
          
          $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

          for($i=0;$i<$count_field_names;$i++) {
          $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

          }
        }
        if(isset($post['field_name']))
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required');  
        }
       
       if(isset($_POST['payment_date']) && !empty($post['payment_date'])){

          $payment_date=date('d-m-Y',strtotime($_POST['payment_date']));

          }else{
          $payment_date='';
          }
          /*
          if(isset($_POST['cheque_date']) && !empty($post['cheque_date'])){

          $cheque_date=date('d-m-Y',strtotime($_POST['cheque_date']));

          }else{
          $cheque_date='';
          }
          if(isset($_POST['transaction_no'])){

          $transaction_no=$_POST['transaction_no'];
          }else{
          $transaction_no='';
          }*/




        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                        'data_id'=>$post['data_id'], 
                                        'ipd_id'=>$post['ipd_id'],
                                        "patient_id"=>$post['patient_id'],
                                        "payment_mode"=>$post['payment_mode'],
                                        'particular'=>$post['particular'],
                                        "amount"=>$post['amount'],
                                        'field_name'=>$total_values,
                                        //'bank_name'=>$bank_name,
                                        //'card_no'=>$post['transaction_no'],
                                        //'cheque_date'=>$cheque_date,
                                        //'cheque_no'=>$cheque_no,
                                       // 'transaction_no'=>$transaction_no,
                                        "payment_date"=>$payment_date
                                       ); 
            return $data['form_data'];
        }   
    }

    
 
    public function delete($id="")
    {
       //unauthorise_permission('73','463');
       if(!empty($id) && $id>0)
       {
           $result = $this->advancepayment->delete($id);
           $response = "Advance payment successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('73','463');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advancepayment->deleteall($post['row_id']);
            $response = "Advance payment successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->advancepayment->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('advance_payment/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('73','464');
        $data['page_title'] = 'Advance payment Archive List';
        $this->load->helper('url');
        $this->load->view('advance_payment/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('73','464');
        $this->load->model('advance_payment/advance_payment_archive_model','advance_payment_archive');
        $list = $this->advance_payment_archive->get_datatables();
       // print_r();die;  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot) { 
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
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot->id.'">'.$check_script; 
            $row[] = $ot->patient_code;
            $row[] = $ot->ipd_no;  
            $row[] = $ot->patient_name;
            if($ot->admission_date =='1970-01-01'){
              $row[] = '';
            }else{
              $row[]=date('d-m-Y',strtotime($ot->admission_date));
            }
            
            $row[] = $ot->net_price;
             if($ot->payment_date =='1970-01-01'){
              $row[] = '';
            }else{
              $row[]=date('d-m-Y',strtotime($ot->payment_date));
            }


            $row[] = date('H:i:s',strtotime($ot->created_date));;
           
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('466',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_advance_payment('.$ot->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('465',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$ot->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->advance_payment_archive->count_all(),
                        "recordsFiltered" => $this->advance_payment_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('73','466');
       $this->load->model('advance_payment/advance_payment_archive_model','advance_payment_archive');
         if(!empty($id) && $id>0)
       {
           $result = $this->advance_payment_archive->restore($id);
           $response = "Advance Payment successfully restore in Advance Payment list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('73','466');
        $this->load->model('advance_payment/advance_payment_archive_model','advance_payment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advance_payment_archive->restoreall($post['row_id']);
            $response = "Advance Payment successfully restore in Advance payment list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('73','465');
        $this->load->model('advance_payment/advance_payment_archive_model','advance_payment_archive');
        if(!empty($id) && $id>0)
       {
           $result = $this->advance_payment_archive->trash($id);
           $response = "Advance payment successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('73','465');
        $this->load->model('advance_payment/advance_payment_archive_model','advance_payment_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advance_payment_archive->trashall($post['row_id']);
            $response = "Advance payment successfully deleted parmanently.";
            echo $response;
        }
    }
   

    public function print_advance_payment_report($ids="")
    {
        $user_detail= $this->session->userdata('auth_users');

        $data['page_title'] = "Add Advance Payment";
        if(!empty($ids)){
        $advance_payment_id= $ids;
        }else{
        $advance_payment_id= $this->session->userdata('advance_payment_id');
        }
        $get_detail_by_id= $this->advancepayment->get_by_id($advance_payment_id);

        $get_by_id_data=$this->advancepayment->get_all_detail_print($advance_payment_id,$get_detail_by_id['branch_id']);

        $template_format= $this->advancepayment->template_format(array('section_id'=>5,'types'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
        
        $re_number = $this->advancepayment->get_payment_reciept_no($ids);
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $data['re_number'] =$re_number;
        $this->load->view('advance_payment/print_template_advance_payment',$data);
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

        $html.='<div class="row m-b-5"><div class="col-md-5"><label>'.$payment_detail->field_name.'<label class="star">*</span></div> <div class="col-md-7"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>';
        }
        echo $html;exit;

    } 

}
?>