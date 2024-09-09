<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

  function __construct() 
  {
   parent::__construct();  
   auth_users();  
   $this->load->model('canteen/products/products_model','products');
   $this->load->library('form_validation'); 
 }

 public function index()
 { 
        //unauthorise_permission('163','940');
        $users_data = $this->session->userdata('auth_users');
         $this->session->unset_userdata('opd_search');
        $data['page_title'] = 'Products list'; 
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
  $this->load->view('canteen/products/list',$data);
}

public function ajax_list()
{ 
  $users_data = $this->session->userdata('auth_users');
    //unauthorise_permission('163','940');

  $sub_branch_details = $this->session->userdata('sub_branches_data');
  $parent_branch_details = $this->session->userdata('parent_branches_data');

  $list = $this->products->get_datatables();

  $data = array();
  $no = $_POST['start'];
  $i = 1;
  $total_num = count($list);
  foreach ($list as $products) {
         // print_r($products);die;
    $no++;
    $row = array();
    if($products->status==1)
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
      if($users_data['parent_id']==$products->branch_id){
       $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$products->id.'">'.$check_script;
     } 
     else{
       $row[]='';
     }
     $row[] = $products->product_code; 
     $row[] = $products->product_name; 
     if($products->type==1)
     {
      $type = 'Readymade';
    }   
    else{
      $type = 'Manufactured';
    } 
    $row[] = $type; 
    $row[] = date('d-M-Y H:i A',strtotime($products->created_date));
    $row[] = $status;



    $btnedit='';
    $btndelete='';

    if($users_data['parent_id']==$products->branch_id){
          //if(in_array('942',$users_data['permission']['action'])){
     $btnedit = '<a  class="btn-custom" href="'.base_url().'canteen/products/edit/'.$products->id.'" style="'.$products->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          //}
          //if(in_array('943',$users_data['permission']['action'])){
     $btndelete = '<a class="btn-custom" onClick="return delete_products('.$products->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
          //}
   }

   $row[] = $btnedit.$btndelete;
   $data[] = $row;
   $i++;
 }

 $output = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $this->products->count_all(),
  "recordsFiltered" => $this->products->count_filtered(),
  "data" => $data,
);

 echo json_encode($output);
}


public function add()
{
        //unauthorise_permission('163','941');
  $data['page_title'] = "Add Products";  
  $this->load->model('canteen/general/common_model','common_model');
  $data['unit_lists']=$this->common_model->unit_list();
  $data['category_lists']=$this->common_model->category_list(); 
  
  $post = $this->input->post();
  $product_code=generate_unique_id(69);
 // print_r($post);die;
  if(empty($post))
  {
       $this->session->unset_userdata('product_combo_list'); 
  }
 
  
  $data['form_error'] = []; 
  $data['form_data'] = array(
    'data_id'=>"", 
    'branch_id'=>"",
    'product_code'=>$product_code,
    'product_name'=>"",
    'cateory_id'=>"",
    'quantity'=>"",
    'product_type'=>"",
    'unit_id'=>"",
    'alert_qty'=>"",
    'product_cost'=>"",
    'product_price'=>"",
    'description'=>"",
    'comb_prod_price'=>"", 
    'comb_prod_qty'=>"",
    'comb_prod_code'=>"",
    'status'=>""
  );    

  if(isset($post) && !empty($post))
  {   
    $data['form_data'] = $this->_validate();
    if($this->form_validation->run() == TRUE)
    {
      $this->products->save();
      $this->session->set_flashdata('success','Produts successfully done.');
      redirect(base_url('canteen/products'));

    }
    else
    {

      $data['form_error'] = validation_errors(); 
               // print_r($data['form_error']);die;

    }     
  }
  $this->load->view('canteen/products/add',$data);       
}

