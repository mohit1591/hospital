<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_opening_stock extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('medicine_opening_stock/medicine_opening_stock_model','medicine_opening_stock');
		$this->load->library('form_validation');
        $this->load->library('excel');
    }

    
	public function index()
    {
        unauthorise_permission(97,624);
        $data['page_title'] = 'Medicine opening stock list'; 
        $this->session->unset_userdata('stock_search');
        $this->load->view('medicine_opening_stock/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(97,624);
        $list = $this->medicine_opening_stock->get_datatables();  
        //print_r( $list);
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //print '<pre>'; print_r($list);die;
        //$row='';
        foreach ($list as $medicine_opening_stock) { 
            $no++;
            $row = array();
            
            ///// State name ////////
            $state = "";
            if(!empty($medicine_opening_stock->state))
            {
                $state = " ( ".ucfirst(strtolower($medicine_opening_stock->state))." )";
            }
            //////////////////////// 
            
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
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="medicine_opening_stock[]" class="checklist" value="'.$medicine_opening_stock->id.'">'.$check_script;
            
            $row[] = $medicine_opening_stock->medicine_name;
            //$row[] = $medicine_opening_stock->company_name;
            $row[] = $medicine_opening_stock->batch_no;
            $row[] = $medicine_opening_stock->bar_code;
              $qty_data = $this->medicine_opening_stock->get_batch_med_qty($medicine_opening_stock->id,$medicine_opening_stock->batch_no);
            $medicine_total_qty = $qty_data['total_qty'];
            if($medicine_opening_stock->min_alrt>=$qty_data['total_qty'])
            {
              $medicine_total_qty = '<div class="m_alert_green">'.$qty_data['total_qty'].'</div>';
            }
            
            
            
            $row[] = $medicine_total_qty;

              /////////// Start expire alert ////////
            $alert_days = get_setting_value('MEDICINE_EXPIRY_INTIMATION_DAY');
            $expire_timestamp="";
            $expire_alert_days="";
            if($medicine_opening_stock->expiry_date!="0000-00-00")
            {
                $expire_timestamp = strtotime($medicine_opening_stock->expiry_date)-(86400*$alert_days);
                $expire_alert_days = date('Y-m-d',$expire_timestamp);
            }
            //echo $expire_alert_days;
            $current_date = date('Y-m-d');
            //echo $expire_alert_days;
            /*if($medicine_opening_stock->expiry_date!="0000-00-00")
            {
                $expire_date = date('d-m-Y',strtotime($medicine_opening_stock->expiry_date));
                if($current_date>=$expire_alert_days)
                { 
                   $expire_date = '<div class="m_alert_red">'.$expire_date.'</div>';
                }
            }
            else
            {
                $expire_date="";
            }*/
            $expire_date = date('d-m-Y',strtotime($medicine_opening_stock->expiry_date));
            if($medicine_opening_stock->expiry_date!='0000-00-00' && $medicine_opening_stock->expiry_date!='00-00-0000' && $expire_date!='01-01-1970' && $expire_date!='00-00-0000' && $expire_date!='0000-00-00')
            {
                
                $dateTimestamp1 = strtotime($current_date);
                $dateTimestamp2 = strtotime($expire_date);
                
              if ($dateTimestamp1 > $dateTimestamp2)
              {
                $expire_date = '<div class="m_alert_red">'.$expire_date.'</div>';  
              }
              elseif($current_date>=$expire_alert_days)
              {
                $expire_date = '<div class="m_alert_orange">'.$expire_date.'</div>';  
              }
              else
              {
                $expire_date = $expire_date; 
              }
              
            }
            else
            {
              $expire_date = '<div class="m_alert_orange"></div>'; 
            }
            
            ////////// End Expire alert ////////////
            $row[] = $expire_date;
            $row[] = $medicine_opening_stock->min_alrt;
            $row[] = $medicine_opening_stock->new_mrp;
            $row[] = $medicine_opening_stock->med_purchase;
          
           // $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($medicine_opening_stock->created_date));  
            $users_data = $this->session->userdata('auth_users');
             $btn_edit = "";
            $btn_delete = "";
            $btn_view = "";
            $btn_permission = "";
           
            if($users_data['parent_id']==$medicine_opening_stock->branch_id){ 
                // if(in_array('3',$users_data['permission']['action']))
                // {
                if(isset($medicine_opening_stock->batch_no) && !empty($medicine_opening_stock->batch_no))
                {
                    $batch_no=$medicine_opening_stock->batch_no;
                }
                else
                {
                    $batch_no=0;;
                }
                
                if(in_array('626',$users_data['permission']['action']))
                {
                   $btn_edit = ' <a onClick="return edit_opening_stock('.$medicine_opening_stock->m_eid.','.$medicine_opening_stock->stock_id.');" class="btn-custom" href="javascript:void(0)" style="'.$medicine_opening_stock->id.'" title="Edit"><input type="hidden" id="batch_no_'.$medicine_opening_stock->stock_id.'" value="'.$batch_no.'" /><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                
                // }

                // if(in_array('4',$users_data['permission']['action']))
                // {
                    // $btn_delete =' <a class="btn-custom" onClick="return delete_opening_stock('.$medicine_opening_stock->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                // }
            }
            $row[] = $btn_edit.$btn_delete;   
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_opening_stock->count_all(),
                        "recordsFiltered" => $this->medicine_opening_stock->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }
     function check_bar_code()
    {
      $bar_code= $this->input->post('bar_code');
      $mbid= $this->input->post('mbid');
      $return= $this->medicine_opening_stock->check_bar_code($bar_code,$mbid);
      if($return==2)
      {
        echo '1';exit;
      }
      else
      {
        echo '0';exit;
      }
    }
     public function add()
    {
         unauthorise_permission(97,625);
    
        //print_r($result);die;
        $reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
        $data['unit_list'] = $this->medicine_opening_stock->unit_list();
        $data['rack_list'] = $this->medicine_opening_stock->rack_list();
        $data['manuf_company_list'] = $this->medicine_opening_stock->manuf_company_list();
        $data['page_title'] = "Add medicine Opening Stock";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                    "data_id"=>'', 
                                    "stock_id"=>'',
                                    "medicine_code"=>$reg_no,
                                    "medicine_name"=>'',
                                    "conversion"=>'',
                                    "manuf_company"=>'',
                                    "mrp"=>'',
                                    "unit1_quantity"=>"0",
                                    "unit2_quantity"=>"",
                                    "batch_no"=>'',
                                    "bar_code"=>'',
                                    "expiry_date"=>'',
                                   "purchase_rate"=>'',
                                    // "status"=>'1'
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $response = $this->medicine_opening_stock->save();
                if(!empty($response))
                {
                     echo 2;
                    return false;  
                }
                else
                {
                    echo 1;
                    return false; 
                }
               
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                //print_r($data['form_error']);
                //die;
            }     
        }

     
       $this->load->view('medicine_opening_stock/add',$data);       
      }
    
   public function edit($id="",$batch_no="")
    {
       unauthorise_permission(97,626);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->medicine_opening_stock->get_by_id($id,$batch_no); 
        
        $reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
        $data['unit_list'] = $this->medicine_opening_stock->unit_list();
        $data['rack_list'] = $this->medicine_opening_stock->rack_list();
        $data['manuf_company_list'] = $this->medicine_opening_stock->manuf_company_list();
        $data['page_title'] = "Update medicine Opening Stock";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
            "data_id"=>$result['id'], 
            "stock_id"=>$result['stock_id'], 
            "medicine_code"=>$result['medicine_code'],
            "medicine_name"=>$result['medicine_name'],
            "conversion"=>$result['conversion'],
            "manuf_company"=>$result['manuf_company'],
            "mrp"=>$result['new_mrp'],
            "purchase_rate"=>$result['med_purchase'],
            "unit1_quantity"=>$result['unit1'],
            "unit2_quantity"=>$result['unit2'],
            "batch_no"=>$result['batch_no'],
            "bar_code"=>$result['bar_code'],
            "total_quantity"=>$result['total_quantity'], // Added By Nitin Sharma
           "expiry_date"=>date('d-m-Y',strtotime($result['expiry_date'])),
        );  
       
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_opening_stock->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

      
       $this->load->view('medicine_opening_stock/add',$data);       
      }
    }
     
    private function _validate()
    {
        $field_list = mandatory_section_field_list(5);
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_name', 'medicine name', 'trim|required');
        $this->form_validation->set_rules('conversion', 'conversion', 'trim|required');
        $this->form_validation->set_rules('mrp', 'mrp', 'trim|required');
        $this->form_validation->set_rules('expiry_date', 'expiry date', 'trim|required');
        $this->form_validation->set_rules('unit2_quantity', 'quantity', 'trim|required|callback_check_qty');
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(10); 
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'], 
                                    "stock_id"=>$_POST['stock_id'],
                                    "medicine_code"=>$post['medicine_code'],
                                    "medicine_name"=>$_POST['medicine_name'],
                                    "conversion"=>$_POST['conversion'],
                                    "manuf_company"=>$_POST['manuf_company'],
                                    "mrp"=>$_POST['mrp'],
                                    "batch_no"=>$_POST['batch_no'],
                                    "bar_code"=>$_POST['bar_code'],
                                    "unit1_quantity"=>$_POST['unit1_quantity'],
                                    "unit2_quantity"=>$_POST['unit2_quantity'],
                                    "purchase_rate"=>$_POST['purchase_rate'],
                                  );  
            if(empty($_POST['data_id']))
            {
                 $data['form_data']['expiry_date'] = date('d-m-Y',strtotime($_POST['expiry_date']));
            }
            return $data['form_data'];
        }   
    }

