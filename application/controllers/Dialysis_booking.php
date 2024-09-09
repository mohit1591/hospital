<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_booking extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dialysis_booking/dialysis_booking_model','dialysisbooking');
        $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('207','1193');
        $data['page_title'] = 'Dialysis Booking List'; 
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
        $this->load->view('dialysis_booking/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('207','1193');
        $users_data = $this->session->userdata('auth_users');
        //$list = $this->dialysisbooking->get_datatables();  
        $list = $this->dialysisbooking->get_datatables();
        $recordsTotal = $this->dialysisbooking->count_all();
        $recordsFiltered = $recordsTotal;
        $dialysis_time='';
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $btn_list='';
        
        $total_num = count($list);
        foreach ($list as $dialysis) {
            // echo "<pre>"; print_r($dialysis);die;
            $no++;
            $row = array();
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
            $row[] = $dialysis->p_code; 
            $row[] = $dialysis->booking_code; 
            $row[] = $dialysis->dialysiss_name; 
            
            $row[] = $dialysis->p_name;
            $row[] = date('d-m-Y',strtotime($dialysis->dialysis_booking_date));
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
           /* $doctor_list= $this->dialysisbooking->doctor_list_by_otids($dialysis->id);
           $doctor_name=array();
            foreach($doctor_list as $doctor_list=>$value){
              $doctor_name[]=$value[0];
             }
           
            $name= implode(',',$doctor_name);*/
            //$row[] = $name;
            $row[] = $dialysis->room_type;
            $row[] = $dialysis->room_no;
            $row[] = $dialysis->bad_no;
            $row[] = $dialysis->no_of_visit;
            $row[] = $dialysis->no_of_visit_done;
           
           
          $btnedit='';
          $btndelete='';
          if(in_array('1195',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_dialysis_booking('.$dialysis->id.');" class="btn-custom" href="javascript:void(0)" style="'.$dialysis->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1196',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_dialysis_booking('.$dialysis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $btnprint='';
          if(in_array('1195',$users_data['permission']['action'])){
          $print_url = "'".base_url('dialysis_booking/print_dialysis_booking_report/'.$dialysis->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
          }
          
           $btndialysis_summary = '<a   href='.base_url('dialysis_patient_summary/add/'.$dialysis->id.'/'.$dialysis->patient_id).' title="Dialysis Summary" data-url="512"> <i class="fa fa-bed" aria-hidden="true"></i> Dialysis Summary</a>';

          
                  $btncharge_entry='';
        //$btncharge_entry = '<a   href='.base_url('dialysis_booking/add_charge/'.$dialysis->id.'/'.$dialysis->patient_id).' title="Charge Entry" data-url="512"> <i class="fa fa-keyboard-o" aria-hidden="true"></i> Charge Entry</a>';
            
        $procedure_entry = '<a   href='.base_url('dialysis_booking/procedure_bill/'.$dialysis->id.'/'.$dialysis->patient_id).' title="Procedure Bill" data-url="512"> <i class="fa fa-keyboard-o" aria-hidden="true"></i> Procedure Bill</a>';
        
            $btnprint_procedure='';
             if(!empty($dialysis->procedure_bill_no))
             {
                 $print_procedure_url = "'".base_url('dialysis_booking/print_procedure_bill/'.$dialysis->id.'/'.$dialysis->patient_id)."'";
                $btnprint_procedure = '<a href="javascript:void(0)" onClick="return print_window_page('.$print_procedure_url.')" title="Print" ><i class="fa fa-print"></i> Procedure Bill Print</a>';
             }
        
       // $prescription_count = $this->dialysisbooking->get_prescription_count($dialysis->id);
        $btn_prescription='';
        
         $btn_prescription = '<a  href="'.base_url("dialysis_booking/prescription/".$dialysis->id).'" title="Add Prescription"> <i class="fa fa-pencil"></i> Add Prescription</a>';
       
        if($dialysis->no_of_visit==$dialysis->no_of_visit_done)
        {
            $btn_next_dialysis = '';
        }
        else
        {
            $btn_next_dialysis = '<a  onclick="next_dialysis('.$dialysis->id.','.$dialysis->patient_id.');"  title="Next Dialysis"> <i class="fa fa-database" aria-hidden="true"></i> Next Dialysis</a>';
        }
        
          $btn_view_prescription = '<a  href="'.base_url("dialysis_prescription/index/".$dialysis->id.'/'.$dialysis->patient_id).'" title="View Prescription"> <i class="fa fa-circle"></i> View Prescription</a>';
          
         $btn_view_note_summary = '<a  href="'.base_url("dialysis_patient_summary/index/".$dialysis->id.'/'.$dialysis->patient_id).'" title="View Summary"> <i class="fa fa-circle"></i> View Summary</a>';
           
          $btn_view_summary = '<a  href="'.base_url("dialysis_booking/view_history/".$dialysis->id.'/'.$dialysis->patient_id).'" title="View History"> <i class="fa fa-circle"></i> View History</a>';
          
          $btn_add_inventory = '<a onClick="add_inventory('.$dialysis->id.','.$dialysis->patient_id.')"><i class="fa fa-cubes"></i> Add Inventory</a>';
          
          $btn_view_inventory = '<a  href="'.base_url("dialysis_booking/view_Inventory/".$dialysis->id.'/'.$dialysis->patient_id).'" title="View Inventory"> <i class="fa fa-circle"></i> View Inventory</a>';
          
           $print_adv_url = "'".base_url('dialysis_booking/print_advance_payment_report/'.$dialysis->id)."'";
            $btnprint_adv = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_adv_url.')" title="Print" ><i class="fa fa-print"></i> Print Advance</a>';
            
            $print_admission_pdf_url = "'".base_url('dialysis_booking/print_dialysis_adminssion/'.$dialysis->id)."'";
            
            $btn_admission_print = '<li><a  onclick="print_window_page('.$print_admission_pdf_url.');" target="_blank"><i class="fa fa-print"></i> Print Admission</a></li>';
          
          
          $btn_a = '<div class="slidedown">
            <button disabled class="btn-custom">More <span class="caret"></span></button>
            <ul class="slidedown-content">
              '.$btn_admission_print.$btncharge_entry.$procedure_entry.$btnprint_procedure.$btn_prescription.$btn_view_prescription.$btndialysis_summary.$btn_view_note_summary.$btn_view_summary.$btn_next_dialysis.$btn_add_inventory.$btn_view_inventory.$btndelete.'
            </ul>
          </div> ';
                // End Action Button //
        $row[] = $btnedit.$btnprint.$btnprint_adv.$btn_a; 
            
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $recordsTotal,
                        "recordsFiltered" =>$recordsFiltered,
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

        $data['page_title'] = "Add Dialysis Booking";
        if(!empty($ids))
        {
          $dialysis_book_id= $ids;
        }
        else
        {
          $dialysis_book_id= $this->session->userdata('dialysis_book_id');
        }
        $get_detail_by_id= $this->dialysisbooking->get_by_id($dialysis_book_id);
        $get_by_id_data=$this->dialysisbooking->get_all_detail_print($dialysis_book_id,$get_detail_by_id['branch_id']);
        //print_r($get_by_id_data);die;
        $template_format= $this->dialysisbooking->template_format(array('section_id'=>8,'types'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $this->load->model('general/general_model');
        $get_date_time_setting = $this->general_model->get_date_time_setting('dialysis_booking');
        $data['date_time_status'] = $get_date_time_setting->date_time_status;
        $this->load->view('dialysis_booking/print_template_dialysis_booking',$data);
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
          $fields = array($data_reg_name,'Booking No.','Dialysis Name','Patient Name','Booking Date','Dialysis Date','Dialysis Time','Doctor Name','Room Type','Room No','Bed No.');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
           
           $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
           
           
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
               $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
               
               $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
               
              
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
                    array_push($rowData,$reports->p_code,$reports->booking_code,$reports->dialysiss_name,$reports->p_name, date('d-m-Y',strtotime($reports->dialysis_booking_date)),date('d-m-Y',strtotime($reports->dialysis_date)),date('h:i A',strtotime($reports->dialysis_time)),$name,$reports->room_type,$reports->room_no,$reports->bad_no);
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
          header("Content-Disposition: attachment; filename=dialysis_booking_report_".time().".xls");
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
        $this->load->view('dialysis_booking/dialysis_booking_report_html',$data);
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
      $this->load->view('dialysis_booking/dialysis_booking_report_html',$data); 
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
                                      "dialysis_no"=>"",
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
            $this->load->view('dialysis_booking/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('dialysis_booking_serach');
    }
    
    public function add($patient_id='',$ipd_id='')
    {
        if(!empty($_GET['appointment_id']))
        {
            $appointment_id = $_GET['appointment_id'];
        }
        else
        {
            $appointment_id = '';
        }
        
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
        $dialysis_booking_no = generate_unique_id(34);
        $patient_reg_code=generate_unique_id(4);
        $relation_name='';
        $relation_type='';
        $relation_simulation_id='';
        $dialysis_name='';
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
        else if(!empty($appointment_id))
        {
            $this->load->model('dialysis_appointment/dialysis_appointment_model','dialysisapp');
            $result = $this->dialysisapp->get_by_id($appointment_id);
            $dialysis_name = $result['dialysis_name'];
            $pid = $result['patient_id'];
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
              $patient_type ='';
            }

            
            //echo "<pre>"; print_r($result); exit;
            
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
              $patient_type ='';
            }

            $reg_id=$patient_id;
       
        }


        $this->load->model('general/general_model');
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['simulation_list']= $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['dialysis_pacakage_list']= $this->dialysisbooking->pacakage_list();
        $data['dialysisn_list']= $this->dialysisbooking->dialysis_list();
        $data['dialysis_room_list']= $this->dialysisbooking->dialysis_room_list();
        $data['remarks_list']= $this->dialysisbooking->remarks_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        
        $data['room_type_list']=$this->general_model->dialysis_room_type_list();
        $data['room_no'] = $this->dialysisbooking->get_dialysis_room_no();
        $data['bed_no'] = $this->dialysisbooking->get_dialysis_bed_no();
        $data['country_list'] = $this->general_model->country_list();
        
        $data['page_title'] = "Dialysis Booking";  
        $post = $this->input->post();

       
       //print '<pre>'; print_r($post);die;
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'dialysis_booking_code'=>$dialysis_booking_no,
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
                                  "address"=>$address,
                                  "address_second"=>$address_second,
                                  "address_third"=>$address_third,
                                  "room_no"=>$room_no,
                                  'reg_patient'=>$reg_id,
                                  "patient_type"=>$patient_type,
                                  
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
                                  "room_id"=>"",
                                  "room_no_id"=>"",
                                  'patient_email'=>'',
                                  'country_id'=>'99',
                                  'city_id'=>'',
                                  'state_id'=>'',
                                  'dob'=>'',
                                  'no_of_visit'=>'',
                                  'no_pf_visit_duration'=>'',
                                  'no_pf_visit_unit'=>'',
                                  "advance_deposite"=>"",
                                  'payment_mode'=>"",
                                  'dialysis_name'=>$dialysis_name,
                                  'appointment_id'=>$appointment_id,
                                  ); 
        //print '<pre>'; print_r($data['form_data']);die;
        if(isset($post) && !empty($post))
        {   
              $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                 if(isset($post['dialysis_type']) && $post['dialysis_type']==1)
                 {
                    $p_hours= $this->dialysisbooking->get_dialysis_hours($post['dialysis_name']);  
                    $time_n=date('H:i:s',strtotime($post['dialysis_time']));
                    $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
                    $start_time=date('Y-m-d',strtotime($post['dialysis_date'])).' '.$time_n;
                    $end_date_time= date('Y-m-d',strtotime($post['dialysis_date'])).' '.$time;
                 }
                if(isset($post['pacakage_name'])  && $post['dialysis_type']==2)
                {
                    $p_hours= $this->dialysisbooking->get_package_hours($post['pacakage_name']);
                    $time_n=date('H:i:s',strtotime($post['dialysis_time']));
                    $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
                    $start_time=date('Y-m-d',strtotime($post['dialysis_date'])).' '.$time_n;
                    $end_date_time= date('Y-m-d',strtotime($post['dialysis_date'])).' '.$time;

                }

                   $check_ot_scheduled_time=  $this->dialysisbooking->dialysis_scheduled_time(date('Y-m-d',strtotime($post['dialysis_date'])),$start_time,$end_date_time,$post['dialysis_room']);


                    if(!empty($check_ot_scheduled_time) && $check_ot_scheduled_time['error']==1)
                    {

                      $this->session->set_flashdata('error','Dialysis Booking not possible at that time.');
                      redirect(base_url('dialysis_booking'));
                    }
                   

                   else if(isset($post['doctor_names']))
                   {
                     $check_ot_scheduled_doctor=  $this->dialysisbooking->dialysis_scheduled_doctor(date('Y-m-d',strtotime($post['dialysis_date'])),$start_time,$end_date_time,$post['doctor_names']);
                      if(!empty($check_ot_scheduled_doctor) && $check_ot_scheduled_doctor['error']==3)
                      {
                       
                      $this->session->set_flashdata('error','Dialysis Booking not possible at that time.');
                      redirect(base_url('dialysis_booking'));
                      }
                      else if(!empty($check_ot_scheduled_doctor['doctor_error']) && $check_ot_scheduled_doctor['doctor_error']==4)
                      {
                        $this->session->set_flashdata('error','Dialysis Booking not possible at that time because doctor not avilable.');
                        redirect(base_url('dialysis_booking'));
                      }
                      else
                      {
                          $book_id= $this->dialysisbooking->save();
                          $this->session->set_userdata('dialysis_book_id',$book_id);
                          $this->session->set_flashdata('success','Dialysis Booking has been successfully added.');
                          redirect(base_url('dialysis_booking/add/?status=print')); 
                      }
                    }
                    else
                    {
                   
                      $book_id= $this->dialysisbooking->save();
                      $this->session->set_userdata('dialysis_book_id',$book_id);
                      $this->session->set_flashdata('success','Dialysis Booking has been successfully added.');
                      //redirect(base_url('dialysis_booking/?status=print')); 
                  redirect(base_url('dialysis_booking/add/?status=print'));
                    }

                    
                   //print_r($check_ot_scheduled_time['error']);die;
                   
                  
                  
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
     
       $this->load->view('dialysis_booking/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('207','1195');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model');
        $data['payment_mode']=$this->general_model->payment_mode();
        $result = $this->dialysisbooking->get_by_id($id);  
        $dialysis_time='';
        $data['page_title'] = "Update Dialysis Booking";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['dialysis_pacakage_list']= $this->dialysisbooking->pacakage_list();
        $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['doctor_list'] =$this->dialysisbooking->doctor_list_by_otids($id);
        $data['package_list'] =$this->dialysisbooking->package_list_by_otids($id);
        $data['dialysis_room_list']= $this->dialysisbooking->dialysis_room_list();
        //print '<pre>';print_r($result);die;
        $data['remarks_list']= $this->dialysisbooking->remarks_list();
        $data['simulation_list']= $this->general_model->simulation_list();
        $this->load->model('general/general_model','general');
        $data['dialysisn_list']= $this->dialysisbooking->dialysis_list();
        $data['referal_hospital_list'] = $this->general->referal_hospital_list();
        $data['dialysis_room_list']= $this->dialysisbooking->dialysis_room_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['country_list'] = $this->general_model->country_list();
        
        $data['room_type_list']=$this->general_model->dialysis_room_type_list($id);
        
        
        
        $data['room_no'] = $this->dialysisbooking->get_dialysis_room_no();
        $data['bed_no'] = $this->dialysisbooking->get_dialysis_bed_no();
        
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
                                  'dialysis_booking_code'=>$result['booking_code'],
                                  'patient_code'=>$result['patient_code'],
                                  'patient_id'=>$result['patient_id'],
                                  'reg_patient'=>$result['patient_id'],
                                  'ipd_id'=>$result['ipd_id'],
                                  'relation_simulation_id'=>$result['relation_simulation_id'],
                                  'relation_name'=>$result['relation_name'],
                                  'relation_type'=>$result['relation_type'],
                                  'ipd_no'=>$result['ipd_no'],
                                  'simulation_id'=>$result['simulation_id'],
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
                                  "room_no"=>$result['room_no'],
                                  "patient_type"=>$result['patient_type'],
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
                                  
                                  'city_id'=>$result['city_id'],
                                  'state_id'=>$result['state_id'],
                                  'country_id'=>$result['country_id'],
                                  'patient_email'=>$result['patient_email'],
                                  "room_id"=>$result['room_id'],
                                  'room_no_id'=>$result['room_no_id'],
            					  'bed_no_id'=>$result['bed_no_id'],
                                  
                                  "room_type_id"=>$result['room_type_id'],
                                  'dob'=>$result['dob'],
                                  'ref_by_other'=>'',
                                  'no_of_visit'=>$result['no_of_visit'],
                                  'no_pf_visit_duration'=>$result['no_pf_visit_duration'],
                                  'no_pf_visit_unit'=>$result['no_pf_visit_unit'],
                                  "advance_deposite"=>$result['advance_payment'],
                                  'payment_mode'=>$result['payment_mode'],
                                  );  
                                  
                                  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {

                $book_id= $this->dialysisbooking->save();
                $this->session->set_userdata('dialysis_book_id',$book_id);
                $this->session->set_flashdata('success','Dialysis Booking has been successfully updated.');
                redirect(base_url('dialysis_booking/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }  

        }
        //print '<pre>'; print_r($data);die;
       $this->load->view('dialysis_booking/add',$data);       
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
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        $this->form_validation->set_rules('adhar_no', 'aadhaar no', 'min_length[12]|max_length[16]'); 
         if(isset($post['ipd_id']) && !empty($post['ipd_id']))
         {
           $this->form_validation->set_rules('room_no', 'Room No', 'trim|required');
          $this->form_validation->set_rules('patient_type', 'patient type', 'trim|required');

         }
        if(isset($post['dialysis_type']) && $post['dialysis_type']==1)
        {
          $this->form_validation->set_rules('dialysis_name', 'dialysis name', 'trim|required'); 
        }
        
        $this->form_validation->set_rules('dialysis_date', 'dialysis date', 'trim|required'); 
          $this->form_validation->set_rules('dialysis_booking_date', 'dialysis booking date', 'trim|required'); 
        $this->form_validation->set_rules('remarks', 'remarks', 'trim|required'); 
        
        $this->form_validation->set_rules('room_id', 'Room Type', 'trim|required'); 
        $this->form_validation->set_rules('room_no_id', 'Room No.', 'trim|required'); 
        $this->form_validation->set_rules('bed_no_id', 'Bed No.', 'trim|required'); 

        
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
         
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                      'data_id'=>$post['data_id'], 
                                      'patient_code'=>$post['patient_reg_code'],
                                      "patient_id"=>$post['patient_id'],
                                      'reg_patient'=>$post['reg_patient'],
                                      'dialysis_booking_code'=>$post['dialysis_booking_code'],
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
                                      "patient_type"=>$post['patient_type'],
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
                                      'room_type_id'=>$post['room_id'],
                                        'room_id'=>$post['room_no_id'],
                                        'bad_id'=>$post['bed_no_id'],
                                         "advance_deposite"=>$post['advance_deposite'],
                                         'payment_mode'=>$post['payment_mode'],
                                         'no_of_visit'=>$post['no_of_visit'],
                                       ); 
            return $data['form_data'];
        }   
    }

    
 
    public function delete($id="")
    {
       unauthorise_permission('207','1196');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysisbooking->delete($id);
           $response = "Dialysis booking successfully deleted.";
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
            $response = "Dialysis booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->dialysisbooking->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('dialysis_booking/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('207','1197');
        $data['page_title'] = 'Dialysis Booking Archive List';
        $this->load->helper('url');
        $this->load->view('dialysis_booking/archive',$data);
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
           $response = "Dialysis booking successfully restore in Dialysis booking list.";
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
            $response = "Dialysis booking successfully restore in Dialysis booking list.";
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
           $response = "Dialysis booking successfully deleted parmanently.";
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
            $response = "Dialysis booking successfully deleted parmanently.";
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
    
    function get_package_name($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->dialysisbooking->get_package_name($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
    function append_package_list()
    {
     $name=  $this->input->post('name');
     $row_count=$this->input->post('rowCount');
     $package_id=$this->input->post('package_id');
     $amount=$this->input->post('amount');
     $i=1;
     if(!empty($name)){
     $table=' <tr> <td><input type="checkbox" value="'.$name.'" name="package_names['.$package_id.'][]" class="child_checkbox" onclick="add_package_check();" checked/></td><td>'.$row_count.'</td><td>'.ucfirst($name).'</td><td>'.$amount.'</td></tr>';
            $i++;
            echo $table;exit;
          }

    }

    public function print_dialysis_booking_report($ids="")
    {
       
        $user_detail= $this->session->userdata('auth_users');

        $data['page_title'] = "Add Dialysis Booking";
        if(!empty($ids))
        {
          $dialysis_book_id= $ids;
        }
        else
        {
          $dialysis_book_id= $this->session->userdata('dialysis_book_id');
        }
        $get_detail_by_id= $this->dialysisbooking->get_by_id($dialysis_book_id);
        $get_by_id_data=$this->dialysisbooking->get_all_detail_print($dialysis_book_id,$get_detail_by_id['branch_id']);
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
 
 
 public function select_room_number()
    {
     $this->load->model('general/general_model');
     $room_id= $this->input->post('room_id');
     $room_no_id= $this->input->post('room_no_id');
     if(!empty($room_id)){
       $data['number_rooms']= $this->general_model->dialysis_room_no_list($room_id);
     }
     $dropdown = '<option value="">-Select-</option>'; 
     if(!empty($data['number_rooms']))
     {
       $selected='';
       foreach($data['number_rooms'] as $number_rooms)
       {
        if($room_no_id==$number_rooms->id){
          $selected='selected=selected';

        }else{
          $selected='';
        }
        $dropdown .= '<option value="'.$number_rooms->id.'"  '.$selected.'>'.$number_rooms->room_no.'</option>';
      }
    } 
    echo $dropdown; 
    
  }
  public function select_bed_no_number()
  {
    $this->load->model('general/general_model');
    $room_id= $this->input->post('room_id');
    $room_no_id= $this->input->post('room_no_id');
    $bed_id= $this->input->post('bed_id');
    $dialysis_id= $this->input->post('dialysis_id');
    if(!empty($room_id) && !empty($room_no_id))
    {
      $data['number_bed']= $this->general_model->dialysis_number_bed_list($room_id,$room_no_id,$dialysis_id);
    }
          //print_r($data['number_bed']); exit;
    $selected='';
          // print_r($data['number_bed']);die;
    $dropdown = '<option value="">-Select-</option>'; 
    if(!empty($data['number_bed']))
    {
      foreach($data['number_bed'] as $number_bed)
      {
              //print_r($number_bed);
              //echo $number_bed->ipd_is_deleted; 
        
        if($bed_id == $number_bed->id)
        {
          $selected="selected=selected";
        }
        else
        {
          $selected='';
        }
        if($bed_id == $number_bed->id && $number_bed->status==1)
        {

          $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_name.'</option>';
        }
        else
        {
          if($number_bed->ipd_is_deleted=='2' ||  ($number_bed->ipd_is_deleted=='' || ($number_bed->ipd_is_deleted!=1 && $number_bed->ipd_is_deleted!=0) ))
          { 
            if($number_bed->status==1 && $number_bed->ipd_is_deleted==2)
            {
              $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_name.'</option>';  
            }
            else
            {
              $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_name.'</option>'; 
            }  
            
          }
        }
        
      }
    } 
    echo $dropdown; 
  }
  
  public function add_charge($dialysis_id="",$patient_id="")
  {
        unauthorise_permission(128,777);
        $post = $this->input->post();
        if(!isset($post) || empty($post))
        {
          $this->session->unset_userdata('dialysis_particular_charge_billing');  
        }
        
        $data['page_title'] = 'Charge Entry';
        $data['particulars_charges']='';
        $data['data_id']='';
        $data['patient_id']=$patient_id;
        $data['dialysis_id']=$dialysis_id;
        $this->load->model('general/general_model');
        
        $data['patient_details']= $this->general_model->get_patient_according_to_dialysis($dialysis_id,$patient_id); 
        //echo "<pre>"; print_r($data['patient_details']); exit;
        $data['particulars_list'] = $this->general_model->ipd_particulars_list();
        $data['perticuller_list']= $this->general_model->get_dialysis_particular_details($dialysis_id,$patient_id);
         $data['simulation_list'] = $this->general_model->simulation_list();
       if((!isset($post) || empty($post)) && count($data['perticuller_list'])>=1)
       {
          
            $dialysis_particular_charge_billing = $this->session->userdata('dialysis_particular_charge_billing');
            //echo '<pre>'; print_r($dialysis_particular_charge_billing); exit;
            if(isset($dialysis_particular_charge_billing) && !empty($dialysis_particular_charge_billing))
            {
              $dialysis_particular_charge_billing = $dialysis_particular_charge_billing; 
            }
            else
            {
              $dialysis_particular_charge_billing = [];
            }
            $i=1;
            foreach($data['perticuller_list'] as $particulars_data)
            {
              //$particulars_data['charge_id']
                $dialysis_particular_charge_billing[] = array('charge_id'=>$i,'particular'=>$particulars_data['particular'],'doctor'=>$particulars_data['doctor'],'doctor_id'=>$particulars_data['doctor_id'],'s_date'=>date('d-m-Y',strtotime($particulars_data['s_date'])), 'quantity'=>$particulars_data['quantity'], 'amount'=>$particulars_data['amount'],'particulars'=>$particulars_data['particulars'],'charges'=>$particulars_data['charges']);
                $amount_arr = array_column($dialysis_particular_charge_billing, 'amount'); 
                $total_amount = array_sum($amount_arr);
                $this->session->set_userdata('dialysis_particular_charge_billing',$dialysis_particular_charge_billing);

            $i++;
            }


            $total = $total_amount;
          
            if($total==0)
            {
              $totamount = '0.00';
            }
            else
            {
              $totamount = number_format($total,2,'.','');
            }

             $ipd_particular_payment_array = array('total_amount'=>$totamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
            $this->session->set_userdata('dialysis_particular_payment', $ipd_particular_payment_array);
       }
      
        //echo "<pre>";print_r($post); exit;
        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validatecharges();
            if($this->form_validation->run() == TRUE)
            {
              //print_r($ipd_particular_billing_list);die;
                $charge_id = $this->dialysisbooking->save_charges($dialysis_id,$patient_id);
                $this->session->set_flashdata('success','Dialysis charge added successfully.');
                redirect(base_url('dialysis_booking/add_charge/'.$post['dialysis_id'].'/'.$patient_id));
            }
            else
            {
                $data['form_error'] = validation_errors(); 
            }
                
        }
        $this->load->view('dialysis_booking/add_charge',$data);
    }
    
    
    public function particular_payment_calculation()
    {
          $post = $this->input->post();
          if(isset($post) && !empty($post))
          {   
            $dialysis_particular_charge_billing = $this->session->userdata('dialysis_particular_charge_billing');

            if(isset($dialysis_particular_charge_billing) && !empty($dialysis_particular_charge_billing))
            {
              $dialysis_particular_charge_billing = $dialysis_particular_charge_billing; 
            }
            else
            {
              $dialysis_particular_charge_billing = [];
            }

            $p = count($dialysis_particular_charge_billing)+1; 

          $dialysis_particular_charge_billing[] = array('charge_id'=>$p,'particular'=>$post['particular'],'doctor'=>$post['doctor'],'doctor_id'=>$post['doctor_id'],'s_date'=>$post['s_date'], 'quantity'=>$post['quantity'], 'amount'=>$post['amount'],'particulars'=>$post['particulars'],'charges'=>$post['charges']);
          $amount_arr = array_column($dialysis_particular_charge_billing, 'amount'); 
          $total_amount = array_sum($amount_arr);
          
          //print_r($dialysis_particular_charge_billing); exit; // [charge_id] => 1
          $this->session->set_userdata('dialysis_particular_charge_billing', $dialysis_particular_charge_billing);

          //print '<pre>';print_r($ipd_particular_billing);die;
          $html_data = $this->dialysis_perticuller_list();
          $total = $total_amount;
          
          if($total==0)
          {
            $totamount = '0.00';
          }
          else
          {
            $totamount = number_format($total,2,'.','');
          }
          
          $response_data = array('html_data'=>$html_data, 'total_amount'=>$totamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
          $dialysis_particular_payment_array = array('total_amount'=>$totamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
          $this->session->set_userdata('dialysis_particular_payment', $dialysis_particular_payment_array);
          $json = json_encode($response_data,true);
          echo $json;
            
       }
    }
    
    
    private function _validatecharges()
    {
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
        $ipd_particular_billing_list = $this->session->userdata('dialysis_particular_charge_billing');
        if(!isset($ipd_particular_billing_list) && empty($ipd_particular_billing_list) && empty($post['data_id']))
        {
          $this->form_validation->set_rules('particular_id', 'particular_id', 'trim|callback_check_dialysis_particular_id');
        }

        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        
                                       );

                                         
            return $data['form_data'];
        }   
    }
    
    public function check_dialysis_particular_id()
    {
       $dialysis_particular_charge_billing = $this->session->userdata('dialysis_particular_charge_billing');
       if(isset($dialysis_particular_charge_billing) && !empty($dialysis_particular_charge_billing))
       {
          return true;
       }
       else
       {
          $this->form_validation->set_message('check_dialysis_particular_id', 'Please select a particular.');
          return false;
       }
    }
    
    private function dialysis_perticuller_list()
    {
        $particular_data = $this->session->userdata('dialysis_particular_charge_billing');
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
                     <input name="selectall" class="" id="selectAll" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Particular</th>
                    <th>Doctor</th>
                     <th>Date</th>
                    <th>Quantity</th>
                    <th>Charges</th>
                    <th>Amount</th>
                  </tr></thead>';  
           if(isset($particular_data) && !empty($particular_data))
           {
              
              $i = 1;
              foreach($particular_data as $particulardata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="particular_id[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$particulardata['particulars'].'</td>
                            <td>'.$particulardata['doctor'].'</td>
                            <td>'.$particulardata['s_date'].'</td>
                            <td>'.$particulardata['quantity'].'</td>
                            <td>'.$particulardata['charges'].'</td>
                            <td>'.$particulardata['amount'].'</td>
                            
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


    public function remove_dialysis_particular()
    {
       $post =  $this->input->post();
       
       if(isset($post['particular_id']) && !empty($post['particular_id']))
       {
           $dialysis_particular_charge_billing = $this->session->userdata('dialysis_particular_charge_billing');
           //echo "<pre>"; print_r($dialysis_particular_charge_billing); exit; 
           $ipd_particular_payment = $this->session->userdata('dialysis_particular_payment'); 
           
           $particular_id_list = array_column($dialysis_particular_charge_billing, 'charge_id'); 
           //echo "<pre>";print_r($dialysis_particular_charge_billing); exit;
           foreach($dialysis_particular_charge_billing as $key=>$perticuller_ids)
           { 
             // echo "<pre>";print_r($perticuller_ids['charge_id']); 
              // change name for delete $perticuller_ids['charge_id']//  old particular
              if(in_array($perticuller_ids['charge_id'],$post['particular_id']))
              {  
                //echo "<pre>"; print_r($perticuller_ids); exit;
                 unset($dialysis_particular_charge_billing[$key]);
                 //echo $ipd_particular_payment['particulars_charges'];die;
                 $this->session->unset_userdata('ipd_particular_payment');
                
              }
           }  
     
       
        $amount_arr = array_column($dialysis_particular_charge_billing, 'amount'); 
        $total_amount = array_sum($amount_arr);
        $this->session->set_userdata('dialysis_particular_charge_billing',$dialysis_particular_charge_billing);
        $html_data = $this->dialysis_perticuller_list();
        $particulars_charges = $total_amount;
        $bill_total = $total_amount;
        $response_data = array('html_data'=>$html_data,'particulars_charges'=>$particulars_charges,'total_amount'=>$bill_total);
        $json = json_encode($response_data,true);
        echo $json;
       }
    }
    
    public function calculate_hrs()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
            $start_date = $post['start_date'];
            $end_date = $post['end_date'];

             $start_time = $post['start_time'];
            $end_time = $post['end_time'];

            $start_date_time = date('d-m-Y',strtotime($post['start_date'])).' '.date('H:i:s',strtotime($post['start_time']));
            $end_date_time = date('d-m-Y',strtotime($post['end_date'])).' '.date('H:i:s',strtotime($post['end_time']));

            $day1 = strtotime($start_date_time);
            $day2 = strtotime($end_date_time);
            $diffHours = round(($day2 - $day1) / 3600);
            $pay_arr = array('quantity'=>$diffHours);
            $json = json_encode($pay_arr,true);
            echo $json;
       }
    }
    
    
    public function prescription($booking_id="")
    {
        $data['prescription_tab_setting'] = get_dialysis_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_tab_setting();

        $this->load->model('general/general_model'); 
        
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $post = $this->input->post();
        $patient_id = "";
        $patient_code = "";
        $simulation_id = "";
        $patient_name = "";
        $mobile_no = "";
        $gender = "";
        $age_y = "";
        $age_m = "";
        $age_d = "";
        $address = "";
        $city_id = "";
        $state_id = "";
        $date=date('d-m-Y');
        $country_id = ""; 
      
        if($booking_id>0)
        {
           $this->load->model('opd/opd_model','opd');
           $this->load->model('patient/patient_model');
           $opd_booking_data = $this->dialysisbooking->get_dialysis_by_id($booking_id);
           //echo "<pre>";print_r($opd_booking_data); exit;
           if(!empty($opd_booking_data))
           {
               
              $age_y = $opd_booking_data['age_y'];
              $age_m = $opd_booking_data['age_m'];
              $age_d = $opd_booking_data['age_d'];
              $booking_id = $opd_booking_data['id'];
              $referral_doctor = $opd_booking_data['referral_doctor'];
              $booking_code = $opd_booking_data['booking_code'];
              $patient_id = $opd_booking_data['patient_id'];//
              $simulation_id = $opd_booking_data['simulation_id'];
              $patient_code = $opd_booking_data['patient_code'];
              $patient_name = $opd_booking_data['patient_name'];
              $mobile_no = $opd_booking_data['mobile_no'];
              $gender = $opd_booking_data['gender'];
              /*$age_y = $opd_booking_data['age_y'];
              $age_m = $opd_booking_data['age_m'];
              $age_d = $opd_booking_data['age_d'];*/
              $address = $opd_booking_data['address'];
              $city_id = $opd_booking_data['city_id'];
              $state_id = $opd_booking_data['state_id'];
              $country_id = $opd_booking_data['country_id']; 
              //$appointment_date = $opd_booking_data['appointment_date'];
              
              $relation_name = $opd_booking_data['relation_name'];
              $relation_type = $opd_booking_data['relation_type'];
              $relation_simulation_id = $opd_booking_data['relation_simulation_id'];
              
              $aadhaar_no ='';
              if(!empty($opd_booking_data['adhar_no']))
              {
                $aadhaar_no = $opd_booking_data['adhar_no'];
              }
              /*$patient_bp = $opd_booking_data['patient_bp'];
              $patient_temp = $opd_booking_data['patient_temp'];
              $patient_weight = $opd_booking_data['patient_weight'];
              $patient_height = $opd_booking_data['patient_height'];
              $patient_spo2 = $opd_booking_data['patient_spo2'];
              $patient_rbs = $opd_booking_data['patient_rbs'];*/
              $patient_bp='';
              $patient_temp='';
              $patient_weight='';
              $patient_height='';
              $patient_spo2='';
              $patient_rbs='';
          }


        }
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['chief_complaints'] = $this->general_model->chief_complaint_list(); 
        $data['examination_list'] = $this->opd->examinations_list();
        $data['diagnosis_list'] = $this->opd->diagnosis_list();  
        $data['suggetion_list'] = $this->opd->suggetion_list();  
        $data['prv_history'] = $this->opd->prv_history_list();  
        $data['personal_history'] = $this->opd->personal_history_list();
        $data['template_list'] = $this->dialysisbooking->template_list();    
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['page_title'] = "Prescription";
                
        $post = $this->input->post();
       
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'booking_id'=>$booking_id,
                                  'attended_doctor'=>'',
                                  'appointment_date'=>'',
                                  'patient_code'=>$patient_code,
                                  'booking_code'=>$booking_code,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  "aadhaar_no"=>$aadhaar_no,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'dept_id'=>"",
                                  'address'=>$address,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'patient_bp'=>$patient_bp,
                                  'patient_temp'=>$patient_temp,
                                  'patient_weight'=>$patient_weight,
                                  'patient_spo2'=>$patient_spo2,
                                  'patient_height'=>$patient_height,
                                  'patient_rbs'=>$patient_rbs,
                                  'prv_history'=>"",
                                  'personal_history'=>"",
                                  'chief_complaints'=>'',
                                  'examination'=>'',
                                  'diagnosis'=>'',
                                  'suggestion'=>'',
                                  'remark'=>'',
                                  'next_appointment_date'=>"",
                                   '$next_appointmenttime'=>"",
                                  //$next_appointmenttime = date('H:i A',strtotime($prescription_data->next_appointment_date));
                                  "relation_name"=>$relation_name,
                                    "relation_type"=>$relation_type,
                                    "relation_simulation_id"=>$relation_simulation_id,
                                    'next_appointment_time'=>'',
                                    'prescription_date'=>$date,
                                    
                                  );
        if(isset($post) && !empty($post))
        {   
          
          $prescription_id = $this->dialysisbooking->save_prescription();
          $this->session->set_userdata('dialysis_prescription_id',$prescription_id);
          $this->session->set_flashdata('success','Prescription successfully added.');
          redirect(base_url('dialysis_prescription/?status=print'));


        }   

         $this->load->view('dialysis_booking/prescription',$data);
    }

    
    function get_template_data($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->dialysisbooking->template_data($template_id);
        echo $templatedata;
      }
    }



    function get_template_test_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->dialysisbooking->get_template_test_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_template_prescription_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->dialysisbooking->get_template_prescription_data($template_id);
        
        echo $templatetestdata;
      }
    }
    

   function complaints_name($complaints_id="")
   {
      $this->load->model('opd/opd_model','opd');
      if($complaints_id>0)
      {
         $complaintsname = $this->opd->compalaints_list($complaints_id);
         echo $complaintsname;
      }
    }

   

    function examination_name($examination_id="")
    {
        $this->load->model('opd/opd_model','opd');
      if($examination_id>0)
      {
         $examination_name = $this->opd->examination_list($examination_id);
         echo $examination_name;
      }
    }

    function diagnosis_name($diagnosis_id="")
    {
        $this->load->model('opd/opd_model','opd');
      if($diagnosis_id>0)
      {
         $diagnosis_name = $this->opd->diagnosis_name($diagnosis_id);
         echo $diagnosis_name;
      }
    }
    //hms_opd_suggetion
    function suggetion_name($suggetion_id="")
    {
        $this->load->model('opd/opd_model','opd');
      if($suggetion_id>0)
      {
         $suggetion_name = $this->opd->suggetion_name($suggetion_id);
         echo $suggetion_name;
      }
    }

    function personal_history_name($personal_his_id="")
    {
        $this->load->model('opd/opd_model','opd');
      if($personal_his_id>0)
      {
         $personal_his_name = $this->opd->personal_history_name($personal_his_id);
         echo $personal_his_name;
      }
    }

    function prv_history_name($pre_id="")
    {
        $this->load->model('opd/opd_model','opd');
      if($pre_id>0)
      {
         $pre_name = $this->opd->prv_history_name($pre_id);
         echo $pre_name;
      }
    }
    
    
    function get_test_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $this->load->model('opd/opd_model','opd');
            $result = $this->opd->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_medicine_auto_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $this->load->model('opd/opd_model','opd');
            $result = $this->opd->get_medicine_auto_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_dosage_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $this->load->model('opd/opd_model','opd');
            $result = $this->opd->get_dosage_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_type_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $this->load->model('opd/opd_model','opd');
            $result = $this->opd->get_type_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    

    function get_duration_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $this->load->model('opd/opd_model','opd');
            $result = $this->opd->get_duration_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_frequency_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $this->load->model('opd/opd_model','opd');
            $result = $this->opd->get_frequency_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }  


    function get_advice_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $this->load->model('opd/opd_model','opd');
            $result = $this->opd->get_advice_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_diseases_data($branch_id="")
    {
      if($branch_id>0)
      {
          $this->load->model('opd/opd_model','opd');
        $diseases_data = $this->opd->get_diseases_data($branch_id);
        echo $diseases_data;
      }
    }
    
    
    public function procedure_bill($dialysis_id="",$patient_id="")
    {
          $post= $this->input->post();
          $data['page_title'] = 'Bill List';
          $data['dialysis_id'] = $dialysis_id;
          
          $this->load->model('dialysis_booking/dialysis_booking_model','booking_model');

          $ipd_details = $this->booking_model->get_by_id($data['dialysis_id']);
          if(!empty($ipd_details['procedure_bill_no']))
          {
             $bill_no = $ipd_details['procedure_bill_no'];
          }
          else
          {
            $bill_no = generate_unique_id(75);
          }
          
          if(!empty($ipd_details['procedure_remarks']))
          {
             $procedure_remarks = $ipd_details['procedure_remarks'];
          }
          else
          {
            $procedure_remarks = '';
          }


          $data['patient_id'] = $patient_id;
          $list = $this->dialysisbooking->get_discharge_bill_info_datatables($data['dialysis_id'],$data['patient_id']);
          //echo "<pre>"; print_r($list); exit;
          $this->load->model('general/general_model');
           $data['payment_mode']=$this->general_model->payment_mode();
           
           
            $get_payment_detail= $this->general_model->payment_ipd_mode_detail_according_to_field($ipd_details['procedure_payment_mode'],$dialysis_id,9,9);
          $total_values=array();
          for($i=0;$i<count($get_payment_detail);$i++) {
          $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

          }
          
          
          $payamount=$this->dialysisbooking->get_paid_amount($data['dialysis_id'],$data['patient_id']);
          if($payamount[0]->advance_payment_dis_bill>$payamount[0]->total_amount_dis_bill){
            //$data['get_paid_amount']=$payamount[0]->refund_amount_dis_bill;
            $data['get_paid_amount']='0.00';
          }else{
            $data['get_paid_amount']=$payamount[0]->paid_amount_dis_bill;
          }
          if($payamount[0]->discount_amount_dis_bill>0)
          {
              $data['total_discount'] = $payamount[0]->discount_amount_dis_bill;
          }
          else
          {
              $data['total_discount'] = '0.00';
          }
          $data['running_bill_data']=$list;

         /* if(!empty($payamount[0]->discharge_date) &&  $payamount[0]->discharge_date!='0000-00-00 00:00:00' && date('Y-m-d',strtotime($payamount[0]->discharge_date))!='1970-01-01')
          {
              $dischargedate = date('d-m-Y h:i A',strtotime($payamount[0]->discharge_date));
          }   
          else
          {
              //$dischargedate = date('d-m-Y h:i A'); 
              $dischargedate = date('d-m-Y h:i A',$discharge_date); 
          }*/
          
          if(!empty($ipd_details['refund_payment_mode']))
          {
            $payment_re = $ipd_details['refund_payment_mode'];
          }
          else
          {
            $payment_re ='';
          }
          
          $data['form_data']= array( //'discharge_date'=>$dischargedate,
                                    'particular_date'=>date('d-m-Y'),
                                    'next_app_date'=>'',
                                    'procedure_bill_no'=>$bill_no,
                                    "payment_mode"=>$ipd_details['procedure_payment_mode'],
                                    "amount"=>'',
                                    'field_name'=>$total_values,
                                    'cheque_date'=>date('d-m-Y'),
                                    "refund_payment_mode"=>$payment_re,
                                    'dialysis_remarks'=>$procedure_remarks,
                                    );

            if(isset($post) && !empty($post))
            { 
                $data['form_data'] = $this->_validateprocedure();
                if($this->form_validation->run() == TRUE)
                {

                    $procedure_bill_id= $this->dialysisbooking->save_procedure_bill();
                   
                  
              if(!empty($procedure_bill_id))
              {
                  $get_ipd_patient_details= $this->dialysisbooking->get_patient_according_to_dialysis($dialysis_id,$patient_id);
                  $get_by_id_data=$this->dialysisbooking->get_discharge_bill_info_datatables($dialysis_id,$patient_id);
                  $get_updated_data= $this->dialysisbooking->dialysis_detail_data($dialysis_id,$patient_id);
                 
                  $patient_name = $get_ipd_patient_details['patient_name'];
                  $booking_code = $get_updated_data[0]->booking_code;
                  $paid_amount = $get_by_id_data['advance_deposite'];
                  $mobile_no = $get_ipd_patient_details['mobile_no'];
                  
                  $patient_email = $get_ipd_patient_details['patient_email'];
                  $procedure_bill_no = $get_ipd_patient_details['procedure_bill_no'];
                  $amount = $get_updated_data[0]->total_amount_dis_bill;
                //check permission 
                  //exit; procedure_remarks
                if(in_array('640',$users_data['permission']['action']))
                {
                    if(!empty($mobile_no))
                    {
                      send_sms('discharge_bill',17,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{BillNo}'=>$procedure_bill_no,'{IPDNo}'=>$booking_code,'{Amt}'=>$amount));  
                    }
                    
                }

                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($patient_email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($patient_email,'','','','','1','discharge_bill','17',array('{Name}'=>$patient_name,'{BillNo}'=>$procedure_bill_no,'{IPDNo}'=>$booking_code,'{Amt}'=>$amount));
                     
                  }
                } 
              }

                    $this->session->set_userdata('procedure_bill_id',$procedure_bill_id);
                    $this->session->set_flashdata('success','Dialysis Billing has been successfully updated.');
                   
                    redirect(base_url('dialysis_booking/procedure_bill/'.$dialysis_id.'/'.$patient_id.'/?status=print'));
            //&billstatus=printbill
                }
                else
                {

                    $data['form_error'] = validation_errors();
                    //echo "<pre>"; print_r($data['form_error']); die;

                }  
            }
         $data['doctor_list'] = $this->general_model->attended_doctor_list();
         $users_data = $this->session->userdata('auth_users');
         
          $this->load->view('dialysis_booking/procedure_bill',$data);
          
     }
     
    private function _validateprocedure()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
       
        
          $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required');
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
                                        //'discharge_date'=>$_POST['discharge_date'],
                                        //'next_app_date'=>$_POST['next_app_date'],
                                        'particular_date'=>$_POST['date'],
                                        'procedure_bill_no'=>$_POST['procedure_bill_no'],
                                        "payment_mode"=>$post['payment_mode'],
                                        'field_name'=>$total_values,
                                        'refund_payment_mode'=>$_POST['refund_payment_mode'],
                                        'dialysis_remarks'=>$post['dialysis_remarks'],
                                       ); 
            return $data['form_data'];
        }   
    }
     
    function get_particular_data($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->dialysisbooking->get_particular_data($vals);  
        if(!empty($result))
        {
            echo json_encode($result,true);
        } 
        } 
    }
    
    function add_perticulars($dialysis_id="",$patient_id="")
    {
     $post= $this->input->post();
     if(!empty($post['particular']))
     {
        $this->dialysisbooking->save_particulars($dialysis_id,$patient_id);
        $this->session->set_flashdata('success','Particular has been successfully added.');
        $data=array('success'=>1,'dialysis_id'=>$dialysis_id,'patient_id'=>$patient_id);
        echo json_encode($data);
        return false;
         //echo '1';exit;
     }
    }
    
    public function update_procedure_date()
    {
       $post= $this->input->post();
       $result= $this->dialysisbooking->update_procedure_date();
       $this->session->set_flashdata('success','Particular has been successfully updated');
       echo '1';
       exit;
    }
    
    
     public function payment_calc_all()
    { 
       
        $post = $this->input->post();
        //echo "<pre>"; print_r($post); die;
        $total_amount = 0;
        $total_vat = 0;
        $total_discount =0;
        $net_amount =0;  
        $purchase_amount=0;
        $total_new_amount=0;
        $tot_discount_amount=0;
        $total_advance_amount=0;
        $blance_dues=0;
        $payamount=0;
        $refund_amount=0;
        $new_amount=0;
        $received_amount=0;
        $total_bill_amount=0;
        $i=0;
        if($post['update_row']==1)
        {
              $list = $this->dialysisbooking->get_discharge_bill_info_datatables($post['dialysis_id'],$post['patient_id']);
              //print '<pre>'; print_r($list);die;
              foreach($list['CHARGES'] as $patient_ipd_list)
              {
                  $received_amount= $received_amount+$patient_ipd_list['net_price'];
                  //$total_amount+=($patient_ipd_list['net_price']*$patient_ipd_list['quantity']);
                  $total_amount+=($patient_ipd_list['price']*$patient_ipd_list['quantity']);

              }
              
             
              
              $total_discount=0;
              $net_amount = ($total_amount-$total_discount);
              
              foreach($list['advance_payment'] as $patient_ipd_list)
              {
                  $net_advance_data[] = $patient_ipd_list->net_price;
              }

              $advance_payment=$net_advance_data[0];
              if($net_amount+$payamount>$advance_payment)
              {
               $blance_dues=($net_amount+$payamount)-$advance_payment;   
              }
              else
              {
                $blance_dues='0.00';  
              }
              
              if($net_amount+$payamount<$advance_payment)
              {
                 $refund_amount= $advance_payment-($net_amount+$payamount); 
              }
              else
              {
                 $refund_amount= '0.00'; 
              }
              


         
        }
        else
        {
        
            if($post['discount']!='' && $post['discount']!=0)
            {
               $total_discount =$post['discount'];
            }
            else
            { 
               $total_discount=0;
            }
           
    
    
            $total_amount = $post['total_amount'];
            
            
            $total_net_amount = $post['total_amount']-$total_discount;
            $net_amount = $total_net_amount;
            if($post['total_advance_amount'] >$total_amount)
            {
               
              $payamount=$post['pay_amount'];
              //$refund_amount= $post['total_refund_amount'];
              $new_amount= $net_amount+$post['total_refund_amount'];
              $advance_payment = floatval(preg_replace('/[^\d.]/', '', number_format($post['total_advance_amount'])));
              
              if(($net_amount+$payamount)>$advance_payment)
                {
                  $blance_dues_amount=($net_amount+$payamount)-$advance_payment;
                  $refund_amount='0.00';
                  if($blance_dues_amount>0)
                  {
                      $blance_dues=$blance_dues_amount;
                  }
                  else
                  {
                     $blance_dues='0.00'; 
                  }
                  
                }
                else
                {
                  $balance_due ='0.00';
                  $refund_amount_total= $advance_payment-($net_amount+$payamount);
                  if($refund_amount_total>0)
                  {
                       $refund_amount=$refund_amount_total;
                  }
                  else
                  {
                     $refund_amount='0.00'; 
                  }
                }
                
    
            }
            else
            {
    
              $refund_amount=0;
              $payamount= $post['pay_amount'];
              $blance_dues=$post['total_amount']-$post['total_advance_amount']-$payamount-$total_discount;
              
              if($blance_dues<0)
              {
                    $refund_amount = abs($blance_dues);  
                    $blance_dues = 0;
              }
              
            }
       }
        
        $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''),'total_amount_bill'=>number_format($total_amount,2,'.',''),'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'balance_due'=>number_format($blance_dues,2,'.',''),'discount'=>$post['discount'],'total_advance_amount'=>number_format($post['total_advance_amount'],2,'.',''),'refund_amount'=>number_format($refund_amount,2,'.',''),'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       
        
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
    
    
     public function delete_procedure_charges($id="",$dialysis_id="",$patient_id="")
    {
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysisbooking->delete_charges($id,$dialysis_id,$patient_id);
           $this->session->set_flashdata('success','Particular has been successfully deleted');
           $data=array('success'=>1,'dialysis_id'=>$dialysis_id,'patient_id'=>$patient_id);
            //print_r($data); exit;
            echo json_encode($data);
            return false;
        
       }
    }
    
    public function deleteAllprocedure_charges()
    {
      $post=$this->input->post();
       if(!empty($post))
       {
           $result = $this->dialysisbooking->deleteAllprocedure_charges($post['row_id'],$post['dialysis_id'],$post['patient_id']);
           $this->session->set_flashdata('success','Charges has been successfully deleted');
           echo '1';
           exit;
       }
    }
    
    public function print_procedure_bill($dialysis_id="",$patient_id="")
    {

        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $data['get_ipd_patient_details']= $this->dialysisbooking->get_patient_according_to_dialysis($dialysis_id,$patient_id,9,9);
        //echo "<pre>";print_r($data['get_ipd_patient_details']);die;
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->dialysisbooking->get_discharge_bill_info_datatables($dialysis_id,$patient_id);
        $get_updated_data= $this->dialysisbooking->dialysis_detail_data($dialysis_id,$patient_id);
        //print '<pre>'; print_r($data['get_ipd_patient_details']);die;
        //print '<pre>'; print_r($data['get_ipd_patient_details']);die;
       $users_data = $this->session->userdata('auth_users'); 
        $template_format= $this->dialysisbooking->procedure_template_format(array('branch_id'=>$users_data['parent_id']));
    // $template_format= $this->ipd_discharge_bill->template_format(array());
        //print_r($template_format);
        $data['template_data']=$template_format;
        
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        //print_r($data['all_detail']['CHARGES']);die;
        
        $paid_amt = $this->dialysisbooking->get_all_payment($dialysis_id,$patient_id);
        $data['total_paid_amount'] ='0';
        if(!empty($paid_amt))
        {
          $data['total_paid_amount'] = $paid_amt->paid_amount;
        }
        
        $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $data['address_setting_list'] = $this->address_setting->get_master_unique();
        $this->load->view('dialysis_booking/print_template_procedure_bill',$data);
    }

    
    public function next_dialysis($dialysis_id="",$patient_id)
    {
      
        $this->load->model('general/general_model');
        $data['page_title']="Next Dialysis";
        $data['patient_details']= $this->general_model->get_patient_according_to_dialysis($dialysis_id,$patient_id);
        $data['room_category']= $this->general_model->check_dialysis_room_type();
        $post= $this->input->post();
        $data['form_data'] = array(
          'data_id'=>"", 
          'dialysis_id'=>$dialysis_id,
          "patient_id"=>$patient_id,
          "room_id"=>'',
          "room_no_id"=>'',
          'bed_no_id'=>'',
          'transfer_date'=>date('d-m-Y'),
          "transfer_time"=>date('H:i:s')
        );    
    
        if(isset($post) && !empty($post))
        { 
                  $data['form_data'] = $this->_validate_readmit();
                  if($this->form_validation->run() == TRUE)
                  {
                    $this->dialysisbooking->save_next_dialysis();
                    $this->session->set_flashdata('success','Patient next dialysis schedule successfully.');
                    redirect(base_url('dialysis_booking/')); // /?status=print
                    
                  }
                  else
                  {
                    $data['form_error'] = validation_errors();  
                  }  
                }
            $this->load->view('dialysis_booking/next_dialysis',$data); 
                
          
        }


        private function _validate_readmit()
        {

          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('room_id', 'room type', 'trim|required'); 
          $this->form_validation->set_rules('room_no_id', 'room no', 'trim|required'); 
          $this->form_validation->set_rules('bed_no_id', 'bed no', 'trim|required'); 
          $this->form_validation->set_rules('transfer_date', 're-admit date', 'trim|required'); 
          $this->form_validation->set_rules('transfer_time', 're-admit time', 'trim|required'); 
          
          if ($this->form_validation->run() == FALSE) 
          {  
            $data['form_data'] = array(
              'data_id'=>"", 
              'ipd_id'=>$post['ipd_id'],
              "patient_id"=>$post['patient_id'],
              "room_id"=>$post['room_id'],
              "room_no_id"=>$post['room_no_id'],
              'bed_no_id'=>$post['bed_no_id'],
                                      //'card_no'=>'',
              'transfer_date'=>date('d-m-Y',strtotime($post['transfer_date'])),
              "transfer_time"=>date('H:i:s',strtotime($post['transfer_time']))
            ); 
            return $data['form_data'];
          }   
        }
        
        
    public function view_history($dialysis_id,$patient_id)
    { 
      $data['page_title']="Dialysis History";
      $data['history_list'] = $this->dialysisbooking->get_dialysis_history_data($dialysis_id,$patient_id);
      
      $data['dialysis_id'] = $dialysis_id;
      $data['patient_id'] = $patient_id;
      $this->load->view('dialysis_booking/dialysis_history',$data); 
    }
    
    
    public function dialysis_history_excel($dialysis_id,$patient_id)
    {
      
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $data_reg_name= get_setting_value('PATIENT_REG_NO');
          $fields = array($data_reg_name,'Booking No.','Dialysis Name','Patient Name','Dialysis Date','Dialysis Time','Room Type','Room No','Bed No.');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           
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
               
               $col++;
          }
          $list = $this->dialysisbooking->get_dialysis_history_data($dialysis_id,$patient_id);
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                
                
                    array_push($rowData,$reports->patient_code,$reports->booking_code,$reports->dialysiss_name,$reports->patient_name, date('d-m-Y',strtotime($reports->dialysis_date)),date('h:i A',strtotime($reports->dialysis_time)),$reports->room_type,$reports->room_no,$reports->bad_no);
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
          header("Content-Disposition: attachment; filename=dialysis_booking_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }


    }
    
    public function pdf_dialysis_history_list($dialysis_id,$patient_id)
    {    
        $data['print_status']="";
        $data['data_list'] = $this->dialysisbooking->get_dialysis_history_data($dialysis_id,$patient_id);
        $this->load->view('dialysis_booking/dialysis_history_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("dialysis_booking_report_".time().".pdf");
    }
    public function print_dialysis_history_list($dialysis_id,$patient_id)
    { 
      $data['print_status']="1";
      $data['data_list'] = $this->dialysisbooking->get_dialysis_history_data($dialysis_id,$patient_id);
      $this->load->view('dialysis_booking/dialysis_history_report_html',$data); 
    }
    
    public function add_inventory($booking_id="",$patient_id="")
    {

        if(!empty($patient_id) && !empty($booking_id)) 
        { 
            $data['patient_id'] = $patient_id;
            $data['booking_id'] = $booking_id;

            $data['item_list']= $this->dialysisbooking->item_list($test_id, $patient_id); 

             $post = $this->input->post();
             $inventory_data = $this->session->userdata('inventory_item_ids');
             if(!empty($inventory_data))
             {
                $inventory_data = array();
                $this->session->set_userdata('inventory_item_ids',$inventory_data);
             }
        
           // if(isset($post) && !empty($post))
            //{
            $inventory_data = array();
            $i=0;
            foreach ($data['item_list'] as $medicine_id) {

             

              $inventory_data[$i]['item_id'] = $medicine_id->item_id;
              $inventory_data[$i]['item_name'] =  $medicine_id->item;
              $inventory_data[$i]['unit_value'] = $medicine_id->unit_id;

            $i++;
            }
            
          // print '<pre>';print_r($inventory_data);die;

               $this->session->set_userdata('inventory_item_ids', $inventory_data);
             
        // } 
        
         $data['page_title'] = 'Items in Inventory';
         
        
        

         $row='';
          $r = 0;
          $i=1;
         $total_num = count($data['item_list']);
         if(!empty($data['item_list']))
         {
              // echo "<pre>"; print_r($data['item_list']);die;
               foreach($data['item_list'] as $item)
               {
                        $get_unit_according_item= get_units_by_item($item->item_id);
                        $get_unit_by_unit_id= get_units_by_id($item->unit_id);

                         
                        
                         $option_name='';
                            $option_name='<select  name="unit_value['.$r.']" id="unit_dropdown_'.$r.'"><option value="">Select Unit</option>';
                            if(!empty($get_unit_according_item[0])) 
                            { 
                            foreach($get_unit_according_item[0] as $get_unit)
                            {

                            if(!empty($get_unit))
                            {
                            
                                if(!empty($item->booking_status) && $item->booking_unit>0)
                                {
                                   if($item->booking_unit==$get_unit['id'])
                                   {
                                      $selected='selected="selected"';
                                   }
                                   else
                                   {
                                       $selected='';
                                   }
                                  
                                }
                                else if(empty($item->booking_status) && $get_unit['id']==$get_unit_by_unit_id[0]->id)
                                {
                                $selected='selected="selected"';
                                } 
                                else
                                {
                                  $selected='';
                                }


                            $option_name.='<option value="'.$get_unit['id'].'" '.$selected.'>'.$get_unit['first_name'].'</option>';
                            } } }

                            $option_name.='</select>';

                            $qty = $item->inventory_qty;
                            if(!empty($item->booking_status) && $item->booking_status>0)
                            {
                               $qty = $item->booking_qty;
                            } 

                            $row.= '<tr><td align="center">'.$i.'</td><td> <input type="text" name="item_name['.$r.']" value="'.$item->item.'" class="" id="item_name_'.$r.'" readonly/><input type="hidden" name="item_id['.$r.']" value="'.$item->item_id.'" class="" id="item_id_'.$r.'" /></td><td><input type="text" name="quantity['.$r.']" value="'.$qty.'" id="quantity_'.$r.'" onkeyup="check_stock_quantity('.$item->item_id.');"/><div  id="stock_error_'.$item->item_id.'" style="color:red;">
                                   </div>
                            </td> <td>'.$option_name.'</td></tr>';
                    $i++;

                    $r++;
               }
          }else{
               $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
          }
          $data['item_inventory_list'] = $row;
       

        $data['int_i'] = 0;
         $this->load->view('dialysis_booking/add_inventory',$data);
        }
      }

    function check_stock_quantity()
    {
      $this->load->model('item_manage/item_manage_model','item_manage'); 
      $item_id= $this->input->post('item_id');
      if(!empty($item_id))
      {
         $return= $this->item_manage->get_item_quantity($item_id);
         if($return['total_qty']>0)
        {
          echo '0';exit;
        }
        else
        {
          echo '1';exit;
        }
      }

    }
    
    public function add_inventory_item_to_booking()
    {
         // unauthorise_permission('165','953');
          $post = $this->input->post();
          //echo "<pre>"; print_r($post); exit;
          $data['patient_id']= $post['patient_id'];
          $data['booking_id']= $post['booking_id'];
          $users_data = $this->session->userdata('auth_users');
          $data['page_title'] = 'Items in Inventory';
          $data['item_list'] = '';
          if(isset($post) && !empty($post))
          {
              

                      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                      $inventory_item_ids = $this->session->userdata('inventory_item_ids');

                      $this->form_validation->set_rules('item_id', 'item_id', 'trim');
                      //|callback_check_stock_purchase_item_id
      
                     if($this->form_validation->run() == TRUE)
                     {
                            
                          
                            $this->dialysisbooking->insert_invntory();
                            echo 1;
                            return false;
                     }
                    
                    else
                    {
                        //echo "dsd"; die;
                        
                         $data['item_list']= $this->dialysisbooking->item_list($data['test_id'],$data['booking_id']); 
                         $row='';
                         $i=0;
                         $r=0;
                         $total_num = count($data['item_list']);
                         if(!empty($data['item_list']))
                         {
                            
                          foreach($data['item_list'] as $item)
                          {

                              $get_unit_according_item= get_units_by_item($item->item_id);
                              $get_unit_by_unit_id= get_units_by_id($item->unit_id);
                              if(!empty($item->booking_unit) && $item->booking_unit>0)
                              {
                              $get_unit_by_unit_id= get_units_by_id($item->booking_unit);
                              }

                             $option_name='';
                              $option_name='<select  name="unit_value['.$r.']" id="unit_dropdown_'.$r.'"><option value="">Select Unit</option>';
                               if(!empty($get_unit_according_item[0])) 
                                  { 
                                 foreach($get_unit_according_item[0] as $get_unit)
                                   {

                                    if(!empty($get_unit))
                                    {
                                        if(!empty($item->booking_status) && $item->booking_unit>0)
                                        {
                                          if($item->booking_unit==$get_unit['id'])
                                          {
                                          $selected='selected="selected"';
                                          }
                                          else
                                          {
                                          $selected='';
                                          }

                                        }
                                        else if(empty($item->booking_status) && $get_unit['id']==$get_unit_by_unit_id[0]->id)
                                        {
                                        $selected='selected="selected"';
                                        } 
                                        else
                                        {
                                        $selected='';
                                        } 

                                        $option_name.='<option value="'.$get_unit['id'].'" '.$selected.'>'.$get_unit['first_name'].'</option>';
                                      } 
                                    } 
                                  }

                                  $option_name.='</select>';

                                  $qty = $item->inventory_qty;
                                  if(!empty($item->booking_status) && $item->booking_status>0)
                                  {
                                  $qty = $item->booking_qty;
                                  } 

                                  $row.= '<tr><td align="center">'.$i.'</td><td> <input type="text" name="item_name['.$r.']" value="'.$item->item.'" class="" id="item_name_'.$r.'" readonly/><input type="hidden" name="item_id['.$r.']" value="'.$item->item_id.'" class="" id="item_id_'.$r.'" /></td><td><input type="text" name="quantity['.$r.']" value="'.$qty.'" id="quantity_'.$r.'" onkeyup="check_stock_quantity('.$item->item_id.');"/>
                                   <div  id="stock_error_'.$item->item_id.'" style="color:red;">
                                   </div></td><td>'.$option_name.'</td>
                                   </tr>';
                                $i++;

                                  $r++;
                          }    
                         }
                         else{
                              $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
                         }

                         $data['item_inventory_list'] = $row;
                         $data['form_error'] = validation_errors(); 
                         //echo "<pre>"; print_r($data['form_error']); exit;
                      
                    }

               
          }
          
         $this->load->view('dialysis_booking/add_inventory',$data);
     }
     
     
    public function view_inventory($dialysis_id,$patient_id)
    { 
      $data['page_title']="Dialysis History";
      $inv_history_list = $this->dialysisbooking->get_dialysis_inventory_data($dialysis_id,$patient_id);
      //echo "<pre>";print_r($inv_history_list); die;
      $data['dialysis_id'] = $dialysis_id;
      $data['patient_id'] = $patient_id;
      $data['history_list'] = $inv_history_list;
      $this->load->view('dialysis_booking/view_inventory_history',$data); 
    }
    
    
    public function print_inventory($inv_id="",$branch_id='')
    {
         
        
        $data['page_title'] = "Print Inventory";
        $opd_prescription_info = $this->dialysisbooking->get_dialysis_inventory_data_by_id($inv_id);
        $data['all_detail']= $opd_prescription_info;
        
        $this->load->view('dialysis_booking/print_inventory_template',$data);
        
    }
    
  public function print_advance_payment_report($ids="")
    {
        $user_detail= $this->session->userdata('auth_users');

        $data['page_title'] = "Print Advance Payment";
        if(!empty($ids)){
        $advance_payment_id= $ids;
        }else{
        $advance_payment_id= $this->session->userdata('dialysis_booking_id');
        }
        //$get_detail_by_id= $this->dialysisbooking->get_advance_by_id($advance_payment_id);

        $get_by_id_data=$this->dialysisbooking->get_all_detail_print($advance_payment_id,$user_detail['parent_id']);
//echo "<pre>"; print_r($get_by_id_data); exit;
        $template_format= $this->dialysisbooking->template_format(array('section_id'=>18,'types'=>1,'branch_id'=>$user_detail['parent_id']));
        
        $re_number = $this->dialysisbooking->get_payment_reciept_no($ids);
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $data['re_number'] =$re_number;
        $this->load->view('dialysis_booking/print_template_advance_payment',$data);
 }
 
 
public function print_dialysis_adminssion($id="")
{
  $this->load->model('dialysis_admission_print_setting/dialysis_admission_print_setting_model','dialysis_admission_print');
  $data['page_title'] = "Print Bookings";
  $dialysis_booking_id= $this->session->userdata('dialysis_booking_id');
   $users_data = $this->session->userdata('auth_users');
  $branch_id = $users_data['parent_id'];
  if(!empty($id))
  {
    $dialysis_booking_id = $id;
  }
  else if(isset($dialysis_booking_id) && !empty($dialysis_booking_id))
  {
    $dialysis_booking_id =$dialysis_booking_id;
  }
  else
  {
    $dialysis_booking_id = '';
  } 
  $get_by_id_data = $this->dialysisbooking->get_all_detail_print($dialysis_booking_id);
  //echo "<pre>";print_r($get_by_id_data); exit;
  

  $template_format = $this->dialysis_admission_print->template_format(array('setting_name'=>'DIALYSIS_PRINT_SETTING','unique_id'=>1,'type'=>0),$branch_id);

  
  $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
  $data['template_data']=$template_format->setting_value;
  //print_r($data['template_data']);
  $data['all_detail']= $get_by_id_data;
  $data['page_type'] = 'Booking';
  $this->load->model('address_print_setting/address_print_setting_model','address_setting');
  $data['address_setting_list'] = $this->address_setting->get_master_unique();
  $this->load->model('time_print_setting/time_print_setting_model','time_setting');
  $data['time_setting'] = $this->time_setting->get_master_unique();
  
  $this->load->view('dialysis_booking/print_dialysis_admission_template',$data);
}
    
    
 
  
  

}
?>