private function _validate()
{

  $post = $this->input->post();  
      //  print_r($post);die;  
  $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
  $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required||callback_check_product_name'); 
  $this->form_validation->set_rules('product_type', 'Product Type', 'trim|required');
  $this->form_validation->set_rules('product_code', 'Product Code', 'trim|required|callback_check_product_code'); 
  //$this->form_validation->set_rules('product_category', 'Product Category', 'trim|required|callback_check_product_name'); 
  
  if ($this->form_validation->run() == FALSE) 
  {  
    $product_code = generate_unique_id(69); 
    $data['form_data'] = array(
      'data_id'=>$post['data_id'], 
      'branch_id'=>$post['parent_id'],
      'product_code'=>$product_code,
      'product_name'=>$post['product_name'],
      'category_id'=>$post['category_id'],
      'product_type'=>$post['product_type'],
      'unit_id'=>$post['unit1_id'],
      'alert_qty'=>$post['min_qty_alert'],
      'product_cost'=>$post['product_cost'],
      'product_price'=>$post['product_price'],
      'product_detail'=>$post['description'],
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
      $data_cat= $this->products->get_by_id($post['data_id']);
    
        $product_code = $this->common->check_main_product_code($str);

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
      $product_code = $this->common->check_main_product_code($str);
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
   
}

public function check_product_name($str)
{

  $post = $this->input->post();
  if(!empty($str))
  {
    $this->load->model('canteen/general/common_model','common'); 
    if(!empty($post['data_id']) && $post['data_id']>0)
    {
      $data_cat= $this->products->get_by_id($post['data_id']);
    
        $product_code = $this->common->check_main_product_name($str);

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
      $product_code = $this->common->check_main_product_name($str);
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
          $data['page_title'] = "Edit Product";  
        $this->load->model('canteen/general/common_model','common_model');
        $data['unit_lists']=$this->common_model->unit_list();
      $data['category_lists']=$this->common_model->category_list(); 
      $result=$this->products->get_by_id($id);
      if(empty($post))
      {
          $this->session->unset_userdata('product_combo_list');
      }
      
        $post = $this->input->post();
        
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>$id, 
                                  'branch_id'=>$result['branch_id'],
                                  'product_code'=>$result['product_code'],
                                  'product_name'=>$result['product_name'],
                                  'product_type'=>$result['product_type'],
                                  'category_id'=>$result['pro_cat_id'],
                                  'quantity'=>$result['quantity'],
                                  'type'=>$result['type'],
                                  'unit_id'=>$result['unit_id'],
                                  'alert_qty'=>$result['min_qty_alert'],
                                  'product_price'=>$result['product_price'],
                                  'product_detail'=>$result['description'],
                                  'product_cost'=>$result['product_cost'],
                                  'status'=>$result['status']
                                  );    

          if(isset($post) && !empty($post))
          {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
              $this->products->save();
              $this->session->set_flashdata('success','Master entry successfully updated.');
                redirect(base_url('canteen/products'));

            }
            else
            {
              $data['form_error'] = validation_errors();  
            }     
          }
          $this->load->view('canteen/products/add',$data);       
        }
      }
       public function delete($id="")
    {  
       //unauthorise_permission('163','943');
       if(!empty($id) && $id>0)
       {
           $result = $this->products->delete($id);
           $response = "Product successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        //unauthorise_permission('163','943');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->products->deleteall($post['row_id']);
            $response = "Product successfully deleted.";
            echo $response;
        }
    }
    
    /* Autocomplete */
    function get_product_values($vals="")
    {

        if(!empty($vals))
        {
            $result = $this->products->get_product_values($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
    public function item_payment_calculation()
    {
          $post = $this->input->post();

          if(isset($post) && !empty($post))
          {   
          // $product_combo_list = array();
            $item_new_array=[];
            $product_combo_list = $this->session->userdata('product_combo_list');
          //  print_r($product_combo_list);
           
           if(isset( $product_combo_list) && !empty($product_combo_list))
            {
               foreach($product_combo_list as $stock_item_list)
              {
                $item_new_array[]= $stock_item_list['product_name'];
              }
            }
             
              
             if(in_array($post['product_name'],$item_new_array))
             {
                  $response_data = array('error'=>1, 'message'=>'Item Already Added Please Increase Quantity');
                   $json = json_encode($response_data,true);
                   echo $json;
             }
             else
             {
                
                //   print_r($product_combo_list);
                  if(isset($product_combo_list) && !empty($product_combo_list))
                  {
                     
                     $product_combo_list= $product_combo_list; 
                  }
                 /* else
                  {
                     $product_combo_list = '';
                  }
               
           */
                  $product_combo_list[] = array('product_id'=>$post['product_id'],'total_price'=>$post['total_price'],'product_code'=>$post['product_code'], 'quantity'=>$post['quantity'],'price'=>$post['price'],'product_name'=>$post['product_name']);
                /*  $amount_arr = array_column($product_combo_list, 'price'); 
                  $total_amount = array_sum($amount_arr);*/
                  $this->session->set_userdata('product_combo_list', $product_combo_list);

                  
                  $html_data = $this->product_combo_list();
                  $total = $total_amount;

                  if($total==0)
                  {
                  $totamount = '0.00';
                  }
                  else
                  {
                  $totamount = number_format($total,2,'.','');
                  }

                  $response_data = array('html_data'=>$html_data);
                //  $product_combo_list = array('total_amount'=>$totamount,'total_amount'=>number_format($total_amount,2,'.',''));
                 // $this->session->set_userdata('product_combo_list', $product_combo_list);
                  $json = json_encode($response_data,true);
                  echo $json;

             }
            
            
       }
    }
    
     private function product_combo_list()
    {
        $product_combo_list = $this->session->userdata('product_combo_list');
     //  print_r($product_combo_list);die;
    
         $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.booked_checkbox').prop('checked', false);
                                  } else {
                                      $('.booked_checkbox').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      /* $rows = '<thead><tr>           
                    <th>Product(Name-Code)</th>
                    <th class="text-center" style="width:50px;">Qty</th>
                    <th class="text-center" style="width:80px;">Total Price</th>
                    <th class="text-center" style="width:80px;">Action</th>
                  </tr></thead>';*/
           if(isset($product_combo_list) && !empty($product_combo_list))
           {
              
              $i = 1;
              
              foreach($product_combo_list as $purchase_item_list)
              {
                 
               /*  <td width="60" align="center">';
                            if($available_qty >=1)
                            {
                             $rows .= '<input type="checkbox" name="item_id[]" class="part_checkbox booked_checkbox" value="'.$purchase_item_list['item_id'].'">'; 
                            }
                            else
                            {
                              $rows .= ''; 
                            }
                           

                             $rows .= '</td>*/
                 $rows .= '<tr>
                           
    
                            <td>'.$purchase_item_list['product_name'].'-'.$purchase_item_list['product_code'].'<input type="hidden" name="comb_prod_code[]" value="'.$purchase_item_list['product_code'].'" id="product_code_'.$purchase_item_list['product_id'].'"/><input type="hidden" name="comb_prod_name[]" value="'.$purchase_item_list['product_name'].'" id="product_name_'.$purchase_item_list['product_id'].'"/></td>
                            <td><input type="text" value="'.$purchase_item_list['quantity'].'" name="comb_prod_qty[]" id="quantity_'.$purchase_item_list['product_id'].'" onkeyup="return cal_price('.$purchase_item_list['product_id'].');"/></td>
                            <td>'.$purchase_item_list['total_price'].'<input type="hidden" name="comb_prod_price[]" value="'.$purchase_item_list['total_price'].'" id="total_price_'.$purchase_item_list['product_id'].'"/></td>
                            <td class="text-center"> <a href="javascript:void(0);" id="del_'.$purchase_item_list['product_id'].'" onClick="return delete_combo_item('.$purchase_item_list['product_id'].');" class="btn-custom" title="Remove Item">Delete</a></td>
                           
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Particular data not available.</div></td>
                        </tr></body>';
           }

           
           return $rows;
    }
    
    
     public function remove_stock_purchase_item_list()
    {
       $post =  $this->input->post();
       
       if(isset($post['product_id']) && !empty($post['product_id']))
       {
           $product_combo_list = $this->session->userdata('product_combo_list');
          // $this->db->delete('hms_canteen_products',array('id'=>$post['product_id']));
         //  $this->db->delete('hms_canteen_products_combo',array('id'=>$post['product_id']));
          // $product_list=$this->products->combo_prod_list($post['product_id']);
        
           $particular_id_list = array_column($product_combo_list, 'product_id'); 
           $i=0;
           foreach($product_combo_list as $item_list)
           { 
            
              if(in_array($item_list['product_id'],$post['product_id']))
              {  
               
                 unset($item_list[$i]);

  
              }

           }
            
                 // $this->session->set_userdata('product_combo_list', $product_list);
             
          $product_combo_list= $this->session->userdata('product_combo_list');
         // $product_combo_list='';
       /* $amount_arr = array_column($product_combo_list, 'amount'); 
        $total_amount = array_sum($amount_arr);*/
        $this->session->set_userdata('product_combo_list',$product_combo_list);
        $html_data = $this->product_combo_list();
       /* $particulars_charges = $total_amount;
        $bill_total = $total_amount;*/
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
       }
    }
    
      public function remove_combo_item_list()
    {
       $post =  $this->input->post();
       $this->db->delete('hms_canteen_products_combo',array('id'=>$post['id']));
       return 1;
      
    }

    /* Autocomplete*/

  
}
?>