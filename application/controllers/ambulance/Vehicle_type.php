<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_type extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		
        $this->load->model('general/general_model');
        $this->load->model('ambulance/vehicle_type_model','vehicle_type');
		    $this->load->library('form_validation');
    }

    
	public function index()
    {
            unauthorise_permission('348','2081');
            $this->session->unset_userdata('vehicle_search');
        $data['page_title'] = 'Ambulance Vehicle Type List'; 
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
        $this->load->model('general/general_model','general_model');
          $data['location_list'] = $this->general_model->location_list();
        $data['form_data'] = array('type'=>'','vehicle_type'=>'','owner_name'=>'','start_date'=>$start_date,'end_date'=>$end_date); 
        $this->load->view('ambulance/vehicle_type/list',$data);
    }
  
  public function ajax_list()
    { 
      unauthorise_permission('348','2081');
      $users_data = $this->session->userdata('auth_users');
      $sub_branch_details = $this->session->userdata('sub_branches_data');
      $parent_branch_details = $this->session->userdata('parent_branches_data');
      $list = $this->vehicle_type->get_datatables();
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vehicle_list) {
         // print_r($ipd_perticular);die;
            $no++;
            $row = array();
            if($vehicle_list->status==1)
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
            if($users_data['parent_id']==$vehicle_list->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vehicle_list->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $vehicle_list->type;
            $row[] = $vehicle_list->local_min_amount;
            $row[] = $vehicle_list->outstation_min_amount;
            
            $btnedit='';
            $btndelete='';
            $btnupload='';
            $btnview='';
          
            if($users_data['parent_id']==$vehicle_list->branch_id)
            {
              if(in_array('2083',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_vehicle_list('.$vehicle_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vehicle_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
             if(in_array('2084',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_driver_list('.$vehicle_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
                }
            }
          
             $row[] = $btnedit.$btndelete; 
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                       "recordsTotal" => $this->vehicle_type->count_all(),
                        "recordsFiltered" => $this->vehicle_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 

public function add($pid="")
    {
        unauthorise_permission('348','2082');
        $data['page_title'] = 'Add Ambulance Vehicle Type'; 
        $this->load->model('general/general_model'); 
	

        $post = $this->input->post();
		
	
		
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                'data_id'=>"", 
                                'local_min_distance'=>"",
                                'local_min_amount'=>"",
                                'local_per_km_charge'=>"",
                                'outstation_per_km_charge'=>"",
                                'outstation_per_km_charge'=>"",
                                'outstation_per_km_charge'=>"",
                                'type'=>"",
                                  );   


        if(isset($post) && !empty($post))
        {   
          
         $data['form_data'] = $this->_validate();
             if($this->form_validation->run() == TRUE)
             {
                $this->vehicle_type->save();
                echo 1;
                return false;
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
             
            }
        }
       $this->load->view('ambulance/vehicle_type/add',$data);       
    }
    

     public function edit($id="")
    {
	
     //unauthorise_permission('109','679');
     if(isset($id) && !empty($id) && is_numeric($id))
      {    

	
  
        $this->load->model('general/general_model'); 
         $data['location_list'] = $this->general_model->location_list();
        $result = $this->vehicle_type->get_by_id($id); 
        
		
        $data['page_title'] = "'Edit Ambulance Vehicle Type";  
        $post = $this->input->post();
        $dob=date('d-m-Y',strtotime($result['dob']));
        $data['form_error'] = ''; 
        $data['form_data'] = array(
          'data_id'=>$result['id'],        
                'local_min_distance'=>$result['local_min_distance'],
                'local_min_amount'=>$result['local_min_amount'],
                'local_per_km_charge'=>$result['local_per_km_charge'],
                'outstation_min_distance'=>$result['outstation_min_distance'],
                'outstation_min_amount'=>$result['outstation_min_amount'],
                'outstation_per_km_charge'=>$result['outstation_per_km_charge'],
                'type'=>$result['type'],
               );   
       
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vehicle_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/vehicle_type/add',$data);       
      }
    }
    
    
    
 private function _validate()
    {
        $post = $this->input->post();  
       
        $this->load->model('general/general_model'); 
      
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('type', 'Vehicle Type', 'trim|required'); 
 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                'data_id'=>$post['data_id'], 
                'type'=>$post['type'],
                'local_min_distance'=>$post['local_min_distance'],
                'local_min_amount'=>$post['local_min_amount'],
                'local_per_km_charge'=>$post['local_per_km_charge'],
                'outstation_min_distance'=>$post['outstation_min_distance'],
                'outstation_min_amount'=>$post['outstation_min_amount'],
                'outstation_per_km_charges'=>$post['outstation_per_km_charge'],
                ); 
             return $data['form_data'];
            
        }
     //   return $data['form_data'];   
    }
    
    
  function get_vandor_data($vendor_id="")
  {
        $data['vendor_list'] = $this->vehicle_type->vendor_list($vendor_id);
        //print_r($data['vendor_list']);die;
        $post = $this->input->post();
        $data['form_data'] = array(
	
	                            "vendor_code"=>$data['vendor_list'][0]->vendor_id,
								"name"=>$data['vendor_list'][0]->name,
								"mobile"=>$data['vendor_list'][0]->mobile,
								"email"=>$data['vendor_list'][0]->email,
								"address"=>$data['vendor_list'][0]->address,
								"address2"=>$data['vendor_list'][0]->address2,
								"address3"=>$data['vendor_list'][0]->address3,
								"vendor_gst"=>$data['vendor_list'][0]->vendor_gst,
                                 ); 
         
          //print_r($data['form_data']);die;
		  $json = json_encode($data['form_data'],true);
          echo $json;
     
  }		

    public function delete($id="")
    {
       //unauthorise_permission('109','680');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->vehicle_type->delete($id);
           $response = "Vehicle Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('109','680');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vehicle_type->deleteall($post['row_id']);
            $response = "Vehicle Types successfully deleted.";
            echo $response;
        }
    }

    public function vehicle_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('ID','Vehicle Type','Local Min Charge','Out Station Min Charge','Created Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
               
                $col++;
          }
          $list = $this->vehicle_type->search_report_data();

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $m=1;
               foreach($list as $reports)
               {
                   array_push($rowData,$m,$reports->type,$reports->local_min_amount,$reports->outstation_min_amount,date('d-M-Y',strtotime($reports->created_date)));
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
          header("Content-Disposition: attachment; filename=vehicle_type_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function pdf_vehicle()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->vehicle_type->search_report_data();
        $this->load->view('ambulance/vehicle_type/vehicle_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("vehicle_type_list_".time().".pdf");
    }

}
