<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_entry extends CI_Controller {

  function __construct() 
  {
   parent::__construct();  
   auth_users();  
   $this->load->model('canteen/master_entry/master_entry_model','master_entry');
   $this->load->library('form_validation'); 
 }

 public function index()
 { 
        //unauthorise_permission('163','940');
        $users_data = $this->session->userdata('auth_users');
         $this->session->unset_userdata('search_data');
  $data['page_title'] = 'Master Entry list'; 
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
        $data['form_data'] = array('patient_name'=>'','mobile_no'=>'','booking_code'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date); 
  $this->load->view('canteen/master_entry/list',$data);
}

public function ajax_list()
{ 
  $users_data = $this->session->userdata('auth_users');
    //unauthorise_permission('163','940');

  $sub_branch_details = $this->session->userdata('sub_branches_data');
  $parent_branch_details = $this->session->userdata('parent_branches_data');

  $list = $this->master_entry->get_datatables();

  $data = array();
  $no = $_POST['start'];
  $i = 1;
  $total_num = count($list);
  foreach ($list as $master_entry) {
         // print_r($master_entry);die;
    $no++;
    $row = array();
    if($master_entry->status==1)
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
      if($users_data['parent_id']==$master_entry->branch_id){
       $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$master_entry->id.'">'.$check_script;
     } 
     else{
       $row[]='';
     }
     $row[] = $master_entry->product_code; 
     $row[] = $master_entry->product_name; 
     if($master_entry->type==1)
     {
      $type = 'Readymade';
    }   
    else{
      $type = 'Manufactured';
    } 
    $row[] = $type; 
    $row[] = date('d-M-Y H:i A',strtotime($master_entry->created_date));
    $row[] = $status;



    $btnedit='';
    $btndelete='';

    if($users_data['parent_id']==$master_entry->branch_id){
          //if(in_array('942',$users_data['permission']['action'])){
     $btnedit = '<a  class="btn-custom" href="'.base_url().'canteen/master_entry/edit/'.$master_entry->id.'" style="'.$master_entry->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          //}
          //if(in_array('943',$users_data['permission']['action'])){
     $btndelete = '<a class="btn-custom" onClick="return delete_master('.$master_entry->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
          //}
   }

   $row[] = $btnedit.$btndelete;
   $data[] = $row;
   $i++;
 }

 $output = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $this->master_entry->count_all(),
  "recordsFiltered" => $this->master_entry->count_filtered(),
  "data" => $data,
);

 echo json_encode($output);
}


public function add()
{
        //unauthorise_permission('163','941');
  $data['page_title'] = "Add Master Entry";  
  $this->load->model('canteen/general/common_model','common_model');
  $data['unit_lists']=$this->common_model->unit_list();
  $data['category_lists']=$this->common_model->category_list(); 
  $post = $this->input->post();

$product_code=generate_unique_id(68);
  $data['form_error'] = []; 
  $data['form_data'] = array(
    'data_id'=>"", 
    'branch_id'=>"",
    'product_code'=>$product_code,
    'product_name'=>"",
    'cateory_id'=>"",
    'type'=>"",
    'unit1_id'=>"",
    'unit2'=>"",
    'conversion'=>"",
    'min_qty_alert'=>"",
    'expiry_days'=>"",
    'mrp'=>"",
    'purchase_rate'=>"",
    'description'=>"",
    'sgst'=>"",
    'cgst'=>"",
    'igst'=>"",
    'status'=>"1"
  );    

  if(isset($post) && !empty($post))
  {   
    $data['form_data'] = $this->_validate();
    if($this->form_validation->run() == TRUE)
    {
      $this->master_entry->save();
      $this->session->set_flashdata('success','Master entry successfully done.');
      redirect(base_url('canteen/master_entry'));

    }
    else
    {

      $data['form_error'] = validation_errors(); 
               // print_r($data['form_error']);die;

    }     
  }
  $this->load->view('canteen/master_entry/add',$data);       
}

private function _validate()
{

  $post = $this->input->post();  
      //  print_r($post);die;  
  $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
  $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required|callback_check_product_name'); 
  $this->form_validation->set_rules('type', 'Product Type', 'trim|required');
  $this->form_validation->set_rules('product_code', 'Product Code', 'trim|required|callback_check_product_code'); 
  $this->form_validation->set_rules('product_category', 'Product Category', 'trim|required');  
  $this->form_validation->set_rules('conversion','Conversion', 'trim|required');  

  if ($this->form_validation->run() == FALSE) 
  {  
    /*$reg_no = generate_unique_id(2); */
    $data['form_data'] = array(
      'data_id'=>$post['data_id'], 
      'branch_id'=>$post['parent_id'],
      'product_code'=>$post['product_code'],
      'product_name'=>$post['product_name'],
      'category_id'=>$post['category_id'],
      'type'=>$post['product_type_id'],
      'unit1_id'=>$post['unit1_id'],
      'unit2'=>$post['unit2'],
      'conversion'=>$post['conversion'],
      'min_qty_alert'=>$post['min_qty_alert'],
      'expiry_days'=>$post['expiry_days'],
      'mrp'=>$post['mrp'],
      'purchase_rate'=>$post['purchase_rate'],
      'description'=>$post['description'],
      'sgst'=>$post['sgst'],
      'cgst'=>$post['cgst'],
      'igst'=>$post['igst'],
      'status'=>$post['status'],
    ); 
    return $data['form_data'];
  }   
}

