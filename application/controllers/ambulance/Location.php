<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends CI_Controller {

	function __construct() 
	{
		parent::__construct();	
		auth_users();  
    $this->load->model('general/general_model','general');
    $this->load->model('ambulance/location_model','location');
    $this->load->library('form_validation');
  }


  public function index()
  {
   unauthorise_permission('408','2471');
   $data['page_title'] = 'location List'; 
   $this->load->view('ambulance/location/list',$data);
 }

 public function ajax_list()
 { 
  unauthorise_permission('408','2471');
  $users_data = $this->session->userdata('auth_users');
  $sub_branch_details = $this->session->userdata('sub_branches_data');
  $parent_branch_details = $this->session->userdata('parent_branches_data');
  $list = $this->location->get_datatables();
       // print_r($list);die;
  $data = array();
  $no = $_POST['start'];
  $i = 1;
  $total_num = count($list);
  foreach ($list as $location_list) {
         // print_r($ipd_perticular);die;
    $no++;
    $row = array();
    if($location_list->status==1)
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
      $checkboxs = "";
      if($users_data['parent_id']==$location_list->branch_id)
      {
       $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$location_list->id.'">'.$check_script;
     }else{
       $row[]='';
     }
     if(!empty($location_list->status))
     {
      if($location_list->status==1)
      {
        $status='<font color="green">Active</font>';
      }
      elseif($location_list->status==0)
      {
        $status='<font color="green">InActive</font>';
      }
    }
    $row[] = $location_list->location_name;
    $row[] = $status;
    //$row[] = date('d-M-Y H:i A',strtotime($location_list->created_date)); 
            // $row[] = $status;
          
    $btnedit='';
    $btndelete='';

    if($users_data['parent_id']==$location_list->branch_id)
    {
      if(in_array('2469',$users_data['permission']['action'])){
       $btnedit =' <a onClick="return edit_location_list('.$location_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$location_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
     }
     if(in_array('2468',$users_data['permission']['action'])){
      $btndelete = ' <a class="btn-custom" onClick="return delete_location_list('.$location_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
    }
  }

  $row[] = $btnedit.$btndelete;


  $data[] = $row;
  $i++;
}

$output = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $this->location->count_all(),
  "recordsFiltered" => $this->location->count_filtered(),
  "data" => $data,
);
        //output to json format
echo json_encode($output);
}


public function add()
{
  unauthorise_permission('408','2470');
  $data['page_title'] = 'Add location'; 
  $post = $this->input->post();
  $data['form_error'] = []; 
  $data['form_data'] = array(
    'data_id'=>"", 
    'location_name'=>"",
    'status'=>1

  );   


  if(isset($post) && !empty($post))
  {   

   $data['form_data'] = $this->_validate();
   if($this->form_validation->run() == TRUE)
   {
    $this->location->save();
    echo 1;
    return false;

  }
  else
  {

    $data['form_error'] = validation_errors();  

  }
}
$this->load->view('ambulance/location/add',$data);       
}

public function edit($id="")
{ 
 unauthorise_permission('408','2469');
 $data['page_title'] = 'Edit Location'; 
 $post=$this->input->post();
 if(isset($id) && !empty($id) && is_numeric($id))
 {      
  $result = $this->location->get_by_id($id);
  $this->load->model('general/general_model');   
  $data['form_error'] = ''; 
  $data['form_data'] = array(
    'data_id'=>$result['id'], 
    'location_name'=>$result['location_name'],
    'status'=>$result['status']
  );   


  if(isset($post) && !empty($post))
  {   
     
    $data['form_data'] = $this->_validate();
    if($this->form_validation->run() == TRUE)
    {
    
      $this->location->save();
      echo 1;
      return false;

    }
    else
    {
        
      $data['form_error'] = validation_errors();  
      print_r($data['form_error']);die;
    }     
  }
  $this->load->view('ambulance/location/add',$data);       
}
}
private function _validate()
{
  $post = $this->input->post();  
      //  print_r($post);die;

  $this->load->model('general/general_model'); 

  $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
  $this->form_validation->set_rules('location_name', 'Location name', 'trim|required'); 
  $this->form_validation->set_rules('status', 'Status', 'trim|required'); 

  if ($this->form_validation->run() == FALSE) 
  {  
    $data['form_data'] = array(
     'data_id'=>$post['data_id'], 
     'location_name'=>$post['location_name'],
    'status'=>$post['status']
   ); 
    return $data['form_data'];
  } 
}

public function delete($id="")
{
 unauthorise_permission('408','2468');
 if(!empty($id) && $id>0)
 {

   $result = $this->location->delete($id);
   $response = "location successfully deleted.";
   echo $response;
 }
}

function deleteall()
{
  unauthorise_permission('408','2468');
  $post = $this->input->post();  
  if(!empty($post))
  {
    $result = $this->location->deleteall($post['row_id']);
    $response = "locations successfully deleted.";
    echo $response;
  }
}

public function location_excel()
{
  $this->load->library('excel');
  $this->excel->IO_factory();
  $objPHPExcel = new PHPExcel();
  $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
  $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
  $fields = array('ID','location Name','Mobile No','DL no');
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

   $col++;
 }
 $list = $this->location->search_report_data();

 $rowData = array();
 $data= array();
 if(!empty($list))
 {

   $m=1;
   foreach($list as $reports)
   {

    array_push($rowData,$m,$reports->location_name,$reports->mobile_no,$reports->licence_no);
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

          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
header('Content-Type: application/vnd.ms-excel charset=UTF-8');
header("Content-Disposition: attachment; filename=location_list_".time().".xls");
header("Pragma: no-cache"); 
header("Expires: 0");
if(!empty($data))
{
  ob_end_clean();
  $objWriter->save('php://output');
}
}

public function pdf_location()
{    
  $data['print_status']="";
  $data['data_list'] = $this->location->search_report_data();
        //echo "<pre>";print_r($data['data_list']);die;
  $this->load->view('ambulance/location/location_report_html',$data);
  $html = $this->output->get_output();
        // Load library
  $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
  $this->pdf->load_html($html);
  $this->pdf->render();
  $this->pdf->stream("location_list_".time().".pdf");
}

}
?>