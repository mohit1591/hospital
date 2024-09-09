<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_salary extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('canteen/employee_salary/employee_salary_model','employee_salary');
		$this->load->library('form_validation');
	}

	public function index()
	{
		unauthorise_permission('36','203');
		$data['page_title'] = 'Employees Salary List'; 
		$this->load->view('canteen/employee_salary/list',$data);
	}

	public function ajax_list()
	{ 
		unauthorise_permission('36','203');
		$users_data = $this->session->userdata('auth_users');
	
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
    
            $list = $this->employee_salary->get_datatables();

        //  echo "<pre>";  print_r($list);die();
        
		$data = array();
		$no = $_POST['start'];
		$i = 1;
		$total_num = count($list);
		foreach ($list as $employee_salary) { 
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
            if($users_data['parent_id']==$employee_salary->branch_id){ 

			    $row[] = '<input type="checkbox" name="branch[]" class="checklist" value="'.$employee_salary->id.'">'.$check_script;
			}else{
				$row[]='';
			}
			$row[] = $employee_salary->name;
			$row[] = date('d-m-Y',strtotime($employee_salary->expenses_date)); 
			
			$row[] = $employee_salary->employee_pay_now;
			$row[] = $employee_salary->payment_mode;
		    $row[] = date('d-M-Y H:i A',strtotime($employee_salary->created_date)); 
            // Action button ///////////////
            $btn_view = "";
            $btn_delete = "";
            $btn_edit="";

            if($users_data['parent_id']==$employee_salary->branch_id){ 
	          
	            if(in_array('206',$users_data['permission']['action']))
	            {
	                $btn_delete =' <a class="btn-custom" onClick="return delete_employee_salary('.$employee_salary->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
	            }
	                if(in_array('209',$users_data['permission']['action']))
	            {
	                $btn_view = ' <a onClick="return view_employee_salary('.$employee_salary->id.');" class="btn-custom" href="javascript:void(0)" style="'.$employee_salary->id.'" title="View"><i class="fa fa-info-circle" aria-hidden="true"></i> View</a>';

	            }
	            $btn_edit = ' <a onClick="return edit_employee_salary('.$employee_salary->id.');" class="btn-custom" href="javascript:void(0)" style="'.$employee_salary->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
			
				
	        }
	        $print_url = "'".base_url('canteen/expenses/print_expense_report/'.$employee_salary->id)."'";
				$btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
	        
          
            // End action button //////////
			$row[] = $btn_delete.$btn_view.$btn_edit.$btnprint; 	   			 
		
			$data[] = $row;
			$i++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->employee_salary->count_all(),
						"recordsFiltered" => $this->employee_salary->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        
        $data['form_data'] = array(
                                    "start_date"=>"",
                                   
                                    
                                  );
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('employe_search_detail', $merge);
        }
        $employe_search_detail = $this->session->userdata('employe_search_detail');
        if(isset($employe_search_detail) && !empty($employe_search_detail))
        {
            $data['form_data'] = $employe_search_detail;
        }
        
    }
    
	
	public function add()
	{  
		unauthorise_permission('36','204');
		$users_data = $this->session->userdata('auth_users');
        $this->load->model('general/general_model');
         $this->load->model('employee/employee_model','employee');
        $data['type_list'] = $this->employee->employee_type_list();
	    $data['page_title'] = "Employee Salary Add";

	    $data['expense_category_list'] = $this->general_model->expense_category_list();
	    $data['payment_mode']=$this->general_model->payment_mode();
	    $data['employee_name'] = $this->employee->employee_list();
	    $post = $this->input->post();
	    $data['form_error'] = []; 
	    $voucher_no = generate_unique_id(7);
        $data['form_data'] = array(
								  'data_id'=>"",
								  'voucher_no'=>$voucher_no,
								  'expenses_date'=>date('d-m-Y'),
								 
								  'paid_amount'=>"",
								  'payment_mode'=>"",
								  'remark'=>"",
								  "field_name"=>'',
								  'branch_name'=>"",
								  'emp_type_id'=>"",
								  //'cheque_no'=>"",
								  //'cheque_date'=>"",
								  //'transaction_no'=>"",
								  'employee_id'=>'',
								  'patient_balance'=>'',
								  'advance_paid'=>'',
								  'balance'=>'',
								  'pay_now'=>''

								);	

		if(isset($post) && !empty($post))
		{   
			$data['form_data'] = $this->_validate();
			if($this->form_validation->run() == TRUE)
			{
				
				$expenses_id = $this->employee_salary->save();
				
				if(!empty($expenses_id))
				{
				//check permission
					$get_by_id_data = $this->employee_salary->get_by_id($expenses_id);
					//print_r($get_by_id_data); exit;
					$name = $get_by_id_data['name'];
					$mobile_no = $get_by_id_data['contact_no'];
					$employee_email = $get_by_id_data['employee_email'];
					$employee_pay_now = $get_by_id_data['employee_pay_now'];
					if(in_array('640',$users_data['permission']['action']))
					{
						
						if(!empty($mobile_no))
						{
							send_sms('canteen/employee_salary',8,$name,$mobile_no,array('{Name}'=>$name,'{Amt}'=>$employee_pay_now)); 

						
						}

					}

					if(in_array('641',$users_data['permission']['action']))
					{
					if(!empty($employee_email))
					{
					  
					  $this->load->library('general_functions');
					  $this->general_functions->email($employee_email,'','','','','1','employee_salary','8',array('{Name}'=>$name,'{Amt}'=>$employee_pay_now));
					   
					}
					}
				}
				
				$this->session->set_userdata('expense_id', $expenses_id);
				//$data_array= array('expense_id'=>$expenses_id,'response'=>1);
				//echo json_encode($data_array);
				echo '1';
				return false;
				
			}
			else
			{

				$data['form_error'] = validation_errors();  

			}		
		}
	
       $this->load->view('canteen/employee_salary/add',$data);		
	}
	
	public function edit($id="")
	{  
	
	 // unauthorise_permission('1','3');
	 if(isset($id) && !empty($id) && is_numeric($id))
	  { 	 
        $result = $this->employee_salary->get_by_id($id);   

	    $data['page_title'] = "Update Employee Salary"; 
	    $this->load->model('general/general_model');
	     $this->load->model('employee/employee_model','employee');
        $data['type_list'] = $this->employee->employee_type_list();
	    $data['country_list'] = $this->general_model->country_list();
	    $data['rate_list'] = $this->general_model->rate_list();
	     $data['employee_list'] = $this->employee->type_to_employee($result['emp_type_id']);
	    $post = $this->input->post();
	    $data['form_error'] = ''; 
		$data['payment_mode']=$this->general_model->payment_mode();
		$get_payment_detail= $this->employee_salary->payment_mode_detail_according_to_field($result['payment_mode'],$id);
		$total_values='';
		for($i=0;$i<count($get_payment_detail);$i++) {
		$total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

		}
         $data['form_data'] = array(
								'data_id'=>$id,
								'voucher_no'=>$result['vouchar_no'],
								'expenses_date'=>date('d-m-Y',strtotime($result['expenses_date'])),
								'emp_type_id'=>$result['emp_type_id'],
								'paid_amount'=>$result['paid_amount'],
								'payment_mode'=>$result['payment_mode'],
								'remark'=>$result['remarks'],
								//'branch_name'=>$result['branch_name'],
									//'cheque_no'=>$result['cheque_no'],
									//'cheque_date'=>$result['cheque_date'],
									//'transaction_no'=>$result['transaction_no'],
									'field_name'=>$total_values,
									'patient_balance'=>$result['employee_patient_balance'],
									'employee_id'=>$result['employee_id'],
									'advance_paid'=>$result['employee_advance'],
									'balance'=>$result['employee_balance'],
									'pay_now'=>$result['employee_pay_now']
								  
								  );	
		
		if(isset($post) && !empty($post))
		{   
			$data['form_data'] = $this->_validate();
			if($this->form_validation->run() == TRUE)
			{
				 $expenses_id = $this->employee_salary->save();
				 $this->session->set_userdata('expense_id', $expenses_id);
				//$data_array= array('expense_id'=>$expenses_id,'response'=>1);
				//echo json_encode($data_array);
				echo '1';
				return false;
				
			}
			else
			{
				$data['form_error'] = validation_errors();  
			}		
		}
       $this->load->view('canteen/employee_salary/add',$data);		
	  }  
	}
	 
     public function print_expense_report($ids=""){
     	$this->load->model('expenses/expenses_model','expenses');
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
		$data['all_detail']= $get_by_id_data;
		$this->load->view('expenses/print_template_expense',$data);
	}
	private function _validate()
	{

		$post = $this->input->post();

		$total_values=array(); 

		if(isset($post['field_name']))
		{
		$count_field_names= count($post['field_name']);  

		$get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

		for($i=0;$i<$count_field_names;$i++) {
		$total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

		}
		}    
	    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	    $this->form_validation->set_rules('expenses_date', 'expense date', 'trim|required');
		
		$this->form_validation->set_rules('paid_amount', 'paid amount', 'trim|required');
		$this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required');
		$this->form_validation->set_rules('employee_id', 'employee name', 'trim|required');
		$this->form_validation->set_rules('emp_type_id', 'employee type', 'trim|required');
		if(isset($post['field_name']))
		{
			 $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
		}
	   
		              
		/*if($post['payment_mode']=='cheque'){
			$this->form_validation->set_rules('branch_name', 'bank name', 'trim|required');
			$this->form_validation->set_rules('cheque_date', 'cheque date', 'trim|required');
		    $this->form_validation->set_rules('cheque_no', 'cheque no.', 'trim|required');

		}else if($post['payment_mode']=='card' || $post['payment_mode']=='neft'){
			$this->form_validation->set_rules('transaction_no', 'transaction no', 'trim|required');
			$this->form_validation->set_rules('emp_type_id', 'employee type', 'trim|required');
		}*/
		
		
		
		
		if ($this->form_validation->run() == FALSE) 
		{ 
			
			$billing_code = generate_unique_id(7);
	         $data['form_data'] = array(
								  'data_id'=>$post['data_id'],
								  'voucher_no'=>$post['voucher_no'],
								  'expenses_date'=>$post['expenses_date'],
								   'emp_type_id'=>$post['emp_type_id'],
								  'paid_amount'=>$post['paid_amount'],
								  'payment_mode'=>$post['payment_mode'],
								  'remark'=>$post['remark'],
								  'field_name'=>$total_values,
								  //'branch_name'=>$post['branch_name'],
								  //'cheque_no'=>$post['cheque_no'],
								  //'cheque_date'=>$post['cheque_date'],
								  //'transaction_no'=>$post['transaction_no'],
								  'employee_id'=>$post['employee_id'],
								  'patient_balance'=>$post['patient_balance'],
								  'advance_paid'=>$post['advance_paid'],
								   'employee_id'=>$post['employee_id'],
								  'balance'=>$post['balance'],
								  'pay_now'=>$post['pay_now']
								 
								  
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
	   unauthorise_permission('36','206');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->employee_salary->delete($id);
           $response = "Employee Salary successfully deleted.";
           echo $response;
       }
	}

	function deleteall()
	{
		unauthorise_permission('36','206');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->employee_salary->deleteall($post['row_id']);
			$response = "Employee Salary successfully deleted.";
			echo $response;
	    }
	}


	public function view($id="")
	{  
	 unauthorise_permission('36','209');
	 if(isset($id) && !empty($id) && is_numeric($id))
	  { 	 
        $data['form_data'] = $this->employee_salary->get_by_id($id);
       
	  	$data['page_title'] = "Employee Salary  Detail";
        $this->load->view('canteen/employee_salary/view',$data);		
      }
    }  


    ///// branch Archive Start  ///////////////
    public function archive()
	{
		unauthorise_permission('36','207');
		$data['page_title'] = 'Employee Salary Archive List';
		$this->load->helper('url');
		$this->load->view('canteen/employee_salary/archive',$data);
	}

	public function archive_ajax_list()
	{ 
		unauthorise_permission('36','207');
		$users_data = $this->session->userdata('auth_users');
		$this->load->model('canteen/employee_salary/employee_salary_archive_model','employee_salary_archive');
		

            $list = $this->employee_salary_archive->get_datatables();
             
		$data = array();
		$no = $_POST['start'];
		$i = 1;
		$total_num = count($list);
		foreach ($list as $employee_salary_archive) { 
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
             if($users_data['parent_id']==$employee_salary_archive->branch_id){
			      $row[] = '<input type="checkbox" name="branch[]" class="checklist" value="'.$employee_salary_archive->id.'">'.$check_script;
			  }else{
			  	$row[]='';
			  }
			// $row[] = $employee_salary_archive->vouchar_no;
			$row[] = date('d-m-Y',strtotime($employee_salary_archive->expenses_date)); 
			
			$row[] = $employee_salary_archive->paid_amount;
			$row[] = $employee_salary_archive->payment_mode;
			$row[] = date('d-M-Y H:i A',strtotime($employee_salary_archive->created_date)); 
            ////Action button ////////////
            $btn_restore = "";
            $btn_delete = "";
            $btn_view = "";
            if($users_data['parent_id']==$employee_salary_archive->branch_id){
            if(in_array('298',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_bill('.$employee_salary_archive->id.');" class=" btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore</a> ';
            }

         
           
            if(in_array('208',$users_data['permission']['action'])) 
            {
               $btn_delete = ' <a onClick="return trash_bill('.$employee_salary_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
           }
            /////////////////////////////
			$row[] = $btn_restore.$btn_view.$btn_delete; 	   			 
		
			$data[] = $row;
			$i++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->employee_salary_archive->count_all(),
						"recordsFiltered" => $this->employee_salary_archive->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function restore($id="")
	{
	   unauthorise_permission('36','298');
	   $this->load->model('canteen/employee_salary/employee_salary_archive_model','employee_salary_archive');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->employee_salary_archive->restore($id);
           $response = "employee salary successfully restore in employee salary list.";
           echo $response;
       }
	}

	function restoreall()
	{ 
		unauthorise_permission('36','298');
		$this->load->model('canteen/employee_salary/employee_salary_archive_model','employee_salary_archive');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->employee_salary_archive->restoreall($post['row_id']);
			$response = "employee salary successfully restore in employee salary list.";
			echo $response;
	    }
	}

	public function trash($id="")
	{
		unauthorise_permission('36','208');
		$this->load->model('canteen/employee_salary/employee_salary_archive_model','employee_salary_archive');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->employee_salary_archive->trash($id);
           $response = "employee salary successfully deleted parmanently.";
           echo $response;
       }
	}

	function trashall()
	{
		unauthorise_permission('36','208');
		$this->load->model('canteen/employee_salary/employee_salary_archive_model','employee_salary_archive');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->employee_salary_archive->trashall($post['row_id']);
			$response = "employee salary successfully deleted parmanently.";
			echo $response;
	    }
	}
    ///// branch Archive end  ///////////////

 
   public function find_employee_salary(){
		$post= $this->input->post();
		$emp_id = $this->input->post('emp_id');
		$emp_type = $this->input->post('emp_type');
		$expense_date = $this->input->post('expense_date');
		$first_date = date("Y-m-01", strtotime($expense_date));
		$last_date =  date("Y-m-t", strtotime($expense_date));
		$advance_payment_bkp='';
		$balance='';
        $this->load->model('general/general_model');
        $advance_payment= $this->general_model->get_total_adavance_payment($emp_id,$emp_type,$first_date,$last_date);
       
		if(!empty($emp_id))
		{
		$result = $this->general_model->find_employee_salary($emp_id);
		}
		if(isset($advance_payment) && !empty($advance_payment) && $advance_payment[0]->id!='')
		{
		$balance= $result[0]->salary- $advance_payment[0]->tot_payment;
		//$advance_payment_bkp=$advance_payment[0]->tot_payment;
		}
		if($balance>0)
		{
		 $data_new_arr= array('sal_amount'=>$result[0]->salary,'balance'=>$balance,'success'=>1);
		}
		else
		{
		$b = str_replace( '-', '', $balance);
		$data_new_arr= array('sal_amount'=>$result[0]->salary,'advance_payment'=>$b,'success'=>1);
		}
        
        echo json_encode($data_new_arr);
        die;
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

        $html.='<div class="row m-b-5"><div class="col-md-4"><label>'.$payment_detail->field_name.'<label class="star">*</span></div> <div class="col-md-8"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>';
        }
        echo $html;exit;

    }


 

}
?>