public function check_product_code($str)
{

  $post = $this->input->post();
  if(!empty($str))
  {
    $this->load->model('canteen/general/common_model','common'); 
    if(!empty($post['data_id']) && $post['data_id']>0)
    {
      $data_cat= $this->master_entry->get_by_id($post['data_id']);
    
        $product_code = $this->common->check_product_code($str);

        if(empty($product_code))
        {
          return true;
        }
        else
        {
          $this->form_validation->set_message('check_product_code' ,'This Product code already exists.');
          return false;
        }
      
    }
    else
    {
      $product_code = $this->common->check_product_code($str);
      if(empty($product_code))
      {
       return true;
     }
     else
     {
      $this->form_validation->set_message('check_product_code','This Product code already exists.');
      return false;
    }
  }  
}
    /*else
    {
     
      $this->form_validation->set_message('product_code', 'This Product code  is required.');
            return false; 
          } */
        }
        
public function check_product_name($str)
{
    
      $post = $this->input->post();
      if(!empty($str))
      {
        $this->load->model('canteen/general/common_model','common'); 
        if(!empty($post['data_id']) && $post['data_id']>0)
        {
          $data_cat= $this->master_entry->get_by_id($post['data_id']);
        
            $product_code = $this->common->check_product_name($str);
    
            if(empty($product_code))
            {
              return true;
            }
            else
            {
              $this->form_validation->set_message('check_product_name' ,'This Product name already exists.');
              return false;
            }
          
        }
        else
        {
          $product_code = $this->common->check_product_code($str);
          if(empty($product_code))
          {
           return true;
         }
         else
         {
          $this->form_validation->set_message('check_product_name','This Product name already exists.');
          return false;
        }
      }  
    }
   
}


        public function edit($id="")
        {
           
     //unauthorise_permission('163','942');
         if(isset($id) && !empty($id) && is_numeric($id))
         {      
          $data['page_title'] = "Edit Master Entry";  
        $this->load->model('canteen/general/common_model','common_model');
        $data['unit_lists']=$this->common_model->unit_list();
      $data['category_lists']=$this->common_model->category_list(); 
      $result=$this->master_entry->get_by_id($id);
     
        $post = $this->input->post();
        
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>$id, 
                                  'branch_id'=>$result['branch_id'],
                                  'product_code'=>$result['product_code'],
                                  'product_name'=>$result['product_name'],
                                  'category_id'=>$result['pro_cat_id'],
                                  'type'=>$result['type'],
                                  'unit1_id'=>$result['unit_id'],
                                  'unit2'=>$result['unit_second_id'],
                                  'conversion'=>$result['conversion'],
                                  'min_qty_alert'=>$result['min_qty_alert'],
                                  'expiry_days'=>$result['expiry_days'],
                                  'mrp'=>$result['mrp'],
                                  'purchase_rate'=>$result['purchase_rate'],
                                  'description'=>$result['description'],
                                  'sgst'=>$result['sgst'],
                                  'cgst'=>$result['cgst'],
                                  'igst'=>$result['igst'],
                                  'status'=>$result['status']
                                  );    

          if(isset($post) && !empty($post))
          {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
              $this->master_entry->save();
              $this->session->set_flashdata('success','Master entry successfully updated.');
                redirect(base_url('canteen/master_entry'));

            }
            else
            {
              $data['form_error'] = validation_errors();  
            }     
          }
          $this->load->view('canteen/master_entry/add',$data);       
        }
      }
       public function delete($id="")
    {  
       //unauthorise_permission('163','943');
       if(!empty($id) && $id>0)
       {
           $result = $this->master_entry->delete($id);
           $response = "Master Entry successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        //unauthorise_permission('163','943');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->master_entry->deleteall($post['row_id']);
            $response = "Master Entry successfully deleted.";
            echo $response;
        }
    }
    
     public function reset_search()
    {
        $this->session->unset_userdata('customer_search');
    }

    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();

        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['form_data'] = array(
                                    "start_date"=>'',//date('d-m-Y')
                                    "end_date"=>'',//date('d-m-Y')
                                  );
                                 
        if(isset($post) && !empty($post))
        {
            $merge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('search_data', $merge_post);
        }
        $search_data = $this->session->userdata('search_data');
        if(isset($search_data) && !empty($search_data))
        {
            $data['form_data'] = $search_data;
        }
        $this->load->view('opd/advance_search',$data);
    }
}
?>