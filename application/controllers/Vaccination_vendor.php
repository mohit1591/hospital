<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_vendor extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('vendor/vendor_model','vendor');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
       unauthorise_permission(182,1042);
        $data['page_title'] = 'Vendor List'; 
        $this->load->view('vendor/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(182,1042);
        $list = $this->vendor->get_datatables();  
        //print_r( $list);
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $vendor) { 
            $no++;
            $row = array();
            if($vendor->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }

            if($vendor->vendor_type==1)
            {
            $vendor_type = 'Medicine';
            }   
            else if($vendor->vendor_type==2)
            {
            $vendor_type = 'Expense';
            }
            else if($vendor->vendor_type==3)
            {
            $vendor_type = 'Inventory';
            }
            else if($vendor->vendor_type==4)
            {
            $vendor_type = 'Vaccination';
            }
            else
            {
              $vendor_type='';
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
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$vendor->id.'">'.$check_script;
            $row[] = $vendor->name;
            $row[] = $vendor->vendor_id;
            $row[] = $vendor_type;
            $row[] = $vendor->mobile;
            $row[] = $vendor->email;
            $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($vendor->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('1044',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_vendor('.$vendor->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vendor->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if(in_array('1049',$users_data['permission']['action'])){
                $btnview=' <a class="btn-custom" onclick="return view_vendor('.$vendor->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
           }
            if(in_array('1045',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_vendor('.$vendor->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
               }
               if(in_array('1053',$users_data['permission']['action'])){
                if($vendor->vendor_type==4){
                    $btn_purchase = ' <div class="btn-ipd"><a class="btn-custom" href="'.base_url('vaccine_purchase/add/'.$vendor->id).'" style="'.$vendor->id.'" title="Purchase Vaccine"><i class="fa fa-plus"></i>Purchase Vaccine</a></div>';
                }else{
                  $btn_purchase="";
                }
              
               }
               if(in_array('1051',$users_data['permission']['action'])){
               if($vendor->vendor_type==4){
                $btn_purchase_return = ' <div class="btn-ipd"><a class="btn-custom" href="'.base_url('vaccine_purchase_return/add/'.$vendor->id).'" style="'.$vendor->id.'" title="Purchase return"><i class="fa fa-plus"></i>Purchase Return</a></div>';
               }else{
                $btn_purchase_return="";
               }
                
              

             }       
            $row[] = $btnedit.$btnview.$btn_purchase.$btn_purchase_return.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vendor->count_all(),
                        "recordsFiltered" => $this->vendor->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

	public function add($type='')
	{
    unauthorise_permission(182,1043);
		$this->load->model('general/general_model'); 
		$data['page_title'] = "Add Vendor";
		$data['form_error'] = [];
		$data['vendor_list'] = $this->vendor->vendor_list(); 
		$reg_no = generate_unique_id(11);
		$post = $this->input->post();
        $data['type']= $type;
		$data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "vendor_id"=>$reg_no,
                                    "name"=>"",
                                    'vendor_gst'=>'',
                                    "mobile"=>"",
                                    "address"=>"",
                                    "address2"=>"",
                                    "address3"=>"",
                                    "vendor_type"=>'1',
                                    'vendor_email'=>"",
                                    "status"=>"1", 
                                     "country_code"=>"+91"
			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vendor->save();
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       $this->load->view('vendor/add',$data);
	}

	public function edit($id="")
    {
      
      unauthorise_permission(182,1044);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->vendor->get_by_id($id); 
        
        $reg_no = generate_unique_id(11);
        $this->load->model('general/general_model');
    	  $data['vendor_list'] = $this->vendor->vendor_list();
        $data['page_title'] = "Update Vendor";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['type']= $this->uri->segment(1);
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "vendor_id"=>$result['vendor_id'],
                                    'name'=>$result['name'],
                                    'vendor_gst'=>$result['vendor_gst'],
                                    'mobile'=>$result['mobile'],
                                    'vendor_email'=>$result['email'],
                                    "vendor_type"=>$result['vendor_type'],
                                    'address'=>$result['address'],
                                    'address2'=>$result['address2'],
                                    'address3'=>$result['address3'],
                                    "status"=>$result['status'],
                                    "country_code"=>"+91"
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vendor->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->load->view('vendor/add',$data);       
      }
    }
     
    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'Vendor Name', 'trim|required');
        $check_ids= $this->vendor->get_by_id($_POST['data_id']);
       if(!empty($_POST['data_id']) && $check_ids['email']==$_POST['vendor_email']){
        }else{
        $this->form_validation->set_rules('vendor_email', 'Email', 'trim|valid_email|is_unique[hms_medicine_vendors.email]');
        }
       $this->form_validation->set_rules('vendor_type', 'Type', 'trim|required'); 
        //$this->form_validation->set_rules('conversion', 'Conversion', 'trim|required');
       // $this->form_validation->set_rules('purchase_rate', 'Purchase rate', 'trim|required|numeric'); 
       
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(11); 
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'], 
                                    "vendor_id"=>$reg_no,
                                    "name"=>$_POST['name'],
                                    "address"=>$_POST['address'],
                                     "address2"=>$_POST['address2'],
                                      "address3"=>$_POST['address3'],
                                    'vendor_email'=>$_POST['vendor_email'],
                                    "mobile"=>$_POST['mobile'],
                                    "vendor_type"=>$_POST['vendor_type'],
                                    "status"=>$_POST['status'],
                                     "country_code"=>"+91"
                                  );  
            return $data['form_data'];
        }   
    }

    public function delete($id="")
    {
        unauthorise_permission(182,1045);
       if(!empty($id) && $id>0)
       {
           $result = $this->vendor->delete($id);
           $response = "Vendor successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(182,1045);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vendor->deleteall($post['row_id']);
            $response = "Vendor successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        unauthorise_permission(182,1049);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vendor->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('vendor/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(182,1048);
        $data['page_title'] = 'Vendor Archive List';
        $this->load->helper('url');
        $this->load->view('vendor/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(182,1048);
        $this->load->model('vendor/vendor_archive_model','vendor_archive'); 

        $list = $this->vendor_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $vendor_type='';
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vendor) { 
            $no++;
            $row = array();
            if($vendor->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            if($vendor->vendor_type==1)
            {
            $vendor_type = 'Medicine';
            }   
            else if($vendor->vendor_type==2)
            {
            $vendor_type = 'Expense';
            }
            else if($vendor->vendor_type==3)
            {
            $vendor_type = 'Inventory';
            }
            else if($vendor->vendor_type==4)
            {
            $vendor_type = 'Vaccination';
            }
            else
            {
              $vendor_type='';
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

            $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$vendor->id.'">'.$check_script;
            $row[] = $vendor->name;
            $row[] = $vendor->vendor_id;
            $row[] = $vendor_type; 
            $row[] = $vendor->mobile;
            $row[] = $vendor->email;
            $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($vendor->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           if(in_array('1047',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_vendor('.$vendor->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('1046',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$vendor->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vendor_archive->count_all(),
                        "recordsFiltered" => $this->vendor_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(182,1047);
        $this->load->model('vendor/vendor_archive_model','vendor_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vendor_archive->restore($id);
           $response = "Vendor successfully restore in vendor list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(182,1047);
        $this->load->model('vendor/vendor_archive_model','vendor_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vendor_archive->restoreall($post['row_id']);
            $response = "Vendor successfully restore in vendor list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(182,1046);
        $this->load->model('vendor/vendor_archive_model','vendor_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vendor_archive->trash($id);
           $response = "Vendor successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(182,1046);
        $this->load->model('vendor/vendor_archive_model','vendor_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vendor_archive->trashall($post['row_id']);
            $response = "Vendor successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function vendor_dropdown($type="")
  {
      $vendor_list = $this->vendor->vendor_list($type);
      $dropdown = '<option value="">Select Vendor</option>'; 
      if(!empty($vendor_list))
      {
        foreach($vendor_list as $vendor)
        {
           $dropdown .= '<option value="'.$vendor->id.'">'.$vendor->name.'</option>';
        }
      } 
      echo $dropdown; 
  }




}
