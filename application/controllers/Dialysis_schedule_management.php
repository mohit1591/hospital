<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_schedule_management extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dialysis_schedule_management/dialysis_schedule_management_model','dialysis_schedule_list');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //unauthorise_permission('212','1191');
        $data['page_title'] = 'Dialysis Schedule Management List'; 
        $data['form_data'] = '';
        $this->load->view('dialysis_schedule_management/list',$data);
    }

    public function ajax_list()
    { 
        $users_data = $this->session->userdata('auth_users');
        $list = $this->dialysis_schedule_list->get_datatables();  
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        
        $total_num = count($list);
        foreach ($list as $dialysis) {
         // print_r($relation);die;
            $no++;
            $row = array();
        

            
            ////////// Check  List /////////////////
            $check_script = "";
            if($dialysis->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysis->id.'">'.$check_script; 
            $row[] = $dialysis->schedule_name;
            $row[] = $dialysis->per_patient_timing;
            $row[] = $status;
            
            
            $btnedit = ' <a onClick="return edit_doctors('.$dialysis->id.');" class="btn-custom" href="javascript:void(0)" style="'.$dialysis->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
               
            $btnview=' <a class="btn-custom" onclick="return view_doctors('.$dialysis->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
            $btndelete = ' <a class="btn-custom" onClick="return delete_doctors('.$dialysis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
                    
            
           $row[] = $btnedit.$btnview.$btndelete;
          
           
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dialysis_schedule_list->count_all(),
                        "recordsFiltered" => $this->dialysis_schedule_list->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
	{
        $this->load->model('general/general_model'); 
        $users_data = $this->session->userdata('auth_users');
		$data['page_title'] = "Add Dialysis Schedule";
		$data['form_error'] = [];
		$data['days_list'] = $this->general_model->get_days_list(); 
		$post = $this->input->post();
		$data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "schedule_name"=>"",
                                    'per_patient_timing'=>'',
                                    'status'=>1,

 			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
               $schedule_id = $this->dialysis_schedule_list->save(); 
               echo 1;
               return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        $this->load->view('dialysis_schedule_management/add',$data);
	}

	public function edit($id="")
    {
        // unauthorise_permission(20,123);
      if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->dialysis_schedule_list->get_by_id($id);
        $data['schedule_availablity'] = $result['schedule_availablity'];
        $array_result = array();
        if(!empty($result['schedule_availablity']))
        {
            
            foreach ($result['schedule_availablity'] as $value) 
            {   //print_r($value);
                $array_result[$value->available_day] = $value->day_name;    
                 //$array_result[$value->available_day] = $value->available_day;
            }
        }
        $data['available_day'] = $array_result;

        // $reg_no = generate_unique_id(3);
        $this->load->model('general/general_model');
        $data['days_list'] = $this->general_model->get_days_list();
        $data['page_title'] = "Update Schedule";  
        $post = $this->input->post();
        $data['form_error'] = '';
        
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "schedule_name"=>$result['schedule_name'],
                                    'per_patient_timing'=>$result['per_patient_timing'],
                                    "status"=>$result['status'],
                                    
                                   );
                                  
       
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dialysis_schedule_list->save();
                echo 1;
                return false;
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->load->view('dialysis_schedule_management/add',$data);       
      }
    }
    
    private function _validate()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $post = $this->input->post(); 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('schedule_name', 'schedule name', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
           $data['form_data'] = array(
										"data_id"=>$post['data_id'], 
										"schedule_name"=>$post['schedule_name'],
										'per_patient_timing'=>$post['per_patient_timing']


                                       );
                                
            return $data['form_data'];
        }   
    }

    public function dialysis_list_excel()
    {
      
         // unauthorise_permission('212','1205');
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $data_reg_name= get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array($data_reg_name,'Patient Name','Dialysis Booking No','Dialysis Room','Assign Doctors','Dialysis Booking Date');
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
          $list = $this->dialysis_schedule_list->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                $doctor_name=array();
                $doctor_list= $this->dialysis_schedule_list->doctor_list_by_otids($reports->id);
                foreach($doctor_list as $doctor_list=>$value){
                    $doctor_name[]=$value[0];
                    }
                     $name= implode(',',$doctor_name);
                      if($reports->dialysis_booking_date=='0000-00-00')
                     {
                      $date_time='';
                     }
                     else
                     {
                      $date_time= date('d-m-Y',strtotime($reports->dialysis_booking_date));
                     }
                    array_push($rowData,$reports->p_code,$reports->p_name,$reports->booking_code,$reports->dia_room, $name, $date_time);
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
          // unauthorise_permission('212','1206');
           // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $data_reg_name= get_setting_value('PATIENT_REG_NO');

          $fields = array($data_reg_name,'Patient Name','Dialysis Booking No','Dialysis Room','Assign Doctors','Dialysis Booking Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->dialysis_schedule_list->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                   $doctor_name=array(); 
                   $doctor_list= $this->dialysis_schedule_list->doctor_list_by_otids($reports->id);
                foreach($doctor_list as $doctor_list=>$value){
                    $doctor_name[]=$value[0];
                    }
                     $name= implode(',',$doctor_name);
                     if($reports->dialysis_booking_date=='0000-00-00')
                     {
                      $date_time='';
                     }
                     else
                     {
                      $date_time= date('d-m-Y',strtotime($reports->dialysis_booking_date));
                     }
                     array_push($rowData,$reports->p_code,$reports->p_name,$reports->booking_code,$reports->dia_room,$name,$date_time);
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
       // unauthorise_permission('212','1207');
        $data['print_status']="";
        $data['data_list'] = $this->dialysis_schedule_list->search_report_data();
        $this->load->view('dialysis_schedule_list/dialysis_booking_report_html',$data);
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
   //unauthorise_permission('212','1192');   
      $data['print_status']="1";
      $data['data_list'] = $this->dialysis_schedule_list->search_report_data();
      $this->load->view('dialysis_schedule_list/dialysis_booking_report_html',$data); 
    }
    
    public function advance_search()
    {

            $this->load->model('general/general_model'); 
            $data['page_title'] = "Advance Search";
            $post = $this->input->post();
           
            $data['form_data'] = array(
                                      "start_datetime"=>"",
                                      "end_datetime"=>"",
                                      "ipd_no"=>"",
                                      "patient_code"=>"",
                                      "patient_name"=>"",
                                      "mobile_no"=>"",
                                      "dialysis_date"=>"",
                                      'all'=>'',
                                      "dialysis_time"=>"",
                                      
                                      );
            if(isset($post) && !empty($post))
            {
         
                $marge_post = array_merge($data['form_data'],$post);
                $this->session->set_userdata('dialysis_booking_serach', $marge_post);

            }
                $purchase_search = $this->session->userdata('dialysis_booking_serach');
                if(isset($purchase_search) && !empty($purchase_search))
                  {
                  $data['form_data'] = $purchase_search;
                   }
            $this->load->view('dialysis_schedule_list/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('dialysis_booking_serach');
    }
   
     
   
   public function delete($id="")
    {
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_schedule_list->delete($id);
           $response = "Schedule successfully deleted.";
           echo $response;
       }
    }

   
    function deleteall()
    {
       //unauthorise_permission('134','810');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_schedule_list->deleteall($post['row_id']);
            $response = "Dialysis schedule successfully deleted.";
            echo $response;
        }
    }

 


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('134','811');
        $data['page_title'] = 'Dialysis Schedule archive list';
        $this->load->helper('url');
        $this->load->view('dialysis_schedule_list/archive',$data);
    }

    public function archive_ajax_list()
    {
       // unauthorise_permission('134','811');
        $this->load->model('dialysis_schedule_list/dialysis_schedule_archive_model','dialysis_schedule_list_archive');
        $list = $this->dialysis_schedule_list_archive->get_datatables();
       // print_r();die;  
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
           
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysis->id.'">'.$check_script; 
          $row[] = $dialysis->ipd_no;
          $row[] = $dialysis->patient_code;  
          $row[] = $dialysis->patient_name;
          $row[] = date('d-m-Y',strtotime($dialysis->operation_date));
          $row[] = $dialysis->operation_time;
          $doctor_list= $this->dialysis_schedule_list->doctor_list_by_otids($dialysis->id);
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
          //if(in_array('813',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_dialysis_pacakge('.$dialysis->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
         // }
          //if(in_array('812',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$dialysis->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          //}
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dialysis_schedule_list_archive->count_all(),
                        "recordsFiltered" => $this->dialysis_schedule_list_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission('134','813');
       $this->load->model('dialysis_schedule_list/dialysis_schedule_archive_model','dialysis_schedule_list_archive');
         if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_schedule_list_archive->restore($id);
           $response = "Dialysis schedule successfully restore in Dialysis schedule list.";
           echo $response;
       }
    }

    function restoreall()
    { 
      //  unauthorise_permission('134','813');
        $this->load->model('dialysis_schedule_list/dialysis_schedule_archive_model','dialysis_schedule_list_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_schedule_list_archive->restoreall($post['row_id']);
            $response = "Dialysis schedule successfully restore in Dialysis schedule list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       // unauthorise_permission('134','812');
       $this->load->model('dialysis_schedule_list/dialysis_schedule_archive_model','dialysis_schedule_list_archive');
        if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_schedule_list_archive->trash($id);
           $response = "Dialysis schedule successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('134','812');
        $this->load->model('dialysis_schedule_list/dialysis_schedule_archive_model','dialysis_schedule_list_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_schedule_list_archive->trashall($post['row_id']);
            $response = "Dialysis schedule successfully deleted parmanently.";
            echo $response;
        }
    }
    
    public function view($id="")
    {  
        $this->load->model('general/general_model');
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->dialysis_schedule_list->get_by_id($id);

        $data['schedule_availablity'] = $data['form_data']['schedule_availablity'];
        $array_result = array();
        if(!empty($data['form_data']['schedule_availablity']))
        {
            foreach ($data['form_data']['schedule_availablity'] as $value) 
            {   
                $array_result[$value->available_day] = $value->day_name;    
            }
        }
        $data['available_day'] = $array_result;
        
        $data['days_list'] = $this->general_model->get_days_list();

        $data['page_title'] = $data['form_data']['schedule_name']." detail";
        $this->load->view('dialysis_schedule_management/view',$data);     
      }
    }  
 

}
?>