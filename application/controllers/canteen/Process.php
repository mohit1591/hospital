<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Process extends CI_Controller {

  function __construct() 
  {
     parent::__construct();  
     auth_users();  
     $this->load->model('canteen/process/process_model','process');
     $this->load->library('form_validation');
  }

  public function index()
  {
    $data['page_title'] = 'Process List';
    $this->load->view('canteen/process/list', $data);
  }
  
  public function ajax_list()
    { 
       //unauthorise_permission('170','984');
       $users_data = $this->session->userdata('auth_users');
       $list = $this->process->get_datatables();
       $this->load->model('canteen/stock_item/stock_item_model','stock_item');
      
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $stock_item) {
         // print_r($Category);die;
            $no++;
            $row = array();
            if($stock_item->status==1)
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
          if($users_data['parent_id']==$stock_item->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$stock_item->id.'">'.$check_script;
          }else{
               $row[]='';
          } 
          
           $row[] = $stock_item->item_name;
            $row[] = $stock_item->item_qty; 
             $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($stock_item->created_date));
          
           /* $qty_data = $this->stock_item->get_item_quantity($stock_item->id,$stock_item->category_id);
          
            $medicine_total_qty = $qty_data['total_qty'];
            $row[] = $stock_item->item_code;
            $row[] = $stock_item->item; 
            $row[] = $stock_item->price; 
            $row[] = $stock_item->category;
            if($stock_item->min_alert>=$qty_data['total_qty'])
            {
            $medicine_total_qty = '<div class="m_alert_red">'.$qty_data['total_qty'].'</div>';
            }
            if($qty_data['total_qty']>=0)
            {
            $row[] = $medicine_total_qty;
            }
            else
            {
            $row[]='0';
            }
            $row[] = $stock_item->min_alert;
            $row[] = $stock_item->unit;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($stock_item->created_date)); */
           
          $btnedit='';
          $btndelete='';
        
          if($users_data['parent_id']==$stock_item->branch_id){
               //if(in_array('986',$users_data['permission']['action'])){
                    $btnedit = ' <a onClick="return edit_stock_item('.$stock_item->id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock_item->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               //}
               // if(in_array('987',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_stock_item('.$stock_item->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               //}
          }
      
             $row[] = $btnedit.$btndelete; 
             
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->process->count_all(),
                        "recordsFiltered" => $this->process->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
  
/*  public function add()
  {
     
    $data['page_title'] = 'Add Process';
    $post = $this->input->post();
    
   // echo '<pre>'; print_r($post); die;
    $data['form_error'] = []; 
    $data['form_data'] = array(
                              'item_name'=>"",
                               'item_qty'=>"",
                               'select_item'=>"",
                               'select_item_qty'=>""
                              );    

    if(isset($post) && !empty($post))
    {   
        $data['form_data'] = $this->_validate();
        if($this->form_validation->run() == TRUE)
        {
            $this->process->save();
            echo 1;
            return false;
            
        }
        else
        {
            $data['form_error'] = validation_errors();  
        }    
    $this->load->view('canteen/process/add', $data);
  }

}*/
  public function add()
    {
        //unauthorise_permission('170','985');
        $data['page_title'] = "Add  Opening Stock";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $this->load->model('canteen/general/common_model'); 
        $data['category_lists'] = $this->common_model->canteen_category_list();
        $data['item_lists'] = $this->process->item_list();
    
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'item_name'=>"",
                                  'product_id'=>"",
                                  'item_qty'=>"",
                                  'select_item'=>"",
                                  'select_item_qty'=>""
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->process->save();

                echo 1; 
                return false;
                
            }
            else
            {
                 
                $data['form_error'] = validation_errors();  
                print_r($data['form_error']);die;
            }     
        }
     //print_r($data['form_error']);die();
       $this->load->view('canteen/process/add',$data);       
    }
    
    public function edit($id="")
    {
        //unauthorise_permission('170','985');
        $data['page_title'] = "Edit  Opening Stock";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $this->load->model('general/general_model'); 
        $data['category_lists'] = $this->general_model->canteen_category_list();
         $data['item_lists'] = $this->process->item_list($id);
        $data['lists'] = $this->process->item_lists($id);
        $result = $this->process->get_by_id($id);
       
        $data['form_data'] = array(
                                  'data_id'=>$result['id'], 
                                  'item_name'=>$result['item_name'],
                                  'product_id'=>$result['product_id'],
                                  'item_qty'=>$result['item_qty'],
                                  'select_item'=>$result['select_item'],
                                  'select_item_qty'=>$result['select_item_qty']
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->process->save();

                echo 1; 
                return false;
                
            }
            else
            {
                 
                $data['form_error'] = validation_errors();  
                print_r($data['form_error']);die;
            }     
        }
     //print_r($data['form_error']);die();
       $this->load->view('canteen/process/add',$data);       
    }

 private function _validate()     {
    
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('item_name', 'Item Name', 'trim|required'); 
          $this->form_validation->set_rules('item_qty', 'Item quantity', 'trim|required'); 
         // $this->form_validation->set_rules('quantity', 'Item quantity', 'trim|required|callback_check_stock_unit'); 
        
        if ($this->form_validation->run() == FALSE)
        {  
            
             $item_code= generate_unique_id(68); 
             $data['form_data'] = array(
                                  'item_name'=>$post['item_name'],
                                  'product_id'=>$post['product_id'],
                                  'item_qty'=>$post['item_qty'],
                                  'select_item'=>$post['select_item'],
                                  'select_item_qty'=>$post['select_item_qty'],
                                  'status'=>1,
                                       ); 
             return $data['form_data'];
         }   
   }
  function get_product_values($vals="")
    {

        if(!empty($vals))
        {
           // $this->load->model('canteen/products/products_model','products');
            $result = $this->process->get_product_values($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
        function product_combo_list($prod_id="",$qty="",$product_pre_qty="")
    {
      
     $product_combo_list=$this->process->get_product_combo($prod_id);
    
        if(!empty($qty))
        {
            $qty=$qty;
        }
        else{
            $qty=$product_pre_qty;
        }
        if(empty($product_pre_qty))
        {
            $product_pre_qty=1;
            
        }
       
    
         $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.booked_checkbox').prop('checked', false);
                                  } else {
                                      $('.booked_checkbox').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
 
           if(isset($product_combo_list) && !empty($product_combo_list))
           {
              
              $i = 1;
              
              foreach($product_combo_list as $purchase_item_list)
              {
                  $qty_formula=($qty*$purchase_item_list['comb_prod_qty'])/$product_pre_qty;
                 
                 $rows .= '<tr id="tr_'.$purchase_item_list['combo_id'].'">
                <td>'.$i.'</td>               
                <td>'.$purchase_item_list['p_name'].'-'.$purchase_item_list['comb_prod_code'].'<input type="hidden" name="comb_prod_code[]" value="'.$purchase_item_list['comb_prod_code'].'" id="comb_prod_code_'.$purchase_item_list['combo_id'].'"/><input type="hidden" name="comb_prod_name[]" value="'.$purchase_item_list['p_name'].'" id="comb_prod_name_'.$purchase_item_list['combo_id'].'"/></td>
                <td>'.$purchase_item_list['comb_prod_qty']*$qty_formula.'<input type="hidden" value="'.$purchase_item_list['comb_prod_qty']*$qty_formula.'" name="comb_prod_qty[]" id="quantity_'.$purchase_item_list['combo_id'].'" /></td>
                <td class="text-center"> <a href="javascript:void(0);" id="del_'.$purchase_item_list['combo_id'].'" onClick="return delete_combo_item('.$purchase_item_list['combo_id'].');" class="btn-custom" title="remove item">Delete</a></td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Particular data not available.</div></td>
                        </tr>';
           }

           $response_data = array('html_data'=>$rows);
        $json = json_encode($response_data,true);
        echo $json;
           
    }
   public function get_code($product_id,$qty)
   {
       $product_detail=$this->process->get_code($product_id);
       $response_data = array('product_name'=>$product_detail['product_name'],'product_code'=>$product_detail['product_code'],'qty'=>$qty,'product_id'=>$product_id);
        print_r(json_encode($response_data));
      
   }
   public function delete($id="")
    {
       //unauthorise_permission('73','463');
       if(!empty($id) && $id>0)
       {
           $result = $this->process->delete($id);
            $response = "Process successfully deleted.";
           echo $response;
       }
    }
   
  function deleteall()
    {
       //unauthorise_permission('73','463');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->process->deleteall($post['row_id']);
            $response = "Process successfully deleted.";
            echo $response;
        }
    }
}
?>