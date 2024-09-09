<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_booking extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_booking/ot_booking_model','otbooking');
        $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('134','807');
        $data['page_title'] = 'OT Booking List'; 
        $this->session->unset_userdata('ot_booking_serach');
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
        $this->load->view('ot_booking/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('134','807');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->otbooking->get_datatables();  
        $operation_time='';
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $btn_ot_details='';
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
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot->id.'">'.$check_script; 
            $row[] = $ot->ipd_no;
            $row[] = $ot->p_name;
            $row[] = $ot->mobile_no;
            $row[] = $ot->p_code;  
            $row[] = $ot->gender;
            $row[] = $ot->address;
            $row[] = $ot->booking_code;//$ot->father_husband_simulation." ".$ot->father_husband;
            $row[] = $ot->ot_room;
            $row[] = $ot->ot_pack_name;
            if($ot->operation_time=="00:00:00")
            {
                $operation_time='';
            }
            else
            {
                $operation_time=date('h:i A',strtotime($ot->operation_time));
            }
            $row[] = date('d-m-Y',strtotime($ot->operation_date)).' '.$operation_time;

            
            //$row[] = ;
            $row[] = $ot->doctor_hospital_name;
            $row[] = $ot->specialization;
            $doctor_list= $this->otbooking->doctor_list_by_otids($ot->id);
            $doctor_name=array();
            foreach($doctor_list as $doctor_list=>$value){
              $doctor_name[]="Dr. ".$value[0];
             }
           
            $name= implode(',',$doctor_name);
            $row[] = $name;
            $row[] = $ot->payment_mode;
            $row[] = $ot->room_type;
            $row[] = $ot->room_no;
            $row[] = $ot->bad_no;
            $row[] = $ot->total_amount;
            $row[] = $ot->discount_amount;
            $row[] = $ot->net_amount;
            $row[] = $ot->net_amount;
            $row[] = $ot->balance_amount;

          $btnedit='';
          $btndelete='';
          $btn_ot_summary="";
            $btn_ot_details='';
          //808;
          if(in_array('809',$users_data['permission']['action']))
          {
               $btnedit = ' <a onClick="return edit_ot_booking('.$ot->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('810',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_ot_booking('.$ot->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          if(in_array('808',$users_data['permission']['action']))
          {
            if($ot->op_type==2)
            {

              $btn_ot_details = '<li><a onClick="return ot_pack_detail('.$ot->id.','.$ot->package_id.','.$ot->pack_amount.')" href="javascript:void(0)" title="OT Detail" data-url="512"><i class="fa fa-info"></i> OT Detail</a></li> ';
            }
            if($ot->op_type==1)
            {
              $btn_ot_details = '<li><a onClick="return ot_detail('.$ot->id.','.$ot->operation_name.','.$ot->ot_amount.')" href="javascript:void(0)" title="OT Detail" data-url="512"><i class="fa fa-info"></i> OT Detail</a></li>';
            }
           
          }
           
          $btnprint='';
          //if(in_array('817',$users_data['permission']['action'])){
           $print_url = "'".base_url('ot_booking/print_ot_booking_report/'.$ot->id)."'";
            $btnprint = '<li><a  href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print OT</a></li>'; 
            
            $print_barcode_url = "'".base_url('ot_booking/print_barcode/'.$ot->id)."'";
            $btn_barcode = '<li><a   href="javascript:void(0)" onClick="return print_window_page('.$print_barcode_url.')"  title="Print Barcode" ><i class="fa fa-barcode"></i> Print Barcode </a></li>';

            $btnprint_ot_detail='';
            $btn_print_ot_summary='';

            $ot_print_url = "'".base_url('ot_booking/print_ot_detail_booking_report/'.$ot->id)."'";
            $btnprint_ot_detail = '<li><a  href="javascript:void(0)" onClick="return print_window_page('.$ot_print_url.')" title="Print" ><i class="fa fa-print"></i> Print OT Detail</a></li>'; 
            

             $btn_download_image = ' <li><a href="'.base_url('/ot_booking/download_image/'.$ot->id.'/'.$ot->branch_id).'" title="Download Image" data-url="512" target="_blank"><i class="fa fa-download"></i> Download Image</a></li>';

              
               
              if(in_array('801',$users_data['permission']['action']))
              {    
                $btn_ot_summary = '<li><a onClick="return operation_summary('.$ot->id.');" href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> OT Note Summary</a></li>';
                $btn_ot_summary .= '<li><a href="'.base_url('procedure_history/add/').$ot->id.'" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Procedure Note Summary</a></li>';
              }    

              if(in_array('800',$users_data['permission']['action']))
              { 
                $print_url = "'".base_url('operation_summary/print_summary/'.$ot->id)."'";  
                $print_procedure_url =  "'".base_url('operation_summary/print_procedure_note_summary/'.$ot->id)."'";  
                $btn_print_ot_summary='<li><a onClick="return print_window_page('.$print_url.')"  href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Print OT Note Summary</a></li>';
                // $btn_print_ot_summary .='<li><a onClick="return print_window_page('.$print_procedure_url.')"  href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Print Procedure Note Summary</a></li>';
              } 
                 
                  $btn_recipient_booking ='';
                     if(in_array('1507',$users_data['permission']['action'])) 
                   {
               $btn_recipient_booking = '<li><a  href="'.base_url('blood_bank/recipient/add/ot_'.$ot->id).'" style="'.$ot->id.'" title="Blood Recipient"><i class="fa fa-plus"></i> Blood Recipient</a></li>'; 
               }


              $btn_b = '<div class="slidedown">
                  <button disabled class="btn-custom">More <span class="caret"></span></button>
                  <ul class="slidedown-content">
                    '.$btn_barcode.$btn_ot_details.$btnprint_ot_detail.$btn_ot_summary.$btn_print_ot_summary.$btn_recipient_booking.$btnprint.$btn_download_image.'
                  </ul>
                </div> '; 

              $row[] = $btnedit.$btndelete.$btn_b;
              $data[] = $row;
              $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->otbooking->count_all(),
                        "recordsFiltered" => $this->otbooking->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function download_image($ids="",$branch_id='')
    {
          
        $data['type'] = 2;
        $data['download_type'] = '2'; //for image
        $user_detail= $this->session->userdata('auth_users');
        $data['page_title'] = "Add OT Booking";
        if(!empty($ids)){
        $ot_book_id= $ids;
        }else{
        $ot_book_id= $this->session->userdata('ot_book_id');
        }
        $get_detail_by_id= $this->otbooking->get_by_id($ot_book_id);
        
        $get_by_id_data=$this->otbooking->get_all_detail_print($ot_book_id,$get_detail_by_id['branch_id']);

        $template_format= $this->otbooking->template_format(array('section_id'=>6,'types'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $this->load->model('general/general_model');
        //$get_date_time_setting = $this->general_model->get_date_time_setting('vender_payment');
       // $data['date_time_status'] = $get_date_time_setting->date_time_status;

        $this->load->view('ot_booking/print_template_ot_booking',$data);
    }

    public function save_image()
    {
        $post = $this->input->post();
        $data = $post['data'];
        $patient_name = $post['patient_name'];
        $patient_code = $post['patient_code'];
        $data = substr($data,strpos($data,",")+1);
        $data = base64_decode($data);
        $file_name = 'OT'.strtolower($patient_name).'_'.strtolower($patient_code).'.png';
        $download  = REPORT_IMAGE_FS_OT_PATH.$file_name;
        $file = DIR_UPLOAD_REPORT_OT_IMAGE_PATH.$file_name;
        file_put_contents($file, $data);

        echo  $download;

    }

     public function ot_list_excel()
    {
      
          unauthorise_permission('134','814');
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
           $data_reg_name= get_setting_value('PATIENT_REG_NO');
          $fields = array('IPD No.',$data_reg_name,'Patient Name','Operation Date','Operation Time','Doctor Name','Room Type','Room No','Bed No.');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
               
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
          }
          $list = $this->otbooking->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                $doctor_name=array();
                $doctor_list= $this->otbooking->doctor_list_by_otids($reports->id);
                foreach($doctor_list as $doctor_list=>$value){
                    $doctor_name[]=$value[0];
                    }
                     $name= implode(',',$doctor_name);
                    array_push($rowData,$reports->ipd_no,$reports->p_code,$reports->p_name, date('d-m-Y',strtotime($reports->operation_date)),date('h:i A',strtotime($reports->operation_time)),$name,$reports->room_type,$reports->room_no,$reports->bad_no);
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
          header("Content-Disposition: attachment; filename=ot_booking_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }


    }

    public function ot_list_csv()
    {
           unauthorise_permission('134','815');
           // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $data_reg_name= get_setting_value('PATIENT_REG_NO');
           $fields = array('IPD No.',$data_reg_name,'Patient Name','Operation Date','Operation Time','Doctor Name','Room Type','Room No','Bed No.');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->otbooking->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                   $doctor_name=array(); 
                   $doctor_list= $this->otbooking->doctor_list_by_otids($reports->id);
                foreach($doctor_list as $doctor_list=>$value){
                    $doctor_name[]=$value[0];
                    }
                     $name= implode(',',$doctor_name);
                     array_push($rowData,$reports->ipd_no,$reports->p_code,$reports->p_name, date('d-m-Y',strtotime($reports->operation_date)),date('h:i A',strtotime($reports->operation_time)),$name,$reports->room_type,$reports->room_no,$reports->bad_no);
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
          header("Content-Disposition: attachment; filename=ot_booking_report_".time().".csv");    
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }

    }

    public function pdf_ot_list()
    {    
        unauthorise_permission('134','816');
        $data['print_status']="";
        $data['data_list'] = $this->otbooking->search_report_data();
        $this->load->view('ot_booking/ot_booking_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("ot_booking_report_".time().".pdf");
    }
    public function print_ot_list()
    { 
      unauthorise_permission('134','817');   
      $data['print_status']="1";
      $data['data_list'] = $this->otbooking->search_report_data();
      $this->load->view('ot_booking/ot_booking_report_html',$data); 
    }
    
    public function advance_search()
    {

            $this->load->model('general/general_model'); 
            
            $data['page_title'] = "Advance Search";
            $post = $this->input->post();
            $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                      "adhar_no"=>"",
                                      "ipd_no"=>"",
                                      "patient_code"=>"",
                                      "patient_name"=>"",
                                      "mobile_no"=>"",
                                      "operation_date"=>"",
                                      'all'=>'',
                                      'pacakage_name'=>'',
                                      'operation_name'=>'',
                                      "operation_time"=>"",
                                      "insurance_type"=>"",
                                      "insurance_type_id"=>"",
                                      "ins_company_id"=>"",
                                      "specialization_id"=>"",
                                      "doctor_name"=>"",
                                      "doctor_id"=>"",
                                      );
            if(isset($post) && !empty($post))
            {
              // echo "<pre/>"; print_r($post); exit;
            //print_r($post);die;
                $marge_post = array_merge($data['form_data'],$post);
                $this->session->set_userdata('ot_booking_serach', $marge_post);

            }
            $data['ot_pacakage_list']= $this->otbooking->pacakage_list();
            $data['operation_list']= $this->otbooking->operation_list();
            $data['specialization_list'] = $this->general_model->specialization_list();
            $data['doctor_list'] =$this->otbooking->doctor_list_by_otids($id);
            $purchase_search = $this->session->userdata('ot_booking_serach');
            if(isset($purchase_search) && !empty($purchase_search))
            {
              $data['form_data'] = $purchase_search;
            }
            $this->load->view('ot_booking/advance_search',$data);
    }

    public function reset_search()
    {
      $this->session->unset_userdata('ot_booking_serach');
    }
    
    public function add($patient_id='',$ipd_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        unauthorise_permission('134','808');
        $pid='';
        $reg_id='';
        $patient_reg_code="";
        $ipd_no="";
        $simulation_id="";
        $name="";
        $mobile_no="";
        $age_m="";
        $age_y="";
        $age_d="";
        $address="";
        $address_second="";
        $address_third="";
        $room_no="";
        $patient_type="";
        $gender="";
        $referral_doctor='';
        $referred_by='';
        $referral_hospital='';
        $adhar_no="";
        $relation_type="";
        $relation_name="";
        $relation_simulation_id="";
        $operation_name='';
        $ot_booking_no = generate_unique_id(23);
        $patient_reg_code=generate_unique_id(4);
        if(isset($_GET['reg']))
        {
          $pid= $_GET['reg'];
        }
        if(isset($patient_id) && !empty($patient_id))
        {
          $pid = $patient_id;
        }
        
        if(isset($ipd_id))
        {
           $ipd_id= $ipd_id;
        }
        else
        {
          $ipd_id='';
        }

        if($ipd_id>0)
        {
            $patient_ipd_details = $this->otbooking->get_patient_by_id_with_ipd_detail($pid);
             $this->load->model('general/general_model','general');
            $data['referal_hospital_list'] = $this->general->referal_hospital_list();
            if(!empty($patient_ipd_details))
            {
                
                //present age of patient
                if($patient_ipd_details['dob']=='1970-01-01' || $patient_ipd_details['dob']=="0000-00-00")
                {
                $present_age = get_patient_present_age('',$patient_ipd_details);
                }
                else
                {
                $dob=date('d-m-Y',strtotime($patient_ipd_details['dob']));
                $present_age = get_patient_present_age($dob,$patient_ipd_details);
                }

                 $age_y = $present_age['age_y'];
                $age_m = $present_age['age_m'];
                $age_d = $present_age['age_d'];
                //present age of patient
                
                $patient_id = $patient_ipd_details['id'];
                //$ipd_id=$patient_ipd_details['ipd_id'];
                $simulation_id = $patient_ipd_details['simulation_id'];
                $patient_reg_code = $patient_ipd_details['patient_code'];
                $ipd_no = $patient_ipd_details['ipd_no'];
                $name = $patient_ipd_details['patient_name'];
                $mobile_no = $patient_ipd_details['mobile_no'];
                /*$age_m = $patient_ipd_details['age_m'];
                $age_y = $patient_ipd_details['age_y'];
                $age_d = $patient_ipd_details['age_d'];*/
                $address = $patient_ipd_details['address'];
                $address_second = $patient_ipd_details['address2'];
                $address_third = $patient_ipd_details['address3'];
                $room_no = $patient_ipd_details['room_no'];
                $patient_type = $patient_ipd_details['patient_type'];
                $adhar_no=$patient_ipd_details['adhar_no'];
                $relation_type=$patient_ipd_details['relation_type'];
                $relation_name=$patient_ipd_details['relation_name'];
                $relation_simulation_id=$patient_ipd_details['relation_simulation_id'];
            }
            $reg_id='';
        }
        else if(isset($_GET['lid']) && !empty($_GET['lid']) && $_GET['lid']>0)
        {
          $this->load->model('opd/opd_model');
          $lead_data = $this->opd_model->crm_get_by_id($_GET['lid']);
          //echo '<pre>'; print_r($lead_data);die;
          $name = $lead_data['name'];
          $email = $lead_data['email'];
          $mobile_no = $lead_data['phone']; 
          $operation_name =  $lead_data['ot_id']; 
          $data['lead_ot_id'] = $lead_data['ot_id']; 
          $gender=$lead_data['gender'];
          $age_m = $lead_data['age_m'];
          $age_y = $lead_data['age_y'];
          $age_d = $lead_data['age_d'];
          $address = $lead_data['address'];
          $address_second = $lead_data['address2'];
          $address_third = $lead_data['address3'];
        }
        else
        {
           if(isset($pid) && !empty($pid))
            {
                $patient = $this->otbooking->get_patient_by_id($pid);
            }
            if(!empty($patient))
            {
                
                //present age of patient
              if($patient['dob']=='1970-01-01' || $patient['dob']=="0000-00-00")
              {
                $present_age = get_patient_present_age('',$patient);
               // echo "<pre>"; print_r($present_age);
              }
              else
              {
                $dob=date('d-m-Y',strtotime($patient['dob']));
                $present_age = get_patient_present_age($dob);
              }
              
              $age_y = $patient['age_y'];
              $age_m = $patient['age_m'];
              $age_d = $patient['age_d'];
              //present age of patient
              
              $patient_id = $patient['id'];
              $simulation_id = $patient['simulation_id'];
              $patient_reg_code = $patient['patient_code'];
              $name = $patient['patient_name'];
              $mobile_no = $patient['mobile_no'];
              $email = $patient['patient_email'];
              $gender=$patient['gender'];
            /*  $age_m = $patient['age_m'];
              $age_y = $patient['age_y'];
              $age_d = $patient['age_d'];*/
              $address = $patient['address'];
              $address_second = $patient['address2'];
              $address_third = $patient['address3'];
              $adhar_no = $patient['adhar_no'];
              $relation_type=$patient['relation_type'];
              $relation_name=$patient['relation_name'];
              $relation_simulation_id=$patient['relation_simulation_id'];
              $ipd_no = '';
              $room_no = '';
              $patient_type ='';

              // /echo "aaa";die;

            }

            $reg_id=$patient_id;
       
        }
        $this->load->model('general/general_model');
        $data['simulation_list']= $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['ot_pacakage_list']= $this->otbooking->pacakage_list();
        $data['operation_list']= $this->otbooking->operation_list();
        $data['ot_room_list']= $this->otbooking->ot_room_list();
        $data['remarks_list']= $this->otbooking->remarks_list();
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list(); 
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['page_title'] = "OT Booking";  
        $post = $this->input->post();
       // print '<pre>'; print_r($post);die;
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'ot_booking_code'=>$ot_booking_no,
                                  'patient_code'=>$patient_reg_code,
                                  'ipd_id'=>$ipd_id,
                                  "patient_id"=>$patient_id,
                                  'ipd_no'=>$ipd_no,
                                  'op_type'=>1,
                                  'simulation_id'=>$simulation_id,
                                  'name'=>$name,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  "age_m"=>$age_m,
                                  "age_y"=>$age_y,
                                  "operation_room"=>"",
                                  "age_d"=>$age_d,
                                  "address"=>$address,
                                  "address_second"=>$address_second,
                                  "address_third"=>$address_third,
                                  "adhar_no"=>$adhar_no,
                                  "room_no"=>$room_no,
                                  'reg_patient'=>$reg_id,
                                  "patient_type"=>$patient_type,
                                  "relation_type"=>$relation_type,
                                  "relation_name"=>$relation_name,
                                  "relation_simulation_id"=>$relation_simulation_id,
                                  "operation_name"=>$operation_name,
                                  "pacakage_name"=>"",
                                  "operation_time"=>date('H:i:s'),
                                  "operation_date"=>date('d-m-Y'),
                                  "operation_booking_date"=>date('d-m-Y'),
                                  "country_code"=>"+91",
                                  "remarks"=>"",
                                  'referred_by'=>'',
                                  'referral_doctor'=>'',
                                  'referral_hospital'=>'',
                                  'specialization_id'=>'',
                                  'operated_eye'=>'',
                                  'payment_mode'=>'',
                                  'balance'=>0,
                                  'net_amount'=>0,
                                  'total_amount'=>0,
                                  'paid_amount'=>0,
                                  'discount'=>0,
                                  'discount_percent'=>'',
                                  'field_name'=>'',
                                  'ref_by_other'=>'',
                                  ); 
        //print '<pre>'; print_r($data['form_data']);die;
        if(isset($post) && !empty($post))
        {   
          //print '<pre>'; print_r($post);die;
            $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                 if(isset($post['op_type']) && $post['op_type']==1)
                 {
                    $p_hours= $this->otbooking->get_operation_hours($post['operation_name']);  
                    $time_n=date('H:i:s',strtotime($post['operation_time']));
                    $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
                    $start_time=date('Y-m-d',strtotime($post['operation_date'])).' '.$time_n;
                    $end_date_time= date('Y-m-d',strtotime($post['operation_date'])).' '.$time;
                 }
                if(isset($post['pacakage_name'])  && $post['op_type']==2)
                {
                    $p_hours= $this->otbooking->get_package_hours($post['pacakage_name']);
                    $time_n=date('H:i:s',strtotime($post['operation_time']));
                    $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
                    $start_time=date('Y-m-d',strtotime($post['operation_date'])).' '.$time_n;
                    $end_date_time= date('Y-m-d',strtotime($post['operation_date'])).' '.$time;
                }

                   $check_ot_scheduled_time=  $this->otbooking->ot_scheduled_time(date('Y-m-d',strtotime($post['operation_date'])),$start_time,$end_date_time,$post['operation_room']);

                    if($check_ot_scheduled_time['error']==1)
                    {

                      $this->session->set_flashdata('error','OT Booking not possible at that time.');
                      redirect(base_url('ot_booking'));
                    }
                   

                   else if(isset($post['doctor_names']))
                   {
                     $check_ot_scheduled_doctor=  $this->otbooking->ot_scheduled_doctor(date('Y-m-d',strtotime($post['operation_date'])),$start_time,$end_date_time,$post['doctor_names']);
                      if($check_ot_scheduled_doctor['error']==3)
                      {
                       
                      $this->session->set_flashdata('error','OT Booking not possible at that time.');
                      redirect(base_url('ot_booking'));
                      }
                      else if($check_ot_scheduled_doctor['doctor_error']==4)
                      {
                        $this->session->set_flashdata('error','OT Booking not possible at that time because doctor not avilable.');
                        redirect(base_url('ot_booking'));
                      }
                      else
                      {
                          if(in_array('640',$users_data['permission']['action']))
                            {
                                if(!empty($mobile_no))
                                {    
                                  $patient_data = $this->otbooking->get_patient_by_id($pid);
                                  if(!empty($pid) && !empty($patient_data) && $patient_data['mobile_no']!=$post['mobile_no'])
                                      {
                                        $parameter = array('{patient_name}'=>$post['patient_name'], '{mobile_no}'=>$post['mobile_no']);
                                         
                                        send_sms('update_patient_mobile',28,'',$post['mobile_no'],$parameter);  
                                      }
                                }
                                
                            }
                          $book_id= $this->otbooking->save();
                          $this->session->set_userdata('ot_book_id',$book_id);
                          $this->session->set_flashdata('success','OT Booking has been successfully added.');
                          redirect(base_url('ot_booking/add/?status=print')); 
                      }
                    }
                    else
                    {
                      if(in_array('640',$users_data['permission']['action']))
                            {
                                if(!empty($mobile_no))
                                {    
                                  $patient_data = $this->otbooking->get_patient_by_id($pid);
                                  if(!empty($pid) && !empty($patient_data) && $patient_data['mobile_no']!=$post['mobile_no'])
                                      {
                                        $parameter = array('{patient_name}'=>$post['patient_name'], '{mobile_no}'=>$post['mobile_no']);
                                         
                                        send_sms('update_patient_mobile',28,'',$post['mobile_no'],$parameter);  
                                      }
                                }
                                
                            }
                            
                      $book_id= $this->otbooking->save();
                      $this->session->set_userdata('ot_book_id',$book_id);
                      $this->session->set_flashdata('success','OT Booking has been successfully added.');
                      redirect(base_url('ot_booking/add/?status=print')); 
                  
                    }

                    
                }
                else
                {
                    $data['form_error'] = validation_errors();  
                }  
          //print_r($data['form_error']) ;die; 
              if(!empty($post['doctor_names']))
              {
                $data['doctor_list']=$post['doctor_names'];   
              }
         }
     
       $this->load->view('ot_booking/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('134','809');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model');
        $result = $this->otbooking->get_by_id($id);  
        //print '<pre>';print_r($result);die;
        $operation_time='';
        $data['page_title'] = "Update OT Booking";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['ot_pacakage_list']= $this->otbooking->pacakage_list();
        $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['doctor_list'] =$this->otbooking->doctor_list_by_otids($id);
        $data['ot_room_list']= $this->otbooking->ot_room_list();
     //print '<pre>';print_r($result);die;
        $data['remarks_list']= $this->otbooking->remarks_list();
        $data['simulation_list']= $this->general_model->simulation_list();
        $this->load->model('general/general_model','general');
        $data['operation_list']= $this->otbooking->operation_list();
        $data['referal_hospital_list'] = $this->general->referal_hospital_list();
        $data['ot_room_list']= $this->otbooking->ot_room_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['payment_mode']=$this->general_model->payment_mode();

        $get_payment_detail= $this->otbooking->payment_mode_detail_according_to_field($result['mode_of_payment'],$id,'17', '17');


        $total_values='';

        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
        if($result['operation_time']=='00:00:00')
        {
          $operation_time='';
        }
        else
        {
          $operation_time=$result['operation_time'];
        }
        
         //present age of patient
          if($result['dob']=='1970-01-01' || $result['dob']=="0000-00-00")
          {
            $present_age = get_patient_present_age('',$result);
          }
          else
          {
            $dob=date('d-m-Y',strtotime($result['dob']));
            $present_age = get_patient_present_age($dob,$result);
          }
          $age_y = $present_age['age_y'];
          $age_m = $present_age['age_m'];
          $age_d = $present_age['age_d'];
          //present age of patient
       
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'ot_booking_code'=>$result['booking_code'],
                                  'patient_code'=>$result['patient_code'],
                                  'patient_id'=>$result['patient_id'],
                                  'adhar_no'=>$result['adhar_no'],
                                  'reg_patient'=>$result['patient_id'],
                                  'ipd_id'=>$result['ipd_id'],
                                  'ipd_no'=>$result['ipd_no'],
                                  'simulation_id'=>$result['simulation_id'],
                                  "relation_type"=>$result['relation_type'],
                                  "relation_name"=>$result['relation_name'],
                                  "relation_simulation_id"=>$result['relation_simulation_id'],
                                  'name'=>$result['patient_name'],
                                  'mobile_no'=>$result['mobile_no'],
                                  'op_type'=>$result['op_type'],
                                  'gender'=>$result['gender'],
                                  "age_m"=>$age_m,
                                  "age_y"=>$age_y,
                                  "operation_room"=>$result['ot_room_no'],
                                  "age_d"=>$age_d,
                                  "address"=>$result['address'],
                                  "address_second"=>$result['address2'],
                                  "address_third"=>$result['address3'],
                                  "room_no"=>$result['room_no'],
                                  "patient_type"=>$result['patient_type'],
                                  "operation_name"=>$result['operation_name'],
                                  "pacakage_name"=>$result['package_id'],
                                  "operation_time"=>$operation_time,
                                  "operation_date"=>date('d-m-Y',strtotime($result['operation_date'])),
                                   "operation_booking_date"=>date('d-m-Y',strtotime($result['operation_booking_date'])),
                                   "country_code"=>"+91",
                                  "remarks"=>$result['remarks'],
                                  'referred_by'=>$result['referred_by'],
                                  'referral_doctor'=>$result['referral_doctor'],
                                  'referral_hospital'=>$result['referral_hospital'],
                                  'specialization_id'=>$result['specialization_id'],
                                  'operated_eye'=>$result['operated_eye'],
                                  'payment_mode'=>$result['mode_of_payment'],
                                  'total_amount'=>$result['total_amount'],
                                  'net_amount'=>$result['net_amount'],
                                  'paid_amount'=>$result['paid_amount'],
                                  'balance'=>$result['balance_amount'],
                                  'discount_percent'=>$result['discount_percent'],
                                  'discount'=>$result['discount_amount'],
                                  'field_name'=>$total_values,
                                  'ref_by_other'=>$result['ref_by_other'],
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                if(in_array('640',$users_data['permission']['action']))
                {     
                if(!empty($post['mobile_no']) && $result['mobile_no']!=$post['mobile_no'])
                  {
                    $parameter = array('{patient_name}'=>$post['patient_name'], '{mobile_no}'=>$post['mobile_no']);
                     
                    send_sms('update_patient_mobile',28,'',$post['mobile_no'],$parameter);  
                  }
                }
                
                $book_id= $this->otbooking->save();
                $this->session->set_userdata('ot_book_id',$book_id);
                $this->session->set_flashdata('success','OT Booking has been successfully updated.');
                redirect(base_url('ot_booking/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }  

        }
        //print '<pre>'; print_r($data);die;
       $this->load->view('ot_booking/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post(); 
       //print '<pre>';print_r($post);die;   
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('age_y', 'age', 'trim|required'); 
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
          //$this->form_validation->set_rules('address', 'Address', 'trim|required'); 
          $this->form_validation->set_rules('adhar_no', 'aadhaar no', 'min_length[12]|max_length[16]');
         $this->form_validation->set_rules('gender', 'gender', 'trim|required');
         if(isset($post['ipd_id']) && !empty($post['ipd_id']))
         {
           $this->form_validation->set_rules('room_no', 'Room No', 'trim|required');
          $this->form_validation->set_rules('patient_type', 'patient type', 'trim|required');

         }
        if(isset($post['op_type']) && $post['op_type']==1)
        {
          $this->form_validation->set_rules('operation_name', 'operation Type', 'trim|required'); 
        }
        if(isset($post['op_type']) && $post['op_type']==2)
        {
          $this->form_validation->set_rules('pacakage_name', 'pacakage name', 'trim|required');

        }

        $this->form_validation->set_rules('operation_date', 'operation date', 'trim|required'); 
          $this->form_validation->set_rules('operation_booking_date', 'operation booking date', 'trim|required'); 
        $this->form_validation->set_rules('remarks', 'remarks', 'trim'); 

        
        $data['ot_pacakage_list']= $this->otbooking->pacakage_list();
        $data['operation_list']= $this->otbooking->operation_list();
        $package_name='';
        if(isset($post['pacakage_name']))
        {
          $package_name=$post['pacakage_name'];
        }
        else
        {
          $package_name='';
        }
        if(isset($post['operation_name']))
        {
          $operation_name = $post['operation_name'];
        }
        else
        {
          $operation_name =''; 
        }
        //$data['doctor_list']=$post['doctor_names']; 
        $data['remarks_list']= $this->otbooking->remarks_list();
         $data['ot_room_list']= $this->otbooking->ot_room_list();
         $total_values=array();
        if(isset($post['field_name'])) 
        {
        $count_field_names= count($post['field_name']);  
       
        $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
        $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        } 
        }
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                      'data_id'=>$post['data_id'], 
                                      'patient_code'=>$post['patient_reg_code'],
                                      "patient_id"=>$post['patient_id'],
                                      'reg_patient'=>$post['reg_patient'],
                                      'ot_booking_code'=>$post['ot_booking_code'],
                                      'ipd_no'=>$post['ipd_no'],
                                      'ipd_id'=>$post['ipd_id'],
                                      'simulation_id'=>$post['simulation_id'],
                                      'name'=>$post['name'],
                                      'op_type'=>$post['op_type'],
                                      'mobile_no'=>$post['mobile_no'],
                                      'gender'=>$post['gender'],
                                      "age_m"=>$post['age_m'],
                                      "operation_room"=>$post['operation_room'],
                                      "age_y"=>$post['age_y'],
                                      "age_d"=>$post['age_d'],
                                      "address"=>$post['address'],
                                      "address_second"=>$post['address_second'],
                                      "address_third"=>$post['address_third'],
                                      "adhar_no"=>$post['adhar_no'],
                                      "room_no"=>$post['room_no'],
                                      "patient_type"=>$post['patient_type'],
                                      "operation_name"=>$operation_name,
                                      "pacakage_name"=>$package_name,
                                      "operation_time"=>$post['operation_time'],
                                      "operation_date"=>$post['operation_date'],
                                      "operation_booking_date"=>$post['operation_booking_date'],
                                      "country_code"=>"+91",
                                      "remarks"=>$post['remarks'],
                                      "relation_type"=>$post['relation_type'],
                                      "relation_name"=>$post['relation_name'],
                                      "relation_simulation_id"=>$post['relation_simulation_id'],
                                      'referral_doctor'=>$post['referral_doctor'],
                                      'referred_by'=>$post['referred_by'],
                                      'referral_hospital'=>$post['referral_hospital'],
                                      'specialization_id'=>$post['specialization_id'],
                                      'operated_eye'=>$post['operated_eye'],
                                      'payment_mode'=>$post['payment_mode'],
                                      'total_amount'=>$post['total_amount'],
                                      'discount'=>$post['discount'],
                                      'net_amount'=>$post['net_amount'],
                                      'paid_amount'=>$post['paid_amount'],
                                      'balance'=>$post['balance'],
                                      'discount_percent'=>$post['discount_percent'],
                                      'field_name'=>$total_values,
                                      'ref_by_other'=>$post['ref_by_other'],
                                       ); 
            return $data['form_data'];
        }   
    }

    
 
    public function delete($id="")
    {
       unauthorise_permission('134','810');
       if(!empty($id) && $id>0)
       {
           $result = $this->otbooking->delete($id);
           $response = "Operation booking successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('134','810');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->otbooking->deleteall($post['row_id']);
            $response = "Operation booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->otbooking->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('ot_booking/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('134','811');
        $data['page_title'] = 'Operation Booking Archive List';
        $this->load->helper('url');
        $this->load->view('ot_booking/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('134','811');
        $this->load->model('ot_booking/ot_booking_archive_model','ot_booking_archive');
        $list = $this->ot_booking_archive->get_datatables();
        $operation_time='';
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
           $row[] = $ot->ipd_no;
            $row[] = $ot->p_code;  
            $row[] = $ot->p_name;
            $row[] = date('d-m-Y',strtotime($ot->operation_date));
           if($ot->operation_time=="00:00:00")
            {
                $operation_time='';
            }
            else
            {
                $operation_time=date('h:i A',strtotime($ot->operation_time));
            }
            $row[] = $operation_time;
          $doctor_list= $this->otbooking->doctor_list_by_otids($ot->id);
          $doctor_name=array();
          foreach($doctor_list as $doctor_list=>$value){
          $doctor_name[]=$value[0];
          }
           
            $name= implode(',',$doctor_name);
            $row[] = $name;
            $row[] = $ot->room_type;
            $row[] = $ot->room_no;
            $row[] = $ot->bad_no;
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('813',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ot_pacakge('.$ot->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('812',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$ot->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_booking_archive->count_all(),
                        "recordsFiltered" => $this->ot_booking_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission('134','813');
       $this->load->model('ot_booking/ot_booking_archive_model','ot_booking_archive');
         if(!empty($id) && $id>0)
       {
           $result = $this->ot_booking_archive->restore($id);
           $response = "Operation booking successfully restore in Operation booking list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('134','813');
        $this->load->model('ot_booking/ot_booking_archive_model','ot_booking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_booking_archive->restoreall($post['row_id']);
            $response = "Operation booking successfully restore in Operation booking list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('134','812');
       $this->load->model('ot_booking/ot_booking_archive_model','ot_booking_archive');
        if(!empty($id) && $id>0)
       {
           $result = $this->ot_booking_archive->trash($id);
           $response = "Operation booking successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('134','812');
        $this->load->model('ot_booking/ot_booking_archive_model','ot_booking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_booking_archive->trashall($post['row_id']);
            $response = "Operation booking successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ot_pacakage_dropdown()
  {
	  $ot_pacakge_list = $this->otbooking->pacakage_list();
      $dropdown = '<option value="">Select OT Package</option>'; 
      if(!empty($ot_pacakge_list))
      {
        foreach($ot_pacakge_list as $otpackage)
        {
           $dropdown .= '<option value="'.$otpackage->id.'">'.$otpackage->name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  public function ot_name_dropdown()
  {
    $ot_name_list = $this->otbooking->operation_list();
    $dropdown = '<option value="">Select Operation</option>'; 
    if(!empty($ot_name_list))
    {
    foreach($ot_name_list as $otname)
    {
    $dropdown .= '<option value="'.$otname->id.'">'.$otname->name.'</option>';
    }
    } 
    echo $dropdown; 
  }

  function get_vals($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->otpackage->get_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_doctor_name($vals="",$specialization_id="")
    {
        if(!empty($vals))
        {
            $result = $this->otbooking->get_doctor_name($vals,$specialization_id);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function append_doctor_list(){
     $name=  $this->input->post('name');
     $row_count=$this->input->post('rowCount');
     $doctor_id=$this->input->post('doctor_id');
     $i=1;
     if(!empty($name)){
     $table=' <tr> <td><input type="checkbox" value="'.$name.'" name="doctor_names['.$doctor_id.'][]" class="child_checkbox" onclick="add_check();" checked/></td>
                 <td>'.$row_count.'</td>
                <td>'.ucfirst($name).'</td>
                        </tr>';
                        $i++;
                        echo $table;exit;
                      }

    }

    public function print_ot_booking_report($ids="")
    {
        
        $user_detail= $this->session->userdata('auth_users');
        $data['page_title'] = "Add OT Booking";
        if(!empty($ids)){
        $ot_book_id= $ids;
        }else{
        $ot_book_id= $this->session->userdata('ot_book_id');
        }
        $get_detail_by_id= $this->otbooking->get_by_id($ot_book_id);
        //print '<pre>'; print_r($get_detail_by_id);die;
        $get_by_id_data=$this->otbooking->get_all_detail_print($ot_book_id,$get_detail_by_id['branch_id']);

        $template_format= $this->otbooking->template_format(array('section_id'=>6,'types'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
        //print_r($template_format);
        $data['template_data']=$template_format;
        //print '<pre>'; print_r($data['template_data']);die;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        //print '<pre>'; print_r($data['all_detail']);die;
        $this->load->model('general/general_model');
        $get_date_time_setting = $this->general_model->get_date_time_setting('vender_payment');
        if(isset($get_date_time_setting))
        {
          $data['date_time_status'] = $get_date_time_setting->date_time_status;
        }
        else
        {
          $data['date_time_status'] = '';
        }
        
        $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $data['address_setting_list'] = $this->address_setting->get_master_unique();
        //print '<pre>'; print_r($data['address_setting_list']);die;
        $this->load->view('ot_booking/print_template_ot_booking',$data);
 }

  public function print_ot_detail_booking_report($ot_booking_id="")
  {
      $user_detail= $this->session->userdata('auth_users');
      $data['page_title'] = "OT Detail";
      $get_detail_by_id= $this->otbooking->get_by_id($ot_booking_id);
      //print '<pre>'; print_r($get_detail_by_id);die;
      $get_by_id_data=$this->otbooking->get_ot_detail_print($ot_booking_id,$get_detail_by_id['branch_id']);

      //print '<pre>';print_r($get_by_id_data);die;
      $template_format= $this->otbooking->template_format_ot_detail(array('branch_id'=>$get_detail_by_id['branch_id']));
      
      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $data['patient_detail']= $get_detail_by_id;
      $this->load->model('general/general_model');
      $get_date_time_setting = $this->general_model->get_date_time_setting('vender_payment');
      if(isset($get_date_time_setting))
      {
        $data['date_time_status'] = $get_date_time_setting->date_time_status;
      }
      else
      {
        $data['date_time_status'] = '';
      }
      $this->load->model('address_print_setting/address_print_setting_model','address_setting');
      $data['address_setting_list'] = $this->address_setting->get_master_unique();
      $this->load->view('ot_booking/print_template_ot_detail_pack',$data);
  }

  public function get_amount_details_operation_management()
  {
    $this->load->model('ot_management/ot_management_model','mgmt_model');
    $data=$this->mgmt_model->get_by_id($this->input->post('op_mgmt_id'));
    echo json_encode($data);

  }

  // Function to get details from Operation packages
  public function get_amount_details_operation_package()
  {
    $this->load->model('ot_pacakge/ot_pacakge_model','pkg_model');
    $data=$this->pkg_model->get_by_id($this->input->post('pkg_id'));
    echo json_encode($data);
  }
  // Function to get details from Operation packages

  public function print_barcode($id)
    {
        $patient_data = $this->otbooking->get_by_id($id); 
        $data['barcode_id'] = $patient_data['booking_code'];
        if(!empty($data['barcode_id']))
        {
            $this->load->view('patient/barcode',$data);
        }
    }
// Please write code above this 
}
?>