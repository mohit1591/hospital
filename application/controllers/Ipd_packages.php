<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_packages extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_packages/ipd_packages_model','ipd_packages');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission(117,720);
        $this->session->unset_userdata('perticular_list'); 
        $data['page_title'] = 'IPD Packages List'; 
        $this->load->view('ipd_packages/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(117,720);
        $users_data = $this->session->userdata('auth_users');
        $this->session->unset_userdata('particular_ids');
        $this->session->unset_userdata('package_cost');
        $this->session->unset_userdata('delete_particular_ids');
        $list = $this->ipd_packages->get_datatables();

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_packages) {
        //print_r($ipd_packages);
        $no++;
        $row = array();
        if($ipd_packages->status==1)
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
        if($users_data['parent_id']==$ipd_packages->branch_id){

              $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$ipd_packages->id.'">'.$check_script;

        }
        else{
         $row[]='';
        }
        $row[] = $ipd_packages->name; 
        $row[] = $ipd_packages->amount;
        $row[] = $status;



        //$row[] = date('d-M-Y H:i A',strtotime($ipd_packages->created_date)); 
        $users_data = $this->session->userdata('auth_users');
        $btnedit='';
        $btndelete='';
        if($users_data['parent_id']==$ipd_packages->branch_id){
         if(in_array('722',$users_data['permission']['action'])){
              $btnedit = '<a onClick="return edit_ipd_packages('.$ipd_packages->id.')" class="btn-custom" href="javascript:void(0)" style="'.$ipd_packages->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
         }
         if(in_array('723',$users_data['permission']['action'])){
              $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_packages('.$ipd_packages->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
         }
        }
        $row[] = $btnedit.$btndelete;


        $data[] = $row;
        $i++;
        }

        $output = array(
                  "draw" => $_POST['draw'],
                  "recordsTotal" => $this->ipd_packages->count_all(),
                  "recordsFiltered" => $this->ipd_packages->count_filtered(),
                  "data" => $data,
          );
        //output to json format
        echo json_encode($output);
    }
  
    public function add()
    {
       unauthorise_permission(117,721);
        $particular_ids = $this->session->userdata('particular_ids');
        // $data['departments_list'] = $this->ipd_packages->particular_list();
        $data['page_title'] = "Add IPD Package";  
        $post = $this->input->post();
        $vendor_code = generate_unique_id(6);
        $data['form_error'] = []; 
        $data['added_particular'] = '';
        $this->load->model('ipd_perticular/ipd_perticular_model','ipd_particulars');
        $data['particulars']=$this->ipd_particulars->get_particulars();
        $data['particular_list']='';
        $data['form_data'] = array(
                                'pack_id'=>"",
                                'package_name'=>'',
                                'particular_amount'=>"",
                                'amount' =>'',
                                'status'=>'1',
                            );    


        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {  
              $this->ipd_packages->save($post);
              $this->session->set_flashdata('success','particular successfully added.');
              redirect('ipd_packages');
            }
            else
            {
              $particular_ids = $this->session->userdata('particular_ids');
              if(isset($particular_ids) && !empty($particular_ids))
              {
                 $table = "";
                 foreach($particular_ids as $particular_id)
                 {
                     $check_script = 
                                 "<script>$('#addparticularselectAll').on('click', function () { 
                                                if ($(this).hasClass('allChecked')) {
                                                     $('.particularchecklist').prop('checked', false);
                                                } else {
                                                     $('.particularchecklist').prop('checked', true);
                                                }
                                                $(this).toggleClass('allChecked');
                                           });
                                 </script>";
                    $particular_data = get_particular($particular_id);
                    if(!empty($tes_data)){
                       $table.= '<tr><td align="center"><input type="checkbox" name="employee[]" class="particularchecklist" value="'.$particular_data['id'].'"></td><td>'.$particular_data['particular_name'].'</td><td>'.$particular_data['company_name'].'</td></tr>'.$check_script;
                    }
                 }
                 $data['added_particular'] = $table;
              }
               
             

                
               
                $data['form_error'] = validation_errors();  
               
                $result = $this->load->view('ipd_packages/add_packages',$data,true);
                echo $result;
                die;
            }     
        }
         
        $this->load->view('ipd_packages/add_packages',$data);  
    }
     
    public function edit($id="")
    {
        unauthorise_permission(117,722);
        $perticular_list = $this->session->userdata('perticular_list');
        //echo "<pre>";print_r($perticular_list);die;
        if(isset($id) && !empty($id) && is_numeric($id)){ 
             $data['added_particular'] = '';
             $data['particular_list']=''; 
             $test_arr = [];
             $table='';
            
             //echo "<pre>";print_r($this->session->userdata('particular_list'));die; 
             $post = $this->input->post();
             $result = $this->ipd_packages->get_by_id($id);   
             $data['added_particular'] = $table;
             $data['page_title'] = "Update IPD Package";  
             $data['form_error'] = ''; 
             $data['form_data'] = array(
                  'pack_id'=>$result['id'],
                  'package_name'=>$result['name'],
                  'particular_amount'=>"",
                  'amount' =>$result['amount'],
                  'status'=>$result['status'],
             );
             $this->load->model('ipd_perticular/ipd_perticular_model','ipd_particulars');
             $data['particulars']=$this->ipd_particulars->get_particulars(); 
             if(isset($post) && !empty($post))
             {   

                  $data['form_data'] = $this->_validate();
                  if($this->form_validation->run() == TRUE)
                  {
                       $this->ipd_packages->save($post);
                       $this->session->set_flashdata('success','ipd package successfully updated.');
                       redirect('ipd_packages');
                  }
                  else
                  { 
                       $pack=1;
                       $data['form_error'] = validation_errors(); 
                       $result = $this->load->view('ipd_packages/add_packages',$data,true);    
                       echo $result;
                       die;
                  }     
             }
             else
             {
               if(!isset($particular_ids))
               {
                    $selected_particular_result = $this->ipd_packages->selected_particular($id);
                   
                    $selected_particular_ids = array();
                    if(!empty($selected_particular_result))
                    {             
                         foreach($selected_particular_result as $particular_data)
                         {   
                             $particular_arr[$particular_data->pert_id] =  array('particular_id'=>$particular_data->pert_id, 'particuler_text'=>$particular_data->particular_name, 'particular_amount'=>$particular_data->particuler_amount);
                         }

                         $this->session->set_userdata('perticular_list',$particular_arr);   
                          
                    }
               } 
             }
            
             $this->load->view('ipd_packages/add_packages',$data); 
          
        }

    }
     
    private function _validate()
    {
        $post = $this->input->post();     
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('package_name', 'name', 'trim|required|callback_check_ipd_package'); 
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
       
        $this->form_validation->set_rules('test', 'test', 'trim|callback_check_particular_id');
        $this->form_validation->set_rules('status', 'status', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(6); 
             $data['form_data'] = array(
                                  'pack_id'=>$post['pack_id'],
                                  'package_name'=>$post['package_name'],
                                  'amount' =>$post['amount'],
                                  'particular_amount'=>"",
                                  'status'=>$post['status']
                              );    
            return $data['form_data'];
        }   
    }
   
    public function check_particular_id()
    {
       $particular_list = $this->session->userdata('perticular_list');
       //print_r($particular_list);die;
       if(isset($particular_list) && !empty($particular_list))
       {
          return true;
       }
       else
       { 
          $this->form_validation->set_message('check_particular_id', 'Please add atleast one particular.');
          return false;
       }
    }

    public function check_ipd_package($str){
 
            $post = $this->input->post();
            if(!empty($post['package_name']))
            {
                  $this->load->model('general/general_model','general'); 
                  if(!empty($post['pack_id']) && $post['pack_id']>0)
                  {
                        return true;
                  }
                  else
                  {
                        $packagedata = $this->general->check_ipd_package($post['package_name']);
                        if(empty($packagedata))
                        {
                              return true;
                        }
                        else
                        {
                              $this->form_validation->set_message('check_ipd_package', 'The package name already exists.');
                              return false;
                        }
                  }  
            }
            else
            {
                  $this->form_validation->set_message('check_ipd_package', 'The package name field is required.');
                  return false; 
            } 
      }

    public function delete($id="")
    {
       unauthorise_permission(117,723);
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_packages->delete($id);
           $response = "IPD Packages successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(117,723);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_packages->deleteall($post['row_id']);
            $response = "IPD Packages successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_packages->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Vendor']." detail";
        $this->load->view('ipd_packages/view',$data);     
      }
    }  
      
    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(117,724);
        $data['page_title'] = 'IPD Packages list';
        $this->load->helper('url');
        $this->load->view('ipd_packages/archive',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission(117,724);
        $this->load->model('ipd_packages/ipd_packages_archive_model','ipd_packages_archieve'); 
        $users_data = $this->session->userdata('auth_users');
     

            $list = $this->ipd_packages_archieve->get_datatables();
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_packages) { 
            $no++;
            $row = array();
            if($ipd_packages->status==1)
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
            if($users_data['parent_id']==$ipd_packages->branch_id){
            
                     $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_packages->id.'">'.$check_script; 
              
            }else{
               $row[]='';
            }
              $row[] = $ipd_packages->name; 
            $row[] = $ipd_packages->amount;
            $row[] = $status;
           
          
           // $row[] = date('d-M-Y H:i A',strtotime($ipd_packages->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if($users_data['parent_id']==$ipd_packages->branch_id){
               if(in_array('726',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_ipd_packages('.$ipd_packages->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
               if(in_array('725',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_packages->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_packages_archieve->count_all(),
                        "recordsFiltered" => $this->ipd_packages_archieve->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission(117,726);
        $this->load->model('ipd_packages/ipd_packages_archive_model','ipd_packages_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_packages_archieve->restore($id);
           $response = "IPD Packages successfully restore in IPD Packages List.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(117,726);
       $this->load->model('ipd_packages/ipd_packages_archive_model','ipd_packages_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_packages_archieve->restoreall($post['row_id']);
            $response = "IPD Packages successfully restore in IPD Packages List.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(117,725);
       $this->load->model('ipd_packages/ipd_packages_archive_model','ipd_packages_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_packages_archieve->trash($id);
           $response = "IPD Packages successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(117,725);
       $this->load->model('ipd_packages/ipd_packages_archive_model','ipd_packages_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_packages_archieve->trashall($post['row_id']);
            $response = "IPD Packages successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

     
     public function reset_all_session_data($id=''){
          // if(empty($id)){
            
          //      $this->session->unset_userdata('departement_id');
          //      $this->session->unset_userdata('interpretation');
          //      $this->session->unset_userdata('test_head_ids');
          // }else{
                
          // }
     }
     //get child test to be added from test_head_id
     public function get_added_particular_list($pack='')
     {
          $post = $this->input->post();
          $list = array();
          $list = $this->ipd_packages->get_added_particular_list();
          $particular_ids =  $this->session->userdata('particular_ids');
          $data = '';
          $total_num = count($list);
          if($total_num<1){
               $data  = '<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>'; 
          }
          else{
               $count=1;
               for($i=0;$i<$total_num;$i++) {
                    // print_r($Vendor);die;
                    $check_script='';
                    if($i==$total_num-1){
                         $check_script = 
                                   "<script>
                                        $('#addparticularselectAll').on('click', function () 
                                        { 
                                             if($(this).hasClass('allChecked')) 
                                             {
                                                  $('.particularchecklist').prop('checked', false);
                                             } else {
                                                  $('.particularchecklist').prop('checked', true);
                                             }
                                             $(this).toggleClass('allChecked');
                                        })
                                   </script>";
                    }
             
            $data  = $data.'<tr><td align="center"><input type="checkbox" name="employee[]" class="particularchecklist" value="'.$list[$i]['id'].'"></td><td>'.$count.'</td><td>'.$list[$i]['particular'].'</td><td>'.$list[$i]['charge'].'</td></tr>'.$check_script; 
            $count++;
                    
               }
          }
 
      if(!empty($pack))
      {
          return $data;
              
      }
      else
      {
           echo  $data;
      }
          
     }
     //get child test after adding 
     public function listalladdedparticular(){
          $post = $this->input->post();  

          $particular_ids = $this->session->userdata('particular_ids');
          if(empty($particular_ids)){
               $particular_ids = array();
          }
          if(!empty($post)){
              
               $result = $this->ipd_packages->addallparticular($post['row_id']);

                      $data = '';
            
               $total_num = count($result);
             

               if($total_num<1){
                  
                    $data  = '<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>'; 
               }

               else{
                    for($i=0;$i<$total_num;$i++) {
                         // print_r($Vendor);die;
                         $check_script='';
                         if($i==$total_num-1){
                          }
                         array_push($particular_ids,$result[$i]['id']);
                       $data= $data.'<tr><td align="center"><input type="checkbox" name="employee[]" class="particularchecklist" value="'.$result[$i]['id'].'"></td><td>'.$result[$i]['particular_name'].'</td><td>'.$result[$i]['company_name'].'</td></tr>';
                        
                    }
               }
                $this->session->set_userdata('particular_ids',$particular_ids);
              

             
               
               
               
                
               echo $data;
          }
     }
     
     //delete all added child test list
     public function deletealllistedparticular()
     { 
          $post = $this->input->post();
          $perticular_list = $this->session->userdata('perticular_list'); 
          if(isset($post) && !empty($post))
          {  
            foreach($post['row_id'] as $rows)
            {
              if(isset($perticular_list[$rows]))
              {
                 unset($perticular_list[$rows]);
              } 
            } 
            $this->session->set_userdata('perticular_list',$perticular_list);  
          }
     }

     //get all test heads according to selected departement
     public function get_test_heads($dept_id=''){
          $post = $this->input->post();
          $test_id = $this->session->userdata('test_head_ids');
          $result= array();
          if(array_key_exists('departments_id',$post)){
              
               $this->session->set_userdata('departement_id',$post['departments_id']);
               $result = $this->ipd_packages->get_test_heads($post['departments_id']);
          }
          elseif(!empty($dept_id)){
             
               $this->session->set_userdata('departement_id',$dept_id);
               $result = $this->ipd_packages->get_test_heads($dept_id);
          }
        
          $data='';
          for($i=0;$i<count($result);$i++){
               $selected = '';
               if($result[$i]->id==$test_id){
                    $selected = 'selected="selected"';
               }
               $data = $data.'<option value="'.$result[$i]->id.'" '.$selected.'>'.$result[$i]->test_heads.'</option>';
          }
          if(array_key_exists('departments_id',$post)){
                print_r($data);
          }
          else{
               return $data;
          }
          
     }
     public function save_sort_order_data(){
          $post = $this->input->post();
          $id = $post['test_id'];
          $sort_order_value = $post['sort_order_value'];
          if(!empty($id) && !empty($sort_order_value)){
               $result = $this->ipd_packages->save_sort_order_data($id,$sort_order_value);
               echo $result;
               die;
          }

     }
     
     public function add_package_particuler()
     {
       $post = $this->input->post();
       $perticular_list = $this->session->userdata('perticular_list');
       if(!empty($post))
       {
         if(isset($perticular_list))
         {
            $perticular_list = $perticular_list;   
         }
         else
         {
            $perticular_list = [];
         }   
         $perticular_list[$post['particular_id']] = $post;
         $this->session->set_userdata('perticular_list',$perticular_list);
      }
     }

     public function get_selected_particular()
     {  
      $perticular_list = $this->session->userdata('perticular_list'); 
      if(!empty($perticular_list) && !empty($perticular_list))
      { 
       $i =1; 
       $data = '';
       foreach($perticular_list as $key=>$particular)
       {   
        $data.= '<tr><td align="center"><input type="checkbox" name="employee[]" class="particularchecklist" value="'.$key.'"></td><td>'.$i.'</td><td>'.$particular['particuler_text'].'</td><td>'.$particular['particular_amount'].'</td></tr>'; 
        $i++;
       }
         
      }
      else
      {
         $data  = '<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>'; 
      } 
		  echo $data;

     }

     public function clc_package_price()
     {
         $perticular_list = $this->session->userdata('perticular_list');
         $total_amount = '0.00';
         if(!empty($perticular_list))
         {
           foreach($perticular_list as $perticular)
           {
             $total_amount = $total_amount+$perticular['particular_amount'];
           }
         }
         echo number_format($total_amount,2, '.', '');
     }

     public function get_added_particular_dropdown()
     {
          $particular_dropdown_list = $this->ipd_packages->get_updated_particular_dropdown();
          $particular_ids = $this->session->userdata('particular_ids');
          $count =0;
          $selected_particular = '';
          if(empty($particular_ids))
          {
               $particular_ids = array();
          }
          $dropdown = '<option value="">Select Particular</option>'; 
          if(!empty($particular_dropdown_list)){
               foreach($particular_dropdown_list as $particular_dropdown){
                    if(!in_array($particular_dropdown->id,$particular_ids))
                    {
                         if($count==0)
                         {
                            
                            $selected_particular = 'selected="selected"';
                         }
                         echo $count;

                         $dropdown .= '<option value="'.$particular_dropdown->id.'" '.$selected_particular.'>'.$particular_dropdown->particular.'</option>';
                    }
                    $count++;
               }
          } 
          echo $dropdown; 
     }
     public function get_deleted_particular_dropdown(){
          $particular_dropdown_list = $this->ipd_packages->get_updated_particular_dropdown();
          $particular_ids = $this->session->userdata('particular_ids');
          $count =0;
           $selected_particular = '';
          if(empty($particular_ids))
          {
               $particular_ids = array();
          }
          $dropdown = '<option value="">Select Particular</option>'; 
          if(!empty($particular_dropdown_list)){
               foreach($particular_dropdown_list as $particular_dropdown){
                    if(!in_array($particular_dropdown->id,$particular_ids))
                    {
                         if($count==0)
                         {
                            $selected_particular = 'selected="selected"';
                         }
                         $dropdown .= '<option value="'.$particular_dropdown->id.'" '.$selected_particular.'>'.$particular_dropdown->particular.'</option>';
                    }
                    $count++;
               }
          } 
          echo $dropdown; 
     }
     public function get_added_package_cost()
     {
      
          $particular_ids = $this->session->userdata('particular_ids');

          $particular_list = get_particular_list($particular_ids);
         
          $updated_package_cost = 0;
          if(!empty($particular_list))
          {
            
               foreach ($particular_list as $particular) {
                    $updated_package_cost = $updated_package_cost + $particular->charge; 
               }
          }
        
          
          $this->session->set_userdata('package_cost',$updated_package_cost);
         
          echo $updated_package_cost;
     }

     public function get_deleted_package_cost()
     {

          $particular_ids = $this->session->userdata('delete_particular_ids');

          $particular_list = get_particular_list($particular_ids);
         

          $current_package_cost = $this->session->userdata('package_cost');
         
         
          if(empty($current_package_cost))
          {
               $current_package_cost=0;
          }
          if(!empty($particular_list))
          {

               foreach ($particular_list as $particular) 
               {
                   
                    $current_package_cost = $current_package_cost - $particular->charge; 
               }
          }
          $updated_package_cost = number_format($current_package_cost,2);
          $this->session->set_userdata('package_cost',$updated_package_cost);
          $current_package_cost = $this->session->userdata('package_cost');
         
          echo $updated_package_cost;

     }
 
}