public function check_qty()
{
    $post = $this->input->post();
    $response = true;
    if(!empty($post['data_id']))
    {
            $debit = ($post['unit1_quantity']*$post['conversion'])+$post['unit2_quantity'];
            $qty_data = $this->medicine_opening_stock->get_batch_med_validate_qty($post['data_id'],$post['batch_no']);
            //echo $qty_data['total_qty'];
            $new_qty = $qty_data['total_qty']+$debit; 
		    if($new_qty<0)
		    {
		      $this->form_validation->set_message('check_qty', 'The stock qunatity become '.$qty_data['total_qty'].' Kindly add more quantity.');
                $response = false;
		    }
		    else
		    {
		        $response = true;
		    }
    }
    
    return $response;
}

 
public function medicine_opening_stock_excel()
{
        unauthorise_permission(97,627);
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
       
        $fields = array('Medicine Name(*)','Batch No.','Barcode','MRP(*)','Purchase Rate','Unit2 Qty(*)','Conversion','Expiry Date(yyyy-mm-dd)');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $rowData = array();
        $data= array();
      
        // Fetching the table data
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel charset=UTF-8');
       header("Content-Disposition: attachment; filename=medicine_opening_stock_report_".time().".xls");  
        header("Pragma: no-cache"); 
        header("Expires: 0");
      
            ob_end_clean();
            $objWriter->save('php://output');
       
    }

    public function medicine_opening_stock_csv()
    {
        unauthorise_permission(97,624);
        $list = $this->medicine_opening_stock->search_report_data();
       // print_r( $list);die;
        $columnHeader = '';  
        $columnHeader = "Medicine Name." . "\t" . "Packing" . "\t" . "Rack Number" . "\t" . "MRP" . "\t" . "Purchase Rate". "\t" . "Batch No". "\t" . "Expiry Date". "\t" . "Min Alert". "\t" . "Quantity";
        $setData = '';
        if(!empty($list))
        {
//print '<pre>';print_r($list);die;
           
            $rowData = "";
            foreach($list as $reports)
            {
                $alert_days = get_setting_value('MEDICINE_EXPIRY_INTIMATION_DAY');
                $expire_timestamp = strtotime($reports->expiry_date)-(86400*$alert_days);
                $expire_alert_days = date('Y-m-d',$expire_timestamp);
                //echo $expire_alert_days;
                $current_date = date('Y-m-d');
                //echo $expire_alert_days;
                $expire_date = date('d-m-Y',strtotime($reports->expiry_date));
                if($current_date>=$expire_alert_days)
                { 
                $expire_date =$expire_date;
                }

              $qty_data = $this->medicine_opening_stock->get_batch_med_qty($reports->id,$reports->batch_no);
              $medicine_total_qty = $qty_data['total_qty'];
              if($reports->min_alrt>=$qty_data['total_qty'])
              {
                $medicine_total_qty = $qty_data['total_qty'];
              }
                $rowData = $reports->medicine_name . "\t" . $reports->packing . "\t" . $reports->rack_no . "\t" . $reports->mrp . "\t". $reports->purchase_rate. "\t". $reports->batch_no. "\t". $expire_date. "\t". $reports->min_alrt. "\t".$medicine_total_qty; 
                $setData .= trim($rowData) . "\n";    
            }
        }
        //echo $setData;die;
        header("Content-type: application/octet-stream");  
        header("Content-Disposition: attachment; filename=medicine_opening_stock_report_".time().".csv");  
        header("Pragma: no-cache");  
        header("Expires: 0");  

        echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
    }

    public function pdf_medicine_opening_stock()
    {    
        // unauthorise_permission(63,580);
        $data['print_status']="";
        $data['data_list'] = $this->medicine_opening_stock->search_report_data();
        $this->load->view('medicine_opening_stock/medicine_opening_stock_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_opening_stock_report_".time().".pdf");
    }
    public function print_medicine_opening_stock()
    {    
      // unauthorise_permission(63,581);
      $data['print_status']="1";
      $data['data_list'] = $this->medicine_opening_stock->search_report_data();
      $this->load->view('medicine_opening_stock/medicine_opening_stock_report_html',$data); 
    }
  
    public function delete($id="")
    {
        // unauthorise_permission(63,124);
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_opening_stock->delete($id);
           $response = "Medicine entry successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        // unauthorise_permission(63,124);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_opening_stock->deleteall($post['row_id']);
            $response = "Medicine entry successfully deleted.";
            echo $response;
        }
    }

   

    ///// employee Archive Start  ///////////////
    public function archive()
    {
       // unauthorise_permission(63,126);
        $data['page_title'] = 'Medicine entry archive list';
        $this->load->helper('url');
        $this->load->view('medicine_opening_stock/archive',$data);
    }

    public function archive_ajax_list0()
    {
        //unauthorise_permission(63,126);
        $this->load->model('medicine_opening_stock/medicine_opening_stock_archive_model','medicine_opening_stock_archive'); 

        $list = $this->medicine_opening_stock_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine_opening_stock) { 
            $no++;
            $row = array();
            if($medicine_opening_stock->status==1)
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

            $row[] = '<input type="checkbox" name="medicine_opening_stock[]" class="checklist" value="'.$medicine_opening_stock->id.'">'.$check_script;
            $rack_name= rack_list($medicine_opening_stock->rack_no);
            $row[] = $medicine_opening_stock->medicine_name;
            $row[] = $medicine_opening_stock->packing;
            $row[] = $rack_name[0]->rack_no;
            $row[] = $medicine_opening_stock->mrp;
            $row[] = $medicine_opening_stock->purchase_rate;
            $row[] = $medicine_opening_stock->discount;
            $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($medicine_opening_stock->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           // if(in_array('128',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_medicine_opening_stock('.$medicine_opening_stock->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           // }
           // if(in_array('127',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$medicine_opening_stock->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           // }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_opening_stock_archive->count_all(),
                        "recordsFiltered" => $this->medicine_opening_stock_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       // unauthorise_permission(63,128);
        $this->load->model('medicine_opening_stock/medicine_opening_stock_archive_model','medicine_opening_stock_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_opening_stock_archive->restore($id);
           $response = "Medicine entry successfully restore in medicine entry list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       // unauthorise_permission(63,128);
        $this->load->model('medicine_opening_stock/medicine_opening_stock_archive_model','medicine_opening_stock_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_opening_stock_archive->restoreall($post['row_id']);
            $response = "Medicine entry successfully restore in medicine entry list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission(63,127);
        $this->load->model('medicine_opening_stock/medicine_opening_stock_archive_model','medicine_opening_stock_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_opening_stock_archive->trash($id);
           $response = "Medicine entry successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       // unauthorise_permission(63,127);
        $this->load->model('medicine_opening_stock/medicine_opening_stock_archive_model','medicine_opening_stock_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_opening_stock->trashall($post['row_id']);
            $response = "Medicine entry successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

public function advance_search()
      {
          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $data['unit_list'] = $this->medicine_opening_stock->unit_list();
          $data['form_data'] = array(
                                    "start_date"=>'',
                                    "batch_no"=>"",
                                    "medicine_name"=>"",
                                    "medicine_code"=>"", 
                                    "medicine_company"=>"",
                                    "qty_from"=>"",
                                    "qty_to"=>"",
                                    "min_alert"=>"",
                                    "price_to_mrp"=>"",
                                    "price_from_mrp"=>"",
                                    "price_to_purchase"=>"",
                                    "price_from_purchase"=>"",
                                    "rack_no"=>"",
                                    "packing"=>"",
                                    "end_date"=>"",
                                    "expiry_to"=>"",
                                    "expiry_from"=>"",
                                    "unit1"=>"",
                                    "unit2"=>""
                                   
                                    );
          if(isset($post) && !empty($post))
          {
              $this->session->set_userdata('stock_search', $post);
          }
          $purchase_search = $this->session->userdata('stock_search');
          if(isset($purchase_search) && !empty($purchase_search))
          {
              $data['form_data'] = $purchase_search;
          }
          $this->load->view('medicine_opening_stock/advance_search',$data);
   }

    public function reset_search()
    {
        $this->session->unset_userdata('stock_search');
    }
   
    public function medicine_opening_stock_import_excel()
    {
        unauthorise_permission(97,628);
        $data['page_title'] = 'Import medicine opening stock excel';
        $arr_data = array();
        $header = array();
        $path='';
       

       
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['opening_stock']) || $_FILES['opening_stock']['error']>0)
            {
               
               $this->form_validation->set_rules('opening_stock', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'medicine_opening_stock/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('opening_stock')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }
                if(!empty($arr_data))
                {
                    $arrs_data = array_values($arr_data);
                    $total_opening_stock_medicines = count($arrs_data);
                    // print_r($arr_data);
                    // die;
                    $array_keys = array('medicine_name','batch_no','bar_code','mrp','purchase_rate','unit2_qty','conversion','expiry_date');
                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H');
                    $medicine_data = array();
                    $medicine_all_data = array();
                    $j=0;
                    $m=0;
                    $data='';
                    $medicines_all_data= array();
                    for($i=0;$i<$total_opening_stock_medicines;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $medicines_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $medicines_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                    $this->medicine_opening_stock->save_all_opening_stock($medicines_all_data);
                }
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {

                $data['form_error'] = validation_errors();
                

            }
        }

        $this->load->view('medicine_opening_stock/import_medicine_opening_stock_excel',$data);
    } 
    function get_medicine_name($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->medicine_opening_stock->get_medicine_name($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
    function check_medicine_conversion()
    {
      $medicine_id= $this->input->post('medicine_id');
      $conversion= $this->input->post('conversion');
      $batch_no = $this->input->post('batch_no');
      $medicine_name = $this->input->post('medicine_name');
      $result = $this->medicine_opening_stock->get_medicine_conversion($medicine_id,$batch_no); 
      //echo "<pre>"; print_r($result); exit;
      $conversion_one = $result['conversion'];
      $medicine_name_one = $result['medicine_name'];
      $response=array();
      if(strcmp(strtolower($medicine_name), strtolower($medicine_name_one))!== 0)
      {
      	
      	  	$response['sucess']='0';
      	  	//$response['dss']='ddd';

      }
      else
      {
      	if(trim($conversion_one)==trim($conversion))
	      {
	        $response['sucess']='0';
	      }
	      else
	      {
	      	$response['sucess']='1';
	        $response['conversion']=$conversion_one;
	      }
	      
      	
	  }
      
      echo json_encode($response,true);
    }
}
