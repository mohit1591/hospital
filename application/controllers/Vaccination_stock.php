<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_stock extends CI_Controller {
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
    $this->load->model('vaccination_stock/vaccination_stock_model','vaccination_stock');
    $this->load->library('form_validation');
    }

    
  public function index()
    {
        unauthorise_permission(177,1103);
        $data['page_title'] = 'Vaccination stock list'; 
        $this->session->unset_userdata('stock_search');
        $data['form_data']=array('start_date'=>'','end_date'=>'','batch_no'=>'');
        $this->load->view('vaccination_stock/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(177,1103);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->vaccination_stock->get_datatables();  
        //print '<pre>';print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $vaccination_stock) { 
            $no++;
            $row = array();
            if($vaccination_stock->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($vaccination_stock->state))
            {
                $state = " ( ".ucfirst(strtolower($vaccination_stock->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }   

           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            //v_id
               $qty_data = $this->vaccination_stock->get_batch_med_qty($vaccination_stock->v_id,$vaccination_stock->batch_no);
            if($qty_data['total_qty']>0){
            $row[] = '<input type="checkbox" name="'.$vaccination_stock->batch_no.'" class="checklist" value="'.$vaccination_stock->id.'">';
            }else{
             $row[] ='';   
            }
            $rack_name= rack_list($vaccination_stock->rack_no);
            $row[] = $vaccination_stock->vaccination_name;
            $row[] = $vaccination_stock->company_name;
            $row[] = $vaccination_stock->packing;
            $row[] = $vaccination_stock->batch_no;
            $row[] = $vaccination_stock->bar_code;
         
            $medicine_total_qty = $qty_data['total_qty'];
            if($vaccination_stock->min_alrt>=$qty_data['total_qty'])
            {
            $medicine_total_qty = '<div class="m_alert_red">'.$qty_data['total_qty'].'</div>';
            }
             if($qty_data['total_qty']>=0){
             $row[] = $medicine_total_qty;
             }else{
             $row[]=0;
             }

            /////////// Start expire alert ////////
            $alert_days = get_setting_value('MEDICINE_EXPIRY_INTIMATION_DAY');
            $expire_timestamp = strtotime($vaccination_stock->expiry_date)-(86400*$alert_days);
            $expire_alert_days = date('Y-m-d',$expire_timestamp);
            //echo $expire_alert_days;
            $current_date = date('Y-m-d');
            //echo $expire_alert_days;
            $expire_date = date('d-m-Y',strtotime($vaccination_stock->expiry_date));
            //strtotime($medicine_sess[$vaccine->id.'.'.$vaccine->batch_no]["manuf_date"]) < 31536000
            if(($current_date>=$expire_alert_days || $vaccination_stock->expiry_date!="0000-00-00") && $expire_date!='01-01-1970')
            { 
               $expire_date = '<div class="m_alert_orange">'.$expire_date.'</div>';
            }else{
               $expire_date = '<div class="m_alert_orange"></div>';  
            }
            $row[] = $expire_date;

          $row[] = $vaccination_stock->rack_nu;
          $row[] = $vaccination_stock->min_alrt;
          //$row[] = $vaccination_stock->per_pic_rate;
           
            // $row[] = $vaccination_stock->mrp;
            // $row[] = $vaccination_stock->purchase_rate;
            
            
            ////////// End Expire alert ////////////
            
            
            //$row[] = $status; 
            if(date('d-M-Y',strtotime($vaccination_stock->stock_created_date))=='01-Jan-1970'){
              $row[]='';

            }else{
              $row[] = date('d-M-Y',strtotime($vaccination_stock->stock_created_date));
            }
          
            $btn_history="";
             if($users_data['parent_id']==$vaccination_stock->branch_id){
                if(!empty($vaccination_stock->batch_no))
                {
                  $batchno = $vaccination_stock->batch_no;
                }
                else
                {
                  $batchno = 0;
                }
                    $btn_history = ' <a class="btn-custom" href="'.base_url('vaccination_stock/medicine_allot_history?id='.$vaccination_stock->id.'&batch_no='.$batchno.'&type=1').'" style="'.$vaccination_stock->id.'" title="Edit"><i class="fa fa-history"></i> History</a>';
                     
             }  
             $row[] = $btn_history;
           
          
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_stock->count_all(),
                        "recordsFiltered" => $this->vaccination_stock->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }
public function vaccination_stock_excel()
    {
        unauthorise_permission(177,1104);
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vaccination Name','Vaccination Company','Packing','Batch No.','Quantity','Expiry Date','Rack No.','Min Alert','Create Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
               $col++;
          }
          $list =$this->vaccination_stock->search_report_data();
          //print_r($list);die;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {

                $alert_days = get_setting_value('MEDICINE_EXPIRY_INTIMATION_DAY');
                $expire_timestamp = strtotime($reports->expiry_date)-(86400*$alert_days);
                $expire_alert_days = date('Y-m-d',$expire_timestamp);
                //echo $expire_alert_days;
                $current_date = date('Y-m-d');
                //echo $expire_alert_days;
                $expire_date = date('d-m-Y',strtotime($reports->expiry_date));
                if($current_date>=$expire_alert_days && $reports->expiry_date!="0000-00-00")
                { 
                $expire_date =$expire_date;
                }else{
                  $expire_date='';  
                }



              $qty_data = $this->vaccination_stock->get_batch_med_qty($reports->id,$reports->batch_no);
              $medicine_total_qty = $qty_data['total_qty'];
              if($reports->min_alrt>=$qty_data['total_qty'])
              {
                $medicine_total_qty = $qty_data['total_qty'];
              }
                    if($qty_data['total_qty']>=0){
                        $medicine_total_qty= $medicine_total_qty;
                        }else{
                        $medicine_total_qty=0;
                    }
                    
                    array_push($rowData,$reports->vaccination_name,$reports->company_name,$reports->packing,$reports->batch_no,$medicine_total_qty,$expire_date,$reports->rack_nu,$reports->min_alrt,date('d-M-Y H:i A',strtotime($reports->stock_created_date)));
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
          header("Content-Disposition: attachment; filename=vaccination_stock_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function vaccination_stock_csv()
    {
        unauthorise_permission(177,1105);
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Vaccination Name','Vaccination Company','Packing','Batch No.','Quantity','Expiry Date','Rack No.','Min Alert','Create Date');
        $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->vaccination_stock->search_report_data();
        
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
               
           $i=0;
           foreach($list as $reports)
           {
                $alert_days = get_setting_value('MEDICINE_EXPIRY_INTIMATION_DAY');
                $expire_timestamp = strtotime($reports->expiry_date)-(86400*$alert_days);
                $expire_alert_days = date('Y-m-d',$expire_timestamp);
                //echo $expire_alert_days;
                $current_date = date('Y-m-d');
                //echo $expire_alert_days;
                $expire_date = date('d-m-Y',strtotime($reports->expiry_date));
                if($current_date>=$expire_alert_days && $reports->expiry_date!="0000-00-00")
                {  
                    $expire_date =$expire_date;
                }else{
                    $expire_date ='';  
                }
                $qty_data = $this->vaccination_stock->get_batch_med_qty($reports->id,$reports->batch_no);
                $medicine_total_qty = $qty_data['total_qty'];
                if($reports->min_alrt>=$qty_data['total_qty'])
                {
                    $medicine_total_qty = $qty_data['total_qty'];
                }
                if($qty_data['total_qty']>=0){
                    $medicine_total_qty= $medicine_total_qty;
                    }else{
                    $medicine_total_qty=0;
                }
                
                  array_push($rowData,$reports->vaccination_name,$reports->company_name,$reports->packing,$reports->batch_no,$medicine_total_qty,$expire_date,$reports->rack_nu,$reports->min_alrt,date('d-M-Y H:i A',strtotime($reports->stock_created_date)));
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
        header("Content-Disposition: attachment; filename=vaccination_stock_report_".time().".csv");  
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
      
    }

    public function pdf_vaccination_stock()
    {    
        unauthorise_permission(177,1106);
        $data['print_status']="";
        $data['data_list'] = $this->vaccination_stock->search_report_data();
        $this->load->view('vaccination_stock/vaccination_stock_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("vaccination_stock_report_".time().".pdf");
    }
    public function print_vaccination_stock()
    {    
      unauthorise_permission(177,1107);
      $data['print_status']="1";
      $data['data_list'] = $this->vaccination_stock->search_report_data();
      $this->load->view('vaccination_stock/vaccination_stock_report_html',$data); 
    }
  
    public function delete($id="")
    {
        // unauthorise_permission(63,124);
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_stock->delete($id);
           $response = "Vaccination entry successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        // unauthorise_permission(63,124);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_stock->deleteall($post['row_id']);
            $response = "Vaccination entry successfully deleted.";
            echo $response;
        }
    }

   

    ///// employee Archive Start  ///////////////
    public function archive()
    {
       // unauthorise_permission(63,126);
        $data['page_title'] = 'Vaccination entry archive list';
        $this->load->helper('url');
        $this->load->view('vaccination_stock/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission(63,126);
        $this->load->model('vaccination_stock/vaccination_stock_archive_model','vaccination_stock_archive'); 

        $list = $this->vaccination_stock_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_stock) { 
            $no++;
            $row = array();
            if($vaccination_stock->status==1)
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

            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                  

            $row[] = '<input type="checkbox" name="vaccination_stock[]" class="checklist" value="'.$vaccination_stock->id.'">';
            $rack_name= rack_list($vaccination_stock->rack_no);
            $row[] = $vaccination_stock->vaccination_name;
            $row[] = $vaccination_stock->packing;
            $row[] = $rack_name[0]->rack_no;
            $row[] = $vaccination_stock->mrp;
            $row[] = $vaccination_stock->purchase_rate;
            $row[] = $vaccination_stock->discount;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($vaccination_stock->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           // if(in_array('128',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_vaccination_stock('.$vaccination_stock->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           // }
           // if(in_array('127',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$vaccination_stock->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           // }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_stock_archive->count_all(),
                        "recordsFiltered" => $this->vaccination_stock_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       // unauthorise_permission(63,128);
        $this->load->model('vaccination_stock/vaccination_stock_archive_model','vaccination_stock_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_stock_archive->restore($id);
           $response = "Vaccination entry successfully restore in vaccine entry list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       // unauthorise_permission(63,128);
        $this->load->model('vaccination_stock/vaccination_stock_archive_model','vaccination_stock_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_stock_archive->restoreall($post['row_id']);
            $response = "Vaccination entry successfully restore in vaccine entry list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission(63,127);
        $this->load->model('vaccination_stock/vaccination_stock_archive_model','vaccination_stock_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_stock_archive->trash($id);
           $response = "Vaccination entry successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       // unauthorise_permission(63,127);
        $this->load->model('vaccination_stock/vaccination_stock_archive_model','vaccination_stock_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_stock->trashall($post['row_id']);
            $response = "Vaccination entry successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

public function advance_search()
      {
          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          
         
          $data['unit_list'] = $this->vaccination_stock->unit_list();
          $data['form_data'] = array(
                                    "start_date"=>'',
                                    "batch_no"=>"",
                                    "type"=>'',
                                    "vaccination_name"=>"",
                                    "vaccination_code"=>"", 
                                    "vaccination_company"=>"",
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
                                    "branch_ids"=>"",
                                    "expiry_from"=>"",
                                    "unit1"=>"",
                                    "unit2"=>""
                                   
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('stock_search', $marge_post);
          }
          $purchase_search = $this->session->userdata('stock_search');
          if(isset($purchase_search) && !empty($purchase_search))
          {
              $data['form_data'] = $purchase_search;
          }
          $this->load->view('vaccination_stock/advance_search',$data);
   }

    public function reset_search()
    {
        $this->session->unset_userdata('stock_search');
    }
     public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
            $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name="sub_branch_id"><option value="">Select</option></option>';
            if(!empty($sub_branch_details)){
                $i=0;
                foreach($sub_branch_details as $key=>$value){
                    $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                    $i = $i+1;
                }
               
            }
            $dropdown.='</select>';
            echo $dropdown; 
        }
         
       
    }
     public function allotement_to_branch()
     {
         unauthorise_permission(177,1108);
         $post = $this->input->post();

         $medicine_data = $this->session->userdata('alloted_vaccine_ids');
         //print_r($medicine_data);die;
         // $medicine_batch = $this->session->userdata('alloted_medicine_batch_nos');
         if(!empty($medicine_data))
         {
            $medicine_data = array();
            $this->session->set_userdata('alloted_vaccine_ids',$medicine_data);
         }
        
         if(isset($post) && !empty($post))
         {
               $medicine_data = array();
               $i=0;
               foreach ($post['vaccine_ids'] as $vaccine_id) {
                    
                        $medicine_data[$i]['vaccine_id'] = $post['vaccine_ids'][$i];
                        $medicine_data[$i]['batch_no'] = $post['batch_no'][$i];
                    
                    $i++;
               }
              
 
              $this->session->set_userdata('alloted_vaccine_ids', $medicine_data);
             
         } 
        
         $data['page_title'] = 'Vaccination Allotment';
         
         $medicine_list = $this->vaccination_stock->get_medicine_list();
        

         $row='';
         $i=1;
         $total_num = count($medicine_list);
         if(!empty($medicine_list))
         {
               foreach($medicine_list as $medicine_data)
               {
                        $qty_data = $this->vaccination_stock->get_batch_med_qty($medicine_data['v_id'],$medicine_data['batch_no']); 
                   
                    $row.= '<tr><td align="center">'.$i.'<input type="hidden" name="vaccine['.$medicine_data['id'].'][vid]" class="medicinechecklist" value="'.$medicine_data['id'].'"></td><td>'.$medicine_data['vaccination_name'].'</td><td>'.$medicine_data['vaccination_code'].'</td><td>'.$medicine_data['batch_no'].'<input type="hidden" name="vaccine['.$medicine_data['id'].'][batch_no]" class="medicinechecklist" value="'.$medicine_data['batch_no'].'"></td><td><input type="text" id="qty_'.$medicine_data['id'].'" name="vaccine['.$medicine_data['id'].'][qty]" data-qty="'.$qty_data['total_qty'].'" data-id="'.$medicine_data['id'].'" value = "'.$qty_data['total_qty'].'" onblur="check_max_qty(this);"/><p id="msg_'.$medicine_data['id'].'" style="color:red;"></p></td></tr>';
                    $i++;
               }
          }else{
               $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
          }
          $data['medicine_list'] = $row;
       

        
         $this->load->view('vaccination_stock/allot_to_branch',$data);
     }
     public function allot_medicine_to_branch()
     {
         unauthorise_permission(177,1108);
          $post = $this->input->post();

          $users_data = $this->session->userdata('auth_users');
          $data['page_title'] = 'Vaccination Allotment';
           $data['medicine_list'] = '';
          if(isset($post) && !empty($post))
          {
              if($users_data['users_role']=='2')
              {

                     $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                     $this->form_validation->set_rules('sub_branch_id', 'branch', 'trim|required');
                    
                     if($this->form_validation->run() == TRUE)
                     {
                    
                          
                            $this->vaccination_stock->allot_medicine_to_branch();
                            echo 1;
                            return false;
                     }
                    
                    else
                    {
                       
                         // echo "hello";
                         // print_r($this->session->userdata('alloted_vaccine_ids'));
                         // die;
                         $medicine_list = $this->vaccination_stock->get_medicine_list();

                         $row='';
                         $i=0;
                         $total_num = count($medicine_list);
                         if(!empty($medicine_list))
                         {
                              foreach($medicine_list as $medicine_data)
                              {
                                   $qty_data = $this->vaccination_stock->get_batch_med_qty($medicine_data['v_id'],$medicine_data['batch_no']);
                                   $check_script='';
                                   if($i==$total_num)
                                   {
                                        // $check_script = 
                                        //      "<script>$('#getmedicineselectAll').on('click', function () { 
                                        //           if ($(this).hasClass('allChecked')) {
                                        //                          $('.medicinechecklist').prop('checked', false);
                                        //           } else {
                                        //           $('.medicinechecklist').prop('checked', true);
                                        //           }
                                        //           $(this).toggleClass('allChecked');
                                        //      })</script>";
                                   }
                                   $row.= '<tr><td align="center">'.$i.'<input type="hidden" name="vaccine['.$medicine_data['id'].'][vid]" class="medicinechecklist" value="'.$medicine_data['id'].'"></td><td>'.$medicine_data['vaccination_name'].'</td><td>'.$medicine_data['vaccination_code'].'</td><td>'.$medicine_data['batch_no'].'<input type="hidden" name="vaccine['.$medicine_data['id'].'][batch_no]" class="medicinechecklist" value="'.$medicine_data['batch_no'].'"></td><td><input type="text" id="qty_'.$medicine_data['id'].'" name="vaccine['.$medicine_data['id'].'][qty]" data-qty="'.$qty_data['total_qty'].'" data-id="'.$medicine_data['id'].'" value = "'.$qty_data['total_qty'].'" onblur="check_max_qty(this);"/><p id="msg_'.$medicine_data['id'].'" style="color:red;"></p></td></tr>';
                                   $i++;
                              }
                         }else{
                              $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
                         }
                         $data['medicine_list'] = $row;
                         $data['form_error'] = validation_errors(); 
                      
                    }

               }
          }


             //print_r($data['medicine_list']);die;
          
          $this->load->view('vaccination_stock/allot_to_branch',$data);
     }
     public function medicine_allot_history($id='')
     {
          $data['id'] = $this->input->get('id');
          $data['batch_no'] = $this->input->get('batch_no');
          $data['type'] = $this->input->get('type');
          $data['page_title'] = 'Vaccination Allot History List';
          $this->load->view('vaccination_stock/medicine_allot_history',$data);
          // $this->load->view('vaccination_stock/medicine_allot_history',$data);
     }
    public function medicine_allot_history_ajax()
    {  
        $post = $this->input->post();
        
        unauthorise_permission(177,1103);
        $users_data = $this->session->userdata('auth_users');
         $this->load->model('medicine_entry/medicine_entry_model'); 

         $medicine_datas=$this->medicine_entry_model->get_by_id($post['vaccine_id']);

        $list = $this->vaccination_stock->get_medicine_allot_history_datatables($post['vaccine_id'],$post['batch_no'],$post['type'],$medicine_datas['branch_id']);  
      
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';

        //print_r($list);die;
        foreach ($list as $vaccination_stock) 
        { 
            $no++;
            $row = array();
            $state = "";
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
            if($post['type']==5)
            {
              $row[] = $vaccination_stock->branch_name;
              $row[] = $vaccination_stock->vaccination_code;
              $row[] = $vaccination_stock->vaccination_name;
              $row[] = $vaccination_stock->company_name;
              $row[] = $vaccination_stock->quantity;
              $row[] = date('d-M-Y',strtotime($vaccination_stock->created_date));
            
            }
            else
            {
              $row[] = $vaccination_stock->vaccination_code;
              $row[] = $vaccination_stock->vendor_name;
              $row[] = $vaccination_stock->vaccination_name;
              $row[] = $vaccination_stock->company_name;
              $row[] = $vaccination_stock->batch_no;
              $row[] = $vaccination_stock->bar_code;
              $row[] = $vaccination_stock->qty;
              $row[] = date('d-M-Y',strtotime($vaccination_stock->purchase_date));
            }
            
            
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_stock->get_medicine_allot_history_count_all($post['vaccine_id'],$post['batch_no'],$post['type'],$medicine_datas['branch_id']),
                        "recordsFiltered" => $this->vaccination_stock->get_medicine_allot_history_count_filtered($post['vaccine_id'],$post['batch_no'],$post['type'],$medicine_datas['branch_id']),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }
    
}
