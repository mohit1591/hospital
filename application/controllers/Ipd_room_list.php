<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_room_list extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_room_list/ipd_room_list_model','ipd_room_list');
        $this->load->library('form_validation');
    }

    public function index()
    { 
      unauthorise_permission('108','670');
      $data['page_title'] = 'IPD Room List'; 
      $this->session->unset_userdata('room_list');
      $this->load->view('ipd_room_list/list',$data);
    }

    public function ajax_list()
    { 
      unauthorise_permission('108','670');
      $users_data = $this->session->userdata('auth_users');

      $sub_branch_details = $this->session->userdata('sub_branches_data');
      $parent_branch_details = $this->session->userdata('parent_branches_data');

      $list = $this->ipd_room_list->get_datatables();

        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_room_list) {
         // print_r($ipd_room_list);die;
            $no++;
            $row = array();
            // if($ipd_room_list->status==1)
            // {
            //     $status = '<font color="green">Active</font>';
            // }   
            // else{
            //     $status = '<font color="red">Inactive</font>';
            // } 
            
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
            if($users_data['parent_id']==$ipd_room_list->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_room_list->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $ipd_room_list->room_no;
            $row[] = $ipd_room_list->room_category;
            // $row[] = $ipd_room_list->panel_type;
            // $row[] = $ipd_room_list->panel_company;
            $get_charges= get_room_charge_according_to_branch($users_data['parent_id']);
            if(!empty($get_charges))
            {

              foreach($get_charges as $charge_type)
              {
                  $room_charges = get_room_charge_accordint_to_id($users_data['parent_id'], $charge_type->id, $ipd_room_list->room_type_id, $ipd_room_list->id);
                  if(!empty($room_charges[0]->room_charge))
                  {
                     $row[] = $room_charges[0]->room_charge;
                  }
                  else
                  {
                     $row[]='';
                  }
              }

            }
            $get_bad_number= count_bad($ipd_room_list->id);
            $row[]='<a href="'.base_url().'ipd_room_list/get_bed_to_room_list/roomno/'.$ipd_room_list->id.'/roomcategory/'.$ipd_room_list->room_category_id.'"><u>'.$get_bad_number[0]->total_bad.'</u></a>';
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_room_list->created_date)); 
            $btnedit='';
            $btndelete='';
            $btnview='';
          
            if($users_data['parent_id']==$ipd_room_list->branch_id)
            {
               if(in_array('672',$users_data['permission']['action'])){
                   $btnedit = ' <a class="btn-custom" onClick="return edit_ipd_room_list('.$ipd_room_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-pencil"></i> Edit</a> ';
               }
               if(in_array('673',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_room_list('.$ipd_room_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';  
               } 
                    $btnview =' <a onClick="return view_ipd_room_list('.$ipd_room_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_room_list->id.'" title="Edit"><i class="fa fa-info-circle" aria-hidden="true"></i> View</a>';
                
            }
          
             $row[] = $btnedit.$btndelete.$btnview;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_room_list->count_all(),
                        "recordsFiltered" => $this->ipd_room_list->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function room_list_excel()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row

        $users_data= $this->session->userdata('auth_users');
        $get_charges= get_room_charge_according_to_branch($users_data['parent_id']); 
        if(!empty($get_charges))
          {
            $field_name[] = 'Room No.';
            $field_name[] = 'Room Type';
            foreach($get_charges as $charges_type)
              {
                $field_name[]=  $charges_type->charge_type;
              }   
            $field_name[] = 'No. of Beds';  
            $fields = $field_name;
          }
          else
          {
             $fields = array('Room No.','Room Type','No. of Beds');
          } 
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
              $col++;
          }
          
          $rowData = array();
          $data= array();
          $list = $this->ipd_room_list->search_report_data();
          if(!empty($list))
          { 
              $i=0;
              foreach($list as $reports)
              { 
                  $get_charges= get_room_charge_according_to_branch($users_data['parent_id']);
                  $get_bad_number= count_bad($reports->id);
                  $data_vals = [];
                  if(!empty($get_charges))
                  {
                    $data_vals[]=$reports->room_no;
                    $data_vals[]=$reports->room_category;
                    foreach($get_charges as $charge_type)
                    { 
                       $room_charges = get_room_charge_accordint_to_id($users_data['parent_id'], $charge_type->id, $reports->room_type_id, $reports->id);
                       if(!empty($room_charges[0]->room_charge))
                       {
                        $data_vals[]=$room_charges[0]->room_charge;
                       }
                       else
                       {
                        $data_vals[]=0;
                       } 
                    }
                     $data_vals[]=$get_bad_number[0]->total_bad; 
                     $rowData = $data_vals;
                     $data_vals = array();
                  }
                  else
                  {
                    array_push($rowData,$reports->room_no,$reports->room_category,$get_bad_number[0]->total_bad);
                  } 
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
          //echo '<pre>';print_r($data);die;
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $patients_data)
              {
                  $col = 0;
                  foreach ($fields as $field)
                  { 
                      $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $patients_data[$field]);
                      $col++;
                  }
                  $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }


          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=ipd_room_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function room_list_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $users_data= $this->session->userdata('auth_users');
          $get_charges= get_room_charge_according_to_branch($users_data['parent_id']); 
        if(!empty($get_charges))
          {
            $field_name[] = 'Room No.';
            $field_name[] = 'Room Type';
            foreach($get_charges as $charges_type)
              {
                $field_name[]=  $charges_type->charge_type;
              }   
            $field_name[] = 'No. of Beds';  
            $fields = $field_name;
          }
          else
          {
             $fields = array('Room No.','Room Type','No. of Beds');
          } 


          $col = 0;

          foreach ($fields as $field)
          {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
              $col++;
          }


          $list = $this->ipd_room_list->search_report_data();
          if(!empty($list))
          { 
              $i=0;
              foreach($list as $reports)
              { 
                  $get_charges= get_room_charge_according_to_branch($users_data['parent_id']);
                  $get_bad_number= count_bad($reports->id);
                  $data_vals = [];
                  if(!empty($get_charges))
                  {
                    $data_vals[]=$reports->room_no;
                    $data_vals[]=$reports->room_category;
                    foreach($get_charges as $charge_type)
                    { 
                       $room_charges = get_room_charge_accordint_to_id($users_data['parent_id'], $charge_type->id, $reports->room_type_id, $reports->id);
                       if(!empty($room_charges[0]->room_charge))
                       {
                        $data_vals[]=$room_charges[0]->room_charge;
                       }
                       else
                       {
                        $data_vals[]=0;
                       } 
                    }
                     $data_vals[]=$get_bad_number[0]->total_bad; 
                     $rowData = $data_vals;
                     $data_vals = array();
                  }
                  else
                  {
                    array_push($rowData,$reports->room_no,$reports->room_category,$get_bad_number[0]->total_bad);
                  } 
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
          header("Content-Disposition: attachment; filename=ipd_room_list_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    
    }

    public function pdf_room_list()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->ipd_room_list->search_report_data();
        $this->load->view('ipd_room_list/ipd_room_list_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("room_list_report_".time().".pdf");
    }
    public function print_room_list()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->ipd_room_list->search_report_data();
      $this->load->view('ipd_room_list/ipd_room_list_html',$data); 
    }

    public function advance_search()
    {

      $this->load->model('general/general_model'); 
      $data['page_title'] = "Advance Search";
      $post = $this->input->post();

      $data['form_data'] = array(
      'room_type'=>''
      );
        if(isset($post) && !empty($post))
        {
          //print_r($post);die;
          $marge_post = array_merge($data['form_data'],$post);
          $this->session->set_userdata('room_list', $marge_post);
        }
        $room_list = $this->session->userdata('room_list');
        if(isset($room_list) && !empty($room_list))
        {
          $data['form_data'] = $room_list;
        }
    //$this->load->view('ipd_room_list/advance_search',$data);
    }
    
    
    public function add()
    {
        unauthorise_permission('108','671');
        $data['page_title'] = "Add IPD Room";  
        $this->load->model('general/general_model'); 
        $data['room_type_list'] = $this->general_model->room_type_list();
        $data['room_charge_type_list'] = $this->general_model->room_charge_type_list();
        // $data['panel_type_list'] = $this->general_model->panel_type_list();
        // $data['panel_company_list'] = $this->general_model->panel_company_list();
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'room_category_id'=>"",
                                  // 'panel_type_id'=>"",
                                  // 'panel_company_id'=>"",
                                  'room_no'=>"",
                                  // 'bed_charge' =>"",
                                  // 'nursing_charge'=>"",
                                  // 'rmo_charge'=>"",
                                  // 'panel_bed_charges'=>"",
                                  // 'panel_nursing_charges'=>"",
                                  // 'panel_rmo_charges'=>"",
                                  'total_bad'=>""
                                  );   
        $room_charge_type_list = $data['room_charge_type_list'];
          if(!empty($room_charge_type_list)){
               $room_charge_type_list_count = count($room_charge_type_list);
               for($i=0;$i<$room_charge_type_list_count;$i++){
              $data['form_data'][strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'] = "";
                $data['form_data'][strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['code'] = "";
               }
               
          } 

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_room_list->save();
                echo 1;
                return false;
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
             
            }     
        }
       $this->load->view('ipd_room_list/add',$data);       
    }
     // -> function to find gender according to selected ipd_room_list
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_room_list_id = $this->input->post('ipd_room_list_id');
         $data='';
          if(!empty($ipd_room_list_id)){
               $result = $this->general_model->find_gender($ipd_room_list_id);
               if(!empty($result))
               {
                  $male = "";
                  $female = ""; 
                  $others=""; 
                  if($result['gender']==1)
                  {
                     $male = 'checked="checked"';
                  } 
                  else if($result['gender']==0)
                  {
                     $female = 'checked="checked"';
                  } 
                  else if($result['gender']==2){
                    $others = 'checked="checked"';
                  }
                  $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
                          <input type="radio" name="gender" '.$female.' value="0" /> Female &nbsp;
                          <input type="radio" name="gender" '.$others.' value="2" /> Others ';
               }
 
          }
          echo $data;
    }
    // -> end:
    public function edit($id="")
    {
     unauthorise_permission('108','672');

     if(isset($id) && !empty($id) && is_numeric($id))
      {     
        $this->load->model('general/general_model'); 
        $data['room_type_list'] = $this->general_model->room_type_list(); 
         $data['room_charge_type_list'] = $this->general_model->room_charge_type_list();
         $result = $this->ipd_room_list->get_by_id($id);  

        //print_r($result);die;
        $data['page_title'] = "Update IPD Room";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'room_category_id'=>$result['room_type_id'],
                                  // 'panel_type_id'=>$result['panel_type_id'],
                                  // 'panel_company_id'=>$result['panel_company_id'],
                                  'room_no'=>$result['room_no'],
                                  // 'bed_charge' =>$result['bed_charges'],
                                  // 'nursing_charge'=>$result['nursing_charges'],
                                  // 'rmo_charge'=>$result['rmo_charges'],
                                  // 'panel_bed_charges'=>$result['panel_bed_charges'],
                                  // 'panel_nursing_charges'=>$result['panel_nursing_charges'],
                                  // 'panel_rmo_charges'=>$result['panel_rmo_charges'],
                                  'total_bad'=>$result['total_bad']
                                  );  
         $room_charge_type_list = $data['room_charge_type_list'];

         if(!empty($room_charge_type_list)){ 
               $room_charge_type_list_count = count($room_charge_type_list);
               for($i=0;$i<$room_charge_type_list_count;$i++){ //echo $id;
                
               $data['form_data'][strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] =get_charges_according($id,$room_charge_type_list[$i]['id']);

              // print_r(get_charges_according($id,$room_charge_type_list[$i]['id']));
               }
               
          } 


        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_room_list->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       //print '<pre>'; print_r($data);die;
       $this->load->view('ipd_room_list/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();  
      
        $this->load->model('general/general_model'); 
        $data['room_charge_type_list'] = $this->general_model->room_charge_type_list();  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('room_category_id', 'room type', 'trim|required'); 
        $this->form_validation->set_rules('room_no', 'room no.', 'trim|required|callback_check_room_no'); 
       
        // $this->form_validation->set_rules('nursing_charge', 'nursing charges', 'trim|required'); 
        // $this->form_validation->set_rules('rmo_charge', 'rmo charges', 'trim|required'); 
        // $this->form_validation->set_rules('panel_bed_charges', 'panel charges', 'trim|required');
        // $this->form_validation->set_rules('panel_nursing_charges', 'panel charges', 'trim|required');
        // $this->form_validation->set_rules('panel_rmo_charges', 'panel charges', 'trim|required');
        //  $this->form_validation->set_rules('panel_type_id', 'panel type', 'trim|required');
        //   $this->form_validation->set_rules('panel_company_id', 'panel company', 'trim|required');
        $this->form_validation->set_rules('total_bad', 'no. of beds', 'trim|required'); 
        $room_charge_type_list = $data['room_charge_type_list'];
         if(!empty($room_charge_type_list)){

               $room_charge_type_list_count = count($room_charge_type_list);
               for($i=0;$i<$room_charge_type_list_count;$i++){

                 if(empty($post['charges'][$room_charge_type_list[$i]['id']][1]))
                  {
                    $this->form_validation->set_rules('charges['.$room_charge_type_list[$i]['id'].']',ucfirst($room_charge_type_list[$i]['charge_type']), 'trim|required'); 

                 }
                //echo 'charges['.$room_charge_type_list[$i]['id'].']';
                  
               }
               
          } 
          
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'room_category_id'=>$post['room_category_id'],
                                        // 'panel_type_id'=>$post['panel_type_id'],
                                        // 'panel_company_id'=>$post['panel_company_id'],
                                        'room_no'=>$post['room_no'],
                                        // 'bed_charge' =>$post['bed_charges'],
                                        // 'nursing_charge'=>$post['nursing_charges'],
                                        // 'rmo_charge'=>$post['rmo_charges'],
                                        // 'panel_bed_charges'=>$post['panel_bed_charges'],
                                        // 'panel_nursing_charges'=>$post['panel_nursing_charges'],
                                        // 'panel_rmo_charges'=>$post['panel_rmo_charges'],
                                        'total_bad'=>$post['total_bad']
                                       ); 
            if(!empty($room_charge_type_list)){
               $room_charge_type_list_count = count($room_charge_type_list);
               for($i=0;$i<$room_charge_type_list_count;$i++){
              $data['form_data'][strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'] = $post['charges'][$room_charge_type_list[$i]['id']][1];
              $data['form_data'][strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['code'] = $post['charges'][$room_charge_type_list[$i]['id']][0];
               }
               
          } 
            //print '<pre>'; print_r($data['form_data']);die;
            return $data['form_data'];
        }   
    }
     public function check_room_no($str){
 
          $post = $this->input->post();
          if(!empty($post['room_no']))
          {
               $this->load->model('general/general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    return true;
               }
               else
               {
                    $room_data = $this->general->check_room_no($post['room_no'],$post['room_category_id']);
                    if(empty($room_data))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('check_room_no', 'The room no. already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('check_room_no', 'The  room no. field is required.');
               return false; 
          } 
     }
    public function delete($id="")
    {
       unauthorise_permission('108','673');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_room_list->delete($id);
           $response = "ipd_room_list successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('108','673');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_room_list->deleteall($post['row_id']);
            $response = "IPD Room  successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_room_list->get_ipd_room_details($id);  
      //print '<pre>'; print_r($data['form_data'][0]['total_bad']);die;
        $data['page_title'] ="IPD Room Detail";
       
        $this->load->view('ipd_room_list/view_ipd_room',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('108','674');
        $data['page_title'] = 'IPD Room Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_room_list/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('108','674');
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('ipd_room_list/ipd_room_list_archive_model','ipd_room_list_archive'); 
         $list = $this->ipd_room_list_archive->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_room_list) {
         // print_r($ipd_room_list);die;
            $no++;
            $row = array();
            // if($ipd_room_list->status==1)
            // {
            //     $status = '<font color="green">Active</font>';
            // }   
            // else{
            //     $status = '<font color="red">Inactive</font>';
            // } 
            
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
            if($users_data['parent_id']==$ipd_room_list->branch_id)
            {
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_room_list->id.'">'.$check_script;
            }else{
            $row[]='';
            }
            $row[] = '<a href="'.base_url().'ipd_room_list/get_bed_to_room_list/roomno/'.$ipd_room_list->id.'/roomcategory/'.$ipd_room_list->room_category_id.'">'.$ipd_room_list->room_no.'</a>'; 
            $row[] = $ipd_room_list->room_category;
            // $row[] = $ipd_room_list->panel_type;
            // $row[] = $ipd_room_list->panel_company;
            $get_charges= get_room_charge_according_to_branch($users_data['parent_id']);
            if(!empty($get_charges))
            {

              foreach($get_charges as $charge_type)
              {
                  $room_charges = get_room_charge_accordint_to_id($users_data['parent_id'], $charge_type->id, $ipd_room_list->room_type_id, $ipd_room_list->id);
                  if(!empty($room_charges[0]->room_charge))
                  {
                     $row[] = $room_charges[0]->room_charge;
                  }
                  else
                  {
                     $row[]='';
                  }
              }

            }
            $get_bad_number= count_bad($ipd_room_list->id);
            $row[]=$get_bad_number[0]->total_bad;
            // $row[] = date('d-M-Y H:i A',strtotime($ipd_room_list->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
             if(in_array('676',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_room_list('.$ipd_room_list->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
               if(in_array('675',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_room_list->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
                }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_room_list_archive->count_all(),
                        "recordsFiltered" => $this->ipd_room_list_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('108','676');
        $this->load->model('ipd_room_list/ipd_room_list_archive_model','ipd_room_list_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_room_list_archive->restore($id);
           $response = "IPD Room successfully restore in IPD Room.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('108','676');
        $this->load->model('ipd_room_list/ipd_room_list_archive_model','ipd_room_list_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_room_list_archive->restoreall($post['row_id']);
            $response = "IPD Room  successfully restore in IPD Room.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('108','675');
        $this->load->model('ipd_room_list/ipd_room_list_archive_model','ipd_room_list_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_room_list_archive->trash($id);
           $response = "IPD Room successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('108','675');
        $this->load->model('ipd_room_list/ipd_room_list_archive_model','ipd_room_list_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_room_list_archive->trashall($post['row_id']);
            $response = "IPD Room successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ipd_room_list_dropdown()
  {
     $ipd_room_list_list = $this->ipd_room_list->ipd_room_list();
     $dropdown = '<option value="">Select Room</option>'; 
     //$ipd_room_lists_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($ipd_room_list_list))
     {
          foreach($ipd_room_list_list as $ipd_room_list)
          {
               
               $selected_ipd_room_list = '';   
               $dropdown .= '<option value="'.$ipd_room_list->id.'" '.$selected_ipd_room_list.' >'.$ipd_room_list->ipd_room_list.'</option>';
          }
     } 
     echo $dropdown; 
  }
  public function bed_status()
  {
      $data['page_title']="Room Bed Status";
       $this->load->model('general/general_model'); 
      $data['room_type_list']=$this->general_model->room_type_list();
      $this->load->view('ipd_room_list/room_bed_status',$data);
  }
  public function select_bed_no_number()
  {
      
        $this->load->model('general/general_model');
        $room_id= $this->input->post('room_id');
        $room_no_id= $this->input->post('room_no_id');
        $bed_id= $this->input->post('bed_id');
        $ipd_id= $this->input->post('ipd_id');

        $beds_total_count = $this->ipd_room_list->get_all_beds_counts('',$room_id,$room_no_id);
        $total_bed_in_hosp = $beds_total_count[0]->total_bad;

        $beds_count = $this->ipd_room_list->get_all_beds_counts(1,$room_id,$room_no_id);
        $total_bed_filled = $beds_count[0]->total_bad;

        $beds_free_count = $this->ipd_room_list->get_all_beds_counts(2,$room_id,$room_no_id);
        $total_bed_free = $beds_free_count[0]->total_bad;

        

        $dropdown ="";
          if(!empty($room_id) && !empty($room_no_id))
          {
            $data['number_bed']= $this->general_model->number_bed_all_list($room_id,$room_no_id,$ipd_id);
          }


          


          if(!empty($data['number_bed']))
          {
            
   $dropdown .='<div> <table align="right" cellpadding="0" cellspacing="0" border="0" width="200px" style="border:1px solid #EEE;margin-bottom:10px;">
         <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:red;"></div></td> <td><a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',0);">Total Bed '.$total_bed_in_hosp.'</a></td> </tr>

         <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:orange;"></div></td> <td> <a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',2);"> VACANT  '.$total_bed_free.'</a></td> </tr> <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:green;"></div></td><td><a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',1);"> Full  '.$total_bed_filled.'</a></td> </tr></table></div><br><br><br><br><br><br>';






             $dropdown .='<div class="beds">'; 
            foreach($data['number_bed'] as $number_bed)
            {

              if($number_bed->status==1 && $number_bed->ipd_is_deleted!=2)
              {
                 $get_patient_detail=$this->ipd_room_list->get_patient_detail($number_bed->id,$number_bed->ipd_id);
                 $free_button='';
                 if($get_patient_detail[0]->discharge_status=='1')
                 {
                     
                    $room_iddd = $number_bed->room_id;
                    $room_no_iddd = $number_bed->room_type_id;
                    $bad_no_iddd = $number_bed->bad_no;
            
                     $ipd_id = $get_patient_detail[0]->id;
                     $free_button = ' <a href="javascript:void(0);"  onClick="return free_room('.$ipd_id.','.$room_iddd.','.$room_no_iddd.','.$bad_no_iddd.');"> Free Room</a>';
                     
                 }
                 else
                 {
                     $free_button = ' <br><a style="color: #fffaf0;" href="javascript:void(0)" onclick="generate_discharge_bill('.$get_patient_detail[0]->id.','.$get_patient_detail[0]->patient_id.',1)" title="Discharge Bill"><i class="fa fa-database" aria-hidden="true"></i> Discharge Bill</a>';
                 }
                 
                 if(!empty($number_bed->bad_name))
                 {
                    $bad_name = $number_bed->bad_name;
                 }
                 else
                 {
                     $bad_name = $number_bed->bad_no;
                 }
                 //print_r()
                $dropdown .='<div class="bed green">Name : '.$get_patient_detail[0]->patient_name.' <br> IPD No. : '.$get_patient_detail[0]->ipd_no.' <br> Bed No. : '.$bad_name.' '.$free_button.'</div>';
              }
              else
              {
                  if(!empty($number_bed->bad_name))
                 {
                    $bad_name = $number_bed->bad_name;
                 }
                 else
                 {
                     $bad_name = $number_bed->bad_no;
                 }
                 
                $dropdown .='<div class="bed yellow">Bed No. : '.$bad_name.'</div>';


              }
              
            }

            


            $dropdown.='</div>';
              
            
        } 
          echo $dropdown;
          
  }
  
  public function free_room()
  {
      
        $this->load->model('general/general_model');
        $room_id= $this->input->post('room_id');
        $room_no_id= $this->input->post('room_no_id');
        $bed_id= $this->input->post('bed_id');
        $ipd_id= $this->input->post('ipd_id');
        $post = $this->input->post();
       // echo "<pre>"; print_r($post); exit;

        $beds_total_count = $this->ipd_room_list->get_all_beds_counts('',$room_id,$room_no_id);
        $total_bed_in_hosp = $beds_total_count[0]->total_bad;

        $beds_count = $this->ipd_room_list->get_all_beds_counts(1,$room_id,$room_no_id);
        $total_bed_filled = $beds_count[0]->total_bad;

        $beds_free_count = $this->ipd_room_list->get_all_beds_counts(2,$room_id,$room_no_id);
        $total_bed_free = $beds_free_count[0]->total_bad;
       
      
        if(!empty($bed_id) && !empty($ipd_id))
        {
            $this->db->set('status','0');
            $this->db->set('ipd_id','0');
            
            
            $this->db->where('room_id',$room_id);
            $this->db->where('room_type_id',$room_no_id);
            
            $this->db->where('bad_no',$bed_id);
            $this->db->update('hms_ipd_room_to_bad');
            //echo $this->db->last_query(); exit;
        }
        
          $dropdown ="";

          $dropdown.='<div style="border: 1px solid #ccc; float: right; padding:5px; font-weight: bold;"> <table align="right" cellpadding="0" cellspacing="0" width="200px"> <tbody><tr><td><div class="m_alert_orange_mark" style="background-color:red;"></div></td> <td> <a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',0);">Total Bed '.$total_bed_in_hosp.'</a></td> </tr> <tr>

              <tr><td><div class="m_alert_orange_mark" style="background-color:orange;"></div></td> <td> <a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',2);"> VACANT  '.$total_bed_free.'</a></td> </tr> <tr><td><div class="m_alert_orange_mark" style="background-color:green;"></div></td><td><a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',1);"> Full  '.$total_bed_filled.'</a></td> </tr></tbody></table></div><br><br><br><br>';

          if(!empty($room_id) && !empty($room_no_id))
          {
            $data['number_bed']= $this->general_model->number_bed_all_list($room_no_id,$room_id,$ipd_id);
          }

           // echo "<pre>"; print_r($data['number_bed']); exit;
          if(!empty($data['number_bed']))
          {
            $dropdown='';
            foreach($data['number_bed'] as $number_bed)
            {

              if($number_bed->status==1 && $number_bed->ipd_is_deleted!=2)
              {
                 $get_patient_detail=$this->ipd_room_list->get_patient_detail($number_bed->id,$number_bed->ipd_id);
                 $free_button='';
                 if($get_patient_detail[0]->discharge_status=='1')
                 {
                     
                    $room_iddd = $number_bed->room_id;
                    $room_no_iddd = $number_bed->room_type_id;
                    $bad_no_iddd = $number_bed->bad_no;
            
                     $ipd_id = $get_patient_detail[0]->id;
                     $free_button = ' <a href="javascript:void(0);"  onClick="return free_room('.$ipd_id.','.$room_iddd.','.$room_no_iddd.','.$bad_no_iddd.');"> Free Room</a>';
                     
                 }
                 //print_r()
                $dropdown .='<div style="width:150px; height:70px; margin:10px 10px 10px 10px; padding-left: 5px; padding-right: 5px; float:left; background-color:green; color:#fff; font-weight: bold; font-size: 12px; font-family: callibri; vertical-align: middle; padding-top: 10px; border-radius: 5px;"><div style="width:100%; height:height:20px;">Name : '.$get_patient_detail[0]->patient_name.'</div><div style="width:100%; height:height:20px;">IPD No. : '.$get_patient_detail[0]->ipd_no.'</div><div style="width:100%; height:height:20px;">Bed No. : '.$number_bed->bad_no.' '.$free_button.'</div> </div>';
              }
              else
              {
                $dropdown .='<div style="width:120px; height:70px; margin:10px 10px 10px 10px; padding-left: 5px; padding-right: 5px; float:left; background-color:orange; color:#000; font-weight: bold; font-size: 14px; font-family: callibri; vertical-align: middle; padding-top: 10px; border-radius: 5px; padding-top: 30px;"><div style="width:100%; height:height:20px;">Bed No. : '.$number_bed->bad_no.'</div></div>';


              }
              
            }
          } 
          echo $dropdown;
  }


  public function search_filled()
  {

        $this->load->model('general/general_model');
        $room_id= $this->input->post('room_id');
        $room_no_id= $this->input->post('room_no_id');
        $status= $this->input->post('status');
       
        $beds_total_count = $this->ipd_room_list->get_all_beds_counts('',$room_id,$room_no_id);
        $total_bed_in_hosp = $beds_total_count[0]->total_bad;

        $beds_count = $this->ipd_room_list->get_all_beds_counts(1,$room_id,$room_no_id);
        $total_bed_filled = $beds_count[0]->total_bad;

        $beds_free_count = $this->ipd_room_list->get_all_beds_counts(2,$room_id,$room_no_id);
        $total_bed_free = $beds_free_count[0]->total_bad;
       
          $dropdown ="";
          if(!empty($room_id) && !empty($room_no_id))
          {
            $data['number_bed']= $this->general_model->total_number_bed_list_status($room_id,$room_no_id,$status);
          }

            //echo "<pre>"; print_r($data['number_bed']); exit;
          if(!empty($data['number_bed']))
          {
            $dropdown='';

            $dropdown.='<div style="border: 1px solid #ccc; float: right; padding:5px; font-weight: bold;"> <table align="right" cellpadding="0" cellspacing="0" width="200px"> <tbody><tr><td><div class="m_alert_orange_mark" style="background-color:red;"></div></td> <td><a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',0);"> Total Bed '.$total_bed_in_hosp.'</a></td> </tr> <tr>

              <tr><td><div class="m_alert_orange_mark" style="background-color:orange;"></div></td> <td>  <a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',2);"> VACANT  '.$total_bed_free.'</a></td> </tr> <tr><td><div class="m_alert_orange_mark" style="background-color:green;"></div></td><td> <a href="javascript:void(0);"  onClick="return search_filled('.$room_id.','.$room_no_id.',1);">Full  '.$total_bed_filled.'</a></td> </tr></tbody></table></div><br><br><br><br>';


            foreach($data['number_bed'] as $number_bed)
            {

              if($number_bed->status==1)
              {
                 $get_patient_detail=$this->ipd_room_list->get_patient_detail($number_bed->id,$number_bed->ipd_id);
                 $free_button='';
                 if($get_patient_detail[0]->discharge_status=='1')
                 {
                     
                    $room_iddd = $number_bed->room_id;
                    $room_no_iddd = $number_bed->room_type_id;
                    $bad_no_iddd = $number_bed->bad_no;
            
                     $ipd_id = $get_patient_detail[0]->id;
                     $free_button = ' <a href="javascript:void(0);"  onClick="return free_room('.$ipd_id.','.$room_iddd.','.$room_no_iddd.','.$bad_no_iddd.');"> Free Room</a>';
                     
                 }
                 //print_r()
                $dropdown .='<div style="width:150px; height:70px; margin:10px 10px 10px 10px; padding-left: 5px; padding-right: 5px; float:left; background-color:green; color:#fff; font-weight: bold; font-size: 12px; font-family: callibri; vertical-align: middle; padding-top: 10px; border-radius: 5px;"><div style="width:100%; height:height:20px;">Name : '.$get_patient_detail[0]->patient_name.'</div><div style="width:100%; height:height:20px;">IPD No. : '.$get_patient_detail[0]->ipd_no.'</div><div style="width:100%; height:height:20px;">Bed No. : '.$number_bed->bad_no.' '.$free_button.'</div> </div>';
              }
              else
              {
                $dropdown .='<div style="width:120px; height:70px; margin:10px 10px 10px 10px; padding-left: 5px; padding-right: 5px; float:left; background-color:orange; color:#000; font-weight: bold; font-size: 14px; font-family: callibri; vertical-align: middle; padding-top: 10px; border-radius: 5px; padding-top: 30px;"><div style="width:100%; height:height:20px;">Bed No. : '.$number_bed->bad_no.'</div></div>';


              }
              
            }
          } 
          echo $dropdown;

  }

  public function all_search_filled()
  { 

    $post = $this->input->post();
    $status = $post['status'];
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('general/general_model');
        $table ='';
        $room_list = $this->ipd_room_list->get_room_list();

        $beds_total_count = $this->ipd_room_list->get_all_beds_counts();
        $total_bed_in_hosp = $beds_total_count[0]->total_bad;

        $beds_count = $this->ipd_room_list->get_all_beds_counts(1);
        $total_bed_filled = $beds_count[0]->total_bad;

        $beds_free_count = $this->ipd_room_list->get_all_beds_counts(2);
        $total_bed_free = $beds_free_count[0]->total_bad;

      $table .='<div> <table align="right" cellpadding="0" cellspacing="0" border="0" width="200px" style="border:1px solid #EEE;margin-bottom:10px;">
         <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:red;"></div></td> <td><a href="javascript:void(0);"  onClick="return all_search_filled(0);">Total Bed '.$total_bed_in_hosp.'</a></td> </tr>

         <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:orange;"></div></td> <td> <a href="javascript:void(0);"  onClick="return all_search_filled(2);"> VACANT '.$total_bed_free.' </a></td> </tr> <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:green;"></div></td><td><a href="javascript:void(0);"  onClick="return all_search_filled(1);"> Full '.$total_bed_filled.'</a></td> </tr></table></div>';


        if((isset($room_list) && !empty($room_list)))
        {
            $table.='<table class="" style="width:100%;">';
            foreach($room_list as $particularlist)
            {  
                $table.='<tr style="">';
                $table.='<td style="text-align:center;font-weight:bold;padding:5px 0;"><big><u>'.$particularlist->room_category.'</u></big></td></tr><tr><td>';
                
                
                $number_room_list = $this->general_model->room_no_list($particularlist->id); 
                foreach ($number_room_list as $room_list) 
                {
                    $table.='<tr style="background-color:lightgray;">';
                    $table.='<td style="text-align:left;padding:4px;"><b>Room No.:'.$room_list->room_no.'</b></td></tr>';

                    $bed_list = $this->general_model->all_filter_number_bed_all_list($particularlist->id,$room_list->id,$status);
                    //echo "<pre>";print_r($bed_list);

                    if(!empty($bed_list))
                    {
                      $table.='<tr>';
                      $table.='<td width="100%"><div style="display:grid;grid-template-columns:repeat(6, 1fr);grid-gap:10px;margin-top:5px;text-align:left;">';
                      foreach($bed_list as $number_bed)
                      {
                        
                        if($number_bed->status==1 && $number_bed->ipd_is_deleted!=2 && !empty($number_bed->ipd_id))
                        {
                         
                          if(!empty($number_bed->bad_name))
                          {
                              $bad_name = $number_bed->bad_name;
                          }
                          else
                          {
                              $bad_name = $number_bed->bad_no;
                          }
                           $get_patient_detail=$this->ipd_room_list->get_patient_detail($number_bed->id,$number_bed->ipd_id);
                           //print_r($get_patient_detail[0]->patient_name); die;
                          $table.='';
                          $table .='<div style="background-color:green;   color:#FFF;   font-family:calibri;   font-size:13px;   border-radius:3px;padding:5px;"><p>Name : '.$get_patient_detail[0]->patient_name.'</p><p>IPD No. : '.$get_patient_detail[0]->ipd_no.'</p><p>Bed No. : '.$bad_name.'</p> </div>';
                           $table.=' ';
                        }
                        else
                        {
                          
                          
                          if(!empty($number_bed->bad_name))
                          {
                              $bad_name = $number_bed->bad_name;
                          }
                          else
                          {
                              $bad_name = $number_bed->bad_no;
                          }
                          $table .='<div  style="padding:5px; background-color:orange;   color:#000;   font-family:calibri;   font-size:13px;border-radius:3px; text-align:center;padding-top:40px;"><p>Bed No. :</p><p> '.$bad_name.'</p></div>';
                          

                        }
                        
                      }
                        
           $table.='</td>';

                      $table.='</tr>';
                    } 
                    
                    $table.='</tr>';
                    //$table.='</table>';
                    
                }
                

                
                $table.='</td></tr></tr>'; 

            }
            
            $table.='</table>'; 
            
        }

        
        echo $table;
               


          
  }

       

  public function select_all_bed_number()
  { 
        $post = $this->input->post();
        $room_id = $post['room_id'];
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('general/general_model');
        $table ='';
        $room_list = $this->ipd_room_list->get_room_list($room_id);

        $beds_total_count = $this->ipd_room_list->get_all_beds_counts(0,$room_id);
        $total_bed_in_hosp = $beds_total_count[0]->total_bad;

        $beds_count = $this->ipd_room_list->get_all_beds_counts(1,$room_id);
        $total_bed_filled = $beds_count[0]->total_bad;

        $beds_free_count = $this->ipd_room_list->get_all_beds_counts(2,$room_id);
        $total_bed_free = $beds_free_count[0]->total_bad;

    $table .='<div> <table align="right" cellpadding="0" cellspacing="0" border="0" width="200px" style="border:1px solid #EEE;margin-bottom:10px;">
         <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:red;"></div></td> <td><a href="javascript:void(0);"  onClick="return all_search_filled(0);">Total Bed '.$total_bed_in_hosp.'</a></td> </tr>

         <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:orange;"></div></td> <td> <a href="javascript:void(0);"  onClick="return all_search_filled(2);"> VACANT '.$total_bed_free.' </a></td> </tr> <tr><td style="padding:4px;"><div class="m_alert_orange_mark" style="background-color:green;"></div></td><td><a href="javascript:void(0);"  onClick="return all_search_filled(1);"> Full '.$total_bed_filled.'</a></td> </tr></table></div>';


        if((isset($room_list) && !empty($room_list)))
        {
            $table.='<table class="" style="width:100%;">';
            foreach($room_list as $particularlist)
            {  
                $table.='<tr style="">';
                $table.='<td style="text-align:center;font-weight:bold;padding:5px 0;"><big><u>'.$particularlist->room_category.'</u></big></td></tr><tr><td>';
                
                
                $number_room_list = $this->general_model->room_no_list($particularlist->id); 
                foreach ($number_room_list as $room_list) 
                {
                    $table.='<tr style="background-color:lightgray;">';
                    $table.='<td style="text-align:left;padding:4px;"><b>Room No.:'.$room_list->room_no.'</b></td></tr>';

                    $bed_list = $this->general_model->number_bed_all_list($particularlist->id,$room_list->id);
                    //echo "<pre>";print_r($bed_list);

                    if(!empty($bed_list))
                    {
                      $table.='<tr>';
                      $table.='<td width="100%"><div style="display:grid;grid-template-columns:repeat(6, 1fr);grid-gap:10px;margin-top:5px;text-align:left;">';
                      foreach($bed_list as $number_bed)
                      {
                        
                        if($number_bed->status==1 && $number_bed->ipd_is_deleted!=2 && !empty($number_bed->ipd_id))
                        {
                         
                          if(!empty($number_bed->bad_name))
                          {
                              $bad_name = $number_bed->bad_name;
                          }
                          else
                          {
                              $bad_name = $number_bed->bad_no;
                          }
                           $get_patient_detail=$this->ipd_room_list->get_patient_detail($number_bed->id,$number_bed->ipd_id);
                           //print_r($get_patient_detail[0]->patient_name); die;
                          $table.='';
                          $table .='<div style="background-color:green;   color:#FFF;   font-family:calibri;   font-size:13px;   border-radius:3px;padding:5px;"><p>Name : '.$get_patient_detail[0]->patient_name.'</p><p>IPD No. : '.$get_patient_detail[0]->ipd_no.'</p><p>Bed No. : '.$bad_name.'</p> <p><a href="javascript:void(0)" onclick="generate_discharge_bill('.$get_patient_detail[0]->id.','.$get_patient_detail[0]->patient_id.',1)" title="Discharge Bill" style="color: #fffaf0;"><i class="fa fa-database" aria-hidden="true"></i> Discharge Bill</a></p></div>';
                           $table.=' ';
                        }
                        else
                        {
                          
                          
                          if(!empty($number_bed->bad_name))
                          {
                              $bad_name = $number_bed->bad_name;
                          }
                          else
                          {
                              $bad_name = $number_bed->bad_no;
                          }
                          $table .='<div  style="padding:5px; background-color:orange;   color:#000;   font-family:calibri;   font-size:13px;border-radius:3px; text-align:center;padding-top:40px;"><p>Bed No. :</p><p> '.$bad_name.'</p></div>';
                          

                        }
                        
                      }
                        
           $table.='</td>';

                      $table.='</tr>';
                    } 
                    
                    $table.='</tr>';
                    //$table.='</table>';
                    
                }
                

                
                $table.='</td></tr></tr>'; 

            }
            
            $table.='</table>'; 
            
        }

        
        echo $table;
               


          
  }
  public function get_bed_to_room_list(){
        $room_data = $this->uri->uri_to_assoc();
        $data['page_title'] = 'Room Bed List';
        $room_to_bed_data = $this->ipd_room_list->get_bed_to_room_list($room_data);
        // echo "<pre>";
        // print_r($room_to_bed_data);
        // die;
        $data['bed_to_room_list'] = $this->format_room_to_bed_data($room_to_bed_data);
        $this->load->view('ipd_room_list/bed_to_room_list',$data);
  }
  public function format_room_to_bed_data($bed_to_room_data = array()){
         // print_r($bed_to_room_data);die;
          $table="";
          if(!empty($bed_to_room_data)){
               $bed_to_room_data_count = count($bed_to_room_data);
               for($i=0;$i<$bed_to_room_data_count;$i++){

                    // $table.='<tr><td>'.$bed_to_room_data[$i]['bed_name'].'</td><td>'.$bed_to_room_data[$i]['bed_charges'].'</td><td>'.$bed_to_room_data[$i]['nursing_charges'].'</td><td>'.$bed_to_room_data[$i]['rmo_charges'].'</td><td>'.$bed_to_room_data[$i]['panel_bed_charges'].'</td><td>'.$bed_to_room_data[$i]['panel_nursing_charges'].'</td><td>'.$bed_to_room_data[$i]['panel_rmo_charges'].'</td>';
                    $table.='<tr><td>'.$bed_to_room_data[$i]['bad_no'].'</td>';
                     $table.='<td>'.$bed_to_room_data[$i]['bad_name'].'</td>';
                    // if($bed_to_room_data[$i]['is_alloted']==0){
                         
                    //       $status = '<font color="red">Inactive</font>';
                        
                    // }else
                    // {
                    //     $status = '<font color="green">Active</font>';
                    // }
                    // $table.='<td>'.$status.'</td><td>'.$bed_to_room_data[$i]['created_date'].'</td><td><button name="edit_beds" id="edit_beds" class="btn-custom" onClick="edit_beds('.$bed_to_room_data[$i]['room_id'].','.$bed_to_room_data[$i]['bed_id'].')">Edit</button></td></tr>';

                      $table.='<td>'.$bed_to_room_data[$i]['created_date'].'</td><td><a onclick="return edit_beds('.$bed_to_room_data[$i]['room_id'].','.$bed_to_room_data[$i]['bed_id'].');" class="btn-custom" href="javascript:void(0)" style="623" title="Edit"><i class="fa fa-pencil"></i> Edit</a></td></tr>';
               }
          }else{
                $table = '<tr><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>';    
          }
          return $table;
     }

  public function edit_bed($id="")
  {
    unauthorise_permission('108','672');     
    if(isset($id) && !empty($id) && is_numeric($id))
      {     

        $result = $this->ipd_room_list->get_bed_by_id($id);
        $post= $this->input->post();
        $data['page_title'] = "Update Bed";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                    'data_id'=>$result['id'],
                                    'bad_no'=>$result['bad_no'],
                                    'room_no'=>$result['room_no'],
                                    'room_id'=>$result['room_id'],
                                    'bad_name'=>$result['bad_name']
                                  );  
     
    if(isset($post) && !empty($post))
        {   
         
             $this->ipd_room_list->bed_save();
                echo 1;
                return false;
                
            
               
        }
       $this->load->view('ipd_room_list/edit_ipd_bed',$data);       
      }
  }


  public function get_room_type_list()
  {
      $this->load->model('general/general_model'); 
        $data['room_type_list'] = $this->general_model->room_type_list();
       $dropdown = '<option value="">Select Room Type</option>'; 
      if(!empty($data['room_type_list']))
      {
        foreach($data['room_type_list'] as $room_type)
        {
           $dropdown .= '<option value="'.$room_type->id.'">'.ucfirst($room_type->room_category).'</option>';
        }
      } 
      echo $dropdown; 
  }


 
 public function select_room_number()
  {
         $this->load->model('general/general_model');
         $room_id= $this->input->post('room_id');
         $room_no_id= $this->input->post('room_no_id');
         if(!empty($room_id)){
           $data['number_rooms']= $this->general_model->room_no_list($room_id);
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


}
?>