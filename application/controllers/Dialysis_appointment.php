<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_appointment extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dialysis_appointment/dialysis_appointment_model','dialysisbooking');
        $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
        $this->load->library('form_validation');
    }

    public function index_old()
    { 
        unauthorise_permission('207','1193');
        $data['page_title'] = 'Dialysis Appointment List'; 
        $this->session->unset_userdata('dialysis_booking_serach');
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
        $this->load->view('dialysis_appointment/list',$data);
    }
    
    public function index()
    {
        
        $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        $opd_search = $this->session->userdata('appointment_search');
        //print_r($opd_search);
        if(!empty($opd_search['appointment_date']))
        {
           $start_date = $opd_search['appointment_date'];
       
        }
        else
        {
         $start_date = date('d-m-Y');
          
        }
          
        $data['form_data'] = array('patient_name'=>'','mobile_no'=>'','appointment_code'=>'','mobile_no'=>'','appointment_date'=>$start_date);
        $this->session->set_userdata('appointment_search', $data['form_data']);
        $data['page_title'] = 'Patient Appointment'; 
        $this->load->view('dialysis_appointment/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('207','1193');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->dialysisbooking->get_datatables();
        $recordsTotal =$this->dialysisbooking->count_all();
        $recordsFiltered = $recordsTotal;
        
        $dialysis_time='';
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $btn_list='';
        
        $total_num = count($list);
        foreach ($list as $dialysis) {
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysis->id.'">'.$check_script; 
            $row[] = $dialysis->booking_code;
            $row[] = $dialysis->p_code;  
            $row[] = $dialysis->p_name;
            $row[] = date('d-m-Y',strtotime($dialysis->dialysis_date));
            if($dialysis->dialysis_time=="00:00:00")
            {
                $dialysis_time='';
                if(!empty($dialysis->time_value))
                {
                  $dialysis_time=$dialysis->time_value;  
                }
                
            }
            else
            {
                $dialysis_time=date('h:i A',strtotime($dialysis->dialysis_time));
            }
            $row[] = $dialysis_time;
            
            
          $btnedit='';
          $btndelete='';
          if(in_array('1195',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_dialysis_appointment('.$dialysis->id.');" class="btn-custom" href="javascript:void(0)" style="'.$dialysis->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1196',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_dialysis_appointment('.$dialysis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $btnprint='';
          if(in_array('1195',$users_data['permission']['action'])){
          $print_url = "'".base_url('dialysis_appointment/print_dialysis_booking_report/'.$dialysis->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
          }

          

              $btn_list = ' 
                  <div class="dropdown">
                  <a class="btn-custom toggle" data-toggle="dropdown" onClick="" href="javascript:void(0)"><i class="fa fa-download"></i> Download</a>
                    <div class="dropdown-menu">
                      '.$btnprint.'
                    </div>
                  </div>

               ';

          $row[] = $btnedit.$btndelete.$btnprint;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $recordsTotal,
                        "recordsFiltered" => $recordsFiltered,
                        /*"recordsTotal" => $this->dialysisbooking->count_all(),
                        "recordsFiltered" => $this->dialysisbooking->count_filtered(),*/
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

        $data['page_title'] = "Add Dialysis Appointment";
        if(!empty($ids))
        {
          $ot_book_id= $ids;
        }
        else
        {
          $ot_book_id= $this->session->userdata('ot_book_id');
        }
        $get_detail_by_id= $this->dialysisbooking->get_by_id($ot_book_id);
        $get_by_id_data=$this->dialysisbooking->get_all_detail_print($ot_book_id,$get_detail_by_id['branch_id']);
        //print_r($get_by_id_data);die;
        $template_format= $this->dialysisbooking->template_format(array('section_id'=>8,'types'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $this->load->model('general/general_model');
        $get_date_time_setting = $this->general_model->get_date_time_setting('dialysis_booking');
        $data['date_time_status'] = $get_date_time_setting->date_time_status;
        $this->load->view('dialysis_appointment/print_template_dialysis_booking',$data);
    }

    public function save_image()
    {
        $post = $this->input->post();
        $data = $post['data'];
        $patient_name = $post['patient_name'];
        $patient_code = $post['patient_code'];
        $data = substr($data,strpos($data,",")+1);
        $data = base64_decode($data);
        $file_name = 'Dialysis'.strtolower($patient_name).'_'.strtolower($patient_code).'.png';
        $download  = REPORT_IMAGE_FS_DIALYSIS_PATH.$file_name;
        $file = DIR_UPLOAD_REPORT_DIALYSIS_IMAGE_PATH.$file_name;
        file_put_contents($file, $data);

        echo  $download;

    }

     public function dialysis_list_excel()
    {
      
          unauthorise_permission('207','1200');
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $data_reg_name= get_setting_value('PATIENT_REG_NO');
          $fields = array('Appointment No.',$data_reg_name,'Patient Name','Appointment Date','Appointment Time','Doctor Name','Dialysis Name');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           /*$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
           //$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          foreach ($fields as $field)
          {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                /*$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);*/
               
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
          }
          $list = $this->dialysisbooking->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                $doctor_name=array();
                $doctor_list= $this->dialysisbooking->doctor_list_by_otids($reports->id);
                foreach($doctor_list as $doctor_list=>$value){
                    $doctor_name[]=$value[0];
                    }
                    
                    if($reports->dialysis_time=="00:00:00")
                    {
                        $dialysis_time='';
                        if(!empty($reports->time_value))
                        {
                          $dialysis_time=$reports->time_value;  
                        }
                        
                    }
                    else
                    {
                        $dialysis_time=date('h:i A',strtotime($reports->dialysis_time));
                    }
                   
                    
                     $name= implode(',',$doctor_name);
                    array_push($rowData,$reports->booking_code,$reports->p_code,$reports->p_name, date('d-m-Y',strtotime($reports->dialysis_date)),$dialysis_time,$name,$reports->ot_pack_name);
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
          header("Content-Disposition: attachment; filename=dialysis_booking_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }


    }

    public function dialysis_list_csv()
    {
           unauthorise_permission('207','1201');
           // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $data_reg_name= get_setting_value('PATIENT_REG_NO');
           $fields = array('Appointment No.',$data_reg_name,'Patient Name','Appointment Date','Appointment Time','Doctor Name','Dialysis Name');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->dialysisbooking->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                   $doctor_name=array(); 
                   $doctor_list= $this->dialysisbooking->doctor_list_by_otids($reports->id);
                foreach($doctor_list as $doctor_list=>$value){
                    $doctor_name[]=$value[0];
                    }
                    
                    
                     $name= implode(',',$doctor_name);
                     
                      if($reports->dialysis_time=="00:00:00")
                    {
                        $dialysis_time='';
                        if(!empty($reports->time_value))
                        {
                          $dialysis_time=$reports->time_value;  
                        }
                        
                    }
                    else
                    {
                        $dialysis_time=date('h:i A',strtotime($reports->dialysis_time));
                    }
                     array_push($rowData,$reports->booking_code,$reports->p_code,$reports->p_name, date('d-m-Y',strtotime($reports->dialysis_date)),$dialysis_time,$name,$reports->ot_pack_name);
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
          header("Content-Disposition: attachment; filename=dialysis_booking_report_".time().".csv");    
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }

    }

    public function pdf_dialysis_list()
    {    
        unauthorise_permission('207','1202');
        $data['print_status']="";
        $data['data_list'] = $this->dialysisbooking->search_report_data();
        $this->load->view('dialysis_appointment/dialysis_booking_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("dialysis_booking_report_".time().".pdf");
    }
    public function print_dialysis_list()
    { 
      unauthorise_permission('207','1202');   
      $data['print_status']="1";
      $data['data_list'] = $this->dialysisbooking->search_report_data();
      $this->load->view('dialysis_appointment/dialysis_booking_report_html',$data); 
    }
    
    public function advance_search()
    {

            $this->load->model('general/general_model'); 
            $data['page_title'] = "Advance Search";
            $data['insurance_type_list'] = $this->general_model->insurance_type_list();
            $data['insurance_company_list'] = $this->general_model->insurance_company_list(); 
            $post = $this->input->post();
           //echo "<pre>"; print_r($post); exit;
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
        
            $data['form_data'] = array(
                                      "start_date"=>$start_date,
                                      "end_date"=>$end_date,
                                      "appointment_no"=>"",
                                      "patient_code"=>"",
                                      "patient_name"=>"",
                                      "mobile_no"=>"",
                                      "dialysis_date"=>"",
                                      "adhar_no"=>"",
                                      'all'=>'',
                                      "dialysis_time"=>"",
                                      "insurance_type"=>"",
                                      "insurance_type_id"=>"",
                                      "ins_company_id"=>"",
                                      );
            if(isset($post) && !empty($post))
            {
                //print_r($post);die;
                $marge_post = array_merge($data['form_data'],$post);
                $this->session->set_userdata('dialysis_booking_serach', $marge_post);

            }
        $purchase_search = $this->session->userdata('dialysis_booking_serach');
        if(isset($purchase_search) && !empty($purchase_search))
        {
            $data['form_data'] = $purchase_search;
        }
        
        $this->load->view('dialysis_appointment/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('dialysis_booking_serach');
    }
    
    public function add($patient_id='',$ipd_id='')
    {
        //schedule_available_time
        unauthorise_permission('207','1194');
        $pid='';
        $reg_id='';
        $patient_reg_code="";
        $ipd_no="";
        $simulation_id="";
        $name="";
        $mobile_no="";
        $adhar_no="";
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
        $dialysis_appointment_code = generate_unique_id(74);
        $patient_reg_code=generate_unique_id(4);
        $relation_name='';
        $relation_type='';
        $relation_simulation_id='';
        $city_id = "";
        $state_id = "";
        $country_id = "99"; 
        $patient_email='';
        $insurance_type='';
        $insurance_type_id='';
        $ins_company_id='';
        $polocy_no='';
        $tpa_id='';
        $ins_amount='';
        $ins_authorization_no='';
        $pannel_type="0";
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
            
            $patient_ipd_details = $this->dialysisbooking->get_patient_by_id_with_ipd_detail($pid);
             $this->load->model('general/general_model','general');
            $data['referal_hospital_list'] = $this->general->referal_hospital_list();
            if(!empty($patient_ipd_details))
            {
                $patient_id = $patient_ipd_details['id'];
                $ipd_id=$patient_ipd_details['ipd_id'];
                $simulation_id = $patient_ipd_details['simulation_id'];
                $patient_reg_code = $patient_ipd_details['patient_code'];
                $ipd_no = $patient_ipd_details['ipd_no'];
                $name = $patient_ipd_details['patient_name'];
                $mobile_no = $patient_ipd_details['mobile_no'];
                $age_m = $patient_ipd_details['age_m'];
                $age_y = $patient_ipd_details['age_y'];
                $age_d = $patient_ipd_details['age_d'];
                $address = $patient_ipd_details['address'];
                $address_second = $patient_ipd_details['address2'];
                $address_third = $patient_ipd_details['address3'];
                $room_no = $patient_ipd_details['room_no'];
                $patient_type = $patient_ipd_details['patient_type'];
                $gender=$patient_ipd_details['gender'];
                $adhar_no=$patient_ipd_details['adhar_no'];
                $relation_name=$patient_ipd_details['relation_name'];
                $relation_type=$patient_ipd_details['relation_type'];
                $relation_simulation_id=$patient_ipd_details['relation_simulation_id'];
            }
            $reg_id='';
        }
        else
        {
           if(isset($pid) && !empty($pid))
            {
                $patient = $this->dialysisbooking->get_patient_by_id($pid);
            }

            if(!empty($patient))
            {
              $patient_id = $patient['id'];
              $simulation_id = $patient['simulation_id'];
              $patient_reg_code = $patient['patient_code'];
              $name = $patient['patient_name'];
              $mobile_no = $patient['mobile_no'];
              $email = $patient['patient_email'];
              $gender=$patient['gender'];
              $age_m = $patient['age_m'];
              $age_y = $patient['age_y'];
              $age_d = $patient['age_d'];
              $adhar_no=$patient['adhar_no'];
              $address = $patient['address'];
              $address_second = $patient['address_second'];
              $address_third = $patient['address_third'];
              $relation_name=$patient['relation_name'];
              $relation_type=$patient['relation_type'];
              $relation_simulation_id=$patient['relation_simulation_id'];
              $ipd_no = '';
              $room_no = '';
             
            }

            $reg_id=$patient_id;
       
        }

       
        $this->load->model('general/general_model');
        $data['simulation_list']= $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['dialysis_pacakage_list']= $this->dialysisbooking->pacakage_list();
        $data['dialysisn_list']= $this->dialysisbooking->dialysis_list();
        $data['dialysis_room_list']= $this->dialysisbooking->dialysis_room_list();
        $data['remarks_list']= $this->dialysisbooking->remarks_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['country_list'] = $this->general_model->country_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $this->load->model('appointment/appointment_model','appointment');
        $data['attended_doctor_list'] = $this->appointment->attended_doctor_list();
        $data['page_title'] = "Dialysis Appointment";  
        $post = $this->input->post();
        //echo $post['available_time']; die;
       
       //print '<pre>'; print_r($post);die;
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'dialysis_appointment_code'=>$dialysis_appointment_code,
                                  'patient_code'=>$patient_reg_code,
                                  'ipd_id'=>$ipd_id,
                                  "patient_id"=>$patient_id,
                                  'ipd_no'=>$ipd_no,
                                  'adhar_no'=>$adhar_no,
                                  'dialysis_type'=>1,
                                  'simulation_id'=>$simulation_id,
                                  'relation_simulation_id'=>$relation_simulation_id,
                                  'relation_name'=>$relation_name,
                                  'relation_type'=>$relation_type,
                                  'name'=>$name,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  "age_m"=>$age_m,
                                  "age_y"=>$age_y,
                                  "dialysis_room"=>"",
                                  "age_d"=>$age_d,
                                  'patient_email'=>$patient_email,
                                  'address'=>$address,
                                  'address_second'=>$address_second,
                                  'address_third'=>$address_third,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  "room_no"=>$room_no,
                                  'reg_patient'=>$reg_id,
                                  "dialysis_name"=>'',
                                  "pacakage_name"=>"",
                                  "dialysis_time"=>date('H:i:s'),
                                  "dialysis_date"=>date('d-m-Y'),
                                  "dialysis_booking_date"=>date('d-m-Y'),
                                  "country_code"=>"+91",
                                  "remarks"=>"",
                                  'referred_by'=>'',
                                  'referral_doctor'=>'',
                                  'referral_hospital'=>'',
                                  'ref_by_other'=>'',
                                  'time_value'=>'',
                                  "insurance_type"=>$insurance_type,
                                  "insurance_type_id"=>$insurance_type_id,
                                  "ins_company_id"=>$ins_company_id,
                                  "polocy_no"=>$polocy_no,
                                  "tpa_id"=>$tpa_id,
                                  "ins_amount"=>$ins_amount,
                                  "ins_authorization_no"=>$ins_authorization_no,
                                  'pannel_type'=>$pannel_type,
                                  
                                 
                                  ); 
        //print '<pre>'; print_r($data['form_data']);die;
        if(isset($post) && !empty($post))
        {   
              $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                
                    $book_id= $this->dialysisbooking->save();
                      $this->session->set_userdata('dialysis_book_id',$book_id);
                      $this->session->set_flashdata('success','Dialysis Appointment has been successfully added.');
                      //redirect(base_url('dialysis_booking/?status=print')); 
                        redirect(base_url('dialysis_appointment/add/'));
                    
                  
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
         
         $schedule_id ='';
        if(!empty($post['schedule_id']))
        {
           $schedule_id =$post['schedule_id'];
        }
        $available_time ='';
        if(!empty($post['available_time']))
        {
          $available_time =$post['available_time'];
        }
        $doctor_slot ='';
        if(!empty($post['doctor_slot']))
        {
          $doctor_slot =$post['doctor_slot'];
        }
        $dialysis_date='';
        if(!empty($post['dialysis_date']))
        {
          $dialysis_date =$post['dialysis_date'];
        }
        
         
         
        $data['schedule_available_time'] = $this->general_model->schedule_time($schedule_id,$dialysis_date);
        
        //$data['schedule_available_time'] = $this->dialysisbooking->get_dialysis_schedule_available_time($schedule_id,$dialysis_date);
        //echo "<pre>"; print_r($data['schedule_available_time']); exit;
        
        $data['schedule_available_slot'] = $this->get_schedule_slot($schedule_id,$available_time,$doctor_slot,$dialysis_date);
        
     
       $this->load->view('dialysis_appointment/add',$data);       
    }
    
     public function get_schedule_available_days()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
           $days_data = $this->dialysisbooking->get_schedule_available_days($post['schedule_id'],$post['booking_date']);
           $json = json_encode($days_data,true);
           echo $json;
        }
    }
    
    public function edit($id="")
    {
      unauthorise_permission('207','1195');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model');
        $result = $this->dialysisbooking->get_by_id($id); 
        //echo "<pre>"; print_r($result); exit;
        $dialysis_time='';
        $data['page_title'] = "Update Dialysis Appointment";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['simulation_list']= $this->general_model->simulation_list();
        $this->load->model('general/general_model','general');
        $data['dialysisn_list']= $this->dialysisbooking->dialysis_list();
        $data['referal_hospital_list'] = $this->general->referal_hospital_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['country_list'] = $this->general_model->country_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $this->load->model('appointment/appointment_model','appointment');
        $data['attended_doctor_list'] = $this->appointment->attended_doctor_list();
        if($result['dialysis_time']=='00:00:00')
        {
        $dialysis_time='';
        }
        else
        {
        $dialysis_time=$result['dialysis_time'];
        }
        $adhar_no ='';
        if(!empty($result['adhar_no']))
        {
          $adhar_no = $result['adhar_no'];
        }
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'dialysis_appointment_code'=>$result['booking_code'],
                                  'patient_code'=>$result['patient_code'],
                                  'patient_id'=>$result['patient_id'],
                                  'schedule_id'=>$result['schedule_id'],
                                  'reg_patient'=>$result['patient_id'],
                                  'relation_simulation_id'=>$result['relation_simulation_id'],
                                  'relation_name'=>$result['relation_name'],
                                  'relation_type'=>$result['relation_type'],
                                  'simulation_id'=>$result['simulation_id'],
                                  'doctor_slot'=>$result['doctor_slot'],
                                  'appointment_date'=>date('d-m-Y',strtotime($result['dialysis_booking_date'])),
                                  'name'=>$result['patient_name'],
                                  'mobile_no'=>$result['mobile_no'],
                                  'adhar_no'=>$adhar_no,
                                  'dialysis_type'=>$result['dialysis_type'],
                                  'gender'=>$result['gender'],
                                  "age_m"=>$result['age_m'],
                                  "age_y"=>$result['age_y'],
                                  "dialysis_room"=>$result['dialysis_room_no'],
                                  "age_d"=>$result['age_d'],
                                  "address"=>$result['address'],
                                  "address_second"=>$result['address2'],
                                  "address_third"=>$result['address3'],
                                  'address'=>$result['address'],
                                    'city_id'=>$result['city_id'],
                                    'state_id'=>$result['state_id'],
                                    'country_id'=>$result['country_id'],
                                    
                                  "dialysis_name"=>$result['dialysis_name'],
                                  "pacakage_name"=>$result['package_id'],
                                  "dialysis_time"=>$dialysis_time,
                                  "dialysis_date"=>date('d-m-Y',strtotime($result['dialysis_date'])),
                                  "dialysis_booking_date"=>date('d-m-Y',strtotime($result['dialysis_booking_date'])),
                                  "country_code"=>"+91",
                                  "remarks"=>$result['remarks'],
                                  'referred_by'=>$result['referred_by'],
                                  'referral_doctor'=>$result['referral_doctor'],
                                  'referral_hospital'=>$result['referral_hospital'],
                                  'time_value'=>$result['time_value'],
                                  'ref_by_other'=>$result['ref_by_other'],
                                  'available_time'=>$result['available_time'],
                                  "insurance_type"=>$result['insurance_type'],
                                    "insurance_type_id"=>$result['insurance_type_id'],
                                    "ins_company_id"=>$result['ins_company_id'],
                                    "polocy_no"=>$result['polocy_no'],
                                    "tpa_id"=>$result['tpa_id'],
                                    "ins_amount"=>$result['ins_amount'],
                                     "ins_authorization_no"=>$result['ins_authorization_no'],
                                    'pannel_type'=>$result['pannel_type'],
                                    'patient_email'=>$result['patient_email']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {

                $book_id= $this->dialysisbooking->save();
                $this->session->set_userdata('ot_book_id',$book_id);
                $this->session->set_flashdata('success','Dialysis Appointment has been successfully updated.');
                redirect(base_url('dialysis_appointment/'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }  

        }
        //print '<pre>'; print_r($data);die;
        
        $data['schedule_available_time'] = $this->general_model->schedule_time($result['schedule_id']);
        $data['schedule_available_slot'] = $this->get_schedule_slot($result['schedule_id'],$result['available_time'],$result['doctor_slot'],$result['dialysis_booking_date'],$result['id']);
        
        
       $this->load->view('dialysis_appointment/add',$data);       
      }
    }
    
    function get_schedule_slot($schedule_id='',$time_id='',$selected='',$appointment_date='',$appointment_id='')
    {
        $post = $this->input->post(); 
        $this->load->model('general/general_model');
        $option =''; 
        if(!empty($schedule_id) && !empty($time_id))
        {
            $booked_slot_list = $this->general_model->get_dialysis_booked_slot($schedule_id,$time_id,$appointment_date,$appointment_id);
            $booked_slot = array();
            foreach ($booked_slot_list as $booked_list) 
            { 
              $booked_slot[] = $booked_list->doctor_slot;
            }   

           // print_r($booked_slot); exit();
            $time_list = $this->general_model->schedule_slot($schedule_id,$time_id);
            $per_patient_time = $this->general_model->dialysis_per_patient_time($schedule_id);
            $time1='';
            if(!empty($time_list[0]->time1))
            {
              $time1 = strtotime($time_list[0]->time1);  
            }
            $time2='';
            if(!empty($time_list[0]->time2))
            {
              $time2 = strtotime($time_list[0]->time2);
            }
            
            
            $total_time = $time2-$time1;
            $time_in_minute = $total_time/60;  
            $total_slot ='';
            if(!empty($per_patient_time[0]->per_patient_timing))
            {
              $total_slot = $time_in_minute/$per_patient_time[0]->per_patient_timing;  
            }
            
            $slot_data = '';  
            $option .= "<option value=''>Select Time</option>";
            $start_slot = $time1;
            $end_slot = $start_slot+($per_patient_time[0]->per_patient_timing*60);
            for($i=0;$i<$total_slot;$i++)
            { 
                $slot_data = date('H:i A', $start_slot).' To '.date('H:i A', $end_slot);
                $time_values = date('h:i:s A', $start_slot-60);
                $start_slot = $end_slot+60;
                $end_slot = ($end_slot+($per_patient_time[0]->per_patient_timing*60));
                $chek='';
                
        if($selected==$time_values)
        {
          $chek = 'selected="selected"';
        }
        if(!in_array($slot_data, $booked_slot))
        {
          $option .= "<option ".$chek." value='".$time_values."'>".$slot_data."</option>";
        }
            } 
        }
        return $option;
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
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        $this->form_validation->set_rules('adhar_no', 'aadhaar no', 'min_length[12]|max_length[16]'); 
         
        if(isset($post['dialysis_type']) && $post['dialysis_type']==1)
        {
          $this->form_validation->set_rules('dialysis_name', 'dialysis name', 'trim|required'); 
          
        }
       
        $this->form_validation->set_rules('dialysis_date', 'Appointment date', 'trim|required'); 
         
        $this->form_validation->set_rules('remarks', 'remarks', 'trim'); 
        
        $this->form_validation->set_rules('schedule_id', 'Schedule', 'trim|required'); 
        $this->form_validation->set_rules('available_time', 'Available Time', 'trim|required'); 
        $this->form_validation->set_rules('doctor_slot', 'Schedule Slot', 'trim|required'); 

        

        
        $data['dialysis_pacakage_list']= $this->dialysisbooking->pacakage_list();
        $data['dialysis_list']= $this->dialysisbooking->dialysis_list();
        $package_name='';
        if(isset($post['pacakage_name']))
        {
          $package_name=$post['pacakage_name'];
        }
        else
        {
          $package_name='';
        }
        if(isset($post['dialysis_name']))
        {
          $dialysis_name = $post['dialysis_name'];
        }
        else
        {
          $dialysis_name =''; 
        }
        //$data['doctor_list']=$post['doctor_names']; 
        $data['remarks_list']= $this->dialysisbooking->remarks_list();
         $data['dialysis_room_list']= $this->dialysisbooking->dialysis_room_list();
         
         $data['schedule_available_time'] = $this->general_model->schedule_time($schedule_id);
        $data['schedule_available_slot'] = $this->get_schedule_slot($schedule_id,$available_time,$doctor_slot,$appointment_date);
         
         $pannel_type = '0';
            if(!empty($post['pannel_type']))
            {
              $pannel_type = $post['pannel_type'];
            }
         
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                      'data_id'=>$post['data_id'], 
                                      'patient_code'=>$post['patient_reg_code'],
                                      "patient_id"=>$post['patient_id'],
                                      'reg_patient'=>$post['reg_patient'],
                                      'dialysis_appointment_code'=>$post['dialysis_appointment_code'],
                                      'ipd_no'=>$post['ipd_no'],
                                      'ipd_id'=>$post['ipd_id'],
                                      'adhar_no'=>$post['adhar_no'],
                                      'simulation_id'=>$post['simulation_id'],
                                      'name'=>$post['name'],
                                      'dialysis_type'=>$post['dialysis_type'],
                                      'mobile_no'=>$post['mobile_no'],
                                      'gender'=>$post['gender'],
                                      "age_m"=>$post['age_m'],
                                      "dialysis_room"=>$post['dialysis_room'],
                                      "age_y"=>$post['age_y'],
                                      "age_d"=>$post['age_d'],
                                      "address"=>$post['address'],
                                      "address_second"=>$post['address_second'],
                                      "address_third"=>$post['address_third'],
                                      "room_no"=>$post['room_no'],
                                      "dialysis_name"=>$dialysis_name,
                                      "pacakage_name"=>$package_name,
                                      "dialysis_time"=>$post['dialysis_time'],
                                      "dialysis_date"=>$post['dialysis_date'],
                                      "dialysis_booking_date"=>$post['dialysis_booking_date'],
                                      "country_code"=>"+91",
                                      "remarks"=>$post['remarks'],
                                      'referral_doctor'=>$post['referral_doctor'],
                                      'referred_by'=>$post['referred_by'],
                                      'relation_simulation_id'=>$post['relation_simulation_id'],
                                      'relation_name'=>$post['relation_name'],
                                      'relation_type'=>$post['relation_type'],
                                      
                                      'referral_hospital'=>$post['referral_hospital'],
                                      'patient_email'=>$post['patient_email'],
                                      'pannel_type'=>$pannel_type,
                                        "insurance_type"=>$insurance_type,
                                        "insurance_type_id"=>$insurance_type_id,
                                        "ins_company_id"=>$ins_company_id,
                                        "polocy_no"=>$post['polocy_no'],
                                        "tpa_id"=>$post['tpa_id'],
                                        "ins_amount"=>$post['ins_amount'],
                                        "ins_authorization_no"=>$post['ins_authorization_no'],
                                        'adhar_no'=>$post['adhar_no'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'schedule_id'=>$post['schedule_id'],
                                        'available_time'=>$post['available_time'],
                                         'doctor_slot'=>$post['doctor_slot'],
                                       ); 
                                       
                                       

            return $data['form_data'];
        }   
    }

    
 
    public function delete_appointment($id="")
    {
       unauthorise_permission('207','1196');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysisbooking->delete($id);
           $response = "Dialysis Appointment successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('207','1196');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysisbooking->deleteall($post['row_id']);
            $response = "Dialysis Appointment successfully deleted.";
            echo $response;
        }
    }
    
    public function cancel_appointment($id="")
    {
       //unauthorise_permission('207','1196');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysisbooking->cancel_appointment($id);
           $response = "Dialysis Appointment cancelled successfully.";
           echo $response;
       }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->dialysisbooking->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('dialysis_appointment/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('207','1197');
        $data['page_title'] = 'Dialysis Appointment Archive List';
        $this->load->helper('url');
        $this->load->view('dialysis_appointment/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('207','1197');
        $this->load->model('dialysis_booking/dialysis_booking_archive_model','dialysis_booking_archive');
        $list = $this->dialysis_booking_archive->get_datatables();
       // print_r();die;  
        $dialysis_time='';
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $dialysis) { 
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysis->id.'">'.$check_script; 
          $row[] = $dialysis->ipd_no;
          $row[] = $dialysis->p_code;  
          $row[] = $dialysis->p_name;
          $row[] = date('d-m-Y',strtotime($dialysis->dialysis_date));
          if($dialysis->dialysis_time=="00:00:00")
          {
              $dialysis_time='';
          }
          else
          {
              $dialysis_time=date('h:i A',strtotime($dialysis->dialysis_time));
          }
          $row[] = $dialysis_time;
          $doctor_list= $this->dialysisbooking->doctor_list_by_otids($dialysis->id);
          $doctor_name=array();
          foreach($doctor_list as $doctor_list=>$value){
          $doctor_name[]=$value[0];
          }
           
            $name= implode(',',$doctor_name);
            $row[] = $name;
            $row[] = $dialysis->room_type;
            $row[] = $dialysis->room_no;
            $row[] = $dialysis->bad_no;
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1199',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_dialysis_pacakge('.$dialysis->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1198',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$dialysis->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dialysis_booking_archive->count_all(),
                        "recordsFiltered" => $this->dialysis_booking_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission('207','1199');
       $this->load->model('dialysis_booking/dialysis_booking_archive_model','dialysis_booking_archive');
         if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_booking_archive->restore($id);
           $response = "Dialysis appointment successfully restore in Dialysis booking list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('207','1199');
        $this->load->model('dialysis_booking/dialysis_booking_archive_model','dialysis_booking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_booking_archive->restoreall($post['row_id']);
            $response = "Dialysis appointment successfully restore in Dialysis booking list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('207','1198');
       $this->load->model('dialysis_booking/dialysis_booking_archive_model','dialysis_booking_archive');
        if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_booking_archive->trash($id);
           $response = "Dialysis appointment successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('207','1198');
        $this->load->model('dialysis_booking/dialysis_booking_archive_model','dialysis_booking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_booking_archive->trashall($post['row_id']);
            $response = "Dialysis appointment successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function dialysis_pacakage_dropdown()
  {
	  $dialysis_pacakge_list = $this->dialysisbooking->pacakage_list();
    //print_r($dialysis_pacakge_list);die;
      $dropdown = '<option value="">Select Dialysis Package</option>'; 
      if(!empty($dialysis_pacakge_list))
      {
        foreach($dialysis_pacakge_list as $dialysispackage)
        {
           $dropdown .= '<option value="'.$dialysispackage->id.'">'.$dialysispackage->name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  public function dialysis_name_dropdown()
  {
    $dialysis_name_list = $this->dialysisbooking->dialysis_list();
    $dropdown = '<option value="">Select Dialysis</option>'; 
    if(!empty($dialysis_name_list))
    {
    foreach($dialysis_name_list as $dialysisname)
    {
    $dropdown .= '<option value="'.$dialysisname->id.'">'.$dialysisname->name.'</option>';
    }
    } 
    echo $dropdown; 
  }

  function get_vals($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->dialysis->get_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_doctor_name($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->dialysisbooking->get_doctor_name($vals);  
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

    public function print_dialysis_appointment_report($ids="")
    {
       
        $user_detail= $this->session->userdata('auth_users');

        $data['page_title'] = "Add Dialysis Appointment";
        if(!empty($ids))
        {
          $ot_book_id= $ids;
        }
        else
        {
          $ot_book_id= $this->session->userdata('ot_book_id');
        }
        $get_detail_by_id= $this->dialysisbooking->get_by_id($ot_book_id);
        $get_by_id_data=$this->dialysisbooking->get_all_detail_print($ot_book_id,$get_detail_by_id['branch_id']);
        //print_r($get_by_id_data);die;
        $template_format= $this->dialysisbooking->template_format(array('section_id'=>8,'types'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $this->load->model('general/general_model');
        $get_date_time_setting = $this->general_model->get_date_time_setting('dialysis_booking');
        if(isset($get_date_time_setting->date_time_status))
        {
            $data['date_time_status']=$get_date_time_setting->date_time_status;
        }
        else
        {
          $data['date_time_status']='';
        }
        $this->load->view('dialysis_appointment/print_template_dialysis_booking',$data);
 }
 
 public function print_dialysis_booking_report($ids="")
    {
       
        $user_detail= $this->session->userdata('auth_users');

        $data['page_title'] = "Add Dialysis Appointment";
        if(!empty($ids))
        {
          $ot_book_id= $ids;
        }
        else
        {
          $ot_book_id= $this->session->userdata('ot_book_id');
        }
        $get_detail_by_id= $this->dialysisbooking->get_by_id($ot_book_id);
        $get_by_id_data=$this->dialysisbooking->get_all_detail_print($ot_book_id,$get_detail_by_id['branch_id']);
        //print_r($get_by_id_data);die;
        $template_format= $this->dialysisbooking->template_format(array('section_id'=>8,'types'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $this->load->model('general/general_model');
        $get_date_time_setting = $this->general_model->get_date_time_setting('dialysis_booking');
        if(isset($get_date_time_setting->date_time_status))
        {
            $data['date_time_status']=$get_date_time_setting->date_time_status;
        }
        else
        {
          $data['date_time_status']='';
        }
        $this->load->view('dialysis_booking/print_template_dialysis_booking',$data);
 }
 
public function confirm_booking($id)
{
      $this->load->helper('url');
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->dialysisbooking->get_by_id($id);
        //echo "<pre>";print_r($result); exit;
        $patient_id = $result['patient_id'];
        $data['page_title'] = "Confirm Booking";  
        $post = $this->input->post();
        $dialysis_booking_no = generate_unique_id(34);
        $this->load->model('general/general_model');
        $data['form_error'] = ''; 
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'booking_code'=>$dialysis_booking_no,
                                  'appointment_id'=>$id, 
                                  'dialysis_date'=>date('d-m-Y',strtotime($result['dialysis_date'])),
                                  'dialysis_time'=>$result['dialysis_time'],
                                  'advance_amount'=>'', 
                                  
                                  'booking_status'=>1,
                                  'field_name'=>'',
                                  'payment_mode'=>"",
                                  'patient_id'=>$patient_id,
                                  'consultant'=>$consultant,
                                  'attended_doctor' =>$consultant,   
                                  );  
        
        if(!empty($post))
        {   
        
            $data['form_data'] = $this->_validate_booking_confirm();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->dialysisbooking->confirm_booking();
                $this->session->set_userdata('dialysis_book_id',$booking_id);
                echo 1;
                return false;
                        
            }
            else
            {
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
                        
                        $dialysis_booking_no = generate_unique_id(34);
                         $data['form_data'] = array(
                                          'data_id'=>$result['id'],
                                          'booking_code'=>$dialysis_booking_no,
                                          'appointment_id'=>$id, 
                                          
                                          'booking_status'=>1,
                                          'field_name'=>$total_values,
                                          'consultant'=>$consultant,
                                          'payment_mode'=>$post['payment_mode'],
                                          'patient_id'=>$patient_id,
                                          'dialysis_name'=>'',
                                          );  
                        $data['form_error'] = validation_errors(); 
        
        
                    }     
        }
        $this->load->model('dialysis_booking/dialysis_booking_model','dialysis_booking_mod');
        $data['room_type_list']=$this->general_model->dialysis_room_type_list();
        $data['room_no'] = $this->dialysis_booking_mod->get_dialysis_room_no();
        $data['bed_no'] = $this->dialysis_booking_mod->get_dialysis_bed_no();
        $data['dialysisn_list']= $this->dialysis_booking_mod->dialysis_list();
        
       $this->load->view('dialysis_appointment/confirm',$data);       
      }
    }
    
    private function _validate_booking_confirm()
    {
        $post = $this->input->post();  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('advance_amount', 'Advance Amount', 'trim');
         if(isset($post['field_name']))
         {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required');
         }
        
       
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
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                'data_id'=>$post['data_id'],
                                'advance_amount'=>$post['advance_amount'],
                                'field_name'=>$total_values,
                                 'consultant'=>$post['consultant'],
                                 'dialysis_name'=>$post['dialysis_name'],
                                ); 
            return $data['form_data'];
        }  

    }
    
    public function reschedule_appointment($id)
    {
      $this->load->helper('url');
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->dialysisbooking->get_by_id($id);
        //echo "<pre>";print_r($result); exit;
        $patient_id = $result['patient_id'];
        $data['page_title'] = "Reschedule Booking";  
        $post = $this->input->post();
        //$dialysis_booking_no = generate_unique_id(34);
        $this->load->model('general/general_model');
        $data['form_error'] = ''; 
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'booking_code'=>$result[''],
                                  'appointment_id'=>$id, 
                                  'dialysis_date'=>date('d-m-Y',strtotime($result['dialysis_date'])),
                                  'dialysis_time'=>$result['dialysis_time'],
                                  'room_id'=>$result['room_id'],
                                  'patient_id'=>$result['dialysis_name'],
                                  'dialysis_name' =>$result['dialysis_name'],   
                                  );  
        
        if(!empty($post))
        {   
        
            $data['form_data'] = $this->_validate_reschedule_confirm();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->dialysisbooking->reschedule_booking();
                //$this->session->set_userdata('dialysis_book_id',$booking_id);
                echo 1;
                return false;
                        
            }
            else
            {
                $data['form_data'] = array(
                                          'data_id'=>$result['id'],
                                          'booking_code'=>$post['booking_code'],
                                          'appointment_id'=>$id, 
                                          'booking_status'=>1,
                                          'field_name'=>$total_values,
                                          'consultant'=>$consultant,
                                          'payment_mode'=>$post['payment_mode'],
                                          'patient_id'=>$patient_id,
                                          'dialysis_name'=>$post['dialysis_name'],
                                          );  
                        $data['form_error'] = validation_errors(); 
        
        
                    }     
        }
        $this->load->model('dialysis_booking/dialysis_booking_model','dialysis_booking_mod');
        $data['room_type_list']=$this->general_model->dialysis_room_type_list();
        $data['room_no'] = $this->dialysis_booking_mod->get_dialysis_room_no();
        $data['bed_no'] = $this->dialysis_booking_mod->get_dialysis_bed_no();
        $data['dialysisn_list']= $this->dialysis_booking_mod->dialysis_list();
        
       $this->load->view('dialysis_appointment/reschedule',$data);       
      }
    }
    
    private function _validate_reschedule_confirm()
    {
        $post = $this->input->post();  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('dialysis_name', 'Dialysis', 'trim');
        //$this->form_validation->set_rules('room_id', 'Room', 'trim|required');
        $this->form_validation->set_rules('dialysis_name', 'dialysis name', 'trim|required'); 
          $this->form_validation->set_rules('schedule_id', 'Schedule', 'trim|required'); 
        $this->form_validation->set_rules('available_time', 'Available Time', 'trim|required'); 
        $this->form_validation->set_rules('doctor_slot', 'Schedule Slot', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                'data_id'=>$post['data_id'],
                              
                                // 'room_id'=>$post['room_id'],
                                 'dialysis_name'=>$post['dialysis_name'],
                                 'schedule_id'=>$post['schedule_id'],
                                'available_time'=>$post['available_time'],
                                 'doctor_slot'=>$post['doctor_slot'],
                                 //'room_no_id'=>$post['room_no_id'],
                                 //'bed_no_id'=>$post['bed_no_id'],
                                ); 
            return $data['form_data'];
        }  

    }

}
?>