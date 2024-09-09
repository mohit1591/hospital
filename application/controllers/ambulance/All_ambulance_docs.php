<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_ambulance_docs extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
     $this->load->model('ambulance/document_file_model','document_file');
    }

    
	public function index()
    {
        $data['page_title'] = 'Document List';
        $this->load->model('ambulance/document_reports_model','document_report');
         $data['vehicle_list'] = $this->document_report->vehicle_list();
         $data['document_list'] = $this->document_report->document_list();
        $this->load->view('ambulance/all_documents/list',$data);
    }

    public function ajax_list()
    { 
        $users_data = $this->session->userdata('auth_users');
        $vehicle_id='';
        $list = $this->document_file->get_datatables($vehicle_id); 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescription) { 
            $no++;
            $row = array();
            if($prescription->status==1)
            {
                $date1= $prescription->expiry_date;
                $date2 = date('Y-m-d');
                $status = '<font color="green">Active</font>';
                if($date1<$date2)
                {
                    $status = '<font color="red">Inactive</font>';
                }
                else
                {
                    $status = '<font color="green">Active</font>';
                }
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
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
            $row[] = '<input type="checkbox" name="document[]" class="checklist" value="'.$prescription->id.'">'.$check_script;

            $sign_img = "";
            if(!empty($prescription->vehicle_docs) && file_exists(DIR_UPLOAD_PATH.'vehicle_docs/'.$prescription->vehicle_docs))
            {
                $sign_img = ROOT_UPLOADS_PATH.'vehicle_docs/'.$prescription->vehicle_docs;
                $ext = pathinfo($sign_img, PATHINFO_EXTENSION);
                if($ext=='pdf' || $ext=='doc' || $ext=='docs')
                {
                  $sign_img = '<a href="'.$sign_img.'" download><img src="'.base_url().'assets/images/pdf.png'.'" width="70px"/></a>';
                }
                else{                  
                   $sign_img = '<a href="'.$sign_img.'" download><img src="'.$sign_img.'" width="70px"/></a>';
                }
            }

            $row[] = $sign_img;
            $row[] = $prescription->document;
            $row[] = $prescription->vehicle_no;
            $row[] = date('d-m-Y',strtotime($prescription->renewal_date)); 
            $row[] = date('d-m-Y',strtotime($prescription->expiry_date)); 
            $row[] = $status; 
            $row[] = date('d-M-Y h:i A',strtotime($prescription->created_date));  
            $btn_del='';
             //if(in_array('2370',$users_data['permission']['action'])){
              $btn_del = '<a class="btn-custom" onClick="return delete_document_file('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';  
             //}
            $row[] = $btn_del;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->document_file->count_all($vehicle_id),
                        "recordsFiltered" => $this->document_file->count_filtered($vehicle_id),
                        "data" => $data,
                );
        echo json_encode($output);
    }
 
  
    public function document_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('S.No.','Document Name','Vehicle No.','Renewal Date','Expiry Date', 'Status', 'Created Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           
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
          $list = $this->document_file->search_report_data();

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $m=1;
               foreach($list as $reports)
               {
                   if($reports->status==1)
                    {
                        $status = 'Active';
                    }   
                    else{
                        $status = 'Inactive';
                    }
                    array_push($rowData,$m,$reports->document,$reports->vehicle_no,date('d-m-Y',strtotime($reports->renewal_date)),date('d-m-Y',strtotime($reports->expiry_date)),$status,date('d-m-Y',strtotime($reports->created_date)));
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;  
                    $m++;
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
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=docs_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function pdf_documents()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->document_file->search_report_data();
        $this->load->view('ambulance/all_documents/docs_report_html',$data);
        $html = $this->output->get_output();
        $this->load->library('pdf');
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("docs_list_".time().".pdf");
    }
    
    
    public function advance_search()
    {
        $post = $this->input->post();
        $this->session->set_userdata('docs_search', $post);
    }

    public function reset_form()
    {
        $this->session->unset_userdata('docs_search');
    }
}
?>