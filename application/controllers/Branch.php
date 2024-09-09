<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Branch extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('branch/branch_model','branch');
		$this->load->library('form_validation');
	}

	public function index()
	{
		unauthorise_permission('1','1');
		$data['page_title'] = 'Branch List';
		$data['form_data'] = array('branch_type'=>1,'status'=>'1');
		$this->session->unset_userdata('branch_search'); 
		$this->load->view('branch/list',$data);
	}


	public function ajax_list()
	{ 
		unauthorise_permission('1','1');
		$users_data = $this->session->userdata('auth_users');
	    $sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$list = $this->branch->get_datatables();
	    $data = array();
		$no = $_POST['start'];
		$i = 1;
		$total_num = count($list);
		
		
		foreach ($list as $branch){ 
			$no++;
			$row = array();
			if($users_data['users_role']==1)
		    {
			
            $total_count=0;
            $start_date='';
            $end_date='';
            if((!empty($branch->start_date)) && ($branch->start_date!='1970-01-01') && ($branch->start_date!='0000-00-00'))
            {
            $start_date=$branch->start_date;
            }
            if((!empty($branch->end_date)) && ($branch->end_date!='1970-01-01') && ($branch->end_date!='0000-00-00'))
            {
            $end_date=$branch->end_date;
            }
            
            $current_date=date('Y-m-d');
            $diff = abs(strtotime($end_date)-strtotime($current_date));
            $date_diff_total=$total_count+$diff;
            $date_days_total=$date_diff_total/(24*60*60);
           // echo"<pre>";print_r($date_days_total);
            //die;
		}
			if($branch->status==1)
			{
				$status = '<font color="green">Active</font>';
			}	
			else
			{
				$status = '<font color="red">Inactive</font>';
			}
			///// State name ////////
			$state = "";
            if(!empty($branch->state))
            {
            	$state = " ( ".ucfirst(strtolower($branch->state))." )";
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
			$j=0;
            $sub_branches = array();
            if(!empty($sub_branch_details)){
                foreach($sub_branch_details as $key=>$value){
                    $sub_branches[$j] = $sub_branch_details[$j]['id'];
                    $j= $j+1;
                }
        	}		  
            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$branch->parent_id){ 

			    $row[] = '<input type="checkbox" name="branch[]" class="checklist" value="'.$branch->id.'">'.$check_script;
			}elseif(!empty($sub_branches)){
                    if(in_array($branch->parent_id,$sub_branches)){
                        $row[] = '<input type="checkbox" name="branch[]" class="checklist" value="'.$branch->id.'">'.$check_script;
                    }
                }
            else{
				$row[]='';
			}

			//if($branch->users_id=='28')
			//{
			 $per_ids = branch_alloted_module_permissionwise($users_data['parent_id'],$branch->users_id);

			
			 //print '<pre>'; print_r($per_ids);die;
			//}
			 if(!empty($per_ids))
			 {
				$data_split=array();
				if(in_array('85',$per_ids))
				{
					$data_split[]='OPD';
				}
				if(in_array('121',$per_ids))
				{
					$data_split[]='IPD';
				}
				if(in_array('134',$per_ids))
				{
					$data_split[]='OT';
				}
				if(in_array('207',$per_ids))
				{
					$data_split[]='Dialysis';
				}
				if(in_array('145',$per_ids))
				{
					$data_split[]='Pathology';
				}
				if(in_array('167',$per_ids))
				{
					$data_split[]='Issue Allot';
				}
				if(in_array('60',$per_ids))
				{
					$data_split[]='Medicine Sale';
				}	
			 }
			 else
			 {
			 	$data_split[]='';
			 }
			
			//print_r($data_split);
			 $data_imp='';
			 	if($users_data['users_role']==1)
		{
			$data_imp=implode(',',$data_split);
		}
			$row[] = $branch->branch_code;
			$row[] = $branch->branch_name; 
			$row[] = $branch->city.$state;
			$row[] = $branch->contact_no;
	     if($users_data['users_role']==1)
		{
			$row[] = $data_imp;
		}
			$last_login_ip='';
			$last_login_time='';
			if(!empty($branch->last_login_ip) && isset($branch->last_login_ip))
			{
				$last_login_ip= $branch->last_login_ip;
			}
			else
			{
				$last_login_ip=='';
			}
			if(!empty($branch->last_login_time) && isset($branch->last_login_time) && $branch->last_login_time!="0000-00-00 00:00:00")
			{
				$last_login_time= date('d-m-Y h:i A',strtotime($branch->last_login_time));
			}
			else
			{
				$last_login_time=='';
			}
			
			if(!empty($branch->login_time) && isset($branch->login_time) && $branch->login_time!="0000-00-00 00:00:00")
			{
				$login_time= date('d-m-Y h:i A',strtotime($branch->login_time));
			}
			else
			{
				$login_time=='';
			}
			
			$row[] = $status;
			$row[] = $login_time;
			if($users_data['users_role']==1)
			{
				/*if($branch->branch_type=='1')
				{
					$row[] = 'N.A.';
					$row[] = 'N.A.';
				}
				else
				{*/
				if($branch->start_date!='1970-01-01' && $branch->start_date!='0000-00-00')
				{
					$row[] = date('d-M-Y',strtotime($branch->start_date)); 
				}
				else
				{
					$row[] = 'N.A.';	
				}
				if($branch->end_date!='1970-01-01' && $branch->end_date!='0000-00-00')
				{
					$row[] = date('d-M-Y',strtotime($branch->end_date)); 
				}
				else
				{
					$row[] = 'N.A.';	
				}
							
				//$row[] = date('d-M-Y',strtotime($branch->end_date)); 

			//}
	}
			
			//$row[] = date('d-M-Y H:i A',strtotime($branch->created_date)); 
            // Action button ///////////////
            $btn_edit = "";
            $btn_delete = "";
            $btn_view = "";
            $btn_permission = "";
            $btn_renew='';
            $btn_logs='';
           
            if($users_data['parent_id']==$branch->parent_id){ 
	            if(in_array('3',$users_data['permission']['action']))
	            {
	               $btn_edit = ' <a onClick="return edit_branch('.$branch->id.');" class="btn-custom" href="javascript:void(0)" style="'.$branch->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
	            }

	            if(in_array('4',$users_data['permission']['action']))
	            {
	                $btn_delete =' <a class="btn-custom" onClick="return delete_branch('.$branch->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
	            }

	            if(in_array('7',$users_data['permission']['action']))
	            {
	                $btn_view = ' <a onClick="return view_branch('.$branch->id.');" class="btn-custom" href="javascript:void(0)" style="'.$branch->id.'" title="View"><i class="fa fa-info-circle" aria-hidden="true"></i> View</a>';
	            }

	            if(in_array('129',$users_data['permission']['action']))
	            {
	                $btn_permission = ' <a class="btn-custom" href="'.base_url("branch/permission/").$branch->users_id.'" title="Permission"><i class="fa fa-expeditedssl"></i> Permission</a>';


	            }

	             if(in_array('129',$users_data['permission']['action']))
	            {
	                $btn_logs = ' <a class="btn-custom" href="'.base_url("branch/log/").$branch->id."/".$branch->users_id.'" title="Permission"><i class="fa fa-expeditedssl"></i> Log</a>';


	            }


	            if($users_data['users_role']==1)	            {
	            	//print_r($date_days_total);
	            	if(!empty($date_days_total))
	            	{
	            		if($date_days_total<=15)
	            		{
	                $btn_renew = ' <a onClick="return renew_mail('.$branch->id.');" class="btn-custom" href="javascript:void(0)" style="'.$branch->id.'" title="Edit"><i class="fa fa-envelope" aria-hidden="true"></i> Branch Renewal</a>';
	            }
	        }
	        }
	        }
	        
	       

            // End action button //////////
			$row[] = $btn_edit.$btn_delete.$btn_view.$btn_permission.$btn_renew.$btn_logs; 	   			 
		
			$data[] = $row;
			$i++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->branch->count_all(),
						"recordsFiltered" => $this->branch->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
    
	
	public function add()
	{  
		unauthorise_permission('1','2');
		$users_data = $this->session->userdata('auth_users');
		$data['total_branch'] = get_permission_attr(1,2);

	    $data['page_title'] = "Branch Add";
	    $this->load->model('general/general_model');
	    $data['country_list'] = $this->general_model->country_list();
	    $data['rate_list'] = $this->general_model->rate_list();
	    $post = $this->input->post();
	    $data['form_error'] = []; 
	    $branch_code = generate_unique_id(1);
        $data['form_data'] = array(
								  'data_id'=>"",
								  'branch_code'=>$branch_code,
								  'branch_name'=>"",
								  'contact_no'=>"",
								  'email'=>"",
								  'address'=>"",
								  "address_second"=>"",
                                  "address_third"=>"",
								  'country_id'=>"99",
								  'city_id'=>"",
								  'state_id'=>"",
								  'username'=>"",
								  'password'=>"",
                                  'cpassword'=>"",
                                  'branch_status'=>"1",
                                  'login_status'=>"1",
                                  'branch_type'=>'1',
                                  'start_date'=>'',
				                  'end_date'=>'',
				                  'rate'=>'',
				                  'contact_person'=>'',
								  );	

		if(isset($post) && !empty($post))
		{   
			$data['form_data'] = $this->_validate();
			if($this->form_validation->run() == TRUE)
			{
				$this->branch->save();
				
				////////// Send SMS /////////////////////
                if(in_array('640',$users_data['permission']['action']))
                {
                  if(!empty($post['contact_no']))
                  { 
                  	
                    send_sms('branch_registration',12,'',$post['contact_no'],array('{branch_name}'=>$post['branch_name'],'{username}'=>$post['username'],'{password}'=>$post['password']));  
                  }
                }
                //////////////////////////////////////////

                ////////// Send Email /////////////////////
                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($post['email']))
                  { 
                    $this->load->library('general_functions');
                    $this->general_functions->email($post['email'],'','','','','1','branch_registration','12',array('{branch_name}'=>$post['branch_name'],'{username}'=>$post['username'],'{password}'=>$post['password']));
                  }
                }
                //////////////////////////////////////////

				////// Email ///////////////
				/*$this->load->model('email_template/Email_template_model','email_template');
				$email_type='3';
				$email_template = $this->email_template->get_email_template($email_type);
				$message = '';
				if(!empty($email_template))
				{
					$to = $post['email'];
				    $subject = $email_template[0]->subject;
				    $name = $post['branch_name'];
				    $username = $post['username'];
				    $password = $post['password'];
				    $search = array("{name}","{username}","{password}");
				    $relaced = array($name,$username,$password);
				    $message=str_replace($search,$relaced,$email_template[0]->message); 
                    $this->load->library('general','','general_lib');
				    $this->general_lib->email($to,$subject,$message);  
				    
				}*/
				////// End email ///////////
				echo 1;
				return false;
				
			}
			else
			{
				$data['form_error'] = validation_errors();  
			}		
		}
       $this->load->view('branch/add',$data);		
	}

	public function renew_mail($id="")
	{  
	 $data['total_branch'] = '1';
	 $data['page_title'] = "Renewal Branch Mail";
	 $this->load->model('general/general_model');

	 unauthorise_permission('1','3');

	 if(isset($id) && !empty($id) && is_numeric($id))
	  { 	 
        $result = $this->branch->get_by_id($id); 
        $data['template_data'] = $this->branch->get_mail_temp_data();
        //print_r($data['template_data']);
        $data['temp']=$data['template_data']->template;
        //$data['temp'];
       //echo  htmlspecialchars_decode(stripslashes($data['temp'])); 
            $name=$result['branch_name'];
            $code=$result['branch_code'];
            $email=$result['email'];
            $total_count=0;
			$start_date=$result['start_date'];
			$end_date=$result['end_date'];
			$current_date=date('Y-m-d');
			
			$diff = abs(strtotime($end_date)-strtotime($current_date));
	        $date_diff_total=$total_count+$diff;
	        
            $data['date_days_total']=$date_diff_total/(24*60*60);

         
	     
	    
	    
                  $data['form_data']=$result;
	    $post = $this->input->post();
	    //print_r($post);	
		if(isset($post) && !empty($post))
		{   
			
			if(!empty($id))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->send_birh_anni_email($post['email'],$post['subject'],$post['message'],"","",'','');
                   //$this->general_functions->email($post['email'],$post['subject'],'','','','1','branch_renewal','28',array('{branch_name}'=>$name,'{date}'=>$data['date_days_total']));

                   
          
                  
                   echo 1;
				return false;
                     
                  }
				
				
			
					
		}
       $this->load->view('branch/mail_renew',$data);		
	  }  
	}

	
	public function edit($id="")
	{  
	 $data['total_branch'] = '1';
	 unauthorise_permission('1','3');
	 if(isset($id) && !empty($id) && is_numeric($id))
	  { 	 
        $result = $this->branch->get_by_id($id);    
	    $data['page_title'] = "Update Branch"; 
	    $this->load->model('general/general_model');
	    $data['country_list'] = $this->general_model->country_list();
	    $data['rate_list'] = $this->general_model->rate_list();
	    $post = $this->input->post();
	    $data['form_error'] = ''; 
        $start_date='';
        if($result['start_date']!='0000-00-00' && $result['start_date']!='1970-01-01')
        {
        	$start_date = date('d-m-Y',strtotime($result['start_date']));
        }
        $end_date='';
        if($result['end_date']!='0000-00-00' && $result['end_date']!='1970-01-01')
        {
        	$end_date = date('d-m-Y',strtotime($result['end_date']));
        }
        $data['form_data'] = array(
								  'data_id'=>$id,
								  'branch_code'=>$result['branch_code'],
								  'branch_name'=>$result['branch_name'],
								  'contact_no'=>$result['contact_no'],
								  //'rate_id'=>$result['rate_id'],
								  'email'=>$result['email'],
								  'address'=>$result['address'],
								  "address_second"=>$result['address2'],
                                  "address_third"=>$result['address3'],
                                  'country_id'=>$result['country_id'],
								  'city_id'=>$result['city_id'],
								  'state_id'=>$result['state_id'],
								  'username'=>$result['username'],
								  'password'=>"",
								  'cpassword'=>"",
                                  'branch_status'=>$result['status'],
                                  'login_status'=>$result['login_status'],
                                  'branch_type'=>$result['branch_type'],
                                  'start_date'=>$start_date,
				                  'end_date'=>$end_date,
				                  'contact_person'=>$result['contact_person'],

								  );
		
		if(isset($post) && !empty($post))
		{   
			$data['form_data'] = $this->_validate();
			if($this->form_validation->run() == TRUE)
			{
				$this->branch->save();
				echo 1;
				return false;
				
			}
			else
			{
				$data['form_error'] = validation_errors();  
			}		
		}
       $this->load->view('branch/add',$data);		
	  }  
	}
	 
     
	private function _validate()
	{
		$post = $this->input->post();    
	    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	    $this->form_validation->set_rules('branch_name', 'branch name', 'trim|required|min_length[2]');
		$this->form_validation->set_rules('contact_no', 'contact no.', 'trim|required|min_length[4]|max_length[12]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_email');
		//$this->form_validation->set_rules('rate_id', 'rate plan', 'trim|required');
		$this->form_validation->set_rules('country_id', 'country', 'trim|required');
		$this->form_validation->set_rules('state_id', 'state', 'trim|required');
		$this->form_validation->set_rules('city_id', 'city', 'trim|required');
		$this->form_validation->set_rules('address', 'address', 'trim|required');
		$this->form_validation->set_rules('username', 'username', 'trim|alpha_numeric|required|min_length[4]|max_length[15]|callback_check_username'); 
		if($post['data_id']==0 || !empty($post['password']) || !empty($post['cpassword']))
		{
			$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[15]');
			$this->form_validation->set_rules('cpassword', 'confirm password', 'trim|required|min_length[6]|max_length[15]|matches[password]'); 
		}

		//if($post['branch_type']=='0')
		//{
			$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
			$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
// 
		
		if ($this->form_validation->run() == FALSE) 
		{ 
			$password  = "";
			$cpassword = "";
			if(empty($post['data_id']) || $post['data_id']==0)
			{
				$password = $_POST['password'];
				$cpassword = $_POST['cpassword'];
			}

			$branch_code = generate_unique_id(1);
	        $data['form_data'] = array(
				'data_id'=>$_POST['data_id'],
				'branch_code'=>$branch_code,
				'branch_name'=>$_POST['branch_name'],
				'contact_no'=>$_POST['contact_no'],
				'email'=>$_POST['email'],
				//'rate_id'=>$_POST['rate_id'],
				'country_id'=>$_POST['country_id'],
				'state_id'=>$_POST['state_id'],
				'city_id'=>$_POST['city_id'],
				'address'=>$_POST['address'],
				'address_second'=>$_POST['address_second'],
				'address_third'=>$_POST['address_third'],
				'username'=>$_POST['username'],
				'password'=>$password,
				'cpassword'=>$cpassword,
				'branch_status'=>$_POST['branch_status'],
				'login_status'=>$_POST['login_status'],
				'branch_type'=>$post['branch_type'],
				'start_date'=>$post['start_date'],
				'end_date'=>$post['end_date'],
				'contact_person'=>$post['contact_person'],
				);  
			$data['form_error'] = validation_errors();  
			return $data['form_data'];
		} 	
	}

	public function check_username($str)
	{
		$this->load->model('general/general_model');
		$post = $this->input->post();
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
            return true;
		}
		else
		{
            $userdata = $this->general_model->check_username($str);
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
		$this->load->model('general/general_model');
		$post = $this->input->post();
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
            return true;
		}
		else
		{
            $userdata = $this->general_model->check_email($str); 
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
	   unauthorise_permission('1','4');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->branch->delete($id);
           $response = "Branch successfully deleted.";
           echo $response;
       }
	}

	function deleteall()
	{
		unauthorise_permission('1','4');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->branch->deleteall($post['row_id']);
			$response = "Branches successfully deleted.";
			echo $response;
	    }
	}


	public function view($id="")
	{  
	 unauthorise_permission('1','7');
	 if(isset($id) && !empty($id) && is_numeric($id))
	  { 	 
        $data['form_data'] = $this->branch->get_by_id($id);
	  	$data['page_title'] = $data['form_data']['branch_name']." detail";
        $this->load->view('branch/view',$data);		
      }
    }  


    ///// Branch Archive Start  ///////////////
    public function archive()
	{
		unauthorise_permission('1','5');
		$data['page_title'] = 'Branch Archive List';
		$this->load->helper('url');
		$this->load->view('branch/archive',$data);
	}

	public function archive_ajax_list()
	{ 
		unauthorise_permission('1','5');
		$users_data = $this->session->userdata('auth_users');
		$this->load->model('branch/branch_archive_model','branch_archive');
		

               $list = $this->branch_archive->get_datatables();
              
            
		$data = array();
		$no = $_POST['start'];
		$i = 1;
		$total_num = count($list);

		foreach ($list as $branch) { 
			$no++;
			$row = array();
			if($branch->status==1)
			{
				$status = '<font color="green">Active</font>';
			}	
			else{
				$status = '<font color="red">Inactive</font>';
			}
			///// State name ////////
			$state = "";
            if(!empty($branch->state))
            {
            	$state = " ( ".ucfirst(strtolower($branch->state))." )";
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
            ////////// Check list end ///////////// 
			$row[] = '<input type="checkbox" name="branch[]" class="checklist" value="'.$branch->id.'">'.$check_script;
			$row[] = $branch->branch_code;
			$row[] = $branch->branch_name; 
			$row[] = $branch->city.$state;
			$row[] = $branch->contact_no;
			$last_login_ip='';
			$last_login_time='';

			if(!empty($branch->last_login_ip) && isset($branch->last_login_ip))
			{
				$last_login_ip= $branch->last_login_ip;
			}
			else
			{
				$last_login_ip=='';
			}
			if(!empty($branch->last_login_time) && isset($branch->last_login_time) && $branch->last_login_time!="0000-00-00 00:00:00")
			{
				$last_login_time= date('d-m-Y h:i A',strtotime($branch->last_login_time));
			}
			else
			{
				$last_login_time=='';
			}
			$row[] = $status;
			$row[] = $last_login_time;			
			//$row[] = date('d-M-Y H:i A',strtotime($branch->created_date)); 
            ////Action button ////////////
            $btn_restore = "";
            $btn_delete = "";
            $btn_view = "";
            if(in_array('38',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_branch('.$branch->id.');" class=" btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore</a> ';
            }

            if(in_array('7',$users_data['permission']['action'])) 
            {
               $btn_view = ' <a onClick="return view_branch('.$branch->id.');" class="btn-custom" href="javascript:void(0)" title="View"><i class="fa fa-info-circle" aria-hidden="true"></i> View</a>';
            } 
           
            if(in_array('6',$users_data['permission']['action'])) 
            {
               $btn_delete = ' <a onClick="return trash_branch('.$branch->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            /////////////////////////////
			$row[] = $btn_restore.$btn_view.$btn_delete; 	   			 
		
			$data[] = $row;
			$i++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->branch_archive->count_all(),
						"recordsFiltered" => $this->branch_archive->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function restore($id="")
	{
	   unauthorise_permission('1','38');
	   $this->load->model('branch/branch_archive_model','branch_archive');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->branch_archive->restore($id);
           $response = "Branch successfully restore in branch list.";
           echo $response;
       }
	}

	function restoreall()
	{ 
		unauthorise_permission('1','38');
		$this->load->model('branch/branch_archive_model','branch_archive');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->branch_archive->restoreall($post['row_id']);
			$response = "Branch successfully restore in Branch list.";
			echo $response;
	    }
	}

	public function trash($id="")
	{
		unauthorise_permission('1','6');
		$this->load->model('branch/branch_archive_model','branch_archive');
       if(!empty($id) && $id>0)
       {
       	   $result = $this->branch_archive->trash($id);
           $response = "Branch successfully deleted parmanently.";
           echo $response;
       }
	}

	function trashall()
	{
		unauthorise_permission('1','6');
		$this->load->model('branch/branch_archive_model','branch_archive');
		$post = $this->input->post();  
	    if(!empty($post))
	    {
			$result = $this->branch_archive->trashall($post['row_id']);
			$response = "Branches successfully deleted parmanently.";
			echo $response;
	    }
	}
    ///// Branch Archive end  ///////////////

    public function permission($branch_id="")
   {
   	  unauthorise_permission('1','129');
      if(!empty($branch_id) && $branch_id>0)
      { 
         $post = $this->input->post();
         if(!empty($post))
         {
           $this->branch->save_branch_permission($branch_id);
           echo 'Branch permission successfully assigned.';
           return false;
           //echo "<pre>"; print_r($post);die;
         }
         $data['branch_id'] = $branch_id;
         $data['page_title'] = "Branch Permission";
         $data['section_list'] = $this->branch->permission_section_list($branch_id);
         
         $this->load->view('branch/permission',$data);
      }
   }




   public function advance_search()
   {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        $data['form_data'] = array(
                                    "branch_type"=>"1",
                                     "status"=>"1",
                                    
                                  );
        if(isset($post) && !empty($post))
        {
            
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('branch_search', $marge_post);
        }
        else
        {
        	$this->session->set_userdata('branch_search', $data['form_data']);
        }
        $branch_search = $this->session->userdata('branch_search');
        if(isset($branch_search) && !empty($branch_search))
        {
            $data['form_data'] = $branch_search;
        }
        //print_r($branch_search); exit;
        //$this->load->view('appointment/advance_search',$data);
    }

      public function log($branch_id="",$users_id='')
   {
   	  unauthorise_permission('1','129');
   	  
   	  $users_data = $this->session->userdata('auth_users');
       
      if(!empty($branch_id) && $branch_id>0)
      { 
         
         $data['per_ids'] = branch_alloted_module_permissionwise_total_log($users_id);
         $data['user_list'] = $this->branch->get_users_login_list($branch_id);
         $data['branch_id'] = $branch_id;  
         //print_r($data['total_count']);
         $data['page_title'] = "Log";
         $data['section_list'] = $this->branch->permission_section_list($branch_id);
         $this->load->view('branch/log',$data);
      }
   }
   
   public function send_renew_mail()
	{  
			$users_data = $this->session->userdata('auth_users');
			if($users_data['users_role']==1)	            
	        {
	        	$branch_list = $this->branch->get_branch_list();
	        	//echo count($list);
	        	/*echo "<pre>"; print_r($template_data); */ 
	        	//echo "<pre>"; print_r($branch_list); exit;
	        	if(!empty($branch_list))
	        	{
	        		$i='1';
	        		foreach ($branch_list as $branch) 
	        		{
	        			$branch_name = $branch->branch_name;
	        			$contact_person = $branch->contact_person;
	        			$end_date = $branch->end_date;
	        			$branch_email = $branch->email;
	        			$template_data = $this->branch->get_renewal_mail_template();
	        			if(!empty($template_data))
	        			{
	        				
	        				foreach ($template_data as $templates) 
	        				{
	        					$template_data = $this->branch->get_renewal_mail_template();
	        					//echo "<pre>"; print_r($template_data); exit;
				        		$template_date=Date('Y-m-d', strtotime("".$templates->days." days")); 
        						//$template_date = 
	        					if($end_date == $template_date)
	        					{
									$message_template_formate =  str_replace(array_keys(array('{contact_person}'=>$contact_person,'{hospital_name}'=>$branch_name,'{expiry_date}'=>date('d-M-Y',strtotime($end_date)))),array('{contact_person}'=>$contact_person,'{hospital_name}'=>$branch_name,'{expiry_date}'=>date('d-M-Y',strtotime($end_date))),$templates->template);

									$this->load->library('general_functions'); 
									$this->general_functions->send_renewal_mail($branch_email,$message_template_formate);
								}
								
							
				        					
				        	}
		        		}
	        			
	        		}
	        	}
	        }

	        
		 
	}
 
}
?>