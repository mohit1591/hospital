<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('canteen/expenses/Expenses_model','expenses');
		$this->load->library('form_validation');
	}

	public function index()
	{
		unauthorise_permission('35','210');
		$data['page_title'] = 'Expenses List';
		// $this->session->unset_userdata('expenses_search');
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
		$data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date,'expense_type'=>''); 
		$this->load->view('canteen/expenses/list',$data);
	}

	public function ajax_list()
	{ 
		unauthorise_permission('35','210');
		$users_data = $this->session->userdata('auth_users');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$list = $this->expenses->get_datatables();
      
		$data = array();
		$no = $_POST['start'];
		$i = 1;
		$total_num = count($list);
		foreach ($list as $expenses) { 
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
            if($users_data['parent_id']==$expenses->branch_id){ 

			    $row[] = '<input type="checkbox" name="branch[]" class="checklist" value="'.$expenses->id.'">'.$check_script;
			}else{
				$row[]='';
			}
			$expenses_type = $expenses->exp_category;
            if($expenses->type>0)
            {
              $expenses_type = $expenses->expenses_type;
            }

			$row[] = $expenses->vouchar_no;
			$row[] = date('d-m-Y',strtotime($expenses->expenses_date)); 
			$row[] = $expenses_type;
			$row[] = $expenses->paid_amount;
			$row[] = $expenses->p_mode;
		    $row[] = date('d-M-Y H:i A',strtotime($expenses->created_date)); 
            // Action button ///////////////
            $btn_view = "";
            $btn_delete = "";
            $btn_edit="";
            $btnprint= '';
             

	            if(in_array('213',$users_data['permission']['action']))
	            {
	                $btn_delete =' <a class="btn-custom" onClick="return delete_expenses('.$expenses->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
	            }
	            if(in_array('216',$users_data['permission']['action']))
	            {
	                $btn_view = ' <a onClick="return view_expenses('.$expenses->id.');" class="btn-custom" href="javascript:void(0)" style="'.$expenses->id.'" title="View"><i class="fa fa-info-circle" aria-hidden="true"></i> View</a>';
	            } 
	            if($expenses->type==2){
				$print_url = "'".base_url('vendor_payment/print_patient_balance_receipt/'.$expenses->parent_id)."/".$expenses->type."'";
				$btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 

	            }else{
	            $btn_edit = ' <a onClick="return edit_expenses('.$expenses->id.');" class="btn-custom" href="javascript:void(0)" style="'.$expenses->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
				$print_url = "'".base_url('canteen/expenses/print_expense_report/'.$expenses->id)."'";
				$btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
				}

	        
	       
          
            // End action button //////////
            if($expenses->type>0){
            	$row[]=$btnprint;

            }
            else
            {
                if($users_data['parent_id']==$expenses->branch_id)
                {
                  $row[] = $btn_delete.$btn_view.$btn_edit.$btnprint; 
                }
                else
                {
                  $row[] = $btn_view.$btnprint; 
                }  	
            }
				   			 
		
			$data[] = $row;
			$i++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->expenses->count_all(),
						"recordsFiltered" => $this->expenses->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
    
	
	public function add()
	{  
		//unauthorise_permission('35','211');
		$this->load->model('general/general_model');
		$this->load->model('employee/employee_model','employee');
		$data['type_list'] = $this->employee->employee_type_list();
		$data['page_title'] = "Add Expense";
		$data['expense_category_list'] = $this->general_model->expense_category_list();
        //$data['vendor_list'] = $this->general_model->vendor_list();
        $data['vendor_list'] = $this->expenses->vendor_list();
       // print_r($data['vendor_list']);die;
	    $post = $this->input->post();
	    $data['form_error'] = []; 
	    $data['payment_mode']=$this->general_model->payment_mode();
	    $voucher_no = generate_unique_id(19);
        $data['form_data'] = array(
								  'data_id'=>"",
								  'voucher_no'=>$voucher_no,
								  'expenses_date'=>date('d-m-Y'),
								  'expense_category_id'=>"",
								  'paid_amount'=>"",
								  'payment_mode'=>"",
								  'emp_type_id'=>"",
								  'remark'=>"",
								 // 'branch_name'=>"",
								  //'cheque_date'=>"",
								  "field_name"=>'',
								  //'cheque_no'=>"",
								  //'transaction_no'=>"",
								  'employee_id'=>'',
                                  'vendor_id'=>'', 
								  
								  );	

		if(isset($post) && !empty($post))
		{   
			$data['form_data'] = $this->_validate();
			if($this->form_validation->run() == TRUE)
			{
				 $expense_id= $this->expenses->save();
				 $this->session->set_userdata('expense_id', $expense_id);
				// $data_array= array('expense_id'=>$expense_id,'response'=>1);
				// echo json_encode($data_array);
				 echo '1';
				return false;
				
			}
			else
			{

				$data['form_error'] = validation_errors();  
			}		
		}
	
       $this->load->view('canteen/expenses/add',$data);		
	}
	
	public function edit($id="")
	{  
	 
	 // unauthorise_permission('35','213');
	 if(isset($id) && !empty($id) && is_numeric($id))
	  { 	 
        $result = $this->expenses->get_by_id($id);   

      
	    $data['page_title'] = "Update Expense"; 
	    $this->load->model('general/general_model');
	     $this->load->model('employee/employee_model','employee');
	    $data['type_list'] = $this->employee->employee_type_list();
	    $data['country_list'] = $this->general_model->country_list();
	    $data['rate_list'] = $this->general_model->rate_list();
	     $data['expense_category_list'] = $this->general_model->expense_category_list();
	    $data['employee_list'] = $this->general_model->type_to_employee($result['emp_type_id']);
	    $post = $this->input->post();
		$data['payment_mode']=$this->general_model->payment_mode();
		$get_payment_detail= $this->expenses->payment_mode_detail_according_to_field($result['payment_mode'],$id);
		$total_values='';
		for($i=0;$i<count($get_payment_detail);$i++) {
		$total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

		}
        $data['vendor_list'] = $this->expenses->vendor_list();
	    $data['form_error'] = ''; 
         $data['form_data'] = array(
								  'data_id'=>$id,
								  'voucher_no'=>$result['vouchar_no'],
								  'expenses_date'=>date('d-m-Y',strtotime($result['expenses_date'])),
								  'expense_category_id'=>$result['paid_to_id'],
								  'paid_amount'=>$result['paid_amount'],
								  'employee_id'=>$result['employee_id'],
								  'emp_type_id'=>$result['emp_type_id'],
								  'payment_mode'=>$result['payment_mode'],
								  'remark'=>$result['remarks'],
                                  'vendor_id'=>$result['vendor_id'], 
								  'field_name'=>$total_values
								  //'branch_name'=>$result['branch_name'],
								  //'cheque_no'=>$result['cheque_no'],
								  //'cheque_date'=>date('d-m-Y',strtotime($result['cheque_date'])),
								 // 'transaction_no'=>$result['transaction_no']
								  
								  );	
		
		if(isset($post) && !empty($post))
		{   
			$data['form_data'] = $this->_validate();
			if($this->form_validation->run() == TRUE)
			{
				 $expense_id= $this->expenses->save();
				 $this->session->set_userdata('expense_id', $expense_id);
				 //$data_array= array('expense_id'=>$expense_id,'response'=>1);
				 //echo json_encode($data_array);
				 echo '1';
				return false;
				
			}
			else
			{
				$data['form_error'] = validation_errors();  
			}		
		}
        $data['vendor_list'] = $this->expenses->vendor_list();
       $this->load->view('canteen/expenses/add',$data);		
	  }  
	}

	public function print_expense_report($ids="")
	{
		$user_detail= $this->session->userdata('auth_users');
       
		if(!empty($ids)){
		$expense_id= $ids;
		}else{
		$expense_id= $this->session->userdata('expense_id');
		}

        $get_detail_by_id= $this->expenses->get_by_id($expense_id);
    
		$get_by_id_data=$this->expenses->get_all_detail_print($expense_id,$get_detail_by_id['branch_id']);

		$template_format= $this->expenses->template_format($get_detail_by_id['branch_id']);
		//echo $template_format->setting_value;
		$data['template_data']=$template_format;
		$data['user_detail']=$user_detail;
		$data['emp_name']=$get_detail_by_id['name'];
		$data['all_detail']= $get_by_id_data;
		$this->load->view('canteen/expenses/print_template_expense',$data);
	}


	public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        
        $data['form_data'] = array(
                                    "branch_id"=>""
                                   );
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('expenses_search', $merge);
        }
        $expenses_search = $this->session->userdata('expenses_search');
        if(isset($expenses_search) && !empty($expenses_search))
        {
            $data['form_data'] = $expenses_search;
        }
        $this->load->model('general/general_model');
        $this->load->model('employee/employee_model','employee');
        $data['type_list'] = $this->employee->employee_type_list();
	    $data['expense_category_list'] = $this->general_model->expense_category_list();
        $this->load->view('canteen/expenses/advance_search',$data);
    }
	 
     
	private function _validate()
	{
		$post = $this->input->post();    
	    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	    $this->form_validation->set_rules('expenses_date', 'expense date', 'trim|required');
		$this->form_validation->set_rules('expense_category_id', 'expenses category', 'trim|required');
		$this->form_validation->set_rules('paid_amount', 'paid amount', 'trim|required');
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

		for($i=0;$i<$count_field_names;$i++) {
		$total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

		}
		} 
		
	   
		              
		/*if($post['payment_mode']=='cheque'){
			$this->form_validation->set_rules('branch_name', 'bank name', 'trim|required');
		    $this->form_validation->set_rules('cheque_no', 'cheque no.', 'trim|required');
		    $this->form_validation->set_rules('cheque_date', 'cheque date', 'trim|required');

		}else if($post['payment_mode']=='card' || $post['payment_mode']=='neft'){
			$this->form_validation->set_rules('transaction_no', 'transaction no', 'trim|required');
		}*/
		if(!empty($post['expense_category_id'])){
		    $result = get_exp_category($post['expense_category_id']);
		    if($result['exp_category']=='advance' || $result['exp_category']=='Advance'){
		     	$this->form_validation->set_rules('employee_id', 'employee name', 'trim|required');
		     	$this->form_validation->set_rules('emp_type_id', 'employee type', 'trim|required');

		  
		    }
		}
		
		
		
		if ($this->form_validation->run() == FALSE) 
		{ 
			
			$billing_code = generate_unique_id(7);
	         $data['form_data'] = array(
								  'data_id'=>$post['data_id'],
								  'voucher_no'=>$post['voucher_no'],
								  'expenses_date'=>$post['expenses_date'],
								  'expense_category_id'=>$post['expense_category_id'],
								  'paid_amount'=>$post['paid_amount'],
								  'payment_mode'=>$post['payment_mode'],
								  'emp_type_id'=>$post['emp_type_id'],
								  'remark'=>$post['remark'],
								  'field_name'=>$total_values,
								 // 'branch_name'=>$post['branch_name'],
								  //'cheque_no'=>$post['cheque_no'],
								  //'cheque_date'=>$post['cheque_date'],
								  //'transaction_no'=>$post['transaction_no'],
								  'employee_id'=>$post['employee_id'],
								 
								  
								  );	
			$data['form_error'] = validation_errors();  
			return $data['form_data'];
		} 	
	}

	public function check_username($str)
	{
		$this->load->model('general/general_model','general');
		$post = $this->input->post();
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
            return true;
		}
		else
		{
            $userdata = $this->general->check_username($str);
            if(empty($userdata))
            {
               return true;
            }
            else
            {
				$this->form_validation->set_message('check_username', 'Username already exists.');
				return false;
            }
		}
	}

	public function check_email($str)
	{
		$this->load->model('general/general_model','general');
		$post = $this->input->post();
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
            return true;
		}
		else
		{
            $userdata = $this->general->check_email($str); 
            if(empty($userdata))
            {
               return true;
            }
            else
            { 
				$this->form_validation->set_message('check_email', 'Email already exists.');
				return false;
            }
		}
	}

	public function delete($id="")
	{
	   unauthorise_permission('35','213');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->expenses->delete($id);
           $response = "Expense successfully deleted.";
           echo $response;
       }
	}

	function deleteall()
	{
		unauthorise_permission('35','213');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->expenses->deleteall($post['row_id']);
			$response = "Expense successfully deleted.";
			echo $response;
	    }
	}


	public function view($id="")
	{  
	 unauthorise_permission('35','216');
	 if(isset($id) && !empty($id) && is_numeric($id))
	  { 	 
        $data['form_data'] = $this->expenses->get_by_id($id);
	  	$data['page_title'] = "Expense  detail";
$this->load->model('general/general_model');
$get_payment_detail= $this->general_model->payment_mode_by_id('',$data['form_data']['payment_mode']);
		//print_r($get_payment_detail);
		$data['payment_mode']= $get_payment_detail;
        $this->load->view('canteen/expenses/view',$data);		
      }
    }  


    ///// branch Archive Start  ///////////////
    public function archive()
	{
		unauthorise_permission('35','214');
		$data['page_title'] = 'Expenses Archive List';
		$this->load->helper('url');
		$this->load->view('canteen/expenses/archive',$data);
	}

	public function archive_ajax_list()
	{ 
		unauthorise_permission('35','214');
		$users_data = $this->session->userdata('auth_users');
		$this->load->model('expenses/Expenses_archive_model','expenses_archive');
		

            $list = $this->expenses_archive->get_datatables();
              
		$data = array();
		$no = $_POST['start'];
		$i = 1;
		$total_num = count($list);
		foreach ($list as $expenses_archive) { 
			$no++;
			$row = array();
			
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
            ////////// Check list end ///////////// 
           
			     $row[] = '<input type="checkbox" name="branch[]" class="checklist" value="'.$expenses_archive->id.'">'.$check_script;
			
			$expenses_type = $expenses_archive->exp_category;
            if($expenses_archive->type>0)
            {
              $expenses_type = $expenses_archive->expenses_type;
            }

			$row[] = $expenses_archive->vouchar_no;
			$row[] = date('d-m-Y',strtotime($expenses_archive->expenses_date)); 
			$row[] = $expenses_type;
			$row[] = $expenses_archive->paid_amount;
			$row[] = $expenses_archive->p_mode;
			$row[] = date('d-M-Y H:i A',strtotime($expenses_archive->created_date)); 
            ////Action button ////////////
            $btn_restore = "";
            $btn_delete = "";
            $btn_view = "";
            if(in_array('297',$users_data['permission']['action'])) 
            {
          
               $btn_restore = ' <a onClick="return restore_bill('.$expenses_archive->id.');" class=" btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore</a> ';

            }

            // if(in_array('7',$users_data['permission']['action'])) 
            // {
               
            // } 
           
            if(in_array('215',$users_data['permission']['action'])) 
            {
               $btn_delete = ' <a onClick="return trash_bill('.$expenses_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
        
            /////////////////////////////
			$row[] = $btn_restore.$btn_view.$btn_delete; 	   			 
		
			$data[] = $row;
			$i++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->expenses_archive->count_all(),
						"recordsFiltered" => $this->expenses_archive->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function restore($id="")
	{
	   unauthorise_permission('35','297');
	   $this->load->model('expenses/Expenses_archive_model','expenses_archive');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->expenses_archive->restore($id);
           $response = "Expense successfully restore in Expense list.";
           echo $response;
       }
	}

	function restoreall()
	{ 
		unauthorise_permission('35','297');
		$this->load->model('expenses/Expenses_archive_model','expenses_archive');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->expenses_archive->restoreall($post['row_id']);
			$response = "Expense successfully restore in Expense list.";
			echo $response;
	    }
	}

	public function trash($id="")
	{
		unauthorise_permission('35','215');
		$this->load->model('expenses/Expenses_archive_model','expenses_archive');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->expenses_archive->trash($id);
           $response = "Expense successfully deleted parmanently.";
           echo $response;
       }
	}

	function trashall()
	{
		unauthorise_permission('35','215');
		$this->load->model('expenses/Expenses_archive_model','expenses_archive');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->expenses_archive->trashall($post['row_id']);
			$response = "Expense successfully deleted parmanently.";
			echo $response;
	    }
	}
    ///// branch Archive end  ///////////////

 
	public function reset_search()
	{
		$this->session->unset_userdata('expenses_search');
	}


	/* printing data */

  public function expenses_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Voucher No.','Expense Date','Expense Type','Paid Amount','Payment Mode');
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
          $list = $this->expenses->search_report_data();

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
		               	$expenses_type = $reports->exp_category;
						if($reports->type>0)
						{
						$expenses_type = $reports->expenses_type;
						}
                       array_push($rowData,$reports->vouchar_no,date('d-m-Y',strtotime($reports->expenses_date)),$expenses_type,$reports->paid_amount,$reports->p_mode);
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
          header("Content-Disposition: attachment; filename=expenses_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function expenses_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
            $fields = array('Voucher No.','Expense Date','Expense Type','Paid Amount','Payment Mode');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->expenses->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {
				$expenses_type = $reports->exp_category;
				if($reports->type>0)
				{
				$expenses_type = $reports->expenses_type;
				}
                    array_push($rowData,$reports->vouchar_no,date('d-m-Y',strtotime($reports->expenses_date)),$expenses_type,$reports->paid_amount,$reports->p_mode);
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
          header("Content-Disposition: attachment; filename=expenses_report_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    
    }

    public function pdf_expenses()
    {    

        $data['print_status']="";
        $data['data_list'] = $this->expenses->search_report_data();
      // print  '<pre>'; print_r($data['data_list']);die;
        $view_new =$this->load->view('canteen/expenses/expenses_report_html',$data); 
        //print_r($view_new);die;
        //die;
        $html = $this->output->get_output();
        
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("expenses_report_".time().".pdf");
    }
    public function print_expenses()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->expenses->search_report_data();
      $this->load->view('canteen/expenses/expenses_report_html',$data); 
    }
	/* printing data */

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

        $html.='<div class="row m-b-5"><div class="col-md-4"><label>'.$payment_detail->field_name.'<label class="star">*</span></div> <div class="col-md-8"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>';
        }
        echo $html;exit;

    }

}
?>